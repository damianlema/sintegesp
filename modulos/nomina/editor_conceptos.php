<?
$sql_actualizar = mysql_query("delete from temporal_conceptos where idsession = '".session_id()."'");
?>
<script src="modulos/nomina/js/editor_conceptos_ajax.js"></script>


	<br>
	<h4 align=center>Editor de Conceptos</h4>
	<br>

	<h2 class="sqlmVersion"></h2>
	<br>
    <br>



<input type="hidden" id="iditem_eliminar" name="iditem_eliminar">
<input type="hidden" id="idconcepto" name="idconcepto">


<table width="80%" border="0" align="center">
  <tr>
    <td colspan="2" align="center">
    <img src="imagenes/search0.png" onclick="window.open('lib/listas/listar_conceptos.php','Lista de Conceptos','scrollbars=yes,width=900,height=600')" style="cursor:pointer">
    &nbsp;
    <img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=942&modulo=13'">    </td>
  </tr>
  <tr>
    <td width="15%" align="right" class='viewPropTitle'>Codigo</td>
    <td>
      <input type="text" name="codigo" id="codigo">      </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Denominaci&oacute;n</td>
    <td><label>
      <input name="denominacion" type="text" id="denominacion" size="100">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Afectaci&oacute;n</td>
    <td><?
    $sql_consulta_tipo_concepto = mysql_query("select * from tipo_conceptos_nomina order by codigo");
	?>
      <select name="tipo_concepto" id="tipo_concepto">
        <?
      while($bus_consulta_tipo_concepto = mysql_fetch_array($sql_consulta_tipo_concepto)){
			?>
        <option <? if($bus_consulta_tipo_concepto["afecta"] == "deduccion"){ ?> onclick="document.getElementById('buscarClasificador').style.display='none', document.getElementById('buscarOrdinal').style.display='none'"<? }else{ ?>onclick="document.getElementById('buscarClasificador').style.display='block', document.getElementById('buscarOrdinal').style.display='block'"<? } ?> value="<?=$bus_consulta_tipo_concepto["idconceptos_nomina"]?>">(
          <?=$bus_consulta_tipo_concepto["codigo"]?>
          )&nbsp;
          <?=$bus_consulta_tipo_concepto["descripcion"]?>
        </option>
        <?  
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Aplica Prestaciones</td>
    <td><input type="checkbox" name="aplica_prestaciones" id="aplica_prestaciones" ></td>
  </tr>

  <tr>
    <td align="right" class='viewPropTitle'>Afecta Columna Prestaciones:</td>
    <td>
        <select name="columna_prestaciones" id="columna_prestaciones">
            <option value="sueldo">Sueldo</option>
            <option value="oc">Otros Complementos</option>
            <option value="bv">Bono Vacacional</option>
            <option value="bfa">Bono Fin de A&ntilde;o</option>
        </select>

    </td>
  </tr>

  <tr>
    <td align="right" class='viewPropTitle'>Posicion</td>
    <td><label>
      <input name="posicion" type="text" id="posicion" size="3" value="1"/>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Clasificador Presupuestario</td>
    <td><?
	  if($bus_busqueda){
     	 $sql_clasificador_presupuestario = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_busqueda["idclasificador_presupuestario"]."");
	 	 $bus_clasificador_presupuestario = mysql_fetch_array($sql_clasificador_presupuestario);
	  }
	  ?>
    
    
    <table>
    <tr>
    <td>
     
        <input type="text" name="clasificador" size="120" disabled id="clasificador" value="">
        <input type="hidden" name="id_clasificador" id="id_clasificador" value="">     </td>
     <td>
      
    <a href="#" onclick="window.open('lib/listas/listar_clasificador_presupuestario.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario" id="buscarClasificador" style="display:block"/></a>    </td>
    </tr>
    </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Ordinal</td>
    <td><?
      
	  
	  
	    if($_SESSION["version"] == "basico" and $bus_busqueda["idordinal"] == ''){
			$sql_ordinal = mysql_query("select * from ordinal where idordinal = '6'")or die(mysql_error());
	 	 	$bus_ordinal = mysql_fetch_array($sql_ordinal);
		}else{
			$sql_ordinal = mysql_query("select * from ordinal where idordinal = '".$bus_busqueda["idordinal"]."'")or die(mysql_error());
	 	 	$bus_ordinal = mysql_fetch_array($sql_ordinal);

		}
	  ?>
      
      <table>
      <tr>
      <td>
      
      <input name="nombre_ordinal" type="text" id="nombre_ordinal" size="120" value="" disabled="disabled" />
      <input name="idordinal" type="hidden" id="idordinal" value="" />    </td>
    <td>
    
    <a href="#" onclick="window.open('lib/listas/lista_ordinal.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario" id="buscarOrdinal" style="display:block"/></a>    </td>
    </tr>
    </table>    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    
    <table border="0">
      <tr>
        <td><input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none"/></td>
        <td><input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" style="display:block" onclick="ingresarConcepto()"/></td>
        <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onclick="modificarConcepto()"/></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    
    
    <div id="mensajeError" style="color: #F00; font-weight:bold"></div>
    <br />
    
    <table width="100%" border="0" style="display:block" id="tabla_calculadora">
      <tr>
        <td width="20%" rowspan="3"><table width="100%" border="0">
          <tr>
            <td align="center"><input type="submit" name="sumar" id="sumar" value="+" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_+')" /></td>
            <td align="center"><input type="submit" name="restar" id="restar" value="-" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_-')" /></td>
            <td align="center"><input type="submit" name="multiplicar" id="multiplicar" value="*" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_*')" /></td>
            <td align="center"><input type="submit" name="dividir" id="dividir" value="/" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_/')" /></td>
            <td align="left"><input type="submit" name="insertar_derecha" id="insertar_derecha" value="Ins &gt;" style="cursor:pointer" onclick="insertarDespues()"/></td>
          </tr>
          <tr>
            <td align="center"><label>
              <input type="submit" name="siete" id="siete" value="7" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_7')" />
            </label></td>
            <td align="center"><label>
              <input type="submit" name="ocho" id="ocho" value="8" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_8')" />
            </label></td>
            <td align="center"><label>
              <input type="submit" name="nueve" id="nueve" value="9" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_9')" />
            </label></td>
            <td align="center"><input type="submit" name="corchote_abierto" id="corchote_abierto" value="(" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_(')" /></td>
            <td align="left"><input type="submit" name="insertar_izquierda" id="insertar_izquierda" value="Ins &lt;" style="cursor:pointer" onclick="insertarAntes()"/></td>
          </tr>
          <tr>
            <td align="center"><input type="submit" name="cuatro" id="cuatro" value="4" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_4')" /></td>
            <td align="center"><label>
              <input type="submit" name="cinco" id="cinco" value="5" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_5')" />
            </label></td>
            <td align="center"><label>
              <input type="submit" name="seis" id="seis" value="6" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_6')" />
            </label></td>
            <td align="center"><input type="submit" name="corchete_cerrado" id="corchete_cerrado" value=")" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_)')" /></td>
            <td align="left"><label>
              <input type="submit" name="borrar" id="borrar" value="Borrar" style="cursor:pointer" onclick="eliminarItem()"/>
            </label></td>
          </tr>
          <tr>
            <td align="center"><input type="submit" name="uno" id="uno" value="1" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_1')" /></td>
            <td align="center"><input type="submit" name="dos" id="dos" value="2" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_2')" /></td>
            <td align="center"><input type="submit" name="tres" id="tres" value="3" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_3')" /></td>
            <td align="center"><input type="submit" name="igual" id="igual" value="==" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_==')" /></td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td align="center"><input type="submit" name="cero" id="cero" value="0" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_0')" /></td>
            <td align="center"><input type="submit" name="punto" id="punto" value="." style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_.')" /></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><input type="submit" name="menor" id="menor" value="&lt;" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_&lt;')" /></td>
            <td align="center"><input type="submit" name="mayor" id="mayor" value="&gt;" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_&gt;')" /></td>
            <td align="center"><input type="submit" name="mayor_igual" id="mayor_igual" value="&gt;=" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_&gt;=')" /></td>
            <td align="center"><input type="submit" name="menor_igual" id="menor_igual" value="&lt;=" style="width:25px; cursor:pointer" onclick="ingresarValores(this.value, 'SI_&lt;=')" /></td>
            <td align="center"><input type="submit" name="borrar_todo" id="borrar_todo" value="Limpiar" style="cursor:pointer" onclick="limpiarDatos()"/></td>
          </tr>
        </table></td>
        <td width="4%" rowspan="3">
        
        
        
        
        <table width="100%" border="0" height="132">
          <tr>
            <td height="63" align="center" valign="top"><input type="radio" name="radio" id="formula_primaria" value="formula_primaria" style="cursor:pointer" title="Escribir en Editorde Fomula" checked="checked" onclick="cambiarSeleccionEditor('visor_formula')"/></td>
          </tr>
          <tr>
            <td height="27" align="center"><input type="radio" name="radio" id="condicion" value="condicion" style="cursor:pointer" title="Escribir Condicion" onclick="cambiarSeleccionEditor('tabla_condicion')"/></td>
          </tr>
          <tr>
            <td align="center"><input type="radio" name="radio" id="entonces" value="entonces" style="cursor:pointer" title="Escribir el Entonces de la Condicion" onclick="cambiarSeleccionEditor('tabla_entonces')"/></td>
          </tr>
        </table>        </td>
        <td style="border:#000 1px solid;" height="60px">
        <div style="width:800px; overflow-x:auto" id="visor_formula">
