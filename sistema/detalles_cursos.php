<?php
$detallesCurso = '<div class="panel panel-primary"><div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" id="centroArual" data-toggle="collapse" data-parent="#accordion{l}" href="#collapse{k}">
        <h5>Fecha de Aplicación: {faplicacion}<i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i></h5>
        </a>
      </h4>
    </div>
    <div id="collapse{k}" class="panel-collapse collapse">
      <div class="panel-body">
        <p><strong>Cupo:</strong> Aún quedan {cupo} lugares disponibles.</p><p><strong>Instalaciones: </strong>{lespecifico}</p>
		<p><button data-id="{id}" id="btnInscribirCurso{id}" form="frmInscripcion" type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#usuario_inscripcion">Inscripción</button></p>
      </div>
    </div>
</div>
<script>
$("#btnInscribirCurso{id}").click(function(){
	$("#idcursoSolicitado").val($(this).data().id);
});	
</script>
';
?>