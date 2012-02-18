<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	ob_start();
	session_name('Chetucar');
	session_start();
	/*###########################################################################
					ADMIN
	#############################################################################*/
	function index(){
		protect_session('admin');
		$d['title']='Panel de administración | '.$_SESSION['user'];
		load_view('index',$d);
		
	}
	
	function userdel(){
		protect_session('admin');
		
		$d['title']='Panel de administración | Borrar';	
		if(setData(array('id'),'get')){
			if(do_query("delete from usuarios where id = ? and login <> ?",array(intval($_GET['id']),$_SESSION['user'])))
				redirect(ADD_ACTION.'users&deleted=true');
			else
				redirect(ADD_ACTION.'users&deleted=false');
				
		}
		

	}
	
	function users(){
		protect_session('admin');
		$data=get_results("select * from usuarios where login <> ?",array($_SESSION['user']));
		$d['title']='Panel de administración | Usuarios';		
		if($data)
			$d['users']=$data->fetchAll();
		if(setData(array('deleted'),'get')){
			if($_GET['deleted']=='true')
				set_message('confirm','Usuario eliminado');
			else
				set_message('error','Ocurrió un error eliminando');
		}
		
		load_view('view_users',$d);
		
	}
	
	function useradd(){
		protect_session('admin');
		$d['title']='Panel de administración | Agregar usuario';
		$d['seccion_adm']='Agregar usuarios';
		
		if(setData(array('user','mail','password','confirm'))){
			foreach ($_POST as $k => $v) $$k=$v;
			if($user!=''&&!is_valid_user($user)){
				set_message('error','
					<ul> 
						El nombre de usuario solo puede:
						<li>Contener letras y/o números, sin espacios</li>
						<li>Tener como longitud: Min: 3 - Max:10</li>
					</ul>'
					
				 );
			}
			else if(user_exist($user)){
				set_message('error',"El usuario <b>$user</b> ya existe");
			}
			else if(strcmp($password,$confirm)!=0){
				set_message('error','Los passwords no coinciden');
			}
			else if(!is_a_mail($mail)){
				set_message('error','El correo no es válido');
			}
			else{
				/*Todos los datos son correctos. Aqui se da de alta en la BD y se crean sesiones*/
				if(add_new_user($user,$nombre,$password,$mail,$rol,1))
					set_message('confirm','Usuario agregado con éxito');
				else
					set_message('error','Error');
					
			}
			
		}else{set_message('info','Los campos marcados con (*) son obligatorios');}
		
		load_view('useradd',$d);
		
	}
	
	?>
