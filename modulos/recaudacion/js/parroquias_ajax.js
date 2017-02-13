// JavaScript Document


function ingresarParroquias(){
	var descripcion = document.getElementById('descripcion').value;
	var idmunicipios = document.getElementById('idmunicipio').value;
	var externo = document.getElementById('externo').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/parroquias_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "La parroquia fue ingresada con exito");
				listarParroquias();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('parroquia', 'celda_parroquia', opener.document.getElementById('municipios').value, 'idmunicipios');
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			}
			
		}
		ajax.send("idmunicipios="+idmunicipios+"&descripcion="+descripcion+"&ejecutar=ingresarParroquias");
}





function modificarParroquias(){
	var idparroquias= document.getElementById('idparroquia').value;
	var idmunicipios = document.getElementById('idmunicipio').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/parroquias_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Parroquia modificada con exito");
				listarParroquias();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idmunicipios="+idmunicipios+"&idparroquias="+idparroquias+"&descripcion="+descripcion+"&ejecutar=modificarParroquias");
}


function eliminarParroquias(){
	if(confirm("¿Seguro desea Eliminar este Parroquias?")){
	var idparroquias = document.getElementById('idparroquia').value;
	var idmunicipios = document.getElementById('idmunicipio').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/parroquias_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe el Parroquias que intenta eliminar esta asociado a algun Municipio, por lo tanto no puede ser eliminado");
				}else{
					listarParroquias();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idmunicipios="+idmunicipios+"&idparroquias="+idparroquias+"&descripcion="+descripcion+"&ejecutar=eliminarParroquias");
	}
}




function listarParroquias(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/parroquias_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaParroquias').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarParroquias");
}





function seleccionarDatos(descripcion, idparroquias, idmunicipios){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idparroquia').value = idparroquias;
	document.getElementById('idmunicipio').value = idmunicipios;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	