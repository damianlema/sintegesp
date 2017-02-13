// JavaScript Document
document.write("<script type='text/javascript' src='js/function.js'></script>");


function seleccionarDocumento(numero_orden, fecha_orden, id_beneficiario, nombre_beneficiario, nro_factura, nro_control, fecha_factura, exento, sub_total, impuesto, total, exento_oculto, subtotal_oculto, impuesto_oculto, total_oculto, iddocomento, porcentaje_impuesto){
	document.getElementById('nro_orden').value = numero_orden;
	document.getElementById('fecha_orden').value = fecha_orden;
	document.getElementById('id_beneficiario').value = id_beneficiario;
	document.getElementById('beneficiario').value = nombre_beneficiario;
	document.getElementById('nro_factura').value = nro_factura;
	document.getElementById('nro_control').value = nro_control;
	document.getElementById('fecha_factura').value = fecha_factura;
	document.getElementById('exento').value = exento;
	document.getElementById('sub_total').value = sub_total;
	document.getElementById('impuesto').value = impuesto;
	document.getElementById('total').value = total;
	document.getElementById('exento_oculto').value = exento_oculto;
	document.getElementById('subtotal_oculto').value = subtotal_oculto;
	document.getElementById('impuesto_oculto').value = impuesto_oculto;
	document.getElementById('total_oculto').value = total_oculto;
	document.getElementById('iddocumento').value = iddocomento;
	document.getElementById('porcentaje_impuesto').value = porcentaje_impuesto;
	document.getElementById('select_tipo_pago').disabled = false;
	
	document.getElementById('exento_inicial').value = exento_oculto;
	document.getElementById('subtotal_inicial').value = subtotal_oculto;
	document.getElementById('impuesto_inicial').value = impuesto_oculto;
	document.getElementById('total_inicial').value = total_oculto;
	
}






function sumarValores(campoExento, campoSubtotal, campoImpuesto, campoTotalMostrado, campoTotal){
	exento = document.getElementById(campoExento).value;	
	subtotal = document.getElementById(campoSubtotal).value;	
	impuesto = document.getElementById(campoImpuesto).value;	
	total = parseFloat(exento) + parseFloat(subtotal) + parseFloat(impuesto);
	document.getElementById(campoTotalMostrado).value = total;
	document.getElementById(total_oculto).value = total;
	formatoNumero(campoTotalMostrado, campoTotal);
}










function mostrarRetencionModificar(idrelacion_retenciones){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("tablaDatosRetencion").innerHTML=ajax.responseText;
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrelacion_retenciones="+idrelacion_retenciones+"&ejecutar=mostrarRetencionModificar");
}




function mostrarRetencion(id_tipo_retencion, exento, sub_total, impuesto, total, destino){
	if(document.getElementById('nro_factura').value != "" && document.getElementById('nro_control').value != "" && document.getElementById('fecha_factura').value != "" && total != 0){

		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("tablaDatosRetencion").innerHTML=ajax.responseText;
					refrescarListaDocumentos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_tipo_retencion="+id_tipo_retencion+"&exento="+exento+"&sub_total="+sub_total+"&impuesto="+impuesto+"&total="+total+"&destino="+destino+"&ejecutar=mostrarRetencion");
	}
}








