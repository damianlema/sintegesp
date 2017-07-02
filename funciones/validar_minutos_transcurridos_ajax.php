<?
session_start();
include "../conf/conex.php";
include "../funciones/funciones.php";
conectarse();

$sql                    = mysql_query("select fechayhora from registro_transacciones where usuario = '" . $login . "' and estacion = '" . $pc . "' order by idregistro_transacciones DESC");
$bus                    = mysql_fetch_array($sql);
$hora_completa_registro = explode(" ", $bus["fechayhora"]);
$minutos_registro       = explode(":", $hora_completa_registro[1]);

$cantidad_minutos_registro = ($minutos_registro[0] * 60) + $minutos_registro[1];
$cantidad_minutos_actual   = (date("H") * 60) + date("i");

$minutos = $cantidad_minutos_actual - $cantidad_minutos_registro;

if ($minutos > 30) {
    echo "mayor";
} else {
    echo "menor";
}
