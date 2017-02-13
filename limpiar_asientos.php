<?
$link=mysql_connect("localhost","root","gestion2009");
mysql_select_db("gestion_2013",$link);

$sql_compromisos = mysql_query("select * from cuentas_asiento_contable where idcuenta = '' and monto > 0");
echo mysql_num_rows($sql_compromisos);
while ($campos = mysql_fetch_array($sql_compromisos)){
	
	
	$borrar = mysql_query("delete from asiento contable where idasiento_contable = '".$campos["idasiento_contable"]."'");
	
}
$borrar2 = mysql_query("delete from cuentas_asiento_contable where idcuenta = '' and monto > 0");



?>