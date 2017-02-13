// JavaScript Document


function consultarRif(rif)
{		
//var formato = /^[JV]-\d{8}-\d$/;
		var formato = /^[JVGgEejv]\d{9}$/;
		if(!formato.test(rif)){
					document.getElementById("divMensaje").style.display = 'block';
					document.getElementById("divMensaje").innerHTML = "RIF / CI INCORRECTO EJ. ( J123456789 / V012456789 )";
					if(document.getElementById("boton_ingresar")){
						document.getElementById("boton_ingresar").value = "RIF / CI INCORRECTO EJ. ( J123456789 / V012456789 )";
						document.getElementById("boton_ingresar").disabled = true;
					}else{
						document.getElementById("boton_modificar").value = "RIF / CI INCORRECTO EJ. ( J123456789 / V012456789 )";
						document.getElementById("boton_modificar").disabled = true;
					}
					document.getElementById("divMensaje").style.border="#990000 1px solid";
					document.getElementById("divMensaje").style.color="#990000";
		}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
		ajax.onreadystatechange=function(){ 
			if (ajax.readyState==1){}
			if (ajax.readyState==4){
				//validarVacios('rif', document.getElementById('rif').value, 'form1');
				
				if(ajax.responseText != "vacio"){
					if(ajax.responseText == "si"){
						if(document.getElementById("boton_ingresar")){
							document.getElementById("divMensaje").style.display = 'block';
							document.getElementById("divMensaje").innerHTML = "RIF ya Registrado";
							document.getElementById("boton_ingresar").value = "RIF ya Registrado";
							document.getElementById("boton_ingresar").disabled = true;
							document.getElementById("divMensaje").style.border="#990000 1px solid";
							document.getElementById("divMensaje").style.color="#990000";
						}else{
							document.getElementById("divMensaje").style.display = 'block';
							document.getElementById("boton_ingresar").value = "Ingresar";
							document.getElementById("boton_ingresar").disabled = false;
							document.getElementById("divMensaje").innerHTML = "Correcto!";
							document.getElementById("divMensaje").style.border="#339966 1px solid";
							document.getElementById("divMensaje").style.color="#339966";
						}
					}else{
							document.getElementById("divMensaje").style.display = 'block';
							if(document.getElementById("boton_ingresar")){
								document.getElementById("boton_ingresar").value = "Ingresar";
								document.getElementById("boton_ingresar").disabled = false;
							}else{
								document.getElementById("boton_modificar").value = "Ingresar";
								document.getElementById("boton_modificar").disabled = false;
							}
							
							
							document.getElementById("divMensaje").innerHTML = "Correcto!";
							document.getElementById("divMensaje").style.border="#339966 1px solid";
							document.getElementById("divMensaje").style.color="#339966";
					}
				}else{
					document.getElementById("divMensaje").style.display = 'block';
					document.getElementById("divMensaje").innerHTML = "RIF Vacio";
					document.getElementById("boton_ingresar").value = "RIF Vacio";
					document.getElementById("boton_ingresar").disabled = true;
					document.getElementById("divMensaje").style.border="#990000 1px solid";
					document.getElementById("divMensaje").style.color="#990000";	
				}
				}
			} 
		ajax.send("rif="+rif+"&ejecutar=consultarRif");
		}
		
}




function cargarSelect(sel, divHijo, idpadre, nombreIdPadre){
		if(nombreIdPadre != "idcarrera"){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(sel);
				document.getElementById(divHijo).innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("divHijo="+divHijo+"&idpadre="+idpadre+"&nombreIdPadre="+nombreIdPadre+"&sel="+sel+"&ejecutar=cargarSelect");
		
		}
}


function mostrarPestana(div){
	document.getElementById('div_datosBasicos').style.display = 'none';
	document.getElementById('div_registroMercantil').style.display = 'none';
	document.getElementById('div_registroFotografico').style.display = 'none';
	document.getElementById('div_actividadComercial').style.display = 'none';
	document.getElementById('div_requisitos').style.display = 'none';
	document.getElementById(div).style.display = 'block';
}



