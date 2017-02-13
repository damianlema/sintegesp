<script src="modulos/bienes/js/ubicacion_ajax.js"></script>
	<br>
	<h4 align=center>Ubicacion</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
    
        <br />
<center><img src="imagenes/nuevo.png" style="cursor:pointer" onClick="window.location.href='principal.php?modulo=8&accion=766'"></center>
<br />
<br />
<input type="hidden" id="idubicacion" name="idubicacion">
<table width="35%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align='right' class='viewPropTitle'>Código:</td>
    <td>
      <input type="text" name="codigo" id="codigo">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Denominación: </td>
    <td>
      <input name="denominacion" type="text" id="denominacion" size="50">
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table width="50%" border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td align="center">
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarUbicacion()">
        </td>
        <td align="center">
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarUbicacion()">
        </td>
        <td align="center">
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarUbicacion()">
        </td>
        </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<div id="listaUbicaciones">
<?
		$sql_consulta = mysql_query("select * from ubicacion")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
        <tr>
            <td width="10%" align="center" class="Browse">Código</td>
          <td width="50%" align="center" class="Browse">Denominación</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idubicacion"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idubicacion"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
</div>
