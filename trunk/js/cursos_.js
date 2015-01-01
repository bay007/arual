			$("#centros a").click(function(){
				$('html,body').animate({scrollTop:$(".tituloCurso").parent().offset().top}, 1500);
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

			});