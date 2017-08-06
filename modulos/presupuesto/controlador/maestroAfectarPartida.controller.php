<?php
/*******************************************************************************************************************
 * @Controlador maestro para partidas a afectar en movimientos
 * @version: 1.0
 * @Fecha creacion:
 * @Autor:
 ********************************************************************************************************************
 * @Fecha Modificacion:
 * @Autor:
 * @Descripcion:
 ********************************************************************************************************************/
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '1');
extract($_POST);
extract($_GET);

$root_server = $_SERVER['DOCUMENT_ROOT'] . $_SESSION["directorio_root"];
require $root_server . "/conf/class.Conexion.php";
require $root_server . "/modulos/presupuesto/modelo/class.maestroAfectarPartida.php";

$ejecutar_afectar = isset($_POST['ejecutar_afectar']) ? $_POST['ejecutar_afectar'] : null;

switch ($ejecutar_afectar) {
    case 'mostrar_partida_afectar':
        $llena_tipo_presupuesto = new MaestroAfectarPartida();
        $llena_tipo_presupuesto->mostrarPartidaAfectar();
        break;

}
