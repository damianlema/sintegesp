// JavaScript Document

function listaCompromete(){
	if(document.getElementById('causa').checked == true && document.getElementById('compromete').checked == false && document.getElementById('paga').checked == false){
		document.getElementById('documentoCompromete').style.display = 'block'	;
	}else{
		document.getElementById('documentoCompromete').style.display = 'none';
	}	
}


function consultarDatosGenerales(id_documento, tipo_consulta, modulo, documento_compromete){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("tablaGeneral").innerHTML=ajax.responseText;
				
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_documento="+id_documento+"&tipo_consulta="+tipo_consulta+"&modulo="+modulo+"&modulo="+modulo+"&documento_compromete="+documento_compromete+"&ejecutar=consultarDatosGenerales");
}





function eliminarDocumentos(idtipos_documentos, modulo){
	var ajax=nuevoAjax();
		ajax.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				if (ajax.responseText=='existen'){
					alert("No se puede eliminar el Tipo de Documento porque ya fue utilizado");
					document.getElementById("divCargando").style.display = "none";
				}else{
					document.getElementById("listaDocumentos").innerHTML=ajax.responseText;
					var ajax2=nuevoAjax();
					ajax2.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);
					ajax2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
					ajax2.onreadystatechange=function() { 
						if (ajax2.readyState==4){
							document.getElementById("tablaGeneral").innerHTML=ajax2.responseText;
						}
					}
					ajax2.send("ejecutar=nuevoRegistro");
					document.getElementById("divCargando").style.display = "none";
				}
			} 
		}
		ajax.send("idtipos_documentos="+idtipos_documentos+"&modulo="+modulo+"&ejecutar=eliminarDocumentos");
}


function modificarDocumentos(descripcion, siglas, contador, documentoAsociado , idtipos_documentos, modulo, documento_compromete, forma_preimpresa, multi_categoria, numero_modulos){
	i=0;
		var modulos_seleccionados = new Array();
		for(i=0;i<numero_modulos;i++){
			modulos_seleccionados[i] = document.getElementById('modulos2').options[i].value;
		}
		
		
		if(descripcion == ""){
			mostrarMensajes("error", "Discule debe ingresar la descripcion");
		}else if(siglas == ""){
			mostrarMensajes("error", "Disculpe debe ingresar las siglas");
		}else if(document.getElementById('origen1').checked == true && contador == ""){
			mostrarMensajes("error", "Disculpe debe indicar el numero inicial del contador");
		}else if(document.getElementById('origen2').checked == true && documentoAsociado == 0){
			mostrarMensajes("error", "Disculpe debe indicar el documento asociado");
		}else{
		
		
					if(document.getElementById('compromete').checked == true){
						compromete = "si";	
					}else{
						compromete = "no";	
					}
					
					if(document.getElementById('causa').checked == true){
						causa = "si";	
					}else{
						causa = "no";	
					}
					if(document.getElementById('paga').checked == true){
						paga = "si";	
					}else{
						paga = "no";	
					}
					
					if(document.getElementById('origen1').checked == true){
						origen = "numero_propio";	
					}else{
						origen = "numero_asociado";
					}
					
					if(document.getElementById('documento_padre').checked == true){
						documento_padre = 'si';	
					}else{
						documento_padre = 'no';
					}
					
					if(document.getElementById('forma_preimpresa').checked == true){
						forma_preimpresa = 'si';	
					}else{
						forma_preimpresa = 'no';
					}
					if(document.getElementById('multi_categoria').checked == true){
						multi_categoria = 'si';	
					}else{
						multi_categoria = 'no';
					}
					
					if(document.getElementById('fondos_terceros').checked == true){
						fondos_terceros = 'si';	
					}else{
						fondos_terceros = 'no';
					}
					if(document.getElementById('reversa_compromiso').checked == true){
						reversa_compromiso = 'si';	
					}else{
						reversa_compromiso = 'no';
					}
					if(document.getElementById('excluir_contabilidad').checked == true){
						excluir_contabilidad = 'si';	
					}else{
						excluir_contabilidad = 'no';
					}
					var tabla_deudora = document.getElementById('tabla_deudora').value;
					var idcuenta_deudora = document.getElementById('idcuenta_deudora').value;
					var tabla_acreedora = document.getElementById('tabla_acreedora').value;
					var idcuenta_acreedora = document.getElementById('idcuenta_acreedora').value;
					
					var ajax=nuevoAjax();
					ajax.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);	
					ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
					ajax.onreadystatechange=function() { 
						if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
						}
						if (ajax.readyState==4){
								document.getElementById("listaDocumentos").innerHTML=ajax.responseText;
								var ajax2=nuevoAjax();
								ajax2.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php?", true);
								ajax2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
								ajax2.onreadystatechange=function() { 
									if (ajax2.readyState==4){
										
										document.getElementById("tablaGeneral").innerHTML=ajax2.responseText;
									}
								}
								ajax2.send("modulo="+modulo+"&ejecutar=nuevoRegistro");
								document.getElementById("divCargando").style.display = "none";
						} 
					}
					ajax.send("reversa_compromiso="+reversa_compromiso+"&excluir_contabilidad="+excluir_contabilidad+"&fondos_terceros="+fondos_terceros+"&descripcion="+descripcion+"&siglas="+siglas+"&compromete="+compromete+"&causa="+causa+"&paga="+paga+"&origen="+origen+"&documento_padre="+documento_padre+"&contador="+contador+"&documentoAsociado="+documentoAsociado+"&idtipos_documentos="+idtipos_documentos+"&modulo="+modulo+"&documento_compromete="+documento_compromete+"&modulos_seleccionados="+modulos_seleccionados+"&forma_preimpresa="+forma_preimpresa+"&multi_categoria="+multi_categoria+"&tabla_deudora="+tabla_deudora+"&idcuenta_deudora="+idcuenta_deudora+"&tabla_acreedora="+tabla_acreedora+"&idcuenta_acreedora="+idcuenta_acreedora+"&ejecutar=modificarDocumentos");
		}
}








