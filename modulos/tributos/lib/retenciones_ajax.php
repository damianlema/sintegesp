<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


if($ejecutar == "mostrarRetencion"){
	$sql_relacion_retenciones = mysql_query("select * from relacion_retenciones where idtipo_retencion = '".$id_tipo_retencion."'");
	$bus_relacion_retenciones = mysql_fetch_array($sql_relacion_retenciones);

	$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$id_tipo_retencion."'");
	$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
	
	
	if($bus_tipo_retencion["base_calculo"] == "Exento"){
		$base = $exento;
	}else if($bus_tipo_retencion["base_calculo"] == "Base Imponible"){
		$base = $sub_total;
	}else if($bus_tipo_retencion["base_calculo"] == "IVA"){
		$base = $impuesto;
	}else if($bus_tipo_retencion["base_calculo"] == "Total"){
		$base = $total;
	}
	
	
	$factor = ($bus_tipo_retencion["unidad_tributaria"]*$bus_tipo_retencion["porcentaje"]/100)*$bus_tipo_retencion["factor_calculo"];
	if($bus_tipo_retencion["monto_fijo"] == 0){
	?>
   
	<table width="100%" align="center">
	  <tr>
		<td align="center" class='viewPropTitle'>C&oacute;digo</td>
		<td colspan="3" align="center" class='viewPropTitle'>Descripci&oacute;n</td>
		<td align="center" class='viewPropTitle'>C&oacute;digo de Concepto</td>
		<td align="center" class='viewPropTitle'>Base C&aacute;lculo</td>
		<td align="center" class='viewPropTitle'>Porcentaje</td>
		<td align="center" class='viewPropTitle'>Divisor</td>
		<td align="center" class='viewPropTitle'>Sustraendo</td>
		<td align="center" class='viewPropTitle'>Factor</td>
		<td align="center" class='viewPropTitle'>Total Retenci&oacute;n</td>
		<td align="center" >&nbsp;</td>
	  </tr>
	  <tr>
		<td align="center">
        	<input name="codigo_retencion" type="text" id="codigo_retencion" size="10" disabled value="<?=$bus_tipo_retencion["codigo"]?>">
        </td>
		<td colspan="3" align="center">
        	<input name="descipcion_retencion" type="text" 
            									id="descipcion_retencion" 
                                                size="50" 
                                                disabled value="<?=$bus_tipo_retencion["descripcion"]?>">
        </td>
		<td align="center">
			<input name="codigo_concepto" type="text" id="codigo_concepto" size="15" value="<?=$bus_relacion_retenciones["codigo_concepto"]?>"/>
		</td>
		<td align="center">
        	<input name="base_calculo_retencion" 
            		type="text" 
                    id="base_calculo_retencion" 
                    size="15" 
                    style="text-align:right" 
                    value="<?=number_format($base,2,",",".")?>" 
                    onblur="formatoNumero(this.name, 'base_calculo_oculto'), restarSustraendo()">
        	<input type="hidden" name="base_calculo_oculto" id="base_calculo_oculto" value="<?=$base?>">        
        </td>
		<td align="center">
        	<input name="porcentaje_retencion" type="text" 
            									id="porcentaje_retencion" 
                                                size="10" 
                                                style="text-align:right" 
                                                value="<?=$bus_tipo_retencion["porcentaje"]?>">
        </td>
	 	<td align="center">
        	<input name="divisor_retencion" type="text" 
            								id="divisor_retencion" 
                                            size="10" 
                                            style="text-align:right" 
                                            disabled value="<?=$bus_tipo_retencion["divisor"]?>">
        </td>
		<td align="center">
		  <input name="sustraendo" type="text" id="sustraendo" 
          										size="10" 
                                                style="text-align:right" 
                                                value="<?=number_format(0,2,",",".")?>" 
                                                onblur="formatoNumero(this.name, 'sustraendo_oculto'), restarSustraendo()"/>
          <input name="sustraendo_oculto" type="hidden" id="sustraendo_oculto" size="10" style="text-align:right" value="0"/>
		</td>
		<td align="center">
		 	<input name="factor" type="text" id="factor" size="10" style="text-align:right" value="<?=number_format($factor,2,",",".")?>"
            					onblur="formatoNumero(this.name, 'factor_oculto'), restarSustraendo()"/>
          	<input name="factor_oculto" type="hidden" id="factor_oculto" size="10" style="text-align:right" value="<?=$factor?>"/>
          	<input type="hidden" name="total_retencion_oculto" 
            					id="total_retencion_oculto" 
                                value="<?=(($base*$bus_tipo_retencion["porcentaje"])/$bus_tipo_retencion["divisor"])-$factor?>">
		</td>
		<td align="center">
        	<input name="total_retencion" type="text" 
                                            id="total_retencion" 
                                            size="10" 
                                            disabled 
                                            value="<?=number_format((($base*$bus_tipo_retencion["porcentaje"])/$bus_tipo_retencion["divisor"])-$factor,3,",",".")?>" style="text-align:right"></td>
		<td align="center">
        
        <?
        if($destino == 'ingresar'){
		?>  
        <img id="botonSeleccionarRelacionIngresar" src="imagenes/validar.png" style="cursor:pointer; display:block" 
        			onclick="registrarRetencion(document.getElementById('tipo_pago').value, 
                    							document.getElementById('id_retencion').value, 
                                                '<?=$id_tipo_retencion?>', 
                                                document.getElementById('porcentaje_retencion').value,
                                                document.getElementById('base_calculo_oculto').value,
                                                document.getElementById('sustraendo').value, 
                                                document.getElementById('factor_oculto').value,
                                                document.getElementById('total_retencion_oculto').value,
                                                document.getElementById('porcentaje_impuesto').value, 
                                                document.getElementById('porcentaje_impuesto').value, 
                                                document.getElementById('codigo_concepto').value)" title="Ingresar">
        <?
        }else{
		?>
        <img id="botonSeleccionarRelacionModificar" src="imagenes/modificar.png" style="cursor:pointer; display:block" 
        		onclick="modificarRelacionRetencion(document.getElementById('id_retencion').value, 
                									'<?=$id_tipo_retencion?>', 
                                                    document.getElementById('porcentaje_retencion').value, 
                                                    document.getElementById('base_calculo_oculto').value, 
                                                    document.getElementById('sustraendo').value, 
                                                    document.getElementById('factor_oculto').value, 
                                                    document.getElementById('total_retencion_oculto').value,
                                                    '', 
                                                    document.getElementById('porcentaje_impuesto').value, 
                                                    document.getElementById('idrelacion_retenciones').value,
                                                    document.getElementById('monto_retenido_viejo').value, 
                                                    document.getElementById('codigo_concepto').value,
                                                    document.getElementById('tipo_pago').value)" title="Modificar">
        <?
        }
		?>        </td>
	  </tr>
      <tr>
      	<td colspan="11" align="right" class='viewPropTitle'><strong>Generar Comprobante de retenci&oacute;n:</strong></td>
		<td align="left" >
        	<input id="generar_comprobante" name="generar_comprobante" type="checkbox" checked="checked" /></td>
      </tr>
	</table>
    
