<?
//$link=mysql_connect("localhost","root","gestion2009");
//mysql_select_db("gestion_mapire_15072015",$link);
include("conf/conex.php");
Conectarse();
$borrar_compromisos = mysql_query("delete from asiento_contable where tipo_movimiento='causado'");
$sql_compromisos = mysql_query("select * from orden_pago");
echo "ORDENES DE PAGO ";
while ($campos = mysql_fetch_array($sql_compromisos)){
	$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
	$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
	if($bus_cuentas_contables["excluir_contabilidad"] != 'si'){
		if($campos["estado"] == 'elaboracion'){ $estado = 'elaboracion'; }
		elseif($campos["estado"] == 'anulado'){ $estado = 'anulado'; }
		else { $estado = 'procesado'; }
		echo $estado;
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
																				'causado',
																				'".$campos["idorden_pago"]."',
																				'".$estado."',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_orden"]."',
																				'3')");
			
			$idasiento_contable = mysql_insert_id();
			
			$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
			$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
			if ($bus_cuentas_contables["multi_categoria"] == "no"){
				$monto = $campos["total"];
			}else{
				$monto = $campos["sub_total"];
			}
			
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
																				'".$monto."')");
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
																				'".$monto."')");
			
			
		if ($bus_cuentas_contables["compromete"] == 'no' and $bus_cuentas_contables["causa"] == 'si'){
			$bus_orden_pago = $campos;
			$bus_tipo_documento = $bus_cuentas_contables;
			$id_orden_pago = $campos["idorden_pago"];
			$relacion_pago_compromisos = mysql_query("select * from relacion_pago_compromisos where idorden_pago = ".$id_orden_pago."");
			//$reg_relacion = mysql_fetch_array($relacion_pago_compromisos);
			
			if($bus_orden_pago["forma_pago"] != "parcial" && $bus_orden_pago["forma_pago"] != "valuacion"){
			
				//ACTUALIZO LAS CUENTAS CONTABLES 
				if($bus_tipo_documento["multi_categoria"] == "no"){ // SI LA ORDEN DE PAGO SE RELACIONA CON COMPROMISOS DE TIPO UNICATEGORIA COMO COMPRAS, SERVICIOS
					$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_pago."
																	and tipo_movimiento = 'causado'");
					while ($reg_relacion = mysql_fetch_array($relacion_pago_compromisos)){
					//VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
					$sql_retencion = mysql_query("select * from retenciones where iddocumento = '".$reg_relacion["idorden_compra_servicio"]."'");
					$bus_retencion = mysql_fetch_array($sql_retencion);	
					$num_retencion = mysql_num_rows($sql_retencion);
					if($num_retencion > 0){
						//OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
						$sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = ".$bus_retencion["idretenciones"]."");
						while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)){
							$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$bus_relacion_retenciones["idtipo_retencion"]."");
							$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
							//valido la cuenta que afecta la retencion por el debe, 
							//si es la misma que la que ya existe no la afecto, 
							//si no es la misma la busco en el haber para ver si existe y restar el monto retenido
							$sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																		and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																		and afecta = 'debe'");
							$num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
							if ($num_cuenta_retencion_debe == 0){
								//entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
								//la busco en el haber para restarla
								$sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																		and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																		and afecta = 'haber'");
								$num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
								if ($num_cuenta_retencion_haber > 0){
									//valido que la cuenta a insertar no este registrada
									$sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																		and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																		and afecta = 'haber'");
									$bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
									$num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
									if ($num_cuenta_retencion_haber_nueva == 0){
										//si la cuenta no existe la insertamos la cuenta 
										$ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																														tabla,
																														idcuenta,
																														monto,
																														afecta)values(
																														'".$idasiento_contable."',
																														'".$bus_tipo_retencion["tabla_haber"]."',
																														'".$bus_tipo_retencion["idcuenta_haber"]."',
																														'".$bus_relacion_retenciones["monto_retenido"]."',
																														'haber'
																														)");
									}else{
										//si la cuenta existe le sumo el monto retenido
										$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_retenciones["monto_retenido"]."'
																			where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																					and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																					and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
									}
								}
							}										
						}
						//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
						$sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																			where idasiento_contable = '".$idasiento_contable."'
																				and idcuenta <> '".$bus_tipo_retencion["idcuenta_debe"]."'
																				and tabla <> '".$bus_tipo_retencion["tabla_debe"]."'
																				and afecta = 'haber'");
						$monto_retenido = mysql_fetch_array($sql_suma_retenido);
						//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
						//echo 'RETENIDO '.$monto_retenido["monto_retenido"];
						$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$monto_retenido["monto_retenido"]."'
																				where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																					and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																					and afecta = 'haber'");
					}
					}
				}else{ // SI EL DOCUMENTO RELACIONADO ES DE TIPO MULTICATEGORIA COMO NOMINAS LAS RETENCIONES BIENEN DE LOS CONCEPTOS DE NOMINA
					$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_pago."
																	and tipo_movimiento = 'causado'");
					while ($reg_relacion = mysql_fetch_array($relacion_pago_compromisos)){												
					
					//VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
					
					//$num_retencion = mysql_num_rows($sql_deduccion);
					//if($bus_select_orden_compra["exento"] > 0){
						//OBTENGO LOS CONCEPTOS DE TIPO DEDUCCION
						$sql_deduccion = mysql_query("select articulos_compra_servicio.exento,
															 articulos_servicios.idcuenta_debe,
															 articulos_servicios.tabla_debe,
															 articulos_servicios.idcuenta_haber,
															 articulos_servicios.tabla_haber
															from articulos_compra_servicio, articulos_servicios
															where articulos_compra_servicio.idorden_compra_servicio = '".$reg_relacion["idorden_compra_servicio"]."'
															and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
															and articulos_servicios.tipo_concepto = '2'");
						
						while ($bus_relacion_deducciones = mysql_fetch_array($sql_deduccion)){
							//valido la cuenta que afecta la retencion por el debe, 
							//si es la misma que la que ya existe no la afecto, 
							//si no es la misma la busco en el haber para ver si existe y restar el monto retenido
							$sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
																		and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
																		and afecta = 'debe'");
							$num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
							//echo " existe en el debe ".$num_cuenta_retencion_debe;
							if ($num_cuenta_retencion_debe == 0){
								//entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
								//la busco en el haber para restarla
								$sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
																		and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
																		and afecta = 'haber'");
								$num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
								//echo " existe en el haber ".$num_cuenta_retencion_haber;
								if ($num_cuenta_retencion_haber > 0){
									//valido que la cuenta a insertar no este registrada
									$sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_relacion_deducciones["idcuenta_haber"]."'
																		and tabla = '".$bus_relacion_deducciones["tabla_haber"]."'
																		and afecta = 'haber'");
									$bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
									$num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
									if ($num_cuenta_retencion_haber_nueva == 0){
										//si la cuenta no existe la insertamos la cuenta 
										$ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																														tabla,
																														idcuenta,
																														monto,
																														afecta)values(
																														'".$idasiento_contable."',
																														'".$bus_relacion_deducciones["tabla_haber"]."',
																														'".$bus_relacion_deducciones["idcuenta_haber"]."',
																														'".$bus_relacion_deducciones["exento"]."',
																														'haber'
																														)");
									}else{
										//si la cuenta existe le sumo el monto retenido
										$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_deducciones["exento"]."'
																			where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_relacion_deducciones["idcuenta_haber"]."'
																					and tabla = '".$bus_relacion_deducciones["tabla_haber"]."'
																					and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
									}
									//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
									$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$bus_relacion_deducciones["exento"]."'
																				where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
																					and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
																					and afecta = 'haber'");
								}
							}										
						}
					}
				}
			}else{
				$sql_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = ".$id_orden_pago."");
				$reg_relacion = mysql_fetch_array($sql_relacion_retenciones);
				
				//VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
			
			$sql_retencion = mysql_query("select * from retenciones where idretenciones = '".$reg_relacion["idretencion"]."'");
			$bus_retencion = mysql_fetch_array($sql_retencion);	
			$num_retencion = mysql_num_rows($sql_retencion);
			if($num_retencion > 0){
				//OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
				$sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = ".$bus_retencion["idretenciones"]."");
				while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)){
					$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$bus_relacion_retenciones["idtipo_retencion"]."");
					$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
					//valido la cuenta que afecta la retencion por el debe, 
					//si es la misma que la que ya existe no la afecto, 
					//si no es la misma la busco en el haber para ver si existe y restar el monto retenido
					$sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable 
															where idasiento_contable = '".$idasiento_contable."'
																and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																and afecta = 'debe'");
					$num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
					
					if ($num_cuenta_retencion_debe == 0){
						//entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
						//la busco en el haber para restarla
						$sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable 
															where idasiento_contable = '".$idasiento_contable."'
																and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																and afecta = 'haber'");
						$num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
						if ($num_cuenta_retencion_haber > 0){
							//valido que la cuenta a insertar no este registrada
							$sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable 
															where idasiento_contable = '".$idasiento_contable."'
																and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																and afecta = 'haber'");
							$bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
							$num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
							if ($num_cuenta_retencion_haber_nueva == 0){
								//si la cuenta no existe la insertamos la cuenta 
								$ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																												tabla,
																												idcuenta,
																												monto,
																												afecta)values(
																												'".$idasiento_contable."',
																												'".$bus_tipo_retencion["tabla_haber"]."',
																												'".$bus_tipo_retencion["idcuenta_haber"]."',
																												'".$bus_relacion_retenciones["monto_retenido"]."',
																												'haber'
																												)");
							}else{
								//si la cuenta existe le sumo el monto retenido
								$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_retenciones["monto_retenido"]."'
																	where idasiento_contable = '".$idasiento_contable."'
																			and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																			and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																			and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
							}
						}
					}										
				}
				//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
				$sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta <> '".$bus_tipo_retencion["idcuenta_debe"]."'
																		and tabla <> '".$bus_tipo_retencion["tabla_debe"]."'
																		and afecta = 'haber'");
				$monto_retenido = mysql_fetch_array($sql_suma_retenido);
				//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
				//echo 'RETENIDO '.$monto_retenido["monto_retenido"];
				$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$monto_retenido["monto_retenido"]."'
																		where idasiento_contable = '".$idasiento_contable."'
																			and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																			and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																			and afecta = 'haber'");
			}
				
				
				
			}
			
		}
																				
		}else{
			echo 'ENTRO anulado '.$campos["idorden_pago"];
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
																				'causado',
																				'".$campos["idorden_pago"]."',
																				'procesado',
																				'a',
																				'".$campos["usuario"]."',
																				'".$campos["fechayhora"]."',
																				'".$campos["fecha_orden"]."',
																				'si',
																				'3')");
			
			$idasiento_contable = mysql_insert_id();
			echo " id asiento ".$idasiento_contable;
			
			$sql_cuentas_contables = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$campos["tipo"]."'");
			$bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables);
			if ($bus_cuentas_contables["multi_categoria"] == "no"){
				$monto = $campos["total"];
			}else{
				$monto = $campos["sub_total"];
			}
			
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
																				'".$monto."')");
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
																				'".$monto."')");
			
			if ($bus_cuentas_contables["compromete"] == 'no' and $bus_cuentas_contables["causa"] == 'si'){
			
			$bus_orden_pago = $campos;
			$bus_tipo_documento = $bus_cuentas_contables;
			$id_orden_pago = $campos["idorden_pago"];
			$relacion_pago_compromisos = mysql_query("select * from relacion_pago_compromisos where idorden_pago = ".$id_orden_pago."");
			$reg_relacion = mysql_fetch_array($relacion_pago_compromisos);
			
			if($bus_orden_pago["forma_pago"] != "parcial" && $bus_orden_pago["forma_pago"] != "valuacion"){
			
				//ACTUALIZO LAS CUENTAS CONTABLES 
				if($bus_tipo_documento["multi_categoria"] == "no"){ // SI LA ORDEN DE PAGO SE RELACIONA CON COMPROMISOS DE TIPO UNICATEGORIA COMO COMPRAS, SERVICIOS
					$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_pago."
																	and tipo_movimiento = 'causado'");
					
					//VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
					$sql_retencion = mysql_query("select * from retenciones where iddocumento = '".$reg_relacion["idorden_compra_servicio"]."'");
					$bus_retencion = mysql_fetch_array($sql_retencion);	
					$num_retencion = mysql_num_rows($sql_retencion);
					if($num_retencion > 0){
						//OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
						$sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = ".$bus_retencion["idretenciones"]."");
						while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)){
							$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$bus_relacion_retenciones["idtipo_retencion"]."");
							$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
							//valido la cuenta que afecta la retencion por el debe, 
							//si es la misma que la que ya existe no la afecto, 
							//si no es la misma la busco en el haber para ver si existe y restar el monto retenido
							$sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																		and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																		and afecta = 'debe'");
							$num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
							if ($num_cuenta_retencion_debe == 0){
								//entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
								//la busco en el haber para restarla
								$sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																		and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																		and afecta = 'haber'");
								$num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
								if ($num_cuenta_retencion_haber > 0){
									//valido que la cuenta a insertar no este registrada
									$sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																		and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																		and afecta = 'haber'");
									$bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
									$num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
									if ($num_cuenta_retencion_haber_nueva == 0){
										//si la cuenta no existe la insertamos la cuenta 
										$ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																														tabla,
																														idcuenta,
																														monto,
																														afecta)values(
																														'".$idasiento_contable."',
																														'".$bus_tipo_retencion["tabla_haber"]."',
																														'".$bus_tipo_retencion["idcuenta_haber"]."',
																														'".$bus_relacion_retenciones["monto_retenido"]."',
																														'haber'
																														)");
									}else{
										//si la cuenta existe le sumo el monto retenido
										$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_retenciones["monto_retenido"]."'
																			where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																					and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																					and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
									}
								}
							}										
						}
						//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
						$sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																			where idasiento_contable = '".$idasiento_contable."'
																				and idcuenta <> '".$bus_tipo_retencion["idcuenta_debe"]."'
																				and tabla <> '".$bus_tipo_retencion["tabla_debe"]."'
																				and afecta = 'haber'");
						$monto_retenido = mysql_fetch_array($sql_suma_retenido);
						//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
						//echo 'RETENIDO '.$monto_retenido["monto_retenido"];
						$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$monto_retenido["monto_retenido"]."'
																				where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																					and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																					and afecta = 'haber'");
					}
				}else{ // SI EL DOCUMENTO RELACIONADO ES DE TIPO MULTICATEGORIA COMO NOMINAS LAS RETENCIONES BIENEN DE LOS CONCEPTOS DE NOMINA
					$sql_validar_asiento = mysql_query("select * from asiento_contable where iddocumento = ".$id_orden_pago."
																	and tipo_movimiento = 'causado'");
					//VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
					while ($reg_relacion = mysql_fetch_array($relacion_pago_compromisos)){	
					//$num_retencion = mysql_num_rows($sql_deduccion);
					//if($bus_select_orden_compra["exento"] > 0){
						//OBTENGO LOS CONCEPTOS DE TIPO DEDUCCION
						$sql_deduccion = mysql_query("select articulos_compra_servicio.exento,
															 articulos_servicios.idcuenta_debe,
															 articulos_servicios.tabla_debe,
															 articulos_servicios.idcuenta_haber,
															 articulos_servicios.tabla_haber
															from articulos_compra_servicio, articulos_servicios
															where articulos_compra_servicio.idorden_compra_servicio = '".$reg_relacion["idorden_compra_servicio"]."'
															and articulos_servicios.idarticulos_servicios = articulos_compra_servicio.idarticulos_servicios
															and articulos_servicios.tipo_concepto = '2'");
						
						while ($bus_relacion_deducciones = mysql_fetch_array($sql_deduccion)){
							//valido la cuenta que afecta la retencion por el debe, 
							//si es la misma que la que ya existe no la afecto, 
							//si no es la misma la busco en el haber para ver si existe y restar el monto retenido
							$sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
																		and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
																		and afecta = 'debe'");
							$num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
							//echo " existe en el debe ".$num_cuenta_retencion_debe;
							if ($num_cuenta_retencion_debe == 0){
								//entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
								//la busco en el haber para restarla
								$sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
																		and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
																		and afecta = 'haber'");
								$num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
								//echo " existe en el haber ".$num_cuenta_retencion_haber;
								if ($num_cuenta_retencion_haber > 0){
									//valido que la cuenta a insertar no este registrada
									$sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable 
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta = '".$bus_relacion_deducciones["idcuenta_haber"]."'
																		and tabla = '".$bus_relacion_deducciones["tabla_haber"]."'
																		and afecta = 'haber'");
									$bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
									$num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
									if ($num_cuenta_retencion_haber_nueva == 0){
										//si la cuenta no existe la insertamos la cuenta 
										$ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																														tabla,
																														idcuenta,
																														monto,
																														afecta)values(
																														'".$idasiento_contable."',
																														'".$bus_relacion_deducciones["tabla_haber"]."',
																														'".$bus_relacion_deducciones["idcuenta_haber"]."',
																														'".$bus_relacion_deducciones["exento"]."',
																														'haber'
																														)");
									}else{
										//si la cuenta existe le sumo el monto retenido
										$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_deducciones["exento"]."'
																			where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_relacion_deducciones["idcuenta_haber"]."'
																					and tabla = '".$bus_relacion_deducciones["tabla_haber"]."'
																					and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
									}
									//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
									$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$bus_relacion_deducciones["exento"]."'
																				where idasiento_contable = '".$idasiento_contable."'
																					and idcuenta = '".$bus_relacion_deducciones["idcuenta_debe"]."'
																					and tabla = '".$bus_relacion_deducciones["tabla_debe"]."'
																					and afecta = 'haber'");
								}
							}										
						}
					}
				}
			}else{
				$sql_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = ".$id_orden_pago."");
				$reg_relacion = mysql_fetch_array($sql_relacion_retenciones);
				
				//VALIDO SI TIENE RETENCIONES PARA DIFERENCIAR LA CUENTA CONTABLE
			
			$sql_retencion = mysql_query("select * from retenciones where idretenciones = '".$reg_relacion["idretencion"]."'");
			$bus_retencion = mysql_fetch_array($sql_retencion);	
			$num_retencion = mysql_num_rows($sql_retencion);
			if($num_retencion > 0){
				//OBTENGO LA RELACION DE RETENCIONES PARA SABER DE EL TIPO DE RETENCION Y LAS CUENTAS QUE AFECTA
				$sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idretenciones = ".$bus_retencion["idretenciones"]."");
				while ($bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones)){
					$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$bus_relacion_retenciones["idtipo_retencion"]."");
					$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
					//valido la cuenta que afecta la retencion por el debe, 
					//si es la misma que la que ya existe no la afecto, 
					//si no es la misma la busco en el haber para ver si existe y restar el monto retenido
					$sql_cuentas_contables_debe = mysql_query("select * from cuentas_asiento_contable 
															where idasiento_contable = '".$idasiento_contable."'
																and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																and afecta = 'debe'");
					$num_cuenta_retencion_debe = mysql_num_rows($sql_cuentas_contables_debe);
					
					if ($num_cuenta_retencion_debe == 0){
						//entro aqui porque la cuenta no existe afectando el debe por lo que no la voy a afectar por el debe
						//la busco en el haber para restarla
						$sql_cuentas_contables_haber = mysql_query("select * from cuentas_asiento_contable 
															where idasiento_contable = '".$idasiento_contable."'
																and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																and afecta = 'haber'");
						$num_cuenta_retencion_haber = mysql_num_rows($sql_cuentas_contables_haber);
						if ($num_cuenta_retencion_haber > 0){
							//valido que la cuenta a insertar no este registrada
							$sql_cuentas_contables_haber_nueva = mysql_query("select * from cuentas_asiento_contable 
															where idasiento_contable = '".$idasiento_contable."'
																and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																and afecta = 'haber'");
							$bus_cuenta_retencion_haber_nueva = mysql_fetch_array($sql_cuentas_contables_haber_nueva);
							$num_cuenta_retencion_haber_nueva = mysql_num_rows($sql_cuentas_contables_haber_nueva);
							if ($num_cuenta_retencion_haber_nueva == 0){
								//si la cuenta no existe la insertamos la cuenta 
								$ingreso_cuenta_haber_retencion = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																												tabla,
																												idcuenta,
																												monto,
																												afecta)values(
																												'".$idasiento_contable."',
																												'".$bus_tipo_retencion["tabla_haber"]."',
																												'".$bus_tipo_retencion["idcuenta_haber"]."',
																												'".$bus_relacion_retenciones["monto_retenido"]."',
																												'haber'
																												)");
							}else{
								//si la cuenta existe le sumo el monto retenido
								$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto + '".$bus_relacion_retenciones["monto_retenido"]."'
																	where idasiento_contable = '".$idasiento_contable."'
																			and idcuenta = '".$bus_tipo_retencion["idcuenta_haber"]."'
																			and tabla = '".$bus_tipo_retencion["tabla_haber"]."'
																			and afecta = 'haber'")or die("actualizando monto contable ".mysql_error());
							}
						}
					}										
				}
				//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
				$sql_suma_retenido = mysql_query("select sum(monto) as monto_retenido from cuentas_asiento_contable
																	where idasiento_contable = '".$idasiento_contable."'
																		and idcuenta <> '".$bus_tipo_retencion["idcuenta_debe"]."'
																		and tabla <> '".$bus_tipo_retencion["tabla_debe"]."'
																		and afecta = 'haber'");
				$monto_retenido = mysql_fetch_array($sql_suma_retenido);
				//actualizo la cuenta existente para restarle el monto retenido en ese tipo de retencion
				//echo 'RETENIDO '.$monto_retenido["monto_retenido"];
				$actualizo_cuenta_haber = mysql_query("update cuentas_asiento_contable set monto = monto - '".$monto_retenido["monto_retenido"]."'
																		where idasiento_contable = '".$idasiento_contable."'
																			and idcuenta = '".$bus_tipo_retencion["idcuenta_debe"]."'
																			and tabla = '".$bus_tipo_retencion["tabla_debe"]."'
																			and afecta = 'haber'");
			}
				
				
				
			}
			}
		//REVERSO EL COMPROMISO
		
		
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
																					'causado',
																					'".$bus_asiento_contable["iddocumento"]."',
																					'anulado',
																					'a',
																					'".$campos["usuario"]."',
																					'".$campos["fechayhora"]."',
																					'3')")or die("aqui insert ".mysql_error());
				
				
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