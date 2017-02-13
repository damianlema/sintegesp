// JavaScript Document



function consultarContribuyente(idcontribuyente){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('datos_contribuyente').innerHTML = ajax.responseText;
				document.getElementById('idcontribuyente').value = idcontribuyente;
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=consultarContribuyente");	
}




function ingresarDatosBasicos(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	if(idcontribuyente == ""){
		mostrarMensajes("error", "Disculpe debe seleccionar el contribuyente");
	}else{
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				
			//	muestro el boton imprimir
			document.getElementById("btImprimir").style.visibility = "visible";
			
			document.getElementById("boton_siguiente").style.display = 'none';
			document.getElementById("boton_procesar").style.display = 'block';
			document.getElementById("boton_anular").style.display = 'none';
			document.getElementById('idpagos_recaudacion').value = ajax.responseText;
			consultarDeudas();
		}
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=ingresarDatosBasicos");	
	}
}



function consultarDeudas(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var idpagos_recaudacion = document.getElementById("idpagos_recaudacion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('celda_pagos_pendientes').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idpagos_recaudacion="+idpagos_recaudacion+"&idcontribuyente="+idcontribuyente+"&ejecutar=consultarDeudas");	

	
}



function seleccionarPago(conceptos_var, montos_var, moras_var){
	var conceptos = document.getElementById(conceptos_var).value;
	var montos = document.getElementById(montos_var).value;
	var moras = document.getElementById(moras_var).value;
	var idpagos_recaudacion = document.getElementById('idpagos_recaudacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			sumarTotalPagar();
			//document.getElementById('celda_pagos_pendientes').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idpagos_recaudacion="+idpagos_recaudacion+"&montos="+montos+"&conceptos="+conceptos+"&moras="+moras+"&ejecutar=seleccionarPago");
}



function sumarTotalPagar(){
	var idpagos_recaudacion = document.getElementById('idpagos_recaudacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById('total_a_pagar').innerHTML = partes[0];
			document.getElementById('total').value = partes[1];
			document.getElementById('total_mora').value = partes[2];
			actualizarTotales();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idpagos_recaudacion="+idpagos_recaudacion+"&ejecutar=sumarTotalPagar");
}






function actualizarTotales(){
	var total = parseFloat(document.getElementById('total').value);
	var descuento = parseFloat(document.getElementById('descuento').value);
	var total_mora = parseFloat(document.getElementById('total_mora').value);
	var total_pagar = parseFloat(total)+parseFloat(total_mora)-parseFloat(descuento);
	document.getElementById('total_pagar').value = total_pagar;
}






function abrirFormularioPago(){
	var formulario = document.getElementById('formulario_deudas');
	var elementos = formulario.elements;
	var seleccionados = 0;
	for(i=0;i<elementos.length;i++){
		if(elementos[i].type == "checkbox"){
			if(elementos[i].checked == true){
				seleccionados++;
			}	
		}	
	}
	
	if(seleccionados > 0){
		document.getElementById('planilla_datos_pago').style.display = 'block';	
	}else{
		mostrarMensajes("error", "Disculpe debe seleccionar al menos un pago a cancelar");
	}
		
}



function procesarPago(){
	var idpagos_recaudacion = document.getElementById('idpagos_recaudacion').value;
	var fecha_pago = document.getElementById('fecha_pago').value;
	var total = document.getElementById('total').value;
	var descuento = document.getElementById('descuento').value;
	var total_mora = document.getElementById('total_mora').value;
	var total_pagar = document.getElementById('total_pagar').value;
	var banco = document.getElementById('banco').value;
	var nro_cuenta = document.getElementById('nro_cuenta').value;
	var especie = document.getElementById('especie').value;
	var recibido_por = document.getElementById('recibido_por').value;
	var ci_recibido = document.getElementById('ci_recibido').value;
	
	
	var banco_pago = document.getElementById('banco_pago').value;
	var nro_cuenta_pago = document.getElementById('nro_cuenta_pago').value;
	var nro_documento = document.getElementById('nro_documento').value;
	var nro_cheque_tarjeta = document.getElementById('nro_cheque_tarjeta').value;
	var tipo_tarjeta = document.getElementById('tipo_tarjeta').value;
	var fecha = document.getElementById('fecha').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "El pago ah sido procesado con exito");
				
			//	muestro el boton imprimir
			document.getElementById("btImprimir").style.visibility = "visible";
			
			document.getElementById('numero_planilla').innerHTML = ajax.responseText;
			document.getElementById('numero_orden').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
			document.getElementById('boton_pagar').style.display='none';
			document.getElementById('boton_procesar').style.display='none';
			document.getElementById('boton_anular').style.display='block';
			document.getElementById("descuento").disabled = false;
			document.getElementById('estado_orden').innerHTML = 'Procesado';
			
		}
		
	}
	ajax.send("idpagos_recaudacion="+idpagos_recaudacion+"&fecha_pago="+fecha_pago+"&total="+total+"&descuento="+descuento+"&total_mora="+total_mora+"&total_pagar="+total_pagar+"&banco="+banco+"&nro_cuenta="+nro_cuenta+"&especie="+especie+"&recibido_por="+recibido_por+"&ci_recibido="+ci_recibido+"&banco_pago="+banco_pago+"&nro_cuenta_pago="+nro_cuenta_pago+"&nro_documento="+nro_documento+"&nro_cheque_tarjeta="+nro_cheque_tarjeta+"&tipo_tarjeta="+tipo_tarjeta+"&fecha="+fecha+"&ejecutar=procesarPago");
	
}




