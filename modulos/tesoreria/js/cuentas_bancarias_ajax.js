// JavaScript Document



function cambiarCantidadNumeroInicial(cantidad){
	if(cantidad == ""){
		document.getElementById('numero_inicial').size = 2;
		document.getElementById('numero_inicial').setAttribute('maxlength', 2);
		document.getElementById('numero_final').setAttribute('maxlength', 2);
		document.getElementById('numero_final').size = 2;
	}else{
		document.getElementById('numero_inicial').size = 9-cantidad;
		document.getElementById('numero_inicial').setAttribute('maxlength', 8-cantidad);
		document.getElementById('numero_final').setAttribute('maxlength', 8-cantidad);
		document.getElementById('numero_final').size = 9-cantidad;	
	}
}









function ingresarCuentaBancaria(){
	var numero_cuenta = document.getElementById('nro_cuenta').value;
	var validez_documento = document.getElementById('validez_documento').value;
	var monto_apertura = document.getElementById('monto_apertura').value;
	var monto_libro = document.getElementById('monto_apertura_libro').value;
	var estado_cuenta = document.getElementById('estado_cuenta').value;
	var fecha_inicio_periodo = document.getElementById('fecha_inicio_periodo').value;
	var fecha_fin_periodo = document.getElementById('fecha_fin_periodo').value;
	var fecha_apertura = document.getElementById('fecha_apertura').value;
	var uso_cuenta = document.getElementById('uso_cuenta').value;
	var firma_autorizada = document.getElementById('firma_autorizada').value;
	var ci_firma_autorizada = document.getElementById('ci_firma_autorizada').value;
	var cargo_firma_autorizada = document.getElementById('cargo_firma_autorizada').value;
	var firma_autorizada_2 = document.getElementById('firma_autorizada_2').value;
	var ci_firma_autorizada_2 = document.getElementById('ci_firma_autorizada_2').value;
	var cargo_firma_autorizada_2 = document.getElementById('cargo_firma_autorizada_2').value;
	var firma_autorizada_3 = document.getElementById('firma_autorizada_3').value;
	var ci_firma_autorizada_3 = document.getElementById('ci_firma_autorizada_3').value;
	var cargo_firma_autorizada_3 = document.getElementById('cargo_firma_autorizada_3').value;
	var sub_cuenta_contable = document.getElementById('sub_cuenta').value;
	var tabla = document.getElementById('tabla').value;
	var idcuenta = document.getElementById('idcuenta').value;
	
	if(document.getElementById('conjuntas').checked == true){
		var conjuntas = 'si'; 	
	}else{
		var conjuntas = 'no';	
	}
	
	var banco = document.getElementById('banco').value;
	var tipo_cuenta = document.getElementById('tipo_cuenta').value;

	
	if(numero_cuenta == ""){	
		alert("Debe Ingresar el Numero de Cuenta");
		document.getElementById('nro_cuenta').focus();
	}else if(isNaN(numero_cuenta)){
		alert("Disculpe el Numero de Cuenta solo debe contener digitos numericos");
		document.getElementById('nro_cuenta').select();
	}else if(numero_cuenta.length < 20){
		alert("Disculpe el numero de cuenta no puede ser menor a 20 digitos");
		document.getElementById('nro_cuenta').select();
	}else if(banco == 0){
		alert("Debe seleccionar una Entidad Bancaria");
		document.getElementById('banco').focus();
	}else if(tipo_cuenta == 0){
		alert("Debe seleccionar el Tipo de Cuenta");
		document.getElementById('tipo_cuenta').focus();
	}else if(estado_cuenta == 0){
		alert("Debe seleccionar el Estado de la Cuenta");
		document.getElementById('estado_cuenta').focus();
	}else{
	
	
	
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				//alert(ajax.responseText);
				if (ajax.readyState==4){
					if(ajax.responseText.indexOf("exito") != -1){

						var partes = ajax.responseText.split("-");
						document.getElementById('id_cuenta_bancaria').value = partes[1];
						document.getElementById('tablaChequeras').style.display='block';
						document.getElementById('ingresar_cuenta').style.display = 'none';
						document.getElementById('modificar_cuenta').style.display = 'block';
						document.getElementById('eliminar_cuenta').style.display = 'block';
					}else if(ajax.responseText.indexOf("existe") != -1){
						alert("Disculpe ese numero de cuenta ya existe");
					}
					document.getElementById("divCargando").style.display = "none";
				} 
			}
			ajax.send("numero_cuenta="+numero_cuenta+"&validez_documento="+validez_documento+"&fecha_inicio_periodo="+fecha_inicio_periodo
						+"&fecha_fin_periodo="+fecha_fin_periodo+"&fecha_apertura="+fecha_apertura+"&uso_cuenta="+uso_cuenta
						+"&firma_autorizada="+firma_autorizada+"&firma_autorizada_2="+firma_autorizada_2+"&firma_autorizada_3="+firma_autorizada_3
						+"&ci_firma_autorizada="+ci_firma_autorizada+"&ci_firma_autorizada_2="+ci_firma_autorizada_2
						+"&ci_firma_autorizada_3="+ci_firma_autorizada_3+"&cargo_firma_autorizada="+cargo_firma_autorizada
						+"&cargo_firma_autorizada_2="+cargo_firma_autorizada_2+"&cargo_firma_autorizada_3="+cargo_firma_autorizada_3
						+"&conjuntas="+conjuntas+"&banco="+banco+"&tipo_cuenta="+tipo_cuenta+"&estado_cuenta="+estado_cuenta
						+"&monto_apertura="+monto_apertura+"&estado_cuenta="+estado_cuenta+"&tabla="+tabla+"&idcuenta="+idcuenta
						+"&sub_cuenta_contable="+sub_cuenta_contable+"&monto_apertura_libro="+monto_apertura_libro+"&ejecutar=ingresarCuentaBancaria");
	}
	//document.getElementById('nro_cuenta').focus();
}







