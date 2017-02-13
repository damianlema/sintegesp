// JavaScript Document


function fdestino(){
	if(document.getElementById('formula_primaria').checked == true){
		var destino = "principal";
	}else if(document.getElementById('condicion').checked == true){
		var destino = "condicion";
	}else if(document.getElementById('entonces').checked == true){
		var destino = "entonces";
	}
	return destino;
}



function cambiarColor(id, color){
	if(id == "tdconstantes" && document.getElementById("pestana_seleccionada").value == 1){
		color = "#EAEAEA";	
	}else if(id == "tdconceptos" && document.getElementById("pestana_seleccionada").value == 2){
		color = "#EAEAEA";	
	}else if(id == "tdtablas_constantes" && document.getElementById("pestana_seleccionada").value == 3){
		color = "#EAEAEA";	
	}else if(id == "tdfunciones" && document.getElementById("pestana_seleccionada").value == 4){
		color = "#EAEAEA";	
	}else if(id == "tdhoja_tiempo" && document.getElementById("pestana_seleccionada").value == 5){
		color = "#EAEAEA";	
	}
	document.getElementById(id).bgColor = color;
}



function seleccionarPestana(id){
	document.getElementById("tdconstantes").bgColor = "#FFFFCC";
	document.getElementById("tdconceptos").bgColor = "#FFFFCC";
	document.getElementById("tdtablas_constantes").bgColor = "#FFFFCC";
	document.getElementById("tdfunciones").bgColor = "#FFFFCC";
	document.getElementById("tdhoja_tiempo").bgColor = "#FFFFCC";
	document.getElementById("div_constantes").style.display = 'none';
	document.getElementById("div_conceptos").style.display = 'none';
	document.getElementById("div_tablas_constantes").style.display = 'none';
	document.getElementById("div_funciones").style.display = 'none';
	document.getElementById("div_hoja_tiempo").style.display = 'none';
	
	if(id == "tdconstantes"){
		document.getElementById("pestana_seleccionada").value = 1;
		cambiarColor("tdconstantes", "#EAEAEA");
		document.getElementById("div_constantes").style.display = 'block';
	}else if(id == "tdconceptos"){
		document.getElementById("pestana_seleccionada").value = 2;	
		cambiarColor("tdconceptos", "#EAEAEA");
		document.getElementById("div_conceptos").style.display = 'block';
	}else if(id == "tdtablas_constantes"){
		document.getElementById("pestana_seleccionada").value = 3;
		cambiarColor("tdtablas_constantes", "#EAEAEA");
		document.getElementById("div_tablas_constantes").style.display = 'block';
	}else if(id == "tdfunciones"){
		 document.getElementById("pestana_seleccionada").value = 4;
		 cambiarColor("tdfunciones", "#EAEAEA");
		 document.getElementById("div_funciones").style.display = 'block';
	}else if(id == "tdhoja_tiempo"){
		 document.getElementById("pestana_seleccionada").value = 5;
		 cambiarColor("tdhoja_tiempo", "#EAEAEA");
		 document.getElementById("div_hoja_tiempo").style.display = 'block';
	}
}



function seleccionarColor(id, c,c2){
consultarVisor();
setTimeout("color('"+id+"', '"+c+"', '"+c2+"')", 50);	
}



function color(id, c,c2){
	if(document.getElementById('iditem_eliminar').value == 0 && c != "#FF0000"){
		document.getElementById(id).bgColor = "#FFFFFF";
		document.getElementById(id).style.color = "#000000";
	}else{
		document.getElementById(id).bgColor = c;
		document.getElementById(id).style.color = c2;	
	}
	
}



function colocarItemEliminar(id, color){
	if(document.getElementById('iditem_eliminar').value == id && color != "#FF0000"){
		document.getElementById('iditem_eliminar').value = 0;
	}else{
		document.getElementById('iditem_eliminar').value = id;	
	}
}



