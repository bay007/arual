$.ajaxSetup({ cache:false });
var timeOut=50;
function seleccionaTAGSelect(idSelect,datoABuscar){
$(idSelect+" option").each(function(){
	if($(this).data("texto")==datoABuscar){
	$(idSelect).val($(this).val());
	}
});
}

function formularioDesdeTabla(fila,idFormulario){
$(idFormulario).parent().parent().parent().addClass('animated pulse');
		$.each( fila, function( key, value ) {
				$(idFormulario+' :input#'+key).val(value);
		
				if(key=="activo"){
				$(idFormulario+' :checkbox#'+key).prop("checked",value=="Si"?true:false);
				}
				else{
				$(idFormulario+' :checkbox#'+key).prop("checked",value=="No"?true:false);
				}
				
				if(key=="hospital"){
				seleccionaTAGSelect(idFormulario+" select#cmbHospitalesCatalogo",value);
				}
				
				if(key=="nombre_curso"){
				seleccionaTAGSelect(idFormulario+" select#cmbCursosCatalogo",value);
				}
				
				if(key=="idadministrandoInscripciones"){
						$.ajax({
							url : $(idFormulario).attr("action")+"?idadministrandoInscripciones="+ $("#idadministrandoInscripciones").val(),
							type: "GET",
							dataType:'json',
							success:function(data, textStatus, jqXHR) 
							{
								if(jqXHR.status==200&&jqXHR.statusText=="OK")
								{
									if(data[0].credencial_aspirante!=""){
										$("#credencial_aspirante").attr("href","inscripciones/aspirantes/"+data[0].credencial_aspirante);
										$("#credencial_aspirante").removeAttr("disabled");
									}else{
										$("#credencial_aspirante").attr("disabled","true");
										alert("Sin credencial presentada.");	
									}
								}else{						//if fails      

								}
								// setTimeout('wait("'+idTablaAsociada+'")',timeOut);
							},
							error: function(jqXHR, textStatus, errorThrown) 
							{
							$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
							$('#mensajes').modal('show');
								//if fails      
							}
						});
//				
				}
				if(key=="idadministrandoInscripciones_pago"){
						$.ajax({
							url : $(idFormulario).attr("action")+"?idadministrandoInscripciones_pago="+ $("#idadministrandoInscripciones_pago").val(),
							type: "GET",
							dataType:'json',
							success:function(data, textStatus, jqXHR) 
							{
								if(jqXHR.status==200&&jqXHR.statusText=="OK")
								{
									if(data[0].credencial_aspirante!=""){
										$("#boucher_aspirante_pago").attr("href","inscripciones/comprobantes_pago/"+data[0].boucher_aspirante_pago);
										$("#boucher_aspirante_pago").removeAttr("disabled");
									}else{
										$("#boucher_aspirante_pago").attr("disabled","true");
										alert("Sin credencial presentada");	
									}
								}else{						//if fails      

								}
								// setTimeout('wait("'+idTablaAsociada+'")',timeOut);
							},
							error: function(jqXHR, textStatus, errorThrown) 
							{
							$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
							$('#mensajes').modal('show');
								//if fails      
							}
						});
//				
				}
		});
	$(idFormulario).parent().parent().parent().one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		$(idFormulario).parent().parent().parent().removeClass('animated pulse');
	});
}

////////EDICION DEL CATALOGO DE CURSOS////////////////////////////////////////////////////////////////////////////////

