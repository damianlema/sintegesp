// JavaScript Document
/*********************************************************************************************************************************************************
************************************************** FUNCION PARA ABRIR Y CERRAR LAS OBSERVACIONES ****************************************************
**********************************************************************************************************************************************************/

function abrirCerrarObservaciones(){
	if(document.getElementById('divObservaciones').style.display=="block"){
			document.getElementById('divObservaciones').style.display="none";
			document.getElementById('textoContraerObservaciones').innerHTML = "<img src='imagenes/comments.png' title = 'Anotar Observaciones'>";
	}else{
			document.getElementById('divObservaciones').style.display="block";
			document.getElementById('textoContraerObservaciones').innerHTML = "<img src='imagenes/arrow_up.png' title='Cerrar Observaciones'>";
	}
}




/*********************************************************************************************************************************************************
************************************************** FUNCION PARA ABRIR Y CERRAR LOS DATOS EXTRA ****************************************************
**********************************************************************************************************************************************************/


function abrirCerrarDatosExtra(){
	if(document.getElementById('datosExtra').style.display=="block"){
			document.getElementById('datosExtra').style.display="none";
			document.getElementById('textoContraerDatosExtra').innerHTML = "Origen Presupuestario";
	}else{
			document.getElementById('datosExtra').style.display="block";
			document.getElementById('textoContraerDatosExtra').innerHTML = "Ocultar";
	}
}

/*********************************************************************************************************************************************************
************************************************** FUNCION PARA ABRIR Y CERRAR LAS LISTAD DE PARTIDAS ****************************************************
**********************************************************************************************************************************************************/


function abrirCerrarPartidas(){
	if(document.getElementById('divPartidas').style.display=="block"){
			document.getElementById('divPartidas').style.display="none";
			//document.getElementById('formularioPartidas').style.display="none";
			document.getElementById('textoContraerPartidas').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('divPartidas').style.display="block";
			//document.getElementById('formularioPartidas').style.display="block";
			document.getElementById('textoContraerPartidas').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
	}
}


/*********************************************************************************************************************************************************
*************************************************** FUNCION PARA ABRIR Y CERRAR LAS LISTAS DE MATERIALES *************************************************
**********************************************************************************************************************************************************/


function abrirCerrarMateriales(){
	if(document.getElementById('divMateriales').style.display=="block"){
			document.getElementById('divMateriales').style.display="none";
			document.getElementById('formularioMateriales').style.display="none";
			document.getElementById('textoContraerMateriales').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('formularioMateriales').style.display="block";
			document.getElementById('divMateriales').style.display="block";
			document.getElementById('textoContraerMateriales').innerHTML = "<img src='imagenes/cerrar.gif' title = 'Cerrar'>";
	}
}


/*********************************************************************************************************************************************************
********************************************* FUCION PARA ABRIR Y CERRAR LOS PROVEEDORES *****************************************************************
**********************************************************************************************************************************************************/


function abrirCerrarProveedores(){
	if(document.getElementById('formularioProveedores').style.display=="block"){
			document.getElementById('tablaProveedor').width='800';
			document.getElementById('formularioProveedores').style.display="none";
			document.getElementById('textoContraerProveedores').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('formularioProveedores').style.display="block";
			document.getElementById('textoContraerProveedores').innerHTML = "<img src='imagenes/cerrar.gif' title = 'Cerrar'>";
	}
}


/*********************************************************************************************************************************************************
*********************************************** FUNCION PARA RECALCULAR LA REQUISICION *******************************************************************
**********************************************************************************************************************************************************/

