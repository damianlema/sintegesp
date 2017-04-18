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
<script src="function.js"></script>
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

function llamarFiltrar6(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3, valor4, filtro4, valor5, filtro5, valor6, filtro6) {
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4) {
			location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&"+filtro5+"="+valor5+"&"+filtro6+"="+valor6;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre);
}

function validar_filtro_orden_pago(nombre) {
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	alert("aqui");
	var idestado=document.getElementById("idestado").value;
	var idtipo=document.getElementById("idtipo").value;
	var hasta=document.getElementById("hasta").value;
	var desde=document.getElementById("desde").value;
	var idcategoria=document.getElementById("idcategoria").value;
	var idbeneficiario=document.getElementById("idbeneficiario").value;
	
	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&idestado="+idestado+"&idtipo="+idtipo+"&hasta="+hasta+"&desde="+desde+"&idcategoria="+idcategoria+"&idbeneficiario="+idbeneficiario;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&idestado="+idestado+"&idtipo="+idtipo+"&hasta="+hasta+"&desde="+desde+"&idcategoria="+idcategoria+"&idbeneficiario="+idbeneficiario;
		}
	}
}

function validar_filtro_orden_pagod(nombre) {
	var pdf=document.getElementById("pdf");
	var excel=document.getElementById("excel");
	var archivo=document.getElementById("archivo").value; archivo=archivo.trim();
	var idestado=document.getElementById("idestado").value;
	var idmodulo='';
	//document.getElementById("idmodulo").value;
	var idtipo=document.getElementById("idtipo").value;
	var hasta=document.getElementById("hasta").value;
	var desde=document.getElementById("desde").value;
	var idcategoria=document.getElementById("idcategoria").value;
	var idfuente_financiamiento=document.getElementById("financiamiento").value;
	var idbeneficiario=document.getElementById("idbeneficiario").value;
	if (document.getElementById("chkpartidas").checked){
		var chkpar = 'si';
	}else{
		var chkpar = 'no';
	}
	if (document.getElementById("chkconcepto").checked){
		var chkconcepto = 'si';
	}else{
		var chkconcepto = 'no';
	}
	if (document.getElementById("chkdocumentofinanciero").checked){
		var chkfinanciero = 'si';
	}else{
		var chkfinanciero = 'no';
	}
	if (document.getElementById("chksinafectacion").checked){
		var chksinafectacion = 'si';
	}else{
		var chksinafectacion = 'no';
	}
	if (document.getElementById("chkanticipo").checked){
		var chkanticipo = 'si';
	}else{
		var chkanticipo = 'no';
	}
	if (document.getElementById("chkerror").checked){
		var chkerror = 'si';
	}else{
		var chkerror = 'no';
	}
	if (pdf.checked) {
		location.href = "reportes.php?nombre="+nombre+"&idestado="+idestado+"&idmodulo="+idmodulo+"&idtipo="+idtipo+"&hasta="+hasta+"&desde="+desde
			+"&idcategoria="+idcategoria+"&idbeneficiario="+idbeneficiario+"&idfuente_financiamiento="+idfuente_financiamiento+"&chkpar="+chkpar
			+"&chkanticipo="+chkanticipo+"&chkconcepto="+chkconcepto+"&chkfinanciero="+chkfinanciero+"&chksinafectacion="+chksinafectacion+"&chkerror="+chkerror;
	}
	else if (excel.checked) {
		if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
		else {
			location.href = "excel.php?nombre="+nombre+"&idestado="+idestado+"&idmodulo="+idmodulo+"&idtipo="+idtipo+"&hasta="+hasta+"&desde="+desde
			+"&idcategoria="+idcategoria+"&idbeneficiario="+idbeneficiario+"&idfuente_financiamiento="+idfuente_financiamiento+"&chkpar="+chkpar
			+"&chkanticipo="+chkanticipo+"&chkconcepto="+chkconcepto+"&chkfinanciero="+chkfinanciero+"&chksinafectacion="+chksinafectacion+"&chkerror="+chkerror;
		}
	}
}

function enabledArchivo() {
	if (document.getElementById('excel').checked) document.getElementById('archivo').disabled=false; else document.getElementById('archivo').disabled=true;
	
}

function llamarFiltrar7(nombre, valor1, filtro1, valor2, filtro2, valor3, filtro3, valor4, filtro4, valor5, filtro5, valor6, filtro6, valor7, filtro7) {
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4) {
			location.href = "reportes.php?nombre="+nombre+"&"+filtro1+"="+valor1+"&"+filtro2+"="+valor2+"&"+filtro3+"="+valor3+"&"+filtro4+"="+valor4+"&"+filtro5+"="+valor5+"&"+filtro6+"="+valor6+"&"+filtro7+"="+valor7;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nombre="+nombre);
}

