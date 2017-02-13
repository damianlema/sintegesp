// JavaScript Document

function validarHoras(str_hora){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_permisos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			if(ajax.responseText == 0){
				document.getElementById('hr_inicio').style.background = "#fec092";
				document.getElementById('hr_culminacion').style.background = "#fec092";
			}
			else{
				document.getElementById('hr_inicio').style.background = "#ffffff";
				document.getElementById('hr_culminacion').style.background = "#ffffff";
			}
		} 
	}
	ajax.send("hr_inicio="+str_hora+"&ejecutar=validarHoras");		
}

function checkbox(valor){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_permisos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				//document.getElementById('grilla').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=checkbox");		
}

function buscarPermisos(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_permisos_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				document.getElementById('grilla').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=llenargrilla");		
}

function validarFechas(desde,hasta){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_permisos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){

				document.getElementById('tiempo').value = ajax.responseText;
				
			if(ajax.responseText == "La fecha de inicio ha de ser anterior a la fecha Actual"){
				document.getElementById('ingresar').disabled = true;	
				document.getElementById('ingresar').value = "Seleccione Fechas Validas";	
				
				document.getElementById('ingresar').disabled = true;	
				document.getElementById('ingresar').value = "Seleccione Fechas Validas";	
			}else{
				document.getElementById('ingresar').disabled = false;
				document.getElementById('ingresar').value = "Ingresar";
				
				document.getElementById('ingresar').disabled = false;	
				document.getElementById('ingresar').value = "Ingresar";
			}
				totalHoras(document.getElementById('tiempo').value, document.getElementById('hr_inicio').value, document.getElementById('hr_culminacion').value);
		} 
	}
	ajax.send("hasta="+hasta+"&desde="+desde+"&ejecutar=validarFechas");		
}

function totalHoras(total_dias,hr_inicio,hr_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/historico_permisos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				document.getElementById('tiempo_horas').value = ajax.responseText;
				
			if(ajax.responseText == "Selecione horas validas"){
				document.getElementById('ingresar').disabled = true;	
				document.getElementById('ingresar').value = "Selecione horas validas";	
				
				document.getElementById('ingresar').disabled = true;	
				document.getElementById('ingresar').value = "Selecione horas validas";	
			}else{
				document.getElementById('ingresar').disabled = false;
				document.getElementById('ingresar').value = "Ingresar";
				
				document.getElementById('ingresar').disabled = false;	
				document.getElementById('ingresar').value = "Ingresar";
			}
		} 
	}
	ajax.send("hr_inicio="+hr_inicio+"&hr_culminacion="+hr_culminacion+"&total_dias="+total_dias+"&ejecutar=total_Horas");		
}
