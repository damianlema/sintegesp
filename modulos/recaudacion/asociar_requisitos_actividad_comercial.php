<script src="modulos/recaudacion/js/asociar_requisitos_actividad_comercial_ajax.js"></script>
 <body>
    <br>
<h4 align=center>Asociar Requisitos a Actividades Comerciales</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idcarrera" name="idcarrera">

 <table width="40%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Actividad Comercial</td>
    <td width="68%"><label>
      <select id="idactividad_comercial" name="idactividad_comercial">
    <?
    $sql_actividad_comercial = mysql_query("select * from actividades_comerciales");
	?>
	<option value="0">.:: Seleccione ::.</option>
	<?
	while($bus_actividad_comercial = mysql_fetch_array($sql_actividad_comercial)){
		?>
		<option value="<?=$bus_actividad_comercial["idactividades_comerciales"]?>" onClick="listarRequisitos('<?=$bus_actividad_comercial["idactividades_comerciales"]?>')"><?=$bus_actividad_comercial["denominacion"]?></option>
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