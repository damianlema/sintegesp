// JavaScript Document

function mostrarContenido(contenido){
	document.getElementById('divDatosPrincipales').style.display = 'none';
	document.getElementById('divAnexidades').style.display = 'none';
	document.getElementById('divLinderos').style.display = 'none';
	document.getElementById(contenido).style.display = 'block';
	
}



function editarTerreno(){
	
	  var idterreno = document.getElementById("idterreno").value;
	  var idtipo_movimiento = document.getElementById("idtipo_movimiento").value;
	  var iddetalle_catalogo_bienes = document.getElementById("iddetalle_catalogo_bienes").value;
	  var estado_municipio = document.getElementById("estado_municipio").value;
	  var denominacion_inmueble = document.getElementById("denominacion_inmueble").value;
	  
	  var codigo_bien = document.getElementById("codigo_bien").value;
		if(document.getElementById("clasificacion_agricultura").checked == true){
			var clasificacion_agricultura = "si";
		}else{
			var clasificacion_agricultura = "no";
		}
	  
	  if(document.getElementById("clasificacion_ganaderia").checked == true){
			var clasificacion_ganaderia = "si";
		}else{
			var clasificacion_ganaderia = "no";
		}
		
	if(document.getElementById("clasificacion_mixto_agropecuario").checked == true){
			var clasificacion_mixto_agropecuario = "si";
		}else{
			var clasificacion_mixto_agropecuario = "no";
		}
	  
	  var clasificacion_otros = document.getElementById("clasificacion_otros").value;
	  var ubicacion_municipio = document.getElementById("ubicacion_municipio").value;
	  var ubicacion_territorio = document.getElementById("ubicacion_direccion").value;
	  var area_total_terreno_hectarias = document.getElementById("area_total_terreno_hectarias").value;
	  var area_total_terreno_metros = document.getElementById("area_total_terreno_metros").value;
	  var area_construccion_metros = document.getElementById("area_construccion_metros").value;
	  var tipografia_plana = document.getElementById("tipografia_plana").value;
	  var tipografia_semiplana = document.getElementById("tipografia_semiplana").value;
	  var tipografia_pendiente = document.getElementById("tipografia_pendiente").value;
	  var tipografia_muypendiente = document.getElementById("tipografia_muypendiente").value;
	  var cultivos_permanentes = document.getElementById("cultivos_permanentes").value;
	  var cultivos_deforestados = document.getElementById("cultivos_deforestados").value;
	  var otros_bosques = document.getElementById("otros_bosques").value;
	  var otros_tierras_incultas = document.getElementById("otros_tierras_incultas").value;
	  var otros_noaprovechables = document.getElementById("otros_noaprovechables").value;
	  var potreros_naturales = document.getElementById("potreros_naturales").value;
	  var potreros_cultivados = document.getElementById("potreros_cultivados").value;
	  var recursos_cursos = document.getElementById("recursos_cursos").value;
	  var recursos_manantiales = document.getElementById("recursos_manantiales").value;
	  var recursos_canales = document.getElementById("recursos_canales").value;
	  var recursos_embalses = document.getElementById("recursos_embalses").value;
	  var recursos_pozos = document.getElementById("recursos_pozos").value;
	  var recursos_acuaductos = document.getElementById("recursos_acuaductos").value;
	  var recursos_otros = document.getElementById("recursos_otros").value;
	  var cercas_longitud = document.getElementById("cercas_longitud").value;
	  var cercas_estantes = document.getElementById("cercas_estantes").value;
	  var cercas_material = document.getElementById("cercas_material").value;
	  var vias_interiores = document.getElementById("vias_interiores").value;
	  var otras_bienhechurias = document.getElementById("otras_bienhechurias").value;
	  var linceros = document.getElementById("linceros").value;
	  var estudio_legal = document.getElementById("estudio_legal").value;
	  var contabilidad_fecha = document.getElementById("contabilidad_fecha").value;
	  var contabilidad_valor = document.getElementById("contabilidad_valor").value;
	  var adicionales_fecha = document.getElementById("adicionales_fecha").value;
	  var adicionales_valor = document.getElementById("adicionales_valor").value;
	  var adicionales_fecha2 = document.getElementById("adicionales_fecha2").value;
	  var adicionales_valor2 = document.getElementById("adicionales_valor2").value;
	  var adicionales_fecha3 = document.getElementById("adicionales_fecha3").value;
	  var adicionales_valor3 = document.getElementById("adicionales_valor3").value;
	  var adicionales_fecha4 = document.getElementById("adicionales_fecha4").value;
	  var adicionales_valor4 = document.getElementById("adicionales_valor4").value;
	  var adicionales_fecha5 = document.getElementById("adicionales_fecha5").value;
	  var adicionales_valor5 = document.getElementById("adicionales_valor5").value;
	  var avaluo_hectarias = document.getElementById("avaluo_hectarias").value;
	  var avaluo_bs = document.getElementById("avaluo_bs").value;
	  var avaluo_hectarias2 = document.getElementById("avaluo_hectarias2").value;
	  var avaluo_bs2 = document.getElementById("avaluo_bs2").value;
	  var avaluo_hectarias3 = document.getElementById("avaluo_hectarias3").value;
	  var avaluo_bs3 = document.getElementById("avaluo_bs3").value;
	  var planos_esquemas_fotografias = document.getElementById("planos_esquemas_fotografias").value;
	  var preparado_por = document.getElementById("preparado_por").value;
	  var lugar = document.getElementById("lugar").value;
	  var fecha = document.getElementById("fecha").value;
	  var cargo = document.getElementById("cargo").value;
	  var organizacion = document.getElementById("organizacion").value;
	  
	  
	  var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/terreno_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Los datos fueron registrados con exito");	
					}else{
						mostrarMensajes("error", "Disculpe los datos no fueron registrados con exito, por favor intente de nuevo mas tarde");
					}
					document.getElementById("divCargando").style.display = "none";
					window.location.href= 'principal.php?accion=591&modulo=8';
			} 
		}
		ajax.send("organizacion="+organizacion+"&codigo_bien="+codigo_bien+"&idterreno="+idterreno+"&idtipo_movimiento="+idtipo_movimiento+"&iddetalle_catalogo_bienes="+iddetalle_catalogo_bienes+"&estado_municipio="+estado_municipio+"&denominacion_inmueble="+denominacion_inmueble+"&clasificacion_agricultura="+clasificacion_agricultura+"&clasificacion_ganaderia="+clasificacion_ganaderia+"&clasificacion_mixto_agropecuario="+clasificacion_mixto_agropecuario+"&clasificacion_otros="+clasificacion_otros+"&ubicacion_municipio="+ubicacion_municipio+"&ubicacion_territorio="+ubicacion_territorio+"&area_total_terreno_hectarias="+area_total_terreno_hectarias+"&area_total_terreno_metros="+area_total_terreno_metros+"&area_construccion_metros="+area_construccion_metros+"&tipografia_plana="+tipografia_plana+"&tipografia_semiplana="+tipografia_semiplana+"&tipografia_pendiente="+tipografia_pendiente+"&tipografia_muypendiente="+tipografia_muypendiente+"&cultivos_permanentes="+cultivos_permanentes+"&cultivos_deforestados="+cultivos_deforestados+"&otros_bosques="+otros_bosques+"&otros_tierras_incultas="+otros_tierras_incultas+"&otros_noaprovechables="+otros_noaprovechables+"&potreros_naturales="+potreros_naturales+"&potreros_cultivados="+potreros_cultivados+"&recursos_cursos="+recursos_cursos+"&recursos_manantiales="+recursos_manantiales+"&recursos_canales="+recursos_canales+"&recursos_embalses="+recursos_embalses+"&recursos_pozos="+recursos_pozos+"&recursos_acuaductos="+recursos_acuaductos+"&recursos_otros="+recursos_otros+"&cercas_longitud="+cercas_longitud+"&cercas_estantes="+cercas_estantes+"&cercas_material="+cercas_material+"&vias_interiores="+vias_interiores+"&otras_bienhechurias="+otras_bienhechurias+"&linceros="+linceros+"&estudio_legal="+estudio_legal+"&contabilidad_fecha="+contabilidad_fecha+"&contabilidad_valor="+contabilidad_valor+"&adicionales_fecha="+adicionales_fecha+"&adicionales_valor="+adicionales_valor+"&adicionales_fecha2="+adicionales_fecha2+"&adicionales_valor2="+adicionales_valor2+"&adicionales_fecha3="+adicionales_fecha3+"&adicionales_valor3="+adicionales_valor3+"&adicionales_fecha4="+adicionales_fecha4+"&adicionales_valor4="+adicionales_valor4+"&adicionales_fecha5="+adicionales_fecha5+"&adicionales_valor5="+adicionales_valor5+"&avaluo_hectarias="+avaluo_hectarias+"&avaluo_bs="+avaluo_bs+"&avaluo_hectarias2="+avaluo_hectarias2+"&avaluo_bs2="+avaluo_bs2+"&avaluo_hectarias3="+avaluo_hectarias3+"&avaluo_bs3="+avaluo_bs3+"&planos_esquemas_fotografias="+planos_esquemas_fotografias+"&preparado_por="+preparado_por+"&lugar="+lugar+"&fecha="+fecha+"&cargo="+cargo+"&ejecutar=editarTerreno");
	
	
}

















