// JavaScript Document

function ubicarDocumento(id_documento,tabla){
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/remision_documentos/ubicar_documentos_ajax.php", true);	
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
		ajax.send("id_documento="+id_documento+"&tabla="+tabla+"&ejecutar=mostrarUbicacion");		
}

