// JavaScript Document


function cargarCuentasBancarias(banco){
		var forma_preimpresa = document.getElementById('forma_preimpresa').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
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
		ajax.send("banco="+banco+"&forma_preimpresa="+forma_preimpresa+"&ejecutar=cargarCuentasBancarias");
}



function calcularPorcentaje(porcentaje){
		if(document.getElementById('modo_cancelacion').value == 'Porcentual'){
			var monto_orden_pago = parseFloat(document.getElementById('monto_restante').value);
			var decimal = parseInt(porcentaje)/100;
			var total_a_mostrar = monto_orden_pago*decimal;
			document.getElementById('monto_cheque').value = total_a_mostrar;
			formatoNumero('monto_cheque', 'monto_cheque_oculto');
		}else{
			document.getElementById('monto_cheque').value = porcentaje;
			formatoNumero('monto_cheque', 'monto_cheque_oculto');
		}
	if(parseFloat(document.getElementById('monto_cheque_oculto').value) > parseFloat(document.getElementById('monto_restante').value)){
		mostrarMensajes("error", "Disculpe el monto a pagar no puede ser mayor que el monto restante");
	}
}



function formatoNumero(idcampo, campoOculto) {
var res =  document.getElementById(idcampo).value;
document.getElementById(campoOculto).value = res;
if (document.getElementById(idcampo).value >= 0 && document.getElementById(idcampo).value <= 99999999999)  {
	resultado = parseFloat(res).toFixed(2).toString();
	resultado = resultado.split(".");
	var cadena = ""; cont = 1
	for(m=resultado[0].length-1; m>=0; m--){
		cadena = resultado[0].charAt(m) + cadena
		cont%3 == 0 && m >0 ? cadena = "." + cadena : cadena = cadena
		cont== 3 ? cont = 1 :cont++
	}
	document.getElementById(idcampo).value = cadena + "," + resultado[1];
} else {
	document.getElementById(idcampo).value = "0";
	document.getElementById(idcampo).focus();
}
}




function ingresarEmisionPagos(idtipo_documento, forma_pago, porcentaje_pago, numero_parte_pago, idtipo_movimiento_bancario, idorden_pago, idcuenta_bancaria, idcheques_cuentas_bancarias, numero_cheque, divCheque, posicion, fecha_cheque, monto_cheque, beneficiario, ci_beneficiario, formato_imprimir, modo_cancelacion, controlar_cheque){
	if (controlar_cheque == 1){
		if(divCheque > 0){
			numero_cheque = numero_cheque+divCheque;
		}
	}

	//alert(numero_cheque);
	beneficiario=escape(beneficiario);

	if(confirm("Realmente desea procesar el cheque?")){
	if(idtipo_documento == 0){
		mostrarMensajes("error", "Disculpe debe Seleccionar el Tipo de Documento");
	}else if(idtipo_movimiento_bancario == 0){
		mostrarMensajes("error", "Disculpe debe seleccionar el Tipo de Movimiento Bancario");
	}else if(idcuenta_bancaria == 0){
		mostrarMensajes("error", "Disculpe debe Seleccionar la Cuenta Bancaria");
	}else if(fecha_cheque == ""){
		mostrarMensajes("error", "Por favor Seleccione la fecha del cheque");
	}else if(monto_cheque == ''){
		mostrarMensajes("error", "Disculpe debe ingresar el Monto del Cheque");
	}else if(parseFloat(document.getElementById('monto_cheque_oculto').value) > parseFloat(document.getElementById('monto_restante').value)){
		mostrarMensajes("error", "Disculpe el monto a pagar no puede ser mayor que el monto restante");
	}else{
		tabla_debe = document.getElementById('tabla_debe').value;
		idcuenta_debe = document.getElementById('idcuenta_debe').value;
		tabla_haber = document.getElementById('tabla_haber').value;
		idcuenta_haber = document.getElementById('idcuenta_haber').value;

		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText.indexOf("exito") != -1){
					partes = ajax.responseText.split("|.|");
					document.getElementById('id_emision_pago').value = partes[1];
					mostrarMensajes("exito", "Pago registrado con exito");
					bloquearCampos();
					//document.getElementById('divNumeroCheque').innerHTML = '';
					document.getElementById('lista_ordenes_pago').style.display = 'none';
					document.getElementById('tipo_documento').value = 0;
					document.getElementById('botonProcesar').style.display='none';
					document.getElementById('botonAnular').style.display='block';
				}else if(ajax.responseText.indexOf("existe") != -1){
					mostrarMensajes("error", "Disculpe el digito consecutivo del numero del cheque que intenta realizar ya fue procesado en otro pago, por favor verifique e intente de nuevo");
				}else{
					mostrarMensajes("error", "Error: "+ajax.responseText);

				}
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("divCheque="+divCheque+"&idtipo_documento="+idtipo_documento+"&forma_pago="+forma_pago+"&porcentaje_pago="+porcentaje_pago+"&numero_parte_pago="+numero_parte_pago+"&idtipo_movimiento_bancario="+idtipo_movimiento_bancario+"&idorden_pago="+idorden_pago+"&idcuenta_bancaria="+idcuenta_bancaria+"&idcheques_cuentas_bancarias="+idcheques_cuentas_bancarias+"&numero_cheque="+numero_cheque+"&fecha_cheque="+fecha_cheque+"&monto_cheque="+monto_cheque+"&beneficiario="+beneficiario+"&ci_beneficiario="+ci_beneficiario+"&formato_imprimir="+formato_imprimir+"&id_chequera="+id_chequera+"&modo_cancelacion="+modo_cancelacion+"&tabla_debe="+tabla_debe+"&idcuenta_debe="+idcuenta_debe+"&tabla_haber="+tabla_haber+"&idcuenta_haber="+idcuenta_haber+"&ejecutar=ingresarEmisionPagos");
	}
	}
}




