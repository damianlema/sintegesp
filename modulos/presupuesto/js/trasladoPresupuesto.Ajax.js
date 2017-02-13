/*****************************************************************************
* @Funciones javascript para controlar los eventos de la vista trasladoPresupuesto.view.php
* @versión: 1.0
* @fecha creación: 30/07/2016
* @autor: Hector Damian Lema
******************************************************************************
* @fecha modificacion
* @autor
* @descripcion
******************************************************************************/

function ingresarDatosBasicos(){

	var numero_solicitud  = document.getElementById('numeroSolicitud').value;
	var fecha_solicitud   = document.getElementById('datetimepicker1').value;
	var numero_resolucion = document.getElementById('numeroResolucion').value;
	var fecha_resolucion  = document.getElementById('datetimepicker2').value;
	var concepto          = document.getElementById('concepto').value;

	var form = 'numero_solicitud='+ numero_solicitud + '&fecha_solicitud=' + fecha_solicitud
				+ '&numero_resolucion=' + numero_resolucion + '&fecha_resolucion=' + fecha_resolucion
				+ '&concepto=' + concepto + '&ejecutar=ingresar_datos_basicos';

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			alert(ajax.responseText);
			respuesta = ajax.responseText.split("|.|");
			if(respuesta[0] == 1){
				document.getElementById("idTrasladoPresupuestario").value = respuesta[1];
				document.getElementById("_MENSAJE_SUCCESS_").style.display = "block";
				document.getElementById('_MENSAJE_SUCCESS_').innerHTML = '<strong>¡Continue!</strong>    Registro de datos exitoso, continue con la carga de las partidas....';
				setTimeout("document.getElementById('_MENSAJE_SUCCESS_').style.display='none'",2500);
				document.getElementById("divTablas").style.display = "block";
				document.getElementById("btnContinuar").style.display = "none";
				document.getElementById("btnActualizar").style.display = "block";
				document.getElementById('numeroSolicitud').disabled;
			}else if(respuesta[0] == 2){
				document.getElementById("_MENSAJE_DANGER_").style.display = "block";
				document.getElementById('_MENSAJE_DANGER_').innerHTML = '<strong>¡ERROR!</strong>    El Número de solicitud ya se encuentra registrado, por favor verifique el Número de Solicitud ingresado....';
				setTimeout("document.getElementById('_MENSAJE_DANGER_').style.display='none'",3500);
			}else if(respuesta[0] == 3){
				document.getElementById("_MENSAJE_WARNING_").style.display = "block";
				document.getElementById('_MENSAJE_WARNING_').innerHTML = '<strong>¡Alerta!</strong>    Por favor complete los  campos requeridos del formulario....';
				setTimeout("document.getElementById('_MENSAJE_WARNING_').style.display='none'",2500);
			}
		}
	}
	ajax.send(form);
}

function actualizarDatosBasicos(){

	var idtraslado_presupuestario = document.getElementById('idTrasladoPresupuestario').value;
	var fecha_solicitud           = document.getElementById('datetimepicker1').value;
	var numero_resolucion         = document.getElementById('numeroResolucion').value;
	var fecha_resolucion          = document.getElementById('datetimepicker2').value;
	var concepto                  = document.getElementById('concepto').value;
	alert(concepto);
	var form = 'idtraslado_presupuestario='+ idtraslado_presupuestario + '&fecha_solicitud=' + fecha_solicitud
				+ '&numero_resolucion=' + numero_resolucion + '&fecha_resolucion=' + fecha_resolucion
				+ '&concepto=' + concepto + '&ejecutar=actualizar_datos_basicos';

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/presupuesto/controlador/trasladoPresupuesto.controller.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			alert(ajax.responseText);
			respuesta = ajax.responseText.split("|.|");
			if(respuesta[0] == 1){
				document.getElementById("_MENSAJE_SUCCESS_").style.display = "block";
				document.getElementById('_MENSAJE_SUCCESS_').innerHTML = '<strong>¡Continue!</strong>    Actualización de datos exitoso, continue con la carga de las partidas....';
				setTimeout("document.getElementById('_MENSAJE_SUCCESS_').style.display='none'",2500);
			}else if(respuesta[0] == 2){
				document.getElementById("_MENSAJE_DANGER_").style.display = "block";
				document.getElementById('_MENSAJE_DANGER_').innerHTML = '<strong>ERROR!</strong>   A ocurrido un error en la actualizacion. (Código del error: '+respuesta[1]+')';
				setTimeout("document.getElementById('_MENSAJE_DANGER_').style.display='none'",2500);
			}else if(respuesta[0] == 3){
				document.getElementById("_MENSAJE_WARNING_").style.display = "block";
				document.getElementById('_MENSAJE_WARNING_').innerHTML = '<strong>¡Alerta!</strong>   Por favor complete los  campos requeridos del formulario....';
				setTimeout("document.getElementById('_MENSAJE_WARNING_').style.display='none'",2500);
			}
		}
	}
	ajax.send(form);
}