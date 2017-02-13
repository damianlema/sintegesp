// JavaScript Document
function alerta(){
	alert("prueba");
}
function mostrarContenido(contenido){
	document.getElementById('divDatosPrincipales').style.display = 'none';
	document.getElementById('divMovimiento').style.display = 'none';
	document.getElementById('divCosto').style.display = 'none';
	document.getElementById('divSeguro').style.display = 'none';
	document.getElementById('divFotos').style.display = 'none';
	document.getElementById(contenido).style.display = 'block';
	
}

function cargarTipo(iddetalle){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_tipo_detalle').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("iddetalle="+iddetalle+"&ejecutar=cargarTipo");	
}


function seleccionarNivel(idorganizacion){
	var ajax=nuevoAjax();
	idcampo = 'idnivel_organizacion';
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
				document.getElementById('celda_nivel_organizacion').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&idcampo="+idcampo+"&ejecutar=seleccionarNivel");
}



function validarSeleccionado(id){
	if(document.getElementById(id).checked == false){
		document.getElementById('tabla_aseguradora').style.display = 'none';
	}else{
		document.getElementById('tabla_aseguradora').style.display = 'block';
	}	
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
			document.getElementById('codigo_bien').value = partes[1];
			document.getElementById('codigo_bien_automatico').value = partes[0];
		} 
	}
	ajax.send("cantidad="+cantidad+"&ejecutar=generar_codigo");			
}




function ingresarMueble(){
	var idorganizacion = document.getElementById('idorganizacion').value;
	var idnivel_organizacion = document.getElementById('idnivel_organizacion').value;
	var idtipo_movimiento = document.getElementById('idtipo_movimiento').value;
	var idcatalogo_bienes = document.getElementById('idcatalogo_bienes').value;
	var codigo_bien = document.getElementById('codigo_bien').value;
	var codigo_bien_automatico = document.getElementById('codigo_bien_automatico').value;
	var cantidad = document.getElementById('cantidad_bien').value;
	var codigo_anterior_bien = document.getElementById('codigo_anterior_bien').value;
	var idubicacion = document.getElementById('idubicacion').value;
	var especificaciones = document.getElementById('especificaciones').value;
	var marca = document.getElementById('marca').value;
	var modelo = document.getElementById('modelo').value;
	var idtipo = document.getElementById('idtipo').value;
	var serial = document.getElementById('serial').value;
	var accesorios = document.getElementById('accesorios').value;
	var nro_documento_compra = document.getElementById('nro_documento_compra').value;
	var proveedor = document.getElementById('proveedor').value;
	var fecha_compra = document.getElementById('fecha_compra').value;
	var nro_factura = document.getElementById('nro_factura').value;
	var fecha_factura = document.getElementById('fecha_factura').value;
	var costo = document.getElementById('costo').value;
	var mejoras = document.getElementById('mejoras').value;
	var costo_ajustado = document.getElementById('costo_ajustado').value;
	var valor_residual = document.getElementById('valor_residual').value;
	var vida_util = document.getElementById('vida_util').value;
	var depreciacion_anual = document.getElementById('depreciacion_anual').value;
	var depreciacion_acumulada = document.getElementById('depreciacion_acumulada').value;
	if(document.getElementById('asegurado').checked == true){
		asegurado = "si";	
	}else{
		asegurado = "no";	
	}
	var aseguradora = document.getElementById('aseguradora').value;
	var nro_poliza = document.getElementById('nro_poliza').value;
	var fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
	var monto_poliza = document.getElementById('monto_poliza').value;
	var monto_asegurado = document.getElementById('monto_asegurado').value;
	
	//alert(codigo_bien);
	//if(!validarCodigoBien(codigo_bien)){
		if(codigo_bien == ""){	
			mostrarMensajes("error", "Disculpe debe Ingresar el Codigo del Bien");
		}else if(idcatalogo_bienes == ""){
			mostrarMensajes("error", "Disculpe debe seleccionar el Catalogo de bienes");
		}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					mostrarMensajes("exito", "El mueble fue ingresado con exito");
						//limpiarDatos();
						document.getElementById('idDivMovimiento').style.display = 'block';
						document.getElementById('idDivCosto').style.display = 'block';
						document.getElementById('idDivSeguro').style.display = 'block';
						document.getElementById('idDivFotos').style.display = 'block';

						document.getElementById("boton_ingresar").style.display = "none";
						document.getElementById('boton_modificar').style.display = 'block';
						document.getElementById("boton_eliminar").style.display = "none";
						document.getElementById("codigo_bien_mostrar").innerHTML = codigo_bien;
						document.getElementById("estado_bien_mostrar").innerHTML = 'Activo';
						//document.getElementById('buscar_catalogo').style.display = 'none';
						document.getElementById("idmueble").value = ajax.responseText;
						document.getElementById("divCargando").style.display = "none";
						if(cantidad>1){
							window.location.href = 'principal.php?accion=767&modulo=8'
						}
				} 
			}
			ajax.send("idorganizacion="+idorganizacion+"&idnivel_organizacion="+idnivel_organizacion+"&idtipo_movimiento="+idtipo_movimiento+"&idcatalogo_bienes="+idcatalogo_bienes+"&codigo_bien="+codigo_bien+"&codigo_anterior_bien="+codigo_anterior_bien+"&idubicacion="+idubicacion+"&especificaciones="+especificaciones+"&marca="+marca+"&modelo="+modelo+"&idtipo="+idtipo+"&serial="+serial+"&accesorios="+accesorios+"&nro_documento_compra="+nro_documento_compra+"&proveedor="+proveedor+"&fecha_compra="+fecha_compra+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&costo="+costo+"&valor_residual="+valor_residual+"&vida_util="+vida_util+"&depreciacion_anual="+depreciacion_anual+"&depreciacion_acumulada="+depreciacion_acumulada+"&asegurado="+asegurado+"&aseguradora="+aseguradora+"&nro_poliza="+nro_poliza+"&fecha_vencimiento="+fecha_vencimiento+"&monto_poliza="+monto_poliza+"&monto_asegurado="+monto_asegurado+"&mejoras="+mejoras+"&costo_ajustado="+costo_ajustado+"&codigo_bien_automatico="+codigo_bien_automatico+"&cantidad="+cantidad+"&ejecutar=ingresarMueble");
		}
	//}

}