function ingresarTerreno(){
	
	  var idtipo_movimiento = document.getElementById("idtipo_movimiento").value;
	  var iddetalle_catalogo_bienes = document.getElementById("iddetalle_catalogo_bienes").value;
	  var estado_municipio = document.getElementById("estado_municipio").value;
	  var denominacion_inmueble = document.getElementById("denominacion_inmueble").value;
	
	 var codigo_bien = document.getElementById("codigo_bien").value;
	
	if(document.getElementById("clasificacion_agricultura").checked == true){
			var clasificacion_agricultura = "si";
		}else{
			var clasificacion_agricultura = "no";
		}
	  
	  if(document.getElementById("clasificacion_ganaderia").checked == true){
			var clasificacion_ganaderia = "si";
		}else{
			var clasificacion_ganaderia = "no";
		}
		
	if(document.getElementById("clasificacion_mixto_agropecuario").checked == true){
			var clasificacion_mixto_agropecuario = "si";
		}else{
			var clasificacion_mixto_agropecuario = "no";
		}
	  
	  var clasificacion_otros = document.getElementById("clasificacion_otros").value;
	  var ubicacion_municipio = document.getElementById("ubicacion_municipio").value;
	  var ubicacion_territorio = document.getElementById("ubicacion_direccion").value;
	  var area_total_terreno_hectarias = document.getElementById("area_total_terreno_hectarias").value;
	  var area_total_terreno_metros = document.getElementById("area_total_terreno_metros").value;
	  var area_construccion_metros = document.getElementById("area_construccion_metros").value;
	  var tipografia_plana = document.getElementById("tipografia_plana").value;
	  var tipografia_semiplana = document.getElementById("tipografia_semiplana").value;
	  var tipografia_pendiente = document.getElementById("tipografia_pendiente").value;
	  var tipografia_muypendiente = document.getElementById("tipografia_muypendiente").value;
	  var cultivos_permanentes = document.getElementById("cultivos_permanentes").value;
	  var cultivos_deforestados = document.getElementById("cultivos_deforestados").value;
	  var otros_bosques = document.getElementById("otros_bosques").value;
	  var otros_tierras_incultas = document.getElementById("otros_tierras_incultas").value;
	  var otros_noaprovechables = document.getElementById("otros_noaprovechables").value;
	  var potreros_naturales = document.getElementById("potreros_naturales").value;
	  var potreros_cultivados = document.getElementById("potreros_cultivados").value;
	  var recursos_cursos = document.getElementById("recursos_cursos").value;
	  var recursos_manantiales = document.getElementById("recursos_manantiales").value;
	  var recursos_canales = document.getElementById("recursos_canales").value;
	  var recursos_embalses = document.getElementById("recursos_embalses").value;
	  var recursos_pozos = document.getElementById("recursos_pozos").value;
	  var recursos_acuaductos = document.getElementById("recursos_acuaductos").value;
	  var recursos_otros = document.getElementById("recursos_otros").value;
	  var cercas_longitud = document.getElementById("cercas_longitud").value;
	  var cercas_estantes = document.getElementById("cercas_estantes").value;
	  var cercas_material = document.getElementById("cercas_material").value;
	  var vias_interiores = document.getElementById("vias_interiores").value;
	  var otras_bienhechurias = document.getElementById("otras_bienhechurias").value;
	  var linceros = document.getElementById("linceros").value;
	  var estudio_legal = document.getElementById("estudio_legal").value;
	  var contabilidad_fecha = document.getElementById("contabilidad_fecha").value;
	  var contabilidad_valor = document.getElementById("contabilidad_valor").value;
	  var adicionales_fecha = document.getElementById("adicionales_fecha").value;
	  var adicionales_valor = document.getElementById("adicionales_valor").value;
	  var adicionales_fecha2 = document.getElementById("adicionales_fecha2").value;
	  var adicionales_valor2 = document.getElementById("adicionales_valor2").value;
	  var adicionales_fecha3 = document.getElementById("adicionales_fecha3").value;
	  var adicionales_valor3 = document.getElementById("adicionales_valor3").value;
	  var adicionales_fecha4 = document.getElementById("adicionales_fecha4").value;
	  var adicionales_valor4 = document.getElementById("adicionales_valor4").value;
	  var adicionales_fecha5 = document.getElementById("adicionales_fecha5").value;
	  var adicionales_valor5 = document.getElementById("adicionales_valor5").value;
	  var avaluo_hectarias = document.getElementById("avaluo_hectarias").value;
	  var avaluo_bs = document.getElementById("avaluo_bs").value;
	  var avaluo_hectarias2 = document.getElementById("avaluo_hectarias2").value;
	  var avaluo_bs2 = document.getElementById("avaluo_bs2").value;
	  var avaluo_hectarias3 = document.getElementById("avaluo_hectarias3").value;
	  var avaluo_bs3 = document.getElementById("avaluo_bs3").value;
	  var planos_esquemas_fotografias = document.getElementById("planos_esquemas_fotografias").value;
	  var preparado_por = document.getElementById("preparado_por").value;
	  var lugar = document.getElementById("lugar").value;
	  var fecha = document.getElementById("fecha").value;
	  var cargo = document.getElementById("cargo").value;
	  var organizacion = document.getElementById("organizacion").value;
	  
	  
	  var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/terreno_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Los datos fueron modificados con exito");	
				}else{
					mostrarMensajes("error", "Los datos no fueron modificados con exito, por favor intente de nuevo mas tarde");
				}
					document.getElementById("divCargando").style.display = "none";
					setTimeout("window.location.href= 'principal.php?accion=591&modulo=8'",5000);
			} 
		}
		ajax.send("organizacion="+organizacion+"&codigo_bien="+codigo_bien+"&idtipo_movimiento="+idtipo_movimiento+"&iddetalle_catalogo_bienes="+iddetalle_catalogo_bienes+"&estado_municipio="+estado_municipio+"&denominacion_inmueble="+denominacion_inmueble+"&clasificacion_agricultura="+clasificacion_agricultura+"&clasificacion_ganaderia="+clasificacion_ganaderia+"&clasificacion_mixto_agropecuario="+clasificacion_mixto_agropecuario+"&clasificacion_otros="+clasificacion_otros+"&ubicacion_municipio="+ubicacion_municipio+"&ubicacion_territorio="+ubicacion_territorio+"&area_total_terreno_hectarias="+area_total_terreno_hectarias+"&area_total_terreno_metros="+area_total_terreno_metros+"&area_construccion_metros="+area_construccion_metros+"&tipografia_plana="+tipografia_plana+"&tipografia_semiplana="+tipografia_semiplana+"&tipografia_pendiente="+tipografia_pendiente+"&tipografia_muypendiente="+tipografia_muypendiente+"&cultivos_permanentes="+cultivos_permanentes+"&cultivos_deforestados="+cultivos_deforestados+"&otros_bosques="+otros_bosques+"&otros_tierras_incultas="+otros_tierras_incultas+"&otros_noaprovechables="+otros_noaprovechables+"&potreros_naturales="+potreros_naturales+"&potreros_cultivados="+potreros_cultivados+"&recursos_cursos="+recursos_cursos+"&recursos_manantiales="+recursos_manantiales+"&recursos_canales="+recursos_canales+"&recursos_embalses="+recursos_embalses+"&recursos_pozos="+recursos_pozos+"&recursos_acuaductos="+recursos_acuaductos+"&recursos_otros="+recursos_otros+"&cercas_longitud="+cercas_longitud+"&cercas_estantes="+cercas_estantes+"&cercas_material="+cercas_material+"&vias_interiores="+vias_interiores+"&otras_bienhechurias="+otras_bienhechurias+"&linceros="+linceros+"&estudio_legal="+estudio_legal+"&contabilidad_fecha="+contabilidad_fecha+"&contabilidad_valor="+contabilidad_valor+"&adicionales_fecha="+adicionales_fecha+"&adicionales_valor="+adicionales_valor+"&adicionales_fecha2="+adicionales_fecha2+"&adicionales_valor2="+adicionales_valor2+"&adicionales_fecha3="+adicionales_fecha3+"&adicionales_valor3="+adicionales_valor3+"&adicionales_fecha4="+adicionales_fecha4+"&adicionales_valor4="+adicionales_valor4+"&adicionales_fecha5="+adicionales_fecha5+"&adicionales_valor5="+adicionales_valor5+"&avaluo_hectarias="+avaluo_hectarias+"&avaluo_bs="+avaluo_bs+"&avaluo_hectarias2="+avaluo_hectarias2+"&avaluo_bs2="+avaluo_bs2+"&avaluo_hectarias3="+avaluo_hectarias3+"&avaluo_bs3="+avaluo_bs3+"&planos_esquemas_fotografias="+planos_esquemas_fotografias+"&preparado_por="+preparado_por+"&lugar="+lugar+"&fecha="+fecha+"&cargo="+cargo+"&ejecutar=ingresarTerreno");
	
	
}





