<?php
session_start();
include "../../../funciones/funciones.php";
//    ---------------------------------------------
function listar_foros($padre, $titulo, $idubicacion)
{
    global $foros;
    foreach ($foros[$padre] as $foro => $datos) {
        if (isset($foros[$foro])) {
            $nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
            listar_foros($foro, $nuevo_titulo);
        } else {
            ?><option value="<?=$datos['idniveles_organizacionales']?>"><?=$titulo . " - " . $datos['denominacion']?></option><?
        }
    }
}
//    ---------------------------------------------
$ls = array(
    "actividad" => "actividad",
    "familia"   => "familia",
    "grupo"     => "grupo",
);
$_SESSION['listadoSNC'] = serialize($ls);

$lm = array(
    "tipo"       => "tipo",
    "movimiento" => "movimiento",
);
$_SESSION['listadoMovimientos'] = serialize($lm);

$cta = array(
    "banco"  => "banco",
    "cuenta" => "cuenta",
);
$_SESSION['listadoCuentas'] = serialize($cta);

$lc = array(
    "grupo"     => "banco",
    "sub_grupo" => "sub_grupo",
    "seccion"   => "seccion",
);
$_SESSION['listadoCatalogo'] = serialize($lc);

$lo = array(
    "organizacion"       => "organizacion",
    "nivel_organizacion" => "nivel_organizacion",
);
$_SESSION['listadoOrganizacion'] = serialize($lo);

$lpn = array(
    "nomina"  => "nomina",
    "periodo" => "periodo",
);
$_SESSION['listadoPeriodoNomina'] = serialize($lpn);
//    ---------------------------------------------
$nom_mes["01"] = "Enero";
$nom_mes["02"] = "Febrero";
$nom_mes["03"] = "Marzo";
$nom_mes["04"] = "Abril";
$nom_mes["05"] = "Mayo";
$nom_mes["06"] = "Junio";
$nom_mes["07"] = "Julio";
$nom_mes["08"] = "Agosto";
$nom_mes["09"] = "Septiembre";
$nom_mes["10"] = "Octubre";
$nom_mes["11"] = "Noviembre";
$nom_mes["12"] = "Diciembre";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"> @import url("../../../css/theme/calendar-win2k-cold-1.css"); </style>
<style type="text/css">
<!--
.Select1 {width: 300px;}
-->
</style>
<script type="text/javascript" src="../../../js/calendar/calendar.js"></script>
<script type="text/javascript" src="../../../js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="../../../js/calendar/lang/calendar-es.js"></script>
<script type="text/javascript">
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,'') }
var listadoSNC=new Array();
listadoSNC[0]="actividad";
listadoSNC[1]="familia";
listadoSNC[2]="grupo";

var listadoMovimientos=new Array();
listadoMovimientos[0]="tipo";
listadoMovimientos[1]="movimiento";

var listadoCuentas=new Array();
listadoCuentas[0]="banco";
listadoCuentas[1]="cuenta";

var listadoCatalogo=new Array();
listadoCatalogo[0]="grupo";
listadoCatalogo[1]="sub_grupo";
listadoCatalogo[2]="seccion";

var listadoOrganizacion=new Array();
listadoOrganizacion[0]="organizacion";
listadoOrganizacion[1]="nivel_organizacion";

var listadoPeriodoNomina=new Array();
listadoPeriodoNomina[0]="nomina";
listadoPeriodoNomina[1]="periodo";

