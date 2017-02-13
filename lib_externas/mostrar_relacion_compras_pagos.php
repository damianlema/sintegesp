<?
include("conf/conex.php");
Conectarse();
$sql_consulta = mysql_query("select * from orden_compra_servicio where tipo = 166 and estado != 'anulado'");
while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_consulta["idbeneficiarios"]."'");
	$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
	echo $bus_consulta["numero_orden"]." : ";
	$sql_relacion = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_consulta["idorden_compra_servicio"]."'");
	$bus_relacion = mysql_fetch_array($sql_relacion);
	$sql_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion["idorden_pago"]."'");
	$num_pago = mysql_num_rows($sql_pago);
	if($num_pago > 0){
		$bus_pago = mysql_fetch_array($sql_pago);
		echo $bus_pago["numero_orden"]." : ";
		$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_relacion["idorden_pago"]."'");
		$bus_cheque = mysql_fetch_array($sql_cheque);
		echo $bus_cheque["numero_cheque"]." : ";
	}
	echo number_format($bus_consulta["total"],2,",",".")." : ";
	echo $bus_beneficiarios["nombre"]."<br>";
}
?>