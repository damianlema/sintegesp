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
stIT("p0i0",["           Admin. de Beneficiarios","","_self","","","","",1,26,"bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat","bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat",1,0,"left","middle",235,28,"imagenes/treeMenu/XParrow1a.gif","imagenes/treeMenu/XParrow2a.gif","imagenes/treeMenu/XParrow1.gif","imagenes/treeMenu/XParrow2.gif",20,22,1]);
stBS("p1",[,,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=forward,enabled=0,Duration=0.20)",5,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=reverse,enabled=0,Duration=0.20)",4,90],"p0");
stIT("p1i0",["Beneficiarios","javascript:llamarFiltro('beneficiario', 'compras_servicios')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png",20,20,"8pt 'Arial'","#FFFFFF",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'",,,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000","underline",,"imagenes/treeMenu/XPline.gif","repeat-y",,,,,0,0,"","","","",0,0,0],"p0i0");
stIT("p1i1",["Tipos de Beneficiario","javascript:llamarReporte('tipobene', 'recursos_humanos')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png"],"p1i0");
stIT("p1i2",["Estado de Beneficiario","javascript:llamarReporte('edobene', 'recursos_humanos')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png"],"p1i0");
stIT("p1i3",["Tipos de Persona","javascript:llamarReporte('tipopersona', 'recursos_humanos')"],"p1i2");
stIT("p1i4",["Tipos de Sociedad","javascript:llamarFiltro('tiposociedad', 'recursos_humanos')"],"p1i2");
stIT("p1i5",["Tipos de Empresa","javascript:llamarReporte('tipoempresa', 'recursos_humanos')"],"p1i2");
stIT("p1i6",["Documentos Entregados","javascript:llamarFiltro('dbeneficiario', 'compras_servicios')"],"p1i2");
stIT("p1i7",["Documentos Requeridos","javascript:llamarReporte('docreq', 'recursos_humanos')"],"p1i2");
stIT("p1i8",["Ficha de Beneficiarios","javascript:llamarFiltro('ficha_beneficiarios', 'compras_servicios')"],"p1i2");
stIT("p1i9",["Relación Doc. Vencida","javascript:llamarFiltro('relacion_documentacion_vencida', 'compras_servicios')"],"p1i2");
stES();
stIT("p0i1",["           Admin. de Materiales"],"p0i0");
stBS("p2",[],"p1");
stIT("p2i0",["Unidades de Medida","javascript:llamarFiltro('unidadm', 'compras_servicios')"],"p1i1");
stIT("p2i1",["Ramos de Materiales","javascript:llamarReporte('ramos', 'compras_servicios')"],"p1i2");
stIT("p2i2",["Impuestos","javascript:llamarFiltro('impuestos', 'compras_servicios')"],"p1i2");
stIT("p2i5",["Catálogo de Materiales","javascript:llamarFiltro('catalogo_materiales', 'compras_servicios')"],"p1i2");
stIT("p2i3",["S.N.C",""],"p1i2");
stBS("p3",[,0,,,,,,-1],"p0");
stIT("p3i0",["SNC Actividades","javascript:llamarFiltro('sncactividad', 'compras_servicios')"],"p1i2");
stIT("p3i1",["SNC Familia Actividad","javascript:llamarFiltro('sncfamilia', 'compras_servicios')"],"p1i2");
stIT("p3i2",["SNC Grupo Actividad","javascript:llamarFiltro('sncgrupo', 'compras_servicios')"],"p1i2");
stIT("p3i3",["SNC Detalle Actividad","javascript:llamarFiltro('sncdetalle', 'compras_servicios')"],"p1i2");
stIT("p3i4",["SNC","javascript:llamarFiltro('snc', 'compras_servicios')"],"p1i2");
stES();
stES();
stIT("p0i4",["           Reposición de Caja Chica"],"p0i0");
stBS("p6",[],"p1");
stIT("p6i0",["Orden de Compra/Servicio","javascript:llamarFiltro('filtro_orden_compra_servicio', 'compras_servicios')"],"p1i0");
stIT("p6i1",["Ordenes por Financiamiento","javascript:llamarFiltro('ordenes_compra_por_financiamiento', 'compras_servicios')"],"p1i0");
stIT("p6i1",["Rel. de Facturas por Rendición","javascript:llamarFiltro('relacion_facturas_por_rendicion', 'caja_chica')"],"p1i0");
stES();
stIT("p0i5",["           Sumarios"],"p0i0");
stBS("p7",[],"p1");
stIT("p7i0",["Sumario de Contrataciones","javascript:llamarFiltro('filtro_sumarios_snc', 'compras_servicios')"],"p1i0");
stIT("p7i1",["Sumario de Empresas","javascript:llamarFiltro('filtro_sumarios_empresas', 'compras_servicios')"],"p1i0");
stIT("p7i1",["Evaluación de Desempeño","javascript:llamarFiltro('filtro_sumarios_desempenio', 'compras_servicios')"],"p1i0");
stES();
stIT("p0i9",["           Control y Remisión"],"p0i0");
stBS("p10",[],"p1");
stIT("p10i0",["Documentos Remitidos","javascript:llamarFiltro('recibido_y_remitido', 'compras_servicios')"],"p1i0");
stIT("p10i1",["Documentos Recibidos","javascript:llamarFiltro('documentos_recibidos', 'compras_servicios')"],"p1i0");
stES();
stES();
stEM();

stCollapseSubTree('tree6575',0,1);