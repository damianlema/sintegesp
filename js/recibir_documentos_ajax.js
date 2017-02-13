// JavaScript Document

function seleccionarRemision(id_remision){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/recibir_documentos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					document.getElementById("divTablaValidacionRemision").innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_remision="+id_remision+"&ejecutar=seleccionarRemision");		
}



function listarDocumentos(){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/recibir_documentos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					document.getElementById("divListaDocumentos").innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("ejecutar=listarDocumentos");		
}




function vaciarCampos(){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/recibir_documentos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					document.getElementById("divTablaValidacionRemision").innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("ejecutar=vaciarCampos");		
}




function recibirDocumento(id_remision, recibido_por, ci_recibe, observaciones, numero_documentos_recibidos){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/recibir_documentos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					vaciarCampos();
					listarDocumentos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_remision="+id_remision+"&recibido_por="+recibido_por+"&ci_recibe="+ci_recibe+"&observaciones="+observaciones+"&numero_documentos_recibidos="+numero_documentos_recibidos+"&ejecutar=recibirDocumento");		
}

