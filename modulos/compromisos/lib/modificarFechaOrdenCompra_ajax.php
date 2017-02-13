<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "modificarFechaOrdenCompra"){
$sql_modificar_orden = mysql_query("update orden_compra_servicio set fecha_orden = '".$fecha."', fecha_elaboracion = '".$fecha."' where idorden_compra_servicio = '".$id_orden_compra."'");
	if($sql_modificar_orden){
		echo "exito";	
	}
}



if($ejecutar == "buscarOrdenes"){
?>
 <table width="467" align="center" class="Browse" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Numero de Orden</td>
                <td align="center" class="Browse">Fecha a Cambiar</td>
                <td align="center" class="Browse">Modificar</td>
            </tr>
        </thead>
    <?
	$sql_orden_compra = mysql_query("select * from orden_compra_servicio, tipos_documentos 
												where orden_compra_servicio.numero_orden != ''
												and orden_compra_servicio.tipo = '".$tipoOrden."' 
												and orden_compra_servicio.numero_orden like '%".$campoBuscar."%' 
												and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo like '%-3-%' order by orden_compra_servicio.codigo_referencia ASC");
    $i = 0;
    while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="center" class='Browse'><?=$bus_orden_compra["numero_orden"]?></td>
            <td align="center" class='Browse'><input type="text" name="fecha<?=$bus_orden_compra["idorden_compra_servicio"]?>" id="fecha<?=$bus_orden_compra["idorden_compra_servicio"]?>" size="12" readonly value="<?=$bus_orden_compra["fecha_orden"]?>">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_orden_compra["idorden_compra_servicio"]?>" width="16" height="16" id="f_trigger_c<?=$bus_orden_compra["idorden_compra_servicio"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onclick="
                                Calendar.setup({
                                inputField    : 'fecha<?=$bus_orden_compra["idorden_compra_servicio"]?>',
                                button        : 'f_trigger_c<?=$bus_orden_compra["idorden_compra_servicio"]?>',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                });
                            "/></td>
            <td align="center" class='Browse'><input type="button" name="botonModificar<?=$bus_orden_compra["idorden_compra_servicio"]?>" id="botonModificar<?=$bus_orden_compra["idorden_compra_servicio"]?>" onClick="modificarOrdenCompra('<?=$bus_orden_compra["idorden_compra_servicio"]?>', 'fecha<?=$bus_orden_compra["idorden_compra_servicio"]?>', 'botonModificar<?=$bus_orden_compra["idorden_compra_servicio"]?>')" value="Modificar" class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
<?
}
?>