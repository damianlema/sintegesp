// JavaScript Document

function seleccionarDocumento(id_documento, id_recibido, tabla){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/conformar_presupuesto_ajax.php", true);	
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
		ajax.send("id_documento="+id_documento+"&id_recibido="+id_recibido+"&tabla="+tabla+"&ejecutar=seleccionarDocumento");		
}



function listarDocumentos(){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/conformar_presupuesto_ajax.php", true);	
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
		ajax.open("POST", "modulos/presupuesto/lib/conformar_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					document.getElementById("divTablaValidacionRemision").innerHTML = ajax.responseText;
					
			} 
		}
		ajax.send("ejecutar=vaciarCampos");		
}




function conformarDocumento(id_documento, id_recibido, estadoConformacion, razones_devolucion, conformado_por, ci_conformado_por,observaciones){
	if(estadoConformacion == "0"){
		mostrarMensajes("error", "Debe seleccionar un Estado para la Conformaci&oacute;n");
	}else if(estadoConformacion == "devuelto" && razones_devolucion == 0){
		mostrarMensajes("error", "Debe seleccionar un Razon de Devoluci&oacute;n para la Conformaci&oacute;n");
	}else if(conformado_por == "" && ci_conformado_por == ""){
		mostrarMensajes("error", "Debe indicar quien conformo el Documento y la C&eacute;dula de Identidad");
	}else{
		var ajax=nuevoAjax();		
		ajax.open("POST", "modulos/presupuesto/lib/conformar_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					vaciarCampos();
					listarDocumentos();
					//document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_documento="+id_documento+"&id_recibido="+id_recibido+"&estado_conformacion="+estadoConformacion+"&razones_devolucion="+razones_devolucion+"&conformado_por="+conformado_por+"&ci_conformado_por="+ci_conformado_por+"&observaciones="+observaciones+"&ejecutar=conformarDocumento");
	}
}

