<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	ob_start();
	session_name('Chetucar');
	session_start();
		
	function index(){
		protect_session('usuario');
		if (isset($_SESSION['nivel'])){
			
			if($_SESSION['nivel']=='admin')
				redirect(ADD_MOD.'admin');
			
			
				$d['title']='Panel de usuario';
				load_view('index',$d);
		}
		
	}?>
