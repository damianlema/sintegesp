<script src="modulos/tributos/js/retenciones_externas_ajax.js"></script>

<h4 align=center>Registro de Retenciones Externas</h4>
	<h2 class="sqlmVersion"></h2>

<table align="center">
	<tr>
    	<td>
    	<img src="imagenes/search0.png" width="16" height="16" onclick="window.open('lib/listas/listar_retenciones_externas.php','lista_retenciones_externas','resizable=no, scrollbars=yes, width=900, height=600')" style="cursor:pointer"/>
        </td>
        <td>
        <img src="imagenes/nuevo.png" width="16" height="16" onclick="window.location.href='principal.php?modulo=6&accion=738'" style="cursor:pointer"/>
        </td>
        <td>
        <img id="boton_imprimir" src="imagenes/imprimir.png" width="16" height="16" style="cursor:pointer; display:none;" onclick="document.getElementById('divTipoOrden').style.display='block';" />
    </td>
    </tr>
    </table>

<table align="center" width="25%">
	<tr>
    	<td>
            <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
            <div align="right"><a href="#" onclick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
            <table id="tableImprimir">
            <tr>
                <td>Reporte: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="comprobantes_retenciones_externas">Comprobantes de Retenciones</option>
                        <option value="registro_retenciones_externas">Registro de Retenciones</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onclick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('ventanaPDF1').src='lib/reportes/tributos/reportes.php?nombre='+document.getElementById('ordenarPor').value+'&id_retencion='+document.getElementById('id_retencion').value; document.getElementById('ventanaPDF1').style.display='block'; document.getElementById('divImprimir1').style.display='block';">
                </td>
            </tr>
            </table>
            </div>
        </td>
    </tr>
</table> 

<table align="center" width="75%">
	<tr>
    	<td>
            <div id="divImprimir1" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; top:50px;">
            <table align="center">
                <tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir1').style.display='none';">X</a></td></tr>
                <tr><td><iframe name="ventanaPDF1" id="ventanaPDF1" style="display:none" height="600" width="750"></iframe></td></tr>
            </table>
            </div>
        </td>
    </tr>
</table>

<table width="80%" border="0" align="center">
  <input type="hidden" id="id_retencion" name="id_retencion">
  <tr>
    <td align="right" class='viewPropTitle'>Nro. Retenci&oacute;n</td>
    <td id="celda_numero_retencion"><strong>No Generado</strong></td>
    <td colspan="2" align="right">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Estado</td>
    <td id="celda_estado"><strong>Elaboraci&oacute;n</strong></td>
    <td colspan="2" align="right">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Fecha Aplicaci&oacute;n Retenci&oacute;n</td>
    <td id="celda_fecha_retencion"><input name="fecha_aplicacion" type="text" id="fecha_aplicacion" size="15" readonly="readonly">
    <img src="imagenes/jscalendar0.gif" name="imagen_fecha_aplicacion" width="16" height="16" id="imagen_fecha_aplicacion" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
      <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_aplicacion",
							button        : "imagen_fecha_aplicacion",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>
    <img src="imagenes/refrescar.png" width="16" height="16" onclick="actualizarFecha(document.getElementById('id_retencion').value)" title="Actualizar Fecha de Aplicaci&oacute;n de la Retenci&oacute;n" style="cursor:pointer; display:none" id="imagen_actualizar_fecha"/>
    </td>
    <td colspan="2" align="right">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Ente Gubernamental</td>
    <td>

    	  <table>
          <tr>
              <td>
              	<input name="ente_gubernamental" type="text" id="ente_gubernamental" size="50">
              </td>
              <td>
                  <img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/listar_entes_gubernamentales.php','','resizable=no, scrollbars=yes, width=900, height=600')" id="imagen_agregar_ente">
                  <input type="hidden" name="id_ente_gubernamental" id="id_ente_gubernamental">
              </td>
          </tr>
          </table>

    </td>
    <td colspan="2" align="right" class='viewPropTitle'>Beneficiario</td>
    <td colspan="3">
        <table>
          <tr>
              <td>
        		<input name="beneficiario" type="text" id="beneficiario" size="50">
              </td>
              <td>
        		<input name="id_beneficiario" type="hidden" disabled id="id_beneficiario" size="20">
   	  		<img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=retencion','','resizable=no, scrollbars=yes, width=900, height=600')" id="imagen_agregar_beneficiario">
      		</td>
         </tr>
      </table>

    </td>
  </tr>
  <tr>
    <td colspan="7" align="center">
      <input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" style="display:block" class="button" onclick="registrarDatosBasicos(document.getElementById('id_ente_gubernamental').value, document.getElementById('id_beneficiario').value)">
      <input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" style="display:none" class="button" onclick="procesarRetencion(document.getElementById('id_retencion').value)"/>
      <input type="submit" name="boton_anular" id="boton_anular" value="Anular" style="display:none" class="button" onclick="anularRetencion(document.getElementById('id_retencion').value)"/>
      <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" class="button" onclick="eliminarRetencion(document.getElementById('id_retencion').value)"/>
      </td>
  </tr>
  <tr>
    <td colspan="7"></td>
  </tr>
