<script src="modulos/nomina/js/modificarFechaOrdenCompra_ajax.js"></script>
    <br>
<h4 align=center>Modificar Fechas de las Ordenes de Compras</h4>
	<h2 class="sqlmVersion"></h2>
 <br>


<table width="600" align="center" cellpadding="0" cellspacing="0">
<tr>
    	<td width="112" align="center" class='viewPropTitle'>Numero de Orden</td>
      <td width="144"><input type="text" name="campoBuscar" id="campoBuscar"></td>
        <td width="93" align="center" class='viewPropTitle'>Tipo Orden</td>
        <td width="89">
			<select name="tipoOrden" id="tipoOrden">
            <?
            $sql_tipo_documento = mysql_query("select * from tipos_documentos where modulo like '%-13-%'");
			while($bus_tipo_documento = mysql_fetch_array($sql_tipo_documento)){
			?>
            	<option value="<?=$bus_tipo_documento["idtipos_documentos"]?>"><?=$bus_tipo_documento["descripcion"]?></option>
            <?
            }
			?>
            </select>
      </td>
      <td width="54"><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onclick="buscarOrdenes()"></td>
  </tr>
</table>
<br />



    <div id="divMensaje" align="center" style="color:#FF0000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold"></div>
    
<br />
    
<div id="listaOrdenes"></div>