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
		<div id="shadowBox" >
			<div id="wrapper" >
				
				<div id="loginBox" class="rounded bshadow">
					<?=$message?>
					<form action="<?=currentURL()?>" method="post">
						<label for="user">Usuario:</label><br/>
						<input type="text" name="user" value="" id="" /><br/>
						<label for="password">Password:</label><br/>
						<input type="password" name="password" value="" id="" /><br/><br/>
						<input type="submit" class="large button flr" value="Entrar" /><br/>
						<div class="clear" ></div>
						<div style="padding:30px 0 0 0;" >
							<a href="<?=ADD_ACTION."forget"?>">No recuerdo mi password</a> | 
							<a href="<?=ADD_ACTION."register"?>">Registrarme</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
