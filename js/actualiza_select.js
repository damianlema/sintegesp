function buscarEnArray(array, dato){
	var x=0;
	while(array[x])
	{
		if(array[x]==dato) return x;
		x++;
	}
	return null;
}





function actualizaSelect(idSelectOrigen, ruta, tabla, idcreado){
		var idSelectDestino=idSelectOrigen;
		var ajax=nuevoAjax();
		ajax.open("POST", ruta, true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function(){ 
			if (ajax.readyState==4){
				opener.document.getElementById(idSelectDestino).parentNode.innerHTML=ajax.responseText;
				window.top.close();
			} 
		}
		ajax.send("tabla="+tabla+"&nombre="+idSelectDestino+"&idcreado="+idcreado);

}

