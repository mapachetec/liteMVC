<?php if(! defined('ABSPATH')) die("Access is forbidden.");

/*###########################################################################
				EL ARCHIVO QUE QUIZÁ BUSCAS PARA PODER EDITAR ES
							var_settings.php
#############################################################################*/



/*###########################################################################
				¡¡NO TOQUES SI NO SABES!!
#############################################################################*/
if($host&&$dbname){
	try {
	  $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	}
	catch(PDOException $e) {
	    echo $e->getMessage();
	}
}

