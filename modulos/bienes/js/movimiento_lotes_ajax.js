// JavaScript Document

function mostrarInmueble(){
	limpiarCampos();
	
	document.getElementById('tipo').value = 'inmueble';
	document.getElementById('tabla_ubicacion_actual_inmueble').style.display = 'block';
	document.getElementById('tabla_ubicacion_actual_mueble').style.display = 'none';
	document.getElementById('tabla_organizacion_destino_inmueble').style.display = 'block';
	document.getElementById('tabla_organizacion_destino_mueble').style.display = 'none';
	//document.getElementById('imagen_seleccionar_codigo_bien').onclick = mostrarEmergenteCodigoBien;
}


function mostrarMueble(){
	limpiarCampos();
	document.getElementById('tipo').value = 'mueble';
	document.getElementById('tabla_ubicacion_actual_inmueble').style.display = 'none';
	document.getElementById('tabla_ubicacion_actual_mueble').style.display = 'block';
	document.getElementById('tabla_organizacion_destino_inmueble').style.display = 'none';
	document.getElementById('tabla_organizacion_destino_mueble').style.display = 'block';
	//document.getElementById('imagen_seleccionar_codigo_bien').onclick = mostrarEmergenteCodigoBien;
	
}




function buscarInmuebles(idorganizacion){
	var estado = document.getElementById('estado').value;
	var idmovimiento = document.getElementById('idmovimiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
				document.getElementById('tabla_bienes').style.display = 'block';
				document.getElementById('celda_bienes_actuales').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("estado="+estado+"&idmovimiento="+idmovimiento+"&organizacion="+idorganizacion+"&tipo=inmueble&ejecutar=mostrarInmueble");	
}


function buscarMuebles(idnivel_organizacional){
	var estado = document.getElementById('estado').value;
	var ajax=nuevoAjax();
	var idmovimiento = document.getElementById('idmovimiento').value;
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
				document.getElementById('tabla_bienes').style.display = 'block';
				document.getElementById('celda_bienes_actuales').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("estado="+estado+"&idmovimiento="+idmovimiento+"&idnivel_organizacional="+idnivel_organizacional+"&tipo=mueble&ejecutar=mostrarInmueble");	
}




function listarBienesSeleccionados(idmovimiento){
	var estado = document.getElementById('estado').value;
	var tipo = document.getElementById('tipo').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_bienes_seleccionados').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo="+tipo+"&estado="+estado+"&idmovimiento="+idmovimiento+"&ejecutar=listarBienesSeleccionados");
}




function seleccionarNivelOrganizacionalMueble(idorganizacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
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
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);
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



function limpiarCampos(){
	document.getElementById('tabla_bienes').style.display = 'none';
  document.getElementById('celda_bienes_actuales').innerHTML = "";
  document.getElementById('tipo').value = 0;
  //document.getElementById('codigo_bien').value = "";
  //document.getElementById('idcatalogo_bienes').value = "";
  //document.getElementById('catalogo_bienes').value = "";
  
  //document.getElementById('especificaciones').value = "";
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

  document.getElementById('justificacion_movimiento').value = "";
  //document.getElementById('idbien').value = "";
  //document.getElementById('tipo_bien').value = "";
  document.getElementById('boton_procesar').style.display = 'none';
  document.getElementById('boton_modificar').style.display = 'none';
  document.getElementById('boton_anular').style.display = 'none';
}




function procesarMovmientos(){
	 if(confirm("Seguro desea procesar este movimiento?")){
	 var idmovimiento = document.getElementById('idmovimiento').value;
	  var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "noTieneBienes"){
					mostrarMensajes("error", "Disculpe debe seleccionar al menos un bien para poder procesar este movimiento");
				}else{
					consultarMovimiento(idmovimiento);
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=procesarMovmientos");
	 }
}





function bloquearCampos(){
	document.getElementById('tipo').disabled = true;
	document.getElementById('nro_orden').disabled = true;
	document.getElementById('fecha_orden').disabled = true;
	document.getElementById('idtipo_movimiento').disabled = true;
	document.getElementById('fecha_movimiento').disabled = true;
	document.getElementById('justificacion_movimiento').disabled = true;
	document.getElementById('organizacion_inmueble').disabled = true;
	document.getElementById('organizacion_mueble').disabled = true;
	document.getElementById('nivel_organizacional_mueble').disabled = true;
	document.getElementById('organizacion_destino_inmueble').disabled = true;
	document.getElementById('organizacion_destino_mueble').disabled = true;
	document.getElementById('nivel_organizacional_destino_mueble').disabled = true;
}




function desbloquearCampos(){
	document.getElementById('tipo').disabled = false;
	document.getElementById('nro_orden').disabled = false;
	document.getElementById('fecha_orden').disabled = false;
	document.getElementById('idtipo_movimiento').disabled = false;
	document.getElementById('fecha_movimiento').disabled = false;
	document.getElementById('justificacion_movimiento').disabled = false;
	document.getElementById('organizacion_inmueble').disabled = false;
	document.getElementById('organizacion_mueble').disabled = false;
	document.getElementById('nivel_organizacional_mueble').disabled = false;
	document.getElementById('organizacion_destino_inmueble').disabled = false;
	document.getElementById('organizacion_destino_mueble').disabled = false;
	document.getElementById('nivel_organizacional_destino_mueble').disabled = false;
}










function consultarMovimiento(idmovimiento){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){				
				partes = ajax.responseText.split("|.|");
				  if(partes[1] == "mueble"){
					mostrarMueble();
				  }else{
					mostrarInmueble();  
				  }
				  document.getElementById('idmovimiento').value = idmovimiento;
				  document.getElementById('celda_nro_movimiento').innerHTML = partes[0];
				  document.getElementById('nro_orden').value = partes[2];
				  document.getElementById('fecha_orden').value = partes[3];
				  document.getElementById('idtipo_movimiento').value = partes[4];
				  document.getElementById('fecha_movimiento').value = partes[5];
				  
				  
				  document.getElementById('justificacion_movimiento').value = partes[8];
				  document.getElementById('estado').value = partes[9];
				  if(document.getElementById('tipo').value == 'inmueble'){
						buscarInmuebles(document.getElementById('organizacion_inmueble').value);	
					}else{
						buscarMuebles(document.getElementById('nivel_organizacional_mueble').value);
					}
					

				  if(partes[9] != "elaboracion"){
					document.getElementById('boton_procesar').style.display = "none";
					document.getElementById('boton_modificar').style.display = "none";
					if(partes[9] != 'anulado'){
						document.getElementById('boton_anular').style.display = "block";
					}else{
						document.getElementById('boton_anular').style.display = "none";	
					}
					bloquearCampos();
					document.getElementById('celda_bienes_actuales').inneHTML = '';
					document.getElementById('celda_bienes_actuales').style.height = '0px';
					document.getElementById('titulo_bienes_actuales').innerHTML = '';
					
				  }else{
					  
					document.getElementById('boton_procesar').style.display = "block";
					document.getElementById('boton_modificar').style.display = "block";
					document.getElementById('boton_anular').style.display = "none";
					desbloquearCampos();
					//document.getElementById('celda_bienes_actuales').inneHTML = '';
					document.getElementById('celda_bienes_actuales').style.height = '150px';
					document.getElementById('titulo_bienes_actuales').innerHTML = 'Bienes Actuales';
				  }
				  document.getElementById('tabla_actual_destino').style.display = 'block';
				  
					
					listarBienesSeleccionados(idmovimiento);
					document.getElementById('boton_siguiente').style.display = 'none';
				  document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=consultarMovimiento");	
}






function modificarMovimiento(){
	var idmovimiento = document.getElementById('idmovimiento').value;
	var tipo = document.getElementById('tipo').value;
	var nro_orden = document.getElementById('nro_orden').value;
	var fecha_orden = document.getElementById('fecha_orden').value;
	var tipo_movimiento = document.getElementById('idtipo_movimiento').value;
	var fecha_movimiento = document.getElementById('fecha_movimiento').value;
	var justificacion_movimiento = document.getElementById('justificacion_movimiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Se modifico el movimiento con exito");
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&tipo="+tipo+"&nro_orden="+nro_orden+"&fecha_orden="+fecha_orden+"&tipo_movimiento="+tipo_movimiento+"&fecha_movimiento="+fecha_movimiento+"&justificacion_movimiento="+justificacion_movimiento+"&ejecutar=modificarMovimiento");

}





function guardarDatosBasicos(){
	var tipo = document.getElementById('tipo').value;
	var nro_orden = document.getElementById('nro_orden').value;
	var fecha_orden = document.getElementById('fecha_orden').value;
	var tipo_movimiento = document.getElementById('idtipo_movimiento').value;
	var fecha_movimiento = document.getElementById('fecha_movimiento').value;
	var justificacion_movimiento = document.getElementById('justificacion_movimiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('idmovimiento').value = ajax.responseText;
				document.getElementById('boton_procesar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_anular').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("tipo="+tipo+"&nro_orden="+nro_orden+"&fecha_orden="+fecha_orden+"&tipo_movimiento="+tipo_movimiento+"&fecha_movimiento="+fecha_movimiento+"&justificacion_movimiento="+justificacion_movimiento+"&ejecutar=guardarDatosBasicos");
}





function seleccionarBien(idbien, codigo_bien, tipo_bien, idmovimiento){
	if(document.getElementById('tipo').value == "inmueble"){
		var idorganizacion_actual = document.getElementById('organizacion_inmueble').value;
		var idnivel_organizacional_actual = 0;
		var idorganizacion_destino = document.getElementById('organizacion_destino_inmueble').value;
		var idnivel_organizacional_destino = 0;
	}else{
		var idorganizacion_actual = document.getElementById('organizacion_mueble').value;
		var idnivel_organizacional_actual = document.getElementById('nivel_organizacional_mueble').value;
		var idorganizacion_destino = document.getElementById('organizacion_destino_mueble').value;
		var idnivel_organizacional_destino = document.getElementById('nivel_organizacional_destino_mueble').value;
	}
	if(idorganizacion_destino == 0){
		mostrarMensajes("error", "Disculpe debe seleccionar la Organizacion Destino");
	}else if(document.getElementById('tipo').value == "mueble" && idnivel_organizacional_destino == 0){
		mostrarMensajes("error", "Disculpe debe seleccionar el Nivel Organizacional del destino");
	}else if(document.getElementById('tipo').value == 'inmueble' && (idorganizacion_destino == idorganizacion_actual)){
		mostrarMensajes("error", "Disculpe no puede realizar un movimiento de un inmueble dentro de la misma organizacion");
	}else if(document.getElementById('tipo').value == 'mueble' && (idnivel_organizacional_actual == idnivel_organizacional_destino)){
		mostrarMensajes("error", "Disculpe no puede realizar un movimiento de un mueble dentro del mismo nivel organizacional");
	}else{
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(document.getElementById('tipo').value == 'inmueble'){
						buscarInmuebles(document.getElementById('organizacion_inmueble').value);	
					}else{
						buscarMuebles(document.getElementById('nivel_organizacional_mueble').value);
					}
					
					listarBienesSeleccionados(idmovimiento);
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idorganizacion_actual="+idorganizacion_actual+"&idnivel_organizacional_actual="+idnivel_organizacional_actual+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&idorganizacion_actual="+idorganizacion_actual+"&idnivel_organizacional_actual="+idnivel_organizacional_actual+"&idorganizacion_destino="+idorganizacion_destino+"&idnivel_organizacional_destino="+idnivel_organizacional_destino+"&idmovimiento="+idmovimiento+"&tipo_bien="+tipo_bien+"&codigo_bien="+codigo_bien+"&idbien="+idbien+"&ejecutar=seleccionarBien");
	}
}







function eliminarSeleccionado(idbienes_seleccionados){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(document.getElementById('tipo').value == 'inmueble'){
					buscarInmuebles(document.getElementById('organizacion_inmueble').value);	
				}else{
					buscarMuebles(document.getElementById('nivel_organizacional_mueble').value);
				}
				listarBienesSeleccionados(document.getElementById('idmovimiento').value);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idbienes_seleccionados="+idbienes_seleccionados+"&ejecutar=eliminarSeleccionado");
}




function anularMovimiento(){
	if(confirm("Esta seguro que desea Anular este Movimiento?")){
	var idmovimiento = document.getElementById('idmovimiento').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/movimiento_lotes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				consultarMovimiento(idmovimiento);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=anularMovimiento");
	}
}

