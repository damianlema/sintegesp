// JavaScript Document


function seleccionarPeriodo(idtipo_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("celda_periodo").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=seleccionarPeriodo");		
}



function ingresarDatosBasicos(){
	var idtipo_nomina= document.getElementById("idtipo_nomina").value;
	var justificacion= document.getElementById("justificacion").value;
	var idperiodo= document.getElementById("idperiodo").value;
	var id_beneficiarios = document.getElementById("id_beneficiarios").value;
	var idfuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var idtipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	var idcategoria_programatica = document.getElementById("idcentro_costo_fijo").value;
	
	if(idtipo_nomina == 0 || justificacion == "" || idperiodo == 0 || id_beneficiarios == ""){
		mostrarMensajes("error", "Disculpe todos los campos superiores son campos requeridos");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					if(ajax.responseText =="existe"){
						mostrarMensajes("error", "Disculpe el periodo seleccionado ya fue procesado para este tipo de nomina");
						document.getElementById("divCargando").style.display = "none";
					}else{
						partes = ajax.responseText.split("|.|");
						document.getElementById("idgenerar_nomina").value = partes[0];
						document.getElementById("celda_fecha_elaboracion").innerHTML = partes[1];
						document.getElementById("boton_siguiente").style.display = 'none';
						document.getElementById("boton_procesar").style.display = 'none';
						document.getElementById("boton_modificar").style.display = 'block';
						document.getElementById("boton_prenomina").style.display = 'block';
						
						document.getElementById("idtipo_nomina").disabled=true;
						document.getElementById("idperiodo").disabled=true;
						consultarTrabajadores('Elaboracion', '');
					}
			} 
		}
		ajax.send("id_beneficiarios="+id_beneficiarios+"&justificacion="+justificacion+"&idperiodo="+idperiodo+"&idtipo_nomina="+idtipo_nomina
					+"&idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto+"&anio="+anio
					+"&idcategoria_programatica="+idcategoria_programatica+"&ejecutar=ingresarDatosBasicos");	
	}
}




function consultarTrabajadores(estado, buscar){

	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaTrabajadores").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("buscar="+buscar+"&estado="+estado+"&idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarTrabajadores");
}






