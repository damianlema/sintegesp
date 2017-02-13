// JavaScript Document

function ingresarSubGrupos(grupo, codigo_hidden, codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder registrar el Sub Grupo");
	}else{
		codigo = codigo_hidden+codigo;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/sub_grupos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Los dato fueron ingresados con exito");	
					}else{
						mostrarMensajes("error", "Disculpe los datos no fueron ingresados con exito, por favor intente de nuevo mas tarde");
					}
					consultarSubGrupos(document.getElementById('campoBuscar').value);
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("grupo="+grupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarSubGrupos");
	}
}



function consultarSubGrupos(campo_buscar){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/sub_grupos_ajax.php", true);	
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
	ajax.send("campo_buscar="+campo_buscar+"&ejecutar=consultarSubGrupos");		
}




function mostrarEditar(id_subgrupo, grupo, codigoHidden, codigo, denominacion){
	document.getElementById('id_subgrupo').value = id_subgrupo;
	document.getElementById('grupos').value = grupo;
	document.getElementById('codigoHidden').value = codigoHidden+"-";
	document.getElementById('codigoGrupo').innerHTML = codigoHidden+"-";
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('id_subgrupo').disabled = false;
	document.getElementById('grupos').disabled= false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'none';
}




function mostrarEliminar(id_subgrupo, grupo, codigoHidden, codigo, denominacion){
	document.getElementById('id_subgrupo').value = id_subgrupo;
	document.getElementById('grupos').value = grupo;
	document.getElementById('codigoHidden').value = codigoHidden+"-";
	document.getElementById('codigoGrupo').innerHTML = codigoHidden+"-";
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('id_subgrupo').disabled = true;
	document.getElementById('grupos').disabled= true;
	document.getElementById('codigo').disabled= true;
	document.getElementById('denominacion').disabled = true;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'block';
}



function actualizarFormulario(){
	document.getElementById('id_subgrupo').disabled = false;
	document.getElementById('grupos').value = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('id_subgrupo').value = "";
	document.getElementById('grupos').value= "";
	document.getElementById('codigo').value= "";
	document.getElementById('denominacion').value = "";
	document.getElementById('botonProcesar').style.display = 'block';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'none';
}



function modificarSubGrupos(id_subgrupo, grupo, codigo_hidden, codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder Modificar el Sub Grupo");
	}else{
		codigo = codigo_hidden+codigo;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/sub_grupos_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "Los datos fueron modificados con exito");	
					}else{
						mostrarMensajes("error", "Disculpe los datos no fueron modificados con exito, por favor intente de nuevo mas tarde");
					}
					consultarSubGrupos(document.getElementById('campoBuscar').value);
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_subgrupo="+id_subgrupo+"&grupo="+grupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=modificarSubGrupos");
	}
}







function eliminarSubGrupos(id_subgrupo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/sub_grupos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Los datos fueron eliminados con exito");	
				}else{
					mostrarMensajes("error", "Disculpe los datos no fueron eliminados con exito, por favor intente de nuevo mas tarde");
				}
				consultarSubGrupos(document.getElementById('campoBuscar').value);
				actualizarFormulario();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_subgrupo="+id_subgrupo+"&ejecutar=eliminarSubGrupos");		
}