<script src="modulos/presupuesto/js/movimiento_presupuesto_ajax.js" type="text/javascript" language="javascript"></script>

<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<?php
include "../../../funciones/funciones.php";
if ($_POST["ingresar"]) {
    $_GET["accion"] = 254;
}

$id_partida_seleccionada = $_GET["c"];

$emergente = $_POST["emergente"];
$guardo    = $_REQUEST["guardo"];
$juntos    = $_GET["juntos"];

$modopartidas     = $_GET["modopartidas"];
$existen_partidas = false;

if ($modopartidas == "") {
    $modopartidas = 0;
}

if ($juntos == "") {
    $juntos = $_POST["juntos"];
}

$idrectificacion_presupuesto = $_REQUEST["idrectificacion_presupuesto"];

$m = $_POST["modoactual"];
if ($m != "") {$modo = $m;}
if ($_POST["idrectificacion_emergente"] != "") {
    $idrectificacion_presupuesto = $_POST["idrectificacion_emergente"];
}

$sql_configuracion = mysql_query("select * from configuracion
											where status='a'"
    , $conexion_db);
$registro_configuracion = mysql_fetch_assoc($sql_configuracion);
$anio_fijo              = $registro_configuracion["anio_fiscal"];

$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
												where status='a'"
    , $conexion_db);

$sql_partidas_cedentes = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
														clasificador_presupuestario.partida as partida,
														clasificador_presupuestario.generica as generica,
														clasificador_presupuestario.especifica as especifica,
														clasificador_presupuestario.sub_especifica as sub_especifica,
														clasificador_presupuestario.denominacion as denopartida,
														categoria_programatica.codigo as codigocategoria,
														unidad_ejecutora.denominacion as denocategoriaprogramatica,
														maestro_presupuesto.idregistro as idmaestro_presupuesto,
														maestro_presupuesto.anio as anio,
														ordinal.codigo as codigoordinal,
														ordinal.denominacion as denoordinal,
														partidas_rectificadoras.idrectificacion_presupuesto,
														partidas_rectificadoras.idmaestro_presupuesto,
														partidas_rectificadoras.idpartida_rectificadora,
														partidas_rectificadoras.monto_debitar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_rectificadoras,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
													where
							partidas_rectificadoras.status='a'
									and partidas_rectificadoras.idrectificacion_presupuesto=" . $idrectificacion_presupuesto . "
									and maestro_presupuesto.idRegistro=partidas_rectificadoras.idmaestro_presupuesto
									and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
									and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
									and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
									and ordinal.idordinal=maestro_presupuesto.idordinal
									and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
									order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC");

$sql_partidas_receptoras = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
														clasificador_presupuestario.partida as partida,
														clasificador_presupuestario.generica as generica,
														clasificador_presupuestario.especifica as especifica,
														clasificador_presupuestario.sub_especifica as sub_especifica,
														clasificador_presupuestario.denominacion as denopartida,
														categoria_programatica.codigo as codigocategoria,
														unidad_ejecutora.denominacion as denocategoriaprogramatica,
														maestro_presupuesto.idregistro as idmaestro_presupuesto,
														maestro_presupuesto.anio as anio,
														ordinal.codigo as codigoordinal,
														ordinal.denominacion as denoordinal,
														partidas_receptoras_rectificacion.idrectificacion_presupuesto,
														partidas_receptoras_rectificacion.idmaestro_presupuesto,
														partidas_receptoras_rectificacion.idpartida_receptoras_rectificacion,
														partidas_receptoras_rectificacion.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_receptoras_rectificacion,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
													where
										partidas_receptoras_rectificacion.status='a'
										and partidas_receptoras_rectificacion.idrectificacion_presupuesto=" . $idrectificacion_presupuesto . "
										and maestro_presupuesto.idRegistro=partidas_receptoras_rectificacion.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
										order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC"
);

// *******************************************************************************************************************
// carga de rectificacion presupuestaria nueva
if ($guardo and $idrectificacion_presupuesto != "" and ($modopartidas == 0 or $modopartidas == 4)) {
    $sql_rectificacion_presupuesto = mysql_query("select * from rectificacion_presupuesto
												where status='a'
												and idrectificacion_presupuesto=" . $idrectificacion_presupuesto
        , $conexion_db);
    $regrectificacion_presupuesto = mysql_fetch_assoc($sql_rectificacion_presupuesto);

    if (mysql_num_rows($sql_partidas_cedentes) > 0) {
        $existen_partidas_cedentes = true;
    }

    if (mysql_num_rows($sql_partidas_receptoras) > 0) {
        $existen_partidas_receptoras = true;
    }
}

// ***************************************************************************************************************************
//SELECCIONO MODIFICAR O ELIMINAR PARTIDA CEDENTE - FILTRA LOS DATOS DE LA PARTIDA
if ($modopartidas == 1 or $modopartidas == 2) {

    $sql_rectificacion_presupuesto = mysql_query("select * from rectificacion_presupuesto
												where status='a'
												and idrectificacion_presupuesto=" . $idrectificacion_presupuesto
        , $conexion_db) or die(mysql_error());

    $regrectificacion_presupuesto = mysql_fetch_assoc($sql_rectificacion_presupuesto);

//     $idrectificacion_presupuesto=$regrectificacion_presupuesto["idrectificacion_presupuesto"];

    if (mysql_num_rows($sql_partidas_cedentes) > 0) {
        $existen_partidas_cedentes = true;
    }
    if (mysql_num_rows($sql_partidas_receptoras) > 0) {
        $existen_partidas_receptoras = true;
    }

    $sql_partida_cedente_seleccionada = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
														clasificador_presupuestario.partida as partida,
														clasificador_presupuestario.generica as generica,
														clasificador_presupuestario.especifica as especifica,
														clasificador_presupuestario.sub_especifica as sub_especifica,
														clasificador_presupuestario.denominacion as denopartida,
														categoria_programatica.codigo as codigocategoria,
														ordinal.codigo as codigoordinal,
														ordinal.denominacion as denoordinal,
														unidad_ejecutora.denominacion as denocategoriaprogramatica,
														maestro_presupuesto.idregistro as idmaestro_presupuesto,
														maestro_presupuesto.idcategoria_programatica as idcategoria_programatica,
														maestro_presupuesto.idclasificador_presupuestario as idclasificador_presupuestario,
														maestro_presupuesto.anio as anio,
														maestro_presupuesto.idfuente_financiamiento,
														partidas_rectificadoras.idrectificacion_presupuesto,
														partidas_rectificadoras.idmaestro_presupuesto,
														partidas_rectificadoras.idpartida_rectificadora,
														partidas_rectificadoras.monto_debitar,
														fuente_financiamiento.denominacion as denomiancion_fuente
															from
														partidas_rectificadoras,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
													where
										partidas_rectificadoras.status='a'
										and partidas_rectificadoras.idpartida_rectificadora=" . $id_partida_seleccionada . "
										and maestro_presupuesto.idRegistro=partidas_rectificadoras.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento"
    ) or die(mysql_error());

    $regpartida_cedente_seleccionada = mysql_fetch_assoc($sql_partida_cedente_seleccionada);

    $sql_validar_categoria = mysql_query("select
											unidad_ejecutora.denominacion as denocategoriaprogramatica,
											unidad_ejecutora.codigo as codigounidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from unidad_ejecutora,categoria_programatica
												where
													unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and categoria_programatica.idcategoria_programatica=" . $regpartida_cedente_seleccionada["idcategoria_programatica"] . "
												and categoria_programatica.status='a'"
        , $conexion_db);

    if (mysql_num_rows($sql_validar_categoria) > 0) {
        $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);
    }

    $sql_validar_partida = mysql_query("select * from clasificador_presupuestario
														where idclasificador_presupuestario=" . $regpartida_cedente_seleccionada["idclasificador_presupuestario"] . "
															and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_partida) > 0) {
        $regclasificador_presupuestario = mysql_fetch_assoc($sql_validar_partida);
    }

}

