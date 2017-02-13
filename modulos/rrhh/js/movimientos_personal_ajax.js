// JavaScript Document


function buscarMovimientosTrabajador(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaMovimientos").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=buscarMovimientosTrabajador");
}





function consultarFicha(nomenclatura_ficha){
	if(nomenclatura_ficha == 0){
			document.getElementById('nro_ficha').innerHTML = "Seleccione";
	}else{
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nro_ficha').innerHTML = ajax.responseText;
			document.getElementById('campo_nro_ficha').value = ajax.responseText;
		} 
	}
	ajax.send("nomenclatura_ficha="+nomenclatura_ficha+"&ejecutar=consultarFicha");			
	}
}




function ingresarMovimiento(){
	if(confirm("Seguro desea realizar este movimiento? recuerde que si es un movimiento de EGRESO el que intenta registrar, automaticamente esta persona saldra de todas las nominas donde este asociado")){
	var idtrabajador = document.getElementById('id_trabajador').value;
	var fecha_movimiento = document.getElementById('fecha_movimiento').value;
	var tipo_movimiento = document.getElementById('tipo_movimiento').value;
	var justificacion = document.getElementById('justificacion').value;
	var fecha_egreso = document.getElementById('fecha_egreso').value;
	var causal = document.getElementById('causal').value;
	var idnuevo_cargo = document.getElementById('nuevo_cargo').value;
	var idubicacion_nueva = document.getElementById("nueva_ubicacion_funcional").value;
	var fecha_reingreso = document.getElementById('fecha_reingreso').value;
	var fecha_ingreso = document.getElementById('fecha_ingreso').value;
	var desde = document.getElementById('desde').value;
	var hasta = document.getElementById('hasta').value;
	var centro_costo = document.getElementById('centro_costo').value;
	if(document.getElementById('ficha').value != "-"){
		var ficha = document.getElementById('ficha').value+document.getElementById('campo_nro_ficha').value;
	}else{
		var ficha = "";	
	}
	var ajax=nuevoAjax();
	if(justificacion == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la justificacion del movimiento");
	}else{
	ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				buscarMovimientosTrabajador(idtrabajador);
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ficha="+ficha+"&fecha_ingreso="+fecha_ingreso+"&centro_costo="+centro_costo+"&idtrabajador="+idtrabajador+"&fecha_movimiento="+fecha_movimiento+"&tipo_movimiento="+tipo_movimiento+"&justificacion="+justificacion+"&fecha_egreso="+fecha_egreso+"&causal="+causal+"&idnuevo_cargo="+idnuevo_cargo+"&idubicacion_nueva="+idubicacion_nueva+"&fecha_reingreso="+fecha_reingreso+"&desde="+desde+"&hasta="+hasta+"&ejecutar=ingresarMovimiento");
	}
}
}





function limpiarDatos(){
	document.getElementById('fecha_movimiento').value = '';
	document.getElementById('tipo_movimiento').value = 0;
	document.getElementById('justificacion').value = '';
	document.getElementById('fecha_egreso').value = '';
	document.getElementById('causal').value = '';
	document.getElementById('nuevo_cargo').value = 0;
	document.getElementById("nueva_ubicacion_funcional").value = 0;
	document.getElementById('fecha_reingreso').value = '';
	document.getElementById('desde').value = '';
	document.getElementById('hasta').value = '';
	document.getElementById('centro_costo').value = 0;
	
	document.getElementById("celda_ficha").style.display = 'none';
	document.getElementById("celda_campo_ficha").style.display = 'none';
	document.getElementById("campo_nro_ficha").value = '';
	document.getElementById("nro_ficha").innerHTML = '';
		
}




