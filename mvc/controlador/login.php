<?php
session_start();

if (isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']){
include("../modelo/arual.php");
	$arual=new ARUAL();
	$resultado=$arual->isAlumno($_POST['email'],$_POST['pphase']);
	
	if($resultado['r']){
	
	
		switch ($resultado[0][0]['tipo']) {
			case '1':
			$template=file_get_contents("../vista/lego_alumno.jfe");
			$template=str_replace("{nombre}","Bienvenido Alumno de la salud ".$resultado[0][0]['nombre'],$template);
			$template=str_replace("{token}",$_SESSION['token'],$template);
echo $template;


        break;
			case '2':
		echo "Bienvenido alumno ".$resultado[0][0]['nombre_completo'];
		break;
			
	}
	
	
	}else{
	$resultado=array();
	$resultado=$arual->isAdmin($_POST['email'],$_POST['pphase']);
		if($resultado['r']){
		
		
		
		switch ($resultado[0][0]['tipo']) {
			case '1':
			$template=file_get_contents("../vista/dirigente.jfe");
			$template=str_replace("{nombre}","Bienvenido Administrador ".$resultado[0][0]['nombre_completo'],$template);
			
		$template=str_replace("{token}",$_SESSION['token'],$template);
echo $template;
        break;
			case '2':
		echo "Bienvenido Operador ".$resultado[0][0]['nombre_completo'];
		break;
			
	}
		
		
		
		}
		else{
		die("No es miembro :S");
		}
	
	}
	
	
	
	
	
}
else{
echo "Resctricted Access";
}
?>