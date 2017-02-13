<script src="modulos/tributos/js/ajustar_comprobante_ajax.js"></script>
    <br>
	<h4 align=center>Retenciones Realizadas</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <br />
    <form method="post" action="">
    <?
        $mes=date("m");
        $dia=date("d");
        $sql="SELECT anio_fiscal FROM configuracion";
        $query_conf=mysql_query($sql) or die ($sql.mysql_error());
        $conf=mysql_fetch_array($query_conf);
		$anio=$conf["anio_fiscal"];
		include "../../../funciones/funciones.php";
        ?>
    <table align="center" width="37%">
		<tr>
        	<td width="37%" align="right" class='viewPropTitle'>Tipo de Retenci&oacute;n</td>
            <td width="21%">
            <select name="idtipo" id="idtipo">
                    <option value="0"></option>
                    <?php
                        $sql="SELECT nombre_comprobante, descripcion FROM tipo_retencion GROUP BY nombre_comprobante ORDER BY descripcion";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
                    ?>
                </select>
             </td>
        </tr>
        <tr>
                <td align="right" class='viewPropTitle'>Per&iacute;odo: </td>
                <td>
                    <select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
                    </select> - 
                    <select name="mes" id="mes">
                        <?
                        for ($i=1; $i<=12; $i++) {
                            if ($i<10) $m="0$i"; else $m=$i;
                            if ($m==$mes) echo "<option value='$m' selected>$m</option>";
                            else  echo "<option value='$m'>$m</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="button"></td>
        </tr>
    </table>
    </form>
<?

if($_POST){
	$mes = $_POST["mes"];
	$dias_mes['01']=31; $dias_mes['03']=31; $dias_mes['04']=30; $dias_mes['05']=31; $dias_mes['06']=30;
	$dias_mes['07']=31; $dias_mes['08']=31; $dias_mes['09']=30; $dias_mes['10']=31; $dias_mes['11']=30; $dias_mes['12']=31;
	if ($anio%4==0) $dias_mes['02']=29; else $dias_mes['02']=28; 
	$dias=$dias_mes[$mes];

	$desde=$anio."-".$mes."-01";
	$hasta=$anio."-".$mes."-".$dias;
	
	/*$sql_consultar = mysql_query("select relacion_retenciones.idretenciones,
								 		 relacion_retenciones.numero_retencion,
										 retenciones.fecha_aplicacion_retencion,
										 relacion_retenciones.monto_retenido,
										 tipo_retencion.idtipo_retencion
										 from relacion_retenciones,tipo_retencion,retenciones
										where relacion_retenciones.idtipo_retencion = tipo_retencion.idtipo_retencion
										and tipo_retencion.nombre_comprobante = '".$_POST["idtipo"]."'
										and relacion_retenciones.numero_retencion <> 0
										and retenciones.idretenciones = relacion_retenciones.idretenciones
										and retenciones.fecha_aplicacion_retencion>='".$desde."' AND retenciones.fecha_aplicacion_retencion<='".$hasta."'
										group by relacion_retenciones.numero_retencion
										order by relacion_retenciones.numero_retencion 
									
									");*/
	$idtipo = $_POST["idtipo"];
	$sql_consulta="(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion,
					r.idretenciones, 
					r.numero_documento, 
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					rl.periodo,
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					rl.periodo,
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					ror.idorden_pago,
					'relacion_retenciones' AS tabla,
					rl.idrelacion_retenciones as idrelacion_retenciones,
					rl.codigo_concepto as codigo_concepto,
					'0' as numero_orden
				FROM 
					retenciones r 
					INNER JOIN relacion_retenciones rl ON (r.idretenciones=rl.idretenciones AND rl.generar_comprobante = 'si') 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_orden_pago_retencion ror ON (r.idretenciones = ror.idretencion)
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					AND rl.numero_retencion <> 0
					GROUP BY idorden_pago)
					
				UNION 
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion, 
					r.idretenciones,
					r.numero_documento,
					rl.numero_factura,
					rl.numero_control,
					rl.fecha_factura,
					rl.total, 
					rl.exento, 
					rl.sub_total as base, 
					rl.impuesto, 
					rl.periodo, 
					rl.idtipo_retencion, 
					rl.numero_retencion, 
					rl.monto_retenido, 
					rl.alicuota as porcentaje_impuesto, 
					rl.periodo,  
					r.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					r.estado,
					'0' AS idorden_pago,
					'relacion_retenciones_externas' AS tabla,
					rl.idrelacion_retenciones_externas as idrelacion_retenciones,
					rl.codigo_islr as codigo_concepto,
					rl.numero_orden as numero_orden
				FROM 
					retenciones r 
					INNER JOIN beneficiarios b ON (r.idbeneficiarios=b.idbeneficiarios) 
					INNER JOIN relacion_retenciones_externas rl ON (r.idretenciones=rl.idretencion) 
					INNER JOIN tipo_retencion tr ON (rl.idtipo_retencion=tr.idtipo_retencion)
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (r.fecha_aplicacion_retencion>='".$desde."' AND r.fecha_aplicacion_retencion<='".$hasta."')
					AND rl.numero_retencion <> 0
					)
					
				UNION
				
				(SELECT 
					r.fecha_aplicacion_retencion as fecha_retencion,
					r.idretenciones, 
					r.numero_documento,
					r.numero_factura,
					r.numero_control,
					r.fecha_factura,
					r.total, 
					r.exento, 
					r.base, 
					r.impuesto, 
					cr.periodo, 
					cr.idtipo_retencion, 
					cr.numero_retencion,  
					rl.monto_retenido, 
					rl.porcentaje_impuesto, 
					cr.periodo, 
					o.idbeneficiarios, 
					b.nombre, 
					b.rif, 
					tr.nombre_comprobante,
					cr.estado,
					ror.idorden_pago,
					'relacion_retenciones' AS tabla,
					rl.idrelacion_retenciones as idrelacion_retenciones,
					rl.codigo_concepto as codigo_concepto,
					'0' as numero_orden
				FROM 
					retenciones r 
					INNER JOIN comprobantes_retenciones cr ON (r.idretenciones=cr.idretenciones AND cr.estado <> 'procesado') 
					INNER JOIN tipo_retencion tr ON (cr.idtipo_retencion=tr.idtipo_retencion) 
					INNER JOIN relacion_orden_pago_retencion ror ON (r.idretenciones = ror.idretencion)
					INNER JOIN relacion_retenciones rl ON (cr.idretenciones = rl.idretenciones AND cr.idtipo_retencion = rl.idtipo_retencion AND rl.generar_comprobante = 'si')
					INNER JOIN orden_compra_servicio o ON (r.numero_documento=o.numero_orden) 
					INNER JOIN beneficiarios b ON (o.idbeneficiarios=b.idbeneficiarios) 
				WHERE 
					(tr.nombre_comprobante='".$idtipo."') 
					AND (cr.fecha_retencion>='".$desde."' AND cr.fecha_retencion<='".$hasta."')
					AND cr.numero_retencion <> 0
					GROUP BY idorden_pago)
				ORDER BY numero_retencion, numero_factura";

