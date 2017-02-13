// JavaScript Document

function validarPeriodo(periodo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//document.getElementById('periodo').style.background = "#fec092";
			var sms = ajax.responseText;
			
			if(sms == 0){
				document.getElementById('periodo').style = "#fec092";
				}else{
					if(sms == 1){
						document.getElementById('periodo').style.background = "#ffffff";
						}else{
							document.getElementById('periodo').style.background = "#fec092";							
							}
					}
		} 
	}
	ajax.send("periodo="+periodo+"&ejecutar=validarPeriodo");		
}

function validarFechasDisfrute(fecha_inicio,feha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('oculto_disfrutados').value = ajax.responseText;
			document.getElementById('oculto_dias').value = ajax.responseText;
			
			var tiempo_disfrute = document.getElementById('tiempo_disfrute').value;
			var dias_disfrutados = document.getElementById('oculto_disfrutados').value;
			obtener_disfrutes_completos(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+feha_culminacion+"&ejecutar=validarFechas");		
}


function validarFechas(fecha_inicio,feha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_disfrute').value = ajax.responseText;
			var tiempo_disfrute = document.getElementById('tiempo_disfrute').value;
			var dias_disfrutados = document.getElementById('oculto_disfrutados').value;
			obtener_disfrutes_completos(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+feha_culminacion+"&ejecutar=validarFechas");		
}

function reinicioAjustado(reinicio_ajustado,fecha_reincorporacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			if(sms == 0){
				document.getElementById('tiempo_pendiente_disfrute').value = sms;
				}else{
					var desde = document.getElementById('fecha_inicio_disfrute').value;
					var hasta = document.getElementById('fecha_reincorporacion').value;
					validarFechasDisfrute(desde,hasta);
					}
		} 
	}
	ajax.send("reinicio_ajustado="+reinicio_ajustado+"&fecha_reincorporacion="+fecha_reincorporacion+"&ejecutar=reinicioAjustado");		
}

function restar_feriados(cant_feriados){
	var ajax=nuevoAjax();
	var dias_disfrute = document.getElementById('oculto_dias').value;
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){	
			document.getElementById('oculto_disfrutados').value = ajax.responseText;
			
			var tiempo_disfrute = document.getElementById('tiempo_disfrute').value;
			var dias_disfrutados = document.getElementById('oculto_disfrutados').value;
			obtener_disfrutes_completos(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("catidad_feriados="+cant_feriados+"&dias_disfrute="+dias_disfrute+"&ejecutar=cant_feriados");		
}

function obtener_disfrutes_completos(tiempo_disfrute,tiempo_disfrutado){
	var ajax=nuevoAjax();
	var dias_disfrute = document.getElementById('oculto_dias').value;
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){	
			document.getElementById('tiempo_pendiente_disfrute').value = ajax.responseText;
		} 
	}
	ajax.send("tiempo_disfrute="+tiempo_disfrute+"&tiempo_disfrutado="+tiempo_disfrutado+"&ejecutar=obten_disfrute_completo");		
}

function llenargrilla(id_trabajador){
	//alert(id_trabajador);
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById('grilla').innerHTML = ajax.responseText;
			document.getElementById('accion').value = "Guardar";
		} 
	}
	ajax.send("id_trabajador="+id_trabajador+"&ejecutar=llenargrilla");		
}

