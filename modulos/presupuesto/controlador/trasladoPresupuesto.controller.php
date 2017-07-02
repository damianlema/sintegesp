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
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '1');


$root_server = $_SERVER['DOCUMENT_ROOT'].$_SESSION["directorio_root"];
require($root_server."/conf/class.Conexion.php");
require($root_server."/modulos/presupuesto/modelo/class.TrasladoPresupuesto.php");
//require($root_server."/modulos/presupuesto/modelo/class.ListasTrasladoPresupuesto.php");

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
	case 'listar_traslados_presupuestarios':
		$listar_traslados_presupuestarios = new  TrasladoPresupuesto();
		$listar_traslados_presupuestarios->listarTrasladosPresupuestarios();
	break;
	case 'consultar_traslado_presupuestario':
		$consultar_traslados_presupuestarios = new  TrasladoPresupuesto();
		$consultar_traslados_presupuestarios->consultarTrasladosPresupuestarios();
	break;
	case 'mostrar_partidas_disminuidas':
		$consultar_traslados_presupuestarios = new  TrasladoPresupuesto();
		$consultar_traslados_presupuestarios->mostrarPartidasDisminuidas();
	break;
	case 'mostrar_partidas_aumentadas':
		$consultar_traslados_presupuestarios = new  TrasladoPresupuesto();
		$consultar_traslados_presupuestarios->mostrarPartidasAumentadas();
	break;
	default:
		REQUIRE($root_server.'/modulos/presupuesto/vista/trasladoPresupuesto.view.php');
	break;
}
//desconectar();

?>