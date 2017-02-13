<script src="modulos/almacen/js/marca_materia_ajax.js"></script>
	<br>
	<h4 align=center>Marca</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
    
        <br />
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nueva Marca">
</a></center>
<br />
<br />
<input type="hidden" id="idmarca" name="idmarca">
<input type="hidden" id="externo" name="externo" value="<?=$_REQUEST["pop"]?>">
<table width="35%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align='right' class='viewPropTitle'>Denominación: </td>
    <td>
      <input name="denominacion" type="text" id="denominacion" size="50">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Preseleccionada:</td>
    <td>
      <input type="checkbox" name="preseleccion" id="preseleccion" value="0">
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
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarMarca()">
        </td>
        <td align="center">
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarMarca()">
        </td>
        <td align="center">
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarMarca()">
        </td>
        </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<div id="listaMarca">
<?
		$sql_consulta = mysql_query("select * from marca_materia order by denominacion")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="30%" align="center">
   	  <thead>
        <tr>
          <td width="80%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="10%" align="center" class="Browse">Presel.</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>&nbsp;
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
			<td class='Browse' align="center"><?php if ($bus_consulta["seleccionada"] == '1'){  echo "<img src='imagenes/accept.gif'>";} else {echo "&nbsp;";} ?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idmarca_materia"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["seleccionada"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idmarca_materia"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["seleccionada"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
</div>