function limpiarDatos(){
	//document.getElementById('idorganizacion').value = 0;
	//document.getElementById('idnivel_organizacion').value = 0;
	//document.getElementById('idtipo_movimiento').value = 0;
	document.getElementById('idcatalogo_bienes').value = 0;
	document.getElementById('codigo_bien').value = '';
	document.getElementById('codigo_bien_automatico').value = '';
	document.getElementById('codigo_anterior_bien').value = '';
	document.getElementById('idubicacion').value = 0;
	document.getElementById('especificaciones').value = '';
	document.getElementById('marca').value = '';
	document.getElementById('modelo').value = '';
	document.getElementById('idtipo').value = 0;
	document.getElementById('serial').value = '';
	document.getElementById('accesorios').value = '';
	document.getElementById('nro_documento_compra').value = '';
	document.getElementById('proveedor').value = '';
	document.getElementById('fecha_compra').value = '';
	document.getElementById('nro_factura').value = '';
	document.getElementById('fecha_factura').value = '';
	document.getElementById('costo').value = 0;
	document.getElementById('mejoras').value = 0;
	document.getElementById('costo_ajustado').value = 0;
	document.getElementById('valor_residual').value = 0;
	document.getElementById('vida_util').value = 0;
	document.getElementById('depreciacion_anual').value = 0;
	document.getElementById('depreciacion_acumulada').value = 0;
	document.getElementById('asegurado').checked = false;
	document.getElementById('tabla_aseguradora').style.display = 'none';
	document.getElementById('aseguradora').value = '';
	document.getElementById('nro_poliza').value = 0;
	document.getElementById('fecha_vencimiento').value = '';
	document.getElementById('monto_poliza').value = 0;
	document.getElementById('monto_asegurado').value = 0;
	document.getElementById('idmueble').value = '';
	document.getElementById('catalogo_bienes').value = '';
	document.getElementById('lista_Movimientos').innerHTML = '';
	document.getElementById('lista_registroFotografico').innerHTML = '';
	document.getElementById('codigo_bien_mostrar').innerHTML = '';
	document.getElementById('estado_bien_mostrar').innerHTML = '';
	document.getElementById("estado_bien_mostrar").bgColor = '#ccc';
	
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
	
	document.getElementById('idDivMovimiento').style.display = 'none';
	document.getElementById('idDivCosto').style.display = 'none';
	document.getElementById('idDivSeguro').style.display = 'none';
	document.getElementById('idDivFotos').style.display = 'none';
	
	document.getElementById("idgenerar_codigo").style.display  ='block';
	
}