function consultarCuentaBancaria(idcuentas_bancarias){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			var partes = ajax.responseText.split("|.|");
			
				document.getElementById('id_cuenta_bancaria').value = idcuentas_bancarias;
				document.getElementById('nro_cuenta').value = partes[0];
				document.getElementById('banco').value = partes[1];
				document.getElementById('tipo_cuenta').value = partes[2];
				document.getElementById('validez_documento').value = partes[3];
				document.getElementById('fecha_inicio_periodo').value = partes[4];
				document.getElementById('fecha_fin_periodo').value = partes[5];				
				document.getElementById('fecha_apertura').value = partes[6];
				document.getElementById('monto_apertura').value = partes[7];
				document.getElementById('uso_cuenta').value = partes[8];
				document.getElementById('estado_cuenta').value = partes[9];
				document.getElementById('firma_autorizada').value = partes[10];
				document.getElementById('ci_firma_autorizada').value  = partes[11];
				document.getElementById('cargo_firma_autorizada').value = partes[12];
				document.getElementById('firma_autorizada_2').value = partes[13];
				document.getElementById('ci_firma_autorizada_2').value = partes[14];
				document.getElementById('cargo_firma_autorizada_2').value = partes[15];
				document.getElementById('firma_autorizada_3').value = partes[16];
				document.getElementById('ci_firma_autorizada_3').value = partes[17];
				document.getElementById('cargo_firma_autorizada_3').value = partes[18];

				//alert(partes[19]);
				if(partes[19] == "s"){
					document.getElementById('conjuntas').checked = true;
				}
				
				if(partes[20] == "no"){
					document.getElementById('nro_cuenta').disabled = true;
				}else{
					document.getElementById('nro_cuenta').disabled = false;	
				}
				if (partes[21] != 0){
					document.getElementById('cuenta_deudora').disabled = true;
				}
				document.getElementById('idcuenta').value = partes[21];
				document.getElementById('idcuenta_modificar').value = partes[21];
				document.getElementById('tabla').value = partes[22];
				document.getElementById('sub_cuenta').value = partes[23];
				document.getElementById('denominacion_banco').value = partes[24];
				document.getElementById('monto_apertura_libro').value = partes[25];
				//alert(partes[24]);
				document.getElementById('tablaChequeras').style.display = 'block';
				listarChequeras(idcuentas_bancarias);
				document.getElementById('ingresar_cuenta').style.display = 'none';
				document.getElementById('modificar_cuenta').style.display = 'block';
				document.getElementById('eliminar_cuenta').style.display = 'block';
				
				
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_bancarias="+idcuentas_bancarias+"&ejecutar=consultarCuentaBancaria");	
}







function listarChequeras(idcuentas_bancarias){
		var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById('listaChequeras').style.display ='block'
			document.getElementById('listaChequeras').innerHTML = ajax.responseText;			
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idcuentas_bancarias="+idcuentas_bancarias+"&ejecutar=listarChequeras");	
}







