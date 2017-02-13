<script src="modulos/recaudacion/js/calle_ajax.js"></script>
 <body onLoad="listarCalle()">
    <br>
<h4 align=center>Calle</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 
 
 <input type="hidden" id="idcalle" name="idcalle">
 <input type="hidden" id="externo" name="externo" value="<?=$_REQUEST["externo"]?>">
 <table width="40%" border="0" align="center">
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="68%"><label>
      <input name="descripcion" type="text" id="descripcion" size="60">
    </label></td>
  </tr>
  <tr>
    <td width="32%" align="right" class='viewPropTitle'>Urbanizacion</td>
    <td width="68%">
    <select id="idurbanizacion" name="idurbanizacion">
    <?
    $sql_estados = mysql_query("select * from urbanizacion");
	while($bus_estados = mysql_fetch_array($sql_estados)){
		?>
		<option value="<?=$bus_estados["idurbanizacion"]?>"><?=$bus_estados["denominacion"]?></option>
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
              <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" onClick="ingresarCalle()" class="button"></td>
              <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" onClick="modificarCalle()" class="button"></td>
              <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" onClick="eliminarCalle()" class="button"></td>
          </tr>
      </table>
      
      
      
      
      
      
    </label></td>
   </tr>
</table>

<br>
<br>
<div id="listaCalle"></div>
</body>