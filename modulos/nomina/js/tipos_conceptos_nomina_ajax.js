// JavaScript Document



function ingresarConcepto(){
	var codigo = document.getElementById('codigo').value;
	var descripcion = document.getElementById('descripcion').value;
	var afecta = document.getElementById('afecta').value;

	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_conceptos_nomina_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "El tipo de Afectacion se agrego con exito");
				limpiarCampos();
				actualizarLista();
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("afecta="+afecta+"&codigo="+codigo+"&descripcion="+descripcion+"&ejecutar=ingresarConcepto");
	//return false;
}





function modificarConcepto(){
	var idconcepto_nomina = document.getElementById('idconcepto_nomina').value;
	var codigo = document.getElementById('codigo').value;
	var descripcion = document.getElementById('descripcion').value;
	var afecta = document.getElementById('afecta').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_conceptos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "El tipo de afectacion se modifico con exito");
				limpiarCampos();
				document.getElementById('boton_ingresar').style.display = "block";
				document.getElementById('boton_eliminar').style.display = "none";
				document.getElementById('boton_modificar').style.display = "none";
				actualizarLista();
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("afecta="+afecta+"&idconcepto_nomina="+idconcepto_nomina+"&codigo="+codigo+"&descripcion="+descripcion+"&ejecutar=modificarConcepto");
	//return false;
}




function eliminarConcepto(){
	var idconcepto_nomina = document.getElementById('idconcepto_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_conceptos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "relacionado"){
					mostrarMensajes("error", "Disculpe este tipo de afectacion no uede ser eliminado, ya que un concepto lo esta usando");
					document.getElementById("divCargando").style.display = "none";
				}else{
					mostrarMensajes("exito", "El tipo de afectacion se elimino con exito");
					limpiarCampos();
					document.getElementById('boton_ingresar').style.display = "block";
					document.getElementById('boton_eliminar').style.display = "none";
					document.getElementById('boton_modificar').style.display = "none";
					actualizarLista();	
				}
				
		} 
	}
	ajax.send("idconcepto_nomina="+idconcepto_nomina+"&ejecutar=eliminarConcepto");
		
}








function actualizarLista(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/tipos_conceptos_nomina_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('lista_conceptos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=actualizarLista");
		
}





function limpiarCampos(){
	document.getElementById('codigo').value = "";
	document.getElementById('descripcion').value = "";
	//document.getElementById('idconcepto_nomina').value = "";
	
	document.getElementById('boton_ingresar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";	
}



function mostrarModificar(idconceptos_nomina, codigo, descripcion, afecta){
	document.getElementById('codigo').value = codigo;
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idconcepto_nomina').value = idconceptos_nomina;
	document.getElementById('afecta').value = afecta;
	
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	
}



function mostrarEliminar(idconceptos_nomina, codigo, descripcion, afecta){
	document.getElementById('codigo').value = codigo;
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('idconcepto_nomina').value = idconceptos_nomina;
	document.getElementById('afecta').value = afecta;
	
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
	document.getElementById('boton_modificar').style.display = "none";
	
}