function abrirCerrarObservaciones(){
	if(document.getElementById('divObservaciones').style.display=="block"){
			document.getElementById('divObservaciones').style.display="none";
			document.getElementById('textoContraerObservaciones').innerHTML = "<img src='imagenes/comments.png' title = 'Anotar Observaciones'>";
	}else{
			document.getElementById('divObservaciones').style.display="block";
			document.getElementById('textoContraerObservaciones').innerHTML = "<img src='imagenes/arrow_up.png' title='Cerrar Observaciones'>";
	}
}

function buscarEnArray(array, dato)
{
	// Retorna el indice de la posicion donde se encuentra el elemento en el array o null si no se encuentra
	var x=0;
	while(array[x])
	{
		if(array[x]==dato) return x;
		x++;
	}
	return null;
}




// REGISTRA LOS DATOS BASICOS DE UNA SOLICITUD DE COTIZACION
function registrarSolicitudCotizacion(tipo, justificacion, observaciones, ordenado_por, cedula_ordenado, modo_comunicacion, tipo_actividad)
{
	//alert(modo_comunicacion+" ............ "+tipo_actividad);
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText != ""){
					document.getElementById("id_solicitud").value=ajax.responseText;
					//actualizarBotones(document.getElementById('id_solicitud').value, document.getElementById("estadoOculto").value);
					document.getElementById('botonIngresar').style.display			= "none";
					document.getElementById('botonEspera').style.display 			= "block";
					document.getElementById('botonProcesar').style.display 			= "block";
					document.getElementById("formularioProveedores").style.display 	= "block";
					document.getElementById("formularioMateriales").style.display 	= "block";
					document.getElementById("divTablaProveedores").style.display = "block";
				}
			document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("tipo="+tipo+"&justificacion="+justificacion+"&observaciones="+observaciones+"&ordenado_por="+ordenado_por+"&cedula_ordenado="+cedula_ordenado+"&modo_comunicacion="+modo_comunicacion+"&tipo_actividad="+tipo_actividad+"&ejecutar=solicitud");
}





// REGISTRA LOS PROVEEDORES DE UNA SOLICITUD
function procesarProveedor(idbeneficiario, id_solicitud){
	if(idbeneficiario== ""){
		mostrarMensajes("error", "Disculpe no hay Proveedores Seleccionados");
	}else{
		var ajax=nuevoAjax();
			ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText != "repetido"){
						document.getElementById("nombreBeneficiario").value="";
						document.getElementById("rifBeneficiario").value="";
						document.getElementById("idBeneficiario").value="";
						document.getElementById("divContenidoProveedores").innerHTML=ajax.responseText;
					}else{
						mostrarMensajes("error", "Disculpe ya ese proveedor esta seleccionado");
						document.getElementById("idBeneficiario").value="";
						document.getElementById("nombreBeneficiario").value="";
						document.getElementById("rifBeneficiario").value="";
					}
				document.getElementById("divCargando").style.display = "none";
				consultarMateriales(id_solicitud);
				}
			
			}
			ajax.send("id_solicitud="+id_solicitud+"&idbeneficiario="+idbeneficiario+"&ejecutar=proveedores&accion=ingresar");
	}
}


// ELIMINA LOS PROVEEDORES DE LA LISTA DE SOLICITUD DE COTIZACION
function eliminarProveedor(id_solicitud, idbeneficiario){
	var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById("divContenidoProveedores").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
				consultarMateriales(id_solicitud);
			}
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&idbeneficiario="+idbeneficiario+"&ejecutar=proveedores&accion=eliminar");
}




