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
extract($_POST);
extract($_GET);

$root_server = $_SERVER['DOCUMENT_ROOT'] . $_SESSION["directorio_root"];
require $root_server . "/conf/class.Conexion.php";
require $root_server . "/lib/listas/modelo/class.listaPresupuesto.php";

$ejecutar_lista = isset($_POST['ejecutar_lista']) ? $_POST['ejecutar_lista'] : null;

switch ($ejecutar_lista) {
    case 'llena_tipo_presupuesto':
        $llena_tipo_presupuesto = new ListaPresupuesto();
        $llena_tipo_presupuesto->llenaTipoPresupuesto();
        break;
    case 'llena_fuente_financiamiento':
        $llena_fuente_financiamiento = new ListaPresupuesto();
        $llena_fuente_financiamiento->llenaFuenteFinanciamiento();
        break;
    case 'llena_categoria_programatica':
        $llena_categoria_programatica = new ListaPresupuesto();
        $llena_categoria_programatica->llenaCategoriaProgramatica();
        break;
    case 'buscar_lista_presupuesto':
        $buscar_lista_presupuesto = new ListaPresupuesto();
        $buscar_lista_presupuesto->buscarListaPresupuesto();
        break;
    default:
        require $root_server . '/lib/listas/vista/listaPresupuesto.php';
        break;
}