function calcularCostoAjustado(){
	var costo = document.getElementById('costo').value;
	var mejoras = document.getElementById('mejoras').value;
	
	costo_ajustado = (parseFloat(costo) + parseFloat(mejoras));
	
	costo_ajustado = costo_ajustado.toFixed(2);
	if(costo_ajustado == "Infinity"){
		document.getElementById('costo_ajustado').value = 0;
	}else{
		document.getElementById('costo_ajustado').value = costo_ajustado;	
	}
	
}

function calcularDepreciacionAnual(){
	var costo = document.getElementById('costo_ajustado').value;
	var valor_residual = document.getElementById('valor_residual').value;
	var vida_util = document.getElementById('vida_util').value;
	
	depreciacion = (parseFloat(costo) - parseFloat(valor_residual))/ parseFloat(vida_util);
	
	depreciacion = depreciacion.toFixed(2);
	if(depreciacion == "Infinity"){
		document.getElementById('depreciacion_anual').value = 0;
	}else{
		document.getElementById('depreciacion_anual').value = depreciacion;	
	}
	if(parseFloat(document.getElementById('depreciacion_acumulada').value) > parseFloat(document.getElementById('costo').value)){
		document.getElementById('depreciacion_acumulada').style.color = '#FF0000';
	}else{
		document.getElementById('depreciacion_acumulada').style.color = '#000000';	
	}
}


function calcularDepreciacionAcumulada(){
	var depreciacion_anual = document.getElementById('depreciacion_anual').value;
	var fecha_compra = document.getElementById('fecha_compra').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
				if(parseFloat(ajax.responseText) > parseFloat(document.getElementById('costo').value)){
					document.getElementById('depreciacion_acumulada').style.color = '#FF0000';
				}else{
					document.getElementById('depreciacion_acumulada').style.color = '#000000';	
				}
				document.getElementById('depreciacion_acumulada').value = ajax.responseText;
		} 
	}
	ajax.send("fecha_compra="+fecha_compra+"&depreciacion_anual="+depreciacion_anual+"&ejecutar=calcularDepreciacionAcumulada");
	
}









