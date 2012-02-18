<?php 
	ob_start();
	session_name('Chetucar');
	session_start();

chdir('../');

define( 'ABSPATH', dirname(__FILE__) . '/' );
require_once("var_settings.php");
require_once("connection.php");
require_once("constantes.php");
require_once("fns.php");
//print_r($_SESSION);


$mxSize=(1024)*(1024);
$maxImages=8;
$allow_files=array
(
	'jpg',
	'jpeg',
	'png',
	'gif',
	'bmp'
);


$response=Array();
$response['files']=Array();
$response['errores']=Array();
$response['errores']['total']=0;
$error=0;
$maxImagesPerUser=8;


if(!isset($_SESSION['login'])||$_SESSION['login']!='on'){
	$error=1;
	$response['errores']['total']++;
	$response['errores']['msgs'][]='Privilegios insuficientes';
}

$data=get_results('select * from imagenes where id_publish=?',array($_SESSION['edit_id']));
$_SESSION['current_images']=($data)?@$data->fetchAll():0;

if(count($_SESSION['current_images'])>=$maxImagesPerUser){
	$response['errores']['msgs'][] = "Sólo puedes subir hasta $maxImagesPerUser imágenes de tu auto. Elimina algunas de las anteriores";
	$error=1;
	$response['errores']['total']++;
}


$response['total_files']=0;

$maxPermitido=round(($mxSize/1024)/1024);

//print_r($_FILES);
if(count($_FILES))
{
	foreach($_FILES as $file){
		
		
		if(isset($file['name']) && trim($file['name'])!==''){
			
			$file['name']=strtolower($file['name']);
			$f=explode('.',$file['name']);
			$ext=array_pop($f);
			
			###################### CHECANDO EXTENSION ###################
			
			if(!in_array($ext,$allow_files)){
				$response['errores']['msgs'][] = "$ext es una extension no permitida";
				$error=1;
				$response['errores']['total']++;
				
				
			}
			
			if(!$error){
				###################### CHECANDO TAMAÑO ###################
				
				if($file['size']>$mxSize||$file['size']==0){
					
					$response['errores']['msgs'][] = $file['name']." Parece demasiado grande. Max. permitido:". $maxPermitido . "MB";
					$error=1;
					$response['errores']['total']++;
				}
			}
			if(!$error){
				if(!file_exists('uploads/images/')||!file_exists('uploads')){
					$response['errores']['msgs'][] = "Error interno del servidor (Directorio de UPLOADS no existe o no tiene permisos de escritura)";
					$error=1;
					$response['errores']['total']++;
				}
			}
			if(!$error){
				
				/*###########################################################################
								PREPARE FILE TO SAVE
				#############################################################################*/
				$date=sanitize_title_with_dashes(get_date('year'));
				
				if(!file_exists('uploads/images/'.$date)){
					mkdir('uploads/images/'.$date,0777,true);
					chmod('uploads/images/'.$date,0777);
				}
				
				if(!file_exists('uploads/images/'.$date.'/'.$_SESSION['user'])){
					mkdir('uploads/images/'.$date.'/'.$_SESSION['user'],0777,true);
					chmod('uploads/images/'.$date.'/'.$_SESSION['user'],0777);
				}
				
				$destino='uploads/images/'.$date.'/'.$_SESSION['user'].'/';
				$result=sanitize_title_with_dashes(md5($f[0])).".$ext";
				
				if(!@copy($file['tmp_name'],$destino.$result))
				{
					
					$response['errores']['msgs'][] = $file['name']." No se ha podido guardar. Verifica permisos del directorio destino (" . $_POST['fpath'] .")";
					$error=1;
					$response['errores']['total']++;
					
				}
				else{
/*
					$q=do_query("insert into imagenes(userid,idauto,path) values(?,?,?)",array($userid,1,$destino.$result));
					if(!$q){
						$response['errores']['msgs'][] = $file['name']." No se ha podido guardar la imagen en la Base de datos. Intenta nuevamente";
						$error=1;
						$response['errores']['total']++;
					}*/
					$_SESSION['tmp_images'][]=$destino.$result;
					$response['total_files']++;
					$response['files'][]=Array
					(
						'name'	=>	$result,
						'type'	=>	$file['type'],
						'size'	=>	round($file['size']/1024,1),
						'path'	=>	$destino.$result
					);
					
					
					
				}
			}
			
			
		}
			
		
	}
	
	echo json_encode($response);
}

?>
