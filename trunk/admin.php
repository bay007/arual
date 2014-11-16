<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8'); 
require "sistema/seguridad.php";
error_reporting(-1);
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");

if(isset($_GET['uuid'])){ 
		$uuid=$_GET['uuid'];
		$db = new Database;
		$db->connect();
		@$db->select("administradores","*","","activo='Si' and date(fCaducidad) >= date(now()) and uuid='$uuid' and editando=0");
		@$sesion_disponible=$db->getResult();
		$db->disconnect();

		if($db->numRows()==1){
			if(getRealIP()==$sesion_disponible[0]['ip']){
				$db->update("administradores",array("editando"=>"1"),"uuid='$uuid'");
				if($db->numRows()==1){
				// echo json_encode($sesion_disponible);
				include "9768ed58-c37b-4ab8-9ff4-b0315f3e0f";
				}
			}else{
			die("Se debe abrir la página desde la misma pc desde donde se solicitó la administración.");
			}
		}else{
		echo ("Ésta solicitud de acceso ya fué usada ó bien ha caducado, genere otra de nuevo.");
		}
}
else{ 
Echo "Acceso no autorizado";
}
?>