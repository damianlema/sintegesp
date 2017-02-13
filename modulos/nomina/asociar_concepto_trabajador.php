<script src="modulos/nomina/js/asociar_concepto_trabajador_ajax.js"></script>


	<br>
	<h4 align=center>Asociar Conceptos al Trabajador</h4>
	<h2 class="sqlmVersion"></h2>
	<br>



<table width="60%" border="0" align="center">
  <tr>
    <td align="right" class="viewPropTitle">Tipo de Nomina: </td>
    <td>
      <select name="tipo_nomina" id="tipo_nomina">
      <option value="0">.:: Seleccione ::.</option>
	  <?
      $sql_tipo_nomina = mysql_query("select * from tipo_nomina");
	  while($bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina)){
		?>
		<option onclick="consultarAsociados()" value='<?=$bus_tipo_nomina["idtipo_nomina"]?>'><?=$bus_tipo_nomina["titulo_nomina"]?></option>
		<?  
	  }
	  ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Tipo de Asociacion</td>
    <td><label>
      <select name="tipo_asociacion" id="tipo_asociacion">
        <option value="individual" onclick="document.getElementById('imagen_buscar_trabajador').style.display='block', document.getElementById('nombre_trabajador').disabled=false">Individual</option>
        <option value="global" onclick="document.getElementById('imagen_buscar_trabajador').style.display='none', document.getElementById('nombre_trabajador').disabled=true">Global</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td width="22%" class="viewPropTitle"><p align="right">Trabajador</td>
    <td width="78%">
    
    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td>
    <input name="id_trabajador" type="hidden" id="id_trabajador"/>
    <input name="nombre_trabajador" type="text" id="nombre_trabajador" size="60" readonly="readonly"/>
    </td>
    <td valign="top">
    <img src="imagenes/search0.png" width="16" height="16" id="imagen_buscar_trabajador" style="display:block" onclick="window.open('lib/listas/lista_trabajador.php?frm=conceptos_trabajador&tipo_nomina='+document.getElementById('tipo_nomina').value+'','','resizable=no, scrollbars=yes, width =900, height = 600')"/>
    </td>
    </tr>
    </table>
    	
    
      
    </td>
  </tr>
  <tr>
    <td class="viewPropTitle"><p align="right">Concepto o Constante</td>
    <td>
    <input name="id_concepto_constante" type="hidden" id="id_concepto_constante"/>
    <input name="tabla" type="hidden" id="tabla"/>
    <label>
      <input name="concepto_constante" type="text" id="concepto_constante" size="60" />
    <img src="imagenes/search0.png" width="16" height="16" style="cursor:pointer" onclick="window.open('lib/listas/listar_conceptos_constantes.php','','resizable=no, scrollbars=yes, width =900, height = 600')"/></label></td>
  </tr>
  
  <tr>
    <td class="viewPropTitle" align="right">Ejecutar Desde:</td>
    <td>
    <input type="text" id="fecha_ejecutar_desde" name="fecha_ejecutar_desde" size="12">
    <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''"/>
    <script>
		Calendar.setup({
			inputField    : 'fecha_ejecutar_desde',
			button        : 'f_trigger_c',
			align         : 'Tr',
			ifFormat      : '%Y-%m-%d'
		});
	</script>
    </td>
  </tr>
    <tr>
        <td class="viewPropTitle" align="right">Ejecutar Hasta:</td>
        <td>
            <input type="text" id="fecha_ejecutar_hasta" name="fecha_ejecutar_hasta" size="12">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_cr" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''"/>
            <script>
                Calendar.setup({
                    inputField    : 'fecha_ejecutar_hasta',
                    button        : 'f_trigger_cr',
                    align         : 'Tr',
                    ifFormat      : '%Y-%m-%d'
                });
            </script>
        </td>
    </tr>
  
  <tr>
    <td class="viewPropTitle" id="texto_valor_fijo" align="right"></td>
    <td><label>
      <input name="valor_fijo" type="text" id="valor_fijo" size="15" style="display:none" value=""/>
    </label></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center">
      <input type="submit" name="boton_asociar" id="boton_asociar" value="Asociar" class="button" onclick="sosciarConceptoConstante()"/>
      <input type="submit" name="boton_proba" id="boton_proba" value="Probar" class="button" onclick="probarFormula()" style="display:none"/>
    </td>
  </tr>
</table>





<div id="lista_conceptos_constantes">


</div>
