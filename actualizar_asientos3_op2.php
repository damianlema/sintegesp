<?
//$link=mysql_connect("localhost","root","gestion2009");
//mysql_select_db("gestion_mapire_15072015",$link);
include("conf/conex.php");
Conectarse();
$borrar_compromisos = mysql_query("delete from asiento_contable where tipo_movimiento='emision_pagos'");
$sql_compromisos = mysql_query("select * from pagos_financieros");
echo "PAGOS FINANCIEROS";
while ($campos = mysql_fetch_array($sql_compromisos)){
	$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["idtipo_documento"]."'");
	$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
	if($bus_cuentas_contables["excluir_contabilidad"] != 'si'){
		if($campos["estado"] == 'elaboracion'){ $estado = 'elaboracion'; }
		elseif($campos["estado"] == 'anulado'){ $estado = 'anulado'; }
		else { $estado = 'procesado'; }
		echo $estado;
		if($bus_cuentas_contables["paga"]=='si'){
			$sql_op = mysql_query("select * from orden_pago where idorden_pago = '".$campos["idorden_pago"]."'");
			$bus_op = mysql_fetch_array($sql_op);
			$justificacion = $bus_op["justificacion"];
			$idfuente_financiamiento = $bus_op["idfuente_financiamiento"];
		}else{
			$justificacion = ' CHEQUE DIRECTO ';
			$idfuente_financiamiento = 0;

		}

		$monto = $campos["monto_cheque"];

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
																				'".$idfuente_financiamiento."',
																				'".$justificacion."',
																				'emision_pagos',
																				'".$campos["idpagos_financieros"]."',
																				'".$estado."',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_cheque"]."',
																				'4')");
			
			$idasiento_contable = mysql_insert_id();
			echo " id ".$idasiento_contable;
			
			
			$idcuenta_debe=0;
			$idcuenta_haber=0;
			$tabla_debe=0;
			$tabla_haber=0;
			if ($bus_cuentas_contables["tabla_debe"]!='0'){
				$idcuenta_debe = $bus_cuentas_contables["idcuenta_debe"];
				$tabla_debe = $bus_cuentas_contables["tabla_debe"];
			}else{
				$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$campos["idcuenta_bancaria"]."'");
				$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
				$idcuenta_debe = $bus_consultar_cuenta_contable["idcuenta_contable"];
				$tabla_debe = $bus_consultar_cuenta_contable["tabla"];
			}
			if ($bus_cuentas_contables["tabla_haber"]!='0'){
				$idcuenta_haber = $bus_cuentas_contables["idcuenta_haber"];
				$tabla_haber = $bus_cuentas_contables["tabla_haber"];
			}else{
				$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$campos["idcuenta_bancaria"]."'");
				$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
				$idcuenta_haber = $bus_consultar_cuenta_contable["idcuenta_contable"];
				$tabla_haber = $bus_consultar_cuenta_contable["tabla"];
			}



			$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_debe."',
																				'".$idcuenta_debe."',
																				'debe',
																				'".$monto."')");
			$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_haber."',
																				'".$idcuenta_haber."',
																				'haber',
																				'".$monto."')");
			
			
		}else{
		//REVERSO EL COMPROMISO
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
																				'".$idfuente_financiamiento."',
																				'".$justificacion."',
																				'emision_pagos',
																				'".$campos["idpagos_financieros"]."',
																				'procesado',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_cheque"]."',
																				'si',
																				'4')");
			
			$idasiento_contable = mysql_insert_id();
			echo " id ".$idasiento_contable;
			

			$idcuenta_debe=0;
			$idcuenta_haber=0;
			$tabla_debe=0;
			$tabla_haber=0;
			if ($bus_cuentas_contables["tabla_debe"]!='0'){
				$idcuenta_debe = $bus_cuentas_contables["idcuenta_debe"];
				$tabla_debe = $bus_cuentas_contables["tabla_debe"];
			}else{
				$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$campos["idcuenta_bancaria"]."'");
				$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
				$idcuenta_debe = $bus_consultar_cuenta_contable["idcuenta_contable"];
				$tabla_debe = $bus_consultar_cuenta_contable["tabla"];
			}
			if ($bus_cuentas_contables["tabla_haber"]!='0'){
				$idcuenta_haber = $bus_cuentas_contables["idcuenta_haber"];
				$tabla_haber = $bus_cuentas_contables["tabla_haber"];
			}else{
				$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$campos["idcuenta_bancaria"]."'");
				$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
				$idcuenta_haber = $bus_consultar_cuenta_contable["idcuenta_contable"];
				$tabla_haber = $bus_consultar_cuenta_contable["tabla"];
			}




			$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_debe."',
																				'".$idcuenta_debe."',
																				'debe',
																				'".$monto."')");
			$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_haber."',
																				'".$idcuenta_haber."',
																				'haber',
																				'".$monto."')");
			
		
			$sql_asiento_contable = mysql_query("select * from asiento_contable where idasiento_contable = ".$idasiento_contable."")or die("aqui asiento ".mysql_error());
			$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable)or die("aqui asiento ".mysql_error());
			$sql_contable = mysql_query("insert into asiento_contable (idfuente_financiamiento,
																	detalle,
																	fecha_contable,
																	tipo_movimiento,
																	iddocumento,
																	estado,
																	status,
																	usuario,
																	fechayhora,
																	prioridad
																		)values(
																				'".$bus_asiento_contable["idfuente_financiamiento"]."',
																				'".'ANULACION DE ASIENTO: '.$bus_asiento_contable["detalle"]."',
																				'".$campos["fecha_anulacion"]."',
																				'emision_pagos',
																				'".$bus_asiento_contable["iddocumento"]."',
																				'anulado',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'4')")or die("aqui insert ".mysql_error());
			
			
			if($sql_contable){
				$idasiento_contable = mysql_insert_id();
				$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");
				
				while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
					if ($bus_cuentas_contables["afecta"] == 'debe'){ $afecta = 'haber'; }else{ $afecta = 'debe'; }
						$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla"]."',
																				'".$bus_cuentas_contables["idcuenta"]."',
																				'".$afecta."',
																				'".$bus_cuentas_contables["monto"]."')");
				}
			
			}
		}		

	}	
}


