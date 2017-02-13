<?
include("conf/conex.php");
Conectarse();
$sql_consulta = mysql_query("select * from relacion_concepto_trabajador");
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$nuevo_valor = str_replace(",",".",$bus_consulta["valor"]);
	echo "SE ACTUALIZO EL ID: ".$bus_consulta["idrelacion_concepto_trabajador"].".... ".$nuevo_valor."<br>";
	$sql_update = mysql_query("update relacion_concepto_trabajador set valor = '".$nuevo_valor."' where idrelacion_concepto_trabajador = '".$bus_consulta["idrelacion_concepto_trabajador"]."'");
}

?>