// JavaScript Document


function ingresarActividad(){
	var descripcion = document.getElementById('descripcion').value;
	var alicuota = document.getElementById('alicuota').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/actividades_comerciales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarActividades();
				document.getElementById('descripcion').value = '';
				document.getElementById('alicuota').value = '';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("descripcion="+descripcion+"&alicuota="+alicuota+"&ejecutar=ingresarActividad");
}





function modificarActividad(){
	var idactividades_comerciales = document.getElementById('idactividades_comerciales').value;
	var descripcion = document.getElementById('descripcion').value;
	var alicuota = document.getElementById('alicuota').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/actividades_comerciales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarActividades();
				document.getElementById('descripcion').value = '';
				document.getElementById('alicuota').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idactividades_comerciales="+idactividades_comerciales+"&descripcion="+descripcion+"&alicuota="+alicuota+"&ejecutar=modificarActividad");
}


function eliminarActividad(){
	if(confirm("¿Seguro desea Eliminar Esta Actividad Comercial?")){
	var idactividades_comerciales = document.getElementById('idactividades_comerciales').value;
	var descripcion = document.getElementById('descripcion').value;
	var alicuota = document.getElementById('alicuota').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/actividades_comerciales_ajax.php", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe La actividad que intenta eliminar esta asociada a algun contribuyente, por lo tanto no puede ser eliminada");
				}else{
					listarActividades();
					document.getElementById('descripcion').value = '';
					document.getElementById('alicuota').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idactividades_comerciales="+idactividades_comerciales+"&descripcion="+descripcion+"&alicuota="+alicuota+"&ejecutar=eliminarActividad");
	}
}




function listarActividades(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/actividades_comerciales_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaActividades').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarActividades");
}





function seleccionarDatos(descripcion, alicuota, idactividades_comerciales){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('alicuota').value = alicuota;
	document.getElementById('idactividades_comerciales').value = idactividades_comerciales;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	