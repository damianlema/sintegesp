
<script src="modulos/presupuesto/js/movimiento_presupuesto_ajax.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" src="js/funciones.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>

<?php
include "../../../funciones/funciones.php";
extract($_POST);
extract($_GET);
if ($_POST["ingresar"]) {
    $_GET["accion"] = 250;
}
/*
$entro = true;
$ruta = "";
$accion_actual = $_REQUEST["accion"];
while ($entro){
$sql_ruta = mysql_query("select * from accion where id_accion = ".$accion_actual."");
$bus_ruta = mysql_fetch_array($sql_ruta);
if ($bus_ruta["mostrar"] == 1){
if ($ruta == ""){
$ruta = $bus_ruta["nombre_accion"];
}else{
$ruta = $bus_ruta["nombre_accion"]."/".$ruta;
}
}
if ($bus_ruta["accion_padre"] == 0){
$sql_ruta_modulo = mysql_query("select * from modulo where id_modulo = ".$bus_ruta["id_modulo"]."");
$bus_ruta_modulo = mysql_fetch_array($sql_ruta_modulo);
$ruta = $bus_ruta_modulo["nombre_modulo"]."/".$ruta;
$entro= false;
}else{
$accion_actual = $bus_ruta["accion_padre"];
}
}
 */

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

//if ($_GET["idtraslados_presupuestarios"]=="")
$idtraslados_presupuestarios = $_REQUEST["idtraslados_presupuestarios"];
//else
//    $idtraslados_presupuestarios=$_GET["idtraslados_presupuestarios"];

$m = $_POST["modoactual"];
if ($m != "") {$modo = $m;}

if ($_POST["idtraslados_emergente"] != "") {
    $idtraslados_presupuestarios = $_POST["idtraslados_emergente"];
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
														partidas_cedentes_traslado.idtraslados_presupuestarios,
														partidas_cedentes_traslado.idmaestro_presupuesto,
														partidas_cedentes_traslado.idpartida_cedentes_traslado,
														partidas_cedentes_traslado.monto_debitar,
														fuente_financiamiento.denominacion as denominacion_fuente
															FROM
														 partidas_cedentes_traslado,
														 clasificador_presupuestario,
														 categoria_programatica,
														 maestro_presupuesto,
														 unidad_ejecutora,
														 ordinal,
														 fuente_financiamiento
															WHERE
									partidas_cedentes_traslado.status='a'
									and partidas_cedentes_traslado.idtraslados_presupuestarios=" . $idtraslados_presupuestarios . "
									and maestro_presupuesto.idRegistro=partidas_cedentes_traslado.idmaestro_presupuesto
									and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
									and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
									and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
									and ordinal.idordinal=maestro_presupuesto.idordinal
									and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
									order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC"
);

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
														partidas_receptoras_traslado.idtraslados_presupuestarios,
														partidas_receptoras_traslado.idmaestro_presupuesto,
														partidas_receptoras_traslado.idpartida_receptoras_traslado,
														partidas_receptoras_traslado.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_receptoras_traslado,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
															where
									partidas_receptoras_traslado.status='a'
									and partidas_receptoras_traslado.idtraslados_presupuestarios=" . $idtraslados_presupuestarios . "
									and maestro_presupuesto.idRegistro=partidas_receptoras_traslado.idmaestro_presupuesto
									and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
									and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
									and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
									and ordinal.idordinal=maestro_presupuesto.idordinal
									and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
									order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC"
);