function seleccionarDatos(idterreno,
						  idtipo_movimiento,
						  iddetalle_catalogo_bienes,
						  estado_municipio,
						  denominacion_inmueble,
						  clasificacion_agricultura,
						  clasificacion_ganaderia,
						  clasificacion_mixto_agropecuario,
						  clasificacion_otros,
						  ubicacion_municipio,
						  ubicacion_territorio,
						  area_total_terreno_hectarias,
						  area_total_terreno_metros,
						  area_construccion_metros,
						  tipografia_plana,
						  tipografia_semiplana,
						  tipografia_pendiente,
						  tipografia_muypendiente,
						  cultivos_permanentes,
						  cultivos_deforestados,
						  otros_bosques,
						  otros_tierras_incultas,
						  otros_noaprovechables,
						  potreros_naturales,
						  potreros_cultivados,
						  recursos_cursos,
						  recursos_manantiales,
						  recursos_canales,
						  recursos_embalses,
						  recursos_pozos,
						  recursos_acuaductos,
						  recursos_otros,
						  cercas_longitud,
						  cercas_estantes,
						  cercas_material,
						  vias_interiores,
						  otras_bienhechurias,
						  linceros,
						  estudio_legal,
						  contabilidad_fecha,
						  contabilidad_valor,
						  adicionales_fecha,
						  adicionales_valor,
						  adicionales_fecha2,
						  adicionales_valor2,
						  adicionales_fecha3,
						  adicionales_valor3,
						  adicionales_fecha4,
						  adicionales_valor4,
						  adicionales_fecha5,
						  adicionales_valor5,
						  avaluo_hectarias,
						  avaluo_bs,
						  avaluo_hectarias2,
						  avaluo_bs2,
						  avaluo_hectarias3,
						  avaluo_bs3,
						  planos_esquemas_fotografias,
						  preparado_por,
						  lugar,
						  fecha,
						  cargo,
						  codigo_bien,
						  organizacion){
	 
	 
	  document.getElementById("idterreno").value = idterreno;
	  document.getElementById("idtipo_movimiento").value = idtipo_movimiento;
	  document.getElementById("iddetalle_catalogo_bienes").value = iddetalle_catalogo_bienes;
	  document.getElementById("estado_municipio").value = estado_municipio;
	  
	  document.getElementById("codigo_bien").value = codigo_bien;
	  
	  cambiarMunicipios(estado_municipio);
	  document.getElementById("denominacion_inmueble").value = denominacion_inmueble;
		if(clasificacion_agricultura == "si"){
			document.getElementById("clasificacion_agricultura").checked = true;
		}else{
			document.getElementById("clasificacion_agricultura").checked = false;
		}
	  
	  	if(clasificacion_ganaderia == "si"){
			document.getElementById("clasificacion_ganaderia").checked = true;
		}else{
			document.getElementById("clasificacion_ganaderia").checked = false;
		}
	  
		if(clasificacion_mixto_agropecuario == "si"){
			document.getElementById("clasificacion_mixto_agropecuario").checked = true;
		}else{
			document.getElementById("clasificacion_mixto_agropecuario").checked = false;
		}
		
	  
	  document.getElementById("clasificacion_otros").value = clasificacion_otros;
	  
	  document.getElementById("ubicacion_direccion").value = ubicacion_territorio;
	  document.getElementById("area_total_terreno_hectarias").value = area_total_terreno_hectarias;
	  document.getElementById("area_total_terreno_metros").value =  area_total_terreno_metros;
	  document.getElementById("area_construccion_metros").value = area_construccion_metros;
	  
	  document.getElementById("tipografia_plana").value = tipografia_plana;
	  document.getElementById("tipografia_semiplana").value = tipografia_semiplana;
	  document.getElementById("tipografia_pendiente").value = tipografia_pendiente;
	  document.getElementById("tipografia_muypendiente").value = tipografia_muypendiente;
	  
	  sumarTipografia();
	  
	  
	  document.getElementById("cultivos_permanentes").value = cultivos_permanentes;
	  document.getElementById("cultivos_deforestados").value = cultivos_deforestados;
	  document.getElementById("otros_bosques").value = otros_bosques;
	  document.getElementById("otros_tierras_incultas").value = otros_tierras_incultas;
	  document.getElementById("otros_noaprovechables").value = otros_noaprovechables;
	  
	  document.getElementById("potreros_naturales").value = potreros_naturales;
	  document.getElementById("potreros_cultivados").value = potreros_cultivados;
	  sumarPotreros();
	  
	  document.getElementById("recursos_cursos").value = recursos_cursos;
	  document.getElementById("recursos_manantiales").value = recursos_manantiales;
	  document.getElementById("recursos_canales").value = recursos_canales;
	  document.getElementById("recursos_embalses").value = recursos_embalses;
	  document.getElementById("recursos_pozos").value = recursos_pozos;
	  document.getElementById("recursos_acuaductos").value = recursos_acuaductos;
	  document.getElementById("recursos_otros").value = recursos_otros;
	  document.getElementById("cercas_longitud").value = cercas_longitud;
	  document.getElementById("cercas_estantes").value = cercas_estantes;
	  document.getElementById("cercas_material").value = cercas_material;
	  document.getElementById("vias_interiores").value = vias_interiores;
	  document.getElementById("otras_bienhechurias").value = otras_bienhechurias;
	  document.getElementById("linceros").value = linceros;
	  document.getElementById("estudio_legal").value = estudio_legal;
	  document.getElementById("contabilidad_fecha").value = contabilidad_fecha;
	  document.getElementById("contabilidad_valor").value = contabilidad_valor;
	  document.getElementById("adicionales_fecha").value = adicionales_fecha;
	  document.getElementById("adicionales_valor").value = adicionales_valor;
	  document.getElementById("adicionales_fecha2").value = adicionales_fecha2;
	  document.getElementById("adicionales_valor2").value = adicionales_valor2;
	  document.getElementById("adicionales_fecha3").value = adicionales_fecha3;
	  document.getElementById("adicionales_valor3").value = adicionales_valor3;
	  document.getElementById("adicionales_fecha4").value = adicionales_fecha4;
	  document.getElementById("adicionales_valor4").value = adicionales_valor4;
	  document.getElementById("adicionales_fecha5").value = adicionales_fecha5;
	  document.getElementById("adicionales_valor5").value = adicionales_valor5;
	  
	  sumaMejoras();
	  
	  document.getElementById("avaluo_hectarias").value = avaluo_hectarias;
	  document.getElementById("avaluo_bs_mostrado").value = avaluo_bs;
	  document.getElementById("avaluo_bs").value = avaluo_bs;
	  document.getElementById("avaluo_hectarias2").value = avaluo_hectarias2;
	  document.getElementById("avaluo_bs2_mostrado").value = avaluo_bs2;
	  document.getElementById("avaluo_bs2").value = avaluo_bs2;
	  document.getElementById("avaluo_hectarias3").value = avaluo_hectarias3;
	  document.getElementById("avaluo_bs3_mostrado").value = avaluo_bs3;
	  document.getElementById("avaluo_bs3").value = avaluo_bs3;
	  
	  sumarAvaluo();
	  
	  document.getElementById("planos_esquemas_fotografias").value = planos_esquemas_fotografias;
	  document.getElementById("preparado_por").value = preparado_por;
	  document.getElementById("lugar").value = lugar;
	  document.getElementById("fecha").value = fecha;
	  document.getElementById("cargo").value = cargo;
	  document.getElementById("organizacion").value = organizacion;
	  
	  document.getElementById('botonEnviarFormulario').style.display = 'none';
	document.getElementById('botonModificarFormulario').style.display = 'block';
	
	
	setTimeout("document.getElementById('ubicacion_municipio').value = "+ubicacion_municipio+"", 600);
}



