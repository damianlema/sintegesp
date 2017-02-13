// JavaScript Document

function buscarDato(){
	resul = document.getElementById('resultado');
	
	bus=document.buscartrabajador.cedula.value;
	ajax=nuevoAjax();
	ajax.open("POST", "busqueda.php",true);
	ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("busqueda="+bus)

}