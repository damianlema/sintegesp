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
    $_GET["accion"] = 224;
}

$id_partida_credito_adicional = $_GET["c"];
$emergente                    = $_POST["emergente"];
//$guardo=$_GET["guardo"];
//$nro_solicitud_credito=$_GET["nro_sol"];
$juntos                 = $_GET["juntos"];
$idcreditos_adicionales = $_GET["idcreditos_adicionales"];
$modopartidas           = $_GET["modopartidas"];
$existen_partidas       = false;

if ($modopartidas == "") {
    $modopartidas = 0;
}

$guardo = $_REQUEST["guardo"];
if ($nro_solicitud_credito == "") {
    $nro_solicitud_credito = $_POST["nro"];
}

if ($juntos == "") {
    $juntos = $_POST["juntos"];
}

if ($_GET["idcreditos_adicionales"] == "") {
    $idcreditos_adicionales = $_POST["idcreditos_adicionales"];
}

$m = $_POST["modoactual"];
if ($m != "") {$modo = $m;}

$sql_configuracion = mysql_query("select * from configuracion
											where status='a'"
    , $conexion_db);
$registro_configuracion = mysql_fetch_assoc($sql_configuracion);
$anio_fijo              = $registro_configuracion["anio_fiscal"];

$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento
												where status='a'"
    , $conexion_db);

// *******************************************************************************************************************
// carga de credito adicional nuevo
if ($guardo and $idcreditos_adicionales != "") {
    $sql_credito_adicional = mysql_query("select * from creditos_adicionales
												where status='a'
												and idcreditos_adicionales=" . $idcreditos_adicionales
        , $conexion_db);
    $regcredito_adicional = mysql_fetch_assoc($sql_credito_adicional);

    $prueba1                        = $idcredito_adicional;
    $prueba11                       = $idcreditos_adicionales;
    $prueba2                        = $nro_solicitud_credito;
    $prueba3                        = $guardo;
    $sql_partidas_credito_adicional = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
														clasificador_presupuestario.partida as partida,
														clasificador_presupuestario.generica as generica,
														clasificador_presupuestario.especifica as especifica,
														clasificador_presupuestario.sub_especifica as sub_especifica,
														clasificador_presupuestario.denominacion as denopartida,
														categoria_programatica.codigo as codigocategoria,
														unidad_ejecutora.denominacion as denocategoriaprogramatica,
														maestro_presupuesto.idRegistro as idmaestro_presupuesto,
														maestro_presupuesto.anio as anio,
														ordinal.codigo as codigoordinal,
														ordinal.denominacion as denoordinal,
														partidas_credito_adicional.idcredito_adicional,
														partidas_credito_adicional.idmaestro_presupuesto,
														partidas_credito_adicional.idpartida_credito_adicional,
														partidas_credito_adicional.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_credito_adicional,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
															where
										partidas_credito_adicional.status='a'
										and partidas_credito_adicional.idcredito_adicional=" . $idcreditos_adicionales . "
										and maestro_presupuesto.idRegistro=partidas_credito_adicional.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
										order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC"
        , $conexion_db);

    if (mysql_num_rows($sql_partidas_credito_adicional) > 0) {
        $existen_partidas = true;
    }

}

// ***************************************************************************************************************************
//SELECCIONO MODIFICAR O ELIMINAR PARTIDA DEL CREDITO - FILTRA LOS DATOS DE LA PARTIDA
if ($modopartidas == 1 or $modopartidas == 2) {

    $sql_credito_adicional = mysql_query("select * from creditos_adicionales
												where status='a'
												and idcreditos_adicionales=" . $idcreditos_adicionales
        , $conexion_db);
    $regcredito_adicional = mysql_fetch_assoc($sql_credito_adicional);
    $idcredito_adicional  = $regcredito_adicional["idcreditos_adicionales"];

    $sql_partidas_credito_adicional = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
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
														maestro_presupuesto.anio as anio,
														partidas_credito_adicional.idcredito_adicional,
														partidas_credito_adicional.idmaestro_presupuesto,
														partidas_credito_adicional.idpartida_credito_adicional,
														partidas_credito_adicional.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_credito_adicional,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
															where
										partidas_credito_adicional.status='a'
										and partidas_credito_adicional.idcredito_adicional=" . $idcredito_adicional . "
										and maestro_presupuesto.idRegistro=partidas_credito_adicional.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento
										order by categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta ASC"
        , $conexion_db);

    if (mysql_num_rows($sql_partidas_credito_adicional) > 0) {
        $existen_partidas = true;
    }

    $sql_partida_credito_adicional = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
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
														partidas_credito_adicional.idcredito_adicional,
														partidas_credito_adicional.idmaestro_presupuesto,
														partidas_credito_adicional.idpartida_credito_adicional,
														partidas_credito_adicional.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_credito_adicional,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
															where
										partidas_credito_adicional.status='a'
										and partidas_credito_adicional.idpartida_credito_adicional=" . $id_partida_credito_adicional . "
										and maestro_presupuesto.idRegistro=partidas_credito_adicional.idmaestro_presupuesto
										and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
										and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
										and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
										and ordinal.idordinal=maestro_presupuesto.idordinal
										and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento"
        , $conexion_db);

    $regpartida_credito_adicional = mysql_fetch_assoc($sql_partida_credito_adicional);

    $sql_validar_categoria = mysql_query("select
											unidad_ejecutora.denominacion as denocategoriaprogramatica,
											unidad_ejecutora.codigo as codigounidadejecutora,
											categoria_programatica.codigo,
											categoria_programatica.idcategoria_programatica,
											categoria_programatica.anio
											from unidad_ejecutora,categoria_programatica
												where
													unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora
												and categoria_programatica.idcategoria_programatica=" . $regpartida_credito_adicional["idcategoria_programatica"] . "
												and categoria_programatica.status='a'"
        , $conexion_db);

    if (mysql_num_rows($sql_validar_categoria) > 0) {
        $regcategoria_programatica = mysql_fetch_assoc($sql_validar_categoria);
    }

    $sql_validar_partida = mysql_query("select * from clasificador_presupuestario
														where idclasificador_presupuestario=" . $regpartida_credito_adicional["idclasificador_presupuestario"] . "
															and status='a'"
        , $conexion_db);
    if (mysql_num_rows($sql_validar_partida) > 0) {
        $regclasificador_presupuestario = mysql_fetch_assoc($sql_validar_partida);
    }

}

