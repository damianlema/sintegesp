// JavaScript Document

function validarSeleccionado(id){
	if(document.getElementById(id).checked == false){
		document.getElementById('tabla_aseguradora').style.display = 'none';
	}else{
		document.getElementById('tabla_aseguradora').style.display = 'block';
	}	
}

function mostrarInmuebles(){
	//limpiarCampos();
	document.getElementById('imagen_seleccionar_codigo_bien_nuevo').onclick = mostrarImagenBienesNuevos;
	document.getElementById('imagen_seleccionar_codigo_bien_existente_desincorporacion').onclick = mostrarImagenBienesExistentesDesincorporacion;
	document.getElementById('imagen_seleccionar_codigo_bien_existente_incorporacion').onclick = mostrarImagenBienesExistentesIncorporacion;
	
}


function mostrarMuebles(){
	//limpiarCampos();
	document.getElementById('imagen_seleccionar_codigo_bien_nuevo').onclick = mostrarImagenBienesNuevos;
	document.getElementById('imagen_seleccionar_codigo_bien_existente_desincorporacion').onclick = mostrarImagenBienesExistentesDesincorporacion;
	document.getElementById('imagen_seleccionar_codigo_bien_existente_incorporacion').onclick = mostrarImagenBienesExistentesIncorporacion;
	
}


function mostrarImagenBienesNuevos(){
	if(document.getElementById('tipo').value == "mueble"){
		window.open('modulos/bienes/lib/listar_muebles.php?tipo_busqueda=movimiento_individual_nuevo','','resizable=no, scrollbars=yes, width=900, height=600');	
	}else{
		window.open('modulos/bienes/lib/listar_inmuebles.php?tipo_busqueda=movimiento_individual_nuevo','','resizable=no, scrollbars=yes, width=900, height=600');
	}	
}


function mostrarImagenBienesExistentesDesincorporacion(){
	if(document.getElementById('tipo').value == "mueble"){
		window.open('modulos/bienes/lib/listar_muebles.php?tipo_busqueda=movimiento_individual_existente_desincorporacion','','resizable=no, scrollbars=yes, width=900, height=600');	
	}else{
		window.open('modulos/bienes/lib/listar_inmuebles.php?tipo_busqueda=movimiento_individual_existente_desincorporacion','','resizable=no, scrollbars=yes, width=900, height=600');
	}	
}


function mostrarImagenBienesExistentesIncorporacion(){
	if(document.getElementById('tipo').value == "mueble"){
		window.open('modulos/bienes/lib/listar_muebles.php?tipo_busqueda=movimiento_individual_existente_incorporacion','','resizable=no, scrollbars=yes, width=900, height=600');	
	}else{
		window.open('modulos/bienes/lib/listar_inmuebles.php?tipo_busqueda=movimiento_individual_existente_incorporacion','','resizable=no, scrollbars=yes, width=900, height=600');
	}		
}



function desbloquear_destino(){
	document.getElementById('tipo_movimiento_origen').disabled = false;
	document.getElementById('organizacion_existentes_traslados').disabled = false;
	document.getElementById('nivel_organizacional_existente_traslados').disabled = false;	
}



function consultarNivelOrganizacional(idorganizacion, idcampo, idtabla){
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById(String(idtabla)).innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&idcampo="+idcampo+"&ejecutar=consultarNivelOrganizacional");	
}

// *****************************************************************************************************************************
// ***************************** AUTOGENERAR CODIGO ****************************************************************************
// *****************************************************************************************************************************
function generar_codigo(){
	var cantidad = document.getElementById('cantidad_bien').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			partes = ajax.responseText.split("|.|");
			document.getElementById("idgenerar_codigo").style.display		='none';
			document.getElementById('codigo_bien_bienes_nuevos').value = partes[1];
			document.getElementById('idcodigo_bien_bienes_nuevos').value = partes[0];
		} 
	}
	ajax.send("cantidad="+cantidad+"&ejecutar=generar_codigo");			
}

function validarCodigoBien(valor){
	//alert(valor);
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText == "existe"){
					mostrarMensajes2("error", "Disculpe el Codigo del Bien Ingresado ya Existe");
					document.getElementById('codigo_bien_bienes_nuevos').value = '';
					document.getElementById('idcodigo_bien_bienes_nuevos').value = '';
					document.getElementById('boton_ingresar_bienes_nuevos').disabled = true;
					document.getElementById('boton_modificar_bienes_nuevos').disabled = true;
					document.getElementById('idcodigo_bien_bienes_nuevos').focus();
				}else{
					document.getElementById('boton_ingresar_bienes_nuevos').disabled = false;
					document.getElementById('boton_modificar_bienes_nuevos').disabled = false;
					
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=validarCodigoBien");	
}

function mostrarMensajes2(tipo, texto){
	document.getElementById('mensaje_error').style.display = 'block';
	if(tipo == "exito"){
		document.getElementById('mensaje_error').innerHTML="<table align='center' style='margin-top:0px; background-color:#00CCFF; border:#003366 solid 2px; -moz-border-radius: 8px;'><tr><td><img src='imagenes/validar.png'></td><td style='color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:12px'>"+texto+"</td></tr></table>";	
	}else{
		document.getElementById('mensaje_error').innerHTML="<table align='center' style='margin-top:0px; background-color:#FFFF66; border:#FFCC00 solid 2px; -moz-border-radius: 8px;'><tr><td><img src='imagenes/reject.gif'></td><td style='color:#000; font-family:Arial, Helvetica, sans-serif; font-size:12px'>"+texto+"</td></tr></table>";
	}
	setTimeout("document.getElementById('mensaje_error').style.display='none'",4000);
}


