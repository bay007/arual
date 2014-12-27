<!DOCTYPE html>
<html lang="es">
<head>
    <title>ARUAL</title>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/reset.css">
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
    
	<link rel="stylesheet" type="text/css" media="screen" href="css/animate.css">
	
    <link rel='stylesheet' id='camera-css'  href='css/camera.css' type='text/css' media='all'> 
    <link href='http://fonts.googleapis.com/css?family=Condiment' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    <script src="js/jquery-1.7.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    
	<script type='text/javascript' src='js/camera.js'></script> 
	
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
	<style>
		
		.fluid_container {
			margin: 0 auto;
			max-width: 1000px;
			width: 90%;
		}
	</style>
	
    <script>
		jQuery(function(){
			jQuery('#camera_random').camera({
				thumbnails:false ,
				time:2500,
				loader: 'bar'
			});

		});
	</script>

</head>
<body>
<div class="container main">
<div  class="row animated zoomIn" id="logo">
	<div  class="col-md-4">
		<img class="img-responsive" src="images/Imagen1.png" alt="" style="height:100px"/>
	</div>
	<div  class="col-md-4">
		<img class="img-responsive" src="images/FCCS.png" alt="" style="height:100px"/>
	</div>	
	<div  class="col-md-4">
		<img class="img-responsive" src="images/aha_logo4.png" alt="" style="height:90px"/>
	</div>
</div>


<div class="col-md-12"> 	
	<nav  class="navbar navbar-default  animated fadeInLeft" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
		<span class="icon-bar"></span>
      </button>
	  <ul class=" menu">
      <li data-url="index.php" class="home-page current"><a ><span></span></a></li>
	   </ul>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class=" menu">
			<li data-url="nosotros.html"><a >Quienes somos</a></li>
			<li data-url="cursos.html" ><a>Cursos</a></li>
			<li data-url="contacto.html"><a >Contacto</a></li>
		</ul>
		<div class="social-icons ">
		<!--<a href="#" class="icon-3 "></a>-->
			<a href="#" class="icon-2"></a>
			<a href="#" class="icon-1"></a>
		</div>
     </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
 </div>
 
<div id="contenido" class="animated fadeInRight " style="min-height:480px;"> 
		<div class="col-md-7 col-md-offset-0">
			 <div class="fluid_container">
				 <div class="camera_wrap camera_charcoal_skin" id="camera_random">
							<div data-thumb="images/slider-1.jpg" data-src="images/slider-1.jpg">
								<div class="camera_caption moveFomRight">
									<em>ARUAL</em> medicina de reanimacion&nbsp.
								</div>
							</div>
							<div data-thumb="images/slider-2.jpg" data-src="images/slider-2.jpg">
								<div class="camera_caption moveFomRight">
									Centro de entrenamiento <em>internacional&nbsp</em>.
								</div>
							</div>
							<div data-thumb="images/slider-3.jpg" data-src="images/slider-3.jpg">
								<div class="camera_caption moveFomRight">
									Centro de enseñanza e investigacion&nbsp
								</div>
							</div>
					</div><!-- #camera_random -->
			</div>			
		</div>
		
		<div class="logotipos_asociados">
		<?php
		$files=array();
		$files = glob("logotipo/*.*");
        if(0<count($files)){
            $key = @array_search("logotipo/logotipo.png",$files);
			if($key!==false){
				$files[$key]=($files[$key]);
				unset($files[$key]);
			}
			unset($files['logotipo/logotipo.png']);
			for ($i=0; $i<count($files); $i++){
			$image = $files[$i];
			echo '<div class="col-md-2">';
			echo '<img class="img-responsive img-thumbnail" src="'.$image.'" alt="Random image" style="height:110px;" />';
			echo '</div>';
			}
		}
		?>
		</div>
</div>
</div>	

<footer style="height:100px">
	<p id="fecha"></p>
	<p><a style="font-size:19px;">ARUAL</a> Medicina de reanimación.</p>
	<p>CENTRO DE ENTRENAMIENTO INTERNACIONAL.</p>
</footer>


</body>
 <script >
 var firstVisit=true;
 $(document).ready(function() {	
 // $('#logo').css({ "display":" none" });
	 $('.menu li a').click(function(event){
		var url=$(this).parent().data().url;
		event.preventDefault();
		$('.menu li').removeClass("current");
			$(this).parent().addClass("current");
			
			if(url=="index.php"){
			window.location.href=url;
			}else{ 
			$("#contenido" ).removeClass();
			$("#contenido" ).addClass('animated fadeOut');
			 
			$('#contenido').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
					$.get(url, function( data ) {
						$("#contenido").replaceWith(data);
					});
				});
			}
	});
	$('.navbar').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
				 $('#logo').css({ "display":" inherit" });
				 $('#logo').addClass('animated zoomIn'); 
				 
				});
	}); 
var d = new Date();
var n = d.getFullYear(); 
$("#fecha").text("© "+n); 
  </script>
</html>