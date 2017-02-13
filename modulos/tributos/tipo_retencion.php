<script src="modulos/tributos/js/tipo_retencion_ajax.js"></script>
  <br>
  <h4 align=center>Tipos de Retencion</h4>
  <h2 class="sqlmVersion"></h2>
  <br>
<div align="center"><a href="principal.php?accion=<?=$_GET["accion"]?>&modulo=<?=$_GET["modulo"]?>"><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Retencion"></a>&nbsp;</div>
<div id="tablaGeneral">

<table width="70%" border="0" align="center" cellpadding="0" cellspacing="2">

  <tr>
    <td align="right" class='viewPropTitle'>C&oacute;digo</td>
    <td><label>
      <input name="codigo" type="text" id="codigo" size="5">
    </label></td>
  </tr>
  <tr>
    <td width="315" align="right" class='viewPropTitle'>Descripci&oacute;n</td>
    <td width="366"><label>
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


    <table width="100%" style="display:none" id="tablaPorcentaje">
        <tr>
            <td width="54%" class='viewPropTitle'>Porcentaje
              <label>
              <input type="text" name="porcentaje" id="porcentaje" size="6" style="text-align:right">
            </label></td>
            <td width="46%" class='viewPropTitle'>Divisor
              <label>
              <input type="text" name="divisor" id="divisor" size="6" style="text-align:right">
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
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">


  <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->

   <table align="center" style="display:block" id="tablaNroPropio">
    <tr>
      <td class='viewPropTitle'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N&uacute;mero Comprobante</td>
      <td><input name="contador" type="text" id="contador" size="5" maxlength="5"></td>
    </tr>
   </table>

   <table align="center" style="display:none" id="tablaAsociado">
    <tr>
      <td class='viewPropTitle' align="right">Documento Asociado</td>
      <td>
          <?
$sql_consulta_select = mysql_query("select * from tipo_retencion where numero_documento != ''");
?>
            <select name="documentoAsociado" id="documentoAsociado">
              <option value="0">.:: Seleccione ::.</option>
        <?
while ($bus_consulta_select = mysql_fetch_array($sql_consulta_select)) {
    ?>
          <option value="<?=$bus_consulta_select["idtipo_retencion"]?>"><?=$bus_consulta_select["descripcion"]?></option>
        <?
}
?>
            </select>        </td>
    </tr>
   </table>

   <!-- TABLAS OCULTAS DE ORIGEN DE NUMERACION -->    </td>
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
        <option value="ISLR">ISLR</option>
        <option value="1x1000">1x1000 Delta Amacuro</option>
        <option value="1x1000M">1x1000 Monagas</option>
        <option value="municipal">Municipal</option>
        <option value="NA">Sin Comprobante</option>
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

          ) ORDER BY codigo") or die(mysql_error());

while ($bus_cuenta_deudora = mysql_fetch_array($sql_consultar)) {?>
        <option value="<?=$bus_cuenta_deudora["idcuenta"]?>-<?=$bus_cuenta_deudora["tabla"]?>" onClick="document.getElementById('tabla_deudora').value = '<?=$bus_cuenta_deudora["tabla"]?>', document.getElementById('idcuenta_deudora').value = '<?=$bus_cuenta_deudora["idcuenta"]?>'"><?=$bus_cuenta_deudora["codigo"]?>- <?=utf8_decode($bus_cuenta_deudora["denominacion"])?></option>
      <?}?>
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

          ) ORDER BY codigo") or die(mysql_error());
while ($bus_cuenta_acreedora = mysql_fetch_array($sql_consultar)) {?>
        <option value="<?=$bus_cuenta_acreedora["idcuenta"]?>-<?=$bus_cuenta_acreedora["tabla"]?>" onClick="document.getElementById('tabla_acreedora').value = '<?=$bus_cuenta_acreedora["tabla"]?>', document.getElementById('idcuenta_acreedora').value = '<?=$bus_cuenta_acreedora["idcuenta"]?>'"><?=$bus_cuenta_acreedora["codigo"]?> - <?=utf8_decode($bus_cuenta_acreedora["denominacion"])?></option>
      <?}?>
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
if (in_array(407, $privilegios) == true) {
    ?>
      <input type="button" name="enviar" id="enviar" value="Registrar" class="button"
          onclick="ingresarRetencion(document.getElementById('codigo').value, document.getElementById('descripcion').value , document.getElementById('monto_fijo').value, document.getElementById('porcentaje').value, document.getElementById('divisor').value, document.getElementById('base_calculo').value, document.getElementById('unidad_tributaria').value, document.getElementById('factor_calculo').value, document.getElementById('contador').value, document.getElementById('documentoAsociado').value, document.getElementById('articulo').value, document.getElementById('numeral').value, document.getElementById('literal').value, document.getElementById('nombre_comprobante').value)">
    </label>
    <?
}
?></td>
  </tr>
</table>
</div>


<div id="listaRetenciones">
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
while ($bus_consulta = mysql_fetch_array($sql_consulta)) {
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
 </div>