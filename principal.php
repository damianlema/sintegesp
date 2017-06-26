<?php
//session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Gesti&oacute;n P&uacute;blica</title>
<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">

<link href="css/theme/green/main.css" rel="stylesheet" type="text/css">


<script type="text/javascript" src="js/funciones.js"></script>
<script src="js/function.js" type="text/javascript" language="javascript"></script>
<script src="js/actualiza_select.js" type="text/javascript" language="javascript"></script>
<script src="js/select_dependientes.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" src="js/calendar/calendar.js"></script>
<script type="text/javascript" src="js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/calendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="js/treeMenu/stlib.js"></script>
<script type="text/javascript" src="js/validarCampos.js"></script>
<script src="modulos/administracion/js/consultar_rif.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript" src="modulos/compromisos/js/funciones.js"></script>



<style type="text/css"> @import url("css/theme/calendar-win2k-cold-1.css");

#cuadroMensajes{
    position:fixed;
    top: 0px;
    width:100%;
    text-align:center;
    margin-top:0px;
}

</style>

<style type="text/css"> @import url("css/theme/calendar-win2k-cold-1.css");

#cuadroChequeIndividual{
    position:fixed;
    top: 0px;
    width:75%;
    text-align:center;
    margin-top:0px;
}

</style>



</head>
<body>

<div id="divCargando" style="background-color:#CCCCCC; width:250px; height:100px;
                            position: absolute; left: 50%; top: 50%; margin-top: -100px; margin-left: -100px;
                            border: 1px solid black; display:none; z-index:3000">
    <center><h1>Procesando...</h1><br><img src='imagenes/cargando.gif'><br></center>
</div>

<!-- onLoad="setInterval('validar_minutos_transcurridos()',60000);" -->
<?
include "conf/conex.php";
include "funciones/funciones.php";

DEFINE('root_path',$_SERVER['DOCUMENT_ROOT'].$_SESSION["directorio_root"]);

//require($root_server."/conf/class.Conexion.php");
$conexion_db = conectarse();

//$login=$_SESSION['login'];  // IDENTIFICA EL USUARIO ACTIVO
$cedula_usuario = $_SESSION['cedula_usuario'];
/*
$fh=date("Y-m-d H:i:s");
$pc=gethostbyaddr($_SERVER['REMOTE_ADDR']);
$_SESSION["fh"]=$fh;
$_SESSION["pc"]=$pc;
 */
//$_SESSION["directorioroot"] = '/gestion_desarrollo'; //.$bus_configuracion["anio_fiscal"];
$existen_registros  = 0;
$codigo             = $_GET["c"];
$_SESSION["modulo"] = $_GET["modulo"];

//$_SESSION["accion"] = $_GET["accion"];

if (!$_SESSION) {
    mensaje("Disculpe Inicie Sesion");
    redirecciona("marcos.html");
} else {

// AQUI ESTABA LO DE PRIVILEGIOS
    $sql_cierre                           = mysql_query("select idcategoria_programatica, fecha_cierre from configuracion");
    $bus_cierre                           = mysql_fetch_array($sql_cierre);
    $_SESSION["s_categoria_programatica"] = $bus_cierre["idcategoria_programatica"];

    if (date("Y-m-d") > $bus_cierre["fecha_cierre"]) {
        $_SESSION['fecha_validada'] = $bus_cierre["fecha_cierre"];
    } else {
        $_SESSION['fecha_validada'] = date("Y-m-d");
    }

//$sql_version = mysql_query("select version from configuracion");
    //$bus_version = mysql_fetch_array($sql_version);

    $sql_configuracion_caja_chica = mysql_query("select * from configuracion_caja_chica");
    $bus_configuracion_caja_chica = mysql_fetch_array($sql_configuracion_caja_chica);
    $_SESSION["costo_ut"]         = $bus_configuracion_caja_chica["costo_ut"];

//$_SESSION["root_server"] = $_SERVER['DOCUMENT_ROOT'].'/gestion_desarrollo'; //.$bus_configuracion["anio_fiscal"];

/*

$sql_configuracion = mysql_query("select * from configuracion");
$bus_configuracion = mysql_fetch_array($sql_configuracion);
$_SESSION["anio_fiscal"] = $bus_configuracion["anio_fiscal"];
$_SESSION["version"] = $bus_configuracion["version"];
$_SESSION["mos_dis"] = $bus_configuracion["disponibilidad"];
 */

    //var_dump($nombrerivilegios);
    // para guardar la ruta en una variable
    $entro         = true;
    $ruta          = "";
    $accion_actual = $_REQUEST["accion"];
    //echo "ACCION: ".$accion_actual;
    while ($entro) {
        $sql_ruta = mysql_query("select * from accion where id_accion = " . $accion_actual . " limit 0,1") or die(mysql_error());
        $bus_ruta = mysql_fetch_array($sql_ruta);
        if ($bus_ruta["mostrar"] == 1) {
            if ($ruta == "") {
                $ruta = $bus_ruta["nombre_accion"];
            } else {
                $ruta = $bus_ruta["nombre_accion"] . " - " . $ruta;
            }
        }
        if ($bus_ruta["accion_padre"] == 0) {
            $sql_ruta_modulo = mysql_query("select * from modulo where id_modulo = '" . $bus_ruta["id_modulo"] . "' limit 0,1") or die(mysql_error());
            $bus_ruta_modulo = mysql_fetch_array($sql_ruta_modulo);
            $ruta            = $bus_ruta_modulo["nombre_modulo"] . " - " . $ruta;
            $entro           = false;
        } else {
            $accion_actual = $bus_ruta["accion_padre"];
        }
    }
    //echo $privilegios;
    if (in_array($_GET["accion"], $privilegios) == true) {
        $sql = mysql_query("select url from accion where id_accion = " . $_GET["accion"] . " limit 0,1");
        $bus = mysql_fetch_array($sql);
        ?>
            <div id="cuadroMensajes" style="display:none"></div>
            <div id="cuadroChequeIndividual" style="display:none; background-color:#CCCCCC; border:1px solid;">
                <div align="right"><a href="#" onClick="document.getElementById('cuadroChequeIndividual').style.display='none';">X</a></div>
                <iframe name="pdfPrincipal" id="pdfPrincipal" style="display:block" height="300" width="800"></iframe>
            </div>

             <div style="text-align:right">
                <span class="Estilo1 Estilo3" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px; font-weight:normal">
                    &nbsp;<?echo $ruta; ?> &nbsp;
                </span>
             </div>

             <?
        include $bus["url"];
    } else {
        registra_transaccion('Permiso Denegado (' . $_GET["accion"] . ')', $login, $fh, $pc, 'general', $conexion_db);
        mensaje("Disculpe usted no posee permisos suficientes para realizar esta accion, consulte permisos con el ADMINISTRADOR");
        ?>
            <script>
            window.close();
            </script>
            <?
    }
}
?>
</body>
</html>
<?
desconectar();
?>