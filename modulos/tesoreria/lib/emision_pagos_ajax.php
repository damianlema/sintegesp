<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarEmisionPagos"){
	
	if ($formato_imprimir == "Cheque"){
		$sql_consulta_pagos = mysql_query("select * from pagos_financieros where numero_cheque like '%".$numero_cheque."' and idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'");
		$num_consulta_pagos = mysql_num_rows($sql_consulta_pagos);
		//echo "NUM: ".$num_consulta_pagos;
		if($num_consulta_pagos > 0){
			$entro="existe";
		}
	}
	if ($entro == "existe"){
		echo "existe";
	}else{
		
	
	
	if($modo_cancelacion == "Porcentual"){
		$porcentaje_monto = $porcentaje_pago;
	}else{
		$porcentaje_monto = 0;
	}
	
	$sql= mysql_query("select * from configuracion_tributos");
	$bus = mysql_fetch_array($sql);
	$genera_comprobante = $bus["genera_comprobante"];
	
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
															fechayhora)values('".$idtipo_documento."',
																				'".$forma_pago."',
																				'".$modo_cancelacion."',
																				'".$porcentaje_monto."',
																				'".$numero_parte_pago."',
																				'".$idtipo_movimiento_bancario."',
																				'".$idorden_pago."',
																				'".$idcuenta_bancaria."',
																				'".$idcheques_cuentas_bancarias."',
																				'".$numero_cheque."',
																				'".$fecha_cheque."',
																				'".$monto_cheque."',
																				'".utf8_encode($beneficiario)."',
																				'".$ci_beneficiario."',
																				'".$formato_imprimir."',
																				'transito',
																				'a',
																				'".$login."',
																				'".$fh."')")or die(mysql_error());
	if($sql_ingresar_emision_pagos){
		$ultimo_registro = mysql_insert_id();
		echo "exito|.|".mysql_insert_id();
		
		$sql_consultar_chequera = mysql_query("select * from cheques_cuentas_bancarias where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
		$bus_consultar_chequera = mysql_fetch_array($sql_consultar_chequera);
		
		
		if($divCheque == $bus_consultar_chequera["ultimo_cheque"]){
			if($bus_consultar_chequera["ultimo_cheque"] == 99){
				$ultimo_cheque = 00;
			}else{
				$ultimo_cheque = $bus_consultar_chequera["ultimo_cheque"]+1;
			}
			$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set ultimo_cheque = '".$ultimo_cheque."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
		}
		
		
		// ACTUALIZAR EL MAESTRO DE PRESUPUESTO
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
			if($bus_tipo_documento["paga"] == "si"){
				if($idorden_pago != ""){
					$sql_partidas_orden_pago = mysql_query("select * from partidas_orden_pago where idorden_pago = '".$idorden_pago."'");
					while($bus_partidas_orden_pago = mysql_fetch_array($sql_partidas_orden_pago)){
						if($forma_pago == 'total'){
							$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
													set total_pagados = total_pagados+".$bus_partidas_orden_pago["monto"]." 
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
																	set total_pagados = total_pagados+".$bus_partidas_orden_pago["monto"]."
																	where idRegistro = '".$bus_sub_epe["idRegistro"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE 					
																	PRESUPUESTO: ".mysql_error());
									
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
									
									$sql_maestro = mysql_query("update maestro_presupuesto
																	set total_pagados = total_pagados+".$bus_partidas_orden_pago["monto"]."
																	where idRegistro = '".$bus_id_maestro["idRegistro"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE 
																	PRESUPUESTO: ".mysql_error());
									
								}
						
							}
							
							
							
							
						}else{
							$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
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
																	set total_pagados = total_pagados+".$monto_sobre_partia."
																	where idRegistro = '".$bus_sub_epe["idRegistro"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE 
																	PRESUPUESTO: ".mysql_error());
									
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
									
									$sql_maestro = mysql_query("update maestro_presupuesto set total_pagados = total_pagados+".$monto_sobre_partia."
																	where idRegistro = '".$bus_id_maestro["idRegistro"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
									
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
			//$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set ultimo_cheque = '".$ultimo_cheque."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
		}
			
			if($bus_consultar_chequera["ultimo_cheque"] == $bus_consultar_chequera["numero_inicial"]){
					$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set fecha_inicio_uso = '".date("Y-m-d")."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
			}
		
		
		
		$sql_consultar_chequera = mysql_query("select * from cheques_cuentas_bancarias where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'");
		$bandera = false;
		while($bus_consultar_chequera = mysql_fetch_array($sql_consultar_chequera)){
			$sql_con = mysql_query("select * from pagos_financieros where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'");
			$num_con = mysql_num_rows($sql_con);
			if($num_con == $bus_consultar_chequera["cantidad_cheques"]){
					$bandera = true;
			}
		}
		
		
		
		
		
		
		
		if($bandera == true){
			$sql_actualizar_chequera = mysql_query("update cheques_cuentas_bancarias set estado = 'Finalizada', fecha_final_uso = '".date("Y-m-d")."' where idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'")or die("1: ".mysql_error());
			$bandera = false;
		}
		
		
			
			
		if($idorden_pago != 0){
			if($forma_pago == 'total'){
				$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'pagada' 
																where idorden_pago = '".$idorden_pago."'");
			}else{
				$sql_contar_totales = mysql_query("select SUM(monto_cheque) as total from pagos_financieros where idorden_pago = '".$idorden_pago."'");
				$bus_contar_totales = mysql_fetch_array($sql_contar_totales);
				$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
				$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
				$total_orden_pago = $bus_orden_pago["total"] - $bus_orden_pago["total_retenido"];
					if($bus_contar_totales["total"]  < $total_orden_pago){
						$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'parcial' where idorden_pago = '".$idorden_pago."'");	
					}else{
						$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'pagada' where idorden_pago = '".$idorden_pago."'");
					}
				
			}
		}
		
		
		//*******************************************************************************************************************
		//	REGISTRO DEL ASIENTO CONTABLE
		if($bus_tipo_documento["excluir_contabilidad"] != 'si'){
			$sql_orden = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
			$reg_orden = mysql_fetch_array($sql_orden);
			$mes_contable = explode("-",$fecha_cheque);
			$sql_registrar_asiento_contable = mysql_query("insert into asiento_contable(idfuente_financiamiento,
																					fecha_contable,
																					mes_contable,
																					detalle,
																					tipo_movimiento,
																					iddocumento,
																					estado,
																					usuario,
																					fechayhora,
																					prioridad)
														values('".$reg_orden["idfuente_financiamiento"]."',
																'".$fecha_cheque."',
																'".$mes_contable[1]."',
																'PAGO DE: ".$reg_orden["justificacion"]." Documento Nro.".$numero_cheque."',
																'emision_pagos',
																'".$ultimo_registro."',
																'procesado',
																'".$login."',
																'".$fh."',
																'4')")or die(mysql_error());
																										
			$idasiento_contable = mysql_insert_id();
			
			$sql_registrar_ingresos = mysql_query("update pagos_financieros set idasiento_contable = '".$idasiento_contable."'
																	where idpagos_financieros = '".$ultimo_registro."'")or die(mysql_error());
			
			$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																					tabla,
																					idcuenta,
																					monto,
																					afecta)values('".$idasiento_contable."',
																										'".$tabla_debe."',
																										'".$idcuenta_debe."',
																										'".$monto_cheque."',
																										'debe')")or die(mysql_error());
			$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																					tabla,
																					idcuenta,
																					monto,
																					afecta)values('".$idasiento_contable."',
																										'".$tabla_haber."',
																										'".$idcuenta_haber."',
																										'".$monto_cheque."',
																										'haber')")or die(mysql_error());
			
		}
		
		
		
		
		
		
		
		if ($genera_comprobante == '1'){
		//******************************************** ACTUALIZAR NUMERO DE RETENCION
			$sql_fecha_cierre = mysql_query("select * from configuracion");
			$bus_fecha_cierre = mysql_fetch_array($sql_fecha_cierre);
			$fecha_cierre = $bus_fecha_cierre["fecha_cierre"];
			if (date("Y-m-d") < $fecha_cierre){ $fecha_aplicacion = date("Y-m-d"); }else{ $fecha_aplicacion = $fecha_cierre; }
		
			if($idorden_pago != 0){
				
			
				$sql_consulta = mysql_query("select *
												from
													retenciones,
													relacion_orden_pago_retencion,
													relacion_retenciones
												where
													relacion_orden_pago_retencion.idorden_pago = '".$idorden_pago."'
													and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
													and relacion_retenciones.idretenciones = retenciones.idretenciones
													and relacion_retenciones.numero_retencion !=0")or die(mysql_error());
				
				$num_consulta = mysql_num_rows($sql_consulta);
		
				if($num_consulta > 0){
					echo "existeComprobante";
				}else{
		
		
					$sql_configuracion = mysql_query("select nro_linea_comprobante from configuracion_tributos")or die(mysql_error());
					$bus_configuracion = mysql_fetch_array($sql_configuracion);
					
					
					//$sql_consultar_relacion_compromiso = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$idorden_pago."'");
					$sql_consultar_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$idorden_pago."'");
					$acumulador = 0;
					
					// while de los compromisos pagados en esa orden
					$num_consultar_retenciones = mysql_num_rows($sql_consultar_relacion_retenciones);
					if($num_consultar_retenciones != 0){
				
						while($bus_consultar_relacion_compromiso = mysql_fetch_array($sql_consultar_relacion_retenciones)){
							
							$sql_consultar_retenciones = mysql_query("select * from retenciones 
																			where idretenciones = '".$bus_consultar_relacion_compromiso["idretencion"]."'");
							$bus_retencion = mysql_fetch_array($sql_consultar_retenciones);
								
							$bus_actualizar_fecha_retencion = mysql_query("update retenciones set 
																					fecha_aplicacion_retencion = '".$fecha_aplicacion."' where 
																					idretenciones = '".$bus_retencion["idretenciones"]."'");
																					
							$sql_consultar_relacion_retencion = mysql_query("select * from relacion_retenciones, tipo_retencion 
																				where relacion_retenciones.idretenciones = '".$bus_retencion["idretenciones"]."'
																					and relacion_retenciones.generar_comprobante = 'si'
																					and tipo_retencion.idtipo_retencion = relacion_retenciones.idtipo_retencion
																					and tipo_retencion.nombre_comprobante <> 'NA'
																					order by asociado")or die(mysql_error());
							
							//bucle  con las retenciones del documento
							while($bus_consultar_relacion_retencion = mysql_fetch_array($sql_consultar_relacion_retencion)){
								$seguir = 'si';
								//consulto el tipo de retencion para saber si tiene numero de comprobante propio o asociado
								$sql_consultar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_relacion_retencion["idtipo_retencion"]."'");
								$bus_consultar_tipo_retencion = mysql_fetch_array($sql_consultar_tipo_retencion);
								// mantengo el id del tipo de retencion a colocar el numero de comprobante
								$retencion_a_modificar_original = $bus_consultar_tipo_retencion["idtipo_retencion"];
								//echo "orig ".$retencion_a_modificar_original;
								if($bus_consultar_tipo_retencion["asociado"] == 0){
										//valido que alguna retencion hija de este padre se le haya asignado algun numero de comprobante para actualizarselo al padre
										$existen=0;
										$sql_valido_hijos=mysql_query("select * from tipo_retencion
																		where asociado = '".$bus_consultar_tipo_retencion["idtipo_retencion"]."'")or die(mysql_error());
										while($bus_relacion_retencion_asociados = mysql_fetch_array($sql_valido_hijos)){
											$sql_asociados_relacion_retencion = mysql_query("select * from relacion_retenciones
																				where idretenciones = '".$bus_retencion["idretenciones"]."'
																					and idtipo_retencion = '".$bus_relacion_retencion_asociados["idtipo_retencion"]."'
																					and numero_retencion <> '0'
																					")or die("error buscando asociados ".mysql_error());
											if (mysql_num_rows($sql_asociados_relacion_retencion)){
												$existe_hijo = mysql_num_rows($sql_asociados_relacion_retencion);
											}else{
												$existe_hijo = 0;
											}
											if ($existe_hijo > 0){
												$bus_asociado_con_numero = mysql_fetch_array($sql_asociados_relacion_retencion);
												$numero_contador = $bus_asociado_con_numero["numero_retencion"];
												$retencion_a_modificar_contador = 0;
												$existen=1;
											}
										
										}
										//si ninguno de los hijos esta en esta retencion o no se les ha asignado numero les asigno el numero del contador del tipo padre
										if ($existen == 0){
											$numero_contador = $bus_consultar_tipo_retencion["numero_documento"];
											$retencion_a_modificar_contador = $bus_consultar_tipo_retencion["idtipo_retencion"];
										}
								}else{
									$existen = 0;
										//valido que el tipo padre no este dentro de este comprobante para evitar que genere un comprobante por cada retencion del mismo tipo
										$sql_consultar_padre = mysql_query("select * from relacion_retenciones 
																						where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'
																							and idretenciones = '".$bus_retencion["idretenciones"]."'
																							")or die("error buscando padre ".mysql_error());
																							
										if (mysql_num_rows($sql_consultar_padre)){
											$num_existe_padre = mysql_num_rows($sql_consultar_padre)or die(mysql_error());
											
											if ($num_existe_padre <= 0){
												//valido que alguno de los otros hijos asociados no se les haya asignado numero
												$sql_valido_hijos_asociados=mysql_query("select * from tipo_retencion
																		where asociado = '".$bus_consultar_tipo_retencion["asociado"]."'")or die(mysql_error());
																		
												while($bus_relacion_hijos_asociados = mysql_fetch_array($sql_valido_hijos_asociados)){
													$sql_asociados_hijos_retencion = mysql_query("select * from relacion_retenciones
																						where idretenciones = '".$bus_retencion["idretenciones"]."'
																							and idtipo_retencion = '".$bus_relacion_hijos_asociados["idtipo_retencion"]."'
																							and numero_retencion <> '0'")or die(mysql_error());
																							
													if (mysql_num_rows($sql_asociados_hijos_retencion)){
														$existe_hijo = mysql_num_rows($sql_asociados_hijos_retencion);
													}else{
														$existe_hijo = 0;
													}
													if ($existe_hijo > 0){
														$bus_asociado_hijo_con_numero = mysql_fetch_array($sql_asociados_hijos_retencion);
														$numero_contador = $bus_asociado_hijo_con_numero["numero_retencion"];
														$retencion_a_modificar_contador = 0;
														$existen=1;
													}
												
												}
												
												if ($existen == 0){
													//si es asociado, busco el tipo de documento padre para obtener el numero de comprobante a aplicarlo
													$sql_consultar_asociado = mysql_query("select * from tipo_retencion 
																									where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'")or die("aqui 1 ".mysql_error());
													$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);
													
													$numero_contador = $bus_consultar_asociado["numero_documento"];
													$retencion_a_modificar_contador = $bus_consultar_asociado["idtipo_retencion"];
												}
											}else{
												$bus_existe_padre = mysql_fetch_array($sql_consultar_padre);
												$numero_contador = $bus_existe_padre["numero_retencion"];
												$retencion_a_modificar_contador = 0;
											}
										}else{
											//valido que alguno de los otros hijos asociados no se les haya asignado numero
												$sql_valido_hijos_asociados=mysql_query("select * from tipo_retencion
																		where asociado = '".$bus_consultar_tipo_retencion["asociado"]."'")or die(mysql_error());
																		
												while($bus_relacion_hijos_asociados = mysql_fetch_array($sql_valido_hijos_asociados)){
													$sql_asociados_hijos_retencion = mysql_query("select * from relacion_retenciones
																						where idretenciones = '".$bus_retencion["idretenciones"]."'
																							and idtipo_retencion = '".$bus_relacion_hijos_asociados["idtipo_retencion"]."'
																							and numero_retencion <> '0'")or die(mysql_error());
																							
													if (mysql_num_rows($sql_asociados_hijos_retencion)){
														$existe_hijo = mysql_num_rows($sql_asociados_hijos_retencion);
													}else{
														$existe_hijo = 0;
													}
													if ($existe_hijo > 0){
														$bus_asociado_hijo_con_numero = mysql_fetch_array($sql_asociados_hijos_retencion);
														$numero_contador = $bus_asociado_hijo_con_numero["numero_retencion"];
														$retencion_a_modificar_contador = 0;
														$existen=1;
													}
												
												}
												
												if ($existen == 0){
													//si es asociado, busco el tipo de documento padre para obtener el numero de comprobante a aplicarlo
													$sql_consultar_asociado = mysql_query("select * from tipo_retencion 
																									where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'")or die("aqui 1 ".mysql_error());
													$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);
													
													$numero_contador = $bus_consultar_asociado["numero_documento"];
													$retencion_a_modificar_contador = $bus_consultar_asociado["idtipo_retencion"];
												}
											
										}
								}
								//echo "cont ".$numero_contador;
								$numero_documento = $numero_contador;
								//valido si no existe un numero ya asignado para buscarlo y validarlo en los comprobantes y externas
								if ($retencion_a_modificar_contador != 0){
								
								
									$sql_validar_comprobante = mysql_query("select * from comprobantes_retenciones
																						where idorden_pago = '".$idorden_pago."'
																						and idtipo_retencion = '".$retencion_a_modificar_contador."'
																						and estado = 'procesado'");
									$bus_comprobantes = mysql_fetch_array($sql_validar_comprobante);
									$bus_tiene_comprobante = mysql_num_rows($sql_validar_comprobante);
												
									if ($bus_tiene_comprobante == 0){
											
										$numero_documento = $numero_contador;
										//busco que no exista ese numero de comprobante para ese tipo de retencion
										$sql_consultar_documento = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
										$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
										// si existen numero de comprobante para ese tipo de retencion entro en un ciclo para incrementar el numero
										// de comprobante hasta conseguir uno desocupado
										if($num_consultar_documento != 0){
											$acumulador=1;
											while($acumulador > 0){
												$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																										where idtipo_retencion = '".$retencion_a_modificar_contador."'");
												$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion 
																										where idtipo_retencion = '".$retencion_a_modificar_contador."'");
												$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
												$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
												$numero_documento = $numero_contador;
												$sql_consultar_documento = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".			
																			$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
												$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
												
												if($num_consultar_documento == 0){
													$sql_consultar_externa = mysql_query("select * from relacion_retenciones_externas where idtipo_retencion = '".			
																			$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
													$num_consultar_externa = mysql_num_rows($sql_consultar_externa);
													if($num_consultar_externa == 0){
														$acumulador = 0;
													}else{
														$acumulador++;
													}
												}else {
													$acumulador++;
												}		
											}
										}else{
											//valido que el numero no exista en los comprobantes externos
											$sql_consultar_externa = mysql_query("select * from relacion_retenciones_externas where idtipo_retencion = '".			
																			$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
											$num_consultar_externa = mysql_num_rows($sql_consultar_externa);
											if($num_consultar_externa != 0){
												$acumulador = 1;
												while($acumulador > 0){
													$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																											where idtipo_retencion = '".$retencion_a_modificar_contador."'");
													$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion 
																											where idtipo_retencion = '".$retencion_a_modificar_contador."'");
													$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
													$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
													$numero_documento = $numero_contador;
													$sql_consultar_documento = mysql_query("select * from relacion_retenciones_externas where idtipo_retencion = '".			
																				$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
													$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
													
													if($num_consultar_documento == 0){
														$sql_consultar_interna = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".			
																				$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
														$num_consultar_interna = mysql_num_rows($sql_consultar_interna);
														if($num_consultar_interna == 0){
															$acumulador = 0;
														}else{
															$acumulador++;
														}
													}else {
														$acumulador++;
													}		
												}
											}
										}
										//cierro el if para comprobar que el numero de comprobante no existe
								
										//echo date("Y-m-d");
										if ($acumulador==0){
										
											$fecha = explode("-", $fecha_aplicacion);
											$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																			numero_retencion = '".$numero_documento."' 
																			where idretenciones = '".$bus_retencion["idretenciones"]."' 
																			and idtipo_retencion = '".$retencion_a_modificar_original."'")or die(mysql_error());
									
											$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																						where idtipo_retencion = '".$retencion_a_modificar_contador."'")or die(mysql_error());
																						
																						
											$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
																(idorden_pago,
																idretenciones, 
																idtipo_retencion,
																numero_retencion,
																fecha_retencion,
																periodo,
																estado)VALUES('".$idorden_pago."',
																			'".$bus_retencion["idretenciones"]."',
																			'".$retencion_a_modificar_original."',
																			'".$numero_documento."',
																			'".$fecha_aplicacion."',
																			'".$fecha[0].$fecha[1]."',
																			'procesado')");
											
										}
									}else{
										// else que valida si ya tiene comprobante	
										//echo "ya tiene comprobante ".$bus_comprobantes["numero_retencion"];
										//echo $fecha_cheque;
										$fecha = explode("-", $fecha_aplicacion);
										$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
															(idorden_pago,
															idretenciones, 
															idtipo_retencion,
															numero_retencion,
															fecha_retencion,
															periodo,
															estado)VALUES('".$idorden_pago."',
																		'".$bus_retencion["idretenciones"]."',
																		'".$retencion_a_modificar_original."',
																		'".$bus_comprobantes["numero_retencion"]."',
																		'".$fecha_aplicacion."',
																		'".$fecha[0].$fecha[1]."',
																		'procesado')");
											//$fecha = explode("-", date("Y-m-d"));
											
											$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																				numero_retencion = '".$bus_comprobantes["numero_retencion"]."' 
																				where idretenciones = '".$bus_retencion["idretenciones"]."' 
																				and idtipo_retencion = '".$retencion_a_modificar_original."'")or die(mysql_error());
									}
								}else{
									$fecha = explode("-", $fecha_aplicacion);
									$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
															(idorden_pago,
															idretenciones, 
															idtipo_retencion,
															numero_retencion,
															fecha_retencion,
															periodo,
															estado)VALUES('".$idorden_pago."',
																		'".$bus_retencion["idretenciones"]."',
																		'".$retencion_a_modificar_original."',
																		'".$numero_documento."',
																		'".$fecha_aplicacion."',
																		'".$fecha[0].$fecha[1]."',
																		'procesado')");
									
									//$fecha = explode("-", date("Y-m-d"));
											
									$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																		numero_retencion = '".$numero_documento."' 
																		where idretenciones = '".$bus_retencion["idretenciones"]."' 
																		and idtipo_retencion = '".$retencion_a_modificar_original."'")or die(mysql_error());
								}
							}// cierro el ciclo con las retenciones aplicadas al documento	
						} //cierre retenciones del documento
					}
					echo "exito";	
				}	
	
			
			}

		
		}
	
		
		//************************************************ ACTUALIZAR NUMERO DE DOCUMENTO DE PAGO FINANCIERO
		
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
		$siglas = $bus_tipo_documento["siglas"];
		
		if($bus_tipo_documento["documento_asociado"] != 0){
			$sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_tipo_documento["documento_asociado"]."'");
			$bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
			$numero_actual = $bus_documento_asociado["nro_contador"];
			$id_a_modificar = $bus_documento_asociado["idtipos_documentos"];
		}else{
			$numero_actual =  $bus_tipo_documento["nro_contador"];
			$id_a_modificar = $bus_tipo_documento["idtipos_documentos"];
		}
		
		
		$numero_documento =  $siglas."-".$_SESSION["anio_fiscal"]."-".$numero_actual;
		
		$sql_consultar_existe = mysql_query("select * from pagos_financieros where numero_documento = '".$numero_documento."'");
		$num_consultar_existe = mysql_num_rows($sql_consultar_existe);
		
		while($num_consultar_existe > 0){
			$sql_actualizar_contador = mysql_query("update tipos_documentos set nro_contador = nro_contador+1 where idtipos_documentos = '".$id_a_modificar."'");
			$sql_seleccionar_numero = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$id_a_modificar."'");
			$bus_seleccionar_numero = mysql_fetch_array($sql_seleccionar_numero);
			$numero_actual = $bus_seleccionar_numero["nro_contador"];
			$numero_documento = $siglas."-".$_SESSION["anio_fiscal"]."-".$numero_actual;
			$sql_consultar_existe2 = mysql_query("select * from pagos_financieros where numero_documento = '".$numero_documento."'");
			$num_consultar_existe = mysql_num_rows($sql_consultar_existe2);
		}
		
		
		$codigo_referencia = 90000000000+$numero_actual;
		
		if ($formato_imprimir != "Cheque"){
			$sql_actualizar_pago_financiero = mysql_query("update pagos_financieros set numero_cheque = '".$numero_documento."' where idpagos_financieros = '".$ultimo_registro."'");
		}
		
		if($sql_actualizar_numero){
			$sql_actualizar_contador = mysql_query("update tipos_documentos set nro_contador = nro_contador+1 where idtipos_documentos = '".$id_a_modificar."'");
		}
		
		
		
		//*******************************************
		
		
		
		$sql_actualizar_pago_financiero = mysql_query("update pagos_financieros set numero_documento = '".$numero_documento."', codigo_referencia = '".$codigo_referencia."' where idpagos_financieros = '".$ultimo_registro."'");
		
		
		registra_transaccion("Registrar Emision de Pago (".$numero_documento.")",$login,$fh,$pc,'emision_pagos');
	}else{
		registra_transaccion("Registrar Emision de Pago ERROR ID OP (".$idorden_pago.")",$login,$fh,$pc,'emision_pagos');
		echo "fallo";
	}
	
	
	
	
	
	}//SI EL CHEQUE NO EXISTE
}








if($ejecutar == "cargarCuentasBancarias"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$banco."'");
	?>
	<select name="cuenta" id="cuenta">
    	<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
		
			$sql_consultar_chequera = mysql_query("select * from cheques_cuentas_bancarias where idcuentas_bancarias = '".$bus_consultar_cuentas["idcuentas_bancarias"]."' and estado = 'Activa' order by chequera_numero")or die(mysql_error());
			$bus_consultar_chequera = mysql_fetch_array($sql_consultar_chequera);

			$numero_cheque = $bus_consultar_chequera["ultimo_cheque"];
			$cheque_final = $bus_consultar_chequera["numero_final"];
			if($numero_cheque < 10){
				$numero_cheque = "0".$numero_cheque;
			}
			
			
			$can = strlen($numero_cheque);
			$tamanio_completo = 8-$bus_consultar_chequera["cantidad_digitos"];
			if($can < $tamanio_completo){
				$faltantes = $tamanio_completo - $can;
				for($i=0;$i<$faltantes; $i++){
					$ceros .= "0";
				}
				
				$numero_cheque = $ceros.$numero_cheque;
			} 
			
			?>
			<option onclick="<?
                            if($forma_preimpresa == "si"){
							?>
								document.getElementById('id_chequera').value = '<?=$bus_consultar_chequera["idcheques_cuentas_bancarias"]?>';
                                document.getElementById('chequera').value = '<?=$bus_consultar_chequera["chequera_numero"]?>';
                                
                                document.getElementById('nro_cheque').disabled = false;
                                document.getElementById('nro_cheque').focus();
                                document.getElementById('chequera').disabled = true;
                                cargarNumerosChequeras('<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>');
                                mostrar_cuenta_contable_cuenta_bancaria('<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>');
                                <?
                                if($bus_consultar_chequera["digitos_consecutivos"] == "inicio"){
									?>
									document.getElementById('nro_cheque').disabled = true;
                                    document.getElementById('divNumeroCheque').disabled = false;
                                    document.getElementById('nro_cheque').value = '<?=$numero_cheque?>';
                                    document.getElementById('numero_paralelo').value = '<?=$numero_cheque?>';
	                                document.getElementById('nro_cheque_oculto').value = '<?=$numero_cheque?>';
                                    document.getElementById('nro_mayor_cheque_oculto').value = '<?=$cheque_final?>';
                                    document.getElementById('divNumeroCheque').size = <?=$bus_consultar_chequera["cantidad_digitos"]?>;
									document.getElementById('nro_cheque').size = '<?=9-$bus_consultar_chequera["cantidad_digitos"]?>';
                                    document.getElementById('posicion_nro_cheque').value = 'inicio';
                                    document.getElementById('divNumeroCheque').setAttribute('maxlength', <?=$bus_consultar_chequera["cantidad_digitos"]?>);
									<?
								}else{
									?>
									document.getElementById('nro_cheque').disabled = false;
                                    document.getElementById('divNumeroCheque').disabled = true;
                                    document.getElementById('divNumeroCheque').value = '<?=$numero_cheque?>';
	                                document.getElementById('nro_cheque_oculto').value = '<?=$numero_cheque?>';
                                    document.getElementById('numero_paralelo').value = '<?=$numero_cheque?>';
                                    document.getElementById('nro_mayor_cheque_oculto').value = '<?=$cheque_final?>';
									document.getElementById('nro_cheque').size = '<?=$bus_consultar_chequera["cantidad_digitos"]?>';
									document.getElementById('divNumeroCheque').size = '<?=(8-$bus_consultar_chequera["cantidad_digitos"])?>';
                                    document.getElementById('nro_cheque').setAttribute('maxlength', '<?=$bus_consultar_chequera["cantidad_digitos"]?>');
                                    document.getElementById('cantidad_digitos').value = '<?=$bus_consultar_chequera["cantidad_digitos"]?>';
									document.getElementById('posicion_nro_cheque').value = 'final';
									<?
								}
								?>
							<?
							}else{
							?>
								document.getElementById('id_chequera').value = '<?=$bus_consultar_chequera["idcheques_cuentas_bancarias"]?>';
                                document.getElementById('nro_cheque').disabled = true;							
							<?
							}
							?>
                            "			
            		value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
			<?
		}
		?>
    </select>  
	<?
}





if($ejecutar == "anular"){
	$sql = mysql_query("select * from usuarios where login = '".$login."' and clave = '".md5($clave)."'");
$num = mysql_num_rows($sql);
if($num > 0){
	
	$sql_configuracion = mysql_query("select fecha_cierre from configuracion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	if(date("Y-m-d") > $bus_configuracion["fecha_cierre"]){
		$fecha_anulacion = $bus_configuracion["fecha_cierre"];
	}else{
		$fecha_anulacion = $fecha_anulacion_pago;
	}
	
	$sql_anular = mysql_query("update pagos_financieros 
												set estado = 'anulado', 
													fecha_anulacion = '".$fecha_anulacion."',
													monto_cheque = 0
												where idpagos_financieros = '".$id_emision_pago."'");
	$sql_consultar_orden = mysql_query("select * from pagos_financieros where idpagos_financieros = '".$id_emision_pago."'");
	$bus_consultar_orden = mysql_fetch_array($sql_consultar_orden);
	if($bus_consultar_orden["idorden_pago"] != 0){
		$idorden_pago = $bus_consultar_orden["idorden_pago"];
		$sql_cambiar_orden_pago = mysql_query("update orden_pago set estado = 'procesado' where idorden_pago = '".$bus_consultar_orden["idorden_pago"]."'");
		
		$sql= mysql_query("select * from configuracion_tributos");
		$bus = mysql_fetch_array($sql);
		$genera_comprobante = $bus["genera_comprobante"];
		if ($genera_comprobante == 'si'){
			$sql_actualizar_comprobantes = mysql_query("update comprobantes_retenciones set estado='anulado' where idorden_pago = '".$bus_consultar_orden["idorden_pago"]."' 
														and estado != 'anulado'")or die(mysql_error());
			$sql_anular = mysql_query("update comprobantes_retenciones set estado = 'anulado' where idorden_pago = '".$idorden_pago."' and estado != 'anulado'");

			$sql_consultar_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$idorden_pago."'")or die(mysql_error());
			while($bus_consultar_relacion_retenciones = mysql_fetch_array($sql_consultar_relacion_retenciones)){
				$actualiza_retencion = mysql_query("update retenciones set fecha_aplicacion_retencion = '0000-00-00'
															where idretenciones = '".$bus_consultar_relacion_retenciones["idretencion"]."'")or die("error actualizando fecha de aplicacion retenecion".mysql_error());

				$sql_retenciones = mysql_query("select * from retenciones where idretenciones = '".$bus_consultar_relacion_retenciones["idretencion"]."'")or die(mysql_error());
				while($bus_retenciones = mysql_fetch_array($sql_retenciones)){
					$sql_actualizar_retencion = mysql_query("update relacion_retenciones set numero_retencion = 0, 
																	periodo = ''
																	where idretenciones = '".$bus_retenciones["idretenciones"]."'")or die("error actualizando numero retenecion".mysql_error());
				}
			}
		}
	}
	if($sql_anular){
		//*******************************************************************************************************************
		//	REGISTRO DEL ASIENTO CONTABLE
		$sql_actualizar =mysql_query("update asiento_contable set reversado = 'si'
														where iddocumento = ".$id_emision_pago."
															and tipo_movimiento = 'emision_pagos'");

		$sql_cuentas_asiento = mysql_query("select * from asiento_contable, cuentas_asiento_contable 
														where asiento_contable.iddocumento = ".$id_emision_pago."
														AND asiento_contable.tipo_movimiento = 'emision_pagos'
														AND cuentas_asiento_contable.idasiento_contable =asiento_contable.idasiento_contable");

		$sql_orden = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
		$reg_orden = mysql_fetch_array($sql_orden);
		$mes_contable = explode("-",$fecha_anulacion);
		$sql_registrar_asiento_contable = mysql_query("insert into asiento_contable(idfuente_financiamiento,
																				fecha_contable,
																				mes_contable,
																				detalle,
																				tipo_movimiento,
																				iddocumento,
																				estado,
																				usuario,
																				fechayhora,
																				prioridad)
													values('".$reg_orden["idfuente_financiamiento"]."',
															'".$fecha_anulacion."',
															'".$mes_contable[1]."',
												'ANULACION DE PAGO DE: ".$reg_orden["justificacion"]." Documento Nro.".$numero_cheque."',
															'emision_pagos',
															'".$id_emision_pago."',
															'anulado',
															'".$login."',
															'".$fh."',
															'4')")or die(mysql_error());

		$idasiento_contable = mysql_insert_id();

		$sql_registrar_ingresos = mysql_query("update pagos_financieros set idasiento_contable = '".$idasiento_contable."'
																where idpagos_financieros = '".$id_emision_pago."'")or die(mysql_error());

		while ($cuentas_asiento = mysql_fetch_array($sql_cuentas_asiento)){
			if($cuentas_asiento['afecta'] == 'debe'){
				$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																							'".$cuentas_asiento['tabla']."',
																							'".$cuentas_asiento['idcuenta']."',
																							'".$cuentas_asiento['monto']."',
																							'haber')")or die(mysql_error());
			}else{
				$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																							'".$cuentas_asiento['tabla']."',
																							'".$cuentas_asiento['idcuenta']."',
																							'".$cuentas_asiento['monto']."',
																							'debe')")or die(mysql_error());
			}
		}
		echo "exito";
		registra_transaccion("Anular Emision de Pago (".$id_emision_pago.")",$login,$fh,$pc,'emision_pagos');
	}else{
		echo "fallo";
		registra_transaccion("Anular orden Pago ERROR (".$id_orden_pago.")",$login,$fh,$pc,'orden_pago');
	}
	}else{
	registra_transaccion("Error al Anular Cheque (Clave Incorrecta: ".$clave.", Cheque: ".$id_emision_pago.")",$login,$fh,$pc,'pago_financiero');
		echo "claveIncorrecta";
	}
}







if($ejecutar == "buscarOrdenes"){
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
            <thead>
                <tr>
                    <td align="center" class="Browse">Nro. Orden</td>
                    <td align="center" class="Browse">Fecha de la Orden</td>
                    <td align="center" class="Browse">Beneficiario</td>
                    <td align="center" class="Browse">Total A Pagar</td>
                    <td align="center" class="Browse">Seleccionar</td>
                </tr>
            </thead>
                <?
                $sql_ordenes_pago = mysql_query("select * from orden_pago where (estado = 'procesado' or estado = 'parcial') and numero_orden like '%".$campoBuscar."%'");
                while($bus_ordenes_pago = mysql_fetch_array($sql_ordenes_pago)){
                ?>
                    <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td align='left' class='Browse'><?=$bus_ordenes_pago["numero_orden"]?></td>
                        <td align='left' class='Browse'><?=$bus_ordenes_pago["fecha_orden"]?></td>
                        <td align='left' class='Browse'>
                        <?
                        $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_ordenes_pago["idbeneficiarios"]."'");
                        $bus_beneficiario = mysql_fetch_array($sql_beneficiario);
                        echo $bus_beneficiario["nombre"];
                        ?>
                      </td>
                        <td align='right' class='Browse'><?=number_format($bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"],2,",",".")?></td>
                        <td align='center' class='Browse' width='7%'>
						<?
                        $sql_monto_restante = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_ordenes_pago["idorden_pago"]."' and estado = 'transito'");
                        $suma_montos = 0;
                        while($bus_monto_restante = mysql_fetch_array($sql_monto_restante)){
                            $suma_montos += $bus_monto_restante["monto_cheque"];
                        }
                        
                        $monto_restante = ($bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"])-$suma_montos;
                        //echo "MONTO RESTANTE: ".$suma_montos;
                        ?>
                        
                        <img src='imagenes/validar.png' 
                            border='0' 
                            alt='Seleccionar' 
                            title='Seleccionar' 
                            onclick="document.getElementById('monto_restante').value = '<?=$monto_restante?>',
                            document.getElementById('monto_restante_mostrado').value = '<?=number_format($monto_restante,2,",",".")?>',
                            document.getElementById('beneficiario').disabled = true,
                            document.getElementById('ci_beneficiario').disabled = true,
                            document.getElementById('monto_cheque').disabled = true,
                            document.getElementById('numero_orden_pago').disabled = true,
                            document.getElementById('monto_orden_pago').value = '<?=number_format($bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"],2,",",".")?>',
                            document.getElementById('monto_cheque').value = '<?=number_format($monto_restante,2,",",".")?>',
                            document.getElementById('monto_orden_pago_oculto').value = '<?=$bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"]?>',
                            document.getElementById('monto_cheque_oculto').value = '<?=$monto_restante?>',
                            document.getElementById('monto_orden_pago').disabled = true,
                            document.getElementById('numero_orden_pago').value = '<?=$bus_ordenes_pago["numero_orden"]?>',
                            document.getElementById('id_orden_pago').value = '<?=$bus_ordenes_pago["idorden_pago"]?>',
                            document.getElementById('beneficiario').value = '<?=$bus_beneficiario["nombre"]?>'
                            document.getElementById('id_beneficiario').value = '<?=$bus_beneficiario["idbeneficiarios"]?>',
                            document.getElementById('ci_beneficiario').value = '<?=$bus_beneficiario["rif"]?>',
                            document.getElementById('banco').disabled = false,
                            document.getElementById('cuenta').disabled = false,
                            document.getElementById('forma_pago').disabled = false,
                            document.getElementById('formato_imprimir').disabled = false,
                            document.getElementById('forma_pago').focus(),
                            document.getElementById('tipo_movimiento').disabled = false,
                            document.getElementById('celda_valor_debe').innerHTML = '<?=number_format($monto_restante,2,",",".")?>',
                            document.getElementById('celda_valor_haber').innerHTML = '<?=number_format($monto_restante,2,",",".")?>',
                            document.getElementById('valor_debe').value = '<?=$monto_restante?>',
                            document.getElementById('valor_haber').value = '<?=$monto_restante?>'
                            " 
                    		style="cursor:pointer">
                        </td>						
                  </tr>
                 <?
                 }
                 ?>
        </table>
	<?
}





if($ejecutar == "cargarNumerosChequeras"){
	$sql_consulta = mysql_query("select * from cheques_cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."' and estado = 'Activa'");
	
	
	?>
	<select name="chequera" id="chequera">
    <?
    while($bus_consulta = mysql_fetch_array($sql_consulta)){
		
		$numero_cheque = $bus_consulta["ultimo_cheque"];
			$cheque_final = $bus_consulta["numero_final"];
			if($numero_cheque < 10){
				$numero_cheque = "0".$numero_cheque;
			}
			
			
			$can = strlen($numero_cheque);
			$tamanio_completo = 8-$bus_consulta["cantidad_digitos"];
			if($can < $tamanio_completo){
				$faltantes = $tamanio_completo - $can;
				for($i=0;$i<$faltantes; $i++){
					$ceros .= "0";
				}
				
				$numero_cheque = $ceros.$numero_cheque;
			} 
		
		?>
		<option value="<?=$bus_consulta["idcheques_cuentas_bancarias"]?>"
        onclick="<? if($bus_consulta["digitos_consecutivos"] == "inicio"){
						?>
						document.getElementById('nro_cheque').disabled = true;
						document.getElementById('divNumeroCheque').disabled = false;
						document.getElementById('nro_cheque').value = '<?=$numero_cheque?>';
						document.getElementById('numero_paralelo').value = '<?=$numero_cheque?>';
						document.getElementById('nro_cheque_oculto').value = '<?=$numero_cheque?>';
						document.getElementById('nro_mayor_cheque_oculto').value = '<?=$cheque_final?>';
						document.getElementById('divNumeroCheque').size = '<?=$bus_consulta["cantidad_digitos"]?>';
						document.getElementById('nro_cheque').size = '<?=8-$bus_consulta["cantidad_digitos"]?>';
						document.getElementById('posicion_nro_cheque').value = 'inicio';
						document.getElementById('divNumeroCheque').setAttribute('maxlength', '<?=$bus_consulta["cantidad_digitos"]?>');
                        document.getElementById('divNumeroCheque').value = '';
						<?
					}else{
						?>
						document.getElementById('nro_cheque').disabled = false;
						document.getElementById('divNumeroCheque').disabled = true;
						document.getElementById('divNumeroCheque').value = '<?=$numero_cheque?>';
						document.getElementById('nro_cheque_oculto').value = '<?=$numero_cheque?>';
						document.getElementById('numero_paralelo').value = '<?=$numero_cheque?>';
						document.getElementById('nro_mayor_cheque_oculto').value = '<?=$cheque_final?>';
						document.getElementById('nro_cheque').size = '<?=$bus_consulta["cantidad_digitos"]?>';
						document.getElementById('divNumeroCheque').size = '<?=(8-$bus_consulta["cantidad_digitos"])?>';
						document.getElementById('nro_cheque').setAttribute('maxlength', '<?=$bus_consulta["cantidad_digitos"]?>');
						document.getElementById('cantidad_digitos').value = '<?=$bus_consultar_chequera["cantidad_digitos"]?>';
						document.getElementById('posicion_nro_cheque').value = 'final';
                        document.getElementById('nro_cheque').value = '';
						<?
					}
					?>
					document.getElementById('id_chequera').value='<?=$bus_consulta["idcheques_cuentas_bancarias"]?>'">
					<?=$bus_consulta["chequera_numero"]?></option>

		<?
	
	}
	?>
    </select>
	<?
}




if($ejecutar == "buscarNomina"){
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
            <thead>
                <tr>
                    <td align="center" class="Browse">C&eacute;dula</td>
                    <td align="center" class="Browse">Apellidos y Nombres</td>
                    <td align="center" class="Browse">Monto a Pagar</td>
                    <td align="center" class="Browse">N&uacute;mero de Cheque</td>
                    <td align="center" class="Browse">Fecha del Cheque</td>
                    <td colspan="3" align="center" class="Browse">Acci&oacute;n</td>
                </tr>
            </thead>
                <?
				//BUSCO EL COMPROMISO RELACIONADO CON LA ORDEN DE PAGO 
                $sql_compromiso = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$idorden_pago."'");
				$bus_compromiso = mysql_fetch_array($sql_compromiso);
				
				// BUSCO LA NOMINA QUE GENERO LA CERTIFICACION DE COMPROMISO
				$sql_generar = mysql_query("select * from generar_nomina where idorden_compra_servicio = '".$bus_compromiso["idorden_compra_servicio"]."'");
				$bus_generar = mysql_fetch_array($sql_generar);
				
				//	---------------------------------------
				// BUSCO LOS TRABAJADORES ASIGNADOS EN ESA NOMINA
				$sql = "SELECT
						rgn.idtrabajador,
						t.cedula,
						t.apellidos,
						t.nombres
					FROM
						relacion_generar_nomina rgn
						LEFT JOIN trabajador t ON (rgn.idtrabajador = t.idtrabajador)						
					WHERE
						rgn.idgenerar_nomina = '".$bus_generar['idgenerar_nomina']."'
					GROUP BY rgn.idtrabajador
					ORDER BY apellidos, nombres, length(cedula), cedula";
				$query_trabajador = mysql_query($sql) or die ($sql.mysql_error());
				
				
				while ($field_trabajador = mysql_fetch_array($query_trabajador)) {
				$sql = "SELECT
							SUM(rgn.total) AS total_conceptos,
							(SELECT SUM(rgn.total)
							 FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'asignacion')
							 WHERE
								rgn.total > 0 AND
								rgn.idgenerar_nomina = '".$bus_generar['idgenerar_nomina']."' AND
								rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
								rgn.tabla = 'constantes_nomina') AS total_constantes
						FROM
							relacion_generar_nomina rgn
							INNER JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'asignacion')
						WHERE
							rgn.total > 0 AND
							rgn.idgenerar_nomina = '".$bus_generar['idgenerar_nomina']."' AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idtrabajador";
						
					$query_asignaciones = mysql_query($sql) or die ($sql.mysql_error());
					$field_asignaciones = mysql_fetch_array($query_asignaciones);
							
					$sql_concepto = "SELECT
							SUM(rgn.total) AS total_conceptos
						FROM
							relacion_generar_nomina rgn
							LEFT JOIN conceptos_nomina cn ON (rgn.idconcepto = cn.idconceptos_nomina)
							INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina AND afecta = 'Deduccion')
						WHERE
							total > 0 AND
							rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
							rgn.idgenerar_nomina = '".$bus_generar['idgenerar_nomina']."' AND
							rgn.tabla = 'conceptos_nomina'
						GROUP BY rgn.idtrabajador";
					$sql_constante = "SELECT SUM(rgn.total) AS total_constantes
							 FROM
								relacion_generar_nomina rgn
								INNER JOIN constantes_nomina cn ON (rgn.idconcepto = cn.idconstantes_nomina)
								INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina AND tcn.afecta = 'Deduccion')
							 WHERE
								total > 0 AND
								rgn.idtrabajador = '".$field_trabajador['idtrabajador']."' AND
								rgn.idgenerar_nomina = '".$bus_generar['idgenerar_nomina']."' AND
								rgn.tabla = 'constantes_nomina' 
						GROUP BY rgn.idtrabajador";
					//if ($field_trabajador['idtrabajador']==17){ echo $sql;}
					$query_deducciones_concepto = mysql_query($sql_concepto) or die ($sql_concepto.mysql_error());
					$field_deducciones_concepto = mysql_fetch_array($query_deducciones_concepto);
					
					$query_deducciones_constante = mysql_query($sql_constante) or die ($sql_constante.mysql_error());
					$field_deducciones_constante = mysql_fetch_array($query_deducciones_constante);
	
									
					$total_asignaciones = $field_asignaciones['total_conceptos'] + $field_asignaciones['total_constantes'];
					$total_deducciones = $field_deducciones_concepto['total_conceptos'] + $field_deducciones_constante['total_constantes'];
					$total = $total_asignaciones - $total_deducciones;
					
                    $sql_cheque_trabajador = mysql_query("select * from pagos_financieros where idorden_pago = '".$idorden_pago."' and idtrabajador = '".$field_trabajador["idtrabajador"]."' and estado <> 'anulado'") or die(mysql_error());
					$field_cheque_trabajador = mysql_fetch_array($sql_cheque_trabajador);
                        
                        
					?>
                    <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="height:30px">
                     <input type="hidden" id="id_emision_pago<?=$field_trabajador["idtrabajador"]?>" name="id_emision_pago<?=$field_trabajador["idtrabajador"]?>" size="250" maxlength="250" disabled="disabled" value="<?=$field_cheque_trabajador["idpagos_financieros"]?>" />
                     
                        <td align='right' class='Browse'><?=number_format($field_trabajador["cedula"],0,",",".")?></td>
                         <input type="hidden" id="cedula_individual<?=$field_trabajador["idtrabajador"]?>" name="cedula_individual<?=$field_trabajador["idtrabajador"]?>" size="250" maxlength="250" disabled="disabled" value="<?=$field_trabajador["cedula"]?>" />
                         
                        <td align='left' class='Browse' style="font-size:12px;"><?=$field_trabajador["apellidos"]?>,&nbsp;<?=$field_trabajador["nombres"]?></td>						
                        <input type="hidden" id="beneficiario_individual<?=$field_trabajador["idtrabajador"]?>" name="beneficiario_individual<?=$field_trabajador["idtrabajador"]?>" size="250" maxlength="250" disabled="disabled" value="<?=$field_trabajador["apellidos"]?> <?=$field_trabajador["nombres"]?>" />
                		                        
                        <td align='right' class='Browse' style="font-size:14px;"><?=number_format($total,2,",",".")?></td>
                        <input type="hidden" id="monto_cheque_individual<?=$field_trabajador["idtrabajador"]?>" name="monto_cheque_individual<?=$field_trabajador["idtrabajador"]?>" size="250" maxlength="250" disabled="disabled" value="<?=$total?>"/>
                        
                        
                        <td align='center' class='Browse' width='16%'>
                        	<input name="numero_cheque<?=$field_trabajador["idtrabajador"]?>" type="text" id="numero_cheque<?=$field_trabajador["idtrabajador"]?>" size="14" maxlength="10" value="<?=$field_cheque_trabajador["numero_cheque"]?>">
               			</td>
                        
                         <td align='center' class='Browse' width='15%'>
                        	<input name="fecha_cheque_individual<?=$field_trabajador["idtrabajador"]?>" type="text" disabled id="fecha_cheque_individual<?=$field_trabajador["idtrabajador"]?>" size="12" maxlength="12" value="<?=date("Y-m-d")?>">
                            
                            <img src="imagenes/jscalendar0.gif" name="f_trigger_f<?=$field_trabajador["idtrabajador"]?>" 
                									width="16" height="16" 
													id="f_trigger_f<?=$field_trabajador["idtrabajador"]?>" 
													style="cursor: pointer;" 
													title="Selector de Fecha" 
													onMouseOver="this.style.background='red';" 
													onMouseOut="this.style.background=''" onClick="
									Calendar.setup({
									inputField    : 'fecha_cheque_individual<?=$field_trabajador["idtrabajador"]?>',
									button        : 'f_trigger_f<?=$field_trabajador["idtrabajador"]?>',
									align         : 'Tr',
									ifFormat      : '%Y-%m-%d'
									});"/>  
               			</td>
                        
                		<td class="Browse" align="center" width='1%'>
                        	<?
							if ($field_cheque_trabajador["numero_cheque"] == ''){
							?>
				            	<img src="imagenes/refrescar.png" title="Procesar Cheque" style="cursor:pointer; border-style:none; display:block" id='generar_cheque_nomina<?=$field_trabajador["idtrabajador"]?>' onClick="generar_cheque_nominaa('<?=$field_trabajador["idtrabajador"]?>')">		<? 
							}else{ ?>
                            	<img src="imagenes/refrescar.png" title="Procesar Cheque" style="cursor:pointer; border-style:none; display:none" id='generar_cheque_nomina<?=$field_trabajador["idtrabajador"]?>' onClick="generar_cheque_nominaa('<?=$field_trabajador["idtrabajador"]?>')">
                            <? 
							} ?>
                        </td>
                        <td class="Browse" align="center" width='1%'>
                        	<?
							if ($field_cheque_trabajador["numero_cheque"] != ''){
							?>
                            	<img src="imagenes/imprimir.png" title="Imprimir Cheque" id='imprimir_cheque_nomina<?=$field_trabajador["idtrabajador"]?>' style="cursor:pointer; border-style:none; display:block" onClick="imprimir_cheque_nominaa('<?=$field_trabajador["idtrabajador"]?>',event)">
                            <? 
							}else{ ?>
                            	<img src="imagenes/imprimir.png" title="Imprimir Cheque" id='imprimir_cheque_nomina<?=$field_trabajador["idtrabajador"]?>' style="cursor:pointer; border-style:none; display:none" onClick="imprimir_cheque_nominaa('<?=$field_trabajador["idtrabajador"]?>')">
                            <? 
							} ?>
                            
                        </td>
                        <td class="Browse" align="center" width='1%'>
                        	<?
							if ($field_cheque_trabajador["numero_cheque"] != '' and $field_cheque_trabajador["estado"] != 'conciliado'){
							?>
                        		<img src="imagenes/delete.png" title="Anular Cheque" id='anular_cheque_nomina<?=$field_trabajador["idtrabajador"]?>' style="cursor:pointer; border-style:none; display:block" onClick="anular_cheque_nominaa('<?=$field_trabajador["idtrabajador"]?>')">
                           <? 
							}else{ ?>
                            	<img src="imagenes/delete.png" title="Anular Cheque" id='anular_cheque_nomina<?=$field_trabajador["idtrabajador"]?>' style="cursor:pointer; border-style:none; display:none" onClick="anular_cheque_nominaa('<?=$field_trabajador["idtrabajador"]?>')">
                     		<? 
							} ?>       
                        </td>						
                  </tr>
                 <?
				
				
			
				}
				
            
                 ?>
        </table>
	<?
}



if($ejecutar == "generar_cheque_nomina"){
	
	if ($formato_imprimir == "Cheque"){
		$sql_consulta_pagos = mysql_query("select * from pagos_financieros where numero_cheque like '%".$numero_cheque."' and idcheques_cuentas_bancarias = '".$idcheques_cuentas_bancarias."'");
		$num_consulta_pagos = mysql_num_rows($sql_consulta_pagos);
		//echo "NUM: ".$num_consulta_pagos;
		if($num_consulta_pagos > 0){
			$entro="existe";
		}
	}
	if ($entro == "existe"){
		echo "existe";
	}else{
		$porcentaje_monto = 0;
	
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
															fechayhora,
															idtrabajador)values('".$idtipo_documento."',
																				'".$forma_pago."',
																				'".$modo_cancelacion."',
																				'".$porcentaje_monto."',
																				'".$numero_parte_pago."',
																				'".$idtipo_movimiento_bancario."',
																				'".$idorden_pago."',
																				'".$idcuenta_bancaria."',
																				'".$idcheques_cuentas_bancarias."',
																				'".$numero_cheque."',
																				'".$fecha_cheque."',
																				'".$monto_cheque."',
																				'".utf8_encode($beneficiario)."',
																				'".$ci_beneficiario."',
																				'".$formato_imprimir."',
																				'transito',
																				'a',
																				'".$login."',
																				'".$fh."',
																				'".$idtrabajador."')")or die(mysql_error());
	if($sql_ingresar_emision_pagos){
		$ultimo_registro = mysql_insert_id();
		echo "exito|.|".mysql_insert_id();
		
		
		// ACTUALIZAR EL MAESTRO DE PRESUPUESTO
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
			if($bus_tipo_documento["paga"] == "si"){
				if($idorden_pago != ""){
					$sql_partidas_orden_pago = mysql_query("select * from partidas_orden_pago where idorden_pago = '".$idorden_pago."'");
					while($bus_partidas_orden_pago = mysql_fetch_array($sql_partidas_orden_pago)){
						$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
												set total_pagados = total_pagados+".$monto_cheque." 
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
																set total_pagados = total_pagados+".$monto_cheque."
																where idRegistro = '".$bus_sub_epe["idRegistro"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE 
																PRESUPUESTO: ".mysql_error());
								
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
								
								$sql_maestro = mysql_query("update maestro_presupuesto set total_pagados = total_pagados+".$monto_cheque."
																where idRegistro = '".$bus_id_maestro["idRegistro"]."'")or die("ERROR ACTUALIZANDO EL MAESTRO DE PRESUPUESTO: ".mysql_error());
								
							}
							
						}
					}
				}
			}
		// ACTUALIZAR EL MAESTRO DE PRESUPUESTO
		
	
		if($idorden_pago != 0){
			$sql_contar_totales = mysql_query("select SUM(monto_cheque) as total from pagos_financieros where idorden_pago = '".$idorden_pago."'  and estado <> 'anulado'");
			$bus_contar_totales = mysql_fetch_array($sql_contar_totales);
			$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
			$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
			$total_orden_pago = $bus_orden_pago["total"] - $bus_orden_pago["total_retenido"];
			if($bus_contar_totales["total"]  < $total_orden_pago){
				$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'parcial' where idorden_pago = '".$idorden_pago."'");	
			}else{
				$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'pagada' where idorden_pago = '".$idorden_pago."'");
			}

		}
		
		
		
		//*******************************************************************************************************************
		//	REGISTRO DEL ASIENTO CONTABLE
		
		$sql_orden = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
		$reg_orden = mysql_fetch_array($sql_orden);
		$mes_contable = explode("-",$fecha_cheque);
		$sql_registrar_asiento_contable = mysql_query("insert into asiento_contable(idfuente_financiamiento,
																				fecha_contable,
																				mes_contable,
																				detalle,
																				tipo_movimiento,
																				iddocumento,
																				estado,
																				usuario,
																				fechayhora,
																				prioridad)
													values('".$reg_orden["idfuente_financiamiento"]."',
															'".$fecha_cheque."',
															'".$mes_contable[1]."',
															'PAGO DE: ".$reg_orden["justificacion"]." Documento Nro.".$numero_cheque."',
															'emision_pagos',
															'".$ultimo_registro."',
															'procesado',
															'".$login."',
															'".$fh."',
															'4')")or die(mysql_error());
																									
		$idasiento_contable = mysql_insert_id();
		
		$sql_registrar_ingresos = mysql_query("update pagos_financieros set idasiento_contable = '".$idasiento_contable."'
																where idpagos_financieros = '".$ultimo_registro."'")or die(mysql_error());
		
		$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																									'".$tabla_debe."',
																									'".$idcuenta_debe."',
																									'".$monto_cheque."',
																									'debe')")or die(mysql_error());
		$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																									'".$tabla_haber."',
																									'".$idcuenta_haber."',
																									'".$monto_cheque."',
																									'haber')")or die(mysql_error());
		
		
		
		
		
		
		
		
		
		
		
		//************************************************ ACTUALIZAR NUMERO DE DOCUMENTO DE PAGO FINANCIERO
		
		$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipo_documento."'");
		$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
		$siglas = $bus_tipo_documento["siglas"];
		
		if($bus_tipo_documento["documento_asociado"] != 0){
			$sql_documento_asociado = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_tipo_documento["documento_asociado"]."'");
			$bus_documento_asociado = mysql_fetch_array($sql_documento_asociado);
			$numero_actual = $bus_documento_asociado["nro_contador"];
			$id_a_modificar = $bus_documento_asociado["idtipos_documentos"];
		}else{
			$numero_actual =  $bus_tipo_documento["nro_contador"];
			$id_a_modificar = $bus_tipo_documento["idtipos_documentos"];
		}
		
		
		$numero_documento =  $siglas."-".$_SESSION["anio_fiscal"]."-".$numero_actual;
		
		$sql_consultar_existe = mysql_query("select * from pagos_financieros where numero_documento = '".$numero_documento."'");
		$num_consultar_existe = mysql_num_rows($sql_consultar_existe);
		
		while($num_consultar_existe > 0){
			$sql_actualizar_contador = mysql_query("update tipos_documentos set nro_contador = nro_contador+1 where idtipos_documentos = '".$id_a_modificar."'");
			$sql_seleccionar_numero = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$id_a_modificar."'");
			$bus_seleccionar_numero = mysql_fetch_array($sql_seleccionar_numero);
			$numero_actual = $bus_seleccionar_numero["nro_contador"];
			$numero_documento = $siglas."-".$_SESSION["anio_fiscal"]."-".$numero_actual;
			$sql_consultar_existe2 = mysql_query("select * from pagos_financieros where numero_documento = '".$numero_documento."'");
			$num_consultar_existe = mysql_num_rows($sql_consultar_existe2);
		}
		
		
		$codigo_referencia = 90000000000+$numero_actual;
		
		if ($formato_imprimir != "Cheque"){
			$sql_actualizar_pago_financiero = mysql_query("update pagos_financieros set numero_cheque = '".$numero_documento."' where idpagos_financieros = '".$ultimo_registro."'");
		}
		
		if($sql_actualizar_numero){
			$sql_actualizar_contador = mysql_query("update tipos_documentos set nro_contador = nro_contador+1 where idtipos_documentos = '".$id_a_modificar."'");
		}
		
		
		
		//*******************************************
		
		
		
			$sql_actualizar_pago_financiero = mysql_query("update pagos_financieros set numero_documento = '".$numero_documento."', codigo_referencia = '".$codigo_referencia."' where idpagos_financieros = '".$ultimo_registro."'");
			
			
			registra_transaccion("Registrar Emision de Pago (".$numero_documento.")",$login,$fh,$pc,'emision_pagos');
		}else{
			registra_transaccion("Registrar Emision de Pago ERROR ID OP (".$idorden_pago.")",$login,$fh,$pc,'emision_pagos');
			echo "fallo";
		}
	
	}//SI EL CHEQUE NO EXISTE
}


if($ejecutar == "anular_cheque_nominaa"){
	
	$sql_configuracion = mysql_query("select fecha_cierre from configuracion");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	if(date("Y-m-d") > $bus_configuracion["fecha_cierre"]){
		$fecha_anulacion = $bus_configuracion["fecha_cierre"];
	}else{
		$fecha_anulacion = date("Y-m-d");
	}
	
	$sql_anular = mysql_query("update pagos_financieros 
												set estado = 'anulado', 
													fecha_anulacion = '".$fecha_anulacion."' 
												where idpagos_financieros = '".$id_emision_pago."'");
	$sql_consultar_orden = mysql_query("select * from pagos_financieros where idpagos_financieros = '".$id_emision_pago."'");
	$bus_consultar_orden = mysql_fetch_array($sql_consultar_orden);
	if($bus_consultar_orden["idorden_pago"] != 0){
		$idorden_pago = $bus_consultar_orden["idorden_pago"];
		$sql_contar_totales = mysql_query("select SUM(monto_cheque) as total from pagos_financieros where idorden_pago = '".$idorden_pago."' and estado <> 'anulado'");
		$bus_contar_totales = mysql_fetch_array($sql_contar_totales);
		$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
		$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
		$total_orden_pago = $bus_orden_pago["total"] - $bus_orden_pago["total_retenido"];
		if($bus_contar_totales["total"]  == 0){
			$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'procesado' where idorden_pago = '".$idorden_pago."'");	
		}
		if($bus_contar_totales["total"] > 0 and $bus_contar_totales["total"] < $total_orden_pago){
			$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'parcial' where idorden_pago = '".$idorden_pago."'");	
		}else{
			$sql_actualizar_estado_orden = mysql_query("update orden_pago set estado = 'pagada' where idorden_pago = '".$idorden_pago."'");
		}
		
	}
	if($sql_anular){
		
		//*******************************************************************************************************************
		//	REGISTRO DEL ASIENTO CONTABLE
		
		$sql_orden = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
		$reg_orden = mysql_fetch_array($sql_orden);
		$mes_contable = explode("-",$fecha_anulacion);
		$sql_registrar_asiento_contable = mysql_query("insert into asiento_contable(idfuente_financiamiento,
																				fecha_contable,
																				mes_contable,
																				detalle,
																				tipo_movimiento,
																				iddocumento,
																				estado,
																				usuario,
																				fechayhora,
																				prioridad)
													values('".$reg_orden["idfuente_financiamiento"]."',
															'".$fecha_anulacion."',
															'".$mes_contable[1]."',
												'ANULACION DE PAGO DE: ".$reg_orden["justificacion"]." Documento Nro.".$numero_cheque."',
															'emision_pagos',
															'".$id_emision_pago."',
															'anulado',
															'".$login."',
															'".$fh."',
															'4')")or die(mysql_error());
																									
		$idasiento_contable = mysql_insert_id();
		
		$sql_registrar_ingresos = mysql_query("update pagos_financieros set idasiento_contable = '".$idasiento_contable."'
																where idpagos_financieros = '".$ultimo_registro."'")or die(mysql_error());
		
		$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																									'".$tabla_debe."',
																									'".$idcuenta_debe."',
																									'".$monto_cheque."',
																									'haber')")or die(mysql_error());
		$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																									'".$tabla_haber."',
																									'".$idcuenta_haber."',
																									'".$monto_cheque."',
																									'debe')")or die(mysql_error());
		
		
		
		echo "exito";
		registra_transaccion("Anular cheque de Pago (".$id_emision_pago.")",$login,$fh,$pc,'emision_pagos');
	}else{
		echo "fallo";
		registra_transaccion("Anular cheque ERROR (".$id_orden_pago.")",$login,$fh,$pc,'orden_pago');
	}	
	
}

if($ejecutar == "mostrar_cuenta_contable_tipo_movimiento"){
	$sql_consultar_cuenta_contable = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipos_documentos."'");
	$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
	if ($bus_consultar_cuenta_contable["tabla_debe"]=='0'){
		$denominacion_debe = '&nbsp;';
		$idcuenta_debe = '0';
		$tabla_debe = '0';
	}else{
		$sql_debe ="select * from ".$bus_consultar_cuenta_contable["tabla_debe"]." 
						where id".$bus_consultar_cuenta_contable["tabla_debe"]." = '".$bus_consultar_cuenta_contable["idcuenta_debe"]."'";
		$query_debe = mysql_query($sql_debe);							
		$bus_debe = mysql_fetch_array($query_debe)or die(mysql_error());
		$denominacion_debe = $bus_debe["codigo"].'-'.$bus_debe["denominacion"];
		$idcuenta_debe = $bus_debe["id".$bus_consultar_cuenta_contable["tabla_debe"]];
		$tabla_debe = $bus_consultar_cuenta_contable["tabla_debe"];
	}
	if ($bus_consultar_cuenta_contable["tabla_haber"]=='0'){
		$denominacion_haber = '&nbsp;';
		$idcuenta_haber = '0';
		$tabla_haber = '0';
	}else{
		$sql_haber ="select * from ".$bus_consultar_cuenta_contable["tabla_haber"]."
									where id".$bus_consultar_cuenta_contable["tabla_haber"]." = '".$bus_consultar_cuenta_contable["idcuenta_haber"]."'";
		$query_haber = mysql_query($sql_haber);
		$bus_haber = mysql_fetch_array($query_haber)or die(mysql_error());
		$denominacion_haber = $bus_haber["codigo"].'-'.$bus_haber["denominacion"];
		$idcuenta_haber = $bus_haber["id".$bus_consultar_cuenta_contable["tabla_haber"]];
		$tabla_haber = $bus_consultar_cuenta_contable["tabla_haber"];
	}

	echo $denominacion_debe."|.|"
	.$idcuenta_debe."|.|"
	.$tabla_debe."|.|"
	.$denominacion_haber."|.|"
	.$idcuenta_haber."|.|"
	.$tabla_haber."|.|";
}

if($ejecutar == "mostrar_cuenta_contable_cuenta_bancaria"){
	$sql_consultar_cuenta_contable = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."'");
	$bus_consultar_cuenta_contable = mysql_fetch_array($sql_consultar_cuenta_contable);
	$sql_cuenta ="select * from ".$bus_consultar_cuenta_contable["tabla"]."
									where id".$bus_consultar_cuenta_contable["tabla"]." = '".$bus_consultar_cuenta_contable["idcuenta_contable"]."'";
	$query_cuenta = mysql_query($sql_cuenta);
	$bus_cuenta = mysql_fetch_array($query_cuenta)or die(mysql_error());
	$denominacion_cuenta = $bus_cuenta["codigo"].'-'.$bus_cuenta["denominacion"];
	$idcuenta_cuenta = $bus_cuenta["id".$bus_consultar_cuenta_contable["tabla"]];
	$tabla_cuenta = $bus_consultar_cuenta_contable["tabla"];

	echo $denominacion_cuenta."|.|"
	.$idcuenta_cuenta."|.|"
	.$tabla_cuenta."|.|";
}



if($ejecutar == "mostrarAnulado"){
	$sql_consultar_asiento_contable = mysql_query("select * from asiento_contable where tipo_movimiento = 'emision_pagos'
																				and iddocumento = '".$idemision_pagos."'");

	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="100%" align="center">
        <thead>
            <tr>
                <td align="center" class="Browse" width="70%">Cuenta</td>
                <td align="center" class="Browse" width="15%">Debe</td>
                <td align="center" class="Browse" width="15%">Haber</td>
            </tr>
        </thead>
       <? 
	   if (mysql_num_rows($sql_consultar_asiento_contable) > 0){
		   while ($bus_consultar_asiento_contable = mysql_fetch_array($sql_consultar_asiento_contable)){
			   $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable
			   										where idasiento_contable = '".$bus_consultar_asiento_contable["idasiento_contable"]."'
														order by afecta")or die("aqui cuenta ".mysql_error());

				$num_cuentas_contables = mysql_num_rows($sql_cuentas_contables)or die(" num ".mysql_error());
				//echo $num_cuentas_contables;
				if($num_cuentas_contables != 0){
					$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable
			   										where idasiento_contable = '".$bus_consultar_asiento_contable["idasiento_contable"]."'
														order by afecta")or die("aqui cuenta ".mysql_error());
					if ($bus_consultar_asiento_contable["estado"] <> 'anulado'){

						while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
	   ?>
                            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                            <?
							$idcampo = "id".$bus_cuentas_contables["tabla"];
							//echo $idcampo;
							$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]."
																	where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(" tablas ".mysql_error());
							$bus_cuenta = mysql_fetch_array($sql_cuentas);

							if ($bus_cuentas_contables["afecta"] == 'debe'){
							?>
                                <td align='left' class='Browse' id="celda_cuenta_debe"><?=$bus_cuenta["codigo"]." ".$bus_cuenta["denominacion"]?></td>
                                <td align="right" class='Browse' id="celda_valor_debe"><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
                                <td align="right" class='Browse' id="celda_valor_haber1">&nbsp;</td>
                            <?
							}else{
							?>
                            	<td align='left' class='Browse' id="celda_cuenta_debe">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["codigo"]." ".$bus_cuenta["denominacion"]?></td>
                                <td align="right" class='Browse' id="celda_valor_debe">&nbsp;</td>
                                <td align="right" class='Browse' id="celda_valor_haber1"><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
                            <?
							}
							?>
                            </tr>
        <?				}
					}else{
					?>
                        <tr bgcolor="#FFCC33" onMouseOver="setRowColor(this, 0, 'over', '#FFCC33', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFCC33', '#EAFFEA', '#FFFFAA')">
                            <td align="left" class='Browse' colspan="4"><strong>Fecha de Reverso: <?=$bus_consultar_asiento_contable["fecha_contable"]?></strong></td>
                        </tr>

                        <?
                        while($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
						?>
							<tr bgcolor="#FFFF99" onMouseOver="setRowColor(this, 0, 'over', '#FFFF99', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF99', '#EAFFEA', '#FFFFAA')">
						<?
							$idcampo = "id".$bus_cuentas_contables["tabla"];
							//echo $idcampo;
							$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]." 
																	where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(" tablas ".mysql_error());
							$bus_cuenta = mysql_fetch_array($sql_cuentas);

							if ($bus_cuentas_contables["afecta"] == 'debe'){
							?>
                                <td align='left' class='Browse' id="celda_cuenta_debe"><?=$bus_cuenta["codigo"]." ".$bus_cuenta["denominacion"]?></td>
                                <td align="right" class='Browse' id="celda_valor_debe"><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
                                <td align="right" class='Browse' id="celda_valor_haber1">&nbsp;</td>
                            <?
							}else{
							?>
                            	<td align='left' class='Browse' id="celda_cuenta_debe">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["codigo"]." ".$bus_cuenta["denominacion"]?></td>
                                <td align="right" class='Browse' id="celda_valor_debe">&nbsp;</td>
                                <td align="right" class='Browse' id="celda_valor_haber1"><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
                            <?
							}
							?> </tr> <?
						}
					}
				}
		   }
		} ?>
  </table>

	<?
}


