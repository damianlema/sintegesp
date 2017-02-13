// JavaScript Document

function ingresarTipo(){
	var iddetalle = document.getElementById('iddetalle').value;
	var tipo = document.getElementById('tipo').value;
	if(iddetalle == 0){
		mostrarMensajes("error", "Disculpe debe seleccionar un detalle");
	}else if(tipo == ''){
		mostrarMensajes("error", "Disculpe debe escribir el Tipo de Detalle");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/tipos_detalles_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "El tipo de detalle se ingreso con exito");
					listarTipos();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("iddetalle="+iddetalle+"&tipo="+tipo+"&ejecutar=ingresarTipo");
	}
}



function limpiarDatos(){
	document.getElementById('iddetalle').value = 0;
	document.getElementById('tipo').value = '';
	document.getElementById('iddetalle').disabled = false;
	document.getElementById('tipo').disabled = false;
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function listarTipos(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/tipos_detalles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaTiposDetalles').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarTipo");		
}




function modificarTipo(){
	var iddetalle = document.getElementById('iddetalle').value;
	var tipo = document.getElementById('tipo').value;
	var idtipo_detalle = document.getElementById('idtipo_detalle').value;
	
	if(iddetalle == 0){

		mostrarMensajes("error", "Disculpe debe seleccionar un detalle");
	}else if(tipo == ''){
		mostrarMensajes("error", "Disculpe debe escribir el Tipo de Detalle");

	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/tipos_detalles_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				mostrarMensajes("exito", "El tipo de detalle se modifico con exito");
					listarTipos();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idtipo_detalle="+idtipo_detalle+"&iddetalle="+iddetalle+"&tipo="+tipo+"&ejecutar=modificarTipo");
	}
}





function eliminarTipo(){
	var idtipo_detalle = document.getElementById('idtipo_detalle').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/tipos_detalles_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "El tipo de detalle elimino con exito");
					listarTipos();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idtipo_detalle="+idtipo_detalle+"&ejecutar=eliminarTipo");
}



function seleccionarModificar(idtipo_detalle, iddetalle, tipo){
	document.getElementById('iddetalle').value = iddetalle;
	document.getElementById('tipo').value = tipo;
	document.getElementById('idtipo_detalle').value = idtipo_detalle;
	document.getElementById('iddetalle').disabled = false;
	document.getElementById('tipo').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(idtipo_detalle, iddetalle, tipo){
	document.getElementById('iddetalle').value = iddetalle;
	document.getElementById('tipo').value = tipo;
	document.getElementById('idtipo_detalle').value = idtipo_detalle;
	document.getElementById('iddetalle').disabled = true;
	document.getElementById('tipo').disabled = true;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}