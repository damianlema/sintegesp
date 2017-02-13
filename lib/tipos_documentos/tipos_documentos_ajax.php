<?
session_start();
include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();

extract($_POST);
extract($_GET);

if($ejecutar == "ingresarDocumentos"){
	$arreglo = explode(",",$modulos_seleccionados);
	$modulos_seleccionados="";
	foreach($arreglo as $arr){
		$modulos_seleccionados .= "-".$arr."-";
	}
	if($origen == "numero_propio"){
		$sql_ingresar_documentos = mysql_query("insert into tipos_documentos(descripcion,
																			siglas,
																			compromete,
																			causa,
																			paga,
																			nro_contador,
																			forma_preimpresa,
																			status,
																			fechayhora,
																			usuario,
																			modulo,
																			documento_compromete,
																			multi_categoria,
																			fondos_terceros,
																			reversa_compromiso,
																			excluir_contabilidad,
																			padre,
																			idcuenta_debe,
																			tabla_debe,
																			idcuenta_haber,
																			tabla_haber)values('".$descripcion."',
																							'".$siglas."',
																							'".$compromete."',
																							'".$causa."',
																							'".$paga."',
																							'".$contador."',
																							'".$forma_preimpresa."',
																							'a',
																							'".$fh."',
																							'".$login."',
																							'".$modulos_seleccionados."',
																							'".$documento_compromete."',
																							'".$multi_categoria."',
																							'".$fondos_terceros."',
																							'".$reversa_compromiso."',
																							'".$excluir_contabilidad."',
																							'".$documento_padre."',
																							'".$idcuenta_deudora."',
																							'".$tabla_deudora."',
																							'".$idcuenta_acreedora."',
																							'".$tabla_acreedora."')")or die(mysql_error());
	}else{
		$sql_ingresar_documentos = mysql_query("insert into tipos_documentos(descripcion,
																			siglas,
																			compromete,
																			causa,
																			paga,
																			documento_asociado,
																			forma_preimpresa,
																			status,
																			fechayhora,
																			usuario,
																			modulo,
																			documento_compromete,
																			multi_categoria,
																			fondos_terceros,
																			reversa_compromiso,
																			excluir_contabilidad,
																			padre,
																			idcuenta_debe,
																			tabla_debe,
																			idcuenta_haber,
																			tabla_haber)values('".$descripcion."',
																							'".$siglas."',
																							'".$compromete."',
																							'".$causa."',
																							'".$paga."',
																							'".$documentoAsociado."',
																							'".$forma_preimpresa."',
																							'a',
																							'".$fh."',
																							'".$login."',
																							'".$modulos_seleccionados."',
																							'".$documento_compromete."',
																							'".$multi_categoria."',
																							'".$fondos_terceros."',
																							'".$reversa_compromiso."',
																							'".$excluir_contabilidad."',
																							'no',
																							'".$idcuenta_deudora."',
																							'".$tabla_deudora."',
																							'".$idcuenta_acreedora."',
																							'".$tabla_acreedora."')")or die(mysql_error());
	}
registra_transaccion("Ingresar Tipos de Documentos (".mysql_insert_id().")",$login,$fh,$pc,'tipos_documentos');

?>
<?
	
	$sql_consulta = mysql_query("select * from tipos_documentos order by descripcion ASC");


?>
<table width="80%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
<thead>
								<tr>
									<td width="66%" align="center" class="Browse">Descripcion</td>
								  <td width="17%" align="center" class="Browse">Siglas</td>
                                  <td align="center" class="Browse" colspan="3">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["descripcion"]?></td>
                                <td align="center" class='Browse'><?=$bus_consulta["siglas"]?></td>
                              <td width="6%" align="center" class='Browse'><img src="imagenes/modificar.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'modificar', <?=$modulo?>)" style="cursor:pointer"></td>
                              <td width="5%" align="center" class='Browse'><img src="imagenes/delete.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'eliminar', <?=$modulo?>)" style="cursor:pointer"></td>
                            
                            
                            
                          <td width="6%" align="center" class='Browse'>
                                <img src="imagenes/ver.png"
                                								onclick="mostrarNumerosLibres('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>', '<?=$bus_consulta["idtipos_documentos"]?>', 'contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>')"
                                								style="cursor:pointer"
                                                                title="Ver Numeros Disponibles">
                                
       <div id="listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>" style="display:none; position:absolute; background-color: #FFFFCC; border:#000000 1px solid; width:200px" name="listaNumeros">
                                     <div align="right" style="width:100%; background-color:#CCCCCC">
                                         <a href="javascript:;" onclick="document.getElementById('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>').style.display = 'none'">
                                             <strong>Lista de Numeros Libres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</strong>
                                         </a>
                                     </div>
                                     <div style="width:100%; height:600; overflow:auto" align="left" id="contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>">
                                    	<!-- AQUI VA EL CONTENIDO DE LA LISTA -->
                                        
                                     </div>
                                 </div>
                                
                                </td>
                            
                            
                            
                            
                            
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













if($ejecutar == "modificarDocumentos"){
	$arreglo = explode(",",$modulos_seleccionados);
	$modulos_seleccionados="";
	foreach($arreglo as $arr){
		$modulos_seleccionados .= "-".$arr."-";
	}
	
	if($origen == "numero_propio"){
																	
		$sql_ingresar_documentos = mysql_query("update tipos_documentos set descripcion = '".$descripcion."',
																			siglas = '".$siglas."',
																			compromete = '".$compromete."',
																			causa = '".$causa."',
																			paga = '".$paga."',
																			nro_contador = '".$contador."',
																			documento_asociado = '0',
																			forma_preimpresa = '".$forma_preimpresa."',
																			status = 'a',
																			fechayhora = '".$fh."',
																			usuario = '".$login."',
																			documento_compromete = '".$documento_compromete."',
																			modulo = '".$modulos_seleccionados."',
																			multi_categoria = '".$multi_categoria."',
																			fondos_terceros = '".$fondos_terceros."',
																			reversa_compromiso = '".$reversa_compromiso."',
																			excluir_contabilidad = '".$excluir_contabilidad."',
																			padre = '".$documento_padre."',
																			idcuenta_debe = '".$idcuenta_deudora."',
																			tabla_debe = '".$tabla_deudora."',
																			idcuenta_haber = '".$idcuenta_acreedora."',
																			tabla_haber = '".$tabla_acreedora."'
																			where idtipos_documentos = '".$idtipos_documentos."'")or die(mysql_error());
	}else{
		$sql_ingresar_documentos = mysql_query("update tipos_documentos set descripcion = '".$descripcion."',
																			siglas = '".$siglas."',
																			compromete = '".$compromete."',
																			causa = '".$causa."',
																			paga = '".$paga."',
																			nro_contador = '0',
																			documento_asociado = '".$documentoAsociado."',
																			forma_preimpresa = '".$forma_preimpresa."',
																			status = 'a',
																			fechayhora = '".$fh."',
																			usuario = '".$login."',
																			documento_compromete = '".$documento_compromete."',
																			modulo = '".$modulos_seleccionados."',
																			multi_categoria = '".$multi_categoria."',
																			fondos_terceros = '".$fondos_terceros."' ,
																			reversa_compromiso = '".$reversa_compromiso."',
																			excluir_contabilidad = '".$excluir_contabilidad."',
																			idcuenta_debe = '".$idcuenta_deudora."',
																			tabla_debe = '".$tabla_deudora."',
																			idcuenta_haber = '".$idcuenta_acreedora."',
																			tabla_haber = '".$tabla_acreedora."'
																			where idtipos_documentos = '".$idtipos_documentos."'")or die(mysql_error());
	}
registra_transaccion("Modificar Tipos de Documentos (".$idtipos_documentos.")",$login,$fh,$pc,'tipos_documentos');
?>
<?
	$sql_consulta = mysql_query("select * from tipos_documentos order by descripcion ASC");

?>
<table width="80%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
<thead>
								<tr>
									<td width="66%" align="center" class="Browse">Descripci&oacute;n</td>
								  <td width="17%" align="center" class="Browse">Siglas</td>
                                  <td align="center" class="Browse" colspan="3">Acci&oacute;n</td>
								</tr>
						  </thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["descripcion"]?></td>
                                <td align="center" class='Browse'><?=$bus_consulta["siglas"]?></td>
                                <td width="6%" align="center" class='Browse'><img src="imagenes/modificar.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'modificar', <?=$modulo?>)" style="cursor:pointer"></td>
                              <td width="5%" align="center" class='Browse'><img src="imagenes/delete.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'eliminar', <?=$modulo?>)" style="cursor:pointer"></td>
                                
                                
                              <td width="6%" align="center" class='Browse'>
                                <img src="imagenes/ver.png"
                                								 onclick="mostrarNumerosLibres('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>', '<?=$bus_consulta["idtipos_documentos"]?>', 'contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>')"
                                								style="cursor:pointer"
                                                                title="Ver Numeros Disponibles">
                                
                       <div id="listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>" style="display:none; position:absolute; background-color: #FFFFCC; border:#000000 1px solid; width:200px" name="listaNumeros">
                                     <div align="right" style="width:100%; background-color:#CCCCCC">
                                         <a href="javascript:;" onclick="document.getElementById('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>').style.display = 'none'">
                                             <strong>Lista de Numeros Libres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</strong>
                                         </a>
                                     </div>
                                     <div style="width:100%; height:600; overflow:auto" align="left" id="contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>">
                                    	<!-- AQUI VA EL CONTENIDO DE LA LISTA -->
                                        
                                     </div>
                                 </div>
                                
                                </td>
                                
                                
                                
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
	$sql_validar_usado = mysql_query("(select tipo from orden_compra_servicio where tipo = '".$idtipos_documentos."')
										UNION
										(select tipo from orden_pago where tipo = '".$idtipos_documentos."')
										UNION
										(select idtipo_documento from pagos_financieros where idtipo_documento = '".$idtipos_documentos."')")or die(mysql_error());
	$existen_tipo = mysql_num_rows($sql_validar_usado)or die(mysql_error());
	if ($existen_tipo > 0){	
		echo "existen";
	}else{
		
		$sql_ingresar_documentos = mysql_query("delete from tipos_documentos where idtipos_documentos = '".$idtipos_documentos."'")or die(mysql_error());registra_transaccion("Eliminar Tipos de Documentos (".$idtipos_documentos.")",$login,$fh,$pc,'tipos_documentos');
		?>
		<?
			$sql_consulta = mysql_query("select * from tipos_documentos order by descripcion ASC");
		
		?>
		<table width="80%" align="center" cellpadding="0" cellspacing="0" class="Main">
		<tr>
							<td align="center">
							
							
								<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
		<thead>
								<tr>
									<td width="66%" align="center" class="Browse">Descripcion</td>
								  <td width="17%" align="center" class="Browse">Siglas</td>
                                  <td align="center" class="Browse" colspan="3">Acci&oacute;n</td>
								</tr>
						  </thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["descripcion"]?></td>
                                <td align="center" class='Browse'><?=$bus_consulta["siglas"]?></td>
                                <td width="5%" align="center" class='Browse'><img src="imagenes/modificar.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'modificar', <?=$modulo?>)" style="cursor:pointer"></td>
                              <td width="6%" align="center" class='Browse'><img src="imagenes/delete.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'eliminar', <?=$modulo?>)" style="cursor:pointer"></td>
                                
                                
                                
                              <td width="6%" align="center" class='Browse'>
                                <img src="imagenes/ver.png"
                                								 onclick="mostrarNumerosLibres('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>', '<?=$bus_consulta["idtipos_documentos"]?>', 'contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>')"
                                								style="cursor:pointer"
                                                                title="Ver Numeros Disponibles">
                                
                       <div id="listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>" style="display:none; position:absolute; background-color: #FFFFCC; border:#000000 1px solid; width:200px" name="listaNumeros">
                                     <div align="right" style="width:100%; background-color:#CCCCCC">
                                         <a href="javascript:;" onclick="document.getElementById('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>').style.display = 'none'">
                                             <strong>Lista de Numeros Libres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</strong>
                                         </a>
                                     </div>
                                     <div style="width:100%; height:600; overflow:auto" align="left" id="contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>">
                                    	<!-- AQUI VA EL CONTENIDO DE LA LISTA -->
                                        
                                     </div>
                                 </div>
                                
                                </td>
                                
                                
                                
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
$sql_consulta = mysql_query("select * from tipos_documentos where idtipos_documentos = ".$id_documento."");
$bus_consulta= mysql_fetch_array($sql_consulta);
?>
<table width="65%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
    <td width="140" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="144"><label>
      <input name="descripcion" type="text" id="descripcion" value="<?=$bus_consulta["descripcion"]?>" size="90" <? if($tipo_consulta == "eliminar"){echo "disabled";}?> onKeyUp="validarVacios('descripcion', this.value, 'form1')" onBlur="validarVacios('descripcion', this.value, 'form1')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
    </label>
    <input type="hidden" id="idtipos_documentos" name="idtipos_documentos" value="<?=$bus_consulta["idtipos_documentos"]?>"></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Siglas</td>
    <td><label>
      <input name="siglas" type="text" id="siglas" size="5" maxlength="5" value="<?=$bus_consulta["siglas"]?>" <? if($tipo_consulta == "eliminar"){echo "disabled";}?> onKeyUp="validarVacios('siglas', this.value, 'form1')" onBlur="validarVacios('siglas', this.value, 'form1')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
    </label></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Acciones</td>
    <td><label>
      <input type="checkbox" name="compromete" id="compromete" value="si" onclick="listaCompromete()" <? if($bus_consulta["compromete"] == "si"){ echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?> />
    </label>
Compromete&nbsp;&nbsp;
<label>
<input type="checkbox" name="causa" id="causa" value="si" onclick="listaCompromete()" <? if($bus_consulta["causa"] == "si"){ echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?> />
</label>
&nbsp;Causa&nbsp;&nbsp;
<label>
<input type="checkbox" name="paga" id="paga" value="si" onclick="listaCompromete()" <? if($bus_consulta["paga"] == "si"){ echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?> />
</label>
&nbsp;Paga </td>
  </tr>
  
  
   <tr>
    <td colspan="2"><label></label>
      <table width="100%" id="documentoCompromete" <? if($bus_consulta["documento_compromete"] != 0){echo "style='display:block'";}else{echo "style='display:none'";}?> >
<tr>
            	<td width="49%" class='viewPropTitle'>Documento que Compromete</td>
    <td width="51%">
                  <?
				 	
           			 $sql_consulta_select = mysql_query("select * from tipos_documentos where (nro_contador != 0 and causa ='no' and compromete = 'si' and paga ='no') || (multi_categoria = 'si')");
					
					?>
<select name="documento_compromete" id="documento_compromete">
            			<option value="0">.:: Seleccione ::.</option>
					<?
               		 while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
					?>
					<option <? if($bus_consulta["documento_compromete"] == $bus_consulta_select["idtipos_documentos"]){echo "selected";}?> value="<?=$bus_consulta_select["idtipos_documentos"]?>"><?=$bus_consulta_select["descripcion"]?></option>
					<?
					}
					?>
            </select>                </td>
        </tr>
      </table>    </td>
  </tr>

  
  
  
  <tr>
    <td align="right" class='viewPropTitle'>Origen de Numeracion</td>
    <td><label>
      <input type="radio" name="origen" id="origen1" value="numero_propio" onclick="document.getElementById('tablaNroPropio').style.display='block', document.getElementById('tablaAsociado').style.display='none'" <? if($bus_consulta["nro_contador"] != 0){ echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?> />
    </label>
Propio&nbsp;
<label>
<input type="radio" name="origen" id="origen2" value="numero_asociado" onclick="document.getElementById('tablaNroPropio').style.display='none', document.getElementById('tablaAsociado').style.display='block'" <? if($bus_consulta["documento_asociado"] != 0){ echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?> />
</label>
&nbsp;Asociado</td>
  </tr>
  
  <tr>
  	<td colspan="2">
  
  
  <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->

   <table width="100%" align="center" id="tablaNroPropio" <? if($bus_consulta["nro_contador"] != 0){ echo "style='display:block'";}else{echo "style='display:none'";}?>>
   	<tr>
    	<td width="40%" class='viewPropTitle'><div align="right">N&uacute;mero del Documento</div></td>
    	<td width="10%"><input name="contador" type="text" id="contador" size="5" maxlength="5" <? if($bus_consulta["nro_contador"] != 0){ echo "value='".$bus_consulta["nro_contador"]."'";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?>></td>
        <td width="40%" class='viewPropTitle'>Documento Padre</td>
        <td width="10%"><input type="checkbox" name="documento_padre" id="documento_padre" <? echo "value='".$bus_consulta["padre"]."'"?> <? if($bus_consulta["padre"] == 'si'){ echo "checked";}?><? if($tipo_consulta == "eliminar"){echo "disabled";}?>></td>
   	</tr>
   </table>
 
   <table width="100%" align="center" id="tablaAsociado" <? if($bus_consulta["documento_asociado"] != 0){ echo "style='display:block'";}else{echo "style='display:none'";}?>>
   	<tr>
    	<td width="66%" class='viewPropTitle'><div align="right">Documento Asociado</div></td>
    	<td width="34%">
        	<?
	           $sql_consulta_select = mysql_query("select * from tipos_documentos where nro_contador != 0 and causa ='no' and compromete = 'no' and paga ='no'");
			?>
<select name="documentoAsociado" id="documentoAsociado" <? if($tipo_consulta == "eliminar"){echo "disabled";}?>>
            	<option value="0">.:: Seleccione ::.</option>
				<?
                while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
				?>
					<option <? if($bus_consulta["documento_asociado"] != 0 and $bus_consulta["documento_asociado"] == $bus_consulta_select["idtipos_documentos"]){echo "selected";}?> value="<?=$bus_consulta_select["idtipos_documentos"]?>"><?=$bus_consulta_select["descripcion"]?></option>
				<?
				}
				?>
            </select>        </td>
   	</tr>
   </table>

   <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->  	</td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Forma Pre-impresa</td>
    <td><input type="checkbox" name="forma_preimpresa" id="forma_preimpresa" value="si" <? if($bus_consulta["forma_preimpresa"] == "si"){echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?>/></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Multi Categoria</td>
    <td><input type="checkbox" name="multi_categoria" id="multi_categoria" value="si" <? if($bus_consulta["multi_categoria"] == "si"){echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?>/></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Fondos a Terceros</td>
    <td><input type="checkbox" name="fondos_terceros" id="fondos_terceros" value="si" <? if($bus_consulta["fondos_terceros"] == "si"){echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?>/></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle' >Reversa Compromiso</td>
    <td><label>
      <input type="checkbox" name="reversa_compromiso" id="reversa_compromiso" <? if($bus_consulta["reversa_compromiso"] == "si"){echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?>/>
    </label></td>
  </tr>
   <tr>
    <td align="right" class='viewPropTitle' >Excluir de contabilidad</td>
    <td><label>
      <input type="checkbox" name="excluir_contabilidad" id="excluir_contabilidad" <? if($bus_consulta["excluir_contabilidad"] == "si"){echo "checked";}?> <? if($tipo_consulta == "eliminar"){echo "disabled";}?>/>
    </label></td>
  </tr>
  <tr>
      	<td colspan="2" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
    	</tr>
         <tr>
    <td class="viewPropTitle" align="right">Afecta por el Debe:</td>
      <td>
       <label> 
       <select name="cuenta_deudora" id="cuenta_deudora">
       <option value='0-0' onClick="document.getElementById('tabla_deudora').value = '0', document.getElementById('idcuenta_deudora').value = '0'">..:: Cuenta Contable del Concepto o de la Cuenta Bancaria ::..</option>
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
       <option value='0-0' onClick="document.getElementById('tabla_acreedora').value = '0', document.getElementById('idcuenta_acreedora').value = '0'">..:: Cuenta Contable del Concepto o de la Cuenta Bancaria ::..</option>
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
  
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
      	<td colspan="2" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>UTILIZADO POR LOS MODULOS</strong></td>
    	</tr>
  <tr>
  <tr>
    <td colspan="2" align="center">
    
    
    
    	<table cellpadding="4">
        	<tr>
            	<td align="center" class='viewPropTitle'><strong>Modulos Existentes</strong></td>
                <td align="center" class='viewPropTitle'><strong>Modulos Seleccionados</strong></td>
            </tr>
            <tr>
            	<td>
                <select id="modulos" name="modulos" multiple="multiple" size="10" style="cursor:pointer; width:140px">
                	<?
					$sql_modulos = mysql_query("select * from modulo where mostrar = 'si'");
					$i = 0;
					$partes_modulo = explode("-",$bus_consulta["modulo"]);
					while($bus_modulos = mysql_fetch_array($sql_modulos)){
						if(in_array($bus_modulos["id_modulo"], $partes_modulo) == false){
						?>
							<option onclick="seleccionarModulos()" value="<?=$bus_modulos["id_modulo"]?>">
								<?=$bus_modulos["nombre_modulo"]?>
                            </option>
						<?
						}
						$i++;
					}
					?>
                </select>                </td>
           
                <td>
                <?
                $arreglo_modulos = explode("-", $bus_consulta["modulo"]);				
				?>
                    <select id="modulos2" name="modulos2" multiple="multiple" size="10" style="cursor:pointer; width:140px">
                    	<?
					$sql_modulos = mysql_query("select * from modulo");
					$i = 0;
					$partes_modulo = explode("-",$bus_consulta["modulo"]);
					while($bus_modulos = mysql_fetch_array($sql_modulos)){
						if(in_array($bus_modulos["id_modulo"], $partes_modulo) == true){
						?>
							<option onclick="deseleccionarModulos()" value="<?=$bus_modulos["id_modulo"]?>">
								<?=$bus_modulos["nombre_modulo"]?>
                            </option>
						<?
						}
						$i++;
					}
					?>
                    </select>                </td>
            </tr>
        </table>    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <div align="center">

        <input type="button" name="modificar" id="modificar" value="Modificar" class="button" onclick="modificarDocumentos(document.getElementById('descripcion').value, 
        			document.getElementById('siglas').value, 
                    document.getElementById('contador').value,
                    document.getElementById('documentoAsociado').value, 
                    document.getElementById('idtipos_documentos').value, 
					<?=$modulo?>, 
                    document.getElementById('documento_compromete').value, 
                    document.getElementById('forma_preimpresa').value,
                    document.getElementById('multi_categoria').value,
                    document.getElementById('modulos2').length)">

        <input type="button" name="eliminar" id="eliminar" value="Eliminar" class="button" onclick="eliminarDocumentos(document.getElementById('idtipos_documentos').value, <?=$modulo?>)">
      </div>
    </label></td>
  </tr>
</table>
<?
}






if($ejecutar == "nuevoRegistro"){
?>
<table width="65%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
    <td width="140" align="right" class='viewPropTitle'>Descripcion</td>
    <td width="144"><label>
      <input name="descripcion" type="text" id="descripcion" size="90" onKeyUp="validarVacios('descripcion', this.value, 'form1')" onBlur="validarVacios('descripcion', this.value, 'form1')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
    </label></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Siglas</td>
    <td><label>
      <input name="siglas" type="text" id="siglas" size="5" maxlength="5" onKeyUp="validarVacios('siglas', this.value, 'form1')" onBlur="validarVacios('siglas', this.value, 'form1')" autocomplete="OFF" style="padding:0px 20px 0px 0px;">
    </label></td>
  </tr>
  
  <tr>
    <td align="right" class='viewPropTitle'>Acciones</td>
    <td><label>
      <input type="checkbox" name="compromete" id="compromete" value="si" onclick="listaCompromete()" />
    </label>
Compromete&nbsp;&nbsp;
<label>
<input type="checkbox" name="causa" id="causa" value="si" onclick="listaCompromete()" />
</label>
&nbsp;Causa&nbsp;&nbsp;
<label>
<input type="checkbox" name="paga" id="paga" value="si" onclick="listaCompromete()" />
</label>
&nbsp;Paga </td>
  </tr>
  
  
  
  
  
 <tr>
    <td colspan="2"><label></label>


		<table id="documentoCompromete" style="display:none">
        	<tr>
            	<td class='viewPropTitle'>Documento que Compromete</td>
                <td>
                  <?
           			 $sql_consulta_select = mysql_query("select * from tipos_documentos where nro_contador != 0 and causa ='no' and compromete = 'si' and paga ='no'");
					?>
           		  <select name="documento_compromete" id="documento_compromete">
            			<option value="0">.:: Seleccione ::.</option>
					<?
               		 while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
					?>
					<option value="<?=$bus_consulta_select["idtipos_documentos"]?>"><?=$bus_consulta_select["descripcion"]?></option>
					<?
					}
					?>
            </select>                </td>
            </tr>
        </table>    </td>
  </tr>
  
  
  
  
  
  
  
  
  <tr>
    <td align="right" class='viewPropTitle'>Origen de Numeracion</td>
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
				$sql_consulta_select = mysql_query("select * from tipos_documentos where nro_contador != 0 and causa ='no' and compromete = 'no' and paga ='no'");

            
			?>
            <select name="documentoAsociado" id="documentoAsociado">
            	<option value="0">.:: Seleccione ::.</option>
				<?
                while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
				?>
					<option value="<?=$bus_consulta_select["idtipos_documentos"]?>"><?=$bus_consulta_select["descripcion"]?></option>
				<?
				}
				?>
            </select>        </td>
   	</tr>
   </table>

   <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->  	</td>
  </tr>
    <tr>
    <td align="right" class='viewPropTitle'>Forma Pre-impresa</td>
    <td><input type="checkbox" name="forma_preimpresa" id="forma_preimpresa" value="si"/></td>
  </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Multi Catgeoria</td>
      <td><input type="checkbox" name="multi_categoria" id="multi_categoria" value="si"/></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Fondos a Terceros</td>
      <td><input type="checkbox" name="fondos_terceros" id="fondos_terceros" value="si"/></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitle'>Reversa Compromiso</td>
      <td><label>
        <input type="checkbox" name="reversa_compromiso" id="reversa_compromiso" value="si"/>
      </label></td>
    </tr>
    <tr>
    <td align="right" class='viewPropTitle'>Excluir de contabilidad</td>
    <td><label>
      <input type="checkbox" name="excluir_contabilidad" id="excluir_contabilidad" value="si"/>
    </label></td>
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
    <td colspan="2" align="center">
    
    <table cellpadding="10">
        	<tr>
            	<td align="center" class='viewPropTitle'>Modulos Existentes</td>
                <td align="center" class='viewPropTitle'>Modulos Seleccionados</td>
            </tr>
            <tr>
            	<td>
				<?
                $sql_modulos = mysql_query("select * from modulo where mostrar = 'si'"); 
				?>
                <select id="modulos" name="modulos" multiple="multiple" size="10" style="cursor:pointer; width:140px">
                	<?
                    while($bus_modulos = mysql_fetch_array($sql_modulos)){
						?>
							<option onclick="seleccionarModulos()" value="<?=$bus_modulos["id_modulo"]?>"><?=$bus_modulos["nombre_modulo"]?></option>
						<?
					}
					?>
                </select>                </td>
           
                <td>
                    <select id="modulos2" name="modulos2" multiple="multiple" size="10" style="cursor:pointer; width:140px"></select>                </td>
            </tr>
        </table>    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><label>
      <input type="button" name="enviar" id="enviar" value="Registrar" class="button" 
      		onclick="ingresarDocumentos(document.getElementById('descripcion').value, 
            							document.getElementById('siglas').value, document.getElementById('contador').value,
                                        document.getElementById('documentoAsociado').value,
                                        '<?=$modulo?>', 
                                        document.getElementById('documento_compromete').value, 
                                        document.getElementById('forma_preimpresa').value,
                                        document.getElementById('multi_categoria').value,
                                        document.getElementById('modulos2').length)">
    </label></td>
  </tr>
</table>
<?
}




if($ejecutar == "mostrarNumerosLibres"){
										
	$sql_validar = mysql_query("select * from tipos_documentos where idtipos_documentos = '".$idtipos_documentos."' and modulo like '%-3-%'");
	$num_validar = mysql_num_rows($sql_validar);
	
	if($num_validar > 0){
	
	
	$sql_ordenes = mysql_query("select * from orden_compra_servicio where tipo = '".$idtipos_documentos."' and numero_orden != '' order by idorden_compra_servicio desc")or die(mysql_error());
	$bus_ordenes = mysql_fetch_array($sql_ordenes);
	$partes = explode("-",$bus_ordenes["numero_orden"]);
	$ultimo_numero = $partes[2];
		for($i=1;$i < $ultimo_numero; $i++){
			$sql_consultar_ordenes = mysql_query("select * from orden_compra_servicio where tipo = '".$idtipos_documentos."' and numero_orden like '%-".$i."%'");
			$num_consultar_ordenes = mysql_num_rows($sql_consultar_ordenes);
			if($num_consultar_ordenes == 0){
				if($i == 1){
					echo "Numero: ".$i."<br />";
					$prox = $i+1;
					$sql_consultar_proximo = mysql_query("select * from orden_compra_servicio where tipo = '".$idtipos_documentos."' and numero_orden like '%-".$prox."%'");
					$num_consultar_proximo = mysql_num_rows($sql_consultar_proximo);
						if($num_consultar_proximo != 0){
							$bus_consultar_proximo = mysql_fetch_array($sql_consultar_proximo);
							list($a, $m, $d)=SPLIT( '[/.-]', $bus_consultar_proximo["fecha_orden"]);
							$fecha=$d."-".$m."-".$a;
							echo "<strong>
									Numero: ".$prox." | ".$fecha."
								  </strong>
								  <br />";
						}
				}else{
					$ante = $i-1;
					$sql_consultar_anterior = mysql_query("select * from orden_compra_servicio where tipo = '".$idtipos_documentos."' and numero_orden like '%-".$ante."%'");
					$num_consultar_anterior = mysql_num_rows($sql_consultar_anterior);
						if($num_consultar_anterior != 0){
							$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
							list($a, $m, $d)=SPLIT( '[/.-]', $bus_consultar_anterior["fecha_orden"]);
							$fecha=$d."-".$m."-".$a;
							echo "<strong>
									Numero: ".$ante." | ".$fecha."
								  </strong>
								  <br />";
						}
					echo "Numero: ".$i."<br />";
					$prox = $i+1;
					$sql_consultar_proximo = mysql_query("select * from orden_compra_servicio where tipo = '".$idtipos_documentos."' and numero_orden like '%-".$prox."%'")or die(mysql_error());
					$num_consultar_proximo = mysql_num_rows($sql_consultar_proximo);
						if($num_consultar_proximo != 0){
							$bus_consultar_proximo = mysql_fetch_array($sql_consultar_proximo);
							list($a, $m, $d)=SPLIT( '[/.-]', $bus_consultar_proximo["fecha_orden"]);
							$fecha=$d."-".$m."-".$a;
							echo "<strong>
									Numero: ".$prox." | ".$fecha."
								  </strong>
								  <br />";
						}
					
				}
			}
		}
		
	}else{
		
		
		$sql_ordenes = mysql_query("select * from orden_pago where numero_orden != '' order by idorden_pago desc")or die(mysql_error());
		$bus_ordenes = mysql_fetch_array($sql_ordenes);
		$partes = explode("-",$bus_ordenes["numero_orden"]);
		$ultimo_numero = $partes[2];
		for($i=1;$i < $ultimo_numero; $i++){
			$sql_consultar_ordenes = mysql_query("select * from orden_pago where numero_orden like '%-".$i."%'");
			$num_consultar_ordenes = mysql_num_rows($sql_consultar_ordenes);
			if($num_consultar_ordenes == 0){
				if($i == 1){
					echo "Numero: ".$i."<br />";
					$prox = $i+1;
					$sql_consultar_proximo = mysql_query("select * from orden_pago where numero_orden like '%-".$prox."%'");
					$num_consultar_proximo = mysql_num_rows($sql_consultar_proximo);
						if($num_consultar_proximo != 0){
							$bus_consultar_proximo = mysql_fetch_array($sql_consultar_proximo);
							list($a, $m, $d)=SPLIT( '[/.-]', $bus_consultar_proximo["fecha_orden"]);
							$fecha=$d."-".$m."-".$a;
							echo "<strong>
									Numero: ".$prox." | ".$fecha."
								  </strong>
								  <br />";
						}
				}else{
					$ante = $i-1;
					$sql_consultar_anterior = mysql_query("select * from orden_pago where numero_orden like '%-".$ante."%'");
					$num_consultar_anterior = mysql_num_rows($sql_consultar_anterior);
						if($num_consultar_anterior != 0){
							$bus_consultar_anterior = mysql_fetch_array($sql_consultar_anterior);
							list($a, $m, $d)=SPLIT( '[/.-]', $bus_consultar_anterior["fecha_orden"]);
							$fecha=$d."-".$m."-".$a;
							echo "<strong>
									Numero: ".$ante." | ".$fecha."
								  </strong>
								  <br />";
						}
					echo "Numero: ".$i."<br />";
					$prox = $i+1;
					$sql_consultar_proximo = mysql_query("select * from orden_pago where numero_orden like '%-".$prox."%'")or die(mysql_error());
					$num_consultar_proximo = mysql_num_rows($sql_consultar_proximo);
						if($num_consultar_proximo != 0){
							$bus_consultar_proximo = mysql_fetch_array($sql_consultar_proximo);
							list($a, $m, $d)=SPLIT( '[/.-]', $bus_consultar_proximo["fecha_orden"]);
							$fecha=$d."-".$m."-".$a;
							echo "<strong>
									Numero: ".$prox." | ".$fecha."
								  </strong>
								  <br />";
						}
					
				}
			}
		}
	}
}






if($ejecutar == "buscarTipo"){

	$sql_consulta = mysql_query("select * from tipos_documentos where descripcion like '%".$campoBuscar."%' order by descripcion ASC");
?>

<table width="80%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
<thead>
								<tr>
									<td width="69%" align="center" class="Browse">Descripcion</td>
								  <td width="13%" align="center" class="Browse">Siglas</td>
                                  <td align="center" class="Browse" colspan="3">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                                <td class='Browse'><?=$bus_consulta["descripcion"]?></td>
                                <td class='Browse' align="center"><?=$bus_consulta["siglas"]?></td>
                                <td width="6%" align="center" class='Browse'><img src="imagenes/modificar.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'modificar')" style="cursor:pointer"></td>
                              <td width="6%" align="center" class='Browse'><img src="imagenes/delete.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'eliminar')" style="cursor:pointer"></td>
                              <td width="6%" align="center" class='Browse'>
                                <img src="imagenes/ver.png"
                                								 onclick="mostrarNumerosLibres('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>', '<?=$bus_consulta["idtipos_documentos"]?>', 'contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>')"
                                								style="cursor:pointer"
                                                                title="Ver Numeros Disponibles">
                                
                       <div id="listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>" style="display:none; position:absolute; background-color: #FFFFCC; border:#000000 1px solid; width:200px" name="listaNumeros">
                                     <div align="right" style="width:100%; background-color:#CCCCCC">
                                         <a href="javascript:;" onclick="document.getElementById('listaNumerosLibres<?=$bus_consulta["idtipos_documentos"]?>').style.display = 'none'">
                                             <strong>Lista de Numeros Libres &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</strong>
                                         </a>
                                     </div>
                                     <div style="width:100%; height:600; overflow:auto" align="left" id="contenido_numeros_libres<?=$bus_consulta["idtipos_documentos"]?>">
                                    	<!-- AQUI VA EL CONTENIDO DE LA LISTA -->
                                        
                                     </div>
                                 </div>
                                
                                </td>
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
	
?>