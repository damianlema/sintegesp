<script src="modulos/contabilidad/js/subgrupo_ajax.js"></script>

    <br>
<h4 align=center>
Sub Grupo
</h4>
<h2 class="sqlmVersion"></h2>
<br>

<center>
<a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/<?=$_SESSION["rutaReportes"]?>/contabilidad.php?nombre=contabilidad_subgrupo';">
    <img src="imagenes/imprimir.png" border="0" title="Imprimir">
</a>
</center>
<table align="center" width="50%">
	<tr>
    	<td>
            <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
                <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
                <iframe name="pdf" id="pdf" style="display:block" height="500" width="500"></iframe>          
            </div>
        </td>
	</tr>
</table>

<input name="idsubgrupo" type="hidden" id="idsubgrupo"/>
<table width="30%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="right" class='viewPropTitle'>Grupo</td>
    <td><label>
      <select name="idgrupo" id="idgrupo">
      <option value="0">.:: Seleccione ::.</option>
	  <?
      $sql_grupo = mysql_query("select * from grupo_cuentas_contables order by codigo");
	  while($bus_grupo = mysql_fetch_array($sql_grupo)){
	  	?>
		<option value="<?=$bus_grupo["idgrupos_cuentas_contables"]?>">(<?=$bus_grupo["codigo"]?>) <?=$bus_grupo["denominacion"]?></option>
		<?
	  }
	  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Codigo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="2" maxlength="1">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Denominacion</td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="100">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td><label>
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" style=" cursor:pointer" class="button" onClick="ingresarGrupo()">
        </label></td>
        <td><label>
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" style="cursor:pointer; display:none" class="button" onClick="editarGrupo()">
        </label></td>
        <td><label>
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="cursor:pointer; display:none" class="button" onClick="eliminarGrupo()">
        </label></td>
      </tr>
    </table></td>
  </tr>
</table>

<br />
<br />
<form method="post" action="" onSubmit="return listarGrupos()">
<table align="center" id="tabla_buscar">
<tr>
<td align="right" class='viewPropTitle'>Grupo</td>
<td>
	<select name="grupoBuscar" id="grupoBuscar">
      <option value="0">.:: Seleccione ::.</option>
	  <?
      $sql_grupo = mysql_query("select * from grupo_cuentas_contables");
	  while($bus_grupo = mysql_fetch_array($sql_grupo)){
	  	?>
		<option value="<?=$bus_grupo["idgrupos_cuentas_contables"]?>">(<?=$bus_grupo["codigo"]?>) <?=$bus_grupo["denominacion"]?></option>
		<?
	  }
	  ?>
      </select>
</td>
<td align="right" class='viewPropTitle'>Denominacion</td>
<td><input type="text" id="campoBuscar" name="campoBuscar"></td>
<td><input type="submit" id="botonBuscar" name="botonBuscar" value="Buscar" class="button"></td>
</tr>
</table>
</form>

<br />
<div id="listaGrupos">
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="68%">
          <thead>
          <tr>
            <td width="23%" align="center" class="Browse" style="font-size:9px">Grupo</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Codigo</td>
            <td width="58%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query("SELECT grupo_cuentas_contables.codigo as codigo_grupo,
		  										grupo_cuentas_contables.denominacion as denominacion_grupo,
												grupo_cuentas_contables.idgrupos_cuentas_contables as idgrupo,
												subgrupo_cuentas_contables.codigo as codigo_subgrupo,
												subgrupo_cuentas_contables.denominacion as denominacion_subgrupo,
												subgrupo_cuentas_contables.idsubgrupo_cuentas_contables as idsubgrupo
													FROM
												grupo_cuentas_contables, 
												subgrupo_cuentas_contables
													WHERE
												grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo
												 order by codigo_grupo, codigo_subgrupo ");
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="23%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["denominacion_grupo"]?></td>
                    <td class='Browse' align='center' width="6%" style="font-size:10px">&nbsp;<?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["codigo_subgrupo"]?></td>
                    <td class='Browse' align='left' width="58%" style="font-size:10px"><?=$bus_consultar["denominacion_subgrupo"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_subgrupo"]?>', '<?=$bus_consultar["denominacion_subgrupo"]?>', '<?=$bus_consultar["idgrupo"]?>', '<?=$bus_consultar["idsubgrupo"]?>')">                    </td>
                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_subgrupo"]?>', '<?=$bus_consultar["denominacion_subgrupo"]?>', '<?=$bus_consultar["idgrupo"]?>', '<?=$bus_consultar["idsubgrupo"]?>')">                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
</div>