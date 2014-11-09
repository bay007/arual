<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
/*
class ARUAL{
 




	public function isAdmin($email,$pphase){
		$db = new Database;
$email=$this->sanitizar($email);
$pphase=$this->cifrar($pphase);
$db->connect();
$condicion="correo_responsable='{1}' and pphase='{0}'";
$condicion=str_replace("{1}",$email,$condicion);
$condicion=str_replace("{0}",$pphase,$condicion);
$db->select("admin","nombre_completo,tipo,correo_responsable",null,$condicion);
$admin=$db->getResult();

$resultado=array("r"=>"0");
$resultado['r']=$db->numRows();
$db->disconnect();

if($resultado['r']==1){
	$resultado['r']=true;
	array_push($resultado,$admin);
	return $resultado;
	
	
}
else{
	$resultado['r']=false;
	array_push($resultado,null);
	return $resultado;
}
		
	}
	
	
	
	public function isAlumno($email,$pphase){
		$db = new Database;
$email=$this->sanitizar($email);
$pphase=$this->cifrar($pphase);
$db->connect();
$condicion="email='{1}' and etapa>0 and pphase='{0}'";
$condicion=str_replace("{1}",$email,$condicion);
$condicion=str_replace("{0}",$pphase,$condicion);
$db->select("alumno","nombre,email,etapa,tipo",null,$condicion);
$alumno=$db->getResult();

$resultado=array("r"=>"0");
$resultado['r']=$db->numRows();
$db->disconnect();

if($resultado['r']==1){
	$resultado['r']=true;
	array_push($resultado,$alumno);
	return $resultado;
	
	
}
else{
	$resultado['r']=false;
	array_push($resultado,$alumno);
	return $resultado;
}
		
	}
	
	
	
	public function cifrar($entrada){
		$entrada=$this->sanitizar($entrada);
		return hash('sha512',md5(sha1(sha1($entrada.$_SESSION['sk'])))).md5(sha1($entrada.$_SESSION['sk']));
	}
	
	public function sanitizar($entrada){
		 $search = array('@<script [^>]*?>.*?@si','@< [/!]*?[^<>]*?>@si','@<style [^>]*?>.*?</style>@siU','@< ![sS]*?--[ tnr]*>@');
		return  filter_var(strip_tags((stripslashes(preg_replace($search,'',trim($entrada))))),FILTER_SANITIZE_MAGIC_QUOTES);
		
	}
	
public function getCursos(){
$db = new Database;
$db->connect();
$db->select("inscrito","fkid_curso,fkemail,fecha_aplicacion,hora,cupo,contenido,duracion");
$alumno=$db->getResult();
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $alumno;
$db->disconnect();
return (json_encode($jTableResult));
}



public function getAltas(){
$db = new Database;
$db->connect();
$db->select("alumno","email,nombre,ap,edad,tipo,celular,etapa",null,"etapa=0");
$ALTAalumno=$db->getResult();
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $ALTAalumno;
$db->disconnect();
return (json_encode($jTableResult));
}

public function darAlta($email,$etapa){
$db = new Database;
$db->connect();
$condicion="email='{1}' and etapa=0";
$condicion=str_replace("{1}",$email,$condicion);
if($etapa==1){
$a=array("etapa"=>"1");
}else{
$a=array("etapa"=>"0");
}
//var_dump($a);
$db->update("alumno",$a,$condicion);

$db->disconnect();
unset($db);

$db2 = new Database;
$db2->connect();
$condicion="email='{1}' and etapa=1";
$condicion=str_replace("{1}",$email,$condicion);
$db2->select("alumno","etapa",null,$condicion);
$db2->disconnect();
$jTableResult = array();
$b=$db2->numRows();

if($b>=1){
$jTableResult['Result'] = "OK";

}
else{
$jTableResult['Result'] = "ERROR";
$jTableResult['Message'] = "¡Queda pendiente de alta!";
//var_dump($jTableResult);	
}

return (json_encode($jTableResult));
}



public function getAlumnosSistema(){
$db = new Database;
$db->connect();
$db->select("alumno","email,nombre,ap,am,edad,tipo,celular,imagenes_path",null,"etapa>0");
$alumnosensistema=$db->getResult();
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $alumnosensistema;
$db->disconnect();
return (json_encode($jTableResult));
}


public function getTodosCursos(){
$db = new Database;
$db->connect();
//$db->select('select nombre,contenidoCurso,duracionCurso,fecha_aplicacion,hora,publico,name as Lugar,address,lat,lng from edicion,curso,NombreCursos,markers,publico where curso.fkid=markers.id and edicion.fkid_curso=curso.id_curso and curso.fktipo=NombreCursos.id and curso.fkdirigido=publico.id');

$db->sql("select distinct nombre from edicion,curso,NombreCursos,markers,publico where curso.fkid=markers.id and edicion.fkid_curso=curso.id_curso and curso.fktipo=NombreCursos.id and curso.fkdirigido=publico.id");
//echo $db->getSql();
//$db->sql(select tipo from catalogo_centros join inscrito on id_curso=fkid_curso group by tipo);
$CursosEnCurso=$db->getResult();
$a='<ul class="list-1">';
foreach ($CursosEnCurso as $valor) {
    $a=$a.'<li class="'.$valor['nombre']. '"><a href="#marcador1">'.$valor['nombre'].'</a></li>';
}
$a=$a."</ul>";
$db->disconnect();
return ($a);
}

public function getDetalleCurso($tipoCurso){
$db = new Database;
$db->connect();
$filtro='"%{0}%"';
$filtro=str_replace('{0}',$tipoCurso,$filtro);
$db->sql('select requisitos,cupo,contenidoCurso,duracionCurso,fecha_aplicacion,hora,dirigido,name as Lugar,address,lat,lng from edicion,curso,NombreCursos,markers,publico where curso.fkid=markers.id and edicion.fkid_curso=curso.id_curso and curso.fktipo=NombreCursos.id and curso.fkdirigido=publico.id  and NombreCursos.Nombre like '.$filtro);

//echo $db->getSql();
//$db->sql(select tipo from catalogo_centros join inscrito on id_curso=fkid_curso group by tipo);
$CursosEnCurso=$db->getResult();
$db->disconnect();
/*
$a='<ul class="list-1">';
foreach ($CursosEnCurso as $valor) {
    $a=$a.'<li class="'.$valor['tipo']. '"><a href="#marcador1">'.$valor['tipo'].'</a></li>';
}
$a=$a."</ul>";

$a='';

$a0='<h2 class="tituloCurso"  align="center">{titulo}</h2>
<p class="p6" align="justify">
       <strong> Contenido:</strong> {contenido}      
    </p>
    <p class="p6"  align="justify">
	<strong>Duracion:</strong> {duracion}
    </p>
    <p class="p6"  align="justify">
       <strong> Requisitos:</strong> {requisitos}      
    </p>
    <p class="p6"  align="justify">
       <strong> Publico dirigido:</strong> {publico}      
    </p> </br></br></br><h3 align="center">Ubicaciones donde se puede tomar &eacute;ste curso</h3><hr>';

 $a0=str_replace('{contenido}',$CursosEnCurso[0]['contenidoCurso'],$a0);
    $a0=str_replace('{duracion}',$CursosEnCurso[0]['duracionCurso'],$a0);
    $a0=str_replace('{requisitos}',$CursosEnCurso[0]['requisitos'],$a0);
    $a0=str_replace('{publico}',$CursosEnCurso[0]['dirigido'],$a0);
    $a0=str_replace('{titulo}',$tipoCurso,$a0);





$a1='<p>
    <h5>Lugar: {Lugar} </h5></p>
<p><strong>Direccion:</strong> {address}      
        </p><p>
        <a class="link" href="#">
            <strong>
                Cupos disponibles en este momento: <stong>{cupo}</strong>
            </strong>
        </a>
</p>
        <p class="p1"><strong>Hora de aplicacion y fecha:</strong> {fecha_aplicacion} a las {horas} horas
  

<p>    
    <a class="button" href="#"> 

Inscribir

    </a></p><br><br><br>';


foreach ($CursosEnCurso as $valor) {
    $a=$a.str_replace("{Lugar}",$valor['Lugar'],$a1); 
    $a=str_replace('{cupo}',$valor['cupo'],$a);
    $a=str_replace('{address}',$valor['address'],$a);
    $a=str_replace('{horas}',$valor['hora'],$a);
    $a=str_replace('{fecha_aplicacion}', strftime("%A %e de %B de %Y",strtotime($valor['fecha_aplicacion'])),$a);
}

return ($a0.$a);
}

public function getCursoss(){
$db = new Database;
$db->connect();
//$db->sql("select * from publico");

//$db->select("alumno","email,nombre,ap,am,edad,tipo,celular,imagenes_path","publico","etapa>0");

$db->sql("select id_edicion,nombre,name,cupo,fecha_aplicacion,hora,dirigido from edicion,curso,NombreCursos,markers,publico where curso.fkid=markers.id and edicion.fkid_curso=curso.id_curso and curso.fktipo=NombreCursos.id and curso.fkdirigido=publico.id");

$alumnosensistema=$db->getResult();
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $alumnosensistema;
$db->disconnect();
unset($db);
return (json_encode(($jTableResult)));
}


public function CatCursos(){
$db = new Database;
$db->connect();
//$db->sql("select * from publico");

//$db->select("alumno","email,nombre,ap,am,edad,tipo,celular,imagenes_path","publico","etapa>0");

$db->sql("select nombre as DisplayText,id as Value from NombreCursos");

$alumnosensistema=$db->getResult();
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Options'] = $alumnosensistema;
$db->disconnect();
unset($db);
return (json_encode(($jTableResult)));
}



}*/

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
// $db = new Database;
// $db->connect();

