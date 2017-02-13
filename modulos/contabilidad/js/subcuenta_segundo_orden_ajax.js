// JavaScript Document

function ingresarGrupo(){
	var idsubcuenta_primer = document.getElementById('idsubcuenta_primer').value;
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	if(codigo.length < 2){
		mostrarMensajes("error", "Disculpe el Codigo debe ser de DOS digitos");
		document.getElementById('codigo').select();
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/contabilidad/lib/subcuenta_segundo_orden_ajax.php", true);	
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
				document.getElementById('idsubcuenta_primer').value = 0;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idsubcuenta_primer="+idsubcuenta_primer+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarGrupo");
	}
}








function listarGrupos(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var subcuenta_primerBuscar = document.getElementById('subcuenta_primerBuscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/subcuenta_segundo_orden_ajax.php", true);	
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
	ajax.send("subcuenta_primerBuscar="+subcuenta_primerBuscar+"&campoBuscar="+campoBuscar+"&ejecutar=listarGrupos");	
	return false;
}






function editarGrupo(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var idsubcuenta_primer = document.getElementById('idsubcuenta_primer').value;
	var idsubcuenta_segundo = document.getElementById('idsubcuenta_segundo').value;
	if(codigo.length < 2){
		mostrarMensajes("error", "Disculpe el Codigo debe ser de DOS digitos");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/contabilidad/lib/subcuenta_segundo_orden_ajax.php", true);	
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
				document.getElementById('idsubcuenta_primer').value = 0;
				listarGrupos();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idsubcuenta_segundo="+idsubcuenta_segundo+"&idsubcuenta_primer="+idsubcuenta_primer+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=editarGrupo");
	}
}




function eliminarGrupo(){
	var idsubcuenta_segundo = document.getElementById('idsubcuenta_segundo').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/subcuenta_segundo_orden_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == 'usado'){
				mostrarMensajes("error", "Disculpe la Sub Cuenta de Segundo Orden que intenta eliminar esta siendo usada por una o varias Desagregaciones, por lo tanto no puede ser eliminada");
			}else{
			document.getElementById('boton_ingresar').style.display = 'block';
			document.getElementById('boton_modificar').style.display = 'none';
			document.getElementById('boton_eliminar').style.display = 'none';
			document.getElementById('codigo').disabled = false;
			document.getElementById('denominacion').disabled = false;
			document.getElementById('idsubcuenta_primer').disabled = false;
			listarGrupos();
			//desbloquearCampos();
			}
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idsubcuenta_segundo="+idsubcuenta_segundo+"&ejecutar=eliminarGrupo");		
}


function seleccinarModificar(codigo, denominacion, idsubcuenta_primer, idsubcuenta_segundo){
	document.getElementById('idsubcuenta_segundo').value = idsubcuenta_segundo;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idsubcuenta_primer').value = idsubcuenta_primer;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function seleccinarEliminar(codigo, denominacion, idsubcuenta_primer, idsubcuenta_segundo){
	document.getElementById('idsubcuenta_segundo').value = idsubcuenta_segundo;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idsubcuenta_primer').value = idsubcuenta_primer;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';
	
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('idsubcuenta_primer').disabled = true;
}