// JavaScript Document

function modificarOrdenCompra(id_orden_compra, fecha, botonModificar){
	var fecha = document.getElementById(fecha).value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/ajustar_apertura_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById(botonModificar).disabled = true;
			document.getElementById(botonModificar).value = "Cargando...";	
		}
		if (ajax.readyState==4){
				if(ajax.responseText == 'exito'){
					document.getElementById("divMensaje").innerHTML="Fecha Modificada con Exito";
					document.getElementById(botonModificar).value = "Modificar";
					document.getElementById(botonModificar).disabled = false;
				}else{
					mostrarMensajes("error", "Error al Modificar, ERROR: "+ajax.responseText);
				}
		} 
	}
	ajax.send("id_orden_compra="+id_orden_compra+"&fecha="+fecha+"&ejecutar=modificarFechaOrdenCompra");	
}




function buscarOrdenes(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var tipoOrden = document.getElementById('tipoOrden').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/ajustar_apertura_ajax.php", true);
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
	ajax.send("tipoOrden="+tipoOrden+"&campoBuscar="+campoBuscar+"&ejecutar=buscarOrdenes");
}