function ingresarValores(valor, valor_oculto){
	var error = false;
	if(valor_oculto == "FU_numerode"){
		if(document.getElementById('simbolo_numero_de').value == 0){
			mostrarMensajes("error", "Disculpe debe seleccionar un tipo de condicion para calcular el numero de parentezcos, si desea todos sin ninguna condicion seleccione la opcion todos");
			error = true;
		}
		valor += " "+document.getElementById('select_numero_de').options[document.getElementById('select_numero_de').selectedIndex].text+" "+document.getElementById('simbolo_numero_de').options[document.getElementById('simbolo_numero_de').selectedIndex].text+" "+document.getElementById('edad_numero_de').options[document.getElementById('edad_numero_de').selectedIndex].text;	
		valor_oculto += "("+document.getElementById('select_numero_de').value+"-"+document.getElementById('simbolo_numero_de').value+"-"+document.getElementById('edad_numero_de').value+")";	
	}
	
	if(valor_oculto == "FU_tiempoentrefechas"){
		valor += " ( "+document.getElementById('desde_entre_fechas').value+" , "+document.getElementById('hasta_entre_fechas').value+" )";	
		valor_oculto += "("+document.getElementById('desde_entre_fechas').value+","+document.getElementById('hasta_entre_fechas').value+")";;	
	}
	
	if(valor_oculto == "FU_diasferiadosentre"){
		valor += " ( "+document.getElementById('desde_dias_feriados').value+" , "+document.getElementById('hasta_dias_feriados').value+" )";	
		valor_oculto += "("+document.getElementById('desde_dias_feriados').value+","+document.getElementById('hasta_dias_feriados').value+")";;	
	}
	
	if(valor_oculto == "FU_ingresohasta"){
		valor += " ( "+document.getElementById('hasta_ingreso_hasta').value+" )";	
		valor_oculto += "("+document.getElementById('hasta_ingreso_hasta').value+")";;	
	}
	
	
	if(valor_oculto == "FU_mesesentre"){
		valor += " ( "+document.getElementById('desde_meses_entre').value+" , "+document.getElementById('hasta_meses_entre').value+" )";	
		valor_oculto += "("+document.getElementById('desde_meses_entre').value+","+document.getElementById('hasta_meses_entre').value+")";;	
	}
	
	
	
	if(valor_oculto == "FU_diasentre"){
		valor += " ( "+document.getElementById('desde_dias_entre').value+" , "+document.getElementById('hasta_dias_entre').value+" )";	
		valor_oculto += "("+document.getElementById('desde_dias_entre').value+","+document.getElementById('hasta_dias_entre').value+")";;	
	}
	
	
	document.getElementById('divNumeroDe').style.display = 'none';
	
	
	var destino = fdestino();
	var id_actualizar = 0;
	if(document.getElementById('iditem_eliminar').value != ""){
		id_actualizar = document.getElementById('iditem_eliminar').value;
	}
	if(valor == "+"){
		valor = "suma";	
	}
	
	partes = valor_oculto.split("_");
	if(partes[1] == "+"){
		valor_oculto = partes[0]+"_suma";	
	}
	
	
	if(error == false){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				//document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					consultarVisor();
					document.getElementById('iditem_eliminar').value = 0;
			} 
		}
		ajax.send("destino="+destino+"&id_actualizar="+id_actualizar+"&valor="+valor+"&valor_oculto="+valor_oculto+"&ejecutar=ingresarValores");
	}
}



function consultarVisor(){

	var destino = fdestino();
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				if(document.getElementById('formula_primaria').checked == true){
					document.getElementById("visor_formula").innerHTML = ajax.responseText;
				}else if(document.getElementById('condicion').checked == true){
					document.getElementById("tabla_condicion").innerHTML = ajax.responseText;
				}else if(document.getElementById('entonces').checked == true){
					document.getElementById("tabla_entonces").innerHTML = ajax.responseText;
				}
		} 
	}
	ajax.send("destino="+destino+"&ejecutar=consultarVisor");
}



function eliminarItem(){
	var destino = fdestino();
	var iditem = document.getElementById('iditem_eliminar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
				document.getElementById('iditem_eliminar').value = 0;
				consultarVisor();
		} 
	}
	ajax.send("destino="+destino+"&iditem="+iditem+"&ejecutar=eliminarItem");
}




function insertarDespues(){
	var destino = fdestino();
	var iditem = document.getElementById('iditem_eliminar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){

				partes = ajax.responseText.split("_");
				document.getElementById('iditem_eliminar').value = partes[1];
				consultarVisor();

				setTimeout('color(partes[0], "#0000FF", "#FFFFFF")', 50);
				
		} 
	}
	ajax.send("destino="+destino+"&iditem="+iditem+"&ejecutar=insertarDespues");
}



function insertarAntes(){
	var destino = fdestino();
	var iditem = document.getElementById('iditem_eliminar').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){

				partes = ajax.responseText.split("_");
				document.getElementById('iditem_eliminar').value = partes[1];
				consultarVisor();

				setTimeout('color(partes[0], "#0000FF", "#FFFFFF")', 50);
		} 
	}
	ajax.send("destino="+destino+"&iditem="+iditem+"&ejecutar=insertarAntes");
}