function generarNomina(estado){

		//consultarTrabajadores('Elaboracion', '');
//	if(estado == "Procesado" && document.getElementById("error_conceptos").value == "si"){
//		alert("Disculpe hay un error en la certificacion con algun(os) concepto(s), por favor revise para poder procesar la nomina");	
//	}else{
		var idtipo_nomina = document.getElementById("idtipo_nomina").value;
		var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
		var idperiodo = document.getElementById("idperiodo").value;
		var idcertificacion = document.getElementById("idorden_compra_servicio").value;
		var idcertificacion_aporte = document.getElementById("idorden_compra_servicio_aporte").value;
		var idfuente_financiamiento = document.getElementById("fuente_financiamiento").value;
		var idtipo_presupuesto = document.getElementById("tipo_presupuesto").value;
		var anio = document.getElementById("anio").value;
		var idcentro_costo_fijo = document.getElementById("idcentro_costo_fijo").value;
		
		/*alert(idtipo_nomina);
		alert(idgenerar_nomina);
		alert(idperiodo);
		alert(idcertificacion);
		alert(idfuente_financiamiento);
		alert(idtipo_presupuesto);
		alert(anio);*/

		
		document.getElementById("divCargando").style.display = "block";
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				
					
					}
			if (ajax.readyState==4){
					//alert(ajax.responseText);
					partes = ajax.responseText.split("|.|");
					document.getElementById("divCargando").style.display = "none";
					if(partes[0] == "exito"){
						
						mostrarMensajes("exito", "La nomina ha sido generada con exito");
						validarErroresConceptos(partes[1]);
						consultarConceptos(partes[1], 'detallado');
						consultarPartidas(partes[1]);
						if (partes[2] != '' && partes[2] != '0'){
							consultarConceptosAportes(partes[2], 'detallado');
							consultarPartidasAportes(partes[2]);
						}else{
							limpiarConceptosAportes();
							limpiarPartidasAportes();
						}
						var fecha_actual = new Date();
						var dia = fecha_actual.getDate()  
						var mes = fecha_actual.getMonth()+1;  
						var anio = fecha_actual.getFullYear();
						if(mes<10){
							mes = 0+""+mes;
						}
						if(dia<10){
							dia = 0+""+dia;
						}
						
						document.getElementById("titulo_numero_certificacion").innerHTML = "Nro de Certificaci&oacute;n";
						
						document.getElementById("celda_fecha_procesado").innerHTML = anio+"-"+mes+"-"+dia;
						document.getElementById("celda_estado").innerHTML = "Procesado";
						document.getElementById("boton_siguiente").style.display = 'none';
						document.getElementById("boton_procesar").style.display = 'none';
						document.getElementById("boton_modificar").style.display = 'none';
						document.getElementById("boton_prenomina").style.display = 'none';
						document.getElementById("boton_anular").style.display = 'block';
						document.getElementById("boton_procesar_certificacion").style.display = 'block';
						if(idcertificacion_aporte != '' && idcertificacion_aporte != 0){
							document.getElementById("boton_procesar_certificacion_aporte").style.display = 'block';
						}
						document.getElementById("boton_nueva_certificacion").style.display = 'block';
						document.getElementById("idtipo_nomina").disabled=true;
						document.getElementById("justificacion").disabled=true;
						document.getElementById("idperiodo").disabled=true;
						consultarTrabajadores('Procesado','');
						setTimeout("verificarError()",100);
					}else if(partes[0] == "fallo"){
						mostrarMensajes("error", "Disculpe Existieron errores al momento de generar la nomina, por favor verifique");
						validarErroresConceptos(partes[1]);
						consultarConceptos(partes[1], 'detallado');
						consultarPartidas(partes[1]);
						if (partes[2] != '' && partes[2] != '0'){
							consultarConceptosAportes(partes[2], 'detallado');
							consultarPartidasAportes(partes[2]);
						}else{
							limpiarConceptosAportes();
							limpiarPartidasAportes();
						}
						document.getElementById("celda_estado").innerHTML = "Pre Nomina";
						document.getElementById("boton_siguiente").style.display = 'none';
						document.getElementById("boton_procesar").style.display = 'block';
						document.getElementById("boton_modificar").style.display = 'block';
						document.getElementById("boton_prenomina").style.display = 'block';
						document.getElementById("boton_anular").style.display = 'none';
						document.getElementById("idtipo_nomina").disabled=true;
						document.getElementById("justificacion").disabled=true;
						document.getElementById("idperiodo").disabled=true;
						consultarTrabajadores('Procesado','');
						setTimeout("verificarError()",100);
					
					}else{
						mostrarMensajes("exito", "La nomina se coloco en PRE NOMINA con exito");
						validarErroresConceptos(partes[0]);
						consultarConceptos(partes[0], 'detallado');
						consultarPartidas(partes[0]);
						if (partes[1] != '' && partes[1] != '0'){
							consultarConceptosAportes(partes[1], 'detallado');
							consultarPartidasAportes(partes[1]);
						}else{
							limpiarConceptosAportes();
							limpiarPartidasAportes();
						}
						document.getElementById("idorden_compra_servicio").value = partes[0];
						document.getElementById("idorden_compra_servicio_aporte").value = partes[1];
						document.getElementById("celda_estado").innerHTML = "Pre Nomina";
						document.getElementById("boton_siguiente").style.display = 'none';
						document.getElementById("boton_procesar").style.display = 'block';
						document.getElementById("boton_modificar").style.display = 'block';
						document.getElementById("boton_prenomina").style.display = 'block';
						document.getElementById("boton_anular").style.display = 'none';
						document.getElementById("idtipo_nomina").disabled=true;
						document.getElementById("justificacion").disabled=true;
						document.getElementById("idperiodo").disabled=true;
						consultarTrabajadores('Procesado','');
						setTimeout("verificarError()",200);
					}
					
					//if(ajax.responseText == "exito" && (estado == "Procesado" || estado == 'Pre Nomina')) {
						document.getElementById('celdaImprimir').style.display = 'block';
					//}
					
					//window.open("principal.php?accion=664&modulo=13&idorden_compra="+ajax.responseText, "", "height=800, width=900,scrollbars=yes, resizabled =no");
					//document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("estado="+estado+"&idcertificacion="+idcertificacion+"&idperiodo="+idperiodo+"&idgenerar_nomina="+idgenerar_nomina
				+"&idtipo_nomina="+idtipo_nomina+"&idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto
				+"&anio="+anio+"&idcentro_costo_fijo="+idcentro_costo_fijo+"&ejecutar=generarNomina");
