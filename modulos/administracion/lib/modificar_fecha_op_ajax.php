<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "modificarFechaOrdenPago"){
$sql_modificar_orden = mysql_query("update orden_pago set fecha_orden = '".$fecha."', fecha_elaboracion = '".$fecha."' where idorden_pago = '".$id_orden_pago."'")or die(mysql_error());

$sql_relacion = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$id_orden_pago."'");
$existen = mysql_num_rows($sql_relacion);
if ($existen > 0){
	while($bus_relacion = mysql_fetch_array($sql_relacion)){
		$sql_actualizar = mysql_query("update retenciones 
											set 
												fecha_aplicacion_retencion = '".$fecha."'
											where 
												idretenciones = '".$bus_relacion["idretencion"]."'");
	}
}

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
	$sql_orden_pago = mysql_query("select * from orden_pago, tipos_documentos 
												where orden_pago.numero_orden != ''
												and orden_pago.tipo = '".$tipoOrden."' 
												and orden_pago.numero_orden like '%".$campoBuscar."%' 
												and orden_pago.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo like '%-4-%' order by codigo_referencia ASC");
    $i = 0;
    while($bus_orden_pago = mysql_fetch_array($sql_orden_pago)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="center" class='Browse'><?=$bus_orden_pago["numero_orden"]?></td>
            <td align="center" class='Browse'><input type="text" name="fecha<?=$bus_orden_pago["idorden_pago"]?>" id="fecha<?=$bus_orden_pago["idorden_pago"]?>" size="12" readonly value="<?=$bus_orden_pago["fecha_orden"]?>">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_orden_pago["idorden_pago"]?>" width="16" height="16" id="f_trigger_c<?=$bus_orden_pago["idorden_pago"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="Calendar.setup({
                                inputField    : 'fecha<?=$bus_orden_pago["idorden_pago"]?>',
                                button        : 'f_trigger_c<?=$bus_orden_pago["idorden_pago"]?>',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                }); "/>
                            </td>
            <td align="center" class='Browse'><input type="button" name="botonModificar<?=$bus_orden_pago["idorden_pago"]?>" id="botonModificar<?=$bus_orden_pago["idorden_pago"]?>" onClick="modificarOrdenPago('<?=$bus_orden_pago["idorden_pago"]?>', 'fecha<?=$bus_orden_pago["idorden_pago"]?>', 'botonModificar<?=$bus_orden_pago["idorden_pago"]?>')" value="Modificar" class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
<?
}
?>