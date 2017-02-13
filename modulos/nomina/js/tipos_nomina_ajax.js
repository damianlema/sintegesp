// JavaScript Document


function registrarDatosGenerales(){
	var titulo_nomina = document.getElementById('titulo_nomina').value;
	//var anio = document.getElementById('anio').value;
	//var fecha_inicio = document.getElementById('fecha_inicio').value;
	var activa = document.getElementById('activa').value;
	var idtipo_documento = document.getElementById('idtipo_documento').value;
	var motivo_cuenta = document.getElementById('motivo_cuenta').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById('idtipo_nomina').value = ajax.responseText;
				document.getElementById('tabsF').style.display = 'block';
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("motivo_cuenta="+motivo_cuenta+"&idtipo_documento="+idtipo_documento+"&activa="+activa+"&titulo_nomina="+titulo_nomina+"&ejecutar=registrarDatosGenerales");	
}






function guardarTipoFraccion(valor){
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
		} 
	}
	ajax.send("valor="+valor+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=guardarTipoFraccion");	
}



function guardarNumeroFraccion(valor){
	
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		document.getElementById('celda_datos_fraccion').innerHTML = "Cargando...";	
		}
		if (ajax.readyState==4){
			consultarFracciones(idtipo_nomina, 'no');
		} 
	}
	ajax.send("valor="+valor+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=guardarNumeroFraccion");


}




function consultarFracciones(idtipo_nomina, consultar){
	
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('celda_datos_fraccion').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("consultar="+consultar+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarFracciones");	
}





function guardarValorFraccion(valor, idrango_fraccion_nomina, div){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				document.getElementById(div).innerHTML = "<img src='imagenes/validar.png'>";
			}else{
				document.getElementById(div).innerHTML = "<img src='imagenes/cancel.png'>";	
			}
			
		} 
	}
	ajax.send("idrango_fraccion_nomina="+idrango_fraccion_nomina+"&valor="+valor+"&ejecutar=guardarValorFraccion");
}





function guardarJornada(jornada, dia){
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;	
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&jornada="+jornada+"&dia="+dia+"&ejecutar=guardarJornada");
}






function guardarPeriodo(){
	if(document.getElementById("numero_periodo_0").checked == true){
		var periodo = 52;
	}else if(document.getElementById("numero_periodo_1").checked == true){
		var periodo = 24;
	}else if(document.getElementById("numero_periodo_2").checked == true){
		var periodo = 12;	
	}else if(document.getElementById("numero_periodo_3").checked == true){
		var periodo = document.getElementById("otro_periodo").value;
	}
	
	
	var dia_semana_comienza = document.getElementById("dia_semana_comienza").value;
	var anio = document.getElementById("anio").value;
	var fecha_inicio = document.getElementById("fecha_inicio").value;
	var descripcion_periodo = document.getElementById('descripcion_periodo').value;
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;	
	var idperiodo_nomina = document.getElementById('idperiodo_nomina').value;	
	if(document.getElementById("periodo_activo").checked == true){
		periodo_activo = "si";	
		
	}else{
		periodo_activo = "no";	
	}
	
	if(document.getElementById('cierre_mes_0').checked == true){
		cierre_mes = 30;
	}else if(document.getElementById('cierre_mes_1').checked == true){
		cierre_mes = 31;
	}
	
	
	var ajax=nuevoAjax();
	
	
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("celda_resultados_periodo").innerHTML = "Cargando...";
			}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			if(ajax.responseText == 'fallo'){
				document.getElementById('otro_periodo').value = '';	
			}
			if(partes[0] == "exito"){
				document.getElementById('pestana_seleccionada').value = "id"+partes[1]+"";
				calcularPeriodo(periodo, partes[1]);
				document.getElementById("idperiodo_nomina").value = partes[1];
				consultarPestanas();
				seleccionarPestana(partes[1]);
				
			}else if(partes[0] == "existe_generar"){
				mostrarMensajes("error", "Disculpe no se puede actualizar el rango de periodos debido a que una nomina ya fue generada usando algun periodo de este rango");
				document.getElementById("idperiodo_nomina").value = partes[1];
			}
		} 
	}
	ajax.send("cierre_mes="+cierre_mes+"&periodo_activo="+periodo_activo+"&dia_semana_comienza="+dia_semana_comienza+"&idperiodo_nomina="+idperiodo_nomina+"&anio="+anio+"&fecha_inicio="+fecha_inicio+"&idtipo_nomina="+idtipo_nomina+"&periodo="+periodo+"&descripcion_periodo="+descripcion_periodo+"&ejecutar=guardarPeriodo");
	
}