function resultadoUpload(estado, file) {
if (estado == 0)
var mensaje = "<br><br><span style='color:#FF0000'>Disculpe El archivo seleccionado no se pudo cargar<span>";
if (estado == 1)
var mensaje = "<br><br><span style='color:#0000FF'>Archivo Copiado con Exito<span>";
consultarImagenes();
document.getElementById('imagen').value = '';
document.getElementById('descripcion_imagen').value = '';
if (estado == 2)
var mensaje = "<br><br><span style='color:#FFFF00'>Error ! - Solo se permiten Archivos tipo Imagen";
document.getElementById('formUpload').innerHTML=mensaje;
} 




function consultarImagenes(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText == "vacio"){
				document.getElementById('celda_imagenesExistentes').innerHTML = '<strong>No Existen Imagenes Cargadas !</strong><br><br>';		
			}else{
				document.getElementById('celda_imagenesExistentes').innerHTML = ajax.responseText;		
			}
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=consultarImagenes");

}



function verificarId(){
	if(document.getElementById('idcontribuyente').value == ''){
		document.getElementById('li_registroMercantil').style.display ='none';
		document.getElementById('li_registroFotografico').style.display ='none';
		document.getElementById('li_actividadComercial').style.display ='none';
		document.getElementById('li_requisitos').style.display ='none';
		document.getElementById('boton_modificar').style.display ='none';
		document.getElementById('boton_eliminar').style.display ='none';
		document.getElementById('boton_ingresar').style.display ='block';
	}else{
		document.getElementById('tipo_contribuyente').disabled = true;
		if(document.getElementById('tipo_contribuyente').value == 've'){
			document.getElementById('li_registroFotografico').style.display ='block';
			document.getElementById('li_actividadComercial').style.display ='block';
			document.getElementById('li_requisitos').style.display ='block';
			document.getElementById('li_registroMercantil').style.display ='none';
		}else if(document.getElementById('tipo_contribuyente').value == 'pj'){
			document.getElementById('li_registroMercantil').style.display ='block';
			document.getElementById('li_registroFotografico').style.display ='block';
			document.getElementById('li_actividadComercial').style.display ='block';
			document.getElementById('li_requisitos').style.display ='block';
		}else if(document.getElementById('tipo_contribuyente').value == 'pn'){
			document.getElementById('li_registroMercantil').style.display ='none';
			document.getElementById('li_registroFotografico').style.display ='block';
			document.getElementById('li_actividadComercial').style.display ='block';
			document.getElementById('li_requisitos').style.display ='block';
		}
		document.getElementById('boton_modificar').style.display ='block';
		document.getElementById('boton_eliminar').style.display ='block';
		document.getElementById('boton_ingresar').style.display ='none';
	}
}



