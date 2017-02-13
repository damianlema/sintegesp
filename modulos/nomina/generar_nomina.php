<script src="modulos/nomina/js/generar_nomina_ajax.js"></script>
	<? /*
  <br>
	<h4 align=center>Generar Nomina</h4>
	<h2 class="sqlmVersion"></h2>
  */ ?>
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
<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align="right">
		<div align="center">
			<img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/listar_generar_nomina.php','','resizable = no, scrollbars = yes, width = 900, height = 600')">
    	</div>
      </td>
      <td align="right">
		<div align="center">
        	<img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href = 'principal.php?accion=946&modulo=13'">
    	</div>
      </td>
      <td align="right">
    	<div align="center" id="celdaImprimir" style="display:none">
   	 		<img src="imagenes/imprimir.png" style="cursor:pointer;" id="btImprimir" title="Imprimir" onclick="document.getElementById('divOpciones').style.display='block';">
    	</div>
    	
    </td>
  </tr>
</table>
    <div id="divOpciones" style="display:none; position:absolute; z-index:9; background-color:#CCCCCC; border:1px solid; width:25%; left:40%">
        <div align="right">
            <a href="#" onClick="document.getElementById('divOpciones').style.display='none';">X</a>
        </div>
        <table>
        	<tr><td align="center"><span style="font-weight:bold;">Opciones de Reporte </span></td></tr>
        	<tr><td><input type="radio" name="opciones" id="payroll" checked="checked" /> Payroll de Pago</td></tr>
        	<tr><td><input type="radio" name="opciones" id="nomina" checked="checked" /> Relaci&oacute;n de N&oacute;mina</td></tr>
        	<tr><td><input type="radio" name="opciones" id="concepto" /> Resumen de Conceptos</td></tr>
        	<tr><td><input type="radio" name="opciones" id="compromiso" /> Certificaci&oacute;n de Compromisos</td></tr>
          <tr><td><input type="radio" name="opciones" id="compromiso_aporte" /> Certificaci&oacute;n de Compromiso APORTES</td></tr>
        	<tr><td><input type="radio" name="opciones" id="sobregiro" /> Partidas Sobregiradas</td></tr>
            <tr><td><input type="radio" name="opciones" id="listado" /> Listado con cuenta</td></tr>
        	<tr><td align="center"><span style="font-weight:bold;">Seleccione la Salida </span></td></tr>
        	<tr>
            	<td>
                    <input type="radio" name="tipo_reporte" id="optPDF" checked="checked" onclick="document.getElementById('archivo').disabled=true; document.getElementById('archivo').value='';" /> PDF
                    <input type="radio" name="tipo_reporte" id="optXLS" onclick="document.getElementById('archivo').disabled=false;" /> 
                    Excel <input type="text" id="archivo" size="10" disabled="disabled" />
				</td>
			</tr>
        	<tr><td align="center"><input type="button" value="Procesar" onclick="mostrarReporte();" /></td></tr>
        </table>
    </div>
    
    <div id="divImprimir" style="display:none; position:absolute; z-index:10; background-color:#CCCCCC; border:1px solid;">
    <table align="center">
        <tr><td align="right"><a href="javascript:;" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
        <tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
    </table>
    </div>
</div>
<script>
	function procesarNomina(){
		if(confirm("Realmente desea Procesar esta Nomina?")){
			generarNomina('Procesado');
			
			
		}
	}
	</script>
    
<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:86%; margin-left:-540px;margin-top:5px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos de la N&oacute;mina</strong></td>
    </tr>
</table>
</div>      
    
<input type="hidden" id="idgenerar_nomina" name="idgenerar_nomina">

