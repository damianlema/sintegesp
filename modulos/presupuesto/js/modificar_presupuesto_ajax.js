// JavaScript Document

function buscar_presupuesto(){
		var ajax=nuevoAjax();
		var anio = document.getElementById('anio').value;
		var tipo_presupuesto = document.getElementById('tipo_presupuesto').value;
		var fuente_financiamiento = document.getElementById('fuente_financiamiento').value;
		var categoria_programatica = document.getElementById('categoria_programatica').value;
		var textoabuscar = document.getElementById('textoabuscar').value;
		
		ajax.open("POST", "modulos/presupuesto/lib/modificar_presupuesto_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				//alert(ajax.responseText);
				document.getElementById('resultado').innerHTML = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("anio="+anio+"&tipo_presupuesto="+tipo_presupuesto+"&fuente_financiamiento="+fuente_financiamiento+"&categoria_programatica="+categoria_programatica+"&textoabuscar="+textoabuscar+"&ejecutar=buscar_presupuesto");
}

function actualizar_presupuesto(idRegistro_maestro){
		monto = document.getElementById('monto_presupuesto_original'+idRegistro_maestro).value;
		var ajax=nuevoAjax();
		ajax.open("POST", "modulos/presupuesto/lib/modificar_presupuesto_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() { 
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById('monto_presupuesto_original'+idRegistro_maestro).value = ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
		ajax.send("idRegistro_maestro="+idRegistro_maestro+"&monto="+monto+"&ejecutar=actualizar_presupuesto");
}
