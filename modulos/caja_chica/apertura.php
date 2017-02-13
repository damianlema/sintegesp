<?

$sql_configuracion=mysql_query("select * from configuracion 
											where status='a'"
												,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

$anio_fijo=$registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo=$registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo=$registro_configuracion["idfuente_financiamiento"];
?>
<script src="modulos/caja_chica/js/apertura_ajax.js"></script>
    <br>
<h4 align=center>Apertura de Caja Chica	</h4>
<h2 class="sqlmVersion"></h2>
<br>
<br />

<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<div align="center">
  <img src="imagenes/search0.png" title="Buscar Apertura" style="cursor:pointer" onclick="window.open('lib/listas/listar_ordenes_compra.php?destino=apertura','Buscar apertura de caja chica','resisable = no, scrollbars = yes, width=900, height = 500')" /> 
  <img src="imagenes/nuevo.png" title="Nuevo Apertura de Caja Chica" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />
	
    <img src="imagenes/imprimir.png" title="Imprimir Apertura de Caja Chica"  onClick="pdf.location.href='lib/reportes/caja_chica/reportes.php?modulo=<?=$_SESSION["modulo"]?>&nombre=apertura_caja_chica&id_orden_compra='+document.getElementById('idorden_compra_servicio').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />  
  
</div>
<br />

<input type="hidden" name="idorden_compra_servicio" id="idorden_compra_servicio">
<table width="70%" border="0" align="center">
  <tr>
    <td width="9%" align="right" class='viewPropTitle'>Numero</td>
    <td width="16%" style="font-weight:bold" id="celda_numero">Aun no Generado</td>
    <td width="9%" align="right" class='viewPropTitle'>Fecha</td>
    <td width="21%" style="font-weight:bold" id="celda_fecha_apertura">Aun no Procesada</td>
    <td width="29%" align="right" class='viewPropTitle'>Fecha de Elaboracion</td>
    <td width="16%" style="font-weight:bold" id="celda_fecha_elaboracion">
    <?
    echo date("Y-m-d");
	?>
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Caja</td>
    <td colspan="3">
    
    <select name="tipo_caja_chica" id="tipo_caja_chica">
      <option value="0">.:: Seleccione ::.</option>
      <?
        $sql_tipo_caja_chica = mysql_query("select * from tipo_caja_chica");
		while($bus_tipo_caja_chica = mysql_fetch_array($sql_tipo_caja_chica)){
			?>
      <option onClick="calcularMonto('<?=$bus_tipo_caja_chica["idtipo_caja_chica"]?>')" value="<?=$bus_tipo_caja_chica["idtipo_caja_chica"]?>">
        <?=$bus_tipo_caja_chica["denominacion"]?>
        </option>
      <?
			}
		?>
    </select>
    
    </td>
    <td align="right" class='viewPropTitle'>Estado</td>
    <td style="font-weight:bold" id="celda_estado">Elaboracion</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Documento</td>
    <td>
    <?
    $sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like  '%-".$_SESSION["modulo"]."-%' and compromete = 'no' and causa = 'no' and paga = 'no'");
	
	?>
    
    
    <select name="tipos_documentos" id="tipos_documentos">
    <?
    while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
	?>
    	<option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
    <?
	}
	?>
    </select>
    
    </td>
    <td align="right" class='viewPropTitle'>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class='viewPropTitle'>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>A&ntilde;o:</td>
    <td>
    
    <select name="anio" id="anio">
        <option value="2008" <?php if ("2008"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2008</option>
        <option value="2009" <?php if ("2009"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2009</option>
        <option value="2010" <?php if ("2010"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2010</option>
        <option value="2011" <?php if ("2011"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2011</option>
        <option value="2012" <?php if ("2012"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2012</option>
        <option value="2013" <?php if ("2013"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2013</option>
        <option value="2014" <?php if ("2014"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2014</option>
        <option value="2015" <?php if ("2015"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2015</option>
        <option value="2016" <?php if ("2016"==$_SESSION["anio_fiscal"]) { echo ' selected';}?>>2016</option>
      </select>
    
    </td>
    <td align="right" class='viewPropTitle'>Tipo Presupuesto:</td>
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
      </select>
    
    </td>
    <td align="right" class='viewPropTitle'>Fuente Financiamiento:</td>
    <td>
    
        <select name="fuente_financiamiento" id="fuente_financiamiento">
            <option>.:: Seleccione ::.</option>
				<?php
                $sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento 
               														 where status='a'",$conexion_db);
                while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) 
                { 
					?>
					<option <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"'; 
					if ($rowfuente_financiamiento["idfuente_financiamiento"]==$idfuente_financiamiento_fijo) {echo ' selected';}?>> <?php echo $rowfuente_financiamiento["denominacion"];?> 
                    </option>
					<?php
                }
            ?>
        </select>
    
    
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Categoria Programatica</td>
    <td colspan="3"><table border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td><?
            $sql_configuracion_categoria = mysql_query("select categoria_programatica.idcategoria_programatica,
														unidad_ejecutora.denominacion
															from 
														 configuracion, 
														 categoria_programatica, 
														 unidad_ejecutora
															where
														 categoria_programatica.idcategoria_programatica = configuracion.idcategoria_programatica
														 and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora")or die(mysql_error());
			$bus_configuracion_categoria = mysql_fetch_array($sql_configuracion_categoria);
			?>
          <input type="text" name="nombre_categoria" id="nombre_categoria" size="80" readonly="readonly" value="<?=$bus_configuracion_categoria["denominacion"]?>"/>
          <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" value="<?=$bus_configuracion_categoria["idcategoria_programatica"]?>"/></td>
        <td align="left"><img style="display:block; cursor:pointer"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 /></td>
        <td width="216" align="right">&nbsp;</td>
      </tr>
    </table></td>
    <td align="right" class='viewPropTitle'>Monto</td>
    <td>
      
      <input type="hidden" name="monto" id="monto" style="text-align:right" value="0">
      <input type="text" name="monto_mostrado" id="monto_mostrado" style="text-align:right" value="0.00" onClick="this.select()" onblur="formatoNumero(this.name, 'monto')">
    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Beneficiario</td>
    <td colspan="5"><table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="90"  readonly="readonly"/>
          <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
          <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" /></td>
        <td><img style="display:block; cursor:pointer"
                                        src="imagenes/search0.png" 
                                        title="Buscar Nuevo Proveedor" 
                                        id="buscarProveedor2" 
                                        name="buscarProveedor" 
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=rendicion','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Justificacion</td>
    <td colspan="5"><label>
      <textarea name="justificacion" cols="80" rows="5" id="justificacion"></textarea>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Responsable</td>
    <td><label>
      <input type="text" name="responsable" id="responsable">
    </label></td>
    <td align="right" class='viewPropTitle'>CI Responsable</td>
    <td><label>
      <input type="text" name="ci_responsable" id="ci_responsable">
    </label></td>
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
    <td colspan="6"><table border="0" align="center">
      <tr>
        <td>
          <input type="button" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" style="display:block" onclick="ingresarDatosBasicos()">
        </td>
        <td>
          <input type="button" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onclick="procesarApertura()">
        </td>
        <td>
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onclick="modificarDatosBasicos()">
        </td>
        <td>
          <input type="button" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onclick="anularApertura()">
        </td>
      </tr>
    </table></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
