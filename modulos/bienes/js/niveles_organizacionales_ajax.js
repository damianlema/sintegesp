// JavaScript Document


function cargarSubNiveles(organizacion){
	partes = organizacion.split(".|.");
	organizacion = partes[0];
	sub_niveles = partes[1];
	codigo = partes[2]+"-";

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/niveles_organizacionales_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_sub_nivel').innerHTML = ajax.responseText;
				if(sub_niveles == '0'){
					document.getElementById('sub_nivel').disabled = true;	
				}else{
					document.getElementById('sub_nivel').disabled = false;		
				}
				document.getElementById('codigoOrganizacion').innerHTML = codigo;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("organizacion="+organizacion+"&ejecutar=cargarSubNiveles");
}

function cargaCodigoSubNivel(valor){
	partes = valor.split(".|.");
	document.getElementById('codigoSubNivel').innerHTML = partes[1]+'-';
}

function ingresarNivelesOrganizacionales(){
	partesOrganizacion = document.getElementById('organizacion').value.split(".|.");
	var organizacion = partesOrganizacion[0];
	partesSubNivel = document.getElementById('sub_nivel').value.split(".|.");
	var sub_nivel = partesSubNivel[0];
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	
	if(codigo.length > 2){
		mostrarMensajes("error", "Codigo no puede sermayor a Dos Digitos");
		document.getElementById('codigo').select();
	}else{
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/niveles_organizacionales_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el Codigo Ingresado ya Existe");
				}else{
					mostrarMensajes("exito", "Los datos fueron registrados con exito");
					limpiarDatos();
					listarNivelesOrganizacionales();	
				}
				
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("denominacion="+denominacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&codigo="+codigo+"&sub_nivel="+sub_nivel+"&organizacion="+organizacion+"&email="+email+"&telefono="+telefono+"&ejecutar=ingresarNivelesOrganizacionales");
	}
}



function limpiarDatos(){
	document.getElementById('codigo').value = '';
	document.getElementById('denominacion').value = '';
	document.getElementById('responsable').value = '';
	document.getElementById('ci_responsable').value = '';
	document.getElementById('sub_nivel').value = 0;
	document.getElementById('telefono').value = '';
	document.getElementById('email').value = '';
	document.getElementById('organizacion').value = 0;
	document.getElementById('codigoOrganizacion').innerHTML ='';
	document.getElementById('codigoSubNivel').innerHTML ='';
	
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('responsable').disabled = false;
	document.getElementById('ci_responsable').disabled = false;
	document.getElementById('sub_nivel').disabled = false;
	document.getElementById('telefono').disabled = false;
	document.getElementById('email').disabled = false;
	document.getElementById('organizacion').disabled = false;	
	
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';	
}


function modificarNivelesOrganizacionales(){
	
	var organizacion = document.getElementById('idorganizacion').value;

	var idniveles_organizacionales = document.getElementById('idniveles_organizacionales').value;

	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var sub_nivel = document.getElementById('sub_nivel').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/niveles_organizacionales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Los datos fueron modificados con exito");
			limpiarDatos();
			listarNivelesOrganizacionales();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idniveles_organizacionales="+idniveles_organizacionales+"&email="+email+"&telefono="+telefono+"&organizacion="+organizacion+"&sub_nivel="+sub_nivel+"&denominacion="+denominacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&codigo="+codigo+"&ejecutar=modificarNivelesOrganizacionales");
}



function eliminarNivelesOrganizacionales(){
	
	var organizacion = document.getElementById('idorganizacion').value;

	var idniveles_organizacionales = document.getElementById('idniveles_organizacionales').value;

	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var sub_nivel = document.getElementById('sub_nivel').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/niveles_organizacionales_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe No puede Eliminar este Nivel Organizacional porque tiene sub-niveles dependientes");
				limpiarDatos();
				listarNivelesOrganizacionales();
				document.getElementById("divCargando").style.display = "none";
			}else{
				mostrarMensajes("exito", "Nivel Eliminado con Exito");
				limpiarDatos();
				listarNivelesOrganizacionales();
				document.getElementById("divCargando").style.display = "none";
			}
		} 
	}
	ajax.send("idniveles_organizacionales="+idniveles_organizacionales+"&email="+email+"&telefono="+telefono+"&organizacion="+organizacion+"&sub_nivel="+sub_nivel+"&denominacion="+denominacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&codigo="+codigo+"&ejecutar=eliminarNivalesOrganizacionales");		
}



function seleccionarModificar(organizacion, codigo, denominacion, responsable, ci_responsable, sub_nivel, telefono, email, idniveles_organizacionales, codigo_organizacion, num_tiene_sub){
	limpiarDatos();
	document.getElementById('idorganizacion').value = organizacion;
	document.getElementById('organizacion').value = organizacion+".|."+num_tiene_sub+".|."+codigo_organizacion;
	document.getElementById('organizacion').disabled = true;
	document.getElementById('idniveles_organizacionales').value = idniveles_organizacionales;
	document.getElementById('codigo').value = codigo;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('ci_responsable').value = ci_responsable;
	document.getElementById('sub_nivel').value = sub_nivel;
	document.getElementById('sub_nivel').disabled = true;
	document.getElementById('telefono').value = telefono;
	document.getElementById('email').value = email;
	document.getElementById('codigoOrganizacion').innerHTML ='';
	
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';	
}



function seleccionarEliminar(organizacion, codigo, denominacion, responsable, ci_responsable, sub_nivel, telefono, email, idniveles_organizacionales, codigo_organizacion, num_tiene_sub){
	limpiarDatos();
	document.getElementById('idorganizacion').value = organizacion;
	document.getElementById('organizacion').value = organizacion+".|."+num_tiene_sub+".|."+codigo_organizacion;
	document.getElementById('idniveles_organizacionales').value = idniveles_organizacionales;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('ci_responsable').value = ci_responsable;
	document.getElementById('sub_nivel').value = sub_nivel;
	document.getElementById('telefono').value = telefono;
	document.getElementById('email').value = email;
	document.getElementById('codigoOrganizacion').innerHTML ='';

	document.getElementById('idniveles_organizacionales').disabled = true;
	document.getElementById('organizacion').disabled = true;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('responsable').disabled = true;
	document.getElementById('ci_responsable').disabled = true;
	document.getElementById('sub_nivel').disabled = true;
	document.getElementById('telefono').disabled = true;
	document.getElementById('email').disabled = true;
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';	
}



function listarNivelesOrganizacionales(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/niveles_organizacionales_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaNivelesOrganizacionales').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarNivelesOrganizacionales");		
}