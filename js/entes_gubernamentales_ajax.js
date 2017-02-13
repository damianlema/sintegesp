// JavaScript Document

function mostrarModificar(nombre, director, cargod, administrador, cargoa, id_ente){
	document.getElementById('nombre').value = nombre;
	document.getElementById('director').value = director;
	document.getElementById('cargod').value = cargod;
	document.getElementById('administrador').value = administrador;
	document.getElementById('cargoa').value = cargoa;
	document.getElementById('id_ente_gubernamental').value = id_ente;
	document.getElementById('boton_guardar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';
}

function mostrarEliminar(nombre, director, cargod, administrador, cargoa, id_ente){
	document.getElementById('nombre').value = nombre;
	document.getElementById('director').value = director;
	document.getElementById('cargod').value = cargod;
	document.getElementById('administrador').value = administrador;
	document.getElementById('cargoa').value = cargoa;
	document.getElementById('id_ente_gubernamental').value = id_ente;
	document.getElementById('boton_guardar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';
	document.getElementById('nombre').disabled=true;
	document.getElementById('director').disabled=true;
	document.getElementById('cargod').disabled=true;
	document.getElementById('administrador').disabled=true;
	document.getElementById('cargoa').disabled=true;
}

function ingresarEnte(){
		var nombre = document.getElementById('nombre').value;
		var director = document.getElementById('director').value;
		var cargod = document.getElementById('cargod').value;
		var administrador = document.getElementById('administrador').value;
		var cargoa = document.getElementById('cargoa').value;
		
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/entes_gubernamentales/entes_gubernamentales_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				listarEntes();
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("nombre="+nombre+"&director="+director+"&cargod="+cargod+"&administrador="+administrador+"&cargoa="+cargoa+"&ejecutar=ingresarEnte");	
}


function listarEntes(){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/entes_gubernamentales/entes_gubernamentales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('listaEntesGubernamentales').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("ejecutar=listarEntes");
}


function modificarEnte(){
	var nombre = document.getElementById('nombre').value;
	var director = document.getElementById('director').value;
	var cargod = document.getElementById('cargod').value;
	var administrador = document.getElementById('administrador').value;
	var cargoa = document.getElementById('cargoa').value;
	var id_ente = document.getElementById('id_ente_gubernamental').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/entes_gubernamentales/entes_gubernamentales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarEntes();
			limpiarDatos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("id_ente="+id_ente+"&nombre="+nombre+"&director="+director+"&cargod="+cargod+"&administrador="+administrador+"&cargoa="+cargoa+"&ejecutar=modificarEnte");
}


function eliminarEnte(){
	id_ente = document.getElementById('id_ente_gubernamental').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/entes_gubernamentales/entes_gubernamentales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el Ente seleccionado no puede ser eliminado ya que el mismo esta siendo usado desde una retencion");
			}else{
				listarEntes();	
			}
			limpiarDatos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("id_ente="+id_ente+"&ejecutar=eliminarEnte");
}


function limpiarDatos(){
	document.getElementById('id_ente_gubernamental').value = "";
	document.getElementById('nombre').value = "";
	document.getElementById('director').value = "";
	document.getElementById('cargod').value = "";
	document.getElementById('administrador').value = "";
	document.getElementById('cargoa').value = "";
	document.getElementById('boton_guardar').style.display = "block";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('nombre').style.display = "block";
}