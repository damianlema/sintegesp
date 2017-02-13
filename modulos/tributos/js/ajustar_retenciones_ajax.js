// JavaScript Document

function listarCompromisos(){
	var numero_orden = document.getElementById('numero_orden').value;
	var beneficiario = document.getElementById('beneficiario').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/ajustar_retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaCompromisos").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("beneficiario="+beneficiario+"&numero_orden="+numero_orden+"&ejecutar=listarCompromisos");
	return false;
}




function mostrarRetenciones(idorden_compra_servicio){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/ajustar_retenciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaRetenciones").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra_servicio="+idorden_compra_servicio+"&ejecutar=mostrarRetenciones");	
}




function actualizarCodigoConcepto(idrelacion_retenciones, codigo_concepto){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/ajustar_retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById("divCargando").style.display = "none";
				mostrarMensajes("exito", "Codigo de Concepto Actualizado");
		} 
	}
	ajax.send("codigo_concepto="+codigo_concepto+"&idrelacion_retenciones="+idrelacion_retenciones+"&ejecutar=actualizarCodigoConcepto");
}




function actualizarFechaNro(nro_factura, nro_control, fecha_factura, id_retenciones){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/ajustar_retenciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "Se actualizaron los datos con Exito");
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("nro_factura="+nro_factura+"&nro_control="+nro_control+"&fecha_factura="+fecha_factura+"&id_retenciones="+id_retenciones+"&ejecutar=actualizarFechaNro");			
}