// JavaScript Document

function abreVentanaMateriales(){
	miPopup=window.open("lib/listas/lista_materia.php?frm=movimiento_materia_ajuste", "materias","width=900,height=400,scrollbars=yes")
	miPopup.focus()
}

function consultarTipoConceptoMovimiento(tipo){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('celda_tipo_concepto').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("tipo="+tipo+"&ejecutar=consultarTipoConceptoMovimiento");		
}

// *****************************************************************************************************************************
// ***************************** DAR FORMATO AL NUMERO *************************************************************************
// *****************************************************************************************************************************
function formatoNumero(idcampo) {
var res =  document.getElementById(idcampo).value; 
document.getElementById("id"+idcampo).value = res;
if (document.getElementById(idcampo).value < 99999999999)  { 
	resultado = parseFloat(res).toFixed(2).toString();
	resultado = resultado.split(".");
	var cadena = ""; cont = 1
	for(m=resultado[0].length-1; m>=0; m--){
		cadena = resultado[0].charAt(m) + cadena
		cont%3 == 0 && m >0 ? cadena = "." + cadena : cadena = cadena
		cont== 3 ? cont = 1 :cont++
	}
	document.getElementById(idcampo).value = cadena + "," + resultado[1]; 
} else { 
	document.getElementById(idcampo).value = 0;
	document.getElementById("id"+idcampo).value = 0;
	alert ("Debes indicar valores numericos en el campo "+idcampo);
	document.getElementById(idcampo).focus();
} 
}

function llenar_ajustada(){
	var idcantidad_ajuste			= document.getElementById('idcantidad_ajuste').value;
	var cantidad_actual_sin_formato	= document.getElementById('cantidad_actual_sin_formato').value;
	res = parseFloat(cantidad_actual_sin_formato) + parseFloat(idcantidad_ajuste);
	resultado = parseFloat(res).toFixed(2).toString();
	resultado = resultado.split(".");
	var cadena = ""; cont = 1
	for(m=resultado[0].length-1; m>=0; m--){
		cadena = resultado[0].charAt(m) + cadena
		cont%3 == 0 && m >0 ? cadena = "." + cadena : cadena = cadena
		cont== 3 ? cont = 1 :cont++
	}
	cantidad_ajustada = cadena + "," + resultado[1]; 
	
	document.getElementById('idcantidad_ajustada').value = res;
	document.getElementById('cantidad_ajustada').value = cantidad_ajustada;
}


function consultarDocumentoTransaccion(tipo){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('celda_documento_transaccion').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("tipo="+tipo+"&ejecutar=consultarDocumentoTransaccion");		
}

function abrirCerrarAjuste(){
	if(document.getElementById('divAjuste').style.display == 'none'){
		document.getElementById('divAjuste').style.display = 'block';
		document.getElementById('divAjustadosTitulo').style.marginTop = '610';
		document.getElementById('divListaMateriaAjustada').style.marginTop = '630';
		//document.getElementById('td_signo_mas_seriales').innerHTML = "-";
	}else{
		document.getElementById('divAjuste').style.display = 'none';
		document.getElementById('divAjustadosTitulo').style.marginTop = '310';
		document.getElementById('divListaMateriaAjustada').style.marginTop = '330';
		//document.getElementById('td_signo_mas_seriales').innerHTML = "+";
	}
}

function abrirCerrarMateriaAjuste(){
	if(document.getElementById('divListaMateriaAjustada').style.display == 'none'){
		document.getElementById('divListaMateriaAjustada').style.display = 'block'
		//document.getElementById('td_signo_mas_seriales').innerHTML = "-";
	}else{
		document.getElementById('divListaMateriaAjustada').style.display = 'none';
		//document.getElementById('td_signo_mas_seriales').innerHTML = "+";
	}
}

function consultarMostrar(tipo){
	if (tipo == 'salida'){
		document.getElementById('divDestino').style.display = 'block';
		document.getElementById('divProveedor').style.display = 'none';
		document.getElementById('divOrdenCompra').style.display = 'none';
		document.getElementById('botonBuscarProveedor').style.display = 'none';
		document.getElementById('divDocumentoTransaccion').style.display = 'block';
		document.getElementById('divAjusteTitulo').style.marginTop = '305px';
		document.getElementById('divAjuste').style.marginTop = '325px';	
	}
	if (tipo == 'entrada'){
		document.getElementById('divDestino').style.display = 'none';
		document.getElementById('divProveedor').style.display = 'block';
		document.getElementById('divOrdenCompra').style.display = 'none';
		document.getElementById('botonBuscarProveedor').style.display = 'block';
		document.getElementById('divDocumentoTransaccion').style.display = 'block';
		document.getElementById('divAjusteTitulo').style.marginTop = '305px';
		document.getElementById('divAjuste').style.marginTop = '325px';	
	}
	if (document.getElementById('accion_tipo_concepto').value != 0){
		document.getElementById('divOrdenCompra').style.display = 'block';
		document.getElementById('botonBuscarProveedor').style.display = 'none';
		document.getElementById('divAjusteTitulo').style.marginTop = '333px';
		document.getElementById('divAjuste').style.marginTop = '353px';	
	}
	if (tipo == 'inicial'){
		document.getElementById('divDestino').style.display = 'none';
		document.getElementById('divProveedor').style.display = 'none';
		document.getElementById('divOrdenCompra').style.display = 'none';
		document.getElementById('divDocumentoTransaccion').style.display = 'block';
		document.getElementById('divAjusteTitulo').style.marginTop = '280px';
		document.getElementById('divAjuste').style.marginTop = '300px';	
	}
}




function ingresarDatosBasicos(){
	//var idtipos_documentos			= document.getElementById('idtipos_documentos').value;
	var afecta 						= document.getElementById('afecta').value;
	var almacen						= document.getElementById('almacen').value;
	var tipo_concepto_movimiento	= document.getElementById('tipo_concepto_movimiento').value;
	var justificacion 				= document.getElementById('justificacion_movimiento').value;
	var ordenado_por 				= document.getElementById('ordenado_por').value;
	var cedula_ordenado 			= document.getElementById('cedula_ordenado').value;
	if (afecta == 'salida'){
		var idniveles_organizacionales	= document.getElementById('idniveles_organizacionales').value;
	}else{
		var idniveles_organizacionales	= 0;
	}
	if (afecta == 'entrada'){
		var idbeneficiario				= document.getElementById('idbeneficiario').value;
		var proveedor					= document.getElementById('proveedor').value;
	}else{
		var idbeneficiario				= 0;
		var proveedor					= '';
	}
	if (document.getElementById('accion_tipo_concepto').value != 0){
		var idorden_compra_servicio		= document.getElementById('idorden_compra_servicio').value;
	}else{
		var idorden_compra_servicio		= 0;
	}
	
	//validacion para evitar valores obligatorios vacios
	if (afecta == '0'){
		alert("Debe seleccionar una afectacion para el movimiento");
		document.getElementById('afecta').focus();
	}else if (almacen == '0'){
		alert("Debe seleccionar un almacen para el movimiento");
		document.getElementById('almacen').focus();
	}else if (tipo_concepto_movimiento == '0' && afecta != 'salida'){
			alert("Debe seleccionar un Concepto para el movimiento");
			document.getElementById('tipo_concepto_movimiento').focus();
	}else if (justificacion == ''){
		alert("Debe indicar una Justificacion para el movimiento");
		document.getElementById('justificacion_movimiento').focus();
	}else if (document.getElementById('accion_tipo_concepto').value != 0 && idorden_compra_servicio == 0){
		alert("Debe indicar la Orden de Compra para el movimiento");
		document.getElementById('orden_compra').focus();
	}else if (afecta == 'salida' && idniveles_organizacionales == 0){
		alert("Debe indicar la Dependencia hacia donde van los materiales");
		document.getElementById('unidad_destino').focus();
	}else if (afecta == 'entrada' && proveedor == ''){
		alert("Debe seleccionar un proveedor o escribir su Nombre o Razon Social");
		document.getElementById('proveedor').focus();
	}else{
		var documento_transaccion		= document.getElementById('documento_transaccion').value;
		var numero_documento_transaccion= document.getElementById('numero_documento_transaccion').value;
		var fecha_documento_transaccion	= document.getElementById('fecha_documento_transaccion').value;
		
		var ajax				= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				alert (ajax.responseText);
				var partes = ajax.responseText.split("|.|");
					if(partes[0] == "exito"){
						if(document.getElementById('afecta').value == "inicial"){
							document.getElementById('divGrupoAjuste').style.display = 'block';
							document.getElementById('divMateriaAjustada').style.display = 'block';
							//document.getElementById('existentes_desincorporacion').style.display = 'none';
							//document.getElementById('existentes_incorporacion').style.display = 'none';
						}/*else if(document.getElementById('accion_tipo_movimiento').value == "existente_incorporacion"){
							document.getElementById('bienes_nuevos').style.display = 'none';
							document.getElementById('existentes_desincorporacion').style.display = 'none';
							document.getElementById('existentes_incorporacion').style.display = 'block';
						}else if(document.getElementById('accion_tipo_movimiento').value == "existente_desincorporacion"){
							document.getElementById('bienes_nuevos').style.display = 'none';
							document.getElementById('existentes_desincorporacion').style.display = 'block';
							document.getElementById('existentes_incorporacion').style.display = 'none';
						}
						*/
						//document.getElementById('idtipos_documentos').disabled = true;
						document.getElementById('afecta').disabled = true;
						document.getElementById('almacen').disabled = true;
						document.getElementById('tipo_concepto_movimiento').disabled = true;
						document.getElementById('proveedor').disabled = true;
						document.getElementById('orden_compra').disabled = true;
						document.getElementById('fecha_orden_compra').disabled = true;
						document.getElementById('boton_siguiente').style.display = 'none';
						document.getElementById('boton_actualizar').style.display = 'block';
						document.getElementById('boton_procesar').style.display = 'block';
						document.getElementById('idmovimiento').value = partes[1];
					}else{
						mostrarMensajes("error", "Disculpe los datos no pudieron ser registrados con exito, por favor vuelva a intentarlo")	;
					}
					//listarRegistroFotograficoBienesNuevos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("afecta="+afecta+"&almacen="+almacen+"&tipo_concepto_movimiento="+tipo_concepto_movimiento+"&justificacion="+justificacion+"&ordenado_por="+ordenado_por+"&cedula_ordenado="+cedula_ordenado+"&idniveles_organizacionales="+idniveles_organizacionales+"&idbeneficiario="+idbeneficiario+"&proveedor="+proveedor+"&idorden_compra_servicio="+idorden_compra_servicio+"&documento_transaccion="+documento_transaccion+"&numero_documento_transaccion="+numero_documento_transaccion+"&fecha_documento_transaccion="+fecha_documento_transaccion+"&ejecutar=ingresarDatosBasicos");	
	}
}


