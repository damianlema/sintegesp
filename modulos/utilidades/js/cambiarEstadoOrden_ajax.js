// JavaScript Document

function buscarOrdenes(){
	var numero_orden = document.getElementById('numero_orden').value;
	var beneficiario = document.getElementById('beneficiario').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/cambiarEstadoOrden_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				//blanquearFormulario();
				document.getElementById("listaOrdenes").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("beneficiario="+beneficiario+"&numero_orden="+numero_orden+"&ejecutar=buscarOrdenes");	
	return false;
}



function mostrarDetalles(id, tipo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/cambiarEstadoOrden_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				//blanquearFormulario();
				document.getElementById("mostrarDetalles").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id="+id+"&tipo="+tipo+"&ejecutar=mostrarDetalles");		
}




function cambiarEstado(id, tipo, nuevo_estado){
	if(confirm("Esta seguro que desea cambiar el estado de esta orden? recuerde que el cambio de la misma podria afectar el presupuesto y su disponibilidad")){
	var estado = document.getElementById(nuevo_estado).value;	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/cambiarEstadoOrden_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				buscarOrdenes();
				//document.getElementById("mostrarDetalles").innerHTML=ajax.responseText;
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("estado="+estado+"&id="+id+"&tipo="+tipo+"&ejecutar=cambiarEstado");	
	}
}