function recalcular(){
	var id_requisicion = document.getElementById('id_requisicion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				//alert("Recalculado con exito, por favor consulte nuevamente la orden");
				consultarOrdenCompra(id_requisicion, id_categoria_programatica);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_requisicion="+id_requisicion+"&ejecutar=recalcular");
}




/*********************************************************************************************************************************************************
*********************************************** FUNCION PARA CONSULTAR LOS PEDIDOS DE LOS PROVEEDORES ****************************************************
**********************************************************************************************************************************************************/


function consultarPedidosProveedores(id_beneficiarios, tipo, id_requisicion){
		var cofinanciamiento = document.getElementById('cofinanciamiento').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				document.getElementById("listaSolicitudesProveedor").style.display = "block";
				if(ajax.responseText != "noTieneGanadas"){
					document.getElementById("listaSolicitudesProveedor").innerHTML=ajax.responseText;
					document.getElementById("solicitudes").value="siTiene";
				}else{
					document.getElementById("listaSolicitudesProveedor").innerHTML="Este Proveedor no tiene niguna solicitud Finalizada";
					document.getElementById("solicitudes").value="noTiene";
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_beneficiarios="+id_beneficiarios+"&id_requisicion="+id_requisicion+"&tipo="+tipo+"&ejecutar=consultarSolicitudesProveedor");
}


/*********************************************************************************************************************************************************
***************************************************** REFRESCAR LA LISTA DE SOLICITUDES DE COTIZACION POR PROVEEDOR **************************************
**********************************************************************************************************************************************************/


function refrescarListaSolicitudes(id_beneficiarios, tipo, id_requisicion){
	if(document.getElementById('listaSolicitudesProveedor').style.display == 'block'){
			consultarPedidosProveedores(id_beneficiarios, tipo, id_requisicion);
	}
}


/*********************************************************************************************************************************************************
****************************************** FUNCION PARA SELECCIONAR Y DESELECCIONAR LAS SOLICITUDES DE LA LISTA ******************************************
**********************************************************************************************************************************************************/


function seleccionDeseleccionListaSolicitud(cantRegistros){
		var seleccionados = 0;
		formulario = document.getElementById("formSolicitudesFinalizadas");
		for(var i=0; i<formulario.elements.length; i++) {
		  var elemento = formulario.elements[i];
		  if(elemento.type == "checkbox") {
			if(elemento.checked) {
			  seleccionados++;
			}
		  }
		}

		if(seleccionados > 0){
			document.getElementById('tipo_orden').disabled=true;
			document.getElementById('proceso').disabled=true;
			document.getElementById('buscarProveedor').style.display='none';
			document.getElementById('buscarCategoriaProgramatica').style.display='none';
			document.getElementById('anio').disabled = true;
			document.getElementById('fuente_financiamiento').disabled = true;
			document.getElementById('tipo_presupuesto').disabled = true;
			//document.getElementById('buscarOrdinal').style.display='none';
		}else{
			document.getElementById('tipo_orden').disabled=false;
			document.getElementById('buscarProveedor').style.display='block';
			document.getElementById('proceso').disabled=false;
			document.getElementById('buscarCategoriaProgramatica').style.display='block';
			document.getElementById('anio').disabled = false;
			document.getElementById('fuente_financiamiento').disabled = false;
			document.getElementById('tipo_presupuesto').disabled = false;
			//document.getElementById('buscarOrdinal').style.display='block';
		}
		
		//actualizarDatosBasicos();
	
}



/*********************************************************************************************************************************************************
************************************************ DESELECCION LISTA DE SOLICITUDES DE COTIZACION **********************************************************
**********************************************************************************************************************************************************/


function deseleccionarListaSolicitud(id_solicitud){
		formulario = document.getElementById("formSolicitudesFinalizadas");
		for(var i=0; i<formulario.elements.length; i++) {
		  var elemento = formulario.elements[i];
		  if(elemento.type == "checkbox") {
			if(elemento.value == id_solicitud) {
			  elemento.checked = 0;
			}
		  }
		}


		var seleccionados = 0;
		formulario = document.getElementById("formSolicitudesFinalizadas");
		for(var i=0; i<formulario.elements.length; i++) {
		  var elemento = formulario.elements[i];
		  if(elemento.type == "checkbox") {
			if(elemento.checked) {
			  seleccionados++;
			}
		  }
		}

		if(seleccionados > 0){
			document.getElementById('tipo_orden').disabled=true;
			document.getElementById('proceso').disabled=true;
			document.getElementById('buscarProveedor').style.display='none';
			document.getElementById('buscarCategoriaProgramatica').style.display='none';
			document.getElementById('anio').disabled = true;
			document.getElementById('fuente_financiamiento').disabled = true;
			document.getElementById('tipo_presupuesto').disabled = true;
			//document.getElementById('buscarOrdinal').style.display='none';
		}else{
			document.getElementById('tipo_orden').disabled=false;
			document.getElementById('buscarProveedor').style.display='block';
			document.getElementById('proceso').disabled=false;
			document.getElementById('buscarCategoriaProgramatica').style.display='block';
			document.getElementById('anio').disabled = false;
			document.getElementById('fuente_financiamiento').disabled = false;
			document.getElementById('tipo_presupuesto').disabled = false;
			//document.getElementById('buscarOrdinal').style.display='block';
		}
		
		//actualizarDatosBasicos();

	
}


/*********************************************************************************************************************************************************
********************************************************* AGREGAR MATERIALES *****************************************************************************
**********************************************************************************************************************************************************/



function agregarMateriales(id_solicitud, id_requisicion, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal,contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("divMateriales").innerHTML=ajax.responseText;
				listarSolicitudesSeleccionadas(id_requisicion);
				actualizarListaDeTotales(id_requisicion);
				actualizarTotalesPartidas(id_requisicion);
				mostrarPartidas(id_requisicion, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_solicitud="+id_solicitud+"&id_requisicion="+id_requisicion+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=materiales&accion=ingresarSolicitudCreada"+"&contribuyente_ordinario="+contribuyente_ordinario);
}


/*********************************************************************************************************************************************************
********************************************************* INGRESAR DATOS BASICOS *************************************************************************
**********************************************************************************************************************************************************/



function ingresarDatosBasicos(tipo_orden, categoria_programatica, justificacion, observaciones, id_beneficiarios, anio, fuente_financiamiento, tipo_presupuesto, id_ordinal){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(justificacion == "" && observaciones == "" && id_beneficiarios == "" && categoria_programatica == ""){
		mostrarMensajes("error", "Debe llenar todos los datos para poder pasar al siguiente paso de la requisicion");
	}else{
		var ajax=nuevoAjax();
			ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
				if (ajax.readyState==4){
					if(ajax.responseText != "fallo"){
						document.getElementById('botonSiguiente').style.display = "none";
						document.getElementById("id_requisicion").value=ajax.responseText;
						document.getElementById('tablaProveedor').style.display='block';
						document.getElementById('tablaMaterialesPartidas').style.display='block';
						actualizarDatosBasicos("consultar");
						document.getElementById("divCargando").style.display = "none";
					}else{
						mostrarMensajes("error", "Los datos Basicos de la Requisicion no puedieron ingresarse con exito");
						}
				} 
			}
			ajax.send("cofinanciamiento="+cofinanciamiento+"&tipo_orden="+tipo_orden+"&categoria_programatica="+categoria_programatica+"&justificacion="+justificacion+"&observaciones="+observaciones+"&id_beneficiarios="+id_beneficiarios+"&anio="+anio+"&fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&id_ordinal="+id_ordinal+"&ejecutar=agregarDatosBasicos");
	}
}


/*********************************************************************************************************************************************************
************************************************** LISTA DE SOLICITUDES DE COTIZACION SELECCIONADAS ******************************************************
**********************************************************************************************************************************************************/



function listarSolicitudesSeleccionadas(id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("solicitudesSeleccionada").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&ejecutar=listarSolicitudesSeleccionadas");
}



/*********************************************************************************************************************************************************
**************************************************** ACTUALIZAR EL PRECIO Y LA CANTIDAD DE LOS MATERIALES ************************************************
**********************************************************************************************************************************************************/




function actualizarPrecioCantidad(id_requisicion,precio, cantidad,id_articulo, id_articulo_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(cantidad != ""){
		if(precio != ""){
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function(){ 
				if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}				
				if (ajax.readyState==4){
					//alert(ajax.responseText);
					if(ajax.responseText == "exito"){
						
						consultarMateriales(id_requisicion);	
						actualizarListaDeTotales(id_requisicion);
						actualizarTotalesPartidas(id_requisicion);
						mostrarPartidas(id_requisicion, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
						document.getElementById("divCargando").style.display = "none";
					}else{
					}
				}
			} 
			ajax.send("cofinanciamiento="+cofinanciamiento+"&id_articulo_compra="+id_articulo_compra+"&id_requisicion="+id_requisicion+"&id_articulo="+id_articulo+"&precio="+precio+"&cantidad="+cantidad+"&anio="+anio+"&idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto+"&idordinal="+idordinal+"&contribuyente_ordinario="+contribuyente_ordinario+"&ejecutar=actualizarPrecioCantidad");
		}else{
			mostrarMensajes("error", "Para Actualizar el Precio del Producto, Primero debe escribir el Precio Unitario del Mismo");
		}
	}else{
		mostrarMensajes("error", "Para Actualizar la Cantidad del Producto, Primero debe ingresarla");
	}
} 



/*********************************************************************************************************************************************************
***************************************************** CONSULTA LOS MATERIALES DE UNA ORDEN DE COMPRA *****************************************************
**********************************************************************************************************************************************************/



function consultarMateriales(id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
		var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if (ajax.readyState==4)
			{
					document.getElementById("divMateriales").innerHTML=ajax.responseText;
			} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&ejecutar=materiales&accion=consultar");	
}



/*********************************************************************************************************************************************************
******************************************************* ACTUALIZA LOS DATOS BASICOS DE UNA ORDEN DE COMPRA ***********************************************
**********************************************************************************************************************************************************/



function actualizarDatosBasicos(accion, id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	//var id_requisicion = document.getElementById('id_requisicion').value;
		var ajax=nuevoAjax();
	if(id_requisicion){
		ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				document.getElementById("id_requisicion").value 			= id_requisicion;
				
				partes = ajax.responseText.split("|.|");
						//alert(ajax.responseText);
						if (partes[0] != ''){
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>'+partes[0]+'</STRONG>';
						}else{
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>Aun no generado</STRONG>';	
						}
						if (partes[1] != '0000-00-00'){
							document.getElementById("fecha_orden").value 			= partes[1];
						}else{
							document.getElementById("fecha_orden").value 			= '';
						}
						document.getElementById("celdaFechaElaboracion").innerHTML 	= '<STRONG>'+partes[2]+'</STRONG>';
						document.getElementById("idestado").value 					= partes[3];
						
						document.getElementById("celdaEstado").innerHTML 		= partes[4];
						
						document.getElementById("tipo_orden").value					= partes[5];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_orden").disabled 			= true;
						}else{
							document.getElementById("tipo_orden").disabled 			= false;
						}
						
						document.getElementById("id_categoria_programatica").value 	= partes[6];
						document.getElementById("nombre_categoria").value 			= partes[7];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarCategoriaProgramatica").style.display = 'none'
						}else{
							document.getElementById("buscarCategoriaProgramatica").style.display = 'block'
						}
						
						document.getElementById("fuente_financiamiento").value 		= partes[8];
						if (partes[3] != 'elaboracion'){
							document.getElementById("fuente_financiamiento").disabled 	= true;
						}else{
							document.getElementById("fuente_financiamiento").disabled 	= false;
						}
						document.getElementById("cofinanciamiento").value 			= partes[9];
						document.getElementById("tipo_presupuesto").value 			= partes[10];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_presupuesto").disabled 	= true;
						}else{
							document.getElementById("tipo_presupuesto").disabled 	= false;
						}
						document.getElementById("anio").value 						= partes[11];
						if (partes[3] != 'elaboracion'){
							document.getElementById("anio").disabled 				= true;
						}else{
							document.getElementById("anio").disabled 				= false;
						}
						document.getElementById("justificacion").value 				= partes[12];
						if (partes[3] != 'elaboracion'){
							document.getElementById("justificacion").disabled 		= true;
						}else{
							document.getElementById("justificacion").disabled 		= false;
						}
						document.getElementById("observaciones").value 				= partes[13];
						if (partes[3] != 'elaboracion'){
							document.getElementById("observaciones").disabled 		= true;
						}else{
							document.getElementById("observaciones").disabled 		= false;
						}
						
						document.getElementById("id_beneficiarios").value 			= partes[14];
						document.getElementById("nombre_proveedor").value 			= partes[15];
						document.getElementById("contribuyente_ordinario").value	= partes[16];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarProveedor").style.display = 'none';
						}else{
							document.getElementById("buscarProveedor").style.display = 'block'
						}
						
					
					actualizarBotones(document.getElementById('id_requisicion').value, document.getElementById("idestado").value);
				
				document.getElementById("divCargando").style.display = "none";
			} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&accion="+accion+"&ejecutar=actualizarDatosBasicos");	
	
	}else{
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
					partes = ajax.responseText.split("|.|");
						//alert(partes[15]);
						if (partes[0] != ''){
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>'+partes[0]+'</STRONG>';
						}else{
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>Aun no generado</STRONG>';	
						}
						if (partes[1] != '0000-00-00'){
							document.getElementById("fecha_orden").value 			= partes[1];
						}else{
							document.getElementById("fecha_orden").value 			= '';
						}
						document.getElementById("celdaFechaElaboracion").innerHTML 	= '<STRONG>'+partes[2]+'</STRONG>';
						document.getElementById("idestado").value 					= partes[3];
						
						document.getElementById("celdaEstado").innerHTML 		= partes[4]+partes[5];
						
						document.getElementById("tipo_orden").value					= partes[5];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_orden").disabled 			= true;
						}else{
							document.getElementById("tipo_orden").disabled 			= false;
						}
						
						document.getElementById("id_categoria_programatica").value 	= partes[6];
						document.getElementById("nombre_categoria").value 			= partes[7];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarCategoriaProgramatica").style.display = 'none'
						}else{
							document.getElementById("buscarCategoriaProgramatica").style.display = 'block'
						}
						
						document.getElementById("fuente_financiamiento").value 		= partes[8];
						if (partes[3] != 'elaboracion'){
							document.getElementById("fuente_financiamiento").disabled 	= true;
						}else{
							document.getElementById("fuente_financiamiento").disabled 	= false;
						}
						document.getElementById("cofinanciamiento").value 			= partes[9];
						document.getElementById("tipo_presupuesto").value 			= partes[10];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_presupuesto").disabled 	= true;
						}else{
							document.getElementById("tipo_presupuesto").disabled 	= false;
						}
						document.getElementById("anio").value 						= partes[11];
						if (partes[3] != 'elaboracion'){
							document.getElementById("anio").disabled 				= true;
						}else{
							document.getElementById("anio").disabled 				= false;
						}
						document.getElementById("justificacion").value 				= partes[12];
						if (partes[3] != 'elaboracion'){
							document.getElementById("justificacion").disabled 		= true;
						}else{
							document.getElementById("justificacion").disabled 		= false;
						}
						document.getElementById("observaciones").value 				= partes[13];
						if (partes[3] != 'elaboracion'){
							document.getElementById("observaciones").disabled 		= true;
						}else{
							document.getElementById("observaciones").disabled 		= false;
						}
						
						document.getElementById("id_beneficiarios").value 			= partes[14];
						document.getElementById("nombre_proveedor").value 			= partes[15];
						document.getElementById("contribuyente_ordinario").value	= partes[16];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarProveedor").style.display = 'none';
						}else{
							document.getElementById("buscarProveedor").style.display = 'block'
						}
						
					
					actualizarBotones(document.getElementById('id_requisicion').value, document.getElementById("idestado").value);
				
				document.getElementById("divCargando").style.display = "none";
			} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&tipo_orden="+document.getElementById('tipo_orden').value+"&id_requisicion="+document.getElementById('id_requisicion').value+"&id_categoria_programatica="+document.getElementById('id_categoria_programatica').value+"&justificacion="+document.getElementById('justificacion').value+"&observaciones="+document.getElementById('observaciones').value+"&id_beneficiarios="+document.getElementById('id_beneficiarios').value+"&anio="+document.getElementById('anio').value+"&fuente_financiamiento="+document.getElementById('fuente_financiamiento').value+"&tipo_presupuesto="+document.getElementById('tipo_presupuesto').value+"&id_ordinal="+document.getElementById('id_ordinal').value+"&accion="+accion+"&ejecutar=actualizarDatosBasicos");	
	
	}
		
}


