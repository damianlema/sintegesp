// JavaScript Document


function seleccionarPeriodo(idtipo_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("celda_centro_costo").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=seleccionarPeriodo");		
}




function ingresarDatos(){
	var idtipo_hoja_tiempo = document.getElementById("tipo_hoja_tiempo").value;
	var idtipo_nomina = document.getElementById("tipo_nomina").value;
	//var centro_costo = document.getElementById("centro_costo").value;
	var idperiodo = document.getElementById("periodo").value;
	var ajax=nuevoAjax();
	if(idtipo_hoja_tiempo == "0" || idtipo_nomina == "0" || idperiodo == "0"){
		mostrarMensajes("error", "Disculpe debe seleccionar todos los datos");
	}else{
		ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				if(ajax.responseText =="existe"){
					mostrarMensajes("error", "Disculpe la hoja de tiempo que intenta ingresar ya existe");
					document.getElementById("divCargando").style.display = "none";
				}else{
					document.getElementById('idhoja_tiempo').value = ajax.responseText; 
					consultarTrabajadores();	
					document.getElementById("boton_ingresar").style.display = "none";
					document.getElementById("boton_modificar").style.display = "none";
					document.getElementById("boton_eliminar").style.display = "block";
					document.getElementById("boton_duplicar").style.display = "block";
                    document.getElementById("tabla_prefijar").style.display = "block";
				}
			} 
		}
		ajax.send("idperiodo="+idperiodo+"&idtipo_hoja_tiempo="+idtipo_hoja_tiempo+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=ingresarDatos");
	}
}


function consultarTrabajadores(){
	var idhoja_tiempo = document.getElementById("idhoja_tiempo").value;
	var idtipo_nomina = document.getElementById("tipo_nomina").value;
	//var centro_costo = document.getElementById("centro_costo").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaTrabajadores").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&idhoja_tiempo="+idhoja_tiempo+"&ejecutar=consultarTrabajadores");
}




function guardarHoras(idtrabajador, idhoja_tiempo, horas){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){} 
	}
	ajax.send("horas="+horas+"&idtrabajador="+idtrabajador+"&idhoja_tiempo="+idhoja_tiempo+"&ejecutar=guardarHoras");
}

function guardarHorasPrefijadas(){
    idhoja_tiempo = document.getElementById("idhoja_tiempo").value;
    prefijar_valor = document.getElementById("prefijar_valor").value;

    var ajax=nuevoAjax();
    ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange=function() {
        if(ajax.readyState == 1){}
        if (ajax.readyState==4){

            consultarTrabajadores();
        }
    }
    ajax.send("prefijar_valor="+prefijar_valor+"&idhoja_tiempo="+idhoja_tiempo+"&ejecutar=guardarHorasPrefijadas");
}



function consultarDatosPrincipales(idtipo_hoja_tiempo, idtipo_nomina, periodo, idhoja_tiempo){
	document.getElementById("idhoja_tiempo").value = idhoja_tiempo;
	document.getElementById("tipo_hoja_tiempo").value = idtipo_hoja_tiempo;
	document.getElementById("tipo_nomina").value = idtipo_nomina;
	//document.getElementById("centro_costo").value = centro_costo;
	
	document.getElementById("boton_ingresar").style.display = "none";
	document.getElementById("boton_modificar").style.display = "block";
	document.getElementById("boton_eliminar").style.display = "block";
	document.getElementById("boton_duplicar").style.display = "block";
	consultarTrabajadores();
	
	setTimeout("document.getElementById('periodo').value = "+periodo+"",1000);
    document.getElementById("tabla_prefijar").style.display = "block";
	document.getElementById("btImprimir").style.visibility = "visible";
}





function modificarDatos(){
	var idhoja_tiempo = document.getElementById("idhoja_tiempo").value;
	var idtipo_hoja_tiempo = document.getElementById("tipo_hoja_tiempo").value;
	var idtipo_nomina = document.getElementById("tipo_nomina").value;
	//var centro_costo = document.getElementById("centro_costo").value;
	var idperiodo = document.getElementById("periodo").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				consultarTrabajadores();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idperiodo="+idperiodo+"&idtipo_hoja_tiempo="+idtipo_hoja_tiempo+"&idtipo_nomina="+idtipo_nomina+"&idhoja_tiempo="+idhoja_tiempo+"&ejecutar=modificarDatos");
}



function duplicarDatos(){
	var idhoja_tiempo = document.getElementById("idhoja_tiempo").value;
	var idtipo_hoja_tiempo = document.getElementById("tipo_hoja_tiempo").value;
	var idtipo_nomina = document.getElementById("tipo_nomina").value;
	//var centro_costo = document.getElementById("centro_costo").value;
	var idperiodo = document.getElementById("periodo").value;
	var ajax=nuevoAjax();
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			if(ajax.responseText =="existe"){
				mostrarMensajes("error", "Disculpe la hoja de tiempo que intenta DUPLICAR ya existe");
				document.getElementById("divCargando").style.display = "none";
			}else{
				//alert(idhoja_tiempo);
				document.getElementById('idhoja_tiempo').value = ajax.responseText; 
				consultarTrabajadoresDuplicar(idhoja_tiempo);
				document.getElementById("divCargando").style.display = "none";
				document.getElementById("boton_ingresar").style.display = "none";
				document.getElementById("boton_modificar").style.display = "none";
				document.getElementById("boton_eliminar").style.display = "block";
				document.getElementById("boton_duplicar").style.display = "block";
                document.getElementById("tabla_prefijar").style.display = "block";
			} 
		}
	}
	ajax.send("idperiodo="+idperiodo+"&idtipo_hoja_tiempo="+idtipo_hoja_tiempo+"&idtipo_nomina="+idtipo_nomina+"&idhoja_tiempo="+idhoja_tiempo+"&ejecutar=duplicarDatos");
}



function consultarTrabajadoresDuplicar(idhoja_tiempo_anterior){
	var idhoja_tiempo = document.getElementById("idhoja_tiempo").value;
	var idtipo_nomina = document.getElementById("tipo_nomina").value;
	//var centro_costo = document.getElementById("centro_costo").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaTrabajadores").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&idhoja_tiempo="+idhoja_tiempo+"&idhoja_tiempo_anterior="+idhoja_tiempo_anterior+"&ejecutar=consultarTrabajadoresDuplicar");
}



function eliminarDatos(){
	var idhoja_tiempo = document.getElementById("idhoja_tiempo").value;
	if(confirm("Seguro desea Eliminar esta hoja de tiempo")){	
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "asociada_concepto"){
					mostrarMensajes("error", "Disculpe no puede eliminar esta hoja de tiempo, debido a que esta siendo usada en un concepto");
				}else{
					document.getElementById("idhoja_tiempo").value = 0;
					document.getElementById("tipo_hoja_tiempo").value = 0;
					document.getElementById("tipo_nomina").value = 0;
					//document.getElementById("centro_costo").value = 0;
					document.getElementById("periodo").value = 0;
					document.getElementById("listaTrabajadores").innerHTML = '';
					document.getElementById("boton_ingresar").style.display = "block";
					document.getElementById("boton_modificar").style.display = "none";
					document.getElementById("boton_eliminar").style.display = "none";
					volverPeriodo();
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idhoja_tiempo="+idhoja_tiempo+"&ejecutar=eliminarDatos");
	}
}




function volverPeriodo(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/hoja_tiempo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById("celda_centro_costo").innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=volverPeriodo");		
}