function consultarAnio(anio){
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText != "0"){
				mostrarMensajes("error", "Disculpe este anio ya fue Registrado");
				document.getElementById("anio").value = 0;
			}else{
				document.getElementById("descripcion_periodo").value = "";
				document.getElementById("fecha_inicio").value = "";
				//document.getElementById("anio").value = 0;
				document.getElementById("numero_periodo_0").checked = false;	
				document.getElementById("otro_periodo").style.display="none";
				document.getElementById("dia_semana_comienza").style.display = "none";
				document.getElementById("dia_semana_comienza").value = 0;
				document.getElementById("numero_periodo_1").checked = false;	
				document.getElementById("numero_periodo_2").checked = false;
				document.getElementById("numero_periodo_3").checked = false;
				document.getElementById("periodo_activo").checked = false;
				document.getElementById("idperiodo_nomina").value = "";	
				}
		} 
	}
	ajax.send("anio="+anio+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarAnio");
}



function seleccionarPestana(idperiodos_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById("descripcion_periodo").value = partes[0];
			document.getElementById("fecha_inicio").value = partes[1];
			document.getElementById("anio").value = partes[2];
			if(partes[3] == "52"){
				document.getElementById("numero_periodo_0").checked = true;	
				document.getElementById("otro_periodo").style.display="none";
				document.getElementById("dia_semana_comienza").style.display = "block";
				document.getElementById("dia_semana_comienza").value = partes[4];
				document.getElementById("otro_periodo").value="";
			}else if(partes[3] == "24"){
				document.getElementById("numero_periodo_1").checked = true;	
				document.getElementById("otro_periodo").style.display="none";
				document.getElementById("otro_periodo").value="";
				document.getElementById("dia_semana_comienza").style.display = "none";
			}else if(partes[3] == "12"){
				document.getElementById("numero_periodo_2").checked = true;
				document.getElementById("otro_periodo").style.display="none";
				document.getElementById("otro_periodo").value="";
				document.getElementById("dia_semana_comienza").style.display = "none";
			}else{
				document.getElementById("numero_periodo_3").checked = true;
				document.getElementById("otro_periodo").style.display="block";
				document.getElementById("otro_periodo").value=partes[3];
				document.getElementById("dia_semana_comienza").style.display = "none";
			}
			if(partes[5] == "si"){
				document.getElementById("periodo_activo").checked=true;
			}else{
				document.getElementById("periodo_activo").checked=false;
			}
			if(partes[6] == "30"){
				document.getElementById("cierre_mes_0").checked=true;
			}else{
				document.getElementById("cierre_mes_1").checked=true;
			}
			document.getElementById("idperiodo_nomina").value = idperiodos_nomina;
			consultarPestanas();	
			mostrarListaPeriodos();
		} 
	}
	ajax.send("idperiodos_nomina="+idperiodos_nomina+"&ejecutar=seleccionarPestana");
}




function consultarPestanas(){
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;	
	var idpestana = document.getElementById('pestana_seleccionada').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById("celda_lista_pestanas").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idpestana="+idpestana+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarPestanas");	
}





