<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


if($ejecutar == "procesarDatosBasicos"){
	
	$sql_consultar = mysql_query("select * from rendicion_cuentas where anio = '".$anio."'
																		and mes = '".$mes."'
																		and idcategoria_programatica = '".$id_categoria_programatica."'
																		and idtipo_presupuesto = '".$tipo_presupuesto."'
																		and idfuente_financiamiento = '".$fuente_financiamiento."'
																		and idordinal = '".$ordinal."'");
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar > 0){
		
		$bus_consultar = mysql_fetch_array($sql_consultar);
		echo "repetido|.|".$bus_consultar["idrendicion_cuentas"]."|.|".$bus_consultar["concepto"];
	}else{
	//echo "ENTRO ACA";
		$sql_insertar_datos = mysql_query("insert into rendicion_cuentas(anio,
																		mes,
																		concepto,
																		idcategoria_programatica,
																		idtipo_presupuesto,
																		idfuente_financiamiento,
																		idordinal)values('".$anio."',
																						'".$mes."',
																						'".$concepto."',
																						'".$id_categoria_programatica."',
																						'".$tipo_presupuesto."',
																						'".$fuente_financiamiento."',
																						'".$ordinal."')")or die(mysql_error());
																						
		if($sql_insertar_datos){
			echo "exito|.|".mysql_insert_id();
		}else{
			echo "fallo|.|".mysql_error();
		}
	}
}


