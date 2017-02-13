

function nuevoAjax(){ 
	var xmlhttp=false;
	try{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}




function setCheckBox(form, name, value){
	elts = document.forms[form].elements;
	if(typeof(elts)!='undefined'){
		for(var i=0 ; i < elts.length ; i++) {
			if(elts[i].name.indexOf(name)!=-1) elts[i].checked = value; 
		}
	}
}

function cleanNullField(form, name){
	elts = document.forms[form].elements;
	if(typeof(elts)!='undefined'){
		for(var i=0 ; i < elts.length ; i++) {
			if(elts[i].name.indexOf(name)!=-1) elts[i].value = ''; 
		}
	}
}

function setTableAction(form, index, action){
	document.forms[form].elements['modify['+index+']'].checked=true;
	document.forms[form].action.value=action;
	document.forms[form].submit();
}

function afficheCalque(calque){
	if (document.getElementById){
    	document.getElementById(calque).style.visibility="visible";
	} else {
		eval(layerRef + '["' + calque +'"]' + styleRef + '.visibility = "visible"');
	}
}

function cacheCalque(calque){
	if (document.getElementById){
		document.getElementById(calque).style.visibility="hidden";
	} else {
		eval(layerRef + '["' + calque +'"]' + styleRef + '.visibility = "hidden"');
	}
}

function ftype(){
	if(document.functprop.FunctType.selectedIndex==0){
		cacheCalque('Pfinal1');
		cacheCalque('Pfinal2');
	} else {
		afficheCalque('Pfinal1');
		afficheCalque('Pfinal2');
	}
}

function checkPath(){
	if(document.database.dbRealpath.value){
		document.database.dbpath.value = document.database.dbRealpath.value;
		document.database.dbRealpath.value = '';
	}
}




var tabRow = new Array;
function setRowColor(RowObj, numRow, Action, OrigColor, OverColor, ClickColor, bUseClassName){
    if (typeof(document.getElementsByTagName) != 'undefined') TheCells = RowObj.getElementsByTagName('td');
    else return false;
    if(!in_array(numRow, tabRow)){
		if(Action=='over') setColor = OverColor;
		else if(Action == 'out') setColor = OrigColor;
		else if(Action == 'click') {
			setColor = ClickColor;
			tabRow.push(numRow);
		}		
	} else if(Action == 'click'){
		tabIndex = in_array(numRow, tabRow);
		if(tabIndex>0) {
			tabRow[(tabIndex-1)] = '';
			setColor = OrigColor;
		}
	} else return;
	for(i=0 ; i<TheCells.length ; i++)
    if (bUseClassName) {
      if (bUseClassName && TheCells[i].className != setColor) 
        TheCells[i].className = setColor; 
    } else
      if (TheCells[i].style.backgroundColor != setColor)
        TheCells[i].style.backgroundColor = setColor; 
	return;
}




function in_array(needle, haystack){
	for(i=0 ; i<haystack.length ; i++) 
		if(haystack[i] == needle) return (i+1);
	return false;
}






function insertColumn(){
	sourceSel = document.sql.columnTable;
	destSQL = document.sql.DisplayQuery;
	var i=sourceSel.options.length;
	var first = true;
	var stringToDisplay='';
	while(i >= 0){
		if(sourceSel.options[i] && sourceSel.options[i].selected){
			if(first) {
				stringOut = '';
				first = false;
			} else {
				stringOut = ', ';			
			}
			stringToDisplay += stringOut+sourceSel.options[i].value;
			sourceSel.options[i].selected = false;
		}	
		i--;		
	}
	if(document.selection){
		destSQL.focus();
		selection = document.selection.createRange();
		if (selection.findText('*'))
		  selection.text = stringToDisplay;
		else if (selection.findText(' FROM'))
		  selection.text = ', '+stringToDisplay+' FROM';
    else
      selection.text = stringToDisplay;
    selection.empty();
 		document.sql.insertButton.focus();
	} else if(destSQL.selectionStart || destSQL.selectionStart == '0'){
		destSQL.value = destSQL.value.substring(0, destSQL.selectionStart)
						+ stringToDisplay
						+ destSQL.value.substring(destSQL.selectionEnd, destSQL.value.length);
	} else {
		destSQL += stringToDisplay;
	}
}








function clickIE4(){
if (event.button==2){
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
return false;
}
}
}
if (document.layers){
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
	document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("return false" )









function abreVentana(pagina){
	var ajax=nuevoAjax();
	ajax.open("POST", "funciones/contador_ventanas_ajax.php", true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4){
			//alert(ajax.responseText);
			eval('ventana'+ajax.responseText+ "=window.open(pagina,'ventana'+ajax.responseText,'scrollbars = yes, resizabled = no, width = 900, height=500')");
		}
	}
	ajax.send("ejecutar=sumar");
}





