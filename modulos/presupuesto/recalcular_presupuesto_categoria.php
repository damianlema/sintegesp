<script src="modulos/presupuesto/js/recalcular_presupuesto_categoria_ajax.js"></script>
    <br>
<h4 align=center>
Recalcular Presupuesto
<br>
<br>
</h4>

<?
$sql_configuracion=mysql_query("select * from configuracion 
											where status='a'"
												,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

$anio_fijo=$registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo=$registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo=$registro_configuracion["idfuente_financiamiento"];
include "../../../funciones/funciones.php";
?>


<table width="200" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td colspan="2"><table width="100%" id="datosExtra" style="display:block">
      <tr>
        <td width="136" align="right" class='viewPropTitle'>Fuente de Financiamiento</td>
        <td width="135"><select name="fuente_financiamiento" id="fuente_financiamiento">
            <option>.:: Seleccione ::.</option>
            <?php
					$sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento 
												where status='a'"
													,$conexion_db);
						while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) 
							{ 
								?>
            <option <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"'; 
													if ($rowfuente_financiamiento["idfuente_financiamiento"]==$idfuente_financiamiento_fijo) {echo ' selected';}?>> <?php echo $rowfuente_financiamiento["denominacion"];?> </option>
            <?php
							}
					?>
        </select></td>
        <td width="131" align="right" class='viewPropTitle'>Tipo de Presupuesto</td>
        <td width="135" align="right" ><select name="tipo_presupuesto" id="tipo_presupuesto">
            <option>.:: Seleccione ::.</option>
            <?php
					$sql_tipo_presupuesto=mysql_query("select * from tipo_presupuesto 
											where status='a'"
												,$conexion_db);
						while($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) 
							{ 
								?>
            <option <?php echo 'value="'.$rowtipo_presupuesto["idtipo_presupuesto"].'"'; 
											if ($rowtipo_presupuesto["idtipo_presupuesto"]==$idtipo_presupuesto_fijo){echo ' selected';}?>> <?php echo $rowtipo_presupuesto["denominacion"];?> </option>
            <?php
							}
					?>
        </select></td>
        <td width="59" align="right" class='viewPropTitle'>A&ntilde;o</td>
        <td width="66"><select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
        </select></td>
        <td width="63" align="right" class='viewPropTitle'>Ordinal</td>
        <td width="277"><table align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><?
            $sql_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
			$bus_ordinal = mysql_fetch_array($sql_ordinal);
			?>
                  <input type="text" name="descripcion_ordinal" id="descripcion_ordinal" size="30" readonly="readonly" value="(<?=$bus_ordinal["codigo"]?>) <?=$bus_ordinal["denominacion"]?>"/>
                  <input type="hidden" name="id_ordinal" id="id_ordinal" value="<?=$bus_ordinal["idordinal"]?>" />              </td>
              <td><img style="display:block"
                                        src="imagenes/search0.png" 
                                        title="Buscar Ordinal" 
                                        id="buscarOrdinal" 
                                        name="buscarOrdinal"
                                        onclick="window.open('lib/listas/lista_ordinal.php?destino=orden_compra','','resizable = no, scrollbars=yes, width=600, height=400')" 
                                         /> </td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="158" align="right" class='viewPropTitle'>Categoria Programatica</td>
    <td width="655">
    
    
    <table width="200" border="0" align="left" cellpadding="0" cellspacing="0">
   	  <tr>
            <td width="600">
                <input type="text" name="nombre_categoria" id="nombre_categoria" size="100" disabled/>
                <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" />           </td>
            <td width="64" align="left"><img style="display:block"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 /></td>
           </tr>
        </table>    </td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    	<input type="button" 
        		class="button" 
                name="botonProcesar" 
                id="botonProcesar" 
                value="Procesar"
                onClick="procesar(document.getElementById('fuente_financiamiento').value,
                					document.getElementById('tipo_presupuesto').value,
                                    document.getElementById('anio').value,
                                    document.getElementById('id_ordinal').value,
                                    document.getElementById('id_categoria_programatica').value)">    </td>
  </tr>
</table>
