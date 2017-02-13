// JavaScript Document


function ingresarNomenclatura(){
	var descripcion = document.getElementById('descripcion').value;
	var numero = document.getElementById('numero').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/nomenclatura_ficha_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarNomenclatura();
				document.getElementById('descripcion').value = '';
				document.getElementById('numero').value = '';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("numero="+numero+"&descripcion="+descripcion+"&numero="+numero+"&ejecutar=ingresarNomenclatura");
}





function modificarNomenclatura(){
	var idnomenclatura_fichas = document.getElementById('idnomenclatura_fichas').value;
	var descripcion = document.getElementById('descripcion').value;
	var numero = document.getElementById('numero').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/nomenclatura_ficha_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Nomenclatura modificada con exito");
				listarNomenclatura();
				document.getElementById('descripcion').value = '';
				document.getElementById('numero').value = '';
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idnomenclatura_fichas="+idnomenclatura_fichas+"&descripcion="+descripcion+"&numero="+numero+"&ejecutar=modificarNomenclatura");
}


function eliminarNomenclatura(){
	if(confirm("¿Seguro desea Eliminar Esta Nomenclatura de Ficha?")){
	var idnomenclatura_fichas = document.getElementById('idnomenclatura_fichas').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/nomenclatura_ficha_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					listarNomenclatura();
					document.getElementById('descripcion').value = '';
					document.getElementById('numero').value = '';
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
			}
			
		}
		ajax.send("idnomenclatura_fichas="+idnomenclatura_fichas+"&ejecutar=eliminarNomenclatura");
	}
}




function listarNomenclatura(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/nomenclatura_ficha_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaNomenclatura').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarNomenclatura");
}





function seleccionarDatos(descripcion, numero, idnomenclatura_fichas){
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('numero').value = numero;
	document.getElementById('idnomenclatura_fichas').value = idnomenclatura_fichas;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	