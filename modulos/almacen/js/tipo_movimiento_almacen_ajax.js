// JavaScript Document

function ingresarMovimiento(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	if(codigo == ''){
		mostrarMensajes("error", "Disculpe debe escribir el Codigo");
	}else if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		var afecta = 0;
		var origen_materia = 0;
		var comprobante = document.getElementById('comprobante').value;
		var numero_comprobante = document.getElementById('numero_comprobante').value;
		var documento_origen = document.getElementById('documento_origen').value;
		if(document.getElementById('afecta_entrada').checked == true){
			afecta = document.getElementById('afecta_entrada').value;	
		}
		if(document.getElementById('afecta_salida').checked == true){
			afecta = document.getElementById('afecta_salida').value;	
		}
		if(document.getElementById('afecta_transferencia').checked == true){
			afecta = document.getElementById('afecta_transferencia').value;	
		}
		
		if(document.getElementById('origen_nuevo').checked == true){
			origen_materia = document.getElementById('origen_nuevo').value;	
		}
		if(document.getElementById('origen_existente').checked == true){
			origen_materia = document.getElementById('origen_existente').value;	
		}
		if (document.getElementById('activo').checked == true){
			var activo = 1;
		}else{
			var activo = 0;
		}
		if (document.getElementById('describir_motivo').checked == true){
			var describir_motivo = 1;
		}else{
			var describir_motivo = 0;
		}
		if (document.getElementById('memoria_fotografica').checked == true){
			var memoria_fotografica = 1;
		}else{
			var memoria_fotografica = 0;
		}
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/tipo_movimiento_almacen_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se ingresaron los datos con exito");
					listarTipoMovimiento();
				}else if(ajax.responseText == "existe"){
					mostrarMensajes("error", "El Concepto que intenta ingresar ya existe");
				}
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("codigo="+codigo+"&denominacion="+denominacion+"&comprobante="+comprobante+"&numero_comprobante="+numero_comprobante+"&afecta="+afecta+"&activo="+activo+"&documento_origen="+documento_origen+"&describir_motivo="+describir_motivo+"&memoria_fotografica="+memoria_fotografica+"&origen_materia="+origen_materia+"&ejecutar=ingresarMovimiento");
	}
}



