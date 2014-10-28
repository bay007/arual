<?php
session_start();
//header('Content-Type: text/html; charset=UTF-8'); 
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
//if (isset($_SESSION['token']) && $_GET['token'] == $_SESSION['token']){
include("../modelo/arual.php");



$arual=new ARUAL();



//$arual->darAlta('brgw.nebqo@k----u.net');

switch($_GET['accion'])
{
	case 'listar':
	$cursos=$arual->getAltas();
	print $cursos;
	break;
	
	case 'aceptar':

	$cursos=$arual->darAlta($_POST['email'],$_POST['Etapa']);
	print $cursos;
	break;
	
	case 'ListarAlumnos':

	$cursos=$arual->getAlumnosSistema();
	print $cursos;
	break;
	
	
	
	
	
	
		case 'ListarCursos':

	$cursos=$arual->getCursoss();
	print ($cursos);
	break;
	

	case 'CatCursos':
	$cursos=$arual->CatCursos();
	print ($cursos);
	break;
	
		
	
}

//}else{die("No tiene acceso");}
?>