// JavaScript Document



function asociarTipoNomina(){
	var arreglo = new Array();
	if(document.getElementById("tipo_asociacion").value=="global"){
		var elementos = document.form_trabajadores.elements;
		for(i=0; i<elementos.length;i++){
			if(!isNaN(elementos[i].value)){
				if(elementos[i].checked == true){
				arreglo[i] = elementos[i].value;
				}
			}
		}
	}
	
	var idtrabajador = document.getElementById("id_trabajador").value;
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	if(idtipo_nomina == 0){
		mostrarMensajes("error", "Disculpe seleccione un tipo de nomina");
	}else if(idtrabajador == "" && document.getElementById("tipo_asociacion").value=="individual"){
		mostrarMensajes("error", "Disculpe seleccione un Trabajador");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/asociar_tipo_nomina_trabajador_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){

				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el Tipo de Nomina seleccionada ya se le agrego a este trabajador");
				}
				document.getElementById('listaTrabajadores').style.display = 'none';
				if(document.getElementById("tipo_asociacion").value=="global"){
					mostrarMensajes("exito", "Los trabajadores Seleccionados se le ha asociado el tipo de nomina con exito");
					window.location.href = 'principal.php?accion=955&modulo=13';
				}else{
					consultarAsociados();
				}
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("arreglo="+arreglo+"&idtipo_nomina="+idtipo_nomina+"&idtrabajador="+idtrabajador+"&ejecutar=asociarTipoNomina");	
	}
}




function consultarAsociados(){
	var idtrabajador = document.getElementById("id_trabajador").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/asociar_tipo_nomina_trabajador_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("lista_conceptos_constantes").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarAsociados");		
}



function activar_desactivar(id){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/asociar_tipo_nomina_trabajador_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				consultarAsociados();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id="+id+"&ejecutar=activar_desactivar");
}



function eliminarAsociacion(id){
	if(confirm("Seguro desea Eliminar esta Asociacion?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/asociar_tipo_nomina_trabajador_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				consultarAsociados();
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("id="+id+"&ejecutar=eliminarAsociacion");
	}
}






function consultarListaTrabajadores(texto_buscar, select_buscar){
		var idtipo_nomina = document.getElementById("idtipo_nomina").value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/asociar_tipo_nomina_trabajador_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				document.getElementById("listaTrabajadores").style.display = "block";
				document.getElementById("listaTrabajadores").innerHTML= ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("select_buscar="+select_buscar+"&texto_buscar="+texto_buscar+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarListaTrabajadores");	
}







function seleccionarTodo(){
	var elementos = document.form_trabajadores.elements;
	if(document.getElementById("sel_todos").checked == true){
		for(i=0;i<elementos.length;i++){
			elementos[i].checked = true;
		}
	}else{
		for(i=0;i<elementos.length;i++){
			elementos[i].checked = false;
		}	
	}
	
}