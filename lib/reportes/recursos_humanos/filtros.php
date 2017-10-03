<?php
session_start();
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

function enabledArchivo() {
	if (document.getElementById('excel').checked) document.getElementById('archivo').disabled=false; else document.getElementById('archivo').disabled=true;

}

function cargaContenido(idSelectOrigen, listado) {
	// Obtengo la posicion que ocupa el select que debe ser cargado en el array declarado mas arriba
	if (listado=="listadoSNC") var posicionSelectDestino=buscarEnArray(listadoSNC, idSelectOrigen)+1;
	else if (listado=="listadoMovimientos") var posicionSelectDestino=buscarEnArray(listadoMovimientos, idSelectOrigen)+1;
	else if (listado=="listadoCuentas") var posicionSelectDestino=buscarEnArray(listadoCuentas, idSelectOrigen)+1;
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
		else if (listado=="listadoCatalogo") var num = listadoCatalogo.length;
		else if (listado=="listadoOrganizacion") var num = listadoOrganizacion.length;

		// Busco todos los selects siguientes al que inicio el evento onChange y les cambio el estado y deshabilito
		while(x <= num-1)
		{
			if (listado=="listadoSNC") selectActual=document.getElementById(listadoSNC[x]);
			else if (listado=="listadoMovimientos") selectActual=document.getElementById(listadoMovimientos[x]);
			else if (listado=="listadoCuentas") selectActual=document.getElementById(listadoCuentas[x]);
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
	else if((listado=="listadoSNC" && idSelectOrigen!=listadoSNC[listadoSNC.length-1]) || (listado=="listadoMovimientos" && idSelectOrigen!=listadoMovimientos[listadoMovimientos.length-1]) || (listado=="listadoCuentas" && idSelectOrigen!=listadoCuentas[listadoCuentas.length-1]) || (listado=="listadoCatalogo" && idSelectOrigen!=listadoCatalogo[listadoCatalogo.length-1]) || (listado=="listadoOrganizacion" && idSelectOrigen!=listadoOrganizacion[listadoOrganizacion.length-1]))
	{
		// Obtengo el elemento del select que debo cargar
		if (listado=="listadoSNC") var idSelectDestino=listadoSNC[posicionSelectDestino];
		else if (listado=="listadoMovimientos") var idSelectDestino=listadoMovimientos[posicionSelectDestino];
		else if (listado=="listadoCuentas") var idSelectDestino=listadoCuentas[posicionSelectDestino];
		else if (listado=="listadoCatalogo") var idSelectDestino=listadoCatalogo[posicionSelectDestino];
		else if (listado=="listadoOrganizacion") var idSelectDestino=listadoOrganizacion[posicionSelectDestino];
		var selectDestino=document.getElementById(idSelectDestino);
		// Creo el nuevo objeto AJAX y envio al servidor el ID del select a cargar y la opcion seleccionada del select origen
		var ajax=nuevoAjax();
		ajax.open("GET", "http://localhost/gestion/modulos/tablas_comunes/lib/select_dependientes_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada+"&listado="+listado, true);
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

function validar_lista_trabajadores() {//aqui...
	var nomina = document.getElementById("nomina").value;
	var idunidad = document.getElementById("idunidad").value;
	var idcentro = document.getElementById("idcentro").value;
	var idtrabajador = document.getElementById("idtrabajador").value;
	var idconcepto = document.getElementById("idconcepto").value;
	var tabla = document.getElementById("tabla").value;
	var ordenar = document.getElementById("ordenar").value;
	var estado = document.getElementById("estado").value;
	var rdonomina = document.getElementById("rdonomina").checked;
	var rdounidad = document.getElementById("rdounidad").checked;
	var rdocentro = document.getElementById("rdocentro").checked;
	var rdotrabajador = document.getElementById("rdotrabajador").checked;
	var rdoconcepto = document.getElementById("rdoconcepto").checked;
	var evalua_rango = document.getElementById("evalua_rango").value;
	var valor_evaluar = document.getElementById("valor_evaluar").value;
	var aumento_aplicar = document.getElementById("aumento_aplicar").value;
	var aumento_hasta = document.getElementById("aumento_hasta").value;
	var nomina_concepto = document.getElementById("nomina_concepto").value;

	if (rdonomina) var nombre = "trabajadores_por_tipo_de_nomina";
	else if (rdounidad) var nombre = "trabajadores_por_unidad_funcional";
	else if (rdocentro) var nombre = "trabajadores_por_centro_costos";
	//else if (rdotrabajador) var nombre = "listatrab";
	else if (rdoconcepto) var nombre = "trabajadores_por_concepto_constante";

	document.getElementById("divCargando").style.display = "block";
	location.href = "reportes.php?nombre="+nombre+"&nomina="+nomina+"&idunidad="+idunidad+"&idcentro="+idcentro+"&idtrabajador="+idtrabajador+"&rdonomina="+rdonomina+"&rdounidad="+rdounidad+"&rdocentro="+rdocentro+"&rdotrabajador="+rdotrabajador+"&ordenar="+ordenar+"&idconcepto="+idconcepto+"&tabla="+tabla+"&ordenarPor="+ordenar+"&estado="+estado+"&evalua_rango="+evalua_rango+"&valor_evaluar="+valor_evaluar+"&aumento_aplicar="+aumento_aplicar+"&aumento_hasta="+aumento_hasta+"&nomina_concepto="+nomina_concepto;
	document.getElementById("divCargando").style.display = "none";
}



function validar_lista_trabajadores_prestaciones(nombre) {//aqui...
	var form=document.getElementById("frmentrada");
	var nomina = document.getElementById("nomina").value;
	var ordenar = document.getElementById("ordenar").value;
	var estado = document.getElementById("estado").value;
	var mes_prestaciones = document.getElementById("mes_prestaciones").value;
	var anio_prestaciones = document.getElementById("anio_prestaciones").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();

	var chkprestaciones = 0;
	var chkintereses = 0;
	var chksumatoria = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkprestaciones" && form.elements[i].checked) chkprestaciones = 1;
		else if (form.elements[i].id == "chkintereses" && form.elements[i].checked) chkintereses = 1;
		else if (form.elements[i].id == "chksumatoria" && form.elements[i].checked) chksumatoria = 1;
	}
	var checks = chkprestaciones + "|"+ chkintereses + "|"+ chksumatoria;

	if (pdf.checked) {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre
								+"&nomina="+nomina
								+"&ordenar="+ordenar
								+"&estado="+estado
								+"&mes_prestaciones="+mes_prestaciones
								+"&anio_prestaciones="+anio_prestaciones
								+"&checks="+checks;
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
					location.href = "excel.php?nombre="+nombre
									+"&nomina="+nomina
									+"&ordenar="+ordenar
									+"&estado="+estado
									+"&mes_prestaciones="+mes_prestaciones
									+"&anio_prestaciones="+anio_prestaciones
									+"&checks="+checks
									+"&nombre_archivo="+archivo;

					document.getElementById("divCargando").style.display = "none";
				}
			}
			ajax.send("nombre="+nombre);
		}
	}

}


