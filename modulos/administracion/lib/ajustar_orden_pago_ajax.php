<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


if($ejecutar == "actualizarDatos"){

	$sql_consultar_descuento = mysql_query("select * from orden_pago where idorden_pago = '".$id_orden_pago."'");
	$bus_consultar_descuento = mysql_fetch_array($sql_consultar_descuento);

	$descuento_viejo = $bus_consultar_descuento["descuento"];
	$sub_total_viejo = $bus_consultar_descuento["sub_total"];


	if($multi_categoria == 'si'){
		$total_a_pagar = $asignaciones - $deducciones;
		$total = $total_a_pagar;
		$exento = $deducciones;
		$sub_total = $asignaciones;
	}else{
		$total_a_pagar = $exento + $sub_total + $impuesto - $retenido;
		$total =  $exento + $sub_total + $impuesto;
	}


	$sql_actualizar_datos = mysql_query("update orden_pago set justificacion = '".$justificacion."',
																			exento = '".$exento."',
																			sub_total = '".$sub_total."',
																			impuesto = '".$impuesto."',
																			total = '".$total."',
																			total_a_pagar = '".$total_a_pagar."',
																			ordenado_por = '".$ordenado_por."',
																			cedula_ordenado = '".$cedula_ordenado."'
																			where idorden_pago = '".$id_orden_pago."'")or die("Error en el Primer Query: ".mysql_error());


}










if($ejecutar == "buscarOrdenes"){
	?>

	<table align="center" class="Browse" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Nº</td>
                <td align="center" class="Browse">N&uacute;mero de Orden</td>
                <td align="center" class="Browse">Beneficiario</td>
                <td align="center" class="Browse">Justificaci&oacute;n</td>
                <td align="center" class="Browse">Sub Total / Asignaciones</td>
                <td align="center" class="Browse">Exento / Deducciones</td>
                <td align="center" class="Browse">Impuesto</td>
                <td align="center" class="Browse">Retenido</td>
                <td align="center" class="Browse">Total</td>
                <td align="center" class="Browse">Sel</td>
            </tr>
        </thead>
    <?
    $sql_orden_pago = mysql_query("select * from orden_pago, tipos_documentos 
												where orden_pago.numero_orden != '' 
												and orden_pago.numero_orden like '%".$numero_orden."%'
												and orden_pago.tipo = tipos_documentos.idtipos_documentos
												and tipos_documentos.modulo like '%-4-%' order by fecha_elaboracion ASC");
    $i = 0;
    while($bus_orden_pago = mysql_fetch_array($sql_orden_pago)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <?
            $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_orden_pago["idbeneficiarios"]."'");
			$bus_beneficiario = mysql_fetch_array($sql_beneficiario);
			?>
            <td align="center" class='Browse'><?=$i+1?></td>
            <td align="left" class='Browse'><?=$bus_orden_pago["numero_orden"]?></td>
            <td align="left" class='Browse'><?=$bus_beneficiario["nombre"]?></td>
            <td align="left" class='Browse'><?=$bus_orden_pago["justificacion"]?>
            <input type="hidden" id="justifica" value='<?=$bus_orden_pago["justificacion"]?>'></td>
            <td align="right" class='Browse'><?=$bus_orden_pago["sub_total"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_pago["exento"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_pago["impuesto"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_pago["total_retenido"]?></td>
            <td align="right" class='Browse'><?=$bus_orden_pago["total_a_pagar"]?></td>
            <td align="center" class='Browse'>
            <img src="imagenes/validar.png"
                title="Actualizar Descuento"
                style="cursor:pointer"
                onclick="mostrarDatos('<?=$bus_orden_pago["idorden_pago"]?>',
                						'<?=$bus_orden_pago["numero_orden"]?>',
                                        '<?=$bus_orden_pago["justificacion"]?>',
                                        '<?=$bus_orden_pago["exento"]?>',
                                        '<?=$bus_orden_pago["sub_total"]?>',
                                        '<?=$bus_orden_pago["impuesto"]?>',
                                        '<?=$bus_orden_pago["total_retenido"]?>',
                                        '<?=$bus_orden_pago["total_a_pagar"]?>',
                                        '<?=number_format($bus_orden_pago["exento"],2,",",".")?>',
                                        '<?=number_format($bus_orden_pago["sub_total"],2,",",".")?>',
                                        '<?=number_format($bus_orden_pago["impuesto"],2,",",".")?>',
                                        '<?=number_format($bus_orden_pago["total_retenido"],2,",",".")?>',
                                        '<?=number_format($bus_orden_pago["total_a_pagar"],2,",",".")?>',
                                        '<?=$bus_orden_pago["ordenado_por"]?>',
                                        '<?=$bus_orden_pago["cedula_ordenado"]?>',
                                        '<?=$bus_orden_pago["multi_categoria"]?>')">            </td>
      </tr>
        <?

		// '<?=str_replace("\n"," ",$bus_orden_compra["justificacion"])
		$i++;
    }

    ?>
    </table>

	<?
}








if($ejecutar == "listarPartidas"){
	$sql_partidas =  mysql_query("select * from partidas_orden_pago where idorden_pago = '".$id_orden_pago."'")or die(mysql_error());
?>
 	<table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
          <tr>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td width="42%" class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td width="15%" class="Browse"><div align="center">Disponible</div></td>
            <td width="15%" class="Browse"><div align="center">Monto a Comprometer</div></td>
            <td class="Browse" colspan="2"><div align="center">Acciones</div></td>
          </tr>
      </thead>
          <? 
          while($bus_partidas = mysql_fetch_array($sql_partidas)){

		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);

          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_maestro["idclasificador_presupuestario"]."");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
           <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <td width="2%" align='left' class='Browse'><?=$bus_clasificador["partida"]?></td>
            <td width="2%" align='left' class='Browse'><?=$bus_clasificador["generica"]?></td>
            <td width="2%" align='left' class='Browse'><?=$bus_clasificador["especifica"]?></td>
            <td width="2%" align='left' class='Browse'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>
			<td class='Browse' align="right"><?=number_format($bus_maestro["monto_actual"]-$bus_maestro["total_compromisos"],2,',','.')?></td>
			<td class='Browse' align='center'>
           	  <input type="text"
                		name="monto_mostrado<?=$bus_partidas["idmaestro_presupuesto"]?>"
                        id="monto_mostrado<?=$bus_partidas["idmaestro_presupuesto"]?>"
                        value="<?=number_format($bus_partidas["monto"],2,',','.')?>"
                        style="text-align:right"
                        onblur="formatoNumero(this.name, 'monto<?=$bus_partidas["idmaestro_presupuesto"]?>')">
               <input type="hidden"
                		name="monto<?=$bus_partidas["idmaestro_presupuesto"]?>"
                        id="monto<?=$bus_partidas["idmaestro_presupuesto"]?>"
                        value="<?=$bus_partidas["monto"]?>"
                        style="text-align:right">
			</td>
            <td width="6%" align='center' class='Browse'>
           	  <img src="imagenes/refrescar.png"
                	border="0"
                    style="cursor:pointer"
                    title="Actualizar Monto"
                    onclick="actualizarPartidas('<?=$bus_partidas["idpartidas_orden_pago"]?>', document.getElementById('monto<?=$bus_partidas["idmaestro_presupuesto"]?>').value)"></td>
            <td width="6%" align='center' class='Browse'>
            <img src="imagenes/rewind.png" 
            	border="0" 
                style="cursor:pointer"
                title="Volver a Montos Originales"
                onclick="document.getElementById('monto_mostrado<?=$bus_partidas["idmaestro_presupuesto"]?>').value = '<?=number_format($bus_partidas["monto_original"],2,',','.')?>',
                		document.getElementById('monto<?=$bus_partidas["idmaestro_presupuesto"]?>').value = '<?=$bus_partidas["monto_original"]?>'"></td>
      </tr>
          <?
          }
          ?>
</table>
        <?
}











if($ejecutar == "actualizarPartidas"){
	$sql_partida = mysql_query("select * from partidas_orden_pago where idpartidas_orden_pago = '".$idpartida."'");
	$bus_partida = mysql_fetch_array($sql_partida);
	$sql_actualizar_partida = mysql_query("update partidas_orden_pago set monto = '".$monto_actualizar."'
																				where idpartidas_orden_pago = '".$idpartida."'")or die(mysql_error());

	$sql_actualizar_maestro = mysql_query("update maestro_presupuesto set
			total_compromisos = (total_causados + '".$bus_partida["monto_original"]."')-'".$bus_partida["monto"]."'
			where idRegistro = '".$bus_partida["idmaestro_presupuesto"]."'");

	if($sql_actualizar_partida){
		if($sql_actualizar_maestro){
			echo "exito";
		}
	}
}




?>