// JavaScript Document


// *****************************************************************************************************************************
// ***************************** ACTUALIZAR LOS SELECT LUEGO DE INGRESAR ALGUN ITEM DE FORMA DINAMICA **************************
// *****************************************************************************************************************************
function cargarSelect(sel, divHijo, denominacion){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById(divHijo).innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			}
			
		}
		ajax.send("divHijo="+divHijo+"&denominacion="+denominacion+"&sel="+sel+"&ejecutar=cargarSelect");
}


// *****************************************************************************************************************************
// ***************************** MOSTRAR LAS PESTAÑAS **************************************************************************
// *****************************************************************************************************************************
function mostrarPestanas(){
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById('tabsF').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("ejecutar=mostrarPestanas");		
}





// *****************************************************************************************************************************
// ***************************** ABRIR LISTA PARA SELECCIONAR UN MATERIAL ******************************************************
// *****************************************************************************************************************************
function abreVentana(){
	miPopup=window.open("lib/listas/lista_materia.php?frm=datos_basicos", "materias","width=900,height=400,scrollbars=yes")
	miPopup.focus()
}
function abreVentanaReemplazo(){
	miPopup=window.open("lib/listas/lista_materia.php?frm=reemplazos", "materias","width=900,height=400,scrollbars=yes")
	miPopup.focus()
}
function abreVentanaEquivalencia(){
	miPopup=window.open("lib/listas/lista_materia.php?frm=equivalencias", "materias","width=900,height=400,scrollbars=yes")
	miPopup.focus()
}
function abreVentanaAccesorios(){
	miPopup=window.open("lib/listas/lista_materia.php?frm=accesorios", "materias","width=900,height=400,scrollbars=yes")
	miPopup.focus()
}
// *****************************************************************************************************************************
// ***************************** AUTOGENERAR CODIGO ****************************************************************************
// *****************************************************************************************************************************
function generar_codigo(){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			partes = ajax.responseText.split("|.|");
			document.getElementById("idgenerar_codigo").style.display		='none';
			document.getElementById('codigo_materia').value = partes[1];
			document.getElementById('codigo_materia_automatico').value = partes[0];
		} 
	}
	ajax.send("ejecutar=generar_codigo");			
}

// *****************************************************************************************************************************
// ***************************** CARGAR TIPO DE DETALLE ************************************************************************
// *****************************************************************************************************************************
function cargarTipo(iddetalle, idtipo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
				document.getElementById('celda_tipo_detalle').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("iddetalle="+iddetalle+"&idtipo="+idtipo+"&ejecutar=cargarTipo");	
}

// *****************************************************************************************************************************
// ***************************** DAR FORMATO AL NUMERO *************************************************************************
// *****************************************************************************************************************************
function formatoNumero(idcampo) {
var res =  document.getElementById(idcampo).value; 
document.getElementById("id"+idcampo).value = res;
if (document.getElementById(idcampo).value > 0 && document.getElementById(idcampo).value < 99999999999)  { 
	resultado = parseFloat(res).toFixed(2).toString();
	resultado = resultado.split(".");
	var cadena = ""; cont = 1
	for(m=resultado[0].length-1; m>=0; m--){
		cadena = resultado[0].charAt(m) + cadena
		cont%3 == 0 && m >0 ? cadena = "." + cadena : cadena = cadena
		cont== 3 ? cont = 1 :cont++
	}
	document.getElementById(idcampo).value = cadena + "," + resultado[1]; 
} else { 
	document.getElementById(idcampo).value = 0;
	document.getElementById("id"+idcampo).value = 0;
	alert ("Debes indicar valores numericos en el campo "+idcampo);
	document.getElementById(idcampo).focus();
} 
}

//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** DATOS BASICOS ****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************



function ingresarMateria(){
	var idtipo_movimiento_almacen 	= document.getElementById("tipo_movimiento_almacen").value;
	var codigo_materia				= document.getElementById("codigo_materia").value;
	var codigo_materia_automatico 	= document.getElementById("codigo_materia_automatico").value;
	var descripcion_materia			= document.getElementById("descripcion_materia").value;
	var idunidad_medida				= document.getElementById("unidad_medida").value;
	var cantidad_unidad				= document.getElementById("cantidad_unidad").value;
	var iddetalle_materias_almacen	= document.getElementById("iddetalle_materias_almacen").value;
	var idtipo_detalle_almacen		= document.getElementById("idtipo_detalle").value;
	var idmarca_materia				= document.getElementById("marca_materia").value;
	var modelo						= document.getElementById("modelo").value;
	var utilidad					= document.getElementById("utilidad").value;
	var garantia					= document.getElementById("garantia").value;
	
	if (document.getElementById('serializado').checked == true){
			var serializado = 1;
	}else{
			var serializado = 0;
	}
	if (document.getElementById('fecha_vencimiento').checked == true){
			var fecha_vencimiento = 1;
	}else{
			var fecha_vencimiento = 0;
	}
	
	if(codigo_materia			== ""){
		mostrarMensajes("error", "Disculpe debe ingresar el Codigo del Material");
		document.getElementById("codigo_materia").focus();
	}else if(descripcion_materia == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la Descripcion del Material");
		document.getElementById("descripcion_materia").focus();
	}else if(iddetalle_materias_almacen	== ""){
		mostrarMensajes("error", "Disculpe debe ingresar el Codigo del Catalogo del Material");
		document.getElementById("detalle_materias_almacen").focus();
	}else if(idunidad_medida == ""){
		mostrarMensajes("error", "Disculpe debe Indicar la Unidad de Medida del Material");
		document.getElementById("unidad_medida").focus();
	}else{
	
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				var partes = ajax.responseText.split("|.|");
				//alert(ajax.responseText);
				if(partes[1]=="exito"){
					mostrarMensajes("exito", "Los Datos fueron registradoscon Exito");
					consultarMateria(partes[0]);
					document.getElementById('id_materia').value 	= partes[0];
				}else if(ajax.responseText=="codigo_repetido"){
					mostrarMensajes("error", "Disculpe el Codigo del Material ya existe, por favor verifique");
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtipo_movimiento_almacen="+idtipo_movimiento_almacen+"&codigo_materia="+codigo_materia+"&descripcion_materia="+descripcion_materia+"&idunidad_medida="+idunidad_medida+"&cantidad_unidad="+cantidad_unidad+"&iddetalle_materias_almacen="+iddetalle_materias_almacen+"&idtipo_detalle_almacen="+idtipo_detalle_almacen+"&idmarca_materia="+idmarca_materia+"&modelo="+modelo+"&utilidad="+utilidad+"&serializado="+serializado+"&fecha_vencimiento="+fecha_vencimiento+"&codigo_materia_automatico="+codigo_materia_automatico+"&garantia="+garantia+"&ejecutar=ingresarMateria");	
	}
}



function modificarMateria(){
	var idmateria		 			= document.getElementById("id_materia").value;
	var descripcion_materia			= document.getElementById("descripcion_materia").value;
	var idunidad_medida				= document.getElementById("unidad_medida").value;
	var cantidad_unidad				= document.getElementById("cantidad_unidad").value;
	var iddetalle_materias_almacen	= document.getElementById("iddetalle_materias_almacen").value;
	var idtipo_detalle_almacen		= document.getElementById("idtipo_detalle").value;
	var idmarca_materia				= document.getElementById("marca_materia").value;
	var modelo						= document.getElementById("modelo").value;
	var utilidad					= document.getElementById("utilidad").value;
	var garantia					= document.getElementById("garantia").value;
	
	if (document.getElementById('serializado').checked == true){
			var serializado = 1;
	}else{
			var serializado = 0;
	}
	if (document.getElementById('fecha_vencimiento').checked == true){
			var fecha_vencimiento = 1;
	}else{
			var fecha_vencimiento = 0;
	}
	
	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText=="exito"){
					mostrarMensajes("exito", "Los Datos fueron modificados con exito");
					consultarMateria(idmateria);
					//document.getElementById("tipo_nomina").disabled=false;
				}else{
					mostrarMensajes("error", "Disculpe se produjo un error actualizando los Datos Basicos de la materia, por favor verifique");	
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&descripcion_materia="+descripcion_materia+"&idunidad_medida="+idunidad_medida+"&cantidad_unidad="+cantidad_unidad+"&iddetalle_materias_almacen="+iddetalle_materias_almacen+"&idtipo_detalle_almacen="+idtipo_detalle_almacen+"&idmarca_materia="+idmarca_materia+"&modelo="+modelo+"&utilidad="+utilidad+"&serializado="+serializado+"&fecha_vencimiento="+fecha_vencimiento+"&garantia="+garantia+"&ejecutar=modificarMateria");	
		
}

function eliminarMateria(){
	if(confirm("Realmente desea Bloquear este Material?")){
	var idmateria		 			= document.getElementById("id_materia").value;
	var ajax		= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "El Material se ha Bloqueado con exito");
				consultarMateria(idmateria);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=eliminarMateria");		
	}
}

function desbloquearMateria(){
	
	var idmateria		 			= document.getElementById("id_materia").value;
	var ajax		= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				mostrarMensajes("exito", "El Material se ha desbloqueado con exito");
				consultarMateria(idmateria);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=desbloquearMateria");		
	
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** CONSULTAR ****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

function consultarMateria(id_materia){
	var ajax	= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById("id_materia").value = id_materia;
				partes = ajax.responseText.split("|.|");
				document.getElementById("idgenerar_codigo").style.display		='none';
				
				// muestro los datos en la cabezera para mantenerlos fijos
				document.getElementById("codigo_buscar").innerHTML 							= partes[2];
				document.getElementById("descripcion_buscar").innerHTML 					= partes[3];
				document.getElementById("existencia").innerHTML 							= partes[12];
				
				//muestro los datos en los formularios
				document.getElementById("tipo_movimiento_almacen").value 					= partes[1];
				document.getElementById("codigo_materia").value 							= partes[2];
				document.getElementById("descripcion_materia").value 						= partes[3];
				document.getElementById("unidad_medida").value			 					= partes[4];
				document.getElementById("idunidad_principal").value		 					= partes[4];
				document.getElementById("cantidad_unidad").value 							= partes[5];
				document.getElementById("iddetalle_materias_almacen").value 				= partes[6];
				cargarTipo(partes[6],partes[7]);
				//document.getElementById("idtipo_detalle").value 							= partes[7];
				document.getElementById("marca_materia").value 		  						= partes[8];
				document.getElementById("modelo").value 									= partes[9];
				document.getElementById("utilidad").value 									= partes[10];
				document.getElementById("garantia").value 									= partes[11];
				if (partes[13] ==1){
					document.getElementById("serializado").checked							= true;
					document.getElementById("barra_seriales").style.display = "block";
				}else{
					document.getElementById("serializado").checked							= false;
					document.getElementById("barra_seriales").style.display = "none";
				} 
				if (partes[14] ==1){
					document.getElementById("fecha_vencimiento").checked					= true;
					document.getElementById("barra_fecha_vencimiento").style.display = "block";
				}else{
					document.getElementById("fecha_vencimiento").checked					= false;
					document.getElementById("barra_fecha_vencimiento").style.display = "none";
				} 
				if(partes[15] != ''){
				
					document.getElementById('cuadroFoto').innerHTML 							= "<img src='modulos/almacen/imagenes/"+partes[15]+"' width = '140' height='160'>";
				}else{
					document.getElementById('cuadroFoto').innerHTML 							= "Sin Imagen";	
				}
				document.getElementById("detalle_materias_almacen").value 				= "("+partes[16]+") "+partes[17];
				document.getElementById("estado").innerHTML 							= partes[18];
				document.getElementById("almacen").value 		  						= partes[19];
				document.getElementById("iddistribucion_almacen").value 		 		= partes[20];
				document.getElementById("condicion_almacenaje").value 		  			= partes[21];
				document.getElementById("condicion_conservacion_materia").value 		= partes[22];
				document.getElementById("forma_materia").value 				  			= partes[23];
				document.getElementById("volumen_materia").value 						= partes[24];
				document.getElementById("color_materia").value 				  			= partes[25];
				document.getElementById("peso_materia").value 							= partes[26];
				document.getElementById("unidad_peso").value 							= partes[27];
				document.getElementById("capacidad_materia").value 						= partes[28];
				document.getElementById("unidad_capacidad").value 						= partes[29];
				document.getElementById("alto_materia").value 							= partes[30];
				document.getElementById("unidad_alto").value 							= partes[31];
				document.getElementById("largo_materia").value 							= partes[32];
				document.getElementById("unidad_largo").value 							= partes[33];
				document.getElementById("ancho_materia").value 							= partes[34];
				document.getElementById("unidad_ancho").value 							= partes[35];
				document.getElementById("descripcion_unidad_principal").value 			= partes[36];
				document.getElementById("inventario_inicial").value 					= partes[37];
				document.getElementById("idinventario_inicial").value 					= partes[38];
				document.getElementById("total_entradas").value 						= partes[39];
				document.getElementById("total_despachadas").value 						= partes[40];
				document.getElementById("existencia_actual").value 						= partes[41];
				document.getElementById("stock_minimo").value 							= partes[42];
				document.getElementById("idstock_minimo").value 						= partes[43];
				document.getElementById("stock_maximo").value 							= partes[44];
				document.getElementById("idstock_maximo").value 						= partes[45];
				if (partes[46]=='cerrado'){
					document.getElementById("imagen_carga_serial").style.display	='none';
					document.getElementById("imagen_cerrar_inventario").style.display	='none';
					document.getElementById("inventario_inicial").disabled = "true";
				}
				
				document.getElementById('celda_ultima_entrada').innerHTML 				= partes[47];
				document.getElementById('celda_fecha_ultima_entrada').innerHTML 		= partes[48];
				document.getElementById('celda_ultimo_costo').innerHTML 				= partes[49];
				document.getElementById('celda_conto_promedio').innerHTML 				= partes[50];
				
				consultar_registroFotografico(id_materia);
				consultarReemplazos(id_materia);
				consultarEquivalencia(id_materia);
				consultarAccesorio(id_materia);
				consultarProveedor(id_materia);
				consultarProveedor(id_materia);
				consultarArticulos(id_materia);
				idunidad_principal = partes[4];
				consultarDesagregar(idunidad_principal);
				consultarSeriales(id_materia);
				consultarFVencimiento(id_materia);
				
				document.getElementById('codigo_materia').disabled 				= true;
				document.getElementById('tipo_movimiento_almacen').disabled 	= true;
				document.getElementById("ingresar_Materia").style.display		='none';
				document.getElementById("modificar_Materia").style.display		='block';
				
				if (partes[18]=='DISPONIBLE'){
					document.getElementById("eliminar_Materia").style.display		='block';
					document.getElementById("desbloquear_Materia").style.display	='none';
				}
				if (partes[18]=='BLOQUEADO'){
					document.getElementById("eliminar_Materia").style.display		='none';
					document.getElementById("desbloquear_Materia").style.display	='block';
				}
				
				
				document.getElementById("divCargando").style.display 			= 'none';
				
				mostrarPestanas();
				
		} 
	}
	ajax.send("id_materia="+id_materia+"&ejecutar=consultarMateria");		
}






//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** UBICACION *************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
/**
 *  Retorna los datos del almacen y la ubicacion seleccionada dentro del mismo
 */
function getDataUbicacion(){
  var idalmacen      = document.getElementById("almacen").value;
  var iddistribucion = document.getElementById("iddistribucion_almacen").value;
  return "&idalmacen=" + idalmacen + "&iddistribucion=" + iddistribucion_almacen;
}
/**
 *  Se encarga de actualizar las ubicaciones dentro de un almacen
 */
function seleccionarUbicacion(idalmacen, value){
  if(!(idalmacen == null || idalmacen == undefined || idalmacen == '')){
    var idmateria            = document.getElementById("id_materia").value;
    ////////////////////////////////////////////////////////////////////////////////////////////////
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function(){
      if(ajax.readyState == 4){
        document.getElementById('celda_ubicacion').innerHTML = ajax.responseText;
			  document.getElementById("divCargando").style.display = "none";
        updateExistencia();
	    }
	  }
	  ajax.send(((value == null || value == undefined || value == '')?'':('ubicacion='+value+'&')) + "idmateria=" + idmateria + "&idalmacen="+idalmacen+"&ejecutar=seleccionarUbicacion");
    ////////////////////////////////////////////////////////////////////////////////////////////////
    var ajaxAlm = nuevoAjax();
    ajaxAlm.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
    ajaxAlm.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajaxAlm.onreadystatechange = function(){
      if(ajaxAlm.readyState == 4){
        document.getElementById('celda_almacen').innerHTML = ajaxAlm.responseText;
			  document.getElementById("divCargando").style.display = "none";
	    }
	  }
	  ajaxAlm.send("idmateria=" + idmateria + "&idalmacen="+idalmacen+"&ejecutar=seleccionarAlmacen");
  }
}
/**
 *
 */
