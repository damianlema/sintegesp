// JavaScript Document


function modificarOrdenPago(id_orden_pago, fecha, botonModificar){
	var fecha = document.getElementById(fecha).value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/administracion/lib/modificar_fecha_op_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById(botonModificar).disabled = true;
			document.getElementById(botonModificar).value = "Cargando...";	
		}
		if (ajax.readyState==4){
				if(ajax.responseText == 'exito'){
					document.getElementById(botonModificar).value = "Modificar";
					document.getElementById(botonModificar).disabled = false;
					mostrarMensajes("exito", "Fecha Modificada con Exito");
				}else{
					mostrarMensajes("error", "Error al Modificar, ERROR: "+ajax.responseText);
				}
		} 
	}
	ajax.send("id_orden_pago="+id_orden_pago+"&fecha="+fecha+"&ejecutar=modificarFechaOrdenPago");	
}




function buscarOrdenes(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var tipoOrden = document.getElementById('tipoOrden').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/administracion/lib/modificar_fecha_op_ajax.php", true);
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