function calcularPeriodo(periodo, idperiodo){
	var fecha_inicio = document.getElementById('fecha_inicio').value;
	var anio = document.getElementById('anio').value;	
	
	if(document.getElementById('cierre_mes_0').checked == true){
		cierre_mes = 30;
	}else if(document.getElementById('cierre_mes_1').checked == true){
		cierre_mes = 31;
	}

	partes_fecha= fecha_inicio.split("-");
	if((partes_fecha[2] == "01" && periodo == 24) || (partes_fecha[2] == "01" && periodo == 12) || (periodo != 24 && periodo != 12)){
		if(anio == partes_fecha[0]){
	
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;	
	
	var dia_semana_comienza = document.getElementById("dia_semana_comienza").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			mostrarListaPeriodos();
		} 
	}
	ajax.send("cierre_mes="+cierre_mes+"&dia_semana_comienza="+dia_semana_comienza+"&anio="+anio+"&idperiodo="+idperiodo+"&idtipo_nomina="+idtipo_nomina+"&periodo="+periodo+"&fecha_inicio="+fecha_inicio+"&ejecutar=calcularPeriodo");
		}else{
			mostrarMensajes("error", "Disculpe el año de la fecha de inicio debe ser igual al año seleccionado");
		}
	
	}else{
		mostrarMensajes("error", "Disculpe si el periodo es quincenal o mensual la fecha de inicio debe ser el 1ro de un mes");
	}
}



function mostrarListaPeriodos(){
	var idperiodo_nomina = document.getElementById("idperiodo_nomina").value;
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById("celda_resultados_periodo").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&idperiodo_nomina="+idperiodo_nomina+"&ejecutar=mostrarListaPeriodos");	
}






function consultarTipoNomina(idtipo_nomina){
	document.getElementById('idtipo_nomina').value = idtipo_nomina;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_actualizar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	document.getElementById('tabsF').style.display = 'block';
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById('titulo_nomina').value = partes[0];
			document.getElementById('anio').value = partes[1];
			document.getElementById('fecha_inicio').value = partes[2];
			if(partes[3] == "s"){
				document.getElementById('activa').checked = true;	
			}
			
			if(partes[6] == 'no aplica'){
				document.getElementById('tipo_fraccion_0').checked = true;
			}else if(partes[6] == 'porcentual'){
				document.getElementById('tipo_fraccion_1').checked = true;	
			}else if(partes[6] == 'valor'){
				document.getElementById('tipo_fraccion_2').checked = true;	
			}
			
			document.getElementById("idtipo_documento").value = partes[8];
			document.getElementById("motivo_cuenta").value = partes[9];
			document.getElementById('numero_fracciones').value= partes[7];
			
			consultarFracciones(idtipo_nomina, 'si');
			
			consultarJornadas(idtipo_nomina);
			consultarPestanas();
			
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarTipoNomina");
}





