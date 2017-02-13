<?php
session_start();
include "../../../funciones/funciones.php";
include "../../../conf/conex.php";
conectarse();
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

$pro = array(
    "sector_mo" => "sector_mo",
    "programa"  => "programa",
);
$_SESSION['listadoProgramas'] = serialize($pro);

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

$sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
$query_conf = mysql_query($sql) or die($sql . mysql_error());
$conf       = mysql_fetch_array($query_conf);
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

var listadoProgramas=new Array();
listadoProgramas[0]="sector_mo";
listadoProgramas[1]="programa";

var listadoCatalogo=new Array();
listadoCatalogo[0]="grupo";
listadoCatalogo[1]="sub_grupo";
listadoCatalogo[2]="seccion";

var listadoOrganizacion=new Array();
listadoOrganizacion[0]="organizacion";
listadoOrganizacion[1]="nivel_organizacion";

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
	else if (listado=="listadoProgramas") var posicionSelectDestino=buscarEnArray(listadoProgramas, idSelectOrigen)+1;
	else if (listado=="listadoCatalogo") var posicionSelectDestino=buscarEnArray(listadoCatalogo, idSelectOrigen)+1;
	else if (listado=="listadoOrganizacion") var posicionSelectDestino=buscarEnArray(listadoOrganizacion, idSelectOrigen)+1;
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
		else if (listado=="listadoProgramas") var num = listadoProgramas.length;
		else if (listado=="listadoCatalogo") var num = listadoCatalogo.length;
		else if (listado=="listadoOrganizacion") var num = listadoOrganizacion.length;

		// Busco todos los selects siguientes al que inicio el evento onChange y les cambio el estado y deshabilito
		while(x <= num-1)
		{
			if (listado=="listadoSNC") selectActual=document.getElementById(listadoSNC[x]);
			else if (listado=="listadoMovimientos") selectActual=document.getElementById(listadoMovimientos[x]);
			else if (listado=="listadoCuentas") selectActual=document.getElementById(listadoCuentas[x]);
			else if (listado=="listadoProgramas") selectActual=document.getElementById(listadoProgramas[x]);
			else if (listado=="listadoCatalogo") selectActual=document.getElementById(listadoCatalogo[x]);
			else if (listado=="listadoOrganizacion") selectActual=document.getElementById(listadoOrganizacion[x]);
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
	else if((listado=="listadoSNC" && idSelectOrigen!=listadoSNC[listadoSNC.length-1]) || (listado=="listadoMovimientos" && idSelectOrigen!=listadoMovimientos[listadoMovimientos.length-1]) || (listado=="listadoCuentas" && idSelectOrigen!=listadoCuentas[listadoCuentas.length-1]) || (listado=="listadoProgramas" && idSelectOrigen!=listadoProgramas[listadoProgramas.length-1]) || (listado=="listadoCatalogo" && idSelectOrigen!=listadoCatalogo[listadoCatalogo.length-1]) || (listado=="listadoOrganizacion" && idSelectOrigen!=listadoOrganizacion[listadoOrganizacion.length-1]))
	{
		// Obtengo el elemento del select que debo cargar
		if (listado=="listadoSNC") var idSelectDestino=listadoSNC[posicionSelectDestino];
		else if (listado=="listadoMovimientos") var idSelectDestino=listadoMovimientos[posicionSelectDestino];
		else if (listado=="listadoCuentas") var idSelectDestino=listadoCuentas[posicionSelectDestino];
		else if (listado=="listadoProgramas") var idSelectDestino=listadoProgramas[posicionSelectDestino];
		else if (listado=="listadoCatalogo") var idSelectDestino=listadoCatalogo[posicionSelectDestino];
		else if (listado=="listadoOrganizacion") var idSelectDestino=listadoOrganizacion[posicionSelectDestino];
		var selectDestino=document.getElementById(idSelectDestino);
		// Creo el nuevo objeto AJAX y envio al servidor el ID del select a cargar y la opcion seleccionada del select origen
		var ajax=nuevoAjax();
		ajax.open("GET", "../../../modulos/tablas_comunes/lib/select_dependientes_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada+"&listado="+listado, true);
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

function llamarOrdenar(nombre, ordenar) {
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			location.href = "reportes.php?nombre="+nombre+"&orden="+ordenar;
			document.getElementById("divCargando").style.display = "none";
		}
	}
	ajax.send("nombre="+nombre);
}

function validar_preres(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("categoria").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chksobregiradas = 0;
	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkreservado = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chksobregiradas" && form.elements[i].checked) chksobregiradas = 1;
		else if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkreservado" && form.elements[i].checked) chkreservado = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible + "|"+ chkreservado;

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&chkrestar="+chkrestar+"&checks="+checks+"&chksobregiradas="+chksobregiradas;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&chkrestar="+chkrestar+"&checks="+checks+"&chksobregiradas="+chksobregiradas;
		}
	}
}

function validar_consolidado(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	//var fdesde=document.getElementById("desde").value;
	//var fhasta=document.getElementById("hasta").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible;

	//if (fdesde == "" || fhasta == "") alert("¡Debe ingresar el periodo a mostrar!");
	//else if (fdesde > fhasta) alert("¡Periodo incorrecto!");
	//else{
		if (pdf.checked) {
			var ajax=nuevoAjax();
			ajax.open("POST", "reportes.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&checks="+checks;
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
						location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&checks="+checks;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre);
			}
		}
	//}
}

function validar_ejecucion_detallada(nombre) {
	var form=document.getElementById("frmentradadet");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("categoria").value;
	var estado=document.getElementById("estado").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkjustificacionoc = 0;
	var chkjustificacionop = 0;
	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkjustificacionoc" && form.elements[i].checked) chkjustificacionoc = 1;
		if (form.elements[i].id == "chkjustificacionop" && form.elements[i].checked) chkjustificacionop = 1;
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 0;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible;

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&chkrestar="+chkrestar+"&checks="+checks+"&estado="+estado+"&chkjustificacionoc="+chkjustificacionoc+"&chkjustificacionop="+chkjustificacionop;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento
			+"&tipo_presupuesto="+tipo_presupuesto
			+"&idcategoria_programatica="+idcategoria_programatica
			+"&chkrestar="+chkrestar
			+"&estado="+estado
			+"&chkjustificacionoc="+chkjustificacionoc
			+"&chkjustificacionop="+chkjustificacionop
			+"&chkinicial="+chkinicial
			+"&chkaumentos="+chkaumentos
			+"&chkdisminuciones="+chkdisminuciones
			+"&chkmodificaciones="+chkmodificaciones
			+"&chkajustada="+chkajustada
			+"&chkprecompromiso="+chkprecompromiso
			+"&chkcompromiso="+chkcompromiso
			+"&chkcausado="+chkcausado
			+"&chkpagado="+chkpagado
			+"&chkdisponible="+chkdisponible;
		}
	}
}

