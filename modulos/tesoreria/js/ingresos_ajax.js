// JavaScript Document

function cargarCuentasBancarias(banco){
	excluir_contabilidad = document.getElementById('excluir_contabilidad').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);	
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
		ajax.send("banco="+banco+"&excluir_contabilidad="+excluir_contabilidad+"&ejecutar=cargarCuentasBancarias");
}

function mostrar_cuenta_contable_tipo_movimiento(idtipo_movimiento_bancario,estado, excluir_contabilidad){
	//alert(idtipo_movimiento_bancario);
	if (excluir_contabilidad !='si'){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
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
		ajax.send("idtipo_movimiento_bancario="+idtipo_movimiento_bancario+"&ejecutar=mostrar_cuenta_contable_tipo_movimiento");
	}
}

function mostrar_cuenta_contable_cuenta_bancaria(idcuentas_bancarias,estado, excluir_contabilidad){
	excluir_contabilidad = document.getElementById('excluir_contabilidad').value;
	if (excluir_contabilidad !='si'){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);	
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
}


function poner_valor(excluir_contabilidad){
	if (excluir_contabilidad !='si'){
		document.getElementById('celda_valor_debe').innerHTML = document.getElementById('monto').value;
		document.getElementById('celda_valor_haber').innerHTML = document.getElementById('monto').value;
		document.getElementById('valor_debe').innerHTML = document.getElementById('monto_oculto').value;
		document.getElementById('valor_haber').innerHTML = document.getElementById('monto_oculto').value;
	}
}


function registrarIngresos(idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emisor,concepto, excluir_contabilidad, anio){
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
			idfuente_financiamiento = document.getElementById('fuente_financiamiento').value;
			afecta = document.getElementById('movimiento_afecta').value;
			
			tabla_debe = document.getElementById('tabla_debe').value;
			idcuenta_debe = document.getElementById('idcuenta_debe').value;
			tabla_haber = document.getElementById('tabla_haber').value;
			idcuenta_haber = document.getElementById('idcuenta_haber').value;
			
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					//alert(ajax.responseText);
					document.getElementById("divCargando").style.display = "none";
					bloquearDatos();
					
				} 
			}
			ajax.send("idtipo_movimiento="+idtipo_movimiento+"&idfuente_financiamiento="+idfuente_financiamiento
				+"&numero_documento="+numero_documento+"&fecha="+fecha+"&idbanco="+idbanco+"&idcuentas_bancarias="+idcuentas_bancarias
				+"&monto="+monto+"&emitido_por="+emitido_por+"&ci_emisor="+ci_emisor+"&concepto="+concepto+"&tabla_debe="+tabla_debe
				+"&idcuenta_debe="+idcuenta_debe+"&tabla_haber="+tabla_haber+"&idcuenta_haber="+idcuenta_haber+"&afecta="+afecta
				+"&excluir_contabilidad="+excluir_contabilidad+"&anio="+anio+"&ejecutar=registrarIngresos");
		}
}




function bloquearDatos(){
	document.getElementById('fuente_financiamiento').disabled = true;
	document.getElementById('tipo_movimiento').disabled = true;
	document.getElementById('numero_documento').disabled = true;
	document.getElementById('fecha').disabled = true;
	document.getElementById('banco').disabled = true;
	document.getElementById('cuenta').disabled = true;
	document.getElementById('monto').disabled = true;
	document.getElementById('emitido_por').disabled = true;
	document.getElementById('ci_emisor').disabled = true;
	document.getElementById('concepto').disabled = true;
	document.getElementById('botonProcesar').style.display = 'none';
	
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'block';
	document.getElementById('celda_estado').innerHTML = '<strong>Procesado</strong>';
	
}











