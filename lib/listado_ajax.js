
function seleccionarNivel(idorganizacion){
	var ajax=nuevoAjax();
	idcampo = 'nivel_organizacion';
	ajax.open("POST", "listado_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
				document.getElementById('celda_nivel_organizacion').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&idcampo="+idcampo+"&ejecutar=seleccionarNivel");
}

