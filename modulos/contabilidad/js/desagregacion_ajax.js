// JavaScript Document

function ingresarGrupo(){
	var idsubcuenta_segundo = document.getElementById('idsubcuenta_segundo').value;
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/contabilidad/lib/desagregacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe la Subcuenta de Primer Orden que intenta ingresar ya existe");
				}
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				listarGrupos();
				document.getElementById('codigo').value = "";
				document.getElementById('denominacion').value= "";
				document.getElementById('idsubcuenta_segundo').value = 0;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idsubcuenta_segundo="+idsubcuenta_segundo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarGrupo");
}








function listarGrupos(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var subcuenta_segundoBuscar = document.getElementById('subcuenta_segundoBuscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/desagregacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById('listaGrupos').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("subcuenta_segundoBuscar="+subcuenta_segundoBuscar+"&campoBuscar="+campoBuscar+"&ejecutar=listarGrupos");	
	return false;
}






function editarGrupo(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var idsubcuenta_segundo= document.getElementById('idsubcuenta_segundo').value;
	var iddesagregacion = document.getElementById('iddesagregacion').value;
	if(codigo.length < 2){
		mostrarMensajes("error", "Disculpe el Codigo debe ser de DOS digitos");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/contabilidad/lib/desagregacion_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById('codigo').value = "";
				document.getElementById('denominacion').value= "";
				document.getElementById('idsubcuenta_segundo').value = 0;
				document.getElementById('iddesagregacion').value = 0;
				listarGrupos();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idsubcuenta_segundo="+idsubcuenta_segundo+"&iddesagregacion="+iddesagregacion+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=editarGrupo");
	}
}




function eliminarGrupo(){
	var iddesagregacion = document.getElementById('iddesagregacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/desagregacion_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			document.getElementById('boton_ingresar').style.display = 'block';
			document.getElementById('boton_modificar').style.display = 'none';
			document.getElementById('boton_eliminar').style.display = 'none';
			document.getElementById('codigo').disabled = false;
			document.getElementById('denominacion').disabled = false;
			document.getElementById('idsubcuenta_segundo').disabled = false;
			listarGrupos();
			//desbloquearCampos();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("iddesagregacion="+iddesagregacion+"&ejecutar=eliminarGrupo");		
}


function seleccinarModificar(codigo, denominacion, idsubcuenta_segundo, iddesagregacion){
	document.getElementById('iddesagregacion').value = iddesagregacion;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idsubcuenta_segundo').value = idsubcuenta_segundo;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function seleccinarEliminar(codigo, denominacion, idsubcuenta_segundo, iddesagregacion){
	document.getElementById('iddesagregacion').value = iddesagregacion;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idsubcuenta_segundo').value = idsubcuenta_segundo;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';
	
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('idsubcuenta_segundo').disabled = true;
}