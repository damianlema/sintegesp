// JavaScript Document

function ingresarConcepto(){
	var codigo = document.getElementById('codigo').value;
	var anio = document.getElementById('anio').value;
	var denominacion = document.getElementById('denominacion').value;
	var tipo_base_aforo = document.getElementById('tipo_base_aforo').value;
	var tipo_calculo_aforo = document.getElementById('tipo_calculo_aforo').value;
	var porcentual_valor_aforo = document.getElementById('porcentual_valor_aforo').value;
	var porcentual_divisor_aforo = document.getElementById('porcentual_divisor_aforo').value;
	var fijo_valor_aforo = document.getElementById('fijo_valor_aforo').value;
	var tipo_base_minimo = document.getElementById('tipo_base_minimo').value;
	var tipo_calculo_minimo = document.getElementById('tipo_calculo_minimo').value;
	var minimo_porcentual_valor = document.getElementById('minimo_porcentual_valor').value;
	var minimo_porcentual_divisor = document.getElementById('minimo_porcentual_divisor').value;
	var minimo_fijo_valor = document.getElementById('minimo_fijo_valor').value;
	var idclasificador_presupuestario = document.getElementById('id_clasificador').value;
	var idtabla_constantes_recaudacion = document.getElementById('tabla_constantes').value;
	var monto_variable = document.getElementById('monto_variable').value;
	
	//if(anio == "" || desde == "" || hasta == "" || costo == ""){

	//}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				mostrarMensajes("exito", "El concepto se ingreso con Exito");
				document.getElementById('idconcepto_tributario').value = ajax.responseText;
				document.getElementById('tabla_mora').style.display = 'block';
				listarUnidades();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idtabla_constantes_recaudacion="+idtabla_constantes_recaudacion+"&monto_variable="+monto_variable+"&codigo="+codigo+"&anio="+anio+"&denominacion="+denominacion+"&tipo_base_aforo="+tipo_base_aforo+"&tipo_calculo_aforo="+tipo_calculo_aforo+"&porcentual_valor_aforo="+porcentual_valor_aforo+"&porcentual_divisor_aforo="+porcentual_divisor_aforo+"&fijo_valor_aforo="+fijo_valor_aforo+"&tipo_base_minimo="+tipo_base_minimo+"&tipo_calculo_minimo="+tipo_calculo_minimo+"&minimo_porcentual_valor="+minimo_porcentual_valor+"&minimo_porcentual_divisor="+minimo_porcentual_divisor+"&minimo_fijo_valor="+minimo_fijo_valor+"&idclasificador_presupuestario="+idclasificador_presupuestario+"&ejecutar=ingresarConcepto");
	//}
}


function listarUnidades(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('listaUnidades').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("ejecutar=listarUnidades");
}



