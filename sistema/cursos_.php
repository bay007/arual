<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
 
try {
// if (!isset($_GET["activo"]))
// {
    // $_GET['activo'] = '0';
// }

	
		if($_GET['accion']=="disponibilidad"){
		$db = new Database;
		$db->connect();
		@$db->select("edicion_cursos","distinct nombre_curso","catalogo_cursos cc","fkidcc=cc.id and edicion_cursos.activo=1 and date(faplicacion) >= date(now())");
		@$cursos_disponibles=$db->getResult();
		$db->disconnect();
		$entrada="";
		$disponibilidad='<a class="list-group-item "><span class="glyphicon glyphicon-hand-right"></span> {curso}</a>';
			foreach($cursos_disponibles as $v){
			  @$entrada=@$entrada.str_ireplace('{curso}',@$v["nombre_curso"],$disponibilidad);
			}
			echo @trim($entrada);
		}
	
	
	if(isset($_GET["accion"])){
		if($_GET['accion']=="detalleCurso"){
		$nombre_cursos=trim(urldecode($_GET['nombre_cursos']));
		
		$db = new Database;
		$db->connect();
		@$db->select("catalogo_cursos","contenido,duracion,requisitos,publico_dirigido","edicion_cursos ec"," nombre_curso like '%".$nombre_cursos."%' and ec.activo=1 and ec.fkIDCc=catalogo_cursos.id");
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
		@$db->select("edicion_cursos",'concat(date_format(date(faplicacion),"%a %d de %M del %Y")," a las ",date_format(haplicacion,"%T")," horas.") as faplicacion,hospital,cupo,direccion,lespecifico',"catalogo_centros join catalogo_cursos",'date(faplicacion) >= date(now()) and edicion_cursos.activo=1 and fkIDCh=catalogo_centros.id and catalogo_cursos.nombre_curso like "%'.$nombre_cursos.'%"  and fkIDCc=catalogo_cursos.id');
		@$centrosConCurso=$db->getResult();
		@$db->disconnect();
		
		
		
		
		$entrada="";
		$disponibilidad='<h5 id="h5"><a> <img src="images/gm.png"></img></a><div><strong> Lugar: </strong>{hospital}-({lespecifico})</div> </h5>';
		$disponibilidad.='<p><strong><span class="glyphicon glyphicon-map-marker"></span>Direccion:</strong>{direccion}.</p>';
		$disponibilidad.='<p><a class="link" href="#"><strong>Cupos disponibles en éste momento: {cupo}</strong></a></p>';
		$disponibilidad.='<strong><span class="glyphicon glyphicon-calendar"></span> Fecha y hora de aplicación :</strong> {faplicacion}';
		$disponibilidad.='<hr>';
		
		
	
	
			foreach($centrosConCurso as $v){
				@$entrada=$entrada.str_ireplace('{hospital}',@$v["hospital"],$disponibilidad);
				@$entrada=str_ireplace('{direccion}',@$v["direccion"],$entrada);
				@$entrada=str_ireplace('{lespecifico}',@$v["lespecifico"],$entrada);
				@$entrada=str_ireplace('{cupo}',@$v["cupo"],$entrada);
				@$entrada=str_ireplace('{faplicacion}',@$v["faplicacion"],$entrada);
			}
		echo ($entrada.'<script>$("#h5").click(function(){var h5=$(this);$.get( "contacto.html", function( data ) {$( "#contenido" ).replaceWith( data.replace("{lugar}",h5.text().split(":")[1].split("-")[0].trim()) );});});</script>');
	
	
		}
	}
	
	
	
}
catch (Exception $e) {
    echo 'error';
}

?>