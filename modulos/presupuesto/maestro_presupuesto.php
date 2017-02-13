<?php
include "../../../funciones/funciones.php";
if ($_POST["ingresar"]) {
    $_GET["accion"] = 243;
}

$buscar_registros  = $_GET["busca"]; // valida si se mando a buscar algun texto 0 muestra todos los registros 1 - 2 filtra segun lo buscado
$existen_registros = 0; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen
$codigo            = $_GET["c"];
$emergente         = $_POST["emergente"];
$juntos            = 0;

$sql_configuracion = mysql_query("select * from configuracion
											where status='a'"
    , $conexion_db);
$registro_configuracion = mysql_fetch_assoc($sql_configuracion);

$anio_fijo                    = $registro_configuracion["anio_fiscal"];
$idtipo_presupuesto_fijo      = $registro_configuracion["idtipo_presupuesto"];
$idfuente_financiamiento_fijo = $registro_configuracion["idfuente_financiamiento"];

$m = $_POST["modoactual"];
if ($m != "") {
    $modo = $m;
}

$sql_tipo_presupuesto = mysql_query("select * from tipo_presupuesto
											where status='a'"
    , $conexion_db);

$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
												where status='a'"
    , $conexion_db);

if ($_POST["idcategoria_programatica"] != "") {
    $sql_validar_categoria = mysql_query("select
											unidad_ejecutora.denominacion as denocategoriaprogramatica,
											unidad_ejecutora.codigo as codigounidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from unidad_ejecutora,categoria_programatica
												where
													unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and categoria_programatica.idcategoria_programatica=" . $_POST["idcategoria_programatica"] . "
												and categoria_programatica.status='a'");

    if (mysql_num_rows($sql_validar_categoria) > 0) {
        $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);
    }
}

if ($_POST["partida"] != "" and $_POST["generica"] != "" and $_POST["especifica"] != "" and $_POST["sub_especifica"] != "") {

    $sql_validar_partida = mysql_query("select * from clasificador_presupuestario
														where partida=" . $_POST["partida"] . "
															and generica=" . $_POST["generica"] . "
															and especifica=" . $_POST["especifica"] . "
															and sub_especifica=" . $_POST["sub_especifica"] . "
															and status='a'");
    if (mysql_num_rows($sql_validar_partida) > 0) {
        $regclasificador_presupuestario = mysql_fetch_assoc($sql_validar_partida);
    }
}

if ($_POST["ordinal"] != "") {

    $sql_validar_ordinal = mysql_query("select * from ordinal
														where codigo=" . $_POST["ordinal"] . "
															and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_ordinal) > 0) {
        $regordinal = mysql_fetch_assoc($sql_validar_ordinal);
    }
}

