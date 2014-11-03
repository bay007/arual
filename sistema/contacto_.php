<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
 
try {
// if (!isset($_GET["activo"]))
// {
    // $_GET['activo'] = '0';
// }


	
	if(isset($_GET["accion"])){
		if($_GET['accion']=="UbicacionCurso"){
		$nombre_hospital=trim(urldecode($_GET['nombre_hospital']));
		$db = new Database;
		$db->connect();
		@$db->select("catalogo_centros","latitud as lat,longitud as lng",""," hospital like '".$nombre_hospital."'");
		@$detalleCurso=$db->getResult();
		$db->disconnect();
		echo json_encode($detalleCurso);
		}
	}
	 
	if(isset($_GET["accion"])){
		if($_GET['accion']=="detalleCentros"){
		$c=0;
		$db = new Database;
		$db->connect();
		@$db->select("catalogo_centros",'*',"",'activo=1');
		@$centros=$db->getResult();
		@$db->disconnect();
		$entrada=null;
		$detalle_centros=file_get_contents('../pages/contacto_a.html');
				foreach($centros as $v){
				@$entrada=$entrada.str_ireplace('{hospital}',@$v["hospital"],$detalle_centros);
				@$entrada=str_ireplace('{direccion}',@$v["direccion"],$entrada);
				@$entrada=str_ireplace('{telefono}',@$v["telefono"],$entrada);
				@$entrada=str_ireplace('{contacto}',@$v["contacto"],$entrada);
				@$entrada=str_ireplace('{email}',@$v["email"],$entrada);
				@$entrada=str_ireplace('{logotipo}',@$v["logotipo"],$entrada);
				@$entrada=str_ireplace('{c}',$c,$entrada);
			$c=$c+1;
			}
		echo ('<div id="accordion" class="panel-group">'.$entrada."</div>");
		}
	}
}
catch (Exception $e) {
    echo 'error';
}

?>