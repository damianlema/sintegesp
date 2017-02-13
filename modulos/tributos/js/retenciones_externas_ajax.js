 // JavaScript Document

function registrarDatosBasicos(ente, beneficiario){
	if((ente || beneficiario) == ""){
		mostrarMensajes("error", "Disculpe debe ingresar el Ente Gubernamental y el Proveedor o Beneficiario");
	}else{
		fecha_validada = document.getElementById('fecha_aplicacion').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById('id_retencion').value = ajax.responseText;
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_procesar').style.display = 'block';
					document.getElementById('imagen_agregar_ente').style.display = 'none';
					document.getElementById('imagen_agregar_beneficiario').style.display = 'none';
					document.getElementById('divValoresFacturas').style.display = 'block';
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("ente="+ente+"&beneficiario="+beneficiario+"&fecha_validada="+fecha_validada+"&ejecutar=registrarDatosBasicos");	
	}
}






function ingresarFactura(){
	var nro_orden = document.getElementById('numero_orden').value;
	var nro_factura = document.getElementById('numero_factura').value;
	var nro_control = document.getElementById('numero_control').value;
	var fecha_factura = document.getElementById('fecha_factura').value;
	var tipo_retencion = document.getElementById('tipo_retencion').value;
	var codigo_islr = document.getElementById('codigo_islr').value;
	var base_calculo = document.getElementById('base_calculo_oculto').value;
	var porcentaje = document.getElementById('porcentaje').value;
	var divisor = document.getElementById('divisor').value;
	var alicuota = document.getElementById('alicuota_oculto').value;
	var factor = document.getElementById('factor').value;
	var total_retenido = document.getElementById('total_retenido').value;
	var idretencion = document.getElementById('id_retencion').value;
	var exento = document.getElementById('exento_oculto').value;
	var sub_total = document.getElementById('sub_total_oculto').value;
	var impuesto = document.getElementById('impuesto_oculto').value;
	var total = document.getElementById('total_oculto').value;
	var id_beneficiario = document.getElementById('id_beneficiario').value;
	var fecha_orden = document.getElementById('fecha_orden').value;
	var concepto_orden = document.getElementById('concepto_orden').value;
	var monto_contrato = document.getElementById('monto_contrato_oculto').value;
	var ajax=nuevoAjax();
	if(total_retenido != "0"){
	if(tipo_retencion != "0"){
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el Tipo de Retencion ya se Aplico a esta factura o esta factura fue procesada para este Proveedor");
				}else if(ajax.responseText == "masdoce"){
					mostrarMensajes("error", "Disculpe ha llegado al limite de Facturas para este tipo de Retencion");
				}else{
					listarFacturas(document.getElementById('id_retencion').value);	
				}
				document.getElementById('tipo_retencion').value = 0;
				document.getElementById('base_calculo').value = "";
				document.getElementById('base_calculo_oculto').value = "";
				document.getElementById('porcentaje').value = "";
				document.getElementById('divisor').value = "";
				document.getElementById('factor').value = "";
				document.getElementById('total_retenido').value = "";
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idretencion="+idretencion+"&nro_orden="+nro_orden+"&nro_factura="+nro_factura+"&nro_control="+nro_control+"&fecha_factura="+fecha_factura+"&tipo_retencion="+tipo_retencion+"&codigo_islr="+codigo_islr+"&base_calculo="+base_calculo+"&porcentaje="+porcentaje+"&total="+total+"&impuesto="+impuesto+"&sub_total="+sub_total+"&exento="+exento+"&divisor="+divisor+"&alicuota="+alicuota+"&factor="+factor+"&id_beneficiario="+id_beneficiario+"&total_retenido="+total_retenido+"&fecha_orden="+fecha_orden+"&concepto_orden="+concepto_orden+"&monto_contrato="+monto_contrato+"&ejecutar=ingresarFactura");
	}else{
		mostrarMensajes("error", "Disculpe debe seleccionar un Tipo de Retencion");
	}
	}else{
		mostrarMensajes("error", "Disculpe el Monto Total a Retener no puede ser Cero");
	}
}





function listarFacturas(id_retencion){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
					document.getElementById('listaFacturaRetencionesExternas').innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_retencion="+id_retencion+"&ejecutar=listarFacturas");	
}




