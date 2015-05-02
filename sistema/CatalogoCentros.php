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
		$bandera=false;
		$LOGOTIPO=$datos['logotipo'];
		$db->select("catalogo_centros","logotipo",'',"logotipo like '%".sha1($LOGOTIPO)."%'");
			if($db->numRows()>0){
			echo "ya_se_uso_la_imagen";
			}else{
				
				if($LOGOTIPO!=""){//implica que la actualizacion contiene una imagen
				$datos['logotipo']=sha1($LOGOTIPO);
				$ext=base64ToImage($LOGOTIPO,$datos['logotipo']);
				$db->select("catalogo_centros","logotipo",'',"id=$id");
				$r=$db->getResult();
				$OLD_LOGO=$r[0]['logotipo'];
				unlink("../logotipo/".$OLD_LOGO);//borramos la imagen si es que hubo imagen cargada en el sistema
				}else{ // No contiene imagen
				$db->select("catalogo_centros","logotipo",'',"id=".$id);
				$r=$db->getResult();
				$datos['logotipo']=$r[0]['logotipo'];
				$ext="";
				
				}
					if($ext!='ERROR'){
						$datos['logotipo']=$datos['logotipo'].$ext;
						$db->update("catalogo_centros",$datos,"id=".$id);
						if($db->numRows()==1){
							echo "1";
						}else{
							echo "10";
						}
					}else{
						echo $ext;
					}
			}
			break;
		case 'delete':
			$db->select("catalogo_centros","logotipo",'',"id=".$id);
			$r=$db->getResult();
			$logo=$r[0]['logotipo'];
			$db->delete("edicion_cursos","fkIDCh=".$id);
			if($db->numRows()>0){
				$db->delete("catalogo_centros","id=".$id);
				if($db->numRows()==1){
					if(($logo!="logotipo.png")){
						
						if(@unlink("../logotipo/".$logo)){
						
						echo 1;
						}
						else{
						echo "ERRORdeBorradoArchivo";
						}
					}else{	
						echo 1;
					}
				}else{
				echo "ERRORdeBorradodeCentro";
				}	
			}
			$db->disconnect();
			$bandera=false; 
			break;
		case 'create':
			$LOGOTIPO=$datos['logotipo'];
			$db->select("catalogo_centros","logotipo",'',"logotipo like '%".sha1($LOGOTIPO)."%'");
			if($db->numRows()>0){
			echo "ya_se_uso_la_imagen";
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
						echo 1;	
					}
					else{
						@unlink("../logotipo/".$datos['logotipo']);
					}
					$db->disconnect();
				}else{
					echo $ext;
				}
			$bandera=false;
			break;
		}
	 }

	if(($id!="")&&$bandera){
		$db->select("catalogo_centros","id, latitud, longitud, hospital, direccion, contacto, telefono,email,noCuenta,banco,activo",'',"id=".$id);
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
	

 }

?>