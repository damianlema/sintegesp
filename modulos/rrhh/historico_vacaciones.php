<script language="javascript">
function abreVentana(){
	miPopup=window.open("lib/listas/lista_trabajador.php?frm=historico_vacaciones","historico_vacaciones","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}

function llenarGrilla(id){
llenargrilla(id);
limpiarCampos();
}

</script>
<script language="javascript" src="js/historico_vacaciones_ajax.js"></script>
<script language="php" src="lib/historico_vacaciones_ajax.php"></script>
<script language="javascript" src="modulos/rrhh/js/historico_vacaciones_ajax.js"></script>
<?
	$btn="<input type='submit' name='accion' id='accion' value='Guardar' class='button' onclick='accionRegistro(this.value)' size='4'/>";
?>
<div id="error"></div>
<h4 align=center>Hist&oacute;rico Vacaciones</h4>
<table width="943" border="0" align="center">
  <tr>
    <td width="72">&nbsp;</td>
    <td width="180"><input type="hidden"  name="id_trabajador" id="id_trabajador"/></td>
    <td width="87" class='viewPropTitle'><div align="right">C&eacute;dula:</div></td>
    <td width="242">
    <input type="text" name="cedula_trabajador" readonly="readonly" id="cedula_trabajador" size="30" />
      <button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onclick="abreVentana()"><img src='imagenes/search0.png' title="Buscar Trabajador" /></button></td>
    <td width="60">&nbsp;</td>
    <td width="276"><input type="hidden"  id="idhistorico_vacaciones" name="idhistorico_vacaciones"/></td>
  </tr>
  <tr>
    <td class='viewPropTitle'><div align="right">Nombres:</div></td>
    <td><input type="text" name="nombre_trabajador" id="nombre_trabajador" size="30" readonly="readonly"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class='viewPropTitle'><div align="right">Apellidos:</div></td>
    <td><input type="text" size="30" name="apellido_trabajador" id="apellido_trabajador" readonly="readonly"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<h2 class="sqlmVersion"></h2>
<p>&nbsp;</p>

<table width="100%" border="0" align="center" cellspacing="4">
  <tr>
    <td width="168" align="right" class="viewPropTitle">Per&iacute;odo:</td>
    <td width="150"><input type="text" size="25" maxlength="9" name="periodo" id="periodo" onkeyup="validarPeriodo(document.getElementById('periodo').value)"/></td>
    <td colspan="2"><p align="left"><font size="1"><b>Ejemplo: 2009-2010</b></font></p></td>
    <td width="137">&nbsp;</td>
    <td width="150">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">N&uacute;mero Memorandum:</td>
    <td><input type="text" size="25" id="numero_memorandum" name="numero_memorandum"/></td>
    <td width="163" align="right" class="viewPropTitle">Fecha Memorandum:</td>
    <td width="173"><input type="text" name="fecha_memorandum" id="fecha_memorandum" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" alt="" name="fecha_memorandum_cal" width="16" height="16" id="fecha_memorandum_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
      <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_memorandum",
							button        : "fecha_memorandum_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Fecha Inicio Vacaci&oacute;n:</td>
    <td><input type="text" name="fecha_inicio_vacacion" id="fecha_inicio_vacacion" onchange="validarFechas(document.getElementById('fecha_inicio_vacacion').value, document.getElementById('fecha_culminacion_vacacion').value)" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" name="fecha_inicio_vacacion_cal" width="16" height="16" id="fecha_inicio_vacacion_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
    <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_inicio_vacacion",
							button        : "fecha_inicio_vacacion_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    <td align="right" class="viewPropTitle">Fecha Culminaci&oacute;n:</td>
    <td><input type="text" name="fecha_culminacion_vacacion" id="fecha_culminacion_vacacion" onchange="validarFechas(document.getElementById('fecha_inicio_vacacion').value, document.getElementById('fecha_culminacion_vacacion').value)" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" name="fecha_culminacion_vacacion_cal" width="16" height="16" id="fecha_culminacion_vacacion_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
    <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_culminacion_vacacion",
							button        : "fecha_culminacion_vacacion_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    <td align="right" class="viewPropTitle">Tiempo Disfrute:</td>
    <td><input type="text" name="tiempo_disfrute" id="tiempo_disfrute" size="15" readonly="readonly"/></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Fecha Inicio Disfrute:</td>
    <td><input type="text" name="fecha_inicio_disfrute" id="fecha_inicio_disfrute" onchange="validarFechasDisfrute(document.getElementById('fecha_inicio_disfrute').value, document.getElementById('fecha_reincorporacion').value)" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" alt="" name="fecha_inicio_disfrute_cal" width="16" height="16" id="fecha_inicio_disfrute_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
    <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_inicio_disfrute",
							button        : "fecha_inicio_disfrute_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    <td align="right" class="viewPropTitle">Fecha Reincorporaci&oacute;n:</td>
    <td><input type="text" name="fecha_reincorporacion" id="fecha_reincorporacion" onchange="validarFechasDisfrute(document.getElementById('fecha_inicio_disfrute').value, document.getElementById('fecha_reincorporacion').value)" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" alt="" name="fecha_inicio_reincorporacion_cal" width="16" height="16" id="fecha_inicio_reincorporacion_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
    <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_reincorporacion",
							button        : "fecha_inicio_reincorporacion_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    <td align="right" class="viewPropTitle">Fecha Reinc. Ajustada:</td>
    <td><input type="text" name="fecha_reincorporacion_ajustada" id="fecha_reincorporacion_ajustada" onchange="reinicioAjustado(this.value, document.getElementById('fecha_reincorporacion').value)" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" alt="" name="fecha_reincorporacion_ajustada_cal" width="16" height="16" id="fecha_reincorporacion_ajustada_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
      <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_reincorporacion_ajustada",
							button        : "fecha_reincorporacion_ajustada_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Tiempo Pendiente Disfrute:</td>
    <td><input type="text" size="25" name="tiempo_pendiente_disfrute" id="tiempo_pendiente_disfrute" readonly="readonly"/></td>
    <td align="right" class="viewPropTitle">Cantidad de Dias Feriados:</td>
    <td><input type="text" size="25" id="cantidad_feriados" name="cantidad_feriados" onkeyup="restar_feriados(document.getElementById('cantidad_feriados').value)"/></td>
    <td><input type="hidden" name="oculto_dias" id="oculto_dias" /></td>
    <td><input type="hidden" name="oculto_disfrutados" id="oculto_disfrutados" /></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Dias Bonificaci&oacute;n:</td>
    <td><input type="text" size="25" id="dias_bonificacion" name="dias_bonificacion"/></td>
    <td align="right" class="viewPropTitle">Monto Bono Vacacional:</td>
    <td><input type="text" size="25" name="monto_bono_vacacional" id="monto_bono_vacacional"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">N&uacute;mero Orden de Pago:</td>
    <td><input type="text" size="25" name="numero_orden_pago" id="numero_orden_pago"/></td>
    <td align="right" class="viewPropTitle">Fecha Orden de Pago:</td>
    <td><input type="text" name="fecha_orden_pago" id="fecha_orden_pago" size="15" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" alt="" name="fecha_orden_pago_cal" width="16" height="16" id="fecha_orden_pago_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
    <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_orden_pago",
							button        : "fecha_orden_pago_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Elaborado Por:</td>
    <td><input type="text" name="elaborado_por" id="elaborado_por" size="25"/></td>
    <td align="right" class="viewPropTitle">C.I.:</td>
    <td><input type="text" id="ci_elaborado" name="ci_elaborado" size="25" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Aprobado Por:</td>
    <td><input type="text" name="aprobado_por" id="aprobado_por" size="25"/></td>
    <td align="right" class="viewPropTitle">C.I.:</td>
    <td><input type="text" id="ci_aprobado" name="ci_aprobado" size="25" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center"><?=$btn?></td>
  </tr>
</table>
<br />
<div id="grilla">&nbsp;</div>
<div id="datos">&nbsp;</div>