// JavaScript Document

function validarEmail(nombreCampo, contenido){
	var campo = document.getElementById(nombreCampo);
	var filtro=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if(contenido.length != 0){
		if (filtro.test(contenido)){
			campo.style.background="url(imagenes/accept.gif) no-repeat right";
			campo.style.backgroundColor ='#FFFFFF';
			campo.style.border="#339966 1px solid";
			
		}else{
			campo.style.background="url(imagenes/reject.gif) no-repeat right";
			campo.style.backgroundColor ='#FFEEEE';
			campo.style.border="#E3655A 1px solid";
			return false;
		}
	}else{
		campo.style.background="url() no-repeat right";
		campo.style.backgroundColor ='#FFFFFF';
		campo.style.border="1px solid";
	}
}




function validarVacios(nombreCampo, contenido, frm){
		var campo = document.getElementById(nombreCampo);
		var formulario = document.getElementById(frm);
		if(contenido.length != 0){
			campo.style.background="url(imagenes/accept.gif) no-repeat right";
			campo.style.backgroundColor ='#FFFFFF';
			campo.style.border="#339966 1px solid";
			for(var i=0; i< formulario.elements.length; i++) {
			  var elemento = formulario.elements[i];
			  if(elemento.type == "button" || elemento.type == "submit" || elemento.type == "reset") {
				  elemento.disabled = false;
			  }
			}
		}else{
			campo.style.background="url(imagenes/reject.gif) no-repeat right";
			campo.style.backgroundColor ='#FFEEEE';
			campo.style.border="#E3655A 1px solid";
			for(var i=0; i< formulario.elements.length; i++) {
			  var elemento = formulario.elements[i];
			  if(elemento.type == "button" || elemento.type == "submit" || elemento.type == "reset") {
				 if(elemento.name != "botonOrdenar"){
				 	elemento.disabled = true;
				 }
			  }
			}
			return false;
		}
}