<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "eliminarOrdenCompra"){
$sql_eliminar_partidas = mysql_query("delete from partidas_orden_pago where idorden_pago = '".$id_orden_pago."'");
$sql_eliminar_relacion_retenciones = mysql_query("delete from relacion_orden_pago_retencion where idorden_pago = '".$id_orden_pago."'");
$sql_eliminar_compromisos = mysql_query("delete from relacion_pagos_compromisos where idorden_pago = '".$id_orden_pago."'");
$sql_eliminar_orden = mysql_query("delete from orden_pago where idorden_pago = '".$id_orden_pago."'")or die(mysql_error());
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
    $sql_orden_pago = mysql_query("select * from orden_pago where numero_orden = '' and justificacion like '%".$campoBuscar."%' order by codigo_referencia ASC");
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
<?
}
?>