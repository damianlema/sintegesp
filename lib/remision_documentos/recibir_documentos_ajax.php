<?
session_start();

include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();

extract($_POST);

$buscar=mysql_query("select * from usuarios 
								where cedula= '".$_SESSION['cedula_usuario']."'")or die(mysql_error());
$registro_usuario=mysql_fetch_array($buscar);


if($ejecutar == "seleccionarRemision"){
$sql_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."");
$bus_remision = mysql_fetch_array($sql_remision);
?>
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="2">
      <tr>
    <td colspan="4" align="right" ><div align="center"><img src="imagenes/search0.png" style="cursor:pointer" title="Buscar Documentos Recibidos" onclick="window.open('lib/listas/listar_documentos_recibidos.php','buscar documentos recibidos','scrollbars = yes, resizable = no, width=900, height=500')" /></div></td>
      </tr>
      <tr>
        <td width="216" align="right" class='viewPropTitle'>Numero de Documento:</td>
        <td width="279" id="tdNumeroDocumento"><strong><?=$bus_remision["numero_documento"]?></strong></td>
        <td width="93">&nbsp;</td>
        <td width="202">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Fecha Recibido:</td>
        <td id="tdFechaRecibido"><strong><?=$fecha_validada?></strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Recibido Por:</td>
        <td><label>
          <input type="text" name="recibido_por" id="recibido_por" size="40" value = "<?=$registro_usuario["nombres"]." ".$registro_usuario["apellidos"]?>">
          <input type="hidden" id="id_remision" name="id_remision" value="<?=$id_remision?>">
        </label></td>
        <td align="right" class='viewPropTitle'>CI Recibido:</td>
        <td><label>
          <input name="ci_recibe" type="text" id="ci_recibe" size="14" value = "<?=$registro_usuario["cedula"]?>">
        </label></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Observaciones</td>
        <td colspan="3"><label>
          <textarea name="observaciones" cols="80" rows="3" id="observaciones"></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Nro de Documentos Recibidos:</td>
        <td><label>
          <input name="numero_documentos_recibidos" type="text" id="numero_documentos_recibidos" size="5">
        </label></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="right"><label>
          <div align="center">
            <input type="submit" name="botonRecibir" id="botonRecibir" value="Recibir" class="button"
          onClick="recibirDocumento(document.getElementById('id_remision').value, document.getElementById('recibido_por').value, document.getElementById('ci_recibe').value, document.getElementById('observaciones').value, document.getElementById('numero_documentos_recibidos').value)">
          </div>
        </label></td>
      </tr>
    </table>
<?
}