// **********************************************************************************************
// CARGA LOS DATOS SELECCIONADOS DE LA VENTANA EMERGENTE DE LISTADO DE CREDITOS ADICIONALES

if ($_POST["idcreditoadicional_emergente"] != "") {
    // SI ESTA VARIABLE OCULTA TOMA VALOR ENVIADO DE UNA VENTANA EMERGENTE
    $sql_credito_adicional = mysql_query("select * from creditos_adicionales
												where status='a'
												and idcreditos_adicionales=" . $_POST["idcreditoadicional_emergente"]
        , $conexion_db);
    $regcredito_adicional = mysql_fetch_assoc($sql_credito_adicional);
    $idcredito_adicional  = $regcredito_adicional["idcreditos_adicionales"];

    $sql_partidas_credito_adicional = mysql_query("select clasificador_presupuestario.codigo_cuenta as codigopartida,
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
														ordinal.idordinal as idordinal,
														partidas_credito_adicional.idcredito_adicional,
														partidas_credito_adicional.idmaestro_presupuesto,
														partidas_credito_adicional.idpartida_credito_adicional,
														partidas_credito_adicional.monto_acreditar,
														fuente_financiamiento.denominacion as denominacion_fuente
															from
														partidas_credito_adicional,
														clasificador_presupuestario,
														categoria_programatica,
														maestro_presupuesto,
														unidad_ejecutora,
														ordinal,
														fuente_financiamiento
													where
									partidas_credito_adicional.status='a'
									and partidas_credito_adicional.idcredito_adicional=" . $idcredito_adicional . "
									and maestro_presupuesto.idRegistro=partidas_credito_adicional.idmaestro_presupuesto
									and clasificador_presupuestario.idclasificador_presupuestario=maestro_presupuesto.idclasificador_presupuestario
									and categoria_programatica.idcategoria_programatica=maestro_presupuesto.idcategoria_programatica
									and unidad_ejecutora.idunidad_ejecutora=categoria_programatica.idunidad_ejecutora
									and ordinal.idordinal=maestro_presupuesto.idordinal
									and fuente_financiamiento.idfuente_financiamiento = maestro_presupuesto.idfuente_financiamiento"
        , $conexion_db);

    if (mysql_num_rows($sql_partidas_credito_adicional) > 0) {
        $existen_partidas = true;
    }

}
// **********************************************************************************************************************************

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
						maestro_presupuesto.idRegistro as idmaestro_presupuesto,
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

if ($emergente and $_GET["accion"] != 224) {
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
		if (document.frmcreditos_adicionales.nro_solicitud.value.length==0){
			mostrarMensajes("error", "Debe escribir un Numero de Solicitud para el Credito Adicional");
			document.frmcreditos_adicionales.nro_solicitud.focus()
			return false;
		}
		if (document.frmcreditos_adicionales.justificacion.value.length==0){
			mostrarMensajes("error", "Debe escribir una Justificaci&oacute;n para el Credito Adicional");
			document.frmcreditos_adicionales.justificacion.focus()
			return false;
		}

	}

function abreVentanaPresupuesto(){
	m=document.frmcreditos_adicionales.modoactual.value;
	g=document.frmcreditos_adicionales.guardo.value;
	nro=document.frmcreditos_adicionales.nro.value;
	j=document.frmcreditos_adicionales.juntos.value;
	miPopup=window.open("lib/listas/lista_presupuestos.php?m="+m+"&g="+g+"&i="+nro+"&j="+j,"presupuestos","width=1200,height=600,scrollbars=yes")
	miPopup.focus()
}

function abreVentanaCA(){
	m=document.frmcreditos_adicionales.modoactual.value;
	j=document.frmcreditos_adicionales.juntos.value;
	g=document.frmcreditos_adicionales.guardo.value;
	miPopup=window.open("lib/listas/lista_creditos_adicionales.php?m="+m+"&g="+guardo+"&j="+j,"creditos_adicionales","width=800,height=600,scrollbars=yes")
	miPopup.focus()
}

