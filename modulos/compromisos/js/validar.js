// JavaScript Document

// Archivo de Validaciones
var div = "mensaje";


function validarEmail(contenido){
	var div = "mensajeemail";
	var filtro=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if(contenido.length != 0){
		if (filtro.test(contenido)){
			document.getElementById(div).innerHTML = "Email Correcto!";
			document.getElementById(div).style.display = 'block';
			//document.getElementById("guardar").disabled = false;
			document.getElementById(div).style.border="#339966 1px solid";
			document.getElementById(div).style.color="#339966";
			
		}else{
			document.getElementById(div).innerHTML = "Email Incorrecto!";
			document.getElementById(div).style.display = 'block';
			//document.getElementById("guardar").disabled = true;
			document.getElementById(div).style.border="#990000 1px solid";
			document.getElementById(div).style.color="#990000";
			return false;
		}
	}else{
		document.getElementById(div).style.display = 'none';
	}
}

function validarVacios(nombrediv, contenido){
		if(contenido.length == 0){
			document.getElementById(nombrediv).innerHTML = "Campo Requerido";
			document.getElementById(nombrediv).style.display = 'block';
			document.getElementById(nombrediv).style.border="#990000 1px solid";
			document.getElementById(nombrediv).style.color="#990000";
			document.getElementById("guardar").disabled = true;
		}else{
			document.getElementById(nombrediv).innerHTML = "Correcto!";
			document.getElementById(nombrediv).style.display = 'block';
			document.getElementById(nombrediv).style.border="#339966 1px solid";
			document.getElementById(nombrediv).style.color="#339966";
			document.getElementById("guardar").disabled = false;
		}
}