<html>
    <head>
        <link href="modulos/bienes/css/estilos_bienes.css" rel="stylesheet" type="text/css" />
        <script src="modulos/bienes/js/edificio_ajax.js"></script>
</head>
<body>

<h4 align=center>
Inscripci&oacute;n de Edificios

</h4>

		<center>	
			<img src="imagenes/search0.png" 
            	title="Buscar Edificios" 
                style="cursor:pointer" 
                onClick="window.open('modulos/bienes/lib/listar_edificios.php','emision_pagos','resisable = no, scrollbars = yes, width = 900, height = 400')" /> 
            <img src="imagenes/nuevo.png" 
            	title="Nuevo Edificio" 
                onClick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" 
                style="cursor:pointer" /> 
                
            <img src="imagenes/imprimir.png" 
            	title="Imprimir Emision de Pago"
                onclick="mostrarPDF('<?=$_SESSION["rutaReportes"]?>');"  
                style="cursor:pointer" /> 
        </center>

<div id="tabsF">
  <ul>
    <li><a href="javascript:;" onClick="mostrarContenido('divDatosPrincipales')"><span>Datos Principales</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('divAnexidades')"><span>Anexidades</span></a></li>
    <li><a href="javascript:;" onClick="mostrarContenido('divLinderos')"><span>Contabilidad</span></a></li>
  </ul>
</div>
<br>

<table align="center" width="70%">
<tr>
<td align="right" width="90%">
<input type="button"
        class="button"
        id="linkEliminar"
        onClick="eliminarEdificio(document.getElementById('idedificio').value)"
        style="display:none"
        value="Eliminar Edificio">

</td>
<td align="right">
<input type="button" 
        name="botonEnviarFormulario" 
        id="botonEnviarFormulario" 
        class="button" 
        value="Registrar Edificio"
        onclick="ingresarEdificio()"
        style=" display:block">
 </td>
 <td align="right">  
  <input type="button" 
        name="botonModificarFormulario" 
        id="botonModificarFormulario" 
        class="button" 
        value="Modificar Edificio"
        onclick="editarEdificio()"
        style=" display:none">
</td>
</tr>
</table>
<table align="center" width="100%">
    <tr>
        <td id="celdaContenido" align="center">

        
        <!-- TABLA DE DATOS BASICOS -->
