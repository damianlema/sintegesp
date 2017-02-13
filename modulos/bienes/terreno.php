<html>
    <head>
        <link href="modulos/bienes/css/estilos_bienes.css" rel="stylesheet" type="text/css" />
        <script src="modulos/bienes/js/terreno_ajax.js"></script>
</head>
<body>

<h4 align=center>
Inscripci&oacute;n de Terrenos

</h4>

		<center>	
			<img src="imagenes/search0.png" 
            	title="Buscar Edificios" 
                style="cursor:pointer" 
                onClick="window.open('modulos/bienes/lib/listar_terreno.php','listar_terrenos','resisable = no, scrollbars = yes, width = 900, height = 400')" /> 
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


<table align="center" width="70%">
<tr>
<td align="right" width="90%">
<input type="button"
        class="button"
        id="linkEliminar"
        onClick="eliminarTerreno(document.getElementById('idterreno').value)"
        style="display:none"
        value="Eliminar Terreno">
</td>
<td align="right">

<input type="button" 
        name="botonEnviarFormulario" 
        id="botonEnviarFormulario" 
        class="button" 
        value="Registrar Terreno"
        onclick="ingresarTerreno()"
        style=" display:block">
</td>
<td align="right">    
  <input type="button" 
        name="botonModificarFormulario" 
        id="botonModificarFormulario" 
        class="button" 
        value="Modificar Terreno"
        onclick="editarTerreno()"
        style=" display:none">
</td>
</tr>
</table>

<table align="center" width="100%">
    <tr>
        <td id="celdaContenido" align="center">

        
        <!-- TABLA DE DATOS BASICOS -->
