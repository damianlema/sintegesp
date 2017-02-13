// JavaScript Document


function consultarUsuarios(valor){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/utilidades/lib/consultar_usuarios_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function(){ 
			if (ajax.readyState==1){}
			if (ajax.readyState==4){
				document.getElementById("resultadoUsuarios").innerHTML = ajax.responseText;
			} 
		}
		ajax.send("valor="+valor);
}