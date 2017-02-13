<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "modificarFechaRetencion"){
	$sql_consultar = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$id_orden_pago."'")or die(mysql_error());
	while($bus_consultar = mysql_fetch_array($sql_consultar)){
		$sql_actualizar = mysql_query("update retenciones set fecha_aplicacion_retencion = '".$fecha."'
															where iddocumento = '".$bus_consultar["idorden_compra_servicio"]."'")or die(mysql_error());
	}
	if($sql_actualizar){
		echo "exito";
	}
}



if($ejecutar == "buscarOrdenes"){
?>
 <table width="70%" align="center" class="Browse" cellpadding="0" cellspacing="0" >
<thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td width="14%" align="center" class="Browse">N&uacute;mero de Orden</td>
              <td width="48%" align="center" class="Browse">Beneficiario</td>
              <td width="13%" align="center" class="Browse">Total Retenido</td>
              <td width="14%" align="center" class="Browse">Fecha Aplicaci&oacute;n</td>
              <td width="11%" align="center" class="Browse">Modificar</td>
    </tr>
        </thead>
    <?
	$sql_retenciones = mysql_query("select retenciones.fecha_aplicacion_retencion as fecha_aplicacion,
										   retenciones.fecha_retencion as fecha_retencion,
										   retenciones.idretenciones as idretenciones,
										   retenciones.iddocumento as iddocumento,
										   orden_pago.idorden_pago as idorden_pago,
										   orden_pago.numero_orden as numero_orden,
										   orden_pago.total_retenido as total_retenido,
										   beneficiarios.nombre as nombre
									  from beneficiarios,
										   retenciones,
										   orden_pago,
										   relacion_pago_compromisos,
										   orden_compra_servicio
									 where retenciones.fecha_aplicacion_retencion != '0000-00-00'
									   and retenciones.iddocumento = orden_compra_servicio.idorden_compra_servicio
									   and relacion_pago_compromisos.idorden_compra_servicio = orden_compra_servicio.idorden_compra_servicio
									   and relacion_pago_compromisos.idorden_pago = orden_pago.idorden_pago
									   and orden_pago.idbeneficiarios = beneficiarios.idbeneficiarios
									   and orden_pago.numero_orden like '%".$campoBuscar."%'
									   and beneficiarios.nombre like '%".$beneficiario."%'
									group by orden_pago.idorden_pago");
    $i = 0;
    while($bus_retenciones = mysql_fetch_array($sql_retenciones)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td align="center" class='Browse'><?=$bus_retenciones["numero_orden"]?></td>
            <td align="left" class='Browse'><?=$bus_retenciones["nombre"]?></td>
            <td align="right" class='Browse'><?=number_format($bus_retenciones["total_retenido"],2,",",".")?></td>
            <td align="center" class='Browse'><input type="text" 
            											name="fecha<?=$bus_retenciones["idorden_pago"]?>" 
                                                        id="fecha<?=$bus_retenciones["idorden_pago"]?>" 
                                                        size="12" 
                                                        readonly 
                                                        value="<?=$bus_retenciones["fecha_aplicacion"]?>">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_retenciones["idorden_pago"]?>" width="16" height="16" 
            									id="f_trigger_c<?=$bus_retenciones["idorden_pago"]?>" 
                                                style="cursor: pointer;" 
                                                title="Selector de Fecha" 
                                                onMouseOver="this.style.background='red';" 
                                                onMouseOut="this.style.background=''" onClick="
                                Calendar.setup({
                                inputField    : 'fecha<?=$bus_retenciones["idorden_pago"]?>',
                                button        : 'f_trigger_c<?=$bus_retenciones["idorden_pago"]?>',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                });"/>
            </td>
            <td align="center" class='Browse'><input type="button" 
            										name="botonModificar<?=$bus_retenciones["idorden_pago"]?>" 
                                                    id="botonModificar<?=$bus_retenciones["idorden_pago"]?>" 
                                                    onClick="modificarFechaRetencion('<?=$bus_retenciones["idorden_pago"]?>', 'fecha<?=$bus_retenciones["idorden_pago"]?>', 'botonModificar<?=$bus_retenciones["idorden_pago"]?>')" 
                                                    value="Modificar" 
                                                    class="button"></td>
      </tr>
        <?
    }
    
    ?>
    </table>
<?
}
?>