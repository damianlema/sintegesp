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
stIT("p0i0",["           Cargos","","_self","","","","",1,26,"bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat","bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat",1,0,"left","middle",235,28,"imagenes/treeMenu/XParrow1a.gif","imagenes/treeMenu/XParrow2a.gif","imagenes/treeMenu/XParrow1.gif","imagenes/treeMenu/XParrow2.gif",20,22,1]);
stBS("p1",[,,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=forward,enabled=0,Duration=0.20)",5,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=reverse,enabled=0,Duration=0.20)",4,90,,5],"p0");
stIT("p1i0",["Grupos de Cargos","javascript:llamarFiltro('grupos', 'recursos_humanos')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png",20,20,"8pt 'Arial'","#FFFFFF",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'",,,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000","underline",,"imagenes/treeMenu/XPline.gif","repeat-y",,,,,0,0,"","","","",0,0,0],"p0i0");
stIT("p1i1",["Series de Cargos","javascript:llamarFiltro('series', 'recursos_humanos')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png"],"p1i0");
stIT("p1i2",["Cargos","javascript:llamarFiltro('cargos', 'recursos_humanos')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png"],"p1i0");
stES();
stIT("p0i1",["           Estudios"],"p0i0");
stBS("p2",[,,,,,,,,3],"p1");
stIT("p2i0",["Nivel de Estudio","javascript:llamarReporte('nivel', 'recursos_humanos')"],"p1i0");
stIT("p2i1",["Profesiones","javascript:llamarFiltro('profesion', 'recursos_humanos')"],"p1i1");
stIT("p2i2",["Mensiones","javascript:llamarReporte('mensiones', 'recursos_humanos')"],"p1i2");
stES();
stIT("p0i2",["           Otras"],"p0i0");
stBS("p3",[],"p2");
stIT("p3i0",["Estado Civil","javascript:llamarReporte('edocivil', 'recursos_humanos')"],"p1i0");
stIT("p3i1",["Parentesco","javascript:llamarReporte('parentesco', 'recursos_humanos')"],"p1i1");
stIT("p3i2",["Grupo Sanguíneo","javascript:llamarReporte('gsang', 'recursos_humanos')"],"p1i2");
stIT("p3i3",["Tipos de Movimiento","javascript:llamarReporte('tmov', 'recursos_humanos')"],"p1i2");
stES();
stIT("p0i3",["           Trabajador"],"p0i0");
stBS("p4",[],"p2");
//stIT("p4i0",["Lista de Trabajadores","javascript:llamarFiltro('listatrab', 'recursos_humanos')"],"p1i0");
stIT("p4i0",["Lista de Trabajadores","javascript:llamarFiltro('lista_trabajadores', 'recursos_humanos')"],"p1i0");
stIT("p4i0",["Prestaciones Sociales","javascript:llamarFiltro('lista_trabajadores_prestaciones', 'recursos_humanos')"],"p1i0");
stIT("p40i1",["Carga Familiar","javascript:llamarFiltro('trabajadores_carga_familiar', 'recursos_humanos')"],"p1i0");
stIT("p40i1",["Ficha del Trabajador","javascript:llamarFiltro('trabajadores_ficha', 'recursos_humanos')"],"p1i0");
stIT("p40i1",["Listado","javascript:llamarFiltro('trabajadores_listado', 'recursos_humanos')"],"p1i0");
stIT("p40i1",["Constancia de Trabajo","javascript:llamarFiltro('trabajadores_constancia', 'recursos_humanos')"],"p1i0");
stES();
stIT("p0i4",["           Certificación de Compromisos"],"p0i0");
stBS("p6",[],"p1");
stIT("p6i0",["Certificación de Compromisos","javascript:llamarFiltro('filtro_orden_compra_servicio', 'compras_servicios')"],"p1i0");
stES();
stIT("p0i5",["           Catálogo de Conceptos"],"p0i0");
stBS("p7",[],"p1");
stIT("p7i0",["Catálogo de Conceptos","javascript:llamarFiltro('catalogo_materiales', 'compras_servicios')"],"p1i0");
stES();
stIT("p0i9",["           Control y Remisión"],"p0i0");
stBS("p10",[],"p1");
stIT("p10i0",["Documentos Remitidos","javascript:llamarFiltro('recibido_y_remitido', 'compras_servicios')"],"p1i0");
stIT("p10i1",["Documentos Recibidos","javascript:llamarFiltro('documentos_recibidos', 'compras_servicios')"],"p1i0");
stES();
stES();
stEM();

stCollapseSubTree('tree6575',0,1);