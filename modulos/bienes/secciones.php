<script src="modulos/bienes/js/secciones_ajax.js"></script>
    <br>
<h4 align=center>
Secciones</h4>
<h2 class="sqlmVersion"></h2>
<br>
<input name="id_seccion" type="hidden" id="id_seccion">
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<table width="60%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Sub Grupo</td>
    <td><label>
      <select name="subgrupos" id="subgrupos">
      <?
      $sql_consultar_subgrupos = mysql_query("select * from subgrupo_catalogo_bienes");
	  $i=0;
	  while($bus_consultar_subgrupos = mysql_fetch_array($sql_consultar_subgrupos)){
	  	if($i==0){
			$codigoSubGrupo = $bus_consultar_subgrupos["codigo"];
		$i++;
		}
		?>
		<option onClick="document.getElementById('codigoSubGrupo').innerHTML='<?=$bus_consultar_subgrupos["codigo"]?>-', document.getElementById('codigoHidden').value='<?=$bus_consultar_subgrupos["codigo"]?>-'" value="<?=$bus_consultar_subgrupos["idsubgrupo_catalogo_bienes"]?>">
			<?=$bus_consultar_subgrupos["denominacion"]?>
        </option>
		<?
	  }
	  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">C&oacute;digo</span></td>
    <td>
      <table align="left" border="0" cellpadding="0" cellspacing="0">
      <tr>
          <td>
          <input type="hidden" name="codigoHidden" id="codigoHidden" value="<?=$codigoSubGrupo?>-">
              <strong>
                  <div id="codigoSubGrupo">
                    <?=$codigoSubGrupo?>-
                  </div>
              </strong>
          </td>
          <td>
          	&nbsp;<input name="codigo" type="text" id="codigo" size="2" maxlength="1" autocomplete="OFF">
          </td>
      </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">Denominaci&oacute;n</span></td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="120">
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
            onClick="ingresarSecciones(document.getElementById('subgrupos').value,
            						document.getElementById('codigoHidden').value,
                                    document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonModificar" 
            id="botonModificar" 
            value="Modificar"
            class="button"
            onClick="modificarSecciones(document.getElementById('id_seccion').value,
            						document.getElementById('subgrupos').value,
                                    document.getElementById('codigoHidden').value,
            						document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonEliminar" 
            id="botonEliminar" 
            value="Eliminar"
            class="button"
            onClick="eliminarSecciones(document.getElementById('id_seccion').value)">    
    </td>
  </tr>
</table>



<br />
<br />
<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">Denominaci&oacute;n</span>&nbsp;</td>
    <td><label>
      <input type="text" name="campoBuscar" id="campoBuscar" />
    </label></td>
    <td><label>
      <input 
      type="submit" 
      name="botonBuscar" 
      id="botonBuscar" 
      value="Buscar" 
      onclick="consultarSecciones(document.getElementById('campoBuscar').value)"
      class="button"/>
    </label></td>
  </tr>
</table>
<br>
<br>

<div id="listaSecciones">
<?
$sql_consulta = mysql_query("select * from secciones_catalogo_bienes");

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
	  				  <thead>
								<tr>
									<td width="13%" align="center" class="Browse">C&oacute;digo</td>
									<td width="75%" align="center" class="Browse">Denominaci&oacute;n</td>
                                  <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse'><?=$bus_consulta["denominacion"]?></td>
                                <td width="6%" align="center" class='Browse'>
                                <?
                                $partes_codigo = explode("-", $bus_consulta["codigo"]);
								$codigoHidden = $partes_codigo[0]."-".$partes_codigo[1];
								$codigo = $partes_codigo[2];
								?>
                                
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idsecciones_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["idsubgrupo_catalogo_bienes"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idsecciones_catalogo_bienes"]?>',
                                    							'<?=$bus_consulta["idsubgrupo_catalogo_bienes"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
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


