// JavaScript Document


function cargarConstantes(idtipo_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
					
				
				document.getElementById("celda_constantes").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=cargarConstantes");	
}







function ingresarDatosBasicos(){
	var idtipo_nomina= document.getElementById("idtipo_nomina").value;
	var idperiodo = document.getElementById('idperiodo').value;
	
	if(idtipo_nomina == 0){
		mostrarMensajes("error", "Disculpe todos los campos superiores son campos requeridos");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?idperiodo="+idperiodo+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=ingresarDatosBasicos", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
						partes = ajax.responseText.split("|.|");
						document.getElementById("idgenerar_nomina").value = partes[0];
						document.getElementById("boton_siguiente").style.display = 'none';
						document.getElementById("boton_prenomina").style.display = 'block';
						consultarTrabajadores('Elaboracion', '');
			} 
		}
		ajax.send(null);	
	}
}




function consultarTrabajadores(estado, buscar){
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?buscar="+buscar+"&estado="+estado+"&idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarTrabajadores", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaTrabajadores").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send(null);
}






function generarNomina(estado){
	if(estado == "Procesado" && document.getElementById("error_conceptos").value == "si"){
		mostrarMensajes("error", "Disculpe hay un error en la certificacion con algun(os) concepto(s), por favor revise para poder procesar la nomina");
	}else{
		var idtipo_nomina = document.getElementById("idtipo_nomina").value;
		var idperiodo = document.getElementById("idperiodo").value;
		var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
		var idcertificacion = document.getElementById("idorden_compra_servicio").value;
		//var tipo_aumento = document.getElementById('tipo_aumento').value;
		
		var arreglo = new Array();
		//alert(document.getElementById('cantidad_constantes').value);
		for(i=1; i<=document.getElementById('cantidad_constantes').value; i++){
			
			//alert("AQUI");
			if(document.getElementById('valor-'+i).value != ""){
				//alert("AQUI");
				arreglo[i] = new Array();
				arreglo[i][0] = "id_"+document.getElementById('id-'+i).value;
				arreglo[i][1] = document.getElementById('valor-'+i).value;
				arreglo[i][2] = document.getElementById('tipo_cambio-'+i).value;
				arreglo[i][3] = document.getElementById('desde-'+i).value;
				arreglo[i][4] = document.getElementById('hasta-'+i).value;
			}
		}
		
		//alert(arreglo);
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?idperiodo="+idperiodo+"&arreglo="+arreglo+"&estado="+estado+"&idcertificacion="+idcertificacion+"&idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=generarNomina", true);	
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					//alert(ajax.responseText);
					//ajax.responseText = "x";
					/*if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "La nomina ah sido generada con exito");
						validarErroresConceptos(document.getElementById('idorden_compra_servicio').value);
						consultarConceptos(document.getElementById('idorden_compra_servicio').value);
						consultarPartidas(document.getElementById('idorden_compra_servicio').value);
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
						document.getElementById("boton_siguiente").style.display = 'none';
						document.getElementById("boton_prenomina").style.display = 'none';
						document.getElementById("boton_anular").style.display = 'block';
						document.getElementById("idtipo_nomina").disabled=true;
						consultarTrabajadores('Procesado','');
						setTimeout("verificarError()",100);
					}else if(ajax.responseText == "fallo"){
						mostrarMensajes("error", "Disculpe han existido errores al momento de generar la nomina, por favor verifique");
						validarErroresConceptos(document.getElementById("idorden_compra_servicio").value);
						consultarConceptos(document.getElementById("idorden_compra_servicio").value);
						consultarPartidas(document.getElementById("idorden_compra_servicio").value);
						document.getElementById("boton_siguiente").style.display = 'none';
						//document.getElementById("boton_procesar").style.display = 'block';
						//document.getElementById("boton_modificar").style.display = 'block';
						document.getElementById("boton_prenomina").style.display = 'block';
						document.getElementById("boton_anular").style.display = 'none';
						document.getElementById("idtipo_nomina").disabled=true;
						consultarTrabajadores('Procesado','');
						setTimeout("verificarError()",100);
					
					}else{*/
						//mostrarMensajes("error", "Disculpe han existido errores al momento de generar la nomina, por faor verifique");
						validarErroresConceptos(ajax.responseText);
						consultarConceptos(ajax.responseText);
						consultarPartidas(ajax.responseText);
						document.getElementById("idorden_compra_servicio").value = ajax.responseText;
						document.getElementById("boton_siguiente").style.display = 'none';
						//document.getElementById("boton_procesar").style.display = 'block';
						//document.getElementById("boton_modificar").style.display = 'block';
						document.getElementById("boton_prenomina").style.display = 'block';
						document.getElementById("boton_anular").style.display = 'none';
						document.getElementById("idtipo_nomina").disabled=true;
						consultarTrabajadores('Procesado','');
						setTimeout("verificarError()",200);
					//}
					
					document.getElementById('btImprimir').style.visibility='visible';
					
					//window.open("principal.php?accion=664&modulo=13&idorden_compra="+ajax.responseText, "", "height=800, width=900,scrollbars=yes, resizabled =no");
					//document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send(null);
	}
}


