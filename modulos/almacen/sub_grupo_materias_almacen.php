<script src="modulos/almacen/js/sub_grupo_materias_almacen_ajax.js"></script>
    <br>
<h4 align=center>
Sub Grupos</h4>
<h2 class="sqlmVersion"></h2>
<br>
<input name="id_subgrupo" type="hidden" id="id_subgrupo">
<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Grupo">
</a></center>
<table width="60%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Grupo</td>
    <td><label>
      <select name="grupos" id="grupos">
      <?
      $sql_consultar_grupos = mysql_query("select * from grupo_materias_almacen");
	  $i=0;
	  while($bus_consultar_grupos = mysql_fetch_array($sql_consultar_grupos)){
	  	if($i==0){
			$codigoGrupo = $bus_consultar_grupos["codigo"];
		$i++;
		}
		?>
		<option onclick="document.getElementById('codigoGrupo').innerHTML='<?=$bus_consultar_grupos["codigo"]?>-', document.getElementById('codigoHidden').value='<?=$bus_consultar_grupos["codigo"]?>-'" value="<?=$bus_consultar_grupos["idgrupo_materias_almacen"]?>">
			<?=$bus_consultar_grupos["codigo"]."-".$bus_consultar_grupos["denominacion"]?>
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
          <input type="hidden" name="codigoHidden" id="codigoHidden" value="<?=$codigoGrupo?>-">
              <strong>
                  <div id="codigoGrupo">
                    <?=$codigoGrupo?>-
                  </div>
              </strong>
          </td>
          <td>
          	&nbsp;<input name="codigo" type="text" id="codigo" size="2" maxlength="2" autocomplete="OFF">
          </td>
      </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'><span class="Browse">Denominaci&oacute;n</span></td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" value="" size="120" />
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
            onClick="ingresarSubGrupos(document.getElementById('grupos').value,
            						document.getElementById('codigoHidden').value,
                                    document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonModificar" 
            id="botonModificar" 
            value="Modificar"
            class="button"
            onClick="modificarSubGrupos(document.getElementById('id_subgrupo').value,
            						document.getElementById('grupos').value,
                                    document.getElementById('codigoHidden').value,
            						document.getElementById('codigo').value,
            						document.getElementById('denominacion').value)">
      <input type="button"
      		style="display:none" 
      		name="botonEliminar" 
            id="botonEliminar" 
            value="Eliminar"
            class="button"
            onClick="eliminarSubGrupos(document.getElementById('id_subgrupo').value)">    </td>
  </tr>
</table>



<br />
<br />
<table width="302" border="0" align="center" cellpadding="0" cellspacing="0">
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
      onclick="consultarSubGrupos(document.getElementById('campoBuscar').value)"
      class="button"/>
    </label></td>
  </tr>
</table>
<br>
<br>

<div id="listaGrupos">
<?
$sql_consulta = mysql_query("select * from subgrupo_materias_almacen");

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
                                <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                                <td width="6%" align="center" class='Browse'>
                                <?
                                $partes_codigo = explode("-", $bus_consulta["codigo"]);
								$codigoHidden = $partes_codigo[0];
								$codigo = $partes_codigo[1];
								?>
                                
                                	<img src="imagenes/modificar.png" 
                                    	onclick="mostrarEditar('<?=$bus_consulta["idsubgrupo_materias_almacen"]?>',
                                        						'<?=$bus_consulta["idgrupo_materias_almacen"]?>',
                                        						'<?=$codigoHidden?>',
                                                                '<?=$codigo?>',
                                        						'<?=$bus_consulta["denominacion"]?>')" 
                                        style="cursor:pointer">                                </td>
                                <td width="6%" align="center" class='Browse'>
                                	<img src="imagenes/delete.png" 
                                    style="cursor:pointer"
                                    onclick="mostrarEliminar('<?=$bus_consulta["idsubgrupo_materias_almacen"]?>',
                                    							'<?=$bus_consulta["idgrupo_materias_almacen"]?>',
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


