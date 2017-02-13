<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();

extract($_POST);

if($ejecutar == "ingresarRetencion"){
	if($origen == "numero_propio"){
		$numero_documento = $contador;
		$asociado = 0;
	}else{
		$numero_documento = 0;
		$asociado = $documentoAsociado;
	}
	if($tipo_monto == "monto_fijo"){
		$monto_fijo = $monto_fijo;
		$porcentaje = 0;
		$divisor = 0;
	}else{
		$monto_fijo = 0;
		$porcentaje = $porcentaje;
		$divisor = $divisor;	
	}
	
	$sql_insertar = mysql_query("insert into tipo_retencion(codigo,
															descripcion,
															monto_fijo,
															porcentaje,
															divisor,
															base_calculo,
															unidad_tributaria,
															factor_calculo,
															numero_documento,
															asociado,
															articulo,
															numeral,
															literal,
															nombre_comprobante,
															status,
															usuario,
															fechayhora,
															idcuenta_debe,
															tabla_debe,
															idcuenta_haber,
															tabla_haber)values('".$codigo."',
																			'".$descripcion."',
																			'".$monto_fijo."',
																			'".$porcentaje."',
																			'".$divisor."',
																			'".$base_calculo."',
																			'".$unidad_tributaria."',
																			'".$factor_calculo."',
																			'".$numero_documento."',
																			'".$asociado."',
																			'".$articulo."',
																			'".$numeral."',
																			'".$literal."',
																			'".$nombre_comprobante."',
																			'a',
																			'".$login."',
																			'".$fh."',
																			'".$idcuenta_deudora."',
																			'".$tabla_deudora."',
																			'".$idcuenta_acreedora."',
																			'".$tabla_acreedora."')")or die(mysql_error());
																				
																			
registra_transaccion("Ingresar Tipos de Retencion (".mysql_insert_id().")",$login,$fh,$pc,'tipo_retencion');

$sql_consulta = mysql_query("select * from tipo_retencion");

?>

<table width="60%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="50%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  <thead>
								<tr>
									<td align="center" class="Browse">Codigo</td>
									<td align="center" class="Browse">Descipcion</td>
                                    <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=$bus_consulta["descripcion"]?></td>
                              <td class='Browse' align="center"><img src="imagenes/modificar.png" onClick="consultarDatosGenerales(<?=$bus_consulta["idtipo_retencion"]?>, 'modificar')"></td>
                                <td class='Browse' align="center"><img src="imagenes/delete.png" onClick="consultarDatosGenerales(<?=$bus_consulta["idtipo_retencion"]?>, 'eliminar')"></td>
                          </tr>
							<?
							}
							?>
							
								
						</table>
                        
					
      </td>
        </tr>
    </table>

<?
}













if($ejecutar == "modificarRetencion"){
	if($origen == "numero_propio"){
		$numero_documento = $contador;
		$asociado = 0;
	}else{
		$numero_documento = 0;
		$asociado = $documentoAsociado;
	}
	if($tipo_monto == "monto_fijo"){
		$monto_fijo = $monto_fijo;
		$porcentaje = 0;
		$divisor = 0;
	}else{
		$monto_fijo = 0;
		$porcentaje = $porcentaje;
		$divisor = $divisor;	
	}

	
	$sql_insertar = mysql_query("update tipo_retencion set codigo = '".$codigo."',
															descripcion = '".$descripcion."',
															monto_fijo = '".$monto_fijo."',
															porcentaje = '".$porcentaje."',
															divisor = '".$divisor."',
															base_calculo = '".$base_calculo."',
															unidad_tributaria = '".$unidad_tributaria."',
															factor_calculo = '".$factor_calculo."',
															numero_documento = '".$numero_documento."',
															asociado = '".$asociado."',
															articulo = '".$articulo."',
															numeral = '".$numeral."',
															literal = '".$literal."',
															nombre_comprobante = '".$nombre_comprobante."',
															status = 'a',
															usuario = '".$login."',
															fechayhora = '".$fh."',
															idcuenta_debe = '".$idcuenta_deudora."',
															tabla_debe = '".$tabla_deudora."',
															idcuenta_haber = '".$idcuenta_acreedora."',
															tabla_haber = '".$tabla_acreedora."' where idtipo_retencion = '".$idtipo_retencion."'") or die(mysql_error());
																																				
registra_transaccion("Modificar Tipos de Retencion (".$idtipo_retencion.")",$login,$fh,$pc,'tipos_documentos');
?>
<?
$sql_consulta = mysql_query("select * from tipo_retencion");

?>

<table width="60%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="50%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  <thead>
								<tr>
									<td align="center" class="Browse">Codigo</td>
									<td align="center" class="Browse">Descipcion</td>
                                    <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=$bus_consulta["descripcion"]?></td>
                              <td class='Browse' align="center"><img src="imagenes/modificar.png" onClick="consultarDatosGenerales(<?=$bus_consulta["idtipo_retencion"]?>, 'modificar')"></td>
                                <td class='Browse' align="center"><img src="imagenes/delete.png" onClick="consultarDatosGenerales(<?=$bus_consulta["idtipo_retencion"]?>, 'eliminar')"></td>
                          </tr>
							<?
							}
							?>
							
								
						</table>
                        
					
      </td>
        </tr>
    </table>

<?
}