function ingresarChequera(){
	var numero_chequera =  document.getElementById('numero_chequera').value;
	var cantidad_cheques =  document.getElementById('cantidad_cheques').value;
	var numero_inicial =  document.getElementById('numero_inicial').value;
	var numero_final =  document.getElementById('numero_final').value;
	var fecha_inicial_uso =  document.getElementById('fecha_inicial_uso').value;
	var fecha_final_uso =  document.getElementById('fecha_final_uso').value;
	var estado =  document.getElementById('estado').value;
	var idcuentas_bancarias =  document.getElementById('id_cuenta_bancaria').value;
	if(document.getElementById('digitos_consecutivos_inicio').checked == true){
		var digitos_consecutivos =  document.getElementById('digitos_consecutivos_inicio').value;	
	}else{
		var digitos_consecutivos =  document.getElementById('digitos_consecutivos_fin').value;	
	}
	
	var cantidad_digitos =  document.getElementById('cantidad_digitos').value;
	
	
	
	if(numero_chequera == ""){
		alert("Debe ingrear el numero de la chequera");
		document.getElementById('numero_chequera').focus();
	}else if (isNaN(numero_chequera)){
		alert("Disculp el campo Numero de Chequera solo Acepta numeros");
		document.getElementById('numero_chequera').select();
	}else if(cantidad_cheques == 0 || cantidad_cheques == ""){
		alert("Debe ingresar la Cantidad de Cheques");
		document.getElementById('cantidad_cheques').select();
	}else if(numero_inicial == 0 || numero_inicial == ""){
		alert("Debe ingresar el Numero Inicial de la Chequera");
		document.getElementById('numero_inicial').select();
	}else if(estado == 0){
		alert("Debe seleccionar el Estado de la Chequera");
		document.getElementById('estado').focus();
	}else{
	
	
	
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText.indexOf("exito") != -1){
					listarChequeras(idcuentas_bancarias);	
				}else if(ajax.responseText.indexOf("existe") != -1){
					alert("Disculpe esta chequera ya fue ingresada");
				}
					document.getElementById('numero_chequera').value = '';
					document.getElementById('cantidad_cheques').value = '';
					document.getElementById('numero_inicial').value = '';
					document.getElementById('numero_final').value = '';
					document.getElementById('fecha_inicial_uso').value = '';
					document.getElementById('fecha_final_uso').value = '';
					document.getElementById('digitos_consecutivos_inicio').checked = true;
					document.getElementById('digitos_consecutivos_fin').checked = false;
					document.getElementById('cantidad_digitos').value = '';
					document.getElementById('estado').value = 0;
					consultarCuentaBancaria(idcuentas_bancarias);
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("numero_chequera="+numero_chequera+"&cantidad_cheques="+cantidad_cheques+"&numero_inicial="+numero_inicial+"&numero_final="+numero_final+"&fecha_inicial_uso="+fecha_inicial_uso+"&fecha_final_uso="+fecha_final_uso+"&estado="+estado+"&idcuentas_bancarias="+idcuentas_bancarias+"&digitos_consecutivos="+digitos_consecutivos+"&cantidad_digitos="+cantidad_digitos+"&ejecutar=ingresarChequera");
	}
}






function sumarCheques(){
	var cantidad_mostrar = document.getElementById('cantidad_digitos').value;
	if(document.getElementById('cantidad_cheques').value == ""){
		document.getElementById('cantidad_cheques').value = 0;
	}
	if(document.getElementById('numero_inicial').value == ""){
		document.getElementById('numero_inicial').value = 0;
	}
	
	
	var suma = (parseInt(document.getElementById('cantidad_cheques').value)+parseInt(document.getElementById('numero_inicial').value))-1;
	var sumaString = suma.toString();
	//alert(cantidad_mostrar);
	if(sumaString.length > (8-cantidad_mostrar)){
		var ajax=nuevoAjax();
			ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
				if (ajax.readyState==4){
					//alert(ajax.responseText);
					document.getElementById('numero_final').value = ajax.responseText;
				} 
			}
			ajax.send("suma="+suma+"&cantidad="+cantidad_mostrar+"&ejecutar=sumaCheques");
		document.getElementById("divCargando").style.display = "none";
	}else{
		document.getElementById('numero_final').value = parseFloat(sumaString);	
	}
	
}