if ($_POST["idmaestropresupuesto"] != "") {
    // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario
    $encontroRegistros = 1;
    $sql               = mysql_query("select * from maestro_presupuesto
										where idRegistro like '" . $_POST['idmaestropresupuesto'] . "'"
        , $conexion_db);

    $sql = "select 		tipo_presupuesto.denominacion as denotipo_presupuesto,
						clasificador_presupuestario.codigo_cuenta as codigopartida,
						clasificador_presupuestario.denominacion as denopartida,
						categoria_programatica.codigo as codigocategoria,
						ordinal.codigo as codigoordinal,
						ordinal.denominacion as denoordinal,
						fuente_financiamiento.denominacion as denofuente_financiamiento,
						maestro_presupuesto.monto_actual as monto_actual,
						maestro_presupuesto.total_disminucion as total_disminucion,
						maestro_presupuesto.reservado_disminuir as reservado_disminuir,
						maestro_presupuesto.solicitud_aumento as solicitud_aumento,
						maestro_presupuesto.total_aumento as total_aumento,
						maestro_presupuesto.monto_original as monto_original,
						maestro_presupuesto.total_compromisos as total_compromisos,
						maestro_presupuesto.pre_compromiso as pre_compromiso,
						maestro_presupuesto.total_causados as total_causados,
						maestro_presupuesto.total_pagados as total_pagados,
						maestro_presupuesto.anio as anio,
						maestro_presupuesto.idRegistro as idRegistro_maestro,
						maestro_presupuesto.monto_actual-maestro_presupuesto.total_compromisos as disponible,
						maestro_presupuesto.idcategoria_programatica as idcategoria_programatica,
						maestro_presupuesto.idtipo_presupuesto as idtipo_presupuesto,
						maestro_presupuesto.idfuente_financiamiento as idfuente_financiamiento,
						maestro_presupuesto.idclasificador_presupuestario as idclasificador_presupuestario,
						maestro_presupuesto.idordinal as idordinal
						from maestro_presupuesto,tipo_presupuesto,clasificador_presupuestario,categoria_programatica,fuente_financiamiento,ordinal
							where maestro_presupuesto.status='a'
								and maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto
								and maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario
								and maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento
								and maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica
								and maestro_presupuesto.idordinal=ordinal.idordinal
								and maestro_presupuesto.idRegistro like '" . $_POST['idmaestropresupuesto'] . "'";
    $sql_maestro            = mysql_query($sql, $conexion_db);
    $regmaestro_presupuesto = mysql_fetch_assoc($sql_maestro);

    $sql_validar_categoria = mysql_query("select
											unidad_ejecutora.denominacion as denocategoriaprogramatica,
											unidad_ejecutora.codigo as codigounidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from unidad_ejecutora,categoria_programatica
												where
													unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and categoria_programatica.idcategoria_programatica=" . $regmaestro_presupuesto["idcategoria_programatica"] . "
												and categoria_programatica.status='a'"
        , $conexion_db);

    if (mysql_num_rows($sql_validar_categoria) > 0) {
        $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);
    }

    $sql_validar_partida = mysql_query("select * from clasificador_presupuestario
														where idclasificador_presupuestario=" . $regmaestro_presupuesto["idclasificador_presupuestario"] . "
															and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_partida) > 0) {
        $regclasificador_presupuestario = mysql_fetch_assoc($sql_validar_partida);
    }

    $sql_validar_ordinal = mysql_query("select * from ordinal
														where idordinal=" . $regmaestro_presupuesto["idordinal"] . "
															and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_ordinal) > 0) {
        $regordinal = mysql_fetch_assoc($sql_validar_ordinal);
    }
}

if ($emergente and $modo != 0) {
    $juntos = 1;
}

if (isset($_POST["mantener_anio"])) {
    echo '<input type="hidden" name="anio_actual" id="anio_actual" value="' . $_POST["anio"] . '">';
}

//if (!isset($_POST["accion"]) AND !isset($_POST["accionm"]) AND !isset($_POST["accione"])){ // si no se han enviado variables por el POST carga el formulario
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8">
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=lib/cerrar.php"> -->
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>

<SCRIPT language=JavaScript>

<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmmaestro_presupuesto.codigo.value.length==0){
			alert("Debe escribir un Codigo para el Sector")
			document.frmmaestro_presupuesto.codigo.focus()
			return false;
		}
		if (document.frmmaestro_presupuesto.denominacion.value.length==0){
			alert("Debe escribir una Denominacion para el Sector")
			document.frmmaestro_presupuesto.denominacion.focus()
			return false;
		}
	}

function abreVentanaPresupuesto(){
	m=document.frmmaestro_presupuesto.modoactual.value;
	miPopup=window.open("lib/listas/lista_presupuestos.php?m="+m,"presupuestos","width=1200,height=600,scrollbars=yes")
	miPopup.focus()
}

function abreVentanaC(){
	m=document.frmmaestro_presupuesto.modoactual.value;
	miPopup=window.open("lib/listas/lista_categorias_programaticas.php?m="+m,"categoria_programatica","width=900,height=500,scrollbars=yes")
	miPopup.focus()
}

function abreVentanaP(){
	m=document.frmmaestro_presupuesto.modoactual.value;
	miPopup=window.open("lib/listas/lista_partidas.php?m="+m,"partidas","width=900,height=500,scrollbars=yes")
	miPopup.focus()
}

function abreVentanaO(){
	m=document.frmmaestro_presupuesto.modoactual.value;
	miPopup=window.open("lib/listas/lista_ordinal.php?m="+m+"&destino=maestro_presupuesto","ordinales","width=600,height=400,scrollbars=yes")
	miPopup.focus()
}


