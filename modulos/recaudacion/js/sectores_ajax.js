// JavaScript Document


function ingresarSectores(){
	var descripcion = document.getElementById('descripcion').value;
	var idparroquia = document.getElementById('idparroquia').value;
	var externo = document.getElementById('externo').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/sectores_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarSectores();
				document.getElementById('descripcion').value = '';
				document.getElementById("divCargando").style.display = "none";
				if(externo == "si"){
					opener.cargarSelect('sectores', 'celda_sector', opener.document.getElementById('parroquia').value, 'idparroquia');
					//setTimeout("opener.document.getElementById('parroquia').value = "+ajax.responseText+"", 1200);
					window.close();
				}
			}
			
		}
		ajax.send("idparroquia="+idparroquia+"&descripcion="+descripcion+"&ejecutar=ingresarSectores");
}





function modificarSectores(){
	var idsectores= document.getElementById('idsectores').value;
	var idparroquia = document.getElementById('idparroquia').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/sectores_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarSectores();
				document.getElementById('descripcion').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idparroquia="+idparroquia+"&idsectores="+idsectores+"&descripcion="+descripcion+"&ejecutar=modificarSectores");
}


function eliminarSectores(){
	if(confirm("¿Seguro desea Eliminar este Sector?")){
	var idparroquia = document.getElementById('idparroquia').value;
	var idsectores = document.getElementById('idsectores').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/sectores_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe el Sector que intenta eliminar esta asociado a alguna Parroquia, por lo tanto no puede ser eliminado");
				}else{
					listarSectores();
					document.getElementById('descripcion').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idsectores="+idsectores+"&idparroquia="+idparroquia+"&descripcion="+descripcion+"&ejecutar=eliminarSectores");
	}
}




function listarSectores(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/sectores_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaSectores').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarSectores");
}





function seleccionarDatos(descripcion, idsectores, idparroquia){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idsectores').value = idsectores;
	document.getElementById('idparroquia').value = idparroquia;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	