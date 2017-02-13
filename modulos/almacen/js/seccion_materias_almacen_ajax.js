// JavaScript Document

function ingresarSecciones(subgrupo, codigo_hidden, codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder registrar la Seccion");
	}else{
		codigo = codigo_hidden+codigo;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/seccion_materias_almacen_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText == "exito"){
						mostrarMensajes("exito", "La seccion se ingreso con exito");	
					}
					if(ajax.responseText == "existe"){
						mostrarMensajes("error", "Disculpe el Detalle que intenta ingresar ya existe");
					}
					if(ajax.responseText == "fallo"){
						mostrarMensajes("error", "Disculpe los datos no fueron registrados con exito, por favor intente de nuevo");
					}
					consultarSecciones(document.getElementById('campoBuscar').value);
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("subgrupo="+subgrupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarSecciones");
	}
}



function consultarSecciones(campo_buscar){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/seccion_materias_almacen_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaSecciones').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("campo_buscar="+campo_buscar+"&ejecutar=consultarSecciones");		
}




function mostrarEditar(id_secciones, subgrupos, codigoHidden, codigo, denominacion){
	document.getElementById('id_seccion').value = id_secciones;
	document.getElementById('subgrupos').value = subgrupos;
	document.getElementById('codigoHidden').value = codigoHidden+"-";
	document.getElementById('codigoSubGrupo').innerHTML = codigoHidden+"-";
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('id_seccion').disabled = false;
	document.getElementById('subgrupos').disabled= false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'none';
}




function mostrarEliminar(id_seccion, subgrupos, codigoHidden, codigo, denominacion){
	document.getElementById('id_seccion').value = id_seccion;
	document.getElementById('subgrupos').value = subgrupos;
	document.getElementById('codigoHidden').value = codigoHidden+"-";
	document.getElementById('codigoSubGrupo').innerHTML = codigoHidden+"-";
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('id_seccion').disabled = true;
	document.getElementById('subgrupos').disabled= true;
	document.getElementById('codigo').disabled= true;
	document.getElementById('denominacion').disabled = true;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'block';
}



function actualizarFormulario(){
	document.getElementById('id_seccion').disabled = false;
	document.getElementById('subgrupos').value = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('id_seccion').value = "";
	document.getElementById('subgrupos').value= "";
	document.getElementById('codigo').value= "";
	document.getElementById('denominacion').value = "";
	document.getElementById('botonProcesar').style.display = 'block';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'none';
}



function modificarSecciones(id_secciones, subgrupo, codigo_hidden, codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder Modificar la Seccion");
	}else{
		codigo = codigo_hidden+codigo;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/seccion_materias_almacen_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					if(ajax.responseText =="exito"){
						mostrarMensajes("exito", "Los datos han sido modificados con exito");	
					}else{
						mostrarMensajes("error", "Disculpe los datos no fueron modificados con exito, por favbor intente de nuevo mas tarde");
					}
					consultarSecciones(document.getElementById('campoBuscar').value);
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_secciones="+id_secciones+"&subgrupo="+subgrupo+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=modificarSecciones");
	}
}







function eliminarSecciones(id_secciones){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/seccion_materias_almacen_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText =="exito"){
					mostrarMensajes("exito", "Los datos fueron eliminados con Exito");	
				}else{
					mostrarMensajes("error", "Disculpe los datos no fueron eliminados, por favor intente de nuevo mas tarde");	
				}
				consultarSecciones(document.getElementById('campoBuscar').value);
				actualizarFormulario();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_secciones="+id_secciones+"&ejecutar=eliminarSecciones");		
}