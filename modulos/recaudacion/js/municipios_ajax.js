// JavaScript Document


function ingresarMunicipios(){
	var descripcion = document.getElementById('descripcion').value;
	var idestado = document.getElementById('idestado').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/municipios_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Municipio agregado con exito");
				listarMunicipios();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idestado="+idestado+"&descripcion="+descripcion+"&ejecutar=ingresarMunicipios");
}





function modificarMunicipios(){
	var idmunicipios= document.getElementById('idmunicipio').value;
	var idestado = document.getElementById('idestado').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/municipios_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "El municipio fue modificado con exito");
				listarMunicipios();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idestado="+idestado+"&idmunicipios="+idmunicipios+"&descripcion="+descripcion+"&ejecutar=modificarMunicipios");
}


function eliminarMunicipios(){
	if(confirm("¿Seguro desea Eliminar este Municipios?")){
	var idmunicipios = document.getElementById('idmunicipio').value;
	var idestado = document.getElementById('idestado').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/municipios_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe el Municipios que intenta eliminar esta asociado a algun Municipio, por lo tanto no puede ser eliminado");
				}else{
					listarMunicipios();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idestado="+idestado+"&idmunicipios="+idmunicipios+"&descripcion="+descripcion+"&ejecutar=eliminarMunicipios");
	}
}




function listarMunicipios(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/municipios_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaMunicipios').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarMunicipios");
}





function seleccionarDatos(descripcion, idmunicipios, idestado){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idmunicipio').value = idmunicipios;
	document.getElementById('idestado').value = idestado;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	