	$("h5").click(function(){
					//window.location.href = 'contacto.html?lugar=' + $(a).text().split(":")[1].trim();
					$.get( 'contacto.html?lugar=' + $(this).text().split(":")[1].trim(), function( data ) {
					$( "#contenido" ).replaceWith( data );
				});
			});
			
			$("#centros a").click(function(){
				
				$("#centros a").removeClass("active");
				$(this).addClass("active");
				var nombre_cursos=$(this).text();
						$.ajax({
							url : "sistema/cursos_.php",
							type: "GET",
							dataType: 'json',
							data : "accion=detalleCurso&nombre_cursos="+nombre_cursos,
							success:function(data, textStatus, jqXHR){
							//console.dir(textStatus);
							console.dir(data);
							//console.dir(jqXHR);
								if(jqXHR.status==200&&jqXHR.statusText=="OK"&&data!="error")
								{
								$(".tituloCurso").text(nombre_cursos);
								$("#contenidoCuerpo").html(data[0].contenido);
								$("#duracion").html(data[0].duracion);
								$("#requisitos").html(data[0].requisitos);
								$("#publicoDirigido").html(data[0].publico_dirigido);
								
								
								
								
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
			