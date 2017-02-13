<script src="js/recibir_documentos_ajax.js" type="text/javascript" language="javascript"></script>	
<? 
$buscar=mysql_query("select * from usuarios 
								where cedula= '".$_SESSION['cedula_usuario']."'")or die(mysql_error());
$registro_usuario=mysql_fetch_array($buscar);
?>
    <br>
<h4 align=center>Recibir Documentos</h4>
	<h2 class="sqlmVersion"></h2>
	
<div id="divTablaValidacionRemision">
<table width="800" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td colspan="4" align="right" ><div align="center"><img src="imagenes/search0.png" style="cursor:pointer" title="Buscar Documentos Recibidos" onclick="window.open('lib/listas/listar_documentos_recibidos.php','buscar documentos recibidos','scrollbars = yes, resizable = no, width=900, height=500')" /></div></td>
    </tr>
  <tr>
    <td align="right" class='viewPropTitle'>N&uacute;mero de Documento:</td>
    <td id="tdNumeroDocumento">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Fecha Recibido:</td>
    <td id="tdFechaRecibido">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Recibido Por:</td>
    <td><label>
      <input name="recibido_por" type="text" id="recibido_por" size="40" value = "<?=$registro_usuario["nombres"]." ".$registro_usuario["apellidos"]?>" readonly>
    </label></td>
    <td align="right" class='viewPropTitle'>CI Recibido:</td>
    <td><label>
      <input type="text" name="ci_recibe" id="ci_recibe" value = "<?=$registro_usuario["cedula"]?>" readonly>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Observaciones</td>
    <td colspan="3"><label>
      <textarea name="observaciones" cols="80" rows="3" id="observaciones" readonly></textarea>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Nro de Documentos Recibidos:</td>
    <td><label>
      <input name="numero_documentos_recibidos" type="text" id="numero_documentos_recibidos" size="5" readonly>
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="right"><label>
      <div align="center">
        <input type="submit" name="botonRecibir" id="botonRecibir" value="Recibir" class="button" readonly 
      onClick="recibirDocumento(document.getElementById('id_remision').value, document.getElementById('recibido_por').value, document.getElementById('ci_recibe').value, document.getElementById('observaciones').value, document.getElementById('numero_documentos_recibidos').value)">
        </div>
    </label></td>
    </tr>
</table>
</div>



<br>
<br>
    <br>
	<h2 align="center">Lista de Documentos</h2>
	<br>



<div id="divListaDocumentos" style=" width:100%; height:380px; overflow:auto;">
    
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
	  
	$id_dependencia = $bus_configuracion["iddependencia"];
    $sql_remision_documentos = mysql_query("select * from remision_documentos where iddependencia_destino = ".$id_dependencia." and estado = 'enviado'");

	?>
				<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
		<thead>
				  <tr>
					<td width="75" class="Browse"><div align="center">Recibir</div></td>
					<td width="114" class="Browse"><div align="center">Numero</div></td>
					<td width="120" class="Browse"><div align="center">Fecha de Envio</div></td>
					<td width="405" class="Browse"><div align="center">Justificacion</div></td>
                    <td width="405" class="Browse"><div align="center">Enviado Por</div></td>
					<td width="86" class="Browse"><div align="center">Nro. Doc.</div></td>
		  </tr>
				  </thead>
					<?
                    while($bus_remision_documentos = mysql_fetch_array($sql_remision_documentos)){
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" title="<?
                            $sql_documentos_remision = mysql_query("select * from relacion_documentos_remision 
																	where idremision_documentos = '".$bus_remision_documentos["idremision_documentos"]."'");
							while($bus_documentos_remision = mysql_fetch_array($sql_documentos_remision)){
								$sql_documento = mysql_query("select * from ".$bus_documentos_remision["tabla"]." 
																where id".$bus_documentos_remision["tabla"]." = ".$bus_documentos_remision["id_documento"]."");
								$bus_documento = mysql_fetch_array($sql_documento);
								echo $bus_documento["numero_orden"]." &nbsp;&nbsp;|&nbsp;&nbsp; ";
							}
							?>" onclick="seleccionarRemision(<?=$bus_remision_documentos["idremision_documentos"]?>)" style="cursor:pointer">

						<td class="Browse" align='center'>
							<img src="imagenes/validar.png" style=" cursor:pointer" onclick="seleccionarRemision(<?=$bus_remision_documentos["idremision_documentos"]?>)" >
						</td>
						<td class='Browse' align='center'><?=$bus_remision_documentos["numero_documento"]?></td>
						<td class='Browse' align='center'><?=$bus_remision_documentos["fecha_envio"]?></td>
						<td class='Browse' align='left'><?=$bus_remision_documentos["justificacion"]?></td>
						<td class='Browse' align='left'>
							<?
                            $sql_dependencias = mysql_query("select * from dependencias where iddependencia = ".$bus_remision_documentos["iddependencia_origen"]."");
							$bus_dependencias = mysql_fetch_array($sql_dependencias);
                            echo $bus_dependencias["denominacion"];
                            ?>
                        </td>
                        <td align='center' class='Browse'><?=$bus_remision_documentos["numero_documentos_enviados"]?></td>
				  </tr>
                     <?
                     }
					 ?>
				</table>
</div>