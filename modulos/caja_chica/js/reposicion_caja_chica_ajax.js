function cambiarColor(id, color){
	if(document.getElementById("pestana_seleccionada").value != id){
		document.getElementById(id).bgColor = color;	
	}
	
}






function ingresarDatosBasicos(){
	var idcategoria_programatica= document.getElementById("id_categoria_programatica").value;
	var justificacion = document.getElementById("justificacion").value;
	var idbeneficiarios = document.getElementById("id_beneficiarios").value;
	var idtipo_caja_chica = document.getElementById("tipo_caja_chica").value;
	
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	
	var idtipos_documentos = document.getElementById("tipos_documentos").value;
	
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("div_parte_inferior").style.display='block';
				document.getElementById("idrendicion").value = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
				document.getElementById("boton_siguiente").style.display = 'none';
				document.getElementById("boton_procesar").style.display = 'block';
				document.getElementById("boton_anular").style.display = 'none';
				consultarPestanas();
		} 
	}
	ajax.send("idtipos_documentos="+idtipos_documentos+"&fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&idtipo_caja_chica="+idtipo_caja_chica+"&justificacion="+justificacion+"&id_beneficiarios="+idbeneficiarios+"&idcategoria_programatica="+idcategoria_programatica+"&ejecutar=ingresarDatosBasicos");	
}




function consultarPestanas(){
	var idrendicion = document.getElementById("idrendicion").value;
	var idpestana = document.getElementById('pestana_seleccionada').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("celda_pestanas").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idpestana="+idpestana+"&idrendicion="+idrendicion+"&ejecutar=consultarPestanas");	
}




function eliminarFactura(){
	if(confirm("Seguro desea eliminar esta factura?")){
	var idrendicion = document.getElementById("idrendicion").value;
	var idfactura = document.getElementById("idfactura").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
				//document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("tabla_datos_factura").style.display='block';
				document.getElementById("tabla_totales_factura").style.display='block';
				
				document.getElementById("idfactura").value = ajax.responseText;
				//document.getElementById('pestana_seleccionada').value = "id"+ajax.responseText+"";
				document.getElementById("nro_factura").value="";
				document.getElementById("fecha_factura").value="";
				document.getElementById("nro_control").value="";
				document.getElementById("nombre_beneficiario_factura").value="";
				document.getElementById("idbeneficiario_factura").value="";
				document.getElementById("contribuyente_ordinario_factura").value = ""
				document.getElementById("boton_ingresar_factura").style.display = 'none';
				//document.getElementById("divCargando").style.display = "none";
				document.getElementById("celda_nuevos_materiales").style.display = "none";
				document.getElementById("celda_materiales").innerHTML = 'Sin Materiales Asociados';
				consultarPestanas();
				actualizarTotalesFactura();
				actualizarListaPartidas();
				actualizarTotalesGenerales();
				
		} 
	}
	ajax.send("idcategoria_programatica="+idcategoria_programatica+"&idfactura="+idfactura+"&idrendicion="+idrendicion+"&ejecutar=eliminarFactura");	
	}
}





function crearNuevaFactura(){
	var idrendicion = document.getElementById("idrendicion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
				//document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("tabla_datos_factura").style.display='block';
				document.getElementById("tabla_totales_factura").style.display='block';
				
				document.getElementById("idfactura").value = ajax.responseText;
				document.getElementById('pestana_seleccionada').value = "id"+ajax.responseText+"";
				document.getElementById("nro_factura").value="";
				document.getElementById("fecha_factura").value="";
				document.getElementById("nro_control").value="";
				document.getElementById("nombre_beneficiario_factura").value="";
				document.getElementById("idbeneficiario_factura").value="";
				document.getElementById("contribuyente_ordinario_factura").value = ""
				document.getElementById("boton_ingresar_factura").style.display = 'block';
				//document.getElementById("divCargando").style.display = "none";
				document.getElementById("celda_nuevos_materiales").style.display = "none";
				document.getElementById("celda_materiales").innerHTML = 'Sin Materiales Asociados';
				consultarPestanas();
				actualizarTotalesFactura();
				
		} 
	}
	ajax.send("idrendicion="+idrendicion+"&ejecutar=crearNuevaFactura");		
}







