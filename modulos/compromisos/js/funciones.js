// JavaScript Document

function aparecerClasificador(){
	if(document.getElementById('partida_propia').checked == true){
		document.getElementById('imageSelecCategoria').style.display = 'block';
		document.getElementById('divClasificador').style.display = 'block';
	}else{
		document.getElementById('imageSelecCategoria').style.display = 'none';
		document.getElementById('divClasificador').style.display = 'none';
	}
}