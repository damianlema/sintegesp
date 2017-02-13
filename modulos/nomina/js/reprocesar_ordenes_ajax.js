// JavaScript Document


function enviarOrden(){
	var numero_orden = document.getElementById("numero_orden").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/reprocesar_ordenes.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "La orden fue reprocesada con exito");
			document.getElementById("numero_orden").value = "";
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_orden="+numero_orden+"&ejecutar=enviarOrden");
}