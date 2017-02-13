// JavaScript Document

function ingresarTiposMovimientos(codigo, denominacion, afecta1, afecta2){
	if(codigo == "" || denominacion == "" || (document.getElementById(afecta1).checked == false && document.getElementById(afecta2).checked == false)){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder registrar el Tipo de Movimiento");
	}else{
		
		formato = document.getElementById(formato).value;
		
		if(document.getElementById(afecta1).checked == true){
			afecta = document.getElementById(afecta1).value;	
		}else if(document.getElementById(afecta2).checked == true){
			afecta = document.getElementById(afecta2).value;
		}
		
		
		if(document.getElementById('tipo_inmueble').checked == true){
			var tipo_inmueble = "si";	
		}else{
			var tipo_inmueble = "no";
		}
		
		if(document.getElementById('tipo_mueble').checked == true){
			var tipo_mueble = "si";	
		}else{
			var tipo_mueble = "no";
		}
		
		
		if(document.getElementById('origen_bien_0').checked == true){
			var origen_bien = "nuevo";	
		}else if(document.getElementById('origen_bien_1').checked == true){
			var origen_bien = "existente";
		}
		
		if(document.getElementById('estado_bien_0').checked == true){
			var estado_bien = "activo";	
		}else if(document.getElementById('estado_bien_1').checked == true){
			var estado_bien = "desincorporado";
		}
		
		if(document.getElementById('momento_afectado_0').checked == true){
			var momento_afectado = "inicial";	
		}else if(document.getElementById('momento_afectado_1').checked == true){
			var momento_afectado = "movimientos";
		}
		
		
		if(document.getElementById('describir_motivo').checked == true){
			var describir_motivo = "si";	
		}else{
			var describir_motivo = "no";
		}
		
		if(document.getElementById('memoria_fotografica').checked == true){
			var memoria_fotografica = "si";	
		}else{
			var memoria_fotografica = "no";
		}
		
		
		
		if(document.getElementById('cambia_ubicacion').checked == true){
			var cambia_ubicacion = "si";	
		}else{
			var cambia_ubicacion = "no";
		}
		
		
		
		
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/tipos_movimientos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Los datos fueron ingresados con exito");	
					}else{
						mostrarMensajes("error", "Disculpe los datos no fueron registrados con exito, por favor intente de nuevo mas tarde ( "+ajax.responseText+" )");
					}
					consultarTiposMovimientos();
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("tipo_inmueble="+tipo_inmueble+"&tipo_mueble="+tipo_mueble+"&origen_bien="+origen_bien+"&describir_motivo="+describir_motivo+"&memoria_fotografica="+memoria_fotografica+"&cambia_ubicacion="+cambia_ubicacion+"&afecta="+afecta+"&codigo="+codigo+"&denominacion="+denominacion+"&momento_afectado="+momento_afectado+"&estado_bien="+estado_bien+"&formato="+formato+"&ejecutar=ingresarTiposMovimientos");
	}
}



function consultarTiposMovimientos(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/tipos_movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaTiposMovimientos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=consultarTiposMovimientos");		
}




