// JavaScript Document

function cargarSelect(sel, divHijo, idpadre, nombreIdPadre){
		if(nombreIdPadre != "idcarrera"){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/configuracion_ajax.php", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById(divHijo).innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("divHijo="+divHijo+"&idpadre="+idpadre+"&nombreIdPadre="+nombreIdPadre+"&sel="+sel+"&ejecutar=cargarSelect");
		
		}
}