function modificarMueble(){
	var idmueble = document.getElementById('idmueble').value;
	var idorganizacion = document.getElementById('idorganizacion').value;
	var idnivel_organizacion = document.getElementById('idnivel_organizacion').value;
	var idtipo_movimiento = document.getElementById('idtipo_movimiento').value;
	var idcatalogo_bienes = document.getElementById('idcatalogo_bienes').value;
	var codigo_bien = document.getElementById('codigo_bien').value;
	var codigo_anterior_bien = document.getElementById('codigo_anterior_bien').value;
	var idubicacion = document.getElementById('idubicacion').value;
	var especificaciones = document.getElementById('especificaciones').value;
	var marca = document.getElementById('marca').value;
	var modelo = document.getElementById('modelo').value;
	var idtipo = document.getElementById('idtipo').value;
	var serial = document.getElementById('serial').value;
	var accesorios = document.getElementById('accesorios').value;
	var nro_documento_compra = document.getElementById('nro_documento_compra').value;
	var proveedor = document.getElementById('proveedor').value;
	var fecha_compra = document.getElementById('fecha_compra').value;
	var nro_factura = document.getElementById('nro_factura').value;
	var fecha_factura = document.getElementById('fecha_factura').value;
	var costo = document.getElementById('costo').value;
	var mejoras = document.getElementById('mejoras').value;
	var costo_ajustado = document.getElementById('costo_ajustado').value;
	var valor_residual = document.getElementById('valor_residual').value;
	var vida_util = document.getElementById('vida_util').value;
	var depreciacion_anual = document.getElementById('depreciacion_anual').value;
	var depreciacion_acumulada = document.getElementById('depreciacion_acumulada').value;
	if(document.getElementById('asegurado').checked == true){
		asegurado = "si";	
	}else{
		asegurado = "no";	
	}
	var aseguradora = document.getElementById('aseguradora').value;
	var nro_poliza = document.getElementById('nro_poliza').value;
	var fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
	var monto_poliza = document.getElementById('monto_poliza').value;
	var monto_asegurado = document.getElementById('monto_asegurado').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				//limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmueble="+idmueble+"&idorganizacion="+idorganizacion+"&idnivel_organizacion="+idnivel_organizacion+"&idtipo_movimiento="+idtipo_movimiento+"&idcatalogo_bienes="+idcatalogo_bienes+"&codigo_bien="+codigo_bien+"&codigo_anterior_bien="+codigo_anterior_bien+"&idubicacion="+idubicacion+"&especificaciones="+especificaciones+"&marca="+marca+"&modelo="+modelo+"&idtipo="+idtipo+"&serial="+serial+"&accesorios="+accesorios+"&nro_documento_compra="+nro_documento_compra+"&proveedor="+proveedor+"&fecha_compra="+fecha_compra+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&costo="+costo+"&valor_residual="+valor_residual+"&vida_util="+vida_util+"&depreciacion_anual="+depreciacion_anual+"&depreciacion_acumulada="+depreciacion_acumulada+"&asegurado="+asegurado+"&aseguradora="+aseguradora+"&nro_poliza="+nro_poliza+"&fecha_vencimiento="+fecha_vencimiento+"&monto_poliza="+monto_poliza+"&monto_asegurado="+monto_asegurado+"&mejoras="+mejoras+"&costo_ajustado="+costo_ajustado+"&ejecutar=modificarMueble");

}



function eliminarMueble(){
	var idmueble = document.getElementById('idmueble').value;
	if(confirm("Realmente desea Eliminar el Mueble?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmueble="+idmueble+"&ejecutar=eliminarMueble");	
	}
}




function consultarMueble(idmueble){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){	
				partes = ajax.responseText.split("|.|");
					document.getElementById('idmueble').value = partes[0];
					document.getElementById('idorganizacion').value = partes[1];
					seleccionarNivel(partes[1]);
					document.getElementById('idtipo_movimiento').value = partes[3];
					document.getElementById('idcatalogo_bienes').value = partes[4];
					cargarTipo(partes[4]);
					document.getElementById('codigo_bien').value = partes[5];
					document.getElementById("codigo_bien_mostrar").innerHTML = partes[5];
					document.getElementById('idubicacion').value = partes[6];
					document.getElementById('especificaciones').value = partes[7];
					document.getElementById('marca').value = partes[8];
					document.getElementById('modelo').value = partes[9];
					document.getElementById('serial').value = partes[11];
					document.getElementById('accesorios').value = partes[12];
					document.getElementById('nro_documento_compra').value = partes[13];
					document.getElementById('proveedor').value = partes[14];
					document.getElementById('fecha_compra').value = partes[15];
					document.getElementById('nro_factura').value = partes[16];
					document.getElementById('fecha_factura').value = partes[17];
					document.getElementById('costo').value = partes[18];
					document.getElementById('valor_residual').value = partes[19];
					document.getElementById('vida_util').value = partes[20];
					document.getElementById('depreciacion_anual').value = partes[21];
					document.getElementById('depreciacion_acumulada').value = partes[22];
					if(partes[23] == "si"){
						document.getElementById('asegurado').checked = true;
						document.getElementById('tabla_aseguradora').style.display = 'block';
					}else{
						document.getElementById('asegurado').checked = false;
						document.getElementById('tabla_aseguradora').style.display = 'none';	
					}
					document.getElementById('aseguradora').value = partes[24];
					document.getElementById('nro_poliza').value = partes[25];
					document.getElementById('fecha_vencimiento').value = partes[26];
					document.getElementById('monto_poliza').value = partes[27];
					document.getElementById('monto_asegurado').value = partes[28];
					document.getElementById('catalogo_bienes').value = partes[29];
					document.getElementById('mejoras').value = partes[30];
					document.getElementById('costo_ajustado').value = partes[31];
					document.getElementById('codigo_anterior_bien').value = partes[32];
					if (partes[33] == 'activo'){ 
						estado = 'Activo';
						document.getElementById("estado_bien_mostrar").bgColor = '#00CC33';
						document.getElementById("estado_bien_mostrar").innerHTML = estado;
					}else{ 
						estado = 'Desincorporado';
						document.getElementById("estado_bien_mostrar").bgColor = "#FF0000";
						document.getElementById("estado_bien_mostrar").innerHTML = estado;
					}
					
					
					setTimeout("document.getElementById('idnivel_organizacion').value = "+partes[2]+"", 600);
					setTimeout("document.getElementById('idtipo').value = "+partes[10]+"", 700);
					setTimeout("calcularDepreciacionAcumulada()", 800);
					
					consultar_registroFotografico(partes[0]);
					consultar_Movimientos(partes[0],partes[5]);
					
					document.getElementById('boton_ingresar').style.display = 'none';
					document.getElementById('boton_modificar').style.display = 'block';
					document.getElementById('boton_eliminar').style.display = 'block';
					document.getElementById('idDivMovimiento').style.display = 'block';
					document.getElementById('idDivCosto').style.display = 'block';
					document.getElementById('idDivSeguro').style.display = 'block';
					document.getElementById('idDivFotos').style.display = 'block';
					document.getElementById('idgenerar_codigo').style.display = 'none';
					//document.getElementById('buscar_catalogo').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmueble="+idmueble+"&ejecutar=consultarMueble");
}




