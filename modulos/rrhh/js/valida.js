function valida_envia(){
   
	//valido los campos del usuario
    if (document.trabajador.cedula.value.length==0){
	   mostrarMensajes("error", "Debe escribir un numero de Cedula");
       document.trabajador.cedula.focus()
       return false;
    }
	
	if (document.trabajador.apellidos.value.length==0){
		mostrarMensajes("error", "Debe escribir un Apellido(s) para el trabajador");
       document.trabajador.apellidos.focus()
       return false;
    }
	
	if (document.trabajador.nombres.value.length==0){
		mostrarMensajes("error", "Debe escribir un Nombre(s) para el trabajador.");
       document.trabajador.nombres.focus()
       return false;
    }
	
	if (document.trabajador.newlogin.value.length==0){
		mostrarMensajes("error", "Tiene que escribir un Nombre de trabajador o Login que identifique al trabajador");
		document.trabajador.newlogin.focus()
		return false;
	}
	
	if (document.trabajador.clave.value.length==0){
		mostrarMensajes("error", "Debe escribir una Clave para el ingreso del trabajador al Sistema");
		document.trabajador.clave.focus()
		return false;
	}

	if (document.trabajador.clave2.value.length==0){
		mostrarMensajes("error", "Debe confirmar la Clave para el ingreso del trabajador al Sistema");
		document.trabajador.clave2.focus()
		return false;
	}

	if (document.trabajador.clave2.value!=document.trabajador.clave.value){
		mostrarMensajes("error", "Las Claves ingresadas son diferentes, por favor verifique");
		document.trabajador.clave.focus()
		return false;
	}	
	
	if (document.trabajador.pregunta.value.length==0){
		mostrarMensajes("error", "Tiene que escribir una Pregunta en caso de que olvide su clave");
		document.trabajador.pregunta.focus()
		return false;
	}
	
	if (document.trabajador.respuesta.value.length==0){
		mostrarMensajes("error", "Tiene que escribir una Respuesta a la Pregunta Secreta en caso de que olvide su clave");
		document.trabajador.respuesta.focus()
		return false;
	}
    //document.usuario.submit();
} 