function ingresarDatosBasicos(){
	var fecha_movimiento 	= document.getElementById('fecha_movimiento').value;
	var afecta 				= document.getElementById('afecta').value;
	var tipo 				= document.getElementById('tipo').value;
	var tipo_movimiento 	= document.getElementById('tipo_movimiento').value;
	var nro_documento 		= document.getElementById('nro_documento').value;
	var fecha_documento 	= document.getElementById('fecha_documento').value;
	var justificacion 		= document.getElementById('justificacion_movimiento').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			var partes = ajax.responseText.split("|.|");
				if(partes[0] == "exito"){
					if(document.getElementById('accion_tipo_movimiento').value == "nuevo"){
						document.getElementById('bienes_nuevos').style.display = 'block';
						document.getElementById('existentes_desincorporacion').style.display = 'none';
						document.getElementById('existentes_incorporacion').style.display = 'none';
						document.getElementById('traslados').style.display = 'none';
					}else if(document.getElementById('accion_tipo_movimiento').value == "existente_incorporacion"){
						document.getElementById('bienes_nuevos').style.display = 'none';
						document.getElementById('existentes_desincorporacion').style.display = 'none';
						document.getElementById('existentes_incorporacion').style.display = 'block';
						document.getElementById('traslados').style.display = 'none';
					}else if(document.getElementById('accion_tipo_movimiento').value == "existente_desincorporacion"){
						document.getElementById('bienes_nuevos').style.display = 'none';
						document.getElementById('existentes_desincorporacion').style.display = 'block';
						document.getElementById('existentes_incorporacion').style.display = 'none';
						document.getElementById('traslados').style.display = 'none';
					}else if(document.getElementById('accion_tipo_movimiento').value == "ambos"){
						document.getElementById('bienes_nuevos').style.display = 'none';
						document.getElementById('existentes_desincorporacion').style.display = 'none';
						document.getElementById('existentes_incorporacion').style.display = 'none';
						document.getElementById('traslados').style.display = 'block';
					}
					
						
						document.getElementById('afecta').disabled = true;
						document.getElementById('tipo').disabled = true;
						document.getElementById('tipo_movimiento').disabled = true;
						
					
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_modificar').style.display = 'block';
					document.getElementById('boton_procesar').style.display = 'block';
					document.getElementById('idmovimiento').value = partes[1];
				}else{
					mostrarMensajes("error", "Disculpe los datos no pudieron ser registrados con exito, por favor vuelva a intentarlo")	;
				}
				//listarRegistroFotograficoBienesNuevos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("fecha_movimiento="+fecha_movimiento+"&afecta="+afecta+"&tipo="+tipo+"&tipo_movimiento="+tipo_movimiento+"&nro_documento="+nro_documento+"&fecha_documento="+fecha_documento+"&justificacion="+justificacion+"&ejecutar=ingresarDatosBasicos");	
}

function procesarModificar(){
	var fecha_movimiento 	= document.getElementById('fecha_movimiento').value;
	var nro_documento 		= document.getElementById('nro_documento').value;
	var fecha_documento 	= document.getElementById('fecha_documento').value;
	var justificacion 		= document.getElementById('justificacion_movimiento').value;
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				
				document.getElementById('fecha_movimiento').disabled = true;
				document.getElementById('afecta').disabled = true;
				document.getElementById('tipo').disabled = true;
				document.getElementById('tipo_movimiento').disabled = true;
				
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('boton_modificar').style.display = 'block';
				document.getElementById('boton_procesar').style.display = 'block';
				//document.getElementById('idmovimiento').value = partes[1];
				
				listarRegistroFotograficoBienesNuevos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("fecha_movimiento="+fecha_movimiento+"&nro_documento="+nro_documento+"&fecha_documento="+fecha_documento+"&justificacion="+justificacion+"&idmovimientos_bienes="+idmovimiento+"&ejecutar=procesarModificar");	
}

function consultarTipoMovimiento(tipo){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('celda_tipo_movimiento').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("tipo="+tipo+"&ejecutar=consultarTipoMovimiento");		
}

//******************************************************************************************************************************************************
//  INICIO BIENES NUEVOS INCORPORACION
//******************************************************************************************************************************************************

function guardarInformacionPrincipalBienesNuevos(){
	var idmovimientos_bienes 			= document.getElementById('idmovimiento').value;
	var nro_documento_bienes_nuevos 	= document.getElementById('nro_documento_bienes_nuevos').value;
	var fecha_documento_bienes_nuevos 	= document.getElementById('fecha_documento_bienes_nuevos').value;	
	var proveedores_bienes_nuevos 		= document.getElementById('proveedores_bienes_nuevos').value;	
	var nro_factura_bienes_nuevos 		= document.getElementById('nro_factura_bienes_nuevos').value;
	var fecha_factura_bienes_nuevos 	= document.getElementById('fecha_factura_bienes_nuevos').value;
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
	//var codigo_bien				= document.getElementById('codigo_bien_bienes_nuevos').value;
	//var codigo_bien_automatico 	= document.getElementById('idcodigo_bien_bienes_nuevos').value;
	var cantidad 				= document.getElementById('cantidad_bien').value;
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
	var aseguradora = document.getElementById('aseguradora').value;
	var nro_poliza = document.getElementById('nro_poliza').value;
	var fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
	var monto_poliza = document.getElementById('monto_poliza').value;
	var monto_asegurado = document.getElementById('monto_asegurado').value;
	
 	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					listarBienesNuevos();
					limpiarBienesNuevos();
					listarRegistroFotograficoBienesNuevos();
				}else{
					alert(ajax.responseText);
					mostrarMensajes("error", "Disculpe los datos no pudieron ser ingresados por favor intente de nuevo mas tarde "+ajax.responseText);
				}
				//document.getElementById('formulario_bienes_nuevos').reset();
				
		} 
	}
	//nro_documento="+nro_documento+"&fecha_documento="+fecha_documento+"&idbenficiario="+idbenficiario+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&
	ajax.send("idmovimiento_bienes="+idmovimiento+"&idorganizacion="+idorganizacion+"&nivel_organizacional="+nivel_organizacional+"&codigo_catalogo="+codigo_catalogo+"&idubicacion="+idubicacion+"&especificaciones="+especificaciones+"&marca="+marca+"&modelo="+modelo+"&tipo="+tipo+"&serial="+serial+"&accesorios="+accesorios+"&costo="+costo+"&valor_residual="+valor_residual+"&vida_util="+vida_util+"&mejoras="+mejoras+"&costo_ajustado="+costo_ajustado+"&depreciacion_anual="+depreciacion_anual+"&asegurado="+asegurado+"&depreciacion_acumulada="+depreciacion_acumulada+"&aseguradora="+aseguradora+"&nro_poliza="+nro_poliza+"&fecha_vencimiento="+fecha_vencimiento+"&monto_poliza="+monto_poliza+"&monto_asegurado="+monto_asegurado+"&cantidad="+cantidad+"&ejecutar=ingresarBienesNuevos");		
}



