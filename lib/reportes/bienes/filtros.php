<?php
session_start();
//	---------------------------------------------
$ls=array(
"actividad"=>"actividad",
"familia"=>"familia",
"grupo"=>"grupo"
);
$_SESSION['listadoSNC']=serialize($ls);

$lm=array(
"tipo"=>"tipo",
"movimiento"=>"movimiento"
);
$_SESSION['listadoMovimientos']=serialize($lm);

$cta=array(
"banco"=>"banco",
"cuenta"=>"cuenta"
);
$_SESSION['listadoCuentas']=serialize($cta);

$lc=array(
"grupo"=>"banco",
"sub_grupo"=>"sub_grupo",
"seccion"=>"seccion"
);
$_SESSION['listadoCatalogo']=serialize($lc);

$lo=array(
"organizacion"=>"organizacion",
"nivel_organizacion"=>"nivel_organizacion"
);
$_SESSION['listadoOrganizacion']=serialize($lo);
//	---------------------------------------------
$nom_mes["01"]="Enero";
$nom_mes["02"]="Febrero";
$nom_mes["03"]="Marzo";
$nom_mes["04"]="Abril";
$nom_mes["05"]="Mayo";
$nom_mes["06"]="Junio";
$nom_mes["07"]="Julio";
$nom_mes["08"]="Agosto";
$nom_mes["09"]="Septiembre";
$nom_mes["10"]="Octubre";
$nom_mes["11"]="Noviembre";
$nom_mes["12"]="Diciembre";
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

function filtrarInmueblesInventario(nombre) {
	var organizacion = document.getElementById("organizacion").value;
	var catalogo = document.getElementById("catalogo").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			location.href = "reportes.php?nombre="+nombre+"&organizacion="+organizacion+"&catalogo="+catalogo;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre+"&organizacion="+organizacion+"&catalogo="+catalogo);
}

function filtrarInmueblesHoja(nombre) {
	var codigo = document.getElementById("codigo").value; codigo = codigo.trim();
	
	if (codigo == "") alert("¡Debe escribir el código del bien!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				location.href = "reportes.php?nombre="+nombre+"&codigo="+codigo;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("nombre="+nombre+"&codigo="+codigo);
	}
}

function filtrarMueblesInventario(nombre) {	
	var organizacion = document.getElementById("organizacion").value;
	var nivel_organizacion = document.getElementById("nivel_organizacion").value;
	if (document.getElementById("especificaciones").checked) var especificaciones = 1; else var especificaciones = 0;
	if (document.getElementById("codigo_anterior").checked) var codigo_anterior = 1; else codigo_anterior = 0;
	if (document.getElementById("seriales").checked) var seriales = 1; else seriales = 0;
	if (document.getElementById("codigo_catalogo").checked) var codigo_catalogo = 1; else var codigo_catalogo = 0;
	if (document.getElementById("descripcion_catalogo").checked) var descripcion_catalogo = 1; else descripcion_catalogo = 0;
	if (document.getElementById("costo").checked) var costo = 1; else var costo = 0;
	if (document.getElementById("fecha_compra").checked) var fecha_compra = 1; else fecha_compra = 0;
	if (document.getElementById("ubicacion").checked) var ubicacion = 1; else ubicacion = 0;
	if (document.getElementById("valor_actual").checked) var valor_actual = 1; else valor_actual = 0;
	if (document.getElementById("marca").checked) var marca = 1; else marca = 0;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			location.href = "reportes.php?nombre="+nombre+"&organizacion="+organizacion+"&nivel_organizacion="+nivel_organizacion+"&especificaciones="+especificaciones+"&codigo_anterior="+codigo_anterior+"&seriales="+seriales+"&codigo_catalogo="+codigo_catalogo+"&descripcion_catalogo="+descripcion_catalogo+"&costo="+costo+"&fecha_compra="+fecha_compra+"&ubicacion="+ubicacion+"&valor_actual="+valor_actual+"&marca="+marca;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre+"&organizacion="+organizacion+"&nivel_organizacion="+nivel_organizacion+"&especificaciones="+especificaciones+"&codigo_anterior="+codigo_anterior+"&seriales="+seriales+"&codigo_catalogo="+codigo_catalogo+"&descripcion_catalogo="+descripcion_catalogo+"&costo="+costo+"&fecha_compra="+fecha_compra+"&ubicacion="+ubicacion+"&valor_actual="+valor_actual+"&marca="+marca);
}