function ingresarDatosBasicos(){
	var tipo_contribuyente = document.getElementById('tipo_contribuyente').value;
	if(tipo_contribuyente == "pj" || tipo_contribuyente == "pn"){
	
	var razon_social = document.getElementById('razon_social').value;
	var rif = document.getElementById('rif').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	var estado = document.getElementById('estado').value;
	var municipios = document.getElementById('municipios').value;
	var parroquia = document.getElementById('parroquia').value;
	var sectores= document.getElementById('sectores').value;
	var urbanizacion = document.getElementById('urbanizacion').value;
	var calle = document.getElementById('calle').value;
	var carrera = document.getElementById('carrera').value;
	var nro_casa = document.getElementById('nro_casa').value;
	var punto_referencia = document.getElementById('punto_referencia').value;
		if(razon_social == "" || rif == "" || telefono == "" || estado == "0" || municipios == "0" || parroquia == "0" || sectores == "0" || urbanizacion == "0" || calle == "0"){	
			mostrarMensajes("error", "Disculpe hay datos requeridos que no han sido suministrados, por favor verifique");
		}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					if(ajax.responseText == "existe"){
						mostrarMensajes("error", "Disculpe el RIF que intenta ingresar ya existe en el sistema");
					}else{
						document.getElementById("idcontribuyente").value = ajax.responseText;
						document.getElementById("idcontribuyente_imagen").value = ajax.responseText;
						verificarId();	
					}
					
					document.getElementById("divCargando").style.display = "none";
				}
				
			}
			ajax.send("tipo_contribuyente="+tipo_contribuyente+"&razon_social="+razon_social+"&rif="+rif+"&telefono="+telefono+"&email="+email+"&estado="+estado+"&municipios="+municipios+"&parroquia="+parroquia+"&sectores="+sectores+"&urbanizacion="+urbanizacion+"&calle="+calle+"&carrera="+carrera+"&nro_casa="+nro_casa+"&punto_referencia="+punto_referencia+"&ejecutar=ingresarDatosBasicos");
		}
		
	}else{ // SI ES UN VEHICULO
		
	var nro_placa = document.getElementById('nro_placa').value;
	var tipo_vehiculo = document.getElementById('vehiculo').value;
	var marca = document.getElementById('marca').value;
	var modelo = document.getElementById('modelo').value;
	var tipo = document.getElementById('tipo').value;
	var color = document.getElementById('color').value;
	var peso = document.getElementById('peso').value;
	var capacidad = document.getElementById('capacidad').value;
	var nro_matricula = document.getElementById('nro_matricula').value;
	var fecha_matricula = document.getElementById('fecha_matricula').value;
	var serial_motor = document.getElementById('serial_motor').value;
	var serial_carroceria = document.getElementById('serial_carroceria').value;
	var uso_vehiculo = document.getElementById('uso_vehiculo').value;
	var propietario = document.getElementById('propietario').value;
	var cedula_propietario = document.getElementById('cedula_propietario').value;
	
	var estado = document.getElementById('estado_vehiculo').value;
	var municipios = document.getElementById('municipios_vehiculo').value;
	var parroquia = document.getElementById('parroquia_vehiculo').value;
	var sectores= document.getElementById('sectores_vehiculo').value;
	var urbanizacion = document.getElementById('urbanizacion_vehiculo').value;
	var calle = document.getElementById('calle_vehiculo').value;
	var carrera = document.getElementById('carrera_vehiculo').value;
	var nro_casa = document.getElementById('nro_casa_vehiculo').value;
	var punto_referencia = document.getElementById('punto_referencia_vehiculo').value;
	
		if(nro_placa == "" || marca == "" || modelo == "" || tipo == "" || color == "" || peso == "" || capacidad == "" || nro_matricula == "" || fecha_matricula == "" || serial_motor == "" || serial_carroceria == "" || propietario == "" || cedula_propietario == ""){	
			mostrarMensajes("error", "Disculpe hay datos requeridos que no han sido suministrados, por favor verifique");
		}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					alert(ajax.responseText);
					if(ajax.responseText == "existe"){
						mostrarMensajes("error", "Disculpe el RIF que intenta ingresar ya existe en el sistema");
					}else{
						document.getElementById("idcontribuyente").value = ajax.responseText;
						document.getElementById("idcontribuyente_imagen").value = ajax.responseText;
						verificarId();	
					}
					
					document.getElementById("divCargando").style.display = "none";
				}
				
			}

			ajax.send("tipo_contribuyente="+tipo_contribuyente+"&estado="+estado+"&municipios="+municipios+"&parroquia="+parroquia+"&sectores="+sectores+"&urbanizacion="+urbanizacion+"&calle="+calle+"&carrera="+carrera+"&nro_casa="+nro_casa+"&punto_referencia="+punto_referencia+"&tipo_contribuyente="+tipo_contribuyente+"&nro_placa="+nro_placa+"&tipo_vehiculo="+tipo_vehiculo+"&marca="+marca+"&modelo="+modelo+"&tipo="+tipo+"&color="+color+"&parroquia="+parroquia+"&peso="+peso+"&capacidad="+capacidad+"&nro_matricula="+nro_matricula+"&fecha_matricula="+fecha_matricula+"&serial_motor="+serial_motor+"&serial_carroceria="+serial_carroceria+"&uso_vehiculo="+uso_vehiculo+"&propietario="+propietario+"&cedula_propietario="+cedula_propietario+"&ejecutar=ingresarDatosBasicos");
		}
		
	}
}





