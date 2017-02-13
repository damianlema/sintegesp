<script src="modulos/contabilidad/js/desagregacion_ajax.js"></script>

    <br>
<h4 align=center>
Desagregacion
</h4>
<h2 class="sqlmVersion"></h2>
<br>

<center>
<a href="#" onClick="document.getElementById('divTipoOrden').style.display='block'; pdf.location.href='lib/<?=$_SESSION["rutaReportes"]?>/contabilidad.php?nombre=contabilidad_desagregacion';">
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

<input name="iddesagregacion" type="hidden" id="iddesagregacion"/>
<table width="50%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="right" class='viewPropTitle'>Sub cuenta de Segundo Nivel</td>
    <td><label>
      <?
      $sql_grupo = mysql_query("select grupo_cuentas_contables.codigo as codigo_grupo,
				subgrupo_cuentas_contables.codigo as codigo_subgrupo,
				rubro_cuentas_contables.codigo as codigo_rubro,
				cuenta_cuentas_contables.codigo as codigo_cuenta,
				subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
				subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.denominacion as denominacion_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idsubcuenta_segundo
					FROM 
				grupo_cuentas_contables, 
				subgrupo_cuentas_contables, 
				rubro_cuentas_contables, 
				cuenta_cuentas_contables,
				subcuenta_primer_cuentas_contables,
				subcuenta_segundo_cuentas_contables
					WHERE 
				subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
				and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
				and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
				and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo 
				and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo
				order by codigo_grupo, codigo_subgrupo, codigo_rubro, codigo_cuenta, codigo_subcuenta_primer, codigo_subcuenta_segundo")or die(mysql_error());
	  ?>
      <select name="idsubcuenta_segundo" id="idsubcuenta_segundo">
      <option value="0">.:: Seleccione ::.</option>
	  <?
	  while($bus_grupo = mysql_fetch_array($sql_grupo)){
	  	?>
		<option value="<?=$bus_grupo["idsubcuenta_segundo"]?>">(<?=$bus_grupo["codigo_grupo"]?>.<?=$bus_grupo["codigo_subgrupo"]?>.<?=$bus_grupo["codigo_rubro"]?>.<?=$bus_grupo["codigo_cuenta"]?>.<?=$bus_grupo["codigo_subcuenta_primer"]?>.<?=$bus_grupo["codigo_subcuenta_segundo"]?>) <?=$bus_grupo["denominacion_subcuenta_segundo"]?></option>
		<?
	  }
	  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Codigo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="8" maxlength="6">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Denominacion</td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="35">
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
				cuenta_cuentas_contables.codigo as codigo_cuenta,
				subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
				subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.denominacion as denominacion_subcuenta_segundo,
				subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idsubcuenta_segundo
					FROM 
				grupo_cuentas_contables, 
				subgrupo_cuentas_contables, 
				rubro_cuentas_contables, 
				cuenta_cuentas_contables,
				subcuenta_primer_cuentas_contables,
				subcuenta_segundo_cuentas_contables
					WHERE 
				subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
				and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
				and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
				and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo 
				and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo")or die(mysql_error());
	  ?>
      <select name="subcuenta_segundoBuscar" id="subcuenta_segundoBuscar">
      <option value="0">.:: Seleccione ::.</option>
	  <?
	  while($bus_grupo = mysql_fetch_array($sql_grupo)){
	  	?>
		<option value="<?=$bus_grupo["idsubcuenta_segundo"]?>">(<?=$bus_grupo["codigo_grupo"]?>.<?=$bus_grupo["codigo_subgrupo"]?>.<?=$bus_grupo["codigo_rubro"]?>.<?=$bus_grupo["codigo_cuenta"]?>.<?=$bus_grupo["codigo_subcuenta_primer"]?>.<?=$bus_grupo["codigo_subcuenta_segundo"]?>) <?=$bus_grupo["denominacion_subcuenta_segundo"]?></option>
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
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="85%">
          <thead>
          <tr>
            <td width="40%" align="center" class="Browse" style="font-size:9px">Sub cuenta de Segundo Orden</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">C&oacute;digo</td>
            <td width="50%" align="center" class="Browse" style="font-size:9px">Denominaci&oacute;n</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query("SELECT grupo_cuentas_contables.codigo as codigo_grupo,
			subgrupo_cuentas_contables.codigo as codigo_subgrupo,
			rubro_cuentas_contables.codigo as codigo_rubro,
			cuenta_cuentas_contables.codigo as codigo_cuenta,
			subcuenta_primer_cuentas_contables.codigo as codigo_subcuenta_primer,
			subcuenta_segundo_cuentas_contables.codigo as codigo_subcuenta_segundo,
			subcuenta_segundo_cuentas_contables.denominacion as denominacion_subcuenta_segundo,
			subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables as idsubcuenta_segundo,
			desagregacion_cuentas_contables.codigo as codigo_desagregacion,
			desagregacion_cuentas_contables.denominacion as denominacion_desagregacion,
			desagregacion_cuentas_contables.iddesagregacion_cuentas_contables as iddesagregacion
				FROM 
			grupo_cuentas_contables,
			subgrupo_cuentas_contables,
			rubro_cuentas_contables,
			cuenta_cuentas_contables,
			subcuenta_primer_cuentas_contables,
			subcuenta_segundo_cuentas_contables,
			desagregacion_cuentas_contables
				WHERE
			subcuenta_segundo_cuentas_contables.idsubcuenta_segundo_cuentas_contables = desagregacion_cuentas_contables.idsubcuenta_segundo
			and subcuenta_primer_cuentas_contables.idsubcuenta_primer_cuentas_contables = subcuenta_segundo_cuentas_contables.idsubcuenta_primer
			and cuenta_cuentas_contables.idcuenta_cuentas_contables = subcuenta_primer_cuentas_contables.idcuenta
			and rubro_cuentas_contables.idrubro_cuentas_contables = cuenta_cuentas_contables.idrubro
			and subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
			and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo 
			order by codigo_grupo, codigo_subgrupo, codigo_rubro, codigo_cuenta, codigo_subcuenta_primer, codigo_subcuenta_segundo, codigo_desagregacion")or die(mysql_error());
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="40%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"].".".$bus_consultar["codigo_rubro"].".".$bus_consultar["codigo_cuenta"].".".$bus_consultar["codigo_subcuenta_primer"].".".$bus_consultar["codigo_subcuenta_segundo"].". ".$bus_consultar["denominacion_subcuenta_segundo"]?></td>
                    <td class='Browse' align='left' width="10%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"].".".$bus_consultar["codigo_subgrupo"]?>.<?=$bus_consultar["codigo_rubro"]?>.<?=$bus_consultar["codigo_cuenta"]?>.<?=$bus_consultar["codigo_subcuenta_primer"]?>.<?=$bus_consultar["codigo_subcuenta_segundo"]?>.<?=$bus_consultar["codigo_desagregacion"]?></td>
                    <td class='Browse' align='left' width="50%" style="font-size:10px"><?=$bus_consultar["denominacion_desagregacion"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_desagregacion"]?>', '<?=$bus_consultar["denominacion_desagregacion"]?>', '<?=$bus_consultar["idsubcuenta_segundo"]?>', '<?=$bus_consultar["iddesagregacion"]?>')">                    
                    </td>

                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_desagregacion"]?>', '<?=$bus_consultar["denominacion_desagregacion"]?>', '<?=$bus_consultar["idsubcuenta_segundo"]?>', '<?=$bus_consultar["iddesagregacion"]?>')">                    
                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
</div>