function ingresarDocumentos(descripcion, siglas, contador, documentoAsociado, modulo, documento_compromete, forma_preimpresa, multi_categoria, numero_modulos){
		i=0;
		var modulos_seleccionados = new Array();
		for(i=0;i<numero_modulos;i++){
			modulos_seleccionados[i] = document.getElementById('modulos2').options[i].value;
		}
		if(descripcion == ""){
			mostrarMensajes("error", "Discule debe ingresar la descripcion");
		}else if(siglas == ""){
			mostrarMensajes("error", "Disculpe debe ingresar las siglas");
		}else if(document.getElementById('origen1').checked == true && contador == ""){
			mostrarMensajes("error", "Disculpe debe indicar el numero inicial del contador");
		}else if(document.getElementById('origen2').checked == true && documentoAsociado == 0){
			mostrarMensajes("error", "Disculpe debe indicar el documento asociado");
		}else if(modulos_seleccionados == ""){
			mostrarMensajes("error", "Disculpe debe asignarle al menos un modulo");
		}else{
		
		
					if(document.getElementById('compromete').checked == true){
						compromete = "si";	
					}else{
						compromete = "no";	
					}
					
					if(document.getElementById('causa').checked == true){
						causa = "si";	
					}else{
						causa = "no";	
					}
					if(document.getElementById('paga').checked == true){
						paga = "si";	
					}else{
						paga = "no";	
					}
					
					if(document.getElementById('origen1').checked == true){
						origen = "numero_propio";	
					}else{
						origen = "numero_asociado";
					}
					
					if(document.getElementById('documento_padre').checked == true){
						documento_padre = 'si';	
					}else{
						documento_padre = 'no';
					}
					
					if(document.getElementById('forma_preimpresa').checked == true){
						forma_preimpresa = 'si';	
					}else{
						forma_preimpresa = 'no';
					}
					
					if(document.getElementById('multi_categoria').checked == true){
						multi_categoria = 'si';	
					}else{
						multi_categoria = 'no';
					}
					if(document.getElementById('fondos_terceros').checked == true){
						fondos_terceros = 'si';	
					}else{
						fondos_terceros = 'no';
					}
					if(document.getElementById('reversa_compromiso').checked == true){
						reversa_compromiso = 'si';	
					}else{
						reversa_compromiso = 'no';
					}
					if(document.getElementById('excluir_contabilidad').checked == true){
						excluir_contabilidad = 'si';	
					}else{
						excluir_contabilidad = 'no';
					}
					var tabla_deudora = document.getElementById('tabla_deudora').value;
					var idcuenta_deudora = document.getElementById('idcuenta_deudora').value;
					var tabla_acreedora = document.getElementById('tabla_acreedora').value;
					var idcuenta_acreedora = document.getElementById('idcuenta_acreedora').value;
					
					var ajax=nuevoAjax();
					ajax.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);
					ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
					ajax.onreadystatechange=function() { 
						if(ajax.readyState == 1){
							document.getElementById("divCargando").style.display = "block";
							}
						
						if (ajax.readyState==4){
								document.getElementById("listaDocumentos").innerHTML=ajax.responseText;
								var ajax2=nuevoAjax();
								ajax2.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);
								ajax2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
								ajax2.onreadystatechange=function() { 
									if (ajax2.readyState==4){
										document.getElementById("tablaGeneral").innerHTML=ajax2.responseText;
									}
								}
								ajax2.send("ejecutar=nuevoRegistro");
								document.getElementById("divCargando").style.display = "none";
								
								
			
							
						} 
					}
					ajax.send("reversa_compromiso="+reversa_compromiso+"&excluir_contabilidad="+excluir_contabilidad+"&fondos_terceros="+fondos_terceros+"&descripcion="+descripcion+"&siglas="+siglas+"&compromete="+compromete+"&causa="+causa+"&paga="+paga+"&origen="+origen+"&documento_padre="+documento_padre+"&contador="+contador+"&documentoAsociado="+documentoAsociado+"&modulo="+modulo+"&documento_compromete="+documento_compromete+"&modulos_seleccionados="+modulos_seleccionados+"&forma_preimpresa="+forma_preimpresa+"&multi_categoria="+multi_categoria+"&tabla_deudora="+tabla_deudora+"&idcuenta_deudora="+idcuenta_deudora+"&tabla_acreedora="+tabla_acreedora+"&idcuenta_acreedora="+idcuenta_acreedora+"&ejecutar=ingresarDocumentos");
		}
}




