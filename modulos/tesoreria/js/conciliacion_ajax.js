// JavaScript Document

function conciliarPago(id_pago_financiero, botonConciliar){
	fecha_conciliado = document.getElementById('fecha'+id_pago_financiero).value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/conciliacion_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById(botonConciliar).disabled = true;
			document.getElementById(botonConciliar).value = "Cargando...";	
		}
		if (ajax.readyState==4){
				if(ajax.responseText == 'exito'){
					mostrarMensajes("exito", "Pago Conciliado con exito");
					buscarPagos();
				}else{
					mostrarMensajes("error", "Error al Conciliar, ERROR: "+ajax.responseText);
				}
		} 
		document.getElementById(botonConciliar).value = "Conciliar";
		document.getElementById(botonConciliar).disabled = false;
	}
	ajax.send("id_pago_financiero="+id_pago_financiero+"&fecha_conciliado="+fecha_conciliado+"&ejecutar=conciliarPago");	
}


function actualizar_fecha(id_pago_financiero){
	fecha_conciliado = document.getElementById('fecha'+id_pago_financiero).value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/conciliacion_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		
	}
	ajax.send("id_pago_financiero="+id_pago_financiero+"&fecha_conciliado="+fecha_conciliado+"&ejecutar=actualizar_fecha");	
}





function devolverConciliacion(id_pago_financiero, botonConciliar){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/conciliacion_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById(botonConciliar).disabled = true;
			document.getElementById(botonConciliar).value = "Cargando...";	
		}
		if (ajax.readyState==4){
				if(ajax.responseText == 'exito'){
					mostrarMensajes("exito", "Conciliacion Devuelta con Exito");
					buscarPagos();
				}else{
					mostrarMensajes("error", "Error al Devolver, ERROR: "+ajax.responseText);
				}
		} 
		document.getElementById(botonConciliar).value = "Devolver";
		document.getElementById(botonConciliar).disabled = false;
	}
	ajax.send("id_pago_financiero="+id_pago_financiero+"&ejecutar=devolverConciliacion");	
}








function buscarPagos(){
	var banco = document.getElementById('banco').value;
	var cuenta = document.getElementById('cuenta').value;
	var estado = document.getElementById('estado').value;
	//alert (cuenta);
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/conciliacion_ajax.php", true);	
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
	ajax.send("banco="+banco+"&estado="+estado+"&cuenta="+cuenta+"&ejecutar=buscarPagos");
}




function cargarCuentasBancarias(banco){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/conciliacion_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('celdaCuentaBancaria').innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("banco="+banco+"&ejecutar=cargarCuentasBancarias");
}