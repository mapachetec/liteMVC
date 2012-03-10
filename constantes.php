<?php if(! defined('ABSPATH')) die("Access is forbidden.");

	$module=(isset($_GET[$callModule])&&!empty($_GET[$callModule]))?$_GET[$callModule]:$moduloDefault;
	$action=(isset($_GET[$callAction])&&!empty($_GET[$callAction]))?$_GET[$callAction]:$actionDefault;
	
	if(!defined('INDEXPATH')){
		$root=preg_replace('/index\.php/','',$_SERVER['PHP_SELF']);
		define('INDEXPATH',$root);
	}
	
	if(!defined('BASE_URL'))	
		define('BASE_URL',$domainBase);
	
	if(!defined('SECRET_KEY'))
		define('SECRET_KEY','s0m3s3cr37pa55');
		
	define('CALL_ACTION', $callAction);
	define('CALL_MOD', $callModule);
	
	define('ADD_ACTION',BASE_URL."?".$callModule."=".$module."&".$callAction."=");
	define('ADD_MOD', BASE_URL."?".$callModule."=");
	
	if(file_exists(ABSPATH.$folderVista.'/'.$module))
		define('PATH_VIEW', ABSPATH.$folderVista.'/'.$module."/");
	
	define('VIEW_FILES', INDEXPATH.$folderVista.'/'.$module."/");
	define('MOD_FILES', INDEXPATH.$folderVista.'/'.$module."/");
	
	if(file_exists(ABSPATH.$folderModules.'/'.$module))
		define('PATH_MOD', ABSPATH.$folderModules.'/'.$module."/");
	
	if(!defined('MODULES'))
		define('MODULES', ABSPATH.$folderModules);
	
	if(!defined('EXT_DEFAULT'))
		define('EXT_DEFAULT', ".".trim($ext_default));
	
	if(!defined('LIBS'))
		define('LIBS', ".".ABSPATH.'libs/');
	
	define('SALT_LENGTH', 20);
