// JavaScript Document
/**************************************************************************************************************************************************
********************************************** FUNCION PARA ABRIR Y CERRAR LAS OBSERVACIONES ****************************************************
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




/************************************************************************************************************************************************************************************************** FUNCION PARA ABRIR Y CERRAR LOS DATOS EXTRA ****************************************************
**********************************************************************************************************************************************************/


function abrirCerrarDatosExtra(){
	if(document.getElementById('datosExtra').style.display=="block"){
			document.getElementById('datosExtra').style.display="none";
			document.getElementById('textoContraerDatosExtra').innerHTML = "<img src='imagenes/comments.png' title = 'Cambiar Datos Extras'>";
	}else{
			document.getElementById('datosExtra').style.display="block";
			document.getElementById('textoContraerDatosExtra').innerHTML = "<img src='imagenes/arrow_up.png' title='Cerrar Datos Extras'>";
	}
}

/************************************************************************************************************************************************************************************************ FUNCION PARA ABRIR Y CERRAR LAS LISTAD DE PARTIDAS *************************************************
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


/************************************************************************************************************************************************************************************************ FUNCION PARA ABRIR Y CERRAR LAS LISTAS DE MATERIALES ********************************************
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


/*************************************************************************************************************************************************
********************************************* FUCION PARA ABRIR Y CERRAR LOS PROVEEDORES **********************************************************
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


/******************************************************************************************************************************************************************************************* FUNCION PARA CONSULTAR LOS PEDIDOS DE LOS PROVEEDORES **************************************************
**********************************************************************************************************************************************************/


function consultarPedidosProveedores(id_beneficiarios, tipo, id_orden_compra){
		var cofinanciamiento = document.getElementById('cofinanciamiento').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
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
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_beneficiarios="+id_beneficiarios+"&id_orden_compra="+id_orden_compra+"&tipo="+tipo+"&ejecutar=consultarSolicitudesProveedor");
}








function consultarRequisicion(id_beneficiarios, tipo, id_orden_compra){
		var cofinanciamiento = document.getElementById('cofinanciamiento').value;
		var id_categoria_programatica = document.getElementById('id_categoria_programatica').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
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
					document.getElementById("listaSolicitudesProveedor").innerHTML="Este Proveedor no tiene niguna Requisicion Realizada";
					document.getElementById("solicitudes").value="noTiene";
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_categoria_programatica="+id_categoria_programatica+"&id_beneficiarios="+id_beneficiarios+"&id_orden_compra="+id_orden_compra+"&tipo="+tipo+"&ejecutar=consultarRequisicion");
}










/**************************************************************************************************************************************************************************************************** REFRESCAR LA LISTA DE SOLICITUDES DE COTIZACION POR PROVEEDOR **************************************
**********************************************************************************************************************************************************/


function refrescarListaSolicitudes(id_beneficiarios, tipo, id_orden_compra){
	if(document.getElementById('listaSolicitudesProveedor').style.display == 'block'){
			consultarPedidosProveedores(id_beneficiarios, tipo, id_orden_compra);
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



function agregarMateriales(id_solicitud, id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal,contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("divMateriales").innerHTML=ajax.responseText;
				listarSolicitudesSeleccionadas(id_orden_compra);
				actualizarListaDeTotales(id_orden_compra);
				actualizarTotalesPartidas(id_orden_compra);
				mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_solicitud="+id_solicitud+"&id_orden_compra="+id_orden_compra+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=materiales&accion=ingresarSolicitudCreada"+"&contribuyente_ordinario="+contribuyente_ordinario);
}












function agregarRequisiciones(idrequisicion, id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal,contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("divMateriales").innerHTML=ajax.responseText;
				listarRequisicionesSeleccionadas(id_orden_compra);
				actualizarListaDeTotales(id_orden_compra);
				actualizarTotalesPartidas(id_orden_compra);
				mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&idrequisicion="+idrequisicion+"&id_orden_compra="+id_orden_compra+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=materiales&accion=agregarRequisiciones"+"&contribuyente_ordinario="+contribuyente_ordinario);
}






/*********************************************************************************************************************************************************
********************************************************* INGRESAR DATOS BASICOS *************************************************************************
**********************************************************************************************************************************************************/