<?
	}else{
	?>
	
	<table width="100%" align="center">
	  <tr>
		<td align="center" class='viewPropTitle'>C&oacute;digo</td>
		<td colspan="3" align="center" class='viewPropTitle'>Descripci&oacute;n</td>
		<td align="center" class='viewPropTitle'>C&oacute;digo de Concepto</td>
		<td align="center" class='viewPropTitle'>Base C&aacute;lculo</td>
		<td align="center" class='viewPropTitle'>Monto Fijo</td>
		<td align="center" class='viewPropTitle'>Sustraendo</td>
		<td align="center" class='viewPropTitle'>Factor</td>
		<td align="center" class='viewPropTitle'>Total Retenci&oacute;n</td>
		<td align="center" >&nbsp;</td>
	  </tr>
	  <tr>
		<td align="center">
        	<input name="codigo_retencion" type="text" id="codigo_retencion" size="10" disabled value="<?=$bus_tipo_retencion["codigo"]?>">
        </td>
		<td colspan="3" align="center">
        	<input name="descipcion_retencion" type="text" id="descipcion_retencion" size="50" 
            												disabled value="<?=$bus_tipo_retencion["descripcion"]?>">
        </td>
		<td align="center">
		  <input name="codigo_concepto" type="text" id="codigo_concepto" size="15" value="<?=$bus_relacion_retenciones["codigo_concepto"]?>"/>
		</td>
		<td align="center">
        	<input name="base_calculo_retencion" type="text" id="base_calculo_retencion" size="15" 
            									value="<?=number_format($base,2,",",".")?>" 
                                                style="text-align:right" 
                                                onblur="formatoNumero(this.name, 'base_calculo_oculto'), restarSustraendo()">        
        	<input type="hidden" id="base_calculo_oculto" name="base_calculo_oculto" value="<?=$base?>">
        </td>
		<td align="center">
        	<input type="hidden" name="monto_fijo_oculto" id="monto_fijo_oculto">
        	<input name="monto_fijo_retencion" type="text" 
            									id="monto_fijo_retencion" 
                                                size="10" 
                                                value="<?=$bus_tipo_retencion["monto_fijo"]?>" 
                                                style="text-align:right" 
                                                onblur="actualizarMontosFijos(this.value)">
        </td>
		<td align="center">
		  <input name="sustraendo" type="text" id="sustraendo" size="10" disabled="disabled"/>
		</td>
		<td align="center">
		  <input name="factor" type="text" id="factor" size="10" onblur="formatoNumero(this.name, 'factor_oculto'), restarSustraendo()"/>
          <input type="hidden" id="factor_oculto" name="factor_oculto">
		</td>
		<td align="center">
        	<input type="hidden" name="total_retencion_oculto" id="total_retencion_oculto" value="<?=$bus_tipo_retencion["monto_fijo"]?>">
        	<input name="total_retencion" type="text" id="total_retencion" size="10" disabled style="text-align:right" 
            												value="<?=number_format($bus_tipo_retencion["monto_fijo"],2,",",".")?>">
        </td>
		<td align="center">
        
        <?
        if($destino == 'ingresar'){
			?>
             <img src="imagenes/validar.png" id="botonSeleccionarRelacionIngresarFijo" style="cursor:pointer; display:block" onclick="registrarRetencion(document.getElementById('tipo_pago').value, 
             		document.getElementById('id_retencion').value, 
                    '<?=$id_tipo_retencion?>', 
                    '', 
                    document.getElementById('base_calculo_oculto').value,  
                    document.getElementById('sustraendo').value, 
                    document.getElementById('factor_oculto').value, 
                    document.getElementById('total_retencion_oculto').value, 
                    document.getElementById('porcentaje_impuesto').value, 
                    document.getElementById('porcentaje_impuesto').value, 
                    document.getElementById('codigo_concepto').value)" title="Ingresar Fijo">
			<?
		}else{
			?>
			<img id="botonSeleccionarRelacionModificarFijo" src="imagenes/modificar.png" style="cursor:pointer; display:block" 
            		onclick="modificarRelacionRetencion(document.getElementById('id_retencion').value, 
                    									'<?=$id_tipo_retencion?>', 
                                                        '', 
                                                        document.getElementById('base_calculo_oculto').value,  
                                                        document.getElementById('sustraendo').value, 
                                                        document.getElementById('factor_oculto').value, 
                                                        document.getElementById('total_retencion_oculto').value,
                                                        '',  
                                                        document.getElementById('porcentaje_impuesto').value, 
                                                        document.getElementById('idrelacion_retenciones').value, 
                                                        document.getElementById('monto_retenido_viejo').value, 
                                                        document.getElementById('codigo_concepto',
                                                        document.getElementById('tipo_pago').value).value)" title="Modificar Fijo">
			<?
		}
		?>        </td>
	  </tr>
      <tr>
      	<td colspan="10" align="right" class='viewPropTitle'><strong>Generar Comprobante de retenci&oacute;n:</strong></td>
		<td align="left" >
        	<input id="generar_comprobante" name="generar_comprobante" type="checkbox" checked="checked" /></td>
      </tr>
	</table>
<?
	}

}














if($ejecutar == "mostrarRetencionModificar"){
	$sql_relacion_retencion = mysql_query("select * from relacion_retenciones where idrelacion_retenciones = '".$idrelacion_retenciones."'");
	$bus_relacion_retencion = mysql_fetch_array($sql_relacion_retencion);
	
	$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$bus_relacion_retencion["idtipo_retencion"]."");
	$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
	
	$sql_retenciones = mysql_query("select * from retenciones where idretenciones = '".$bus_relacion_retencion["idretenciones"]."'");
	$bus_retenciones = mysql_fetch_array($sql_retenciones);

	if($bus_tipo_retencion["monto_fijo"] == 0){
	?>
	<table width="100%" align="center">
	  <tr>
		<td align="center" class='viewPropTitle'>C&oacute;digo</td>
		<td colspan="3" align="center" class='viewPropTitle'>Descripci&oacute;n</td>
		<td align="center" class='viewPropTitle'>C&oacute;digo de Concepto</td>
		<td align="center" class='viewPropTitle'>Base C&aacute;lculo</td>
		<td align="center" class='viewPropTitle'>Porcentaje</td>
		<td align="center" class='viewPropTitle'>Divisor</td>
		<td align="center" class='viewPropTitle'>Sustraendo</td>
		<td align="center" class='viewPropTitle'>Factor</td>
		<td align="center" class='viewPropTitle'>Total Retenci&oacute;n</td>
		<td align="center" class='viewPropTitle'>&nbsp;</td>
	  </tr>
	  <tr>
		<td align="center">
        	<input name="codigo_retencion" type="text" id="codigo_retencion" size="10" disabled value="<?=$bus_tipo_retencion["codigo"]?>">
        </td>
		<td colspan="3" align="center">
        	<input name="descipcion_retencion" type="text" id="descipcion_retencion" 
            						size="50" disabled value="<?=$bus_tipo_retencion["descripcion"]?>">
        </td>
		<td align="center">
		  	<input name="codigo_concepto" type="text" id="codigo_concepto" size="15" value="<?=$bus_relacion_retencion["codigo_concepto"]?>"/>
		</td>
		<td align="center">
        	<input name="base_calculo_retencion" type="text" 
            		id="base_calculo_retencion" size="15" style="text-align:right" 
                    value="<?=number_format($bus_relacion_retencion["base_calculo"],2,",",".")?>" 
                    onblur="formatoNumero(this.name, 'base_calculo_oculto'), restarSustraendo()">
        	<input type="hidden" name="base_calculo_oculto" id="base_calculo_oculto" value="<?=$bus_relacion_retencion["base_calculo"]?>">        
        </td>
		<td align="center">
        	<input name="porcentaje_retencion" type="text" id="porcentaje_retencion" 
            						size="10" style="text-align:right" value="<?=$bus_relacion_retencion["porcentaje_aplicado"]?>">
        </td>
		<td align="center">
        	<input name="divisor_retencion" type="text" id="divisor_retencion" size="10" 
            									style="text-align:right" disabled value="<?=$bus_tipo_retencion["divisor"]?>">
        </td>
		<td align="center">
		  	<input name="sustraendo" type="text" id="sustraendo" size="10" 
            						style="text-align:right" value="<?=number_format($bus_relacion_retencion["sustraendo"],2,",",".")?>" 
                                    onblur="formatoNumero(this.name, 'sustraendo_oculto'), restarSustraendo()"/>
          	<input name="sustraendo_oculto" type="hidden" id="sustraendo_oculto" 
            						size="10" style="text-align:right" value="<?=$bus_relacion_retencion["sustraendo"]?>"/>
		</td>
		<td align="center">
		  	<input name="factor" type="text" id="factor" size="10" style="text-align:right" 
                  						value="<?=number_format($bus_relacion_retencion["factor"],2,",",".")?>" 
                                        onblur="formatoNumero(this.name, 'factor_oculto'), restarSustraendo()"/>
          	<input name="factor_oculto" type="hidden" id="factor_oculto" size="10" style="text-align:right" 
          													value="<?=$bus_relacion_retencion["factor"]?>"/>
          	<input type="hidden" name="total_retencion_oculto" id="total_retencion_oculto" value="<?=$bus_relacion_retencion["monto_retenido"]?>">
		</td>
		<td align="center">
        	<input name="total_retencion" type="text" 
            								id="total_retencion" size="10" 
                                            disabled 
                                            value="<?=number_format($bus_relacion_retencion["monto_retenido"],2,",",".")?>" 
                                            style="text-align:right">
        </td>
		<td align="center">
        	<img id="botonSeleccionarRelacionModificar" src="imagenes/modificar.png" style="cursor:pointer; display:block" 
        		onclick="modificarRelacionRetencion(document.getElementById('id_retencion').value, 
                									'<?=$bus_relacion_retencion["idtipo_retencion"]?>', 
                                                    document.getElementById('porcentaje_retencion').value, 
                                                    document.getElementById('base_calculo_oculto').value, 
                                                    document.getElementById('sustraendo').value, 
                                                    document.getElementById('factor_oculto').value, 
                                                    document.getElementById('total_retencion_oculto').value,
                                                    '', 
                                                    document.getElementById('porcentaje_impuesto').value, 
                                                    document.getElementById('idrelacion_retenciones').value, 
                                                    document.getElementById('monto_retenido_viejo').value, 
                                                    document.getElementById('codigo_concepto').value,
                                                    document.getElementById('tipo_pago').value)" title="Modificar">        </td>
	  </tr>
      <tr>
      	<td colspan="11" align="right" class='viewPropTitle'><strong>Generar Comprobante de retenci&oacute;n:</strong></td>
		<td align="left" >
        	<input id="generar_comprobante" name="generar_comprobante" type="checkbox" <? if ($bus_relacion_retencion["generar_comprobante"]=='si'){ ?> checked="checked" <? } ?> /></td>
      </tr>
	</table>