<input type="hidden" name="idedificio" id="idedificio">
<div id="divDatosPrincipales" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:380px; width:95%; margin-left:-47%; margin-top:0px; overflow:auto">
  <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
    
    <tr>
      <td align="right" class='viewPropTitleNew'>Tipo Movimiento</td>
      <td width="125"><label>
        <select name="idtipo_movimiento" id="idtipo_movimiento">
          <?
                  $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bienes where afecta = 1");
				  while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
				  ?>
          <option value="<?=$bus_tipo_movimiento["idtipo_movimiento_bienes"]?>"> (
            <?=$bus_tipo_movimiento["codigo"]?>
            )
            <?=$bus_tipo_movimiento["denominacion"]?>
            </option>
          <?
				  }
				  ?>
        </select>
      </label></td>
      </tr>
      <tr>
      <td align="right" class='viewPropTitleNew'>C&oacute;digo Cat&aacute;logo</td>
      <td width="138"><label>
        <select name="iddetalle_catalogo_bienes" id="iddetalle_catalogo_bienes">
          <?
                  $sql_detalle_catalogo = mysql_query("select * from subgrupo_catalogo_bienes");
				  while($bus_detalle_catalogo = mysql_fetch_array($sql_detalle_catalogo)){
				  	if(substr($bus_detalle_catalogo["codigo"],0,1) == 1){
				  ?>
                      <option value="<?=$bus_detalle_catalogo["idsubgrupo_catalogo_bienes"]?>"> (
                        <?=$bus_detalle_catalogo["codigo"]?>
                        )
                        <?=$bus_detalle_catalogo["denominacion"]?>
                        </option>
                      <?
                      }
				  }
				  ?>
        </select>
      </label></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Estado (Territorio)</td>
      <td colspan="3"><select name="ubicacion_geografica_estado" id="ubicacion_geografica_estado">
        <option value="0">.:: Seleccione ::.</option>
        <?
        $sql_estados = mysql_query("select * from estado where idpais = '1'");
		while($bus_estados = mysql_fetch_array($sql_estados)){
			?>
        <option value="<?=$bus_estados["idestado"]?>" onClick="cambiarMunicipios(this.value)">
          <?=$bus_estados["denominacion"]?>
          </option>
        <?
		}
		?>
      </select>      </td>
    <tr>
      <td align="right" class='viewPropTitleNew'>Organización:</td>
      <td>
      <select name="organizacion" id="organizacion">
      	<option value="0">.:: Seleccione ::.</option>
        <?
        $sql_organizacion = mysql_query("select * from organizacion");
		while($bus_organizacion = mysql_fetch_array($sql_organizacion)){
			?>
				<option value="<?=$bus_organizacion["idorganizacion"]?>"><?=$bus_organizacion["denominacion"]?></option>
			<?
		}
		?>
      </select >
      </td>
    </tr>
    <tr>
      <td align="right" valign="top" class='viewPropTitleNew'>C&oacute;digo del Bien</td>
      <td colspan="3">
      <input type="text" id="codigo_bien" name="codigo_bien" maxlength="25" size="25" onBlur="validarCodigoBien(this.value)">
      
      <input type="hidden" id="codigo_bien_automatico" name="codigo_bien_automatico" maxlength="25" size="25">
      
      <img id='idgenerar_codigo' src='imagenes/refrescar.png' title="Generar C&oacute;digo" onClick="generar_codigo()" style="cursor:pointer" style="display:block"> 
      
      
      </td>
    </tr>
    <tr>
      <td align="right" valign="top" class='viewPropTitleNew'>Denominación del Inmueble</td>
      <td colspan="3"><label>
        <textarea name="denominacion_inmueble" cols="125" id="denominacion_inmueble"></textarea>
      </label></td>
    </tr>
    <tr>
      <td colspan="4" class='viewPropTitleNew'>Clasificación Funcional del Inmueble (Uso Principal al que Esta Destinado)</td>
    </tr>
    <tr>
      <td colspan="4"><label>
        <textarea name="clasificacion_funcional_inmueble" cols="170" rows="3" id="clasificacion_funcional_inmueble"></textarea>
      </label></td>
    </tr>
    <tr>
      <td colspan="4" align="left" class='viewPropTitleNew'><strong>Ubicación Geográfica:</strong>
          <label></label></td>
    </tr>
    
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Municipio</td>
      <td colspan="3" id="celda_ubicacion_geografica_municipio"><select id="ubicacion_geografica_municipio" name="ubicacion_geografica_municipio">
        <option value="0">.:: Seleccione Primero el Estado ::.</option>
      </select >      </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Dirección</td>
      <td colspan="3"><label></label>
          <textarea name="ubicacion_geografica_direccion" cols="101" id="ubicacion_geografica_direccion"></textarea>
          <label></label></td>
    </tr>
    <tr>
      <td colspan="4" align="left" class='viewPropTitleNew'>Área del Terrero</td>
    </tr>
    <tr>
      <td colspan="4" align="left" class='viewPropTitleNew'><label>
        <textarea name="area_terreno" cols="170" id="area_terreno"></textarea>
      </label></td>
    </tr>
    <tr>
      <td colspan="4" class='viewPropTitleNew'>Área de Construcción (Área Cubierta ocupada por el edificio sobre el terreno)</td>
    </tr>
    <tr>
      <td colspan="4"><label>
        <textarea name="area_construccion" cols="170" id="area_construccion"></textarea>
      </label></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Numero de Pisos</td>
      <td><label>
        <input name="numero_pisos" type="text" id="numero_pisos" size="5">
      </label></td>
      <td>&nbsp;</td>
      <td><label></label></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Area Total de Construccion (Total de los Pisos)</td>
      <td><input type="text" name="area_total_construccion" id="area_total_construccion"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Area de las Anexidades (Jardines, Patios, Etc.)</td>
      <td><input type="text" name="area_anexidades" id="area_anexidades"></td>
      <td>&nbsp;</td>
    </tr>
    
  </table>
