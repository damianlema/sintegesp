// JavaScript Document

function consultarDatosGenerales(id_retencion, tipo_consulta){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("tablaGeneral").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_retencion="+id_retencion+"&tipo_consulta="+tipo_consulta+"&ejecutar=consultarDatosGenerales");
}





function eliminarRetencion(idtipo_retencion, modulo){
				var ajax=nuevoAjax();
					ajax.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);	
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajax.onreadystatechange=function() { 
						if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
							}
						
						if (ajax.readyState==4){
							if (ajax.responseText=='existen'){
								alert("No se puede eliminar el Tipo de Retencion porque ya fue utilizada");
								document.getElementById("divCargando").style.display = "none";
							}else{
								document.getElementById("listaRetenciones").innerHTML=ajax.responseText;
								var ajax2=nuevoAjax();
								ajax2.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);
								ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
								ajax2.onreadystatechange=function() { 
									if (ajax.readyState==4){
										document.getElementById("tablaGeneral").innerHTML=ajax2.responseText;
									}
								}
								ajax2.send("ejecutar=nuevoRegistro");
								document.getElementById("divCargando").style.display = "none";
							}
						} 
					}
					ajax.send("idtipo_retencion="+idtipo_retencion+"&modulo="+modulo+"&ejecutar=eliminarDocumentos");
}















function modificarRetencion(codigo, descripcion, monto_fijo, porcentaje, divisor, base_calculo, unidad_tributaria, factor_calculo, contador, documentoAsociado, articulo, numeral, literal, nombre_comprobante, idtipo_retencion){
		if(codigo == ""){
			mostrarMensajes("error", "Disculpe debe ingresar el Codigo");
		}else if(descripcion == ""){
			mostrarMensajes("error", "Disculpa debe ingresar la Descripcion");
		}else if(document.getElementById('option_monto_fijo').checked == false && document.getElementById('option_porcentaje').checked == false){
			mostrarMensajes("error", "Disculpe debe seleccionar la opcion de monto");
		}else if(document.getElementById('option_monto_fijo').checked == true && monto_fijo == ""){
			mostrarMensajes("error", "Discule debe ingresar el monto fijo");
		}else if(document.getElementById('option_porcentaje').checked == true && (porcentaje == "" || divisor == "")){
			mostrarMensajes("error", "Discule debe ingresar el porcentaje y el divisor");
		}else if(document.getElementById('origen1').checked == true && contador == ""){
			mostrarMensajes("error", "Disculpe debe indicar el numero inicial del contador");
		}else if(document.getElementById('origen2').checked == true && documentoAsociado == 0){
			mostrarMensajes("error", "Disculpe debe indicar el documento asociado");
		}else{
			
					if(document.getElementById('origen1').checked == true){
						origen = "numero_propio";	
					}else{
						origen = "numero_asociado";
					}
					
					
					if(document.getElementById('option_monto_fijo').checked == true){
						tipo_monto = "monto_fijo";	
					}else{
						tipo_monto = "porcentaje";	
					}
					
					
					var tabla_deudora = document.getElementById('tabla_deudora').value;
					var idcuenta_deudora = document.getElementById('idcuenta_deudora').value;
					var tabla_acreedora = document.getElementById('tabla_acreedora').value;
					var idcuenta_acreedora = document.getElementById('idcuenta_acreedora').value;
					
					var ajax=nuevoAjax();
					ajax.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajax.onreadystatechange=function() { 
						if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
							}
						
						if (ajax.readyState==4){
								document.getElementById("listaRetenciones").innerHTML=ajax.responseText;
									var ajax2=nuevoAjax();
									ajax2.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);
									ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
									ajax2.onreadystatechange=function() { 
										if (ajax.readyState==4){
											document.getElementById("tablaGeneral").innerHTML=ajax2.responseText;
										}
									}
									ajax2.send("ejecutar=nuevoRegistro");
								document.getElementById("divCargando").style.display = "none";
						} 
					}
					ajax.send("codigo="+codigo+"&descripcion="+descripcion+"&monto_fijo="+monto_fijo+"&porcentaje="+porcentaje+"&divisor="+divisor+"&base_calculo="+base_calculo+"&unidad_tributaria="+unidad_tributaria+"&factor_calculo="+factor_calculo+"&contador="+contador+"&documentoAsociado="+documentoAsociado+"&articulo="+articulo+"&numeral="+numeral+"&literal="+literal+"&origen="+origen+"&tipo_monto="+tipo_monto+"&idtipo_retencion="+idtipo_retencion+"&nombre_comprobante="+nombre_comprobante+"&tabla_deudora="+tabla_deudora+"&idcuenta_deudora="+idcuenta_deudora+"&tabla_acreedora="+tabla_acreedora+"&idcuenta_acreedora="+idcuenta_acreedora+"&ejecutar=modificarRetencion");
		}
}








