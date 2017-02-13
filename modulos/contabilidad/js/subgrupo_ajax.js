// JavaScript Document

function ingresarGrupo(){
	var idgrupo = document.getElementById('idgrupo').value;
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/subgrupo_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el grupo que intenta ingresar ya existe");
			}
			document.getElementById('boton_ingresar').style.display = 'block';
			document.getElementById('boton_modificar').style.display = 'none';
			document.getElementById('boton_eliminar').style.display = 'none';
			listarGrupos();
			document.getElementById('codigo').value = "";
			document.getElementById('denominacion').value= "";
			document.getElementById('idgrupo').value = 0;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idgrupo="+idgrupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarGrupo");		
}








function listarGrupos(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var grupoBuscar = document.getElementById('grupoBuscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/subgrupo_ajax.php", true);	
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
	ajax.send("grupoBuscar="+grupoBuscar+"&campoBuscar="+campoBuscar+"&ejecutar=listarGrupos");	
	return false;
}






function editarGrupo(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var idgrupo = document.getElementById('idgrupo').value;
	var idsubgrupo = document.getElementById('idsubgrupo').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/subgrupo_ajax.php", true);	
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
			document.getElementById('idgrupo').value = 0;
			document.getElementById('idsubgrupo').value = 0;
			listarGrupos();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idsubgrupo="+idsubgrupo+"&idgrupo="+idgrupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=editarGrupo");		
}




function eliminarGrupo(){
	var idsubgrupo = document.getElementById('idsubgrupo').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/subgrupo_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText=='usado'){	
				mostrarMensajes("error", "Disculpe el Sub Grupo que intenta eliminar esta siendo usado en uno o varios rubros, por lo tanto no puede ser eliminado");
			}
			document.getElementById('boton_ingresar').style.display = 'block';
			document.getElementById('boton_modificar').style.display = 'none';
			document.getElementById('boton_eliminar').style.display = 'none';
			document.getElementById('codigo').disabled = false;
			document.getElementById('denominacion').disabled = false;
			document.getElementById('idgrupo').disabled = false;
			listarGrupos();
			//desbloquearCampos();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idsubgrupo="+idsubgrupo+"&ejecutar=eliminarGrupo");		
}


function seleccinarModificar(codigo, denominacion, idgrupo, idsubgrupo){
	document.getElementById('idsubgrupo').value = idsubgrupo;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idgrupo').value = idgrupo;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function seleccinarEliminar(codigo, denominacion, idgrupo, idsubgrupo){
	document.getElementById('idsubgrupo').value = idsubgrupo;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idgrupo').value = idgrupo;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';
	
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('idgrupo').disabled = true;
}