// JavaScript Document


function mostrarInmueble(){
	limpiarCampos();
	document.getElementById('tipo').value = 'inmueble';
	document.getElementById('tabla_ubicacion_actual_inmueble').style.display = 'block';
	document.getElementById('tabla_ubicacion_actual_mueble').style.display = 'none';
	document.getElementById('tabla_organizacion_destino_inmueble').style.display = 'block';
	document.getElementById('tabla_organizacion_destino_mueble').style.display = 'none';
	document.getElementById('imagen_seleccionar_codigo_bien').onclick = mostrarEmergenteCodigoBien;
}


function mostrarMueble(){
	limpiarCampos();
	document.getElementById('tipo').value = 'mueble';
	document.getElementById('tabla_ubicacion_actual_inmueble').style.display = 'none';
	document.getElementById('tabla_ubicacion_actual_mueble').style.display = 'block';
	document.getElementById('tabla_organizacion_destino_inmueble').style.display = 'none';
	document.getElementById('tabla_organizacion_destino_mueble').style.display = 'block';
	document.getElementById('imagen_seleccionar_codigo_bien').onclick = mostrarEmergenteCodigoBien;
}




function seleccionarNivelEmergente(idnivel){
	setTimeout("document.getElementById('nivel_organizacional_mueble').value = "+idnivel+"",200);
	setTimeout("document.getElementById('nivel_organizacional_mueble').disabled = true",200);
}




function mostrarEmergenteCodigoBien(){
	if(document.getElementById('tipo').value == 'inmueble'){
		window.open('modulos/bienes/lib/listar_inmuebles.php?tipo_busqueda=movimiento_individual','','resizable=no, scrollbars=yes, width=900, height=600');
	}else{
		window.open('modulos/bienes/lib/listar_muebles.php','','resizable=no, scrollbars=yes, width=900, height=600');
	}	
}


function seleccionarNivelOrganizacionalMueble(idorganizacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_bienes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			//document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_nivel_organizacional_mueble').innerHTML = ajax.responseText;
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&ejecutar=seleccionarNivelOrganizacionalMueble");	
}







function seleccionarNivelOrganizacionalDestinoMueble(idorganizacion){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_bienes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			//document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_nivel_organizacional_destino_mueble').innerHTML = ajax.responseText;
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&ejecutar=seleccionarNivelOrganizacionalDestinoMueble");		
}







function ingresarMovimiento(){
	  var tipo = document.getElementById('tipo').value;
	  var codigo_bien = document.getElementById('codigo_bien').value;
	  var idcatalogo_bienes = document.getElementById('idcatalogo_bienes').value;
	  var especificaciones = document.getElementById('especificaciones').value;
	  if(tipo == "mueble"){
		  var idorganizacion_actual = document.getElementById('organizacion_mueble').value;
	  	  var idnivel_organizacional_actual = document.getElementById('nivel_organizacional_mueble').value;
	  }else{
		  var idorganizacion_actual = document.getElementById('organizacion_inmueble').value;
	  }
	  
	  var nro_orden = document.getElementById('nro_orden').value;
	  var fecha_orden = document.getElementById('fecha_orden').value;
	  var idtipo_movimiento = document.getElementById('idtipo_movimiento').value;
	  if(tipo == "mueble"){
		  	var idorganizacion_destino = document.getElementById('organizacion_destino_mueble').value;
	  		var idnivel_organizacional_destino = document.getElementById('nivel_organizacional_destino_mueble').value;
	  }else{
		  	var idorganizacion_destino = document.getElementById('organizacion_destino_inmueble').value;
	  }

	  
	  var fecha_movimiento = document.getElementById('fecha_movimiento').value;
	  var fecha_regreso = document.getElementById('fecha_regreso').value;
	  if(document.getElementById('retorno_automatico').checked == true){
			var retorno_automatico = "si";  
	  }else{
			var retorno_automatico = "no";    
	  }
	  
	  var justificacion_movimiento = document.getElementById('justificacion_movimiento').value;
	  var idbien = document.getElementById('idbien').value;
	  var tipo_bien = document.getElementById('tipo_bien').value;
	  var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_bienes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "Se registro el mopvimiento con Exito");
				consultarMovimiento(ajax.responseText);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idbien="+idbien+"&tipo_bien="+tipo_bien+"&tipo="+tipo+"&codigo_bien="+codigo_bien+"&idcatalogo_bienes="+idcatalogo_bienes+"&especificaciones="+especificaciones+"&idorganizacion_actual="+idorganizacion_actual+"&idnivel_organizacional_actual="+idnivel_organizacional_actual+"&nro_orden="+nro_orden+"&fecha_orden="+fecha_orden+"&idtipo_movimiento="+idtipo_movimiento+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&fecha_movimiento="+fecha_movimiento+"&fecha_regreso="+fecha_regreso+"&retorno_automatico="+retorno_automatico+"&justificacion_movimiento="+justificacion_movimiento+"&ejecutar=ingresarMovimiento");

}


