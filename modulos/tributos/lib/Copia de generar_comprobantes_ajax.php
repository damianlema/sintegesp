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
    
<?
}


if($ejecutar == "actualizarDatosdeFactura"){
	$sql_datos_factura = mysql_query("update orden_compra_servicio set nro_factura = '".$numero_factura."',
																		fecha_factura = '".$fecha_factura."',
																		nro_control = '".$numero_control."'
																		where idorden_compra_servicio = '".$idorden_compra_servicio."'");
	$sql_datos_factura = mysql_query("update retenciones set numero_factura = '".$numero_factura."',
																		fecha_factura = '".$fecha_factura."',
																		numero_control = '".$numero_control."'
																		where idretenciones = '".$idretenciones."'");																	
}




if($ejecutar == "generarComprobante"){
		
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


			$sql_configuracion = mysql_query("select nro_linea_comprobante from configuracion_tributos")or die(mysql_error());
			$bus_configuracion = mysql_fetch_array($sql_configuracion);
			
			
			//$sql_consultar_relacion_compromiso = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$idorden_pago."'");
			$sql_consultar_relacion_retenciones = mysql_query("select * from relacion_orden_pago_retencion where idorden_pago = '".$idorden_pago."'");
			$acumulador = 0;
			
			// while de los compromisos pagados en esa orden
			$num_consultar_retenciones = mysql_num_rows($sql_consultar_relacion_retenciones);
			if($num_consultar_retenciones != 0){
				
				while($bus_consultar_relacion_compromiso = mysql_fetch_array($sql_consultar_relacion_retenciones)){
					
					$sql_consultar_retenciones = mysql_query("select * from retenciones 
															 		where idretenciones = '".$bus_consultar_relacion_compromiso["idretencion"]."'");
					$bus_retencion = mysql_fetch_array($sql_consultar_retenciones);
						
					$bus_actualizar_fecha_retencion = mysql_query("update retenciones set 
																			fecha_aplicacion_retencion = '".date("Y-m-d")."' where 
																			idretenciones = '".$bus_retencion["idretenciones"]."'");
																			
					$sql_consultar_relacion_retencion = mysql_query("select * from relacion_retenciones 
																		where idretenciones = '".$bus_retencion["idretenciones"]."'
																			and relacion_retenciones.generar_comprobante = 'si'")or die(mysql_error());
					
						//bucle  con las retenciones del documento
						while($bus_consultar_relacion_retencion = mysql_fetch_array($sql_consultar_relacion_retencion)){
/*************** AQUIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII		*/	
							
							//consulto el tipo de retencion para saber si tiene numero de comprobante propio o asociado
							$sql_consultar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_relacion_retencion["idtipo_retencion"]."'");
							$bus_consultar_tipo_retencion = mysql_fetch_array($sql_consultar_tipo_retencion);
							// mantengo el id del tipo de retencion a colocar el numero de comprobante
							$retencion_a_modificar_original = $bus_consultar_tipo_retencion["idtipo_retencion"];
							//echo "orig ".$retencion_a_modificar_original;
							if($bus_consultar_tipo_retencion["asociado"] == 0){
									$numero_contador = $bus_consultar_tipo_retencion["numero_documento"];
									$retencion_a_modificar_contador = $bus_consultar_tipo_retencion["idtipo_retencion"];
							}else{
									//si es asociado, busco el tipo de documento padre para obtener el numero de comprobante a aplicarlo
									$sql_consultar_asociado = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'");
									$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);
									$numero_contador = $bus_consultar_asociado["numero_documento"];
									$retencion_a_modificar_contador = $bus_consultar_asociado["idtipo_retencion"];
							}
							//echo "cont ".$numero_contador;
							$sql_validar_comprobante = mysql_query("select * from comprobantes_retenciones
																				where idorden_pago = '".$idorden_pago."'
																				and idtipo_retencion = '".$retencion_a_modificar_contador."'
																				and estado = 'procesado'");
							$bus_comprobantes = mysql_fetch_array($sql_validar_comprobante);
							$bus_tiene_comprobante = mysql_num_rows($sql_validar_comprobante);
										
							if ($bus_tiene_comprobante == 0){
							
								//si es numero propio obtengo el numero de comprobante para aplicarlo
								if($bus_consultar_tipo_retencion["asociado"] == 0){
									$numero_contador = $bus_consultar_tipo_retencion["numero_documento"];
									$retencion_a_modificar_contador = $bus_consultar_tipo_retencion["idtipo_retencion"];
									
									$numero_documento = $numero_contador;
									//busco que no exista ese numero de comprobante para ese tipo de retencion
									$sql_consultar_documento = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
									$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
									// si existen numero de comprobante para ese tipo de retencion entro en un ciclo para incrementar el numero
									// de comprobante hasta conseguir uno desocupado
									if($num_consultar_documento != 0){
										$acumulador=1;
										while($acumulador > 0){
											$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																													where idtipo_retencion = '".$retencion_a_modificar_contador."'");
											$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$retencion_a_modificar_contador."'");
											$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
											$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
											$numero_documento = $numero_contador;
											$sql_consultar_documento = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".			
																		$retencion_a_modificar_original."' and numero_retencion = '".$numero_documento."'");
											$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
											
											//echo "acu ".$acumulador;
											//echo " doc ".$numero_documento;
											if($num_consultar_documento == 0){
												$acumulador = 0;
											}else {
												$acumulador++;
											}		
										}
									} //cierro el if para comprobar que el numero de comprobante no existe
											
								}else{
									//si es asociado, busco el tipo de documento padre para obtener el numero de comprobante a aplicarlo
									$sql_consultar_asociado = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_tipo_retencion["asociado"]."'");
									$bus_consultar_asociado = mysql_fetch_array($sql_consultar_asociado);
									$numero_contador = $bus_consultar_asociado["numero_documento"];
									$retencion_a_modificar_contador = $bus_consultar_asociado["idtipo_retencion"];
									$numero_documento = $numero_contador;
									
									$sql_todos_asociados=mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consultar_asociado["idtipo_retencion"]."' or asociado ='".$bus_consultar_asociado["idtipo_retencion"]."'");
									//echo "numero asociados ".mysql_num_rows($sql_todos_asociados);
									
									while($bus_consultar_todos_asociados = mysql_fetch_array($sql_todos_asociados)){
										$retencion_asociada=$bus_consultar_todos_asociados["idtipo_retencion"];
										//busco que no exista ese numero de comprobante para ese tipo de retencion
										$sql_consultar_documento = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".$retencion_asociada."' and numero_retencion = '".$numero_documento."'");
										$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
										// si existen numero de comprobante para ese tipo de retencion entro en un ciclo para incrementar el numero
										// de comprobante hasta conseguir uno desocupado
										if($num_consultar_documento != 0){
											$acumulador=1;
											while($acumulador > 0){
												$sql_actualizar_tipo_retencion = mysql_query("update tipo_retencion set numero_documento = numero_documento + 1 
																														where idtipo_retencion = '".$retencion_a_modificar_contador."'");
												$sql_seleccionar_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$retencion_a_modificar_contador."'");
												$bus_seleccionar_tipo_retencion = mysql_fetch_array($sql_seleccionar_tipo_retencion);
												$numero_contador = $bus_seleccionar_tipo_retencion["numero_documento"];
												$numero_documento = $numero_contador;
												$sql_consultar_documento = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".			
																			$retencion_asociada."' and numero_retencion = '".$numero_documento."'");
												$num_consultar_documento = mysql_num_rows($sql_consultar_documento);
												
												if($num_consultar_documento == 0){
													$acumulador = 0;
												}else {
													$acumulador++;
												}		
											}
										} //cierro el if para comprobar que el numero de comprobante no existe

									}
								}
										//echo date("Y-m-d");
								if ($acumulador==0){
									
								
										$fecha = explode("-", date("Y-m-d"));
										$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																		numero_retencion = '".$numero_documento."' 
																		where idretenciones = '".$bus_consultar_retenciones["idretenciones"]."' 
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
																		'".$bus_consultar_retenciones["idretenciones"]."',
																		'".$retencion_a_modificar_original."',
																		'".$numero_documento."',
																		'".date("Y-m-d")."',
																		'".$fecha[0].$fecha[1]."',
																		'procesado')");
									
								}
								
			
							}else {  // else que valida si ya tiene comprobante	
								//echo "ya tiene comprobante ".$bus_comprobantes["numero_retencion"];
								//echo $fecha_cheque;
								
									$fecha = explode("-", date("Y-m-d"));
									
									$sql_relacion_retenciones = mysql_query("update relacion_retenciones set periodo = '".$fecha[0].$fecha[1]."', 
																	numero_retencion = '".$bus_comprobantes["numero_retencion"]."' 
																	where idretenciones = '".$bus_consultar_retenciones["idretenciones"]."' 
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
	
	$sql_consultar_relacion_compromiso = mysql_query("select * from relacion_pago_compromisos where idorden_pago = '".$idorden_pago."'")or die(mysql_error());
		while($bus_consultar_relacion_compromiso = mysql_fetch_array($sql_consultar_relacion_compromiso)){
			$sql_retenciones = mysql_query("select * from retenciones where iddocumento = '".$bus_consultar_relacion_compromiso["idorden_compra_servicio"]."'")or die(mysql_error());
			while($bus_retenciones = mysql_fetch_array($sql_retenciones)){
				$sql_actualizar_retencion = mysql_query("update relacion_retenciones set numero_retencion = 0, periodo = '' where idretenciones = '".$bus_retenciones["idretenciones"]."'")or die("error actualizando numero retenecion".mysql_error());
			}
			
		}
}
?>