function seleccionarModificar(idmovimiento, fecha_movimiento, idtipo_movimiento, justificacion, fecha_egreso, causal, idubicacion_nueva, fecha_reingreso, desde, hasta, idnuevo_cargo, relacion_laboral, goce_sueldo, afecta_cargo, afecta_ubicacion, afecta_tiempo, centro_costo, afecta_centro_costo, afecta_ficha){
	document.getElementById('idmovimiento').value = idmovimiento;
	document.getElementById('fecha_movimiento').value = fecha_movimiento;
	document.getElementById('tipo_movimiento').value = idtipo_movimiento;
	document.getElementById('justificacion').value = justificacion;
	document.getElementById('fecha_egreso').value = fecha_egreso;
	document.getElementById('causal').value = causal;
	document.getElementById('nuevo_cargo').value = idnuevo_cargo;
	document.getElementById("nueva_ubicacion_funcional").value = idubicacion_nueva;
	document.getElementById('fecha_reingreso').value = fecha_reingreso;
	if(desde == "0000-00-00"){
		desde = "";
	}
	if(hasta == "0000-00-00"){
		hasta = "";
	}
	document.getElementById('desde').value = desde;
	document.getElementById('hasta').value = hasta;
	document.getElementById('centro_costo').value = centro_costo;
	
	if(relacion_laboral=='si'){
		document.getElementById('celda_nombre_fecha_egreso').style.display = 'block'; 
		document.getElementById('celda_campo_fecha_egreso').style.display = 'block'; 
	}else{
		document.getElementById('celda_nombre_fecha_egreso').style.display = 'none';
		document.getElementById('celda_campo_fecha_egreso').style.display = 'none';
	}
	
	if(afecta_cargo == 'si'){
		document.getElementById('celda_nombre_nuevo_cargo').style.display = 'block';
		document.getElementById('celda_campo_nuevo_cargo').style.display = 'block';
	}else{
		document.getElementById('celda_nombre_nuevo_cargo').style.display = 'none';
		document.getElementById('celda_campo_nuevo_cargo').style.display = 'none';
	}
	
	if(afecta_ubicacion == "si"){
		document.getElementById("celda_nombre_nueva_ubicacion_funcional").style.display = 'block';
		document.getElementById("celda_campo_nueva_ubicacion_funcional").style.display = 'block';
	}else{
		document.getElementById("celda_nombre_nueva_ubicacion_funcional").style.display = 'none';	
		document.getElementById("celda_campo_nueva_ubicacion_funcional").style.display = 'none';	
	}
	
	
	if(afecta_tiempo == "si"){
		document.getElementById("celda_campo_fecha_reingreso").style.display = 'block';
		document.getElementById("celda_nombre_fecha_reingreso").style.display = 'block';
	}else{
		document.getElementById("celda_nombre_fecha_reingreso").style.display = 'none';	
		document.getElementById("celda_campo_fecha_reingreso").style.display = 'none';	
	}
	if(afecta_centro_costo == "si"){
		document.getElementById("celda_centro_costo").style.display = 'block';
		document.getElementById("campo_centro_costo").style.display = 'block';
	}else{
		document.getElementById("celda_centro_costo").style.display = 'none';	
		document.getElementById("campo_centro_costo").style.display = 'none';	
	}
	
	
	if(afecta_ficha == "si"){
		document.getElementById("celda_ficha").style.display = 'block';
		document.getElementById("celda_campo_ficha").style.display = 'block';		
	}else{
		document.getElementById("celda_ficha").style.display = 'none';
		document.getElementById("celda_campo_ficha").style.display = 'none';
	}
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_editar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';
		
		
}






function seleccionarIngresar(relacion_laboral, goce_sueldo, afecta_cargo, afecta_ubicacion, afecta_tiempo, afecta_centro_costo, afecta_ficha){
	
	if(relacion_laboral=='si'){
		document.getElementById('celda_nombre_fecha_egreso').style.display = 'block'; 
		document.getElementById('celda_campo_fecha_egreso').style.display = 'block'; 
	}else{
		document.getElementById('celda_nombre_fecha_egreso').style.display = 'none';
		document.getElementById('celda_campo_fecha_egreso').style.display = 'none';
	}
	
	if(afecta_cargo == 'si'){
		document.getElementById('celda_nombre_nuevo_cargo').style.display = 'block';
		document.getElementById('celda_campo_nuevo_cargo').style.display = 'block';
	}else{
		document.getElementById('celda_nombre_nuevo_cargo').style.display = 'none';
		document.getElementById('celda_campo_nuevo_cargo').style.display = 'none';
	}
	
	if(afecta_ubicacion == "si"){
		document.getElementById("celda_nombre_nueva_ubicacion_funcional").style.display = 'block';
		document.getElementById("celda_campo_nueva_ubicacion_funcional").style.display = 'block';
		
		
		
	}else{
		
		
		
		document.getElementById("celda_nombre_nueva_ubicacion_funcional").style.display = 'none';	
		document.getElementById("celda_campo_nueva_ubicacion_funcional").style.display = 'none';	
	}
	
	
	if(afecta_tiempo == "si"){
		document.getElementById("celda_campo_fecha_reingreso").style.display = 'block';
		document.getElementById("celda_nombre_fecha_reingreso").style.display = 'block';
	}else{
		document.getElementById("celda_nombre_fecha_reingreso").style.display = 'none';	
		document.getElementById("celda_campo_fecha_reingreso").style.display = 'none';	
	}
	
	if(afecta_centro_costo == "si"){
		document.getElementById("celda_centro_costo").style.display = 'block';
		document.getElementById("campo_centro_costo").style.display = 'block';
	}else{

		document.getElementById("celda_centro_costo").style.display = 'none';	
		document.getElementById("campo_centro_costo").style.display = 'none';	
	}
	
	
	
	if(afecta_ficha == "si"){
		document.getElementById("celda_ficha").style.display = 'block';
		document.getElementById("celda_campo_ficha").style.display = 'block';		
	}else{
		document.getElementById("celda_ficha").style.display = 'none';
		document.getElementById("celda_campo_ficha").style.display = 'none';
	}
	
	
	
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_editar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
		
		
}