function cargarSocio(){
	var nombre_socio = document.getElementById('nombre_socio').value;
	var ci_socio = document.getElementById('ci_socio').value;
	var cargo_socio = document.getElementById('cargo_socio').value;
	var acciones_socio  = document.getElementById('acciones_socio').value;
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var ajax=nuevoAjax();
	if(nombre_socio == "" || ci_socio == "" || cargo_socio == "" || acciones_socio == ""){
		mostrarMensajes("error", "Disculpe todos los datos del socio son datos requeridos para poder ingresarlo");
	}else{
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('nombre_socio').value = "";
				document.getElementById('ci_socio').value = "";
				document.getElementById('cargo_socio').value = "";
				document.getElementById('acciones_socio').value = "";
				cargarListaSocios();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&nombre_socio="+nombre_socio+"&ci_socio="+ci_socio+"&cargo_socio="+cargo_socio+"&acciones_socio="+acciones_socio+"&ejecutar=cargarSocio");
	}
}






function cargarListaSocios(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	if(idcontribuyente != ""){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				alert(ajax.responseText);
				if(ajax.responseText == "vacio"){
					document.getElementById('listaSocios').innerHTML = "<strong><br><br>NO HAY SOCIOS REGISTRADOS</strong>";		
				}else{
					document.getElementById('listaSocios').innerHTML = ajax.responseText;
				}
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=cargarListaSocios");	
	}else{
		document.getElementById('listaSocios').innerHTML = "<strong><br><br>NO HAY SOCIOS REGISTRADOS</strong>";	
	}
}




