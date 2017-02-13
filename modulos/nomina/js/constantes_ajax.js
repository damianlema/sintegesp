// JavaScript Document



function ingresarConstante(){
	var codigo = document.getElementById('codigo').value;
	var abreviatura = document.getElementById('abreviatura').value;
	var descripcion = document.getElementById('descripcion').value;
	var unidad = document.getElementById('unidad').value;
	var tipo = document.getElementById('tipo').value;
	var equivalencia = document.getElementById('equivalencia').value;
	var maximo = document.getElementById('maximo').value;
	var valor = document.getElementById('valor').value;
	var id_clasificador = document.getElementById('id_clasificador').value;
	var idordinal = document.getElementById('idordinal').value;
	var afecta = document.getElementById('afecta').value;
	var posicion = document.getElementById('posicion').value;
	
	if(document.getElementById("mostrar").checked == true){
		mostrar= "si";	
	}else{
		mostrar = "no";	
	}
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/constantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "La constante fue ingresada con exito");
				limpiarCampos();
				actualizarLista();
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("posicion="+posicion+"&afecta="+afecta+"&mostrar="+mostrar+"&id_clasificador="+id_clasificador+"&idordinal="+idordinal+"&codigo="+codigo+"&abreviatura="+abreviatura+"&descripcion="+descripcion+"&unidad="+unidad+"&tipo="+tipo+"&equivalencia="+equivalencia+"&maximo="+maximo+"&valor="+valor+"&ejecutar=ingresarConstante");
	//return false;
}





function modificarConstante(){
	var idconstante_nomina = document.getElementById('idconstante_nomina').value;
	var codigo = document.getElementById('codigo').value;
	var abreviatura = document.getElementById('abreviatura').value;
	var descripcion = document.getElementById('descripcion').value;
	var unidad = document.getElementById('unidad').value;
	var tipo = document.getElementById('tipo').value;
	var equivalencia = document.getElementById('equivalencia').value;
	var maximo = document.getElementById('maximo').value;
	var valor = document.getElementById('valor').value;
	var id_clasificador = document.getElementById('id_clasificador').value;
	var idordinal = document.getElementById('idordinal').value;
	var afecta = document.getElementById('afecta').value;
	var posicion = document.getElementById('posicion').value;
	
	if(document.getElementById("mostrar").checked == true){
		mostrar= "si";	
	}else{
		mostrar = "no";	
	}
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/constantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "La constante fue modificada con exito");
				limpiarCampos();
				document.getElementById('boton_ingresar').style.display = "block";
				document.getElementById('boton_eliminar').style.display = "none";
				document.getElementById('boton_modificar').style.display = "none";
				actualizarLista();
				//document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("posicion="+posicion+"&afecta="+afecta+"&mostrar="+mostrar+"&id_clasificador="+id_clasificador+"&idordinal="+idordinal+"&idconstante_nomina="+idconstante_nomina+"&codigo="+codigo+"&abreviatura="+abreviatura+"&descripcion="+descripcion+"&unidad="+unidad+"&tipo="+tipo+"&equivalencia="+equivalencia+"&maximo="+maximo+"&valor="+valor+"&ejecutar=modificarConstante");
	//return false;
}




function eliminarConstante(){
	var idconstantes_nomina = document.getElementById('idconstante_nomina').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/constantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "asociada_trabajador"){
					mostrarMensajes("error", "Disculpe esta constante no se puede eliminar porque esta asociada a un trabajador");
					document.getElementById("divCargando").style.display = "none";
				}else if(ajax.responseText == "asociada_concepto"){	
					mostrarMensajes("error", "Disculpe esta constante no se puede eliminar porque se esta usando para la elaboracion de un Concepto");
					document.getElementById("divCargando").style.display = "none";
				}else{
					mostrarMensajes("exito", "La constante fue eliminada con exito");
					limpiarCampos();
					document.getElementById('boton_ingresar').style.display = "block";
					document.getElementById('boton_eliminar').style.display = "none";
					document.getElementById('boton_modificar').style.display = "none";
					actualizarLista();	
				}
				
				
		} 
	}
	ajax.send("idconstantes_nomina="+idconstantes_nomina+"&ejecutar=eliminarConstante");
		
}








function actualizarLista(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/constantes_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('lista_constantes').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=actualizarLista");
		
}





function limpiarCampos(){
	document.getElementById('codigo').value = "";
	document.getElementById('abreviatura').value = "";
	document.getElementById('descripcion').value = "";
	document.getElementById('unidad').value = "";
	document.getElementById('tipo').value = "";
	document.getElementById('equivalencia').value = "";
	document.getElementById('maximo').value = "";
	document.getElementById('valor').value = "";
	document.getElementById('idconstante_nomina').value = "";
	document.getElementById('mostrar').checked = false;
	document.getElementById('afecta').value = '0';
	document.getElementById('buscarClasificador').style.display='block';
	document.getElementById('buscarOrdinal').style.display='block';
	
	document.getElementById('id_clasificador').value = "";
	document.getElementById('idordinal').value = "";
	document.getElementById('clasificador').value = "";
	document.getElementById('nombre_ordinal').value = "";
	document.getElementById('posicion').value = "1";
	
	
	document.getElementById('boton_ingresar').style.display = "block";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "none";	
}