function actualizarMontosMaximos(idtipo_caja_chica){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			//document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				document.getElementById("celda_maximo_reponer").innerHTML = partes[0];
				document.getElementById("celda_maximo_factura").innerHTML = partes[1];
		} 
	}
	ajax.send("idtipo_caja_chica="+idtipo_caja_chica+"&ejecutar=actualizarMontosMaximos");		
}









function guardarFactura(){
	var idfactura = document.getElementById("idfactura").value;
	var nro_factura = document.getElementById("nro_factura").value;
	var fecha_factura = document.getElementById("fecha_factura").value;
	var nro_control = document.getElementById("nro_control").value;
	var idbeneficiarios = document.getElementById("idbeneficiario_factura").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				//document.getElementById("celda_pestanas").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
				consultarPestanas();
				document.getElementById("celda_nuevos_materiales").style.display = "block";
		} 
	}
	ajax.send("idfactura="+idfactura+"&nro_factura="+nro_factura+"&fecha_factura="+fecha_factura+"&nro_control="+nro_control+"&idbeneficiarios="+idbeneficiarios+"&ejecutar=guardarFactura");	
}









function ingresarMaterial(){
	var idfactura = document.getElementById("idfactura").value;
	var idrendicion = document.getElementById("idrendicion").value;
	var idmaterial = document.getElementById("id_material").value;
	var cantidad = document.getElementById("cantidad").value;
	var precio_unitario = document.getElementById("precio_unitario").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	
	
	var tipo_caja_chica = document.getElementById("tipo_caja_chica").value;
	var contribuyente_ordinario = document.getElementById("contribuyente_ordinario_factura").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "sobrepasa_ut"){
					mostrarMensajes("error", "Disculpe este producto hace que la factura sobre pase el limite de reposicion por factura de la caja chica");
				}else{
					actualizarListaMateriales();
					actualizarListaPartidas();
					actualizarTotalesGenerales();
					actualizarTotalesFactura();
					document.getElementById("celda_nuevos_materiales").style.display = "block";
				}
				document.getElementById("divCargando").style.display = "none";
				document.formularioIngresarMateriales.reset();
		} 
	}
	ajax.send("fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&contribuyente_ordinario="+contribuyente_ordinario+"&tipo_caja_chica="+tipo_caja_chica+"&idcategoria_programatica="+idcategoria_programatica+"&idrendicion="+idrendicion+"&idfactura="+idfactura+"&idmaterial="+idmaterial+"&cantidad="+cantidad+"&precio_unitario="+precio_unitario+"&ejecutar=ingresarMaterial");
	return false;
}




function actualizarListaMateriales(){
	var idfactura = document.getElementById("idfactura").value;
	var idrendicion = document.getElementById("idrendicion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("celda_materiales").innerHTML=ajax.responseText;
				//document.getElementById("celda_nuevos_materiales").style.display = "block";
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrendicion="+idrendicion+"&idfactura="+idfactura+"&ejecutar=actualizarListaMateriales");	
}



function actualizarListaPartidas(){
	
	var idrendicion = document.getElementById("idrendicion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			//document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("celda_partidas").innerHTML=ajax.responseText;
				//document.getElementById("celda_nuevos_materiales").style.display = "block";
		} 
	}
	ajax.send("idrendicion="+idrendicion+"&ejecutar=actualizarListaPartidas");	
}





function seleccionarFactura(idfactura, estado){
	document.getElementById("tabla_datos_factura").style.display='block';
	document.getElementById("tabla_totales_factura").style.display='block';
	if(estado == "elaboracion"){
	document.getElementById("celda_nuevos_materiales").style.display='block';
	}
	document.getElementById("idfactura").value = idfactura;
	actualizarListaMateriales();
	actualizarListaPartidas();
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			//document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				document.getElementById("nro_factura").value = partes[0];
				document.getElementById("fecha_factura").value = partes[1];
				document.getElementById("nro_control").value = partes[2];
				document.getElementById("idbeneficiario_factura").value = partes[3];
				document.getElementById("nombre_beneficiario_factura").value = partes[4];
				document.getElementById("contribuyente_ordinario_factura").value = partes[5];
				document.getElementById("boton_ingresar_factura").style.display = 'block';
				//document.getElementById("divCargando").style.display = "block";
				actualizarTotalesFactura();
				consultarPestanas();
		} 
	}
	ajax.send("idfactura="+idfactura+"&ejecutar=seleccionarFactura");	
	
	
}








