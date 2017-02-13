// JavaScript Document

function guardarDatosBasicos(){
	fecha_contable = document.getElementById('fecha_documento').value;
	mes_contable = document.getElementById('mes_contable').value;
	detalle = document.getElementById('detalle').value;
	idfuente_financiamiento = document.getElementById('idfuente_financiamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById('idasiento_contable').value = ajax.responseText;
			document.getElementById('debe').style.display = 'block';
			document.getElementById('boton_ajustar').style.display = 'block';
			document.getElementById('boton_siguiente').style.display = 'none';
			document.getElementById('boton_procesar').style.display = 'block';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("detalle="+detalle+"&fecha_contable="+fecha_contable+"&mes_contable="+mes_contable+"&idfuente_financiamiento="+idfuente_financiamiento+"&ejecutar=guardarDatosBasicos");
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




function ingresarCuentas(){
	var tipo = document.getElementById('tipo').value;

	var idcuenta = document.getElementById('idcuenta_debe').value;
	var nivel_cuenta = document.getElementById('nivel_debe').value;
	var monto = document.getElementById('monto_debe').value;
	
	var idasiento_contable = document.getElementById('idasiento_contable').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById('cuenta_debe').value = 0;
			document.getElementById('idcuenta_debe').value = "";
			document.getElementById('nivel_debe').value = "";
			document.getElementById('monto_debe').value = "";
			document.getElementById("divCargando").style.display = "none";
			listarCuentasSeleccionadas();
		} 
	}
	ajax.send("idasiento_contable="+idasiento_contable+"&tipo="+tipo+"&monto="+monto+"&nivel_cuenta="+nivel_cuenta+"&idcuenta="+idcuenta+"&ejecutar=ingresarCuentas");
	return false;
}



function listarCuentasSeleccionadas(){
	var tipo = document.getElementById('tipo').value;
	var estado = document.getElementById('estado_contable').value;
	//alert(estado);
	var idasiento_contable = document.getElementById('idasiento_contable').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById('celda_cuentas_seleccionadas_debe').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idasiento_contable="+idasiento_contable+"&estado="+estado+"&ejecutar=listarCuentasSeleccionadas");
}



function ajustarCuentas(){
	var idasiento_contable = document.getElementById('idasiento_contable').value;
	fecha_contable = document.getElementById('fecha_documento').value;
	mes_contable = document.getElementById('mes_contable').value;
	detalle = document.getElementById('detalle').value;
	idfuente_financiamiento = document.getElementById('idfuente_financiamiento').value;

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idasiento_contable="+idasiento_contable+"&detalle="+detalle+"&fecha_contable="+fecha_contable+"&mes_contable="+mes_contable+"&idfuente_financiamiento="+idfuente_financiamiento+"&ejecutar=ajustarCuentas");	
}



function eliminarCuentaSeleccionada(idcuentas_asiento_contable){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarCuentasSeleccionadas();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_asiento_contable="+idcuentas_asiento_contable+"&ejecutar=eliminarCuentaSeleccionada");		
}




function modificarCuentasSeleccionadas(idcuentas_asiento_contable, monto_actualizar){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarCuentasSeleccionadas();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_asiento_contable="+idcuentas_asiento_contable+"&monto_actualizar="+monto_actualizar+"&ejecutar=modificarCuentasSeleccionadas");	
}





function procesarCuentas(){
	if(confirm("Realmente desea Procesar este Asiento?")){
		var idasiento_contable = document.getElementById('idasiento_contable').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "sinCuentas"){
					mostrarMensajes("error", "Disculpe debe seleccionar cuentas en el debe y en el haber para poder Procesar");
				}else if(ajax.responseText == "difTotales"){
					mostrarMensajes("error", "El total de la columna DEBE tiene que ser igual al de la columna HABER");
				}else{
					mostrarMensajes("exito", "El asiento fue procesado con exito");
					consultarCuentas(idasiento_contable);	
				}
				
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idasiento_contable="+idasiento_contable+"&ejecutar=procesarCuentas");	
	}
}

function anularCuentas(){
	if(confirm("Realmente desea Anular este Asiento?")){
		var idasiento_contable = document.getElementById('idasiento_contable').value;
		fecha_contable = document.getElementById('fecha_anulacion_asiento').value;
		mes_contable = document.getElementById('mes_contable').value;
		detalle = document.getElementById('detalle').value;
		idfuente_financiamiento = document.getElementById('idfuente_financiamiento').value;
		clave = document.getElementById('verificarClave').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				consultarCuentas(ajax.responseText);
				document.getElementById("divPreguntarUsuario").style.display = "none";
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("detalle="+detalle+"&fecha_contable="+fecha_contable+"&mes_contable="+mes_contable+"&idfuente_financiamiento="+idfuente_financiamiento+"&idasiento_contable="+idasiento_contable+"&clave="+clave+"&ejecutar=anularCuentas");	
	}
}

function consultarCuentas(idasiento_contable){

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/asientos_contables_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.onreadystatechange=function() { 
		
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if(ajax.readyState==4){
			
			partes = ajax.responseText.split("|.|");
			document.getElementById('debe').style.display = 'block';
			document.getElementById('idasiento_contable').value = idasiento_contable;
			document.getElementById('estado_contable').value = partes[1];
			document.getElementById('detalle').value = partes[2];
			document.getElementById('fecha_documento').value = partes[3];
			document.getElementById('mes_contable').value = partes[4];
			document.getElementById('idfuente_financiamiento').value = partes[5];
			document.getElementById('numero_asiento').innerHTML = partes[6];
			if(partes[1] != 'elaboracion'){
				document.getElementById('tabla_debe').style.display = 'none';
			}else{
				document.getElementById('tabla_debe').style.display = 'block';
			}
			if(partes[1] == "elaboracion"){
				document.getElementById('boton_procesar').style.display = 'block';
				document.getElementById('boton_ajustar').style.display = 'block';	
				document.getElementById('boton_siguiente').style.display = 'none';	
				document.getElementById('boton_anular').style.display = 'none';	
			}else if(partes[1] == "procesado"){
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_ajustar').style.display = 'none';
				document.getElementById('boton_siguiente').style.display = 'none';
				if(partes[7] != 'si'){
					document.getElementById('boton_anular').style.display = 'block';
				}
			}else if(partes[1] == "anulado"){
				document.getElementById('boton_ajustar').style.display = 'none';
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_siguiente').style.display = 'none';	
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById('estado_contable').style.background.color = "#FF0000"
			}
			
			listarCuentasSeleccionadas();
			
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idasiento_contable="+idasiento_contable+"&ejecutar=consultarCuentas");	
}



