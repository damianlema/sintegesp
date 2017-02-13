// JavaScript Document


function mostrarConceptos(){
		var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
		var idcontribuyente = document.getElementById('idcontribuyente').value;
		if(idcontribuyente != ""){

				var ajax=nuevoAjax();
				ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() { 
					if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}
					if (ajax.readyState==4){
						document.getElementById('imagen_buscar_contribuyente').style.display = 'none';
						document.getElementById('celda_conceptos_tributarios').innerHTML = ajax.responseText;
						mostrarRangoFechas();
					}
					
				}
				ajax.send("idsolicitud_calculo="+idsolicitud_calculo+"&ejecutar=mostrarConceptos");	
		}else{
			mostrarMensajes("error", "Disculpe debe seleccionar un contribuyente para poder seguir con la operacion");
			document.getElementById('tipo_solicitud').value = 0;
		}
}


function consultarContribuyente(idcontribuyente){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);
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
	var tipo_solicitud = document.getElementById('tipo_solicitud').value;
	var fecha_solicitud = document.getElementById('fecha_solicitud').value;
	var anio = document.getElementById('anio').value;
	var desde = document.getElementById('desde').value;
	var hasta = document.getElementById('hasta').value;
	var vence = document.getElementById('vence').value;
	var descripcion = document.getElementById('descripcion').value;
	
	if(idcontribuyente == "" || tipo_solicitud == "" || fecha_solicitud == "" || desde == "" || hasta == "" || vence == "" || descripcion == ""){
			mostrarMensajes("error", "Disculpe todos los datos son Obligatorios");
	}else{
	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Los datos fueron registrados con exito");
				document.getElementById('idsolicitud_calculo').value = ajax.responseText;
				mostrarConceptos();
				document.getElementById("divCargando").style.display = "none";
				document.getElementById('boton_procesar').style.display='block';
				document.getElementById('boton_siguiente').style.display='none';
				document.getElementById('boton_anular').style.display='none';
				
				//	muestro el boton imprimir
				document.getElementById("btImprimir").style.visibility = "visible";
			}
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&tipo_solicitud="+tipo_solicitud+"&fecha_solicitud="+fecha_solicitud+"&anio="+anio+"&desde="+desde+"&hasta="+hasta+"&vence="+vence+"&descripcion="+descripcion+"&idcontribuyente="+idcontribuyente+"&ejecutar=ingresarDatosBasicos");	
		
	}
}




function actualizarMontoVariable(idcampo, idconcepto, valor){
		var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarConceptos();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idsolicitud_calculo="+idsolicitud_calculo+"&valor="+valor+"&idconcepto="+idconcepto+"&idcampo="+idcampo+"&ejecutar=actualizarMontoVariable");	
}




function ingresarConcepto(){
	var idconcepto = document.getElementById('idconcepto_tributario_agregar').value;
	var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
	var idtipo_solicitud = document.getElementById('tipo_solicitud').value;
	var desde = document.getElementById('desde').value;
	var hasta = document.getElementById('hasta').value;
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Concepto ingresado con exito");
			mostrarConceptos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&desde="+desde+"&hasta="+hasta+"&idtipo_solicitud="+idtipo_solicitud+"&idsolicitud_calculo="+idsolicitud_calculo+"&idconcepto="+idconcepto+"&ejecutar=ingresarConcepto");	
}



function eliminarConcepto(idconceptos_solicitud_calculo){
	if(confirm("Seguro desea eliminar este concepto tributario de los cobros a este contribuyente por esta solicitud?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarConceptos();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idconceptos_solicitud_calculo="+idconceptos_solicitud_calculo+"&ejecutar=eliminarConcepto");
	}
}




function procesarSolicitud(idsolicitud_calculo){
	if(confirm("Seguro desea Procesar esta Solicitud de Calculo")){
		var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('numero_orden').innerHTML = ajax.responseText;
				document.getElementById('estado_orden').innerHTML = "Procesado";
				mostrarConceptos();
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'block';
				document.getElementById("divCargando").style.display = "none";
				
				//	muestro el boton imprimir
				document.getElementById("btImprimir").style.visibility = "visible";
			}
			
		}
		ajax.send("idsolicitud_calculo="+idsolicitud_calculo+"&ejecutar=procesarSolicitud");	
	}
}




function anularSolicitud(){
		if(confirm("Seguro desea Anular esta Solicitud de Calculo")){
		var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('estado_orden').innerHTML = "Anulado";
				mostrarConceptos();
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idsolicitud_calculo="+idsolicitud_calculo+"&ejecutar=anularSolicitud");	
	}	
}



function consultarSolicitud(idsolicitud_calculo){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				
				
				document.getElementById('idsolicitud_calculo').value = idsolicitud_calculo;
				document.getElementById('fecha_solicitud').value = partes[2];
				document.getElementById('datos_contribuyente').innerHTML = partes[3];
				document.getElementById('tipo_solicitud').value = partes[4];
				document.getElementById('fecha_solicitud').value = partes[5];
				document.getElementById('anio').value = partes[6];
			 	document.getElementById('desde').value = partes[7];
				document.getElementById('hasta').value = partes[8];
				document.getElementById('vence').value = partes[9];
				document.getElementById('descripcion').value = partes[10];
				document.getElementById('idcontribuyente').value = partes[11];
				
				if(partes[1] == "anulado"){
					document.getElementById('estado_orden').innerHTML = "Anulado";	
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('numero_orden').innerHTML = partes[0];
				}else if(partes[1] == "procesado"){
					document.getElementById('estado_orden').innerHTML = "Procesado";	
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'block';
					document.getElementById('numero_orden').innerHTML = partes[0];
				}else if(partes[1] == "elaboracion"){
					document.getElementById('estado_orden').innerHTML = "Elaboracion";	
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_procesar').style.display = 'block';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('numero_orden').innerHTML = "Aun No Generado";
				}
				
			
				//	muestro el boton imprimir
				document.getElementById("btImprimir").style.visibility = "visible";
			
				mostrarConceptos();
			}
			
		}
		ajax.send("idsolicitud_calculo="+idsolicitud_calculo+"&ejecutar=consultarSolicitud");		
}




function actualizarFechasdePago(idconcepto_tributario, fraccion_pago){
		var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarConceptos();
			}
			
		}
		ajax.send("idsolicitud_calculo="+idsolicitud_calculo+"&fraccion_pago="+fraccion_pago+"&idconcepto_tributario="+idconcepto_tributario+"&ejecutar=actualizarFechasdePago");		
}




function mostrarRangoFechas(){
	var idsolicitud_calculo = document.getElementById('idsolicitud_calculo').value;
	var fecha_solicitud = document.getElementById('fecha_solicitud').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/solciitud_calculo_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//mostrarConceptos();
				document.getElementById('celda_fechas').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("fecha_solicitud="+fecha_solicitud+"&idsolicitud_calculo="+idsolicitud_calculo+"&ejecutar=mostrarRangoFechas");	
}