if($ejecutar == "buscarPartidas"){

	$sql_consultar = mysql_query("select * from maestro_presupuesto, clasificador_presupuestario where 
																	idcategoria_programatica = '".$id_categoria_programatica."'
																	and anio = '".$anio."'
																	and idtipo_presupuesto = '".$tipo_presupuesto."'
																	and idfuente_financiamiento = '".$fuente_financiamiento."'
																	and idordinal = '".$ordinal."'
																	and maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
																	order by clasificador_presupuestario.codigo_cuenta ASC");

	?>
	<input type="hidden" id="idrendicion_cuentas" name="idrendicion_cuentas" value="<?=$idrendicion_cuentas?>">
   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse" style="padding:5px">
<thead>
				  <tr>
					<td width="81" class="Browse" align="center">Partida</td>
					<td width="219" class="Browse" align="center">Denominacion</td>
					<td width="49" class="Browse" align="center">Monto Actual</td>
                    <td width="90" class="Browse" align="center">Aumento del Periodo</td>
                    <td width="90" class="Browse" align="center">Disminucion del Periodo</td>
                    <td width="58" class="Browse" align="center">Monto Ajustado</td>
					<td width="90" class="Browse" align="center">Reversa</td>                    
                    <td width="90" class="Browse" align="center">Compromisos del Periodo</td>
                    <td width="90" class="Browse" align="center">Causados del Periodo</td>
                    <td width="90" class="Browse" align="center">Pagados del Periodo</td>
                    <td width="92" class="Browse" align="center">Disponible</td>
                    <td width="43" class="Browse" align="center">Selec.</td>
    </tr>
				  </thead>
                  <?
                  while($bus_consultar = mysql_fetch_array($sql_consultar)){
				  if($accion != "consultar"){
				  	if($mes != 1){
				  	  $encontrado = false;
					  $primer_mes = false;
					  $mes_consultar = $mes-1;
					  while($encontrado != true){
						  $sql_mes_anterior = mysql_query("select * from rendicion_cuentas where 
															anio = '".$anio."' 
														and mes = '".$mes_consultar."'
														and idcategoria_programatica = '".$id_categoria_programatica."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idordinal = '".$ordinal."'")or die(mysql_error());
						  $bus_mes_anterior = mysql_fetch_array($sql_mes_anterior);
						  $num_mes_anterior = mysql_num_rows($sql_mes_anterior);
						  if($num_mes_anterior != 0){
						  	$encontrado = true;
						  }else{
						  	$mes_consultar = $mes_consultar-1;
							if($mes_consultar == 0){
								$monto_actual = $bus_consultar["monto_original"];
								$encontrado = true;
								$primer_mes = true;
							}
						  }
					  }
					  if($primer_mes == false){
						  $sql_partida_anterior = mysql_query("select * from rendicion_cuentas_partidas where 
						idrendicion_cuentas = '".$bus_mes_anterior["idrendicion_cuentas"]."'
						and idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."'")or die(mysql_error());
						  $bus_partida_anterior = mysql_fetch_array($sql_partida_anterior);
							
						  //echo "PRUEBA:".$bus_partida_anterior["monto_actual_periodo"];
						  $monto_actual = $bus_partida_anterior["disponible_periodo"];
					 }
					}else{
						$monto_actual = $bus_consultar["monto_original"];
					}
					
					  $sql_rendicion_cuentas_partidas = mysql_query("insert into rendicion_cuentas_partidas(
																					idrendicion_cuentas,
																					idmaestro_presupuesto,
																					monto_original_periodo,
																					disminucion_original,
																					aumento_original,
																					monto_actual_periodo,
																					compromisos_original,
																					causados_original,
																					pagados_original)VALUES(
																				'".$idrendicion_cuentas."',
																				'".$bus_consultar["idRegistro"]."',
																				'".$bus_consultar["monto_original"]."',
																				'".$bus_consultar["total_disminucion"]."',
																				'".$bus_consultar["total_aumento"]."',
																				'".$monto_actual."',
																				'".$bus_consultar["total_compromisos"]."',
																				'".$bus_consultar["total_causados"]."',
																				'".$bus_consultar["total_pagados"]."')")or die(mysql_error());
					$id = mysql_insert_id();
				}else{
					if($mes != 1){
					 $encontrado = false;
					 $primer_mes = false;
					  $mes_consultar = $mes-1;
					  while($encontrado != true){
						  $sql_mes_anterior = mysql_query("select * from rendicion_cuentas where 
															anio = '".$anio."' 
														and mes = '".$mes_consultar."'
														and idcategoria_programatica = '".$id_categoria_programatica."'
														and idtipo_presupuesto = '".$tipo_presupuesto."'
														and idfuente_financiamiento = '".$fuente_financiamiento."'
														and idordinal = '".$ordinal."'")or die(mysql_error());
						  $bus_mes_anterior = mysql_fetch_array($sql_mes_anterior);
						  $num_mes_anterior = mysql_num_rows($sql_mes_anterior);
						  if($num_mes_anterior != 0){
						  	$encontrado = true;
							//echo $mes_consultar;
						  }else{
						  	$mes_consultar = $mes_consultar-1;
							//echo "ENTRO AQUI";
							if($mes_consultar == 0){
							
								$monto_actual = $bus_consultar["monto_original"];
								$encontrado = true;
								$primer_mes = true;
							}else{
								$primer_mes = false;
							}
						  }
					  }
					 if($primer_mes == false){
					 //echo "ENTRO ACA";
						  $sql_partida_anterior = mysql_query("select * from rendicion_cuentas_partidas where 
						idrendicion_cuentas = '".$bus_mes_anterior["idrendicion_cuentas"]."'
						and idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."'")or die(mysql_error());
						  $bus_partida_anterior = mysql_fetch_array($sql_partida_anterior);
							
						  //echo "PRUEBA:".$bus_partida_anterior["total_compromisos_periodo"];
						  $monto_actual = $bus_partida_anterior["disponible_periodo"];
					  }
					}else{
						
						$monto_actual = $bus_consultar["monto_original"];
					}
					
					
					
					
					
					$sql_rendicion = mysql_query("select * from rendicion_cuentas_partidas 
												where idrendicion_cuentas = '".$idrendicion_cuentas."' 
												and idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."'");
					$bus_rendicion = mysql_fetch_array($sql_rendicion);
					$num_rendicion = mysql_num_rows($sql_rendicion);
					if($num_rendicion==0){
						$sql_rendicion_cuentas_partidas = mysql_query("insert into rendicion_cuentas_partidas(
																					idrendicion_cuentas,
																					idmaestro_presupuesto,
																					monto_original_periodo,
																					disminucion_original,
																					aumento_original,
																					monto_actual_periodo,
																					compromisos_original,
																					causados_original,
																					pagados_original)VALUES(
																				'".$idrendicion_cuentas."',
																				'".$bus_consultar["idRegistro"]."',
																				'".$bus_consultar["monto_original"]."',
																				'".$bus_consultar["total_disminucion"]."',
																				'".$bus_consultar["total_aumento"]."',
																				'".$monto_actual."',
																				'".$bus_consultar["total_compromisos"]."',
																				'".$bus_consultar["total_causados"]."',
																				'".$bus_consultar["total_pagados"]."')")or die(mysql_error());
						$id = mysql_insert_id();
					}else{
						$id = $bus_rendicion["idrendiciones_cuentas_partidas"];
					}
					
										
					$sql_actualizar = mysql_query("update rendicion_cuentas_partidas set monto_actual_periodo = '".$monto_actual."'
																					where idrendiciones_cuentas_partidas = '".$id."'")or die(mysql_error());
																					
					//echo $monto_actual;																
					$sql_rendicion = mysql_query("select * from rendicion_cuentas_partidas 
												where idrendicion_cuentas = '".$idrendicion_cuentas."' 
												and idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."'");
					$bus_rendicion = mysql_fetch_array($sql_rendicion);
					$id = $bus_rendicion["idrendiciones_cuentas_partidas"];
				}
				  ?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                      <td width="81" class="Browse" align="center">
                      <?
                      $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario='".$bus_consultar["idclasificador_presupuestario"]."'");
					  $bus_clasificador = mysql_fetch_array($sql_clasificador);
					  echo $bus_clasificador["partida"].".".$bus_clasificador["generica"].".".$bus_clasificador["especifica"].".".$bus_clasificador["sub_especifica"];
					  
					  //echo "ID:".$id;
					  ?>                      </td>
                      <td width="219" class="Browse"><?=$bus_clasificador["denominacion"]?></td>
                      <td width="49" class="Browse" align="right">
					  	<?
                        if($accion == "consultar"){
							$actual = $bus_rendicion["monto_actual_periodo"];
						}else{
							$actual = $monto_actual;
						}
						echo number_format($actual,2,",",".");
						?>                      
                       </td>
                      <td width="90" class="Browse" align="center">
                      <?
					  $mes_nuevo = $mes;
					  if($mes_nuevo < 10){
					  	$mes_nuevo = "0".$mes_nuevo;
					  }
					  $fecha_comparar = $anio."-".$mes_nuevo;
					  
					  $sql_receptoras_traslado = mysql_query("select SUM(partidas_receptoras_traslado.monto_acreditar) as total 
							from partidas_receptoras_traslado, traslados_presupuestarios 
							where partidas_receptoras_traslado.idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."' 
							and traslados_presupuestarios.idtraslados_presupuestarios = partidas_receptoras_traslado.idtraslados_presupuestarios
							and traslados_presupuestarios.estado = 'procesado'
							and traslados_presupuestarios.fecha_solicitud like '%".fecha_comparar."%'")or die(mysql_error());
					  $bus_receptoras_traslado = mysql_fetch_array($sql_receptoras_traslado);
					  	
					  
					  $sql_credito_adicional = mysql_query("select SUM(partidas_credito_adicional.monto_acreditar) as total 
							from partidas_credito_adicional, creditos_adicionales 
							where partidas_credito_adicional.idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."' 
							and creditos_adicionales.idcreditos_adicionales = partidas_credito_adicional.idcredito_adicional
							and creditos_adicionales.estado = 'procesado'
							and creditos_adicionales.fecha_solicitud like '%".$fecha_comparar."%'")or die(mysql_error());
					  $bus_credito_adicional = mysql_fetch_array($sql_credito_adicional);
					  
					  $sql_receptoras_rectificacion = mysql_query("select SUM(partidas_receptoras_rectificacion.monto_acreditar) as total 
						from partidas_receptoras_rectificacion, rectificacion_presupuesto
						where partidas_receptoras_rectificacion.idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."' 
						and rectificacion_presupuesto.idrectificacion_presupuesto = partidas_receptoras_rectificacion.idrectificacion_presupuesto
						and rectificacion_presupuesto.estado = 'procesado'
						and rectificacion_presupuesto.fecha_solicitud like '%".$fecha_comparar."%'");
					  $bus_receptoras_rectificacion = mysql_fetch_array($sql_receptoras_rectificacion);
					  
                      if($bus_rendicion["aumento_periodo"] == 0){
					  	$aumento = $bus_receptoras_traslado["total"]+$bus_credito_adicional["total"]+$bus_receptoras_rectificacion["total"];
						$sql_actualizar_rendicion = mysql_query("update rendicion_cuentas_partidas set aumento_periodo = '".$aumento."' where idrendiciones_cuentas_partidas = '".$id."'")or die(mysql_error());
					  }else{
					  	$aumento = $bus_rendicion["aumento_periodo"];
					  }
					 
					  ?>
                      	<input name="aumento_periodo" type="text" id="aumento_periodo<?=$id?>_mostrado" style="text-align:right" size="15" value="<?=number_format($aumento,2,",",".")?>" onclick="this.select()" onblur="formatoNumero(this.id, 'aumento_periodo<?=$id?>')">
                        <input type="hidden" id="aumento_periodo<?=$id?>" value="<?=$aumento?>">
                      </td>
                      <td width="90" class="Browse" align="center">
                      <?
					  $sql_cedentes_traslado = mysql_query("select SUM(partidas_cedentes_traslado.monto_debitar) as total 
							from partidas_cedentes_traslado, traslados_presupuestarios 
							where partidas_cedentes_traslado.idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."' 
							and traslados_presupuestarios.idtraslados_presupuestarios = partidas_cedentes_traslado.idtraslados_presupuestarios
							and traslados_presupuestarios.estado = 'procesado'
							and traslados_presupuestarios.fecha_solicitud like '%".$fecha_comparar."%'")or die(mysql_error());
					  $bus_cedentes_traslado = mysql_fetch_array($sql_cedentes_traslado);
					  	
					  
					  $sql_disminucion_presupuestaria = mysql_query("select SUM(partidas_disminucion_presupuesto.monto_debitar) as total 
					  		from partidas_disminucion_presupuesto, disminucion_presupuesto 
							where partidas_disminucion_presupuesto.idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."' 
							and disminucion_presupuesto.iddisminucion_presupuesto = partidas_disminucion_presupuesto.iddisminucion_presupuesto
							and disminucion_presupuesto.estado = 'procesado'
							and disminucion_presupuesto.fecha_solicitud like '%".$fecha_comparar."%'")or die(mysql_error());
					  $bus_disminucion_presupuestaria = mysql_fetch_array($sql_disminucion_presupuestaria);
					  
					  
					  $sql_rectificadoras = mysql_query("select SUM(partidas_rectificadoras.monto_debitar) as total 
					  		from partidas_rectificadoras, rectificacion_presupuesto
							where partidas_rectificadoras.idmaestro_presupuesto = '".$bus_consultar["idRegistro"]."' 
							and rectificacion_presupuesto.idrectificacion_presupuesto = partidas_rectificadoras.idrectificacion_presupuesto
							and rectificacion_presupuesto.estado = 'procesado'
							and rectificacion_presupuesto.fecha_solicitud like '%".$fecha_comparar."%'")or die(mysql_error());
					  $bus_rectificadoras = mysql_fetch_array($sql_rectificadoras);
					  
					  
					  if($bus_rendicion["disminucion_periodo"] == 0){
					  	$disminucion = $bus_cedentes_traslado["total"]+$bus_disminucion_presupuestaria["total"]+$bus_rectificadoras["total"];
						$sql_actualizar_rendicion = mysql_query("update rendicion_cuentas_partidas set disminucion_periodo = '".$disminucion."' where idrendiciones_cuentas_partidas = '".$id."'")or die(mysql_error());
					  }else{
					  	$disminucion = $bus_rendicion["disminucion_periodo"];
					  }
					  
					  ?>
                      	<input name="disminucion_periodo" type="text" id="disminucion_periodo<?=$id?>_mostrado" style="text-align:right" size="15" value="<?=number_format($disminucion ,2,",",".")?>" onclick="this.select()" onblur="formatoNumero(this.id, 'disminucion_periodo<?=$id?>')">
                        <input type="hidden" id="disminucion_periodo<?=$id?>" value="<?=$disminucion?>">
                        
                      </td>
                      <td width="58" class="Browse" align="right">
					  	<?=number_format($actual+$aumento-$disminucion,2,",",".")?>                        </td>
                      <td width="90" class="Browse" align="center">
                      <input type="text" id="reversa<?=$id?>_mostrado" name="reversa<?=$id?>" size="15" onclick="this.select()" value="<?=number_format($bus_rendicion["reversa"],2,",",".")?>" style=" text-align:right" onblur="formatoNumero(this.id, 'reversa<?=$id?>')">
                      <input type="hidden" id="reversa<?=$id?>" value="<?=$bus_rendicion["reversa"]?>">
                      </td>
                      <td width="90" class="Browse" align="center">
                      	<input name="compromisos_periodo" type="text" id="compromisos_periodo<?=$id?>_mostrado" style="text-align:right" size="15" value="<?=number_format($bus_rendicion["total_compromisos_periodo"],2,",",".")?>" onclick="this.select()" onblur="formatoNumero(this.id, 'compromisos_periodo<?=$id?>')">
                        <input type="hidden" id="compromisos_periodo<?=$id?>" value="<?=$bus_rendicion["total_compromisos_periodo"]?>">
                      </td>
                      <td width="90" class="Browse" align="center">
                      	<input name="causado_periodo" type="text" id="causado_periodo<?=$id?>_mostrado" style="text-align:right" size="15" value="<?=number_format($bus_rendicion["total_causados_periodo"],2,",",".")?>" onclick="this.select()" onblur="formatoNumero(this.id, 'causado_periodo<?=$id?>')">
                        <input type="hidden" id="causado_periodo<?=$id?>" value="<?=$bus_rendicion["total_causados_periodo"]?>">
                      </td>
                      <td width="90" class="Browse" align="center">
                      	<input name="pagado_periodo" type="text" id="pagado_periodo<?=$id?>_mostrado" style="text-align:right" size="15" value="<?=number_format($bus_rendicion["total_pagados_periodo"],2,",",".")?>" onclick="this.select()" onblur="formatoNumero(this.id, 'pagado_periodo<?=$id?>')">
                        <input type="hidden" id="pagado_periodo<?=$id?>" value="<?=$bus_rendicion["total_pagados_periodo"]?>">
                      </td>
                      <td width="92" class="Browse" align="right">
					  	<?=number_format($actual-$bus_rendicion["total_compromisos_periodo"]+$aumento-$disminucion+$bus_rendicion["reversa"],2,",",".")?>
                      <?
					  $disponible_periodo = $actual-$bus_rendicion["total_compromisos_periodo"]+$aumento-$disminucion+$bus_rendicion["reversa"];
                      $sql_actualizar = mysql_query("update rendicion_cuentas_partidas set disponible_periodo = '".$disponible_periodo."' where idrendiciones_cuentas_partidas = '".$id."'");
					  ?>
                      </td>
     				  <td align="center" valign="middle" class="Browse">
                    	&nbsp;<img src='imagenes/refrescar.png' onclick="actualizarPartida('<?=$id?>',
                        										'<?=$idrendicion_cuentas?>',
                        										'<?=$bus_consultar["idRegistro"]?>',
                        										 document.getElementById('aumento_periodo<?=$id?>').value,
                                                                 document.getElementById('disminucion_periodo<?=$id?>').value,
                                                                 document.getElementById('compromisos_periodo<?=$id?>').value,
                                                                 document.getElementById('causado_periodo<?=$id?>').value,
                                                                 document.getElementById('pagado_periodo<?=$id?>').value,
                                                                 document.getElementById('reversa<?=$id?>').value)"
                                    style=" cursor:pointer">
                      </td>
     </tr>
                <?
                }
				?>

				</table>
	
	<?
}




if($ejecutar == "actualizarPartida"){
	//echo "REVERSA: ".$reversa;
	if($reversa != 0){
	//echo "ENTRO ACA";
		$sql_actualizar = mysql_query("update maestro_presupuesto set total_compromisos = total_compromisos - '".$reversa."'
																		where idRegistro = '".$idmaestro_presupuesto."'");
	}
	
	$sql_consulta = mysql_query("select * from rendicion_cuentas_partidas where idrendiciones_cuentas_partidas = '".$idrendicion_cuentas_partidas."'");
	$bus_consulta = mysql_fetch_array($sql_consulta);
	
	
	
	//echo "LA PARTIDA TIENE: ".$bus_ma["total_aumento"]."TIENE:".$bus_consulta["aumento_periodo"]." ACTUALIZA: ".$aumento_periodo;
																
	$sql_actualizar = mysql_query("update maestro_presupuesto set 
							total_aumento= total_aumento-'".$bus_consulta["aumento_periodo"]."'+'".$aumento_periodo."',
							total_disminucion = total_disminucion-'".$bus_consulta["disminucion_periodo"]."'+'".$disminucion_periodo."',
							total_compromisos = total_compromisos-'".$bus_consulta["total_compromisos_periodo"]."'+'".$compromisos_periodo."',
							total_causados = total_causados-'".$bus_consulta["total_causados_periodo"]."'+'".$causado_periodo."',
							total_pagados = total_pagados-'".$bus_consulta["total_pagados_periodo"]."'+'".$pagado_periodo."'
							where idRegistro = '".$idmaestro_presupuesto."'")or die(mysql_error());
	
	$sql_maestro = mysql_query("update maestro_presupuesto set monto_actual = monto_original + total_aumento - total_disminucion where idRegistro = '".$idmaestro_presupuesto."'");



	$sql_actualizar_rendicion = mysql_query("update rendicion_cuentas_partidas set disminucion_periodo = '".$disminucion_periodo."',
																	aumento_periodo = '".$aumento_periodo."',
																	disponible_periodo = disponible_periodo - reversa + '".$reversa."', 
																	reversa = '".$reversa."',
																	total_compromisos_periodo = '".$compromisos_periodo."',
																	total_causados_periodo = '".$causado_periodo."',
																	total_pagados_periodo= '".$pagado_periodo."'
																	where idrendiciones_cuentas_partidas = '".$idrendicion_cuentas_partidas."'")or die(mysql_error());

	if($sql_actualizar){
		echo "exito";
	}else{
		echo "fallo";
	}
}






?>