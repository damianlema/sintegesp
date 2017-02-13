// JavaScript Document

function buscarOrdenes(numero_orden){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/administracion/lib/ajustar_orden_pago_ajax.php", true);
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





function actualizarDatos(id_orden_pago, justificacion, exento, sub_total, impuesto,
							retenido, total, asignaciones, deducciones, total_nomina, ordenado_por, cedula_ordenado, multi_categoria){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/administracion/lib/ajustar_orden_pago_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			mostrarMensajes("exito", "Los datos fueron actualizados con exito");
				buscarOrdenes(document.getElementById('campoBuscar').value);
				blanquearFormulario();
		}
	}
	ajax.send("id_orden_pago="+id_orden_pago
				+"&justificacion="+justificacion
				+"&exento="+exento
				+"&sub_total="+sub_total
				+"&impuesto="+impuesto
				+"&retenido="+retenido
				+"&total="+total
				+"&asignaciones="+asignaciones
				+"&deducciones="+deducciones
				+"&total_nomina="+total_nomina
				+"&cedula_ordenado="+cedula_ordenado
				+"&ordenado_por="+ordenado_por
				+"&multi_categoria="+multi_categoria
				+"&ejecutar=actualizarDatos");
}








function mostrarDatos(id_orden_pago,
					  	numero_orden,
						justificacion,
						exento,
						sub_total,
						impuesto,
						total_retenido,
						total,
						exento_mostrado,
						sub_total_mostrado,
						impuesto_mostrado,
						total_retenido_mostrado,
						total_mostrado,
						ordenado_por,
						cedula_ordenado,
						multi_categoria){

	document.getElementById('celda_deducciones').style.display = 'none';
	document.getElementById('celda_asignaciones').style.display = 'none';
	document.getElementById('celda_total_nomina').style.display = 'none';
	document.getElementById('celda_exento').style.display = 'none';
	document.getElementById('celda_subtotal').style.display = 'none';
	document.getElementById('celda_impuesto').style.display = 'none';
	document.getElementById('celda_retenido').style.display = 'none';
	document.getElementById('celda_total').style.display = 'none';

	//justificacion = document.getElementById('justifica').value;
	document.getElementById('numero_orden').innerHTML= '<strong>'+numero_orden+'</strong>'
	document.getElementById('id_orden_pago').value = id_orden_pago;
	document.getElementById('multi_categoria').value = multi_categoria;
	document.getElementById('numero_orden').value = numero_orden;
	document.getElementById('justificacion').value = justificacion;
	if (multi_categoria == 'si'){
		document.getElementById('deducciones').value = exento;
		document.getElementById('deducciones_mostrado').value = exento_mostrado;
		document.getElementById('celda_deducciones').style.display = 'block';
		document.getElementById('asignaciones').value = sub_total;
		document.getElementById('asignaciones_mostrado').value = sub_total_mostrado;
		document.getElementById('celda_asignaciones').style.display = 'block';
		document.getElementById('total_nomina').value = sub_total;
		document.getElementById('total_nomina_mostrado').value = total_mostrado;
		document.getElementById('celda_total_nomina').style.display = 'block';
	}else{
		document.getElementById('exento').value = exento;
		document.getElementById('exento_mostrado').value = exento_mostrado;
		document.getElementById('celda_exento').style.display = 'block';
		document.getElementById('sub_total').value = sub_total;
		document.getElementById('sub_total_mostrado').value = sub_total_mostrado;
		document.getElementById('celda_subtotal').style.display = 'block';
		document.getElementById('impuesto').value = impuesto;
		document.getElementById('impuesto_mostrado').value = impuesto_mostrado;
		document.getElementById('celda_impuesto').style.display = 'block';
		document.getElementById('retenido').value = total_retenido;
		document.getElementById('retenido_mostrado').value = total_retenido_mostrado;
		document.getElementById('celda_retenido').style.display = 'block';
		document.getElementById('total').value = total;
		document.getElementById('total_mostrado').value = total_mostrado;
		document.getElementById('celda_total').style.display = 'block';
	}

	document.getElementById('ordenado_por').value = ordenado_por;
	document.getElementById('cedula_ordenado').value = cedula_ordenado;
	listarPartidas(id_orden_pago);
}



function blanquearFormulario(){
	document.getElementById('numero_orden').innerHTML= ''
	document.getElementById('id_orden_pago').value = "";
	document.getElementById('numero_orden').value = "";
	document.getElementById('justificacion').value = "";
	document.getElementById('exento').value = "";
	document.getElementById('exento_mostrado').value = "";
	document.getElementById('sub_total').value = "";
	document.getElementById('sub_total_mostrado').value = "";
	document.getElementById('impuesto').value = "";
	document.getElementById('impuesto_mostrado').value = "";
	document.getElementById('total').value = "";
	document.getElementById('total_mostrado').value = "";
	document.getElementById('ordenado_por').value = "";
	document.getElementById('cedula_ordenado').value = "";
	document.getElementById('listaOrdenes').innerHTML = "";
	document.getElementById('mostrarPartidas').innerHTML = "";
	document.getElementById('celda_deducciones').style.display = 'none';
	document.getElementById('celda_asignaciones').style.display = 'none';
	document.getElementById('celda_total_nomina').style.display = 'none';
	document.getElementById('celda_exento').style.display = 'none';
	document.getElementById('celda_subtotal').style.display = 'none';
	document.getElementById('celda_impuesto').style.display = 'none';
	document.getElementById('celda_retenido').style.display = 'none';
	document.getElementById('celda_total').style.display = 'none';
}



function listarPartidas(id_orden_pago){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/administracion/lib/ajustar_orden_pago_ajax.php", true);
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
	ajax.send("id_orden_pago="+id_orden_pago+"&ejecutar=listarPartidas");
}



function actualizarPartidas(idpartida, monto_actualizar){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/administracion/lib/ajustar_orden_pago_ajax.php", true);
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




function cambiarTotal(){
	var multi_categoria = document.getElementById('multi_categoria').value;
	if(multi_categoria == 'no'){
		var exento = parseFloat(document.getElementById('exento').value);
		var sub_total = parseFloat(document.getElementById('sub_total').value);
		var impuesto = parseFloat(document.getElementById('impuesto').value);
		var total_retenido = parseFloat(document.getElementById('retenido').value);
		var total = (exento+sub_total+impuesto)-total_retenido;
		document.getElementById('total_mostrado').value = total;
		formatoNumero("total_mostrado", 'total');
	}else{
		var asignaciones = parseFloat(document.getElementById('asignaciones').value);
		var deducciones = parseFloat(document.getElementById('deducciones').value);
		var total_nomina = asignaciones - deducciones;
		document.getElementById('total_nomina_mostrado').value = total_nomina;
		formatoNumero("total_nomina_mostrado", 'total_nomina');
	}
}