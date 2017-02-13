// JavaScript Document

function buscarOrdenes(numero_orden){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/secrataria_gobierno/lib/ajustar_certificacion_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
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





function actualizarDatos(id_orden_compra, justificacion, ordenado_por, cedula_ordenado, nro_requisicion, fecha_requisicion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/secrataria_gobierno/lib/ajustar_certificacion_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				buscarOrdenes(document.getElementById('campoBuscar').value);
				blanquearFormulario();
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&justificacion="+justificacion+"&fecha_requisicion="+fecha_requisicion+"&nro_requisicion="+nro_requisicion+"&cedula_ordenado="+cedula_ordenado+"&ordenado_por="+ordenado_por+"&ejecutar=actualizarDatos");		
}








function mostrarDatos(id_orden_compra, 
					  	numero_orden, 
						justificacion, 
						ordenado_por,
						cedula_ordenado,
						numero_requisicion,
						fecha_requisicion){
	
	document.getElementById('numero_orden').innerHTML= '<strong>'+numero_orden+'</strong>'
	document.getElementById('id_orden_compra').value = id_orden_compra;
	document.getElementById('numero_orden').value = numero_orden;
	document.getElementById('justificacion').value = justificacion;
	document.getElementById('ordenado_por').value = ordenado_por;
	document.getElementById('cedula_ordenado').value = cedula_ordenado;
	document.getElementById('nro_requisicion').value = numero_documento;
	document.getElementById('fecha_requisicion').value = fecha_requisicion;
}



function blanquearFormulario(){
	document.getElementById('numero_orden').innerHTML= ''
	document.getElementById('id_orden_compra').value = "";
	document.getElementById('numero_orden').value = "";
	document.getElementById('justificacion').value = "";
	document.getElementById('ordenado_por').value = "";
	document.getElementById('cedula_ordenado').value = "";
	document.getElementById('nro_requisicion').value = "";
	document.getElementById('fecha_requisicion').value = "";
	document.getElementById('listaOrdenes').innerHTML = "";
}