function anularRecaudacion(){
	if(confirm("Seguro desea anular este proceso de pago?")){
		var idpagos_recaudacion = document.getElementById('idpagos_recaudacion').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				consultarRecaudacion(idpagos_recaudacion);
				document.getElementById('boton_anular').style.display = 'none';
			}
			
		}
		ajax.send("idpagos_recaudacion="+idpagos_recaudacion+"&ejecutar=anularRecaudacion");
	}
}


function consultarCuentaBancaria(idbanco, cod){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					document.getElementById('div_nro_cuenta').innerHTML = ajax.responseText;
			}
		}
		ajax.send("idbanco="+idbanco+"&ejecutar=consultarCuentaBancaria");	
}



function consultarRecaudacion(idpagos_recaudacion){
	document.getElementById("idpagos_recaudacion").value = idpagos_recaudacion;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/recaudacion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById('idcontribuyente').value = partes[0];
			consultarContribuyente(partes[0]);
			document.getElementById('numero_orden').innerHTML = partes[1];
			document.getElementById('fecha_pago').value = partes[2];
			document.getElementById('total').value = partes[3];
			document.getElementById('descuento').value = partes[4];
			document.getElementById('total_mora').value = partes[5];
			document.getElementById('total_pagar').value = partes[6];
			document.getElementById('total_a_pagar').innerHTML = parseFloat(partes[6]);
			document.getElementById('estado_orden').innerHTML = partes[7];
				
			//	muestro el boton imprimir
			document.getElementById("btImprimir").style.visibility = "visible";
			
			if(partes[7] == "elaboracion"){
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById('boton_procesar').style.display = 'block';	
				document.getElementById('titulo').innerHTML = '<strong>Pagos Pendientes</strong>';
			}else if(partes[7] == "procesado"){
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'block';
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('titulo').innerHTML = '<strong>Pagos Realizados</strong>';
			}else if(partes[7] == "anulado"){
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById('boton_procesar').style.display = 'none';		
				document.getElementById('titulo').innerHTML = '<strong>Pagos Realizados</strong>';
			}
			consultarDeudas();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idpagos_recaudacion="+idpagos_recaudacion+"&ejecutar=consultarRecaudacion");	
}