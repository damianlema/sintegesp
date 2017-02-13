<?
include("../../../conf/conex.php");
Conectarse();
extract($_POST);

if($ejecutar == "listarCompromisos"){
$sql_orden_compra = mysql_query("select  oc.idorden_compra_servicio,
										 oc.numero_orden,
										 oc.fecha_orden,
										 be.nombre,
										 oc.total
										FROM 
											orden_compra_servicio oc, 
											retenciones re,
											beneficiarios be
										WHERE
											re.iddocumento = oc.idorden_compra_servicio
											and (oc.estado = 'procesado' or oc.estado = 'pagado')
											and be.idbeneficiarios = oc.idbeneficiarios
											and oc.numero_orden like '%".$numero_orden."%'
											and be.nombre like '%".$beneficiario."%'")or die(mysql_error());

?>

<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
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
    while($bus_orden_compra = mysql_fetch_array($sql_orden_compra)){
	?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_orden_compra["numero_orden"]?></td>
            <td class='Browse'><?=$bus_orden_compra["fecha_orden"]?></td>
            <td class='Browse'><?=$bus_orden_compra["nombre"]?></td>
            <td class='Browse' align="right"><?=number_format($bus_orden_compra["total"],2,",",".")?></td>
            <td class='Browse' align="center"><img src="imagenes/validar.png" style="cursor:pointer" onclick="mostrarRetenciones('<?=$bus_orden_compra["idorden_compra_servicio"]?>')"></td>
        </tr>
    <?
    }
	?>
    </table>
 <?
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 if($ejecutar == "mostrarRetenciones"){
	$sql_retencion = mysql_query("select * from retenciones where iddocumento = ".$idorden_compra_servicio." order by idretenciones DESC");
	
	
	$i = 0;
	while($bus_retencion = mysql_fetch_array($sql_retencion)){
		$sql_consulta = mysql_query("select * from relacion_retenciones where idretenciones = '".$bus_retencion["idretenciones"]."'");	
		$num_consulta = mysql_num_rows($sql_consulta);
		if($num_consulta != 0){	
			?>
			
			<table width="80%" align="center" cellpadding="0" cellspacing="0" class="Main">
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
                              &nbsp;</td>
                            </tr>
                        </thead>
                        
                     <thead>
                            <tr>
                                <td align="center" class="Browse">Tipo de Retencion</td>
                                <td align="center" class="Browse">Descripcion</td>
                                <td align="center" class="Browse">Monto Retenido</td>
                                <td align="center" class="Browse">Codigo Concepto</td>
                                <td align="center" class="Browse">Acci&oacute;n</td>
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
                            <td class='Browse' align="center"><input type="text" id="codigo_concepto<?=$bus_consulta["idrelacion_retenciones"]?>" name="codigo_concepto<?=$bus_consulta["idrelacion_retenciones"]?>" value="<?=$bus_consulta["codigo_concepto"]?>" size="8" style="text-align:center"></td>

                          <td class='Browse' align="center"><img style="cursor:pointer" src="imagenes/modificar.png" onClick="actualizarCodigoConcepto('<?=$bus_consulta["idrelacion_retenciones"]?>', document.getElementById('codigo_concepto<?=$bus_consulta["idrelacion_retenciones"]?>').value)"></td>
                      </tr>
                        <?
                        }
                        ?>
                    </table>				  
                </td>
			</tr>
			</table>
            <?
	}		
	}
}










if($ejecutar == "actualizarCodigoConcepto"){
	$sql_actualizar_concepto = mysql_query("update relacion_retenciones set 
																		codigo_concepto = '".$codigo_concepto."'
																		where idrelacion_retenciones = '".$idrelacion_retenciones."'");
}





if($ejecutar == "actualizarFechaNro"){
	$sql_actualizar_datos = mysql_query("update retenciones set numero_factura = '".$nro_factura."',
																numero_control = '".$nro_control."',
																fecha_factura = '".$fecha_factura."' where idretenciones = '".$id_retenciones."'")or die(mysql_error());
}
 ?>