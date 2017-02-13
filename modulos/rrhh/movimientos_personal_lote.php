<script src="modulos/rrhh/js/movimientos_personal_lote_ajax.js"></script>
<br>
	<h4 align=center>Movimientos de Personal en Lote</h4>
<h2 class="sqlmVersion"></h2>
<br>
<br>
    <table width="95%" border="0" align="center">
      <tr>
        <td class='viewPropTitle'>Centro de Costo</td>
        <td>
        
        <?
    $sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
												un.denominacion,
												ct.codigo
												from 
												niveles_organizacionales no,
												unidad_ejecutora un,
												categoria_programatica ct
												where 
												no.modulo='1' 
												and no.idcategoria_programatica != '0'
												and ct.idcategoria_programatica = no.idcategoria_programatica
												and ct.idunidad_ejecutora = un.idunidad_ejecutora
												order by ct.codigo");
		
		?>
                <select id="centro_costo" name="centro_costo">
                  <option value="0">.:: Seleccione ::.</option>
                  <?
                  while($bus_centro_costo = mysql_fetch_array($sql_centro_costo)){
					  ?>
					  <option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
					  <?
					}
				  ?>
                </select>        </td>
      </tr>
      <tr>
        <td class='viewPropTitle'>Unidad Funcional</td>
        <td>
        <?		
		$sql_ubicacion_funcional = mysql_query("select * from niveles_organizacionales where modulo = '1' order by codigo");
			?>
                <select id="unidad_funcional" name="unidad_funcional">
                  <option value="0">.:: Seleccione ::.</option>
     		<?
     		while($bus_ubicacion_funcional = mysql_fetch_array($sql_ubicacion_funcional)){
				?>
				<option value="<?=$bus_ubicacion_funcional["idniveles_organizacionales"]?>">(<?=$bus_ubicacion_funcional["codigo"]?>)&nbsp;<?=$bus_ubicacion_funcional["denominacion"]?>
                </option>
				<?	
			}
	 ?>
                </select>        </td>
      </tr>
      <tr>
        <td class='viewPropTitle'>Cargo</td>
        <td>
        
        <?
      $sql_cargos = mysql_query("select * from cargos order by denominacion");
	  ?>
      <select id="cargo" name="cargo">
		<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_cargos = mysql_fetch_array($sql_cargos)){
		?>
        <option value="<?=$bus_cargos["idcargo"]?>"><?=$bus_cargos["denominacion"]?></option>
        <?
        }
		?>
      </select>        </td>
      </tr>
      <tr>
        <td class='viewPropTitle'>Prefijo Ficha</td>
        <td>
        <div>
		<?		
		$sql_ficha = mysql_query("select * from nomenclatura_fichas");
			?>
                <select id="ficha_busqueda" name="ficha_busqueda">
                  <option value="0">-</option>
     		<?
     		while($bus_ficha= mysql_fetch_array($sql_ficha)){
				?>
				<option value="<?=$bus_ficha["descripcion"]?>"><?=$bus_ficha["descripcion"]?>
                </option>
				<?	
			}
	 ?>
                </select>
&nbsp;      </div></td>
      </tr>

      <tr>
        <td class='viewPropTitle'>Tipo de Nomina</td>
        <td>
          <div>
          <? $sql_nomina = mysql_query("select * from tipo_nomina"); ?>
            <select id="tipo_nomina" name="tipo_nomina">
              <option value="0">.:: Seleccione ::.</option>
              <?
                while($bus_nomina= mysql_fetch_array($sql_nomina)){
                ?>
                <option value="<?=$bus_nomina["idtipo_nomina"]?>"><?=$bus_nomina["titulo_nomina"]?></option>
                <?  
                }
                ?>
            </select>
          </div>
        </td>
      </tr>


      <tr>
        <td colspan="2" align="center"><label>
        <input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="button" onClick="buscarTrabajadores()">
        </label></td>
      </tr>
    </table>
<br>
<center><div id="listaTrabajadores" style="height:200px; overflow:auto; border:1px solid #000000; display:none; width:80%"></div></center>
<br>




<div id="formulario_movimiento" style="display:none">

