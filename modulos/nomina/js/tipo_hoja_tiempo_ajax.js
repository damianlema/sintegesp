// JavaScript Document



function ingresarHojaTiempo(){
	var descripcion = document.getElementById('descripcion').value;
	var unidad = document.getElementById('unidad').value;

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipo_hoja_tiempo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "La hoja de tiempo fue ingresada con exito");
				limpiarCampos();
				actualizarLista();
		} 
	}
	ajax.send("unidad="+unidad+"&descripcion="+descripcion+"&ejecutar=ingresarHojaTiempo");
}





function modificarHojaTiempo(){

	var idtipo_hoja_tiempo = document.getElementById('idtipo_hoja_tiempo').value;
	var descripcion = document.getElementById('descripcion').value;
	var unidad = document.getElementById('unidad').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipo_hoja_tiempo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "La hoja de tiempo se modifico con exito");
				limpiarCampos();
				document.getElementById('boton_ingresar').style.display = "block";
				document.getElementById('boton_eliminar').style.display = "none";
				document.getElementById('boton_modificar').style.display = "none";
				actualizarLista();
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("unidad="+unidad+"&idtipo_hoja_tiempo="+idtipo_hoja_tiempo+"&descripcion="+descripcion+"&ejecutar=modificarHojaTiempo");
	//return false;
}




function eliminarHojaTiempo(){
	var idtipo_hoja_tiempo = document.getElementById('idtipo_hoja_tiempo').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipo_hoja_tiempo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "asociada_concepto"){
					mostrarMensajes("error", "Disculpe No puede eliminar este tipo de hoja de tiempo debido a queestaasociado a una hoja de tiempo que esta dentro de un concepto de nomina");
					document.getElementById("divCargando").style.display = "none";
				}else{
					mostrarMensajes("exito", "La hoja de tiempo se Elimino con exito");
					limpiarCampos();
					document.getElementById('boton_ingresar').style.display = "block";
					document.getElementById('boton_eliminar').style.display = "none";
					document.getElementById('boton_modificar').style.display = "none";
					actualizarLista();	
					
				}
				
		} 
	}
	ajax.send("idtipo_hoja_tiempo="+idtipo_hoja_tiempo+"&ejecutar=eliminarHojaTiempo");
		
}








function actualizarLista(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipo_hoja_tiempo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('lista_conceptos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=actualizarLista");
		
}





function limpiarCampos(){
	document.getElementById('descripcion').value = "";
	document.getElementById('unidad').value = "";
	document.getElementById('boton_ingresar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";	
}



function mostrarModificar(idtipo_hoja_tiempo, descripcion, unidad){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('unidad').value = unidad;
	document.getElementById('idtipo_hoja_tiempo').value = idtipo_hoja_tiempo;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	
}



function mostrarEliminar(idtipo_hoja_tiempo, descripcion, unidad){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('unidad').value = unidad;
	document.getElementById('idtipo_hoja_tiempo').value = idtipo_hoja_tiempo;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
	document.getElementById('boton_modificar').style.display = "none";
	
}