//	}
}



function procesarCertificacion(tipo_generar){
		var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
		var idfuente_financiamiento = document.getElementById("fuente_financiamiento").value;
		var idtipo_presupuesto = document.getElementById("tipo_presupuesto").value;
		var anio = document.getElementById("anio").value;
		var idcentro_costo_fijo = document.getElementById("idcentro_costo_fijo").value;
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					validarErroresConceptos(document.getElementById('idorden_compra_servicio').value);
					if(document.getElementById("error_conceptos").value == "si"){	
						mostrarMensajes("error", "Disculpe hay un error en la certificacion con algun(os) concepto(s), por favor revise para poder procesar la nomina");
					}else{
						if(tipo_generar == 'N'){
							document.getElementById("boton_procesar_certificacion").style.display= 'none';
							document.getElementById("boton_nueva_certificacion").style.display= 'none';
							document.getElementById("titulo_numero_certificacion").innerHTML = "Nro de Certificaci&oacute;n: ";
							document.getElementById("numero_certificacion").innerHTML = ajax.responseText;
						}
						if(tipo_generar == 'A'){
							document.getElementById("boton_procesar_certificacion_aporte").style.display= 'none';
							document.getElementById("titulo_numero_certificacion_aporte").innerHTML = "Nro de Certificaci&oacute;n APORTE: ";
							document.getElementById("numero_certificacion_aporte").innerHTML = ajax.responseText;
						}
						
					}
					
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idgenerar_nomina="+idgenerar_nomina+"&idfuente_financiamiento="+idfuente_financiamiento
					+"&idtipo_presupuesto="+idtipo_presupuesto+"&anio="+anio
					+"&idcentro_costo_fijo="+idcentro_costo_fijo
					+"&tipo_generar="+tipo_generar+"&ejecutar=procesarCertificacion");
}



function verificarError(){
	if(document.getElementById("error_conceptos").value=="si"){
		cambiarColor("tdconceptos", '#FF0000');	
	}	
}



function anularNomina(){
	if(confirm("Seguro desea Anular esta Nomina?")){
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				//alert(ajax.responseText);
				//document.getElementById("celda_fecha_procesado").innerHTML = anio+"-"+mes+"-"+dia;
				document.getElementById("celda_estado").innerHTML = "Anulado";
				document.getElementById("boton_siguiente").style.display = 'none';
				document.getElementById("boton_procesar").style.display = 'none';
				document.getElementById("boton_modificar").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_procesar_certificacion").style.display = 'none';
				document.getElementById("boton_procesar_certificacion_aporte").style.display = 'none';
				document.getElementById("boton_nueva_certificacion").style.display = 'none';
				document.getElementById("boton_prenomina").style.display = 'none';
				document.getElementById("idtipo_nomina").disabled=true;
				document.getElementById("justificacion").disabled=true;
				document.getElementById("idperiodo").disabled=true;
				consultarTrabajadores('Procesado','');
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=anularNomina");	
	}
}