if($ejecutar == "eliminarDocumentos"){
	$sql_validar_eliminar = mysql_query("(select idtipo_retencion from relacion_retenciones where idtipo_retencion = '".$idtipo_retencion."')
											UNION
										(select idtipo_retencion from relacion_retenciones_externas where idtipo_retencion = '".$idtipo_retencion."')") or die("error 			
											validacion".mysql_error());	
																										
	$existen_retenciones = mysql_num_rows($sql_validar_eliminar);
	if ($existen_retenciones > 0){
		echo "existen";
	}else{
		$sql_ingresar_documentos = mysql_query("delete from tipo_retencion where idtipo_retencion = '".$idtipo_retencion."'")or die(mysql_error());registra_transaccion("Eliminar Tipos de Documentos (".$idtipo_retencion.")",$login,$fh,$pc,'tipos_documentos');
	
?>
<?
$sql_consulta = mysql_query("select * from tipo_retencion");

?>

<table width="60%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="50%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  <thead>
								<tr>
									<td align="center" class="Browse">Codigo</td>
									<td align="center" class="Browse">Descipcion</td>
                                    <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                                <td class='Browse' align="left"><?=$bus_consulta["descripcion"]?></td>
                              <td class='Browse' align="center"><img src="imagenes/modificar.png" onClick="consultarDatosGenerales(<?=$bus_consulta["idtipo_retencion"]?>, 'modificar')"></td>
                                <td class='Browse' align="center"><img src="imagenes/delete.png" onClick="consultarDatosGenerales(<?=$bus_consulta["idtipo_retencion"]?>, 'eliminar')"></td>
                          </tr>
							<?
							}
							?>
							
								
						</table>
                        
					
      </td>
        </tr>
    </table>

<?
}
}