function sumarTipografia(){
	  tipografia_plana = parseInt(document.getElementById("tipografia_plana").value);
	  tipografia_semiplana = parseInt(document.getElementById("tipografia_semiplana").value);
	  tipografia_pendiente = parseInt(document.getElementById("tipografia_pendiente").value);
	  tipografia_muypendiente = parseInt(document.getElementById("tipografia_muypendiente").value);
	  var total = tipografia_plana + tipografia_semiplana + tipografia_pendiente + tipografia_muypendiente;
	  document.getElementById("total_tiografia").value = total;
}


function sumarPotreros(){
	potreros_naturales = parseInt(document.getElementById("potreros_naturales").value);
	  potreros_cultivados = parseInt(document.getElementById("potreros_cultivados").value);
	  var total = potreros_naturales + potreros_cultivados;
	  document.getElementById("total_potreros").value = total;
}

function sumaMejoras(){
	  contabilidad_valor = parseFloat(document.getElementById("contabilidad_valor").value);
	  adicionales_valor = parseFloat(document.getElementById("adicionales_valor").value);
	  adicionales_valor2 = parseFloat(document.getElementById("adicionales_valor2").value);
	  adicionales_valor3 = parseFloat(document.getElementById("adicionales_valor3").value);
	  adicionales_valor4 = parseFloat(document.getElementById("adicionales_valor4").value);
	  adicionales_valor5 = parseFloat(document.getElementById("adicionales_valor5").value);
	 total = contabilidad_valor + adicionales_valor + adicionales_valor2 + adicionales_valor3 + adicionales_valor4 + adicionales_valor5;
	 document.getElementById("total_mejoras_adquisicion_mostrado").value = total;
	 formatoNumero('total_mejoras_adquisicion_mostrado', 'total_mejoras_adquisicion');
}


