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
				//alert(ajax.responseText);
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
stIT("p0i0",["           Programación","","_self","","","","",1,26,"bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat","bold 8pt 'Arial'","#000000","none","transparent","imagenes/treeMenu/XPbanner1.gif","no-repeat","bold 8pt 'Arial'","#3399FF","none","transparent","imagenes/treeMenu/XPbanner1a.gif","no-repeat",1,0,"left","middle",235,28,"imagenes/treeMenu/XParrow1a.gif","imagenes/treeMenu/XParrow2a.gif","imagenes/treeMenu/XParrow1.gif","imagenes/treeMenu/XParrow2.gif",20,22,1]);
stBS("p1",[,,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=forward,enabled=0,Duration=0.20)",5,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=1,motion=reverse,enabled=0,Duration=0.20)",4,90],"p0");
stIT("p1i0",["Sectores","javascript:llamarReporte('sector', 'presupuesto')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png",20,20,"8pt 'Arial'","#FFFFFF",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000",,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'",,,,"imagenes/treeMenu/XPline.gif","repeat-y","8pt 'Arial'","#000000","underline",,"imagenes/treeMenu/XPline.gif","repeat-y",,,,,0,0,"","","","",0,0,0],"p0i0");
stIT("p1i1",["Programas","javascript:llamarFiltro('programa', 'presupuesto')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png"],"p1i0");
stIT("p1i2",["Sub-Programas","javascript:llamarFiltro('sprograma', 'presupuesto')",,,,"imagenes/treeMenu/pdf.png","imagenes/treeMenu/pdf.png"],"p1i0");
stIT("p1i3",["Proyectos","javascript:llamarFiltro('proyecto', 'presupuesto')"],"p1i2");
stIT("p1i4",["Actividades","javascript:llamarFiltro('actividad', 'presupuesto')"],"p1i2");
stIT("p1i5",["Unidad Ejecutora","javascript:llamarFiltro('unidade', 'presupuesto')"],"p1i2");
stIT("p1i6",["Categorías Programáticas","javascript:llamarFiltro('catprog', 'presupuesto')"],"p1i2");
stIT("p1i7",["Indice de Categorías Prog.","javascript:llamarReporte('icatprog', 'presupuesto')"],"p1i2");

stES();
stIT("p0i1",["           Presupuesto"],"p0i0");
stBS("p2",[],"p1");
stIT("p2i0",["Clasificador Presupuestario","javascript:llamarFiltro('clapre', 'presupuesto')"],"p1i0");
stIT("p2i1",["Ordinales","javascript:llamarFiltro('ordinal', 'presupuesto')"],"p1i1");
stIT("p2i2",["Tipos de Presupuesto","javascript:llamarReporte('tipopre', 'presupuesto')"],"p1i2");
stIT("p2i3",["Fuentes de Financiamiento","javascript:llamarReporte('fuentes', 'presupuesto')"],"p1i2");