function ingresarUbicacion(){
  var idmateria                 = document.getElementById('id_materia').value;
  var idalmacen                 = document.getElementById("almacen").value;
  var iddistribucion_almacen    = document.getElementById("iddistribucion_almacen").value;
  var idcondicion_almacenaje    = document.getElementById("condicion_almacenaje").value;
  var idcondicion_conservacion  = document.getElementById("condicion_conservacion_materia").value;
  var idforma_materia           = document.getElementById("forma_materia").value;
  var idvolumen_materia         = document.getElementById("volumen_materia").value;
  var color                     = document.getElementById("color_materia").value;
  var peso                      = document.getElementById("peso_materia").value;
  var idunidad_medida_peso      = document.getElementById("unidad_peso").value;
  var capacidad                 = document.getElementById("capacidad_materia").value;
  var idunidad_medida_capacidad = document.getElementById("unidad_capacidad").value;
  var alto                      = document.getElementById("alto_materia").value;
  var idunidad_medida_alto      = document.getElementById("unidad_alto").value;
  var largo                     = document.getElementById("largo_materia").value;
  var idunidad_medida_largo     = document.getElementById("unidad_largo").value;
  var ancho                     = document.getElementById("ancho_materia").value;
  var idunidad_medida_ancho     = document.getElementById("unidad_ancho").value;	
  var ajax                      = nuevoAjax();
  ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
  ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
      document.getElementById("divCargando").style.display = "block";
		}
		if(ajax.readyState==4){
      if(ajax.responseText=="exito"){
        mostrarMensajes("exito", "Los Datos fueron registrados con Exito");
      }else{
        mostrarMensajes("error", "Disculpe no se pude registrar los datos con exito, por favor verifique: ("+ajax.responseText+")");
      }
			document.getElementById("divCargando").style.display = "none";
		}
	}
	ajax.send("idmateria="+idmateria+"&idalmacen="+idalmacen+"&iddistribucion_almacen="+iddistribucion_almacen+"&idcondicion_almacenaje="+idcondicion_almacenaje+"&idcondicion_conservacion="+idcondicion_conservacion+"&idforma_materia="+idforma_materia+"&idvolumen_materia="+idvolumen_materia+"&color="+color+"&peso="+peso+"&idunidad_medida_peso="+idunidad_medida_peso+"&capacidad="+capacidad+"&idunidad_medida_capacidad="+idunidad_medida_capacidad+"&alto="+alto+"&idunidad_medida_alto="+idunidad_medida_alto+"&largo="+largo+"&idunidad_medida_largo="+idunidad_medida_largo+"&ancho="+ancho+"&idunidad_medida_ancho="+idunidad_medida_ancho+"&ejecutar=ingresarUbicacion");	
}
//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** EXISTENCIA ************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
/**
 *
 */
function validarUbicacion(){
  var _return        = undefined;
  var idalmacen      = document.getElementById("almacen").value;
  var iddistribucion = document.getElementById("iddistribucion_almacen").value;
  if(iddistribucion == null || iddistribucion == undefined || iddistribucion == '' || iddistribucion == 0){
    mostrarMensajes("error", "Disculpe debe indicar la ubicacion en la cual esta trabajando dentro del almacen");
    window.frames[3].document.getElementById('div_existencia').style.display = 'none';
    window.frames[3].document.getElementById('div_ubicacion').style.display = 'block';
    document.getElementById("iddistribucion_almacen").focus();
  }else{
    if(idalmacen == null || idalmacen == undefined || idalmacen == '' || iddistribucion == 0){
      mostrarMensajes("error", "Disculpe debe indicar el almacen en el cual esta trabajando");      
      window.frames[3].document.getElementById('div_existencia').style.display = 'none';
      window.frames[3].document.getElementById('div_ubicacion').style.display = 'block';
      document.getElementById("almacen_existencia").focus();
    }else{
      _return = '&idAlmacen=' + idalmacen + '&idDistribucion=' + iddistribucion;
    }
  }
  return _return;
}
/**
 *
 */
function updateExistencia(){
  var idinventario_inicial = document.getElementById("id_materia").value;
  var ubicacion            = validarUbicacion();
  if(ubicacion != undefined){
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function(){
      if(ajax.readyState == 1){
        document.getElementById("divCargando").style.display = "block";
      }
	    if(ajax.readyState == 4){
        if(ajax.responseText == "noExiste"){
           mostrarMensajes("error", "Disculpe no se puedo obtener informacion del inventario solicitado.");
        }else if(ajax.responseText == "existeMultiple"){
           mostrarMensajes("error", "Disculpe se obtuvieron multiples inventarios");
        }else{
          var json = JSON.parse(ajax.responseText);
          document.getElementById("imagen_cerrar_inventario").style.display	= json.estado == 'a'? 'inline':'none';
					document.getElementById("imagen_carga_serial").style.display      = json.estado == 'a'? 'block':'none';
					document.getElementById("inventario_inicial").disabled            = json.estado != 'a';
				  document.getElementById("inventario_inicial").value               = json.inicial;
          document.getElementById("barra_seriales").style.display           = json.serial == 1  ? 'block':'none';
          document.getElementById("barra_fecha_vencimiento").style.display  = json.caduca == 1  ? 'block':'none';
          if(json.serial == 1){
            consultarSeriales();
          }
          if(json.caduca == 1){
            consultarFVencimiento();
          }
        }
      }
      document.getElementById("divCargando").style.display = "none";
    }
	  ajax.send("idinventario_inicial=" + idinventario_inicial + ubicacion + "&ejecutar=consultarExistenciaAlmacen");
  }
}
/**
 *
 */
function modificarExistencia(){
  var ubicacion = validarUbicacion();
  if(ubicacion != undefined){
    var idmateria            = document.getElementById("id_materia").value;
    var idinventario_inicial = document.getElementById("idinventario_inicial").value;
    var existencia_actual    = document.getElementById("inventario_inicial").value;
    var idstock_minimo       = document.getElementById("idstock_minimo").value;
    var idstock_maximo       = document.getElementById("idstock_maximo").value;
    var ajax                 = nuevoAjax();
    ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function(){ 
      if(ajax.readyState == 1){
        document.getElementById("divCargando").style.display = "block";
      }
      if(ajax.readyState == 4){
        if(ajax.responseText=="exito"){
          document.getElementById("existencia_actual").value = existencia_actual;
          mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
        }else{
          mostrarMensajes("error", "Disculpe se produjo un error actualizando la Existencia de la materia, por favor verifique");	
        }
        document.getElementById("divCargando").style.display = "none";
      } 
    }
	  ajax.send("idmateria=" + idmateria + "&idinventario_inicial=" + idinventario_inicial + "&idstock_minimo=" + idstock_minimo + "&idstock_maximo=" + idstock_maximo + ubicacion + "&ejecutar=modificarExistencia");
  }
}
/**
 *
 */
function ingresarSerial(){
  var idinventario_inicial = document.getElementById("idinventario_inicial").value;
  var idmateria		         = document.getElementById("id_materia").value;
  var serial               = document.getElementById("serialMateria").value;
  var ubicacion            = validarUbicacion();
  if(ubicacion != undefined){
    if(serial == ""){
      mostrarMensajes("error", "Disculpe debe ingresar el Serial del Material");
      document.getElementById("serialMateria").focus();
      }else{
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function(){
        if(ajax.readyState == 1){
          document.getElementById("divCargando").style.display = "block";
        }
        if(ajax.readyState==4){
          if(ajax.responseText=="exito"){
            mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
            consultarSeriales(idmateria);
          }else if (ajax.responseText=="existe"){
            mostrarMensajes("error", "Disculpe el serial que intenta ingresar ya esta registrado en ese material, por favor verifique");
          }else if (ajax.responseText=="limite"){
            mostrarMensajes("error", "YA INGRESO LOS SERIALES PARA CADA UNO DE LOS MATERIALES DEL INVENTARIO INICIAL");
          }
          document.getElementById("divCargando").style.display = "none";
        } 
      }
      ajax.send("idmateria=" + idmateria + "&serial=" + serial + "&inventario_inicial=" + idinventario_inicial + ubicacion + "&ejecutar=ingresarSerial");	
    }
  }
}
/**
 *
 */
function consultarSeriales(){
  var ubicacion = validarUbicacion();
  if(ubicacion != undefined){
    var idmateria = document.getElementById('id_materia').value;
    var ajax      = nuevoAjax();
    ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange=function() { 
      if(ajax.readyState == 1){
        document.getElementById("divCargando").style.display = "block";
      }
      if(ajax.readyState == 4){
        document.getElementById('lista_seriales').innerHTML = ajax.responseText;
        document.getElementById("serialMateria").value = "";
        document.getElementById("divCargando").style.display = "none";
        var van_seriales = document.getElementById("van_seriales").value;
        document.getElementById('van_serial').innerHTML 	= "Van: "+van_seriales;        
        mostrarMensajes("exito", "Seriales registrados : " + van_seriales);
		  }
	  }
    ajax.send("idmateria=" + idmateria + ubicacion + "&ejecutar=consultarSeriales");
  }
}
/**
 *
 */
function EliminarSerial(idrelacion_serial_materia){
  var idmateria = document.getElementById('id_materia').value;
  var ajax      = nuevoAjax();
  ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
  ajax.onreadystatechange = function(){ 
    if(ajax.readyState == 1){
      document.getElementById("divCargando").style.display = "block";
    }
    if(ajax.readyState==4){
      document.getElementById("divCargando").style.display = "none";
      consultarSeriales(idmateria);
    }
  }
  ajax.send("idrelacion_serial_materia="+idrelacion_serial_materia+"&ejecutar=EliminarSerial");	
}
/**
 *
 */
function ingresarFVencimiento(){
  var ubicacion = validarUbicacion();
  if(ubicacion != undefined){
	  var idmateria             = document.getElementById("id_materia").value;
    var lote                  = document.getElementById("lote").value;
    var fecha_vencimiento     = document.getElementById("fecha_vencimiento_materia").value;
    var cantidad              = document.getElementById("cantidad_fecha_vencimiento").value;
    var idinventario_inicial  = document.getElementById("idinventario_inicial").value;
    if(lote == ""){
	    mostrarMensajes("error", "Disculpe debe ingresar el Lote del Material");
	    document.getElementById("lote").focus();
    }else if(cantidad == ""){
      mostrarMensajes("error", "Disculpe debe ingresar la Cantidad de items en el Lote del Material");
      document.getElementById("cantidad_fecha_vencimiento").focus();
    }else if(fecha_vencimiento == ""){
      mostrarMensajes("error", "Disculpe debe ingresar la Fecha de Vencimiento de los items del Lote del Material");
      document.getElementById("fecha_vencimiento").focus();
    }else{
      var ajax = nuevoAjax();
      ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
      ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
      ajax.onreadystatechange = function(){
        if(ajax.readyState == 1){
          document.getElementById("divCargando").style.display = "block";					
        }
        if(ajax.readyState == 4){
          if(ajax.responseText == "exito"){
            mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
            consultarFVencimiento(idmateria);
          }else if (ajax.responseText=="existe"){
            mostrarMensajes("error", "Disculpe el lote que intenta ingresar ya esta registrado en ese material, por favor verifique");	
          }else if (ajax.responseText=="limite"){
            mostrarMensajes("error", "YA INGRESO LA CANTIDAD DE ITEMS PARA CADA UNO DE LOS MATERIALES DEL INVENTARIO INICIAL");	
          }
			    document.getElementById("divCargando").style.display = "none";
			  } 
		  }
		  ajax.send("idmateria="+idmateria+"&lote="+lote+"&inventario_inicial="+idinventario_inicial+"&cantidad="+cantidad+"&fecha_vencimiento="+fecha_vencimiento+ubicacion+"&ejecutar=ingresarFVencimiento");	
	  }
  }
}
/**
 *
 */
function eliminarLoteVencimiento(idrelacion_vencimiento_materia){
  var idmateria = document.getElementById('id_materia').value;
  var ajax      = nuevoAjax();
  ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
  ajax.onreadystatechange = function(){
    if(ajax.readyState == 1){
      document.getElementById("divCargando").style.display = "block";
		}
    if(ajax.readyState == 4){
      if(ajax.responseText == "exito"){
         mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
         consultarFVencimiento(idmateria);
      }else if(ajax.responseText == "despachados"){
         mostrarMensajes("error", "Disculpe el lote que intenta eliminar ya despacho materiales, por favor verifique");	
      }
      document.getElementById("divCargando").style.display = "none";
		}
	}
	ajax.send("idmateria="+idmateria+"&idrelacion_vencimiento_materia="+idrelacion_vencimiento_materia+"&ejecutar=eliminarLoteVencimiento");	
}
function consultarFVencimiento(){
  var ubicacion = validarUbicacion();
  if(ubicacion != undefined){
    var idmateria = document.getElementById('id_materia').value;
    var ajax  = nuevoAjax();
	  ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	  ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	  ajax.onreadystatechange = function() { 
      if(ajax.readyState == 1){
        document.getElementById("divCargando").style.display = "block";
		  }
		  if(ajax.readyState == 4){
        document.getElementById('lista_fecha_vencimiento').innerHTML = ajax.responseText;
        document.getElementById("lote").value = "";
        document.getElementById("cantidad_fecha_vencimiento").value = "";
        document.getElementById("fecha_vencimiento_materia").value = "";
        document.getElementById("divCargando").style.display = "none";
        var van_fechas		= document.getElementById("van_fechas").value;
        document.getElementById('van_fecha').innerHTML 	= "Van: "+van_fechas;
		  }
	  }
	  ajax.send("idmateria="+idmateria+ubicacion+"&ejecutar=consultarFVencimiento");
	}
}
/**
 *
 */
function cerrarInventarioInicial(){
  var ubicacion = validarUbicacion();
  if(ubicacion != undefined){
    var idmateria            = document.getElementById('id_materia').value;
    var idinventario_inicial = document.getElementById("idinventario_inicial").value;
    var serializado          = document.getElementById('serializado').checked? 1 : 0;
    var fecha_vencimiento    = document.getElementById('fecha_vencimiento').checked? 1 : 0;
    var ajax                 = nuevoAjax();
    ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange  = function() {
      if(ajax.readyState == 1){
        document.getElementById("divCargando").style.display = "block";
      }
      if(ajax.readyState==4){
        if(ajax.responseText=="exito"){
          mostrarMensajes("exito", "El Inventario Inicial fue cerrado con exito con exito");
          consultarMateria(idmateria);
        }else if (ajax.responseText=="faltan_seriales"){
          mostrarMensajes("error", "Faltan Ingresar seriales para la cantidad del Inventario Inicial");
        }else if (ajax.responseText=="faltan_fechas"){
          mostrarMensajes("error", "Faltan Ingresar Lotes de Fecha de Vencimiento para la cantidad del Inventario Inicial");
        }else{
          mostrarMensajes("error", ajax.responseText);
        }
        document.getElementById("divCargando").style.display = "none";
      }
    }
    ajax.send("idmateria="+idmateria+"&idinventario_inicial="+idinventario_inicial+"&serializado="+serializado+"&fecha_vencimiento="+fecha_vencimiento+ubicacion+"&ejecutar=cerrarInventarioInicial");	
  }
}
//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** DESAGREGAR UNIDAD *****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

function consultarDesagregar(idunidad_principal){
	var idmateria 			= document.getElementById('id_materia').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('lista_Desagregada').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idunidad_principal="+idunidad_principal+"&idmateria="+idmateria+"&ejecutar=consultarDesagregar");
	
}


function actualizarCantidadDesagrega(idrelacion_desagrega_unidad_materia, iddesagrega_unidad_medida){
	var unidad = "unidad"+iddesagrega_unidad_medida;
	var cantidad_desagrega				= document.getElementById(unidad).value;
	
	var ajax					= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText=="exito"){
					mostrarMensajes("exito", "Los Datos fueron actualizados con exito");
				}else{
					mostrarMensajes("error", "Disculpe se produjo un error actualizando los Datos, por favor verifique");	
				}
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idrelacion_desagrega_unidad_materia="+idrelacion_desagrega_unidad_materia+"&cantidad_desagrega="+cantidad_desagrega+"&ejecutar=actualizarCantidadDesagrega");	
		
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** REEMPLAZOS ************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function ingresarReemplazoMateria(){
	var idmateria 					= document.getElementById('id_materia').value;
	var idmateria_reemplazo			= document.getElementById("idmateria_reemplazo").value;
	if (idmateria == idmateria_reemplazo){
		alert("No puede ingresar el mismo articulo como reemplazo");
		document.getElementById('descripcion_reemplazo').value = '';
	}else{
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText=="exito"){
					mostrarMensajes("exito", "Los Datos fueron registrados con Exito");
				}else{
					mostrarMensajes("error", "El material que intenta ingresra como reemplazo ya lo registro, verifique por favor");
				}
				document.getElementById("divCargando").style.display = "none";
				consultarReemplazos(idmateria);
		} 
	}
	ajax.send("idmateria="+idmateria+"&idmateria_reemplazo="+idmateria_reemplazo+"&ejecutar=ingresarReemplazoMateria");
	}
}