function eliminarImagen(idimagenes_contribuyente){
	if(confirm("¿Seguro desea Eliminar esta Imagen?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			consultarImagenes()
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idimagenes_contribuyente="+idimagenes_contribuyente+"&ejecutar=eliminarImagen");	
	}
}









function modificarDatosBasicos(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	

	var tipo_contribuyente = document.getElementById('tipo_contribuyente').value;
	if(tipo_contribuyente == "pj" || tipo_contribuyente == "pn"){
	
	var razon_social = document.getElementById('razon_social').value;
	var rif = document.getElementById('rif').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	var estado = document.getElementById('estado').value;
	var municipios = document.getElementById('municipios').value;
	var parroquia = document.getElementById('parroquia').value;
	var sectores= document.getElementById('sectores').value;
	var urbanizacion = document.getElementById('urbanizacion').value;
	var calle = document.getElementById('calle').value;
	var carrera = document.getElementById('carrera').value;
	var nro_casa = document.getElementById('nro_casa').value;
	var punto_referencia = document.getElementById('punto_referencia').value;
		if(razon_social == "" || rif == "" || telefono == "" || estado == "0" || municipios == "0" || parroquia == "0" || sectores == "0" || urbanizacion == "0" || calle == "0"){	
			mostrarMensajes("error", "Disculpe hay datos requeridos que no han sido suministrados, por favor verifique");
		}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					if(ajax.responseText == "existe"){
						mostrarMensajes("error", "Disculpe el RIF que intenta ingresar ya existe en el sistema");
					}else{
						document.getElementById("idcontribuyente").value = ajax.responseText;
						document.getElementById("idcontribuyente_imagen").value = ajax.responseText;
						verificarId();	
					}
					
					document.getElementById("divCargando").style.display = "none";
				}
				
			}
			ajax.send("idcontribuyente="+idcontribuyente+"&tipo_contribuyente="+tipo_contribuyente+"&razon_social="+razon_social+"&rif="+rif+"&telefono="+telefono+"&email="+email+"&estado="+estado+"&municipios="+municipios+"&parroquia="+parroquia+"&sectores="+sectores+"&urbanizacion="+urbanizacion+"&calle="+calle+"&carrera="+carrera+"&nro_casa="+nro_casa+"&punto_referencia="+punto_referencia+"&ejecutar=modificarDatosBasicos");
		}
		
	}else{ // SI ES UN VEHICULO
		
	var nro_placa = document.getElementById('nro_placa').value;
	var tipo_vehiculo = document.getElementById('vehiculo').value;
	var marca = document.getElementById('marca').value;
	var modelo = document.getElementById('modelo').value;
	var tipo = document.getElementById('tipo').value;
	var color = document.getElementById('color').value;
	var peso = document.getElementById('peso').value;
	var capacidad = document.getElementById('capacidad').value;
	var nro_matricula = document.getElementById('nro_matricula').value;
	var fecha_matricula = document.getElementById('fecha_matricula').value;
	var serial_motor = document.getElementById('serial_motor').value;
	var serial_carroceria = document.getElementById('serial_carroceria').value;
	var uso_vehiculo = document.getElementById('uso_vehiculo').value;
	var propietario = document.getElementById('propietario').value;
	var cedula_propietario = document.getElementById('cedula_propietario').value;
	
	var estado = document.getElementById('estado_vehiculo').value;
	var municipios = document.getElementById('municipios_vehiculo').value;
	var parroquia = document.getElementById('parroquia_vehiculo').value;
	var sectores= document.getElementById('sectores_vehiculo').value;
	var urbanizacion = document.getElementById('urbanizacion_vehiculo').value;
	var calle = document.getElementById('calle_vehiculo').value;
	var carrera = document.getElementById('carrera_vehiculo').value;
	var nro_casa = document.getElementById('nro_casa_vehiculo').value;
	var punto_referencia = document.getElementById('punto_referencia_vehiculo').value;
	
		if(nro_placa == "" || marca == "" || modelo == "" || tipo == "" || color == "" || peso == "" || capacidad == "" || nro_matricula == "" || fecha_matricula == "" || serial_motor == "" || serial_carroceria == "" || propietario == "" || cedula_propietario == ""){	
			mostrarMensajes("error", "Disculpe hay datos requeridos que no han sido suministrados, por favor verifique");
		}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					alert(ajax.responseText);
					if(ajax.responseText == "existe"){
						mostrarMensajes("error", "Disculpe el RIF que intenta ingresar ya existe en el sistema");
					}else{
						document.getElementById("idcontribuyente").value = ajax.responseText;
						document.getElementById("idcontribuyente_imagen").value = ajax.responseText;
						verificarId();	
					}
					
					document.getElementById("divCargando").style.display = "none";
				}
				
			}

			ajax.send("idcontribuyente="+idcontribuyente+"&tipo_contribuyente="+tipo_contribuyente+"&estado="+estado+"&municipios="+municipios+"&parroquia="+parroquia+"&sectores="+sectores+"&urbanizacion="+urbanizacion+"&calle="+calle+"&carrera="+carrera+"&nro_casa="+nro_casa+"&punto_referencia="+punto_referencia+"&tipo_contribuyente="+tipo_contribuyente+"&nro_placa="+nro_placa+"&tipo_vehiculo="+tipo_vehiculo+"&marca="+marca+"&modelo="+modelo+"&tipo="+tipo+"&color="+color+"&parroquia="+parroquia+"&peso="+peso+"&capacidad="+capacidad+"&nro_matricula="+nro_matricula+"&fecha_matricula="+fecha_matricula+"&serial_motor="+serial_motor+"&serial_carroceria="+serial_carroceria+"&uso_vehiculo="+uso_vehiculo+"&propietario="+propietario+"&cedula_propietario="+cedula_propietario+"&ejecutar=modificarDatosBasicos");
		}
		
	}
}








function eliminarSocio(idsocios_contribuyentes){
	if(confirm("¿Seguro desea Eliminar este Socio?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Socio eliminado con Exito");
			cargarListaSocios();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idsocios_contribuyentes="+idsocios_contribuyentes+"&ejecutar=eliminarSocio");	
	}
}




function agregarActividad(){
	var idactividad_comercial = document.getElementById('actividad_comercial').value;
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Actividad agregada con exito");
			cargarListaActividades();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&idactividad_comercial="+idactividad_comercial+"&ejecutar=agregarActividad");		
}





function cargarListaActividades(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	if(idcontribuyente != ""){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				if(ajax.responseText == "vacio"){
					document.getElementById('listaActividades').innerHTML = "<strong><br><br>NO HAY ACTIVIDADES REGISTRADAS</strong>";		
				}else{
					document.getElementById('listaActividades').innerHTML = ajax.responseText;
				}
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=cargarListaActividades");	
	}else{
		document.getElementById('listaActividades').innerHTML = "<strong><br><br>NO HAY ACTIVIDADES REGISTRADAS</strong>";	
	}
}