function validarCodigoBien(valor){
	//alert(valor);
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el Codigo del Bien Ingresado ya Existe");
					document.getElementById('codigo_bien').value = '';
					document.getElementById('boton_ingresar').disabled = true;
					document.getElementById('boton_modificar').disabled = true;
					document.getElementById('boton_eliminar').disabled = true;
				}else{
					document.getElementById('boton_ingresar').disabled = false;
					document.getElementById('boton_modificar').disabled = false;
					document.getElementById('boton_eliminar').disabled = false;	
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=validarCodigoBien");	
}


function subirRegistroFotografico(){
	var nombre_imagen 	= document.getElementById("nombre_imagen").value;
	var idmueble 	= document.getElementById("idmueble").value;
	var descripcion		= document.getElementById('descripcion_foto').value;
	var ajax			= nuevoAjax();
	
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('mostrarImagen').innerHTML = '';
			document.getElementById('foto_registroFotografico').value = '';
			document.getElementById('descripcion_foto').value = '';
			consultar_registroFotografico(idmueble);	
		} 
	}
	ajax.send("descripcion="+descripcion+"&idmueble="+idmueble+"&nombre_imagen="+nombre_imagen+"&ejecutar=subirRegistroFotografico");		
}


function consultar_registroFotografico(idmueble){	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('lista_registroFotografico').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idmueble="+idmueble+"&ejecutar=consultar_registroFotografico");		
}


function consultar_Movimientos(idmueble,codigo_bien){	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('lista_Movimientos').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idmueble="+idmueble+"&codigo_bien="+codigo_bien+"&ejecutar=consultar_Movimientos");		
}



function eliminar_registroFotografico(idregistro_fotografico){
	if(confirm("Seguro desea Eliminar esta imagen?")){
	var ajax	= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultar_registroFotografico(document.getElementById('idmueble').value);
		} 
	}
	ajax.send("idregistro_fotografico="+idregistro_fotografico+"&ejecutar=eliminar_registroFotografico");		
	}
}




function principal_registroFotografico(idregistro_fotografico_trabajador){
	var idmueble 	= document.getElementById("idmueble").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/muebles_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultarTrabajador(document.getElementById('idmueble').value);
		} 
	}
	ajax.send("idmueble="+idmueble+"&idregistro_fotografico_trabajador="+idregistro_fotografico_trabajador+"&ejecutar=principal_registroFotografico");		
}


