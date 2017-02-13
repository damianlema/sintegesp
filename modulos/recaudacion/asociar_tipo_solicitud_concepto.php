<script src="modulos/recaudacion/js/asociar_tipo_solicitud_concepto_ajax.js"></script>
 <body>
    <br>
<h4 align=center>Asociar Conceptos Tributarios a Tipo de Solicitud</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idcarrera" name="idcarrera">

 <table width="40%" border="0" align="center">
  <tr>
    <td width="38%" align="right" class='viewPropTitle'>Tipo de Solicitud</td>
    <td width="62%"><label>
      <select id="idtipo_solicitud" name="idtipo_solicitud">
    <?
    $sql_actividad_comercial = mysql_query("select * from tipo_solicitud");
	?>
	<option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_actividad_comercial = mysql_fetch_array($sql_actividad_comercial)){
		?>
		<option value="<?=$bus_actividad_comercial["idtipo_solicitud"]?>" onClick="listarRequisitos('<?=$bus_actividad_comercial["idtipo_solicitud"]?>')"><?=$bus_actividad_comercial["descripcion"]?></option>
		<?
	}
	?>
    </select>
    </label></td>
  </tr>
  <tr>
    <td width="38%" align="right" class='viewPropTitle'>Concepto Tributario</td>
    <td width="62%">
    <select id="idrequisitos" name="idrequisitos">
    <?
    $sql_requisitos = mysql_query("select * from concepto_tributario");
	?>
	<option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_requisitos = mysql_fetch_array($sql_requisitos)){
		?>
		<option value="<?=$bus_requisitos["idconcepto_tributario"]?>"><?=$bus_requisitos["denominacion"]?></option>
		<?
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <table>
          <tr>
              <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarRequisitos()" class="button"></td>
              <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" onClick="eliminarRequisitos()" class="button"></td>
          </tr>
      </table>
      
      
      
      
      
      
    </label></td>
   </tr>
</table>

<br>
<br>
<div id="listaRequisitos"></div>
</body>