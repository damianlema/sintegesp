<?
session_start();
include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();

extract($_POST);

if($ejecutar == "mostrarUbicacion"){

$sql_conforma = mysql_query("select * from configuracion_presupuesto")or die(mysql_error());
$bus_conforma = mysql_fetch_array($sql_conforma);

$sql_ubicacion_remitidos = mysql_query("select * from relacion_documentos_remision,remision_documentos,dependencias
											where remision_documentos.estado != 'anulado' and remision_documentos.numero_documento != '' and
 remision_documentos.idremision_documentos = relacion_documentos_remision.idremision_documentos
												and dependencias.iddependencia = remision_documentos.iddependencia_destino
												order by remision_documentos.idremision_documentos")or die(mysql_error());


$bus_existe_ubicacion = mysql_num_rows($sql_ubicacion_remitidos);
if ($bus_existe_ubicacion > 0){ 
?>

			<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
				<tr>
                          <td width="23%" class="Browse"><div align="center">Enviado Por</div></td>
                          <td width="23%" class="Browse"><div align="center">Recibido Por</div></td>
                          <td width="6%" class="Browse"><div align="center">N&uacute;mero Remisi&oacute;n</div></td>
                          <td width="6%" class="Browse"><div align="center">Fecha Envio</div></td>
                          <td width="6%" class="Browse"><div align="center">Fecha Recibido</div></td>
                          <td width="23%" class="Browse"><div align="center">Para</div></td>
                          <td width="19%" class="Browse"><div align="center">Recibido Por:</div></td>
					
	  		  	</tr>
				  </thead>
					<?
					$entro = false;
                    while($bus_ubicacion = mysql_fetch_array($sql_ubicacion_remitidos)){
						$tabla_registro = $bus_ubicacion["tabla"];
						
						if ($tabla == $tabla_registro and $bus_ubicacion["id_documento"] == $id_documento){
					
					?>
                    	
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                            <td class='Browse' align='left'>
                            <? 	$sql_dependencia_envia = mysql_query("select * from dependencias where iddependencia = ".$bus_ubicacion["iddependencia_origen"]."");
                            	$bus_dependencia = mysql_fetch_array($sql_dependencia_envia);
								
							 echo $bus_dependencia["denominacion"]?>
                            
                            </td>
                            <td class='Browse' align='left'>&nbsp;</td>
                            <td class='Browse' align='center'>&nbsp;<?=$bus_ubicacion["numero_documento"]?></td>
                            <td class='Browse' align='center'>&nbsp;<?=$bus_ubicacion["fecha_envio"]?></td>
                            <td class='Browse' align='center'>&nbsp;</td>
                            <td class='Browse' align='left'>&nbsp;<?=$bus_ubicacion["denominacion"]?></td>
                            <td class='Browse' align='center'>&nbsp;</td>
                           
				  		</tr>
                     <?		
							$sql_ubicacion_recibidos = mysql_query("select * from relacion_documentos_recibidos,recibir_documentos,dependencias
												where relacion_documentos_recibidos.id_documento = ".$id_documento."
													and relacion_documentos_recibidos.tabla = '$tabla'
													and recibir_documentos.idremision_documentos = ".$bus_ubicacion["idremision_documentos"]."
													")or die(mysql_error());
							$existe_recibido = mysql_num_rows($sql_ubicacion_recibidos);
							if ($existe_recibido){ 

								$bus_recibido = mysql_fetch_array($sql_ubicacion_recibidos);
								$tabla_registro = $bus_recibido["tabla"];
								if ($tabla == $tabla_registro and $bus_recibido["id_documento"] == $id_documento){
								?>
                            	
								<tr bgcolor="#BBBBBB" onMouseOver="setRowColor(this, 0, 'over', '#BBBBBB', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#BBBBBB', '#EAFFEA', '#FFFFAA')">
                                	<td class='Browse' align='left'>&nbsp;</td>
									<td class='Browse' align='left'>&nbsp;<?=$bus_ubicacion["denominacion"]?></td>
									<td class='Browse' align='center'>&nbsp;</td>
                                    <td class='Browse' align='left'>&nbsp;</td>
									<td class='Browse' align='center'>&nbsp;<?=$bus_recibido["fecha_recibido"]?></td>
                                    <td class='Browse' align='center'>&nbsp;</td>
									<td class='Browse' align='left'>&nbsp;<?=$bus_recibido["recibido_por"]?></td>
				
								  </tr>
							<?
								// VALIDAR QUE FUE CONFORMADO
								if ($bus_recibido["iddependencia_recibe"] == $bus_conforma["iddependencia"]){
									
									$sql_conformado = mysql_query("select * from conformar_documentos
																					where iddocumento = ".$id_documento."
																						and idrecibido = ".$bus_recibido["idrecibir_documentos"]."")or die(mysql_error());
									$existe_conformado = mysql_num_rows($sql_conformado);
									if ($existe_conformado){ 
										$bus_conformado = mysql_fetch_array($sql_conformado);?>
										
										
                                        	<? if ($bus_conformado["estado"] == "conformado") { ?>
                                            	<tr bgcolor="#00CC66" onMouseOver="setRowColor(this, 0, 'over', '#00CC66', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#00CC66', '#EAFFEA', '#FFFFAA')">
												<td class='Browse' align='left'><strong> C O N F O R M A D O </strong></td>
                                            <? }else{ ?>
                                            	<tr bgcolor="#FF0000" onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
                                            	<td class='Browse' align='left'><strong> D E V U E L T O </strong></td>
                                            <? } ?>
											<td class='Browse' align='left'>&nbsp;<?=$bus_conformado["conformado_por"]?></td>
											<td class='Browse' align='center'>&nbsp;
											<? if($bus_conformado["estado"] == "conformado") { echo "Conformado"; }else{ echo "Devuelto";}?></td>
											<td class='Browse' align='left'>&nbsp;</td>
											<td class='Browse' align='center'><?=$bus_conformado["fecha_conformado"]?></td>
											<td class='Browse' align='center'>&nbsp;
                                            <? 	$sql_razones = mysql_query("select * from razones_devolucion where idrazones_devolucion = ".$bus_conformado["idrazones_devolucion"]."");
												$bus_razones = mysql_fetch_array($sql_razones);
												echo $bus_razones["descripcion"];
											?>
                                            </td>
											<td class='Browse' align='left'><?=$bus_conformado["observaciones"]?></td>
						
										  </tr>
                                  <?
									}								  
									}
									
								}
						
							}
							$entro = true;	
						}
                     }
					  if (!$entro){ ?>
							<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
								<thead>
									  <tr>
										<td align="center">
											<? echo "Documento no Remitido"; ?>
										</td>
									  </tr>
								</thead>
							</table>
					<? } ?>
				</table>

<?
	}else{ ?>
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
			<thead>
				  <tr>
                  	<td align="center">
						<? echo "Documento no Remitido"; ?>
                    </td>
                  </tr>
            </thead>
        </table>
     <?      
	}
	
	
}
//registra_transaccion("Ubicacion de los Documentos",$login,$fh,$pc,'remision_documentos');

echo "<br /><br /><center>";
	$sql_orden = mysql_query("select * from ".$tabla." where id".$tabla." = '".$id_documento."'")or die(mysql_error());
	$bus_orden = mysql_fetch_array($sql_orden);
	//echo $bus_orden["estado"];
      switch($bus_orden["estado"]){
	  case "elaboracion":
		  $mostrar_estado = "<span style='font-size:14px'>El Documento se Encuentra en Estado: <strong>En Elaboraci&oacute;n</strong></span>";
		  break;
	  case "procesado":
	  	  $mostrar_estado = "<span style='font-size:14px'>El Documento se Encuentra en Estado: <strong>Procesado</strong></span>";
		  break;
	  case "anulado":
	  	  $mostrar_estado = "<span style='font-size:14px'>El Documento se Encuentra en Estado: <strong>Anulado</strong></span>";
		  break;
	  case "parcial":
	  	  $mostrar_estado = "<span style='font-size:14px'>El Documento se Encuentra en Estado: <strong>Parcial</strong>  |";

			 $sql_pago_compromisos = mysql_query("select * from relacion_pago_compromisos 
			 												where idorden_compra_servicio = '".$bus_orden["idorden_compra_servicio"]."'");
		if($tabla == "orden_pago"){
		  	 $id_orden = $id_documento;
		  }else{
		 	 $bus_pago_compromisos = mysql_fetch_array($sql_pago_compromisos);
		 	 $id_orden = $bus_pago_compromisos["idorden_pago"];
			 $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$id_orden."'");
			 $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
		  	 $mostrar_estado .= "<strong> Numero de la Orden: </strong>".$bus_orden_pago["numero_orden"]." | ";
		  	 $mostrar_estado .= "<strong>Fecha de la Orden: </strong>".$bus_orden_pago["fecha_elaboracion"]." | <br />"; 
			}
	 	 $sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$id_orden."'");
	  	 $bus_cheque = mysql_fetch_array($sql_cheque);
	 	 if($bus_cheque["numero_cheque"] != ""){
		 	$mostrar_estado .= " <strong>Numero de Cheque: </strong>: 
														".$bus_cheque["numero_cheque"]." | ";
		 }
		 if($bus_cheque["fecha_cheque"] != ""){
		 	$mostrar_estado .= " <strong>Fecha del Cheque: </strong>: 
														".$bus_cheque["fecha_cheque"]." | ";
		 }
		 if($bus_cheque["recibido_por"] != ""){
		 	$mostrar_estado .= " <strong>Recibido por: </strong> 
														".$bus_cheque["recibido_por"]." - <strong>CI:</strong> ".$bus_cheque["ci_recibe"]." - <strong>Fecha:</strong> ".$bus_cheque["fecha_recibe"]."</span>";
		 }
		  break;
	  case "pagado":
		  $mostrar_estado = "<span style='font-size:14px'> El Documento se Encuentra en Estado: 
	  													<strong>Pagado</strong> |";
		 
			 $sql_pago_compromisos = mysql_query("select * from relacion_pago_compromisos 
			 												where idorden_compra_servicio = '".$bus_orden["idorden_compra_servicio"]."'");
		 	 $bus_pago_compromisos = mysql_fetch_array($sql_pago_compromisos);
		 	 $id_orden = $bus_pago_compromisos["idorden_pago"];
			 $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$id_orden."'");
			 $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
		  	 $mostrar_estado .= "<strong> Numero de la Orden: </strong>".$bus_orden_pago["numero_orden"]." | ";
		  	 $mostrar_estado .= "<strong>Fecha de la Orden: </strong>".$bus_orden_pago["fecha_elaboracion"]." | <br />"; 
		  
	 	 $sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$id_orden."'");
	  	 $bus_cheque = mysql_fetch_array($sql_cheque);
	 	 if($bus_cheque["numero_cheque"] != ""){
		 	$mostrar_estado .= " <strong>Numero de Cheque</strong>: 
														".$bus_cheque["numero_cheque"]." | ";
		 }
		 if($bus_cheque["fecha_cheque"] != ""){
		 	$mostrar_estado .="<strong>Fecha del Cheque</strong>: 
														".$bus_cheque["fecha_cheque"]." | ";
		 }
		 if($bus_cheque["recibido_por"] != ""){
		 	$mostrar_estado .= " <strong>Recibido por: </strong> 
														".$bus_cheque["recibido_por"]." - <strong>CI:</strong> ".$bus_cheque["ci_recibe"]." - <strong>Fecha:</strong> ".$bus_cheque["fecha_recibe"]."</span>";
		 }
	  	 break;
		 
		 case "pagada":
		  $mostrar_estado = "<span style='font-size:14px'> El Documento se Encuentra en Estado: 
	  													<strong>Pagado</strong> |";

			 $sql_pago_compromisos = mysql_query("select * from relacion_pago_compromisos 
			 												where idorden_compra_servicio = '".$bus_orden["idorden_compra_servicio"]."'");
		 if($tabla == "orden_pago"){
		  	 $id_orden = $id_documento;
		  }else{
		 	 $bus_pago_compromisos = mysql_fetch_array($sql_pago_compromisos);
		 	 $id_orden = $bus_pago_compromisos["idorden_pago"];
			 $sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$id_orden."'");
			 $bus_orden_pago = mysql_fetch_array($sql_orden_pago);
		  	 $mostrar_estado .= "<strong> Numero de la Orden: </strong>".$bus_orden_pago["numero_orden"]." | ";
		  	 $mostrar_estado .= "<strong>Fecha de la Orden: </strong>".$bus_orden_pago["fecha_elaboracion"]." | <br />"; 
			}
	 	 $sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$id_orden."'");
	  	 $bus_cheque = mysql_fetch_array($sql_cheque);
	 	 if($bus_cheque["numero_cheque"] != ""){
		 	$mostrar_estado .= " <strong>Numero de Cheque</strong>: 
														".$bus_cheque["numero_cheque"]." | ";
		 }
		 if($bus_cheque["fecha_cheque"] != ""){
		 	$mostrar_estado .="<strong>Fecha del Cheque</strong>: 
														".$bus_cheque["fecha_cheque"]." | ";
		 }
		 if($bus_cheque["recibido_por"] != ""){
		 	$mostrar_estado .= " <strong>Recibido por: </strong> 
														".$bus_cheque["recibido_por"]." - <strong>CI:</strong> ".$bus_cheque["ci_recibe"]." - <strong>Fecha:</strong> ".$bus_cheque["fecha_recibe"]."</span>";
		 }
	  	 break;
	  }
	  echo $mostrar_estado."</center>";
	  

?>