function formatoNumero(idcampo) {
var frm = document.frmcreditos_adicionales;
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
	<h4 align=center>Cr&eacute;ditos Adicionales</h4>
	<h2 class="sqlmVersion"></h2>

	<?php
if ($regcredito_adicional["nro_solicitud"] != "") {
    $btimprimir = "visibility:visible;";
} else {
    $btimprimir = "visibility:hidden;";
}

?>
	<table align=center cellpadding=2 cellspacing=0 width="10%">
			<tr>
				<td align='center' ><img src="imagenes/search0.png" border="0" style="cursor:pointer" onClick="javascript:abreVentanaCA()" title="Buscar Creditos Adicionales">
                &nbsp;<a href="principal.php?modulo=2&accion=48"><img src="imagenes/nuevo.png" border="0" title="Nuevo Credito Adicional"></a>
                &nbsp;<img src="imagenes/imprimir.png" style="cursor:pointer; <?=$btimprimir?>" title="Imprimir Credito Adicional"  onClick="document.getElementById('divTipo').style.display='block'; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='block';" /></td>
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
                                <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/presupuesto/reportes.php?nombre=credito_adicional&id_credito='+document.getElementById('idcreditos_adicionales').value+'&solicitud='+document.getElementById('solicitud').checked+'&simulado='+document.getElementById('simulado').checked; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block'; document.getElementById('divTipo').style.display='none'; document.getElementById('tableImprimir').style.display='none';">
                            </td>
                        </tr>
                      </table>
                      </div>
                </td>
            </tr>
	</table>
	<br>
	<?PHP
//echo $regmaestro_presupuesto['idmaestro_presupuesto'];
?>
	<form name="frmcreditos_adicionales" action="principal.php?modulo=2&accion=<?=$_GET["accion"]?>" method="POST" enctype="multipart/form-data" onSubmit="return valida_envia()">
	<input type="hidden" name="modoactual" id="modoactual" <?php echo 'value="' . $modo . '"'; ?>>
	<input type="hidden" name="guardo" id="guardo" <?php echo 'value="' . $guardo . '"'; ?>>
	<input type="hidden" name="nro" id="nro" <?php echo 'value="' . $nro_solicitud_credito . '"'; ?>>
	<input type="hidden" name="juntos" id="juntos" <?php echo 'value="' . $juntos . '"'; ?>>
	<input type="hidden" name="fecha_ingreso" id="fecha_ingreso" <?php echo 'value="' . date("d/m/Y", strtotime("-0 day")) . '"'; ?>>
	<input type="hidden" name="idcreditos_adicionales" id="idcreditos_adicionales" <?php if (isset($_POST["idcreditos_adicionales"])) {echo 'value="' . $_POST["idcreditos_adicionales"] . '"';} else {echo 'value="' . $regcredito_adicional["idcreditos_adicionales"] . '"';}?>>
	<input type="hidden" name="idcreditoadicional_emergente" id="idcreditoadicional_emergente" <?php echo 'value="' . $regcredito_adicional['idcreditos_adicionales'] . '"'; ?>>
	<input type="hidden" name="emergente" maxlength="5" size="5" id="emergente" <?php echo 'value="' . $_POST['emergente'] . '"'; ?>>
	<input type="hidden" name="id_partida_credito_adicional" id="id_partida_credito_adicional" <?php echo 'value="' . $id_partida_credito_adicional . '"'; ?>>

		<table align=center cellpadding=2 cellspacing=0 width="80%">
			<tr>
			  <td align='right' >&nbsp;</td>
			  <td colspan="2" class=''><b><?echo $regcredito_adicional["estado"]; ?></b></td>
              <?//echo "iD ".$regmaestro_presupuesto['idmaestro_presupuesto']; ?>
			  <td>&nbsp;</td>
			  <td align='right' >&nbsp;</td>
			  <td class=''>&nbsp;</td>
			  <td align='right'>&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>

			<tr>
				<td align='right' class='viewPropTitle' width="10%">Nro. Solicitud:</td>
					<td class='' width="10%"><input type="text" id="nro_solicitud" name="nro_solicitud" maxlength="12" size="12" value="<?php echo $regcredito_adicional["nro_solicitud"]; ?>">
				</td>
				<td align='right' class='viewPropTitle' width="12%">Fecha Solicitud:</td>
				<td width="15%">
				<input name="fecha_solicitud" type="text" id="fecha_solicitud" value="<?php
if ($guardo) {echo substr($regcredito_adicional['fecha_solicitud'], 8, 2) . '/' . substr($regcredito_adicional['fecha_solicitud'], 5, 2) . '/' . substr($regcredito_adicional['fecha_solicitud'], 0, 4) . '"';} else {echo date("d/m/Y", strtotime("-0 day"));}?>" size="13" maxlength="10">
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
					<td class='' width="10%"><input type="text" id="nro_resolucion" name="nro_resolucion" maxlength="12" size="12" value="<?php echo $regcredito_adicional["nro_resolucion"]; ?>">
				</td>
				<td align='right' class='viewPropTitle' width="13%">Fecha Resoluci&oacute;n:</td>
				<td width="15%">
				<input name="fecha_resolucion" type="text" id="fecha_resolucion" value="<?php if ($guardo) {echo substr($regcredito_adicional['fecha_resolucion'], 8, 2) . '/' . substr($regcredito_adicional['fecha_resolucion'], 5, 2) . '/' . substr($regcredito_adicional['fecha_resolucion'], 0, 4) . '"';} else {echo date("d/m/Y", strtotime("-0 day"));}?>" size="13" maxlength="10">
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
					<td class='' colspan="7"><textarea id="justificacion" name="justificacion" cols="137" rows="3" ><?php echo $regcredito_adicional["justificacion"]; ?></textarea>
				</td>
			</tr>
			<tr>
				<td align='right' class='viewPropTitle' width="20%">Origen de Financiamiento Cr&eacute;dito Adicional:</td>
				<td class='viewProp' colspan="2">
				<select name="fuente_financiamiento" style="width:100%">
					<?php
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
									<option <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';
    if ($rowfuente_financiamiento["idfuente_financiamiento"] == $regcredito_adicional["idfuente_financiamiento"]) {echo ' selected';}
    ?>>
										<?php echo $rowfuente_financiamiento["denominacion"]; ?>
									</option>
					<?php
}
?>
				</select>
				</td>
				<td align='right' class='viewPropTitle'>A&ntilde;o:</td>
				<td class='viewProp'>
				<select name="anio" id="anio" style="width:80%">
                        <?
anio_fiscal();
?>
				</select>
				</td>
				<td align='right' class='viewPropTitle' width="10%" colspan="2">Total Cr&eacute;dito:</td>
					<td class='' width="15%"><input type="label" style="text-align:right" id="total_credito" name="total_credito" maxlength="18" size="18"
										<?php echo ' value="' . number_format($regcredito_adicional["total_credito"], 2, ",", ".") . '"'; ?>>
                    </td>
                    <td>
			    <a href="#" onClick="recalcularTotalCredito()" id="recalcularTotalCredito"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular sumatoria"></a>				</td>
			</tr>
		</table>

		<table align=center cellpadding=2 cellspacing=0>
			<tr><td>
			  <?php