function modificarCuentaBancaria(){
	var idcuentas_bancarias = document.getElementById('id_cuenta_bancaria').value;
	var numero_cuenta = document.getElementById('nro_cuenta').value;
	var validez_documento = document.getElementById('validez_documento').value;
	var monto_apertura = document.getElementById('monto_apertura').value;
	var monto_apertura_libro = document.getElementById('monto_apertura_libro').value;
	var estado_cuenta = document.getElementById('estado_cuenta').value;
	var fecha_inicio_periodo = document.getElementById('fecha_inicio_periodo').value;
	var fecha_fin_periodo = document.getElementById('fecha_fin_periodo').value;
	var fecha_apertura = document.getElementById('fecha_apertura').value;
	var uso_cuenta = document.getElementById('uso_cuenta').value;
	var firma_autorizada = document.getElementById('firma_autorizada').value;
	var ci_firma_autorizada = document.getElementById('ci_firma_autorizada').value;
	var cargo_firma_autorizada = document.getElementById('cargo_firma_autorizada').value;
	var firma_autorizada_2 = document.getElementById('firma_autorizada_2').value;
	var ci_firma_autorizada_2 = document.getElementById('ci_firma_autorizada_2').value;
	var cargo_firma_autorizada_2 = document.getElementById('cargo_firma_autorizada_2').value;
	var firma_autorizada_3 = document.getElementById('firma_autorizada_3').value;
	var ci_firma_autorizada_3 = document.getElementById('ci_firma_autorizada_3').value;
	var cargo_firma_autorizada_3 = document.getElementById('cargo_firma_autorizada_3').value;
	
	var sub_cuenta_contable = document.getElementById('sub_cuenta').value;
	var tabla = document.getElementById('tabla').value;
	var idcuenta = document.getElementById('idcuenta').value;
	var idcuenta_modificar = document.getElementById('idcuenta_modificar').value;
	
	
	
	if(document.getElementById('conjuntas').checked == true){
		var conjuntas = 'si'; 	
	}else{
		var conjuntas = 'no';	
	}
	
	var banco = document.getElementById('banco').value;
	var tipo_cuenta = document.getElementById('tipo_cuenta').value;


	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			if(ajax.responseText.indexOf("exito") != -1){
				var partes = ajax.responseText.split("-");
				document.getElementById('id_cuenta_bancaria').value = partes[1];
				document.getElementById('tablaChequeras').style.display='block';	
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_cuenta="+numero_cuenta+"&validez_documento="+validez_documento+"&fecha_inicio_periodo="+fecha_inicio_periodo
		+"&fecha_fin_periodo="+fecha_fin_periodo+"&fecha_apertura="+fecha_apertura+"&uso_cuenta="+uso_cuenta+"&firma_autorizada="+firma_autorizada
		+"&firma_autorizada_2="+firma_autorizada_2+"&firma_autorizada_3="+firma_autorizada_3+"&ci_firma_autorizada="+ci_firma_autorizada
		+"&ci_firma_autorizada_2="+ci_firma_autorizada_2+"&ci_firma_autorizada_3="+ci_firma_autorizada_3
		+"&cargo_firma_autorizada="+cargo_firma_autorizada+"&cargo_firma_autorizada_2="+cargo_firma_autorizada_2
		+"&cargo_firma_autorizada_3="+cargo_firma_autorizada_3+"&conjuntas="+conjuntas+"&banco="+banco+"&tipo_cuenta="+tipo_cuenta
		+"&estado_cuenta="+estado_cuenta+"&monto_apertura="+monto_apertura+"&estado_cuenta="+estado_cuenta+"&idcuentas_bancarias="+idcuentas_bancarias
		+"&sub_cuenta_contable="+sub_cuenta_contable+"&tabla="+tabla+"&idcuenta="+idcuenta+"&idcuenta_modificar="+idcuenta_modificar
		+"&monto_apertura_libro="+monto_apertura_libro+"&ejecutar=modificarCuentaBancaria");
	//document.getElementById('nro_cuenta').focus();		
}







function eliminarCuentaBancaria(){
	if(confirm("Realmente desea Eliminar esta cuenta Bancaria?")){
		var idcuentas_bancarias = document.getElementById('id_cuenta_bancaria').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText.indexOf("tieneCheques") != -1){
					alert("Disculpe esta cuanta no se puede eliminar porque ya posee chequeras en uso");
				}else{
					window.location.href = 'principal.php?modulo7&accion=416';		
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idcuentas_bancarias="+idcuentas_bancarias+"&ejecutar=eliminarCuentaBancaria");	
	}
}