if($ejecutar == "listarDocumentos"){
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
					$existen_registros = mysql_num_rows($sql_remision_documentos);
					if ($existen_registros > 0){
                    while($bus_remision_documentos = mysql_fetch_array($sql_remision_documentos)){
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')"  title="<?
                            $sql_documentos_remision = mysql_query("select * from relacion_documentos_remision where idremision_documentos = '".$bus_remision_documentos["idremision_documentos"]."'");
							while($bus_documentos_remision = mysql_fetch_array($sql_documentos_remision)){
								$sql_documento = mysql_query("select * from ".$bus_documentos_remision["tabla"]." where id".$bus_documentos_remision["tabla"]." = ".$bus_documentos_remision["id_documento"]."");
								$bus_documento = mysql_fetch_array($sql_documento);
								echo $bus_documento["numero_orden"]." &nbsp;&nbsp;|&nbsp;&nbsp; ";
							}
							?>" onclick="seleccionarRemision(<?=$bus_remision_documentos["idremision_documentos"]?>)" style="cursor:pointer">

						<td class="Browse" align='center'>
							<img src="imagenes/validar.png" style=" cursor:pointer" onclick="seleccionarRemision(<?=$bus_remision_documentos["idremision_documentos"]?>)">
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
					 }
					 ?>
				</table>
<?
}





if($ejecutar == "vaciarCampos"){
?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td colspan="4" align="right" ><div align="center"><img src="imagenes/search0.png" style="cursor:pointer" title="Buscar Documentos Recibidos" onclick="window.open('lib/listas/listar_documentos_recibidos.php','buscar documentos recibidos','scrollbars = yes, resizable = no, width=900, height=500')" /></div></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Numero de Documento:</td>
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
      <input type="text" name="recibido_por" id="recibido_por" readonly value = "<?=$registro_usuario["nombres"]." ".$registro_usuario["apellidos"]?>">
    </label></td>
    <td align="right" class='viewPropTitle'>CI Recibido:</td>
    <td><label>
      <input type="text" name="ci_recibe" id="ci_recibe" readonly value = "<?=$registro_usuario["cedula"]?>">
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
        <input type="submit" name="botonRecibir" id="botonRecibir" value="Recibir" class="button" readonly>
        </div>
    </label></td>
  </tr>
</table>

<?
}


if($ejecutar == "recibirDocumento"){
$sql_remision = mysql_query("select * from remision_documentos where idremision_documentos = ".$id_remision."");
$bus_remision = mysql_fetch_array($sql_remision);
$sql_recibir_documentos = mysql_query("insert into recibir_documentos(
																		idremision_documentos,
																		iddependencia_recibe,
																		fecha_recibido,
																		recibido_por,
																		ci_recibe,
																		observaciones,
																		numero_documentos_recibidos,
																		estado,
																		status,
																		usuario,
																		fechayhora)values(
																						'".$id_remision."',
																						'".$bus_remision["iddependencia_destino"]."',
																						'".$fecha_validada."',
																						'".$recibido_por."',
																						'".$ci_recibe."',
																						'".$observaciones."',
																						'".$numero_documentos_recibidos."',
																						'recibido',
																						'a',
																						'".$login."',
																						'".date("Y-m-d H:i:s")."')");
$id_remision_recibida = mysql_insert_id();
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

registra_transaccion("Recibir Documentos en ".$oficina." (".$id_remision_recibida.")",$login,$fh,$pc,'recibir_documentos');																						
$sql_actualiza_remision = mysql_query("update remision_documentos set estado = 'recibido' where idremision_documentos = ".$id_remision."");
$sql_relacion_remision = mysql_query("select * from relacion_documentos_remision where idremision_documentos = ".$id_remision."")or die(mysql_error());

	while($bus_relacion_remision = mysql_fetch_array($sql_relacion_remision)){

		$sql_relacion_recibidos = mysql_query("insert into relacion_documentos_recibidos(
																									idrecibir_documentos,
																									iddependencia_origen,
																									idtipos_documentos,
																									id_documento,
																									tabla,
																									estado,
																									idrazones_devolucion,
																									observaciones,
																									conformado_por,
																									ci_conformador,
																									status,
																									usuario,
																									fechayhora)values(
																										'".$id_remision_recibida."',
																										'".$bus_relacion_remision["iddependencia_origen"]."',
																										'".$bus_relacion_remision["idtipos_documentos"]."',
																										'".$bus_relacion_remision["id_documento"]."',
																										'".$bus_relacion_remision["tabla"]."',
																										'recibido',
																										'".$bus_relacion_remision["idrazones_devolucion"]."',
																										'".$bus_relacion_remision["observaciones"]."',
																										'".$bus_relacion_remision["conformado_por"]."',
																										'".$bus_relacion_remision["ci_conformador"]."',
																										'".$bus_relacion_remision["status"]."',
																										'".$login."',
																										'".date("Y-m-d H:i:s")."')")or die(mysql_error());
	
				
				$sql_actualizar_relacion = mysql_query("update relacion_documentos_remision set estado = 'recibido' where idrelacion_documentos_remision = ".$bus_relacion_remision["idrelacion_documentos_remision"]."");
				
				$tabla = $bus_relacion_remision["tabla"];
				$id_tabla = "id".$tabla;
				
				$sql_tipo_documento = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$bus_relacion_remision["idtipos_documentos"]."")
										or die("error tipos documentos".mysql_error());
				$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);
				
				$modulos_relacionados = explode("-", $bus_tipo_documento["modulo"]);
				if (in_array($modulo, $modulos_relacionados)){
					$ubicacion = "0";
				}else{
					$ubicacion = "recibido";
				}
				
				$sql_tabla = mysql_query("update ".$tabla." set ubicacion = '".$ubicacion."' where ".$id_tabla." = ".$bus_relacion_remision["id_documento"]."")
								or die("error ubicacion".mysql_error());
	
	}
	
																						
}


?>