function nuevoAjax() {
	var xmlhttp=false;
	try{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp;
}

function buscarEnArray(array, dato) {
	// Retorna el indice de la posicion donde se encuentra el elemento en el array o null si no se encuentra
	var x=0;
	while(array[x])
	{
		if(array[x]==dato) return x;
		x++;
	}
	return null;
}

function cargaContenido(idSelectOrigen, listado) {
	// Obtengo la posicion que ocupa el select que debe ser cargado en el array declarado mas arriba
	if (listado=="listadoSNC") var posicionSelectDestino=buscarEnArray(listadoSNC, idSelectOrigen)+1;
	else if (listado=="listadoMovimientos") var posicionSelectDestino=buscarEnArray(listadoMovimientos, idSelectOrigen)+1;
	else if (listado=="listadoCuentas") var posicionSelectDestino=buscarEnArray(listadoCuentas, idSelectOrigen)+1;
	else if (listado=="listadoCatalogo") var posicionSelectDestino=buscarEnArray(listadoCatalogo, idSelectOrigen)+1;
	else if (listado=="listadoOrganizacion") var posicionSelectDestino=buscarEnArray(listadoOrganizacion, idSelectOrigen)+1;
	else if (listado=="listadoPeriodoNomina") var posicionSelectDestino=buscarEnArray(listadoPeriodoNomina, idSelectOrigen)+1;
	// Obtengo el select que el usuario modifico
	var selectOrigen=document.getElementById(idSelectOrigen);
	// Obtengo la opcion que el usuario selecciono
	var opcionSeleccionada=selectOrigen.options[selectOrigen.selectedIndex].value;
	// Si el usuario eligio la opcion "Elige", no voy al servidor y pongo los selects siguientes en estado "Selecciona opcion..."
	if(opcionSeleccionada==0)
	{
		var x=posicionSelectDestino, selectActual=null;

		if (listado=="listadoSNC") var num = listadoSNC.length;
		else if (listado=="listadoMovimientos") var num = listadoMovimientos.length;
		else if (listado=="listadoCuentas") var num = listadoCuentas.length;
		else if (listado=="listadoCatalogo") var num = listadoCatalogo.length;
		else if (listado=="listadoOrganizacion") var num = listadoOrganizacion.length;
		else if (listado=="listadoPeriodoNomina") var num = listadoPeriodoNomina.length;

		// Busco todos los selects siguientes al que inicio el evento onChange y les cambio el estado y deshabilito
		while(x <= num-1)
		{
			if (listado=="listadoSNC") selectActual=document.getElementById(listadoSNC[x]);
			else if (listado=="listadoMovimientos") selectActual=document.getElementById(listadoMovimientos[x]);
			else if (listado=="listadoCuentas") selectActual=document.getElementById(listadoCuentas[x]);
			else if (listado=="listadoCatalogo") selectActual=document.getElementById(listadoCatalogo[x]);
			else if (listado=="listadoOrganizacion") selectActual=document.getElementById(listadoOrganizacion[x]);
			else if (listado=="listadoPeriodoNomina") selectActual=document.getElementById(listadoPeriodoNomina[x]);
			selectActual.length=0;

			var nuevaOpcion=document.createElement("option");
			nuevaOpcion.value=0;
			nuevaOpcion.innerHTML="Selecciona Opción...";
			selectActual.appendChild(nuevaOpcion);
			selectActual.disabled=true;
			x++;
		}
	}
	// Compruebo que el select modificado no sea el ultimo de la cadena
	else if((listado=="listadoSNC" && idSelectOrigen!=listadoSNC[listadoSNC.length-1]) || (listado=="listadoMovimientos" && idSelectOrigen!=listadoMovimientos[listadoMovimientos.length-1]) || (listado=="listadoCuentas" && idSelectOrigen!=listadoCuentas[listadoCuentas.length-1]) || (listado=="listadoCatalogo" && idSelectOrigen!=listadoCatalogo[listadoCatalogo.length-1]) || (listado=="listadoOrganizacion" && idSelectOrigen!=listadoOrganizacion[listadoOrganizacion.length-1]) || (listado=="listadoPeriodoNomina" && idSelectOrigen!=listadoPeriodoNomina[listadoPeriodoNomina.length-1]))
	{
		// Obtengo el elemento del select que debo cargar
		if (listado=="listadoSNC") var idSelectDestino=listadoSNC[posicionSelectDestino];
		else if (listado=="listadoMovimientos") var idSelectDestino=listadoMovimientos[posicionSelectDestino];
		else if (listado=="listadoCuentas") var idSelectDestino=listadoCuentas[posicionSelectDestino];
		else if (listado=="listadoCatalogo") var idSelectDestino=listadoCatalogo[posicionSelectDestino];
		else if (listado=="listadoOrganizacion") var idSelectDestino=listadoOrganizacion[posicionSelectDestino];
		else if (listado=="listadoPeriodoNomina") var idSelectDestino=listadoPeriodoNomina[posicionSelectDestino];
		var selectDestino=document.getElementById(idSelectDestino);
		// Creo el nuevo objeto AJAX y envio al servidor el ID del select a cargar y la opcion seleccionada del select origen
		var ajax=nuevoAjax();
		ajax.open("GET", "../../../modulos/tablas_comunes/lib/select_dependientes_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada+"&listado="+listado, true);
		//ajax.open("GET", "http://localhost/gestion/modulos/tablas_comunes/lib/select_dependientes_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada+"&listado="+listado, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==1)
			{
				// Mientras carga elimino la opcion "Selecciona Opcion..." y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion); selectDestino.disabled=true;
			}
			if (ajax.readyState==4)
			{
				selectDestino.parentNode.innerHTML=ajax.responseText;
				//document.getElementById("selectActividad").innerHTML=ajax.responseText;
				//selectDestino.disabled = false;
			}
		}
		ajax.send(null);
	}
}

function filtrarRelacionNomina(nombre, valor1, filtro1, pagina) {
	if (valor1=="") alert("¡Debe seleccionar un documento!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("nombre="+nombre);
	}
}

function validarRelacionAnticipoTerceros(nombre) {
	var idconceptod = document.getElementById("idconceptod").value;
	var idconceptoa = document.getElementById("idconceptoa").value;
	var estado = document.getElementById("estado").value;
	var tipo_nomina = document.getElementById("nomina").value;
	var desde = document.getElementById("desde").value;
	var hasta = document.getElementById("hasta").value;
	var ordenar = document.getElementById("ordenar").value;
	if (idconceptod == "") alert("¡Debe seleccionar un concepto de Deduccion!");
	else if (idconceptoa == "") alert("¡Debe seleccionar un concepto de Aportes!");
	else if ((desde != "" && hasta == "") || (desde == "" && hasta != "")) alert ("¡Periodo Incorrecto!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&idconceptod="+idconceptod+"&desde="+desde+"&hasta="+hasta
									+"&tipo_nomina="+tipo_nomina+"&estado="+estado+"&ordenar="+ordenar+"&idconceptoa="+idconceptoa;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("nombre="+nombre);
	}
}



function validarRelacionConceptoPeriodo(nombre) {
	var idconcepto = document.getElementById("idconcepto").value;
	var estado = document.getElementById("estado").value;
	var tipo_nomina = document.getElementById("nomina").value;
	var desde = document.getElementById("desde").value;
	var hasta = document.getElementById("hasta").value;
	var ordenar = document.getElementById("ordenar").value;
	if (idconcepto == "") alert("¡Debe seleccionar un concepto!");
	else if ((desde != "" && hasta == "") || (desde == "" && hasta != "")) alert ("¡Periodo Incorrecto!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&idconcepto="+idconcepto+"&desde="+desde+"&hasta="+hasta+"&tipo_nomina="+tipo_nomina+"&estado="+estado+"&ordenar="+ordenar;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("nombre="+nombre);
	}
}




function validarRelacionFormaIVSS(nombre) {
	var idsso = document.getElementById("idconcepto1").value;
	var idaportesso = document.getElementById("idconcepto2").value;
	var idrpe = document.getElementById("idconcepto3").value;
	var idaporterpe = document.getElementById("idconcepto4").value;
	var estado = document.getElementById("estado").value;
	var tipo_nomina = document.getElementById("nomina").value;
	var desde = document.getElementById("desde").value;
	var hasta = document.getElementById("hasta").value;
	var ordenar = document.getElementById("ordenar").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (idsso == "" || idrpe == "" || idaportesso == "" || idaporterpe == "") alert("¡Debe seleccionar los conceptos!");
	else if ((desde != "" && hasta == "") || (desde == "" && hasta != "")) alert ("¡Periodo Incorrecto!");
	else {
		if (pdf.checked) {

			var ajax=nuevoAjax();
			ajax.open("POST", "reportes.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "reportes.php?nombre="+nombre+"&idsso="+idsso+"&idaportesso="+idaportesso+"&idrpe="+idrpe+"&idaporterpe="+idaporterpe+"&desde="+desde+"&hasta="+hasta+"&tipo_nomina="+tipo_nomina+"&estado="+estado+"&ordenar="+ordenar;
					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre);
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				var ajax=nuevoAjax();
				ajax.open("POST", "excel.php", true);
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() {
					if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}
					if (ajax.readyState==4) {
						location.href = "excel.php?nombre_archivo="+archivo+"&nombre="+nombre+"&idsso="+idsso+"&idaportesso="+idaportesso+"&idrpe="+idrpe+"&idaporterpe="+idaporterpe+"&desde="+desde+"&hasta="+hasta+"&tipo_nomina="+tipo_nomina+"&estado="+estado+"&ordenar="+ordenar;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre);
			}
		}
	}
}