<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:278px; width:86%; margin-left:-540px; margin-top:25px; overflow:auto">
<table width="100%" border="0" align="center">
  <tr>
    <td width="15%" align="right" class='viewPropTitleNew'>Tipo de N&oacute;mina: </td>
    <td colspan="3">
      <?
  		$sql_consultar_tipo_nomina = mysql_query("select * from tipo_nomina");
  		?>
		  <select name="idtipo_nomina" id="idtipo_nomina">
  			<option value="0">.:: Seleccione ::.</option>
  			<?
  			while($bus_consultar_tipo_nomina = mysql_fetch_array($sql_consultar_tipo_nomina)){
  				?>
    			<option value="<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>" onClick="seleccionarPeriodo('<?=$bus_consultar_tipo_nomina["idtipo_nomina"]?>')"><?=$bus_consultar_tipo_nomina["titulo_nomina"]?></option>
    			<?	
  			}
  			?>
      </select>
    </td>
  </tr>
   
  <tr>
    <td align="right" class='viewPropTitleNew'>Periodo: </td>
    <td id="celda_periodo" colspan="3">
    
    <label>
      <select name="idperiodo" id="idperiodo">
      <option value="<?=$bus_tipos_nomina["idtipo_nomina"]?>">.:: Seleccione un tipo de Nomina ::.</option>
      </select>
    </label></td>
    
  </tr>
  
  
  <tr>
    <td valign="top" align="right" class='viewPropTitleNew'>Concepto: </td>
    <td colspan="3"><label>
      <textarea name="justificacion" cols="115" rows="2" id="justificacion"></textarea>
    </label></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitleNew'>Beneficiario:</td>
    <td colspan="3" id="celda_fecha_elaboracion2" style="font-weight:bold"><table width="50%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="100" disabled="disabled"/>
          <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
          <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" /></td>
        <td><img style="display:block; cursor:pointer"
                                        src="imagenes/search0.png" 
                                        title="Buscar Nuevo Proveedor" 
                                        id="buscarProveedor" 
                                        name="buscarProveedor" 
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=compromisos_rrhh','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')"/></td>
      </tr>
    </table></td>
  </tr>
  <tr>
  <td colspan="4">
    		<input type="hidden" id="id_ordinal" />
            <table width="100%" id="datosExtra" style="display:block">
                <tr>
                    <td align="right" class='viewPropTitleNew' width="22%">Fuente Financiamiento: </td>
                    <td colspan="2">
                        <select name="fuente_financiamiento" id="fuente_financiamiento">
                          <option>.:: Seleccione ::.</option>
                          <?php
                                    $sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento 
                                                                where status='a'"
                                                                    ,$conexion_db);
                                        while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) 
                                            { 
                                                ?>
                          <option onclick="document.getElementById('cofinanciamiento').value = 'no'" <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"'; 
                                                                    if ($rowfuente_financiamiento["idfuente_financiamiento"]==$idfuente_financiamiento_fijo) {echo ' selected';}?>> <?php echo $rowfuente_financiamiento["denominacion"];?> </option>
                          <?php
                                            }
                                    
                                    $sql_cofinanciamiento = mysql_query("select * from cofinanciamiento");
                                    while($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)){
                                        ?>
                                        <option onclick="document.getElementById('cofinanciamiento').value = 'si'" value="<?=$bus_cofinanciamiento["idcofinanciamiento"]?>"><?=$bus_cofinanciamiento["denominacion"]?></option>
                                        <?
                                    }
                                    ?>
                                    
                        </select>
                        <input type="hidden" id="cofinanciamiento" name="cofinanciamiento" value="">
                    </td>
                    <td align="right" class='viewPropTitleNew'>Tipo de Presupuesto: </td>
                    <td align="right" ><select name="tipo_presupuesto" id="tipo_presupuesto">
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
                     <td align="right" class='viewPropTitleNew'>A&ntilde;o: </td>
                     <td >
                      <select name="anio" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
                      </select>
                  </td>
              </tr>
          </table>
       </td>       
    </tr>
  <tr>
    <td width="15%" align="right" class='viewPropTitleNew'>Centro de Costo:</td>
    <td colspan="3" id="celda_centro_costo_fijo" style="font-weight:bold"><table width="50%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><input name="nombre_categoria" type="text" id="nombre_categoria" size="100" disabled="disabled" value="Seleccione el centro de costo SOLO si va a centralizar el pago de la nomina en una sola Categoria Programatica"/>
          <input type="hidden" name="idcentro_costo_fijo" id="idcentro_costo_fijo" /></td>
          <td><img style="display:block; cursor:pointer"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=generar_nomina','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 />
          </td>
          <td>
            <img style="display:block; cursor:pointer"
                                                src="imagenes/nuevo.png" 
                                                title="Borrar Categoria Programatica" 
                                                id="borrarCategoriaProgramatica" 
                                                name="borrarCategoriaProgramatica"
                                                onclick="document.getElementById('nombre_categoria').value='Seleccione el centro de costo SOLO si va a centralizar el pago de la nomina en una sola Categoria Programatica';
                                                        document.getElementById('idcentro_costo_fijo').value='';" 
                                                 />
          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="15%" align="right" class='viewPropTitleNew'>Estado</td>
    <td colspan="2" style="border:1px solid #999; background-color:#FFF;font-weight:bold"  id="celda_estado">En Elaboraci&oacute;n</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew'>Fecha de Elaboraci&oacute;n: </td>
    <td style="font-weight:bold" id="celda_fecha_elaboracion">Aun no Generada</td>
    <td align="right" class='viewPropTitleNew'>Fecha de Proceso: </td>
    <td id="celda_fecha_procesado" style="font-weight:bold">Aun No Generada</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitleNew' id="titulo_numero_certificacion">&nbsp;</td>
    <td id="numero_certificacion" style="font-weight:bold">&nbsp;</td>
    <td align="right" class='viewPropTitleNew' id="titulo_numero_certificacion_aporte">&nbsp;</td>
    <td id="numero_certificacion_aporte" style="font-weight:bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center">
    
    <table border="0">
      <tr>
        <td><input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" class="button" onClick="ingresarDatosBasicos()" style="display:block"></td>
        <td><input type="submit" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onclick="modificarNomina()"></td>
        <td><input type="submit" name="boton_prenomina" id="boton_prenomina" value="Pre Nomina" class="button" style="display:none" onclick="generarNomina('Pre Nomina')"></td>
        <td><input type="submit" name="boton_anular" id="boton_anular" value="Anular" class="button" style="display:none" onclick="anularNomina()"></td>
        <td><input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" class="button" style="display:none" onClick="procesarNomina()"></td>
        <td><input type="submit" name="boton_procesar_certificacion" id="boton_procesar_certificacion" value="Procesar Certificacion" class="button" style="display:none" onClick="procesarCertificacion('N')"></td>
        <td><input type="submit" name="boton_procesar_certificacion_aporte" id="boton_procesar_certificacion_aporte" value="Procesar Certificacion APORTE" class="button" style="display:none" onClick="procesarCertificacion('A')"></td>
        <td><input type="submit" name="boton_nueva_certificacion" id="boton_nueva_certificacion" value="Generar Nueva Certificacion" class="button" style="display:none" onClick="nuevaCertificacion()"></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</div>
