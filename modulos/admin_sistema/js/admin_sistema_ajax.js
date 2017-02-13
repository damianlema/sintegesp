// JavaScript Document

function nuevoAjax(){ 
	var xmlhttp=false;
	try{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}



function mostrarContenido(nombreDiv){
	document.getElementById('limpiar_repetidos').style.display = 'none';
	document.getElementById('nuevas_acciones').style.display = 'none';
	document.getElementById('montar_sql').style.display = 'none';
	document.getElementById('incluir_archivos').style.display = 'none';
	document.getElementById('ruta_reportes').style.display = 'none';
	document.getElementById('modulos').style.display = 'none';
	document.getElementById('configuracion').style.display = 'none';
	document.getElementById('agrupar_modulos').style.display ='none';
	document.getElementById('configuracion_cheques').style.display ='none';
	document.getElementById('divMensaje').innerHTML = '';
	document.getElementById(nombreDiv).style.display = 'block';
	
}




function guardarDatosRespaldo(usuario_bd, clave_bd, bd_conexion){
	if(usuario_bd == "" || clave_bd == "" || bd_conexion == ""){
		document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>Disculpe no puede dejar los campos en blanco</font>"
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("boton_guardar_respaldo").disabled = true;
				document.getElementById("boton_guardar_respaldo").innerHTML = "Cargando...";	
			}
			if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>Los datos se guardaron con Exito</font>"
				}else{
					document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>"+ajax.responseText+"</font>"
				}
			} 
			document.getElementById('boton_guardar_respaldo').innerHTML = "Limpiar";
			document.getElementById("boton_guardar_respaldo").disabled = false;
		}
		ajax.send("usuario_bd="+usuario_bd+"&clave_bd="+clave_bd+"&bd_conexion="+bd_conexion+"&ejecutar=guardarDatosRespaldo");
	}
}






function limpiarDatosDuplicados(tabla_actual, campo_agrupar, bd){
	if(tabla_actual == "" || campo_agregar == "" || bd == ""){
		document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>Disculpe no puede dejar los campos en blanco</font>"
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("botonLimpiarDatos").disabled = true;
				document.getElementById("botonLimpiarDatos").innerHTML = "Cargando...";	
			}
			if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>La tabla se limpio con Exito</font>"
				}else{
					document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>"+ajax.responseText+"</font>"
				}
			} 
			document.getElementById('botonLimpiarDatos').innerHTML = "Limpiar";
			document.getElementById("botonLimpiarDatos").disabled = false;
		}
		ajax.send("tabla_actual="+tabla_actual+"&campo_agrupar="+campo_agrupar+"&bd="+bd+"&ejecutar=limpiarDatosDuplicados");
	}
}





function crearNuevasAcciones(nombre_accion, id_modulo, url, mostrar, accion_padre, posicion){
	if(nombre_accion == "" || id_modulo == "" || url == "" || accion_padre == "" || posicion == ""){
		document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>Disculpe ninguno de los campos de texto pueden quedar vacios</font>";
	}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("botonLimpiarDatos").disabled = true;
					document.getElementById("botonLimpiarDatos").innerHTML = "Cargando...";	
				}
				if (ajax.readyState==4){
					partes = ajax.responseText.split("|.|");
					if(ajax.responseText.indexOf("exito") != -1){
						document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>La Accion se creo con Exito, El ID que se Genero para esta accion es: "+partes[1]+"</font>";
					}else{
						document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>Error Al crear la accion: "+partes[1]+"</font>";
					}
				} 
				document.getElementById('botonLimpiarDatos').innerHTML = "Crear Accion";
				document.getElementById("botonLimpiarDatos").disabled = false;
				document.getElementById('nombre_accion').value = "";
				document.getElementById('id_modulo').value = "";
				document.getElementById('url').value = "";
				document.getElementById('mostrar').checked = false;
				document.getElementById('accion_padre').value = "";
				document.getElementById('posicion').value = "";
			}
			ajax.send("nombre_accion="+nombre_accion+"&id_modulo="+id_modulo+"&url="+url+"&mostrar="+mostrar+"&accion_padre="+accion_padre+"&posicion="+posicion+"&ejecutar=crearNuevasAcciones");
	}
}





