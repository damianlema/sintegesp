// JavaScript Document

function nuevoAjax()
{ 
	var xmlhttp=false;
	try{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}



function hora()
    {
    var now = new Date();
    var yr = now.getYear();
    if (navigator.appName=='Netscape'){yr = yr + 1900;}
    var mName = now.getMonth() + 1;
    var dName = now.getDay() + 1;
    var dayNr = ((now.getDate()<10) ? "0" : "")+ now.getDate();
    if(dName==1) Day = "Domingo";
    if(dName==2) Day = "Lunes";
    if(dName==3) Day = "Martes";
    if(dName==4) Day = "Mi&eacute;rcoles";
    if(dName==5) Day = "Jueves";
    if(dName==6) Day = "Viernes";
    if(dName==7) Day = "S&aacute;bado";
    if(mName==1) Month="Enero";
    if(mName==2) Month="Febrero";
    if(mName==3) Month="Marzo";
    if(mName==4) Month="Abril";
    if(mName==5) Month="Mayo";
    if(mName==6) Month="Junio";
    if(mName==7) Month="Julio";
    if(mName==8) Month="Agosto";
    if(mName==9) Month="Septiembre";
    if(mName==10) Month="Octubre";
    if(mName==11) Month="Noviembre";
    if(mName==12) Month="Diciembre";
    var todaysDate =(Day + " " + dayNr + " de " + Month + " de " + yr);
   	return todaysDate;
   	//document.open();
    //document.write(todaysDate);
    }



function respaldar_transacciones(){
	var ajax=nuevoAjax();
	ajax.open("POST", "respaldar_transacciones.php", true);	
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			document.getElementById("tablaGeneral").innerHTML = "<h1>PROCESANDO... <img src='imagenes/cargando.gif'></h1>";
		}
		if (ajax.readyState==4){
				if(ajax.responseText == "exito"){
					document.getElementById("tablaGeneral").innerHTML = "<h1>Status: Respaldo Realizado con Exito al dia "+hora()+"</h1>";	
				}else{
					document.getElementById("tablaGeneral").innerHTML = "<h1>Status: Error al generar el respaldo, por favor contacte con el administrador de la aplicacion</h1><br><strong>Error Generado:</strong> "+ajax.responseText+"<br><br><strong>Al dia: "+hora()+"</strong>";	
				}
				
		} 
	}
	ajax.send(null);
}