function mostrarEditar(id_tipo_movimiento, codigo, denominacion, afecta, tipo_mueble, tipo_inmueble, origen_bien, estado_bien, describir_motivo, memoria_fotografica, cambia_ubicacion, momento_afectado, formato){
	
	document.getElementById('id_tipo_movimiento').value = id_tipo_movimiento;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('formato').value = formato;
	
	
	
	if(tipo_mueble == "si"){
		document.getElementById('tipo_mueble').checked = true;
	}else{
		document.getElementById('tipo_mueble').checked = false;
	}
	
	if(tipo_inmueble == "si"){
		document.getElementById('tipo_inmueble').checked = true;
	}else{
		document.getElementById('tipo_inmueble').checked = false;
	}
	

	if(origen_bien == "nuevo"){
		document.getElementById('origen_bien_0').checked = true;
	}else if(origen_bien == "existente"){
		document.getElementById('celda_estado').style.display = 'block';
		document.getElementById('origen_bien_1').checked = true;
		
	}
	
	if(estado_bien == "activo"){
		document.getElementById('estado_bien_0').checked = true;
	}else if(origen_bien == "desincorporado"){
		document.getElementById('estado_bien_1').checked = true;
	}
	
	//alert(momento_afectado);
	if(momento_afectado == "inicial"){
		document.getElementById('momento_afectado_0').checked = true;	
	}else if(momento_afectado == "movimientos"){
		document.getElementById('momento_afectado_1').checked = true;
	}
	
	
	if(describir_motivo == "si"){
		document.getElementById('describir_motivo').checked = true;	
	}else{
		document.getElementById('describir_motivo').checked = false;	
	}
	
	
	
	if(memoria_fotografica == "si"){
		document.getElementById('memoria_fotografica').checked = true;	
	}else{
		document.getElementById('memoria_fotografica').checked = false;	
	}
	
	if(cambia_ubicacion == "si"){
		document.getElementById('cambia_ubicacion').checked = true;	
	}else{
		document.getElementById('cambia_ubicacion').checked = false;	
	}
	
	if(afecta == 1){
		document.getElementById('afecta_incorporacion').checked= true;
	}else if(afecta == 2){
		document.getElementById('afecta_desincorporacion').checked = true;	
	}
	
	document.getElementById('id_tipo_movimiento').disabled = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('afecta_incorporacion').disabled = false;
	document.getElementById('afecta_desincorporacion').disabled = false;

	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'none';
}




function mostrarEliminar(id_tipo_movimiento, codigo, denominacion, afecta, tipo_mueble, tipo_inmueble, origen_bien, estado_bien, describir_motivo, memoria_fotografica, cambia_ubicacion, momento_afectado, formato){
	
	document.getElementById('id_tipo_movimiento').value = id_tipo_movimiento;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('formato').value = formato;
	
	if(tipo_mueble == "si"){
		
		document.getElementById('tipo_mueble').checked = true;
	}else{
		document.getElementById('tipo_mueble').checked = false;
	}
	
	if(tipo_inmueble == "si"){
		document.getElementById('tipo_inmueble').checked = true;
	}else{
		document.getElementById('tipo_inmueble').checked = false;
	}
	

	if(origen_bien == "nuevo"){
		document.getElementById('origen_bien_0').checked = true;
	}else if(origen_bien == "existente"){
		document.getElementById('celda_estado').style.display = 'block';
		document.getElementById('origen_bien_1').checked = true;
	}
	
	if(estado_bien == "activo"){
		document.getElementById('estado_bien_0').checked = true;
	}else if(origen_bien == "desincorporado"){
		document.getElementById('estado_bien_1').checked = true;
	}
	
	if(momento_afectado == "inicial"){
		document.getElementById('momento_afectado_0').checked = true;	
	}else if(momento_afectado == "movimientos"){
		document.getElementById('momento_afectado_1').checked = true;
	}
	
	if(describir_motivo == "si"){
		document.getElementById('describir_motivo').checked = true;	
	}else{
		document.getElementById('describir_motivo').checked = false;	
	}
	
	
	
	if(memoria_fotografica == "si"){
		document.getElementById('memoria_fotografica').checked = true;	
	}else{
		document.getElementById('memoria_fotografica').checked = false;	
	}
	
	if(cambia_ubicacion == "si"){
		document.getElementById('cambia_ubicacion').checked = true;	
	}else{
		document.getElementById('cambia_ubicacion').checked = false;	
	}
	
	if(afecta == 1){
		document.getElementById('afecta_incorporacion').checked= true;
	}else if(afecta == 2){
		document.getElementById('afecta_desincorporacion').checked = true;	
	}
	
	document.getElementById('id_tipo_movimiento').disabled = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('afecta_incorporacion').disabled = false;
	document.getElementById('afecta_desincorporacion').disabled = false;

	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'block';
}



