// JavaScript Document


function ingresarInteres(){
	var anio = document.getElementById('anio').value;
	var mes = document.getElementById('mes').value;
	var interes = document.getElementById('interes').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tabla_intereses_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			consultarIntereses();
			document.getElementById('mes').value = 0;
			document.getElementById('interes').value = '';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("anio="+anio+"&mes="+mes+"&interes="+interes+"&ejecutar=ingresarInteres");	
}



function seleccionarModificar(idinteres, anio, mes, interes){
	document.getElementById('idinteres').value 	= idinteres;
	document.getElementById('anio').value 		= anio;
	document.getElementById('mes').value 		= mes;
	document.getElementById('interes').value 	= interes;
	document.getElementById('boton_ingresar_intereses').style.display	= 'none';
	document.getElementById('boton_modificar_intereses').style.display	= 'block';
}




function modificarInteres(){
	var idinteres = document.getElementById('idinteres').value;
	var anio = document.getElementById('anio').value;
	var mes = document.getElementById('mes').value;
	var interes = document.getElementById('interes').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tabla_intereses_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			consultarIntereses();
			document.getElementById('formulario_intereses').reset();
			document.getElementById('boton_ingresar_intereses').style.display = 'block';
			document.getElementById('boton_modificar_intereses').style.display = 'none';
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idinteres="+idinteres+"&anio="+anio+"&mes="+mes+"&interes="+interes+"&ejecutar=modificarInteres");	
}



function consultarIntereses(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/tabla_intereses_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('listaPorcentajes').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=consultarIntereses");	
}




function eliminarIntereses(idinteres){
	if(confirm("Seguro desea eliminar el interes seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/tabla_intereses_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
				consultarIntereses();
			} 
		}
		ajax.send("idinteres="+idinteres+"&ejecutar=eliminarIntereses");		
	}
}