// ***************************************************************************************************************************
//SELECCIONO MODIFICAR O ELIMINAR PARTIDA RECEPTORA - FILTRA LOS DATOS DE LA PARTIDA
if ($modopartidas == 8 or $modopartidas == 9) {

    $sql_rectificacion_presupuesto = mysql_query("select * from rectificacion_presupuesto
												where status='a'
												and idrectificacion_presupuesto=" . $idrectificacion_presupuesto
        , $conexion_db) or die(mysql_error());
    $regrectificacion_presupuesto = mysql_fetch_assoc($sql_rectificacion_presupuesto);

//     $idrectificacion_presupuesto=$regrectificacion_presupuesto["idrectificacion_presupuesto"];

    if (mysql_num_rows($sql_partidas_receptoras) > 0) {
        $existen_partidas_receptoras = true;
    }

    if (mysql_num_rows($sql_partidas_cedentes) > 0) {
        $existen_partidas_cedentes = true;
    }

    $sql_partida_receptora_seleccionada = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
														clasificador_presupuestario.partida as partida,
														clasificador_presupuestario.generica as generica,
														clasificador_presupuestario.especifica as especifica,
														clasificador_presupuestario.sub_especifica as sub_especifica,
														clasificador_presupuestario.denominacion as denopartida,
														categoria_programatica.codigo as codigocategoria,
														ordinal.codigo as codigoordinal,
														ordinal.denominacion as denoordinal,
														unidad_ejecutora.denominacion as denocategoriaprogramatica,
														maestro_presupuesto.idregistro as idmaestro_presupuesto,
														maestro_presupuesto.idcategoria_programatica as idcategoria_programatica,
														maestro_presupuesto.idclasificador_presupuestario as idclasificador_presupuestario,
														maestro_presupuesto.anio as anio,
														partidas_receptoras_rectificacion.idrectificacion_presupuesto,
														partidas_receptoras_rectificacion.idmaestro_presupuesto,
														partidas_receptoras_rectificacion.idpartida_receptoras_rectificacion,
														partidas_receptoras_rectificacion.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_receptoras_rectificacion,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
													where
										partidas_receptoras_rectificacion.status='a'
										and partidas_receptoras_rectificacion.idpartida_receptoras_rectificacion=" . $id_partida_seleccionada . "
										and maestro_presupuesto.idRegistro=partidas_receptoras_rectificacion.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento"
    ) or die(mysql_error());

    $regpartida_receptora_seleccionada = mysql_fetch_assoc($sql_partida_receptora_seleccionada);

    $sql_validar_categoria = mysql_query("select
											unidad_ejecutora.denominacion as denocategoriaprogramatica,
											unidad_ejecutora.codigo as codigounidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from unidad_ejecutora,categoria_programatica
												where
													unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and categoria_programatica.idcategoria_programatica=" . $regpartida_receptora_seleccionada["idcategoria_programatica"] . "
												and categoria_programatica.status='a'"
        , $conexion_db);

    if (mysql_num_rows($sql_validar_categoria) > 0) {
        $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);
    }

    $sql_validar_partida = mysql_query("select * from clasificador_presupuestario
														where idclasificador_presupuestario=" . $regpartida_receptora_seleccionada["idclasificador_presupuestario"] . "
															and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_partida) > 0) {
        $regclasificador_presupuestario = mysql_fetch_assoc($sql_validar_partida);
    }

}

// **********************************************************************************************
// CARGA LOS DATOS SELECCIONADOS DE LA VENTANA EMERGENTE DE LISTADO DE TRASLADOS PRESUPUESTARIOS

if ($_POST["idrectificacion_emergente"] != "") {
    // SI ESTA VARIABLE OCULTA TOMA VALOR ENVIADO DE UNA VENTANA EMERGENTE
    $sql_rectificacion_presupuesto = mysql_query("select * from rectificacion_presupuesto
												where status='a'
												and idrectificacion_presupuesto=" . $_POST["idrectificacion_emergente"]
        , $conexion_db);
    $regrectificacion_presupuesto = mysql_fetch_assoc($sql_rectificacion_presupuesto);
    //$idrectificacion_presupuesto=$regrectificacion_presupuesto["idrectificacion_presupuesto"];

    if (mysql_num_rows($sql_partidas_cedentes) > 0) {
        $existen_partidas_cedentes = true;
    }

    if (mysql_num_rows($sql_partidas_receptoras) > 0) {
        $existen_partidas_receptoras = true;
    }

}
// **********************************************************************************************************************************
// Carga los datos de las categorias programaticas

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
												and categoria_programatica.status='a'"
        , $conexion_db);

    if (mysql_num_rows($sql_validar_categoria) > 0) {
        $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);
    }
}
// **********************************************************************************************************************************
// Carga los datos de las partidas del maestro de presupuesto

if ($_POST["idmaestropresupuesto"] != "") {
    // si entra para modificar o eliminar busca el registro para cargar los datos en el formulario

    $sql = mysql_query("select * from maestro_presupuesto
										where idRegistro like '" . $_POST['idmaestropresupuesto'] . "'"
        , $conexion_db);

    $sql = "select 		tipo_presupuesto.denominacion as denotipo_presupuesto,
						clasificador_presupuestario.codigo_cuenta as codigopartida,
						clasificador_presupuestario.denominacion as denopartida,
						categoria_programatica.codigo as codigocategoria,
						fuente_financiamiento.denominacion as denofuente_financiamiento,
						ordinal.codigo as codigoordinal,
						ordinal.denominacion as denoordinal,
						maestro_presupuesto.monto_actual as monto_actual,
						maestro_presupuesto.total_disminucion as total_disminucion,
						maestro_presupuesto.total_aumento as total_aumento,
						maestro_presupuesto.monto_original as monto_original,
						maestro_presupuesto.total_compromisos as total_compromisos,
						maestro_presupuesto.total_causados as total_causados,
						maestro_presupuesto.total_pagados as total_pagados,
						maestro_presupuesto.anio as anio,
						maestro_presupuesto.idRegistro as idRegistro_maestro,
						maestro_presupuesto.monto_actual-maestro_presupuesto.total_compromisos as disponible,
						maestro_presupuesto.idcategoria_programatica as idcategoria_programatica,
						maestro_presupuesto.idtipo_presupuesto as idtipo_presupuesto,
						maestro_presupuesto.idfuente_financiamiento as idfuente_financiamiento,
						maestro_presupuesto.idclasificador_presupuestario as idclasificador_presupuestario
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
}

