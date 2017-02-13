<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);



if($ejecutar == "registrarIngresos"){
	if ($afecta == 'a'){
		$tipo = 'ingreso';
	}else{
		$tipo = 'egreso';
	}
	$sql_registrar_ingresos = mysql_query("insert into ingresos_egresos_financieros(idfuente_financiamiento,
																				idtipo_movimiento,
																				tipo,
																				numero_documento,
																				fecha,
																				idbanco,
																				idcuentas_bancarias,
																				monto,
																				emitido_por,
																				ci_emitido,
																				concepto,
																				estado,
																				status,
																				usuario,
																				fechayhora,
																				anio_documento)values('".$idfuente_financiamiento."',
																									'".$idtipo_movimiento."',
																									'".$tipo."',
																									'".$numero_documento."',
																									'".$fecha."',
																									'".$idbanco."',
																									'".$idcuentas_bancarias."',
																									'".$monto."',
																									'".$emitido_por."',
																									'".$ci_emisor."',
																									'".$concepto."',
																									'procesado',
																									'a',
																									'".$login."',
																									'".$fh."',
																									'".$anio."')")or die(mysql_error());
	

	if($sql_registrar_ingresos){
		$idingresos_egresos_financieros = mysql_insert_id();
		echo "exito";
		if ($excluir_contabilidad != 'si'){
			$mes_contable = explode("-",$fecha);
			$sql_registrar_asiento_contable = mysql_query("insert into asiento_contable(idfuente_financiamiento,
																					fecha_contable,
																					mes_contable,
																					detalle,
																					tipo_movimiento,
																					iddocumento,
																					estado,
																					usuario,
																					fechayhora,
																					prioridad)values('".$idfuente_financiamiento."',
																										'".$fecha."',
																										'".$mes_contable[1]."',
																										'".$concepto." Documento Nro.".$numero_documento."',
																										'movimiento_bancario',
																										'".$idingresos_egresos_financieros."',
																										'procesado',
																										'".$login."',
																										'".$fh."',
																										'1')")or die(mysql_error());
																										
			$idasiento_contable = mysql_insert_id();
			
			$sql_registrar_ingresos = mysql_query("update ingresos_egresos_financieros set idasiento_contable = '".$idasiento_contable."'
																	where idingresos_financieros = '".$idingresos_egresos_financieros."'")or die(mysql_error());
			
			$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																					tabla,
																					idcuenta,
																					monto,
																					afecta)values('".$idasiento_contable."',
																										'".$tabla_debe."',
																										'".$idcuenta_debe."',
																										'".$monto."',
																										'debe')")or die(mysql_error());
			$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																					tabla,
																					idcuenta,
																					monto,
																					afecta)values('".$idasiento_contable."',
																										'".$tabla_haber."',
																										'".$idcuenta_haber."',
																										'".$monto."',
																										'haber')")or die(mysql_error());
		}	
	}else{
		echo "fallo";
	}
}





if($ejecutar == "cargarCuentasBancarias"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$banco."'");
	?>
	<select name="cuenta" id="cuenta">
    	<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
		?>
			<option	value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>" onclick="mostrar_cuenta_contable_cuenta_bancaria('<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>','<?=$excluir_contabilidad?>')"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
			<?
		}
		?>
    </select>  
	<?
}