function iniciarTabla(idTabla,idFormulario){
	$(idTabla).bootstrapTable({
		//url: 'data.json'
			onClickRow: function (row) {
					var row_=row;
					var Referencia;
				  $(idTabla+' tr').each(function(index, element){
				  //$(idTabla+' tr').removeClass("info");
					Referencia = $(element).find("td").eq(0).html();
						//if(Referencia==row_.id||Referencia==row_.idadministrandoInscripciones||Referencia==row_.idadministrandoPagos){
						if(index>=1){
							if(Referencia==row_.id){
								$(element).addClass("info");
							}else{
									if(Referencia==row_.idadministrandoInscripciones){
										$(element).addClass("info");
									}else{
										if(Referencia==row_.idadministrandoInscripciones_pago){
										$(element).addClass("info");
										}else{
											$(element).removeClass("info");
										}
									}
							}
						}else{
							$(element).removeClass("info");
						}
					});
				
				var obj=JSON.stringify(row);
				//var frm = $("#formularioCursos");
				formularioDesdeTabla(row,idFormulario);
				if(idFormulario=="#frmadministrandoInscripciones"){
					if($(idFormulario+" input#sello").val()==""){
						$("#btnAceptaradministrandoInscripciones").text("Aceptar solicitud");
						$("#btnEliminaradministrandoInscripciones").removeAttr("disabled");
					}else{
						$("#btnAceptaradministrandoInscripciones").text("Reenviar email");
						$("#btnEliminaradministrandoInscripciones").attr("disabled","true");
					}
				}else{
					if($(idFormulario+" input#verificado").val()=="Si"){
					$("#btnAceptaradministrandoInscripciones_pago").text("Reenviar email");
					$("#btnEliminaradministrandoInscripciones_pago").attr("disabled","true");
					$("#btnAceptaradministrandoInscripciones_pago").removeAttr("disabled");
					}else{
						if($(idFormulario+" input#verificado").val()=="No"){
						$("#btnAceptaradministrandoInscripciones_pago").text("Aceptar Boucher");
						$("#btnEliminaradministrandoInscripciones_pago").removeAttr("disabled");
						$("#btnAceptaradministrandoInscripciones_pago").removeAttr("disabled");
						}else{
							$("#btnAceptaradministrandoInscripciones_pago").attr("disabled","true")
							$("#btnEliminaradministrandoInscripciones_pago").attr("disabled","true")
						}
					}
				}
				
						//console.dir(response);      // for debug
		}
	});
}

function GuardarActualizar(idBotonGuardar,idTablaAsociada){
	$(idBotonGuardar).click(function(e){
	e.preventDefault(); //STOP default action
	var formulario_id=$(idBotonGuardar).parent().attr('id');
	var accion="";
	if ($("#"+formulario_id+" input#id").val()==""){
	accion="create";
	}else{
	accion="update";
	}
		$("#"+formulario_id+' input#accion').val(accion);
			var postData = $("#"+formulario_id).serializeArray();
			var formURL = $("#"+formulario_id).attr("action");
			var a=$("#"+formulario_id+' :checkbox').is(":checked");
			postData.push({name: "activo", value:a?"Si":"No"});
			var nuevoElemento=true;
			
			postData.forEach(function(e){
				if((e.name=="nombre_curso"&&(e.value==""))){
				nuevoElemento=false;
				alert('Escriba un nombre para el curso.');
				}
				if(e.name=="contenido"&&(e.value=="")){
				nuevoElemento=false;
				alert('Escriba el contenido del curso.');
				}
				if(e.name=="duracion"&&(e.value=="")){
				nuevoElemento=false;
				alert('Escriba la duración del curso.');
				}
				if(e.name=="requisitos"&&(e.value=="")){
				nuevoElemento=false;
				alert('Esciba los requisitos.');
				}
				if(e.name=="publico_dirigido"&&(e.value==""||e.value=="-1")){
				nuevoElemento=false;
				alert('Seleccione a quién va dirigido el curso.');
				}
			;})
			
		if((nuevoElemento)){
				$.ajax({
					url : formURL,
					type: "POST",
					data : postData,
					success:function(data, textStatus, jqXHR){
					// console.dir(textStatus);
					// console.dir(data);
					// console.dir(jqXHR);
						if(jqXHR.status==200&&jqXHR.statusText=="OK"&&JSON.parse(data).Estado=="OK")
						{
							$("#body-mensajes").html('<div class="alert alert-success" role="alert">'+JSON.parse(data).Respuesta+'</div>');
							resetFormulario("#"+formulario_id);
						}
						if(JSON.parse(data).Estado=="ERROR"){						//if fails      
						$("#body-mensajes").html('<div class="alert alert-warning" role="alert">'+JSON.parse(data).Respuesta+'</div>');
						}
						// setTimeout('wait("'+idTablaAsociada+'")',timeOut);
						var tablaa=$(".tblGENERAL");recargarCombos();
						tablaa.each(function(){
							setTimeout('wait("#'+$(this).attr("id")+'")',timeOut);
						;})
						$('#mensajes').modal('show');
						//data: return data from server
					},
					error: function(jqXHR, textStatus, errorThrown) 
					{
					$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
					$('#mensajes').modal('show');
						//if fails      
					}
				});
			}else{
			// alert("Por favor, complete todos los campos antes de continuar.");
			}
		
		//});
	});
} 

