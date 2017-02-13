// JavaScript Document

function ingresarConstante(codigo,descripcion,desde,hasta,unidad){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			var accion = ajax.responseText.split("|.|");
			if(accion[0] == 0){
				mostrarMensajes("error", "Existen Campos vacios");
				}else{
					if(accion[0]==1){
						mostrarMensajes("error", "Disculpe no se pude realizar el registro por favor intente mas tarde");
						}else{
							document.getElementById("idtabla_constantes").value = accion[1];
							consultarRango();
							document.getElementById('guardar').style.display = "none";
							document.getElementById('modificar').style.display = "block";
							document.getElementById('eliminar').style.display = "block";
							}
					}
		} 
	}
	ajax.send("codigo="+codigo+"&descripcion="+descripcion+"&desde="+desde+"&hasta="+hasta+"&unidad="+unidad+"&ejecutar=ingresar");		
}




function consultarRango(){
	var idtabla_constantes = document.getElementById('idtabla_constantes').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('rango').innerHTML = ajax.responseText;
			document.getElementById('desdeRango').focus();
		} 
	}
	ajax.send("idtabla_constantes="+idtabla_constantes+"&ejecutar=consultarRango");		
}




function eliminarRango(idrango_tabla_constantes){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			consultarRango();
		} 
	}
	ajax.send("idrango_tabla_constantes="+idrango_tabla_constantes+"&ejecutar=eliminarRango");		
}



function guardarRango(desdeRango,hastaRango,valor){
	var idtabla_constantes = document.getElementById('idtabla_constantes').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			var accion = ajax.responseText;
			if(accion == 0){
				mostrarMensajes("error", "Debe escribir todos los campos");
				}else{
					if(accion==1){
						mostrarMensajes("error", "Disculpe no se pudo realizar el registro por favor intente mas tarde");
						}else if(accion == "desde_duplicado"){
							mostrarMensajes("error", "Disculpe el Desde o el Hasta estan duplicados");
						}else{
							consultarRango();
						}
					}
		} 
	}
	ajax.send("idtabla_constantes="+idtabla_constantes+"&desdeRango="+desdeRango+"&hastaRango="+hastaRango+"&valor="+valor+"&ejecutar=guardarRango");
	//return false;
}






function consultarTablaConstantes(idtabla_constantes){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			var partes = ajax.responseText.split("|.|");
			document.getElementById("codigo").value = partes[0];
			document.getElementById("descripcion").value =  partes[1];
			document.getElementById("desde").value =  partes[2];
			document.getElementById("hasta").value =  partes[3];
			document.getElementById("unidad").value =  partes[4];
			document.getElementById("idtabla_constantes").value =  idtabla_constantes;
			document.getElementById("guardar").style.display = 'none';
			document.getElementById("modificar").style.display = 'block';
			document.getElementById("eliminar").style.display = 'block';
			consultarRango();
			
			//	muestro el boton de imprimir
			document.getElementById("btImprimir").style.visibility = "visible";
			//	------------------------------------
		} 
	}
	ajax.send("idtabla_constantes="+idtabla_constantes+"&ejecutar=consultarTablaConstantes");	
}



function modificarTablaConstantes(codigo, descripcion, desde, hasta, unidad){
	var idtabla_constantes = document.getElementById("idtabla_constantes").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			var accion = ajax.responseText;
			if(accion[0] == 0){
				mostrarMensajes("error", "Existen Campos vacios");
				}else{
					if(accion[0]==1){
						mostrarMensajes("error", "Disculpe no se pudo modificar el registro, por favor intente mas tarde");
						}else{
							mostrarMensajes("exito", "Se modificaron los datos con Exito");
							}
					}
		} 
	}
	ajax.send("idtabla_constantes="+idtabla_constantes+"&codigo="+codigo+"&descripcion="+descripcion+"&desde="+desde+"&hasta="+hasta+"&unidad="+unidad+"&ejecutar=modificarTablaConstantes");
}





function eliminarTablaConstantes(){	
	if(confirm("Eata seguro que desea eliminar esta Tabla de Constantes?")){
		
	
	var idtabla_constantes = document.getElementById("idtabla_constantes").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			mostrarMensajes("error", "Se Elimino el Registro con Exito");
			window.location.href="principal.php?modulo=13&accion=883";
		} 
	}
	ajax.send("idtabla_constantes="+idtabla_constantes+"&ejecutar=eliminarTablaConstantes");
	}
}





function seleccionarModificarRango(id, desde, hasta, valor){
	document.getElementById("idrango").value = id;
	document.getElementById("desdeRango").value = desde;
	document.getElementById("hastaRango").value = hasta;
	document.getElementById("valor").value = valor;
	document.getElementById("boton_ingresar_rango").style.display = 'none';
	document.getElementById("boton_modificar_rango").style.display = 'block';
}





function modificarRango(){
	var idtabla_constantes = document.getElementById("idtabla_constantes").value;
	var id = document.getElementById("idrango").value;
	var desde = document.getElementById("desdeRango").value;
	var hasta = document.getElementById("hastaRango").value;
	var valor = document.getElementById("valor").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/tabla_constante_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
		document.getElementById("idrango").value = "";
		document.getElementById("desdeRango").value = "";
		document.getElementById("hastaRango").value = "";
		document.getElementById("valor").value = "";
		document.getElementById("boton_ingresar_rango").style.display = 'block';
		document.getElementById("boton_modificar_rango").style.display = 'none';
		consultarRango();
			
		} 
	}
	ajax.send("idtabla_constantes="+idtabla_constantes+"&id="+id+"&desde="+desde+"&hasta="+hasta+"&valor="+valor+"&ejecutar=modificarRango");
}