function actualizarPrecioCantidad(precio, cantidad, idmaterial, idmaterial_compra){
	var idfactura = document.getElementById("idfactura").value;
	var idrendicion = document.getElementById("idrendicion").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	var tipo_caja_chica = document.getElementById("tipo_caja_chica").value;
	var contribuyente_ordinario = document.getElementById("contribuyente_ordinario_factura").value;
	
	
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "sobrepasa_ut"){
					mostrarMensajes("error", "Disculpe no se puede editar el precio y la cantidad de este producto debido a que haciendolo superaria el maximo permitido por factura para realizar la rendicion de la caja chica");
					actualizarListaMateriales();
					actualizarListaPartidas();
					actualizarTotalesGenerales();
					actualizarTotalesFactura();
				}else{
					actualizarListaMateriales();
					actualizarListaPartidas();
					actualizarTotalesGenerales();
					actualizarTotalesFactura();
					//document.getElementById("celda_nuevos_materiales").style.display = "block";
				}
				document.getElementById("divCargando").style.display = "none";
				document.formularioIngresarMateriales.reset();
		} 
	}
	ajax.send("fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&precio="+precio+"&cantidad="+cantidad+"&idmaterial="+idmaterial+"&idmaterial_compra="+idmaterial_compra+"&idfactura="+idfactura+"&idrendicion="+idrendicion+"&idcategoria_programatica="+idcategoria_programatica+"&&tipo_caja_chica="+tipo_caja_chica+"&&contribuyente_ordinario="+contribuyente_ordinario+"&ejecutar=actualizarPrecioCantidad");
}



function eliminarMateriales(idmaterial){
	var idrendicion = document.getElementById("idrendicion").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	var tipo_caja_chica = document.getElementById("tipo_caja_chica").value;
	var contribuyente_ordinario = document.getElementById("contribuyente_ordinario_factura").value;
	
	
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				actualizarListaMateriales();
				actualizarListaPartidas();
				actualizarTotalesGenerales();
				actualizarTotalesFactura();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&idmaterial="+idmaterial+"&idrendicion="+idrendicion+"&idcategoria_programatica="+idcategoria_programatica+"&tipo_caja_chica="+tipo_caja_chica+"&contribuyente_ordinario="+contribuyente_ordinario+"&ejecutar=eliminarMateriales");
}




function actualizarTotalesGenerales(){
	var idrendicion = document.getElementById("idrendicion").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				document.getElementById("celda_exento").innerHTML = partes[0];
				document.getElementById("celda_subtotal").innerHTML = partes[1];
				document.getElementById("celda_impuesto").innerHTML = partes[2];
				document.getElementById("celda_total").innerHTML = partes[3];
		} 
	}
	ajax.send("idrendicion="+idrendicion+"&ejecutar=actualizarTotalesGenerales");	
}




function actualizarTotalesFactura(){
	var idrendicion = document.getElementById("idrendicion").value;
	var idfactura = document.getElementById("idfactura").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				document.getElementById("celda_exento_factura").innerHTML = partes[0];
				document.getElementById("celda_subtotal_factura").innerHTML = partes[1];
				document.getElementById("celda_impuesto_factura").innerHTML = partes[2];
				document.getElementById("celda_total_factura").innerHTML = partes[3];
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idfactura="+idfactura+"&idrendicion="+idrendicion+"&ejecutar=actualizarTotalesFactura");	
}





