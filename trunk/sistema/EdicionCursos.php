<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
 date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");

// if (!isset($_POST["activo"]))
// {
    // $_POST['activo'] = '0';
// }
$db = new Database;
$db->connect();
$accion="";
if(isset($_POST['accion'])){

$accion=$_POST['accion'];
array_shift($_POST);
$id=array_shift($_POST);
$datos=$_POST;
}
$bandera=false;

//$db->select("catalogo_cursos","id,nombre_curso,contenido,duracion,requisitos,publico_dirigido,activo");
//$catalogo_cursos=$db->getResult();


	 switch ($accion) {
		case 'update':
			$db->update("edicion_cursos",$datos,"id=".$id);$db->disconnect();
			//echo $db->getSql();
			echo $db->numRows();
			$bandera=true;
			break;
		case 'delete':
			 $db->delete("edicion_cursos","id=".$id);$db->disconnect();
			$bandera=true; 
			 echo $db->numRows();
			 //echo var_dump($id);
			break;
		case 'create':
			$db->insert("edicion_cursos",$datos);$db->disconnect();
			 echo $db->numRows();
			 $bandera=true;
			break;
		default:
			$bandera=false;
			break;
	}

if(!$bandera){
$db = new Database;
$db->connect();
$db->select("edicion_cursos","edicion_cursos.id,hospital,lespecifico,cupo,faplicacion,haplicacion,nombre_curso,edicion_cursos.activo","catalogo_centros join catalogo_cursos","catalogo_centros.id=edicion_cursos.fkIDCh AND catalogo_cursos.id=edicion_cursos.fkIDCc");
	$db->disconnect();
	if($db->numRows()>0){
	$catalogo_cursos=$db->getResult();
	echo json_encode($catalogo_cursos);
	}else{
	echo 1;
	}	
}
?>