<script src="js/certificacion_rrhh_ajax.js" type="text/javascript" language="javascript"></script>


<h4 align=center>Certificaci&oacute;n de Compromisos</h4>
<h2 class="sqlmVersion"></h2>
<?

$sql_configuracion = mysql_query("select * from configuracion where status='a'");

$registro_configuracion = mysql_fetch_assoc($sql_configuracion);

$anio_fijo                    = $registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo      = $registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo = $registro_configuracion["idfuente_financiamiento"];
include "../../../funciones/funciones.php";
?>
<input type="hidden" name="tipo_carga_orden" id="tipo_carga_orden">
<input type="hidden" id="id_orden_compra" name="id_orden_compra">
<input type="hidden" id="idestado" name="idestado"  />

<table width="6%" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
      <td align="right">
        <div align="center">
          <img src="imagenes/search0.png" title="Buscar Certificaciones" style="cursor:pointer" onclick="abreVentana('lib/listas/listar_ordenes_compra.php?destino=ordenes&amp;accion=<?=$_GET["accion"]?>');return false" />
        </div>
      </td>
      <td align="right">
        <div align="center">
          <img src="imagenes/nuevo.png" title="Ingresar nueva Certificacion" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />
        </div>
      </td>
      <td align="right">
        <div align="center" id="celdaRecalcular" style="display:none">
          <img src="imagenes/refrescar.png" title="Recalcular Compromiso" onClick="recalcular()" style="cursor:pointer" />
        </div>
      </td>
      <td align="right" >
        <div align="center" id="celdaImprimir" style="display:none">
         <img src="imagenes/imprimir.png" title="Imprimir Certificacion" id="btImprimir"  onclick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=certificacion_compromiso_rrhh&id_orden_compra='+document.getElementById('id_orden_compra').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer; visibility:hidden;" />
        </div>
      </td>
    </tr>
</table>



<div id="divImprimir" style="display:none; position:absolute; z-index:10; background-color:#CCCCCC; border:1px solid;">
<table align="center">
  <tr><td align="right"><a href="javascript:;" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
    <tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<div id="divAjusteTituloDatos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:20px; width:90%; margin-left:-560px;margin-top:5px">
<table width="100%" border="0" align="center" style="background: #09F" >
    <tr>
      <td align="center" style="color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:12px"><strong>Datos del Compromiso</strong></td>
    </tr>
</table>
</div>

<!-- TABLA DE DATOS BASICOS-->
<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:282px; width:90%; margin-left:-560px; margin-top:25px; overflow:auto">
<!-- <div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:265px; width:96%; margin-left:-600px; margin-top:25px; overflow:auto"> -->
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">

    <tr>
      <td align="right" class='viewPropTitleNew' width="25%">N&uacute;mero:</td>
      <td width="5%" colspan="2" style="border:1px solid #999; background-color:#FFF" id="celdaNroOrden"><strong>&nbsp;Aun no generado</strong></td>
      <td align="right" class='viewPropTitleNew' width="5%">Fecha Orden:</td>
      <td align="left" width="10%"><input type="text" name="fecha_orden" id="fecha_orden" style="width:150px; height:20px; font-size:10px; font-weight: bold;" readonly="readonly" /></td>
      <td align="right" class='viewPropTitleNew' width="15%">Elaborada:</td>
      <td colspan="3" width="15%" class='viewPropTitleNew' id="celdaFechaElaboracion"><?=date("d-m-Y")?></td>
    </tr>

    <tr>
      <td align="right" class='viewPropTitleNew'>Estado:</td>
        <td colspan="6" style="border:1px solid #999; background-color:#FFF" id="celdaEstado"><strong>&nbsp;En Elaboraci&oacute;n</strong></td>
    </tr>
    <tr>
        <td align="right" class='viewPropTitleNew'><div align="right">Tipo de Orden:</div></td>
      <td colspan="6">
    <?
$sql_tipos_documentos = mysql_query("select * from tipos_documentos where modulo like '%-" . $_SESSION["modulo"] . "-%' and compromete = 'si' and causa = 'no' and paga = 'no' and multi_categoria='si'");
?>
        <select name="tipo_orden" id="tipo_orden">
      <?
