// JavaScript Document


function cargarSubNiveles(idalmacen, sub_niveles){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/distribucion_almacen_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_sub_nivel').innerHTML = ajax.responseText;
				if(sub_niveles == '0'){
					document.getElementById('sub_nivel').disabled = true;	
				}else{
					document.getElementById('sub_nivel').disabled = false;		
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idalmacen="+idalmacen+"&ejecutar=cargarSubNiveles");
}



function ingresarDistribucionAlmacen(){
	var idalmacen = document.getElementById('almacen').value;
	var sub_nivel = document.getElementById('sub_nivel').value;
	var codigo = document.getElementById('codigo').value;
	var denominacion = document.getElementById('denominacion').value;
	var largo = document.getElementById('largo').value;
	var alto = document.getElementById('alto').value;
	var ancho = document.getElementById('ancho').value;
	var capacidad = document.getElementById('capacidad').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	
	if(codigo.length > 2){
		mostrarMensajes("error", "Codigo no puede sermayor a Dos Digitos");
		document.getElementById('codigo').select();
	}else{
	var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/distribucion_almacen_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				if(ajax.responseText == "existe"){
					mostrarMensajes("error", "Disculpe el Codigo Ingresado ya Existe");
				}else{
					mostrarMensajes("exito", "Los datos fueron registrados con exito");
					limpiarDatos();
					listarDistribucionAlmacen();	
				}
				
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("denominacion="+denominacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&codigo="+codigo+"&sub_nivel="+sub_nivel+"&idalmacen="+idalmacen+"&email="+email+"&telefono="+telefono+"&largo="+largo+"&alto="+alto+"&ancho="+ancho+"&capacidad="+capacidad+"&ejecutar=ingresarDistribucionAlmacen");
	}
}



function limpiarDatos(){
	document.getElementById('codigo').value = '';
	document.getElementById('denominacion').value = '';
	document.getElementById('responsable').value = '';
	document.getElementById('ci_responsable').value = '';
	document.getElementById('sub_nivel').value = 0;
	document.getElementById('telefono').value = '';
	document.getElementById('email').value = '';
	document.getElementById('largo').value = '';
	document.getElementById('alto').value = '';
	document.getElementById('ancho').value = '';
	document.getElementById('capacidad').value = '';
	document.getElementById('almacen').value = 0;
	document.getElementById('codigoDistribucion').innerHTML ='';
	document.getElementById('codigoSubNivel').innerHTML ='';
	
	document.getElementById('codigo').disabled = false;
	document.getElementById('denominacion').disabled = false;
	document.getElementById('responsable').disabled = false;
	document.getElementById('ci_responsable').disabled = false;
	document.getElementById('sub_nivel').disabled = false;
	document.getElementById('telefono').disabled = false;
	document.getElementById('email').disabled = false;
	document.getElementById('almacen').disabled = false;
	document.getElementById('largo').disabled = false;
	document.getElementById('alto').disabled = false;
	document.getElementById('ancho').disabled = false;	
	document.getElementById('capacidad').disabled = false;
	
	document.getElementById('boton_ingresar').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'none';	
}


function modificarDistribucionAlmacen(){
	
	var denominacion = document.getElementById('denominacion').value;
	var responsable = document.getElementById('responsable').value;
	var ci_responsable = document.getElementById('ci_responsable').value;
	var telefono = document.getElementById('telefono').value;
	var email = document.getElementById('email').value;
	var largo = document.getElementById('largo').value;
	var alto = document.getElementById('alto').value;
	var ancho = document.getElementById('ancho').value;
	var capacidad = document.getElementById('capacidad').value;
	var iddistribucion_almacen = document.getElementById('iddistribucion_almacen').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/distribucion_almacen_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			mostrarMensajes("exito", "Los datos fueron modificados con exito");
			limpiarDatos();
			listarDistribucionAlmacen();
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("iddistribucion_almacen="+iddistribucion_almacen+"&email="+email+"&telefono="+telefono+"&denominacion="+denominacion+"&responsable="+responsable+"&ci_responsable="+ci_responsable+"&codigo="+codigo+"&alto="+alto+"&largo="+largo+"&ancho="+ancho+"&capacidad="+capacidad+"&ejecutar=modificarDistribucionAlmacen");
}



function eliminarDistribucionAlmacen(){
	var iddistribucion_almacen = document.getElementById('iddistribucion_almacen').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/distribucion_almacen_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe No puede Eliminar esta Distribucion porque tiene areas distributivas dependientes");
				limpiarDatos();
				listarDistribucionAlmacen();
				document.getElementById("divCargando").style.display = "none";
			}
			if(ajax.responseText == "inventario"){
				mostrarMensajes("error", "Disculpe No puede Eliminar esta Distribucion porque tiene materiales en su stock");
				limpiarDatos();
				listarDistribucionAlmacen();
				document.getElementById("divCargando").style.display = "none";
			}
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Distribucion Eliminada con Exito");
				limpiarDatos();
				listarDistribucionAlmacen();
				document.getElementById("divCargando").style.display = "none";
			}
		} 
	}
	ajax.send("iddistribucion_almacen="+iddistribucion_almacen+"&ejecutar=eliminarDistribucionAlmacen");		
}



