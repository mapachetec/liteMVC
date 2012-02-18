<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	
	function index(){
		$d['title']='Welcome to Hello Wordl!';
		
		load_view('index',$d);
		
		}?>
