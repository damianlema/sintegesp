// JavaScript Document


function ingresarRequisitos(){
	var descripcion = document.getElementById('descripcion').value;
	if(document.getElementById("vencimiento").checked == true){
		var vencimiento = "si";	
	}else{
		var vencimiento = "no";	
	}
	
	if(document.getElementById("bloquea_proceso").checked == true){
		var bloquea_proceso = "si";	
	}else{
		var bloquea_proceso = "no";	
	}
	
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/requisitos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarRequisitos();
				document.getElementById('descripcion').value = '';
				document.getElementById('vencimiento').checked = false;
					document.getElementById('bloquea_proceso').checked = false;
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("vencimiento="+vencimiento+"&bloquea_proceso="+bloquea_proceso+"&descripcion="+descripcion+"&ejecutar=ingresarRequisitos");
}





function modificarRequisitos(){
	var idrequisitos = document.getElementById('idrequisitos').value;
	var descripcion = document.getElementById('descripcion').value;
	if(document.getElementById("vencimiento").checked == true){
		var vencimiento = "si";	
	}else{
		var vencimiento = "no";	
	}
	
	if(document.getElementById("bloquea_proceso").checked == true){
		var bloquea_proceso = "si";	
	}else{
		var bloquea_proceso = "no";	
	}
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/requisitos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				listarRequisitos();
				document.getElementById('descripcion').value = '';
				document.getElementById('vencimiento').checked = false;
					document.getElementById('bloquea_proceso').checked = false;
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("vencimiento="+vencimiento+"&bloquea_proceso="+bloquea_proceso+"&idrequisitos="+idrequisitos+"&descripcion="+descripcion+"&ejecutar=modificarRequisitos");
}


function eliminarRequisitos(){
	if(confirm("¿Seguro desea Eliminar este Requisito?")){
	var idrequitos = document.getElementById('idrequisitos').value;
	var descripcion = document.getElementById('descripcion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/requisitos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "usado"){
					mostrarMensajes("error", "Disculpe el requisito que intenta eliminar esta asociado a alguna Actividad Comercial, por lo tanto no puede ser eliminado");
				}else{
					listarRequisitos();
					document.getElementById('descripcion').value = '';
					document.getElementById('vencimiento').checked = false;
					document.getElementById('bloquea_proceso').checked = false;
					document.getElementById('boton_ingresar').style.display = 'block';
					document.getElementById('boton_modificar').style.display = 'none';
					document.getElementById('boton_eliminar').style.display = 'none';
					document.getElementById("divCargando").style.display = "none";	
				}
			}
			
		}
		ajax.send("idrequisitos="+idrequisitos+"&descripcion="+descripcion+"&ejecutar=eliminarRequisitos");
	}
}




function listarRequisitos(){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/recaudacion/lib/requisitos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaRequisitos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
		}
		ajax.send("ejecutar=listarRequisitos");
}





function seleccionarDatos(descripcion, idrequisitos, vencimiento, bloquea_proceso){
	document.getElementById('descripcion').value = descripcion;
	if(vencimiento == "si"){
		document.getElementById('vencimiento').checked = true;
	}else{
		document.getElementById('vencimiento').checked = false;
	}
	if(bloquea_proceso == "si"){
		document.getElementById('bloquea_proceso').checked = true;
	}else{
		document.getElementById('bloquea_proceso').checked = false;
	}
	document.getElementById('idrequisitos').value = idrequisitos;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'block';
	
}
	