<input type="hidden" name="fecha_ingreso" id="fecha_ingreso">


<table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align='right' class='viewPropTitle'>Fecha del Movimiento</td>
    <td>
    
      <table width="42%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="41%"><input name="fecha_movimiento" type="text" id="fecha_movimiento" size="12" value="<?=date("Y-m-d")?>"></td>
          <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_c" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_movimiento",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>          </td>
        </tr>
      </table></td>
    <td align='right' class='viewPropTitle'>Tipo de Movimiento</td>
    <td><label>
      <?
      $sql_tipos_movimientos = mysql_query("select * from tipo_movimiento_personal");
	  ?>
      <select name="tipo_movimiento" id="tipo_movimiento">
      <option value="0" onclick="desaparecerCampos()">.:: Seleccione ::.</option>
	  <?
	  while($bus_tipos_movimientos = mysql_fetch_array($sql_tipos_movimientos)){
	  	?>
		<option value="<?=$bus_tipos_movimientos["idtipo_movimiento"]?>" onclick="seleccionarIngresar('<?=$bus_tipos_movimientos["relacion_laboral"]?>', '<?=$bus_tipos_movimientos["goce_sueldo"]?>', '<?=$bus_tipos_movimientos["afecta_cargo"]?>', '<?=$bus_tipos_movimientos["afecta_ubicacion"]?>', '<?=$bus_tipos_movimientos["afecta_tiempo"]?>', '<?=$bus_tipos_movimientos["afecta_centro_costo"]?>', '<?=$bus_tipos_movimientos["afecta_ficha"]?>')"><?=$bus_tipos_movimientos["denominacion"]?></option>
		<?
	  }
      ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Justificacion</td>
    <td colspan="3"><label>
      <textarea name="justificacion" cols="80" rows="5" id="justificacion"></textarea>
    </label></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>
    <div id="celda_centro_costo" style="display:none">Centro de Costo</div>
    </td>
    <td colspan="3">
    <?
    $sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
												un.denominacion,
												ct.codigo
												from 
												niveles_organizacionales no,
												unidad_ejecutora un,
												categoria_programatica ct
												where 
												no.modulo='1' 
												and no.idcategoria_programatica != '0'
												and ct.idcategoria_programatica = no.idcategoria_programatica
												and ct.idunidad_ejecutora = un.idunidad_ejecutora
												order by ct.codigo");
		
		?>
                <div id="campo_centro_costo" style="display:none">
                <select id="centro_costo_nuevo" name="centro_costo_nuevo">
                  <option value="0">.:: Seleccione ::.</option>
                  <?
                  while($bus_centro_costo = mysql_fetch_array($sql_centro_costo)){
					  ?>
					  <option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
					  <?
					}
				  ?>
                </select>    
                </div>
      </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Causal</td>
    <td>
      <input type="text" name="causal" id="causal" />    </td>
    <td align='right' class='viewPropTitle'>
        <div id="celda_nombre_fecha_egreso" style="display:none">
            Fecha de Egreso:        </div>    </td>
    <td>
    <div id="celda_campo_fecha_egreso" style="display:none">
    <table width="42%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="41%"><input name="fecha_egreso" type="text" id="fecha_egreso" size="12" /></td>
        <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_e" id="f_trigger_e" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
            <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_egreso",
							button        : "f_trigger_e",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>        </td>
      </tr>
    </table>      
    </div>    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>
    <div id="celda_nombre_nuevo_cargo" style="display:none">
    Cargo    </div>    </td>
    <td colspan="3">
    <div id="celda_campo_nuevo_cargo" style="display:none">
      <?
      $sql_cargos = mysql_query("select * from cargos order by denominacion");
	  ?>
      <select id="nuevo_cargo" name="nuevo_cargo">
		<option value="0">.:: Seleccione ::.</option>
		<?
        while($bus_cargos = mysql_fetch_array($sql_cargos)){
		?>
        <option value="<?=$bus_cargos["idcargo"]?>"><?=$bus_cargos["denominacion"]?></option>
        <?
        }
		?>
      </select>
    </div>    <label></label></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>
    <div id="celda_nombre_nueva_ubicacion_funcional" style="display:none">
    Ubicacion Funcional:    </div>    </td>
    <td colspan="3">
	<div id="celda_campo_nueva_ubicacion_funcional" style="display:none">
	<?		
		$sql_ubicacion_funcional = mysql_query("select * from niveles_organizacionales where modulo = '1' order by codigo");
			?>
                <select id="nueva_ubicacion_funcional" name="nueva_ubicacion_funcional">
                  <option value="0">.:: Seleccione ::.</option>
     		<?
     		while($bus_ubicacion_funcional = mysql_fetch_array($sql_ubicacion_funcional)){
				?>
				<option value="<?=$bus_ubicacion_funcional["idniveles_organizacionales"]?>">(<?=$bus_ubicacion_funcional["codigo"]?>)&nbsp;<?=$bus_ubicacion_funcional["denominacion"]?>
                </option>
				<?	
			}
	 ?>
                </select>
      </div>      </td>
  </tr>
  
  
  
  
  <tr>
    <td align='right' class='viewPropTitle'>
    <div id="celda_ficha" style="display:none">
    Ficha:    </div>    </td>
    <td colspan="3">
	<div id="celda_campo_ficha" style="display:none; float:left">
	<?		
		$sql_ficha = mysql_query("select * from nomenclatura_fichas");
			?>
                <select id="ficha" name="ficha">
                  <option value="0">-</option>
     		<?
     		while($bus_ficha= mysql_fetch_array($sql_ficha)){
				?>
				<option onclick="consultarFicha(this.value)" value="<?=$bus_ficha["descripcion"]?>"><?=$bus_ficha["descripcion"]?>
                </option>
				<?	
			}
	 ?>
                </select>
                &nbsp;
      </div>
      <div id="nro_ficha" style="font-weight:bold" onmouseover="document.getElementById('explicacion').style.display = 'block'" onmouseout="document.getElementById('explicacion').style.display = 'none'"></div>
      <div style="position:absolute; width:300px; height:100px; display:none; background-color:#FFFFCC; text-align:justify; border:#000000 solid 1px; padding:10px" id="explicacion">Es importante que sepa que si selecciono varios trabajadores este numero de ficha se le colocara al primero de ellos y de alli en adelante el sistema ira incrementando 1 el numero y asignandoselo a cada trabajador</div>
      <input type="hidden" name="campo_nro_ficha" id="campo_nro_ficha">
      </td>
  </tr>
  
  
  
  
  
  <tr>
    <td align='right' class='viewPropTitle'>
    <div id="celda_nombre_fecha_reingreso" style="display:none">
    Fecha de Reingreso:    </div>    </td>
    <td>
    <div id="celda_campo_fecha_reingreso" style="display:none">
    
      <table width="42%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="41%"><input name="fecha_reingreso" type="text" id="fecha_reingreso" size="12"></td>
          <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_r" id="f_trigger_r" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
              <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_reingreso",
							button        : "f_trigger_r",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>          </td>
        </tr>
      </table>
    </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Desde</td>
    <td><label>
      </label>
      <table width="42%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="41%"><input name="desde" type="text" id="desde" size="12" /></td>
          <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_d" id="f_trigger_d" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
              <script type="text/javascript">
							Calendar.setup({
							inputField    : "desde",
							button        : "f_trigger_d",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>          </td>
        </tr>
      </table>
    <label></label></td>
    <td align='right' class='viewPropTitle'>Hasta</td>
    <td><label>
      </label>
      <table width="42%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="41%"><input name="hasta" type="text" id="hasta" size="12" /></td>
          <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_h" id="f_trigger_h" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
              <script type="text/javascript">
							Calendar.setup({
							inputField    : "hasta",
							button        : "f_trigger_h",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>          </td>
        </tr>
      </table>
    <label></label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><table width="2%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><label>
          <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onclick="ingresarMovimiento()">
        </label></td>
        <td><label>
          <input type="submit" name="boton_editar" id="boton_editar" value="Modificar" class="button" style="display:none" onclick="modificarMovimiento(document.getElementById('idmovimiento').value)">
        </label></td>
        <td><label>
          <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none">
        </label></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</table>


<br />
<br />


</div>