function validar_porsector(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("categoria").value;
	var fdesde=document.getElementById("desde").value;
	var fhasta=document.getElementById("hasta").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkreservado = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkreservado" && form.elements[i].checked) chkreservado = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible + "|"+ chkreservado;

	if (fdesde == "" || fhasta == "") alert("¡Debe ingresar el periodo a mostrar!");
	else if (fdesde > fhasta) alert("¡Periodo incorrecto!");
	else{
		if (pdf.checked) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&chkrestar="+chkrestar+"&checks="+checks+"&fdesde="+fdesde+"&fhasta="+fhasta;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&chkrestar="+chkrestar+"&fdesde="+fdesde+"&fhasta="+fhasta+"&checks="+checks;
			}
		}
	}
}

function validar_consolidado_sector(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var sector=document.getElementById("sector").value;
	var fdesde=document.getElementById("desde").value;
	var fhasta=document.getElementById("hasta").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkreservado = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chksobregiradas" && form.elements[i].checked) chksobregiradas = 1;
		else if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkreservado" && form.elements[i].checked) chkreservado = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible + "|"+ chkreservado;

	if (document.getElementById("resumido").checked) nombre = nombre + "_resumido";

	if (fdesde == "" || fhasta == "") alert("¡Debe ingresar el periodo a mostrar!");
	else if (fdesde > fhasta) alert("¡Periodo incorrecto!");
	else{
		if (pdf.checked) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idsector="+sector+"&chkrestar="+chkrestar+"&checks="+checks+"&fdesde="+fdesde+"&fhasta="+fhasta;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idsector="+sector+"&chkrestar="+chkrestar+"&fdesde="+fdesde+"&fhasta="+fhasta+"&checks="+checks;
			}
		}
	}
}

function llamarFiltrarTipo(nombre, valor, filtro, pagina) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (pdf.checked) {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&"+filtro+"="+valor+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
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
					location.href = "excel.php?nombre="+nombre+"&"+filtro+"="+valor+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre);
		}
	}
}
/*
function ejecucion_por_trimestre(nombre) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var categoria=document.getElementById("categoria").value;
	var trimestre=document.getElementById("trimestre").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (pdf.checked) {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes_2.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes_2.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&idcategoria_programatica="+categoria+"&trimestre="+trimestre+"&tipo_presupuesto="+tipo_presupuesto;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("nombre="+nombre);
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&idcategoria_programatica="+categoria+"&trimestre="+trimestre+"&tipo_presupuesto="+tipo_presupuesto+"&nombre_archivo="+archivo;
		}
	}
}
*/
function validar_detalle_por_partida_tipo_ordinal(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3, pagina) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (valor1=="") alert ("¡LA CATEGORIA PROGRAMATICA ES OBLIGATORIA!")
	else if (valor2=="") alert ("¡LA PARTIDA ES OBLIGATORIA!")
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
					location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto);
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
						location.href = "excel.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto);
			}
		}
	}
}

function validar_disponibilidad_presupuestaria(nombre) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria=document.getElementById("idcategoria").value;
	var idpartida=document.getElementById("idpartida").value;
	var idordinal=document.getElementById("idordinal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria="+idcategoria+"&idpartida="+idpartida+"&idordinal="+idordinal;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria="+idcategoria+"&idpartida="+idpartida+"&idordinal="+idordinal+"&nombre_archivo="+archivo;
		}
	}
}

function validar_presupuesto_detalle_por_partida(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria=document.getElementById("idcategoria").value;
	var idpartida=document.getElementById("idpartida").value;
	var idordinal=document.getElementById("idordinal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkjustificacionoc = 0;
	var chkjustificacionop = 0;
	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkreservado = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkjustificacionoc" && form.elements[i].checked) chkjustificacionoc = 1;
		if (form.elements[i].id == "chkjustificacionop" && form.elements[i].checked) chkjustificacionop = 1;
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkreservado" && form.elements[i].checked) chkreservado = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible + "|"+ chkreservado;

	if (idcategoria == "" || idcategoria == 0) alert ("¡LA CATEGORIA PROGRAMATICA ES OBLIGATORIA!")
	else if (idpartida == "" || idpartida == 0) alert ("¡LA PARTIDA ES OBLIGATORIA!")
	else {
		if (pdf.checked) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria="+idcategoria+"&idpartida="+idpartida+"&idordinal="+idordinal+"&chkrestar="+chkrestar+"&checks="+checks+"&chkjustificacionoc="+chkjustificacionoc+"&chkjustificacionop="+chkjustificacionop;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria="+idcategoria+"&idpartida="+idpartida+"&idordinal="+idordinal+"&chkrestar="+chkrestar+"&checks="+checks+"&nombre_archivo="+archivo+"&chkjustificacionoc="+chkjustificacionoc+"&chkjustificacionop="+chkjustificacionop+"&checks="+checks;
			}
		}
	}
}

function llamarFiltrarEjecucionDetallada(nombre, valor, filtro, pagina) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	if (valor=="0") alert ("¡Debe seleccionar una Categoría Programática!");
	else {
		var pdf=document.getElementById("pdf");
		var excel=document.getElementById("excel");
		var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
		if (pdf.checked) {
			var ajax=nuevoAjax();
			ajax.open("POST", "reportes.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "reportes.php?nombre="+nombre+"&"+filtro+"="+valor+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
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
						location.href = "excel.php?nombre="+nombre+"&"+filtro+"="+valor+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre);
			}
		}
	}
}

function validar_disponibilidad_presupuestaria_periodo(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var desde=document.getElementById("desde").value; desde=desde.trim();
	var hasta=document.getElementById("hasta").value; hasta=hasta.trim();
	var idcategoria=document.getElementById("idcategoria").value; idcategoria=idcategoria.trim();
	var idpartida=document.getElementById("idpartida").value; idpartida=idpartida.trim();
	var idordinal=document.getElementById("idordinal").value; idordinal=idordinal.trim();
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var categoria=document.getElementById("categoria").value;
	if (document.getElementById("detallado").checked) var tipo_reporte = "detallado"; else var tipo_reporte = "resumido";

	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible;


	if (desde=="" || hasta=="") {
		alert ("¡Debe ingresar el período a buscar!");
	}
	else {
		if (pdf.checked) {
			location.href = "reportes_2.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&tipo_reporte="+tipo_reporte+"&idcategoria="+idcategoria+"&idpartida="+idpartida+"&idordinal="+idordinal+"&desde="+desde+"&hasta="+hasta+"&chkrestar="+chkrestar+"&checks="+checks;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&tipo_reporte="+tipo_reporte+"&idcategoria="+idcategoria+"&idpartida="+idpartida+"&idordinal="+idordinal+"&desde="+desde+"&hasta="+hasta+"&chkrestar="+chkrestar+"&checks="+checks+"&nombre_archivo="+archivo;
			}
		}
	}
}

function verificarProyeccionPresupuestaria(nombre, pagina) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("idcategoria_programatica").value;
	var referencia=document.getElementById("referencia").value;
	var mes=document.getElementById("mes").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (pdf.checked) {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&idcategoria_programatica="+idcategoria_programatica+"&referencia="+referencia+"&mes="+mes;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("nombre="+nombre);
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&idcategoria_programatica="+idcategoria_programatica+"&referencia="+referencia+"&mes="+mes+"&nombre_archivo="+archivo;
		}
	}
}

