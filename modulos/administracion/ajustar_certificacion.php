<script src="modulos/administracion/js/ajustar_certificacion_ajax.js"></script>
    <br>
<h4 align=center>Ajustar Certificaciones de Compromiso</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 
 <!-- TABLA DE DATOS -->
 
 <input type="hidden" id="id_orden_compra" name="id_orden_compra">
 <table width="987" border="0" align="center" cellpadding="0" cellspacing="5">
   <tr>
     <td align="right" class='viewPropTitle'>Numero de Orden</td>
     <td colspan="7" id="numero_orden">&nbsp;</td>
   </tr>
   <tr>
     <td width="105" align="right" class='viewPropTitle'>Justificacion</td>
     <td colspan="7">
       <textarea name="justificacion" cols="100" rows="3" id="justificacion"></textarea>     </td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Exento</td>
     <td width="120">
     <input type="text" 
     		style="text-align:right" 
            name="exento_mostrado" 
            id="exento_mostrado" 
            size="15" 
            onBlur="formatoNumero(this.name, 'exento'), cambiarTotal()"
            onclick="select()">
            
     <input type="hidden" 
     		id="exento" 
            name="exento" >     </td>
     <td width="102" align="right" class='viewPropTitle'>Sub Total</td>
     <td width="76">
     <input type="text" 
     		style="text-align:right" 
            name="sub_total_mostrado" 
            id="sub_total_mostrado" 
            size="15" 
            onBlur="formatoNumero(this.name, 'sub_total'), cambiarTotal()"
            onclick="select()">
            
     <input type="hidden" 
     		id="sub_total" 
            name="sub_total" >     </td>
     <td width="92" align="right" class='viewPropTitle'>Impuesto</td>
     <td width="98">
     <input type="text" 
     		style="text-align:right" 
            name="impuesto_mostrado" 
            id="impuesto_mostrado" 
            size="15" 
            onBlur="formatoNumero(this.name, 'impuesto'), cambiarTotal()"
            onclick="select()">
            
     <input type="hidden" 
     		id="impuesto" 
            name="impuesto" >     </td>
     <td width="33" align="right" class='viewPropTitle'>Total</td>
     <td width="101">
     <input type="text" 
     		style="text-align:right" 
            name="total_mostrado" 
            id="total_mostrado" 
            size="15" 
            onBlur="formatoNumero(this.name, 'total')"
            disabled="disabled">
            
     <input type="hidden" 
     		id="total" 
            name="total" >     </td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Ordenado Por</td>
     <td><input name="ordenado_por" type="text" id="ordenado_por" size="20"></td>
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
                                            onClick="actualizarDatos(document.getElementById('id_orden_compra').value,
                                            document.getElementById('justificacion').value,
                                            document.getElementById('exento').value,
                                            document.getElementById('sub_total').value,
                                            document.getElementById('impuesto').value,
                                            document.getElementById('total').value,
                                            document.getElementById('ordenado_por').value,
                                            document.getElementById('cedula_ordenado').value)">
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