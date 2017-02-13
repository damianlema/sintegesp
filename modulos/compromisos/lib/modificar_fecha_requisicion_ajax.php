<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "modificarFechaOrdenCompra"){
$sql_modificar_orden = mysql_query("update requisicion set fecha_orden = '".$fecha."', fecha_elaboracion = '".$fecha."' where idrequisicion = '".$id_orden_compra."'");
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
	$sql_orden_compra = mysql_query("select * from requisicion, tipos_documentos 
												where requisicion.numero_requisicion != ''
												and requisicion.tipo = '".$tipoOrden."' 
												and requisicion.numero_requisicion like '%".$campoBuscar."%' 
												and requisicion.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo like '%-3-%' order by requisicion.codigo_referencia ASC");
    $i = 0;
    while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="center" class='Browse'><?=$bus_orden_compra["numero_requisicion"]?></td>
            <td align="center" class='Browse'><input type="text" name="fecha<?=$bus_orden_compra["idrequisicion"]?>" id="fecha<?=$bus_orden_compra["idrequisicion"]?>" size="12" readonly value="<?=$bus_orden_compra["fecha_orden"]?>">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_orden_compra["idrequisicion"]?>" width="16" height="16" id="f_trigger_c<?=$bus_orden_compra["idrequisicion"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="
                                Calendar.setup({
                                inputField    : 'fecha<?=$bus_orden_compra["idrequisicion"]?>',
                                button        : 'f_trigger_c<?=$bus_orden_compra["idrequisicion"]?>',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                });
                            "/></td>
            <td align="center" class='Browse'><input type="button" name="botonModificar<?=$bus_orden_compra["idrequisicion"]?>" id="botonModificar<?=$bus_orden_compra["idrequisicion"]?>" onClick="modificarOrdenCompra('<?=$bus_orden_compra["idrequisicion"]?>', 'fecha<?=$bus_orden_compra["idrequisicion"]?>', 'botonModificar<?=$bus_orden_compra["idrequisicion"]?>')" value="Modificar" class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
<?
}
?>