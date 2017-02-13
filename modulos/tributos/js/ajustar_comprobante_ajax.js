// JavaScript Document

function cambiar_numero_comprobante(idretenciones,idtipo_retencion,idorden_pago,idrelacion_retenciones, numero_orden){
	
		numero_comprobante = document.getElementById('numero_comprobante'+idretenciones).value;
		fecha_comprobante = document.getElementById('fecha'+idretenciones).value;
		numero_factura = document.getElementById('nro_factura'+idretenciones+''+idrelacion_retenciones).value;
		numero_control = document.getElementById('nro_control'+idretenciones+''+idrelacion_retenciones).value;
		fecha_factura = document.getElementById('fecha_factura'+idretenciones+''+idrelacion_retenciones).value;
		codigo_concepto = document.getElementById('codigo'+idretenciones+''+idrelacion_retenciones).value;
		if (idorden_pago == 0){
			numero_orden_e = document.getElementById('numero_orden'+idretenciones+''+idrelacion_retenciones).value;
		}else{
			numero_orden_e = 0;
		}
		//idrelacion_retenciones = document.getElementById('idrelacion_retenciones'+idrelacion_retenciones).value;
		alert(numero_factura);
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tributos/lib/ajustar_comprobante_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert (ajax.responseText);
				document.getElementById('numero_comprobante'+idretenciones).value = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idretenciones="+idretenciones+"&idtipo_retencion="+idtipo_retencion+"&idorden_pago="+idorden_pago+"&numero_comprobante="+numero_comprobante+"&fecha_comprobante="+fecha_comprobante+"&numero_factura="+numero_factura+"&numero_control="+numero_control+"&fecha_factura="+fecha_factura+"&idrelacion_retenciones="+idrelacion_retenciones+"&codigo_concepto="+codigo_concepto+"&numero_orden_e="+numero_orden_e+"&ejecutar=cambiar_numero_comprobante");
}
