<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

  
  if($ejecutar ==  "guardarDatosBasicos"){
	  
	$sql_ingresar = mysql_query("insert into asiento_contable(fecha_contable, 
														detalle,
														mes_contable,
														estado, 
														status, 
														usuario, 
														fechayhora,
														idfuente_financiamiento,
														tipo_movimiento,
														prioridad)VALUES('".$fecha_contable."',
																		'".$detalle."',
																		'".$mes_contable."',
																		'elaboracion',
																		'a',
																		'".$login."',
																		'".$fh."',
																		'".$idfuente_financiamiento."',
																		'asiento_manual',
																		'0')");
	echo mysql_insert_id();
  }
  
  
  if($ejecutar == "ajustarCuentas"){
  	
	$sql_actualizar =mysql_query("update asiento_contable set fecha_contable = '".$fecha_contable."',
																detalle = '".$detalle."',
																mes_contable = '".$mes_contable."',
																idfuente_financiamiento = '".$idfuente_financiamiento."'
										where idasiento_contable = '".$idasiento_contable."'");
} 
  
  
  if($ejecutar == "ingresarCuentas"){
 
  	$sql_consultar = mysql_query("select * from cuentas_asiento_contable where idcuenta = '".$idcuenta."' 
																			and tabla = '".$nivel_cuenta."' 
																			and idasiento_contable = '".$idasiento_contable."'");

  	
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar == 0){
	

		$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																tabla,
																idcuenta,
																afecta,
																monto
																	)values(
																			'".$idasiento_contable."',
																			'".$nivel_cuenta."',
																			'".$idcuenta."',
																			'".$tipo."',
																			'".$monto."')");

	}else{
		echo "existe";
	}
  }
  
  
  
  
  if($ejecutar == "listarCuentasSeleccionadas"){
  	$sql_consultar = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$idasiento_contable."'
  										order by afecta");
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar > 0){
	
	?>
	
	<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="5%" align="center" class="Browse" style="font-size:9px">Cuenta</td>
            <td width="59%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td width="14%" align="center" class="Browse" style="font-size:9px">Debe</td>
            <td width="14%" align="center" class="Browse" style="font-size:9px">Haber</td>
            <?
            if($estado == "elaboracion"){
			?>
            	<td align="center" class="Browse" style="font-size:9px" colspan="2">Acciones</td>
            <?
            }
			?>
          </tr>
          </thead>
          <?
			while($bus_consultar = mysql_fetch_array($sql_consultar)){
				$idcampo = "id".$bus_consultar["tabla"];
				$sql_cuentas = mysql_query("select * from ".$bus_consultar["tabla"]." 
																	where ".$idcampo." = '".$bus_consultar["idcuenta"]."'")or die(" tablas ".mysql_error());
				$bus_cuenta = mysql_fetch_array($sql_cuentas);

			
		  ?>
	          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
	                  	
	            <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_cuenta["codigo"]?></td>
	            
	            <?
	            if($estado != "elaboracion"){
		            if($bus_consultar["afecta"] == "debe"){ ?>
		            	<td class='Browse' align='left' width="59%" style="font-size:10px"><?=$bus_cuenta["denominacion"]?></td>
		            	<td class='Browse' align='right' width="15%" style="font-size:10px"><?=number_format($bus_consultar["monto"],2,",",".")?></td>
		            	<td class='Browse' align='right' width="15%" style="font-size:10px">&nbsp;</td>
		            <?
		            }else{	?>
		            	<td class='Browse' align='left' width="59%" style="font-size:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["denominacion"]?></td>
		            	<td class='Browse' align='right' width="15%" style="font-size:10px">&nbsp;</td>
		            	<td class='Browse' align='right' width="15%" style="font-size:10px"><?=number_format($bus_consultar["monto"],2,",",".")?></td>
		            <?
		            }
		        }else{
		            if($bus_consultar["afecta"] == "debe"){ ?>
		            	<td class='Browse' align='left' width="59%" style="font-size:10px"><?=$bus_cuenta["denominacion"]?></td>
		            	<input type="hidden" name="monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>" id="monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>" value="<?=$bus_consultar["monto"]?>">
		            	<td class='Browse' align='right' width="15%" style="font-size:10px">
		            		<input type="text" name="monto_actualizar_mostrado<?=$bus_consultar["idcuentas_asiento_contable"]?>" id="monto_actualizar_mostrado<?=$bus_consultar["idcuentas_asiento_contable"]?>" value="<?=number_format($bus_consultar["monto"],2,",",".")?>" onblur="formatoNumero(this.id, 'monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>')" style="text-align:right" onclick="this.select()">
		            	</td>
		            	<td class='Browse' align='right' width="15%" style="font-size:10px">&nbsp;</td>
		            <?
		            }else{	?>
		            	<td class='Browse' align='left' width="59%" style="font-size:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$bus_cuenta["denominacion"]?></td>
		            	<td class='Browse' align='right' width="15%" style="font-size:10px">&nbsp;</td>
		            	<input type="hidden" name="monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>" id="monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>" value="<?=$bus_consultar["monto"]?>">
		            	<td class='Browse' align='right' width="15%" style="font-size:10px">
		            		<input type="text" name="monto_actualizar_mostrado<?=$bus_consultar["idcuentas_asiento_contable"]?>" id="monto_actualizar_mostrado<?=$bus_consultar["idcuentas_asiento_contable"]?>" value="<?=number_format($bus_consultar["monto"],2,",",".")?>" onblur="formatoNumero(this.id, 'monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>')" style="text-align:right" onclick="this.select()">
		            	</td>
		            <?
		            }
		        }
	            ?>

	    		<?
	            if($estado == "elaboracion"){
				?>
	            <td width="3%" align="center" valign="middle" class='Browse'>
	                      <img src="imagenes/modificar.png" style="cursor:pointer" 
	                      		onClick="modificarCuentasSeleccionadas('<?=$bus_consultar["idcuentas_asiento_contable"]?>', document.getElementById('monto_actualizar<?=$bus_consultar["idcuentas_asiento_contable"]?>').value)">            
	            </td>
	            <td width="3%" align="center" valign="middle" class='Browse'>
	              <img src="imagenes/delete.png" style="cursor:pointer" 
	                      		onClick="eliminarCuentaSeleccionada('<?=$bus_consultar["idcuentas_asiento_contable"]?>')">            
	            </td>
	            <?
	            }
				?>
	  		</tr>
          <?
          }
          ?>
        <tr>
        	<td colspan='2' align="right" class="Browse" style="font-size:12px"><strong>TOTALES</strong></td>
        	<?
        	$sql_consultar = mysql_query("SELECT SUM(monto) as total FROM cuentas_asiento_contable WHERE afecta = 'debe' and idasiento_contable = '".$idasiento_contable."'");
			$bus_consultar = mysql_fetch_array($sql_consultar);
			//echo number_format($bus_consultar["total"],2,",",".");
			?>
            <td width="14%" align="right" class="Browse" style="font-size:9px"><strong><?=number_format($bus_consultar["total"],2,",",".")?></strong></td>
            <?
        	$sql_consultar = mysql_query("SELECT SUM(monto) as total FROM cuentas_asiento_contable WHERE afecta = 'haber' and idasiento_contable = '".$idasiento_contable."'");
			$bus_consultar = mysql_fetch_array($sql_consultar);
			//echo number_format($bus_consultar["total"],2,",",".");
			?>
            <td width="14%" align="right" class="Browse" style="font-size:9px"><strong><?=number_format($bus_consultar["total"],2,",",".")?></strong></td>
        </tr	
  </table>
	<?
	}else{
	echo "&nbsp;";
	}
	
  }
  
  
  
  
  
  if($ejecutar == "actualizarTotal"){
  	$sql_consultar = mysql_query("SELECT SUM(monto) as total FROM cuentas_asiento_contable WHERE afecta = '".$tipo."' and idasiento_contable = '".$idasiento_contable."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
	echo number_format($bus_consultar["total"],2,",",".");
  }
  
  
  
  if($ejecutar == "eliminarCuentaSeleccionada"){
  	$sql_eliminar = mysql_query("delete from cuentas_asiento_contable where idcuentas_asiento_contable = '".$idcuentas_asiento_contable."'");
  }
  
  
  
  if($ejecutar == "modificarCuentasSeleccionadas"){
  	$sql_consultar_cuenta = mysql_query("select * from cuentas_asiento_contable where idcuentas_asiento_contable = '".$idcuentas_asiento_contable."'");
	$bus_consultar_cuenta = mysql_fetch_array($sql_consultar_cuenta);
	$sql_actualizar =mysql_query("update cuentas_asiento_contable set monto = '".$monto_actualizar."' 
										where idcuentas_asiento_contable = '".$idcuentas_asiento_contable."'");
}
  
  
 if($ejecutar == "procesarCuentas"){
 	$sql_consultar_cuentas = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$idasiento_contable."'");
	$num_consultar_cuentas = mysql_num_rows($sql_consultar_cuentas);
	
	//echo $num_consultar_haber;
	if($num_consultar_cuentas == 0){
		echo "sinCuentas";
	}else{
       	$sql_consultar = mysql_query("SELECT SUM(monto) as total FROM cuentas_asiento_contable WHERE afecta = 'debe' and idasiento_contable = '".$idasiento_contable."'");
		$bus_consultar = mysql_fetch_array($sql_consultar);
		$total_debe = $bus_consultar["total"];
       	$sql_consultar = mysql_query("SELECT SUM(monto) as total FROM cuentas_asiento_contable WHERE afecta = 'haber' and idasiento_contable = '".$idasiento_contable."'");
		$bus_consultar = mysql_fetch_array($sql_consultar);
		$total_haber = $bus_consultar["total"];
		if($total_debe != $total_haber){
			echo "difTotales";
		}else{
			$sql_actualizar =mysql_query("update asiento_contable set estado = 'procesado'
										where idasiento_contable = '".$idasiento_contable."'");
			echo "exito";
		}
	}
 }
 
 
if($ejecutar == "consultarCuentas"){

 	$sql_primera_consulta = mysql_query("select * from asiento_contable where idasiento_contable = '".$idasiento_contable."'")or die(" consulta ".mysql_error());
	$bus_primera_consulta = mysql_fetch_array($sql_primera_consulta)or die(" arreglo ".mysql_error());
	
	echo $bus_primera_consulta["idasiento_contable"]."|.|".
		$bus_primera_consulta["estado"]."|.|".
		$bus_primera_consulta["detalle"]."|.|".
		$bus_primera_consulta["fecha_contable"]."|.|".
		$bus_primera_consulta["mes_contable"]."|.|".
		$bus_primera_consulta["idfuente_financiamiento"]."|.|".
		$bus_primera_consulta["numero_asiento"]."|.|".
		$bus_primera_consulta["reversado"]."|.|";
 }
 
 
 
if($ejecutar == "anularCuentas"){
	$sql = mysql_query("select * from usuarios where login = '".$login."' and clave = '".md5($clave)."'");
	$num = mysql_num_rows($sql);
	if($num > 0){
	 	$sql_ingresar = mysql_query("insert into asiento_contable(fecha_contable, 
															detalle,
															mes_contable,
															estado, 
															status, 
															usuario, 
															fechayhora,
															idfuente_financiamiento,
															tipo_movimiento,
															prioridad)VALUES('".$fecha_contable."',
																			'".$detalle."',
																			'".$mes_contable."',
																			'anulado',
																			'a',
																			'".$login."',
																			'".$fh."',
																			'".$idfuente_financiamiento."',
																			'asiento_manual',
																			'0')");
		$nuevo_asiento = mysql_insert_id();
		echo mysql_insert_id();

		$sql_actualizar =mysql_query("update asiento_contable set reversado = 'si'
										where idasiento_contable = '".$idasiento_contable."'");

		$sql_consultar = mysql_query("select * from cuentas_asiento_contable where idasiento_contable = '".$idasiento_contable."' ");
	  	while($bus_consultar = mysql_fetch_array($sql_consultar)){
	  		if ($bus_consultar["afecta"] == 'debe'){ $tipo = 'haber'; }else{ $tipo='debe'; }
				$sql_cuenta_contable_debe = mysql_query("insert into cuentas_asiento_contable (idasiento_contable,
																	tabla,
																	idcuenta,
																	afecta,
																	monto
																		)values(
																				'".$nuevo_asiento."',
																				'".$bus_consultar["tabla"]."',
																				'".$bus_consultar["idcuenta"]."',
																				'".$tipo."',
																				'".$bus_consultar["monto"]."')");

		}
	}else{
		echo "claveIncorrecta";
	}	
 }
?>