function bloquearCampos(){
  document.getElementById('tipo').disabled = true;
  document.getElementById('codigo_bien').disabled = true;
  document.getElementById('idcatalogo_bienes').disabled = true;
  document.getElementById('catalogo_bienes').disabled = true;
  
  document.getElementById('especificaciones').disabled = true;
  document.getElementById('organizacion_mueble').disabled = true;
  document.getElementById('nivel_organizacional_mueble').disabled = true;
  document.getElementById('organizacion_inmueble').disabled = true;
  
  
  document.getElementById('nro_orden').disabled = true;
  document.getElementById('fecha_orden').disabled = true;
  document.getElementById('idtipo_movimiento').disabled = true;
  
  document.getElementById('organizacion_destino_mueble').disabled = true;
  document.getElementById('nivel_organizacional_destino_mueble').disabled = true;
  document.getElementById('organizacion_destino_inmueble').disabled = true;
  
  document.getElementById('fecha_movimiento').disabled = true;
  document.getElementById('fecha_regreso').disabled = true;
  document.getElementById('retorno_automatico').disabled = true;
  document.getElementById('justificacion_movimiento').disabled = true;
  document.getElementById('idbien').disabled = true;
  document.getElementById('tipo_bien').disabled = true;

}


function desbloquearDatos(){
  document.getElementById('tipo').disabled = false;
  document.getElementById('codigo_bien').disabled = false;
  document.getElementById('idcatalogo_bienes').disabled = false;
  document.getElementById('catalogo_bienes').disabled = false;
  
  document.getElementById('especificaciones').disabled = false;
  document.getElementById('organizacion_mueble').disabled = false;
  document.getElementById('nivel_organizacional_mueble').disabled = false;
  document.getElementById('organizacion_inmueble').disabled = false;
  
  
  document.getElementById('nro_orden').disabled = false;
  document.getElementById('fecha_orden').disabled = false;
  document.getElementById('idtipo_movimiento').disabled = false;
  
  document.getElementById('organizacion_destino_mueble').disabled = false;
  document.getElementById('nivel_organizacional_destino_mueble').disabled = false;
  document.getElementById('organizacion_destino_inmueble').disabled = false;
  
  document.getElementById('fecha_movimiento').disabled = false;
  document.getElementById('fecha_regreso').disabled = false;
  document.getElementById('retorno_automatico').disabled = false;
  document.getElementById('justificacion_movimiento').disabled = false;
  document.getElementById('idbien').disabled = false;
  document.getElementById('tipo_bien').disabled = false;		
}

function limpiarCampos(){
  document.getElementById('tipo').value = 0;
  document.getElementById('codigo_bien').value = "";
  document.getElementById('idcatalogo_bienes').value = "";
  document.getElementById('catalogo_bienes').value = "";
  
  document.getElementById('especificaciones').value = "";
  document.getElementById('organizacion_mueble').value = 0;
  document.getElementById('nivel_organizacional_mueble').value = 0;
  document.getElementById('organizacion_inmueble').value = 0;
  
  
  document.getElementById('nro_orden').value = "";
  document.getElementById('fecha_orden').value = "";
  document.getElementById('idtipo_movimiento').value = 0;
  
  document.getElementById('organizacion_destino_mueble').value = 0;
  document.getElementById('nivel_organizacional_destino_mueble').value = 0;
  document.getElementById('organizacion_destino_inmueble').value = 0;
  
  document.getElementById('fecha_movimiento').value = "";
  document.getElementById('fecha_regreso').value = "";
  document.getElementById('retorno_automatico').checked = false;
  document.getElementById('justificacion_movimiento').value = "";
  document.getElementById('idbien').value = "";
  document.getElementById('tipo_bien').value = "";
  document.getElementById('boton_procesar').style.display = 'block';
  //document.getElementById('boton_modificar').style.display = 'none';
  document.getElementById('boton_anular').style.display = 'none';
}




