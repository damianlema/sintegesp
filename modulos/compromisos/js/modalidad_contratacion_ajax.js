// JavaScript Document



function ingresarHojaTiempo(){
	var descripcion = document.getElementById('descripcion').value;
	var codigo = document.getElementById('codigo').value;
	//alert(codigo);

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/modalidad_contratacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				limpiarCampos();
				actualizarLista();
		} 
	}
	ajax.send("descripcion="+descripcion+"&codigo="+codigo+"&ejecutar=ingresarHojaTiempo");
}





function modificarHojaTiempo(){
	var idmodalidad_contratacion = document.getElementById('idmodalidad_contratacion').value;
	var descripcion = document.getElementById('descripcion').value;
	var codigo= document.getElementById('codigo').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/modalidad_contratacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				limpiarCampos();
				document.getElementById('boton_ingresar').style.display = "block";
				document.getElementById('boton_eliminar').style.display = "none";
				document.getElementById('boton_modificar').style.display = "none";
				actualizarLista();
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmodalidad_contratacion="+idmodalidad_contratacion+"&codigo="+codigo+"&descripcion="+descripcion+"&ejecutar=modificarHojaTiempo");
	//return false;
}




function eliminarHojaTiempo(){
	var idmodalidad_contratacion = document.getElementById('idmodalidad_contratacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/modalidad_contratacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "asociada_concepto"){
					mostrarMensajes("error", "Disculpe No puede eliminar esta Modalidad de Contratacion");
					document.getElementById("divCargando").style.display = "none";
				}else{
					limpiarCampos();
					document.getElementById('boton_ingresar').style.display = "block";
					document.getElementById('boton_eliminar').style.display = "none";
					document.getElementById('boton_modificar').style.display = "none";
					actualizarLista();	
					
				}
				
		} 
	}
	ajax.send("idmodalidad_contratacion="+idmodalidad_contratacion+"&ejecutar=eliminarHojaTiempo");
		
}








function actualizarLista(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/compromisos/lib/modalidad_contratacion_ajax.php", true);	
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
	document.getElementById('idmodalidad_contratacion').value = "";
	document.getElementById('descripcion').value = "";
	document.getElementById('codigo').value = "";
	document.getElementById('boton_ingresar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";	
}



function mostrarModificar(idmodalidad_contratacion, descripcion, codigo){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('codigo').value = codigo;
	document.getElementById('idmodalidad_contratacion').value = idmodalidad_contratacion;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	
}



function mostrarEliminar(idmodalidad_contratacion, descripcion, codigo){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('codigo').value = codigo;
	document.getElementById('idmodalidad_contratacion').value = idmodalidad_contratacion;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
	document.getElementById('boton_modificar').style.display = "none";
	
}