function validar_relacion_nomina_trabajadores(nombre) {
	var nomina = document.getElementById("nomina").value;
	var periodo = document.getElementById("periodo").value;
	var unidad = document.getElementById("unidad").value;
	var centro = document.getElementById("centro").value;
	var origen = "reportes";
	if (document.getElementById("flagunidad").checked) var flagunidad = "S"; else var flagunidad = "N";
	if (document.getElementById("flagcentro").checked) var flagcentro = "S"; else var flagcentro = "N";

	//alert(nombre);
	if (nomina == "0" || periodo == "0") alert("¡Debe seleccionar la nomina y el periodo!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//alert(ajax.responseText);
				location.href = "reportes.php?nombre="+nombre+"&nomina="+nomina+"&periodo="+periodo+"&unidad="+unidad+"&centro="+centro
								+"&flagunidad="+flagunidad+"&flagcentro="+flagcentro+"&origen="+origen;
			}
		}
		ajax.send("nombre="+nombre);
	}
}



function validar_auditoria_nomina(nombre) {
	var nomina = document.getElementById("nomina").value;
	var periodo = document.getElementById("periodo").value;
	var unidad = document.getElementById("unidad").value;
	var centro = document.getElementById("centro").value;
	if (document.getElementById("flagunidad").checked) var flagunidad = "S"; else var flagunidad = "N";
	if (document.getElementById("flagcentro").checked) var flagcentro = "S"; else var flagcentro = "N";

	if (nomina == "0" || periodo == "0") alert("¡Debe seleccionar la nomina y el periodo!");
	else {
		location.href = "reportes_2.php?nombre="+nombre+"&nomina="+nomina+"&periodo="+periodo+"&unidad="+unidad+"&centro="+centro+"&flagunidad="+flagunidad+"&flagcentro="+flagcentro;
	}
}

