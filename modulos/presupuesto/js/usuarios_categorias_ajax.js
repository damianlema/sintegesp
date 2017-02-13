// JavaScript Document

/*********************************************************************************************************************************************************
************************************************** FUNCION PARA ABRIR Y CERRAR LAS LISTAD DE PARTIDAS ****************************************************
**********************************************************************************************************************************************************/


function pasarUsuario(){
	var cedula_usuario_seleccionado=document.getElementById("usuarios_activos").value;
	var idcategoria=document.getElementById("idcategoriaprogramatica").value;
	var ajax=nuevoAjax();
	if (cedula_usuario_seleccionado != ""){
		ajax.open("POST", "modulos/presupuesto/lib/usuarios_categorias_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById("usuarios_autorizados").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
	}else{
		mostrarMensajes("error", "Debe seleccionar un usuario a asignarlo a esta categoria");
	}
	ajax.send("cedula_usuario_seleccionado="+cedula_usuario_seleccionado+"&idcategoria="+idcategoria+"&ejecutar=pasarUsuario");
}






function regresarUsuario(){
	var cedula_usuario_seleccionado=document.getElementById("usuarios_asignados").value;
	var idcategoria=document.getElementById("idcategoriaprogramatica").value;
	var ajax=nuevoAjax();
	if (cedula_usuario_seleccionado != ""){
		ajax.open("POST", "modulos/presupuesto/lib/usuarios_categorias_ajax.php", true);
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
		ajax.onreadystatechange=function() {
			if(ajax.readyState == 1){
				document.getElementById("divCargando").style.display = "block";
				}
			if (ajax.readyState==4){
				document.getElementById("usuarios_autorizados").innerHTML=ajax.responseText;
				document.getElementById("divCargando").style.display = "none";
			} 
		}
	}else{
		mostrarMensajes("error", "Debe seleccionar un usuario asignado a esta categoria para quitarlo el acceso");
	}
	ajax.send("cedula_usuario_seleccionado="+cedula_usuario_seleccionado+"&idcategoria="+idcategoria+"&ejecutar=regresarUsuario");
}