function resetFormularioBoton(idBotonReset){
$(idBotonReset).click(function(e){
e.preventDefault(); //STOP default action
$("tr").removeClass("info");
	var formulario=$(idBotonReset).parent();
	var entradas=formulario.find(":input");
	entradas.each (function(){
	  //this.reset();
	  $(this).val("");
	});
$("#"+formulario.attr('id')+' :checkbox').prop("checked",false);
$("#"+formulario.attr('id')+' a').attr("href","#");
$('select').val(-1);
});

}

function resetFormulario(idFormulario){
	var entradas=$(idFormulario).find(" :input");
	entradas.each (function(){
	  //this.reset();
	  $(this).val(null);
	});
$(idFormulario+' :checkbox').prop("checked",false);
$('select').val(-1);
}

function EliminarElemento(idBotonEliminar,idTablaAsociada,idBotonEliminarModal){
var formulario_id=$(idBotonEliminar).parent().attr('id');
var modal_id=$(idBotonEliminarModal).parent().parent().parent().parent().attr("id");
var postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
var formURL = $("#"+formulario_id).attr("action");

	$(idBotonEliminar).click(function(e){
		postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
		e.preventDefault(); //STOP default action
	
	if(postData[1].value!=""){
		$("#"+modal_id).modal('show');
		}else{	
		alert("Debe seleccionar un elemento para eliminar.");
		}
	});	
		
	$(idBotonEliminarModal).click(function(e){	
	$(idBotonEliminar).button('loading');
	var accion="delete";
	$('#'+modal_id).modal('hide');
		$("#"+formulario_id+' input#accion').val(accion);
			postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
			if(modal_id=="confirmacionesRechazarSolicitud"){
			postData.push({name:'motivo',value:$('#motivo').val()});	
			}
			$.ajax({
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR) 
				{
				// console.dir(textStatus);
				// console.dir(data);
				// console.dir(jqXHR);
					if(jqXHR.status==200&&jqXHR.statusText=="OK"&&JSON.parse(data).Estado=="OK")
					{
					 $("#body-mensajes").html('<div class="alert alert-success" role="alert">'+JSON.parse(data).Respuesta+'</div>');
					 var tablaa=$(".tblGENERAL");
					 tablaa.each(function(){
						 setTimeout('wait("#'+$(this).attr("id")+'")',timeOut);
						 });
					 recargarCombos();
					}else{						//if fails      
					$("#body-mensajes").html('<div class="alert alert-warning" role="alert">'+JSON.parse(data).Respuesta+'</div>');
					}
					resetFormulario("#"+formulario_id);
					$('#mensajes').modal('show');
					$(idBotonEliminar).button('reset');
					$("tr").attr("class","")
					//data: return data from server
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
				$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
				$('#mensajes').modal('show');
					//if fails      
				}
			});
		});
} 
//////////////////EDICION DEL CATALOGO DE CENTROS AFILIADOS////////////////////////////////////////////////////
function CargaSelectHospitales(IdSelect){
	$.ajax({
		type: "GET",
		url: 'sistema/CatalogoCentros.php?accion=selectCMB&id=',
	   // data: {'categoryID': $("#category").val(),'isAjax':true},
		dataType:'json',
		success: function(data) {
		   var select = $(IdSelect);
		select.empty();		  
		  options = '<option value="-1">Seleccion</option>';
		   for(var i=0;i<data.length; i++)
		   {
			options += "<option data-texto='"+data[i].hospital+"' value='"+data[i].id+"'>"+ data[i].hospital+"</option>";              
		   }
		   select.append(options);
		}
	});
}