function llenaroculto(valor){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
					
			//document.getElementById('grilla').innerHTML = ajax.responseText;			
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=llenaroculto");		
}
function accionRegistro(accion){
	var ajax=nuevoAjax();
	var id_trabajador = document.getElementById('id_trabajador').value;
	var periodo = document.getElementById('periodo').value;
	var numero_memorandum = document.getElementById('numero_memorandum').value;
	var fecha_memorandum = document.getElementById('fecha_memorandum').value;
	var fecha_inicio_vacacion = document.getElementById('fecha_inicio_vacacion').value;
	var fecha_culminacion_vacacion = document.getElementById('fecha_culminacion_vacacion').value;
	var tiempo_disfrute = document.getElementById('tiempo_disfrute').value;
	var fecha_inicio_disfrute = document.getElementById('fecha_inicio_disfrute').value;
	var fecha_reincorporacion = document.getElementById('fecha_reincorporacion').value;
	var fecha_reincorporacion_ajustada = document.getElementById('fecha_reincorporacion_ajustada').value;
	var tiempo_pendiente_disfrute = document.getElementById('tiempo_pendiente_disfrute').value;
	var dias_bonificacion = document.getElementById('dias_bonificacion').value;
	
	var oculto_dias = document.getElementById('oculto_dias').value;
	var oculto_disfrutados = document.getElementById('oculto_disfrutados').value;
	
	var cantidad_feriados = document.getElementById('cantidad_feriados').value;
	var monto_bono_vacacional = document.getElementById('monto_bono_vacacional').value;	
	
	var numero_orden_pago = document.getElementById('numero_orden_pago').value;
	var fecha_orden_pago = document.getElementById('fecha_orden_pago').value;
	var elaborado_por = document.getElementById('elaborado_por').value;
	var ci_elaborado = document.getElementById('ci_elaborado').value;
	var aprobado_por = document.getElementById('aprobado_por').value;
	var ci_aprobado = document.getElementById('ci_aprobado').value;
	var idhistorico_vacaciones = document.getElementById('idhistorico_vacaciones').value;
	
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			
			if(sms == 0){
				mostrarMensajes("error", "Seleccione Trabajador");
				}else{
					if(sms == 1){
						mostrarMensajes("error", "Campos vacios");
						}else{
							if(sms == 2){
								mostrarMensajes("exito", "Registro Exitoso");
								llenargrilla(id_trabajador);
								limpiarCampos();
								}else{
									mostrarMensajes("error", "Error Agregar Historico Vacacional: "+ajax.responseText);
									}
							}
					}
			//boton(ajax.responseText);
			//document.getElementById('grilla').innerHTML = ajax.responseText;
			
		} 
	}
	ajax.send("id_trabajador="+id_trabajador+"&oculto_disfrutados="+oculto_disfrutados+"&oculto_dias="+oculto_dias+"&cantidad_feriados="+cantidad_feriados+"&idhistorico_vacaciones="+idhistorico_vacaciones+"&ci_aprobado="+ci_aprobado+"&aprobado_por="+aprobado_por+"&ci_elaborado="+ci_elaborado+"&elaborado_por="+elaborado_por+"&fecha_orden_pago="+fecha_orden_pago+"&numero_orden_pago="+numero_orden_pago+"&monto_bono_vacacional="+monto_bono_vacacional+"&dias_bonificacion="+dias_bonificacion+"&tiempo_pendiente_disfrute="+tiempo_pendiente_disfrute+"&fecha_reincorporacion_ajustada="+fecha_reincorporacion_ajustada+"&fecha_reincorporacion="+fecha_reincorporacion+"&numero_memorandum="+numero_memorandum+"&fecha_memorandum="+fecha_memorandum+"&fecha_inicio_vacacion="+fecha_inicio_vacacion+"&fecha_culminacion_vacacion="+fecha_culminacion_vacacion+"&fecha_inicio_disfrute="+fecha_inicio_disfrute+"&tiempo_disfrute="+tiempo_disfrute+"&periodo="+periodo+"&accion="+accion+"&ejecutar=accion");		
}

function limpiarCampos(){
	document.getElementById('periodo').style.background = "#ffffff";
	document.getElementById('periodo').value="";
	document.getElementById('numero_memorandum').value="";
	document.getElementById('fecha_memorandum').value="";
	document.getElementById('fecha_inicio_vacacion').value="";
	document.getElementById('fecha_culminacion_vacacion').value="";
	document.getElementById('tiempo_disfrute').value="";
	document.getElementById('fecha_inicio_disfrute').value="";
	document.getElementById('fecha_reincorporacion').value="";
	document.getElementById('fecha_reincorporacion_ajustada').value="";
	document.getElementById('fecha_culminacion_vacacion').value="";
	document.getElementById('tiempo_pendiente_disfrute').value="";
	document.getElementById('dias_bonificacion').value="";
	document.getElementById('monto_bono_vacacional').value="";
	document.getElementById('numero_orden_pago').value="";
	document.getElementById('fecha_orden_pago').value="";
	document.getElementById('elaborado_por').value="";
	document.getElementById('ci_elaborado').value="";
	document.getElementById('aprobado_por').value="";
	document.getElementById('ci_aprobado').value="";
	document.getElementById('cantidad_feriados').value = "";
	document.getElementById('oculto_dias').value = "";
	document.getElementById('oculto_disfrutados').value = "";
}

