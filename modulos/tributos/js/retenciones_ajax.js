// JavaScript Document

function validarVacio(valor, id){
	if(valor == ""){
		document.getElementById(id).value= 0;	
		document.getElementById(id+"_oculto").value= 0;	
	}	
}



function seleccionarDocumento(numero_orden, fecha_orden, id_beneficiario, nombre_beneficiario, nro_factura, nro_control, fecha_factura, exento, sub_total, impuesto, total, exento_oculto, subtotal_oculto, impuesto_oculto, total_oculto, iddocomento, porcentaje_impuesto, anio){
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
	document.getElementById('anio_documento').value = anio;
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
	document.getElementById(campoTotal).value = total;
	formatoNumero(campoTotalMostrado, campoTotal);
}





function actualizarMontosFijos(monto_fijo){
	document.getElementById('total_retencion').value = monto_fijo;
	formatoNumero('total_retencion', 'total_retencion_oculto');
	formatoNumero('monto_fijo_retencion', 'monto_fijo_oculto');
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
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrelacion_retenciones="+idrelacion_retenciones+"&ejecutar=mostrarRetencionModificar");
}




function mostrarRetencion(id_tipo_retencion, exento, sub_total, impuesto, total, destino){


		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("tablaDatosRetencion").innerHTML=ajax.responseText;
					refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_tipo_retencion="+id_tipo_retencion+"&exento="+exento+"&sub_total="+sub_total+"&impuesto="+impuesto+"&total="+total+"&destino="+destino+"&ejecutar=mostrarRetencion");

}







function registrarRetencion(tipo_pago, idretenciones, idtipo_retencion, porcentaje_aplicado, base_calculo, sustraendo, factor, monto_retenido, numero_retencion, porcentaje_impuesto, codigo_concepto){
		var tipoPago;
		//anio = document.getElementById('anio_documento').value;
		var ajax2=nuevoAjax();
		if (document.getElementById('generar_comprobante').checked == true){
			var generar_comprobante = 'si';
		}else{
			var generar_comprobante = 'no';
		}
		//alert(tipo_pago);
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
							mostrarMensajes("error", "Disculpe el tipo de pago no es igual al que se registro en retenciones anteriores");
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
										//alert(ajax.responseText);
											if(ajax.responseText.indexOf("supera") != -1){
												mostrarMensajes("error", "El monto retenido SUPERA el MAXIMO A RETENER para ese TIPO DE RETENCION");
											}else{
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
											}
										document.getElementById('codigo_concepto').value = '';
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
										
									} 
								}
								ajax.send("idretenciones="+idretenciones+"&tipo_pago="+tipo_pago+"&idtipo_retencion="+idtipo_retencion+"&porcentaje_aplicado="+porcentaje_aplicado+"&base_calculo="+base_calculo+"&sustraendo="+sustraendo+"&factor="+factor+"&monto_retenido="+monto_retenido+"&numero_retencion="+numero_retencion+"&codigo_concepto="+codigo_concepto+"&generar_comprobante="+generar_comprobante+"&porcentaje_impuesto="+document.getElementById('porcentaje_impuesto').value+"&iddocumento="+document.getElementById('iddocumento').value+"&anio="+document.getElementById('anio').value+"&ejecutar=registrarRetencion");
						}
						
						refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
						document.getElementById('estado').value = 'elaboracion';
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax2.send("iddocumento="+document.getElementById('iddocumento').value+"&tipo_pago="+tipo_pago+"&ejecutar=consultarTipoPago");
}






function registrarDatosBasicos(iddocumento, numero_documento, numero_factura, numero_control, fecha_factura, exento, base, impuesto, porcentaje_impuesto, total, tipo_pago, estado, anio){
	if(total == 0){
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
					//alert(ajax.responseText);
					document.getElementById('id_retencion').value = ajax.responseText;
						if(tipo_pago == "parcial"){
							document.getElementById('exento').disabled = true;
							document.getElementById('sub_total').disabled = true;
							document.getElementById('impuesto').disabled = true;
							document.getElementById('total').disabled = true;
						}
						refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
					document.getElementById("divCargando").style.display = "none";
					
			} 
		}
		ajax.send("iddocumento="+iddocumento+"&numero_documento="+numero_documento+"&numero_factura="+numero_factura+"&numero_control="+numero_control+"&fecha_factura="+fecha_factura+"&exento="+exento+"&base="+base+"&impuesto="+impuesto+"&total="+total+"&tipo_pago="+tipo_pago+"&estado="+estado+"&anio="+anio+"&ejecutar=registrarDatosBasicos");
	}
}




function mostrarListaRetencionesAplicadas(iddocumento, anio){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				document.getElementById("listaRetencionesAplicadas").innerHTML=ajax.responseText;
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("iddocumento="+iddocumento+"&anio="+anio+"&ejecutar=mostrarListaRetencionesAplicadas");	
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
	anio = document.getElementById('anio_documento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarMensajes("exito", "La retencion se proceso con Exito");
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value, anio);
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
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("idretencion="+idretencion+"&iddocumento="+iddocumento+"&anio="+anio+"&ejecutar=procesarRetencion");	
}




