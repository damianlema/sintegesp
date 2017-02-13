// JavaScript Document

function ingresarUbicacion(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	if(codigo == ''){
		mostrarMensajes("error", "Disculpe debe escribir el Codigo");
	}else if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/ubicacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarUbicacion();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarUbicacion");
	}
}



function limpiarDatos(){
	document.getElementById('codigo').value = '';
	document.getElementById('denominacion').value = '';
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function listarUbicacion(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/ubicacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaUbicaciones').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarUbicacion");		
}




function modificarUbicacion(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var idubicacion = document.getElementById('idubicacion').value;
	if(codigo == ''){

		mostrarMensajes("error", "Disculpe debe escribir un Codigo");
	}else if(denominacion == ''){

		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/ubicacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarUbicacion();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idubicacion="+idubicacion+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=modificarUbicacion");
	}
}





function eliminarUbicacion(){
	var idubicacion = document.getElementById('idubicacion').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/ubicacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se eliminaron los datos con exito");
					listarUbicacion();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idubicacion="+idubicacion+"&ejecutar=eliminarUbicacion");
}



function seleccionarModificar(idubicacion, codigo, denominacion){
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idubicacion').value = idubicacion;
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idubicacion, codigo, denominacion){
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idubicacion').value = idubicacion;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}