$sql_compromisos = mysql_query("select * from ingresos_egresos_financieros");
echo "MOVIMIENTOS FINANCIEROS";
while ($campos = mysql_fetch_array($sql_compromisos)){
	$sql_cuentas_contables = mysql_query("select * from tipo_movimiento_bancario where idtipo_movimiento_bancario = '".$campos["idtipo_movimiento"]."'");
	$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
	if ($bus_cuentas_contables["excluir_contabilidad"] != 'si'){
		if($campos["estado"] == 'elaboracion'){ $estado = 'elaboracion'; }
		elseif($campos["estado"] == 'anulado'){ $estado = 'anulado'; }
		else { $estado = 'procesado'; }
		echo $estado;
		$idcuenta_debe=0;
		$idcuenta_haber=0;
		$tabla_debe=0;
		$tabla_haber=0;
		if ($bus_cuentas_contables["tabla_debe"]!='0'){
			$idcuenta_debe = $bus_cuentas_contables["idcuenta_debe"];
			$tabla_debe = $bus_cuentas_contables["tabla_debe"];
		}else{
			$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$campos["idcuentas_bancarias"]."'");
			$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
			$idcuenta_debe = $bus_consultar_cuenta_contable["idcuenta_contable"];
			$tabla_debe = $bus_consultar_cuenta_contable["tabla"];
		}
		if ($bus_cuentas_contables["tabla_haber"]!='0'){
			$idcuenta_haber = $bus_cuentas_contables["idcuenta_haber"];
			$tabla_haber = $bus_cuentas_contables["tabla_haber"];
		}else{
			$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$campos["idcuentas_bancarias"]."'");
			$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
			$idcuenta_haber = $bus_consultar_cuenta_contable["idcuenta_contable"];
			$tabla_haber = $bus_consultar_cuenta_contable["tabla"];
		}
		$monto = $campos["monto"];

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
																				'".$campos["concepto"]."',
																				'movimiento_bancario',
																				'".$campos["idingresos_financieros"]."',
																				'".$estado."',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha"]."',
																				'1')");
			
			$idasiento_contable = mysql_insert_id();
			echo " id ".$idasiento_contable;
			
			$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_debe."',
																				'".$idcuenta_debe."',
																				'debe',
																				'".$monto."')");
			$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_haber."',
																				'".$idcuenta_haber."',
																				'haber',
																				'".$monto."')");
			
			
		}else{
		//REVERSO EL COMPROMISO
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
																				'".$campos["concepto"]."',
																				'movimiento_bancario',
																				'".$campos["idingresos_financieros"]."',
																				'procesado',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha"]."',
																				'si',
																				'1')");
			
			$idasiento_contable = mysql_insert_id();
			echo " id ".$idasiento_contable;
			
			$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_debe."',
																				'".$idcuenta_debe."',
																				'debe',
																				'".$monto."')");
			$sql_cuenta_contable_haber = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$tabla_haber."',
																				'".$idcuenta_haber."',
																				'haber',
																				'".$monto."')");
			
		
			$sql_asiento_contable = mysql_query("select * from asiento_contable where idasiento_contable = ".$idasiento_contable."")or die("aqui asiento ".mysql_error());
			$bus_asiento_contable = mysql_fetch_array($sql_asiento_contable)or die("aqui asiento ".mysql_error());
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
																				'".'ANULACION DE ASIENTO: '.$campos["concepto"]."',
																				'movimiento_bancario',
																				'".$campos["idingresos_financieros"]."',
																				'anulado',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_anulacion"]."',
																				'1')");
			
			
			if($sql_contable){
				$idasiento_contable = mysql_insert_id();
				$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$bus_asiento_contable["idasiento_contable"]."'");
				
				while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
					if ($bus_cuentas_contables["afecta"] == 'debe'){ $afecta = 'haber'; }else{ $afecta = 'debe'; }
						$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$idasiento_contable."',
																				'".$bus_cuentas_contables["tabla"]."',
																				'".$bus_cuentas_contables["idcuenta"]."',
																				'".$afecta."',
																				'".$bus_cuentas_contables["monto"]."')");
				}
			
			}
		}
	}
}


?>