function consultarGenerarNomina(idgenerar_nomina, idtipo_nomina, descripcion, idperiodo, fecha_elaboracion, fecha_procesado, estado, nombre_beneficiario, idbeneficiarios, idorden_compra_servicio, numero_orden, rango_periodo, idorden_compra_servicio_aporte, idcentro_costo_fijo, nombre_categoria){
		
	//alert(nombre_categoria);
	//alert(idcentro_costo_fijo);

		seleccionarPeriodo(idtipo_nomina);
		validarErroresConceptos(idorden_compra_servicio);
		consultarConceptos(idorden_compra_servicio, 'detallado');
		consultarPartidas(idorden_compra_servicio);
		if (idorden_compra_servicio_aporte != '0' && idorden_compra_servicio_aporte != ''){
			consultarConceptosAportes(idorden_compra_servicio_aporte, 'detallado');
			consultarPartidasAportes(idorden_compra_servicio_aporte);
		}else{
			limpiarConceptosAportes();
			limpiarPartidasAportes();
		}

		document.getElementById('idorden_compra_servicio').value = idorden_compra_servicio;
		document.getElementById('idorden_compra_servicio_aporte').value = idorden_compra_servicio_aporte;
		document.getElementById("idtipo_nomina").value=idtipo_nomina;
		
		
		document.getElementById("idgenerar_nomina").value=idgenerar_nomina;
		document.getElementById("justificacion").value=descripcion;
		
		document.getElementById("nombre_proveedor").value=nombre_beneficiario;
		document.getElementById("id_beneficiarios").value=idbeneficiarios;

		document.getElementById("nombre_categoria").value=nombre_categoria;
		document.getElementById("idcentro_costo_fijo").value=idcentro_costo_fijo;
		
		document.getElementById("celda_estado").innerHTML=estado;
		document.getElementById("celda_fecha_elaboracion").innerHTML=fecha_elaboracion;
		document.getElementById("celda_fecha_procesado").innerHTML=fecha_procesado;
		consultarTrabajadores(estado,'');
		document.getElementById("boton_siguiente").style.display= 'none';
		//alert("Consultando nomina: "+descripcion);
		
		if(estado =="elaboracion"){
				document.getElementById("boton_modificar").style.display= 'block';
				document.getElementById("boton_procesar").style.display= 'block';
				document.getElementById("boton_prenomina").style.display= 'block';
				document.getElementById("boton_procesar_certificacion").style.display= 'none';
				document.getElementById("boton_procesar_certificacion_aporte").style.display= 'none';
				document.getElementById("boton_nueva_certificacion").style.display= 'none';
				document.getElementById("boton_anular").style.display= 'none';
				document.getElementById("justificacion").disabled=false;
				
				document.getElementById("titulo_numero_certificacion").innerHTML = "";
				document.getElementById("numero_certificacion").innerHTML = "";
				
		}else if(estado == "procesado"){
				document.getElementById("boton_modificar").style.display= 'none';
				document.getElementById("boton_procesar").style.display= 'none';
				document.getElementById("boton_anular").style.display= 'block';
				document.getElementById("boton_prenomina").style.display= 'none';
				document.getElementById("boton_procesar_certificacion").style.display= 'block';
				if (idorden_compra_servicio_aporte != '0' && idorden_compra_servicio_aporte != ''){
					document.getElementById("boton_procesar_certificacion_aporte").style.display= 'block';
				}
				document.getElementById("boton_nueva_certificacion").style.display= 'block';
				document.getElementById("justificacion").disabled=true;
				var ajax=nuevoAjax();
				ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() { 
					if(ajax.readyState == 1){}
					if (ajax.readyState==4){
						partes = ajax.responseText.split("|.|");
						if(partes[0] != "elaboracion"){
							document.getElementById("titulo_numero_certificacion").innerHTML = "Nro de Certificaci&oacute;n";
							document.getElementById("numero_certificacion").innerHTML = partes[1];
							document.getElementById("boton_procesar_certificacion").style.display= 'none';
							document.getElementById("boton_nueva_certificacion").style.display= 'none';
						}
						if(partes[2] != "elaboracion" && partes[2] != "anulado" && partes[2] != "0"){
								document.getElementById("titulo_numero_certificacion_aporte").innerHTML = "Nro de Certificaci&oacute;n APORTE: ";
								document.getElementById("numero_certificacion_aporte").innerHTML = partes[3];
								document.getElementById("boton_procesar_certificacion_aporte").style.display= 'none';
						}
					} 
				}
				ajax.send("idorden_compra_servicio="+idorden_compra_servicio
							+"&idorden_compra_servicio_aporte="+idorden_compra_servicio_aporte+"&ejecutar=consultarEstadoCertificacion");	
				
				document.getElementById("celdaImprimir").style.display= 'block';

		}else if(estado == "Anulado"){
				document.getElementById("boton_modificar").style.display= 'none';
				document.getElementById("boton_procesar").style.display= 'none';
				document.getElementById("boton_anular").style.display= 'none';	
				document.getElementById("boton_prenomina").style.display= 'none';
				document.getElementById("boton_procesar_certificacion").style.display= 'none';
				document.getElementById("boton_procesar_certificacion_aporte").style.display= 'none';
				document.getElementById("boton_nueva_certificacion").style.display= 'none';
				document.getElementById("justificacion").disabled=true;
				document.getElementById("titulo_numero_certificacion").innerHTML = "Nro de Certificaci&oacute;n";
				document.getElementById("numero_certificacion").innerHTML = numero_orden;
		}else if(estado == "Pre Nomina"){
				document.getElementById("boton_modificar").style.display= 'block';
				document.getElementById("boton_procesar").style.display= 'block';
				document.getElementById("boton_anular").style.display= 'none';	
				document.getElementById("boton_prenomina").style.display= 'block';
				document.getElementById("boton_procesar_certificacion").style.display= 'none';
				document.getElementById("boton_procesar_certificacion_aporte").style.display= 'none';
				document.getElementById("boton_nueva_certificacion").style.display= 'none';
				document.getElementById("justificacion").disabled=false;
				document.getElementById("titulo_numero_certificacion").innerHTML = "";
				document.getElementById("numero_certificacion").innerHTML = "";
				document.getElementById("celdaImprimir").style.display= 'block';
		}
		
		document.getElementById("idtipo_nomina").disabled=true;
		
		
		setTimeout("document.getElementById('div_seleccione').innerHTML='"+rango_periodo+"'",1000);
		//setTimeout("document.getElementById('idperiodo').disabled=true",3800);
		
		setTimeout("document.getElementById('idperiodo').value='"+idperiodo+"'",500);
}