function mostrarMontos(idtipo_retencion){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
					var partes = ajax.responseText.split("|.|");
					var base = 0;
					if(partes[0] == "Exento"){
						base = document.getElementById('exento_oculto').value;
						base_mostrar = document.getElementById('exento').value;
					}else if(partes[0] == "Base Imponible"){
						base = document.getElementById('sub_total_oculto').value;
						base_mostrar = document.getElementById('sub_total').value;
					}else if(partes[0] == "IVA"){
						base = document.getElementById('impuesto_oculto').value;
						base_mostrar = document.getElementById('impuesto').value;
					}else if(partes[0] == "Total"){
						base = document.getElementById('total_oculto').value;
						base_mostrar = document.getElementById('total').value;
					}
					
					document.getElementById('base_calculo').value=base_mostrar;
					document.getElementById('base_calculo_oculto').value=base;
					document.getElementById('factor').value=partes[1];
					document.getElementById('porcentaje').value=partes[2];
					document.getElementById('divisor').value=partes[3];
					document.getElementById('total_retenido').value = ((base*partes[2])/partes[3])-partes[1];
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idtipo_retencion="+idtipo_retencion+"&ejecutar=mostrarMontos");			
}



function sumaTotal(){
	if(document.getElementById('exento').value == ""){
		document.getElementById('exento').value = 0.00;	
	}
	if(document.getElementById('sub_total').value == ""){
		document.getElementById('sub_total').value = 0.00;	
	}
	if(document.getElementById('impuesto').value == ""){
		document.getElementById('impuesto').value = 0.00;	
	}
	
	exento = document.getElementById('exento_oculto').value;
	sub_total = document.getElementById('sub_total_oculto').value;
	impuesto = document.getElementById('impuesto_oculto').value;
	total = parseFloat(exento) + parseFloat(sub_total) + parseFloat(impuesto);
	document.getElementById('total_oculto').value = total;
	document.getElementById('total').value = total;
	formatoNumero('total', 'total_oculto');
}

function calcularImpuesto(){
	sub_total = document.getElementById('sub_total_oculto').value;
	alicuota = document.getElementById('alicuota_oculto').value;
	impuesto = (sub_total*alicuota)/100;
	document.getElementById('impuesto').value = impuesto;
	document.getElementById('impuesto_oculto').value = impuesto;
	formatoNumero('impuesto', 'impuesto_oculto');
}



function mostrarModificarFacturas(idrelacion_retencion){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				document.getElementById('numero_orden').value = partes[0];
				document.getElementById('numero_factura').value = partes[1];
				document.getElementById('numero_control').value = partes[2];
				document.getElementById('fecha_factura').value = partes[3];
				document.getElementById('tipo_retencion').value = partes[4];
				document.getElementById('codigo_islr').value = partes[5];
				document.getElementById('base_calculo').value = partes[6];
				formatoNumero('base_calculo','base_calculo_oculto');
				document.getElementById('alicuota').value = partes[7];
				formatoNumero('alicuota','alicuota_oculto');
				document.getElementById('factor').value = partes[8];
				document.getElementById('total_retenido').value = partes[9];
				document.getElementById('porcentaje').value = partes[10];
				document.getElementById('exento').value = partes[11];
				formatoNumero('exento','exento_oculto');
				document.getElementById('sub_total').value = partes[12];
				formatoNumero('sub_total','sub_total_oculto');
				document.getElementById('impuesto').value = partes[13];
				formatoNumero('impuesto','impuesto_oculto');
				document.getElementById('total').value = partes[14];
				formatoNumero('total','total_oculto');
				document.getElementById('divisor').value = partes[15];
				document.getElementById('fecha_orden').value = partes[16];
				document.getElementById('concepto_orden').value = partes[17];
				document.getElementById('monto_contrato').value = partes[18];
				document.getElementById('boton_registrar_factura').style.display = 'none';
				document.getElementById('boton_modificar_factura').style.display = 'block';
				document.getElementById('divCargando').style.display = "none";
				document.getElementById('idrelacion_retencion').value = idrelacion_retencion;
				document.getElementById('exento').disabled = true;
				document.getElementById('sub_total').disabled = true;
				document.getElementById('impuesto').disabled = true;
				document.getElementById('total').disabled = true;
				document.getElementById('alicuota').disabled = true;
		} 
	}
	ajax.send("idrelacion_retencion="+idrelacion_retencion+"&ejecutar=mostrarModificarFacturas");			
}



