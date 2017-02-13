// JavaScript Document


function ingresarDatosBasicos(){
	var tipo_caja_chica= document.getElementById("tipo_caja_chica").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	var monto = document.getElementById("monto").value;
	var idbeneficiarios = document.getElementById("id_beneficiarios").value;
	var justificacion = document.getElementById("justificacion").value;
	var responsable = document.getElementById("responsable").value;
	var ci_responsable = document.getElementById("ci_responsable").value;
	
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	var idtipos_documentos = document.getElementById("tipos_documentos").value;
	
	
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/apertura_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";	
		}
		if (ajax.readyState==4){
			
			if(ajax.responseText == "categoria_existe"){
				mostrarMensajes("error", "Disculpe ya existe una apertura de caja con esta categoria programatgica y para este tipo de caja chica");
			}else{
				document.getElementById("idorden_compra_servicio").value = ajax.responseText;
				document.getElementById("boton_procesar").style.display = 'block';
				document.getElementById("boton_modificar").style.display = 'block';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_siguiente").style.display = 'none';	
			}
			
			
			
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipos_documentos="+idtipos_documentos+"&anio="+anio+"&tipo_presupuesto="+tipo_presupuesto+"&uente_financiamiento="+fuente_financiamiento+"&tipo_caja_chica="+tipo_caja_chica+"&monto="+monto+"&idbeneficiarios="+idbeneficiarios+"&justificacion="+justificacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&idcategoria_programatica="+idcategoria_programatica+"&ejecutar=ingresarDatosBasicos");	
}









function anularApertura(){
	if(confirm("Seguro que desea Anular esta apertura?")){
		var idorden_compra_servicio= document.getElementById("idorden_compra_servicio").value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/caja_chica/lib/apertura_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";	
			}
			if (ajax.readyState==4){
				document.getElementById("boton_procesar").style.display = 'none';
				document.getElementById("boton_modificar").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_siguiente").style.display = 'none';
				document.getElementById("celda_estado").innerHTML = "Anulado";
				bloquearCampos();	
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idorden_compra_servicio="+idorden_compra_servicio+"&ejecutar=anularApertura");	
	}
}









function modificarDatosBasicos(){
	var idorden_compra_servicio= document.getElementById("idorden_compra_servicio").value;
	var tipo_caja_chica= document.getElementById("tipo_caja_chica").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	var monto = document.getElementById("monto").value;
	var idbeneficiarios = document.getElementById("id_beneficiarios").value;
	var justificacion = document.getElementById("justificacion").value;
	var responsable = document.getElementById("responsable").value;
	var ci_responsable = document.getElementById("ci_responsable").value;
	
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/apertura_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";	
		}
		if (ajax.readyState==4){
			
			if(ajax.responseText == "categoria_existe"){
				mostrarMensajes("error", "Disculpe ya existe una apertura de caja con esta categoria programatgica y para este tipo de caja chica");
			}else{
				document.getElementById("boton_procesar").style.display = 'block';
				document.getElementById("boton_modificar").style.display = 'block';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_siguiente").style.display = 'none';	
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&idorden_compra_servicio="+idorden_compra_servicio+"&tipo_caja_chica="+tipo_caja_chica+"&monto="+monto+"&idbeneficiarios="+idbeneficiarios+"&justificacion="+justificacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&idcategoria_programatica="+idcategoria_programatica+"&ejecutar=modificarDatosBasicos");	
}























function procesarApertura(){
	if(confirm("Seguro desea procesar esta apertura de caja?")){
		var id_orden_compra= document.getElementById("idorden_compra_servicio").value;
		var tipo_caja_chica= document.getElementById("tipo_caja_chica").value;
		var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
		
		var idtipos_documentos= document.getElementById("tipos_documentos").value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/caja_chica/lib/apertura_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";	
			}
			if (ajax.readyState==4){
				if(ajax.responseText == "categoria_existe"){
					mostrarMensajes("error", "Disculpe ya existe una apertura de caja con esta categoria programatgica y para este tipo de caja chica");
				}else{
					//document.getElementById("idorden_compra_servicio").value = ajax.responseText;
					document.getElementById("boton_procesar").style.display = 'none';
					document.getElementById("boton_modificar").style.display = 'none';
					document.getElementById("boton_anular").style.display = 'block';
					document.getElementById("boton_siguiente").style.display = 'none';
					document.getElementById("celda_numero").innerHTML = ajax.responseText;
					var fecha_actual = new Date();
					var dia = fecha_actual.getDate()  
					var mes = fecha_actual.getMonth()+1;  
					var anio = fecha_actual.getFullYear();
					if(mes<10){
						mes = 0+""+mes;
					}
					if(dia<10){
						dia = 0+""+dia;
					}
					document.getElementById("celda_fecha_apertura").innerHTML = anio+"-"+mes+"-"+dia;
					document.getElementById("celda_estado").innerHTML = "Procesado";
					document.getElementById("divCargando").style.display = "none";
					bloquearCampos();	
				}
			} 
		}
		ajax.send("idtipos_documentos="+idtipos_documentos+"&tipo_caja_chica="+tipo_caja_chica+"&idcategoria_programatica="+idcategoria_programatica+"&id_orden_compra="+id_orden_compra+"&ejecutar=procesarApertura");	
	}
}




