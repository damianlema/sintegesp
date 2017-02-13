<?

$sql_configuracion=mysql_query("select * from configuracion 
											where status='a'"
												,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

$anio_fijo=$registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo=$registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo=$registro_configuracion["idfuente_financiamiento"];
?>

<script src="modulos/caja_chica/js/reposicion_caja_chica_ajax.js"></script>
    <br>
<h4 align=center>Reposicion de Caja Chica</h4>
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
  <img src="imagenes/search0.png" title="Buscar Compromiso" style="cursor:pointer" onclick="window.open('lib/listas/listar_ordenes_compra.php?destino=rendicion','buscar orden compra servicio','resisable = no, scrollbars = yes, width=900, height = 500')" /> 
  <img src="imagenes/nuevo.png" title="Ingresar nueva Solicitud de Cotizacion" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />
  
  <img src="imagenes/imprimir.png" title="Imprimir Orden de Compra/Servicios"  onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?modulo=<?=$_SESSION["modulo"]?>&nombre=ordencs&id_orden_compra='+document.getElementById('idrendicion').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
  </div>
<br />

<input type="hidden" id="idrendicion" name="idrendicion">
<input type="hidden" value="0" id="pestana_seleccionada" name="pestana_seleccionada">
<table width="70%" border="0" align="center">
  <tr>
    <td width="83%"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="10%" align="right" class='viewPropTitle'>N&ordm; de Rendici&oacute;n</td>
        <td width="9%" id="celda_numero_orden" style="font-weight:bold">Aun no Procesado</td>
        <td width="8%" align="right" class='viewPropTitle'>Fecha de Rendici&oacute;n</td>
        <td width="11%" id="fecha_orden" style="font-weight:bold">&nbsp;</td>
        <td width="13%" align="right" class='viewPropTitle'>Fecha de Elaboraci&oacute;n</td>
        <td id="fecha_elaboracion" style="font-weight:bold"><?=date("Y-m-d")?></td>
      </tr>
      
      
      <tr>
        <td align="right" class='viewPropTitle'>Tipo de Caja Chica</td>
        <td colspan="3"><select name="tipo_caja_chica" id="tipo_caja_chica">
          <option value="0" onclick="document.getElementById('buscarCategoriaProgramatica').style.display='none'">.:: Seleccione ::.</option>
		  <?
        $sql_tipo_caja_chica = mysql_query("select * from tipo_caja_chica");
		while($bus_tipo_caja_chica = mysql_fetch_array($sql_tipo_caja_chica)){
			?>
          <option onclick="document.getElementById('buscarCategoriaProgramatica').style.display='block', actualizarMontosMaximos('<?=$bus_tipo_caja_chica["idtipo_caja_chica"]?>')" value="<?=$bus_tipo_caja_chica["idtipo_caja_chica"]?>">
            <?=$bus_tipo_caja_chica["denominacion"]?>
            </option>
          <?
			}
		?>
        </select></td>
        <td class='viewPropTitle'><div align="right"><span class="viewPropTitle">Estado:</span></div></td>
        <td colspan="2" id="celda_estado" style=" font-weight:bold">En Elaboraci&oacute;n</td>
        </tr>
      
      
      
      <tr>
        <td align="right" class='viewPropTitle'>Tipos de Documentos</td>
        <td colspan="2">
        
        <?
			$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like  '%-".$_SESSION["modulo"]."-%' and compromete = 'si' and causa = 'no' and paga = 'no'");
			
			?>
			
			
			<select name="tipos_documentos" id="tipos_documentos">
			<?
			while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
			?>
				<option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
			<?
			}
			?>
			</select>        </td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
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
      </select>    </td>
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
      </select>    </td>
    <td align="right" class='viewPropTitle'>Fuente Financiamiento:</td>
    <td colspan="2">
    
        <select name="fuente_financiamiento" id="fuente_financiamiento">
            <option>.:: Seleccione ::.</option>
				<?php
                $sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento 
               														 where status='a'",$conexion_db);
                while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) 
                { 
					?>
					<option <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"'; 
					if ($rowfuente_financiamiento["idfuente_financiamiento"]==$idfuente_financiamiento_fijo) {echo ' selected';}?>> <?php echo $rowfuente_financiamiento["denominacion"];?>                    </option>
					<?php
                }
            ?>
        </select>    </td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Categoria Programatica</td>
        <td colspan="6"><table border="0" align="left" cellpadding="0" cellspacing="0">
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
              <input type="text" name="nombre_categoria" id="nombre_categoria" size="100" readonly="readonly" value="<?=$bus_configuracion_categoria["denominacion"]?>"/>
              <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" value="<?=$bus_configuracion_categoria["idcategoria_programatica"]?>"/></td>
            <td align="left"><img style="display:none; cursor:pointer"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=reposicion&tcc='+document.getElementById('tipo_caja_chica').value+'','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 /></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top" align="right" class='viewPropTitle'>Justificacion</td>
        <td colspan="6"><textarea name="justificacion" cols="100" rows="5" id="justificacion"></textarea></td>
      </tr>
      <tr>
        <td align="right" class='viewPropTitle'>Sujeto a Reponer</td>
        <td colspan="6"><table border="0" cellpadding="0" cellspacing="0">
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
        <td colspan="7"><table width="80%" border="0">
          <tr>
            <td align="right" class='viewPropTitle'>Maximo a Reponer:</td>
            <td id="celda_maximo_reponer" width="20%" align="right" style=" font-weight:bold">0,00</td>
            <td align="right" class='viewPropTitle'>Maximo a por Factura:</td>
            <td id="celda_maximo_factura" width="20%" align="right" style=" font-weight:bold">0,00</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7" align="center"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onclick="ingresarDatosBasicos()" /></td>
            <td><input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onclick="procesarOrden()"/></td>
            <td><input type="submit" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onclick="anularOrden()"/></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="17%" valign="top">
    
    <table width="100%" border="0" style="border:#CCC 1px solid">
      <tr>
        <td width="51%"><strong>Exento</strong></td>
        <td width="49%" align="right" id="celda_exento">0,00</td>
      </tr>
      <tr>
        <td ><strong>Sub Total</strong></td>
        <td align="right" id="celda_subtotal">0,00</td>
      </tr>
      <tr>
        <td ><strong>Impuesto</strong></td>
        <td align="right" id="celda_impuesto">0,00</td>
      </tr>
      <tr>
        <td ><strong>Total</strong></td>
        <td align="right" id="celda_total">0,00</td>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<br />