function anularRetencion(idretencion){
	anio = document.getElementById('anio_documento').value
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value, anio);
				}else{
					mostrarMensajes("error", "Disculpe no se pudo Anular la retencion, por el siguiente motivo: "+ajax.responseText);
				}
				
				//document.getElementById('codigo_retencion').value = '';
				//document.getElementById('descipcion_retencion').value = '';
				//document.getElementById('base_calculo_retencion').value = '';
				//document.getElementById('sustraendo').value = '';
				//document.getElementById('factor').value = '';
				//if(document.getElementById('monto_fijo_retencion')){
					//document.getElementById('monto_fijo_retencion').value = '';	
				//}else{
					//document.getElementById('porcentaje_retencion').value = '';
					//document.getElementById('divisor_retencion').value = '';		
				//}		
				//document.getElementById('total_retencion').value = '';	
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";	
				

		} 
	}
	ajax.send("idretencion="+idretencion+"&ejecutar=anularRetencion");	
}


function eliminarRelacionRetencion(relacionRetenciones){
	anio = document.getElementById('anio_documento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value, anio);
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
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("relacionRetenciones="+relacionRetenciones+"&ejecutar=eliminarRelacionRetencion");	
}





function modificarRelacionRetencion(idretenciones, idtipo_retencion, porcentaje_aplicado, base_calculo, sustraendo, factor, monto_retenido, numero_retencion, porcentaje_impuesto, idrelacion_retenciones, monto_retenido_viejo, codigo_concepto, tipo_pago){
	anio = document.getElementById('anio_documento').value;
	if (document.getElementById('generar_comprobante').checked == true){
		var generar_comprobante = 'si';
	}else{
		var generar_comprobante = 'no';
	}
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarListaRetencionesAplicadas(document.getElementById('iddocumento').value, anio);
					actualizarMontoRetenido(idrelacion_retenciones);
				}else{
					mostrarMensajes("error", "Disculpe intenta retener un monto SUPERIOR a los MAXIMO A RETENER para ese TIPO DE RETENCION");
				}
				document.getElementById('codigo_concepto').value = '';
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
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";	
		} 
	}
	ajax.send("idretenciones="+idretenciones+"&tipo_pago="+tipo_pago+"&idtipo_retencion="+idtipo_retencion+"&porcentaje_aplicado="+porcentaje_aplicado+"&base_calculo="+base_calculo+"&sustraendo="+sustraendo+"&factor="+factor+"&monto_retenido="+monto_retenido+"&numero_retencion="+numero_retencion+"&porcentaje_impuesto="+porcentaje_impuesto+"&idrelacion_retenciones="+idrelacion_retenciones+"&monto_retenido_viejo="+monto_retenido_viejo+"&codigo_concepto="+codigo_concepto+"&generar_comprobante="+generar_comprobante+"&ejecutar=modificarRelacionRetencion");		
}





























function refrescarListaDocumentos(campoBuscar, anio){
	//alert(anio);
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
	ajax.send("campoBuscar="+campoBuscar+"&anio="+anio+"&ejecutar=refrescarListaDocumentos");
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
				refrescarListaDocumentos(document.getElementById('campoBuscar').value, document.getElementById('anio').value);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrelacion_retenciones="+idrelacion_retenciones+"&ejecutar=actualizarMontoRetenido");			
}



function validarMayor(nombre, nombre_inicial){
	campo = parseFloat(document.getElementById(nombre).value);
	campoInicial = parseFloat(document.getElementById(nombre_inicial).value);
	if(campo > campoInicial){
		mostrarMensajes("error", "Disculpe valor ingresado no puede ser mayor al valor pendiente por pagar que es de "+campoInicial.value);
		campo.value = campoInicial.value;
	}	
}


function validarEstado(){
	if(document.getElementById('estado').value == "procesado"){
		document.getElementById('exento').disabled = false;
		document.getElementById('sub_total').disabled = false;
		document.getElementById('impuesto').disabled = false;
		
		//document.getElementById("mostrarImprimir").style.display="block";
	}else{
		document.getElementById('exento').disabled = true;
		document.getElementById('sub_total').disabled = true;
		document.getElementById('impuesto').disabled = true;
	}
	//document.getElementById('select_tipo_pago').value = "0";
}

function actualizarFechaNro(nro_factura, nro_control, fecha_factura, id_retenciones){
	String.prototype.trim= function() {//Agrega la función trim al objeto String
    return this.replace(/(^\s*)|(\s*$)/g,""); //elimina espacios a izquierda y derecha
    }
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			respuesta = ajax.responseText.trim();
			if(respuesta == 'exito'){
				mostrarMensajes("exito", "Se actualizaron los datos con Exito");
			}
			if(respuesta == 'existe'){
				mostrarMensajes("error","El numero de factura ya fue registrado para este proveedor");
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nro_factura="+nro_factura+"&nro_control="+nro_control+"&fecha_factura="+fecha_factura+"&id_retenciones="+id_retenciones+"&ejecutar=actualizarFechaNro");			
}