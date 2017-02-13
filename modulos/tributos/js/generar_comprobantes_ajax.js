// JavaScript Document


function mostrarCompromisos(idorden_pago, div){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById(div).innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_pago="+idorden_pago+"&ejecutar=mostrarCompromisos");	
}




function actualizarDatosdeFactura(numero_factura, numero_control, fecha_factura, idorden_pago, idorden_compra_servicio, div, idretenciones){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			if(ajax.responseText == 'exito'){
				mostrarMensajes("exito", "Datos Actualizados con Exito");
			}
			if(ajax.responseText == 'existe'){
				mostrarMensajes("error","El numero de factura ya fue registrado para este proveedor");
			}
			mostrarCompromisos(idorden_pago, div);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra_servicio="+idorden_compra_servicio+"&numero_factura="+numero_factura
			+"&numero_control="+numero_control+"&fecha_factura="+fecha_factura
			+"&idorden_pago="+idorden_pago+"&idretenciones="+idretenciones+"&ejecutar=actualizarDatosdeFactura");
}


function actualizarDatosdePago(numero_deposito, fecha_enteramiento, fecha_deposito, fecha_transferencia, idrelacion_retenciones){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			if(ajax.responseText == 'exito'){
				mostrarMensajes("exito", "Datos Actualizados con Exito");
			}
			document.getElementById("divCargando").style.display = "none";
		}
	}

	ajax.send("idrelacion_retenciones="+idrelacion_retenciones
			+"&numero_deposito="+numero_deposito
			+"&fecha_enteramiento="+fecha_enteramiento
			+"&fecha_deposito="+fecha_deposito
			+"&fecha_transferencia="+fecha_transferencia
			+"&ejecutar=actualizarDatosdePago");
}


function generarComprobante(idorden_pago, ruta, estado){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText == "existeComprobante"){
					mostrarMensajes("error", "Disculpe esta Orden ya tiene Comprobante de Retenciones");
				}else{
					mostrarMensajes("exito", "Comprobantes generados con exito");
				}
				//imprimirReporte(idorden_pago, ruta);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_pago="+idorden_pago+"&ejecutar=generarComprobante");	
}


function imprimirReporte(idorden_pago, ruta){
	document.getElementById('pdf').src="lib/reportes/tributos/reportes.php?nombre=emitir_retenciones&id_emision_pago="+idorden_pago+"&estado=procesado&origen=tributos_internos";
	document.getElementById('pdf').style.display='block'; 
	document.getElementById('divImprimir').style.display='block';
}



function listarOrdenPago(busqueda){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaOrdenPago').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("busqueda="+busqueda+"&ejecutar=listarOrdenPago");
	return false;
}


function guardarAnticipo(idorden_pago, anticipo){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "Porcentaje de anticipo guardado");
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("anticipo="+anticipo+"&idorden_pago="+idorden_pago+"&ejecutar=guardarAnticipo");
}



function anularComprobante(idorden_pago,estado){
	if (estado == "pagada"){
		alert("No se púede anular el comprobante hasta que no se anule el cheque");
	}
	if (estado == "procesado"){
		if(confirm("Realmente desea Anular el Comprobante de este Pago?")){
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					alert(ajax.responseText);
						mostrarMensajes("exito", "El comprobante fue Anulado con Exito");
						document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("idorden_pago="+idorden_pago+"estado="+estado+"&ejecutar=anularComprobante");
		}
	}
	
}



function cambiaGenerarComprobante(idrelacion_retenciones){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){				
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrelacion_retenciones="+idrelacion_retenciones+"&ejecutar=cambiaGenerarComprobante");	
}
