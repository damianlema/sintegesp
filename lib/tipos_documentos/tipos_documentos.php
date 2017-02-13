<script src="js/tipos_documentos_ajax.js"></script>
	<h4 align=center>Tipos de Documentos</h4>
	<h2 class="sqlmVersion"></h2>
    <div align="center"><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>"><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Documento"></a>&nbsp;</div>

 <form id="form1" name="form1">
<div id="tablaGeneral">    
   
<table width="65%" border="0" align="center" cellpadding="0" cellspacing="2">
  
  <tr>
    <td width="173" align="right" class='viewPropTitle'>Descripci&oacute;n</td>
    <td width="346"><label>
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
    <td>
          <input type="checkbox" name="compromete" id="compromete" value="si" onclick="listaCompromete()">
    </label>
    Compromete&nbsp;&nbsp;
    <label>
    <input type="checkbox" name="causa" id="causa" value="si" onclick="listaCompromete()">
    </label>
    &nbsp;Causa&nbsp;&nbsp;
    <label>
    <input type="checkbox" name="paga" id="paga" value="si" onclick="listaCompromete()">
    </label>
    &nbsp;Paga    </td>
  </tr>
  <tr>
    <td colspan="2"><label>
    
    
    
	  <table id="documentoCompromete" style="display:none">
        	<tr>
            	<td class='viewPropTitle'>Documento que Compromete</td>
                <td>
                  <?
           			 $sql_consulta_select = mysql_query("select * from tipos_documentos where (nro_contador != 0 and causa ='no' and compromete = 'si' and paga ='no') || (multi_categoria = 'si')");
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
    <td colspan="2" ></td>
  </tr>
  <tr>
  	<td colspan="2">
  
  
  <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->

   <table width="100%" align="center" style="display:none" id="tablaNroPropio">
   	<tr>
    	<td width="40%" class='viewPropTitle'>N&uacute;mero del Documento</td>
    	<td width="10%"><input name="contador" type="text" id="contador" size="5" maxlength="5"></td>
        <td width="40%" class='viewPropTitle'>Documento Padre</td>
        <td width="10%"><input type="checkbox" name="documento_padre" id="documento_padre" value="no"/></td>
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
    <td align="right" class='viewPropTitle'>Forma Pre-Impresa</td>
    <td><input type="checkbox" name="forma_preimpresa" id="forma_preimpresa" value="si"/></td>
  </tr>
  <tr>
    <td align="right" class='viewPropTitle'>Multi Categoria</td>
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
				<option value="<?=$bus_cuenta_acreedora["idcuenta"]?>-<?=$bus_cuenta_acreedora["tabla"]?>" onClick="document.getElementById('tabla_acreedora').value = '<?=$bus_cuenta_acreedora["tabla"]?>', document.getElementById('idcuenta_acreedora').value = '<?=$bus_cuenta_acreedora["idcuenta"]?>'"><?=$bus_cuenta_acreedora["codigo"]?> - <?=utf8_decode($bus_cuenta_acreedora["denominacion"])?></option>
			<? }?>
      </select>
      </label>
      <input name="tabla_acreedora" type="hidden" id="tabla_acreedora" size="100" value="0">
      <input name="idcuenta_acreedora" type="hidden" id="idcuenta_acreedora" size="100" value="0">
      <input name="idcuenta_acreedora_modificar" type="hidden" id="idcuenta_acreedora_modificar" size="100">
        </td>
    </tr>
  
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
      	<td colspan="2" style="background:#09F; text-align:center; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>UTILIZADO POR LOS MODULOS</strong></td>
    	</tr>
  <tr>
    <td colspan="2" align="center">
    
    
    	<table cellpadding="4">
        	<tr>
            	<td align="center" class='viewPropTitle'><strong>Modulos Existentes</strong></td>
                <td align="center" class='viewPropTitle'><strong>Modulos Seleccionados</strong></td>
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
                                        '<?=$_GET["modulo"]?>', 
                                        document.getElementById('documento_compromete').value, 
                                        document.getElementById('forma_preimpresa').value,
                                        document.getElementById('multi_categoria').value,
                                        document.getElementById('modulos2').length)">
    </label></td>
  </tr>
</table>
</div> 
<br>

<table align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" class='viewPropTitle'>Tipo de Documento:&nbsp;</td>
        <td><input type="text" name="campoBuscar" id="campoBuscar"></td>
        <td><img src="imagenes/search0.png" onclick="buscarTipo()" style="cursor:pointer" title="Buscra tipo de documento"></td>
    </tr>
</table>
<div id="listaDocumentos">
<?
	$sql_consulta = mysql_query("select * from tipos_documentos order by descripcion ASC");
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
                                <td width="6%" align="center" class='Browse'><img src="imagenes/modificar.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'modificar', <?=$_GET["modulo"]?>)" style="cursor:pointer"></td>
                              <td width="6%" align="center" class='Browse'><img src="imagenes/delete.png" onclick="consultarDatosGenerales(<?=$bus_consulta["idtipos_documentos"]?>, 'eliminar', <?=$_GET["modulo"]?>)" style="cursor:pointer"></td>
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
 </div>
 
 
 
 
 
 
 

 
 
 
 
 
 
 
  <script>document.getElementById('descripcion').focus()</script>
  </form>