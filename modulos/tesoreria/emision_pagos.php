<?php
session_start();
?>

<script src="modulos/tesoreria/js/emision_pagos_ajax.js"></script>
	<br>
	<h4 align=center>Emisi&oacute;n de Pagos</h4>
	<h2 class="sqlmVersion"></h2>
    <br>


<div id="optPDF" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; left:20%; top:0%; text-align:center; width:800px">
<div align="right"><a href="#" onClick="document.getElementById('optPDF').style.display='none'; document.getElementById('optCheque').style.display='none'; document.getElementById('optOrden').style.display='none'; document.getElementById('optOrdenOficio').style.display='none'; document.getElementById('optOficio').style.display='none'; document.getElementById('optRetenciones').style.display='none';">X</a></div>
<div style="font-size:12px; font-weight:bold; font-style:italic;">Seleccione la impresi&oacute;n</div>
<br />
<table align="center" width="90%">
	<tr>
    	<td>
        	<a href="javascript:" id="optCheque" style="display:none;" onclick="imprimirPdf('emitir_pagos_cheque', 'tesoreria')">Imprimir Cheque<br /></a>
			<a href="javascript:" id="optOrdenPago" style="display:none" onclick="imprimirPdf('emitir_orden_pago', 'tesoreria')">Imprimir Orden de Pago<br /></a>
			<a href="javascript:" id="optOrden" style="display:none" onclick="imprimirPdf('emitir_pagos_orden', 'tesoreria')">Anexo Orden de Pago<br /></a>
			<a href="javascript:" id="optOrdenOficio" style="display:none" onclick="imprimirPdf('emitir_pagos_orden_oficio', 'tesoreria')">Anexo Orden de Pago del Oficio<br /></a>
			<a href="javascript:" id="optOficio" style="display:none" onclick="imprimirRtf('emitir_pagos_oficio', 'reportes/tesoreria')">Imprimir Oficio<br /></a>
			<a href="javascript:" id="optRetenciones" style="display:none" onclick="imprimirPdf('emitir_retenciones', 'tributos')">Imprimir Retenciones<br /></a>
            <a href="javascript:" id="optContable" style="display:none" onclick="imprimirPdf('comprobante_contable', 'tributos')">Comprobante Contable<br /></a>
    	</td>
	</tr>
</table>

<iframe name="pdf" id="pdf" style="display:none" height="550" width="800"></iframe>
<table align="center">
	<tr><td></td></tr>
</table>
</div>    
</div>

    
<table width="89%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
      <td align="center" colspan="10">
      	<div align="center">
            <img src="imagenes/search0.png" 
            	title="Buscar Emisiones de Pago" 
                style="cursor:pointer" 
                onClick="window.open('modulos/tesoreria/lib/listar_emision_pago.php','emision_pagos','resisable = no, scrollbars = yes, width = 900, height = 400')" /> 
            <img src="imagenes/nuevo.png" 
            	title="Ingresar Nueva Emision de Pago" 
                onClick="window.location.href='principal.php?modulo=7&accion=368'" 
                style="cursor:pointer" /> 
            <img src="imagenes/imprimir.png" 
            	title="Imprimir Emision de Pago"
                onclick="mostrarPDF('<?=$_SESSION["rutaReportes"]?>');"  
                style="cursor:pointer" /> 
            <br />
            <br /> 
   		</div>      </td>
  </tr>
  <? $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
  ?>
  <input type="hidden" name="controlar_cheque" id="controlar_cheque" value = "<?=$bus_configuracion["controlar_cheques"]?>">
  <tr>
    <td width="100" align='right' class='viewPropTitle'>Tipo de Documento
    <input type="hidden" name="id_emision_pago" id="id_emision_pago">
    <input type="hidden" name="forma_preimpresa" id="forma_preimpresa">    </td>
  <td width="157">
  
 