<input type="hidden" name="idterreno" id="idterreno">        
        
		<div id="divDatosPrincipales" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:380px; width:95%; margin-left:-47%; margin-top:0px; overflow:auto">
            <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
             
              <tr>
                <td align="right" class='viewPropTitleNew'>Tipo Movimiento</td>
                <td><label>
                  <select name="idtipo_movimiento" id="idtipo_movimiento">
					  <?
                              $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bienes");
                              while($bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento)){
                              ?>
                      <option value="<?=$bus_tipo_movimiento["idtipo_movimiento_bienes"]?>">
                        (<?=$bus_tipo_movimiento["codigo"]?>) <?=$bus_tipo_movimiento["denominacion"]?>
                        </option>
                      <?
                              }
                              ?>
                    </select>
                </label></td>
                </tr>
                <tr>
                <td align="right" class='viewPropTitleNew'><p>C&oacute;digo Cat&aacute;logo</p></td>
                <td ><label>
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
                <td align="right" class='viewPropTitleNew'>Estado (o Municipio Propietario):</td>
                <td>
                <select name="estado_municipio" id="estado_municipio">
                <option value="0">.:: Seleccione ::.</option>
                <?
                $sql_estados = mysql_query("select * from estado where idpais = '1'");
                while($bus_estados = mysql_fetch_array($sql_estados)){
                    ?>
                    <option value="<?=$bus_estados["idestado"]?>" onClick="cambiarMunicipios(this.value)"><?=$bus_estados["denominacion"]?></option>
                    <?
                }
                ?>
                </select>
                </td>
                </tr>
                <tr>
                <td align="right" class='viewPropTitleNew'>Organizacion</td>
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
                <td align="right" class='viewPropTitleNew'>C&oacute;digo del Bien:</td>
                <td colspan="3">
                  <input type="text" name="codigo_bien" id="codigo_bien" onBlur="validarCodigoBien(this.value)">
                </td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>Denominaci&oacute;n del Inmueble:</td>
                <td colspan="5"><label>
                  <textarea name="denominacion_inmueble" cols="110" rows="3" id="denominacion_inmueble"></textarea>
                </label></td>
              </tr>
              <tr>
                <td colspan="4" class='viewPropTitleNew'><strong>Clasificaci&oacute;n Funcional del Inmueble:</strong></td>
              </tr>
              
              <tr>
                <td align="right" class='viewPropTitleNew'>Agricultura</td>
                <td><label>
                  <input type="checkbox" name="clasificacion_agricultura" id="clasificacion_agricultura">
                </label></td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>Ganadería</td>
                <td><label>
                  <input type="checkbox" name="clasificacion_ganaderia" id="clasificacion_ganaderia">
                </label></td>
              </tr>
              <tr>
                <td align="right" valign="top" class='viewPropTitleNew'>Mixto Agropecuario</td>
          <td valign="top"><label>
                  <input type="checkbox" name="clasificacion_mixto_agropecuario" id="clasificacion_mixto_agropecuario">
                </label></td>
              </tr>
              <tr>
                <td align="right" valign="top" class='viewPropTitleNew'>Otros</td>
          <td><label>
                  <textarea name="clasificacion_otros" rows="3" cols="100" id="clasificacion_otros"></textarea>
                </label></td>
              </tr>
              <tr>
                <td colspan="4" align="left" class='viewPropTitleNew'><strong>Ubicaci&oacute;n Geogr&aacute;fica:</strong>                  <label></label></td>
              </tr>
              <tr>
                <td align="right" valign="top" class='viewPropTitleNew'>Municipio</td>
                <td valign="top" id="celda_ubicacion_municipio">
                <select id="ubicacion_municipio" name="ubicacion_municipio">
                    <option value="0">.:: Seleccione Primero el Estado ::.</option>
                  </select >
                </td>
              </tr>
              <tr>
                <td align="right" valign="top" class='viewPropTitleNew'>Direcci&oacute;n</td>
                <td colspan="5"><textarea name="ubicacion_direccion" rows="3" cols="100" id="ubicacion_direccion"></textarea></td>
              </tr>
              
              <tr>
                <td colspan="4" align="left" class='viewPropTitleNew'><strong>&Aacute;rea del Terreno:</strong></td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>Hect&aacute;reas</td>
                <td colspan="3"><span class="viewPropTitleNew">
                  <textarea name="area_total_terreno_hectarias" cols="101" id="area_total_terreno_hectarias"></textarea>
                </span>                  <label></label></td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>M2</td>
                <td colspan="3" ><textarea name="area_total_terreno_metros" cols="101" id="area_total_terreno_metros"></textarea></td>
              </tr>
              <tr>
                <td align="right" class='viewPropTitleNew'>&Aacute;rea de Construcci&oacute;n M2</td>
                <td colspan="3" ><textarea name="area_construccion_metros" cols="101" id="area_construccion_metros"></textarea></td>
              </tr>
             
            </table>
            
          </div>
            
            
            
            <!-- TABLA DE ANEXIDADES -->
        
        	<!-- <div id="divAnexidades" style="display:none; width:70%; height:530px; overflow:auto; border:#000000 1px solid" align="center"> -->
            <div id="divAnexidades" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:380px; width:95%; margin-left:-47%; margin-top:0px; overflow:auto">
                <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
                 
                  <tr>
                    <td colspan="4" class='viewPropTitleNew'><strong>Tipografia</strong></td>
                  </tr>
                  <tr>
                    <td colspan="4" align="right">
                    
                    <table width="100%" border="0" cellpadding="4" cellspacing="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td align="center" bgcolor="#CCCCCC"><strong>Plana</strong></td>
                        <td align="center" bgcolor="#CCCCCC"><strong>Semi-Plana</strong></td>
                        <td align="center" bgcolor="#CCCCCC"><strong>Pendiente</strong></td>
                        <td align="center" bgcolor="#CCCCCC"><strong>Muy Pendiente</strong></td>
                        <td align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Hectareas %</td>
                        <td align="center">
                          <input name="tipografia_plana" type="text" id="tipografia_plana" size="10" style="text-align:right" onBlur="sumarTipografia()" value="0">                        </td>
                        <td align="center">
                          <input name="tipografia_semiplana" type="text" id="tipografia_semiplana" size="10" style="text-align:right" onBlur="sumarTipografia()" value="0">                        </td>
                        <td align="center">
                          <input name="tipografia_pendiente" type="text" id="tipografia_pendiente" size="10" style="text-align:right" onBlur="sumarTipografia()" value="0">                        </td>
                        <td align="center">
                          <input name="tipografia_muypendiente" type="text" id="tipografia_muypendiente" size="10" style="text-align:right" onBlur="sumarTipografia()" value="0">                        </td>
                        <td align="center">
                          <input name="total_tiografia" type="text" id="total_tiografia" size="10" style="text-align:right" disabled value="0">                        </td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="left" class='viewPropTitleNew'><strong>Cultivos Agricolas</strong>                      <label></label></td>
                  </tr>
                  <tr>
                    <td colspan="4"><table width="100%" border="0" cellpadding="4" cellspacing="0">
                      <tr>
                        <td width="17%">&nbsp;</td>
                        <td width="56%" align="center" bgcolor="#CCCCCC"><strong>Permanentes: (Frutales y mederables)</strong></td>
                        <td width="27%" align="center" bgcolor="#CCCCCC"><strong>Área Deforestada</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Hectareas %</td>
                        <td align="center"><label>
                          <input name="cultivos_permanentes" type="text" id="cultivos_permanentes" value="0" size="10" style="text-align:right">
                        </label></td>
                        <td align="center"><label>
                          <input name="cultivos_deforestados" type="text" id="cultivos_deforestados" value="0" size="10" style="text-align:right">
                        </label></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" class='viewPropTitleNew'><strong>Otros Terrenos</strong></td>
                  </tr>
                  <tr>
                    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="17%">&nbsp;</td>
                        <td width="23%" align="center" bgcolor="#CCCCCC"><strong>Bosques</strong></td>
                        <td width="28%" align="center" bgcolor="#CCCCCC"><strong>Tierras Incultas</strong></td>
                        <td width="32%" align="center" bgcolor="#CCCCCC"><strong>No Aprovechadas</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Hectareas %</td>
                        <td align="center"><label>
                          <input name="otros_bosques" type="text" id="otros_bosques" value="0" size="10" style="text-align:right">
                        </label></td>
                        <td align="center"><label>
                          <input name="otros_tierras_incultas" type="text" id="otros_tierras_incultas" value="0" size="10" style="text-align:right">
                        </label></td>
                        <td align="center"><label>
                          <input name="otros_noaprovechables" type="text" id="otros_noaprovechables" value="0" size="10" style="text-align:right">
                        </label></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" class='viewPropTitleNew'><strong>Potreros</strong></td>
                  </tr>
                  <tr>
                    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="17%">&nbsp;</td>
                        <td width="35%" align="center" bgcolor="#CCCCCC"><strong>Naturales</strong></td>
                        <td width="25%" align="center" bgcolor="#CCCCCC"><strong>Cultivados</strong></td>
                        <td width="23%" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Hectareas %</td>
                        <td align="center"><label>
                          <input name="potreros_naturales" type="text" id="potreros_naturales" size="10" style="text-align:right" onBlur="sumarPotreros()" value="0">
                        </label></td>
                        <td align="center"><label>
                          <input name="potreros_cultivados" type="text" id="potreros_cultivados" size="10" style="text-align:right" onBlur="sumarPotreros()" value="0">
                        </label></td>
                        <td align="center"><label>
                          <input name="total_potreros" type="text" id="total_potreros" size="10" style="text-align:right" disabled value="0">
                        </label></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" class='viewPropTitleNew'><strong>Recursos de Agua: (Naturales y Artificiales)</strong></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Cursos de Agua: (Ríos, Quebradas)</td>
                    <td><label>
                      <input type="text" name="recursos_cursos" id="recursos_cursos">
                    </label></td>
                    <td align="right" class='viewPropTitleNew'><span class="viewPropTitleNew">Manantiales</span></td>
                    <td><input type="text" name="recursos_manantiales" id="recursos_manantiales"></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Canales y Acequias</td>
                    <td><label>
                      <input type="text" name="recursos_canales" id="recursos_canales">
                    </label></td>
                    <td align="right" class='viewPropTitleNew'><span class="viewPropTitleNew">Embalses y Lagunas</span></td>
                    <td><input type="text" name="recursos_embalses" id="recursos_embalses"></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Pozos y Aljubes</td>
                    <td><label>
                      <input type="text" name="recursos_pozos" id="recursos_pozos">
                    </label></td>
                    <td align="right" class='viewPropTitleNew'><span class="viewPropTitleNew">Acueductos</span></td>
                    <td><input type="text" name="recursos_acuaductos" id="recursos_acuaductos"></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Otros Recursos de Agua</td>
                    <td><label>
                      <input type="text" name="recursos_otros" id="recursos_otros">
                    </label></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" class='viewPropTitleNew'><strong>Cercas</strong></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Longitud</td>
                    <td><label>
                      <input type="text" name="cercas_longitud" id="cercas_longitud">
                    </label></td>
                    <td align="right" class='viewPropTitleNew'>Estantes de</td>
                    <td><label>
                      <input type="text" name="cercas_estantes" id="cercas_estantes">
                    </label></td>
                  </tr>
                  <tr>
                    <td align="right" class='viewPropTitleNew'>Material</td>
                    <td><label>
                      <input type="text" name="cercas_material" id="cercas_material">
                    </label></td>
                    <td align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="left" class='viewPropTitleNew'>Vías Inferiores (Longitud y especificaciones)</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="left"><label>
                      <textarea name="vias_interiores" cols="132" rows="3" id="vias_interiores"></textarea>
                    </label></td>
                  </tr>
                  <tr>
                    <td colspan="4" align="left" class='viewPropTitleNew'>Otras Bienhechurias (En resumen. El detalle de los edificios se anotara en la HOJA DE TRABAJO Nº 1, y el de las instalaciones fijas en la HOJA DE TRABAJO Nº 3)</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="left"><label>
                      <textarea name="otras_bienhechurias" cols="132" rows="3" id="otras_bienhechurias"></textarea>
                    </label></td>
                  </tr>
                  
                </table>
          </div>
                
                
                
                
                <!-- TABLA DE DATOS SERVICIOS-->
        
        	
            	<!-- <div id="divLinderos" style="display:none; width:70%; height:530px; overflow:auto; border:#000000 1px solid" align="center">-->
                <div id="divLinderos" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:380px; width:95%; margin-left:-47%; margin-top:0px; overflow:auto">
                    <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
                     
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Linderos</strong></td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="linceros" cols="132" rows="3" id="linceros"></textarea>
                        </label></td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Estudio Legal de la Propiedad </strong>(Será llenado por el procurador del estado por el Síndico - Procurador Municipal, según el caso)</td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="estudio_legal" cols="132" rows="3" id="estudio_legal"></textarea>
                        </label></td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Valor con que figura en la Contabilidad:</strong></td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td width="165">
                          <input name="contabilidad_fecha" type="text" id="contabilidad_fecha" size="12" readonly/>
                          <img src="imagenes/jscalendar0.gif" name="f_valor_contabilidad" width="16" height="16" id="f_valor_contabilidad" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "contabilidad_fecha",
							button        : "f_valor_contabilidad",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                       </td>
                        <td width="171" align="right" class='viewPropTitleNew'>Valor de Adquisici&oacute;n Bs:</td>
                        <td width="190">
                          <input 
                              name="contabilidad_valor" type="text" 
                              id="contabilidad_valor" 
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
                          <input name="adicionales_fecha" type="text" id="adicionales_fecha" size="12" readonly/>
                          <img src="imagenes/jscalendar0.gif" name="f_mejora" width="16" height="16" id="f_mejora" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "adicionales_fecha",
							button        : "f_mejora",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                          <input 
                          		name="adicionales_valor" type="text" 
                                id="adicionales_valor" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras'), sumaMejoras()" 
                                value="0"/>
                          <input type="hidden" name="valor_mejoras" id="valor_mejoras" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="adicionales_fecha2" type="text" id="adicionales_fecha2" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora2" width="16" height="16" id="f_mejora2" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "adicionales_fecha2",
							button        : "f_mejora2",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="adicionales_valor2" type="text" 
                                id="adicionales_valor2" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras2'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras2" id="valor_mejoras2" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="adicionales_fecha3" type="text" id="adicionales_fecha3" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora3" width="16" height="16" id="f_mejora3" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "adicionales_fecha3",
							button        : "f_mejora3",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="adicionales_valor3" type="text" 
                                id="adicionales_valor3" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras3'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras3" id="valor_mejoras3" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="adicionales_fecha4" type="text" id="adicionales_fecha4" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora4" width="16" height="16" id="f_mejora4" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "adicionales_fecha4",
							button        : "f_mejora4",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="adicionales_valor4" type="text" 
                                id="adicionales_valor4" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras4'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras4" id="valor_mejoras4" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
                        <td>
                        <input name="adicionales_fecha5" type="text" id="adicionales_fecha5" size="12" readonly/>
                        <img src="imagenes/jscalendar0.gif" name="f_mejora5" width="16" height="16" id="f_mejora5" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "adicionales_fecha5",
							button        : "f_mejora5",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>                        </td>
                        <td align="right" class='viewPropTitleNew'>Valor Bs.</td>
                        <td>
                        <input 
                        		name="adicionales_valor5" type="text" 
                                id="adicionales_valor5" 
                                style="text-align:right"
                                onBlur="formatoNumero(this.name, 'valor_mejoras5'), sumaMejoras()" 
                                value="0"/>
                        <input type="hidden" name="valor_mejoras5" id="valor_mejoras5" value="0"/>                        </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" class='viewPropTitleNew'><strong>Total ... Bs.</strong></td>
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
                        <td colspan="4" class='viewPropTitleNew'><strong>Avaluó Provisional de la Comisión </strong>(Para la construcción y el área de terreno ocupada por la misma)</td>
                      </tr>
                      <tr>
                        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                          <tr>
                            <td width="25%" align="center">
                              <input type="text" name="avaluo_hectarias" id="avaluo_hectarias" style="text-align:right" value="0">                            </td>
                            <td width="18%">Hectareas a Bs c/u</td>
                            <td width="26%" align="center">
                              <input type="text" 
                              			name="avaluo_bs_mostrado" 
                                        id="avaluo_bs_mostrado" 
                                        style="text-align:right" 
                                        onBlur="formatoNumero(this.name, 'avaluo_bs'), sumarAvaluo()" value="0">
                              <input type="hidden" name="avaluo_bs" id="avaluo_bs" value="0">                            </td>
                            <td width="5%">Bs.</td>
                            <td width="26%" align="center">
                              <input type="text" name="total_avaluo_mostrado" id="total_avaluo_mostrado" style="text-align:right" disabled value="0">                              <input type="hidden" name="total_avaluo" id="total_avaluo" value="0">
                              </td>
                          </tr>
                          <tr>
                            <td align="center">
                              <input type="text" name="avaluo_hectarias2" id="avaluo_hectarias2" style="text-align:right" value="0">                            </td>
                            <td>Hectareas a Bs c/u</td>
                            <td align="center">
                              <input type="text" 
                              			name="avaluo_bs2_mostrado" 
                                        id="avaluo_bs2_mostrado" 
                                        style="text-align:right" 
                                        onBlur="formatoNumero(this.name, 'avaluo_bs2'), sumarAvaluo()" value="0">
                              <input type="hidden" name="avaluo_bs2" id="avaluo_bs2" value="0">                            </td>
                            <td>Bs.</td>
                            <td align="center">
                              <input type="text" name="total_avaluo2_mostrado" id="total_avaluo2_mostrado" style="text-align:right" disabled value="0">
                              <input type="hidden" name="total_avaluo2" id="total_avaluo2" value="0">
                              </td>
                          </tr>
                          <tr>
                            <td align="center"><input type="text" name="avaluo_hectarias3" id="avaluo_hectarias3" style="text-align:right" value="0"></td>
                            <td>Hectareas a Bs c/u</td>
                            <td align="center"><input type="text" 
                              			name="avaluo_bs3_mostrado" 
                                        id="avaluo_bs3_mostrado" 
                                        style="text-align:right" 
                                        onBlur="formatoNumero(this.name, 'avaluo_bs3'), sumarAvaluo()"value="0">
                              <input type="hidden" name="avaluo_bs3" id="avaluo_bs3" value="0"></td>
                            <td>Bs.</td>
                            <td align="center">
                         <input type="text" name="total_avaluo3_mostrado" id="total_avaluo3_mostrado" style="text-align:right" disabled value="0">
                         <input type="hidden" name="total_avaluo3" id="total_avaluo3" value="0">
                            </td>
                          </tr>
                          <tr>
                            <td align="center">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="2" align="right">Total:&nbsp;</td>
                            <td align="center"><label>
                              <input type="text" name="total_avaluo_general_mostrado" id="total_avaluo_general_mostrado" disabled style="text-align:right" value="0">
                              <input type="hidden" name="total_avaluo_general" id="total_avaluo_general">
                            </label></td>
                          </tr>
                        </table>                        </td>
                      </tr>
                      <tr>
                        <td colspan="4" class='viewPropTitleNew'><strong>Planos, Esquemas y Fotografías: </strong>(Los que se acompañen, con mención de la Oficina en donde se encuentren los restantes)</td>
                      </tr>
                      <tr>
                        <td colspan="4"><label>
                          <textarea name="planos_esquemas_fotografias" cols="132" rows="3" id="planos_esquemas_fotografias"></textarea>
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
                        <td align="right" class='viewPropTitleNew'>Fecha</td>
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
						</script>                        </td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4"></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
          </div>
                
                
                
            
            
            
            
        </td>
    </tr>
</table>

</body>
</html>






