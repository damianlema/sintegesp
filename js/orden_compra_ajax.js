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
			document.getElementById('textoContraerDatosExtra').innerHTML = "Origen Presupuestario";
	}else{
			document.getElementById('datosExtra').style.display="block";
			document.getElementById('textoContraerDatosExtra').innerHTML = "Ocultar";
	}
}


/************************************************************************************************************************************************************************************************** FUNCION PARA ABRIR Y CERRAR LOS DATOS DE FACTURA ****************************************************
**********************************************************************************************************************************************************/


function abrirCerrarDatosFactura(){
	if(document.getElementById('datosFactura').style.display=="block"){
			document.getElementById('datosFactura').style.display="none";
			document.getElementById('textoContraerDatosFactura').innerHTML = "Datos de Facturacion";
	}else{
			document.getElementById('datosFactura').style.display="block";
			document.getElementById('textoContraerDatosFactura').innerHTML = "Ocultar Datos de Facturacion";
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


/************************************************************************************************************************************************************************************************ FUNCION PARA ABRIR Y CERRAR LAS LISTAD DE CUENTAS CONTABLES *************************************************
**********************************************************************************************************************************************************/


function abrirCerrarContable(){
	if(document.getElementById('divContable').style.display=="block"){
			document.getElementById('divContable').style.display="none";
			//document.getElementById('formularioPartidas').style.display="none";
			document.getElementById('textoContraerContable').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			document.getElementById('divContable').style.display="block";
			//document.getElementById('formularioPartidas').style.display="block";
			document.getElementById('textoContraerContable').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
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

/************************************************************************************************************************************************************************************************ FUNCION PARA ABRIR Y CERRAR LAS LISTAS DE SNC ********************************************
**********************************************************************************************************************************************************/


function abrirCerrarSNC(){
	if(document.getElementById('divsnc').style.display=="block"){
			document.getElementById('divsnc').style.display="none";
			//document.getElementById('formularioMateriales').style.display="none";
			document.getElementById('textoContraerSNC').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
	}else{
			//document.getElementById('formularioMateriales').style.display="block";
			document.getElementById('divsnc').style.display="block";
			document.getElementById('textoContraerSNC').innerHTML = "<img src='imagenes/cerrar.gif' title = 'Cerrar'>";
	}
}


/*************************************************************************************************************************************************
********************************************* FUCION PARA ABRIR Y CERRAR LOS PROVEEDORES **********************************************************
**********************************************************************************************************************************************************/


function abrirCerrarProveedores(){
	if(document.getElementById('formularioProveedores').style.display=="block"){
			//document.getElementById('tablaProveedor').width='950';
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
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
			document.getElementById('sproceso').disabled=true;
			document.getElementById('buscarProveedor').style.display='none';
			document.getElementById('buscarCategoriaProgramatica').style.display='none';
			document.getElementById('anio').disabled = true;
			document.getElementById('fuente_financiamiento').disabled = true;
			document.getElementById('tipo_presupuesto').disabled = true;
			//document.getElementById('buscarOrdinal').style.display='none';
		}else{
			document.getElementById('tipo_orden').disabled=false;
			document.getElementById('buscarProveedor').style.display='block';
			document.getElementById('sproceso').disabled=false;
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
			document.getElementById('sproceso').disabled=true;
			document.getElementById('buscarProveedor').style.display='none';
			document.getElementById('buscarCategoriaProgramatica').style.display='none';
			document.getElementById('anio').disabled = true;
			document.getElementById('fuente_financiamiento').disabled = true;
			document.getElementById('tipo_presupuesto').disabled = true;
			//document.getElementById('buscarOrdinal').style.display='none';
		}else{
			document.getElementById('tipo_orden').disabled=false;
			document.getElementById('buscarProveedor').style.display='block';
			document.getElementById('sproceso').disabled=false;
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
				mostrarCuentasContables(id_orden_compra);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_solicitud="+id_solicitud+"&id_orden_compra="+id_orden_compra+"&anio="+anio+"&fuente_financiamiento="+idfuente_financiamiento+"&tipo_presupuesto="+idtipo_presupuesto+"&id_ordinal="+idordinal+"&ejecutar=materiales&accion=ingresarSolicitudCreada"+"&contribuyente_ordinario="+contribuyente_ordinario);
}





/*********************************************************************************************************************************************************
********************************************************* ACTUALIZAR SNC *****************************************************************************
**********************************************************************************************************************************************************/



function procesarSNC(){
	var modo_comunicacion = document.getElementById('modo_comunicacion').value;
	var tipo_actividad = document.getElementById('tipo_actividad').value;
	var tipo_procedimiento = document.getElementById('tipo_procedimiento').value;
	var fecha_inicio = document.getElementById('fecha_inicio').value;
	var fecha_cierre = document.getElementById('fecha_cierre').value;
	if(document.getElementById('4998').checked == true){
		var decreto_4998 = 1;
	}else{
		var decreto_4998 = 0;
	}
	if(document.getElementById('4910').checked == true){
		var decreto_4910 = 1;
	}else{
		var decreto_4910 = 0;
	}
	//alert(decreto_4998);
	var id_orden_compra = document.getElementById('id_orden_compra').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('divsnc').style.display="none";
			//document.getElementById('formularioMateriales').style.display="none";
			document.getElementById('textoContraerSNC').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&modo_comunicacion="+modo_comunicacion+"&tipo_actividad="+tipo_actividad+"&tipo_procedimiento="+tipo_procedimiento+"&fecha_inicio="+fecha_inicio+"&fecha_cierre="+fecha_cierre+"&decreto_4998="+decreto_4998+"&decreto_4910="+decreto_4910+"&ejecutar=procesarSNC");
}






function agregarRequisiciones(idrequisicion, id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal,contribuyente_ordinario){
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert (ajax.responseText);
				document.getElementById("divMateriales").innerHTML=ajax.responseText;
				listarRequisicionesSeleccionadas(id_orden_compra);
				actualizarListaDeTotales(id_orden_compra);
				actualizarTotalesPartidas(id_orden_compra);
				mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
				mostrarCuentasContables(id_orden_compra);
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
			ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
				if (ajax.readyState==4){
					//alert(ajax.responseText);
					if(ajax.responseText != "fallo"){
						//document.getElementById('id_orden_compra').value = ajax.responseText;
						document.getElementById('botonSiguiente').style.display = "none";
						document.getElementById('divTablaProveedores').style.display='block';
						document.getElementById('proceso').style.display='block';
						document.getElementById('solicitudesSeleccionada').style.display='block';
						document.getElementById('tablaMaterialesPartidas').style.display='block';
						document.getElementById('tablaSNC').style.display='block';
						document.getElementById('formularioMateriales').style.display='block';
						document.getElementById('tablaPartidas').style.display='block';
						document.getElementById('tablaContabilidad').style.display='block';
						document.getElementById('celdaImprimir').style.display='block';
						//document.getElementById('celdaRecalcular').style.display='block';
						actualizarDatosBasicos('consultar',ajax.responseText);
						document.getElementById("divCargando").style.display = "none";
						document.getElementById('idestado').value='elaboracion';
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
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
*********************************************** FUNCION PARA RECALCULAR EL COMPROMISO *******************************************************************
**********************************************************************************************************************************************************/

function recalcular(){
	var id_orden_compra = document.getElementById('id_orden_compra').value;
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			//alert("Recalculado con exito, por favor consulte nuevamente la orden");
			consultarOrdenCompra(id_orden_compra);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&anio="+document.getElementById('anio').value+"&idfuente_financiamiento="+document.getElementById('fuente_financiamiento').value+"&idtipo_presupuesto="+document.getElementById('tipo_presupuesto').value+"&idordinal="+document.getElementById('id_ordinal').value+"&contribuyente_ordinario="+document.getElementById('contribuyente_ordinario').value+"&ejecutar=recalcular");
}












/*********************************************************************************************************************************************************
**************************************************** ACTUALIZAR EL PRECIO Y LA CANTIDAD DE LOS MATERIALES ************************************************
**********************************************************************************************************************************************************/




function actualizarPrecioCantidad(id_orden_compra,precio, cantidad,id_articulo, id_articulo_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, contribuyente_ordinario){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(cantidad != ""){
		if(precio != ""){
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function(){ 
				if(ajax.readyState == 1){
						document.getElementById("divCargando").style.display = "block";
						}				
				if (ajax.readyState==4){
					//alert(ajax.responseText);
					if(ajax.responseText == "exito"){
						consultarMateriales(id_orden_compra);	
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, id_categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal);
						mostrarCuentasContables(id_orden_compra);
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					//alert(ajax.responseText);
					//document.getElementById("tablaDatosBasicos").innerHTML = ajax.responseText;
						document.getElementById("id_orden_compra").value 			= id_orden_compra;
					
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
						
						if (partes[3] == 'anulado'){
							document.getElementById("celdaEstado").innerHTML 		= partes[4]+partes[5];
						}else{
							document.getElementById("celdaEstado").innerHTML 		= partes[4];
						}
						
						document.getElementById("tipo_orden").value					= partes[6];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_orden").disabled 			= true;
						}else{
							document.getElementById("tipo_orden").disabled 			= false;
						}
						
						document.getElementById("id_categoria_programatica").value 	= partes[7];
						document.getElementById("nombre_categoria").value 			= partes[8];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarCategoriaProgramatica").style.display = 'none'
						}else{
							document.getElementById("buscarCategoriaProgramatica").style.display = 'block'
						}
						
						document.getElementById("fuente_financiamiento").value 		= partes[9];
						if (partes[3] != 'elaboracion'){
							document.getElementById("fuente_financiamiento").disabled 	= true;
						}else{
							document.getElementById("fuente_financiamiento").disabled 	= false;
						}
						document.getElementById("cofinanciamiento").value 			= partes[10];
						document.getElementById("tipo_presupuesto").value 			= partes[11];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_presupuesto").disabled 	= true;
						}else{
							document.getElementById("tipo_presupuesto").disabled 	= false;
						}
						document.getElementById("anio").value 						= partes[12];
						if (partes[3] != 'elaboracion'){
							document.getElementById("anio").disabled 				= true;
						}else{
							document.getElementById("anio").disabled 				= false;
						}
						document.getElementById("justificacion").value 				= partes[13];
						if (partes[3] != 'elaboracion'){
							document.getElementById("justificacion").disabled 		= true;
						}else{
							document.getElementById("justificacion").disabled 		= false;
						}
						document.getElementById("observaciones").value 				= partes[14];
						if (partes[3] != 'elaboracion'){
							document.getElementById("observaciones").disabled 		= true;
						}else{
							document.getElementById("observaciones").disabled 		= false;
						}
						document.getElementById("ordenado_por").value 				= partes[15];
						document.getElementById("cedula_ordenado").value 			= partes[16];
						if (partes[3] != 'elaboracion'){
							document.getElementById("ordenado_por").disabled 		= true;
							document.getElementById("cedula_ordenado").disabled 	= true;
						}else{
							document.getElementById("ordenado_por").disabled 		= false;
							document.getElementById("cedula_ordenado").disabled 	= false;
						}
						document.getElementById("numero_requisicion").value 		= partes[17];
						if (partes[18] != '0000-00-00'){
							document.getElementById("fecha_requisicion").value 			= partes[18];
						}
						if (partes[3] != 'elaboracion'){
							document.getElementById("numero_requisicion").disabled 	= true;
							document.getElementById("fecha_requisicion").disabled 	= true;
						}else{
							document.getElementById("numero_requisicion").disabled 	= false;
							document.getElementById("fecha_requisicion").disabled 	= false;
						}
						
						document.getElementById("id_beneficiarios").value 			= partes[19];
						document.getElementById("nombre_proveedor").value 			= partes[20];
						document.getElementById("contribuyente_ordinario").value	= partes[21];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarProveedor").style.display = 'none';
						}else{
							document.getElementById("buscarProveedor").style.display = 'block'
						}
						document.getElementById("nro_factura").value 				= partes[22];
						document.getElementById("nro_control").value 				= partes[23];
						document.getElementById("fecha_factura").value				= partes[24];
						
						document.getElementById("modo_comunicacion").value			= partes[25];
						document.getElementById("tipo_actividad").value				= partes[26];
						document.getElementById("tipo_procedimiento").value			= partes[27];
						document.getElementById("fecha_inicio").value				= partes[28];
						document.getElementById("fecha_cierre").value				= partes[29];
						
						if (partes[30]==1){
							document.getElementById("4998").checked				= true;
						}else{
							document.getElementById("4998").checked				= false;
						}
						
						if (partes[31]==1){
							document.getElementById("4910").checked				= true;
						}else{
							document.getElementById("4910").checked				= false;
						}
						
						if (partes[3] != 'elaboracion'){
							document.getElementById("nro_factura").disabled 		= true;
							document.getElementById("nro_control").disabled 		= true;
							document.getElementById("fecha_factura").disabled 		= true;
						}else{
							document.getElementById("nro_factura").disabled 		= false;
							document.getElementById("nro_control").disabled 		= false;
							document.getElementById("fecha_factura").disabled 		= false;
						}
					
					actualizarBotones(document.getElementById('id_orden_compra').value, document.getElementById("idestado").value);
					consultarMateriales(document.getElementById('id_orden_compra').value);
					actualizarListaDeTotales(document.getElementById('id_orden_compra').value);
					actualizarTotalesPartidas(id_orden_compra);
					setTimeout("mostrarPartidas(document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value)",600);
					mostrarCuentasContables(document.getElementById('id_orden_compra').value);
					document.getElementById('celdaImprimir').style.display='block';
					if (partes[3] == 'elaboracion'){
						//document.getElementById('celdaRecalcular').style.display='block';
					}
					document.getElementById("divCargando").style.display = "none";
				} 
			}
		ajax.send("id_orden_compra="+id_orden_compra+"&accion="+accion+"&ejecutar=actualizarDatosBasicos");
	}else{
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				//document.getElementById("tablaDatosBasicos").innerHTML = ajax.responseText;
						document.getElementById("id_orden_compra").value 			= id_orden_compra;
					
						partes = ajax.responseText.split("|.|");
						//alert(partes[15]);
						if (partes[0] != ''){
							document.getElementById("celdaNroOrden").innerHTML		= '<STRONG>'+partes[0]+'</STRONG>';
						}
						if (partes[1] != '0000-00-00'){
							document.getElementById("fecha_orden").value 			= partes[1];
						}
						document.getElementById("celdaFechaElaboracion").innerHTML 	= '<STRONG>'+partes[2]+'</STRONG>';
						document.getElementById("idestado").value 					= partes[3];
						
						if (partes[3] == 'anulado'){
							document.getElementById("celdaEstado").innerHTML 		= partes[4]+partes[5];
						}else{
							document.getElementById("celdaEstado").innerHTML 		= partes[4];
						}
						
						document.getElementById("tipo_orden").value					= partes[6];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_orden").disabled 			= true;
						}else{
							document.getElementById("tipo_orden").disabled 			= false;
						}
						
						document.getElementById("id_categoria_programatica").value 	= partes[7];
						document.getElementById("nombre_categoria").value 			= partes[8];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarCategoriaProgramatica").style.display = 'none'
						}else{
							document.getElementById("buscarCategoriaProgramatica").style.display = 'block'
						}
						
						document.getElementById("fuente_financiamiento").value 		= partes[9];
						if (partes[3] != 'elaboracion'){
							document.getElementById("fuente_financiamiento").disabled 	= true;
						}else{
							document.getElementById("fuente_financiamiento").disabled 	= false;
						}
						document.getElementById("cofinanciamiento").value 			= partes[10];
						document.getElementById("tipo_presupuesto").value 			= partes[11];
						if (partes[3] != 'elaboracion'){
							document.getElementById("tipo_presupuesto").disabled 	= true;
						}else{
							document.getElementById("tipo_presupuesto").disabled 	= false;
						}
						document.getElementById("anio").value 						= partes[12];
						if (partes[3] != 'elaboracion'){
							document.getElementById("anio").disabled 				= true;
						}else{
							document.getElementById("anio").disabled 				= false;
						}
						document.getElementById("justificacion").value 				= partes[13];
						if (partes[3] != 'elaboracion'){
							document.getElementById("justificacion").disabled 		= true;
						}else{
							document.getElementById("justificacion").disabled 		= false;
						}
						document.getElementById("observaciones").value 				= partes[14];
						if (partes[3] != 'elaboracion'){
							document.getElementById("observaciones").disabled 		= true;
						}else{
							document.getElementById("observaciones").disabled 		= false;
						}
						document.getElementById("ordenado_por").value 				= partes[15];
						document.getElementById("cedula_ordenado").value 			= partes[16];
						if (partes[3] != 'elaboracion'){
							document.getElementById("ordenado_por").disabled 		= true;
							document.getElementById("cedula_ordenado").disabled 	= true;
						}else{
							document.getElementById("ordenado_por").disabled 		= false;
							document.getElementById("cedula_ordenado").disabled 	= false;
						}
						document.getElementById("numero_requisicion").value 		= partes[17];
						if (partes[18] != '0000-00-00'){
							document.getElementById("fecha_requisicion").value 			= partes[18];
						}
						if (partes[3] != 'elaboracion'){
							document.getElementById("numero_requisicion").disabled 	= true;
							document.getElementById("fecha_requisicion").disabled 	= true;
						}else{
							document.getElementById("numero_requisicion").disabled 	= false;
							document.getElementById("fecha_requisicion").disabled 	= false;
						}
						
						document.getElementById("id_beneficiarios").value 			= partes[19];
						document.getElementById("nombre_proveedor").value 			= partes[20];
						document.getElementById("contribuyente_ordinario").value	= partes[21];
						if (partes[3] != 'elaboracion'){
							document.getElementById("buscarProveedor").style.display = 'none';
						}else{
							document.getElementById("buscarProveedor").style.display = 'block'
						}
						document.getElementById("nro_factura").value 				= partes[22];
						document.getElementById("nro_control").value 				= partes[23];
						document.getElementById("fecha_factura").value				= partes[24];
						
						document.getElementById("modo_comunicacion").value			= partes[25];
						document.getElementById("tipo_actividad").value				= partes[26];
						document.getElementById("tipo_procedimiento").value			= partes[27];
						document.getElementById("fecha_inicio").value				= partes[28];
						document.getElementById("fecha_cierre").value				= partes[29];
						
						if (partes[30]==1){
							document.getElementById("4998").checked				= true;
						}else{
							document.getElementById("4998").checked				= false;
						}
						
						if (partes[31]==1){
							document.getElementById("4910").checked				= true;
						}else{
							document.getElementById("4910").checked				= false;
						}
						
						
						if (partes[3] != 'elaboracion'){
							document.getElementById("nro_factura").disabled 		= true;
							document.getElementById("nro_control").disabled 		= true;
							document.getElementById("fecha_factura").disabled 		= true;
						}else{
							document.getElementById("nro_factura").disabled 		= false;
							document.getElementById("nro_control").disabled 		= false;
							document.getElementById("fecha_factura").disabled 		= false;
						}
				
				
				actualizarBotones(document.getElementById('id_orden_compra').value, document.getElementById("idestado").value);
				consultarMateriales(document.getElementById('id_orden_compra').value);
				actualizarListaDeTotales(document.getElementById('id_orden_compra').value);
				actualizarTotalesPartidas(id_orden_compra);
				setTimeout("mostrarPartidas(document.getElementById('id_orden_compra').value, document.getElementById('id_categoria_programatica').value)",600);
				mostrarCuentasContables(document.getElementById('id_orden_compra').value);
				document.getElementById('celdaImprimir').style.display='block';
				//document.getElementById('celdaRecalcular').style.display='block';
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
					mostrarCuentasContables(id_orden_compra);
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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
************************************** FUNCION PARA MOSTRAR LAS CUENTAS CONTABLES APLICADAS AL COMPROMISO  ***********************************************
**********************************************************************************************************************************************************/



function mostrarCuentasContables(id_orden_compra){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById('divContable').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&ejecutar=mostrarCuentasContables");
}


/*********************************************************************************************************************************************************
*************************************** ACTUALIZA LOS BOTONES DE PROCESAR; DUPLICAR; ANULAR; EN ELABORACION **********************************************
**********************************************************************************************************************************************************/



function actualizarBotones(id_orden_compra, estado){
	
	if (estado == 'elaboracion'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonDuplicar').style.display 			= "none";
		document.getElementById('botonEnElaboracion').style.display 	= "block";
		document.getElementById('botonProcesar').style.display 			= "block";
		document.getElementById('celdaRecalcular').style.display		='block';
		document.getElementById('celdaImprimir').style.display			='block';
		document.getElementById('botonAnular').style.display 			= "none";
	}
	if (estado == 'procesado' || estado == 'devuelto' || estado == 'conformado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonEnElaboracion').style.display 	= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonAnular').style.display 			= "block";
		document.getElementById('botonDuplicar').style.display 			= "block";
		document.getElementById('celdaRecalcular').style.display		='none';
		document.getElementById('celdaImprimir').style.display			='block';
	}
	if (estado == 'anulado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonEnElaboracion').style.display 	= "none";
		document.getElementById('botonProcesar').style.display 			= "none";
		document.getElementById('botonAnular').style.display			= "none";
		document.getElementById('celdaRecalcular').style.display		='none';
		document.getElementById('celdaImprimir').style.display			='block';
	}
	if (estado == 'procesado' || estado == 'devuelto' || estado == 'conformado' || estado == 'pagado' || estado == 'anulado' || estado == 'parcial'  || estado == 'ordenado'){
		document.getElementById('botonSiguiente').style.display			= "none";
		document.getElementById('botonDuplicar').style.display 			= "block";
		document.getElementById('celdaRecalcular').style.display		='none';
		document.getElementById('celdaImprimir').style.display			='block';
	}
	
	
}



/*********************************************************************************************************************************************************
**************************************************************** PROCESA LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function procesarOrden(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Procesar esta orden de compra?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					//alert(ajax.responseText);
					if(ajax.responseText == "exito"){
						document.getElementById('idestado').value='procesado';
						mostrarMensajes("exito", "La orden se Proceso con Exito");
					}else if(ajax.responseText == "sinMateriales"){
						document.getElementById('idestado').value='elaboracion';
						mostrarMensajes("error", "Disculpe debe seleccionar materiales para poder procesar la orden de compra");
					}else if (ajax.responseText == "falloImpuestos"){
						document.getElementById('idestado').value='elaboracion';
						mostrarMensajes("error", "Disculpe Hay impuestos sin Disponibilidad o Rechazados por favor verifique");
						consultarMateriales(id_orden_compra);
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, document.getElementById("id_categoria_programatica").value);
						mostrarCuentasContables(id_orden_compra);
					}else if(ajax.responseText == "fallo"){
						document.getElementById('idestado').value='elaboracion';
						mostrarMensajes("error", "Disculpe no se pudo procesar la Orden de Compra");
					}else if(ajax.responseText == "falloMateriales"){
						document.getElementById('idestado').value='elaboracion';
						mostrarMensajes("error", "Disculpe hay materiales sin disponibilidad o rechazados por favor verifique");
						consultarMateriales(id_orden_compra);
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, document.getElementById("id_categoria_programatica").value);
						mostrarCuentasContables(id_orden_compra);
					}else if(ajax.responseText == "duplicados"){
						document.getElementById('idestado').value='elaboracion';
						mostrarMensajes("error", "Disculpe hay materiales Duplicados");
						consultarMateriales(id_orden_compra);
						actualizarListaDeTotales(id_orden_compra);
						actualizarTotalesPartidas(id_orden_compra);
						mostrarPartidas(id_orden_compra, document.getElementById("id_categoria_programatica").value);
						mostrarCuentasContables(id_orden_compra);
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



function anularOrden(id_orden_compra, clave, fecha_anulacion_compromiso){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Anular la Orden?")){	
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					if(ajax.responseText == "exito"){
						document.getElementById('idestado').value='anulado';
						mostrarMensajes("exito", "La orden se Anulo con Exito");
						actualizarDatosBasicos("consultar");
						consultarMateriales(id_orden_compra);
						document.getElementById('divPreguntarUsuario').style.display = 'none';
						consultarOrdenCompra(id_orden_compra);
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
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&clave="+clave+"&fecha_anulacion_compromiso="+fecha_anulacion_compromiso+"&ejecutar=anularOrden");
	}
}



/*********************************************************************************************************************************************************
**************************************************************** DUPLICAR LA ORDEN DE COMPRA **************************************************************
**********************************************************************************************************************************************************/



function duplicarOrden(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	if(confirm("Realmente desea Duplicar la Orden?")){	
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){	
				document.getElementById('sproceso').disabled = false;
				document.getElementById('idestado').value='elaboracion';
				consultarOrdenCompra(ajax.responseText, document.getElementById('id_categoria_programatica').value);
				document.getElementById('listaSolicitudesProveedor').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("cofinanciamiento="+cofinanciamiento+"&id_orden_compra="+id_orden_compra+"&ejecutar=duplicarOrden");
	}
}





function consultarTipoCarga(id_orden_compra){
	
	var cofinanciamiento = document.getElementById('cofinanciamiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);
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

function consultarOrdenCompra(id_orden_compra, id_categoria_programatica){
	document.getElementById('id_orden_compra').value = id_orden_compra;
	var resultado = consultarTipoCarga(id_orden_compra);
	setTimeout("actualizarDatosBasicos('consultar', "+id_orden_compra+")",500);
	document.getElementById('tablaMaterialesPartidas').style.display='block';
	document.getElementById('tablaSNC').style.display='block';
	document.getElementById('tablaPartidas').style.display='block';
	document.getElementById('tablaContabilidad').style.display='block';
	consultarMateriales(id_orden_compra);
	actualizarListaDeTotales(id_orden_compra);
	actualizarTotalesPartidas(id_orden_compra);
	actualizarBotones(id_orden_compra, document.getElementById("idestado").value);
	mostrarPartidas(id_orden_compra, id_categoria_programatica);
	setTimeout("validarTipoListaMostrar("+resultado+","+id_orden_compra+")", 1000);
	setTimeout("esconderListaSolicitudes()", 1500);
	estado = document.getElementById('idestado').value;
	if (estado == 'elaboracion'){
		document.getElementById('divTablaProveedores').style.display='block';
		document.getElementById('proceso').style.display='block';
		document.getElementById('solicitudesSeleccionada').style.display='block';
		document.getElementById('formularioMateriales').style.display='block';
	}else{
		document.getElementById('divTablaProveedores').style.display='block';
		document.getElementById('proceso').style.display='none';
		document.getElementById('solicitudesSeleccionada').style.display='block';
		document.getElementById('formularioMateriales').style.display='none';
	}
	mostrarCuentasContables(id_orden_compra);
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
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
	ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
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
			ajax.open("POST", "lib/orden_compra/orden_compra_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					//alert(ajax.responseText);
						if(ajax.responseText.indexOf("exito") != -1){
							consultarMateriales(id_orden_compra);
							actualizarListaDeTotales(id_orden_compra);
							actualizarTotalesPartidas(id_orden_compra);
							
							actualizarBotones(id_orden_compra, document.getElementById("idestado").value);
							mostrarPartidas(id_orden_compra, id_categoria_programatica);
							mostrarCuentasContables(id_orden_compra);
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