function formatoNumero(idcampo) {
var frm = document.frmmaestro_presupuesto;
var res =  frm.elements[idcampo].value;
frm.elements["id"+idcampo].value = res;
if (frm.elements[idcampo].value>0 && frm.elements[idcampo].value<99999999999)  {
	resultado = parseFloat(res).toFixed(2).toString();
	resultado = resultado.split(".");
	var cadena = ""; cont = 1
	for(m=resultado[0].length-1; m>=0; m--){
		cadena = resultado[0].charAt(m) + cadena
		cont%3 == 0 && m >0 ? cadena = "." + cadena : cadena = cadena
		cont== 3 ? cont = 1 :cont++
	}
	frm.elements[idcampo].value = cadena + "," + resultado[1];
	frm.monto_actual.value = cadena + "," + resultado[1];
} else {
	frm.elements[idcampo].value = 0;
	alert ("Debes indicar valores numericos en el campo "+idcampo);
	frm.elements[idcampo].focus();
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
<style type="text/css">
a:link {
color: #0000CC;
}
a:hover {
color: #006666;
}
a:active {
color: #0000ff;
}
</style>

</head>
	<body>
	<br>
	<h4 align=center>Maestro de Presupuesto</h4>
	<h2 class="sqlmVersion"></h2>


	<?PHP
//echo $regmaestro_presupuesto["monto_original"];
//echo "anio".$_POST["mantener_anio"];
//echo "Ordinal ".$_POST["ordinal"];
//echo "idOrdinal ".$_POST["idordinal"];
//echo "Codigo ".$regordinal["codigo"];
//echo $modo;
?>
	<form name="frmmaestro_presupuesto" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $modo . '"'; ?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="' . $guardo . '"'; ?>>
	<input type="hidden" name="nro" id="nro" <?php echo 'value="' . $nro_solicitud_credito . '"'; ?>>

	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="' . $juntos . '"'; ?>>
		<table align=center cellpadding=2 cellspacing=0 width="60%">
			<tr>
			  <td colspan="2" align='right'><div align="center"><span class="viewProp"><img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaPresupuesto()" title="Buscar Partidas en Maestro Presupuesto"> &nbsp;<a href="principal.php?modulo=2&accion=46"><img src="imagenes/nuevo.png" border="0" title="Nuevo Registro"></a></span></div></td>
		  </tr>
			<tr>
				<td align='right' class='viewPropTitle'>A&ntilde;o:</td>
				<td class='viewProp'>
				<select name="anio" style="width:12%">
                        <?
anio_fiscal();
?>
				</select>&nbsp;</td>
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
    if ($_GET["accion"] != 244 and $_GET["accion"] != 245 and $rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {echo ' selected';}
    if ($_GET["accion"] == 244 or $_GET["accion"] == 245) {if ($rowtipo_presupuesto["idtipo_presupuesto"] == $regmaestro_presupuesto["idtipo_presupuesto"]) {echo ' selected';}}
    if (isset($_POST["tipo_presupuesto"])) {if ($rowtipo_presupuesto["idtipo_presupuesto"] == $_POST["tipo_presupuesto"]) {echo ' selected';}}
    ?>>
										<?php echo $rowtipo_presupuesto["denominacion"]; ?>									</option>
					<?php
}
?>
				</select>			</td>
			</tr>

			<tr>
			<td align='right' class='viewPropTitle'>Fuente de Financiamiento:</td>
			<td class='viewProp'>
				<select name="fuente_financiamiento" style="width:50%">
					<option>&nbsp;</option>
					<?php
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
									<option <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
    if ($_GET["accion"] != 244 and $_GET["accion"] != 245 and $rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {echo ' selected';}
    if ($_GET["accion"] == 244 or $_GET["accion"] == 245) {if ($rowfuente_financiamiento["idfuente_financiamiento"] == $regmaestro_presupuesto["idfuente_financiamiento"]) {echo ' selected';}}
    if (isset($_POST["fuente_financiamiento"])) {if ($rowfuente_financiamiento["idfuente_financiamiento"] == $_POST["fuente_financiamiento"]) {echo ' selected';}}
    ?>>
										<?php echo $rowfuente_financiamiento["denominacion"]; ?>									</option>
					<?php
}
?>
				</select>			</td>
			</tr>
			<tr>

             <?
$sql_configuracion_categoria = mysql_query("select categoria_programatica.idcategoria_programatica,
														unidad_ejecutora.denominacion,
														categoria_programatica.codigo
															from
														 configuracion,
														 categoria_programatica,
														 unidad_ejecutora
															where
														 categoria_programatica.idcategoria_programatica = configuracion.idcategoria_programatica
														 and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora") or die(mysql_error());
$bus_configuracion_categoria = mysql_fetch_array($sql_configuracion_categoria);
$categoria_fija              = mysql_num_rows($sql_configuracion_categoria);
?>
				<td align='right' class='viewPropTitle'>Categoria Program&aacute;tica:</td>
				<td class=''><input type="text" name="codcategoria_programatica" id="codcategoria_programatica" maxlength="14" size="16"
					<?php if ($categoria_fija > 0) {
    echo ' value="' . $bus_configuracion_categoria["codigo"] . '"';
} else {
    echo ' value="' . $regcategoria_programatica["codigo"] . '"';
}if ($_GET["accion"] == 245 or $categoria_fija > 0) {
    echo "disabled";
}
?>>
				<?php
if ($_GET["accion"] != 245 and $categoria_fija == 0) {?>
	                <img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaC()" title="Buscar Categorias Programaticas">
				<?php }?>			  </td>
			</tr>

			<tr>
				<td align='right' class='viewPropTitle'>&nbsp;</td>

			<td class=''><input type="text" name="denocategoria_programatica" id="denocategoria_programatica" maxlength="80" size="80"
				<?php if ($categoria_fija > 0) {
    echo ' value="' . utf8_decode($bus_configuracion_categoria["denominacion"]) . '"';
} else {
    echo ' value="' . utf8_decode($regcategoria_programatica["denocategoriaprogramatica"]) . '"';
}
if ($_GET["accion"] == 245 or $categoria_fija > 0) {
    echo "disabled";
}
?>>
                </td>
            </tr>
		</table>
<table align=center cellpadding=4 cellspacing=0 width="60%">
			<tr>
				<td align='right' class='viewPropTitle'>Partida:</td>
				<td>
					<input type="text" name="partida" id="partida" maxlength="3" size="3" onKeyPress="javascript: if(document.frmmaestro_presupuesto.partida.value.length == 2){document.frmmaestro_presupuesto.generica.focus()}"
							<?php echo ' value="' . $regclasificador_presupuestario["partida"] . '"';if ($_GET["accion"] == 245) {
    echo "disabled";
}
?>>
					<input type="text" name="generica" id="generica" maxlength="2" size="2" onKeyPress="javascript: if(document.frmmaestro_presupuesto.generica.value.length == 1){document.frmmaestro_presupuesto.especifica.focus()}" <?php echo ' value="' . $regclasificador_presupuestario["generica"] . '"';if ($_GET["accion"] == 245) {
    echo "disabled";
}
?>>
					<input type="text" name="especifica" id="especifica" maxlength="2" size="2" onKeyPress="javascript: if(document.frmmaestro_presupuesto.especifica.value.length == 1){document.frmmaestro_presupuesto.sub_especifica.focus()}" <?php echo ' value="' . $regclasificador_presupuestario["especifica"] . '"';if ($_GET["accion"] == 245) {
    echo "disabled";
}
?>>
					<input type="text" name="sub_especifica" id="sub_especifica" maxlength="2" size="2" onKeyPress="javascript: if(document.frmmaestro_presupuesto.sub_especifica.value.length == 1){document.frmmaestro_presupuesto.validar_partida.focus()}" <?php echo ' value="' . $regclasificador_presupuestario["sub_especifica"] . '"';if ($_GET["accion"] == 245) {
    echo "disabled";
}
?>>

					<?php if ($_GET["accion"] != 245) {?>

						<button name="validar_partida" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="this.form.submit()"><img src='imagenes/validar.png' ></button>
                        <a href="#" onClick="window.open('lib/listas/listar_clasificador_presupuestario.php?destino=maestro','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')"><img src="imagenes/search0.png" title="Buscar Clasificador Presupuestario"> </a>

                       <?/*
<img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaP()" title="Buscar Partida en el Clasificador Presupuestario">
<?php */}?>
				</td>
			</tr>

			<tr>
				<td align='right' class='viewPropTitle'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td class='' colspan="5"><input type="text" name="denopartida" id="denopartida" maxlength="80" size="80"  <?php echo ' value="' . $regclasificador_presupuestario["denominacion"] . '"';if ($_GET["accion"] == 245) {
        echo "disabled";
    }
    ?>>
			</tr>

		</table>

		<table align=center cellpadding=4 cellspacing=0 width="60%">
			<tr>
				<td align='right' class='viewPropTitle'>Ordinal:</td>
				<td>
					<input type="text" name="ordinal" id="ordinal" maxlength="4" size="4" <?php if ($_POST["ordinal"] != "") {
        echo ' value="' . $regordinal["codigo"] . '"';} else {echo ' value="' . "0000" . '"';}if ($_GET["accion"] == 245) {
        echo "disabled";
    }
    ?>>

					<?php if ($_GET["accion"] != 245) {?>
						<button name="validar_ordinal" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="this.form.submit()"><img src='imagenes/validar.png' ></button>
                        <img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaO()" title="Buscar Ordinales">
					<?php }?>
				</td>
			</tr>

			<tr>
				<td align='right' class='viewPropTitle'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td class='' colspan="5"><input type="text" name="denoordinal" id="denoordinal" maxlength="255" size="80"  <?php if ($encontroRegistros) {echo ' value="' . utf8_decode($regordinal["denominacion"]) . '"';} else {echo ' value="' . utf8_decode($regordinal["denominacion"]) . '"';}if ($_GET["accion"] == 245) {
        echo "disabled";
    }
    ?>>
			</tr>

		</table>

			<br>
			<table align=center cellpadding=4 cellspacing=0 width="40%">

			<tr>
				<td align='right' class='viewPropTitle'>Monto Original:</td>
				<td class=''><input type="text" style="text-align:right" name="monto_original" maxlength="20" size="20" id="monto_original"
										<?php
echo ' value="' . number_format($regmaestro_presupuesto['monto_original'], 2, ",", ".") . '"';
    if ($_GET["accion"] == 245) {
        echo " disabled";
    }

    ?>
												onblur="formatoNumero(this.name)"></td>
			</tr>
			<tr>
              <td align='right' class='viewPropTitle'>Aumentos Acumulados:</td>
			  <td class=''><input type="text" style="text-align:right" name="aumentos" maxlength="20" size="20" id="aumentos"
											<?php if (!$encontroRegistros) {echo ' value="0.00"';} else {
        echo 'value="' . number_format($regmaestro_presupuesto['total_aumento'], 2, ",", ".") . '"';}
    echo " disabled";?> ></td>
			  </tr>
			<tr>
              <td align='right' class='viewPropTitle'>Aumentos en Solicitud:</td>
			  <td class=''><input name="aumentos_solicitud" type="text" id="aumentos_solicitud" style="text-align:right" size="20" maxlength="20"
											<?php if (!$encontroRegistros) {echo ' value="0.00"';} else {
        echo 'value="' . number_format($regmaestro_presupuesto['solicitud_aumento'], 2, ",", ".") . '"';}
    echo " disabled";?> ></td>
			  </tr>
			<tr>
              <td align='right' class='viewPropTitle'>Disminuciones Acumuladas:</td>
			  <td class=''><input type="text" color="red" style="text-align:right" name="disminuciones" maxlength="20" size="20" id="disminuciones"
											<?php if (!$encontroRegistros) {echo ' value="0.00"';} else {
        echo 'value="' . number_format($regmaestro_presupuesto['total_disminucion'], 2, ",", ".") . '"';}
    echo " disabled";?> ></td>
			  </tr>
			<tr>
				<td align='right' class='viewPropTitle'>Reservado por Disminuir:</td>
				<td class=''><input name="reservado_disminuir" type="text" id="reservado_disminuir" style="text-align:right" size="20" maxlength="20"
											<?php if (!$encontroRegistros) {echo ' value="0.00"';} else {
        echo 'value="' . number_format($regmaestro_presupuesto['reservado_disminuir'], 2, ",", ".") . '"';}
    echo " disabled";?> ></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Monto Actual:</td>
				<td class=''><input type="text" style="text-align:right" name="monto_actual" maxlength="20" size="20" id="monto_actual"
											<?php //if ($modo==0) { echo ' value="0.00"';}
    echo 'value="' . number_format($regmaestro_presupuesto['monto_actual'], 2, ",", ".") . '"';
    echo " disabled"; ?> ></td>
			</tr>
		</table>
<br>

		<input type="hidden" name="idmonto_original" maxlength="25" size="25" id="idmonto_original"
											<?php if ($_GET["accion"] == 244) {
        if ($_POST["monto_original"] == "") {echo ' value="' . $regmaestro_presupuesto['monto_original'] . '"';} else {echo ' value="' . $_POST['idmonto_original'] . '"';}
    }
    ?>>
		<input type="hidden" name="idtotal_aumento" maxlength="25" size="25" id="idtotal_aumento"
											<?php if ($_GET["accion"] == 244) {
        if ($_POST["idtotal_aumento"] == "") {echo ' value="' . $regmaestro_presupuesto['total_aumento'] . '"';} else {echo ' value="' . $_POST['idtotal_aumento'] . '"';}
    }
    ?>>
		<input type="hidden" name="idtotal_disminucion" maxlength="25" size="25" id="idtotal_disminucion"
											<?php if ($_GET["accion"] == 244) {
        if ($_POST["idtotal_disminucion"] == "") {echo ' value="' . $regmaestro_presupuesto['total_disminucion'] . '"';} else {echo ' value="' . $_POST['idtotal_disminucion'] . '"';}
    }
    ?>>
		<input type="hidden" name="idmonto_actual" maxlength="25" size="25" id="idmonto_actual"
											<?php if ($_GET["accion"] == 244) {
        if ($_POST["idmonto_actual"] == "") {echo ' value="' . $regmaestro_presupuesto['monto_actual'] . '"';} else {echo ' value="' . $_POST['idmonto_actual'] . '"';}
    }
    ?>>
		<input type="hidden" name="idcategoria_programatica" maxlength="5" size="5" id="idcategoria_programatica" <?php
if ($categoria_fija > 0) {
        echo ' value="' . $bus_configuracion_categoria["idcategoria_programatica"] . '"';
    } else {
        echo ' value="' . $regcategoria_programatica["idcategoria_programatica"] . '"';
    }?>>


		<input type="hidden" name="idclasificador_presupuestario" maxlength="5" size="5" id="idclasificador_presupuestario" <?php echo 'value="' . $regclasificador_presupuestario['idclasificador_presupuestario'] . '"'; ?>>
		<input type="hidden" name="idordinal" maxlength="5" size="5" id="idordinal" <?php echo 'value="' . $regordinal['idordinal'] . '"'; ?>>
		<input type="hidden" name="emergente" maxlength="5" size="5" id="emergente" <?php echo 'value="' . $_POST['emergente'] . '"'; ?>>
		<input type="hidden" name="idmaestropresupuesto" id="idmaestropresupuesto" <?php echo 'value="' . $regmaestro_presupuesto['idRegistro_maestro'] . '"'; ?>>

		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			<?php
if ($encontroRegistros > 0 and in_array(244, $privilegios) == true) {
        ?>
			  <input  value='Modificar' name='modificar' type='submit'  class="button">
			  <?php
}
    if ($encontroRegistros == 0 and in_array(243, $privilegios) == true) {
        ?>
			  <input  name='guardar' id='guardar' value='Ingresar' type='submit' class="button">
			  <?
    }
    if ($encontroRegistros > 0 and in_array(245, $privilegios) == true) {
        ?>
			  <input name="eliminar" type="submit" value="Eliminar" class="button">
      <?php
}

    ?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>

		<?php
if ($encontroRegistros) {
        ?>
		<br><br>
		<table align=center cellpadding=4 cellspacing=0 width="60%">
			<h2 align=center>Ejecuci&oacute;n</h2>
			<h2 class="sqlmVersion"></h2>
            <tr>
				<td align='right' class='viewPropTitle'>Total Pre-Compromisos:</td>
				<td class=''><input type="text" style="text-align:right" name="pre_compromiso" maxlength="20" size="20" id="pre_compromiso"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['pre_compromiso'], 2, ",", ".") . '"';

        ?> disabled></td>
				<td align='left' class='viewPropTitle'>%</td>
				<td class=''><input type="text" style="text-align:right" name="pre_compromisoX100" maxlength="5" size="5" id="pre_compromisoX100"
										<?php if ($regmaestro_presupuesto["pre_compromiso"] == 0) {echo ' value="0.00"';} else {echo ' value="' . number_format($regmaestro_presupuesto['pre_compromiso'] * 100 / $regmaestro_presupuesto['monto_actual'], 2, ",", ".") . '"';}?>
                              disabled></td>

				<td align='right' class='viewPropTitle'>Disponible para Comprometer:</td>
				<td class=''><input type="text" style="text-align:right" name="disponible_compromisos" maxlength="20" size="20" id="disponible_compromisos"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['monto_actual'] - $regmaestro_presupuesto['pre_compromiso'] - $regmaestro_presupuesto['reservado_disminuir'] - $regmaestro_presupuesto['total_compromisos'], 2, ",", ".") . '"'; ?>
                              disabled></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Total Compromisos:</td>
				<td class=''><input type="text" style="text-align:right" name="total_compromisos" maxlength="20" size="20" id="total_compromisos"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['total_compromisos'], 2, ",", ".") . '"';

        ?> disabled></td>
				<td align='left' class='viewPropTitle'>%</td>
				<td class=''><input type="text" style="text-align:right" name="total_compromisosX100" maxlength="5" size="5" id="total_compromisosX100"
										<?php if ($regmaestro_presupuesto["total_compromisos"] == 0) {echo ' value="0.00"';} else {echo ' value="' . number_format($regmaestro_presupuesto['total_compromisos'] * 100 / $regmaestro_presupuesto['monto_actual'], 2, ",", ".") . '"';}?>
                              disabled></td>

				<td align='right' class='viewPropTitle'>Disponible para Comprometer:</td>
				<td class=''><input type="text" style="text-align:right" name="disponible_compromisos" maxlength="20" size="20" id="disponible_compromisos"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['monto_actual'] - $regmaestro_presupuesto['pre_compromiso'] - $regmaestro_presupuesto['reservado_disminuir'] - $regmaestro_presupuesto['total_compromisos'], 2, ",", ".") . '"'; ?>
                              disabled></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Total Causados:</td>
				<td class=''><input type="text" style="text-align:right" name="total_causados" maxlength="20" size="20" id="total_causados"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['total_causados'], 2, ",", ".") . '"'; ?> disabled></td>
				<td align='left' class='viewPropTitle'>%</td>
				<td class=''><input type="text" style="text-align:right" name="total_causadosX100" maxlength="5" size="5" id="total_causadosX100"
										<?php if ($regmaestro_presupuesto["total_causados"] == 0) {echo ' value="0.00"';} else {echo ' value="' . number_format($regmaestro_presupuesto['total_causados'] * 100 / $regmaestro_presupuesto['monto_actual'], 2, ",", ".") . '"';}
        ?> disabled></td>

				<td align='right' class='viewPropTitle'>Disponible para Causar:</td>
				<td class=''><input type="text" style="text-align:right" name="disponible_causados" maxlength="20" size="20" id="disponible_causados"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['monto_actual'] - $regmaestro_presupuesto['pre_compromiso'] - $regmaestro_presupuesto['reservado_disminuir'] - $regmaestro_presupuesto['total_causados'], 2, ",", ".") . '"'; ?>
                              disabled></td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle'>Total Pagados:</td>
				<td class=''><input type="text" style="text-align:right" name="total_pagados" maxlength="20" size="20" id="total_pagados"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['total_pagados'], 2, ",", ".") . '"'; ?>
                              disabled></td>
				<td align='left' class='viewPropTitle'>%</td>
				<td class=''><input type="text" style="text-align:right" name="total_pagadosX100" maxlength="5" size="5" id="total_pagadosX100"
										<?php if ($regmaestro_presupuesto["total_pagados"] == 0) {echo ' value="0.00"';} else {echo ' value="' . number_format($regmaestro_presupuesto['total_pagados'] * 100 / $regmaestro_presupuesto['monto_actual'], 2, ",", ".") . '"';}
        ?> disabled></td>

				<td align='right' class='viewPropTitle'>Disponible para Pagar:</td>
				<td class=''><input type="text" style="text-align:right" name="disponible_pagados" maxlength="20" size="20" id="disponible_pagados"
										<?php echo ' value="' . number_format($regmaestro_presupuesto['monto_actual'] - $regmaestro_presupuesto['pre_compromiso'] - $regmaestro_presupuesto['reservado_disminuir'] - $regmaestro_presupuesto['total_pagados'], 2, ",", ".") . '"'; ?>
                              disabled></td>
			</tr>
		</table
		><?php }?>
	</form>
	<br>
	<script> document.frmmaestro_presupuesto.anio.focus() </script>
