<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	ob_start();
	session_name('Chetucar');
	session_start();
	/*###########################################################################
					LOGIN
	#############################################################################*/
	function index(){
		$d['title']='Login';
		if(isset($_POST['user'])&&isset($_POST['password'])){
			if(trim($_POST['user'])!==''&&trim($_POST['password'])!==''){
				$user=$_POST['user'];
				$pass=$_POST['password'];
				$data=get_results("select * from usuarios where login = ?",array($user));
				
				if($data){
					if($data->rowCount()==1){
						
						$r=$data->fetchAll();
						//Si los datos son correctos ;>...
						if($r[0]['activo']!=1){
							set_message('error','Lo siento, tu usuario ha sido inhabilitado. Contacta al administrador');
						}
						else if(strcmp(generateHash($pass),$r[0]['passwd'])==0){
							set_message('confirm','Te has logeado naker!');
							init_session($user);
						}
						else{set_message('error','Usuario o contraseña incorrecta');}
					}
					else{set_message('error','ERROR INESPERADO (Hack Attempt). El sistema de login se desactivará 12 hrs por seguridad y se le enviará un correo al administrador informándole del error');}
					
				}
				else{set_message('error','Usuario o contraseña incorrecta');}
				
			}else{set_message('info','Ingresa tu nombre de usuario y contraseña');}
		}

		if($_SESSION['login'] == 'on'){
			switch ($_SESSION['nivel']) {
				case 'admin':
					redirect(ADD_MOD.'admin');
				default :
					redirect(ADD_MOD.'panel');
				break;
			}
		}
		
		load_view('index',$d);
	}
	
	/*###########################################################################
					Registro de un nuevo usuario común
	#############################################################################*/
	function register(){
		if($_SESSION['login'] == 'on'){
			redirect(ADD_MOD.'panel');
		}
		
		global $message;
		
		$d['title']='Registro';
		
		$error=0;
		$data=array();
		if(count($_POST)){
			foreach ($_POST as $k => $v){
				if(trim($v)==''){
					set_message('warning','Todos los campos son obligatorios');
					$error=1;
					break;
				}
				$$k=$v;
			}
			
			if(!$error){
				if(!is_valid_user($user)){
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
					if(add_new_user($user,$nombre,$password,$mail,'usuario',1)){
							init_session($user);
							redirect(INDEXPATH);
						}else{	set_message('error',':(');}
						
				}
			}
		}
		
		load_view('register',$d);
	}
	
	function logout(){
		foreach($_SESSION as $k => $v){
			unset($_SESSION[$k]);
		}
		$redirect=($_SERVER['HTTP_REFERER']&&is_a_url($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:INDEXPATH;
		
		redirect($redirect);
	}
	?>
