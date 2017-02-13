// JavaScript Document

function validarFechas(desde, hasta){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/experiencia_laboral_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('tiempo_srv').value = ajax.responseText;
			boton(ajax.responseText);
		} 
	}
	ajax.send("hasta="+hasta+"&desde="+desde+"&ejecutar=validarFechas");		
}

function boton(estado){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/experiencia_laboral_ajax.php", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "anterior"){
				document.getElementById('boton_modificar').disabled = true;	
				document.getElementById('boton_modificar').value = "Seleccione Fechas Validas";	
				
				document.getElementById('boton_ingresar').disabled = true;	
				document.getElementById('boton_ingresar').value = "Seleccione Fechas Validas";	
			}else{
				document.getElementById('boton_modificar').disabled = false;
				document.getElementById('boton_modificar').value = "Modificar";
				
				document.getElementById('boton_ingresar').disabled = false;	
				document.getElementById('boton_ingresar').value = "Ingresar";	
				
			}
			
		} 
	}
	ajax.send("estado="+estado+"&ejecutar=boton");		
}