if ($emergente and $_GET["accion"] != 254) {
    $juntos = 1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="600; URL=lib/cerrar.php"> -->
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<script type="text/javascript" src="../js/calendar/calendar.js"></script>
	<script type="text/javascript" src="../js/calendar/calendar-setup.js"></script>
	<script type="text/javascript" src="../js/calendar/lang/calendar-es.js"></script>
	<style type="text/css"> @import url("../theme/calendar-win2k-cold-1.css"); </style>
<SCRIPT language=JavaScript>
document.oncontextmenu=new Function("return false")
<!--- oculta el script para navegadores antiguos
function valida_envia(){
		//script para validar que el usuario introdujo datos en el formulario
		if (document.frmrectificacion_presupuesto.nro_solicitud.value.length==0){
			mostrarMensajes("error", "Debe escribir un Numero de Solicitud para la Rectificaci&oacute;n Presupuestaria");
			document.frmrectificacion_presupuesto.nro_solicitud.focus()
			return false;
		}
		if (document.frmrectificacion_presupuesto.justificacion.value.length==0){
			mostrarMensajes("error", "Debe escribir una Justificaci&oacute;n para la Rectificaci&oacute;n Presupuestaria");
			document.frmrectificacion_presupuesto.justificacion.focus()
			return false;
		}

	}

function abreVentanaPresupuestoR(){
	m=document.frmrectificacion_presupuesto.modoactual.value;
	g=document.frmrectificacion_presupuesto.guardo.value;
	nro=document.frmrectificacion_presupuesto.nro.value;
	j=document.frmrectificacion_presupuesto.juntos.value;
	miPopup=window.open("lib/listas/lista_presupuestos.php?m="+m+"&g="+g+"&i="+nro+"&j="+j+"&llama="+"rectificacion","presupuestos","width=1200,height=600,scrollbars=yes")
	miPopup.focus()
}


function abreVentanaPresupuesto(){
	m=document.frmrectificacion_presupuesto.modoactual.value;
	g=document.frmrectificacion_presupuesto.guardo.value;
	nro=document.frmrectificacion_presupuesto.nro.value;
	j=document.frmrectificacion_presupuesto.juntos.value;
	miPopup=window.open("lib/listas/lista_presupuestos.php?m="+m+"&g="+g+"&i="+nro+"&j="+j+"&llama="+"recibe","presupuestos","width=1200,height=600,scrollbars=yes")
	miPopup.focus()
}


function abreVentanaCA(){
	m=document.frmrectificacion_presupuesto.modoactual.value;
	j=document.frmrectificacion_presupuesto.juntos.value;
	g=document.frmrectificacion_presupuesto.guardo.value;
	miPopup=window.open("lib/listas/lista_rectificacion_presupuesto.php?m="+m+"&g="+guardo+"&j="+j,"traslados presupuestarios","width=800,height=600,scrollbars=yes")
	miPopup.focus()
}

function formatoNumero(idcampo) {
var frm = document.frmrectificacion_presupuesto;
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
	//frm.monto_actual.value = cadena + "," + resultado[1];
} else {
	frm.elements[idcampo].value = 0;
	alert ("Debes indicar valores n&uacute;mericos en el campo "+idcampo);
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

function abrirCerrarCedentes(){
	if(document.getElementById('divCedentes').style.display=="block"){
			document.getElementById('divCedentes').style.display="none";
			document.getElementById('textoContraerCedentes').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('divCedentes').style.display="block";
			document.getElementById('textoContraerCedentes').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
	}
}

function abrirCerrarReceptoras(){
	if(document.getElementById('divReceptoras').style.display=="block"){
			document.getElementById('divReceptoras').style.display="none";
			document.getElementById('textoContraerReceptoras').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('divReceptoras').style.display="block";
			document.getElementById('textoContraerReceptoras').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
	}
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
	<h4 align=center>Rectificaci&oacute;n Presupuestaria</h4>
	<h2 class="sqlmVersion"></h2>

	<?php
if ($regrectificacion_presupuesto["nro_solicitud"] != "") {
    $btimprimir = "visibility:visible;";
} else {
    $btimprimir = "visibility:hidden;";
}

?>
	<table align=center cellpadding=2 cellspacing=0 width="10%">
			<tr>
				<td align='center' ><img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaCA()" title="Buscar Rectificaci&oacute;n Presupuestaria">
                &nbsp;<a href="principal.php?modulo=2&accion=49"><img src="imagenes/nuevo.png" border="0" title="Nueva Rectificaci&oacute;n Presupuestaria"></a>
                &nbsp;<img src="imagenes/imprimir.png" title="Imprimir Credito Adicional"  onClick="document.getElementById('divTipo').style.display='block'; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='block';" style="cursor:pointer; <?=$btimprimir?>" /></td>
	  		</tr>
             </tr>
      		<tr>
            	<td>
                	<div id="divTipo" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
                      <div align="right"><a href="#" onClick="document.getElementById('divTipo').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='none';">X</a></div>
                      <table id="tableImprimir">
                        <tr><td><input type="radio" name="tipo" id="solicitud" value="solicitud" checked /> Solicitud</td></tr>
                        <tr><td><input type="radio" name="tipo" id="simulado" value="simulado" /> Simulado</td></tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=rectificacion_partida&id_rectificacion='+document.getElementById('idrectificacion_presupuesto').value+'&solicitud='+document.getElementById('solicitud').checked+'&simulado='+document.getElementById('simulado').checked; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block'; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block'; document.getElementById('divTipo').style.display='none'; document.getElementById('tableImprimir').style.display='none';">
                            </td>
                        </tr>
                      </table>
                      </div>
                </td>
            </tr>
	</table>
	<br>
	<?PHP
//var_dump($_POST);
//echo "cuerpo".$_POST["cuerpo"];
//echo " total credito ".$regrectificacion_presupuesto["total_credito"];
//echo " total debito ".$regrectificacion_presupuesto["total_debito"];
//echo " idpartida".$id_partida_seleccionada;
?>

	<form name="frmrectificacion_presupuesto" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">

    <input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $modo . '"'; ?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="' . $guardo . '"'; ?>>
	<input type="hidden" name="nro" id="nro" <?php echo 'value="' . $nro_solicitud_credito . '"'; ?>>
	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="' . $juntos . '"'; ?>>
	<input type="hidden" name="fecha_ingreso" id="fecha_ingreso" <?php echo 'value="' . date("d/m/Y", strtotime("-0 day")) . '"'; ?>>
	<input type="hidden" name="idrectificacion_presupuesto" id="idrectificacion_presupuesto" <?php if (isset($_POST["idrectificacion_presupuesto"])) {echo 'value="' . $_POST["idrectificacion_presupuesto"] . '"';} else {echo 'value="' . $regrectificacion_presupuesto["idrectificacion_presupuesto"] . '"';}?>>
	<input type="hidden" name="idrectificacion_emergente" id="idrectificacion_emergente" <?php echo 'value="' . $regrectificacion_presupuesto['idrectificacion_presupuesto'] . '"'; ?>>
	<input type="hidden" name="emergente" maxlength="5" size="5" id="emergente" <?php echo 'value="' . $_POST['emergente'] . '"'; ?>>
	<input type="hidden" name="id_partida_seleccionada" id="id_partida_seleccionada" <?php echo 'value="' . $id_partida_seleccionada . '"'; ?>>
    <input type="hidden" name="cuerpo" id="cuerpo" value="<?=$_REQUEST["cuerpo"]?>" >
    <input type="hidden" name="idmaestropresupuesto" id="idmaestropresupuesto" <?php echo 'value="' . $regmaestro_presupuesto['idRegistro_maestro'] . '"'; ?>>

		<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
			  <td align='right' >&nbsp;</td>
			  <td colspan="2" class=''><b><?echo $regrectificacion_presupuesto["estado"]; ?></b></td>
			  <td>&nbsp;</td>
			  <td align='right' >&nbsp;</td>
			  <td class=''>&nbsp;</td>
			  <td align='right'>&nbsp;</td>
			  <td>&nbsp;</td>
			</tr>

			<tr>
				<td align='right' class='viewPropTitle' width="10%">Nro. Solicitud:</td>
				<td class='' width="10%"><input type="text" id="nro_solicitud" name="nro_solicitud" maxlength="12" size="12" value="<?php echo $regrectificacion_presupuesto["nro_solicitud"]; ?>">
				</td>
				<td align='right' class='viewPropTitle' width="12%">Fecha Solicitud:</td>
				<td width="15%">
				<input name="fecha_solicitud" type="text" id="fecha_solicitud" value="<?php
if ($guardo) {echo substr($regrectificacion_presupuesto['fecha_solicitud'], 8, 2) . '/' . substr($regrectificacion_presupuesto['fecha_solicitud'], 5, 2) . '/' . substr($regrectificacion_presupuesto['fecha_solicitud'], 0, 4) . '"';} else {echo date("d/m/Y", strtotime("-0 day"));}?>" size="13" maxlength="10">
					<img src="imagenes/jscalendar0.gif" name="f_trigger_c" id="f_trigger_c" style="cursor: pointer; " title="Selector de Fecha" onMouseOver="this.style.background='#E6E6E6';" onMouseOut="this.style.background=''" />
						<script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_solicitud",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat    	: "%d/%m/%Y"
							});
						</script>
				</td>
				<td align='right' class='viewPropTitle' width="10%">Nro. Resoluci&oacute;n:</td>
					<td class='' width="10%"><input type="text" id="nro_resolucion" name="nro_resolucion" maxlength="12" size="12" value="<?php echo $regrectificacion_presupuesto["nro_resolucion"]; ?>">
				</td>
				<td align='right' class='viewPropTitle' width="13%">Fecha Resoluci&oacute;n:</td>
				<td width="15%">
				<input name="fecha_resolucion" type="text" id="fecha_resolucion" value="<?php if ($guardo) {echo substr($regrectificacion_presupuesto['fecha_resolucion'], 8, 2) . '/' . substr($regrectificacion_presupuesto['fecha_resolucion'], 5, 2) . '/' . substr($regrectificacion_presupuesto['fecha_resolucion'], 0, 4) . '"';} else {echo date("d/m/Y", strtotime("-0 day"));}?>" size="13" maxlength="10">
					<img src="imagenes/jscalendar0.gif" name="f_trigger_d" id="f_trigger_d" style="cursor: pointer; " title="Selector de Fecha" onMouseOver="this.style.background='#E6E6E6';" onMouseOut="this.style.background=''" />
						<script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_resolucion",
							button        : "f_trigger_d",
							align         : "Tr",
							ifFormat    	: "%d/%m/%Y"
							});
						</script>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle' width="12%">Justificaci&oacute;n:</td>
					<td class='' colspan="7"><textarea id="justificacion" name="justificacion" cols="137" rows="3" ><?php echo utf8_decode($regrectificacion_presupuesto["justificacion"]); ?></textarea>
				</td>
			</tr>
			<tr>

				<td align='right' class='viewPropTitle'>A&ntilde;o:</td>
				<td class='viewProp'>
				<select name="anio" style="width:80%">
                        <?
anio_fiscal();
?>
				</select>
				</td>
				<td align='right' class='viewPropTitle' width="10%" colspan="2">Disponible para Rectificar:</td>
					<td class='' width="15%"><input type="label" style="text-align:right" id="total_cedentes" name="total_cedentes" maxlength="18" size="18"
										<?php echo ' value="' . number_format($regrectificacion_presupuesto["total_debito"], 2, ",", ".") . '"'; ?>>
				</td>
                <td align='right' class='viewPropTitle' width="10%" colspan="2">Total Receptoras:</td>
					<td class='' width="15%"><input type="label" style="text-align:right" id="total_receptoras" name="total_receptoras" maxlength="18" size="18"
										<?php echo ' value="' . number_format($regrectificacion_presupuesto["total_credito"], 2, ",", ".") . '"'; ?>>
				</td>
                <td>  <a href="#" onClick="recalcularTotalRectificacion()" id="recalcularTotalRectificacion"><img border="0" src="imagenes/refrescar.png" title="Recalcular sumatoria" style="text-decoration:none"></a>
                </td>

			</tr>
		</table>

		<!-- TABLA QUE MUESTRA LOS BOTONES -->
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			  <?php

if ($modopartidas == 4) {
    echo "<input align=center class='button' name='actualizar' type='submit' value='Actualizar Duplicado'>";
}

if ($modopartidas != 4 and ($juntos != 1 and $_GET["accion"] != 255 and $_GET["accion"] != 256 and in_array(254, $privilegios) == true)) {
    echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
}

if ($modopartidas != 4 and ($juntos == 1 or $_GET["accion"] == 255 or $_GET["accion"] == 256 and in_array($_GET["accion"], $privilegios) == true) and $regrectificacion_presupuesto["estado"] == "elaboracion") {
    echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
}

if ($modopartidas != 4 and ($juntos == 1 or $_GET["accion"] == 255 or $_GET["accion"] == 256 and in_array($_GET["accion"], $privilegios) == true) and $regrectificacion_presupuesto["estado"] == "elaboracion") {
    echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
}

if ($modopartidas != 4 and $regrectificacion_presupuesto["estado"] == "elaboracion") {
    echo "<input align=center class='button' name='procesar' type='submit' value='Procesar'>";
}

if ($modopartidas != 4 and $regrectificacion_presupuesto["estado"] == "procesado") {
    echo "<input align=center class='button' name='anular' type='submit' value='Anular'>";
    echo "<input align=center class='button' name='duplicar' type='submit' value='Duplicar'>";
}

if ($modopartidas != 4 and $regrectificacion_presupuesto["estado"] == "Anulado") {
    echo "<input align=center class='button' name='duplicar' type='submit' value='Duplicar'>";
}

