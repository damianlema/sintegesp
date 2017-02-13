<?php
/*****************************************************************************
* @Controlador de Traslado Presupuestarios
* @versión: 1.0
* @fecha creación: 30/07/2016
* @autor: Hector Damian Lema
******************************************************************************
* @fecha modificacion
* @autor
* @descripcion
******************************************************************************/
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');


$root_server = $_SERVER['DOCUMENT_ROOT'].$_SESSION["directorio_root"];
require($root_server."/conf/class.Conexion.php");
require($root_server."/modulos/presupuesto/modelo/class.TrasladoPresupuesto.php");
//$db = new Conexion();

$ejecutar = isset($_POST['ejecutar']) ? $_POST['ejecutar'] : null;

switch($ejecutar){
	case 'ingresar_datos_basicos':
		$registrar_datos_cabecera = new  TrasladoPresupuesto();
		$registrar_datos_cabecera->ingresarDatosBasicos();

	break;
	case 'actualizar_datos_basicos':
		$actualizar_datos_cabecera = new TrasladoPresupuesto();
		$actualizar_datos_cabecera->actualizarDatosBasicos();
	break;
	case '2':
		echo '2';
	break;
	case '3':
		echo '3';
	break;
	default:
		REQUIRE($root_server.'/modulos/presupuesto/vista/trasladoPresupuesto.view.php');
	break;
}
//desconectar();

?>