if($ejecutar == "mostrar_cuenta_contable_tipo_movimiento"){
	$sql_consultar_cuenta_contable = mysql_query("select * from tipo_movimiento_bancario 
														where idtipo_movimiento_bancario = '".$idtipo_movimiento_bancario."'");
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




if($ejecutar == "modificarIngresos"){

	$sql_registrar_ingresos = mysql_query("update ingresos_egresos_financieros set idfuente_financiamiento = '".$idfuente_financiamiento."',
																		numero_documento = '".$numero_documento."',
																		fecha = '".$fecha."',
																		monto = '".$monto."',
																		emitido_por = '".$emitido_por."',
																		ci_emitido = '".$ci_emisor."',
																		concepto ='".$concepto."',
																		anio_documento ='".$anio."' where idingresos_financieros = '".$id_ingresos."'")or die(mysql_error());
	
	$mes_contable = explode("-",$fecha);
	$sql_registrar_ingresos = mysql_query("update asiento_contable set 	idfuente_financiamiento = '".$idfuente_financiamiento."',
																		fecha_contable = '".$fecha."',
																		mes_contable = '".$mes_contable[1]."',
																		detalle = '".$concepto." Documento Nro.".$numero_documento."'
																		where idasiento_contable = '".$idasiento_contable."'")or die(mysql_error());
	
	$sql_registrar_ingresos = mysql_query("update cuentas_asiento_contable set 	monto = '".$monto."'
																		where idasiento_contable = '".$idasiento_contable."'")or die(mysql_error());
	if($sql_registrar_ingresos){
		echo "exito";
	}else{
		echo "fallo";
	}
}






if($ejecutar == "eliminarIngresos"){
	$sql = mysql_query("select * from usuarios where login = '".$login."' and clave = '".md5($clave)."'");
	$num = mysql_num_rows($sql);
	if($num > 0){
		$sql_registrar_ingresos = mysql_query("update ingresos_egresos_financieros set estado = 'anulado',
																				fecha_anulacion = '".$fecha_anulacion."'
																		where idingresos_financieros = '".$id_ingresos."'")or die(mysql_error());
		
		$sql_actualizar =mysql_query("update asiento_contable set reversado = 'si'
															where iddocumento = ".$id_ingresos."
																and tipo_movimiento = 'movimiento_bancario'");
		$mes_contable = explode("-",$fecha);
		$sql_registrar_asiento_contable = mysql_query("insert into asiento_contable(idfuente_financiamiento,
																					fecha_contable,
																					mes_contable,
																				detalle,
																				tipo_movimiento,
																				iddocumento,
																				estado,
																				usuario,
																				fechayhora,
																				prioridad)values('".$idfuente_financiamiento."',
																									'".$fecha_anulacion."',
																									'".$mes_contable[1]."',
																								'ANULACION DE ASIENTO: ".$concepto." Documento Nro.".$numero_documento."',
																									'movimiento_bancario',
																									'".$id_ingresos."',
																									'anulado',
																									'".$login."',
																									'".$fh."',
																									'1')")or die(mysql_error());
		
		

		$idasiento_contable = mysql_insert_id();
		
		$sql_registrar_ingresos = mysql_query("update ingresos_egresos_financieros set idasiento_contable = '".$idasiento_contable."'
																where idingresos_financieros = '".$id_ingresos."'")or die(mysql_error());
		
		$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																									'".$tabla_debe."',
																									'".$idcuenta_debe."',
																									'".$monto."',
																									'haber')")or die(mysql_error());
		$sql_registrar_cuenta_asiento_contable = mysql_query("insert into cuentas_asiento_contable(idasiento_contable,
																				tabla,
																				idcuenta,
																				monto,
																				afecta)values('".$idasiento_contable."',
																									'".$tabla_haber."',
																									'".$idcuenta_haber."',
																									'".$monto."',
																									'debe')")or die(mysql_error());
	
		if($sql_eliminar_ingresos){
			echo "exito";
		}else{
			echo "fallo";
		}
	}
}



if($ejecutar == "mostrarAnulado"){
	$sql_consultar_asiento_contable = mysql_query("select * from asiento_contable where tipo_movimiento = 'movimiento_bancario'
																				and iddocumento = '".$id_ingresos."'");
	
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
					
					if ($bus_consultar_asiento_contable["estado"] <> 'anulado'){
						 $sql_cuentas_contables = mysql_query("select * from cuentas_asiento_contable 
			   											where idasiento_contable = '".$bus_consultar_asiento_contable["idasiento_contable"]."'
														order by afecta")or die("aqui cuenta ".mysql_error());
														
						while ($bus_cuentas_contables = mysql_fetch_array($sql_cuentas_contables)){
	   ?>
                            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                            <?
							$idcampo = "id".$bus_cuentas_contables["tabla"];
							//echo "select * from ".$bus_cuentas_contables["tabla"]."where ".$idcampo." = '".$bus_cuentas_contables["idcuenta"]."'";
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

?>