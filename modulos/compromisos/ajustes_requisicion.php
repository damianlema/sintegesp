<script src="modulos/compromisos/js/ajustes_requisicion_ajax.js"></script>
    <br>
<h4 align=center>Ajustar Requisiciones</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 
 <!-- TABLA DE DATOS -->
 
 <input type="hidden" id="id_requisicion" name="id_requisicion">
 <table width="975" border="0" align="center" cellpadding="0" cellspacing="5">
   <tr>
     <td align="right" class='viewPropTitle'>Numero de Requisicion</td>
     <td colspan="9" id="numero_orden">&nbsp;</td>
   </tr>
   <tr>
     <td width="123" align="right" class='viewPropTitle'>Justificacion</td>
     <td colspan="9">
       <textarea name="justificacion" cols="100" rows="3" id="justificacion"></textarea>     </td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Exento</td>
     <td width="76">
     <input type="text" 
     		style="text-align:right" 
            name="exento_mostrado" 
            id="exento_mostrado" 
            size="11" 
            onBlur="formatoNumero(this.name, 'exento')">
            
     <input type="hidden" 
     		id="exento" 
            name="exento" >     </td>
     <td width="112" align="right" class='viewPropTitle'>Sub Total</td>
     <td width="80">
     <input type="text" 
     		style="text-align:right" 
            name="sub_total_mostrado" 
            id="sub_total_mostrado" 
            size="11" 
            onBlur="formatoNumero(this.name, 'sub_total')">
            
     <input type="hidden" 
     		id="sub_total" 
            name="sub_total" >     </td>
     <td width="102" align="right" class='viewPropTitle'>Impuesto</td>
     <td width="72">
     <input type="text" 
     		style="text-align:right" 
            name="impuesto_mostrado" 
            id="impuesto_mostrado" 
            size="11" 
            onBlur="formatoNumero(this.name, 'impuesto')">
            
     <input type="hidden" 
     		id="impuesto" 
            name="impuesto" >     </td>
     <td width="115" align="right" class='viewPropTitle'>Descuento</td>
     <td width="75">
     <input type="text" 
     		style="text-align:right" 
            name="descuento_mostrado" 
            id="descuento_mostrado" 
            size="11" 
            onBlur="formatoNumero(this.name, 'descuento')">
            
     <input type="hidden" 
     		id="descuento" 
            name="descuento" >     </td>
     <td width="36" align="right" class='viewPropTitle'>Total</td>
     <td width="129">
     <input type="text" 
     		style="text-align:right" 
            name="total_mostrado" 
            id="total_mostrado" 
            size="11" 
            onBlur="formatoNumero(this.name, 'total')"
            disabled="disabled">
            
     <input type="hidden" 
     		id="total" 
            name="total" >     </td>
   </tr>
   <tr>
     <td colspan="10" align="center"><input type="submit" 
     										name="actualizar" 
                                            id="actualizar" 
                                            value="Actualizar" 
                                            class="button"
                                            onClick="actualizarDatos(document.getElementById('id_requisicion').value, 
                							document.getElementById('descuento').value,
                                            document.getElementById('justificacion').value,
                                            document.getElementById('exento').value,
                                            document.getElementById('sub_total').value,
                                            document.getElementById('impuesto').value,
                                            document.getElementById('total').value)">
                                            
                                            
    &nbsp;&nbsp;&nbsp;&nbsp;
       <input type="submit" name="original" id="original" value="Volver a Montos Originales" onclick="volverMontosOriginales(document.getElementById('id_requisicion').value)" class="button"/>                                        
    
    </td>
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
    	<td align="center" class='viewPropTitle'>Numero de Requisicion: </td>
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