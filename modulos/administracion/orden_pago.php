<script src="modulos/administracion/js/orden_pago_ajax.js" type="text/javascript" language="javascript"></script>

<?
$sql_configuracion = mysql_query("select * from configuracion
                      where status='a'"
    , $conexion_db);
$registro_configuracion = mysql_fetch_assoc($sql_configuracion);

$anio_fijo = $registro_configuracion["anio_fiscal"];
include "../../../funciones/funciones.php";
/*<h4 align=center>Orden de Pago</h4>
<h2 class="sqlmVersion"></h2>
 */
?>
<input type="hidden" id="sinAfectacionOculto" name="sinAfectacionOculto" value="1">
<input type="hidden" id="seleccionado" name="seleccionado" value="no">
<input type="hidden" name="solicitudes" id="solicitudes">
<input type="hidden" name="mostrarCuadro" id="mostrarCuadro" value='si'>
<input type="hidden" name="estado" id="estado" value=''>

<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align="right">
        <div align="center">
          <img src="imagenes/search0.png" title="Buscar Orden de Pago" style="cursor:pointer" onClick="window.open('lib/listas/listar_ordenes_pago.php?destino=ordenes_pago','orden_pago','resisable = no, scrollbars = yes, width=1300, height = 755')">
        </div>
      </td>
      <td align="right">
        <div align="center">
          <img src="imagenes/nuevo.png" title="Ingresar nueva Orden de Pago" onClick="window.location.href='principal.php?modulo=4&accion=277'" style="cursor:pointer">
        </div>
      </td>
      <td align="right" >
        <div align="center" id="celdaImprimir" style="display:none">
          <img src="imagenes/imprimir.png" title="Imprimir Orden de Pago"  onClick="document.getElementById('pdf').src='lib/reportes/administracion/reportes.php?nombre=ordenpago&idorden_pago='+document.getElementById('id_orden_pago').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
        </div>
      </td>
    </tr>
</table>

<div id="divImprimir" style="display:none; position:absolute; z-index:10; background-color:#CCCCCC; border:1px solid;">
<table align="center">
  <tr><td align="right"><a href="javascript:;" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
    <tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>


<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:90%; margin-left:-560px; margin-top:5px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos del Causado</strong></td>
    </tr>
</table>
</div>

  <!-- TABLA DE DATOS BASICOS-->