// REGISTRA LOS MATRIALES SELCCIONADOS DE UNA SOLICITUD DE COTIZACION
function procesarMateriales(id_solicitud, id_material, cantidad){
	if(id_material == ""){
		mostrarMensajes("error", "No hay ningun Material Seleccionado");
	}else{
		if(cantidad == ""){
			mostrarMensajes("error", "Disculpe Ingrese una Cantidad");
			document.divTablaProveedores.cantidad.focus();
		}else{

			var ajax=nuevoAjax();
				ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
				ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
				ajax.onreadystatechange=function(){ 
					if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}
					if (ajax.readyState==4){
						//alert(ajax.responseText);
						if(ajax.responseText != "repetido"){
							actualizarCantidad(id_solicitud);
							document.getElementById("codigo_material").value="";
							document.getElementById("descripcion_material").value="";
							document.getElementById("unidad_medida").value="";
							document.getElementById("cantidad").value="";
							document.getElementById("id_material").value="";
							document.getElementById("divContenidoMateriales").innerHTML=ajax.responseText;
						}else{
							mostrarMensajes("error", "Disculpe ya ese Articulo esta seleccionado");
							document.getElementById("id_material").value="";							
							document.getElementById("codigo_material").value="";
							document.getElementById("descripcion_material").value="";
							document.getElementById("unidad_medida").value="";
							document.getElementById("cantidad").value="";
							}
						document.getElementById("divCargando").style.display = "none";
					}

				}
				ajax.send("id_solicitud="+id_solicitud+"&id_material="+id_material+"&cantidad="+cantidad+"&ejecutar=materiales&accion=ingresar");
		}
	}
}


// ELIMINA LOS MATERIALES SELECCIONADOS EN UNA SOLICITUD DE COTIZACION
function eliminarMateriales(id_solicitud, id_material){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}			
			if (ajax.readyState==4){
					document.getElementById("divContenidoMateriales").innerHTML=ajax.responseText;
					actualizarCantidad(id_solicitud);
					document.getElementById("divCargando").style.display = "none";
			} 
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&id_material="+id_material+"&ejecutar=materiales&accion=eliminar");
}






	
// CONSULTA LOS MATERISLAES DE UNA SOLICITUD Y ACUALIZA LA LISTA DE LOS MISMOS
function consultarMateriales(id_solicitud){
		var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4)
			{
					document.getElementById("divContenidoMateriales").innerHTML=ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
			
	}
	ajax.send("id_solicitud="+id_solicitud+"&ejecutar=materiales&accion=consultar");	
}


// CONSULTA LOS DATOS BASICOS DE UNA SOLICITUD Y ACTUALIZA EL FORMULARIO
function consultarDatosBasicos(id_solicitud){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}			
			if (ajax.readyState==4){
					//document.getElementById("divDatosBasicos").innerHTML=ajax.responseText;
					partes = ajax.responseText.split("|.|");
					document.getElementById("id_solicitud").value 			= partes[0];
							
						//alert("aqui "+partes[3]);
						if (partes[1] != ''){
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>'+partes[1]+'</STRONG>';
						}else{
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>Aun no generado</STRONG>';	
						}
						if (partes[2] != '0000-00-00'){
							document.getElementById("fecha_consulta").value 		= partes[2];
						}else{
							document.getElementById("fecha_consulta").value 		= '';
						}
						document.getElementById("celdaEstado").innerHTML 			= '<STRONG>'+partes[4]+'</STRONG>';
						document.getElementById("estadoOculto").value 				= partes[3];
						
						document.getElementById("tipo").value						= partes[5];
						if (partes[3] != 'espera'){
							document.getElementById("tipo").disabled 				= true;
						}else{
							document.getElementById("tipo").disabled 				= false;
						}
						
						document.getElementById("divCantidadItems").value 			= '<STRONG>'+partes[6]+'</STRONG>';
											
						document.getElementById("justificacion").value 				= partes[7];
						if (partes[3] != 'espera'){
							document.getElementById("justificacion").disabled 		= true;
						}else{
							document.getElementById("justificacion").disabled 		= false;
						}
						
						document.getElementById("observaciones").value 				= partes[8];
						if (partes[3] != 'espera'){
							document.getElementById("observaciones").disabled 		= true;
						}else{
							document.getElementById("observaciones").disabled 		= false;
						}
						
						document.getElementById("modo_comunicacion").value 			= partes[9];
						document.getElementById("tipo_actividad").value 			= partes[10];
						document.getElementById("ordenado_por").value 				= partes[11];
						document.getElementById("cedula_ordenado").value 			= partes[12];
						if (partes[3] != 'espera'){
							document.getElementById("ordenado_por").disabled 		= true;
							document.getElementById("cedula_ordenado").disabled 	= true;
							document.getElementById("modo_comunicacion").disabled	= true;
							document.getElementById("tipo_actividad").disabled		= true;
						}else{
							document.getElementById("ordenado_por").disabled 		= false;
							document.getElementById("cedula_ordenado").disabled 	= false;
							document.getElementById("modo_comunicacion").disabled	= false;
							document.getElementById("tipo_actividad").disabled		= false;
						}
						
					//actualizarBotones(document.getElementById('id_solicitud').value, document.getElementById("estadoOculto").value);
			}
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&ejecutar=datosBasicos&accion=consultar");
}