function bloquearCampos(){
	// BLOQUEAR TODOS LOS CAMPOS
	
	document.getElementById('beneficiario').disabled = true;
	document.getElementById('ci_beneficiario').disabled = true;
	document.getElementById('monto_cheque').disabled = true;
	document.getElementById('numero_orden_pago').disabled = true;
	document.getElementById('monto_orden_pago').disabled = true;
	document.getElementById('banco').disabled = true;
	document.getElementById('cuenta').disabled = true;
	document.getElementById('forma_pago').disabled = true;
	document.getElementById('formato_imprimir').disabled = true;
	document.getElementById('tipo_movimiento').disabled = true;	
	document.getElementById('porcentaje').disabled = true;	
	document.getElementById('nro_parte').disabled = true;
	//document.getElementById('chequera').disabled = true;
	document.getElementById('nro_cheque').disabled = true;
	
	//limpiarCampos();
}


function limpiarCampos(){
	document.getElementById('beneficiario').value = "";
	document.getElementById('ci_beneficiario').value = "";
	document.getElementById('monto_cheque').value = "";
	document.getElementById('numero_orden_pago').value = "";
	document.getElementById('monto_orden_pago').value = "";
	document.getElementById('banco').value = 0;
	document.getElementById('cuenta').value = 0;
	document.getElementById('forma_pago').value = 0;
	document.getElementById('formato_imprimir').value = 0;
	document.getElementById('tipo_movimiento').value = 0;	
	document.getElementById('porcentaje').value = "";	
	document.getElementById('nro_parte').value = "";
	//document.getElementById('chequera').value = "";
	document.getElementById('nro_cheque').value = "";
	document.getElementById('id_beneficiario').value = "";
	document.getElementById('id_orden_pago').value = "";
	document.getElementById('monto_orden_pago_oculto').value = "";
	document.getElementById('nro_cheque_oculto').value = "";
	document.getElementById('monto_cheque_oculto').value = "";
	document.getElementById('id_emision_pago').value = "";
}