&nbsp;        </div>        </td>
      </tr>
      <tr>
        <td height="1"><strong>CONDICIONANTES</strong></td>
      </tr>
      <tr>
        <td valign="top" style="border:#000 1px solid">
        
        
        <table width="100%" border="0" height="95">
          <tr>
            <td width="15%" height="31" valign="top"><strong>CONDICION:</strong></td>
            <td width="85%" id="tabla_condicion" bgcolor="#CCCCCC" style="border:#000 1px solid">&nbsp;</td>
          </tr>
          <tr>
            <td height="58" valign="top"><strong>ENTONCES:</strong></td>
            <td id="tabla_entonces" bgcolor="#CCCCCC" style="border:#000 1px solid">&nbsp;</td>
          </tr>
        </table>        </td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td height="21" colspan="2">
    
    <input type="hidden" value="1" id="pestana_seleccionada" name="pestana_seleccionada">
    
    
    <table width="70%" border="0" id="tabla_pestanas" cellpadding="3">
      <tr>
        <td align="center" bgcolor="#EAEAEA" id="tdconstantes" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>CONSTANTES</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdconceptos" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>CONCEPTOS</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdtablas_constantes"onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>TABLAS CONSTANTES</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdfunciones" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>FUNCIONES</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdhoja_tiempo" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>HOJAS DE TIEMPO</strong></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td colspan="2" style="background-color:#EAEAEA">
 
 
 
 
 
 
 
 
 
 
 
       
         
 
  <!-- DIV FUNCION NUMERO DE -->
 
