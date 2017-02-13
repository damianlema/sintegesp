// JavaScript Document

function consultarAsociados(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/motivos_cuentas_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("lista_cuentas_bancarias").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=consultarAsociados");		
}





function ingresarCunetaBancaria(){
	var denominacion = document.getElementById("denominacion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/motivos_cuentas_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos();
			consultarAsociados();
		} 
	}
	ajax.send("denominacion="+denominacion+"&ejecutar=ingresarCunetaBancaria");			
}





function modificarCunetaBancaria(){
	var idmotivos_cuentas= document.getElementById("idmotivos_cuentas").value;
	var denominacion = document.getElementById("denominacion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/motivos_cuentas_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos();
			consultarAsociados();
		} 
	}
	ajax.send("idmotivos_cuentas="+idmotivos_cuentas+"&denominacion="+denominacion+"&ejecutar=modificarCunetaBancaria");			
}




function seleccionar(denominacion, idmotivos_cuentas){
	
	document.getElementById("idmotivos_cuentas").value = idmotivos_cuentas;
	document.getElementById("denominacion").value = denominacion;
	
	document.getElementById("boton_ingresar").style.display = "none";
	document.getElementById("boton_modificar").style.display = "block";
	document.getElementById("boton_eliminar").style.display = "block";
}




function eliminarCuentaBancaria(){
	if(confirm("Realmente desea Eliminar este motivo de cuenta?")){
	var idmotivos_cuentas = document.getElementById("idmotivos_cuentas").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/motivos_cuentas_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos();
			consultarAsociados();
		} 
	}
	ajax.send("idmotivos_cuentas="+idmotivos_cuentas+"&ejecutar=eliminarCuentaBancaria");	
	}
}


function limpiarDatos(){
	document.getElementById("idmotivos_cuentas").value ="";
	document.getElementById("denominacion").value = "";
	
	document.getElementById("boton_ingresar").style.display = "block";
	document.getElementById("boton_modificar").style.display = "none";
	document.getElementById("boton_eliminar").style.display = "none";
}