function mostrarContenido(idtipo_documento, forma_pago, porcentaje_pago, numero_parte_pago, idtipo_movimiento_bancario,
							numero_orden, idcuentas_bancarias, numero_cheque, fecha_cheque, monto_cheque, beneficiario,
							ci_beneficiario, formato_imprimir, chequera, estado, idemision_pagos, idbanco, modo_cancelacion,
							digitos_consecutivos, cantidad_digitos, idasiento_contable){
	//alert(cantidad_digitos);
	//location.href = '../../../principal.php?modulo=7&accion=368';

	document.getElementById('tipo_documento').value = idtipo_documento;
	document.getElementById('forma_pago').value = forma_pago;

	document.getElementById('nro_parte').value = numero_parte_pago;
	document.getElementById('tipo_movimiento').value = idtipo_movimiento_bancario;
	document.getElementById('numero_orden_pago').value = numero_orden;
	if (cantidad_digitos != ''){
		cantidad_digitos = parseInt(cantidad_digitos);
		if(digitos_consecutivos == "inicio"){
			document.getElementById('nro_cheque').size = (9-cantidad_digitos);
			document.getElementById('divNumeroCheque').size = cantidad_digitos+1;
			var numero_cheque_aleatorio = numero_cheque.substring(0, (8-cantidad_digitos));
			var numero_cheque_fijo = numero_cheque.substring((8-cantidad_digitos), 8);

		}else{
			document.getElementById('nro_cheque').size = cantidad_digitos+1;
			document.getElementById('divNumeroCheque').size = (9-cantidad_digitos);
			var numero_cheque_aleatorio = numero_cheque.substring(0, cantidad_digitos);
			var numero_cheque_fijo = numero_cheque.substring(cantidad_digitos, 8);
		}
	}
	document.getElementById('divNumeroCheque').type="hidden";
	document.getElementById('nro_cheque').size = 12;
	document.getElementById('nro_cheque').value = numero_cheque;
	//document.getElementById('divNumeroCheque').value = numero_cheque_fijo;
	document.getElementById('fecha_chequera').value = fecha_cheque;
	document.getElementById('monto_cheque').value = monto_cheque;
	document.getElementById('beneficiario').value = beneficiario;
	document.getElementById('beneficiario').style.fontWeight ='bold';
	document.getElementById('beneficiario').style.fontSize = '12';
	document.getElementById('numero_orden_pago').style.fontWeight ='bold';
	document.getElementById('numero_orden_pago').style.fontSize = '12';
	if (estado == 'anulado'){
		document.getElementById('beneficiario').style.backgroundColor = '#FE2E2E';
		document.getElementById('numero_orden_pago').style.backgroundColor = '#FE2E2E';
	}else{
		document.getElementById('beneficiario').style.backgroundColor = 'white';
		document.getElementById('numero_orden_pago').style.backgroundColor = 'white';
	}
	document.getElementById('ci_beneficiario').value = ci_beneficiario;
	document.getElementById('formato_imprimir').value = formato_imprimir;
	document.getElementById('chequera').value = chequera;
	document.getElementById('id_emision_pago').value = idemision_pagos;
	document.getElementById('banco').value = idbanco;
	cargarCuentasBancarias(idbanco);

	document.getElementById('tipo_documento').disabled = true;

	if(modo_cancelacion == "Porcentual"){
		document.getElementById('porcentaje').value = porcentaje_pago;
		document.getElementById('divTipoPago').innerHTML = 'Porcentaje';
	}else{
		document.getElementById('porcentaje').value = monto_cheque;
		document.getElementById('divTipoPago').innerHTML = 'Monto';
	}

	if (estado != 'anulado'){
		//mostrar_cuenta_contable_tipo_movimiento(idtipo_documento,estado);
		//setTimeout("mostrar_cuenta_contable_cuenta_bancaria("+idcuentas_bancarias+")", 1500);
		//poner_valor();
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById('tabla_cuentas_contables').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
				//bloquearDatos();
			}
		}
		ajax.send("idemision_pagos="+idemision_pagos+"&ejecutar=mostrarProcesado");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById('tabla_cuentas_contables').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
				//bloquearDatos();
			}
		}
		ajax.send("idemision_pagos="+idemision_pagos+"&ejecutar=mostrarAnulado");

	}

	if(estado == "transito"){
		document.getElementById('botonProcesar').style.display = 'none';
		document.getElementById('botonAnular').style.display = 'block';
	}else if(estado == "anulado"){
		document.getElementById('botonProcesar').style.display = 'none';
		document.getElementById('botonAnular').style.display = 'none';
	}else if(estado == "conciliado"){
		document.getElementById('botonProcesar').style.display = 'none';
		document.getElementById('botonAnular').style.display = 'none';
	}
	setTimeout("seleccionarCuenta("+idcuentas_bancarias+")", 1500);

}