function trabajadores_por_tipo_de_nomina(nombre) {
	var nomina = document.getElementById("nomina").value;
	var ordenar = document.getElementById("ordenar").value;
	document.getElementById("divCargando").style.display = "block";
	location.href = "reportes.php?nombre="+nombre+"&nomina="+nomina+"&ordenar="+ordenar;
	document.getElementById("divCargando").style.display = "none";
}

function trabajadores_por_concepto_constante(nombre) {
	var idconcepto = document.getElementById("idconcepto").value;
	var ordenar = document.getElementById("ordenar").value;
	var tabla = document.getElementById("tabla").value;

	document.getElementById("divCargando").style.display = "block";
	location.href = "reportes.php?nombre="+nombre+"&tabla="+tabla+"&idconcepto="+idconcepto+"&ordenar="+ordenar;
	document.getElementById("divCargando").style.display = "none";
}

function trabajadores_por_unidad_funcional(nombre) {
	var idunidad = document.getElementById("idunidad").value;
	var ordenar = document.getElementById("ordenar").value;

	document.getElementById("divCargando").style.display = "block";
	location.href = "reportes.php?nombre="+nombre+"&idunidad="+idunidad+"&ordenar="+ordenar;
	document.getElementById("divCargando").style.display = "none";
}

function trabajadores_por_centro_costos(nombre) {
	var idcentro = document.getElementById("idcentro").value;
	var ordenar = document.getElementById("ordenar").value;

	document.getElementById("divCargando").style.display = "block";
	location.href = "reportes.php?nombre="+nombre+"&idcentro="+idcentro+"&ordenar="+ordenar;
	document.getElementById("divCargando").style.display = "none";
}

function trabajadores_carga_familiar(nombre) {
	var nomina = document.getElementById("nomina").value;
	var idunidad = document.getElementById("idunidad").value;
	var idcentro = document.getElementById("idcentro").value;
	var idtrabajador = document.getElementById("idtrabajador").value;
	var parentesco = document.getElementById("parentesco").value;
	var ordenar = document.getElementById("ordenar").value;
	var rdonomina = document.getElementById("rdonomina").checked;
	var rdounidad = document.getElementById("rdounidad").checked;
	var rdocentro = document.getElementById("rdocentro").checked;
	var rdotrabajador = document.getElementById("rdotrabajador").checked;

	document.getElementById("divCargando").style.display = "block";
	location.href = "reportes.php?nombre="+nombre+"&nomina="+nomina+"&idunidad="+idunidad+"&idcentro="+idcentro+"&idtrabajador="+idtrabajador+"&rdonomina="+rdonomina+"&rdounidad="+rdounidad+"&rdocentro="+rdocentro+"&rdotrabajador="+rdotrabajador+"&ordenar="+ordenar+"&parentesco="+parentesco;
	document.getElementById("divCargando").style.display = "none";
}

