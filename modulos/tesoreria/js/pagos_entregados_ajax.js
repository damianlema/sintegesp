// JavaScript Document

function mostrarDatos(idpagos_financieros, numero_orden_pago, beneficiario, recibido_por, ci_recibe, fecha_recibe){
	document.getElementById('idpagos_financieros').value = idpagos_financieros;
	document.getElementById('divOrdenPago').innerHTML = "<strong>"+numero_orden_pago+"</strong>";
	document.getElementById('divBeneficiario').innerHTML = "<strong>"+beneficiario+"</strong>";
	document.getElementById('recibido_por').value = recibido_por;
	document.getElementById('ci_recibe').value = ci_recibe;
	document.getElementById('fecha_recibe').value = fecha_recibe;
}




function actualizarDatos(idpagos_financieros){
	recibido_por = document.getElementById('recibido_por').value;
	ci_recibe = document.getElementById('ci_recibe').value;
	fecha_recibe = document.getElementById('fecha_recibe').value;
	
	if(document.getElementById('idpagos_financieros').value == ""){
		mostrarMensajes("error", "Disculpe Seleccione un Pago para poder actualizar sus datos");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/pagos_entregados_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Datos Actualizados con Exito");
					document.getElementById('idpagos_financieros').value = "";
					document.getElementById('divOrdenPago').innerHTML = "";
					document.getElementById('divBeneficiario').innerHTML = "";
					document.getElementById('recibido_por').value = "";
					document.getElementById('ci_recibe').value = "";
					document.getElementById('fecha_recibe').value = "";
					listarPagosFinancieros();
				}else{	
					mostrarMensajes("error", "ERROR: "+ajax.responseText);
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idpagos_financieros="+idpagos_financieros+"&recibido_por="+recibido_por+"&ci_recibe="+ci_recibe+"&fecha_recibe="+fecha_recibe+"&ejecutar=actualizarDatos");	
	}
}










function listarPagosFinancieros(){
	var cuenta = document.getElementById('cuenta').value;
	var nro_orden = document.getElementById('nro_orden_busqueda').value;
	var nro_cheque = document.getElementById('nro_cheque_busqueda').value;
	var beneficiario = document.getElementById('beneficiario_busqueda').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/pagos_entregados_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('lista_pagos_financieros').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("banco="+banco+"&cuenta="+cuenta+"&nro_orden="+nro_orden+"&nro_cheque="+nro_cheque+"&beneficiario="+beneficiario+"&ejecutar=listarPagosFinancieros");	
}





function cargarCuentasBancarias(banco){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/conciliacion_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById('celdaCuentaBancaria').innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("banco="+banco+"&ejecutar=cargarCuentasBancarias");
}