function seleccionarUnidad(idconcepto_tributario, codigo, anio, denominacion, tipo_base_aforo, tipo_calculo_aforo, valor_aforo, porcentual_divisor_aforo, tipo_base_minimo, tipo_calculo_minimo, valor_minimo, minimo_porcentual_divisor, idclasificador_presupuestario, nombre_clasificador, idtabla_constantes_recaudacion, monto_variable){
	
	
	if(idtabla_constantes_recaudacion != '0'){
		document.getElementById('tabla_tabla_constantes').style.display = 'block';
		document.getElementById('tabla_valores_propios').style.display = 'none';
	}else{
		document.getElementById('tabla_tabla_constantes').style.display = 'none';
		document.getElementById('tabla_valores_propios').style.display = 'block';
	}
	
	
	document.getElementById('tabla_constantes').value = idtabla_constantes_recaudacion;
	if(tipo_calculo_aforo == "fijo"){
		document.getElementById('porcentual_valor_aforo').value = "";
		document.getElementById('porcentual_divisor_aforo').value = "";
		document.getElementById('fijo_valor_aforo').value = valor_aforo;
		document.getElementById('tabla_porcentual_aforo').style.display = 'none';
		document.getElementById('tabla_fijo_aforo').style.display = 'block';
	}else{
		document.getElementById('tabla_porcentual_aforo').style.display = 'block';
		document.getElementById('tabla_fijo_aforo').style.display = 'none';
		document.getElementById('porcentual_valor_aforo').value = valor_aforo;
		document.getElementById('porcentual_divisor_aforo').value = porcentual_divisor_aforo;	
		document.getElementById('fijo_valor_aforo').value = "";
	}
	
	
	if(tipo_calculo_minimo == "fijo"){
		document.getElementById('tabla_porcentual_minimo').style.display = 'none';
		document.getElementById('tabla_fijo_minimo').style.display = 'block';
		document.getElementById('porcentual_valor_aforo').value = "";
		document.getElementById('porcentual_divisor_aforo').value = "";
		document.getElementById('minimo_fijo_valor').value = valor_minimo;
		
	}else{
		document.getElementById('tabla_porcentual_minimo').style.display = 'block';
		document.getElementById('tabla_fijo_minimo').style.display = 'none';
		document.getElementById('minimo_porcentual_valor').value = valor_minimo;
		document.getElementById('minimo_porcentual_divisor').value = minimo_porcentual_divisor;	
		document.getElementById('minimo_fijo_valor').value = "";
	}
	
	document.getElementById("idconcepto_tributario").value = idconcepto_tributario;
	document.getElementById('codigo').value = codigo;
	document.getElementById('anio').value = anio;
	document.getElementById('denominacion').value = denominacion;
	
	
	document.getElementById('tipo_base_aforo').value = tipo_base_aforo;
	if(tipo_base_aforo == "unidad_tributaria"){
		document.getElementById('div_monto_variable').style.display='none';	
	}else if(tipo_base_aforo == "monto_variable"){
		document.getElementById('div_monto_variable').style.display='block';	
	}else{
		document.getElementById('div_monto_variable').style.display='none';		
	}
	
	document.getElementById('tipo_calculo_aforo').value = tipo_calculo_aforo;
	document.getElementById('monto_variable').value = monto_variable;
	document.getElementById('tipo_base_minimo').value = tipo_base_minimo;
	document.getElementById('tipo_calculo_minimo').value = tipo_calculo_minimo;
	


	
	document.getElementById('id_clasificador').value = idclasificador_presupuestario;
	document.getElementById('clasificador').value = nombre_clasificador;
	
	
	
	document.getElementById('tabla_mora').style.display = 'block';
	mostrarListaMoras();
	
	
	
}


function modificarConcepto(){
	var idconcepto_tributario = document.getElementById("idconcepto_tributario").value;
	var codigo = document.getElementById('codigo').value;
	var anio = document.getElementById('anio').value;
	var denominacion = document.getElementById('denominacion').value;
	var tipo_base_aforo = document.getElementById('tipo_base_aforo').value;
	var tipo_calculo_aforo = document.getElementById('tipo_calculo_aforo').value;
	var porcentual_valor_aforo = document.getElementById('porcentual_valor_aforo').value;
	var porcentual_divisor_aforo = document.getElementById('porcentual_divisor_aforo').value;
	var fijo_valor_aforo = document.getElementById('fijo_valor_aforo').value;
	var tipo_base_minimo = document.getElementById('tipo_base_minimo').value;
	var tipo_calculo_minimo = document.getElementById('tipo_calculo_minimo').value;
	var minimo_porcentual_valor = document.getElementById('minimo_porcentual_valor').value;
	var minimo_porcentual_divisor = document.getElementById('minimo_porcentual_divisor').value;
	var minimo_fijo_valor = document.getElementById('minimo_fijo_valor').value;
	var idclasificador_presupuestario = document.getElementById('id_clasificador').value;
	var idtabla_constantes_recaudacion = document.getElementById('tabla_constantes').value;
	var monto_variable = document.getElementById('monto_variable').value;
	
	//if(anio == "" || desde == "" || hasta == "" || costo == ""){
	//}else{
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "El concepto se modifico con exito");
			listarUnidades();
			limpiarCampos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("monto_variable="+monto_variable+"&idtabla_constantes_recaudacion="+idtabla_constantes_recaudacion+"&idconcepto_tributario="+idconcepto_tributario+"&codigo="+codigo+"&anio="+anio+"&denominacion="+denominacion+"&tipo_base_aforo="+tipo_base_aforo+"&tipo_calculo_aforo="+tipo_calculo_aforo+"&porcentual_valor_aforo="+porcentual_valor_aforo+"&porcentual_divisor_aforo="+porcentual_divisor_aforo+"&fijo_valor_aforo="+fijo_valor_aforo+"&tipo_base_minimo="+tipo_base_minimo+"&tipo_calculo_minimo="+tipo_calculo_minimo+"&minimo_porcentual_valor="+minimo_porcentual_valor+"&minimo_porcentual_divisor="+minimo_porcentual_divisor+"&minimo_fijo_valor="+minimo_fijo_valor+"&idclasificador_presupuestario="+idclasificador_presupuestario+"&ejecutar=modificarConcepto");	
	//}
}