<select name="tipo_documento" id="tipo_documento">
    		<option value="0">.:: Selecciona ::.</option>
			<?
			
            $sql_lista_tipo_documento = mysql_query("select * from tipos_documentos where modulo like '%-7-%'");
				while($bus_lista_tipo_documento = mysql_fetch_array($sql_lista_tipo_documento)){
					?>
					<option onclick="
                            limpiarCampos();
                            mostrar_cuenta_contable_tipo_movimiento('<?=$bus_lista_tipo_documento["idtipos_documentos"]?>');
                            document.getElementById('botonProcesar').style.display='block';
							document.getElementById('botonAnular').style.display='none';
					<?
                    if($bus_lista_tipo_documento["paga"] == "no"){
						?>
							document.getElementById('beneficiario').disabled = false;
                            document.getElementById('ci_beneficiario').disabled = false;
                            document.getElementById('monto_cheque').disabled = false;
                            document.getElementById('forma_pago').disabled = false;
                            document.getElementById('porcentaje').disabled = true;
                            document.getElementById('nro_parte').disabled = true;
                            document.getElementById('lista_ordenes_pago').style.display = 'none';
                            document.getElementById('banco').disabled = false;
                            document.getElementById('cuenta').disabled = false;
                            document.getElementById('fecha_chequera').disabled = false;
						<?
					}else if($bus_lista_tipo_documento["paga"] == "si"){
						?>
							document.getElementById('lista_ordenes_pago').style.display = 'block';
                            buscarOrdenes();
                            document.getElementById('beneficiario').disabled = true;
                            document.getElementById('ci_beneficiario').disabled = true;
                            document.getElementById('monto_cheque').disabled = true;
                            document.getElementById('fecha_chequera').disabled = false;
                            
						<?
					}
					if($bus_lista_tipo_documento["forma_preimpresa"] == 'si'){
					?>
                        document.getElementById('chequera').disabled = true;
                        document.getElementById('forma_preimpresa').value = 'si';
					<?
					}else{
					?>
						document.getElementById('chequera').disabled = true;
                        document.getElementById('nro_cheque').disabled = true;
                        document.getElementById('forma_preimpresa').value = 'no';
					<?
					}
					?>" 
                    value="<?=$bus_lista_tipo_documento["idtipos_documentos"]?>" 
                    
                    ><?=$bus_lista_tipo_documento["descripcion"]?> 
                    
                    </option>
					<?
				}
			?>
      </select>    </td>
    <td width="118" align='right' class='viewPropTitle'>Forma de Pago</td>
  <td width="76">