function validar_ordenes_pago_por_financiamiento(nombre) {
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var financiamiento = document.getElementById("financiamiento").value;
	var desde = document.getElementById("desde").value;
	var hasta = document.getElementById("hasta").value;
	var tipo = document.getElementById("tipo").value;
	var estado = document.getElementById("estado").value;
	var anio_fiscal = document.getElementById("anio_fiscal").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "reportes.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4) {
			location.href = "reportes.php?nombre="+nombre+"&anio_fiscal="+anio_fiscal+"&tipo_presupuesto="+tipo_presupuesto+"&financiamiento="+financiamiento+"&desde="+desde+"&hasta="+hasta+"&tipo="+tipo+"&estado="+estado;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send(null);
}

function validar_sobregiro_tributario(nombre) {
	var cantidad_ut = document.getElementById("cantidad_ut").value;
	var valor_ut = document.getElementById("valor_ut").value;
	
	var esNumero = new Number(cantidad_ut);
	if (isNaN(esNumero)) alert("¡Cantidad incorrecta: Debe ser un valor númerico!");
	else if (esNumero == 0) alert("¡Debe ingresar la cantidad de unidades tributarias!");
	else location.href = "reportes.php?nombre="+nombre+"&cantidad_ut="+cantidad_ut+"&valor_ut="+valor_ut;
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
	//	Solicitud de Cotizaciones...
	case "filtro_solicitud_cotizacion":
		?>
		<center>
		<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Filtro Solicitud de Cotizaciones</div>
		<br /><br />
		<form name="frmentrada">
		<table>
			<tr>
			  <td align="right">Proveedor: </td>
			  <td colspan="3">
				<input type="hidden" name="idbeneficiario" id="idbeneficiario" value="" />
				<input type="text" name="beneficiario" id="beneficiario" style="width:400px" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=beneficiarios', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
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
              </td>  
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
			  <td align="right">Tipo de Solicitud: </td>
			  <td colspan="3">
				<select name="idtipo" id="idtipo">
					<option value="0"></option>
					<?php
						$sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE modulo like '%-3-%' ORDER BY descripcion";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
					?>
				</select>
			  </td>
			</tr>
			<tr>
			  <td align="right">Estado de la Solicitud: </td>
			  <td colspan="3">
				<select name="idestado" id="idestado">
					<option value="0"></option>
					<option value="espera">En Espera</option>                
					<option value="procesado">Procesado</option>
					<option value="anulado">Anulado</option>                
					<option value="otorgado">Otorgado</option>
					<option value="finalizado">Finalizado</option>
					<option value="ordenado">Ordenado</option>
				</select>
			  </td>
			</tr>
			<tr>
			  <td align="right">Art&iacute;culo: </td>
			  <td colspan="3">
				<input type="hidden" name="idarticulo" id="idarticulo" value="" />
				<input type="text" name="articulo" id="articulo" style="width:400px" />          
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=articulos', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
			  </td>
			</tr>
			<tr>
				<td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrar6('<? echo $_GET['nombre']; ?>', document.getElementById('idbeneficiario').value, 'idbeneficiario', document.getElementById('idarticulo').value, 'idarticulo', document.getElementById('desde').value, 'desde', document.getElementById('hasta').value, 'hasta', document.getElementById('idtipo').value, 'idtipo', document.getElementById('idestado').value, 'idestado')" /></td>
			</tr>
		  </table>
		  </form>
		</center>
		<?
		break;
		
	//	Certificacion de Compromisos...
	case "filtro_compromisos":
		?>
		<center>
		<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar las Certificaciones de Compromisos</div>
		<br /><br />
		<form name="frmentrada">
		<table>
			<tr>
			  <td align="right">Proveedor: </td>
			  <td colspan="3">
				<input type="hidden" name="idbeneficiario" id="idbeneficiario" value="" />
				<input type="text" name="beneficiario" id="beneficiario" style="width:400px" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=beneficiarios', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
			  </td>
			</tr>
			<tr>
			  <td align="right">Categor&iacute;a Program&aacute;tica: </td>
			  <td colspan="3">
				<?
				$sql = "SELECT u.denominacion FROM unidad_ejecutora u INNER JOIN categoria_programatica c ON (u.idunidad_ejecutora = c.idunidad_ejecutora) WHERE c.idcategoria_programatica = '".$_SESSION["s_categoria_programatica"]."'";
				$query = mysql_query($sql) or die ($sql.mysql_error());
				if (mysql_num_rows($query)) $field = mysql_fetch_array($query);
				?>
				<input type="hidden" name="idcategoria" id="idcategoria" value="<?=$_SESSION["s_categoria_programatica"]?>" />
				<input type="text" name="categoria" id="categoria" style="width:400px" value="<?=$field['denominacion']?>" readonly="readonly" />
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
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
              </td>
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
			  <td align="right">Tipo de Solicitud: </td>
			  <td colspan="3">
				<select name="idtipo" id="idtipo">
					<option value="0"></option>
					<?php
						if ($_SESSION['modulo']=='1' || $_SESSION['modulo']=='13') $or="OR modulo like '%-13-%' OR modulo like '%-1-%'"; else $or="";
						$sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE modulo like '%-".$_SESSION['modulo']."-%' $or ORDER BY descripcion";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
					?>
				</select>
			  </td>
			</tr>
			<tr>
			  <td align="right">Estado de la Solicitud: </td>
			  <td colspan="3">
				<select name="idestado" id="idestado">
					<option value="0"></option>
					<option value="elaboracion">En Elaboraci&oacute;n</option>
					<option value="procesado">Procesado</option>
					<option value="conformado">Conformado</option>
					<option value="devuelto">Devuelto</option>
					<option value="anulado">Anulado</option>
					<option value="pagado">Pagado</option>
				</select>
			  </td>
			</tr>
			<tr>
			  <td align="right">Art&iacute;culo: </td>
			  <td colspan="3">
				<input type="hidden" name="idarticulo" id="idarticulo" value="" />
				<input type="text" name="articulo" id="articulo" style="width:400px" />          
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=articulos', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
			  </td>
			</tr>
			<tr>
				<td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="llamarFiltrar7('<? echo $_GET['nombre']; ?>', document.getElementById('idbeneficiario').value, 'idbeneficiario', document.getElementById('idcategoria').value, 'idcategoria', document.getElementById('idarticulo').value, 'idarticulo', document.getElementById('desde').value, 'desde', document.getElementById('hasta').value, 'hasta', document.getElementById('idtipo').value, 'idtipo', document.getElementById('idestado').value, 'idestado')" /></td>
			</tr>
		  </table>
		  </form>
		</center>
        <?
		break;
		
	//	Ordenes de Pago
	case "filtro_orden_pagod":
		/* ?>
		<center>
		<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar las Ordenes de Pago</div>
		<br /><br />
		<form name="frmentrada">
		<table>
			<tr>
			  <td align="right">Proveedor: </td>
			  <td colspan="3">
				<input type="hidden" name="idbeneficiario" id="idbeneficiario" value="" />
				<input type="text" name="beneficiario" id="beneficiario" style="width:400px" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=beneficiarios', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
			  </td>
			</tr>
			<tr>
				<td align="right">Categor&iacute;a Program&aacute;tica: </td>
			  <td colspan="3">
				<input type="hidden" name="idcategoria" id="idcategoria" value="" />
				<input type="text" name="categoria" id="categoria" style="width:400px" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
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
              </td>
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
			  <td align="right">Tipo de Solicitud: </td>
			  <td colspan="3">
				<select name="idtipo" id="idtipo">
					<option value="0"></option>
					<?php
						$sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE modulo like '%-4-%' ORDER BY descripcion";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
					?>
				</select>
			  </td>
			</tr>
			<tr>
			  <td align="right">Estado de la Solicitud: </td>
			  <td colspan="3">
				<select name="idestado" id="idestado">
					<option value="0"></option>
					<option value="elaboracion">En Elaboraci&oacute;n</option>
					<option value="procesado">Procesado</option>
					<option value="conformado">Conformado</option>
					<option value="devuelto">Devuelto</option>
					<option value="anulado">Anulado</option>
					<option value="pagada">Pagado</option>
				</select>
			  </td>
			</tr>
             <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_filtro_orden_pagod('<?=$nombre?>')" /></td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
		  </table>
		  </form>
		</center>
        <? */
		break;
	
	//	Ordenes de Pago por Dependencia
	case "filtro_orden_pago":
		?>
		<center>
		<div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese los criterios para filtrar las Ordenes de Pago</div>
		<br /><br />
		 <form name="frmentrada">
		<table>
			<tr>
			  <td align="right">Proveedor: </td>
			  <td colspan="3">
				<input type="hidden" name="idbeneficiario" id="idbeneficiario" value="" />
				<input type="text" name="beneficiario" id="beneficiario" style="width:400px" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=beneficiarios', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
			  </td>
			</tr>
			<tr>
				<td align="right">Categor&iacute;a Program&aacute;tica: </td>
			  <td colspan="3">
				<input type="hidden" name="idcategoria" id="idcategoria" value="" />
				<input type="text" name="categoria" id="categoria" style="width:400px" />            
				<img src="../../../imagenes/search0.png" title="Buscar" style="cursor:pointer" onclick="window.open('../../listas/lista.php?lista=categorias', 'wLista','dependent=yes, height=400, width=700, scrollbars=yes, top=150, left=200');" />
			  </td>
			</tr>
            <tr>
                <td align="right">Fuente de Financiamiento: </td>
                <td colspan="3">
                    <select name="financiamiento" id="financiamiento" style="width:300px;">
                        <option value="0"></option>
                        <?
                        $sql="SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
                        $query_fin=mysql_query($sql) or die ($sql.mysql_error());
                        while ($fin=mysql_fetch_array($query_fin)) {
                            if ($fin['idfuente_financiamiento']==$conf['idfuente_financiamiento']) 
                                echo "<option value='".$fin['idfuente_financiamiento']."' selected>".$fin['denominacion']."</option>";
                            else  
                                echo "<option value='".$fin['idfuente_financiamiento']."'>".$fin['denominacion']."</option>";
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
              </td>
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
           <? /* <tr>
			  <td align="right">Dependencia: </td>
			  <td colspan="3">
				<select name="idmodulo" id="idmodulo">
					<option value="0"></option>
					<?php 
						$sql="SELECT * FROM modulo where mostrar = 'si' ORDER BY nombre_modulo";
						$query=mysql_query($sql) or die ($sql.mysql_error());
						while ($field=mysql_fetch_array($query)) { ?> 
                        	<option value="<?=$field["id_modulo"]?>" onClick="seleccionarTipo('<?=$field["id_modulo"]?>')"><?=$field["nombre_modulo"]?></option>
					<? }?>
				</select>
			  </td>
			</tr> 
			*/ ?>

			<tr>
			  <td align="right">Tipo de Documento: </td>
			  <td colspan="3" id="celda_tipo_documento">
              <select name="idtipo" id="idtipo">
				<option value="0">.:: Seleccione el Tipo de Documento ::.</option>
                <?
                $sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos 
													WHERE (compromete = 'no' and causa = 'si')
													or (compromete = 'si' and causa = 'si')
													or (compromete = 'no' and causa = 'no' and paga = 'no' and documento_asociado <> 0)
													ORDER BY descripcion";
				$query=mysql_query($sql) or die ($sql.mysql_error());
				while ($field=mysql_fetch_array($query)) {
	
					?>
					<option value=<?=$field[0]?>><?=$field[1]?></option>
					<? 
				} ?>
     		  </select>
			  </td>
			</tr>

			

			
			<tr>
			  <td align="right">Estado de la Solicitud: </td>
			  <td colspan="3">
				<select name="idestado" id="idestado">
					<option value="0"></option>
					<option value="elaboracion">En Elaboraci&oacute;n</option>
					<option value="procesado">Procesado</option>
					<option value="conformado">Conformado</option>
					<option value="devuelto">Devuelto</option>
					<option value="anulado">Anulado</option>
					<option value="pagada">Pagado</option>
                    <option value="procesadapagada">Procesado-Pagado</option>
				</select>
			  </td>
			</tr>
            <tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3"><input type="checkbox" name="chkpartidas" id="chkpartidas" /> &nbsp; Mostrar Partidas Presupuestarias </td>
			</tr>
			<tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3"><input type="checkbox" name="chkconcepto" id="chkconcepto" /> &nbsp; Mostrar Concepto de la Orden </td>
			</tr>
			<tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3"><input type="checkbox" name="chkdocumentofinanciero" id="chkdocumentofinanciero" /> &nbsp; Mostrar Documento de Pago Financiero </td>
			</tr>
            <tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3"><input type="checkbox" name="chksinafectacion" id="chksinafectacion" /> &nbsp; Incluir Ordenes Sin Afectaci&oacute;n </td>
			</tr>
			<tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3"><input type="checkbox" name="chkanticipo" id="chkanticipo" /> &nbsp; Solo Mostrar Anticipos </td>
			</tr>
			<tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3"><input type="checkbox" name="chkerror" id="chkerror" /> &nbsp; MOSTRAR ERRORES DE DIFERENCIAS ENTRE PARTIDAS Y TOTALES </td>
			</tr>
			<tr>
			  	<td align="right">&nbsp;</td>
              	<td colspan="3">&nbsp;</td>
			</tr>
            <tr>
                <td colspan="4" align="center">
                <input type="radio" name="tipo" id="pdf" value="pdf" checked onclick="enabledArchivo();" /> PDF
                <input type="radio" name="tipo" id="excel" value="excel" onclick="enabledArchivo();" /> Excel
                <input type="text" name="archivo" id="archivo" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_filtro_orden_pagod('<?=$nombre?>')" /></td>
            </tr>
		  </table>
		</center>
        </form>
        <? 
		break;
	
	//	Ordenes de Pago por Financiamiento...
	case "ordenes_pago_por_financiamiento":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Seleccione la opci&oacute;n para filtrar las Ordenes por Financiamiento</div>
        <br /><br />
        <?
        $sql="SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf=mysql_query($sql) or die ($sql.mysql_error());
        $conf=mysql_fetch_array($query_conf);
        ?>
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
                        $sql="SELECT idtipo_presupuesto, denominacion FROM tipo_presupuesto";
                        $query_pres=mysql_query($sql) or die ($sql.mysql_error());
                        while ($pres=mysql_fetch_array($query_pres)) {
                            if ($pres['idtipo_presupuesto']==$conf['idtipo_presupuesto']) 
                                echo "<option value='".$pres['idtipo_presupuesto']."' selected>".$pres['denominacion']."</option>";
                            else  
                                echo "<option value='".$pres['idtipo_presupuesto']."'>".$pres['denominacion']."</option>";
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
                        $sql="SELECT idfuente_financiamiento, denominacion FROM fuente_financiamiento";
                        $query_fin=mysql_query($sql) or die ($sql.mysql_error());
                        while ($fin=mysql_fetch_array($query_fin)) {
                            if ($fin['idfuente_financiamiento']==$conf['idfuente_financiamiento']) 
                                echo "<option value='".$fin['idfuente_financiamiento']."' selected>".$fin['denominacion']."</option>";
                            else  
                                echo "<option value='".$fin['idfuente_financiamiento']."'>".$fin['denominacion']."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="4"><hr size="1" width="550" /></td></tr>
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
                <td align="right">Tipo de Solicitud: </td>
                <td colspan="3">
                    <select name="tipo" id="tipo" style="width:300px;">
                        <option value="0"></option>
                        <?php
                        //if ($_SESSION['modulo']=='1' || $_SESSION['modulo']=='13') $or="(modulo like '%-".$_SESSION['modulo']."-%' OR modulo like '%-13-%' OR modulo like '%-1-%'"; 
                        $sql="SELECT idtipos_documentos, descripcion FROM tipos_documentos WHERE (causa = 'no' AND compromete = 'si') ORDER BY descripcion";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>$field[1]</option>";
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Estado: </td>
                <td colspan="3">
                    <select name="estado" id="estado" style="width:300px;">
                        <option value="0"></option>
                        <?php
                        $sql="SELECT estado FROM orden_pago GROUP BY estado ORDER BY estado";
                        $query=mysql_query($sql) or die ($sql.mysql_error());
                        while ($field=mysql_fetch_array($query)) echo "<option value='$field[0]'>".strtoupper($field[0])."</option>";
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_ordenes_pago_por_financiamiento('<?=$nombre?>');" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;
		
		
	//	Sobregiro Tributario...
	case "sobregiro_tributario":
		?>
        <center>
        <div style="font-size:20px; width:300px;  font-weight:bold; font-style:italic; top:50%; left:50%;">Ingrese la cantidad de unidades tributarias para ver las ordenes sobregiradas</div>
        <br /><br />
        <?
        $sql="SELECT anio_fiscal, idtipo_presupuesto, idfuente_financiamiento FROM configuracion";
        $query_conf=mysql_query($sql) or die ($sql.mysql_error());
        $conf=mysql_fetch_array($query_conf);
        ?>
        <table>
            <tr>
                <td align="right">Cantidad U.T: </td>
                <td>
                	<?
					$sql = "SELECT costo_unidad_tributaria FROM configuracion_tributos limit 0, 1";
					$query_ut = mysql_query($sql) or die($sql.mysql_error());
					if (mysql_num_rows($query_ut) != 0) $field_ut = mysql_fetch_array($query_ut);
					?>
                	<input type="text" name="cantidad_ut" id="cantidad_ut" style="text-align:right;" />
                    <input type="hidden" name="valor_ut" id="valor_ut" value="<?=$field_ut['costo_unidad_tributaria']?>" />
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="validar_sobregiro_tributario('<?=$nombre?>');" /></td>
            </tr>
        </table>    
        </center>
        <?
		break;
}

?>
</body>
</html>