/*********************************************************************************************************************************************************
*************************************** ACTUALIZA LOS BOTONES DE PROCESAR; DUPLICAR; ANULAR; EN ELABORACION **********************************************
**********************************************************************************************************************************************************/



function actualizarBotones(id_solicitud, estado){
	estado = document.getElementById("estadoOculto").value;
	//alert(estado);
	if (estado == 'espera'){
		document.getElementById('botonIngresar').style.display			= "none";
		document.getElementById('botonEspera').style.display 			= "block";
		document.getElementById('botonProcesar').style.display 			= "block";
		document.getElementById("formularioProveedores").style.display 	= "block";
		document.getElementById("formularioMateriales").style.display 	= "block";
	}
	if (estado == 'procesado'){
		document.getElementById('botonIngresar').style.display			= "none";
		document.getElementById('botonEspera').style.display 			= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonGanador').style.display 			= "block";
		document.getElementById('botonAnular').style.display 			= "block";
		document.getElementById("formularioProveedores").style.display 	= "none";
		document.getElementById("formularioMateriales").style.display 	= "none";
		document.getElementById('celdaImprimir').style.display			='block';
	}
	if (estado == 'otorgado'){
		document.getElementById('botonIngresar').style.display			= "none";
		document.getElementById('botonEspera').style.display 			= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonGanador').style.display 			= "none";
		document.getElementById('botoFinalizar').style.display 			= "block";
		document.getElementById('botonAnular').style.display 			= "block";
		document.getElementById("formularioProveedores").style.display 	= "none";
		document.getElementById("formularioMateriales").style.display 	= "none";
		document.getElementById('celdaImprimir').style.display			='block';
	}
	if (estado == 'finalizado' || estado == 'ordenado'){
		document.getElementById('botoFinalizar').style.display 			= "none";
		document.getElementById('botonAnular').style.display 			= "block";
		document.getElementById('celdaImprimir').style.display			= 'block';
	}
	if (estado == 'anulado'){
		document.getElementById('botonAnular').style.display			= "none";
		document.getElementById('botonIngresar').style.display			= "none";
		document.getElementById('botonEspera').style.display 			= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonGanador').style.display 			= "none";
		document.getElementById('botoFinalizar').style.display 			= "none";
	}
	if (estado == 'procesado' || estado == 'otorgado' || estado == 'ordenado' || estado == 'finalizado' || estado == 'anulado'){
		document.getElementById('botonIngresar').style.display			= "none";
		document.getElementById('botonDuplicar').style.display 			= "block";
		document.getElementById("formularioProveedores").style.display 	= "none";
		document.getElementById("formularioMateriales").style.display 	= "none";
	}
	
	
}








// CONSULTA LOS PROVEEDORES DE UNA SOLICITUD Y ACTUALIZA LA LISTA DE LOS MISMOS
function consultarProveedores(id_solicitud){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
			
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divContenidoProveedores").innerHTML=ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
					consultarMateriales(id_solicitud);
			}
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&ejecutar=proveedores&accion=consultar");	
}



