// JavaScript Document

function ingresarForma(){
	var denominacion = document.getElementById('denominacion').value;
	var externo = document.getElementById('externo').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/forma_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarForma();
				}else if(ajax.responseText == "existe"){
					mostrarMensajes("error", "La Forma que intenta ingresar ya existe");
				}
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('forma_materia', 'celda_forma', denominacion);
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			} 
		}
		ajax.send("denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=ingresarForma");
	}
}



function limpiarDatos(){
	document.getElementById('denominacion').value = '';
	document.getElementById('preseleccion').checked = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function listarForma(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/forma_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaForma').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarForma");		
}




function modificarForma(){
	var denominacion = document.getElementById('denominacion').value;
	var idforma = document.getElementById('idforma').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/forma_materia_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarForma();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idforma="+idforma+"&denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=modificarForma");
	}
}





function eliminarForma(){
	var idforma = document.getElementById('idforma').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/forma_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se eliminaron los datos con exito");
					listarForma();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idforma="+idforma+"&ejecutar=eliminarForma");
}



function seleccionarModificar(idforma, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idforma').value = idforma;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idforma, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idforma').value = idforma;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}