function eliminarActividad(idactividades_contribuyente){
	if(confirm("¿Seguro desea Eliminar esta Actividad?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Actividad eliminada con exito");
			cargarListaActividades();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idactividades_contribuyente="+idactividades_contribuyente+"&ejecutar=eliminarActividad");	
	}
}






function eliminarContribuyente(){
	
	var idcontribuyente = document.getElementById('idcontribuyente').value;	
	if(confirm("¿Seguro desea Eliminar este contribuyente?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Contribuyente eliminado con exito");
			window.location.href = 'principal.php?accion=968&modulo=17';
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=eliminarContribuyente");	
	}
}








function consultarContribuyente(){
	var idcontribuyente = document.getElementById("idcontribuyente").value;
	document.getElementById('tipo_contribuyente').disabled = true;
	consultarImagenes();
	cargarListaSocios();
	cargarListaActividades();
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			
			if(document.getElementById('tipo_contribuyente').value != 've'){
				
				document.getElementById('datos_basicos_vehiculo').style.display = 'none';
				document.getElementById('datos_basicos_natural_juridica').style.display = 'block';
				partes = ajax.responseText.split("|.|");
				document.getElementById('razon_social').value = partes[0];
				document.getElementById('rif').value = partes[1];
				document.getElementById('telefono').value = partes[2];
				document.getElementById('email').value = partes[3];
				document.getElementById('estado').value = partes[4];
				cargarSelect('municipios', 'celda_municipio', partes[4], 'idestado');
				setTimeout("document.getElementById('municipios').value = '"+partes[5]+"'",200);
				cargarSelect('parroquia', 'celda_parroquia', partes[5], 'idmunicipios');
				setTimeout("document.getElementById('parroquia').value = '"+partes[6]+"'",300);
				cargarSelect('sectores', 'celda_sector', partes[6], 'idparroquia');
				setTimeout("document.getElementById('sectores').value = '"+partes[7]+"'",400);
				cargarSelect('urbanizacion', 'celda_urb', partes[7], 'idsectores');
				setTimeout("document.getElementById('urbanizacion').value = '"+partes[8]+"'",500);
				cargarSelect('calle', 'celda_calle', partes[9], 'idurbanizacion');
				setTimeout("document.getElementById('calle').value = '"+partes[9]+"'",600);
				cargarSelect('carrera', 'celda_carrera', partes[9], 'idcalle');
				setTimeout("document.getElementById('carrera').value = '"+partes[10]+"'",700);
				
				
				
				document.getElementById('nro_casa').value = partes[11];
				document.getElementById('punto_referencia').value = partes[12];
				document.getElementById('tipo_persona').value = partes[13];
				
				document.getElementById('tipo_empresa').value = partes[14];
				document.getElementById('tipo_sociedad').value = partes[15];
				document.getElementById('objeto').value = partes[16];
				document.getElementById('libro').value = partes[17];
				document.getElementById('folio').value = partes[18];
				document.getElementById('capital_social').value = partes[19];
				document.getElementById('capital_suscrito').value = partes[20];
			
				
				
			}else{
				document.getElementById('datos_basicos_natural_juridica').style.display = 'none';
				document.getElementById('datos_basicos_vehiculo').style.display = 'block';
				partes = ajax.responseText.split("|.|");
				document.getElementById('estado').value = partes[0];
				cargarSelect('municipios_vehiculo', 'celda_municipio_vehiculo', partes[0], 'idestado');
				setTimeout("document.getElementById('municipios_vehiculo').value = '"+partes[1]+"'",200);
				cargarSelect('parroquia_vehiculo', 'celda_parroquia_vehiculo', partes[1], 'idmunicipios');
				setTimeout("document.getElementById('parroquia_vehiculo').value = '"+partes[2]+"'",300);
				cargarSelect('sectores_vehiculo', 'celda_sector_vehiculo', partes[2], 'idparroquia');
				setTimeout("document.getElementById('sectores_vehiculo').value = '"+partes[3]+"'",400);
				cargarSelect('urbanizacion_vehiculo', 'celda_urb_vehiculo', partes[3], 'idsectores');
				setTimeout("document.getElementById('urbanizacion_vehiculo').value = '"+partes[4]+"'",500);
				cargarSelect('calle_vehiculo', 'celda_calle_vehiculo', partes[4], 'idurbanizacion');
				setTimeout("document.getElementById('calle_vehiculo').value = '"+partes[5]+"'",600);
				cargarSelect('carrera_vehiculo', 'celda_carrera_vehiculo', partes[5], 'idcalle');
				setTimeout("document.getElementById('carrera_vehiculo').value = '"+partes[6]+"'",700);
				
				
				
				document.getElementById('nro_placa').value = partes[7];
				document.getElementById('vehiculo').value = partes[8];
				document.getElementById('marca').value = partes[9];
				document.getElementById('modelo').value = partes[10];
				document.getElementById('tipo').value = partes[11];
				document.getElementById('color').value = partes[12];
				document.getElementById('peso').value = partes[13];
				document.getElementById('capacidad').value = partes[14];
				document.getElementById('nro_matricula').value = partes[15];
				document.getElementById('fecha_matricula').value = partes[16];
				document.getElementById('serial_motor').value = partes[17];
				document.getElementById('serial_carroceria').value = partes[18];
				document.getElementById('uso_vehiculo').value = partes[19];
				document.getElementById('propietario').value = partes[20];
				document.getElementById('cedula_propietario').value = partes[21];

				
			
			}
			
			//	muestro el boton imprimir
			document.getElementById("btImprimir").style.visibility = "visible";
			
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=consultarContribuyente");	
	
	
	
}






