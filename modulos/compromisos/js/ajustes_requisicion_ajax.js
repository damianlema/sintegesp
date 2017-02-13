// JavaScript Document

function buscarOrdenes(numero_orden){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/ajustes_requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaOrdenes").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_orden="+numero_orden+"&ejecutar=buscarOrdenes");	
}





function actualizarDatos(id_requisicion, descuento, justificacion, exento, sub_total, impuesto, total){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/ajustes_requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Los datos fueron actualizados con Exito");
				buscarOrdenes(document.getElementById('campoBuscar').value);
				blanquearFormulario();
		} 
	}
	ajax.send("id_requisicion="+id_requisicion+"&descuento="+descuento+"&justificacion="+justificacion+"&exento="+exento+"&sub_total="+sub_total+"&impuesto="+impuesto+"&total="+total+"&ejecutar=actualizarDatos");		
}








function mostrarDatos(id_requisicion, 
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
	document.getElementById('id_requisicion').value = id_requisicion;
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
	listarPartidas(id_requisicion);
}



function blanquearFormulario(){
	document.getElementById('numero_orden').innerHTML= ''
	document.getElementById('id_requisicion').value = "";
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
}








function listarPartidas(id_requisicion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/ajustes_requisicion_ajax.php", true);	
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
	ajax.send("id_requisicion="+id_requisicion+"&ejecutar=listarPartidas");	
}



function actualizarPartidas(idpartida, monto_actualizar){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/ajustes_requisicion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exitio", "Se Actualizo la Partida con Exito");
			}else{
				mostrarMensajes("error", ajax.responseText);
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idpartida="+idpartida+"&monto_actualizar="+monto_actualizar+"&ejecutar=actualizarPartidas");	
}


function volverMontosOriginales(id_requisicion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/ajustes_requisicion_ajax.php", true);
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
	ajax.send("id_requisicion="+id_requisicion+"&ejecutar=volverMontosOriginales");	
}