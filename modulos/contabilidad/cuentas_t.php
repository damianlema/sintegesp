<script src="modulos/contabilidad/js/cuentas_t_ajax.js"></script>

	<br>
	<h4 align=center>Asientos Contables<br /></h4>
	<h2 class="sqlmVersion"></h2>
<center>
<img src="imagenes/nuevo.png" onclick="window.location.href='principal.php?accion=865&modulo=5'" style="cursor:pointer">
&nbsp;
<img src="imagenes/search0.png" onclick="window.open('modulos/contabilidad/lib/listar_cuentas_t.php','listar_cuentas_t','resizable=no, scrollbars=yes, width=900, height=600')" style="cursor:pointer">

</center>
<br />
<input type="hidden" id="idcuentas_t" name="idcuentas_t">
<input type="hidden" id="idorden_pago" name="idorden_pago">
<input type="hidden" id="tipo_asiento_oculto" name="tipo_asiento_oculto">
<input type="hidden" id="estado" name="estado">

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:950px; margin-left:-475px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos del Asiento</strong></td>
    </tr>
</table>
</div>
<? /*
<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:20px; margin-left:-475px; overflow:auto">
*/ ?>

<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:20px; margin-left:-475px; overflow:auto">

<br />
<table width="100%" border="0" align="center">
  <tr>
    <td width="15%" align="right" >Documento</td>
    <td width="13%" id="celda_nro_orden"><input type="text" name="numero_documento" id="numero_documento" style="width:150px; height:20px" readonly="readonly" /></td>
    <td width="13%" align="right" >Fecha</td>
    <td colspan="4" id="celda_fecha" ><input type="text" name="fecha_documento" id="fecha_documento" style="width:80px; height:20px" readonly="readonly" /></td>
    <td width="13%" align="right" id="titulo_exento" ></td>
    <td width="11%" id="celda_exento" style="display:none"><input type="text" name="exento" id="exento" style="width:120px; height:20px; text-align:right" value="0.00" readonly="readonly" /></td>
  </tr>
  <tr>
    <td align="right" >Beneficiario</td>
    <td id="celda_beneficiario" colspan="6"><input type="text" name="beneficiario" id="beneficiario" style="width:600px; height:20px" readonly="readonly" /></td>
    <td width="13%" align="right" id="titulo_subtotal"></td>
    <td width="11%" id="celda_sub_total" style="display:none"><input type="text" name="sub_total" id="sub_total" style="width:120px; height:20px; text-align:right" value="0.00" readonly="readonly" /></td>
  </tr>
   <tr>
    <td align="right" rowspan="4" >Justificaci&oacute;n</td>
    <td colspan="6" id="celda_justificacion" rowspan="4"><textarea name="justificacion" cols="95" rows="6" id="justificacion"></textarea>
   </td>
    <td width="13%" align="right" id="titulo_impuesto"></td>
    <td width="11%" id="celda_impuesto" style="display:none"><input type="text" name="impuesto" id="impuesto" style="width:120px; height:20px; text-align:right" value="0.00" readonly="readonly" /></td>
  </tr>
  <tr>
    <td width="13%" align="right" id="titulo_totalneto"></td>
    <td width="11%" id="celda_total" style="display:none"><input type="text" name="total_neto" id="total_neto" style="width:120px; height:20px; text-align:right" value="0.00" readonly="readonly" /></td>
  </tr>
  <tr>
    <td width="13%" align="right" id="titulo_retenido"></td>
    <td width="11%" id="celda_retenido" style="display:none"><input type="text" name="retenido" id="retenido" style="width:120px; height:20px; text-align:right" value="0.00" readonly="readonly" /></td>
  </tr>
  <tr>
    <td width="13%" align="right" id="titulo_totalpagar"></td>
    <td width="11%" id="celda_total_pagar" style="display:none"><input type="text" name="total_pagar" id="total_pagar" style="width:120px; height:20px; text-align:right" value="0.00" readonly="readonly" /></td>
  </tr>
  <tr>
    <td align="right">Estado Contable</td>
    <td id="celda_estado"><input type="text" name="estado_contable" id="estado_contable" style="width:100px; height:20px" value="Elaboraci&oacute;n" readonly="readonly" disabled="disabled" /></td>
    <td align="right" >Fecha Contable</td>
    <td id="celda_fecha_contable" colspan="4"><input type="text" name="fecha_contable" id="fecha_contable" style="width:80px; height:20px" value="<?=date("d-m-Y");?>" readonly="readonly" /></td>
  </tr>
  <tr>
    <td colspan="9" align="center">
    <input type="button" name="boton_siguiente" id="boton_siguiente" value="Realizar Asiento" class="button" style="display:none" onClick="guardarDatosBasicos()">
    <input type="button" name="boton_procesar" id="boton_procesar" value="Procesar Asiento" class="button" style="display:none" onclick="procesarCuentas()">
    <input type="button" name="boton_anular" id="boton_anular" value="Anular Asiento" class="button" style="display:none" onclick="anularCuentas()"></td>
    <td width="1%"></td>
  </tr>
</table>
</div>
<br />

