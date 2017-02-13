// JavaScript Document

function ingresar_unidad(){
	var anio = document.getElementById('anio').value;
	var desde = document.getElementById('desde').value;
	var hasta = document.getElementById('hasta').value;
	var costo = document.getElementById('costo').value;
	var costo = document.getElementById('gaceta').value;
	var costo = document.getElementById('fecha_gaceta').value;
	if(anio == "" || desde == "" || hasta == "" || costo == ""){
		mostrarMensajes("error", "Disculpe no puede dejar ningun campo vacio");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/unidad_tributaria_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				limpiarCampos();
				listarUnidades();
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("anio="+anio+"&desde="+desde+"&hasta="+hasta+"&costo="+costo+"&gaceta="+gaceta+"&fecha_gaceta="+fecha_gaceta+"&ejecutar=ingresar_unidad");
	}
}


function listarUnidades(){
	var idunidad_tributaria = document.getElementById('idunidad_tributaria').value;	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/unidad_tributaria_ajax.php", true);	
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
	ajax.send("idunidad_tributaria="+idunidad_tributaria+"&ejecutar=listarUnidades");
}



function seleccionarUnidad(idunidad_tributaria, anio, desde, hasta, costo, gaceta, fecha_gaceta){
	document.getElementById('idunidad_tributaria').value = idunidad_tributaria;
	document.getElementById('anio').value = anio;
	document.getElementById('desde').value = desde;
	document.getElementById('hasta').value = hasta;
	document.getElementById('costo').value = costo;
	document.getElementById('gaceta').value = gaceta;
	document.getElementById('fecha_gaceta').value = fecha_gaceta;
}


function modificarUnidad(){
	var idunidad_tributaria = document.getElementById("idunidad_tributaria").value;
	var anio = document.getElementById('anio').value;
	var desde = document.getElementById('desde').value;
	var hasta = document.getElementById('hasta').value;
	var costo = document.getElementById('costo').value;
	var gaceta = document.getElementById('gaceta').value;
	var fecha_gaceta = document.getElementById('fecha_gaceta').value;
	
	if(anio == "" || desde == "" || hasta == "" || costo == ""){
		mostrarMensajes("error", "Disculpe no puede dejar los campos Año, Desde, Hasta y Costo vacios");
	}else{
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/unidad_tributaria_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarUnidades();
			limpiarCampos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idunidad_tributaria="+idunidad_tributaria+"&anio="+anio+"&desde="+desde+"&hasta="+hasta+"&costo="+costo+"&gaceta="+gaceta+"&fecha_gaceta="+fecha_gaceta+"&ejecutar=modificarUnidad");	
	}
}



function eliminarUnidad(){
	if(confirm("Seguro desea eliminar esta unidad tributaria?")){
	var idunidad_tributaria = document.getElementById("idunidad_tributaria").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/recaudacion/lib/unidad_tributaria_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarUnidades();
			limpiarCampos();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idunidad_tributaria="+idunidad_tributaria+"&ejecutar=eliminarUnidad");	
	}
}



function limpiarCampos(){
	document.getElementById('idunidad_tributaria').value = "";
	document.getElementById('anio').value = "";
	document.getElementById('desde').value = "";
	document.getElementById('hasta').value = "";
	document.getElementById('costo').value = "";
	document.getElementById('gaceta').value = "";
	document.getElementById('fecha_gaceta').value = "";
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';
}