function verificarSimularSolicitudPresupuesto(nombre, pagina) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("idcategoria_programatica").value;
	var sobre=document.getElementById("sobre").value;
	var porcentaje=document.getElementById("porcentaje").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (pdf.checked) {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&idcategoria_programatica="+idcategoria_programatica+"&sobre="+sobre+"&porcentaje="+porcentaje;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("nombre="+nombre);
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&idcategoria_programatica="+idcategoria_programatica+"&sobre="+sobre+"&porcentaje="+porcentaje+"&nombre_archivo="+archivo;
		}
	}
}

function validar_documento_por_partida_tipo(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3, valor4, filtro4, pagina) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (valor1=="" || valor2=="") alert ("¡LA CATEGORIA PROGRAMATICA Y LA PARTIDA SON OBLIGATORIOS!")
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
					location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto);
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
						location.href = "excel.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto);
			}
		}
	}
}

function validar_resumen_por_partida(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idpartida=document.getElementById("idpartida").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible;

	if (idpartida == "") alert("¡Debe seleccionar de la lista una partida!");
	else {
		if (pdf.checked) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idpartida="+idpartida+"&chkrestar="+chkrestar+"&checks="+checks;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idpartida="+idpartida;
			}
		}
	}
}

function enabledArchivo() {
	if (document.getElementById('excel').checked) document.getElementById('archivo').disabled=false; else document.getElementById('archivo').disabled=true;

}

function limpiar_partida() {
	document.getElementById("idpartida").value="";
	document.getElementById("partida").value="";
}

function limpiar_ordinal() {
	document.getElementById("idordinal").value="";
	document.getElementById("codordinal").value="";
	document.getElementById("nomordinal").value="";
}

function validar_categorias() {
	var categoria=document.getElementById("idcategoria").value;
	if (categoria=="" || categoria==0) alert ("¡SELECCIONE UNA CATEGORIA PROGRAMATICA!");
	else window.open("../../listas/lista.php?lista=partidas_por_categoria&idcategoria="+categoria, "wLista","dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200");
}

function validar_ordinal() {
	var partida=document.getElementById("idpartida").value;
	if (partida=="") alert ("¡SELECCIONE UNA PARTIDA!");
	else window.open("../../listas/lista.php?lista=ordinales&idpartida="+partida, "wLista","dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200");
}

function validar_partidas() {
	window.open("../../listas/lista.php?lista=partidas", "wLista","dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200");
}

function validar_movimientos_presupuesto(nombre) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();


	if (pdf.checked) {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
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
					location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto;
					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre);
		}
	}
}

function validar_compromisos_en_transito(nombre) {
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var categoria=document.getElementById("categoria").value;
	var desde=document.getElementById("desde").value;
	var hasta=document.getElementById("hasta").value;

	if ((desde != "" && hasta == "") || (desde == "" && hasta != "")) alert("¡Debe ingresar un periodo correcto de busqueda!");
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
					location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&categoria="+categoria+"&desde="+desde+"&hasta="+hasta;
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
						location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&categoria="+categoria+"&desde="+desde+"&hasta="+hasta;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre);
			}
		}
	}
}

function validar_consolidado_agrupado(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var idcategoria_desde=document.getElementById("idcategoria_desde").value;
	var idcategoria_hasta=document.getElementById("idcategoria_hasta").value;

	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible;

	if ((idcategoria_desde >= idcategoria_hasta) ) alert("¡Seleccione un intervalo de categorias correcto!");
	else {
		if (pdf.checked) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_desde="+idcategoria_desde+"&idcategoria_hasta="+idcategoria_hasta+"&chkrestar="+chkrestar+"&checks="+checks;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel_2.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_desde="+idcategoria_desde+"&idcategoria_hasta="+idcategoria_hasta+"&chkrestar="+chkrestar+"&checks="+checks+"&nombre_archivo="+archivo;
			}
		}
	}
}

function validar_rendicion_mensual(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("categoria").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var mes=document.getElementById("mes").value;

	if (pdf.checked) {
		location.href = "reportes_2.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&mes="+mes;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel_2.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&mes="+mes;
		}
	}
}

function validar_consolidado_por_partida(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idpartida=document.getElementById("idpartida").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var desde=document.getElementById("desde").value;
	var hasta=document.getElementById("hasta").value;

	var chkrestar = 0;
	var chkinicial = 0;
	var chkaumentos = 0;
	var chkdisminuciones = 0;
	var chkmodificaciones = 0;
	var chkajustada = 0;
	var chkprecompromiso = 0;
	var chkcompromiso= 0;
	var chkcausado = 0;
	var chkpagado = 0;
	var chkdisponible = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkrestar" && form.elements[i].checked) chkrestar = 1;
		else if (form.elements[i].id == "chkinicial" && form.elements[i].checked) chkinicial = 1;
		else if (form.elements[i].id == "chkaumentos" && form.elements[i].checked) chkaumentos = 1;
		else if (form.elements[i].id == "chkdisminuciones" && form.elements[i].checked) chkdisminuciones = 1;
		else if (form.elements[i].id == "chkmodificaciones" && form.elements[i].checked) chkmodificaciones = 1;
		else if (form.elements[i].id == "chkajustada" && form.elements[i].checked) chkajustada = 1;
		else if (form.elements[i].id == "chkprecompromiso" && form.elements[i].checked) chkprecompromiso = 1;
		else if (form.elements[i].id == "chkcompromiso" && form.elements[i].checked) chkcompromiso = 1;
		else if (form.elements[i].id == "chkcausado" && form.elements[i].checked) chkcausado = 1;
		else if (form.elements[i].id == "chkpagado" && form.elements[i].checked) chkpagado = 1;
		else if (form.elements[i].id == "chkdisponible" && form.elements[i].checked) chkdisponible = 1;
	}
	var checks = chkinicial + "|"+ chkaumentos + "|"+ chkdisminuciones + "|"+ chkmodificaciones + "|"+ chkajustada + "|"+ chkprecompromiso + "|"+ chkcompromiso + "|"+ chkcausado + "|"+ chkpagado + "|"+ chkdisponible;

	if (desde == "" || hasta == "") alert("¡Debe ingresar el periodo a filtrar!");
	else {
		if (pdf.checked) {
			location.href = "reportes_2.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idpartida="+idpartida+"&desde="+desde+"&hasta="+hasta+"&checks="+checks+"&chkrestar="+chkrestar;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel_2.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idpartida="+idpartida+"&desde="+desde+"&hasta="+hasta+"&checks="+checks+"&chkrestar="+chkrestar;
			}
		}
	}
}

function validar_mensual_onapre(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto_mo").value;
	var financiamiento=document.getElementById("financiamiento_mo").value;
	var anio_fiscal=document.getElementById("anio_fiscal_mo").value;
	var mes=document.getElementById("mes_mo").value;
	var idcategoria_programatica=document.getElementById("categoria_mo").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&mes="+mes;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&mes="+mes;
		}
	}
}

