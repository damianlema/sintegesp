<script src="modulos/almacen/js/tipo_detalle_materias_almacen_ajax.js"></script>
	<br>
	<h4 align=center>Tipos de Detalles</h4>
	<h2 class="sqlmVersion"></h2>
    <br>
    
        <br />
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
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
        	$sql_detalles = mysql_query("select * from detalle_materias_almacen");
			while($bus_detalles = mysql_fetch_array($sql_detalles)){
				?>
					<option value="<?=$bus_detalles["iddetalle_materias_almacen"]?>">
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
		$sql_consulta = mysql_query("select detalle_materias_almacen.codigo,
										detalle_materias_almacen.denominacion,
										tipo_detalle_almacen.tipo,
										tipo_detalle_almacen.idtipo_detalle_almacen,
										tipo_detalle_almacen.iddetalle_materias_almacen 
											from 
										detalle_materias_almacen,
										tipo_detalle_almacen
											where
										detalle_materias_almacen.iddetalle_materias_almacen = tipo_detalle_almacen.iddetalle_materias_almacen")or die(mysql_error());
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
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["idtipo_detalle_almacen"]?>', '<?=$bus_consulta["iddetalle_materias_almacen"]?>', '<?=$bus_consulta["tipo"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["idtipo_detalle_almacen"]?>', '<?=$bus_consulta["iddetalle_materias_almacen"]?>', '<?=$bus_consulta["tipo"]?>')"></td>
      </tr>
        <?
        }
		?>
        </table>
</div>
