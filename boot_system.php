<?php if(! defined('ABSPATH')) die("Access is forbidden.");
	
	if($show_errors){
		@ini_set('display_errors',1);
		@error_reporting(E_ALL^E_NOTICE);
	}else
		@ini_set('display_errors',0);
		
	@ini_set('output_buffering','4096');
	
	if( file_exists(MODULES) && file_exists(PATH_MOD."actions.php") ){require_once(PATH_MOD."/actions.php");}
	
	$fn=(is_callable($action))?$action:'index';
	
	if(is_callable($fn)){
		
		require_once(ABSPATH."fns.php");
		if( file_exists(MODULES) && file_exists(PATH_MOD."functions.php") ){
			define('MODFNS',PATH_MOD."functions.php");
			require_once(MODFNS);
		}	
		$fn();
	}
	else
		die(require_once("error404.php"));
		
?>
