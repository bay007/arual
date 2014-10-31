<?php
header("Content-Type: text/plain; charset=ISO-8859-1");
//header('Content-Type: text/html; charset=UTF-8'); 
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
		@$db->select("edicion_cursos","distinct nombre_curso","catalogo_cursos cc","fkidcc=cc.id and edicion_cursos.activo=1");
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
	
	
	
}
catch (Exception $e) {
    echo 'error';
}

?>