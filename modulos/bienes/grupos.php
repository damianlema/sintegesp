<script src="modulos/bienes/js/grupos_ajax.js"></script>
    <br>
<h4 align=center>
Grupos</h4>
<h2 class="sqlmVersion"></h2>
<br>
<input name="id_grupo" type="hidden" id="id_grupo">
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<table width="200" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">C&oacute;digo</span></td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="2" maxlength="1" autocomplete="OFF">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">Denominaci&oacute;n</span></td>
    <td><label>
      <input type="text" name="denominacion" id="denominacion">
    </label></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="button" 
      		name="botonProcesar" 
            id="botonProcesar" 
            value="Ingresar"
            class="button"
            style="display:block"
            onClick="ingresarGrupos(document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonModificar" 
            id="botonModificar" 
            value="Modificar"
            class="button"
            onClick="modificarGrupos(document.getElementById('id_grupo').value,
            						document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonEliminar" 
            id="botonEliminar" 
            value="Eliminar"
            class="button"
            onClick="eliminarGrupos(document.getElementById('id_grupo').value)">
    </td>
  </tr>
</table>



<br>
<br>

<div id="listaGrupos">
<?
$sql_consulta = mysql_query("select * from grupo_catalogo_bienes");

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
<thead>
								<tr>
									<td width="12%" align="center" class="Browse">C&oacute;digo</td>
								  <td width="76%" align="center" class="Browse">Denominaci&oacute;n</td>
                                  <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idgrupo_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                          <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idgrupo_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["codigo"]?>',
                                        						'<?=$bus_consulta["denominacion"]?>')">                                </td>
                          </tr>
							<?
							}
							?>
							
								
						</table>
                        
					
      </td>
        </tr>
    </table>
</div>