// CONSULTA LA SOLICITUD QUE SE BUSCA EN UNA LISTA DE SOLICITUDES DE COTIZACION
function consultarSolicitud(id_solicitud){
	// CONSULTAR DATOS BASICOS

		document.getElementById("divTablaProveedores").style.display = "block";
		consultarDatosBasicos(id_solicitud);
		consultarProveedores(id_solicitud);
		consultarMateriales(id_solicitud);
		actualizarCantidad(id_solicitud);
		consultarTotales(id_solicitud);
		setTimeout("actualizarBotones(id_solicitud, document.getElementById('estadoOculto').value)",600);
		
}


// ACTUALIZA LA INFORMACION DE CABEZERA DE UNA SOLICITUD DE COTIZACION
function actualizarInformacionBasica(id_solicitud, tipo, justificacion, observaciones, ordenado_por, cedula_ordenado, modo_comunicacion, tipo_actividad){
	//alert(modo_comunicacion+" ..... "+tipo_actividad);
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Consulta de Precios Guardada con Exito");
			}else{
				mostrarMensajes("error", "Disculpe no se pudo Actualizar la informacion de la Consulta, por Favor Intente mas Tarde");
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	
	}
	ajax.send("modo_comunicacion="+modo_comunicacion+"&tipo_actividad="+tipo_actividad+"&id_solicitud="+id_solicitud+"&tipo="+tipo+"&justificacion="+justificacion+"&observaciones="+observaciones+"&ordenado_por="+ordenado_por+"&cedula_ordenado="+cedula_ordenado+"&ejecutar=actualizarSolicitud");
}








// EJECUTA LA ACCION DE PROCESAR UNA SOLICITUD DE COTIZACION
function procesar(id_solicitud, tipo, justificacion, observaciones, ordenado_por, cedula_ordenado){
	if(confirm("Realmente desea Procesar la Solicitud?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Consulta de Precios Procesada con Exito");
					consultarSolicitud(id_solicitud);
				}else if(ajax.responseText == "noProveedores"){
					mostrarMensajes("error", "No puede procesar la Consulta si no tiene agregado al menos un proveedor");
				}else if(ajax.responseText == "noMateriales"){
					mostrarMensajes("error", "No puede procesar la Consulta si no tiene agregado al menos un Articulo");
				}else{
					mostrarMensajes("error", "Disculpe no se pudo procesar la Consulta");
				}
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&tipo="+tipo+"&justificacion="+justificacion+"&observaciones="+observaciones+"&ordenado_por="+ordenado_por+"&cedula_ordenado="+cedula_ordenado+"&ejecutar=procesar");
	}
}


// EJECUTA LA ACCION DE DUPLICAR UNA SOLICITUD DE COTIZACION
function duplicarSolicitud(id_solicitud){
	if(confirm("Realmente desea Duplicar esta Solicitud?")){
		var ajax=nuevoAjax();
			ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
				if (ajax.readyState==4)
				{
					consultarSolicitud(ajax.responseText);
					actualizarCantidad(document.getElementById("id_solicitud").value);
					document.getElementById("divCargando").style.display = "none";
				} 
			
			}
			ajax.send("id_solicitud="+id_solicitud+"&ejecutar=duplicar");
	}
}
	
	

// VALIDA QUE LA CONTRASE&ntilde;A QUE COLOCA EL USUARIO PARA ANULAR UNA SOLICITUD SEA CORRECTA
function validarAnulacion(id_solicitud,clave){
	if(confirm("Realmente desea anular esta Solicitud?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}		
				if (ajax.readyState==4){
					if(ajax.responseText == "fallo"){
						mostrarMensajes("error", "Disculpe la clave es Incorrecta");
						setTimeout("window.location.href='principal.php?accion=242&modulo=3'",5000);
					}else{
						anularSolicitud(id_solicitud);
					}
					document.getElementById("divCargando").style.display = "none";
				}
			
			}
			ajax.send("id_solicitud="+id_solicitud+"&claveValidacion="+clave+"&ejecutar=verificarUsuario");
	}
}


