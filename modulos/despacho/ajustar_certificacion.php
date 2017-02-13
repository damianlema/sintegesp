<script src="modulos/despacho/js/ajustar_certificacion_ajax.js"></script>
    <br>
<h4 align=center>Ajustar Certificacion de Compromisos</h4>
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
     <td align="right" class='viewPropTitle'>Ordenado Por</td>
     <td width="120"><input name="ordenado_por" type="text" id="ordenado_por" size="20"></td>
     <td width="102" align="right" class='viewPropTitle'>Cedula Ordenado</td>
     <td width="76"><input name="cedula_ordenado" type="text" id="cedula_ordenado" size="12"></td>
     <td width="92" align="right" class='viewPropTitle'>Nro Doumento</td>
     <td width="98"><input name="nro_requisicion" type="text" id="nro_requisicion" size="12"></td>
     <td width="102" align="right" class='viewPropTitle'>Fecha Documento</td>
     <td width="103">
     <input name="fecha_requisicion" type="text" id="fecha_requisicion" size="12" readonly="readonly">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_requisicion",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>     </td>
   </tr>
   
   <tr>
     <td colspan="8" align="center"><input type="submit" 
     										name="actualizar" 
                                            id="actualizar" 
                                            value="Actualizar" 
                                            class="button"
                                            onClick="actualizarDatos(document.getElementById('id_orden_compra').value, 
                                            document.getElementById('justificacion').value,
                                            document.getElementById('ordenado_por').value,
                                            document.getElementById('cedula_ordenado').value,
                                            document.getElementById('nro_requisicion').value,
                                            document.getElementById('fecha_requisicion').value)"></td>
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