</div>
<!-- TABLA DE ANEXIDADES -->
        
<div id="divAnexidades" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:380px; width:95%; margin-left:-47%; margin-top:0px; overflow:auto">
                <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
                  
                  <tr>
                    <td><strong>Tipo de Estructura</strong></td>
                    <td>&nbsp;</td>
                    <td><strong>Pisos</strong></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Paredes de Carga</td>
                    <td><label>
                      <input type="checkbox" name="tipo_estructura" id="tipo_estructura" value="Paredes de Carga"/>
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Tierra</td>
                    <td><label>
                      <input type="checkbox" name="pisos" id="pisos" value="Tierra"/>
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Madera</td>
                    <td><label>
                      <input type="checkbox" name="tipo_estructura2" id="tipo_estructura2" value="Madera"/>
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Cemento</td>
                    <td><input type="checkbox" name="pisos2" id="pisos2" value="Cemento"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Metalica</td>
                    <td><input type="checkbox" name="tipo_estructura3" id="tipo_estructura3" value="Metalica"/></td>
                    <td align="right" class='viewPropTitleNew'>Ladrillo</td>
                    <td><input type="checkbox" name="pisos3" id="pisos3" value="Ladrillo"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Concreto Armado</td>
                    <td><input type="checkbox" name="tipo_estructura4" id="tipo_estructura4" value="Concreto Armado"/></td>
                    <td align="right" class='viewPropTitleNew'>Mosaico</td>
                    <td><input type="checkbox" name="pisos4" id="pisos4" value="Mosaico"/></td>
                  </tr>
                  <tr>
                  	<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    
                    <td align="right" class='viewPropTitleNew'>Granito</td>
                    <td><input type="checkbox" name="pisos5" id="pisos5" value="Granito"/></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top" class='viewPropTitleNew'>Otras</td>
                    <td><label>
                      <textarea name="tipo_estructura5" id="tipo_estructura5"></textarea>
                    </label></td>
                    <td align="right" valign="top" class='viewPropTitleNew'>Otros</td>
                    <td><label>
                      <textarea name="pisos6" id="pisos6"></textarea>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><strong>Paredes</strong></td>
                    <td>&nbsp;</td>
                    <td><strong>Techos</strong></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Bloque de Arcilla</td>
                    <td><label>
                      <input type="checkbox" name="paredes" id="paredes" value="Bloque de Arcilla"/>
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Metalicos (Zinc o Aluminio)</td>
                    <td><label>
                      <input type="checkbox" name="techos" id="techos" value="Metalicos"/>
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Bloques de Concreto</td>
                    <td><input type="checkbox" name="paredes2" id="paredes2" value="Bloques de Concreto"/></td>
                    <td align="right" class='viewPropTitleNew'>Asbesto</td>
                    <td><input type="checkbox" name="techos2" id="techos2" value="Asbesto"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Ladrillos</td>
                    <td><input type="checkbox" name="paredes3" id="paredes3" value="Ladrillos"/></td>
                    <td align="right" class='viewPropTitleNew'>Tejas de Arcilla sobre losa de concreto</td>
                    <td><input type="checkbox" name="techos3" id="techos3" value="Tejas de Arcilla sobre losa de concreto"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Madera</td>
                    <td><input type="checkbox" name="paredes4" id="paredes4" value="Madera"/></td>
                    <td align="right" class='viewPropTitleNew'>Tejas de Arcilla sobre caña amarga o similar</td>
                    <td><input type="checkbox" name="techos4" id="techos4" value="Tejas de Arcilla sobre caña amarga o similar"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Metalicas</td>
                    <td><input type="checkbox" name="paredes5" id="paredes5" value="Metalicas"/></td>
                    <td align="right" class='viewPropTitleNew'>Platabanda</td>
                    <td><input type="checkbox" name="techos5" id="techos5" value="Platabanda"/></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top" class='viewPropTitleNew'>Otras</td>
                    <td><label>
                      <textarea name="paredes6" id="paredes6"></textarea>
                    </label></td>
                    <td align="right" valign="top" class='viewPropTitleNew'>Otros</td>
                    <td><label>
                      <textarea name="techos6" id="techos6"></textarea>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><strong>Puertas y Ventanas</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>De Madera</td>
                    <td><label>
                      <input type="checkbox" name="puertas_ventanas" id="puertas_ventanas" value="Madera"/>
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Metalicas</td>
                    <td><input type="checkbox" name="puertas_ventanas2" id="puertas_ventanas2" value="Metalica"/></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><strong>Servicios</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Sanitarios</td>
                    <td><label>
                      <input type="checkbox" name="servicios" id="servicios" value="Sanitarios"/>
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Telefonos</td>
                    <td><input type="checkbox" name="servicios5" id="servicios5" value="Telefonos"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Cocinas</td>
                    <td><input type="checkbox" name="servicios2" id="servicios2" value="Cocinas"/></td>
                    <td align="right" class='viewPropTitleNew'>Aire Acondicionado</td>
                    <td><input type="checkbox" name="servicios6" id="servicios6" value="Aire Acondicionado"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Agua Corriente</td>
                    <td><input type="checkbox" name="servicios3" id="servicios3" value="Agua Corriente"/></td>
                    <td align="right" class='viewPropTitleNew'>Ascensores</td>
                    <td><input type="checkbox" name="servicios7" id="servicios7" value="Ascensores"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Electricidad</td>
                    <td><input type="checkbox" name="servicios4" id="servicios4" value="Electricidad"/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right" valign="top" class='viewPropTitleNew'>Otros</td>
                    <td><label>
                      <textarea name="servicios8" id="servicios8"></textarea>
                    </label></td>
                  </tr>
                  <tr>
                    <td><strong>Otras Anexidades del Edificio</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Patios</td>
                    <td><label>
                      <input type="checkbox" name="otras_anexidades" id="otras_anexidades" value="Patios"/>
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Estacionamientos</td>
                    <td><input type="checkbox" name="otras_anexidades3" id="otras_anexidades3" value="Estacionamientos"/></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Jardines</td>
                    <td><input type="checkbox" name="otras_anexidades2" id="otras_anexidades2" value="Jardines"/></td>
                    <td align="right" valign="top" class='viewPropTitleNew'>Otros</td>
                    <td><textarea name="otras_anexidades4" id="otras_anexidades4"></textarea></td>
                  </tr>
                  
                </table>
          </div>
                
                
                
                
                
                
                
                
                
                <!-- TABLA DE DATOS SERVICIOS-->
        
        	
            	<div id="divLinderos" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:380px; width:95%; margin-left:-47%; margin-top:0px; overflow:auto">
                    <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
                      
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Linderos</strong></td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="linderos" cols="170" rows="3" id="linderos"></textarea>
                        </label></td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Estudio Legal de la Propiedad </strong>(Obtener dictamen del procurador del estado o del sindico procurador municipal)</td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="estado_legal" cols="170" rows="3" id="estado_legal"></textarea>
                        </label></td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Valor con que figura en la Contabilidad:</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td width="165">
                          <input name="valor_contabilidad_fecha" type="text" id="valor_contabilidad_fecha" size="12" readonly/>
                          <img src="imagenes/jscalendar0.gif" name="f_valor_contabilidad" width="16" height="16" id="f_valor_contabilidad" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "valor_contabilidad_fecha",
							button        : "f_valor_contabilidad",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                       </td>
                        <td width="171" align="right" class='viewPropTitleNew'>Valor de Adquisici&oacute;n Bs:</td>
                        <td width="190">
                          <input 
                              name="valor_contabilidad_monto_mostrado" type="text" 
                              id="valor_contabilidad_monto_mostrado" 
                              style="text-align:right" 
                              onBlur="formatoNumero(this.name, 'valor_contabilidad_monto'), sumaMejoras()" 
                              value="0"/>
                          <input type="hidden" name="valor_contabilidad_monto" id="valor_contabilidad_monto" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Mas adiciones y mejoras</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                          <input name="mejoras_fecha" type="text" id="mejoras_fecha" size="12" readonly/>
                          <img src="imagenes/jscalendar0.gif" name="f_mejora" width="16" height="16" id="f_mejora" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "mejoras_fecha",
							button        : "f_mejora",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                          <input 
                          		name="valor_mejoras_mostrado" type="text" 
                                id="valor_mejoras_mostrado" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras'), sumaMejoras()" 
                                value="0"/>
                          <input type="hidden" name="valor_mejoras" id="valor_mejoras" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="mejoras_fecha2" type="text" id="mejoras_fecha2" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora2" width="16" height="16" id="f_mejora2" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "mejoras_fecha2",
							button        : "f_mejora2",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="valor_mejoras2_mostrado" type="text" 
                                id="valor_mejoras2_mostrado" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras2'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras2" id="valor_mejoras2" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="mejoras_fecha3" type="text" id="mejoras_fecha3" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora3" width="16" height="16" id="f_mejora3" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "mejoras_fecha3",
							button        : "f_mejora3",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="valor_mejoras3_mostrado" type="text" 
                                id="valor_mejoras3_mostrado" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras3'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras3" id="valor_mejoras3" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="mejoras_fecha4" type="text" id="mejoras_fecha4" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora4" width="16" height="16" id="f_mejora4" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "mejoras_fecha4",
							button        : "f_mejora4",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="valor_mejoras4_mostrado" type="text" 
                                id="valor_mejoras4_mostrado" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras4'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras4" id="valor_mejoras4" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="mejoras_fecha5" type="text" id="mejoras_fecha5" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora5" width="16" height="16" id="f_mejora5" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "mejoras_fecha5",
							button        : "f_mejora5",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="valor_mejoras5_mostrado" type="text" 
                                id="valor_mejoras5_mostrado" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras5'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras5" id="valor_mejoras5" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" class='viewPropTitleNew'><strong>Total ... Bs</strong></td>
                        <td><label>
                          <input 
                          		name="total_mejoras_adquisicion_mostrado" type="text" 
                                disabled="disabled" 
                                id="total_mejoras_adquisicion_mostrado" 
                                style="text-align:right; font-weight:bold;"
                                 
                                value="0" size="16"/>
                          <input type="hidden" name="total_mejoras_adquisicion" id="total_mejoras_adquisicion"/>
                        </label></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Avaluo Provicional de la Comision </strong>(Para la construccion y el area de terreno ocupada por la misma)</td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="avaluo_provicional" cols="132" rows="3" id="avaluo_provicional"></textarea>
                        </label></td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Planos, Esquemas y Fotografias: </strong>(Los que se acompañen, con mencion de la Oficina en donde se encuentren los restantes)</td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="planos_esquemas_fotocopias" cols="132" rows="3" id="planos_esquemas_fotocopias"></textarea>
                        </label></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Preparado Por</td>
                        <td colspan="2"><label>
                          <input name="preparado_por" type="text" id="preparado_por" size="60">
                        </label></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Cargo</td>
                        <td colspan="2"><label>
                          <input type="text" name="cargo" id="cargo" size="60">
                        </label></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Lugar</td>
                        <td colspan="2"><label>
                          <input type="text" name="lugar" id="lugar" size="60">
                        </label></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'> Fecha</td>
                        <td colspan="2">
                          <input name="fecha" type="text" id="fecha" size="12">
                          <img src="imagenes/jscalendar0.gif" name="f_fehca" width="16" height="16" id="f_fehca" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                          <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha",
							button        : "f_fehca",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                          </td>
                        <td>&nbsp;</td>
                      </tr>
                      
                    </table>
          </div>        </td>
    </tr>
</table>

</body>
</html>