function consultarRequisitos(){
	var idcontribuyente = document.getElementById("idcontribuyente").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('div_requisitos').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=consultarRequisitos");	
}




function seleccionarrequisitoVencimiento(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var idrequisitos = document.getElementById('idrequisito').value;
	var fecha_vencimiento = document.getElementById('fecha_vencimiento').value;
var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				consultarRequisitos();
				document.getElementById('fecha_vencimiento').value = '';
				document.getElementById('div_fechaVencimientoRequisitos').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("fecha_vencimiento="+fecha_vencimiento+"&idcontribuyente="+idcontribuyente+"&idrequisitos="+idrequisitos+"&ejecutar=seleccionarrequisitoVencimiento");		
}




function seleccionarRequisito(idcontribuyente, idrequisitos, vencimiento, id){
	if(vencimiento == "si" && document.getElementById(id).checked == true){
		document.getElementById('idrequisito').value = idrequisitos;
		document.getElementById('div_fechaVencimientoRequisitos').style.display = 'block';
		document.getElementById('check_'+idrequisitos).disabled=true;
	}else{
	
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				consultarRequisitos();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&idrequisitos="+idrequisitos+"&ejecutar=seleccionarRequisito");	
	}
}




function ingresarRegistroMercantil(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var tipo_persona = document.getElementById('tipo_persona').value;
	var tipo_empresa = document.getElementById('tipo_empresa').value;
	var tipo_sociedad = document.getElementById('tipo_sociedad').value;
	var fecha_registro = document.getElementById('fecha_registro').value;
	var objeto = document.getElementById('objeto').value;
	var libro = document.getElementById('libro').value;
	var folio = document.getElementById('folio').value;
	var capital_social = document.getElementById('capital_social').value;
	var capital_suscrito = document.getElementById('capital_suscrito').value;
	if(tipo_persona == "" || tipo_empresa == "" || tipo_sociedad == "" || fecha_registro == "" || objeto == "" || libro == "" || folio == "" || capital_social == "" || capital_suscrito == ""){
	mostrarMensajes("error", "Disculpe todos los datos son obligatorios");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				limpiarRegistroMercantil();
				consultarRegistroMercantil();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idcontribuyente="+idcontribuyente+"&tipo_persona="+tipo_persona+"&tipo_empresa="+tipo_empresa+"&tipo_sociedad="+tipo_sociedad+"&fecha_registro="+fecha_registro+"&objeto="+objeto+"&libro="+libro+"&folio="+folio+"&capital_social="+capital_social+"&capital_suscrito="+capital_suscrito+"&ejecutar=ingresarRegistroMercantil");
	}
	
}