function desaparecerCampos(){
	if(document.getElementById('idmovimiento').value == ''){
		document.getElementById('celda_nombre_fecha_egreso').style.display = 'none'; 
		document.getElementById('celda_campo_fecha_egreso').style.display = 'none'; 
		document.getElementById('celda_nombre_nuevo_cargo').style.display = 'none';
		document.getElementById('celda_campo_nuevo_cargo').style.display = 'none';
		document.getElementById("celda_nombre_nueva_ubicacion_funcional").style.display = 'none';	
		document.getElementById("celda_campo_nueva_ubicacion_funcional").style.display = 'none';
		document.getElementById("celda_nombre_fecha_reingreso").style.display = 'none';	
		document.getElementById("celda_campo_fecha_reingreso").style.display = 'none';
		document.getElementById("celda_centro_costo").style.display = 'none';	
		document.getElementById("campo_centro_costo").style.display = 'none';
		
		document.getElementById("celda_ficha").style.display = 'none';
		document.getElementById("celda_campo_ficha").style.display = 'none';
		
		document.getElementById("celda_ficha").style.display = 'none';
		document.getElementById("celda_campo_ficha").style.display = 'none';
		document.getElementById("campo_nro_ficha").value = '';
		document.getElementById("nro_ficha").innerHTML = '';
		
		
		document.getElementById('boton_ingresar').style.display = 'block';
		document.getElementById('boton_editar').style.display = 'none';
		document.getElementById('boton_eliminar').style.display = 'none';
	}
}



function modificarMovimiento(idmovimiento){
	var idtrabajador = document.getElementById('id_trabajador').value;
	var idmovimiento = document.getElementById('idmovimiento').value;
	var fecha_movimiento = document.getElementById('fecha_movimiento').value;
	var tipo_movimiento = document.getElementById('tipo_movimiento').value;
	var justificacion = document.getElementById('justificacion').value;
	var fecha_egreso = document.getElementById('fecha_egreso').value;
	var causal = document.getElementById('causal').value;
	var idnuevo_cargo = document.getElementById('nuevo_cargo').value;
	var idubicacion_nueva = document.getElementById("nueva_ubicacion_funcional").value;
	var fecha_reingreso = document.getElementById('fecha_reingreso').value;
	var fecha_ingreso = document.getElementById('fecha_ingreso').value;
	var desde = document.getElementById('desde').value;
	var hasta = document.getElementById('hasta').value;
	var centro_costo = document.getElementById('centro_costo').value;
	if(document.getElementById('ficha').value != "-"){
		var ficha = document.getElementById('ficha').value+document.getElementById('campo_nro_ficha').value;
	}else{
		var ficha = "";	
	}
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.phpo", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				buscarMovimientosTrabajador(document.getElementById("id_trabajador").value);
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ficha="+ficha+"&idtrabajador="+idtrabajador+"&fecha_ingreso="+fecha_ingreso+"&centro_costo="+centro_costo+"&idmovimiento="+idmovimiento+"&fecha_movimiento="+fecha_movimiento+"&tipo_movimiento="+tipo_movimiento+"&justificacion="+justificacion+"&fecha_egreso="+fecha_egreso+"&causal="+causal+"&idnuevo_cargo="+idnuevo_cargo+"&idubicacion_nueva="+idubicacion_nueva+"&fecha_reingreso="+fecha_reingreso+"&desde="+desde+"&hasta="+hasta+"&ejecutar=modificarMovimient");
}







function eliminarMovimiento(idmovimiento){
	if(confirm("Seguro desea eliminar el movimiento seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				buscarMovimientosTrabajador(document.getElementById("id_trabajador").value);
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=eliminarMovimiento");	
	}	
}