function actualizarDatosBasicos(){
	//var idmovimiento				= document.getElementById('idmovimiento').value;
	var idtipos_documentos			= document.getElementById('idtipos_documentos').value;
	var afecta 						= document.getElementById('afecta').value;
	var almacen						= document.getElementById('almacen').value;
	var tipo_concepto_movimiento	= document.getElementById('tipo_concepto_movimiento').value;
	var justificacion 				= document.getElementById('justificacion_movimiento').value;
	var ordenado_por 				= document.getElementById('ordenado_por').value;
	var cedula_ordenado 			= document.getElementById('cedula_ordenado').value;
	var idniveles_organizacionales	= document.getElementById('idniveles_organizacionales').value;
	var idbeneficiario				= document.getElementById('idbeneficiario').value;
	var proveedor					= document.getElementById('proveedor').value;
	var idorden_compra_servicio		= document.getElementById('idorden_compra_servicio').value;
	var documento_transaccion		= document.getElementById('documento_transaccion').value;
	var numero_documento_transaccion= document.getElementById('numero_documento_transaccion').value;
	var fecha_documento_transaccion	= document.getElementById('fecha_documento_transaccion').value;
		
		var ajax				= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					if(ajax.responseText=="exito"){
						mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
						//document.getElementById('idtipos_documentos').disabled = true;
						document.getElementById('afecta').disabled = true;
						document.getElementById('almacen').disabled = true;
						document.getElementById('tipo_concepto_movimiento').disabled = true;
						document.getElementById('proveedor').disabled = true;
						document.getElementById('orden_compra').disabled = true;
						document.getElementById('fecha_orden_compra').disabled = true;
						document.getElementById('boton_siguiente').style.display = 'none';
						document.getElementById('boton_actualizar').style.display = 'block';
						document.getElementById('boton_procesar').style.display = 'block'; 
					}else{
						mostrarMensajes("error", "Disculpe los datos no pudieron ser actualizados con exito, por favor vuelva a intentarlo")	;
					}
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("justificacion="+justificacion+"&ordenado_por="+ordenado_por+"&cedula_ordenado="+cedula_ordenado+"&idniveles_organizacionales="+idniveles_organizacionales+"&idbeneficiario="+idbeneficiario+"&proveedor="+proveedor+"&idorden_compra_servicio="+idorden_compra_servicio+"&documento_transaccion="+documento_transaccion+"&numero_documento_transaccion="+numero_documento_transaccion+"&fecha_documento_transaccion="+fecha_documento_transaccion+"&ejecutar=actualizarDatosBasicos");	
	
}



//*****************************************************************************************************************************************************
//*****************************************************************************************************************************************************
//*********************************************** MATERIALES DESDE AJUSTE DE INVENTARIO ***************************************************************
//*****************************************************************************************************************************************************
//*****************************************************************************************************************************************************
//*****************************************************************************************************************************************************


function ingresarMaterialAjuste(){
	var idinventario_materia 	= document.getElementById('idinventario_materia').value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var cantidad_ajuste			= document.getElementById("idcantidad_ajuste").value;
	
	if(cantidad_ajuste == 0){
		mostrarMensajes("error", "Disculpe debe indicar la cantidad a Ajustar del Material");
		document.getElementById("cantidad_ajuste").focus();
	}else {
		var ajax					= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {			
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					if(ajax.responseText=="exito"){
						mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
					}else if (ajax.responseText=="existe"){
						mostrarMensajes("error", "Disculpe el material que intenta ajustar ya esta registrado en ese movimiento, por favor verifique");	
					}
					document.getElementById("divCargando").style.display = "none";
					mostrar_tablas();
					lista_materiales_movimiento();
			} 
		}
		ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&cantidad_ajuste="+cantidad_ajuste+"&ejecutar=ingresarMaterialAjuste");	
	}
}


function mostrar_tablas(){
	var idinventario_materia = document.getElementById('idinventario_materia').value;
	var serializado			= document.getElementById('serializado').value;
	var caduca				= document.getElementById('caduca').value;
	var idcantidad_ajuste	= document.getElementById('idcantidad_ajuste').value;
	if (serializado == '1' || caduca == '1'){
		document.getElementById('divAjuste').style.height = '300';
		document.getElementById('divAjustadosTitulo').style.marginTop = '610';
		document.getElementById('divListaMateriaAjustada').style.marginTop = '630';
		//document.getElementById('cantidad_ajuste').disabled = true;
	}
	if (serializado == '1'){
		document.getElementById('barra_seriales').style.display = 'block';
		document.getElementById('boton_ajuste').style.display = 'none';
		document.getElementById('boton_cierre_ajuste').style.display = 'block';
		if (idcantidad_ajuste < 0){
			document.getElementById('cargador_serial').style.display = 'none';
			mostrarSerialesAjustar();
		}else{
			document.getElementById('cargador_serial').style.display = 'block';
		}
	}
	if (caduca == '1'){
		document.getElementById('barra_fecha_vencimiento').style.display = 'block';
		document.getElementById('boton_ajuste').style.display = 'none';
		document.getElementById('boton_cierre_ajuste').style.display = 'block';
		if (idcantidad_ajuste < 0){
			document.getElementById('cargador_fecha_vencimiento').style.display = 'none';
			mostrarFechaVencimientoAjustar(idinventario_materia);
		}else{
			document.getElementById('cargador_fecha_vencimiento').style.display = 'block';
			consultarFVencimiento(idinventario_materia);
		}
	}
	if (serializado == '0' && caduca == '0'){
		limpiarMateriaAjuste();
	}
}

function lista_materiales_movimiento(){
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {			
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){			
			document.getElementById('lista_materiales').innerHTML = ajax.responseText;	
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listaMaterialesMovimiento");
	
}

function limpiarMateriaAjuste(){
	document.getElementById('boton_ajuste').style.display 				= 'block';
	document.getElementById('boton_cierre_ajuste').style.display		= 'none';
	document.getElementById('barra_seriales').style.display 			= 'none';
	document.getElementById('barra_fecha_vencimiento').style.display 	= 'none';
	document.getElementById('codigo_materia').value 		= '';
	document.getElementById('descripcion_materia').value 	= '';
	document.getElementById('cantidad_actual').value 		= '';
	document.getElementById('cantidad_ajuste').value 		= '';
	document.getElementById('cantidad_ajustada').value 		= '';
	document.getElementById('unidad_materia').value 		= '';
	document.getElementById('divAjuste').style.height = '165';
	document.getElementById('divAjustadosTitulo').style.marginTop = '475';
	document.getElementById('divListaMateriaAjustada').style.marginTop = '495';
}


function ingresarSerial(){
	var idinventario_materia	= document.getElementById("idinventario_materia").value;
	var serial					= document.getElementById("serialMateria").value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var cantidadmovimiento		= document.getElementById("idcantidad_ajuste").value;
	
	if(serial == ""){
		mostrarMensajes("error", "Disculpe debe ingresar el Serial del Material");
		document.getElementById("serialMateria").focus();
	}else {
		var ajax					= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {			
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					if(ajax.responseText=="exito"){
						mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
				   		consultarSeriales(idinventario_materia);
					}else if (ajax.responseText=="existe"){
						mostrarMensajes("error", "Disculpe el serial que intenta ingresar ya esta registrado en ese material, por favor verifique");	
					}else if (ajax.responseText=="limite"){
						mostrarMensajes("error", "YA INGRESO LA CANTIDAD DE SERIALES PARA EL TOTAL AJUSTADO DE MATERIALES ");	
					}
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&serial="+serial+"&cantidadmovimiento="+cantidadmovimiento+"&ejecutar=ingresarSerial");	
	}
}

 function abrirCerrarSeriales(){
	if(document.getElementById('mostrar_seriales').style.display == 'none'){
		document.getElementById('mostrar_seriales').style.display = 'block'
	}else{
		document.getElementById('mostrar_seriales').style.display = 'none';
		
	}	 
 }

function abrirCerrarFechaVencimiento(){
	if(document.getElementById('mostrar_fecha_vencimiento').style.display == 'none'){
		document.getElementById('mostrar_fecha_vencimiento').style.display = 'block'
	}else{
		document.getElementById('mostrar_fecha_vencimiento').style.display = 'none';
	}		
}

function consultarSeriales(){
	var idinventario_materia	= document.getElementById('idinventario_materia').value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				//document.getElementById('cargador_serial').style.display = 'block';
				document.getElementById('lista_seriales').innerHTML = ajax.responseText;
				document.getElementById("serialMateria").value = "";
				document.getElementById("divCargando").style.display = "none";
				var van_seriales		= document.getElementById("van_seriales").value;
				document.getElementById('van_serial').innerHTML 	= "Van: "+van_seriales;
		} 
	}
	ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&ejecutar=consultarSeriales");
	
}

