<?php 
include "../../../funciones/funciones.php";
?>
<script src="lib/reversa_compromiso/js/reversa_compromiso_ajax.js" type="text/javascript" language="javascript"></script>

    <br>
<h4 align=center>
<?
echo "Reversar Compromisos";
?>
	
 		<td align="right" class='viewPropTitleNew'>A&ntilde;o</td>
        <td >
          <select name="anio" id="anio" disabled="disabled">
                        <?
            anio_fiscal();
            ?>
          </select>
      </td>
  </tr>

<?

$sql_configuracion=mysql_query("select * from configuracion 
											where status='a'"
												,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

$anio_fijo=$registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo=$registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo=$registro_configuracion["idfuente_financiamiento"];

?>
<input type="hidden" name="tipo_carga_orden" id="tipo_carga_orden">
<input type="hidden" id="id_orden_compra" name="id_orden_compra">
</h4>
	<h2 class="sqlmVersion"></h2>

<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="javascript:;" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>
    
    
  <!-- TABLA DE DATOS BASICOS-->
<div id="tablaDatosBasicos">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
<tr>
  <td colspan="6" align="right"><div align="center">
  <img src="imagenes/search0.png" title="Buscar Compromiso" style="cursor:pointer" onclick="window.open('lib/listas/listar_reversa_compromiso.php?destino=ordenes','buscar orden compra servicio','resisable = no, scrollbars = yes, width=900, height = 500')" /> 
  <img src="imagenes/nuevo.png" title="Ingresar nueva Solicitud de Cotizacion" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" /></div></td>
  </tr>
   <tr>
          <td colspan="6">&nbsp;</td>
   </tr>
<tr>
        <td align="right" class='viewPropTitle'>Nro. de Orden:</td>
        <td width="179"><strong>Aun No Generado</strong>
        &nbsp;</td>
      <td width="157" align="right" class='viewPropTitle'>Fecha Orden:</td>
      <td width="115" align="right" >&nbsp;</td>
      <td width="185" align="right" class='viewPropTitle'>&nbsp;Fecha de Elaboraci&oacute;n:</td>
      <td width="220"><strong><?=date("d-m-Y")?></strong></td>
    </tr> 
    <tr>
      <td width="173" align="right" class='viewPropTitle'>Tipo de Orden:</td>
    <td>
    <?
	$sql_tipos_documentos = mysql_query("select * from tipos_documentos 
													where 
													compromete = 'si' and 
													causa = 'no' and 
													paga = 'no' and 
													modulo like '%-".$_SESSION["modulo"]."-%' and 
													multi_categoria = 'no' and 
													reversa_compromiso = 'si'");
	?>
    <select name="tipo_orden" id="tipo_orden">
    <?
    while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
	?>
		<option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
	<?
	}
	?>
        </select>     
      </td>
    <td align="right" class='viewPropTitle'>Estado:</td>
    <td><strong>&nbsp;En Elaboraci&oacute;n</strong></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td align="right" class='viewPropTitle'>Categor&iacute;a Program&aacute;tica:</td>
      <td colspan="5">
        
        
        <table width="664" border="0" align="left" cellpadding="0" cellspacing="0">
        	<tr>
            <td>
            <?
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
                <input type="text" name="nombre_categoria" id="nombre_categoria" size="120" readonly="readonly" value="<?=$bus_configuracion_categoria["denominacion"]?>"/>
                <input type="hidden" name="id_categoria_programatica" id="id_categoria_programatica" value="<?=$bus_configuracion_categoria["idcategoria_programatica"]?>"/>
            </td>
            <td align="left"><img style="display:block; cursor:pointer"
                                                src="imagenes/search0.png" 
                                                title="Buscar Categoria Programatica" 
                                                id="buscarCategoriaProgramatica" 
                                                name="buscarCategoriaProgramatica"
                                                onclick="window.open('lib/listas/lista_categorias_programaticas.php?destino=orden_compra','listar Categorias programaticas','resizable = no, scrollbars=yes, width=900, height = 500')" 
                                                 /></td>
            <td width="216" align="right">
            <a href="#" onClick="abrirCerrarDatosExtra()" id="textoContraerDatosExtra"><img border="0" src="imagenes/comments.png" title="Datos Extra" style="text-decoration:none"></a>            </td>
           </tr>
        </table>       </td>
    </tr>
    
    
    
    
    
  <tr>
  <td colspan="6">  
    
    
  <table width="100%" id="datosExtra" style="display:none">  
    <tr>
        <td width="136" align="right" class='viewPropTitle'>Fuente de Financiamiento</td>
        <td width="135">
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
      <td width="66">
      <select name="anio" id="anio">
        <option value="2008" <?php if ("2008"==$anio_fijo) { echo ' selected';}?>>2008</option>
        <option value="2009" <?php if ("2009"==$anio_fijo) { echo ' selected';}?>>2009</option>
        <option value="2010" <?php if ("2010"==$anio_fijo) { echo ' selected';}?>>2010</option>
        <option value="2011" <?php if ("2011"==$anio_fijo) { echo ' selected';}?>>2011</option>
        <option value="2012" <?php if ("2012"==$anio_fijo) { echo ' selected';}?>>2012</option>
        <option value="2013" <?php if ("2013"==$anio_fijo) { echo ' selected';}?>>2013</option>
        <option value="2014" <?php if ("2014"==$anio_fijo) { echo ' selected';}?>>2014</option>
        <option value="2015" <?php if ("2015"==$anio_fijo) { echo ' selected';}?>>2015</option>
        <option value="2016" <?php if ("2016"==$anio_fijo) { echo ' selected';}?>>2016</option>
      </select></td>
      <td width="63" align="right"></td>
      <td width="277"><table align="left" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
              <input type="hidden" name="descripcion_ordinal" id="descripcion_ordinal" size="30" readonly="readonly" value=""/>
              <input type="hidden" name="id_ordinal" id="id_ordinal" value="" />          </td>
          <td><img style="display:none"
                                        src="imagenes/search0.png" 
                                        title="Buscar Ordinal" 
                                        id="buscarOrdinal" 
                                        name="buscarOrdinal"
                                        onclick="window.open('lib/listas/lista_ordinal.php?destino=orden_compra','','resizable = no, scrollbars=yes, width=600, height=400')" 
                                         /> </td>
        </tr>
      </table></td>
</tr>
 </table>    </td>
    </tr>
    
    
    
    
    
    
    
    
    
    
    
    <tr>
      <td align="right" class='viewPropTitle'>Justificaci&oacute;n:</td>
      <td colspan="5"><textarea name="justificacion" cols="140" rows="4" id="justificacion"></textarea>&nbsp;&nbsp;<a href="#" onClick="abrirCerrarObservaciones()" id="textoContraerObservaciones"><img border="0" src="imagenes/comments.png" title="Observaciones" style="text-decoration:none"></a></td>
    </tr>
    
    <tr>
    	<td colspan="6">
    		<table id="divObservaciones" style="display:none" width="100%">
                <tr>
                    <td align="right" class='viewPropTitle'>Observaciones:</td>
                    <td width="95%" colspan="7"><textarea name="observaciones" cols="130" rows="2" id="observaciones"></textarea></td>
                </tr>
            </table>    	</td>
    </tr>
    </table>
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td width="16%" align="right" class='viewPropTitle'>Ordenado Por:</td>
      <td colspan="3"><?
      $sql_configuracion = mysql_query("select * from configuracion");
	  $bus_configuracion = mysql_fetch_array($sql_configuracion);
	  
	  
	  if($_SESSION["modulo"] == "2"){
	  	$campo_buscar = $bus_configuracion["ordena_presupuesto"];
		$ci_campo_buscar = $bus_configuracion["ci_ordena_presupuesto"];
	  }else if($_SESSION["modulo"] == "3"){
	  	$campo_buscar = $bus_configuracion["ordena_compras"];
		$ci_campo_buscar = $bus_configuracion["ci_ordena_compras"];
	  }else if($_SESSION["modulo"] == "4"){
	  	$campo_buscar = $bus_configuracion["ordena_certificacion_administracion"];
		$ci_campo_buscar = $bus_configuracion["ci_ordena_certificacion_administracion"];
	  }else if($_SESSION["modulo"] == "12"){
	  	$campo_buscar = $bus_configuracion["ordena_despacho"];
		$ci_campo_buscar = $bus_configuracion["ci_ordena_despacho"];
	  }else if($_SESSION["modulo"] == "1"){
	  	$campo_buscar = $bus_configuracion["ordena_rrhh"];
		$ci_campo_buscar = $bus_configuracion["ci_ordena_rrhh"];
	  }else if($_SESSION["modulo"] == "14"){
	  	$campo_buscar = $bus_configuracion["ordena_secretaria"];
		$ci_campo_buscar = $bus_configuracion["ci_ordena_secretaria"];
	  }
	  
	  ?>
        <select name="ordenado_por" id="ordenado_por">
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_administracion"]){
			  			echo "selected";
						}?> 
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
            <?=$bus_configuracion_administracion["primero_administracion"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_administracion"]){
			  			echo "selected";
						}?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
            <?=$bus_configuracion_administracion["segundo_administracion"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_administracion"]){
			  			echo "selected";
						}?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               	onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
            <?=$bus_configuracion_administracion["tercero_administracion"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_compras")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_compras"]){
			  			echo "selected";
						}?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
            <?=$bus_configuracion_administracion["primero_compras"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_compras"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
            <?=$bus_configuracion_administracion["segundo_compras"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_compras"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
            <?=$bus_configuracion_administracion["tercero_compras"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_rrhh"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
            <?=$bus_configuracion_administracion["primero_rrhh"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_rrhh"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
            <?=$bus_configuracion_administracion["segundo_rrhh"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_rrhh"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
            <?=$bus_configuracion_administracion["tercero_rrhh"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_contabilidad"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_priemro_contabilidad"]?>'">
            <?=$bus_configuracion_administracion["primero_contabilidad"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_contabilidad"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
            <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_contabilidad"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
            <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_presupuesto"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
            <?=$bus_configuracion_administracion["primero_presupuesto"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_presupuesto"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
            <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_presupuesto"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
            <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_tesoreria"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
            <?=$bus_configuracion_administracion["primero_tesoreria"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_tesoreria"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
            <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_tesoreria"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
            <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_tributos"]){
			  			echo "selected";

						}?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
            <?=$bus_configuracion_administracion["primero_tributos"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_tributos"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
            <?=$bus_configuracion_administracion["segundo_tributos"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_tributos"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
            <?=$bus_configuracion_administracion["tercero_tributos"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_despacho"]){
			  			echo "selected";

						}?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_despacho"]?>'">
            <?=$bus_configuracion_administracion["primero_despacho"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_despacho"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
            <?=$bus_configuracion_administracion["segundo_despacho"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_despacho"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
            <?=$bus_configuracion_administracion["tercero_despacho"]?>
          </option>
          <?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_secretaria")or die(mysql_error());
			  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
			  ?>
          <option <? if($campo_buscar == $bus_configuracion_administracion["primero_secretaria"]){
			  			echo "selected";

						}?>
              id="<?=$bus_configuracion_administracion["primero_secretaria"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_secretaria"]?>'">
            <?=$bus_configuracion_administracion["primero_secretaria"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["segundo_secretaria"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["segundo_secretaria"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_secretaria"]?>'">
            <?=$bus_configuracion_administracion["segundo_secretaria"]?>
          </option>
          <option <? if($campo_buscar == $bus_configuracion_administracion["tercero_secretaria"]){
			  			echo "selected";
						}?>
              id="<?=$bus_configuracion_administracion["tercero_secretaria"]?>"
			  onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_secretaria"]?>'">
            <?=$bus_configuracion_administracion["tercero_secretaria"]?>
          </option>
        </select></td>
      <td width="12%" align="right" class='viewPropTitle'> C&eacute;dula Ordenado:</td>
      <td colspan="3"><input type="text" name="cedula_ordenado" id="cedula_ordenado" value="<?=$ci_campo_buscar?>"/></td>
    </tr>
    <tr>
      <? if ($_SESSION["modulo"] <> '1') {?>
      <td align="right" class='viewPropTitle'>N&uacute;mero de Requisici&oacute;n:</td>
      <? }else{ ?>
      <td width="17%" align="right" class='viewPropTitle'>N&uacute;mero de Memorandum:</td>
      <? } ?>
      <td colspan="2"><input type="text" name="numero_requisicion" id="numero_requisicion" /></td>
      <td colspan="2" align="right" class='viewPropTitle'>Fecha de Requisici&oacute;n:</td>
      <td colspan="2"><input type="text" name="fecha_requisicion" id="fecha_requisicion" size="13" readonly="readonly"/>
        <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_requisicion",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
      
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Proveedor:</td>
      <td colspan="7"><table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="120"  readonly="readonly"/>
            <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
            <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" /></td>
          <td><img style="display:block; cursor:pointer"
                                        src="imagenes/search0.png" 
                                        title="Buscar Nuevo Proveedor" 
                                        id="buscarProveedor" 
                                        name="buscarProveedor" 
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=ordenes','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="8">
      
      
      
      
     <table width="80%">
      <tr>
      <td colspan="2" align="right" class='viewPropTitle'><label>Nro. Factura</label></td>
      <td width="22%"><input type="text" name="nro_factura" id="nro_factura" /></td>
      <td  width="13%" align="right" class='viewPropTitle'><label>Nro. Control</label></td>
      <td width="16%"><input type="text" name="nro_control" id="nro_control" /></td>
      <td width="13%" align="right" class="viewPropTitle">Fecha Factura</td>
      <td  width="13%"><input type="text" name="fecha_factura" id="fecha_factura" size="13" readonly="readonly"/>
        <img src="imagenes/jscalendar0.gif" name="f_trigger_d" width="16" height="16" id="f_trigger_d" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_factura",
							button        : "f_trigger_d",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
      </tr>
      </table>
      
      
      
      
      
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8" id="vistaDeBotones"><table align="center">
        <tr>
          <td><input type="button" 
                            name="botonSiguiente" 
                            id="botonSiguiente" 
                            value="Siguiente >" 
                            style="display:block" 
                            onclick="ingresarDatosBasicos(document.getElementById('tipo_orden').value, document.getElementById('id_categoria_programatica').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('numero_requisicion').value, document.getElementById('fecha_requisicion').value, document.getElementById('id_beneficiarios').value, document.getElementById('nro_factura').value, document.getElementById('fecha_factura').value, document.getElementById('nro_control').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('tipo_carga_orden').value)" 
                            class="button" /></td>
        </tr>
      </table>
        <br /></td>
    </tr>
  </table>
</div>
  
  <!-- TABLA DE DATOS BASICOS-->
 
 <input type="hidden" name="solicitudes" id="solicitudes">
 
  
  <!-- TABLA DE PROVEEDORES-->
	<table align="center" cellpadding="0" cellspacing="0" id="tablaProveedor" style="display:none; width:800px" width="800">
    <tr>
      <td align="left" class='viewPropTitle'><strong>PROCESO:
        <select name="proceso" id="proceso">
        <option value="0">.:: Seleccione ::.</option>
          <option onclick="document.getElementById('listaSolicitudesProveedor').style.display = 'none';
          					document.getElementById('tipo_carga_orden').value = 'directo';
                            actualizarTipoCargaOrden(document.getElementById('id_orden_compra').value, 'directo')" 
                                            value="directo">Directo</option>
          <option onclick="consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, document.getElementById('id_orden_compra').value),
          					document.getElementById('tipo_carga_orden').value = 'cotizacion';
                            actualizarTipoCargaOrden(document.getElementById('id_orden_compra').value, 'cotizacion')"
                                            value="cotizacion">Desde Consulta de Precios</option>
          <option onclick="consultarRequisicion(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, document.getElementById('id_orden_compra').value),
          document.getElementById('tipo_carga_orden').value = 'requisicion';
                            actualizarTipoCargaOrden(document.getElementById('id_orden_compra').value, 'requisicion')"
                                            value="requisicion">Desde Requisicion</option>
        </select>
      </strong></td>
      <td align="right" class='viewPropTitle'>
        <a href="javascript:;" onClick="abrirCerrarProveedores()" id="textoContraerProveedores">
        <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">        </a>      </td>
    </tr>
    <tr>
       <td colspan="2" width="800">     
          <table width="800" align="center" id="formularioProveedores" style=" display:block" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="20%" align="left" valign="top" class='viewPropTitle'>
              

              <div id="listaSolicitudesProveedor" style="background-color:#CCCCCC; 
                                                                border:#000000 1px solid; 
                                                                display:none; 
                                                                width:180px; 
                                                                height:200px; 
                                                                overflow:auto;
                                                                cursor:pointer"></div>
              
              
              
              
              
              
              
              
              </td>
            <td width="80%" valign="top" class='viewPropTitle'>
              <div id="solicitudesSeleccionada" style="width:100%">
              <center>No hay Solicitudes Seleccionadas</center>
              </div>
              
             </td>
            </tr>
      </table>      </td>
   </tr>
</table>
  <!-- PROVEEDORES-->

<br>

<!-- MATERIALES-->
<table width="800" align="center" style="display:none" id="tablaMaterialesPartidas">
        <tr>
        <td colspan="2">
        <table align="center" width="90%">
			<tr>
				<td bgcolor="#e7dfce" width="4%"></td>
                	<td width="26%" align="left"><font size="1"><strong >Disponibilidad Presupuestaria</strong></font></td>
                <td bgcolor="#FFFF00" width="4%"></td>
                	<td width="26%" align="left"><font size="1"><strong >Sin Disponibilidad Presupuestaria</strong></font></td>
                <td bgcolor="#FF0000" width="4%"></td>
                	<td width="26%" align="left"><font size="1"><strong >Sin Partida Presupuestaria</strong></font></td>
            </tr>
		</table>
        </td>
        </tr>
        <tr>
          <td align="left" class='viewPropTitle'><strong>MATERIALES</strong></td>
          <td align="right" class='viewPropTitle'>
          	<span id="totales">
            <strong>Exento:</strong> 0,00 |
            <strong>Sub Total:</strong> 0,00 | 
            <strong>Impuesto:</strong> 0,00 | 
            <strong>Total Bsf:</strong> 0,00
            </span >
            <a href="javascript:;" onClick="abrirCerrarMateriales()" id="textoContraerMateriales">
            <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
 		</tr>
        <tr>
          <td colspan="2" align="right">
            
            
            
            
            
            <form name="formularioIngresarMateriales" onsubmit="return ingresarMaterialIndividual(document.getElementById('id_orden_compra').value, document.getElementById('id_material').value, document.getElementById('cantidad').value, document.getElementById('precio_unitario').value, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)">
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
            
            
            
            
            
            
          </td>
  		</tr>
        <tr>
          <td colspan="2" align="center">
          	<div id="divMateriales" style="display:block">
          		No hay Materiles Asociados
          	</div>
          </td>
        </tr>
       <!-- PROVEEDORES-->
<!-- PARTIDAS-->
         <tr>
          <td align="left" class='viewPropTitle'><strong>PARTIDAS</strong></td>
          <td align="right" class='viewPropTitle'>
          	<span id="totalPartidas"><strong>Total Bsf: </strong>0,00</span>
            &nbsp;
            <a href="javascript:;" onClick="abrirCerrarPartidas()" id="textoContraerPartidas">
            	<img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
          	<div id="divPartidas" style="display:block">
          		No hay Partidas Asociadas
          	</div>
          </td>
        </tr>
        <tr>
          <td width="131">&nbsp;</td>
          <td width="657">&nbsp;</td>
  </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
      <!-- PARTIDAS-->
</table>