function resetFormularioMapas(idFormulario){
resetFormulario(idFormulario);
marker.setPosition(centro);
map.setCenter(centro);
map.setZoom(mapProp.zoom);
$(idFormulario+'>select').val(-1);
}

function formularioDesdeSelect(idSelect,idFormulario){
var formURL = $(idFormulario).attr("action");
	$(idSelect).on('change', function (e) {
	var value=$(idSelect).val();
	if(value=="-1"){
	resetFormularioMapas(idFormulario);
	return;
	}
	
	
	$(idFormulario).parent().parent().addClass('animated pulse');
		$.ajax(
		{
			url : formURL+"?accion=-&id="+value,
			type: "GET",
			
			//data : "?accion=-&id="+value,
			success:function(data, textStatus, jqXHR) 
			{
				var row= $.parseJSON(data);
				var fila=row[0];
				$.each(fila, function(key, value){
					//console.log(key + ": " + fila[key]);
				$(idFormulario+' :input#'+key).val(fila[key]);
				
				if(key=="activo"){
				$(idFormulario+' :checkbox#'+key).prop("checked",fila[key]=="Si"?true:false);
				}
				else{
				$(idFormulario+' :checkbox#'+key).prop("checked",fila[key]=="Si"?true:false);
				}
				
				});
				
				var centro=new google.maps.LatLng(document.getElementById("latitud").value ,document.getElementById("longitud").value);
				marker.setPosition(centro);
				map.setCenter(centro);
				//map.setZoom(mapProp.zoom);
				
			// console.dir(textStatus);
			// console.dir(data);
			// console.dir(jqXHR);
			}
		});

		$(idFormulario).parent().parent().one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
			$(idFormulario).parent().parent().removeClass('animated pulse');
		});
	});
}

