// JavaScript Document


function ingresarTipoSolicitud(){
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/tipo_solicitud_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Datos ingresados con Exito");
				listarTipoSolicitud();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("descripcion="+descripcion+"&ejecutar=ingresarTipoSolicitud");
}





function modificarTipoSolicitud(){
	var idtipo_solicitud = document.getElementById('idtipo_solicitud').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/tipo_solicitud_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Datos modificados con exito");
				listarTipoSolicitud();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idtipo_solicitud="+idtipo_solicitud+"&descripcion="+descripcion+"&ejecutar=modificarTipoSolicitud");
}


function eliminarTipoSolicitud(){
	if(confirm("¿Seguro desea Eliminar este Tipo de Solicitud?")){
	var idtipo_solicitud = document.getElementById('idtipo_solicitud').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/tipo_solicitud_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe el Tipo de Solicitud que intenta Eliminar esta siendo usado, por favor verifique y vuelva a intentarlo");
				}else{
					listarTipoSolicitud();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idtipo_solicitud="+idtipo_solicitud+"&descripcion="+descripcion+"&ejecutar=eliminarTipoSolicitud");
	}
}




function listarTipoSolicitud(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/tipo_solicitud_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaTipoSolicitud').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarTipoSolicitud");
}





function seleccionarDatos(descripcion, idtipo_solicitud){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idtipo_solicitud').value = idtipo_solicitud;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	