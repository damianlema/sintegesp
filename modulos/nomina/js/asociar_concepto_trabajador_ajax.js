// JavaScript Document



function sosciarConceptoConstante(){
	var idtrabajador = document.getElementById("id_trabajador").value;
	var idconcepto_constante = document.getElementById("id_concepto_constante").value;
	var tabla = document.getElementById('tabla').value;
	var valor_fijo = document.getElementById('valor_fijo').value;
	var tipo_nomina = document.getElementById("tipo_nomina").value;
	var tipo_asociacion = document.getElementById("tipo_asociacion").value;
	var fecha_ejecutar_desde = document.getElementById('fecha_ejecutar_desde').value;
    var fecha_ejecutar_hasta = document.getElementById('fecha_ejecutar_hasta').value;
	var ajax=nuevoAjax();
	if(idtrabajador == "" && tipo_asociacion == 'individual'){
		mostrarMensajes("error", "Disculpe debe seleccionar un trabajador para asociarle el concepto o la constante");
	}else if(idconcepto_constante == ""){
		mostrarMensajes("error", "Disculpe debe seleccionar un concepto o una constante a asociar");
	}else{
		ajax.open("POST", "modulos/nomina/lib/asociar_concepto_trabajador_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el concepto o constante ya esta asociada a este trabajador");
				}else if(ajax.responseText=="no_nomina"){
					mostrarMensajes("error", "Disculpe el tipo de nomina seleccionado no esta asociado a este trabajador, debe asociarle primero el tipo de nomina al trabajador, para luego asociarle conceptos que se paguen con este tipo de nomina");
				}
				document.getElementById("id_concepto_constante").value = '';
				document.getElementById('tabla').value = '';
				document.getElementById('valor_fijo').value = '';
				document.getElementById('concepto_constante').value = '';
				document.getElementById('valor_fijo').style.display = 'none';
				document.getElementById('texto_valor_fijo').innerHTML = '';
                document.getElementById('fecha_ejecutar_desde').value = '';
                document.getElementById('fecha_ejecutar_hasta').value = '';
				
				if(tipo_asociacion == 'individual'){
					consultarAsociados();
				}else{
					mostrarMensajes("error", "El concepto seleccionado se le a asociado a todos los trabajadores dependientes de la nomina seleccionada, en total: "+ajax.responseText+" Trabajadores");
					document.getElementById("divCargando").style.display = "none";
				}
			} 
		}
		ajax.send("fecha_ejecutar_desde="+fecha_ejecutar_desde+"&fecha_ejecutar_hasta="+fecha_ejecutar_hasta+"&tipo_asociacion="+tipo_asociacion+"&tipo_nomina="+tipo_nomina+"&idtrabajador="+idtrabajador+"&idconcepto_constante="+idconcepto_constante+"&tabla="+tabla+"&valor_fijo="+valor_fijo+"&ejecutar=sosciarConceptoConstante");
	}
}





function consultarAsociados(){
	var idtrabajador = document.getElementById("id_trabajador").value;
	var tipo_nomina = document.getElementById("tipo_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/asociar_concepto_trabajador_ajax.php", true);	
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
	ajax.send("tipo_nomina="+tipo_nomina+"&idtrabajador="+idtrabajador+"&ejecutar=consultarAsociados");		
}




function probarFormula(){
	var idtrabajador = document.getElementById("id_trabajador").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/asociar_concepto_trabajador_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			mostrarMensajes("error", "EL total es: "+ajax.responseText);
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=probarFormula");
}





function eliminarAsociacion(idreacion){
	//var idtrabajador = document.getElementById("id_trabajador").value;
	if(confirm("Realmente desea eliminar esta asociacion del concepto al trabajador?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/asociar_concepto_trabajador_ajax.php", true);	
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
		ajax.send("idreacion="+idreacion+"&ejecutar=eliminarAsociacion");
	}
}




function modificarValor(id, valor){

    var ajax=nuevoAjax();
    ajax.open("POST", "modulos/nomina/lib/asociar_concepto_trabajador_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange=function() {
        if(ajax.readyState == 1){
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState==4){

            consultarAsociados();
            //document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("valor="+valor+"&id="+id+"&ejecutar=modificarValor");
}
