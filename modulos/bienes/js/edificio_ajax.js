// JavaScript Document

function mostrarContenido(contenido){
	document.getElementById('divDatosPrincipales').style.display = 'none';
	document.getElementById('divAnexidades').style.display = 'none';
	document.getElementById('divLinderos').style.display = 'none';
	document.getElementById(contenido).style.display = 'block';
	
}


// *****************************************************************************************************************************
// ***************************** AUTOGENERAR CODIGO ****************************************************************************
// *****************************************************************************************************************************
function generar_codigo(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/edificio_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById("idgenerar_codigo").style.display		='none';
			document.getElementById('codigo_bien').value = partes[1];
			document.getElementById('codigo_bien_automatico').value = partes[0];
		} 
	}
	ajax.send("ejecutar=generar_codigo");			
}



function ingresarEdificio(){
	var idtipo_movimiento = document.getElementById('idtipo_movimiento').value;
	var iddetalle_catalogo_bienes = document.getElementById('iddetalle_catalogo_bienes').value;
	var estado_municipio_propietario = document.getElementById('estado_municipio_propietario').value;
	var denominacion_inmueble = document.getElementById('denominacion_inmueble').value;
	var clasificacion_funcional_inmueble = document.getElementById('clasificacion_funcional_inmueble').value;
	var ubicacion_geografica_estado = document.getElementById('ubicacion_geografica_estado').value;
	var ubicacion_geografica_municipio = document.getElementById('ubicacion_geografica_municipio').value;
	var ubicacion_geografica_direccion = document.getElementById('ubicacion_geografica_direccion').value;
	var area_terreno = document.getElementById('area_terreno').value;
	var area_construccion = document.getElementById('area_construccion').value;
	var numero_pisos = document.getElementById('numero_pisos').value;
	var area_total_construccion = document.getElementById('area_total_construccion').value;
	var area_anexidades = document.getElementById('area_anexidades').value;
	var organizacion = document.getElementById('organizacion').value;
	var codigo_bien = document.getElementById('codigo_bien').value;
	
	var tipo_estructura = '';
	
	if(document.getElementById('tipo_estructura').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	if(document.getElementById('tipo_estructura2').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura2').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	if(document.getElementById('tipo_estructura3').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura3').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	if(document.getElementById('tipo_estructura4').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura4').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura5').value;
	
	
	
	
	var pisos = "";
	
	if(document.getElementById('pisos').checked == true){
		pisos = pisos+document.getElementById('pisos').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos2').checked == true){
		pisos = pisos+document.getElementById('pisos2').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos3').checked == true){
		pisos = pisos+document.getElementById('pisos3').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos4').checked == true){
		pisos = pisos+document.getElementById('pisos4').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos5').checked == true){
		pisos = pisos+document.getElementById('pisos5').value+",";
	}else{
		pisos = pisos+",";
	}

	pisos = pisos+document.getElementById('pisos6').value;
	
	
	
	
	var paredes = "";
	
	if(document.getElementById('paredes').checked == true){
		paredes = paredes+document.getElementById('paredes').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes2').checked == true){
		paredes = paredes+document.getElementById('paredes2').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes3').checked == true){
		paredes = paredes+document.getElementById('paredes3').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes4').checked == true){
		paredes = paredes+document.getElementById('paredes4').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes5').checked == true){
		paredes = paredes+document.getElementById('paredes5').value+",";
	}else{
		paredes = paredes+",";
	}
	
	paredes = paredes+document.getElementById('paredes6').value;





					
	var techos = "";
	
	
	if(document.getElementById('techos').checked == true){
		techos = techos+document.getElementById('techos').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos2').checked == true){
		techos = techos+document.getElementById('techos2').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos3').checked == true){
		techos = techos+document.getElementById('techos3').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos4').checked == true){
		techos = techos+document.getElementById('techos4').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos5').checked == true){
		techos = techos+document.getElementById('techos5').value+",";
	}else{
		techos = techos+",";
	}
	
		techos = techos+document.getElementById('techos6').value;

	
	

					
	var puertas_ventanas = "";
	
	if(document.getElementById('puertas_ventanas').checked == true){
		puertas_ventanas = puertas_ventanas+document.getElementById('puertas_ventanas').value+",";
	}else{
		puertas_ventanas = puertas_ventanas+",";
	}
	if(document.getElementById('puertas_ventanas2').checked == true){
		puertas_ventanas = puertas_ventanas+document.getElementById('puertas_ventanas2').value+",";
	}else{
		puertas_ventanas = puertas_ventanas+",";
	}
	



	var servicios = "";
	if(document.getElementById('servicios').checked == true){
		servicios = servicios+document.getElementById('servicios').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios2').checked == true){
		servicios = servicios+document.getElementById('servicios2').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios3').checked == true){
		servicios = servicios+document.getElementById('servicios3').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios4').checked == true){
		servicios = servicios+document.getElementById('servicios4').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios5').checked == true){
		servicios = servicios+document.getElementById('servicios5').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios6').checked == true){
		servicios = servicios+document.getElementById('servicios6').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios7').checked == true){
		servicios = servicios+document.getElementById('servicios7').value+",";
	}else{
		servicios = servicios+",";
	}
	
	servicios = servicios+document.getElementById('servicios8').value;



					
	var otras_anexidades = "";
	
	
	if(document.getElementById('otras_anexidades').checked == true){
		otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades').value+",";
	}else{
		otras_anexidades = otras_anexidades+",";
	}
	if(document.getElementById('otras_anexidades2').checked == true){
		otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades2').value+",";
	}else{
		otras_anexidades = otras_anexidades+",";
	}
	if(document.getElementById('otras_anexidades3').checked == true){
		otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades3').value+",";
	}else{
		otras_anexidades = otras_anexidades+",";
	}
	otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades4').value;
	
	var linderos = document.getElementById('linderos').value;
	var estado_legal = document.getElementById('estado_legal').value;
	var valor_contabilidad_fecha = document.getElementById('valor_contabilidad_fecha').value;
	var valor_contabilidad_monto = document.getElementById('valor_contabilidad_monto').value;
	var mejoras_fecha = document.getElementById('mejoras_fecha').value;
	var mejoras_valor = document.getElementById('valor_mejoras').value;
	var mejoras_fecha2 = document.getElementById('mejoras_fecha2').value;
	var mejoras_valor2 = document.getElementById('valor_mejoras2').value;
	var mejoras_fecha3 = document.getElementById('mejoras_fecha3').value;
	var mejoras_valor3 = document.getElementById('valor_mejoras3').value;
	var mejoras_fecha4 = document.getElementById('mejoras_fecha4').value;
	var mejoras_valor4 = document.getElementById('valor_mejoras4').value;
	var mejoras_fecha5 = document.getElementById('mejoras_fecha5').value;
	var mejoras_valor5 = document.getElementById('valor_mejoras5').value;
	var avaluo_provicional = document.getElementById('avaluo_provicional').value;
	var planos_esquemas_fotocopias = document.getElementById('planos_esquemas_fotocopias').value;
	var preparado_por = document.getElementById('preparado_por').value;
	var lugar = document.getElementById('lugar').value;
	var cargo = document.getElementById('cargo').value;
	var fecha = document.getElementById('fecha').value;
	
	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/edificio_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					mostrarMensajes("exito", "Edificio Ingresado con Exito");
					document.getElementById("divCargando").style.display = "none";
					setTimeout("window.location.href= 'principal.php?accion=590&modulo=8'",5000);
			} 
		}
		ajax.send("codigo_bien="+codigo_bien+"&organizacion="+organizacion+"&idtipo_movimiento="+idtipo_movimiento+"&iddetalle_catalogo_bienes="+iddetalle_catalogo_bienes+"&estado_municipio_propietario="+estado_municipio_propietario+"&denominacion_inmueble="+denominacion_inmueble+"&clasificacion_funcional_inmueble="+clasificacion_funcional_inmueble+"&ubicacion_geografica_estado="+ubicacion_geografica_estado+"&ubicacion_geografica_municipio="+ubicacion_geografica_municipio+"&ubicacion_geografica_direccion="+ubicacion_geografica_direccion+"&area_terreno="+area_terreno+"&area_construccion="+area_construccion+"&numero_pisos="+numero_pisos+"&area_total_construccion="+area_total_construccion+"&area_anexidades="+area_anexidades+"&tipo_estructura="+tipo_estructura+"&pisos="+pisos+"&paredes="+paredes+"&techos="+techos+"&puertas_ventanas="+puertas_ventanas+"&servicios="+servicios+"&otras_anexidades="+otras_anexidades+"&linderos="+linderos+"&estado_legal="+estado_legal+"&valor_contabilidad_fecha="+valor_contabilidad_fecha+"&valor_contabilidad_monto="+valor_contabilidad_monto+"&mejoras_fecha="+mejoras_fecha+"&mejoras_valor="+mejoras_valor+"&mejoras_fecha2="+mejoras_fecha2+"&mejoras_valor2="+mejoras_valor2+"&mejoras_fecha3="+mejoras_fecha3+"&mejoras_valor3="+mejoras_valor3+"&mejoras_fecha4="+mejoras_fecha4+"&mejoras_valor4="+mejoras_valor4+"&mejoras_fecha5="+mejoras_fecha5+"&mejoras_valor5="+mejoras_valor5+"&avaluo_provicional="+avaluo_provicional+"&planos_esquemas_fotocopias="+planos_esquemas_fotocopias+"&preparado_por="+preparado_por+"&lugar="+lugar+"&fecha="+fecha+"&cargo="+cargo+"&ejecutar=ingresarEdificio");
}






