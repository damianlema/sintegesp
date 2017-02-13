// JavaScript Document

function buscarOrdenes(numero_orden){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/centralizarOrden_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				//blanquearFormulario();
				document.getElementById("cuadroOrdenes").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_orden="+numero_orden+"&ejecutar=buscarOrdenes");	
}



function centralizarOrden(idorden_compra){
	if(confirm("Seguro desea reubicar esta orden en su sitio de origen ?")){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/centralizarOrden_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "La orden ah regresado a su posicion de origen, mas sin embargo aun sigue teniendo sus retenciones y de mas acciones aplicadas");	
				}else{
					mostrarMensajes("error", "Disculpe no se pudo centralizar la orden con exito");	
				}
				
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=centralizarOrden");	
	}
}