var timeOut=1000;


function seleccionaTAGSelect(idSelect,datoABuscar){
$(idSelect+" option").each(function(){
	if($(this).data("texto")==datoABuscar){
	$(idSelect).val($(this).val());
	}
});
}

function formularioDesdeTabla(fila,idFormulario){
$(idFormulario).addClass('animated pulse');

$.each( fila, function( key, value ) {
							$(idFormulario+' :input#'+key).val(value);
					
							if(key=="activo"){
							$(idFormulario+' :checkbox#'+key).prop("checked",value=="1"?true:false);
							}
							else{
							$(idFormulario+' :checkbox#'+key).prop("checked",value=="1"?true:false);
							}
							
							if(key=="hospital"){
							seleccionaTAGSelect(idFormulario+" select#cmbHospitalesCatalogo",value);
							}
							
							if(key=="nombre_curso"){
							seleccionaTAGSelect(idFormulario+" select#cmbCursosCatalogo",value);
							}
					});
							
								$(idFormulario).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
					$(idFormulario).removeClass('animated pulse');
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
			if(Referencia==row_.id){
			// console.log(Referencia);
			// console.log(row_.id);
			$(element).addClass("info");
			}else{
			$(element).removeClass("info");
			}
		});
	
	var obj=JSON.stringify(row);
	//var frm = $("#formularioCursos");
		formularioDesdeTabla(row,idFormulario);
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
		/*$("#"+formulario_id).submit(function(e)
		{*/
			var postData = $("#"+formulario_id).serializeArray();
			var formURL = $("#"+formulario_id).attr("action");
			
			var a=$("#"+formulario_id+' :checkbox').is(":checked");
			postData.push({name: "activo", value:a?1:0});
			var nuevoElemento=0;
			
			postData.forEach(function(f){
				if(f.value==""){
				nuevoElemento=nuevoElemento+1;
				}
			;})
			
		if(!(3<=nuevoElemento)){
				$.ajax({
					url : formURL,
					type: "POST",
					data : postData,
					success:function(data, textStatus, jqXHR){
					// console.dir(textStatus);
					// console.dir(data);
					// console.dir(jqXHR);
						if(jqXHR.status==200&&jqXHR.statusText=="OK"&&data==1)
						{
							$("#body-mensajes").html('<p class="bg-success">El cambio en el curso fue exitoso</p>');
							$('#mensajes').modal('show');
							resetFormulario("#"+formulario_id);
							setTimeout('wait("'+idTablaAsociada+'")',timeOut);
						}else{						//if fails      
						$("#body-mensajes").html('<p class="bg-warning">Ocurrio un error al actualizar el curso</p>');
						$('#mensajes').modal('show');
						}
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
	  $(this).val(null);
	});
$("#"+formulario.attr('id')+' :checkbox').prop("checked",false);
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
		alert("Debe seleccionar un curso para eliminar.");
		}
	});	
		
	$(idBotonEliminarModal).click(function(e){	
	var accion="delete";
	$('#'+modal_id).modal('hide');
		$("#"+formulario_id+' input#accion').val(accion);
			postData = $("#"+formulario_id).find("input[type=hidden]").serializeArray();
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
					 $("#body-mensajes").html('<p class="bg-success">La eliminacion fue exitosa</p>');
					 resetFormulario("#"+formulario_id);
					 setTimeout('wait("'+idTablaAsociada+'")',timeOut);
					}else{						//if fails      
					$("#body-mensajes").html('<p class="bg-warning">Ocurrio un error al eliminar el curso,inténtelo de nuevo por favor</p>');
					}
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
							if(value=="Seleccion"){
							resetFormularioMapas(idFormulario);
							return;
							}
							
							
							$(idFormulario).addClass('animated pulse');
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
										$(idFormulario+' :checkbox#'+key).prop("checked",fila[key]=="1"?true:false);
										}
										else{
										$(idFormulario+' :checkbox#'+key).prop("checked",fila[key]=="1"?true:false);
										}
										
										});
										
										var centro=new google.maps.LatLng(document.getElementById("latitud").value ,document.getElementById("longitud").value);
										marker.setPosition(centro);
										map.setCenter(centro);
										map.setZoom(mapProp.zoom);
										
									// console.dir(textStatus);
									// console.dir(data);
									// console.dir(jqXHR);
									}
								});

							$(idFormulario).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
							$(idFormulario).removeClass('animated pulse');
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
			var logotipo=$("#frmMapas").find("input[type=file]").val();
			postData.push({name: "activo", value:a?1:0});
			postData.push({name: "logotipo", value:logotipo});
			var nuevoElemento=0;
			
			postData.forEach(function(e){
				if(e.value==""){
				nuevoElemento=nuevoElemento+1;
				}
			;})
			
		if((5>=nuevoElemento)){
			$.ajax(
			{
				url : formURL,
				type: "GET",
				data : postData,
				success:function(data, textStatus, jqXHR) 
				{
				
				// console.dir(textStatus);
				// console.dir(data);
				// console.dir(jqXHR);
					if(jqXHR.status==200&&jqXHR.statusText=="OK"&&data==1)
					{
				
				 $("#body-mensajes").html('<p class="bg-success">El cambio en el centro afiliado ARUAL fue exitoso</p>');
				 $('#mensajes').modal('show');
				resetFormularioMapas("#"+formulario_id);
					}else{						//if fails      
					$("#body-mensajes").html('<p class="bg-warning">Ocurrio un error al actualizar el Centro Arual</p>');
					$('#mensajes').modal('show');
					}
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
			alert("Por favor, complete todos los campos del nuevo centro antes de continuar.");
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
			postData.push({name: "activo", value:a?1:0});
			var nuevoElemento=0;
			
			postData.forEach(function(e){
				if(e.value==""){
				nuevoElemento=nuevoElemento+1;
				}
			;})
			
		if(!(3<=nuevoElemento)){
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
						$("#body-mensajes").html('<p class="bg-success">El cambio en el centro afiliado ARUAL fue exitoso</p>');
						$('#mensajes').modal('show');
						resetFormulario("#"+formulario_id);
						setTimeout('wait("'+idTablaAsociada+'")',timeOut);
					}else{						//if fails      
					$("#body-mensajes").html('<p class="bg-warning">Ocurrio un error al actualizar la edicion a un curso.</p>'+data);
					$('#mensajes').modal('show');
					}
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
			alert("Por favor, complete todos los campos antes de continuar y seleccione un lugar en el mapa.");
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