<div id="divNumeroDe" style="width:500px; background-color:#EAEAEA; display:none; border:#000 1px solid; position:absolute">
<div align="right"><strong onclick="document.getElementById('divNumeroDe').style.display = 'none'" style=" cursor:pointer">X</strong></div>
    <table>
        <tr>
            <td>Numero de:</td>
            <td>
            <?
            $sql_parentezco = mysql_query("select * from parentezco");
			?>
            <select name="select_numero_de" id="select_numero_de">
            	<?
                while($bus_parentezco = mysql_fetch_array($sql_parentezco)){
					?>
						<option value="<?=$bus_parentezco["idparentezco"]?>"><?=$bus_parentezco["denominacion"]?></option>
					<?	
				}
				?>
            </select>            </td>
            <td>
            <select name="simbolo_numero_de" id="simbolo_numero_de">
						<option value="0">.:: Seleccione ::.</option>
                        <option value="todos" onclick="document.getElementById('edad_numero_de').value = 0">Todos</option>
                        <option value=">">Mayor(es) de</option>
                        <option value="<">Menor(es) de</option>
                        <option value=">=">Mayor(es) o Igual de</option>
                        <option value="<=">Menor(es) o Igual de</option>
                        <option value="=">Igual(es) a</option>
            </select>            </td>
            <td>
            <select name="edad_numero_de" id="edad_numero_de">
            	<option value="0"></option>
				<?
                for($i=1;$i<100; $i++){
					?>
						<option value="<?=$i?>"><?=$i?> A&ntilde;os</option>
					<?	
				}
				?>
            </select>            </td>
        </tr>
        <tr>
        <td colspan="2"><input type="button" name="boton_ingresar_entre_fechas" id="boton_ingresar_entre_fechas" 
        onclick="ingresarValores('Numero de', 'FU_numerode')" value="Guardar" class="button"><td>
        </tr>
    </table>
</div>
  <!-- DIV FUNCION NUMERO DE -->
 
 
 
 
 
         
         
 
 <!-- DIV FUNCION ENTRE DOS FECHAS -->
 
