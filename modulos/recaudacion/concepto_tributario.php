<script src="modulos/recaudacion/js/concepto_tributario_ajax.js"></script>

<body onLoad="listarUnidades()">
<input type="hidden" id="idconcepto_tributario" name="idconcepto_tributario" value="">
    <br>
<h4 align=center>Concepto Tributario</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 <br>
 <table width="75%" border="0" align="center">
   <tr>
     <td width="26%" align="right" class='viewPropTitle'>Codigo</td>
     <td width="26%"><label>
       <input type="text" name="codigo" id="codigo">
     </label></td>
     <td width="20%" align="right">Año</td>
     <td width="28%"><label>
       <select name="anio" id="anio">
         <?
		for($i=date("Y");$i<2100;$i++){
			?>
         <option value="<?=$i?>">
           <?=$i?>
         </option>
         <?
		}
		?>
       </select>
       <!-- <input name="anio" type="text" id="anio" size="5"> -->
     </label></td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Denominacion</td>
     <td colspan="3"><input name="denominacion" type="text" id="denominacion" size="50"></td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Tipo de Concepto</td>
     <td colspan="3"><label>
       <select name="tipo_concepto" id="tipo_concepto">
         <option value="0">.:: Seleccione ::.</option>
         <option value="desde_tabla" onClick="document.getElementById('tabla_tabla_constantes').style.display = 'block', document.getElementById('tabla_valores_propios').style.display = 'none'">Desde Tabla de Constantes</option>
         <option value="valores_propios" onClick="document.getElementById('tabla_tabla_constantes').style.display = 'none', document.getElementById('tabla_valores_propios').style.display = 'block'">Valores Propios</option>
       </select>
     </label></td>
   </tr>

   <tr>
     <td colspan="4">
     
     
     <!-- TABLA DE CONSTANTES -->
         <table id="tabla_tabla_constantes" style="display:none">
           <tr>
             <td align="right" class='viewPropTitle'>Tabla de Constantes</td>
             <td><select name="tabla_constantes" id="tabla_constantes">
                 <option value="0">.:: Seleccione ::.</option>
				 <?
    $sql_tabla_constantes = mysql_query("select * from tabla_constantes_recaudacion");
	while($bus_tabla_constantes = mysql_fetch_array($sql_tabla_constantes)){
		?>
                 <option value="<?=$bus_tabla_constantes["idtabla_constantes"]?>">
                   <?=$bus_tabla_constantes["descripcion"]?>
               </option>
                 <?
	}
	?>
               </select>             </td>
           </tr>
         </table>
       <!-- TABLA DE CONSTANTES -->
       
       
         <!-- TABLA DE VALORES PROPIOS -->
         <table width="100%" id="tabla_valores_propios" style="display:block">
           <tr>
             <td colspan="4" align="center"class='viewPropTitle'><strong>Formula </strong></td>
           </tr>
           <tr>
             <td align="right" class='viewPropTitle'>Alforo Anual (Alicuota)</td>
             <td>
             <table align="left">
             <tr>
             <td>
             
               <select name="tipo_base_aforo" id="tipo_base_aforo">
                 <option value="0" onClick="document.getElementById('div_monto_variable').style.display ='none'">.:: Seleccione ::.</option>
                 <option value="unidad_tributaria" onClick="document.getElementById('div_monto_variable').style.display ='none'">Unidad Tributaria</option>
                 <option value="monto_variable" onClick="document.getElementById('div_monto_variable').style.display ='block'">Monto Variable</option>
                 <option value="capital_social" onClick="document.getElementById('div_monto_variable').style.display ='none'">Capital Social</option>
               </select>
               </td>
               <td>
               <div id="div_monto_variable" style="display:none">Monto:&nbsp;<input type="text" name="monto_variable" id="monto_variable" style="text-align:right"></div>
               </td>
               </tr>
               </table>
             
             </td>
             <td colspan="2" align="left"><label>
               <select name="tipo_calculo_aforo" id="tipo_calculo_aforo">
                 <option value="0">.:: Seleccione ::.</option>
                 <option value="fijo" onClick="document.getElementById('tabla_porcentual_aforo').style.display = 'none', document.getElementById('tabla_fijo_aforo').style.display = 'block'">Fijo</option>
                 <option value="porcentual" onClick="document.getElementById('tabla_porcentual_aforo').style.display = 'block', document.getElementById('tabla_fijo_aforo').style.display = 'none'">Porcentual</option>
               </select>
             </label></td>
           </tr>
           <tr>
             <td colspan="4" align="center">
             
             
             
             
             <table id="tabla_porcentual_aforo" style="display:none">
                 <tr>
                   <td class='viewPropTitle'>Valor</td>
                   <td><input type="text" name="porcentual_valor_aforo" id="porcentual_valor_aforo"></td>
                   <td class='viewPropTitle'>Divisor</td>
                   <td><input type="text" name="porcentual_divisor_aforo" id="porcentual_divisor_aforo"></td>
                 </tr>
               </table>
                 <table id="tabla_fijo_aforo" style="display:none">
                   <tr>
                     <td class='viewPropTitle'>Valor</td>
                     <td><input type="text" name="fijo_valor_aforo" id="fijo_valor_aforo"></td>
                   </tr>
               </table></td>
           </tr>
           <tr>
             <td align="right" class='viewPropTitle'>Minimo Tributable</td>
             <td><select name="tipo_base_minimo" id="tipo_base_minimo">
                 <option value="0">.:: Seleccione ::.</option>
                 <option value="unidad_tributaria">Unidad Tributaria</option>
                 <option value="monto_variable">Monto Variable</option>
                 <option value="capital_social">Capital Social</option>
             </select></td>
             <td colspan="2" align="left"><select name="tipo_calculo_minimo" id="tipo_calculo_minimo">
                 <option value="0">.:: Seleccione ::.</option>
                 <option value="fijo" onClick="document.getElementById('tabla_porcentual_minimo').style.display = 'none', document.getElementById('tabla_fijo_minimo').style.display = 'block'">Fijo</option>
                 <option value="porcentual" onClick="document.getElementById('tabla_porcentual_minimo').style.display = 'block', document.getElementById('tabla_fijo_minimo').style.display = 'none'">Porcentual</option>
             </select></td>
           </tr>
           <tr>
             <td colspan="4" align="center"><table id="tabla_porcentual_minimo" style="display:none">
                 <tr>
                   <td class='viewPropTitle'>Valor</td>
                   <td><input type="text" name="minimo_porcentual_valor" id="minimo_porcentual_valor"></td>
                   <td class='viewPropTitle'>Divisor</td>
                   <td><input type="text" name="minimo_porcentual_divisor" id="minimo_porcentual_divisor"></td>
                 </tr>
               </table>
                 <table id="tabla_fijo_minimo" style="display:none">
                   <tr>
                     <td class='viewPropTitle'>Valor</td>
                     <td><input type="text" name="minimo_fijo_valor" id="minimo_fijo_valor"></td>
                   </tr>
               </table></td>
           </tr>
           <tr>
             <td align="right" class='viewPropTitle'>Partida Presupuestaria</td>
             <td colspan="3"><label>
               <input type="text" name="clasificador" size="80" disabled id="clasificador" value="<? echo "(".$bus_clasificador_presupuestario["codigo_cuenta"].")";?> <?=$bus_clasificador_presupuestario["denominacion"]?>">
               <input type="hidden" name="id_clasificador" id="id_clasificador" value="<?=$bus_clasificador_presupuestario["idclasificador_presupuestario"]?>">
               </label>
               <a href="#" onClick="window.open('lib/listas/listar_clasificador_presupuestario.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario"> </a></td>
           </tr>
         </table>
       <!-- TABLA DE VALORES PROPIOS-->     </td>
   </tr>
   <tr>
     <td colspan="4">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="4"><table border="0" align="center">
         <tr>
           <td><label>
             <input type="submit" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarConcepto()">
           </label></td>
           <td><label>
             <input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarConcepto()">
           </label></td>
           <td><label>
             <input type="submit" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarConcepto()">
           </label></td>
         </tr>
     </table></td>
   </tr>
   <tr>
     <td colspan="4">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="4" align="center"><center>
         <input type="hidden" name="idmoras_conceptos_tributarios" id="idmoras_conceptos_tributarios">
         <table align="center" width="100%" id="tabla_mora" style="display:block">
           <tr>
             <td align="right" class='viewPropTitle'>Tipo</td>
             <td><select name="tipo_mora" id="tipo_mora">
                 <option value="fijo">Valor fijo</option>
                 <option value="porcentual">Porcentual</option>
               </select>             </td>
             <td align="right" class='viewPropTitle'>Valor a Calcular:</td>
             <td>
                 <table>
                 <tr>
                     <td>
                     <input type="text" name="valor_calculo" id="valor_calculo">
                     </td>
                     <td>
                     <select name="tipo_valor_mora" id="tipo_valor_mora">
                         <option value="bolivares">Bolivares</option>
                         <option value="unidad_tributaria">Unidad Tributaria</option>
                     </select>
                     </td>
                 </tr>
                 </table>
             </td>
           </tr>
           <tr>
             <td align="right" class='viewPropTitle'>Denominacion</td>
             <td colspan="3"><label>
               <input name="denominacion_mora" type="text" id="denominacion_mora" size="70">
             </label></td>
           </tr>
           <tr>
             <td align="right" class='viewPropTitle'>Sobre</td>
             <td><label>
               <select name="sobre_mora" id="sobre_mora">
                 <option value="total">Total</option>
                 <option value="fraccion_pago">Fraccion de Pago Vencida</option>
               </select>
             </label></td>
             <td align="right" class='viewPropTitle'>Frecuencia del Calculo</td>
             <td><label>
               <select name="frecuencia_calculo_mora" id="frecuencia_calculo_mora">
                 <option value="unica">Unica</option>
                 <option value="diaria">Diaria</option>
                 <option value="quincenal">Quincenal</option>
                 <option value="semanal">Semanal</option>
                 <option value="mensual">Mensual</option>
                 <option value="trimestral">Trimestral</option>
                 <option value="semestral">Semestral</option>
                 <option value="anual">Anual</option>
               </select>
             </label></td>
           </tr>
           <tr>
             <td align="right" class='viewPropTitle'>Condicion</td>
             <td></td>
             <td></td>
             <td></td>
           </tr>
           <tr>
             <td><label>
               <select name="condicion_tipo_mora" id="condicion_tipo_mora">
                 <option value="diaria">Diario</option>
                 <option value="quincenal">Quincenal</option>
                 <option value="mensual">Mensual</option>
                 <option value="trimestral">Trimestral</option>
                 <option value="semestral">Semestral</option>
                 <option value="anual">Anual</option>
               </select>
             </label></td>
             <td><label>
               <select name="condicion_operador_mora" id="condicion_operador_mora">
                 <option value=">">Mayor</option>
                 <option value="<">Menor</option>
                 <option value="==">Igual</option>
                 <option value=">=">Mayor Igual</option>
                 <option value="=<">Menor Igual</option>
               </select>
             </label></td>
             <td><label>
               <select name="condicion_factor_mora" id="condicion_factor_mora">
                 <option value="fecha_vencimiento">Fecha de Vencimiento</option>
                 <option value="fecha_calculo">Fecha de Calculo</option>
               </select>
             </label></td>
             <td><label> Valor:
               <input name="condicion_valor_mora" type="text" id="condicion_valor_mora" size="10">
             </label></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td></td>
             <td></td>
             <td></td>
           </tr>
           <tr>
             <td colspan="4" align="center"><table border="0">
                 <tr>
                   <td><label>
                     <input type="submit" name="boton_ingresar_mora" id="boton_ingresar_mora" value="Ingresar Mora" class="button" onClick="ingresarMora()">
                   </label></td>
                   <td><label>
                     <input type="submit" name="boton_modificar_mora" id="boton_modificar_mora" value="Modificar Mora" class="button" style="display:none" onClick="modificarMora()">
                   </label></td>
                   <td><label>
                     <input type="submit" name="boton_eliminar_mora" id="boton_eliminar_mora" value="Eliminar Mora" class="button" style="display:none" onClick="eliminarMora()">
                   </label></td>
                 </tr>
             </table></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td></td>
             <td></td>
             <td></td>
           </tr>
           <tr>
             <td colspan="4" id="celda_lista_moras">&nbsp;</td>
           </tr>
         </table>
     </center></td>
   </tr>
 </table>
<br>
<div id="listaUnidades"></div>
<br>
<br>
</body>