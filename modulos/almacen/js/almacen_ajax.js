// JavaScript Document

function ingresarAlmacen(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var ubicacion = document.getElementById('ubicacion').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/almacen_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarAlmacen();
				}else if(ajax.responseText == "existe"){
					mostrarMensajes("error", "El Almacen que intenta ingresar ya existe");
				}
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("codigo="+codigo+"&denominacion="+denominacion+"&ubicacion="+ubicacion+"&preseleccion="+preseleccion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&telefono="+telefono+"&email="+email+"&ejecutar=ingresarAlmacen");
	}
}



function limpiarDatos(){
	document.getElementById('codigo').value = '';
	document.getElementById('denominacion').value = '';
	document.getElementById('ubicacion').value = '';
	document.getElementById('preseleccion').checked = false;
	document.getElementById('responsable').value = '';
	document.getElementById('ci_responsable').value = '';
	document.getElementById('telefono').value = '';
	document.getElementById('email').value = '';
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('ubicacion').disabled = false;
	document.getElementById('responsable').disabled = false;
	document.getElementById('ci_responsable').disabled = false;
	document.getElementById('telefono').disabled = false;
	document.getElementById('email').disabled = false;
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function listarAlmacen(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/almacen_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaAlmacen').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarAlmacen");		
}




function modificarAlmacen(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var ubicacion = document.getElementById('ubicacion').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	var idalmacen = document.getElementById('idalmacen').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/almacen_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarAlmacen();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idalmacen="+idalmacen+"&codigo="+codigo+"&denominacion="+denominacion+"&ubicacion="+ubicacion+"&preseleccion="+preseleccion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&telefono="+telefono+"&email="+email+"&ejecutar=modificarAlmacen");
	}
}





function eliminarAlmacen(){
	var idalmacen = document.getElementById('idalmacen').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/almacen_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if (ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se eliminaron los datos con exito");
				}
				if (ajax.responseText == "existe"){
					mostrarMensajes("error", "No se puede eliminar el almacen porque se encuentra en uso");
				}
				listarAlmacen();
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idalmacen="+idalmacen+"&ejecutar=eliminarAlmacen");
}



function seleccionarModificar(idalmacen, codigo, denominacion, defecto, responsable, ci_responsable, telefono, email, ubicacion ){
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('ubicacion').value = ubicacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('ci_responsable').value = ci_responsable;
	document.getElementById('telefono').value = telefono;
	document.getElementById('email').value = email;
	if (defecto == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idalmacen').value = idalmacen;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('ubicacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idalmacen, codigo, denominacion, defecto, responsable, ci_responsable, telefono, email, ubicacion ){
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('ubicacion').value = ubicacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('ci_responsable').value = ci_responsable;
	document.getElementById('telefono').value = telefono;
	document.getElementById('email').value = email;
	if (defecto == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idalmacen').value = idalmacen;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('ubicacion').disabled = true;
	document.getElementById('responsable').disabled = true;
	document.getElementById('ci_responsable').disabled = true;
	document.getElementById('telefono').disabled = true;
	document.getElementById('email').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}