function limpiarBienesNuevos(){
		//document.getElementById('organizacion_bienes_nuevos').value = '0';
		//document.getElementById('nivel_organizacional_bienes_nuevos').value = '0';
		document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '';
		document.getElementById('codigo_catalogo_bienes_nuevos').value = '';
		//document.getElementById('codigo_bien_bienes_nuevos').value = '';
		//document.getElementById('idcodigo_bien_bienes_nuevos').value = '';
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
		document.getElementById('aseguradora').value = '';
		document.getElementById('nro_poliza').value = '';
		document.getElementById('fecha_vencimiento').value = '';
		document.getElementById('monto_poliza').value = '';
		document.getElementById('monto_asegurado').value = '';
		//document.getElementById("idgenerar_codigo").style.display		='block';
}



function listarBienesNuevos(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
	if(confirm("Seguro desea eliminar el bien seleccionado?")){
		var ajax			= nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					listarBienesNuevos();
					limpiarBienesNuevos();
					listarRegistroFotograficoBienesNuevos();
			} 
		}
		ajax.send("idmovimiento="+id+"&ejecutar=eliminarBienesNuevos");			
		}	
}


function seleccionarModificarBienesNuevos(nro_documento, fecha_documento, idbenficiario, nro_factura, fecha_factura, idorganizacion, nivel_organizacional, codigo_catalogo, idubicacion, especificaciones, marca, modelo, tipo, serial, accesorios, costo, valor_residual, vida_util, mejoras, costo_ajustado, depreciacion_anual, depreciacion_acumulada, asegurado, idmovimientos_bienes_nuevos, idcodigo_catalogo, idcodigo_bien, aseguradora, nro_poliza, fecha_vencimiento, monto_poliza, monto_asegurado){
	
	document.getElementById('idmovimientos_bienes_nuevos').value = idmovimientos_bienes_nuevos;
	document.getElementById('nro_documento_bienes_nuevos').value 			= nro_documento;
	document.getElementById('fecha_documento_bienes_nuevos').value 			= fecha_documento;
	document.getElementById('proveedores_bienes_nuevos').value 				= idbenficiario;
	document.getElementById('nro_factura_bienes_nuevos').value 				= nro_factura;
	document.getElementById('fecha_factura_bienes_nuevos').value 			= fecha_factura;
	document.getElementById('organizacion_bienes_nuevos').value 			= idorganizacion;

	document.getElementById('idcodigo_catalogo_bienes_nuevos').value 		= idcodigo_catalogo;
	//document.getElementById('idcodigo_bien_bienes_nuevos').value 			= idcodigo_bien;
	document.getElementById('codigo_catalogo_bienes_nuevos').value 			= codigo_catalogo;
	//document.getElementById('codigo_bien_bienes_nuevos').value 				= idcodigo_bien;
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
	document.getElementById('aseguradora').value = aseguradora;
	document.getElementById('nro_poliza').value = nro_poliza;
	document.getElementById('fecha_vencimiento').value = fecha_vencimiento;
	document.getElementById('monto_poliza').value = monto_poliza;
	document.getElementById('monto_asegurado').value = monto_asegurado;
	
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
	//var codigo_bien				= document.getElementById('idcodigo_bien_bienes_nuevos').value;
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
	var aseguradora = document.getElementById('aseguradora').value;
	var nro_poliza = document.getElementById('nro_poliza').value;
	var fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
	var monto_poliza = document.getElementById('monto_poliza').value;
	var monto_asegurado = document.getElementById('monto_asegurado').value;
	
 	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				listarBienesNuevos();
				limpiarBienesNuevos();
				listarRegistroFotograficoBienesNuevos();
				//document.getElementById('formulario_bienes_nuevos').reset();
				
		} 
	}
	//nro_documento="+nro_documento+"&fecha_documento="+fecha_documento+"&idbenficiario="+idbenficiario+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&
	
	ajax.send("idmovimientos_bienes_nuevos="+idmovimientos_bienes_nuevos+"&idmovimiento_bienes="+idmovimiento+"&idorganizacion="+idorganizacion+"&nivel_organizacional="+nivel_organizacional+"&codigo_catalogo="+codigo_catalogo+"&codigo_bien="+codigo_bien+"&idubicacion="+idubicacion+"&especificaciones="+especificaciones+"&marca="+marca+"&modelo="+modelo+"&tipo="+tipo+"&serial="+serial+"&accesorios="+accesorios+"&costo="+costo+"&valor_residual="+valor_residual+"&vida_util="+vida_util+"&mejoras="+mejoras+"&costo_ajustado="+costo_ajustado+"&depreciacion_anual="+depreciacion_anual+"&asegurado="+asegurado+"&depreciacion_acumulada="+depreciacion_acumulada+"&aseguradora="+aseguradora+"&nro_poliza="+nro_poliza+"&fecha_vencimiento="+fecha_vencimiento+"&monto_poliza="+monto_poliza+"&monto_asegurado="+monto_asegurado+"&ejecutar=modificarBienesNuevos");	
}