function eliminarFactura(idrelacion_retencion){
	if(confirm("Seguro dese Eliminar esta Retencion?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarFacturas(document.getElementById('id_retencion').value);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idrelacion_retencion="+idrelacion_retencion+"&ejecutar=eliminarFactura");	
	}
}





function procesarRetencion(id_retencion){
	if(confirm("Seguro Desea Procesar esta Retencion?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById("boton_imprimir").style.display="block";
				consultarRetencion(document.getElementById('id_retencion').value);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_retencion="+id_retencion+"&ejecutar=procesarRetencion");	
	}	
}




function consultarRetencion(id_retencion){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				
				partes = ajax.responseText.split("|.|");
				document.getElementById('id_retencion').value = id_retencion;
				document.getElementById('id_beneficiario').value = partes[0];
				document.getElementById('id_ente_gubernamental').value = partes[1];
				document.getElementById('beneficiario').value = partes[2];
				document.getElementById('ente_gubernamental').value = partes[3];
				document.getElementById('fecha_aplicacion').value = partes[6];
				document.getElementById('celda_estado').innerHTML = "<strong>"+partes[4]+"</strong>";
				if(partes[5] == ""){
					document.getElementById('celda_numero_retencion').innerHTML = "<strong>No Generado</strong>";	
				}else{
					document.getElementById('celda_numero_retencion').innerHTML = "<strong>"+partes[5]+"</strong>";	
				}
				
				if(partes[4] == "procesado"){
					document.getElementById('imagen_agregar_ente').style.display = 'none';
					document.getElementById('imagen_agregar_beneficiario').style.display = 'none';
					document.getElementById('tipo_retencion').disabled = true;
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'block';
					document.getElementById('boton_eliminar').style.display = 'block';
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('divValoresFacturas').style.display = 'none';
					document.getElementById('beneficiario').disabled = true;
					document.getElementById('ente_gubernamental').disabled = true;
					document.getElementById("boton_imprimir").style.display="block";
					document.getElementById('imagen_actualizar_fecha').style.display = 'block';
				}
				if(partes[4] == "anulado"){
					document.getElementById('imagen_agregar_ente').style.display = 'none';
					document.getElementById('imagen_agregar_beneficiario').style.display = 'none';
					document.getElementById('tipo_retencion').disabled = true;
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('divValoresFacturas').style.display = 'none';
					document.getElementById('beneficiario').disabled = true;
					document.getElementById('ente_gubernamental').disabled = true;	
					document.getElementById("boton_imprimir").style.display="none";
				}
				if(partes[4] == "elaboracion"){
					document.getElementById('imagen_agregar_ente').style.display = 'block';
					document.getElementById('imagen_agregar_beneficiario').style.display = 'block';
					document.getElementById('tipo_retencion').disabled = false;
					document.getElementById('boton_procesar').style.display = 'block';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('divValoresFacturas').style.display = 'block';
					document.getElementById('beneficiario').disabled = false;
					document.getElementById('ente_gubernamental').disabled = false;
					document.getElementById("boton_imprimir").style.display="none";
				}
				listarFacturas(document.getElementById('id_retencion').value);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_retencion="+id_retencion+"&ejecutar=consultarRetencion");	
}






function modificarFactura(){
	    var numero_orden = document.getElementById('numero_orden').value;
		var fecha_orden = document.getElementById('fecha_orden').value;
		var numero_factura = document.getElementById('numero_factura').value;
		var numero_control = document.getElementById('numero_control').value;
		var fecha_factura = document.getElementById('fecha_factura').value;
		var idtipo_retencion = document.getElementById('tipo_retencion').value;
		var codigo_islr = document.getElementById('codigo_islr').value;
		var base_calculo = document.getElementById('base_calculo_oculto').value;
		var alicuota = document.getElementById('alicuota').value;
		var factor = document.getElementById('factor').value;
		var monto_retenido = document.getElementById('total_retenido').value;
		var porcentaje = document.getElementById('porcentaje').value;		
		var divisor = document.getElementById('divisor').value;
		var concepto_orden = document.getElementById('concepto_orden').value;
		var monto_contrato = document.getElementById('monto_contrato_oculto').value;
		var idrelacion_retencion = document.getElementById('idrelacion_retencion').value;
		
	if(monto_retenido != "0"){	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('boton_modificar_factura').style.display = 'none';
			document.getElementById('boton_registrar_factura').style.display = 'block';
			listarFacturas(document.getElementById('id_retencion').value);
			document.getElementById('tipo_retencion').value = 0;
			document.getElementById('base_calculo').value = "";
			document.getElementById('porcentaje').value = "";
			document.getElementById('divisor').value = "";
			document.getElementById('factor').value = "";
			document.getElementById('total_retenido').value = "";
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_orden="+numero_orden+"&numero_factura="+numero_factura+"&numero_control="+numero_control+"&fecha_factura="+fecha_factura+"&idtipo_retencion="+idtipo_retencion+"&codigo_islr="+codigo_islr+"&base_calculo="+base_calculo+"&alicuota="+alicuota+"&factor="+factor+"&monto_retenido="+monto_retenido+"&porcentaje="+porcentaje+"&fecha_orden="+fecha_orden+"&codigo_islr="+codigo_islr+"&divisor="+divisor+"&idrelacion_retencion="+idrelacion_retencion+"&concepto_orden="+concepto_orden+"&monto_contrato="+monto_contrato+"&ejecutar=modificarFactura");
	}else{
		mostrarMensajes("error", "Disculpe el Monto Total Retenido no puede ser Cero");
	}
}







function anularRetencion(id_retencion){
	if(confirm("Seguro Desea Anular esta Retencion?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				consultarRetencion(document.getElementById('id_retencion').value);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_retencion="+id_retencion+"&ejecutar=anularRetencion");	
	}	
}


function eliminarRetencion(id_retencion){
	if(confirm("Seguro Desea Eliminar esta Retencion?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//consultarRetencion(document.getElementById('id_retencion').value);
				document.getElementById("divCargando").style.display = "none";
				window.location.href='principal.php?modulo=6&accion=738'
			} 
		}
		ajax.send("id_retencion="+id_retencion+"&ejecutar=eliminarRetencion");	
	}	
}