?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>
	<br>

    <!-- PARTIDAS CEDENTES-->
	<?php if ($guardo and $regrectificacion_presupuesto["estado"] == "elaboracion" or (in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado")) {?>

		<table align=center cellpadding="1" cellspacing="0" width="90%" >
  		  <tr>
      		<td class='viewPropTitle'><strong>Partidas Rectificadoras</strong></td>
            <td align="right" class='viewPropTitle'><a href="#" onClick="abrirCerrarCedentes()" id="textoContraerCedentes"><img border="0" src="imagenes/cerrar.gif" title="Cerrar" style="text-decoration:none"></td>
      		</tr>
      	</table>

	<?}?>
     <div id="divCedentes" style="display:block">
    <?
if ($regrectificacion_presupuesto["estado"] == "elaboracion" or (in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado")) {
    ?>

		<table align=center cellpadding="1" cellspacing="0" width="74%" >
          <tr>
      		<?if ($modopartidas == 0 or $cuerpo == 2 and $regrectificacion_presupuesto["estado"] == "elaboracion") {?>
				<td align='center' class='' width="5%" rowspan="2"><button name="listado_presupuesto" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="document.frmrectificacion_presupuesto.cuerpo.value=1, abreVentanaPresupuestoR()"><img src='imagenes/search0.png'></button></td>
            <?}?>
				<td align='center' class='viewPropTitle' colspan="2">Categoria Program&aacute;tica</td>
			<td align='center' class='viewPropTitle' colspan="2">Partida</td>
			<td align='center' class='viewPropTitle' width="13%">Monto Disponible</td>

<?php
if ($modopartidas == 0 or $cuerpo == 2 and $regrectificacion_presupuesto["estado"] == "elaboracion") {?>
						<td align='center' class='' width="8%" rowspan="2"><button name="agregar_partida_cedente" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/save.png'></button></td>
<?php }
    if ((in_array(754, $privilegios) == true and $modopartidas == 2 and $regrectificacion_presupuesto["estado"] == "procesado") or ($modopartidas == 2 and $regrectificacion_presupuesto["estado"] == "elaboracion")) {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="eliminar_partida_cedente" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/delete.png'></button></td>
				<?php }?>

	    	</tr>

			<tr>
				<td class='' width="9%"><input type="label" name="codcategoria_programatica" id="codcategoria_programatica" maxlength="14" size="14"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . $regcategoria_programatica["codigo"] . '"';}
    }?>></td>
			  <td class='' width="25%"><input type="label" name="denocategoria_programatica" id="denocategoria_programatica" maxlength="40" size="40"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . utf8_decode($regcategoria_programatica["denocategoriaprogramatica"]) . '"';}
    }?>></td>
			  <td class='' width="8%"><input type="label" name="codigo_cuenta" id="codigo_cuenta" maxlength="12" size="12"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . $regclasificador_presupuestario["codigo_cuenta"] . '"';}
    }?>></td>
			  <td class='' width="27%"><input type="label" name="denopartida" id="denopartida" maxlength="40" size="40"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . utf8_decode($regclasificador_presupuestario["denominacion"]) . '"';}
    }?>></td>
			  <td class='' width="13%"><input type="label" style="text-align:right" name="monto_debitarM" maxlength="14" size="14" id="monto_debitarM"
									<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {
            if ($modopartidas == 2) {
                echo ' value="' . $regpartida_cedente_seleccionada['monto_debitar'] . '"';
            } else {
                $disponible = consultarDisponibilidad($regmaestro_presupuesto['idRegistro_maestro']);
                echo ' value="' . $disponible . '"';
            }
        }
    }
    ?> ></td>


		  </tr>
			<tr>
				<td></td>
				<td><button name="limpiar" type="submit" style="background-color:white;border-style:none;cursor:pointer;"><font size="1">Limpiar</button></td>
			</tr>
		</table>

 <input type="hidden" style="text-align:right" name="monto_debitar" maxlength="20" size="20" id="monto_debitar"
									<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {
            echo ' value="' . $regmaestro_presupuesto['monto_actual'] . '"';
        }
    }?> >

    	<input type="hidden" name="idtotal_debito" maxlength="18" size="18" id="idtotal_debito" <?php
if ($_POST["total_debito"] == "") {echo ' value="' . $regrectificacion_presupuesto['total_debito'] . '"';} else {echo ' value="' . $_POST['idtotal_debito'] . '"';}
    ?>>
		<?}?>

	<?php if ($existen_partidas_cedentes) {
    ?>

						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
					  	<thead>
								<tr>
									<td align="center" class="Browse">A&ntilde;o</td>
									<td align="center" class="Browse">Fuente Financiamiento</td>
                                    <td align="center" class="Browse" colspan="2">Categor&iacute;a Program&aacute;tica</td>
									<td align="center" class="Browse" colspan="2">Partida Presupuestaria</td>
									<td align="center" class="Browse">Monto Disponible</td>
                                    <?//if($regrectificacion_presupuesto["estado"]=="elaboracion" and $modopartidas<>4) {?>
										<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                        <?//}?>
								</tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de partidas cedentes

    while ($llenar_grilla = mysql_fetch_array($sql_partidas_cedentes)) {
        ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
echo "<td align='center' class='Browse' width='4%'>" . $llenar_grilla["anio"] . "</td>";
        echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["denominacion_fuente"] . "</td>";
        echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["codigocategoria"] . "</td>";
        echo "<td align='left' class='Browse' width='22%'>" . utf8_decode($llenar_grilla["denocategoriaprogramatica"]) . "</td>";
        echo "<td align='left' class='Browse' width='14%'>" . $llenar_grilla["partida"] . " " . $llenar_grilla["generica"] . " " . $llenar_grilla["especifica"] . " " . $llenar_grilla["sub_especifica"] . " " . $llenar_grilla["codigoordinal"] . " ";
        echo "</td>";
        //echo "<td align='center' class='Browse' width='8%'>".$llenar_grilla["partida"]." ".$llenar_grilla["generica"]." ".$llenar_grilla["especifica"]." ".$llenar_grilla["sub_especifica"]."</td>";
        echo "<td align='left' class='Browse' width='40%'>";if ($llenar_grilla["codigoordinal"] != 0000) {echo utf8_decode($llenar_grilla["denoordinal"]);} else {echo utf8_decode($llenar_grilla["denopartida"]);}
        echo "</td>";

        echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_debitar"], 2, ",", ".") . "</td>";
        $c      = $llenar_grilla["idpartida_rectificadora"];
        $i      = $llenar_grilla["idrectificacion_presupuesto"];
        $guardo = true;

        if ((in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado") or in_array(256, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "elaboracion" and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
										<a href='principal.php?modulo=2&accion=256&modopartidas=2&c=$c&juntos=1&idrectificacion_presupuesto=$i&guardo=$guardo&modo=2&cuerpo=1'
										class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
        }
        echo "</tr>";
    }
    ?>
					</table>
  	<?}?>

    </div>



	<!-- PARTIDAS RECEPTORAS-->
	<?if ($guardo and $regrectificacion_presupuesto["estado"] == "elaboracion" or (in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado")) {
    ?>
    	<br><br>
      	<table align=center cellpadding="1" cellspacing="0" width="90%">
<tr>
      			<td class='viewPropTitle'><strong>Partidas Receptoras</strong></td>
                <td align="right" class='viewPropTitle'><a href="#" onClick="abrirCerrarReceptoras()" id="textoContraerReceptoras"><img border="0" src="imagenes/cerrar.gif" title="Cerrar" style="text-decoration:none"></td>
      		</tr>
   		</table>
   <?php }?>
   <div id="divReceptoras" style="display:block">
   <?
if ($regrectificacion_presupuesto["estado"] == "elaboracion" or (in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado")) {
    ?>
	<table align=center cellpadding="1" cellspacing="0" width="80%">
      <tr>
      		<?if ($modopartidas == 0 or $cuerpo == 1 and $regrectificacion_presupuesto["estado"] == "elaboracion") {?>
				<td align='center' class='' width="5%" rowspan="2"><button name="listado_presupuesto" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="document.frmrectificacion_presupuesto.cuerpo.value=2, abreVentanaPresupuesto()"><img src='imagenes/search0.png'></button></td>
            <?}?>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Categoria Program&aacute;tica</td>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Partida</td>
				<td align='center' class='viewPropTitle' width="10%">Monto Aumentar</td>

                <?php
if ($modopartidas == 0 or $cuerpo == 1 and $regrectificacion_presupuesto["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="agregar_partida_receptora" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/save.png'></button></td>
				<?php }
    if ($modopartidas == 8 and $regrectificacion_presupuesto["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="modificar_partida_receptora" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/modificar.png'></button></td>
				<?php }
    if ((in_array(754, $privilegios) == true and $modopartidas == 9 and $regrectificacion_presupuesto["estado"] == "procesado") or ($modopartidas == 9 and $regrectificacion_presupuesto["estado"] == "elaboracion")) {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="eliminar_partida_receptora" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/delete.png'></button></td>
				<?php }?>

	    </tr>
			<tr>
				<td class='' width="10%"><input type="label" name="codcategoria_programatica" id="codcategoria_programatica" maxlength="14" size="14" <?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 2) {
            echo ' value="' . $regcategoria_programatica["codigo"] . '"';
        }
    }?>></td>
				<td class='' width="40%"><input type="label" name="denocategoria_programatica" id="denocategoria_programatica" maxlength="50" size="40" <?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 2) {
            echo ' value="' . utf8_decode($regcategoria_programatica["denocategoriaprogramatica"]) . '"';
        }
    }?>></td>
				<td class='' width="10%"><input type="label" name="codigo_cuenta" id="codigo_cuenta" maxlength="12" size="12" <?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 2) {
            echo ' value="' . $regclasificador_presupuestario["codigo_cuenta"] . '"';
        }
    }?>></td>
				<td class='' width="40%"><input type="label" name="denopartida" id="denopartida" maxlength="50" size="40" <?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 2) {
            echo ' value="' . utf8_decode($regclasificador_presupuestario["denominacion"]) . '"';
        }
    }?>></td>
				<td class='' width="10%"><input type="text" style="text-align:right" name="monto_acreditar" maxlength="20" size="20" id="monto_acreditar"
									<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 2) {
            if ($_GET["accion"] == 254 and $_POST["monto_acreditar"] != "") {
                echo ' value="' . number_format($_POST["idmonto_acreditar"], 2, ",", ".") . '"';
            }
            if ($_GET["accion"] == 255 || $_GET["accion"] == 256) {
                if ($_POST["monto_debitar"] == "") {echo ' value="' . number_format($regpartida_receptora_seleccionada['monto_acreditar'], 2, ",", ".") . '"';} else {echo ' value="' . number_format($_POST['monto_acreditar'], 2, ",", ".") . '"';}
            }

            if ($_GET["accion"] == 256) {
                echo " disabled";
            }

        }
    }
    ?> onBlur="formatoNumero(this.name)"></td>

			</tr>
			<tr>
			<td></td>
			<td><button name="limpiar" type="submit" style="background-color:white;border-style:none;cursor:pointer;"><font size="1">Limpiar</button></td>
			</tr>
		</table>

    	<input type="hidden" name="idtotal_credito" maxlength="18" size="18" id="idtotal_credito" <?php
