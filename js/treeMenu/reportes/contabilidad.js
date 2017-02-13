function nuevoAjax() { 
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

function llamarFiltro(nombre, modulo) {
       var ajax=nuevoAjax();
        ajax.open("POST", "lib/reportes/"+modulo+"/filtros.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax.onreadystatechange=function() { 
            if(ajax.readyState == 1){
                document.getElementById("divCargando").style.display = "block";
                }
            if (ajax.readyState==4){
                document.getElementById("aCerrar").style.display = "block";	
                document.getElementById("frmReporte").style.display = "block";
                frmReporte.location.href = "lib/reportes/"+modulo+"/filtros.php?nombre="+nombre;

                document.getElementById("divCargando").style.display = "none";
            } 
        }
        ajax.send("nombre="+nombre);
    }
	
function llamarReporte(nombre, modulo) {
        var ajax=nuevoAjax();
        ajax.open("POST", "lib/reportes/"+modulo+"/reportes.php", true);	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax.onreadystatechange=function() { 
            if(ajax.readyState == 1){
                document.getElementById("divCargando").style.display = "block";
                }
            if (ajax.readyState==4){
                document.getElementById("aCerrar").style.display = "block";	
                document.getElementById("frmReporte").style.display = "block";			
                frmReporte.location.href = "lib/reportes/"+modulo+"/reportes.php?nombre="+nombre;

                document.getElementById("divCargando").style.display = "none";
            } 
        }
        ajax.send("nombre="+nombre);
    }


stBM(260,"tree6575",[0,"","","imagenes/treeMenu/blank.gif",0,"left","auto","auto",1,0,-1,-1,-1,"none",0,"#5b80cc","#FFFFFF","","repeat-y",1,"imagenes/treeMenu/simple_f.gif","imagenes/treeMenu/simple_uf.gif",9,9,0,"","","","",0,0,3,1,"center",0,0,0,"","","","",""]);
stBS("p0",[0,1,"",-2,"",-2,50,25,3]);
stIT("p0i0",["           Ordenes de Pago","","_self","","","","",1,26,"bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat","bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat",1,0,"left","middle",235,28,"imagenes/treeMenu/XParrow1a.gif","imagenes/treeMenu/XParrow2a.gif","imagenes/treeMenu/XParrow1.gif","imagenes/treeMenu/XParrow2.gif",20,22,1]);
stBS("p1",[,,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=forward,enabled=0,Duration=0.20)",5,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=reverse,enabled=0,Duration=0.20)",4,90],"p0");
stIT("p1i0",["Orden de Pago","javascript:llamarFiltro('orden_pago_contabilidad', 'contabilidad')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png",20,20,"8pt 'Arial'","#FFFFFF",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'",,,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000","underline",,"imagenes/treeMenu/XPline.gif","repeat-y",,,,,0,0,"","","","",0,0,0],"p0i0");
stES();
stIT("p0i1",["           Ordenes de Compra/Servicio"],"p0i0");
stBS("p2",[],"p1");
stIT("p2i0",["Orden de Compra/Servicio","javascript:llamarFiltro('orden_compra_servicio_contabilidad', 'contabilidad')"],"p1i0");
stES();
stIT("p0i2",["           Cuentas Contables"],"p0i0");
stBS("p3",[],"p1");
stIT("p3i0",["Grupos","javascript:llamarReporte('contabilidad_grupo', 'contabilidad')"],"p1i0");
stIT("p3i1",["Sub Grupo","javascript:llamarReporte('contabilidad_subgrupo', 'contabilidad')"],"p1i0");
stIT("p3i2",["Rubro","javascript:llamarReporte('contabilidad_rubro', 'contabilidad')"],"p1i0");
stIT("p3i3",["Cuenta","javascript:llamarReporte('contabilidad_cuenta', 'contabilidad')"],"p1i0");
stIT("p3i4",["Sub Cuenta de P.O","javascript:llamarReporte('contabilidad_subcuenta1', 'contabilidad')"],"p1i0");
stIT("p3i3",["Sub Cuenta de S.O","javascript:llamarReporte('contabilidad_subcuenta2', 'contabilidad')"],"p1i0");
stIT("p3i4",["Desagregacion","javascript:llamarReporte('contabilidad_desagregacion', 'contabilidad')"],"p1i0");
stES();
stIT("p0i2",["           Movimientos Contables"],"p0i0");
stBS("p4",[],"p1");
stIT("p4i0",["Libro Diario Detallado","javascript:llamarFiltro('contabilidad_libro_diario', 'contabilidad')"],"p1i0");
//stIT("p4i0",["Libro Diario Resumido","javascript:llamarFiltro('contabilidad_libro_diarioR', 'contabilidad')"],"p1i0");
stIT("p4i2",["Libro Mayor","javascript:llamarFiltro('contabilidad_libro_mayor', 'contabilidad')"],"p1i0");
stIT("p4i3",["Balance de Comprobacion","javascript:llamarFiltro('contabilidad_comprobacion', 'contabilidad')"],"p1i0");
stIT("p4i4",["Balance General","javascript:llamarFiltro('contabilidad_balance_general', 'contabilidad')"],"p1i0");
stES();
stIT("p0i9",["           Control y Remisión"],"p0i0");
stBS("p10",[],"p1");
stIT("p10i0",["Documentos Remitidos","javascript:llamarFiltro('recibido_y_remitido', 'compras_servicios')"],"p1i0");
stIT("p10i1",["Documentos Recibidos","javascript:llamarFiltro('documentos_recibidos', 'compras_servicios')"],"p1i0");
stES();
stES();
stEM();

stCollapseSubTree('tree6575',0,1);