// EJECUTA LA ACCION DE ANULAR UNA SOLICITUD DE COTIZACION
function anularSolicitud(id_solicitud){
	var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}		
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Consulta Anulada con Exito");
					document.getElementById('divPreguntarUsuario').style.display = 'none';
					consultarSolicitud(id_solicitud);	
				}
			document.getElementById("divCargando").style.display = "none";
			} 
		
		}
		ajax.send("id_solicitud="+id_solicitud+"&ejecutar=anular");
}


// MUESTRA LOS RADIO PARA SELECCIONAR EL GANADOR DE LA SOLICITUD DE COTIZACION
function mostrarSelectGanador(id_solicitud){
	var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}	
			if (ajax.readyState==4){
				document.getElementById('tituloGanador').style.display='block';
				valor = parseInt(ajax.responseText,10);
				for(i=0; i < valor; i++){
				document.getElementById('selectGanador'+i).style.display='block';
				}
			document.getElementById("divCargando").style.display = "none";
			} 
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&ejecutar=cantidadProveedores");
	
	}


function abrirCerrarProveedores(){
	if(document.getElementById('divContenidoProveedores').style.display=="block"){
			document.getElementById('divContenidoProveedores').style.display="none";
			document.getElementById('formularioProveedores').style.display="none";
			document.getElementById('textoContraerProveedores').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('divContenidoProveedores').style.display="block";
			document.getElementById('formularioProveedores').style.display="block";
			document.getElementById('textoContraerProveedores').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
	}
}


function abrirCerrarMateriales(){
	if(document.getElementById('divContenidoMateriales').style.display=="block"){
			document.getElementById('divContenidoMateriales').style.display="none";
			document.getElementById('formularioMateriales').style.display="none";
			document.getElementById('textoContraerMateriales').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('formularioMateriales').style.display="block";
			document.getElementById('divContenidoMateriales').style.display="block";
			document.getElementById('textoContraerMateriales').innerHTML = "<img src='imagenes/cerrar.gif' title = 'Cerrar'>";
	}
}


function registrarGanador(){



var id_solicitud = document.getElementById('id_solicitud').value;
var id_beneficiario = document.getElementById('id_radio').value;
var tipo = document.getElementById("tipo_procedimiento").value
var justificacion = document.getElementById("justificacionGanador").value;
var formato= document.getElementById("formato").value;
var articulo = document.getElementById("articulo").value;
var numeral = document.getElementById("numeral").value;

var datos = "";		
	
for(i=0;i<document.formulario_ganador.elements.length; i++){
	if(document.formulario_ganador.elements[i].name == "idparticipante"){
		
		datos = datos+""+document.formulario_ganador.elements[i].value+",";
	}else if(document.formulario_ganador.elements[i].name == "descuento"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";	
	}else if(document.formulario_ganador.elements[i].name == "garantia_servicio"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";
	}else if(document.formulario_ganador.elements[i].name == "calidad"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";
	}else if(document.formulario_ganador.elements[i].name == "gtos_flt"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";
	}else if(document.formulario_ganador.elements[i].name == "emp_local"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";	
	}else if(document.formulario_ganador.elements[i].name == "experiencia"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";	
	}else if(document.formulario_ganador.elements[i].name == "monto_total"){
		datos = datos+""+document.formulario_ganador.elements[i].value+",";	
	}else if(document.formulario_ganador.elements[i].name == "nro_cotizacion"){	
		datos = datos+""+document.formulario_ganador.elements[i].value+";";	
	}
}	
	


var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){

			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Ganador Registrado con Exito");
				consultarSolicitud(id_solicitud);		
			}else{
				mostrarMensajes("error", "No se pudo Registrar el Ganador: "+ajax.responseText);
			}
		document.getElementById("divCargando").style.display = "none";
		}
	} 
	ajax.send("id_solicitud="+id_solicitud+"&idbeneficiario="+id_beneficiario+"&tipo="+tipo+"&justificacion="+justificacion+"&formato="+formato+"&articulo="+articulo+"&numeral="+numeral+"&datos="+datos+"&ejecutar=registrarGanador");
}


