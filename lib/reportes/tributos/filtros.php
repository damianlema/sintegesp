<?php
session_start(); 
include "../../../funciones/funciones.php";
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

function llamarFiltrarRelacionRetenciones(nombre) {
	var idtipo_retencion=document.getElementById("idtipo").value;
	var desde=document.getElementById("desde").value;
	var hasta=document.getElementById("hasta").value;	
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var txt=document.getElementById("txt");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var todo=document.getElementById("todo");
	var comprobante=document.getElementById("comprobante");
	var sin_comprobante=document.getElementById("sin_comprobante");
	
	if(todo.checked){ imprimir = 'todo'; }
	if(comprobante.checked){ imprimir = 'comprobante'; }
	if(sin_comprobante.checked){ imprimir = 'sin_comprobante'; }
	//alert(imprimir);
	if (idtipo_retencion=="0") alert ("¡Debe seleccionar el tipo de retención a filtrar!");
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
					location.href = "reportes.php?nombre="+nombre+"&desde="+desde+"&hasta="+hasta+"&idtipo_retencion="+idtipo_retencion+"&imprimir="+imprimir;
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
						location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&desde="+desde+"&hasta="+hasta+"&idtipo_retencion="+idtipo_retencion+"&imprimir="+imprimir;
						document.getElementById("divCargando").style.display = "none";
					} 
				}
				ajax.send("nombre="+nombre);
			}
		}
	}
}

function llamarFiltrarRetencionesBeneficiario(nombre) {
	var idbeneficiario=document.getElementById("idbeneficiario").value;
	var idtipo_retencion=document.getElementById("idtipo").value;
	var estado=document.getElementById("estado").value;
	var desde=document.getElementById("desde").value;
	var hasta=document.getElementById("hasta").value;
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var txt=document.getElementById("txt");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	
	if (idbeneficiario=="") alert("¡Debe seleccionar el beneficiario!");
	else if (idtipo_retencion=="0") alert ("¡Debe seleccionar el tipo de retención a filtrar!");
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
					location.href = "reportes.php?nombre="+nombre+"&desde="+desde+"&hasta="+hasta+"&idtipo_retencion="+idtipo_retencion+"&idbeneficiario="+idbeneficiario+"&estado="+estado;
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
						location.href = "excel.php?nombre="+nombre+"&desde="+desde+"&hasta="+hasta+"&idtipo_retencion="+idtipo_retencion+"&idbeneficiario="+idbeneficiario+"&estado="+estado+"&nombre_archivo="+nombre;
						document.getElementById("divCargando").style.display = "none";
					} 
				}
				ajax.send("nombre="+nombre);
			}
		}
	}
}

function llamarFiltrar4(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3, valor4, filtro4) {
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4) {
			location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre);
}

function llamarFiltrar5(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3) {
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var txt=document.getElementById("txt");
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
					location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3;
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
						location.href = "excel.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&nombre_archivo="+archivo;
						document.getElementById("divCargando").style.display = "none";
					} 
				}
				ajax.send("nombre="+nombre);
			}
		}


/*
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4) {
			location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre);
	*/
}

function relacionRetenciones(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3, valor4, filtro4, valor5, filtro5, banco, cuenta) {	
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var txt=document.getElementById("txt");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	
	if (valor4=="0"){ 
		alert("¡Debe ingresar el tipo de retención!");
	}else{
		if (pdf.checked) {
			var ajax=nuevoAjax();
			ajax.open("POST", "reportes.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&"+filtro5+"="+valor5+"&banco="+banco+"&cuenta="+cuenta;
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
						location.href = "excel.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&"+filtro5+"="+valor5+"&banco="+banco+"&cuenta="+cuenta+"&nombre_archivo="+archivo;
						document.getElementById("divCargando").style.display = "none";
					} 
				}
				ajax.send("nombre="+nombre);
			}
		}
	}
}