function seleccionarModificarChequeras(numero_cheque, cantidad_cheque, numero_inicial, numero_final, fecha_inicial, fecha_final, estado, idchequeras, digitos_consecutivos, cantidad_digitos){
		//alert(digitos_consecutivos);
		document.getElementById('idchequeras').value = idchequeras;
		document.getElementById('numero_chequera').value = numero_cheque;
		document.getElementById('cantidad_cheques').value = cantidad_cheque;
		if (digitos_consecutivos == "inicio"){
			document.getElementById('digitos_consecutivos_inicio').checked = true;
			document.getElementById('digitos_consecutivos_fin').checked = false;
		}else{
			document.getElementById('digitos_consecutivos_fin').checked = true;
			document.getElementById('digitos_consecutivos_inicio').checked = false;
		}
		document.getElementById('cantidad_digitos').value = cantidad_digitos;
		document.getElementById('numero_inicial').value = numero_inicial;
		document.getElementById('numero_final').value = numero_final;
		document.getElementById('fecha_inicial_uso').value = fecha_inicial;
		document.getElementById('fecha_final_uso').value = fecha_final;
		document.getElementById('estado').value = estado;
			if(fecha_inicial != ""){
					document.getElementById('numero_chequera').disabled = true;
			}
		document.getElementById('ingresar_chequera').style.display = 'none';
		document.getElementById('modificar_chequeras').style.display = 'block';
}







function modificarChequeras(){
	var numero_chequera =  document.getElementById('numero_chequera').value;
	var cantidad_cheques =  document.getElementById('cantidad_cheques').value;
	var numero_inicial =  document.getElementById('numero_inicial').value;
	var numero_final =  document.getElementById('numero_final').value;
	var fecha_inicial_uso =  document.getElementById('fecha_inicial_uso').value;
	var fecha_final_uso =  document.getElementById('fecha_final_uso').value;
	var estado =  document.getElementById('estado').value;
	var idcuentas_bancarias =  document.getElementById('id_cuenta_bancaria').value;
	var idchequeras = document.getElementById('idchequeras').value;
	if(document.getElementById('digitos_consecutivos_inicio').checked == true){
		var digitos_consecutivos = "inicio";	
	}else{
		var digitos_consecutivos = "fin";		
	}
	var cantidad_digitos = document.getElementById('cantidad_digitos').value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			if(ajax.responseText.indexOf("exito") != -1){
				listarChequeras(idcuentas_bancarias);	
			}
				document.getElementById('numero_chequera').value = '';
				document.getElementById('cantidad_cheques').value = '';
				document.getElementById('numero_inicial').value = '';
				document.getElementById('numero_final').value = '';
				document.getElementById('fecha_inicial_uso').value = '';
				document.getElementById('fecha_final_uso').value = '';
				document.getElementById('estado').value = 0;
				document.getElementById('digitos_consecutivos_inicio').checked = true;
				document.getElementById('digitos_consecutivos_fin').checked = false;
				document.getElementById('cantidad_digitos').value = '';
				listarChequeras(idcuentas_bancarias);
				document.getElementById('ingresar_chequera').style.display = 'block';
				document.getElementById('modificar_chequeras').style.display = 'none';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("numero_chequera="+numero_chequera+"&cantidad_cheques="+cantidad_cheques+"&numero_inicial="+numero_inicial+"&numero_final="+numero_final+"&fecha_inicial_uso="+fecha_inicial_uso+"&fecha_final_uso="+fecha_final_uso+"&estado="+estado+"&idcuentas_bancarias="+idcuentas_bancarias+"&idchequeras="+idchequeras+"&cantidad_digitos="+cantidad_digitos+"&digitos_consecutivos="+digitos_consecutivos+"&ejecutar=modificarChequeras");		
}







function eliminarChequeras(idchequera){
	if(confirm("Realmente desea Eliminar esta Chequera?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/tesoreria/lib/cuentas_bancarias_ajax.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText.indexOf("exito") != -1){
					listarChequeras(document.getElementById('id_cuenta_bancaria').value);
				}
				
			} 
		}
		ajax.send("idchequeras="+idchequera+"&ejecutar=eliminarChequeras");
	}
}






function limpiarFormularioChequera(){
	document.getElementById('numero_chequera').value = '';
	document.getElementById('cantidad_cheques').value = 0;
	document.getElementById('numero_inicial').value = 0;
	document.getElementById('numero_final').value = '';
	document.getElementById('fecha_inicial_uso').value = '';
	document.getElementById('fecha_final_uso').value = '';
	document.getElementById('digitos_consecutivos_inicio').checked = true;
	document.getElementById('digitos_consecutivos_fin').checked = false;
	document.getElementById('cantidad_digitos').value = '';
	document.getElementById('estado').value = 0;
	listarChequeras(document.getElementById('id_cuenta_bancaria').value);	
	document.getElementById('ingresar_chequera').style.display = 'block';
	document.getElementById('modificar_chequeras').style.display = 'none';
}