</table>
<br>
<div id="divValoresFacturas" style="display:none" align="center">

<table width="75%" border="0" align="center" cellpadding="0" cellspacing="2" id="tablaValoresFacturas" style="display:block">
  <tr>
  	<td colspan="8" style="background-color:#09F" align="center"><strong>Datos de la Transacci&oacute;n</strong>&nbsp;<img src="imagenes/nuevo.png" width="16" height="16" onclick="nuevaFactura()" title="Limpiar e ingresar nueva factura" style="cursor:pointer"/></td>
  </tr>
  <tr>
    <td width="10%" align="right" class='viewPropTitle'>Nro Orden</td>
    <td width="13%"><input name="numero_orden" type="text" id="numero_orden" size="15" /></td>
    <td width="11%" align="right" class='viewPropTitle'>Fecha </td>
    <td ><input name="fecha_orden" type="text" id="fecha_orden" size="15" readonly="readonly">
    <img src="imagenes/jscalendar0.gif" name="imagen_fecha_orden" width="16" height="16" id="imagen_fecha_orden" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_orden",
							button        : "imagen_fecha_orden",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>
    </td>
    <td width="10%" align="right" class='viewPropTitle'>Monto Contrato</td>
    <input name="monto_contrato_oculto" type="hidden" id="monto_contrato_oculto"/>
    <td width="13%"><input name="monto_contrato" type="text" id="monto_contrato" size="18" style="text-align:right" value="0.00" onblur="formatoNumero(this.name, 'monto_contrato_oculto')"/></td>
    <td width="13%" align="right" class='viewPropTitle'><strong>Exento</strong></td>
    <input name="exento_oculto" type="hidden" id="exento_oculto" value="0"/>
    <td width="10%" align="center"> <input name="exento" type="text" id="exento" size="18" onkeyup="calcularImpuesto(document.getElementById('alicuota').value), sumaTotal()" value="0.00" style="text-align:right" onclick="this.select()" onblur="formatoNumero(this.name, 'exento_oculto')"/></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro Factura</td>
    <td><input name="numero_factura" type="text" id="numero_factura" size="15" /></td>
    <td align="right" class='viewPropTitle'>Nro Control</td>
    <td width="10%"><input name="numero_control" type="text" id="numero_control" size="15" /></td>
    <td width="12%" align="right" class='viewPropTitle'>Fecha Factura</td>
    <td width="21%"><input name="fecha_factura" type="text" id="fecha_factura" size="15" readonly="readonly">
    <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_factura",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>
     </td>
     <td align="right" class='viewPropTitle'><strong>Sub Total</strong></td>
     <input name="sub_total_oculto" type="hidden" id="sub_total_oculto" value="0"/>
     <td width="13%"><input name="sub_total" type="text" id="sub_total" size="18" value="0.00" style="text-align:right" onclick="this.select()" onblur="formatoNumero(this.name, 'sub_total_oculto'), calcularImpuesto(), sumaTotal()"/></td>
   </tr>
   <tr>
    <td align="right" class='viewPropTitle'>Concepto</td>
   	<td colspan="5" rowspan="3"><textarea name="concepto_orden" cols="100" rows="3" id="concepto_orden"></textarea></td>
    <td align="right" class='viewPropTitle'><strong>Alicuota</strong></td>
    <input name="alicuota_oculto" type="hidden" id="alicuota_oculto"/>
    <td width="7%" align="right"><input name="alicuota" type="text" id="alicuota" size="8" onkeyup="" style="text-align:right" onclick="this.select()" value="0.00" onblur="formatoNumero(this.name, 'alicuota_oculto'), calcularImpuesto(), sumaTotal()"/></td>
   </tr>
   <tr>
   	<td colspan="6">&nbsp;</td>
    <td align="right" class='viewPropTitle'><strong>Impuesto</strong></td>
    <input name="impuesto_oculto" type="hidden" id="impuesto_oculto" value="0"/>
    <td width="13%"><input name="impuesto" type="text" id="impuesto" size="18" value="0.00" style="text-align:right" onclick="this.select()" onblur="formatoNumero(this.name, 'impuesto_oculto')"/></td>
   </tr>
   <tr>
   	<td colspan="6">&nbsp;</td>
    <td align="right" class='viewPropTitle'><strong>Total</strong></td>
    <input name="total_oculto" type="hidden" id="total_oculto"/>
    <td width="14%"><input name="total" type="text" id="total" size="18" value="0.00" style="text-align:right" onclick="this.select()" onblur="formatoNumero(this.name, 'total_oculto')"/></td>
   </tr>
  </table>
  <br />
  <table width="65%" align="center">
   <tr>
  	<td colspan="10" style="background-color:#09F" align="center"><strong>Retenciones Aplicadas&nbsp;<img src="imagenes/nuevo.png" width="16" height="16" onclick="nuevaRetencion()" title="Limpiar e ingresar nueva retenci&oacute;n" style="cursor:pointer"/></strong></td>
   </tr>
   <tr>
    <td width="26%" align="right" class='viewPropTitle'>Tipo de Retenci&oacute;n</td>
    <td width="31%">
     <select name="tipo_retencion" id="tipo_retencion">
        <option value="0">.:: Seleccione ::.</option>
        <?
		  $sql_tipos_retenciones = mysql_query("select * from tipo_retencion");
		  while($bus_tipos_retenciones = mysql_fetch_array($sql_tipos_retenciones)){
	  	?>
        <option value="<?=$bus_tipos_retenciones["idtipo_retencion"]?>" onClick="mostrarMontos('<?=$bus_tipos_retenciones["idtipo_retencion"]?>')">
          <?=$bus_tipos_retenciones["descripcion"]?>
        </option>
        <?
	  }
	  ?>
      </select>
    </td>
    <td width="13%" align="right" class='viewPropTitle'>C&oacute;digo I.S.L.R</td>
    <td width="8%"><input name="codigo_islr" type="text" id="codigo_islr" size="10" /></td>
   </tr>
   <tr>
    <td colspan="4">
    <input name="idrelacion_retencion" type="hidden" id="idrelacion_retencion">
	   <table width="80%" align="center" border="0" cellspacing="0" cellpadding="0">
      <tr>
      	<td align="center" align="center" class='viewPropTitle'><strong>Base C&aacute;lculo</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Porcentaje</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Divisor</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Factor</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Total Retenido</strong></td>
      </tr>
      <tr>
      	<td align="center"><input name="base_calculo" type="text" id="base_calculo" size="18" style="text-align:right">
        					<input name="base_calculo_oculto" type="hidden" id="base_calculo_oculto" size="18" style="text-align:right"></td>
        <td align="center"><input name="porcentaje" type="text" id="porcentaje" size="10" style="text-align:right"></td>
        <td align="center"><input name="divisor" type="text" id="divisor" size="10" style="text-align:right"></td>
        <td align="center"><input name="factor" type="text" id="factor" size="12" style="text-align:right"></td>
        <td align="center"><input name="total_retenido" type="text" id="total_retenido" size="18" style="text-align:right"></td>
      </tr>
      <tr>
        <td align="center" align="center" class='viewPropTitle'><strong>Fecha Enteramiento</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Fecha Deposito</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Numero Deposito</strong></td>
        <td align="center" align="center" class='viewPropTitle'><strong>Fecha Transferencia</strong></td>
        <td align="center" align="center" class='viewPropTitle'></td>
      </tr>
      <tr>
        <td align="center"><input name="fecha_enteramiento" type="text" id="fecha_enteramiento" size="18" style="text-align:right"></td>
        <td align="center"><input name="fecha_deposito" type="text" id="fecha_deposito" size="10" style="text-align:right"></td>
        <td align="center"><input name="numero_deposito" type="text" id="numero_deposito" size="10" style="text-align:right"></td>
        <td align="center"><input name="fecha_transferencia" type="text" id="fecha_transferencia" size="12" style="text-align:right"></td>
        <td align="center"></td>
      </tr>
    </table>
    </td>
  </tr>
   <tr>
    <td align="center" colspan="4">
      <input type="button" name="boton_registrar_factura" id="boton_registrar_factura" value="Registrar Retenci&oacute;n" class="button" onclick="ingresarFactura()"/>
      <input type="button" name="boton_modificar_factura" id="boton_modificar_factura" value="Modificar Factura" class="button" onclick="modificarFactura()" style="display:none"/>
    </td>
  </tr>
</table>
</div>
<br />
<br />
<div id="listaFacturaRetencionesExternas">
</div>