<div id="div_parte_inferior" style="display:none">
<table style="border:#CCC solid 1px" align="center" width="60%">
	<tr>
		<td id="celda_pestanas">
        	
        </td>
	</tr>
    <tr>
    	<td>
        	<table style="border:#CCC solid 1px; margin-bottom:3px; margin-left:3px; margin-right:3px;" width="99%">
            	<tr>
            		<td id="celda_datos_factura">
                    	<input type="hidden" id="idfactura" name="idfactura">
                        <table id="tabla_datos_factura" style="display:none">
                            <tr>
                                <td>Nro. Factura</td>
                                <td><input type="text" name="nro_factura" id="nro_factura"></td>
                                <td>Fecha. Factura</td>
                                <td>
                                <input type="text" name="fecha_factura" id="fecha_factura" size="13" readonly="readonly"/>
                                <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
                                <script type="text/javascript">
                                                    Calendar.setup({
                                                    inputField    : "fecha_factura",
                                                    button        : "f_trigger_c",
                                                    align         : "Tr",
                                                    ifFormat      : "%Y-%m-%d"
                                                    });
                                                </script>
                               </td>
                            </tr>
                            <tr>
                                <td>Nro. Control</td>
                                <td><input type="text" name="nro_control" id="nro_control"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Proveedor</td>
                                <td colspan="3">
                                	
                                    <table cellpadding="0" cellspacing="0" border="0">
                                      <tr>
                                        <td>
                                            <input name="nombre_beneficiario_factura" type="text" id="nombre_beneficiario_factura" size="90"  readonly="readonly"/>
                                            <input type="hidden" name="contribuyente_ordinario_factura" id="contribuyente_ordinario_factura" />
                                            <input type="hidden" name="idbeneficiario_factura" id="idbeneficiario_factura" />
                                        </td>
                                        <td>
                                        	<img style="display:block; cursor:pointer"
                                            src="imagenes/search0.png" 
                                            title="Buscar Nuevo Proveedor" 
                                            id="buscarProveedor" 
                                            name="buscarProveedor" 
                                            onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=rendicion_factura','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" />
                                       </td>
                                      </tr>
                                    </table>
                                
                                </td>
                            </tr>
                            <tr>
                            <td colspan="4" align="left">
                            <input type="button" value="Guardar Factura" name="boton_ingresar_factura" id="boton_ingresar_factura" class="button" onclick="guardarFactura()">
                            </td>
                            </tr>
                        </table>
                        
                        
                    </td>
            	</tr>
                <tr>
                	<td>
                    	<div id="celda_nuevos_materiales" style="display:none">
                    	<form name="formularioIngresarMateriales" onsubmit="return ingresarMaterial()">
                            <table width="78%" border="0" align="center" cellpadding="0" cellspacing="0" id="formularioMateriales" style="display:block">
                                <tr>
                                    <td>
                                    &nbsp;<img src="imagenes/search0.png" 
                                                style="cursor:pointer"
                                                title="Buscar Nuevo Material" 
                                                id="buscarMaterial" 
                                                name="buscarMaterial" 
                                                onClick="window.open('lib/listas/listar_materiales.php?destino=orden_compra',
                                                                    '',
                                                                    'resizable = no, scrollbars=yes, width = 800, height= 400')">
                                  </td>
                                  <td align="center">Codigo:<br>
                                    <input name="codigo_material" type="text" disabled id="codigo_material" size="10">
                                    <input type="hidden" id="id_material" name="id_material">                
                                  </td>
                                  <td align="center">Descipcion:<br><input name="descripcion_material" type="text" disabled id="descripcion_material" size="65"></td>
                                  <td align="center">Und:<br><input name="unidad_medida" type="text" disabled id="unidad_medida" size="5"></td>
                                  <td align="center">Cantidad:<br><input name="cantidad" type="text" id="cantidad" size="10"></td>
                                  <td align="center">PU:<br><input name="precio_unitario" type="text" id="precio_unitario" size="10"></td>
                                  <td>&nbsp;<input type="image" src="imagenes/validar.png" 
                                                                title="Procesar Material" 
                                                                id="procesarMaterial" 
                                                                name="procesarMaterial">
                                  </td>
                               </tr>
                            </table>
                        </form>
                        </div>
                   	  <!-- AQUI VA LOS CAMPOS PARA SELECCIONAR LOS NUEVOS MATERIALES -->
                    </td>
                </tr>
                <tr>
                	<td align="center">
                    	<table id="tabla_totales_factura" style="display:none">
                        	<tr>
                            <td><strong>Exento</strong></td>
                            <td id="celda_exento_factura">0,00</td>
                            <td><strong>Sub Total</strong></td>
                            <td id="celda_subtotal_factura">0,00</td>
                            <td><strong>Impuesto</strong></td>
                            <td id="celda_impuesto_factura">0,00</td>
                            <td><strong>Total</strong></td>
                            <td id="celda_total_factura">0,00</td>
                            <td>&nbsp;<img src="imagenes/refrescar.png" title="Recalcular Montos de la Factura" style="cursor:pointer" onclick="actualizarTotalesFactura()"></td>
                            </tr>
                         </table>
                  </td>
                </tr>
                <tr>
                	<td id="celda_materiales" align="center">
                    	Sin Materiales Asociados
                  </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br />
<center><div align="center" style="background-color:#CCC; width:60%; font-weight:bold">PARTIDAS</div></center>
<br />
<table style="border:#CCC solid 1px" align="center" width="60%">
    <tr>
	    <td align="center" id="celda_partidas">
			Sin Partidas Asociadas
        </td>
    </tr>
</table>
</div>
<br />
<br />
<br />

