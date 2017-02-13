// JavaScript Document
/*********************************************************************************************************************************************************
************************************************** FUNCION PARA ABRIR Y CERRAR LAS LISTAD DE PARTIDAS ****************************************************
**********************************************************************************************************************************************************/


function abrirCerrarListaDocumentos(){
	if(document.getElementById('tablaListaDocumentos').style.display=="block"){
			document.getElementById('tablaListaDocumentos').style.display="none";
			document.getElementById('textoContraerListaDocumentos').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('tablaListaDocumentos').style.display="block";
			document.getElementById('textoContraerListaDocumentos').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
	}
}



function cargarCuentasBancarias(banco){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);
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
		ajax.send("banco="+banco+"&ejecutar=cargarCuentasBancarias");
}



/*********************************************************************************************************************************************************
************************************************** INGRESAR DATOS BASICOS ********************************************************************************
**********************************************************************************************************************************************************/

function ingresarDatosBasicos(para, asunto, justificacion, id_remision, cuenta){
		if(para == 0){
			mostrarMensajes("error", "Disculpe seleccione un destino");
			document.getElementById('dependencias').focus();
		}else if(asunto == ""){
			mostrarMensajes("error", "Disculpe escriba un asunto");
			document.getElementById('asunto').focus();
		}else if(justificacion == ""){
			mostrarMensajes("error", "Disculpe escriba la justificacion");
			document.getElementById('justificacion').focus();
		}else if(cuenta == ""){
			mostrarMensajes("error", "Disculpe seleccione una cuenta bancaria");
			document.getElementById('cuenta').focus();
		}else{
				var ajax=nuevoAjax();
				ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() { 
					if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
							}
					if (ajax.readyState==4){
						//alert(ajax.responseText);
							document.getElementById("id_remision").value = ajax.responseText;
							document.getElementById("estado").value = 'elaboracion';
							muestraBotones('elaboracion');
							document.getElementById("tablaSeleccionarDocumentos").style.display = "block";
							document.getElementById("tablaDocumentosEnviar").style.display = "block";
							consultarDocumentos(cuenta);
							document.getElementById("divCargando").style.display = "none";
					} 
				}
				ajax.send("para="+para+"&asunto="+asunto+"&justificacion="+justificacion+"&id_remision="+id_remision+"&cuenta="+cuenta+"&ejecutar=ingresarDatosBasicos");					
		}
}




function actualizarDatosBasicos(id_remision, para, asunto, justificacion, cuenta){
	var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}
				if (ajax.readyState==4){
						//ingresarDatosBasicos("", "", "", id_remision);
						document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("para="+para+"&asunto="+asunto+"&justificacion="+justificacion+"&id_remision="+id_remision+"&cuenta="+cuenta+"&ejecutar=actualizarDatosBasicos");
}


function muestraBotones(estado){

	
	if (estado == 'elaboracion'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonElaboracion').style.display 		= "block";
		document.getElementById('botonEnviar').style.display 			= "block";
		document.getElementById('botonAnular').style.display 			= "none";
		document.getElementById('celdaImprimir').style.display			='block';
	}
	if (estado == 'enviado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonElaboracion').style.display 		= "none";
		document.getElementById('botonEnviar').style.display 			= "none";
		document.getElementById('botonAnular').style.display 			= "block";
		document.getElementById('celdaImprimir').style.display			='block';
	}
}


function consultarDocumentos(cuenta){
	id_remision = document.getElementById("id_remision").value;
	
	cuenta = document.getElementById("cuenta").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById('listaDocumentos').innerHTML = ajax.responseText;	
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cuenta="+cuenta+"&id_remision="+id_remision+"&ejecutar=consultarDocumentos");
}





function consultarSubDocumentos(documento, tabla, estado_tipo, id_remision){
	if(documento != "" && tabla != "" && estado_tipo != ""){
		document.getElementById('idtipo_documento').value = estado_tipo;
		var campo_tipo_documento = document.getElementById('campo_tipo_documento').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById('listaDocumentos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("campo_tipo_documento="+campo_tipo_documento+"&id_remision="+id_remision+"&documento="+documento+"&tabla="+tabla+"&estado_tipo="+estado_tipo+"&ejecutar=consultarSubDocumentos");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
				if(ajax.responseText == "elaboracion"){
					document.getElementById('listaDocumentos').innerHTML = "<b>Seleccione...</b>";
				}else if(ajax.responseText == "enviado"){
					document.getElementById("tablaSeleccionarDocumentos").style.display = "none";	
				}else if(ajax.responseText == "recibido"){
					document.getElementById("tablaSeleccionarDocumentos").style.display = "none";
				}else if(ajax.responseText == "anulado"){
					document.getElementById("tablaSeleccionarDocumentos").style.display = "none";
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_remision="+id_remision+"&ejecutar=consultarEstado");
		
	}
}







function listaSeleccionadosDocumentos(idorden_pago, id_remision){
	cuenta = document.getElementById("cuenta").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				
				document.getElementById("cuenta").disabled = true;
				
				document.getElementById('divListaSeleccionadosDocumentos').innerHTML = ajax.responseText;
				consultarDocumentos(cuenta);
				consultarCantidadDocumentos(id_remision);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_pago="+idorden_pago+"&id_remision="+id_remision+"&ejecutar=listaSeleccionadosDocumentos");	
}






function eliminarListaSeleccionadosDocumentos(idorden_pago, id_remision){
	cuenta = document.getElementById("cuenta").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){	
		//alert(ajax.responseText);			
			listaSeleccionadosDocumentos('', id_remision);	
			consultarCantidadDocumentos(id_remision);
			consultarDocumentos(cuenta);
			//buscarOrdenEspecifica();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_pago="+idorden_pago+"&id_remision="+id_remision+"&ejecutar=eliminarListaSeleccionadosDocumentos");

}

function consultarCantidadDocumentos(id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){				
			document.getElementById("divCantidad").innerHTML = ajax.responseText;	
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_remision="+id_remision+"&ejecutar=consultarCantidadDocumentos");
}



function remitirDocumentos(id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			if(ajax.responseText == "noTieneDocumentos"){
				mostrarMensajes("error", "Disculpe no Existen documentos para enviar por favor seleccione al menos uno");
			}else{
				document.getElementById("divNumeroDocumento").innerHTML = ajax.responseText;	
				var fecha = new Date();
				var dia = fecha.getDate();
				var anio = fecha.getFullYear();
				var mes = fecha.getMonth()+1;
				document.getElementById("divFechaEnvio").innerHTML = "<b>"+dia+"-"+mes+"-"+anio+"</b>";
				document.getElementById("estado").value = 'enviado';
				document.getElementById("divEstado").innerHTML = "<b>Enviado</b>";
				muestraBotones('enviado');
				document.getElementById("tablaSeleccionarDocumentos").style.display = "none";
				document.getElementById("dependencias").disabled = true;
				document.getElementById("asunto").disabled = true;
				document.getElementById("justificacion").disabled = true;
				document.getElementById("banco").disabled = true;
				document.getElementById("cuenta").disabled = true;
				
				document.getElementById("divCargando").style.display = "none";
				listaSeleccionadosDocumentos("", id_remision);
				
				//consultarRemisiones(id_remision);
			}
		} 
	}
	ajax.send("id_remision="+id_remision+"&ejecutar=remitirDocumentos");	
}