<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:317px; width:90%; margin-left:-560px; margin-top:25px; overflow:auto">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">

   <tr>
     <td align="right" class='viewPropTitleNew' width="15%">Nro. de Orden:</td>
     <td width="15%" colspan="2" style="border:1px solid #999; background-color:#FFF" id="celdaNroOrden"><strong>&nbsp;Aun no generado</strong></td>
     <td>&nbsp;</td>

     <td rowspan="4" valign="top" align="right">

        <table id="tablaTotalesNomina" style="display:none">
          <tr>
            <td colspan="2" class='viewPropTitleNew'><strong>Montos Totales</strong></td>
        </tr>
          <tr>
            <td width="59" align="right" class='viewPropTitleNew'>Asignaciones</td>
            <td><input name="asignaciones_mostrado" type="text" id="asignaciones_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'subtotal'), " value="0" size="15" align="right" onclick="this.select()" />
            <input type="hidden" id="subtotal" name="subtotal" value="0" /></td>
          </tr>

          <tr>
            <td width="59" align="right" class='viewPropTitleNew'>Deducciones</td>
            <td><input name="deducciones_mostrado" type="text" id="deducciones_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'exento'), " value="0" size="15" align="right" onclick="this.select()" />
            <input type="hidden" id="exento" name="exento" value="0" /></td>
          </tr>

          <tr>
            <td width="59" align="right" class='viewPropTitleNew'>Monto:</td>
          <td width="90"><label>
            <input name="monto_sinafectacion_mostradoN" type="text" id="monto_sinafectacion_mostradoN" style="text-align:right" onblur="formatoNumero(this.name, 'monto_sinafectacion')" value="0" size="15" align="right" onclick="this.select()" disabled/>
            <input type="hidden" id="monto_sinafectacionN" name="monto_sinafectacionN" value="0" />
            </label>
            <input type="hidden" name="textoTotalAPagarPrincipalOcultoN" id="textoTotalAPagarPrincipalOcultoN">
            <input type="hidden" name="textoTotalAPagarPrincipalN" id="textoTotalAPagarPrincipalN">        </td>
        </tr>
      </table>


        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="tablaMontoSinAfectacion" style="display:block" align="right">
          <tr>
            <td colspan="2" align="center" class='viewPropTitleNew'><strong>Montos Totales</strong></td>
          </tr>
          <tr style="display:block">
            <td width="100" align="right" class='viewPropTitleNew'>Exento:</td>
            <td width="120" align="right"><input name="exento_mostrado" type="text" id="exento_mostrado" style="text-align:right"
                    onblur="formatoNumero(this.name, 'exento'),
                            sumarValores('exento', 'subtotal', 'impuesto', 'monto_sinafectacion_mostrado', 'monto_sinafectacion', 'textoMontoRetenidoPrincipalOculto', 'textoTotalAPagarPrincipalOculto', 'textoTotalAPagarPrincipal')"
                            value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" />
                <input type="hidden" id="exento" name="exento" value="0" />
            </td>
          </tr>
          <tr style="display:block">
            <td width="100" align="right" class='viewPropTitleNew'>S Total:</td>
            <td width="120" align="right"><input name="subtotal_mostrado" type="text" id="subtotal_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'subtotal'), sumarValores('exento', 'subtotal', 'impuesto', 'monto_sinafectacion_mostrado', 'monto_sinafectacion', 'textoMontoRetenidoPrincipalOculto', 'textoTotalAPagarPrincipalOculto', 'textoTotalAPagarPrincipal' )" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()"/>
                <input type="hidden" id="subtotal" name="subtotal" value="0" />
            </td>
          </tr>
          <tr style="display:block">
            <td width="100" align="right" class='viewPropTitleNew'>Impuesto:</td>
            <td width="120" align="right">
            <input name="impuesto_mostrado" type="text" id="impuesto_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'impuesto'), sumarValores('exento', 'subtotal', 'impuesto', 'monto_sinafectacion_mostrado', 'monto_sinafectacion', 'textoMontoRetenidoPrincipalOculto', 'textoTotalAPagarPrincipalOculto', 'textoTotalAPagarPrincipal' )" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()"/>
                <input type="hidden" id="impuesto" name="impuesto" value="0" />
            </td>
          </tr>
          <tr style="display:block">
            <td width="100" align="right" class='viewPropTitleNew'>Total:</td>
            <td width="120" align="right"><input name="monto_sinafectacion_mostrado" type="text" id="monto_sinafectacion_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'monto_sinafectacion')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
                <input type="hidden" id="monto_sinafectacion" name="monto_sinafectacion" value="0" />
            </td>
          </tr>



          <tr id='filaRetencion' style="display:block">
            <td width="100" align="right" class='viewPropTitleNew'><strong>Retenido:</strong></td>
            <td width="120" align="right">
            <input type="hidden" name="textoMontoRetenidoPrincipalOculto" id="textoMontoRetenidoPrincipalOculto" value="0">
            <input type='text' disabled name='textoMontoRetenidoPrincipal' id="textoMontoRetenidoPrincipal" style='color:#FF0000; font-weight:bold; text-align:right' size='20' value="<?=number_format(0, 2, ",", ".")?>">
            </td>
          </tr>



          <tr id='filaAPagar' style="display:block">
            <td width="100" align="right" class='viewPropTitleNew'><strong>A Pagar:</strong></td>
            <td width="120" align="right"><input type="hidden" id="textoTotalAPagarPrincipalOculto" name="textoTotalAPagarPrincipalOculto" value="0">
               <input type='text' disabled name='textoTotalAPagarPrincipal' id="textoTotalAPagarPrincipal" style='font-weight:bold; text-align:right; font-size:12px; height:18px' size='18' value="<?=number_format(0, 2, ",", ".")?>">
            </td>
          </tr>
        </table>
    </td>

    <?// TABLA DE PENDIENTE POR CAUSAR ?>

     <td rowspan="4" valign="top">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="tablaPendientePorPagar" style="display:none">
          <tr>
            <td align="center" class='viewPropTitleNew'><strong>Por Causar</strong></td>
        </tr>

          <tr>
            <td width="106">
              <input name="exento_pendiente_mostrado" type="text" id="exento_pendiente_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'exento_pendiente'), sumarValores('exento_pendiente', 'subtotal_pendiente', 'impuesto_pendiente', 'monto_pendiente_mostrado', 'monto_pendiente')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="exento_pendiente" name="exento_pendiente" value="0" />            </td>
        </tr>
          <tr>
            <td width="106">
              <input name="subtotal_pendiente_mostrado" type="text" id="subtotal_pendiente_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'subtotal_pendiente'), sumarValores('exento_pendiente', 'subtotal_pendiente', 'impuesto_pendiente', 'monto_pendiente_mostrado', 'monto_pendiente')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="subtotal_pendiente" name="subtotal_pendiente" value="0" />            </td>
        </tr>
          <tr>
            <td width="106">
              <input name="impuesto_pendiente_mostrado" type="text" id="impuesto_pendiente_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'impuesto_pendiente'), sumarValores('exento_pendiente', 'subtotal_pendiente', 'impuesto_pendiente', 'monto_pendiente_mostrado', 'monto_pendiente')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="impuesto_pendiente" name="impuesto_pendiente" value="0" />            </td>
        </tr>
          <tr>
            <td width="106">
              <input name="monto_pendiente_mostrado" type="text" id="monto_pendiente_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'monto_pendiente')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="monto_pendiente" name="monto_pendiente" value="0" />            </td>
        </tr>
      </table>
    </td>

      <?// TABLA DE ESTE PAGO ?>
     <td rowspan="4" valign="top">

        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="tablaPagoActual" style="display:none">
          <tr>
            <td align="center" class='viewPropTitleNew'><strong>Pago Actual</strong></td>
        </tr>
          <tr>
            <td width="109">
              <input name="exento_actual_mostrado" type="text" id="exento_actual_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'exento_actual'), sumarValores('exento_actual', 'subtotal_actual', 'impuesto_actual', 'monto_actual_mostrado', 'monto_actual')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="exento_actual" name="exento_actual" value="0" />            </td>
        </tr>
          <tr>
            <td width="109">
              <input name="subtotal_actual_mostrado" type="text" id="subtotal_actual_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'subtotal_actual'), sumarValores('exento_actual', 'subtotal_actual', 'impuesto_actual', 'monto_actual_mostrado', 'monto_actual')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="subtotal_actual" name="subtotal_actual" value="0" />           </td>
        </tr>
          <tr>
            <td width="109">
              <input name="impuesto_actual_mostrado" type="text" id="impuesto_actual_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'impuesto_actual'), sumarValores('exento_actual', 'subtotal_actual', 'impuesto_actual', 'monto_actual_mostrado', 'monto_actual')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled"/>
              <input type="hidden" id="impuesto_actual" name="impuesto_actual" value="0" />            </td>
        </tr>
          <tr>
            <td width="109">
              <input name="monto_actual_mostrado" type="text" id="monto_actual_mostrado" style="text-align:right" onblur="formatoNumero(this.name, 'monto_actual')" value="<?=number_format(0, 2, ",", ".")?>" size="21" align="right" onclick="this.select()" disabled="disabled" disabled="disabled">
              <input type="hidden" id="monto_actual" name="monto_actual" value="0" />            </td>
        </tr>
        <tr id="montoRetenidoFinal">
            <td width="143" style="color:#FF0000; font-weight:bold">
              <input type="hidden" id="textoMontoRetenidoFinalOculto" name="textoMontoRetenidoFinalOculto" value="0">
              <input type='text' disabled name='textoMontoRetenidoFinal' id="textoMontoRetenidoFinal" style='color:#FF0000; font-weight:bold; text-align:right' size='17' value="<?=number_format(0, 2, ",", ".")?>">          </td>
          </tr>
          <tr id="montoAPagarFinal">
            <td width="143" style="color:#FF0000; font-weight:bold" >
               <input type="hidden" id="textoTotalAPagarFinalOculto" name="textoTotalAPagarFinalOculto" value="0">
               <input type='text' disabled name='textoTotalAPagarFinal' id="textoTotalAPagarFinal" style='font-weight:bold; text-align:right; height:18px; font-size:12px' size='14' value="<?=number_format(0, 2, ",", ".")?>">             </td>
             </tr>
      </table>
    </td>



   </tr>
   <tr>
     <td align="right" class='viewPropTitleNew' width="15%">Fecha:</td>
     <td width="15%"><input type="text" name="fecha_orden" id="fecha_orden" style="width:150px; height:20px; font-size:12px; font-weight: bold;" readonly="readonly" />
    </td>
     <td align="right" class='viewPropTitleNew' width="15%">Fecha de Elaboraci&oacute;n:</td>
     <td id="celdaFechaElaboracion" width="15%"><strong><?=date("d-m-Y")?></strong></td>
   </tr>
   <tr>
      <td width="15%" align="right" class='viewPropTitleNew'>Estado</td>
      <td colspan="3" style="border:1px solid #999; background-color:#FFF" id="celdaEstado"><strong>&nbsp;En Elaboraci&oacute;n</strong></td>
    </tr>

    <tr>
      <td align="right" class='viewPropTitleNew'>Tipo de Orden:</td>
      <td colspan="2">
    <?