function limpiarDatos(){
	if(confirm("Realmente desea limpiar el editor de formula")){
		var destino = fdestino();
		//document.getElementById('visor_formula').innerHTML = '&nbsp;';
		//document.getElementById('tabla_condicion').innerHTML = '&nbsp;';
		//document.getElementById('tabla_entonces').innerHTML = '&nbsp;';
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){}
			if (ajax.readyState==4){
					consultarVisor();
			} 
		}
		ajax.send("destino="+destino+"&ejecutar=limpiarDatos");
	}
}





function ingresarConcepto(){
		if(confirm("Esta seguro que desea ingresar este concepto?")){
			var codigo = document.getElementById("codigo").value;
			var descripcion = document.getElementById("denominacion").value;
			var tipo_concepto = document.getElementById('tipo_concepto').value;
			var id_clasificador = document.getElementById('id_clasificador').value;
			var idordinal = document.getElementById('idordinal').value;
			var posicion = document.getElementById('posicion').value;
			if(document.getElementById('aplica_prestaciones').checked == true){
				var aplica_prestaciones = "si";
                var columna_prestaciones = document.getElementById('columna_prestaciones').value;
			}else{
				var aplica_prestaciones = "no";
                var columna_prestaciones = '';
			}
            /*
			if(document.getElementById('desagregar_concepto').checked ==  true){

				var desagregar_concepto = "si";
			}else{
				var desagregar_concepto = "no";
			}
			var factor_desagregacion = document.getElementById('factor_desagregacion').value;*/

			if(codigo == "" || descripcion == "" || tipo_concepto == ""){
				mostrarMensajes("error", "Disculpe debe ingresar todos los datos superiores");
			}else{
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
				}
				if (ajax.readyState==4){

						partes = ajax.responseText.split("|.|");
						//partes[0]="error";
						if(partes[0] == "error"){
							mostrarMensajes("error", partes[1]);
							id = "id"+partes[2];
							colocarItemEliminar(partes[2], "#FF0000");
							seleccionarColor(id, '#FF0000', '#FFFFFF');
							
						}else if(partes[0] == "vacio"){
							mostrarMensajes("error", partes[1]);
						}else{
							mostrarMensajes("exito", "El Concepto fue creado con Exito");
							setTimeout("window.location.href='principal.php?accion=942&modulo=13'",5000);
						}
						document.getElementById("divCargando").style.display = "none";
						
				} 
			}
			ajax.send("aplica_prestaciones="+aplica_prestaciones+"&columna_prestaciones="+columna_prestaciones+"&posicion="+posicion+"&id_clasificador="+id_clasificador+"&idordinal="+idordinal+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo_concepto="+tipo_concepto+"&ejecutar=ingresarConcepto");
			}
		}
}





function limpiarFormulario(){
	document.getElementById("codigo").value='';
	document.getElementById("idconcepto").value='';
	document.getElementById("denominacion").value='';
	document.getElementById("tipo_concepto").value='';
	document.getElementById("visor_formula").innerHTML = '&nbsp;';
	document.getElementById("tabla_condicion").innerHTML = '&nbsp;';
	document.getElementById("tabla_entonces").innerHTML = '&nbsp;';
	document.getElementById("pestana_seleccionada").value =1;
	document.getElementById("div_constantes").style.display ='block';
	document.getElementById("div_conceptos").style.display ='none';
	document.getElementById("div_tablas_constantes").style.display ='none';
	document.getElementById("div_funciones").style.display ='none';
	document.getElementById("div_hoja_tiempo").style.display ='none';
	cambiarSeleccionEditor("visor_formula");
	document.getElementById("formula_primaria").checked= true;
	document.getElementById("mensajeError").innerHTML = '&nbsp;';
	document.getElementById("boton_ingresar").style.display='block';
	document.getElementById("boton_modificar").style.display='none';
	document.getElementById('id_clasificador').value = "";
	document.getElementById('idordinal').value = "";
	document.getElementById('clasificador').value = "";
	document.getElementById('nombre_ordinal').value = "";
	//document.getElementById('desagregar_concepto').checked = false;
	//document.getElementById('factor_desagregacion').value = "";
	document.getElementById('posicion').value = "1";
	
	
}





function cambiarSeleccionEditor(td){
	document.getElementById("visor_formula").bgColor = '#CCCCCC';
	document.getElementById("tabla_condicion").bgColor = '#CCCCCC';
	document.getElementById("tabla_entonces").bgColor = '#CCCCCC';
	document.getElementById(td).bgColor = '#FFFFFF';
	
}