<div id="debe" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:220px; margin-left:-475px; overflow:auto">
<table width="70%" style="border:#000000 1px solid">
 <tr>
    <td width="50%" align="center" class='viewPropTitle' style="border-right:#000000 1px solid; border-bottom:#000000 1px solid"><strong>Debe</strong> </td>
  </tr>
  <tr>
    <td width="50%">
        <table>
          <tr>
            <td><strong>Total:&nbsp;</strong></td>
            <td id="celda_total_debe" style="font-weight:bold"></td>
          </tr>
        </table>
    </td>
  </tr>
  <tr>
  
  <td width="50%">
  <form onsubmit="return ingresarCuentas('debe')">
      <input type="hidden" name="idcuenta_debe" id="idcuenta_debe">
      <input type="hidden" name="nivel_debe" id="nivel_debe">
      <table align="center" style="display:none" id="tabla_debe">
        <tr>
          <td class='viewPropTitle'>Cuenta</td>
          <td><?
                    $sql_cuenta = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion' AS tabla
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
							  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo' AS tabla
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
							  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)")or die(mysql_error());
					?>
                <select name="cuenta_debe" id="cuenta_debe">
                  <option value="0">.:: Seleccione ::.</option>
                  <?
						while($bus_cuenta = mysql_fetch_array($sql_cuenta)){
				  ?>
                  <option value="<?=$bus_desagregacion["iddesagregacion_cuentas_contables"]?>" 
                  		onclick="seleccionarCuentaDebe('<?=$bus_cuenta["idcuenta"]?>', '<?=$bus_cuenta["tabla"]?>')"> 
                        (<?=$bus_cuenta["codigo"]?>)
                    	<?=$bus_cuenta["denominacion"]?>
                  </option>
                  <?
					}	
				   ?>
                </select>
          </td>
          <td><input type="text" name="monto_debe" id="monto_debe" size="15" style="text-align:right"/></td>
          <td><input type="image" name="boton_cuentas_debe" src="imagenes/validar.png"/></td>
        </tr>
      </table>
    </form></td>
  
  </tr>
  <tr>
   <td width="50%" id="celda_cuentas_seleccionadas_debe" valign="top">&nbsp;</td>
  </tr>
</table>
<br />
<br />
</div>
<div id="haber" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:320px; margin-left:-475px; overflow:auto">
<table id="tabla_cuentas" align="center" width="70%" style="border:#000000 1px solid" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50%" align="center" class='viewPropTitle' style="border-bottom:#000000 1px solid"><strong>Haber</strong> </td>
  </tr>
  
  
    <tr>
    <td width="50%">
        <table>
          <tr>
            <td><strong>Total:&nbsp;</strong></td>
            <td id="celda_total_haber" style="font-weight:bold"></td>
          </tr>
        </table>
    </td>
  </tr>
  
  
  
  
  
  <tr>
        <td width="50%"><form onsubmit="return ingresarCuentas('haber')">
      <input type="hidden" name="idcuenta_haber" id="idcuenta_haber">
      <input type="hidden" name="nivel_haber" id="nivel_haber">
      <table align="center" style="display:none" id="tabla_haber">
        <tr>
          <td class='viewPropTitle'>Cuenta</td>
          <td><?
                    $sql_cuenta = mysql_query("(SELECT
						  d.iddesagregacion_cuentas_contables as idcuenta,
						  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo, '.', d.codigo) AS codigo,
						  d.denominacion,
						  'desagregacion' AS tabla
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
							  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
							  sc2.denominacion,
							  'subcuenta_segundo' AS tabla
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
							  CONCAT(g.codigo, '.', s.codigo, '.', r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
							  sc.denominacion,
							  'subcuenta_primer' AS tabla
					FROM
							  subcuenta_primer_cuentas_contables sc
							  INNER JOIN cuenta_cuentas_contables c ON (sc.idcuenta= c.idcuenta_cuentas_contables)
							  INNER JOIN rubro_cuentas_contables r ON (c.idrubro = r.idrubro_cuentas_contables)
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)
					WHERE (
							  sc.idsubcuenta_primer_cuentas_contables not in(select idsubcuenta_primer from subcuenta_segundo_cuentas_contables)
					
					)
					)")or die(mysql_error());
					?>
                <select name="cuenta_haber" id="cuenta_haber">
                  <option value="0">.:: Seleccione ::.</option>
                  <?
						while($bus_cuenta = mysql_fetch_array($sql_cuenta)){
				  ?>
                  <option value="<?=$bus_desagregacion["iddesagregacion_cuentas_contables"]?>" 
                  		onclick="seleccionarCuentaHaber('<?=$bus_cuenta["idcuenta"]?>', '<?=$bus_cuenta["tabla"]?>')"> 
                        (<?=$bus_cuenta["codigo"]?>)
                    	<?=$bus_cuenta["denominacion"]?>
                  </option>
                  <?
					}	
				   ?>
                </select>
          </td>
          <td><input type="text" name="monto_haber" id="monto_haber" size="15" style="text-align:right"/></td>
          <td><input type="image" name="boton_cuentas_haber" src="imagenes/validar.png"/></td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td width="50%" id="celda_cuentas_seleccionadas_haber" valign="top">&nbsp;</td>
  </tr>
</table>
<br />
</div>
<br>
<br>
<div id="documentos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:220px; margin-left:-475px; overflow:auto">
<form id="form_buscar" onSubmit="return listarOrdenes()">
<table align="center">
    <tr>
        <td align="right">Tipo de Asiento</td>
        <td>
        <select name="tipo_asiento" id="tipo_asiento">
            <option value="0">.:: Seleccione ::.</option>
            <option value="ingresos">Ingresos / Egresos</option>
            <option value="compromisos">Compromisos</option>
            <option value="causados">Causados</option>
            <option value="pagados">Pagados</option>
        </select>
      </td>
        <td align="right">Datos a Buscar</td>
        <td><input type="text" name="texto_buscar" id="texto_buscar" size="40"></td>
        <td><input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="button"></td>
    </tr>
</table>
</form>

</div>
<div id="lista_ordenes" style="position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:290px; margin-left:-475px; overflow:auto"></div>