<?
	}else{
	?>
	
	<table width="100%" align="center">
	  <tr>
		<td align="center" class='viewPropTitle'>C&oacute;digo</td>
		<td colspan="3" align="center" class='viewPropTitle'>Descripci&oacute;n...</td>
		<td align="center" class='viewPropTitle'>C&oacute;digo de Concepto</td>
		<td align="center" class='viewPropTitle'>Base C&aacute;lculo</td>
		<td align="center" class='viewPropTitle'>Monto Fijo</td>
		<td align="center" class='viewPropTitle'>Sustraendo</td>
		<td align="center" class='viewPropTitle'>Factor</td>
		<td align="center" class='viewPropTitle'>Total Retenci&oacute;n</td>
		<td align="center" class='viewPropTitle'>&nbsp;</td>
	  </tr>
	  <tr>
		<td align="center">
        	<input name="codigo_retencion" type="text" id="codigo_retencion" size="10" disabled value="<?=$bus_tipo_retencion["codigo"]?>">
        </td>
		<td colspan="3" align="center">
        	<input name="descipcion_retencion" type="text" id="descipcion_retencion" size="50" 
            									disabled value="<?=$bus_tipo_retencion["descripcion"]?>">
        </td>
		<td align="center">
		  	<input name="codigo_concepto" type="text" id="codigo_concepto" size="15" value="<?=$bus_relacion_retencion["codigo_concepto"]?>"/>
		</td>
		<td align="center">
        	<input name="base_calculo_retencion" type="text" id="base_calculo_retencion" size="15" 
            				value="<?=number_format($bus_relacion_retencion["base_calculo"],2,",",".")?>" 
                            style="text-align:right" onblur="formatoNumero(this.name, 'base_calculo_oculto'), restarSustraendo()">        
        	<input type="hidden" id="base_calculo_oculto" name="base_calculo_oculto" value="<?=$bus_relacion_retencion["base_calculo"]?>">
        </td>
		<td align="center">
        	<input type="hidden" name="monto_fijo_oculto" id="monto_fijo_oculto">
        	<input name="monto_fijo_retencion" type="text" id="monto_fijo_retencion" size="10" 
            								value="<?=$bus_relacion_retencion["monto_retenido"]?>" 
                                            style="text-align:right" onblur="actualizarMontosFijos(this.value)">        
        </td>
		<td align="center">
		  <input name="sustraendo" type="text" id="sustraendo" size="10" disabled="disabled"/>
		</td>
		<td align="center">
		  <input name="factor" type="text" id="factor" size="10" value="<?=number_format($bus_relacion_retencion["factor"],2,",",".")?>" 
          						onblur="formatoNumero(this.name, 'factor_oculto'), restarSustraendo()"/>
          <input name="factor_oculto" type="hidden" id="factor_oculto" value="<?=$bus_relacion_retencion["factor"]?>"/>
		</td>
		<td align="center">
        	<input type="hidden" name="total_retencion_oculto" id="total_retencion_oculto" value="<?=$bus_relacion_retencion["monto_retenido"]?>">
        	<input name="total_retencion" type="text" id="total_retencion" size="10" 
            						disabled style="text-align:right" 
                                    value="<?=number_format($bus_relacion_retencion["monto_retenido"],2,",",".")?>">
        </td>
		<td align="center">
        
			<img id="botonSeleccionarRelacionModificarFijo" src="imagenes/modificar.png" style="cursor:pointer; display:block" 
            		onclick="modificarRelacionRetencion(document.getElementById('id_retencion').value, 
                    									'<?=$bus_relacion_retencion["idtipo_retencion"]?>', 
                                                        '', 
                                                        document.getElementById('base_calculo_oculto').value,  
                                                        document.getElementById('sustraendo').value, 
                                                        document.getElementById('factor_oculto').value, 
                                                        document.getElementById('total_retencion_oculto').value,
                                                        '',  
                                                        document.getElementById('porcentaje_impuesto').value, 
                                                        document.getElementById('idrelacion_retenciones').value, 
                                                        document.getElementById('monto_retenido_viejo').value, 
                                                        document.getElementById('codigo_concepto').value,
                                                        document.getElementById('tipo_pago').value)" title="Modificar Fijo">        </td>
	  </tr>
      <tr>
      	<td colspan="10" align="right" class='viewPropTitle'><strong>Generar Comprobante de retenci&oacute;n:</strong></td>
		<td align="left" >
        	<input id="generar_comprobante" name="generar_comprobante" type="checkbox" <? if ($bus_relacion_retencion["generar_comprobante"]=='si'){ ?> checked="checked" <? } ?> /></td>
      </tr>
      
	</table>
<?
	}

}



