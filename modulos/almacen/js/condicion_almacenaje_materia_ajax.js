// JavaScript Document

function ingresarCondicion(){
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
		ajax.open("POST", "modulos/almacen/lib/condicion_almacenaje_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarCondicion();
				}else if(ajax.responseText == "existe"){
					mostrarMensajes("error", "La Condicion que intenta ingresar ya existe");
				}
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('condicion_almacenaje_materia', 'celda_condicion_almacenaje', denominacion);
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			} 
		}
		ajax.send("denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=ingresarCondicion");
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


function listarCondicion(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/condicion_almacenaje_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaCondicion').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarCondicion");		
}




function modificarCondicion(){
	var denominacion = document.getElementById('denominacion').value;
	var idcondicion_almacenaje = document.getElementById('idcondicion_almacenaje').value;
	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		if (document.getElementById('preseleccion').checked == true){
			var preseleccion = 1;
		}else{
			var preseleccion = 0;
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/condicion_almacenaje_materia_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarCondicion();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idcondicion_almacenaje="+idcondicion_almacenaje+"&denominacion="+denominacion+"&preseleccion="+preseleccion+"&ejecutar=modificarCondicion");
	}
}





function eliminarCondicion(){
	var idcondicion_almacenaje = document.getElementById('idcondicion_almacenaje').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/condicion_almacenaje_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Se eliminaron los datos con exito");
					listarCondicion();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idcondicion_almacenaje="+idcondicion_almacenaje+"&ejecutar=eliminarCondicion");
}



function seleccionarModificar(idcondicion_almacenaje, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idcondicion_almacenaje').value = idcondicion_almacenaje;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idcondicion_almacenaje, denominacion, seleccionada){
	document.getElementById('denominacion').value = denominacion;
	if (seleccionada == 1){
		document.getElementById('preseleccion').checked = true;
	}else{
		document.getElementById('preseleccion').checked = false;
	}
	document.getElementById('idcondicion_almacenaje').value = idcondicion_almacenaje;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}