function consultarConceptos(idtipo_nomina){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/asociar_conceptos_desde_tipo_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById('celda_concepto').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_nomina="+idtipo_nomina+"&ejecutar=consultarConceptos");
}