function filtrarMueblesCatalogo(nombre, pagina) {
	var catalogo = document.getElementById("catalogo").value;
	if (document.getElementById("especificaciones").checked) var especificaciones = 1; else var especificaciones = 0;
	if (document.getElementById("codigo_anterior").checked) var codigo_anterior = 1; else codigo_anterior = 0;
	if (document.getElementById("seriales").checked) var seriales = 1; else seriales = 0;
	if (document.getElementById("costo").checked) var costo = 1; else var costo = 0;
	if (document.getElementById("fecha_compra").checked) var fecha_compra = 1; else fecha_compra = 0;
	if (document.getElementById("ubicacion").checked) var ubicacion = 1; else ubicacion = 0;
	if (document.getElementById("valor_actual").checked) var valor_actual = 1; else valor_actual = 0;
	if (document.getElementById("marca").checked) var marca = 1; else marca = 0;
	
	
	if (catalogo == "0") alert("¡Debe seleccionar un catálogo!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				location.href = "reportes.php?nombre="+nombre+"&catalogo="+catalogo+"&especificaciones="+especificaciones+"&codigo_anterior="+codigo_anterior+"&seriales="+seriales+"&costo="+costo+"&fecha_compra="+fecha_compra+"&ubicacion="+ubicacion+"&valor_actual="+valor_actual+"&marca="+marca;
				
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("nombre="+nombre+"&catalogo="+catalogo+"&especificaciones="+especificaciones+"&codigo_anterior="+codigo_anterior+"&seriales="+seriales+"&costo="+costo+"&fecha_compra="+fecha_compra+"&ubicacion="+ubicacion+"&valor_actual="+valor_actual+"&marca="+marca);
	}
}

function filtrarbm1(nombre) {	
	var organizacion = document.getElementById("organizacion").value;
	var nivel_organizacion = document.getElementById("nivel_organizacion").value;
		
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			location.href = "reportes.php?nombre="+nombre+"&organizacion="+organizacion+"&nivel_organizacion="+nivel_organizacion;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre+"&organizacion="+organizacion+"&nivel_organizacion="+nivel_organizacion);
}



function filtrarCatalogoBienes(nombre, pagina) {
	var grupo = document.getElementById("grupo").value; grupo = grupo.trim();
	var sub_grupo = document.getElementById("sub_grupo").value; sub_grupo = sub_grupo.trim();
	var seccion = document.getElementById("seccion").value; seccion = seccion.trim();
	
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			location.href = "reportes.php?nombre="+nombre+"&grupo="+grupo+"&sub_grupo="+sub_grupo+"&seccion="+seccion;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre+"&grupo="+grupo+"&sub_grupo="+sub_grupo+"&seccion="+seccion);
}

function filtrarEstructuraOrganizativaBienes(nombre, pagina) {
	var organizacion = document.getElementById("organizacion").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			location.href = "reportes.php?nombre="+nombre+"&organizacion="+organizacion;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre+"&organizacion="+organizacion);
}

function acta_incorporacion_bienes(nombre, pagina) {
	var desde = document.getElementById("desde").value;
	var hasta = document.getElementById("hasta").value;
	var organizacion = document.getElementById("organizacion").value;
	var nivel_organizacion = document.getElementById("nivel_organizacion").value;
	
	if (desde == "" || hasta == "") alert ("¡Debe seleccionar el periodo a imprimir!");
	else if (organizacion == "0") alert ("¡Debe seleccionar la organización!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				location.href = "reportes.php?nombre="+nombre+"&desde="+desde+"&hasta="+hasta+"&organizacion="+organizacion+"&nivel_organizacion="+nivel_organizacion;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("nombre="+nombre+"&desde="+desde+"&hasta="+hasta+"&organizacion="+organizacion+"&nivel_organizacion="+nivel_organizacion);
	}
}
</script>
</head>
<body style="background-color:#CCCCCC;">
<div id="divCargando" style="background-color:#CCCCCC; width:250px; height:100px; position: absolute; left: 50%; top: 50%; margin-top: -100px; margin-left: -250px; border: 1px solid black; display:none"></div>

