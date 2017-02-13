<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "listarOrdenes"){
	if($tipo_asiento == "causados"){
//	echo "prueba";
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Nro. Orden</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td width="22%" align="center" class="Browse" style="font-size:9px">Beneficiario</td>
            <td width="56%" align="center" class="Browse" style="font-size:9px">Justificacion</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Monto</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
          $sql_consultar = mysql_query("select orden_pago.idorden_pago as idorden_pago,
		  										orden_pago.numero_orden as nro_orden,
												orden_pago.fecha_orden as fecha,
												orden_pago.justificacion as justificacion,
												orden_pago.total as total,
												orden_pago.exento as exento,
												orden_pago.impuesto as impuesto,
												orden_pago.sub_total as sub_total,
												orden_pago.total_retenido as retenido,
												orden_pago.total_a_pagar as total_a_pagar,
												beneficiarios.nombre as beneficiario,
												tipos_documentos.multi_categoria
													FROM
												orden_pago,
												beneficiarios,
												tipos_documentos
													WHERE
												orden_pago.contabilizado = 'no'
												and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios
												and tipos_documentos.idtipos_documentos = orden_pago.tipo
												and (orden_pago.estado = 'procesado' or orden_pago.estado = 'pagada')
												and (beneficiarios.nombre like '%".$texto_buscar."%'
												or orden_pago.justificacion like '%".$texto_buscar."%'
												or orden_pago.numero_orden like '%".$texto_buscar."%')")or die(mysql_error());
			while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["nro_orden"]?></td>
            <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["fecha"]?></td>
            <td class='Browse' align='left' width="22%" style="font-size:10px"><?=$bus_consultar["beneficiario"]?></td>
            <td class='Browse' align='left' width="56%" style="font-size:10px"><?=$bus_consultar["justificacion"]?></td>
            <td class='Browse' align="right" width="4%" style="font-size:10px"><?=number_format($bus_consultar["total"],2,",",".")?></td>
    		<td width="4%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/validar.png" style="cursor:pointer" 
                      		onClick="document.getElementById('numero_documento').value = '<?=$bus_consultar["nro_orden"]?>',
                            		document.getElementById('fecha_documento').value = '<?=$bus_consultar["fecha"]?>',
                                    document.getElementById('beneficiario').value = '<?=$bus_consultar["beneficiario"]?>',
                                    document.getElementById('justificacion').value = '<?=$bus_consultar["justificacion"]?>',
                                    <? if ($bus_consultar["multi_categoria"] == 'no'){?>
                                    	document.getElementById('titulo_exento').innerHTML = 'Exento',
                                        document.getElementById('celda_exento').style.display = 'block',
                                        document.getElementById('exento').value = '<?=number_format($bus_consultar["exento"],2,",",".")?>',
                                        document.getElementById('titulo_subtotal').innerHTML = 'Sub-total',
                                        document.getElementById('celda_sub_total').style.display = 'block',
                                        document.getElementById('sub_total').value = '<?=number_format($bus_consultar["sub_total"],2,",",".")?>',
                                        document.getElementById('titulo_impuesto').innerHTML = 'Impuesto',
                                        document.getElementById('celda_impuesto').style.display = 'block',
                                        document.getElementById('impuesto').value = '<?=number_format($bus_consultar["impuesto"],2,",",".")?>',
                                        document.getElementById('titulo_totalneto').innerHTML = 'Total Neto',
                                        document.getElementById('celda_total').style.display = 'block',
                                        document.getElementById('total_neto').value = '<?=number_format($bus_consultar["total"],2,",",".")?>',
                                        document.getElementById('titulo_retenido').innerHTML = 'Retenido',
                                        document.getElementById('celda_retenido').style.display = 'block',
                                        document.getElementById('retenido').value = '<?=number_format($bus_consultar["retenido"],2,",",".")?>',
                                        document.getElementById('titulo_totalpagar').innerHTML = 'Pagado',
                                        document.getElementById('celda_total_pagar').style.display = 'block',
                                        document.getElementById('total_pagar').value = '<?=number_format($bus_consultar["total_a_pagar"],2,",",".")?>',
                                    <? }else{ ?>
                                    	document.getElementById('titulo_exento').innerHTML = '',
                                        document.getElementById('celda_exento').style.display = 'none',
                                    	document.getElementById('exento').value = '<?=number_format($bus_consultar["exento"],2,",",".")?>',
                                        document.getElementById('titulo_subtotal').innerHTML = '',
                                        document.getElementById('celda_sub_total').style.display = 'none',
                                        document.getElementById('sub_total').value = '<?=number_format($bus_consultar["sub_total"],2,",",".")?>',
                                        document.getElementById('titulo_impuesto').innerHTML = '',
                                        document.getElementById('celda_impuesto').style.display = 'none',
                                        document.getElementById('impuesto').value = '<?=number_format($bus_consultar["impuesto"],2,",",".")?>',
                                        document.getElementById('titulo_totalneto').innerHTML = 'Asignaciones',
                                        document.getElementById('celda_total').style.display = 'block',
                                    	document.getElementById('total_neto').value = '<?=number_format($bus_consultar["sub_total"],2,",",".")?>',
                                        document.getElementById('titulo_retenido').innerHTML = 'Deducciones',
                                        document.getElementById('celda_retenido').style.display = 'block',
                                        document.getElementById('retenido').value = '<?=number_format($bus_consultar["exento"],2,",",".")?>',
                                        document.getElementById('titulo_totalpagar').innerHTML = 'Total pagado',
                                        document.getElementById('celda_total_pagar').style.display = 'block',
                                        document.getElementById('total_pagar').value = '<?=number_format($bus_consultar["total"],2,",",".")?>',
                                    <? } ?>
                                    document.getElementById('idorden_pago').value = '<?=$bus_consultar["idorden_pago"]?>',
                                    document.getElementById('tipo_asiento_oculto').value = 'causados',
                                    document.getElementById('boton_siguiente').style.display = 'block',
                                    document.getElementById('tabla_debe').style.display = 'none',
                                    document.getElementById('tabla_haber').style.display = 'none',
                                    document.getElementById('idcuentas_t').value = '',
                                    document.getElementById('estado').value = '',
                                    document.getElementById('boton_procesar').style.display = 'none',
                                    document.getElementById('boton_anular').style.display = 'none',
                                    document.getElementById('celda_cuentas_seleccionadas_debe').innerHTML = '',
                                    document.getElementById('celda_cuentas_seleccionadas_haber').innerHTML = ''"> 
            </td>
  </tr>
          <?
          }
          ?>
  </table>
  <?
  		}else if($tipo_asiento == "ingresos"){
			?>
			
			<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Tipo</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Nro. Documento</td>
            <td width="22%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td width="56%" align="center" class="Browse" style="font-size:9px">Justificacion</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Monto</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
          $sql_consultar = mysql_query("select * from ingresos_egresos_financieros
													WHERE
												contabilizado = 'no'
												and numero_documento like '%".$texto_buscar."%'
												and concepto like '%".$texto_buscar."%'")or die(mysql_error());
			while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="7%" style="font-size:10px">
					<?
                    if($bus_consultar["tipo"] == "egreso"){
					echo "Egreso";
					}else{
					echo "Ingreso";
					}?>
                    </td>
                    <td class='Browse' align='left' width="7%" style="font-size:10px">&nbsp;<?=$bus_consultar["numero_documento"]?></td>
                    <td class='Browse' align='left' width="22%" style="font-size:10px">&nbsp;<?=$bus_consultar["fecha"]?></td>
                    <td class='Browse' align='left' width="56%" style="font-size:10px">&nbsp;<?=$bus_consultar["concepto"]?></td>
                    <td class='Browse' align="right" width="4%" style="font-size:10px">&nbsp;<?=number_format($bus_consultar["monto"],2,",",".")?></td>
                    <td width="4%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/validar.png" style="cursor:pointer" 
                      		onClick="document.getElementById('numero_documento').value = '<?=$bus_consultar["numero_documento"]?>',
                            		document.getElementById('fecha_documento').value = '<?=$bus_consultar["fecha"]?>',
                                    document.getElementById('beneficiario').value = '',
                                    document.getElementById('justificacion').value = '<?=$bus_consultar["concepto"]?>',
                                    document.getElementById('titulo_exento').innerHTML = 'Monto Bs.',
                                    document.getElementById('celda_exento').style.display = 'block',
                                    document.getElementById('exento').value = '<?=number_format($bus_consultar["monto"],2,",",".")?>',
                                    document.getElementById('total_neto').value = '<?=number_format($bus_consultar["monto"],2,",",".")?>',
                                    document.getElementById('impuesto').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('sub_total').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('retenido').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('total_pagar').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('idorden_pago').value = '<?=$bus_consultar["idingresos_financieros"]?>',
                                    document.getElementById('tipo_asiento_oculto').value = 'ingresos',
                                    document.getElementById('boton_siguiente').style.display = 'block',
                                    document.getElementById('tabla_debe').style.display = 'none',
                                    document.getElementById('tabla_haber').style.display = 'none',
                                    document.getElementById('idcuentas_t').value = '',
                                    document.getElementById('estado').value = '',
                                    document.getElementById('boton_procesar').style.display = 'none',
                                    document.getElementById('boton_anular').style.display = 'none',
                                    document.getElementById('celda_cuentas_seleccionadas_debe').innerHTML = '',
                                    document.getElementById('celda_cuentas_seleccionadas_haber').innerHTML = ''"> 
            </td>
  </tr>
          <?
          }
          ?>
  </table>
			<?
		}else if($tipo_asiento == "compromisos"){
		?>
		
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Nro. Orden</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td width="22%" align="center" class="Browse" style="font-size:9px">Beneficiario</td>
            <td width="56%" align="center" class="Browse" style="font-size:9px">Justificacion</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Monto</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
          $sql_consultar = mysql_query("select orden_compra_servicio.idorden_compra_servicio as idorden_compra_servicio,
		  										orden_compra_servicio.numero_orden as nro_orden,
												orden_compra_servicio.fecha_orden as fecha,
												orden_compra_servicio.justificacion as justificacion,
												orden_compra_servicio.exento as exento,
												orden_compra_servicio.impuesto as impuesto,
												orden_compra_servicio.sub_total as sub_total,
												orden_compra_servicio.total as total,
												beneficiarios.nombre as beneficiario,
												tipos_documentos.multi_categoria
													FROM
												orden_compra_servicio,
												beneficiarios,
												tipos_documentos
													WHERE
												orden_compra_servicio.contabilizado = 'no'
												and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'pagado')
												and (beneficiarios.nombre like '%".$texto_buscar."%'
													or orden_compra_servicio.justificacion like '%".$texto_buscar."%'
													or orden_compra_servicio.numero_orden like '%".$texto_buscar."%')")or die(mysql_error());
			while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["nro_orden"]?></td>
            <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["fecha"]?></td>
            <td class='Browse' align='left' width="22%" style="font-size:10px"><?=$bus_consultar["beneficiario"]?></td>
            <td class='Browse' align='left' width="56%" style="font-size:10px"><?=$bus_consultar["justificacion"]?></td>
            <td class='Browse' align="right" width="4%" style="font-size:10px"><?=number_format($bus_consultar["total"],2,",",".")?></td>
    		<td width="4%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/validar.png" style="cursor:pointer" 
                      		onClick="document.getElementById('numero_documento').value = '<?=$bus_consultar["nro_orden"]?>',
                            		document.getElementById('fecha_documento').value = '<?=$bus_consultar["fecha"]?>',
                                    document.getElementById('beneficiario').value = '<?=$bus_consultar["beneficiario"]?>',
                                    document.getElementById('justificacion').value = '<?=$bus_consultar["justificacion"]?>',
                                    <? if ($bus_consultar["multi_categoria"] == 'no'){?>
                                    	document.getElementById('titulo_exento').innerHTML = 'Exento',
                                        document.getElementById('celda_exento').style.display = 'block',
                                        document.getElementById('exento').value = '<?=number_format($bus_consultar["exento"],2,",",".")?>',
                                        document.getElementById('titulo_subtotal').innerHTML = 'Sub-total',
                                        document.getElementById('celda_sub_total').style.display = 'block',
                                        document.getElementById('sub_total').value = '<?=number_format($bus_consultar["sub_total"],2,",",".")?>',
                                        document.getElementById('titulo_impuesto').innerHTML = 'Impuesto',
                                        document.getElementById('celda_impuesto').style.display = 'block',
                                        document.getElementById('impuesto').value = '<?=number_format($bus_consultar["impuesto"],2,",",".")?>',
                                        document.getElementById('titulo_totalneto').innerHTML = 'Total Neto',
                                        document.getElementById('celda_total').style.display = 'block',
                                        document.getElementById('total_neto').value = '<?=number_format($bus_consultar["total"],2,",",".")?>',
                                        document.getElementById('titulo_retenido').innerHTML = '',
                                        document.getElementById('celda_retenido').style.display = 'none',
                                        document.getElementById('retenido').value = '<?=number_format(0,2,",",".")?>',
                                        document.getElementById('titulo_totalpagar').innerHTML = '',
                                        document.getElementById('celda_total_pagar').style.display = 'none',
                                        document.getElementById('total_pagar').value = '<?=number_format(0,2,",",".")?>',
                                    <? }else{ ?>
                                    	document.getElementById('titulo_exento').innerHTML = '',
                                        document.getElementById('celda_exento').style.display = 'none',
                                        document.getElementById('exento').value = '<?=number_format($bus_consultar["exento"],2,",",".")?>',
                                        document.getElementById('titulo_subtotal').innerHTML = 'Asignaciones',
                                        document.getElementById('celda_sub_total').style.display = 'block',
                                        document.getElementById('sub_total').value = '<?=number_format($bus_consultar["sub_total"],2,",",".")?>',
                                        document.getElementById('titulo_impuesto').innerHTML = 'Deducciones',
                                        document.getElementById('celda_impuesto').style.display = 'block',
                                        document.getElementById('impuesto').value = '<?=number_format($bus_consultar["exento"],2,",",".")?>',
                                        document.getElementById('titulo_totalneto').innerHTML = 'Total Neto',
                                        document.getElementById('celda_total').style.display = 'block',
                                        document.getElementById('total_neto').value = '<?=number_format($bus_consultar["total"],2,",",".")?>',
                                        document.getElementById('titulo_retenido').innerHTML = '',
                                        document.getElementById('celda_retenido').style.display = 'none',
                                        document.getElementById('retenido').value = '<?=number_format(0,2,",",".")?>',
                                        document.getElementById('titulo_totalpagar').innerHTML = '',
                                        document.getElementById('celda_total_pagar').style.display = 'none',
                                        document.getElementById('total_pagar').value = '<?=number_format(0,2,",",".")?>',
                                    <? } ?>
                                    document.getElementById('idorden_pago').value = '<?=$bus_consultar["idorden_compra_servicio"]?>',
                                    document.getElementById('tipo_asiento_oculto').value = 'compromisos',
                                    document.getElementById('boton_siguiente').style.display = 'block',
                                    document.getElementById('tabla_debe').style.display = 'none',
                                    document.getElementById('tabla_haber').style.display = 'none',
                                    document.getElementById('idcuentas_t').value = '',
                                    document.getElementById('estado').value = '',
                                    document.getElementById('boton_procesar').style.display = 'none',
                                    document.getElementById('boton_anular').style.display = 'none',
                                    document.getElementById('celda_cuentas_seleccionadas_debe').innerHTML = '',
                                    document.getElementById('celda_cuentas_seleccionadas_haber').innerHTML = ''"> 
            </td>
  </tr>
          <?
          }
          ?>
  </table>
		<?
		}else if($tipo_asiento == "pagados"){
			?>
		
		<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Nro. Cheque</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Nro. Orden</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td width="22%" align="center" class="Browse" style="font-size:9px">Beneficiario</td>
            <td width="56%" align="center" class="Browse" style="font-size:9px">Justificacion</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Monto</td>
            <td width="4%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
          $sql_consultar = mysql_query("select pagos_financieros.idpagos_financieros,
		  										pagos_financieros.numero_documento,
		  										pagos_financieros.numero_cheque,
												pagos_financieros.fecha_cheque,
												beneficiarios.nombre,
												orden_pago.justificacion,
												orden_pago.numero_orden,
												orden_pago.total
													FROM
												pagos_financieros,
												beneficiarios,
												orden_pago
													WHERE
												pagos_financieros.contabilizado = 'no'
												and orden_pago.idorden_pago = pagos_financieros.idorden_pago
												and orden_pago.idbeneficiarios = beneficiarios.idbeneficiarios
												and (beneficiarios.nombre like '%".$texto_buscar."%'
												or orden_pago.justificacion like '%".$texto_buscar."%'
												or orden_pago.numero_orden like '%".$texto_buscar."%'
												or pagos_financieros.numero_cheque like '%".$texto_buscar."%')
												group by pagos_financieros.numero_cheque")or die(mysql_error());
			while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["numero_cheque"]?></td>
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["numero_orden"]?></td>
            <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["fecha_cheque"]?></td>
            <td class='Browse' align='left' width="22%" style="font-size:10px"><?=$bus_consultar["nombre"]?></td>
            <td class='Browse' align='left' width="56%" style="font-size:10px"><?=$bus_consultar["justificacion"]?></td>
            <td class='Browse' align="right" width="4%" style="font-size:10px"><?=number_format($bus_consultar["total"],2,",",".")?></td>
    		<td width="4%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/validar.png" style="cursor:pointer" 
                      		onClick="document.getElementById('numero_documento').value = '<?=$bus_consultar["numero_cheque"]?> / <?=$bus_consultar["numero_orden"]?>',
                            		document.getElementById('fecha_documento').value = '<?=$bus_consultar["fecha_cheque"]?>',
                                    document.getElementById('beneficiario').value = '<?=$bus_consultar["nombre"]?>',
                                    document.getElementById('justificacion').value = '<?=$bus_consultar["justificacion"]?>',
                                    document.getElementById('titulo_exento').innerHTML = 'Total Bs.',
                                    document.getElementById('celda_exento').style.display = 'block',
                                    document.getElementById('exento').value = '<?=number_format($bus_consultar["total"],2,",",".")?>',
                                    document.getElementById('titulo_subtotal').innerHTML = '',
                                    document.getElementById('celda_sub_total').style.display = 'none',
                                    document.getElementById('sub_total').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('titulo_impuesto').innerHTML = '',
                                    document.getElementById('celda_impuesto').style.display = 'none',
                                    document.getElementById('impuesto').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('titulo_totalneto').innerHTML = '',
                                    document.getElementById('celda_total').style.display = 'none',
                                    document.getElementById('total_neto').value = '<?=number_format($bus_consultar["total"],2,",",".")?>',
                                    document.getElementById('titulo_retenido').innerHTML = '',
                                    document.getElementById('celda_retenido').style.display = 'none',
                                    document.getElementById('retenido').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('titulo_totalpagar').innerHTML = '',
                                    document.getElementById('celda_total_pagar').style.display = 'none',
                                    document.getElementById('total_pagar').value = '<?=number_format(0,2,",",".")?>',
                                    document.getElementById('idorden_pago').value = '<?=$bus_consultar["idpagos_financieros"]?>',
                                    document.getElementById('tipo_asiento_oculto').value = 'pagados',
                                    document.getElementById('boton_siguiente').style.display = 'block',
                                    document.getElementById('tabla_debe').style.display = 'none',
                                    document.getElementById('tabla_haber').style.display = 'none',
                                    document.getElementById('idcuentas_t').value = '',
                                    document.getElementById('estado').value = '',
                                    document.getElementById('boton_procesar').style.display = 'none',
                                    document.getElementById('boton_anular').style.display = 'none',
                                    document.getElementById('celda_cuentas_seleccionadas_debe').innerHTML = '',
                                    document.getElementById('celda_cuentas_seleccionadas_haber').innerHTML = ''"> 
            </td>
  </tr>
          <?
          }
          ?>
  </table>
		<?		
		}
  }
  
  
  
  
  
  if($ejecutar ==  "guardarDatosBasicos"){
	$sql_ingresar = mysql_query("insert into cuentas_t(idorden_pago, 
														estado, 
														status, 
														usuario, 
														fechayhora,
														tipo_asiento)VALUES('".$idorden_pago."',
																		'elaboracion',
																		'a',
																		'".$login."',
																		'".$fh."',
																		'".$tipo_asiento."')");
	echo mysql_insert_id();
  }
  
  
  
  
  
  if($ejecutar == "ingresarCuentas"){
 
  	$sql_consultar = mysql_query("select * from cuentas_t_seleccionadas where idcuenta = '".$idcuenta."' 
																			and nivel = '".$nivel_cuenta."' 
																			and tipo = '".$tipo."'
																			and idcuenta_t = '".$idcuenta_t."'");
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar == 0){
	//echo "PRUEBA:".$tipo_asiento;
  		if($tipo_asiento == "causados"){
			$sql_orden = mysql_query("select orden_pago.total as total 
												from 
												orden_pago, cuentas_t 
												where 
												orden_pago.idorden_pago = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and idcuentas_t = '".$idcuenta_t."'")or die(mysql_error());
		}else if($tipo_asiento == "compromisos"){
			$sql_orden = mysql_query("select orden_compra_servicio.total as total 
												from 
												orden_compra_servicio, cuentas_t 
												where 
												orden_compra_servicio.idorden_compra_servicio = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and cuentas_t.idcuentas_t = '".$idcuenta_t."'")or die(mysql_error());
		}else if($tipo_asiento == "ingresos"){
			//echo "entro";
			$sql_orden = mysql_query("select ingresos_egresos_financieros.monto as total 
												from 
												ingresos_egresos_financieros, cuentas_t 
												where 
												ingresos_egresos_financieros.idingresos_financieros = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and cuentas_t.idcuentas_t = '".$idcuenta_t."'")or die(mysql_error());
		}else if($tipo_asiento == "pagados"){
			$sql_orden = mysql_query("select pagos_financieros.monto_cheque as total 
												from 
												pagos_financieros, cuentas_t 
												where 
												pagos_financieros.idpagos_financieros = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and cuentas_t.idcuentas_t = '".$idcuenta_t."'")or die(mysql_error());
		}
		$bus_orden = mysql_fetch_array($sql_orden);
		
		
		
		
		
		
		
		$sql_suma = mysql_query("select SUM(monto) as total from cuentas_t_seleccionadas 
															where 
															idcuenta_t = '".$idcuenta_t."'
															and tipo = '".$tipo."'");
		$bus_suma = mysql_fetch_array($sql_suma);
		$total_suma = $bus_suma["total"] + $monto;
		//echo "AQUI".$total_suma;
		if($total_suma > $bus_orden["total"]){
			echo "supero_monto";
		}else{
		$sql_ingresar = mysql_query("INSERT INTO cuentas_t_seleccionadas(idcuenta,
																	nivel,
																	monto,
																	tipo,
																	idcuenta_t)VALUES('".$idcuenta."',
																				'".$nivel_cuenta."',
																				'".$monto."',
																				'".$tipo."',
																				'".$idcuenta_t."')");
		}
	}else{
		echo "existe";
	}
  }
  
  
  
  
  if($ejecutar == "listarCuentasSeleccionadas"){
  	$sql_consultar = mysql_query("select * from cuentas_t_seleccionadas where tipo = '".$tipo."' and idcuenta_t = '".$idcuenta_t."'");
	$num_consultar = mysql_num_rows($sql_consultar);
	if($num_consultar > 0){
	
	?>
	
	<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="21%" align="center" class="Browse" style="font-size:9px">Cuenta</td>
            <td width="59%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td width="14%" align="center" class="Browse" style="font-size:9px">Monto</td>
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
			if($bus_consultar["nivel"] == 'subcuenta_primer'){
				$sql_cuenta = mysql_query("select grupo_cuentas_contables.codigo as codigo_grupo,
				subgrupo_cuentas_contables.codigo as codigo_subgrupo,
				rubro_cuentas_contables.codigo as codigo_rubro,
				cuenta_cuentas_contables.codigo as codigo_cuenta,
				subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
				subcuenta_primer_cuentas_contables.denominacion as denominacion,
				subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables as idcuenta
					FROM 
				grupo_cuentas_contables, 
				subgrupo_cuentas_contables, 
				rubro_cuentas_contables, 
				cuenta_cuentas_contables,
				subcuenta_primer_cuentas_contables
					WHERE 
				subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = '".$bus_consultar["idcuenta"]."'
				and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
				and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
				and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo 
				and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo")or die(mysql_error());
				$bus_cuenta = mysql_fetch_array($sql_cuenta);
				
				$codigo_cuenta = $bus_cuenta["codigo_grupo"].".".
								 $bus_cuenta["codigo_subgrupo"].".".
								 $bus_cuenta["codigo_rubro"].".".
								 $bus_cuenta["codigo_cuenta"].".".
								 $bus_cuenta["codigo_subcuenta_primer"];
								 
			}else if($bus_consultar["nivel"] == 'subcuenta_segundo'){
				$sql_cuenta = mysql_query("select grupo_cuentas_contables.codigo as codigo_grupo,
				subgrupo_cuentas_contables.codigo as codigo_subgrupo,
				rubro_cuentas_contables.codigo as codigo_rubro,
				cuenta_cuentas_contables.codigo as codigo_cuenta,
				subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
				subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.denominacion as denominacion,
				subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idcuenta
					FROM 
				grupo_cuentas_contables, 
				subgrupo_cuentas_contables, 
				rubro_cuentas_contables, 
				cuenta_cuentas_contables,
				subcuenta_primer_cuentas_contables,
				subcuenta_segundo_cuentas_contables
					WHERE 
				subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables = '".$bus_consultar["idcuenta"]."'
				and subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
				and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
				and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
				and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo 
				and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo")or die(mysql_error());
				$bus_cuenta = mysql_fetch_array($sql_cuenta);
				$codigo_cuenta = $bus_cuenta["codigo_grupo"].".".
								 $bus_cuenta["codigo_subgrupo"].".".
								 $bus_cuenta["codigo_rubro"].".".
								 $bus_cuenta["codigo_cuenta"].".".
								 $bus_cuenta["codigo_subcuenta_primer"].".".
								 $bus_cuenta["codigo_subcuenta_segundo"];
			}else{
				$sql_cuenta = mysql_query("SELECT grupo_cuentas_contables.codigo as codigo_grupo,
			subgrupo_cuentas_contables.codigo as codigo_subgrupo,
			rubro_cuentas_contables.codigo as codigo_rubro,
			cuenta_cuentas_contables.codigo as codigo_cuenta,
			subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
			subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
			subcuenta_segundo_cuentas_contables.denominacion as denominacion_subcuenta_segundo,
			subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idsubcuenta_segundo,
			desagregacion_cuentas_contables.codigo as codigo_desagregacion,
			desagregacion_cuentas_contables.denominacion as denominacion,
			desagregacion_cuentas_contables.iddesagregacion_cuentas_contables as idcuenta
				FROM 
			grupo_cuentas_contables,
			subgrupo_cuentas_contables,
			rubro_cuentas_contables,
			cuenta_cuentas_contables,
			subcuenta_primer_cuentas_contables,
			subcuenta_segundo_cuentas_contables,
			desagregacion_cuentas_contables
				WHERE
			desagregacion_cuentas_contables.iddesagregacion_cuentas_contables = '".$bus_consultar["idcuenta"]."'
			and subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables = desagregacion_cuentas_contables.idsubcuenta_segundo
			and subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
			and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
			and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
			and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
			and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo");
			$bus_cuenta = mysql_fetch_array($sql_cuenta);
				$codigo_cuenta = $bus_cuenta["codigo_grupo"].".".
								 $bus_cuenta["codigo_subgrupo"].".".
								 $bus_cuenta["codigo_rubro"].".".
								 $bus_cuenta["codigo_cuenta"].".".
								 $bus_cuenta["codigo_subcuenta_primer"].".".
								 $bus_cuenta["codigo_subcuenta_segundo"].".".
								 $bus_cuenta["codigo_desagregacion"];
			}
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
            <td class='Browse' align='left' width="21%" style="font-size:10px"><?=$codigo_cuenta?></td>
            <td class='Browse' align='left' width="59%" style="font-size:10px"><?=$bus_cuenta["denominacion"]?></td>
            <td class='Browse' align="right" width="14%" style="font-size:10px">
				<?
                if($estado != "elaboracion"){
					echo number_format($bus_consultar["monto"],2,",",".");
                }else{
					?>
                    	<input type="hidden" name="monto_actualizar<?=$bus_consultar["idcuentas_t_seleccionadas"]?>" id="monto_actualizar<?=$bus_consultar["idcuentas_t_seleccionadas"]?>" value="<?=$bus_consultar["monto"]?>">
                        
						<input type="text" name="monto_actualizar_mostrado<?=$bus_consultar["idcuentas_t_seleccionadas"]?>" id="monto_actualizar_mostrado<?=$bus_consultar["idcuentas_t_seleccionadas"]?>" value="<?=number_format($bus_consultar["monto"],2,",",".")?>" onblur="formatoNumero(this.id, 'monto_actualizar<?=$bus_consultar["idcuentas_t_seleccionadas"]?>')" style="text-align:right" onclick="this.select()">
					<?
				}
				?>
            </td>
    		<?
            if($estado == "elaboracion"){
			?>
            <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png" style="cursor:pointer" 
                      		onClick="modificarCuentasSeleccionadas('<?=$bus_consultar["idcuentas_t_seleccionadas"]?>','<?=$tipo?>', document.getElementById('monto_actualizar<?=$bus_consultar["idcuentas_t_seleccionadas"]?>').value)">            
            </td>
            <td width="3%" align="center" valign="middle" class='Browse'>
              <img src="imagenes/delete.png" style="cursor:pointer" 
                      		onClick="eliminarCuentaSeleccionada('<?=$bus_consultar["idcuentas_t_seleccionadas"]?>','<?=$tipo?>')">            
            </td>
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
	echo "&nbsp;";
	}
	
  }
  
  
  
  
  
  if($ejecutar == "actualizarTotal"){
  	$sql_consultar = mysql_query("SELECT SUM(monto) as total FROM cuentas_t_seleccionadas WHERE tipo = '".$tipo."' and idcuenta_t = '".$idcuenta_t."'");
	$bus_consultar = mysql_fetch_array($sql_consultar);
	echo number_format($bus_consultar["total"],2,",",".");
  }
  
  
  
  if($ejecutar == "eliminarCuentaSeleccionada"){
  	$sql_eliminar = mysql_query("delete from cuentas_t_seleccionadas where idcuentas_t_seleccionadas = '".$idcuentas_t_seleccionadas."'");
  }
  
  
  
  if($ejecutar == "modificarCuentasSeleccionadas"){
  	$sql_consultar_cuenta = mysql_query("select * from cuentas_t_seleccionadas where idcuentas_t_seleccionadas = '".$idcuentas_t_seleccionadas."'");
	$bus_consultar_cuenta = mysql_fetch_array($sql_consultar_cuenta);
	
		
		
		if($tipo_asiento == "causados"){
			$sql_orden = mysql_query("select orden_pago.total as total 
												from 
												orden_pago, cuentas_t 
												where 
												orden_pago.idorden_pago = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and idcuentas_t = '".$bus_consultar_cuenta["idcuenta_t"]."'")or die(mysql_error());
		}else if($tipo_asiento == "compromisos"){
			$sql_orden = mysql_query("select orden_compra_servicio.total as total 
												from 
												orden_compra_servicio, cuentas_t 
												where 
												orden_compra_servicio.idorden_compra_servicio = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and cuentas_t.idcuentas_t = '".$bus_consultar_cuenta["idcuenta_t"]."'")or die(mysql_error());
		}else if($tipo_asiento == "ingresos"){
			$sql_orden = mysql_query("select ingresos_egresos_financieros.monto as total 
												from 
												ingresos_egresos_financieros, cuentas_t 
												where 
												ingresos_egresos_financieros.idingresos_financieros = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and cuentas_t.idcuentas_t = '".$bus_consultar_cuenta["idcuenta_t"]."'")or die(mysql_error());
		}else if($tipo_asiento == "pagados"){
			$sql_orden = mysql_query("select pagos_financieros.monto_cheque as total 
												from 
												pagos_financieros, cuentas_t 
												where 
												pagos_financieros.idpagos_financieros = cuentas_t.idorden_pago
												and cuentas_t.tipo_asiento = '".$tipo_asiento."'
												and cuentas_t.idcuentas_t = '".$bus_consultar_cuenta["idcuenta_t"]."'")or die(mysql_error());
		}
		$bus_orden = mysql_fetch_array($sql_orden);
		
		
		$sql_suma = mysql_query("select SUM(monto) as total from cuentas_t_seleccionadas 
															where 
															idcuenta_t = '".$bus_consultar_cuenta["idcuenta_t"]."'
															and tipo = '".$bus_consultar_cuenta["tipo"]."'");
		$bus_suma = mysql_fetch_array($sql_suma);
		$total_suma = ($bus_suma["total"] - $bus_consultar_cuenta["monto"]) + $monto_actualizar;
		//echo $total_suma;
		if($total_suma > $bus_orden["total"]){
			echo "supero_monto";
		}else{
  			$sql_actualizar =mysql_query("update cuentas_t_seleccionadas set monto = '".$monto_actualizar."' where idcuentas_t_seleccionadas = '".$idcuentas_t_seleccionadas."'");
		}
  }
  
  
 if($ejecutar == "procesarCuentas"){
 	$sql_consultar_debe = mysql_query("select * from cuentas_t_seleccionadas where idcuenta_t = '".$idcuentas_t."' and tipo = 'debe'");
	$num_consultar_debe = mysql_num_rows($sql_consultar_debe);
	$sql_consultar_haber = mysql_query("select * from cuentas_t_seleccionadas where idcuenta_t = '".$idcuentas_t."' and tipo = 'haber'");
	$num_consultar_haber = mysql_num_rows($sql_consultar_haber);
	//echo $num_consultar_haber;
	if($num_consultar_debe == 0 or $num_consultar_haber == 0){
		echo "sinCuentas";
	}else{
 		$sql_procesar = mysql_query("update cuentas_t set estado = 'procesado', fecha_contabilizacion = '".date("Y-m-d")."' where idcuentas_t = '".$idcuentas_t."'");
		$sql_cuentas_t = mysql_query("select * from cuentas_t where idcuentas_t = '".$idcuentas_t."'");
		$bus_cuentas_t = mysql_fetch_array($sql_cuentas_t);
		if($bus_cuentas_t["tipo_asiento"] == "causados"){
			$sql_op = mysql_query("update orden_pago set contabilizado = 'si' where idorden_pago = '".$bus_cuentas_t["idorden_pago"]."'");
		}else if($bus_cuentas_t["tipo_asiento"] == "compromisos"){
			$sql_op = mysql_query("update orden_compra_servicio set contabilizado = 'si' where idorden_compra_servicio = '".$bus_cuentas_t["idorden_pago"]."'");
		}else if($bus_cuentas_t["tipo_asiento"] == "pagados"){
			$sql_op = mysql_query("update pagos_financieros set contabilizado = 'si' where idpagos_financieros = '".$bus_cuentas_t["idorden_pago"]."'");
		}else if($bus_cuentas_t["tipo_asiento"] == "ingresos"){
			$sql_op = mysql_query("update ingresos_egresos_financieros set contabilizado = 'si' where idingresos_financieros = '".$bus_cuentas_t["idorden_pago"]."'");		
		}
		
	}
 }
 
 
 if($ejecutar == "consultarCuentas"){
 	$sql_primera_consulta = mysql_query("select * from cuentas_t where idcuentas_t = '".$idcuentas_t."'");
	$bus_primera_consulta = mysql_fetch_array($sql_primera_consulta);
	
	if($bus_primera_consulta["tipo_asiento"] == "ingresos"){
		$sql_consultar = mysql_query("SELECT 
										cuentas_t.idorden_pago as idorden_pago,
										cuentas_t.estado as estado,
										cuentas_t.fecha_contabilizacion as fecha_contabilizacion,
										ingresos_egresos_financieros.numero_documento as numero_orden,
										ingresos_egresos_financieros.fecha as fecha_orden,
										ingresos_egresos_financieros.monto as total,
										ingresos_egresos_financieros.concepto as justificacion
											FROM 
										cuentas_t, 
										ingresos_egresos_financieros
											WHERE
										cuentas_t.idcuentas_t = '".$idcuentas_t."'
										and ingresos_egresos_financieros.idingresos_financieros = cuentas_t.idorden_pago")or die(mysql_error());
	}else if($bus_primera_consulta["tipo_asiento"] == "causados"){
		$sql_consultar = mysql_query("SELECT 
										cuentas_t.idorden_pago as idorden_pago,
										cuentas_t.estado as estado,
										cuentas_t.fecha_contabilizacion as fecha_contabilizacion,
										orden_pago.numero_orden as numero_orden,
										orden_pago.fecha_orden as fecha_orden,
										orden_pago.total as total,
										orden_pago.exento as exento,
										orden_pago.sub_total as sub_total,
										orden_pago.impuesto as impuesto,
										orden_pago.total_retenido as total_retenido,
										orden_pago.total_a_pagar as total_a_pagar,
										orden_pago.justificacion as justificacion,
										beneficiarios.nombre as beneficiario
											FROM 
										cuentas_t, 
										orden_pago, 
										beneficiarios
											WHERE
										cuentas_t.idcuentas_t = '".$idcuentas_t."'
										and orden_pago.idorden_pago = cuentas_t.idorden_pago
										and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios")or die(mysql_error());
	}else if($bus_primera_consulta["tipo_asiento"] == "compromisos"){
		$sql_consultar = mysql_query("SELECT 
										cuentas_t.idorden_pago as idorden_pago,
										cuentas_t.estado as estado,
										cuentas_t.fecha_contabilizacion as fecha_contabilizacion,
										orden_compra_servicio.numero_orden as numero_orden,
										orden_compra_servicio.fecha_orden as fecha_orden,
										orden_compra_servicio.total as total,
										orden_compra_servicio.exento as exento,
										orden_compra_servicio.impuesto as impuesto,
										orden_compra_servicio.sub_total as sub_total,
										orden_compra_servicio.justificacion as justificacion,
										beneficiarios.nombre as beneficiario
											FROM 
										cuentas_t, 
										orden_compra_servicio, 
										beneficiarios
											WHERE
										cuentas_t.idcuentas_t = '".$idcuentas_t."'
										and orden_compra_servicio.idorden_compra_servicio = cuentas_t.idorden_pago
										and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios")or die(mysql_error());
	}else if($bus_primera_consulta["tipo_asiento"] == "pagados"){
		$sql_consultar = mysql_query("SELECT 
										cuentas_t.idorden_pago as idorden_pago,
										cuentas_t.estado as estado,
										cuentas_t.fecha_contabilizacion as fecha_contabilizacion,
										pagos_financieros.numero_documento as numero_orden,
										pagos_financieros.fecha_cheque as fecha_orden,
										pagos_financieros.monto_cheque as total,
										pagos_financieros.justificacion as justificacion,
										pagos_financieros.beneficiario as beneficiario
											FROM 
										cuentas_t, 
										pagos_financieros
											WHERE
										cuentas_t.idcuentas_t = '".$idcuentas_t."'
										and pagos_financieros.idpagos_financieros = cuentas_t.idorden_pago")or die(mysql_error());
	}
	
	
	$bus_consultar = mysql_fetch_array($sql_consultar);
	
	echo $bus_consultar["idorden_pago"]."|.|".
		$bus_consultar["estado"]."|.|".
		$bus_consultar["numero_orden"]."|.|".
		$bus_consultar["fecha_orden"]."|.|".
		number_format($bus_consultar["total"],2,",",".")."|.|".
		$bus_consultar["justificacion"]."|.|".
		$bus_consultar["beneficiario"]."|.|".
		number_format($bus_consultar["exento"],2,",",".")."|.|".
		number_format($bus_consultar["impuesto"],2,",",".")."|.|".
		number_format($bus_consultar["sub_total"],2,",",".")."|.|".
		number_format($bus_consultar["retenido"],2,",",".")."|.|".
		number_format($bus_consultar["total_a_pagar"],2,",",".")."|.|".
		$bus_consultar["fecha_contabilizacion"];
 }
 
 
 
 if($ejecutar == "anularCuentas"){
 	$sql_update = mysql_query("update cuentas_t set estado = 'anulado' where idcuentas_t ='".$idcuentas_t."'");
 }
?>