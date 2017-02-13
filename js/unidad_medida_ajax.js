// JavaScript Document

function asociarUnidadDesagrega(){
		var idunidad_desagrega = document.getElementById('unidades_medida').value;
		var idunidad_medida = document.getElementById('id').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/materiales/unidad_medida_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert (ajax.responseText);
				if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el esa Unidad ya fue Incluida como desagregada en esta Unidad de Medida");
				}else{
					listarDesagregados();
					actualizarSelectDesagregar();
				}
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("idunidad_desagrega="+idunidad_desagrega+"&idunidad_medida="+idunidad_medida+"&ejecutar=asociarUnidadDesagrega");	
}


function listarDesagregados(){
	var idunidad_medida = document.getElementById('id').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/materiales/unidad_medida_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('listaUnidadesDesagregan').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idunidad_medida="+idunidad_medida+"&ejecutar=listarDesagregados");
}


function actualizarSelectDesagregar(){
	var idunidad_medida = document.getElementById('id').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/materiales/unidad_medida_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById('unidades_medida').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("idunidad_medida="+idunidad_medida+"&ejecutar=actualizarSelectDesagregar");
}



function eliminarDesagregado(iddesagrega_unidad_medida){
	
	var ajax=nuevoAjax();
	ajax.open("POST", "lib/materiales/unidad_medida_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			listarDesagregados();
			actualizarSelectDesagregar();
			document.getElementById("divCargando").style.display = "none";
		}
		
	}
	ajax.send("iddesagrega_unidad_medida="+iddesagrega_unidad_medida+"&ejecutar=eliminarUnidadDesagregada");
}


