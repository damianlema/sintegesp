<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "actualizarDatos"){
	$sql_actualizar = mysql_query("update pagos_financieros set recibido_por = '".$recibido_por."',
																ci_recibe = '".$ci_recibe."',
																fecha_recibe = '".$fecha_recibe."'
																where idpagos_financieros = '".$idpagos_financieros."'")or die(mysql_error());
	if($sql_actualizar){
		echo "exito";
	}else{
		echo "fallo";
	}
}






if($ejecutar == "listarPagosFinancieros"){
	?>
	<table align="center" class="Browse" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Nro. Orden</td>
                <td align="center" class="Browse">Beneficiario</td>
                <td align="center" class="Browse">Monto Cheque</td>
                <td align="center" class="Browse">Nro. Cheque</td>
                <td align="center" class="Browse">Entregar</td>
            </tr>
        </thead>
    <?
    //echo "CUENTA: ".$cuenta." BANCO: ".$banco." ESTADO: ".$estado;
		$sql = "(select pagos_financieros.idpagos_financieros as idpagos_financieros,
						pagos_financieros.idorden_pago as idorden_pago,
						pagos_financieros.beneficiario as beneficiario,
						pagos_financieros.monto_cheque as monto_cheque,
						pagos_financieros.numero_cheque as numero_cheque,
						pagos_financieros.recibido_por as recibido_por,
						pagos_financieros.ci_recibe as ci_recibe,
						pagos_financieros.fecha_recibe as fecha_recibe,
						orden_pago.numero_orden as numero_orden from pagos_financieros, 
						orden_pago 
							where 
								pagos_financieros.estado = 'transito' 
								and orden_pago.idorden_pago = pagos_financieros.idorden_pago";
		if($cuenta != 0){
			$sql.= " and pagos_financieros.idcuenta_bancaria = '".$cuenta."'";
		}
		if($nro_orden != ""){
			$sql .= " and orden_pago.numero_orden like '%".$nro_orden."%'";
		}
		if($nro_cheque != ""){
			$sql .= " and pagos_financieros.numero_cheque like '%".$nro_cheque."%'";
		}
		if($beneficiario != ""){
			$sql .= " and pagos_financieros.beneficiario like '%".$beneficiario."%'";
		}
		
    $sql .= ") UNION ";  
	
	$sql .= " (select idorden_pago as idorden_pago,
						beneficiario as beneficiario,
						monto_cheque as monto_cheque,
						numero_cheque as numero_cheque,
						recibido_por as recibido_por,
						ci_recibe as ci_recibe,
						fecha_recibe as fecha_recibe,
						codigo_referencia as codigo_referencia,
						'' as numero_orden
						from pagos_financieros
							where 
								estado = 'transito' 
								and idorden_pago = 0";
		if($cuenta != 0){
			$sql.= " and idcuenta_bancaria = '".$cuenta."'";
		}
		if($nro_cheque != ""){
			$sql .= " and numero_cheque like '%".$nro_cheque."%'";
		}
		if($beneficiario != ""){
			$sql .= " and beneficiario like '%".$beneficiario."%'";
		}
	
	
	$sql .= ")";
	//echo $sql;
	$sql_pagos_financieros = mysql_query($sql)or die(mysql_error());
	$i = 0;
    while($bus_pagos_financieros = mysql_fetch_array($sql_pagos_financieros)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            <?
			if($bus_pagos_financieros["idorden_pago"] != 0){
				$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_pagos_financieros["idorden_pago"]."'");
				$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
				$nro_orden = $bus_orden_pago["numero_orden"];
			}else{
				$nro_orden = "<strong>Pago Directo</strong>";
			}
			?>
            <td align="center" class='Browse'><?=$nro_orden?></td>
            <td align="left" class='Browse'><?=$bus_pagos_financieros["beneficiario"]?></td>
            <td align="right" class='Browse'><?=$bus_pagos_financieros["monto_cheque"]?></td>
            <td align="center" class='Browse'><?=$bus_pagos_financieros["numero_cheque"]?></td>
            <td align="center" class='Browse'>
            <img src="imagenes/modificar.png"
            	style="cursor:pointer"
                onClick="mostrarDatos('<?=$bus_pagos_financieros["idpagos_financieros"]?>',
                					'<?=$nro_orden?>',
                					'<?=$bus_pagos_financieros["beneficiario"]?>',
                                    '<?=$bus_pagos_financieros["recibido_por"]?>',
                                    '<?=$bus_pagos_financieros["ci_recibe"]?>',
                                    '<?=$bus_pagos_financieros["fecha_recibe"]?>')">
            </td>
      </tr>
        <?
    }
    
    ?>
  </table>
	<?
}

?>