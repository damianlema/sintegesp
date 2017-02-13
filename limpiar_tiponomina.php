<?
$link = mysql_connect("localhost", "root", "");
mysql_select_db("gestion_mariguitar_2017", $link);

$borrar2 = mysql_query("DELETE FROM relacion_concepto_trabajador WHERE idtipo_nomina = 80");
$borrar2 = mysql_query("DELETE FROM relacion_concepto_trabajador WHERE idtipo_nomina = 81");
$borrar2 = mysql_query("DELETE FROM relacion_concepto_trabajador WHERE idtipo_nomina = 82");

$borrar2 = mysql_query("DELETE FROM relacion_tipo_nomina_trabajador WHERE idtipo_nomina = 80");
$borrar2 = mysql_query("DELETE FROM relacion_tipo_nomina_trabajador WHERE idtipo_nomina = 81");
$borrar2 = mysql_query("DELETE FROM relacion_tipo_nomina_trabajador WHERE idtipo_nomina = 82");

