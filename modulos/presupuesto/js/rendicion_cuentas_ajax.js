// JavaScript Document


function procesarDatosBasicos(){
	var anio = document.getElementById('anio').value;
	var mes = document.getElementById('mes').value;
	var concepto = document.getElementById('concepto').value;
	var id_categoria_programatica = document.getElementById('id_categoria_programatica').value;
	var tipo_presupuesto = document.getElementById('tipo_presupuesto').value;
	var fuente_financiamiento = document.getElementById('fuente_financiamiento').value;
	var ordinal = document.getElementById('ordinal').value;
	
	if(id_categoria_programatica == ""){
		mostrarMensajes("error", "Disculpe debe seleccionar la Categoria Programatica");
	}else{
	
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/rendicion_cuentas_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					partes =  ajax.responseText.split("|.|");
					
					if(partes[0] == "exito"){
						//document.getElementById('botonSiguiente').style.display = 'none';
						buscarPartidas(partes[1], anio, id_categoria_programatica, tipo_presupuesto, fuente_financiamiento, ordinal);
						document.getElementById('anio').disabled = true;
						document.getElementById('mes').disabled = true;
						document.getElementById('concepto').disabled = true;
						document.getElementById('categoria_programatica').disabled = true;
						document.getElementById('tipo_presupuesto').disabled = true;
						document.getElementById('fuente_financiamiento').disabled = true;
						document.getElementById('descripcion_ordinal').disabled = true;
					}else if(partes[0] == "fallo"){
						mostrarMensajes("error", "Disculpe no se pudo registrar la rendicion de cuenta: "+partes[1]);
					}else if(partes[0] == "repetido"){
						document.getElementById('concepto').value = partes[2];
						buscarPartidas(partes[1],
									document.getElementById('anio').value, 
									document.getElementById('id_categoria_programatica').value, 
									document.getElementById('tipo_presupuesto').value, 
									document.getElementById('fuente_financiamiento').value, 
									document.getElementById('ordinal').value,
									'consultar');	
					}else{
						mostrarMensajes("error", "ERROR: "+ajax.responseText);
					}
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("anio="+anio+"&mes="+mes+"&concepto="+concepto+"&id_categoria_programatica="+id_categoria_programatica+"&tipo_presupuesto="+tipo_presupuesto+"&fuente_financiamiento="+fuente_financiamiento+"&ordinal="+ordinal+"&ejecutar=procesarDatosBasicos");
		
	}
}



function buscarPartidas(idrendicion_cuentas, anio, id_categoria_programatica, tipo_presupuesto, fuente_financiamiento, ordinal, accion){
var mes = document.getElementById('mes').value;
var ajax=nuevoAjax();
	ajax.open("POST", "modulos/presupuesto/lib/rendicion_cuentas_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById("divCargando").style.display = "none";
				document.getElementById('listaPartidas').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idrendicion_cuentas="+idrendicion_cuentas+"&anio="+anio+"&mes="+mes+"&id_categoria_programatica="+id_categoria_programatica+"&tipo_presupuesto="+tipo_presupuesto+"&fuente_financiamiento="+fuente_financiamiento+"&ordinal="+ordinal+"&accion="+accion+"&ejecutar=buscarPartidas");
	
}




function actualizarPartida(idrendicion_cuentas_partidas, idrendicion_cuentas, idmaestro_presupuesto, aumento_periodo, disminucion_periodo, compromisos_periodo, causado_periodo, pagado_periodo, reversa){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/presupuesto/lib/rendicion_cuentas_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Patida Actualizada con Exito");
					buscarPartidas(idrendicion_cuentas,
									document.getElementById('anio').value, 
									document.getElementById('id_categoria_programatica').value, 
									document.getElementById('tipo_presupuesto').value, 
									document.getElementById('fuente_financiamiento').value, 
									document.getElementById('ordinal').value,
									'consultar');
				}else{
					mostrarMensajes("error", "Disculpe no se pudo actualizar la partida, intente de nuevo mas tarde");
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("reversa="+reversa+"&idmaestro_presupuesto="+idmaestro_presupuesto+"&aumento_periodo="+aumento_periodo+"&disminucion_periodo="+disminucion_periodo+"&compromisos_periodo="+compromisos_periodo+"&causado_periodo="+causado_periodo+"&pagado_periodo="+pagado_periodo+"&idrendicion_cuentas_partidas="+idrendicion_cuentas_partidas+"&ejecutar=actualizarPartida")
}