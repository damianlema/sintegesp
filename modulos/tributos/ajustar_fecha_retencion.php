<script src="modulos/tributos/js/ajustar_fecha_retencion_ajax.js"></script>
    <br>
<h4 align=center>Modificar Fechas de Retenciones</h4>
	<h2 class="sqlmVersion"></h2>
 <br>


<table width="600" align="center" cellpadding="0" cellspacing="0">
<tr>
    	<td width="112" align="center" class='viewPropTitle'>Nº Orden de Pago</td>
        <td width="144"><input type="text" name="campoBuscar" id="campoBuscar"></td>
        <td width="93" align="center" class='viewPropTitle'>Beneficiario</td>
      <td width="89"><label>
        <input type="text" name="beneficiario" id="beneficiario">
      </label></td>
      <td width="54">
      <input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onClick="buscarOrdenes()"></td>
  </tr>
</table>
<br />



    <div id="divMensaje" align="center" style="color:#FF0000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold"></div>
    
<br />
    
<div id="listaOrdenes"></div>