function consultarReemplazos(idmateria){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('descripcion_reemplazo').value = '';
				document.getElementById('lista_Reemplazos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=consultarReemplazoMateria");
	
}

function seleccionarEliminarReemplazo(idmateria_reemplazo){
	var idmateria 					= document.getElementById('id_materia').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById("divCargando").style.display = "none";
				consultarReemplazos(idmateria);
		} 
	}
	ajax.send("idmateria_reemplazo="+idmateria_reemplazo+"&ejecutar=seleccionarEliminarReemplazo");
	
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** EQUIVALENCIAS *********************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function ingresarEquivalenciaMateria(){
	var idmateria 					= document.getElementById('id_materia').value;
	var idmateria_equivalente		= document.getElementById("idmateria_equivalente").value;
	var describir_equivalencia		= document.getElementById("describir_equivalencia").value;
	
	if (idmateria == idmateria_equivalente){
		alert("No puede ingresar el mismo articulo como Equivalente");
		document.getElementById('descripcion_equivalencia').value = '';
	}else{
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText=="exito"){
					mostrarMensajes("exito", "Los Datos fueron registrados con Exito");
				}else{
					mostrarMensajes("error", "El material que intenta ingresra como Equivalente ya lo registro, verifique por favor");
				}
				document.getElementById("divCargando").style.display = "none";
				consultarEquivalencia(idmateria);
		} 
	}
	ajax.send("idmateria="+idmateria+"&idmateria_equivalente="+idmateria_equivalente+"&describir_equivalencia="+describir_equivalencia+"&ejecutar=ingresarEquivalenciaMateria");
	}
}


function consultarEquivalencia(idmateria){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('descripcion_equivalencia').value = '';
				document.getElementById('describir_equivalencia').value = '';
				document.getElementById('lista_Equivalencias').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=consultarEquivalencia");
	
}

function seleccionarEliminarEquivalencia(idrelacion_equivalencia_materia){
	var idmateria 					= document.getElementById('id_materia').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById("divCargando").style.display = "none";
				consultarEquivalencia(idmateria);
		} 
	}
	ajax.send("idrelacion_equivalencia_materia="+idrelacion_equivalencia_materia+"&ejecutar=seleccionarEliminarEquivalencia");
	
}


//******************************************************************************************************************************
//******************************************************************************************************************************
// ****************************************************** REGISTRO FOTOGRAFICO ********************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

function subirRegistroFotografico(){
	var nombre_imagen 	= document.getElementById("nombre_imagen").value;
	var idmateria	 	= document.getElementById("id_materia").value;
	var descripcion		= document.getElementById('descripcion_foto').value;
	var ajax			= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('mostrarImagen').innerHTML = '';
			document.getElementById('foto_registroFotografico').value = '';
			document.getElementById('descripcion_foto').value = '';
			consultar_registroFotografico(idmateria);	
		} 
	}
	ajax.send("descripcion="+descripcion+"&idmateria="+idmateria+"&nombre_imagen="+nombre_imagen+"&ejecutar=subirRegistroFotografico");		
}


function consultar_registroFotografico(idmateria){	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('lista_registroFotografico').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=consultar_registroFotografico");		
}


function eliminar_registroFotografico(idrelacion_imagen_materia){
	if(confirm("Seguro desea Eliminar esta imagen?")){
	var ajax	= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultar_registroFotografico(document.getElementById('id_materia').value);
		} 
	}
	ajax.send("idrelacion_imagen_materia="+idrelacion_imagen_materia+"&ejecutar=eliminar_registroFotografico");		
	}
}




function principal_registroFotografico(idrelacion_imagen_materia){
	var idmateria	 	= document.getElementById("id_materia").value;
	var ajax			= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultar_registroFotografico(document.getElementById('id_materia').value);
		} 
	}
	ajax.send("idmateria="+idmateria+"&idrelacion_imagen_materia="+idrelacion_imagen_materia+"&ejecutar=principal_registroFotografico");		
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** ACCESORIOS ************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function ingresarAccesorioMateria(){
	var idmateria 					= document.getElementById('id_materia').value;
	var idmateria_accesorios		= document.getElementById("idmateria_accesorios").value;
	var describir_accesorio			= document.getElementById("describir_accesorio").value;
	
	if (idmateria == idmateria_accesorios){
		alert("No puede ingresar el mismo articulo como Equivalente");
		document.getElementById('descripcion_accesorio').value = '';
	}else{
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				if(ajax.responseText=="exito"){
					mostrarMensajes("exito", "Los Datos fueron registrados con Exito");
				}else{
					mostrarMensajes("error", "El material que intenta ingresra como Accesorio ya lo registro, verifique por favor");
				}
				document.getElementById("divCargando").style.display = "none";
				consultarAccesorio(idmateria);
		} 
	}
	ajax.send("idmateria="+idmateria+"&idmateria_accesorios="+idmateria_accesorios+"&describir_accesorio="+describir_accesorio+"&ejecutar=ingresarAccesorioMateria");
	}
}


function consultarAccesorio(idmateria){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('descripcion_accesorio').value = '';
				document.getElementById('describir_accesorio').value = '';
				document.getElementById('listaAccesorios').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=consultarAccesorio");
	
}

function seleccionarEliminarAccesorios(idrelacion_accesorios_materia){
	var idmateria 					= document.getElementById('id_materia').value;
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById("divCargando").style.display = "none";
				consultarAccesorio(idmateria);
		} 
	}
	ajax.send("idrelacion_accesorios_materia="+idrelacion_accesorios_materia+"&ejecutar=seleccionarEliminarAccesorios");
	
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** COMPRA PROVEEDOR ******************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function consultarProveedor(idmateria){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('listaCompras').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=consultarProveedor");
	
}


//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** ARTICULOS DE COMPRA ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function consultarArticulos(idmateria){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
				document.getElementById('listaArticulos').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmateria="+idmateria+"&ejecutar=consultarArticulos");
	
}


//******************************************************************************************************************************
//******************************************************************************************************************************
// ************************************************** DATOS DE EMPLEO ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function modificarDatosEmpleo(){
	var idcargo 					= document.getElementById("idcargo_datosEmpleo").value;
	var ubicacion_funcional 		= document.getElementById("ubicacion_funcional_datosEmpleo").value;
	var centro_costo 				= document.getElementById("centro_costo_datosEmpleo").value;
	var fecha_ingreso 				= document.getElementById("fecha_ingreso_datosEmpleo").value;
	var vacaciones 					= document.getElementById('vacaciones_datosEmpleo').value;
	var activo_nomina 				= document.getElementById('activo_nomina_datosEmpleo').value;
	var idtrabajador 				= document.getElementById('idtrabajador').value;
	var fecha_inicio_continuidad 	= document.getElementById('fecha_inicio_continuidad').value;
	
	if(document.getElementById('vacaciones_datosEmpleo').checked == true){
		vacaciones 			= 'si';	
	}else{
		vacaciones 			= 'no';
	}
	
	if(document.getElementById('activo_nomina_datosEmpleo').checked == true ){
		activo_nomina 		= 'si';
	}else{
		activo_nomina 		= 'no';
	}
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			consultarTrabajador(idtrabajador);
		} 
	}
	ajax.send("fecha_inicio_continuidad="+fecha_inicio_continuidad+"&idcargo="+idcargo+"&ubicacion_funcional="+ubicacion_funcional+"&centro_costo="+centro_costo+"&fecha_ingreso="+fecha_ingreso+"&vacaciones="+vacaciones+"&activo_nomina="+activo_nomina+"&idtrabajador="+idtrabajador+"&ejecutar=modificarDatosEmpleo");		
	
}





//******************************************************************************************************************************
//******************************************************************************************************************************
// ************************************************** CARGA FAMILIAR *****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

function consultarCargaFamiliar(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('lista_cargaFamiliar').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarCargaFamiliar");			
}


function ingresarCargaFamiliar(){
	var idtrabajador 		= document.getElementById('idtrabajador').value;
	var apellido 			= document.getElementById('apellidos_cargaFamiliar').value;
	var nombre 				= document.getElementById('nombres_cargaFamiliar').value;
	var idnacionalidad 		= document.getElementById('idnacionalidad_cargaFamiliar').value;
	var cedula 				= document.getElementById('cedula_cargaFamiliar').value;
	var fecha_nacimiento 	= document.getElementById('fecha_nacimiento_cargaFamiliar').value;
	var sexo 				= document.getElementById('sexo_cargaFamiliar').value;
	var idparentezco 		= document.getElementById('idparentezco_cargaFamiliar').value;
	if(document.getElementById('constancia_cargaFamiliar').checked == true){
		var constancia 		= "si";	
	}else{
		var constancia 		= "no";	
	}
	var direccion 			= document.getElementById('direccion_cargaFamiliar').value;
	var telefono 			= document.getElementById('telefono_cargaFamiliar').value;
	var ocupacion 			= document.getElementById('ocupacion_cargaFamiliar').value;
	var nombre_foto 		= document.getElementById('nombre_foto_cargaFamiliar').value;
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultarCargaFamiliar(idtrabajador);
			document.formulario_cargaFamiliar.reset();
			document.getElementById('foto_cargaFamiliar').value 		= '';
			document.getElementById('nombre_foto_cargaFamiliar').value 	= '';
			mostrarMensajes("exito", "Se Ingresaron los datos con exito");
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&apellido="+apellido+"&nombre="+nombre+"&idnacionalidad="+idnacionalidad+"&cedula="+cedula+"&fecha_nacimiento="+fecha_nacimiento+"&sexo="+sexo+"&idparentezco="+idparentezco+"&constancia="+constancia+"&direccion="+direccion+"&telefono="+telefono+"&ocupacion="+ocupacion+"&nombre_foto="+nombre_foto+"&ejecutar=ingresarCargaFamiliar");			
	
}





function modificarCargaFamiliar(){
	var idtrabajador 		= document.getElementById('idtrabajador').value;
	var idcarga_familiar 	= document.getElementById('idcarga_familiar').value;
	var apellido 			= document.getElementById('apellidos_cargaFamiliar').value;
	var nombre 				= document.getElementById('nombres_cargaFamiliar').value;
	var idnacionalidad 		= document.getElementById('idnacionalidad_cargaFamiliar').value;
	var cedula 				= document.getElementById('cedula_cargaFamiliar').value;
	var fecha_nacimiento 	= document.getElementById('fecha_nacimiento_cargaFamiliar').value;
	var sexo 				= document.getElementById('sexo_cargaFamiliar').value;
	var idparentezco 		= document.getElementById('idparentezco_cargaFamiliar').value;
	if(document.getElementById('constancia_cargaFamiliar').checked == true){
		var constancia 		= "si";	
	}else{
		var constancia 		= "no";	
	}
	var direccion 			= document.getElementById('direccion_cargaFamiliar').value;
	var telefono 			= document.getElementById('telefono_cargaFamiliar').value;
	var ocupacion 			= document.getElementById('ocupacion_cargaFamiliar').value;
	var nombre_foto 		= document.getElementById('nombre_foto_cargaFamiliar').value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			consultarCargaFamiliar(idtrabajador);
			document.formulario_cargaFamiliar.reset();
			document.getElementById('foto_cargaFamiliar').value 					= '';
			document.getElementById('nombre_foto_cargaFamiliar').value 				= '';
			document.getElementById('boton_ingresar_cargaFamiliar').style.display 	= 'block';
			document.getElementById('boton_modificar_cargaFamiliar').style.display 	= 'none';
			//document.getElementById('lista_cargaFamiliar').innerHTML = ajax.responseText;
			mostrarMensajes("exito", "Se modifico los datos con exito");
		} 
	}
	ajax.send("idcarga_familiar="+idcarga_familiar+"&apellido="+apellido+"&nombre="+nombre+"&idnacionalidad="+idnacionalidad+"&cedula="+cedula+"&fecha_nacimiento="+fecha_nacimiento+"&sexo="+sexo+"&idparentezco="+idparentezco+"&constancia="+constancia+"&direccion="+direccion+"&telefono="+telefono+"&ocupacion="+ocupacion+"&nombre_foto="+nombre_foto+"&ejecutar=modificarCargaFamiliar");			
	
}





function seleccionarCargaFamiliar(idcarga_familiar, apellido, nombre, idnacionalidad, cedula, fecha_nacimiento, sexo, idparentezco, constancia, direccion, telefono, ocupacion){
	document.getElementById('idcarga_familiar').value 						= idcarga_familiar;
	document.getElementById('apellidos_cargaFamiliar').value 				= apellido;
	document.getElementById('nombres_cargaFamiliar').value 					= nombre;
	document.getElementById('idnacionalidad_cargaFamiliar').value 			= idnacionalidad;
	document.getElementById('cedula_cargaFamiliar').value 					= cedula;
	document.getElementById('fecha_nacimiento_cargaFamiliar').value 		= fecha_nacimiento;
	document.getElementById('sexo_cargaFamiliar').value 					= sexo;
	document.getElementById('idparentezco_cargaFamiliar').value 			= idparentezco;
	if(constancia == "s"){
		document.getElementById('constancia_cargaFamiliar').checked 		= true;
	}else{
		document.getElementById('constancia_cargaFamiliar').checked 		= false;	
	}
	document.getElementById('direccion_cargaFamiliar').value 				= direccion;
	document.getElementById('telefono_cargaFamiliar').value 				= telefono;
	document.getElementById('ocupacion_cargaFamiliar').value 				= ocupacion;
	document.getElementById('boton_ingresar_cargaFamiliar').style.display 	= 'none';
	document.getElementById('boton_modificar_cargaFamiliar').style.display 	= 'block';
}



function eliminarCargaFamiliar(idcarga_familiar){
	if(confirm("¿Seguro desea eliminar la carga familiar seleccionada?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultarCargaFamiliar(document.getElementById('idtrabajador').value);
		} 
	}
	ajax.send("idcarga_familiar="+idcarga_familiar+"&ejecutar=eliminarCargaFamiliar");			
	}
}




//******************************************************************************************************************************
//******************************************************************************************************************************
// ********************************************************** INSTRUCCION ACADEMICA ********************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function ingresarInstruccionAcademica(){
	var idnivel_estudios 		= document.getElementById('idnivel_estudio').value;
	var idprofesion 			= document.getElementById('idprofesion').value;
	var idmension 				= document.getElementById('idmension').value;
	var institucion 			= document.getElementById('institucion').value;
	var anio_egreso 			= document.getElementById('anio_egreso').value;
	var nombre_foto 			= document.getElementById('nombre_foto_instruccionAcademica').value;
	var idtrabajador 			= document.getElementById('idtrabajador').value;
	var observaciones 			= document.getElementById('observaciones').value;
	if( document.getElementById('constancia').checked == true){
		var constancia 			= "s";	
	}else{
		var constancia 			= "n";	
	}
	if(document.getElementById('profesion_actual').checked == true){
		var profesion_actual 	= "s";	
	}else{
		var profesion_actual 	= "n";	
	}
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Sus datos han sido registrados con exito");	
			}else{
				mostrarMensajes("error", "Disculpe los datos no se han podido registrar ("+ajax.responseText+")");
			}
			consultarInstruccionAcademica(idtrabajador);
			limpiar_campos_instruccionAcademica();
		} 
	}
	ajax.send("idnivel_estudios="+idnivel_estudios+"&idprofesion="+idprofesion+"&idmension="+idmension+"&institucion="+institucion+"&anio_egreso="+anio_egreso+"&constancia="+constancia+"&profesion_actual="+profesion_actual+"&idtrabajador="+idtrabajador+"&nombre_foto="+nombre_foto+"&observaciones="+observaciones+"&ejecutar=ingresarInstruccionAcademica");	
}