// *******************************************************************************************************************
// carga de traslado presupuestario nuevo
if ($guardo and $idtraslados_presupuestarios != "" and ($modopartidas == 0 or $modopartidas == 4)) {
    $sql_traslados_presupuestarios = mysql_query("select * from traslados_presupuestarios
												where status='a'
												and idtraslados_presupuestarios=" . $idtraslados_presupuestarios
        , $conexion_db);
    $regtraslados_presupuestarios = mysql_fetch_assoc($sql_traslados_presupuestarios);

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

    $sql_traslados_presupuestarios = mysql_query("select * from traslados_presupuestarios
												where status='a'
												and idtraslados_presupuestarios=" . $idtraslados_presupuestarios
        , $conexion_db) or die(mysql_error());

    $regtraslados_presupuestarios = mysql_fetch_assoc($sql_traslados_presupuestarios);

//     $idtraslados_presupuestarios=$regtraslados_presupuestarios["idtraslados_presupuestarios"];

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
														partidas_cedentes_traslado.idtraslados_presupuestarios,
														partidas_cedentes_traslado.idmaestro_presupuesto,
														partidas_cedentes_traslado.idpartida_cedentes_traslado,
														partidas_cedentes_traslado.monto_debitar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_cedentes_traslado,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
															where
										partidas_cedentes_traslado.status='a'
										and partidas_cedentes_traslado.idpartida_cedentes_traslado=" . $id_partida_seleccionada . "
										and maestro_presupuesto.idRegistro=partidas_cedentes_traslado.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento") or die(mysql_error());

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

    $sql_traslados_presupuestarios = mysql_query("select * from traslados_presupuestarios
												where status='a'
												and idtraslados_presupuestarios=" . $idtraslados_presupuestarios
        , $conexion_db) or die(mysql_error());
    $regtraslados_presupuestarios = mysql_fetch_assoc($sql_traslados_presupuestarios);

//     $idtraslados_presupuestarios=$regtraslados_presupuestarios["idtraslados_presupuestarios"];

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
														partidas_receptoras_traslado.idtraslados_presupuestarios,
														partidas_receptoras_traslado.idmaestro_presupuesto,
														partidas_receptoras_traslado.idpartida_receptoras_traslado,
														partidas_receptoras_traslado.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_receptoras_traslado,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
															where
										partidas_receptoras_traslado.status='a'
										and partidas_receptoras_traslado.idpartida_receptoras_traslado=" . $id_partida_seleccionada . "
										and maestro_presupuesto.idRegistro=partidas_receptoras_traslado.idmaestro_presupuesto
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

if ($_POST["idtraslados_emergente"] != "") {
    // SI ESTA VARIABLE OCULTA TOMA VALOR ENVIADO DE UNA VENTANA EMERGENTE
    $sql_traslados_presupuestarios = mysql_query("select * from traslados_presupuestarios
												where status='a'
												and idtraslados_presupuestarios=" . $_POST["idtraslados_emergente"]
        , $conexion_db);
    $regtraslados_presupuestarios = mysql_fetch_assoc($sql_traslados_presupuestarios);
    //$idtraslados_presupuestarios=$regtraslados_presupuestarios["idtraslados_presupuestarios"];

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
							from
						maestro_presupuesto,
						tipo_presupuesto,
						clasificador_presupuestario,
						categoria_programatica,
						fuente_financiamiento,
						ordinal
							where
						maestro_presupuesto.status='a'
						and maestro_presupuesto.idtipo_presupuesto=tipo_presupuesto.idtipo_presupuesto
						and maestro_presupuesto.idclasificador_presupuestario=clasificador_presupuestario.idclasificador_presupuestario
						and maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento
						and maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica
						and maestro_presupuesto.idordinal=ordinal.idordinal
						and maestro_presupuesto.idRegistro like '" . $_POST['idmaestropresupuesto'] . "'";
    $sql_maestro            = mysql_query($sql, $conexion_db) or die("error " . mysql_error());
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

if ($emergente and $_GET["accion"] != 250) {
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
		if (document.frmtraslados_presupuestarios.nro_solicitud.value.length==0){
			mostrarMensajes("error", "Debe escribir un Numero de Solicitud para el Traslado Presupuestarios");
			document.frmtraslados_presupuestarios.nro_solicitud.focus()
			return false;
		}
		if (document.frmtraslados_presupuestarios.justificacion.value.length==0){
			mostrarMensajes("error", "Debe escribir una Justificaci&oacute;n para el Traslado Presupuestario");
			document.frmtraslados_presupuestarios.justificacion.focus()
			return false;
		}

	}

function abreVentanaPresupuesto(){
	m=document.frmtraslados_presupuestarios.modoactual.value;
	g=document.frmtraslados_presupuestarios.guardo.value;
	nro=document.frmtraslados_presupuestarios.nro.value;
	j=document.frmtraslados_presupuestarios.juntos.value;
	miPopup=window.open("lib/listas/lista_presupuestos.php?m="+m+"&g="+g+"&i="+nro+"&j="+j,"presupuestos","width=1200,height=600,scrollbars=yes")
	miPopup.focus()
}

function abreVentanaCA(){
	m=document.frmtraslados_presupuestarios.modoactual.value;
	j=document.frmtraslados_presupuestarios.juntos.value;
	g=document.frmtraslados_presupuestarios.guardo.value;
	miPopup=window.open("lib/listas/lista_traslados_presupuestarios.php?m="+m+"&g="+guardo+"&j="+j,"traslados presupuestarios","width=800,height=600,scrollbars=yes")
	miPopup.focus()
}

function formatoNumero(idcampo) {
var frm = document.frmtraslados_presupuestarios;
var res =  frm.elements[idcampo].value;
frm.elements["id"+idcampo+""].value = res;
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
    <h4 align=center>Traslados Presupuestarios</h4>
	<h2 class="sqlmVersion"></h2>

	<?php
if ($regtraslados_presupuestarios["nro_solicitud"] != "") {
    $btimprimir = "visibility:visible;";
} else {
    $btimprimir = "visibility:hidden;";
}

?>
	<table align=center cellpadding=2 cellspacing=0 width="10%">
			<tr>
				<td align='center' ><img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaCA()" title="Buscar Traslados Presupuestarios">&nbsp;<a href="principal.php?modulo=2&accion=47">
				<img src="imagenes/nuevo.png" border="0" title="Nuevo Traslado Presupuestario"></a>&nbsp;<img src="imagenes/imprimir.png" id="btimprimir" style="cursor:pointer; <?=$btimprimir?>" title="Imprimir Traslado Presupuestario"  onClick="document.getElementById('divTipo').style.display='block'; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='block';" />
                </td>
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
                                <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=traslado_presupuestario&id_traslado='+document.getElementById('idtraslados_presupuestarios').value+'&solicitud='+document.getElementById('solicitud').checked+'&simulado='+document.getElementById('simulado').checked; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block'; document.getElementById('divTipo').style.display='none'; document.getElementById('tableImprimir').style.display='none';">
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
//echo "ruta: ".$ruta;
//echo " total credito ".$regtraslados_presupuestarios["total_credito"];
//echo " total debito ".$regtraslados_presupuestarios["total_debito"];
//echo " idpartida".$id_partida_seleccionada;
?>

	<form name="frmtraslados_presupuestarios" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">

    <input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $modo . '"'; ?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="' . $guardo . '"'; ?>>
	<input type="hidden" name="nro" id="nro" <?php echo 'value="' . $nro_solicitud_credito . '"'; ?>>
	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="' . $juntos . '"'; ?>>
	<input type="hidden" name="fecha_ingreso" id="fecha_ingreso" <?php echo 'value="' . date("d/m/Y", strtotime("-0 day")) . '"'; ?>>
	<input type="hidden" name="idtraslados_presupuestarios" id="idtraslados_presupuestarios" <?php if (isset($_POST["idtraslados_presupuestarios"])) {echo 'value="' . $_POST["idtraslados_presupuestarios"] . '"';} else {echo 'value="' . $regtraslados_presupuestarios["idtraslados_presupuestarios"] . '"';}?>>
	<input type="hidden" name="idtraslados_emergente" id="idtraslados_emergente" <?php echo 'value="' . $regtraslados_presupuestarios['idtraslados_presupuestarios'] . '"'; ?>>
	<input type="hidden" name="emergente" maxlength="5" size="5" id="emergente" <?php echo 'value="' . $_POST['emergente'] . '"'; ?>>
	<input type="hidden" name="id_partida_seleccionada" id="id_partida_seleccionada" <?php echo 'value="' . $id_partida_seleccionada . '"'; ?>>
    <input type="hidden" name="cuerpo" id="cuerpo" value="<?=$_REQUEST["cuerpo"]?>" >
    <input type="hidden" name="idmaestropresupuesto" id="idmaestropresupuesto" <?php echo 'value="' . $regmaestro_presupuesto['idRegistro_maestro'] . '"'; ?>>

		<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
			  <td align='right' >&nbsp;</td>
			  <td colspan="2" class=''><b><?echo $regtraslados_presupuestarios["estado"]; ?></b></td>
			  <td>&nbsp;</td>
			  <td align='right' >&nbsp;</td>
			  <td class=''>&nbsp;</td>
			  <td align='right'>&nbsp;</td>
			  <td>&nbsp;</td>
			</tr>

			<tr>
				<td align='right' class='viewPropTitle' width="10%">Nro. Solicitud:</td>
				<td class='' width="10%"><input type="text" id="nro_solicitud" name="nro_solicitud" maxlength="12" size="12" value="<?php echo $regtraslados_presupuestarios["nro_solicitud"]; ?>">
				</td>
				<td align='right' class='viewPropTitle' width="12%">Fecha Solicitud:</td>
				<td width="15%">
				<input name="fecha_solicitud" type="text" id="fecha_solicitud" value="<?php
if ($guardo) {echo substr($regtraslados_presupuestarios['fecha_solicitud'], 8, 2) . '/' . substr($regtraslados_presupuestarios['fecha_solicitud'], 5, 2) . '/' . substr($regtraslados_presupuestarios['fecha_solicitud'], 0, 4) . '"';} else {echo date("d/m/Y", strtotime("-0 day"));}?>" size="13" maxlength="10">
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
				<td align='right' class='viewPropTitle' width="10%">Nro. Resolucion:</td>
					<td class='' width="10%"><input type="text" id="nro_resolucion" name="nro_resolucion" maxlength="12" size="12" value="<?php echo $regtraslados_presupuestarios["nro_resolucion"]; ?>">
				</td>
				<td align='right' class='viewPropTitle' width="13%">Fecha Resoluci&oacute;n:</td>
				<td width="15%">
				<input name="fecha_resolucion" type="text" id="fecha_resolucion" value="<?php if ($guardo) {echo substr($regtraslados_presupuestarios['fecha_resolucion'], 8, 2) . '/' . substr($regtraslados_presupuestarios['fecha_resolucion'], 5, 2) . '/' . substr($regtraslados_presupuestarios['fecha_resolucion'], 0, 4) . '"';} else {echo date("d/m/Y", strtotime("-0 day"));}?>" size="13" maxlength="10">
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
					<td class='' colspan="7"><textarea id="justificacion" name="justificacion" cols="137" rows="3" ><?php echo $regtraslados_presupuestarios["justificacion"]; ?></textarea>
				</td>
			</tr>
      </table>
            <table align=center cellpadding=2 cellspacing=0 width="77%">
<tr>

				<td width="10%" align='right' class='viewPropTitle'>A&ntilde;o:</td>
				<td width="13%" class='viewProp'>
				<select name="anio" style="width:80%">
                        <?
anio_fiscal();
?>
				</select>
			  </td>
				<td align='right' class='viewPropTitle' colspan="2">Total Disminuidas:</td>
					<td class='' width="12%"><input type="label" style="text-align:right" id="total_cedentes" name="total_cedentes" maxlength="18" size="18"
										<?php echo ' value="' . number_format($regtraslados_presupuestarios["total_debito"], 2, ",", ".") . '"'; ?>>
				</td>
  <td width="9%">
			    <a href="#" onClick="recalcularTotalTrasladoD()" id="recalcularTotalTrasladoD"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular sumatoria"></a>				</td>
                <td align='right' class='viewPropTitle' colspan="2">Total Aumentadas:</td>
					<td class='' width="12%"><input type="label" style="text-align:right" id="total_receptoras" name="total_receptoras" maxlength="18" size="18"
										<?php echo ' value="' . number_format($regtraslados_presupuestarios["total_credito"], 2, ",", ".") . '"'; ?>>
				</td>
       	  <td width="10%">
			    <a href="#" onClick="recalcularTotalTrasladoI()" id="recalcularTotalTrasladoI"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular sumatoria"></a>				</td>
			</tr>
		</table>

<!-- TABLA QUE MUESTRA LOS BOTONES -->
		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			  <?php

if ($modopartidas == 4) {
    echo "<input align=center class='button' name='actualizar' type='submit' value='Actualizar Duplicado'>";
}

if ($modopartidas != 4 and ($juntos != 1 and $_GET["accion"] != 251 and $_GET["accion"] != 252 and in_array(250, $privilegios) == true)) {
    echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
}

if ($modopartidas != 4 and ($juntos == 1 or $_GET["accion"] == 251 or $_GET["accion"] == 252 and in_array($_GET["accion"], $privilegios) == true) and $regtraslados_presupuestarios["estado"] == "elaboracion") {
    echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
}

if ($modopartidas != 4 and ($juntos == 1 or $_GET["accion"] == 251 or $_GET["accion"] == 252 and in_array($_GET["accion"], $privilegios) == true) and $regtraslados_presupuestarios["estado"] == "elaboracion") {
    echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
}

if ($modopartidas != 4 and $regtraslados_presupuestarios["estado"] == "elaboracion") {
    echo "<input align=center class='button' name='procesar' type='submit' value='Procesar'>";
}

if ($modopartidas != 4 and $regtraslados_presupuestarios["estado"] == "procesado") {
    echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
    echo "<input align=center class='button' name='anular' type='submit' value='Anular'>";
    echo "<input align=center class='button' name='duplicar' type='submit' value='Duplicar'>";
}

if ($modopartidas != 4 and $regtraslados_presupuestarios["estado"] == "Anulado") {
    echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
    echo "<input align=center class='button' name='duplicar' type='submit' value='Duplicar'>";
}

?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>
	<br>

    <!-- PARTIDAS CEDENTES-->
	<?php if ($guardo) {?>

		<table align=center cellpadding="1" cellspacing="0" width="80%" >
	  		<tr>
      		<td class='viewPropTitle'><strong>Partidas Cedentes</strong></td>
            <td align="right" class='viewPropTitle'><a href="#" onClick="abrirCerrarCedentes()" id="textoContraerCedentes"><img border="0" src="imagenes/cerrar.gif" title="Cerrar" style="text-decoration:none"></td>
      		</tr>
      	</table>

	<?}?>
     <div id="divCedentes" style="display:block">
    <?
if ($regtraslados_presupuestarios["estado"] == "elaboracion" or (in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado")) {
    ?>

		<table align=center cellpadding="1" cellspacing="0" width="80%" >
          <tr>
      		<?if ($modopartidas == 0 or $cuerpo == 2 and $regtraslados_presupuestarios["estado"] == "elaboracion") {?>
				<td align='center' class='' width="5%" rowspan="2"><button name="listado_presupuesto" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="document.frmtraslados_presupuestarios.cuerpo.value=1, abreVentanaPresupuesto()"><img src='imagenes/search0.png'></button></td>
            <?}?>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Categoria Program&aacute;tica</td>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Partida</td>
				<td align='center' class='viewPropTitle' width="10%">Monto Disminuir</td>

                <?php
if ($modopartidas == 0 or $cuerpo == 2 and $regtraslados_presupuestarios["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="agregar_partida_cedente" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/save.png'></button></td>
				<?php }
    if ($modopartidas == 1 and $regtraslados_presupuestarios["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="modificar_partida_cedente" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/modificar.png'></button></td>
				<?php }
    if ((in_array(745, $privilegios) == true and $modopartidas == 2 and $regtraslados_presupuestarios["estado"] == "procesado") or ($modopartidas == 2 and $regtraslados_presupuestarios["estado"] == "elaboracion")) {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="eliminar_partida_cedente" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/delete.png'></button></td>
				<?php }?>

	    	</tr>

			<tr>
				<td class='' width="10%"><input type="label" name="codcategoria_programatica" id="codcategoria_programatica" maxlength="14" size="14"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . $regcategoria_programatica["codigo"] . '"';}
    }?>></td>
				<td class='' width="40%"><input type="label" name="denocategoria_programatica" id="denocategoria_programatica" maxlength="50" size="40"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . $regcategoria_programatica["denocategoriaprogramatica"] . '"';}
    }?>></td>
				<td class='' width="10%"><input type="label" name="codigo_cuenta" id="codigo_cuenta" maxlength="12" size="12"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . $regclasificador_presupuestario["codigo_cuenta"] . '"';}
    }?>></td>
				<td class='' width="40%"><input type="label" name="denopartida" id="denopartida" maxlength="50" size="40"
					<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {echo ' value="' . utf8_decode($regclasificador_presupuestario["denominacion"]) . '"';}
    }?>></td>
				<td class='' width="10%"><input type="text" style="text-align:right" name="monto_debitar" maxlength="20" size="20" id="monto_debitar"
									<?php if (!isset($_POST["limpiar"])) {
        if ($_REQUEST["cuerpo"] == 1) {
            if ($_GET["accion"] == 250 and $_POST["monto_debitar"] != "") {
                echo ' value="' . number_format($_POST["idmonto_debitar"], 2, ",", ".") . '"';
            }
            if ($_GET["accion"] == 251 || $_GET["accion"] == 252) {
                if ($_POST["monto_debitar"] == "") {echo ' value="' . number_format($regpartida_cedente_seleccionada['monto_debitar'], 2, ",", ".") . '"';} else {echo ' value="' . number_format($_POST['monto_debitar'], 2, ",", ".") . '"';}
            }

            if ($_GET["accion"] == 252) {
                echo " disabled";
            }

        }
    }
    ?> onBlur="formatoNumero(this.name)"></td>

			</tr>
			<tr>
				<td></td>
				<td><button name="limpiar" type="submit" style="background-color:white;border-style:none;cursor:pointer;"><font size="1">Limpiar</font></button></td>
			</tr>
		</table>

    	<input type="hidden" name="idtotal_debito" maxlength="18" size="18" id="idtotal_debito" <?php