$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like '%-" . $_SESSION["modulo"] . "-%' and padre = 'no' and ((causa = 'no' and compromete = 'no' and paga = 'no') or (causa = 'si' and compromete = 'no' and paga = 'no') or (causa = 'si' and compromete = 'si' and paga = 'no'))");
?>
    <select name="tipo_orden" id="tipo_orden">
      <option value='0'>..:: Seleccione un Tipo de Documento ::..</option>
    <?
while ($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)) {
    ?>
      <option
    <?
    if ($bus_tipos_documentos["compromete"] == "no" and $bus_tipos_documentos["causa"] == "no" and $bus_tipos_documentos["paga"] == "no") {
        ?>
          onclick="
                document.getElementById('compromete').value ='no',
                    document.getElementById('causa').value ='no',
                    document.getElementById('paga').value ='no',
                    document.getElementById('formularioMateriales').style.display ='none',
                    document.getElementById('tablaProveedor').style.display ='none',
                document.getElementById('mostrarCuadro').value = 'no',
                    document.getElementById('sinAfectacionOculto').value= '1',
                    document.getElementById('exento_mostrado').disabled = false,
                    document.getElementById('subtotal_mostrado').disabled = false,
                    document.getElementById('impuesto_mostrado').disabled = false,
                    document.getElementById('tablaPendientePorPagar').style.display = 'none',
                    document.getElementById('tablaPagoActual').style.display = 'none',
                    document.getElementById('forma_pago').value = 'total',
          document.getElementById('forma_pago').disabled = true,
                    document.getElementById('nombre_categoria').disabled = true,
                    document.getElementById('buscarCategoriaProgramatica').style.visibility = 'hidden',
                    document.getElementById('tablaTotalesNomina').style.display = 'block',
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'none'"
    <?
    } else if ($bus_tipos_documentos["compromete"] == "no" and $bus_tipos_documentos["causa"] == "si" and $bus_tipos_documentos["paga"] == "no") {
        ?>
          onclick="<?if ($bus_tipos_documentos["documento_compromete"] != 0) {
            ?>
                    document.getElementById('formularioMateriales').style.display ='none',
            <?
        } else {
            ?>
                    document.getElementById('formularioMateriales').style.display ='block',
            <?
        }
        ?>
                    document.getElementById('compromete').value ='no',
                    document.getElementById('causa').value ='si',
                    document.getElementById('paga').value ='no',
                    document.getElementById('exento_mostrado').disabled = true,
                    document.getElementById('subtotal_mostrado').disabled = true,
                    document.getElementById('impuesto_mostrado').disabled = true,
                    document.getElementById('forma_pago').disabled = false,
        <?if ($bus_tipos_documentos["multi_categoria"] == "si") {?>
                  document.getElementById('forma_pago').disabled = true,
                    document.getElementById('nombre_categoria').disabled = true,
                    document.getElementById('buscarCategoriaProgramatica').style.visibility = 'hidden',
                    document.getElementById('tablaTotalesNomina').style.display = 'block',
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'none'
        <?} else {?>
                  document.getElementById('forma_pago').disabled = false,
                    document.getElementById('nombre_categoria').disabled = false,
                    document.getElementById('sinAfectacionOculto').value= '0',
                    document.getElementById('buscarCategoriaProgramatica').style.visibility = 'visible',
                    document.getElementById('tablaTotalesNomina').style.display = 'none',
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'block'
        <?}?>"
         <?
    } else if ($bus_tipos_documentos["compromete"] == "si" and $bus_tipos_documentos["causa"] == "si" and $bus_tipos_documentos["paga"] == "no") {
        ?>
        onclick="<?if ($bus_tipos_documentos["documento_compromete"] != 0) {
            ?>
                        document.getElementById('formularioMateriales').style.display ='none',
            <?
        } else {
            ?>
                        document.getElementById('formularioMateriales').style.display ='block',
            <?
        }
        ?>
                    document.getElementById('compromete').value ='si',
                    document.getElementById('causa').value ='si',
                    document.getElementById('paga').value ='no',
                  document.getElementById('mostrarCuadro').value = 'no',
                  document.getElementById('sinAfectacionOculto').value= '0',
                    document.getElementById('exento_mostrado').disabled = true,
                    document.getElementById('subtotal_mostrado').disabled = true,
                    document.getElementById('impuesto_mostrado').disabled = true,
                    document.getElementById('forma_pago').disabled = true,
                    document.getElementById('tablaPendientePorPagar').style.display = 'none',
                    document.getElementById('tablaPagoActual').style.display = 'none',
                    document.getElementById('forma_pago').value = 'total',
                    document.getElementById('forma_pago').disabled = true,
        <?if ($bus_tipos_documentos["multi_categoria"] == "si") {?>
                  document.getElementById('forma_pago').disabled = true,
                    document.getElementById('multi_categoria').value = 'si',
                    document.getElementById('nombre_categoria').disabled = true,
                    document.getElementById('buscarCategoriaProgramatica').style.visibility = 'hidden',
                    document.getElementById('tablaTotalesNomina').style.display = 'block',
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'none'
        <?} else {?>
                    document.getElementById('nombre_categoria').disabled = false,
                    document.getElementById('multi_categoria').value = 'no',
                    document.getElementById('buscarCategoriaProgramatica').style.visibility = 'visible' ,
                    document.getElementById('tablaTotalesNomina').style.display = 'none',
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'block'
        <?}?>"
        <?
    }
    ?>
                 value= "<?=$bus_tipos_documentos["idtipos_documentos"]?>">
    <?=$bus_tipos_documentos["descripcion"]?> </option>
  <?
}
?>
        </select>

      <input type="hidden" id="id_orden_pago" name="id_orden_pago">
      <input type="hidden" id="compromete" name="compromete" >
      <input type="hidden" id="causa" name="causa" >
      <input type="hidden" id="paga" name="paga" >
      <input type="hidden" id="multi_categoria" name="multi_categoria" >
    </td>
    <td>
            <select name="forma_pago" id="forma_pago" disabled="disabled">
              <option value="total" onclick="document.getElementById('tablaPendientePorPagar').style.display = 'none',
                                             document.getElementById('tablaPagoActual').style.display = 'none',
                                             document.getElementById('anticipo').style.display = 'none',
                                             document.getElementById('check_anticipo').checked = 0">
                Total
              </option>
              <option value="parcial" onclick="document.getElementById('tablaPendientePorPagar').style.display = 'block',
                                                 document.getElementById('tablaPagoActual').style.display = 'block',
                                                 document.getElementById('check_anticipo').checked = 1">
                Anticipo
              </option> <?// document.getElementById('anticipo').style.display = 'none', ?>
              <option value="valuacion" onclick="document.getElementById('tablaPendientePorPagar').style.display = 'block',
                                                 document.getElementById('tablaPagoActual').style.display = 'block',
                                                 document.getElementById('check_anticipo').checked = 0">
                Valuaci&oacute;n
              </option>
            </select>
            <div id="anticipo" style="display:none"><input type="checkbox" id="check_anticipo" name="check_anticipo"></div>
      </td>
    </tr>


    <tr>
      <td align="right" class='viewPropTitleNew'>Proveedor/Beneficiario:</td>
      <td colspan="4">
          <input name="nombre_proveedor" type="text" id="nombre_proveedor" size="120" value="Haga clic para buscar proveedores / beneficiarios"  readonly="readonly" onclick="window.open('modulos/administracion/lib/listar_beneficiarios.php?destino=ordenes_pago','proveedor','resizable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer"/>
            <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
            <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" />
      </td>
      <td  colspan="2">&nbsp;</td>
    </tr>


    <tr>
      <td align="right" class='viewPropTitleNew'>Categor&iacute;a Program&aacute;tica:</td>
      <td colspan="4">
          <?
