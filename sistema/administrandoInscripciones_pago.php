<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
include("seguridad.php");
include("mail.php");
error_reporting(-1);

//$tiempo_edicion='00:30:00.999998';
$tiempo_edicion="+90 minutes";
// echo getRealIP();
// $eMail = new mail();
// echo $eMail->gen_uuid();


function responder($mensaje,$estado="OK"){
	 if(preg_match("/(OK).*/",$estado)==1){
	 $mensaje="<h4><div class='alert alert-success text-justify' role='alert'>
				<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
				<span class='sr-only'>Error:</span>
				$mensaje
				</div></h4>";	
	return json_encode(array("e"=>$estado,"m"=>$mensaje),JSON_FORCE_OBJECT);	 
	 }else{
		 $mensaje="<h4><div class='alert alert-danger' role='alert'>
					<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
					<span class='sr-only'>Error:</span>
					$mensaje</div></h4>";
	return json_encode(array("e"=>$estado,"m"=>$mensaje),JSON_FORCE_OBJECT);	 
	}
}

if(isset($_GET["idadministrandoInscripciones_pago"])){
	if($_GET["idadministrandoInscripciones_pago"]!=""){
		$bandera=false;
	$id	=$_GET["idadministrandoInscripciones_pago"];
		$db = new Database;
					$db->connect();
					@$db->select("solicitudes_inscripcion_pago",
					"boucher_aspirante_pago",
					"",
					"idadministrandoInscripciones_pago=$id");	
					$result=$db->getResult();
					$db->disconnect();
					echo (utf8_decode(json_encode($result)));
		}
}else{
		if(isset($_POST["accion"])){
			if($_POST["accion"]=="aceptarBoucher"){
						$bandera=false;
						$id	=$_POST["idadministrandoInscripciones_pago"];
						$db3 = new Database;
						$db3->connect();
						@$db3->select("solicitudes_inscripcion_pago","email_aspirante_pago,nombres_aspirante_pago,sello_pago","","idadministrandoInscripciones_pago=$id and sello_pago is not null");
						$result=$db3->getResult();
						$email=$result[0]["email_aspirante_pago"];
						$nombres_aspirante=$result[0]["nombres_aspirante_pago"];
						$sello_pago=$result[0]["sello_pago"];
						$eMail = new mail();
						$eMail->asunto="Solicitud Aceptada";
						$eMail->para=$email;
						$mensaje=file_get_contents('../pages/emailPagoOK.html');
						$eMail->mensaje=str_ireplace('{NOMBRE}',$nombres_aspirante,$mensaje);
						$eMail->mensaje=str_ireplace('{GUI}',$sello_pago,$eMail->mensaje);
						$eMail->mensaje=str_ireplace('{SERVERNAME}',$_SERVER['SERVER_NAME'],$eMail->mensaje);
						if($eMail->enviar()==1){
						@$db3->update("solicitudes_inscripcion_pago",array("verificado"=>"Si"),"idadministrandoInscripciones_pago=$id");
							if($db3->numRows()>0){
							echo responder("Se ha notificado al cliente que su pago ha sido verificado con éxito.");
							}else{
							echo responder("El pago no pudo ser procesado, habrá que intentarlo mas tarde.","error");
							}
						}else{
						echo responder("El email no pudo ser envíado, el pago no pudo ser procesado.","error");
						}
						$db3->disconnect();
			}else{		
				if(isset($_POST["accion"])){
					if($_POST["accion"]=="delete"){
						$bandera=false;
						$id	=$_POST["idadministrandoInscripciones_pago"];
						$motivo	=$_POST["motivo"];
						$db3 = new Database;
						$db3->connect();
						@$db3->select("solicitudes_inscripcion_pago","email_aspirante_pago,nombres_aspirante_pago","","idadministrandoInscripciones_pago=$id and sello_pago is not null");
						$result=$db3->getResult();
						$email=$result[0]["email_aspirante_pago"];
						$nombres_aspirante=$result[0]["nombres_aspirante_pago"];
						$eMail = new mail();
						$eMail->asunto="Comprobante de pago rechazado";
						$eMail->para=$email;
						$mensaje=file_get_contents('../pages/emailRechazoPago.html');
						$eMail->mensaje=str_ireplace('{NOMBRE}',$nombres_aspirante,$mensaje);
						$eMail->mensaje=str_ireplace('{MOTIVO}',$motivo,$eMail->mensaje);
						
						if($eMail->enviar()==1){
						@$db3->update("solicitudes_inscripcion_pago",array("verificado"=>"Rechazado"),"idadministrandoInscripciones_pago=$id");
							if($db3->numRows()>0){
							echo responder("Se ha rechazado el pago, se ha notificado a el cliente.");
							}else{
							echo responder("El rechazo no pudo ser procesado, no se notifico al cliente.","error");
							}
						}else{
						echo responder("El email no pudo ser envíado, el rechazo no pudo ser procesado.","error");
						}
						$db3->disconnect();
					}			
				}
					
			}
		}else{
					$db = new Database;
				$db->connect();
				$db->select("edicion_cursos","idadministrandoInscripciones_pago
				,email_aspirante_pago,nombres_aspirante_pago,apellidos_aspirante_pago,telefono_aspirante_pago,titulo_aspirante_pago,titulo_aspirante_pago,sello_pago,verificado,concat(edicion_cursos.faplicacion,' / ',edicion_cursos.haplicacion) as fhora_programada_pago,catalogo_cursos.nombre_curso,catalogo_centros.hospital",
				"catalogo_cursos join catalogo_centros join solicitudes_inscripcion_pago",
				"idcursoSolicitado_pago=edicion_cursos.id and fkIDCh=catalogo_centros.id and fkIDCc=catalogo_cursos.id","verificado asc");
				if($db->numRows()>0){
				//$adminis=array("0"=>array("id"=>"18","sello_solicitud"=>"0c224cc4","titulo_aspirante"=>"Dr","email_aspirante"=>"a@a.com","nombres_aspirante"=>"nombre_aspirante","apellidos_aspirante"=>"apellidos_aspirante","telefono_aspirante"=>"telefono_aspirante","lugar_aplicacion"=>"Pedregal Angeles","fhora_programada"=>"2015-10-22/16:20:00","curso_solicitado"=>"SALVACORAZONES PRIMEROS AUXILIOS CON RCP Y DEA","descarga_instrucciones"=>"No"));
				$Resp=$db->getResult();
				$db->disconnect();
				echo (utf8_decode(json_encode($Resp)));
				}
			}
		}

		
?>