// JavaScript Document



function recalcularTotalRectificacion(){
		var ajax=nuevoAjax();
		var idRectificacion = document.getElementById('idrectificacion_presupuesto').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('total_receptoras').value = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
				//}
			}
		}
		ajax.send("ejecutar=recalcularTotalRectificacion&id="+idRectificacion);
}

function recalcularTotalCredito(){
		var ajax=nuevoAjax();
		var idCredito = document.getElementById('idcreditos_adicionales').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('total_credito').value = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("ejecutar=recalcularTotalCredito&id="+idCredito);
}


function recalcularTotalDisminucion(){
		var ajax=nuevoAjax();
		var idDisminucion = document.getElementById('iddisminucion_presupuesto').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('total_disminucion').value = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("ejecutar=recalcularTotalDisminucion&id="+idDisminucion);
}

function recalcularTotalTrasladoI(){
		var ajax=nuevoAjax();
		var idTraslado = document.getElementById('idtraslados_presupuestarios').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('total_receptoras').value = ajax.responseText;

					document.getElementById("divCargando").style.display = "none";
				//}

			} 
		}
		ajax.send("ejecutar=recalcularTotalTrasladoI&id="+idTraslado);
}

function recalcularTotalTrasladoD(){
		var ajax=nuevoAjax();
		var idTraslado = document.getElementById('idtraslados_presupuestarios').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					document.getElementById('total_cedentes').value = ajax.responseText;
					
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("ejecutar=recalcularTotalTrasladoD&id="+idTraslado);
}


function formatoNumeroPpt(idcampo, campoOculto) {

var res =  document.getElementById(idcampo).value; 
document.getElementById(campoOculto).value = res; 
if (document.getElementById(idcampo).value >= 0 && document.getElementById(idcampo).value <= 99999999999)  { 
	resultado = parseFloat(res).toFixed(2).toString();
	resultado = resultado.split(".");
	var cadena = ""; cont = 1
	for(m=resultado[0].length-1; m>=0; m--){
		cadena = resultado[0].charAt(m) + cadena
		cont%3 == 0 && m >0 ? cadena = "." + cadena : cadena = cadena
		cont== 3 ? cont = 1 :cont++
	}
	document.getElementById(idcampo).value = cadena + "," + resultado[1]; 
} else { 
	document.getElementById(idcampo).value = "0"; 
	document.getElementById(idcampo).focus();
} 
}


function recalcularPartidaTrasladoCedente(id_partida,id_traslado,monto){
		var ajax=nuevoAjax();
		var idTraslado = document.getElementById('idtraslados_presupuestarios').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					//document.getElementById('total_cedentes').value = ajax.responseText;
					
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("ejecutar=recalcularPartidaTrasladoCedente&id_traslado="+id_traslado+"&id_partida="+id_partida+"&monto="+monto);
}

function recalcularPartidaTrasladoReceptora(id_partida,id_traslado,monto){
		var ajax=nuevoAjax();
		var idTraslado = document.getElementById('idtraslados_presupuestarios').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("ejecutar=recalcularPartidaTrasladoReceptora&id_traslado="+id_traslado+"&id_partida="+id_partida+"&monto="+monto);
}



function recalcularPartidaCredito(id_partida,id_credito,monto){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("ejecutar=recalcularPartidaCredito&id_credito="+id_credito+"&id_partida="+id_partida+"&monto="+monto);
}



function recalcularPartidaDisminucion(id_partida,id_disminucion,monto){
		var ajax=nuevoAjax();
		//var idCredito = document.getElementById('idcreditos_adicionales').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					//document.getElementById('total_cedentes').value = ajax.responseText;
					
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("?ejecutar=recalcularPartidaDisminucion&id_disminucion="+id_disminucion+"&id_partida="+id_partida+"&monto="+monto);
}


function recalcularPartidaRectificada(id_partida,id_rectificacion,monto){
		var ajax=nuevoAjax();
		
		//var idCredito = document.getElementById('idcreditos_adicionales').value;
		ajax.open("POST", "modulos/presupuesto/lib/movimiento_presupuesto_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//if(ajax.responseText.indexOf("exito") != -1){
					//document.getElementById('total_cedentes').value = ajax.responseText;
					
					document.getElementById("divCargando").style.display = "none";
				//}
				
			} 
		}
		ajax.send("?ejecutar=recalcularPartidaRectificada&id_rectificacion="+id_rectificacion+"&id_partida="+id_partida+"&monto="+monto);
}

