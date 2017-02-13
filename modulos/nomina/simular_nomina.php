<script src="modulos/nomina/js/simular_nomina_ajax.js"></script>
	<br>
	<h4 align=center>Simular Nomina</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<br>

<div align="center">
	
    <img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/listar_simular_nomina.php','','width=900, height=600, scrollbars=yes, resizable=yes')">
    &nbsp;
    <img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href = 'principal.php?accion=959&modulo=13'">
    &nbsp;
    <img src="imagenes/imprimir.png" style="cursor:pointer; visibility:hidden;" id="btImprimir" title="Imprimir" onclick="document.getElementById('divOpciones').style.display='block';">
    
    <div id="divOpciones" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:25%; left:40%">
        <div align="right">
            <a href="#" onClick="document.getElementById('divOpciones').style.display='none';">X</a>
        </div>
        <table>
        	<tr><td align="center"><span style="font-weight:bold;">Opciones de Reporte </span></td></tr>
        	<tr><td><input type="radio" name="opciones" id="nomina" checked="checked" /> Relaci&oacute;n de N&oacute;mina</td></tr>
        	<tr><td><input type="radio" name="opciones" id="concepto" /> Resumen de Conceptos</td></tr>
        	<tr><td><input type="radio" name="opciones" id="comparacion" /> Comparaci&oacute;n de N&oacute;mina</td></tr>
        	<tr><td align="center"><input type="button" value="Procesar" onclick="mostrarReporte();" /></td></tr>
        </table>
    </div>
    
    
    <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
        <div align="right">
            <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
        </div>
        <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
    </div>
</div>
<br />
<br />

<script>
	function procesarNomina(){
		if(confirm("Realmente desea Procesar esta Nomina?")){
			generarNomina('Procesado');
			
			
		}
	}
	</script>
    
     
    

<input type="hidden" id="idgenerar_nomina" name="idgenerar_nomina">
<table width="80%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Nomina</td>
    <td><?
		$sql_consultar_tipo_nomina = mysql_query("select * from tipo_nomina");
		?>
      <select name="idtipo_nomina" id="idtipo_nomina">
        <option value="0">.:: Seleccione ::.</option>
        <?
			while($bus_consultar_tipo_nomina = mysql_fetch_array($sql_consultar_tipo_nomina)){
				?>
        <option value="<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>" onclick="cargarConstantes('<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>'), consultarNominas('<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>'), seleccionarPeriodo('<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>')">
        <?=$bus_consultar_tipo_nomina["titulo_nomina"]?>
        </option>
        <?	
			}
			?>
      </select></td>
    <td align="right">&nbsp;</td>
    <td style="font-weight:bold" id="celda_estado2">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Periodo a Simular</td>
    <td id="celda_periodos"></td>
    <td align="right">&nbsp;</td>
    <td style="font-weight:bold" id="celda_estado3">&nbsp;</td>
  </tr>
  <tr>
    <td width="26%" align="right" class='viewPropTitle'>Nomina a Comparar</td>
    <td width="59%" id="listaComparar"></td>
    <td width="1%" align="right">&nbsp;</td>
    <td width="14%" style="font-weight:bold" id="celda_estado"><label></label></td>
  </tr>
  <tr>
    <td colspan="4" id="celda_constantes">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center">
    
    
    <table border="0">
      <tr>
        <td><input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onClick="ingresarDatosBasicos()" style="display:block"></td>
        <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onclick="modificarNomina()"></td>
        <td><input type="submit" name="boton_prenomina" id="boton_prenomina" value="Simular" class="button" style="display:none" onclick="generarNomina('Pre Nomina')"></td>
        <td><input type="submit" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onclick="anularNomina()"></td>
        <td><input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onClick="procesarNomina()"></td>
      </tr>
    </table>    </td>
  </tr>
</table>

<input type="hidden" value="1" id="pestana_seleccionada" name="pestana_seleccionada">
<input type="hidden" id="idorden_compra_servicio" name="idorden_compra_servicio">
<input type="hidden" name="error_conceptos" id="error_conceptos">


<table width="70%" align="center" cellpadding="0" cellspacing="0">
<tr>
<td align="left">

<table width="30%" border="0" id="tabla_pestanas" cellpadding="3">
      <tr>
        <td align="center" bgcolor="#EAEAEA" id="tdtrabajador" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>TRABAJADORES</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdconceptos" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>CONCEPTOS</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdpartidas"onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>PARTIDAS</strong></td>
      </tr>
</table>

</td>
</tr>
<tr>
<td>


<table width="100%" style="border:#000 solid 1px;"align="center" id="tabla_listas">
    <tr>
        <td>
            <div id="listaTrabajadores" style="text-align:center"> Sin Trabajadores Cargados</div>
            <div id="listaConceptos" style="display:none;text-align:center"> Sin Conceptos Cargados </div>
            <div id="listaPartidas" style="display:none; text-align:center"> Sin partidas Cargadas</div>
        </td>
    </tr>
</table>


</td>
</tr>
</table>