function mostrarContenido(id_ingresos, idfuente_financiamiento, idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emitido, concepto, idasiento_contable, estado, anio, excluir_contabilidad){
	cargarCuentasBancarias(idbanco);
	
	
	if (estado == 'procesado'){
		estado_movimiento = 'Procesado';
	}else{
		estado_movimiento = 'Anulado';
	}
	document.getElementById('fuente_financiamiento').value = idfuente_financiamiento;
	document.getElementById('celda_estado').innerHTML = '<strong>'+estado_movimiento+'</strong>';
	document.getElementById('id_ingresos').value = id_ingresos;
	document.getElementById('idasiento_contable').value = idasiento_contable;
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
	setTimeout("seleccionarCuenta("+idcuentas_bancarias+")", 500);
	document.getElementById('tipo_movimiento').disabled = true;
	document.getElementById('banco').disabled = true;
	document.getElementById('cuenta').disabled = true;
	if (estado != 'anulado'){
		document.getElementById('botonModificar').style.display = 'block';
		document.getElementById('botonEliminar').style.display = 'block';
	}else{
		document.getElementById('botonModificar').style.display = 'none';
		document.getElementById('botonEliminar').style.display = 'none';
		document.getElementById('divPreguntarUsuario').style.display = 'none';
	}
	
	document.getElementById('anio').value = anio;
	document.getElementById('excluir_contabilidad').value = excluir_contabilidad;

	if (estado != 'anulado'){
		mostrar_cuenta_contable_tipo_movimiento(idtipo_movimiento,estado);
		mostrar_cuenta_contable_cuenta_bancaria(idcuentas_bancarias,estado);
		poner_valor();
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);	
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
		ajax.send("id_ingresos="+id_ingresos+"&ejecutar=mostrarAnulado");
	}
}


function seleccionarCuenta(idcuentas_bancarias){
	document.getElementById('cuenta').value = idcuentas_bancarias;	
}



function modificarIngresos(id_ingresos, idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emisor,concepto, idasiento_contable, excluir_contabilidad, anio){
		
		idfuente_financiamiento = document.getElementById('fuente_financiamiento').value;
		afecta = document.getElementById('movimiento_afecta').value;
		
		tabla_debe = document.getElementById('tabla_debe').value;
		idcuenta_debe = document.getElementById('idcuenta_debe').value;
		tabla_haber = document.getElementById('tabla_haber').value;
		idcuenta_haber = document.getElementById('idcuenta_haber').value;
	
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					document.getElementById("divCargando").style.display = "none";
					window.location.href = 'principal.php?mmodulo=7&accion=478'
				
			} 
		}
		ajax.send("numero_documento="+numero_documento+"&fecha="+fecha+"&monto="+monto+"&emitido_por="+emitido_por+"&ci_emisor="+ci_emisor
			+"&concepto="+concepto+"&id_ingresos="+id_ingresos+"&idfuente_financiamiento="+idfuente_financiamiento
			+"&idasiento_contable="+idasiento_contable+"&anio="+anio+"&ejecutar=modificarIngresos");	
}



function eliminarIngresos(id_ingresos, idtipo_movimiento, numero_documento, fecha, idbanco, idcuentas_bancarias, monto, emitido_por, ci_emisor,concepto, idasiento_contable, excluir_contabilidad, anio ){
	
		idfuente_financiamiento = document.getElementById('fuente_financiamiento').value;
		afecta = document.getElementById('movimiento_afecta').value;
		
		tabla_debe = document.getElementById('tabla_debe').value;
		idcuenta_debe = document.getElementById('idcuenta_debe').value;
		tabla_haber = document.getElementById('tabla_haber').value;
		idcuenta_haber = document.getElementById('idcuenta_haber').value;
		fecha_anulacion = document.getElementById('fecha_anulacion_ingreso').value;
		clave = document.getElementById('verificarClave').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/ingresos_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se Anulo el Movimiento con exito");
					document.getElementById("divCargando").style.display = "none";
					window.location.href = 'principal.php?mmodulo=7&accion=478'
				//}
				
			} 
		}
		ajax.send("id_ingresos="+id_ingresos+"&idfuente_financiamiento="+idfuente_financiamiento+"&numero_documento="+numero_documento
			+"&fecha="+fecha+"&idbanco="+idbanco+"&idcuentas_bancarias="+idcuentas_bancarias+"&monto="+monto+"&emitido_por="+emitido_por
			+"&ci_emisor="+ci_emisor+"&concepto="+concepto+"&tabla_debe="+tabla_debe+"&idcuenta_debe="+idcuenta_debe
			+"&tabla_haber="+tabla_haber+"&idcuenta_haber="+idcuenta_haber+"&afecta="+afecta+"&clave="+clave
			+"&fecha_anulacion="+fecha_anulacion+"&anio="+anio+"&ejecutar=eliminarIngresos");	
}