function ingresarRetencion(codigo, descripcion, monto_fijo, porcentaje, divisor, base_calculo, unidad_tributaria, factor_calculo, contador, documentoAsociado, articulo, numeral, literal, nombre_comprobante){
		if(codigo == ""){
			mostrarMensajes("error", "Disculpe debe ingresar el Codigo");
		}else if(descripcion == ""){
			mostrarMensajes("error", "Disculpa debe ingresar la Descripcion");
		}else if(document.getElementById('option_monto_fijo').checked == false && document.getElementById('option_porcentaje').checked == false){
			mostrarMensajes("error", "Disculpe debe seleccionar la opcion de monto");
		}else if(document.getElementById('option_monto_fijo').checked == true && monto_fijo == ""){
			mostrarMensajes("error", "Disculpe debe ingresar el monto fijo");
		}else if(document.getElementById('option_porcentaje').checked == true && (porcentaje == "" || divisor == "")){
			mostrarMensajes("error", "Disculpe debe ingresar el porcentaje y el divisor");
		}else if(document.getElementById('origen1').checked == true && contador == ""){
			mostrarMensajes("error", "Disculpe debe indicar el numero inicial del contador");
		}else if(document.getElementById('origen2').checked == true && documentoAsociado == 0){
			mostrarMensajes("error", "Disculpe debe indicar el documento asociado");
		}else{
			
					if(document.getElementById('origen1').checked == true){
						origen = "numero_propio";	
					}else{
						origen = "numero_asociado";
					}
					
					
					if(document.getElementById('option_monto_fijo').checked == true){
						tipo_monto = "monto_fijo";	
					}else{
						tipo_monto = "porcentaje";	
					}
					
					var tabla_deudora = document.getElementById('tabla_deudora').value;
					var idcuenta_deudora = document.getElementById('idcuenta_deudora').value;
					var tabla_acreedora = document.getElementById('tabla_acreedora').value;
					var idcuenta_acreedora = document.getElementById('idcuenta_acreedora').value;
					
					var ajax=nuevoAjax();
					ajax.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);	
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					ajax.onreadystatechange=function() { 
						if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
							}
						
						if (ajax.readyState==4){
							
								document.getElementById("listaRetenciones").innerHTML=ajax.responseText;
									var ajax2=nuevoAjax();
									ajax2.open("POST", "modulos/tributos/lib/tipo_retencion_ajax.php", true);
									ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
									ajax2.onreadystatechange=function() { 
										if (ajax.readyState==4){
											document.getElementById("tablaGeneral").innerHTML=ajax2.responseText;
										}
									}
									ajax2.send("ejecutar=nuevoRegistro");
								document.getElementById("divCargando").style.display = "none";
						} 
					}
					ajax.send("codigo="+codigo+"&descripcion="+descripcion+"&monto_fijo="+monto_fijo+"&porcentaje="+porcentaje+"&divisor="+divisor+"&base_calculo="+base_calculo+"&unidad_tributaria="+unidad_tributaria+"&factor_calculo="+factor_calculo+"&contador="+contador+"&documentoAsociado="+documentoAsociado+"&articulo="+articulo+"&numeral="+numeral+"&literal="+literal+"&origen="+origen+"&tipo_monto="+tipo_monto+"&nombre_comprobante="+nombre_comprobante+"&tabla_deudora="+tabla_deudora+"&idcuenta_deudora="+idcuenta_deudora+"&tabla_acreedora="+tabla_acreedora+"&idcuenta_acreedora="+idcuenta_acreedora+"&ejecutar=ingresarRetencion");
		}
}