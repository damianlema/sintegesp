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




function registrarComentario(){

	var comentario 	= document.getElementById('cuadro_texto').value;
	var ua 			= document.getElementById('ua').value;
	var ur 			= document.getElementById('ur').value;
	var ajax		= nuevoAjax();
	ajax.open("POST", "lib/ventanaChat_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("estado").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				consultarMensajes(ua, ur);
				document.getElementById('cuadro_texto').value = "";
				document.getElementById('boton_enviar_comentario').disabled = true;
		} 
	}
	ajax.send("comentario="+comentario+"&ua="+ua+"&ur="+ur+"&ejecutar=registrarComentario");
	return false;
}


function consultarMensajes(ua, ur){
	var ajax		= nuevoAjax();
	ajax.open("POST", "lib/ventanaChat_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("estado").innerHTML = "Cargando...";
		}
		if (ajax.readyState==4){
				document.getElementById('ventana_mensajes').innerHTML = ajax.responseText;
				document.getElementById("estado").innerHTML = "";
				window.location.href='#fin';
				document.getElementById('cuadro_texto').focus();
		} 
	}
	ajax.send("ua="+ua+"&ur="+ur+"&ejecutar=consultarMensajes");
}