<?php
session_start();
?>

<script src="modulos/contabilidad/js/asientos_contables_ajax.js"></script>
<?
$sql_configuracion=mysql_query("select * from configuracion 
											where status='a'"
												,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

$anio_fijo=$registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo=$registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo=$registro_configuracion["idfuente_financiamiento"];

	/*<br>
	<h4 align=center>Asientos Contables<br /></h4>
	<h2 class="sqlmVersion"></h2>
  */
  ?>
<center>
<img src="imagenes/nuevo.png" onclick="window.location.href='principal.php?accion=357&modulo=5'" style="cursor:pointer">
&nbsp;
<img src="imagenes/search0.png" onclick="window.open('modulos/contabilidad/lib/listar_cuentas_t.php','listar_cuentas_t','resizable=no, scrollbars=yes, width=900, height=600')" style="cursor:pointer">

</center>
<br />
<input type="hidden" id="idasiento_contable" name="idasiento_contable">
<input type="hidden" id="estado" name="estado">

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:90%; margin-left:-560px;">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos del Asiento</strong></td>
    </tr>
</table>
</div>
<? /*
<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:950px; margin-top:20px; margin-left:-475px; overflow:auto">
<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:90%; margin-left:-560px; margin-left:-475px; overflow:auto">
*/ ?>
<div id="divDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:auto; width:90%; margin-left:-560px; margin-top:20px; overflow:auto">


<br />
<table width="100%" border="0" align="center">
  <tr>
    <td width="20%" align="right" >Fecha</td>
    <td  id="celda_fecha" width="15%">
    	<input type="text" name="fecha_documento" id="fecha_documento" style="width:80px; height:20px" value="<?=date("Y-m-d");?>"/>
         <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" 
            									id="f_trigger_c" 
                                                style="cursor: pointer;" 
                                                title="Selector de Fecha" 
                                                onMouseOver="this.style.background='red';" 
                                                onMouseOut="this.style.background=''" onClick="
                                Calendar.setup({
                                inputField    : 'fecha_documento',
                                button        : 'f_trigger_c',
                                align         : 'Tr',
                                ifFormat      : '%Y-%m-%d'
                                });"/>
    </td>
    <td width="5%" align="right" >Mes</td>
    <td  id="celda_mes" width="5%" >
    	 <select name="mes_contable" id="mes_contable">
             <option value="01" <? if(date(m) == '01') echo 'selected';?>>01</option>
             <option value="02" <? if(date(m) == '02') echo 'selected';?>>02</option>
             <option value="03" <? if(date(m) == '03') echo 'selected';?>>03</option>
             <option value="04" <? if(date(m) == '04') echo 'selected';?>>04</option>
             <option value="05" <? if(date(m) == '05') echo 'selected';?>>05</option>
             <option value="06" <? if(date(m) == '06') echo 'selected';?>>06</option>
             <option value="07" <? if(date(m) == '07') echo 'selected';?>>07</option>
             <option value="08" <? if(date(m) == '08') echo 'selected';?>>08</option>
             <option value="09" <? if(date(m) == '09') echo 'selected';?>>09</option>
             <option value="10" <? if(date(m) == '10') echo 'selected';?>>10</option>
             <option value="11" <? if(date(m) == '11') echo 'selected';?>>11</option>
             <option value="12" <? if(date(m) == '12') echo 'selected';?>>12</option>
         </select>
    </td>
    <td align="right" width="15%">N&uacute;mero de Asiento</td>
    <td id="celda_numero_asiento" width="5%"><input type="text" name="numero_asiento" id="numero_asiento" style="width:50px; height:20px" value="*" readonly="readonly" disabled="disabled" /></td>
    <td align="right" width="15%">Estado Contable</td>
    <td id="celda_estado"><input type="text" name="estado_contable" id="estado_contable" style="width:100px; height:20px" value="Elaboracion" readonly="readonly" disabled="disabled" /></td>
  </tr>
  <tr>
    <td align="right" rowspan="1" >Detalle</td>
    <td colspan="7" id="celda_justificacion" rowspan="1"><textarea name="detalle" cols="125" rows="4" id="detalle"></textarea></td>
  </tr>

  <tr>
    <td align="right" class='viewPropTitleNew'>Fuente de Financiamiento</td>
    <td colspan="7">
    	<select name="idfuente_financiamiento" id="idfuente_financiamiento">
          <option>.:: Seleccione ::.</option>
          <?php
			$sql_fuente_financiamiento=mysql_query("select * from fuente_financiamiento 
												where status='a'",$conexion_db);
			while($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {  ?>
          		<option <?php echo 'value="'.$rowfuente_financiamiento["idfuente_financiamiento"].'"'; 
						if ($rowfuente_financiamiento["idfuente_financiamiento"]==$idfuente_financiamiento_fijo) {echo ' selected';}?>> 
						<?php echo $rowfuente_financiamiento["denominacion"];?> 
				</option>
          <?php 
      		} ?>
      	</select>
    </td>
  </tr>

 
  <tr>
    <td colspan="10" align="center">
    	<table width="20%" border="0" align="center">
    		<tr>
    			<td align="center">
				    <input type="button" name="boton_siguiente" id="boton_siguiente" value="Realizar Asiento" class="button" style="display:block" onClick="guardarDatosBasicos()">
				</td>
				<td align="center">
				    <input type="button" name="boton_ajustar" id="boton_ajustar" value="Modificar Asiento" class="button" style="display:none" onclick="ajustarCuentas()">
				</td>
				<td align="center">
				    <input type="button" name="boton_procesar" id="boton_procesar" value="Procesar Asiento" class="button" style="display:none" onclick="procesarCuentas()">
				</td>
				<td align="center">
				    <input type="button" name="boton_anular" id="boton_anular" value="Anular Asiento" class="button" style="display:none" 
				    onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"  >
				</td>
			</tr>
		</table>
	</td>
  </tr>
</table>
</div>
<div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:11; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:200px; margin-left:550px">
      <table align="center">
        <tr>
          <td align="right" colspan="2">
            <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
              <strong>x</strong>                                </a>                            </td>
        </tr>
        <tr>
          <td  width="70%"><strong>Fecha de Anulaci&oacute;n:</strong> </td>
          
            <td><input name="fecha_anulacion_asiento" type="text" id="fecha_anulacion_asiento" size="12" value="<?=date("Y-m-d")?>" disabled="disabled">
            
            	<img src="imagenes/jscalendar0.gif" name="f_trigger_cf" width="16" height="16" id="f_trigger_cf" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				  <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_anulacion_asiento",
							button        : "f_trigger_cf",
							align         : "Tr",
							ifFormat    	: "%Y-%m-%d"
							});
						</script>                 
           </td>
        </tr>
        <tr>
          <td><strong>Usuario:</strong> </td>
            <td><?=$login?></td>
        </tr>
        <tr>
          <td><strong>Clave:</strong></td>
            <td><input type="password" name="verificarClave" id="verificarClave"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="button" name="validar" id="validar" class="button" value="Anular" 
          	onclick="anularCuentas()"></td>

        </tr>
	</table>
</div>
<br />

<div id="debe" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; 
		height:350px; width:90%; margin-left:-560px; margin-top:205px;  overflow:auto">
<table width="100%">
 <tr>
    <td width="100%" align="center" class='viewPropTitle' style="border-right:#000000 1px solid; border-bottom:#000000 1px solid"><strong>Cuentas Contables</strong> </td>
  </tr>
  <tr>
  
  <td width="100%">
  <form onsubmit="return ingresarCuentas()">
      <input type="hidden" name="idcuenta_debe" id="idcuenta_debe">
      <input type="hidden" name="nivel_debe" id="nivel_debe">
      <table align="center" style="display:block" id="tabla_debe">
        <tr>
        	<td width="5%">Afecta:</td>	
        	<td width="5%">
        			<select id='tipo' name='tipo'>
        				<option value='debe'>Debe</option>
        				<option value='haber'>Haber</option>
        			</select>
        	</td>	
          <td width="5%">Cuenta:</td>
          <td width="68%"><?
                    $sql_cuenta = mysql_query("(SELECT
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
					?>
                <select name="cuenta_debe" id="cuenta_debe" style="width:100%">
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
          <td width="20%"><input type="text" name="monto_debe" id="monto_debe" size="20" style="text-align:right"/></td>
          <td width="1%"><input type="image" name="boton_cuentas_debe" src="imagenes/validar.png"/></td>
        </tr>
      </table>
    </form>
</td>
  
  	</tr>
  	<tr>
	   	<td width="100%" id="celda_cuentas_seleccionadas_debe" valign="top">&nbsp;</td>
	</tr>
 </table>
</div>

<? /*

<div id="haber" style="display:none; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; 
	height:450px; width:950px; margin-top:400px; margin-left:-475px; overflow:auto">
<table align="right" width="70%" style="border:#000000 1px solid">
 <tr>
    <td width="50%" colspan="4" align="center" class='viewPropTitle' style="border-right:#000000 1px solid; border-bottom:#000000 1px solid"><strong>Haber</strong> </td>
  </tr>
  
  
    <tr>
    <td width="50%" align="right" colspan="4">
        <table>
          <tr>
            <td><strong>Total:&nbsp;</strong></td>
            <td id="celda_total_haber" style="font-weight:bold"></td>
          </tr>
        </table>
    </td>
  </tr>
  
  
  
  
  
  <tr>
       <td ><form onsubmit="return ingresarCuentas('haber')">
      <input type="hidden" name="idcuenta_haber" id="idcuenta_haber">
      <input type="hidden" name="nivel_haber" id="nivel_haber">
      <table align="center" style="display:block" id="tabla_haber">
        <tr>
          <td width="5%">Cuenta</td>
          <td width="69%"><?
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
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo, '.', sc2.codigo) AS codigo,
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
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', c.codigo, '.', sc.codigo) AS codigo,
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
					)
					UNION
					(SELECT
							  cc.idcuenta_cuentas_contables as idcuenta,
							  CONCAT(g.codigo, s.codigo, r.codigo, '.', cc.codigo) AS codigo,
							  cc.denominacion,
							  'cuenta_cuentas' AS tabla
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
							  'rubro_cuentas' AS tabla
					FROM
							  rubro_cuentas_contables r
							  INNER JOIN subgrupo_cuentas_contables s ON (r.idsubgrupo = s.idsubgrupo_cuentas_contables)
							  INNER JOIN grupo_cuentas_contables g ON (s.idgrupo = g.idgrupos_cuentas_contables)

					) ORDER BY codigo")or die(mysql_error());
					?>
                <select name="cuenta_haber" id="cuenta_haber" style="width:100%">
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
          <td width="20%"><input type="text" name="monto_haber" id="monto_haber" size="25" style="text-align:right"/></td>
          <td width="1%" align="left"><input type="image" name="boton_cuentas_haber" src="imagenes/validar.png"/></td>
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
*/ ?>