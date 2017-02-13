// JavaScript Document

function ingresarDetalle(secciones, codigo_hidden, codigo, denominacion){
	
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder registrar el Detalle");
	}else{
		codigo = codigo_hidden+codigo;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/detalles_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					consultarDetalles(document.getElementById('campoBuscar').value);
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("secciones="+secciones+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=ingresarDetalle");
	}
}



function consultarDetalles(campo_buscar){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/detalles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaDetalles').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("campo_buscar="+campo_buscar+"&ejecutar=consultarDetalles");		
}




function mostrarEditar(id_detalles, secciones, codigoHidden, codigo, denominacion){
	document.getElementById('id_detalles').value = id_detalles;
	document.getElementById('secciones').value = secciones;
	document.getElementById('codigoHidden').value = codigoHidden+"-";
	document.getElementById('codigoDetalle').innerHTML = codigoHidden+"-";
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('id_detalles').disabled = false;
	document.getElementById('secciones').disabled= false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'block';
	document.getElementById('botonEliminar').style.display = 'none';
}




function mostrarEliminar(id_detalles, secciones, codigoHidden, codigo, denominacion){
	document.getElementById('id_detalles').value = id_detalles;
	document.getElementById('secciones').value = secciones;
	document.getElementById('codigoHidden').value = codigoHidden+"-";
	document.getElementById('codigoDetalle').innerHTML = codigoHidden+"-";
	document.getElementById('codigo').value = codigo;
	document.getElementById('denominacion').value = denominacion;
	
	document.getElementById('id_detalles').disabled = true;
	document.getElementById('secciones').disabled= true;
	document.getElementById('codigo').disabled= true;
	document.getElementById('denominacion').disabled = true;
	
	document.getElementById('botonProcesar').style.display = 'none';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'block';
}



function actualizarFormulario(){
	document.getElementById('id_detalles').disabled = false;
	document.getElementById('secciones').value = false;
	document.getElementById('codigo').disabled= false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('id_detalles').value = "";
	document.getElementById('secciones').value= "";
	document.getElementById('codigo').value= "";
	document.getElementById('denominacion').value = "";

	document.getElementById('botonProcesar').style.display = 'block';
	document.getElementById('botonModificar').style.display = 'none';
	document.getElementById('botonEliminar').style.display = 'none';
}



function modificarDetalles(id_detalles, secciones, codigo_hidden, codigo, denominacion){
	if(codigo == "" || denominacion == ""){
		mostrarMensajes("error", "Disculpe debe completar todos los campos para poder modificar el Detalle");
	}else{
		codigo = codigo_hidden+codigo;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/detalles_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					consultarDetalles(document.getElementById('campoBuscar').value);
					actualizarFormulario();
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id_detalles="+id_detalles+"&secciones="+secciones+"&codigo="+codigo+"&denominacion="+denominacion+"&ejecutar=modificarDetalle");		
	}
}







function eliminarDetalles(id_detalles){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/detalles_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "Detalles Eliminados con Exito");
				consultarDetalles(document.getElementById('campoBuscar').value);
				actualizarFormulario();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("id_detalles="+id_detalles+"&ejecutar=eliminarDetalles");		
}