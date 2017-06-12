// JavaScript Document

function mostrarTiposReportes(idmodulo){
	//document.getElementById('reportes').disabled = true;
	cargarTiposReportes(idmodulo);
}


function cargarTiposReportes(idmodulo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/firmas_reportes_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('celdaTiposReportes').innerHTML = ajax.responseText;
			//setTimeout("consultarTipoSeleccionado()",100);
			 setTimeout("mostrarFormato("+document.getElementById('tipos_reportes').value+", "+document.getElementById('cant').value+")", 500);
		}
	}
	ajax.send("idmodulo="+idmodulo+"&ejecutar=cargarTiposReportes");
}


function consultarTipoSeleccionado(){
	if(document.getElementById('tipos_reportes').value != 0){
		consultarReporteSeleccionado(document.getElemetById('reporte').value, document.getElementById('tipos_reportes').value);
	}
}



function mostrarFormato(idtipo_reporte, cant_firmas){
	document.getElementById('cantidad_firmas').value = cant_firmas;
	idmodulo = document.getElementById('reportes').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/firmas_reportes_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById('detalle_configuracion_reportes').innerHTML = "CARGANDO...";
		}
		if (ajax.readyState==4){
			document.getElementById('detalle_configuracion_reportes').innerHTML = ajax.responseText;
		}
	}
	ajax.send("idmodulo="+idmodulo+"&cant_firmas="+cant_firmas+"&idtipo_reporte="+idtipo_reporte+"&ejecutar=mostrarFormato");
}




function procesarConfiguracion(){
	var cantidad = document.getElementById('cantidad_firmas').value;
	if(document.getElementById('titulo1')){
		nombre_campo1 = document.getElementById('nombre_campo1').value;
		tabla1= document.getElementById('tabla1').value;
		titulo1 = document.getElementById('titulo1').value;
		dependencia1 = document.getElementById('dependencia1').value;
		posicion1 =document.getElementById('posicion1').value;
		campo_completo1 =document.getElementById('campo_completo1').value;
	}else{
		nombre_campo1 = "";
		tabla1= "";
		titulo1 = "";
		dependencia1 = "";
		posicion1 ="";
		campo_completo1 = "";
	}

	if(document.getElementById('titulo2')){
		nombre_campo2 = document.getElementById('nombre_campo2').value;
		tabla2= document.getElementById('tabla2').value;
		titulo2 = document.getElementById('titulo2').value;
		dependencia2 = document.getElementById('dependencia2').value;
		posicion2 = document.getElementById('posicion2').value;
		campo_completo2 =document.getElementById('campo_completo2').value;
	}else{
		nombre_campo2 = "";
		tabla2= "";
		titulo2 = "";
		dependencia2 = "";
		posicion2 ="";
		campo_completo2 = "";
	}


	if(document.getElementById('titulo3')){
		nombre_campo3 = document.getElementById('nombre_campo3').value;
		tabla3= document.getElementById('tabla3').value;
		titulo3 = document.getElementById('titulo3').value;
		dependencia3 = document.getElementById('dependencia3').value;
		posicion3 =document.getElementById('posicion3').value;
		campo_completo3 =document.getElementById('campo_completo3').value;
	}else{
		nombre_campo3 = "";
		tabla3= "";
		titulo3 = "";
		dependencia3 = "";
		posicion3 ="";
		campo_completo3 ="";
	}


	if(document.getElementById('titulo4')){
		nombre_campo4 = document.getElementById('nombre_campo4').value;
		tabla4= document.getElementById('tabla4').value;
		titulo4 = document.getElementById('titulo4').value;
		dependencia4 = document.getElementById('dependencia4').value;
		posicion4 =document.getElementById('posicion4').value;
		campo_completo4 =document.getElementById('campo_completo4').value;
	}else{
		nombre_campo4 = "";
		tabla4= "";
		titulo4 = "";
		dependencia4 = "";
		posicion4 ="";
		campo_completo4 ="";
	}



	if(document.getElementById('titulo5')){
		nombre_campo5 = document.getElementById('nombre_campo5').value;
		tabla5= document.getElementById('tabla5').value;
		titulo5 = document.getElementById('titulo5').value;
		dependencia5 = document.getElementById('dependencia5').value;
		posicion5 =document.getElementById('posicion5').value;
		campo_completo5 =document.getElementById('campo_completo5').value;
	}else{
		nombre_campo5 = "";
		tabla5= "";
		titulo5 = "";
		dependencia5 = "";
		posicion5 ="";
		campo_completo5 ="";
	}



	if(document.getElementById('titulo6')){
		nombre_campo6 = document.getElementById('nombre_campo6').value;
		tabla6= document.getElementById('tabla6').value;
		titulo6 = document.getElementById('titulo6').value;
		dependencia6 = document.getElementById('dependencia6').value;
		posicion6 =document.getElementById('posicion6').value;
		campo_completo6 =document.getElementById('campo_completo6').value;
	}else{
		nombre_campo6 = "";
		tabla6= "";
		titulo6 = "";
		dependencia6 = "";
		posicion6 ="";
		campo_completo6 ="";
	}


	var idmodulo = document.getElementById('reportes').value;
	var idtipo_reporte = document.getElementById('tipos_reportes').value;
	//var titulo_general = document.getElementById('titulo_general').value;
	//var logo = document.getElementById('logo').value;

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/utilidades/lib/firmas_reportes_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if(ajax.readyState == 1){
			document.getElementById('detalle_configuracion_reportes').innerHTML = "CARGANDO...";
		}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "Configuracion Guardada con Exito");
			//window.close();
			document.getElementById('detalle_configuracion_reportes').innerHTML = '';
			document.getElementById('reportes').value = 0;
			document.getElementByid('tipos_reportes').value = 0;
			//document.getElementById('detalle_configuracion_reportes').innerHTML = ajax.responseText;
		}
	}
	ajax.send("campo_completo1="+campo_completo1+"&campo_completo2="+campo_completo2+"&campo_completo3="+campo_completo3+"&campo_completo4="+campo_completo4+"&campo_completo5="+campo_completo5+"&campo_completo6="+campo_completo6+"&idmodulo="+idmodulo+"&idtipo_reporte="+idtipo_reporte+"&&nombre_campo6="+nombre_campo6+"&tabla6="+tabla6+"&titulo6="+titulo6+"&dependencia6="+dependencia6+"&posicion6="+posicion6+"&nombre_campo1="+nombre_campo1+"&tabla1="+tabla1+"&titulo1="+titulo1+"&dependencia1="+dependencia1+"&posicion1="+posicion1+"&nombre_campo2="+nombre_campo2+"&tabla2="+tabla2+"&titulo2="+titulo2+"&dependencia2="+dependencia2+"&posicion2="+posicion2+"&nombre_campo3="+nombre_campo3+"&tabla3="+tabla3+"&titulo3="+titulo3+"&dependencia3="+dependencia3+"&posicion3="+posicion3+"&nombre_campo4="+nombre_campo4+"&tabla4="+tabla4+"&titulo4="+titulo4+"&dependencia4="+dependencia4+"&posicion4="+posicion4+"&nombre_campo5="+nombre_campo5+"&tabla5="+tabla5+"&titulo5="+titulo5+"&dependencia5="+dependencia5+"&posicion5="+posicion5+"&ejecutar=procesarConfiguracion");
}
