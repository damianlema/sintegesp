<script src="modulos/rrhh/js/movimientos_personal_ajax.js"></script>
<?
function listar_foros($padre, $titulo) {
	global $foros;
	foreach($foros[$padre] as $foro => $datos) {			
		if(isset($foros[$foro])) {
			$nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
			listar_foros($foro, $nuevo_titulo);
		}else{
		?>
		<option value="<?=$datos['idniveles_organizacionales']?>">
			<?=$titulo ." - ". $datos['denominacion']?>
		</option>
		<?
		}
	}
}
?>
<input type="hidden" name="fecha_ingreso" id="fecha_ingreso">
<br>
	<h4 align=center>Movimientos de Personal</h4>
    <h2 class="sqlmVersion"></h2>
<p>&nbsp;</p>
	<table width="60%" align="center">
      <tr>
        <td width="254" align='right' class='viewPropTitle'>Cedula</td>
        <td width="144">
          <input type="hidden" id="id_trabajador" name="id_trabajador" />
          <input type="hidden" id="idmovimiento" name="idmovimiento" />
          <input type="text" id="cedula_trabajador" name="cedula_trabajador" disabled="disabled"/>
        <img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/lista_trabajador.php?frm=movimientos_personal','','width=900, height=600, scrollbars=yes')" /></td>
        <td width="247">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="26%" align='right' class='viewPropTitle'>Nombre:</td>
            <td width="22%">
              <input type="text" name="nombre_trabajador" id="nombre_trabajador" disabled="disabled"/>
            </td>
            <td width="13%" align='right' class='viewPropTitle'>Apellido</td>
    <td width="39%">
              <input type="text" name="apellido_trabajador" id="apellido_trabajador" disabled="disabled"/>
            </td>
          </tr>
        </table></td>
      </tr>
    </table>
	<br>
    <h2 class="sqlmVersion"></h2>
    <br />

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
                <select id="centro_costo" name="centro_costo">
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
	La Ficha Actual es: <span id="ficha_actual" style="font-weight:bold"></span> Y la deseo cambiar por ->
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
      <div id="nro_ficha" style="font-weight:bold"></div>
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


<div id="listaMovimientos"></div>