function procesarOrden(){
	if(confirm("Realmente desea procesar lareposicionde caja chica?")){
	var idrendicion = document.getElementById("idrendicion").value;
	var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
	
	var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
	var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
	var anio = document.getElementById("anio").value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			if(partes[0] == "exito"){
				actualizarListaMateriales();
				document.getElementById("celda_estado").innerHTML="Procesado";
				document.getElementById("celda_numero_orden").innerHTML=partes[1];
				document.getElementById("boton_siguiente").style.display = 'none';
				document.getElementById("boton_procesar").style.display = 'none';
				document.getElementById("boton_anular").style.display = 'block';
				document.getElementById('boton_ingresar_factura').style.display = 'none'
				setTimeout("document.getElementById('celda_nuevos_materiales').style.display = 'none'",200);
				consultarPestanas();
				
			}
		} 
	}
	ajax.send("fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&idcategoria_programatica="+idcategoria_programatica+"&idrendicion="+idrendicion+"&ejecutar=procesarOrden");	
	}
}



function anularOrden(){
	if(confirm("Realmente desea Anular la Reposicion de Caja Chica?")){
		var idrendicion = document.getElementById("idrendicion").value;
		var idcategoria_programatica = document.getElementById("id_categoria_programatica").value;
		
		var fuente_financiamiento = document.getElementById("fuente_financiamiento").value;
		var tipo_presupuesto = document.getElementById("tipo_presupuesto").value;
		var anio = document.getElementById("anio").value;
		
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				//if(partes[0] == "exito"){
					actualizarListaMateriales();
					document.getElementById("celda_estado").innerHTML="Anulado";
					document.getElementById("boton_siguiente").style.display = 'none';
					document.getElementById("boton_procesar").style.display = 'none';
					document.getElementById("boton_anular").style.display = 'none';
					document.getElementById("boton_ingresar_factura").style.display = 'none';
					setTimeout("document.getElementById('celda_nuevos_materiales').style.display = 'none'",200);
					consultarPestanas();
					
				//}
			} 
		}
		ajax.send("fuente_financiamiento="+fuente_financiamiento+"&tipo_presupuesto="+tipo_presupuesto+"&anio="+anio+"&idcategoria_programatica="+idcategoria_programatica+"&idrendicion="+idrendicion+"&ejecutar=anularOrden");	
	}	
}




function consultarOrden(idrendicion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/caja_chica/lib/reposicion_caja_chica_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("idrendicion").value = idrendicion;
				partes = ajax.responseText.split("|.|");
				document.getElementById("nombre_categoria").value = partes[0];
				document.getElementById("id_categoria_programatica").value = partes[1];
				document.getElementById("justificacion").value = partes[2];
				document.getElementById("nombre_proveedor").value = partes[3];
				document.getElementById("id_beneficiarios").value = partes[4];
				document.getElementById("tipo_caja_chica").value = partes[5];
				actualizarMontosMaximos(partes[5]);	
				document.getElementById("celda_estado").innerHTML = partes[6];
				document.getElementById("celda_numero_orden").innerHTML = partes[7];
				
				
				document.getElementById("fuente_financiamiento").value = partes[8];
				document.getElementById("tipo_presupuesto").value = partes[9];
				document.getElementById("anio").value = partes[10];
				document.getElementById("tipos_documentos").value = partes[11];
				document.getElementById("fecha_orden").innerHTML = partes[12];
				document.getElementById("fecha_elaboracion").innerHTML = partes[12];
				
				
				if(partes[6] == "elaboracion"){
					document.getElementById("boton_siguiente").style.display = 'none';
					document.getElementById("boton_procesar").style.display = 'block';
					document.getElementById("boton_anular").style.display = 'none';
				}else if(partes[6] == "procesado"){
					document.getElementById("boton_siguiente").style.display = 'none';
					document.getElementById("boton_procesar").style.display = 'none';
					document.getElementById("boton_anular").style.display = 'block';
				}else if(partes[6] == "anulado"){
					document.getElementById("boton_siguiente").style.display = 'none';
					document.getElementById("boton_procesar").style.display = 'none';
					document.getElementById("boton_anular").style.display = 'none';
				}
				
				
				
				document.getElementById("div_parte_inferior").style.display='block';
				//document.getElementById("boton_anular").style.display = 'none';
				consultarPestanas();
				actualizarTotalesGenerales();
				document.getElementById('buscarCategoriaProgramatica').style.display='block';
		} 
	}
	ajax.send("idrendicion="+idrendicion+"&ejecutar=consultarOrden");	
}