function GuardarActualizarCentro(idBotonGuardar){
	$(idBotonGuardar).click(function(e){
	e.preventDefault(); //STOP default action
	var formulario_id=$(idBotonGuardar).parent().attr('id');
	var accion="";
	if ($("#"+formulario_id+" input#id").val()==""){
	accion="create";
	}else{
	accion="update";
	}
		
		$("#"+formulario_id+' input#accion').val(accion);
		/*$("#"+formulario_id).submit(function(e)
		{*/
			var postData = $("#"+formulario_id).serializeArray();
			var formURL = $("#"+formulario_id).attr("action");
			
			var a=$("#"+formulario_id+' :checkbox').is(":checked");
			//var logotipo=$("#"+formulario_id).find("input[type=hidden]").val();
			postData.push({name: "activo", value:a?"Si":"No"});
			//postData.push({name: "logotipo", value:logotipo});
			var nuevoElemento=true;
			
			postData.forEach(function(e){
				if((e.name=="hospital"&&(e.value==""))){
				nuevoElemento=false;
				alert('Escriba un nombre para el nuevo centro ARUAL.');
				}
				if((e.name=="latitud"||e.name=="longitud")&&(e.value=="")){
				nuevoElemento=false;
				alert('Defina en el mapa la ubicación del centro.');
				}
				if(e.name=="direccion"&&(e.value=="")){
				nuevoElemento=false;
				alert('Proporcione una dirección.');
				}
				if(e.name=="contacto"&&(e.value=="")){
				nuevoElemento=false;
				alert('Defina una persona de contacto.');
				}
				if(e.name=="telefono"&&(e.value=="")){
				nuevoElemento=false;
				alert('Defina los numeros telefónicos.');
				}
				if(e.name=="email"&&(e.value=="")){
				nuevoElemento=false;
				alert('Defina una dirección de e-mail.');
				}
				if(e.name=="banco"&&(e.value=="")){
				nuevoElemento=false;
				alert('Defina una banco para los depositos.');
				}
				if(e.name=="noCuenta"&&(e.value=="")){
				nuevoElemento=false;
				alert('Defina un numero de cuenta.');
				}
				if(e.name=="fkIDadministrador"&&(e.value=="-1")){
				nuevoElemento=false;
				alert('Defina un administrador para éste centro ARUAL.');
				}
			;})
			
		if((nuevoElemento)){
			$.ajax({
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR) 
				{
				// console.dir(textStatus);
				// console.dir(data);
				// console.dir(jqXHR);
					if(jqXHR.status==200&&jqXHR.statusText=="OK"&&JSON.parse(data).Estado=="OK")
					{
						$("#body-mensajes").html('<div class="alert alert-success" role="alert">'+JSON.parse(data).Respuesta+'</div>');
					}else{						//if fails      
						$("#body-mensajes").html('<div class="alert alert-warning" role="alert">'+JSON.parse(data).Respuesta+'</div>');
					}
						resetFormularioMapas("#"+formulario_id);
						$('#mensajes').modal('show');
						recargarCombos();

					//data: return data from server
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
				$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
				$('#mensajes').modal('show');
					//if fails      
				}
			});
			}else{
			// alert("Por favor, complete todos los campos del nuevo centro antes de continuar.");
			}
		
		//});
	});} 

function CargaSelectPublicoDirigido(IdSelect){
	$.ajax({
		type: "GET",
		url: 'sistema/CatalogoCursos.php?accion=selectCMBPublico',
	   // data: {'categoryID': $("#category").val(),'isAjax':true},
		dataType:'json',
		success: function(data) {
		   var select = $(IdSelect);
		   options = '<option value="-1">Seleccion</option>';
		   select.empty();      

		   for(var i=0;i<data.length; i++)
		   {
			options += '<option value="'+data[i].publico_dirigido+'">'+ data[i].publico_dirigido+'</option>';
		   }
		   select.append(options);
		}
	});
}	

function CargaSelectAdministradoresDisponibles(IdSelect){
	$.ajax({
		type: "GET",
		url: 'sistema/CatalogoCentros.php?accion=AdministradoresDisponibles&id=',
	   // data: {'categoryID': $("#category").val(),'isAjax':true},
		dataType:'json',
		success: function(data) {
		   var select = $(IdSelect);
		   options = '<option value="-1">Seleccion</option>';
		   select.empty();      

		   for(var i=0;i<data.length; i++)
		   {
			options += "<option value='"+data[i].id+"'>"+ data[i].email+"</option>";
		   }
		   select.append(options);
		}
	});
}
////////////////EDICION de cursos usuario final////////////////////////////////////////////////////

