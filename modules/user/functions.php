<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	
	function generateHash($plainText, $salt = null){
	    if ($salt === null){
	        //$salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
	        $salt = md5(SECRET_KEY);
	    }
	    else{
	        $salt = substr($salt, 0, SALT_LENGTH);
	    }
	
	    return sha1($salt . $plainText);
	}
	
	function add_new_user($user,$nombre,$password,$mail,$nivel,$activo){
		return do_query(
			"insert into usuarios(login,nombre,nivel,nicename,registro,activo,mail,passwd) values(?,?,?,?,?,?,?,?)",
			array($user,$nombre,$nivel,sanitize_title_with_dashes($user),get_date(),intval($activo),$mail,generateHash($password))
			);
	}
	
	function is_valid_user($user){
		return preg_match('/^[a-zA-Z0-9]{3,10}+$/',$user);
	}
	
	function user_exist($user){
		return get_results("select * from usuarios where login = ?",array($user));
	}
	
	function init_session($u){
		
		$data=get_results("select * from usuarios where login = ?",array($u));
		$r=$data->fetchAll();
		
		$_SESSION['user'] = $r[0]['login'];
		$_SESSION['userid'] = $r[0]['id'];
		$_SESSION['nombre'] = $r[0]['nombre'];;
		$_SESSION['mail'] = $r[0]['mail'];
		$_SESSION['ip'] = get_ip();
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		$_SESSION['login'] = 'on';
		$_SESSION['nivel'] = $r[0]['nivel'];;
		$_SESSION['status'] = intval($r[0]['activo']);
		
	}

 ?>
