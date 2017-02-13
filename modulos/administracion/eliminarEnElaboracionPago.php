<script src="modulos/administracion/js/eliminarEnElaboracionPago_ajax.js"></script>
    <br>
<h4 align=center>Eliminar Ordenes de Pago en Elaboracion</h4>
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
    $sql_orden_pago = mysql_query("select * from orden_pago where numero_orden = '' order by codigo_referencia ASC");
    $i = 0;
    while($bus_orden_pago = mysql_fetch_array($sql_orden_pago)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <?
            $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_orden_pago["idbeneficiarios"]."'");
			$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
			?>
            <td align="center" class='Browse'><?=$bus_beneficiario["nombre"]?></td>
            <td align="left" class='Browse'><?=$bus_orden_pago["justificacion"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_pago["total"]?></td>
            <td align="center" class='Browse'><input type="button" name="botonEliminar<?=$bus_orden_pago["idorden_pago"]?>" id="botonEliminar<?=$bus_orden_pago["idorden_pago"]?>" onClick="eliminarOrdenCompra('<?=$bus_orden_pago["idorden_pago"]?>', 'botonEliminar<?=$bus_orden_pago["idorden_pago"]?>')" value="Eliminar" class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
</div>