function GuardarActualizarEdicion(idBotonGuardar,idTablaAsociada){
	$(idBotonGuardar).click(function(e){
	e.preventDefault(); //STOP default action
	var formulario_id=$(idBotonGuardar).parent().attr('id');
	var accion="";
	if ($("#"+formulario_id+" input#id").val()==""){
	accion="create";
	}else{
	accion="update";
	}
		
		$("#"+formulario_id+' input#accion').val(accion);
		/*$("#"+formulario_id).submit(function(e)
		{*/
			var postData = $("#"+formulario_id).serializeArray();
			var formURL = $("#"+formulario_id).attr("action");
			
			var a=$("#"+formulario_id+' :checkbox').is(":checked");
			postData.push({name: "activo", value:a?"Si":"No"});
			var nuevoElemento=true;
			
			postData.forEach(function(e){
				if((e.name=="cupo"&&(e.value==""))){
				nuevoElemento=false;
				alert('Escriba el cupo de alumnos.');
				}
				if(e.name=="fkIDCh"&&(e.value==""||e.value=="-1")){
				nuevoElemento=false;
				alert('Seleccione un centro ARUAL.');
				}
				if(e.name=="lespecifico"&&(e.value==""||e.value=="-1")){
				nuevoElemento=false;
				alert('Escriba un lugar específico.');
				}
				if(e.name=="faplicacion"&&(e.value=="Seleccione fecha"||e.value=="-1")){
				nuevoElemento=false;
				alert('Seleccione una fecha.');
				}
				if(e.name=="haplicacion"&&(e.value=="Seleccione hora"||e.value=="-1")){
				nuevoElemento=false;
				alert('Seleccione una hora.');
				}
				if(e.name=="costo"&&(e.value==""||e.value=="-1")){
				nuevoElemento=false;
				alert('Defina un costo para éste curso.');
				}
				if(e.name=="fkIDCc"&&(e.value==""||e.value=="-1")){
				nuevoElemento=false;
				alert('Seleccione un curso.');}
				
				
			;})
			
		if((nuevoElemento)){
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR) 
				{
				
				// console.dir(textStatus);
				// console.dir(data);
				// console.dir(jqXHR);
					if(jqXHR.status==200&&jqXHR.statusText=="OK"&&data==1)
					{
						$("#body-mensajes").html('<div class="alert alert-success" role="alert">El cambio en el centro afiliado ARUAL fue exitoso.</div>');
						$('#mensajes').modal('show');
						// setTimeout('wait("'+idTablaAsociada+'")',timeOut);
						var tablaa=$(".tblGENERAL");recargarCombos();
					tablaa.each(function(){
						setTimeout('wait("#'+$(this).attr("id")+'")',timeOut);
					});
					}else{						//if fails      
					$("#body-mensajes").html('<div class="alert alert-warning" role="alert">Ocurrió un error al actualizar la edición a un curso.</div>');
					}
					resetFormulario("#"+formulario_id);
					$('#mensajes').modal('show');
					//data: return data from server
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
				$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
				$('#mensajes').modal('show');
					//if fails      
				}
			});
			}else{
			// alert("Por favor, complete todos los campos antes de continuar y seleccione un lugar en el mapa.");
			}
		
		//});
	});} 

function CargaSelectCursos(IdSelect){
	$.ajax({
		type: "GET",
		url: 'sistema/CatalogoCentros.php?accion=selectCMBCursos&id=',
	   // data: {'categoryID': $("#category").val(),'isAjax':true},
		dataType:'json',
		success: function(data) {
		   var select = $(IdSelect);
		   options = '<option value="-1">Seleccion</option>';
		   select.empty();      

		   for(var i=0;i<data.length; i++)
		   {
			options += "<option data-texto='"+data[i].nombre_curso+"' value='"+data[i].id+"'>"+ data[i].nombre_curso+"</option>";
		   }
		   select.append(options);
		}
	});
}

function CargaSelectHospitalesActivos(IdSelect){
	$.ajax({
		type: "GET",
		url: 'sistema/CatalogoCentros.php?accion=selectCMBActivos&id=',
	   // data: {'categoryID': $("#category").val(),'isAjax':true},
		dataType:'json',
		success: function(data) {
		   var select = $(IdSelect);
		   options = '<option value="-1">Seleccion</option>';
		   select.empty();      

		   for(var i=0;i<data.length; i++)
		   {
			options += "<option data-texto='"+data[i].hospital+"' value='"+data[i].id+"'>"+ data[i].hospital+"</option>";              
		   }
		   select.append(options);
		}
	});
}

function wait(idTablaAsociada){
$(idTablaAsociada).bootstrapTable('refresh');
console.log('Refreshed...');
}

