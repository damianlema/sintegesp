// JavaScript Document


function ingresarRequisitos(){
	var idtipo_solicitud = document.getElementById('idtipo_solicitud').value;
	var idrequisitos = document.getElementById('idrequisitos').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/asociar_requisitos_tipo_solicitud_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el requisito que intenta asociar ya esta asociado para esta actividad comercial");
					document.getElementById('idrequisitos').value = '0';
				}else{
					listarRequisitos(idtipo_solicitud);
						
				}
				
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idrequisitos="+idrequisitos+"&idtipo_solicitud="+idtipo_solicitud+"&ejecutar=ingresarRequisitos");
}






function eliminarRequisitos(idasociar_requisitos_tipo_solicitud, idtipo_solicitud){
	if(confirm("¿Seguro desea Eliminar esta Asociacion de requisitos?")){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/asociar_requisitos_tipo_solicitud_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe el requisito que intenta eliminar esta siendo usado por algun contribuyente, por lo tanto no puede ser eliminado");
				}else{
					listarRequisitos(idtipo_solicitud);
					document.getElementById('idrequisitos').value = '0';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idasociar_requisitos_tipo_solicitud="+idasociar_requisitos_tipo_solicitud+"&ejecutar=eliminarRequisitos");
	}
}




function listarRequisitos(idtipo_solicitud){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/asociar_requisitos_tipo_solicitud_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaRequisitos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("idtipo_solicitud="+idtipo_solicitud+"&ejecutar=listarRequisitos");
}






	