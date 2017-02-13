<?php
session_start();
$sql="SELECT anio_fiscal FROM configuracion";
$query_conf=mysql_query($sql) or die ($sql.mysql_error());
$conf=mysql_fetch_array($query_conf);

?>

<script src="modulos/tesoreria/js/ingresos_ajax.js"></script>
	<br>
	<h4 align=center>Movimientos Bancarios</h4>
	<h2 class="sqlmVersion"></h2>
    <br>


<div id="optPDF" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; left:20%; top:0%; text-align:center">
<div align="right"><a href="#" onClick="document.getElementById('optPDF').style.display='none';">X</a></div>
<div style="font-size:12px; font-weight:bold; font-style:italic;">Seleccione la impresi&oacute;n</div>
<br />
<table align="center" width="90%">
	<tr>
    	<td>
        	<a href="javascript:" id="optCheque" style="visibility:hidden;" onclick="imprimirPdf('emitir_pagos_cheque', '<?=$_SESSION["rutaReportes"]?>')">Imprimir Cheque<br /></a>
			<a href="javascript:" id="optOrden" style="visibility:hidden" onclick="imprimirPdf('emitir_pagos_orden', '<?=$_SESSION["rutaReportes"]?>')">Imprimir Orden de Pago<br /></a>
			<a href="javascript:" id="optOficio" style="visibility:hidden" onclick="imprimirPdf('emitir_pagos_oficio', '<?=$_SESSION["rutaReportes"]?>')">Imprimir Oficio<br /></a>
			<a href="javascript:" id="optRetenciones" style="visibility:hidden">Imprimir Movimiento<br /></a>
    	</td>
	</tr>
</table>

<iframe name="pdf" id="pdf" style="display:none" height="550" width="600"></iframe>
<table align="center">
	<tr><td></td></tr>