if ($_GET["accion"] == 251) {
        if ($_POST["total_debito"] == "") {echo ' value="' . $regtraslados_presupuestarios['total_debito'] . '"';} else {echo ' value="' . $_POST['idtotal_debito'] . '"';}
    }?>>
		<?}?>

	<?php if ($existen_partidas_cedentes) {
    ?>

						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
					  	<thead>
								<tr>
									<td align="center" class="Browse">A&ntilde;o</td>
									<td align="center" class="Browse">Fuente de Financiamiento</td>
                                    <td align="center" class="Browse" colspan="2">Categor&iacute;a Program&aacute;tica</td>
									<td align="center" class="Browse" colspan="2">Partida Presupuestaria</td>
									<td align="center" class="Browse">Monto Disminuir</td>
                                    <?//if($regtraslados_presupuestarios["estado"]=="elaboracion" and $modopartidas<>4) {?>
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
        echo "<td align='left' class='Browse' width='40%'>";if ($llenar_grilla["codigoordinal"] != 0000) {echo $llenar_grilla["denoordinal"];} else {echo utf8_decode($llenar_grilla["denopartida"]);}
        echo "</td>";
        if ($regtraslados_presupuestarios["estado"] == "elaboracion" or $regtraslados_presupuestarios["estado"] == "Anulado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_debitar"], 2, ",", ".") . "</td>";
        } else if (in_array(745, $privilegios) == false and $regtraslados_presupuestarios["estado"] == "procesado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_debitar"], 2, ",", ".") . "</td>";
        } else if (in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado") { ?>
                                    	<td align='right' class='Browse' width='8%'>
                                        <input align="right" style="text-align:right"
                                        					name="monto_debitar<?=$llenar_grilla["idpartida_cedentes_traslado"]?>"
            												type="hidden"
                                                            id="monto_debitar<?=$llenar_grilla["idpartida_cedentes_traslado"]?>"
                                                            size="20"
                                                            value="<?=$llenar_grilla["monto_debitar"]?>">
										<input align="right" style="text-align:right"
                                        					name="monto_debitar_mostrado<?=$llenar_grilla["idpartida_cedentes_traslado"]?>"
            												type="text"
                                                            id="monto_debitar_mostrado<?=$llenar_grilla["idpartida_cedentes_traslado"]?>"
                                                            size="20"
                                                            onclick="this.select()"
                                                            onblur="formatoNumeroPpt(this.id, 'monto_debitar<?=$llenar_grilla["idpartida_cedentes_traslado"]?>')"
                                                            value="<?=number_format($llenar_grilla["monto_debitar"], 2, ',', '.')?>">
                                       </td>
									<?}
        $c      = $llenar_grilla["idpartida_cedentes_traslado"];
        $i      = $llenar_grilla["idtraslados_presupuestarios"];
        $guardo = true;
        if ($regtraslados_presupuestarios["estado"] == "Anulado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        } else if (in_array(745, $privilegios) == false and $regtraslados_presupuestarios["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        }
        if (in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'> ";
            ?> <a href="#" onClick="recalcularPartidaTrasladoCedente('<?=$llenar_grilla["idmaestro_presupuesto"]?>','<?=$llenar_grilla["idtraslados_presupuestarios"]?>', document.getElementById('monto_debitar<?=$llenar_grilla["idpartida_cedentes_traslado"]?>').value)" id="recalcularPartidaTrasladoCedente"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular partida"></a></td> <?

        }

        if (in_array(251, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "elaboracion" and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
										<a href='principal.php?modulo=2&accion=251&modopartidas=1&c=$c&juntos=1&idtraslados_presupuestarios=$i&guardo=$guardo&modo=2&cuerpo=1'
										 class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Borrar'></a></td>";
        }
        if ((in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado") or (in_array(252, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "elaboracion") and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
										<a href='principal.php?modulo=2&accion=252&modopartidas=2&c=$c&juntos=1&idtraslados_presupuestarios=$i&guardo=$guardo&modo=2&cuerpo=1'
										class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
        }
        echo "</tr>";
    }
    ?>
					</table>
  	<?}?>

    </div>



	<!-- PARTIDAS RECEPTORAS-->
	<?if ($guardo) {
    ?>
    	<br><br>
      	<table align=center cellpadding="1" cellspacing="0" width="80%">
	  		<tr>
      			<td class='viewPropTitle'><strong>Partidas Receptoras</strong></td>
                <td align="right" class='viewPropTitle'>
                	<a href="#" onClick="abrirCerrarReceptoras()" id="textoContraerReceptoras">
                    	<img border="0" src="imagenes/cerrar.gif" title="Cerrar" style="text-decoration:none">
                    </a>
                </td>
      		</tr>
   		</table>
   <?php }?>
   <div id="divReceptoras" style="display:block">
   <?