function poner_valor(){
	document.getElementById('celda_valor_debe').innerHTML = document.getElementById('monto_cheque').value;
	document.getElementById('celda_valor_haber').innerHTML = document.getElementById('monto_cheque').value;
	document.getElementById('valor_debe').innerHTML = document.getElementById('monto_cheque').value;
	document.getElementById('valor_haber').innerHTML = document.getElementById('monto_cheque').value;
}


function anular(id_emision_pago,clave,fecha_anulacion_pago){
	if(confirm("Realmente desea Anular el Cheque?")){
		/*tabla_debe = document.getElementById('tabla_debe').value;
		idcuenta_debe = document.getElementById('idcuenta_debe').value;
		tabla_haber = document.getElementById('tabla_haber').value;
		idcuenta_haber = document.getElementById('idcuenta_haber').value;*/
		monto_cheque = document.getElementById('monto_cheque').value;
		//alert(tabla_haber);
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					if(ajax.responseText.indexOf("exito") != -1){
						document.getElementById('divPreguntarUsuario').style.display = 'none';
						window.location.href='principal.php?modulo=7&accion=368';
					}else if(ajax.responseText.indexOf("claveIncorrecta") != -1){
						mostrarMensajes("error", "Disculpe Clave incorrecta");
					}else{
						mostrarMensajes("error", "No se pudo anular la orden");
					}
					document.getElementById("verificarClave").value = "";
					document.getElementById("divCargando").style.display = "none";
					//window.location.href='principal.php?modulo=7&accion=368';
			} 
		}
		ajax.send("id_emision_pago="+id_emision_pago+"&clave="+clave+"&fecha_anulacion_pago="+
					fecha_anulacion_pago+"&monto_cheque="+monto_cheque+"&ejecutar=anular");
	}
}






function seleccionarCuenta(idcuentas_bancarias){
	document.getElementById('cuenta').disabled = true;
	document.getElementById('cuenta').value = idcuentas_bancarias;	
}







function buscarOrdenes(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaOrdenes").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("campoBuscar="+campoBuscar+"&ejecutar=buscarOrdenes");
}




function buscarNomina(){
	//alert("entro");
	var idorden_pago = document.getElementById('id_orden_pago').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('botonProcesar').style.display='none';
			document.getElementById('lista_ordenes_pago').style.display = 'none';
			document.getElementById('div_nomina').style.display = "block";
			document.getElementById('control_cheque').style.display = "none";
			document.getElementById("div_nomina").innerHTML = ajax.responseText;
			
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_pago="+idorden_pago+"&ejecutar=buscarNomina");
}



