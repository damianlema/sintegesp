<script src="modulos/bienes/js/tipos_Detalles_ajax.js"></script>
	<br>
	<h4 align=center>Tipos de Detalles</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
    
        <br />
<center><img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?modulo=8&accion=761'"></center>
<br />
<br />
<input type="hidden" id="idtipo_detalle" name="idtipo_detalle">
<table width="35%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align='right' class='viewPropTitle'>Detalle:</td>
    <td>
      <select name="iddetalle" id="iddetalle">
      	<option value="0">.:: Seleccione ::.</option>
		<?
        	$sql_detalles = mysql_query("select * from detalle_catalogo_bienes");
			while($bus_detalles = mysql_fetch_array($sql_detalles)){
				?>
					<option value="<?=$bus_detalles["iddetalle_catalogo_bienes"]?>">
                    	(<?=$bus_detalles["codigo"]?>)&nbsp;<?=$bus_detalles["denominacion"]?>
                    </option>
				<?
			}
		?>
      </select>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tipo: </td>
    <td>
      <input name="tipo" type="text" id="tipo" size="50">
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
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarTipo()">
        </td>
        <td align="center">
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarTipo()">
        </td>
        <td align="center">
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarTipo()">
        </td>
        </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<div id="listaTiposDetalles">
<?
		$sql_consulta = mysql_query("select detalle_catalogo_bienes.codigo,
										detalle_catalogo_bienes.denominacion,
										tipo_detalle.tipo,
										tipo_detalle.idtipo_detalle,
										tipo_detalle.iddetalle 
											from 
										detalle_catalogo_bienes,
										tipo_detalle
											where
										detalle_catalogo_bienes.iddetalle_catalogo_bienes = tipo_detalle.iddetalle")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
    	<thead>
        <tr>
            <td width="39%" align="center" class="Browse">Detalle</td>
          <td width="50%" align="center" class="Browse">Tipo de Detalle</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>
        </tr>
        </thead>
        <?
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		?>
        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'>(<?=$bus_consulta["codigo"]?>)&nbsp;<?=$bus_consulta["denominacion"]?></td>
          	<td class='Browse'><?=$bus_consulta["tipo"]?></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idtipo_detalle"]?>', '<?=$bus_consulta["iddetalle"]?>', '<?=$bus_consulta["tipo"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idtipo_detalle"]?>', '<?=$bus_consulta["iddetalle"]?>', '<?=$bus_consulta["tipo"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
</div>