function validar_nomina_resumen_anual_trabajador(nombre) {
	var anio_fiscal = document.getElementById("anio_fiscal").value;
	var idtrabajador = document.getElementById("idtrabajador").value;
	var nomina = document.getElementById("nomina").value;
	var centro = document.getElementById("centro").value;
	var archivo = document.getElementById("archivo").value;
	if (idtrabajador=="") alert("¡Debe seleccionar un trabajador!");
	else {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&nomina="+nomina+"&anio_fiscal="+anio_fiscal+"&centro="+centro+"&idtrabajador="+idtrabajador;
		}
	}
	/*var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&idtrabajador="+idtrabajador+"&nomina="+nomina+"&centro="+centro;
		}
	}
	ajax.send("nombre="+nombre);*/
}

function setFiltroUnidadCentro(actual, otro) {
	document.getElementById(otro).value = 0;
	document.getElementById(otro).disabled = true;
	document.getElementById(actual).value = 0;
	document.getElementById(actual).disabled = false;
}

function validar_nomina_resumen_detallado_conceptos(nombre) {
	var nomina = document.getElementById("nomina").value;
	var periodo = document.getElementById("periodo").value;
	var unidad = document.getElementById("unidad").value;
	var centro = document.getElementById("centro").value;
	if (document.getElementById("flagunidad").checked) var flagunidad = "S"; else var flagunidad = "N";
	if (document.getElementById("flagcentro").checked) var flagcentro = "S"; else var flagcentro = "N";
	var archivo = document.getElementById("archivo").value;

	if (nomina == "0" || periodo == "0") alert("¡Debe seleccionar la nomina y el periodo!");
	else {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&nomina="+nomina+"&periodo="+periodo+"&unidad="+unidad+"&centro="+centro+"&flagunidad="+flagunidad+"&flagcentro="+flagcentro;
		}
	}
}

function validar_generar_diskette_banco_venezuela(nombre) {
	var unidad = document.getElementById("unidad").value;
	var centro = document.getElementById("centro").value;
	if (document.getElementById("flagunidad").checked) var flagunidad = "S"; else var flagunidad = "N";
	if (document.getElementById("flagcentro").checked) var flagcentro = "S"; else var flagcentro = "N";
	var archivo = document.getElementById("archivo").value;
	var banco = document.getElementById("banco").value;
	var cuenta = document.getElementById("cuenta").value;
	var fecha = document.getElementById("fecha").value;

	var nominas = "";
	frmentrada = document.getElementById("frmentrada");
	for(i=0; n=frmentrada.elements[i]; i++) {
		if (n.name == "nomina") nominas += n.value + "|";
		else if (n.name == "periodo") nominas += n.value + ";";
	}
	var len = nominas.length; len--;
	nominas = nominas.substr(0, len);

	if (nominas == "") alert("¡Debe seleccionar la nomina y el periodo!");
	else if (banco == "0" || cuenta == "0") alert("¡Debe seleccionar el banco y la cuenta bancaria!");
	else {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			var ajax=nuevoAjax();
			ajax.open("POST", "txt.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "descargar_txt.php?nombre="+nombre+"&nombre_archivo="+archivo+"&nominas="+nominas+"&unidad="+unidad+"&centro="+centro+"&flagunidad="+flagunidad+"&flagcentro="+flagcentro+"&banco="+banco+"&cuenta="+cuenta+"&fecha="+fecha;
					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre+"&nombre_archivo="+archivo+"&nominas="+nominas+"&unidad="+unidad+"&centro="+centro+"&flagunidad="+flagunidad+"&flagcentro="+flagcentro+"&banco="+banco+"&cuenta="+cuenta+"&fecha="+fecha);
		}
	}
}

function validar_nomina_detalle_conceptos(nombre) {
	var nomina = document.getElementById("nomina").value;
	var periodo = document.getElementById("periodo").value;
	var unidad = document.getElementById("unidad").value;
	var centro = document.getElementById("centro").value;
	if (document.getElementById("flagunidad").checked) var flagunidad = "S"; else var flagunidad = "N";
	if (document.getElementById("flagcentro").checked) var flagcentro = "S"; else var flagcentro = "N";
	var concepto = document.getElementById("concepto").value;

	if (nomina == "0" || periodo == "0" || concepto == "0") alert("¡Debe seleccionar la nomina, el periodo y el concepto!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&nomina="+nomina+"&periodo="+periodo+"&concepto="+concepto+"&unidad="+unidad+"&centro="+centro+"&flagunidad="+flagunidad+"&flagcentro="+flagcentro;
			}
		}
		ajax.send("nombre="+nombre);
	}
}
</script>
</head>
<body style="background-color:#CCCCCC;">
<div id="divCargando" style="background-color:#CCCCCC; width:250px; height:100px; position: absolute; left: 50%; top: 50%; margin-top: -100px; margin-left: -250px; border: 1px solid black; display:none"></div>