//echo $sql_consulta;
$sql_consultar = mysql_query($sql_consulta);
?>

<div id="divImprimir1" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; top:50px;">
    <table align="center">
        <tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir1').style.display='none';">X</a></td></tr>
        <tr><td><iframe name="ventanaPDF1" id="ventanaPDF1" style="display:none" height="600" width="750"></iframe></td></tr>
    </table>
</div>


<table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
  <thead>
	<tr>
      <td width="8%" class="Browse">Comprobante</td>
      <td width="12%" align="center" class="Browse">Fecha de Retenci&oacute;n</td>
      <td width="7%" align="center" class="Browse">Nro. Factura</td>
      <td width="7%" align="center" class="Browse">Nro. Control</td>
      <td width="12%" align="center" class="Browse">Fecha Factura</td>
      <td width="8%" align="center" class="Browse">C&oacute;digo</td>
      <td width="12%" align="center" class="Browse">Orden de Pago</td>
      <td width="32%" class="Browse">Beneficiario</td>
      <td width="10%" class="Browse">Monto Retenci&oacute;n</td>
        <td width="6%" class="Browse">Acci&oacute;n</td>
     </tr>
     </thead>
     <?
	 $numero_retencion_anterior = 0;
     while($bus_consultar = mysql_fetch_array($sql_consultar)){
		 $idorden_pago = $bus_consultar["idorden_pago"];
		
			if ($idorden_pago <> '0'){
				$sql_op=mysql_query("select orden_pago.numero_orden
											from orden_pago
													where idorden_pago = '".$idorden_pago."'")or die(mysql_error());
				$bus_op = mysql_fetch_array($sql_op)or die(mysql_error());
				$numero_orden = $bus_op["numero_orden"];
			 }else{
				 
				$numero_orden = $bus_consultar["numero_orden"]; 
			 }
		?>
			<input type="hidden" id="idrelacion_retenciones<?=$bus_consultar["idrelacion_retenciones"]?>" name="idrelacion_retenciones<?=$bus_consultar["idrelacion_retenciones"]?>" value="<?=$bus_consultar["idrelacion_retenciones"]?>">
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
				<td class="Browse">
				<input align="right" style="text-align:center; font-size:14px" name="numero_comprobante<?=$bus_consultar["idretenciones"]?>" 
																type="text" id="numero_comprobante<?=$bus_consultar["idretenciones"]?>" 
																size="10" onclick="this.select()"
																value="<?=$bus_consultar["numero_retencion"]?>">
				
				</td>
				<? //<td align="center" class="Browse">&nbsp;<?=$bus_consultar["fecha_aplicacion_retencion"]?>
				<td align="center" class='Browse'><input type="text" 
															name="fecha<?=$bus_consultar["idretenciones"]?>" 
															id="fecha<?=$bus_consultar["idretenciones"]?>" 
															size="12" 
															readonly 
															value="<?=$bus_consultar["fecha_retencion"]?>">
															
				<img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_consultar["idretenciones"]?>" width="16" height="16" 
													id="f_trigger_c<?=$bus_consultar["idretenciones"]?>" 
													style="cursor: pointer;" 
													title="Selector de Fecha" 
													onMouseOver="this.style.background='red';" 
													onMouseOut="this.style.background=''" onClick="
									Calendar.setup({
									inputField    : 'fecha<?=$bus_consultar["idretenciones"]?>',
									button        : 'f_trigger_c<?=$bus_consultar["idretenciones"]?>',
									align         : 'Tr',
									ifFormat      : '%Y-%m-%d'
									});"/>
				</td>
				<td class="Browse">
				<input align="right" style="text-align:center; font-size:14px" 
                												name="nro_factura<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																type="text" 
                                                                id="nro_factura<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																size="10" onclick="this.select()"
																value="<?=$bus_consultar["numero_factura"]?>">
				
				</td>
				<td class="Browse">
				<input align="right" style="text-align:center; font-size:14px" 
                												name="nro_control<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																type="text" 
                                                                id="nro_control<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																size="10" onclick="this.select()"
																value="<?=$bus_consultar["numero_control"]?>">
				
				</td>
				<td align="center" class='Browse'><input type="text" 
															name="fecha_factura<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
															id="fecha_factura<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
															size="12" 
															readonly 
															value="<?=$bus_consultar["fecha_factura"]?>">
															
				<img src="imagenes/jscalendar0.gif" name="f_trigger_f<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
                									width="16" height="16" 
													id="f_trigger_f<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
													style="cursor: pointer;" 
													title="Selector de Fecha" 
													onMouseOver="this.style.background='red';" 
													onMouseOut="this.style.background=''" onClick="
									Calendar.setup({
									inputField    : 'fecha_factura<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>',
									button        : 'f_trigger_f<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>',
									align         : 'Tr',
									ifFormat      : '%Y-%m-%d'
									});"/>
				</td>
                <td class="Browse">
				<input align="right" style="text-align:center; font-size:14px" 
                												name="codigo<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																type="text" id="codigo<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																size="8" onclick="this.select()"
																value="<?=$bus_consultar["codigo_concepto"]?>">
				
				</td>
				<td class="Browse">&nbsp;
					<? if ($idorden_pago <> '0'){
							echo $numero_orden;
						}else{ ?>
							<input align="right" style="text-align:center; font-size:14px" 
                												name="numero_orden<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																type="text" 
                                                                id="numero_orden<?=$bus_consultar["idretenciones"]?><?=$bus_consultar["idrelacion_retenciones"]?>" 
																size="14" onclick="this.select()"
																value="<?=$bus_consultar["numero_orden"]?>">
						<? }
					?>
                </td>
                <? if ($idorden_pago <> '0'){ ?>
					<td class="Browse">&nbsp;<?=utf8_decode($bus_consultar["nombre"])?></td>
                <? }else{ ?>
                	<td class="Browse">&nbsp;<?=utf8_decode($bus_consultar["nombre"]).' (EXTERNA)'?></td>
                <? } ?>
				<td class="Browse" align="right">&nbsp;<?=number_format($bus_consultar["monto_retenido"],2,",",".")?></td>
				<td class="Browse" align="center">
				
				<a href="#" onclick="">
				<button name='cambiar_numero_comprobante' type='button' style='background-color:#e7dfce;border-style:none;cursor:pointer;' onclick="cambiar_numero_comprobante('<?=$bus_consultar["idretenciones"]?>','<?=$bus_consultar["idtipo_retencion"]?>','<?=$idorden_pago?>','<?=$bus_consultar["idrelacion_retenciones"]?>', '<?=$numero_orden?>')">
					<img src="imagenes/refrescar.png" style="cursor:pointer" title="Actualizar Numero del Comprobante"></button>
				</a>
				</td>
			</tr>  	
		<?
		// }
	 }
}
	 ?>
</table>
        