function registrarRetencion(tipo_pago, idretenciones, idtipo_retencion, porcentaje_aplicado, base_calculo, sustraendo, factor, monto_retenido, numero_retencion, porcentaje_impuesto){
		var tipoPago;
		var ajax2=nuevoAjax();
		
		ajax2.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
		ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax2.onreadystatechange=function() { 
			if(ajax2.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax2.readyState==4){
					if(ajax2.responseText.indexOf("exito") != -1){
						tipoPago = 'correcto';	
					}else{
						tipoPago = 'distinto';	
					}
						if(tipoPago != 'correcto'){
							mostrarMensajes("exito", "Disculpe el tipo de pago no es igual al que se registro en retenciones anteriores");
						}else if(monto_retenido == 0){
							mostrarMensajes("error", "Disculpe esta retencion no puede ser aplicada ya que el monto retenido es igual a cero");
						}else if(parseFloat(monto_retenido) > parseFloat(document.getElementById('total_oculto').value)){
							mostrarMensajes("error", "Disculpe el monto retenido no puede ser mayor al total");
						}else{
								var ajax=nuevoAjax();
								ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
								ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
								ajax.onreadystatechange=function() { 
									if(ajax.readyState == 1){
										document.getElementById("divCargando").style.display = "block";
										}
									if (ajax.readyState==4){
											if(ajax.responseText.indexOf("retencionYaAplicada") != -1){
												
												mostrarMensajes("error", "Disculpe el tipo de retencion que selecciono ya fue aplicada a este documento");
											}else{
												document.getElementById("listaRetencionesAplicadas").innerHTML=ajax.responseText;
												if(document.getElementById('estado').value == "procesado"){
													document.getElementById('divTotalRetenciones').innerHTML = parseFloat(monto_retenido);
												}else{
													document.getElementById('divTotalRetenciones').innerHTML = parseFloat(document.getElementById('divTotalRetenciones').innerHTML) + parseFloat(monto_retenido);
												}
												
											}
										document.getElementById('codigo_retencion').value = '';
										document.getElementById('descipcion_retencion').value = '';
										document.getElementById('base_calculo_retencion').value = '';
										document.getElementById('sustraendo').value = '';
										document.getElementById('factor').value = '';
										if(document.getElementById('monto_fijo_retencion')){
											document.getElementById('monto_fijo_retencion').value = '';	
										}else{
											document.getElementById('porcentaje_retencion').value = '';
											document.getElementById('divisor_retencion').value = '';		
										}		
										document.getElementById('total_retencion').value = '';	
										document.getElementById("divCargando").style.display = "none";
										
										
										
										// Mostrar los nuevos montos restantes
										
										
										
										/*var ajax3=nuevoAjax();
										ajax3.open("POST", "modulos/tributos/lib/retenciones_ajax.php?iddocumento="+document.getElementById('iddocumento').value+"&ejecutar=consultarMontoAMostrar", true);	
										ajax3.onreadystatechange=function() { 
											if(ajax3.readyState == 1){
												document.getElementById("divCargando").style.display = "block";
												}
											if (ajax3.readyState==4){
													pedasos = ajax3.responseText.split("-");
													alert(pedasos[0]+" "+pedasos[1]+" "+pedasos[2]+" "+pedasos[3]+" "+pedasos[4]);
											} 
										}
										ajax3.send(null);*/
										
										
										
										/*
										restaExento = parseFloat(document.getElementById('exento_inicial').value)-parseFloat(document.getElementById('exento_oculto').value);
										document.getElementById('exento').value = restaExento;
										document.getElementById('exento_inicial').value = restaExento;
										formatoNumero('exento', 'exento_oculto');
										
										restaBase = parseFloat(document.getElementById('subtotal_inicial').value)-parseFloat(document.getElementById('subtotal_oculto').value);
										document.getElementById('sub_total').value = restaBase;
										document.getElementById('subtotal_inicial').value = restaBase;
										formatoNumero('sub_total', 'subtotal_oculto');
										
										restaImpuesto = parseFloat(document.getElementById('impuesto_inicial').value)-parseFloat(document.getElementById('impuesto_oculto').value);
										document.getElementById('impuesto').value = restaImpuesto;
										document.getElementById('impuesto_inicial').value = restaImpuesto;
										formatoNumero('impuesto', 'impuesto_oculto');
										
										restaTotal = parseFloat(document.getElementById('total_inicial').value)-parseFloat(document.getElementById('total_oculto').value);
										document.getElementById('total').value = restaTotal;
										document.getElementById('total_inicial').value = restaTotal;
										formatoNumero('total', 'total_oculto');
										*/
									} 
								}
								ajax.send("idretenciones="+idretenciones+"&idtipo_retencion="+idtipo_retencion+"&porcentaje_aplicado="+porcentaje_aplicado+"&base_calculo="+base_calculo+"&sustraendo="+sustraendo+"&factor="+factor+"&monto_retenido="+monto_retenido+"&numero_retencion="+numero_retencion+"&porcentaje_impuesto="+document.getElementById('porcentaje_impuesto').value+"&iddocumento="+document.getElementById('iddocumento').value+"&ejecutar=registrarRetencion");
						}
						
						refrescarListaDocumentos();
						document.getElementById('estado').value = 'elaboracion';
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax2.send("iddocumento="+document.getElementById('iddocumento').value+"&tipo_pago="+tipo_pago+"&ejecutar=consultarTipoPago");
}






function registrarDatosBasicos(iddocumento, numero_documento, numero_factura, numero_control, fecha_factura, exento, base, impuesto, total, tipo_pago, estado){
	if(numero_factura == "" || numero_control == "" || fecha_factura == ""){
		mostrarMensajes("error", "Disculpe no puede dejar el Numero de la Factura o el Numero de Control o la Fecha de la Factura vacio");
	}else if(total == 0){
		mostrarMensajes("error", "Disculpe no se puede aplicar mas retenciones a este documento");
	}else{
	
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById('id_retencion').value = ajax.responseText;
						if(tipo_pago == "parcial"){
							document.getElementById('exento').disabled = true;
							document.getElementById('sub_total').disabled = true;
							document.getElementById('impuesto').disabled = true;
							document.getElementById('total').disabled = true;
						}
						refrescarListaDocumentos();
					document.getElementById("divCargando").style.display = "none";
					
			} 
		}
		ajax.send("iddocumento="+iddocumento+"&numero_documento="+numero_documento+"&numero_factura="+numero_factura+"&numero_control="+numero_control+"&fecha_factura="+fecha_factura+"&exento="+exento+"&base="+base+"&impuesto="+impuesto+"&total="+total+"&tipo_pago="+tipo_pago+"&estado="+estado+"&ejecutar=registrarDatosBasicos");
	}
}




