<?
session_start();
include("../../../conf/conex.php");
conectarse();

extract($_POST);

if($ejecutar == "mostrarCompromisos"){
	$sql_orden_pago = mysql_query("select porcentaje_anticipo from orden_pago where idorden_pago = '".$idorden_pago."'");
	$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
	?>
	<? /*<table width="800" align="center" style="border:#000000 solid 1px" class="Browse">
    	<tr>
        <td width="167" class="Browse"><strong>Porcentaje de Anticipo?</strong></td>
        <td width="146"><input type="text" id="anticipo<?=$idorden_pago?>" name="anticipo<?=$idorden_pago?>" value="<?=$bus_orden_pago["porcentaje_anticipo"]?>"></td>
        <td width="471"><img src="imagenes/refrescar.png" style="cursor:pointer" onclick="guardarAnticipo('<?=$idorden_pago?>', document.getElementById('anticipo<?=$idorden_pago?>').value)" title="Guardar Porcentaje de Anticipo"></td>
      </tr> 
     </table> */ ?>
    <table width="800" class="Browse" cellpadding="0" cellspacing="0" align="center">
	  <thead>
        <tr>
        <?
        $sql_consulta = mysql_query("select * from orden_pago where idorden_pago = '".$idorden_pago."'");
		$bus_consulta = mysql_fetch_array($sql_consulta);
		?>
            <td colspan="6" class="Browse"><strong>Lista de Compromisos de la orden de pago Nro.: <?=$bus_consulta["numero_orden"]?></strong></td>
            <td width="71" align="right" class="Browse"><strong onClick="document.getElementById('divCompromisos<?=$idorden_pago?>').style.display = 'none'" style="cursor:pointer">X</strong></td>
      </tr>
      </thead>
       <thead>
        <tr>
          <td width="82" align="center" class="Browse">Nro. Orden</td>
          <td width="91" align="center" class="Browse">Fecha Orden</td>
          <td width="144" align="center" class="Browse">Nro. Factura</td>
          <td width="144" align="center" class="Browse">Nro. Control</td>
          <td width="144" align="center" class="Browse">Fecha Factura</td>
          <td width="92" align="center" class="Browse">Total Retenido</td>
          <td align="center">Actualizar</td>
        </tr>
        </thead>
        <?
        $sql_ordenes = mysql_query("select retenciones.numero_factura as numero_factura,
											retenciones.numero_control as numero_control,
											retenciones.fecha_factura as fecha_factura,
											orden_compra_servicio.numero_orden as numero_orden,
											orden_compra_servicio.fecha_orden as fecha_orden,
											retenciones.total_retenido as total,
											orden_compra_servicio.idorden_compra_servicio as idorden_compra_servicio,
											retenciones.idretenciones
										from 
											relacion_pago_compromisos, orden_compra_servicio, retenciones, relacion_orden_pago_retencion
										where
											orden_compra_servicio.idorden_compra_servicio = retenciones.iddocumento
											and relacion_pago_compromisos.idorden_pago = '".$idorden_pago."'
											and relacion_orden_pago_retencion.idorden_pago = '".$idorden_pago."'
											and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
										group by idretenciones")or die(mysql_error());
		while($bus_ordenes = mysql_fetch_array($sql_ordenes)){
			?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                	<td class='Browse'><?=$bus_ordenes["numero_orden"]?></td>
                    <td class='Browse'><?=$bus_ordenes["fecha_orden"]?></td>
                    <td align="center" class='Browse'><input type="text" id="numero_factura<?=$bus_ordenes["idorden_compra_servicio"]?>" value="<?=$bus_ordenes["numero_factura"]?>" size="10"></td>
                  <td align="center" class='Browse'><input type="text" id="numero_control<?=$bus_ordenes["idorden_compra_servicio"]?>" value="<?=$bus_ordenes["numero_control"]?>" size="10"></td>
                  <td align="center" class='Browse'>
                  	<input type="text" id="fecha_factura<?=$bus_ordenes["idorden_compra_servicio"]?>" value="<?=$bus_ordenes["fecha_factura"]?>" size="12" readonly>
                    <img src="imagenes/jscalendar0.gif" name="imagen_fecha_factura<?=$bus_ordenes["idorden_compra_servicio"]?>" width="16" height="16" id="imagen_fecha_factura<?=$bus_ordenes["idorden_compra_servicio"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="
							Calendar.setup({
							inputField    : 'fecha_factura<?=$bus_ordenes["idorden_compra_servicio"]?>',
							button        : 'imagen_fecha_factura<?=$bus_ordenes["idorden_compra_servicio"]?>',
							align         : 'Tr',
							ifFormat      : '%Y-%m-%d'
							});
						"/>
                    </td>
                    <td align="right" class='Browse'><?=number_format($bus_ordenes["total"],2,",",".")?></td>
                  <td class='Browse' align="center">
                  	<img src="imagenes/refrescar.png" style="cursor:pointer" onClick="actualizarDatosdeFactura(document.getElementById('numero_factura<?=$bus_ordenes["idorden_compra_servicio"]?>').value, document.getElementById('numero_control<?=$bus_ordenes["idorden_compra_servicio"]?>').value, document.getElementById('fecha_factura<?=$bus_ordenes["idorden_compra_servicio"]?>').value, '<?=$idorden_pago?>', '<?=$bus_ordenes["idorden_compra_servicio"]?>', 'divCompromisos<?=$idorden_pago?>', '<?=$bus_ordenes["idretenciones"]?>')">
                  </td>
                </tr>
			<?
		}
		?>
    </table>

    <br>
    <table width="800" class="Browse" cellpadding="0" cellspacing="0" align="center">
	  <thead>
        <tr>
            <td colspan="6" class="Browse"><strong>Lista de Retenciones Aplicadas al Compromiso</strong></td>
      	</tr>
      </thead>
      <thead>
        <tr>
          <td width="50" align="center" class="Browse">C&oacute;digo</td>
          <td width="144" align="center" class="Browse">Descripci&oacute;n</td>
          <td width="90" align="center" class="Browse">Retenido</td>
          <td width="90" align="center" class="Browse">Comprobante</td>
        </tr>
        </thead>
        <?
        $sql_ordenes = mysql_query("select relacion_retenciones.idtipo_retencion as idtipo_retencion,
											relacion_retenciones.monto_retenido as monto_retenido,
											relacion_retenciones.generar_comprobante as generar_comprobante,
											tipo_retencion.codigo as codigo,
											tipo_retencion.descripcion as descripcion,
											retenciones.idretenciones,
											relacion_retenciones.idrelacion_retenciones
										from 
											relacion_pago_compromisos, orden_compra_servicio, retenciones, relacion_orden_pago_retencion,
											relacion_retenciones, tipo_retencion
										where
											orden_compra_servicio.idorden_compra_servicio = retenciones.iddocumento
											and relacion_pago_compromisos.idorden_pago = '".$idorden_pago."'
											and relacion_orden_pago_retencion.idorden_pago = '".$idorden_pago."'
											and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
											and relacion_retenciones.idretenciones = retenciones.idretenciones
											and tipo_retencion.idtipo_retencion = relacion_retenciones.idtipo_retencion
										")or die(mysql_error());
		while($bus_ordenes = mysql_fetch_array($sql_ordenes)){
			?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                	<td class='Browse'><?=$bus_ordenes["codigo"]?></td>
                    <td class='Browse'><?=$bus_ordenes["descripcion"]?></td>
                    <td class='Browse' align="right"><?=number_format($bus_ordenes["monto_retenido"],2,",",".")?></td>
                    <td align="center" class='Browse'>
					<?
					if($bus_ordenes["generar_comprobante"] == 'si'){
					?>
                    	<input type="checkbox" id="generar_comprobante<?=$bus_ordenes["idrelacion_retenciones"]?>" checked="checked" onclick="cambiaGenerarComprobante('<?=$bus_ordenes["idrelacion_retenciones"]?>')">
                    <? }else{ ?>
						<input type="checkbox" id="generar_comprobante<?=$bus_ordenes["idrelacion_retenciones"]?>" onclick="cambiaGenerarComprobante('<?=$bus_ordenes["idrelacion_retenciones"]?>')">
                    <? } ?>
                    </td>

                </tr>
			<?
		}
		?>
    </table>

    <br>
    <table width="800" class="Browse" cellpadding="0" cellspacing="0" align="center">
	  <thead>
        <tr>
            <td colspan="8" class="Browse"><strong>Pagos de Retenciones Aplicadas al Compromiso</strong></td>
      	</tr>
      </thead>
      <thead>
        <tr>
          <td width="40" align="center" class="Browse">C&oacute;digo</td>
          <td width="90" align="center" class="Browse">Descripci&oacute;n</td>
          <td width="60" align="center" class="Browse">Retenido</td>
          <td width="60" align="center" class="Browse">Fecha Enteramiento</td>
          <td width="60" align="center" class="Browse">Fecha Deposito</td>
          <td width="20" align="center" class="Browse">Nro. Deposito</td>
          <td width="60" align="center" class="Browse">Fecha Transferencia</td>
          <td width="15" align="center">Act</td>
        </tr>
        </thead>
        <?
        $sql_ordenes = mysql_query("select relacion_retenciones.idtipo_retencion as idtipo_retencion,
											relacion_retenciones.monto_retenido as monto_retenido,
											relacion_retenciones.generar_comprobante as generar_comprobante,
											tipo_retencion.codigo as codigo,
											tipo_retencion.descripcion as descripcion,
											retenciones.idretenciones,
											relacion_retenciones.idrelacion_retenciones,
											relacion_retenciones.fecha_enteramiento as fecha_enteramiento,
											relacion_retenciones.fecha_deposito as fecha_deposito,
											relacion_retenciones.fecha_transferencia as fecha_transferencia,
											relacion_retenciones.numero_deposito as numero_deposito
										from
											relacion_pago_compromisos, orden_compra_servicio, retenciones, relacion_orden_pago_retencion,
											relacion_retenciones, tipo_retencion
										where
											orden_compra_servicio.idorden_compra_servicio = retenciones.iddocumento
											and relacion_pago_compromisos.idorden_pago = '".$idorden_pago."'
											and relacion_orden_pago_retencion.idorden_pago = '".$idorden_pago."'
											and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
											and relacion_retenciones.idretenciones = retenciones.idretenciones
											and tipo_retencion.idtipo_retencion = relacion_retenciones.idtipo_retencion
										")or die(mysql_error());
		while($bus_ordenes = mysql_fetch_array($sql_ordenes)){
			?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                	<td class='Browse'><?=$bus_ordenes["codigo"]?></td>
                    <td class='Browse'><?=$bus_ordenes["descripcion"]?></td>
                    <td class='Browse' align="right"><?=number_format($bus_ordenes["monto_retenido"],2,",",".")?></td>
                    <td align="center" class='Browse'>
						<input type="text" id="fecha_enteramiento<?=$bus_ordenes["idrelacion_retenciones"]?>" value="<?=$bus_ordenes["fecha_enteramiento"]?>" size="12" readonly>
	                    <img src="imagenes/jscalendar0.gif" name="imagen_fecha_enteramiento<?=$bus_ordenes["idrelacion_retenciones"]?>" width="16" height="16" id="imagen_fecha_enteramiento<?=$bus_ordenes["idrelacion_retenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="
								Calendar.setup({
								inputField    : 'fecha_enteramiento<?=$bus_ordenes["idrelacion_retenciones"]?>',
								button        : 'imagen_fecha_enteramiento<?=$bus_ordenes["idrelacion_retenciones"]?>',
								align         : 'Tr',
								ifFormat      : '%Y-%m-%d'
								});
							"/>
                    </td>
                    <td align="center" class='Browse'>
						<input type="text" id="fecha_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>" value="<?=$bus_ordenes["fecha_deposito"]?>" size="12" readonly>
	                    <img src="imagenes/jscalendar0.gif" name="imagen_fecha_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>" width="16" height="16" id="imagen_fecha_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="
								Calendar.setup({
								inputField    : 'fecha_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>',
								button        : 'imagen_fecha_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>',
								align         : 'Tr',
								ifFormat      : '%Y-%m-%d'
								});
							"/>
                    </td>
                    <td align="center" class='Browse'>
                     	<input type="text" id="numero_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>" value="<?=$bus_ordenes["numero_deposito"]?>" size="20">
                    </td>
                    <td align="center" class='Browse'>
						<input type="text" id="fecha_transferencia<?=$bus_ordenes["idrelacion_retenciones"]?>" value="<?=$bus_ordenes["fecha_deposito"]?>" size="12" readonly>
	                    <img src="imagenes/jscalendar0.gif" name="imagen_fecha_transferencia<?=$bus_ordenes["idrelacion_retenciones"]?>" width="16" height="16" id="imagen_fecha_transferencia<?=$bus_ordenes["idrelacion_retenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onClick="
								Calendar.setup({
								inputField    : 'fecha_transferencia<?=$bus_ordenes["idrelacion_retenciones"]?>',
								button        : 'imagen_fecha_transferencia<?=$bus_ordenes["idrelacion_retenciones"]?>',
								align         : 'Tr',
								ifFormat      : '%Y-%m-%d'
								});
							"/>
                    </td>
                    <td class='Browse' align="center">
                  		<img src="imagenes/refrescar.png" style="cursor:pointer" onClick="actualizarDatosdePago(document.getElementById('numero_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>').value, document.getElementById('fecha_enteramiento<?=$bus_ordenes["idrelacion_retenciones"]?>').value, document.getElementById('fecha_deposito<?=$bus_ordenes["idrelacion_retenciones"]?>').value, document.getElementById('fecha_transferencia<?=$bus_ordenes["idrelacion_retenciones"]?>').value,
                  		'<?=$bus_ordenes["idrelacion_retenciones"]?>')">
                  </td>
                </tr>
			<?
		}
		?>
    </table>
<?
}


if($ejecutar == "actualizarDatosdeFactura"){

	$sql_proveedor = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio= '".$idorden_compra_servicio."'")or die(mysql_error());
	$bus_proveedor = mysql_fetch_array($sql_proveedor);

	$sql_existe = mysql_query("select * from retenciones
    								INNER JOIN orden_compra_servicio on (retenciones.iddocumento = orden_compra_servicio.idorden_compra_servicio)
    								INNER JOIN beneficiarios on (orden_compra_servicio.idbeneficiarios = beneficiarios.idbeneficiarios)
  								where retenciones.numero_factura = '".$numero_factura."'
    									and beneficiarios.idbeneficiarios = '".$bus_proveedor["idbeneficiarios"]."'")or die(mysql_error());

	$num_existe_factura = mysql_num_rows($sql_existe);

	if ($num_existe_factura > 0){
		$sql_datos_factura = mysql_query("update orden_compra_servicio set fecha_factura = '".$fecha_factura."',
																				nro_control = '".$numero_control."'
																		where idorden_compra_servicio = '".$idorden_compra_servicio."'");
		$sql_datos_factura = mysql_query("update retenciones set fecha_factura = '".$fecha_factura."',
																	numero_control = '".$numero_control."'
																		where idretenciones = '".$idretenciones."'");
			echo "exito";

	}else{

		$sql_datos_factura = mysql_query("update orden_compra_servicio set nro_factura = '".$numero_factura."',
																		fecha_factura = '".$fecha_factura."',
																		nro_control = '".$numero_control."'
																		where idorden_compra_servicio = '".$idorden_compra_servicio."'");
		$sql_datos_factura = mysql_query("update retenciones set numero_factura = '".$numero_factura."',
																		fecha_factura = '".$fecha_factura."',
																		numero_control = '".$numero_control."'
																		where idretenciones = '".$idretenciones."'");
		echo "exito";

	}
}


if($ejecutar == "actualizarDatosdePago"){

	$sql_datos_factura = mysql_query("update relacion_retenciones set 	fecha_enteramiento  = '".$fecha_enteramiento."',
																		fecha_deposito      = '".$fecha_deposito."',
																		numero_deposito     = '".$numero_deposito."',
																		fecha_transferencia = '".$fecha_transferencia."'
														where idrelacion_retenciones = '".$idrelacion_retenciones."'")
						or die('error'.mysql_error());

	echo "exito";
}



if($ejecutar == "cambiaGenerarComprobante"){

	$sql_retencion = mysql_query("select * from relacion_retenciones where idrelacion_retenciones= '".$idrelacion_retenciones."'")or die(mysql_error());
	$bus_retencion = mysql_fetch_array($sql_retencion);

	if ($bus_retencion["generar_comprobante"] == 'si'){
		$sql_datos_factura = mysql_query("update relacion_retenciones set generar_comprobante = 'no',
																		  numero_retencion = 0,
																		  periodo = '',
																		  numero_retencion_referencia = '900000000000'
																		where idrelacion_retenciones= '".$idrelacion_retenciones."'");
	}else{
		$sql_datos_factura = mysql_query("update relacion_retenciones set generar_comprobante = 'si'
																		where idrelacion_retenciones= '".$idrelacion_retenciones."'");
	}
}



if($ejecutar == "generarComprobante"){

		$sql_fecha_cierre = mysql_query("select * from configuracion");
		$bus_fecha_cierre = mysql_fetch_array($sql_fecha_cierre);
		$fecha_cierre = $bus_fecha_cierre["fecha_cierre"];
		if (date("Y-m-d") < $fecha_cierre){ $fecha_aplicacion = date("Y-m-d"); }else{ $fecha_aplicacion = $fecha_cierre; }

		$sql_consulta = mysql_query("select *
										from
											retenciones,
											relacion_orden_pago_retencion,
											relacion_retenciones
										where
											relacion_orden_pago_retencion.idorden_pago = '".$idorden_pago."'
											and retenciones.idretenciones = relacion_orden_pago_retencion.idretencion
											and relacion_retenciones.idretenciones = retenciones.idretenciones
											and relacion_retenciones.numero_retencion !=0")or die(mysql_error());

		$num_consulta = mysql_num_rows($sql_consulta);

		if($num_consulta > 0){
			echo "existeComprobante";
		}else{

			//obtengo el numero de lineas por comprobantes establecido en la tabla de configuracion de tributos
			$sql_configuracion = mysql_query("select nro_linea_comprobante from configuracion_tributos")or die(mysql_error());
			$bus_configuracion = mysql_fetch_array($sql_configuracion);

			//obtengo la relacion de retenciones pagadas en esa orden de pago a generar comprobante
			//a cada una de estas retenciones le voy a asignar el mismo nuemero de comprobante
			$sql_consultar_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$idorden_pago."'");

			$acumulador = 0;

			// while de los compromisos pagados en esa orden
			$num_consultar_retenciones = mysql_num_rows($sql_consultar_relacion_retenciones);
			if($num_consultar_retenciones != 0){

			while($bus_consultar_relacion_compromiso = mysql_fetch_array($sql_consultar_relacion_retenciones)){
				// busco la retencion en la tabla de retenciones para luego obtener todas las retenciones calculadas a generar comprobante
				$sql_consultar_retenciones = mysql_query("select * from retenciones
																where idretenciones = '".$bus_consultar_relacion_compromiso["idretencion"]."'");
				$bus_retencion = mysql_fetch_array($sql_consultar_retenciones);
				//actualizo la fecha de aplicacion de la retencion
				$bus_actualizar_fecha_retencion = mysql_query("update retenciones set
																		fecha_aplicacion_retencion = '".$fecha_aplicacion."' where
																		idretenciones = '".$bus_retencion["idretenciones"]."'");

				//consulto la relacion de retenciones que se aplicaron en esa retencion y a las cuales se les debe asignar comprobante
				$sql_consultar_relacion_retencion = mysql_query(
								"select * from relacion_retenciones, tipo_retencion
								where relacion_retenciones.idretenciones = '".$bus_retencion["idretenciones"]."'
										and relacion_retenciones.generar_comprobante = 'si'
										and tipo_retencion.idtipo_retencion = relacion_retenciones.idtipo_retencion
										and tipo_retencion.nombre_comprobante <> 'NA'
									order by asociado")or die(mysql_error());

				//bucle  con las retenciones del documento
				while($bus_consultar_relacion_retencion = mysql_fetch_array($sql_consultar_relacion_retencion)){
					$seguir = 'si';
					//consulto el tipo de retencion para saber si tiene numero de comprobante propio o asociado
					$sql_consultar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_relacion_retencion["idtipo_retencion"]."'");
					$bus_consultar_tipo_retencion = mysql_fetch_array($sql_consultar_tipo_retencion);
					// mantengo el id del tipo de retencion a colocar el numero de comprobante
					$retencion_a_modificar_original = $bus_consultar_tipo_retencion["idtipo_retencion"];
					//si la retencion es padre
					if($bus_consultar_tipo_retencion["asociado"] == 0){
							//valido que alguna retencion hija de este padre se le haya asignado algun
							//numero de comprobante para actualizarselo al padre
							$existen=0;
							$sql_valido_hijos=mysql_query("select * from tipo_retencion
															where asociado = '".$bus_consultar_tipo_retencion["idtipo_retencion"]."'"
															)or die(mysql_error());
							//recorro todas las retenciones que tienen asociado ese padre y valido que ya tengan
							//asignado numero de comprobante
							while($bus_relacion_retencion_asociados = mysql_fetch_array($sql_valido_hijos)){
								$sql_asociados_relacion_retencion = mysql_query("select * from relacion_retenciones
														where idretenciones = '".$bus_retencion["idretenciones"]."'
														and idtipo_retencion = '".$bus_relacion_retencion_asociados["idtipo_retencion"]."'
														and numero_retencion <> '0'
														")or die("error buscando asociados ".mysql_error());
								if (mysql_num_rows($sql_asociados_relacion_retencion)){
									$existe_hijo = mysql_num_rows($sql_asociados_relacion_retencion);
								}else{
									$existe_hijo = 0;
								}
								if ($existe_hijo > 0){
									//echo "existe un hijo con numero de comprobante asignado ".$existe_hijo;
									$bus_asociado_con_numero = mysql_fetch_array($sql_asociados_relacion_retencion);
									$numero_contador = $bus_asociado_con_numero["numero_retencion"];
									$retencion_a_modificar_contador = 0;
									$existen=1;
								}

							}
							//si ninguno de los hijos esta en esta retencion o no se les ha asignado
							//numero les asigno el numero del contador del tipo padre
							if ($existen == 0){
								$numero_contador = $bus_consultar_tipo_retencion["numero_documento"];
								$retencion_a_modificar_contador = $bus_consultar_tipo_retencion["idtipo_retencion"];
							}
					}else{
						$existen = 0;
						//valido que el tipo padre no este dentro de este comprobante para evitar que
						//genere un comprobante por cada retencion del mismo tipo
						$sql_consultar_padre = mysql_query("select * from relacion_retenciones
																where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'
																		and idretenciones = '".$bus_retencion["idretenciones"]."'
																		")or die("error buscando padre ".mysql_error());

						if (mysql_num_rows($sql_consultar_padre)){
							$num_existe_padre = mysql_num_rows($sql_consultar_padre)or die(mysql_error());

							if ($num_existe_padre <= 0){
								//valido que alguno de los otros hijos asociados no se les haya asignado numero
								$sql_valido_hijos_asociados=mysql_query("select * from tipo_retencion
														where asociado = '".$bus_consultar_tipo_retencion["asociado"]."'")
														or die(mysql_error());

								while($bus_relacion_hijos_asociados = mysql_fetch_array($sql_valido_hijos_asociados)){
									$sql_asociados_hijos_retencion = mysql_query("select * from relacion_retenciones
															where idretenciones = '".$bus_retencion["idretenciones"]."'
															and idtipo_retencion = '".$bus_relacion_hijos_asociados["idtipo_retencion"]."'
															and numero_retencion <> '0'")or die(mysql_error());

									if (mysql_num_rows($sql_asociados_hijos_retencion)){
										$existe_hijo = mysql_num_rows($sql_asociados_hijos_retencion);
									}else{
										$existe_hijo = 0;
									}
									if ($existe_hijo > 0){
										$bus_asociado_hijo_con_numero = mysql_fetch_array($sql_asociados_hijos_retencion);
										$numero_contador = $bus_asociado_hijo_con_numero["numero_retencion"];
										$retencion_a_modificar_contador = 0;
										$existen=1;
									}

								}

								if ($existen == 0){
									//si es asociado, busco el tipo de documento padre para obtener el numero de comprobante a aplicarlo
									$sql_consultar_asociado = mysql_query("select * from tipo_retencion
																where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'")
																		or die("aqui 1 ".mysql_error());
									$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);

									$numero_contador = $bus_consultar_asociado["numero_documento"];
									$retencion_a_modificar_contador = $bus_consultar_asociado["idtipo_retencion"];
								}
							}else{
								$bus_existe_padre = mysql_fetch_array($sql_consultar_padre);
								$numero_contador = $bus_existe_padre["numero_retencion"];
								$retencion_a_modificar_contador = 0;
							}
						}else{
							//valido que alguno de los otros hijos asociados no se les haya asignado numero
								$sql_valido_hijos_asociados=mysql_query("select * from tipo_retencion
														where asociado = '".$bus_consultar_tipo_retencion["asociado"]."'")or die(mysql_error());

								while($bus_relacion_hijos_asociados = mysql_fetch_array($sql_valido_hijos_asociados)){
									$sql_asociados_hijos_retencion = mysql_query("select * from relacion_retenciones
																		where idretenciones = '".$bus_retencion["idretenciones"]."'
																			and idtipo_retencion = '".$bus_relacion_hijos_asociados["idtipo_retencion"]."'
																			and numero_retencion <> '0'")or die(mysql_error());

									if (mysql_num_rows($sql_asociados_hijos_retencion)){
										$existe_hijo = mysql_num_rows($sql_asociados_hijos_retencion);
									}else{
										$existe_hijo = 0;
									}
									if ($existe_hijo > 0){
										$bus_asociado_hijo_con_numero = mysql_fetch_array($sql_asociados_hijos_retencion);
										$numero_contador = $bus_asociado_hijo_con_numero["numero_retencion"];
										$retencion_a_modificar_contador = 0;
										$existen=1;
									}

								}

								if ($existen == 0){
									//si es asociado, busco el tipo de documento padre para obtener el
									//numero de comprobante a aplicarlo
									$sql_consultar_asociado = mysql_query("select * from tipo_retencion
															where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'"
															)or die("aqui 1 ".mysql_error());
									$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);

									$numero_contador = $bus_consultar_asociado["numero_documento"];
									$retencion_a_modificar_contador = $bus_consultar_asociado["idtipo_retencion"];
								}

						}
				}
				echo " contador antes de verificar numero ".$numero_contador;
				$numero_documento = $numero_contador;
				//valido si no existe un numero ya asignado para buscarlo y validarlo en los comprobantes y externas
				if ($retencion_a_modificar_contador != 0){
				
				
					$sql_validar_comprobante = mysql_query("select * from comprobantes_retenciones
																		where idorden_pago = '".$idorden_pago."'
																		and idtipo_retencion = '".$retencion_a_modificar_contador."'
																		and estado = 'procesado'");
					$bus_comprobantes = mysql_fetch_array($sql_validar_comprobante);
					$bus_tiene_comprobante = mysql_num_rows($sql_validar_comprobante);
								
					if ($bus_tiene_comprobante == 0){
							
						$numero_documento = $numero_contador;
						//busco que no exista ese numero de comprobante para ese tipo de retencion
						
						/*FALLA AQUI
						
						ESTA BUSCANDO EL COMPROBANTE EN ESE TIPO DE RETENCION, PERO PUEDE ESTAR EN UNA RETENCION RELACIONADA
						DISTINTA 
						
						
						*/
						$sql_consultar_documento = mysql_query("select * from relacion_retenciones 
																		where idtipo_retencion = '".$retencion_a_modificar_original."' 
																		and numero_retencion = '".$numero_documento."'");
						$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
						// si existen numero de comprobante para ese tipo de retencion entro en un ciclo para incrementar el numero
						// de comprobante hasta conseguir uno desocupado
						if($num_consultar_documento != 0){
							$acumulador=1;
							while($acumulador > 0){
								$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion 
																					set numero_documento = numero_documento + 1 
																		where idtipo_retencion = '".$retencion_a_modificar_contador."'");
								$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion 
																		where idtipo_retencion = '".$retencion_a_modificar_contador."'");
								$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
								$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
								$numero_documento = $numero_contador;
								$sql_consultar_documento = mysql_query("select * from relacion_retenciones 
															where idtipo_retencion = '".$retencion_a_modificar_original."' 
															and numero_retencion = '".$numero_documento."'");
								$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
								
								if($num_consultar_documento == 0){
									$sql_consultar_externa = mysql_query("select * from relacion_retenciones_externas
									 									where idtipo_retencion = '".$retencion_a_modificar_original."' 
																		and numero_retencion = '".$numero_documento."'");
									$num_consultar_externa = mysql_num_rows($sql_consultar_externa);
									if($num_consultar_externa == 0){
										$acumulador = 0;
									}else{
										$acumulador++;
									}
								}else {
									$acumulador++;
								}		
							}
						}else{
							//valido que el numero no exista en los comprobantes externos
							$sql_consultar_externa = mysql_query("select * from relacion_retenciones_externas 
																where idtipo_retencion = '".$retencion_a_modificar_original."' 
																and numero_retencion = '".$numero_documento."'");
							$num_consultar_externa = mysql_num_rows($sql_consultar_externa);
							if($num_consultar_externa != 0){
								$acumulador = 1;
								while($acumulador > 0){
									$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion 
																					set numero_documento = numero_documento + 1 
																					where idtipo_retencion = '".$retencion_a_modificar_contador."'");
									$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion 
																							where idtipo_retencion = '".$retencion_a_modificar_contador."'");
									$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
									$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
									$numero_documento = $numero_contador;
									$sql_consultar_documento = mysql_query("select * from relacion_retenciones_externas 
																		where idtipo_retencion = '".$retencion_a_modificar_original."' 
																		and numero_retencion = '".$numero_documento."'");
									$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
									
									if($num_consultar_documento == 0){
										$sql_consultar_interna = mysql_query("select * from relacion_retenciones 
																	where idtipo_retencion = '".$retencion_a_modificar_original."' 
																	and numero_retencion = '".$numero_documento."'");
										$num_consultar_interna = mysql_num_rows($sql_consultar_interna);
										if($num_consultar_interna == 0){
											$acumulador = 0;
										}else{
											$acumulador++;
										}
									}else {
										$acumulador++;
									}		
								}
							}
						}
						//cierro el if para comprobar que el numero de comprobante no existe
				
						//echo date("Y-m-d");
						if ($acumulador==0){
						
							$fecha = explode("-", $fecha_aplicacion);
							$codigo_referencia = 90000000000+$numero_documento;
							$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
															numero_retencion = '".$numero_documento."',
															numero_retencion_referencia = '".$codigo_referencia."'  
															where idretenciones = '".$bus_retencion["idretenciones"]."' 
															and idtipo_retencion = '".$retencion_a_modificar_original."'")or die(mysql_error());
					
							$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																		where idtipo_retencion = '".$retencion_a_modificar_contador."'")or die(mysql_error());
																		
																		
							$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
												(idorden_pago,
												idretenciones, 
												idtipo_retencion,
												numero_retencion,
												fecha_retencion,
												periodo,
												estado)VALUES('".$idorden_pago."',
															'".$bus_retencion["idretenciones"]."',
															'".$retencion_a_modificar_original."',
															'".$numero_documento."',
															'".$fecha_aplicacion."',
															'".$fecha[0].$fecha[1]."',
															'procesado')");
							
						}
					}else{
						// else que valida si ya tiene comprobante	
						//echo "ya tiene comprobante ".$bus_comprobantes["numero_retencion"];
						//echo $fecha_cheque;
						$fecha = explode("-", $fecha_aplicacion);
						$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
											(idorden_pago,
											idretenciones, 
											idtipo_retencion,
											numero_retencion,
											fecha_retencion,
											periodo,
											estado)VALUES('".$idorden_pago."',
														'".$bus_retencion["idretenciones"]."',
														'".$retencion_a_modificar_original."',
														'".$bus_comprobantes["numero_retencion"]."',
														'".$fecha_aplicacion."',
														'".$fecha[0].$fecha[1]."',
														'procesado')");
							//$fecha = explode("-", date("Y-m-d"));
							$codigo_referencia = 90000000000+$bus_comprobantes["numero_retencion"];
							$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																numero_retencion = '".$bus_comprobantes["numero_retencion"]."',
																numero_retencion_referencia = '".$codigo_referencia."' 
																where idretenciones = '".$bus_retencion["idretenciones"]."' 
																and idtipo_retencion = '".$retencion_a_modificar_original."'")or die(mysql_error());
					}
				}else{
					$fecha = explode("-", $fecha_aplicacion);
					$sql_ingresar_comprobante = mysql_query("insert into comprobantes_retenciones
											(idorden_pago,
											idretenciones, 
											idtipo_retencion,
											numero_retencion,
											fecha_retencion,
											periodo,
											estado)VALUES('".$idorden_pago."',
														'".$bus_retencion["idretenciones"]."',
														'".$retencion_a_modificar_original."',
														'".$numero_documento."',
														'".$fecha_aplicacion."',
														'".$fecha[0].$fecha[1]."',
														'procesado')");
					
					$codigo_referencia = 90000000000+$numero_documento;
					$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
														numero_retencion = '".$numero_documento."',
														numero_retencion_referencia = '".$codigo_referencia."'  
														where idretenciones = '".$bus_retencion["idretenciones"]."' 
														and idtipo_retencion = '".$retencion_a_modificar_original."'")or die(mysql_error());
				}
				}// cierro el ciclo con las retenciones aplicadas al documento	
			} //cierre retenciones del documento
			}
			echo "exito";	
		}	
}







if($ejecutar == "listarOrdenPago"){
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
            <thead>
                <tr>
                    <td align="center" width="20%" class="Browse">Nro. Orden</td>
                    <td align="center" width="10%" class="Browse">Fecha</td>
                    <td align="center" width="50%"class="Browse">Beneficiario</td>
                    <td align="center" class="Browse" colspan="4">Accion</td>
                </tr>
            </thead>
<?
	$sql_consultar_ordenes = mysql_query("select orden_pago.numero_orden as numero_orden_pago,
												 orden_pago.idorden_pago as idorden_pago,
												 orden_pago.fecha_orden as fecha_orden,
												 orden_pago.estado as estado,
												 beneficiarios.nombre as nombre_beneficiario
											 from orden_pago, beneficiarios
											where beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios
											and orden_pago.numero_orden like '%".$busqueda."%'
											and (estado = 'pagada'
												or estado = 'procesado'
												or estado = 'parcial'
												or estado = 'conformado')")or die(mysql_error());
														
	while($bus_consultar_ordenes = mysql_fetch_array($sql_consultar_ordenes)){
		?>
        
		<tr bgcolor='#e7dfce' >
            <td align='left' width="20%"  class='Browse'>
                <?=$bus_consultar_ordenes["numero_orden_pago"]?>
                <div id="divCompromisos<?=$bus_consultar_ordenes["idorden_pago"]?>" style="display:none; position:absolute; background:#EFEFEF; border:#000000 2px solid; width:900px"></div>                
            </td>
            
            <td align="center"  width="10%"  class='Browse'><?=$bus_consultar_ordenes["fecha_orden"]?></td>
            <td align='left'  width="50%"  class='Browse'><?=$bus_consultar_ordenes["nombre_beneficiario"]?></td>
            <td align='center' width="5%" class='Browse'>
                <img src="imagenes/ver.png" title="Administrar Compromisos de la Orden: <?=$bus_consultar_ordenes["numero_orden_pago"]?>" style="cursor:pointer" onclick="document.getElementById('divCompromisos<?=$bus_consultar_ordenes["idorden_pago"]?>').style.display = 'block', mostrarCompromisos('<?=$bus_consultar_ordenes["idorden_pago"]?>', 'divCompromisos<?=$bus_consultar_ordenes["idorden_pago"]?>')">
            </td>
            <td align='center' width="5%" class='Browse'>
                <img src="imagenes/comments.png" title="Generar Comprobante de Retencion de los Compromisos de la Orden: <?=$bus_consultar_ordenes["numero_orden_pago"]?>" style="cursor:pointer" onClick="generarComprobante('<?=$bus_consultar_ordenes["idorden_pago"]?>', '/lib/reportes/tributos/','<?=$bus_consultar_ordenes["estado"]?>')">
            </td>
            <td align='center' width="5%" class='Browse'>
                <img src="imagenes/imprimir.png" title="Imprimir Comprobantes de la Orden: <?=$bus_consultar_ordenes["numero_orden_pago"]?>" style="cursor:pointer" onClick="imprimirReporte('<?=$bus_consultar_ordenes["idorden_pago"]?>', '/lib/reportes/tributos/')">
            </td>
            <td align='center' width="5%" class='Browse'>
                <img src="imagenes/delete.png" title="Anular Comprobante de Retencion de los Compromisos de la Orden: <?=$bus_consultar_ordenes["numero_orden_pago"]?>" style="cursor:pointer" onClick="anularComprobante('<?=$bus_consultar_ordenes["idorden_pago"]?>','<?=$bus_consultar_ordenes["estado"]?>')">
            </td>
        </tr>
            
		<?
	}
?>
</table>
	<?
}




if($ejecutar == "guardarAnticipo"){
	$sql_actualizar = mysql_query("update orden_pago set porcentaje_anticipo = '".$anticipo."' where idorden_pago = '".$idorden_pago."'");
}


if($ejecutar == "anularComprobante"){
	
	$sql_anular = mysql_query("update comprobantes_retenciones set estado = 'anulado' where idorden_pago = '".$idorden_pago."' and estado != 'anulado'");
	
	$sql_consultar_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$idorden_pago."'")or die(mysql_error());
		while($bus_consultar_relacion_retenciones = mysql_fetch_array($sql_consultar_relacion_retenciones)){
			$actualiza_retencion = mysql_query("update retenciones set fecha_aplicacion_retencion = '0000-00-00'
														where idretenciones = '".$bus_consultar_relacion_retenciones["idretencion"]."'")or die("error actualizando fecha de aplicacion retenecion".mysql_error());
			
			$sql_retenciones = mysql_query("select * from retenciones where idretenciones = '".$bus_consultar_relacion_retenciones["idretencion"]."'")or die(mysql_error());
			while($bus_retenciones = mysql_fetch_array($sql_retenciones)){
				$sql_actualizar_retencion = mysql_query("update relacion_retenciones set numero_retencion = 0, 
																periodo = ''
																where idretenciones = '".$bus_retenciones["idretenciones"]."'")or die("error actualizando numero retenecion".mysql_error());
			}
		}
}
?>