function listarRegistroFotograficoBienesNuevos(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nombre_imagen_bienes_nuevos').value = "";
			document.getElementById('descripcion_foto_bienes_nuevos').value = "";
			document.getElementById('foto_registroFotografico_bienes_nuevos').value = "";
			document.getElementById('mostrarImagen').innerHTML = "";
			mostrarMensajes("exito", "El registro fotografico fue subido con exito");
		} 
	}
	ajax.send("idmovimientos_bienes_nuevos_fotos="+idmovimientos_bienes_nuevos_fotos+"&nombre_imagen_bienes_nuevos="+nombre_imagen_bienes_nuevos+"&descripcion_foto_bienes_nuevos="+descripcion_foto_bienes_nuevos+"&ejecutar=subirRegistroFotograficoBienesNuevos");	
}


function eliminarRegistroFotograficoBienesNuevos(idregistro_fotografico_bienes_nuevos, idmovimientos_bienes_nuevos){
	if(confirm("Seguro desea eliminar este registro fotografico?")){
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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


//******************************************************************************************************************************************************
//  FIN BIENES NUEVOS INCORPORACION
//******************************************************************************************************************************************************




//******************************************************************************************************************************************************
//  INICIO BIENES EXISTENTES INCORPORACION
//******************************************************************************************************************************************************


function ingresarExistentesIncorporacion(){
	  var idmovimientos		 				= document.getElementById('idmovimiento').value;
	  var idorganizacion 					= document.getElementById('organizacion_existentes_incorporacion').value;
	  var idnivel_organizacional 			= document.getElementById('nivel_organizacional_existente_incorporacion').value;
	  //var idorganizacion_destino 			= document.getElementById('organizacion_destino_existente_incorporacion').value;
	  //var idnivel_organizacional_destino 	= document.getElementById('nivel_organizacional_destino_existente_incorporacion').value;
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				listaMovimientosExistentesIncorporacion();
				//document.getElementById('formulario_existentes_incorporacion').reset();
				//document.getElementById("tabla_bienes_existente_incorporacion").innerHTML = "";
				listarRegistroFotograficoExistentesIncorporacion();
				
				
				//document.getElementById('organizacion_destino_existente_incorporacion').value = 0;
				//document.getElementById('nivel_organizacional_destino_existente_incorporacion').value = 0;
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
	ajax.send("idmovimientos="+idmovimientos+"&idorganizacion="+idorganizacion+"&idnivel_organizacional="+idnivel_organizacional+"&codigo_bien="+codigo_bien+"&codigo_catalogo="+codigo_catalogo+"&especificaciones="+especificaciones+"&mejoras="+mejoras+"&descripcion="+descripcion+"&retorno_automatico="+retorno_automatico+"&fecha_retorno="+fecha_retorno+"&ejecutar=ingresarExistentesIncorporacion");			
}




function mostrarBienesActualesExistentesIncorporacion(idnivel_organizacional){
	idmovimiento = document.getElementById('tipo_movimiento').value;
	//alert(idmovimiento);
	if(document.getElementById('campo_buscar_bienes')){
		var buscar = document.getElementById('campo_buscar_bienes').value;	
	}else{
		var buscar = "";
	}
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_bienes_existente_incorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("buscar="+buscar+"&idnivel_organizacional="+idnivel_organizacional+"&idmovimiento="+idmovimiento+"&ejecutar=mostrarBienesActualesExistentesIncorporacion");	
		
}




function cargarBienExistenteIncorporacion(idcodigo_bien, codigo_bien, idcodigo_catalogo, codigo_catalogo, especificaciones, idorganizacion, idnivel_organizacion){
	//document.getElementById('organizacion_destino_existente_incorporacion').value = idorganizacion;
	//consultarNivelOrganizacional(idorganizacion, 'nivel_organizacional_destino_existente_incorporacion', 'div_nivel_organizacional_destino_existente_incorporacion');
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
		ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
 	//document.getElementById('organizacion_destino_existente_incorporacion').value = idorganizacion_destino;
 	//document.getElementById('nivel_organizacional_destino_existente_incorporacion').value = idnivel_organizacional_destino;
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosExistentesIncorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_existentes_incorporacion="+idmovimientos_existentes_incorporacion+"&ejecutar=consultarRegistroFotografico");	
}

function consultarRegistroFotograficoExistentesIncorporacion(idmovimientos_existentes_incorporacion, estado){
	document.getElementById('tabla_registro_fotografico_existentes_incorporacion1').style.display = 'block';
	
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosExistentesIncorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_existentes_incorporacion="+idmovimientos_existentes_incorporacion+"&ejecutar=consultarRegistroFotograficoExistentesIncorporacion");	
}

function subirRegistroFotograficoExistentesIncorporacion(){
	var idmovimientos_existentes_incorporacion_fotos 	= document.getElementById('idmovimientos_existentes_incorporacion_fotos').value;
	var nombre_imagen_existentes_incorporacion			= document.getElementById('nombre_imagen_existentes_incorporacion').value;
	var descripcion_foto_existentes_incorporacion		= document.getElementById('descripcion_foto_existentes_incorporacion').value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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

//******************************************************************************************************************************************************
//  FIN BIENES EXISTENTES INCORPORACION
//******************************************************************************************************************************************************


//******************************************************************************************************************************************************
//  INICIO BIENES EXISTENTES DESINCORPORACION
//******************************************************************************************************************************************************


function ingresarExistentesDesIncorporacion(){
	  var idmovimientos		 				= document.getElementById('idmovimiento').value;
	  var idorganizacion 					= document.getElementById('organizacion_existentes_desincorporacion').value;
	  var idnivel_organizacional 			= document.getElementById('nivel_organizacional_existente_desincorporacion').value;
	  var idorganizacion_destino 			= document.getElementById('organizacion_existentes_desincorporacion_destino').value;
	  var idnivel_organizacional_destino 	= document.getElementById('nivel_organizacional_existente_desincorporacion_destino').value;
	  var codigo_bien 						= document.getElementById('idcodigo_bien_existente_desincorporacion').value;
	  var codigo_catalogo 					= document.getElementById('idcodigo_catalogo_existente_desincorporacion').value;
	  var especificaciones					= document.getElementById('especificaciones_existente_desincorporacion').value
	  var descripcion 						= document.getElementById('descripcion_existente_desincorporacion').value;
	  
	  
	  var ajax					= nuevoAjax();
	  ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	  ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				listaMovimientosExistentesDesIncorporacion();
				//document.getElementById('formulario_existentes_incorporacion').reset();
				//document.getElementById("tabla_bienes_existente_incorporacion").innerHTML = "";
				listarRegistroFotograficoExistentesDesIncorporacion();
				
				
				//document.getElementById('organizacion_destino_existente_incorporacion').value = 0;
				//document.getElementById('nivel_organizacional_destino_existente_incorporacion').value = 0;
				document.getElementById('idcodigo_bien_existente_desincorporacion').value = '';
				document.getElementById('idcodigo_catalogo_existente_desincorporacion').value = '';
				document.getElementById('codigo_bien_existente_desincorporacion').value = '';
				document.getElementById('codigo_catalogo_existente_desincorporacion').value = '';
				document.getElementById('especificaciones_existente_desincorporacion').value = '';
				document.getElementById('descripcion_existente_desincorporacion').value = '';
				
				mostrarBienesActualesExistentesDesIncorporacion(document.getElementById('nivel_organizacional_existente_desincorporacion').value)
				
				document.getElementById('codigo_bien_existente_desincorporacion').disabled = true;
				document.getElementById('codigo_catalogo_existente_desincorporacion').disabled = true;
				document.getElementById('especificaciones_existente_desincorporacion').disabled = true;
				//document.getElementById('mejoras_existente_desincorporacion').disabled = true;
				document.getElementById('descripcion_existente_desincorporacion').disabled = true;
				//document.getElementById('retorno_existente_desincorporacion').disabled = true;
				//document.getElementById('fecha_retorno_existente_desincorporacion').disabled = true;
				document.getElementById('boton_ingresar_existentes_desincorporacion').disabled = false;
		} 
	}
	ajax.send("idmovimientos="+idmovimientos+"&idorganizacion="+idorganizacion+"&idnivel_organizacional="+idnivel_organizacional+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&codigo_bien="+codigo_bien+"&codigo_catalogo="+codigo_catalogo+"&especificaciones="+especificaciones+"&descripcion="+descripcion+"&ejecutar=ingresarExistentesDesIncorporacion");			
}




function mostrarBienesActualesExistentesDesIncorporacion(idnivel_organizacional){
	var idmovimiento = document.getElementById('tipo_movimiento').value;
    var idmovimiento_desincorporacion = document.getElementById('idmovimiento').value;
	//alert(idmovimiento);
	if(document.getElementById('campo_buscar_bienes')){
		var buscar = document.getElementById('campo_buscar_bienes').value;	
	}else{
		var buscar = "";
	}
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_bienes_existente_desincorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("buscar="+buscar+"&idnivel_organizacional="+idnivel_organizacional+"&idmovimiento="+idmovimiento+"&idmovimiento_desincorporacion="+idmovimiento_desincorporacion+"&ejecutar=mostrarBienesActualesExistentesDesIncorporacion");
		
}





function cargarBienExistenteDesIncorporacion(idcodigo_bien, codigo_bien, idcodigo_catalogo, codigo_catalogo, especificaciones, idorganizacion, idnivel_organizacion){
	var idmovimiento = document.getElementById('tipo_movimiento').value;
	//alert(especificaciones);
    document.getElementById('boton_ingresar_existentes_desincorporacion').disabled = false;
    document.getElementById('codigo_catalogo_existente_desincorporacion').value = codigo_catalogo;
    document.getElementById('idcodigo_catalogo_existente_desincorporacion').value = idcodigo_catalogo;
    document.getElementById('codigo_bien_existente_desincorporacion').value = codigo_bien;
    document.getElementById('idcodigo_bien_existente_desincorporacion').value = idcodigo_bien;
    document.getElementById('especificaciones_existente_desincorporacion').value = especificaciones;
    document.getElementById('descripcion_existente_desincorporacion').disabled = false;
	/*if(document.getElementById('campo_buscar_bienes')){
		var buscar = document.getElementById('campo_buscar_bienes').value;	
	}else{
		var buscar = "";
	}
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			alert(ajax.responseText);
				document.getElementById('datos_bienes_existente_desincorporacion').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("buscar="+buscar+"&idnivel_organizacional="+idnivel_organizacion+"&idmovimiento="+idmovimiento+"&ejecutar=mostrarBienesActualesExistentesDesIncorporacion");
	*/
}






function listaMovimientosExistentesDesIncorporacion(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('lista_existente_desincorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listaMovimientosExistentesDesIncorporacion");	
}



function eliminarMovimientosExistentesDesIncorporacion(id){
	if(confirm("Seguro desea eliminar el movimiento seleccionado ?")){
		var ajax = nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					listaMovimientosExistentesDesIncorporacion();
			} 
		}
		ajax.send("idmovimiento="+id+"&ejecutar=eliminarMovimientosExistentesDesIncorporacion");			
	}		
}



function seleccionarModificarExistentesDesIncorporacion(codigo_catalogo, idcodigo_catalogo, codigo_bien, idcodigo_bien, idorganizacion, idnivel_organizacional, idorganizacion_destino, idnivel_organizacional_destino, denominacion, descripcion, especificaciones){
 	document.getElementById('codigo_catalogo_existente_desincorporacion').value = codigo_catalogo;
 	document.getElementById('idcodigo_catalogo_existente_desincorporacion').value = idcodigo_catalogo;
 	document.getElementById('codigo_bien_existente_desincorporacion').value = codigo_bien;
 	document.getElementById('idcodigo_bien_existente_desincorporacion').value = idcodigo_bien;
 	document.getElementById('organizacion_existentes_desincorporacion').value = idorganizacion;
  	document.getElementById('nivel_organizacional_existente_desincorporacion').value = idnivel_organizacional;
 	document.getElementById('descripcion_existente_desincorporacion').value = descripcion;
 	document.getElementById('especificaciones_existente_desincorporacion').value = especificaciones;
 
	 
}



function listarRegistroFotograficoExistentesDesIncorporacion(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_registro_fotografico_existentes_desincorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarRegistroFotograficoExistentesDesIncorporacion");	
}




function consultarRegistroFotograficoExistentesDesIncorporacion(idmovimientos_existentes_desincorporacion, estado){
	document.getElementById('tabla_registro_fotografico_existentes_desincorporacion1').style.display = 'block';
	document.getElementById('listaFotosExistentesDesIncorporacion').style.display = 'block';
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosExistentesDesIncorporacion').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_existentes_desincorporacion="+idmovimientos_existentes_desincorporacion+"&ejecutar=consultarRegistroFotograficoExistentesDesIncorporacion");	
}

function subirRegistroFotograficoExistentesDesIncorporacion(){
	var idmovimientos_existentes_desincorporacion_fotos 	= document.getElementById('idmovimientos_existentes_desincorporacion_fotos').value;
	var nombre_imagen_existentes_desincorporacion			= document.getElementById('nombre_imagen_existentes_desincorporacion').value;
	var descripcion_foto_existentes_desincorporacion		= document.getElementById('descripcion_foto_existentes_desincorporacion').value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nombre_imagen_existentes_desincorporacion').value = "";
			document.getElementById('descripcion_foto_existentes_desincorporacion').value = "";
			document.getElementById('foto_registroFotografico_existentes_desincorporacion').value = "";
			mostrarMensajes("exito", "El registro fotografico fue subido con exito");
		} 
	}
	ajax.send("idmovimientos_existentes_desincorporacion_fotos="+idmovimientos_existentes_desincorporacion_fotos+"&nombre_imagen_existentes_desincorporacion="+nombre_imagen_existentes_desincorporacion+"&descripcion_foto_existentes_desincorporacion="+descripcion_foto_existentes_desincorporacion+"&ejecutar=subirRegistroFotograficoExistentesDesIncorporacion");	
}


