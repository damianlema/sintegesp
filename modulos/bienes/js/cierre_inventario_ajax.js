// JavaScript Document


function ingresar_cierreInventario(){
	var organizacion = document.getElementById('organizacion').value;
	var idnivel_organizacion = document.getElementById('idniveles_organizacionales').value;
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/cierre_inventario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
			}
		if (ajax.readyState==4){
			document.getElementById("divCargando").style.display = "none";
			window.location.href = 'principal.php?modulo=8&accion=1024';
		} 
	}
	ajax.send("organizacion="+organizacion+"&idnivel_organizacion="+idnivel_organizacion+"&ejecutar=ingresar_cierreInventario");		
}



function cargarNivelesOrganizacionales(idorganizacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/bienes/lib/cierre_inventario_ajax.php", true);	
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("divCargando").style.display = "block";
		}
		if (ajax.readyState==4){
			document.getElementById('campo_nivel_organizacional').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
		} 
	}
	ajax.send("idorganizacion="+idorganizacion+"&ejecutar=cargarNivelesOrganizacionales");	
}