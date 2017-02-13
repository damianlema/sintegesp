// JavaScript Document

function consultarAsociados(){
	var idtrabajador = document.getElementById("id_trabajador").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/cuentas_bancarias_trabajador_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("lista_cuentas_bancarias").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarAsociados");		
}





function ingresarCunetaBancaria(){
	var idtrabajador = document.getElementById("id_trabajador").value;
	var numero_cuenta = document.getElementById("numero_cuenta").value;
	var banco = document.getElementById("banco").value;
	var tipo_cuenta = document.getElementById("tipo_cuenta").value;
	var motivo_cuenta = document.getElementById("motivo_cuenta").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/cuentas_bancarias_trabajador_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "La cuenta bancaria fue ingresada con exito");
			limpiarDatos();
			consultarAsociados();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&numero_cuenta="+numero_cuenta+"&banco="+banco+"&tipo_cuenta="+tipo_cuenta+"&motivo_cuenta="+motivo_cuenta+"&ejecutar=ingresarCunetaBancaria");			
}





function modificarCunetaBancaria(){
	var idcuenta_bancaria = document.getElementById("idcuenta_bancaria").value;
	var numero_cuenta = document.getElementById("numero_cuenta").value;
	var banco = document.getElementById("banco").value;
	var tipo_cuenta = document.getElementById("tipo_cuenta").value;
	var motivo_cuenta = document.getElementById("motivo_cuenta").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/cuentas_bancarias_trabajador_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos();
			consultarAsociados();
		} 
	}
	ajax.send("idcuenta_bancaria="+idcuenta_bancaria+"&numero_cuenta="+numero_cuenta+"&banco="+banco+"&tipo_cuenta="+tipo_cuenta+"&motivo_cuenta="+motivo_cuenta+"&ejecutar=modificarCunetaBancaria");			
}




function seleccionar(nombres, nro_cuenta, tipo, motivo, banco, idcuenta_bancaria){
	
	document.getElementById("idcuenta_bancaria").value = idcuenta_bancaria;
	document.getElementById("numero_cuenta").value = nro_cuenta;
	document.getElementById("banco").value = banco;
	document.getElementById("tipo_cuenta").value = tipo;
	document.getElementById("motivo_cuenta").value = motivo;
	
	document.getElementById("boton_ingresar").style.display = "none";
	document.getElementById("boton_modificar").style.display = "block";
	document.getElementById("boton_eliminar").style.display = "block";
}




function eliminarCuentaBancaria(){
	if(confirm("Realmente desea Eliminar esta Cuenta Bancaria?")){
	var idcuenta_bancaria = document.getElementById("idcuenta_bancaria").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/cuentas_bancarias_trabajador_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos();
			consultarAsociados();
		} 
	}
	ajax.send("idcuenta_bancaria="+idcuenta_bancaria+"&ejecutar=eliminarCuentaBancaria");	
	}
}


function limpiarDatos(){
	document.getElementById("idcuenta_bancaria").value ="";
	document.getElementById("numero_cuenta").value = "";
	document.getElementById("banco").value = 0;
	document.getElementById("tipo_cuenta").value = "";
	document.getElementById("motivo_cuenta").value = "";
	
	document.getElementById("boton_ingresar").style.display = "block";
	document.getElementById("boton_modificar").style.display = "none";
	document.getElementById("boton_eliminar").style.display = "none";
}