function eliminarRegistroFotograficoExistentesDesIncorporacion(idregistro_fotografico_existentes_desincorporacion, idmovimientos_existentes_desincorporacion){
	if(confirm("Seguro desea eliminar este registro fotografico?")){
		
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				consultarRegistroFotograficoExistentesDesIncorporacion(idmovimientos_existentes_desincorporacion);
		} 
	}
	ajax.send("idregistro_fotografico_existentes_desincorporacion="+idregistro_fotografico_existentes_desincorporacion+"&ejecutar=eliminarRegistroFotograficoExistentesDesIncorporacion");	
		
	}
}

//******************************************************************************************************************************************************
//  FIN BIENES EXISTENTES DESINCORPORACION
//******************************************************************************************************************************************************




//******************************************************************************************************************************************************
// TRASLADOS
//******************************************************************************************************************************************************



function mostrarBienesActualesExistentesTraslado(idnivel_organizacional){
	var idmovimiento = document.getElementById('idmovimiento').value;
	var idtipo_movimiento = document.getElementById('tipo_movimiento_origen').value;
	//alert(idmovimiento);
	if(document.getElementById('campo_buscar_bienes')){
		var buscar = document.getElementById('campo_buscar_bienes').value;	
	}else{
		var buscar = "";
	}
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				document.getElementById('tabla_bienes_traslados_origen').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("buscar="+buscar+"&idnivel_organizacional="+idnivel_organizacional+"&idmovimiento="+idmovimiento+"&idtipo_movimiento="+idtipo_movimiento+"&ejecutar=mostrarBienesActualesExistentesTraslado");	
		
}