<div id="divTiempoEntreFechas" style="width:300px; background-color:#EAEAEA; display:none; border:#000 1px solid; position:absolute">
<div align="right"><strong onclick="document.getElementById('divTiempoEntreFechas').style.display = 'none'" style=" cursor:pointer">X</strong></div>
    <table>
        <tr>
            <td>Desde</td>
            <td>
            <input type="text" id="desde_entre_fechas" name="desde_entre_fechas" size="12">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "desde_entre_fechas",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>            </td>
            <td>Hasta</td>
            <td><input type="text" id="hasta_entre_fechas" name="hasta_entre_fechas" size="12">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_e" width="16" height="16" id="f_trigger_e" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "hasta_entre_fechas",
                                button        : "f_trigger_e",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script> </td>
        </tr>
        <tr>
        <td colspan="2"><input type="button" name="boton_ingresar_entre_fechas" id="boton_ingresar_entre_fechas" onclick="ingresarValores('Tiempo entre', 'FU_tiempoentrefechas'), document.getElementById('divTiempoEntreFechas').style.display = 'none'" value="Guardar" class="button"><td>
        </tr>
    </table>
</div>
  <!-- DIV FUNCION ENTRE DOS FECHAS -->
 
 
 
 
 
 
 
 
 
 
   <!-- DIV FUNCION DIAS ENTRE -->
 
<div id="divDiasEntre" style="width:300px; background-color:#EAEAEA; display:none; border:#000 1px solid; position:absolute">
<div align="right"><strong onclick="document.getElementById('divDiasEntre').style.display = 'none'" style=" cursor:pointer">X</strong></div>
    <table>
        <tr>
            <td>Desde</td>
            <td>
            <select id="desde_dias_entre" name="desde_dias_entre">
            <option value="fechaIngreso">Fecha de Ingreso</option>
            </select>            </td>
            <td>Hasta</td>
            <td>
            <select id="hasta_dias_entre" name="hasta_dias_entre">
			<?
            for($i=1;$i<=31;$i++){
                ?>
                <option value="<?=$i?>"><?=$i?></option>
                <?
            }
            ?>
            </select>            </td>
        </tr>
        <tr>
        <td colspan="2"><input type="button" name="boton_ingresar_dias_entre" id="boton_ingresar_dias_entre" onclick="ingresarValores('Fraccion de Dias Entre', 'FU_diasentre'), document.getElementById('divDiasEntre').style.display = 'none'" value="Guardar" class="button"><td>
        </tr>
    </table>
</div>
  <!-- DIV FUNCION DIAS FERIADOS -->
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
    <!-- DIV FUNCION MESES ENTRE -->
 
<div id="divMesesEntre" style="width:300px; background-color:#EAEAEA; display:none; border:#000 1px solid; position:absolute">
<div align="right"><strong onclick="document.getElementById('divMesesEntre').style.display = 'none'" style=" cursor:pointer">X</strong></div>
    <table>
        <tr>
            <td>Desde</td>
            <td>
            <select id="desde_meses_entre" name="desde_meses_entre">
            	<option value="fechaIngreso">Fecha de Ingreso</option>
            </select>            </td>
            <td>Hasta</td>
            <td>
            <select id="hasta_meses_entre" name="hasta_meses_entre">
			<?
            for($i=1;$i<=12;$i++){
                ?>
                <option value="<?=$i?>"><?=$i?></option>
                <?
            }
            ?>
            </select>            </td>
        </tr>
        <tr>
        <td colspan="2"><input type="button" name="boton_ingresar_meses_entre" id="boton_ingresar_meses_entre" onclick="ingresarValores('Meses Entre', 'FU_mesesentre'), document.getElementById('divMesesEntre').style.display = 'none'" value="Guardar" class="button"><td>
        </tr>
    </table>
</div>
  <!-- DIV FUNCION DIAS FERIADOS -->
 
 
 
 
 
 
 
 
 
 
 
 
 
  <!-- DIV FUNCION DIAS FERIADOS -->
 
<div id="divDiasFeriados" style="width:300px; background-color:#EAEAEA; display:none; border:#000 1px solid; position:absolute">
<div align="right"><strong onclick="document.getElementById('divDiasFeriados').style.display = 'none'" style=" cursor:pointer">X</strong></div>
    <table>
        <tr>
            <td>Desde</td>
            <td>
            <input type="text" id="desde_dias_feriados" name="desde_dias_feriados" size="12">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "desde_dias_feriados",
                                button        : "f_trigger_a",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>            </td>
            <td>Hasta</td>
            <td><input type="text" id="hasta_dias_feriados" name="hasta_dias_feriados" size="12">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_q" width="16" height="16" id="f_trigger_q" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "hasta_dias_feriados",
                                button        : "f_trigger_q",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script> </td>
        </tr>
        <tr>
        <td colspan="2"><input type="button" name="boton_ingresar_entre_fechas" id="boton_ingresar_entre_fechas" onclick="ingresarValores('Dias Feriados Entre', 'FU_diasferiadosentre'), document.getElementById('divDiasFeriados').style.display = 'none'" value="Guardar" class="button"><td>
        </tr>
    </table>
