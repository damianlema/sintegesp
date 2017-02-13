<?
include("conf/conex.php");
Conectarse();

// MAESTRO PRESUPUESTO
$sql_maestro_presupuesto = mysql_query("update maestro_presupuesto set total_compromisos = 0,
																		total_causados = 0,
																		total_pagados = 0");
																		
echo "TERMINO MAESTRO PRESUPUESTO<br>";

// CHEQUES
$sql_cheques = mysql_query("truncate cheques_cuentas_bancarias");
echo "TERMINO CHEQUES<br>";

// PAGOS FINANCIEROS
$sql_pagos_financieros = mysql_query("truncate pagos_financieros");
echo "TERMINO PAGOS FINANCIEROS<br>";


//REMISION DOCUMENTOS
$sql_relacion_remision = mysql_query("truncate relacion_documentos_remision");
$sql_remision_documentos = mysql_query("truncate remision_documentos");
echo "TERMINO REMISION DE DOCUMENTOS<br>";

// RECIBIR DOCUMENTOS

$sql_relacion_recibidos = mysql_query("truncate relacion_documentos_recibidos");
$sql_recibir_documentos = mysql_query("truncate recibir_documentos");
echo "TERMINO RECIBIR DOCUMENTOS<br>";



// ORDEN PAGO
$sql_partidas_orden_pago = mysql_query("truncate partidas_orden_pago");
$sql_relacion_orden_pago_retencion = mysql_query("truncate relacion_orden_pago_retencion");
$sql_pagos_compromisos = mysql_query("truncate relacion_pago_compromisos");
$sql_orden_pago = mysql_query("truncate orden_pago");
echo "TERMINO ORDEN DE PAGO<br>";


//RETENCIONES
$sql_relacion_retenciones = mysql_query("truncate relacion_retenciones");
$sql_retenciones = mysql_query("truncate retenciones");
echo "TERMINO RETENCIONES<br>";


//REQUISICION
$sql_relacion_cotizacion = mysql_query("truncate relacion_requisicion_solicitud_cotizacion");
$sql_relacion_impuesto = mysql_query("truncate relacion_impuestos_requisiciones");
$sql_partidas = mysql_query("truncate partidas_requisiciones");
$sql_relacion_compra = mysql_query("truncate relacion_compra_requisicion");
$sql_articulos = mysql_query("truncate articulos_requisicion");
$sql_requisicion = mysql_query("truncate requisicion");
echo "TERMINO REQUISICIONES<br>";


// SOLICITUD DE COTIZACION
$sql_proveedores = mysql_query("truncate proveedores_solicitud_cotizacion");
$sql_relacion_compra = mysql_query("truncate relacion_compra_solicitud_cotizacion");
$sql_articulos = mysql_query("truncate articulos_solicitud_cotizacion");
$sql_solicitud_cotizacion = mysql_query("truncate solicitud_cotizacion");
echo "TERMINO SOLICITUD DE COTIZACION<br>";


// ORDEN COMPRA SERVICIO
$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo != (1 and 13)");
while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
	$sql_select_orden = mysql_query("select * from orden_compra_servicio where tipo = '".$bus_tipos_documentos["idtipos_documentos"]."'");
		while($bus_select_orden = mysql_fetch_array($sql_select_orden)){
			 $sql_articulos = mysql_query("delete from articulos_compra_servicio 
			 												where idorden_compra_servicio = '".$bus_select_orden["idorden_compra_servicio"]."'");
			 $sql_partidas = mysql_query("delete from partidas_orden_compra_servicio  
			 												where idorden_compra_servicio = '".$bus_select_orden["idorden_compra_servicio"]."'")or die(mysql_error());
			 $sql_relacion_impuestos = mysql_query("delete from relacion_impuestos_ordenes_compras 
			 												where idorden_compra_servicio = '".$bus_select_orden["idorden_compra_servicio"]."'");
			 $sql_orden_compra = mysql_query("delete from orden_compra_servicio 
			 												where idorden_compra_servicio = '".$bus_select_orden["idorden_compra_servicio"]."'");
		}
}

echo "TERMINO ORDEN DE COMPRA<br>";



$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo = (1 and 13)");
while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
	$sql_orden_compra = mysql_query("update orden_compra_servicio set estado = 'elaboracion', 
																		numero_orden = '' 
																		where tipo = '".$bus_tipos_documentos["idtipos_documentos"]."'");
}

echo "TERMINO ACTUALIZACION DE ORDEN DE COMPRA<br>";



?>