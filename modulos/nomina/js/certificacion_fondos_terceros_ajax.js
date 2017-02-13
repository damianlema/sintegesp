// JavaScript Document

function seleccionarPeriodo(idtipo_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("celda_periodo").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=seleccionarPeriodo");		
}





function crearCertificacion(){
	document.getElementById("boton_siguiente").style.display='none';
	var idtipo_documento = document.getElementById("tipo_documento").value;
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var idperiodo = document.getElementById("idperiodo").value;
	var idconcepto = document.getElementById("id_concepto_constante").value;
	var idbeneficiario = document.getElementById("id_beneficiarios").value;
	var justificacion = document.getElementById("justificacion").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("tipo_documento").disabled = 'true';
				document.getElementById("idtipo_nomina").disabled = 'true';
				document.getElementById("idperiodo").disabled = 'true';
				document.getElementById("id_concepto_constante").disabled = 'true';
				document.getElementById("id_beneficiarios").disabled = 'true';
				document.getElementById("justificacion").disabled = 'true';
				document.getElementById("idorden_compra").value = ajax.responseText;
				consultarConceptos(ajax.responseText);
				consultarTotal(idorden_compra);
				
				document.getElementById("boton_procesar").style.display='block';
				document.getElementById("boton_anular").style.display='none';
				
				document.getElementById("divCargando").style.display = "none";
				setTimeout(consultarTotal(ajax.responseText),10000);
		} 
	}
	ajax.send("idcategoria_programatica="+idcategoria_programatica+"&idtipo_documento="+idtipo_documento+"&idtipo_nomina="+idtipo_nomina+"&idperiodo="+idperiodo+"&idconcepto="+idconcepto+"&idbeneficiario="+idbeneficiario+"&justificacion="+justificacion+"&ejecutar=crearCertificacion");	
}




function procesarCertificacion(){
	var idorden_compra = document.getElementById("idorden_compra").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				if(partes[0] == "exito"){
					mostrarMensajes("exito", "La certificacion de compromisos fue procesada con exito");
					document.getElementById("nro_orden").innerHTML = partes[1];
					document.getElementById("estado").innerHTML = "Procesado";
					//setTimeout(consultarTotal(idorden_compra),2000);
				}else if(partes[0] == "falloMateriales"){
					mostrarMensajes("error", "Disculpe la orden no pude ser procesada ya que existen conceptos que no tienen disponibilidad presupuestaria");
				}else{
					mostrarMensajes("error", "Disculpe la orden no pude ser procesada, por favor intente mas tarde");
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=procesarCertificacion");	
}





function consultarConceptos(idorden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaConceptos").innerHTML = ajax.responseText;
				consultarPartidas(idorden_compra);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=consultarConceptos");		
}



function consultarPartidas(idorden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaPartidas").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=consultarPartidas");		
}





function consultarCertificacion(idorden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				seleccionarPeriodo(partes[1]);
				document.getElementById('idorden_compra').value = idorden_compra;
				document.getElementById('tipo_documento').value = partes[0];
				document.getElementById('idtipo_nomina').value = partes[1];
				document.getElementById('nombre_categoria').value = partes[3];
				document.getElementById('id_categoria_programatica').value = "("+partes[12]+")"+partes[4];
				document.getElementById('concepto_constante').value = partes[5];
				document.getElementById('id_concepto_constante').value = partes[6];
				document.getElementById('nombre_proveedor').value = partes[7];
				document.getElementById('id_beneficiarios').value = partes[8];
				document.getElementById('justificacion').value = partes[9];
				
				
				document.getElementById('estado').innerHTML = partes[11];
				setTimeout("document.getElementById('idperiodo').value = "+partes[2],500);
				
				
				document.getElementById('tipo_documento').disabled = true;
				document.getElementById('idtipo_nomina').disabled = true;
				document.getElementById('justificacion').disabled = true;
				
				if(partes[11] == "elaboracion"){
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('boton_procesar').style.display = 'block';
					document.getElementById('nro_orden').innerHTML = "No Generado";
					
				}else if(partes[11] == "procesado"){
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'block';
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('nro_orden').innerHTML = partes[10];
				}else if(partes[11] == "anulado"){
					document.getElementById('boton_siguiente').style.display = 'none';
					document.getElementById('boton_anular').style.display = 'none';
					document.getElementById('boton_procesar').style.display = 'none';
					document.getElementById('nro_orden').innerHTML = partes[10];
				}
				setTimeout("document.getElementById('idperiodo').disabled = true",800);
				
				consultarConceptos(idorden_compra);
				consultarPartidas(idorden_compra);
				consultarTotal(idorden_compra);
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=consultarCertificacion");		
}


function consultarTotal(idorden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("total").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=consultarTotal");		
}


function anularCertificacion(){
	var idorden_compra = document.getElementById("idorden_compra").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/certificacion_fondos_terceros_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('boton_siguiente').style.display = 'none';
			document.getElementById('boton_anular').style.display = 'none';
			document.getElementById('boton_procesar').style.display = 'none';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorden_compra="+idorden_compra+"&ejecutar=anularCertificacion");		
}