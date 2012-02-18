<?php  if(! defined('ABSPATH')) die("Access is forbidden."); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?=$title?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.19.1" />
  <meta name="robots" content="all" />
  <meta name="keywords" content="mvc, kisin, kCire, system" />
  <?=getStyle()?>
  <meta charset="UTF-8" />
</head>

<body>
	<div id="shadowBox">
    	<div id="wrapper">
			<div id="formEditor" >
		      	<?=$message?>
				<form action="<?=currentURL()?>" method="post">
					<label for="nombre">Nombre:</label><br/>
					<input type="text" name="nombre" value="<?=$_POST['nombre']?>" id="" /><br/>
					<label for="user">Usuario:(*)</label><br/>
					<input type="text" name="user" value="<?=$_POST['user']?>" id="" /><br/>
					<label for="password">Nuevo password:(*)</label><br/>
					<input type="password" name="password" value="" id="" /><br/>
					<label for="confirm">Introduce nuevamente su password:(*)</label><br/>
					<input type="password" name="confirm" value="" id="" /><br/>
					<label for="mail">Mail:(*)</label><br/>
					<input type="text" name="mail" value="<?=$_POST['mail']?>" id="" /><br/>
					<br/>
					<select name="rol" id="">
						<option value="usuario">Usuario</option>
						<option value="staff">Staff</option>
						<option value="admin">Administrador</option>
					</select>
					<br/><br/>
					<input type="submit" class="button primary" value="Agregar" /><br/>
					<div class="clear" ></div>
		         </form>
		    </div>
  		</div>
  	</div>
</body>
</html>