$sql_configuracion_categoria = mysql_query("select categoria_programatica.idcategoria_programatica,
                            unidad_ejecutora.denominacion
                              from
                             configuracion,
                             categoria_programatica,
                             unidad_ejecutora
                              where
                             categoria_programatica.idcategoria_programatica = configuracion.idcategoria_programatica
                             and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora") or die(mysql_error());
$bus_configuracion_categoria = mysql_fetch_array($sql_configuracion_categoria);
?>

          <input type="text" name="nombre_categoria" id="nombre_categoria" size="120" readonly="readonly"
                  value="<?if ($bus_configuracion_categoria["denominacion"] != '') {
    echo $bus_configuracion_categoria["denominacion"];
} else {
    echo "Haga clic para seleccionar la Categoria Programatica";
}?>"
                            onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra',
                                    'categoria_programatica','resizable = no, scrollbars=yes, width=900, height = 500')" style="cursor:pointer"/>
          <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" value="<?=$bus_configuracion_categoria["idcategoria_programatica"]?>"/>      </td>
          <input type="hidden" name="id_categoria_programatica_anterior" id="id_categoria_programatica_anterior" value="<?=$bus_configuracion_categoria["idcategoria_programatica"]?>"/>      </td>


       <td align="left">
         <a href="#" onClick="abrirCerrarDatosExtra()" id="textoContraerDatosExtra" style="font-size:10px">Origen Presupuestario</a>
       </td>
       <td>&nbsp;</td>
    </tr>





    <?
