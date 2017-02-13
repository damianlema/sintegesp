// JavaScript Document

function ingresarDatosBasicos(){
	var justificacion = document.getElementById('justificacion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('datos_extra').style.display = 'block';
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('boton_procesar').style.display = 'block';
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById('iddesincorporacion').value = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("justificacion="+justificacion+"&ejecutar=ingresarDatosBasicos");	
}



function ingresarMueble(){
	var iddesincorporacion = document.getElementById('iddesincorporacion').value;
	var idmueble = document.getElementById('idmueble').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe ya este mueble esta en la lista para desagregarlo");	
				}
				document.getElementById('codigo_mueble').value = '';
				document.getElementById('nombre_mueble').value = '';
				document.getElementById('idmueble').value = '';
				consultarMuebles();
			}
		}
		ajax.send("iddesincorporacion="+iddesincorporacion+"&idmueble="+idmueble+"&ejecutar=ingresarMueble");
}



function eliminarMueble(idmueble_desincorporacion){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Mueble Eliminado con Exito");
				consultarMuebles();
			}
		}
		ajax.send("idmueble_desincorporacion="+idmueble_desincorporacion+"&ejecutar=eliminarMueble");	
}



function consultarMuebles(){
	var iddesincorporacion = document.getElementById('iddesincorporacion').value;	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				document.getElementById('lista_seleccionados').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("iddesincorporacion="+iddesincorporacion+"&ejecutar=consultarMuebles");	
}



function procesarDesincorporacion(){
	if(confirm("Seguro desea Procesar esta acta de desincorporacion de bienes?")){
	var iddesincorporacion = document.getElementById('iddesincorporacion').value;	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "La desincorporacion fue procesada con exito");	
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'block';
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('tabla_campos_muebles').style.display = 'none';
				document.getElementById('estado_planilla').innerHTML = 'Procesado';
				document.getElementById("numero_planilla").innerHTML = ajax.responseText;
				consultarMuebles();
			}
		}
		ajax.send("iddesincorporacion="+iddesincorporacion+"&ejecutar=procesarDesincorporacion");	
	}
}




function anularOrden(){
	
	if(confirm("Seguro desea Anular esta planilla de desincorporacion?")){
	var iddesincorporacion = document.getElementById('iddesincorporacion').value;	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);	
		
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "La desincorporacion fue anulada con exito");
				document.getElementById('boton_procesar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById('boton_siguiente').style.display = 'none';
				document.getElementById('tabla_campos_muebles').style.display = 'none';
				document.getElementById('estado_planilla').innerHTML = 'Anulado';
				consultarMuebles();
			}
		}
		ajax.send("iddesincorporacion="+iddesincorporacion+"&ejecutar=anularOrden");	
	}
}



function consultarDesincorporacion(iddesincorporacion){
	document.getElementById('iddesincorporacion').value = iddesincorporacion;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/desincorporacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				var partes = ajax.responseText.split("|.|");
				
				
				document.getElementById('fecha_proceso').innerHTML = partes[1];
				document.getElementById('justificacion').value = partes[2];
				
				
				if(partes[3] == "elaboracion" ){
					document.getElementById('boton_procesar').style.display = 'block';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('boton_siguiente').style.display = 'none';	
					document.getElementById('estado_planilla').innerHTML = "Elaboracion";
				}else if(partes[3] == "procesado"){
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'block';
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('estado_planilla').innerHTML = "Procesado";
					document.getElementById('numero_planilla').innerHTML =  partes[0];
				}else if(partes[3] == "anulado"){
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('estado_planilla').innerHTML = "Anulado";
					document.getElementById('numero_planilla').innerHTML =  partes[0];
				}
				consultarMuebles();
			}
		}
		ajax.send("iddesincorporacion="+iddesincorporacion+"&ejecutar=consultarDesincorporacion");	
}