<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
 
try{
		if($_GET['accion']=="disponibilidad"){
		$db = new Database;
		$db->connect();
		@$db->select("edicion_cursos","distinct nombre_curso","catalogo_cursos cc",'fkidcc=cc.id and cupo>0 and edicion_cursos.activo="Si" and date(faplicacion) >= date(now())');
		@$cursos_disponibles=$db->getResult();
		$db->disconnect();
		if($db->numRows()>=1){
			$entrada="";
			$disponibilidad='<a class="list-group-item "><span class="glyphicon glyphicon-hand-right"></span> {curso}</a>';
				foreach($cursos_disponibles as $v){
				  @$entrada=@$entrada.str_ireplace('{curso}',@$v["nombre_curso"],$disponibilidad);
				}
				echo @trim($entrada);
			}else{
			echo "error";
			}
		}

	if(isset($_GET["accion"])){
		if($_GET['accion']=="detalleCurso"){
		$nombre_cursos=trim(($_GET['nombre_cursos']));
		$db = new Database;
		$db->connect();
		@$db->select("catalogo_cursos","contenido,duracion,requisitos,publico_dirigido","edicion_cursos ec"," nombre_curso like '%$nombre_cursos%' and ec.fkIDCc=catalogo_cursos.id and ec.activo='Si'");
		@$detalleCurso=$db->getResult();
		$db->disconnect();
		echo json_encode($detalleCurso);
		}
	}
	
	if(isset($_GET["accion"])){
		if($_GET['accion']=="centosConCurso"){
		$nombre_cursos=trim(urldecode($_GET['nombre_cursos']));
		
		$db = new Database;
		$db->connect();
		@$db->select("edicion_cursos",'concat(date_format(date(faplicacion),"%a %d de %M del %Y")," a las ",date_format(haplicacion,"%T")," horas.") as faplicacion,hospital,cupo,direccion,lespecifico,edicion_cursos.updated',"catalogo_centros join catalogo_cursos",'date(faplicacion) >= date(now()) and edicion_cursos.activo="Si" and fkIDCh=catalogo_centros.id and catalogo_cursos.nombre_curso like "%'.$nombre_cursos.'%"  and fkIDCc=catalogo_cursos.id');
		@$centrosConCurso=$db->getResult();
		@$db->disconnect();

		$entrada="";
		$disponibilidad='<sup>Última actualización: {updated}</sup><h5 id="{hospital}"><a> <img src="images/gm.png"></img></a><button type="button" class="btn btn-info pull-right">Inscribir</button></h5><div><strong> Lugar: </strong>{hospital} - ({lespecifico})</div> ';
		$disponibilidad.='<p><strong><span class="glyphicon glyphicon-map-marker"></span>Dirección:</strong>{direccion}.</p>';
		$disponibilidad.='<p><strong>Cupos disponibles en éste momento: {cupo}</strong></p>';
		$disponibilidad.='<strong><span class="glyphicon glyphicon-calendar"></span> Fecha y hora:</strong> {faplicacion}';
		$disponibilidad.='<hr>';

			foreach($centrosConCurso as $v){
				$a=date('d-M-Y, h:i A', strtotime($v["updated"]));
				@$entrada=$entrada.str_ireplace('{hospital}',@$v["hospital"],$disponibilidad);
				@$entrada=str_ireplace('{direccion}',@$v["direccion"],$entrada);
				@$entrada=str_ireplace('{updated}',$a,$entrada);
				@$entrada=str_ireplace('{lespecifico}',@$v["lespecifico"],$entrada);
				@$entrada=str_ireplace('{cupo}',@$v["cupo"],$entrada);
				@$entrada=str_ireplace('{faplicacion}',@$v["faplicacion"],$entrada);
			}
		echo ($entrada.'<script>
		$("h5 a").click(function(){var h5=$(this).parent();
		$.get( "contacto.html", function( data ) {
			$(".menu li").removeClass("current");
			$(".contacto_menu_li").addClass("current");
		$("#contenido").replaceWith( data.replace("{lugar}",h5.attr("id").trim()));
			});
		});</script>');
		}
	}
}
catch (Exception $e) {
    echo 'error';
}
?>