$sql_configuracion = mysql_query("select * from configuracion
                      where status='a'"
    , $conexion_db);
$registro_configuracion = mysql_fetch_assoc($sql_configuracion);

$anio_fijo                    = $registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo      = $registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo = $registro_configuracion["idfuente_financiamiento"];
?>



    <tr>
      <td colspan="7">

        <table width="100%" border="0" id="datosExtra" style="display:none">
          <tr>
            <td align="right" class='viewPropTitleNew'>Fuente de Financiamiento</td>
            <td>
            <select name="idfuente_financiamiento" id="idfuente_financiamiento">
          <option>.:: Seleccione ::.</option>
          <?php
$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
                        where status='a'"
    , $conexion_db);
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
          <option onclick="document.getElementById('cofinanciamiento').value = 'no'" <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
    if ($rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {echo ' selected';} ?>> <?php echo $rowfuente_financiamiento["denominacion"]; ?> </option>
          <?php
}

$sql_cofinanciamiento = mysql_query("select * from cofinanciamiento");
while ($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)) {
    ?>
            <option onclick="document.getElementById('cofinanciamiento').value = 'si'" value="<?=$bus_cofinanciamiento["idcofinanciamiento"]?>"><?=$bus_cofinanciamiento["denominacion"]?></option>
            <?
}
?>

        </select>
        <input type="hidden" id="cofinanciamiento" name="cofinanciamiento" value="">
            </td>
            <td align="right" class='viewPropTitleNew'>Tipo de Presupuesto</td>
            <td><select name="idtipo_presupuesto" id="idtipo_presupuesto">
              <option>.:: Seleccione ::.</option>
              <?php
$sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto
                      where status='a'"
    , $conexion_db);
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
    ?>
              <option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';
    if ($rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {echo ' selected';} ?>> <?php echo $rowtipo_presupuesto["denominacion"]; ?> </option>
              <?php
}
?>
            </select>
            </td>

            <td align="right" class='viewPropTitleNew'>A&ntilde;o</td>
            <td><select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
            </select></td>
            <td align="right"></td>
            <td><table align="left" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><input type="hidden" name="descripcion_ordinal" id="descripcion_ordinal" size="30" readonly="readonly" value=""/>
                    <input type="hidden" name="id_ordinal" id="id_ordinal" value=""/>                </td>
                <td><img style="display:none"
                                        src="imagenes/search0.png"
                                        title="Buscar Ordinal"
                                        id="buscarOrdinal"
                                        name="buscarOrdinal"
                                        onclick="window.open('lib/listas/lista_ordinal.php?destino=orden_compra','','resizable = no, scrollbars=yes, width=600, height=400')"
                                         /> </td>
              </tr>
            </table></td>
          </tr>
        </table>        </td>
    </tr>



    <tr>
      <td align="right" class='viewPropTitleNew'>Concepto:</td>
      <td colspan="6"><textarea name="justificacion" cols="130" rows="3" id="justificacion"></textarea></td>
    </tr>

    <textarea name="observaciones" cols="130" rows="2" id="observaciones" style="display:none"></textarea></td>

    <tr>
      <td align="right" class='viewPropTitleNew'>Ordenado Por:</td>
      <td colspan="2">

    <?
$sql_configuracion = mysql_query("select * from configuracion");
$bus_configuracion = mysql_fetch_array($sql_configuracion);

$campo_buscar = $bus_configuracion["ordena_administracion"];