function seleccionarModificar(idalmacen, codigo, denominacion, responsable, ci_responsable, sub_nivel, telefono, email, largo, alto, ancho, capacidad, iddistribucion_almacen){
	limpiarDatos();
	document.getElementById('almacen').value = idalmacen;
	document.getElementById('almacen').disabled = true;
	document.getElementById('iddistribucion_almacen').value = iddistribucion_almacen;
	document.getElementById('codigo').value = codigo;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('responsable').value = responsable;
	document.getElementById('ci_responsable').value = ci_responsable;
	document.getElementById('sub_nivel').value = sub_nivel;
	document.getElementById('sub_nivel').disabled = true;
	document.getElementById('telefono').value = telefono;
	document.getElementById('email').value = email;
	document.getElementById('largo').value = largo;
	document.getElementById('alto').value = alto;
	document.getElementById('ancho').value = ancho;
	document.getElementById('capacidad').value = capacidad;
	document.getElementById('codigoDistribucion').innerHTML ='';
	
	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('boton_eliminar').style.display = 'none';	
}



function seleccionarEliminar(idalmacen, codigo, denominacion, responsable, ci_responsable, sub_nivel, telefono, email, largo, alto, ancho, capacidad, iddistribucion_almacen){
	document.getElementById('almacen').value = idalmacen;
	document.getElementById('almacen').disabled = true;
	document.getElementById('iddistribucion_almacen').value = iddistribucion_almacen;
	document.getElementById('codigo').value = codigo;
	document.getElementById('codigo').disabled = true;
	document.getElementById('denominacion').value = denominacion;
	document.getElementById('denominacion').disabled = true;
	document.getElementById('responsable').value = responsable;
	document.getElementById('responsable').disabled = true;
	document.getElementById('ci_responsable').value = ci_responsable;
	document.getElementById('ci_responsable').disabled = true;
	document.getElementById('sub_nivel').value = sub_nivel;
	document.getElementById('sub_nivel').disabled = true;
	document.getElementById('telefono').value = telefono;
	document.getElementById('telefono').disabled = true;
	document.getElementById('email').value = email;
	document.getElementById('email').disabled = true;
	document.getElementById('largo').value = largo;
	document.getElementById('largo').disabled = true;
	document.getElementById('alto').value = alto;
	document.getElementById('alto').disabled = true;
	document.getElementById('ancho').value = ancho;
	document.getElementById('ancho').disabled = true;
	document.getElementById('capacidad').value = capacidad;
	document.getElementById('capacidad').disabled = true;
	document.getElementById('codigoDistribucion').innerHTML ='';

	
	document.getElementById('boton_ingresar').style.display = 'none';
	document.getElementById('boton_modificar').style.display = 'none';
	document.getElementById('boton_eliminar').style.display = 'block';	
}



function listarDistribucionAlmacen(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/distribucion_almacen_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('listaDistribucionAlmacen').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ejecutar=listarDistribucionAlmacen");		
}