function modificarNomina(){
	var idgenerar_nomina= document.getElementById("idgenerar_nomina").value;
	var justificacion= document.getElementById("justificacion").value;
	var id_beneficiarios= document.getElementById("id_beneficiarios").value;
	var idfuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var idtipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	var idcentro_costo_fijo = document.getElementById("idcentro_costo_fijo").value;
	var id_beneficiarios = document.getElementById("id_beneficiarios").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){		
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_beneficiarios="+id_beneficiarios+"&justificacion="+justificacion+"&idgenerar_nomina="+idgenerar_nomina
		+"&idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto+"&anio="+anio
		+"&idcentro_costo_fijo="+idcentro_costo_fijo+"&id_beneficiarios="+id_beneficiarios+"&ejecutar=modificarNomina");
}








function consultarConceptos(id_orden_compra, tipo){
	//alert(id_orden_compra);
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("listaConceptos").innerHTML=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
			
		} 
	}
	ajax.send("tipo="+tipo+"&id_orden_compra="+id_orden_compra+"&ejecutar=consultarConceptos");
}


function consultarConceptosAportes(id_orden_compra_aportes, tipo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("listaAportes").innerHTML=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
			
		} 
	}
	ajax.send("tipo="+tipo+"&id_orden_compra_aportes="+id_orden_compra_aportes+"&ejecutar=consultarConceptosAportes");
}

function limpiarConceptosAportes(){
	document.getElementById("listaAportes").innerHTML='Sin Aportes Generados';
}
function limpiarPartidasAportes(){
	document.getElementById("listaPartidasAportes").innerHTML='Sin Partidas de Aportes Generados';
}

function validarErroresConceptos(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("error_conceptos").value=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&ejecutar=validarErroresConceptos");
}




function consultarPartidas(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("listaPartidas").innerHTML=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&ejecutar=consultarPartidas");
}


function consultarPartidasAportes(id_orden_compra_aportes){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("listaPartidasAportes").innerHTML=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_orden_compra_aportes="+id_orden_compra_aportes+"&ejecutar=consultarPartidasAportes");
}