?>
        <select name="ordenado_por" id="ordenado_por">
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_administracion"]) {
    echo "selected";
}?>
                    id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
          <?=$bus_configuracion_administracion["primero_administracion"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_administracion"]) {
    echo "selected";
}?>
                    id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
          <?=$bus_configuracion_administracion["segundo_administracion"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_administracion"]) {
    echo "selected";
}?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
                onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
          <?=$bus_configuracion_administracion["tercero_administracion"]?>
          </option>
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_compras"]) {
    echo "selected";
}?>
                  id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
          <?=$bus_configuracion_administracion["primero_compras"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_compras"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
          <?=$bus_configuracion_administracion["segundo_compras"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_compras"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
          <?=$bus_configuracion_administracion["tercero_compras"]?>
          </option>
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_rrhh"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
          <?=$bus_configuracion_administracion["primero_rrhh"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_rrhh"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
          <?=$bus_configuracion_administracion["segundo_rrhh"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_rrhh"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
          <?=$bus_configuracion_administracion["tercero_rrhh"]?>
          </option>
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_contabilidad"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_priemro_contabilidad"]?>'">
          <?=$bus_configuracion_administracion["primero_contabilidad"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_contabilidad"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
          <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_contabilidad"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
          <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
          </option>
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_presupuesto"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
          <?=$bus_configuracion_administracion["primero_presupuesto"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_presupuesto"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
          <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_presupuesto"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
          <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
          </option>
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_tesoreria"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
          <?=$bus_configuracion_administracion["primero_tesoreria"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_tesoreria"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
          <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_tesoreria"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
          <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
          </option>
          <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_tributos"]) {
    echo "selected";

}?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
          <?=$bus_configuracion_administracion["primero_tributos"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_tributos"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
          <?=$bus_configuracion_administracion["segundo_tributos"]?>
          </option>
          <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_tributos"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
          <?=$bus_configuracion_administracion["tercero_tributos"]?>
          </option>
        </select>
      </td>
      <td align="right" class='viewPropTitleNew'>C&eacute;dula:</td>
      <td colspan="3"><input type="text" name="cedula_ordenado" id="cedula_ordenado" value="<?=$bus_configuracion["ci_ordena_administracion"]?>"/></td>
    </tr>
    <input type="hidden" name="numero_documento" id="numero_documento" />
    <input type="hidden" name="fecha_documento" id="fecha_documento" size="13" readonly="readonly"/>
    <?/*<tr>
<td width="171" align="right" class='viewPropTitleNew'>N&uacute;mero de Documento:</td>
<td colspan="3"><input type="text" name="numero_documento" id="numero_documento" /></td>
<td width="162" align="right" class='viewPropTitleNew'>Fecha de Documento:</td>
<td width="441" colspan="3"><input type="text" name="fecha_documento" id="fecha_documento" size="13" readonly="readonly"/>
<img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
<script type="text/javascript">
Calendar.setup({
inputField    : "fecha_documento",
button        : "f_trigger_c",
align         : "Tr",
ifFormat      : "%Y-%m-%d"
});
</script></td>
</tr> */?>


    <tr>
      <td class='viewPropTitleNew'><div align="right">Nro. Proyecto:</div></td>
      <td ><input type="text" name="numero_proyecto" id="numero_proyecto" /></td>
      <td class='viewPropTitleNew'><div align="right">Nro. Contrato:</div></td>
      <td ><input type="text" name="numero_contrato" id="numero_contrato" /></td>
      <td colspan="3">&nbsp; </td>
    </tr>
    <tr>
      <td colspan="7" id="vistaDeBotones">

        <table align="center">
          <tr>
            <td id="celdaBotonSiguiente" style="display:block">
                    <input type="button"
                            name="botonSiguiente"
                            id="botonSiguiente"
                            value="Siguiente >"
                            style="display:block"
                            onclick="ingresarDatosBasicos(document.getElementById('tipo_orden').value,
                                    document.getElementById('id_categoria_programatica').value,
                                    document.getElementById('anio').value,
                                    document.getElementById('idfuente_financiamiento').value,
                                    document.getElementById('idtipo_presupuesto').value,
                                    document.getElementById('id_ordinal').value,
                                    document.getElementById('justificacion').value,
                                    document.getElementById('observaciones').value,
                                    document.getElementById('ordenado_por').value,
                                    document.getElementById('cedula_ordenado').value,
                                    document.getElementById('numero_documento').value,
                                    document.getElementById('fecha_documento').value,
                                    document.getElementById('numero_proyecto').value,
                                    document.getElementById('numero_contrato').value,
                                    document.getElementById('id_beneficiarios').value,
                                    document.getElementById('textoTotalAPagarPrincipalOculto').value,
                                    document.getElementById('monto_sinafectacion').value,
                                    document.getElementById('exento').value,
                                    document.getElementById('subtotal').value,
                                    document.getElementById('impuesto').value,
                                    document.getElementById('forma_pago').value,
                                    document.getElementById('anio').value)"
                            class="button">
                </td>
                <td>
                  <input type="button"
                        name="botonEnElaboracion"
                        id="botonEnElaboracion"
                        value="En Elaboracion"
                        style="display:none"
                        onclick="actualizarDatosBasicos('actualizar'), consultarOrdenPago(document.getElementById('id_orden_pago').value)"
                        class="button">
                </td>
                <td id="celdaBotonProcesar">
                  <?
if (in_array(391, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonProcesar"
              id="botonProcesar"
              value="Procesar"
                            style="display:none"
              onclick="actualizarDatosBasicos('actualizar'), consultarOrdenPago(document.getElementById('id_orden_pago').value), procesarOrden(document.getElementById('id_orden_pago').value)"
              class="button">
           <?
}
?>
                </td>
                <td id="celdaBotonAnular">
          <?
if (in_array(393, $privilegios) == true) {
    ?>
                    <input type="button"
                            name="botonAnular"
                            id="botonAnular"
                            value="Anular"
                            style="display:none"
                            onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                            class="button">
                     <?
}
?>
                </td>
                <td id="celdaBotonDuplicar">
          <?
if (in_array(392, $privilegios) == true) {
    ?>
                    <input type="button"
                            name="botonDuplicar"
                            id="botonDuplicar"
                            value="Duplicar"
                            style="display:none"
                            onclick="duplicarOrden(document.getElementById('id_orden_pago').value)"
                            class="button">
                   <?
}
?>
                </td>
          </tr>
       </table>
     </td>
    </tr>
  </table>
</div>

  <!-- TABLA DE DATOS BASICOS-->

 <div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:11; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:200px; margin-left:550px">
      <table align="center">
        <tr>
          <td align="right" colspan="2">
            <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
              <strong>x</strong>                                </a>                            </td>
        </tr>
        <tr>
          <td  width="70%"><strong>Fecha de Anulaci&oacute;n:</strong> </td>

            <td><input name="fecha_anulacion_opago" type="text" id="fecha_anulacion_opago" size="12" value="<?=date("Y-m-d")?>" disabled="disabled">

              <img src="imagenes/jscalendar0.gif" name="f_trigger_cf" width="16" height="16" id="f_trigger_cf" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_anulacion_opago",
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
          <td colspan="2"><input type="button" name="validar" id="validar" class="button" value="Anular" onClick="anularOrden(document.getElementById('id_orden_pago').value, document.getElementById('verificarClave').value, document.getElementById('fecha_anulacion_opago').value)"></td>
        </tr>
  </table>
</div>



  <!-- TABLA DE PROVEEDORES-->

  <div id="divTablaProveedores" style="display:block; position:absolute; left:50%; width:90%; margin-left:-560px; height:100px; height:auto !important; min-height:100px; margin-top:355px; overflow:auto">
  <!-- PROVEEDORES-->

  <table align="center" id="tablaProveedor" style="display:none" width="100%">
    <tr style="background: #09F">
      <td align="left" width="21%"><strong>COMPROMISOS: </strong> </td>
      <td align="right" width="87%">&nbsp;</td>
      <td align="right" width="2%"><a href="#" onclick="abrirCerrarProveedores()" id="textoContraerProveedores"> <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar" /> </a> </td>
    </tr>
    <tr>
      <td>
            <div id="listaSolicitudesProveedor" style="background-color:#CCCCCC;
                                    border:#000000 1px solid;
                                    display:block;
                                    width:200px;
                                    height:200px;
                                    overflow:auto"></div>
            </td>
            <td valign="top">
            <div id="solicitudesSeleccionada" style="width:100%">
                <center>
                  <strong>No hay Compromisos Seleccionados</strong>
                </center>
            </div>
                <!-- ****************************************************************************************************************** -->
                <div id="listaRetenciones" style="width:100%"></div>
            </td>
            <td>&nbsp;</td>
    </tr>
  </table>




   <br />

  <!-- PARTIDAS PRESUPUESTARIAS -->



  <table align="center" style="display:none" id="tablaMaterialesPartidas" width="100%" >
       <tr>
          <td colspan="3">
            <table align="center" width="70%">
                  <tr>
                    <td bgcolor="#e7dfce" width="4%"></td>
                    <td width="26%" align="left"><font size="1"><strong >Disponibilidad Presupuestaria</strong></font></td>
                    <td bgcolor="#FFFF00" width="4%"></td>
                    <td width="26%" align="left"><font size="1"><strong >Sin Disponibilidad Presupuestaria</strong></font></td>
                  </tr>
                </table>
             </td>
        </tr>
        <tr style="background: #09F">
          <td align="left" width="21%"><strong>AFECTACION PRESUPUESTARIA</strong></td>
          <td align="right" width="87%">
            <span id="totalPartidas"><strong>Total Bsf: </strong>0,00</span>
          </td>
          <td align="right" width="2%">
                <a href="#" onClick="abrirCerrarPartidas()" id="textoContraerPartidas">
                <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
                </a>

          </td>
        </tr>

        <tr>
        <td colspan="3" align="center">

            <form name="formularioPartidas" onsubmit="return ingresarPartidaIndividual(document.getElementById('id_orden_pago').value, document.getElementById('id_partida').value, document.getElementById('id_categoria_programatica').value, document.getElementById('monto').value)">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" id="formularioMateriales" style="display:block">
                 <tr>
                     <td width="2%">&nbsp;</td>
                     <td align="center" width="10%">Partida:</td>
                     <td align="center" width="6%">Ordinal:</td>
                     <td align="center" width="60%">Denominaci&oacute;n:</td>
                     <td align="center" width="15%">Monto Bs:</td>
                     <td width="2%">&nbsp;</td>
                 </tr>
                 <tr>
                  <td><img src="imagenes/search0.png"
                                    title="Buscar Partida Presupuestaria"
                                    id="buscarMaterial"
                                    name="buscarMaterial"
                                    onclick="mostrarListaPresupuesto()"
                                    style="cursor:pointer"/>
                  </td>
                  <td><input name="partida" type="text" disabled="disabled" id="partida" style="width:100%" /></td>
                      <input type="hidden" id="id_partida" name="id_partida"/>
                  <td><input name="ordinal" type="text" disabled="disabled" id="ordinal" style="width:100%"/></td>
                  <td><input name="denominacion_partida" type="text" disabled="disabled" id="denominacion_partida" style="width:100%" /></td>
                  <td><input name="monto" type="text" id="monto" style="width:100%; text-align:right" /></td>
                  <td><input type="image"
                                    src="imagenes/validar.png"
                                    title="Procesar Material"
                                    id="procesarMaterial"
                                    name="procesarMaterial"
                                    style="cursor:pointer"
                                    onclick="" />
                  </td>
                </tr>
          </table>
          </form>

        </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
             <div id="divPartidas" style="display:block">
                <strong>   </strong>
             </div>
          </td>
        </tr>
    </table>

 <br />


     <table align="center" width="100%"  style="display:none" id="tablaContabilidad">
<!-- CONTABILIDAD -->
         <tr style="background: #09F">
          <td align="left"  width="21%"><strong>AFECTACION CONTABLE</strong></td>
          <td align="right"  width="87%">&nbsp;</td>
          <td align="right"  width="2%">
                <a href="javascript:;" onClick="abrirCerrarContable()" id="textoContraerContable">
              <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <div id="divContable" style="display:block">
              <strong>  No se han Registrado Movimientos Contables para este documento </strong>
            </div>
          </td>
        </tr>
      <!-- CONTABILIDAD -->
</table>




 </div>
