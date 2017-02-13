<?php
session_start();
extract($_GET);
extract($_POST);
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

$sql = "SELECT ordenpago_cheque FROM configuracion";
$query_orden = mysql_query($sql) or die ($sql.mysql_error());
if (mysql_num_rows($query_orden) != 0) $field_orden = mysql_fetch_array($query_orden);

$sql_ret = "SELECT genera_comprobante FROM configuracion_tributos";
$query_ret = mysql_query($sql_ret) or die ($sql.mysql_error());
if (mysql_num_rows($query_ret) != 0) $field_ret = mysql_fetch_array($query_ret);

/*
$sql="SELECT 
			pagos_financieros.idorden_pago, 
			relacion_pago_compromisos.idorden_compra_servicio, 
			orden_compra_servicio.numero_orden, 
			retenciones.idretenciones 
		FROM 
			relacion_pago_compromisos, 
			orden_compra_servicio, 
			retenciones, 
			pagos_financieros 
		WHERE 
			(pagos_financieros.idorden_pago=relacion_pago_compromisos.idorden_pago AND 
			 relacion_pago_compromisos.idorden_compra_servicio=orden_compra_servicio.idorden_compra_servicio AND 
			 orden_compra_servicio.numero_orden=retenciones.numero_documento) AND 
			pagos_financieros.idpagos_financieros='".$id_emision_pago."'";
			
$query=mysql_query($sql) or die ($sql.mysql_error());

$rows=mysql_num_rows($query);
if ($rows!=0) {
	if ($field_orden['ordenpago_cheque'] != 0) echo "SI ORDEN";	
	//else echo "SI";
} else {
	if ($field_orden['ordenpago_cheque'] != 0) echo "NO ORDEN";	
	//else echo "NO";
}
*/
if ($field_orden['ordenpago_cheque'] == '1'){ echo "SI ORDEN"; }else{ echo "NO ORDEN"; };	

if ($field_ret['genera_comprobante'] == '1') echo "-SI";
else echo "-NO";

?>