function mostrarListaRetencionesAplicadas(iddocumento){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaRetencionesAplicadas").innerHTML=ajax.responseText;
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("iddocumento="+iddocumento+"&ejecutar=mostrarListaRetencionesAplicadas");	
}



function restarSustraendo(){
	var base_calculo = parseFloat(document.getElementById('base_calculo_oculto').value);
	var divisor = parseFloat(document.getElementById('divisor_retencion').value);
	var porcentaje = parseFloat(document.getElementById('porcentaje_retencion').value);
	var factor = parseFloat(document.getElementById('factor_oculto').value);
	var sustraendo = parseFloat(document.getElementById('sustraendo_oculto').value);
	var total = ((((base_calculo*porcentaje)/divisor)-factor)-sustraendo);
	document.getElementById('total_retencion').value = total;
	formatoNumero("total_retencion", "total_retencion_oculto");
}





function procesarRetencion(idretencion, iddocumento){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value);
					document.getElementById('estado').value = 'procesado';
				}else{
					mostrarMensajes("error", "Disculpe no se pudo Procesar la retencion");
				}
				document.getElementById('codigo_retencion').value = '';
				document.getElementById('descipcion_retencion').value = '';
				document.getElementById('base_calculo_retencion').value = '';
				document.getElementById('sustraendo').value = '';
				document.getElementById('factor').value = '';
				if(document.getElementById('monto_fijo_retencion')){
					document.getElementById('monto_fijo_retencion').value = '';	
				}else{
					document.getElementById('porcentaje_retencion').value = '';
					document.getElementById('divisor_retencion').value = '';		
				}		
				document.getElementById('total_retencion').value = '';	
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("idretencion="+idretencion+"&iddocumento="+iddocumento+"&ejecutar=procesarRetencion");	
}




function anularRetencion(idretencion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value);
				}else{
					mostrarMensajes("error", "Disculpe no se pudo Anular la retencion");
				}
				document.getElementById('codigo_retencion').value = '';
				document.getElementById('descipcion_retencion').value = '';
				document.getElementById('base_calculo_retencion').value = '';
				document.getElementById('sustraendo').value = '';
				document.getElementById('factor').value = '';
				if(document.getElementById('monto_fijo_retencion')){
					document.getElementById('monto_fijo_retencion').value = '';	
				}else{
					document.getElementById('porcentaje_retencion').value = '';
					document.getElementById('divisor_retencion').value = '';		
				}		
				document.getElementById('total_retencion').value = '';	
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("idretencion="+idretencion+"&ejecutar=anularRetencion");	
}


