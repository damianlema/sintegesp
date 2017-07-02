<?php
/*****************************************************************************
* @
* @versión: 1.0
* @fecha creación:
* @autor:
******************************************************************************
* @fecha modificacion
* @autor
* @descripcion
******************************************************************************/
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '1');

var_dump(class_exists('Conexion'));




if (REQUIRE(root_path."/lib/listas/modelo/class.listaTrasladoPresupuesto.php")) echo "si"; echo "no";


$ejecutar = isset($_POST['ejecutar']) ? $_POST['ejecutar'] : null;

switch($ejecutar){
	case 'listar_traslados_presupuestarios':
		$listar_traslados_presupuestarios = new  ListaTrasladoPresupuestario();
		$listar_traslados_presupuestarios->listarTrasladosPresupuestarios();

	break;
	case '1':
		echo '1';
	break;
	case '2':
		echo '2';
	break;
	case '3':
		echo '3';
	break;
	default:
		REQUIRE(root_path.'/lib/listas/vista/lista_traslado_presupuesto_modal.php');
	break;
}



?>