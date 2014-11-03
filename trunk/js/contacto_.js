$("a#centroArual").click(function(){
//mostramos en caso de dar click el panel
	//$($(this).attr("href").trim()).attr('show');
	var nombre_hospital=$(this).text();
	detalleUbicacion(nombre_hospital);
});