function consultarJornadas(idtipo_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
				// LUNES
				if(partes[0] == 'c'){
					document.getElementById('jornada_lunes_0').checked = true;	
				}else if(partes[0] == 'm'){
					document.getElementById('jornada_lunes_1').checked = true;	
				}else if(partes[0] == 'n'){
					document.getElementById('jornada_lunes_2').checked = true;	
				}
				// MARTES
				
				if(partes[1] == 'c'){
					document.getElementById('jornada_martes_0').checked = true;	
				}else if(partes[1] == 'm'){
					document.getElementById('jornada_martes_1').checked = true;	
				}else if(partes[1] == 'n'){
					document.getElementById('jornada_martes_2').checked = true;	
				}
				// MIERCOLES
				
				if(partes[2] == 'c'){
					document.getElementById('jornada_miercoles_0').checked = true;	
				}else if(partes[2] == 'm'){
					document.getElementById('jornada_miercoles_1').checked = true;	
				}else if(partes[2] == 'n'){
					document.getElementById('jornada_miercoles_2').checked = true;	
				}
				
				// JUEVES
				
				if(partes[3] == 'c'){
					document.getElementById('jornada_jueves_0').checked = true;	
				}else if(partes[3] == 'm'){
					document.getElementById('jornada_jueves_1').checked = true;	
				}else if(partes[3] == 'n'){
					document.getElementById('jornada_jueves_2').checked = true;	
				}
				
				// VIERNES
				
				if(partes[4] == 'c'){
					document.getElementById('jornada_viernes_0').checked = true;	
				}else if(partes[4] == 'm'){
					document.getElementById('jornada_viernes_1').checked = true;	
				}else if(partes[4] == 'n'){
					document.getElementById('jornada_viernes_2').checked = true;	
				}
				
				// SABADO
				
				if(partes[5] == 'c'){
					document.getElementById('jornada_sabado_0').checked = true;	
				}else if(partes[5] == 'm'){
					document.getElementById('jornada_sabado_1').checked = true;	
				}else if(partes[5] == 'n'){
					document.getElementById('jornada_sabado_2').checked = true;	
				}
				
				// DOMINGO
				
				if(partes[6] == 'c'){
					document.getElementById('jornada_domingo_0').checked = true;	
				}else if(partes[6] == 'm'){
					document.getElementById('jornada_domingo_1').checked = true;	
				}else if(partes[6] == 'n'){
					document.getElementById('jornada_domingo_2').checked = true;	
				}
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarJornadas");		
}




function actualizarDatosGenerales(){
	var titulo_nomina = document.getElementById('titulo_nomina').value;
	var anio = document.getElementById('anio').value;
	var fecha_inicio = document.getElementById('fecha_inicio').value;
	var activa = document.getElementById('activa').value;
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;
	var idtipo_documento = document.getElementById('idtipo_documento').value;
	var motivo_cuenta = document.getElementById('motivo_cuenta').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("divCargando").style.display = "none";

				mostrarMensajes("exito", "Datos Actualizados con Exito");
		} 
	}
	ajax.send("motivo_cuenta="+motivo_cuenta+"&idtipo_documento="+idtipo_documento+"&idtipo_nomina="+idtipo_nomina+"&activa="+activa+"&fecha_inicio="+fecha_inicio+"&anio="+anio+"&titulo_nomina="+titulo_nomina+"&ejecutar=actualizarDatosGenerales");
}






function eliminarTipoNomina(){
	var idtipo_nomina = document.getElementById('idtipo_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			if(ajax.responseText == "trabajadores_asociados"){
				mostrarMensajes("error", "Disculpe no se puede eliminar el tipo de nomina ya que existen trabajadores asociados a ella, por favor verifique e intente de nuevo");
			}else{
				mostrarMensajes("exito", "Se elimino el tipo de nomina con exito");	
				window.location.href='principal.php?accion=875&modulo=13';
			}
			
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=eliminarTipoNomina");
}



function cambiarColor(id, color){
	if(document.getElementById("pestana_seleccionada").value != id){
		document.getElementById(id).bgColor = color;	
	}
	
}

function cambiarColor(id, color){
	if(document.getElementById("pestana_seleccionada").value != id){
		document.getElementById(id).bgColor = color;	
	}
	
}




function registrarConcepto(idperiodo, idconcepto, valor){
	if(valor > 100){
		mostrarMensajes("error", "Disculpe el valor para el concepto no puede ser mayor a 100%");
	}else{
		var idtipo_nomina = document.getElementById('idtipo_nomina').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){} 
		}
		ajax.send("idtipo_nomina="+idtipo_nomina+"&idperiodo="+idperiodo+"&idconcepto="+idconcepto+"&valor="+valor+"&ejecutar=registrarConcepto");
	}
}


function actualizarSemanas(nombre, valor, idrango_periodo_nomina){
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/tipos_nomina_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){} 
		}
		ajax.send("idrango_periodo_nomina="+idrango_periodo_nomina+"&nombre="+nombre+"&valor="+valor+"&ejecutar=actualizarSemanas");
}


