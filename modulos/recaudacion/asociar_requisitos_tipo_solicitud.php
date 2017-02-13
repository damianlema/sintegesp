<script src="modulos/recaudacion/js/asociar_requisitos_tipo_solicitud_ajax.js"></script>
 <body>
    <br>
<h4 align=center>Asociar Requisitos a Tipo de Solicitud</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idcarrera" name="idcarrera">

 <table width="40%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Tipo de Solicitud</td>
    <td width="68%"><label>
      <select id="idtipo_solicitud" name="idtipo_solicitud">
    <?
    $sql_tipo_solicitud = mysql_query("select * from tipo_solicitud");
	?>
	<option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_tipo_solicitud = mysql_fetch_array($sql_tipo_solicitud)){
		?>
		<option value="<?=$bus_tipo_solicitud["idtipo_solicitud"]?>" onClick="listarRequisitos('<?=$bus_tipo_solicitud["idtipo_solicitud"]?>')"><?=$bus_tipo_solicitud["descripcion"]?></option>
		<?
	}
	?>
    </select>
    </label></td>
  </tr>
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Requisito</td>
    <td width="68%">
    <select id="idrequisitos" name="idrequisitos">
    <?
    $sql_requisitos = mysql_query("select * from requisitos");
	?>
	<option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_requisitos = mysql_fetch_array($sql_requisitos)){
		?>
		<option value="<?=$bus_requisitos["idrequisitos"]?>"><?=$bus_requisitos["denominacion"]?></option>
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