<?
session_start();

include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();

extract($_POST);


if($ejecutar == "actualizarDatosBasicos"){
	$sql_insertar_datos_basicos = mysql_query("update remision_documentos set
																			iddependencia_destino = '".$para."',
																			asunto = '".$asunto."',
																			justificacion = '".$justificacion."',
																			estado = 'elaboracion',
																			status = 'a',
																			usuario = '".$login."',
																			fechayhora = '".date("Y-m-d H:i:s")."' where idremision_documentos = ".$id_remision."");
registra_transaccion("Actualizar Datos Basicos de Remision de Documentos (".$id_remision.")",$login,$fh,$pc,'remision_documentos');																			
}



if($ejecutar == "ingresarDatosBasicos"){
	if($id_remision == ""){
		if ($modulo == 1){
		  	$sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	  	}else if ($modulo == 2){
		  	$sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	  	}else if($modulo == 3){
		  	$sql_configuracion = mysql_query("select * from configuracion_compras");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
  	  	}else if($modulo == 4){
		  	$sql_configuracion = mysql_query("select * from configuracion_administracion");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 6){
		  	$sql_configuracion = mysql_query("select * from configuracion_tributos");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 7){
		  	$sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
		}else if($modulo == 5){
		  	$sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 12){
		  	$sql_configuracion = mysql_query("select * from configuracion_despacho");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 13){
		  	$sql_configuracion = mysql_query("select * from configuracion_nomina");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 14){
		  	$sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 16){
		  	$sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}else if($modulo == 19){
		  	$sql_configuracion = mysql_query("select * from configuracion_obras");
		  	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	 	}
		
		$sql_insertar_datos_basicos = mysql_query("insert into remision_documentos(fecha_elaboracion,
																				iddependencia_destino,
																				asunto,
																				justificacion,
																				estado,
																				status,
																				usuario,
																				fechayhora,
																				iddependencia_origen)values(
																								'".$fecha_validada."',
																								'".$para."',
																								'".$asunto."',
																								'".$justificacion."',
																								'elaboracion',
																								'a',
																								'".$login."',
																								'".date("Y-m-d H:i:s")."',
																								'".$bus_configuracion["iddependencia"]."')");
		$id_remision = mysql_insert_id();
		registra_transaccion("Ingresar Datos Basicos Remisi&oacute;n de Documentos (".$id_remision.")",$login,$fh,$pc,'remision_documentos');
	}

	$sql_consulta = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."")or die(mysql_error());
	$bus_consulta = mysql_fetch_array($sql_consulta);
	?>
    
	<table width="75%" border="0" align="center" cellpadding="0" cellspacing="2">
    	<tr>
       
      <td colspan="7" align="right"><div align="center"><img src="imagenes/search0.png" title="Buscar Remisiones" style="cursor:pointer" onclick="window.open('lib/listas/listar_remision_documentos.php','buscar remisiones','resisable = no, scrollbars = yes, width=900, height = 500')" />
      <?
      if($bus_consulta["estado"] == "enviado" or $bus_consulta["estado"] == "recibido" or $bus_consulta["estado"] == "anulado"){
	  ?>
      <img src="imagenes/imprimir.png" title="Imprimir Remisi&oacute;n"  onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?nombre=remitirdoc&id_remision='+document.getElementById('id_remision').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" /> 
      <?
      }
	  ?>
      </div></td>
      </tr>
    
    
        <tr>
        <td width="183" align="right" class='viewPropTitle'>N&uacute;mero de Remisi&oacute;n:</td>
        <td width="123" id="divNumeroDocumento">
        <?
        if($bus_consulta["numero_documento"] == ""){
			echo "<strong>Aun No Generado</strong>";
		}else{
			echo "<strong>".$bus_consulta["numero_documento"]."</strong>";
		}
		?>        </td>
      	<td width="117" class='viewPropTitle'><div align="right">Fecha de Envio:</div></td>
        <td width="101" id="divFechaEnvio">&nbsp;</td>
        <td width="211" class='viewPropTitle'><div align="right">Nro. Documentos enviados:</div></td>
      <td width="106" id="divCantidad"><?=$bus_consulta["numero_documentos_enviados"]?></td>
</tr>
    <tr>
      <td align="right" class='viewPropTitle'>Fecha de Elaboraci&oacute;n:</td>
      <td colspan="6"><strong><?=$fecha_validada?></strong></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Para:</td>
      <td colspan="6">
      <select name="dependencias" id="dependencias" <? if($bus_consulta["estado"] == "enviado" || $bus_consulta["estado"] == "recibido" || $bus_consulta["estado"] == "anulado"){echo "disabled";}?>>
	  <option value="0">.:: Seleccione ::. </option>
	  <?
	  if ($modulo == 1){
		  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if ($modulo == 2){
		  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 3){
		  $sql_configuracion = mysql_query("select * from configuracion_compras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
  	  }else if($modulo == 4){
		  $sql_configuracion = mysql_query("select * from configuracion_administracion");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 6){
		  $sql_configuracion = mysql_query("select * from configuracion_tributos");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 7){
		  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 5){
		  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 12){
		  $sql_configuracion = mysql_query("select * from configuracion_despacho");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 13){
		  $sql_configuracion = mysql_query("select * from configuracion_nomina");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 14){
		  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 16){
		  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }else if($modulo == 19){
		  $sql_configuracion = mysql_query("select * from configuracion_obras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  }
	  
      $sql_dependencias = mysql_query("select * from dependencias order by denominacion");
	  while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
	  	if ($bus_dependencias["iddependencia"] <> $bus_configuracion["iddependencia"]){
	  	?>
	  		<option <? if($bus_dependencias["iddependencia"] == $bus_consulta["iddependencia_destino"]){ echo " selected";}?> value="<?=$bus_dependencias["iddependencia"]?>"><?=$bus_dependencias["denominacion"]?></option>
	  	<? }
	  }
	  ?>
      </select>      </td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Asunto:
      <td height="4" colspan="7">
      	<input name="asunto" type="text" id="asunto" value="<?=$bus_consulta["asunto"]?>" size="100" <? if($bus_consulta["estado"] == "enviado" || $bus_consulta["estado"] == "recibido" || $bus_consulta["estado"] == "anulado"){echo "disabled";}?>/>
      	<input type="hidden" name="id_remision" id="id_remision" value="<?=$id_remision?>">
        <input type="hidden" name="estado" id="estado">
        <input type="hidden" name="tabla" id="tabla">      
        <input type="hidden" name="idtipo_documento" id="idtipo_documento">      
        </td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Justificaci&oacute;n:</td>
      <td colspan="6"><textarea <? if($bus_consulta["estado"] == "enviado" || $bus_consulta["estado"] == "recibido" || $bus_consulta["estado"] == "anulado"){echo "disabled";}?> name="justificacion" cols="100" rows="3" id="justificacion"><?=$bus_consulta["justificacion"]?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" id="vistaDeBotones">
      	
        <table align="center">
        	<tr>
        		<td>
                <? if($bus_consulta["estado"] != "enviado" and $bus_consulta["estado"] != "recibido" and $bus_consulta["estado"] != "anulado"){
				?>
				<input type="button" 
                            name="botonElaboracion" 
                            id="botonElaboracion" 
                            value="En Elaboraci&oacute;n" 
                            style="display:block" 
                            onclick="actualizarDatosBasicos(document.getElementById('id_remision').value, document.getElementById('dependencias').value, document.getElementById('asunto').value, document.getElementById('justificacion').value)" 
                            class="button">                	
				<?
				}?>               </td>
                <td>
                <? if($bus_consulta["estado"] != "enviado" and $bus_consulta["estado"] != "recibido" and $bus_consulta["estado"] != "anulado"){
				?>
				<input type="button" 
                            name="botonEnviar" 
                            id="botonEnviar" 
                            value="Enviar" 
                            style="display:block" 
                            onclick="remitirDocumentos(document.getElementById('id_remision').value)" 
                            class="button">                	
				<?
				}?>              </td>
               <td>
                <? if($bus_consulta["estado"] != "recibido" and $bus_consulta["estado"] != "anulado"){
				?>
				<input type="button" 
                            name="botonAnular" 
                            id="botonAnular" 
                            value="Anular" 
                            style="display:block" 
                            onclick="anularRemision(document.getElementById('id_remision').value)" 
                            class="button">                	
				<?
				}?>               </td>
       	  </tr>
       </table>
        <br />        </td>
    </tr>
  </table>
<?
}


if($ejecutar == "consultarDocumentos"){
	switch($documento){
		case "presupuesto":
			?>
            <select name='tipoPresupuesto' id='tipoPresupuesto'>
                <option value='0'>.:: Seleccione ::.</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'elaboracion', document.getElementById('id_remision').value)" value="traslados_presupuestarios">
                	Solicitud de Traslado</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'procesado', document.getElementById('id_remision').value)" value='traslados_presupuestarios'>
                	Traslados Procesados</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'elaboracion', document.getElementById('id_remision').value)" value='creditos_adicionales'>
                	Solicitud de Credito Adicional</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'procesado', document.getElementById('id_remision').value)" value='creditos_adicionales'>
                	Creditos Adicionales Procesados</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'elaboracion', document.getElementById('id_remision').value)" value='disminucion_presupuesto'>
                	Solicitud de Disminucion</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'procesado', document.getElementById('id_remision').value)" value='disminucion_presupuesto'>
                	Disminuciones Procesadas</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'elaboracion', document.getElementById('id_remision').value)" value='rectificacion_presupuesto'>
                	Solicitud de Rectificaciones</option>
                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, 'procesado', document.getElementById('id_remision').value)" value='rectificacion_presupuesto'>
                	Rectificaciones Procesadas</option>
            </select>
           <?
		break;
		case "compromete":

				/*$sql_comprometen = mysql_query("select * from tipos_documentos 
													where 
													compromete = 'si' and causa = 'no' and paga = 'no' order by descripcion");
													
				*/
													
													
			if ($modulo == 2 or $modulo == 4 or $modulo == 6 or $modulo == 7 or $modulo == 5){
				$sql_comprometen = mysql_query("select * from tipos_documentos where 
														(compromete = 'no' and causa = 'no' and paga = 'no' and documento_asociado = 0) 
														or (compromete = 'si' and causa = 'no' and paga = 'no' and documento_asociado = 0)");
			}else if ($modulo == 1 or $modulo == 13){
				$sql_comprometen = mysql_query("select * from tipos_documentos where 
												((compromete = 'no' and causa = 'no' and paga = 'no' and documento_asociado = 0) 
												or (compromete = 'si' and causa = 'no' and paga = 'no' and documento_asociado = 0))
												and (modulo like '%-1-%' or modulo like '%-13-%')");
				}else {
					$sql_comprometen = mysql_query("select * from tipos_documentos where 
													compromete = 'si' and causa = 'no' and paga = 'no' and modulo like '%-".$modulo."-%'");
				}													
			?>
            <select name='tipoCompromisos' id='tipoCompromisos'>
                <option value='0' onclick="ocultarBusqueda()">.:: Seleccione ::.</option>
                <? while ($bus_compromete = mysql_fetch_array($sql_comprometen)){ 
				//$entro_modulo = ereg("-7-",$bus_compromete["modulo"]);
				if($bus_compromete["compromete"] == 'no' and $bus_compromete["causa"] == 'no' and $bus_compromete["paga"] == 'no'){ 
					//if($entro_modulo != "1"){
				?>
	                <option onclick="<? if($bus_compromete["compromete"] == 'no' and $bus_compromete["causa"] == 'no' and $bus_compromete["paga"] == 'no'){ ?> document.getElementById('campo_tipo_documento').value = 'padre'<? }else{ ?>document.getElementById('campo_tipo_documento').value = 'hijo' <? } ?> , consultarSubDocumentos('<?=$documento?>', this.value, '<?=$bus_compromete["idtipos_documentos"]?>', document.getElementById('id_remision').value), aparecerBusqueda()" value='orden_compra_servicio'><?=$bus_compromete["descripcion"]?></option>
               
			   <? 
			   		//}
			   }else{
			   	?>
				<option onclick="<? if($bus_compromete["compromete"] == 'no' and $bus_compromete["causa"] == 'no' and $bus_compromete["paga"] == 'no'){ ?> document.getElementById('campo_tipo_documento').value = 'padre'<? }else{ ?>document.getElementById('campo_tipo_documento').value = 'hijo' <? } ?> , consultarSubDocumentos('<?=$documento?>', this.value, '<?=$bus_compromete["idtipos_documentos"]?>', document.getElementById('id_remision').value), aparecerBusqueda()" value='orden_compra_servicio'><?=$bus_compromete["descripcion"]?></option>
				<?
			   }
			   }
			   ?>
            </select>
		<?
		break;
		case "causa":
			$sql_causan = mysql_query("select * from tipos_documentos where (causa = 'si' and paga = 'no') or (compromete = 'no' and causa = 'no' and paga = 'no') and documento_asociado != 0");
			?>
            <select name='tipoCausados' id='tipoCausados'>
                <option value='0' onclick="ocultarBusqueda()">.:: Seleccione ::.</option>
    			<? while ($bus_causan = mysql_fetch_array($sql_causan)){ ?>
	                <option onclick="consultarSubDocumentos('<?=$documento?>', this.value, '<?=$bus_causan["idtipos_documentos"]?>', document.getElementById('id_remision').value), aparecerBusqueda()" value='orden_pago'><?=$bus_causan["descripcion"]?></option>
               <? } ?>
			</select>
             <?
		break;
	}
}





if($ejecutar == "consultarEstado"){
	$sql_consulta_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."");
	$bus_consulta_remision = mysql_fetch_array($sql_consulta_remision);
	echo $bus_consulta_remision["estado"];
}




