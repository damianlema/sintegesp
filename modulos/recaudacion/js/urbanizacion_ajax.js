// JavaScript Document


function ingresarUrbanizacion(){
	var descripcion = document.getElementById('descripcion').value;
	var idsectores = document.getElementById('idsectores').value;
		var externo = document.getElementById('externo').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/urbanizacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarUrbanizacion();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('urbanizacion', 'celda_urb', opener.document.getElementById('sectores').value, 'idsectores');
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			}
			
		}
		ajax.send("idsectores="+idsectores+"&descripcion="+descripcion+"&ejecutar=ingresarUrbanizacion");
}





function modificarUrbanizacion(){
	var idurbanizacion= document.getElementById('idurbanizacion').value;
	var idsectores = document.getElementById('idsectores').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/urbanizacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarUrbanizacion();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idsectores="+idsectores+"&idurbanizacion="+idurbanizacion+"&descripcion="+descripcion+"&ejecutar=modificarUrbanizacion");
}


function eliminarUrbanizacion(){
	if(confirm("¿Seguro desea Eliminar esta Urbanizacion?")){
	var idsectores = document.getElementById('idsectores').value;
	var idurbanizacion = document.getElementById('idurbanizacion').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/urbanizacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe la Urbanizacion que intenta eliminar esta asociada a algun Sector, por lo tanto no puede ser eliminada");
				}else{
					mostrarMensajes("exito", "La urbanizacion fue eliminada con exito");
					listarUrbanizacion();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idurbanizacion="+idurbanizacion+"&idsectores="+idsectores+"&descripcion="+descripcion+"&ejecutar=eliminarUrbanizacion");
	}
}




function listarUrbanizacion(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/urbanizacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaUrbanizacion').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarUrbanizacion");
}





function seleccionarDatos(descripcion, idurbanizacion, idsectores){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idurbanizacion').value = idurbanizacion;
	document.getElementById('idsectores').value = idsectores;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	