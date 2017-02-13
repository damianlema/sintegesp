// JavaScript Document

function procesar(idfuente_financiamiento, idtipo_presupuesto, anio, idordinal, idcategoria_programatica){
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/recalcular_presupuesto_categoria_ajax.php", true);	
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			
			if (ajax.readyState==4){
				
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idfuente_financiamiento="+idfuente_financiamiento+"&idtipo_presupuesto="+idtipo_presupuesto+"&anio="+anio+"&idordinal="+idordinal+"&idcategoria_programatica="+idcategoria_programatica+"&ejecutar=procesar");
}


function abrirPartidas(){
	idcategoria = document.getElementById('id_categoria_programatica').value;
	window.open('lib/listas/listar_clasificador_presupuestario.php?destino=materiales&idcategoria='+idcategoria+'','buscar clasificador presupuestario','width=900, height = 500, scrollbars = yes')	
}