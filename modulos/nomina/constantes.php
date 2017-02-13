<script src="modulos/nomina/js/constantes_ajax.js"></script>
</script>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;
}
-->
</style>

	<br>
	<h4 align=center>Constantes de Nomina</h4><br>

	<h2 class="sqlmVersion"></h2>
	<br>
    <br>
<div align="center">
	<img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=877&modulo=13'"> &nbsp;
    <img src="imagenes/imprimir.png" style="cursor:pointer" title="Imprimir" onclick="document.getElementById('divImprimir').style.display='block'; pdf.location.href='lib/reportes/nomina/reportes.php?nombre=nomina_constantes_lista'; document.getElementById('pdf').style.display='block';">
    
    <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
        <div align="right">
            <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
        </div>
        <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
    </div>
</div>
<br />
<br />

<form method="post" name="formulario_constantes" id="formulario_constantes">
<input type="hidden" name="idconstante_nomina" id="idconstante_nomina">
<table width="65%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class='viewPropTitle' align="right">Codigo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="8" maxlength="6">
    </label></td>
    <td class='viewPropTitle' align="right">Abreviatura</td>
    <td><label>
      <input name="abreviatura" type="text" id="abreviatura" size="10" maxlength="10">
    </label></td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Descripcion</td>
    <td colspan="3"><label>
      <input name="descripcion" type="text" id="descripcion" size="80">
    </label></td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Unidad</td>
    <td><label>
      <input name="unidad" type="text" id="unidad" size="16" maxlength="20">
    ( <span class="Estilo1">Ejem. Horas, Bsf Etc.)</span></label></td>
    <td class='viewPropTitle' align="right">Tipo</td>
    <td><label>
      <select name="tipo" id="tipo">
      	<option value="valor_fijo">Valor Fijo</option>
        <option value="tabulado">Tabulado</option>
        <option value="hoja_tiempo">Hoja de Tiempo</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Equivalencia</td>
    <td><label>
    <input type="text" name="equivalencia" id="equivalencia" />
    </label></td>
    <td class='viewPropTitle' align="right">Maximo</td>
    <td><label>
      <input type="text" name="maximo" id="maximo">
    </label></td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Valor</td>
    <td><input type="text" name="valor" id="valor" value="0"/></td>
    <td class='viewPropTitle' align="right">Mostrar</td>
    <td><input type="checkbox" name="mostrar" id="mostrar" /></td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Posicion</td>
    <td><label>
      <input name="posicion" type="text" id="posicion" size="3" value="1"/>
    </label></td>
    <td class='viewPropTitle' align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Tipo de Afectaci&oacute;n</td>
    <td><span class="viewPropTitle">
      <select name="afecta" id="afecta">
        <option value="0">.:: Seleccione ::.</option>
		<?
		$sql_tipos_afectacion = mysql_query("select * from tipo_conceptos_nomina");
        while($bus_tipo_afectacion = mysql_fetch_array($sql_tipos_afectacion)){
			?>
			<option 
			<? if($bus_tipo_afectacion["afecta"] == "Asignacion"){
					?>
					onclick="document.getElementById('buscarClasificador').style.display='block', document.getElementById('buscarOrdinal').style.display='block'"
					<?	
				}else{
					?>
					onclick="document.getElementById('buscarClasificador').style.display='none', document.getElementById('buscarOrdinal').style.display='none', document.getElementById('id_clasificador').value = '', document.getElementById('idordinal').value = '', document.getElementById('clasificador').value = '', document.getElementById('nombre_ordinal').value = ''" 
					<?	
				}
			?> 
            value="<?=$bus_tipo_afectacion["idconceptos_nomina"]?>"><?=$bus_tipo_afectacion["descripcion"]?></option>
			<?	
		}
		?>
      </select>
    </span></td>
    <td class='viewPropTitle' align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class='viewPropTitle' align="right">Clasificador Presupuestario</td>
    <td colspan="3"><?
	  if($bus_busqueda){
     	 $sql_clasificador_presupuestario = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = ".$bus_busqueda["idclasificador_presupuestario"]."");
	 	 $bus_clasificador_presupuestario = mysql_fetch_array($sql_clasificador_presupuestario);
	  }
	  ?>
      <table>
      <tr>
      <td>
        <input type="text" name="clasificador" size="80" disabled="disabled" id="clasificador" value="" />
        <input type="hidden" name="id_clasificador" id="id_clasificador" value="" />      </td>
      <td>
      <a href="#" onclick="window.open('lib/listas/listar_clasificador_presupuestario.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario" id="buscarClasificador" style="display:block"/></a>      </td>
      </tr>
      </table>      </td>
    </tr>
  <tr>
    <td class='viewPropTitle' align="right">Ordinal</td>
    <td colspan="3"><?
      
	  
	  
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
      <input name="nombre_ordinal" type="text" id="nombre_ordinal" size="80" value="" disabled="disabled" />
      <input name="idordinal" type="hidden" id="idordinal" value="" />      </td>
      <td>
      <a href="#" onclick="window.open('lib/listas/lista_ordinal.php?destino=materiales','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario" id="buscarOrdinal" style="display:block"/></a>      </td>
      </tr>
      </table>      </td>
    </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarConstante()"></td>
        <td><input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" style="display:none" class="button" onClick="modificarConstante()"></td>
        <td><input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" style="display:none" class="button" onClick="eliminarConstante()"></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>

