<?
session_start();

include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarCuentaBancaria"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where numero_cuenta = '".$numero_cuenta."'");
	$num_consultar_cuentas = mysql_num_rows($sql_consultar_cuentas);
	if($num_consultar_cuentas == 0){
		$sql_insert_cuantas_bancarias = mysql_query("insert into cuentas_bancarias(numero_cuenta,
																					idbanco,
																					idtipo_cuenta,
																					validez_documento,
																					fecha_inicio_periodo,
																					fecha_final_periodo,
																					fecha_apertura,
																					monto_apertura,
																					monto_libro,
																					uso_cuenta,
																					estado,
																					firma_autorizada1,
																					ci_firma_autorizada1,
																					cargo_firma_autorizada1,
																					firma_autorizada2,
																					ci_firma_autorizada2,
																					cargo_firma_autorizada2,
																					firma_autorizada3,
																					ci_firma_autorizada3,
																					cargo_firma_autorizada3,
																					conjuntas,
																					usuario,
																					status,
																					fechayhora)values(
																								'".$numero_cuenta."',
																								'".$banco."',
																								'".$tipo_cuenta."',
																								'".$validez_documento."',
																								'".$fecha_inicio_periodo."',
																								'".$fecha_fin_periodo."',
																								'".$fecha_apertura."',
																								'".$monto_apertura."',
																								'".$monto_apertura_libro."',
																								'".$uso_cuenta."',
																								'".$estado_cuenta."',
																								'".$firma_autorizada."',
																								'".$ci_firma_autorizada."',
																								'".$cargo_firma_autorizada."',
																								'".$firma_autorizada_2."',
																								'".$ci_firma_autorizada_2."',
																								'".$cargo_firma_autorizada_2."',
																								'".$firma_autorizada_3."',
																								'".$ci_firma_autorizada_3."',
																								'".$cargo_firma_autorizada_3."',
																								'".$conjuntas."',
																								'".$login."',
																								'a',
																								'".$fh."')")or die(mysql_error());
		if(sql_insert_cuantas_bancarias){
			$idcuenta_bancaria = mysql_insert_id();
			echo "exito-".mysql_insert_id();
			registra_transaccion("Ingresar Cuenta Bancaria (".$numero_cuenta.")",$login,$fh,$pc,'cuentas_bancarias');
			if ($idcuenta > 0){
				if ($tabla == 'rubro_cuentas'){
					$tabla_ingresar = "cuenta_cuentas_contables";
					$sql_insert_cuentas_contables = mysql_query("insert into cuenta_cuentas_contables(idrubro,
																					denominacion)values(
																								'".$idcuenta."',
																								'".$sub_cuenta_contable."')")or die(mysql_error());
				}
				if ($tabla == 'cuenta_cuentas'){
					$tabla_ingresar = "subcuenta_primer_cuentas_contables";
					$sql_insert_cuentas_contables = mysql_query("insert into subcuenta_primer_cuentas_contables(idcuenta,
																					denominacion)values(
																								'".$idcuenta."',
																								'".$sub_cuenta_contable."')")or die(mysql_error());
				}
				if ($tabla == 'subcuenta_primer'){
					$tabla_ingresar = "subcuenta_segundo_cuentas_contables";
					$sql_insert_cuentas_contables = mysql_query("insert into subcuenta_segundo_cuentas_contables(idsubcuenta_primer,
																					denominacion)values(
																								'".$idcuenta."',
																								'".$sub_cuenta_contable."')")or die(mysql_error());
				}
				if ($tabla == 'subcuenta_segundo'){
					$tabla_ingresar = "desagregacion_cuentas_contables";
					$sql_insert_cuentas_contables = mysql_query("insert into desagregacion_cuentas_contables(idsubcuenta_segundo,
																					denominacion)values(
																								'".$idcuenta."',
																								'".$sub_cuenta_contable."')")or die(mysql_error());
				}
			}
			if($sql_insert_cuentas_contables){
				$idcuenta_contable = mysql_insert_id();
				$sql_insert_cuantas_bancarias = mysql_query("update cuentas_bancarias set idcuenta_contable = '".$idcuenta_contable."',
											tabla = '".$tabla_ingresar."'
											where idcuentas_bancarias = '".$idcuenta_bancaria."'")or die(mysql_error());
			}
		}else{
			registra_transaccion("Ingresar Cuenta Bancaria ERROR (".$numero_cuenta.")",$login,$fh,$pc,'cuentas_bancarias');
			echo "fallo";
		}
	}else{
		echo "existe";
	}																						
}




