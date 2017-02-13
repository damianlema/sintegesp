<script src="modulos/tributos/js/retenciones_ajax.js"></script>
	<br>
	<h4 align=center>Registro de Retenciones</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<input type="hidden" name="iddocumento" id="iddocumento" size="15">
<input type="hidden" name="anio_documento" id="anio_documento">
<input type="hidden" name="exento_oculto" id="exento_oculto">
<input type="hidden" name="subtotal_oculto" id="subtotal_oculto">
<input type="hidden" name="impuesto_oculto" id="impuesto_oculto">
<input type="hidden" name="total_oculto" id="total_oculto">
<input type="hidden" name="total_retenciones_oculto" id="total_retenciones_oculto">
<input type="hidden" name="id_retencion" id="id_retencion">
<input type="hidden" name="porcentaje_impuesto" id="porcentaje_impuesto">
<input type="hidden" name="idrelacion_retenciones" id="idrelacion_retenciones">
<input type="hidden" name="monto_retenido_viejo" id="monto_retenido_viejo">

<input type="hidden" name="exento_inicial" id="exento_inicial">
<input type="hidden" name="subtotal_inicial" id="subtotal_inicial">
<input type="hidden" name="impuesto_inicial" id="impuesto_inicial">
<input type="hidden" name="total_inicial" id="total_inicial">


