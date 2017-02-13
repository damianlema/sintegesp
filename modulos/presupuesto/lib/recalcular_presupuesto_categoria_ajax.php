<?
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();

extract($_POST);
 
if($ejecutar == "procesar"){
	
	$sql_maestro_presupuesto = mysql_query("select * from maestro_presupuesto 
													where idfuente_financiamiento = '".$idfuente_financiamiento."'
													and idtipo_presupuesto = '".$idtipo_presupuesto."'
													and anio = '".$anio."'
													and idordinal = '".$idordinal."'
													and idcategoria_programatica = '".$idcategoria_programatica."'")or die("ERROR SELECCIONAR MAESTRO: ".mysql_error());
	

	
	while($bus_maestro_presupuesto = mysql_fetch_array($sql_maestro_presupuesto)){
	
				$sql_suma_orden_compra = mysql_query("select SUM(partidas_orden_compra_servicio.monto) as total_suma,
										orden_compra_servicio.idorden_compra_servicio,
										partidas_orden_compra_servicio.idpartidas_orden_compra_servicio
										from partidas_orden_compra_servicio,
										orden_compra_servicio
										where partidas_orden_compra_servicio.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
										and partidas_orden_compra_servicio.idorden_compra_servicio = orden_compra_servicio.idorden_compra_servicio
										and (orden_compra_servicio.estado = 'procesado' || 
											orden_compra_servicio.estado = 'conformado' || 
											orden_compra_servicio.estado = 'pagado' || 
											orden_compra_servicio.estado = 'parcial' || 
											orden_compra_servicio.estado = 'ordenado')
										group by partidas_orden_compra_servicio.idmaestro_presupuesto")
													or die("ERROR SELECCIONANDO PARTIDAS ORDEN COMPRA: ".mysql_error());
	
				$bus_suma_orden_compra = mysql_fetch_array($sql_suma_orden_compra);
				$total_suma_compras = $bus_suma_orden_compra["total_suma"];
	
				//echo "TOTAL SUMA COMPRAS: ".$total_suma_compras."<br />";
				
				$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
														set total_compromisos = '".$total_suma_compras."' 
														where idRegistro = '".$bus_maestro_presupuesto["idRegistro"]."'")
														or die("ERROR ACTUALIZANDO MAESTRO POR COMPRAS: ".mysql_error());
	

// 

	
				// ACTUALIZAR LOS COMPROMISOS DE ORDEN DE PAGO DIRECTO
				
				$sql_orden_pago_directo = mysql_query("select SUM(partidas_orden_pago.monto) as monto 
															from partidas_orden_pago, tipos_documentos, orden_pago
															where partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
															and orden_pago.tipo = tipos_documentos.idtipos_documentos
															and tipos_documentos.compromete = 'si'
															and partidas_orden_pago.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
															and (orden_pago.estado = 'procesado' or orden_pago.estado = 'pagada' or orden_pago.estado = 'conformado' or orden_pago.estado = 'parcial')")or die(mysql_error());
				$bus_orden_pago_directo = mysql_fetch_array($sql_orden_pago_directo);												
				$total_suma_pago_directo = $bus_orden_pago_directo["monto"];
				
				//echo "TOTAL SUMA PAGOS DIRECTOS: ".$total_suma_pago_directo."<br />";
				
				
				$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
																	set total_compromisos = total_compromisos+'".$total_suma_pago_directo."' 
																	where idRegistro = '".$bus_maestro_presupuesto["idRegistro"]."'")
																	or die("ERROR ACTUALIZANDO MAESTRO POR COMPRAS: ".mysql_error());
				
				// ACTUALIZAR LOS COMPROMISOS DE ORDEN DE PAGO DIRECTO
	

				$sql_suma_orden_pago = mysql_query("select partidas_orden_pago.monto as total_monto,
										orden_pago.estado as estado_pago
										from partidas_orden_pago,
										orden_pago
										where partidas_orden_pago.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
										and partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
										and (orden_pago.estado = 'procesado' || orden_pago.estado = 'pagada' or orden_pago.estado = 'conformado' or orden_pago.estado = 'parcial')
                    group by partidas_orden_pago.idorden_pago")or die("ERROR SELECCIONANDO PARTIDAS PAGOS: ".mysql_error());
					
				while($bus_suma_orden_pago = mysql_fetch_array($sql_suma_orden_pago)){
					if($bus_suma_orden_pago["estado_pago"] == 'pagada'){
						$total_suma_pago_procesado += $bus_suma_orden_pago["total_monto"];
						$total_suma_pago_pagado += $bus_suma_orden_pago["total_monto"];
					}else{
						$total_suma_pago_procesado += $bus_suma_orden_pago["total_monto"];
					}
				}
	
	
				$sql_consultar_partidas_disminucion = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total from 
								partidas_disminucion_presupuesto, disminucion_presupuesto where 
								partidas_disminucion_presupuesto.iddisminucion_presupuesto = disminucion_presupuesto.iddisminucion_presupuesto 
								and partidas_disminucion_presupuesto.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
								and disminucion_presupuesto.estado = 'procesado'");
				$bus_consultar_partidas_disminucion = mysql_fetch_array($sql_consultar_partidas_disminucion);
				
				$total_partida_disminucion = $bus_consultar_partidas_disminucion["total"];
				
				$sql_partidas_cedentes_traslado = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total from 
								partidas_cedentes_traslado, traslados_presupuestarios where 
								partidas_cedentes_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios 
								and partidas_cedentes_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
								and traslados_presupuestarios.estado = 'procesado'");
										
				$bus_partidas_cedentes_traslado = mysql_fetch_array($sql_partidas_cedentes_traslado);
				
				$total_partidas_cedentes_traslado = $bus_partidas_cedentes_traslado["total"];
	
	
				$sql_partidas_rectificadoras = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total from 
									partidas_rectificadoras, rectificacion_presupuesto where 
									partidas_rectificadoras.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
									and partidas_rectificadoras.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
									and rectificacion_presupuesto.estado = 'procesado'");
													
				$bus_partidas_rectificadoras = mysql_fetch_array($sql_partidas_rectificadoras);
	
				$total_partidas_rectificadoras = $bus_partidas_rectificadoras["total"];
	
	
				$total_disminuciones = $total_partida_disminucion + $total_partidas_cedentes_traslado + $total_partidas_rectificadoras;
	
				$sql_partidas_credito_adicional = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total from 
										partidas_credito_adicional, creditos_adicionales
										where
										partidas_credito_adicional.idcredito_adicional = creditos_adicionales.idcreditos_adicionales 
										and partidas_credito_adicional.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
										and creditos_adicionales.estado = 'procesado'")or die(mysql_error());
													
				$bus_partidas_credito_adicional = mysql_fetch_array($sql_partidas_credito_adicional);
	
				$total_partidas_credito_adicional = $bus_partidas_credito_adicional["total"];
	
	
	
				$sql_partidas_receptoras_rectificacion = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total from 
						partidas_receptoras_rectificacion, rectificacion_presupuesto
						where 
						partidas_receptoras_rectificacion.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
						and partidas_receptoras_rectificacion.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
						and rectificacion_presupuesto.estado = 'procesado'");
				$bus_partidas_receptoras_rectificacion = mysql_fetch_array($sql_partidas_receptoras_rectificacion);
	
				$total_partidas_receptoras_rectificacion = $bus_partidas_receptoras_rectificacion["total"];
	
	
				$sql_partidas_receptoras_traslado = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total from 
							partidas_receptoras_traslado,traslados_presupuestarios
							where 
							partidas_receptoras_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios and
							partidas_receptoras_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
							and traslados_presupuestarios.estado = 'procesado'");
				$bus_partidas_receptoras_traslado = mysql_fetch_array($sql_partidas_receptoras_traslado);
	
				$total_partidas_receptoras_traslado = $bus_partidas_receptoras_traslado["total"];
	
				$total_aumento = $total_partidas_credito_adicional+$total_partidas_receptoras_rectificacion+$total_partidas_receptoras_traslado;
	
	
				
	
				// NUEVOS CRITERIOS*************************************************************************************************************
				
				$sql_consultar_partidas_disminucion2 = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total from 
								partidas_disminucion_presupuesto, disminucion_presupuesto where 
								partidas_disminucion_presupuesto.iddisminucion_presupuesto = disminucion_presupuesto.iddisminucion_presupuesto 
								and partidas_disminucion_presupuesto.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
								and disminucion_presupuesto.estado = 'elaboracion'");
				$bus_consultar_partidas_disminucion2 = mysql_fetch_array($sql_consultar_partidas_disminucion2);
				
				$total_partida_disminucion = $bus_consultar_partidas_disminucion["total"];
				
				$sql_partidas_cedentes_traslado2 = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total from 
								partidas_cedentes_traslado, traslados_presupuestarios where 
								partidas_cedentes_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios 
								and partidas_cedentes_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
								and traslados_presupuestarios.estado = 'elaboracion'");
										
				$bus_partidas_cedentes_traslado2 = mysql_fetch_array($sql_partidas_cedentes_traslado2);
				
				$total_partidas_cedentes_traslado2 = $bus_partidas_cedentes_traslado2["total"];
	
	
				$sql_partidas_rectificadoras2 = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total from 
									partidas_rectificadoras, rectificacion_presupuesto where 
									partidas_rectificadoras.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
									and partidas_rectificadoras.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
									and rectificacion_presupuesto.estado = 'elaboracion'");
													
				$bus_partidas_rectificadoras2 = mysql_fetch_array($sql_partidas_rectificadoras2);
	
				$total_partidas_rectificadoras2 = $bus_partidas_rectificadoras2["total"];
	
	
				$total_disminuciones2 = $total_partida_disminucion2 + $total_partidas_cedentes_traslado2 + $total_partidas_rectificadoras2;
	
				$sql_partidas_credito_adicional2 = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total from 
										partidas_credito_adicional, creditos_adicionales
										where
										partidas_credito_adicional.idcredito_adicional = creditos_adicionales.idcreditos_adicionales 
										and partidas_credito_adicional.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
										and creditos_adicionales.estado = 'elaboracion'")or die(mysql_error());
													
				$bus_partidas_credito_adicional2 = mysql_fetch_array($sql_partidas_credito_adicional2);
	
				$total_partidas_credito_adicional2 = $bus_partidas_credito_adicional2["total"];
	
	
	
				$sql_partidas_receptoras_rectificacion2 = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total from 
						partidas_receptoras_rectificacion, rectificacion_presupuesto
						where 
						partidas_receptoras_rectificacion.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
						and partidas_receptoras_rectificacion.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
						and rectificacion_presupuesto.estado = 'elaboracion'");
				$bus_partidas_receptoras_rectificacion2 = mysql_fetch_array($sql_partidas_receptoras_rectificacion2);
	
				$total_partidas_receptoras_rectificacion2 = $bus_partidas_receptoras_rectificacion2["total"];
	
	
				$sql_partidas_receptoras_traslado2 = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total from 
							partidas_receptoras_traslado,traslados_presupuestarios
							where 
							partidas_receptoras_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios and
							partidas_receptoras_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
							and traslados_presupuestarios.estado = 'elaboracion'");
				$bus_partidas_receptoras_traslado2 = mysql_fetch_array($sql_partidas_receptoras_traslado2);
	
				$total_partidas_receptoras_traslado2 = $bus_partidas_receptoras_traslado2["total"];
	
				$total_aumento2 = $total_partidas_credito_adicional2+$total_partidas_receptoras_rectificacion2+$total_partidas_receptoras_traslado2;
				
				
				
				
				// REQUISICIONES
				
				$sql_suma_requisicion = mysql_query("select SUM(partidas_requisiciones.monto) as total_suma,
										requisicion.idrequisicion,
										partidas_requisiciones.idpartidas_requisiciones
										from partidas_requisiciones,
										requisicion
										where partidas_requisiciones.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
										and partidas_requisiciones.idrequisicion = requisicion.idrequisicion
										and requisicion.estado = 'procesado'
										group by partidas_requisiciones.idmaestro_presupuesto")
													or die("ERROR SELECCIONANDO PARTIDAS REQUISICIONES: ".mysql_error());
				
				$bus_suma_requisicion = mysql_fetch_array($sql_suma_requisicion);
				$total_suma_requisicion = $bus_suma_requisicion["total_suma"];
				// NUEVOS CRITERIOS*************************************************************************************************************
	
				
				
				$sql_consultar_rendicion = mysql_query("select * from rendicion_cuentas_partidas where idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'");
				$num_consultar_rendicion = mysql_num_rows($sql_consultar_rendicion);
				
				if($num_consultar_rendicion > 0){
					
					$sql_rendicion = mysql_query("select SUM(total_compromisos_periodo) as total_compromisos,
													SUM(total_causados_periodo) as total_causados,
													SUM(total_pagados_periodo) as total_pagados,
													SUM(aumento_periodo) as total_aumento,
													SUM(disminucion_periodo) as total_disminuciones 
														from 
													rendicion_cuentas_partidas
														where
													idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'");
					$bus_rendicion = mysql_fetch_array($sql_rendicion);
					
					$sql_actualizar_maestro = mysql_query("update maestro_presupuesto
															set total_compromisos = ".$bus_rendicion["total_compromisos"]."
															where idRegistro = '".$bus_maestro_presupuesto["idRegistro"]."'");
					
					$total_suma_pago_procesado = $bus_rendicion["total_causados"];
					$total_suma_pago_pagado = $bus_rendicion["total_pagados"];
					$total_aumento = $bus_rendicion["total_aumento"];
					$total_disminuciones = $bus_rendicion["total_disminuciones"];
					$total_disminuciones2 = 0;
					$total_aumento2 = 0;
					$total_suma_requisicion = 0;
					
				}
				
				
				
				
				
				
					
	
				$sql_update_disminuciones_aumento = mysql_query("update maestro_presupuesto
															set total_disminucion = '".$total_disminuciones."',
															total_aumento = '".$total_aumento."',
															total_causados = '".$total_suma_pago_procesado."',
															total_pagados = '".$total_suma_pago_pagado."',
															reservado_disminuir = '".$total_disminuciones2."',
															solicitud_aumento = '".$total_aumento2."',
															pre_compromiso = '".$total_suma_requisicion."'
															where idRegistro = '".$bus_maestro_presupuesto["idRegistro"]."'");
															
				$monto_actual = $total_aumento - $total_disminuciones;
	
				$sql_update_monto_actual = mysql_query("update maestro_presupuesto
															set monto_actual = monto_original + total_aumento - total_disminucion
															where idRegistro = '".$bus_maestro_presupuesto["idRegistro"]."'");
			
	$total_disminuciones = 0;
	$total_aumento = 0;
	$total_suma_pago_procesado = 0;
	$total_suma_pago_pagado = 0;
	$total_disminuciones2 = 0;
	$total_aumento2 = 0;
	$total_suma_requisicion = 0;
	}
	
	
	
	
	//******************************** SUMAR LAS SUB ESPECIFICAS ***********************************************************

$sql_maestro_presupuesto = mysql_query("select * from maestro_presupuesto, clasificador_presupuestario 
													where idfuente_financiamiento = '".$idfuente_financiamiento."'
													and idtipo_presupuesto = '".$idtipo_presupuesto."'
													and anio = '".$anio."'
													and idordinal = '".$idordinal."'
													and idcategoria_programatica = '".$idcategoria_programatica."'
													and clasificador_presupuestario.idclasificador_presupuestario = maestro_presupuesto.idclasificador_presupuestario
													and clasificador_presupuestario.sub_especifica<>'00'")or die("ERROR SELECCIONAR MAESTRO: ".mysql_error());

while($bus_maestro_presupuesto = mysql_fetch_array($sql_maestro_presupuesto)){
	$sql_clasificador_presupuestario = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".	
												   $bus_maestro_presupuesto["idclasificador_presupuestario"]."'");
	$bus_clasificador_presupuestario =  mysql_fetch_array($sql_clasificador_presupuestario);
	echo "<strong>&nbsp;".$i."-&nbsp;</strong>".$bus_clasificador_presupuestario["partida"]." ".$bus_clasificador_presupuestario["generica"]." ".		
			$bus_clasificador_presupuestario["especifica"]." ".$bus_clasificador_presupuestario["sub_especifica"]." ".$bus_clasificador_presupuestario[
			"denominacion"]."<br>";	
			$i++;
	//echo " maestro ".$bus_maestro_presupuesto["idRegistro"];
	
	$sql_suma_orden_compra = mysql_query("select SUM(partidas_orden_compra_servicio.monto) as total_suma,
											orden_compra_servicio.idorden_compra_servicio,
											partidas_orden_compra_servicio.idpartidas_orden_compra_servicio
										from partidas_orden_compra_servicio,
											orden_compra_servicio
										where partidas_orden_compra_servicio.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
											and partidas_orden_compra_servicio.idorden_compra_servicio = orden_compra_servicio.idorden_compra_servicio
											and (orden_compra_servicio.estado = 'procesado' || 
											orden_compra_servicio.estado = 'conformado' || 
											orden_compra_servicio.estado = 'pagado' || 
											orden_compra_servicio.estado = 'parcial' || 
											orden_compra_servicio.estado = 'ordenado')
										group by partidas_orden_compra_servicio.idmaestro_presupuesto")
													or die("ERROR SELECCIONANDO PARTIDAS ORDEN COMPRA: ".mysql_error());
	
				$bus_suma_orden_compra = mysql_fetch_array($sql_suma_orden_compra);
				$total_suma_compras = $bus_suma_orden_compra["total_suma"];
	
			
				$sql_clasificador = mysql_query("select * from clasificador_presupuestario 
													  where 
													  idclasificador_presupuestario = '".$bus_maestro_presupuesto["idclasificador_presupuestario"]."'");
				
				$bus_clasificador = mysql_fetch_array($sql_clasificador);
			
						//echo $bus_maestro_presupuesto["idRegistro"];
						$sql_especifica = mysql_query("select * from clasificador_presupuestario 
													  where partida = '".$bus_clasificador["partida"]."' and
													  generica = '".$bus_clasificador["generica"]."' and
													  especifica = '".$bus_clasificador["especifica"]."' and
													  sub_especifica = '00'")or die("ERROR SELECCIONAR ESPECIFICA: ".mysql_error());
						$bus_especifica = mysql_fetch_array($sql_especifica);
												
						$sql_maestro_especifica = mysql_query("select * 
																from maestro_presupuesto
																where 
													maestro_presupuesto.idclasificador_presupuestario = '".$bus_especifica["idclasificador_presupuestario"]."'
													and maestro_presupuesto.idcategoria_programatica = '".$bus_maestro_presupuesto["idcategoria_programatica"]."'
													and maestro_presupuesto.idtipo_presupuesto = '".$bus_maestro_presupuesto["idtipo_presupuesto"]."'
													and maestro_presupuesto.idfuente_financiamiento = '".$bus_maestro_presupuesto["idfuente_financiamiento"]."'
													and maestro_presupuesto.idordinal = '".$bus_maestro_presupuesto["idordinal"]."'
															 ")or die("ERROR SELECCIONAR MAESTRO especifica: ".mysql_error());
						
						$bus_maestroE = mysql_fetch_array($sql_maestro_especifica);
						
		//echo " antes de compra ".$bus_maestroE["total_compromisos"];				
						
						
						$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
															set total_compromisos =  total_compromisos+'".$total_suma_compras."' 
															where idRegistro = '".$bus_maestroE["idRegistro"]."'")
															or die("ERROR ACTUALIZANDO MAESTRO POR COMPRAS ESPECIFICA: ".mysql_error());
					
				


	
				// ACTUALIZAR LOS COMPROMISOS DE ORDEN DE PAGO DIRECTO
				
				$sql_orden_pago_directo = mysql_query("select SUM(partidas_orden_pago.monto) as monto 
															from partidas_orden_pago, tipos_documentos, orden_pago
															where partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
															and orden_pago.tipo = tipos_documentos.idtipos_documentos
															and tipos_documentos.compromete = 'si'
															and partidas_orden_pago.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
															and (orden_pago.estado = 'procesado' or orden_pago.estado = 'pagada' or orden_pago.estado = 'conformado' or orden_pago.estado = 'parcial')")or die(mysql_error());
				$bus_orden_pago_directo = mysql_fetch_array($sql_orden_pago_directo);												
				$total_suma_pago_directo = $bus_orden_pago_directo["monto"];
				
							
						$sql_actualizar_maestro = mysql_query("update maestro_presupuesto 
																	set total_compromisos = total_compromisos+'".$total_suma_pago_directo."' 
																	where idRegistro = '".$bus_maestroE["idRegistro"]."'")
																	or die("ERROR ACTUALIZANDO MAESTRO POR COMPRAS: ".mysql_error());
					
				
			

				$sql_suma_orden_pago = mysql_query("select partidas_orden_pago.monto as total_monto,
										orden_pago.estado as estado_pago
											from 
										partidas_orden_pago,
										orden_pago
											where 
										partidas_orden_pago.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
										and partidas_orden_pago.idorden_pago = orden_pago.idorden_pago
										and (orden_pago.estado = 'procesado' or 
										orden_pago.estado = 'pagada' 
										or orden_pago.estado = 'conformado'
										or orden_pago.estado = 'parcial')
                    group by partidas_orden_pago.idorden_pago")or die("ERROR SELECCIONANDO PARTIDAS PAGOS: ".mysql_error());
					
				while($bus_suma_orden_pago = mysql_fetch_array($sql_suma_orden_pago)){
					
					
					if($bus_suma_orden_pago["estado_pago"] == 'pagada'){
						$total_suma_pago_procesado += $bus_suma_orden_pago["total_monto"];
						$total_suma_pago_pagado += $bus_suma_orden_pago["total_monto"];
					}else{
						$total_suma_pago_procesado += $bus_suma_orden_pago["total_monto"];
					}
				}
				
				
				
				
				
				/*$sql_consulta_pagos_financieros = mysql_query("select * from pagos_financieros");
				while($bus_consultar_pagos_financieros = mysql_fetch_array($sql_consulta_pagos_financieros)){
							$total_suma_pago_pagado = $bus_consultar_pagos_financieros["monto_cheque"];
				}*/
	
	
				$sql_consultar_partidas_disminucion = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total from 
								partidas_disminucion_presupuesto, disminucion_presupuesto where 
								partidas_disminucion_presupuesto.iddisminucion_presupuesto = disminucion_presupuesto.iddisminucion_presupuesto 
								and partidas_disminucion_presupuesto.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
								and disminucion_presupuesto.estado = 'procesado'");
				$bus_consultar_partidas_disminucion = mysql_fetch_array($sql_consultar_partidas_disminucion);
				
				$total_partida_disminucion = $bus_consultar_partidas_disminucion["total"];
				
				$sql_partidas_cedentes_traslado = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total from 
								partidas_cedentes_traslado, traslados_presupuestarios where 
								partidas_cedentes_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios 
								and partidas_cedentes_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
								and traslados_presupuestarios.estado = 'procesado'");
										
				$bus_partidas_cedentes_traslado = mysql_fetch_array($sql_partidas_cedentes_traslado);
				
				$total_partidas_cedentes_traslado = $bus_partidas_cedentes_traslado["total"];
	
	
				$sql_partidas_rectificadoras = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total from 
									partidas_rectificadoras, rectificacion_presupuesto where 
									partidas_rectificadoras.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
									and partidas_rectificadoras.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
									and rectificacion_presupuesto.estado = 'procesado'");
													
				$bus_partidas_rectificadoras = mysql_fetch_array($sql_partidas_rectificadoras);
	
				$total_partidas_rectificadoras = $bus_partidas_rectificadoras["total"];
	
	
				$total_disminuciones = $total_partida_disminucion + $total_partidas_cedentes_traslado + $total_partidas_rectificadoras;
	
				$sql_partidas_credito_adicional = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total from 
										partidas_credito_adicional, creditos_adicionales
										where
										partidas_credito_adicional.idcredito_adicional = creditos_adicionales.idcreditos_adicionales 
										and partidas_credito_adicional.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
										and creditos_adicionales.estado = 'procesado'")or die(mysql_error());
													
				$bus_partidas_credito_adicional = mysql_fetch_array($sql_partidas_credito_adicional);
	
				$total_partidas_credito_adicional = $bus_partidas_credito_adicional["total"];
	
	
	
				$sql_partidas_receptoras_rectificacion = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total from 
						partidas_receptoras_rectificacion, rectificacion_presupuesto
						where 
						partidas_receptoras_rectificacion.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
						and partidas_receptoras_rectificacion.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
						and rectificacion_presupuesto.estado = 'procesado'");
				$bus_partidas_receptoras_rectificacion = mysql_fetch_array($sql_partidas_receptoras_rectificacion);
	
				$total_partidas_receptoras_rectificacion = $bus_partidas_receptoras_rectificacion["total"];
	
	
				$sql_partidas_receptoras_traslado = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total from 
							partidas_receptoras_traslado,traslados_presupuestarios
							where 
							partidas_receptoras_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios and
							partidas_receptoras_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
							and traslados_presupuestarios.estado = 'procesado'");
				$bus_partidas_receptoras_traslado = mysql_fetch_array($sql_partidas_receptoras_traslado);
	
				$total_partidas_receptoras_traslado = $bus_partidas_receptoras_traslado["total"];
	
				$total_aumento = $total_partidas_credito_adicional+$total_partidas_receptoras_rectificacion+$total_partidas_receptoras_traslado;
	
	
				
	
				// NUEVOS CRITERIOS*************************************************************************************************************
				
				$sql_consultar_partidas_disminucion2 = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total from 
								partidas_disminucion_presupuesto, disminucion_presupuesto where 
								partidas_disminucion_presupuesto.iddisminucion_presupuesto = disminucion_presupuesto.iddisminucion_presupuesto 
								and partidas_disminucion_presupuesto.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
								and disminucion_presupuesto.estado = 'elaboracion'");
				$bus_consultar_partidas_disminucion2 = mysql_fetch_array($sql_consultar_partidas_disminucion2);
				
				$total_partida_disminucion = $bus_consultar_partidas_disminucion["total"];
				
				$sql_partidas_cedentes_traslado2 = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total from 
								partidas_cedentes_traslado, traslados_presupuestarios where 
								partidas_cedentes_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios 
								and partidas_cedentes_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
								and traslados_presupuestarios.estado = 'elaboracion'");
										
				$bus_partidas_cedentes_traslado2 = mysql_fetch_array($sql_partidas_cedentes_traslado2);
				
				$total_partidas_cedentes_traslado2 = $bus_partidas_cedentes_traslado2["total"];
	
	
				$sql_partidas_rectificadoras2 = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total from 
									partidas_rectificadoras, rectificacion_presupuesto where 
									partidas_rectificadoras.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
									and partidas_rectificadoras.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
									and rectificacion_presupuesto.estado = 'elaboracion'");
													
				$bus_partidas_rectificadoras2 = mysql_fetch_array($sql_partidas_rectificadoras2);
	
				$total_partidas_rectificadoras2 = $bus_partidas_rectificadoras2["total"];
	
	
				$total_disminuciones2 = $total_partida_disminucion2 + $total_partidas_cedentes_traslado2 + $total_partidas_rectificadoras2;
	
				$sql_partidas_credito_adicional2 = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total from 
										partidas_credito_adicional, creditos_adicionales
										where
										partidas_credito_adicional.idcredito_adicional = creditos_adicionales.idcreditos_adicionales 
										and partidas_credito_adicional.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
										and creditos_adicionales.estado = 'elaboracion'")or die(mysql_error());
													
				$bus_partidas_credito_adicional2 = mysql_fetch_array($sql_partidas_credito_adicional2);
	
				$total_partidas_credito_adicional2 = $bus_partidas_credito_adicional2["total"];
	
	
	
				$sql_partidas_receptoras_rectificacion2 = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total from 
						partidas_receptoras_rectificacion, rectificacion_presupuesto
						where 
						partidas_receptoras_rectificacion.idrectificacion_presupuesto = rectificacion_presupuesto.idrectificacion_presupuesto 
						and partidas_receptoras_rectificacion.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
						and rectificacion_presupuesto.estado = 'elaboracion'");
				$bus_partidas_receptoras_rectificacion2 = mysql_fetch_array($sql_partidas_receptoras_rectificacion2);
	
				$total_partidas_receptoras_rectificacion2 = $bus_partidas_receptoras_rectificacion2["total"];
	
	
				$sql_partidas_receptoras_traslado2 = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total from 
							partidas_receptoras_traslado,traslados_presupuestarios
							where 
							partidas_receptoras_traslado.idtraslados_presupuestarios = traslados_presupuestarios.idtraslados_presupuestarios and
							partidas_receptoras_traslado.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."' 
							and traslados_presupuestarios.estado = 'elaboracion'");
				$bus_partidas_receptoras_traslado2 = mysql_fetch_array($sql_partidas_receptoras_traslado2);
	
				$total_partidas_receptoras_traslado2 = $bus_partidas_receptoras_traslado2["total"];
	
				$total_aumento2 = $total_partidas_credito_adicional2+$total_partidas_receptoras_rectificacion2+$total_partidas_receptoras_traslado2;
				
				
				
				
				// REQUISICIONES
				
				$sql_suma_requisicion = mysql_query("select SUM(partidas_requisiciones.monto) as total_suma,
										requisicion.idrequisicion,
										partidas_requisiciones.idpartidas_requisiciones
										from partidas_requisiciones,
										requisicion
										where partidas_requisiciones.idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'
										and partidas_requisiciones.idrequisicion = requisicion.idrequisicion
										and requisicion.estado = 'procesado'
										group by partidas_requisiciones.idmaestro_presupuesto")
													or die("ERROR SELECCIONANDO PARTIDAS REQUISICIONES: ".mysql_error());
				
				$bus_suma_requisicion = mysql_fetch_array($sql_suma_requisicion);
				$total_suma_requisicion = $bus_suma_requisicion["total_suma"];
				// NUEVOS CRITERIOS*************************************************************************************************************
	
				
				
				$sql_consultar_rendicion = mysql_query("select * from rendicion_cuentas_partidas where idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'");
				$num_consultar_rendicion = mysql_num_rows($sql_consultar_rendicion);
				
				if($num_consultar_rendicion > 0){
					
					$sql_rendicion = mysql_query("select SUM(total_compromisos_periodo) as total_compromisos,
													SUM(total_causados_periodo) as total_causados,
													SUM(total_pagados_periodo) as total_pagados,
													SUM(aumento_periodo) as total_aumento,
													SUM(disminucion_periodo) as total_disminuciones 
														from 
													rendicion_cuentas_partidas
														where
													idmaestro_presupuesto = '".$bus_maestro_presupuesto["idRegistro"]."'");
					$bus_rendicion = mysql_fetch_array($sql_rendicion);
					
					$sql_actualizar_maestro = mysql_query("update maestro_presupuesto
															set total_compromisos = ".$bus_rendicion["total_compromisos"]."
															where idRegistro = '".$bus_maestro_presupuesto["idRegistro"]."'");
					
					$total_suma_pago_procesado = $bus_rendicion["total_causados"];
					$total_suma_pago_pagado = $bus_rendicion["total_pagados"];
					$total_aumento = $bus_rendicion["total_aumento"];
					$total_disminuciones = $bus_rendicion["total_disminuciones"];
					$total_disminuciones2 = 0;
					$total_aumento2 = 0;
					$total_suma_requisicion = 0;
					
				}
				
				//echo " id ".$bus_maestroE["idRegistro"];
				//echo " total causa".$total_suma_pago_procesado;
				//echo " antes de ".$bus_maestroE["total_causados"];
				
				
				
				$sql_update_especifica = mysql_query("update maestro_presupuesto
															set total_disminucion = total_disminucion +'".$total_disminuciones."',
															total_aumento = total_aumento +'".$total_aumento."',
															total_causados = total_causados +'".$total_suma_pago_procesado."',
															total_pagados = total_pagados +'".$total_suma_pago_pagado."',
															reservado_disminuir = reservado_disminuir +'".$total_disminuciones2."',
															solicitud_aumento = solicitud_aumento +'".$total_aumento2."',
															pre_compromiso = pre_compromiso +'".$total_suma_requisicion."'
															where idRegistro = '".$bus_maestroE["idRegistro"]."'") or die("ERROR FINAL REQUISICIONES: ".mysql_error());
															

			
				$monto_actual = $total_aumento - $total_disminuciones;
	
				$sql_update_monto_actual = mysql_query("update maestro_presupuesto
															set monto_actual = monto_original + total_aumento - total_disminucion
															where idRegistro = '".$bus_maestroE["idRegistro"]."'");
			
	$total_disminuciones = 0;
	$total_aumento = 0;
	$total_suma_pago_procesado = 0;
	$total_suma_pago_pagado = 0;
	$total_disminuciones2 = 0;
	$total_aumento2 = 0;
	$total_suma_requisicion = 0;


}

}
?>