function cargarDatosGanador(tipo_procedumiento, justificacion, nro_cotizacion, fecha_cotizacion){
	document.getElementById('divTipoProcedimiento').innerHTML = "<b>"+tipo_procedumiento+"</b>";
	document.getElementById('divJustificacionGanador').innerHTML = "<b>"+justificacion+"</b>";
	document.getElementById('divNumeroCotizacion').innerHTML = "<b>"+nro_cotizacion+"</b>";
	document.getElementById('divFechaCotizacion').innerHTML = "<b>"+fecha_cotizacion+"</b>";
	document.getElementById('botonRegistrarGanador').style.display = "none";
}


function actualizarPrecio(id_solicitud,precio,id_articulo, id_articulo_cotizacion){
if(precio != ""){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			consultarMateriales(id_solicitud);	
			document.getElementById('totales').innerHTML = ajax.responseText;	
			document.getElementById("divCargando").style.display = "none";
		}
		
	} 
	ajax.send("id_articulo_cotizacion="+id_articulo_cotizacion+"&id_solicitud="+id_solicitud+"&id_articulo="+id_articulo+"&precio="+precio+"&ejecutar=guardarPrecio");
}else{
	mostrarMensajes("error", "Para Actualizar el Precio del Producto, Primero debe escribir el Precio Unitario del Mismo");
	}
}




function consultarTotales(id_solicitud){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('totales').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		}
		
	} 
	ajax.send("id_solicitud="+id_solicitud+"&ejecutar=consultarTotales");
}



function finalizarSolicitud(id_solicitud){
	if(confirm("Realmente desea Finnalizar la Consulta de Precios?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					consultarSolicitud(id_solicitud);
					mostrarMensajes("exito", "Consulta de Precios Finalizada con Exito");
				}else{
					if(ajax.responseText == "preciosVacios"){
						if(confirm("Algunos de sus productos no tienen precios asignados, Desea eliminar dichos Articulos de la Consulta y finalizar la misma?")==1){
							eliminarMaterialesEnCero(id_solicitud);
						}
					}
					if(ajax.responseText == "soloUnRegistro"){
						mostrarMensajes("error", "Disculpe solo tiene un material y el mismo no tiene un precio, por favor ingreselo");
					}
					if(ajax.responseText == "todosEnCero"){
						mostrarMensajes("error", "Disculpe todos sus precios estan en cero por favor ingrese al menos un precio");
					}
				
				}
			document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("id_solicitud="+id_solicitud+"&ejecutar=finalizarSolicitud");
	}
}



function eliminarMaterialesEnCero(id_solicitud){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				consultarTotales(id_solicitud);
				finalizarSolicitud(id_solicitud);
				actualizarCantidad(id_solicitud);
			}else{
				mostrarMensajes("error", "Los Materiales no pudieron ser Eliminados");
			}
			
		document.getElementById("divCargando").style.display = "none";	
		}
	
	} 
	ajax.send("id_solicitud="+id_solicitud+"&ejecutar=materialesEnCero");
}



function asignarValor(precioOculto, precioMostrado){
	document.getElementById(precioOculto).value = "";
	var valorMostrado = document.getElementById(precioMostrado).value;
	var tamanioMostrado = valorMostrado.length;
	for (var i = 0; i < tamanioMostrado; i++){
		var ultimaLetra = valorMostrado.charAt(i);
		if(ultimaLetra == ","){
			document.getElementById(precioOculto).value = document.getElementById(precioOculto).value+".";
		}else{
			if(!isNaN(ultimaLetra)){
				document.getElementById(precioOculto).value = document.getElementById(precioOculto).value+ultimaLetra;
			}
		}
	}
}





// ACTUALIZA LA CANTIDAD DE MATERIALES SELECCIONADOS PARA UNA SOLICITUD DE COTIZACION					
function actualizarCantidad(id_solicitud){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/solicitud_cotizacion/solicitud_cotizaciones_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
					document.getElementById("divCantidadItems").innerHTML=ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
		
		}
		ajax.send("id_solicitud="+id_solicitud+"&ejecutar=consultaItems");
}