function actualizarFormulario(){
	document.getElementById('id_tipo_movimiento').value = "";
	document.getElementById('codigo').value = "";
	document.getElementById('denominacion').value = "";
	
	document.getElementById('afecta_incorporacion').checked = false;

	document.getElementById('afecta_desincorporacion').checked = false;	
	
	
	document.getElementById('tipo_mueble').checked = false;
	document.getElementById('tipo_inmueble').checked = false;
	document.getElementById('origen_bien_0').checked = false;
	document.getElementById('origen_bien_1').checked = false;
	document.getElementById('estado_bien_0').checked = false;
	document.getElementById('estado_bien_1').checked = false;
	document.getElementById('describir_motivo').checked = false;	
	document.getElementById('memoria_fotografica').checked = false;	
	document.getElementById('cambia_ubicacion').checked = false;	
		
	document.getElementById('momento_afectado_0').checked = false;
	document.getElementById('momento_afectado_1').checked = false;
	
	document.getElementById('id_tipo_movimiento').disabled = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('afecta_incorporacion').disabled = false;
	document.getElementById('afecta_desincorporacion').disabled = false;
	
	document.getElementById('botonProcesar').style.display = 'block';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'none';
	document.getElementById('celda_estado').style.display='none';
	document.getElementById('formato').value = '0';
}



function modificarTiposMovimientos(idtipos_movimientos, codigo, denominacion, afecta1, afecta2){
	if(codigo == "" || denominacion == "" || (document.getElementById(afecta1).checked == false && document.getElementById(afecta2).checked == false)){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder Modificar el Tipo de Movimiento");
	}else{
		if(document.getElementById(afecta1).checked == true){
			afecta = document.getElementById(afecta1).value;	
		}else if(document.getElementById(afecta2).checked == true){
			afecta = document.getElementById(afecta2).value;		
		}
		
		formato = document.getElementById('formato').value;
		
		if(document.getElementById('tipo_inmueble').checked == true){
			var tipo_inmueble = "si";	
		}else{
			var tipo_inmueble = "no";
		}
		
		if(document.getElementById('tipo_mueble').checked == true){
			var tipo_mueble = "si";	
		}else{
			var tipo_mueble = "no";
		}
		
		
		if(document.getElementById('origen_bien_0').checked == true){
			var origen_bien = "nuevo";	
		}else if(document.getElementById('origen_bien_1').checked == true){
			var origen_bien = "existente";
		}
		
		if(document.getElementById('estado_bien_0').checked == true){
			var estado_bien = "activo";	
		}else if(document.getElementById('estado_bien_1').checked == true){
			var estado_bien = "desincorporado";
		}
		
		if(document.getElementById('momento_afectado_0').checked == true){
			var momento_afectado = "inicial";	
		}else if(document.getElementById('momento_afectado_1').checked == true){
			var momento_afectado = "movimientos";
		}
		
		if(document.getElementById('describir_motivo').checked == true){
			var describir_motivo = "si";	
		}else{
			var describir_motivo = "no";
		}
		
		if(document.getElementById('memoria_fotografica').checked == true){
			var memoria_fotografica = "si";	
		}else{
			var memoria_fotografica = "no";
		}
		
		
		
		if(document.getElementById('cambia_ubicacion').checked == true){
			var cambia_ubicacion = "si";	
		}else{
			var cambia_ubicacion = "no";
		}
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/tipos_movimientos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Los datos fueron modificados con exito");	
					}else{
						mostrarMensajes("error", "Disculpe los datos no fueron modificados con exito, por favor intente de nuevo mas tarde");
					}
					consultarTiposMovimientos();
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("tipo_inmueble="+tipo_inmueble+"&tipo_mueble="+tipo_mueble+"&origen_bien="+origen_bien+"&describir_motivo="+describir_motivo+"&memoria_fotografica="+memoria_fotografica+"&cambia_ubicacion="+cambia_ubicacion+"&idtipos_movimientos="+idtipos_movimientos+"&codigo="+codigo+"&afecta="+afecta+"&denominacion="+denominacion+"&momento_afectado="+momento_afectado+"&estado_bien="+estado_bien+"&formato="+formato+"&ejecutar=modificarTiposMovimientos");
	}
}







function eliminarTiposMovimientos(idtipos_movimientos){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/tipos_movimientos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Los datos fieron eliminados con exito");
				}else{
					mostrarMensajes("error", "Disculpe los datos no fueron eliminados con exito, por favor intente de nuevo mas tarde");
				}
				consultarTiposMovimientos();
				actualizarFormulario();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipos_movimientos="+idtipos_movimientos+"&ejecutar=eliminarTiposMovimientos");		
}