function modificarInstruccionAcademica(){
	var idinstruccion_academica = document.getElementById('idinstruccion_academica').value;
	var idnivel_estudios 		= document.getElementById('idnivel_estudio').value;
	var idprofesion 			= document.getElementById('idprofesion').value;
	var idmension 				= document.getElementById('idmension').value;
	var institucion 			= document.getElementById('institucion').value;
	var anio_egreso 			= document.getElementById('anio_egreso').value;
	var nombre_foto 			= document.getElementById('nombre_foto_instruccionAcademica').value;
	var observaciones			= document.getElementById('observaciones').value;
	
	if( document.getElementById('constancia').checked == true){
		var constancia 			= "si";	
	}else{
		var constancia 			= "no";	
	}
	if(document.getElementById('profesion_actual').checked == true){
		var profesion_actual 	= "si";	
	}else{
		var profesion_actual 	= "no";	
	}
	
	var ajax					= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Sus datos han sido Modificados con exito");	
			}else{
				mostrarMensajes("error", "Disculpe los datos no se han podido Modificar");
			}
			consultarInstruccionAcademica(document.getElementById('idtrabajador').value);
			limpiar_campos_instruccionAcademica();
		} 
	}
	ajax.send("observaciones="+observaciones+"&idnivel_estudios="+idnivel_estudios+"&idprofesion="+idprofesion+"&idmension="+idmension+"&institucion="+institucion+"&anio_egreso="+anio_egreso+"&constancia="+constancia+"&profesion_actual="+profesion_actual+"&idinstruccion_academica="+idinstruccion_academica+"&nombre_foto="+nombre_foto+"&ejecutar=modificarInstruccionAcademica");	
}




function eliminarInstruccionAcademica(idinstruccion_academica){
	if(confirm("Seguro desea eliminar la Instruccion Academica seleccionada?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "Se elimino el registro con exito");	
			}else{
				mostrarMensajes("error", "Disculpe el Registro no se pudo eliminar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
			}
			consultarInstruccionAcademica(document.getElementById('idtrabajador').value);
		} 
	}
	ajax.send("idinstruccion_academica="+idinstruccion_academica+"&ejecutar=eliminarInstruccionAcademica");	
	}
}



function consultarInstruccionAcademica(idtrabajador){
	var ajax = nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('lista_instruccionAcademica').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarInstruccionAcademica");		
}



function limpiar_campos_instruccionAcademica(){
	var idnivel_estudios = document.getElementById('idnivel_estudio').value 			= 0;
	var idprofesion = document.getElementById('idprofesion').value 						= 0;
	var idmension = document.getElementById('idmension').value 							= 0;
	var institucion = document.getElementById('institucion').value 						= "";
	var anio_egreso = document.getElementById('anio_egreso').value 						= "";
	var nombre_foto = document.getElementById('nombre_foto_instruccionAcademica').value = "";
	var observaciones = document.getElementById('observaciones').value 					= "";
	document.getElementById('constancia').checked 										= false;
	document.getElementById('profesion_actual').checked 								= false;
	document.getElementById('foto_instruccionAcademica').value 							= '';
	document.getElementById('boton_ingresar_instruccionAcademica').style.display 		= "block";
	document.getElementById('boton_modificar_instruccionAcademica').style.display		= "none";
	
}



function seleccionar_instruccionAcademica(idnivel_estudios, idprofesion, idmension, institucion, anio_egreso, observaciones, constancia, profesion_actual, idinstruccion_academica){
	document.getElementById('idnivel_estudio').value 	= idnivel_estudios;
	document.getElementById('idprofesion').value 	 	= idprofesion;
	document.getElementById('idmension').value 		 	= idmension;
	document.getElementById('institucion').value 		= institucion;
	document.getElementById('anio_egreso').value 		= anio_egreso;
	document.getElementById('observaciones').value 		= observaciones;
	if(constancia == "s"){
		document.getElementById('constancia').checked = true;	
	}else{
		document.getElementById('constancia').checked = false;
	}
	
	if(profesion_actual == "s"){
		document.getElementById('profesion_actual').checked = true;
	}else{
		document.getElementById('profesion_actual').checked = false;	
	}
	
	document.getElementById('boton_ingresar_instruccionAcademica').style.display = "none";
	document.getElementById('boton_modificar_instruccionAcademica').style.display = "block";
	
	document.getElementById('idinstruccion_academica').value = idinstruccion_academica;
}






//******************************************************************************************************************************
//******************************************************************************************************************************
//*************************************************** EXPERIENCIA LABORAL ******************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


function ingresarExperienciaLaboral(){
var idtrabajador 		= document.getElementById('idtrabajador').value;
var empresa 			= document.getElementById('empresa').value;
var desde_exp 			= document.getElementById('desde_exp').value;
var hasta_exp 			= document.getElementById('hasta_exp').value;
var tiempo_srv	 		= document.getElementById('tiempo_srv').value;
var motivo 				= document.getElementById('motivo').value;
var ultimo_cargo 		= document.getElementById('ultimo_cargo').value;
var direccion_empresa 	= document.getElementById('direccion_empresa').value;
var telefono 			= document.getElementById('telefono').value;
var observaciones 		= document.getElementById('observaciones').value;
var nombre_foto 		= document.getElementById('nombre_foto_experienciaLaboral').value;


var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			if(ajax.responseText == "exito"){
				mostrarMensajes("exito", "El registro se ah ingresado con exito");	
			}else{
				mostrarMensajes("error", "Disculpe el Registro no pudo ser ingresado, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
			}
			consultarExperienciaLaboral(idtrabajador);
			limpiarExperienciaLaboral();
		} 
	}
	ajax.send("nombre_foto="+nombre_foto+"&idtrabajador="+idtrabajador+"&empresa="+empresa+"&desde_exp="+desde_exp+"&hasta_exp="+hasta_exp+"&tiempo_srv="+tiempo_srv+"&motivo="+motivo+"&ultimo_cargo="+ultimo_cargo+"&direccion_empresa="+direccion_empresa+"&telefono="+telefono+"&observaciones="+observaciones+"&ejecutar=ingresarExperienciaLaboral");		
}




function modificarExperienciaLaboral(){
var idexperiencia_laboral 	= document.getElementById('idexperiencia_laboral').value;
var empresa 				= document.getElementById('empresa').value;
var desde_exp 				= document.getElementById('desde_exp').value;
var hasta_exp 				= document.getElementById('hasta_exp').value;
var tiempo_srv 				= document.getElementById('tiempo_srv').value;
var motivo 					= document.getElementById('motivo').value;
var ultimo_cargo 			= document.getElementById('ultimo_cargo').value;
var direccion_empresa 		= document.getElementById('direccion_empresa').value;
var telefono 				= document.getElementById('telefono').value;
var observaciones 			= document.getElementById('observaciones').value;
var nombre_foto 			= document.getElementById('nombre_foto_experienciaLaboral').value;
var ajax					= nuevoAjax();

	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultarExperienciaLaboral(document.getElementById("idtrabajador").value);
			limpiarExperienciaLaboral();
		} 
	}
	ajax.send("nombre_foto="+nombre_foto+"&idexperiencia_laboral="+idexperiencia_laboral+"&empresa="+empresa+"&desde_exp="+desde_exp+"&hasta_exp="+hasta_exp+"&tiempo_srv="+tiempo_srv+"&motivo="+motivo+"&ultimo_cargo="+ultimo_cargo+"&direccion_empresa="+direccion_empresa+"&telefono="+telefono+"&observaciones="+observaciones+"&ejecutar=modificarExperienciaLaboral");		
}


function eliminarExperienciaLaboral(idexperiencia_laboral){
	if(confirm("Seguro desea eliminar la experiencia laboral seleccionada?")){
		
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			consultarExperienciaLaboral(document.getElementById('idtrabajador').value);
		} 
		}
	ajax.send("idexperiencia_laboral="+idexperiencia_laboral+"&ejecutar=eliminarExperienciaLaboral");		
		
	}
}




function consultarExperienciaLaboral(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('lista_experienciaLaboral').innerHTML = ajax.responseText;
		} 
		}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarExperienciaLaboral");			
}





function seleccionar_experienciaLaboral(idexperiencia_laboral, empresa, desde, hasta, tiempo_servicio, motivo_salida, ultimo_cargo, direccion_empresa, telefono_empresa, observaciones){
	document.getElementById('idexperiencia_laboral').value = idexperiencia_laboral;
	document.getElementById('empresa').value = empresa;
	document.getElementById('desde_exp').value = desde;
	document.getElementById('hasta_exp').value = hasta;
	document.getElementById('tiempo_srv').value = tiempo_servicio;
	document.getElementById('motivo').value = motivo_salida;
	document.getElementById('ultimo_cargo').value = ultimo_cargo;
	document.getElementById('direccion_empresa').value = direccion_empresa;
	document.getElementById('telefono').value = telefono_empresa;
	document.getElementById('observaciones').value = observaciones;
	
	document.getElementById('boton_ingresar_experienciaLaboral').style.display = 'none';
	document.getElementById('boton_modificar_experienciaLaboral').style.display = 'block';
}


function limpiarExperienciaLaboral(){
	document.getElementById('idexperiencia_laboral').value = "";
	document.getElementById('empresa').value = "";
	document.getElementById('desde_exp').value = "";
	document.getElementById('hasta_exp').value = "";
	document.getElementById('tiempo_srv').value = "";
	document.getElementById('motivo').value = "";
	document.getElementById('ultimo_cargo').value = "";
	document.getElementById('direccion_empresa').value = "";
	document.getElementById('telefono').value = "";
	document.getElementById('observaciones').value = "";
	
	document.getElementById('boton_ingresar_experienciaLaboral').style.display = 'block';
	document.getElementById('boton_modificar_experienciaLaboral').style.display = 'none';
}



//***************************************************************************************************************************
//***************************************************************************************************************************
//***************************************************** MOVIMIENTOS ********************************************************
//***************************************************************************************************************************
//***************************************************************************************************************************


function buscarMovimientosTrabajador(idtrabajador){
 	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				document.getElementById("listaMovimientos").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=buscarMovimientosTrabajador");
}





function consultarFichaMovimientos(nomenclatura_ficha){
	var idtrabajador = document.getElementById('idtrabajador').value;
	if(nomenclatura_ficha == 0){
			document.getElementById('nro_ficha_movimientos').innerHTML = "Seleccione";
	}else{
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('nro_ficha_movimientos').innerHTML = ajax.responseText;
			document.getElementById('campo_nro_ficha_movimientos').value = ajax.responseText;
		} 
	}
	ajax.send("nomenclatura_ficha="+nomenclatura_ficha+"&ejecutar=consultarFichaMovimientos");			
	}
}




function ingresarMovimiento(){
	if(confirm("Seguro desea realizar este movimiento? recuerde que si es un movimiento de EGRESO el que intenta registrar, automaticamente esta persona saldra de todas las nominas donde este asociado")){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var fecha_movimiento = document.getElementById('fecha_movimiento_movimientos').value;
	var tipo_movimiento = document.getElementById('tipo_movimiento_movimientos').value;
	var justificacion = document.getElementById('justificacion_movimientos').value;
	var fecha_egreso = document.getElementById('fecha_egreso_movimientos').value;
	var causal = document.getElementById('causal_movimientos').value;
	var idnuevo_cargo = document.getElementById('nuevo_cargo_movimientos').value;
	var idubicacion_nueva = document.getElementById("nueva_ubicacion_funcional_movimientos").value;
	
	var fecha_reingreso = document.getElementById('fecha_reingreso_movimientos').value;
	var fecha_ingreso = document.getElementById('fecha_ingreso_movimientos').value;
	var desde = document.getElementById('desde_movimientos').value;
	var hasta = document.getElementById('hasta_movimientos').value;
	var centro_costo = document.getElementById('centro_costo_movimientos').value;
	
	
	if(document.getElementById('ficha_movimientos').value != "-"){
		var ficha = document.getElementById('ficha_movimientos').value+document.getElementById('campo_nro_ficha_movimientos').value;
	}else{
		var ficha = "";	
	}
	var ajax=nuevoAjax();
	if(justificacion == ""){
		mostrarMensajes("error", "Disculpe debe ingresar la justificacion del movimiento");
	}else{
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
				buscarMovimientosTrabajador(idtrabajador);
				limpiarDatos();
				consultarTrabajador(idtrabajador);
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ficha="+ficha+"&fecha_ingreso="+fecha_ingreso+"&centro_costo="+centro_costo+"&idtrabajador="+idtrabajador+"&fecha_movimiento="+fecha_movimiento+"&tipo_movimiento="+tipo_movimiento+"&justificacion="+justificacion+"&fecha_egreso="+fecha_egreso+"&causal="+causal+"&idnuevo_cargo="+idnuevo_cargo+"&idubicacion_nueva="+idubicacion_nueva+"&fecha_reingreso="+fecha_reingreso+"&desde="+desde+"&hasta="+hasta+"&ejecutar=ingresarMovimiento");
	}
}
}





function limpiarDatos(){
	document.getElementById('fecha_movimiento_movimientos').value = '';
	document.getElementById('tipo_movimiento_movimientos').value = 0;
	document.getElementById('justificacion_movimientos').value = '';
	document.getElementById('fecha_egreso_movimientos').value = '';
	document.getElementById('causal_movimientos').value = '';
	document.getElementById('nuevo_cargo_movimientos').value = 0;
	document.getElementById("nueva_ubicacion_funcional_movimientos").value = 0;
	document.getElementById('fecha_reingreso_movimientos').value = '';
	document.getElementById('desde_movimientos').value = '';
	document.getElementById('hasta_movimientos').value = '';
	document.getElementById('centro_costo_movimientos').value = 0;
	
	document.getElementById("celda_ficha_movimientos").style.display = 'none';
	document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
	document.getElementById("campo_nro_ficha_movimientos").value = '';
	document.getElementById("nro_ficha_movimientos").innerHTML = '';
		
}




function seleccionarModificar(idmovimiento, fecha_movimiento, idtipo_movimiento, justificacion, fecha_egreso, causal, idubicacion_nueva, fecha_reingreso, desde, hasta, idnuevo_cargo, relacion_laboral, goce_sueldo, afecta_cargo, afecta_ubicacion, afecta_tiempo, centro_costo, afecta_centro_costo, afecta_ficha){
	
	var idtrabajador = document.getElementById('idtrabajador').value;
	document.getElementById('idmovimiento_movimientos').value = idmovimiento;
	document.getElementById('fecha_movimiento_movimientos').value = fecha_movimiento;
	document.getElementById('tipo_movimiento_movimientos').value = idtipo_movimiento;
	document.getElementById('justificacion_movimientos').value = justificacion;
	document.getElementById('fecha_egreso_movimientos').value = fecha_egreso;
	document.getElementById('causal_movimientos').value = causal;
	document.getElementById('nuevo_cargo_movimientos').value = idnuevo_cargo;
	document.getElementById("nueva_ubicacion_funcional_movimientos").value = idubicacion_nueva;
	document.getElementById('fecha_reingreso_movimientos').value = fecha_reingreso;
	if(desde == "0000-00-00"){
		desde = "";
	}
	if(hasta == "0000-00-00"){
		hasta = "";
	}
	document.getElementById('desde_movimientos').value = desde;
	document.getElementById('hasta_movimientos').value = hasta;
	document.getElementById('centro_costo_movimientos').value = centro_costo;
	
	if(relacion_laboral=='si'){
		document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'block'; 
		document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'block'; 
	}else{
		document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'none';
		document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'none';
	}
	
	if(afecta_cargo == 'si'){
		document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'block';
		document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'block';
	}else{
		document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'none';
		document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'none';
	}
	
	if(afecta_ubicacion == "si"){
		document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'block';
		document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'block';
	}else{
		document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'none';	
		document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'none';	
	}
	
	
	if(afecta_tiempo == "si"){
		document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'block';
		document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'block';
	}else{
		document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'none';	
		document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'none';	
	}
	if(afecta_centro_costo == "si"){
		document.getElementById("celda_centro_costo_movimientos").style.display = 'block';
		document.getElementById("campo_centro_costo_movimientos").style.display = 'block';
	}else{
		document.getElementById("celda_centro_costo_movimientos").style.display = 'none';	
		document.getElementById("campo_centro_costo_movimientos").style.display = 'none';	
	}
	
	
	if(afecta_ficha == "si"){
		document.getElementById("celda_ficha_movimientos").style.display = 'block';
		document.getElementById("celda_campo_ficha_movimientos").style.display = 'block';		
	}else{
		document.getElementById("celda_ficha_movimientos").style.display = 'none';
		document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
	}
	
	document.getElementById('boton_ingresar_movimientos').style.display = 'none';
	document.getElementById('boton_editar_movimientos').style.display = 'block';
	document.getElementById('boton_eliminar_movimientos').style.display = 'none';
		
		
}






