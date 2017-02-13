<?
include("conf/conex.php");
Conectarse();

$sql_consulta = mysql_query("select * from pagos_financieros2");

while($bus_consulta = mysql_fetch_array($sql_consulta)){
	$sql_update = mysql_query("update pagos_financieros set estado = '".$bus_consulta["estado"]."',
															fecha_cheque = '".$bus_consulta["fecha_cheque"]."',
															monto_cheque = '".$bus_consulta["monto_cheque"]."',
															beneficiario = '".$bus_consulta["beneficiario"]."',
															ci_beneficiario = '".$bus_consulta["ci_beneficiario"]."',
															idorden_pago = '".$bus_consulta["idorden_pago"]."',
															idtipo_documento = '".$bus_consulta["idtipo_documento"]."',
															fecha_conciliado = '".$bus_consulta["fecha_conciliado"]."',
															numero_cheque = '".$bus_consulta["numero_cheque"]."'
															 where idpagos_financieros = '".$bus_consulta["idpagos_financieros"]."'
															 ");
	
	
	
}

$sql_update = mysql_query("update pagos_financieros set fecha_anulacion = fecha_cheque
															 where estado = 'anulado'
															 ");

?>