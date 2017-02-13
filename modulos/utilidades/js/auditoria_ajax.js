// JavaScript Document

function consultarUsuarios(valor)
{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/utilidades/lib/auditoria_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() 
		{ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4)
			{
				document.getElementById("divListaUsuarios").style.display = 'block';
				document.getElementById("divListaUsuarios").innerHTML = ajax.responseText;
				if(valor == ""){
					document.getElementById("divListaUsuarios").style.display = 'none';	
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("valor="+valor+"&ejecutar=consultarUsuarios");
}



function listarTransacciones(fecha_desde, fecha_hasta, cedula, documento){
	var separacion_fecha_desde = fecha_desde.split("-");
	var separacion_fecha_hasta = fecha_hasta.split("-");
	if(fecha_desde == "" && fecha_hasta == "" && cedula == ""){
		mostrarMensajes("error", "Disculpe no puede dejar todos los criterios en blanco");
	}else if(fecha_desde != "" && fecha_hasta != "" && cedula == ""){
		mostrarMensajes("error", "Disculpe debe seleccionar un usuario, si desea el estado de todos los usuarios seleccione la opcion TODOS LOS USUARIOS");
	}else if((fecha_desde != "" && fecha_hasta == "") || (fecha_desde == "" && fecha_hasta != "")){
		mostrarMensajes("error", "Disculpe si va a seleccionar el criterio de fecha, debe seleccionar las dos correspondientes");
	}else if((fecha_desde != "" && fecha_hasta != "") && (separacion_fecha_desde[0] < 2009 || separacion_fecha_hasta[0] < 2009)){
			mostrarMensajes("error", "Disculpe el a&ntilde;o no puede ser menor que 2009");
	}else if(separacion_fecha_hasta[0] < separacion_fecha_desde[0]){
		mostrarMensajes("error", "Disculpe el a&ntilde;o de fin no puede ser menor que el de inicio");
	}else if((separacion_fecha_desde[0] == separacion_fecha_hasta[0]) && (separacion_fecha_hasta[1] < separacion_fecha_desde[1])){
			mostrarMensajes("error", "Disculpe la fecha de fin no puede ser menor que la fecha de inicio");
	}else if((separacion_fecha_desde[0] == separacion_fecha_hasta[0]) && (separacion_fecha_desde[1] == separacion_fecha_hasta[1]) && (separacion_fecha_hasta[2] < separacion_fecha_desde[2])){
		mostrarMensajes("error", "Disculpe la fecha de fin no puede ser manor que la fecha de inicio");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/utilidades/lib/auditoria_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() 
		{ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				document.getElementById("listaConsulta").innerHTML = "<center>"+ajax.responseText+"</center>";
				document.getElementById("divCargando").style.display = "none";
			} 
			
		}
		ajax.send("documento="+documento+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&cedula="+cedula+"&ejecutar=listarTransacciones");
	}
}

function listarPDF(fecha_desde, fecha_hasta, cedula){
	var separacion_fecha_desde = fecha_desde.split("-");
	var separacion_fecha_hasta = fecha_hasta.split("-");
	if(fecha_desde == "" && fecha_hasta == "" && cedula == ""){
		mostrarMensajes("error", "Disculpe no puede dejar todos los criterios en blanco");
	}else if(fecha_desde != "" && fecha_hasta != "" && cedula == ""){
		mostrarMensajes("error", "Disculpe debe seleccionar un usuario, si desea el estado de todos los usuarios seleccione la opcion TODOS LOS USUARIOS");
	}else if((fecha_desde != "" && fecha_hasta == "") || (fecha_desde == "" && fecha_hasta != "")){
		mostrarMensajes("error", "Disculpe si va a seleccionar el criterio de fecha, debe seleccionar las dos correspondientes");
	}else if((fecha_desde != "" && fecha_hasta != "") && (separacion_fecha_desde[0] < 2009 || separacion_fecha_hasta[0] < 2009)){
			mostrarMensajes("error", "Disculpe el a&ntilde;o no puede ser menor que 2009");
	}else if(separacion_fecha_hasta[0] < separacion_fecha_desde[0]){
		mostrarMensajes("error", "Disculpe el a&ntilde;o de fin no puede ser menor que el de inicio");
	}else if((separacion_fecha_desde[0] == separacion_fecha_hasta[0]) && (separacion_fecha_hasta[1] < separacion_fecha_desde[1])){
			mostrarMensajes("error", "Disculpe la fecha de fin no puede ser menor que la fecha de inicio");
	}else if((separacion_fecha_desde[0] == separacion_fecha_hasta[0]) && (separacion_fecha_desde[1] == separacion_fecha_hasta[1]) && (separacion_fecha_hasta[2] < separacion_fecha_desde[2])){
		mostrarMensajes("error", "Disculpe la fecha de fin no puede ser manor que la fecha de inicio");
	}
	else {
		document.getElementById("divTipoOrden").style.display="block";
		pdf.location.href="lib/reportes/utilidades/reportes.php?nombre=auditoria&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&cedula="+cedula;
	}
}
