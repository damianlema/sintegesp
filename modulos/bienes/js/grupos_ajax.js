// JavaScript Document

function ingresarGrupos(codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder registrar el Grupo");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/grupos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Grupo Ingresado!");
				}else{
					mostrarMensajes("error", "Disculpe el Grupo no se pudo ingresar con Exito, por favor intente de nuevo mas tarde");
				}
					
					consultarGrupos();
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarGrupos");
	}
}



function consultarGrupos(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/grupos_ajax.php", true);	
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
	ajax.send("ejecutar=listarGrupos");		
}




function mostrarEditar(id_grupo, codigo, denominacion){
	document.getElementById('id_grupo').value = id_grupo;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('id_grupo').disabled = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'none';
}




function mostrarEliminar(id_grupo, codigo, denominacion){
	document.getElementById('id_grupo').value = id_grupo;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('id_grupo').disabled = true;
	document.getElementById('codigo').disabled= true;
	document.getElementById('denominacion').disabled = true;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'block';
}



function actualizarFormulario(){
	document.getElementById('id_grupo').disabled = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('id_grupo').value = "";
	document.getElementById('codigo').value= "";
	document.getElementById('denominacion').value = "";
	document.getElementById('botonProcesar').style.display = 'block';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'none';
}



function modificarGrupos(id_grupo, codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder modificar el Grupo");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/grupos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Grupo Modificado con Exito");
					}else{
					mostrarMensajes("error", "Disculpe el grupo no pudo ser Modificado, por favor intente de nuevo mas tarde");	
					}
					consultarGrupos();
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_grupo="+id_grupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=modificarGrupos");	
	}
}







function eliminarGrupos(id_grupo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/grupos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Se elimino el grupo con exito");
				}else{
					mostrarMensajes("error", "Disculpe el grupo no pudo ser eliminado por favor intente de nuevo mas tarde");
				}
				consultarGrupos();
				actualizarFormulario();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_grupo="+id_grupo+"&ejecutar=eliminarGrupos");		
}