if ($_GET["accion"] == 255) {
        if ($_POST["total_credito"] == "") {echo ' value="' . $regrectificacion_presupuesto['total_credito'] . '"';} else {echo ' value="' . $_POST['idtotal_credito'] . '"';}
    }?>>

		<?}?>

	<?php if ($existen_partidas_receptoras) {
    ?>

						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
							<thead>
								<tr>
									<td align="center" class="Browse">A&ntilde;o</td>
									<td align="center" class="Browse">Fuente Financiamiento</td>
                                    <td align="center" class="Browse" colspan="2">Categor&iacute;a Program&aacute;tica</td>
									<td align="center" class="Browse" colspan="2">Partida Presupuestaria</td>
									<td align="center" class="Browse">Monto Aumentar</td>
                                    <?//if ($regrectificacion_presupuesto["estado"]=="elaboracion" and $modopartidas<>4){?>
										<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                    <?//}?>
								</tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de partidas cedentes

    while ($llenar_grilla = mysql_fetch_array($sql_partidas_receptoras)) {
        ?>
										<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
								<?php
echo "<td align='center' class='Browse' width='4%'>" . $llenar_grilla["anio"] . "</td>";
        echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["denominacion_fuente"] . "</td>";
        echo "<td align='center' class='Browse' width='10%'>" . $llenar_grilla["codigocategoria"] . "</td>";
        echo "<td align='left' class='Browse' width='22%'>" . utf8_decode($llenar_grilla["denocategoriaprogramatica"]) . "</td>";
        echo "<td align='left' class='Browse' width='14%'>" . $llenar_grilla["partida"] . " " . $llenar_grilla["generica"] . " " . $llenar_grilla["especifica"] . " " . $llenar_grilla["sub_especifica"] . " " . $llenar_grilla["codigoordinal"] . " ";
        echo "</td>";
        echo "<td align='left' class='Browse' width='40%'>";if ($llenar_grilla["codigoordinal"] != 0000) {echo utf8_decode($llenar_grilla["denoordinal"]);} else {echo utf8_decode($llenar_grilla["denopartida"]);}
        echo "</td>";

        if ($regrectificacion_presupuesto["estado"] == "elaboracion" or $regrectificacion_presupuesto["estado"] == "Anulado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_acreditar"], 2, ",", ".") . "</td>";
        } else if (in_array(754, $privilegios) == false and $regrectificacion_presupuesto["estado"] == "procesado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_acreditar"], 2, ",", ".") . "</td>";
        } else if (in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado") { ?>
                                    	<td align='right' class='Browse' width='8%'>
                                        <input align="right" style="text-align:right"
                                        					name="monto_acreditar<?=$llenar_grilla["idpartida_receptoras_rectificacion"]?>"
            												type="hidden"
                                                            id="monto_acreditar<?=$llenar_grilla["idpartida_receptoras_rectificacion"]?>"
                                                            size="20"
                                                            value="<?=$llenar_grilla["monto_acreditar"]?>">
										<input align="right" style="text-align:right"
                                        					name="monto_acreditar_mostrado<?=$llenar_grilla["idpartida_receptoras_rectificacion"]?>"
            												type="text"
                                                            id="monto_acreditar_mostrado<?=$llenar_grilla["idpartida_receptoras_rectificacion"]?>"
                                                            size="20"
                                                            onclick="this.select()"
                                                            onblur="formatoNumeroPpt(this.id, 'monto_acreditar<?=$llenar_grilla["idpartida_receptoras_rectificacion"]?>')"
                                                            value="<?=number_format($llenar_grilla["monto_acreditar"], 2, ',', '.')?>">
                                       </td>
									<?}

        //echo "<td align='right' class='Browse' width='8%'>".number_format($llenar_grilla["monto_acreditar"],2,",",".")."</td>";
        $c      = $llenar_grilla["idpartida_receptoras_rectificacion"];
        $i      = $llenar_grilla["idrectificacion_presupuesto"];
        $guardo = true;

        if ($regrectificacion_presupuesto["estado"] == "Anulado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        } else if (in_array(754, $privilegios) == false and $regrectificacion_presupuesto["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        }
        if (in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'> ";
            ?> <a href="#" onClick="recalcularPartidaRectificada('<?=$llenar_grilla["idmaestro_presupuesto"]?>','<?=$llenar_grilla["idrectificacion_presupuesto"]?>', document.getElementById('monto_acreditar<?=$llenar_grilla["idpartida_receptoras_rectificacion"]?>').value)" id="recalcularPartidaRectificada"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular partida"></a></td> <?

        }

        if (in_array(255, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "elaboracion" and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
											<a href='principal.php?modulo=2&accion=255&modopartidas=8&c=$c&juntos=1&idrectificacion_presupuesto=$i&guardo=$guardo&modo=1&cuerpo=2'
											class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
        }
        if ((in_array(754, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "procesado") or in_array(256, $privilegios) == true and $regrectificacion_presupuesto["estado"] == "elaboracion" and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
										<a href='principal.php?modulo=2&accion=256&modopartidas=9&c=$c&juntos=1&idrectificacion_presupuesto=$i&guardo=$guardo&modo=2&cuerpo=2'
										class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
        }
        echo "</tr>";
    }
    ?>
						</table>

		<?php }?>
     </div>
  </form>
	<br>
	<?php
echo "<script> document.frmrectificacion_presupuesto.nro_solicitud.focus() </script>";
if ($cuerpo == 1) {echo "<script> document.frmrectificacion_presupuesto.monto_debitar.focus(), document.frmrectificacion_presupuesto.monto_debitar.select()  </script>";}
if ($cuerpo == 2) {echo "<script> document.frmrectificacion_presupuesto.monto_acreditar.focus(), document.frmrectificacion_presupuesto.monto_acreditar.select()  </script>";}

?>

</body>
</html>

<?php