function modificarRegistroMercantil(){
	var idregistro_mercantil = document.getElementById('idregistro_mercantil').value;
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var tipo_persona = document.getElementById('tipo_persona').value;
	var tipo_empresa = document.getElementById('tipo_empresa').value;
	var tipo_sociedad = document.getElementById('tipo_sociedad').value;
	var fecha_registro = document.getElementById('fecha_registro').value;
	var objeto = document.getElementById('objeto').value;
	var libro = document.getElementById('libro').value;
	var folio = document.getElementById('folio').value;
	var capital_social = document.getElementById('capital_social').value;
	var capital_suscrito = document.getElementById('capital_suscrito').value;
	var ajax=nuevoAjax();
	if(tipo_persona == "" || tipo_empresa == "" || tipo_sociedad == "" || fecha_registro == "" || objeto == "" || libro == "" || folio == "" || capital_social == "" || capital_suscrito == ""){
		mostrarMensajes("error", "Disculpe todos los datos son obligatorios");
	}else{
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				limpiarRegistroMercantil();
				consultarRegistroMercantil();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idregistro_mercantil="+idregistro_mercantil+"&idcontribuyente="+idcontribuyente+"&tipo_persona="+tipo_persona+"&tipo_empresa="+tipo_empresa+"&tipo_sociedad="+tipo_sociedad+"&fecha_registro="+fecha_registro+"&objeto="+objeto+"&libro="+libro+"&folio="+folio+"&capital_social="+capital_social+"&capital_suscrito="+capital_suscrito+"&ejecutar=modificarRegistroMercantil");	
	}
		
}


function consultarRegistroMercantil(){
	var idcontribuyente = document.getElementById('idcontribuyente').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('lista_registro_mercantil').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idcontribuyente="+idcontribuyente+"&ejecutar=consultarRegistroMercantil");		
}



function limpiarRegistroMercantil(){
	document.getElementById('idregistro_mercantil').value = "";
	document.getElementById('tipo_persona').value = 0;
	document.getElementById('tipo_empresa').value = 0;
	document.getElementById('tipo_sociedad').value = 0;
	document.getElementById('fecha_registro').value = "";
	document.getElementById('objeto').value = "";
	document.getElementById('folio').value = "";
	document.getElementById('libro').value = "";
	document.getElementById('capital_social').value = "";
	document.getElementById('capital_suscrito').value = "";
	
	
	document.getElementById('boton_ingresar_registro_mercantil').style.display = 'block';
	document.getElementById('boton_modificar_registro_mercantil').style.display = 'none';	
}



function seleccionarRegistroMercantil(idregistro_mercantil_contribuyente, tipo_persona, tipo_empresa, tipo_sociedad, fecha_registro, objeto, libro, folio, capital_social, capital_suscrito){
	document.getElementById('idregistro_mercantil').value = idregistro_mercantil_contribuyente;
	document.getElementById('tipo_persona').value = tipo_persona;
	document.getElementById('tipo_empresa').value = tipo_empresa;
	document.getElementById('tipo_sociedad').value = tipo_sociedad;
	document.getElementById('fecha_registro').value = fecha_registro;
	document.getElementById('objeto').value = objeto;
	document.getElementById('folio').value = folio;
	document.getElementById('libro').value = libro;
	document.getElementById('capital_social').value = capital_social;
	document.getElementById('capital_suscrito').value = capital_suscrito;
	
	
	document.getElementById('boton_ingresar_registro_mercantil').style.display = 'none';
	document.getElementById('boton_modificar_registro_mercantil').style.display = 'block';
	
}




function eliminarRegistroMercantil(idregistro_mercantil_contribuyente){
	if(confirm("Seguro desea eliminar este registro mercantil?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/datos_basico_contribuyente_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				consultarRegistroMercantil();
				limpiarRegistroMercantil();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idregistro_mercantil_contribuyente="+idregistro_mercantil_contribuyente+"&ejecutar=eliminarRegistroMercantil");	
	}
}