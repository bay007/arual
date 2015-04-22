<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
include("seguridad.php");
include("mail.php");
error_reporting(-1);

function gen_uuid() {
    return sprintf( '%04x%04x',mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
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
		$eMail = new mail();
		$eMail->para=$email;
		$eMail->asunto="Solicitud para toma de curso";
		$sello=$result[0]["sello"];
		$mensaje=file_get_contents('../pages/emailPreinscripcion.html');
		$eMail->mensaje=str_ireplace('{GUI}',$sello,$mensaje);
		$eMail->mensaje=str_ireplace('{SERVERNAME}',$_SERVER['SERVER_NAME'],$eMail->mensaje);
		if($eMail->enviar()==1){
			if($db3->numRows()>0){
				echo "OKr";
			}else{
				echo "ERROR";
			}
		}
		//@$db3->delete("solicitudes_inscripcion","idadministrandoInscripciones=$id");	
	}			
}

if(isset($_POST["accion"])){
	if($_POST["accion"]=="aceptarSolicitud"){
		$bandera=false;
		$id	=$_POST["idadministrandoInscripciones"];
		$db3 = new Database;
		$db3->connect();
		@$db3->select("solicitudes_inscripcion","*","","idadministrandoInscripciones=$id");
		$result=$db3->getResult();
		$email=$result[0]["email_aspirante"];
		$eMail = new mail();
		$eMail->para=$email;
		$sello=gen_uuid();
		$mensaje=file_get_contents('../pages/emailPreinscripcion.html');
		$eMail->mensaje=str_ireplace('{GUI}',$sello,$mensaje);
		$eMail->mensaje=str_ireplace('{SERVERNAME}',$_SERVER['SERVER_NAME'],$eMail->mensaje);
		if($eMail->enviar()==1){
		@$db3->update("solicitudes_inscripcion",array("sello"=>"$sello"),"sello is null and idadministrandoInscripciones=$id");$db3->disconnect();
		
		if($db3->numRows()>0){
					echo "OK";
				}else{
					echo "ERROR";
				}
		}
		//@$db3->delete("solicitudes_inscripcion","idadministrandoInscripciones=$id");	
	}			
}

if($bandera){
	$db2 = new Database;
	$db2->connect();
	@$db2->select("edicion_cursos",
	"solicitudes_inscripcion.idadministrandoInscripciones,sello,email_aspirante,nombres_aspirante,apellidos_aspirante,telefono_aspirante,titulo_aspirante,credencial_aspirante,concat(edicion_cursos.faplicacion,' / ',edicion_cursos.haplicacion) as fhora_programada,catalogo_cursos.nombre_curso,catalogo_centros.hospital",
	"catalogo_cursos join catalogo_centros join solicitudes_inscripcion",
	"idcursoSolicitado=edicion_cursos.id and fkIDCh=catalogo_centros.id and fkIDCc=catalogo_cursos.id","sello asc");	
	$adminis=$db2->getResult();
	$db2->disconnect();
	echo (utf8_decode(json_encode($adminis)));
}
				

?>