function bloquearCampos(){
	document.getElementById("tipo_caja_chica").disabled = true;
	document.getElementById("nombre_categoria").disabled = true;
	document.getElementById("monto_mostrado").disabled = true;
	document.getElementById("nombre_proveedor").disabled = true;
	document.getElementById("justificacion").disabled = true;
	document.getElementById("responsable").disabled=true;
	document.getElementById("ci_responsable").disabled = true;	
	document.getElementById("buscarCategoriaProgramatica").style.display = 'none';
	document.getElementById("buscarProveedor2").style.display = 'none';
}




function desbloquearCampos(){
	document.getElementById("tipo_caja_chica").disabled = false;
	document.getElementById("nombre_categoria").disabled = false;
	document.getElementById("monto_mostrado").disabled = false;
	document.getElementById("nombre_proveedor").disabled = false;
	document.getElementById("justificacion").disabled = false;
	document.getElementById("responsable").disabled=false;
	document.getElementById("ci_responsable").disabled = false;	
	document.getElementById("buscarCategoriaProgramatica").style.display = 'block';
	document.getElementById("buscarProveedor2").style.display = 'block';
}






function calcularMonto(idtipo_caja_chica){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/apertura_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById("monto").value = partes[1];
			document.getElementById("monto_mostrado").value = partes[0];
		} 
	}
	ajax.send("idtipo_caja_chica="+idtipo_caja_chica+"&ejecutar=calcularMonto");		
}






function consultarApertura(idorden_compra_servicio){
	document.getElementById("idorden_compra_servicio").value = idorden_compra_servicio;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/apertura_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById("tipo_caja_chica").value = partes[0];
			document.getElementById("nombre_categoria").value = partes[1];
			document.getElementById("id_categoria_programatica").value = partes[2];
			document.getElementById("monto_mostrado").value = partes[3];
			document.getElementById("monto").value = partes[4];
			document.getElementById("nombre_proveedor").value = partes[5];
			document.getElementById("id_beneficiarios").value = partes[6];
			document.getElementById("justificacion").value = partes[7];
			document.getElementById("responsable").value = partes[8];
			document.getElementById("ci_responsable").value = partes[9];
			document.getElementById("celda_numero").innerHTML = partes[10];
			document.getElementById("celda_fecha_apertura").innerHTML= partes[11];
			document.getElementById("celda_fecha_elaboracion").innerHTML = partes[12];
			document.getElementById("celda_estado").innerHTML = partes[13];
			
			
			document.getElementById("fuente_financiamiento").value = partes[14];
			document.getElementById("tipo_presupuesto").value = partes[15];
			document.getElementById("anio").value = partes[16];
			document.getElementById("tipos_documentos").value = partes[17];
			
			
			if(partes[13] == "elaboracion"){
				document.getElementById("boton_procesar").style.display = 'block';
				document.getElementById("boton_modificar").style.display = 'block';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_siguiente").style.display = 'none';	
				desbloquearCampos();
			}else if(partes[13] == "procesado"){
				document.getElementById("boton_procesar").style.display = 'none';
				document.getElementById("boton_modificar").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'block';
				document.getElementById("boton_siguiente").style.display = 'none';	
				bloquearCampos();	
			}else if(partes[13] == "anulado"){
				document.getElementById("boton_procesar").style.display = 'none';
				document.getElementById("boton_modificar").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_siguiente").style.display = 'none';
				bloquearCampos();	
			}
			
		} 
	}
	ajax.send("idorden_compra_servicio="+idorden_compra_servicio+"&ejecutar=consultarApertura");	
}