</table>
</div>    
</div>

    
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
      <td align="center" colspan="6">
      	<div align="center">
            <img src="imagenes/search0.png" 
            	title="Buscar Emisiones de Pago" 
                style="cursor:pointer" 
                onClick="window.open('modulos/tesoreria/lib/listar_ingresos.php','emision_pagos','resisable = no, scrollbars = yes, width = 900, height = 400')" /> 
            <img src="imagenes/nuevo.png" 
            	title="Ingresar Nueva Emision de Pago" 
                onClick="window.location.href='principal.php?modulo=7&accion=477'" 
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
    <td align='right' width="20%" class='viewPropTitle'>Fuente de Financiamiento:</td>
    <td class='viewProp' width="30%" >
    <? $sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento 
												where status='a'");
	?>
        <select name="fuente_financiamiento" id="fuente_financiamiento" style="width:100%">
            <option value='0'>.:: Seleccione la Fuente de Financiamiento Afectada ::.</option>
            <?php
                while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) 
                    { 
                        ?>
                            <option <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"';?>>
                                <?php echo $rowfuente_financiamiento["denominacion"];?>
                            </option>
            <?php
                    }
            ?>
        </select>			
      </td>
       <td  align='right' class='viewPropTitle'>A&ntilde;o del Documento</td>
      <td>
          <select name="anio" id="anio">
              <option>.:: Seleccione ::.</option>
                <?
                for($i=1997; $i<=date("Y");$i++){
                  if ($i == $conf['anio_fiscal']) {
                    echo "<option value='$i' selected>$i</option>";
                  } else {
                      echo "<option value='$i'>$i</option>";
                  }
                }
                ?>
            </select>
          </select>

      </td>
      <td width="81" align='right' class='viewPropTitle'>Estado</td>
    <td width="169" id="celda_estado"><strong>Elaboraci&oacute;n</strong></td>
  </tr>
   
  <tr>
    <td width="230" align='right' class='viewPropTitle'>Tipo Movimiento</td>
  <td width="135">
  <input type="hidden" name="id_ingresos" id="id_ingresos">
  <input type="hidden" name="idasiento_contable" id="idasiento_contable">
  
  <select name="tipo_movimiento" id="tipo_movimiento">
  	<option>.:: Seleccione ::.</option>
    <?
            $sql_consultar_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario");
				while($bus_consultar_tipo_movimiento = mysql_fetch_array($sql_consultar_tipo_movimiento)){
					?>
    <option value="<?=$bus_consultar_tipo_movimiento["idtipo_movimiento_bancario"]?>" onclick="mostrar_cuenta_contable_tipo_movimiento('<?=$bus_consultar_tipo_movimiento["idtipo_movimiento_bancario"]?>'), 
                    document.getElementById('movimiento_afecta').value = '<?=$bus_consultar_tipo_movimiento["afecta"]?>', 
                    document.getElementById('excluir_contabilidad').value = '<?=$bus_consultar_tipo_movimiento["excluir_contabilidad"]?>'">
      <?=$bus_consultar_tipo_movimiento["denominacion"]?>
      </option>
    <?
				}
			?>
  </select>
  <input type="hidden" name="movimiento_afecta" id="movimiento_afecta">
  <input type="hidden" name="excluir_contabilidad" id="excluir_contabilidad">
  </td>
    <td width="140" align='right' class='viewPropTitle'>Nro.  Documento</td>
  <td width="212"><label>
    <input type="text" name="numero_documento" id="numero_documento">
  </label></td>
    <td width="81" align='right' class='viewPropTitle'>Fecha</td>
    <td width="169"><input name="fecha" type="text" id="fecha" size="12" disabled>
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
    <td><input name="monto" type="text" id="monto" size="15"  style="text-align:right; font-size:14px; font-weight:bold; height:20px" onBlur="formatoNumero(this.id, 'monto_oculto'), poner_valor(document.getElementById('excluir_contabilidad').value)">
      <input type="hidden" name="monto_oculto" id="monto_oculto"></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Emitido Por:</td>
    <td colspan="3">
      <input type="text" name="emitido_por" id="emitido_por" size="95">
 	</td>
    <td align='right' class='viewPropTitle'>C.I / RIF </td>
    <td >
      <input type="text" name="ci_emisor" id="ci_emisor">
   </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Concepto</td>
    <td colspan="5"><label>
      <textarea name="concepto" cols="115" rows="3" id="concepto"></textarea>
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
      <td colspan="6" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
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
  </div>
  <input name="tabla_debe" type="hidden" id="tabla_debe" size="100">
  <input name="idcuenta_debe" type="hidden" id="idcuenta_debe" size="100">
  <input name="tabla_haber" type="hidden" id="tabla_haber" size="100">
  <input name="idcuenta_haber" type="hidden" id="idcuenta_haber" size="100">
  <input name="valor_debe" type="hidden" id="valor_debe" size="100">
  <input name="valor_haber" type="hidden" id="valor_haber" size="100">
  </td>
  </tr>
  <tr>
    <td colspan="6" align="center">
      <?
        if(in_array(479, $privilegios) == true){
        ?>
          <input type="button" 
      			style="display:block"
                name="botonProcesar" 
                id="botonProcesar" 
                value="Procesar" 
                class="button"
                onclick="registrarIngresos(document.getElementById('tipo_movimiento').value,
                						document.getElementById('numero_documento').value,
                                        document.getElementById('fecha').value,
                                        document.getElementById('banco').value,
                                        document.getElementById('cuenta').value,
                                        document.getElementById('monto_oculto').value,
                                        document.getElementById('emitido_por').value,
                                        document.getElementById('ci_emisor').value,
                                        document.getElementById('concepto').value,
                                        document.getElementById('excluir_contabilidad').value,
                                        document.getElementById('anio').value)"/>
          <?
           }
           ?>
      <table align="center">
      <tr>
          <td>
            <?
            if(in_array(480, $privilegios) == true){
            ?>
              <input type="button" 
          			name="botonModificar" 
                    id="botonModificar" 
                    style="display:none" 
                    value="Modificar Movimiento" 
                    class="button"
                    onclick="modificarIngresos(document.getElementById('id_ingresos').value,
                    					document.getElementById('tipo_movimiento').value,
                						document.getElementById('numero_documento').value,
                                        document.getElementById('fecha').value,
                                        document.getElementById('banco').value,
                                        document.getElementById('cuenta').value,
                                        document.getElementById('monto_oculto').value,
                                        document.getElementById('emitido_por').value,
                                        document.getElementById('ci_emisor').value,
                                        document.getElementById('concepto').value,
                                        document.getElementById('idasiento_contable').value,
                                        document.getElementById('excluir_contabilidad').value,
                                        document.getElementById('anio').value)">
            <?
           }
           ?>
          </td>
          <td>
            <?
            if(in_array(480, $privilegios) == true){
            ?>
              <input type="button" 
          			name="botonEliminar" 
                    id="botonEliminar" 
                    style="display:none" 
                    value="Anular Movimiento" 
                    class="button"
                    onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'">
              <?
             }
             ?>     
                   
          </td>
      </tr>
      </table>
 </td>
  </tr>
</table>

<div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:11; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:20px; margin-left:550px">
      <table align="center">
        <tr>
          <td align="right" colspan="2">
            <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
              <strong>x</strong>                                </a>                            </td>
        </tr>
        <tr>
          <td  width="70%"><strong>Fecha de Anulaci&oacute;n:</strong> </td>
          
            <td><input name="fecha_anulacion_ingreso" type="text" id="fecha_anulacion_ingreso" size="12" value="<?=date("Y-m-d")?>" disabled="disabled">
            
              <img src="imagenes/jscalendar0.gif" name="f_trigger_cf" width="16" height="16" id="f_trigger_cf" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_anulacion_ingreso",
              button        : "f_trigger_cf",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
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
          <td colspan="2"><input type="button" name="validar" id="validar" class="button" value="Anular" 
            onclick="eliminarIngresos(document.getElementById('id_ingresos').value,
                              document.getElementById('tipo_movimiento').value,
                            document.getElementById('numero_documento').value,
                                        document.getElementById('fecha').value,
                                        document.getElementById('banco').value,
                                        document.getElementById('cuenta').value,
                                        document.getElementById('monto_oculto').value,
                                        document.getElementById('emitido_por').value,
                                        document.getElementById('ci_emisor').value,
                                        document.getElementById('concepto').value,
                                        document.getElementById('idasiento_contable').value)"></td>

        </tr>
  </table>
</div>
