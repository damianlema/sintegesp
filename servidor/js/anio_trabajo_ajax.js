// JavaScript Document



function generarAnio(){
	if(confirm("Realmente desea generar el Periodo seleccionado?")){
		var anio_a_cambiar= document.getElementById("anio").value;
		var ajax=nuevoAjax();
		ajax.open("POST", "servidor/lib/anio_trabajo_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				
				document.getElementById("divResultado").innerHTML = "<br><img src='imagenes/cargando.gif' width = '16' height = '16'> Por favor espere, este proceso puede tardar <b>VARIOS MINUTOS!</b><br><br>Por favor no ejecute ninguna otra accion en el sistema hasta que el proceso culmine<br><br>";
				
			}
			if (ajax.readyState==4){
				document.getElementById("divResultado").innerHTML = ajax.responseText;
			} 
		}
		ajax.send("anio_a_cambiar="+anio_a_cambiar+"&fecha_cierre="+fecha_cierre+"&ejecutar=generarAnio");	
	}	
}