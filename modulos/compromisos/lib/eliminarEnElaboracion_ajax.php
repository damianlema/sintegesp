<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "eliminarOrdenCompra"){
$sql_eliminar_partidas = mysql_query("delete from partidas_orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'");
$sql_eliminar_relacion_compra = mysql_query("delete from relacion_compra_solicitud_cotizacion where idorden_compra_servicio = '".$id_orden_compra."'");
$sql_eliminar_impuestos = mysql_query("delete from relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$id_orden_compra."'");
$sql_eliminar_orden = mysql_query("delete from orden_compra_servicio where idorden_compra_servicio = '".$id_orden_compra."'")or die(mysql_error());
	if($sql_eliminar_orden){
		echo "exito";	
	}
}



if($ejecutar == "buscarOrdenes"){
?>
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
												and orden_compra_servicio.justificacion like '%".$campoBuscar."%' 
												and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo like '%-3-%' order by fecha_elaboracion ASC");
    //$sql_orden_compra = mysql_query("select * from orden_compra_servicio where numero_orden = '' and  order by fecha_elaboracion ASC");
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
<?
}
?>