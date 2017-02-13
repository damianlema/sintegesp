<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);



function diasEntreFechas($date1, $date2){
					$s = strtotime($date1)-strtotime($date2);
					$d = intval($s/86400);
					$s -= $d*86400;
					$h = intval($s/3600);
					$s -= $h*3600;
					$m = intval($s/60);
					$s -= $m*60;
					
					$dif= (($d*24)+$h).hrs." ".$m."min";
					$dif2= $d;
					return $dif2;
				}

function mesesEntreFechas($fecha_vencimiento){
	$date = date("Y-m-d");
	$activationdate = date("Y-m-d", strtotime ($fecha_vencimiento));
	$years= date("Y", strtotime("now")) - date("Y", strtotime($activationdate));
	// 
	
	if (date ("Y", strtotime($date)) == date ("Y", strtotime($activationdate))){
	$months = date ("m", strtotime($date)) - date ("m", strtotime($activationdate));
	}
	elseif ($years == "1"){
	$months = (date ("m", strtotime("December")) - date ("m", strtotime($activationdate))) + (date ("m"));
	}
	elseif($years >= "2"){
	$months = ($years*12) + (date ("m", strtotime("now")) - date ("m", strtotime($activationdate)));
	}
	return $months;
}


switch($ejecutar){
case "consultarContribuyente":
		$sql_consulta = mysql_query("select * from contribuyente where idcontribuyente ='".$idcontribuyente."'")or die(mysql_error());
		$bus_consulta = mysql_fetch_array($sql_consulta);
		
		echo "<strong>Nombre o Razon Social:</strong> ".$bus_consulta["razon_social"]."&nbsp;
			<strong>RIF:</strong> ".$bus_consulta["rif"];
		
		
	break;
	
	case "consultarDeudas":
		$sql_pagos_recaudacion = mysql_query("select * from pagos_recaudacion where idpagos_recaudacion = '".$idpagos_recaudacion."'");
		$bus_pagos_recaudacion = mysql_fetch_array($sql_pagos_recaudacion);
		
		if($bus_pagos_recaudacion["estado"] == "elaboracion"){
		?>
        <form name="formulario_deudas" id="formulario_deudas" action="">
		<table width="100%" align="center" cellpadding="4" cellspacing="4">
		<?
		$sql_consultar_solicitudes = mysql_query("select * from 
															solicitud_calculo 
																where 
															idcontribuyente = '".$idcontribuyente."'
															and estado = 'procesado'");
		$i=1;
		while($bus_consultar_solicitudes = mysql_fetch_array($sql_consultar_solicitudes)){
			$sql_tipo_solicitud = mysql_query("select * from 
														tipo_solicitud 
														where 
														idtipo_solicitud = '".$bus_consultar_solicitudes["idtipo_solicitud"]."'");
			$bus_tipo_solicitud = mysql_fetch_array($sql_tipo_solicitud);
			
			
			$sql_conceptos_solicitud_calculo = mysql_query("select
									 csc.idconcepto,
									 rfv.fecha_vencimiento,
									 rfv.idrango_fecha_vencimiento_conceptos as idfraccion,
									 csc.idconceptos_solicitud_calculo as idcon_sol,
									 csc.total_pagar as monto_total_pagar,
									 (csc.total_pagar / (select count(*)
													from
												rango_fecha_vencimiento_conceptos rr
													where
												rr.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo)) as fraccion_pagar
										from
									conceptos_solicitud_calculo csc,
									rango_fecha_vencimiento_conceptos rfv
										where
									csc.idsolicitud_calculo = '".$bus_consultar_solicitudes["idsolicitud_calculo"]."'
									and rfv.idconcepto_solicitud_calculo =  csc.idconceptos_solicitud_calculo
									and rfv.estado = 'pendiente'
									order by rfv.fecha_vencimiento");
			$num_conceptos_solicitud_calculo = mysql_num_rows($sql_conceptos_solicitud_calculo);
			if($num_conceptos_solicitud_calculo > 0){
				?>
				<tr>
				<td><br><strong><?=$bus_consultar_solicitudes["numero_solicitud"]?> | <?=$bus_tipo_solicitud["descripcion"]?></strong></td>
				</tr>
                	<tr>
                    <td style="border-bottom:#999999 solid 1px">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr style="background-color:#CCCCCC">
                        <td width="27%" align="center"><strong>Concepto</strong></td>
                        <td width="22%" align="center"><strong>Cuota a Pagar</strong></td>
                        <td width="51%" align="center"><strong>Fecha Tope</strong></td>
                        <td width="1%" align="center"><strong>Sel.</strong></td>
                        <tr>
				<?
			}
			while($bus_conceptos_solicitud_calculo = mysql_fetch_array($sql_conceptos_solicitud_calculo)){
				
				
				
				//  CALCULO DE LA MORA PARA CADA CONCEPTO
				
				
				
				$sql_configuracion_recaudacion = mysql_query("select * from configuracion_recaudacion");
			$bus_configuracion_recaudacion = mysql_fetch_array($sql_configuracion_recaudacion);
				
				
				
				$sql_mora = mysql_query("select * from moras_conceptos_tributarios where idconcepto_tributario = '".$bus_conceptos_solicitud_calculo["idconcepto"]."'");
				while($bus_mora = mysql_fetch_array($sql_mora)){
				
					if($bus_mora["sobre"] == "fraccion_pago"){
						$monto_base = $bus_conceptos_solicitud_calculo["fraccion_pagar"];
					}else{
						$monto_base = $bus_conceptos_solicitud_calculo["monto_total_pagar"];
					}
					
					for($i=0;$i<$bus_mora["condicion_valor"];$i++){
						$fecha_inicio = date("Y-m-d");
						if($bus_mora["condicion_tipo"] == "diaria"){
						
							$resultado_fechas = diasEntreFechas($fecha_inicio,$bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
							if($resultado_fechas > $i){
								//$fecha_inicio = date("Y-m-d", strtotime($fecha_inicio." + 1 days"));
								if($bus_mora["tipo_mora"] == "fijo"){
									if($bus_mora["tipo_valor_mora"] == "bolivares"){
										$total_mora += $bus_mora["valor_calculo"];
									}else{
										$total_mora += ($bus_mora["valor_calculo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"]);
									}
									
								}else{
									$total_mora += (($monto_base*$bus_mora["valor_calculo"])/100);								
								}
							}
							
						}
						
						
						if($bus_mora["condicion_tipo"] == "quincenal"){
							
						}	
						
						if($bus_mora["condicion_tipo"] == "mensual"){
							$resultado_fechas = mesesEntreFechas($bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
							if($resultado_fechas > $i){
								if($bus_mora["tipo_mora"] == "fijo"){
									if($bus_mora["tipo_valor_mora"] == "bolivares"){
										$total_mora += $bus_mora["valor_calculo"];
									}else{
										$total_mora += ($bus_mora["valor_calculo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"]);
									}
								}else{
									$total_mora += (($monto_base*$bus_mora["valor_calculo"])/100);
									
								}
							}
						}
						
						
						if($bus_mora["condicion_tipo"] == "trimestral"){
							$resultado_fechas = mesesEntreFechas($bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
							$resultado_fechas = floor(($resultado_fechas/3));
							if($resultado_fechas > $i){
								if($bus_mora["tipo_mora"] == "fijo"){
									if($bus_mora["tipo_valor_mora"] == "bolivares"){
										$total_mora += $bus_mora["valor_calculo"];
									}else{
										$total_mora += ($bus_mora["valor_calculo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"]);
									}
								}else{
									$total_mora += (($monto_base*$bus_mora["valor_calculo"])/100);
									
								}
							}
						}
						
						
						if($bus_mora["condicion_tipo"] == "semestral"){
							$resultado_fechas = mesesEntreFechas($bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
							$resultado_fechas = floor(($resultado_fechas/6));
							if($resultado_fechas > $i){
								if($bus_mora["tipo_mora"] == "fijo"){
									if($bus_mora["tipo_valor_mora"] == "bolivares"){
										$total_mora += $bus_mora["valor_calculo"];
									}else{
										$total_mora += ($bus_mora["valor_calculo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"]);
									}
								}else{
									$total_mora += (($monto_base*$bus_mora["valor_calculo"])/100);
									
								}
							}
						}
						
						
						if($bus_mora["condicion_tipo"] == "semestral"){
							$resultado_fechas = mesesEntreFechas($bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
							$resultado_fechas = floor(($resultado_fechas/12));
							if($resultado_fechas > $i){
								if($bus_mora["tipo_mora"] == "fijo"){
									if($bus_mora["tipo_valor_mora"] == "bolivares"){
										$total_mora += $bus_mora["valor_calculo"];
									}else{
										$total_mora += ($bus_mora["valor_calculo"]*$bus_configuracion_recaudacion["costo_unidad_tributaria"]);
									}
								}else{
									$total_mora += (($monto_base*$bus_mora["valor_calculo"])/100);
									
								}
							}
						}
						
					}
					
					
				}
				
				
				
				//  CALCULO DE LA MORA PARA CADA CONCEPTO
				
				
				
				$sql_concepto_tributario = mysql_query("select * from concepto_tributario where idconcepto_tributario = '".$bus_conceptos_solicitud_calculo["idconcepto"]."'");
				$bus_concepto_tributario = mysql_fetch_array($sql_concepto_tributario);
					
					if($bus_conceptos_solicitud_calculo["fecha_vencimiento"] != $fecha){
						
						if($i != 1){
							?>
							<tr style="background-color:#FFFFCC; font-weight:bold;">
                            <td style="border-bottom:#FFFFFF solid 5px"><input type="hidden" value="<?=$conceptos?>" id="conceptos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td align="right" style="color:#000; border-bottom:#FFFFFF solid 5px"><?=number_format($suma_pago,2,",",".")?></td>
                            <td style="border-bottom:#FFFFFF solid 5px"><input type="hidden" value="<?=$montos_fraccion?>" id="montos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td style="border-bottom:#FFFFFF solid 5px"><input type="hidden" value="<?=$moras?>" id="mora_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            </tr>
							<?
							$suma_pago=0;
							$conceptos="";
							$montos_fraccion = "";
							$total_mora = "";
						}
						?>
						<tr style="background-color:#666666; font-weight:bold">
                        <td style="color:#FFFFFF;" colspan="2">PAGO <?=$i?></td>
                        <td></td>
                        <td><input type="checkbox" name="sel" id="sel" onClick="seleccionarPago('conceptos_<?=$i?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>', 'montos_<?=$i?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>', 'mora_<?=$i?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>')"></td>
                        </tr>
						<?
						$fecha = $bus_conceptos_solicitud_calculo["fecha_vencimiento"];
						$i++;
						$suma_pago += $bus_conceptos_solicitud_calculo["fraccion_pagar"];
						
					}else{
						$suma_pago += $bus_conceptos_solicitud_calculo["fraccion_pagar"];
					}
					
					
					
					?>
                    <tr>
                    <td>(<?=$bus_concepto_tributario["codigo"]?>) <?=$bus_concepto_tributario["denominacion"]?></td>
                    <td align="right"><?=number_format($bus_conceptos_solicitud_calculo["fraccion_pagar"],2,",",".")?></td>
                    <td>&nbsp;&nbsp;&nbsp;
					<?
                    if($bus_conceptos_solicitud_calculo["fecha_vencimiento"] == date("Y-m-d")){
						echo "<strong>Este Rubro debe ser cancelado el dia de hoy</strong>";
					}else{
						$partes = explode("-",$bus_conceptos_solicitud_calculo["fecha_vencimiento"]);
						echo "Debe Cancelarse antes del ".$partes[2]." de ".meses($partes[1])." de ".$partes[0];
					}
					?></td>
                    <td></td>
                    </tr>
                     <?
                     if($total_mora != 0){
					 ?>
                        <tr>
                            <td style="color:#990000; text-align:right; font-weight:bold">Total Mora Acumulada:&nbsp;</td>
                            <td style="color:#990000; text-align:right; font-weight:bold"><?=number_format($total_mora,2,",",".")?></td>
                            <td></td>
                            <td></td>
                        </tr>
					<?
					}
				$conceptos .= $bus_conceptos_solicitud_calculo["idfraccion"].", ";
				$montos_fraccion .= round($bus_conceptos_solicitud_calculo["fraccion_pagar"],2).", "; 
				$moras .= $total_mora.", ";
				$suma_pago += $total_mora;
				$total_mora = "";
			}
			?>
            
							<tr style="background-color:#FFFFCC; font-weight:bold; border-bottom:#FFFFFF solid 1px">
                            <td><input type="hidden" value="<?=$conceptos?>" id="conceptos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td align="right" style="color:#000"><?=number_format($suma_pago,2,",",".")?></td>
                            <td><input type="hidden" value="<?=$montos_fraccion?>" id="montos_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            <td><input type="hidden" value="<?=$moras?>" id="mora_<?=($i-1)?>_<?=$bus_consultar_solicitudes["idsolicitud_calculo"]?>"></td>
                            </tr>
							
            	</table>
                <br>
				
            </td>
			</tr>
			<?
			$fecha = "";
			$i=1;
			$suma_pago = 0;
			$conceptos = "";
			$montos_fraccion = "";
			$total_mora = "";
		}
		?>
		</table>
        </form>
		<?
		}else{
			?>
			<table align="center" width="100%">
            <tr style="background-color:#CCCCCC">
            	<td align="center"><strong>Nro. Solicitud</strong></td>
                <td align="center"><strong>Concepto</strong></td>
                <td align="center"><strong>Fecha de Vencimiento</strong></td>
                <td align="center"><strong>Total Monto</strong></td>
                <td align="center"><strong>Total Mora</strong></td>
                <td align="center"><strong>Estado</strong></td>
            </tr>
			<?
			$sql_fracciones = mysql_query("select fcc.monto_cancelado,
												  fcc.monto_mora,
												  rfv.fecha_vencimiento,
												  c.codigo,
												  c.denominacion,
												  sc.numero_solicitud
													from 
												fracciones_conceptos_canceladas fcc,
												rango_fecha_vencimiento_conceptos rfv,
												conceptos_solicitud_calculo csc,
												concepto_tributario c,
												solicitud_calculo sc
													where 
												fcc.idpagos_recaudacion = '".$idpagos_recaudacion."'
												and rfv.idrango_fecha_vencimiento_conceptos = fcc.idrango_fecha_vencimiento_conceptos
												and rfv.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo
												and csc.idconcepto = c.idconcepto_tributario
												and csc.idsolicitud_calculo = sc.idsolicitud_calculo
												order by sc.idsolicitud_calculo")or die(mysql_error());
			while($bus_fracciones = mysql_fetch_array($sql_fracciones)){
				?>
				<tr>
                    <td><?=$bus_fracciones["numero_solicitud"]?></td>
                    <td>(<?=$bus_fracciones["codigo"]?>)&nbsp;<?=$bus_fracciones["denominacion"]?></td>
                    <td align="center"><?=$bus_fracciones["fecha_vencimiento"]?></td>
                    <td align="right"><?=number_format($bus_fracciones["monto_cancelado"],2,",",".")?></td>
                    <td align="right"><?=number_format($bus_fracciones["monto_mora"],2,",",".")?></td>
                    <td align="center"><strong>Pagado</strong></td>
                </tr>
                
				<?
				$suma_total += $bus_fracciones["monto_cancelado"];
				$suma_mora += $bus_fracciones["monto_mora"];
			}
			?>
            <tr style="background-color:#FFFFCC; font-weight:bold;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right"><?=number_format($suma_total,2,",",".")?></td>
                <td align="right"><?=number_format($suma_mora,2,",",".")?></td>
                <td>&nbsp;</td>
                
			<table>
			<?
		}
	
	break;
	
	
	
	case "seleccionarPago":
	//echo "AQUI ".$conceptos;
	$partes_conceptos = explode(",",$conceptos);
	$partes_montos = explode(",",$montos);
	$partes_moras = explode(",",$moras);
	
	$i=0;
	foreach($partes_conceptos as $con){
		$sql_consultar = mysql_query("select * from fracciones_conceptos_canceladas 
												where 
												idrango_fecha_vencimiento_conceptos = '".$con."'
												and idpagos_recaudacion = '".$idpagos_recaudacion."'");
		$num_consultar = mysql_num_rows($sql_consultar);
		if($num_consultar == 0){
			if($con != " "){
				$sql_ingresar = mysql_query("insert into fracciones_conceptos_canceladas(idrango_fecha_vencimiento_conceptos,
																					monto_cancelado,
																					idpagos_recaudacion,
																					monto_mora)
																					VALUES('".$con."',
																							'".$partes_montos[$i]."',
																							'".$idpagos_recaudacion."',
																							'".$partes_moras[$i]."')");	
				$i++;
			}
		}else{
			$sql_eliminar = mysql_query("delete from 
												fracciones_conceptos_canceladas 
												where 
												idrango_fecha_vencimiento_conceptos = '".$con."'
												and idpagos_recaudacion = '".$idpagos_recaudacion."'");
			$i++;	
		}
	}
	
	
	
	break;
	
	
	
	
	case "ingresarDatosBasicos":
		$sql_insertar = mysql_query("insert into pagos_recaudacion(idcontribuyente, estado)VALUES('".$idcontribuyente."', 'elaboracion')");
		echo mysql_insert_id();
	break;
	
	
	
	case "sumarTotalPagar":
		
		$sql_suma = mysql_query("select sum(monto_cancelado)+SUM(monto_mora) as suma_total,
										sum(monto_cancelado) as total,
										SUM(monto_mora) as total_mora
										 	from 
										 fracciones_conceptos_canceladas 
										 	where 
										 idpagos_recaudacion = '".$idpagos_recaudacion."'");
		$bus_suma = mysql_fetch_array($sql_suma);
		echo number_format($bus_suma["suma_total"],2,",",".")."|.|".$bus_suma["total"]."|.|".$bus_suma["total_mora"];
		
	break;
	
	
	
	
	case "procesarPago":
		
		$sql_consultar_numero = mysql_query("select numero_recaudacion from configuracion_recaudacion");
		$bus_consultar_numero = mysql_fetch_array($sql_consultar_numero);
		
		$numero_planilla = "SC-".$_SESSION["anio_fiscal"]."-".$bus_consultar_numero["numero_recaudacion"];
		
		
	$sql_actualizar = mysql_query("update pagos_recaudacion set 
															numero_planilla = '".$numero_planilla."',
															fecha_pago = '".date("Y-m-d")."',
															total = '".$total."',
															descuento = '".$descuento."',
															mora = '".$total_mora."',
															total_cancelar = '".$total_pagar."',
															idbanco = '".$banco."',
															idcuenta = '".$nro_cuenta."',
															especie = '".$especie."',
															recibido_por = '".$recibido_por."',
															ci_recibido = '".$ci_recibido."',
															banco= '".$banco_pago."',
															nro_cuenta= '".$nro_cuenta_pago."',
															nro_documento = '".$nro_documento."',
															nro_cheque_tarjeta = '".$nro_cheque_tarjeta."',
															tipo_tarjeta = '".$tipo_tarejta."',
															fecha = '".$fecha."',
															estado = 'procesado'
															where
															idpagos_recaudacion = '".$idpagos_recaudacion."'")or die(mysql_error());
	
		$sql_conceptos = mysql_query("select * from fracciones_conceptos_canceladas");
		while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
			$sql_fraccion = mysql_query("update rango_fecha_vencimiento_conceptos 
												set estado = 'pagado' 
													where 
												idrango_fecha_vencimiento_conceptos = '".$bus_conceptos["idrango_fecha_vencimiento_conceptos"]."'");
		}
		
		
		
		$sql_actualizar_numero = mysql_query("update 
												configuracion_recaudacion 
													set 
												numero_recaudacion = numero_recaudacion + 1");
												
												
												
		$sql_consulta_contribuyente = mysql_query("select idcontribuyente from pagos_recaudacion where idpagos_recaudacion = '".$idpagos_recaudacion."'");
		$bus_consulta_contribuyente = mysql_fetch_array($sql_consulta_contribuyente);
		
		$sql_consulta = mysql_query("select * from solicitud_calculo where idcontribuyente = '".$bus_consulta_contribuyente["idcontribuyente"]."'");
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			$sql_fracciones = mysql_query("select * from 
												conceptos_solicitud_calculo csc,
												rango_fecha_vencimiento_conceptos rfv
													where
												csc.idsolicitud_calculo = '".$bus_consulta["idsolicitud_calculo"]."'
												and rfv.idconcepto_solicitud_calculo = csc.idconceptos_solicitud_calculo
												and rfv.estado = 'pendiente'")or die(mysql_error());
												
			$num_fracciones = mysql_num_rows($sql_fracciones);
			
			if($num_fracciones == 0){
				$sql_actualizar_solicitud = mysql_query("update solicitud_calculo set estado = 'pagado' where idsolicitud_calculo = '".$bus_consulta["idsolicitud_calculo"]."'");
			}
		
		}
												
		echo $numero_planilla;
	
	
	break;
	
	
	
	case "consultarRecaudacion":
		
		$sql_pagos_recaudacion = mysql_query("select * 
														from 
													pagos_recaudacion 
														where 
													idpagos_recaudacion = '".$idpagos_recaudacion."'");
		$bus_pagos_recaudacion = mysql_fetch_array($sql_pagos_recaudacion);
		
		echo $bus_pagos_recaudacion["idcontribuyente"]."|.|".
			$bus_pagos_recaudacion["numero_planilla"]."|.|".
			$bus_pagos_recaudacion["fecha_pago"]."|.|".
			$bus_pagos_recaudacion["total"]."|.|".
			$bus_pagos_recaudacion["descuento"]."|.|".
			$bus_pagos_recaudacion["mora"]."|.|".
			$bus_pagos_recaudacion["total_cancelar"]."|.|".
			$bus_pagos_recaudacion["estado"];
		
	break;
	
	
	
	case "anularRecaudacion":
		$sql_actualizar = mysql_query("update pagos_recaudacion set 
															estado = 'anulado'
															where
															idpagos_recaudacion = '".$idpagos_recaudacion."'")or die(mysql_error());
															
															
		$sql_conceptos = mysql_query("select * from fracciones_conceptos_canceladas where idpagos_recaudacion = '".$idpagos_recaudacion."'");
		while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
			$sql_fraccion = mysql_query("update rango_fecha_vencimiento_conceptos 
												set estado = 'pendiente' 
													where 
												idrango_fecha_vencimiento_conceptos = '".$bus_conceptos["idrango_fecha_vencimiento_conceptos"]."'");
		}
		
		
	break;
	
	
	
	case "consultarCuentaBancaria":
	
		$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias where idbanco = '".$idbanco."'");
	?>
	<select name="nro_cuenta" id="nro_cuenta">
    	<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
			?>
			<option value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
			<?
		}
		?>
    </select>  
	<?
	
	break;
	
}
?>