function mostrarBienesDestinoTraslado(){
	var idmovimiento = document.getElementById('idmovimiento').value;
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				document.getElementById('tabla_bienes_traslados_destino').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("&idmovimiento="+idmovimiento+"&ejecutar=mostrarBienesDestinoTraslado");	
		
}







function cargarBienExistenteTrasladoDestino(idcodigo_bien, codigo_bien, idcodigo_catalogo, codigo_catalogo, denominacion, especificaciones, idorganizacion, idnivel_organizacion){
	var idmovimiento 					= document.getElementById('idmovimiento').value;
	var descripcion 					= document.getElementById('justificacion_movimiento').value;
	
	var idtipo_movimiento_destino 		= document.getElementById("tipo_movimiento_destino").value;
	var idorganizacion_destino 			= document.getElementById("organizacion_traslados_destino").value;
	var idnivel_organizacional_destino 	= document.getElementById("nivel_organizacional_traslados_destino").value;
	
	var idtipo_movimiento_origen 		= document.getElementById("tipo_movimiento_origen").value;
	var idorganizacion_origen 			= document.getElementById("organizacion_existentes_traslados").value;
	var idnivel_organizacional_origen 	= document.getElementById("nivel_organizacional_existente_traslados").value;
	
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			mostrarBienesActualesExistentesTraslado(idnivel_organizacional_origen);
			mostrarBienesDestinoTraslado();
			
			//	document.getElementById('tabla_bienes_traslados_origen').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idcodigo_bien="+idcodigo_bien+"&idtipo_movimiento_destino="+idtipo_movimiento_destino+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&idtipo_movimiento_origen="+idtipo_movimiento_origen+"&idorganizacion_origen="+idtipo_movimiento_origen+"&idnivel_organizacional_origen="+idnivel_organizacional_origen+"&idmovimiento="+idmovimiento+"&idcodigo_catalogo="+idcodigo_catalogo+"&denominacion="+denominacion+"&descripcion="+descripcion+"&ejecutar=cargarBienesSeleccionadosTraslado");
}


function EliminarBienExistenteTrasladoDestino(id){
	var idnivel_organizacional_origen 	= document.getElementById("nivel_organizacional_existente_traslados").value;
	if(confirm("Seguro desea eliminar el movimiento seleccionado ?")){
		var ajax = nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				mostrarBienesActualesExistentesTraslado(idnivel_organizacional_origen);
				mostrarBienesDestinoTraslado();
			} 
		}
		ajax.send("idmovimiento="+id+"&ejecutar=EliminarBienExistenteTrasladoDestino");			
	}		
}