function seleccionarIngresar(relacion_laboral, goce_sueldo, afecta_cargo, afecta_ubicacion, afecta_tiempo, afecta_centro_costo, afecta_ficha){
	
	if(relacion_laboral=='si'){
		document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'block'; 
		document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'block'; 
	}else{
		document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'none';
		document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'none';
	}
	
	if(afecta_cargo == 'si'){
		document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'block';
		document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'block';
	}else{
		document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'none';
		document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'none';
	}
	
	if(afecta_ubicacion == "si"){
		document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'block';
		document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'block';
		
		
		
	}else{
		
		
		
		document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'none';	
		document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'none';	
	}
	
	
	if(afecta_tiempo == "si"){
		document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'block';
		document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'block';
	}else{
		document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'none';	
		document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'none';	
	}
	
	if(afecta_centro_costo == "si"){
		document.getElementById("celda_centro_costo_movimientos").style.display = 'block';
		document.getElementById("campo_centro_costo_movimientos").style.display = 'block';
	}else{

		document.getElementById("celda_centro_costo_movimientos").style.display = 'none';	
		document.getElementById("campo_centro_costo_movimientos").style.display = 'none';	
	}
	
	
	
	if(afecta_ficha == "si"){
		document.getElementById("celda_ficha_movimientos").style.display = 'block';
		document.getElementById("celda_campo_ficha_movimientos").style.display = 'block';		
	}else{
		document.getElementById("celda_ficha_movimientos").style.display = 'none';
		document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
	}
	
	
	
	document.getElementById('boton_ingresar_movimientos').style.display = 'block';
	document.getElementById('boton_editar_movimientos').style.display = 'none';
	document.getElementById('boton_eliminar_movimientos').style.display = 'none';
		
		
}




function desaparecerCampos(){
	if(document.getElementById('idmovimiento_movimientos').value == ''){
		document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'none'; 
		document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'none'; 
		document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'none';
		document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'none';
		document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'none';	
		document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'none';
		document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'none';	
		document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'none';
		document.getElementById("celda_centro_costo_movimientos").style.display = 'none';	
		document.getElementById("campo_centro_costo_movimientos").style.display = 'none';
		
		document.getElementById("celda_ficha_movimientos").style.display = 'none';
		document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
		
		document.getElementById("celda_ficha_movimientos").style.display = 'none';
		document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
		document.getElementById("campo_nro_ficha_movimientos").value = '';
		document.getElementById("nro_ficha_movimientos").innerHTML = '';
		
		
		document.getElementById('boton_ingresar_movimientos').style.display = 'block';
		document.getElementById('boton_editar_movimientos').style.display = 'none';
		document.getElementById('boton_eliminar_movimientos').style.display = 'none';
	}
}



function modificarMovimiento(idmovimiento){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var idmovimiento = document.getElementById('idmovimiento_movimientos').value;
	var fecha_movimiento = document.getElementById('fecha_movimiento_movimientos').value;
	var tipo_movimiento = document.getElementById('tipo_movimiento_movimientos').value;
	var justificacion = document.getElementById('justificacion_movimientos').value;
	var fecha_egreso = document.getElementById('fecha_egreso_movimientos').value;
	var causal = document.getElementById('causal_movimientos').value;
	var idnuevo_cargo = document.getElementById('nuevo_cargo_movimientos').value;
	var idubicacion_nueva = document.getElementById("nueva_ubicacion_funcional_movimientos").value;
	var fecha_reingreso = document.getElementById('fecha_reingreso_movimientos').value;
	var fecha_ingreso = document.getElementById('fecha_ingreso_movimientos').value;
	var desde = document.getElementById('desde_movimientos').value;
	var hasta = document.getElementById('hasta_movimientos').value;
	var centro_costo = document.getElementById('centro_costo_movimientos').value;
	if(document.getElementById('ficha_movimientos').value != "-"){
		var ficha = document.getElementById('ficha_movimientos').value+document.getElementById('campo_nro_ficha_movimientos').value;
	}else{
		var ficha = "";	
	}
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				buscarMovimientosTrabajador(document.getElementById("idtrabajador").value);
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("ficha="+ficha+"&idtrabajador="+idtrabajador+"&fecha_ingreso="+fecha_ingreso+"&centro_costo="+centro_costo+"&idmovimiento="+idmovimiento+"&fecha_movimiento="+fecha_movimiento+"&tipo_movimiento="+tipo_movimiento+"&justificacion="+justificacion+"&fecha_egreso="+fecha_egreso+"&causal="+causal+"&idnuevo_cargo="+idnuevo_cargo+"&idubicacion_nueva="+idubicacion_nueva+"&fecha_reingreso="+fecha_reingreso+"&desde="+desde+"&hasta="+hasta+"&ejecutar=modificarMovimient");
}







function eliminarMovimiento(idmovimiento){
	if(confirm("Seguro desea eliminar el movimiento seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
		if (ajax.readyState==4){
				buscarMovimientosTrabajador(document.getElementById("idtrabajador").value);
				limpiarDatos();
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idmovimiento="+idmovimiento+"&ejecutar=eliminarMovimiento");	
	}	
}



// ************************************************************************************************************************
// ************************************************************************************************************************
// ****************************************************** HISTORICO DE PERMISOS *******************************************
// ************************************************************************************************************************
// ************************************************************************************************************************



function validarHoras(str_hora){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			if(ajax.responseText == 0){
				document.getElementById('hr_inicio_historico').style.background = "#fec092";
				document.getElementById('hr_culminacion_historico').style.background = "#fec092";
			}
			else{
				document.getElementById('hr_inicio_historico').style.background = "#ffffff";
				document.getElementById('hr_culminacion_historico').style.background = "#ffffff";
			}
		} 
	}
	ajax.send("hr_inicio="+str_hora+"&ejecutar=validarHoras");		
}

function checkbox(valor){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				//document.getElementById('grilla').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=checkbox");		
}

function buscarPermisos(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				document.getElementById('grilla_historico').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=llenargrilla");		
}

function validarFechas(desde,hasta){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){

				document.getElementById('tiempo_historico').value = ajax.responseText;
				
			if(ajax.responseText == "La fecha de inicio ha de ser anterior a la fecha Actual"){
				document.getElementById('boton_ingresar_permiso').disabled = true;	
				document.getElementById('boton_ingresar_permiso').value = "Seleccione Fechas Validas";	
				
				document.getElementById('boton_ingresar_permiso').disabled = true;	
				document.getElementById('boton_ingresar_permiso').value = "Seleccione Fechas Validas";	
			}else{
				document.getElementById('boton_ingresar_permiso').disabled = false;
				document.getElementById('boton_ingresar_permiso').value = "Ingresar";
				
				document.getElementById('boton_ingresar_permiso').disabled = false;	
				document.getElementById('boton_ingresar_permiso').value = "Ingresar";
			}
				totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value);
		} 
	}
	ajax.send("hasta="+hasta+"&desde="+desde+"&ejecutar=validarFechas");		
}

function totalHoras(total_dias,hr_inicio,hr_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				document.getElementById('tiempo_horas_historico').value = ajax.responseText;
				
			if(ajax.responseText == "Selecione horas validas"){
				document.getElementById('boton_ingresar_permiso').disabled = true;	
				document.getElementById('boton_ingresar_permiso').value = "Selecione horas validas";	
				
				document.getElementById('boton_ingresar_permiso').disabled = true;	
				document.getElementById('boton_ingresar_permiso').value = "Selecione horas validas";	
			}else{
				document.getElementById('boton_ingresar_permiso').disabled = false;
				document.getElementById('boton_ingresar_permiso').value = "Ingresar";
				
				document.getElementById('boton_ingresar_permiso').disabled = false;	
				document.getElementById('boton_ingresar_permiso').value = "Ingresar";
			}
		} 
	}
	ajax.send("hr_inicio="+hr_inicio+"&hr_culminacion="+hr_culminacion+"&total_dias="+total_dias+"&ejecutar=total_Horas");		
}





function ingresarHistorico(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var fecha_solicitud_historico = document.getElementById('fecha_solicitud_historico').value;
	var fecha_inicio = document.getElementById('fecha_inicio_historico').value;
	var hora_inicio = document.getElementById('hr_inicio_historico').value;
	var fecha_culminacion = document.getElementById('fecha_culminacion_historico').value;
	var hora_culminacion = document.getElementById('hr_culminacion_historico').value;
	var fecha_solicitud = document.getElementById('fecha_solicitud_historico').value;
	var tiempo_total = document.getElementById('tiempo_historico').value;
	if(document.getElementById('desc_bono_historico').checked == true){
		var descuenta_bono_alimentacion = "si";		
	}else{
		var descuenta_bono_alimentacion = "no";
	}

	if(document.getElementById('motivo_historico').checked == true){
		var motivo = "si";
	}else{
		var motivo = "no";
	}
	
	if(document.getElementById('justificado1_historico').checked == true){
		var justificado = "si";
	}else{
		var justificado = "no";
	}
	
	if(document.getElementById('remunerado1_historico').checked == true){
		var remunerado = "si";	
	}else{
		var remunerado =  "no";
	}
	var aprobado_por = document.getElementById('aprobado_por_historico').value;
	var ci_aprobado = document.getElementById('ci_aprobado_historico').value;
	
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El permiso ah sido registrado con exito");
				}else{
					mostrarMensajes("error", "Disculpe el permiso no se pudo registrar, por favor intente de nuevo mas tarde... ("+ajax.responseText+")");
				}
				consultarHistoricoPermisos(idtrabajador);
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&fecha_solicitud_historico="+fecha_solicitud_historico+"&fecha_inicio="+fecha_inicio+"&hora_inicio="+hora_inicio+"&fecha_culminacion="+fecha_culminacion+"&hora_culminacion="+hora_culminacion+"&fecha_solicitud="+fecha_solicitud+"&tiempo_total="+tiempo_total+"&descuenta_bono_alimentacion="+descuenta_bono_alimentacion+"&motivo="+motivo+"&justificado="+justificado+"&remunerado="+remunerado+"&aprobado_por="+aprobado_por+"&ci_aprobado="+ci_aprobado+"&ejecutar=ingresarHistorico");		
	
}









function habilitaDeshabilita(){
    
	if (document.getElementById('desc_bono_historico').checked == true){
	   	document.getElementById('motivo_historico').disabled = false;
    }
	
    if (document.getElementById('desc_bono_historico').checked == false){
	    document.getElementById('motivo_historico').disabled = true;
    }
	
}

function consultarHistoricoPermisos(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				document.getElementById('grilla_historico').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarHistoricoPermisos");	
}






function seleccionarModificar(idhistorico_permisos, fecha_inicio, hora_inicio, fecha_culminacion, hora_culminacion, fecha_solicitud, tiempo_total, descuenta_bono_alimentacion, motivo, justificado, remunerado, aprobado_por, ci_aprobado){
	
	document.getElementById('idhistorico_permisos').value = idhistorico_permisos;
	document.getElementById('fecha_solicitud_historico').value = fecha_solicitud;
	document.getElementById('fecha_inicio_historico').value = fecha_inicio;
	document.getElementById('hr_inicio_historico').value = hora_inicio;
	document.getElementById('fecha_culminacion_historico').value = fecha_culminacion;
	document.getElementById('hr_culminacion_historico').value = hora_culminacion;
	document.getElementById('tiempo_historico').value = tiempo_total;
	
	if(descuenta_bono_alimentacion = "si"){
		document.getElementById('desc_bono_historico').checked = true;
	}else{
		document.getElementById('desc_bono_historico').checked = false;
	}

	if(motivo = "si"){
		document.getElementById('motivo_historico').checked = true;
	}else{
		document.getElementById('motivo_historico').checked = false;
	}
	
	if(justificado = "si"){
		document.getElementById('justificado1_historico').checked = true;
	}else{
		document.getElementById('justificado1_historico').checked = false;
	}
	
	if(remunerado = "si"){
		document.getElementById('remunerado1_historico').checked = true;
	}else{
		document.getElementById('remunerado1_historico').checked = false;
	}
	document.getElementById('aprobado_por_historico').value = aprobado_por;
	document.getElementById('ci_aprobado_historico').value = ci_aprobado;
	
	
	document.getElementById('boton_ingresar_permiso').style.display = 'none';
	document.getElementById('boton_modificar_permiso').style.display = 'block';
	
}











// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** RECONOCIMIENTOS ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 



function ingresarReconocimientos(){
	var idtrabajador 		= document.getElementById('idtrabajador').value;
	var tipo 		 		= document.getElementById('tipo_reconocimientos').value;
	var motivo		 		= document.getElementById('motivo_reconocimientos').value;
	var fecha		 		= document.getElementById('fecha_reconocimientos').value;
	var numero_documentos	= document.getElementById('numero_documentos_reconocimientos').value;
	var fecha_entrega		= document.getElementById('fecha_entrega_reconocimientos').value;
	
	if(document.getElementById('constancia_anexa_reconocimientos').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_reconocimientos').value;
	var observaciones		= document.getElementById('observaciones_reconocimientos').value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El reconocimiento fue ingresado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe el reconocimiento no se pudo ingresar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiarReconocimientos();
				consultarReconocimientos(idtrabajador);
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&tipo="+tipo+"&motivo="+motivo+"&fecha="+fecha+"&numero_documentos="+numero_documentos+"&fecha_entrega="+fecha_entrega+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&observaciones="+observaciones+"&ejecutar=ingresarReconocimientos");	
}





function modificarReconocimientos(){
	var idreconocimientos 	= document.getElementById('idreconocimientos').value;
	var tipo 		 		= document.getElementById('tipo_reconocimientos').value;
	var motivo		 		= document.getElementById('motivo_reconocimientos').value;
	var fecha		 		= document.getElementById('fecha_reconocimientos').value;
	var numero_documentos	= document.getElementById('numero_documentos_reconocimientos').value;
	var fecha_entrega		= document.getElementById('fecha_entrega_reconocimientos').value;

	if(document.getElementById('constancia_anexa_reconocimientos').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_reconocimientos').value;
	var observaciones		= document.getElementById('observaciones_reconocimientos').value;
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El reconocimiento fue Modificado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe el reconocimiento no se pudo Modificar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiarReconocimientos();
				consultarReconocimientos(document.getElementById('idtrabajador').value);
		} 
	}
	ajax.send("idreconocimientos="+idreconocimientos+"&tipo="+tipo+"&motivo="+motivo+"&fecha="+fecha+"&numero_documentos="+numero_documentos+"&fecha_entrega="+fecha_entrega+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&observaciones="+observaciones+"&ejecutar=modificarReconocimientos");	
}



function seleccionarReconocimientos(idreconocimientos, tipo, motivo, fecha, numero_documentos, fecha_entrega, constancia_anexa, nombre_imagen, observaciones){
	document.getElementById('idreconocimientos').value 							= idreconocimientos;
	document.getElementById('tipo_reconocimientos').value 						= tipo;
	document.getElementById('motivo_reconocimientos').value 					= motivo;
	document.getElementById('fecha_reconocimientos').value 						= fecha;
	document.getElementById('numero_documentos_reconocimientos').value 			= numero_documentos;
	document.getElementById('fecha_entrega_reconocimientos').value 				= fecha_entrega;
	
	if(constancia_anexa == "si"){
		document.getElementById('constancia_anexa_reconocimientos').checked		= true;
	}else{
		document.getElementById('constancia_anexa_reconocimientos').checked		= false;	
	}
	//document.getElementById('nombre_imagen_reconocimientos').value 			= nombre_imagen;
	document.getElementById('observaciones_reconocimientos').value 				= observaciones;	
	document.getElementById('boton_ingresar_reconocimientos').style.display 	= "none";
	document.getElementById('boton_modificar_reconocimientos').style.display 	= "block";
}



function eliminarReconocimiento(idreconocimientos){
	if(confirm("Seguro desea eliminar el reconocimiento seleccionado?")){
		var ajax				= nuevoAjax();
		
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){} 
			if (ajax.readyState==4){
				alert(ajax.responseText);	
				consultarReconocimientos(document.getElementById('idtrabajador').value);
			} 
		}
		ajax.send("idreconocimientos="+idreconocimientos+"&ejecutar=eliminarReconocimiento");		
	}
}


function consultarReconocimientos(idtrabajador){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){} 
		if (ajax.readyState==4){
			document.getElementById('listaReconocimientos').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarReconocimientos");			
}


function limpiarReconocimientos(){
	document.getElementById('idreconocimientos').value 							= "";
	document.getElementById('tipo_reconocimientos').value 						= "";
	document.getElementById('motivo_reconocimientos').value 					= "";
	document.getElementById('fecha_reconocimientos').value 						= "";
	document.getElementById('numero_documentos_reconocimientos').value 			= "";
	document.getElementById('fecha_entrega_reconocimientos').value 				= "";
	document.getElementById('constancia_anexa_reconocimientos').checked			= false;
	document.getElementById('nombre_imagen_reconocimientos').value 				= "";
	document.getElementById('observaciones_reconocimientos').value 				= "";	
	document.getElementById('boton_ingresar_reconocimientos').style.display 	= "block";
	document.getElementById('boton_modificar_reconocimientos').style.display 	= "none";
}












// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** SANCIONES ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 



function ingresarsanciones(){
	var idtrabajador 		= document.getElementById('idtrabajador').value;
	var tipo 		 		= document.getElementById('tipo_sanciones').value;
	var motivo		 		= document.getElementById('motivo_sanciones').value;
	var fecha		 		= document.getElementById('fecha_sanciones').value;
	var numero_documentos	= document.getElementById('numero_documentos_sanciones').value;
	var fecha_entrega		= document.getElementById('fecha_entrega_sanciones').value;
	
	if(document.getElementById('constancia_anexa_sanciones').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_sanciones').value;
	var observaciones		= document.getElementById('observaciones_sanciones').value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "La Sancion fue ingresado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe la Sancion no se pudo ingresar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiarsanciones();
				consultarsanciones(idtrabajador);
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&tipo="+tipo+"&motivo="+motivo+"&fecha="+fecha+"&numero_documentos="+numero_documentos+"&fecha_entrega="+fecha_entrega+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&observaciones="+observaciones+"&ejecutar=ingresarsanciones");	
}





function modificarsanciones(){
	var idsanciones 		= document.getElementById('idsanciones').value;
	var tipo 		 		= document.getElementById('tipo_sanciones').value;
	var motivo		 		= document.getElementById('motivo_sanciones').value;
	var fecha		 		= document.getElementById('fecha_sanciones').value;
	var numero_documentos	= document.getElementById('numero_documentos_sanciones').value;
	var fecha_entrega		= document.getElementById('fecha_entrega_sanciones').value;

	if(document.getElementById('constancia_anexa_sanciones').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_sanciones').value;
	var observaciones		= document.getElementById('observaciones_sanciones').value;
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El sancion fue Modificado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe el sancion no se pudo Modificar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiarsanciones();
				consultarsanciones(document.getElementById('idtrabajador').value);
		} 
	}
	ajax.send("idsanciones="+idsanciones+"&tipo="+tipo+"&motivo="+motivo+"&fecha="+fecha+"&numero_documentos="+numero_documentos+"&fecha_entrega="+fecha_entrega+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&observaciones="+observaciones+"&ejecutar=modificarsanciones");	
}



function seleccionarsanciones(idsanciones, tipo, motivo, fecha, numero_documentos, fecha_entrega, constancia_anexa, nombre_imagen, observaciones){
	document.getElementById('idsanciones').value 							= idsanciones;
	document.getElementById('tipo_sanciones').value 						= tipo;
	document.getElementById('motivo_sanciones').value 						= motivo;
	document.getElementById('fecha_sanciones').value 						= fecha;
	document.getElementById('numero_documentos_sanciones').value 			= numero_documentos;
	document.getElementById('fecha_entrega_sanciones').value 				= fecha_entrega;
	
	if(constancia_anexa == "si"){
		document.getElementById('constancia_anexa_sanciones').checked		= true;
	}else{
		document.getElementById('constancia_anexa_sanciones').checked		= false;	
	}
	//document.getElementById('nombre_imagen_sanciones').value 				= nombre_imagen;
	document.getElementById('observaciones_sanciones').value 				= observaciones;	
	document.getElementById('boton_ingresar_sanciones').style.display 		= "none";
	document.getElementById('boton_modificar_sanciones').style.display 		= "block";
}



function eliminarsancion(idsanciones){
	if(confirm("Seguro desea eliminar el sancion seleccionado?")){
		var ajax				= nuevoAjax();
		
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){} 
			if (ajax.readyState==4){
				alert(ajax.responseText);	
				consultarsanciones(document.getElementById('idtrabajador').value);
			} 
		}
		ajax.send("idsanciones="+idsanciones+"&ejecutar=eliminarsancion");		
	}
}


function consultarsanciones(idtrabajador){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){} 
		if (ajax.readyState==4){
			document.getElementById('listasanciones').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarsanciones");			
}


function limpiarsanciones(){
	document.getElementById('idsanciones').value 							= "";
	document.getElementById('tipo_sanciones').value 						= "";
	document.getElementById('motivo_sanciones').value 						= "";
	document.getElementById('fecha_sanciones').value 						= "";
	document.getElementById('numero_documentos_sanciones').value 			= "";
	document.getElementById('fecha_entrega_sanciones').value 				= "";
	document.getElementById('constancia_anexa_sanciones').checked			= false;
	document.getElementById('nombre_imagen_sanciones').value 				= "";
	document.getElementById('observaciones_sanciones').value 				= "";	
	document.getElementById('boton_ingresar_sanciones').style.display 		= "block";
	document.getElementById('boton_modificar_sanciones').style.display 		= "none";
}












// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** DECLARACION JURADA ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 



function ingresardeclaracion_jurada(){
	var idtrabajador 		= document.getElementById('idtrabajador').value;
	var tipo 		 		= document.getElementById('tipo_declaracion_jurada').value;
	var fecha_declaracion	= document.getElementById('fecha_declaracion_jurada').value;
	var fecha_entrega		= document.getElementById('fecha_entrega_declaracion_jurada').value;
	
	if(document.getElementById('constancia_anexa_declaracion_jurada').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_declaracion_jurada').value;
	var observaciones		= document.getElementById('observaciones_declaracion_jurada').value;
	
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "La Sancion fue ingresado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe la Sancion no se pudo ingresar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiardeclaracion_jurada();
				consultardeclaracion_jurada(idtrabajador);
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&tipo="+tipo+"&fecha_declaracion="+fecha_declaracion+"&fecha_entrega="+fecha_entrega+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&observaciones="+observaciones+"&ejecutar=ingresardeclaracion_jurada");	
}





function modificardeclaracion_jurada(){
	var iddeclaracion_jurada= document.getElementById('iddeclaracion_jurada').value;
	var tipo 		 		= document.getElementById('tipo_declaracion_jurada').value;
	var fecha_declaracion	= document.getElementById('fecha_declaracion_jurada').value;
	var fecha_entrega		= document.getElementById('fecha_entrega_declaracion_jurada').value;

	if(document.getElementById('constancia_anexa_declaracion_jurada').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_declaracion_jurada').value;
	var observaciones		= document.getElementById('observaciones_declaracion_jurada').value;
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El sancion fue Modificado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe el sancion no se pudo Modificar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiardeclaracion_jurada();
				consultardeclaracion_jurada(document.getElementById('idtrabajador').value);
		} 
	}
	ajax.send("iddeclaracion_jurada="+iddeclaracion_jurada+"&tipo="+tipo+"&fecha_declaracion="+fecha_declaracion+"&fecha_entrega="+fecha_entrega+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&observaciones="+observaciones+"&ejecutar=modificardeclaracion_jurada");	
}



function seleccionardeclaracion_jurada(iddeclaracion_jurada, tipo, fecha_declaracion, fecha_entrega, constancia_anexa, nombre_imagen, observaciones){
	document.getElementById('iddeclaracion_jurada').value 							= iddeclaracion_jurada;
	document.getElementById('tipo_declaracion_jurada').value 						= tipo;
	document.getElementById('fecha_declaracion_jurada').value 						= fecha_declaracion;
	document.getElementById('fecha_entrega_declaracion_jurada').value 				= fecha_entrega;
	
	if(constancia_anexa == "si"){
		document.getElementById('constancia_anexa_declaracion_jurada').checked		= true;
	}else{
		document.getElementById('constancia_anexa_declaracion_jurada').checked		= false;	
	}
	//document.getElementById('nombre_imagen_declaracion_jurada').value 				= nombre_imagen;
	document.getElementById('observaciones_declaracion_jurada').value 				= observaciones;	
	document.getElementById('boton_ingresar_declaracion_jurada').style.display 		= "none";
	document.getElementById('boton_modificar_declaracion_jurada').style.display 	= "block";
}



function eliminarDeclaracion_jurada(iddeclaracion_jurada){
	if(confirm("Seguro desea eliminar el sancion seleccionado?")){
		var ajax				= nuevoAjax();
		
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){} 
			if (ajax.readyState==4){
				//alert(ajax.responseText);	
				consultardeclaracion_jurada(document.getElementById('idtrabajador').value);
			} 
		}
		ajax.send("iddeclaracion_jurada="+iddeclaracion_jurada+"&ejecutar=eliminarDeclaracion_jurada");		
	}
}


function consultardeclaracion_jurada(idtrabajador){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){} 
		if (ajax.readyState==4){
			document.getElementById('listadeclaracion_jurada').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultardeclaracion_jurada");			
}


function limpiardeclaracion_jurada(){
	document.getElementById('iddeclaracion_jurada').value 							= "";
	document.getElementById('tipo_declaracion_jurada').value 						= "";
	document.getElementById('fecha_declaracion_jurada').value 						= "";
	document.getElementById('fecha_entrega_declaracion_jurada').value 				= "";
	document.getElementById('constancia_anexa_declaracion_jurada').checked			= false;
	document.getElementById('nombre_imagen_declaracion_jurada').value 				= "";
	document.getElementById('observaciones_declaracion_jurada').value 				= "";	
	document.getElementById('boton_ingresar_declaracion_jurada').style.display 		= "block";
	document.getElementById('boton_modificar_declaracion_jurada').style.display 	= "none";
}














// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** CURSOS REALIZADOS ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 



function ingresarCursos(){
	var idtrabajador 			= document.getElementById('idtrabajador').value;
	var denominacion 			= document.getElementById('denominacion_cursos').value;
	var detalle_contenido		= document.getElementById('detalle_contenido_cursos').value;
	var desde					= document.getElementById('fecha_desde_cursos').value;
	var hasta					= document.getElementById('fecha_hasta_cursos').value;
	var duracion				= document.getElementById('duracion_cursos').value;
	var institucion				= document.getElementById('institucion_cursos').value;
	var telefonos				= document.getElementById('telefonos_cursos').value;
	var realizado_por 			= document.getElementById('realizado_por').value;
	var tipo_duracion			= document.getElementById('tipo_duracion').value;

	
	if(document.getElementById('constancia_anexa_cursos').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}

	var nombre_imagen		= document.getElementById('nombre_imagen_cursos').value;
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El Curso fue ingresado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe el curso no se pudo ingresar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiarCursos();
				consultarCursos(idtrabajador);
		} 
	}
	ajax.send("tipo_duracion="+tipo_duracion+"&idtrabajador="+idtrabajador+"&denominacion="+denominacion+"&detalle_contenido="+detalle_contenido+"&desde="+desde+"&hasta="+hasta+"&duracion="+duracion+"&institucion="+institucion+"&telefonos="+telefonos+"&realizado_por="+realizado_por+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&ejecutar=ingresarCursos");	
}





function modificarCursos(){
	var idcursos			= document.getElementById('idcurso').value;
	var idtrabajador 		= document.getElementById('idtrabajador').value;
	var denominacion 		= document.getElementById('denominacion_cursos').value;
	var detalle_contenido	= document.getElementById('detalle_contenido_cursos').value;
	var desde				= document.getElementById('fecha_desde_cursos').value;
	var hasta				= document.getElementById('fecha_hasta_cursos').value;
	var duracion			= document.getElementById('duracion_cursos').value;
	var institucion			= document.getElementById('institucion_cursos').value;
	var telefonos			= document.getElementById('telefonos_cursos').value;
	var realizado_por 		= document.getElementById('realizado_por').value;
	var tipo_duracion		= document.getElementById('tipo_duracion').value;
	
	
	if(document.getElementById('constancia_anexa_cursos').checked == true){
	var constancia_anexa	= "si";
	}else{
	var constancia_anexa	= "no";	
	}
	
	var nombre_imagen		= document.getElementById('nombre_imagen_cursos').value;
	var ajax				= nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					mostrarMensajes("exito", "El Curso fue Modificado con Exito");
				}else{
					mostrarMensajes("error", "Disculpe el curso no se pudo Modificar, por favor intente de nuevo mas tarde ("+ajax.responseText+")");
				}
				limpiarCursos();
				consultarCursos(document.getElementById('idtrabajador').value);
		} 
	}
		ajax.send("tipo_duracion="+tipo_duracion+"&idcursos="+idcursos+"&denominacion="+denominacion+"&detalle_contenido="+detalle_contenido+"&desde="+desde+"&hasta="+hasta+"&duracion="+duracion+"&institucion="+institucion+"&telefonos="+telefonos+"&realizado_por="+realizado_por+"&constancia_anexa="+constancia_anexa+"&nombre_imagen="+nombre_imagen+"&ejecutar=modificarCursos");	
}



function seleccionarCursos(idcurso, denominacion, detalle_contenido, fecha_desde, fecha_hasta, duracion, institucion, telefonos, realizado_por, constancia_anexa, tipo_duracion){
	document.getElementById('idcurso').value 					= idcurso;
	document.getElementById('denominacion_cursos').value 		= denominacion;
	document.getElementById('detalle_contenido_cursos').value 	= detalle_contenido;
	document.getElementById('fecha_desde_cursos').value 		= fecha_desde;
	document.getElementById('fecha_hasta_cursos').value 		= fecha_hasta;
	document.getElementById('duracion_cursos').value 			= duracion;
	document.getElementById('institucion_cursos').value 		= institucion;
	document.getElementById('telefonos_cursos').value 			= telefonos;
	document.getElementById('realizado_por').value				= realizado_por; 
	document.getElementById('tipo_duracion').value				= tipo_duracion; 
	
	
	

	if(constancia_anexa == "si"){
	document.getElementById('constancia_anexa_cursos').checked 		 = true;
	}else{
	document.getElementById('constancia_anexa_cursos').checked 		 = false;
	}

	document.getElementById('boton_ingresar_cursos').style.display 		= "none";
	document.getElementById('boton_modificar_cursos').style.display 	= "block";
}



function eliminarCursos(idcursos){
	if(confirm("Seguro desea eliminar el Curso seleccionado?")){
		var ajax				= nuevoAjax();
		
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.onreadystatechange=function(){ 
			if(ajax.readyState == 1){} 
			if (ajax.readyState==4){
				//alert(ajax.responseText);	
				consultarCursos(document.getElementById('idtrabajador').value);
			} 
		}
		ajax.send("idcursos="+idcursos+"&ejecutar=eliminarCursos");		
	}
}


