<?php
session_start();
//header('Content-Type: text/html; charset=UTF-8'); 
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
//if (isset($_SESSION['token']) && $_GET['token'] == $_SESSION['token']){
include("../modelo/arual.php");
$arual=new ARUAL();


if(isset($_GET['action'])&&(0==strcmp($_GET['action'],'cursosDisponibles'))){
$pagina=file_get_contents("../vista/services.html");
$pagina=str_replace('{ListaCursos}',$arual->getTodosCursos(),$pagina);
$pagina=str_replace('{token}',$_SESSION['token'],$pagina);
echo $pagina;	
}


if(isset($_GET['action'])&&(0==strcmp($_GET['action'],'tipoCurso'))){
echo $arual->getDetalleCurso( $_GET['tipo']);
}


//echo $arual->getTodosCursos();




//}else{die("No tiene acceso");}
?>