function verNumerosDisponibles(nombreDiv){
	//document.listaNumeros.style.display = 'none';
	document.getElementById(nombreDiv).style.display = 'block';
}






function seleccionarModulos(){
	var indice = document.getElementById('modulos').selectedIndex;
	var valor = document.getElementById('modulos').options[indice].value;
	var texto = document.getElementById('modulos').options[indice].text;
	var num_modulos2 = document.getElementById('modulos2').length;
	document.getElementById('modulos2').options[num_modulos2]= new Option(texto,valor);
	document.getElementById('modulos').options[indice] = null;
	document.getElementById('modulos2').options[num_modulos2].onclick = deseleccionarModulos;
}


function deseleccionarModulos(){
	var indice = document.getElementById('modulos2').selectedIndex;
	var valor = document.getElementById('modulos2').options[indice].value;
	var texto = document.getElementById('modulos2').options[indice].text;
	var num_modulos = document.getElementById('modulos').length;
	document.getElementById('modulos').options[num_modulos]= new Option(texto,valor);
	document.getElementById('modulos2').options[indice] = null;
	document.getElementById('modulos').options[num_modulos].onclick = seleccionarModulos;
}







function mostrarNumerosLibres(div, idtipos_documentos, divcontenido){
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById(div).style.display = 'block';
				document.getElementById(divcontenido).innerHTML = ajax.responseText;
				//document.getElementById("tablaGeneral").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipos_documentos="+idtipos_documentos+"&ejecutar=mostrarNumerosLibres");	
}




function buscarTipo(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	//alert("entro");
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/tipos_documentos/tipos_documentos_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaDocumentos").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("campoBuscar="+campoBuscar+"&ejecutar=buscarTipo");
}