function validar_trimestral_onapre(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto_mo").value;
	var financiamiento=document.getElementById("financiamiento_mo").value;
	var anio_fiscal=document.getElementById("anio_fiscal_mo").value;
	var mes=document.getElementById("trimestre_mo").value;
	var idcategoria_programatica=document.getElementById("categoria_mo").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&mes="+mes;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&mes="+mes;
		}
	}
}

function validar_anual_onapre(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto_mo").value;
	var financiamiento=document.getElementById("financiamiento_mo").value;
	var anio_fiscal=document.getElementById("anio_fiscal_mo").value;
	var idcategoria_programatica=document.getElementById("categoria_mo").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica;
		}
	}
}


function validar_consolidado_sector_onapre(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto_mo").value;
	var financiamiento=document.getElementById("financiamiento_mo").value;
	var anio_fiscal=document.getElementById("anio_fiscal_mo").value;
	var trimestre=document.getElementById("trimestre_mo").value;
	var idsector=document.getElementById("sector_mo").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idsector="+idsector+"&trimestre="+trimestre;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idsector="+idsector+"&trimestre="+trimestre;
		}
	}
}

function validar_consolidado_programa_onapre(nombre) {
	if (document.getElementById("sector_mo").value == ''){
		alert("Debe seleccionar un Sector");
	}else{

		var form=document.getElementById("frmentrada");
		var tipo_presupuesto=document.getElementById("tipo_presupuesto_mo").value;
		var financiamiento=document.getElementById("financiamiento_mo").value;
		var anio_fiscal=document.getElementById("anio_fiscal_mo").value;
		var trimestre=document.getElementById("trimestre_mo").value;
		var idsector=document.getElementById("sector_mo").value;
		var idprograma=document.getElementById("programa").value;
		var pdf=document.getElementById("pdf");
		var excel=document.getElementById("excel");
		var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

		if (pdf.checked) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idsector="+idsector+"&idprograma="+idprograma+"&trimestre="+trimestre;
		}
		else if (excel.checked) {
			if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
			else {
				location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idsector="+idsector+"&idprograma="+idprograma+"&trimestre="+trimestre;
			}
		}
	}
}


function validar_consolidado_categoria_onapre(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var idcategoria_programatica=document.getElementById("categoria").value;
	var trimestre=document.getElementById("trimestre_mo").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&trimestre="+trimestre;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&idcategoria_programatica="+idcategoria_programatica+"&trimestre="+trimestre;
		}
	}

}





function validar_consolidado_general_onapre(nombre) {
	var form=document.getElementById("frmentrada");
	var tipo_presupuesto=document.getElementById("tipo_presupuesto").value;
	var financiamiento=document.getElementById("financiamiento_mo").value;
	var anio_fiscal=document.getElementById("anio_fiscal").value;
	var trimestre=document.getElementById("trimestre_mo").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();


		if (pdf.checked) {
			var ajax=nuevoAjax();
			ajax.open("POST", "reportes.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() {
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&trimestre="+trimestre;
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
						location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&anio_fiscal="+anio_fiscal+"&financiamiento="+financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&trimestre="+trimestre;
						document.getElementById("divCargando").style.display = "none";
					}
				}
				ajax.send("nombre="+nombre);
			}
		}

}


</script>
</head>
<body style="background-color:#CCCCCC;">
<div id="divCargando" style="background-color:#CCCCCC; width:250px; height:100px; position: absolute; left: 50%; top: 50%; margin-top: -100px; margin-left: -250px; border: 1px solid black; display:none"></div>

<?php

