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
				$db = new Database;
				$db->connect();
				$db->select("edicion_cursos","idadministrandoInscripciones_pago
				,email_aspirante_pago,nombres_aspirante_pago,apellidos_aspirante_pago,telefono_aspirante_pago,titulo_aspirante_pago,titulo_aspirante_pago,sello_pago,verificado,concat(edicion_cursos.faplicacion,' / ',edicion_cursos.haplicacion) as fhora_programada_pago,catalogo_cursos.nombre_curso,catalogo_centros.hospital",
				"catalogo_cursos join catalogo_centros join solicitudes_inscripcion_pago",
				"idcursoSolicitado_pago=edicion_cursos.id and fkIDCh=catalogo_centros.id and fkIDCc=catalogo_cursos.id","verificado desc");
				
					if($db->numRows()>0){
					//$adminis=array("0"=>array("id"=>"18","sello_solicitud"=>"0c224cc4","titulo_aspirante"=>"Dr","email_aspirante"=>"a@a.com","nombres_aspirante"=>"nombre_aspirante","apellidos_aspirante"=>"apellidos_aspirante","telefono_aspirante"=>"telefono_aspirante","lugar_aplicacion"=>"Pedregal Angeles","fhora_programada"=>"2015-10-22/16:20:00","curso_solicitado"=>"SALVACORAZONES PRIMEROS AUXILIOS CON RCP Y DEA","descarga_instrucciones"=>"No"));
					$Resp=$db->getResult();
					$db->disconnect();
					echo (utf8_decode(json_encode($Resp)));
					}
				
				}
		
?>