// JavaScript Document

function eliminarOrdenCompra(id_orden_compra, botonEliminar){

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/eliminar_en_elaboracion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById(botonEliminar).disabled = true;
			document.getElementById(botonEliminar).value = "Cargando...";	
		}
		if (ajax.readyState==4){
				if(ajax.responseText == 'exito'){
					document.getElementById("divMensaje").innerHTML="Orden Eliminada con Exito";
					buscarOrdenes();
				}else{
					mostrarMensajes("error", "Error al Eliminar, ERROR: "+ajax.responseText);
				}
		} 
		document.getElementById(botonEliminar).value = "Eliminar";
		document.getElementById(botonEliminar).disabled = false;
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&ejecutar=eliminarOrdenCompra");	
}




function buscarOrdenes(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/eliminar_en_elaboracion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaOrdenes").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("campoBuscar="+campoBuscar+"&ejecutar=buscarOrdenes");
}