<?php
include "../../../conf/conex.php";
conectarse();
extract($_GET);
//    ---------------------------------------------
switch ($nombre) {
    //    Relacion de Nomina...
    case "filtro_relacion_nomina":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Relacion de N&oacute;mina</div>
        <br /><br />
        <form name="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Documento: </td>
                <td>
                    <input type="hidden" name="idorden" id="idorden" value="" />
                    <input type="text" name="orden" id="orden" style="width:100px" readonly />
                    <input type="text" name="beneficiario" id="beneficiario" style="width:293px" readonly />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onClick="window.open('../../listas/lista.php?lista=orden_compra_servicio', 'wLista','dependent=yes, height=600, width=900, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarRelacionNomina('<?echo $_GET['nombre']; ?>', document.getElementById('idorden').value, 'idorden', 'nomina');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Relacion Anticipo A Terceros...
    case "relacion_anticipo_terceros":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Relacion de N&oacute;mina</div>
        <br /><br />
        <form name="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
        	<tr>
                <td align="right">Nómina: </td>
                <td>
                    <select name="nomina" id="nomina" style="width:300px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Estado: </td>
                <td colspan='3'>
                    <select name="estado" id="estado">
                    	<option value="">[Todas]</option>
                    	<option value="Pre Nomina">Pre Nomina</option>
                    	<option value="procesado">Procesado</option>
                    	<option value="Anulado">Anulado</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Concepto Deduccion: </td>
                <td>
                    <select name="idconceptod" id="idconceptod" style="width:300px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Deduccion')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Deduccion')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>


            <tr>
                <td align="right">Concepto Aporte: </td>
                <td>
                    <select name="idconceptoa" id="idconceptoa" style="width:300px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Aporte')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Aporte')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>

            <tr>
			  <td align="right">Desde: </td>
			  <td>
				<input type="text" name="desde" id="desde" size="15" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "desde",
					button        : "f_trigger_a",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
              </td>
			  <td align="right">Hasta: </td>
			  <td>
				<input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "hasta",
					button        : "f_trigger_c",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
			  </td>
			</tr>
			<tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validarRelacionAnticipoTerceros('<?echo $_GET['nombre']; ?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Relacion Anticipo A Terceros...
    case "relacion_conceptos_periodo":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Relacion de N&oacute;mina</div>
        <br /><br />
        <form name="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
        	<tr>
                <td align="right">Nómina: </td>
                <td>
                    <select name="nomina" id="nomina" style="width:300px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Estado: </td>
                <td colspan='3'>
                    <select name="estado" id="estado">
                    	<option value="">[Todas]</option>
                    	<option value="Pre Nomina">Pre Nomina</option>
                    	<option value="procesado">Procesado</option>
                    	<option value="Anulado">Anulado</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Concepto: </td>
                <td>
                    <select name="idconcepto" id="idconcepto" style="width:300px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta <> 'Neutro')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta <> 'Neutro')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>

            <tr>
			  <td align="right">Desde: </td>
			  <td>
				<input type="text" name="desde" id="desde" size="15" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "desde",
					button        : "f_trigger_a",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
              </td>
			  <td align="right">Hasta: </td>
			  <td>
				<input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "hasta",
					button        : "f_trigger_c",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
			  </td>
			</tr>
			<tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validarRelacionConceptoPeriodo('<?echo $_GET['nombre']; ?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Relacion de Nomina...
    case "relacion_nomina_trabajadores":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Relacion de N&oacute;mina de Trabajadores</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Nómina: </td>
                <td>
                    <select name="nomina" id="nomina" style="width:300px;" onChange="cargaContenido(this.id, 'listadoPeriodoNomina')">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Periodo: </td>
                <td>
                    <select name="periodo" id="periodo" style="width:300px;" disabled="disabled">
                        <option value="0">Selecciona Opci&oacute;n...</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Unidad Funcional: </td>
                <td>
                	<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagunidad" value="unidad" checked="checked" onClick="setFiltroUnidadCentro(this.value, 'centro');" />
                    <select id="unidad" name="unidad" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Centro de Costo: </td>
                <td>
					<?
        $sql_centro_costo = mysql_query("SELECT
															no.idcategoria_programatica,
															cp.codigo,
															ue.denominacion
														FROM
															categoria_programatica cp
															INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
															INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
														WHERE no.idcategoria_programatica <> '0' AND no.modulo = '1'
														GROUP BY idcategoria_programatica
														ORDER BY codigo");
        ?>
                    <input type="radio" name="filtro" id="flagcentro" value="centro" onClick="setFiltroUnidadCentro(this.value, 'unidad');" />
                    <select id="centro" name="centro" style="width:300px;" disabled="disabled">
                        <option value="0">.:: Todos ::.</option>
                        <?
        while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
            ?>
                        	<option value="<?=$bus_centro_costo["idcategoria_programatica"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                        	<?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_relacion_nomina_trabajadores('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Resumen de Conceptos...
    case "nomina_resumen_conceptos":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Resumen de Conceptos</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Nómina: </td>
                <td>
                    <select name="nominar" id="nominar" style="width:300px;" onChange="cargaContenido(this.id, 'listadoPeriodoNomina')">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Periodo: </td>
                <td>
                    <select name="periodor" id="periodor" style="width:300px;" disabled="disabled">
                        <option value="0">Selecciona Opci&oacute;n...</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Unidad Funcional: </td>
                <td>
                	<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagunidad" value="unidad" checked="checked" onClick="setFiltroUnidadCentro(this.value, 'centro');" />
                    <select id="unidad" name="unidad" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Centro de Costo: </td>
                <td>
					<?
        $sql_centro_costo = mysql_query("SELECT
															no.idcategoria_programatica,
															cp.codigo,
															ue.denominacion
														FROM
															categoria_programatica cp
															INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
															INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
														WHERE no.idcategoria_programatica <> '0' AND no.modulo = '1'
														GROUP BY idcategoria_programatica
														ORDER BY codigo");
        ?>
                    <input type="radio" name="filtro" id="flagcentro" value="centro" onClick="setFiltroUnidadCentro(this.value, 'unidad');" />
                    <select id="centro" name="centro" style="width:300px;" disabled="disabled">
                        <option value="0">.:: Todos ::.</option>
                        <?
        while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
            ?>
                        	<option value="<?=$bus_centro_costo["idcategoria_programatica"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                        	<?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_relacion_nomina_trabajadores('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Detalle de Conceptos...
    case "nomina_detalle_conceptos":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Detalle de Conceptos</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Nómina: </td>
                <td>
                    <select name="nomina" id="nomina" style="width:300px;" onChange="cargaContenido(this.id, 'listadoPeriodoNomina')">
                        <option value="0">Elige</option>
                    	<?
        $sql          = "select * from tipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Periodo: </td>
                <td>
                    <select name="periodo" id="periodo" style="width:300px;" disabled="disabled">
                        <option value="0">Selecciona Opci&oacute;n...</option>
                    </select>
                </td>
            </tr>
            <!--	AQUI ES	-->
            <tr>
                <td align="right">Concepto: </td>
                <td>
                    <select name="concepto" id="concepto" style="width:300px;">
                        <option value="0">Elige</option>
                    	<?
        /*$sql_conceptos = mysql_query("select
        relacion_concepto_trabajador.idrelacion_concepto_trabajador,
        relacion_concepto_trabajador.tabla,
        relacion_concepto_trabajador.idconcepto
        from
        periodos_nomina,
        tipo_nomina,
        relacion_concepto_trabajador
        where
        periodos_nomina.idperiodos_nomina = '".$idperiodo_nomina."'
        and tipo_nomina.idtipo_nomina = periodos_nomina.idtipo_nomina
        and relacion_concepto_trabajador.idtipo_nomina = tipo_nomina.idtipo_nomina
        group by relacion_concepto_trabajador.tabla, relacion_concepto_trabajador.idconcepto")or die(mysql_error());
         */
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta <> 'Neutro')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta <> 'Neutro')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <!--	HASTA AQUI	-->
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Unidad Funcional: </td>
                <td>
                	<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagunidad" value="unidad" checked="checked" onClick="setFiltroUnidadCentro(this.value, 'centro');" />
                    <select id="unidad" name="unidad" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Centro de Costo: </td>
                <td>
					<?
        $sql_centro_costo = mysql_query("SELECT
															no.idcategoria_programatica,
															cp.codigo,
															ue.denominacion
														FROM
															categoria_programatica cp
															INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
															INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
														WHERE no.idcategoria_programatica <> '0' AND no.modulo = '1'
														GROUP BY idcategoria_programatica
														ORDER BY codigo");
        ?>
                    <input type="radio" name="filtro" id="flagcentro" value="centro" onClick="setFiltroUnidadCentro(this.value, 'unidad');" />
                    <select id="centro" name="centro" style="width:300px;" disabled="disabled">
                        <option value="0">.:: Todos ::.</option>
                        <?
        while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
            ?>
                        	<option value="<?=$bus_centro_costo["idcategoria_programatica"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                        	<?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_nomina_detalle_conceptos('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Resumen Detallado de Conceptos...
    case "nomina_resumen_detallado_conceptos":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Resumen Detallado de Conceptos</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Nómina: </td>
                <td align="right">
                    <select name="nomina" id="nomina" style="width:300px;" onChange="cargaContenido(this.id, 'listadoPeriodoNomina')">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Periodo: </td>
                <td align="right">
                    <select name="periodo" id="periodo" style="width:300px;" disabled="disabled">
                        <option value="0">Selecciona Opci&oacute;n...</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Unidad Funcional: </td>
                <td align="right">
                	<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagunidad" value="unidad" checked="checked" onClick="setFiltroUnidadCentro(this.value, 'centro');" />
                    <select id="unidad" name="unidad" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr>
                 <td align="right">Centro de Costo: </td>
                	<td align="right">
					<?
        $sql_centro_costo = mysql_query("SELECT
															no.idcategoria_programatica,
															cp.codigo,
															ue.denominacion
														FROM
															categoria_programatica cp
															INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
															INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
														WHERE no.idcategoria_programatica <> '0' AND no.modulo = '1'
														GROUP BY idcategoria_programatica
														ORDER BY codigo");
        ?>
                    <input type="radio" name="filtro" id="flagcentro" value="centro" onClick="setFiltroUnidadCentro(this.value, 'unidad');" />
                    <select id="centro" name="centro" style="width:300px;" disabled="disabled">
                        <option value="0">.:: Todos ::.</option>
                        <?
        while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
            ?>
                        	<option value="<?=$bus_centro_costo["idcategoria_programatica"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                        	<?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Archivo: </td>
                <td><input name="archivo" id="archivo" size="25" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_nomina_resumen_detallado_conceptos('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Resumen Anual por Trabajador...
    case "nomina_resumen_anual_trabajador":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Resumen Anual por Trabajador</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td align="right">A&ntilde;o Fiscal: </td>
                <td align="right">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
anio_fiscal();
?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Trabajador: </td>
                <td align="right">
                    <input type="hidden" name="idtrabajador" id="idtrabajador" value="" />
                    <input type="text" name="nomtrabajador" id="nomtrabajador" style="width:275px" readonly />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onClick="window.open('../../listas/lista.php?lista=trabajadores', 'wLista','dependent=yes, height=600, width=900, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td align="right">Nómina: </td>
                <td align="right">
                    <select name="nomina" id="nomina" style="width:300px;" onChange="cargaContenido(this.id, 'listadoPeriodoNomina')">
                        <option value="0">.:: Todos ::.</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Centro de Costo: </td>
                <td align="right">
					<?
        $sql_centro_costo = mysql_query("SELECT
															no.idcategoria_programatica,
															cp.codigo,
															ue.denominacion
														FROM
															categoria_programatica cp
															INNER JOIN unidad_ejecutora ue ON (cp.idunidad_ejecutora = ue.idunidad_ejecutora)
															INNER JOIN niveles_organizacionales no ON (cp.idcategoria_programatica = no.idcategoria_programatica)
														WHERE no.idcategoria_programatica <> '0' AND no.modulo = '1'
														GROUP BY idcategoria_programatica
														ORDER BY codigo");
        ?>
                    <select id="centro" name="centro" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?
        while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
            ?>
                        	<option value="<?=$bus_centro_costo["idcategoria_programatica"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                        	<?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Archivo: </td>
                <td><input name="archivo" id="archivo" size="25" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_nomina_resumen_anual_trabajador('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Generar diskette de banco...
    case "generar_diskette_banco_venezuela":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Generar Archivo de Banco</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
        	<tr>
            	<td colspan="2" align="center">
                Inserte las nominas a procesar
                <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onClick="window.open('nomina_periodo_insertar.php', 'wLista','dependent=yes, height=200, width=525, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
            <td id="listaNominas" colspan="2">&nbsp;

            </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Unidad Funcional: </td>
                <td align="right">
                	<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagunidad" value="unidad" checked="checked" onClick="setFiltroUnidadCentro(this.value, 'centro');" />
                    <select id="unidad" name="unidad" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Centro de Costo: </td>
                <td align="right">
					<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1
													and idcategoria_programatica != 0") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagcentro" value="centro" onClick="setFiltroUnidadCentro(this.value, 'unidad');" />
                    <select id="centro" name="centro" style="width:300px;" disabled="disabled">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Banco: </td>
                <td align="right">
                    <select name="banco" id="banco" onChange="cargaContenido(this.id, 'listadoCuentas')" class="Select1">
                        <option value="0">Elige</option>
                        <?php
$query = mysql_query("SELECT idbanco, denominacion FROM banco ORDER BY denominacion");
        while ($field = mysql_fetch_array($query)) {
            echo "<option value='" . $field['idbanco'] . "'>" . $field['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Cuenta: </td>
                <td align="right">
                    <select name="cuenta" id="cuenta" disabled class="Select1">
                        <option value="0">Selecciona Opci&oacute;n...</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
			  <td align="right">Fecha: </td>
			  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" name="fecha" id="fecha" size="10" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "fecha",
					button        : "f_trigger_a",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
              </td>
			</tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Archivo: </td>
                <td><input name="archivo" id="archivo" size="25" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_generar_diskette_banco_venezuela('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    //    Auditoria de Nomina...
    case "auditoria_nomina":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Auditoria de N&oacute;mina</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <?
        $sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf = mysql_query($sql) or die($sql . mysql_error());
        $conf       = mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Nómina: </td>
                <td>
                    <select name="nomina" id="nomina" style="width:300px;" onChange="cargaContenido(this.id, 'listadoPeriodoNomina')">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado' OR gn.estado = 'Pre Nomina'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Periodo: </td>
                <td>
                    <select name="periodo" id="periodo" style="width:300px;" disabled="disabled">
                        <option value="0">Selecciona Opci&oacute;n...</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
            <tr>
                <td align="right">Unidad Funcional: </td>
                <td>
                	<?
        $foros  = array();
        $result = mysql_query("SELECT idniveles_organizacionales,
													denominacion,
													sub_nivel
												FROM
													niveles_organizacionales
												WHERE
													modulo = 1") or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $foro  = $row['idniveles_organizacionales'];
            $padre = $row['sub_nivel'];
            if (!isset($foros[$padre])) {
                $foros[$padre] = array();
            }

            $foros[$padre][$foro] = $row;
        }
        ?>
                    <input type="radio" name="filtro" id="flagunidad" value="unidad" checked="checked" onClick="setFiltroUnidadCentro(this.value, 'centro');" />
                    <select id="unidad" name="unidad" style="width:300px;">
                        <option value="0">.:: Todos ::.</option>
                        <?=listar_foros(0, "", "")?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Centro de Costo: </td>
                <td>
					<?
        $sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
															un.denominacion,
															ct.codigo
														from
															niveles_organizacionales no,
															unidad_ejecutora un,
															categoria_programatica ct
														where
															no.modulo='1'
															and no.idcategoria_programatica != '0'
															and ct.idcategoria_programatica = no.idcategoria_programatica
															and ct.idunidad_ejecutora = un.idunidad_ejecutora
														order by ct.codigo");
        ?>
                    <input type="radio" name="filtro" id="flagcentro" value="centro" onClick="setFiltroUnidadCentro(this.value, 'unidad');" />
                    <select id="centro" name="centro" style="width:300px;" disabled="disabled">
                        <option value="0">.:: Todos ::.</option>
                        <?
        while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
            ?>
                        	<option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                        	<?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_auditoria_nomina('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;

    // FORMA 13-12 IVSS
    case "relacion_forma_1312":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Relacion de N&oacute;mina</div>
        <br /><br />
        <form name="frmentrada">
        <table>
        	<tr>
                <td align="right">Nómina: </td>
                <td colspan='4'>
                    <select name="nomina" id="nomina" style="width:500px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "SELECT
									gn.idtipo_nomina,
									tn.titulo_nomina
								FROM
									generar_nomina gn
									INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
								WHERE
									gn.estado = 'Procesado'
								GROUP BY idtipo_nomina";
        $query_nomina = mysql_query($sql) or die($sql . mysql_error());
        while ($field_nomina = mysql_fetch_array($query_nomina)) {
            ?><option value="<?=$field_nomina['idtipo_nomina']?>"><?=htmlentities($field_nomina['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Estado: </td>
                <td colspan='4'>
                    <select name="estado" id="estado">
                    	<option value="">[Todas]</option>
                    	<option value="Pre Nomina">Pre Nomina</option>
                    	<option value="procesado">Procesado</option>
                    	<option value="Anulado">Anulado</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">&nbsp;</td>
            </tr>
            <tr>
                <td align="right">Cotizaci&oacute;n Semanal IVSS Trabajador: </td>
                <td colspan='4'>
                    <select name="idconcepto1" id="idconcepto1" style="width:500px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Deduccion')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Deduccion')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Aporte Semanal IVSS Patrono: </td>
                <td colspan='4'>
                    <select name="idconcepto2" id="idconcepto2" style="width:500px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Aporte')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Aporte')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Cotizaci&oacute;n Semanal R.P.E. Trabajador: </td>
                <td colspan='4'>
                    <select name="idconcepto3" id="idconcepto3" style="width:500px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Deduccion')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Deduccion')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Aporte Semanal R.P.E. Patrono: </td>
                <td colspan='4'>
                    <select name="idconcepto4" id="idconcepto4" style="width:500px;">
                        <option value="0">Elige</option>
                    	<?
        $sql = "(SELECT
									cn.idconceptos_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'conceptos_nomina' AS tabla
								FROM
									conceptos_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.tipo_concepto = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Aporte')

								UNION

								(SELECT
									cn.idconstantes_nomina AS id,
									cn.descripcion,
									tcn.afecta,
									'constantes_nomina' AS tabla
								FROM
									constantes_nomina cn
									INNER JOIN tipo_conceptos_nomina tcn ON (cn.afecta = tcn.idconceptos_nomina)
								WHERE tcn.afecta = 'Aporte')

								ORDER BY afecta, descripcion";
        $query_concepto = mysql_query($sql) or die($sql . mysql_error());
        while ($field_concepto = mysql_fetch_array($query_concepto)) {
            ?><option value="<?=$field_concepto['tabla']?>|<?=$field_concepto['id']?>"><?=htmlentities($field_concepto['descripcion'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">&nbsp;</td>
            </tr>
            <tr>
			  <td align="right">Desde: </td>
			  <td>
				<input type="text" name="desde" id="desde" size="15" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "desde",
					button        : "f_trigger_a",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
              </td>
			  <td align="right">Hasta: </td>
			  <td>
				<input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly />
				<img src="../../../imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				<script type="text/javascript">
					Calendar.setup({
					inputField    : "hasta",
					button        : "f_trigger_c",
					align         : "Tr",
					ifFormat      : "%Y-%m-%d"
					});
				</script>
			  </td>
			</tr>
			<tr>
                <td align="right">&nbsp;</td>
            </tr>
			<tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    </select>
                </td>
			</tr>
			<tr>
                <td align="right">&nbsp;</td>
            </tr>
             <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo2();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo2();" /> Excel <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="5"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validarRelacionFormaIVSS('<?echo $_GET['nombre']; ?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
		<?
        break;
}
?>
</body>
</html>