//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************

if($ejecutar == "consultarSubDocumentos"){
//echo "entro aqui ".$modulo;
	if ($modulo == 1){
		  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if ($modulo == 2){
		  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 3){
		  $sql_configuracion = mysql_query("select * from configuracion_compras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
  	}else if($modulo == 4){
		  $sql_configuracion = mysql_query("select * from configuracion_administracion");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 6){
		  $sql_configuracion = mysql_query("select * from configuracion_tributos");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 7){
		  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 5){
		  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 12){
		  $sql_configuracion = mysql_query("select * from configuracion_despacho");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 13){
		  $sql_configuracion = mysql_query("select * from configuracion_nomina");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 14){
		  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 16){
		  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 19){
		  $sql_configuracion = mysql_query("select * from configuracion_obras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}
	
	
	$sql_consulta_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."");
	$bus_consulta_remision = mysql_fetch_array($sql_consulta_remision);

	if($bus_consulta_remision["estado"] != "enviado"){
	//echo "AQUI";
		if($bus_consulta_remision["estado"] != "recibido"){

			if($documento=="presupuesto"){
				$sql_consulta = mysql_query("select * from ".$tabla." where ".$tabla.".estado = '".$estado_tipo."' 
																			and ".$tabla.".ubicacion = '0'
																			group by id".$tabla."
																			") or die("error presupuesto".mysql_error());
				$num_consulta = mysql_num_rows($sql_consulta);
			}else if($documento=="compromete"){
		
				//echo "AQUIIII:".$campo_tipo_documento;
				if($campo_tipo_documento == "hijo"){
				
							if ($modulo == 1 || $modulo == 13 || $modulo == 3 || $modulo == 16 || $modulo == 19){
								$sql_consulta = mysql_query("select * from orden_compra_servicio 
														where tipo = '".$estado_tipo."' 
														and numero_orden like '%".$busqueda."%'
														and (estado = 'procesado' or estado = 'conformado' 
														or estado = 'devuelto' or estado = 'parcial')
														and ubicacion = '0'  group by idorden_compra_servicio")
														or die("consulta mala compromete".mysql_error());
								$num_consulta = mysql_num_rows($sql_consulta);
								
							}
								
											
							if ($modulo == 4 || $modulo == 5 || $modulo == 12 || $modulo == 14){
								$sql_tipo_documento = mysql_query("select * from tipos_documentos where 
																						idtipos_documentos = ".$estado_tipo."
																						and modulo like '%-".$modulo."%-'");
								$num_tipo_documento = mysql_fetch_array($sql_tipo_documento);
								if ($num_tipo_documento == 0){
									/*echo "select * from orden_compra_servicio,
										relacion_documentos_recibidos, 
										recibir_documentos 
										where 
										orden_compra_servicio.tipo = '".$estado_tipo."' 
										and (orden_compra_servicio.estado = 'conformado' 
											or orden_compra_servicio.estado = 'devuelto' 
											or orden_compra_servicio.estado = 'procesado' 
											or orden_compra_servicio.estado = 'parcial')
										and orden_compra_servicio.numero_orden like '%".$busqueda."%'
										and (orden_compra_servicio.ubicacion = 'recibido' 
											or orden_compra_servicio.ubicacion = 'procesado')
										and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_recibidos.id_documento
										and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
										and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
										group by orden_compra_servicio.idorden_compra_servicio";*/
									$sql_consulta = mysql_query("select * from orden_compra_servicio,
										relacion_documentos_recibidos, 
										recibir_documentos 
										where 
										orden_compra_servicio.tipo = '".$estado_tipo."' 
										and (orden_compra_servicio.estado = 'conformado' 
											or orden_compra_servicio.estado = 'devuelto' 
											or orden_compra_servicio.estado = 'procesado' 
											or orden_compra_servicio.estado = 'parcial')
										and orden_compra_servicio.numero_orden like '%".$busqueda."%'
										and (orden_compra_servicio.ubicacion = 'recibido' 
											or orden_compra_servicio.ubicacion = 'procesado')
										and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_recibidos.id_documento
										and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
										and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
										group by orden_compra_servicio.idorden_compra_servicio
										order by orden_compra_servicio.codigo_referencia")
										 or die("consulta mala administracion compras".mysql_error());
								}else{
									/*echo "select * from orden_compra_servicio,tipos_documentos 
											where orden_compra_servicio.tipo = '".$estado_tipo."' 
												and (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' or orden_compra_servicio.estado = 'parcial')
												and orden_compra_servicio.numero_orden like '%".$busqueda."%'
												and orden_compra_servicio.ubicacion = '0'
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and tipos_documentos.modulo like '%-".$modulo."-%' 
												group by orden_compra_servicio.idorden_compra_servicio";*/
									$sql_consulta = mysql_query("select * from orden_compra_servicio,tipos_documentos 
											where orden_compra_servicio.tipo = '".$estado_tipo."' 
												and (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' or orden_compra_servicio.estado = 'parcial')
												and orden_compra_servicio.numero_orden like '%".$busqueda."%'
												and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												and tipos_documentos.modulo like '%-".$modulo."-%' 
												group by orden_compra_servicio.idorden_compra_servicio
												order by orden_compra_servicio.codigo_referencia")
												or die("consulta mala compromete".mysql_error());
								}
								$num_consulta = mysql_num_rows($sql_consulta);
							}
							
							if ($modulo == 2 || $modulo == 6 || $modulo == 7){
								//echo "ALLA";
								
								$sql_consulta = mysql_query("select * from 
												orden_compra_servicio,
												relacion_documentos_recibidos, 
												recibir_documentos
												where 
												orden_compra_servicio.tipo = '".$estado_tipo."' 
											and (orden_compra_servicio.estado = 'conformado' 
													or orden_compra_servicio.estado = 'devuelto' 
													or orden_compra_servicio.estado = 'procesado' 
													or orden_compra_servicio.estado = 'parcial')
											and orden_compra_servicio.numero_orden like '%".$busqueda."%'
											and (orden_compra_servicio.ubicacion = 'recibido' 
												or orden_compra_servicio.ubicacion = 'conformado')
											and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_recibidos.id_documento
											and recibir_documentos.idrecibir_documentos = relacion_documentos_recibidos.idrecibir_documentos
											and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
											group by orden_compra_servicio.idorden_compra_servicio")
											or die("consulta mala presupuesto".mysql_error());
								$num_consulta = mysql_num_rows($sql_consulta);
							}
				}else{// SI EL TIPO DE DOCUMENTO ES PADRE
				//echo "ENTRO AQUIIIIIIIIIII";
							if ($modulo == 1 || $modulo == 13 || $modulo == 3 || $modulo == 16 || $modulo == 19){
							//echo "AQUI";
								$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")or die(mysql_error());
								$num_asociados = mysql_num_rows($sql_asociados);
								if ($num_tipo_documento == 0){
								//echo "AQUI";
									$query = "select * from orden_compra_servicio where numero_orden like '%".$busqueda."%' ";
									if($num_asociados > 1){
										$query .= " and (";
									}
									while($bus_asociados = mysql_fetch_array($sql_asociados)){
										$query .= " tipo = '".$bus_asociados["idtipos_documentos"]."'";
										if($num_asociados > 1){
											$query .= " ||";
										}
									}
									if($num_asociados > 1){
										$query = substr($query, 0, strlen($query) - 2);
										$query .=  ") ";
									}
									
									$query .= " and (estado = 'procesado' or estado = 'conformado' 
												or estado = 'devuelto' or estado = 'parcial')
												and (ubicacion = '0' or ubicacion = 'recibido') group by idorden_compra_servicio";
									//echo $query;
									$sql_consulta = mysql_query($query);
									$num_consulta = mysql_num_rows($sql_consulta);
								}
							}
							
							
							if ($modulo == 2 || $modulo == 6 || $modulo == 7){
								//echo "AQUI";
								$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")or die(mysql_error());
								$num_asociados = mysql_num_rows($sql_asociados);
								if ($num_tipo_documento == 0){
									$query = "select * from orden_compra_servicio,
													relacion_documentos_remision, remision_documentos 
													where 
													(orden_compra_servicio.estado = 'conformado' 
													or orden_compra_servicio.estado = 'devuelto' 
													or orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'parcial') ";
									
									if($num_asociados > 1){
										$query .= " and (";
									}
									while($bus_asociados = mysql_fetch_array($sql_asociados)){
										$query .= " orden_compra_servicio.tipo = '".$bus_asociados["idtipos_documentos"]."'";
										if($num_asociados > 1){
											$query .= "||";
										}
									}
									if($num_asociados > 1){
										$query = substr($query, 0, strlen($query) - 2);
										$query .=  ") ";
									}		
												
									$query .= "and orden_compra_servicio.numero_orden like '%".$busqueda."%'
										and (orden_compra_servicio.ubicacion = 'recibido' 
										or orden_compra_servicio.ubicacion = 'conformado')
										and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_remision.id_documento
										and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
										and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
										group by orden_compra_servicio.idorden_compra_servicio";
									
									//echo $query;
									$sql_consulta = mysql_query($query)or die("consulta mala presupuesto".mysql_error());
									$num_consulta = mysql_num_rows($sql_consulta);
								}
								//echo "aqui";
							}
							
							//echo "AQUI";
											
							if ($modulo == 4 || $modulo == 5 || $modulo == 12 || $modulo == 14){
							
								$sql_tipo_documento = mysql_query("select * from tipos_documentos where 
																						idtipos_documentos = ".$estado_tipo."
																						and modulo like '%-".$modulo."-%'")or die(mysql_error());
								$num_tipo_documento = mysql_num_rows($sql_tipo_documento);
								//echo $num_tipo_documento;
								if ($num_tipo_documento == 0){
									//echo "alla";
									//echo "select * from tipos_documentos where documento_asociado = '".$estado_tipo."'";
									$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")or die(mysql_error());
									$num_asociados = mysql_num_rows($sql_asociados);
									if($num_asociados != 0){
										$query = "select * from orden_compra_servicio,
											relacion_documentos_remision, remision_documentos 
											where (orden_compra_servicio.estado = 'conformado' or orden_compra_servicio.estado = 'devuelto' 
											or orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'parcial') ";
										if($num_asociados > 1){
										$query .= " and (";
										}
										while($bus_asociados = mysql_fetch_array($sql_asociados)){
											$query .= " orden_compra_servicio.tipo = '".$bus_asociados["idtipos_documentos"]."'";
											if($num_asociados > 1){
												$query .= "||";
											}
										}
										if($num_asociados > 1){
											$query = substr($query, 0, strlen($query) - 2);
											$query .=  ") ";
										}
											 
										$query .= " and orden_compra_servicio.numero_orden like '%".$busqueda."%'
											and (orden_compra_servicio.ubicacion = 'recibido' or orden_compra_servicio.ubicacion = 'procesado')
											and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_remision.id_documento
											and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
											and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
											group by orden_compra_servicio.idorden_compra_servicio";
										
										//echo $query;
										
										$sql_consulta = mysql_query($query)or die("consulta mala administracion compras".mysql_error());
										$num_consulta = mysql_num_rows($sql_consulta);
									}
								
								
								
								}else{
									$sql_asociados = mysql_query("select * from tipos_documentos where documento_asociado = '".$estado_tipo."'")
																														or die(mysql_error());
									$num_asociados = mysql_num_rows($sql_asociados);
									if ($num_tipo_documento == 0){
										$query = "select * from orden_compra_servicio,tipos_documentos 
												where (orden_compra_servicio.estado = 'procesado' or orden_compra_servicio.estado = 'conformado' 
													or orden_compra_servicio.estado = 'devuelto' or orden_compra_servicio.estado = 'parcial') ";
										if($num_asociados > 1){
										$query .= " and (";
										}
										while($bus_asociados = mysql_fetch_array($sql_asociados)){
											$query .= " orden_compra_servicio.tipo = '".$bus_asociados["idtipos_documentos"]."'";
											if($num_asociados > 1){
												$query .= "||";
											}
										}
										if($num_asociados > 1){
											$query = substr($query, 0, strlen($query) - 2);
											$query .=  ") ";
										}					
										$query .= "and orden_compra_servicio.numero_orden like '%".$busqueda."%'
												   and orden_compra_servicio.ubicacion = '0'
												   and tipos_documentos.idtipos_documentos = orden_compra_servicio.tipo
												   and tipos_documentos.modulo like '%-".$modulo."-%' 
												   group by orden_compra_servicio.idorden_compra_servicio";
									
										$sql_consulta = mysql_query($query)or die("consulta mala compromete".mysql_error());
										$num_consulta = mysql_num_rows($sql_consulta);
									}
								}
								
							}
				}// FIN DE SI EL TIPO DE DOCUMENTO ES PADRE
				
				
			}else if($documento=="causa"){
				
				if ($modulo == 2){
					$sql_consulta = mysql_query("select * from orden_pago,
																relacion_documentos_recibidos, 
																recibir_documentos
												where 
												orden_pago.tipo = '".$estado_tipo."' 
												and (orden_pago.estado = 'procesado' 
													or orden_pago.estado = 'conformado' 
													or orden_pago.estado = 'devuelto')
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.ubicacion = 'recibido' 
													or orden_pago.ubicacion = 'conformado')
												and orden_pago.idorden_pago = relacion_documentos_recibidos.id_documento
												and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
												and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."'
												GROUP BY orden_pago.idorden_pago
												order by orden_pago.codigo_referencia")
											or die("consulta mala presupuesto causa".mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				if ($modulo == 4){	
					$sql_consulta = mysql_query("select * from orden_pago 
												where tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (estado = 'procesado' or estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.ubicacion = '0' group by idorden_pago") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				if ($modulo == 5){	
					$sql_consulta = mysql_query("select * from orden_pago,
												relacion_documentos_recibidos, 
												recibir_documentos 
												where 
												orden_pago.tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.estado = 'procesado' 
												or orden_pago.estado = 'conformado' 
												or orden_pago.estado = 'devuelto')
												and (orden_pago.ubicacion = 'recibido' or orden_pago.ubicacion = 'conformado')
												and orden_pago.idorden_pago = relacion_documentos_recibidos.id_documento
												and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos 
												and recibir_documentos.iddependencia_recibe = '".$bus_configuracion["iddependencia"]."' 
												GROUP BY orden_pago.idorden_pago
												order by orden_pago.codigo_referencia") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				
				if ($modulo == 12){	
					$sql_consulta = mysql_query("select * from orden_pago 
												where tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (estado = 'procesado' or estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.ubicacion = '0' group by idorden_pago") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				
				if ($modulo == 14){	
					$sql_consulta = mysql_query("select * from orden_pago 
												where tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (estado = 'procesado' or estado = 'conformado' or orden_pago.estado = 'devuelto')
												and orden_pago.ubicacion = '0' group by idorden_pago") or die(mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				
				if ($modulo == 6){
					$sql_consulta = mysql_query("select * from orden_pago,relacion_documentos_remision, remision_documentos 
												where orden_pago.tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.estado = 'procesado' or orden_pago.estado = 'conformado' or orden_pago.estado = 'devuelto' or orden_pago.estado = 'pagada')
												and (orden_pago.ubicacion = 'recibido' or orden_pago.ubicacion = 'conformado')
												and orden_pago.idorden_pago = relacion_documentos_remision.id_documento
												and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
												and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
												GROUP BY orden_pago.idorden_pago
												order by orden_pago.codigo_referencia")
												or die("consulta mala presupuesto causa".mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
				if ($modulo == 7){
					$sql_consulta = mysql_query("select * from orden_pago,relacion_documentos_remision, remision_documentos 
												where orden_pago.tipo = '".$estado_tipo."' 
												and orden_pago.numero_orden like '%".$busqueda."%'
												and (orden_pago.estado = 'procesado' or orden_pago.estado = 'conformado' or orden_pago.estado = 'devuelto' or orden_pago.estado = 'pagada')
												and (orden_pago.ubicacion = 'recibido')
												and orden_pago.idorden_pago = relacion_documentos_remision.id_documento
												and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos 
												and remision_documentos.iddependencia_destino = '".$bus_configuracion["iddependencia"]."' 
												GROUP BY orden_pago.idorden_pago 
												order by orden_pago.codigo_referencia")
												or die("consulta mala presupuesto causa".mysql_error());
					$num_consulta = mysql_num_rows($sql_consulta);
				}
			}
			

			if($num_consulta > 0){
				if($documento == "presupuesto"){
				
			?>
<input type="hidden" id="estado_consulta" name="estado_consulta" value="<?=$bus_consulta_remision["estado"]?>">
			<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
				  <tr>
					<td class="Browse"><div align="center">Incluir</div></td>
					<td class="Browse"><div align="center">Origen</div></td>
					<td class="Browse"><div align="center">Numero</div></td>
					<td class="Browse"><div align="center">Fecha</div></td>
					<td class="Browse"><div align="center">Justificacion</div></td>
				  </tr>
				  </thead>
					
					<?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						$sql_remision = mysql_query("select * from relacion_documentos_remision where id_documento = ".$bus_consulta[0]." 
																				and idtipos_documentos = '".$estado_tipo."' 
																				and idremision_documentos = ".$id_remision."");
						$num_remision = mysql_num_rows($sql_remision);
						if($num_remision == 0){
						$id = "id".$tabla;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta[$id]?>', document.getElementById('id_remision').value, document.getElementById('origenDocumentos').value, 'ingresar', '<?=$tabla?>', '<?=$estado_tipo?>')">
					
						<td class="Browse" align='center'>
							<img src="imagenes/validar.png" style=" cursor:pointer">
						</td>
						<td class='Browse' align='left'>
						<? 
						if($tabla == "traslados_presupuestarios" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Traslado";
						}else if($tabla == "traslados_presupuestarios" and $estado_tipo == 'procesado'){
							$origen = "Traslados Procesados";
						}else if($tabla == "creditos_adicionales" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Creditos Adicionales";
						}else if($tabla == "creditos_adicionales" and $estado_tipo == 'procesado'){
							$origen = "Creditos Adicionales Procesados";
						}else if($tabla == "disminucion_presupuesto" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Disminucion";
						}else if($tabla == "disminucion_presupuesto" and $estado_tipo == 'procesado'){
							$origen = "Disminuciones Procesadas";
						}else if($tabla == "rectificacion_presupuesto" and $estado_tipo == 'elaboracion'){
							$origen = "Solicitud de Rectificaciones";
						}else if($tabla == "rectificacion_presupuesto" and $estado_tipo == 'procesado'){
							$origen = "Rectificaciones Procesadas";
						}
						
						echo $origen;
						?>
						</td>
						<td class='Browse' align='left'><?=$bus_consulta["nro_solicitud"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_solicitud"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["justificacion"]?></td>
					 </tr>
					<?
						}
					}
					?>
				</table>
				
				<?
				}
				
				if($documento == "compromete"){
						?>
					<input type="hidden" id="estado_consulta" name="estado_consulta" value="<?=$bus_consulta_remision["estado"]?>">	
				<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
                
				<thead>
				  <tr>
					<td class="Browse"><div align="center">Incluir</div></td>
					<td class="Browse"><div align="center">Numero</div></td>
					<td class="Browse"><div align="center">Fecha</div></td>
					<td class="Browse"><div align="center">Beneficiario</div></td>
                    <td class="Browse"><div align="center">Monto</div></td>
				  </tr>
				  </thead>
					
					<?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						$sql_remision = mysql_query("select * from relacion_documentos_remision where id_documento = ".$bus_consulta[0]." 
																	and idtipos_documentos = '".$estado_tipo."' 
																	and idremision_documentos = ".$id_remision."");
						$num_remision = mysql_num_rows($sql_remision);
						if($num_remision == 0){
							$id = "id".$tabla;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta[$id]?>', document.getElementById('id_remision').value, document.getElementById('origenDocumentos').value, 'ingresar', '<?=$tabla?>', '<?=$estado_tipo?>')" style="cursor:pointer">
   
						<td class="Browse" align='center'><img src="imagenes/validar.png" style=" cursor:pointer"></td>
                        
						<td class='Browse' align='left'><?=$bus_consulta["numero_orden"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_orden"]?></td>
                        
						<td class='Browse' align='left'>
						<?
                            $sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = ".$bus_consulta["idbeneficiarios"]."");
							$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
                            echo $bus_beneficiarios["nombre"];
                            ?>
						
                        
                        </td>
                        <td class='Browse' align='right'><?=number_format($bus_consulta["total"],2,",",".")?></td>
					 </tr>
					<?
					}
					}
				
				}
			if($documento == "causa"){
						?>
					<input type="hidden" id="estado_consulta" name="estado_consulta" value="<?=$bus_consulta_remision["estado"]?>">	
				<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
                
				<thead>
				  <tr>
					<td class="Browse"><div align="center">Incluir</div></td>
					<td class="Browse"><div align="center">Numero</div></td>
					<td class="Browse"><div align="center">Fecha</div></td>
					<td class="Browse"><div align="center">Beneficiario</div></td>
                    <td class="Browse"><div align="center">Monto</div></td>
				  </tr>
				  </thead>
					
					<?
					while($bus_consulta = mysql_fetch_array($sql_consulta)){
						$sql_remision = mysql_query("select * from relacion_documentos_remision where id_documento = ".$bus_consulta[0]." 
																		and idtipos_documentos = '".$estado_tipo."' 
																		and idremision_documentos = ".$id_remision."");
						$num_remision = mysql_num_rows($sql_remision);
						if($num_remision == 0){
							
							$id = "id".$tabla;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="listaSeleccionadosDocumentos('<?=$bus_consulta[$id]?>', document.getElementById('id_remision').value, document.getElementById('origenDocumentos').value, 'ingresar', '<?=$tabla?>', '<?=$estado_tipo?>')">

						<td class="Browse" align='center'><img src="imagenes/validar.png" style=" cursor:pointer"></td>
                        
						<td class='Browse' align='left'><?=$bus_consulta["numero_orden"]?></td>
						<td class='Browse' align='left'><?=$bus_consulta["fecha_orden"]?></td>
                        
						<td class='Browse' align='left'>
						<?
                            $sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = ".$bus_consulta["idbeneficiarios"]."");
							$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
                            echo $bus_beneficiarios["nombre"];
                            ?>
						
                        
                        </td>
                        <td class='Browse' style="text-align:right"><?=number_format($bus_consulta["total"],2,",",".")?></td>
					 </tr>
					<?
					}
					}
				
				}
			}else{
			echo "<center>Sin Resultados</center>";
			}
	}else{
	echo "<strong>Remision Recibida</strong>";
	}
	}else{
	echo "<strong>Remision Enviada</strong>";
	}
}




//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************
//***************************************************************************************************************************************************


if($ejecutar == "listaSeleccionadosDocumentos"){
	
	if ($modulo == 1){
		  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if ($modulo == 2){
		  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 3){
		  $sql_configuracion = mysql_query("select * from configuracion_compras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
  	}else if($modulo == 4){
		  $sql_configuracion = mysql_query("select * from configuracion_administracion");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 6){
		  $sql_configuracion = mysql_query("select * from configuracion_tributos");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 7){
		  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 5){
		  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 12){
		  $sql_configuracion = mysql_query("select * from configuracion_despacho");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 13){
		  $sql_configuracion = mysql_query("select * from configuracion_nomina");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 14){
		  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 16){
		  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}else if($modulo == 19){
		  $sql_configuracion = mysql_query("select * from configuracion_obras");
		  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	}
	if($accion != "consultar"){
		if($accion == "ingresar"){
			$sql_insercion = mysql_query("insert into relacion_documentos_remision(
																							idremision_documentos,
																							iddependencia_origen,
																							tabla,
																							idtipos_documentos,
																							id_documento,
																							estado,
																							status,
																							usuario,
																							fechayhora
																								)values(
																										'".$id_remision."',
																										'".$bus_configuracion["iddependencia"]."',
																										'".$tabla."',
																										'".$estado_tipo."',
																										'".$id_documento."',
																										'elaboracion',
																										'a',
																										'".$login."',
																										'".date("Y-m-d")."')")or die(mysql_error());
		
		
		}else if($accion == "eliminar"){
		
		}
	}
	
	$sql_consulta = mysql_query("select * from relacion_documentos_remision where idremision_documentos = ".$id_remision."")or die(mysql_error());
	$num_consulta = mysql_num_rows($sql_consulta); 
	if($num_consulta > 0){
		$sql_consulta_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."")or die(mysql_error());
		$bus_consulta_remision = mysql_fetch_array($sql_consulta_remision);
	?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
          <tr>
            <td class="Browse"><div align="center">Origen</div></td>
            <td class="Browse"><div align="center">Numero</div></td>
            <td class="Browse"><div align="center">Fecha</div></td>
            <td class="Browse"><div align="center">Monto Bs.</div></td>
            <td class="Browse"><div align="center">Justificacion / Beneficiario</div></td>
            <?
           	if($bus_consulta_remision["estado"] == "elaboracion"){
		   	?>
            <td class="Browse"><div align="center">Eliminar</div></td>
            <?
            }
			?>
          </tr>
          </thead>
	<?
	while($bus_consulta = mysql_fetch_array($sql_consulta)){
		$idtabla = "id".$bus_consulta["tabla"];
		$sql_consulta2 = mysql_query("select * from ".$bus_consulta["tabla"]." where ".$idtabla." = ".$bus_consulta["id_documento"]." group by ".$idtabla."");
		$bus_consulta2 = mysql_fetch_array($sql_consulta2);
		//echo "select * from ".$bus_consulta["tabla"]." where ".$idtabla." = ".$bus_consulta["id_documento"]."";
	?>
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                
                <td class='Browse' align='left'>
				<?
                if($bus_consulta["tabla"] == "traslados_presupuestarios" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
					$origen = "Solicitud de Traslado";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "traslados_presupuestarios" and $bus_consulta["idtipos_documentos"] == 'procesado'){
					$origen = "Traslados Procesados";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "creditos_adicionales" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
					$origen = "Solicitud de Creditos Adicionales";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "creditos_adicionales" and $bus_consulta["idtipos_documentos"] == 'procesado'){
					$origen = "Creditos Adicionales Procesados";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "disminucion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
					$origen = "Solicitud de Disminucion";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "disminucion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'procesado'){
					$origen = "Disminuciones Procesadas";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "rectificacion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'elaboracion'){
					$origen = "Solicitud de Rectificaciones";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "rectificacion_presupuesto" and $bus_consulta["idtipos_documentos"] == 'procesado'){
					$origen = "Rectificaciones Procesadas";
					$mostrar = "presupuesto";
				}else if($bus_consulta["tabla"] == "orden_compra_servicio") {
					$sql_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_consulta["idtipos_documentos"]."");
					$bus_documento = mysql_fetch_array($sql_documento);
					$origen = $bus_documento["descripcion"];
					$mostrar = "compromete";
				}else if($bus_consulta["tabla"] == "orden_pago") {
					$sql_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_consulta["idtipos_documentos"]."");
					$bus_documento = mysql_fetch_array($sql_documento);
					$origen = $bus_documento["descripcion"];
					$mostrar = "causa";
				}
				echo $origen;
				?>
                </td>
                <? if ($mostrar == "presupuesto") { ?>
                <td class='Browse' align='left'><?=$bus_consulta2["nro_solicitud"]?></td>
                <td class='Browse' align='left'><?=$bus_consulta2["fecha_solicitud"]?></td>
                <td class='Browse' align='left'><?=$bus_consulta2["justificacion"]?></td>
                <td class='Browse' align='left'>&nbsp;</td>
               
                 <? }
				 if ($mostrar == "compromete" or $mostrar == "causa") { ?>
	                <td class='Browse' align='left'><?=$bus_consulta2["numero_orden"]?></td>
    	            <td class='Browse' align='left'><?=$bus_consulta2["fecha_orden"]?></td>
                    <td class='Browse' style="text-align:right"><?=number_format($bus_consulta2["total"],2,",",".")?></td>
        	        <td class='Browse' align='left'><?
                            $sql_beneficiarios = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_consulta2["idbeneficiarios"]."'")or die(mysql_error());
							$bus_beneficiarios = mysql_fetch_array($sql_beneficiarios);
                            echo $bus_beneficiarios["nombre"];
                            ?>
                    </td>
                 <? }
           			if($bus_consulta_remision["estado"] == "elaboracion"){
		   			?>
                    <td class="Browse" align='center'>
                <img src="imagenes/delete.png" style=" cursor:pointer" onclick="eliminarListaSeleccionadosDocumentos('<?=$bus_consulta["id_documento"]?>', '<?=$bus_consulta["idtipos_documentos"]?>', <?=$id_remision?>)"> </td>
                <?
                }
				?>
                
             
         	 </tr>
			<?
		}
	?>
	</table>
	<?
	}else{
	echo "<center>No hay Documentos Seleccionados</center>";
	}
	
}



//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
//***********************************************************************************************************************************************

if($ejecutar == "eliminarListaSeleccionadosDocumentos"){
		//echo "delete relacion_documentos_remision_documentos where id_documento = ".$id." and tipo = ".$tipo." and idremision_documentos = ".$id_remision."";
		$sql_eliminar = mysql_query("delete from relacion_documentos_remision where id_documento = '".$id."' and idtipos_documentos = '".$tipo."' and idremision_documentos = '".$id_remision."'")or die(mysql_error());
		registra_transaccion("Eliminar Documentos Seleccionados para Remitir (".$id_remision.")",$login,$fh,$pc,'remision_documentos');

}


//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
//***********************************************************************************************************************************************



if($ejecutar == "consultarCantidadDocumentos"){
		$sql_cantidad = mysql_query("select * from relacion_documentos_remision where idremision_documentos = ".$id_remision."");
		$num_cantidad = mysql_num_rows($sql_cantidad);
		$sql_actualizar = mysql_query("update remision_documentos set numero_documentos_enviados = ".$num_cantidad." where idremision_documentos = ".$id_remision."");
		echo "<strong>".$num_cantidad."</strong>";
}



//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
//***********************************************************************************************************************************************



if($ejecutar == "remitirDocumentos"){
	$sql_relacion = mysql_query("select * from relacion_documentos_remision where idremision_documentos = ".$id_remision."");
	$num_relacion = mysql_num_rows($sql_relacion);
	if($num_relacion != 0){
		if ($modulo == 1){
			  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "RRHH-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if ($modulo == 2){
			  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "PPTO-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 3){
			  $sql_configuracion = mysql_query("select * from configuracion_compras");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "OFCS-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 4){
			  $sql_configuracion = mysql_query("select * from configuracion_administracion");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "ADMI-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 6){
			  $sql_configuracion = mysql_query("select * from configuracion_tributos");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "OFTI-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 7){
			  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "TESO-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 5){
			  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "CONT-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 12){
			  $sql_configuracion = mysql_query("select * from configuracion_despacho");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "DESP-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 13){
			  $sql_configuracion = mysql_query("select * from configuracion_nomina");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "NOMI-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 14){
			  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "SGG-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 16){
			  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "CJCH-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}else if($modulo == 19){
			  $sql_configuracion = mysql_query("select * from configuracion_obras");
			  $bus_configuracion = mysql_fetch_array($sql_configuracion);
			  $numero_documento = "COB-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
		}
		

	
		$sql_existe_num = mysql_query("select * from remision_documentos where numero_documento = '".$numero_documento."'");
		$num_existe_num = mysql_num_rows($sql_existe_num);
		
		while($num_existe_num > 0){
			if ($modulo == 1){
				  $sql_configuracion = mysql_query("select * from configuracion_rrhh");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "RRHH-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if ($modulo == 2){
				  $sql_configuracion = mysql_query("select * from configuracion_presupuesto");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "PPTO-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 3){
				  $sql_configuracion = mysql_query("select * from configuracion_compras");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "OFCS-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 4){
				  $sql_configuracion = mysql_query("select * from configuracion_administracion");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "ADMI-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 6){
				  $sql_configuracion = mysql_query("select * from configuracion_tributos");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "OFTI-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 7){
				  $sql_configuracion = mysql_query("select * from configuracion_tesoreria");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "TESO-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 5){
				  $sql_configuracion = mysql_query("select * from configuracion_contabilidad");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "CONT-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 12){
				  $sql_configuracion = mysql_query("select * from configuracion_despacho");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "DESP-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 13){
				  $sql_configuracion = mysql_query("select * from configuracion_nomina");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "NOMI-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 14){
				  $sql_configuracion = mysql_query("select * from configuracion_secretaria");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "SGG-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 16){
				  $sql_configuracion = mysql_query("select * from configuracion_caja_chica");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "CJCH-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}else if($modulo == 19){
				  $sql_configuracion = mysql_query("select * from configuracion_obras");
				  $bus_configuracion = mysql_fetch_array($sql_configuracion);
				  $numero_documento = "COB-".$_SESSION["anio_fiscal"]."-".$bus_configuracion["nro_remision"];
			}
			$sql_existe_num = mysql_query("select * from remision_documentos where numero_documento = '".$numero_documento."'");
			$num_existe_num = mysql_num_rows($sql_existe_num);
		
		
			if ($modulo == 1){
				$sql_actualizar_configuracion = mysql_query("update configuracion_rrhh set nro_remision = nro_remision+1");
				$oficina = "Recursos Humanos";
			}else if ($modulo == 2){
				$sql_actualizar_configuracion = mysql_query("update configuracion_presupuesto set nro_remision = nro_remision+1");
				$oficina = "Presupuesto";
			}else if($modulo == 3){
				$sql_actualizar_configuracion = mysql_query("update configuracion_compras set nro_remision = nro_remision+1");
				$oficina = "Compras y Servicios";
			}else if($modulo == 4){
				$sql_actualizar_configuracion = mysql_query("update configuracion_administracion set nro_remision = nro_remision+1");
				$oficina = "Administracion";
			}else if($modulo == 6){
				$sql_actualizar_configuracion = mysql_query("update configuracion_tributos set nro_remision = nro_remision+1");
				$oficina = "Tributos Internos";
			}else if($modulo == 7){
				$sql_actualizar_configuracion = mysql_query("update configuracion_tesoreria set nro_remision = nro_remision+1");
				$oficina = "Tesoreria";
			}else if($modulo == 5){
				$sql_actualizar_configuracion = mysql_query("update configuracion_contabilidad set nro_remision = nro_remision+1");
				$oficina = "Contabilidad";
			}else if($modulo == 12){
				$sql_actualizar_configuracion = mysql_query("update configuracion_despacho set nro_remision = nro_remision+1");
				$oficina = "Despacho";
			}else if($modulo == 13){
				$sql_actualizar_configuracion = mysql_query("update configuracion_nomina set nro_remision = nro_remision+1");
				$oficina = "Nomina";
			}else if($modulo == 14){
				$sql_actualizar_configuracion = mysql_query("update configuracion_secretaria set nro_remision = nro_remision+1");
				$oficina = "Secretaria de Gobierno";
			}else if($modulo == 16){
				$sql_actualizar_configuracion = mysql_query("update configuracion_caja_chica set nro_remision = nro_remision+1");
				$oficina = "Caja Chica";
			}else if($modulo == 19){
				$sql_actualizar_configuracion = mysql_query("update configuracion_obras set nro_remision = nro_remision+1");
				$oficina = "Control de Obras";
			}		
		
		}
			
		$sql_actualizar = mysql_query("update remision_documentos set numero_documento = '".$numero_documento."',
																		fecha_envio = '".$fecha_validada."',
																		estado = 'enviado' where idremision_documentos = ".$id_remision."")or die(mysql_error());
																		
		$sql_relacion = mysql_query("select * from relacion_documentos_remision where idremision_documentos = ".$id_remision."");
		//$sql_actualizar_remision_documentos = mysql_query("update remision_documentos set estado = 'enviado'  where idremision_documentos = ".$id_remision."");
		
		while($bus_relacion = mysql_fetch_array($sql_relacion)){
			
			$sql_actualizar_relacion = mysql_query("update relacion_documentos_remision set estado = 'enviado' 
																		where idrelacion_documentos_remision = ".$bus_relacion["idrelacion_documentos_remision"]."");
			$tabla = $bus_relacion["tabla"];
			$id_tabla = "id".$tabla;
			$sql_tabla = mysql_query("update ".$tabla." set ubicacion = 'enviado' where ".$id_tabla." = ".$bus_relacion["id_documento"]."");
		}
		echo "<strong>".$numero_documento."</strong>";
		registra_transaccion("Remitir documento desde ".$oficina." (".$numero_documento.")",$login,$fh,$pc,'remision_documentos');
	}else{
		registra_transaccion("Remitir documentos ERROR (no tiene documentos)",$login,$fh,$pc,'remision_documentos');
		echo "noTieneDocumentos";
	}
}





if($ejecutar == "anularRemision"){
	$sql_actualizar = mysql_query("update remision_documentos set estado = 'anulado' where idremision_documentos = ".$id_remision."");
	$sql_relacion = mysql_query("select * from relacion_documentos_remision where idremision_documentos = ".$id_remision."");
	
	while($bus_relacion = mysql_fetch_array($sql_relacion)){
		
		$sql_actualizar_relacion = mysql_query("update relacion_documentos_remision set estado = 'anulado' 
								where idrelacion_documentos_remision = ".$bus_relacion["idrelacion_documentos_remision"]."");
		$tabla = $bus_relacion["tabla"];
		$id_tabla = "id".$tabla;
		
	
		$sql_tabla = mysql_query("select * from ".$tabla."");
		$bus_tabla = mysql_fetch_array($sql_tabla);
		$sql_tipo = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_relacion["idtipos_documentos"]."'");
		$bus_tipo = mysql_fetch_array($sql_tipo);
		$bus_tipo["modulo"] = explode("-", $bus_tipo["modulo"]);
		if(in_array($_SESSION["modulo"], $bus_tipo["modulo"]) == true){
			$sql_tabla = mysql_query("update ".$tabla." set ubicacion = '0' where ".$id_tabla." = ".$bus_relacion["id_documento"]."");
		}else{
			$sql_tabla = mysql_query("update ".$tabla." set ubicacion = 'recibido' where ".$id_tabla." = ".$bus_relacion["id_documento"]."");
		}
		
		
	}
	if ($modulo == 1){
		$oficina = "Recursos Humanos";
	}else if ($modulo == 2){
		$oficina = "Presupuesto";
	}else if($modulo == 3){
		$oficina = "Compras y Servicios";
	}else if($modulo == 4){
		$oficina = "Administracion";
	}else if($modulo == 6){
		$oficina = "Tributos Internos";
	}else if($modulo == 7){
		$oficina = "Tesoreria";
	}else if($modulo == 5){
		$oficina = "Contabilidad";
	}else if($modulo == 12){
		$oficina = "Despacho";
	}else if($modulo == 13){
		$oficina = "Nomina";
	}else if($modulo == 14){
		$oficina = "Secretaria de Gobierno";
	}else if($modulo == 16){
		$oficina = "Caja Chica";
	}else if($modulo == 19){
		$oficina = "Control de Obras";
	}
	
	registra_transaccion("Anular Remision de Documento de ".$oficina." (".$id_remision.")",$login,$fh,$pc,'remision_documentos');
}

?>