if($ejecutar == "registrarDatosBasicos"){
	//echo "select * from where iddocumento = '".$iddocumento."'";
	$sql_validar_existencia = mysql_query("select * from retenciones where iddocumento = '".$iddocumento."' order by idretenciones DESC")or die(mysql_error());
	$num_validar_existencia = mysql_num_rows($sql_validar_existencia);
	$bus_validar_existencia = mysql_fetch_array($sql_validar_existencia);
	if($bus_validar_existencia["tipo_pago"] != "total"){
			if($num_validar_existencia == 0){
					$sql_insertar_datos_basicos = mysql_query("insert into retenciones (fecha_retencion,
																						iddocumento,
																						anio_documento,
																						anio_proceso,
																						numero_documento,
																						numero_factura,
																						numero_control,
																						fecha_factura,
																						exento,
																						base,
																						impuesto,
																						porcentaje_impuesto,
																						total,
																						status,
																						fechayhora,
																						usuario,
																						tipo_pago,
																						estado)value('".$fecha_validada."',
																										'".$iddocumento."',
																										'".$anio."',
																										'".$_SESSION["anio_fiscal"]."',
																										'".$numero_documento."',
																										'".$numero_factura."',
																										'".$numero_control."',
																										'".$fecha_factura."',
																										'".$exento."',
																										'".$base."',
																										'".$impuesto."',
																										'".$porcentaje_impuesto."',
																										'".$total."',
																										'a',
																										'".$fh."',
																										'".$login."',
																										'".$tipo_pago."',
																										'".$estado."')");
				echo mysql_insert_id();
			}else{
				//$bus_validar_existencia = mysql_fetch_array($sql_validar_existencia);
				if($bus_validar_existencia["estado"] == 'procesado' or $bus_validar_existencia["estado"] == 'pagada'){
					$sql_insertar_datos_basicos = mysql_query("insert into retenciones (fecha_retencion,
																						iddocumento,
																						anio_documento,
																						anio_proceso,
																						numero_documento,
																						numero_factura,
																						numero_control,
																						fecha_factura,
																						exento,
																						base,
																						impuesto,
																						porcentaje_impuesto,
																						total,
																						status,
																						fechayhora,
																						usuario,
																						tipo_pago,
																						estado)value('".$fecha_validada."',
																										'".$iddocumento."',
																										'".$anio."',
																										'".$_SESSION["anio_fiscal"]."',
																										'".$numero_documento."',
																										'".$numero_factura."',
																										'".$numero_control."',
																										'".$fecha_factura."',
																										'".$exento."',
																										'".$base."',
																										'".$impuesto."',
																										'".$porcentaje_impuesto."',
																										'".$total."',
																										'a',
																										'".$fh."',
																										'".$login."',
																										'".$tipo_pago."',
																										'".$estado."')")or die(mysql_error());
					echo mysql_insert_id();
				}else{
					$sql_update = mysql_query("update retenciones set exento = '".$exento."',
																		base = '".$base."',
																		impuesto = '".$impuesto."',
																		total = '".$total."',
																		numero_factura = '".$numero_factura."', 
																		numero_control= '".$numero_control."', 
																		fecha_factura = '".$fecha_factura."' 
																		where idretenciones = ".$bus_validar_existencia["idretenciones"]."");
					echo $bus_validar_existencia["idretenciones"];
				}
			}
		}else{
			echo $bus_validar_existencia["idretenciones"];
		}
}






if($ejecutar == "registrarRetencion"){
	$sql_consulta = mysql_query("select * from relacion_retenciones where 
																	idtipo_retencion = '".$idtipo_retencion."' 
																	and idretenciones = '".$idretenciones."'");
	$num_consulta = mysql_num_rows($sql_consulta);
	if($num_consulta == 0){
		$paso = 'si';
		if ($tipo_pago == 'parcial'){
			//****************************************************************************************************************
			// DETERMINAR SI YA SE RETUBO EL TOTAL PARA ESE TIPO DE RETENCION
			// ***************************************************************************************************************
			$sql_retenido = mysql_query("select SUM(monto_retenido) as total_retenido from relacion_retenciones
         											INNER JOIN retenciones on (relacion_retenciones.idretenciones = retenciones.idretenciones)
														where relacion_retenciones.idtipo_retencion = '".$idtipo_retencion."'
															and retenciones.iddocumento = '".$iddocumento."'")or die(mysql_error());
			$bus_retenido = mysql_fetch_array($sql_retenido);
			$total_retenido = $bus_retenido["total_retenido"] + $monto_retenido;
			if ($total_retenido > 0){
				$sql_compromiso = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$iddocumento."'");
				$bus_compromiso = mysql_fetch_array($sql_compromiso);
				$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$idtipo_retencion."'");
				$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
				
				if($bus_tipo_retencion["base_calculo"] == "Exento"){
					$base = $bus_compromiso["exento"];
				}else if($bus_tipo_retencion["base_calculo"] == "Base Imponible"){
					$base = $bus_compromiso["sub_total"];
				}else if($bus_tipo_retencion["base_calculo"] == "IVA"){
					$base = $bus_compromiso["impuesto"];
				}else if($bus_tipo_retencion["base_calculo"] == "Total"){
					$base = $bus_compromiso["total"];
				}
				
				$factor = ($bus_tipo_retencion["unidad_tributaria"]*$bus_tipo_retencion["porcentaje"]/100)*$bus_tipo_retencion["factor_calculo"];
				if ($bus_tipo_retencion["divisor"] > 0){
					$maximo_retener = (($base*$bus_tipo_retencion["porcentaje"])/$bus_tipo_retencion["divisor"])-$factor;
				}else{
					$maximo_retener = $total_retenido;
				}
				//echo 'total retenido '.round($total_retenido,2).' maximo retener '.round($maximo_retener,2);
				/*if (round($total_retenido,2) > round($maximo_retener,2)){
					if ($bus_tipo_retencion["nombre_comprobante"] <> 'NA') {
						echo "supera";
						$paso = 'no';
					}else{
						$paso = 'si';
					}
				}else{*/
					$paso = 'si';
				//}
			}else{
				$paso = 'si';
			}
			//*******************************************************************************************************************
		}
		if ($paso == 'si'){
			$query = "insert into relacion_retenciones (idretenciones,
																	idtipo_retencion,
																	porcentaje_aplicado,
																	base_calculo,
																	sustraendo,
																	factor,
																	monto_retenido,
																	numero_retencion,
																	codigo_concepto,
																	porcentaje_impuesto,
																	status,
																	fechayhora,
																	usuario,
																	generar_comprobante)value('".$idretenciones."',
																				'".$idtipo_retencion."',
																				'".$porcentaje_aplicado."',
																				'".$base_calculo."',
																				'".$sustraendo."',
																				'".$factor."',
																				'".$monto_retenido."',
																				'0',
																				'".$codigo_concepto."',
																				'".$porcentaje_impuesto."',
																				'a',
																				'".$fh."',
																				'".$login."',
																				'".$generar_comprobante."')";
			$sql_insertar_retencion = mysql_query($query)or die($query." ERROR MYSQL -> ".mysql_error());
		
			$sql_actualizar_monto = mysql_query("update retenciones set total_retenido = total_retenido + ".$monto_retenido." where idretenciones = ".$idretenciones."");
	
																				
		if($sql_insertar_retencion){
			registra_transaccion("Asignar retencion (Codigo Retencion ".$idretenciones.", Documento: ".$iddocumento." )",$login,$fh,$pc,'retenciones');
		}else{
			registra_transaccion("Asignar retencion ERROR (Codigo Retencion ".$idretenciones.", Documento: ".$iddocumento.")",$login,$fh,$pc,'retenciones');
	
		}
			//if ($anio == $_SESSION["anio_fiscal"]){
				$sql_retenciones = mysql_query("select * from retenciones where iddocumento = ".$iddocumento." order by anio_proceso  DESC,idretenciones");
			/*}elseif ($anio < $_SESSION["anio_fiscal"]){
				$sql_retenciones = mysql_query("(select * from retenciones where iddocumento = ".$iddocumento.")
												UNION
												(select * from retenciones where iddocumento = ".$iddocumento.")
												 order by anio_proceso DESC ,idretenciones")or die(mysql_error());
			}*/
			//echo "select * from retenciones where idretenciones = ".$idretenciones."";
			$ii =0;
			while($bus_retenciones = mysql_fetch_array($sql_retenciones)){
				$sql_consulta = mysql_query("select * from relacion_retenciones where idretenciones = '".$bus_retenciones["idretenciones"]."'");	
			
			
				?>
				
				<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
				<tr>
					<td align="center">
								
									
						<table width="100%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
						<?
						
						if($i == 0){
						?>
							  <thead style=" background-image:none; background-color: #006699">
								<tr>
									<td align="center" class="Browse" colspan="11">
										<strong style="color:#FFFFFF">Lista de Retenciones Aplicadas para este documento</strong>
									</td>
								</tr>
							</thead>
						<?
						$i++;
						}
						
						?>
								<thead style=" background-image:none; background-color:#FFFFCC">
								<tr>
									<td align="left" colspan="11">
										
										<strong>Nro. Factura:</strong>
										<input type="text" name="nro_factura_actualizar<?=$bus_retenciones["idretenciones"]?>" id="nro_factura_actualizar<?=$bus_retenciones["idretenciones"]?>" value="<?=$bus_retenciones["numero_factura"]?>">
		
		
		
										<strong> Nro. de Control:</strong> 
	
											<input type="text" name="nro_control_actualizar<?=$bus_retenciones["idretenciones"]?>" id="nro_control_actualizar<?=$bus_retenciones["idretenciones"]?>" value="<?=$bus_retenciones["numero_control"]?>">
	
										<strong> F. Factura:</strong> 
										
											<input type="text" name="fecha_factura_actualizar<?=$bus_retenciones["idretenciones"]?>" id="fecha_factura_actualizar<?=$bus_retenciones["idretenciones"]?>" readonly="readonly" size="15" value="<?=$bus_retenciones["fecha_factura"]?>"> 
											<img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_retenciones["idretenciones"]?>" width="16" height="16" id="f_trigger_c<?=$bus_retenciones["idretenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onclick="
												Calendar.setup({
												inputField    : 'fecha_factura_actualizar<?=$bus_retenciones["idretenciones"]?>',
												button        : 'f_trigger_c<?=$bus_retenciones["idretenciones"]?>',
												align         : 'Tr',
												ifFormat      : '%Y-%m-%d'
												});
											"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									 <strong><img src="imagenes/refrescar.png" onclick="actualizarFechaNro(
											document.getElementById('nro_factura_actualizar<?=$bus_retenciones["idretenciones"]?>').value,
											document.getElementById('nro_control_actualizar<?=$bus_retenciones["idretenciones"]?>').value,
											document.getElementById('fecha_factura_actualizar<?=$bus_retenciones["idretenciones"]?>').value,
											'<?=$bus_retenciones["idretenciones"]?>')" style="cursor:pointer" title="Actualizar Datos de Factura"/>                                </strong>
									 <br />
									 <br />
										<strong>Exento:</strong> <?=number_format($bus_retenciones["exento"],2,",",".")?>
										<strong>| Base:</strong> <?=number_format($bus_retenciones["base"],2,",",".")?>
										<strong>| Impuesto:</strong> <?=number_format($bus_retenciones["impuesto"],2,",",".")?>
										<strong>| Total:</strong> <?=number_format($bus_retenciones["total"],2,",",".")?>
										<strong>| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total esta Retenci&oacute;n:</strong> <?=number_format($bus_retenciones["total_retenido"],2,",",".")?>
								  </td>
								</tr>
								</thead>
						
						 <thead>
								<tr>
									<td align="center" class="Browse">Tipo de Retencion</td>
									<td align="center" class="Browse">Descripcion</td>
									<td align="center" class="Browse">Monto Retenido</td>
									<?
										if($bus_retenciones["estado"] == "elaboracion"){
									?>
									<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
									<?
									}
									?>
								</tr>
							</thead>
							<?
							while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<td class='Browse'>
								<?
								$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consulta["idtipo_retencion"]."'");
								$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
								?>
								<?=$bus_tipo_retencion["codigo"]?>
								
								</td>
								<td class='Browse'><?=$bus_tipo_retencion["descripcion"]?></td>
								<td align="right" class='Browse'><?=number_format($bus_consulta["monto_retenido"],2,",",".")?></td>
							  
							<?
							if($bus_retenciones["estado"] == "elaboracion"){
							?>
							  <td class='Browse' align="center">
								<img style="cursor:pointer" src="imagenes/modificar.png" onClick="mostrarRetencionModificar('<?=$bus_consulta["idrelacion_retenciones"]?>'), document.getElementById('monto_retenido_viejo').value = '<?=$bus_consulta["monto_retenido"]?>', document.getElementById('idrelacion_retenciones').value = '<?=$bus_consulta["idrelacion_retenciones"]?>'">
							  </td>
								<td class='Browse' align="center"><img style="cursor:pointer" src="imagenes/delete.png" onClick="eliminarRelacionRetencion('<?=$bus_consulta["idrelacion_retenciones"]?>')"></td>
							<?
							}
							?>
							
							
							
						  </tr>
							<?
							}
							?>
						</table>				  
					</td>
				</tr>
				</table>
				<?
				if($bus_retenciones["estado"] == "elaboracion"){
				?>
					<div><input type="button" name="procesarRetencion" id="procesarRetencion" value="Procesar Retencion" class="button" onclick="procesarRetencion('<?=$bus_retenciones["idretenciones"]?>', document.getElementById('iddocumento').value)"></div>
				<?
				}else{
					?> <div> <? 
					$sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion, 
																			orden_pago 
																			where relacion_orden_pago_retencion.idretencion = '".$bus_retencion["idretenciones"]."'
																						and orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago
																						and orden_pago.estado <> 'anulado'");
					$bus_relacion_pago_retencion = mysql_fetch_array($sql_relacion_pago_retencion);
					$sql_estado_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago_retencion["idorden_pago"]."'");
					$num_estado_pago = mysql_num_rows($sql_estado_pago);
					if ($num_estado_pago > 0){
						$bus_estado_pago = mysql_fetch_array($sql_estado_pago);
						if ($bus_estado_pago["estado"] != 'procesado' && $bus_estado_pago["estado"] != 'pagada' && $bus_estado_pago["estado"] != 'conformado' && $bus_estado_pago["estado"] != 'parcial'){
				?>
						<input type="button" name="anularRetencion" id="anularRetencion" value="Anular Retencion" class="button" onclick="anularRetencion('<?=$bus_retencion["idretenciones"]?>')">
					  <? } 
					}else{ ?>
						<input type="button" name="anularRetencion" id="anularRetencion" value="Anular Retencion" class="button" onclick="anularRetencion('<?=$bus_retencion["idretenciones"]?>')">
					<? } ?>
					
	<input type="button" name="imprimirRetencion" id="imprimirRetencion1" value="Imprimir Retencion" class="button" onclick="document.getElementById('ventanaPDF1').src='lib/reportes/tributos/reportes.php?nombre=registro_retenciones&id_retencion=<?=$bus_retencion["idretenciones"]?>'; document.getElementById('ventanaPDF1').style.display='block'; document.getElementById('divImprimir1').style.display='block';" />
					<div id="divImprimir1" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; top:50px;">
					<table align="center">
						<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir1').style.display='none';">X</a></td></tr>
						<tr><td><iframe name="ventanaPDF1" id="ventanaPDF1" style="display:none" height="600" width="750"></iframe></td></tr>
					</table>
					</div>
					</div>
					
				<?
				}
		
				}
		}
	}else{
	 	echo "retencionYaAplicada"; 
	}
	
}