function verificarError(){
	if(document.getElementById("error_conceptos").value=="si"){
		cambiarColor("tdconceptos", '#FF0000');	
	}	
}



function anularNomina(){
	if(confirm("Seguro desea Anular esta Nomina?")){
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=anularNomina", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("boton_siguiente").style.display = 'none';
				//document.getElementById("boton_procesar").style.display = 'none';
				//document.getElementById("boton_modificar").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'none';
				document.getElementById("boton_prenomina").style.display = 'none';
				document.getElementById("idtipo_nomina").disabled=true;
				consultarTrabajadores('Procesado','');
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send(null);	
	}
}






function consultarGenerarNomina(idgenerar_nomina, idtipo_nomina, descripcion, idperiodo, fecha_elaboracion, fecha_procesado, estado, nombre_beneficiario, idbeneficiarios, idorden_compra_servicio){
		seleccionarPeriodo(idtipo_nomina);
		validarErroresConceptos(idorden_compra_servicio);
		consultarConceptos(idorden_compra_servicio);
		consultarPartidas(idorden_compra_servicio);
		document.getElementById('idorden_compra_servicio').value = idorden_compra_servicio;
		document.getElementById("idtipo_nomina").value=idtipo_nomina;
		document.getElementById("idgenerar_nomina").value=idgenerar_nomina;
		consultarTrabajadores(estado,'');
		document.getElementById("boton_siguiente").style.display= 'none';
		cargarConstantes(idtipo_nomina); 
		consultarNominas(idtipo_nomina);
		seleccionarPeriodo(idtipo_nomina);
		/*if(estado =="Elaboracion"){
				//document.getElementById("boton_modificar").style.display= 'block';
				//document.getElementById("boton_procesar").style.display= 'block';
				document.getElementById("boton_prenomina").style.display= 'block';
				document.getElementById("boton_anular").style.display= 'none';
		}else if(estado == "Procesado"){
				//document.getElementById("boton_modificar").style.display= 'none';
				//document.getElementById("boton_procesar").style.display= 'none';
				document.getElementById("boton_anular").style.display= 'block';
				document.getElementById("boton_prenomina").style.display= 'none';
		}else if(estado == "Anulado"){
				//document.getElementById("boton_modificar").style.display= 'none';
				//document.getElementById("boton_procesar").style.display= 'none';
				document.getElementById("boton_anular").style.display= 'none';	
				document.getElementById("boton_prenomina").style.display= 'none';
		}else if(estado == "Pre Nomina"){
				//document.getElementById("boton_modificar").style.display= 'block';
				//document.getElementById("boton_procesar").style.display= 'block';
				document.getElementById("boton_anular").style.display= 'none';	
				document.getElementById("boton_prenomina").style.display= 'block';
		}*/
		
		//document.getElementById("idtipo_nomina").disabled=true;
		
		document.getElementById('btImprimir').style.visibility = "visible";
		
}



