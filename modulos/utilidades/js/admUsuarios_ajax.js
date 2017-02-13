// JavaScript Document

function eliminarUsuario(cedula){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/admUsuarios_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById("divCargando").style.display = "none";
				mostrarMensajes("exito", "Se Elimino con Exito el Usuario");
				setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
			}
			
		} 
	}
	ajax.send("cedula="+cedula+"&ejecutar=eliminarUsuario");	
}

function activarUsuario(cedula){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/admUsuarios_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById("divCargando").style.display = "none";
				mostrarMensajes("exito", "Se activo con Exito el Usuario");
				setTimeout("window.location.href='principal.php?modulo=10&accion=62'",5000);
			}
			
		} 
	}
	ajax.send("cedula="+cedula+"&ejecutar=activarUsuario");	
}


function mostrarProcesando(){
	document.getElementById("divCargando").style.display = "block";
	
}