function EliminarSerialMovimiento(idrelacion_serial_movimiento_materia){
	var ajax		= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById("divCargando").style.display = "none";
				consultarSeriales();
		} 
	}
	ajax.send("idrelacion_serial_movimiento_materia="+idrelacion_serial_movimiento_materia+"&ejecutar=EliminarSerialMovimiento");
}

function mostrarSerialesAjustar(){
	var idinventario_materia	= document.getElementById('idinventario_materia').value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var cantidadajustada		= document.getElementById("idcantidad_ajustada").value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById('lista_seriales').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&cantidadajustada="+cantidadajustada+"&ejecutar=mostrarSerialesAjustar");
}

function EliminarSerial(idrelacion_serial_materia){
	var idinventario_materia 		= document.getElementById('idinventario_materia').value;
	var idmovimiento				= document.getElementById("idmovimiento").value;
	var cantidadajustada			= document.getElementById("idcantidad_ajustada").value;
	
	//if (cantidadinventario_inicial == cantidad_seriales){
	//	mostrarMensajes("error", "No puede elimnar mas seriales, ya llego a la cantidad ajustada");
	//}else{
		var ajax		= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
					document.getElementById("divCargando").style.display = "none";
					mostrarSerialesAjustar(idinventario_materia);
			} 
		}
		ajax.send("idrelacion_serial_materia="+idrelacion_serial_materia+"&idmovimiento="+idmovimiento+"&idinventario_materia="+idinventario_materia+"&cantidadajustada="+cantidadajustada+"&ejecutar=EliminarSerial");
	//}
}

function ReingresarSerial(serial){
	var idinventario_materia 		= document.getElementById('idinventario_materia').value;
	var idmovimiento				= document.getElementById("idmovimiento").value;
	var cantidadajustada			= document.getElementById("idcantidad_ajustada").value;
		var ajax		= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
					document.getElementById("divCargando").style.display = "none";
					mostrarSerialesAjustar(idinventario_materia);
			} 
		}
		ajax.send("serial="+serial+"&idmovimiento="+idmovimiento+"&idinventario_materia="+idinventario_materia+"&cantidadajustada="+cantidadajustada+"&ejecutar=ReingresarSerial");
	
}

function ingresarFVencimiento(){
	var idinventario_materia	= document.getElementById("idinventario_materia").value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var lote					= document.getElementById("lote").value;
	var fecha_vencimiento		= document.getElementById("fecha_vencimiento_materia").value;
	var cantidad				= document.getElementById("cantidad_fecha_vencimiento").value;
	var cantidad_movimiento		= document.getElementById("idcantidad_ajuste").value;
	
	if(lote == ""){
		mostrarMensajes("error", "Disculpe debe ingresar el Lote del Material");
		document.getElementById("lote").focus();
	}else if(cantidad == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la Cantidad de items en el Lote del Material");
		document.getElementById("cantidad_fecha_vencimiento").focus();
	}else if(fecha_vencimiento == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la Fecha de Vencimiento de los items del Lote del Material");
		document.getElementById("fecha_vencimiento").focus();
	}else {
		var ajax					= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {			
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					
					}
			if (ajax.readyState==4){
				alert(ajax.responseText);
					if(ajax.responseText=="exito"){
						mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
				   		consultarFVencimiento(idinventario_materia);
					}else if (ajax.responseText=="existe"){
						mostrarMensajes("error", "Disculpe el lote que intenta ingresar ya esta registrado en ese material, por favor verifique");	
					}else if (ajax.responseText=="limite"){
						mostrarMensajes("error", "YA INGRESO LA CANTIDAD DE ITEMS PARA CADA UNO DE LOS MATERIALES DEL INVENTARIO INICIAL");	
					}
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&lote="+lote+"&cantidad_movimiento="+cantidad_movimiento+"&cantidad="+cantidad+"&fecha_vencimiento="+fecha_vencimiento+"&ejecutar=ingresarFVencimiento");	
	}
}


function consultarFVencimiento(){
	var idinventario_materia 	= document.getElementById('idinventario_materia').value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('lista_fecha_vencimiento').innerHTML = ajax.responseText;
				document.getElementById("lote").value = "";
				document.getElementById("cantidad_fecha_vencimiento").value = "";
				document.getElementById("fecha_vencimiento_materia").value = "";
				document.getElementById("divCargando").style.display = "none";
				var van_fechas		= document.getElementById("van_fechas").value;
				document.getElementById('van_fecha').innerHTML 	= "Van: "+van_fechas;
		} 
	}
	ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&ejecutar=consultarFVencimiento");
	
}

function eliminarLoteVencimientoMovimiento(idrelacion_vencimiento_movimiento_materia){
	var ajax		= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById("divCargando").style.display = "none";
				consultarFVencimiento();
		} 
	}
	ajax.send("idrelacion_vencimiento_movimiento_materia="+idrelacion_vencimiento_movimiento_materia+"&ejecutar=eliminarLoteVencimientoMovimiento");
}


function mostrarFechaVencimientoAjustar(){
	var idinventario_materia 			= document.getElementById('idinventario_materia').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('lista_fecha_vencimiento').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idinventario_materia="+idinventario_materia+"&ejecutar=mostrarFechaVencimientoAjustar");
	
}


function ajustarLoteVencimiento(idrelacion_vencimiento_materia){
	var idinventario_materia 	= document.getElementById('idinventario_materia').value;
	var idmovimiento			= document.getElementById("idmovimiento").value;
	var idcantidad 				= "cantidad_ajuste_lote"+idrelacion_vencimiento_materia;
	var cantidad_ajuste_lote	= parseInt(document.getElementById(idcantidad).value);
	var cantidadajustada		= document.getElementById("idcantidad_ajustada").value;
	
		var ajax		= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				alert(ajax.responseText);
					if(ajax.responseText=="exito"){
						mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
						consultarFVencimiento(idinventario_materia);
					}else if (ajax.responseText=="mayor"){
						mostrarMensajes("error", "Disculpe la cantidad que intenta ajustar es mayor a la cantidad AJUSTADA de la materia, por favor verifique");	
					}else if (ajax.responseText=="superior"){
						mostrarMensajes("error", "Disculpe la cantidad que intenta ajustar dejaria la existencia en negativo, por favor verifique");	
					}
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idinventario_materia="+idinventario_materia+"&idrelacion_vencimiento_materia="+idrelacion_vencimiento_materia+"&cantidad_ajuste_lote="+cantidad_ajuste_lote+"&idmovimiento="+idmovimiento+"&cantidadajustada="+cantidadajustada+"&ejecutar=ajustarLoteVencimiento");
	
}


function finalizarAjusteMateria(){
	var idinventario_materia 		= document.getElementById('idinventario_materia').value;
	var idmovimiento				= document.getElementById("idmovimiento").value;
	var cantidad_ajustada			= document.getElementById("idcantidad_ajustada").value;
	var serializado					= document.getElementById('serializado').value;
	var caduca						= document.getElementById('caduca').value;
	var cantidad_ajuste				= document.getElementById('idcantidad_ajuste').value;
	
	var ajax		= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			alert(ajax.responseText);
				if(ajax.responseText=="exito"){
						mostrarMensajes("exito", "El Ajuste fue finalizado con exito con exito");
						limpiarMateriaAjuste();
					}else if (ajax.responseText=="faltan_seriales"){
						mostrarMensajes("error", "Faltan Ingresar seriales para llegar a la Cantidad Ajustada");	
					}else if (ajax.responseText=="faltan_fechas"){
						mostrarMensajes("error", "Faltan Ingresar Lotes de Fecha de Vencimiento para llegar a la Cantidad Ajustada");	
					}
					document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idinventario_materia="+idinventario_materia+"&idmovimiento="+idmovimiento+"&cantidad_ajuste="+cantidad_ajuste+"&cantidad_ajustada="+cantidad_ajustada+"&serializado="+serializado+"&caduca="+caduca+"&ejecutar=finalizarAjusteMateria");
	
}

