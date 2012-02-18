<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	
	function get_menu(){
		?>
		<div id="categories_strip">
		    <ul>
		      <li><a href="<?=ADD_MOD.'panel'?>">Home</a></li>
		      <li ><a href="<?=ADD_ACTION.'publish'?>">Publicar</a></li>
		      <li ><a href="<?=ADD_ACTION.'edit_publish'?>">Editar</a></li>
		      <li ><a href="<?=ADD_MOD.'logout'?>">Salir</a></li>
		    </ul>
		</div>
		<?php
	}

 ?>
