<script src="modulos/utilidades/js/transferir_ordenes_anios_ajax.js"></script>
<table width="40%" border="0" align="center">
  <tr>
    <td width="34%" align='right' class='viewPropTitle'>A&ntilde;o Destino:</td>
    <td width="66%"><label>
      <?
	  
      mysql_select_db("gestion_configuracion_general");
      $sql_consulta = mysql_query("select * from anios")or die(mysql_error());
	  ?>
      <select name="anio_destino" id="anio_destino">
      <option value="0" onclick="document.getElementById('boton_procesar').style.display='none'">.:: Seleccione ::.</option>
      <?
	  while($bus_consulta = mysql_fetch_array($sql_consulta)){
	  	?>
		<option value="<?=$bus_consulta["anio"]?>"><?=$bus_consulta["anio"]?></option>
		<?
	  }
	  ?>
      
      </select>
    </label></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tipo de Orden:</td>
    <td>
    <?
	//"gestion_".$_SESSION["anio_fiscal"]
	mysql_select_db("gestion_barrancas");
    $sql_tipo_documento = mysql_query("select * from tipos_documentos")or die(mysql_error());
	?>
    <select name="tipo_orden" id="tipo_orden">
    	<option value="0" onclick="document.getElementById('boton_procesar').style.display='none'">.:: Seleccione ::.</option>
		<?
        while($bus_tipo_documento = mysql_fetch_array($sql_tipo_documento)){
			?>
			<option onclick="consultarOrdenes()" value="<?=$bus_tipo_documento["idtipos_documentos"]?>"><?=$bus_tipo_documento["descripcion"]?></option>
			<?
		}
		?>
    </select>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Numero de Orden</td>
    <td id="celda_numero_orden">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onclick="procesarOrden()"/>
    </label></td>
  </tr>
</table>