function limpiarMaterial(){
	document.getElementById('codigo_materia').value = "";
	document.getElementById('descripcion_materia').value = "";
	document.getElementById('cantidad_actual').value = "";
	document.getElementById('cantidad_ajuste').value = "";
	document.getElementById('unidad_materia').value = "";
	document.getElementById('cantidad_ajustada').value = "";
}






















function ingresarBienesNuevos(){
	var idmovimiento 			= document.getElementById('idmovimiento').value;
	//var nro_documento 			= document.getElementById('nro_documento_bienes_nuevos').value;
	//var fecha_documento 		= document.getElementById('fecha_documento_bienes_nuevos').value;
	//var idbenficiario			= document.getElementById('proveedores_bienes_nuevos').value;
	//var nro_factura				= document.getElementById('nro_factura_bienes_nuevos').value;
	//var fecha_factura			= document.getElementById('fecha_factura_bienes_nuevos').value;
	var idorganizacion 			= document.getElementById('organizacion_bienes_nuevos').value;
	var nivel_organizacional	= document.getElementById('nivel_organizacional_bienes_nuevos').value;
	var codigo_catalogo			= document.getElementById('idcodigo_catalogo_bienes_nuevos').value;
	var codigo_bien				= document.getElementById('idcodigo_bien_bienes_nuevos').value;
	var idubicacion				= document.getElementById('ubicacion_bienes_nuevos').value;
	var especificaciones		= document.getElementById('especificaciones_bienes_nuevos').value;
	var marca					= document.getElementById('marca_bienes_nuevos').value;
	var modelo 					= document.getElementById('modelos_bienes_nuevos').value;
	var tipo					= document.getElementById('tipo_bienes_nuevos').value;
	var serial					= document.getElementById('seriales_bienes_nuevos').value;
	var accesorios				= document.getElementById('accesorios_bienes_nuevos').value;
	var costo					= document.getElementById('costo_bienes_nuevos').value;
	var valor_residual			= document.getElementById('valor_residual_bienes_nuevos').value;
	var vida_util				= document.getElementById('vida_util_bienes_nuevos').value;
	var mejoras					= document.getElementById('mejoras_bienes_nuevos').value;
	var costo_ajustado			= document.getElementById('costo_ajustado_bienes_nuevos').value;
	var depreciacion_anual		= document.getElementById('depreciacion_anual_bienes_nuevos').value;
	var depreciacion_acumulada	= document.getElementById('depreciacion_acumulada_bienes_nuevos').value;
	if(document.getElementById('asegurado_bienes_nuevos').checked == true){
		var asegurado			= "si";	
	}else{
		var asegurado			= "no";	
	}
	
 	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					listarBienesNuevos();
					limpiarBienesNuevos();
					listarRegistroFotograficoBienesNuevos();
				}else{
					mostrarMensajes("error", "Disculpe los datos no pudieron ser ingresados por favor intente de nuevo mas tarde "+ajax.responseText);
				}
				//document.getElementById('formulario_bienes_nuevos').reset();
				
		} 
	}
	//nro_documento="+nro_documento+"&fecha_documento="+fecha_documento+"&idbenficiario="+idbenficiario+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&
	ajax.send("idmovimiento_bienes="+idmovimiento+"&idorganizacion="+idorganizacion+"&nivel_organizacional="+nivel_organizacional+"&codigo_catalogo="+codigo_catalogo+"&codigo_bien="+codigo_bien+"&idubicacion="+idubicacion+"&especificaciones="+especificaciones+"&marca="+marca+"&modelo="+modelo+"&tipo="+tipo+"&serial="+serial+"&accesorios="+accesorios+"&costo="+costo+"&valor_residual="+valor_residual+"&vida_util="+vida_util+"&mejoras="+mejoras+"&costo_ajustado="+costo_ajustado+"&depreciacion_anual="+depreciacion_anual+"&asegurado="+asegurado+"&depreciacion_acumulada="+depreciacion_acumulada+"&ejecutar=ingresarBienesNuevos");		
}



function limpiarBienesNuevos(){
		document.getElementById('organizacion_bienes_nuevos').value = '0';
		document.getElementById('nivel_organizacional_bienes_nuevos').value = '0';
		document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '';
		document.getElementById('codigo_catalogo_bienes_nuevos').value = '';
		document.getElementById('codigo_bien_bienes_nuevos').value = '';
		document.getElementById('idcodigo_bien_bienes_nuevos').value = '';
		document.getElementById('ubicacion_bienes_nuevos').value = '0';
		document.getElementById('especificaciones_bienes_nuevos').value = '';
		document.getElementById('marca_bienes_nuevos').value = '';
		document.getElementById('modelos_bienes_nuevos').value = '';
		document.getElementById('tipo_bienes_nuevos').value = '';
		document.getElementById('accesorios_bienes_nuevos').value = '';
		document.getElementById('costo_bienes_nuevos').value = '';
		document.getElementById('valor_residual_bienes_nuevos').value = '';
		document.getElementById('vida_util_bienes_nuevos').value = '';
		document.getElementById('mejoras_bienes_nuevos').value = '';
		document.getElementById('costo_ajustado_bienes_nuevos').value = '';
		document.getElementById('depreciacion_anual_bienes_nuevos').value = '';
		document.getElementById('depreciacion_acumulada_bienes_nuevos').value = '';
		document.getElementById('asegurado_bienes_nuevos').value = '';
		
}



function listarBienesNuevos(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('lista_bienes_nuevos').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarBienesNuevos");		
}




function eliminarBienesNuevos(id){
	if(confirm("Seguro desea eliminar el movimiento seleccionado?")){
		var ajax			= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					listarBienesNuevos();
			} 
		}
		ajax.send("idmovimiento="+id+"&ejecutar=eliminarBienesNuevos");			
		}	
}


function seleccionarModificarBienesNuevos(nro_documento, fecha_documento, idbenficiario, nro_factura, fecha_factura, idorganizacion, nivel_organizacional, codigo_catalogo, codigo_bien, idubicacion, especificaciones, marca, modelo, tipo, serial, accesorios, costo, valor_residual, vida_util, mejoras, costo_ajustado, depreciacion_anual, depreciacion_acumulada, asegurado, idmovimientos_bienes_nuevos,
idcodigo_catalogo,
idcodigo_bien){
	
	document.getElementById('idmovimientos_bienes_nuevos').value = idmovimientos_bienes_nuevos;
	document.getElementById('nro_documento_bienes_nuevos').value 			= nro_documento;
	document.getElementById('fecha_documento_bienes_nuevos').value 			= fecha_documento;
	document.getElementById('proveedores_bienes_nuevos').value 				= idbenficiario;
	document.getElementById('nro_factura_bienes_nuevos').value 				= nro_factura;
	document.getElementById('fecha_factura_bienes_nuevos').value 			= fecha_factura;
	document.getElementById('organizacion_bienes_nuevos').value 			= idorganizacion;

	document.getElementById('idcodigo_catalogo_bienes_nuevos').value 		= idcodigo_catalogo;
	document.getElementById('idcodigo_bien_bienes_nuevos').value 			= idcodigo_bien;
	document.getElementById('codigo_catalogo_bienes_nuevos').value 			= codigo_catalogo;
	document.getElementById('codigo_bien_bienes_nuevos').value 				= codigo_bien;
	document.getElementById('ubicacion_bienes_nuevos').value 				= idubicacion;
	document.getElementById('especificaciones_bienes_nuevos').value 		= especificaciones;
	document.getElementById('marca_bienes_nuevos').value 					= marca;
	document.getElementById('modelos_bienes_nuevos').value 					= modelo;
	document.getElementById('tipo_bienes_nuevos').value 					= tipo;
	document.getElementById('seriales_bienes_nuevos').value 				= serial;
	document.getElementById('accesorios_bienes_nuevos').value 				= accesorios;
	document.getElementById('costo_bienes_nuevos').value = costo 			= costo;
	document.getElementById('valor_residual_bienes_nuevos').value 			= valor_residual;
	document.getElementById('vida_util_bienes_nuevos').value 				= vida_util;
	document.getElementById('mejoras_bienes_nuevos').value 					= mejoras;
	document.getElementById('costo_ajustado_bienes_nuevos').value 			= costo_ajustado;
	document.getElementById('depreciacion_anual_bienes_nuevos').value 		= depreciacion_anual;
	document.getElementById('depreciacion_acumulada_bienes_nuevos').value 	= depreciacion_acumulada;
	if(asegurado == "si"){
		document.getElementById('asegurado_bienes_nuevos').checked	= true;	
	}else{
		document.getElementById('asegurado_bienes_nuevos').checked	= false;	
	}	
	
	document.getElementById('boton_ingresar_bienes_nuevos').style.display = 'none';
	document.getElementById('boton_modificar_bienes_nuevos').style.display = 'block';
	
	consultarNivelOrganizacional(idorganizacion, 'nivel_organizacional_bienes_nuevos', 'div_nivel_organizacional_bienes_nuevos');
	setTimeout("document.getElementById('nivel_organizacional_bienes_nuevos').value = "+nivel_organizacional, 500);
	
	
	
}