if($ejecutar == "mostrarListaRetencionesAplicadas"){
	//echo " anio ".$anio. " session ".$_SESSION["anio_fiscal"];
	//if ($anio == $_SESSION["anio_fiscal"]){
		//echo "entro 0";
		$sql_retenciones = mysql_query("select * from retenciones where iddocumento = ".$iddocumento." order by anio_proceso  DESC,idretenciones ")or die("aqui0".mysql_error());
	/*}
	if ($anio < $_SESSION["anio_fiscal"]){
		$sql_retenciones = mysql_query("(select * retenciones where iddocumento = ".$iddocumento.")
										UNION
										(select * from retenciones where iddocumento = ".$iddocumento.")
										order by anio_proceso  DESC,idretenciones")or die("aqui1".mysql_error());
	}*/
	
	$i = 0;
	while($bus_retencion = mysql_fetch_array($sql_retenciones)){
		$sql_consulta = mysql_query("select * from relacion_retenciones where idretenciones = '".$bus_retencion["idretenciones"]."'");	
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta != 0){	
		//echo ' año documento '.$bus_retencion["anio_documento"].' año proceso '.$bus_retencion["anio_proceso"].' id ' .$bus_retencion["idretenciones"];
			?>
			
			<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
			<tr>
				<td align="center">
								
								
                    <table width="100%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
         			     <?
                         if($i == 0){
						 ?>
                          <thead style=" background-image:none; background-color: #006699">
                            <tr>
                                <td align="center" class="Browse" colspan="11">
                                	<strong style="color:#FFFFFF">Lista de Retenciones Aplicadas para este documento</strong>
                                </td>
                            </tr>
                        </thead>
                        <?
                        $i++;
						}
						?>
                          <thead style="background-image:none; background-color: #FFFFCC">
                            <tr>
                                <td align="left" class="Browse" colspan="11">
                                	<strong>Nro. Factura:</strong> 
										<input type="text" name="nro_factura_actualizar<?=$bus_retencion["idretenciones"]?>" id="nro_factura_actualizar<?=$bus_retencion["idretenciones"]?>" value="<?=$bus_retencion["numero_factura"]?>">
	
    
    
                                    <strong> Nro. de Control:</strong> 

										<input type="text" name="nro_control_actualizar<?=$bus_retencion["idretenciones"]?>" id="nro_control_actualizar<?=$bus_retencion["idretenciones"]?>" value="<?=$bus_retencion["numero_control"]?>">

                                    <strong> F. Factura:</strong> 
                                    
                                        <input type="text" name="fecha_factura_actualizar<?=$bus_retencion["idretenciones"]?>" id="fecha_factura_actualizar<?=$bus_retencion["idretenciones"]?>" readonly="readonly" size="15" value="<?=$bus_retencion["fecha_factura"]?>"> 
                                        <img src="imagenes/jscalendar0.gif" name="f_trigger_c<?=$bus_retencion["idretenciones"]?>" width="16" height="16" id="f_trigger_c<?=$bus_retencion["idretenciones"]?>" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" onclick="
                                            Calendar.setup({
                                            inputField    : 'fecha_factura_actualizar<?=$bus_retencion["idretenciones"]?>',
                                            button        : 'f_trigger_c<?=$bus_retencion["idretenciones"]?>',
                                            align         : 'Tr',
                                            ifFormat      : '%Y-%m-%d'
                                            });
                                            "/>
										
                                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;<img src="imagenes/refrescar.png" onclick="actualizarFechaNro(
                                                document.getElementById('nro_factura_actualizar<?=$bus_retencion["idretenciones"]?>').value,
                                                document.getElementById('nro_control_actualizar<?=$bus_retencion["idretenciones"]?>').value,
                                                document.getElementById('fecha_factura_actualizar<?=$bus_retencion["idretenciones"]?>').value,
                                                '<?=$bus_retencion["idretenciones"]?>')" style="cursor:pointer" title="Actualizar Datos de Factura"/></strong>
                                    <br />
                                    <br />
                                    <strong> Exento:</strong> <?=number_format($bus_retencion["exento"],2,",",".")?>
                                    <strong>| Base:</strong> <?=number_format($bus_retencion["base"],2,",",".")?>
                                    <strong>| Impuesto:</strong> <?=number_format($bus_retencion["impuesto"],2,",",".")?>
                                    <strong>| Total:</strong> <?=number_format($bus_retencion["total"],2,",",".")?>
                                    <strong>| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total esta Retenci&oacute;n:</strong> <?=number_format($bus_retencion["total_retenido"],2,",",".")?>
                              &nbsp;</td>
                            </tr>
                        </thead>
                        
                     <thead>
                            <tr>
                                <td align="center" class="Browse">Tipo de Retencion</td>
                                <td align="center" class="Browse">Descripcion</td>
                                <td align="center" class="Browse">Monto Retenido</td>
                                            <?
								if($bus_retencion["estado"] == "elaboracion"){
								?>
                                	<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                <?
                                }
								?>
                            </tr>
                        </thead>
                        <?
                        while($bus_consulta = mysql_fetch_array($sql_consulta)){
                        ?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                            <td class='Browse'>
							<?
                            $sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$bus_consulta["idtipo_retencion"]."'");
							$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
							?>
							<?=$bus_tipo_retencion["codigo"]?>
                            
                            </td>
                            <td class='Browse'><?=$bus_tipo_retencion["descripcion"]?></td>
                            <td align="right" class='Browse'><?=number_format($bus_consulta["monto_retenido"],2,",",".")?></td>
                       	   
                           
                           
                            <?
							if($bus_retencion["estado"] == "elaboracion"){
							?>
                          <td class='Browse' align="center"><img style="cursor:pointer" src="imagenes/modificar.png" onClick=" mostrarRetencionModificar('<?=$bus_consulta["idrelacion_retenciones"]?>'), document.getElementById('monto_retenido_viejo').value = '<?=$bus_consulta["monto_retenido"]?>', document.getElementById('idrelacion_retenciones').value = '<?=$bus_consulta["idrelacion_retenciones"]?>'"></td>
                            <td class='Browse' align="center"><img style="cursor:pointer" src="imagenes/delete.png" onClick="eliminarRelacionRetencion('<?=$bus_consulta["idrelacion_retenciones"]?>')"></td>
                            
                            <?
                            }
							?>
                            
                            
                            
                            
                      </tr>
                        <?
                        }
                        ?>
                    </table>				  
                </td>
			</tr>
			</table>
            <?
			if($bus_retencion["estado"] == "elaboracion"){
			?>
				<div><input type="button" name="procesarRetencion" id="procesarRetencion" value="Procesar Retencion" class="button" onclick="procesarRetencion('<?=$bus_retencion["idretenciones"]?>', document.getElementById('iddocumento').value)"></div>
			<?
			}else{
				?> <div> <? 
				$sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion, 
																			orden_pago 
																			   	where relacion_orden_pago_retencion.idretencion = '".$bus_retencion["idretenciones"]."'
																					and orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago
																					and orden_pago.estado <> 'anulado'");
				$bus_relacion_pago_retencion = mysql_fetch_array($sql_relacion_pago_retencion);
				$sql_estado_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago_retencion["idorden_pago"]."'");
				$num_estado_pago = mysql_num_rows($sql_estado_pago);
				//echo "select * from gestion_".$bus_retencion["anio_proceso"].".orden_pago where idorden_pago = '".$bus_relacion_pago_retencion["idorden_pago"]."'";
				if ($num_estado_pago > 0){
					$bus_estado_pago = mysql_fetch_array($sql_estado_pago);
					if ($bus_estado_pago["estado"] != 'procesado' && $bus_estado_pago["estado"] != 'pagada' && $bus_estado_pago["estado"] != 'conformado' && $bus_estado_pago["estado"] != 'parcial'){
			?>
	            	<input type="button" name="anularRetencion" id="anularRetencion" value="Anular Retencion" class="button" onclick="anularRetencion('<?=$bus_retencion["idretenciones"]?>')">
                  <? } 
				}else{ ?>
                	<input type="button" name="anularRetencion" id="anularRetencion" value="Anular Retencion" class="button" onclick="anularRetencion('<?=$bus_retencion["idretenciones"]?>')">
                <? } ?>
                
				<input type="button" name="imprimirRetencion" id="imprimirRetencion1" value="Imprimir Retencion" class="button" onclick="document.getElementById('ventanaPDF1').src='lib/reportes/tributos/reportes.php?nombre=registro_retenciones&id_retencion=<?=$bus_retencion["idretenciones"]?>'; document.getElementById('ventanaPDF1').style.display='block'; document.getElementById('divImprimir1').style.display='block';" />
				<div id="divImprimir1" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; top:50px;">
				<table align="center">
					<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir1').style.display='none';">X</a></td></tr>
					<tr><td><iframe name="ventanaPDF1" id="ventanaPDF1" style="display:none" height="600" width="750"></iframe></td></tr>
				</table>
				</div>
