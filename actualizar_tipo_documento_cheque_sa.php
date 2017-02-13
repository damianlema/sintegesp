<?
$link=mysql_connect("localhost","root","gestion2009");
mysql_select_db("gestion_mapire_15072015",$link);

$sql_compromisos = mysql_query("select * from orden_pago WHERE `tipo` =44");

while ($campos = mysql_fetch_array($sql_compromisos)){
	$sql_asiento_contable = mysql_query("update pagos_financieros set idtipo_documento = '230'
											 					where idorden_pago = '".$campos["idorden_pago"]."'")or die("error".mysql_error());

	
}