// JavaScript Document


function addNewRow(){
    var TABLE = document.getElementById("base");
    var TROW = document.getElementById("example");
    
    var content = TROW.getElementsByTagName("td");
    //var newRow = TABLE.insertRow(-1);
    //newRow.className = TROW.attributes['class'].value;
    //insertLOselect(content,newRow);
    
    var newRow2 = TABLE.insertRow(-1);
    newRow2.className = TROW.attributes['class'].value;
    copyRow(content,newRow2);
}
function removeLastRow() {
    var TABLE = document.getElementById("base");
    if(TABLE.rows.length > 1) {
        TABLE.deleteRow(TABLE.rows.length-1);
        //TABLE.deleteRow(TABLE.rows.length-1);
    }
}

function appendCell(Trow, txt) {
    var newCell = Trow.insertCell(Trow.cells.length)
    newCell.innerHTML = txt
	//newCell.attributes['class'].value = 'viewPropTitle'
}

function copyRow(content,Trow) {
    var cnt = 0;
    for (; cnt < content.length; cnt++) {
        appendCell(Trow, content[cnt].innerHTML);
    }
}

function insertLOselect(content,Trow) {
    var cnt = 0;
    for (; cnt < content.length-1; cnt++) {
        appendCell(Trow, '&nbsp;');
    }
    str = '<td>';
    str += ' <select id="LO" class="combo"  name="logical[]">';
    str += '    <option value="and">and</option>';
    str += '    <option value="or">or</option>';
    str += ' </select>';
    str += '</td>';
    //appendCell(Trow,str);
}


function ingresarDatos(){
	/*var descripcion = new Array();
	var valor = new Array();
	var fecha = new Array();
	var j = 0;
	var k = 0;
	var z = 0;
		formulario = document.getElementById("formulario");
		var filas = formulario.elements.length / 3;
		for(var i=0; i<formulario.elements.length; i++) {
		  var elemento = formulario.elements[i];
		  if(elemento.type == "text"){
			if((elemento.name == "descripcion" && (elemento.value != "" || formulario.elements[i+1].value != "" || formulario.elements[i+2].value != "")) || (elemento.name == "valor" && (elemento.value != "" || formulario.elements[i-1].value != "" || formulario.elements[i+1].value != "")) || (elemento.name == "fecha" && (elemento.value != "" || formulario.elements[i-1].value != "" || formulario.elements[i-2].value != ""))){
			  if(elemento.name == "descripcion") {
				descripcion[j] = elemento.value;
				//alert(descripcion[j]);
				j++;
			  }
			  if(elemento.name == "valor") {
				valor[k] = elemento.value;
				//alert(valor[k]);
				k++;
			  }
			  if(elemento.name == "fecha") {
				fecha[z] = elemento.value;
				//alert(fecha[z]);
				z++;
			  }
		  }
		 }
		}
	*/
	var idinmueble = document.getElementById('idinmueble').value;
	var tipo_inmueble = document.getElementById('tipo_inmueble').value;
	var descripcion = document.getElementById('descripcion').value;
	var valor = document.getElementById('valor').value;
	var fecha = document.getElementById('fecha').value;
	if(idinmueble == ""){
		mostrarMensajes("error", "Disculpe debe seleccionar un Inmueble");
	}else if(descripcion == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la Descripcion de la Instalacion");
		document.getElementById('descripcion').focus();
	}else if(valor == ""){
		mostrarMensajes("error", "Disculpe debe ingresar el Valor de la Instalacion");
		document.getElementById('valor').focus();
	}else if(fecha == ""){
		mostrarMensajes("error", "Disculpe debe Seleccionar la Fecha de la Instalacion");
		document.getElementById('fecha').focus();
	}else{
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/instalaciones_fijas_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Datos ingresados con exito");
				}else{
					mostrarMensajes("error", "Disculpe los datos no fueron ingresados, por favor intente de nuevo mas tarde");
				}
					
					document.getElementById("divCargando").style.display = "none";
					consultarDatos();
					document.getElementById('descripcion').value = "";
					document.getElementById('valor').value = "";
					document.getElementById('valor_mostrado').value = "";
					document.getElementById('fecha').value = "";
					
			} 
		}
		ajax.send("idinmueble="+idinmueble+"&tipo_inmueble="+tipo_inmueble+"&descripcion="+descripcion+"&valor="+valor+"&fecha="+fecha+"&ejecutar=ingresarDatos");
	}

}





function modificarDatos(){
	var idinmueble = document.getElementById('idinmueble').value;
	var idrelacion = document.getElementById('idrelacion').value;
	var tipo_inmueble = document.getElementById('tipo_inmueble').value;
	var descripcion = document.getElementById('descripcion').value;
	var valor = document.getElementById('valor').value;
	var fecha = document.getElementById('fecha').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/instalaciones_fijas_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "Los datos han sido modificados con exito");	
				}else{
					mostrarMensajes("error", "Disculpe los datos no se pudieron modificar por favor intente de nuevo mas tarde");
				}
				document.getElementById("divCargando").style.display = "none";
				consultarDatos();
				document.getElementById('descripcion').value = "";
				document.getElementById('valor').value = "";
				document.getElementById('valor_mostrado').value = "";
				document.getElementById('fecha').value = "";
				document.getElementById('botonModificar').style.display = "none";
				document.getElementById('botonGuardar').style.display = "block";
		} 
	}
	ajax.send("idrelacion="+idrelacion+"&idinmueble="+idinmueble+"&tipo_inmueble="+tipo_inmueble+"&descripcion="+descripcion+"&valor="+valor+"&fecha="+fecha+"&ejecutar=modificarDatos");	
}





function consultarDatos(){
	var idinmueble = document.getElementById('idinmueble').value;
	var tipo_inmueble = document.getElementById('tipo_inmueble').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/instalaciones_fijas_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById("listaInstalaciones").innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idinmueble="+idinmueble+"&tipo_inmueble="+tipo_inmueble+"&ejecutar=consultarDatos");
}





function eliminarInstalacion(idrelacion){
	if(confirm("Realmente desea Eliminar la Instalacion seleccionada?")){
		//var idinmueble = document.getElementById('idinmueble').value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/bienes/lib/instalaciones_fijas_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
					document.getElementById("divCargando").style.display = "none";
					consultarDatos();
			} 
		}
		ajax.send("idrelacion="+idrelacion+"&ejecutar=eliminarInstalacion");	
	}
}