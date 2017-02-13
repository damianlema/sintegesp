// JavaScript Document

function listarOrdenes(){
	document.getElementById('numero_documento').value = '';
	document.getElementById('fecha_documento').value = '';
	document.getElementById('beneficiario').value = '';
	document.getElementById('justificacion').value = '';
	document.getElementById('idorden_pago').value = '';
	document.getElementById('boton_siguiente').style.display = 'none';
	var tipo_asiento = document.getElementById('tipo_asiento').value;
	var texto_buscar = document.getElementById('texto_buscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById('lista_ordenes').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&texto_buscar="+texto_buscar+"&ejecutar=listarOrdenes");	
	return false;
}




function guardarDatosBasicos(){
	var idorden_pago = document.getElementById('idorden_pago').value;
	var tipo_asiento = document.getElementById('tipo_asiento_oculto').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById('idcuentas_t').value = ajax.responseText;
			document.getElementById('estado').value = "elaboracion";
			document.getElementById('tabla_debe').style.display = 'block';
			document.getElementById('tabla_haber').style.display = 'block';
			document.getElementById('boton_siguiente').style.display = 'none';
			document.getElementById('boton_procesar').style.display = 'block';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&idorden_pago="+idorden_pago+"&ejecutar=guardarDatosBasicos");
}



function seleccionarCuentaDebe(id, nivel){
	document.getElementById('idcuenta_debe').value = id;
	document.getElementById('nivel_debe').value = nivel;
	document.getElementById('monto_debe').focus();
}



function seleccionarCuentaHaber(id, nivel){
	document.getElementById('idcuenta_haber').value = id;
	document.getElementById('nivel_haber').value = nivel;
	document.getElementById('monto_haber').focus();
}




function ingresarCuentas(tipo){
	if(tipo == 'debe'){
		var idcuenta = document.getElementById('idcuenta_debe').value;
		var nivel_cuenta = document.getElementById('nivel_debe').value;
		var monto = document.getElementById('monto_debe').value;
	}else{
		var idcuenta = document.getElementById('idcuenta_haber').value;
		var nivel_cuenta = document.getElementById('nivel_haber').value;
		var monto = document.getElementById('monto_haber').value;
	}
	var idcuenta_t = document.getElementById('idcuentas_t').value;
	var tipo_asiento = document.getElementById('tipo_asiento_oculto').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe esa cuenta ya fue ingresada");
			}else if(ajax.responseText == "supero_monto"){
				mostrarMensajes("error", "Disculpe ha superado el monto total de la orden");
			}else{
				document.getElementById('cuenta_debe').value = 0;
				document.getElementById('cuenta_haber').value = 0;
				document.getElementById('idcuenta_debe').value = "";
				document.getElementById('nivel_debe').value = "";
				document.getElementById('monto_debe').value = "";
				document.getElementById('idcuenta_haber').value = "";
				document.getElementById('nivel_haber').value = "";
				document.getElementById('monto_haber').value = "";
				listarCuentasSeleccionadas(tipo);
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&idcuenta_t="+idcuenta_t+"&tipo="+tipo+"&monto="+monto+"&nivel_cuenta="+nivel_cuenta+"&idcuenta="+idcuenta+"&ejecutar=ingresarCuentas");
	return false;
}



function listarCuentasSeleccionadas(tipo){
	var estado = document.getElementById('estado').value;
	var idcuenta_t = document.getElementById('idcuentas_t').value;
	var tipo_asiento = document.getElementById('tipo_asiento_oculto').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(tipo == "debe"){
				actualizarTotal(tipo);
				document.getElementById('celda_cuentas_seleccionadas_debe').innerHTML = ajax.responseText;
			}else{
				actualizarTotal(tipo);
				document.getElementById('celda_cuentas_seleccionadas_haber').innerHTML = ajax.responseText;
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&estado="+estado+"&idcuenta_t="+idcuenta_t+"&tipo="+tipo+"&ejecutar=listarCuentasSeleccionadas");
}



function actualizarTotal(tipo){
	var idcuenta_t = document.getElementById('idcuentas_t').value;
	var tipo_asiento = document.getElementById('tipo_asiento_oculto').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(tipo == "debe"){
				document.getElementById('celda_total_debe').innerHTML = ajax.responseText;	
			}else{
				document.getElementById('celda_total_haber').innerHTML = ajax.responseText;	
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&idcuenta_t="+idcuenta_t+"&tipo="+tipo+"&ejecutar=actualizarTotal");	
}



function eliminarCuentaSeleccionada(idcuentas_t_seleccionadas, tipo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarCuentasSeleccionadas(tipo);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_t_seleccionadas="+idcuentas_t_seleccionadas+"&tipo="+tipo+"&ejecutar=eliminarCuentaSeleccionada");		
}




function modificarCuentasSeleccionadas(idcuentas_t_seleccionadas, tipo, monto_actualizar){
	var tipo_asiento = document.getElementById('tipo_asiento_oculto').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "supero_monto"){
				mostrarMensajes("error", "Disculpe supero el monto de la Orden");
			}
			listarCuentasSeleccionadas(tipo);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&idcuentas_t_seleccionadas="+idcuentas_t_seleccionadas+"&monto_actualizar="+monto_actualizar+"&tipo="+tipo+"&ejecutar=modificarCuentasSeleccionadas");	
}





function procesarCuentas(){
	if(confirm("Realmente desea Procesar este Asiento?")){
	var idcuentas_t = document.getElementById('idcuentas_t').value;
	var idorden_pago = document.getElementById('idorden_pago').value;
	var tipo_asiento = document.getElementById('tipo_asiento_oculto').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "sinCuentas"){
				mostrarMensajes("error", "Disculpe debe seleccionar cuentas en el debe y en el haber para poder Procesar");
			}else{
				consultarCuentas(idcuentas_t);	
			}
			
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo_asiento="+tipo_asiento+"&idorden_pago="+idorden_pago+"&idcuentas_t="+idcuentas_t+"&ejecutar=procesarCuentas");	
	}
}



function consultarCuentas(idcuentas_t){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			if(partes[1] != 'elaboracion'){
				document.getElementById('tabla_debe').style.display = 'none';
				document.getElementById('tabla_haber').style.display = 'none';
			}else{
				document.getElementById('tabla_debe').style.display = 'block';
				document.getElementById('tabla_haber').style.display = 'block';	
			}
			if(partes[1] == "elaboracion"){
				document.getElementById('celda_estado').innerHTML = "Elaboracion";
				document.getElementById('boton_procesar').style.display = 'block';	
				document.getElementById('boton_anular').style.display = 'none';	
			}else if(partes[1] == "procesado"){
				document.getElementById('celda_estado').innerHTML = "Procesado";
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'block';	
			}else if(partes[1] == "anulado"){
				document.getElementById('celda_estado').innerHTML = "Anulado";
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'none';	
			}
			document.getElementById('idcuentas_t').value = idcuentas_t;
			document.getElementById('idorden_pago').value = partes[0];
			document.getElementById('estado').value = partes[1];
			document.getElementById('celda_nro_orden').innerHTML = partes[2];
			document.getElementById('celda_fecha').innerHTML = partes[3];
			document.getElementById('celda_total').innerHTML = partes[4];
			document.getElementById('celda_justificacion').innerHTML = partes[5];
			document.getElementById('celda_beneficiario').innerHTML = partes[6];
			
			document.getElementById('celda_exento').innerHTML = partes[7];
			document.getElementById('celda_impuesto').innerHTML = partes[8];
			document.getElementById('celda_sub_total').innerHTML = partes[9];
			document.getElementById('celda_retenido').innerHTML = partes[10];
			document.getElementById('celda_total_pagar').innerHTML = partes[11];
			document.getElementById('celda_fecha_contable').innerHTML = partes[12];
			
			listarCuentasSeleccionadas('debe');
			listarCuentasSeleccionadas('haber');
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_t="+idcuentas_t+"&ejecutar=consultarCuentas");	
}


function anularCuentas(){
	if(confirm("Realmente desea Anular este Asiento?")){
	var idcuentas_t = document.getElementById('idcuentas_t').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/cuentas_t_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			consultarCuentas(idcuentas_t);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_t="+idcuentas_t+"&ejecutar=anularCuentas");	
	}
}