function sumaMejoras(){
	valor_inicial = parseInt(document.getElementById('valor_contabilidad_monto').value); 
	mejora1 = parseInt(document.getElementById('valor_mejoras').value);
	mejora2 = parseInt(document.getElementById('valor_mejoras2').value);
	mejora3 = parseInt(document.getElementById('valor_mejoras3').value);
	mejora4 = parseInt(document.getElementById('valor_mejoras4').value);
	mejora5 = parseInt(document.getElementById('valor_mejoras5').value);
	total = valor_inicial + mejora1 + mejora2 + mejora3 + mejora4 + mejora5;	
	document.getElementById('total_mejoras_adquisicion_mostrado').value = total;
	formatoNumero('total_mejoras_adquisicion_mostrado', 'total_mejoras_adquisicion');
}



function cargarEdificio(idedificio,
						idtipo_movimiento,
						  iddetalle_catalogo_bienes,
						  estado_municipio_propietario,
						  denominacion_inmueble,
						  clasificacion_funcional_inmueble,
						  ubicacion_geografica_estado,
						  ubicacion_geografica_municipio,
						  ubicacion_geografica_direccion,
						  area_terreno,
						  area_construccion,
						  numero_pisos,
						  area_total_construccion,
						  area_anexidades,
						  tipo_estructura,
						  pisos,
						  paredes,
						  techos,
						  puertas_ventanas,
						  servicios,
						  otras_anexidades,
						  linderos,
						  estado_legal,
						  valor_contabilidad_fecha,
						  valor_contabilidad_monto,
						  mejoras_fecha,
						  mejoras_valor,
						  mejoras_fecha2,
						  mejoras_valor2,
						  mejoras_fecha3,
						  mejoras_valor3,
						  mejoras_fecha4,
						  mejoras_valor4,
						  mejoras_fecha5,
						  mejoras_valor5,
						  avaluo_provicional,
						  planos_esquemas_fotocopias,
						  preparado_por,
						  lugar,
						  fecha,
						  cargo,
						  organizacion,
						  codigo_bien){

	document.getElementById('idedificio').value = idedificio;
	document.getElementById('idtipo_movimiento').value = idtipo_movimiento;
	document.getElementById('iddetalle_catalogo_bienes').value = iddetalle_catalogo_bienes;
	document.getElementById('estado_municipio_propietario').value = estado_municipio_propietario;
	document.getElementById('denominacion_inmueble').value = denominacion_inmueble;
	document.getElementById('clasificacion_funcional_inmueble').value = clasificacion_funcional_inmueble;
	document.getElementById('ubicacion_geografica_estado').value = ubicacion_geografica_estado;
	cambiarMunicipios(ubicacion_geografica_estado);
	document.getElementById('ubicacion_geografica_direccion').value = ubicacion_geografica_direccion;
	document.getElementById('area_terreno').value = area_terreno;
	document.getElementById('area_construccion').value = area_construccion;
	document.getElementById('numero_pisos').value = numero_pisos;
	document.getElementById('area_total_construccion').value = area_total_construccion;
	document.getElementById('area_anexidades').value = area_anexidades;
	document.getElementById('organizacion').value = organizacion;
	document.getElementById('codigo_bien').value = codigo_bien;
	
	
	
	tipo_estructura_array = tipo_estructura.split(",");
	
	if(document.getElementById('tipo_estructura').value == tipo_estructura_array[0]){
		document.getElementById('tipo_estructura').checked = true;	
	}else{
		document.getElementById('tipo_estructura').checked = false;		
	}
	if(document.getElementById('tipo_estructura2').value == tipo_estructura_array[1]){
		document.getElementById('tipo_estructura2').checked = true;	
	}else{
		document.getElementById('tipo_estructura2').checked = false;	
	}
	if(document.getElementById('tipo_estructura3').value == tipo_estructura_array[2]){
		document.getElementById('tipo_estructura3').checked = true;
	}else{
		document.getElementById('tipo_estructura3').checked = false;
	}
	if(document.getElementById('tipo_estructura4').value == tipo_estructura_array[3]){
		document.getElementById('tipo_estructura4').checked = true;
	}else{
		document.getElementById('tipo_estructura4').checked = false;
	}
	document.getElementById('tipo_estructura5').value = tipo_estructura_array[4];
	
	
	
	pisos_array = pisos.split(",");
	
	if(document.getElementById('pisos').value == pisos_array[0]){
		document.getElementById('pisos').checked = true;
	}else{
		document.getElementById('pisos').checked = false;
	}
	if(document.getElementById('pisos2').value == pisos_array[1]){
		document.getElementById('pisos2').checked = true;
	}else{
		document.getElementById('pisos2').checked = false;	
	}
	if(document.getElementById('pisos3').value == pisos_array[2]){
		document.getElementById('pisos3').checked = true;	
	}else{
		document.getElementById('pisos3').checked = false;
	}
	if(document.getElementById('pisos4').value == pisos_array[3]){
		document.getElementById('pisos4').checked = true;
	}else{
		document.getElementById('pisos4').checked = false;	
	}
	if(document.getElementById('pisos5').value == pisos_array[4]){
		document.getElementById('pisos5').checked = true;	
	}else{
		document.getElementById('pisos5').checked = false;	
	}
	document.getElementById('pisos6').value = pisos_array[5];
	
	
	
				
	paredes_array = paredes.split(",");
	
	if(document.getElementById('paredes').value == paredes_array[0]){
		document.getElementById('paredes').checked = true;
	}else{
		document.getElementById('paredes').checked = false;
	}
	if(document.getElementById('paredes2').value == paredes_array[1]){
		document.getElementById('paredes2').checked = true;	
	}else{
		document.getElementById('paredes2').checked = false;	
	}
	if(document.getElementById('paredes3').value == paredes_array[2]){
		document.getElementById('paredes3').checked = true;	
	}else{
		document.getElementById('paredes3').checked = false;	
	}
	if(document.getElementById('paredes4').value == paredes_array[3]){
		document.getElementById('paredes4').checked = true;	
	}else{
		document.getElementById('paredes4').checked = false;	
	}
	if(document.getElementById('paredes5').value == paredes_array[4]){
		document.getElementById('paredes5').checked = true;
	}else{
		document.getElementById('paredes5').checked = false;
	}
	document.getElementById('paredes6').value = paredes_array[5];


	
	techos_array = techos.split(",");
	
	if(document.getElementById('techos').value == techos_array[0]){
		document.getElementById('techos').checked = true;
	}else{
		document.getElementById('techos').checked = false;
	}
	if(document.getElementById('techos2').value == techos_array[1]){
		document.getElementById('techos2').checked = true;	
	}else{
		document.getElementById('techos2').checked = false;	
	}
	if(document.getElementById('techos3').value == techos_array[2]){
		document.getElementById('techos3').checked = true;
	}else{
		document.getElementById('techos3').checked = false;	
	}
	if(document.getElementById('techos4').value == techos_array[3]){
		document.getElementById('techos4').checked = true;
	}else{
		document.getElementById('techos4').checked = false;	
	}
	if(document.getElementById('techos5').value == techos_array[4]){
		document.getElementById('techos5').checked = true;
	}else{
		document.getElementById('techos5').checked = false;	
	}
	document.getElementById('techos6').value = techos_array[5];


	
	puertas_ventanas_array = puertas_ventanas.split(",");
	
	
	if(document.getElementById('puertas_ventanas').value == puertas_ventanas_array[0]){
		document.getElementById('puertas_ventanas').checked = true;
	}else{
		document.getElementById('puertas_ventanas').checked = false;
	}
	if(document.getElementById('puertas_ventanas2').value == puertas_ventanas_array[1]){
		document.getElementById('puertas_ventanas2').checked = true;
	}else{
		document.getElementById('puertas_ventanas2').checked = false;	
	}
	
	
	servicios_array = servicios.split(",");
	
	if(document.getElementById('servicios').value == servicios_array[0]){
		document.getElementById('servicios').checked = true;	
	}else{
		document.getElementById('servicios').checked = false;		
	}
	if(document.getElementById('servicios2').value == servicios_array[1]){
		document.getElementById('servicios2').checked = true;
	}else{
		document.getElementById('servicios2').checked = false;
	}
	if(document.getElementById('servicios3').value == servicios_array[2]){
		document.getElementById('servicios3').checked = true;	
	}else{
		document.getElementById('servicios3').checked = false;	
	}
	if(document.getElementById('servicios4').value == servicios_array[3]){
		document.getElementById('servicios4').checked = true;
	}else{
		document.getElementById('servicios4').checked = false;	
	}
	if(document.getElementById('servicios5').value == servicios_array[4]){
		document.getElementById('servicios5').checked = true;
	}else{
		document.getElementById('servicios5').checked = false;	
	}
	if(document.getElementById('servicios6').value == servicios_array[5]){
		document.getElementById('servicios6').checked = true;
	}else{
		document.getElementById('servicios6').checked = false;	
	}
	if(document.getElementById('servicios7').value == servicios_array[6]){
		document.getElementById('servicios7').checked = true;
	}else{
		document.getElementById('servicios7').checked = false;	
	}
	document.getElementById('servicios8').value = servicios_array[7];


	
	
	otras_anexidades_array = otras_anexidades.split(",");
	
	
	
	if(document.getElementById('otras_anexidades').value == otras_anexidades_array[0]){
		document.getElementById('otras_anexidades').checked = true;
	}else{
		document.getElementById('otras_anexidades').checked = false;	
	}
	if(document.getElementById('otras_anexidades2').value == otras_anexidades_array[1]){
		document.getElementById('otras_anexidades2').checked = true;
	}else{
		document.getElementById('otras_anexidades2').checked = false;	
	}
	if(document.getElementById('otras_anexidades3').value == otras_anexidades_array[2]){
		document.getElementById('otras_anexidades3').checked = true;
	}else{
		document.getElementById('otras_anexidades3').checked = false;	
	}
	document.getElementById('otras_anexidades4').value = otras_anexidades_array[3];



	
	document.getElementById('linderos').value = linderos;
	document.getElementById('estado_legal').value = estado_legal;
	
	document.getElementById('valor_contabilidad_fecha').value = valor_contabilidad_fecha;
	document.getElementById('mejoras_fecha').value = mejoras_fecha;
	document.getElementById('mejoras_fecha2').value = mejoras_fecha2;
	document.getElementById('mejoras_fecha3').value = mejoras_fecha3;
	document.getElementById('mejoras_fecha4').value = mejoras_fecha4;
	document.getElementById('mejoras_fecha5').value = mejoras_fecha5;
	
	
	document.getElementById('valor_contabilidad_monto_mostrado').value = valor_contabilidad_monto;
	formatoNumero('valor_contabilidad_monto_mostrado', 'valor_contabilidad_monto');
	document.getElementById('valor_mejoras_mostrado').value = mejoras_valor;
	formatoNumero('valor_mejoras_mostrado', 'valor_mejoras');
	document.getElementById('valor_mejoras2_mostrado').value = mejoras_valor2;
	formatoNumero('valor_mejoras2_mostrado', 'valor_mejoras2');
	document.getElementById('valor_mejoras3_mostrado').value = mejoras_valor3;
	formatoNumero('valor_mejoras3_mostrado', 'valor_mejoras3');
	document.getElementById('valor_mejoras4_mostrado').value = mejoras_valor4;
	formatoNumero('valor_mejoras4_mostrado', 'valor_mejoras4');
	document.getElementById('valor_mejoras5_mostrado').value= mejoras_valor5;
	formatoNumero('valor_mejoras5_mostrado', 'valor_mejoras5');
	
	
	sumaMejoras();
	
	
	
	document.getElementById('avaluo_provicional').value = avaluo_provicional;
	document.getElementById('planos_esquemas_fotocopias').value = planos_esquemas_fotocopias;
	document.getElementById('preparado_por').value = preparado_por;
	document.getElementById('lugar').value = lugar;	
	document.getElementById('fecha').value = fecha;
	document.getElementById('cargo').value = cargo;	
	
	
	document.getElementById('botonEnviarFormulario').style.display = 'none';
	document.getElementById('botonModificarFormulario').style.display = 'block';

	setTimeout("document.getElementById('ubicacion_geografica_municipio').value = "+ubicacion_geografica_municipio+"", 600);
}











