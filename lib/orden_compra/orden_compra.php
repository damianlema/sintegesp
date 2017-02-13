<script src="js/orden_compra_ajax.js" type="text/javascript" language="javascript"></script>

<?/*
<h4 align=center>
<?
if($_SESSION["modulo"] == 4){
echo "Certificaci&oacute;n de Compromisos";
}else if($_SESSION["modulo"] == 3){
echo "Orden de Compra o Servicio";
}else if($_SESSION["modulo"] == "1"){
echo "Certificaci&oacute;n con Impuesto";
}else if($_SESSION["modulo"] == "2"){
echo "Certificaci&oacute;n de Compromisos";
}else if($_SESSION["modulo"] == "12"){
echo "Certificaci&oacute;n de Compromisos";
}else if($_SESSION["modulo"] == "14"){
echo "Certificaci&oacute;n de Compromisos";
}
?>
</h4>
<h2 class="sqlmVersion"></h2>
<? */
$sql_configuracion = mysql_query("select * from configuracion
                      where status='a'"
    , $conexion_db);
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
          <img src="imagenes/search0.png" title="Buscar Compromiso" style="cursor:pointer" onclick="window.open('lib/listas/listar_ordenes_compra.php?destino=ordenes','buscar orden compra servicio','resisable = no, scrollbars = yes, width=900, height = 500')" />
        </div>
      </td>
      <td align="right">
        <div align="center">
          <img src="imagenes/nuevo.png" title="Ingresar nuevo Compromiso" onclick="window.location.href='principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" />
        </div>
      </td>
      <td align="right">
        <div align="center" id="celdaRecalcular" style="display:none">
          <img src="imagenes/refrescar.png" title="Recalcular Compromiso" onClick="recalcular()" style="cursor:pointer" />
        </div>
      </td>
      <td align="right" >
        <div align="center" id="celdaImprimir" style="display:none">
          <img src="imagenes/imprimir.png" title="Imprimir Orden de Compra/Servicios"  onClick="pdf.location.href='lib/reportes/compras_servicios/reportes.php?modulo=<?=$_SESSION["modulo"]?>&nombre=ordencs&id_orden_compra='+document.getElementById('id_orden_compra').value; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block';" style="cursor:pointer" />
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

  <!-- TABLA DE DATOS BASICOS
<div id="divTablaProveedores" style="display:block; position:absolute; left:50%; width:1050px; margin-left:-525px; height:100px; height:auto !important; min-height:100px; margin-top:310px; overflow:auto">-->

<div id="tablaDatosBasicos" style="display:block; position:absolute; background-color:#EAEAEA; border:1px solid; left:50%; height:280px; width:90%; margin-left:-560px; margin-top:25px; overflow:auto">

  <table width="96%" border="0" align="center" cellpadding="0" cellspacing="2">
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
        <td colspan="8" style="border:1px solid #999; background-color:#FFF" id="celdaEstado" ><strong>&nbsp;En Elaboraci&oacute;n</strong></td>
    </tr>
    <tr>
      <td align="right" class='viewPropTitleNew'>Tipo:</td>
      <td colspan="8">
    <?