/*********************************************************************************************************************************************************
******************************************************** ELIMINAR MATERIALES DE UNA ORDEN DE COMPRA ******************************************************
**********************************************************************************************************************************************************/



function eliminarMateriales(id_requisicion, id_material, id_solicitud, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divMateriales").innerHTML=ajax.responseText;
					listarSolicitudesSeleccionadas(id_requisicion);
					mostrarPartidas(id_requisicion, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
					actualizarListaDeTotales(id_requisicion);
					actualizarTotalesPartidas(id_requisicion);
					if(document.getElementById("eliminoSolicitud").value != ""){
						deseleccionarListaSolicitud(document.getElementById("eliminoSolicitud").value);
					}
					document.getElementById("divCargando").style.display = "none";
					
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&id_material="+id_material+"&id_solicitud="+id_solicitud+"&id_categoria_programatica="+id_categoria_programatica+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&contribuyente_ordinario="+contribuyente_ordinario+"&ejecutar=materiales&accion=eliminar");
}


/*********************************************************************************************************************************************************
**************************************************** ACTUALIZAR LAS LISTAS DE TOTALES DE LOS MATERIALES **************************************************
**********************************************************************************************************************************************************/



function actualizarListaDeTotales(id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('totales').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&ejecutar=actualizarListaDeTotales");
}


