<?
include("conf/conex.php");
Conectarse();


$k=0;
$sql_consulta = mysql_query("select * from orden_pago, beneficiarios 
										where 
										beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios
										and orden_pago.estado = 'procesado'");
while($bus_consulta_orden = mysql_fetch_array($sql_consulta)){
	if($bus_consulta_orden["nombre"] == "GOBERNACION DEL ESTADO (MIGRACION)"){
		
		
	if($modo_cancelacion == "Porcentual"){
		$porcentaje_monto = $porcentaje_pago;
	}else{
		$porcentaje_monto = 0;
	}
	$sql_ingresar_emision_pagos = mysql_query("insert into pagos_financieros(idtipo_documento,
															forma_pago,
															modo_cancelacion,
															porcentaje_pago,
															numero_parte_pago,
															idtipo_movimiento_bancario,
															idorden_pago,
															idcuenta_bancaria,
															idcheques_cuentas_bancarias,
															numero_cheque,
															fecha_cheque,
															monto_cheque,
															beneficiario,
															ci_beneficiario,
															formato_imprimir,
															estado,
															status,
															usuario,
															fechayhora)values('53',
																				'total',
																				'porcentual',
																				'".$porcentaje_monto."',
																				'0',
																				'10',
																				'".$bus_consulta_orden["idorden_pago"]."',
																				'7',
																				'49',
																				'11111111',
																				'".date("Y-m-d")."',
																				'".$bus_consulta_orden["total_a_pagar"]."',
																				'".$bus_consulta_orden["nombre"]."',
																				'".$bus_consulta_orden["rif"]."',
																				'cheque',
																				'transito',
																				'a',
																				'".$login."',
																				'".$fh."')")or die(mysql_error());
	if($sql_ingresar_emision_pagos){
		$ultimo_registro = mysql_insert_id();
		echo "exito|.|".$bus_consulta_orden["numero_orden"]."<br>";
		
		$sql_consultar_chequera = mysql_query("select * from cheques_cuentas_bancarias where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
		$bus_consultar_chequera = mysql_fetch_array($sql_consultar_chequera);
		
		
		
		if($bus_consultar_chequera["ultimo_cheque"] == 99){
			$ultimo_cheque = 00;
		}else{
			$ultimo_cheque = $bus_consultar_chequera["ultimo_cheque"]+1;
		}
		
		
		// ACTUALIZAR EL MAESTRO DE PRESUPUESTO
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '53'");
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
			if($bus_tipo_documento["paga"] == "si"){
				if($bus_consulta_orden["idorden_pago"] != ""){
					$sql_partidas_orden_pago = mysql_query("select * from partidas_orden_pago where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
					while($bus_partidas_orden_pago = mysql_fetch_array($sql_partidas_orden_pago)){
						if($forma_pago == 'total'){
							$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
													set total_pagados = total_pagados+".$bus_partidas_orden_pago["monto"]." 
													where idRegistro = '".$bus_partidas_orden_pago["idmaestro_presupuesto"]."'");
							
					
					
					
					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);
					
					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_partidas_orden_pago["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'");
					$num_consulta_maestro = mysql_num_rows($sql_consulta_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'");    
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto
															set total_pagados = total_pagados+".$bus_partidas_orden_pago["monto"]."
															where idRegistro = ".$bus_sub_epe["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
							
						}
						
						$sql_clasificador = mysql_query("select * clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'");
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'");
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'");
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);
							
							$sql_maestro = mysql_query("update maestro_presupuesto
															set total_pagados = total_pagados+".$bus_partidas_orden_pago["monto"]."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
							
						}
						
					}
							
							
							
							
						}else{
							$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
							$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
							$monto_orden = $bus_orden_pago["total"];
							$porcentaje_monto_total = ($monto_cheque*100)/$monto_orden;
							$monto_sobre_partia = ($bus_partidas_orden_pago["monto"]*$porcentaje_monto_total)/100;
							$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
													set total_pagados = total_pagados+".$monto_sobre_partia." 
													where idRegistro = '".$bus_partidas_orden_pago["idmaestro_presupuesto"]."'");
							
					
					
					$sql_consulta_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
					$bus_consulta_ordinal = mysql_fetch_array($sql_consulta_ordinal);
					
					$sql_consultar_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus_partidas_orden_pago["idmaestro_presupuesto"]."' and idordinal != '".$bus_consulta_ordinal["idordinal"]."'");
					$num_consulta_maestro = mysql_num_rows($sql_consultar_maestro);
					if($num_consulta_maestro != 0){
						$bus_consultar_maestro= mysql_fetch_array($sql_consultar_maestro);
						$sql_sub_espe = mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'");    
						$num_sub_espe =mysql_num_rows($sql_sub_espe);
						if($num_sub_espe != 0){
							$bus_sub_epe = mysql_fetch_array($sql_sub_espe);
							$sql_maestro = mysql_query("update maestro_presupuesto 
															set total_pagados = total_pagados+".$monto_sobre_partia."
															where idRegistro = '".$bus_sub_epe["idmaestro_presupuesto"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
							
						}
						
						$sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consultar_maestro["idclasificador_presupuestario"]."' and sub_especifica != '00'");
						$num_clasificador = mysql_num_rows($sql_clasificador);
						if($num_clasificador > 0){
							$bus_clasificador = mysql_fetch_array($sql_clasificador);
							$sql_consulta_clasificador = mysql_query("select * from clasificador_presupuestario where partida = '".$bus_clasificador["partida"]."'
							and generica = '".$bus_clasificador["generica"]."'
							and especifica ='".$bus_clasificador["especifica"]."'
							and sub_especifica= '00'");
							$bus_consulta_clasificador= mysql_fetch_array($sql_consulta_clasificador);
							$sql_id_maestro= mysql_query("select * from maestro_presupuesto where 
							idcategoria_programatica= '".$bus_consultar_maestro["idcategoria_programatica"]."'
						and idtipo_presupuesto = '".$bus_consultar_maestro["idtipo_presupuesto"]."'
						and idfuente_financiamiento = '".$bus_consultar_maestro["idfuente_financiamiento"]."'
						and idclasificador_presupuestario = '".$bus_consulta_clasificador["idclasificador_presupuestario"]."'
						and idordinal = '".$bus_consulta_ordinal["idordinal"]."'");
							$bus_id_maestro = mysql_fetch_array($sql_id_maestro);
							
							$sql_maestro = mysql_query("update maestro_presupuesto set total_pagados = total_pagados+".$monto_sobre_partia."
															where idRegistro = ".$bus_id_maestro["idmaestro_presupuesto"]."")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
							
						}
						
					}
							
							
							
						}
					}
				}
			}
		// ACTUALIZAR EL MAESTRO DE PRESUPUESTO
		
		
		
		
		
		
		
		
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'")or die(mysql_error());
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
		//echo $bus_tipo_documento["forma_preimpresa"];
		if($bus_tipo_documento["forma_preimpresa"] == 'si'){
			$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set ultimo_cheque = '".$ultimo_cheque."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
		}
		
		
		
		
			if($bus_consultar_chequera["ultimo_cheque"] == $bus_consultar_chequera["numero_final"]){
					$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set estado = 'Finalizada', fecha_final_uso = '".date("Y-m-d")."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
			}
			
			if($bus_consultar_chequera["ultimo_cheque"] == $bus_consultar_chequera["numero_inicial"]){
					$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set fecha_inicio_uso = '".date("Y-m-d")."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
			}
			
			
		if($bus_consulta_orden["idorden_pago"] != 0){
			if($forma_pago == 'total'){
				$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'pagada' where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
			}else{
				$sql_contar_totales = mysql_query("select SUM(monto_cheque) as total from pagos_financieros where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
				$bus_contar_totales = mysql_fetch_array($sql_contar_totales);
				$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
				$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
				$total_orden_pago = $bus_orden_pago["total"] - $bus_orden_pago["total_retenido"];
					if($bus_contar_totales["total"]  < $total_orden_pago){
						$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'parcial' where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");	
					}else{
						$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'pagada' where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
					}
				
			}
		}
		
		
		//******************************************** ACTUALIZAR NUMERO DE RETENCION
		
		
		if($bus_consulta_orden["idorden_pago"] != 0){
			$sql_consultar_relacion_compromiso = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$bus_consulta_orden["idorden_pago"]."'");
				$acumulador = 0;
				
				while($bus_consultar_relacion_compromiso = mysql_fetch_array($sql_consultar_relacion_compromiso)){
					$sql_consultar_retenciones = mysql_query("select * from retenciones where iddocumento = '".$bus_consultar_relacion_compromiso["idorden_compra_servicio"]."'");
					$num_consultar_retenciones = mysql_num_rows($sql_consultar_retenciones);
					
					if($num_consultar_retenciones != 0){
					
						$bus_retencion = mysql_fetch_array($sql_consultar_retenciones);
						$bus_actualizar_fecha_retencion = mysql_query("update retenciones set 
																			fecha_aplicacion_retencion = '".$fecha_cheque."' where 
																			idretenciones = '".$bus_retencion["idretenciones"]."'");
																			
						$sql_consultar_retenciones = mysql_query("select * from retenciones where iddocumento = '".$bus_consultar_relacion_compromiso["idorden_compra_servicio"]."'");	
																		
						while($bus_consultar_retenciones = mysql_fetch_array($sql_consultar_retenciones)){
							$sql_consultar_relacion_retencion = mysql_query("select * from relacion_retenciones where idretenciones = '".$bus_consultar_retenciones["idretenciones"]."'");
							while($bus_consultar_relacion_retencion = mysql_fetch_array($sql_consultar_relacion_retencion)){
								$sql_consultar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_relacion_retencion["idtipo_retencion"]."'");
								$bus_consultar_tipo_retencion = mysql_fetch_array($sql_consultar_tipo_retencion);
									if($bus_consultar_tipo_retencion["asociado"] == 0){
										$numero_contador = $bus_consultar_tipo_retencion["numero_documento"];
										$retencion_a_modificar = $bus_consultar_tipo_retencion["idtipo_retencion"];
									}else{
										$sql_consultar_asociado = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'");
										$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);
										$numero_contador = $bus_consultar_asoaciado["numero_documento"];
										$retencion_a_modificar = $bus_consultar_asociado["idtipo_retencion"];
									}
									$numero_documento = $numero_contador;
									$sql_consultar_documento = mysql_query("select * from relacion_retenciones where numero_retencion = '".$numero_documento."'");
									$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
										if($acumulador == 0){
											while($num_consultar_documento > 0){
												$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																														where idtipo_retencion = '".$retencion_a_modificar."'");
												$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$retencion_a_modificar."'");
												$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
												$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
												$numero_documento = $numero_contador;
												$sql_consultar_documento = mysql_query("select * from relacion_retenciones where numero_retencion = '".$numero_documento."'");
												$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
											}
										}
										$acumulador++;
										//echo $fecha_cheque;
									$fecha = explode("-", $fecha_cheque);
									$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																		numero_retencion = '".$numero_documento."' 
																		where idretenciones = '".$bus_consultar_retenciones["idretenciones"]."' 
																		and idtipo_retencion = '".$retencion_a_modificar."'")or die(mysql_error());
								}
							}
					}
				}
				if($sql_relacion_retenciones){
					$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																					where idtipo_retencion = '".$retencion_a_modificar."'");	
				}	
		}

		
		
	
		
		//************************************************ ACTUALIZAR NUMERO DE DOCUMENTO DE PAGO FINANCIERO
		
		$numero_documento = "MIG-2009-".$k;
		$k++;
		
		
		//*******************************************
		
		
		
		$sql_actualizar_pago_financiero = mysql_query("update pagos_financieros set numero_documento = '".$numero_documento."' where idpagos_financieros = '".$ultimo_registro."'");
		
		
	}else{
		echo "fallo <br> ".$bus_consulta_orden["numero_orden"];
	}

		
		
		
		
		
	}
}
?>