<input type="hidden" name="estado" id="estado">
<input type="hidden" name="tipo_pago" id="tipo_pago">
<table width="80%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Forma de Calculo</td>
    <td><label>
    
    
      <select name="select_tipo_pago" id="select_tipo_pago" disabled="disabled">
        <option value="0">.:: Seleccione ::.</option>
        <option value="total" onclick="document.getElementById('tipo_pago').value = 'total',
        								document.getElementById('exento').disabled = true, 
                                        document.getElementById('sub_total').disabled = true, 
                                        document.getElementById('impuesto').disabled = true, 
                                        document.getElementById('total').disabled = true, 
                                        document.getElementById('tipo_retencion').disabled=false">Total</option>
        <option value="parcial" onclick="document.getElementById('tipo_pago').value = 'parcial',
                                        document.getElementById('tipo_retencion').disabled=false,
                                        document.getElementById('exento').disabled = false, 
                                        document.getElementById('sub_total').disabled = false, 
                                        document.getElementById('impuesto').disabled = false, 
                                        document.getElementById('total').disabled = false">Parcial</option>
                                        
                                        <? //validarEstado() ?>
      </select>
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro Orden</td>
    <td><label>
      <input name="nro_orden" type="text" disabled id="nro_orden" size="15">
    </label></td>
    <td align="right" class='viewPropTitle'>Fecha</td>
    <td><input name="fecha_orden" type="text" disabled id="fecha_orden" size="15"></td>
    <td align="right" class='viewPropTitle'><label>Beneficiario</label></td>
    <td colspan="3">
    <input name="beneficiario" type="text" disabled id="beneficiario" size="50">
    <input name="id_beneficiario" type="hidden" disabled id="id_beneficiario" size="20">    <label></label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro. Factura</td>
    <td><label>
      <input name="nro_factura" type="text" id="nro_factura" size="15">
    </label></td>
    <td align="right" class='viewPropTitle'>Nro Control</td>
    <td><input name="nro_control" type="text" id="nro_control" size="10"></td>
    <td align="right" class='viewPropTitle'><label>Fecha de la Factura</label></td>
    <td>
    <input name="fecha_factura" type="text" id="fecha_factura" size="15" readonly="readonly">
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
    <td>
    <img src="imagenes/refrescar.png" onclick="actualizarFechaFactura()" name="imagen_actualizar_factura" id="imagen_actualizar_factura" style="cursor:pointer; display:none">
    
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Exento</td>
    <td><label>
      <input name="exento" type="text" disabled id="exento" size="15" style="text-align:right" onblur="validarMayor(this.name, 'exento_inicial'), formatoNumero(this.name, 'exento_oculto'),
      sumarValores('exento_oculto', 'subtotal_oculto', 'impuesto_oculto', 'total', 'total_oculto')">
    </label></td>
    <td align="right" class='viewPropTitle'>Base (Sub Total)</td>
    <td><label>
      <input name="sub_total" type="text" disabled id="sub_total" size="15" style="text-align:right" onblur="validarMayor(this.name, 'subtotal_inicial'), formatoNumero(this.name, 'subtotal_oculto'),sumarValores('exento_oculto', 'subtotal_oculto', 'impuesto_oculto', 'total', 'total_oculto')">
    </label></td>
    <td align="right" class='viewPropTitle'>Impuesto</td>
    <td><label>
      <input name="impuesto" type="text" disabled id="impuesto" size="15" style="text-align:right" onblur="validarVacio(this.value, this.id), validarMayor(this.name, 'impuesto_inicial'), formatoNumero(this.name, 'impuesto_oculto'), sumarValores('exento_oculto', 'subtotal_oculto', 'impuesto_oculto', 'total', 'total_oculto')">
    </label></td>
    <td align="right" class='viewPropTitle'>Total </td>
    <td><input name="total" type="text" disabled id="total" size="15" style="text-align:right" onblur="validarMayor(this.name, 'total_inicial'), formatoNumero(this.name, 'total_oculto'), sumarValores('exento_oculto', 'subtotal_oculto', 'impuesto_oculto', 'total', 'total_oculto')"></td>
    <td><label></label></td>
  </tr>
  </table>
  <table width="80%" border="0" align="center">
  <tr>
    <td width="16%" align="right" class='viewPropTitle'>Tipo de Retencion</td>
    <td width="40%"><label>
      <select name="tipo_retencion" id="tipo_retencion" disabled>
        <option value="0">.:: Seleccione ::.</option>
        <?
      $sql_tipos_retenciones = mysql_query("select * from tipo_retencion");
	  while($bus_tipos_retenciones = mysql_fetch_array($sql_tipos_retenciones)){
	  	?>
        <option value="<?=$bus_tipos_retenciones["idtipo_retencion"]?>" onClick="registrarDatosBasicos(document.getElementById('iddocumento').value, document.getElementById('nro_orden').value, document.getElementById('nro_factura').value, document.getElementById('nro_control').value, document.getElementById('fecha_factura').value, document.getElementById('exento_oculto').value, document.getElementById('subtotal_oculto').value, document.getElementById('impuesto_oculto').value, document.getElementById('porcentaje_impuesto').value, document.getElementById('total_oculto').value, document.getElementById('tipo_pago').value, 'elaboracion', document.getElementById('anio_documento').value), mostrarRetencion('<?=$bus_tipos_retenciones["idtipo_retencion"]?>',document.getElementById('exento_oculto').value, document.getElementById('subtotal_oculto').value, document.getElementById('impuesto_oculto').value, document.getElementById('total_oculto').value, 'ingresar')">
          <?=$bus_tipos_retenciones["descripcion"]?>
        </option>
        <?
	  }
	  ?>
      </select>
    </label></td>
    <td width="21%" align="right" class='viewPropTitle'><strong>Total Retenciones</strong></td>
	<td width="23%">
    	<div id="divTotalRetenciones" style="font-size:14px; font-weight:bold"></div>
    </td>
    
  </tr>
  <tr>
   <td colspan="4">
   	<div id="tablaDatosRetencion" style=" display:block">    </div>   </td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
   
  </tr>
  <tr>
    <td colspan="4">
    <div id="listaRetencionesAplicadas"></div>    </td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
   
    
    <!-- FORMULARIO DE BUSQUEDA -->
    <table align="center">
    	<tr>
        	<td align="right" style="display:none" >A&ntilde;o</td>
            <td style="display:none"><select name="anio" id="anio">
              <option value="2008" <?php if ($_SESSION["anio_fiscal"] == "2008") { echo ' selected';}?>>2008</option>
              <option value="2009" <?php if ($_SESSION["anio_fiscal"] == "2009") { echo ' selected';}?>>2009</option>
              <option value="2010" <?php if ($_SESSION["anio_fiscal"] == "2010") { echo ' selected';}?>>2010</option>
              <option value="2011" <?php if ($_SESSION["anio_fiscal"] == "2011") { echo ' selected';}?>>2011</option>
              <option value="2012" <?php if ($_SESSION["anio_fiscal"] == "2012") { echo ' selected';}?>>2012</option>
              <option value="2013" <?php if ($_SESSION["anio_fiscal"] == "2013") { echo ' selected';}?>>2013</option>
              <option value="2014" <?php if ($_SESSION["anio_fiscal"] == "2014") { echo ' selected';}?>>2014</option>
              <option value="2015" <?php if ($_SESSION["anio_fiscal"] == "2015") { echo ' selected';}?>>2015</option>
              <option value="2016" <?php if ($_SESSION["anio_fiscal"] == "2016") { echo ' selected';}?>>2016</option>
            </select></td>
        	<td>N&uacute;mero de Orden</td>
            <td><input type="text" name="campoBuscar" id="campoBuscar"></td>
            <td><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onclick="refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value)"></td>
    	</tr>
    </table>
    <!-- FORMULARIO DE BUSQUEDA -->
   
   
   
   
   
    
    </td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
    	<div id="listaDocumentos" style="display:block"></div></td>
  </tr>
  <tr>
    <td colspan="4"></td>
  </tr>
</table>