function procesarSql(sql, nombreBD){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("botonSubirSql").disabled = true;
			document.getElementById("botonSubirSql").innerHTML = "Cargando...";	
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>Su Consulta se Ejecuto con Exito</font>";
			}else{
				document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>ERROR El SQL no se pudo ejecutar<br><br>"+ajax.responseText+"</font>";
			}
		} 
		document.getElementById('botonSubirSql').innerHTML = "Subir SQL";
		document.getElementById("botonSubirSql").disabled = false;
	}
	ajax.send("sql="+sql+"&nombreBD="+nombreBD+"&ejecutar=procesarSql");
}


function cambiar_ruta_reportes(ruta){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("botonCambiarReportes").disabled = true;
			document.getElementById("botonCambiarReportes").innerHTML = "Cargando...";	
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>La ruta se cambio con Exito</font>";
			}else{
				document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>ERROR al cambiar la ruta de los reportes<br><br>"+ajax.responseText+"</font>";
			}
		} 
		document.getElementById('botonCambiarReportes').innerHTML = "Subir SQL";
		document.getElementById("botonCambiarReportes").disabled = false;
	}
	ajax.send("ruta="+ruta+"&ejecutar=cambiar_ruta_reportes");		
}



function ingresar_modulos(nuevo_modulo){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("botonIngresarModulo").disabled = true;
			document.getElementById("botonIngresarModulo").value = "Cargando...";	
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>El modulo se ingreso con Exito</font>";
			}else{
				document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>ERROR al ingresar el modulo<br><br>"+ajax.responseText+"</font>";
			}
		} 
		document.getElementById('botonIngresarModulo').value = "Ingresar Modulo";
		document.getElementById('nuevo_modulo').value = "";
		document.getElementById("botonIngresarModulo").disabled = false;
	}
	ajax.send("nuevo_modulo="+nuevo_modulo+"&ejecutar=ingresar_modulos");			
}









function procesarConfiguracion(){
	var logo = document.getElementById('logo').value;
	var segundo_logo = document.getElementById('segundo_logo').value;
	var alto_primero = document.getElementById("alto_primero").value;
	var ancho_primero = document.getElementById("ancho_primero").value;
	var alto_segundo= document.getElementById("alto_segundo").value;
	var ancho_segundo = document.getElementById("ancho_segundo").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			//document.getElementById('detalle_configuracion_reportes').innerHTML = "CARGANDO...";	
		}
		if (ajax.readyState==4){
			alert("Configuracion Guardada con Exito");
			//document.getElementById('detalle_configuracion_reportes').innerHTML = ajax.responseText;	
		}
	}
	ajax.send("ancho_segundo="+ancho_segundo+"&alto_segundo="+alto_segundo+"&ancho_primero="+ancho_primero+"&alto_primero="+alto_primero+"&logo="+logo+"&segundo_logo="+segundo_logo+"&ejecutar=procesarConfiguracion");
}








function actualizar_configuracion(tipo_sistema){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("botonActualizar").disabled = true;
			document.getElementById("botonActualizar").value = "Cargando...";	
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById('divMensaje').innerHTML = "<font style='color:#0000FF'>Se actualizo la configuracion con Exito</font>";
			}else{
				document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>ERROR al actualizar la configuracion<br><br>"+ajax.responseText+"</font>";
			}
		} 
		document.getElementById('botonActualizar').value = "Actualizar COnfiguracion";
		document.getElementById('nuevo_modulo').value = "";
		document.getElementById("botonActualizar").disabled = false;
	}
	ajax.send("tipo_sistema="+tipo_sistema+"&ejecutar=actualizar_configuracion");			
}






function mostrar_modulo(idcheck, id_modulo){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
		//alert(ajax.responseText);
			if(ajax.responseText == "si"){
				document.getElementById(idcheck).checked = true;	
			}else{
				document.getElementById(idcheck).checked = false;
			}
		} 
	}
	ajax.send("idcheck="+idcheck+"&id_modulo="+id_modulo+"&ejecutar=mostrar_modulo");
}
