while ($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)) {
    ?>
                <option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
            <?
}
?>
        </select>
      <input type="hidden" id="id_orden_compra" name="id_orden_compra">      </td>



    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Beneficiario:</td>
      <td colspan="4"><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="120"  readonly="readonly"/>
                  <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
                  <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" />
      </td>
      <td align="left"><img style="display:block"
                                        src="imagenes/search0.png"
                                        title="Buscar Nuevo Proveedor"
                                        id="buscarProveedor"
                                        name="buscarProveedor"
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=compromisos_rrhh','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" />
       </td>
       <td align="left"><a href="#" onclick="abrirCerrarDatosExtra()" id="textoContraerDatosExtra" style="font-size:10px">Origen Presupuestario</a>
       </td>
    </tr>


  <tr>
    <td colspan="7">
    <table width="100%" id="datosExtra" style="display:none">
      <tr>
          <input type="hidden" name="id_ordinal" id="id_ordinal" value="" />
            <td width="21%" align="right" class='viewPropTitleNew'>Fuente de Financiamiento</td>
            <td colspan="2">
              <select name="fuente_financiamiento" id="fuente_financiamiento">
                      <option>.:: Seleccione ::.</option>
                      <?php
$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
                                                            where status='a'"
    , $conexion_db);
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
                      <option onclick="document.getElementById('cofinanciamiento').value = 'no'" <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
    if ($rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {echo ' selected';} ?>> <?php echo $rowfuente_financiamiento["denominacion"]; ?> </option>
                      <?php
}

$sql_cofinanciamiento = mysql_query("select * from cofinanciamiento");
while ($bus_cofinanciamiento = mysql_fetch_array($sql_cofinanciamiento)) {
    ?>
                                    <option onclick="document.getElementById('cofinanciamiento').value = 'si'" value="<?=$bus_cofinanciamiento["idcofinanciamiento"]?>"><?=$bus_cofinanciamiento["denominacion"]?></option>
                                    <?
}
?>
              </select>
              <input type="hidden" id="cofinanciamiento" name="cofinanciamiento" value="">
            </td>
            <td align="right" class='viewPropTitleNew'>Tipo de Presupuesto</td>
            <td align="right" >
                <select name="tipo_presupuesto" id="tipo_presupuesto">
                    <option>.:: Seleccione ::.</option>
                    <?php
$sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto
                                                        where status='a'"
    , $conexion_db);
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
    ?>
                    <option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';
    if ($rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {echo ' selected';} ?>> <?php echo $rowtipo_presupuesto["denominacion"]; ?> </option>
                    <?php
}
?>
                </select>
            </td>
            <td align="right" class='viewPropTitleNew'>A&ntilde;o</td>
            <td><select name="anio" id="anio" disabled="disabled">
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
      <td align="right" class='viewPropTitleNew'>Concepto:</td>
      <td colspan="5"><textarea name="justificacion" cols="125" rows="4" id="justificacion"></textarea></td>
      <td><a href="#" onclick="abrirCerrarObservaciones()" id="textoContraerObservaciones"><img border="0" src="imagenes/comments.png" title="Observaciones" style="text-decoration:none" /></a>
      </td>
  </tr>
  <tr>
      <td colspan="6">
        <table id="divObservaciones" style="display:none" width="100%">
                <tr>
                    <td width="12%" align="right" class='viewPropTitleNew'>Observaciones:</td>
                    <td width="88%" colspan="7"><textarea name="observaciones" cols="110" rows="2" id="observaciones"></textarea></td>
                </tr>
            </table>      </td>
  </tr>
  <tr>
      <td align="right" class='viewPropTitleNew'>Ordenado:</td>
      <td ><?
$sql_configuracion = mysql_query("select * from configuracion");
$bus_configuracion = mysql_fetch_array($sql_configuracion);

