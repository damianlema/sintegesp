// JavaScript Document


function cambiarMunicipios(idestado){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/organizacion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById("celda_municipio").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idestado="+idestado+"&ejecutar=cambiarMunicipios");	  
}


function ingresarOrganizacion(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var estado = document.getElementById('estado').value;
	var municipio = document.getElementById('municipio').value;
	var direccion = document.getElementById('direccion').value;
	var telefonos = document.getElementById('telefonos').value;
	var email = document.getElementById('email').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/organizacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Los datos fueron cargados con exito");
			limpiarDatos();
			listarOrganizaciones();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("email="+email+"&telefonos="+telefonos+"&direccion="+direccion+"&municipio="+municipio+"&estado="+estado+"&responsable="+responsable+"&denominacion="+denominacion+"&codigo="+codigo+"&ejecutar=ingresarOrganizacion");
}



function limpiarDatos(){
	document.getElementById('codigo').value = '';
	document.getElementById('denominacion').value = '';
	document.getElementById('responsable').value = '';
	document.getElementById('estado').value = 0;
	document.getElementById('municipio').value = 0;
	document.getElementById('direccion').value = '';
	document.getElementById('telefonos').value = '';
	document.getElementById('email').value = '';
	
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('responsable').disabled = false;
	document.getElementById('estado').disabled = false;
	document.getElementById('municipio').disabled = false;
	document.getElementById('direccion').disabled = false;
	document.getElementById('telefonos').disabled = false;
	document.getElementById('email').disabled = false;	
	
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';	
}


function modificarOrganizacion(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var estado = document.getElementById('estado').value;
	var municipio = document.getElementById('municipio').value;
	var direccion = document.getElementById('direccion').value;
	var telefonos = document.getElementById('telefonos').value;
	var email = document.getElementById('email').value;
	var idorganizacion = document.getElementById('idorganizacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/organizacion_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Los datos fueron modificados con exito");
			limpiarDatos();
			listarOrganizaciones();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&email="+email+"&telefonos="+telefonos+"&direccion="+direccion+"&municipio="+municipio+"&estado="+estado+"&responsable="+responsable+"&denominacion="+denominacion+"&codigo="+codigo+"&ejecutar=modificarOrganizacion");	
}



function eliminarOrganizacion(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var estado = document.getElementById('estado').value;
	var municipio = document.getElementById('municipio').value;
	var direccion = document.getElementById('direccion').value;
	var telefonos = document.getElementById('telefonos').value;
	var email = document.getElementById('email').value;
	var idorganizacion = document.getElementById('idorganizacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/organizacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			limpiarDatos();
			listarOrganizaciones();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&email="+email+"&telefonos="+telefonos+"&direccion="+direccion+"&municipio="+municipio+"&estado="+estado+"&responsable="+responsable+"&denominacion="+denominacion+"&codigo="+codigo+"&ejecutar=eliminarOrganizacion");		
}



function seleccionarModificar(idorganizacion, codigo, denominacion, responsable, estado, municipio, direccion, telefonos, email){
	limpiarDatos();
	cambiarMunicipios(estado);

	document.getElementById('idorganizacion').value = idorganizacion;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('estado').value = estado;
	document.getElementById('direccion').value = direccion;
	document.getElementById('telefonos').value = telefonos;
	document.getElementById('email').value = email;
	
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';	
	setTimeout("document.getElementById('municipio').value = "+municipio+"", 600);
}



function seleccionarEliminar(idorganizacion, codigo, denominacion, responsable, estado, municipio, direccion, telefonos, email){
	cambiarMunicipios(estado);
	document.getElementById('idorganizacion').value = idorganizacion;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('estado').value = estado;
	document.getElementById('direccion').value = direccion;
	document.getElementById('telefonos').value = telefonos;
	document.getElementById('email').value = email;

	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('responsable').disabled = true;
	document.getElementById('estado').disabled = true;
	//document.getElementById('municipio').disabled = true;
	document.getElementById('direccion').disabled = true;
	document.getElementById('telefonos').disabled = true;
	document.getElementById('email').disabled = true;	
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';	
	setTimeout("document.getElementById('municipio').value = "+municipio+"", 600);
	setTimeout("document.getElementById('municipio').disabled = true", 100);
}



function listarOrganizaciones(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/organizacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaOrganizaciones').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarOrganizaciones");		
}
