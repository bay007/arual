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
		header("Location: index.php");
		die();
		}
}
else{ 
	if(isset($_GET['sello'])){ 
		$sello=$_GET['sello'];
			$db = new Database;
			$db->connect();
			@$db->select("edicion_cursos",
			"sello,date_format((fcaducidadSolicitud),'%a %d de %M del %Y a las %h:%i %p') as fcaducidadSolicitud,nombres_aspirante,NoDescargas,
			apellidos_aspirante,date_format(date(edicion_cursos.faplicacion),'%a %d de %M del %Y') as faplicacion,edicion_cursos.haplicacion,
			catalogo_cursos.nombre_curso,catalogo_centros.hospital,catalogo_centros.direccion,lespecifico,banco,noCuenta,costo",
			"catalogo_cursos join catalogo_centros join solicitudes_inscripcion",
			"idcursoSolicitado=edicion_cursos.id and fkIDCh=catalogo_centros.id and fkIDCc=catalogo_cursos.id and sello like '$sello'");
			
			@$resultado=$db->getResult();
			if($db->numRows()>0){
				$descargas=$resultado[0]["NoDescargas"];
				$descargas++;							//aumentar el numero de descargas que se generan en la solicitud
				@$db->update("solicitudes_inscripcion",array("NoDescargas"=>"$descargas"),"sello like '$sello'");
				$db->disconnect();
				include("sistema/comprobante.php");
				$pdf = new PDF();
				$pdf->InfoPago($resultado[0]);
			}else{
			
			@$db->select("edicion_cursos",
			"sello_pago as sello,nombres_aspirante_pago as nombres_aspirante,apellidos_aspirante_pago as apellidos_aspirante,
			date_format(date(edicion_cursos.faplicacion),'%a %d de %M del %Y') as faplicacion,edicion_cursos.haplicacion,catalogo_cursos.nombre_curso,
			catalogo_centros.hospital,catalogo_centros.direccion,lespecifico,banco,noCuenta,costo",
			"catalogo_cursos join catalogo_centros join solicitudes_inscripcion_pago",
			"idcursoSolicitado_pago=edicion_cursos.id and fkIDCh=catalogo_centros.id and fkIDCc=catalogo_cursos.id and sello_pago like '$sello'");
			
			@$resultado=$db->getResult();
			
				if($db->numRows()>0){
					$db->disconnect();
					include("sistema/comprobante.php");
					$pdf = new PDF();
					$pdf->InfoPago($resultado[0],$db->numRows()>0);
				}else{
					header("Location: index.php");
				}
			}
	}else{
		header("Location: index.php");
	}
}
?>