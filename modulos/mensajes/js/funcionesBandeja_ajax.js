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




function consultarRecibidos(){
	var ajax = nuevoAjax();
	ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				document.getElementById('cuadro_eliminar').style.visibility = 'visible'; 
				document.getElementById('cuadro_eliminar_inferior').style.visibility = 'visible';
				document.getElementById('cuadro_paginacion').style.visibility = 'visible'
				document.getElementById('cuadro_mensajes').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=consultarRecibidos");
}



function mostrarEnviar(){
	var ajax = nuevoAjax();
	ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				document.getElementById('cuadro_eliminar').style.visibility = 'hidden';
				document.getElementById('cuadro_eliminar_inferior').style.visibility = 'hidden';
				document.getElementById('cuadro_paginacion').style.visibility = 'hidden'	
				document.getElementById('cuadro_mensajes').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=mostrarEnviar");
	
}



function mostrarAlertas(tipo, texto){
	document.getElementById('cuadroMensajes').style.display = 'block';
	if(tipo == "exito"){
		document.getElementById('cuadroMensajes').innerHTML="<table align='center' style='margin-top:0px; background-color:#00CCFF; border:#003366 solid 2px; -moz-border-radius: 8px;'><tr><td><img src='../../imagenes/validar.png'></td><td style='color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:12px'>"+texto+"</td></tr></table>";	
	}else{
		document.getElementById('cuadroMensajes').innerHTML="<table align='center' style='margin-top:0px; background-color:#FFFF66; border:#FFCC00 solid 2px; -moz-border-radius: 8px;'><tr><td><img src='../../imagenes/reject.gif'></td><td style='color:#000; font-family:Arial, Helvetica, sans-serif; font-size:12px'>"+texto+"</td></tr></table>";
	}
	setTimeout("document.getElementById('cuadroMensajes').style.display='none'",8000);
}






function enviarMensaje(){
	var para = document.getElementById('campo_para').value;
	var asunto = document.getElementById('campo_asunto').value;
	var mensaje = document.getElementById('campo_mensaje').value;
	var adjuntos = document.getElementById('adjuntos').value;
	var ajax = nuevoAjax();
	ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
					mostrarAlertas("exito", "Mensaje Enviado con Exito");
					consultarRecibidos();
		} 
	}
	ajax.send("adjuntos="+adjuntos+"&para="+para+"&asunto="+asunto+"&mensaje="+mensaje+"&ejecutar=enviarMensaje");
}




function consultarEnviados(){
	var ajax = nuevoAjax();
	ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				document.getElementById('cuadro_eliminar').style.visibility = 'hidden'; 
				document.getElementById('cuadro_eliminar_inferior').style.visibility = 'hidden';
				document.getElementById('cuadro_paginacion').style.visibility = 'hidden';
				document.getElementById('cuadro_mensajes').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=consultarEnviados");	
}



function consultarPapelera(){
		var ajax = nuevoAjax();
	ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				document.getElementById('cuadro_eliminar').style.visibility = 'visible'; 
				document.getElementById('cuadro_eliminar_inferior').style.visibility = 'visible';
				document.getElementById('cuadro_paginacion').style.visibility = 'visible'
				document.getElementById('cuadro_mensajes').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=consultarPapelera");	
}




function mostrarMensaje(idmensajes_correo){
	var ubicacion = document.getElementById('ubicacion').value;
	var ajax = nuevoAjax();
	ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				document.getElementById('cuadro_eliminar').style.visibility = 'hidden'; 
				document.getElementById('cuadro_eliminar_inferior').style.visibility = 'hidden';
				document.getElementById('cuadro_paginacion').style.visibility = 'hidden'
				document.getElementById('cuadro_mensajes').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ubicacion="+ubicacion+"&idmensajes_correo="+idmensajes_correo+"&ejecutar=mostrarMensaje");		
}




function eliminarMensajes(){
	var valores = "";
	var form = document.getElementById('formulario_listaCorreos');
	for(i=0; i<form.length; i++){
		if(form.elements[i].type == "checkbox"){
			if(form.elements[i].checked == true){
				valores += form.elements[i].value+", ";	
			}	
		}	
	}
	//alert(valores);
	var ubicacion = document.getElementById('ubicacion').value;
	if(document.getElementById('accion').value == "papelera"){
		var ajax = nuevoAjax();
		ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
			}
			if (ajax.readyState==4){
				if(ubicacion == "recibidos"){
					consultarRecibidos();	
				}else if(ubicacion == "enviados"){
					consultarEnviados();
				}else if(ubicacion == "papelera"){
					consultarPapelera();
				}
			} 
		}
		ajax.send("valores="+valores+"&ejecutar=enviarPapelera");			
	}else if(document.getElementById('accion').value == "eliminar"){
		if(confirm("Seguro desea suprimir definitivamente los Correos Seleccionados?")){
		var ajax = nuevoAjax();
		ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
			}
			if (ajax.readyState==4){
				if(ubicacion == "recibidos"){
					consultarRecibidos();	
				}else if(ubicacion == "enviados"){
					consultarEnviados();
				}else if(ubicacion == "papelera"){
					consultarPapelera();
				}
			} 
		}
		ajax.send("valores="+valores+"&ejecutar=suprimirDefinitivamente");
	}
	}
}





function eliminarUnicoMensaje(idmensajes_correo){
	var valores = idmensajes_correo+",";
	
		if(confirm("Seguro desea suprimir definitivamente Este Correo?")){
		var ajax = nuevoAjax();
		ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
			}
			if (ajax.readyState==4){
					consultarRecibidos();	
			} 
		}
		ajax.send("valores="+valores+"&ejecutar=suprimirDefinitivamente");
	}
}



function buscarMensajes(){
	var campoBuscar = document.getElementById('campoBuscar').value;	
	var ajax = nuevoAjax();
		ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("cuadro_mensajes").innerHTML = "Cargando...";
			}
			if (ajax.readyState==4){
				document.getElementById('cuadro_eliminar').style.visibility = 'visible'; 
				document.getElementById('cuadro_eliminar_inferior').style.visibility = 'visible';
				document.getElementById('cuadro_paginacion').style.visibility = 'visible'
				document.getElementById('cuadro_mensajes').innerHTML = ajax.responseText;
			} 
		}
		ajax.send("campoBuscar="+campoBuscar+"&ejecutar=buscarMensajes");
}





function consultarAdjuntos(){
	var ajax = nuevoAjax();
		ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				document.getElementById('archivo_adjunto').value='';
				document.getElementById('lista_adjuntos_listos').innerHTML = ajax.responseText;
			} 
		}
		ajax.send("ejecutar=consultarAdjuntos");	
}



function eliminarAdjunto(idadjuntos_temporales){
		var ajax = nuevoAjax();
		ajax.open("POST", "lib/funcitonesBandeja_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				consultarAdjuntos();
			} 
		}
		ajax.send("idadjuntos_temporales="+idadjuntos_temporales+"&ejecutar=eliminarAdjunto");
}