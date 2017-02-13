<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);


if($ejecutar == "cargarCuentasBancarias"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$banco."'");
	?>
	<select name="cuenta" id="cuenta">
    	<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
			?>
			<option value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
			<?
		}
		?>
    </select>  
	<?
}


if($ejecutar == "actualizarDatosBasicos"){
	$sql_insertar_datos_basicos = mysql_query("update autorizar_generar_comprobante set
																			iddependencia_destino = '".$para."',
																			asunto = '".$asunto."',
																			justificacion = '".$justificacion."',
																			estado = 'elaboracion',
																			status = 'a',
																			usuario = '".$login."',
																			idcuenta_bancaria = '".$cuenta."',
																			
																		fechayhora = '".date("Y-m-d H:i:s")."' where idautorizar_generar_comprobante = ".$id_remision."");
registra_transaccion("Actualizar Datos Basicos de Relacion Autorizacion de generar comprobantes (".$id_remision.")",$login,$fh,$pc,'autorizar_generar_comprobante');																			
}



if($ejecutar == "ingresarDatosBasicos"){
	if($id_remision == ""){
		if ($modulo == 1){
		  	$sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	  	}else if ($modulo == 2){
		  	$sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	  	}else if($modulo == 3){
		  	$sql_configuracion = mysql_query("select * from configuracion_compras");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
  	  	}else if($modulo == 4){
		  	$sql_configuracion = mysql_query("select * from configuracion_administracion");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 6){
		  	$sql_configuracion = mysql_query("select * from configuracion_tributos");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 7){
		  	$sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
		}else if($modulo == 5){
		  	$sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 12){
		  	$sql_configuracion = mysql_query("select * from configuracion_despacho");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 13){
		  	$sql_configuracion = mysql_query("select * from configuracion_nomina");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 14){
		  	$sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 16){
		  	$sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 19){
		  	$sql_configuracion = mysql_query("select * from configuracion_obras");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}
		
		$sql_insertar_datos_basicos = mysql_query("insert into autorizar_generar_comprobante(iddependencia_destino,
																				asunto,
																				justificacion,
																				estado,
																				status,
																				usuario,
																				fechayhora,
																				iddependencia_origen,
																				idcuenta_bancaria)values(
																								'".$para."',
																								'".$asunto."',
																								'".$justificacion."',
																								'elaboracion',
																								'a',
																								'".$login."',
																								'".date("Y-m-d H:i:s")."',
																								'".$bus_configuracion["iddependencia"]."',
																								'".$cuenta."'
																								)")or die(mysq_error());
		$id_remision = mysql_insert_id();
		echo $id_remision;
		registra_transaccion("Ingresar Datos Basicos Relacion autorizar generar comprobantes (".$id_remision.")",$login,$fh,$pc,'relacion_autorizar_generar_comprobantes');
	}

	
}


if($ejecutar == "consultarDocumentos"){

	$sql_buscar_documentos = mysql_query("select orden_pago.idorden_pago,
													orden_pago.numero_orden,
													orden_pago.fecha_orden,
													orden_pago.idbeneficiarios,
													orden_pago.exento,
													orden_pago.sub_total,
													orden_pago.impuesto,
													orden_pago.total,
													relacion_retenciones.porcentaje_aplicado,
													relacion_retenciones.monto_retenido
													 from orden_pago,
														relacion_pago_compromisos,
														orden_compra_servicio,
														retenciones,
														relacion_retenciones,
             	 										pagos_financieros
											where relacion_pago_compromisos.idorden_pago = orden_pago.idorden_pago
											  and orden_compra_servicio.idorden_compra_servicio = relacion_pago_compromisos.idorden_compra_servicio
											  and retenciones.iddocumento = orden_compra_servicio.idorden_compra_servicio
											  and relacion_retenciones.idretenciones = retenciones.idretenciones
											  and pagos_financieros.idorden_pago = orden_pago.idorden_pago
											  and pagos_financieros.idcuenta_bancaria = '".$cuenta."'
											  and relacion_retenciones.numero_retencion = '0'
											group by orden_pago.codigo_referencia
											order by orden_pago.codigo_referencia");
	if (mysql_num_rows($sql_buscar_documentos) > 0){
		?>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
           <tr>
            <td class="Browse"><div align="center">Selec</div></td>
            <td class="Browse"><div align="center">Numero</div></td>
            <td class="Browse"><div align="center">Fecha</div></td>
            <td class="Browse"><div align="center">Beneficiario</div></td>
            <td class="Browse"><div align="center">Exento</div></td>
            <td class="Browse"><div align="center">Base</div></td>
            <td class="Browse"><div align="center">Impuesto</div></td>
            <td class="Browse"><div align="center">Total</div></td>
           <? // <td class="Browse"><div align="center">%Reten.</div></td> ?>
            <td class="Browse"><div align="center">Monto Retenido</div></td>
          </tr>
          </thead>
        <?
		while($bus_consulta = mysql_fetch_array($sql_buscar_documentos)){
			$sql_validar_op= mysql_query("select * from relacion_documentos_generar_comprobante, autorizar_generar_comprobante
							where autorizar_generar_comprobante.idautorizar_generar_comprobante = relacion_documentos_generar_comprobante.idautorizar_generar_comprobante
								and (autorizar_generar_comprobante.estado = 'enviado' 
										or autorizar_generar_comprobante.estado = 'recibido'
										or autorizar_generar_comprobante.estado = 'procesado')
								and relacion_documentos_generar_comprobante.idorden_pago = '".$bus_consulta["idorden_pago"]."'");
			if(mysql_num_rows($sql_validar_op) <= 0){
				$sql_valida_op2 = mysql_query("select * from relacion_documentos_generar_comprobante
											where relacion_documentos_generar_comprobante.idautorizar_generar_comprobante = '".$id_remision."'
											and relacion_documentos_generar_comprobante.idorden_pago = '".$bus_consulta["idorden_pago"]."'");
				if(mysql_num_rows($sql_valida_op2) <= 0){
					$sql_suma_total_retenido = mysql_query("select SUM(relacion_retenciones.monto_retenido) as monto_retenido from relacion_orden_pago_retencion,
																			retenciones,
																			
																			relacion_retenciones
																		 where relacion_orden_pago_retencion.idorden_pago = '".$bus_consulta["idorden_pago"]."'
																		 and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
																		 and relacion_retenciones.idretenciones = retenciones.idretenciones
																		 ");
					$bus_suma_total_retenido = mysql_fetch_array($sql_suma_total_retenido);					
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta["idorden_pago"]?>', document.getElementById('id_remision').value)" style="cursor:pointer">
		
						<td class="Browse" align='center'><img src="imagenes/validar.png" style=" cursor:pointer"></td>
						
						<td class='Browse' align='left'><?=$bus_consulta["numero_orden"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_orden"]?></td>
						
						<td class='Browse' align='left'>
						<?
							$sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = ".$bus_consulta["idbeneficiarios"]."");
							$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
							echo $bus_beneficiarios["nombre"];
							?>
						
						
						</td>
						<td class='Browse' align='right'><?=number_format($bus_consulta["exento"],2,",",".")?></td>
						<td class='Browse' align='right'><?=number_format($bus_consulta["sub_total"],2,",",".")?></td>
						<td class='Browse' align='right'><?=number_format($bus_consulta["impuesto"],2,",",".")?></td>
						<td class='Browse' align='right'><?=number_format($bus_consulta["total"],2,",",".")?></td>
						<? // <td class='Browse' align='right'><?=number_format($bus_consulta["porcentaje_aplicado"],2,",",".")</td> ?>
						<td class='Browse' align='right'><?=number_format($bus_suma_total_retenido["monto_retenido"],2,",",".")?></td>
					 </tr>
							<?
				}
			}
		}
		?>
        </table>
       <?
	}else{
	?>  <strong>No existen documentos pagados por esa cuenta con ese tipo de retención </strong> <?
	}
}





if($ejecutar == "consultarEstado"){
	$sql_consulta_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."");
	$bus_consulta_remision = mysql_fetch_array($sql_consulta_remision);
	echo $bus_consulta_remision["estado"];
}




//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************

if($ejecutar == "consultarSubDocumentos"){
//echo "entro aqui ".$modulo;
	if ($modulo == 1){
		  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if ($modulo == 2){
		  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 3){
		  $sql_configuracion = mysql_query("select * from configuracion_compras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
  	}else if($modulo == 4){
		  $sql_configuracion = mysql_query("select * from configuracion_administracion");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 6){
		  $sql_configuracion = mysql_query("select * from configuracion_tributos");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 7){
		  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 5){
		  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 12){
		  $sql_configuracion = mysql_query("select * from configuracion_despacho");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 13){
		  $sql_configuracion = mysql_query("select * from configuracion_nomina");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 14){
		  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 16){
		  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 19){
		  $sql_configuracion = mysql_query("select * from configuracion_obras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}
	
	
	$sql_consulta_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."");
	$bus_consulta_remision = mysql_fetch_array($sql_consulta_remision);

	if($bus_consulta_remision["estado"] != "enviado"){
	//echo "AQUI";
		if($bus_consulta_remision["estado"] != "recibido"){

			if($documento=="presupuesto"){
				$sql_consulta = mysql_query("select * from ".$tabla." where ".$tabla.".estado = '".$estado_tipo."' 
																			and ".$tabla.".ubicacion = '0'
																			group by id".$tabla."
																			") or die("error presupuesto".mysql_error());
				$num_consulta = mysql_num_rows($sql_consulta);
			}else if($documento=="compromete"){
		
				//echo "AQUIIII:".$campo_tipo_documento;
				if($campo_tipo_documento == "hijo"){
				
							if ($modulo == 1 || $modulo == 13 || $modulo == 3 || $modulo == 16 || $modulo == 19){
								$sql_consulta = mysql_query("select * from orden_compra_servicio 
														where tipo = '".$estado_tipo."' 
														and numero_orden like '%".$busqueda."%'
														and (estado = 'procesado' or estado = 'conformado' 
														or estado = 'devuelto' or estado = 'parcial')
														and ubicacion = '0'  group by idorden_compra_servicio")
														or die("consulta mala compromete".mysql_error());
								$num_consulta = mysql_num_rows($sql_consulta);
								
							}
								
											
							if ($modulo == 4 || $modulo == 5 || $modulo == 12 || $modulo == 14){
								$sql_tipo_documento = mysql_query("select * from tipos_documentos where 
																						idtipos_documentos = ".$estado_tipo."
																						and modulo like '%-".$modulo."%-'");
								$num_tipo_documento = mysql_fetch_array($sql_tipo_documento);
								if ($num_tipo_documento == 0){
									/*echo "select * from orden_compra_servicio,
										relacion_documentos_recibidos, 
										recibir_documentos 
										where 
										orden_compra_servicio.tipo = '".$estado_tipo."' 
										and (orden_compra_servicio.estado = 'conformado' 
											or orden_compra_servicio.estado = 'devuelto' 
											or orden_compra_servicio.estado = 'procesado' 
											or orden_compra_servicio.estado = 'parcial')
										and orden_compra_servicio.numero_orden like '%".$busqueda."%'
										and (orden_compra_servicio.ubicacion = 'recibido' 
											or orden_compra_servicio.ubicacion = 'procesado')
										and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_recibidos.id_documento
										and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
										and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
										group by orden_compra_servicio.idorden_compra_servicio";*/
									$sql_consulta = mysql_query("select * from orden_compra_servicio,
										relacion_documentos_recibidos, 
										recibir_documentos 
										where 
										orden_compra_servicio.tipo = '".$estado_tipo."' 
										and (orden_compra_servicio.estado = 'conformado' 
											or orden_compra_servicio.estado = 'devuelto' 
											or orden_compra_servicio.estado = 'procesado' 
											or orden_compra_servicio.estado = 'parcial')
										and orden_compra_servicio.numero_orden like '%".$busqueda."%'
										and (orden_compra_servicio.ubicacion = 'recibido' 
											or orden_compra_servicio.ubicacion = 'procesado')
										and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_recibidos.id_documento
										and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
										and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
										group by orden_compra_servicio.idorden_compra_servicio
										order by orden_compra_servicio.codigo_referencia")
										 or die("consulta mala administracion compras".mysql_error());
								}else{
									/*echo "select * from orden_compra_servicio,tipos_documentos 
											where orden_compra_servicio.tipo = '".$estado_tipo."' 
												and (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' or orden_compra_servicio.estado = 'parcial')
												and orden_compra_servicio.numero_orden like '%".$busqueda."%'
												and orden_compra_servicio.ubicacion = '0'
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and tipos_documentos.modulo like '%-".$modulo."-%' 
												group by orden_compra_servicio.idorden_compra_servicio";*/
									$sql_consulta = mysql_query("select * from orden_compra_servicio,tipos_documentos 
											where orden_compra_servicio.tipo = '".$estado_tipo."' 
												and (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' or orden_compra_servicio.estado = 'parcial')
												and orden_compra_servicio.numero_orden like '%".$busqueda."%'
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and tipos_documentos.modulo like '%-".$modulo."-%' 
												group by orden_compra_servicio.idorden_compra_servicio
												order by orden_compra_servicio.codigo_referencia")
												or die("consulta mala compromete".mysql_error());
								}
								$num_consulta = mysql_num_rows($sql_consulta);
							}
							
							if ($modulo == 2 || $modulo == 6 || $modulo == 7){
								//echo "ALLA";
								
								$sql_consulta = mysql_query("select * from 
												orden_compra_servicio,
												relacion_documentos_recibidos, 
												recibir_documentos
												where 
												orden_compra_servicio.tipo = '".$estado_tipo."' 
											and (orden_compra_servicio.estado = 'conformado' 
													or orden_compra_servicio.estado = 'devuelto' 
													or orden_compra_servicio.estado = 'procesado' 
													or orden_compra_servicio.estado = 'parcial')
											and orden_compra_servicio.numero_orden like '%".$busqueda."%'
											and (orden_compra_servicio.ubicacion = 'recibido' 
												or orden_compra_servicio.ubicacion = 'conformado')
											and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_recibidos.id_documento
											and recibir_documentos.idrecibir_documentos = relacion_documentos_recibidos.idrecibir_documentos
											and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
											group by orden_compra_servicio.idorden_compra_servicio")
											or die("consulta mala presupuesto".mysql_error());
								$num_consulta = mysql_num_rows($sql_consulta);
							}
				}else{// SI EL TIPO DE DOCUMENTO ES PADRE
				//echo "ENTRO AQUIIIIIIIIIII";
							if ($modulo == 1 || $modulo == 13 || $modulo == 3 || $modulo == 16 || $modulo == 19){
							//echo "AQUI";
								$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")or die(mysql_error());
								$num_asociados = mysql_num_rows($sql_asociados);
								if ($num_tipo_documento == 0){
								//echo "AQUI";
									$query = "select * from orden_compra_servicio where numero_orden like '%".$busqueda."%' ";
									if($num_asociados > 1){
										$query .= " and (";
									}
									while($bus_asociados = mysql_fetch_array($sql_asociados)){
										$query .= " tipo = '".$bus_asociados["idtipos_documentos"]."'";
										if($num_asociados > 1){
											$query .= " ||";
										}
									}
									if($num_asociados > 1){
										$query = substr($query, 0, strlen($query) - 2);
										$query .=  ") ";
									}
									
									$query .= " and (estado = 'procesado' or estado = 'conformado' 
												or estado = 'devuelto' or estado = 'parcial')
												and (ubicacion = '0' or ubicacion = 'recibido') group by idorden_compra_servicio";
									//echo $query;
									$sql_consulta = mysql_query($query);
									$num_consulta = mysql_num_rows($sql_consulta);
								}
							}
							
							
							if ($modulo == 2 || $modulo == 6 || $modulo == 7){
								//echo "AQUI";
								$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")or die(mysql_error());
								$num_asociados = mysql_num_rows($sql_asociados);
								if ($num_tipo_documento == 0){
									$query = "select * from orden_compra_servicio,
													relacion_documentos_remision, remision_documentos 
													where 
													(orden_compra_servicio.estado = 'conformado' 
													or orden_compra_servicio.estado = 'devuelto' 
													or orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'parcial') ";
									
									if($num_asociados > 1){
										$query .= " and (";
									}
									while($bus_asociados = mysql_fetch_array($sql_asociados)){
										$query .= " orden_compra_servicio.tipo = '".$bus_asociados["idtipos_documentos"]."'";
										if($num_asociados > 1){
											$query .= "||";
										}
									}
									if($num_asociados > 1){
										$query = substr($query, 0, strlen($query) - 2);
										$query .=  ") ";
									}		
												
									$query .= "and orden_compra_servicio.numero_orden like '%".$busqueda."%'
										and (orden_compra_servicio.ubicacion = 'recibido' 
										or orden_compra_servicio.ubicacion = 'conformado')
										and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_remision.id_documento
										and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
										and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
										group by orden_compra_servicio.idorden_compra_servicio";
									
									//echo $query;
									$sql_consulta = mysql_query($query)or die("consulta mala presupuesto".mysql_error());
									$num_consulta = mysql_num_rows($sql_consulta);
								}
								//echo "aqui";
							}
							
							//echo "AQUI";
											
							if ($modulo == 4 || $modulo == 5 || $modulo == 12 || $modulo == 14){
							
								$sql_tipo_documento = mysql_query("select * from tipos_documentos where 
																						idtipos_documentos = ".$estado_tipo."
																						and modulo like '%-".$modulo."-%'")or die(mysql_error());
								$num_tipo_documento = mysql_num_rows($sql_tipo_documento);
								//echo $num_tipo_documento;
								if ($num_tipo_documento == 0){
									//echo "alla";
									//echo "select * from tipos_documentos where documento_asociado = '".$estado_tipo."'";
									$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")or die(mysql_error());
									$num_asociados = mysql_num_rows($sql_asociados);
									if($num_asociados != 0){
										$query = "select * from orden_compra_servicio,
											relacion_documentos_remision, remision_documentos 
											where (orden_compra_servicio.estado = 'conformado' or orden_compra_servicio.estado = 'devuelto' 
											or orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'parcial') ";
										if($num_asociados > 1){
										$query .= " and (";
										}
										while($bus_asociados = mysql_fetch_array($sql_asociados)){
											$query .= " orden_compra_servicio.tipo = '".$bus_asociados["idtipos_documentos"]."'";
											if($num_asociados > 1){
												$query .= "||";
											}
										}
										if($num_asociados > 1){
											$query = substr($query, 0, strlen($query) - 2);
											$query .=  ") ";
										}
											 
										$query .= " and orden_compra_servicio.numero_orden like '%".$busqueda."%'
											and (orden_compra_servicio.ubicacion = 'recibido' or orden_compra_servicio.ubicacion = 'procesado')
											and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_remision.id_documento
											and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
											and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
											group by orden_compra_servicio.idorden_compra_servicio";
										
										//echo $query;
										
										$sql_consulta = mysql_query($query)or die("consulta mala administracion compras".mysql_error());
										$num_consulta = mysql_num_rows($sql_consulta);
									}
								
								
								
								}else{
									$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")
																														or die(mysql_error());
									$num_asociados = mysql_num_rows($sql_asociados);
									if ($num_tipo_documento == 0){
										$query = "select * from orden_compra_servicio,tipos_documentos 
												where (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado' 
													or orden_compra_servicio.estado = 'devuelto' or orden_compra_servicio.estado = 'parcial') ";
										if($num_asociados > 1){
										$query .= " and (";
										}
										while($bus_asociados = mysql_fetch_array($sql_asociados)){
											$query .= " orden_compra_servicio.tipo = '".$bus_asociados["idtipos_documentos"]."'";
											if($num_asociados > 1){
												$query .= "||";
											}
										}
										if($num_asociados > 1){
											$query = substr($query, 0, strlen($query) - 2);
											$query .=  ") ";
										}					
										$query .= "and orden_compra_servicio.numero_orden like '%".$busqueda."%'
												   and orden_compra_servicio.ubicacion = '0'
												   and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												   and tipos_documentos.modulo like '%-".$modulo."-%' 
												   group by orden_compra_servicio.idorden_compra_servicio";
									
										$sql_consulta = mysql_query($query)or die("consulta mala compromete".mysql_error());
										$num_consulta = mysql_num_rows($sql_consulta);
									}
								}
								
							}
				}// FIN DE SI EL TIPO DE DOCUMENTO ES PADRE
				
				
			}else if($documento=="causa"){
				
				if ($modulo == 2){
					$sql_consulta = mysql_query("select * from orden_pago,
																relacion_documentos_recibidos, 
																recibir_documentos
												where 
												orden_pago.tipo = '".$estado_tipo."' 
												and (orden_pago.estado = 'procesado' 
													or orden_pago.estado = 'conformado' 
													or orden_pago.estado = 'devuelto')
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.ubicacion = 'recibido' 
													or orden_pago.ubicacion = 'conformado')
												and orden_pago.idorden_pago = relacion_documentos_recibidos.id_documento
												and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
												and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."'
												GROUP BY orden_pago.idorden_pago
												order by orden_pago.codigo_referencia")
											or die("consulta mala presupuesto causa".mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				if ($modulo == 4){	
					$sql_consulta = mysql_query("select * from orden_pago 
												where tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (estado = 'procesado' or estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.ubicacion = '0' group by idorden_pago") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				if ($modulo == 5){	
					$sql_consulta = mysql_query("select * from orden_pago,
												relacion_documentos_recibidos, 
												recibir_documentos 
												where 
												orden_pago.tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.estado = 'procesado' 
												or orden_pago.estado = 'conformado' 
												or orden_pago.estado = 'devuelto')
												and (orden_pago.ubicacion = 'recibido' or orden_pago.ubicacion = 'conformado')
												and orden_pago.idorden_pago = relacion_documentos_recibidos.id_documento
												and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
												and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
												GROUP BY orden_pago.idorden_pago
												order by orden_pago.codigo_referencia") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				
				if ($modulo == 12){	
					$sql_consulta = mysql_query("select * from orden_pago 
												where tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (estado = 'procesado' or estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.ubicacion = '0' group by idorden_pago") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				
				if ($modulo == 14){	
					$sql_consulta = mysql_query("select * from orden_pago 
												where tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (estado = 'procesado' or estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.ubicacion = '0' group by idorden_pago") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				
				if ($modulo == 6){
					$sql_consulta = mysql_query("select * from orden_pago,relacion_documentos_remision, remision_documentos 
												where orden_pago.tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.estado = 'procesado' or orden_pago.estado = 'conformado' or orden_pago.estado = 'devuelto' or orden_pago.estado = 'pagada')
												and (orden_pago.ubicacion = 'recibido' or orden_pago.ubicacion = 'conformado')
												and orden_pago.idorden_pago = relacion_documentos_remision.id_documento
												and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
												and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
												GROUP BY orden_pago.idorden_pago
												order by orden_pago.codigo_referencia")
												or die("consulta mala presupuesto causa".mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				if ($modulo == 7){
					$sql_consulta = mysql_query("select * from orden_pago,relacion_documentos_remision, remision_documentos 
												where orden_pago.tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.estado = 'procesado' or orden_pago.estado = 'conformado' or orden_pago.estado = 'devuelto' or orden_pago.estado = 'pagada')
												and (orden_pago.ubicacion = 'recibido')
												and orden_pago.idorden_pago = relacion_documentos_remision.id_documento
												and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
												and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
												GROUP BY orden_pago.idorden_pago 
												order by orden_pago.codigo_referencia")
												or die("consulta mala presupuesto causa".mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
			}
			

			if($num_consulta > 0){
				if($documento == "presupuesto"){
				
			?>
<input type="hidden" id="estado_consulta" name="estado_consulta" value="<?=$bus_consulta_remision["estado"]?>">
			<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
				  <tr>
					<td class="Browse"><div align="center">Incluir</div></td>
					<td class="Browse"><div align="center">Origen</div></td>
					<td class="Browse"><div align="center">Numero</div></td>
					<td class="Browse"><div align="center">Fecha</div></td>
					<td class="Browse"><div align="center">Justificacion</div></td>
				  </tr>
				  </thead>
					
					<?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						$sql_remision = mysql_query("select * from relacion_documentos_remision where id_documento = ".$bus_consulta[0]." 
																				and idtipos_documentos = '".$estado_tipo."' 
																				and idremision_documentos = ".$id_remision."");
						$num_remision = mysql_num_rows($sql_remision);
						if($num_remision == 0){
						$id = "id".$tabla;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta[$id]?>', document.getElementById('id_remision').value, document.getElementById('origenDocumentos').value, 'ingresar', '<?=$tabla?>', '<?=$estado_tipo?>')">
					
						<td class="Browse" align='center'>
							<img src="imagenes/validar.png" style=" cursor:pointer">
						</td>
						<td class='Browse' align='left'>
						<? 
						if($tabla == "traslados_presupuestarios" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Traslado";
						}else if($tabla == "traslados_presupuestarios" and $estado_tipo == 'procesado'){
							$origen = "Traslados Procesados";
						}else if($tabla == "creditos_adicionales" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Creditos Adicionales";
						}else if($tabla == "creditos_adicionales" and $estado_tipo == 'procesado'){
							$origen = "Creditos Adicionales Procesados";
						}else if($tabla == "disminucion_presupuesto" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Disminucion";
						}else if($tabla == "disminucion_presupuesto" and $estado_tipo == 'procesado'){
							$origen = "Disminuciones Procesadas";
						}else if($tabla == "rectificacion_presupuesto" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Rectificaciones";
						}else if($tabla == "rectificacion_presupuesto" and $estado_tipo == 'procesado'){
							$origen = "Rectificaciones Procesadas";
						}
						
						echo $origen;
						?>
						</td>
						<td class='Browse' align='left'><?=$bus_consulta["nro_solicitud"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_solicitud"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["justificacion"]?></td>
					 </tr>
					<?
						}
					}
					?>
				</table>
				
				<?
				}
				
				if($documento == "compromete"){
						?>
					<input type="hidden" id="estado_consulta" name="estado_consulta" value="<?=$bus_consulta_remision["estado"]?>">	
				<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
                
				<thead>
				  <tr>
					<td class="Browse"><div align="center">Incluir</div></td>
					<td class="Browse"><div align="center">Numero</div></td>
					<td class="Browse"><div align="center">Fecha</div></td>
					<td class="Browse"><div align="center">Beneficiario</div></td>
                    <td class="Browse"><div align="center">Monto</div></td>
				  </tr>
				  </thead>
					
					<?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						$sql_remision = mysql_query("select * from relacion_documentos_remision where id_documento = ".$bus_consulta[0]." 
																	and idtipos_documentos = '".$estado_tipo."' 
																	and idremision_documentos = ".$id_remision."");
						$num_remision = mysql_num_rows($sql_remision);
						if($num_remision == 0){
							$id = "id".$tabla;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta[$id]?>', document.getElementById('id_remision').value, document.getElementById('origenDocumentos').value, 'ingresar', '<?=$tabla?>', '<?=$estado_tipo?>')" style="cursor:pointer">
   
						<td class="Browse" align='center'><img src="imagenes/validar.png" style=" cursor:pointer"></td>
                        
						<td class='Browse' align='left'><?=$bus_consulta["numero_orden"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_orden"]?></td>
                        
						<td class='Browse' align='left'>
						<?
                            $sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = ".$bus_consulta["idbeneficiarios"]."");
							$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
                            echo $bus_beneficiarios["nombre"];
                            ?>
						
                        
                        </td>
                        <td class='Browse' align='right'><?=number_format($bus_consulta["total"],2,",",".")?></td>
					 </tr>
					<?
					}
					}
				
				}
			if($documento == "causa"){
						?>
					<input type="hidden" id="estado_consulta" name="estado_consulta" value="<?=$bus_consulta_remision["estado"]?>">	
				<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
                
				<thead>
				  <tr>
					<td class="Browse"><div align="center">Incluir</div></td>
					<td class="Browse"><div align="center">Numero</div></td>
					<td class="Browse"><div align="center">Fecha</div></td>
					<td class="Browse"><div align="center">Beneficiario</div></td>
                    <td class="Browse"><div align="center">Monto</div></td>
				  </tr>
				  </thead>
					
					<?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						$sql_remision = mysql_query("select * from relacion_documentos_remision where id_documento = ".$bus_consulta[0]." 
																		and idtipos_documentos = '".$estado_tipo."' 
																		and idremision_documentos = ".$id_remision."");
						$num_remision = mysql_num_rows($sql_remision);
						if($num_remision == 0){
							
							$id = "id".$tabla;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta[$id]?>', document.getElementById('id_remision').value, document.getElementById('origenDocumentos').value, 'ingresar', '<?=$tabla?>', '<?=$estado_tipo?>')">

						<td class="Browse" align='center'><img src="imagenes/validar.png" style=" cursor:pointer"></td>
                        
						<td class='Browse' align='left'><?=$bus_consulta["numero_orden"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_orden"]?></td>
                        
						<td class='Browse' align='left'>
						<?
                            $sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = ".$bus_consulta["idbeneficiarios"]."");
							$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
                            echo $bus_beneficiarios["nombre"];
                            ?>
						
                        
                        </td>
                        <td class='Browse' style="text-align:right"><?=number_format($bus_consulta["total"],2,",",".")?></td>
					 </tr>
					<?
					}
					}
				
				}
			}else{
			echo "<center>Sin Resultados</center>";
			}
	}else{
	echo "<strong>Remision Recibida</strong>";
	}
	}else{
	echo "<strong>Remision Enviada</strong>";
	}
}




//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************


if($ejecutar == "listaSeleccionadosDocumentos"){

if ($idorden_pago != ''){
	$sql_eliminar_en_elaboracion = mysql_query("delete from relacion_documentos_generar_comprobante
													where relacion_documentos_generar_comprobante.idorden_pago= '".$idorden_pago."'
													and relacion_documentos_generar_comprobante.estado = 'elaboracion'");
	
		
	$sql_insercion = mysql_query("insert into relacion_documentos_generar_comprobante(
																							idautorizar_generar_comprobante,
																							idorden_pago,
																							estado,
																							status,
																							usuario,
																							fechayhora
																								)values(
																										'".$id_remision."',
																										'".$idorden_pago."',
																										'elaboracion',
																										'a',
																										'".$login."',
																										'".date("Y-m-d")."')")or die(mysql_error());
		
		
		
}
	$sql_consulta = mysql_query("select * from relacion_documentos_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."")or die(mysql_error());
	$num_consulta = mysql_num_rows($sql_consulta); 
	if($num_consulta > 0){
		$sql_consulta_remision = mysql_query("select * from autorizar_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."")or die(mysql_error());
		$bus_consulta_remision = mysql_fetch_array($sql_consulta_remision);
	?>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
           <tr>        
            <td class="Browse"><div align="center">Numero</div></td>
            <td class="Browse"><div align="center">Fecha</div></td>
            <td class="Browse"><div align="center">Beneficiario</div></td>
            <td class="Browse"><div align="center">Exento</div></td>
            <td class="Browse"><div align="center">Base</div></td>
            <td class="Browse"><div align="center">Impuesto</div></td>
            <td class="Browse"><div align="center">Total</div></td>
            <? //<td class="Browse"><div align="center">%Reten.</div></td> ?>
            <td class="Browse"><div align="center">Monto Retenido</div></td>
            <?
           	if($bus_consulta_remision["estado"] == "elaboracion"){
		   	?>
            <td class="Browse"><div align="center">Eliminar</div></td>
            <?
            }
			?>
          </tr>
          </thead>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$sql_consulta2 = mysql_query("select * from orden_pago where idorden_pago = '".$bus_consulta["idorden_pago"]."'");
		$bus_consulta2 = mysql_fetch_array($sql_consulta2);
		//echo "select * from ".$bus_consulta["tabla"]." where ".$idtabla." = ".$bus_consulta["id_documento"]."";
	?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                
                <td class='Browse' align='left'><?=$bus_consulta2["numero_orden"]?></td>
                <td class='Browse' align='left'><?=$bus_consulta2["fecha_orden"]?></td>
                <td class='Browse' align='left'><?
						$sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_consulta2["idbeneficiarios"]."'")or die(mysql_error());
						$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
						echo $bus_beneficiarios["nombre"];
						?>
				</td>
                <td class='Browse' style="text-align:right"><?=number_format($bus_consulta2["exento"],2,",",".")?></td>
                <td class='Browse' style="text-align:right"><?=number_format($bus_consulta2["sub_total"],2,",",".")?></td>
                <td class='Browse' style="text-align:right"><?=number_format($bus_consulta2["impuesto"],2,",",".")?></td>
                <td class='Browse' style="text-align:right"><?=number_format($bus_consulta2["total"],2,",",".")?></td>
                 <? /*
				 $sql_retenido = mysql_query("select relacion_retenciones.porcentaje_aplicado
													from relacion_orden_pago_retencion, relacion_retenciones,retenciones
													where relacion_orden_pago_retencion.idorden_pago = '".$bus_consulta["idorden_pago"]."'
													and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
													and relacion_retenciones.idretenciones = retenciones.idretenciones
													
															");
					$bus_retenido = mysql_fetch_array($sql_retenido);
				?>
                <td class='Browse' style="text-align:right"><?=number_format($bus_retenido["porcentaje_aplicado"],2,",",".")?></td>
                
                <?
				*/
				$sql_suma_total_retenido = mysql_query("select SUM(relacion_retenciones.monto_retenido) as monto_retenido from relacion_orden_pago_retencion,
																			retenciones,
																			
																			relacion_retenciones
																		 where relacion_orden_pago_retencion.idorden_pago = '".$bus_consulta["idorden_pago"]."'
																		 and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
																		 and relacion_retenciones.idretenciones = retenciones.idretenciones
																		 ");
					$bus_suma_total_retenido = mysql_fetch_array($sql_suma_total_retenido);	
				?>
                <td class='Browse' style="text-align:right"><?=number_format($bus_suma_total_retenido["monto_retenido"],2,",",".")?></td>
                
                <?
					
           			if($bus_consulta_remision["estado"] == "elaboracion"){
		   			?>
                    <td class="Browse" align='center'>
                <img src="imagenes/delete.png" style=" cursor:pointer" onclick="eliminarListaSeleccionadosDocumentos('<?=$bus_consulta["idorden_pago"]?>', <?=$id_remision?>)"> </td>
                <?
                }
				?>
                
             
         	 </tr>
			<?
		}
	?>
	</table>
	<?
	}else{
	echo "<center>No hay Documentos Seleccionados</center>";
	}
	
}



//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
//***********************************************************************************************************************************************

if($ejecutar == "eliminarListaSeleccionadosDocumentos"){
	echo $idorden_pago;
	echo " ".$id_remision;
		//echo "delete relacion_documentos_remision_documentos where id_documento = ".$id." and tipo = ".$tipo." and idremision_documentos = ".$id_remision."";
		$sql_eliminar = mysql_query("delete from relacion_documentos_generar_comprobante where idorden_pago = '".$idorden_pago."' 
											and idautorizar_generar_comprobante = '".$id_remision."'")or die(mysql_error());
		registra_transaccion("Eliminar Documentos Seleccionados para Elaborar comprobante (".$id_remision.")",$login,$fh,$pc,'remision_documentos');

}


//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
//***********************************************************************************************************************************************



if($ejecutar == "consultarCantidadDocumentos"){
		$sql_cantidad = mysql_query("select * from relacion_documentos_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."");
		$num_cantidad = mysql_num_rows($sql_cantidad);
		$sql_actualizar = mysql_query("update autorizar_generar_comprobante set numero_documentos_enviados = ".$num_cantidad." 
															where idautorizar_generar_comprobante = ".$id_remision."");
		echo "<strong>".$num_cantidad."</strong>";
}



//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
//***********************************************************************************************************************************************



if($ejecutar == "remitirDocumentos"){
	$sql_relacion = mysql_query("select * from relacion_documentos_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."");
	$num_relacion = mysql_num_rows($sql_relacion);
	if($num_relacion != 0){
		
	  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
	  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  $numero_documento = $bus_configuracion["relacion_comprobantes"]."-".$_SESSION["anio_fiscal"];
		

	
		$sql_existe_num = mysql_query("select * from autorizar_generar_comprobante where numero_documento = '".$numero_documento."'");
		$num_existe_num = mysql_num_rows($sql_existe_num);
		
		while($num_existe_num > 0){
			$sql_configuracion = mysql_query("select * from configuracion_tesoreria");
	  		$bus_configuracion = mysql_fetch_array($sql_configuracion);
	  		$numero_documento = $bus_configuracion["relacion_comprobantes"]."-".$_SESSION["anio_fiscal"];
			
			$sql_existe_num = mysql_query("select * from autorizar_generar_comprobante where numero_documento = '".$numero_documento."'");
			$num_existe_num = mysql_num_rows($sql_existe_num);
		
			$sql_actualizar_configuracion = mysql_query("update configuracion_tesoreria set relacion_comprobantes = relacion_comprobantes+1");
			$oficina = "Tesoreria";
			
		
		}
			
		$sql_actualizar = mysql_query("update autorizar_generar_comprobante set numero_documento = '".$numero_documento."',
																	fecha_envio = '".$fecha_validada."',
																	estado = 'enviado' where idautorizar_generar_comprobante = ".$id_remision."")or die(mysql_error());
																		
		$sql_relacion = mysql_query("select * from relacion_documentos_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."");
		//$sql_actualizar_remision_documentos = mysql_query("update remision_documentos set estado = 'enviado'  where idremision_documentos = ".$id_remision."");
		
		while($bus_relacion = mysql_fetch_array($sql_relacion)){
			
			$sql_actualizar_relacion = mysql_query("update relacion_documentos_generar_comprobante set estado = 'enviado' 
													where idrelacion_documentos_generar_comprobante = ".$bus_relacion["idrelacion_documentos_generar_comprobante"]."");
		}
		echo "<strong>".$numero_documento."</strong>";
		registra_transaccion("Remitir documento desde ".$oficina." (".$numero_documento.")",$login,$fh,$pc,'remision_documentos');
	}else{
		registra_transaccion("Remitir documentos ERROR (no tiene documentos)",$login,$fh,$pc,'remision_documentos');
		echo "noTieneDocumentos";
	}
}





if($ejecutar == "anularRemision"){
	$sql_actualizar = mysql_query("update autorizar_generar_comprobante set estado = 'anulado' where idautorizar_generar_comprobante = ".$id_remision."");
	$sql_relacion = mysql_query("select * from relacion_documentos_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."");
	
	while($bus_relacion = mysql_fetch_array($sql_relacion)){
		
		$sql_actualizar_relacion = mysql_query("update relacion_documentos_generar_comprobante set estado = 'anulado' 
								where idrelacion_documentos_generar_comprobante = ".$bus_relacion["idrelacion_documentos_generar_comprobante"]."");
		
		
		
	}

		$oficina = "Tesoreria";
	
	registra_transaccion("Anular Relacion Generar Comprobante de ".$oficina." (".$id_remision.")",$login,$fh,$pc,'remision_documentos');
}



if($ejecutar == "consultarRemisiones"){
	
$sql = mysql_query("select * from autorizar_generar_comprobante where idautorizar_generar_comprobante = ".$id_remision."");
$bus_consulta = mysql_fetch_array($sql);															

$sql_banco = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$bus_consulta["idcuenta_bancaria"]."'");
$bus_banco = mysql_fetch_array($sql_banco);

$fecha_anulacion='';
switch($bus_consulta["estado"]){
	  case "elaboracion":
		  $mostrar_estado = "Elaboraci&oacute;n";
		  break;
	  case "enviado":
	  	  $mostrar_estado = "Enviado";
		  break;
	  case "recibido":
	  	  $mostrar_estado = "Recibido";
		  break;
	  case "anulado":
	  	  $mostrar_estado = "Anulado";
		  break;
	  }

	echo $bus_consulta["idautorizar_generar_comprobante"]	."|.|".
		$bus_consulta["iddependencia_origen"]				."|.|".
		$bus_consulta["numero_documento"]					."|.|".
		$bus_consulta["estado"]								."|.|".
		'<STRONG>'.$mostrar_estado.'</strong>'				."|.|".
		$bus_consulta["fecha_envio"]						."|.|".
		$bus_consulta["iddependencia_destino"]				."|.|".
		$bus_consulta["asunto"]								."|.|".
		$bus_consulta["justificacion"]						."|.|".
		$bus_consulta["idcuenta_bancaria"]					."|.|".
		$bus_consulta["tipo_comprobante"]					."|.|".
		$bus_consulta["numero_documentos_enviados"]			."|.|".
		$bus_banco["idbanco"]								."|.|";
																
}







?>
