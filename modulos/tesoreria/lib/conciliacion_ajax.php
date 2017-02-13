<?
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "conciliarPago"){
$sql_conciliar_pago = mysql_query("update pagos_financieros set estado = 'conciliado',
																fecha_conciliado = '".$fecha_conciliado."'
															 where idpagos_financieros = '".$id_pago_financiero."'")or die(mysql_error());
	if($sql_conciliar_pago){
		echo "exito";	
	}
}

if($ejecutar == "actualizar_fecha"){
$sql_conciliar_pago = mysql_query("update pagos_financieros set fecha_conciliado = '".$fecha_conciliado."'
															 where idpagos_financieros = '".$id_pago_financiero."'")or die(mysql_error());
	if($sql_conciliar_pago){
		echo "exito";	
	}
}

if($ejecutar == "devolverConciliacion"){
$sql_conciliar_pago = mysql_query("update pagos_financieros set estado = 'transito',
																fecha_conciliado = fecha_cheque
															where idpagos_financieros = '".$id_pago_financiero."'")or die(mysql_error());
	if($sql_conciliar_pago){
		echo "exito";	
	}
}




if($ejecutar == "buscarPagos"){
?>
    <table align="center" class="Browse" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <!--<td class="Browse">&nbsp;</td>-->
                <td align="center" class="Browse">Nro. Orden</td>
                <td align="center" class="Browse">Beneficiario</td>
                <td align="center" class="Browse">Monto Cheque</td>
                <td align="center" class="Browse">Nro. Cheque</td>
                <td align="center" class="Browse">Fecha Cheque</td>
                <td align="center" class="Browse">Fecha Conciliar</td>
                <td align="center" class="Browse">Conciliar</td>
            </tr>
        </thead>
    <?
    //echo "CUENTA: ".$cuenta." BANCO: ".$banco." ESTADO: ".$estado;
	if($cuenta != "0" and $banco != "0" and $estado != "0"){
		$sql_pagos_financieros = mysql_query("select * from pagos_financieros where estado = '".$estado."' and idcuenta_bancaria = '".$cuenta."' order by fecha_cheque, codigo_referencia ASC");
	}else{
		$sql_pagos_financieros = mysql_query("select * from pagos_financieros where estado = 'transito' order by fecha_cheque, codigo_referencia ASC");
	}
    
	
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
            <td align="center" class='Browse'>&nbsp;<?=$nro_orden?></td>
            <td align="left" class='Browse'>&nbsp;<?=$bus_pagos_financieros["beneficiario"]?></td>
            <td align="right" class='Browse'>&nbsp;<?=number_format($bus_pagos_financieros["monto_cheque"],2,",",".")?></td>
            <td align="center" class='Browse'>&nbsp;<?=$bus_pagos_financieros["numero_cheque"]?></td>
            <td align="center" class='Browse'>&nbsp;<?=$bus_pagos_financieros["fecha_cheque"]?></td>
            <td align="center" class='Browse'>
            	<input type="text" 
															name="fecha<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
															id="fecha<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
															size="12" 
															readonly 
															value="<?=$bus_pagos_financieros["fecha_conciliado"]?>">
															
				<img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_pagos_financieros["idpagos_financieros"]?>" width="16" height="16" 
													id="f_trigger_c<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
													style="cursor: pointer;" 
													title="Selector de Fecha" 
													onMouseOver="this.style.background='red';" 
													onMouseOut="this.style.background=''" onClick="
									Calendar.setup({
									inputField    : 'fecha<?=$bus_pagos_financieros["idpagos_financieros"]?>',
									button        : 'f_trigger_c<?=$bus_pagos_financieros["idpagos_financieros"]?>',
									align         : 'Tr',
									ifFormat      : '%Y-%m-%d'
									});"/>
                <? if ($bus_pagos_financieros["estado"]=='conciliado'){ ?>
                 	<img src="imagenes/refrescar.png" style="cursor: pointer;" onclick="actualizar_fecha('<?=$bus_pagos_financieros["idpagos_financieros"]?>')" />
                <? } ?>
            
            </td>
            <td align="center" class='Browse'>
            <?
            if($bus_pagos_financieros["estado"] == "transito"){
			?>
            <input type="button" 
            		name="botonEliminar<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
                    id="botonEliminar<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
                    onClick="conciliarPago('<?=$bus_pagos_financieros["idpagos_financieros"]?>', 
                    							'botonEliminar<?=$bus_pagos_financieros["idpagos_financieros"]?>')" 
                    value="Conciliar" 
                    class="button">
             <?
             }else{
			?>
			<input type="button" 
				name="botonDevolver<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
				id="botonDevolver<?=$bus_pagos_financieros["idpagos_financieros"]?>" 
				onClick="devolverConciliacion('<?=$bus_pagos_financieros["idpagos_financieros"]?>', 
											'botonDevolver<?=$bus_pagos_financieros["idpagos_financieros"]?>')" 
				value="Devolver" 
				class="button">
			<?
			 }
			 ?>
            </td>
      </tr>
        <?
    }
    
    ?>
    </table>
<?
}








if($ejecutar == "cargarCuentasBancarias"){
	$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$banco."'");
	?>
	<select name="cuenta" id="cuenta">
    	<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
		
			$sql_consultar_chequera = mysql_query("select * from cheques_cuentas_bancarias where idcuentas_bancarias = '".$bus_consultar_cuentas["idcuentas_bancarias"]."' and estado = 'Activa' order by chequera_numero")or die(mysql_error());
			$bus_consultar_chequera = mysql_fetch_array($sql_consultar_chequera);

			$numero_cheque = $bus_consultar_chequera["ultimo_cheque"];
			
			if($numero_cheque < 10){
				$numero_cheque = "0".$numero_cheque;
			}
			
			?>
			<option value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
			<?
		}
		?>
    </select>  
	<?
}










?>