if($ejecutar == "listarChequeras"){
			$sql_cheques_cuentas_bancarias = mysql_query("select * from cheques_cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."'")or die(mysql_error());
			$num_cheques_cuentas_bancarias = mysql_num_rows($sql_cheques_cuentas_bancarias);
			if($num_cheques_cuentas_bancarias > 0){
			?>
			<table class="Browse" cellpadding="0" cellspacing="0" width="60%" align="center">
				<thead>
					<tr>
                        <td align="center" class="Browse">Nro. Chequera</td>
                        <td align="center" class="Browse">Numero Inicial</td>
                        <td align="center" class="Browse">Numero Final</td>
                        <td align="center" class="Browse">Fecha Inicial de Uso</td>
                        <td align="center" class="Browse">Fecha Final de Uso</td>
						<td align="center" class="Browse">Estado</td>
						<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
					</tr>
				</thead>
				<?
				while($bus_cheques_cuentas_bancarias = mysql_fetch_array($sql_cheques_cuentas_bancarias)){
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td align='left' class='Browse'><?=$bus_cheques_cuentas_bancarias["chequera_numero"]?></td>
                        <td align='left' class='Browse'><?=$bus_cheques_cuentas_bancarias["numero_inicial"]?></td>                        
                        <td align='left' class='Browse'><?=$bus_cheques_cuentas_bancarias["numero_final"]?></td>
                        <td align='left' class='Browse'>
						<?
							if($bus_cheques_cuentas_bancarias["fecha_inicio_uso"] == '0000-00-00'){
								echo "&nbsp;";
							}else{
								echo $bus_cheques_cuentas_bancarias["fecha_inicio_uso"];
							}
						?>
                        </td>					
                        <td align='left' class='Browse'>
						<?
							if($bus_cheques_cuentas_bancarias["fecha_final_uso"] == '0000-00-00'){
								echo "&nbsp;";
							}else{
								echo $bus_cheques_cuentas_bancarias["fecha_inicio_uso"];
							}
						?>
                        </td>
                        <td align='left' class='Browse'><?=$bus_cheques_cuentas_bancarias["estado"]?></td>					
                        <td align='center' class='Browse' width='7%'>
						<?
                        $idchequeras = $bus_cheques_cuentas_bancarias["idcheques_cuentas_bancarias"];
						$numero_cheque = $bus_cheques_cuentas_bancarias["chequera_numero"];
						$cantidad_cheques = $bus_cheques_cuentas_bancarias["cantidad_cheques"];
						$numero_inicial = $bus_cheques_cuentas_bancarias["numero_inicial"];
						$numero_final = $bus_cheques_cuentas_bancarias["numero_final"];
						$digitos_consecutivos = $bus_cheques_cuentas_bancarias["digitos_consecutivos"];
						$cantidad_digitos = $bus_cheques_cuentas_bancarias["cantidad_digitos"];
							if($bus_cheques_cuentas_bancarias["fecha_inicio_uso"] != "0000-00-00"){
								$fecha_inicial = $bus_cheques_cuentas_bancarias["fecha_inicio_uso"];
							}else{
								$fecha_inicial = "";
							}
							if($bus_cheques_cuentas_bancarias["fecha_final_uso"] != "0000-00-00"){
								$fecha_final = $bus_cheques_cuentas_bancarias["fecha_final_uso"];
							}else{
								$fecha_final = "";
							}
						
						$estado = $bus_cheques_cuentas_bancarias["estado"];
						?>
                        		<img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar' style="cursor:pointer" onclick="seleccionarModificarChequeras('<?=$numero_cheque?>', '<?=$cantidad_cheques?>', '<?=$numero_inicial?>', '<?=$numero_final?>', '<?=$fecha_inicial?>', '<?=$fecha_final?>', '<?=$estado?>', '<?=$idchequeras?>','<?=$digitos_consecutivos?>','<?=$cantidad_digitos?>')">
						</td>
						<?
                        if($bus_cheques_cuentas_bancarias["fecha_inicio_uso"] == "0000-00-00"){
							?>
							<td align='center' class='Browse' width='7%'>
									<img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar' onClick="eliminarChequeras('<?=$bus_cheques_cuentas_bancarias["idcheques_cuentas_bancarias"]?>')" style="cursor:pointer">
							</td>
                       		<?
                        }
						?>

			  </tr>
				 <?
				 }
				 ?>
			</table>
            <br />
<br />

			<?
			}
}






if($ejecutar == "consultarCuentaBancaria"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."'");
	$bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas);
	
	$sql_chequeras = mysql_query("select * from cheques_cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."'");
	$num_chequeras = mysql_num_rows($sql_chequeras);
	if($num_chequeras == 0){
		$modificar_num = 'si';
	}else{
		$modificar_num = 'no';
	}
	if ($bus_consultar_cuentas["tabla"] == 'cuenta_cuentas_contables'){
		$sql_contabilidad = mysql_query("select * from cuenta_cuentas_contables 
										where idcuenta_cuentas_contables = '".$bus_consultar_cuentas["idcuenta_contable"]."'");
	}
	if ($bus_consultar_cuentas["tabla"] == 'subcuenta_primer_cuentas_contables'){
		$sql_contabilidad = mysql_query("select * from subcuenta_primer_cuentas_contables 
										where idsubcuenta_primer_cuentas_contables = '".$bus_consultar_cuentas["idcuenta_contable"]."'");
	}
	if ($bus_consultar_cuentas["tabla"] == 'subcuenta_segundo_cuentas_contables'){
		$sql_contabilidad = mysql_query("select * from subcuenta_segundo_cuentas_contables 
										where idsubcuenta_segundo_cuentas_contables = '".$bus_consultar_cuentas["idcuenta_contable"]."'");
	}
	if ($bus_consultar_cuentas["tabla"] == 'desagregacion_cuentas_contables'){
		$sql_contabilidad = mysql_query("select * from desagregacion_cuentas_contables 
										where iddesagregacion_cuentas_contables = '".$bus_consultar_cuentas["idcuenta_contable"]."'");
	}
	if ($sql_contabilidad){
		$bus_consultar_contabilidad = mysql_fetch_array($sql_contabilidad) or die(mysql_error());
		$denominacion_cuenta_contable = $bus_consultar_contabilidad["denominacion"];
	}else{
		$denominacion_cuenta_contable = '';
	}
	
	$sql_banco = mysql_query("select * from banco where idbanco = '".$bus_consultar_cuentas["idbanco"]."'");
	$bus_banco = mysql_fetch_array($sql_banco);
	$denominacion_banco = $bus_banco["denominacion"];
	
	echo $bus_consultar_cuentas["numero_cuenta"]."|.|"
	.$bus_consultar_cuentas["idbanco"]."|.|"
	.$bus_consultar_cuentas["idtipo_cuenta"]."|.|"
	.$bus_consultar_cuentas["validez_documento"]."|.|"
	.$bus_consultar_cuentas["fecha_inicio_periodo"]."|.|"
	.$bus_consultar_cuentas["fecha_final_periodo"]."|.|"
	.$bus_consultar_cuentas["fecha_apertura"]."|.|"
	.$bus_consultar_cuentas["monto_apertura"]."|.|"
	.$bus_consultar_cuentas["uso_cuenta"]."|.|"
	.$bus_consultar_cuentas["estado"]."|.|"
	.$bus_consultar_cuentas["firma_autorizada1"]."|.|"
	.$bus_consultar_cuentas["ci_firma_autorizada1"]."|.|"
	.$bus_consultar_cuentas["cargo_firma_autorizada1"]."|.|"
	.$bus_consultar_cuentas["firma_autorizada2"]."|.|"
	.$bus_consultar_cuentas["ci_firma_autorizada2"]."|.|"
	.$bus_consultar_cuentas["cargo_firma_autorizada2"]."|.|"
	.$bus_consultar_cuentas["firma_autorizada3"]."|.|"
	.$bus_consultar_cuentas["ci_firma_autorizada3"]."|.|"
	.$bus_consultar_cuentas["cargo_firma_autorizada3"]."|.|"
	.$bus_consultar_cuentas["conjuntas"]."|.|"
	.$modificar_num."|.|"
	.$bus_consultar_cuentas["idcuenta_contable"]."|.|"
	.$bus_consultar_cuentas["tabla"]."|.|"
	.$denominacion_cuenta_contable."|.|"
	.$denominacion_banco."|.|"
	.$bus_consultar_cuentas["monto_libro"];
	registra_transaccion("Consultar Cuenta Bancaria (".$idcuentas_bancarias.")",$login,$fh,$pc,'cuentas_bancarias');
}







if($ejecutar == "ingresarChequera"){
	$sql_consultar_chequera = mysql_query("select * from cheques_cuentas_bancarias where chequera_numero = '".$numero_chequera."' and idcuentas_bancarias = '".$idcuentas_bancarias."'");
	$num_consultar_chequera = mysql_num_rows($sql_consultar_chequera);
		if($num_consultar_chequera == 0){
			$sql_insert_chequera = mysql_query("insert into cheques_cuentas_bancarias (idcuentas_bancarias,
																						chequera_numero,
																						cantidad_cheques,
																						digitos_consecutivos,
																						cantidad_digitos,
																						numero_inicial,
																						numero_final,
																						ultimo_cheque,
																						estado,
																						usuario,
																						status,
																						fechayhora)values('".$idcuentas_bancarias."',
																											'".$numero_chequera."',
																											'".$cantidad_cheques."',
																											'".$digitos_consecutivos."',
																											'".$cantidad_digitos."',
																											'".$numero_inicial."',
																											'".$numero_final."',
																											'".$numero_inicial."',
																											'".$estado."',
																											'".$login."',
																											'a',
																											'".$fh."')")or die(mysql_error());
			if($sql_insert_chequera){
				echo "exito";
				registra_transaccion("Ingresar Chequera (".$numero_chequera.")",$login,$fh,$pc,'cheques_cuentas_bancarias');
			}else{
				echo "fallo";
				registra_transaccion("Ingresar Chequera ERROR (".$numero_chequera.")",$login,$fh,$pc,'cheques_cuentas_bancarias');
			}
		}else{
			echo "existe";
		}
}










if($ejecutar == "modificarCuentaBancaria"){
	if ($idcuenta_modificar == '0'){
		if ($tabla == 'rubro_cuentas'){
			$tabla_ingresar = "cuenta_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("insert into cuenta_cuentas_contables(idrubro,
																			denominacion)values(
																						'".$idcuenta."',
																						'".$sub_cuenta_contable."')")or die(mysql_error());
		}
		if ($tabla == 'cuenta_cuentas'){
			$tabla_ingresar = "subcuenta_primer_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("insert into subcuenta_primer_cuentas_contables(idcuenta,
																			denominacion)values(
																						'".$idcuenta."',
																						'".$sub_cuenta_contable."')")or die(mysql_error());
		}
		if ($tabla == 'subcuenta_primer'){
			$tabla_ingresar = "subcuenta_segundo_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("insert into subcuenta_segundo_cuentas_contables(idsubcuenta_primer,
																			denominacion)values(
																						'".$idcuenta."',
																						'".$sub_cuenta_contable."')")or die(mysql_error());
		}
		if ($tabla == 'subcuenta_segundo'){
			$tabla_ingresar = "desagregacion_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("insert into desagregacion_cuentas_contables(idsubcuenta_segundo,
																			denominacion)values(
																						'".$idcuenta."',
																						'".$sub_cuenta_contable."')")or die(mysql_error());
		}
		if($sql_insert_cuentas_contables){
				$idcuenta_contable = mysql_insert_id();
				$sql_insert_cuantas_bancarias = mysql_query("update cuentas_bancarias set idcuenta_contable = '".$idcuenta_contable."',
											tabla = '".$tabla_ingresar."'
											where idcuentas_bancarias = '".$idcuentas_bancarias."'")or die(mysql_error());
		}
	}else{
		if ($tabla == 'cuenta_cuentas_contables'){
			$tabla_ingresar = "cuenta_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("update cuenta_cuentas_contables 
																	set denominacion = '".$sub_cuenta_contable."'
																	where idcuenta_cuentas_contables = '".$idcuenta_modificar."'")or die(mysql_error());
		}
		if ($tabla == 'subcuenta_primer_cuentas_contables'){
			$tabla_ingresar = "subcuenta_primer_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("update subcuenta_primer_cuentas_contables 
																	set denominacion = '".$sub_cuenta_contable."'
																	where idsubcuenta_primer_cuentas_contables = '".$idcuenta_modificar."'")or die(mysql_error());
		}
		if ($tabla == 'subcuenta_segundo_cuentas_contables'){
			$tabla_ingresar = "subcuenta_segundo_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("update subcuenta_segundo_cuentas_contables
																	set denominacion = '".$sub_cuenta_contable."'
																	where idsubcuenta_segundo_cuentas_contables = '".$idcuenta_modificar."'")or die(mysql_error());
		}
		if ($tabla == 'desagregacion_cuentas_contables'){
			$tabla_ingresar = "desagregacion_cuentas_contables";
			$sql_insert_cuentas_contables = mysql_query("update desagregacion_cuentas_contables 
																	set denominacion = '".$sub_cuenta_contable."'
																	where iddesagregacion_cuentas_contables = '".$idcuenta_modificar."'")or die(mysql_error());
		}
		
	}
	$sql_insert_cuantas_bancarias = mysql_query("update cuentas_bancarias set numero_cuenta = '".$numero_cuenta."',
											idbanco = '".$banco."',
											idtipo_cuenta = '".$tipo_cuenta."',
											validez_documento = '".$validez_documento."',
											fecha_inicio_periodo = '".$fecha_inicio_periodo."',
											fecha_final_periodo = '".$fecha_fin_periodo."',
											fecha_apertura = '".$fecha_apertura."',
											monto_apertura = '".$monto_apertura."',
											monto_libro = '".$monto_apertura_libro."',
											uso_cuenta = '".$uso_cuenta."',
											estado = '".$estado_cuenta."',
											firma_autorizada1 = '".$firma_autorizada."',
											ci_firma_autorizada1 = '".$ci_firma_autorizada."',
											cargo_firma_autorizada1 = '".$cargo_firma_autorizada."',
											firma_autorizada2 = '".$firma_autorizada_2."',
											ci_firma_autorizada2 = '".$ci_firma_autorizada_2."',
											cargo_firma_autorizada2 = '".$cargo_firma_autorizada_2."',
											firma_autorizada3 = '".$firma_autorizada_3."',
											ci_firma_autorizada3 = '".$ci_firma_autorizada_3."',
											cargo_firma_autorizada3 = '".$cargo_firma_autorizada_3."',
											conjuntas = '".$conjuntas."' where idcuentas_bancarias = '".$idcuentas_bancarias."'")or die(mysql_error());
	if(sql_insert_cuantas_bancarias){
		echo "exito-".$idcuentas_bancarias;
		registra_transaccion("Modificar Cuentas Bancarias (".$numero_cuenta.")",$login,$fh,$pc,'cuentas_bancarias');
	}else{
		echo "fallo";
		registra_transaccion("Modificar Cuentas Bancarias ERROR (".$numero_cuenta.")",$login,$fh,$pc,'cuentas_bancarias');
	}																						
}







if($ejecutar == "eliminarCuentaBancaria"){
	$sql_consultar_cheques = mysql_query("select * from cheques_cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."' and fecha_inicio_uso != '0000-00-00'");
	$num_consultar_cheques = mysql_num_rows($sql_consultar_cheques);
	if($num_consultar_cheques == 0){
		$sql_eliminar_chequeras = mysql_query("delete from cheques_cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."'");
		$sql_eliminar_cuenta_bancaria = mysql_query("delete from cuentas_bancarias where idcuentas_bancarias = '".$idcuentas_bancarias."'");
		if($sql_eliminar_cuenta_bancaria){
			echo "exito";
			registra_transaccion("Eliminar Cuentas Bancarias (".$idcuentas_bancarias.")",$login,$fh,$pc,'cuentas_bancarias');
		}else{
			echo "fallo";
			registra_transaccion("Eliminar Cuentas Bancarias ERROR(".$idcuentas_bancarias.")",$login,$fh,$pc,'cuentas_bancarias');
		}
	}else{
		echo "tieneCheques";
	}
}







if($ejecutar == "modificarChequeras"){
			$sql_update_chequera = mysql_query("update cheques_cuentas_bancarias set idcuentas_bancarias = '".$idcuentas_bancarias."',
													chequera_numero = '".$numero_chequera."',
													cantidad_cheques = '".$cantidad_cheques."',
													numero_inicial = '".$numero_inicial."',
													numero_final = '".$numero_final."',
													digitos_consecutivos = '".$digitos_consecutivos."',
													cantidad_digitos = '".$cantidad_digitos."',
													estado = '".$estado."' where idcheques_cuentas_bancarias = '".$idchequeras."'")or die(mysql_error());
			if($sql_update_chequera){
				echo "exito";
				registra_transaccion("Modificar Chequeras (".$numero_chequera.")",$login,$fh,$pc,'cheques_cuentas_bancarias');
			}else{
				echo "fallo";
				registra_transaccion("Modificar Chequeras ERROR (".$numero_chequera.")",$login,$fh,$pc,'cheques_cuentas_bancarias');
			}

}




if($ejecutar == "eliminarChequeras"){
	$sql_eliminar_chequeras = mysql_query("delete from cheques_cuentas_bancarias where idcheques_cuentas_bancarias = '".$idchequeras."'")or die(mysql_error());
	if($sql_eliminar_chequeras){
		echo "exito";
		registra_transaccion("Eliminar Chequeras (".$idchequeras.")",$login,$fh,$pc,'cheques_cuentas_bancarias');
	}else{
		echo "fallo";
		registra_transaccion("Eliminar Chequeras ERROR (".$idchequeras.")",$login,$fh,$pc,'cheques_cuentas_bancarias');
	}
}



if($ejecutar == "sumaCheques"){
	$total = 8-$cantidad;
	$resultado = substr($suma, 1,$total);
	echo $resultado;
}

?>