function trabajadores_ficha(nombre) {
	var nomina = document.getElementById("nomina").value;
	var idunidad = document.getElementById("idunidad").value;
	var idcentro = document.getElementById("idcentro").value;
	var idtrabajador = document.getElementById("idtrabajador").value;
	var ordenar = document.getElementById("ordenar").value;
	var rdonomina = document.getElementById("rdonomina").checked;
	var rdounidad = document.getElementById("rdounidad").checked;
	var rdocentro = document.getElementById("rdocentro").checked;
	var rdotrabajador = document.getElementById("rdotrabajador").checked;

	if (nomina == "" && idunidad == "" && idcentro == "" && idtrabajador == "") alert("¡ERROR: Debe seleccionar una opcion para filtrar los empleados!");
	else {
		document.getElementById("divCargando").style.display = "block";
		location.href = "reportes.php?nombre="+nombre+"&nomina="+nomina+"&idunidad="+idunidad+"&idcentro="+idcentro+"&idtrabajador="+idtrabajador+"&rdonomina="+rdonomina+"&rdounidad="+rdounidad+"&rdocentro="+rdocentro+"&rdotrabajador="+rdotrabajador+"&ordenar="+ordenar;
		document.getElementById("divCargando").style.display = "none";
	}
}

function validar_listatrab(nombre) {
	var form = document.getElementById("frmentrada");
	var ordenarPor = document.getElementById("ordenarPor").value;

	var chkcedula = 0;
	var chknombre = 0;
	var chkcentro = 0;
	var chkunidad = 0;
	for (i=0; i<form.length; i++) {
		if (form.elements[i].id == "chkcedula" && form.elements[i].checked) chkcedula = 1;
		else if (form.elements[i].id == "chknombre" && form.elements[i].checked) chknombre = 1;
		else if (form.elements[i].id == "chkcentro" && form.elements[i].checked) chkcentro = 1;
		else if (form.elements[i].id == "chkunidad" && form.elements[i].checked) chkunidad = 1;
	}
	var checks = chkcedula + "|"+ chknombre + "|"+ chkcentro + "|"+ chkunidad;

	location.href = "reportes.php?nombre="+nombre+"&checks="+checks+"&ordenarPor="+ordenarPor;
}

function trabajadores_listado(nombre) {
	var ordenarPor = document.getElementById("ordenarPor").value;
	var chkcuenta = document.getElementById("chkcuenta").checked;
	location.href = "reportes.php?nombre="+nombre+"&ordenar="+ordenarPor+"&chkcuenta="+chkcuenta;
}


function trabajadores_constancia(nombre) {
    var idconcepto = document.getElementById("idconcepto").value;
    var tabla = document.getElementById("tabla").value;
    var idtrabajador = document.getElementById("idtrabajador").value;

    var chkfingreso = document.getElementById("chkfingreso").checked;
    var chkcargo = document.getElementById("chkcargo").checked;
    var chkubicacion = document.getElementById("chkubicacion").checked;
    var chkcentro = document.getElementById("chkcentro").checked;
    var chkcuenta = document.getElementById("chkcuenta").checked;
    location.href = "reportes.php?nombre="+nombre+"&idconcepto="+idconcepto+"&tabla="+tabla+"&idtrabajador="+idtrabajador+"&chkfingreso="+chkfingreso+"&chkcargo="+chkcargo+"&chkubicacion="+chkubicacion+"&chkcentro="+chkcentro+"&chkcuenta="+chkcuenta;
}


function imprimirRtf(nombre, carpeta) {
    var idconcepto = document.getElementById("idconcepto").value;
    var tabla = document.getElementById("tabla").value;
    var idtrabajador = document.getElementById("idtrabajadorc").value;
	var nota_pie = document.getElementById("pie").value;
    var chkfingreso = document.getElementById("chkfingreso").checked;
    var chkcargo = document.getElementById("chkcargo").checked;
    var chkubicacion = document.getElementById("chkubicacion").checked;
    var chkcentro = document.getElementById("chkcentro").checked;
    var chkcuenta = document.getElementById("chkcuenta").checked;
    location.href="generar_rtf.php?nombre="+nombre+"&idconcepto="+idconcepto+"&tabla="+tabla+"&idtrabajador="+idtrabajador+"&chkfingreso="+chkfingreso+"&chkcargo="+chkcargo+"&chkubicacion="+chkubicacion+"&chkcentro="+chkcentro+"&chkcuenta="+chkcuenta+"&nota_pie="+nota_pie;
}

</script>
</head>
<body style="background-color:#CCCCCC;">
<div id="divCargando" style="background-color:#CCCCCC; width:250px; height:100px; position: absolute; left: 50%; top: 50%; margin-top: -100px; margin-left: -250px; border: 1px solid black; display:none"></div>

<?php
include "../../../conf/conex.php";
conectarse();