function consultarMovimiento(idmovimiento){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_bienes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){				
				partes = ajax.responseText.split("|.|");
				  if(partes[0] == "mueble"){
					mostrarMueble();
				  }else{
					mostrarInmueble();  
				  }
				  document.getElementById('idmovimiento').value = idmovimiento;
				  document.getElementById('tipo').value = partes[0];
				  document.getElementById('codigo_bien').value = partes[1];
				  document.getElementById('idcatalogo_bienes').value =  partes[2];
				  document.getElementById('catalogo_bienes').value = partes[3];
				  document.getElementById('especificaciones').value = partes[4];	  
				  if(partes[0] == "mueble"){
					document.getElementById('organizacion_mueble').value = partes[5];
					seleccionarNivelOrganizacionalMueble(partes[5]);
				  }else{
					document.getElementById('organizacion_inmueble').value = partes[5];  
				  }
				  document.getElementById('nro_orden').value = partes[7];
				  document.getElementById('fecha_orden').value = partes[8];
				  document.getElementById('idtipo_movimiento').value = partes[9];
				  if(partes[0] == "mueble"){
					document.getElementById('organizacion_destino_mueble').value = partes[10];
					seleccionarNivelOrganizacionalDestinoMueble(partes[10]);
				  }else{
					document.getElementById('organizacion_destino_inmueble').value = partes[10];  
				  }
				  document.getElementById('fecha_movimiento').value = partes[12];
				  document.getElementById('fecha_regreso').value = partes[13];
				  
				  if(partes[14] == 'si'){
				 	 document.getElementById('retorno_automatico').checked = true;
				  }else{
				  	 document.getElementById('retorno_automatico').checked = false;
				  }
				  document.getElementById('justificacion_movimiento').value = partes[15];
				  document.getElementById('idbien').value = partes[16];
				  document.getElementById('tipo_bien').value = partes[17];
				  if(partes[18] == "procesado"){
					  document.getElementById('boton_procesar').style.display = 'none';
					  document.getElementById('boton_anular').style.display = 'block';
				}else{
						document.getElementById('boton_procesar').style.display = 'none';
					  document.getElementById('boton_anular').style.display = 'none';
				}
				  
				 // document.getElementById('boton_modificar').style.display = 'block';
				  
				  setTimeout("document.getElementById('nivel_organizacional_mueble').value = "+partes[6]+"",600);
				  setTimeout("document.getElementById('nivel_organizacional_destino_mueble').value = "+partes[11]+"",700);
				  document.getElementById("divCargando").style.display = "none";
				  if(partes[18] == "procesado" || partes[18] ==  "anulado" || partes[18] == "devuelto"){
						bloquearCampos();  
				  }
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=consultarMovimiento");	
}













function modificarMovimiento(){
	  var idmovimiento = document.getElementById('idmovimiento').value;
	  var tipo = document.getElementById('tipo').value;
	  var codigo_bien = document.getElementById('codigo_bien').value;
	  var idcatalogo_bienes = document.getElementById('idcatalogo_bienes').value;
	  var especificaciones = document.getElementById('especificaciones').value;
	  if(tipo == "mueble"){
		  var idorganizacion_actual = document.getElementById('organizacion_mueble').value;
	  	  var idnivel_organizacional_actual = document.getElementById('nivel_organizacional_mueble').value;
	  }else{
		  var idorganizacion_actual = document.getElementById('organizacion_inmueble').value;
	  }
	  
	  var nro_orden = document.getElementById('nro_orden').value;
	  var fecha_orden = document.getElementById('fecha_orden').value;
	  var idtipo_movimiento = document.getElementById('idtipo_movimiento').value;
	  if(tipo == "mueble"){
		  	var idorganizacion_destino = document.getElementById('organizacion_destino_mueble').value;
	  		var idnivel_organizacional_destino = document.getElementById('nivel_organizacional_destino_mueble').value;
	  }else{
		  	var idorganizacion_destino = document.getElementById('organizacion_destino_inmueble').value;
	  }

	  
	  var fecha_movimiento = document.getElementById('fecha_movimiento').value;
	  var fecha_regreso = document.getElementById('fecha_regreso').value;
	  if(document.getElementById('retorno_automatico').checked == true){
			var retorno_automatico = "si";  
	  }else{
			var retorno_automatico = "no";    
	  }
	  
	  var justificacion_movimiento = document.getElementById('justificacion_movimiento').value;
	  var idbien = document.getElementById('idbien').value;
	  var tipo_bien = document.getElementById('tipo_bien').value;
	  var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_bienes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "Se modifico el movimiento con exito");
				limpiarCampos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&tipo="+tipo+"&codigo_bien="+codigo_bien+"&idcatalogo_bienes="+idcatalogo_bienes+"&especificaciones="+especificaciones+"&idorganizacion_actual="+idorganizacion_actual+"&idnivel_organizacional_actual="+idnivel_organizacional_actual+"&nro_orden="+nro_orden+"&fecha_orden="+fecha_orden+"&idtipo_movimiento="+idtipo_movimiento+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&fecha_movimiento="+fecha_movimiento+"&fecha_regreso="+fecha_regreso+"&retorno_automatico="+retorno_automatico+"&justificacion_movimiento="+justificacion_movimiento+"&ejecutar=modificarMovimiento");

}





function eliminarMovimiento(){
	if(confirm("Realmente desea Anular el Movimiento")){
	var idmovimiento = document.getElementById('idmovimiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_bienes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "Se anulo con exito el movimiento");
				limpiarCampos();
				desbloquearDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=eliminarMovimiento");
	}
}