</div>
  <!-- DIV FUNCION DIAS FERIADOS -->
 
 
  <!-- DIV FUNCION DIAS ENTRE INGRESO Y HASTA -->
 
<div id="divIngresoHasta" style="width:300px; background-color:#EAEAEA; display:none; border:#000 1px solid; position:absolute">
<div align="right"><strong onclick="document.getElementById('divIngresoHasta').style.display = 'none'" style=" cursor:pointer">X</strong></div>
    <table>
        <tr>
            <td>Desde</td>
            <td>Fecha de ingreso</td>
            <td>Hasta</td>
            <td><input type="text" id="hasta_ingreso_hasta" name="hasta_ingreso_hasta" size="12">
            <img src="imagenes/jscalendar0.gif" name="f_trigger_p" width="16" height="16" id="f_trigger_p" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "hasta_ingreso_hasta",
                                button        : "f_trigger_p",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script> </td>
        </tr>
        <tr>
        <td colspan="2"><input type="button" name="boton_ingresar_ingreso_hasta" id="boton_ingresar_ingreso_hasta" onclick="ingresarValores('A�os desde Ingreso Hasta el ', 'FU_ingresohasta'), document.getElementById('divIngresoHasta').style.display = 'none'" value="Guardar" class="button"><td>
        </tr>
    </table>
