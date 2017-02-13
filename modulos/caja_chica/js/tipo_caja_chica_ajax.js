// JavaScript Document


function ingresarTipoCajaChica(){
	var denominacion = document.getElementById("denominacion").value;
	var ut_aprobadas = document.getElementById("ut_aprobadas").value;
	var resolucion_nro = document.getElementById("resolucion_nro").value;
	var fecha_resolucion = document.getElementById("fecha_resolucion").value;
	var gaceta_nro = document.getElementById("gaceta_nro").value;
	var fecha_gaceta = document.getElementById("fecha_gaceta").value;
	var minimo_reponer = document.getElementById("minimo_reponer").value;
	var maximo_reponer = document.getElementById("maximo_reponer").value;
	var ut_maximas_factura = document.getElementById("ut_maximas_factura").value;
	
	if(parseInt(maximo_reponer) > parseInt(ut_aprobadas)){
		mostrarMensajes("error", "Disculpe maximo a reponer no puede ser mayor a la cantidad de unidades tributarias aprobadas");
	}else if(parseInt(ut_maximas_factura) > parseInt(maximo_reponer)){
		mostrarMensajes("error", "Disculpe el maximo de unidades tributarias por factura no puede ser mayor al maximo de unidades a reponer");
	}else{
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/tipo_caja_chica_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				consultarTiposCajaChica();
				limpiarCampos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("denominacion="+denominacion+"&ut_aprobadas="+ut_aprobadas+"&resolucion_nro="+resolucion_nro+"&fecha_resolucion="+fecha_resolucion+"&gaceta_nro="+gaceta_nro+"&fecha_gaceta="+fecha_gaceta+"&minimo_reponer="+minimo_reponer+"&maximo_reponer="+maximo_reponer+"&ut_maximas_factura="+ut_maximas_factura+"&ejecutar=ingresarTipoCajaChica");		
	}
}





function consultarTiposCajaChica(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/tipo_caja_chica_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("lista_tipo_caja_chica").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=consultarTiposCajaChica");
}





function modificarTipoCajaChica(){
	var idtipo_caja_chica= document.getElementById("idtipo_caja_chica").value;
	var denominacion = document.getElementById("denominacion").value;
	var ut_aprobadas = document.getElementById("ut_aprobadas").value;
	var resolucion_nro = document.getElementById("resolucion_nro").value;
	var fecha_resolucion = document.getElementById("fecha_resolucion").value;
	var gaceta_nro = document.getElementById("gaceta_nro").value;
	var fecha_gaceta = document.getElementById("fecha_gaceta").value;
	var minimo_reponer = document.getElementById("minimo_reponer").value;
	var maximo_reponer = document.getElementById("maximo_reponer").value;
	var ut_maximas_factura = document.getElementById("ut_maximas_factura").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/tipo_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				consultarTiposCajaChica();
				limpiarCampos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_caja_chica="+idtipo_caja_chica+"&denominacion="+denominacion+"&ut_aprobadas="+ut_aprobadas+"&resolucion_nro="+resolucion_nro+"&fecha_resolucion="+fecha_resolucion+"&gaceta_nro="+gaceta_nro+"&fecha_gaceta="+fecha_gaceta+"&minimo_reponer="+minimo_reponer+"&maximo_reponer="+maximo_reponer+"&ut_maximas_factura="+ut_maximas_factura+"&ejecutar=modificarTipoCajaChica");		
}






function eliminarTipoCajaChica(idtipo_caja_chica){
	if(confirm("¿Seguro desea Eliminar este tipo de caja chica?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/caja_chica/lib/tipo_caja_chica_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					if(ajax.resposneText == "utilizado"){
						mostrarMensajes("error", "Disculpe el tipo de caja chica que intenta eliminar esta siendo utilizado por algunas reposiciones de caja, por lo tanto no puede ser eliminado, solo modificado");
					}
					consultarTiposCajaChica();
					limpiarCampos();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idtipo_caja_chica="+idtipo_caja_chica+"&ejecutar=eliminarTipoCajaChica");		
	}
}







function seleccionarDatos(idtipo_caja_chica, denominacion, unidades_tributarias_aprobadas, resolucion_nro, fecha_resolucion, gaceta_nro, fecha_gaceta, 
minimo_reponer, maximo_reponer, ut_maxima_factura){
	document.getElementById("idtipo_caja_chica").value = idtipo_caja_chica;
	document.getElementById("denominacion").value = denominacion;
	document.getElementById("ut_aprobadas").value = unidades_tributarias_aprobadas;
	document.getElementById("resolucion_nro").value = resolucion_nro;
	document.getElementById("fecha_resolucion").value = fecha_resolucion;
	document.getElementById("gaceta_nro").value = gaceta_nro;
	document.getElementById("fecha_gaceta").value = fecha_gaceta;
	document.getElementById("minimo_reponer").value = minimo_reponer;
	document.getElementById("maximo_reponer").value = maximo_reponer;
	document.getElementById("ut_maximas_factura").value = ut_maxima_factura;
	
	
	document.getElementById("boton_ingresar").style.display='none';
	document.getElementById("boton_modificar").style.display='block';
}








function limpiarCampos(){
	document.getElementById("idtipo_caja_chica").value = "";
	document.getElementById("denominacion").value = "";
	document.getElementById("ut_aprobadas").value = "";
	document.getElementById("resolucion_nro").value = "";
	document.getElementById("fecha_resolucion").value = "";
	document.getElementById("gaceta_nro").value = "";
	document.getElementById("fecha_gaceta").value = "";
	document.getElementById("minimo_reponer").value = "";
	document.getElementById("maximo_reponer").value = "";
	document.getElementById("ut_maximas_factura").value = "";
	document.getElementById("boton_ingresar").style.display='block';
	document.getElementById("boton_modificar").style.display='none';
	
}