// JavaScript Document

function ingresarGrupo(){
	var idsubgrupo = document.getElementById('idsubgrupo').value;
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/rubro_ajax.php", true);
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
			document.getElementById('idsubgrupo').value = 0;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idsubgrupo="+idsubgrupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarGrupo");		
}








function listarGrupos(){
	var campoBuscar = document.getElementById('campoBuscar').value;
	var subgrupoBuscar = document.getElementById('subgrupoBuscar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/rubro_ajax.php", true);
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
	ajax.send("subgrupoBuscar="+subgrupoBuscar+"&campoBuscar="+campoBuscar+"&ejecutar=listarGrupos");	
	return false;
}






function editarGrupo(){
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var idsubgrupo = document.getElementById('idsubgrupo').value;
	var idrubro = document.getElementById('idrubro').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/rubro_ajax.php", true);	
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
			document.getElementById('idsubgrupo').value = 0;
			document.getElementById('idrubro').value = 0;
			listarGrupos();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idsubgrupo="+idsubgrupo+"&idrubro="+idrubro+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=editarGrupo");		
}




function eliminarGrupo(){
	var idrubro = document.getElementById('idrubro').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/contabilidad/lib/rubro_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == 'usado'){
				mostrarMensajes("error", "Disculpe el rubro que intenta eliminar ya esta siendo usado por una o varias cuentas, por lo tanto no puede ser eliminado");
			}else{
				document.getElementById('boton_ingresar').style.display = 'block';
				document.getElementById('boton_modificar').style.display = 'none';
				document.getElementById('boton_eliminar').style.display = 'none';
				document.getElementById('codigo').disabled = false;
				document.getElementById('denominacion').disabled = false;
				document.getElementById('idsubgrupo').disabled = false;
				listarGrupos();
			}
			//desbloquearCampos();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrubro="+idrubro+"&ejecutar=eliminarGrupo");		
}


function seleccinarModificar(codigo, denominacion, idsubgrupo, idrubro){
	document.getElementById('idrubro').value = idrubro;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idsubgrupo').value = idsubgrupo;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';
}


function seleccinarEliminar(codigo, denominacion, idsubgrupo, idrubro){
	document.getElementById('idrubro').value = idrubro;
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('idsubgrupo').value = idsubgrupo;
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';
	
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('idsubgrupo').disabled = true;
}