</div>
  <!-- DIV FUNCION ENTRE INGRESO Y HASTA -->
 
 
 
 
 
 
 
    
    
    <div id="div_constantes" style="display:block; border:#000 solid 1px">
    	<?
        $sql_constantes = mysql_query("select * from constantes_nomina");
		?>
        <table cellpadding="0" cellspacing="20" border="0">
            <tr>
            <?
			$num_constantes=1;
            while($bus_constantes = mysql_fetch_array($sql_constantes)){
			?>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_constantes<?=$bus_constantes["idconstantes_nomina"]?>" onclick="ingresarValores('<?=$bus_constantes["descripcion"]?>', 'CN_<?=$bus_constantes["idconstantes_nomina"]?>')">
                - (<?=$bus_constantes["codigo"]?>)&nbsp;<?=$bus_constantes["descripcion"]?>               </td>
            <?
				if($num_constantes == 6){
					?>
					</tr>
					<tr>
					<?
					$num_constantes = 1;
				}else{
					$num_constantes++;	
				}
			}
			?>
            </tr>
         </table>    
    </div>
    
    
    
    
    
    
    
    
    <div id="div_conceptos" style="display:none; border:#000 solid 1px">
    
    <?
        $sql_conceptos = mysql_query("select * from conceptos_nomina");
		?>
        <table cellpadding="0" cellspacing="20" border="0">
            <tr>
            <?
			$num_conceptos=1;
            while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
			?>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_conceptos<?=$bus_conceptos["idconceptos_nomina"]?>" onclick="ingresarValores('<?=$bus_conceptos["descripcion"]?>', 'CO_<?=$bus_conceptos["idconceptos_nomina"]?>')">
                - (<?=$bus_conceptos["codigo"]?>)&nbsp;<?=$bus_conceptos["descripcion"]?>               </td>
            <?
				if($num_conceptos == 6){
					?>
					</tr>
					<tr>
					<?
					$num_conceptos = 1;
				}else{
					$num_conceptos++;	
				}
			}
			?>
            </tr>
         </table>    
    </div>
    
    
    
    
    
    
    
    <div id="div_tablas_constantes" style="display:none; border:#000 solid 1px">
    <?
        $sql_tabla_constantes = mysql_query("select * from tabla_constantes");
		?>
        <table cellpadding="0" cellspacing="20" border="0">
            <tr>
            <?
			$num_tabla_constantes=1;
            while($bus_tabla_constantes = mysql_fetch_array($sql_tabla_constantes)){
			?>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos<?=$bus_tabla_constantes["idtabla_constantes"]?>" onclick="ingresarValores('<?=$bus_tabla_constantes["descripcion"]?>', 'TA_<?=$bus_tabla_constantes["idtabla_constantes"]?>')">
                - (<?=$bus_tabla_constantes["codigo"]?>)&nbsp;<?=$bus_tabla_constantes["descripcion"]?>               </td>
            <?
				if($num_tabla_constantes == 6){
					?>
					</tr>
					<tr>
					<?
					$num_tabla_constantes = 1;
				}else{
					$num_tabla_constantes++;	
				}
			}
			?>
            </tr>
         </table>    
         </div>
         
         
         
         
         
         
         <div id="div_hoja_tiempo" style="display:none; border:#000 solid 1px">
    
    <?
        $sql_conceptos = mysql_query("select tht.descripcion, tht.idtipo_hoja_tiempo from 
													tipo_hoja_tiempo tht");
		?>
        <table cellpadding="0" cellspacing="20" border="0">
            <tr>
            <?
			$num_conceptos=1;
            while($bus_conceptos = mysql_fetch_array($sql_conceptos)){
			?>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_hoja_tiempo<?=$bus_conceptos["idtipo_hoja_tiempo"]?>" onclick="ingresarValores('<?=$bus_conceptos["descripcion"]?>', 'THT_<?=$bus_conceptos["idtipo_hoja_tiempo"]?>')">
                - <?=$bus_conceptos["descripcion"]?>               </td>
            <?
				if($num_conceptos == 6){
					?>
					</tr>
					<tr>
					<?
					$num_conceptos = 1;
				}else{
					$num_conceptos++;	
				}
			}
			?>
            </tr>
         </table>    
    </div>
         
         
         
         
         
         
        <div id="div_funciones" style="display:none; border:#000 solid 1px">
    	<table cellpadding="0" cellspacing="20" border="0" width="100%">
            <tr>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_anios_empresa" onclick="ingresarValores('A&ntilde;os en la empresa', 'FU_anioempresa')">
                - A&ntilde;os en la empresa               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos<?=$bus_tabla_constantes["idtabla_constantes"]?>" onclick="document.getElementById('divNumeroDe').style.display='block'">
                - Numero de:               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptostiempo_fechas" onclick="document.getElementById('divTiempoEntreFechas').style.display='block'">
                - Tiempo entre dos Fechas               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_dias_feriados" onclick="document.getElementById('divDiasFeriados').style.display='block'">
                - Dias feriados entre dos fechas               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_numero_mes" onclick="ingresarValores('Numero de Mes Actual', 'FU_mesactual')">
                - Numero de Mes Actual               </td>
               </tr>
               
               
               
               
               
               
               <tr>
               
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_tiempo_completo_dias" onclick="ingresarValores('Tiempo Completo Laborable (Dias)', 'FU_continuidadAdministrativa_dias')">
                - Tiempo Completo Laborable (Dias)              </td>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_tiempo_completo_meses" onclick="ingresarValores('Tiempo Completo Laborable (Meses)', 'FU_continuidadAdministrativa_meses')">
                - Tiempo Completo Laborable (Meses)              </td>
                <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_tiempo_completo_anios" onclick="ingresarValores('Tiempo Completo Laborable (A�os)', 'FU_continuidadAdministrativa_anios')">
                - Tiempo Completo Laborable (A&ntilde;os)              </td>
               
               
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_conceptos_dias_mes" onclick="ingresarValores('Dias del Mes', 'FU_diasmes')">
                - Dias del Mes               </td>
               
               
             </tr>
             
             
             <tr>
             <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_meses_enre" onclick="document.getElementById('divMesesEntre').style.display='block'">
                - Meses Entre               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_dias_entre" onclick="document.getElementById('divDiasEntre').style.display='block'">
                - Fraccion Dias Entre               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_tiempo_bono_vacacional" onclick="ingresarValores('Tiempo Bono Vacacional', 'FU_tiempobono')">
               - Tiempo Bono Vacacional               </td>
               <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_ingreso_hasta" onclick="document.getElementById('divIngresoHasta').style.display='block'">
               - Años entre Ingreso y Fecha Hasta               </td>
                 <td onMouseOver="cambiarColor(this.id, '#FFFFCC')" onMouseOut="cambiarColor(this.id, '#EAEAEA')" style="cursor:pointer; width:200px; font-size:10px" id="td_tabla_dia_fin_periodo" onclick="ingresarValores('Dia fin periodo', 'FU_diafinperiodo')">
                     - Dia Fin Periodo               </td>
             </tr>
         </table>    
    </div>    </td>
  </tr>
</table>




