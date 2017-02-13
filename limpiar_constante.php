<?
$link = mysql_connect("localhost", "root", "gestion2009");
mysql_select_db("gestion_2017", $link);

$borrar2 = mysql_query("DELETE FROM relacion_concepto_trabajador WHERE tabla = 'constantes_nomina' and idconcepto = 79");


