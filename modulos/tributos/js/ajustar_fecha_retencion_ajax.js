// JavaScript Document

function modificarFechaRetencion(id_orden_pago, fecha, botonModificar){
	var fecha = document.getElementById(fecha).value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/ajustar_fecha_retencion_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById(botonModificar).disabled = true;
			document.getElementById(botonModificar).value = "Cargando...";	
		}
		if (ajax.readyState==4){
				if(ajax.responseText == 'exito'){
					mostrarMensajes("exito", "La fecha fue modificada con Exito");
					document.getElementById(botonModificar).value = "Modificar";
					document.getElementById(botonModificar).disabled = false;
				}else{
					mostrarMensajes("error", "Error al Modificar, ERROR: "+ajax.responseText);
				}
		} 
	}
	ajax.send("id_orden_pago="+id_orden_pago+"&fecha="+fecha+"&ejecutar=modificarFechaRetencion");	
}




function buscarOrdenes(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var beneficiario = document.getElementById('beneficiario').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/ajustar_fecha_retencion_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaOrdenes").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("beneficiario="+beneficiario+"&campoBuscar="+campoBuscar+"&ejecutar=buscarOrdenes");
}