/*********************************************************************************************************************************************************
******************************************** FUNCION PARA DARLE FORMATO CORRECTO AL CAMPO DE PRECIO UNITARIO *********************************************
**********************************************************************************************************************************************************/



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



/*********************************************************************************************************************************************************
************************************** FUNCION PARA MOSTRAR LAS PARTIDAS ELACIONADAS A LOS MATERIALES SELECCIONADOS **************************************
**********************************************************************************************************************************************************/



function mostrarPartidas(id_requisicion, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById('divPartidas').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&id_categoria_programatica="+id_categoria_programatica+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=mostrarPartidas");
}


/*********************************************************************************************************************************************************
*************************************** ACTUALIZA LOS BOTONES DE PROCESAR; DUPLICAR; ANULAR; EN ELABORACION **********************************************
**********************************************************************************************************************************************************/



function actualizarBotones(id_requisicion,estado){
	
	
	if (estado == 'elaboracion'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonDuplicar').style.display 			= "none";
		document.getElementById('botonEnElaboracion').style.display 	= "block";
		document.getElementById('botonProcesar').style.display 			= "block";
		document.getElementById('celdaRecalcular').style.display		= 'block';
		document.getElementById('celdaImprimir').style.display			='block';
		document.getElementById('tablaPartidas').style.display			='block';
	}
	if (estado == 'procesado' || estado == 'devuelto' || estado == 'conformado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonEnElaboracion').style.display 	= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonAnular').style.display 			= "block";
		document.getElementById('botonDuplicar').style.display 			= "block";
		document.getElementById('celdaRecalcular').style.display		= 'none';
		document.getElementById('celdaImprimir').style.display			='block';
		document.getElementById('tablaPartidas').style.display			='block';
	}
	if (estado == 'anulado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonEnElaboracion').style.display 	= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonAnular').style.display			= "none";
		document.getElementById('celdaRecalcular').style.display		= 'none';
		document.getElementById('celdaImprimir').style.display			='block';
		document.getElementById('tablaPartidas').style.display			='block';
	}
	if (estado == 'procesado' || estado == 'devuelto' || estado == 'conformado' || estado == 'pagado' || estado == 'anulado' || estado == 'parcial'  || estado == 'ordenado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonDuplicar').style.display 			= "block";
		document.getElementById('celdaRecalcular').style.display		= 'none';
		document.getElementById('celdaImprimir').style.display			='block';
		document.getElementById('tablaPartidas').style.display			='block';
	}
	
}



