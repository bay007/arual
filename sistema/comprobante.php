<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
date_default_timezone_set("America/Mexico_City");setlocale(LC_ALL, "es_MX");
include("mvc/apps/fpdf/fpdf.php");
error_reporting(-1);
try{
	class PDF extends FPDF{
	// Cabecera de página
		function Header(){
			// Logo
			$this->Image("images/Imagen1.gif",10,8,33);
			// Arial bold 15
			$this->SetFont("Arial","B",15);
			// Movernos a la derecha
			$this->Cell(60);
			// Título
			$this->Cell(80,10,utf8_decode("Inscripción"),1,0,"C");
			// Salto de línea
			$this->Ln(20);
		}
		// Pie de página
		function Footer(){
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont("Arial","I",8);
			// Número de página
			$this->Cell(0,10,"Page ".$this->PageNo()."/{nb}",0,0,"C");
		}

		function InfoPago($datos=array(),$pagado=false){
			$sello=$datos["sello"];
			$nombres=$datos["nombres_aspirante"];
			$apellidos=$datos["apellidos_aspirante"];
			$nombreCurso=$datos["nombre_curso"];
			$LugarCurso=$datos["hospital"];
			$DireccionCurso=$datos["direccion"];
			$fcurso=$datos["faplicacion"];
			$hcurso=$datos["haplicacion"];
			$lespecifico=$datos["lespecifico"];
			$costo=$datos["costo"];
			$banco=$datos["banco"];
			$noCuenta=$datos["noCuenta"];
			if(!$pagado){
			$fcaducidadSolicitud=$datos["fcaducidadSolicitud"];
			}
				$this->AliasNbPages();
				$this->AddPage();
				$this->SetFont("Times","",15);
				$this->Cell(0,10,utf8_decode("Estimado $nombres $apellidos"),0,1);$this->Ln(5);
				$this->SetFont("Times","",11);
				if(!$pagado){
					$this->Cell(0,10,utf8_decode("Ud está apunto de concluir su proceso de inscripción al curso:"),0,1);$this->Ln(1);
				}else{
					$this->Cell(0,10,utf8_decode("Ud concluyó con éxito su inscripción al curso:"),0,1);$this->Ln(1);
				}
				$this->Cell(0,10,utf8_decode("$nombreCurso"),0,1);$this->Ln(1);
				$this->Cell(0,10,utf8_decode("Un lugar para usted ha sido reservado."),0,1);$this->Ln(1);
				$this->Cell(0,10,utf8_decode("El curso se llevará acabo en $LugarCurso ($lespecifico) "),0,1);$this->Ln(1);
				$this->SetFont("Times","",10);
				$this->Cell(0,10,utf8_decode("Ubicado en $DireccionCurso"),0,1);$this->Ln(1);
				$this->Cell(0,10,utf8_decode("Que se impartirá el $fcurso a las $hcurso horas."),0,1);$this->Ln(2);
				if(!$pagado){
					$this->Cell(0,10,utf8_decode("Para concluir la inscripción es necesario que realice "),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode("un depósito bancario a la cuenta $noCuenta del banco $banco por la cantidad de $costo MNX"),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode(""),0,1);$this->Ln(1);
				}
				$this->SetFont("Times","",11);
				$this->Cell(0,10,utf8_decode("Su codigo de identificación es: $sello"),0,1);$this->Ln(1);
				$this->SetFont("Times","",10); 
				if(!$pagado){
					$this->Cell(0,10,utf8_decode("Con éste código usted deberá subir una fotografia de su boucher de pago en la sección"),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode("'Cursos'->'Concluir Inscripción' de ARUAL (www.arualmr.com)."),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode("A mas tardar el $fcaducidadSolicitud (48 horas apartir de éste momento)"),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode("De lo contrario su lugar será re asignado a otra persona."),0,1);$this->Ln(1);
				}else{
					$this->Cell(0,10,utf8_decode("-Éste comprobante deberá ser presentado el día del curso."),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode("-Su credencial más actual de la AHA deberá ser presentada también."),0,1);$this->Ln(1);
					$this->Cell(0,10,utf8_decode("-Su boucher de pago deberá ser presentado en original."),0,1);$this->Ln(1);
				}
				$this->Output("Info para $nombres.pdf",'I');
		}
	}
}
catch (Exception $e) {
    echo "error 0x875421";
}
?>