$sql_tipos_documentos = mysql_query("select * from tipos_documentos
                          where
                          compromete = 'si' and
                          causa = 'no' and
                          paga = 'no' and
                          modulo like '%-" . $_SESSION["modulo"] . "-%' and
                          multi_categoria = 'no' and
                          reversa_compromiso = 'no'");
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
      </td>
    </tr>

    <tr>
      <td align="right" class='viewPropTitleNew'>Proveedor:</td>
      <td colspan="4"><input name="nombre_proveedor" type="text" id="nombre_proveedor" size="120"  readonly="readonly"/>
            <input type="hidden" name="id_beneficiarios" id="id_beneficiarios" />
            <input type="hidden" name="contribuyente_ordinario" id="contribuyente_ordinario" />
      </td>
      <td align="left" colspan="4"><img style="display:block; cursor:pointer"
                                        src="imagenes/search0.png"
                                        title="Buscar Nuevo Proveedor"
                                        id="buscarProveedor"
                                        name="buscarProveedor"
                                        onclick="window.open('modulos/compromisos/lib/listar_beneficiarios.php?destino=ordenes','listar proveedores','resizable = no, scrollbars = yes, width=900, height = 500')" />
       </td>
    </tr>





    <tr>
      <td align="right" class='viewPropTitleNew'>Categor&iacute;a Program&aacute;tica:</td>
      <td colspan="4">
          <?
$sql_configuracion_categoria = mysql_query("select categoria_programatica.idcategoria_programatica,
                            unidad_ejecutora.denominacion
                              from
                             configuracion,
                             categoria_programatica,
                             unidad_ejecutora
                              where
                             categoria_programatica.idcategoria_programatica = configuracion.idcategoria_programatica
                             and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora") or die(mysql_error());
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
                                                 />
         </td>
         <td align="left" colspan="3">
            <a href="#" onClick="abrirCerrarDatosExtra()" id="textoContraerDatosExtra" style="font-size:10px">Origen Pptario</a>
         </td>
    </tr>

    <tr>
      <td colspan="9">
        <input type="hidden" id="id_ordinal" />
            <table width="100%" id="datosExtra" style="display:none">
                <tr>
                    <td align="right" class='viewPropTitleNew' width="21%">Fuente de Financiamiento</td>
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
                    <td align="right" ><select name="tipo_presupuesto" id="tipo_presupuesto">
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
      <td align="right" class='viewPropTitleNew'>Concepto:</td>
      <td colspan="8"><textarea name="justificacion" cols="125" rows="4" id="justificacion"></textarea>&nbsp;&nbsp;<a href="#" onClick="abrirCerrarObservaciones()" id="textoContraerObservaciones"><img border="0" src="imagenes/comments.png" title="Observaciones" style="text-decoration:none"></a></td>
    </tr>

    <tr>
      <td colspan="9">
        <table id="divObservaciones" style="display:none" width="100%">
                <tr>
                    <td align="right" class='viewPropTitleNew'>Observaciones:</td>
                    <td width="84%" colspan="7"><textarea name="observaciones" cols="135" rows="2" id="observaciones"></textarea></td>
                </tr>
            </table>
        </td>
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
      <?if ($_SESSION["modulo"] != '1') {?>
      <td align="right" class='viewPropTitleNew'>Requisici&oacute;n:</td>
      <?} else {?>
      <td align="right" class='viewPropTitleNew'>Memorandum:</td>
      <?}?>
      <td ><input type="text" name="numero_requisicion" id="numero_requisicion" /></td>
      <td align="right" class='viewPropTitleNew'>Fecha:</td>
      <td ><input type="text" name="fecha_requisicion" id="fecha_requisicion" size="13" readonly="readonly"/>
        <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_requisicion",
              button        : "f_trigger_c",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
              });
            </script></td>
        <td colspan="5" align="right"><a href="#" onClick="abrirCerrarDatosFactura()" id="textoContraerDatosFactura" style="font-size:10px">Datos de Facturacion</a></td>

    </tr>

    <tr>
      <td colspan="9">
            <table width="100%" id="datosFactura" style="display:none">
                <tr>
                  <td align="right" class='viewPropTitleNew' width="24%"><label>Nro. Factura</label></td>
                  <td ><input type="text" name="nro_factura" id="nro_factura" /></td>
                  <td align="right" class='viewPropTitleNew'><label>Nro. Control</label></td>
                  <td ><input type="text" name="nro_control" id="nro_control" /></td>
                  <td align="right" class="viewPropTitleNew">Fecha Factura</td>
                  <td ><input type="text" name="fecha_factura" id="fecha_factura" size="13" readonly="readonly"/>
                    <img src="imagenes/jscalendar0.gif" name="f_trigger_d" width="16" height="16" id="f_trigger_d" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
                    <script type="text/javascript">
                                        Calendar.setup({
                                        inputField    : "fecha_factura",
                                        button        : "f_trigger_d",
                                        align         : "Tr",
                                        ifFormat      : "%Y-%m-%d"
                                        });
                                    </script>
                  </td>
                </tr>
            </table>
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
                            onclick="ingresarDatosBasicos(document.getElementById('tipo_orden').value, document.getElementById('id_categoria_programatica').value, document.getElementById('justificacion').value, document.getElementById('observaciones').value, document.getElementById('ordenado_por').value, document.getElementById('cedula_ordenado').value, document.getElementById('numero_requisicion').value, document.getElementById('fecha_requisicion').value, document.getElementById('id_beneficiarios').value, document.getElementById('nro_factura').value, document.getElementById('fecha_factura').value, document.getElementById('nro_control').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('tipo_carga_orden').value)"
                            class="button" />
           </td>
           <td id="celdaBotonElaboracion">
              <input type="button"
                    name="botonEnElaboracion"
                    id="botonEnElaboracion"
                    value="En Elaboracion"
                    style="display:none"
                    onclick="actualizarDatosBasicos('actualizar'), consultarOrdenCompra(document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value)"
                    class="button">
           </td>
           <td id="celdaBotonProcesar">
           <?
