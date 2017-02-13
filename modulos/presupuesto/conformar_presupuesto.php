<script src="modulos/presupuesto/js/conformar_presupuesto_ajax.js" type="text/javascript" language="javascript"></script>
<style type="text/css">
<!--
.Estilo1 {font-family: Arial, Helvetica, sans-serif}
-->
</style>
	

    <br>
<h4 align=center>Conformar Documentos</h4>
	<h2 class="sqlmVersion"></h2>
<div id="divTablaValidacionRemision">
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
</div>



<br>
    <br>
	<h2 align="center">Lista de Documentos Por Conformar</h2>
<br>



<div id="divListaDocumentos" style=" width:100%; height:380px; overflow:auto;">
    
    <?
	
	$sql_configuracion = mysql_query("select * from configuracion_presupuesto");
	$bus_configuracion = mysql_fetch_array($sql_configuracion);
	$id_dependencia = $bus_configuracion["iddependencia"];
	
	
    $sql_remision_documentos = mysql_query("select * from recibir_documentos,relacion_documentos_recibidos
															where recibir_documentos.iddependencia_recibe = ".$id_dependencia."
																and recibir_documentos.idrecibir_documentos = relacion_documentos_recibidos.idrecibir_documentos")or die(mysql_error());
																
	$num_remision_documentos = mysql_num_rows($sql_remision_documentos);
	//echo "PRUEBA: ".$num_remision_documentos;
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
								
								
									//echo "HOLA: ".$sql_u_c;
								//echo $sql_u_c."<br>";
								$sql_usuarios_categoria = mysql_query($sql_u_c);
								$tiene_permiso = mysql_num_rows($sql_usuarios_categoria);
							}
							
							
							if ($tiene_permiso > 0){	
							//echo "ENTRO ACA";						
								if ($bus_documento["ubicacion"] == "recibido" and $bus_documento["estado"] <> "conformado" and $bus_documento["estado"] <> "devuelto" and $bus_documento["estado"] <> "anulado" and $bus_documento["estado"] <> "ordenado" and $bus_documento["estado"] <> "pagado" and $bus_documento["estado"] <> "pagada"){
							?>
									<td class='Browse' align='center'><?=$bus_documento["numero_orden"]?></td>
									<td class='Browse' align='center'><?=$bus_documento["fecha_orden"]?></td>
									<td class='Browse' align='left'><?=$bus_documento["nombre"]?></td>
									<td class='Browse' style="text-align:right"><?=number_format($bus_documento["total"],2,",",".")?></td>
									<td class="Browse" align='center'>
										<img src="imagenes/validar.png" style=" cursor:pointer" onclick="seleccionarDocumento(<?=$bus_remision_documentos["id_documento"]?>,<?=$bus_remision_documentos["idrecibir_documentos"]?>,'<?=$bus_remision_documentos["tabla"]?>')">
									</td>
							 <? }
							} ?>
				  </tr>
                     <?
                     }
					 ?>
				</table>
</div>