<?php
include("../../../conf/conex.php");
conectarse();
extract($_GET);
//	---------------------------------------------
switch ($nombre) {
	//	Inventario de Inmuebles...
	case "inmuebles_inventario":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Inventario de Inmuebles</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td width="175">C&oacute;digo de Cat&aacute;logo: </td>
                <td>
                    <select name="catalogo" id="catalogo" style="width:300px;">
                        <option value="0">.:: Seleccione ::.</option>
                        <?
                        $query = mysql_query("SELECT * FROM detalle_catalogo_bienes") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            if(substr($field["codigo"],0,1) == 1) {
                                ?> <option value="<?=$field["iddetalle_catalogo_bienes"]?>">(<?=$field["codigo"]?>) <?=$field["denominacion"]?></option> <?
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Organizaci&oacute;n: </td>
                <td>
                    <select name="organizacion" id="organizacion" style="width:300px;">
                        <option value="0">.:: Seleccione ::.</option>
                        <?
                        $query = mysql_query("SELECT * FROM organizacion") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field['idorganizacion']?>"><?=$field['denominacion']?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarInmueblesInventario('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Hoja de Trabajo 1...
	case "inmuebles_hoja1":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Hoja de Trabajo Nro. 1</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td width="125">C&oacute;digo del Bien: </td>
                <td><input type="text" name="codigo" id="codigo" size="30" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarInmueblesHoja('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Hoja de Trabajo 2...
	case "inmuebles_hoja2":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Hoja de Trabajo Nro. 2</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td width="125">C&oacute;digo del Bien: </td>
                <td><input type="text" name="codigo" id="codigo" size="30" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarInmueblesHoja('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Hoja de Trabajo 3...
	case "inmuebles_hoja3":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Hoja de Trabajo Nro. 3</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td width="125">C&oacute;digo del Bien: </td>
                <td><input type="text" name="codigo" id="codigo" size="30" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarInmueblesHoja('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Bienes Muebles Inventario...
	case "muebles_inventario":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Inventario de Muebles</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td>Organizaci&oacute;n: </td>
                <td colspan="4">
                    <select name="organizacion" id="organizacion" style="width:450px;" onChange="cargaContenido(this.id, 'listadoOrganizacion');">
                        <option value="0">Elige</option>
                        <?
                        $query = mysql_query("SELECT * FROM organizacion") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field['idorganizacion']?>"><?=$field['denominacion']?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nivel Organizacional: </td>
                <td colspan="4">
                    <select name="nivel_organizacion" id="nivel_organizacion" disabled style="width:450px;">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
            <tr>
                <td>Mostrar</td>
                <td>
                    <input type="checkbox" name="por" id="especificaciones" value="especificaciones" /> Especificaciones &nbsp; <br />
                    <input type="checkbox" name="por" id="codigo_anterior" value="codigo_anterior" /> C&oacute;digo anterior &nbsp; <br />
                    <input type="checkbox" name="por" id="seriales" value="seriales" /> Seriales &nbsp;
                </td>
                 <td>
                	<input type="checkbox" name="por" id="codigo_catalogo" value="codigo_catalogo" /> C&oacute;digo de Catalogo &nbsp; <br />					<input type="checkbox" name="por" id="descripcion_catalogo" value="descripcion_catalogo" /> Descripci&oacute;n Catalogo &nbsp; <br />
                    <input type="checkbox" name="por" id="marca" value="marca" /> Marca &nbsp;
                </td>
                 <td>
                 	<input type="checkbox" name="por" id="fecha_compra" value="fecha_compra" /> Fecha de Compra &nbsp; <br />
                	<input type="checkbox" name="por" id="costo" value="costo" /> Costo &nbsp; <br />
                    <input type="checkbox" name="por" id="valor_actual" value="valor_actual" /> Valor Actual &nbsp; <br />
                </td>
                <td>
                    <input type="checkbox" name="por" id="ubicacion" value="ubicacion" /> Ubicaci&oacute;n f&iacute;sica &nbsp; <br />
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" colspan="6"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarMueblesInventario('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Bienes Muebles Por Catalogo...
	case "muebles_por_catalogo":
	
	?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Inventario de Muebles</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td>C&oacute;digo de Cat&aacute;logo: </td>
                <td colspan="4">
                    <select name="catalogo" id="catalogo" style="width:300px;">
                        <option value="0">.:: Seleccione ::.</option>
                        <?
                        $query = mysql_query("SELECT * FROM detalle_catalogo_bienes") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            //if(substr($field["codigo"],0,1) == 1) {
                                ?> <option value="<?=$field["iddetalle_catalogo_bienes"]?>"><?=$field["denominacion"]?> (<?=$field["codigo"]?>) </option> 
								<?
                           // }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>Mostrar</td>
                <td>
                    <input type="checkbox" name="por" id="especificaciones" value="especificaciones" /> Especificaciones &nbsp; <br />
                    <input type="checkbox" name="por" id="codigo_anterior" value="codigo_anterior" /> C&oacute;digo anterior &nbsp; <br />
                    <input type="checkbox" name="por" id="seriales" value="seriales" /> Seriales &nbsp;
                </td>
                 <td>
                	<input type="checkbox" name="por" id="ubicacion" value="ubicacion" /> Ubicaci&oacute;n f&iacute;sica &nbsp; <br />
                    <input type="checkbox" name="por" id="marca" value="marca" /> Marca &nbsp;
                </td>
                 <td>
                 	<input type="checkbox" name="por" id="fecha_compra" value="fecha_compra" /> Fecha de Compra &nbsp; <br />
                	<input type="checkbox" name="por" id="costo" value="costo" /> Costo &nbsp; <br />
                    <input type="checkbox" name="por" id="valor_actual" value="valor_actual" /> Valor Actual &nbsp; <br />
                </td>
               
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" colspan="6"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarMueblesCatalogo('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
	
		break;
	
	
	//	FORMATO BM1...
	case "bm1":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar Inventario de Muebles</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td>Organizaci&oacute;n: </td>
                <td colspan="4">
                    <select name="organizacion" id="organizacion" style="width:450px;" onChange="cargaContenido(this.id, 'listadoOrganizacion');">
                        <option value="0">Elige</option>
                        <?
                        $query = mysql_query("SELECT * FROM organizacion") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field['idorganizacion']?>"><?=$field['denominacion']?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nivel Organizacional: </td>
                <td colspan="4">
                    <select name="nivel_organizacion" id="nivel_organizacion" disabled style="width:450px;">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" colspan="6"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarbm1('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	
	
	
	//	Catalogo...
	case "bienes_catalogo":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar el Catálogo de Bienes</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
                <td width="100">Grupo: </td>
                <td>
                    <select name="grupo" id="grupo" class="Select1" onChange="cargaContenido(this.id, 'listadoCatalogo');">
                         <option value="0">Elige</option>
                        <?
                        $query = mysql_query("SELECT * FROM grupo_catalogo_bienes ORDER BY codigo") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field["idgrupo_catalogo_bienes"]?>">(<?=$field["codigo"]?>) <?=$field["denominacion"]?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sub-Grupo: </td>
                <td>
                    <select name="sub_grupo" id="sub_grupo" disabled class="Select1">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
            <tr>
                <td>Secci&oacute;n: </td>
                <td>
                    <select name="seccion" id="seccion" disabled class="Select1">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarCatalogoBienes('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Estructura Organizativa...
	case "bienes_estructura_organizativa":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar la Estructura Organizativa</div>
        <br /><br />
        <form name="frmentrada" id="frmentrada">
        <table>
            <tr>
                <td width="100">Organizaci&oacute;: </td>
                <td>
                    <select name="organizacion" id="organizacion" class="Select1">
                         <option value="0">Elige</option>
                        <?
                        $query = mysql_query("SELECT * FROM organizacion ORDER BY codigo") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field["idorganizacion"]?>"><?=$field["codigo"]?> - <?=$field["denominacion"]?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="filtrarEstructuraOrganizativaBienes('<?=$nombre?>');" /></td>
            </tr>
        </table>
		</form>
        </center>
		<?
		break;
	
	//	Acta de Incorporacion...
	case "acta_incorporacion_bienes":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para imprimir el Acta de Incorporación de Bienes</div>
        <br /><br />
        <form name="frmentrada">
        <table>
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
                <td>Organizaci&oacute;n: </td>
                <td colspan="3">
                    <select name="organizacion" id="organizacion" style="width:450px;" onChange="cargaContenido(this.id, 'listadoOrganizacion');">
                        <option value="0">Elige</option>
                        <?
                        $query = mysql_query("SELECT * FROM organizacion") or die ($sql.mysql_error());
                        while ($field = mysql_fetch_array($query)) {
                            ?> <option value="<?=$field['idorganizacion']?>"><?=$field['denominacion']?></option> <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nivel Organizacional: </td>
                <td colspan="3">
                    <select name="nivel_organizacion" id="nivel_organizacion" disabled style="width:450px;">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="acta_incorporacion_bienes('<?=$nombre?>');" /></td>
            </tr>
        </table>
          </form>
        </center>
		<?
		break;
	
	//	Desincorporacion...
	case "desincorporacion":
		?>
        
		<?
		break;
	
}

?>
</body>
</html>