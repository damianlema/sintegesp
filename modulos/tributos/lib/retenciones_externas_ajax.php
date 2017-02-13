<?
session_start();
include("../../../conf/conex.php");
$conexion = Conectarse();
extract($_POST);


 
if($ejecutar == "registrarDatosBasicos"){
	$sql_ingresar = mysql_query("insert into retenciones (idbeneficiarios, idente_gubernamental, tipo_retencion, estado, fecha_retencion)
														VALUES
														('".$beneficiario."', '".$ente."', '1', 'elaboracion', '".$fecha_validada."')");
	echo mysql_insert_id();
}

if($ejecutar == "actualizarFecha"){
	$sql_ingresar = mysql_query("update retenciones set fecha_aplicacion_retencion = '".$fecha_validada."',
														fecha_retencion = '".$fecha_validada."'
													where idretenciones = '".$id_retencion."'");
}

if($ejecutar == "ingresarFactura"){
	$sql_consulta = mysql_query("select * from retenciones, relacion_retenciones_externas 
															where relacion_retenciones_externas.numero_factura = '".$nro_factura."'
															and relacion_retenciones_externas.idtipo_retencion = '".$tipo_retencion."'
															and relacion_retenciones_externas.idretencion = retenciones.idretenciones
															and retenciones.estado <> 'anulado'
															and retenciones.idbeneficiarios = '".$id_beneficiario."'")or die(mysql_error());
	$num_consulta = mysql_num_rows($sql_consulta);
	$sql_maximo_facturas = mysql_query("select * from relacion_retenciones_externas where idretencion = '".$idretencion."' 
									   												and idtipo_retencion = '".$tipo_retencion."'");
	$num_maximo_facturas = mysql_num_rows($sql_maximo_facturas);
	
	$sql_configuracion = mysql_query("select nro_linea_comprobante from configuracion_tributos");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	
	if($num_maximo_facturas == $bus_configuracion["nro_linea_comprobante"]){
		echo "masdoce";
	}else{
		if($num_consulta == 0){
			$sql_ingresar_facturas = mysql_query("insert into relacion_retenciones_externas(
																					numero_orden,
																					numero_factura,
																					numero_control,
																					fecha_factura,
																					idtipo_retencion,
																					codigo_islr,
																					base_calculo,
																					alicuota,
																					factor,
																					monto_retenido,
																					porcentaje,
																					status,
																					fechayhora,
																					usuario,
																					idretencion,
																					exento,
																					sub_total,
																					impuesto,
																					total,
																					divisor,
																					fecha_orden,
																					concepto_orden,
																					monto_contrato)VALUES(
																									'".$nro_orden."',
																									'".$nro_factura."',
																									'".$nro_control."',
																									'".$fecha_factura."',
																									'".$tipo_retencion."',
																									'".$codigo_islr."',
																									'".$base_calculo."',
																									'".$alicuota."',
																									'".$factor."',
																									'".$total_retenido."',
																									'".$porcentaje."',
																									'a',
																									'".$fh."',
																									'".login."',
																									'".$idretencion."',
																									'".$exento."',
																									'".$sub_total."',
																									'".$impuesto."',
																									'".$total."',
																									'".$divisor."',
																									'".$fecha_orden."',
																									'".$concepto_orden."',
																									'".$monto_contrato."')")or die(mysql_error());
		}else{
			echo "existe";
		}
	}
}







if($ejecutar == "listarFacturas"){
$sql_retenciones = mysql_query("select * from retenciones where idretenciones = '".$id_retencion."'");
$bus_retenciones = mysql_fetch_array($sql_retenciones);
	$sql_facturas = mysql_query("select tipo_retencion.descripcion as nombre_tipo_retencion,
										relacion_retenciones_externas.numero_orden as numero_orden,
										relacion_retenciones_externas.numero_control as numero_control,
										relacion_retenciones_externas.numero_factura as numero_factura,
										relacion_retenciones_externas.fecha_factura as fecha_factura,
										relacion_retenciones_externas.monto_retenido as monto_retenido,
										relacion_retenciones_externas.idrelacion_retenciones_externas as idrelacion_retenciones_externas
											 from relacion_retenciones_externas, tipo_retencion
											 where 
											 relacion_retenciones_externas.idretencion = '".$id_retencion."'
											 and tipo_retencion.idtipo_retencion = relacion_retenciones_externas.idtipo_retencion");
	?>
		<table class="Main" cellpadding="0" cellspacing="0" width="65%" align="center">
				<tr>
					<td>
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
								  <td width="18%" align="center" class="Browse">Nro Orden</td>
								  <td width="12%" align="center" class="Browse">Nro Factura</td>
								  <td width="13%" align="center" class="Browse">Nro Control</td>
								  <td width="10%" align="center" class="Browse">Fecha Factura</td>
                                  <td width="27%" align="center" class="Browse">Tipo Retencion</td>
                                  <td width="13%" align="center" class="Browse">Total Retenido</td>
								  <?
                                  if($bus_retenciones["estado"] == "elaboracion"){
								  ?>
                                  <td colspan="2" align="center" class="Browse">Acci&oacute;n</td>
                                  <?
                                  }
								  ?>
							  </tr>
							</thead>
								<?
                                while($bus_facturas = mysql_fetch_array($sql_facturas)) 
                                { 
                                ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                        	<td class="Browse">&nbsp;<?=$bus_facturas["numero_orden"]?></td>
                                            <td class="Browse">&nbsp;<?=$bus_facturas["numero_factura"]?></td>
                                            <td class="Browse">&nbsp;<?=$bus_facturas["numero_control"]?></td>
                                            <td class="Browse">&nbsp;<?=$bus_facturas["fecha_factura"]?></td>
                                            <td class="Browse">&nbsp;<?=$bus_facturas["nombre_tipo_retencion"]?></td>
                                            <td align="right" class="Browse">&nbsp;<?=number_format($bus_facturas["monto_retenido"],2,",",".")?></td>
                                            <?
											  if($bus_retenciones["estado"] == "elaboracion"){
											  ?>
                                            <td width="3%" align="center" class="Browse">
                                            	<img src="imagenes/modificar.png" onclick="mostrarModificarFacturas('<?=$bus_facturas["idrelacion_retenciones_externas"]?>')" style="cursor:pointer" id="imagen_modificar_factura">
                                            </td>
                                          <td width="4%" align="center" class="Browse"><img src="imagenes/delete.png" style="cursor:pointer" onclick="eliminarFactura('<?=$bus_facturas["idrelacion_retenciones_externas"]?>')" id="imagen_eliminar_factura"></td>
                                          <?
                                          }
										  ?>
                          </tr>
								<?
                                }
								?>
						</table>
						</form>
					</td>
				</tr>
			</table>
	<?
}















if($ejecutar == "mostrarMontos"){
	$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$idtipo_retencion."'");
	$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
	$base = $bus_tipo_retencion["base_calculo"];	
	$factor = ($bus_tipo_retencion["unidad_tributaria"]*$bus_tipo_retencion["porcentaje"]/100)*$bus_tipo_retencion["factor_calculo"];
	$porcentaje = $bus_tipo_retencion["porcentaje"];
	$divisor = $bus_tipo_retencion["divisor"];
	
	echo $base."|.|".$factor."|.|".$porcentaje."|.|".$divisor;
}



if($ejecutar == "mostrarModificarFacturas"){
	$sql_relacion_retenciones = mysql_query("select * from relacion_retenciones_externas where idrelacion_retenciones_externas = '".$idrelacion_retencion."'");
	$bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones);
	echo $bus_relacion_retenciones["numero_orden"]."|.|".
		 $bus_relacion_retenciones["numero_factura"]."|.|".
		 $bus_relacion_retenciones["numero_control"]."|.|".
		 $bus_relacion_retenciones["fecha_factura"]."|.|".
		 $bus_relacion_retenciones["idtipo_retencion"]."|.|".
		 $bus_relacion_retenciones["codigo_islr"]."|.|".
		 $bus_relacion_retenciones["base_calculo"]."|.|".
		 $bus_relacion_retenciones["alicuota"]."|.|".
		 $bus_relacion_retenciones["factor"]."|.|".
		 $bus_relacion_retenciones["monto_retenido"]."|.|".
		 $bus_relacion_retenciones["porcentaje"]."|.|".
		 $bus_relacion_retenciones["exento"]."|.|".
		 $bus_relacion_retenciones["sub_total"]."|.|".
		 $bus_relacion_retenciones["impuesto"]."|.|".
		 $bus_relacion_retenciones["total"]."|.|".
		 $bus_relacion_retenciones["divisor"]."|.|".
		 $bus_relacion_retenciones["fecha_orden"]."|.|".
		 $bus_relacion_retenciones["concepto_orden"]."|.|".
		 $bus_relacion_retenciones["monto_contrato"];
		 
}



if($ejecutar == "eliminarFactura"){
	$sql_eliminar = mysql_query("delete from relacion_retenciones_externas where idrelacion_retenciones_externas = '".$idrelacion_retencion."'");	
}




if($ejecutar == "procesarRetencion"){
	$sql_procesar = mysql_query("update retenciones set estado = 'procesado' where idretenciones = '".$id_retencion."'")or die(mysql_error());
	
	$acumulador = 0;
		
	$fecha_retencion = $fecha_validada;
	
	$bus_actualizar_fecha_retencion = mysql_query("update retenciones set fecha_aplicacion_retencion = '".$fecha_retencion."' 
												  			where idretenciones = '".$id_retencion."'")or die("ERRROR ACTUALIZANDO FECHA: ".mysql_error());

	//SELECCIONO LAS RETENCIONES APLICADAS EN ESA RETENCION EXTERNA Y QUE DEBEN TENER EL MISMO NUMERO DE COMPROBANTE
	$sql_consultar_relacion_retencion = mysql_query("select * from relacion_retenciones_externas, tipo_retencion 
											where relacion_retenciones_externas.idretencion = '".$id_retencion."'
												and tipo_retencion.idtipo_retencion = relacion_retenciones_externas.idtipo_retencion
												and tipo_retencion.nombre_comprobante <> 'NA'")or die("ERRROR SELECCIONANDO RETENCIONES EXTERNAS: ".mysql_error());
	
	//INICIO BUCLE CON TODAS LAS FACTURAS CARGADAS EN LA RETENCION EXTERNA
	while($bus_consultar_relacion_retencion = mysql_fetch_array($sql_consultar_relacion_retencion)){
		$seguir = 'si';
		//valido si a ese tipo de retencion ya se le asigno numero se lo actualizo a odas las retenciones iguales
		$sql_validar_retencion = mysql_query("select * from relacion_retenciones_externas
													where idretencion = '".$id_retencion."'
														and idtipo_retencion = '".$bus_consultar_relacion_retencion["idtipo_retencion"]."'
														and numero_retencion <> '0'")or die("ERRROR ".mysql_error());
		
		$num_validar_retencion = mysql_num_rows($sql_validar_retencion);
		//valida que a este tipo de retencion no se le haya asignado numero 
		if ($num_validar_retencion == 0){
			
			$idrelacion_retenciones_externas = $bus_consultar_relacion_retencion["idrelacion_retenciones_externas"];
			//busco el tipo de retencion aplicada para saber si tiene numero propio o asociado
			$sql_consultar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_relacion_retencion["idtipo_retencion"]."'")or die("ERRROR TIPO DE RETENCION: ".mysql_error());
			$bus_consultar_tipo_retencion = mysql_fetch_array($sql_consultar_tipo_retencion);
			//si la retencion tiene numero propio
			if($bus_consultar_tipo_retencion["asociado"] == 0){
				
				$numero_contador = $bus_consultar_tipo_retencion["numero_documento"];
				$retencion_a_modificar = $bus_consultar_tipo_retencion["idtipo_retencion"];
				$relacion_retencion_tipo_retencion = $bus_consultar_tipo_retencion["idtipo_retencion"];
			}else{
				
				$sql_consultar_asociado = mysql_query("select * from tipo_retencion 
													  where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'")
														or die("ERRROR TIPO DE RETENCION 2: ".mysql_error());;
				$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);
				//valido que esa retencion asociada ya otorgo numero de retencion
				
//*********************************************************************************************************************************************************				
// AQUI DEBE IR UNA CONSULTA QUE TRAIGA LAS RETENCIONES QUE TENGA EL MISMO ASOCIADO Y EL ASOCIADO PADRE 
//*********************************************************************************************************************************************************
				$validar_numero_asociado = mysql_query("select * from relacion_retenciones_externas, tipo_retencion 
													   where relacion_retenciones_externas.idretencion = '".$id_retencion."'
													   and relacion_retenciones_externas.idtipo_retencion = '".$bus_consultar_asociado["idtipo_retencion"]."'
													   and relacion_retenciones_externas.numero_retencion <> '0'");
				$num_numero_asociado = mysql_num_rows($validar_numero_asociado);
				
				if ($num_numero_asociado == 0){
					
					$numero_contador = $bus_consultar_asociado["numero_documento"];
					$relacion_retencion_tipo_retencion = $bus_consultar_tipo_retencion["idtipo_retencion"];
					$retencion_a_modificar = $bus_consultar_asociado["idtipo_retencion"];
					$seguir = 'si';
					
				}else{
					$bus_numero_asociado = mysql_fetch_array($validar_numero_asociado);
					
					$sql_relacion_retenciones = mysql_query("update relacion_retenciones_externas 
													set periodo = '".$bus_numero_asociado["periodo"]."', 
													numero_retencion = '".$bus_numero_asociado["numero_retencion"]."',
													numero_retencion_referencia = 900000000000+'".$bus_numero_asociado["numero_retencion"]."' 
								where idrelacion_retenciones_externas = '".$bus_consultar_relacion_retencion["idrelacion_retenciones_externas"]."' ")
																	or die(mysql_error());
					$seguir = 'no';
				}
//*******************************************************************************************************************************************************
			}
			
			if ($seguir == 'si'){
				//variable con el numero a asignar a la retencion
				$numero_documento = $numero_contador;
				//valido que el numero de retención  no haya sido asignado a una retencion similar
				$sql_consultar_documento = mysql_query("select * from relacion_retenciones_externas 
													   where idtipo_retencion = '".$retencion_a_modificar."' 
													   and numero_retencion = '".$numero_documento."'")or die("ERRROR SELECIONANDO RELACION RETENCIONES EXTERNAS: ".mysql_error());;
				$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
				//si el numero de comprobante ya fue asignado
				if($num_consultar_documento != 0){
					//entro en cliclo hasta conseguir un numero de comprobante que no se haya asignado
					$acumulador=1;
					while($acumulador > 0){
						
						$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion 
											set numero_documento = numero_documento + 1 
											where idtipo_retencion = '".$retencion_a_modificar."'")
											or die("ERRROR ACTUALIZANDO TIPO DE RETENCION: ".mysql_error());;
						$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion 
													where idtipo_retencion = '".$retencion_a_modificar."'");
						$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
						$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
						//tomo el nuevo numero a asignar para validarlos
						$numero_documento = $numero_contador;
						//select para validar que el nuevo numero no exista
						$sql_consultar_documento = mysql_query("select * from relacion_retenciones_externas 
															   		where idtipo_retencion = '".$retencion_a_modificar."' 
													   				and numero_retencion = '".$numero_documento."'")or die("ERRROR SELECCIONANDO RELACION RETENCIONES EXTERNAS: ".mysql_error());;
						$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
						if($num_consultar_documento == 0){
							//valido que no exista en la tabla de retenciones internas
							$sql_consultar_interna = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".			
													$retencion_a_modificar."' and numero_retencion = '".$numero_documento."'");
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
				}else{
					//si el numero esta disponible lo valido en las retenciones internas para validar que no exista
					$sql_consultar_interna = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".			
													$retencion_a_modificar."' and numero_retencion = '".$numero_documento."'");
					$num_consultar_interna = mysql_num_rows($sql_consultar_interna);
					if($num_consultar_interna != 0){
						$acumulador=1;
						while($acumulador > 0){
							
							$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion 
												set numero_documento = numero_documento + 1 
												where idtipo_retencion = '".$retencion_a_modificar."'")
												or die("ERRROR ACTUALIZANDO TIPO DE RETENCION: ".mysql_error());;
							$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion 
														where idtipo_retencion = '".$retencion_a_modificar."'");
							$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
							$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
							//tomo el nuevo numero a asignar para validarlos
							$numero_documento = $numero_contador;
							//select para validar que el nuevo numero no exista
							$sql_consultar_documento = mysql_query("select * from relacion_retenciones
																		where idtipo_retencion = '".$retencion_a_modificar."' 
													   					and numero_retencion = '".$numero_documento."'")or die("ERRROR SELECCIONANDO RELACION RETENCIONES EXTERNAS: ".mysql_error());;
							$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
							if($num_consultar_documento == 0){
								//valido que no exista en la tabla de retenciones internas
								$sql_consultar_externa = mysql_query("select * from relacion_retenciones_externas 
																	 where idtipo_retencion = '".$retencion_a_modificar."' 
																	 and numero_retencion = '".$numero_documento."'");
								$num_consultar_externa = mysql_num_rows($sql_consultar_interna);
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
				echo "numero retencion ".$numero_documento." ";
				echo $acumulador;
				//si el numero esta validad se lo asigno al tipo de retencion
				if ($acumulador == 0){	
					$fecha = explode("-", $fecha_retencion);
					$sql_relacion_retenciones = mysql_query("update relacion_retenciones_externas 
										set periodo = '".$fecha[0].$fecha[1]."', 
										numero_retencion = '".$numero_documento."',
										numero_retencion_referencia = 900000000000+'".$numero_documento."' 
										where idrelacion_retenciones_externas = '".$idrelacion_retenciones_externas."'")or die(mysql_error());
				
					if($sql_relacion_retenciones){
						//echo "ENTRO";
						$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion 
															set numero_documento = numero_documento + 1 
															where idtipo_retencion = '".$retencion_a_modificar."'");	
					}
					$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
														(idorden_pago,
														idretenciones, 
														idtipo_retencion,
														numero_retencion,
														fecha_retencion,
														periodo,
														estado)VALUES('0',
																	'".$id_retencion."',
																	'".$retencion_a_modificar."',
																	'".$numero_documento."',
																	'".date("Y-m-d")."',
																	'".$fecha[0].$fecha[1]."',
																	'procesado')");
				}
			}
		}else{
			echo '// ENTRA AQUI SI EL TIPO DE RETENCION YA TIENE NUMERO ASIGNADO PARA ACTUALIZARCELO';
			$existe_validar_retencion = mysql_fetch_array($sql_validar_retencion)or die(mysql_error());
			$sql_relacion_retenciones = mysql_query("update relacion_retenciones_externas 
														set periodo = '".$existe_validar_retencion["periodo"]."', 
														numero_retencion = '".$existe_validar_retencion["numero_retencion"]."',
														numero_retencion_referencia = 900000000000+'".$existe_validar_retencion["numero_retencion"]."'
									where idrelacion_retenciones_externas = '".$bus_consultar_relacion_retencion["idrelacion_retenciones_externas"]."' ")or die(mysql_error());
		}
	}
							
	//CIERRE DEL BUCLE CON LAS FACTURAS CARGAS EN LA RETENCION EXTERNA						
							
		$sql_configuracion = mysql_query("select * from configuracion_tributos");
		$bus_configuracion = mysql_fetch_array($sql_configuracion);
		$nro_retencion = $bus_configuracion["nro_retencion_externa"];
		$numero_retencion = "RTN-".$_SESSION["anio_fiscal"]."-".$nro_retencion;
		$sql_consultar_numero = mysql_query("select * from retenciones where nro_retencion = '".$numero_retencion."'");
		$num_consultar_numero = mysql_num_rows($sql_consultar_numero);
		
		while($num_consultar_numero != 0){
			$sql_configuracion = mysql_query("update configuracion_tributos set nro_retencion_externa = nro_retencion_externa + 1");
			$sql_configuracion = mysql_query("select * from configuracion_tributos");
			$bus_configuracion = mysql_fetch_array($sql_configuracion);
			$nro_retencion = $bus_configuracion["nro_retencion_externa"];
			$numero_retencion = "RTN-".$_SESSION["anio_fiscal"]."-".$nro_retencion;
			$sql_consultar_numero = mysql_query("select * from retenciones where nro_retencion = '".$numero_retencion."'");
			$num_consultar_numero = mysql_num_rows($sql_consultar_numero);
		}
		
		$sql_actualizar = mysql_query("update retenciones set nro_retencion = '".$numero_retencion."' where idretenciones = '".$id_retencion."'");
		$sql_configuracion = mysql_query("update configuracion_tributos set nro_retencion_externa = nro_retencion_externa + 1");
	
}



if($ejecutar == "consultarRetencion"){
	$sql_consultar = mysql_query("select beneficiarios.idbeneficiarios as idbeneficiarios,
										entes_gubernamentales.identes_gubernamentales as identes_gubernamentales,
										beneficiarios.nombre as nombe_beneficiario,
										entes_gubernamentales.nombre as nombre_entes,
										retenciones.estado as estado,
										retenciones.nro_retencion as nro_retencion,
										retenciones.fecha_retencion as fecha_retencion
											from 
										retenciones, beneficiarios, entes_gubernamentales
										    where 
										retenciones.idretenciones = '".$id_retencion."'
										and retenciones.idbeneficiarios = beneficiarios.idbeneficiarios
										and retenciones.idente_gubernamental = entes_gubernamentales.identes_gubernamentales")or die(mysql_error());
	$bus_consultar = mysql_fetch_array($sql_consultar);
	echo $bus_consultar["idbeneficiarios"]."|.|".
		 $bus_consultar["identes_gubernamentales"]."|.|".
		 $bus_consultar["nombe_beneficiario"]."|.|".
		 $bus_consultar["nombre_entes"]."|.|".
		 $bus_consultar["estado"]."|.|".
		 $bus_consultar["nro_retencion"]."|.|".
		 $bus_consultar["fecha_retencion"];
		 
}





if($ejecutar == "modificarFactura"){
	$sql_actualizar = mysql_query("update relacion_retenciones_externas
																set numero_orden = '".$numero_orden."',
																fecha_orden = '".$fecha_orden."',
																numero_factura = '".$numero_factura."',
																numero_control = '".$numero_control."',
																fecha_factura = '".$fecha_factura."',
																idtipo_retencion = '".$idtipo_retencion."',
																codigo_islr = '".$codigo_islr."',
																base_calculo = '".$base_calculo."',
																alicuota = '".$alicuota."',
																factor = '".$factor."',
																monto_retenido = '".$monto_retenido."',
																porcentaje = '".$porcentaje."',
																divisor = '".$divisor."',
																concepto_orden = '".$concepto_orden."',
																monto_contrato = '".$monto_contrato."'
																where idrelacion_retenciones_externas = '".$idrelacion_retencion."'");
}



if($ejecutar == "anularRetencion"){
	$sql_anular = mysql_query("update retenciones set estado = 'anulado' where idretenciones = '".$id_retencion."'");
	$sql_anular = mysql_query("update comprobantes_retenciones set estado = 'anulado' where idretenciones = '".$id_retencion."'");
}

if($ejecutar == "eliminarRetencion"){
	$sql_eliminar = mysql_query("delete from retenciones where idretenciones = '".$id_retencion."'");
	$sql_eliminar = mysql_query("delete from relacion_retenciones_externas where idretencion = '".$id_retencion."'");
	$sql_eliminar = mysql_query("delete from comprobantes_retenciones where idretenciones = '".$id_retencion."'");
}
?>