function eliminarConcepto(){
	if(confirm("Seguro desea eliminar esta unidad tributaria?")){
	var idconcepto_tributario = document.getElementById("idconcepto_tributario").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "El concepto se elimino con exito");
			listarUnidades();
			limpiarCampos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idconcepto_tributario="+idconcepto_tributario+"&ejecutar=eliminarConcepto");	
	}
}



function limpiarCampos(){
	document.getElementById('codigo').value = "";
	document.getElementById('anio').value = "";
	document.getElementById('denominacion').value = "";
	document.getElementById('tipo_base_aforo').value = 0;
	document.getElementById('tipo_calculo_aforo').value = 0;
	document.getElementById('porcentual_valor_aforo').value = "";
	document.getElementById('porcentual_divisor_aforo').value = "";
	document.getElementById('fijo_valor_aforo').value = "";
	document.getElementById('tipo_base_minimo').value = 0;
	document.getElementById('tipo_calculo_minimo').value = 0;
	document.getElementById('minimo_porcentual_valor').value = "";
	document.getElementById('minimo_porcentual_divisor').value = "";
	document.getElementById('minimo_fijo_valor').value = "";
	document.getElementById('id_clasificador').value = "";
	document.getElementById('clasificador').value = "";
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
	document.getElementById('tabla_porcentual_aforo').style.display = 'none';
	document.getElementById('tabla_fijo_aforo').style.display = 'none';
	document.getElementById('tabla_porcentual_minimo').style.display = 'none';
	document.getElementById('tabla_fijo_minimo').style.display = 'none';
	document.getElementById('monto_variable').value = '';
	document.getElementById('div_monto_variable').style.display='none';
}






function ingresarMora(){
	var tipo_mora = document.getElementById('tipo_mora').value;
	var denominacion_mora = document.getElementById('denominacion_mora').value;
	var sobre_mora = document.getElementById('sobre_mora').value;
	var frecuencia_calculo_mora = document.getElementById('frecuencia_calculo_mora').value;
	var condicion_tipo_mora = document.getElementById('condicion_tipo_mora').value;
	var condicion_operador_mora = document.getElementById('condicion_operador_mora').value;
	var condicion_factor_mora = document.getElementById('condicion_factor_mora').value;
	var condicion_valor_mora = document.getElementById('condicion_valor_mora').value;
	var idconcepto_tributario = document.getElementById('idconcepto_tributario').value;
	var valor_calculo = document.getElementById('valor_calculo').value;
	var tipo_valor_mora = document.getElementById('tipo_valor_mora').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarListaMoras();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("tipo_valor_mora="+tipo_valor_mora+"&valor_calculo="+valor_calculo+"&idconcepto_tributario="+idconcepto_tributario+"&tipo_mora="+tipo_mora+"&denominacion_mora="+denominacion_mora+"&sobre_mora="+sobre_mora+"&frecuencia_calculo_mora="+frecuencia_calculo_mora+"&condicion_tipo_mora="+condicion_tipo_mora+"&condicion_operador_mora="+condicion_operador_mora+"&condicion_factor_mora="+condicion_factor_mora+"&condicion_valor_mora="+condicion_valor_mora+"&ejecutar=ingresarMora");	
}