function llenarFormulario(id_historico_vacacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_vacaciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('datos').innerHTML = ajax.responseText;
			// creacion de variables por id
			var idhistorico_vacaciones = document.getElementById('idhistorico_vacaciones_encontrado').value;
			//var idtrabajador = document.getElementById('idtrabajador_encontrado').value;
			var periodo = document.getElementById('periodo_encontrado').value;
			var numero_memorandum = document.getElementById('numero_memorandum_encontrado').value;
			var fecha_memorandum = document.getElementById('fecha_memorandum_encontrado').value;
			var fecha_inicio_vacacion = document.getElementById('fecha_inicio_vacacion_encontrado').value;
			var fecha_culminacion_vacacion = document.getElementById('fecha_culminacion_vacacion_encontrado').value;
			var tiempo_disfrute = document.getElementById('tiempo_disfrute_encontrado').value;
			var fecha_inicio_disfrute = document.getElementById('fecha_inicio_disfrute_encontrado').value;
			var fecha_reincorporacion = document.getElementById('fecha_reincorporacion_encontrado').value;
			var fecha_reincorporacion_ajustada = document.getElementById('fecha_reincorporacion_ajustada_encontrado').value;
			var dias_pendiente_disfrute = document.getElementById('dias_pendiente_disfrute_encontrado').value;
			var dias_bono = document.getElementById('dias_bono_encontrado').value;
			var monto_bonos = document.getElementById('monto_bonos_encontrado').value;
			var numero_orden_pago = document.getElementById('numero_orden_pago_encontrado').value;
			var fecha_cancelacion = document.getElementById('fecha_cancelacion_encontrado').value;
			var elaborado_por = document.getElementById('elaborado_por_encontrado').value;
			var ci_elaborado_por = document.getElementById('ci_elaborado_por_encontrado').value;
			var aprobada_por = document.getElementById('aprobada_por_encontrado').value;
			var ci_aprobado = document.getElementById('ci_aprobado_encontrado').value;
			
			var cantidad_feriados = document.getElementById('cantidad_feriadostabla').value;
			
			var oculto_dias = document.getElementById('oculto_dias_encontrado').value;
			var oculto_disfrutados = document.getElementById('oculto_disfrutados_encontrado').value;
			
			//asiganaciuon de valores por campos
			document.getElementById('idhistorico_vacaciones').value = idhistorico_vacaciones;
			
			document.getElementById('cantidad_feriados').value = cantidad_feriados;
			//document.getElementById('id_trabajador').value = idhistorico_vacaciones;
			document.getElementById('periodo').value = periodo;
			document.getElementById('numero_memorandum').value = numero_memorandum;
			document.getElementById('fecha_memorandum').value = fecha_memorandum;
			document.getElementById('fecha_inicio_vacacion').value = fecha_inicio_vacacion;
			document.getElementById('fecha_culminacion_vacacion').value = fecha_culminacion_vacacion;
			document.getElementById('tiempo_disfrute').value = tiempo_disfrute;
			document.getElementById('fecha_inicio_disfrute').value = fecha_inicio_disfrute;
			document.getElementById('fecha_reincorporacion').value = fecha_reincorporacion;
			document.getElementById('fecha_reincorporacion_ajustada').value = fecha_reincorporacion_ajustada;			
			document.getElementById('tiempo_pendiente_disfrute').value = dias_pendiente_disfrute;
			document.getElementById('dias_bonificacion').value = dias_bono;			
			document.getElementById('monto_bono_vacacional').value = monto_bonos;
			document.getElementById('numero_orden_pago').value = numero_orden_pago;			
			document.getElementById('fecha_orden_pago').value = fecha_cancelacion;
			document.getElementById('elaborado_por').value = elaborado_por;			
			document.getElementById('ci_elaborado').value = ci_elaborado_por;
			document.getElementById('aprobado_por').value = aprobada_por;
			document.getElementById('ci_aprobado').value = ci_aprobado;			
			
			document.getElementById('oculto_dias').value = oculto_dias;
			document.getElementById('oculto_disfrutados').value = oculto_disfrutados;
			
			document.getElementById('accion').value = "Modificar";			
			
		} 
	}
	ajax.send("id_historico="+id_historico_vacacion+"&ejecutar=llenarFormulario");		
}