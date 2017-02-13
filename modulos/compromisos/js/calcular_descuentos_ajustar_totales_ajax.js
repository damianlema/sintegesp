// JavaScript Document

function buscarOrdenes(numero_orden){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/calcular_descuentos_ajustar_totales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				blanquearFormulario();
				document.getElementById("listaOrdenes").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_orden="+numero_orden+"&ejecutar=buscarOrdenes");	
}





function actualizarDatos(id_orden_compra, descuento, justificacion, exento, sub_total, impuesto, total,  ordenado_por, cedula_ordenado, nro_requisicion, fecha_requisicion, nro_factura, nro_control, fecha_factura){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/calcular_descuentos_ajustar_totales_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Los datos fueron actualizados con exito");
				buscarOrdenes(document.getElementById('campoBuscar').value);
				blanquearFormulario();
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&descuento="+descuento+"&justificacion="+justificacion+"&exento="+exento+"&sub_total="+sub_total+"&impuesto="+impuesto+"&total="+total+"&fecha_factura="+fecha_factura+"&nro_control="+nro_control+"&nro_factura="+nro_factura+"&fecha_requisicion="+fecha_requisicion+"&nro_requisicion="+nro_requisicion+"&cedula_ordenado="+cedula_ordenado+"&ordenado_por="+ordenado_por+"&ejecutar=actualizarDatos");		
}








function mostrarDatos(id_orden_compra, 
					  	numero_orden, 
						justificacion, 
						exento, 
						sub_total, 
						impuesto, 
						descuento,
						total,
						exento_mostrado, 
						sub_total_mostrado, 
						impuesto_mostrado, 
						descuento_mostrado,
						total_mostrado,
						ordenado_por,
						cedula_ordenado,
						numero_requisicion,
						fecha_requisicion,
						nro_factura,
						nro_control,
						fecha_factura){
	
	
	justificacion = document.getElementById('justifica').value;
	document.getElementById('numero_orden').innerHTML= '<strong>'+numero_orden+'</strong>'
	document.getElementById('id_orden_compra').value = id_orden_compra;
	document.getElementById('numero_orden').value = numero_orden;
	document.getElementById('justificacion').value = justificacion;
	document.getElementById('exento').value = exento;
	document.getElementById('exento_mostrado').value = exento_mostrado;
	document.getElementById('sub_total').value = sub_total;
	document.getElementById('sub_total_mostrado').value = sub_total_mostrado;
	document.getElementById('impuesto').value = impuesto;
	document.getElementById('impuesto_mostrado').value = impuesto_mostrado;
	document.getElementById('descuento').value = descuento;
	document.getElementById('descuento_mostrado').value = descuento_mostrado;
	document.getElementById('total').value = total;
	document.getElementById('total_mostrado').value = total_mostrado;
	document.getElementById('ordenado_por').value = ordenado_por;
	document.getElementById('cedula_ordenado').value = cedula_ordenado;
	document.getElementById('nro_requisicion').value = numero_requisicion;
	document.getElementById('fecha_requisicion').value = fecha_requisicion;
	document.getElementById('nro_factura').value = nro_factura;
	document.getElementById('nro_control').value = nro_control;
	document.getElementById('fecha_factura').value = fecha_factura;
	listarPartidas(id_orden_compra);
}



function blanquearFormulario(){
	document.getElementById('numero_orden').innerHTML= ''
	document.getElementById('id_orden_compra').value = "";
	document.getElementById('numero_orden').value = "";
	document.getElementById('justificacion').value = "";
	document.getElementById('exento').value = "";
	document.getElementById('exento_mostrado').value = "";
	document.getElementById('sub_total').value = "";
	document.getElementById('sub_total_mostrado').value = "";
	document.getElementById('impuesto').value = "";
	document.getElementById('impuesto_mostrado').value = "";
	document.getElementById('descuento').value = "";
	document.getElementById('descuento_mostrado').value = "";
	document.getElementById('total').value = "";
	document.getElementById('total_mostrado').value = "";
	document.getElementById('ordenado_por').value = "";
	document.getElementById('cedula_ordenado').value = "";
	document.getElementById('nro_requisicion').value = "";
	document.getElementById('fecha_requisicion').value = "";
	document.getElementById('nro_factura').value = "";
	document.getElementById('nro_control').value = "";
	document.getElementById('fecha_factura').value = "";
	document.getElementById('listaOrdenes').innerHTML = "";
	document.getElementById('mostrarPartidas').innerHTML = "";
}



function listarPartidas(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/calcular_descuentos_ajustar_totales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('mostrarPartidas').style.display = 'block';
			document.getElementById('mostrarPartidas').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&ejecutar=listarPartidas");	
}



function actualizarPartidas(idpartida, monto_actualizar){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/calcular_descuentos_ajustar_totales_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Se Actualizo la Partida con Exito");
			}else{
				mostrarMensajes("error", ajax.responseText);
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idpartida="+idpartida+"&monto_actualizar="+monto_actualizar+"&ejecutar=actualizarPartidas");	
}


function volverMontosOriginales(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/calcular_descuentos_ajustar_totales_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){

			partes = ajax.responseText.split("|.|");
			document.getElementById('exento_mostrado').value = partes[0];
			document.getElementById('sub_total_mostrado').value = partes[1];
			document.getElementById('impuesto_mostrado').value = partes[2];
			document.getElementById('total_mostrado').value = partes[3];
			formatoNumero('exento_mostrado', 'exento');
			formatoNumero('sub_total_mostrado', 'sub_total');
			formatoNumero('impuesto_mostrado', 'impuesto');
			formatoNumero('total_mostrado', 'total');
			document.getElementById("divCargando").style.display = "none";
			
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&ejecutar=volverMontosOriginales");	
}