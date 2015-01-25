<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
 error_reporting(-1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$db = new Database;
$db->connect();

$accion=$_POST['accion'];
array_shift($_POST);
$id=array_shift($_POST);
$datos=$_POST;

//$db->select("catalogo_cursos","id,nombre_curso,contenido,duracion,requisitos,publico_dirigido,activo");
//$catalogo_cursos=$db->getResult();

$db->disconnect();
  switch ($accion) {
    case 'update':
		$db->update("catalogo_cursos",$datos,"id=".$id);$db->disconnect();
		 echo $db->numRows();
		//
        break;
    case 'delete':
         $db->delete("edicion_cursos","fkIDCc=".$id);
		 if($db->numRows()>0){
		 $db->delete("catalogo_cursos","id=".$id);
			if($db->numRows()==1){
			echo 1;
			}
		 }
		 $db->disconnect();
		 //echo var_dump($id);
        break;
    case 'create':
        $db->insert("catalogo_cursos",$datos);$db->disconnect();
		 echo $db->numRows();
        break;
	}
}else{
	if(isset($_GET["accion"])){
		if($_GET['accion']=="selectCMBPublico"){
		$db = new Database;
		$db->connect();
		@$db->select("catalogo_cursos","distinct publico_dirigido");
			if(($db->numRows())>1){
			@$detalleCurso=$db->getResult();
			$db->disconnect();
			echo json_encode($detalleCurso);
			}else{
			$detalleCurso=array(array("publico_dirigido"=>"Público en general."),array("publico_dirigido"=>"Sólo profesionales del área de la salud."));
			echo json_encode($detalleCurso);
			}
		}
	}else{ 
		$db = new Database;
		$db->connect();
		$db->select("catalogo_cursos","id,nombre_curso,contenido,duracion,requisitos,publico_dirigido,activo");
		$catalogo_cursos=$db->getResult();
		$db->disconnect();
			if(($db->numRows())>0){
			echo json_encode($catalogo_cursos);
			}else{
			echo 1;
			}
		}
}
?>