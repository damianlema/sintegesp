<?
//$link=mysql_connect("localhost","root","gestion2009");
//mysql_select_db("gestion_mapire_15072015",$link);
include("conf/conex.php");
Conectarse();
$borrar_compromisos = mysql_query("delete from asiento_contable where tipo_movimiento='compromiso'");
$sql_compromisos = mysql_query("select * from orden_compra_servicio");
echo "COMPROMISOS";
while ($campos = mysql_fetch_array($sql_compromisos)){
	$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
	$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
	if($bus_cuentas_contables["excluir_contabilidad"] != 'si'){
		if($campos["estado"] == 'elaboracion'){ $estado = 'elaboracion'; }
		elseif($campos["estado"] == 'anulado'){ $estado = 'anulado'; }
		else { $estado = 'procesado'; }
		if ($estado != 'anulado'){
			$sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	fecha_contable,
																	prioridad
																		)values(
																				'".$campos["idfuente_financiamiento"]."',
																				'".$campos["justificacion"]."',
																				'compromiso',
																				'".$campos["idorden_compra_servicio"]."',
																				'".$estado."',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_orden"]."',
																				'2')");
			
			$idasiento_contable = mysql_insert_id();
			
			//$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
			//$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
			
			
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe',
																				'".$campos["total"]."')");
				$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber',
																				'".$campos["total"]."')");
			
																				
		}else{
			$sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	fecha_contable,
																	reversado,
																	prioridad
																		)values(
																				'".$campos["idfuente_financiamiento"]."',
																				'".$campos["justificacion"]."',
																				'compromiso',
																				'".$campos["idorden_compra_servicio"]."',
																				'procesado',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_orden"]."',
																				'si',
																				'2')");
			
			$idasiento_contable = mysql_insert_id();
			
			$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
			$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
			
			
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'debe',
																				'".$campos["total"]."')");
				$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'haber',
																				'".$campos["total"]."')");
			//reverso del documento
			
			$sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	fecha_contable,
																	prioridad
																		)values(
																				'".$campos["idfuente_financiamiento"]."',
																				'".'ANULACION DE ASIENTO: '.$campos["justificacion"]."',
																				'compromiso',
																				'".$campos["idorden_compra_servicio"]."',
																				'".$estado."',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_anulacion"]."',
																				'2')");
			
			$idasiento_contable = mysql_insert_id();
			
			$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
			$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
			
			
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_haber"]."',
																				'".$bus_cuentas_contables["idcuenta_haber"]."',
																				'debe',
																				'".$campos["total"]."')");
				$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla_debe"]."',
																				'".$bus_cuentas_contables["idcuenta_debe"]."',
																				'haber',
																				'".$campos["total"]."')");
		}

	}
}



?>