<br>
<br>
<br>
<center>
<div style="background-color:#CCC; border:#000 1px solid; display:none; width:500px" id="div_proceso_lote">
<div align="right"><strong onclick="document.getElementById('div_proceso_lote').style.display='none'" style="cursor:pointer" title="Cerrar">X</strong></div>
        	<input type="hidden" name="idconstante_procesar_lote" id="idconstante_procesar_lote">
            <table>
            	<tr>
                	<td>Tipo de Nomina</td>
                    <td>
                    <select name="tipo_nomina" id="tipo_nomina">
                    	<option value="todas">Todas</option>
                        <?
                        $sql_tipo_nomina = mysql_query("select * from tipo_nomina");
						while($bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina)){
						?>
						<option value="<?=$bus_tipo_nomina["idtipo_nomina"]?>"><?=$bus_tipo_nomina["titulo_nomina"]?></option>
						<?
						}
						?>
                    </select>
                    </td>
                </tr>
                <tr>
                	<td>Tipo de Proceso</td>
                    <td>
                    <select name="tipo_proceso_lote" id="tipo_proceso_lote">
                    	<option value="porcentual">Porcentual</option>
                        <option value="valor_fijo">Valor Fijo</option>
                    </select>
                    </td>
                </tr>
                <tr>
                	<td>Valor</td>
                    <td><input type="text" name="monto_lote" id="monto_lote"></td>
                </tr>
                <tr>
                	<td>Rango de Sueldos:</td>
                    <td>
                    	<table>
                        <tr>
                        <td>Entre: <input type="text" name="rango_entre" id="rango_entre"><td>
                        <td>Hasta: <input type="text" name="rango_hasta" id="rango_hasta"><td>
                    	</tr>
                        </table>
                    
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    <input type="button" name="boton_guardar" id="boton_guardar" value="Procesar" onclick="procesoPorLote()" class="button">
                    </td>
                </tr>
            </table>
   </div>
</center>



