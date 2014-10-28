<?php
header("Content-Type: text/plain; charset=ISO-8859-1");
//header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
$db = new Database;
$db->connect();

// if (!isset($_POST["activo"]))
// {
    // $_POST['activo'] = '0';
// }

$accion=$_POST['accion'];
array_shift($_POST);
$id=array_shift($_POST);
$datos=$_POST;

//$db->select("catalogo_cursos","id,nombre_curso,contenido,duracion,requisitos,publico_dirigido,activo");
//$catalogo_cursos=$db->getResult();

$db->disconnect();
  switch ($accion) {
    case 'update':
		
		
         $db->update("catalogo_cursos",$datos,"id=".$id);
		 echo $db->numRows();
		//
        break;
    case 'delete':
         $db->delete("catalogo_cursos","id=".$id);
		 echo $db->numRows();
		 //echo var_dump($id);
        break;
    case 'create':
        $db->insert("catalogo_cursos",$datos);
		 echo $db->numRows();
        break;
}
  
  
  
  
  
  
}else{
$db = new Database;
$db->connect();
$db->select("catalogo_cursos","id,nombre_curso,contenido,duracion,requisitos,publico_dirigido,activo");
$catalogo_cursos=$db->getResult();
$db->disconnect();
echo json_encode($catalogo_cursos);
}

?>