<script src="modulos/contabilidad/js/cuenta_ajax.js"></script>

    <br>
<h4 align=center>
Cuenta
</h4>
<h2 class="sqlmVersion"></h2>
<br>

<center>
<a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/<?=$_SESSION["rutaReportes"]?>/contabilidad.php?nombre=contabilidad_cuenta';">
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

<input name="idcuenta" type="hidden" id="idcuenta"/>
<table width="30%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="right" class='viewPropTitle'>Rubro</td>
    <td><label>
      <?
      $sql_grupo = mysql_query("select grupo_cuentas_contables.codigo as codigo_grupo,
	  									subgrupo_cuentas_contables.codigo as codigo_subgrupo,
										rubro_cuentas_contables.codigo as codigo_rubro,
										rubro_cuentas_contables.denominacion as denominacion_rubro,
										rubro_cuentas_contables.idrubro_cuentas_contables as idrubro
	  										FROM 
										grupo_cuentas_contables, subgrupo_cuentas_contables, rubro_cuentas_contables
											WHERE 
										subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo 
										and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo
										order by codigo_grupo, codigo_subgrupo, codigo_rubro")or die(mysql_error());
	  ?>
      <select name="idrubro" id="idrubro">
      <option value="0">.:: Seleccione ::.</option>
	  <?
	  while($bus_grupo = mysql_fetch_array($sql_grupo)){
	  	?>
		<option value="<?=$bus_grupo["idrubro"]?>">(<?=$bus_grupo["codigo_grupo"]?><?=$bus_grupo["codigo_subgrupo"]?><?=$bus_grupo["codigo_rubro"]?>) <?=$bus_grupo["denominacion_rubro"]?></option>
		<?
	  }
	  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Codigo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="4" maxlength="4">
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
	<?
      $sql_grupo = mysql_query("select grupo_cuentas_contables.codigo as codigo_grupo,
	  									subgrupo_cuentas_contables.codigo as codigo_subgrupo,
										rubro_cuentas_contables.codigo as codigo_rubro,
										rubro_cuentas_contables.denominacion as denominacion_rubro,
										rubro_cuentas_contables.idrubro_cuentas_contables as idrubro
	  										FROM 
										grupo_cuentas_contables, subgrupo_cuentas_contables, rubro_cuentas_contables
											WHERE 
										subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo 
										and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo")or die(mysql_error());
	  ?>
      <select name="rubroBuscar" id="rubroBuscar">
      <option value="0">.:: Seleccione ::.</option>
	  <?
	  while($bus_grupo = mysql_fetch_array($sql_grupo)){
	  	?>
		<option value="<?=$bus_grupo["idrubro"]?>">(<?=$bus_grupo["codigo_grupo"]?><?=$bus_grupo["codigo_subgrupo"]?><?=$bus_grupo["codigo_rubro"]?>) <?=$bus_grupo["denominacion_rubro"]?></option>
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
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="78%">
          <thead>
          <tr>
            <td width="35%" align="center" class="Browse" style="font-size:9px">Rubro</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">C&oacute;digo</td>
            <td width="58%" align="center" class="Browse" style="font-size:9px">Denominaci&oacute;n</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query("SELECT grupo_cuentas_contables.codigo as codigo_grupo,
		  										subgrupo_cuentas_contables.codigo as codigo_subgrupo,
												rubro_cuentas_contables.codigo as codigo_rubro,
												rubro_cuentas_contables.denominacion as denominacion_rubro,
												rubro_cuentas_contables.idrubro_cuentas_contables as idrubro,
												cuenta_cuentas_contables.idcuenta_cuentas_contables as idcuenta,
												cuenta_cuentas_contables.codigo as codigo_cuenta,
												cuenta_cuentas_contables.denominacion as denominacion_cuenta
													FROM 
												grupo_cuentas_contables,
												subgrupo_cuentas_contables,
												rubro_cuentas_contables,
												cuenta_cuentas_contables
													WHERE
												rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
												and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
												and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo
												order by codigo_grupo, codigo_subgrupo, codigo_rubro, codigo_cuenta")or die(mysql_error());
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="35%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["codigo_subgrupo"]." ".$bus_consultar["codigo_rubro"]." ".$bus_consultar["denominacion_rubro"]?></td>
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["codigo_subgrupo"]?>&nbsp;<?=$bus_consultar["codigo_rubro"]?>.<?=$bus_consultar["codigo_cuenta"]?></td>
                    <td class='Browse' align='left' width="58%" style="font-size:10px"><?=$bus_consultar["denominacion_cuenta"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_cuenta"]?>', '<?=$bus_consultar["denominacion_cuenta"]?>', '<?=$bus_consultar["idrubro"]?>', '<?=$bus_consultar["idcuenta"]?>')">                    
                    </td>
                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_cuenta"]?>', '<?=$bus_consultar["denominacion_cuenta"]?>', '<?=$bus_consultar["idrubro"]?>', '<?=$bus_consultar["idcuenta"]?>')">                    
                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
</div>