function recargarCombos(){
CargaSelectHospitalesActivos("#cmbHospitalesCatalogo");
CargaSelectHospitales("#cmbEdicionHospitales");  
CargaSelectCursos("#cmbCursosCatalogo");
CargaSelectAdministradoresDisponibles("#fkIDadministrador");
} 
////////////////Edicion ADMINISTRADORES////////////////////////////////////////////////////
function GuardarActualizarAdministradores(idBotonGuardar,idTablaAsociada){
	$(idBotonGuardar).click(function(e){
	e.preventDefault(); //STOP default action
	var formulario_id=$(idBotonGuardar).parent().attr('id');
	var accion="";
	if ($("#"+formulario_id+" input#id").val()==""){
	accion="create";
	}else{
	accion="update";
	}
		$("#"+formulario_id+' input#accion').val(accion);
			var postData = $("#"+formulario_id).serializeArray();
			var formURL = $("#"+formulario_id).attr("action");
			var a=$("#"+formulario_id+' :checkbox').is(":checked");
			postData.push({name: "activo", value:a?"Si":"No"});
			var nuevoElemento=0;
			
			postData.forEach(function(f){
				if(f.value==""){
				nuevoElemento=nuevoElemento+1;
				}
			;})
			
		if(postData[2].value!=""){
				$.ajax({
					url : formURL,
					type: "POST",
					data : postData,
					success:function(data, textStatus, jqXHR){
					// console.dir(textStatus);
					// console.dir(data);
					// console.dir(jqXHR);
						if(jqXHR.status==200&&jqXHR.statusText=="OK"&&JSON.parse(data).Estado=="OK")
						{
							$("#body-mensajes").html('<div class="alert alert-success" role="alert">'+JSON.parse(data).Respuesta+'</div>');
							resetFormulario("#"+formulario_id);
						var tablaa=$(".tblGENERAL");recargarCombos();
						tablaa.each(function(){
						setTimeout('wait("#'+$(this).attr("id")+'")',timeOut);
						});
						}else{						//if fails      
						$("#body-mensajes").html('<div class="alert alert-warning" role="alert">'+JSON.parse(data).Respuesta+'</div>');
						}
						$('#mensajes').modal('show');
					},
					error: function(jqXHR, textStatus, errorThrown) 
					{
					$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
					$('#mensajes').modal('show');
						//if fails      
					}
				});
			}else{
			alert("Por favor, complete todos los campos antes de continuar.");
			}
		//});
	});
}
////////////////////////////////////Administrando SolicitudesInscripcion///////////////////////////////////////////
function aceptarSolicitud(idBotonGuardar,idTablaAsociada){
	$(idBotonGuardar).click(function(e){
	e.preventDefault(); //STOP default action
	var formulario_id=$(idBotonGuardar).parent().attr('id');
	
	var accion="";
	if ($("#"+formulario_id+" input#idadministrandoInscripciones").val()==""){
	//accion="aceptarSolicitud";
	alert("Seleccione un registro de la tabla");
	}else{

	var a=true;

		if(($(idBotonGuardar).text()=="Aceptar solicitud")&&a){
			a=false;
			accion="aceptarSolicitud";		
		}
		
		if(($(idBotonGuardar).text()=="Reenviar email")&&a){
			a=false;
			accion="ReenviarEmail";		
		}
		
		if(($(idBotonGuardar).text()=="Aceptar Boucher")&&a){
			a=false;
			accion="aceptarBoucher";		
		}
	$(idBotonGuardar).button('loading');
		$("#"+formulario_id+' input#accion').val(accion);
			var postData = $("#"+formulario_id).serializeArray();
			var formURL = $("#"+formulario_id).attr("action");
			var a=$("#"+formulario_id+' :checkbox').is(":checked");
			postData.push({name: "activo", value:a?"Si":"No"});
			var nuevoElemento=0;
			
			postData.forEach(function(f){
				if(f.value==""){
				nuevoElemento=nuevoElemento+1;
				}
			;})
			
		if(postData[2].value!=""){
				$.ajax({
					url : formURL,
					type: "POST",
					data : postData,
					success:function(data, textStatus, jqXHR){
					// console.dir(textStatus);
					// console.dir(data);
					// console.dir(jqXHR);
					resetFormulario("#"+formulario_id);
						if(jqXHR.status==200&&jqXHR.statusText=="OK"&&(JSON.parse(data).Estado=="OK"||JSON.parse(data).Estado=="OKrenvio"||JSON.parse(data).Estado=="OKSinCupos"))
						{
							if(JSON.parse(data).Estado=="OKrenvio"){
								$("#body-mensajes").html(JSON.parse(data).Respuesta);
							}else{
								if(JSON.parse(data).Estado=="OKSinCupos"){
								$("#body-mensajes").html(JSON.parse(data).Respuesta);
								}else{
									if(JSON.parse(data).Estado=="OK"){
									$("#body-mensajes").html(JSON.parse(data).Respuesta);
									}else{
									$("#body-mensajes").html(JSON.parse(data).Respuesta);	
									}
								}
							}
						}else{						//if fails      
						$("#body-mensajes").html(JSON.parse(data).Respuesta);
						}
						// setTimeout('wait("'+idTablaAsociada+'")',timeOut);
						var tablaa=$(".tblGENERAL");recargarCombos();
						$(idBotonGuardar).button('reset');
						tablaa.each(function(){
							setTimeout('wait("#'+$(this).attr("id")+'")',timeOut);
						;})
						$('#mensajes').modal('show');
						//data: return data from server
					},
					error: function(jqXHR, textStatus, errorThrown) 
					{
					$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
					$('#mensajes').modal('show');
						//if fails      
					}
				});
			}else{
			alert("Por favor, complete todos los campos antes de continuar.");
			}
		//});
	}	
	});
}