function eliminarRelacionRetencion(relacionRetenciones){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value);
					var respuesta = ajax.responseText.split("-");
					document.getElementById('divTotalRetenciones').innerHTML = parseFloat(respuesta[1]);
					if(respuesta[2].indexOf("si") != -1){
						document.getElementById('exento').disabled = false;
						document.getElementById('sub_total').disabled = false;
						document.getElementById('impuesto').disabled = false;
						document.getElementById('total').disabled = false;
						document.getElementById('estado').value = "procesado";
					}
				}else{
					mostrarMensajes("error", "Disculpe no se pudo Eliminar la Aplicacion de retencion");
				}
				document.getElementById('codigo_retencion').value = '';
				document.getElementById('descipcion_retencion').value = '';
				document.getElementById('base_calculo_retencion').value = '';
				document.getElementById('sustraendo').value = '';
				document.getElementById('factor').value = '';
				if(document.getElementById('monto_fijo_retencion')){
					document.getElementById('monto_fijo_retencion').value = '';	
				}else{
					document.getElementById('porcentaje_retencion').value = '';
					document.getElementById('divisor_retencion').value = '';		
				}		
				document.getElementById('total_retencion').value = '';
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("relacionRetenciones="+relacionRetenciones+"&ejecutar=eliminarRelacionRetencion");	
}





function modificarRelacionRetencion(idretenciones, idtipo_retencion, porcentaje_aplicado, base_calculo, sustraendo, factor, monto_retenido, numero_retencion, porcentaje_impuesto, idrelacion_retenciones, monto_retenido_viejo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value);
					actualizarMontoRetenido(idrelacion_retenciones);
				}else{
					mostrarMensajes("error", "Disculpe no se pudo Eliminar la Aplicacion de retencion");
				}
				document.getElementById('codigo_retencion').value = '';
				document.getElementById('descipcion_retencion').value = '';
				document.getElementById('base_calculo_retencion').value = '';
				document.getElementById('sustraendo').value = '';
				document.getElementById('factor').value = '';
				if(document.getElementById('monto_fijo_retencion')){
					document.getElementById('monto_fijo_retencion').value = '';	
				}else{
					document.getElementById('porcentaje_retencion').value = '';
					document.getElementById('divisor_retencion').value = '';		
				}		
				document.getElementById('total_retencion').value = '';	
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("idretenciones="+idretenciones+"&idtipo_retencion="+idtipo_retencion+"&porcentaje_aplicado="+porcentaje_aplicado+"&base_calculo="+base_calculo+"&sustraendo="+sustraendo+"&factor="+factor+"&monto_retenido="+monto_retenido+"&numero_retencion="+numero_retencion+"&porcentaje_impuesto="+porcentaje_impuesto+"&idrelacion_retenciones="+idrelacion_retenciones+"&monto_retenido_viejo="+monto_retenido_viejo+"&ejecutar=modificarRelacionRetencion");		
}






function refrescarListaDocumentos(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaDocumentos").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=refrescarListaDocumentos");
}






function actualizarMontoRetenido(idrelacion_retenciones){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("divTotalRetenciones").innerHTML=ajax.responseText;
				refrescarListaDocumentos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrelacion_retenciones="+idrelacion_retenciones+"&ejecutar=actualizarMontoRetenido");			
}



function validarMayor(nombre, nombre_inicial){
	campo = document.getElementById(nombre);
	campoInicial = document.getElementById(nombre_inicial);
	if(campo.value > campoInicial.value){
		mostrarMensajes("error", "Disculpe valor ingresado no puede ser mayor al valor pendiente por pagar que es de "+campoInicial.value);
		campo.value = campoInicial.value;
	}	
}


function validarEstado(){
	if(document.getElementById('estado').value == "procesado"){
		document.getElementById('exento').disabled = false;
		document.getElementById('sub_total').disabled = false;
		document.getElementById('impuesto').disabled = false;
		
		document.getElementById("mostrarImprimir").style.display="block";
	}else{
		document.getElementById('exento').disabled = true;
		document.getElementById('sub_total').disabled = true;
		document.getElementById('impuesto').disabled = true;
	}
	document.getElementById('select_tipo_pago').value = "0";
}