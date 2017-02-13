// JavaScript Document


function ingresarCalle(){
	var descripcion = document.getElementById('descripcion').value;
	var idurbanizacion = document.getElementById('idurbanizacion').value;
	var externo = document.getElementById('externo').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/calle_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarCalle();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('calle', 'celda_calle', opener.document.getElementById('urbanizacion').value, 'idurbanizacion');
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			}
			
		}
		ajax.send("idurbanizacion="+idurbanizacion+"&descripcion="+descripcion+"&ejecutar=ingresarCalle");
}





function modificarCalle(){
	var idcalle= document.getElementById('idcalle').value;
	var idurbanizacion = document.getElementById('idurbanizacion').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/calle_ajax.php?idcalle="+idcalle+"&idurbanizacion="+idurbanizacion+"&descripcion="+descripcion+"&ejecutar=modificarCalle", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarCalle();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send(null);
}


function eliminarCalle(){
	if(confirm("¿Seguro desea Eliminar esta Calle?")){
	var idurbanizacion = document.getElementById('idurbanizacion').value;
	var idcalle = document.getElementById('idcalle').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/calle_ajax.php?idcalle="+idcalle+"&idurbanizacion="+idurbanizacion+"&descripcion="+descripcion+"&ejecutar=eliminarCalle", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe la Urbanizacion que intenta eliminar esta asociada a algun Sector, por lo tanto no puede ser eliminada");
				}else{
					listarCalle();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send(null);
	}
}




function listarCalle(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/calle_ajax.php?ejecutar=listarCalle", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaCalle').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send(null);
}





function seleccionarDatos(descripcion, idcalle, idurbanizacion){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idcalle').value = idcalle;
	document.getElementById('idurbanizacion').value = idurbanizacion;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	