function ingresarDatosBasicos(tipo_orden, categoria_programatica, justificacion, observaciones, ordenado_por, cedula_ordenado, numero_requisicion, fecha_requisicion, id_beneficiarios, nro_factura, fecha_factura, nro_control, anio, fuente_financiamiento, tipo_presupuesto, id_ordinal, tipo_carga_orden){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(justificacion == "" || id_beneficiarios == "" || categoria_programatica == ""){
		mostrarMensajes("error", "Debe llenar todos los datos para poder pasar al siguiente paso de la orden");
	}else{
		var ajax=nuevoAjax();
			ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
				if (ajax.readyState==4){
					if(ajax.responseText != "fallo"){
						document.getElementById('botonSiguiente').style.display = "none";
						document.getElementById("id_orden_compra").value=ajax.responseText;
						document.getElementById('tablaProveedor').style.display='block';
						document.getElementById('tablaMaterialesPartidas').style.display='block';
						actualizarDatosBasicos("consultar");
						document.getElementById("divCargando").style.display = "none";
					}else{
						mostrarMensajes("error", "Los datos Basicos de la Orden no puedieron ingresarse con exito");
						}
				} 
			}
			ajax.send("cofinanciamiento="+cofinanciamiento+"&tipo_orden="+tipo_orden+"&categoria_programatica="+categoria_programatica+"&justificacion="+justificacion+"&observaciones="+observaciones+"&ordenado_por="+ordenado_por+"&cedula_ordenado="+cedula_ordenado+"&numero_requisicion="+numero_requisicion+"&fecha_requisicion="+fecha_requisicion+"&id_beneficiarios="+id_beneficiarios+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&nro_control="+nro_control+"&anio="+anio+"&fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&id_ordinal="+id_ordinal+"&tipo_carga_orden="+tipo_carga_orden+"&ejecutar=agregarDatosBasicos");
	}
}


/*********************************************************************************************************************************************************
************************************************** LISTA DE SOLICITUDES DE COTIZACION SELECCIONADAS ******************************************************
**********************************************************************************************************************************************************/



function listarSolicitudesSeleccionadas(id_orden_compra){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
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
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=listarSolicitudesSeleccionadas");
}








function listarRequisicionesSeleccionadas(id_orden_compra){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
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
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=listarRequisicionesSeleccionadas");
}


/*********************************************************************************************************************************************************
**************************************************** ACTUALIZAR EL PRECIO Y LA CANTIDAD DE LOS MATERIALES ************************************************
**********************************************************************************************************************************************************/