function consultarConcepto(idconcepto, codigo, descripcion, idtipo_concepto, id_clasificador, idordinal, partida, generica, especifica, sub_especifica, nombre_clasificador, codigo_ordinal, nombre_ordinal, posicion, aplica_prestaciones, columna_prestaciones){
	/*if(desagregar_concepto == "si"){
		document.getElementById("desagregar_concepto").checked = true;	
	}else{
		document.getElementById("desagregar_concepto").checked = false;	
	}
	*/
	if(aplica_prestaciones == "si"){
		document.getElementById('aplica_prestaciones').checked = true;
	}else{
		document.getElementById('aplica_prestaciones').checked = false;
	}
    //alert(columna_prestaciones);
    document.getElementById('columna_prestaciones').value = columna_prestaciones;
	//document.getElementById('factor_desagregacion').value = factor_desagregacion;
	//document.getElementById("divCargando").style.display = "block";
	document.getElementById("idconcepto").value= idconcepto;
	document.getElementById("codigo").value= codigo;
	document.getElementById("denominacion").value= descripcion;
	document.getElementById("tipo_concepto").value= idtipo_concepto;
	document.getElementById("boton_ingresar").style.display='none';
	document.getElementById("boton_modificar").style.display='block';
	document.getElementById('id_clasificador').value = id_clasificador;
	document.getElementById('idordinal').value = idordinal;
	document.getElementById('clasificador').value = "("+partida+"."+generica+"."+especifica+"."+sub_especifica+") "+nombre_clasificador;
	document.getElementById('nombre_ordinal').value = "("+codigo_ordinal+") "+nombre_ordinal;
	document.getElementById('posicion').value = posicion;
	consultarFormulas(idconcepto, 'principal');
	consultarFormulas(idconcepto, 'condicion');
	consultarFormulas(idconcepto, 'entonces');
	
}



function consultarFormulas(idconcepto, destino){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			if(destino == "principal"){
					document.getElementById("visor_formula").innerHTML = ajax.responseText;
				}else if(destino == "condicion"){
					document.getElementById("tabla_condicion").innerHTML = ajax.responseText;
				}else if(destino == "entonces"){
					document.getElementById("tabla_entonces").innerHTML = ajax.responseText;
				}
		document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("destino="+destino+"&idconcepto="+idconcepto+"&ejecutar=consultarFormulas");
}




function modificarConcepto(){
		if(confirm("Realmente desea Guardar los cambios realizados")){
			var idconcepto=document.getElementById("idconcepto").value;
			var codigo=document.getElementById("codigo").value;
			var descripcion=document.getElementById("denominacion").value;
			var idtipo_concepto=document.getElementById("tipo_concepto").value;
			var id_clasificador = document.getElementById('id_clasificador').value;
			var idordinal = document.getElementById('idordinal').value;
			var posicion = document.getElementById('posicion').value;

            if(document.getElementById('aplica_prestaciones').checked == true){
                var aplica_prestaciones = "si";
                var columna_prestaciones = document.getElementById('columna_prestaciones').value;
            }else{
                var aplica_prestaciones = "no";
                var columna_prestaciones = '';
            }

			/*if(document.getElementById('desagregar_concepto').checked ==  true){
				var desagregar_concepto = "si";
			}else{
				var desagregar_concepto = "no";
			}
			var factor_desagregacion = document.getElementById('factor_desagregacion').value;*/
			var ajax=nuevoAjax();
			ajax.open("POST", "modulos/nomina/lib/editor_conceptos_ajax.php", true);	
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.onreadystatechange=function() { 
				if(ajax.readyState == 1){
					document.getElementById("divCargando").style.display = "block";
				}
				if (ajax.readyState==4){
					partes = ajax.responseText.split("|.|");
					if(partes[0] == "error"){
						mostrarMensajes("error", partes[1]);
						id = "id"+partes[2];
						color(id, '#FF0000', '#FFFFFF');
						colocarItemEliminar(partes[2]);
					}else if(partes[0] == "vacio"){
							mostrarMensajes("error",partes[1] );
					}else{
						mostrarMensajes("exito", "Los cambios fueron guardados con exito");
						limpiarFormulario();
					}
					document.getElementById("divCargando").style.display = "none";
				} 
			}
            //ajax.send("aplica_prestaciones="+aplica_prestaciones+"&columna_prestaciones="+columna_prestaciones+"&posicion="+posicion+"&id_clasificador="+id_clasificador+"&idordinal="+idordinal+"&codigo="+codigo+"&descripcion="+descripcion+"&tipo_concepto="+tipo_concepto+"&ejecutar=modificarConcepto");
			ajax.send("aplica_prestaciones="+aplica_prestaciones+"&columna_prestaciones="+columna_prestaciones+"&posicion="+posicion+"&id_clasificador="+id_clasificador+"&idordinal="+idordinal+"&idconcepto="+idconcepto+"&codigo="+codigo+"&descripcion="+descripcion+"&idtipo_concepto="+idtipo_concepto+"&ejecutar=modificarConcepto");
		}
}