function mostrarModificar(idconstantes_nomina, codigo, abreviatura, descripcion, unidad, tipo, equivalencia, maximo, valor, id_clasificador, idordinal, nombre_clasificador, nombre_ordinal, mostrar, asociado, afecta, posicion){
	document.getElementById('codigo').value = codigo;
	document.getElementById('abreviatura').value = abreviatura;
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('unidad').value = unidad;
	document.getElementById('tipo').value = tipo;
	document.getElementById('equivalencia').value = equivalencia;
	document.getElementById('maximo').value = maximo;
	document.getElementById('valor').value = valor;
	document.getElementById('idconstante_nomina').value = idconstantes_nomina;
	document.getElementById('id_clasificador').value = id_clasificador;
	document.getElementById('idordinal').value = idordinal;
	document.getElementById('clasificador').value = nombre_clasificador;
	document.getElementById('nombre_ordinal').value = nombre_ordinal;
	document.getElementById('afecta').value = afecta;
	
	document.getElementById('posicion').value = posicion;
	if(afecta == "Deduccion"){
		document.getElementById('buscarClasificador').style.display='none';
		document.getElementById('buscarOrdinal').style.display='none';
		document.getElementById('id_clasificador').value = "";
		document.getElementById('idordinal').value = "";
		document.getElementById('clasificador').value = "";
		document.getElementById('nombre_ordinal').value = "";
	}else{
		document.getElementById('buscarClasificador').style.display='block';
		document.getElementById('buscarOrdinal').style.display='block';
	}
	
	if(mostrar == "si"){
		document.getElementById('mostrar').checked = true;	
	}else{
		document.getElementById('mostrar').checked = false;
	}
	
	
	if(asociado == "no"){
		document.getElementById("valor").disabled=false;	
	}else{
		document.getElementById("valor").disabled=true;	
	}
	

	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "none";
	document.getElementById('boton_modificar').style.display = "block";
	
}



function mostrarEliminar(idconstantes_nomina, codigo, abreviatura, descripcion, unidad, tipo, equivalencia, maximo, valor, id_clasificador, idordinal, nombre_clasificador, nombre_ordinal, mostrar,asociado, afecta, posicion){
	document.getElementById('codigo').value = codigo;
	document.getElementById('abreviatura').value = abreviatura;
	document.getElementById('descripcion').value = descripcion;
	document.getElementById('unidad').value = unidad;
	document.getElementById('tipo').value = tipo;
	document.getElementById('equivalencia').value = equivalencia;
	document.getElementById('maximo').value = maximo;
	document.getElementById('valor').value = valor;
	document.getElementById('idconstante_nomina').value = idconstantes_nomina;
	document.getElementById('id_clasificador').value = id_clasificador;
	document.getElementById('idordinal').value = idordinal;
	document.getElementById('clasificador').value = nombre_clasificador;
	document.getElementById('nombre_ordinal').value = nombre_ordinal;
	document.getElementById('posicion').value = posicion;
	if(mostrar == "si"){
		document.getElementById('mostrar').checked = true;	
	}else{
		document.getElementById('mostrar').checked = false;
	}
	
	if(asociado == "no"){
		document.getElementById("valor").disabled=false;	
	}else{
		document.getElementById("valor").disabled=true;	
	}
	
	document.getElementById('afecta').value = afecta;
	if(afecta == "Deduccion"){
		document.getElementById('buscarClasificador').style.display='none';
		document.getElementById('buscarOrdinal').style.display='none';
		document.getElementById('id_clasificador').value = "";
		document.getElementById('idordinal').value = "";
		document.getElementById('clasificador').value = "";
		document.getElementById('nombre_ordinal').value = "";
	}else{
		document.getElementById('buscarClasificador').style.display='block';
		document.getElementById('buscarOrdinal').style.display='block';
	}
	document.getElementById('boton_ingresar').style.display = "none";
	document.getElementById('boton_eliminar').style.display = "block";
	document.getElementById('boton_modificar').style.display = "none";
	
}







function procesoPorLote(){
	if(confirm("Seguro desea procesar este aumento para todos los trabajadores que tengas asociada esta constante?")){
		var tipo = document.getElementById("tipo_proceso_lote").value;
		var valor = document.getElementById("monto_lote").value;
		var rango_entre = document.getElementById("rango_entre").value;
		var rango_hasta = document.getElementById("rango_hasta").value;
		var tipo_nomina = document.getElementById("tipo_nomina").value;
		var idconstante = document.getElementById("idconstante_procesar_lote").value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/constantes_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					alert(ajax.responseText);
					if(tipo == "porcentual"){
						var mostrar = "Porciento";	
					}else{
						var mostrar = "Bsf en Valor Fijo";
					}
					mostrarMensajes("exito", "El proceso por lote se ha realizado con exito, se le ha aumentado "+valor+" "+mostrar+" al valor que contenga esta constante para todos los trabajadores que la tengan asociada");
					//document.getElementById('lista_constantes').innerHTML = ajax.responseText;
					document.getElementById("divCargando").style.display = "none";
					document.getElementById('div_proceso_lote').style.display='none';
			} 
		}
		ajax.send("tipo_nomina="+tipo_nomina+"&rango_entre="+rango_entre+"&rango_hasta="+rango_hasta+"&valor="+valor+"&idconstante="+idconstante+"&tipo="+tipo+"&ejecutar=procesoPorLote");
	}
}