<input type="hidden" value="1" id="pestana_seleccionada" name="pestana_seleccionada">
<input type="hidden" id="idorden_compra_servicio" name="idorden_compra_servicio">
<input type="hidden" id="idorden_compra_servicio_aporte" name="idorden_compra_servicio_aporte">
<input type="hidden" name="error_conceptos" id="error_conceptos">

<div id="divTablaProveedores" style="display:block; position:absolute; left:50%; width:86%; margin-left:-540px; height:100px; height:auto !important; min-height:100px; margin-top:310px; overflow:auto">


<table width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
<td align="left">

<table width="80%" border="0" id="tabla_pestanas" cellpadding="3">
      <tr>
        <td align="center" bgcolor="#EAEAEA" id="tdtrabajador" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>TRABAJADORES</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdconceptos" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>CONCEPTOS</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdpartidas"onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>PARTIDAS</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdaportes" onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>APORTES PATRONALES</strong></td>
        <td align="center" bgcolor="#FFFFCC" id="tdpartidasaportes"onMouseOver="cambiarColor(this.id, '#EAEAEA')" onMouseOut="cambiarColor(this.id, '#FFFFCC')" style="cursor:pointer" onClick="seleccionarPestana(this.id)"><strong>PARTIDAS APORTES</strong></td>
      </tr>
</table>

</td>
</tr>
<tr>
<td>


<table width="100%" style="border:#000 solid 1px;"align="center" id="tabla_listas">
    <tr>
        <td>
            <div id="listaTrabajadores" style="text-align:center"> Sin Trabajadores Cargados</div>
            <div id="listaConceptos" style="display:none;text-align:center"> Sin Conceptos Cargados </div>
            <div id="listaPartidas" style="display:none; text-align:center"> Sin partidas Cargadas</div>
            <div id="listaAportes" style="display:none; text-align:center"> Sin Aportes Cargados</div>
            <div id="listaPartidasAportes" style="display:none; text-align:center"> Sin partidas de Aportes Cargadas</div>
        </td>
    </tr>
</table>


</td>
</tr>
</table>
</div>