function editarEdificio(){
	var idedificio = document.getElementById('idedificio').value;
	var idtipo_movimiento = document.getElementById('idtipo_movimiento').value;
	var iddetalle_catalogo_bienes = document.getElementById('iddetalle_catalogo_bienes').value;
	var estado_municipio_propietario = document.getElementById('estado_municipio_propietario').value;
	var denominacion_inmueble = document.getElementById('denominacion_inmueble').value;
	var clasificacion_funcional_inmueble = document.getElementById('clasificacion_funcional_inmueble').value;
	var ubicacion_geografica_estado = document.getElementById('ubicacion_geografica_estado').value;
	var ubicacion_geografica_municipio = document.getElementById('ubicacion_geografica_municipio').value;
	var ubicacion_geografica_direccion = document.getElementById('ubicacion_geografica_direccion').value;
	var area_terreno = document.getElementById('area_terreno').value;
	var area_construccion = document.getElementById('area_construccion').value;
	var numero_pisos = document.getElementById('numero_pisos').value;
	var area_total_construccion = document.getElementById('area_total_construccion').value;
	var area_anexidades = document.getElementById('area_anexidades').value;
	var organizacion = document.getElementById('organizacion').value;
	var codigo_bien = document.getElementById('codigo_bien').value;
	
	var tipo_estructura = '';
	
	if(document.getElementById('tipo_estructura').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	if(document.getElementById('tipo_estructura2').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura2').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	if(document.getElementById('tipo_estructura3').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura3').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	if(document.getElementById('tipo_estructura4').checked == true){
		tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura4').value+",";
	}else{
		tipo_estructura = tipo_estructura+",";
	}
	tipo_estructura = tipo_estructura+document.getElementById('tipo_estructura5').value;
	
	
	
	
	var pisos = "";
	
	if(document.getElementById('pisos').checked == true){
		pisos = pisos+document.getElementById('pisos').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos2').checked == true){
		pisos = pisos+document.getElementById('pisos2').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos3').checked == true){
		pisos = pisos+document.getElementById('pisos3').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos4').checked == true){
		pisos = pisos+document.getElementById('pisos4').value+",";
	}else{
		pisos = pisos+",";
	}
	if(document.getElementById('pisos5').checked == true){
		pisos = pisos+document.getElementById('pisos5').value+",";
	}else{
		pisos = pisos+",";
	}

	pisos = pisos+document.getElementById('pisos6').value;
	
	
	
	
	var paredes = "";
	
	if(document.getElementById('paredes').checked == true){
		paredes = paredes+document.getElementById('paredes').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes2').checked == true){
		paredes = paredes+document.getElementById('paredes2').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes3').checked == true){
		paredes = paredes+document.getElementById('paredes3').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes4').checked == true){
		paredes = paredes+document.getElementById('paredes4').value+",";
	}else{
		paredes = paredes+",";
	}
	if(document.getElementById('paredes5').checked == true){
		paredes = paredes+document.getElementById('paredes5').value+",";
	}else{
		paredes = paredes+",";
	}
	
	paredes = paredes+document.getElementById('paredes6').value;





					
	var techos = "";
	
	
	if(document.getElementById('techos').checked == true){
		techos = techos+document.getElementById('techos').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos2').checked == true){
		techos = techos+document.getElementById('techos2').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos3').checked == true){
		techos = techos+document.getElementById('techos3').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos4').checked == true){
		techos = techos+document.getElementById('techos4').value+",";
	}else{
		techos = techos+",";
	}
	if(document.getElementById('techos5').checked == true){
		techos = techos+document.getElementById('techos5').value+",";
	}else{
		techos = techos+",";
	}
	
		techos = techos+document.getElementById('techos6').value;

	
	

					
	var puertas_ventanas = "";
	
	if(document.getElementById('puertas_ventanas').checked == true){
		puertas_ventanas = puertas_ventanas+document.getElementById('puertas_ventanas').value+",";
	}else{
		puertas_ventanas = puertas_ventanas+",";
	}
	if(document.getElementById('puertas_ventanas2').checked == true){
		puertas_ventanas = puertas_ventanas+document.getElementById('puertas_ventanas2').value+",";
	}else{
		puertas_ventanas = puertas_ventanas+",";
	}
	



	var servicios = "";
	if(document.getElementById('servicios').checked == true){
		servicios = servicios+document.getElementById('servicios').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios2').checked == true){
		servicios = servicios+document.getElementById('servicios2').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios3').checked == true){
		servicios = servicios+document.getElementById('servicios3').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios4').checked == true){
		servicios = servicios+document.getElementById('servicios4').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios5').checked == true){
		servicios = servicios+document.getElementById('servicios5').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios6').checked == true){
		servicios = servicios+document.getElementById('servicios6').value+",";
	}else{
		servicios = servicios+",";
	}
	if(document.getElementById('servicios7').checked == true){
		servicios = servicios+document.getElementById('servicios7').value+",";
	}else{
		servicios = servicios+",";
	}
	
	servicios = servicios+document.getElementById('servicios8').value;



					
	var otras_anexidades = "";
	
	
	if(document.getElementById('otras_anexidades').checked == true){
		otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades').value+",";
	}else{
		otras_anexidades = otras_anexidades+",";
	}
	if(document.getElementById('otras_anexidades2').checked == true){
		otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades2').value+",";
	}else{
		otras_anexidades = otras_anexidades+",";
	}
	if(document.getElementById('otras_anexidades3').checked == true){
		otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades3').value+",";
	}else{
		otras_anexidades = otras_anexidades+",";
	}
	otras_anexidades = otras_anexidades+document.getElementById('otras_anexidades4').value;
	
	



	
	var linderos = document.getElementById('linderos').value;
	var estado_legal = document.getElementById('estado_legal').value;
	var valor_contabilidad_fecha = document.getElementById('valor_contabilidad_fecha').value;
	var valor_contabilidad_monto = document.getElementById('valor_contabilidad_monto').value;
	var mejoras_fecha = document.getElementById('mejoras_fecha').value;
	var mejoras_valor = document.getElementById('valor_mejoras').value;
	var mejoras_fecha2 = document.getElementById('mejoras_fecha2').value;
	var mejoras_valor2 = document.getElementById('valor_mejoras2').value;
	var mejoras_fecha3 = document.getElementById('mejoras_fecha3').value;
	var mejoras_valor3 = document.getElementById('valor_mejoras3').value;
	var mejoras_fecha4 = document.getElementById('mejoras_fecha4').value;
	var mejoras_valor4 = document.getElementById('valor_mejoras4').value;
	var mejoras_fecha5 = document.getElementById('mejoras_fecha5').value;
	var mejoras_valor5 = document.getElementById('valor_mejoras5').value;
	var avaluo_provicional = document.getElementById('avaluo_provicional').value;
	var planos_esquemas_fotocopias = document.getElementById('planos_esquemas_fotocopias').value;
	var preparado_por = document.getElementById('preparado_por').value;
	var lugar = document.getElementById('lugar').value;
	var fecha = document.getElementById('fecha').value;
	var cargo = document.getElementById('cargo').value;
	

	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/edificio_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "Edificio Editado con Exito");
				document.getElementById("divCargando").style.display = "none";
				window.location.href= 'principal.php?accion=590&modulo=8';
			} 
		}
		ajax.send("codigo_bien="+codigo_bien+"&organizacion="+organizacion+"&idtipo_movimiento="+idtipo_movimiento+"&iddetalle_catalogo_bienes="+iddetalle_catalogo_bienes+"&estado_municipio_propietario="+estado_municipio_propietario+"&denominacion_inmueble="+denominacion_inmueble+"&clasificacion_funcional_inmueble="+clasificacion_funcional_inmueble+"&ubicacion_geografica_estado="+ubicacion_geografica_estado+"&ubicacion_geografica_municipio="+ubicacion_geografica_municipio+"&ubicacion_geografica_direccion="+ubicacion_geografica_direccion+"&area_terreno="+area_terreno+"&area_construccion="+area_construccion+"&numero_pisos="+numero_pisos+"&area_total_construccion="+area_total_construccion+"&area_anexidades="+area_anexidades+"&tipo_estructura="+tipo_estructura+"&pisos="+pisos+"&paredes="+paredes+"&techos="+techos+"&puertas_ventanas="+puertas_ventanas+"&servicios="+servicios+"&otras_anexidades="+otras_anexidades+"&linderos="+linderos+"&estado_legal="+estado_legal+"&valor_contabilidad_fecha="+valor_contabilidad_fecha+"&valor_contabilidad_monto="+valor_contabilidad_monto+"&mejoras_fecha="+mejoras_fecha+"&mejoras_valor="+mejoras_valor+"&mejoras_fecha2="+mejoras_fecha2+"&mejoras_valor2="+mejoras_valor2+"&mejoras_fecha3="+mejoras_fecha3+"&mejoras_valor3="+mejoras_valor3+"&mejoras_fecha4="+mejoras_fecha4+"&mejoras_valor4="+mejoras_valor4+"&mejoras_fecha5="+mejoras_fecha5+"&mejoras_valor5="+mejoras_valor5+"&avaluo_provicional="+avaluo_provicional+"&planos_esquemas_fotocopias="+planos_esquemas_fotocopias+"&preparado_por="+preparado_por+"&lugar="+lugar+"&fecha="+fecha+"&cargo="+cargo+"&idedificio="+idedificio+"&ejecutar=editarEdificio");
}







function eliminarEdificio(idedificio){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/edificio_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById("divCargando").style.display = "none";
			window.location.href= 'principal.php?accion=590&modulo=8';
		} 
	}
	ajax.send("?idedificio="+idedificio+"&ejecutar=eliminarEdificio");	
}
  
  
  
  
function cambiarMunicipios(idestado){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/edificio_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById("celda_ubicacion_geografica_municipio").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idestado="+idestado+"&ejecutar=cambiarMunicipios");	  
}




function validarCodigoBien(valor){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/edificio_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el Codigo del Bien Ingresado ya Existe");
					document.getElementById('codigo_bien').value = '';
					document.getElementById('botonEnviarFormulario').disabled = true;
					document.getElementById('botonModificarFormulario').disabled = true;
					document.getElementById('linkEliminar').disabled = true;
				}else{
					document.getElementById('botonEnviarFormulario').disabled = false;
					document.getElementById('botonModificarFormulario').disabled = false;
					document.getElementById('linkEliminar').disabled = false;	
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=validarCodigoBien");	
}