function listarRegistroFotograficoTraslado(){
	var idmovimiento 	= document.getElementById("idmovimiento").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('tabla_registro_fotografico_traslados').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=listarRegistroFotograficoTraslado");	
}




function consultarRegistroFotograficoTraslado(idmovimientos_existentes_incorporacion, estado){
	document.getElementById('tabla_registro_fotografico_traslados').style.display = 'block';
	document.getElementById('listaFotosExistentesTraslados').style.display = 'block';
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('listaFotosExistentesTraslados').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("estado="+estado+"&idmovimientos_existentes_incorporacion="+idmovimientos_existentes_incorporacion+"&ejecutar=consultarRegistroFotograficoTraslado");	
}

function subirRegistroFotograficoTraslado(){
	var idmovimientos_existentes_traslado_fotos 	= document.getElementById('idmovimientos_existentes_traslado_fotos').value;
	var nombre_imagen_existentes_traslado			= document.getElementById('nombre_imagen_existentes_traslado').value;
	var descripcion_foto_existentes_traslado		= document.getElementById('descripcion_foto_existentes_traslado').value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nombre_imagen_existentes_traslado').value = "";
			document.getElementById('descripcion_foto_existentes_traslado').value = "";
			document.getElementById('foto_registroFotografico_existentes_traslado').value = "";
			mostrarMensajes("exito", "El registro fotografico fue subido con exito");
		} 
	}
	ajax.send("idmovimientos_existentes_traslado_fotos="+idmovimientos_existentes_traslado_fotos+"&nombre_imagen_existentes_traslado="+nombre_imagen_existentes_traslado+"&descripcion_foto_existentes_traslado="+descripcion_foto_existentes_traslado+"&ejecutar=subirRegistroFotograficoTraslado");	
}


function eliminarRegistroFotograficoTraslado(idregistro_fotografico_existentes_incorporacion, idmovimientos_existentes_incorporacion){
	if(confirm("Seguro desea eliminar este registro fotografico?")){
		
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				consultarRegistroFotograficoTraslado(idmovimientos_existentes_incorporacion);
		} 
	}
	ajax.send("idregistro_fotografico_existentes_incorporacion="+idregistro_fotografico_existentes_incorporacion+"&ejecutar=eliminarRegistroFotograficoTraslado");	
		
	}
}


//**************************************************************************************************************************************************************
//**************************************************************************************************************************************************************





function procesarMovimiento(){
	var idmovimiento = document.getElementById('idmovimiento').value;
	var accion_tipo_movimiento = document.getElementById('accion_tipo_movimiento').value;
	
	if(confirm("Seguro desea Procesar el movimiento realizado?")){
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);	
				if (ajax.responseText == "error_existe_codigo"){
					mostrarMensajes2("error", "Un Codigo de Bien que intenta Ingresar ya Existe para otro bien");
					listarBienesNuevos();
					listaMovimientosExistentesIncorporacion();
					listaMovimientosExistentesDesIncorporacion();
					
					listarRegistroFotograficoExistentesIncorporacion();
					listarRegistroFotograficoExistentesDesIncorporacion();
					listarRegistroFotograficoBienesNuevos();
				}else{
					bloquearCampos();
					listarBienesNuevos();
					listaMovimientosExistentesIncorporacion();
					listaMovimientosExistentesDesIncorporacion();
					
					listarRegistroFotograficoExistentesIncorporacion();
					listarRegistroFotograficoExistentesDesIncorporacion();
					listarRegistroFotograficoBienesNuevos();
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('celdaImprimir').style.display = 'block';
					document.getElementById('celda_nro_movimiento').innerHTML = ajax.responseText;
				}
				
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&accion_tipo_movimiento="+accion_tipo_movimiento+"&ejecutar=procesarMovimiento");	
		
	}
}



