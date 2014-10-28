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


	if($_POST['accion']="disponibilidad"){
	$db = new Database;
	$db->connect();
	@$db->select("ediciown_cursos","distinct nombre_curso","catalogo_cursos cc","fkidcc=cc.id and cc.activo=1 and edicion_cursos.activo=1");
	@$cursos_disponibles=$db->getResult();
	
	
	$db->disconnect();
	$entrada="";

	$disponibilidad='<a href="#" class="list-group-item "><span class="glyphicon glyphicon-hand-right"></span> {curso}</a>';

		foreach($cursos_disponibles as $v){
		  @$entrada=@$entrada.str_ireplace('{curso}',@$v["nombre_curso"],$disponibilidad);
		}
		echo @$entrada;
	}
	
}
catch (Exception $e) {
    echo 'error';
}

?>