function cambiarColor(id, color){
	if(id == "tdtrabajador" && document.getElementById("pestana_seleccionada").value == 1){
		color = "#EAEAEA";	
	}else if(id == "tdconceptos" && document.getElementById("pestana_seleccionada").value == 2){
		color = "#EAEAEA";	
	}else if(id == "tdpartidas" && document.getElementById("pestana_seleccionada").value == 3){
		color = "#EAEAEA";	
	}
	document.getElementById(id).bgColor = color;
}



function seleccionarPestana(id){
	document.getElementById("tdtrabajador").bgColor = "#FFFFCC";
	document.getElementById("tdconceptos").bgColor = "#FFFFCC";
	document.getElementById("tdpartidas").bgColor = "#FFFFCC";
    document.getElementById("tdaportes").bgColor = "#FFFFCC";
    document.getElementById("tdpartidasaportes").bgColor = "#FFFFCC";

	document.getElementById("listaTrabajadores").style.display = 'none';
	document.getElementById("listaConceptos").style.display = 'none';
	document.getElementById("listaPartidas").style.display = 'none';
    document.getElementById("listaAportes").style.display = 'none';
    document.getElementById("listaPartidasAportes").style.display = 'none';
	
	if(id == "tdtrabajador"){
		document.getElementById("pestana_seleccionada").value = 1;
		cambiarColor("tdtrabajador", "#EAEAEA");
		document.getElementById("listaTrabajadores").style.display = 'block';
	}else if(id == "tdconceptos"){
		document.getElementById("pestana_seleccionada").value = 2;	
		cambiarColor("tdconceptos", "#EAEAEA");
		document.getElementById("listaConceptos").style.display = 'block';
	}else if(id == "tdpartidas"){
		document.getElementById("pestana_seleccionada").value = 3;
		cambiarColor("tdpartidas", "#EAEAEA");
		document.getElementById("listaPartidas").style.display = 'block';
	}else if(id == "tdaportes"){
        document.getElementById("pestana_seleccionada").value = 3;
        cambiarColor("tdaportes", "#EAEAEA");
        document.getElementById("listaAportes").style.display = 'block';
    }else if(id == "tdpartidasaportes"){
        document.getElementById("pestana_seleccionada").value = 3;
        cambiarColor("tdpartidasaportes", "#EAEAEA");
        document.getElementById("listaPartidasAportes").style.display = 'block';
    }
}


function mostrarReporte(){
	var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
	var nomina = document.getElementById("idtipo_nomina").value;
	var periodo = document.getElementById("idperiodo").value;
	var archivo = document.getElementById("archivo").value;
	//alert(nomina); alert(periodo);
	if (document.getElementById("payroll").checked) {
		if (document.getElementById("optPDF").checked == true) {
			document.getElementById("divImprimir").style.display="block"; 
			pdf.location.href="lib/reportes/nomina/reportes.php?nombre=payroll_trabajadores&nomina="+nomina+"&periodo="+periodo+"&idgenerar_nomina="+idgenerar_nomina;
			document.getElementById("pdf").style.display="block";
		} else {
			if (archivo == "") mostrarMensajes("error", "�Debe ingresar el nombre del archivo!");
			else window.open("lib/reportes/nomina/excel.php?nombre_archivo="+archivo+"&nombre=payroll_trabajadores&nomina="+nomina+"&periodo="+periodo+"&idgenerar_nomina="+idgenerar_nomina, "", "");
		}
	}
	else if (document.getElementById("nomina").checked) {
		document.getElementById("divImprimir").style.display="block"; 
		pdf.location.href="lib/reportes/nomina/reportes.php?nombre=relacion_nomina_trabajadores&nomina="+nomina+"&periodo="+periodo+"&idgenerar_nomina="+idgenerar_nomina;
		document.getElementById("pdf").style.display="block";
	}
	else if (document.getElementById("concepto").checked) {
		document.getElementById("divImprimir").style.display="block"; 
		pdf.location.href="lib/reportes/nomina/reportes.php?nombre=nomina_resumen_conceptos&nomina="+nomina+"&periodo="+periodo+"&idgenerar_nomina="+idgenerar_nomina;
		document.getElementById("pdf").style.display="block";
	}
	else if (document.getElementById("compromiso").checked) { 
		document.getElementById('divImprimir').style.display='block';
		pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=certificacion_compromiso_rrhh&id_orden_compra='+document.getElementById('idorden_compra_servicio').value; 
		document.getElementById('pdf').style.display='block'; 
	}
	else if (document.getElementById("compromiso_aporte").checked) { 
		if(document.getElementById("idorden_compra_servicio_aporte").value != '' && document.getElementById("idorden_compra_servicio_aporte").value != 0){
			document.getElementById('divImprimir').style.display='block';
			pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=certificacion_compromiso_rrhh&id_orden_compra='+document.getElementById('idorden_compra_servicio_aporte').value; 
			document.getElementById('pdf').style.display='block'; 
		}
	}
	else if (document.getElementById("sobregiro").checked) { 
		document.getElementById('divImprimir').style.display='block';
		pdf.location.href='lib/reportes/nomina/reportes.php?nombre=sobregiro_partidas&nomina='+nomina+'&periodo='+periodo+'&id_orden_compra='+document.getElementById('idorden_compra_servicio').value; 
		document.getElementById('pdf').style.display='block'; 
	}
	else if (document.getElementById("listado").checked) { 
		document.getElementById('divImprimir').style.display='block';
		pdf.location.href="lib/reportes/nomina/reportes.php?nombre=listado&nomina="+nomina+"&periodo="+periodo+"&idgenerar_nomina="+idgenerar_nomina; 
		document.getElementById('pdf').style.display='block'; 
	}
	
}