extract($_GET);
//    ---------------------------------------------
switch ($nombre) {
    //    Programas...
    case "programa":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Programas</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="sector.codigo, programa.codigo">Sector</option>
                        <option value="programa.denominacion">Denominaci&oacute;n</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Sub-Programas...
    case "sprograma":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Sub-Programas</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="sector.codigo, programa.codigo, sub_programa.codigo">Sector</option>
                        <option value="programa.codigo, sub_programa.codigo">Programa</option>
                        <option value="sub_programa.denominacion">Denominaci&oacute;n</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Proyectos...
    case "proyecto":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Proyectos</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="sector.codigo, programa.codigo, sub_programa.codigo, proyecto.codigo">Sector</option>
                        <option value="programa.codigo, sub_programa.codigo, proyecto.codigo">Programa</option>
                        <option value="sub_programa.codigo, proyecto.codigo">Sub-Programa</option>
                        <option value="proyecto.denominacion">Denominaci&oacute;n</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Actividades...
    case "actividad":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar las Actividades</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="sector.codigo, programa.codigo, sub_programa.codigo, proyecto.codigo, actividad.codigo">Sector</option>
                        <option value="programa.codigo, sub_programa.codigo, proyecto.codigo, actividad.codigo">Programa</option>
                        <option value="sub_programa.codigo, proyecto.codigo, actividad.codigo">Sub-Programa</option>
                        <option value="proyecto.codigo, actividad.codigo">Proyecto</option>
                        <option value="actividad.denominacion">Denominaci&oacute;n</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Unidad Ejecutora...
    case "unidade":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar las Unidades Ejecutoras</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="denominacion">Denominacion</option>
                        <option value="responsable">Responsable</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Categoria Programatica...
    case "catprog":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar las Categor&iacute;as Program&aacute;ticas</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="categoria_programatica.codigo">C&oacute;digo</option>
                        <option value="unidad_ejecutora.denominacion">Denominaci&oacute;n</option>
                        <option value="unidad_ejecutora.responsable">Responsable</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Clasificador Presupuestario...
    case "clapre":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar el Clasificador Presupuestario</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                   <select name="ordenarPor" id="ordenarPor">
                        <option value="partida, generica, especifica, sub_especifica">Partida</option>
                        <option value="denominacion">Denominaci&oacute;n</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Ordinales...
    case "ordinal":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Ordinales</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="codigo">C&oacute;digo</option>
                        <option value="denominacion">Denominacion</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value)" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Presupuesto Original...
    case "preori":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Presupuesto Original</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrarTipo('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, '<?php echo "idcategoria_programatica"; ?>')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Resumen por Categorias...
    case "preres":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Presupuesto por Categor&iacute;as</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria" id="categoria" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_preres('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chksobregiradas" id="chksobregiradas" /> &nbsp; Mostrar solo partidas sobregiradas </td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkreservado" checked="checked" /> &nbsp; Reservado para Disminuir <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>

        </center>
        </form>
        <?
        break;

    //    Resumen Consolidado...
    case "consolidado":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Resumen por Partida Consolidado</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <?php
/*
        <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
        <tr>
        <td align="right">Desde: </td>
        <td>
        <input type="text" name="desde" id="desde" size="15" maxlength="10" readonly="readonly" />
        <img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
        Calendar.setup({
        inputField    : "desde",
        button        : "f_trigger_a",
        align         : "Tr",
        ifFormat      : "%Y-%m-%d"
        });
        </script>
        <td align="right">Hasta: </td>
        <td>
        <input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly="readonly" />
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
         */
        ?>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Consolidado por Categoria...
    case "porsector":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar el Consolidado por Categor&iacute;as</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
                <td colspan="3">
                    <select name="categoria" id="categoria" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
              <td align="right">Desde: </td>
              <td>
                <input type="text" name="desde" id="desde" size="15" maxlength="10" readonly="readonly" />
                <img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                    Calendar.setup({
                    inputField    : "desde",
                    button        : "f_trigger_a",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                    });
                </script>
              <td align="right">Hasta: </td>
              <td>
                <input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly="readonly" />
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
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_porsector('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

         <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" style="display:none;" /></td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkreservado" checked="checked" /> &nbsp; Reservado para Disminuir <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" style="display:none;" />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Consolidado por Sector...
    case "consolidado_sector":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar el Consolidado por Sector</div>
        <br /><br />
        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Sector: </td>
                <td colspan="3">
                    <select name="sector" id="sector" style="width:350px;">
                        <option value="">[TODAS]</option>
                       <?
        $sql   = "SELECT sector.idsector, sector.codigo, sector.denominacion FROM sector ORDER BY sector.codigo";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
              <td align="right">Desde: </td>
              <td>
                <input type="text" name="desde" id="desde" size="15" maxlength="10" readonly="readonly" />
                <img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                    Calendar.setup({
                    inputField    : "desde",
                    button        : "f_trigger_a",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                    });
                </script>
              <td align="right">Hasta: </td>
              <td>
                <input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly="readonly" />
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
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_sector('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr>
            	<td>
                	<input type="checkbox" id="resumido" /> Resumido (Agrupado por Sector)
                    <input type="checkbox" name="chkrestar" id="chkrestar" style="display:none;" />
				</td>
			</tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkreservado" checked="checked" /> &nbsp; Reservado para Disminuir <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" style="display:none;" />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Detalle por Partida...
    case "detalle_por_partida":
        ?>
		<center>
		<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar el Detalle por Partida</div>
		<br /><br />
		<form name="frmentrada" id="frmentrada">

		<table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
              <td>
                <?
        $sql   = "SELECT u.denominacion FROM unidad_ejecutora u INNER JOIN categoria_programatica c ON (u.idunidad_ejecutora = c.idunidad_ejecutora) WHERE c.idcategoria_programatica = '" . $_SESSION["s_categoria_programatica"] . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query)) {
            $field = mysql_fetch_array($query);
        }

        ?>
                <input type="hidden" name="idcategoria" id="idcategoria" value="<?=$_SESSION["s_categoria_programatica"]?>" />
                <input type="text" name="categoria" id="categoria" style="width:400px" value="<?=$field['denominacion']?>" readonly="readonly" />
               <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_partida(); limpiar_ordinal(); window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
            </td>
            </tr>
            <tr>
                <td align="right">Partidas: </td>
                <td>
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_ordinal(); validar_categorias();" />
                </td>
            </tr>
            <tr>
                <td align="right">Ordinal: </td>
                <td>
                    <input type="hidden" name="idordinal" id="idordinal" />
                    <input type="text" name="codordinal" id="codordinal" size="6" readonly="readonly" />
                    <input type="text" name="nomordinal" id="nomordinal" style="width:335px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="validar_ordinal();" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                    <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                    <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_presupuesto_detalle_por_partida('<?=$nombre?>');" /></td>
            </tr>
		</table><br />

		<table width="500">
			<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
			<tr><td><hr size="1" width="500" /></td></tr>
			<tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
			<tr>
				<td>
					<input type="checkbox" name="chkjustificacionoc" id="chkjustificacionoc" /> &nbsp; Mostrar Justificaci&oacute;n Compromisos <br />
					<input type="checkbox" name="chkjustificacionop" id="chkjustificacionop" /> &nbsp; Mostrar Justificaci&oacute;n Pagos <br />
					<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
					<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
					<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
					<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
					<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
					<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
					<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
					<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
					<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
					<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

				</td>
			</tr>
		</table>
        </form>
        </center>
		<?
        break;

    //    Ejecucion Detallada...
    case "ejecucion_detallada":
        ?>
        <form name="frmentradadet" id="frmentradadet">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar la Ejecuci&oacute;n Detallada</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria" id="categoria" style="width:350px;">
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Estado: </td>
                <td>
                    <select name="estado" id="estado" style="width:150px;">
                        <option value="">[TODAS]</option>
                        <option value="procesadas/pagadas">Procesadas/Pagadas</option>
                        <option value="procesadas">Procesadas</option>
                        <option value="pagadas">Pagadas</option>
                        <option value="anuladas">Anuladas</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_ejecucion_detallada('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
					<input type="checkbox" name="chkjustificacionoc" id="chkjustificacionoc" /> &nbsp; Mostrar Justificaci&oacute;n Compromisos <br />
					<input type="checkbox" name="chkjustificacionop" id="chkjustificacionop" /> &nbsp; Mostrar Justificaci&oacute;n Pagos <br />
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />
                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Disponibilidad Presupuestaria...
    case "disponibilidad_presupuestaria":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Disponibilidad Presupuestaria</div>
        <br /><br />
        <form name="frmentrada">

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
              <td>
                <?
        $sql   = "SELECT u.denominacion FROM unidad_ejecutora u INNER JOIN categoria_programatica c ON (u.idunidad_ejecutora = c.idunidad_ejecutora) WHERE c.idcategoria_programatica = '" . $_SESSION["s_categoria_programatica"] . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query)) {
            $field = mysql_fetch_array($query);
        }

        ?>
                <input type="hidden" name="idcategoria" id="idcategoria" value="" />
                <input type="text" name="categoria" id="categoria" style="width:400px" value="" readonly="readonly" />
               <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_partida(); limpiar_ordinal(); window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
        </td>
            </tr>
            <tr>
                <td align="right">Partidas: </td>
                <td>
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_ordinal(); validar_categorias();" />
                </td>
            </tr>
            <tr>
                <td align="right">Ordinal: </td>
                <td>
                    <input type="hidden" name="idordinal" id="idordinal" />
                    <input type="text" name="codordinal" id="codordinal" size="6" readonly="readonly" />
                    <input type="text" name="nomordinal" id="nomordinal" style="width:335px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="validar_ordinal();" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                    <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                    <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_disponibilidad_presupuestaria('<?=$nombre?>');" /></td>
            </tr>
          </table>
          </form>
        </center>
        <?
        break;

    /*    Disponibilidad Presupuestaria por Periodo... */
    case "disponibilidad_presupuestaria_periodo":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Disponibilidad Presupuestaria por Per&iacute;odo</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="550" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
              <td colspan="3">
                <?
        $sql   = "SELECT u.denominacion FROM unidad_ejecutora u INNER JOIN categoria_programatica c ON (u.idunidad_ejecutora = c.idunidad_ejecutora) WHERE c.idcategoria_programatica = '" . $_SESSION["s_categoria_programatica"] . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query)) {
            $field = mysql_fetch_array($query);
        }

        ?>
                <input type="hidden" name="idcategoria" id="idcategoria" value="" />
                <input type="text" name="categoria" id="categoria" style="width:400px" value="" readonly="readonly" />
               <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_partida(); limpiar_ordinal(); window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
        </td>
            </tr>
            <tr>
                <td align="right">Partidas: </td>
                <td colspan="3">
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_ordinal(); validar_categorias();" />
                </td>
            </tr>
            <tr>
                <td align="right">Ordinal: </td>
                <td colspan="3">
                    <input type="hidden" name="idordinal" id="idordinal" />
                    <input type="text" name="codordinal" id="codordinal" size="6" readonly="readonly" />
                    <input type="text" name="nomordinal" id="nomordinal" style="width:335px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="validar_ordinal();" />
                </td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
              <td align="right">Desde: </td>
              <td>
                <input type="text" name="desde" id="desde" size="15" maxlength="10" readonly="readonly" />
                <img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                    Calendar.setup({
                    inputField    : "desde",
                    button        : "f_trigger_a",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                    });
                </script>
              <td align="right">Hasta: </td>
              <td>
                <input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly="readonly" />
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
            	<td colspan="3" align="center">
                	<input type="radio" name="tipo_reporte" id="detallado" value="detallado" checked="checked" /> Detallado
                	<input type="radio" name="tipo_reporte" id="resumido" value="resumido" /> Resumido
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_disponibilidad_presupuestaria_periodo('<?=$nombre?>');" /></td>
            </tr>
          </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Resumen por Actividades...
    case "resumen_actividades":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Resumen Estad&iacute;stico por Actividades</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria" id="categoria" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_preres('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>

        </center>
        </form>
        <?
        break;

    //    Movimientos por Partidas...
    case "movimientos_por_partida":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar los Movimientos por Partida</div>
        <br /><br />
        <form name="frmentrada">

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
              <td>
                <?
        $sql   = "SELECT u.denominacion FROM unidad_ejecutora u INNER JOIN categoria_programatica c ON (u.idunidad_ejecutora = c.idunidad_ejecutora) WHERE c.idcategoria_programatica = '" . $_SESSION["s_categoria_programatica"] . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query)) {
            $field = mysql_fetch_array($query);
        }

        ?>
                <input type="hidden" name="idcategoria" id="idcategoria" value="<?=$_SESSION["s_categoria_programatica"]?>" />
                <input type="text" name="categoria" id="categoria" style="width:400px" value="<?=$field['denominacion']?>" readonly="readonly" />
               <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_partida(); limpiar_ordinal(); window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
        </td>
            </tr>
            <tr>
                <td align="right">Partidas: </td>
                <td>
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_ordinal(); validar_categorias();" />
                </td>
            </tr>
            <tr>
                <td align="right">Ordinal: </td>
                <td>
                    <input type="hidden" name="idordinal" id="idordinal" />
                    <input type="text" name="codordinal" id="codordinal" size="6" readonly="readonly" />
                    <input type="text" name="nomordinal" id="nomordinal" style="width:335px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="validar_ordinal();" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                    <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                    <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_detalle_por_partida_tipo_ordinal('<?echo $_GET['nombre']; ?>', document.getElementById('idcategoria').value, 'idcategoria', document.getElementById('idpartida').value, 'idpartida', document.getElementById('idordinal').value, 'idordinal');" /></td>
            </tr>
          </table>
          </form>
        </center>
        <?
        break;

    //    Movimientos por Categoria...
    case "movimientos_por_categoria":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Movimiento por Categor&iacute;a</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor" style="width:350px;">
                        <option value="0">.::Seleccione::.</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrarTipo('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, '<?php echo "idcategoria_programatica"; ?>')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Proyeccion Presupuestaria...
    case "proyeccion_presupuestaria":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar la Proyecci&oacute;n Presupuestaria </div>
        <br /><br />
        <?
        $imax       = $conf['anio_fiscal'] + 10;
        $mes_actual = date("m");
        ?>
        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="idcategoria_programatica" id="idcategoria_programatica" style="width:350px;">
                        <option value="0">[Todas]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Referencia: </td>
                <td>
                    <select name="referencia" id="referencia" style="width:150px;">
                        <option value="U">&Uacute;ltimo Pago</option>
                        <option value="P">Promedio</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Proyectado al mes: </td>
                <td>
                    <select name="mes" id="mes">
                        <?
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $m = "0$i";
            } else {
                $m = "$i";
            }

            if ($m > $mes_actual) {
                if ($m == "12") {
                    echo "<option value='$m' selected>" . $nom_mes[$m] . "</option>";
                } else {
                    echo "<option value='$m'>" . $nom_mes[$m] . "</option>";
                }

            }
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="verificarProyeccionPresupuestaria('<?=$nombre?>', 'presupuesto')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Simular Solicitud Presupuesto...
    case "simular_solicitud_presupuesto":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar la Simulaci&oacute;n de Solicitud de Presupuesto </div>
        <br /><br />
        <?
        $imax       = $conf['anio_fiscal'] + 10;
        $mes_actual = date("m");
        ?>
        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="idcategoria_programatica" id="idcategoria_programatica" style="width:350px;">
                        <option value="0">[Todas]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sobre: </td>
                <td>
                    <select name="sobre" id="sobre" style="width:150px;">
                        <option value="O">Monto Original</option>
                        <option value="A">Monto Actual</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>% Aplicar: </td>
                <td><input type="text" name="porcentaje" id="porcentaje" size="10" /></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="verificarSimularSolicitudPresupuesto('<?=$nombre?>', 'presupuesto')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Documentos por Partida...
    case "documentos_por_partida":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar los Documentos por Partida</div>
        <br /><br />
        <form name="frmentrada">

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
              <td>
                <?
        $sql   = "SELECT u.denominacion FROM unidad_ejecutora u INNER JOIN categoria_programatica c ON (u.idunidad_ejecutora = c.idunidad_ejecutora) WHERE c.idcategoria_programatica = '" . $_SESSION["s_categoria_programatica"] . "'";
        $query = mysql_query($sql) or die($sql . mysql_error());
        if (mysql_num_rows($query)) {
            $field = mysql_fetch_array($query);
        }

        ?>
                <input type="hidden" name="idcategoria" id="idcategoria" value="<?=$_SESSION["s_categoria_programatica"]?>" />
                <input type="text" name="categoria" id="categoria" style="width:400px" value="<?=$field['denominacion']?>" readonly="readonly" />
               <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_partida(); limpiar_ordinal(); window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
        </td>
            </tr>
            <tr>
                <td align="right">Partidas: </td>
                <td>
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="limpiar_ordinal(); validar_categorias();" />
                </td>
            </tr>
            <tr>
                <td align="right">Ordinal: </td>
                <td>
                    <input type="hidden" name="idordinal" id="idordinal" />
                    <input type="text" name="codordinal" id="codordinal" size="6" readonly="readonly" />
                    <input type="text" name="nomordinal" id="nomordinal" style="width:335px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="validar_ordinal();" />
                </td>
            </tr>
            <tr>
                <td align="right">Estado: </td>
                <td>
                    <select name="estado" id="estado" style="width:150px;">
                        <option value="0"></option>
                        <option value="procesado">Procesado</option>
                        <option value="pagado">Pagado</option>
                        <option value="anulado">Anulado</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_documento_por_partida_tipo('<?echo $_GET['nombre']; ?>', document.getElementById('idcategoria').value, 'idcategoria', document.getElementById('idpartida').value, 'idpartida', document.getElementById('estado').value, 'estado', document.getElementById('idordinal').value, 'idordinal');" /></td>
            </tr>
          </table>
          </form>
        </center>
        <?
        break;

    //    Resumen por Partida...
    case "resumen_por_partida":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Presupuesto por Partidas</div>
        <br /><br />

        <form name="frmentrada" id="frmentrada" method="post" action="generar_filtro.php">
        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Partidas: </td>
                <td>
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="validar_partidas();" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_resumen_por_partida('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Ejecucion Trimestral...
    case "ejecucion_trimestral":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar la Ejecuci&oacute;n Presupuestaria por Trimestre</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor" style="width:350px;">
                        <option value="0">[Todas]</option>
                        <option value="1">[Consolidado]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrarTipo('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, '<?php echo "idcategoria_programatica"; ?>')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Compromisos en Transito...
    case "compromisos_en_transito":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar los Compromisos en Tr&aacute;nsito</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td colspan="3">
                    <select name="categoria" id="categoria" style="width:350px;">
                        <option value="0">[Todas]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
              <td align="right">Desde: </td>
              <td>
                <input type="text" name="desde" id="desde" size="15" maxlength="10" readonly="readonly" />
                <img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                    Calendar.setup({
                    inputField    : "desde",
                    button        : "f_trigger_a",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                    });
                </script>
              <td align="right">Desde: </td>
              <td>
                <input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly="readonly" />
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
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_compromisos_en_transito('<?=$nombre?>')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Movimientos de Presupuesto...
    case "movimientos_presupuesto":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar los Movimientos de Presupuesto</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_movimientos_presupuesto('<?=$nombre?>')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Consolidado Agrupado...
    case "consolidado_agrupado":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Presupuesto Original</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Desde: </td>
                <td>
                    <select name="idcategoria_desde" id="idcategoria_desde" style="width:350px;">
                        <option value=""></option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Hasta: </td>
                <td>
                    <select name="idcategoria_hasta" id="idcategoria_hasta" style="width:350px;">
                        <option value=""></option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_agrupado('<?=$nombre?>')" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Reporte nuevo...
    case "rendicion_mensual":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
            	<td>Mes: </td>
            	<td>
                	<select name="mes" id="mes">
                    	<option value="01">Enero</option>
                    	<option value="02">Febrero</option>
                    	<option value="03">Marzo</option>
                    	<option value="04">Abril</option>
                    	<option value="05">Mayo</option>
                    	<option value="06">Junio</option>
                    	<option value="07">Julio</option>
                    	<option value="08">Agosto</option>
                    	<option value="09">Septiembre</option>
                    	<option value="10">Octubre</option>
                    	<option value="11">Noviembre</option>
                    	<option value="12">Diciembre</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria" id="categoria" style="width:350px;">
                        <option value="0">[Todas]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" checked="checked" /> Excel
                <input type="text" name="archivo" id="archivo" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_rendicion_mensual('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Consolidado por Partidas...
    case "consolidado_por_partida":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar Consolidado por Partida</div>
        <br /><br />

        <table>
            <tr>
                <td align="right">A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            if ($fin['idfuente_financiamiento'] == $conf['idfuente_financiamiento']) {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "' selected>" . $fin['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
           <tr>
              <td align="right">Desde: </td>
              <td>
                <input type="text" name="desde" id="desde" size="15" maxlength="10" readonly="readonly" />
                <img src="../../../imagenes/jscalendar0.gif" name="f_trigger_a" width="16" height="16" id="f_trigger_a" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                    Calendar.setup({
                    inputField    : "desde",
                    button        : "f_trigger_a",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                    });
                </script>
              <td align="right">Hasta: </td>
              <td>
                <input type="text" name="hasta" id="hasta" size="15" maxlength="10" readonly="readonly" />
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
                <td align="right">Partida: </td>
                <td colspan="3">
                    <input type="hidden" name="idpartida" id="idpartida" value="" />
                    <input type="text" name="partida" id="partida" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=partidas', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" onclick="enabledArchivo();" checked="checked" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_por_partida('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><input type="checkbox" name="chkrestar" id="chkrestar" /> &nbsp; Restar Pre - Compromiso</td></tr>
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkinicial" checked="checked" /> &nbsp; Asig. Inicial <br />
                	<input type="checkbox" name="chkcampos" id="chkaumentos" checked="checked" /> &nbsp; Aumentos <br />
                	<input type="checkbox" name="chkcampos" id="chkdisminuciones" checked="checked" /> &nbsp; Disminuci&oacute;n <br />
                	<input type="checkbox" name="chkcampos" id="chkmodificaciones" checked="checked" /> &nbsp; Modificaciones <br />
                	<input type="checkbox" name="chkcampos" id="chkajustada" checked="checked" /> &nbsp; Asig. Ajustada <br />
                	<input type="checkbox" name="chkcampos" id="chkprecompromiso" checked="checked" /> &nbsp; Pre-Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcompromiso" checked="checked" /> &nbsp; Compromiso <br />
                	<input type="checkbox" name="chkcampos" id="chkcausado" checked="checked" /> &nbsp; Causado <br />
                	<input type="checkbox" name="chkcampos" id="chkpagado" checked="checked" /> &nbsp; Pagado <br />
                	<input type="checkbox" name="chkcampos" id="chkdisponible" checked="checked" /> &nbsp; Disponible <br />

                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Ejecucion Mensual ONAPRE...
    case "mensual_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Presupuesto por Categor&iacute;as</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal_mo" id="anio_fiscal_mo" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto_mo" id="tipo_presupuesto_mo" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento_mo" id="financiamiento_mo" style="width:300px;">
                        <option value="" selected="selected">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Mes: </td>
                <td>
                    <select name="mes_mo" id="mes_mo" style="width:100px;">
                       <option value='01'>Enero</option>
                       <option value='02'>Febrero</option>
                       <option value='03'>Marzo</option>
                       <option value='04'>Abril</option>
                       <option value='05'>Mayo</option>
                       <option value='06'>Junio</option>
                       <option value='07'>Julio</option>
                       <option value='08'>Agosto</option>
                       <option value='09'>Septiembre</option>
                       <option value='10'>Octubre</option>
                       <option value='11'>Noviembre</option>
                       <option value='12'>Diciembre</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria_mo" id="categoria_mo" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_mensual_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        </center>
        </form>
        <?
        break;

    //    Ejecucion TRIMESTRAL ONAPRE...
    case "trimestral_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Reporte TRIMESTRAL ONAPRE</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal_mo" id="anio_fiscal_mo" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto_mo" id="tipo_presupuesto_mo" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento_mo" id="financiamiento_mo" style="width:300px;">
                        <option value="" selected="selected">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Trimestre: </td>
                <td>
                    <select name="trimestre_mo" id="trimestre_mo" style="width:140px;">
                       <option value='01'>TRIMESTRE I</option>
                       <option value='02'>TRIMESTRE II</option>
                       <option value='03'>TRIMESTRE III</option>
                       <option value='04'>TRIMESTRE IV</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria_mo" id="categoria_mo" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_trimestral_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        </center>
        </form>
        <?
        break;

    //    Ejecucion ANUAL ONAPRE...
    case "anual_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Reporte TRIMESTRAL ONAPRE</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td>
                    <select name="anio_fiscal_mo" id="anio_fiscal_mo" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td>
                    <select name="tipo_presupuesto_mo" id="tipo_presupuesto_mo" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td>
                    <select name="financiamiento_mo" id="financiamiento_mo" style="width:300px;">
                        <option value="" selected="selected">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>

            <tr><td colspan="2"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Categor&iacute;a Program&aacute;tica: </td>
                <td>
                    <select name="categoria_mo" id="categoria_mo" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_anual_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        </center>
        </form>
        <?
        break;
// FIN ANUAL ONAPRE

//    consolidado general onapre...
    case "consolidado_general_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Consolidado General</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                     <select name="financiamiento_mo" id="financiamiento_mo" style="width:300px;">
                        <option value="" selected="selected">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>

            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>

            <tr>
                <td>Trimestre: </td>
                <td>
                    <select name="trimestre_mo" id="trimestre_mo" style="width:140px;">
                       <option value='00'>AÑO COMPLETO</option>
                       <option value='01'>TRIMESTRE I</option>
                       <option value='02'>TRIMESTRE II</option>
                       <option value='03'>TRIMESTRE III</option>
                       <option value='04'>TRIMESTRE IV</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>

            <tr>
                <td align="center" colspan="4">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_general_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table><br />


        </center>
        </form>
        <?
        break;
// FIN CONSOLIDADO GENERAL ONAPRE

//    Consolidado por Sector ONAPRE...
    case "consolidado_sector_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar el Consolidado por Sector</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                   <select name="anio_fiscal_mo" id="anio_fiscal_mo" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto_mo" id="tipo_presupuesto_mo" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento_mo" id="financiamiento_mo" style="width:300px;">
                        <option value="" selected="selected">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Sector: </td>
                <td colspan="3">
                    <select name="sector_mo" id="sector_mo" style="width:350px;">
                        <option value="">[TODAS]</option>
                       <?
        $sql   = "SELECT sector.idsector, sector.codigo, sector.denominacion FROM sector ORDER BY sector.codigo";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td>Trimestre: </td>
                <td>
                    <select name="trimestre_mo" id="trimestre_mo" style="width:140px;">
                       <option value='00'>AÑO COMPLETO</option>
                       <option value='01'>TRIMESTRE I</option>
                       <option value='02'>TRIMESTRE II</option>
                       <option value='03'>TRIMESTRE III</option>
                       <option value='04'>TRIMESTRE IV</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>

            <tr>
                <td align="center" colspan="4">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_sector_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table><br />


        </center>
        </form>
        <?
        break;
    //FIN CONSOLIDADO POR SECTOR ONAPRE

//    Consolidado por Programa ONAPRE...
    case "consolidado_programa_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar el Consolidado por Sector</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                   <select name="anio_fiscal_mo" id="anio_fiscal_mo" style="width:300px;" disabled="disabled">
                        <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto_mo" id="tipo_presupuesto_mo" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento_mo" id="financiamiento_mo" style="width:300px;">
                        <option value="" selected="selected">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Sector: </td>
                <td colspan="3">
                    <select name="sector_mo" id="sector_mo" style="width:350px;" onChange="cargaContenido(this.id, 'listadoProgramas')" class="Select1">
                        <option value="">..:: Seleccione ::..</option>
                       <?
        $sql   = "SELECT sector.idsector, sector.codigo, sector.denominacion FROM sector ORDER BY sector.codigo";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Programa: </td>
                <td colspan="3">
                    <select name="programa" id="programa" disabled style="width:350px;" class="Select1">
                        <option value="0">Seleccione Sector</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Trimestre: </td>
                <td>
                    <select name="trimestre_mo" id="trimestre_mo" style="width:140px;">
                       <option value='00'>AÑO COMPLETO</option>
                       <option value='01'>TRIMESTRE I</option>
                       <option value='02'>TRIMESTRE II</option>
                       <option value='03'>TRIMESTRE III</option>
                       <option value='04'>TRIMESTRE IV</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>

            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_programa_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table><br />


        </center>
        </form>
        <?
        break;
    //FIN CONSOLIDADO POR PROGRAMA ONAPRE

    //    Consolidado por Categoria ONAPRE...
    case "consolidado_categoria_onapre":
        ?>
        <form name="frmentrada" id="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar el Consolidado por Categor&iacute;as ONAPRE</div>
        <br /><br />

        <table>
            <tr>
                <td>A&ntilde;o Fiscal: </td>
                <td colspan="3">
                    <select name="anio_fiscal" id="anio_fiscal" style="width:300px;" disabled="disabled">
                         <?
        anio_fiscal();
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo de Presupuesto: </td>
                <td colspan="3">
                    <select name="tipo_presupuesto" id="tipo_presupuesto" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql        = "SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
        $query_pres = mysql_query($sql) or die($sql . mysql_error());
        while ($pres = mysql_fetch_array($query_pres)) {
            if ($pres['idtipo_presupuesto'] == $conf['idtipo_presupuesto']) {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "' selected>" . $pres['denominacion'] . "</option>";
            } else {
                echo "<option value='" . $pres['idtipo_presupuesto'] . "'>" . $pres['denominacion'] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql       = "SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
        $query_fin = mysql_query($sql) or die($sql . mysql_error());
        while ($fin = mysql_fetch_array($query_fin)) {
            echo "<option value='" . $fin['idfuente_financiamiento'] . "'>" . $fin['denominacion'] . "</option>";
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Categor&iacute;a Program&aacute;tica: </td>
                <td colspan="3">
                    <select name="categoria" id="categoria" style="width:350px;">
                        <option value="">[TODAS]</option>
                        <?
        $sql = mysql_query("SELECT categoria_programatica.idcategoria_programatica, categoria_programatica.codigo, unidad_ejecutora.denominacion FROM categoria_programatica, unidad_ejecutora WHERE (categoria_programatica.idunidad_ejecutora=unidad_ejecutora.idunidad_ejecutora) ORDER BY categoria_programatica.codigo") or die($sql . mysql_error());
        while ($field = mysql_fetch_array($sql)) {
            if ($_SESSION["s_categoria_programatica"] == $field[0]) {
                echo "<option value='" . $field[0] . "' selected>" . $field[1] . " " . $field[2] . "</option>";
            } else {
                echo "<option value='" . $field[0] . "'>" . $field[1] . " " . $field[2] . "</option>";
            }

        }
        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td align="right">Trimestre: </td>
                <td>
                    <select name="trimestre_mo" id="trimestre_mo" style="width:140px;">
                       <option value='00'>AÑO COMPLETO</option>
                       <option value='01'>TRIMESTRE I</option>
                       <option value='02'>TRIMESTRE II</option>
                       <option value='03'>TRIMESTRE III</option>
                       <option value='04'>TRIMESTRE IV</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="500" /></td></tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_consolidado_categoria_onapre('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;
        //FIN CONSOLIDADO POR CATEGORIA ONAPRE

}

?>
</body>
</html>