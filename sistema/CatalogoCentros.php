<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");


$conDatos=false;
if(isset($_GET["accion"])){
$accion=$_GET['accion'];
array_shift($_GET);
$conDatos=true;
}else{
$accion=$_POST['accion'];
array_shift($_POST);
$conDatos=true;
}

if(isset($_GET["id"])){
$id=$_GET['id'];
array_shift($_GET);
$datos=$_GET;
$conDatos=true;
}else{
$id=$_POST['id'];
array_shift($_POST);
$datos=$_POST;
$conDatos=true;
}
	if($conDatos){
	$db = new Database;
	$db->connect();
	$bandera=true;
	
	function base64ToImage($img,$nombre){
	$extension='ERROR';
	list($type, $img) = explode(';', $img);
	list(, $img)      = explode(',', $img);
	$data = base64_decode($img);
	
	if($type=='data:image/gif'){
	$extension=".gif";}
	
	if($type=='data:image/png'){
	$extension=".png";}
	
	if($type=='data:image/jpg'){
	$extension=".jpg";}
	
	if($type=='data:image/jpeg'){
	$extension=".jpg";}
		if($extension!='ERROR'){
		file_put_contents('../logotipo/'.$nombre.$extension, $data,LOCK_EX);
		}
		return $extension;
	}
	
	switch ($accion) {
		case 'update':
		$r=array("Estado"=>'','Respuesta'=>'');
		$bandera=false;
		$LOGOTIPO=$datos['logotipo'];
		$db->select("catalogo_centros","logotipo",'',"logotipo like '%".sha1($LOGOTIPO)."%'");
			if($db->numRows()>0){
				$r["Estado"]="ERROR";
				$r["Respuesta"]="El logotipo ya está siendo usado. Intente con otro archivo.";
				echo json_encode($r);
			}else{
				if($LOGOTIPO!=""){//implica que la actualizacion contiene una imagen
				$datos['logotipo']=sha1($LOGOTIPO);
				$ext=base64ToImage($LOGOTIPO,$datos['logotipo']);
				$db->select("catalogo_centros","logotipo",'',"id=$id");
				$res=$db->getResult();
				$OLD_LOGO=$res[0]['logotipo'];
				@unlink("../logotipo/".$OLD_LOGO);//borramos la imagen si es que hubo imagen cargada en el sistema
				}else{ // No contiene imagen
				$db->select("catalogo_centros","logotipo",'',"id=".$id);
				$res=$db->getResult();
				$datos['logotipo']=$res[0]['logotipo'];
				$ext="";
				}
					if($ext!='ERROR'){
						$datos['logotipo']=$datos['logotipo'].$ext;
						$db->update("catalogo_centros",$datos,"id=".$id);
						if($db->numRows()==1){
							$r["Estado"]="OK";
							$r["Respuesta"]="La actualización fué exitosa.";
						}else{
							$r["Estado"]="ERROR";
							$r["Respuesta"]="Ocurrió un error al actualizar el Centro Arual.";
						}
						echo json_encode($r);
					}else{
						echo $ext;
					}
			}
			break;
		case 'delete':
		$r=array("Estado"=>'','Respuesta'=>'');
			$db->sql("call SP_CatalogoCentros_Borrar($id)");
			$r=$db->getResult()[0];
				if($r["Estado"]=="OK"){
					if(($r["Respuesta"]=="logotipo.png")){
					}else{	
					@unlink("../logotipo/".$r["Respuesta"]);
					}
					$r["Respuesta"]="Centro eliminado satisfactoriamente";
					echo json_encode($r);
				}
				
				if($r["Estado"]=="ERROR"){
				echo utf8_decode(json_encode($r));
				}
			$db->disconnect();
			$bandera=false; 
			break;
		case 'create':
			$LOGOTIPO=$datos['logotipo'];
			$db->select("catalogo_centros","logotipo",'',"logotipo like '%".sha1($LOGOTIPO)."%'");
			if($db->numRows()>0){
				$r["Estado"]="ERROR";
				$r["Respuesta"]="El logotipo usado ya está siendo utilizado, por favor, intente con otro.";
				echo json_encode($r);
			}else{
			if($LOGOTIPO!=""){//implica que la actualizacion contiene una imagen
			$datos['logotipo']=sha1($LOGOTIPO);
			$ext=base64ToImage($LOGOTIPO,$datos['logotipo']);
			}else{ // No contiene imagen
			$datos['logotipo']="logotipo.png";
			$ext="";
			}
				if($ext!='ERROR'){
					$datos['logotipo']=$datos['logotipo'].$ext;
					if($db->insert("catalogo_centros",$datos)){
						$r["Estado"]="OK";
						$r["Respuesta"]="El alta fue exitosa.";
						echo json_encode($r);
					}
					else{
						@unlink("../logotipo/".$datos['logotipo']);
					}
					$db->disconnect();
				}else{
					$r["Estado"]="ERROR";
					$r["Respuesta"]="El archivo del logotipo no es una imagen.";
					echo json_encode($r);
				}
			$bandera=false;
			break;
		}
	 }

	if(($id!="")&&$bandera){
		$db->select("catalogo_centros","id, latitud, longitud, hospital, direccion, contacto, telefono,email,noCuenta,banco,fkIDadministrador,activo",'',"id=".$id);
		if(($db->numRows())>0){
		$catalogo_centros=$db->getResult();
		$db->disconnect();
		echo json_encode($catalogo_centros);
		}else{
		echo 1;
		}
	}

	if($accion=="selectCMB"){
		$db->select("catalogo_centros","id,hospital");
		if(($db->numRows())>0){
		$catalogo_centros=$db->getResult();
		$db->disconnect();
		echo json_encode($catalogo_centros);
		}else{
		echo 1;
		}
	}

	if($accion=="selectCMBActivos"){
		$db->select("catalogo_centros","id,hospital","",'activo="Si"');
		if(($db->numRows())>0){
		$catalogo_centros=$db->getResult();
		$db->disconnect();
		echo json_encode($catalogo_centros);
		}else{
		echo 1;
		}
	}


	if($accion=="selectCMBCursos"){
		$db->select("catalogo_cursos","id,nombre_curso","",'activo="Si"');
		if(($db->numRows())>0){
		$catalogo_centros=$db->getResult();
		$db->disconnect();
		echo json_encode($catalogo_centros);
		}else{
		echo 1;
		}
	}
	
	if($accion=="AdministradoresDisponibles"){
		$db->select("AdministradoresDisponibles","*");
		if(($db->numRows())>0){
		$AdministradoresDisponibles=$db->getResult();
		$db->disconnect();
		echo json_encode($AdministradoresDisponibles);
		}else{
		echo 1;
		}
	}
	

 }

?>