if (substr($regcredito_adicional["nro_solicitud"], 0, 1) == "*" and $regcredito_adicional["estado"] == "procesado") {
    echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
} else {
    if ($modopartidas == 4) {
        echo "<input align=center class='button' name='actualizar' type='submit' value='Actualizar Duplicado'>";
    }

    if ($modopartidas != 4 and ($juntos != 1 and $_GET["accion"] != 225 and $_GET["accion"] != 226 and in_array(224, $privilegios) == true)) {
        echo "<input align=center class='button' name='ingresar' type='submit' value='Ingresar'>";
    }

    if ($modopartidas != 4 and ($juntos == 1 or $_GET["accion"] == 225 or $_GET["accion"] == 226 and in_array($_GET["accion"], $privilegios) == true) and $regcredito_adicional["estado"] == "elaboracion") {
        echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
    }

    if ($modopartidas != 4 and ($juntos == 1 or $_GET["accion"] == 225 or $_GET["accion"] == 226 and in_array($_GET["accion"], $privilegios) == true) and $regcredito_adicional["estado"] == "elaboracion") {
        echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
    }

    if ($modopartidas != 4 and $regcredito_adicional["estado"] == "elaboracion") {
        echo "<input align=center class='button' name='procesar' type='submit' value='Procesar'>";
    }

    if ($modopartidas != 4 and $regcredito_adicional["estado"] == "procesado") {
        echo "<input align=center class='button' name='modificar' type='submit' value='Modificar'>";
        echo "<input align=center class='button' name='anular' type='submit' value='Anular'>";
        echo "<input align=center class='button' name='duplicar' type='submit' value='Duplicar'>";
    }

    if ($modopartidas != 4 and $regcredito_adicional["estado"] == "Anulado") {
        echo "<input align=center class='button' name='eliminar' type='submit' value='Eliminar'>";
        echo "<input align=center class='button' name='duplicar' type='submit' value='Duplicar'>";
    }
}

?>
				<input type="reset" value="Reiniciar" class="button">
			</td></tr>
		</table>

	<?php if ($guardo and $regcredito_adicional["estado"] == "elaboracion" or (in_array(752, $privilegios) == true and $regcredito_adicional["estado"] == "procesado")) {
    ?>
		<br>
		<br>
		<h2 align=center>Partidas a Recibir Cr&eacute;dito</h2>
		<h2 class="sqlmVersion"></h2>
		<br>

		<table align=center cellpadding="1" cellspacing="0" width="80%">
	  <tr>
      		<?if ($modopartidas == 0 or $cuerpo == 1 and $regcredito_adicional["estado"] == "elaboracion") {?>
				<td align='center' class='' width="5%" rowspan="2"><button name="listado_presupuesto" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentanaPresupuesto()"><img src='imagenes/search0.png'></button></td>
            <?}?>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Categoria Program&aacute;tica</td>
				<td align='center' class='viewPropTitle' colspan="2" width="40%">Partida</td>
				<td align='center' class='viewPropTitle' width="10%">Monto Acreditar</td>

                <?php
if ($modopartidas == 0 or $cuerpo == 1 and $regcredito_adicional["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="agregar_partida" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/save.png'></button></td>
				<?php }
    if ($modopartidas == 1 and $regcredito_adicional["estado"] == "elaboracion") {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="modificar_partida" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/modificar.png'></button></td>
				<?php }
    if ((in_array(752, $privilegios) == true and $modopartidas == 2 and $regcredito_adicional["estado"] == "procesado") or ($modopartidas == 2 and $regcredito_adicional["estado"] == "elaboracion")) {?>
						<td align='center' class='' width="5%" rowspan="2"><button name="eliminar_partida" type="submit" style="background-color:white;border-style:none;cursor:pointer;" ><img src='imagenes/delete.png'></button></td>
				<?php }?>
	    </tr>
			<tr>
				<td class='' width="10%"><input type="label" name="codcategoria_programatica" id="codcategoria_programatica" maxlength="14" size="14" <?php if (!isset($_POST["limpiar"])) {
        echo ' value="' . $regcategoria_programatica["codigo"] . '"';
    }
    ?>></td>
			  <td class='' width="40%"><input type="label" name="denocategoria_programatica" id="denocategoria_programatica" maxlength="50" size="40" <?php if (!isset($_POST["limpiar"])) {
        echo ' value="' . $regcategoria_programatica["denocategoriaprogramatica"] . '"';
    }
    ?>></td>
				<td class='' width="10%"><input type="label" name="codigo_cuenta" id="codigo_cuenta" maxlength="12" size="12" <?php if (!isset($_POST["limpiar"])) {
        echo ' value="' . $regclasificador_presupuestario["codigo_cuenta"] . '"';
    }
    ?>></td>
				<td class='' width="40%"><input type="label" name="denopartida" id="denopartida" maxlength="50" size="40" <?php if (!isset($_POST["limpiar"])) {
        echo ' value="' . utf8_decode($regclasificador_presupuestario["denominacion"]) . '"';
    }
    ?>></td>
				<td class='' width="10%"><input type="text" style="text-align:right" name="monto_acreditar" maxlength="20" size="20" id="monto_acreditar"
									<?php if (!isset($_POST["limpiar"])) {
        if ($_GET["accion"] == 224 and $_POST["monto_acreditar"] != "") {
            echo ' value="' . number_format($_POST["idmonto_acreditar"], 2, ",", ".") . '"';
        }
        if ($_GET["accion"] == 225 || $_GET["accion"] == 226) {
            if ($_POST["monto_acreditar"] == "") {echo ' value="' . number_format($regpartida_credito_adicional['monto_acreditar'], 2, ",", ".") . '"';} else {echo ' value="' . number_format($_POST['monto_acreditar'], 2, ",", ".") . '"';}
        }

        if ($_GET["accion"] == 226) {
            echo " disabled";
        }

    }
    ?> "></td>
			</tr>
			<tr>
			<td></td>
			<td><button name="limpiar" type="submit" style="background-color:white;border-style:none;cursor:pointer;"><font size="1">Limpiar</button></td>
			</tr>
		</table>

    <input type="hidden" name="idtotal_credito" maxlength="18" size="18" id="idtotal_credito" <?php
if ($_GET["accion"] == 225) {
        if ($_POST["total_credito"] == "") {echo ' value="' . $regcredito_adicional['total_credito'] . '"';} else {echo ' value="' . $_POST['idtotal_credito'] . '"';}
    }?>>
		<input type="hidden" name="idmaestropresupuesto" id="idmaestropresupuesto" <?php echo 'value="' . $regmaestro_presupuesto['idmaestro_presupuesto'] . '"'; ?>>

	<?php }?>
	</form>
	<?php if ($existen_partidas) {
    ?>
	<br>
	<div align="center">
			<table class="Main" cellpadding="0" cellspacing="0" width="85%">
				<tr>
					<td>
						<form name="grilla" action="" method="POST">
						<table class="Browse" align=center cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<td align="center" class="Browse">A&ntilde;o</td>
									<td align="center" class="Browse">Fuente Financiamiento</td>
                                    <td align="center" class="Browse" colspan="2">Categor&iacute;a Program&aacute;tica</td>
									<td align="center" class="Browse" colspan="2">Partida Presupuestaria</td>
									<td align="center" class="Browse">Monto Acreditar</td>
                                    <?//if ($regcredito_adicional["estado"]=="elaboracion") { ?>
										<td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                                     <?//} ?>
								</tr>
							</thead>
							<?php //  llena la grilla con los registros de la tabla de grupos

    while ($llenar_grilla = mysql_fetch_array($sql_partidas_credito_adicional)) {
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

        if ($regcredito_adicional["estado"] == "elaboracion" or $regcredito_adicional["estado"] == "Anulado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_acreditar"], 2, ",", ".") . "</td>";
        } else if (in_array(752, $privilegios) == false and $regcredito_adicional["estado"] == "procesado") {
            echo "<td align='right' class='Browse' width='8%'>" . number_format($llenar_grilla["monto_acreditar"], 2, ",", ".") . "</td>";
        } else if (in_array(752, $privilegios) == true and $regcredito_adicional["estado"] == "procesado") { ?>
                                    	<td align='right' class='Browse' width='8%'>
                                        <input align="right" style="text-align:right"
                                        					name="monto_acreditar<?=$llenar_grilla["idpartida_credito_adicional"]?>"
            												type="hidden"
                                                            id="monto_acreditar<?=$llenar_grilla["idpartida_credito_adicional"]?>"
                                                            size="20"
                                                            value="<?=$llenar_grilla["monto_acreditar"]?>">
										<input align="right" style="text-align:right"
                                        					name="monto_acreditar_mostrado<?=$llenar_grilla["idpartida_credito_adicional"]?>"
            												type="text"
                                                            id="monto_acreditar_mostrado<?=$llenar_grilla["idpartida_credito_adicional"]?>"
                                                            size="20"
                                                            onclick="this.select()"
                                                            onblur="formatoNumeroPpt(this.id, 'monto_acreditar<?=$llenar_grilla["idpartida_credito_adicional"]?>')"
                                                            value="<?=number_format($llenar_grilla["monto_acreditar"], 2, ',', '.')?>">
                                       </td>
									<?}

        //echo "<td align='right' class='Browse' width='8%'>".number_format($llenar_grilla["monto_acreditar"],2,",",".")."</td>";

        $c      = $llenar_grilla["idpartida_credito_adicional"];
        $i      = $llenar_grilla["idcredito_adicional"];
        $guardo = true;

        if ($regcredito_adicional["estado"] == "Anulado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        } else if (in_array(752, $privilegios) == false and $regcredito_adicional["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'>&nbsp; ";
        }
        if (in_array(752, $privilegios) == true and $regcredito_adicional["estado"] == "procesado") {
            echo "<td align='center' class='Browse' width='3%'> ";
            ?> <a href="#" onClick="recalcularPartidaCredito('<?=$llenar_grilla["idmaestro_presupuesto"]?>','<?=$llenar_grilla["idcredito_adicional"]?>', document.getElementById('monto_acreditar<?=$llenar_grilla["idpartida_credito_adicional"]?>').value)" id="recalcularPartidaCredito"><img src="imagenes/refrescar.png" alt="" border="0" style="text-decoration:none" title="Recalcular partida"></a></td> <?

        }

        if (in_array(225, $privilegios) == true and $regcredito_adicional["estado"] == "elaboracion") {
            echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=225&modopartidas=1&c=$c&juntos=1&idcreditos_adicionales=$i&guardo=$guardo&modo=1' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
        }
        if ((in_array(752, $privilegios) == true and $regcredito_adicional["estado"] == "procesado") or in_array(226, $privilegios) == true and $regcredito_adicional["estado"] == "elaboracion") {
            echo "<td align='center' class='Browse' width='3%'><a href='principal.php?modulo=2&accion=226&modopartidas=2&c=$c&juntos=1&idcreditos_adicionales=$i&guardo=$guardo&modo=2' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
        }
        echo "</tr>";
    }
    ?>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
	<?php }?>
	<br>
	<?php if ($emergente) {
    echo "<script> document.frmcreditos_adicionales.monto_acreditar.focus(), document.frmcreditos_adicionales.monto_acreditar.select()  </script>";
} else {
    echo "<script> document.frmcreditos_adicionales.nro_solicitud.focus() </script>";
}