// // if (!isset($_POST["activo"]))
// // {
    // // $_POST['activo'] = '0';
// // }

// $accion=$_POST['accion'];
// array_shift($_POST);
// $id=array_shift($_POST);
// $datos=$_POST;

// //$db->select("catalogo_centros","id,nombre_curso,contenido,duracion,requisitos,publico_dirigido,activo");
// //$catalogo_centros=$db->getResult();

// $db->disconnect();
  // switch ($accion) {
    // case 'update':
		
		
         // // $db->update("	catalogo_centros",$datos,"id=".$id);
		 // // echo $db->numRows();
		// //
        // break;
    // case 'delete':
         // // $db->delete("catalogo_centros","id=".$id);
		 // // echo $db->numRows();
		 // //echo var_dump($id);
        // break;
    // case 'create':
        // // $db->insert("catalogo_centros",$datos);
		 // // echo $db->numRows();
        // break;
// }
  
  
  
  
  
  
// }else{
$conDatos=false;
if(isset($_GET["accion"])){
$accion=$_GET['accion'];
$conDatos=true;
}else{
$accion=$_POST['accion'];
$conDatos=true;
}

if(isset($_GET["id"])){
$id=$_GET['id'];
$conDatos=true;
}else{
$id=$_POST['id'];
$conDatos=true;
}
	if($conDatos){

	$db = new Database;
	$db->connect();
	$bandera=true;
	array_shift($_GET);
	array_shift($_GET);
	$datos=$_GET;
	switch ($accion) {
		case 'update':
			$bandera=false;
			
			  $db->update("catalogo_centros",$datos,"id=".$id);
			  echo $db->numRows();
			
			break;
		case 'delete':
			  $db->delete("catalogo_centros","id=".$id);
			  echo $db->numRows();
			$bandera=false; 
			break;
		case 'create':
			$db->insert("catalogo_centros",$datos);
			echo $db->numRows();
			$bandera=false;
			break;
	 }



	if(($id!="")&&$bandera){
	$db->select("catalogo_centros","id, latitud, longitud, hospital, direccion, contacto, telefono, email, activo",'',"id=".$id);//,);		
	$catalogo_centros=$db->getResult();
	$db->disconnect();
	echo json_encode($catalogo_centros);
	}

	 if($accion=="selectCMB"){
	$db->select("catalogo_centros","id,hospital");
	$catalogo_centros=$db->getResult();
	$db->disconnect();
	echo json_encode($catalogo_centros);
	}

	if($accion=="selectCMBActivos"){
	$db->select("catalogo_centros","id,hospital","",'activo="Si"');
	$catalogo_centros=$db->getResult();
	$db->disconnect();
	echo json_encode($catalogo_centros);
	}


	if($accion=="selectCMBCursos"){
	$db->select("catalogo_cursos","id,nombre_curso","",'activo="Si"');
	$catalogo_centros=$db->getResult();
	$db->disconnect();
	echo json_encode($catalogo_centros);
	}


 }

?>