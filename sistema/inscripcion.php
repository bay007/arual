<?php
//header("Content-Type: text/plain; charset=ISO-8859-1");
date_default_timezone_set('America/Mexico_City');setlocale(LC_ALL, "es_MX");
header('Content-Type: text/html; charset=UTF-8'); 
include("mysql_crud.php");
 error_reporting(-1);
 
 
function responder($mensaje,$estado="OK"){
	 if($estado=="OK"){
	 $mensaje="<h4><div class='alert alert-success text-justify' role='alert'>
				<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
				<span class='sr-only'>Error:</span>
				$mensaje
				</div></h4>";	
	return json_encode(array("e"=>$estado,"m"=>$mensaje),JSON_FORCE_OBJECT);	 
	 }else{
		 $mensaje="<h4><div class='alert alert-danger' role='alert'>
					<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
					<span class='sr-only'>Error:</span>
					$mensaje</div></h4>";
	return json_encode(array("e"=>$estado,"m"=>$mensaje),JSON_FORCE_OBJECT);	 
	}
}
 
 function base64ToImage($img,$nombre,$ruta='../inscripciones/aspirantes/'){
	$extension='ERROR';
	list($type, $img) = explode(';', $img);
	list(, $img)      = explode(',', $img);
	$data = base64_decode($img);
	
	if($type=='data:image/gif'){
	$extension=".gif";}
	
	if($type=='data:image/png'){
	$extension=".png";}
	
	if($type=='data:image/jpg'){
	$extension=".jpg";}
	
	if($type=='data:image/jpeg'){
	$extension=".jpg";}
		if($extension!='ERROR'){
		file_put_contents($ruta.$nombre.$extension, $data,LOCK_EX);
		}
		return $nombre.$extension;
	}
 

