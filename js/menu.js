window.status = "H&eacute;ctor Lema"

var empezar = false
var anclas = new Array ("ancla1","ancla2","ancla3","ancla4","ancla5","ancla6")
var capas = new Array("e1","e2","e3","e4","e5","e6")
var retardo 
var ocultar

function muestra(capa){
	xShow(capa);
}
function oculta(capa){
	xHide(capa);
}
function posiciona (){
	for (i=0;i<capas.length;i++){
		posx= xOffsetLeft(anclas[i])
		posy= xOffsetTop (anclas[i])
		xMoveTo(capas[i],posx,posy+20)
	}
}

window.onload = function() {
	posiciona()
	empezar = true
}
window.onresize = function() {
	posiciona()
}

function muestra_coloca(capa){
 if (empezar){
	for (i=0;i<capas.length;i++){
		if (capas[i] != capa) xHide(capas[i])
	}
	clearTimeout(retardo)
	xShow(capa)
 }
}

function oculta_retarda(capa){
 if (empezar){
	ocultar =capa
	clearTimeout(retardo)
	retardo = setTimeout("xHide('" + ocultar + "')",1000)
 }
}

function muestra_retarda(ind){
 if (empezar){
	clearTimeout(retardo)
 }
}