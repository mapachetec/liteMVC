<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	ob_start();
	session_start();
	function index(){
		foreach($_SESSION as $k => $v){
			unset($_SESSION[$k]);
		}
		$redirect=($_SERVER['HTTP_REFERER']&&is_a_url($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:INDEXPATH;
		
		redirect("?".CALL_MOD."=user&".CALL_ACTION."=login");
	}
	
	?>
