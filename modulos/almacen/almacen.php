<script src="modulos/almacen/js/almacen_ajax.js"></script>
	<br>
	<h4 align=center>Almacen</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
    
        <br />
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<br />
<br />
<input type="hidden" id="idalmacen" name="idalmacen">
<table width="35%" border="0" align="center" cellpadding="4" cellspacing="0">
   <tr>
    <td align='right' class='viewPropTitle'>C&oacute;digo: </td>
    <td>
      <input name="codigo" type="text" id="codigo" size="6">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Denominaci&oacute;n: </td>
    <td>
      <input name="denominacion" type="text" id="denominacion" size="50">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Preseleccionada: </td>
    <td>
      <input type="checkbox" name="preseleccion" id="preseleccion" value="0">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Ubicaci&oacute;n: </td>
    <td>
      <textarea name="ubicacion" cols="60" rows="3" id="ubicacion" autocomplete="OFF" style="padding:0px 20px 0px 0px;"></textarea>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Responsable: </td>
    <td>
      <input name="responsable" type="text" id="responsable" size="50">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>C.I. Responsable: </td>
    <td>
      <input name="ci_responsable" type="text" id="ci_responsable" size="14">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Nro. Telefono: </td>
    <td>
      <input name="telefono" type="text" id="telefono" size="20">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>e-mail: </td>
    <td>
      <input name="email" type="text" id="email" size="50">
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
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarAlmacen()">
        </td>
        <td align="center">
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarAlmacen()">
        </td>
        <td align="center">
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarAlmacen()">
        </td>
        </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<div id="listaAlmacen">
<?
		$sql_consulta = mysql_query("select * from almacen")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="70%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td width="10%" align="center" class="Browse">Presel.</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>&nbsp;
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["denominacion"]?></td>
			<td class='Browse' align="center"><?php if ($bus_consulta["defecto"] == '1'){  echo "<img src='imagenes/accept.gif'>";} else {echo "&nbsp;";} ?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idalmacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["defecto"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["ubicacion"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idalmacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["denominacion"]?>', '<?=$bus_consulta["defecto"]?>', '<?=$bus_consulta["responsable"]?>', '<?=$bus_consulta["ci_responsable"]?>', '<?=$bus_consulta["telefono"]?>', '<?=$bus_consulta["email"]?>', '<?=$bus_consulta["ubicacion"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
</div>