<select name="forma_pago" id="forma_pago" disabled>
            <option value="total" onclick="document.getElementById('porcentaje').disabled = true, 
            								document.getElementById('nro_parte').disabled = true,
                                            document.getElementById('monto_cheque').value = document.getElementById('monto_orden_pago').value,
                                            document.getElementById('monto_cheque_oculto').value = document.getElementById('monto_orden_pago_oculto').value,
                                             document.getElementById('modo_cancelacion').disabled = true
                                            document.getElementById('porcentaje').value=''">Total</option>
            <option value="parcial" onclick="document.getElementById('porcentaje').disabled = false, 
            								document.getElementById('nro_parte').disabled = false,
                                            document.getElementById('modo_cancelacion').disabled = false">Parcial</option>
      </select>    </td>
    <td width="74" align='right' class='viewPropTitle'>Modo de Cancelacion</td>
    <td width="98">
      <select name="modo_cancelacion" id="modo_cancelacion" disabled="disabled">
        <option onclick="document.getElementById('divTipoPago').innerHTML = 'Porcentaje',
                        document.getElementById('beneficiario').disabled = false,
                        document.getElementById('ci_beneficiario').disabled = false" value="Porcentual">Porcentual</option>
        <option onclick="document.getElementById('divTipoPago').innerHTML = 'Monto',
                        document.getElementById('beneficiario').disabled = false,
                        document.getElementById('ci_beneficiario').disabled = false" value="Fijo">Fijo</option>
        <option onclick="document.getElementById('divTipoPago').innerHTML = 'Nomina',
                        document.getElementById('beneficiario').disabled = true,
                        document.getElementById('ci_beneficiario').disabled = true,
                        buscarNomina();" value="Fijo">Desde Nómina</option>
      </select>    </td>
    <td width="73" align='right' class='viewPropTitle'>
    	<div id="divTipoPago">Porcentaje</div>    </td>
    <td width="57"><input name="porcentaje" type="text" id="porcentaje" size="8" disabled  onkeyup="calcularPorcentaje(this.value)" style="text-align:right" />    </td>
    <td width="81" class='viewPropTitle'>Nro. Parte </td>
    <td width="83"><input name="nro_parte" type="text" id="nro_parte" size="8" disabled></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>N&uacute;mero de Orden de Pago</td>
    <td>
    	<input type="text" name="numero_orden_pago" id="numero_orden_pago" size="25" disabled>
    	<input type="hidden" name="id_orden_pago" id="id_orden_pago">    </td>
    <td align='right' class='viewPropTitle'>Beneficiario</td>
    <td colspan="7">
    	<table>
            <tr>
                <td>
                    <input type="text" name="beneficiario" id="beneficiario" size="80" disabled onclick="this.select()">                </td>
                <td>
                    <img style="display:none; cursor:pointer"
                                                    src="imagenes/search0.png" 
                                                    title="Buscar Nuevo Beneficiario" 
                                                    id="buscarBeneficiario" 
                                                    name="buscarBeneficiario" 
                                                    onclick="window.open('modulos/administracion/lib/listar_beneficiarios.php?destino=emision_pagos','proveedor','resizable = no, scrollbars = yes, width =600, height=400')" />                </td>
            </tr>
        </table>
      <input type="hidden" name="id_beneficiario" id="id_beneficiario">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>CI / RIF Beneficiario</td>
    <td><input type="text" name="ci_beneficiario" id="ci_beneficiario" size="15" disabled></td>
    <td align='right' class='viewPropTitle'>Monto de Orden de Pago</td>
    <td colspan="2">
    	<input type="text" name="monto_orden_pago" id="monto_orden_pago" size="15" disabled style="text-align:right">
    	<input type="hidden" name="monto_orden_pago_oculto" id="monto_orden_pago_oculto">    </td>
    <td colspan="2" align='right' class='viewPropTitle'>Monto Restante</td>
    <td colspan="3">
    <input name="monto_restante" type="text" id="monto_restante_mostrado" size="15" disabled="disabled" style="text-align:right; font-weight:bold" />
    <input name="monto_restante" type="hidden" id="monto_restante"/>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tipo Movimiento</td>
    <td>
    	<select name="tipo_movimiento" id="tipo_movimiento" disabled>
        	<?
            $sql_consultar_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'd'");
				while($bus_consultar_tipo_movimiento = mysql_fetch_array($sql_consultar_tipo_movimiento)){
					?>
					<option value="<?=$bus_consultar_tipo_movimiento["idtipo_movimiento_bancario"]?>"><?=$bus_consultar_tipo_movimiento["denominacion"]?></option>
					<?
				}
			?>
         </select>    </td>
    <td align='right' class='viewPropTitle'>Banco</td>
    <td colspan="2">
    	<select name="banco" id="banco" disabled>
        	<option value="0">.:: Seleccione ::.</option>
			<?
            $sql_consultar_cuentas = mysql_Query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
			$sql_consultar_banco = mysql_query("select * from banco");
				while($bus_consultar_banco = mysql_fetch_array($sql_consultar_banco)){
				$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
				$num_consultar_cuentas = mysql_num_rows($sql_consultar_cuentas);
					if($num_consultar_cuentas > 0){
						?>
						<option value="<?=$bus_consultar_banco["idbanco"]?>" onclick="cargarCuentasBancarias('<?=$bus_consultar_banco["idbanco"]?>')"><?=$bus_consultar_banco["denominacion"]?></option>
						<?
					}
				}
			?>
         </select>    </td>
    <td colspan="2" align='right' class='viewPropTitle'>Cuenta</td>
    <td colspan="3" id="celdaCuentaBancaria">
    	<select name="cuenta" id="cuenta" disabled>
        	<option value="0">.:: Seleccione un Banco ::.</option>
		</select>   </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Chequera</td>
    <? if ($bus_configuracion["controlar_cheques"] == '1') { ?>
        <td id="divNroChequeras">
        <select id="chequera" name="chequera" disabled="disabled">
        <option></option>
        </select>
        </td>
    <? }else{ ?>
    	<td id="divNroChequeras">
    	<input type="text" name="chequera" id="chequera" size="10" disabled>    
        </td>
    <? } ?>
    <td align='right' class='viewPropTitle'>Nro. de Cheque</td>
  	<input type="hidden" id="nro_cheque_oculto" name="nro_cheque_oculto" />
    <input type="hidden" name="id_chequera" id="id_chequera" size="10"> 
    <? if ($bus_configuracion["controlar_cheques"] == '1') { ?>
    <td colspan="2">
    <table border="0" id="control_cheque" style="display:block">
      <tr>
        <td>
        
        <table>
          <tr>
            <td><input name="nro_cheque" type="text" disabled="disabled" id="nro_cheque" size="7" maxlength="6" />
                
                <input type="hidden" id="nro_mayor_cheque_oculto" name="nro_mayor_cheque_oculto" />
                <input type="hidden" id="cantidad_digitos" name="cantidad_digitos" />
                <input type="hidden" id="numero_paralelo" name="numero_paralelo" />
            </td>
            <td style="font-weight:bold"><input type="text" id="divNumeroCheque" name="divNumeroCheque" size="2" maxlength="2" disabled="disabled" />
                <input type="hidden" id="posicion_nro_cheque" name="posicion_nro_cheque" />
            </td>
            
          </tr>
        </table>
        
        
        </td>
        <td>
        <input type="button" id="abajo" name="abajo" value="<" onclick="disminuirNumero()">
        </td>
        <td>
        <input type="button" id="arriba" name="arriba" value=">" onclick="aumentarNumero()" />
        </td>
        
      </tr>
    </table>
    <? }else{ ?>
    	 <td id="control_cheque" style="display:block">
                <input name="nro_cheque" type="text" disabled id="nro_cheque" size="10" maxlength="10">
                <input type="hidden" id="divNumeroCheque" name="divNumeroCheque" size="2" maxlength="2" disabled="disabled" />
                <input type="hidden" id="posicion_nro_cheque" name="posicion_nro_cheque" />
                <input type="hidden" id="numero_paralelo" name="numero_paralelo" />
         </td>
    <? } ?>
    
    
    
    </td>
    <td colspan="2" align='right' class='viewPropTitle'>Monto Cheque</td>
    <td colspan="3">
      	<input name="monto_cheque" 
        		type="text" 
                id="monto_cheque" 
                size="15" 
                disabled style="text-align:right; font-size:14px; font-weight:bold; height:20px" 
                onBlur="formatoNumero(this.id, 'monto_cheque_oculto')">
      	<input type="hidden" name="monto_cheque_oculto" id="monto_cheque_oculto">   
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Formato a Imprimir</td>
    <td>
    	<select name="formato_imprimir" id="formato_imprimir" disabled>
        	<option value="Cheque">Cheque</option>
            <option value="Oficio">Oficio</option>
            <option value="Nota de Debito">Nota de Debito</option>
        </select>    </td>
    <td align='right' class='viewPropTitle'>Fecha</td>
    <td colspan="2"><input name="fecha_chequera" type="text" id="fecha_chequera" size="12" disabled="disabled" value="<?=date("Y-m-d")?>" />
      <img src="imagenes/jscalendar0.gif" name="imagen_fecha_fin" width="16" height="16" id="imagen_fecha_fin" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" border="0" />
      <script>
      Calendar.setup({
                        inputField    : 'fecha_chequera',
                        button        : 'imagen_fecha_fin',
                        align         : 'Tr',
                        ifFormat      : '%Y-%m-%d'
                        });
    </script>    </td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td colspan="10" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="center"><div id='div_nomina' style="display:none"></div></td>
  </tr>
  
  <tr>
    <td colspan="10" align="center">
        <?
        if(in_array(483, $privilegios) == true){
        ?>
            <input type="button" 
      			name="botonProcesar" 
                id="botonProcesar" 
                value="Procesar" 
                class="button"
                onclick="ingresarEmisionPagos(document.getElementById('tipo_documento').value, 
                								document.getElementById('forma_pago').value, 
                                                document.getElementById('porcentaje').value, 
                                                document.getElementById('nro_parte').value, 
                                                document.getElementById('tipo_movimiento').value, 
                                                document.getElementById('id_orden_pago').value, 
                                                document.getElementById('cuenta').value, 
                                                document.getElementById('id_chequera').value, 
                                                document.getElementById('nro_cheque').value,
                                                document.getElementById('divNumeroCheque').value,
                                                document.getElementById('posicion_nro_cheque').value,
                                                document.getElementById('fecha_chequera').value, 
                                                document.getElementById('monto_cheque_oculto').value, 
                                                document.getElementById('beneficiario').value, 
                                                document.getElementById('ci_beneficiario').value, 
                                                document.getElementById('formato_imprimir').value,
                                                document.getElementById('modo_cancelacion').value,
                                                document.getElementById('controlar_cheque').value)"/>
        <?
        }
       if(in_array(485, $privilegios) == true){
        ?>  

            <input type="button" 
      			name="botonAnular" 
                id="botonAnular" 
                value="Anular" 
                class="button" 
                style="display:none" 
                onclick="document.getElementById('divPreguntarUsuario').style.display = 'block'"/>  
        <?
         }
         ?>  
          </td>
  </tr>
   <tr>
    <td colspan="10" align="center">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="10" align="center">
   <div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:11; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:10px; margin-left:550px">
      <table align="center">
        <tr>
          <td align="right" colspan="2">
            <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
              <strong>x</strong>                                </a>                            </td>
        </tr>
        <tr>
          <td  width="70%"><strong>Fecha de Anulaci&oacute;n:</strong> </td>
          
            <td><input name="fecha_anulacion_pago" type="text" id="fecha_anulacion_pago" size="12" value="<?=date("Y-m-d")?>" disabled="disabled">
            
            	<img src="imagenes/jscalendar0.gif" name="f_trigger_cf" width="16" height="16" id="f_trigger_cf" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				  <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_anulacion_pago",
							button        : "f_trigger_cf",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>                 
           </td>
        </tr>
        <tr>
          <td><strong>Usuario:</strong> </td>
            <td><?=$login?></td>
        </tr>
        <tr>
          <td><strong>Clave:</strong></td>
            <td><input type="password" name="verificarClave" id="verificarClave"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="button" name="validar" id="validar" class="button" value="Anular" onClick="anular(document.getElementById('id_emision_pago').value, document.getElementById('verificarClave').value, document.getElementById('fecha_anulacion_pago').value)"></td>
        </tr>
	</table>
