// JavaScript Document


function ingresarCarrera(){
	var descripcion = document.getElementById('descripcion').value;
	var idcalle = document.getElementById('idcalle').value;
		var externo = document.getElementById('externo').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/carrera_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarCarrera();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
								if(externo == "si"){
					opener.cargarSelect('calle', 'celda_calle', opener.document.getElementById('urbanizacion').value, 'idurbanizacion');
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			}
			
		}
		ajax.send("idcalle="+idcalle+"&descripcion="+descripcion+"&ejecutar=ingresarCarrera");
}





function modificarCarrera(){
	var idcarrera= document.getElementById('idcarrera').value;
	var idcalle = document.getElementById('idcalle').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/carrera_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarCarrera();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcarrera="+idcarrera+"&idcalle="+idcalle+"&descripcion="+descripcion+"&ejecutar=modificarCarrera");
}


function eliminarCarrera(){
	if(confirm("¿Seguro desea Eliminar esta Carrera?")){
	var idcalle = document.getElementById('idcalle').value;
	var idcarrera = document.getElementById('idcarrera').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/carrera_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe la Urbanizacion que intenta eliminar esta asociada a algun Sector, por lo tanto no puede ser eliminada");
				}else{
					listarCarrera();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idcarrera="+idcarrera+"&idcalle="+idcalle+"&descripcion="+descripcion+"&ejecutar=eliminarCarrera");
	}
}




function listarCarrera(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/carrera_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaCarrera').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarCarrera");
}





function seleccionarDatos(descripcion, idcarrera, idcalle){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idcarrera').value = idcarrera;
	document.getElementById('idcalle').value = idcalle;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	