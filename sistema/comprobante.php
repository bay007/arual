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
		$this->Cell(80,10,utf8_decode("Datos para pago/inscripción"),1,0,"C");
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

	function InfoPago($datos=array()){
		$sello=$datos["sello"];
		$nombres=$datos["nombres_aspirante"];
		$apellidos=$datos["apellidos_aspirante"];
		$nombreCurso=$datos["nombre_curso"];
		$LugarCurso=$datos["hospital"];
		$DireccionCurso=$datos["direccion"];
		$fcurso=$datos["faplicacion"];
		$hcurso=$datos["haplicacion"];
			$this->AliasNbPages();
			$this->AddPage();
			$this->SetFont("Times","",15);
			$this->Cell(0,10,utf8_decode("Estimado $nombres $apellidos"),0,1);$this->Ln(5);
			$this->SetFont("Times","",12);
			$this->Cell(0,10,utf8_decode("Ud está apunto de concluir su proceso de inscripción al curso:"),0,1);$this->Ln(1);
			$this->Cell(0,10,utf8_decode("$nombreCurso"),0,1);$this->Ln(1);
			$this->Cell(0,10,utf8_decode("Que se llevará acabo en $LugarCurso "),0,1);$this->Ln(1);
			$this->SetFont("Times","",10);
			$this->Cell(0,10,utf8_decode("Ubicado en $DireccionCurso"),0,1);$this->Ln(1);
			$this->Cell(0,10,utf8_decode("Que se impartirá el $fcurso a las $hcurso horas."),0,1);$this->Ln(2);
			$this->Cell(0,10,utf8_decode("Para concluir la inscripción es necesario que realice "),0,1);$this->Ln(1);
			$this->Cell(0,10,utf8_decode("un depósito bancario a la cuenta {cuenta} de Banamex por la cantidad de {monto} MNX"),0,1);$this->Ln(1);
			$this->Cell(0,10,utf8_decode(""),0,1);$this->Ln(1);
			$this->SetFont("Times","",11);
			$this->Cell(0,10,utf8_decode("El número de comprobante es: $sello"),0,1);$this->Ln(1);
			$this->SetFont("Times","",10); 
			$this->Cell(0,10,utf8_decode("Con éste número usted deberá subir una fotografia de su boucher de pago en la sección"),0,1);$this->Ln(1);
			$this->Cell(0,10,utf8_decode("'Cursos'->'Concluir Inscripción' de ARUAL (www.arualmr.com)."),0,1);$this->Ln(1);
			$this->Output("Info para $nombres.pdf",'I');
	}
	
	
	
}


	
		
			
			
		
}
catch (Exception $e) {
    echo "error";
}
?>