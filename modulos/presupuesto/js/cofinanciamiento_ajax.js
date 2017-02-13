// JavaScript Document


function ingresarCofinanciamiento(){
	var denominacion = document.getElementById('denominacion').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/cofinanciamiento_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display 	= "block";
				}
			if (ajax.readyState==4){
				document.getElementById("idcofinanciamiento").value 	= ajax.responseText;
				document.getElementById('tabla_fuentes').style.display  = 'block';
				document.getElementById("divCargando").style.display 	= "none";
			} 
		}
	ajax.send("denominacion="+denominacion+"&ejecutar=ingresarCofinanciamiento");	
}


function incluirFinancimiento(){
	var idfuente_financiamiento = document.getElementById('fuente_financiamiento').value;
	var porcentaje 				= document.getElementById('porcentaje_fuente_financiamiento').value;
	var idcofinanciamiento 		= document.getElementById('idcofinanciamiento').value;
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/cofinanciamiento_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display 				= "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe la fuente de financiamiento que intenta ingresar ya existe");
				}else if(ajax.responseText == "mayor"){
					mostrarMensajes("error", "Disculpe el porcentaje total de todas las fuentes de financiamiento no puede ser mayor al 100%, y actualmente con el monto que esta ingresando supera este porcentaje, por favor revise y vuelva aintentarlo");
				}else{
					//alert(ajax.responseText);
					if(ajax.responseText == "0"){
						mostrarMensajes("exito", "La fuente de financiamiento ah sido registrada con exito, <strong style='color:#990000'>YA ASIGNO EL 100% DE LAS FUENTES DE FINANCIAMIENTO</strong>");
					}else{
						mostrarMensajes("exito", "La fuente de financiamiento ah sido registrada con exito, <strong style='color:#990000'>AUN LE RESTA "+ajax.responseText+"% POR ASIGNAR</strong>");	
					}
					
				}
				
				document.getElementById('fuente_financiamiento').value 				= 0;
				document.getElementById('porcentaje_fuente_financiamiento').value 	= '';
				document.getElementById("divCargando").style.display 				= "none";
				listarFuentes(idcofinanciamiento);
			} 
		}
	ajax.send("porcentaje="+porcentaje+"&idcofinanciamiento="+idcofinanciamiento+"&idfuente_financiamiento="+idfuente_financiamiento+"&ejecutar=incluirFinancimiento");		
}




function listarFuentes(idcofinanciamiento){
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/cofinanciamiento_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display 				= "block";
				}
			if (ajax.readyState==4){
				document.getElementById('listaFuentesSeleccionadas').innerHTML 	= ajax.responseText;
				document.getElementById("divCargando").style.display 				= "none";
			} 
		}
	ajax.send("idcofinanciamiento="+idcofinanciamiento+"&ejecutar=listarFuentes");			
}


function eliminarFinanciamiento(idfuentes_cofinanciamiento){
		if(confirm("Seguro desea eliminar la fuente de financiamiento seleccionada")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/cofinanciamiento_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display 				= "block";
				}
			if (ajax.readyState==4){
				listarFuentes(document.getElementById('idcofinanciamiento').value);
				document.getElementById("divCargando").style.display 				= "none";
			} 
		}
	ajax.send("idfuentes_cofinanciamiento="+idfuentes_cofinanciamiento+"&ejecutar=eliminarFinanciamiento");		
		}
}




function consultarCofinanciamiento(idcofinanciamiento){
	
				listarFuentes(idcofinanciamiento);
}