if($ejecutar == "consultarDatosGenerales"){
$sql_consulta = mysql_query("select * from tipo_retencion where idtipo_retencion = ".$id_retencion."");
$bus_consulta= mysql_fetch_array($sql_consulta);
?>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
    <td align="right" class='viewPropTitle'>C&oacute;digo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" value="<?=$bus_consulta["codigo"]?>" size="5" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
      <input type="hidden" name="idtipo_retencion" id="idtipo_retencion" value="<?=$bus_consulta["idtipo_retencion"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
    </label></td>
  </tr>
  <tr>
<td width="211" align="right" class='viewPropTitle'><div align="right">Descripci&oacute;n</div></td>
    <td width="333"><label>
      <input name="descripcion" type="text" id="descripcion" size="50" value="<?=$bus_consulta["descripcion"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
</label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Retenci&oacute;n</td>
    <td>
      <input type="radio" name="tipo_monto" value="fijo" id="option_monto_fijo" onClick="document.getElementById('tablaMontoFijo').style.display = 'block', document.getElementById('tablaPorcentaje').style.display = 'none'" <? if($bus_consulta["monto_fijo"] != 0){echo "checked";} if($tipo_consulta == "eliminar"){echo " disabled";}?> >
        Fija
        <input type="radio" name="tipo_monto" value="porcentaje" id="option_porcentaje" onClick="document.getElementById('tablaPorcentaje').style.display = 'block', document.getElementById('tablaMontoFijo').style.display = 'none'" <? if($bus_consulta["porcentaje"] != 0){echo "checked";} if($tipo_consulta == "eliminar"){echo " disabled";}?>>
        Porcentaje   </td>
  </tr>
  <tr>
  <td colspan="2">
  
  
  	<table width="100%" id="tablaPorcentaje" <? if($bus_consulta["porcentaje"] != 0){echo "style='display:block'";}else{echo "style='display:none'";}?>>
    <tr>
            <td width="52%" class='viewPropTitle'> Porcentaje 
              <label>
              <input name="porcentaje" type="text" id="porcentaje" style="text-align:right" value="<?=$bus_consulta["porcentaje"]?>" size="6" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
          </label></td>
          <td width="48%" class='viewPropTitle'> Divisor 
        <label>
              <input name="divisor" type="text" id="divisor" style="text-align:right" value="<?=$bus_consulta["divisor"]?>" size="6" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
              </label>            </td>
        </tr>
    </table>  </td>
  </tr>
  <tr>
  	<td colspan="2">
  	
    
    	<table <? if($bus_consulta["monto_fijo"] != 0){echo "style='display:block'";}else{echo "style='display:none'";}?> id="tablaMontoFijo">
        	<tr>
                <td class='viewPropTitle'>Ingrese el monto Fijo</td>
                <td><label>
                  <input type="text" name="monto_fijo" id="monto_fijo" value="<?=$bus_consulta["monto_fijo"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?> style="text-align:right">
                </label>                </td>
              </tr>
        </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Base del C&aacute;lculo</td>
    <td><label>
      <select name="base_calculo" id="base_calculo" <? if($tipo_consulta == "eliminar"){echo "disabled";}?>>
        <option <? if($bus_consulta["base_calculo"] == "Exento"){echo "selected";}?> value="Exento">Exento</option>
        <option <? if($bus_consulta["base_calculo"] == "Base Imponible"){echo "selected";}?> value="Base Imponible">Base Imponible</option>
        <option <? if($bus_consulta["base_calculo"] == "IVA"){echo "selected";}?> value="IVA">IVA</option>
        <option <? if($bus_consulta["base_calculo"] == "Total"){echo "selected";}?> value="Total">Total</option>
      </select>
    </label></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Unidad Tributaria</td>
    <td><label>
      <input type="text" name="unidad_tributaria" id="unidad_tributaria" value="<?=$bus_consulta["unidad_tributaria"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?> style="text-align:right">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Factor de C&aacute;lculo</td>
    <td><label>
      <input type="text" name="factor_calculo" id="factor_calculo" value="<?=$bus_consulta["factor_calculo"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?> style="text-align:right">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Origen de Numeraci&oacute;n</td>
    <td><label>
      <input type="radio" name="origen" id="origen1" value="numero_propio" onclick="document.getElementById('tablaNroPropio').style.display='block', document.getElementById('tablaAsociado').style.display='none'" <? if($bus_consulta["numero_documento"] != 0){echo "checked";} if($tipo_consulta == "eliminar"){echo " disabled";}?> />
    </label>
Propio&nbsp;
<label>
<input type="radio" name="origen" id="origen2" value="numero_asociado" onclick="document.getElementById('tablaNroPropio').style.display='none', document.getElementById('tablaAsociado').style.display='block'" <? if($bus_consulta["asociado"] != 0){echo "checked";} if($tipo_consulta == "eliminar"){echo " disabled";}?> />
</label>
&nbsp;Asociado</td>
  </tr>
  <tr>
    <td colspan="2" ><label></label>
      <label></label></td>
  </tr>
  <tr>
  	<td colspan="2">
  
  
  <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->

   <table width="100%" align="center"  id="tablaNroPropio" <? if($bus_consulta["numero_documento"] != 0){ echo " style='display:block'"; }else{ echo " style='display:none'"; }?>>
   	<tr>
    	<td width="82%" class='viewPropTitle'><div align="right">N&uacute;mero Comprobante</div></td>
    	<td width="18%"><input name="contador" type="text" id="contador" size="5" maxlength="5" value="<?=$bus_consulta["numero_documento"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>></td>
   	</tr>
   </table>
 
   <table align="center" <? if($bus_consulta["asociado"] != 0){ echo " style='display:block'"; }else{ echo " style='display:none'"; }?> id="tablaAsociado">
   	<tr>
    	<td class='viewPropTitle'>Documento Asociado</td>
    	<td>
        	<?
            $sql_consulta_select = mysql_query("select * from tipo_retencion where numero_documento != 0");
			?>
            <select name="documentoAsociado" id="documentoAsociado" <? if($tipo_consulta == "eliminar"){echo " disabled";}?>>
            	<option value="0">.:: Seleccione ::.</option>
				<?
                while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
				?>
					<option <? if($bus_consulta["asociado"] == $bus_consulta_select["idtipo_retencion"]){echo "selected";}?> value="<?=$bus_consulta_select["idtipo_retencion"]?>"><?=$bus_consulta_select["descripcion"]?></option>
				<?
				}
				?>
            </select>        </td>
   	</tr>
   </table>

   <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->  	</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Art&iacute;culo</td>
    <td><label>
      <input type="text" name="articulo" id="articulo" value="<?=$bus_consulta["articulo"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Numeral</td>
    <td><label>
      <input type="text" name="numeral" id="numeral" value="<?=$bus_consulta["numeral"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Literal</td>
    <td><label>
      <input type="text" name="literal" id="literal" value="<?=$bus_consulta["literal"]?>" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
    </label></td>
  </tr>
  
    <tr>
    <td align="right" class='viewPropTitle'>Nombre Comprobante</td>
    <td><label>
    <select name="nombre_comprobante" id="nombre_comprobante" <? if($tipo_consulta == "eliminar"){ echo "disabled";}?>>
      <option <? if($bus_consulta["nombre_comprobante"] == "IVA"){ echo "selected";}?> value="IVA">IVA</option>
      <option <? if($bus_consulta["nombre_comprobante"] == "ISLR"){ echo "selected";}?> value="ISLR">ISLR</option>
      <option <? if($bus_consulta["nombre_comprobante"] == "1x1000"){ echo "selected";}?> value="1x1000">1x1000 Delta Amacuro</option>
      <option <? if($bus_consulta["nombre_comprobante"] == "1x1000M"){ echo "selected";}?> value="1x1000M">1x1000 Monagas</option>
      <option <? if($bus_consulta["nombre_comprobante"] == "municipal"){ echo "selected";}?> value="municipal">Municipal</option>
       <option <? if($bus_consulta["nombre_comprobante"] == "NA"){ echo "selected";}?> value="NA">Sin Comprobante</option>
    </select>
    </label></td>
  </tr>
   <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
      	<td colspan="2" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
    	</tr>
         <tr>
    <td class="viewPropTitle" align="right">Afecta por el Debe:</td>
      <td>
       <label> 
       <select name="cuenta_deudora" id="cuenta_deudora">
       <option value='0-0' onClick="document.getElementById('tabla_deudora').value = '0', document.getElementById('idcuenta_deudora').value = '0'">..:: Cuenta Contable del Concepto ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
          			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			
			while($bus_cuenta_deudora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_deudora["idcuenta"]?>-<?=$bus_cuenta_deudora["tabla"]?>" <? if($bus_consulta["idcuenta_debe"]==$bus_cuenta_deudora["idcuenta"] and $bus_consulta["tabla_debe"]==$bus_cuenta_deudora["tabla"]) { echo "selected"; }?> onClick="document.getElementById('tabla_deudora').value = '<?=$bus_cuenta_deudora["tabla"]?>', document.getElementById('idcuenta_deudora').value = '<?=$bus_cuenta_deudora["idcuenta"]?>'"><?=$bus_cuenta_deudora["codigo"]?>- <?=utf8_decode($bus_cuenta_deudora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_deudora" type="hidden" id="tabla_deudora" size="100" value="<?=$bus_consulta["tabla_debe"]?>">
      <input name="idcuenta_deudora" type="hidden" id="idcuenta_deudora" size="100" value="<?=$bus_consulta["idcuenta_debe"]?>">
      <input name="idcuenta_deudora_modificar" type="hidden" id="idcuenta_deudora_modificar" size="100">
        </td>
    </tr>
    <tr>
      
      <td class="viewPropTitle" align="right">Afecta por el Haber:</td>
      <td>
       <label> 
       <select name="cuenta_acreedora" id="cuenta_acreedora">
       <option value='0-0' onClick="document.getElementById('tabla_acreedora').value = '0', document.getElementById('idcuenta_acreedora').value = '0'">..:: Cuenta Contable del Concepto ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
         			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			while($bus_cuenta_acreedora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_acreedora["idcuenta"]?>-<?=$bus_cuenta_acreedora["tabla"]?>" <? if($bus_consulta["idcuenta_haber"]==$bus_cuenta_acreedora["idcuenta"] and $bus_consulta["tabla_haber"]==$bus_cuenta_acreedora["tabla"]) { echo "selected"; }?> onClick="document.getElementById('tabla_acreedora').value = '<?=$bus_cuenta_acreedora["tabla"]?>', document.getElementById('idcuenta_acreedora').value = '<?=$bus_cuenta_acreedora["idcuenta"]?>'"><?=$bus_cuenta_acreedora["codigo"]?> - <?=utf8_decode($bus_cuenta_acreedora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_acreedora" type="hidden" id="tabla_acreedora" size="100" value="<?=$bus_consulta["tabla_haber"]?>">
      <input name="idcuenta_acreedora" type="hidden" id="idcuenta_acreedora" size="100" value="<?=$bus_consulta["idcuenta_haber"]?>">
      <input name="idcuenta_acreedora_modificar" type="hidden" id="idcuenta_acreedora_modificar" size="100">
      
        </td>
    </tr>
  
  
  
  
  
  <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    
      <?
      if($tipo_consulta == "modificar"){
    		if(in_array(408, $privilegios) == true){
	
	  ?>
	  <input type="button" name="modificar" id="modificar" value="Modificar" class="button" onClick="modificarRetencion(document.getElementById('codigo').value, document.getElementById('descripcion').value , document.getElementById('monto_fijo').value, document.getElementById('porcentaje').value, document.getElementById('divisor').value, document.getElementById('base_calculo').value, document.getElementById('unidad_tributaria').value, document.getElementById('factor_calculo').value, document.getElementById('contador').value, document.getElementById('documentoAsociado').value, document.getElementById('articulo').value, document.getElementById('numeral').value, document.getElementById('literal').value, document.getElementById('nombre_comprobante').value, document.getElementById('idtipo_retencion').value)">
	  <?
	  		}
	  }else if($tipo_consulta == "eliminar"){
	  		if(in_array(409, $privilegios) == true){
	  ?>
	  <input type="button" name="eliminar" id="eliminar" value="Eliminar" class="button" onClick="eliminarRetencion(document.getElementById('idtipo_retencion').value)">
	  <?
	  		}
	  }
	  ?>	</td>
  </tr>
</table>
<?
}






if($ejecutar == "nuevoRegistro"){
?>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
    <td align="right" class='viewPropTitle'>C&oacute;digo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="5">
    </label></td>
  </tr>
  <tr>
    <td width="208" align="right" class='viewPropTitle'>Descripci&oacute;n</td>
    <td width="336"><label>
      <input name="descripcion" type="text" id="descripcion" size="50">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Retenci&oacute;n</td>
<td>
        <input type="radio" name="tipo_monto" value="fijo" id="option_monto_fijo" onClick="document.getElementById('tablaMontoFijo').style.display = 'block', document.getElementById('tablaPorcentaje').style.display = 'none'">
        Fija
        <input type="radio" name="tipo_monto" value="porcentaje" id="option_porcentaje" onClick="document.getElementById('tablaPorcentaje').style.display = 'block', document.getElementById('tablaMontoFijo').style.display = 'none'">
        Porcentaje   </td>
  </tr>
  <tr>
  <td colspan="2">
  
  
  	<table style="display:none" id="tablaPorcentaje">
        <tr>
            <td class='viewPropTitle'>Porcentaje 
              <label>
              <input type="text" name="porcentaje" id="porcentaje" style="text-align:right">
              </label></td>
            <td class='viewPropTitle'>Divisor 
              <label>
              <input type="text" name="divisor" id="divisor" style="text-align:right">
              </label>            </td>
        </tr>
    </table>  </td>
  </tr>
  <tr>
  	<td colspan="2">
  	
    
    	<table style="display:none" id="tablaMontoFijo">
        	<tr>
                <td class='viewPropTitle'>Ingrese el monto Fijo</td>
                <td><label>
                  <input type="text" name="monto_fijo" id="monto_fijo" style="text-align:right">
                </label>                </td>
              </tr>
        </table>    </td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Base del C&aacute;lculo</td>
    <td><label>
      <select name="base_calculo" id="base_calculo">
        <option value="Exento">Exento</option>
        <option value="Base Imponible">Base Imponible</option>
        <option value="IVA">IVA</option>
        <option value="Total">Total</option>
      </select>
    </label></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Unidad Tributaria</td>
    <td><label>
      <input type="text" name="unidad_tributaria" id="unidad_tributaria" style="text-align:right">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Factor de C&aacute;lculo</td>
    <td><label>
      <input type="text" name="factor_calculo" id="factor_calculo" style="text-align:right">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Origen de Numeraci&oacute;n</td>
    <td><label>
      <input type="radio" name="origen" id="origen1" value="numero_propio" onclick="document.getElementById('tablaNroPropio').style.display='block', document.getElementById('tablaAsociado').style.display='none'" />
    </label>
Propio&nbsp;
<label>
<input type="radio" name="origen" id="origen2" value="numero_asociado" onclick="document.getElementById('tablaNroPropio').style.display='none', document.getElementById('tablaAsociado').style.display='block'" />
</label>
&nbsp;Asociado</td>
  </tr>
  <tr>
    <td colspan="2" ><label></label>
      <label></label></td>
  </tr>
  <tr>
  	<td colspan="2">
  
  
  <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->

   <table align="center" style="display:none" id="tablaNroPropio">
   	<tr>
    	<td class='viewPropTitle'>Nro. Propio</td>
    	<td><input name="contador" type="text" id="contador" size="5" maxlength="5"></td>
   	</tr>
   </table>
 
   <table align="center" style="display:none" id="tablaAsociado">
   	<tr>
    	<td class='viewPropTitle'>Documento Asociado</td>
    	<td>
        	<?
            $sql_consulta_select = mysql_query("select * from tipo_retencion where numero_documento != ''");
			?>
            <select name="documentoAsociado" id="documentoAsociado">
            	<option value="0">.:: Seleccione ::.</option>
				<?
                while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
				?>
					<option value="<?=$bus_consulta_select["idtipo_retencion"]?>"><?=$bus_consulta_select["descripcion"]?></option>
				<?
				}
				?>
            </select>        </td>
   	</tr>
   </table>

   <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->  	</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Art&iacute;culo</td>
    <td><label>
      <input type="text" name="articulo" id="articulo">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Numeral</td>
    <td><label>
      <input type="text" name="numeral" id="numeral">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Literal</td>
    <td><label>
      <input type="text" name="literal" id="literal">
    </label></td>
  </tr>
   <tr>
    <td align="right" class='viewPropTitle'>Nombre Comprobante</td>
    <td><label>
    <select name="nombre_comprobante" id="nombre_comprobante">
      <option value="IVA">IVA</option>
      <option value="ISRL">ISRL</option>
      <option value="1x1000">1x1000</option>
      <option value="NA">N/A</option>
    </select>
    </label></td>
  </tr>
  <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
      	<td colspan="2" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
    	</tr>
         <tr>
    <td class="viewPropTitle" align="right">Afecta por el Debe:</td>
      <td>
       <label> 
       <select name="cuenta_deudora" id="cuenta_deudora">
       <option value='0-0' onClick="document.getElementById('tabla_deudora').value = '0', document.getElementById('idcuenta_deudora').value = '0'">..:: Cuenta Contable del Concepto ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
          			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			
			while($bus_cuenta_deudora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_deudora["idcuenta"]?>-<?=$bus_cuenta_deudora["tabla"]?>" onClick="document.getElementById('tabla_deudora').value = '<?=$bus_cuenta_deudora["tabla"]?>', document.getElementById('idcuenta_deudora').value = '<?=$bus_cuenta_deudora["idcuenta"]?>'"><?=$bus_cuenta_deudora["codigo"]?>- <?=utf8_decode($bus_cuenta_deudora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_deudora" type="hidden" id="tabla_deudora" size="100" value="0">
      <input name="idcuenta_deudora" type="hidden" id="idcuenta_deudora" size="100" value="0">
      <input name="idcuenta_deudora_modificar" type="hidden" id="idcuenta_deudora_modificar" size="100">
        </td>
    </tr>
    <tr>
      
      <td class="viewPropTitle" align="right">Afecta por el Haber:</td>
      <td>
       <label> 
       <select name="cuenta_acreedora" id="cuenta_acreedora">
       <option value='0-0' onClick="document.getElementById('tabla_acreedora').value = '0', document.getElementById('idcuenta_acreedora').value = '0'">..:: Cuenta Contable del Concepto ::..</option>
        <?
       		$sql_consultar = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion_cuentas_contables' AS tabla
					FROM
						  desagregacion_cuentas_contables d
						  INNER JOIN subcuenta_segundo_cuentas_contables sc2 ON (d.idsubcuenta_segundo = sc2.idsubcuenta_segundo_cuentas_contables)
						  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
						  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
						  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
						  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
						  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					)

						UNION

					(SELECT
							  sc2.idsubcuenta_segundo_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo_cuentas_contables' AS tabla
					FROM
							  subcuenta_segundo_cuentas_contables sc2
							  INNER JOIN subcuenta_primer_cuentas_contables sc ON (sc2.idsubcuenta_primer = sc.idsubcuenta_primer_cuentas_contables)
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc2.idsubcuenta_segundo_cuentas_contables not in(select idsubcuenta_segundo from desagregacion_cuentas_contables)

					)
					)
					UNION
					(SELECT
							  sc.idsubcuenta_primer_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer_cuentas_contables' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas_contables' AS tabla
					FROM
							  cuenta_cuentas_contables cc
							  INNER JOIN rubro_cuentas_contables r ON (cc.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					)
         			UNION
					(SELECT
							  r.idrubro_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo) AS codigo,
							  r.denominacion,
							  'rubro_cuentas_contables' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
			while($bus_cuenta_acreedora = mysql_fetch_array($sql_consultar)){?>
				<option value="<?=$bus_cuenta_acreedora["idcuenta"]?>-<?=$bus_cuenta_acreedora["tabla"]?>" onClick="document.getElementById('tabla_acreedora').value = '<?=$bus_cuenta_acreedora["tabla"]?>', document.getElementById('idcuenta_acreedora').value = '<?=$bus_cuenta_acreedora["idcuenta"]?>'"><?=$bus_cuenta_acreedora["codigo"]?> - <?=utf8_decode($bus_cuenta_acreedora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_acreedora" type="hidden" id="tabla_acreedora" size="100" value="0">
      <input name="idcuenta_acreedora" type="hidden" id="idcuenta_acreedora" size="100" value="0">
      <input name="idcuenta_acreedora_modificar" type="hidden" id="idcuenta_acreedora_modificar" size="100">
        </td>
    </tr>
  
  
  <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
    <?
    if(in_array(380, $privilegios) == true){
	?>
      <input type="button" name="enviar" id="enviar" value="Registrar" class="button" 
      		onclick="ingresarRetencion(document.getElementById('codigo').value, document.getElementById('descripcion').value , document.getElementById('monto_fijo').value, document.getElementById('porcentaje').value, document.getElementById('divisor').value, document.getElementById('base_calculo').value, document.getElementById('unidad_tributaria').value, document.getElementById('factor_calculo').value, document.getElementById('contador').value, document.getElementById('documentoAsociado').value, document.getElementById('articulo').value, document.getElementById('numeral').value, document.getElementById('literal').value, document.getElementById('nombre_comprobante').value)">
    </label>
    <?
    }
	?></td>
  </tr>
</table>
<?
}


?>