<div id="lista_constantes">
  
  
  
  
  <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="60%">
    <thead>
      <tr>
        <td width="13%" align="center" class="Browse">Clasificador Presupuestario</td>
        <td width="8%" align="center" class="Browse">Ordinal</td>
        <td width="15%" align="center" class="Browse">Codigo</td>
        <td width="11%" align="center" class="Browse">Abreviatura</td>
        <td width="42%" align="center" class="Browse">Descripcion</td>
        <td align="center" class="Browse" colspan="3">Accion</td>
      </tr>
    </thead>
    <?
          $sql_consulta = mysql_query("select * from constantes_nomina");
		  while($bus_consulta = mysql_fetch_array($sql_consulta)){
		  ?>
    <tr bgcolor='#e7dfce' onmouseover="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onmouseout="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
      <?
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consulta["idclasificador_presupuestario"]."'");
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  $sql_ordinal = mysql_query("select * from ordinal where idordinal = '".$bus_consulta["idordinal"]."'");
		  $bus_ordinal = mysql_fetch_array($sql_ordinal);
		  ?>
      <td class='Browse' align='left'><?=$bus_clasificador["codigo_cuenta"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_ordinal["codigo"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_consulta["codigo"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_consulta["abreviatura"]?>
        &nbsp;</td>
      <td class='Browse' align='left'><?=$bus_consulta["descripcion"]?>
        &nbsp;</td>
      <td width="5%" align='center' class='Browse'><?
            $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_consulta["idclasificador_presupuestario"]."'");
			$bus_clasificador = mysql_fetch_array($sql_clasificador);
			
			$sql_ordinal = mysql_query("select * from ordinal where idordinal = '".$bus_consulta["idordinal"]."'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			
			
			$sql_consulta_asociacion =mysql_query("select * from relacion_concepto_trabajador where idconcepto = '".$bus_consulta["idconstantes_nomina"]."' and tabla = 'constantes_nomina'");
			$num_consulta_asociacion = mysql_num_rows($sql_consulta_asociacion);
			if($num_consulta_asociacion > 0){
				$asociado = 'si';	
			}else{
				$asociado = 'no';	
			}
			
			?>
        <img src="imagenes/modificar.png" onclick="mostrarModificar('<?=$bus_consulta["idconstantes_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["abreviatura"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>',
                                                                            '<?=$bus_consulta["tipo"]?>',
                                                                            '<?=$bus_consulta["equivalencia"]?>',
                                                                            '<?=$bus_consulta["maximo"]?>',
                                                                            '<?=$bus_consulta["valor"]?>',
                                                                            '<?=$bus_consulta["idclasificador_presupuestario"]?>',
                                                                            '<?=$bus_consulta["idordinal"]?>',
                                                                            '<?=$bus_clasificador["denominacion"]?>',
                                                                            '<?=$bus_ordinal["denominacion"]?>',
                                                                            '<?=$bus_consulta["mostrar"]?>',
                                                                            '<?=$asociado?>',
                                                                            '<?=$bus_consulta["afecta"]?>',
                                                                            '<?=$bus_consulta["posicion"]?>')" style="cursor:pointer" /></td>
      <td width="6%" align='center' class='Browse'><img src="imagenes/delete.png" onclick="mostrarEliminar('<?=$bus_consulta["idconstantes_nomina"]?>',
                															'<?=$bus_consulta["codigo"]?>',
                                                                            '<?=$bus_consulta["abreviatura"]?>',
                                                                            '<?=$bus_consulta["descripcion"]?>',
                                                                            '<?=$bus_consulta["unidad"]?>',
                                                                            '<?=$bus_consulta["tipo"]?>',
                                                                            '<?=$bus_consulta["equivalencia"]?>',
                                                                            '<?=$bus_consulta["maximo"]?>',
                                                                            '<?=$bus_consulta["valor"]?>',
                                                                            '<?=$bus_consulta["idclasificador_presupuestario"]?>',
                                                                            '<?=$bus_consulta["idordinal"]?>',
                                                                            '<?=$bus_clasificador["denominacion"]?>',
                                                                            '<?=$bus_ordinal["denominacion"]?>',
                                                                            '<?=$bus_consulta["mostrar"]?>',
                                                                            '<?=$asociado?>',
                                                                            '<?=$bus_consulta["afecta"]?>',
                                                                            '<?=$bus_consulta["posicion"]?>')" style="cursor:pointer" /></td>
        
        <td width="10%" align='center' class='Browse'>
        
        <?
        if($bus_consulta["tipo"] == "valor_fijo"){
		?>
        
        <img src="imagenes/refrescar.png" onclick="document.getElementById('div_proceso_lote').style.display='block', document.getElementById('idconstante_procesar_lote').value = '<?=$bus_consulta["idconstantes_nomina"]?>'" alt="Procesos en Lote" style="cursor:pointer"/>
        <?
		}else{
			?>&nbsp;<?	
		}
		?>
        </td>
    </tr>
    <?
         }
		 ?>
  </table>
</div>
