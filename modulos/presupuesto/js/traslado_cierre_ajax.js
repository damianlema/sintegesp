// JavaScript Document

function ingresarTraslado(){
		var ajax=nuevoAjax();
		var nro_solicitud = document.getElementById('nro_solicitud').value;
		var fecha_solicitud = document.getElementById('fecha_solicitud').value;
		var nro_resolucion = document.getElementById('nro_solicitud').value;
		var fecha_resolucion = document.getElementById('fecha_resolucion').value;
		var justificacion = document.getElementById('justificacion').value;
		var anio = document.getElementById('anio').value;
		ajax.open("POST", "modulos/presupuesto/lib/traslado_cierre_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('listarTraslados').innerHTML = ajax.responseText;
					document.getElementById('boton_ingresar').style.display = "none";
					document.getElementById('boton_reiniciar').style.display = "none";
					mostrarMensajes("exito", "Su traslado se genero exitosamente, para mas opciones sobre el mismo diríjase al modulo de traslados");
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("nro_solicitud="+nro_solicitud+"&fecha_solicitud="+fecha_solicitud+"&nro_resolucion="+nro_resolucion+"&fecha_resolucion="+fecha_resolucion+"&justificacion="+justificacion+"&anio="+anio+"&ejecutar=ingresarTraslado");
}