function nuevaCertificacion(){

		
		var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;		
		var idcertificacion = document.getElementById("idorden_compra_servicio").value;
		var idcertificacion_aporte = document.getElementById("idorden_compra_servicio_aporte").value;
		
		document.getElementById("divCargando").style.display = "block";
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/generar_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				
					
					}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				partes = ajax.responseText.split("|.|");
				document.getElementById("divCargando").style.display = "none";
				//alert(partes[0]);
				consultarConceptos(partes[0], 'detallado');
				consultarPartidas(partes[0]);
				if (partes[1] != '' && partes[1] != '0'){
					consultarConceptosAportes(partes[1], 'detallado');
					consultarPartidasAportes(partes[1]);
				}else{
					limpiarConceptosAportes();
					limpiarPartidasAportes();
				}
				var fecha_actual = new Date();
				var dia = fecha_actual.getDate()  
				var mes = fecha_actual.getMonth()+1;  
				var anio = fecha_actual.getFullYear();
				if(mes<10){
					mes = 0+""+mes;
				}
				if(dia<10){
					dia = 0+""+dia;
				}
				
				document.getElementById("titulo_numero_certificacion").innerHTML = "Nro de Certificaci&oacute;n";
				
				document.getElementById("celda_fecha_procesado").innerHTML = anio+"-"+mes+"-"+dia;
				document.getElementById("celda_estado").innerHTML = "Procesado";
				document.getElementById("boton_siguiente").style.display = 'none';
				document.getElementById("boton_procesar").style.display = 'none';
				document.getElementById("boton_modificar").style.display = 'none';
				document.getElementById("boton_prenomina").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'block';
				document.getElementById("boton_procesar_certificacion").style.display = 'block';
				if(idcertificacion_aporte != '' && idcertificacion_aporte != 0){
					document.getElementById("boton_procesar_certificacion_aporte").style.display = 'block';
				}
				document.getElementById("boton_nueva_certificacion").style.display = 'block';
				document.getElementById("idtipo_nomina").disabled=true;
				document.getElementById("justificacion").disabled=true;
				document.getElementById("idperiodo").disabled=true;
				//consultarTrabajadores('Procesado','');
				setTimeout("verificarError()",100);
			
				document.getElementById('celdaImprimir').style.display = 'block';
					
			} 
		}
		ajax.send("idgenerar_nomina="+idgenerar_nomina+"&idcertificacion="+idcertificacion+"&ejecutar=nuevaCertificacion");
//	}
}