</div>
	</td>
    </tr>
  
  
  
  
   <tr>
    <td colspan="10" align="center">
    	<div id='div_contabilidad' style="display:block">
        <table width="100%">
        	<tr>
      			<td colspan="6" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px">
                	<strong>AFECTACI&Oacute;N CONTABLE</strong>
                </td>
  			</tr>
  			<tr>
              <td colspan="6">
              <div id='tabla_cuentas_contables'>
               <table class="Browse" cellpadding="0" cellspacing="0" width="100%" align="center">
                    <thead>
                        <tr>
                            <td align="center" class="Browse" width="70%">Cuenta</td>
                            <td align="center" class="Browse" width="15%">Debe</td>
                            <td align="center" class="Browse" width="15%">Haber</td>
                        </tr>
                    </thead>
                    <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td align='left' class='Browse' id="celda_cuenta_debe">&nbsp;</td>
                        <td align="right" class='Browse' id="celda_valor_debe">&nbsp;</td>
                        <td align="right" class='Browse' id="celda_valor_haber1">&nbsp;</td>
                    </tr>
                     <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                        <td align='left' class='Browse' id="celda_cuenta_haber">&nbsp;</td>
                        <td align='right' class='Browse' id="celda_valor_debe1">&nbsp;</td>
                        <td align='right' class='Browse' id="celda_valor_haber">&nbsp;</td>
                    </tr>
              </table>
              <input name="tabla_debe" type="hidden" id="tabla_debe" size="100">
              <input name="idcuenta_debe" type="hidden" id="idcuenta_debe" size="100">
              <input name="tabla_haber" type="hidden" id="tabla_haber" size="100">
              <input name="idcuenta_haber" type="hidden" id="idcuenta_haber" size="100">
              <input name="valor_debe" type="hidden" id="valor_debe" size="100">
              <input name="valor_haber" type="hidden" id="valor_haber" size="100">
              </div>
              </td>
  		</tr>
    </div>
    </table>
    </td>
  </tr>
