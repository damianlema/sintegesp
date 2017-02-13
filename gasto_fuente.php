<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<?php
	$link=mysql_connect("localhost","root","gestion2009");
	mysql_select_db("gestion_gobernacion_2012_final",$link);
	$sql_fuente = mysql_query("select * from fuente_financiamiento");
	?>
    <table cellpadding="0" cellspacing="0" width="95%">
		<tr>	
          <td width="30%" align="center" class="Browse">Fuente Financiamiento</td>
          <td width="10%" align="center" class="Browse">Nro. Ordenes</td>
          <td width="15%" align="center" class="Browse">Sub-Total</td>
          <td width="15%" align="center" class="Browse">Retenido</td>
          <td width="15%" align="center" class="Browse">Total Pagado</td>
          <td width="15%" align="center" class="Browse">Total Causado</td>
		</tr>
							
    <?
	$total_ordenes=0;
	while ($bus_fuente = mysql_fetch_array($sql_fuente)){
		$sub_total=0; $retenido=0; $total_pagado=0; $causado=0;
		//$suma_sub_total=0; $suma_retenido=0; $suma_total_pagado=0;
		?>
        <tr>
        	<td align="left"><?=$bus_fuente["denominacion"]?></td>
      
        <?
		$sql_ordenes_fuente = mysql_query("select * from orden_pago where orden_pago.idorden_pago <> ''
											AND (orden_pago.idfuente_financiamiento = '".$bus_fuente["idfuente_financiamiento"]."')
											AND (orden_pago.fecha_orden >= '2012-01-01'
													AND orden_pago.fecha_orden <= '2012-12-31')
											AND (orden_pago.estado = 'procesado'
												OR orden_pago.estado = 'pagada'
												OR orden_pago.estado = 'parcial'
												OR orden_pago.estado = 'pagada')
											");
		$numero_ordenes =mysql_num_rows($sql_ordenes_fuente);									
		while ($bus_ordenes_fuente = mysql_fetch_array($sql_ordenes_fuente)){
			
			$tipo_documento=mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_ordenes_fuente["tipo"]."'");
			
			$bus_tipo = mysql_fetch_array($tipo_documento);
			//sin afectacion
			if ($bus_tipo["compromete"] == 'no' and $bus_tipo["causa"] == 'no'){
				$sub_total += $bus_ordenes_fuente["exento"];
				
				$total_pagado += $bus_ordenes_fuente["total_a_pagar"];
				//$suma_total_pagado += $total_pagado;
			}
			
			//pago directo
			if ($bus_tipo["compromete"] == 'si' and $bus_tipo["causa"] == 'si'){
				$sub_total += $bus_ordenes_fuente["total"];
				//$suma_sub_total += $sub_total;
				$total_pagado += $bus_ordenes_fuente["total_a_pagar"];
				//$suma_total_pagado += $total_pagado;
			}
			
			//pago compromisos
			if ($bus_tipo["compromete"] == 'no' and $bus_tipo["causa"] == 'si'){
				$sql_tipo_compromiso = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_tipo["documento_compromete"]."'");
				$bus_tipo_compromiso = mysql_fetch_array($sql_tipo_compromiso);
				if ($bus_tipo_compromiso["multi_categoria"]=='si'){
					$sub_total += $bus_ordenes_fuente["sub_total"];
					//$suma_sub_total += $sub_total;
					$retenido += $bus_ordenes_fuente["exento"];
					//$suma_retenido += $retenido;
					$total_pagado += $bus_ordenes_fuente["total_a_pagar"];
					//$suma_total_pagado += $total_pagado;
				}else{
					$sub_total += ($bus_ordenes_fuente["sub_total"] + $bus_ordenes_fuente["exento"] + $bus_ordenes_fuente["impuesto"]);
					//$suma_sub_total += $sub_total;
					$retenido += $bus_ordenes_fuente["total_retenido"];
					//$suma_retenido += $retenido;
					$total_pagado += $bus_ordenes_fuente["total_a_pagar"];
					//$suma_total_pagado += $total_pagado;
				}
			}
			
			
			$sql_causado = mysql_query("select sum(monto) as causado from partidas_orden_pago where idorden_pago = '".$bus_ordenes_fuente["idorden_pago"]."'");
			$bus_causado = mysql_fetch_array($sql_causado);
			$causado += $bus_causado["causado"];

		}
		$total_ordenes = $total_ordenes + $numero_ordenes;
		?>
        	<td align="right"><?=$numero_ordenes?></td>
        	<td align="right"><?=number_format($sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($total_pagado, 2, ',', '.')?></td>
            <td align="right"><?=number_format($causado, 2, ',', '.')?></td>
  
       
        </tr>
        <?
		$suma_sub_total += $sub_total;
		$suma_retenido += $retenido;
		$suma_total_pagado += $total_pagado;
		$suma_causado += $causado;
	}
	?>
        	<tr>
        	<td align="left">TOTALES</td>
            <td align="right"><?=$total_ordenes?></td>
        	<td align="right"><?=number_format($suma_sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($suma_retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($suma_total_pagado, 2, ',', '.')?></td>
  			<td align="right"><?=number_format($suma_causado, 2, ',', '.')?></td>
       
        </tr>
        <?
	/*
	$sql_nro_ordenes = mysql_query("SELECT orden_pago.numero_orden, fuente_financiamiento.denominacion AS denominacion
											FROM orden_pago, fuente_financiamiento
											WHERE orden_pago.idorden_pago <> ''
											AND (orden_pago.idfuente_financiamiento = '".$bus_fuente["idfuente_financiamiento"]."')
											AND (orden_pago.fecha_orden >= '2012-01-01'
													AND orden_pago.fecha_orden <= '2012-12-31')
											AND (orden_pago.estado = 'procesado'
												OR orden_pago.estado = 'pagada'
											");
		if (mysql_num_rows($sql_nro_ordenes)>0){$numero_ordenes =mysql_num_rows($sql_nro_ordenes); }else{$numero_ordenes = 0;}
		$total_ordenes = $total_ordenes + $numero_ordenes;
		?>
        
        	<td align="right"><?=$numero_ordenes?></td>
  
        <?
		//filtro ordenes sin afectacion
		$sql_totales1 = mysql_query("SELECT sum(orden_pago.exento) as exento, sum(orden_pago.sub_total) as sub_total,
  										sum(orden_pago.total) as total, sum(orden_pago.total_retenido) as total_retenido,
										sum(orden_pago.total_a_pagar) as total_a_pagar

										FROM orden_pago

										WHERE orden_pago.idorden_pago<>'' AND (orden_pago.idfuente_financiamiento = '".$bus_fuente["idfuente_financiamiento"]."')
											AND (orden_pago.fecha_orden>='2012-01-01' AND orden_pago.fecha_orden<='2012-12-31')
										AND (orden_pago.estado = 'procesado'
											OR orden_pago.estado = 'pagada')
										AND orden_pago.sub_total = 0
										AND orden_pago.impuesto =0
										AND orden_pago.total_retenido =0
										AND orden_pago.exento >0
										");
		$bus_totales = mysql_fetch_array($sql_totales1);
		$sub_total = $bus_totales["exento"];
		$suma_sub_total += $sub_total;
		$total_pagado = $bus_totales["total_a_pagar"];
		$suma_total_pagado += $total_pagado;
		?>
        	</tr>
            <tr>
        	<td align="right"><?=number_format($sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($total_pagado, 2, ',', '.')?></td>
  
        <?
		$sql_totales2 = mysql_query("SELECT sum(orden_pago.exento) as exento, sum(orden_pago.sub_total) as sub_total,
  										sum(orden_pago.total) as total, sum(orden_pago.total_retenido) as total_retenido,
										sum(orden_pago.total_a_pagar) as total_a_pagar

										FROM orden_pago

										WHERE orden_pago.idorden_pago<>'' AND (orden_pago.idfuente_financiamiento = '".$bus_fuente["idfuente_financiamiento"]."')
											AND (orden_pago.fecha_orden>='2012-01-01' AND orden_pago.fecha_orden<='2012-12-31')
										AND (orden_pago.estado = 'procesado'
											OR orden_pago.estado = 'pagada')
										
										AND orden_pago.sub_total = 0
										AND orden_pago.impuesto =0
										AND orden_pago.total_retenido >0
										AND orden_pago.exento >0
										");
		$bus_totales = mysql_fetch_array($sql_totales2);
		$sub_total += $bus_totales["exento"];
		$suma_sub_total += $sub_total;
		$retenido += $bus_totales["total_retenido"];
		$suma_retenido += $retenido;
		$total_pagado += $bus_totales["total_a_pagar"];
		$suma_total_pagado += $total_pagado;
		
		?>
        	</tr>
            <tr>
        	<td align="right"><?=number_format($sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($total_pagado, 2, ',', '.')?></td>
  
        <?
		$sql_totales3 = mysql_query("SELECT sum(orden_pago.exento) as exento, sum(orden_pago.sub_total) as sub_total,
  										sum(orden_pago.total) as total, sum(orden_pago.total_retenido) as total_retenido,
										sum(orden_pago.total_a_pagar) as total_a_pagar

										FROM orden_pago

										WHERE orden_pago.idorden_pago<>'' AND (orden_pago.idfuente_financiamiento = '".$bus_fuente["idfuente_financiamiento"]."')
											AND (orden_pago.fecha_orden>='2012-01-01' AND orden_pago.fecha_orden<='2012-12-31')
										AND (orden_pago.estado = 'procesado'
											OR orden_pago.estado = 'pagada')
										AND orden_pago.sub_total > 0
										AND orden_pago.exento > 0
										AND orden_pago.total_retenido =0
										AND orden_pago.impuesto =0
										");
		$bus_totales = mysql_fetch_array($sql_totales3);
		$sub_total += $bus_totales["sub_total"];
		$suma_sub_total += $sub_total;
		$retenido += $bus_totales["exento"];
		$suma_retenido += $retenido;
		$total_pagado += $bus_totales["total_a_pagar"];
		$suma_total_pagado += $total_pagado;
		?>
        	</tr>
            <tr>
        	<td align="right"><?=number_format($sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($total_pagado, 2, ',', '.')?></td>
  
        <?
		$sql_totales4 = mysql_query("SELECT sum(orden_pago.exento) as exento, sum(orden_pago.sub_total) as sub_total,
  										sum(orden_pago.total) as total, sum(orden_pago.total_retenido) as total_retenido,
										sum(orden_pago.total_a_pagar) as total_a_pagar

										FROM orden_pago

										WHERE orden_pago.idorden_pago<>'' AND (orden_pago.idfuente_financiamiento = '".$bus_fuente["idfuente_financiamiento"]."')
											AND (orden_pago.fecha_orden>='2012-01-01' AND orden_pago.fecha_orden<='2012-12-31')
										AND (orden_pago.estado = 'procesado'
											OR orden_pago.estado = 'pagada')
										AND orden_pago.sub_total > 0
										AND orden_pago.exento > 0
										AND orden_pago.total_retenido >0
										AND orden_pago.impuesto >0
										");
		$bus_totales = mysql_fetch_array($sql_totales4);
		$sub_total += $bus_totales["sub_total"]+$bus_totales["exento"];
		$suma_sub_total += $sub_total;
		$retenido += $bus_totales["total_retenido"];
		$suma_retenido += $retenido;
		$total_pagado += $bus_totales["total_a_pagar"];
		$suma_total_pagado += $total_pagado;
		?>
        	</tr>
            <tr>
        	<td align="right"><?=number_format($sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($total_pagado, 2, ',', '.')?></td>
  
        <?
		?>
        	</tr>
            <tr>
        	<td align="right"><?=number_format($suma_sub_total, 2, ',', '.')?></td>
            <td align="right"><?=number_format($suma_retenido, 2, ',', '.')?></td>
            <td align="right"><?=number_format($suma_total_pagado, 2, ',', '.')?></td>
  
        <?
		?>
        </tr>
        <?
	*/
?>
</table>
<body>
</body>
</html>