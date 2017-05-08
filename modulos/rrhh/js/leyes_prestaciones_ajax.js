// JavaScript Document

function limpiarFormulario(){

	window.location.href='principal.php?accion=1202&modulo=1';

}




function registrarLeyesPrestaciones(){
	var denominacion		= document.getElementById('denominacion').value;
	var siglas 				= document.getElementById('siglas').value;
	var calcula 			= document.getElementById('calcula').value;
	var tipo_abono 			= document.getElementById('tipo_abono').value;
	var mes_inicio_abono 	= document.getElementById('mes_inicio_abono').value;
	var valor_abono			= document.getElementById('valor_abono').value;
	var valor_abono_adicional = document.getElementById('valor_abono_adicional').value;
	var tipo_abono_adicional = document.getElementById('tipo_abono_adicional').value;
	var tope_abono_adicional = document.getElementById('tope_abono_adicional').value;
	var mes_inicio_aplica	= document.getElementById('mes_inicio_aplica').value;
	var anio_inicio_aplica	= document.getElementById('anio_inicio_aplica').value;
	var mes_fin_aplica		= document.getElementById('mes_fin_aplica').value;
	var anio_fin_aplica		= document.getElementById('anio_fin_aplica').value;
	var capitaliza_intereses = document.getElementById('capitaliza_intereses').value;

	if (anio_fin_aplica < anio_inicio_aplica){
		alert("El año final a aplicar Ley no puede ser menor al año inicial de aplicación de la misma");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/leyes_prestaciones_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarMensajes("exito", "La Ley de Prestaciones se registro con exito");
					//consultarLeyesPrestaciones();
					limpiarFormulario();
				}
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("denominacion="+denominacion
				+"&siglas="+siglas
				+"&calcula="+calcula
				+"&tipo_abono="+tipo_abono
				+"&mes_inicio_abono="+mes_inicio_abono
				+"&valor_abono="+valor_abono
				+"&valor_abono_adicional="+valor_abono_adicional
				+"&tipo_abono_adicional="+tipo_abono_adicional
				+"&tope_abono_adicional="+tope_abono_adicional
				+"&mes_inicio_aplica="+mes_inicio_aplica
				+"&anio_inicio_aplica="+anio_inicio_aplica
				+"&mes_fin_aplica="+mes_fin_aplica
				+"&anio_fin_aplica="+anio_fin_aplica
				+"&capitaliza_intereses="+capitaliza_intereses
				+"&ejecutar=registrarLeyesPrestaciones");
	}
}




function consultarLeyesPrestaciones(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/leyes_prestaciones_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('listaLeyesPrestaciones').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
	}
	ajax.send("ejecutar=consultarLeyesPrestaciones");
}





function eliminarLeyesPrestaciones(idleyes_prestaciones){
	if(confirm("Realmente desea eliminar esta Ley de Prestaciones?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/leyes_prestaciones_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				window.location.href='principal.php?accion=1202&modulo=1';
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("idleyes_prestaciones="+idleyes_prestaciones+"&ejecutar=eliminarLeyesPrestaciones");
	}
}




function mostrarParaModificar(	idleyes_prestaciones,
								denominacion,
								siglas,
								calcula,
								tipo_abono,
								mes_inicio_abono,
								valor_abono,
								valor_abono_adicional,
								tipo_abono_adicional,
								valor_tope_adicional,
								mes_inicio_aplica,
								anio_inicio_aplica,
								mes_fin_aplica,
								anio_fin_aplica,
								capitaliza_intereses){
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idleyes_prestaciones').value = idleyes_prestaciones;
	document.getElementById('siglas').value = siglas;
	document.getElementById('calcula').value = calcula;
	document.getElementById('tipo_abono').value = tipo_abono;
	document.getElementById('mes_inicio_abono').value = mes_inicio_abono;
	document.getElementById('valor_abono').value = valor_abono;
	document.getElementById('valor_abono_adicional').value = valor_abono_adicional;
	document.getElementById('tipo_abono_adicional').value = tipo_abono_adicional;
	document.getElementById('tope_abono_adicional').value = valor_tope_adicional;
	document.getElementById('mes_inicio_aplica').value = mes_inicio_aplica;
	document.getElementById('anio_inicio_aplica').value = anio_inicio_aplica;
	document.getElementById('mes_fin_aplica').value = mes_fin_aplica;
	document.getElementById('anio_fin_aplica').value = anio_fin_aplica;
	document.getElementById('capitaliza_intereses').value = capitaliza_intereses;
	document.getElementById('ingresar').style.display = 'none';
	document.getElementById('modificar').style.display = 'block';
}





function modificarLeyesPrestaciones(){
	var idleyes_prestaciones = document.getElementById('idleyes_prestaciones').value;
	var denominacion		= document.getElementById('denominacion').value;
	var siglas 				= document.getElementById('siglas').value;
	var calcula 			= document.getElementById('calcula').value;
	var tipo_abono 			= document.getElementById('tipo_abono').value;
	var mes_inicio_abono 	= document.getElementById('mes_inicio_abono').value;
	var valor_abono			= document.getElementById('valor_abono').value;
	var valor_abono_adicional = document.getElementById('valor_abono_adicional').value;
	var tipo_abono_adicional = document.getElementById('tipo_abono_adicional').value;
	var tope_abono_adicional = document.getElementById('tope_abono_adicional').value;
	var mes_inicio_aplica	= document.getElementById('mes_inicio_aplica').value;
	var anio_inicio_aplica	= document.getElementById('anio_inicio_aplica').value;
	var mes_fin_aplica		= document.getElementById('mes_fin_aplica').value;
	var anio_fin_aplica		= document.getElementById('anio_fin_aplica').value;
	var capitaliza_intereses = document.getElementById('capitaliza_intereses').value;

	if (anio_fin_aplica < anio_inicio_aplica){
		alert("El año final a aplicar Ley no puede ser menor al año inicial de aplicación de la misma");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/leyes_prestaciones_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText.indexOf("exito") != -1){
					mostrarMensajes("exito", "Se modificaron los datos con exito");
					//consultarLeyesPrestaciones();
					limpiarFormulario();
					document.getElementById('modificar').style.display = 'none';
					document.getElementById('ingresar').style.display = 'block';
				}
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("denominacion="+denominacion
				+"&siglas="+siglas
				+"&calcula="+calcula
				+"&tipo_abono="+tipo_abono
				+"&mes_inicio_abono="+mes_inicio_abono
				+"&valor_abono="+valor_abono
				+"&valor_abono_adicional="+valor_abono_adicional
				+"&tipo_abono_adicional="+tipo_abono_adicional
				+"&tope_abono_adicional="+tope_abono_adicional
				+"&mes_inicio_aplica="+mes_inicio_aplica
				+"&anio_inicio_aplica="+anio_inicio_aplica
				+"&mes_fin_aplica="+mes_fin_aplica
				+"&anio_fin_aplica="+anio_fin_aplica
				+"&capitaliza_intereses="+capitaliza_intereses
				+"&idleyes_prestaciones="+idleyes_prestaciones
				+"&ejecutar=modificarLeyesPrestaciones");
	}
}
