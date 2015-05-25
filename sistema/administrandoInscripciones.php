<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
include("mysql_crud.php");
include("mail.php");
error_reporting(-1);
$tiempo_espera="+2 day";
function gen_uuid() {
    return sprintf( '%04x%04x',mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
}  

function responder($mensaje,$estado="OK"){
	 if(preg_match("/(OK).*/",$estado)==1){
	 $mensaje="<h4><div class='alert alert-success text-justify' role='alert'>
				<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
				<span class='sr-only'>Error:</span>
				$mensaje
				</div></h4>";	
	return json_encode(array("Estado"=>$estado,"Respuesta"=>$mensaje));	 
	}else{
		$mensaje="<h4><div class='alert alert-danger' role='alert'>
					<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
					<span class='sr-only'>Error:</span>
					$mensaje</div></h4>";
		return json_encode(array("Estado"=>$estado,"Respuesta"=>$mensaje));	 
	}
}

$bandera=true;
if(isset($_GET["idadministrandoInscripciones"])){
	if($_GET["idadministrandoInscripciones"]!=""){
		$bandera=false;
	$id	=$_GET["idadministrandoInscripciones"];
		$db = new Database;
					$db->connect();
					@$db->select("solicitudes_inscripcion",
					"credencial_aspirante",
					"",
					"idadministrandoInscripciones=$id");	
					$result=$db->getResult();
					$db->disconnect();
					echo (utf8_decode(json_encode($result)));
		}
}

if(isset($_POST["accion"])){
	if($_POST["accion"]=="ReenviarEmail"){
		$bandera=false;
		$id	=$_POST["idadministrandoInscripciones"];
		$db3 = new Database;
		$db3->connect();
		@$db3->select("solicitudes_inscripcion","*","","idadministrandoInscripciones=$id and sello is not null");
		$result=$db3->getResult();
		$email=$result[0]["email_aspirante"];
		$nombres_aspirante=$result[0]["nombres_aspirante"];
		$eMail = new mail();
		$eMail->para=$email;
		$eMail->asunto="Solicitud para toma de curso";
		$sello=$result[0]["sello"];
		$mensaje=file_get_contents('../pages/emailPreinscripcion.html');
		$eMail->mensaje=str_ireplace('{GUI}',$sello,$mensaje);
		$eMail->mensaje=str_ireplace('{SERVERNAME}',$_SERVER['SERVER_NAME'],$eMail->mensaje);
		$eMail->mensaje=str_ireplace('{NOMBRE}',$nombres_aspirante,$eMail->mensaje);
		if($eMail->enviar()==1){
			if($db3->numRows()>0){
				echo responder("Se ha re-envíado un email al solicitante..","OKrenvio");
			}else{
				echo responder("Ocurrió un error al actualizar al administrador.","ERROR");
			}
		}
	}			
}

if(isset($_POST["accion"])){
	if($_POST["accion"]=="delete"){
		$bandera=false;
		$id	=$_POST["idadministrandoInscripciones"];
		$motivo	=$_POST["motivo"];
		$db3 = new Database;
		$db3->connect();
		@$db3->select("solicitudes_inscripcion","email_aspirante,nombres_aspirante","","idadministrandoInscripciones=$id and sello is null");
		$result=$db3->getResult();
		$email=$result[0]["email_aspirante"];
		$nombres_aspirante=$result[0]["nombres_aspirante"];
		$eMail = new mail();
		$eMail->asunto="Solicitud Declinada";
		$eMail->para=$email;
		$mensaje=file_get_contents('../pages/emailRechazo.html');
		$eMail->mensaje=str_ireplace('{NOMBRE}',$nombres_aspirante,$mensaje);
		$eMail->mensaje=str_ireplace('{MOTIVO}',$motivo,$eMail->mensaje);
		
		if($eMail->enviar()==1){
		@$db3->delete("solicitudes_inscripcion","idadministrandoInscripciones=$id");
		$db3->disconnect();
				if($db3->numRows()>0){
					echo "OK";
				}else{
					echo "ERROR";
				}
		}
	}			
}

if(isset($_POST["accion"])){
	if($_POST["accion"]=="aceptarSolicitud"){
		$bandera=false;
		$id	=$_POST["idadministrandoInscripciones"];
			$fecha = new DateTime();
			$fecha->modify($tiempo_espera);
		$fcaducidadSolicitud=$fecha->format('Y-m-d H:i:s');
		$db3 = new Database;
		$db3->connect();
		@$db3->select("solicitudes_inscripcion","*","","idadministrandoInscripciones=$id");
				$result=$db3->getResult();
				$email=$result[0]["email_aspirante"];
				$idcursoSolicitado=$result[0]["idcursoSolicitado"];
		$db3->select("edicion_cursos","cupo","","id=$idcursoSolicitado");
		$k=$db3->getResult();
		$cupo=0;
		$cupo=$k[0]['cupo'];
		$cupo=$cupo-1;
		if($cupo>=0){
				$nombres_aspirante=$result[0]["nombres_aspirante"];
				$eMail = new mail();
				$eMail->para=$email;
				$eMail->asunto="Solicitud Aceptada";
				$sello=gen_uuid();
				$mensaje=file_get_contents('../pages/emailPreinscripcion.html');
				$eMail->mensaje=str_ireplace('{GUI}',$sello,$mensaje);
				$eMail->mensaje=str_ireplace('{SERVERNAME}',$_SERVER['SERVER_NAME'],$eMail->mensaje);
				$eMail->mensaje=str_ireplace('{NOMBRE}',$nombres_aspirante,$eMail->mensaje);
				if($eMail->enviar()==1){//se genera un pdf con la solicitud y x horas para que pague.
					@$db3->update("solicitudes_inscripcion",array("sello"=>"$sello","fcaducidadSolicitud"=>$fcaducidadSolicitud),"sello is null and idadministrandoInscripciones=$id");
					@$db3->update("edicion_cursos",array("cupo"=>"$cupo"),"id=$idcursoSolicitado");
				echo responder("Se ha aceptado la solicitud, se ha envíado un email al solicitante.");
				}
				$db3->disconnect();
				
		}else{		
				$db3->disconnect();
				echo responder("Lamentablemente el numero de cupos para el curso solicitado ya es 0.<p>La solicitud debe ser rechazada con el comentario pertinente.</p>","OKSinCupos");
		}
	}
}			


if($bandera){
	$db2 = new Database;
	$db2->connect();
	@$db2->select("edicion_cursos",
	"NoDescargas,solicitudes_inscripcion.idadministrandoInscripciones,sello,email_aspirante,nombres_aspirante,apellidos_aspirante,telefono_aspirante,titulo_aspirante,credencial_aspirante,concat(edicion_cursos.faplicacion,' / ',edicion_cursos.haplicacion) as fhora_programada,catalogo_cursos.nombre_curso,catalogo_centros.hospital",
	"catalogo_cursos join catalogo_centros join solicitudes_inscripcion",
	"idcursoSolicitado=edicion_cursos.id and fkIDCh=catalogo_centros.id and fkIDCc=catalogo_cursos.id","sello asc");	
	$adminis=$db2->getResult();
	$db2->disconnect();
	echo (utf8_decode(json_encode($adminis)));
}
?>
