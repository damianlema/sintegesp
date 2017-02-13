// JavaScript Document

function procesar(idfuente_financiamiento, idtipo_presupuesto, anio, idordinal, idcategoria_programatica, idpartidas){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/utilidades/lib/recalcular_presupuesto_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			//alert(ajax.responseText);
			if (ajax.readyState==4){
				alert("Proceso finalizado");
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto+"&anio="+anio+"&idordinal="+idordinal+"&idcategoria_programatica="+idcategoria_programatica+"&idpartidas="+idpartidas+"&ejecutar=procesar");
}


function abrirPartidas(){
	idcategoria = document.getElementById('id_categoria_programatica').value;
	window.open('lib/listas/listar_clasificador_presupuestario.php?destino=materiales&idcategoria='+idcategoria+'','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')	
}