function modificarMora(){
	var idmoras_conceptos_tributarios = document.getElementById('idmoras_conceptos_tributarios').value;
	var tipo_mora = document.getElementById('tipo_mora').value;
	var denominacion_mora = document.getElementById('denominacion_mora').value;
	var sobre_mora = document.getElementById('sobre_mora').value;
	var frecuencia_calculo_mora = document.getElementById('frecuencia_calculo_mora').value;
	var condicion_tipo_mora = document.getElementById('condicion_tipo_mora').value;
	var condicion_operador_mora = document.getElementById('condicion_operador_mora').value;
	var condicion_factor_mora = document.getElementById('condicion_factor_mora').value;
	var condicion_valor_mora = document.getElementById('condicion_valor_mora').value;
	var idconcepto_tributario = document.getElementById('idconcepto_tributario').value;
	var valor_calculo = document.getElementById('valor_calculo').value;
	var tipo_valor_mora = document.getElementById('tipo_valor_mora').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarListaMoras();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("tipo_valor_mora="+tipo_valor_mora+"&valor_calculo="+valor_calculo+"&idmoras_conceptos_tributarios="+idmoras_conceptos_tributarios+"&tipo_mora="+tipo_mora+"&denominacion_mora="+denominacion_mora+"&sobre_mora="+sobre_mora+"&frecuencia_calculo_mora="+frecuencia_calculo_mora+"&condicion_tipo_mora="+condicion_tipo_mora+"&condicion_operador_mora="+condicion_operador_mora+"&condicion_factor_mora="+condicion_factor_mora+"&condicion_valor_mora="+condicion_valor_mora+"&ejecutar=modificarMora");	
}






function eliminarMora(){
	if(confirm("Seguro desea eliminar esta mora?")){
	var idmoras_conceptos_tributarios = document.getElementById('idmoras_conceptos_tributarios').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarListaMoras();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idmoras_conceptos_tributarios="+idmoras_conceptos_tributarios+"&ejecutar=eliminarMora");	
	}
}






function mostrarListaMoras(){
	
	var idconcepto_tributario = document.getElementById('idconcepto_tributario').value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/concepto_tributario_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('celda_lista_moras').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idconcepto_tributario="+idconcepto_tributario+"&ejecutar=mostrarListaMoras");	
}




function seleccionarMora(idmoras_conceptos_tributarios, tipo_mora, denominacion, sobre, frecuencia_calculo, condicion_tipo, condicion_operador, condicion_factor, condicion_valor, tipo, valor_calculo){
	if(tipo == "modificar"){
		document.getElementById('boton_modificar_mora').style.display='block';
		document.getElementById('boton_eliminar_mora').style.display='none';
	}else{
		document.getElementById('boton_modificar_mora').style.display='none';
		document.getElementById('boton_eliminar_mora').style.display='block';
	}
	
	document.getElementById('idmoras_conceptos_tributarios').value = idmoras_conceptos_tributarios;
	document.getElementById('tipo_mora').value = tipo_mora;
	document.getElementById('denominacion_mora').value = denominacion;
	document.getElementById('sobre_mora').value = sobre;
	document.getElementById('frecuencia_calculo_mora').value = frecuencia_calculo;
	document.getElementById('condicion_tipo_mora').value = condicion_tipo;
	document.getElementById('condicion_operador_mora').value = condicion_operador;
	document.getElementById('condicion_factor_mora').value = condicion_factor;
	document.getElementById('condicion_valor_mora').value = condicion_valor;
	document.getElementById('valor_calculo').value = valor_calculo;

	
	
}