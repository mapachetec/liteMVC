<?php  if(! defined('ABSPATH')) die("Access is forbidden.");
	/*
	 * Cambia "true" por "false" (sin las comillas) para mostrar o no errores
	 * de PHP
	 */
	$show_errors=true;
	/*
	 * Las siguientes 2 variables, determinan los directorios donde el sistema
	 * buscará los módulos y las vistas creadas
	 */
	$folderModules='modules';
	$folderVista='views';
	/*
	 * $callModulo es el nombre de la varible $_GET con que el sistema se manejará la llamada
	 * a los módulos así mismo $callAction representa la variable que manejará las acciones a 
	 * realizar previamente declaradas como funciones dentro del archivo "actions.php" del $moduloDefault
	 * En la barra de direcciones se verá por ejemplo: http://web/?m=usuarios&action=listar
	 */
	$callModule='m';
	$callAction='option';
	
	/*
	 * $moduloDefault es el nombre del directorio que estará dentro del directorio
	 * declarado en $folderModules, si no existe el sistema mostrará un error
	 */
	$moduloDefault='home';
	
	/*
	 * Si $actionDefault se deja en blanco, el sistema buscará una funcion llamada "index"
	 * dentro del archivo "actions.php" del $moduloDefault si no existe, mostrará un error.
	 * El error mostrado puede editarse modificando el archivo error404.php
	 */
	$actionDefault=''; 
	/*
	 * Extensión de los archivos a cargar en las vistas
	 */
	$ext_default="php";
	/*
	 * El sistema por default no usa bases de datos, si deseas usar una, llena lo siguiente
	 * con los datos de tu base de datos y desmárcalos como comentarios
	 */
	 
	 $domainBase='http://localhost/liteMVC/';
	 
	
	/*###########################################################################
					Datos de la BD
			Si ocupas una coneccion a una BD, descomenta lo siguiente
			Y pon la información correspondiente
	********************************************************************************
	$host='localhost';
	$dbname='Nombre de tu BD';
	$user='User de la BD';
	$pass='Pass de la BD';
	/*#############################################################################*/
