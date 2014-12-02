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
    <link rel="stylesheet" type="text/css" media="screen" href="css/slider.css">
    <link href='http://fonts.googleapis.com/css?family=Condiment' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    <script src="js/jquery-1.7.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/tms-0.4.x.js"></script>
	
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script>
		$(document).ready(function(){				   	
			$('.slider')._TMS({
				show:0,
				pauseOnHover:true,
				prevBu:false,
				nextBu:false,
				playBu:false,
				duration:1000,
				preset:'fadeThree',
				pagination:true,
				pagNums:false,
				slideshow:4000,
				numStatus:true,
				banners:'fromRight',
				waitBannerAnimation:false,
				progressBar:false
			})		
		});
	</script>

</head>
<body>
<div class="container main">
<div style="height: 125px;" class="row">
	<div id="logo" class="col-xs-5 col-xs-offset-0 col-md-5 col-md-offset-1 animated zoomIn" >
		<h1><a href="index.php"><img class="img-responsive" src="images/Imagen1.png" alt="" style="height: 120px;position:fixed;"/></a></h1>
	</div>
	<div id="logo" class="col-xs-5 col-sm-offset-8 animated zoomIn" >
		<img class="img-responsive" src="images/aha_logo4.png" alt="" style="height: 120px;position:fixed;"/>
	</div>
</div>


<div class="col-md-10 col-md-offset-1"> 	
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
<div id="contenido" class="animated fadeInRight" style="min-height:480px;"> 
		<div class="col-md-8 col-md-offset-0">
		   <div id="slide" class="box-shadow">
					<div class="slider">
						<ul class="items">
							<li><img class="img-responsive" src="images/slider-1.jpg" alt="" /><div class="banner lead" >ARUAL medicina de reanimacion&nbsp;</div></li>
							<li><img class="img-responsive" src="images/slider-2.jpg" alt="" /><div class="banner lead">Centro de entrenamiento internacional&nbsp;</div></li>
							<li><img class="img-responsive" src="images/slider-3.jpg" alt="" /><div class="banner lead">Centro de enseñanza e investigacion&nbsp;</div></li>
						</ul>
					</div>
				</div>
		</div>
		
		<div class="logotipos_asociados">
		<?php
		$files = glob("logotipo/*.*");
		$key = array_search("logotipo/logotipo.png",$files);
		if($key!==false){
			unset($files[$key]);
		}
		unset($files['logotipo/logotipo.png']);
		for ($i=0; $i<count($files); $i++){
		$image = $files[$i];
		echo '<div class="col-xs-2 col-md-offset-0">';
		echo '<img class="img-responsive img-thumbnail" src="'.utf8_encode($image) .'" alt="Random image" style="height:82px;" />';
		echo '</div>';
		}
		?>
		</div>
</div>
</div>	

<footer style="height:100px">
	<p id="fecha"></p>
	<p><a style="font-size:19px;">ARUAL</a> Medicina de reanimación.</p>
	<p>Centro de entrenamiento internacional.</p>
</footer>


</body>
 <script >
 var firstVisit=true;
 $(document).ready(function() {	
 $('#logo').css({ "display":" none" });
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