function generar_cheque_nominaa(idtrabajador){
	//alert("entro");
	idcuenta_bancaria = document.getElementById('cuenta').value;
	if(idcuenta_bancaria == 0){
		mostrarMensajes("error", "Disculpe debe Seleccionar la Cuenta Bancaria");
	}else{
		numero_cheque = document.getElementById('numero_cheque'+idtrabajador).value;
		if(numero_cheque == ''){
			mostrarMensajes("error", "Disculpe debe Colocar el Numero del Cheque");
		}else{
			idtipo_documento = document.getElementById('tipo_documento').value;
			forma_pago = document.getElementById('forma_pago').value;
			porcentaje_pago = document.getElementById('porcentaje').value;
			numero_parte_pago = document.getElementById('nro_parte').value;
			idtipo_movimiento_bancario = document.getElementById('tipo_movimiento').value;
			idorden_pago = document.getElementById('id_orden_pago').value;
			idcheques_cuentas_bancarias = document.getElementById('id_chequera').value; 
			fecha_cheque = document.getElementById('fecha_cheque_individual'+idtrabajador).value;
			monto_cheque = document.getElementById('monto_cheque_individual'+idtrabajador).value;
			beneficiario = document.getElementById('beneficiario_individual'+idtrabajador).value; 
			ci_beneficiario = document.getElementById('cedula_individual'+idtrabajador).value;
			formato_imprimir = document.getElementById('formato_imprimir').value;
			modo_cancelacion= document.getElementById('modo_cancelacion').value;
			
		//alert(numero_cheque);
			beneficiario=escape(beneficiario);
			
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');	
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					if(ajax.responseText.indexOf("exito") != -1){
						//alert(ajax.responseText);
						partes = ajax.responseText.split("|.|");
						document.getElementById('id_emision_pago'+idtrabajador).value = partes[1];
						document.getElementById('generar_cheque_nomina'+idtrabajador).style.display='none';
						document.getElementById('imprimir_cheque_nomina'+idtrabajador).style.display='block';
						document.getElementById('anular_cheque_nomina'+idtrabajador).style.display='block';
					}else if(ajax.responseText.indexOf("existe") != -1){
					mostrarMensajes("error", "Disculpe el numero del cheque que intenta ingresar ya fue procesado en otro pago, por favor verifique e intente de nuevo");
					}
					document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("idtipo_documento="+idtipo_documento+"&forma_pago="+forma_pago+"&porcentaje_pago="+porcentaje_pago+"&numero_parte_pago="+numero_parte_pago+"&idtipo_movimiento_bancario="+idtipo_movimiento_bancario+"&idorden_pago="+idorden_pago+"&idcuenta_bancaria="+idcuenta_bancaria+"&idcheques_cuentas_bancarias="+idcheques_cuentas_bancarias+"&numero_cheque="+numero_cheque+"&fecha_cheque="+fecha_cheque+"&monto_cheque="+monto_cheque+"&beneficiario="+beneficiario+"&ci_beneficiario="+ci_beneficiario+"&formato_imprimir="+formato_imprimir+"&modo_cancelacion="+modo_cancelacion+"&idtrabajador="+idtrabajador+"&ejecutar=generar_cheque_nomina");
		}
	}
}


function anular_cheque_nominaa(idtrabajador){
	id_emision_pago = document.getElementById('id_emision_pago'+idtrabajador).value;
	if(confirm("Realmente desea Anular el Cheque?")){
		tabla_debe = document.getElementById('tabla_debe').value;
		idcuenta_debe = document.getElementById('idcuenta_debe').value;
		tabla_haber = document.getElementById('tabla_haber').value;
		idcuenta_haber = document.getElementById('idcuenta_haber').value;
		monto_cheque = document.getElementById('monto_cheque_individual'+idtrabajador).value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText.indexOf("exito") != -1){
						//document.getElementById('divPreguntarUsuario').style.display = 'none';
						document.getElementById('numero_cheque'+idtrabajador).value = '';
						document.getElementById('generar_cheque_nomina'+idtrabajador).style.display='block';
						document.getElementById('imprimir_cheque_nomina'+idtrabajador).style.display='none';
						document.getElementById('anular_cheque_nomina'+idtrabajador).style.display='none';
					}else{
						mostrarMensajes("error", "No se pudo anular el cheque");
					}
					
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_emision_pago="+id_emision_pago+"&tabla_debe="+tabla_debe+"&idcuenta_debe="+idcuenta_debe+"&tabla_haber="+tabla_haber+"&idcuenta_haber="+idcuenta_haber+"&monto_cheque="+monto_cheque+"&ejecutar=anular_cheque_nominaa");
	}
}


function imprimir_cheque_nominaa(idtrabajador){
	document.getElementById('cuadroChequeIndividual').style.display = 'block';
	var id_emision_pago=document.getElementById('id_emision_pago'+idtrabajador).value;
	var formato = 'emitir_pagos_cheque2';
	//window.pdf.location.href="lib/"+carpeta+"/tesoreria.php?nombre="+formato+"&id_emision_pago="+id_emision_pago+"&estado=procesado";
	window.pdfPrincipal.location.href="lib/reportes/tesoreria/reportes.php?nombre="+formato+"&id_emision_pago="+id_emision_pago+"&estado=procesado";
	
	//document.getElementById('cuadroChequeIndividual').innerHTML="<table align='center' style='margin-top:0px; background-color:#00CCFF; border:#003366 solid 2px; -moz-border-radius: 8px;'><tr><td><img src='imagenes/validar.png'></td><td style='color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:12px'>"+idtrabajador+"</td></tr></table>";	
	
}




