			$("#centros a").click(function(){ //al dar click en el panel izquierdo que contiene la lista de cursos
				$("#centros a").removeClass("active");
				$(this).addClass("active");
				var nombre_cursos=$(this).text().trim();
						$.ajax({
							url : "sistema/cursos_.php",
							type: "GET",
							dataType: 'json',
							data : "accion=detalleCurso&nombre_cursos="+nombre_cursos,
							success:function(data, textStatus, jqXHR){
							//console.dir(textStatus);
							//console.dir(data);
							//console.dir(jqXHR);
								if(jqXHR.status==200&&jqXHR.statusText=="OK"&&data!="error")
								{
								$(".tituloCurso").text(nombre_cursos);
								$("#contenidoCuerpo").html(data[0].contenido);
								$("#duracion").html(data[0].duracion);
								$("#requisitos").html(data[0].requisitos);
								$("#publicoDirigido").html(data[0].publico_dirigido);
								$("#block-6").attr("style","");
								$("#block-6_block").attr("style","display:none");
								
								$.ajax({
										url : "sistema/cursos_.php",
										type: "GET",
										data : "accion=centosConCurso&nombre_cursos="+nombre_cursos,
										success:function(data1, textStatus1, jqXHR1){
										//console.dir(textStatus);
										//console.dir(data1);
										//console.dir(jqXHR);
											if(jqXHR1.status==200&&jqXHR1.statusText=="OK"&&data1!="error")
											{
											$("#ubicacionesCursos").html(data1);
											
											}
										},
										error: function(jqXHR1, textStatus1, errorThrown1) 
										{
										$("#body-mensajes").html('<p class=bg-warning">'+errorThrown1+'</p>');
										$('#mensajes').modal('show');
											//if fails      
										}
									});
								}
							},
							error: function(jqXHR, textStatus, errorThrown) 
							{
							$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
							$('#mensajes').modal('show');
								//if fails      
							}
						});
						$('html,body').animate({scrollTop:$(".tituloCurso_top").parent().offset().top}, 1500);
							
		});
		
		
		
		
		$("#enviarSolicitud").click(function(boton){
			var postData=$("#frmInscripcion").serializeArray();
			$(this).button('loading');
			var formURL = $(this).parent().parent().attr("action");
			$.ajax({
							url : formURL,
							type: "POST",
							data : postData,
							success:function(data, textStatus, jqXHR){
							/*alert(textStatus);
							alert(data);
							alert(jqXHR);*/
								if(jqXHR.status==200&&jqXHR.statusText=="OK"&&data=="OK")
								{
								$("#inscripcionCurso").html("<h4>Hemos recibido su solicitud, una vez procesada (máximo en 24 horas) le enviaremos un email con los pasos a seguir para completar su inscripcion a éste curso.</h4>");
								$("#inscripcionCurso").append("<p>Por favor revise su bandeja de entrada asi como su bandeja de correo no deseado.</p>");
								}else{
								$("#inscripcionCurso").html("<h4>Por el momento no podemos procesar tu solicitud, por favor intentalo más tarde..</h4>");
								}
							},
							error: function(jqXHR, textStatus, errorThrown) 
							{
							$("#body-mensajes").html('<p class=bg-warning">'+errorThrown+'</p>');
							$('#mensajes').modal('show');
							$("#enviarSolicitud").button('reset');
								//if fails      
							}
						});
		});