function anularMovimiento(){
	var idmovimiento = document.getElementById('idmovimiento').value;
	if(confirm("Seguro desea anular este Movimiento?")){
		var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
  	document.getElementById('descripcion_existente_desincorporacion').disabled =  false;
 	document.getElementById('organizacion_existentes_incorporacion').disabled =  false;
  	document.getElementById('nivel_organizacional_existente_incorporacion').disabled =  false;
// 	document.getElementById('organizacion_destino_existente_incorporacion').disabled =  false;
// 	document.getElementById('nivel_organizacional_destino_existente_incorporacion').disabled =  false;
 	document.getElementById('mejoras_existente_incorporacion').disabled =  false;
 	document.getElementById('descripcion_existente_incorporacion').disabled =  false;
 	document.getElementById('especificaciones_existente_incorporacion').disabled =  false;
	
	
	//document.getElementById('codigo_bien_bienes_nuevos').disabled = false;
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
  	document.getElementById('descripcion_existente_desincorporacion').disabled =  true;
 	document.getElementById('organizacion_existentes_incorporacion').disabled =  true;
  	document.getElementById('nivel_organizacional_existente_incorporacion').disabled =  true;
 	//document.getElementById('organizacion_destino_existente_incorporacion').disabled =  true;
 	//document.getElementById('nivel_organizacional_destino_existente_incorporacion').disabled =  true;
 	document.getElementById('mejoras_existente_incorporacion').disabled =  true;
 	document.getElementById('descripcion_existente_incorporacion').disabled =  true;
 	document.getElementById('especificaciones_existente_incorporacion').disabled =  true;
	
	
	//document.getElementById('codigo_bien_bienes_nuevos').disabled = true;
	document.getElementById('codigo_catalogo_bienes_nuevos').disabled = true;
	document.getElementById('codigo_bien_existente_incorporacion').disabled = true;
	document.getElementById('codigo_catalogo_existente_incorporacion').disabled = true;
	document.getElementById('codigo_bien_existente_desincorporacion').disabled = true;
	document.getElementById('codigo_catalogo_existente_desincorporacion').disabled = true;
	
}





function consultarDatosBasicos(idmovimiento){
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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
					setTimeout("document.getElementById('tipo_movimiento').value= "+partes[4],300);
					document.getElementById('tipo').value= partes[3];
					document.getElementById('nro_documento').value= partes[5];
					document.getElementById('fecha_documento').value= partes[6];
					document.getElementById('justificacion_movimiento').value= partes[7];
					
					document.getElementById('nro_documento_bienes_nuevos').value= partes[9];
					document.getElementById('fecha_documento_bienes_nuevos').value= partes[10];
					document.getElementById('proveedores_bienes_nuevos').value= partes[11];
					document.getElementById('nro_factura_bienes_nuevos').value= partes[12];
					document.getElementById('fecha_factura_bienes_nuevos').value= partes[13];
					
				
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
		document.getElementById('traslados').style.display = 'none';
		document.getElementById('li_registro_fotografico_bienes_nuevos').style.visibility = 'visible';
		document.getElementById('li_bienes_adquisicion_bienes_nuevos').style.visibility = 'visible';
	}else if(document.getElementById('accion_tipo_movimiento').value == "existente_incorporacion"){
		document.getElementById('bienes_nuevos').style.display = 'none';
		document.getElementById('existentes_desincorporacion').style.display = 'none';
		document.getElementById('existentes_incorporacion').style.display = 'block';
		document.getElementById('traslados').style.display = 'none';
		document.getElementById('li_registro_fotografico_bienes_incorporacion').style.visibility = 'visible';
		document.getElementById('li_bienes_adquisicion_bienes_nuevos').style.visibility = 'hidden';
	}else if(document.getElementById('accion_tipo_movimiento').value == "existente_desincorporacion"){
		document.getElementById('bienes_nuevos').style.display = 'none';
		document.getElementById('existentes_desincorporacion').style.display = 'block';
		document.getElementById('existentes_incorporacion').style.display = 'none';
		document.getElementById('traslados').style.display = 'none';
		document.getElementById('li_registro_fotografico_bienes_desincorporacion').style.visibility = 'visible';
		document.getElementById('li_bienes_adquisicion_bienes_nuevos').style.visibility = 'hidden';
	}else if(document.getElementById('accion_tipo_movimiento').value == "ambos"){
		document.getElementById('bienes_nuevos').style.display = 'none';
		document.getElementById('existentes_desincorporacion').style.display = 'none';
		document.getElementById('existentes_incorporacion').style.display = 'none';
		document.getElementById('traslados').style.display = 'block';
		document.getElementById('li_registro_fotografico_bienes_desincorporacion').style.visibility = 'hidden';
		document.getElementById('li_bienes_adquisicion_bienes_nuevos').style.visibility = 'hidden';
		document.getElementById('li_registro_fotografico_bienes_traslado').style.visibility = 'visible';
		
	}
	
	
	
	
	
	consultarDatosBasicos(idmovimiento);
	listaMovimientosExistentesDesIncorporacion();
	listarBienesNuevos();
	listaMovimientosExistentesIncorporacion();
	listarRegistroFotograficoExistentesIncorporacion();
	listarRegistroFotograficoExistentesDesIncorporacion();
	listarRegistroFotograficoTraslado();
	listarRegistroFotograficoBienesNuevos();
	mostrarBienesDestinoTraslado();
	if(estado == "elaboracion"){
		desbloquearCampos();
		document.getElementById('boton_procesar').style.display = 'block';
		document.getElementById('boton_modificar').style.display = 'block';
		document.getElementById('boton_siguiente').style.display = 'none';
		document.getElementById('boton_anular').style.display = 'none';
		document.getElementById('celdaImprimir').style.display = 'none';
	}else if(estado == 'anulado'){
		bloquearCampos();
		document.getElementById('boton_procesar').style.display = 'none';
		document.getElementById('boton_modificar').style.display = 'none';
		document.getElementById('boton_siguiente').style.display = 'none';
		document.getElementById('boton_anular').style.display = 'none';	
		document.getElementById('celdaImprimir').style.display = 'block';
	}else if(estado == "procesado"){
		bloquearCampos();
		document.getElementById('boton_procesar').style.display = 'none';
		document.getElementById('boton_modificar').style.display = 'none';
		document.getElementById('boton_siguiente').style.display = 'none';
		document.getElementById('boton_anular').style.display = 'block';
		document.getElementById('celdaImprimir').style.display = 'block';
	}
}





function consultarTipoDetalle(iddetalle){
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("celda_tipo_detalle").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("iddetalle="+iddetalle+"&ejecutar=consultarTipoDetalle");		
}















function calcularDepreciacionAnual(){
	var costo = document.getElementById('costo_ajustado_bienes_nuevos').value;
	var valor_residual = document.getElementById('valor_residual_bienes_nuevos').value;
	var vida_util = document.getElementById('vida_util_bienes_nuevos').value;
	
	depreciacion = (parseFloat(costo) - parseFloat(valor_residual))/ parseFloat(vida_util);
	
	depreciacion = depreciacion.toFixed(2);
	if(depreciacion == "Infinity"){
		document.getElementById('depreciacion_anual_bienes_nuevos').value = 0;
	}else{
		document.getElementById('depreciacion_anual_bienes_nuevos').value = depreciacion;	
	}
	if(parseFloat(document.getElementById('depreciacion_acumulada_bienes_nuevos').value) > parseFloat(document.getElementById('costo_ajustado_bienes_nuevos').value)){
		document.getElementById('depreciacion_acumulada_bienes_nuevos').style.color = '#FF0000';
	}else{
		document.getElementById('depreciacion_acumulada_bienes_nuevos').style.color = '#000000';	
	}
}


function calcularDepreciacionAcumulada(){
	var depreciacion_anual = document.getElementById('depreciacion_anual_bienes_nuevos').value;
	var fecha_compra = document.getElementById('fecha_documento_bienes_nuevos').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimientos_ajax.php", true);
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