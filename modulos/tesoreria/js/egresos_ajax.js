// JavaScript Document

function cargarCuentasBancarias(banco){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/egresos_ajax.php?banco="+banco+"&ejecutar=cargarCuentasBancarias", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('celdaCuentaBancaria').innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send(null);
}




function registrarEgresos(idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emisor,concepto){
		if(numero_documento == ""){	
			mostrarMensajes("error", "Disculpe debe llenar el numero de Documento");
			document.getElementById('numero_documento').focus();
		}else if(fecha == "" ){
			mostrarMensajes("error", "Disculpe debe seleccionar la Fecha");
		}else if(idbanco == 0){
			mostrarMensajes("error", "Disculpe debe seleccionar el banco");
			document.getElementById('banco').focus();
		}else if(idcuentas_bancarias == 0){
			mostrarMensajes("error", "Disculpe debe seleccionar la cuenta bancaria");
			document.getElementById('cuenta').focus();
		}else{
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/egresos_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divCargando").style.display = "none";
					bloquearDatos();
				//}
				
			} 
		}
		ajax.send("idtipo_movimiento="+idtipo_movimiento+"&numero_documento="+numero_documento+"&fecha="+fecha+"&idbanco="+idbanco+"&idcuentas_bancarias="+idcuentas_bancarias+"&monto="+monto+"&emitido_por="+emitido_por+"&ci_emisor="+ci_emisor+"&concepto="+concepto+"&ejecutar=registrarEgresos");
		}
}




function bloquearDatos(){
	document.getElementById('tipo_movimiento').disabled = true;
	document.getElementById('numero_documento').disabled = true;
	document.getElementById('fecha').disabled = true;
	document.getElementById('banco').disabled = true;
	document.getElementById('cuenta').disabled = true;
	document.getElementById('monto').disabled = true;
	document.getElementById('emitido_por').disabled = true;
	document.getElementById('ci_emisor').disabled = true;
	document.getElementById('concepto').disabled = true;
	document.getElementById('botonProcesar').disabled = true;
}











function mostrarContenido(id_egresos, idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emitido, concepto){
	cargarCuentasBancarias(idbanco);
	document.getElementById('id_egresos').value = id_egresos;
	document.getElementById('tipo_movimiento').value = idtipo_movimiento;
	document.getElementById('numero_documento').value = numero_documento;
	document.getElementById('fecha').value = fecha;
	document.getElementById('banco').value = idbanco;
	document.getElementById('monto').value = monto;
	formatoNumero('monto', 'monto_oculto');
	document.getElementById('emitido_por').value = emitido_por;
	document.getElementById('ci_emisor').value = ci_emitido;
	document.getElementById('concepto').value = concepto;
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'block';
	setTimeout("seleccionarCuenta("+idcuentas_bancarias+")", 500);
}


function seleccionarCuenta(idcuentas_bancarias){
	document.getElementById('cuenta').value = idcuentas_bancarias;	
}



function modificarEgresos(id_egresos, idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emisor,concepto){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/egresos_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divCargando").style.display = "none";
					window.location.href = 'principal.php?mmodulo=7&accion=482'
				
			} 
		}
		ajax.send("idtipo_movimiento="+idtipo_movimiento+"&numero_documento="+numero_documento+"&fecha="+fecha+"&idbanco="+idbanco+"&idcuentas_bancarias="+idcuentas_bancarias+"&monto="+monto+"&emitido_por="+emitido_por+"&ci_emisor="+ci_emisor+"&concepto="+concepto+"&id_egresos="+id_egresos+"&ejecutar=modificarEgresos");	
}



function eliminarEgresos(id_egresos){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/egresos_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					mostrarMensajes("error", "Se elimino el egreso con exito");
					document.getElementById("divCargando").style.display = "none";
					window.location.href = 'principal.php?mmodulo=7&accion=482'
				//}
				
			} 
		}
		ajax.send("id_egresos="+id_egresos+"&ejecutar=eliminarEgresos");	
}