if ($_SESSION["modulo"] == 4 and in_array(388, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonProcesar"
                        id="botonProcesar"
                        value="Procesar"
                        style="display:none"
                        onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 3 and in_array(399, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonProcesar"
                        id="botonProcesar"
                        value="Procesar"
                        style="display:none"
                        onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 1 and in_array(435, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonProcesar"
                        id="botonProcesar"
                        value="Procesar"
                        style="display:none"
                        onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 2 and in_array(507, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonProcesar"
                        id="botonProcesar"
                        value="Procesar"
                        style="display:none"
                        onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 12 and in_array(595, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonProcesar"
                        id="botonProcesar"
                        value="Procesar"
                        style="display:none"
                        onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 14 and in_array(786, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonProcesar"
                        id="botonProcesar"
                        value="Procesar"
                        style="display:none"
                        onclick="procesarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 19 and in_array(1028, $privilegios) == true) {
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
if ($_SESSION["modulo"] == 4 and in_array(389, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonAnular"
                        id="botonAnular"
                        value="Anular"
                        style="display:none"
                        onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                        class="button">
                 <?
} else if ($_SESSION["modulo"] == 3 and in_array(400, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonAnular"
                        id="botonAnular"
                        value="Anular"
                        style="display:none"
                        onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                        class="button">
                 <?
} else if ($_SESSION["modulo"] == 1 and in_array(436, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonAnular"
                        id="botonAnular"
                        value="Anular"
                        style="display:none"
                        onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                        class="button">
                 <?
} else if ($_SESSION["modulo"] == 2 and in_array(508, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonAnular"
                        id="botonAnular"
                        value="Anular"
                        style="display:none"
                        onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                        class="button">
                 <?
} else if ($_SESSION["modulo"] == 12 and in_array(596, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonAnular"
                        id="botonAnular"
                        value="Anular"
                        style="display:none"
                        onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                        class="button">
                 <?
} else if ($_SESSION["modulo"] == 14 and in_array(787, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonAnular"
                        id="botonAnular"
                        value="Anular"
                        style="display:none"
                        onClick="document.getElementById('divPreguntarUsuario').style.display = 'block'"
                        class="button">
                 <?
} else if ($_SESSION["modulo"] == 19 and in_array(1029, $privilegios) == true) {
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
if ($_SESSION["modulo"] == 4 and in_array(390, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonDuplicar"
                        id="botonDuplicar"
                        value="Duplicar"
                        style="display:none"
                        onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 3 and in_array(401, $privilegios) == true) {

    ?>
                <input type="button"
                        name="botonDuplicar"
                        id="botonDuplicar"
                        value="Duplicar"
                        style="display:none"
                        onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 1 and in_array(437, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonDuplicar"
                        id="botonDuplicar"
                        value="Duplicar"
                        style="display:none"
                        onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 2 and in_array(509, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonDuplicar"
                        id="botonDuplicar"
                        value="Duplicar"
                        style="display:none"
                        onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 12 and in_array(597, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonDuplicar"
                        id="botonDuplicar"
                        value="Duplicar"
                        style="display:none"
                        onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 14 and in_array(788, $privilegios) == true) {
    ?>
                <input type="button"
                        name="botonDuplicar"
                        id="botonDuplicar"
                        value="Duplicar"
                        style="display:none"
                        onclick="duplicarOrden(document.getElementById('id_orden_compra').value)"
                        class="button">
                <?
} else if ($_SESSION["modulo"] == 19 and in_array(1030, $privilegios) == true) {
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

<div id="divPreguntarUsuario" style="display:none; z-index:11; position:absolute; background-color:#CCCCCC; border:#000000 solid 1px; margin-top:200px; margin-left:550px">
     <table align="center">
        <tr>
          <td align="right" colspan="2">
            <a href="#" onClick="document.getElementById('divPreguntarUsuario').style.display='none'" title="Cerrar">
              <strong>x</strong>                                </a>                            </td>
        </tr>
        <tr>
          <td  width="70%"><strong>Fecha de Anulaci&oacute;n:</strong> </td>

            <td><input name="fecha_anulacion_compromiso" type="text" id="fecha_anulacion_compromiso" size="12" value="<?=date("Y-m-d")?>" disabled="disabled">

              <img src="imagenes/jscalendar0.gif" name="f_trigger_cf" width="16" height="16" id="f_trigger_cf" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_anulacion_compromiso",
              button        : "f_trigger_cf",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
              });
            </script>
           </td>
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
          <td colspan="2" align="center"><input type="button" name="validar" id="validar" class="button" value="Anular" onClick="anularOrden(document.getElementById('id_orden_compra').value, document.getElementById('verificarClave').value, document.getElementById('fecha_anulacion_compromiso').value)"></td>
        </tr>
  </table>
</div>




  <!-- TABLA DE DATOS BASICOS-->

 <input type="hidden" name="solicitudes" id="solicitudes">


  <!-- TABLA DE PROVEEDORES-->
<div id="divTablaProveedores" style="display:block; position:absolute; left:50%; width:90%; margin-left:-560px; height:100px; height:auto !important; min-height:100px; margin-top:310px; overflow:auto">

  <table align="center" id="proceso" style="display:none" width="100%">
    <tr style="background: #09F">
      <td align="left" width="25%"><strong>PROCESO:</strong><td>
      <td align="left" width="220" id="celdaProceso">
        <select name="sproceso" id="sproceso"  style="display:block">
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
                                            value="requisicion">Desde Requisici&oacute;n</option>
        </select>

      </td>
      <td align="right" width="780">&nbsp;</td>
      <td align="right" width="20">
        <a href="javascript:;" onClick="abrirCerrarProveedores()" id="textoContraerProveedores">
        <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">        </a>
      </td>
    </tr>
    <tr>
       <td colspan="4">
          <table width="100%" align="center" id="formularioProveedores" style=" display:block" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="20%" align="left" valign="top" >


              <div id="listaSolicitudesProveedor" style="background-color:#CCCCCC;
                                                                border:#000000 1px solid;
                                                                display:none;
                                                                width:180px;
                                                                height:250px;
                                                                overflow:auto;
                                                                cursor:pointer"></div>

               </td>
              <td width="80%" valign="top">
                  <div id="solicitudesSeleccionada" style="width:100%; display:none">
                  <center>No hay Solicitudes Seleccionadas</center>
                  </div>

             </td>
            </tr>
        </table>
     </td>
   </tr>
</table>


<br>

<!-- DATOS DE CONTRATACION
<div id="divTablaContratacion" style="display:block; position:absolute; left:50%; width:1050px; margin-left:-525px; height:100px; height:auto !important; min-height:100px; margin-top:310px; overflow:auto">
-->
<table align="center" id="tablaSNC" style="display:none" width="100%">
    <tr style="background: #09F">
      <td align="left" width="25%"><strong>DATOS DE CONTRATACI&Oacute;N</strong></td>
      <td align="right" width="90%">&nbsp;</td>
      <td align="right" width="5%">
        <a href="javascript:;" onClick="abrirCerrarSNC()" id="textoContraerSNC">
        <img border="0" src="imagenes/abrir.gif" style="text-decoration:none" title="Cerrar">        </a>
      </td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <?//<div id="divsnc" style="display:block"> ?>
        <div id="divsnc" style="display:none; position:absolute; background-color:#FF6; border:1px solid; left:50%; height:58px; width:1045px; margin-left:-522px; margin-top:1px; overflow:auto">
        <table>
        <tr>
          <td align="right">Fecha de Inicio:</td>
           <td><input type="text" name="fecha_inicio" id="fecha_inicio" style="width:80px; height:20px"/>
            <img src="imagenes/jscalendar0.gif" name="f_trigger_i" width="16" height="16" id="f_trigger_i" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
                    <script type="text/javascript">
                                        Calendar.setup({
                                        inputField    : "fecha_inicio",
                                        button        : "f_trigger_i",
                                        align         : "Tr",
                                        ifFormat      : "%Y-%m-%d"
                                        });
                                    </script>
           </td>
           <td align="right">Fecha de Cierre:</td>
           <td><input type="text" name="fecha_cierre" id="fecha_cierre" style="width:80px; height:20px"/>
              <img src="imagenes/jscalendar0.gif" name="f_trigger_f" width="16" height="16" id="f_trigger_f" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
                    <script type="text/javascript">
                                        Calendar.setup({
                                        inputField    : "fecha_cierre",
                                        button        : "f_trigger_f",
                                        align         : "Tr",
                                        ifFormat      : "%Y-%m-%d"
                                        });
                                    </script>
           </td>
            <td align="right">Decreto 4998:<input type="checkbox" id='4998' /></td>
            <td align="left">Decreto 4910:<input type="checkbox" id='4910' /></td>
        </tr>
        <tr>
           <td align="right">Modo de Comunicaci&oacute;n:</td>
           <td><label>
             <select name="modo_comunicacion" id="modo_comunicacion">
              <option value="invitacion">Invitaci&oacute;n</option>
                <option value="prensa">Prensa</option>
             </select>
           </label>
           </td>
           <td align="right">Actividad:</td>
           <td><label>
             <select name="tipo_actividad" id="tipo_actividad">
               <option value="bienes">Bienes</option>
               <option value="servicios">Servicios</option>
               <option value="obras">Obras</option>
             </select>
           </label>
           </td>
           <td align="right">Modalidad de Contrataci&oacute;n:</td>
           <td><label>
            <?
$sql_modalidad_contratacion = mysql_query("select * from modalidad_contratacion");
?>

            <select id="tipo_procedimiento" name="tipo_procedimiento">
               <?
while ($bus_modalidad_contratacion = mysql_fetch_array($sql_modalidad_contratacion)) {
    ?>

        <option value="<?=$bus_modalidad_contratacion["descripcion"]?>"><?=$bus_modalidad_contratacion["descripcion"]?></option>
        <?

}
?>
             </select>
            </label>
           </td>
           <td><input type="image" src="imagenes/validar.png"
                                                title="Actualizar Datos de Contrataci&oacute;n"
                                                id="procesarSNC"
                                                name="procesarSNC"
                                                onclick="procesarSNC()">
           </td>
        </tr>
        </table>

        </div>
      </td>
    </tr>
</table>

<!-- DATOS DE CONTRATACION -->
<br />



<!-- MATERIALES

<div id="divTablaMateriales" style="display:block; position:absolute; left:50%; width:1050px; margin-left:-525px; height:100px; height:auto !important; min-height:100px; margin-top:410px; overflow:auto">-->

  <table align="center" style="display:none" id="tablaMaterialesPartidas" width="100%" >
       <tr>
        <td colspan="3">
          <table align="center" width="70%">
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
      <td align="left" width="25%"><strong>MATERIALES</strong></td>
      <td align="right" width="74%">
        <span id="totales">
        <strong>Exento:</strong> 0,00 |
        <strong>Sub Total:</strong> 0,00 |
        <strong>Impuesto:</strong> 0,00 |
        <strong>Total Bsf:</strong> 0,00
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

        <form name="formularioIngresarMateriales" onsubmit="return ingresarMaterialIndividual(document.getElementById('id_orden_compra').value, document.getElementById('id_material').value, document.getElementById('cantidad').value, document.getElementById('precio_unitario').value, document.getElementById('id_categoria_programatica').value, document.getElementById('anio').value, document.getElementById('fuente_financiamiento').value, document.getElementById('tipo_presupuesto').value, document.getElementById('id_ordinal').value, document.getElementById('contribuyente_ordinario').value)" id="formularioMateriales"
              style="display:none">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
            <tr>
               <td rowspan="2">
                &nbsp;<img src="imagenes/search0.png"
                      style="cursor:pointer"
                            title="Buscar Nuevo Material"
                            id="buscarMaterial"
                            name="buscarMaterial"
                            onClick="window.open('lib/listas/listar_materiales.php?destino=orden_compra',
                                      '',
                                                'resizable = no, scrollbars=yes, width = 800, height= 400')">
              </td>
              <td align="center" width="15%">C&oacute;digo:</td>
              <td align="center" width="60%">Descripci&oacute;n:</td>
              <td align="center" width="5%">Und:</td>
              <td align="center" width="10%">Cantidad:</td>
              <td align="center" width="10%">PU:</td>
              <td rowspan="2">&nbsp;<input type="image" src="imagenes/validar.png"
                                            title="Procesar Material"
                                            id="procesarMaterial"
                                            name="procesarMaterial">
              </td>
           </tr>
           <tr>
              <td>
                <input name="codigo_material" type="text" disabled id="codigo_material" style="width:100%">
                <input type="hidden" id="id_material" name="id_material">
                </td>
                <td style="border:1px"><input name="descripcion_material" type="text" disabled id="descripcion_material" style="width:100%"></td>
                <td><input name="unidad_medida" type="text" disabled id="unidad_medida" style="width:100%"></td>
                <td><input name="cantidad" type="text" id="cantidad" style="width:100%"></td>
                <td><input name="precio_unitario" type="text" id="precio_unitario" style="width:100%"></td>
           </tr>
        </table>
        </form>

      </td>
    </tr>
        <tr>
          <td colspan="3" align="center">
            <div id="divMateriales" style="display:block">
              <strong>No se han registrado Materiales, Servicios u Otro Suministro en este documento</strong>
            </div>
          </td>
        </tr>
       <!-- PROVEEDORES-->
      </table>

  <br /> <br>

<!--  <div id="divTablaPartidas" style="display:block; position:absolute; left:50%; width:1050px; margin-left:-525px; height:100px; height:auto !important; min-height:100px; margin-top:610px; overflow:auto">-->
     <table align="center" width="100%"  style="display:none" id="tablaPartidas">
<!-- PARTIDAS-->
         <tr style="background: #09F">
          <td align="left" width="25%"><strong>AFECTACI&Oacute;N PRESUPUESTARIA</strong></td>
          <td align="right" width="90%">
            <span id="totalPartidas"><strong>Total Bsf: </strong>0,00</span>
          </td>
          <td align="right" width="5%">
            <a href="javascript:;" onClick="abrirCerrarPartidas()" id="textoContraerPartidas">
              <img border="0" src="imagenes/cerrar.gif" style="text-decoration:none" title="Cerrar">
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <div id="divPartidas" style="display:block">
              <strong>  No se han registrado Partidas Presupuestarias para este documento </strong>
            </div>
          </td>
        </tr>
  <!-- PARTIDAS-->
</table>
<br /> <br>


     <table align="center" width="100%"  style="display:none" id="tablaContabilidad">
<!-- CONTABILIDAD -->
         <tr style="background: #09F">
          <td align="left"  width="25%"><strong>AFECTACI&Oacute;N CONTABLE</strong></td>
          <td align="right"  width="90%">&nbsp;</td>
          <td align="right"  width="5%">
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

 </div>