if ($_POST) {

    $nro_solicitud               = $_POST["nro_solicitud"];
    $fecha_solicitud             = substr($_POST["fecha_solicitud"], 6, 4) . "/" . substr($_POST["fecha_solicitud"], 3, 2) . "/" . substr($_POST["fecha_solicitud"], 0, 2);
    $nro_resolucion              = $_POST["nro_resolucion"];
    $fecha_resolucion            = substr($_POST["fecha_resolucion"], 6, 4) . "/" . substr($_POST["fecha_resolucion"], 3, 2) . "/" . substr($_POST["fecha_resolucion"], 0, 2);
    $fecha_ingreso               = substr($_POST["fecha_ingreso"], 6, 4) . "/" . substr($_POST["fecha_ingreso"], 3, 2) . "/" . substr($_POST["fecha_ingreso"], 0, 2);
    $justificacion               = $_POST["justificacion"];
    $anio                        = $_POST["anio"];
    $idtotal_credito             = $_POST["idtotal_credito"];
    $idtotal_debito              = $_POST["idtotal_debito"];
    $idrectificacion_presupuesto = $_POST["idrectificacion_presupuesto"];
    $fh                          = date("Y-m-d H:i:s");
    $pc                          = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $busca_rectificacion         = mysql_query("select * from rectificacion_presupuesto where idrectificacion_presupuesto = '$idrectificacion_presupuesto'", $conexion_db);
    $rectificacion               = mysql_fetch_array($busca_rectificacion);
//****************************************************************************************************************************************
    //  PARTIDAS RECTIFICADORAS
    //
    //****************************************************************************************************************************************
    // INGRESAR PARTIDAS RECTIFICADORAS
    //****************************************************************************************************************************************
    $monto_debitar = $_POST["monto_debitarM"];

    if (isset($_POST["agregar_partida_cedente"])) {
        $idmaestro_presupuesto = $_POST["idmaestropresupuesto"];
        $busca_existe_partida  = mysql_query("select * from partidas_rectificadoras where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_partida) > 0) {

            ?>
				<script>
			mostrarMensajes("error", "Disculpe, la partida ya fue ingresada a la Rectificaci&oacute;n Presupuestaria");
			//setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0'",5000);
			</script>

		<?
        } else {
            // BUSCA QUE NO EXISTA EN LAS RECEPTORAS
            $busca_existe_receptora = mysql_query("select * from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
            if (mysql_num_rows($busca_existe_receptora) > 0) {
                mensaje("Disculpe, la partida ya fue ingresada a la Rectificaci&oacute;n Presupuestaria como Receptora");
                redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");
            } else {

                ?>
					<script>
					mostrarMensajes("error", "XXXXXXXXXXXXXXXXXXXX");
					//setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0'",5000);
					</script>
				<?
                $disponible_compromiso = consultarDisponibilidad($idmaestro_presupuesto);

                $disponible_compromiso_resta = $monto_debitar;

                //$resta0 = $disponible_compromiso - ($disponible_compromiso_resta);
                $resta = bcsub($disponible_compromiso, $disponible_compromiso_resta, 2);

                if ($resta < 0) {
                    ?>
					<script>
					mostrarMensajes("error", "Disculpe, el monto a Debitar '<?=$monto_debitar?>' es mayor que el monto Disponible para disminuir '<?=($disponible_compromiso)?>'");
					//setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0'",5000);
					</script>

				<?

                } else {

                    $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idregistro = '" . $idmaestro_presupuesto . "'
																					and status='a'");
                    $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);

                    mysql_query("insert into partidas_rectificadoras
										(idrectificacion_presupuesto,idmaestro_presupuesto,monto_debitar,usuario,fechayhora,status)
								values ('$idrectificacion_presupuesto','$idmaestro_presupuesto','$monto_debitar','$login','$fh','a')"
                        , $conexion_db);

                    mysql_query("update rectificacion_presupuesto set
										total_debito=(total_debito)+'" . $monto_debitar . "'
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'"
                        , $conexion_db) or die(mysql_error());

                    if ($rectificacion["estado"] == "elaboracion") {
                        mysql_query("update maestro_presupuesto set
										reservado_disminuir = (reservado_disminuir) + '" . $monto_debitar . "'
										where 	idregistro = '" . $idmaestro_presupuesto . "'"
                            , $conexion_db) or die(mysql_error());
                    } else if ($rectificacion["estado"] == "procesado") {
                        mysql_query("update maestro_presupuesto set
										total_disminucion = (total_disminucion) + '" . $monto_debitar . "'
										where 	idregistro = '" . $idmaestro_presupuesto . "'"
                            , $conexion_db) or die(mysql_error());
                    }
                    registra_transaccion('Ingresar Partida Cedente Rectificacion Presupuesto', $login, $fh, $pc, 'partidas_rectificadoras', $conexion_db);
                    redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");

                }
            }
        }
    }

//****************************************************************************************************************************************
    // ELIMINAR PARTIDAS RECTIFICADORAS
    //****************************************************************************************************************************************

    if (isset($_POST["eliminar_partida_cedente"])) {
        // ELIMINO UNA PARTIDA AL CEDENTE

        $idpartida_seleccionada = $_POST["id_partida_seleccionada"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_rectificadoras where idpartida_rectificadora = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_rectificadora = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior           = $regpartida_rectificadora["monto_debitar"];
        $idmaestro_presupuesto    = $regpartida_rectificadora["idmaestro_presupuesto"];
        mysql_query("delete from partidas_rectificadoras
									where 	idpartida_rectificadora = '$idpartida_seleccionada'
											and status = 'a'", $conexion_db);

        mysql_query("update rectificacion_presupuesto set
									total_debito=(total_debito)-'" . $monto_anterior . "'
									where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'"
            , $conexion_db);

        if ($rectificacion["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir)-'" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        } else if ($rectificacion["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_disminucion = (total_disminucion)-'" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }

        registra_transaccion('Eliminar Partida Rectificadora ', $login, $fh, $pc, 'partidas_rectificadoras', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");

    }

//****************************************************************************************************************************************
    //  PARTIDAS RECEPTORAS
    //
    //****************************************************************************************************************************************
    // INGRESAR PARTIDAS RECEPTORAS
    //****************************************************************************************************************************************
    if (isset($_POST["agregar_partida_receptora"])) {

        $idmaestro_presupuesto = $_POST["idmaestropresupuesto"];
        $monto_acreditar       = $_POST["monto_acreditar"];
        $busca_existe_partida  = mysql_query("select * from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_partida) > 0) {
            ?>
				<script>
			mostrarMensajes("error", "Disculpe, la partida ya fue ingresada a la Rectificaci&oacute;n Presupuestaria");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas='",5000);
			</script>

		<?
        } else {
            $busca_existe_cedente = mysql_query("select * from partidas_rectificadoras where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
            if (mysql_num_rows($busca_existe_cedente) > 0) {

                ?>
				<script>
			mostrarMensajes("error", "Disculpe, la partida ya fue ingresada a la Rectificacion Presupuestaria como Rectificadora");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0'",5000);
			</script>

		<?
            } else {
                $busca_acumulado = mysql_query("select * from rectificacion_presupuesto where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
																				and status='a'", $conexion_db);
                $acumulado     = mysql_fetch_assoc($busca_acumulado);
                $total_credito = $acumulado["total_credito"] + $monto_acreditar;
                $total_debito  = $acumulado["total_debito"];
                if ($total_credito > $total_debito) {

                    ?>
				<script>
			mostrarMensajes("error", "Disculpe, el Monto Disponible para Rectificar es menor al total de las Partidas a Recibir Rectificaci&oacute;n, por lo que se sobregiraria la Partida de Rectificaci&oacute;n");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0'",5000);
			</script>

		<?

                } else {
                    mysql_query("insert into partidas_receptoras_rectificacion
									(idrectificacion_presupuesto,idmaestro_presupuesto,monto_acreditar,usuario,fechayhora,status)
							values ('$idrectificacion_presupuesto','$idmaestro_presupuesto','$monto_acreditar','$login','$fh','a')"
                        , $conexion_db);

                    mysql_query("update rectificacion_presupuesto set
									total_credito=(total_credito)+'" . $monto_acreditar . "'
									where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'"
                        , $conexion_db);

                    if ($rectificacion["estado"] == "elaboracion") {
                        mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                            , $conexion_db) or die(mysql_error());
                    } else if ($rectificacion["estado"] == "procesado") {
                        mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) + '" . $monto_acreditar . "',
									monto_actual = (monto_actual) + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                            , $conexion_db) or die(mysql_error());
                    }
                    registra_transaccion('Ingresar Partida Receptora Rectificaci&oacute;n Presupuestaria (' . $idmaestro_presupuesto . ')', $login, $fh, $pc, 'partidas_receptoras_rectificacion', $conexion_db);
                    redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");
                }
            }
        }
    }

//****************************************************************************************************************************************
    // MODIFICAR PARTIDAS RECEPTORA
    //****************************************************************************************************************************************

    if (isset($_POST["modificar_partida_receptora"])) {
        // MODIFICO UNA PARTIDA CEDENTE

        $monto_acreditar        = $_POST["monto_acreditar"];
        $idpartida_seleccionada = $_POST["id_partida_seleccionada"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_receptoras_rectificacion where idpartida_receptoras_rectificacion = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_receptora_seleccionada = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior                    = $regpartida_receptora_seleccionada["monto_acreditar"];
        $idmaestro_presupuesto             = $regpartida_receptora_seleccionada["idmaestro_presupuesto"];
        $idrectificacion_presupuesto       = $regpartida_receptora_seleccionada["idrectificacion_presupuesto"];
        $busca_acumulado                   = mysql_query("select * from rectificacion_presupuesto where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
																				and status='a'", $conexion_db);
        $acumulado     = mysql_fetch_assoc($busca_acumulado);
        $total_credito = $acumulado["total_credito"] + $monto_acreditar - $monto_anterior;
        $total_debito  = $acumulado["total_debito"];
        if ($total_credito > $total_debito) {

            ?>
				<script>
			mostrarMensajes("error", "Disculpe, el Monto Disponible para Rectificar es menor al total de las Partidas a Recibir Rectificaci&oacute;n, por lo que se sobregiraria la Partida de Rectificaci&oacute;n");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0'",5000);
			</script>

		<?
        } else {
            mysql_query("update partidas_receptoras_rectificacion set
									monto_acreditar='" . $monto_acreditar . "'
									where 	idpartida_receptoras_rectificacion= '$idpartida_seleccionada'
											and status = 'a'", $conexion_db) or die(mysql_error());

            mysql_query("update rectificacion_presupuesto set
									total_credito=(total_credito)-'" . $monto_anterior . "'+'" . $monto_acreditar . "'
									where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'"
                , $conexion_db);

            if ($rectificacion["estado"] == "elaboracion") {
                mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_anterior . "' + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());
            } else if ($rectificacion["estado"] == "procesado") {
                mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) - '" . $monto_anterior . "' + '" . $monto_acreditar . "',
									monto_actual = (monto_actual) + '" . $monto_acreditar . "' - '" . $monto_anterior . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());
            }
            registra_transaccion('Modificar Partida Receptora Rectificaci&oacute;n Presupuestaria', $login, $fh, $pc, 'partidas_receptoras_rectificacion', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");
        }
    }

//****************************************************************************************************************************************
    // ELIMINAR PARTIDAS RECEPTORA
    //****************************************************************************************************************************************

    if (isset($_POST["eliminar_partida_receptora"])) {
        // ELIMINO UNA PARTIDA AL CEDENTE

        $idpartida_seleccionada = $_POST["id_partida_seleccionada"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_receptoras_rectificacion where idpartida_receptoras_rectificacion = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_receptora_seleccionada = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior                    = $regpartida_receptora_seleccionada["monto_acreditar"];
        $idrectificacion_presupuesto       = $regpartida_receptora_seleccionada["idrectificacion_presupuesto"];
        $idmaestro_presupuesto             = $regpartida_receptora_seleccionada["idmaestro_presupuesto"];

        mysql_query("delete from partidas_receptoras_rectificacion
									where 	idpartida_receptoras_rectificacion = '$idpartida_seleccionada'
											and status = 'a'", $conexion_db);

        mysql_query("update rectificacion_presupuesto set
									total_credito=(total_credito)-'" . $monto_anterior . "'
									where 	idrectificacion_presupuesto= '$idrectificacion_presupuesto'"
            , $conexion_db);

        if ($rectificacion["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        } else if ($rectificacion["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) - '" . $monto_anterior . "',
									monto_actual = (monto_actual) - '" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }
        registra_transaccion('Eliminar Partida Receptoras Rectificacion Presupuestaria', $login, $fh, $pc, 'partidas_receptoras_rectificacion', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");

    }

//*************************************************************************************************************************************************************************
    //    PROCESAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["procesar"])) {

        $busca_rectificacion = mysql_query("select * from rectificacion_presupuesto where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'") or die(mysql_error());
        $regrectificacion    = mysql_fetch_array($busca_rectificacion);

        if ($regrectificacion["total_debito"] >= $regrectificacion["total_credito"]) {
            $monto_credito  = $regrectificacion["total_credito"];
            $busca_cedentes = mysql_query("select * from partidas_rectificadoras where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");
            $filas          = mysql_num_rows($busca_cedentes);
            if (mysql_num_rows($busca_cedentes) > 0) {
                $busca_receptoras = mysql_query("select * from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");
                if (mysql_num_rows($busca_receptoras) > 0) {

                    $monto_por_partida = $regrectificacion["total_credito"] / $filas;

                    while ($procesar_cedentes = mysql_fetch_array($busca_cedentes)) {
                        $idmaestro_presupuesto_rectificadora = $procesar_cedentes["idmaestro_presupuesto"];
                        $busca_partida_maestro               = mysql_query("select * from maestro_presupuesto where idregistro = '" . $idmaestro_presupuesto_rectificadora . "'
																				and status='a'");
                        $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
                        $disponible_compromiso     = $datos_maestro_presupuesto["monto_actual"] - $datos_maestro_presupuesto["total_compromisos"];
                        $disponible_acumulado      = $disponible_acumulado + $disponible_compromiso;
                    }

                    if ($disponible_acumulado >= $regrectificacion["total_credito"]) {

                        $busca_cedentes = mysql_query("select * from partidas_rectificadoras where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");
                        while ($procesar_cedentes = mysql_fetch_array($busca_cedentes)) {
                            $idmaestro_presupuesto_rectificadora = $procesar_cedentes["idmaestro_presupuesto"];
                            //*********** VALIDAR QUE ESA PARTIDA TENGA ESE MONTO **********************//
                            $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '" . $idmaestro_presupuesto_rectificadora . "'
																				and status='a'");
                            $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
                            $disponible_compromiso     = $datos_maestro_presupuesto["monto_actual"] - $datos_maestro_presupuesto["total_compromisos"];

                            if ($disponible_compromiso >= $monto_por_partida) {
                                mysql_query("update maestro_presupuesto set
											total_disminucion = total_disminucion + '" . $monto_por_partida . "',
											reservado_disminuir = reservado_disminuir - '" . $monto_por_partida . "',
											monto_actual = monto_actual - '" . $monto_por_partida . "'
											where 	idRegistro = '" . $idmaestro_presupuesto_rectificadora . "'"
                                ) or die(mysql_error());

                                mysql_query("update partidas_rectificadoras set
											monto_debitar='" . $monto_por_partida . "'
									where 	idmaestro_presupuesto = '" . $idmaestro_presupuesto_rectificadora . "'
										and idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
											and status = 'a'", $conexion_db) or die(mysql_error());

                            } else {
                                $resto = $monto_por_partida - $disponible_compromiso;
                                mysql_query("update maestro_presupuesto set
											total_disminucion = total_disminucion + '" . $disponible_compromiso . "',
											reservado_disminuir = reservado_disminuir - '" . $disponible_compromiso . "',
											monto_actual = monto_actual - '" . $disponible_compromiso . "'
											where 	idRegistro = '" . $idmaestro_presupuesto_rectificadora . "'"
                                    , $conexion_db) or die(mysql_error());

                                mysql_query("update partidas_rectificadoras set
											monto_debitar='" . $monto_por_partida . "'
									where 	idmaestro_presupuesto = '" . $idmaestro_presupuesto_rectificadora . "'
										and idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
											and status = 'a'", $conexion_db) or die(mysql_error());

                                $monto_por_partida = ($regrectificacion["total_credito"] - $disponible_compromiso) / $filas;
                            }

                        }
                    } else {

                        registra_transaccion('ERROR - Procesar Rectificacion Presupuestaria - No tiene disponibilidad en la(s) Partida(s) de Rectificacion', $login, $fh, $pc, 'partidas_receptoras_rectificacion', $conexion_db);
                        ?>
				<script>
			mostrarMensajes("error", "ERROR -  No tiene disponibilidad en la(s) Partida(s) de Rectificacion, por lo que no se puede procesar <?=$disponible_acumulado?>");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=<?=$idrectificacion_presupuesto?>&juntos=1&modopartidas=0'",5000);
			</script>

		<?

                    }

                    while ($procesar_receptoras = mysql_fetch_array($busca_receptoras)) {
                        $monto_acreditar                 = $procesar_receptoras["monto_acreditar"];
                        $idmaestro_presupuesto_receptora = $procesar_receptoras["idmaestro_presupuesto"];

                        mysql_query("update maestro_presupuesto set
									total_aumento = total_aumento+'" . $monto_acreditar . "',
									solicitud_aumento = solicitud_aumento - '" . $monto_acreditar . "',
									monto_actual = monto_actual+'" . $monto_acreditar . "'
									where 	idRegistro = '$idmaestro_presupuesto_receptora'"
                            , $conexion_db);

                    }

                    mysql_query("update rectificacion_presupuesto set
										estado='procesado',
										fechayhora='" . $fh . "',
										usuario='" . $login . "',
										total_debito='" . $monto_credito . "'
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);

                    registra_transaccion('Procesar Rectificacion Presupuestaria - EXITOSO', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
                    ?>
				<script>
			mostrarMensajes("exito", "El documento de Rectificacion Presupuestaria se proceso con exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49'",5000);
			</script>

		<?
                } else {
                    registra_transaccion('ERROR - Procesar Rectificacion Presupuestaria - No tiene partidas receptoras cargadas', $login, $fh, $pc, 'partidas_receptoras_rectificacion', $conexion_db);
                    ?>
				<script>
			mostrarMensajes("error", "ERROR -  No existen partidas Receptoras en el documento, por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=<?=$idrectificacion_presupuesto?>&juntos=1&modopartidas=0'",5000);
			</script>

		<?
                }
            } else {

                registra_transaccion('ERROR - Procesar Rectificacion Presupuestaria - No tiene partidas Rectificadoras cargadas', $login, $fh, $pc, 'partidas_rectificadoras', $conexion_db);
                ?>
				<script>
			mostrarMensajes("error", "ERROR -  No existen partidas Rectificadoras en el documento, por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=<?=$idrectificacion_presupuesto?>&juntos=1&modopartidas=0'",5000);
			</script>

		<?
            }
        } else {

            registra_transaccion('ERROR - Procesar Rectificacion Presupuestaria - No coinciden montos debitar y acreditar', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
            ?>
				<script>
			mostrarMensajes("error", "ERROR -  El Monto a Rectificar (<?=$regrectificacion["total_credito"]?>), es mayor que el Monto Disponible para Rectificacion (<?=$regrectificacion["total_debito"]?>), por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=<?=$idrectificacion_presupuesto?>&juntos=1&modopartidas=0'",5000);
			</script>

		<?

        }
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //    ANULAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["anular"])) {

        $busca_rectificacion = mysql_query("select * from rectificacion_presupuesto where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'") or die(mysql_error());
        $regrectificacion    = mysql_fetch_array($busca_rectificacion);
        $busca_cedentes      = mysql_query("select * from partidas_rectificadoras where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");
        $busca_receptoras    = mysql_query("select * from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");

        while ($procesar_receptoras = mysql_fetch_array($busca_receptoras)) {
            $monto_acreditar       = $procesar_receptoras["monto_acreditar"];
            $idmaestro_presupuesto = $procesar_receptoras["idmaestro_presupuesto"];
            $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idregistro = '" . $idmaestro_presupuesto . "'
																				and status='a'");
            $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
            $disponible_compromiso     = $datos_maestro_presupuesto["monto_actual"] - $datos_maestro_presupuesto["total_compromisos"];
            if ($monto_acreditar > $disponible_compromiso) {

                $sql_validar_categoria = mysql_query("select
											unidad_ejecutora.denominacion as denocategoriaprogramatica,
											unidad_ejecutora.codigo as codigounidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from unidad_ejecutora,categoria_programatica
												where
													unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and categoria_programatica.idcategoria_programatica=" . $datos_maestro_presupuesto["idcategoria_programatica"] . "
												and categoria_programatica.status='a'"
                    , $conexion_db);
                $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);

                $sql_validar_partida = mysql_query("select * from clasificador_presupuestario
														where idclasificador_presupuestario=" . $datos_maestro_presupuesto["idclasificador_presupuestario"] . "
															and status='a'"
                    , $conexion_db);
                $regclasificador_presupuestario = mysql_fetch_assoc($sql_validar_partida);

                ?>
				<script>
			mostrarMensajes("error", "La Partida '<?=$regcategoria_programatica["codigo"]?>' '<?=$regcategoria_programatica["denocategoriaprogramatica"]?>' '<?=$regclasificar_presupuestario["codigo_cuenta"]?>' '<?=$regclasificador_presupuestario["denominacion"]?>' tiene un Disponible de '<?=$disponible_compromiso?>' y al ANULAR este Documento se le restar&iacute;a '<?=$monto_acreditar?>' lo que sobregiraria la Partida, por lo que no puede Anular este documento");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=<?=$idrectificacion_presupuesto?>&juntos=1&modopartidas=0'",5000);
			</script>

		<?
            }

        }

        $filas             = mysql_num_rows($busca_cedentes);
        $monto_por_partida = $regrectificacion["total_credito"] / $filas;

        while ($procesar_cedentes = mysql_fetch_array($busca_cedentes)) {
            $idmaestro_presupuesto = $procesar_cedentes["idmaestro_presupuesto"];
            mysql_query("update maestro_presupuesto set
									total_disminucion= total_disminucion-'" . $monto_por_partida . "',
									monto_actual = monto_actual+'" . $monto_por_partida . "'
									where 	idRegistro = '$idmaestro_presupuesto'"
                , $conexion_db) or die(mysql_error());
        }
        $busca_receptoras = mysql_query("select * from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");
        while ($procesar_receptoras = mysql_fetch_array($busca_receptoras)) {
            $monto_acreditar       = $procesar_receptoras["monto_acreditar"];
            $idmaestro_presupuesto = $procesar_receptoras["idmaestro_presupuesto"];
            mysql_query("update maestro_presupuesto set
									total_aumento= total_aumento-'" . $monto_acreditar . "',
									monto_actual = monto_actual-'" . $monto_acreditar . "'
									where 	idRegistro = '$idmaestro_presupuesto'"
                , $conexion_db) or die(mysql_error());

        }

        mysql_query("update rectificacion_presupuesto set
										estado='Anulado',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);

        registra_transaccion('ANULAR Rectificaci&oacute;n Presupuestaria, ID DOCUMENTO (' . $idrectificacion_presupuesto . ')', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
        ?>
				<script>
			mostrarMensajes("exito", "La ANULACION del documento de Rectificaci&oacute;n Presupuestaria se proceso con exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=49'",5000);
			</script>

		<?

    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //    DUPLICAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["duplicar"])) {
        $duplicado           = true;
        $busca_rectificacion = mysql_query("select * from rectificacion_presupuesto where idrectificacion_presupuesto = '$idrectificacion_presupuesto' and status = 'a'");
        $regrectificacion    = mysql_fetch_assoc($busca_rectificacion);
        $total_credito       = $regrectificacion["total_credito"];
        $total_debito        = $regrectificacion["total_debito"];

        mysql_query("insert into rectificacion_presupuesto
									(fecha_solicitud,fecha_resolucion,fecha_ingreso,justificacion,anio,total_credito,total_debito,estado,usuario,fechayhora,status)
							values ('$fecha_solicitud','$fecha_resolucion','$fecha_ingreso','$justificacion','$anio','$total_credito','$total_debito','elaboracion','$login','$fh','a')"
        ) or die(mysql_error());

        $nuevorectificacion_presupuesto = mysql_insert_id();

        $busca_partidas_receptoras = mysql_query("select * from partidas_receptoras_rectificacion where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");

        while ($procesar_credito = mysql_fetch_array($busca_partidas_receptoras)) {
            $monto_acreditar       = $procesar_credito["monto_acreditar"];
            $idmaestro_presupuesto = $procesar_credito["idmaestro_presupuesto"];
            mysql_query("insert into partidas_receptoras_rectificacion
									(idrectificacion_presupuesto,idmaestro_presupuesto,monto_acreditar,usuario,fechayhora,status)
							values ('$nuevorectificacion_presupuesto','$idmaestro_presupuesto','$monto_acreditar','$login','$fh','a')");

            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());

        }

        $busca_partidas_cedentes = mysql_query("select * from partidas_rectificadoras where idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "' and status='a'");
        $error                   = "false";
        while ($procesar_debito = mysql_fetch_array($busca_partidas_cedentes)) {
            $idmaestro_presupuesto = $procesar_debito["idmaestro_presupuesto"];
            $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '" . $idmaestro_presupuesto . "'
																				and status='a'") or die(mysql_error());
            $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
            $disponible_compromiso     = $datos_maestro_presupuesto["monto_actual"] - $datos_maestro_presupuesto["total_compromisos"];
            mysql_query("insert into partidas_rectificadoras
									(idrectificacion_presupuesto,idmaestro_presupuesto,monto_debitar,usuario,fechayhora,status)
							values ('$nuevorectificacion_presupuesto','$idmaestro_presupuesto','$disponible_compromiso','$login','$fh','a')");

            mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir) + '" . $disponible_compromiso . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());

            $disponible_acumulado = $disponible_acumulado + $disponible_compromiso;
        }

        mysql_query("update rectificacion_presupuesto set
										total_debito='" . $disponible_acumulado . "'
										where 	idrectificacion_presupuesto = '$nuevorectificacion_presupuesto'
											and status = 'a'", $conexion_db);

        registra_transaccion('DUPLICAR Rectificacion Presupuestaria', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
        //redirecciona("principal.php?modulo=2&accion=47&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1&modopartidas=0");
        redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$nuevorectificacion_presupuesto&juntos=0&modopartidas=4");
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //   REGISTRAR DUPLICADO
    //*************************************************************************************************************************************************************************

    if (isset($_POST["actualizar"])) {

        mysql_query("update rectificacion_presupuesto set
										nro_solicitud='" . $nro_solicitud . "',
										fecha_solicitud='" . $fecha_solicitud . "',
										nro_resolucion='" . $nro_resolucion . "',
										fecha_resolucion='" . $fecha_resolucion . "',
										justificacion='" . $justificacion . "',
										anio='" . $anio . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);
        registra_transaccion('Actualizar Encabezado de Rectificacion Presupuestaria Duplicado ', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1");
    }

//********************************************************************************************************

    if (isset($_POST["ingresar"]) and in_array(254, $privilegios) == true) {
        $busca_existe_registro = mysql_query("select * from rectificacion_presupuesto where nro_solicitud = '" . $_POST['nro_solicitud'] . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_registro) > 0) {

            ?>
				<script>
			mostrarMensajes("error", "Disculpe el Numero de Solicitud que ingreso ya existe, por favor vuelva a intentarlo");
			</script>

		<?

        } else {
            mysql_query("insert into rectificacion_presupuesto
									(nro_solicitud,fecha_solicitud,nro_resolucion,fecha_resolucion,fecha_ingreso,justificacion,anio,total_credito,total_debito,estado,usuario,fechayhora,status)
							values ('$nro_solicitud','$fecha_solicitud','$nro_resolucion','$fecha_resolucion','$fecha_ingreso','$justificacion','$anio','$idtotal_credito','$idtotal_debito','elaboracion','$login','$fh','a')"
                , $conexion_db);
            $idrectificacion_presupuesto = mysql_insert_id();

            registra_transaccion('Ingresar Rectificaci&oacute;n Presupuestaria', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1");
        }
    }

    if ($_POST["modificar"] and in_array(255, $privilegios) == true) {

        mysql_query("update rectificacion_presupuesto set
										fecha_solicitud='" . $fecha_solicitud . "',
										nro_resolucion='" . $nro_resolucion . "',
										fecha_resolucion='" . $fecha_resolucion . "',
										justificacion='" . $justificacion . "',
										anio='" . $anio . "',
										total_credito='" . $idtotal_credito . "',
										total_debito='" . $idtotal_debito . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);
        registra_transaccion('Modificar Encabezado Rectificacion Presupuestaria', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=49&guardo=true&idrectificacion_presupuesto=$idrectificacion_presupuesto&juntos=1");
    }

    if ($_POST["eliminar"] and in_array(256, $privilegios) == true) {

        $sql_cedentes = mysql_query("select * from partidas_rectificadoras
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db) or die("ERROR cedentes " . mysql_error());

        while ($eliminar_cedentes = mysql_fetch_array($sql_cedentes)) {
            $monto_debitar         = $eliminar_cedentes["monto_debitar"];
            $idmaestro_presupuesto = $eliminar_cedentes["idmaestro_presupuesto"];
            mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir) - '" . $monto_debitar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());

        }

        mysql_query("delete from partidas_rectificadoras
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);

        $sql_receptoras = mysql_query("select * from partidas_receptoras_rectificacion
										where 	idrectificacion_presupuesto = '" . $idrectificacion_presupuesto . "'
											and status = 'a'", $conexion_db) or die(mysql_error());

        while ($eliminar_receptoras = mysql_fetch_array($sql_receptoras)) {
            $monto_acreditar       = $eliminar_receptoras["monto_acreditar"];
            $idmaestro_presupuesto = $eliminar_receptoras["idmaestro_presupuesto"];
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }

        mysql_query("delete from partidas_receptoras_rectificacion
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);

        mysql_query("delete from rectificacion_presupuesto
										where 	idrectificacion_presupuesto = '$idrectificacion_presupuesto'
											and status = 'a'", $conexion_db);

        registra_transaccion('Eliminar Rectificaci&oacute;n Presupuestaria ', $login, $fh, $pc, 'rectificacion_presupuesto', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=49");

    }
}

?>
