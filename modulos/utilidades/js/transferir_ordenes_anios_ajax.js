// JavaScript Document


function consultarOrdenes(){
		var anio_destino = document.getElementById('anio_destino').value;
		var tipo_orden = document.getElementById('tipo_orden').value;
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/utilidades/lib/transferir_ordenes_anios_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() 
		{ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('celda_numero_orden').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("anio_destino="+anio_destino+"&tipo_orden="+tipo_orden+"&ejecutar=consultarOrdenes");
}



function mostrarDatos(numero_orden, id){
	document.getElementById('boton_procesar').style.display='block';	
}



function procesarOrden(){
	
		var anio_destino = document.getElementById('anio_destino').value;
		var tipo_orden = document.getElementById('tipo_orden').value;
		var numero_orden = document.getElementById('numero_orden').value;
	
			var ajax=nuevoAjax();
		ajax.open("POST", "modulos/utilidades/lib/transferir_ordenes_anios_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() 
		{ 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				alert(ajax.responseText);
				mostrarMensajes("exito", "Se procesaron los datos con exito");
				//document.getElementById('celda_numero_orden').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("numero_orden="+numero_orden+"&tipo_orden="+tipo_orden+"&anio_destino="+anio_destino+"&ejecutar=procesarOrden");	
}