<script src="modulos/recaudacion/js/solicitud_calculo_ajax.js"></script>
<br>
<h4 align=center>Solicitud de C&aacute;lculo</h4>
	<h2 class="sqlmVersion"></h2>
 <br>

 <div align="center">
 <img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/listar_solicitud_calculo.php','','width=900, height=600, scrollbars=yes')">&nbsp;
 <img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'">
 &nbsp;
<img id="btImprimir" src="imagenes/imprimir.png" style="cursor:pointer; visibility:hidden;" onClick="document.getElementById('divPDF').style.display='block'; iPDF.location.href='lib/reportes/recaudacion/reportes.php?nombre=solicitud_de_calculo&idcontribuyente='+document.getElementById('idcontribuyente').value+'&idsolicitud_calculo='+document.getElementById('idsolicitud_calculo').value;">
</div>

<div id="divPDF" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr>
    	<td>
            <div align="right"><a href="#" onClick="document.getElementById('divPDF').style.display='none'; document.getElementById('iPDF').src='';">X</a></div>
            <iframe name="iPDF" id="iPDF" height="600px" width="710px"></iframe>
        </td>
    </tr>
</table>
</div>

 </div>
 <br />
 <input id="idcontribuyente" name="idcontribuyente" type="hidden">
 <input id="idsolicitud_calculo" name="idsolicitud_calculo" type="hidden">
 <table width="90%" align="center">
   <tr>
     <td align="right" class='viewPropTitle'>N&uacute;mero de la Solicitud:</td>
     <td id="numero_orden" style="font-weight:bold">Aun no generado</td>
     <td align="right" class='viewPropTitle'>Estado</td>
     <td id="estado_orden" style="font-weight:bold">Elaboraci&oacute;n</td>
   </tr>
   
   <tr>
     <td width="24%" align="right" class='viewPropTitle'>
     <table>
     <tr>
     <td>
     Contribuyente&nbsp;     </td>
     <td><img src="imagenes/search0.png" onclick="window.open('lib/listas/listar_contribuyentes.php?url=solicitud_calculo','','width=900, height = 600, scrollbars=yes')" style="cursor:pointer" id="imagen_buscar_contribuyente"></td>
     </tr>
     </table>     </td>
     
     <td colspan="3" id="datos_contribuyente">&nbsp;</td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Tipo de Solicitud</td>
     <td colspan="3"><select name="tipo_solicitud" id="tipo_solicitud">
       <option value="0" onclick="mostrarConceptos(this.value)">.:: Seleccione ::.</option>
	   <?
       $sql_tipo_solicitud = mysql_query("select * from tipo_solicitud");
	   while($bus_tipo_solicitud = mysql_fetch_array($sql_tipo_solicitud)){
	   	?>
       <option value="<?=$bus_tipo_solicitud["idtipo_solicitud"]?>">
         <?=$bus_tipo_solicitud["descripcion"]?>
        </option>
       <?
	   }
	   ?>
     </select></td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Fecha de Solciitud</td>
     <td colspan="3">
       <input name="fecha_solicitud" type="text" id="fecha_solicitud" size="12" value="<?=date('Y-m-d')?>" readonly="readonly"/>
      <img src="imagenes/jscalendar0.gif" name="f_trigger_d" width="16" height="16" id="f_trigger_d" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "fecha_solicitud",
                                button        : "f_trigger_d",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>     </td>
   </tr>
   <tr>
     <td colspan="4"><label></label>       <label></label>       <label></label>       <table width="100%" border="0">
       <tr>
         <td align="right" class='viewPropTitle'>Año</td>
         <td>
         <select name="anio" id="anio">
         	<?
            for($i=date("Y");$i<2100;$i++){
				?>
				<option value="<?=$i?>"><?=$i?></option>
				<?
			}
			?>
         </select>         </td>
         <td align="right" class='viewPropTitle'>Desde</td>
         <td>
         <input name="desde" type="text" id="desde" size="12" readonly="readonly">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "desde",
                                button        : "f_trigger_c",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>         </td>
         <td align="right" class='viewPropTitle'>Hasta</td>
         <td>
         
         <input name="hasta" type="text" id="hasta" size="12" readonly="readonly">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_h" width="16" height="16" id="f_trigger_h" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "hasta",
                                button        : "f_trigger_h",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>         </td>
         <td align="right" class='viewPropTitle'>Vence</td>
         <td>
         <input name="vence" type="text" id="vence" size="12" readonly="readonly">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_v" width="16" height="16" id="f_trigger_v" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
                                Calendar.setup({
                                inputField    : "vence",
                                button        : "f_trigger_v",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                                });
                            </script>         </td>
       </tr>
     </table>       <label></label></td>
   </tr>
   <tr>
     <td align="right" class='viewPropTitle'>Descripcion de la Solicitud</td>
     <td colspan="3"><label>
       <textarea name="descripcion" cols="90" rows="3" id="descripcion"></textarea>
     </label></td>
   </tr>
   <tr>
     <td colspan="4" align="center"><table border="0">
       <tr>
         <td><label>
           <input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onclick="ingresarDatosBasicos()"/>
         </label></td>
         <td><label>
           <input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" style="display:none" class="button" onclick="procesarSolicitud()"/>
         </label></td>
         <td><label>
           <input type="submit" name="boton_anular" id="boton_anular" value="Anular" style="display:none" class="button" onclick="anularSolicitud()"/>
         </label></td>
       </tr>
     </table></td>
   </tr>
   <tr>
     <td colspan="4" bgcolor="#EAEAEA"><strong>Conceptos</strong></td>
   </tr>
   <tr>
     <td colspan="4" id="celda_conceptos_tributarios">&nbsp;</td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td width="27%">&nbsp;</td>
     <td width="21%">&nbsp;</td>
     <td width="28%">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="4" id="celda_fechas">&nbsp;</td>
   </tr>
 </table>
 