function mostrarPDF(carpeta) {
	var id_emision_pago=document.getElementById("id_emision_pago").value;
	var numero_orden_pago=document.getElementById("numero_orden_pago").value;
	var formato_imprimir=document.getElementById("formato_imprimir").value;
	var optPDF=document.getElementById("optPDF");	
	var optCheque=document.getElementById("optCheque");		
	var optOficio=document.getElementById("optOficio");		
	var optOrden=document.getElementById("optOrden");	
	var optOrdenPago=document.getElementById("optOrdenPago");
	var optRetenciones=document.getElementById("optRetenciones");
	var optContable=document.getElementById("optContable");
	var pdf=document.getElementById("pdf");	
	if (id_emision_pago!="") {
		//	-------------------------
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_retenciones_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
			}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				partes = ajax.responseText.split("-");
					//if(ajax.responseText=="SI" || ajax.responseText=="SI ORDEN") optRetenciones.style.display='block';
					if(partes[1]=="SI") optRetenciones.style.display='block';
					//if (ajax.responseText=="SI ORDEN" || ajax.responseText=="NO ORDEN") optOrdenPago.style.display='block';
					if(partes[0]=="SI ORDEN") optOrdenPago.style.display='block';
			} 
		}
		ajax.send("id_emision_pago="+id_emision_pago+"&numero_orden_pago="+numero_orden_pago);
		//	--------------------------
		optPDF.style.display="block";
		pdf.style.display="block";
		optContable.style.display='block'
		if (formato_imprimir=="Cheque") {
			optCheque.style.display='block'	
			optOrden.style.display='block'
		}
		else if (formato_imprimir=="Oficio") {	
			optOficio.style.display='block'		
			optOrdenOficio.style.display='block'	
		}
	} else mostrarMensajes("error", "¡Error: Debe seleccionar un cheque!");
}

function imprimirPdf(formato, carpeta) {
	var id_emision_pago=document.getElementById("id_emision_pago").value;
	
	//window.pdf.location.href="lib/"+carpeta+"/tesoreria.php?nombre="+formato+"&id_emision_pago="+id_emision_pago+"&estado=procesado";
	window.pdf.location.href="lib/reportes/tesoreria/reportes.php?nombre="+formato+"&id_emision_pago="+id_emision_pago+"&estado=procesado";
}

function imprimirRtf(formato, carpeta) {
	var id_emision_pago=document.getElementById("id_emision_pago").value;
	window.pdf.location.href="lib/"+carpeta+"/generar_rtf.php?nombre="+formato+"&id_emision_pago="+id_emision_pago;
}






function cargarNumerosChequeras(idcuentas_bancarias){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('divNroChequeras').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idcuentas_bancarias="+idcuentas_bancarias+"&ejecutar=cargarNumerosChequeras");	
}



