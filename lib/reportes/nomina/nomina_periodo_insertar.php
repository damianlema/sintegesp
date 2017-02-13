<?php
session_start();
extract($_POST);
extract($_GET);
//	---------------------------------------------
$lpn=array(
"nomina"=>"nomina",
"periodo"=>"periodo"
);
$_SESSION['listadoPeriodoNomina']=serialize($lpn);
//	---------------------------------------------
include("../../../conf/conex.php");
conectarse();
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

function insertarNomina() {
	var nomina = document.getElementById("nomina").value;
	var selectNomina = document.getElementById('nomina');
	var nomina_texto = selectNomina.options[selectNomina.selectedIndex].text;
	
	var periodo = document.getElementById("periodo").value;
	var selectPeriodo = document.getElementById('periodo');
	var periodo_texto = selectPeriodo.options[selectPeriodo.selectedIndex].text;
	
	if (nomina == "0" || periodo == "0") alert("¡Debe seleccionar la nómina y el periodo a insertar!");
	else {
		//	CREO UN OBJETO AJAX
		var ajax=nuevoAjax();
		ajax.open("POST", "insertarNomina.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("nomina="+nomina+"&periodo="+periodo+"&periodo_texto="+periodo_texto+"&nomina_texto="+nomina_texto);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4)	{
				var resp = ajax.responseText;
				if (opener.document.getElementById(nomina)) alert("¡No puede agregar dos veces la misma nomina!");
				else {
					opener.document.getElementById("listaNominas").innerHTML += resp;
					window.close();
				}
			}
		}
	}
}
</script>
</head>
<body>
<center>
<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Agregar nómina a procesar</div>
<br />

<div style="background-color:#CCCCCC; align:auto; width:500px; border:1px #000 solid;">
<table align="center">
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
                $sql = "SELECT
                            gn.idtipo_nomina,
                            tn.titulo_nomina
                        FROM
                            generar_nomina gn
                            INNER JOIN tipo_nomina tn ON (gn.idtipo_nomina = tn.idtipo_nomina)
                        GROUP BY idtipo_nomina";
                $query_nomina = mysql_query($sql) or die ($sql.mysql_error());
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
</table>
<center><input type="button" value="Insertar" onclick="insertarNomina();" /></center>
</div>
</body>
</html>