$sql        = "SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
$query_conf = mysql_query($sql) or die($sql . mysql_error());
$conf       = mysql_fetch_array($query_conf);

extract($_GET);
//    ---------------------------------------------
switch ($nombre) {
    //    Grupos...
    case "grupos":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Grupos de Cargo</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="idgrupo">C&oacute;digo</option>
                        <option value="denominacion">Denominaci&oacute;n</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, 'recursos_humanos')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Series...
    case "series":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar las Series de Cargo</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="series.idserie">C&oacute;digo</option>
                        <option value="series.denominacion">Denominaci&oacute;n</option>
                        <option value="grupos.denominacion">Grupo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, 'recursos_humanos')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Cargos...
    case "cargos":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Cargos</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="cargos.idcargo">C&oacute;digo</option>
                        <option value="cargos.denominacion">Denominaci&oacute;n</option>
                        <option value="cargos.grado">Grado</option>
                        <option value="series.denominacion">Serie</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, 'recursos_humanos')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Profesion...
    case "profesion":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar las Profesiones</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="denominacion">Denominaci&oacute;n</option>
                        <option value="abreviatura">Abreviatura</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarOrdenar('<?echo $_GET['nombre']; ?>', document.getElementById('ordenarPor').value, 'recursos_humanos')" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Lista de Trabajadores...
    case "listatrab":
        ?>
        <form id="frmentrada" name="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar la Lista de Trabajadores</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="cedula">C&eacute;dula</option>
                        <option value="nombres">Nombres</option>
                        <option value="apellidos">Apellidos</option>
                        <option value="nomcentro_costo">Centro de Costo</option>
                        <option value="nomunidad_funcional">Unidad Funcional</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_listatrab('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione de la lista los campos a mostrar</em></b></td></tr>
            <tr>
            	<td>
                	<input type="checkbox" name="chkcampos" id="chkcedula" checked="checked" onclick="this.checked=true;" /> &nbsp; C&eacute;dula <br />
                	<input type="checkbox" name="chkcampos" id="chknombre" checked="checked" onclick="this.checked=true;" /> &nbsp; Nombre y Apellido <br />
                	<input type="checkbox" name="chkcampos" id="chkcentro" /> &nbsp; Centro de Costo <br />
                	<input type="checkbox" name="chkcampos" id="chkunidad" /> &nbsp; Unidad Funcional <br />
                </td>
            </tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Tipos de Sociedad...
    case "tiposociedad":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar los Tipos de Sociedad</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="descripcion">Descripci&oacute;n</option>
                        <option value="siglas">Siglas</option>
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

    //    Trabajadores por Tipo de Nomina...
    case "trabajadores_por_tipo_de_nomina":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione el Tipo de Nomina para filtrar a los Trabajadores</div>
        <br /><br />
        <table>
            <tr>
                <td>Tipo de Nómina: </td>
                <td>
                    <select name="nomina" id="nomina" style="width:250px;">
                    	<option value="">:::. [Todos] .:::</option>
                    	<?
        $sql   = "SELECT * FROM tipo_nomina ORDER BY idtipo_nomina";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            ?><option value="<?=$field['idtipo_nomina']?>"><?=htmlentities($field['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="nro_ficha">Nro. de Ficha</option>
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    	<option value="fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_por_tipo_de_nomina('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </center>
        <?
        break;

    //    Trabajadores por Concepto/Constante...
    case "trabajadores_por_concepto_constante":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione el Concepto para filtrar a los Trabajadores</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td>Concepto: </td>
                <td>
                	<input type="hidden" name="tabla" id="tabla" value="" />
                	<input type="hidden" name="idconcepto" id="idconcepto" value="" />
                    <input type="text" name="nomconcepto" id="nomconcepto" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=conceptos_constantes', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="nro_ficha">Nro. de Ficha</option>
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    	<option value="fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_por_concepto_constante('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Trabajadores por Unidad Funcional...
    case "trabajadores_por_unidad_funcional":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la Unidad Funcional para filtrar a los Trabajadores</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td>Unidad Funcional: </td>
                <td>
                	<input type="hidden" name="idunidad" id="idunidad" value="" />
                    <input type="text" name="codunidad" id="codunidad" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomunidad" id="nomunidad" style="width:325px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=unidad_funcional', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="t.nro_ficha">Nro. de Ficha</option>
                    	<option value="length(t.cedula), t.cedula">Cedula</option>
                    	<option value="t.apellidos, t.nombres">Apellidos y Nombres</option>
                    	<option value="t.fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_por_unidad_funcional('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Trabajadores por Centro de Costos...
    case "trabajadores_por_centro_costos":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione el Centro de Costos para filtrar a los Trabajadores</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td>Centro de Costos: </td>
                <td>
                	<input type="hidden" name="idcentro" id="idcentro" value="" />
                    <input type="text" name="codcentro" id="codcentro" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomcentro" id="nomcentro" style="width:425px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=centro_costos', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="t.nro_ficha">Nro. de Ficha</option>
                    	<option value="length(t.cedula), t.cedula">Cedula</option>
                    	<option value="t.apellidos, t.nombres">Apellidos y Nombres</option>
                    	<option value="t.fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_por_centro_costos('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Carga Familiar de Trabajadores...
    case "trabajadores_carga_familiar":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar la Carga Familiar de los Trabajadores</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td>Tipo de Nómina: </td>
                <td>
                	<input name="rdo" id="rdonomina" type="radio" checked="checked" onclick="document.getElementById('nomina').disabled=false; document.getElementById('btUnidad').style.visibility='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value='';" />
                    <select name="nomina" id="nomina" style="width:250px;">
                    	<option value="">:::. [Todos] .:::</option>
                    	<?
        $sql   = "SELECT * FROM tipo_nomina ORDER BY idtipo_nomina";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            ?><option value="<?=$field['idtipo_nomina']?>"><?=htmlentities($field['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Unidad Funcional: </td>
                <td>
                	<input name="rdo" id="rdounidad" type="radio" onclick="document.getElementById('nomina').disabled=true; document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.visibility='visible'; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value='';" />
                	<input type="hidden" name="idunidad" id="idunidad" value="" />
                    <input type="text" name="codunidad" id="codunidad" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomunidad" id="nomunidad" style="width:375px" readonly="readonly" />
                    <img id="btUnidad" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=unidad_funcional', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Centro de Costos: </td>
                <td>
                	<input name="rdo" id="rdocentro" type="radio" onclick="document.getElementById('nomina').disabled=true; document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.visibility='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='visible'; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value='';" />
                	<input type="hidden" name="idcentro" id="idcentro" value="" />
                    <input type="text" name="codcentro" id="codcentro" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomcentro" id="nomcentro" style="width:375px" readonly="readonly" />
                    <img id="btCentro" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=centro_costos', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Trabajador: </td>
                <td>
                	<input name="rdo" id="rdotrabajador" type="radio" onclick="document.getElementById('nomina').disabled=true;  document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.divisibilitysplay='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='visible';" />
                    <input type="hidden" name="idtrabajador" id="idtrabajador" value="" />
                    <input type="text" name="nomtrabajador" id="nomtrabajador" style="width:275px" readonly="readonly" />
                    <img id="btTrabajador" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onClick="window.open('../../listas/lista_trabajador.php?frm=rep_carga_familiar', 'wLista','dependent=yes, height=600, width=900, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Parentesco: </td>
                <td>
                    <select name="parentesco" id="parentesco" style="width:250px;">
                    	<option value="">:::. Todos .:::</option>
                        <?
        $query_parentesco = mysql_query("SELECT * FROM parentezco ORDER BY idparentezco") or die(mysql_error());
        while ($field_parentescos = mysql_fetch_array($query_parentesco)) {
            ?>
                            <option value="<?=$field_parentescos['idparentezco']?>"><?=htmlentities($field_parentescos['denominacion'])?></option>
                            <?
        }
        ?>
                    </select>
                </td>
			</tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="nro_ficha">Nro. de Ficha</option>
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    	<option value="fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_carga_familiar('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Ficha del Trabajador...
    case "trabajadores_ficha":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar la Ficha del Trabajador</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td>Tipo de Nómina: </td>
                <td>
                	<input name="rdo" id="rdonomina" type="radio" checked="checked" onclick="document.getElementById('nomina').disabled=false; document.getElementById('btUnidad').style.visibility='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value='';" />
                    <select name="nomina" id="nomina" style="width:250px;">
                    	<option value="">:::. [Todos] .:::</option>
                    	<?
        $sql   = "SELECT * FROM tipo_nomina ORDER BY idtipo_nomina";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            ?><option value="<?=$field['idtipo_nomina']?>"><?=htmlentities($field['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Unidad Funcional: </td>
                <td>
                	<input name="rdo" id="rdounidad" type="radio" onclick="document.getElementById('nomina').disabled=true; document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.visibility='visible'; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value='';" />
                	<input type="hidden" name="idunidad" id="idunidad" value="" />
                    <input type="text" name="codunidad" id="codunidad" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomunidad" id="nomunidad" style="width:375px" readonly="readonly" />
                    <img id="btUnidad" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=unidad_funcional', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Centro de Costos: </td>
                <td>
                	<input name="rdo" id="rdocentro" type="radio" onclick="document.getElementById('nomina').disabled=true; document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.visibility='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='visible'; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value='';" />
                	<input type="hidden" name="idcentro" id="idcentro" value="" />
                    <input type="text" name="codcentro" id="codcentro" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomcentro" id="nomcentro" style="width:375px" readonly="readonly" />
                    <img id="btCentro" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=centro_costos', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Trabajador: </td>
                <td>
                	<input name="rdo" id="rdotrabajador" type="radio" onclick="document.getElementById('nomina').disabled=true;  document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.divisibilitysplay='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='visible';" />
                    <input type="hidden" name="idtrabajador" id="idtrabajador" value="" />
                    <input type="text" name="nomtrabajador" id="nomtrabajador" style="width:275px" readonly="readonly" />
                    <img id="btTrabajador" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onClick="window.open('../../listas/lista.php?lista=trabajadores', 'wLista','dependent=yes, height=600, width=900, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="nro_ficha">Nro. de Ficha</option>
                    	<option value="length(cedula), cedula">Cedula</option>
                    	<option value="apellidos, nombres">Apellidos y Nombres</option>
                    	<option value="fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_ficha('<?=$nombre?>');" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

    //    Lista de Trabajadores...
    case "lista_trabajadores":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar la Ficha del Trabajador</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table width="90%">
            <tr>
                <td width="30%">Tipo de Nómina: </td>
                <td width="70%">
                	<input name="rdo" id="rdonomina" type="radio" checked="checked" onclick="document.getElementById('nomina').disabled=false; document.getElementById('btUnidad').style.visibility='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value=''; document.getElementById('btConcepto').style.visibility='hidden'; document.getElementById('idconcepto').value=''; document.getElementById('nomconcepto').value=''; document.getElementById('tabla').value=''; document.getElementById('fila_rango').style.display='none';" />
                    <select name="nomina" id="nomina" style="width:250px;">
                    	<option value="">:::. [Todos] .:::</option>
                    	<?
        $sql   = "SELECT * FROM tipo_nomina ORDER BY idtipo_nomina";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            ?><option value="<?=$field['idtipo_nomina']?>"><?=htmlentities($field['titulo_nomina'])?></option><?
        }
        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Unidad Funcional: </td>
                <td>
                	<input name="rdo" id="rdounidad" type="radio" onclick="document.getElementById('nomina').disabled=true; document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.visibility='visible'; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value=''; document.getElementById('btConcepto').style.visibility='hidden'; document.getElementById('idconcepto').value=''; document.getElementById('nomconcepto').value=''; document.getElementById('tabla').value='';document.getElementById('fila_rango').style.display='none';" />
                	<input type="hidden" name="idunidad" id="idunidad" value="" />
                    <input type="text" name="codunidad" id="codunidad" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomunidad" id="nomunidad" style="width:375px" readonly="readonly" />
                    <img id="btUnidad" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=unidad_funcional', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Centro de Costos: </td>
                <td>
                	<input name="rdo" id="rdocentro" type="radio" onclick="document.getElementById('nomina').disabled=true; document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.visibility='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='visible'; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value=''; document.getElementById('btConcepto').style.visibility='hidden'; document.getElementById('idconcepto').value=''; document.getElementById('nomconcepto').value=''; document.getElementById('tabla').value='';document.getElementById('fila_rango').style.display='none';" />
                	<input type="hidden" name="idcentro" id="idcentro" value="" />
                    <input type="text" name="codcentro" id="codcentro" style="width:75px" readonly="readonly" />
                    <input type="text" name="nomcentro" id="nomcentro" style="width:375px" readonly="readonly" />
                    <img id="btCentro" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=centro_costos', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr style="display:none">
                <td>Trabajador: </td>
                <td>
                	<input name="rdo" id="rdotrabajador" type="radio" onclick="document.getElementById('nomina').disabled=true;  document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.divisibilitysplay='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='visible'; document.getElementById('btConcepto').style.visibility='hidden'; document.getElementById('idconcepto').value=''; document.getElementById('nomconcepto').value=''; document.getElementById('tabla').value=''; document.getElementById('fila_rango').style.display='none';" />
                    <input type="hidden" name="idtrabajador" id="idtrabajador" value="" />
                    <input type="text" name="nomtrabajador" id="nomtrabajador" style="width:275px" readonly="readonly" />
                    <img id="btTrabajador" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; visibility:hidden;" onClick="window.open('../../listas/lista.php?lista=trabajadores', 'wLista','dependent=yes, height=600, width=900, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            <tr>
                <td>Concepto: </td>
                <td>
                	<input name="rdo" id="rdoconcepto" type="radio" onclick="document.getElementById('nomina').disabled=true;  document.getElementById('nomina').value=''; document.getElementById('btUnidad').style.divisibilitysplay='hidden'; document.getElementById('idunidad').value=''; document.getElementById('codunidad').value=''; document.getElementById('nomunidad').value=''; document.getElementById('btCentro').style.visibility='hidden'; document.getElementById('idcentro').value=''; document.getElementById('codcentro').value=''; document.getElementById('nomcentro').value=''; document.getElementById('btTrabajador').style.visibility='hidden'; document.getElementById('idtrabajador').value=''; document.getElementById('nomtrabajador').value=''; document.getElementById('btConcepto').style.visibility='visible';document.getElementById('fila_rango').style.display='block';" />
                	<input type="hidden" name="tabla" id="tabla" value="" />
                	<input type="hidden" name="idconcepto" id="idconcepto" value="" />
                    <input type="text" name="nomconcepto" id="nomconcepto" style="width:400px" readonly="readonly" />
                    <img src="../../../imagenes/search0.png" id="btConcepto" title="Buscar" style="cursor:pointer; visibility:hidden;" onclick="window.open('../../listas/lista.php?lista=conceptos_constantes', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                </td>
            </tr>
            </table>

            <table width="100%" align="center" id="fila_rango" style="display:none">
                <tr >
                    <td width="30%">Rango: </td>
                    <td width="20%">
                        <select id="evalua_rango" disabled="disabled">
                            <option value="=">Igual a</option>
                            <option value=">=">Mayor o igual a</option>
                            <option value=">">Mayor a</option>
                            <option value="<=" selected >Menor o igual a</option>
                            <option value="<">Menor a</option>
                        </select>
                    </td>
                    <td width="20%">
                        <input type="text" name="valor_evaluar" id="valor_evaluar" value="" />
                    </td>
                    <td width="20%">
                        % a simular aumento:
                    </td>
                    <td width="10%">
                        <input type="text" name="aumento_aplicar" id="aumento_aplicar" value="" />
                    </td>
                    <td width="20%">
                        Aumentar hasta:
                    </td>
                    <td width="10%">
                        <input type="text" name="aumento_hasta" id="aumento_hasta" value="" />
                    </td>
                 </tr>
                 <tr>
                 	<td colspan="3" style="text-align:right">Tipo de Nomina:</td>
                    <td colspan="4">
                    	<select name="nomina_concepto" id="nomina_concepto" style="width:250px;">
                            <option value="">:::. [Todos] .:::</option>
                            <?
        $sql   = "SELECT * FROM tipo_nomina ORDER BY idtipo_nomina";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            ?><option value="<?=$field['idtipo_nomina']?>"><?=htmlentities($field['titulo_nomina'])?></option><?
        }
        ?>
                    	</select>
                    </td>
                 </tr>
            </table>
            <table>
            <tr>
                <td width="30%">Ordenar por: </td>
                <td width="70%">
                    <select name="ordenar" id="ordenar" style="width:250px;">
                    	<option value="t.nro_ficha">Nro. de Ficha</option>
                    	<option value="length(t.cedula), t.cedula">Cedula</option>
                    	<option value="t.apellidos, t.nombres">Apellidos y Nombres</option>
                    	<option value="t.fecha_ingreso">Fecha de Ingreso</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td>Estado: </td>
                <td>
                    <select name="estado" id="estado" style="width:250px;">
                    	<option value="todos">Todos</option>
                    	<option value="activos">Activos</option>
                    	<option value="egresados">Egresados</option>
                    </select>
                </td>
			</tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_lista_trabajadores();" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
        break;

//    Lista de Prestaciones de Trabajadores...
    case "lista_trabajadores_prestaciones":
        ?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione las opciones para filtrar el Listado de Trabajadores con Prestaciones</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
	        <table width="70%">
	            <tr>
	                <td width="30%" align='right'>Tipo de Nómina: </td>
	                <td width="70%">
	                    <select name="nomina" id="nomina" style="width:350px;">
	                    	<option value="">:::. [Todos] .:::</option>
	                    	<?
        $sql   = "SELECT * FROM tipo_nomina ORDER BY idtipo_nomina";
        $query = mysql_query($sql) or die($sql . mysql_error());
        while ($field = mysql_fetch_array($query)) {
            ?><option value="<?=$field['idtipo_nomina']?>"><?=htmlentities($field['titulo_nomina'])?></option><?
        }
        ?>
	                    </select>
	                </td>
	            </tr>
	            <tr>
	            	<td>&nbsp;</td>
	            	<td colspan='2' align='left'>
	                	<input type="checkbox" name="chkcampos" id="chkprestaciones" checked="checked" /> &nbsp; Prestaciones Sociales<br/>
	                	<input type="checkbox" name="chkcampos" id="chkintereses" checked="checked" /> &nbsp; Intereses sobre Prestaciones<br/>
	                	<input type="checkbox" name="chkcampos" id="chksumatoria" checked="checked" /> &nbsp; Sumatoria<br/>
	                </td>
	            </tr>
	            <tr>
	                <td width="30%" align='right'>Hasta el mes/año: </td>
	                <td width="70%">
	                	<select name="mes_prestaciones" id="mes_prestaciones" style="width:50px;">
	                    	<option value="01">01</option>
	                    	<option value="02">02</option>
	                    	<option value="03">03</option>
	                    	<option value="04">04</option>
	                    	<option value="05">05</option>
	                    	<option value="06">06</option>
	                    	<option value="07">07</option>
	                    	<option value="08">08</option>
	                    	<option value="09">09</option>
	                    	<option value="10">10</option>
	                    	<option value="11">11</option>
	                    	<option value="12">12</option>
	                    </select>
	                    <select name="anio_prestaciones" id="anio_prestaciones" style="width:100px;">
	                        <?
        for ($i = 1960; $i < 2040; $i++) {
            if ($i == $conf['anio_fiscal']) {
                echo "<option value='$i' selected>$i</option>";
            } else {
                echo "<option value='$i'>$i</option>";
            }

        }
        ?>
	                    </select>
	                </td>
	            </tr>
	        </table>
	        <br>
	        <table>
	            <tr>
	                <td width="30%">Ordenar por: </td>
	                <td width="70%">
	                    <select name="ordenar" id="ordenar" style="width:250px;">
	                    	<option value="t.nro_ficha">Nro. de Ficha</option>
	                    	<option value="length(t.cedula), t.cedula">Cedula</option>
	                    	<option value="t.apellidos, t.nombres">Apellidos y Nombres</option>
	                    	<option value="t.fecha_ingreso">Fecha de Ingreso</option>
	                    </select>
	                </td>
				</tr>
	            <tr>
	                <td>Estado: </td>
	                <td>
	                    <select name="estado" id="estado" style="width:250px;">
	                    	<option value="todos">Todos</option>
	                    	<option value="activos">Activos</option>
	                    	<option value="egresados">Egresados</option>
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
	                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte"
	                	onClick="validar_lista_trabajadores_prestaciones('lista_trabajadores_prestaciones');" /></td>
	            </tr>
	        </table>
        </form>
        </center>
        <?
        break;

    //    Lista de Trabajadores...
    case "trabajadores_listado":
        ?>
        <form id="frmentrada" name="frmentrada">
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione c&oacute;mo quiere ordenar la Lista de Trabajadores</div>
        <br /><br />
        <table>
            <tr>
                <td>Ordenar Por: </td>
                <td>
                    <select name="ordenarPor" id="ordenarPor">
                        <option value="cedula">C&eacute;dula</option>
                        <option value="nombres">Nombres</option>
                        <option value="apellidos">Apellidos</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="trabajadores_listado('<?=$nombre?>');" /></td>
            </tr>
        </table><br />

        <table width="500">
        	<tr><td><hr size="1" width="500" /></td></tr>
            <tr><td><b><em>Seleccione las condiciones para mostrar el listado</em></b></td></tr>
            <tr><td><input type="checkbox" id="chkcuenta" checked="checked" /> &nbsp; Mostrar Trabajadores con Cuentas Bancarias</td></tr>
        </table>
        </center>
        </form>
        <?
        break;

    //    Lista de Trabajadores...
    case "trabajadores_constancia":
        ?>
        <form id="frmentrada" name="frmentrada">
            <center>
                <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione Trabajador para Imprimir Constancia</div>
                <br />
                <table>
                    <tr>
                        <td>Trabajador: </td>
                        <td>
                            <input type="hidden" name="idtrabajadorc" id="idtrabajadorc" value="" />
                            <input type="text" name="nomtrabajador" id="nomtrabajador" style="width:400px" readonly="readonly" />
                            <img id="btTrabajador" src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer; " onClick="window.open('../../listas/lista_trabajador.php?frm=rep_constancia', 'wLista','dependent=yes, height=600, width=900, scrollbars=yes, top=150, left=200');" />
                        </td>
                    </tr>
                    <tr>
                        <td>Concepto: </td>
                        <td>
                            <input type="hidden" name="tabla" id="tabla" value="" />
                            <input type="hidden" name="idconcepto" id="idconcepto" value="" />
                            <input type="text" name="nomconcepto" id="nomconcepto" style="width:400px" readonly="readonly" />
                            <img src="../../../imagenes/search0.png" id="btConcepto" title="Buscar" style="cursor:pointer; " onclick="window.open('../../listas/lista.php?lista=conceptos_constantes', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
                        </td>
                    </tr>
                    <tr>
                        <td>Nota al pie: </td>
                        <td>
                            <textarea name="pie" cols="85" rows="5" id="pie"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Constancia" onClick="imprimirRtf('emitir_pagos_oficio', 'reportes/recursos_humanos')" /></td>
                    </tr>
                </table>


                <table width="500" style="display:none">
                    <tr><td><hr size="1" width="500" /></td></tr>
                    <tr><td><b><em>Seleccione las condiciones para mostrar el listado</em></b></td></tr>
                    <tr><td><input type="checkbox" id="chkfingreso"  /> &nbsp; Fecha de Ingreso</td></tr>
                    <tr><td><input type="checkbox" id="chkcargo"  /> &nbsp; Cargo</td></tr>
                    <tr><td><input type="checkbox" id="chkubicacion"  /> &nbsp; Ubicaci&oacute;n Funcional</td></tr>
                    <tr><td><input type="checkbox" id="chkcentro"  /> &nbsp; Centro de Costo</td></tr>
                    <tr><td><input type="checkbox" id="chkcuenta"  /> &nbsp; Cuentas Bancarias</td></tr>
                </table>
            </center>
        </form>
        <?
        break;

}

?>
</body>
</html>
