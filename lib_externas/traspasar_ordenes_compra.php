<?
mysql_connect("localhost", "root", "gestion2009");

$arreglo_ordenes = array('OC-2008-5',
						'OC-2008-4',
						'OC-2008-3');
						
foreach($arreglo_ordenes as $arr){

$sql_orden_compra_2009 = mysql_query("select * from gestion_2008.orden_compra_servicio where numero_orden = '".$arr."'")or die("ERROR SELECCIONANDO LA ORDEN DE COMPRA: ".mysql_error());
$bus_orden_compra_2009 = mysql_fetch_array($sql_orden_compra_2009);

$nuevo_impuesto = ($bus_orden_compra_2009["sub_total"]*9)/100;

$sql_insert_orden_compra_2008 = mysql_query("insert into gestion.orden_compra_servicio(numero_orden,
																					fecha_orden,
																					tipo,
																					fecha_elaboracion,
																					proceso,
																					numero_documento,
																					idbeneficiarios,
																					idcategoria_programatica,
																					anio,
																					idfuente_financiamiento,
																					idtipo_presupuesto,
																					idordinal,
																					justificacion,
																					observaciones,
																					ordenado_por,
																					cedula_ordenado,
																					numero_requisicion,
																					fecha_requisicion,
																					nro_items,
																					exento,
																					sub_total,
																					exento_original,
																					sub_total_original,
																					impuesto,
																					descuento,
																					total,
																					estado,
																					idrazones_devolucion,
																					observaciones_devolucion,
																					numero_remision,
																					fecha_remision,
																					recibido_por,
																					cedula_recibido,
																					fecha_recibido,
																					ubicacion,
																					status,
																					usuario,
																					fechayhora,
																					duplicados,
																					nro_factura,
																					fecha_factura,
																					nro_control,
																					codigo_referencia,
																					tipo_carga_orden)VALUES(
																						'TEMPORAL',
																						'".$bus_orden_compra_2009["fecha_orden"]."',
																						'".$bus_orden_compra_2009["tipo"]."',
																						'".$bus_orden_compra_2009["fecha_elaboracion"]."',
																						'".$bus_orden_compra_2009["proceso"]."',
																						'".$bus_orden_compra_2009["numero_documento"]."',
																						'".$bus_orden_compra_2009["idbeneficiarios"]."',
																						'".$bus_orden_compra_2009["idcategoria_programatica"]."',
																						'".$bus_orden_compra_2009["anio"]."',
																						'".$bus_orden_compra_2009["idfuente_financiamiento"]."',
																						'".$bus_orden_compra_2009["idtipo_presupuesto"]."',
																						'".$bus_orden_compra_2009["idordinal"]."',
																						'".$bus_orden_compra_2009["justificacion"]."',
																						'".$bus_orden_compra_2009["observaciones"]."',
																						'".$bus_orden_compra_2009["ordenado_por"]."',
																						'".$bus_orden_compra_2009["cedula_ordenado"]."',
																						'".$bus_orden_compra_2009["numero_requisicion"]."',
																						'".$bus_orden_compra_2009["fecha_requisicion"]."',
																						'".$bus_orden_compra_2009["nro_items"]."',
																						'".$bus_orden_compra_2009["exento"]."',
																						'".$bus_orden_compra_2009["sub_total"]."',
																						'".$bus_orden_compra_2009["exento_original"]."',
																						'".$bus_orden_compra_2009["sub_total_original"]."',
																						'".$nuevo_impuesto."',
																						'".$bus_orden_compra_2009["descuento"]."',
																						'".$bus_orden_compra_2009["total"]."',
																						'".$bus_orden_compra_2009["estado"]."',
																						'".$bus_orden_compra_2009["idrazones_devolucion"]."',
																						'".$bus_orden_compra_2009["observaciones_devolucion"]."',
																						'".$bus_orden_compra_2009["numero_remision"]."',
																						'".$bus_orden_compra_2009["fecha_remision"]."',
																						'".$bus_orden_compra_2009["recibido_por"]."',
																						'".$bus_orden_compra_2009["cedula_recibido"]."',
																						'".$bus_orden_compra_2009["fecha_recibido"]."',
																						'".$bus_orden_compra_2009["ubicacion"]."',
																						'".$bus_orden_compra_2009["status"]."',
																						'".$bus_orden_compra_2009["usuario"]."',
																						'".$bus_orden_compra_2009["fechayhora"]."',
																						'".$bus_orden_compra_2009["duplicados"]."',
																						'".$bus_orden_compra_2009["nro_factura"]."',
																						'".$bus_orden_compra_2009["fecha_factura"]."',
																						'".$bus_orden_compra_2009["nro_control"]."',
																						'".$bus_orden_compra_2009["codigo_referencia"]."',
																						'".$bus_orden_compra_2009["tipo_carga_orden"]."')")or die("ERROR EN LA CREACION DE LA ORDEN: ".mysql_error());
$id_orden_creada = mysql_insert_id();
echo "COPIO LA ORDEN<br>";
// PARTIDAS_ORDENES_COMPRA
																						
$sql_partidas_orden_2009 = mysql_query("select * from gestion_2008.partidas_orden_compra_servicio where idorden_compra_servicio = '".$bus_orden_compra_2009["idorden_compra_servicio"]."'");
$bus_partidas_orden_2009 = mysql_fetch_array($sql_partidas_orden_2009);


$sql_insert_partidas_2008 = mysql_query("insert into gestion.partidas_orden_compra_servicio(idorden_compra_servicio,
																					idmaestro_presupuesto,
																					monto,
																					monto_original,
																					estado,
																					status,
																					usuario,
																					fechayhora)VALUES(
																						'".$id_orden_creada."',
																						'".$bus_partidas_orden_2009["idmaestro_presupuesto"]."',
																						'".$bus_partidas_orden_2009["monto"]."',
																						'".$bus_partidas_orden_2009["monto_original"]."',
																						'".$bus_partidas_orden_2009["estado"]."',
																						'".$bus_partidas_orden_2009["status"]."',
																						'".$bus_partidas_orden_2009["usuario"]."',
																						'".$bus_partidas_orden_2009["fechayhora"]."')");
																						



// ARTICULOS ORDENES COMPRA
echo "COPIO LAS PARTIDAS<br>";
$sql_articulos_ordenes_2009 = mysql_query("select * from gestion_2008.articulos_compra_servicio where idorden_compra_servicio = '".$bus_orden_compra_2009["idorden_compra_servicio"]."'");
$bus_articulos_ordenes_2009 = mysql_fetch_array($sql_articulos_ordenes_2009);

$sql_insert_articulos_2008 = mysql_query("insert into gestion.articulos_compra_servicio(idorden_compra_servicio,
																				idarticulos_servicios,
																				idcategoria_programatica,
																				cantidad,
																				precio_unitario,
																				porcentaje_impuesto,
																				impuesto,
																				total,
																				exento,
																				idsolicitud_cotizacion,
																				estado,
																				duplicado,
																				status,
																				usuario,
																				fechayhora,
																				idpartida_impuesto)VALUES(
																					'".$id_orden_creada."',
																					'".$bus_articulos_ordenes_2009['idarticulos_servicios']."',
																					'".$bus_articulos_ordenes_2009['idcategoria_programatica']."',
																					'".$bus_articulos_ordenes_2009['cantidad']."',
																					'".$bus_articulos_ordenes_2009['precio_unitario']."',
																					'".$bus_articulos_ordenes_2009['porcentaje_impuesto']."',
																					'".$bus_articulos_ordenes_2009['impuesto']."',
																					'".$bus_articulos_ordenes_2009['total']."',
																					'".$bus_articulos_ordenes_2009['exento']."',
																					'".$bus_articulos_ordenes_2009['idsolicitud_cotizacion']."',
																					'".$bus_articulos_ordenes_2009['estado']."',
																					'".$bus_articulos_ordenes_2009['duplicado']."',
																					'".$bus_articulos_ordenes_2009['status']."',
																					'".$bus_articulos_ordenes_2009['usuario']."',
																					'".$bus_articulos_ordenes_2009['fechayhora']."',
																					'".$bus_articulos_ordenes_2009['idpartida_impuesto']."')")or die("ERROR CREANDO LOS ARTICULOS: ".mysql_error());

// RELACION IMPUESTOS ORDENES COMPRAS
echo "COPIO LOS ARTICULOS<br>";
$sql_impuestos_2009 = mysql_query("select * from gestion_2008.relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$bus_orden_compra_2009["idorden_compra_servicio"]."'");
$bus_impuestos_2009 = mysql_fetch_array($sql_impuestos_2009);


$sql_insert_impuestos_2008 = mysql_query("insert into gestion.relacion_impuestos_ordenes_compras (idorden_compra_servicio,
																							idimpuestos,
																							base_calculo,
																							base_calculo_original,
																							porcentaje,
																							total,
																							estado)VALUES(
																								'".$id_orden_creada."',
																								'".$bus_impuestos_2009["idimpuestos"]."',
																								'".$bus_impuestos_2009["base_calculo"]."',
																								'".$bus_impuestos_2009["base_calculo_original"]."',
																								'".$bus_impuestos_2009["porcentaje"]."',
																								'".$bus_impuestos_2009["total"]."',
																								'".$bus_impuestos_2009["estado"]."')");
echo "COPIO LA RELACION DE IMPUESTOS<br />";
echo "SE TERMINO DE COPIAR EL NUMERO DE ORDEN: <strong>".$arr."</strong><br /><br /><br />";
}// FIN DLE FOREACH
?>