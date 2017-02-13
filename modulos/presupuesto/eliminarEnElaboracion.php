<script src="modulos/presupuesto/js/eliminarEnElaboracion_ajax.js"></script>
    <br>
<h4 align=center>Eliminar Ordenes en Elaboracion</h4>
	<h2 class="sqlmVersion"></h2>
 <br>


<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" class='viewPropTitle'>Justiicación: </td>
        <td><input name="campoBuscar" type="text" id="campoBuscar" size="40"></td>
        <td><input type="button" name="botonBuscar" id="botonBuscar" value="Buscar" class="button" onClick="buscarOrdenes()"></td>
    </tr>
</table>
<br />



    <div id="divMensaje" align="center" style="color:#FF0000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold"></div>
    
<br />    
<div id="listaOrdenes">   
    <table align="center" class="Browse" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Beneficiario</td>
                <td align="center" class="Browse">Justificacion</td>
                <td align="center" class="Browse">Monto</td>
                <td align="center" class="Browse">Eliminar</td>
            </tr>
        </thead>
    <?
    $sql_orden_compra = mysql_query("select * from orden_compra_servicio, tipos_documentos 
												where orden_compra_servicio.numero_orden = '' 
												and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo = 2 order by fecha_elaboracion ASC");
    $i = 0;
    while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <?
            $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_orden_compra["idbeneficiarios"]."'");
			$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
			?>
            <td align="center" class='Browse'><?=$bus_beneficiario["nombre"]?></td>
            <td align="left" class='Browse'><?=$bus_orden_compra["justificacion"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_compra["total"]?></td>
            <td align="center" class='Browse'><input type="button" name="botonEliminar<?=$bus_orden_compra["idorden_compra_servicio"]?>" id="botonEliminar<?=$bus_orden_compra["idorden_compra_servicio"]?>" onClick="eliminarOrdenCompra('<?=$bus_orden_compra["idorden_compra_servicio"]?>', 'botonEliminar<?=$bus_orden_compra["idorden_compra_servicio"]?>')" value="Eliminar" class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
</div>