if($ejecutar == "mostrarProcesado"){
	$sql_consultar_asiento_contable = mysql_query("select * from asiento_contable where tipo_movimiento = 'emision_pagos'
																				and iddocumento = '".$idemision_pagos."'");

	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="100%" align="center">
        <thead>
            <tr>
                <td align="center" class="Browse" width="70%">Cuenta</td>
                <td align="center" class="Browse" width="15%">Debe</td>
                <td align="center" class="Browse" width="15%">Haber</td>
            </tr>
        </thead>
       <? 
	   if (mysql_num_rows($sql_consultar_asiento_contable) > 0){
		   while ($bus_consultar_asiento_contable = mysql_fetch_array($sql_consultar_asiento_contable)){
			   $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable
			   										where idasiento_contable = '".$bus_consultar_asiento_contable["idasiento_contable"]."'
														order by afecta")or die("aqui cuenta ".mysql_error());

				$num_cuentas_contables = mysql_num_rows($sql_cuentas_contables)or die(" num ".mysql_error());
				//echo $num_cuentas_contables;
				if($num_cuentas_contables != 0){
					$sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable
			   										where idasiento_contable = '".$bus_consultar_asiento_contable["idasiento_contable"]."'
														order by afecta")or die("aqui cuenta ".mysql_error());


					while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
   						?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <?
						$idcampo = "id".$bus_cuentas_contables["tabla"];
						//echo $idcampo;
						$sql_cuentas = mysql_query("select * from ".$bus_cuentas_contables["tabla"]."
																where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'")or die(" tablas ".mysql_error());
						$bus_cuenta = mysql_fetch_array($sql_cuentas);

						if ($bus_cuentas_contables["afecta"] == 'debe'){
						?>
                            <td align='left' class='Browse' id="celda_cuenta_debe"><?=$bus_cuenta["codigo"]." ".$bus_cuenta["denominacion"]?></td>
                            <td align="right" class='Browse' id="celda_valor_debe"><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
                            <td align="right" class='Browse' id="celda_valor_haber1">&nbsp;</td>
                        <?
						}else{
						?>
                        	<td align='left' class='Browse' id="celda_cuenta_debe">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["codigo"]." ".$bus_cuenta["denominacion"]?></td>
                            <td align="right" class='Browse' id="celda_valor_debe">&nbsp;</td>
                            <td align="right" class='Browse' id="celda_valor_haber1"><?=number_format($bus_cuentas_contables["monto"],2,',','.')?></td>
                        <?
						}
						?>
                        </tr>
    <?				}
				}
		   }
		} ?>
  </table>

	<?
}
?>