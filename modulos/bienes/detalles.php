<script src="modulos/bienes/js/detalles_ajax.js"></script>
    <br>
<h4 align=center>
Detalles</h4>
<h2 class="sqlmVersion"></h2>
<br>
<input name="id_detalles" type="hidden" id="id_detalles">
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<table width="60%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Secci&oacute;n</td>
    <td><label>
      <select name="secciones" id="secciones">
      <?
      $sql_consultar_secciones= mysql_query("select * from secciones_catalogo_bienes");
  	  $i=0;
  	  while($bus_consultar_secciones = mysql_fetch_array($sql_consultar_secciones)){
  	  	if($i==0){
    			$codigoDetalles = $bus_consultar_secciones["codigo"];
    		  $i++;
  		  }
    		?>
    		<option onClick="document.getElementById('codigoDetalle').innerHTML='<?=$bus_consultar_secciones["codigo"]?>-', 
                          document.getElementById('codigoHidden').value='<?=$bus_consultar_secciones["codigo"]?>-'" 
                          value="<?=$bus_consultar_secciones["idsecciones_catalogo_bienes"]?>">
  			       <?=$bus_consultar_secciones["denominacion"]?>
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
          <input type="hidden" name="codigoHidden" id="codigoHidden" value="<?=$codigoDetalles?>-">
              <strong>
                  <div id="codigoDetalle">
                    <?=$codigoDetalles?>-
                  </div>
              </strong>
          </td>
          <td>
          	&nbsp;<input name="codigo" type="text" id="codigo" size="3" maxlength="3" autocomplete="OFF">
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
            onClick="ingresarDetalle(document.getElementById('secciones').value,
            						document.getElementById('codigoHidden').value,
                                    document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonModificar" 
            id="botonModificar" 
            value="Modificar"
            class="button"
            onClick="modificarDetalles(document.getElementById('id_detalles').value,
            						document.getElementById('secciones').value,
                                    document.getElementById('codigoHidden').value,
            						document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonEliminar" 
            id="botonEliminar" 
            value="Eliminar"
            class="button"
            onClick="eliminarDetalles(document.getElementById('id_detalles').value)">    
    </td>
  </tr>
</table>



<br />
<br />
<table width="307" border="0" align="center" cellpadding="0" cellspacing="0">
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
      onclick="consultarDetalles(document.getElementById('campoBuscar').value)"
      class="button"/>
    </label></td>
  </tr>
</table>
<br>
<br>

<div id="listaDetalles">
<?
$sql_consulta = mysql_query("select * from detalle_catalogo_bienes");

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
                                <?
                                $partes_codigo = explode("-", $bus_consulta["codigo"]);
								$codigoHidden = $partes_codigo[0]."-".$partes_codigo[1]."-".$partes_codigo[2];
								$codigo = $partes_codigo[3];
								?>
                                
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>',
                                        						'<?=$bus_consulta["idsecciones_catalogo_bienes"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>',
                                    							'<?=$bus_consulta["idsecciones_catalogo_bienes"]?>',
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


