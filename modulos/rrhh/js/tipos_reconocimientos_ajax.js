// JavaScript Document


function ingresarTiposSanciones(){
	var descripcion = document.getElementById('descripcion').value;
	var ajax		= nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tipos_reconocimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var partes = ajax.responseText.split("|.|");
			if(partes[0] == "exito"){
				mostrarMensajes("exito", "El tipo de Sancion se guardo con exito");	
				document.getElementById('idtipo_reconocimientos').value = partes[1];
			}else{
				mostrarMensajes("error", "Disculpe existio un error al momento de intentar cargar el tipo de sancion, por favor intente de nuevo mas tarde (ERROR: "+partes[1]+")");	
			}
			consultarSanciones();
			document.getElementById('descripcion').value = '';
		} 
	}
	ajax.send("descripcion="+descripcion+"&ejecutar=ingresarTiposSanciones");	
}



function modificarTiposSanciones(){
	var idtipo_reconocimientos 	= document.getElementById('idtipo_reconocimientos').value;
	var descripcion 		= document.getElementById('descripcion').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tipos_reconocimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			if(partes[0] == "exito"){
				mostrarMensajes("exito", "El tipo de Sancion se modifico con exito");	
				document.getElementById('idtipo_reconocimientos').value = partes[1];
			}else{
				mostrarMensajes("error", "Disculpe existio un error al momento de intentar modificar el tipo de sancion, por favor intente de nuevo mas tarde (ERROR: "+partes[1]+")");	
			}
			consultarSanciones();
			document.getElementById('descripcion').value = '';
			document.getElementById('boton_ingresar_tipos_reconocimientos').style.display = 'block';
			document.getElementById('boton_modificar_tipos_reconocimientos').style.display = 'none';
		} 
	}
	ajax.send("idtipo_reconocimientos="+idtipo_reconocimientos+"&descripcion="+descripcion+"&ejecutar=modificarTiposSanciones");	
}




function consultarSanciones(){
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tipos_reconocimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('listaTiposSanciones').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=consultarSanciones");	
		
}


function seleccionarModificar(idtipo_reconocimientos, denominacion){
	document.getElementById('idtipo_reconocimientos').value = idtipo_reconocimientos;
	document.getElementById('descripcion').value = denominacion;
	document.getElementById('boton_ingresar_tipos_reconocimientos').style.display = 'none';
	document.getElementById('boton_modificar_tipos_reconocimientos').style.display = 'block';
}




function eliminarSanciones(idtipo_reconocimientos){
	if(confirm("Seguro desea eliminar el tipo de sancion que selecciono?")){
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tipos_reconocimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText != "exito"){
				mostrarMensajes("error", "Disculpe el tipo de sancion no se pudo eliminar con exito, por favor intentelo de nuevo mas tarde, (ERROR: "+ajax.responseText+")");
			}
			consultarSanciones();
		} 
	}
	ajax.send("idtipo_reconocimientos="+idtipo_reconocimientos+"&ejecutar=eliminarSanciones");	
	}
		
}