if ($regtraslados_presupuestarios["estado"] == "elaboracion" or (in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado")) {
    ?>
	<table align=center cellpadding="1" cellspacing="0" width="80%">
      <tr>
      		<?if ($modopartidas == 0 or $cuerpo == 1 and $regtraslados_presupuestarios["estado"] == "elaboracion") {?>
				<td align='center' class='' width="5%" rowspan="2"><button name="listado_presupuesto" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="document.frmtraslados_presupuestarios.cuerpo.value=2, abreVentanaPresupuesto()"><img src='imagenes/search0.png'></button></td>
            <?}?>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Categoria Program&aacute;tica</td>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Partida</td>
				<td align='center' class='viewPropTitle' width="10%">Monto Aumentar</td>

                <?php
if ($modopartidas == 0 or $cuerpo == 1 and $regtraslados_presupuestarios["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="agregar_partida_receptora" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/save.png'></button></td>
				<?php }
    if ($modopartidas == 8 and $regtraslados_presupuestarios["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="modificar_partida_receptora" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/modificar.png'></button></td>
				<?php }
    if ((in_array(745, $privilegios) == true and $modopartidas == 9 and $regtraslados_presupuestarios["estado"] == "procesado") or ($modopartidas == 9 and $regtraslados_presupuestarios["estado"] == "elaboracion")) {?>
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
            echo ' value="' . $regcategoria_programatica["denocategoriaprogramatica"] . '"';
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
            if ($_GET["accion"] == 250 and $_POST["monto_acreditar"] != "") {
                echo ' value="' . number_format($_POST["idmonto_acreditar"], 2, ",", ".") . '"';
            }
            if ($_GET["accion"] == 251 || $_GET["accion"] == 252) {
                if ($_POST["monto_debitar"] == "") {echo ' value="' . number_format($regpartida_receptora_seleccionada['monto_acreditar'], 2, ",", ".") . '"';} else {echo ' value="' . number_format($_POST['monto_acreditar'], 2, ",", ".") . '"';}
            }

            if ($_GET["accion"] == 252) {
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
if ($_GET["accion"] == 251) {
        if ($_POST["total_credito"] == "") {echo ' value="' . $regtraslados_presupuestarios['total_credito'] . '"';} else {echo ' value="' . $_POST['idtotal_credito'] . '"';}
    }?>>

		<?}?>

	<?php if ($existen_partidas_receptoras) {
    ?>

						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="80%">
							<thead>
								<tr>
									<td align="center" class="Browse">A&ntilde;o</td>
									<td align="center" class="Browse">Fuente de Financiamiento</td>
                                    <td align="center" class="Browse" colspan="2">Categor&iacute;a Program&aacute;tica</td>
									<td align="center" class="Browse" colspan="2">Partida Presupuestaria</td>
									<td align="center" class="Browse">Monto Aumentar</td>
                                    <?//if ($regtraslados_presupuestarios["estado"]=="elaboracion" and $modopartidas<>4){?>
										<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                    <?// }?>
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
        //echo "<td align='center' class='Browse' width='8%'>".$llenar_grilla["partida"]." ".$llenar_grilla["generica"]." ".$llenar_grilla["especifica"]." ".$llenar_grilla["sub_especifica"]."</td>";
        echo "<td align='left' class='Browse' width='40%'>";if ($llenar_grilla["codigoordinal"] != 0000) {echo $llenar_grilla["denoordinal"];} else {echo utf8_decode($llenar_grilla["denopartida"]);}
        echo "</td>";

        if ($regtraslados_presupuestarios["estado"] == "elaboracion" or $regtraslados_presupuestarios["estado"] == "Anulado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_acreditar"], 2, ",", ".") . "</td>";
        } else if (in_array(745, $privilegios) == false and $regtraslados_presupuestarios["estado"] == "procesado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_acreditar"], 2, ",", ".") . "</td>";
        } else if (in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado") { ?>
                                    	<td align='right' class='Browse' width='8%'>
                                        <input align="right" style="text-align:right"
                                        					name="monto_acreditar<?=$llenar_grilla["idpartida_receptoras_traslado"]?>"
            												type="hidden"
                                                            id="monto_acreditar<?=$llenar_grilla["idpartida_receptoras_traslado"]?>"
                                                            size="20"
                                                            value="<?=$llenar_grilla["monto_acreditar"]?>">
										<input align="right" style="text-align:right"
                                        					name="monto_acreditar_mostrado<?=$llenar_grilla["idpartida_receptoras_traslado"]?>"
            												type="text"
                                                            id="monto_acreditar_mostrado<?=$llenar_grilla["idpartida_receptoras_traslado"]?>"
                                                            size="20"
                                                            onclick="this.select()"
                                                            onblur="formatoNumeroPpt(this.id, 'monto_acreditar<?=$llenar_grilla["idpartida_receptoras_traslado"]?>')"
                                                            value="<?=number_format($llenar_grilla["monto_acreditar"], 2, ',', '.')?>">
                                       </td>
									<?}

        $c      = $llenar_grilla["idpartida_receptoras_traslado"];
        $i      = $llenar_grilla["idtraslados_presupuestarios"];
        $guardo = true;

        if ($regtraslados_presupuestarios["estado"] == "Anulado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        } else if (in_array(745, $privilegios) == false and $regtraslados_presupuestarios["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        }
        if (in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'> ";
            ?> <a href="#" onClick="recalcularPartidaTrasladoReceptora('<?=$llenar_grilla["idmaestro_presupuesto"]?>','<?=$llenar_grilla["idtraslados_presupuestarios"]?>', document.getElementById('monto_acreditar<?=$llenar_grilla["idpartida_receptoras_traslado"]?>').value)" id="recalcularPartidaTrasladoReceptora"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular partida"></a></td> <?

        }

        if (in_array(251, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "elaboracion" and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
											<a href='principal.php?modulo=2&accion=251&modopartidas=8&c=$c&juntos=1&idtraslados_presupuestarios=$i&guardo=$guardo&modo=1&cuerpo=2'
											class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
        }
        if ((in_array(745, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "procesado") or in_array(252, $privilegios) == true and $regtraslados_presupuestarios["estado"] == "elaboracion" and $modopartidas != 4) {
            echo "<td align='center' class='Browse' width='3%'>
										<a href='principal.php?modulo=2&accion=252&modopartidas=9&c=$c&juntos=1&idtraslados_presupuestarios=$i&guardo=$guardo&modo=2&cuerpo=2'
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
echo "<script> document.frmtraslados_presupuestarios.nro_solicitud.focus() </script>";
if ($cuerpo == 1) {echo "<script> document.frmtraslados_presupuestarios.monto_debitar.focus(), document.frmtraslados_presupuestarios.monto_debitar.select()  </script>";}
if ($cuerpo == 2) {echo "<script> document.frmtraslados_presupuestarios.monto_acreditar.focus(), document.frmtraslados_presupuestarios.monto_acreditar.select()  </script>";}

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
    $idtraslados_presupuestarios = $_POST["idtraslados_presupuestarios"];
    $fh                          = date("Y-m-d H:i:s");
    $pc                          = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $busca_traslado              = mysql_query("select * from traslados_presupuestarios where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'", $conexion_db);
    $traslado                    = mysql_fetch_array($busca_traslado);

//****************************************************************************************************************************************
    //  PARTIDAS CEDENTES
    //
    //****************************************************************************************************************************************
    // INGRESAR PARTIDAS CEDENTES
    //****************************************************************************************************************************************
    $monto_debitar = $_POST["monto_debitar"];

    if (isset($_POST["agregar_partida_cedente"])) {
        $idmaestro_presupuesto = $_POST["idmaestropresupuesto"];
        $busca_existe_partida  = mysql_query("select * from partidas_cedentes_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_partida) > 0) {
            ?>
				<script>
			mostrarMensajes("error", "Disculpe, la partida ya fue ingresada al Traslado Presupuestario");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
			</script>

		<?
        } else {
            // BUSCA QUE NO EXISTA EN LAS RECEPTORAS
            $busca_existe_receptora = mysql_query("select * from partidas_receptoras_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
            if (mysql_num_rows($busca_existe_receptora) > 0) {

                ?>
				<script>
			mostrarMensajes("error", "Disculpe, la partida ya fue ingresada al Traslado Presupuestario como Receptora");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
			</script>

		<?

            } else {

                $disponible_compromiso = consultarDisponibilidad($idmaestro_presupuesto);

                $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idregistro = '" . $idmaestro_presupuesto . "'
																				and status='a'");
                $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
                //$disponible_compromiso=$datos_maestro_presupuesto["monto_actual"];
                $disponible_compromiso_resta = $monto_debitar;

                //$resta0 = $disponible_compromiso - ($disponible_compromiso_resta);
                $resta = bcsub($disponible_compromiso, $disponible_compromiso_resta, 2);

                if ($resta < 0) {

                    ?>
				<script>
				mostrarMensajes("error", "Disculpe, el monto a Debitar '<?=$monto_debitar?>' es mayor que el monto Disponible para disminuir '<?=($disponible_compromiso)?>'");
			//setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
			</script>

		<?
                } else {
                    mysql_query("insert into partidas_cedentes_traslado
									(idtraslados_presupuestarios,idmaestro_presupuesto,monto_debitar,usuario,fechayhora,status)
							values ('$idtraslados_presupuestarios','$idmaestro_presupuesto','$monto_debitar','$login','$fh','a')"
                        , $conexion_db);

                    mysql_query("update traslados_presupuestarios set
									total_debito=(total_debito)+'" . $monto_debitar . "'
									where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'"
                        , $conexion_db) or die(mysql_error());

                    if ($traslado["estado"] == "elaboracion") {
                        mysql_query("update maestro_presupuesto set
										reservado_disminuir = (reservado_disminuir) + '" . $monto_debitar . "'
										where 	idregistro = '" . $idmaestro_presupuesto . "'"
                            , $conexion_db) or die(mysql_error());
                        //monto_actual = (monto_actual) - '".$monto_debitar."'
                    } else if ($traslado["estado"] == "procesado") {
                        mysql_query("update maestro_presupuesto set
										total_disminucion = (total_disminucion) + '" . $monto_debitar . "'
										where 	idregistro = '" . $idmaestro_presupuesto . "'"
                            , $conexion_db) or die(mysql_error());
                    }
                    registra_transaccion('Ingresar Partida Cedente Traslado Presupuestario', $login, $fh, $pc, 'partidas_cedentes_traslado', $conexion_db);
                    redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");
                }
            }
        }
    }

//****************************************************************************************************************************************
    // MODIFICAR PARTIDAS CEDENTES
    //****************************************************************************************************************************************

    if (isset($_POST["modificar_partida_cedente"])) {
        // MODIFICO UNA PARTIDA CEDENTE

        $idpartida_seleccionada = $_POST["id_partida_seleccionada"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_cedentes_traslado
																	where idpartida_cedentes_traslado = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_cedente_traslado = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior              = $regpartida_cedente_traslado["monto_debitar"];
        $idmaestro_presupuesto       = $regpartida_cedente_traslado["idmaestro_presupuesto"];
        $idtraslados_presupuestarios = $regpartida_cedente_traslado["idtraslados_presupuestarios"];

        $disponible_compromiso = consultarDisponibilidad($idmaestro_presupuesto) + $monto_anterior;

        $disponible_compromiso_resta = $monto_debitar;

        //$resta0 = $disponible_compromiso - ($disponible_compromiso_resta);
        $resta = bcsub($disponible_compromiso, $disponible_compromiso_resta, 2);

        if ($resta < 0) {

            ?>
				<script>
			mostrarMensajes("error", "Disculpe, el monto a Debitar '<?=$monto_debitar?>' es mayor que el monto Disponible para disminuir '<?=($disponible_compromiso)?>'");
			//setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
			</script>

		<?

        } else {

            mysql_query("update partidas_cedentes_traslado set
									monto_debitar='" . $monto_debitar . "'
									where 	idpartida_cedentes_traslado = '$idpartida_seleccionada'
											and status = 'a'", $conexion_db);

            mysql_query("update traslados_presupuestarios set
									total_debito=(total_debito)-'" . $monto_anterior . "'+'" . $monto_debitar . "'
									where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'"
                , $conexion_db);
            if ($traslado["estado"] == "elaboracion") {
                mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir) + '" . $monto_debitar . "' - '" . $monto_anterior . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());
                //monto_actual = (monto_actual) - '".$monto_debitar."' + '".$monto_anterior."'
            } else if ($traslado["estado"] == "procesado") {
                mysql_query("update maestro_presupuesto set
									total_disminucion = (total_disminucion) + '" . $monto_debitar . "' - '" . $monto_anterior . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());
            }
            registra_transaccion('Modificar Partida Cedente Traslados Presupuestarios', $login, $fh, $pc, 'partidas_cedentes_traslado', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");
        }
    }

//****************************************************************************************************************************************
    // ELIMINAR PARTIDAS CEDENTES
    //****************************************************************************************************************************************

    if (isset($_POST["eliminar_partida_cedente"])) {
        // ELIMINO UNA PARTIDA AL CEDENTE

        $idpartida_seleccionada = $_POST["id_partida_seleccionada"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_cedentes_traslado where idpartida_cedentes_traslado = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_cedente_traslado = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior              = $regpartida_cedente_traslado["monto_debitar"];
        $idtraslados_presupuestarios = $regpartida_cedente_traslado["idtraslados_presupuestarios"];
        $idmaestro_presupuesto       = $regpartida_cedente_traslado["idmaestro_presupuesto"];

        mysql_query("delete from partidas_cedentes_traslado
									where 	idpartida_cedentes_traslado = '$idpartida_seleccionada'
											and status = 'a'", $conexion_db);

        mysql_query("update traslados_presupuestarios set
									total_debito=(total_debito)-'" . $monto_anterior . "'
									where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'"
            , $conexion_db);
        if ($traslado["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir)-'" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        } else if ($traslado["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_disminucion = (total_disminucion)-'" . $monto_anterior . "',
									monto_actual = (monto_actual) + '" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }
        registra_transaccion('Eliminar Partida Cedente Traslado Presupuestario', $login, $fh, $pc, 'partidas_cedentes_traslado', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");

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
        $busca_existe_partida  = mysql_query("select * from partidas_receptoras_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_partida) > 0) {
            mensaje("Disculpe, la partida ya fue ingresada al Traslado Presupuestario");
            redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");
        } else {
            $busca_existe_cedente = mysql_query("select * from partidas_cedentes_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
            if (mysql_num_rows($busca_existe_cedente) > 0) {
                mensaje("Disculpe, la partida ya fue ingresada al Traslado Presupuestario como Cedente");
                redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");
            } else {
                mysql_query("insert into partidas_receptoras_traslado
									(idtraslados_presupuestarios,idmaestro_presupuesto,monto_acreditar,usuario,fechayhora,status)
							values ('$idtraslados_presupuestarios','$idmaestro_presupuesto','$monto_acreditar','$login','$fh','a')"
                    , $conexion_db);

                mysql_query("update traslados_presupuestarios set
									total_credito=(total_credito)+'" . $monto_acreditar . "'
									where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'"
                    , $conexion_db);
                if ($traslado["estado"] == "elaboracion") {
                    mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                        , $conexion_db) or die(mysql_error());
                } else if ($traslado["estado"] == "procesado") {
                    mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) + '" . $monto_acreditar . "',
									monto_actual = (monto_actual) + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                        , $conexion_db) or die(mysql_error());
                }
                registra_transaccion('Ingresar Partida Receptora Traslados Presupuestarios', $login, $fh, $pc, 'partidas_receptoras_traslado', $conexion_db);
                redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");
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

        $busca_existe_partida = mysql_query("select * from partidas_receptoras_traslado where idpartida_receptoras_traslado = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_receptora_seleccionada = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior                    = $regpartida_receptora_seleccionada["monto_acreditar"];
        $idmaestro_presupuesto             = $regpartida_receptora_seleccionada["idmaestro_presupuesto"];
        $idtraslados_presupuestarios       = $regpartida_receptora_seleccionada["idtraslados_presupuestarios"];

        mysql_query("update partidas_receptoras_traslado set
									monto_acreditar='" . $monto_acreditar . "'
									where 	idpartida_receptoras_traslado= '$idpartida_seleccionada'
											and status = 'a'", $conexion_db);

        mysql_query("update traslados_presupuestarios set
									total_credito=(total_credito)-'" . $monto_anterior . "'+'" . $monto_acreditar . "'
									where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'"
            , $conexion_db);
        if ($traslado["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_anterior . "' + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        } else if ($traslado["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) - '" . $monto_anterior . "' + '" . $monto_acreditar . "',
									monto_actual = (monto_actual) + '" . $monto_acreditar . "' - '" . $monto_anterior . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }
        registra_transaccion('Modificar Partida Receptora Traslados Presupuestarios', $login, $fh, $pc, 'partidas_receptoras_traslado', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");

    }

//****************************************************************************************************************************************
    // ELIMINAR PARTIDAS RECEPTORA
    //****************************************************************************************************************************************

    if (isset($_POST["eliminar_partida_receptora"])) {
        // ELIMINO UNA PARTIDA AL CEDENTE

        $idpartida_seleccionada = $_POST["id_partida_seleccionada"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_receptoras_traslado where idpartida_receptoras_traslado = '" . $idpartida_seleccionada . "'
																				and status='a'", $conexion_db);
        $regpartida_receptora_seleccionada = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior                    = $regpartida_receptora_seleccionada["monto_acreditar"];
        $idtraslados_presupuestarios       = $regpartida_receptora_seleccionada["idtraslados_presupuestarios"];
        $idmaestro_presupuesto             = $regpartida_receptora_seleccionada["idmaestro_presupuesto"];

        mysql_query("delete from partidas_receptoras_traslado
									where 	idpartida_receptoras_traslado = '$idpartida_seleccionada'
											and status = 'a'", $conexion_db);

        mysql_query("update traslados_presupuestarios set
									total_credito=(total_credito)-'" . $monto_anterior . "'
									where 	idtraslados_presupuestarios= '$idtraslados_presupuestarios'"
            , $conexion_db);
        if ($traslado["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        } else if ($traslado["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) - '" . $monto_anterior . "',
									monto_actual = (monto_actual) - '" . $monto_anterior . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }
        registra_transaccion('Eliminar Partida Receptoras Traslado Presupuestario', $login, $fh, $pc, 'partidas_receptoras_traslado', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");

    }

//*************************************************************************************************************************************************************************
    //    PROCESAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["procesar"])) {

        $busca_traslado = mysql_query("select * from traslados_presupuestarios where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'") or die(mysql_error());
        $regtraslado    = mysql_fetch_array($busca_traslado);
        //echo $regtraslado["total_debito"];
        //echo $regtraslado["total_credito"];
        //exit();
        //echo $regtraslado["total_debito"];
        if (bccomp($regtraslado["total_debito"], $regtraslado["total_credito"], 2) == 0) {

            $busca_cedentes = mysql_query("select * from partidas_cedentes_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
            if (mysql_num_rows($busca_cedentes) > 0) {
                $busca_receptoras = mysql_query("select * from partidas_receptoras_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
                if (mysql_num_rows($busca_receptoras) > 0) {

                    $no_procesa = 0;
                    while ($procesar_cedentes = mysql_fetch_array($busca_cedentes)) {
                        $monto_debitar         = $procesar_cedentes["monto_debitar"];
                        $idmaestro_presupuesto = $procesar_cedentes["idmaestro_presupuesto"];
                        //*********** VALIDAR QUE ESA PARTIDA TENGA ESE MONTO **********************//
                        $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idregistro = '" . $idmaestro_presupuesto . "'
																				and status='a'");
                        $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
                        $disponible_compromiso     = $datos_maestro_presupuesto["monto_actual"] - $datos_maestro_presupuesto["total_compromisos"];

                        $resta = bcsub($datos_maestro_presupuesto["monto_actual"], $datos_maestro_presupuesto["total_compromisos"], 2);

                        if ($resta < 0) {

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
                                mostrarMensajes("error", "La Partida '<?=$regcategoria_programatica["codigo"]?>' '<?=$regcategoria_programatica["denocategoriaprogramatica"]?>' '<?=$regclasificar_presupuestario["codigo_cuenta"]?>' '<?=$regclasificador_presupuestario["denominacion"]?>' tiene una Disponible de '<?=$disponible_compromiso?>' y al en este Documento se le restar&iacute;a '<?=$monto_debitar?>' lo que sobregiraria la Partida, por lo que no puede Procesar este documento");
                                setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
                                </script>

                            <?
                            $no_procesa = 1;
                        }

                    }
                    if ($no_procesa == 0) {
                        $busca_cedentes = mysql_query("select * from partidas_cedentes_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
                        while ($procesar_cedentes = mysql_fetch_array($busca_cedentes)) {
                            $monto_debitar         = $procesar_cedentes["monto_debitar"];
                            $idmaestro_presupuesto = $procesar_cedentes["idmaestro_presupuesto"];
                            mysql_query("update maestro_presupuesto set
										total_disminucion = total_disminucion+'" . $monto_debitar . "',
										reservado_disminuir = reservado_disminuir - '" . $monto_debitar . "',
										monto_actual = monto_actual-'" . $monto_debitar . "'
										where 	idRegistro = " . $idmaestro_presupuesto . ""
                                , $conexion_db);

                        }

                        while ($procesar_receptoras = mysql_fetch_array($busca_receptoras)) {
                            $monto_acreditar       = $procesar_receptoras["monto_acreditar"];
                            $idmaestro_presupuesto = $procesar_receptoras["idmaestro_presupuesto"];

                            mysql_query("update maestro_presupuesto set
										total_aumento = total_aumento + '" . $monto_acreditar . "',
										solicitud_aumento = solicitud_aumento - '" . $monto_acreditar . "',
										monto_actual = monto_actual + '" . $monto_acreditar . "'
										where 	idRegistro = '$idmaestro_presupuesto'"
                                , $conexion_db);

                        }

                        mysql_query("update traslados_presupuestarios set
											estado='procesado',
											fechayhora='" . $fh . "',
											usuario='" . $login . "'
											where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
												and status = 'a'", $conexion_db);

                        registra_transaccion('Procesar Traslados Presupuestarios - EXITOSO', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
                        ?>
				<script>
			mostrarMensajes("exito", "El documento de Traslados Presupuestarios se proceso con exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47'",5000);
			</script>

		<?

                    }
                } else {
                    registra_transaccion('ERROR - Procesar Traslados Presupuestarios - No tiene partidas receptoras cargadas', $login, $fh, $pc, 'partidas_receptoras_traslado', $conexion_db);
                    ?>
				<script>
			mostrarMensajes("error", "ERROR -  No existen partidas Receptoras en el documento, por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
			</script>

		<?
                }
            } else {
                registra_transaccion('ERROR - Procesar Traslados Presupuestarios - No tiene partidas Cedentes cargadas', $login, $fh, $pc, 'partidas_cedentes_traslado', $conexion_db);
                ?>
				<script>
			mostrarMensajes("error", "ERROR -  No existen partidas Cedentes en el documento, por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
			</script>

		<?

            }
        } else {
            registra_transaccion('ERROR - Procesar Traslados Presupuestarios - No coinciden montos debitar y acreditar', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
            ?>
				<script>
			mostrarMensajes("error", "ERROR -  No coinciden el Monto a Debitar (<?=$regtraslado["total_debito"]?>) y el Monto a Acreditar (<?=$regtraslado["total_credito"]?>), por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=<?=$idtraslados_presupuestarios?>&juntos=1&modopartidas=0'",5000);
			</script>

		<?
        }
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //    ANULAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["anular"])) {

        $busca_cedentes   = mysql_query("select * from partidas_cedentes_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
        $busca_receptoras = mysql_query("select * from partidas_receptoras_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
        $no_anula         = 0;
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
					mostrarMensajes("error", "La Partida '<?=$regcategoria_programatica["codigo"]?>' '<?=$regcategoria_programatica["denocategoriaprogramatica"]?>' '<?=$regclasificar_presupuestario["codigo_cuenta"]?>' '<?=$regclasificador_presupuestario["denominacion"]?>' tiene una Disponible de '<?=$disponible_compromiso?>' y al ANULAR este Documento se le restar&iacute;a '<?=$monto_acreditar?>' lo que sobregiraria la Partida, por lo que no puede Anular este documento");
					setTimeout("window.location.href='principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0'",5000);
					</script>

					<?
                $no_anula = 1;
            }

        }
        if ($no_anula == 0) {
            while ($procesar_cedentes = mysql_fetch_array($busca_cedentes)) {
                $monto_debitar         = $procesar_cedentes["monto_debitar"];
                $idmaestro_presupuesto = $procesar_cedentes["idmaestro_presupuesto"];

                mysql_query("update maestro_presupuesto set
										total_disminucion= total_disminucion - '" . $monto_debitar . "',
										monto_actual = monto_actual + '" . $monto_debitar . "'
										where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }
            $busca_receptoras = mysql_query("select * from partidas_receptoras_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
            while ($procesar_receptoras = mysql_fetch_array($busca_receptoras)) {
                $monto_acreditar       = $procesar_receptoras["monto_acreditar"];
                $idmaestro_presupuesto = $procesar_receptoras["idmaestro_presupuesto"];

                mysql_query("update maestro_presupuesto set
										total_aumento= total_aumento - '" . $monto_acreditar . "',
										monto_actual = monto_actual - '" . $monto_acreditar . "'
										where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }

            mysql_query("update traslados_presupuestarios set
											estado='Anulado',
											fechayhora='" . $fh . "',
											usuario='" . $login . "'
											where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
												and status = 'a'", $conexion_db);

            registra_transaccion('ANULAR Traslados Presupuestarios', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
            ?>
				<script>
			mostrarMensajes("error", "La ANULACION del documento de Traslados Presupuestarios se proceso con exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=47'",5000);
			</script>

		<?

        }

    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //    DUPLICAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["duplicar"])) {
        $duplicado       = true;
        $busca_traslados = mysql_query("select * from traslados_presupuestarios where idtraslados_presupuestarios = '$idtraslados_presupuestarios' and status = 'a'");
        $regtraslados    = mysql_fetch_assoc($busca_traslados);
        $total_credito   = $regtraslados["total_credito"];
        $total_debito    = $regtraslados["total_debito"];

        mysql_query("insert into traslados_presupuestarios
									(fecha_solicitud,fecha_resolucion,fecha_ingreso,justificacion,anio,total_credito,total_debito,estado,usuario,fechayhora,status)
							values ('$fecha_solicitud','$fecha_resolucion','$fecha_ingreso','$justificacion','$anio','$total_credito','$total_debito','elaboracion','$login','$fh','a')"
        ) or die(mysql_error());

        $nuevoid_traslado = mysql_insert_id();

        $busca_partidas_receptoras = mysql_query("select * from partidas_receptoras_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");

        while ($procesar_credito = mysql_fetch_array($busca_partidas_receptoras)) {
            $monto_acreditar       = $procesar_credito["monto_acreditar"];
            $idmaestro_presupuesto = $procesar_credito["idmaestro_presupuesto"];
            mysql_query("insert into partidas_receptoras_traslado
									(idtraslados_presupuestarios,idmaestro_presupuesto,monto_acreditar,usuario,fechayhora,status)
							values ('$nuevoid_traslado','$idmaestro_presupuesto','$monto_acreditar','$login','$fh','a')");

            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) + '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());

        }

        $busca_partidas_cedentes = mysql_query("select * from partidas_cedentes_traslado where idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "' and status='a'");
        $error                   = "false";
        while ($procesar_debito = mysql_fetch_array($busca_partidas_cedentes)) {
            $monto_debitar         = $procesar_debito["monto_debitar"];
            $idmaestro_presupuesto = $procesar_debito["idmaestro_presupuesto"];
            $disponible_compromiso = consultarDisponibilidad($idmaestro_presupuesto);

            $busca_partida_maestro = mysql_query("select * from maestro_presupuesto where idregistro = '" . $idmaestro_presupuesto . "'
																			and status='a'");
            $datos_maestro_presupuesto = mysql_fetch_array($busca_partida_maestro);
            //$disponible_compromiso=$datos_maestro_presupuesto["monto_actual"];
            $disponible_compromiso_resta = $monto_debitar;

            //$resta0 = $disponible_compromiso - ($disponible_compromiso_resta);
            $resta = bcsub($disponible_compromiso, $monto_debitar, 2);

            if ($resta < 0) {
                $error = "true";
            } else {

                mysql_query("insert into partidas_cedentes_traslado
									(idtraslados_presupuestarios,idmaestro_presupuesto,monto_debitar,usuario,fechayhora,status)
							values ('$nuevoid_traslado','$idmaestro_presupuesto','$monto_debitar','$login','$fh','a')");

                mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir) + '" . $monto_debitar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());

            }
        }

        if ($error == "false") {

            ?>
				<script>
			mostrarMensajes("exito", "El DUPLICADO del documento de Traslados Presupuestarios se proceso con exito");
			</script>

		<?

        } else {

            ?>
				<script>
			mostrarMensajes("error", "El DUPLICADO del documento de Traslados Presupuestarios se proceso con ERRORES - Algunas partidas cedentes no se copiaron porque generar&iacute;an un sobregiro presupuestario");
			setTimeout("",50000);
			</script>

		<?
        }
        registra_transaccion('DUPLICAR Traslado Presupuestario', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
        //redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1&modopartidas=0");
        redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$nuevoid_traslado&juntos=0&modopartidas=4");
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //   REGISTRAR DUPLICADO
    //*************************************************************************************************************************************************************************

    if (isset($_POST["actualizar"])) {

        mysql_query("update traslados_presupuestarios set
										nro_solicitud='" . $nro_solicitud . "',
										fecha_solicitud='" . $fecha_solicitud . "',
										nro_resolucion='" . $nro_resolucion . "',
										fecha_resolucion='" . $fecha_resolucion . "',
										justificacion='" . $justificacion . "',
										anio='" . $anio . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
											and status = 'a'", $conexion_db);
        registra_transaccion('Actualizar Encabezado de Traslado Presupuestario Duplicado ', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1");
    }

//********************************************************************************************************

    if (isset($_POST["ingresar"]) and in_array(250, $privilegios) == true) {
        $busca_existe_registro = mysql_query("select * from traslados_presupuestarios where nro_solicitud = '" . $_POST['nro_solicitud'] . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_registro) > 0) {
            mensaje("Disculpe el Numero de Solicitud que ingreso ya existe, por favor vuelva a intentarlo");

        } else {
            mysql_query("insert into traslados_presupuestarios
									(nro_solicitud,fecha_solicitud,nro_resolucion,fecha_resolucion,fecha_ingreso,justificacion,anio,total_credito,total_debito,estado,usuario,fechayhora,status)
							values ('$nro_solicitud','$fecha_solicitud','$nro_resolucion','$fecha_resolucion','$fecha_ingreso','$justificacion','$anio','$idtotal_credito','$idtotal_debito','elaboracion','$login','$fh','a')"
                , $conexion_db);
            $idtraslados_presupuestarios = mysql_insert_id();

            registra_transaccion('Ingresar Traslado Presupuestario', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1");
        }
    }

    if ($_POST["modificar"] and in_array(251, $privilegios) == true) {
        mysql_query("update traslados_presupuestarios set
										nro_solicitud='" . $nro_solicitud . "',
										fecha_solicitud='" . $fecha_solicitud . "',
										nro_resolucion='" . $nro_resolucion . "',
										fecha_resolucion='" . $fecha_resolucion . "',
										justificacion='" . $justificacion . "',
										anio='" . $anio . "',
										total_credito='" . $idtotal_credito . "',
										total_debito='" . $idtotal_debito . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
											and status = 'a'", $conexion_db);
        registra_transaccion('Modificar Encabezado Traslados Presupuestarios', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=47&guardo=true&idtraslados_presupuestarios=$idtraslados_presupuestarios&juntos=1");
    }

    if ($_POST["eliminar"] and in_array(252, $privilegios) == true) {

        $sql_cedentes = mysql_query("select * from partidas_cedentes_traslado
										where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
											and status = 'a'", $conexion_db) or die("ERROR cedentes " . mysql_error());

        while ($eliminar_cedentes = mysql_fetch_array($sql_cedentes)) {
            $monto_debitar         = $eliminar_cedentes["monto_debitar"];
            $idmaestro_presupuesto = $eliminar_cedentes["idmaestro_presupuesto"];
            mysql_query("update maestro_presupuesto set
									reservado_disminuir = (reservado_disminuir) - '" . $monto_debitar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());

        }

        mysql_query("delete from partidas_cedentes_traslado
										where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
											and status = 'a'", $conexion_db);

        $sql_receptoras = mysql_query("select * from partidas_receptoras_traslado
										where 	idtraslados_presupuestarios = '" . $idtraslados_presupuestarios . "'
											and status = 'a'", $conexion_db) or die(mysql_error());

        while ($eliminar_receptoras = mysql_fetch_array($sql_receptoras)) {
            $monto_acreditar       = $eliminar_receptoras["monto_acreditar"];
            $idmaestro_presupuesto = $eliminar_receptoras["idmaestro_presupuesto"];
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_acreditar . "'
									where 	idregistro = '" . $idmaestro_presupuesto . "'"
                , $conexion_db) or die(mysql_error());
        }

        mysql_query("delete from partidas_receptoras_traslado
										where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
											and status = 'a'", $conexion_db);

        mysql_query("delete from traslados_presupuestarios
										where 	idtraslados_presupuestarios = '$idtraslados_presupuestarios'
											and status = 'a'", $conexion_db);
        registra_transaccion('Eliminar Traslados Presupuestarios ', $login, $fh, $pc, 'traslados_presupuestarios', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=47");

    }
}

?>