</body>
</html>

<?php
if ($_POST) {

        $anio                          = $_POST["anio"];
        $idcategoria_programatica      = $_POST["idcategoria_programatica"];
        $idtipo_presupuesto            = $_POST["tipo_presupuesto"];
        $idfuente_financiamiento       = $_POST["fuente_financiamiento"];
        $idclasificador_presupuestario = $_POST["idclasificador_presupuestario"];
        $idordinal                     = $_POST["idordinal"];
        $idmonto_original              = $_POST["idmonto_original"];
        $total_disminucion             = $_POST["disminuciones"];
        $total_aumento                 = $_POST["aumentos"];
        $monto_actual                  = $_POST["monto_actual"];
        $idregistro                    = $_POST["idmaestropresupuesto"];
        $fh                            = date("Y-m-d H:i:s");
        $pc                            = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $busca_existe_registro         = mysql_query("select * from maestro_presupuesto where anio = '" . $_POST['anio'] . "'
																			and idcategoria_programatica = '" . $_POST['idcategoria_programatica'] . "'
																			and idtipo_presupuesto = '" . $_POST['tipo_presupuesto'] . "'
																			and idfuente_financiamiento = '" . $_POST['fuente_financiamiento'] . "'
																			and idclasificador_presupuestario = '" . $_POST['idclasificador_presupuestario'] . "'
																			and idordinal = '" . $_POST['idordinal'] . "'
																			and status='a'");
        if ($_POST["guardar"]) {
            if (mysql_num_rows($busca_existe_registro) > 0) {

                registra_transaccion("ERROR - Ingresar partida Maestro Presupuesto", $login, $fh, $pc, 'maestro_presupuesto');
                ?>
		<script>
			mostrarMensajes("error", "La partida ya existe en el Maestro de Presupuesto");
			setTimeout("window.location.href='principal.php?modulo=2&accion=46'",5000);
			</script>
		<?

                //header("location:error_presupuesto.php?err=10");  // envia mensaje de error que el registro ya existe
            } else {
                mysql_query("insert into maestro_presupuesto
									(anio,idcategoria_programatica,idtipo_presupuesto,idfuente_financiamiento,idclasificador_presupuestario,idordinal,monto_original,monto_actual,usuario,fechayhora,status)
							values ('$anio','$idcategoria_programatica','$idtipo_presupuesto','$idfuente_financiamiento','$idclasificador_presupuestario','$idordinal','$idmonto_original','$idmonto_original','$login','$fh','a')"
                );
                registra_transaccion("Ingreso partida Maestro Presupuesto", $login, $fh, $pc, 'maestro_presupuesto');
                redirecciona("principal.php?modulo=2&accion=46");
            }
        }

        if ($_POST["modificar"]) {

            $busca_existe_registro        = mysql_query("select * from maestro_presupuesto where idRegistro = '$idregistro'");
            $registro_maestro_presupuesto = mysql_fetch_assoc($busca_existe_registro);
            $monto_original_anterior      = $registro_maestro_presupuesto["monto_original"];
            $sql_modificar                = mysql_query("update maestro_presupuesto set
										monto_original='" . $idmonto_original . "',
										monto_actual='" . $idmonto_original . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idRegistro = '$idregistro'
											and total_aumento='0'
											and total_disminucion='0'
											and total_compromisos='0'
											and total_causados='0'
											and total_pagados='0'
											and status = 'a'");

            $busca_existe_registro        = mysql_query("select * from maestro_presupuesto where idRegistro = '$idregistro'");
            $registro_maestro_presupuesto = mysql_fetch_assoc($busca_existe_registro);
            $monto_original_nuevo         = $registro_maestro_presupuesto["monto_original"];
            if ($monto_original_anterior != $monto_original_nuevo) {
                registra_transaccion("Modifico partida Maestro Presupuesto", $login, $fh, $pc, 'maestro_presupuesto');
                redirecciona("principal.php?modulo=2&accion=46");
            } else {
                registra_transaccion("ERROR - Modificando partida Maestro Presupuesto", $login, $fh, $pc, 'maestro_presupuesto');
                ?>
						<script>
			mostrarMensajes("error", "No puede modificar esta partida porque ya presenta movimientos (incremento, disminucion, compromiso, causado, pago)");
			setTimeout("window.location.href='principal.php?modulo=2&accion=46'",5000);
			</script>

				<?

            }
        }

        if ($_POST["eliminar"]) {
            $sql_eliminar = mysql_query("delete from maestro_presupuesto
										where 	idRegistro = '$idregistro'
											and total_aumento='0'
											and total_disminucion='0'
											and total_compromisos='0'
											and total_causados='0'
											and total_pagados='0'
											and status = 'a'");
            $busca_existe_registro = mysql_query("select * from maestro_presupuesto where idRegistro = '$idregistro'");
            if (mysql_num_rows($busca_existe_registro) == 0) {
                registra_transaccion("Elimino partida Maestro Presupuesto", $login, $fh, $pc, 'maestro_presupuesto');
                redirecciona("principal.php?modulo=2&accion=46");
            } else {
                registra_transaccion("ERROR - Eliminando partida Maestro Presupuesto", $login, $fh, $pc, 'maestro_presupuesto');
                ?>
					<script>
			mostrarMensajes("error", "No puede Eliminar esta partida porque ya presenta movimientos (incremento, disminucion, compromiso, causado, pago)");
			setTimeout("window.location.href='principal.php?modulo=2&accion=46'",5000);
			</script>

			<?
            }
        }
    }
    ?>
