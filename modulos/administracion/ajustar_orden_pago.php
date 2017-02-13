<script src="modulos/administracion/js/ajustar_orden_pago_ajax.js"></script>
    <br>
<h4 align=center>Ajustar Ordenes de Pago</h4>
<h2 class="sqlmVersion"></h2>

 <!-- TABLA DE DATOS -->
<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align="right">
        <div align="center">
          <img src="imagenes/nuevo.png" title="Limpiar Fornulario" onClick="blanquearFormulario();" style="cursor:pointer">
        </div>
      </td>
    </tr>
</table>
 <input type="hidden" id="id_orden_pago" name="id_orden_pago">
 <input type="hidden" id="multi_categoria" name="multi_categoria">
 <table width="987" border="0" align="center" cellpadding="0" cellspacing="5">
   <tr>
     <td align="right" class='viewPropTitle'>Número de Orden</td>
     <td colspan="3" id="numero_orden">&nbsp;</td>
   </tr>
   <tr>
     <td width="105" align="right" class='viewPropTitle'>Justificación</td>
     <td colspan="3">
       <textarea name="justificacion" cols="120" rows="4" id="justificacion"></textarea>     </td>
   </tr>
        <td colspan="4">
            <table>
               <tr id="celda_exento" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Exento</td>
                    <td>
                        <input type="text"
                     		style="text-align:right"
                            name="exento_mostrado"
                            id="exento_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'exento'), cambiarTotal()"
                            onclick="select()">

                        <input type="hidden"
                     		id="exento"
                            name="exento" value ='0'>
                    </td>
                </tr>
                <tr  id="celda_subtotal" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Sub Total</td>
                    <td>
                        <input type="text"
                     		style="text-align:right"
                            name="sub_total_mostrado"
                            id="sub_total_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'sub_total'), cambiarTotal()"
                            onclick="select()">

                        <input type="hidden"
                     		id="sub_total"
                            name="sub_total" value ='0'>
                    </td>
                </tr>
                <tr  id="celda_impuesto" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Impuesto</td>
                    <td>
                        <input type="text"
                            style="text-align:right"
                            name="impuesto_mostrado"
                            id="impuesto_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'impuesto'), cambiarTotal()"
                            onclick="select()">

                        <input type="hidden"
                            id="impuesto"
                            name="impuesto" value ='0'>
                    </td>
                </tr>
                <tr id="celda_retenido" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Retenido</td>
                    <td>
                        <input type="text"
                     		style="text-align:right"
                            name="retenido_mostrado"
                            id="retenido_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'retenido')"
                           disabled="disabled">

                        <input type="hidden"
                     		id="retenido"
                            name="retenido" value ='0'>
                    </td>
                </tr>
                <tr id="celda_total" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Total</td>
                    <td>
                        <input type="text"
                     		style="text-align:right"
                            name="total_mostrado"
                            id="total_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'total')"
                            disabled="disabled">

                        <input type="hidden"
                     		id="total"
                            name="total" value ='0'>
                    </td>
                </tr>
                <tr id="celda_asignaciones" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Asignaciones</td>
                    <td>
                        <input type="text"
                            style="text-align:right"
                            name="asignaciones_mostrado"
                            id="asignaciones_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'asignaciones'), cambiarTotal()"
                            onclick="select()">

                        <input type="hidden"
                            id="asignaciones"
                            name="asignaciones" value ='0'>
                    </td>
                </tr>
                <tr id="celda_deducciones" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Deducciones</td>
                    <td>
                        <input type="text"
                            style="text-align:right"
                            name="deducciones_mostrado"
                            id="deducciones_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'deducciones'), cambiarTotal()"
                            onclick="select()">

                        <input type="hidden"
                            id="deducciones"
                            name="deducciones" value ='0'>
                    </td>
                </tr>
                <tr id="celda_total_nomina" style="display:none">
                    <td width="100" align="right" class='viewPropTitle'>Total</td>
                    <td>
                        <input type="text"
                            style="text-align:right"
                            name="total_nomina_mostrado"
                            id="total_nomina_mostrado"
                            size="15"
                            onBlur="formatoNumero(this.name, 'total_nomina')"
                            disabled="disabled">

                        <input type="hidden"
                            id="total_nomina"
                            name="total_nomina" value ='0'>
                    </td>
                </tr>
            </table>
        </td>

   <tr>
     <td align="right" class='viewPropTitle'>Ordenado Por</td>
     <td><input name="ordenado_por" type="text" id="ordenado_por" size="60"></td>
     <td align="right" class='viewPropTitle'>Cedula Ordenado</td>
     <td><input name="cedula_ordenado" type="text" id="cedula_ordenado" size="12"></td>
     <td align="right" class='viewPropTitle'>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td colspan="8" align="center"><input type="submit"
     										name="actualizar"
                                            id="actualizar"
                                            value="Actualizar"
                                            class="button"
                                            onClick="actualizarDatos(document.getElementById('id_orden_pago').value,
                                            document.getElementById('justificacion').value,
                                            document.getElementById('exento').value,
                                            document.getElementById('sub_total').value,
                                            document.getElementById('impuesto').value,
                                            document.getElementById('retenido').value,
                                            document.getElementById('total').value,
                                            document.getElementById('asignaciones').value,
                                            document.getElementById('deducciones').value,
                                            document.getElementById('total_nomina').value,
                                            document.getElementById('ordenado_por').value,
                                            document.getElementById('cedula_ordenado').value,
                                            document.getElementById('multi_categoria').value)">
       &nbsp;&nbsp;&nbsp;&nbsp;
       <label></label></td>
   </tr>
 </table>

<br />
<br />


<div id="mostrarPartidas" name="motrarPartidas" style="display:none"></div>

 <br>
 <br>

 <!-- TABLA BUSCAR ORDENES -->
 <table align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" class='viewPropTitle'>Numero de Orden: </td>
        <td><input name="campoBuscar" type="text" id="campoBuscar" size="40"></td>
        <td><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onClick="buscarOrdenes(document.getElementById('campoBuscar').value)"></td>
    </tr>
</table>
<br />



<div id="divMensaje"
		align="center"
        style="color:#FF0000;
        		font-family:Verdana, Arial, Helvetica, sans-serif;
                font-size:12px;
                font-weight:bold">
</div>

<br />

<!-- DIV LISTA DE ORDENES -->
<div id="listaOrdenes">

</div>