function consultarCursos(idtrabajador){
	var ajax				= nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function(){ 
		if(ajax.readyState == 1){} 
		if (ajax.readyState==4){
			document.getElementById('lista_cursos').innerHTML = ajax.responseText;	
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarCursos");			
}


function limpiarCursos(){
	document.getElementById('denominacion_cursos').value 				= "";
	document.getElementById('detalle_contenido_cursos').value 			= "";
	document.getElementById('fecha_desde_cursos').value 				= "";
	document.getElementById('fecha_hasta_cursos').value 				= "";
	document.getElementById('duracion_cursos').value 					= "";
	document.getElementById('institucion_cursos').value 				= "";
	document.getElementById('telefonos_cursos').value 				 	= "";
	document.getElementById('realizado_por').value 						= '';
	document.getElementById('tipo_duracion').value 						= '';
	document.getElementById('constancia_anexa_cursos').checked 		 	= false;
	document.getElementById('boton_ingresar_cursos').style.display 		= "block";
	document.getElementById('boton_modificar_cursos').style.display 	= "none";
}





// **************************************************************************************************************************
// **************************************************************************************************************************
// ***************************************************** HISTORICO DE VACACIONES ********************************************
// **************************************************************************************************************************
// **************************************************************************************************************************



function ajustarFechas(){
	var fecha_inicio_vacacion = document.getElementById('fecha_inicio_vacacion_vacaciones').value;
	var fecha_culminacion_vacacion = document.getElementById('fecha_culminacion_vacacion_vacaciones').value;
	var fecha_inicio_disfrute = document.getElementById("fecha_inicio_disfrute_vacaciones").value;
	var fecha_culminacion_disfrute = document.getElementById("fecha_reincorporacion_vacaciones").value;
	var cantidad_dias_feriados = document.getElementById("cantidad_feriados_vacaciones").value;
	var fecha_culminacion_ajustada = document.getElementById("fecha_reincorporacion_ajustada_vacaciones").value;
	
	var tiempo_disfrute_vacaciones = "";
	var tiempo_disfrute = "";
	var total_fecha_ajustada = "";
	
	
	
	var ajax=nuevoAjax();
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			tiempo_disfrute_vacaciones = ajax.responseText;
			
			
			var ajax2=nuevoAjax();
			ajax2.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
			ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax2.onreadystatechange=function() { 
				if(ajax2.readyState == 1){
					}
				if (ajax2.readyState==4){
					tiempo_disfrute = ajax2.responseText;
					var total_restante_disfrute = (parseInt(tiempo_disfrute_vacaciones)-parseInt(tiempo_disfrute));
					
							var ajax3=nuevoAjax();
							ajax3.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
							ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
							ajax3.onreadystatechange=function() { 
								if(ajax3.readyState == 1){
									}
								if (ajax3.readyState==4){
									total_fecha_ajustada = ajax3.responseText;
									var total_ajustado = parseInt(tiempo_disfrute_vacaciones)-parseInt(total_fecha_ajustada);
									var total = parseInt(total_restante_disfrute)+parseInt(total_ajustado)+parseInt(cantidad_dias_feriados);
									setTimeout("document.getElementById('tiempo_disfrute_vacaciones').value = "+parseInt(tiempo_disfrute_vacaciones), 100);
									if(document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value != ''){
										setTimeout("document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = "+total, 100);
										var total_pendiente = parseInt(document.getElementById('tiempo_pendiente_acumulado_oculto').value)+parseInt(total);
										setTimeout("document.getElementById('tiempo_pendiente_acumulado').value = "+total_pendiente, 300);
									}
								} 
							}
							ajax3.send("fecha_inicio="+fecha_inicio_vacacion+"&fecha_culminacion="+fecha_culminacion_ajustada+"&ejecutar=validarFechas_vacaciones");
					
					
					
					
				} 
			}
			ajax2.send("fecha_inicio="+fecha_inicio_disfrute+"&fecha_culminacion="+fecha_culminacion_disfrute+"&ejecutar=validarFechas_vacaciones");
			
			
			
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio_vacacion+"&fecha_culminacion="+fecha_culminacion_vacacion+"&ejecutar=validarFechas_vacaciones");
	
	
	
	
	
}







function consultarVacacionesPendientes(idtrabajador){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_pendiente_acumulado_oculto').value 	= ajax.responseText;
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarVacacionesPendientes");	
}





/*
function verificarFecha_historicoVacaciones(fecha_inicio, fecha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_disfrute_vacaciones').value 	= ajax.responseText;
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+fecha_culminacion+"&ejecutar=validarFechas_vacaciones");
}



function verificarFechaDisfrute_historicoVacaciones(fecha_inicio, fecha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 	= parseInt(document.getElementById('tiempo_disfrute_vacaciones').value) - parseInt(ajax.responseText)+parseInt(document.getElementById("cantidad_feriados_vacaciones").value);
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+fecha_culminacion+"&ejecutar=validarFechas_vacaciones");
}



function verificarFechaAjustada_historicoVacaciones(fecha_inicio, fecha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 	= (parseInt(document.getElementById('tiempo_disfrute_vacaciones').value) - parseInt(ajax.responseText))+parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value)+parseInt(document.getElementById("cantidad_feriados_vacaciones").value);
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+fecha_culminacion+"&ejecutar=validarFechas_vacaciones");
}



function aumentarFeriados(valor){
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value) + parseInt(valor);
}





//**********************************









function validarPeriodo_vacaciones(periodo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			if(sms == 0){
				document.getElementById('periodo_vacaciones').style = "#fec092";

				}else{
					if(sms == 1){
						document.getElementById('periodo_vacaciones').style.background = "#ffffff";
						}else{
							document.getElementById('periodo_vacaciones').style.background = "#fec092";							
							}
					}
		} 
	}
	ajax.send("periodo="+periodo+"&ejecutar=validarPeriodo_vacaciones");		
}

function validarFechasDisfrute_vacaciones(fecha_inicio,feha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('oculto_disfrutados_vacaciones').value 			= ajax.responseText;
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 	= ajax.responseText;
			
			document.getElementById('oculto_dias_vacaciones').value 		= ajax.responseText;
			var tiempo_disfrute 											= document.getElementById('tiempo_disfrute_vacaciones').value;
			var dias_disfrutados 											= document.getElementById('oculto_disfrutados_vacaciones').value;
			
			if(document.getElementById('cantidad_feriados_vacaciones').value != ''){
				restar_feriados_vacaciones(document.getElementById('cantidad_feriados_vacaciones').value);	
			}
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+feha_culminacion+"&ejecutar=validarFechas_vacaciones");		
}






function restar_feriados_vacaciones(cant_feriados){
	
	
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = (parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value)+parseInt(cant_feriados));
	
	
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = (parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value) - parseInt(document.getElementById('tiempo_disfrute_vacaciones').value));
	
	
	
	//alert(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value);
	
	
	
	/*var ajax=nuevoAjax();
	var dias_disfrute = document.getElementById('oculto_dias_vacaciones').value;
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){	
			
			document.getElementById('oculto_disfrutados_vacaciones').value = ajax.responseText;
			
			var tiempo_disfrute 	= document.getElementById('tiempo_disfrute_vacaciones').value;
			var dias_disfrutados 	= document.getElementById('oculto_disfrutados_vacaciones').value;
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("catidad_feriados="+cant_feriados+"&dias_disfrute="+dias_disfrute+"&ejecutar=cant_feriados");		
}




function validarFechas_vacaciones(fecha_inicio,feha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_disfrute_vacaciones').value 	= ajax.responseText;
			var tiempo_disfrute 											= document.getElementById('tiempo_disfrute_vacaciones').value;
			var dias_disfrutados 											= document.getElementById('oculto_disfrutados_vacaciones').value;
			obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+feha_culminacion+"&ejecutar=validarFechas_vacaciones");		
}

function reinicioAjustado_vacaciones(reinicio_ajustado,fecha_reincorporacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			alert(sms);
					if(sms != 0){
						alert("AQUI");
					var desde = document.getElementById('fecha_inicio_disfrute_vacaciones').value;
					var hasta = document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value;
					validarFechasDisfrute_vacaciones(desde,hasta);
					}
					
		} 
	}
	ajax.send("reinicio_ajustado="+reinicio_ajustado+"&fecha_reincorporacion="+fecha_reincorporacion+"&ejecutar=reinicioAjustado_vacaciones");		
}



function obtener_disfrutes_completos_vacaciones(tiempo_disfrute,tiempo_disfrutado){
	var ajax=nuevoAjax();
	var dias_disfrute = document.getElementById('oculto_dias_vacaciones').value;
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){	
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = ajax.responseText;
		} 
	}
	ajax.send("tiempo_disfrute="+tiempo_disfrute+"&tiempo_disfrutado="+tiempo_disfrutado+"&ejecutar=obten_disfrute_completo");		
}
*/



function llenargrilla_vacaciones(idtrabajador){
	//alert(idtrabajador);
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			document.getElementById('grilla_vacaciones').innerHTML = ajax.responseText;
			//document.getElementById('accion_vacaciones').value 	= "Guardar";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=llenargrilla_vacaciones");		
}

function llenaroculto_vacaciones(valor){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
					
			//document.getElementById('grilla').innerHTML = ajax.responseText;			
		} 
	}
	ajax.send("valor="+valor+"&ejecutar=llenaroculto_vacaciones");		
}
function accion_vacaciones(accion){
	var ajax=nuevoAjax();
	var idtrabajador 					= document.getElementById('idtrabajador').value;
	var periodo 						= document.getElementById('periodo_vacaciones').value;
	var numero_memorandum 				= document.getElementById('numero_memorandum_vacaciones').value;
	var fecha_memorandum 				= document.getElementById('fecha_memorandum_vacaciones').value;
	var fecha_inicio_vacacion			= document.getElementById('fecha_inicio_vacacion_vacaciones').value;
	var fecha_culminacion_vacacion 		= document.getElementById('fecha_culminacion_vacacion_vacaciones').value;
	var tiempo_disfrute 				= document.getElementById('tiempo_disfrute_vacaciones').value;
	var fecha_inicio_disfrute 			= document.getElementById('fecha_inicio_disfrute_vacaciones').value;
	var fecha_reincorporacion 			= document.getElementById('fecha_reincorporacion_vacaciones').value;
	var fecha_reincorporacion_ajustada 	= document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value;
	var tiempo_pendiente_disfrute 		= document.getElementById('tiempo_pendiente_disfrute_vacaciones').value;
	var dias_bonificacion 				= document.getElementById('dias_bonificacion_vacaciones').value;
	
	var oculto_dias 					= document.getElementById('oculto_dias_vacaciones').value;
	var oculto_disfrutados 				= document.getElementById('oculto_disfrutados_vacaciones').value;
	
	var cantidad_feriados 				= document.getElementById('cantidad_feriados_vacaciones').value;
	var monto_bono_vacacional 			= document.getElementById('monto_bono_vacacional_vacaciones').value;	
	
	var numero_orden_pago 				= document.getElementById('numero_orden_pago_vacaciones').value;
	var fecha_orden_pago 				= document.getElementById('fecha_orden_pago_vacaciones').value;
	var elaborado_por 					= document.getElementById('elaborado_por_vacaciones').value;
	var ci_elaborado 					= document.getElementById('ci_elaborado_vacaciones').value;
	var aprobado_por 					= document.getElementById('aprobado_por_vacaciones').value;
	var ci_aprobado 					= document.getElementById('ci_aprobado_vacaciones').value;
	var idhistorico_vacaciones 			= document.getElementById('idhistorico_vacaciones').value;
	
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			
			if(sms == 0){
				mostrarMensajes("error", "Seleccione Trabajador");
				}else{
					if(sms == 1){
						mostrarMensajes("error", "Campos vacios");
						}else{
							if(sms == 2){
								mostrarMensajes("exito", "Registro Exitoso");
								llenargrilla_vacaciones(idtrabajador);
								limpiarCampos_vacaciones();
								}else{
									if(sms == 5){
										mostrarMensajes("error", "Disculpe el perioro de vacaciones que intenta ingresar ya esta registrado")
									}else{
										mostrarMensajes("error", "Error Agregar Historico Vacacional: "+ajax.responseText);	
									}
									
									}
							}
					}
			//boton(ajax.responseText);
			//document.getElementById('grilla').innerHTML = ajax.responseText;
			consultarVacacionesPendientes(idtrabajador);
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&oculto_disfrutados="+oculto_disfrutados+"&oculto_dias="+oculto_dias+"&cantidad_feriados="+cantidad_feriados+"&idhistorico_vacaciones="+idhistorico_vacaciones+"&ci_aprobado="+ci_aprobado+"&aprobado_por="+aprobado_por+"&ci_elaborado="+ci_elaborado+"&elaborado_por="+elaborado_por+"&fecha_orden_pago="+fecha_orden_pago+"&numero_orden_pago="+numero_orden_pago+"&monto_bono_vacacional="+monto_bono_vacacional+"&dias_bonificacion="+dias_bonificacion+"&tiempo_pendiente_disfrute="+tiempo_pendiente_disfrute+"&fecha_reincorporacion_ajustada="+fecha_reincorporacion_ajustada+"&fecha_reincorporacion="+fecha_reincorporacion+"&numero_memorandum="+numero_memorandum+"&fecha_memorandum="+fecha_memorandum+"&fecha_inicio_vacacion="+fecha_inicio_vacacion+"&fecha_culminacion_vacacion="+fecha_culminacion_vacacion+"&fecha_inicio_disfrute="+fecha_inicio_disfrute+"&tiempo_disfrute="+tiempo_disfrute+"&periodo="+periodo+"&accion="+accion+"&ejecutar=accion_vacaciones");		
}

function limpiarCampos_vacaciones(){
	document.getElementById('periodo_vacaciones').style.background 				= "#ffffff";
	document.getElementById('periodo_vacaciones').value							= "";
	document.getElementById('numero_memorandum_vacaciones').value				= "";
	document.getElementById('fecha_memorandum_vacaciones').value				= "";
	document.getElementById('fecha_inicio_vacacion_vacaciones').value			= "";
	document.getElementById('fecha_culminacion_vacacion_vacaciones').value		= "";
	document.getElementById('tiempo_disfrute_vacaciones').value					= "";
	document.getElementById('fecha_inicio_disfrute_vacaciones').value			= "";
	document.getElementById('fecha_reincorporacion_vacaciones').value			= "";
	document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value	= "";
	document.getElementById('fecha_culminacion_vacacion_vacaciones').value		= "";
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value		= "";
	document.getElementById('dias_bonificacion_vacaciones').value				= "";
	document.getElementById('monto_bono_vacacional_vacaciones').value			= "";
	document.getElementById('numero_orden_pago_vacaciones').value				= "";
	document.getElementById('fecha_orden_pago_vacaciones').value				= "";
	document.getElementById('elaborado_por_vacaciones').value					= "";
	document.getElementById('ci_elaborado_vacaciones').value					= "";
	document.getElementById('aprobado_por_vacaciones').value					= "";
	document.getElementById('ci_aprobado_vacaciones').value						= "";
	document.getElementById('cantidad_feriados_vacaciones').value 				= 0;
	document.getElementById('oculto_dias_vacaciones').value 					= "";
	document.getElementById('oculto_disfrutados_vacaciones').value 				= "";
}

function llenarFormulario_vacaciones(id_historico_vacacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			document.getElementById('datos_vacaciones').innerHTML 	= ajax.responseText;
			// creacion de variables por id
			var idhistorico_vacaciones 					= document.getElementById('idhistorico_vacaciones_encontrado_vacaciones').value;
			//var idtrabajador = document.getElementById('idtrabajador_encontrado').value;
			var periodo 								= document.getElementById('periodo_encontrado_vacaciones').value;
			var numero_memorandum 						= document.getElementById('numero_memorandum_encontrado_vacaciones').value;
			var fecha_memorandum 						= document.getElementById('fecha_memorandum_encontrado_vacaciones').value;
			var fecha_inicio_vacacion 					= document.getElementById('fecha_inicio_vacacion_encontrado_vacaciones').value;
			var fecha_culminacion_vacacion 				= document.getElementById('fecha_culminacion_vacacion_encontrado_vacaciones').value;
			var tiempo_disfrute 						= document.getElementById('tiempo_disfrute_encontrado_vacaciones').value;
			var fecha_inicio_disfrute 					= document.getElementById('fecha_inicio_disfrute_encontrado_vacaciones').value;
			var fecha_reincorporacion 					= document.getElementById('fecha_reincorporacion_encontrado_vacaciones').value;
			var fecha_reincorporacion_ajustada 			= document.getElementById('fecha_reincorporacion_ajustada_encontrado_vacaciones').value;
			var dias_pendiente_disfrute 				= document.getElementById('dias_pendiente_disfrute_encontrado_vacaciones').value;
			var dias_bono 								= document.getElementById('dias_bono_encontrado_vacaciones').value;
			var monto_bonos 							= document.getElementById('monto_bonos_encontrado_vacaciones').value;
			var numero_orden_pago 						= document.getElementById('numero_orden_pago_encontrado_vacaciones').value;
			var fecha_cancelacion 						= document.getElementById('fecha_cancelacion_encontrado_vacaciones').value;
			var elaborado_por 							= document.getElementById('elaborado_por_encontrado_vacaciones').value;
			var ci_elaborado_por 						= document.getElementById('ci_elaborado_por_encontrado_vacaciones').value;
			var aprobada_por 							= document.getElementById('aprobada_por_encontrado_vacaciones').value;
			var ci_aprobado 							= document.getElementById('ci_aprobado_encontrado_vacaciones').value;
			var cantidad_feriados 						= document.getElementById('cantidad_feriadostabla_vacaciones').value;
			
			var oculto_dias 							= document.getElementById('oculto_dias_encontrado_vacaciones').value;
			var oculto_disfrutados 						= document.getElementById('oculto_disfrutados_encontrado_vacaciones').value;
			
			//asiganaciuon de valores por campos
			document.getElementById('idhistorico_vacaciones').value 			= idhistorico_vacaciones;
			
			document.getElementById('cantidad_feriados_vacaciones').value 				= cantidad_feriados;
			//document.getElementById('idtrabajador').value = idhistorico_vacaciones;
			document.getElementById('periodo_vacaciones').value 						= periodo;
			document.getElementById('numero_memorandum_vacaciones').value 				= numero_memorandum;
			document.getElementById('fecha_memorandum_vacaciones').value 				= fecha_memorandum;
			document.getElementById('fecha_inicio_vacacion_vacaciones').value 			= fecha_inicio_vacacion;
			document.getElementById('fecha_culminacion_vacacion_vacaciones').value 		= fecha_culminacion_vacacion;
			document.getElementById('tiempo_disfrute_vacaciones').value 				= tiempo_disfrute;
			document.getElementById('fecha_inicio_disfrute_vacaciones').value 			= fecha_inicio_disfrute;
			document.getElementById('fecha_reincorporacion_vacaciones').value 			= fecha_reincorporacion;
			document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value 	= fecha_reincorporacion_ajustada;			
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 		= dias_pendiente_disfrute;
			document.getElementById('dias_bonificacion_vacaciones').value 				= dias_bono;			
			document.getElementById('monto_bono_vacacional_vacaciones').value 			= monto_bonos;
			document.getElementById('numero_orden_pago_vacaciones').value 				= numero_orden_pago;			
			document.getElementById('fecha_orden_pago_vacaciones').value 				= fecha_cancelacion;
			document.getElementById('elaborado_por_vacaciones').value 					= elaborado_por;			
			document.getElementById('ci_elaborado_vacaciones').value 					= ci_elaborado_por;
			document.getElementById('aprobado_por_vacaciones').value 					= aprobada_por;
			document.getElementById('ci_aprobado_vacaciones').value 					= ci_aprobado;			
			document.getElementById('oculto_dias_vacaciones').value 					= oculto_dias;
			document.getElementById('oculto_disfrutados_vacaciones').value 				= oculto_disfrutados;

			
			
			
			document.getElementById('boton_ingresar_vacaciones').style.display = 'none';
			document.getElementById('boton_modificar_vacaciones').style.display = 'block';
			
		} 
	}
	ajax.send("id_historico="+id_historico_vacacion+"&ejecutar=llenarFormulario_vacaciones");		
}





