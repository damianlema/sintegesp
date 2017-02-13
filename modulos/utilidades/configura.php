<?php

$codigo = $_GET["c"];

$sql_configuracion = mysql_query("select * from configuracion where status='a'"
    , $conexion_db);
$registro_configuracion = mysql_fetch_assoc($sql_configuracion);

if (mysql_num_rows($sql_configuracion) == 0) {$modo = 0;} else {
    $id   = $registro_configuracion["idconfiguracion"];
    $modo = 1;}

$sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto
											where status='a'"
    , $conexion_db);

$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
												where status='a'"
    , $conexion_db);

if (!$_POST) {
    // si no se han enviado variables por el POST carga el formulario
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		document.oncontextmenu=new Function("return false")
		if (document.frmconfigura.denominacion.value.length==0){
			alert("Debe escribir una Denominaci&oacute;n para el Tipo de Presupuesto")
			document.frmconfigura.denominacion.focus()
			return false;
		}
	}

function solonumeros(e){
var key;
if(window.event)
	{key=e.keyCode;}
else if(e.which)
	{key=e.which;}
if (key < 48 || key > 57)
	{return false;}
return true;
}
// end hiding from old browsers -->
</SCRIPT>

</head>
	<body>
	<br>
	<h4 align=center>Configuraci&oacute;n de Datos B&aacute;sicos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	<form name="frmconfigura" action="principal.php?modulo=10&accion=63" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">
	<input type="hidden" id="codigo" name="codigo" maxlength="9" size="9" <?php if ($modo == 1 || $modo == 2) {echo 'value="' . $regtipo_presupuesto['idtipo_presupuesto'] . '"';}?>>
		<table align=center cellpadding=2 cellspacing=0 >
		  <tr>
		    <td width="298" align='right' class='viewPropTitle'>Entidad Propietaria:</td>
		    <td colspan="3" class=''><input type="text" name="entidad_propietaria" maxlength="250" size="120" <?php echo ' value="' . $registro_configuracion["entidad_propietaria"] . '"' ?>>            </td>
	      </tr>
          <tr>
		    <td width="298" align='right' class='viewPropTitle'>Nombre Instituci&oacute;n:</td>
		    <td colspan="3" class=''><input type="text" name="nombre_institucion" maxlength="200" size="120" <?php echo ' value="' . $registro_configuracion["nombre_institucion"] . '"' ?>>            </td>
	      </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>R.I.F. Instituci&oacute;n:</td>
				<td colspan="3" class=''><input type="text" name="rif" maxlength="15" size="15" <?php echo ' value="' . $registro_configuracion["rif"] . '"' ?>>				</td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Domicilio Legal:</td>
				<td colspan="3" class=''><textarea name="domicilio_legal" cols="130" rows="3"><?php echo $registro_configuracion["domicilio_legal"]; ?></textarea></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Ciudad:</td>
				<td class=''><input type="text" name="ciudad" maxlength="45" size="45" <?php echo ' value="' . $registro_configuracion["ciudad"] . '"' ?>></td>
			    <td class='viewPropTitle'><div align="right"><span class="viewPropTitle">Estado:</span></div></td>
			    <td class=''>

                <?
    $sql_estados = mysql_query("select * from estado");

    ?>
					<select name="estado" id="estado">
					<?
    while ($bus_estados = mysql_fetch_array($sql_estados)) {
        ?>
					<option <?if ($registro_configuracion["estado"] == $bus_estados["idestado"]) {echo "selected";}?> value="<?=$bus_estados["idestado"]?>">(<?=$bus_estados["codigo"]?>)&nbsp;<?=$bus_estados["denominacion"]?></option>
					<?
    }
    ?>
					</select>
                </td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Telefonos:</td>
				<td class=''><input type="text" name="telefonos" maxlength="45" size="45" <?php echo ' value="' . $registro_configuracion["telefonos"] . '"' ?>></td>
			    <td class='viewPropTitle'><div align="right"><span class="viewPropTitle">Fax:</span></div></td>
			    <td class=''><input type="text" name="fax" maxlength="45" size="45" <?php echo ' value="' . $registro_configuracion["fax"] . '"' ?>></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>P&aacute;gina Web:</td>
				<td class=''><input type="text" name="pagina_web" maxlength="45" size="45" <?php echo ' value="' . $registro_configuracion["pagina_web"] . '"' ?>></td>
			    <td class='viewPropTitle'><div align="right"><span class="viewPropTitle">C&oacute;digo Postal:</span></div></td>
			    <td class=''><input type="text" name="codigo_postal" maxlength="4" size="4" <?php echo ' value="' . $registro_configuracion["codigo_postal"] . '"' ?>></td>
			</tr>


			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena en Compras</td>
			  <td colspan="3" class=''>
              <input type="hidden" name="ci_ordena_compras" id="ci_ordena_compras" value="<?=$registro_configuracion["ci_ordena_compras"]?>">

              <select name="ordena_compras" id="ordena_compras">
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                <?=$bus_configuracion_administracion["primero_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                <?=$bus_configuracion_administracion["segundo_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                <?=$bus_configuracion_administracion["tercero_administracion"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                <?=$bus_configuracion_administracion["primero_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_compras"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["primero_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tributos"]?>
                </option>

                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                </option>

                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_obras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_obras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_obras"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_primero_obras"]?>'">
                  <?=$bus_configuracion_administracion["primero_obras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_obras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_obras"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_segundo_obras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_obras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_obras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_obras"]?>"
              onClick="document.getElementById('ci_ordena_compras').value='<?=$bus_configuracion_administracion["ci_tercero_obras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_obras"]?>
                </option>
              </select>              </td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Certificacion de Presupuesto</td>
			  <td colspan="3" class=''>
              <input type="hidden" name="ci_ordena_presupuesto" id="ci_ordena_presupuesto" value="<?=$registro_configuracion["ci_ordena_presupuesto"]?>">

              <select name="ordena_presupuesto" id="ordena_presupuesto">
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_adminiatracion"]?>'">
                <?=$bus_configuracion_administracion["primero_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_adminiatracion"]?>'">
                <?=$bus_configuracion_administracion["segundo_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_adminiatracion"]?>'">
                <?=$bus_configuracion_administracion["tercero_administracion"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                <?=$bus_configuracion_administracion["primero_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                <?=$bus_configuracion_administracion["segundo_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                <?=$bus_configuracion_administracion["tercero_compras"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                <?=$bus_configuracion_administracion["primero_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_contailidad"]?>'">
                <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
                <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
                <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
                <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
                <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                <?=$bus_configuracion_administracion["primero_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                <?=$bus_configuracion_administracion["segundo_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                <?=$bus_configuracion_administracion["tercero_tributos"]?>
                </option>


                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_presupuesto"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_presupuesto').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                </option>
              </select></td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Certificacion de Adminitracion</td>
			  <td colspan="3" class=''>
              <input type="hidden" name="ci_ordena_certificacion_administracion" id="ci_ordena_certificacion_administracion" value="<?=$registro_configuracion["ci_ordena_certificacion_adminiatracion"]?>">

              <select name="ordena_certificacion_administracion" id="ordena_certificacion_administracion">
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                <?=$bus_configuracion_administracion["primero_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                <?=$bus_configuracion_administracion["segundo_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                <?=$bus_configuracion_administracion["tercero_administracion"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_obras"]?>'">
                <?=$bus_configuracion_administracion["primero_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_Segundo_compras"]?>'">
                <?=$bus_configuracion_administracion["segundo_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                <?=$bus_configuracion_administracion["tercero_compras"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                <?=$bus_configuracion_administracion["primero_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
                <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
                <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
                <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
                <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                <?=$bus_configuracion_administracion["primero_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                <?=$bus_configuracion_administracion["segundo_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                <?=$bus_configuracion_administracion["tercero_tributos"]?>
                </option>


                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_certificacion_administracion"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_certificacion_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                </option>
              </select></td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Certificacion de RRHH</td>
			  <td colspan="3" class=''>



              <input type="hidden" name="ci_ordena_rrhh" id="ci_ordena_rrhh" value="<?=$registro_configuracion["ci_ordena_rrhh"]?>">
                <select name="ordena_rrhh" id="ordena_rrhh">
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["primero_administracion"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                  <?=$bus_configuracion_administracion["segundo_administracion"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["tercero_administracion"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                  <?=$bus_configuracion_administracion["primero_compras"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_compras"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_compras"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["primero_rrhh"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_tributos"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tributos"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tributos"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_rrhh"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_rrhh').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                  </option>
                </select>                </td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Certificacion de Nomina</td>
			  <td colspan="3" class=''>

              <input type="hidden" name="ci_ordena_nomina" id="ci_ordena_nomina" value="<?=$registro_configuracion["ci_ordena_nomina"]?>">


              <select name="ordena_nomina" id="ordena_nomina">
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                <?=$bus_configuracion_administracion["primero_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                <?=$bus_configuracion_administracion["segundo_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                <?=$bus_configuracion_administracion["tercero_administracion"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                <?=$bus_configuracion_administracion["primero_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_compras"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["primero_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tributos"]?>
                </option>

                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_nomina"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_nomina').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                </option>
              </select></td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Certificacion Despacho</td>
			  <td colspan="3" class=''>




              <input type="hidden" name="ci_ordena_despacho" id="ci_ordena_despacho" value="<?=$registro_configuracion["ci_ordena_despacho"]?>">
                <select name="ordena_despacho" id="ordena_despacho">
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["primero_administracion"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                  <?=$bus_configuracion_administracion["segundo_administracion"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["tercero_administracion"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                  <?=$bus_configuracion_administracion["primero_compras"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_compras"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_compras"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["primero_rrhh"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                  <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_tributos"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tributos"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tributos"]?>
                  </option>
                  <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_primero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                  </option>
                  <option <?if ($registro_configuracion["ordena_despacho"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_despacho').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                  </option>
                </select>                </td>
		  </tr>


          <tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Certificaci&oacute;n de Obras</td>
			  <td colspan="3" class=''>
              <input type="hidden" name="ci_ordena_obras" id="ci_ordena_obras" value="<?=$registro_configuracion["ci_ordena_compras"]?>">

              <select name="ordena_obras" id="ordena_obras">
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                <?=$bus_configuracion_administracion["primero_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                <?=$bus_configuracion_administracion["segundo_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                <?=$bus_configuracion_administracion["tercero_administracion"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                <?=$bus_configuracion_administracion["primero_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_compras"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["primero_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>'">
                  <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tributos"]?>
                </option>

                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                </option>

                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_obras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["primero_obras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_obras"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_primero_obras"]?>'">
                  <?=$bus_configuracion_administracion["primero_obras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["segundo_obras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_obras"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_segundo_obras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_obras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_compras"] == $bus_configuracion_administracion["tercero_obras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_obras"]?>"
              onClick="document.getElementById('ci_ordena_obras').value='<?=$bus_configuracion_administracion["ci_tercero_obras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_obras"]?>
                </option>
              </select>              </td>
		  </tr>







			<tr>
			  <td align='right' class='viewPropTitle'>Quien Ordena Pago</td>
			  <td colspan="3" class=''>

              <input type="hidden" name="ci_ordena_administracion" id="ci_ordena_administracion" value="<?=$registro_configuracion["ci_ordena_administracion"]?>">

              <select name="ordena_administracion" id="ordena_administracion">
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                <?=$bus_configuracion_administracion["primero_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_administracion"]) {
        echo "selected";
    }?>
              			id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                <?=$bus_configuracion_administracion["segundo_administracion"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_administracion"]) {
        echo "selected";
    }?>
               id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
               onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                <?=$bus_configuracion_administracion["tercero_administracion"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_compras") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_compras"]) {
        echo "selected";
    }?>
              		id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                    onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>'">
                <?=$bus_configuracion_administracion["primero_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>'">
                  <?=$bus_configuracion_administracion["segundo_compras"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_compras"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>'">
                  <?=$bus_configuracion_administracion["tercero_compras"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["primero_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_rrhh"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>'">
                  <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_contabilidad"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>'">
                  <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>'">
                  <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_presupuesto"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>'">
                  <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_tesoreria"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                </option>
                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>'">
                  <?=$bus_configuracion_administracion["segundo_tributos"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_tributos"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["tercero_tributos"]?>
                </option>

                <?
    $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho") or die(mysql_error());
    $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
    ?>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["primero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>'">
                  <?=$bus_configuracion_administracion["primero_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["segundo_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>'">
                  <?=$bus_configuracion_administracion["segundo_despacho"]?>
                </option>
                <option <?if ($registro_configuracion["ordena_administracion"] == $bus_configuracion_administracion["tercero_despacho"]) {
        echo "selected";
    }?>
              id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
              onClick="document.getElementById('ci_ordena_administracion').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>'">
                  <?=$bus_configuracion_administracion["tercero_despacho"]?>
                </option>
              </select></td>
		  </tr>
			<tr><td align='right' class='viewPropTitle'>Gobernador(a):</td>
				<td width="452" class=''><input type="text" name="gobernador" maxlength="65" size="65" <?php echo ' value="' . $registro_configuracion["gobernador"] . '"' ?>></td>
			    <td width="137" class=''><div align="right">C.I. No.:</div></td>
			    <td width="229" class=''><label>
			      <input name="ci_gobernador" type="text" id="ci_gobernador" size="15" maxlength="15" <?php echo ' value="' . $registro_configuracion["ci_gobernador"] . '"' ?>>
			    </label></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Contralor(a):</td>
				<td class=''><input type="text" name="contralora" maxlength="65" size="65" <?php echo ' value="' . $registro_configuracion["contralora"] . '"' ?>></td>
			    <td class=''><div align="right">C.I. No.:</div></td>
			    <td class=''><label>
			      <input name="ci_contralora" type="text" id="ci_contralora" size="15" maxlength="15" <?php echo ' value="' . $registro_configuracion["ci_contralor"] . '"' ?>>
			    </label></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Presidente(a) Consejo Legislativo:</td>
				<td class=''><input type="text" name="presidente_consejo_legislativo" maxlength="65" size="65" <?php echo ' value="' . $registro_configuracion["presidente_consejo_legislativo"] . '"' ?>></td>
			    <td class=''><div align="right">C.I. No.:</div></td>
			    <td class=''><label>
			      <input name="ci_presidente_consejo_legislativo" type="text" id="ci_presidente_consejo_legislativo" size="15" maxlength="15" <?php echo ' value="' . $registro_configuracion["ci_presidente_consejo_legislativo"] . '"' ?>>
			    </label></td>
			</tr>
			<tr><td align='right' class='viewPropTitle'>Director(a) de Presupuesto:</td>
				<td class=''><input type="text" name="director_presupuesto" maxlength="65" size="65" <?php echo ' value="' . $registro_configuracion["director_presupuesto"] . '"' ?>></td>
			    <td class=''><div align="right">C.I. No.:</div></td>
			    <td class=''><label>
			      <input name="ci_director_presupuesto" type="text" id="ci_director_presupuesto" size="15" maxlength="15" <?php echo ' value="' . $registro_configuracion["ci_director_presupuesto"] . '"' ?>>
			    </label></td>
			</tr>

			<tr>
				<td align='right' class='viewPropTitle'>A&ntilde;o Fiscal:</td>
				<td class='viewProp'>
                    <select name="anio" id="anio" style="width:20%;">
                        <?php
for ($i = 2008; $i < 2030; $i++) {
        if ($i == $registro_configuracion['anio_fiscal']) {
            echo "<option value='$i' selected>$i</option>";
        } else {
            echo "<option value='$i'>$i</option>";
        }

    }
    ?>
                    </select>
                </td>
            </tr>
			    <td class='viewPropTitle'><div align="right">Fecha de Cierre:</div></td>
			    <td class='viewProp'><input type="text" name="fecha_cierre" id="fecha_cierre" size="13" readonly value="<?=$registro_configuracion["fecha_cierre"]?>"/>
                  <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                  <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_cierre",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
			</tr>

			<tr>
			<td align='right' class='viewPropTitle'>Tipo de Presupuesto:</td>
			<td class='viewProp'>
				<select name="tipo_presupuesto" style="width:50%">
					<option>&nbsp;</option>
					<?php
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
        ?>
									<option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';
        if ($rowtipo_presupuesto["idtipo_presupuesto"] == $registro_configuracion["idtipo_presupuesto"]) {echo ' selected';}
        ?>>
										<?php echo $rowtipo_presupuesto["denominacion"]; ?>									</option>
					<?php
}
    ?>
				</select>			</td>
			<td class='viewPropTitle'>OrdenPago+Cheque:</td>
			<td class='viewProp'>

            <input type="checkbox" name="ordenpago_cheque" id="ordenpago_cheque" <?php if ($registro_configuracion['ordenpago_cheque'] == "1") {echo " checked";}?> value="1" <?if ($_SESSION["version"] == "basico") {echo "checked";}?>>

            </td>
			</tr>

			<tr>
			  <td align='right' class='viewPropTitle'>Fuente de Financiamiento:</td>
			  <td colspan="3" class='viewProp'><select name="fuente_financiamiento" style="width:50%">
                <option>&nbsp;</option>
                <?php
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
        ?>
                <option <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
        if ($rowfuente_financiamiento["idfuente_financiamiento"] == $registro_configuracion["idfuente_financiamiento"]) {echo ' selected';}
        ?>> <?php echo $rowfuente_financiamiento["denominacion"]; ?> </option>
                <?php
}
    ?>
              </select></td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Categoria Programatica:</td>
			  <td colspan="3" class='viewProp'><?
    $sql_categoria_programatica = mysql_query("select categoria_programatica.idcategoria_programatica,
																	categoria_programatica.codigo,
																	unidad_ejecutora.denominacion
																		from
																	categoria_programatica,
																	unidad_ejecutora
																		where
																	categoria_programatica.idunidad_ejecutora = unidad_ejecutora.idunidad_ejecutora
																	order by categoria_programatica.codigo asc") or die(mysql_error());
    ?>
                <select name="idcategoria_programatica">
                  <option value="0">.:: Seleccione ::.</option>
                  <?
    while ($bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica)) {
        ?>
                  <option <?if ($registro_configuracion["idcategoria_programatica"] == $bus_categoria_programatica["idcategoria_programatica"]) {echo "selected";}?> value="<?=$bus_categoria_programatica["idcategoria_programatica"]?>">(
                    <?=$bus_categoria_programatica["codigo"]?>
                    )
                    <?=$bus_categoria_programatica["denominacion"]?>
                  </option>
                  <?
    }
    ?>
              </select></td>
		  </tr>
			<tr>
			  <td align='right' class='viewPropTitle'>Mostrar Disponibilidades:</td>
			  <td colspan="3" class='viewProp'>
			    <input type="checkbox" name="disponibilidad" id="disponibilidad" value="1" <?if ($registro_configuracion["disponibilidad"] == 1) {echo "checked";}?>>
		      </td>
		  </tr>
		</table>
<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			<input type="submit" name="actualizar" id="actualizar" value="Actualizar">
			<input type="reset" name="reiniciar" id="reiniciar" value="Reiniciar">
			</td></tr>
		</table>
	</form>
	<br>


<script> document.frmconfigura.nombre_institucion.focus() </script>
</body>
</html>

<?php
} else {

    $nombre_institucion               = $_POST["nombre_institucion"];
    $rif                              = $_POST["rif"];
    $domicilio_legal                  = $_POST["domicilio_legal"];
    $ciudad                           = $_POST["ciudad"];
    $estado                           = $_POST["estado"];
    $telefonos                        = $_POST["telefonos"];
    $pagina_web                       = $_POST["pagina_web"];
    $fax                              = $_POST["fax"];
    $correo_electronico               = $_POST["correo_electronico"];
    $codigo_postal                    = $_POST["codigo_postal"];
    $gobernador                       = $_POST["gobernador"];
    $contralor                        = $_POST["contralora"];
    $presidente_consejo_legislativo   = $_POST["presidente_consejo_legislativo"];
    $director_presupuesto             = $_POST["director_presupuesto"];
    $cigobernador                     = $_POST["ci_gobernador"];
    $cicontralor                      = $_POST["ci_contralora"];
    $cipresidente_consejo_legislativo = $_POST["ci_presidente_consejo_legislativo"];
    $cidirector_presupuesto           = $_POST["ci_director_presupuesto"];
    $ciudad                           = $_POST["ciudad"];
    $entidad_propietaria              = $_POST["entidad_propietaria"];

    if ($_POST["ordenpago_cheque"]) {
        $ordenpago_cheque = 1;
    } else {
        $ordenpago_cheque = 0;
    }

    $anio                     = $_POST["anio"];
    $idtipo_presupuesto       = $_POST["tipo_presupuesto"];
    $idfuente_financiamiento  = $_POST["fuente_financiamiento"];
    $idcategoria_programatica = $_POST["idcategoria_programatica"];
    $fh                       = date("Y-m-d H:i:s");
    $fecha_cierre             = $_POST["fecha_cierre"];
    $ordenpago_cheque         = $_POST["ordenpago_cheque"];

    $pc = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    mysql_query("update configuracion set
										entidad_propietaria='" . $entidad_propietaria . "',
										nombre_institucion='" . $nombre_institucion . "',
										rif='" . $rif . "',
										domicilio_legal='" . $domicilio_legal . "',
										ciudad='" . $ciudad . "',
										estado='" . $estado . "',
										telefonos='" . $telefonos . "',
										pagina_web='" . $pagina_web . "',
										fax='" . $fax . "',
										correo_electronico='" . $correo_electronico . "',
										codigo_postal='" . $codigo_postal . "',

										ordena_requisicion = '" . $_POST["ordena_requisicion"] . "',
										ordena_compras = '" . $_POST["ordena_compras"] . "',
										ordena_nomina = '" . $_POST["ordena_nomina"] . "',
										ordena_administracion = '" . $_POST["ordena_administracion"] . "',
										ordena_presupuesto = '" . $_POST["ordena_presupuesto"] . "',
										ordena_certificacion_administracion = '" . $_POST["ordena_certificacion_administracion"] . "',
										ordena_despacho = '" . $_POST["ordena_despacho"] . "',
										ordena_rrhh = '" . $_POST["ordena_rrhh"] . "',
										ordena_obras = '" . $_POST["ordena_obras"] . "',

										ci_ordena_requisicion = '" . $_POST["ci_ordena_requisicion"] . "',
										ci_ordena_compras = '" . $_POST["ci_ordena_compras"] . "',
										ci_ordena_nomina = '" . $_POST["ci_ordena_nomina"] . "',
										ci_ordena_administracion = '" . $_POST["ci_ordena_administracion"] . "',
										ci_ordena_presupuesto = '" . $_POST["ci_ordena_presupuesto"] . "',
										ci_ordena_certificacion_administracion = '" . $_POST["ci_ordena_certificacion_administracion"] . "',
										ci_ordena_despacho= '" . $_POST["ci_ordena_despacho"] . "',
										ci_ordena_rrhh= '" . $_POST["ci_ordena_rrhh"] . "',
										ci_ordena_obras= '" . $_POST["ci_ordena_obras"] . "',


										gobernador='" . $gobernador . "',
										ci_gobernador='" . $ci_gobernador . "',
										contralor='" . $contralora . "',
										ci_contralor='" . $ci_contralora . "',
										presidente_consejo_legislativo='" . $presidente_consejo_legislativo . "',
										ci_presidente_consejo_legislativo='" . $ci_presidente_consejo_legislativo . "',
										director_presupuesto='" . $director_presupuesto . "',
										ci_director_presupuesto='" . $ci_director_presupuesto . "',
										anio_fiscal='" . $anio . "',
										idtipo_presupuesto='" . $idtipo_presupuesto . "',
										idfuente_financiamiento='" . $idfuente_financiamiento . "',

										estado='" . $estado . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "',
										fecha_cierre='" . $fecha_cierre . "',
										idcategoria_programatica = '" . $idcategoria_programatica . "',
										ordenpago_cheque = '" . $ordenpago_cheque . "',
										disponibilidad = '" . $disponibilidad . "'
											", $conexion_db) or die(mysql_error());
    registra_transaccion('Actualizar Configuracion De Utilidades', $login, $fh, $pc, 'configuracion', $conexion_db);
    mensaje("Se Actualizo la Configuracion con Exito");
    redirecciona("principal.php?modulo=10&accion=63");
}

?>
