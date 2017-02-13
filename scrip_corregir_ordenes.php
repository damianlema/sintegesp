<?
include("conf/conex.php");
Conectarse();
$sql_orden_pago = mysql_query("select * from orden_pago where tipo = 180");

while($bus_orden_pago = mysql_fetch_array($sql_orden_pago)){

	$sql_partidas_orden_pago = mysql_query("select SUM(monto) as suma 
												from 
											partidas_orden_pago 
												where 
											idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
	$bus_partidas_orden_pago = mysql_fetch_array($sql_partidas_orden_pago);

	$sql_actualizar = mysql_query("update orden_pago set sub_total = '".$bus_partidas_orden_pago["suma"]."',
													total = '".$bus_partidas_orden_pago["suma"]."',
													total_a_pagar = '".$bus_partidas_orden_pago["suma"]."'
													where 
													idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
													
	echo "LISTA LA ORDEN NRO: ".$bus_orden_pago["numero_orden"]." - ".$bus_partidas_orden_pago["suma"]."<br>";

}
?>