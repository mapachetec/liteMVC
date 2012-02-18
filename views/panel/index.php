<?php  if(! defined('ABSPATH')) die("Access is forbidden."); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$title?></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="generator" content="Geany 0.19.1" />
		<?=getStyle()?>
	</head>

	<body>
		<?=get_menu()?>
		<div id="shadowBox" >
			<div id="wrapper" >
				<h2 class="head">Bienvenido <?=$_SESSION['user']?></h2>
				<div class="quick" >
					<a href="<?=ADD_ACTION.'publish'?>" class="dashboard_button">
						<img src="<?=VIEW_FILES?>imgs/icoaddimage.png" class="fll" />
						<span class="dashboard_button_heading">Publicar un auto</span>
						<div class="clear" ></div>
						<span>Publica el auto que quieres vender y sube sus imágenes</span>
					</a>
				</div>
				<div class="quick" >
					<a href="<?=ADD_ACTION.'edit_publish'?>" class="dashboard_button">
						<img src="<?=VIEW_FILES?>imgs/editico.png" class="fll" />
						<span class="dashboard_button_heading">Editar</span>
						<div class="clear" ></div>
						<span>Edita la información de los autos antes publicados</span>
					</a>
				</div>
			</div>
		</div>
	</body>
</html>