function modificarBienesNuevos(){
	var idmovimientos_bienes_nuevos 			= document.getElementById('idmovimientos_bienes_nuevos').value;
	//var nro_documento 			= document.getElementById('nro_documento_bienes_nuevos').value;
	//var fecha_documento 		= document.getElementById('fecha_documento_bienes_nuevos').value;
	//var idbenficiario			= document.getElementById('proveedores_bienes_nuevos').value;
	//var nro_factura				= document.getElementById('nro_factura_bienes_nuevos').value;
	//var fecha_factura			= document.getElementById('fecha_factura_bienes_nuevos').value;
	var idorganizacion 			= document.getElementById('organizacion_bienes_nuevos').value;
	var nivel_organizacional	= document.getElementById('nivel_organizacional_bienes_nuevos').value;
	var codigo_catalogo			= document.getElementById('idcodigo_catalogo_bienes_nuevos').value;
	var codigo_bien				= document.getElementById('idcodigo_bien_bienes_nuevos').value;
	var idubicacion				= document.getElementById('ubicacion_bienes_nuevos').value;
	var especificaciones		= document.getElementById('especificaciones_bienes_nuevos').value;
	var marca					= document.getElementById('marca_bienes_nuevos').value;
	var modelo 					= document.getElementById('modelos_bienes_nuevos').value;
	var tipo					= document.getElementById('tipo_bienes_nuevos').value;
	var serial					= document.getElementById('seriales_bienes_nuevos').value;
	var accesorios				= document.getElementById('accesorios_bienes_nuevos').value;
	var costo					= document.getElementById('costo_bienes_nuevos').value;
	var valor_residual			= document.getElementById('valor_residual_bienes_nuevos').value;
	var vida_util				= document.getElementById('vida_util_bienes_nuevos').value;
	var mejoras					= document.getElementById('mejoras_bienes_nuevos').value;
	var costo_ajustado			= document.getElementById('costo_ajustado_bienes_nuevos').value;
	var depreciacion_anual		= document.getElementById('depreciacion_anual_bienes_nuevos').value;
	var depreciacion_acumulada	= document.getElementById('depreciacion_acumulada_bienes_nuevos').value;
	if(document.getElementById('asegurado_bienes_nuevos').checked == true){
		var asegurado			= "si";	
	}else{
		var asegurado			= "no";	
	}
	
 	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				listarBienesNuevos();	
				document.getElementById('formulario_bienes_nuevos').reset();
				
		} 
	}
	//nro_documento="+nro_documento+"&fecha_documento="+fecha_documento+"&idbenficiario="+idbenficiario+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&
	
	ajax.send("idmovimientos_bienes_nuevos="+idmovimientos_bienes_nuevos+"&idmovimiento_bienes="+idmovimiento+"&idorganizacion="+idorganizacion+"&nivel_organizacional="+nivel_organizacional+"&codigo_catalogo="+codigo_catalogo+"&codigo_bien="+codigo_bien+"&idubicacion="+idubicacion+"&especificaciones="+especificaciones+"&marca="+marca+"&modelo="+modelo+"&tipo="+tipo+"&serial="+serial+"&accesorios="+accesorios+"&costo="+costo+"&valor_residual="+valor_residual+"&vida_util="+vida_util+"&mejoras="+mejoras+"&costo_ajustado="+costo_ajustado+"&depreciacion_anual="+depreciacion_anual+"&asegurado="+asegurado+"&depreciacion_acumulada="+depreciacion_acumulada+"&ejecutar=modificarBienesNuevos");	
}










function ingresarExistentesDesincorporacion(){
  var idmovimientos_bienes 	= document.getElementById('idmovimiento').value;
  var codigo_bien			= document.getElementById('idcodigo_bien_existente_desincorporacion').value;
  var codigo_catalogo		= document.getElementById('idcodigo_catalogo_existente_desincorporacion').value;
  var especificadores		= document.getElementById('especificaciones_existente_desincorporacion').value;
  var motivos				= document.getElementById('motivo_existente_desincorporacion').value;
  var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				listarBienesExistentesDesincorporacion(); 
				document.getElementById('formulario_existentes_desincorporacion').reset();
				listarRegistroFotograficoExistentesDesincorporacion();
		} 
	}
	ajax.send("idmovimientos_bienes="+idmovimientos_bienes+"&codigo_bien="+codigo_bien+"&codigo_catalogo="+codigo_catalogo+"&especificadores="+especificadores+"&motivos="+motivos+"&ejecutar=ingresarExistentesDesincorporacion");	
	  
}



function listarBienesExistentesDesincorporacion(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('lista_existente_desincorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarBienesExistentesDesincorporacion");	
}



function seleccionarModificarBienesExistentesDesincorporacion(especificadores, motivos, idmovimientos_existentes_desincorporacion, idcodigo_bien, idcodigo_catalogo, codigo_bien, codigo_catalogo){
	document.getElementById('idcodigo_bien_existente_desincorporacion').value 				= idcodigo_bien;
	document.getElementById('codigo_bien_existente_desincorporacion').value 				= codigo_bien;
	document.getElementById('idcodigo_catalogo_existente_desincorporacion').value 			= idcodigo_catalogo;
	document.getElementById('codigo_catalogo_existente_desincorporacion').value 			= codigo_catalogo;
	document.getElementById('especificaciones_existente_desincorporacion').value 			= especificadores;
	document.getElementById('motivo_existente_desincorporacion').value 						= motivos;
	document.getElementById('idmovimientos_existentes_desincorporacion').value 				= idmovimientos_existentes_desincorporacion;
	document.getElementById('boton_ingresar_existentes_desincorporacion').style.display 	= 'none';
	document.getElementById('boton_modificar_existentes_desincorporacion').style.display 	= 'block';
}



function eliminarExistentesDesincporacion(id){
	if(confirm("Seguro desea eliminar el movimiento seleccionado?")){
		var ajax			= nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					listarBienesExistentesDesincorporacion();
			} 
		}
		ajax.send("idmovimiento="+id+"&ejecutar=eliminarExistentesDesincporacion");			
		}	
}



function modificarExistentesDesincorporacion(){
  var idmovimientos_bienes 	= document.getElementById('idmovimiento').value;
  var idmovimientos_existentes_desincorporacion 	= document.getElementById('idmovimientos_existentes_desincorporacion').value;
  var codigo_bien			= document.getElementById('idcodigo_bien_existente_desincorporacion').value;
  var codigo_catalogo		= document.getElementById('idcodigo_catalogo_existente_desincorporacion').value;
  var especificadores		= document.getElementById('especificaciones_existente_desincorporacion').value;
  var motivos				= document.getElementById('motivo_existente_desincorporacion').value;
  var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				listarBienesExistentesDesincorporacion(); 
				document.getElementById('formulario_existentes_desincorporacion').reset();
				document.getElementById('boton_ingresar_existentes_desincorporacion').style.display 	= 'block';
				document.getElementById('boton_modificar_existentes_desincorporacion').style.display 	= 'none';
		} 
	}
	ajax.send("idmovimientos_existentes_desincorporacion="+idmovimientos_existentes_desincorporacion+"&codigo_bien="+codigo_bien+"&codigo_catalogo="+codigo_catalogo+"&especificadores="+especificadores+"&motivos="+motivos+"&ejecutar=modificarExistentesDesincorporacion");		
}









