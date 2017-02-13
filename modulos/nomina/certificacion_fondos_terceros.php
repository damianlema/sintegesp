<script src="modulos/nomina/js/certificacion_fondos_terceros_ajax.js"></script>
	<br>
	<h4 align=center>Generar Certificación de Fondos a Terceros</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<br>


<input type="hidden" name="idorden_compra" id="idorden_compra">

<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="javascript:;" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<table align="center" >
    <tr>
    	<td>
        <img src="imagenes/search0.png" style="cursor:pointer" onclick="window.open('lib/listas/listar_ordenes_compra.php?t=fondos_t','','resizable = no, scrollbars = yes, width = 900, height = 600')" />
        </td>
        <td>
        <img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href = 'principal.php?accion=<?=$_GET["accion"]?>&modulo=<?=$_GET["modulo"]?>'">
        </td>
        <td>
        <img src="imagenes/imprimir.png" title="Imprimir Certificacion"  onclick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=certificacion_compromiso_rrhh&id_orden_compra='+document.getElementById('idorden_compra').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
        </td>
  </tr>
</table>

<table width="80%" border="0" align="center">
  <tr>
    <td align="right" class='viewPropTitle'>Numero de Orden</td>
    <td id="nro_orden" style="font-weight:bold">Aun no Generado</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Estado</td>
    <td id="estado" style="font-weight:bold">Elaboracion</td>
  </tr>
  
  <tr>
    <td width="22%" align="right" class='viewPropTitle'>Tipo de Documento</td>
    <td width="78%">
    <?
    $sql_tipo_documento = mysql_query("select * from tipos_documentos 
													where 
													((compromete='si' and causa='no' and paga='no') 
													or (compromete='no' and causa='no' and paga='no'))
													and modulo like '-".$_SESSION["modulo"]."-'
													and fondos_terceros = 'si'")or die(mysql_error());
	?>
    <select id="tipo_documento" name="tipo_documento">
    <?
    while($bus_tipo_documento = mysql_fetch_array($sql_tipo_documento)){
		?>
		<option value="<?=$bus_tipo_documento["idtipos_documentos"]?>"><?=$bus_tipo_documento["descripcion"]?></option>
		<?
	}
	?>
    </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Tipo de Nomina</td>
    <td>
    
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
      </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Periodo</td>
    <td id="celda_periodo">
    <select name="idperiodo" id="idperiodo">
      <option value="<?=$bus_tipos_nomina["idtipo_nomina"]?>">.:: Seleccione un tipo de Nomina ::.</option>
      </select>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Categoria Programatica</td>
    <td><table border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td>
            <input type="text" name="nombre_categoria" id="nombre_categoria" size="80" disabled="disabled"/>
            <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica"/>        </td>
        <td align="left"><img style="display:block"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 /></td>
        <td width="216" align="right">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Concepto</td>
    <td>
        <input name="id_concepto_constante" type="hidden" id="id_concepto_constante"/>
    <input name="tabla" type="hidden" id="tabla"/>
    <label>
      <input name="concepto_constante" type="text" id="concepto_constante" size="60" disabled/>
    <img src="imagenes/search0.png" width="16" height="16" style="cursor:pointer" onclick="window.open('lib/listas/listar_conceptos_constantes.php','','resizable=no, scrollbars=yes, width =900, height = 600')"/></label>    </td>
  </tr>
  <tr>
    <td valign="top" align="right" class='viewPropTitle'>Beneficiario</td>
    <td>
    <table width="50%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="70" disabled="disabled"/>
          <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
          <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" /></td>
        <td><img style="display:block; cursor:pointer"
                                        src="imagenes/search0.png" 
                                        title="Buscar Nuevo Proveedor" 
                                        id="buscarProveedor" 
                                        name="buscarProveedor" 
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=compromisos_rrhh','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')"/></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td valign="top" align="right" class='viewPropTitle'>Justificacion</td>
    <td><textarea name="justificacion" cols="80" rows="5" id="justificacion"></textarea></td>
  </tr>
  <tr>
    <td valign="top" align="right" class='viewPropTitle'>Total a Pagar:</td>
    <td id="total" style="font-weight:bold">0,00</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" name="boton_siguiente" id="boton_siguiente" value="Siguiente" onclick="crearCertificacion()" class="button">
      <input type="submit" name="boton_procesar" id="boton_procesar" value="Procesar" onclick="procesarCertificacion()" style="display:none" class="button">
      <input type="submit" name="boton_anular" id="boton_anular" value="Anular" onclick="anularCertificacion()" style="display:none" class="button">
    </td>
  </tr>
</table>
<br />
<br />
<div id="listaConceptos" align="center"></div>
<br />
<br />
<div id="listaPartidas" align="center"></div>
