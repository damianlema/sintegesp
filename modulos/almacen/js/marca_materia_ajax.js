// JavaScript Document

function ingresarMarca(){
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
		ajax.open("POST", "modulos/almacen/lib/marca_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarMarca();
				}else if(ajax.responseText == "existe"){
					mostrarMensajes("error", "La Marca que intenta ingresar ya existe");
				}
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('marca_materia', 'celda_marca', denominacion);
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			} 
		}
		ajax.send("denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=ingresarMarca");
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


function listarMarca(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/marca_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaMarca').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarMarca");		
}




function modificarMarca(){
	var denominacion = document.getElementById('denominacion').value;
	var idmarca = document.getElementById('idmarca').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/marca_materia_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarMarca();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idmarca="+idmarca+"&denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=modificarMarca");
	}
}





function eliminarMarca(){
	var idmarca = document.getElementById('idmarca').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/marca_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se eliminaron los datos con exito");
					listarMarca();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idmarca="+idmarca+"&ejecutar=eliminarMarca");
}



function seleccionarModificar(idmarca, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idmarca').value = idmarca;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idmarca, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idmarca').value = idmarca;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}