function ingresarExistentesIncorporacion(){
	  var idmovimientos		 				= document.getElementById('idmovimiento').value;
	  var idorganizacion 					= document.getElementById('organizacion_existentes_incorporacion').value;
	  var idnivel_organizacional 			= document.getElementById('nivel_organizacional_existente_incorporacion').value;
	  var idorganizacion_destino 			= document.getElementById('organizacion_destino_existente_incorporacion').value;
	  var idnivel_organizacional_destino 	= document.getElementById('nivel_organizacional_destino_existente_incorporacion').value;
	  var codigo_bien 						= document.getElementById('idcodigo_bien_existente_incorporacion').value;
	  var codigo_catalogo 					= document.getElementById('idcodigo_catalogo_existente_incorporacion').value;
	  var especificaciones					= document.getElementById('especificaciones_existente_incorporacion').value
	  var mejoras 							= document.getElementById('mejoras_existente_incorporacion').value;
	  var descripcion 						= document.getElementById('descripcion_existente_incorporacion').value;
	  
	  if(document.getElementById('retorno_existente_incorporacion').checked == true){
		  var retorno_automatico = "si";
		}else{
		var retorno_automatico 	= "no";	
	  }
	  
	  var fecha_retorno 					= document.getElementById('fecha_retorno_existente_incorporacion').value;
	  var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				listaMovimientosExistentesIncorporacion();
				//document.getElementById('formulario_existentes_incorporacion').reset();
				//document.getElementById("tabla_bienes_existente_incorporacion").innerHTML = "";
				listarRegistroFotograficoExistentesIncorporacion();
				
				
				document.getElementById('organizacion_destino_existente_incorporacion').value = 0;
				document.getElementById('nivel_organizacional_destino_existente_incorporacion').value = 0;
				document.getElementById('idcodigo_bien_existente_incorporacion').value = '';
				document.getElementById('idcodigo_catalogo_existente_incorporacion').value = '';
				document.getElementById('codigo_bien_existente_incorporacion').value = '';
				document.getElementById('codigo_catalogo_existente_incorporacion').value = '';
				document.getElementById('especificaciones_existente_incorporacion').value = '';
				document.getElementById('mejoras_existente_incorporacion').value = '';
				document.getElementById('descripcion_existente_incorporacion').value = '';
				document.getElementById('retorno_existente_incorporacion').checked == false;
				
				mostrarBienesActualesExistentesIncorporacion(document.getElementById('nivel_organizacional_existente_incorporacion').value)
				
				document.getElementById('codigo_bien_existente_incorporacion').disabled = true;
				document.getElementById('codigo_catalogo_existente_incorporacion').disabled = true;
				document.getElementById('especificaciones_existente_incorporacion').disabled = true;
				document.getElementById('mejoras_existente_incorporacion').disabled = true;
				document.getElementById('descripcion_existente_incorporacion').disabled = true;
				document.getElementById('retorno_existente_incorporacion').disabled = true;
				document.getElementById('fecha_retorno_existente_incorporacion').disabled = true;
				document.getElementById('boton_ingresar_existentes_incorporacion').disabled = false;
		} 
	}
	ajax.send("idmovimientos="+idmovimientos+"&idorganizacion="+idorganizacion+"&idnivel_organizacional="+idnivel_organizacional+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&codigo_bien="+codigo_bien+"&codigo_catalogo="+codigo_catalogo+"&especificaciones="+especificaciones+"&mejoras="+mejoras+"&descripcion="+descripcion+"&retorno_automatico="+retorno_automatico+"&fecha_retorno="+fecha_retorno+"&ejecutar=ingresarExistentesIncorporacion");			
}




function mostrarBienesActualesExistentesIncorporacion(idnivel_organizacional){
	if(document.getElementById('campo_buscar_bienes')){
		var buscar = document.getElementById('campo_buscar_bienes').value;	
	}else{
		var buscar = "";
	}
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_bienes_existente_incorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("buscar="+buscar+"&idnivel_organizacional="+idnivel_organizacional+"&ejecutar=mostrarBienesActualesExistentesIncorporacion");	
		
}




function cargarBienExistenteIncorporacion(idcodigo_bien, codigo_bien, idcodigo_catalogo, codigo_catalogo, especificaciones, idorganizacion, idnivel_organizacion){
	document.getElementById('organizacion_destino_existente_incorporacion').value = idorganizacion;
	consultarNivelOrganizacional(idorganizacion, 'nivel_organizacional_destino_existente_incorporacion', 'div_nivel_organizacional_destino_existente_incorporacion');
	document.getElementById('idcodigo_bien_existente_incorporacion').value 		= idcodigo_bien;
	document.getElementById('codigo_bien_existente_incorporacion').value 		= codigo_bien;
	document.getElementById('idcodigo_catalogo_existente_incorporacion').value 	= idcodigo_catalogo;
	document.getElementById('codigo_catalogo_existente_incorporacion').value 	= codigo_catalogo;
	document.getElementById('especificaciones_existente_incorporacion').value 	= especificaciones; 
	//alert(idcodigo_bien+" ------ "+codigo_bien+" ------ "+idcodigo_catalogo+" ------ "+codigo_catalogo);
	
	
	document.getElementById('codigo_bien_existente_incorporacion').disabled = false;
	document.getElementById('codigo_catalogo_existente_incorporacion').disabled = false;
	document.getElementById('especificaciones_existente_incorporacion').disabled = false;
	document.getElementById('mejoras_existente_incorporacion').disabled = false;
	document.getElementById('descripcion_existente_incorporacion').disabled = false;
	document.getElementById('retorno_existente_incorporacion').disabled = false;
	document.getElementById('fecha_retorno_existente_incorporacion').disabled = false;
	document.getElementById('boton_ingresar_existentes_incorporacion').disabled = false;
	setTimeout("document.getElementById('nivel_organizacional_destino_existente_incorporacion').value = "+idnivel_organizacion, 1000);
}


function listaMovimientosExistentesIncorporacion(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('lista_existente_incorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listaMovimientosExistentesIncorporacion");	
}



function eliminarMovimientosExistentesIncorporacion(id){
	if(confirm("Seguro desea eliminar el movimiento seleccionado ?")){
		var ajax = nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					listaMovimientosExistentesIncorporacion();
			} 
		}
		ajax.send("idmovimiento="+id+"&ejecutar=eliminarMovimientosExistentesIncorporacion");			
	}		
}



function seleccionarModificarExistentesIncorporacion(codigo_catalogo, idcodigo_catalogo, codigo_bien, idcodigo_bien, idorganizacion, idnivel_organizacional, idorganizacion_destino, idnivel_organizacional_destino, denominacion, mejoras, descripcion, retorno_automatico, especificaciones, fecha_retorno){
 	document.getElementById('codigo_catalogo_existente_incorporacion').value = codigo_catalogo;
 	document.getElementById('idcodigo_catalogo_existente_incorporacion').value = idcodigo_catalogo;
 	document.getElementById('codigo_bien_existente_incorporacion').value = codigo_bien;
 	document.getElementById('idcodigo_bien_existente_incorporacion').value = idcodigo_bien;
 	document.getElementById('organizacion_existentes_incorporacion').value = idorganizacion;
  	document.getElementById('nivel_organizacional_existente_incorporacion').value = idnivel_organizacional;
 	document.getElementById('organizacion_destino_existente_incorporacion').value = idorganizacion_destino;
 	document.getElementById('nivel_organizacional_destino_existente_incorporacion').value = idnivel_organizacional_destino;
 	document.getElementById('mejoras_existente_incorporacion').value = mejoras;
 	document.getElementById('descripcion_existente_incorporacion').value = descripcion;
 	document.getElementById('especificaciones_existente_incorporacion').value = especificaciones;
 
	 if(retorno_automatico == "si"){
		document.getElementById('retorno_existente_incorporacion').checked = true;		 
	 }else{
		document.getElementById('retorno_existente_incorporacion').checked = false;	 
	 }
	
	if(fecha_retorno != "0000-00-00"){
		document.getElementById('fecha_retorno_existente_incorporacion').value = fecha_retorno;		
	}
}



function listarRegistroFotograficoExistentesIncorporacion(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_registro_fotografico_existentes_incorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarRegistroFotograficoExistentesIncorporacion");	
}



function consultarRegistroFotografico(idmovimientos_existentes_incorporacion, estado){
	document.getElementById('listaFotosExistentesIncorporacion').style.display = 'block';
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosExistentesIncorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_existentes_incorporacion="+idmovimientos_existentes_incorporacion+"&ejecutar=consultarRegistroFotografico");	
}



function subirRegistroFotograficoExistentesIncorporacion(){
	var idmovimientos_existentes_incorporacion_fotos 	= document.getElementById('idmovimientos_existentes_incorporacion_fotos').value;
	var nombre_imagen_existentes_incorporacion			= document.getElementById('nombre_imagen_existentes_incorporacion').value;
	var descripcion_foto_existentes_incorporacion		= document.getElementById('descripcion_foto_existentes_incorporacion').value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nombre_imagen_existentes_incorporacion').value = "";
			document.getElementById('descripcion_foto_existentes_incorporacion').value = "";
			document.getElementById('foto_registroFotografico_existentes_incorporacion').value = "";
			mostrarMensajes("exito", "El registro fotografico fue subido con exito");
		} 
	}
	ajax.send("idmovimientos_existentes_incorporacion_fotos="+idmovimientos_existentes_incorporacion_fotos+"&nombre_imagen_existentes_incorporacion="+nombre_imagen_existentes_incorporacion+"&descripcion_foto_existentes_incorporacion="+descripcion_foto_existentes_incorporacion+"&ejecutar=subirRegistroFotograficoExistentesIncorporacion");	
}


function eliminarRegistroFotograficoExistentesIncorporacion(idregistro_fotografico_existentes_incorporacion, idmovimientos_existentes_incorporacion){
	if(confirm("Seguro desea eliminar este registro fotografico?")){
		
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				consultarRegistroFotografico(idmovimientos_existentes_incorporacion);
		} 
	}
	ajax.send("idregistro_fotografico_existentes_incorporacion="+idregistro_fotografico_existentes_incorporacion+"&ejecutar=eliminarRegistroFotograficoExistentesIncorporacion");	
		
	}
}
















function listarRegistroFotograficoExistentesDesincorporacion(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_registro_fotografico_existentes_desincorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarRegistroFotograficoExistentesDesincorporacion");	
}



function consultarRegistroFotograficoExistenteDesincorporacion(idmovimientos_existentes_desincorporacion, estado){
	document.getElementById('listaFotosExistentesDesincorporacion').style.display = 'block';
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosExistentesDesincorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_existentes_desincorporacion="+idmovimientos_existentes_desincorporacion+"&ejecutar=consultarRegistroFotograficoExistenteDesincorporacion");	
}



