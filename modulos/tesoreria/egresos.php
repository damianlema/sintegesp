<?php
session_start();
?>

<script src="modulos/tesoreria/js/egresos_ajax.js"></script>
	<br>
	<h4 align=center>Egresos</h4>
	<h2 class="sqlmVersion"></h2>
    <br>


<div id="optPDF" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; left:20%; top:0%; text-align:center">
<div align="right"><a href="#" onClick="document.getElementById('optPDF').style.display='none';">X</a></div>
<div style="font-size:12px; font-weight:bold; font-style:italic;">Seleccione la impresi&oacute;n</div>
<br />
<table align="center" width="90%">
	<tr>
    	<td>
        	<a href="javascript:" id="optCheque" style="visibility:hidden;" onClick="imprimirPdf('emitir_pagos_cheque', '<?=$_SESSION["rutaReportes"]?>')">Imprimir Cheque<br /></a>
			<a href="javascript:" id="optOrden" style="visibility:hidden" onClick="imprimirPdf('emitir_pagos_orden', '<?=$_SESSION["rutaReportes"]?>')">Imprimir Orden de Pago<br /></a>
			<a href="javascript:" id="optOficio" style="visibility:hidden" onClick="imprimirPdf('emitir_pagos_oficio', '<?=$_SESSION["rutaReportes"]?>')">Imprimir Oficio<br /></a>
			<a href="javascript:" id="optRetenciones" style="visibility:hidden">Imprimir Retenciones<br /></a>
    	</td>
	</tr>
</table>

<iframe name="pdf" id="pdf" style="display:none" height="550" width="600"></iframe>
<table align="center">
	<tr><td></td></tr>
</table>
</div>    
</div>

    
<table width="78%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
      <td align="center" colspan="6">
      	<div align="center">
            <img src="imagenes/search0.png" 
            	title="Buscar Emisiones de Pago" 
                style="cursor:pointer" 
                onClick="window.open('modulos/tesoreria/lib/listar_egresos.php','emision_pagos','resisable = no, scrollbars = yes, width = 900, height = 400')" /> 
            <img src="imagenes/nuevo.png" 
            	title="Ingresar Nueva Emision de Pago" 
                onClick="window.location.href='principal.php?modulo=7&accion=482'" 
                style="cursor:pointer" /> 
            <img src="imagenes/imprimir.png" 
            	title="Imprimir Emision de Pago"
                onclick="mostrarPDF('<?=$_SESSION["rutaReportes"]?>');"  
                style="cursor:pointer" /> 
        <br />
        <br /> 
   		</div>      </td>
  </tr>
  <tr>
    <td width="151" align='right' class='viewPropTitle'>Tipo Movimiento</td>
  <td width="157">
  <input type="hidden" name="id_egresos" id="id_egresos">
  
  <select name="tipo_movimiento" id="tipo_movimiento">
    <?
            $sql_consultar_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'd'");
				while($bus_consultar_tipo_movimiento = mysql_fetch_array($sql_consultar_tipo_movimiento)){
					?>
    <option value="<?=$bus_consultar_tipo_movimiento["idtipo_movimiento_bancario"]?>">
      <?=$bus_consultar_tipo_movimiento["denominacion"]?>
      </option>
    <?
				}
			?>
  </select></td>
    <td width="144" align='right' class='viewPropTitle'>Nro de Documento</td>
  <td width="135"><label>
    <input type="text" name="numero_documento" id="numero_documento">
  </label></td>
    <td width="81" align='right' class='viewPropTitle'>Fecha</td>
    <td width="191"><input name="fecha" type="text" id="fecha" size="12" disabled>
      <img src="imagenes/jscalendar0.gif" name="imagen_fecha_fin" width="16" height="16" id="imagen_fecha_fin" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" border="0" />
      <script>
      	Calendar.setup({
                        inputField    : 'fecha',
                        button        : 'imagen_fecha_fin',
                        align         : 'Tr',
                        ifFormat      : '%Y-%m-%d'
                        })
		</script>
		</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Banco</td>
    <td><select name="banco" id="banco">
      <option value="0">.:: Seleccione ::.</option>
      <?
            $sql_consultar_cuentas = mysql_Query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
			$sql_consultar_banco = mysql_query("select * from banco");
				while($bus_consultar_banco = mysql_fetch_array($sql_consultar_banco)){
				$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$bus_consultar_banco["idbanco"]."'");
				$num_consultar_cuentas = mysql_num_rows($sql_consultar_cuentas);
					if($num_consultar_cuentas > 0){
						?>
      <option value="<?=$bus_consultar_banco["idbanco"]?>" onClick="cargarCuentasBancarias('<?=$bus_consultar_banco["idbanco"]?>')">
        <?=$bus_consultar_banco["denominacion"]?>
        </option>
      <?
					}
				}
			?>
    </select></td>
    <td align='right' class='viewPropTitle'>Cuenta</td>
    <td id="celdaCuentaBancaria">
    <select name="cuenta" id="cuenta">
      <option value="0">.:: Seleccione un Banco ::.</option>
    </select>
    </td>
    <td align='right' class='viewPropTitle'>Monto</td>
    <td><input name="monto" type="text" id="monto" size="15"  style="text-align:right; font-size:14px; font-weight:bold; height:20px" onBlur="formatoNumero(this.id, 'monto_oculto')">
      <input type="hidden" name="monto_oculto" id="monto_oculto"></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Emitido Por:</td>
    <td colspan="3">
      <input type="text" name="emitido_por" id="emitido_por" size="65">
 	</td>
    <td align='right' class='viewPropTitle'>C.I / RIF</td>
    <td>
      <input type="text" name="ci_emisor" id="ci_emisor">
   </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Concepto</td>
    <td colspan="5"><label>
      <input name="concepto" type="text" id="concepto" size="100">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center">
      <input type="button" 
      			style="display:block"
                name="botonProcesar" 
                id="botonProcesar" 
                value="Procesar" 
                class="button"
                onclick="registrarEgresos(document.getElementById('tipo_movimiento').value,
                						document.getElementById('numero_documento').value,
                                        document.getElementById('fecha').value,
                                        document.getElementById('banco').value,
                                        document.getElementById('cuenta').value,
                                        document.getElementById('monto_oculto').value,
                                        document.getElementById('emitido_por').value,
                                        document.getElementById('ci_emisor').value,
                                        document.getElementById('concepto').value)"/>
      <table align="center">
      <tr>
          <td>
          <input type="button" 
          			name="botonModificar" 
                    id="botonModificar" 
                    style="display:none" 
                    value="Modificar" 
                    class="button"
                    onclick="modificarEgresos(document.getElementById('id_egresos').value,
                    					document.getElementById('tipo_movimiento').value,
                						document.getElementById('numero_documento').value,
                                        document.getElementById('fecha').value,
                                        document.getElementById('banco').value,
                                        document.getElementById('cuenta').value,
                                        document.getElementById('monto_oculto').value,
                                        document.getElementById('emitido_por').value,
                                        document.getElementById('ci_emisor').value,
                                        document.getElementById('concepto').value)">
          </td>
          <td>
          <input type="button" 
          			name="botonEliminar" 
                    id="botonEliminar" 
                    style="display:none" 
                    value="Eliminar" 
                    class="button"
                    onClick="eliminarEgresos(document.getElementById('id_egresos').value)">
          </td>
      </tr>
      </table>
 </td>
  </tr>
</table>