function eliminar_vacaciones(idhistorico_vacaciones){
	if(confirm("Realmente desea eliminar el historico de vacaciones seleccionado?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			llenargrilla_vacaciones(document.getElementById('idtrabajador').value);
		} 
	}
	ajax.send("idhistorico_vacaciones="+idhistorico_vacaciones+"&ejecutar=eliminar_vacaciones");		
	}
}







// **************************************************************************************************************************
// **************************************************************************************************************************
// ***************************************************** CUENTAS BANCARIAS ********************************************
// **************************************************************************************************************************
// **************************************************************************************************************************











function consultarAsociados_cuentas_bancarias(){
	var idtrabajador = document.getElementById("idtrabajador").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById("lista_cuentas_bancarias").innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarAsociados_cuentas_bancarias");		
}





function ingresarCunetaBancaria(){
	var idtrabajador = document.getElementById("idtrabajador").value;
	var numero_cuenta = document.getElementById("numero_cuenta_cuentas_bancarias").value;
	var banco = document.getElementById("banco_cuentas_bancarias").value;
	var tipo_cuenta = document.getElementById("tipo_cuenta_cuentas_bancarias").value;
	var motivo_cuenta = document.getElementById("motivo_cuenta_cuentas_bancarias").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			mostrarMensajes("exito", "La cuenta bancaria fue ingresada con exito");
			limpiarDatos_cuentas_bancarias();
			consultarAsociados_cuentas_bancarias();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&numero_cuenta="+numero_cuenta+"&banco="+banco+"&tipo_cuenta="+tipo_cuenta+"&motivo_cuenta="+motivo_cuenta+"&ejecutar=ingresarCunetaBancaria");			
}





function modificarCunetaBancaria(){
	var idcuenta_bancaria = document.getElementById("idcuenta_bancaria").value;
	var numero_cuenta = document.getElementById("numero_cuenta_cuentas_bancarias").value;
	var banco = document.getElementById("banco_cuentas_bancarias").value;
	var tipo_cuenta = document.getElementById("tipo_cuenta_cuentas_bancarias").value;
	var motivo_cuenta = document.getElementById("motivo_cuenta_cuentas_bancarias").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos_cuentas_bancarias();
			consultarAsociados_cuentas_bancarias();
		} 
	}
	ajax.send("idcuenta_bancaria="+idcuenta_bancaria+"&numero_cuenta="+numero_cuenta+"&banco="+banco+"&tipo_cuenta="+tipo_cuenta+"&motivo_cuenta="+motivo_cuenta+"&ejecutar=modificarCunetaBancaria");			
}




function seleccionar_cuentas_bancarias(nombres, nro_cuenta, tipo, motivo, banco, idcuenta_bancaria){
	
	document.getElementById("idcuenta_bancaria").value = idcuenta_bancaria;
	document.getElementById("numero_cuenta_cuentas_bancarias").value = nro_cuenta;
	document.getElementById("banco_cuentas_bancarias").value = banco;
	document.getElementById("tipo_cuenta_cuentas_bancarias").value = tipo;
	document.getElementById("motivo_cuenta_cuentas_bancarias").value = motivo;
	
	document.getElementById("boton_ingresar_cuentas_bancarias").style.display = "none";
	document.getElementById("boton_modificar_cuentas_bancarias").style.display = "block";
	document.getElementById("boton_eliminar_cuentas_bancarias").style.display = "block";
}




function eliminarCuentaBancaria(){
	if(confirm("Realmente desea Eliminar esta Cuenta Bancaria?")){
	var idcuenta_bancaria = document.getElementById("idcuenta_bancaria").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/cuentas_bancarias_trabajador_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			limpiarDatos_cuentas_bancarias();
			consultarAsociados_cuentas_bancarias();
		} 
	}
	ajax.send("idcuenta_bancaria="+idcuenta_bancaria+"&ejecutar=eliminarCuentaBancaria");	
	}
}


function limpiarDatos_cuentas_bancarias(){
	document.getElementById("idcuenta_bancaria").value ="";
	document.getElementById("numero_cuenta_cuentas_bancarias").value = "";
	document.getElementById("banco_cuentas_bancarias").value = 0;
	document.getElementById("tipo_cuenta_cuentas_bancarias").value = "";
	document.getElementById("motivo_cuenta_cuentas_bancarias").value = "";
	
	document.getElementById("boton_ingresar_cuentas_bancarias").style.display = "block";
	document.getElementById("boton_modificar_cuentas_bancarias").style.display = "none";
	document.getElementById("boton_eliminar_cuentas_bancarias").style.display = "none";
}







function cargarPeriodosCesantes(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var desde = document.getElementById('desde_continuidad').value;
	var hasta = document.getElementById('hasta_continuidad').value;
	var tiempo = document.getElementById('tiempo_continuidad').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			desde = document.getElementById('desde_continuidad').value = "";
			hasta = document.getElementById('hasta_continuidad').value = "";
			tiempo = document.getElementById('tiempo_continuidad').value = "";
			listarPeriodosCesantes();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&desde="+desde+"&hasta="+hasta+"&tiempo="+tiempo+"&ejecutar=cargarPeriodosCesantes");	
}



function listarPeriodosCesantes(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById('lista_periodos_cesantes').innerHTML = ajax.responseText;
			document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=listarPeriodosCesantes");		
}



function seleccionarPeriodosCedentes(desde, hasta, tiempo, idperiodos_cedentes_trabajadores){
	document.getElementById('desde_continuidad').value = desde;
	document.getElementById('hasta_continuidad').value = hasta;
	document.getElementById('tiempo_continuidad').value = tiempo;
	document.getElementById('idperiodos_cedentes_trabajadores').value = idperiodos_cedentes_trabajadores;
	document.getElementById('img_continuidad').src = 'imagenes/modificar.png';
	document.getElementById('img_continuidad').onclick = modificarPeriodosCedentes;
}



function eliminarPeriodosCedentes(idperiodos_cedentes_trabajadores){
	if(confirm("Seguro dese eliminar este periodo Cedente?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
			}
			if (ajax.readyState==4){
				listarPeriodosCesantes();
			} 
		}
		ajax.send("idperiodos_cedentes_trabajadores="+idperiodos_cedentes_trabajadores+"&ejecutar=eliminarPeriodosCedentes");			
	}
}


function modificarPeriodosCedentes(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var desde = document.getElementById('desde_continuidad').value;
	var hasta = document.getElementById('hasta_continuidad').value;
	var tiempo = document.getElementById('tiempo_continuidad').value;
	var idperiodos_cedentes_trabajadores = document.getElementById("idperiodos_cedentes_trabajadores").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById('img_continuidad').src = 'imagenes/validar.png';
			document.getElementById('img_continuidad').onclick = cargarPeriodosCesantes;
			listarPeriodosCesantes();
			desde = document.getElementById('desde_continuidad').value = "";
			hasta = document.getElementById('hasta_continuidad').value = "";
			tiempo = document.getElementById('tiempo_continuidad').value = "";
			
		} 
	}
	ajax.send("idperiodos_cedentes_trabajadores="+idperiodos_cedentes_trabajadores+"&idtrabajador="+idtrabajador+"&desde="+desde+"&hasta="+hasta+"&tiempo="+tiempo+"&ejecutar=modificarPeriodosCedentes");	
}



function calcularTiempoPeriodosCedentes(){
	var desde = document.getElementById('desde_continuidad').value;
	var hasta = document.getElementById('hasta_continuidad').value;
	if(desde != "" && hasta != ""){	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('tiempo_continuidad').value = ajax.responseText;		
		} 
	}
	ajax.send("desde="+desde+"&hasta="+hasta+"&ejecutar=calcularTiempoPeriodosCedentes");	
	}
}






// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** PRESTACIONES ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************


function ingresarPrestaciones(){
	var anio = document.getElementById('anio_prestaciones').value;
	var mes = document.getElementById('mes_prestaciones').value;
	var sueldo = document.getElementById('sueldo_prestaciones').value;
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
			}
			document.getElementById('mes_prestaciones').value = 0;
			document.getElementById('sueldo_prestaciones').value = '';
			consultarPrestaciones();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&anio="+anio+"&mes="+mes+"&sueldo="+sueldo+"&ejecutar=ingresarPrestaciones");
}





function consultarPrestaciones(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var fecha_ingreso = document.getElementById('fecha_ingreso_prestaciones').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('lista_prestaciones').innerHTML = ajax.responseText;
			consultarTotalesGeneralesPrestaciones();
		} 
	}
	ajax.send("fecha_ingreso="+fecha_ingreso+"&idtrabajador="+idtrabajador+"&ejecutar=consultarPrestaciones");
}



function consultarSelectAniosPrestaciones(anio){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('div_anios_prestaciones').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("anio="+anio+"&ejecutar=consultarSelectAniosPrestaciones");
}


function eliminarPrestaciones(idtabla_prestaciones){
	if(confirm("seguro desea eliminar el registro seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
			}
			if (ajax.readyState==4){
				consultarPrestaciones();
			} 
		}
		ajax.send("idtabla_prestaciones="+idtabla_prestaciones+"&ejecutar=eliminarPrestaciones");	
	}
}




function actualizarSueldoPrestaciones(sueldo_prestaciones_modificar_idtabla_prestaciones, idtabla_prestaciones){
	var sueldo = document.getElementById(""+sueldo_prestaciones_modificar_idtabla_prestaciones+"").value;
	
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			consultarPrestaciones();
		} 
	}
	ajax.send("sueldo="+sueldo+"&idtabla_prestaciones="+idtabla_prestaciones+"&ejecutar=actualizarSueldoPrestaciones");	
}




function ingresarAdelanto(idtabla_prestaciones, adelanto_prestaciones, adelanto_interes){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			consultarPrestaciones();
		} 
	}
	ajax.send("idtabla_prestaciones="+idtabla_prestaciones+"&adelanto_prestaciones="+adelanto_prestaciones+"&adelanto_interes="+adelanto_interes+"&ejecutar=ingresarAdelanto");	
}



function eliminarAdelanto(idtabla_adelanto){
	if(confirm("Seguro desea eliminar el ADELANTO DE PRESTACIONES seleccionado?")){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			consultarPrestaciones();
		//alert(ajax.responseText);
		} 
	}
	ajax.send("idtabla_adelanto="+idtabla_adelanto+"&ejecutar=eliminarAdelanto");
	}
}



// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** VACACIONES ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************

function ingresarVacacionesVencidas(){
	var tipo = document.getElementById('tipo_vacaciones_vencidas').value;
	var periodo = document.getElementById('periodo_vacaciones_vencidas').value;
	var dias = document.getElementById('dias_vacaciones_vencidas').value;
	var sueldo = document.getElementById('sueldo_vacaciones_vencidas').value;
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
			}
			document.getElementById('tipo_vacaciones_vencidas').value = 0;
			document.getElementById('periodo_vacaciones_vencidas').value = 0;
			document.getElementById('dias_vacaciones_vencidas').value = '';
			document.getElementById('sueldo_vacaciones_vencidas').value = '';
			consultarVacaciones();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&tipo="+tipo+"&periodo="+periodo+"&dias="+dias+"&sueldo="+sueldo+"&ejecutar=ingresarVacacionesVencidas");
}



function consultarVacaciones(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('lista_vacaciones_vencidas').innerHTML = ajax.responseText;
			consultarTotalesGeneralesPrestaciones();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarVacaciones");
}



function consultarSelectAniosVacaciones(anio){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('periodo_vacaciones_vencidas').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("anio="+anio+"&ejecutar=consultarSelectAniosVacaciones");
}


function eliminarVacaciones(idvacaciones){
	if(confirm("seguro desea eliminar el registro seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
			}
			if (ajax.readyState==4){
				consultarVacaciones();
			} 
		}
		ajax.send("idvacaciones="+idvacaciones+"&ejecutar=eliminarVacaciones");	
	}
}




// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** AGUINALDO ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************

function ingresarAguinaldos(){
	var tipo = document.getElementById('tipo_aguinaldos').value;
	var periodo = document.getElementById('periodo_aguinaldos').value;
	var dias = document.getElementById('dias_aguinaldos').value;
	var sueldo = document.getElementById('sueldo_aguinaldos').value;
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
			}
			document.getElementById('tipo_aguinaldos').value = 0;
			document.getElementById('periodo_aguinaldos').value = 0;
			document.getElementById('dias_aguinaldos').value = '';
			document.getElementById('sueldo_aguinaldos').value = '';
			consultarAguinaldos();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&tipo="+tipo+"&periodo="+periodo+"&dias="+dias+"&sueldo="+sueldo+"&ejecutar=ingresarAguinaldos");
}



function consultarAguinaldos(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('lista_aguinaldos').innerHTML = ajax.responseText;
			consultarTotalesGeneralesPrestaciones();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarAguinaldos");
}



function consultarSelectAniosAguinaldos(anio){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('div_anios_aguinaldos').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("anio="+anio+"&ejecutar=consultarSelectAniosAguinaldos");
}


function eliminarAguinaldos(idaguinaldos){
	if(confirm("seguro desea eliminar el registro seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
			}
			if (ajax.readyState==4){
				consultarAguinaldos();
			} 
		}
		ajax.send("idaguinaldos="+idaguinaldos+"&ejecutar=eliminarAguinaldos");	
	}
}





// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** Deducciones ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************

function ingresarDeducciones(){
	var tipo = document.getElementById('tipo_deducciones').value;
	var periodo = document.getElementById('periodo_deducciones').value;
	var monto = document.getElementById('monto_deducciones').value;
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			if(ajax.responseText == "existe"){
				mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
			}
			document.getElementById('tipo_deducciones').value = "";
			document.getElementById('periodo_deducciones').value = 0;
			document.getElementById('monto_deducciones').value = '';
			consultarDeducciones();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&tipo="+tipo+"&periodo="+periodo+"&monto="+monto+"&ejecutar=ingresarDeducciones");
}



function consultarDeducciones(){
	var idtrabajador = document.getElementById('idtrabajador').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('lista_deducciones').innerHTML = ajax.responseText;
			consultarTotalesGeneralesPrestaciones();
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarDeducciones");
}



function consultarSelectAniosDeducciones(anio){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			document.getElementById('div_anios_deducciones').innerHTML = ajax.responseText;
		} 
	}
	ajax.send("anio="+anio+"&ejecutar=consultarSelectAniosDeducciones");
}


function eliminarDeducciones(iddeducciones){
	if(confirm("seguro desea eliminar el registro seleccionado?")){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
			}
			if (ajax.readyState==4){
				consultarDeducciones();
			} 
		}
		ajax.send("iddeducciones="+iddeducciones+"&ejecutar=eliminarDeducciones");	
	}
}




// CONSULTAR TOTALES


function consultarTotalesGeneralesPrestaciones(){
	var idtrabajador = document.getElementById("idtrabajador").value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/almacen/lib/inventario_materia_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
		}
		if (ajax.readyState==4){
			var partes = ajax.responseText.split("|.|");
			document.getElementById('total_prestaciones_acumuladas').innerHTML = partes[0];
			document.getElementById('total_interes_acumulado').innerHTML = partes[1];
			document.getElementById('total_prestaciones_interes_acumulado').innerHTML = partes[2];
			document.getElementById('total_vacaciones_acumuladas').innerHTML = partes[3];
			document.getElementById('total_aguinaldos_acumulados').innerHTML = partes[4];
			document.getElementById('total_deducciones').innerHTML = partes[5];
			document.getElementById('total_a_pagar').innerHTML = partes[6];
		} 
	}
	ajax.send("idtrabajador="+idtrabajador+"&ejecutar=consultarTotalesGeneralesPrestaciones");	
}
