// JavaScript Document


function consultarVacaciones(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('listaVacaciones').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarVacaciones");			
}


function seleccionarModificar(fecha_salida, fecha_reintegro, comentarios, idvacaciones){
	document.getElementById('fecha_salida').value =  fecha_salida;
	document.getElementById('fecha_reintegro').value =  fecha_reintegro;
	document.getElementById('comentarios').value =  comentarios;
	document.getElementById('idvacaciones').value = idvacaciones;
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
}


function ingresarVacaciones(){
	if(document.getElementById('id_trabajador').value == ''){
		mostrarMensajes("error", "Disculpe debe seleccionar un trabajador");	
	}else{
	var fecha_salida = document.getElementById('fecha_salida').value;
	var fecha_reintegro = document.getElementById('fecha_reintegro').value;
	var comentarios = document.getElementById('comentarios').value;
	var idtrabajador = document.getElementById('id_trabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Las vacaciones fueron registradas con exito para el trabajador "+document.getElementById('nombre_trabajador').value+" "+document.getElementById('apellido_trabajador').value);	
			}else{
				mostrarMensajes("error", "Disculpe las vacaciones que intenta ingresar no fueron procesadas, por favor intente de nuevo mas tarde");	
			}
			consultarVacaciones(idtrabajador);
			document.formularioVacaciones.reset();
		} 
	}
	ajax.send("fecha_salida="+fecha_salida+"&fecha_reintegro="+fecha_reintegro+"&comentarios="+comentarios+"&idtrabajador="+idtrabajador+"&ejecutar=ingresarVacaciones");	
	}
}





function modificarVacaciones(){
	var fecha_salida = document.getElementById('fecha_salida').value;
	var fecha_reintegro = document.getElementById('fecha_reintegro').value;
	var comentarios = document.getElementById('comentarios').value;
	var idvacaciones = document.getElementById('idvacaciones').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Las vacaciones fueron modificadas con exito para el trabajador '"+document.getElementById('nombre_trabajador').value+" "+document.getElementById('apellido_trabajador').value+"'");	
			}else{
				mostrarMensajes("error", "Disculpe las vacaciones que intenta Modificar no fueron procesadas, por favor intente de nuevo mas tarde ... ("+ajax.responseText+")");	
			}
			consultarVacaciones(document.getElementById('id_trabajador').value);
			document.formularioVacaciones.reset();
			document.getElementById('boton_modificar').style.display = 'none';
			document.getElementById('boton_ingresar').style.display = 'block';
		} 
	}
	ajax.send("fecha_salida="+fecha_salida+"&fecha_reintegro="+fecha_reintegro+"&comentarios="+comentarios+"&idvacaciones="+idvacaciones+"&ejecutar=modificarVacaciones");		
}




function eliminarMovimiento(idvacaciones){
	if(confirm("Seguro desea eliminar estas vacaciones de este trabajador?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			consultarVacaciones(document.getElementById('id_trabajador').value);
		} 
	}
	ajax.send("idvacaciones="+idvacaciones+"&ejecutar=eliminarMovimiento");	
	}
}