function agrupar_modulos(modulo_asociacion, grupo){
 	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("botonIngresarModulo").disabled = true;
			document.getElementById("botonIngresarModulo").value = "Cargando...";	
		}
		if (ajax.readyState==4){
			if(ajax.responseText != "exito"){
				document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>ERROR: <br><br>"+ajax.responseText+"</font>";
			}
		} 
		document.getElementById('botonIngresarModulo').value = "Agrupar Modulo";
		document.getElementById("botonIngresarModulo").disabled = false;
		mostrar_modulos(grupo);
	}
	ajax.send("modulo_asociacion="+modulo_asociacion+"&grupo="+grupo+"&ejecutar=agrupar_modulos");			
}


function mostrar_modulos(grupo){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("botonIngresarModulo").disabled = true;
			document.getElementById("botonIngresarModulo").value = "Cargando...";	
		}
		if (ajax.readyState==4){
			document.getElementById('listaGrupoModulos').innerHTML = ajax.responseText;
		} 
		document.getElementById('botonIngresarModulo').value = "Agrupar Modulo";
		document.getElementById("botonIngresarModulo").disabled = false;
	}
	ajax.send("grupo="+grupo+"&ejecutar=mostrar_modulos");	
}




function eliminarAgrupacion(idagrupacion, grupo){
	var ajax=nuevoAjax();
	if(confirm("Seguro desea desasociar este modulo de este grupo?")){	
		ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText != "exito"){
				document.getElementById('divMensaje').innerHTML = "<font style='color:#FF0000'>ERROR: <br><br>Disculpe no se pudo eliminar la asociacion de este modulo en este grupo, por favor intente de nuevo mas tarde, "+ajax.responseText+"</font>";
			}else{
				mostrar_modulos(grupo);
			}
		} 
	}
	ajax.send("idagrupacion="+idagrupacion+"&grupo="+grupo+"&ejecutar=eliminarAgrupacion");	
	}
}


function mostrarConfiguracion(idbanco){	
	var ajax=nuevoAjax();
		ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var partes = ajax.responseText.split("|.|");
			document.getElementById('alto_monto_numeros').value = partes[0];
			document.getElementById('izquierda_monto_numeros').value = partes[1];
			document.getElementById('alto_beneficiario').value = partes[2];
			document.getElementById('izquierda_beneficiario').value = partes[3];
			document.getElementById('alto_monto_letras').value = partes[4];
			document.getElementById('izquierda_monto_letras').value = partes[5];
			document.getElementById('alto_fecha').value = partes[6];
			document.getElementById('izquierda_fecha').value = partes[7];
			document.getElementById('alto_ano').value = partes[8];
			document.getElementById('izquierda_ano').value = partes[9];			
		} 
	}
	ajax.send("idbanco="+idbanco+"&ejecutar=mostrarConfiguracion");	

}




function actualizarConfiguracion(){
	
	var alto_monto_numeros = document.getElementById('alto_monto_numeros').value;
	var izquierda_monto_numeros = document.getElementById('izquierda_monto_numeros').value;
	var alto_beneficiario = document.getElementById('alto_beneficiario').value;
	var izquierda_beneficiario = document.getElementById('izquierda_beneficiario').value;
	var alto_monto_letras = document.getElementById('alto_monto_letras').value;
	var izquierda_monto_letras = document.getElementById('izquierda_monto_letras').value;
	var alto_fecha = document.getElementById('alto_fecha').value;
	var izquierda_fecha = document.getElementById('izquierda_fecha').value;
	var alto_ano = document.getElementById('alto_ano').value;
	var izquierda_ano = document.getElementById('izquierda_ano').value;
	var idbanco = document.getElementById('idbanco').value;
	
	var ajax=nuevoAjax();
		ajax.open("POST", "lib/admin_sistema_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
			alert("los datos han sido actualizados con exito");	
			}else{
				alert(ajax.responseText);	
			}
		} 
	}
	ajax.send("alto_ano="+alto_ano+"&idbanco="+idbanco+"&alto_beneficiario="+alto_beneficiario+"&izquierda_beneficiario="+izquierda_beneficiario+"&alto_monto_letras="+alto_monto_letras+"&izquierda_monto_letras="+izquierda_monto_letras+"&alto_fecha="+alto_fecha+"&izquierda_fecha="+izquierda_fecha+"&izquierda_ano="+izquierda_ano+"&alto_monto_numeros="+alto_monto_numeros+"&alto_monto_numeros="+alto_monto_numeros+"&izquierda_monto_numeros="+izquierda_monto_numeros+"&ejecutar=actualizarConfiguracion");	
	
}