</div>
			<?
			}
	}		
	}
}
?>






<?
if($ejecutar == "procesarRetencion"){
	$sql_retenciones = mysql_query("select * from retenciones where iddocumento = '".$iddocumento."'");
	$num_retenciones = mysql_num_rows($sql_retenciones);
	$contador = ($num_retenciones-1) + 1;
	/*if ($anio < $_SESSION["anio_fiscal"]){
		$sql_retenciones = mysql_query("select * from retenciones where iddocumento = '".$iddocumento."'");
		$num_retenciones = mysql_num_rows($sql_retenciones);
		$contador = $contador + (($num_retenciones-1) + 1);
	}*/
	$sql_procesar = mysql_query("update retenciones set contador_retencion = '".$contador."',  estado = 'procesado' where idretenciones = '".$idretencion."'");
	//echo "update retenciones set contador_retencion = '".$contador."',  estado = 'procesado' where idretenciones = '".$idretencion."'";
	if($sql_procesar){
		echo "exito";
		registra_transaccion("Procesar Retencion (RETENCION: ".$idretencion.")",$login,$fh,$pc,'retenciones');
	}else{
		echo "fallo";
		registra_transaccion("Procesar Retencion ERROR (RETENCION: ".$idretencion.")",$login,$fh,$pc,'retenciones');
	}
}
?>





<?
if($ejecutar == "anularRetencion"){
	echo "AQUI";
	$sql_anular_relacion = mysql_query("delete from relacion_retenciones where idretenciones = ".$idretencion."")or die(mysql_error());
	$sql_anular = mysql_query("delete from retenciones where idretenciones = ".$idretencion."")or die(mysql_error());
	if($sql_anular and  $sql_anular_relacion){
		echo "exito";
		registra_transaccion("Anular Retencion (RETENCION: ".$idretencion.")",$login,$fh,$pc,'retenciones');
	}else{
		echo "fallo";
		registra_transaccion("Anular Retencion  ERROR (RETENCION: ".$idretencion.")",$login,$fh,$pc,'retenciones');
	}
}
?>




<?
if($ejecutar == "eliminarRelacionRetencion"){
	$sql_relacion = mysql_query("select * from relacion_retenciones where idrelacion_retenciones = '".$relacionRetenciones."'");
	$bus_relacion = mysql_fetch_array($sql_relacion);
	
	$sql_actualizar_monto = mysql_query("update retenciones set total_retenido = total_retenido - '".$bus_relacion["monto_retenido"]."' where idretenciones = ".$bus_relacion["idretenciones"]."");
	
	$sql_anular_relacion_retencion = mysql_query("delete from relacion_retenciones where idrelacion_retenciones = ".$relacionRetenciones."")or die(mysql_error());
	
	$sql_select_documento = mysql_query("select * from retenciones where idretenciones = ".$bus_relacion["idretenciones"]."");
	$bus_select_documento = mysql_fetch_array($sql_select_documento);
	$iddocumento = $bus_select_documento["iddocumento"];
	
	$sql_total_relaciones = mysql_query("select * from relacion_retenciones where idretenciones = ".$bus_relacion["idretenciones"]."");
	$num_total_relaciones = mysql_num_rows($sql_total_relaciones);
		if($num_total_relaciones == 0){
			$sql_eliminar_retencion = mysql_query("delete from retenciones where idretenciones = ".$bus_relacion["idretenciones"]."");
			$vacio = "si";
		}
		
	$sql_retenciones = mysql_query("select * from retenciones where iddocumento = ".$iddocumento."");
	while($bus_retenciones = mysql_fetch_array($sql_retenciones)){
		$total_retenido += $bus_retenciones["total_retenido"];
	}
	
	if($sql_anular_relacion_retencion){
		echo "exito-".$total_retenido."-".$vacio;
		registra_transaccion("Eliminar Relacion Retencion (RELACION: ".$relacionRetencion.")",$login,$fh,$pc,'retenciones');
	}else{
		echo "fallo";
		registra_transaccion("Eliminar Relacion Retencion ERROR (RELACION: ".$relacionRetencion.")",$login,$fh,$pc,'retenciones');
	}
}