stES();
stIT("p0i2",["           Ejecución Presupuestaria"],"p0i0");
stBS("p3",[],"p1");
stIT("p3i0",["Presupuesto Original","javascript:llamarFiltro('preori', 'presupuesto')"],"p1i0");
stIT("p3i5",["Detalle por Partida","javascript:llamarFiltro('detalle_por_partida', 'presupuesto')"],"p1i2");
stIT("p3i6",["Ejecución Detallada","javascript:llamarFiltro('ejecucion_detallada', 'presupuesto')"],"p1i2");
stIT("p3i7",["Disponibilidad Presupuestaria","javascript:llamarFiltro('disponibilidad_presupuestaria', 'presupuesto')"],"p1i2");
stIT("p3i1",["Resumen por Categorías","javascript:llamarFiltro('preres', 'presupuesto')"],"p1i1");
stIT("p3i8",["Disp. Presup. por Periodo","javascript:llamarFiltro('disponibilidad_presupuestaria_periodo', 'presupuesto')"],"p1i2");
//stIT("p3i16",["Ejecución por Trimestre","javascript:llamarFiltro('ejecucion_por_trimestre', 'presupuesto')"],"p1i2");
stIT("p3i18",["Rendicion Mensual","javascript:llamarFiltro('rendicion_mensual', 'presupuesto')"],"p1i2");
stIT("p3i3",["Consolidado por Categorías","javascript:llamarFiltro('porsector', 'presupuesto')"],"p1i2");
stIT("p3i4",["Consolidado por Sector","javascript:llamarFiltro('consolidado_sector', 'presupuesto')"],"p1i2");
stIT("p3i18",["Consolidado por Partidas","javascript:llamarFiltro('consolidado_por_partida', 'presupuesto')"],"p1i2");
stIT("p3i18",["Consolidado Agrupado","javascript:llamarFiltro('consolidado_agrupado', 'presupuesto')"],"p1i2");
stIT("p3i2",["Consolidado Estadistico x Part","javascript:llamarFiltro('consolidado', 'presupuesto')"],"p1i2");
stIT("p3i9",["Resumen Estadistico x Act","javascript:llamarFiltro('resumen_actividades', 'presupuesto')"],"p1i2");
stIT("p3i14",["Documentos por Partida","javascript:llamarFiltro('documentos_por_partida', 'presupuesto')"],"p1i2");
stIT("p3i17",["Compromisos en Tránsito","javascript:llamarFiltro('compromisos_en_transito', 'presupuesto')"],"p1i2");

stES();
stIT("p0i3",["           Formatos ONAPRE"],"p0i0");
stBS("p4",[],"p1");
stIT("p4i0",["Ejecución Mensual","javascript:llamarFiltro('mensual_onapre', 'presupuesto')"],"p1i0");
stIT("p4i5",["Ejecución Trimestral","javascript:llamarFiltro('trimestral_onapre', 'presupuesto')"],"p1i2");
stIT("p4i6",["Ejecución Anual","javascript:llamarFiltro('anual_onapre', 'presupuesto')"],"p1i2");
stIT("p4i8",["Consolidado por Sector","javascript:llamarFiltro('consolidado_sector_onapre', 'presupuesto')"],"p1i2");
stIT("p4i9",["Consolidado por Programa","javascript:llamarFiltro('consolidado_programa_onapre', 'presupuesto')"],"p1i2");
stIT("p4i7",["Consolidado por Categoria","javascript:llamarFiltro('consolidado_categoria_onapre', 'presupuesto')"],"p1i2");
stIT("p4i7",["Consolidado General","javascript:llamarFiltro('consolidado_general_onapre', 'presupuesto')"],"p1i2");


stES();
stIT("p0i6",["           Movimientos"],"p0i0");
stBS("p8",[],"p1");
stIT("p8i0",["Movimientos de Presupuesto","javascript:llamarFiltro('movimientos_presupuesto', 'presupuesto')"],"p1i0");
stIT("p8i1",["Movimientos por Partidas","javascript:llamarFiltro('movimientos_por_partida', 'presupuesto')"],"p1i0");
stIT("p8i2",["Movimientos por Categoria","javascript:llamarFiltro('movimientos_por_categoria', 'presupuesto')"],"p1i0");

stES();
stIT("p0i5",["           Proyecciones"],"p0i0");
stBS("p7",[],"p1");
stIT("p7i0",["Proyección Presupuestaria","javascript:llamarFiltro('proyeccion_presupuestaria', 'presupuesto')"],"p1i0");
stIT("p7i1",["Simular Solicitud Presupuesto","javascript:llamarFiltro('simular_solicitud_presupuesto', 'presupuesto')"],"p1i0");

stES();
stIT("p0i9",["           Control y Remisión"],"p0i0");
stBS("p10",[],"p1");
stIT("p10i0",["Documentos Remitidos","javascript:llamarFiltro('recibido_y_remitido', 'compras_servicios')"],"p1i0");
stIT("p10i1",["Documentos Recibidos","javascript:llamarFiltro('documentos_recibidos', 'compras_servicios')"],"p1i0");
stES();
stES();
stEM();

stCollapseSubTree('tree6575',0,1);