function modificarNomina(){
	var idgenerar_nomina= document.getElementById("idgenerar_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?idgenerar_nomina="+idgenerar_nomina+"&ejecutar=modificarNomina", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){		
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send(null);
}








function consultarConceptos(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?id_orden_compra="+id_orden_compra+"&ejecutar=consultarConceptos", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("listaConceptos").innerHTML=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
			
		} 
	}
	ajax.send(null);
}




function validarErroresConceptos(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?id_orden_compra="+id_orden_compra+"&ejecutar=validarErroresConceptos", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("error_conceptos").value=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send(null);
}




function consultarPartidas(id_orden_compra){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php?id_orden_compra="+id_orden_compra+"&ejecutar=consultarPartidas", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("listaPartidas").innerHTML=ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send(null);
}





function cambiarColor(id, color){
	if(id == "tdtrabajador" && document.getElementById("pestana_seleccionada").value == 1){
		color = "#EAEAEA";	
	}else if(id == "tdconceptos" && document.getElementById("pestana_seleccionada").value == 2){
		color = "#EAEAEA";	
	}else if(id == "tdpartidas" && document.getElementById("pestana_seleccionada").value == 3){
		color = "#EAEAEA";	
	}
	document.getElementById(id).bgColor = color;
}



function seleccionarPestana(id){
	document.getElementById("tdtrabajador").bgColor = "#FFFFCC";
	document.getElementById("tdconceptos").bgColor = "#FFFFCC";
	document.getElementById("tdpartidas").bgColor = "#FFFFCC";

	document.getElementById("listaTrabajadores").style.display = 'none';
	document.getElementById("listaConceptos").style.display = 'none';
	document.getElementById("listaPartidas").style.display = 'none';
	
	if(id == "tdtrabajador"){
		document.getElementById("pestana_seleccionada").value = 1;
		cambiarColor("tdtrabajador", "#EAEAEA");
		document.getElementById("listaTrabajadores").style.display = 'block';
	}else if(id == "tdconceptos"){
		document.getElementById("pestana_seleccionada").value = 2;	
		cambiarColor("tdconceptos", "#EAEAEA");
		document.getElementById("listaConceptos").style.display = 'block';
	}else if(id == "tdpartidas"){
		document.getElementById("pestana_seleccionada").value = 3;
		cambiarColor("tdpartidas", "#EAEAEA");
		document.getElementById("listaPartidas").style.display = 'block';
	}
}


function mostrarReporte(){
	var nomina = document.getElementById("idtipo_nomina").value;
	var idgenerar_nomina = document.getElementById("idgenerar_nomina").value;
	var idcomparar = document.getElementById('idcomparar').value;
	
	if (document.getElementById("nomina").checked) {
		document.getElementById("divImprimir").style.display="block"; 
		pdf.location.href="lib/reportes/nomina/reportes_2.php?nombre=relacion_nomina_trabajadores_simular&nomina="+nomina+"&idgenerar_nomina="+idgenerar_nomina;
		document.getElementById("pdf").style.display="block";
	}
	else if (document.getElementById("concepto").checked) {
		document.getElementById("divImprimir").style.display="block";
		pdf.location.href="lib/reportes/nomina/reportes_2.php?nombre=nomina_resumen_conceptos_simular&nomina="+nomina+"&idgenerar_nomina="+idgenerar_nomina;
		document.getElementById("pdf").style.display="block";
	}
	else if (document.getElementById("comparacion").checked) {
		document.getElementById('divImprimir').style.display='block';
		pdf.location.href="lib/reportes/nomina/reportes_2.php?nombre=nomina_comparacion_simular&nomina="+nomina+"&idgenerar_nomina="+idgenerar_nomina+"&idcomparar="+idcomparar;
		document.getElementById('pdf').style.display='block'; 
	}
	
}



function consultarNominas(idtipo_nomina){
	var idgenerar_nomina = document.getElementById('idgenerar_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('listaComparar').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarNominas");	
}



function seleccionarPeriodo(idtipo_nomina){
	var idgenerar_nomina = document.getElementById('idgenerar_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/simular_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("celda_periodos").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idgenerar_nomina="+idgenerar_nomina+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=seleccionarPeriodo");		
}