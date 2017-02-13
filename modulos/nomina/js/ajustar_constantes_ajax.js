// JavaScript Document



function aplicar_ajuste(){
	var arreglo = new Array();
	
	var elementos = document.form_trabajadores.elements;
	for(i=0; i<elementos.length;i++){
		if(!isNaN(elementos[i].value)){
			if(elementos[i].checked == true){
				arreglo[i] = elementos[i].value;
			}
		}
	}
	
	
	var idconstante = document.getElementById("idconstante").value;
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var fecha_ajuste = document.getElementById("fecha_ajuste").value;
	var forma_fijar_valor = document.getElementById("forma_fijar_valor").value;
	var valor_ajuste = document.getElementById("valor_ajuste").value;

	var rango_desde = document.getElementById("rango_desde").value;
	var valor_rango_desde = document.getElementById("valor_rango_desde").value;

	var rango_hasta = document.getElementById("rango_hasta").value;
	var valor_rango_hasta = document.getElementById("valor_rango_hasta").value;

	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/ajustar_constantes_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			alert(ajax.responseText);
			consultarListaTrabajadores();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("arreglo="+arreglo+"&idtipo_nomina="+idtipo_nomina+"&idconstante="+idconstante+"&fecha_ajuste="+fecha_ajuste
			+"&forma_fijar_valor="+forma_fijar_valor+"&valor_ajuste="+valor_ajuste
			+"&rango_desde="+rango_desde+"&valor_rango_desde="+valor_rango_desde
			+"&rango_hasta="+rango_hasta+"&valor_rango_hasta="+valor_rango_hasta+"&ejecutar=aplicar_ajuste");	
	
}


function consultarListaTrabajadores(texto_buscar, select_buscar){
		var idtipo_nomina = document.getElementById("idtipo_nomina").value;
		var idconstante = document.getElementById("idconstante").value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/ajustar_constantes_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				document.getElementById("tabla_datos_ajuste").style.display = "block";
				document.getElementById("titulo_datos_ajuste").style.display = "block";
				document.getElementById("listaTrabajadores").style.display = "block";
				document.getElementById("listaTrabajadores").innerHTML= ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("select_buscar="+select_buscar+"&texto_buscar="+texto_buscar+"&idtipo_nomina="+idtipo_nomina+"&idconstante="+idconstante+"&ejecutar=consultarListaTrabajadores");	
}


function consultarConstantes(){
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/ajustar_constantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("celda_constante").innerHTML= ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarConstantes");	
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

function guardarValor(idtrabajador, idconstante, valor){
	var idtipo_nomina = document.getElementById("idtipo_nomina").value;
	var idconstante = document.getElementById("idconstante").value;
	var fecha_ajuste = document.getElementById("fecha_ajuste").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/ajustar_constantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			alert ("Constante ajustada de forma exitosa");

		} 
	}
	ajax.send("valor="+valor+"&idtrabajador="+idtrabajador+"&idconstante="+idconstante+"&fecha_ajuste="+fecha_ajuste+"&idtipo_nomina="+idtipo_nomina+"&ejecutar=guardarValor");
}