function cerrarSession(destino){
	if(destino == "botonCerrar"){
		if(confirm("Seguro desea salir del sistema?")){
			var ajax=nuevoAjax();
			ajax.open("POST", "lib/principal/cerrar.php", true);
			ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
			ajax.send(null);
			window.top.close();
		}
	}else{
/*	var ajax=nuevoAjax();
	ajax.open("POST", "funciones/contador_ventanas_ajax.php?ejecutar=consultar", true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==2){	
			var cont = ajax.responseText;
			if(cont != ""){
				alert(ajax.responseText);
				for(m=1;m<=cont;m++){
					if(eval('ventana'+m)){
						eval('ventana'+m+".close()");
					}
				}
			}
		}
	}
	ajax.send(null);*/
		var ajax=nuevoAjax();
		ajax.open("POST", "lib/principal/cerrar.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.send(null);
		window.top.close();
	}
}




function validar_minutos_transcurridos(){
	var ajax=nuevoAjax();
	ajax.open("POST", "funciones/validar_minutos_transcurridos_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if (ajax.readyState==4){
			if(ajax.responseText == "mayor"){
				cerrarSession('tiempo');return false
			}
		} 
	}
	ajax.send(null);	
}











// SE LE PASA EL NOMBRE DEL CAMPO QUE EN ESTE CASO SOLO SE CONCATENA PORQUE VARIOS SE LLAMAN IGUAL Y SE LE ASA EL EVENTO event
function validarFecha(campo, e){
	var valor = document.getElementById('fecha_factura'+campo).value;
	var tamanio = valor.length;
	var tcl = (document.all)?e.keyCode:e.which;
	var mydate=new Date();
	var anio=mydate.getFullYear();
	var dia=mydate.getDate();
	var mes=mydate.getMonth()+1;
	var checkOK = "0123456789-";
	var linea = "";
	var arrayMes = new Array();
	arrayMes[1]=31; 
	if (anio%4==0) arrayMes[2]=29; else arrayMes[2]=28;
	arrayMes[3]=31; 
	arrayMes[4]=30; 
	arrayMes[5]=31; 
	arrayMes[6]=30; 
	arrayMes[7]=31; 
	arrayMes[8]=31; 
	arrayMes[9]=30; 
	arrayMes[10]=31; 
	arrayMes[11]=30; 
	arrayMes[12]=31;
		if(tcl != 8){
			if ((tcl >= 48 && tcl <= 57) || (tcl >= 96 && tcl <= 105) || tcl == 8 || tcl == 0 || tcl == 109){
				var bloques = valor.split("-");
				if(tamanio == 4){
					if(parseInt(bloques[0]) > anio){
						alert("Disculpe el A&ntilde;O no puede mayor al actual");
						var newStr  = valor.substring (0, valor.length-4);			
						document.getElementById('fecha_factura'+campo).value = newStr;
					}else{
						document.getElementById('fecha_factura'+campo).value = valor+"-";
					}
				}else if(tamanio == 7){
					if(parseInt(bloques[0]) == anio && parseInt(bloques[1]) > mes){
						alert("Disculpe el MES no puede ser mayor al actual");
   						var newStr  = valor.substring (0, valor.length-2);			
						document.getElementById('fecha_factura'+campo).value = newStr;
					}else{
						document.getElementById('fecha_factura'+campo).value = valor+"-";	
					}
				}else if(tamanio == 10){
					if(parseInt(bloques[0]) == anio && parseInt(bloques[1]) == mes && parseInt(bloques[2]) > dia){
						alert("Disculpe el DIA no puede ser mayor al actual");
						var newStr  = valor.substring (0, valor.length-2);			
						document.getElementById('fecha_factura'+campo).value = newStr;
					}else{
						var m = parseInt(bloques[1]);
						var d = parseInt(bloques[2]);
						if (d>arrayMes[m]){
							alert("Disculpe el DIA es incorrecto");
							var newStr  = valor.substring (0, valor.length-2);			
							document.getElementById('fecha_factura'+campo).value = newStr;			  
						}	
					}
				}	
				
			}else{
					//alert("no es numero");
						if (valor.length!=0) {
							for (i = 0; i < tamanio; i++) { 
								ch = valor.charAt(i); 
								for (j = 0; j < checkOK.length; j++) 
									if (ch == checkOK.charAt(j)) linea = linea + ch; 
							}
							document.getElementById('fecha_factura'+campo).value=linea; 
						}

			}
		}	
}







function formatoNumero(idcampo, campoOculto) {
var res =  document.getElementById(idcampo).value; 
document.getElementById(campoOculto).value = res; 
if (document.getElementById(idcampo).value >= 0 && document.getElementById(idcampo).value <= 99999999999)  { 
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
	document.getElementById(idcampo).value = "0"; 
	//alert ("Debes indicar valores n&uacute;mericos en el campo "+idcampo);
	document.getElementById(idcampo).focus();
} 
}


function addslashes(str){
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\0/g,'\\0');
	return str;
}
