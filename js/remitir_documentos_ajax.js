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



/*********************************************************************************************************************************************************
************************************************** INGRESAR DATOS BASICOS ********************************************************************************
**********************************************************************************************************************************************************/

function ingresarDatosBasicos(para, asunto, justificacion, id_remision){
	if(id_remision == ""){
		if(para == 0){
			mostrarMensajes("error", "Disculpe seleccione un destino");
			document.getElementById('dependencias').focus();
		}else if(asunto == ""){
			mostrarMensajes("error", "Disculpe escriba un asunto");
			document.getElementById('asunto').focus();
		}else if(justificacion == ""){
			mostrarMensajes("error", "Disculpe escriba la justificacion");
			document.getElementById('justificacion').focus();
		}else{
				var ajax=nuevoAjax();
				ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function() { 
					if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
							}
					if (ajax.readyState==4){
							document.getElementById("tablaDatosBasicos").innerHTML = ajax.responseText;
							document.getElementById("tablaSeleccionarDocumentos").style.display = "block";
							document.getElementById("tablaDocumentosEnviar").style.display = "block";
							document.getElementById("divCargando").style.display = "none";
					} 
				}
				ajax.send("para="+para+"&asunto="+asunto+"&justificacion="+justificacion+"&id_remision="+id_remision+"&ejecutar=ingresarDatosBasicos");					
		}
		
		
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					document.getElementById("tablaDatosBasicos").innerHTML = ajax.responseText;
					document.getElementById("tablaSeleccionarDocumentos").style.display = "block";
					document.getElementById("tablaDocumentosEnviar").style.display = "block";
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("para="+para+"&asunto="+asunto+"&justificacion="+justificacion+"&id_remision="+id_remision+"&ejecutar=ingresarDatosBasicos");	
	}
}

function actualizarDatosBasicos(id_remision, para, asunto, justificacion){
	var ajax=nuevoAjax();
			ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}
				if (ajax.readyState==4){
						ingresarDatosBasicos("", "", "", id_remision);
						document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("para="+para+"&asunto="+asunto+"&justificacion="+justificacion+"&id_remision="+id_remision+"&ejecutar=actualizarDatosBasicos");
}





function consultarDocumentos(documento){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById('subOrigenDocumentos').innerHTML = ajax.responseText;	
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("documento="+documento+"&ejecutar=consultarDocumentos");
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







function listaSeleccionadosDocumentos(id, id_remision, documento, accion, tabla, estado_tipo){

	var ajax=nuevoAjax();
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				if(accion != "consultar"){
					buscarOrdenEspecifica();
				}
				document.getElementById("estado").value = estado_tipo;
				document.getElementById("tabla").value = tabla;
				document.getElementById('divListaSeleccionadosDocumentos').innerHTML = ajax.responseText;
				consultarCantidadDocumentos(id_remision);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_documento="+id+"&id_remision="+id_remision+"&tabla="+tabla+"&estado_tipo="+estado_tipo+"&accion="+accion+"&ejecutar=listaSeleccionadosDocumentos");	
}






function eliminarListaSeleccionadosDocumentos(id, tipo, id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){				
			listaSeleccionadosDocumentos("", id_remision, "", "consultar", document.getElementById("tabla").value, document.getElementById("estado").value);	
			consultarCantidadDocumentos(id_remision);
			buscarOrdenEspecifica();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id="+id+"&tipo="+tipo+"&id_remision="+id_remision+"&ejecutar=eliminarListaSeleccionadosDocumentos");

}

function consultarCantidadDocumentos(id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
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
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
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
				listaSeleccionadosDocumentos("", id_remision, "", "consultar", document.getElementById("tabla").value, document.getElementById("estado").value);	
				var fecha = new Date();
				var dia = fecha.getDate();
				var anio = fecha.getFullYear();
				var mes = fecha.getMonth()+1;
				document.getElementById("divFechaEnvio").innerHTML = "<b>"+dia+"-"+mes+"-"+anio+"</b>";
				document.getElementById("botonEnviar").style.display = "none";
				document.getElementById("botonElaboracion").style.display = "none";
				document.getElementById("tablaSeleccionarDocumentos").style.display = "none";
				//document.getElementById("tablaDocumentosEnviar").style.display = "none";
				document.getElementById("dependencias").disabled = true;
				document.getElementById("asunto").disabled = true;
				document.getElementById("justificacion").disabled = true;
				document.getElementById("divCargando").style.display = "none";
				consultarRemisiones(id_remision);
			}
		} 
	}
	ajax.send("id_remision="+id_remision+"&ejecutar=remitirDocumentos");	
}

function consultarRemisiones(id_remision){
		document.getElementById("tablaSeleccionarDocumentos").style.display = "block";
		document.getElementById("tablaDocumentosEnviar").style.display = "block";
		ingresarDatosBasicos("", "", "", id_remision);
		listaSeleccionadosDocumentos("", id_remision, "", "consultar", "", "");
		consultarCantidadDocumentos(id_remision);
}

function anularRemision(id_remision){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/remision_documentos/remitir_documentos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){				
			consultarRemisiones(id_remision);
			document.getElementById("dependencias").disabled = true;
			document.getElementById("asunto").disabled = true;
			document.getElementById("justificacion").disabled = true;
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