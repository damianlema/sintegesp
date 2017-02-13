// JavaScript Document

function limpiarFormulario(){
	document.getElementById('denominacion').value = '';
	document.getElementById('siglas').value = '';
	document.getElementById('debita').checked = false;
	document.getElementById('cuenta_deudora').value = '0-0';
	document.getElementById('tabla_deudora').value = '0';
	document.getElementById('idcuenta_deudora').value = '0';
	
	document.getElementById('cuenta_acreedora').value = '0-0';
	document.getElementById('tabla_acreedora').value = '0';
	document.getElementById('idcuenta_acreedora').value = '0';
	
	document.getElementById('acredita').checked = false;
	document.getElementById('denominacion').focus();
	document.getElementById('excluir_contabilidad').checked = false;
	
}




function registrarTiposMovimientos(){
	if(document.getElementById('excluir_contabilidad').checked == true){
		excluir_contabilidad = "si";	
	}else{
		excluir_contabilidad = "no";	
	}
	if((document.getElementById('debita').checked == false && document.getElementById('acredita').checked == false) || document.getElementById('denominacion').value == ""){	
		mostrarMensajes("error", "Disculpe debe completar todos los datos para poder registrar el tipo de movimiento");
	}else{
	
		var denominacion = document.getElementById('denominacion').value;
		var siglas = document.getElementById('siglas').value;
		var tabla_deudora = document.getElementById('tabla_deudora').value;
		var idcuenta_deudora = document.getElementById('idcuenta_deudora').value;
		var tabla_acreedora = document.getElementById('tabla_acreedora').value;
		var idcuenta_acreedora = document.getElementById('idcuenta_acreedora').value;
		if (excluir_contabilidad == "no"){
			if (tabla_deudora == tabla_acreedora && idcuenta_deudora == idcuenta_acreedora){
					alert("La cuenta del DEBE tiene que ser distinta a la del HABER");
					document.getElementById('cuenta_deudora').focus();
				entro='no';
			}else{
				entro='si';
			}
		}else{
			entro='si';
		}

		if (entro=='si'){
			if(document.getElementById('debita').checked == true){
				var afecta = document.getElementById('debita').value;
			}else{
				var afecta = document.getElementById('acredita').value;	
			}
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/movimientos_bancarios_ajax.php", true);	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					if(ajax.responseText.indexOf("exito") != -1){
						mostrarMensajes("exito", "El tipo de movimiento se registro con exito");
						consultarTiposMovimientos();
						limpiarFormulario();
					}
					document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("denominacion="+denominacion+"&siglas="+siglas+"&afecta="+afecta+"&tabla_deudora="+tabla_deudora+"&idcuenta_deudora="+idcuenta_deudora+"&tabla_acreedora="+tabla_acreedora+"&idcuenta_acreedora="+idcuenta_acreedora+"&excluir_contabilidad="+excluir_contabilidad+"&ejecutar=registrarTiposMovimientos");
		}
	}
}




function consultarTiposMovimientos(){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/movimientos_bancarios_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
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





function eliminarTipoMovimiento(idtipo_movimiento_bancario){
	if(confirm("Realmente desea eliminar este tipo de movimiento bancario?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/movimientos_bancarios_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					consultarTiposMovimientos();
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idtipo_movimiento_bancario="+idtipo_movimiento_bancario+"&ejecutar=eliminarTipoMovimiento");	
	}
}




function mostrarParaModificar(idtipo_movimiento_bancario, denominacion, siglas, afecta, idcuenta_debe, tabla_debe, idcuenta_haber, tabla_haber, excluir_contabilidad){
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('siglas').value = siglas;
	if(afecta == 'd'){
		document.getElementById('debita').checked = true;	
	}else{
		document.getElementById('acredita').checked = true;	
	}
	if(excluir_contabilidad == "si"){
		document.getElementById('excluir_contabilidad').checked = true;
	}else{
		document.getElementById('excluir_contabilidad').checked = false;
	}
	document.getElementById('cuenta_deudora').value = idcuenta_debe+'-'+tabla_debe;
	document.getElementById('tabla_deudora').value = tabla_debe;
	document.getElementById('idcuenta_deudora').value = idcuenta_debe;
	
	document.getElementById('cuenta_acreedora').value = idcuenta_haber+'-'+tabla_haber;
	document.getElementById('tabla_acreedora').value = tabla_haber;
	document.getElementById('idcuenta_acreedora').value = idcuenta_haber;
	
	document.getElementById('id_tipo_movimiento').value = idtipo_movimiento_bancario;
	document.getElementById('ingresar').style.display = 'none';
	document.getElementById('modificar').style.display = 'block';
}





function modificarTiposMovimientos(){
	if(document.getElementById('excluir_contabilidad').checked == true){
		excluir_contabilidad = "si";	
	}else{
		excluir_contabilidad = "no";	
	}
	if((document.getElementById('debita').checked == false && document.getElementById('acredita').checked == false) || document.getElementById('denominacion').value == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los datos para poder modificar el tipo de movimiento");
	}else{
	
		var denominacion = document.getElementById('denominacion').value;
		var siglas = document.getElementById('siglas').value;
		var idtipo_movimiento_bancario = document.getElementById('id_tipo_movimiento').value;
		if(document.getElementById('debita').checked == true){
			var afecta = document.getElementById('debita').value;
		}else{
			var afecta = document.getElementById('acredita').value;	
		}
		var tabla_deudora = document.getElementById('tabla_deudora').value;
		var idcuenta_deudora = document.getElementById('idcuenta_deudora').value;
		var tabla_acreedora = document.getElementById('tabla_acreedora').value;
		var idcuenta_acreedora = document.getElementById('idcuenta_acreedora').value;
		
		if (excluir_contabilidad == "no"){
			if (tabla_deudora == tabla_acreedora && idcuenta_deudora == idcuenta_acreedora){
					alert("La cuenta del DEBE tiene que ser distinta a la del HABER");
					document.getElementById('cuenta_deudora').focus();
				entro='no';
			}else{
				entro='si';
			}
		}else{
			entro='si';
		}

		if (entro=='si'){
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/movimientos_bancarios_ajax.php", true);
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					if(ajax.responseText.indexOf("exito") != -1){
						mostrarMensajes("exito", "Se modificaron los datos con exito");
						consultarTiposMovimientos();
						limpiarFormulario();
						document.getElementById('modificar').style.display = 'none';
						document.getElementById('ingresar').style.display = 'block';
					}
					document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("denominacion="+denominacion+"&siglas="+siglas+"&afecta="+afecta+"&idtipo_movimiento_bancario="+idtipo_movimiento_bancario+"&tabla_deudora="+tabla_deudora+"&idcuenta_deudora="+idcuenta_deudora+"&tabla_acreedora="+tabla_acreedora+"&idcuenta_acreedora="+idcuenta_acreedora+"&excluir_contabilidad="+excluir_contabilidad+"&ejecutar=modificarTiposMovimientos");
		}
	}
}