if ($_GET["accion"] == 225 || $_GET["accion"] == 226) {
    echo "<script> document.frmcreditos_adicionales.monto_acreditar.focus(), document.frmcreditos_adicionales.monto_acreditar.select()   </script>";
}

?>

</body>
</html>

<?php
if ($_POST) {

    $nro_solicitud           = $_POST["nro_solicitud"];
    $fecha_solicitud         = substr($_POST["fecha_solicitud"], 6, 4) . "/" . substr($_POST["fecha_solicitud"], 3, 2) . "/" . substr($_POST["fecha_solicitud"], 0, 2);
    $nro_resolucion          = $_POST["nro_resolucion"];
    $fecha_resolucion        = substr($_POST["fecha_resolucion"], 6, 4) . "/" . substr($_POST["fecha_resolucion"], 3, 2) . "/" . substr($_POST["fecha_resolucion"], 0, 2);
    $fecha_ingreso           = substr($_POST["fecha_ingreso"], 6, 4) . "/" . substr($_POST["fecha_ingreso"], 3, 2) . "/" . substr($_POST["fecha_ingreso"], 0, 2);
    $justificacion           = $_POST["justificacion"];
    $idfuente_financiamiento = $_POST["fuente_financiamiento"];
    $anio                    = $_POST["anio"];
    $idtotal_credito         = $_POST["idtotal_credito"];
    $idcreditos_adicionales  = $_POST["idcreditos_adicionales"];
    $fh                      = date("Y-m-d H:i:s");
    $pc                      = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $busca_credito           = mysql_query("select * from creditos_adicionales where idcreditos_adicionales = '$idcredito_adicional'", $conexion_db);
    $credito                 = mysql_fetch_array($busca_credito);

    $sql        = "SELECT anio_fiscal FROM configuracion";
    $query_conf = mysql_query($sql) or die($sql . mysql_error());
    $conf       = mysql_fetch_array($query_conf);
    $anio       = $conf['anio_fiscal'];

    if (isset($_POST["agregar_partida"])) {

        $idmaestro_presupuesto = $_POST["idmaestropresupuesto"];
        //$idpartida_credito=$_POST["idpartida_credito"]; // variable oculta para saber cual partida hay que modificar o eliminar
        $monto_acreditar      = $_POST["monto_acreditar"];
        $busca_existe_partida = mysql_query("select * from partidas_credito_adicional where idcredito_adicional = '" . $idcredito_adicional . "'
																				and idmaestro_presupuesto = '" . $idmaestro_presupuesto . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_partida) > 0) {
            $varget1 = "modo=1";
            $varget2 = "busca=0";
            $varget3 = "guardo=true";
            $varget4 = "idcreditos_adicionales=$idcredito_adicional";
            $varget5 = "juntos=1";
            ?>
			<script>
			mostrarMensajes("error", "Disculpe, la partida ya fue ingresada al credito adicional");
			setTimeout("window.location.href='principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=<?=$idcredito_adicional?>&juntos=1&modopartidas=0'",5000);
			</script>
		<?
        } else {
            mysql_query("insert into partidas_credito_adicional
									(idcredito_adicional,idmaestro_presupuesto,monto_acreditar,usuario,fechayhora,status)
							values ('$idcredito_adicional','$idmaestro_presupuesto','$monto_acreditar','$login','$fh','a')"
                , $conexion_db);
            if ($credito["estado"] == "elaboracion") {
                mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) + '" . $monto_acreditar . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());
            } else if ($credito["estado"] == "procesado") {
                mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) + '" . $monto_acreditar . "',
									monto_actual = (monto_actual) + '" . $monto_acreditar . "'
									where 	idRegistro = '" . $idmaestro_presupuesto . "'"
                    , $conexion_db) or die(mysql_error());
            }
            mysql_query("update creditos_adicionales set
									total_credito=(total_credito)+'" . $monto_acreditar . "'
									where 	idcreditos_adicionales = '$idcredito_adicional'"
                , $conexion_db);

            registra_transaccion('Ingresar Partida Creditos Adicionales', $login, $fh, $pc, 'partidas_credito_adicional', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$idcredito_adicional&juntos=1&modopartidas=0");

        }
    }

    if (isset($_POST["modificar_partida"])) {
        // MODIFICO UNA PARTIDA AL CREDITO ADICIONAL

        $monto_acreditar   = $_POST["monto_acreditar"];
        $idpartida_credito = $_POST["id_partida_credito_adicional"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_credito_adicional where idpartida_credito_adicional = '" . $idpartida_credito . "'
																				and status='a'", $conexion_db);
        $regpartida_credito_adicional = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior               = $regpartida_credito_adicional["monto_acreditar"];
        $idmaestro_presupuesto        = $regpartida_credito_adicional["idmaestro_presupuesto"];
        $idcredito_adicional          = $regpartida_credito_adicional["idcredito_adicional"];

        mysql_query("update partidas_credito_adicional set
									monto_acreditar='" . $monto_acreditar . "'
									where 	idpartida_credito_adicional = '$idpartida_credito'
											and status = 'a'", $conexion_db);

        if ($credito["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento - '" . $monto_anterior . "' + '" . $monto_acreditar . "',
									where 	idRegistro = '$idmaestro_presupuesto'"
                , $conexion_db);
        } else if ($credito["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento - '" . $monto_anterior . "' + '" . $monto_acreditar . "',
									monto_actual = (monto_actual) + '" . $monto_acreditar . "' - '" . $monto_anterior . "'
									where 	idRegistro = '$idmaestro_presupuesto'"
                , $conexion_db);
        }

        mysql_query("update creditos_adicionales set
									total_credito=(total_credito)-'" . $monto_anterior . "'+'" . $monto_acreditar . "'
									where 	idcreditos_adicionales = '$idcredito_adicional'"
            , $conexion_db);

        registra_transaccion('Modificar Partida Creditos Adicionales', $login, $fh, $pc, 'partidas_credito_adicional', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$idcredito_adicional&juntos=1&modopartidas=0");

    }

    if (isset($_POST["eliminar_partida"])) {
        // ELIMINO UNA PARTIDA AL CREDITO ADICIONAL

        $idpartida_credito = $_POST["id_partida_credito_adicional"]; // variable oculta para saber cual partida hay que modificar o eliminar

        $busca_existe_partida = mysql_query("select * from partidas_credito_adicional where idpartida_credito_adicional = '" . $idpartida_credito . "'
																				and status='a'", $conexion_db);
        $regpartida_credito_adicional = mysql_fetch_assoc($busca_existe_partida);
        $monto_anterior               = $regpartida_credito_adicional["monto_acreditar"];
        $idcredito_adicional          = $regpartida_credito_adicional["idcredito_adicional"];
        $idmaestro_presupuesto        = $regpartida_credito_adicional["idmaestro_presupuesto"];

        mysql_query("delete from partidas_credito_adicional
									where 	idpartida_credito_adicional = '$idpartida_credito'
											and status = 'a'", $conexion_db);

        if ($credito["estado"] == "elaboracion") {
            mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) - '" . $monto_anterior . "',
									where 	idRegistro = '$idmaestro_presupuesto'"
                , $conexion_db);
        } else if ($credito["estado"] == "procesado") {
            mysql_query("update maestro_presupuesto set
									total_aumento = (total_aumento) - '" . $monto_anterior . "',
									monto_actual = (monto_actual) - '" . $monto_anterior . "'
									where 	idRegistro = '$idmaestro_presupuesto'"
                , $conexion_db);
        }

        mysql_query("update creditos_adicionales set
									total_credito=(total_credito)-'" . $monto_anterior . "'
									where 	idcreditos_adicionales = '$idcredito_adicional'"
            , $conexion_db);

        registra_transaccion('Eliminar Partida Credito Adicional', $login, $fh, $pc, 'partidas_credito_adicional', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$idcredito_adicional&juntos=1&modopartidas=0");

    }

//*************************************************************************************************************************************************************************
    //    PROCESAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["procesar"])) {

        $busca_credito = mysql_query("select * from partidas_credito_adicional where idcredito_adicional = '" . $idcredito_adicional . "' and status='a'");
        if (mysql_num_rows($busca_credito) > 0) {
            while ($procesar_credito = mysql_fetch_array($busca_credito)) {
                $monto_acreditar       = $procesar_credito["monto_acreditar"];
                $idmaestro_presupuesto = $procesar_credito["idmaestro_presupuesto"];

                mysql_query("update maestro_presupuesto set
									total_aumento = total_aumento + '" . $monto_acreditar . "',
									solicitud_aumento = solicitud_aumento - '" . $monto_acreditar . "',
									monto_actual = monto_actual+'" . $monto_acreditar . "'
									where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }

            mysql_query("update creditos_adicionales set
										estado='procesado',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idcreditos_adicionales = '$idcredito_adicional'
											and status = 'a'", $conexion_db);

            registra_transaccion('Procesar Creditos Adicionales', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
            ?>
		<script>
			mostrarMensajes("exito", "El documento de Creditos Adicionales se proceso con exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=48'",5000);
			</script>
		<?

        } else {

            registra_transaccion('ERROR - Procesar Creditos Adicionales - No tiene partidas cargadas', $login, $fh, $pc, 'partidas_credito_adicional', $conexion_db);

            ?>
		<script>
			mostrarMensajes("error", "ERROR -  No existen partidas en el documento, por lo que no se puede procesar");
			setTimeout("window.location.href='principal.php?modulo=2&accion=48&guardo=true&idcredito_adicional=<?=$idcredito_adicional?>&juntos=1&modopartidas=0'",5000);
			</script>
		<?

        }
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //    ANULAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["anular"])) {

        $busca_credito = mysql_query("select * from partidas_credito_adicional where idcredito_adicional = '" . $idcredito_adicional . "' and status='a'");
        if (mysql_num_rows($busca_credito) > 0) {

            while ($procesar_credito = mysql_fetch_array($busca_credito)) {
                $monto_acreditar       = $procesar_credito["monto_acreditar"];
                $idmaestro_presupuesto = $procesar_credito["idmaestro_presupuesto"];

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
			setTimeout("window.location.href='principal.php?modulo=2&accion=48&guardo=true&idcredito_adicional=<?=$idcredito_adicional?>&juntos=1&modopartidas=0'",8000);
			</script>
		<?
                }

            }

            while ($procesar_credito = mysql_fetch_array($busca_credito)) {
                $monto_acreditar       = $procesar_credito["monto_acreditar"];
                $idmaestro_presupuesto = $procesar_credito["idmaestro_presupuesto"];

                mysql_query("update maestro_presupuesto set
									total_aumento = total_aumento - '" . $monto_acreditar . "',
									monto_actual = monto_actual-'" . $monto_acreditar . "'
									where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }

            mysql_query("update creditos_adicionales set
										estado='Anulado',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idcreditos_adicionales = '$idcredito_adicional'
											and status = 'a'", $conexion_db);

            registra_transaccion('ANULAR Creditos Adicionales', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
            ?>
		<script>
			mostrarMensajes("exito", "La ANULACION del documento de Creditos Adicionales se proceso con exito");
			setTimeout("window.location.href='principal.php?modulo=2&accion=48'",5000);
			</script>
		<?

        } else {
            registra_transaccion('ERROR - Procesar Creditos Adicionales - No tiene partidas cargadas', $login, $fh, $pc, 'partidas_credito_adicional', $conexion_db);

            ?>
		<script>
			mostrarMensajes("error", "ERROR -  No existen partidas en el documento, por lo que no se puede ANULAR");
			setTimeout("window.location.href='principal.php?modulo=2&accion=48&guardo=true&idcredito_adicional=<?=$idcredito_adicional?>&juntos=1&modopartidas=0'",5000);
			</script>
		<?

        }
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //    DUPLICAR
    //*************************************************************************************************************************************************************************

    if (isset($_POST["duplicar"])) {
        $duplicado               = true;
        $busca_credito           = mysql_query("select * from creditos_adicionales where idcreditos_adicionales = '$idcredito_adicional' and status = 'a'");
        $regcreditos_adicionales = mysql_fetch_assoc($busca_credito);
        $total_credito           = $regcreditos_adicionales["total_credito"];
        mysql_query("insert into creditos_adicionales
												(fecha_solicitud,
												fecha_resolucion,
												fecha_ingreso,
												justificacion,
												anio,
												idfuente_financiamiento,
												total_credito,
												estado,
												usuario,
												fechayhora,
												status)
											values ('$fecha_solicitud',
													'$fecha_resolucion',
													'$fecha_ingreso',
													'$justificacion',
													'$anio',
													'$idfuente_financiamiento',
													'$total_credito',
													'elaboracion',
													'$login',
													'$fh',
													'a')") or die(mysql_error());

        $nuevoid_credito = mysql_insert_id();

        $busca_partidas_credito = mysql_query("select * from partidas_credito_adicional where idcredito_adicional = '" . $idcredito_adicional . "' and status='a'");

        if (mysql_num_rows($busca_partidas_credito) > 0) {

            while ($procesar_credito = mysql_fetch_array($busca_partidas_credito)) {
                $monto_acreditar       = $procesar_credito["monto_acreditar"];
                $idmaestro_presupuesto = $procesar_credito["idmaestro_presupuesto"];

                mysql_query("insert into partidas_credito_adicional
									(idcredito_adicional,idmaestro_presupuesto,monto_acreditar,usuario,fechayhora,status)
							values ('$nuevoid_credito','$idmaestro_presupuesto','$monto_acreditar','$login','$fh','a')");

                mysql_query("update maestro_presupuesto set
									solicitud_aumento = (solicitud_aumento) + '" . $monto_acreditar . "',
									where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }
            echo $nuevoid_credito;

            registra_transaccion('DUPLICAR Credito Adicional', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$nuevoid_credito&juntos=0&modopartidas=4");
        }
    }
//********************************************************************************************************

//*************************************************************************************************************************************************************************
    //   REGISTRAR DUPLICADO
    //*************************************************************************************************************************************************************************

    if (isset($_POST["actualizar"])) {

        mysql_query("update creditos_adiconales set
										nro_solicitud='" . $nro_solicitud . "',
										fecha_solicitud='" . $fecha_solicitud . "',
										nro_resolucion='" . $nro_resolucion . "',
										fecha_resolucion='" . $fecha_resolucion . "',
										justificacion='" . $justificacion . "',
										idfuente_financiamiento='" . $idfuente_financiamiento . "',
										anio='" . $anio . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idcreditos_adicionales = '$idcredito_adicional'
											and status = 'a'", $conexion_db);
        registra_transaccion('Actualizar Encabezado Credito Adicional Duplicado', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$idcredito_adicional&juntos=1");
    }

//********************************************************************************************************

    if ($_GET["accion"] == 224 and in_array(224, $privilegios) == true) {
        $busca_existe_registro = mysql_query("select * from creditos_adicionales where nro_solicitud = '" . $_POST['nro_solicitud'] . "'
																				and status='a'", $conexion_db);
        if (mysql_num_rows($busca_existe_registro) > 0) {
            ?>
		<script>
			mostrarMensajes("error", "Disculpe el Numero de Solicitud que ingreso ya existe, por favor vuelva a intentarlo");
			</script>
		<?

        } else {
            mysql_query("insert into creditos_adicionales
									(nro_solicitud,fecha_solicitud,nro_resolucion,fecha_resolucion,fecha_ingreso,justificacion,anio,idfuente_financiamiento,total_credito,estado,usuario,fechayhora,status)
							values ('$nro_solicitud','$fecha_solicitud','$nro_resolucion','$fecha_resolucion','$fecha_ingreso','$justificacion','$anio','$idfuente_financiamiento','$idtotal_credito','elaboracion','$login','$fh','a')"
                , $conexion_db);
            $busca_existe_registro = mysql_query("select * from creditos_adicionales where nro_solicitud = '" . $nro_solicitud . "'
																				and status='a'", $conexion_db);
            $regcredito_adicional = mysql_fetch_assoc($busca_existe_registro);
            $idcredito_adicional  = $regcredito_adicional["idcreditos_adicionales"];

            registra_transaccion('Ingresar Credito Adicional', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
            redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$idcredito_adicional&juntos=1");
        }
    }

    if ($_POST["modificar"] and in_array(225, $privilegios) == true) {

        mysql_query("update creditos_adicionales set
										nro_solicitud='" . $nro_solicitud . "',
										fecha_solicitud='" . $fecha_solicitud . "',
										nro_resolucion='" . $nro_resolucion . "',
										fecha_resolucion='" . $fecha_resolucion . "',
										justificacion='" . $justificacion . "',
										idfuente_financiamiento='" . $idfuente_financiamiento . "',
										anio='" . $anio . "',
										total_credito='" . $idtotal_credito . "',
										fechayhora='" . $fh . "',
										usuario='" . $login . "'
										where 	idcreditos_adicionales = '$idcredito_adicional'
											and status = 'a'", $conexion_db);
        registra_transaccion('Modificar Encabezado Credito Adicional', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=48&guardo=true&idcreditos_adicionales=$idcredito_adicional&juntos=1");
    }

    if ($_POST["eliminar"] and in_array(226, $privilegios) == true) {
        if (substr($regcredito_adicional["nro_solicitud"], 0, 1) == "*" and $regcredito_adicional["estado"] == "procesado") {

            $sql_partidas_credito = mysql_query("select * from partidas_credito_adicional where idcredito_adicional = '$idcredito_adicional'
																									and status = 'a'") or die(mysql_error());

            while ($bus_partidas_credito = mysql_fetch_array($sql_partidas_credito)) {
                $monto_acreditar       = $bus_partidas_credito["monto_acreditar"];
                $idmaestro_presupuesto = $bus_partidas_credito["idmaestro_presupuesto"];

                mysql_query("update maestro_presupuesto set
											total_aumento = total_aumento -'" . $monto_acreditar . "',
											monto_actual = monto_actual-'" . $monto_acreditar . "'
											where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }
            mysql_query("delete from partidas_credito_adicional
										where 	idcredito_adicional = '$idcredito_adicional'
											and status = 'a'", $conexion_db);
        } else {

            $sql_partidas_credito = mysql_query("select * from partidas_credito_adicional where idcredito_adicional = '$idcredito_adicional'
																									and status = 'a'") or die(mysql_error());

            while ($bus_partidas_credito = mysql_fetch_array($sql_partidas_credito)) {
                $monto_acreditar       = $bus_partidas_credito["monto_acreditar"];
                $idmaestro_presupuesto = $bus_partidas_credito["idmaestro_presupuesto"];

                mysql_query("update maestro_presupuesto set
											solicitud_aumento = solicitud_aumento - '" . $monto_acreditar . "',
											where 	idRegistro = '$idmaestro_presupuesto'"
                    , $conexion_db);

            }

            mysql_query("delete from partidas_credito_adicional
										where 	idcredito_adicional = '$idcredito_adicional'
											and status = 'a'", $conexion_db);
        }
        mysql_query("delete from creditos_adicionales
										where 	idcreditos_adicionales = '$idcredito_adicional'
											and status = 'a'", $conexion_db);

        registra_transaccion('Eliminar Credito Adicional', $login, $fh, $pc, 'creditos_adicionales', $conexion_db);
        redirecciona("principal.php?modulo=2&accion=48");

    }
}
?>