function EliminarElementoPago(idBotonEliminar,idTablaAsociada,idBotonEliminarModal){
var formulario_id=$(idBotonEliminar).parent().attr('id');
var modal_id=$(idBotonEliminarModal).parent().parent().parent().parent().attr("id");
var postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
var formURL = $("#"+formulario_id).attr("action");


	$(idBotonEliminar).click(function(e){
		postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
		e.preventDefault(); //STOP default action
$(idBotonEliminar).button('loading');	
	if(postData[1].value!=""){
		$("#"+modal_id).modal('show');
		}else{	
		alert("Debe seleccionar un elemento para eliminar.");
		}
	});	
		
	$(idBotonEliminarModal).click(function(e){	
	var accion="delete";
	$('#'+modal_id).modal('hide');
		$("#"+formulario_id+' input#accion').val(accion);
			postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
			if(modal_id=="confirmacionesRechazarSolicitud"){
			postData.push({name:'motivo',value:$('#'+modal_id+' textarea#motivo').val()});	
			}
			if(modal_id=="confirmacionesRechazarPago"){
			postData.push({name:'motivo',value:$('#'+modal_id+' textarea#motivo').val()});	
			}
			$.ajax({
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR){
				// console.dir(textStatus);
				// console.dir(data);
				// console.dir(jqXHR);
					if(jqXHR.status==200&&jqXHR.statusText=="OK"&&JSON.parse(data).Estado=="OK"){
					$("#body-mensajes").html(JSON.parse(data).Respuesta);
					resetFormulario("#"+formulario_id);
					}else{//if fails      
					$("#body-mensajes").html(JSON.parse(data).Respuesta);
					}
					// setTimeout('wait("'+idTablaAsociada+'")',timeOut);
					var tablaa=$(".tblGENERAL");
					
					recargarCombos();
					tablaa.each(function(){
						setTimeout('wait("#'+$(this).attr("id")+'")',timeOut);
					});
					$(idBotonEliminar).button('reset');
					$('#mensajes').modal('show');
					//data: return data from server
				},
				error: function(jqXHR, textStatus, errorThrown){
				$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
				$('#mensajes').modal('show');
					//if fails      
				}
			});
		});
}