function sumarAvaluo(){
	  avaluo_hectarias = parseInt(document.getElementById("avaluo_hectarias").value);
	  avaluo_bs = parseFloat(document.getElementById("avaluo_bs").value);
	  avaluo_hectarias2 = parseInt(document.getElementById("avaluo_hectarias2").value);
	  avaluo_bs2 = parseFloat(document.getElementById("avaluo_bs2").value);
	  avaluo_hectarias3 = parseInt(document.getElementById("avaluo_hectarias3").value);
	  avaluo_bs3 = parseFloat(document.getElementById("avaluo_bs3").value);	
	  
	  total_avaluo = avaluo_hectarias * avaluo_bs;
	  total_avaluo2 = avaluo_hectarias2 * avaluo_bs2;
	  total_avaluo3 = avaluo_hectarias3 * avaluo_bs3;
	  
	  document.getElementById("total_avaluo_mostrado").value = total_avaluo;
	  document.getElementById("total_avaluo2_mostrado").value = total_avaluo2;
	  document.getElementById("total_avaluo3_mostrado").value = total_avaluo3;
	  document.getElementById("total_avaluo_general_mostrado").value = total_avaluo + total_avaluo2 + total_avaluo3;
	  
	  
	  formatoNumero('total_avaluo_mostrado', 'total_avaluo');
	  formatoNumero('total_avaluo2_mostrado', 'total_avaluo2');
	  formatoNumero('total_avaluo3_mostrado', 'total_avaluo3');
	  formatoNumero('total_avaluo_general_mostrado', 'total_avaluo_general');
}




function eliminarTerreno(idterreno){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/terreno_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Los datos fueron eliminados con exito");
				}else{
					mostrarMensajes("error", "Disculpe los datos no fueron eliminados con exito, por favor intente de nuevo mas tarde");
				}
					
					document.getElementById("divCargando").style.display = "none";
					window.location.href= 'principal.php?accion=591&modulo=8';
			} 
		}
		ajax.send("idterreno="+idterreno+"&ejecutar=eliminarTerreno");	
}



function cambiarMunicipios(idestado){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/terreno_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById("celda_ubicacion_municipio").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idestado="+idestado+"&ejecutar=cambiarMunicipios");	  
}



function validarCodigoBien(valor){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/terreno_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "existe"){
					alert("Disculpe el Codigo del Bien Ingresado ya Existe");
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