function generarArchivoIVA(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3) {
	var excel=document.getElementById("excel");
	var txt=document.getElementById("txt");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
	else {
		if (txt.checked) {
			var ajax=nuevoAjax();
			ajax.open("POST", "txt.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "descargar_txt.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&nombre_archivo="+archivo+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3;
					document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("nombre="+nombre+"&"+filtro1+"="+valor1+"&nombre_archivo="+archivo+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3);
		}
		else if (excel.checked) {
			var ajax=nuevoAjax();
			ajax.open("POST", "excel.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');		
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4) {
					location.href = "excel.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&nombre_archivo="+archivo+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3;
					document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("nombre="+nombre);
		}
		
		
	}
}

function generarArchivoISLR(nombre, valor1, filtro1, valor2, filtro2) {
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
	else {
		location.href = "xml.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&nombre_archivo="+archivo+"&"+filtro2+"="+valor2;		
	}
}

function generarAnexoOrdenPago(nombre, valor1, filtro1, valor2, filtro2, pagina) {
	var nro_orden=valor1+"-"+valor2;
	if (valor2=="") alert("¡Debe ingresar el número de la orden!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&nro_orden="+nro_orden;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("nombre="+nombre);
	}
}

function enabledArchivo() {
	if (document.getElementById('txt').checked || document.getElementById('excel').checked) document.getElementById('archivo').disabled=false; else document.getElementById('archivo').disabled=true;
	
}

function emitirRetenciones(nombre, pagina, estado) {
	var id_emision_pago=document.getElementById("id_emision_pago").value;
	var origen="tributos_internos";
	if (id_emision_pago=="") alert("¡Debe ingresar el numero de la orden!");
	else {
		var ajax=nuevoAjax();
		ajax.open("POST", "reportes.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4) {
				location.href = "reportes.php?nombre="+nombre+"&id_emision_pago="+id_emision_pago+"&estado="+estado+"&origen="+origen+"";
				document.getElementById("divCargando").style.display = "none";
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
include("../../../conf/conex.php");
conectarse();
extract($_GET);
//	---------------------------------------------
switch ($nombre) {
	//	Relacion de Retenciones...
	case "relacion_retenciones":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar las Relaciones de Retenciones</div>
        <br /><br />
        <form name="frmentrada">
        <table>
            <tr>
              <td align="right">Tipo de Retenci&oacute;n: </td>
              <td colspan="3">
                <select name="idtipo" id="idtipo">
                    <option value="0"></option>
                    <?php
                        $sql="SELECT nombre_comprobante, descripcion FROM tipo_retencion GROUP BY nombre_comprobante ORDER BY descripcion";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
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
                <input type="radio" name="relacion" id="todo" value="todo" checked/> Todas
                <input type="radio" name="relacion" id="comprobante" value="comprobante"/> Con Comprobante
                <input type="radio" name="relacion" id="sin_comprobante" value="sin_comprobante"/> Sin Comprobante
                </td>
             <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="radio" name="tipo" id="txt" value="txt" onclick="enabledArchivo();" style="display:none;" />
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrarRelacionRetenciones('<? echo $_GET['nombre']; ?>')" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
		break;
	
	//	Relacion por Beneficiarios...
	case "retenciones_beneficiario":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar las Relaciones de Retenciones por Beneficiario</div>
        <br /><br />
        <form name="frmentrada">
        <table>
             <tr>
              <td align="right">Beneficiario: </td>
              <td colspan="3">
                <input type="hidden" name="idbeneficiario" id="idbeneficiario" value="" />
                <input type="text" name="beneficiario" id="beneficiario" style="width:400px" />            
                <img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=beneficiarios', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
              </td>
            </tr>
            <tr>
              <td align="right">Tipo de Retenci&oacute;n: </td>
              <td colspan="3">
                <select name="idtipo" id="idtipo">
                    <option value="0"></option>
                    <?php
                        $sql="SELECT nombre_comprobante, descripcion FROM tipo_retencion GROUP BY nombre_comprobante ORDER BY descripcion";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
                    ?>
                </select>
              </td>
            </tr>
            <tr>
              <td align="right">Estado de la Retenci&oacute;n: </td>
              <td colspan="3">
                <select name="estado" id="estado">
                    <option value="0"></option>
                    <option value="pagado">Pagado</option>
                    <option value="procesado">Procesado</option>
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
                <input type="radio" name="tipo" id="txt" value="txt" onclick="enabledArchivo();" style="display:none;" />
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrarRetencionesBeneficiario('<? echo $_GET['nombre']; ?>')" /></td>
            </tr>
        </table>
        </form>
        </center>
        <?
		break;
	
	//	Libro de Compras (I.V.A)...
	case "libro_compras_iva":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Libro de Compras I.V.A.</div>
        <br /><br />
        <?
        $anio=date("Y");
        $mes=date("m");
        $dia=date("d");
        ?>
        <table>
            <tr>
                <td>Per&iacute;odo: </td>
                <td>
                    <select name="anio" id="anio" disabled="disabled">
                        <?
						anio_fiscal();
						?>
                    </select> - 
                    <select name="mes" id="mes">
                        <?
                        for ($i=1; $i<=12; $i++) {
                            if ($i<10) $m="0$i"; else $m=$i;
                            if ($m==$mes) echo "<option value='$m' selected>$m</option>";
                            else  echo "<option value='$m'>$m</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Quincena: </td>
                <td>
                    <select name="quincena" id="quincena">
                        <?
                        if ($d<15) echo "<option value='1' selected>1era. Quincena</option>"; else echo "<option value='1'>1era. Quincena</option>";
                        if ($d>15) echo "<option value='2' selected>2da. Quincena</option>"; else echo "<option value='2'>2da. Quincena</option>";
						if ($d>15) echo "<option value='3' selected>Mensual</option>"; else echo "<option value='3'>Mensual</option>";
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
               <td>Estado</td>
               <td colspan="3">
               <select name="estado" id="estado">
               <option value="todos">[Todos]</option>
               <option value="procesado">Procesado</option>
               <option value="anulado">Anulado</option>
               </select>
              </td>
           </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrar4('<?=$_GET['nombre']?>', document.getElementById('anio').value, 'anio', document.getElementById('mes').value, 'mes', document.getElementById('quincena').value, 'quincena', document.getElementById('estado').value, 'estado')" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;


//	Libro Mensual (1x1000)...
	case "libro_compras_uno":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar el Libro de Compras 1x1000</div>
        <br /><br />
        <?
        $anio=date("Y");
        $mes=date("m");
        $dia=date("d");
        ?>
        <table>
            <tr>
                <td>Per&iacute;odo: </td>
                <td>
                    <select name="anio" id="anio" disabled="disabled">
                        <?
						anio_fiscal();
						?>
                    </select> - 
                    <select name="mes" id="mes">
                        <?
                        for ($i=1; $i<=12; $i++) {
                            if ($i<10) $m="0$i"; else $m=$i;
                            if ($m==$mes) echo "<option value='$m' selected>$m</option>";
                            else  echo "<option value='$m'>$m</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
               <td>Estado</td>
               <td colspan="3">
               <select name="estado" id="estado">
               <option value="todos">[Todos]</option>
               <option value="procesado">Procesado</option>
               <option value="anulado">Anulado</option>
               </select>
              </td>
           </tr>
           <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="radio" name="tipo" id="txt" value="txt" onclick="enabledArchivo();" style="display:none;" />
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrar5('<?=$_GET['nombre']?>', document.getElementById('anio').value, 'anio', document.getElementById('mes').value, 'mes', document.getElementById('estado').value, 'estado')" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;



	//	Retenciones Aplicadas...
	case "retenciones_aplicadas":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar la Relaci&oacute;n de Retenciones Aplicadas </div>
        <br /><br />
        <?
        $anio=date("Y");
        $mes=date("m");
        $dia=date("d");
        ?>
        <table>
            <tr>
              <td align="right">Tipo de Retenci&oacute;n: </td>
              <td colspan="3">
                <select name="idtipo" id="idtipo">
                    <option value="0"></option>
                    <?php
                        $sql="SELECT nombre_comprobante, descripcion FROM tipo_retencion GROUP BY nombre_comprobante ORDER BY descripcion";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
                    ?>
                </select>
              </td>
            </tr>
            <tr>
                <td>Per&iacute;odo: </td>
                <td>
                    <select name="anio" id="anio" disabled="disabled">
                        <?
						anio_fiscal();
						?>
                    </select> - 
                    <select name="mes" id="mes">
                        <?
                        for ($i=1; $i<=12; $i++) {
                            if ($i<10) $m="0$i"; else $m=$i;
                            if ($m==$mes) echo "<option value='$m' selected>$m</option>";
                            else  echo "<option value='$m'>$m</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Quincena: </td>
                <td>
                    <select name="quincena" id="quincena">
                        <?
                        if ($d<15) echo "<option value='1' selected>1era. Quincena</option>"; else echo "<option value='1'>1era. Quincena</option>";
                        if ($d>15) echo "<option value='2' selected>2da. Quincena</option>"; else echo "<option value='2'>2da. Quincena</option>";
                        
                        ?>
                        <option value='3'>Mensual</option>
                    </select>
                </td>
            </tr>
            <tr>
               <td>Estado</td>
               <td colspan="3">
               <select name="estado" id="estado">
               <option value="todos">[Todos]</option>
               <option value="procesado">Procesado</option>
               <option value="anulado">Anulado</option>
               </select>
              </td>
           </tr>
           <tr>
                <td>Banco: </td>
                <td colspan="3">
                    <select name="banco" id="banco" onChange="cargaContenido(this.id, 'listadoCuentas')" class="Select1">
                        <option value="0">Elige</option>
                        <?php
                        $query=mysql_query("SELECT idbanco, denominacion FROM banco ORDER BY denominacion");
                        while ($field=mysql_fetch_array($query)) {
                            echo "<option value='".$field['idbanco']."'>".$field['denominacion']."</option>";
                        }
                        ?>
                    </select>                                
                </td>
            </tr>
            <tr>
                <td>Cuenta: </td>
                <td colspan="3">
                    <select name="cuenta" id="cuenta" disabled class="Select1">
                        <option value="0">Selecciona Opci&oacute;n...</option>                                    
                    </select>
                </td>
            </tr>
             <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="radio" name="tipo" id="txt" value="txt" onclick="enabledArchivo();" style="display:none;" />
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="relacionRetenciones('<?=$_GET['nombre']?>', document.getElementById('anio').value, 'anio', document.getElementById('mes').value, 'mes', document.getElementById('quincena').value, 'quincena', document.getElementById('idtipo').value, 'idtipo', document.getElementById('estado').value, 'estado', document.getElementById('banco').value, document.getElementById('cuenta').value)" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;
	
	//	Generar Archivo I.V.A...
	case "generar_archivo_iva":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para Generar el Archivo I.V.A </div>
        <br /><br />
        <?
        $anio=date("Y");
        $mes=date("m");
        $dia=date("d");
        ?>
        <table>
            <tr>
                <td>Per&iacute;odo: </td>
                <td>
                    <select name="anio" id="anio" disabled="disabled">
                        <?
						anio_fiscal();
						?>
                    </select> - 
                    <select name="mes" id="mes">
                        <?
                        for ($i=1; $i<=12; $i++) {
                            if ($i<10) $m="0$i"; else $m=$i;
                            if ($m==$mes) echo "<option value='$m' selected>$m</option>";
                            else  echo "<option value='$m'>$m</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Quincena: </td>
                <td>
                    <select name="quincena" id="quincena">
                        <?
                        if ($d<15) echo "<option value='1' selected>1era. Quincena</option>"; else echo "<option value='1'>1era. Quincena</option>";
                        if ($d>15) echo "<option value='2' selected>2da. Quincena</option>"; else echo "<option value='2'>2da. Quincena</option>";
						if ($d>15) echo "<option value='3' selected>Mes Completo</option>"; else echo "<option value='3'>Mes Completo</option>";
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="txt" value="txt" checked /> TXT
                <input type="radio" name="tipo" id="excel" value="excel" /> Excel <input type="text" name="archivo" id="archivo" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="generarArchivoIVA('<?=$_GET['nombre']?>', document.getElementById('anio').value, 'anio', document.getElementById('mes').value, 'mes', document.getElementById('quincena').value, 'quincena')" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;
	
	//	Generar Archivo I.S.L.R...
	case "generar_archivo_islr":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para Generar el Archivo I.S.L.R </div>
        <br /><br />
        <?
        $anio=date("Y");
        $mes=date("m");
        $dia=date("d");
        ?>
        <table>
            <tr>
                <td>Per&iacute;odo: </td>
                <td>
                    <select name="anio" id="anio" disabled="disabled">
                        <?
						anio_fiscal();
						?>
                    </select> - 
                    <select name="mes" id="mes">
                        <?
                        for ($i=1; $i<=12; $i++) {
                            if ($i<10) $m="0$i"; else $m=$i;
                            if ($m==$mes) echo "<option value='$m' selected>$m</option>";
                            else  echo "<option value='$m'>$m</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nombre del Archivo: </td>
                <td><input type="text" name="archivo" id="archivo" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="generarArchivoISLR('<?=$_GET['nombre']?>', document.getElementById('anio').value, 'anio', document.getElementById('mes').value, 'mes')" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;
	
	//	Anexos Orden de Pago...
	case "anexos_orden_pago":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para Generar los Anexos de la Orden de Pago </div>
        <br /><br />
        <?
        $anio=date("Y");
        $mes=date("m");
        $dia=date("d");
        $sql="SELECT anio_fiscal FROM configuracion";
        $query_conf=mysql_query($sql) or die ($sql.mysql_error());
        $conf=mysql_fetch_array($query_conf);
        ?>
        <input type="hidden" name="prefijo" id="prefijo" value="<?="OP-".$conf['anio_fiscal']?>" />
        <table>
            <tr>
                <td>Nro. Orden: </td>
                <td><input type="text" name="nro_orden" id="nro_orden" size="10" /></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="generarAnexoOrdenPago('<?=$_GET['nombre']?>', document.getElementById('prefijo').value, 'prefijo', document.getElementById('nro_orden').value, 'nro_orden')" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;
	
	//	...
	case "emitir_retenciones":
		?>
		<center>
		<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar el Comprobante de Retenci&oacute;n</div>
		<br /><br />
		<form name="frmentrada">
		<table>
			 <tr>
			   <td align="right">Estado</td>
			   <td colspan="3">
			   <select name="estado" id="estado">
			   <option value="procesado">Procesado</option>
			   <option value="anulado">Anulado</option>
			   </select>
			  </td>
		   </tr>
			 <tr>
			  <td align="right">Orden de Pago: </td>
			  <td colspan="3">
				<input type="hidden" name="id_emision_pago" id="id_emision_pago" value="" />
				<input type="text" name="orden_pago" id="orden_pago" style="width:100px" readonly="readonly" /> 
				<input type="text" name="beneficiario" id="beneficiario" style="width:400px" readonly="readonly" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=emisiones_pagos', 'wLista','dependent=yes, height=600, width=1000, scrollbars=yes, top=50, left=50');" />          </td>
			</tr>
			<tr>
				<td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="emitirRetenciones('<? echo $_GET['nombre']; ?>', 'tributos', document.getElementById('estado').value)" /></td>
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