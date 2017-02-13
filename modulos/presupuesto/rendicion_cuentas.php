<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<script src="modulos/presupuesto/js/rendicion_cuentas_ajax.js"></script>

	<br>
	<h4 align=center>Rendicion de Cuentas</h4>
	<br />
    <br />
    <h2 class="sqlmVersion"></h2>
	<br><br /><br />
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

<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr>
    <td colspan="6" align='center'>
    	<img src="imagenes/nuevo.png" width="16" height="16" onclick="window.location.href='principal.php?accion=661&modulo=2'" style="cursor:pointer"/>
    	
        <img src="imagenes/imprimir.png" title="Imprimir Orden de Pago"  onClick="document.getElementById('pdf').src='lib/<?=$_SESSION["rutaReportes"]?>/presupuesto.php?nombre=reporte_rendicion_cuentas&idrendicion_cuentas='+document.getElementById('idrendicion_cuentas').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Año</td>
    <td><label>
      <select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
      </select>
    </label></td>
    <td align='right' class='viewPropTitle'>MES</td>
    <td><label>
      <select name="mes" id="mes">
        <option value="1">Enero</option>
        <option value="2">Febrero</option>
        <option value="3">Marzo</option>
        <option value="4">Abril</option>
        <option value="5">Mayo</option>
        <option value="6">Junio</option>
        <option value="7">Julio</option>
        <option value="8">Agosto</option>
        <option value="9">Septiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
      </select>
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Concepto</td>
    <td colspan="5"><input name="concepto" type="text" id="concepto" size="120"></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Categoria Programatica</td>
    <td colspan="5">
      <input name="categoria_programatica" type="text" id="categoria_programatica" size="80">
      <img src="imagenes/search0.png" 
                    title="Buscar Categoria Programatica" 
                    id="buscarCategoriaProgramatica" 
                    name="buscarCategoriaProgramatica"
                    style="visibility:visible"
                    onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=rendicion_cuentas','categoria_programatica','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 />
      <input name="id_categoria_programatica" type="hidden" id="id_categoria_programatica">    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Tipo Presupuesto</td>
    <td>
    <select name="tipo_presupuesto" id="tipo_presupuesto">
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
            </select>    </td>
    <td align='right' class='viewPropTitle'>Fuente Financiamiento</td>
    <td>
    <select name="fuente_financiamiento" id="fuente_financiamiento">
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
            </select>    </td>
    <td align='right' class='viewPropTitle'>Ordinal</td>
    <td>
    
    <?
          $sql_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
		  $bus_ordinal = mysql_fetch_array($sql_ordinal);
		  ?>
                    <input type="text" name="descripcion_ordinal" id="descripcion_ordinal" size="30" readonly="readonly" value="(<?=$bus_ordinal["codigo"]?>) <?=$bus_ordinal["denominacion"]?>"/>
                    <input type="hidden" name="ordinal" id="ordinal" value="<?=$bus_ordinal["idordinal"]?>"/>                </td>
                <td><img style="display:block"
                                        src="imagenes/search0.png" 
                                        title="Buscar Ordinal" 
                                        id="buscarOrdinal" 
                                        name="buscarOrdinal"
                                        onclick="window.open('lib/listas/lista_ordinal.php?destino=rendicion_cuentas','','resizable = no, scrollbars=yes, width=600, height=400')" 
                                         />    </td>
  </tr>
  <tr>
    <td colspan="6" align="center">
      <input type="button" name="botonSiguiente" id="botonSiguiente" value="Siguiente" onclick="procesarDatosBasicos()" class="button">    </td>
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

<br>
<br>

<div id="listaPartidas" style="width:100%; text-align:center"></div>
