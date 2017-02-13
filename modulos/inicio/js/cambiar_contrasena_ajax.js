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

function cambiarClave(clave_actual, nueva_clave, repeticion_clave){
	if(nueva_clave != repeticion_clave){
		alert("Disculpe La clave y la repeticion son distintos");	
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/cambiar_contrasena_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					//document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					//alert(ajax.responseText);
					if(ajax.responseText == "clave_incorrecta"){
						mostrarMensajes("error", "Disculpa la clave actual que ingreso es Incorrecta, por favor vuelva a intentarlo");
						document.getElementById('clave').select();
					}else{
						mostrarMensajes("exito", "Su clave ha sido cambiada con Exito");
						window.close();
					}
					//document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("clave_actual="+clave_actual+"&nueva_clave="+nueva_clave+"&ejecutar=cambiarClave");		
	}
	
}



function cambiarPreguntaSecreta(clave_actual, pregunta, respuesta){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/cambiar_contrasena_ajax.php?clave_actual="+clave_actual+"&nueva_clave="+nueva_clave+"&ejecutar=cambiarClave", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					if(ajax.responseText == "clave_incorrecta"){
						mostrarMensajes("error", "Disculpa la clave actual que ingreso es Incorrecta, por favor vuelva a intentarlo");
						
					}else{
						mostrarMensajes("exito", "Su Pregunta y Respuesta secreta han sido cambiadas con Exito");
						window.close();
					}
			} 
		}
		ajax.send(null);		
}