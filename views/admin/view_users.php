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
	<?=get_menu()?>
	<div id="shadowBox">
		
    	<div id="wrapper" style="background:#fff">
			<?=$message?>
			<a href="<?=ADD_ACTION.'useradd'?>" class="button small">Agregar un nuevo usuario</a>
			<div class="clear" ></div>
			<?php if($users): ?>
				<?php foreach($users as $user): ?>
					<h3><?=$user['login']?> | <a href="<?=ADD_ACTION?>userdel&id=<?=$user['id']?>">Eliminar</a></h3>
				<?php endforeach ?>
			<?php else: ?>
				<h2 style="text-align:center">No hay usuarios</h2>
			<?php endif ?>
				
  		</div>
  	</div>
</body>
</html>
