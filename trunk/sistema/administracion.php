<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
include("seguridad.php");
include("mail.php");
error_reporting(-1);
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
//$tiempo_edicion='00:30:00.999998';
 $tiempo_edicion="+30 minutes";
// echo getRealIP();
// $eMail = new mail();
// echo $eMail->gen_uuid();

if(isset($_POST["email"])){
	$email=htmlentities(trim($_POST["email"]));
	$email2='ai7rbawynv4o5caw$B%SVCAW$5';
	$db = new Database;
	$db->connect();
	@$db->select("administradores","email","","email like '%$email%' and activo='Si'");

	if($db->numRows()==1){
	$a=$db->getResult();
	$email2=$a[0]['email'];
	}
		if(strcmp($email,$email2)==0){//implica que es candidato y está activo este email, por lo que se le puede mandar una requisicion a su email.
			$eMail = new mail();
			$eMail->para=$email;
			$uuid=$eMail->gen_uuid();
			$mensaje=file_get_contents('../pages/emailAcceso.html');
			$datos=array();
			$datos['uuid']=$uuid;

			$fecha = new DateTime();
			$fecha->modify($tiempo_edicion);
			$datos['fCaducidad']=$fecha->format('Y-m-d H:i:s');
			$datos['ip']=getRealIP();
			$datos['uuid']=$uuid;
			$datos['editando']=0;
			$db->update("administradores",$datos,"email='$email'");
				if($db->numRows()==1){
				$eMail->mensaje=str_ireplace('{GUI}',$uuid,$mensaje);
				$eMail->mensaje=str_ireplace('{fCaducidad}',$datos['fCaducidad'],$eMail->mensaje);
				echo $eMail->enviar();
				}else{
				echo "No fue posible actualizar una tabla,no se envió el mail.";
				$db->disconnect();
				}
		$db->disconnect();
		}else{
		echo "No encontramos su mail o bien no está activo";
		$db->disconnect();
		}		
}else{
echo "error";
}
?>