// JavaScript Document


function actualizarCentroCostos(){
	
	var ubicacion_funcional = document.getElementById('ubicacion_funcional').value;
	var idcategoria_programatica = document.getElementById('id_categoria_programatica').value;
	if(ubicacion_funcional == 0 && idcategoria_programatica == ''){
		mostrarMensajes("error", "Debe seleccionar tanto la ubicacion funcional como la categoria programatica");
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/centro_costos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
					}
			if (ajax.readyState==4){
					mostrarMensajes("exito", "Se actualizo el centro de costo con exito");
					document.getElementById('ubicacion_funcional').value = 0;
					document.getElementById('id_categoria_programatica').value = "";
					document.getElementById('nombre_categoria').value = "";
					document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idcategoria_programatica="+idcategoria_programatica+"&ubicacion_funcional="+ubicacion_funcional+"&ejecutar=actualizarCentroCostos");
	}
}







function consultarCategoria(idniveles_organizacionales){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/centro_costos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				partes = ajax.responseText.split("|.|");
				document.getElementById('nombre_categoria').value = "("+partes[0]+") "+partes[1];
				document.getElementById('id_categoria_programatica').value = partes [2];
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idniveles_organizacionales="+idniveles_organizacionales+"&ejecutar=consultarCategoria");
}