function limpiarDatos(){
	document.getElementById('codigo').value = '';
	document.getElementById('denominacion').value = '';
	document.getElementById('afecta_entrada').checked = false;
	document.getElementById('afecta_salida').checked = false;
	document.getElementById('afecta_transferencia').checked = false;	
	document.getElementById('origen_nuevo').checked = false;
	document.getElementById('origen_existente').checked = false;
	document.getElementById('activo').checked = false;
	document.getElementById('describir_motivo').checked = false;
	document.getElementById('memoria_fotografica').checked = false;
	document.getElementById('numero_comprobante').value = '';
	document.getElementById('comprobante').value = '';
	document.getElementById('documento_origen').value = '0';
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('comprobante').disabled = false;
	document.getElementById('numero_comprobante').disabled = false;
	document.getElementById('activo').disabled = false;
	document.getElementById('describir_motivo').disabled = false;
	document.getElementById('memoria_fotografica').disabled = false;
	
	document.getElementById('afecta_entrada').disabled = false;
	document.getElementById('afecta_salida').disabled = false;
	document.getElementById('afecta_transferencia').disabled = false;
	document.getElementById('afecta_traslado').disabled = false;
	document.getElementById('origen_nuevo').disabled = false;
	document.getElementById('origen_existente').disabled = false;
	document.getElementById('documento_origen').disabled = false;
	
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function listarTipoMovimiento(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/tipo_movimiento_almacen_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaTipoMovimiento').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarTipoMovimiento");		
}




function modificarMovimiento(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var id_tipo_movimiento_almacen = document.getElementById('id_tipo_movimiento_almacen').value;

	if(denominacion == ''){
		mostrarMensajes("error", "Disculpe debe escribir la Denominacion");
	}else{
		var afecta = 0;
		var origen_materia = 0;
		var comprobante = document.getElementById('comprobante').value;
		var numero_comprobante = document.getElementById('numero_comprobante').value;
		var documento_origen = document.getElementById('documento_origen').value;
		if(document.getElementById('afecta_entrada').checked == true){
			afecta = document.getElementById('afecta_entrada').value;	
		}
		if(document.getElementById('afecta_salida').checked == true){
			afecta = document.getElementById('afecta_salida').value;	
		}
		if(document.getElementById('afecta_transferencia').checked == true){
			afecta = document.getElementById('afecta_transferencia').value;	
		}
		if(document.getElementById('afecta_traslado').checked == true){
			afecta = document.getElementById('afecta_traslado').value;	
		}
		if(document.getElementById('origen_nuevo').checked == true){
			origen_materia = document.getElementById('origen_nuevo').value;	
		}
		if(document.getElementById('origen_existente').checked == true){
			origen_materia = document.getElementById('origen_existente').value;	
		}
		if (document.getElementById('activo').checked == true){
			var activo = 1;
		}else{
			var activo = 0;
		}
		if (document.getElementById('describir_motivo').checked == true){
			var describir_motivo = 1;
		}else{
			var describir_motivo = 0;
		}
		if (document.getElementById('memoria_fotografica').checked == true){
			var memoria_fotografica = 1;
		}else{
			var memoria_fotografica = 0;
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/tipo_movimiento_almacen_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				
				mostrarMensajes("exito", "Se modificaron los datos con exito");
					listarTipoMovimiento();
					limpiarDatos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_tipo_movimiento_almacen="+id_tipo_movimiento_almacen+"&codigo="+codigo+"&denominacion="+denominacion+"&comprobante="+comprobante+"&numero_comprobante="+numero_comprobante+"&afecta="+afecta+"&activo="+activo+"&documento_origen="+documento_origen+"&describir_motivo="+describir_motivo+"&memoria_fotografica="+memoria_fotografica+"&origen_materia="+origen_materia+"&ejecutar=modificarMovimiento");
	}
}





function eliminarMovimiento(){
	var id_tipo_movimiento_almacen = document.getElementById('id_tipo_movimiento_almacen').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/tipo_movimiento_almacen_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if (ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se eliminaron los datos con exito");
				}
				if (ajax.responseText == "existe"){
					mostrarMensajes("error", "No se puede eliminar el Tipo de Movimiento porque se encuentra en uso");
				}
				listarTipoMovimiento();
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_tipo_movimiento_almacen="+id_tipo_movimiento_almacen+"&ejecutar=eliminarMovimiento");
}



function seleccionarModificar(id_tipo_movimiento_almacen, codigo, descripcion, afecta, activo, origen_materia, describir_motivo, memoria_fotografica, comprobante, numero_comprobante, documento_origen ){
	
	
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = descripcion;
	document.getElementById('comprobante').value = comprobante;
	document.getElementById('numero_comprobante').value = numero_comprobante;
	document.getElementById('documento_origen').value = documento_origen;
	document.getElementById('id_tipo_movimiento_almacen').value = id_tipo_movimiento_almacen;
	
	if (activo == 1){
		document.getElementById('activo').checked = true;
	}else{
		document.getElementById('activo').checked = false;
	}
	if (describir_motivo == 1){
		document.getElementById('describir_motivo').checked = true;
	}else{
		document.getElementById('describir_motivo').checked = false;
	}
	if (memoria_fotografica == 1){
		document.getElementById('memoria_fotografica').checked = true;
	}else{
		document.getElementById('memoria_fotografica').checked = false;
	}
	
	if (afecta == 1){
		document.getElementById('afecta_entrada').checked = true;
	}else if (afecta == 2){
		document.getElementById('afecta_salida').checked = true;
	}else if (afecta == 3){
		document.getElementById('afecta_transferencia').checked = true;
	}else if (afecta == 0 || afecta == ''){
		document.getElementById('afecta_entrada').checked = false;
		document.getElementById('afecta_salida').checked = false;
		document.getElementById('afecta_transferencia').checked = false;
	}
	
	if (origen_materia == 1){
		document.getElementById('origen_nuevo').checked = true;
	}
	if (origen_materia == 2){
		document.getElementById('origen_existente').checked = true;
	}
	if (origen_materia == 0){
		document.getElementById('origen_nuevo').checked = false;
		document.getElementById('origen_existente').checked = false;
	}
	document.getElementById('id_tipo_movimiento_almacen').value = id_tipo_movimiento_almacen;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('comprobante').disabled = false;
	document.getElementById('numero_comprobante').disabled = false;
	document.getElementById('documento_origen').disabled = false;
	document.getElementById('memoria_fotografica').disabled = false;
	document.getElementById('describir_motivo').disabled = false;
	document.getElementById('activo').disabled = false;
		document.getElementById('afecta_entrada').disabled = false;
		document.getElementById('afecta_salida').disabled = false;
		document.getElementById('afecta_transferencia').disabled = false;
		document.getElementById('origen_nuevo').disabled = false;
		document.getElementById('origen_existente').disabled = false;
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";	
}



function seleccionarEliminar(id_tipo_movimiento_almacen, codigo, descripcion, afecta, activo, origen_materia, describir_motivo, memoria_fotografica, comprobante, numero_comprobante, documento_origen ){
	
	
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = descripcion;
	document.getElementById('comprobante').value = comprobante;
	document.getElementById('numero_comprobante').value = numero_comprobante;
	document.getElementById('documento_origen').value = documento_origen;
	document.getElementById('id_tipo_movimiento_almacen').value = id_tipo_movimiento_almacen;
	
	if (activo == 1){
		document.getElementById('activo').checked = true;
	}else{
		document.getElementById('activo').checked = false;
	}
	if (describir_motivo == 1){
		document.getElementById('describir_motivo').checked = true;
	}else{
		document.getElementById('describir_motivo').checked = false;
	}
	if (memoria_fotografica == 1){
		document.getElementById('memoria_fotografica').checked = true;
	}else{
		document.getElementById('memoria_fotografica').checked = false;
	}
	
	if (afecta == 1){
		document.getElementById('afecta_entrada').checked = true;
	}else if (afecta == 2){
		document.getElementById('afecta_salida').checked = true;
	}else if (afecta == 3){
		document.getElementById('afecta_transferencia').checked = true;
	}else if (afecta == 0 || afecta == ''){
		document.getElementById('afecta_entrada').checked = false;
		document.getElementById('afecta_salida').checked = false;
		document.getElementById('afecta_transferencia').checked = false;
	}
	
	if (origen_materia == 1){
		document.getElementById('origen_nuevo').checked = true;
	}
	if (origen_materia == 2){
		document.getElementById('origen_existente').checked = true;
	}
	if (origen_materia == 0){
		document.getElementById('origen_nuevo').checked = false;
		document.getElementById('origen_existente').checked = false;
	}
	document.getElementById('id_tipo_movimiento_almacen').value = id_tipo_movimiento_almacen;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('comprobante').disabled = true;
	document.getElementById('numero_comprobante').disabled = true;
	document.getElementById('documento_origen').disabled = true;
	document.getElementById('memoria_fotografica').disabled = true;
	document.getElementById('describir_motivo').disabled = true;
	document.getElementById('activo').disabled = true;
		document.getElementById('afecta_entrada').disabled = true;
		document.getElementById('afecta_salida').disabled = true;
		document.getElementById('afecta_transferencia').disabled = true;
		document.getElementById('origen_nuevo').disabled = true;
		document.getElementById('origen_existente').disabled = true;
		
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
}