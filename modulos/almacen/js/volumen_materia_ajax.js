// JavaScript Document

function ingresarVolumen(){
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
		ajax.open("POST", "modulos/almacen/lib/volumen_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarVolumen();
				}else if(ajax.responseText == "existe"){
					mostrarMensajes("error", "El volumen que intenta ingresar ya existe");
				}
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('volumen_materia', 'celda_volumen', denominacion);
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			} 
		}
		ajax.send("denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=ingresarVolumen");
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


function listarVolumen(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/volumen_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaVolumenes').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarVolumen");		
}




function modificarVolumen(){
	var denominacion = document.getElementById('denominacion').value;
	var idvolumen = document.getElementById('idvolumen').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/volumen_materia_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarVolumen();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idvolumen="+idvolumen+"&denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=modificarVolumen");
	}
}





function eliminarVolumen(){
	var idvolumen = document.getElementById('idvolumen').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/volumen_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se eliminaron los datos con exito");
					listarVolumen();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idvolumen="+idvolumen+"&ejecutar=eliminarVolumen");
}



function seleccionarModificar(idvolumen, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idvolumen').value = idvolumen;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idvolumen, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idvolumen').value = idvolumen;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}