// JavaScript Document


function ingresarEstado(){
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/estados_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarEstado();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("descripcion="+descripcion+"&ejecutar=ingresarEstado");
}





function modificarEstado(){
	var idestado= document.getElementById('idestado').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/estados_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Estado modificado con Exito");
				listarEstado();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idestado="+idestado+"&descripcion="+descripcion+"&ejecutar=modificarEstado");
}








function eliminarEstado(){
	if(confirm("¿Seguro desea Eliminar este Estado?")){
	var idestado = document.getElementById('idestado').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/estados_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", ""Disculpe el Estado que intenta eliminar esta asociado a algun Municipio, por lo tanto no puede ser eliminado);
				}else{
					listarEstado();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idestado="+idestado+"&descripcion="+descripcion+"&ejecutar=eliminarEstado");
	}
}




function listarEstado(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/estados_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaEstado').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarEstado");
}





function seleccionarDatos(descripcion, idestado){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idestado').value = idestado;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	