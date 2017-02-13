<script src="modulos/bienes/js/cierre_inventario_ajax.js"></script>
    <br>
<h4 align=center>
Cierre de Inventario</h4>
<h2 class="sqlmVersion"></h2>
<br>
<br>
<table align= "center" border="0">
<tr>
<td align="right" class='viewPropTitle'>Organizacion</td>
<td>
<select name="organizacion" id="organizacion">
<option value="0">.:: Seleccione ::.</option>
<?
$sql_organizacion = mysql_query("select * from organizacion");
while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
?>
	<option value="<?=$bus_organizacion["idorganizacion"]?>" onClick="cargarNivelesOrganizacionales('<?=$bus_organizacion["idorganizacion"]?>')"><?=$bus_organizacion["denominacion"]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td align="right" class='viewPropTitle'>Nivel Organizacional</td>
<td id="campo_nivel_organizacional">
<select name="idniveles_organizacionales" id="idniveles_organizacionales">
	<option value="0">.:: Seleccione la organizacion ::.</option>
</select>
</td>
</tr>
<tr>
<td colspan="2" align="center">
	<table align="center">
    <tr>
    	<td align="center"><input type="button" class="button" name="ingresar_cierre_inventario" id="ingresar_cierre_inventario" value="Cierre el Inventario Inicial" onClick="ingresar_cierreInventario()"></td>
    </tr>
    </table>    
</td>
</tr>
</table>