function subirRegistroFotograficoExistentesDesincorporacion(){
	var idmovimientos_existentes_desincorporacion_fotos 	= document.getElementById('idmovimientos_existentes_desincorporacion_fotos').value;
	var nombre_imagen_existentes_desincorporacion			= document.getElementById('nombre_imagen_existentes_desincorporacion').value;
	var descripcion_foto_existentes_desincorporacion		= document.getElementById('descripcion_foto_existentes_desincorporacion').value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('idmovimientos_existentes_desincorporacion_fotos').value = "";
			document.getElementById('nombre_imagen_existentes_desincorporacion').value = "";
			document.getElementById('descripcion_foto_existentes_desincorporacion').value = "";
			document.getElementById('foto_registroFotografico_existentes_desincorporacion').value = "";
			mostrarMensajes("exito", "El registro fotografico fue subido con exito");
		} 
	}
	ajax.send("idmovimientos_existentes_desincorporacion_fotos="+idmovimientos_existentes_desincorporacion_fotos+"&nombre_imagen_existentes_desincorporacion="+nombre_imagen_existentes_desincorporacion+"&descripcion_foto_existentes_desincorporacion="+descripcion_foto_existentes_desincorporacion+"&ejecutar=subirRegistroFotograficoExistentesDesincorporacion");	
}



















function eliminarRegistroFotograficoExistentesDesincorporacion(idregistro_fotografico_existentes_desincorporacion, idmovimientos_existentes_desincorporacion){
	if(confirm("Seguro desea eliminar este registro fotografico?")){
		
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				consultarRegistroFotograficoExistenteDesincorporacion(idmovimientos_existentes_desincorporacion);
		} 
	}
	ajax.send("idregistro_fotografico_existentes_desincorporacion="+idregistro_fotografico_existentes_desincorporacion+"&ejecutar=eliminarRegistroFotograficoExistentesDesincorporacion");	
		
	}
}





























function listarRegistroFotograficoBienesNuevos(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_registro_fotografico_bienes_nuevos').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarRegistroFotograficoBienesNuevos");	
}



function consultarRegistroFotograficoBienesNuevos(idmovimientos_bienes_nuevos, estado){
	document.getElementById('listaFotosBienesNuevos').style.display = 'block';
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosBienesNuevos').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_bienes_nuevos="+idmovimientos_bienes_nuevos+"&ejecutar=consultarRegistroFotograficoBienesNuevos");	
}



function subirRegistroFotograficoBienesNuevos(){
	var idmovimientos_bienes_nuevos_fotos 	= document.getElementById('idmovimientos_bienes_nuevos_fotos').value;
	var nombre_imagen_bienes_nuevos			= document.getElementById('nombre_imagen_bienes_nuevos').value;
	var descripcion_foto_bienes_nuevos		= document.getElementById('descripcion_foto_bienes_nuevos').value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nombre_imagen_bienes_nuevos').value = "";
			document.getElementById('descripcion_foto_bienes_nuevos').value = "";
			document.getElementById('foto_registroFotografico_bienes_nuevos').value = "";
			mostrarMensajes("exito", "El registro fotografico fue subido con exito");
		} 
	}
	ajax.send("idmovimientos_bienes_nuevos_fotos="+idmovimientos_bienes_nuevos_fotos+"&nombre_imagen_bienes_nuevos="+nombre_imagen_bienes_nuevos+"&descripcion_foto_bienes_nuevos="+descripcion_foto_bienes_nuevos+"&ejecutar=subirRegistroFotograficoBienesNuevos");	
}


function eliminarRegistroFotograficoBienesNuevos(idregistro_fotografico_bienes_nuevos, idmovimientos_bienes_nuevos){
	if(confirm("Seguro desea eliminar este registro fotografico?")){
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				consultarRegistroFotograficoBienesNuevos(idmovimientos_bienes_nuevos);
		} 
	}
	ajax.send("idregistro_fotografico_bienes_nuevos="+idregistro_fotografico_bienes_nuevos+"&ejecutar=eliminarRegistroFotograficoBienesNuevos");	
		
	}
}








function procesarMovimiento(){
	var idmovimiento = document.getElementById('idmovimiento').value;
	if(confirm("Seguro desea Procesar el movimiento realizado?")){
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				bloquearCampos();
				listarBienesExistentesDesincorporacion();
				listarBienesNuevos();
				listaMovimientosExistentesIncorporacion();
				listarRegistroFotograficoExistentesIncorporacion();
				document.getElementById('celda_nro_movimiento').innerHTML = ajax.responseText;
				document.getElementById('boton_procesar').style.display = 'none';
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=procesarMovimiento");	
		
	}
}



function anularMovimiento(){
	var idmovimiento = document.getElementById('idmovimiento').value;
	if(confirm("Seguro desea anular este Movimiento?")){
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('boton_anular').style.display = 'none';
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=procesarMovimiento");	
		
	}
}



function desbloquearCampos(){
	document.getElementById('fecha_movimiento').disabled =  false;
	document.getElementById('afecta').disabled =  false;
	document.getElementById('tipo').disabled =  false;
	document.getElementById('tipo_movimiento').disabled =  false;
	document.getElementById('nro_documento').disabled =  false;
	document.getElementById('fecha_documento').disabled =  false;
	document.getElementById('justificacion_movimiento').disabled =  false;
	document.getElementById('idmovimiento').disabled =  false;
	document.getElementById('nro_documento_bienes_nuevos').disabled =  false;
	document.getElementById('fecha_documento_bienes_nuevos').disabled =  false;
	document.getElementById('proveedores_bienes_nuevos').disabled =  false;
	document.getElementById('nro_factura_bienes_nuevos').disabled =  false;
	document.getElementById('fecha_factura_bienes_nuevos').disabled =  false;
	document.getElementById('organizacion_bienes_nuevos').disabled =  false;
	document.getElementById('nivel_organizacional_bienes_nuevos').disabled =  false;
	document.getElementById('ubicacion_bienes_nuevos').disabled =  false;
	document.getElementById('especificaciones_bienes_nuevos').disabled =  false;
	document.getElementById('marca_bienes_nuevos').disabled =  false;
	document.getElementById('modelos_bienes_nuevos').disabled =  false;
	document.getElementById('tipo_bienes_nuevos').disabled =  false;
	document.getElementById('seriales_bienes_nuevos').disabled =  false;
	document.getElementById('accesorios_bienes_nuevos').disabled =  false;
	document.getElementById('costo_bienes_nuevos').disabled =  false;
	document.getElementById('valor_residual_bienes_nuevos').disabled =  false;
	document.getElementById('vida_util_bienes_nuevos').disabled =  false;
	document.getElementById('mejoras_bienes_nuevos').disabled =  false;
	document.getElementById('costo_ajustado_bienes_nuevos').disabled =  false;
	document.getElementById('depreciacion_anual_bienes_nuevos').disabled =  false;
	document.getElementById('depreciacion_acumulada_bienes_nuevos').disabled =  false;
  	document.getElementById('especificaciones_existente_desincorporacion').disabled =  false;
  	document.getElementById('motivo_existente_desincorporacion').disabled =  false;
 	document.getElementById('organizacion_existentes_incorporacion').disabled =  false;
  	document.getElementById('nivel_organizacional_existente_incorporacion').disabled =  false;
 	document.getElementById('organizacion_destino_existente_incorporacion').disabled =  false;
 	document.getElementById('nivel_organizacional_destino_existente_incorporacion').disabled =  false;
 	document.getElementById('mejoras_existente_incorporacion').disabled =  false;
 	document.getElementById('descripcion_existente_incorporacion').disabled =  false;
 	document.getElementById('especificaciones_existente_incorporacion').disabled =  false;
	
	
	document.getElementById('codigo_bien_bienes_nuevos').disabled = false;
	document.getElementById('codigo_catalogo_bienes_nuevos').disabled = false;
	document.getElementById('codigo_bien_existente_incorporacion').disabled = false;
	document.getElementById('codigo_catalogo_existente_incorporacion').disabled = false;
	document.getElementById('codigo_bien_existente_desincorporacion').disabled = false;
	document.getElementById('codigo_catalogo_existente_desincorporacion').disabled = false;
	
}