/*********************************************************************************************************************************************************
**************************************************************** PROCESA LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function procesarOrden(id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var id_requisicion = document.getElementById('id_requisicion').value;
	if(confirm("Realmente desea Procesar esta Requisicion?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Requisicion Procesada con Exito");
						actualizarDatosBasicos("consultar", id_requisicion);
						consultarMateriales(id_requisicion);
					}else if(ajax.responseText == "sinMateriales"){
						mostrarMensajes("error", "Disculpe debe seleccionar materiales para poder procesar la Requisicion");
					}else if (ajax.responseText == "falloImpuestos"){
						mostrarMensajes("error", "Disculpe Hay impuestos sin Disponibilidad o Rechazados por favor verifique");
						consultarMateriales(id_requisicion);
						actualizarListaDeTotales(id_requisicion);
						actualizarTotalesPartidas(id_requisicion);
						mostrarPartidas(id_requisicion, document.getElementById("id_categoria_programatica").value);
					}else if(ajax.responseText == "fallo"){
						mostrarMensajes("error", "Disculpe no se pudo procesar la Requisicion");
					}else if(ajax.responseText == "falloMateriales"){
						mostrarMensajes("error", "Disculpe hay materiales sin disponibilidad o rechazados por favor verifique");
						consultarMateriales(id_requisicion);
						actualizarListaDeTotales(id_requisicion);
						actualizarTotalesPartidas(id_requisicion);
						mostrarPartidas(id_requisicion, document.getElementById("id_categoria_programatica").value);
					}else if(ajax.responseText == "duplicados"){
						mostrarMensajes("error", "Disculpe hay materiales Duplicados");
						consultarMateriales(id_requisicion);
						actualizarListaDeTotales(id_requisicion);
						actualizarTotalesPartidas(id_requisicion);
						mostrarPartidas(id_requisicion, document.getElementById("id_categoria_programatica").value);
					}
					
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&anio="+document.getElementById('anio').value+"&idfuente_financiamiento="+document.getElementById('fuente_financiamiento').value+"&idtipo_presupuesto="+document.getElementById('tipo_presupuesto').value+"&idordinal="+document.getElementById('id_ordinal').value+"&contribuyente_ordinario="+document.getElementById('contribuyente_ordinario').value+"&ejecutar=procesarOrden");
	}
}


/*********************************************************************************************************************************************************
**************************************************************** ANULAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function anularOrden(id_requisicion, clave){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Anular la Requisicion?")){	
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){	
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Requisicion Anulada con Exito");
						actualizarDatosBasicos("consultar");
						consultarMateriales(id_requisicion);
					}else if(ajax.responseText == "claveIncorrecta"){
						mostrarMensajes("error", "Disculpe Clave incorrecta");
						setTimeout("window.location.href='principal.php?accion=249&modulo=3'",5000);
					}else{
						mostrarMensajes("error", "No se pudo Requisicion");
					}
						document.getElementById("verificarClave").value = "";
						document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&clave="+clave+"&ejecutar=anularOrden");
	}
}



/*********************************************************************************************************************************************************
**************************************************************** DUPLICAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function duplicarOrden(id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Duplicar la Requisicion?")){	
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){	
				consultarOrdenCompra(ajax.responseText, document.getElementById('id_categoria_programatica').value);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&ejecutar=duplicarOrden");
	}
}



/*********************************************************************************************************************************************************
**************************************************************** DUPLICAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/

function consultarOrdenCompra(id_requisicion, id_categoria_programatica){
	var resultado = consultarTipoCarga(id_requisicion);
	actualizarDatosBasicos("consultar", id_requisicion);
	document.getElementById('tablaProveedor').style.display='block';
	document.getElementById('tablaMaterialesPartidas').style.display='block';
	document.getElementById('tablaPartidas').style.display='block';
	
	consultarMateriales(id_requisicion);
	actualizarListaDeTotales(id_requisicion);
	actualizarTotalesPartidas(id_requisicion);
	actualizarBotones(id_requisicion);
	mostrarPartidas(id_requisicion, id_categoria_programatica);
	
	//listarSolicitudesSeleccionadas(id_requisicion);
	//setTimeout("consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, "+id_requisicion+")", 3000);
	setTimeout("validarTipoListaMostrar("+resultado+","+id_requisicion+")", 1000);
	setTimeout("esconderListaSolicitudes()", 1000);
}


function validarTipoListaMostrar(resultado, id_requisicion){
	if(document.getElementById('tipo_carga_orden').value == "cotizacion"){
		listarSolicitudesSeleccionadas(id_requisicion);
		setTimeout("consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, "+id_requisicion+")", 500);
	}else{
	}
}


function consultarTipoCarga(id_requisicion){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('tipo_carga_orden').value = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
	}
	
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&ejecutar=consultarTipoCarga");
}


function esconderListaSolicitudes(){
	if(document.getElementById("solicitudes").value == "noTiene"){
		document.getElementById("listaSolicitudesProveedor").style.display = "none";		
	}	
}





function actualizarTotalesPartidas(id_requisicion){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('totalPartidas').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&ejecutar=actualizarTotalesPartidas");
	}
	
	
	
function ingresarMaterialIndividual(id_requisicion, id_material, cantidad, precio_unitario, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(id_material == ""){
		mostrarMensajes("error", "No hay Materiales Seleccionados para ser ingresados");
	}else if(cantidad == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la cantidad");
		document.getElementById('cantidad').focus();
	}else if(precio_unitario == ""){
		mostrarMensajes("error", "Disculpe debe asignarle un pecio unitario al producto");
		document.getElementById('precio_unitario').focus();
	}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
						//alert(ajax.responseText);
						if(ajax.responseText.indexOf("exito") != -1){
							consultarMateriales(id_requisicion);
							actualizarListaDeTotales(id_requisicion);
							actualizarTotalesPartidas(id_requisicion);
							actualizarBotones(id_requisicion);
							mostrarPartidas(id_requisicion, id_categoria_programatica);
						}else if(ajax.responseText == "fallo"){
							mostrarMensajes("error", "Disculpe no se pudo ingresar el Articulo");
						}else if(ajax.responseText == "existe"){
							mostrarMensajes("error", "Disculpe el material que intenta ingresar ya esta ingresado en la lista");
						}
						document.getElementById('codigo_material').value = "";
						document.getElementById('id_material').value = "";
						document.getElementById('descripcion_material').value = "";
						document.getElementById('unidad_medida').value = "";
						document.getElementById('cantidad').value = "";
						document.getElementById('precio_unitario').value = "";
						document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&id_material="+id_material+"&cantidad="+cantidad+"&precio_unitario="+precio_unitario+"&id_categoria_programatica="+id_categoria_programatica+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=ingresarMaterialIndividual"+"&contribuyente_ordinario="+contribuyente_ordinario);
	}
	return false;
}



function actualizarTipoCargaOrden(id_requisicion, tipo_carga_orden){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/requisicion/requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			
			document.getElementById('tipo_carga_orden').value = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
	}
	
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_requisicion="+id_requisicion+"&tipo_carga_orden="+tipo_carga_orden+"&ejecutar=actualizarTipoCargaOrden");	
	}