try{
	
		if(isset($_POST["idcursoSolicitado"])){
		$datos=$_POST;
		$LOGOTIPO=$datos['credencial_aspirante'];
		if($datos["idcursoSolicitado"]!=""){
			$db = new Database;
			$db->connect();
				if($datos["credencial_aspirante"]!=""){//implica que el usuario presenta credencial
				$datos['credencial_aspirante']=sha1($LOGOTIPO);
				$datos['credencial_aspirante']=base64ToImage($LOGOTIPO,$datos['credencial_aspirante']);
				}
				//var_dump($datos);
				if($db->insert("solicitudes_inscripcion",$datos)){
					echo responder("Hemos recibido su solicitud, una vez procesada (máximo en 24 horas) le enviaremos un email con los pasos a seguir para completar su inscripcion a éste curso.");
				}
			$db->disconnect();
			}else{
			echo responder("Debe adjuntar su última credencial de la AHA para continuar.","error");	
			}
		}else{

		
		
	if(isset($_POST["accionConclusion"])){
		if($_POST['accionConclusion']=="accionConclusion"){
			$sello_aspirante=trim(($_POST['sello_aspirante']));
			if($sello_aspirante!=""){
				if(trim($_POST['boucher_aspirante'])!=""){
				$boucher_aspirante=trim($_POST['boucher_aspirante']);
				$db = new Database;
				$db->connect();
				@$db->select("solicitudes_inscripcion","idcursoSolicitado as idcursoSolicitado_pago
				,email_aspirante as email_aspirante_pago
				,nombres_aspirante as nombres_aspirante_pago
				,apellidos_aspirante as apellidos_aspirante_pago
				,telefono_aspirante as telefono_aspirante_pago
				,titulo_aspirante as titulo_aspirante_pago
				,credencial_aspirante as boucher_aspirante_pago
				,sello as sello_pago","","sello='$sello_aspirante'");
	
				@$pendientedePago=$db->getResult();
					if(count($pendientedePago,COUNT_RECURSIVE)>0){
						$nombreBoucher=base64ToImage($boucher_aspirante,sha1($boucher_aspirante),'../inscripciones/comprobantes_pago/');
						$OLD_Image=$pendientedePago[0]["boucher_aspirante_pago"];
						$pendientedePago[0]["boucher_aspirante_pago"]=$nombreBoucher;
						$db->insert("solicitudes_inscripcion_pago",$pendientedePago[0]);
						if($db->numRows()>0){
						$db->delete("solicitudes_inscripcion","sello='$sello_aspirante'");
							unlink("../inscripciones/aspirantes/".$OLD_Image);
							if($db->numRows()>0){
							echo responder("Hemos recibido su boucher de pago, una vez procesado (máximo en 24 horas) le enviaremos un email con una liga de acceso para descargar el material didáctico previo al curso.");
							}
						}else{
							echo responder("No fue posible procesar la solicitud en éste momento.","error");
						}
					}else{
						echo responder("Su comprobante de pago no es el que tenemos registrado.","error");
					}
				$db->disconnect();
				}else{
				echo responder("Debe adjuntar una imagen con su boucher de pago para poder continuar.","error");
				}
			}else{
			echo responder("Por favor, proporcionenos su número de comprobante.","error");	
			}
		}
	}
	
	
	if(isset($_GET["accion"])){
		if($_GET['accion']=="detalleCurso"){
		$nombre_cursos=trim(($_GET['nombre_cursos']));
		$db = new Database;
		$db->connect();
		@$db->select("catalogo_cursos","contenido,duracion,requisitos,publico_dirigido","edicion_cursos ec"," nombre_curso like '%$nombre_cursos%' and ec.fkIDCc=catalogo_cursos.id and ec.activo='Si'");
		@$detalleCurso=$db->getResult();
		$db->disconnect();
		echo json_encode($detalleCurso);
		}
	}
	
	if(isset($_GET["accion"])){
		if($_GET['accion']=="centosConCurso"){
		$nombre_cursos=trim(urldecode($_GET['nombre_cursos']));
		$k=0;
		$l=0;
		$db = new Database;
		$db2 = new Database;
		$db->connect();
		@$db->select("edicion_cursos",'hospital,telefono,direccion,edicion_cursos.updated',"catalogo_centros join catalogo_cursos",'date(faplicacion) >= date(now()) and edicion_cursos.activo="Si" and fkIDCh=catalogo_centros.id and catalogo_cursos.nombre_curso like "%'.$nombre_cursos.'%"  and fkIDCc=catalogo_cursos.id group by hospital');
		@$centrosConCurso=$db->getResult();
		@$db->disconnect();
		$DetallesDeCentro=array();
		$entrada="";
		$disponibilidad='<sup>Última actualización: {updated}</sup><h5 id="{hospital}"><a> <img src="images/gm.png"></img></a>
		</h5>
		<div><strong> Lugar: </strong>{hospital}</div>
		<div><span class="glyphicon glyphicon-earphone"></span><strong> Teléfono: </strong>{telefono}</div> ';
		$disponibilidad.='<p><strong><span class="glyphicon glyphicon-map-marker"></span>Dirección:</strong>{direccion}.</p>';
		require("detalles_cursos.php");

		foreach($centrosConCurso as $v){
		$l=$l+1;	
		$db2->connect();
		@$db2->select("edicion_cursos",'edicion_cursos.id,concat(date_format(date(faplicacion),"%a %d de %M del %Y")," a las ",date_format(haplicacion,"%T")," horas.") as faplicacion,cupo,lespecifico',"catalogo_centros join catalogo_cursos",'date(faplicacion) >= date(now()) and edicion_cursos.activo="Si" and fkIDCh=catalogo_centros.id and catalogo_cursos.nombre_curso like "%'.$nombre_cursos.'%" and fkIDCc=catalogo_cursos.id and hospital like"%'.$v["hospital"].'%" order by faplicacion asc');
		$DetallesDeCentro=array();
		@$DetallesDeCentro=$db2->getResult();
		@$db2->disconnect();
				$a=date('d-M-Y, h:i A', strtotime($v["updated"]));
				@$entrada=$entrada.str_ireplace('{hospital}',@$v["hospital"],$disponibilidad);
				@$entrada=str_ireplace('{direccion}',@$v["direccion"],$entrada);
				@$entrada=str_ireplace('{telefono}',@$v["telefono"],$entrada);
				@$entrada=str_ireplace('{updated}',$a,$entrada);
				@$entrada=$entrada.'<div class="panel-body" id="detallesCursos{k}"><div id="accordion{l}" class="panel-group">';
				foreach($DetallesDeCentro as $x){
					$k=$k+1;
					@$entrada=$entrada.str_ireplace('{faplicacion}',$x["faplicacion"],$detallesCurso);
					@$entrada=str_ireplace('{cupo}',$x["cupo"],$entrada);
					@$entrada=str_ireplace('{lespecifico}',$x["lespecifico"],$entrada);
					@$entrada=str_ireplace('{k}',$k,$entrada);
					@$entrada=str_ireplace('{id}',@$x["id"],$entrada);
					@$entrada=str_ireplace('{l}',$l,$entrada);
				}
				@$entrada=$entrada.'</div></div><hr>';
			}
		echo ($entrada.'<script>
		$("h5 a").click(function(){var h5=$(this).parent();
		$.get( "contacto.html", function( data ) {
			$(".menu li").removeClass("current");
			$(".contacto_menu_li").addClass("current");
		$("#contenido").replaceWith( data.replace("{lugar}",h5.attr("id").trim()));
			});
		});</script>');
		}
	}
	}
}
catch (Exception $e) {
    echo 'error';
}
?>