function actualizarPrecioCantidad(id_orden_compra,precio, cantidad,id_articulo, id_articulo_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(cantidad != ""){
		if(precio != ""){
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function(){ 
				if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}				
				if (ajax.readyState==4){
					alert(ajax.responseText);
					if(ajax.responseText == "exito"){
						consultarMateriales(id_orden_compra);	
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
						
					}else{
						
					}
					document.getElementById("divCargando").style.display = "none";
				}
			} 
			ajax.send("cofinanciamiento="+cofinanciamiento+"&id_articulo_compra="+id_articulo_compra+"&id_orden_compra="+id_orden_compra+"&id_articulo="+id_articulo+"&precio="+precio+"&cantidad="+cantidad+"&anio="+anio+"&idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto+"&idordinal="+idordinal+"&contribuyente_ordinario="+contribuyente_ordinario+"&ejecutar=actualizarPrecioCantidad");
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



function consultarMateriales(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
		var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if (ajax.readyState==4)
			{
					document.getElementById("divMateriales").innerHTML=ajax.responseText;
			} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=materiales&accion=consultar");	
}



/*********************************************************************************************************************************************************
******************************************************* ACTUALIZA LOS DATOS BASICOS DE UNA ORDEN DE COMPRA ***********************************************
**********************************************************************************************************************************************************/



function actualizarDatosBasicos(accion, id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	if(id_orden_compra){
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById("tablaDatosBasicos").innerHTML = ajax.responseText;
				actualizarBotones(document.getElementById('id_orden_compra').value);
				consultarMateriales(document.getElementById('id_orden_compra').value);
				actualizarListaDeTotales(document.getElementById('id_orden_compra').value);
				actualizarTotalesPartidas(document.getElementById('id_orden_compra').value);
				setTimeout("mostrarPartidas(document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value)",600);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
	ajax.send("id_orden_compra="+id_orden_compra+"&accion="+accion+"&ejecutar=actualizarDatosBasicos");
	}else{
		ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById("tablaDatosBasicos").innerHTML = ajax.responseText;
				actualizarBotones(document.getElementById('id_orden_compra').value);
				consultarMateriales(document.getElementById('id_orden_compra').value);
				actualizarListaDeTotales(document.getElementById('id_orden_compra').value);
				actualizarTotalesPartidas(document.getElementById('id_orden_compra').value);
				setTimeout("mostrarPartidas(document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value)",600);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&tipo_orden="+document.getElementById('tipo_orden').value+"&id_orden_compra="+document.getElementById('id_orden_compra').value+"&id_categoria_programatica="+document.getElementById('id_categoria_programatica').value+"&justificacion="+document.getElementById('justificacion').value+"&observaciones="+document.getElementById('observaciones').value+"&ordenado_por="+document.getElementById('ordenado_por').value+"&cedula_ordenado="+document.getElementById('cedula_ordenado').value+"&fecha_requisicion="+document.getElementById('fecha_requisicion').value+"&numero_requisicion="+document.getElementById('numero_requisicion').value+"&id_beneficiarios="+document.getElementById('id_beneficiarios').value+"&nro_factura="+document.getElementById('nro_factura').value+"&fecha_factura="+document.getElementById('fecha_factura').value+"&nro_control="+document.getElementById('nro_control').value+"&anio="+document.getElementById('anio').value+"&fuente_financiamiento="+document.getElementById('fuente_financiamiento').value+"&tipo_presupuesto="+document.getElementById('tipo_presupuesto').value+"&id_ordinal="+document.getElementById('id_ordinal').value+"&accion="+accion+"&tipo_carga_orden="+document.getElementById('tipo_carga_orden').value+"&ejecutar=actualizarDatosBasicos");
	}
			
}


/*********************************************************************************************************************************************************
******************************************************** ELIMINAR MATERIALES DE UNA ORDEN DE COMPRA ******************************************************
**********************************************************************************************************************************************************/



function eliminarMateriales(id_orden_compra, id_material, id_solicitud, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divMateriales").innerHTML=ajax.responseText;
					listarSolicitudesSeleccionadas(id_orden_compra);
					mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
					actualizarListaDeTotales(id_orden_compra);
					actualizarTotalesPartidas(id_orden_compra);
					if(document.getElementById("eliminoSolicitud").value != ""){
						deseleccionarListaSolicitud(document.getElementById("eliminoSolicitud").value);
					}
					document.getElementById("divCargando").style.display = "none";
					
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&id_material="+id_material+"&id_solicitud="+id_solicitud+"&id_categoria_programatica="+id_categoria_programatica+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&contribuyente_ordinario="+contribuyente_ordinario+"&ejecutar=materiales&accion=eliminar");
}


/*********************************************************************************************************************************************************
**************************************************** ACTUALIZAR LAS LISTAS DE TOTALES DE LOS MATERIALES **************************************************
**********************************************************************************************************************************************************/



function actualizarListaDeTotales(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
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
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=actualizarListaDeTotales");
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



function mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
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
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&id_categoria_programatica="+id_categoria_programatica+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=mostrarPartidas");
}


/*********************************************************************************************************************************************************
*************************************** ACTUALIZA LOS BOTONES DE PROCESAR; DUPLICAR; ANULAR; EN ELABORACION **********************************************
**********************************************************************************************************************************************************/



function actualizarBotones(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
		var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('vistaDeBotones').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=actualizarBotones");
}



/*********************************************************************************************************************************************************
**************************************************************** PROCESA LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function procesarOrden(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Procesar esta orden de compra?")){
		var ajax=nuevoAjax();

		ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					alert(ajax.responseText);
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "La orden se Proceso con Exito");
					}else if(ajax.responseText == "sinMateriales"){
						mostrarMensajes("error", "Disculpe debe seleccionar materiales para poder procesar la orden de compra");
					}else if (ajax.responseText == "falloImpuestos"){
						mostrarMensajes("error", "Disculpe Hay impuestos sin Disponibilidad o Rechazados por favor verifique");
						consultarMateriales(id_orden_compra);
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, document.getElementById("id_categoria_programatica").value);
					}else if(ajax.responseText == "fallo"){
						mostrarMensajes("error", "Disculpe no se pudo procesar la Orden de Compra");
					}else if(ajax.responseText == "falloMateriales"){
						mostrarMensajes("error", "Disculpe hay materiales sin disponibilidad o rechazados por favor verifique");
						consultarMateriales(id_orden_compra);
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, document.getElementById("id_categoria_programatica").value);
					}else if(ajax.responseText == "duplicados"){
						mostrarMensajes("error", "Disculpe hay materiales Duplicados");
						consultarMateriales(id_orden_compra);
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, document.getElementById("id_categoria_programatica").value);
					}
					consultarOrdenCompra(id_orden_compra);
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&anio="+document.getElementById('anio').value+"&idfuente_financiamiento="+document.getElementById('fuente_financiamiento').value+"&idtipo_presupuesto="+document.getElementById('tipo_presupuesto').value+"&idordinal="+document.getElementById('id_ordinal').value+"&contribuyente_ordinario="+document.getElementById('contribuyente_ordinario').value+"&ejecutar=procesarOrden");
	}
}


/*********************************************************************************************************************************************************
**************************************************************** ANULAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function anularOrden(id_orden_compra, clave){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Anular la Orden?")){	
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){	
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "La orden se Anulo con Exito");
						actualizarDatosBasicos("consultar");
						consultarMateriales(id_orden_compra);
					}else if(ajax.responseText == "claveIncorrecta"){
						mostrarMensajes("error", "Disculpe Clave incorrecta");
						setTimeout("window.location.href='principal.php?accion=249&modulo=3'",1000);
					}else{
						mostrarMensajes("error", "No se pudo anular la orden");
					}
						document.getElementById("verificarClave").value = "";
						document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&clave="+clave+"&ejecutar=anularOrden");
	}
}



/*********************************************************************************************************************************************************
**************************************************************** DUPLICAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function duplicarOrden(id_orden_compra){

	var cofinanciamiento = document.getElementById('cofinanciamiento').value;

		var ajax=nuevoAjax();
		ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){	
				document.getElementById('proceso').disabled = false;
				consultarOrdenCompra(ajax.responseText, document.getElementById('id_categoria_programatica').value);
				document.getElementById('listaSolicitudesProveedor').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
				setTimeout("actualizarDatosBasicos('actualizar')",1000);
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=duplicarOrden");

}





function consultarTipoCarga(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);
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
	
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=consultarTipoCarga");
}









/*********************************************************************************************************************************************************
**************************************************************** DUPLICAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/

function consultarOrdenCompra(id_orden_compra, id_categoria_programatica, tipo){
	document.getElementById('id_orden_compra').value = id_orden_compra;
	var resultado = consultarTipoCarga(id_orden_compra);
	setTimeout("actualizarDatosBasicos('consultar', "+id_orden_compra+")",500);
	document.getElementById('tablaProveedor').style.display='block';
	document.getElementById('tablaMaterialesPartidas').style.display='block';
	consultarMateriales(id_orden_compra);
	actualizarListaDeTotales(id_orden_compra);
	actualizarTotalesPartidas(id_orden_compra);
	actualizarBotones(id_orden_compra);
	mostrarPartidas(id_orden_compra, id_categoria_programatica);
	setTimeout("validarTipoListaMostrar("+resultado+","+id_orden_compra+")", 1000);
	setTimeout("esconderListaSolicitudes()", 1500);
	
	if(tipo == 'consulta'){
		setTimeout("duplicarOrden("+id_orden_compra+")",100);
	}
	
	
	
}



function validarTipoListaMostrar(resultado, id_orden_compra){
	if(document.getElementById('tipo_carga_orden').value == "cotizacion"){
		listarSolicitudesSeleccionadas(id_orden_compra);
		setTimeout("consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, "+id_orden_compra+")", 500);
	}else if(document.getElementById('tipo_carga_orden').value == "requisicion"){
		
		listarRequisicionesSeleccionadas(id_orden_compra);
		setTimeout("consultarRequisicion(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, "+id_orden_compra+")", 500);
	}else{
	}
}



function actualizarTipoCargaOrden(id_orden_compra, tipo_carga_orden){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
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
	
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&tipo_carga_orden="+tipo_carga_orden+"&ejecutar=actualizarTipoCargaOrden");	
	}





function esconderListaSolicitudes(){
	if(document.getElementById("solicitudes").value == "noTiene"){
		document.getElementById("listaSolicitudesProveedor").style.display = "none";		
	}	
}





function actualizarTotalesPartidas(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
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
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=actualizarTotalesPartidas");
	}
	
	
	
function ingresarMaterialIndividual(id_orden_compra, id_material, cantidad, precio_unitario, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	
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
			ajax.open("POST", "lib/reversa_compromiso/lib/reversa_compromiso_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					alert(ajax.responseText);
						if(ajax.responseText.indexOf("exito") != -1){
							consultarMateriales(id_orden_compra);
							actualizarListaDeTotales(id_orden_compra);
							actualizarTotalesPartidas(id_orden_compra);
							actualizarBotones(id_orden_compra);
							mostrarPartidas(id_orden_compra, id_categoria_programatica);
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
			ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&id_material="+id_material+"&cantidad="+cantidad+"&precio_unitario="+precio_unitario+"&id_categoria_programatica="+id_categoria_programatica+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=ingresarMaterialIndividual"+"&contribuyente_ordinario="+contribuyente_ordinario);
	}
	return false;
}