function nuevaFactura(){
	document.getElementById('numero_orden').value = "";
	document.getElementById('numero_factura').value = "";
	document.getElementById('numero_control').value = "";
	document.getElementById('fecha_factura').value = "";
	document.getElementById('codigo_islr').value = "";
	document.getElementById('alicuota').value = "0.00";
	document.getElementById('alicuota_oculto').value = "0";
	document.getElementById('exento').value = "0.00";
	document.getElementById('exento_oculto').value = "0";
	document.getElementById('sub_total').value = "0.00";
	document.getElementById('sub_total_oculto').value = "0";
	document.getElementById('impuesto').value ="0.00";
	document.getElementById('impuesto_oculto').value ="0";
	document.getElementById('total').value = "0.00";
	document.getElementById('total_oculto').value = "0";
	document.getElementById('fecha_orden').value = "";
	document.getElementById('concepto_orden').value = "";
	document.getElementById('monto_contrato').value = "0.00";
	document.getElementById('boton_registrar_factura').style.display = 'block';
	document.getElementById('boton_modificar_factura').style.display = 'none';
	document.getElementById('exento').disabled = false;
	document.getElementById('sub_total').disabled = false;
	document.getElementById('impuesto').disabled = false;
	document.getElementById('total').disabled = false;
	document.getElementById('alicuota').disabled = false;
	document.getElementById('tipo_retencion').value = 0;
	document.getElementById('base_calculo').value = "";
	document.getElementById('porcentaje').value = "";
	document.getElementById('divisor').value = "";
	document.getElementById('factor').value = "";
	document.getElementById('total_retenido').value = "";
}

function nuevaRetencion(){
	document.getElementById('codigo_islr').value = "";
	document.getElementById('boton_registrar_factura').style.display = 'block';
	document.getElementById('boton_modificar_factura').style.display = 'none';
	document.getElementById('tipo_retencion').value = 0;
	document.getElementById('base_calculo').value = "";
	document.getElementById('porcentaje').value = "";
	document.getElementById('divisor').value = "";
	document.getElementById('factor').value = "";
	document.getElementById('total_retenido').value = "";
}


function actualizarFecha(id_retencion){
	fecha_validada = document.getElementById('fecha_aplicacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_externas_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_retencion="+id_retencion+"&fecha_validada="+fecha_validada+"&ejecutar=actualizarFecha");	
}