function aumentarNumero(){
	if(document.getElementById('posicion_nro_cheque').value == 'inicio'){
		var campoMostrar = document.getElementById('nro_cheque').value;	
		var id = "nro_cheque";
	}else{
		var campoMostrar = document.getElementById('divNumeroCheque').value;
		var id = "divNumeroCheque";
	}
	
	var div = document.getElementById('numero_paralelo').value;
	var n = document.getElementById('nro_mayor_cheque_oculto').value;
	var tamano_inicial = div.length;
	//div = parseFloat(div);
	var numero_actual = parseFloat(div);
	var numero_siguiente = (parseFloat(div)+1);
	var tamano_siguiente = String(numero_siguiente).length;
	var numero_mayor_cheque = parseFloat(document.getElementById('nro_mayor_cheque_oculto').value);
	var numero_maximo = "";
	var num_new = "";
	
	var numero_nuevo = "";
	var numero_menor = parseFloat(document.getElementById('nro_cheque_oculto').value);
	var cantidad_digitos = parseFloat(document.getElementById('cantidad_digitos').value);
	var ceros = '';
	
	/*for(i=0; i<=tamano_inicial; i++){
		numero_maximo = numero_maximo+"9";
	}*/
	
	
	var tamanio = (8-parseFloat(cantidad_digitos));
	
	//alert(tamanio);
	
	if(parseFloat(campoMostrar) != n){
	if(tamano_siguiente > tamanio){
		var resto = String(numero_siguiente).substring(1,4);
	}else{
		var resto = numero_siguiente;	
	}
	
	
	document.getElementById('numero_paralelo').value = (parseFloat(document.getElementById('numero_paralelo').value)+1);
	document.getElementById(id).value = resto;	
	}
	
	
	
	
	
	
	/*
	var resultado = (parseFloat(div)+1);
	var res = resultado;
	
	if((parseInt()+parseInt(resultado)) < 100){
		
		if(document.getElementById('nro_mayor_cheque_oculto').value < resultado){
			document.getElementById('divNumeroCheque').value = document.getElementById('nro_mayor_cheque_oculto').value;
		}else{
			//alert(resultado);
			if(parseInt(resultado) < 10){
				//alert(parseFloat(document.getElementById('divNumeroCheque').value));
				var r = parseFloat(document.getElementById('divNumeroCheque').value)+1;
				//alert("09"+r);
				document.getElementById('divNumeroCheque').value = "0"+r;
				
			}else{
				document.getElementById('divNumeroCheque').value = res;		
			}
			
		}
	}else{
		
		if(parseInt(resultado) >= 100){
			var res = (resultado-100);
			if(parseInt(res) < 10){
				res = "0"+res;
			}
				document.getElementById('divNumeroCheque').value = res;	
			
		}else{
			if(parseInt(res) < 10){
				res = "0"+res;
			}
			document.getElementById('divNumeroCheque').value = res;		
		}
	}
	
	*/
}





function disminuirNumero(){
	if(document.getElementById('posicion_nro_cheque').value == 'inicio'){
		var div = document.getElementById('nro_cheque').value;	
		var id = "nro_cheque";
	}else{
		var div = document.getElementById('divNumeroCheque').value;
		var id = "divNumeroCheque";
	}
	
	//var div = document.getElementById('divNumeroCheque').value;
	var resultado = (parseInt(div)-1);
	
	
	if(document.getElementById('nro_cheque_oculto').value > resultado){
		document.getElementById(id).value = document.getElementById('nro_cheque_oculto').value;
		document.getElementById('numero_paralelo').value = document.getElementById('nro_cheque_oculto').value;
	}else{
		document.getElementById(id).value = resultado;	
	}
}



function mostrar_cuenta_contable_tipo_movimiento(idtipos_documentos,estado){

		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				var partes = ajax.responseText.split("|.|");
				if (estado != 'anulado'){
					document.getElementById('celda_cuenta_debe').innerHTML = partes[0];
					document.getElementById('idcuenta_debe').value = partes[1];
					document.getElementById('tabla_debe').value = partes[2];
					document.getElementById('celda_cuenta_haber').innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+partes[3];
					document.getElementById('idcuenta_haber').value = partes[4];
					document.getElementById('tabla_haber').value = partes[5];
				}else{
					document.getElementById('celda_cuenta_debe').innerHTML = partes[3];
					document.getElementById('idcuenta_debe').value = partes[4];
					document.getElementById('tabla_debe').value = partes[5];
					document.getElementById('celda_cuenta_haber').innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+partes[0];
					document.getElementById('idcuenta_haber').value = partes[1];
					document.getElementById('tabla_haber').value = partes[2];
				}
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("idtipos_documentos="+idtipos_documentos+"&ejecutar=mostrar_cuenta_contable_tipo_movimiento");
}


function mostrar_cuenta_contable_cuenta_bancaria(idcuentas_bancarias,estado){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/emision_pagos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				var partes = ajax.responseText.split("|.|");

					if (document.getElementById('idcuenta_debe').value == '0'){
						document.getElementById('celda_cuenta_debe').innerHTML = partes[0];
						document.getElementById('idcuenta_debe').value = partes[1];
						document.getElementById('tabla_debe').value = partes[2];
					}else{
						document.getElementById('celda_cuenta_haber').innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+partes[0];
						document.getElementById('idcuenta_haber').value = partes[1];
						document.getElementById('tabla_haber').value = partes[2];
					}

				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("idcuentas_bancarias="+idcuentas_bancarias+"&ejecutar=mostrar_cuenta_contable_cuenta_bancaria");
}