function bloquearCampos(){
	document.getElementById('fecha_movimiento').disabled =  true;
	document.getElementById('afecta').disabled =  true;
	document.getElementById('tipo').disabled =  true;
	document.getElementById('tipo_movimiento').disabled =  true;
	document.getElementById('nro_documento').disabled =  true;
	document.getElementById('fecha_documento').disabled =  true;
	document.getElementById('justificacion_movimiento').disabled =  true;
	document.getElementById('idmovimiento').disabled =  true;
	document.getElementById('nro_documento_bienes_nuevos').disabled =  true;
	document.getElementById('fecha_documento_bienes_nuevos').disabled =  true;
	document.getElementById('proveedores_bienes_nuevos').disabled =  true;
	document.getElementById('nro_factura_bienes_nuevos').disabled =  true;
	document.getElementById('fecha_factura_bienes_nuevos').disabled =  true;
	document.getElementById('organizacion_bienes_nuevos').disabled =  true;
	document.getElementById('nivel_organizacional_bienes_nuevos').disabled =  true;
	document.getElementById('ubicacion_bienes_nuevos').disabled =  true;
	document.getElementById('especificaciones_bienes_nuevos').disabled =  true;
	document.getElementById('marca_bienes_nuevos').disabled =  true;
	document.getElementById('modelos_bienes_nuevos').disabled =  true;
	document.getElementById('tipo_bienes_nuevos').disabled =  true;
	document.getElementById('seriales_bienes_nuevos').disabled =  true;
	document.getElementById('accesorios_bienes_nuevos').disabled =  true;
	document.getElementById('costo_bienes_nuevos').disabled =  true;
	document.getElementById('valor_residual_bienes_nuevos').disabled =  true;
	document.getElementById('vida_util_bienes_nuevos').disabled =  true;
	document.getElementById('mejoras_bienes_nuevos').disabled =  true;
	document.getElementById('costo_ajustado_bienes_nuevos').disabled =  true;
	document.getElementById('depreciacion_anual_bienes_nuevos').disabled =  true;
	document.getElementById('depreciacion_acumulada_bienes_nuevos').disabled =  true;
  	document.getElementById('especificaciones_existente_desincorporacion').disabled =  true;
  	document.getElementById('motivo_existente_desincorporacion').disabled =  true;
 	document.getElementById('organizacion_existentes_incorporacion').disabled =  true;
  	document.getElementById('nivel_organizacional_existente_incorporacion').disabled =  true;
 	document.getElementById('organizacion_destino_existente_incorporacion').disabled =  true;
 	document.getElementById('nivel_organizacional_destino_existente_incorporacion').disabled =  true;
 	document.getElementById('mejoras_existente_incorporacion').disabled =  true;
 	document.getElementById('descripcion_existente_incorporacion').disabled =  true;
 	document.getElementById('especificaciones_existente_incorporacion').disabled =  true;
	
	
	document.getElementById('codigo_bien_bienes_nuevos').disabled = true;
	document.getElementById('codigo_catalogo_bienes_nuevos').disabled = true;
	document.getElementById('codigo_bien_existente_incorporacion').disabled = true;
	document.getElementById('codigo_catalogo_existente_incorporacion').disabled = true;
	document.getElementById('codigo_bien_existente_desincorporacion').disabled = true;
	document.getElementById('codigo_catalogo_existente_desincorporacion').disabled = true;
	
}





function consultarDatosBasicos(idmovimiento){
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				//alert(ajax.responseText);
				var partes = ajax.responseText.split("|.|");
					document.getElementById('celda_nro_movimiento').innerHTML = partes[0];
					document.getElementById('fecha_movimiento').value = partes[1];
					document.getElementById('afecta').value= partes[2];
					consultarTipoMovimiento(partes[2]);
					//alert(partes[4]);
					setTimeout("document.getElementById('tipo_movimiento').value= "+partes[4],100);
					document.getElementById('tipo').value= partes[3];
					document.getElementById('nro_documento').value= partes[5];
					document.getElementById('fecha_documento').value= partes[6];
					document.getElementById('justificacion_movimiento').value= partes[7];
				
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=consultarDatosBasicos");	
}





function consultarMovimientos(idmovimiento, estado){
	document.getElementById('idmovimiento').value = idmovimiento;
	
	
	if(document.getElementById('accion_tipo_movimiento').value == "nuevo"){
		document.getElementById('bienes_nuevos').style.display = 'block';
		document.getElementById('existentes_desincorporacion').style.display = 'none';
		document.getElementById('existentes_incorporacion').style.display = 'none';
	}else if(document.getElementById('accion_tipo_movimiento').value == "existente_incorporacion"){
		document.getElementById('bienes_nuevos').style.display = 'none';
		document.getElementById('existentes_desincorporacion').style.display = 'none';
		document.getElementById('existentes_incorporacion').style.display = 'block';
	}else if(document.getElementById('accion_tipo_movimiento').value == "existente_desincorporacion"){
		document.getElementById('bienes_nuevos').style.display = 'none';
		document.getElementById('existentes_desincorporacion').style.display = 'block';
		document.getElementById('existentes_incorporacion').style.display = 'none';
	}
	
	
	document.getElementById('li_registro_fotografico_bienes_nuevos').style.visibility = 'visible';
	document.getElementById('li_bienes_adquisicion_bienes_nuevos').style.visibility = 'visible';
	
	
	consultarDatosBasicos(idmovimiento);
	listarBienesExistentesDesincorporacion();
	listarBienesNuevos();
	listaMovimientosExistentesIncorporacion();
	listarRegistroFotograficoExistentesIncorporacion();
	listarRegistroFotograficoExistentesDesincorporacion();
	listarRegistroFotograficoBienesNuevos()
	if(estado == "elaboracion"){
		desbloquearCampos();
		document.getElementById('boton_procesar').style.display = 'block';
		document.getElementById('boton_siguiente').style.display = 'none';
		document.getElementById('boton_anular').style.display = 'none';	
	}else if(estado == 'anulado'){
		bloquearCampos();
		document.getElementById('boton_procesar').style.display = 'none';
		document.getElementById('boton_siguiente').style.display = 'none';
		document.getElementById('boton_anular').style.display = 'none';	
	}else if(estado == "procesado"){
		bloquearCampos();
		document.getElementById('boton_procesar').style.display = 'none';
		document.getElementById('boton_siguiente').style.display = 'none';
		document.getElementById('boton_anular').style.display = 'block';
	}
}





function consultarTipoDetalle(iddetalle){
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("celda_tipo_detalle").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("iddetalle="+iddetalle+"&ejecutar=consultarTipoDetalle");		
}



function guardarInformacionPrincipalBienesNuevos(){
	var idmovimientos_bienes 			= document.getElementById('idmovimiento').value;
	var nro_documento_bienes_nuevos 	= document.getElementById('nro_documento_bienes_nuevos').value;
	var fecha_documento_bienes_nuevos 	= document.getElementById('fecha_documento_bienes_nuevos').value;	
	var proveedores_bienes_nuevos 		= document.getElementById('proveedores_bienes_nuevos').value;	
	var nro_factura_bienes_nuevos 		= document.getElementById('nro_factura_bienes_nuevos').value;
	var fecha_factura_bienes_nuevos 	= document.getElementById('fecha_factura_bienes_nuevos').value;
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('li_registro_fotografico_bienes_nuevos').style.visibility = 'visible';
			document.getElementById('li_bienes_adquisicion_bienes_nuevos').style.visibility = 'visible';
		} 
	}
	ajax.send("idmovimientos_bienes="+idmovimientos_bienes+"&nro_factura_bienes_nuevos="+nro_factura_bienes_nuevos+"&nro_documento_bienes_nuevos="+nro_documento_bienes_nuevos+"&fecha_documento_bienes_nuevos="+fecha_documento_bienes_nuevos+"&proveedores_bienes_nuevos="+proveedores_bienes_nuevos+"&fecha_factura_bienes_nuevos="+fecha_factura_bienes_nuevos+"&ejecutar=guardarInformacionPrincipalBienesNuevos");	
}












function calcularDepreciacionAnual(){
	var costo = document.getElementById('costo_bienes_nuevos').value;
	var valor_residual = document.getElementById('valor_residual_bienes_nuevos').value;
	var vida_util = document.getElementById('vida_util_bienes_nuevos').value;
	
	depreciacion = (parseFloat(costo) - parseFloat(valor_residual))/ parseFloat(vida_util);
	
	depreciacion = depreciacion.toFixed(2);
	if(depreciacion == "Infinity"){
		document.getElementById('depreciacion_anual_bienes_nuevos').value = 0;
	}else{
		document.getElementById('depreciacion_anual_bienes_nuevos').value = depreciacion;	
	}
	if(parseFloat(document.getElementById('depreciacion_acumulada_bienes_nuevos').value) > parseFloat(document.getElementById('costo_bienes_nuevos').value)){
		document.getElementById('depreciacion_acumulada_bienes_nuevos').style.color = '#FF0000';
	}else{
		document.getElementById('depreciacion_acumulada_bienes_nuevos').style.color = '#000000';	
	}
}


function calcularDepreciacionAcumulada(){
	var depreciacion_anual = document.getElementById('depreciacion_anual_bienes_nuevos').value;
	var fecha_compra = document.getElementById('fecha_documento_bienes_nuevos').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/movimiento_materiales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
				if(parseFloat(ajax.responseText) > parseFloat(document.getElementById('costo_bienes_nuevos').value)){
					document.getElementById('depreciacion_acumulada_bienes_nuevos').style.color = '#FF0000';
				}else{
					document.getElementById('depreciacion_acumulada_bienes_nuevos').style.color = '#000000';	
				}
				document.getElementById('depreciacion_acumulada_bienes_nuevos').value = ajax.responseText;
		} 
	}
	ajax.send("fecha_compra="+fecha_compra+"&depreciacion_anual="+depreciacion_anual+"&ejecutar=calcularDepreciacionAcumulada");
	
}