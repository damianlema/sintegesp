<?
session_start();

include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);



if($ejecutar == "seleccionarDocumento"){

$sql_conformar = mysql_query("select * from ".$tabla.", beneficiarios where ".$tabla.".id".$tabla." = '".$id_documento."'
																				and beneficiarios.idbeneficiarios = ".$tabla.".idbeneficiarios")or die(mysql_error());
$bus_conformar = mysql_fetch_array($sql_conformar);
?>

<table width="80%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td colspan="6" align="right"><div align="center"><img src="imagenes/search0.png" style="cursor:pointer" title="Buscar Documentos Conformados" onclick="window.open('modulos/presupuesto/lib/listar_documentos_conformados.php?destino=Presupuesto','buscra documentos conformados','scrollbars = yes, resizable = no, width=900, height=500')" /></div></td>
    </tr>
   <tr>
     <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td width="196" align="right" class='viewPropTitle'>N&uacute;mero de Documento:</td>
    <td width="183" id="tdNumeroDocumento"><strong><?=$bus_conformar["numero_orden"]?></strong></td>
    <td width="194" align="right" class="viewPropTitle">Fecha Documento:</td>
    <td width="191" id="tdFechaDocumento"><strong><?=$bus_conformar["fecha_orden"]?></strong></td>
    <td width="184" align="right" class="viewPropTitle">Monto:</td>
    <td width="137" id="tdMonto" style="text-align:right"><strong><?=number_format($bus_conformar["total"],2,",",".")?></strong></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Beneficiario:</td>
    <td colspan="5" id="tdBeneficiario"><strong><?=$bus_conformar["nombre"]?></strong></td>
    </tr>
 	<input type="hidden" id="id_documento" name="id_documento" value="<?=$id_documento?>">
    <input type="hidden" id="id_recibido" name="id_recibido" value="<?=$id_recibido?>">
 </table>
 <br>
 <table width="80%" border="0" align="center" cellpadding="0" cellspacing="2"> 
  <tr>
    <td colspan="4" class='viewPropTitle'><div align="center"><strong>Conformaci&oacute;n del Documento</strong></div></td>
   </tr>
  <tr>
   <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
    <td width="19%"><div align="right">Estado</div></td>
    <td width="29%">
    <select id="estadoConformacion">
    	<option value="0">.::Seleccione::.</option>
        <option value="conformado">Conformado</option>
        <option value="devuelto">Devuelto</option>
    </select>
    
    </td>
    <td width="9%"><div align="right">Motivo:</div></td>
    <td width="43%">
   	  <select id="razones_devolucion">
        <option value="0">.::Seleccione::.</option>
    <? 	$sql_motivos = mysql_query("select * from razones_devolucion"); 
		
		while ($bus_razones = mysql_fetch_array($sql_motivos)){ ?>
			<option value="<?=$bus_razones["idrazones_devolucion"]?>"><? echo $bus_razones["descripcion"]; ?></option>
		<?
		}
		
    ?>
    </select>
    
    </td>
  </tr>
  
  <tr>
    <td><div align="right">Conformado por:</div></td>
    <td><input type="text" size="45" name="conformado_por" id="conformado_por" ></td>
    <td><div align="right">C.I. Nro.</div></td>
    <td><input type="text" name="ci_conformado_por" id="ci_conformado_por" ></td>
  </tr>
  <tr>
    <td><div align="right">Fecha de Conformaci&oacute;n</div></td>
    <td><strong><?=date("d-m-Y")?></strong></td>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Observaciones:</div></td>
    <td colspan="3"><textarea name="observaciones" cols="100" rows="3" id="observaciones" ></textarea></td>
    </tr>
  <tr>
   	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="4" align="right"><label>
      <div align="center">
        <input type="submit" name="botonConformar" id="botonConformar" value="Conformar" class="button" readonly 
      onClick="conformarDocumento(document.getElementById('id_documento').value, document.getElementById('id_recibido').value, document.getElementById('estadoConformacion').value, document.getElementById('razones_devolucion').value, document.getElementById('conformado_por').value, document.getElementById('ci_conformado_por').value, document.getElementById('observaciones').value)">
        </div>
    </label></td>
    </tr>
</table>

<?
}






if($ejecutar == "listarDocumentos"){
	
	$sql_configuracion = mysql_query("select * from configuracion_presupuesto");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	$id_dependencia = $bus_configuracion["iddependencia"];
	
     $sql_remision_documentos = mysql_query("select * from recibir_documentos,relacion_documentos_recibidos
															where recibir_documentos.iddependencia_recibe = ".$id_dependencia."
																and relacion_documentos_recibidos.idrecibir_documentos = recibir_documentos.idrecibir_documentos
											")or die(mysql_error());
	?>
				<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
				  <tr>
					<td width="84" class="Browse"><div align="center">N&uacute;mero</div></td>
					<td width="84" class="Browse"><div align="center">Fecha </div></td>
					<td width="415" class="Browse"><div align="center">Beneficiario</div></td>
                    <td width="83" class="Browse"><div align="center">Monto</div></td>
                    <td width="70" class="Browse"><div align="center">Conformar</div></td>
		  </tr>
				  </thead>
					<?
                    while($bus_remision_documentos = mysql_fetch_array($sql_remision_documentos)){
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
						<? 	$tabla = $bus_remision_documentos["tabla"]; 
							$idtabla = "id".$tabla;
							$sql_documento = mysql_query("select * from ".$tabla.",beneficiarios 
																	where ".$tabla.".".$idtabla." = ".$bus_remision_documentos["id_documento"]."
																	and ".$tabla.".idbeneficiarios = beneficiarios.idbeneficiarios");
							$bus_documento = mysql_fetch_array($sql_documento);
							$sql_tipos_documentos = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$bus_documento["tipo"]."'");
							$bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos);
							
							if($bus_tipos_documentos["multi_categoria"] == "no"){
								$sql_usuarios_categoria = mysql_query("select * from usuarios_categoria where cedula = ".$_SESSION["cedula_usuario"]." 
														and idcategoria_programatica = ".$bus_documento["idcategoria_programatica"]."");
								$tiene_permiso = mysql_num_rows($sql_usuarios_categoria);
							}else{
								$sql_articulos_servicios = mysql_query("select * from articulos_compra_servicio where idorden_compra_servicio = '".$bus_documento["idorden_compra_servicio"]."'");
								$num_articulos = mysql_num_rows($sql_articulos_servicios);
								$sql_u_c = "select * from usuarios_categoria where cedula = '".$_SESSION["cedula_usuario"]."' ";
					//echo "NUMEROS: ".$num_articulos;
								if($num_articulos > 0){

									$sql_u_c .= "and (";
									while($bus_articulos_servicios = mysql_fetch_array($sql_articulos_servicios)){
										$sql_u_c .= " idcategoria_programatica = '".$bus_articulos_servicios["idcategoria_programatica"]."' ||";
									}
									
								$sql_u_c = $sql_u_c = substr($sql_u_c, 0, strlen($sql_u_c) - 2);
								
								$sql_u_c .= ")";
								}
								
							$sql_usuarios_categoria = mysql_query($sql_u_c);
								$tiene_permiso = mysql_num_rows($sql_usuarios_categoria);
							}
							if ($tiene_permiso > 0){
								if ($bus_documento["ubicacion"] == "recibido" and $bus_documento["estado"] <> "conformado" and $bus_documento["estado"] <> "anulado"){
									?>
									<td class='Browse' align='center'><?=$bus_documento["numero_orden"]?></td>
									<td class='Browse' align='center'><?=$bus_documento["fecha_orden"]?></td>
									<td class='Browse' align='left'><?=$bus_documento["nombre"]?></td>
									<td class='Browse' style="text-align:right"><?=number_format($bus_documento["total"],2,",",".")?></td>
									<td class="Browse" align='center'>
										<img src="imagenes/validar.png" style=" cursor:pointer" onclick="seleccionarDocumento(<?=$bus_remision_documentos["id_documento"]?>,<?=$bus_remision_documentos["idrecibir_documentos"]?>,'<?=$bus_remision_documentos["tabla"]?>')">
									</td>
                        	<? } 
						}?>
				  </tr>
                     <?
                     }
					 ?>
				</table>
<?
}





if($ejecutar == "vaciarCampos"){
?>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td colspan="6" align="right"><div align="center"><img src="imagenes/search0.png" style="cursor:pointer" title="Buscar Documentos Conformados" onclick="window.open('modulos/presupuesto/lib/listar_documentos_conformados.php?destino=Presupuesto','buscar documentos conformados','scrollbars = yes, resizable = no, width=900, height=500')" /></div></td>
    </tr>
   <tr>
     <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td width="196" align="right" class='viewPropTitle'>N&uacute;mero de Documento:</td>
    <td width="183" id="tdNumeroDocumento">&nbsp;</td>
    <td width="194" align="right" class="viewPropTitle">Fecha Documento:</td>
    <td width="191" id="tdFechaDocumento">&nbsp;</td>
    <td width="184" align="right" class="viewPropTitle">Monto:</td>
    <td width="137" id="tdMonto">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Beneficiario:</td>
    <td colspan="5" id="tdBeneficiario">&nbsp;</td>
    </tr>
 
 </table>
 <br>
 <table width="80%" border="0" align="center" cellpadding="0" cellspacing="2"> 
  <tr>
    <td colspan="4" class='viewPropTitle'><div align="center"><strong>Conformaci&oacute;n del Documento</strong></div></td>
   </tr>
  <tr>
   <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
    <td width="19%"><div align="right">Estado</div></td>
    <td width="31%">
    <select id="estadoConformacion">
    	<option value="0">.::Seleccione::.</option>
        <option value="conformado">Conformado</option>
        <option value="devuelto">Devuelto</option>
    </select>
    
    </td>
    <td width="11%"><div align="right">Motivo:</div></td>
    <td width="39%">
    	<select id="razones_devolucion">
        <option value="0">.::Seleccione::.</option>
    <? 	$sql_motivos = mysql_query("select * from razones_devolucion"); 
		
		while ($bus_razones = mysql_fetch_array($sql_motivos)){ ?>
			<option value="<?=$bus_razones["idrazones_devolucion"]?>"><? echo $bus_razones["descripcion"]; ?></option>
		<?
		}
		
    ?>
    	</select>
    </td>
  </tr>
  <tr>
    <td><div align="right">Conformado por:</div></td>
    <td><input type="text" size="45" name="conformado_por" id="conformado_por" readonly></td>
    <td><div align="right">C.I. Nro.</div></td>
    <td><input type="text" name="ci_conformado_por" id="ci_conformado_por" readonly></td>
  </tr>
  <tr>
    <td><div align="right">Fecha de Conformaci&oacute;n</div></td>
    <td><strong><?=date("d-m-Y")?></strong></td>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">Observaciones:</div></td>
    <td colspan="3"><textarea name="observaciones" cols="100" rows="3" id="observaciones" readonly="readonly"></textarea></td>
    </tr>
  <tr>
   	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="4" align="right"><label>
      <div align="center">
        <input type="submit" name="botonConformar" id="botonConformar" value="Conformar" class="button" readonly 
      onClick="">
        </div>
    </label></td>
    </tr>
</table>

<?
}






if($ejecutar == "conformarDocumento"){
if ($modulo == 2){
	$oficina = "Presupuesto";
}else if($modulo == 3){
	$oficina = "Compras y Servicios";
}else if($modulo == 4){
	$oficina = "Administracion";
}	
$sql_recibido = mysql_query("select * from recibir_documentos where idrecibir_documentos = ".$id_recibido."")or die(mysql_error());
$bus_recibido = mysql_fetch_array($sql_recibido);

$sql_relacion_recibidos = mysql_query("select * from relacion_documentos_recibidos 
															where idrecibir_documentos = ".$bus_recibido["idrecibir_documentos"]."")or die("BUSQUEDA REMISION ".mysql_error());
$bus_relacion_recibidos = mysql_fetch_array($sql_relacion_recibidos);		
$tabla = $bus_relacion_recibidos["tabla"];
$id_tabla = "id".$tabla;
$tipo = $bus_relacion_recibidos["idtipos_documentos"];
$sql_recibir_documentos = mysql_query("insert into conformar_documentos(
																		iddocumento,
																		idrecibido,
																		tipo,
																		estado,
																		idrazones_devolucion,
																		conformado_por,
																		ci_conformador,
																		fecha_conformado,
																		observaciones,
																		status,
																		usuario,
																		fechayhora)values(
																						'".$id_documento."',
																						'".$id_recibido."',
																						'".$tipo."',
																						'".$estado_conformacion."',
																						'".$razones_devolucion."',
																						'".$conformado_por."',
																						'".$ci_conformado_por."',
																						'".date("Y-m-d")."',
																						'".$observaciones."',
																						'a',
																						'".$login."',
																						'".date("Y-m-d H:i:s")."')")or die("CONFORMAR ".mysql_error());
	
	$sql_actualizar_recibido = mysql_query("update relacion_documentos_recibidos set estado = '".$estado_conformacion."'
													where idrecibir_documentos = ".$id_recibido."
														and idtipos_documentos = ".$tipo."
														and id_documento = ".$id_documento."")or die("RELACION ".mysql_error());

	echo $tabla;
	$sql_tabla = mysql_query("update ".$tabla." set ubicacion = 'conformado', 
													estado = '".$estado_conformacion."',
													idrazones_devolucion = '".$razones_devolucion."',
													observaciones_devolucion = '".$observaciones."'
												where ".$id_tabla." = ".$id_documento."")or die(mysql_error());
	
	registra_transaccion("Conformacion de Documento de ".$oficina." (".$id_documento.")",$login,$fh,$pc,'relacion_documentos_recibidos');
																			
}


?>