</table>



<div  id="lista_ordenes_pago" style="display:none">
<br>
<br>
<h2 class="sqlmVersion" align="center">Lista de Ordenes de Pago</h2>
<br>

<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" class='viewPropTitle'>Numero de Orden</td>
        <td><input type="text" name="campoBuscar" id="campoBuscar"></td>
        <td><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onclick="buscarOrdenes()"></td>
    </tr>
</table>


	<div id="listaOrdenes">
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
                $sql_ordenes_pago = mysql_query("select * from orden_pago where estado = 'procesado' or estado = 'parcial'");
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
                                    <img src='imagenes/validar.png' 
                                        border='0' 
                                        alt='Seleccionar' 
                                        title='Seleccionar' 
                                        onclick="document.getElementById('beneficiario').disabled = true,
                                                document.getElementById('ci_beneficiario').disabled = true,
                                                document.getElementById('monto_cheque').disabled = true,
                                                document.getElementById('numero_orden_pago').disabled = true,
                                                document.getElementById('monto_orden_pago').value = '<?=number_format($bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"],2,",",".")?>',
                                                document.getElementById('monto_cheque').value = '<?=number_format($bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"],2,",",".")?>',
                                                document.getElementById('monto_orden_pago_oculto').value = '<?=$bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"]?>',
                                                document.getElementById('monto_cheque_oculto').value = '<?=$bus_ordenes_pago["total"]-$bus_ordenes_pago["total_retenido"]?>',
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
                                                document.getElementById('tipo_movimiento').disabled = false" 
                                        style="cursor:pointer">
                        </td>						
                  </tr>
                 <?
                 }
                 ?>
        </table>
    </div>
</div>