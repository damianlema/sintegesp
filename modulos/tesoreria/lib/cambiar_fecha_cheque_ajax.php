<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "modificarFechaOrdenCompra"){
$sql_modificar_orden = mysql_query("update pagos_financieros set fecha_cheque = '".$fecha."' where idpagos_financieros = '".$id_orden_compra."'");
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
                <td align="center" class="Browse">Numero de Documento</td>
                <td align="center" class="Browse">Numero de Cheque</td>
                <td align="center" class="Browse">Fecha a Cambiar</td>
                <td align="center" class="Browse">Modificar</td>
            </tr>
        </thead>
    <?
	$sql_orden_compra = mysql_query("select * from pagos_financieros 
												where pagos_financieros.numero_cheque != ''
												and pagos_financieros.numero_cheque like '%".$campoBuscar."%' 
												order by pagos_financieros.numero_cheque ASC")or die(mysql_error());
    $i = 0;
    while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="center" class='Browse'><?=$bus_orden_compra["numero_documento"]?></td>
            <td align="center" class='Browse'><?=$bus_orden_compra["numero_cheque"]?></td>
            <td align="center" class='Browse'><input type="text" name="fecha<?=$bus_orden_compra["idpagos_financieros"]?>" id="fecha<?=$bus_orden_compra["idpagos_financieros"]?>" size="12" readonly value="<?=$bus_orden_compra["fecha_cheque"]?>">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_orden_compra["idpagos_financieros"]?>" width="16" height="16" id="f_trigger_c<?=$bus_orden_compra["idpagos_financieros"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onclick="
                                Calendar.setup({
                                inputField    : 'fecha<?=$bus_orden_compra["idpagos_financieros"]?>',
                                button        : 'f_trigger_c<?=$bus_orden_compra["idpagos_financieros"]?>',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                });
                            "/></td>
            <td align="center" class='Browse'><input type="button" name="botonModificar<?=$bus_orden_compra["idpagos_financieros"]?>" id="botonModificar<?=$bus_orden_compra["idpagos_financieros"]?>" onClick="modificarOrdenCompra('<?=$bus_orden_compra["idpagos_financieros"]?>', 'fecha<?=$bus_orden_compra["idpagos_financieros"]?>', 'botonModificar<?=$bus_orden_compra["idpagos_financieros"]?>')" value="Modificar" class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
<?
}
?>