if ($_SESSION["modulo"] == "2") {
    $campo_buscar    = $bus_configuracion["ordena_presupuesto"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_presupuesto"];
} else if ($_SESSION["modulo"] == "3") {
    $campo_buscar    = $bus_configuracion["ordena_compras"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_compras"];
} else if ($_SESSION["modulo"] == "4") {
    $campo_buscar    = $bus_configuracion["ordena_certificacion_administracion"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_certificacion_administracion"];
} else if ($_SESSION["modulo"] == "12") {
    $campo_buscar    = $bus_configuracion["ordena_despacho"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_despacho"];
} else if ($_SESSION["modulo"] == "1") {
    $campo_buscar    = $bus_configuracion["ordena_rrhh"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_rrhh"];
} else if ($_SESSION["modulo"] == "14") {
    $campo_buscar    = $bus_configuracion["ordena_secretaria"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_secretaria"];
} else if ($_SESSION["modulo"] == "19") {
    $campo_buscar    = $bus_configuracion["ordena_obras"];
    $ci_campo_buscar = $bus_configuracion["ci_ordena_obras"];
}

?>
      <select name="ordenado_por" id="ordenado_por">
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_administracion"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
        <?=$bus_configuracion_administracion["primero_administracion"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_administracion"]) {
    echo "selected";
}?>
              id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
              onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
        <?=$bus_configuracion_administracion["segundo_administracion"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_administracion"]) {
    echo "selected";
}?>
           id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
        <?=$bus_configuracion_administracion["tercero_administracion"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_compras"]) {
    echo "selected";
}?>
            id="<?=$bus_configuracion_administracion["primero_compras"]?>"
            onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
        <?=$bus_configuracion_administracion["primero_compras"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_compras"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
        <?=$bus_configuracion_administracion["segundo_compras"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_compras"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
        <?=$bus_configuracion_administracion["tercero_compras"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_rrhh"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
        <?=$bus_configuracion_administracion["primero_rrhh"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_rrhh"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
        <?=$bus_configuracion_administracion["segundo_rrhh"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_rrhh"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
        <?=$bus_configuracion_administracion["tercero_rrhh"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_contabilidad"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_priemro_contabilidad"]?>'">
        <?=$bus_configuracion_administracion["primero_contabilidad"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_contabilidad"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
        <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_contabilidad"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
        <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_presupuesto"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
        <?=$bus_configuracion_administracion["primero_presupuesto"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_presupuesto"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
        <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_presupuesto"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
        <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_tesoreria"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
        <?=$bus_configuracion_administracion["primero_tesoreria"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_tesoreria"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
        <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_tesoreria"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
        <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_tributos"]) {
    echo "selected";

}?>
          id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
        <?=$bus_configuracion_administracion["primero_tributos"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_tributos"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
        <?=$bus_configuracion_administracion["segundo_tributos"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_tributos"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
        <?=$bus_configuracion_administracion["tercero_tributos"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_despacho"]) {
    echo "selected";

}?>
          id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_despacho"]?>'">
        <?=$bus_configuracion_administracion["primero_despacho"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_despacho"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
        <?=$bus_configuracion_administracion["segundo_despacho"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_despacho"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
        <?=$bus_configuracion_administracion["tercero_despacho"]?>
        </option>
        <?
$sql_configuracion_administracion = mysql_query("select * from configuracion_secretaria") or die(mysql_error());
$bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
?>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["primero_secretaria"]) {
    echo "selected";

}?>
          id="<?=$bus_configuracion_administracion["primero_secretaria"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_primero_secretaria"]?>'">
        <?=$bus_configuracion_administracion["primero_secretaria"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["segundo_secretaria"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["segundo_secretaria"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_segundo_secretaria"]?>'">
        <?=$bus_configuracion_administracion["segundo_secretaria"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_administracion["tercero_secretaria"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_administracion["tercero_secretaria"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_administracion["ci_tercero_secretaria"]?>'">
        <?=$bus_configuracion_administracion["tercero_secretaria"]?>
        </option>
               <?
$bus_configuracion_obras = mysql_query("select * from configuracion_obras") or die(mysql_error());
$bus_configuracion_obras = mysql_fetch_array($bus_configuracion_obras);
?>
        <option <?if ($campo_buscar == $bus_configuracion_obras["primero_obras"]) {
    echo "selected";

}?>
          id="<?=$bus_configuracion_obras["primero_obras"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_obras["ci_primero_obras"]?>'">
        <?=$bus_configuracion_obras["primero_obras"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_obras["segundo_obras"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_obras["segundo_obras"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_obras["ci_segundo_obras"]?>'">
        <?=$bus_configuracion_obras["segundo_obras"]?>
        </option>
        <option <?if ($campo_buscar == $bus_configuracion_obras["tercero_obras"]) {
    echo "selected";
}?>
          id="<?=$bus_configuracion_obras["tercero_obras"]?>"
          onclick="document.getElementById('cedula_ordenado').value='<?=$bus_configuracion_obras["ci_tercero_obras"]?>'">
        <?=$bus_configuracion_obras["tercero_obras"]?>
        </option>
      </select>
      </td>

      <td align="right" colspan="2" class='viewPropTitleNew'>C&eacute;dula Ordenado:</td>

      <td colspan="5"><input type="text" name="cedula_ordenado" id="cedula_ordenado" value="<?=$ci_campo_buscar?>"/></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Memorandum:</td>
      <td><input type="text" name="numero_requisicion" id="numero_requisicion" /></td>
      <td align="right" class='viewPropTitleNew'>Fecha</td>
      <td colspan="4"><input type="text" name="fecha_requisicion" id="fecha_requisicion" size="13" readonly="readonly"/>
          <img src="imagenes/jscalendar0.gif" alt="" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
          <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_requisicion",
              button        : "f_trigger_c",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
              });
            </script>
       </td>
    </tr>
    <tr>
      <td colspan="7" id="vistaDeBotones">
        <table align="center">
          <tr>
            <td id="celdaBotonSiguiente" style="display:block">
                    <input type="button"
                            name="botonSiguiente"
                            id="botonSiguiente"
                            value="Siguiente >"
                            style="display:block"
                            onclick="ingresarDatosBasicos(document.getElementById('tipo_orden').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('numero_requisicion').value, document.getElementById('fecha_requisicion').value, document.getElementById('id_beneficiarios').value,  document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value)"
                            class="button">
               </td>
               <td id="celdaBotonElaboracion">
                <input type="button"
                    name="botonEnElaboracion"
                    id="botonEnElaboracion"
                    value="En Elaboraci&oacute;n"
                    style="display:none"
                    onclick="actualizarDatosBasicos('actualizar')"
                    class="button">
               </td>
               <td id="celdaBotonProcesar">
                  <?
if ($_SESSION["modulo"] == 1 and in_array(735, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonProcesar"
              id="botonProcesar"
              value="Procesar"
                            style="display:none"
              onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
              class="button">
          <?
} else if ($_SESSION["modulo"] == 13 and in_array(665, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonProcesar"
              id="botonProcesar"
              value="Procesar"
                            style="display:none"
              onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
              class="button">
          <?
}
?>
               </td>
               <td id="celdaBotonAnular" >
                  <?
if ($_SESSION["modulo"] == 1 and in_array(736, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonAnular"
              id="botonAnular"
              value="Anular"
                            style="display:none"
              onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
              class="button">
           <?
} else if ($_SESSION["modulo"] == 13 and in_array(666, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonAnular"
              id="botonAnular"
              value="Anular"
                            style="display:none"
              onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
              class="button">
           <?
}
?>
               </td>
               <td id="celdaBotonDuplicar">
                  <?
if ($_SESSION["modulo"] == 1 and in_array(737, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonDuplicar"
              id="botonDuplicar"
              value="Duplicar"
                            style="display:none"
              onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
              class="button">
          <?
} else if ($_SESSION["modulo"] == 13 and in_array(667, $privilegios) == true) {
    ?>
          <input type="button"
              name="botonDuplicar"
              id="botonDuplicar"
              value="Duplicar"
                            style="display:none"
              onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
              class="button">
          <?
}
?>
               </td>
          </tr>
       </table>
     </td>
    </tr>
  </table>
</div>


<div id="divPreguntarUsuario" style="display:none; position:absolute; z-index:9; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:200px; margin-left:550px">
      <table align="center">
        <tr>
          <td align="right" colspan="2">
            <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
              <strong>x</strong>                                </a>                            </td>
        </tr>
        <tr>
          <td><strong>Usuario:</strong> </td>
            <td><?=$login?></td>
        </tr>
        <tr>
          <td><strong>Clave:</strong> </td>
            <td><input type="password" name="verificarClave" id="verificarClave"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="button" name="validar" id="validar" class="button" value="Anular" onClick="anularOrden(document.getElementById('id_orden_compra').value, document.getElementById('verificarClave').value)"></td>
        </tr>
    </table>
</div>







<div id="divTablaProveedores" style="display:block; position:absolute; left:50%; width:90%; margin-left:-560px; height:100px; height:auto !important; min-height:100px; margin-top:312px; overflow:auto">
<!-- CATEGORIAS PROGRAMATICAS-->
<table width="100%" align="center" style="display:none" id="tabla_categoria_programatica">
 <td align="right" style="background: #09F" width="25%"><strong>Categor&iacute;a Program&aacute;tica</strong></td>
      <td align="right" width="75%" ><select name="id_categoria_programatica" id="id_categoria_programatica">
        <option onclick="document.getElementById('buscarMaterial').style.display = 'none'">.:: Seleccione ::.</option>
        <?php
$sql_categorias = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo");
while ($rowcategoria_programatica = mysql_fetch_array($sql_categorias)) {
    ?>
        <option onclick="document.getElementById('buscarMaterial').style.display = 'block'" <?php echo 'value="' . $rowcategoria_programatica["idcategoria_programatica"] . '"';
    ?>> <?php echo $rowcategoria_programatica["codigo"] . " " . $rowcategoria_programatica["denominacion"]; ?> </option>
        <?php
}
?>
      </select></td>

</table>

<br />
<!-- MATERIALES-->
<table width="100%" align="center" style="display:none" id="tablaMaterialesPartidas">
        <tr>
        <td colspan="3">
        <table align="center" width="60%">
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
        <tr style="background: #09F">
          <td align="left" width="25%"><strong>CONCEPTOS</strong></td>
          <td align="right" width="90%">
            <span id="totales">
            <strong>Aportes:</strong> 0,00 |
            <strong>Asignaciones:</strong> 0,00 |
            <strong>Deducciones:</strong> 0,00 |
            <strong>Total Bs.:</strong> 0,00
            </span >
          </td>
          <td align="right" width="1%">
            <a href="javascript:;" onClick="abrirCerrarMateriales()" id="textoContraerMateriales">
            <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
    </tr>
        <tr>
          <td colspan="3" align="right">

            <form method="post" name="formMateriales" onsubmit="return ingresarMaterialIndividual(document.getElementById('id_orden_compra').value,
                                                   document.getElementById('id_material').value,
                                                   document.getElementById('cantidad').value,
                                                                     document.getElementById('precio_unitario').value,
                                                                     document.getElementById('id_categoria_programatica').value,
                                                                     document.getElementById('anio').value,
                                                                     document.getElementById('fuente_financiamiento').value,
                                                                     document.getElementById('tipo_presupuesto').value,
                                                                     document.getElementById('id_ordinal').value,
                                                                     document.getElementById('contribuyente_ordinario').value)">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" id="formularioMateriales" style="display:block">
                <tr>
                   <td>&nbsp;</td>
                   <td align="center" width="15%">C&oacute;digo:</td>
                   <td align="center" width="50%">Descipci&oacute;n:</td>
                   <td align="center" width="5%">Und:</td>
                   <td align="center" width="15%">Cantidad:</td>
                   <td align="center" width="15%">PU:</td>
                   <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><img src="imagenes/search0.png"
                          style="cursor:pointer; display:none"
                                title="Buscar Nuevo Material"
                                id="buscarMaterial"
                                name="buscarMaterial"
                                onClick="window.open('lib/listas/listar_materiales.php?destino=orden_compra',
                                          '',
                                                    'resizable = no, scrollbars=yes, width = 800, height= 400')">
                    </td>
                    <td><input name="codigo_material" type="text" disabled id="codigo_material" size="22"></td>
                      <input type="hidden" id="id_material" name="id_material">
                    <td><input name="descripcion_material" type="text" disabled id="descripcion_material" size="80"></td>
                    <td><input name="unidad_medida" type="text" disabled id="unidad_medida" size="10"></td>
                    <td><input name="cantidad" type="text" id="cantidad" size="18"></td>
                    <td><input name="precio_unitario" type="text" id="precio_unitario" size="22"></td>
                    <td><input type="image"
                                src="imagenes/validar.png"
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
          <td colspan="3" align="center">
            <div id="divMateriales" style="display:block">
              <strong>No hay Materiles Asociados</strong>
            </div>
          </td>
        </tr>
    </table>
       <!-- PROVEEDORES-->
       <br /><br />
<!-- PARTIDAS-->
  <table align="center" width="100%"  style="display:none" id="tablaPartidas">
         <tr style="background: #09F">
          <td align="left" width="27%"><strong>AFECTACI&Oacute;N PRESUPUESTARIA</strong></td>
          <td align="right" width="80%">
            <span id="totalPartidas"><strong>Total Bsf: </strong>0,00</span>
          </td>
          <td align="right" width="1%">
            <a href="javascript:;" onClick="abrirCerrarPartidas()" id="textoContraerPartidas">
              <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <div id="divPartidas" style="display:block">
              <strong>No hay Partidas Asociadas</strong>
            </div>
          </td>
        </tr>

    </tr>
      <!-- PARTIDAS-->
</table>
<br /><br />


     <table align="center" width="100%"  style="display:none" id="tablaContabilidad">
<!-- CONTABILIDAD -->
         <tr style="background: #09F">
          <td align="left"  width="25%"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
          <td align="right"  width="80%">&nbsp;</td>
          <td align="right"  width="1%">
                <a href="javascript:;" onClick="abrirCerrarContable()" id="textoContraerContable">
              <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <div id="divContable" style="display:block">
              <strong>  No se han Registrado Movimientos Contables para este documento </strong>
            </div>
          </td>
        </tr>
      <!-- CONTABILIDAD -->
</table>




<?
if ($_GET["idorden_compra"]) {
    ?>
  <script>
    consultarOrdenCompra(<?=$_GET["idorden_compra"]?>, 0);
    </script>
  <?
}
?>