function consultarRemisiones(id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			
			document.getElementById("id_remision").value 			= id_remision;
			partes = ajax.responseText.split("|.|");
			
			document.getElementById("divNumeroDocumento").innerHTML = "<b>"+partes[2]+"</b>";		
			document.getElementById("divFechaEnvio").innerHTML = "<b>"+partes[5]+"</b>";
			 
			document.getElementById("estado").value = partes[3];
			document.getElementById("divEstado").innerHTML = "<b>"+partes[4]+"</b>";
			
			document.getElementById("dependencias").value = partes[6];
			
			
			document.getElementById("asunto").value = partes[7];
			document.getElementById("justificacion").value = partes[8];
			document.getElementById("banco").value = partes[12];
			setTimeout("cargarCuentasBancarias(partes[12])",800);
			
			
			document.getElementById("divCantidad").innerHTML = partes[11];
			
			muestraBotones(partes[3]);
			document.getElementById("tablaDocumentosEnviar").style.display = "block";
			listaSeleccionadosDocumentos("", id_remision);
			setTimeout("document.getElementById('cuenta').value = partes[9]",1000);
			if (partes[3]!='elaboracion'){
				document.getElementById("dependencias").disabled = true;
				document.getElementById("asunto").disabled = true;
				document.getElementById("justificacion").disabled = true;
				document.getElementById("banco").disabled = true;
				setTimeout("document.getElementById('cuenta').disabled = true",1000);
				
			}
			if (partes[3]=='elaboracion'){
				document.getElementById("tablaSeleccionarDocumentos").style.display = "block";
				setTimeout("consultarDocumentos(partes[9])",1000);
			}else{
				document.getElementById("tablaSeleccionarDocumentos").style.display = "none";
			}
			document.getElementById("divCargando").style.display = "none";

		} 
	}
	ajax.send("id_remision="+id_remision+"&ejecutar=consultarRemisiones");
}

function anularRemision(id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/relacion_generar_comprobantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){				
			//consultarRemisiones(id_remision);
			document.getElementById("dependencias").disabled = true;
			document.getElementById("asunto").disabled = true;
			document.getElementById("justificacion").disabled = true;
			document.getElementById("banco").disabled = true;
			document.getElementById("cuenta").disabled = true;
			
			document.getElementById("divEstado").innerHTML = "<b>Anulado</b>";
			document.getElementById("estado").value = 'anulado';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_remision="+id_remision+"&ejecutar=anularRemision");
}





function aparecerBusqueda(){
	document.getElementById('campoBusqueda').style.display = 'block';
	document.getElementById('boton_buscar').style.display = 'block';
	document.getElementById('texto_buscar').style.display = 'block';
}


function ocultarBusqueda(){
	document.getElementById('campoBusqueda').style.display = 'none';
	document.getElementById('boton_buscar').style.display = 'none';
	document.getElementById('texto_buscar').style.display = 'none';
}



function buscarOrdenEspecifica(){
	id_remision = document.getElementById('id_remision').value;
	estado_tipo = document.getElementById('idtipo_documento').value;
	documento = document.getElementById('origenDocumentos').value;
	if(document.getElementById('tipoCompromisos')){
		tabla = document.getElementById('tipoCompromisos').value;	
	}else{
		tabla = document.getElementById('tipoCausados').value;	
	}
	
	
	busqueda = document.getElementById('campoBusqueda').value;
	var campo_tipo_documento = document.getElementById('campo_tipo_documento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			document.getElementById('listaDocumentos').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("campo_tipo_documento="+campo_tipo_documento+"&id_remision="+id_remision+"&documento="+documento+"&tabla="+tabla+"&estado_tipo="+estado_tipo+"&busqueda="+busqueda+"&ejecutar=consultarSubDocumentos");
	return false;
}