if($ejecutar == "modificarRelacionRetencion"){
	
	$paso = 'si';
		if ($tipo_pago == 'parcial'){
			//****************************************************************************************************************
			// DETERMINAR SI YA SE RETUBO EL TOTAL PARA ESE TIPO DE RETENCION
			// ***************************************************************************************************************
			$sql_retenido = mysql_query("select SUM(monto_retenido) as total_retenido from relacion_retenciones
         											INNER JOIN retenciones on (relacion_retenciones.idretenciones = retenciones.idretenciones)
														where relacion_retenciones.idtipo_retencion = '".$idtipo_retencion."'
															and retenciones.iddocumento = '".$iddocumento."'");
			$bus_retenido = mysql_fetch_array($sql_retenido);
			$total_retenido = $bus_retenido["total_retenido"] + $monto_retenido;
			if ($total_retenido > 0){
				$sql_compromiso = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio = '".$iddocumento."'");
				$bus_compromiso = mysql_fetch_array($sql_compromiso);
				$sql_tipo_retencion = mysql_query("select * from tipo_retencion where idtipo_retencion = '".$idtipo_retencion."'");
				$bus_tipo_retencion = mysql_fetch_array($sql_tipo_retencion);
				
				if($bus_tipo_retencion["base_calculo"] == "Exento"){
					$base = $bus_compromiso["exento"];
				}else if($bus_tipo_retencion["base_calculo"] == "Base Imponible"){
					$base = $bus_compromiso["sub_total"];
				}else if($bus_tipo_retencion["base_calculo"] == "IVA"){
					$base = $bus_compromiso["impuesto"];
				}else if($bus_tipo_retencion["base_calculo"] == "Total"){
					$base = $bus_compromiso["total"];
				}
				
				$factor = ($bus_tipo_retencion["unidad_tributaria"]*$bus_tipo_retencion["porcentaje"]/100)*$bus_tipo_retencion["factor_calculo"];
				$maximo_retener = (($base*$bus_tipo_retencion["porcentaje"])/$bus_tipo_retencion["divisor"])-$factor;
				
				if ($total_retenido > $maximo_retener){
					echo "supera";
					$paso = 'no';
				}else{
					$paso = 'si';
				}
			}else{
				$paso = 'si';
			}
			//*******************************************************************************************************************
		}
	if ($paso == 'si'){
		$sql_actualizar_relaciones = mysql_query("update relacion_retenciones set idtipo_retencion = '".$idtipo_retencion."',
																porcentaje_aplicado = '".$porcentaje_aplicado."',
																base_calculo = '".$base_calculo."',
																sustraendo = '".$sustraendo."',
																factor = '".$factor."',
																monto_retenido = '".$monto_retenido."',
																numero_retencion = '0',
																codigo_concepto = '".$codigo_concepto."',
																porcentaje_impuesto = '".$porcentaje_impuesto."',
																status = 'a',
																fechayhora = '".$fh."',
																usuario = '".$login."',
																generar_comprobante = '".$generar_comprobante."' where idrelacion_retenciones = '".$idrelacion_retenciones."'")or die(mysql_error());
	
		$sql_buscar_id= mysql_query("select * from relacion_retenciones where idrelacion_retenciones = '".$idrelacion_retenciones."'");
		//echo "select * from relacion_retenciones where idrelacion_retenciones = '".$idrelacion_retenciones."'";
		$bus_buscar_id = mysql_fetch_array($sql_buscar_id);															
		echo "update retenciones set total_retenido = (total_retenido - ".$monto_retenido_viejo.") + ".$monto_retenido." where idretenciones = '".$bus_buscar_id["idretenciones"]."'";
	$sql_actualizar_retencion = mysql_query("update retenciones set total_retenido = (total_retenido - ".$monto_retenido_viejo.") + ".$monto_retenido." where idretenciones = '".$bus_buscar_id["idretenciones"]."'")or die(mysql_error());
	
	if($sql_actualizar_relaciones){
		echo "exito";
		registra_transaccion("Actualizar Relacion de Retencion (RELACION: ".$relacionRetencion.")",$login,$fh,$pc,'retenciones');
	}else{
		echo "fallo";
		registra_transaccion("Actualizar Relacion de Retencion ERROR (RELACION: ".$relacionRetencion.")",$login,$fh,$pc,'retenciones');
	}
	}
}










if($ejecutar == "consultarTipoPago"){
	$sql_retenciones = mysql_query("select * from retenciones where iddocumento = '".$iddocumento."'");
	$bus_retenciones = mysql_fetch_array($sql_retenciones);
	if($bus_retenciones["tipo_pago"] == $tipo_pago){
		echo "exito";
	}else{
		echo "fallo";
	}
}





if($ejecutar == "actualizarMontoRetenido"){
	$sql_relacion_retencion = mysql_query("select * from relacion_retenciones where idrelacion_retenciones = ".$idrelacion_retenciones."");
	$bus_relacion_retencion = mysql_fetch_array($sql_relacion_retencion);
	$sql_retenciones = mysql_query("select * from retenciones where idretenciones = ".$bus_relacion_retencion["idretenciones"]."");
	$bus_retenciones = mysql_fetch_array($sql_retenciones);
	echo $bus_retenciones["total_retenido"];
}





if($ejecutar == "refrescarListaDocumentos"){

			
			$sql_configuracion = mysql_query("select * from configuracion_tributos");
			$bus_configuracion = mysql_fetch_array($sql_configuracion);
			$id_dependencia = $bus_configuracion["iddependencia"];
			
			if($_SESSION["version"] == "completo"){
				/*
				$sql_consulta = "(SELECT
												  *
								 FROM
								  orden_compra_servicio ocs,
								  beneficiarios
								 WHERE
								  ocs.iddependencia IN (SELECT modulo
														FROM agrupacion_modulos
														WHERE grupo = (SELECT grupo
																	   FROM agrupacion_modulos
																	   WHERE modulo = '".$id_dependencia."')) AND
								  ocs.idorden_compra_servicio NOT IN (SELECT id_documento
																	  FROM relacion_documentos_remision
																	  WHERE
																		tabla = 'orden_compra_servicio' AND
																		iddependencia_origen = ocs.iddependencia) AND
								  ocs.estado <> 'elaboracion'
								  and ocs.estado <> 'anulado'
								  and ocs.estado <> 'ordenado'
								  and ocs.estado <> 'pagado'
								  and beneficiarios.idbeneficiarios = ocs.idbeneficiarios)
												
												UNION
												
												(SELECT
												  * FROM
												  orden_compra_servicio ocs, beneficiarios
												 WHERE
												  ocs.idorden_compra_servicio IN (SELECT rdr2.id_documento
										FROM
											recibir_documentos rd2
										INNER JOIN relacion_documentos_recibidos rdr2 ON (rd2.idrecibir_documentos = rdr2.idrecibir_documentos)
										WHERE
											rdr2.tabla = 'orden_compra_servicio' AND
											rd2.iddependencia_recibe IN (SELECT modulo
																	 FROM agrupacion_modulos
																	 WHERE grupo = (SELECT grupo
																					FROM agrupacion_modulos
																					WHERE modulo = '".$id_dependencia."'))
									 ) AND
												  ocs.estado <> 'elaboracion'
												  and ocs.estado <> 'anulado'
												  and ocs.estado <> 'ordenado'
												  and ocs.estado <> 'pagado'
												  and beneficiarios.idbeneficiarios = ocs.idbeneficiarios
												)
												UNION
												(SELECT
												  * FROM
												  orden_compra_servicio ocs, beneficiarios
												 WHERE
												  ocs.idorden_compra_servicio IN (SELECT rdr2.id_documento
										FROM
											recibir_documentos rd2
										INNER JOIN relacion_documentos_recibidos rdr2 ON (rd2.idrecibir_documentos = rdr2.idrecibir_documentos)
										WHERE
											rdr2.tabla = 'orden_compra_servicio' AND
											rd2.iddependencia_recibe = '".$id_dependencia."'
									 ) AND
												  ocs.estado <> 'elaboracion'
												  and ocs.estado <> 'anulado'
												  and ocs.estado <> 'ordenado'
												  and ocs.estado <> 'pagado' 
												  and beneficiarios.idbeneficiarios = ocs.idbeneficiarios
												) 
								order by codigo_referencia";
				
				
				/*"select * from 
												orden_compra_servicio,
												relacion_documentos_remision, 
												remision_documentos,
												beneficiarios,
												recibir_documentos
													where 
												(orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' 
												or orden_compra_servicio.estado = 'procesado'
												or orden_compra_servicio.estado = 'parcial')
												and (orden_compra_servicio.ubicacion = 'recibido' 
												or orden_compra_servicio.ubicacion = 'conformado'
												or orden_compra_servicio.ubicacion = '0')
												and orden_compra_servicio.numero_orden like '%".$campoBuscar."%'
												and orden_compra_servicio.idorden_compra_servicio = relacion_documentos_remision.id_documento
												and relacion_documentos_remision.idremision_documentos = remision_documentos.idremision_documentos
												
												and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios
												";
				
				$sql_dependencias = mysql_query("select modulo 
												from 
											agrupacion_modulos 
											where 
											modulo != '".$id_dependencia."'
											and grupo = (select grupo from agrupacion_modulos where modulo = '".$id_dependencia."')")or die(mysql_error());
			
									while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
											$sql_consulta .= " or recibir_documentos.iddependencia_recibe = '".$bus_dependencias["modulo"]."' ";
									}
				$sql_consulta .= " group by orden_compra_servicio.idorden_compra_servicio";
				
				echo $sql_consulta;*/
				
				
				$sql_consulta = "select * from 
												orden_compra_servicio,
												beneficiarios
													where 
												(orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' 
												or orden_compra_servicio.estado = 'procesado'
												or orden_compra_servicio.estado = 'parcial')
												and orden_compra_servicio.numero_orden like '%".$campoBuscar."%'
												and orden_compra_servicio.idbeneficiarios = beneficiarios.idbeneficiarios
												group by orden_compra_servicio.idorden_compra_servicio";
															 
				$sql_remision_documentos = mysql_query($sql_consulta)or die("consulta mala tributos".mysql_error());
				
				
			}else{
				$sql_remision_documentos = mysql_query("select * from 
												orden_compra_servicio,
												beneficiarios
													where 
												(orden_compra_servicio.estado = 'conformado' 
												or orden_compra_servicio.estado = 'devuelto' 
												or orden_compra_servicio.estado = 'procesado'
												or orden_compra_servicio.estado = 'parcial')
												and orden_compra_servicio.numero_orden like '%".$campoBuscar."%'
												and orden_compra_servicio.idbeneficiarios = beneficiarios.idbeneficiarios
												group by orden_compra_servicio.idorden_compra_servicio")
												 or die("consulta mala tributos".mysql_error());	
			}
			$num_remision_documentos = mysql_num_rows($sql_remision_documentos);
			//$sql_consulta = mysql_query("select * from orden_compra_servicio where estado = 'procesado'");
			
			?>
			
			<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
			<tr>
								<td align="center">
								
								
                    <table width="100%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
          				<thead style=" background-image:none; background-color: #006699">
                            <tr>
                                <td align="center" class="Browse" colspan="11">
                                	<strong style="color:#FFFFFF">Lista de Documentos</strong>
                                </td>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <td width="14%" align="center" class="Browse">Nro. Orden</td>
                              <td width="15%" align="center" class="Browse">Fecha Orden</td>
                              <td width="53%" align="center" class="Browse">Beneficiario</td>
                              <td width="11%" align="center" class="Browse">Total</td>
                              <td width="7%" align="center" class="Browse">Acci&oacute;n</td>
                          </tr>
                        </thead>
                        <?
                        while($bus_recibidos = mysql_fetch_array($sql_remision_documentos)){
							/*if($_SESSION["version"] == "completo"){
							$sql_consulta = "select * from relacion_documentos_recibidos, 
															recibir_documentos 
													where 
														relacion_documentos_recibidos.id_documento= '".$bus_recibidos["idorden_compra_servicio"]."' 
														and recibir_documentos.idrecibir_documentos = relacion_documentos_recibidos.idrecibir_documentos 
														and (recibir_documentos.iddependencia_recibe = '".$id_dependencia."' ";
							//echo $sql_consulta;
							
									$sql_dependencias = mysql_query("select modulo 
												from 
											agrupacion_modulos 
											where 
											modulo != '".$id_dependencia."'
											and grupo = (select grupo from agrupacion_modulos where modulo = '".$id_dependencia."')")or die(mysql_error());
			
									while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
											$sql_consulta .= " or recibir_documentos.iddependencia_recibe = '".$bus_dependencias["modulo"]."' ";
									}
														
														$sql_consulta .= ") and recibir_documentos.estado != 'anulado'";
							
							//echo $sql_consulta."<br /><br />";
							$sql_recibir = mysql_query($sql_consulta)or die(mysql_error());
							
							$num_recibir = mysql_num_rows($sql_recibir);	
							//echo $num_recibir;
							$sql_validacion = mysql_query("select * from remision_documentos,
								relacion_documentos_remision
								where 
								relacion_documentos_remision.id_documento = '".$bus_recibidos["idorden_compra_servicio"]."'
								and remision_documentos.idremision_documentos = relacion_documentos_remision.idremision_documentos
								and remision_documentos.iddependencia_origen = '".$id_dependencia."'
								and remision_documentos.estado != 'anulado'");
							 
							$num_validacion = mysql_num_rows($sql_validacion);
							
						
						}else{*/
							$num_validacion = 0;
							$num_recibir = 1;	
						//}
						//echo $num_validacion;
						//echo " ".$num_recibir;
						//if($num_validacion != $num_recibir){
					
						?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">				
                        	<?
							
							$sql_relacion_impuesto = mysql_query("select * from relacion_impuestos_ordenes_compras where idorden_compra_servicio = '".$bus_recibidos["idorden_compra_servicio"]."'");
						   $bus_relacion_impuesto = mysql_fetch_array($sql_relacion_impuesto);
						   ?>
                                <td class='Browse'><?=$bus_recibidos["numero_orden"]?></td>
                                <td class='Browse'><?=$bus_recibidos["fecha_orden"]?></td>
                                <td class='Browse'><?=$bus_recibidos["nombre"]?></td>
                                <td class='Browse' align="right"><?=number_format($bus_recibidos["total"],2,",",".")?></td>
                                <td class='Browse' align="center">
                           <?
						
						   
                           
						   // CONSULTA TABLA BENEFICIARIOS PARA MOSTRAR NOMBRE EN CABEZERA
						   $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_recibidos["idbeneficiarios"]."'");
						   $bus_beneficiario = mysql_fetch_array($sql_beneficiario);
						   
						   //BUSCO LAS RETENCIONES QUE TENGA APLICADO EL DOCUMENTO
						   $sql_retenciones = mysql_query("select SUM(total_retenido) as total_retenido from retenciones where iddocumento = ".$bus_recibidos["idorden_compra_servicio"]." AND estado <> 'elaboracion' order by idretenciones DESC");
						   
						   $total_retenido = 0;
						   
						   $num_retenciones = mysql_fetch_array($sql_retenciones);
						   if($num_retenciones > 0){
							   $total_retenido = $num_retenciones["total_retenido"];
							}
						   
						   
						   //BUSCO LAS RETENCIONES QUE SE HAN APLICADO PARA OBTENER LOS TOTALES DE EXENTO
						   	if ($anio < $_SESSION["anio_fiscal"]){
						    	$sql_retenciones_totales = mysql_query("(select *
																		from retenciones 
																   		where iddocumento = '".$bus_recibidos["idorden_compra_servicio"]."' 
																			and estado <> 'elaboracion')
																		UNION
																		(select *
																		from retenciones 
																   		where iddocumento = '".$bus_recibidos["idorden_compra_servicio"]."' 
																			and estado <> 'elaboracion')");
							}else{
								$sql_retenciones_totales = mysql_query("select *
																		from retenciones 
																   		where iddocumento = '".$bus_recibidos["idorden_compra_servicio"]."' 
																			and estado <> 'elaboracion'");
							}
						    $entro = 'no';
							$entro_para_estado = 'no';
						    $num_retenciones_totales = mysql_num_rows($sql_retenciones_totales);
						   			
							if($num_retenciones_totales == 0){
							
								$total_pendiente_exento = $bus_recibidos["exento"];
								$total_pendiente_base = $bus_recibidos["sub_total"];
								$total_pendiente_impuesto = $bus_recibidos["impuesto"];
								$total_pendiente_total = $bus_recibidos["total"];
								$entro_para_estado = 'si';	
							}else{
						   		
						   		while($bus_retenciones_totales = mysql_fetch_array($sql_retenciones_totales)){
									$sql_relacion_pago_retencion = mysql_query("select * from relacion_orden_pago_retencion, 
																							  orden_pago 
																			   		where relacion_orden_pago_retencion.idretencion = '".$bus_retenciones_totales["idretenciones"]."'
																					and orden_pago.idorden_pago = relacion_orden_pago_retencion.idorden_pago
																					and orden_pago.estado <> 'anulado'");
									$bus_relacion_pago_retencion = mysql_fetch_array($sql_relacion_pago_retencion);
									$sql_tipo_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago_retencion["idorden_pago"]."'");
									$num_tipo_pago = mysql_num_rows($sql_tipo_pago);
									if ($num_tipo_pago > 0){
										$bus_tipo_pago = mysql_fetch_array($sql_tipo_pago);
										if ($bus_tipo_pago["anticipo"] != 'si'){
											$total_pendiente_exento += $bus_retenciones_totales["exento"];
											$total_pendiente_base += $bus_retenciones_totales["base"];
											$total_pendiente_impuesto += $bus_retenciones_totales["impuesto"];
											$total_pendiente_total += $bus_retenciones_totales["total"];	
											$entro = "si";
										}
									}
								}
							}
							
							
							
							if($entro == "si"){
								$exento_a_mostrar = $bus_recibidos["exento"]-$total_pendiente_exento;
								$base_a_mostrar = $bus_recibidos["sub_total"]-$total_pendiente_base;	
								$impuesto_a_mostrar = $bus_recibidos["impuesto"]-$total_pendiente_impuesto;
								$total_a_mostrar = $bus_recibidos["total"]-$total_pendiente_total;
								$entro_para_estado = 'si';
								$entro = "no";
								//echo "AQUI";
							}else{
								
								if ($entro_para_estado == 'si'){
									$exento_a_mostrar = $total_pendiente_exento;
									$base_a_mostrar = $total_pendiente_base;
									$impuesto_a_mostrar = $total_pendiente_impuesto;
									$total_a_mostrar = $total_pendiente_total;
									
								}else{
									$exento_a_mostrar = $bus_recibidos["exento"];
									$base_a_mostrar = $bus_recibidos["sub_total"];
									$impuesto_a_mostrar = $bus_recibidos["impuesto"];
									$total_a_mostrar = $bus_recibidos["total"];
									
								}
							}
						   
							$total_pendiente_exento = 0;
							$total_pendiente_base = 0;
							$total_pendiente_impuesto = 0;
							$total_pendiente_total = 0;						  
						   $registrado = "no";
						   ?>
                            <a href="#" onClick="seleccionarDocumento('<?=$bus_recibidos["numero_orden"]?>', '<?=$bus_recibidos["fecha_orden"]?>', '<?=$bus_recibidos["id_beneficiarios"]?>', '<?=$bus_beneficiario["nombre"]?>', '<?=$bus_recibidos["nro_factura"]?>', '<?=$bus_recibidos["nro_control"]?>', '<?=$bus_recibidos["fecha_factura"]?>', '<?=number_format($exento_a_mostrar,2,",",".")?>', '<?=number_format($base_a_mostrar,2,",",".")?>', '<?=number_format($impuesto_a_mostrar,2,",",".")?>', '<?=number_format($total_a_mostrar,2,",",".")?>', '<?=$exento_a_mostrar?>', '<?=$base_a_mostrar?>', '<?=$impuesto_a_mostrar?>', '<?=$total_a_mostrar?>', '<?=$bus_recibidos["idorden_compra_servicio"]?>', '<?=$bus_relacion_impuesto["porcentaje"]?>', '<?=$anio?>'), mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value, '<?=$anio?>'), document.getElementById('divTotalRetenciones').innerHTML = '<?=number_format($total_retenido,2,",",".")?>', <? echo "document.getElementById('estado').value = 'procesado'";?>, document.getElementById('imagen_actualizar_factura').style.display = 'none'">
                            	<img src="imagenes/validar.png" style="cursor:pointer" border="0">
                            </a>  
							<? //} ?>
                            </td>
                      </tr>
                        <?
							}
						//}
                        ?>
                    </table>				  </td>
					</tr>
				</table>

<?
}













if($ejecutar == "actualizarFechaNro"){
	
	$sql_compromiso = mysql_query("select * from retenciones where idretenciones = '".$id_retenciones."'");
	$bus_compromiso = mysql_fetch_array($sql_compromiso);
	
	$sql_proveedor = mysql_query("select * from orden_compra_servicio where idorden_compra_servicio= '".$bus_compromiso["iddocumento"]."'")or die(mysql_error("uno"));
	$bus_proveedor = mysql_fetch_array($sql_proveedor);
	
	$sql_existe = mysql_query("select * from retenciones
    								INNER JOIN orden_compra_servicio on (retenciones.iddocumento = orden_compra_servicio.idorden_compra_servicio)
    								INNER JOIN beneficiarios on (orden_compra_servicio.idbeneficiarios = beneficiarios.idbeneficiarios)
  								where retenciones.numero_factura = '".$nro_factura."'
    									and beneficiarios.idbeneficiarios = '".$bus_proveedor["idbeneficiarios"]."'")or die(mysql_error("dos"));
	
	$num_existe_factura = mysql_num_rows($sql_existe);
	
	if ($num_existe_factura > 0){
		
			$sql_datos_factura = mysql_query("update retenciones set fecha_factura = '".$fecha_factura."',
																	numero_control = '".$nro_control."',
																		where idretenciones = '".$id_retenciones."'");
			echo "exito";
		
	}else{
		$sql_actualizar_datos = mysql_query("update retenciones set numero_factura = '".$nro_factura."',
																numero_control = '".$nro_control."',
																fecha_factura = '".$fecha_factura."' where idretenciones = '".$id_retenciones."'")or die(mysql_error());
		echo "exito";
	}
	
}




if($ejecutar == "actualizarFechaFactura"){
	$sql_actualizar = mysql_query("update orden_compra_servicio 
														set fecha_factura = '".$fecha_factura."',
														nro_factura = '".$nro_factura."',
														nro_control = '".$nro_control."'
														where idorden_compra_servicio  = '".$id_documento."'")or die(mysql_error());
}



?>
