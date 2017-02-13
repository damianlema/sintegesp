// JavaScript Document
function abrirCerrarDatosExtra() {
    if (document.getElementById('datosExtra').style.display == "block") {
        document.getElementById('datosExtra').style.display = "none";
        document.getElementById('textoContraerDatosExtra').innerHTML = "Origen Presupuestario";
    } else {
        document.getElementById('datosExtra').style.display = "block";
        document.getElementById('textoContraerDatosExtra').innerHTML = "Ocultar";
    }
}
/*********************************************************************************************************************************************************
 ************************************************** FUNCION PARA ABRIR Y CERRAR LAS OBSERVACIONES ****************************************************
 **********************************************************************************************************************************************************/
function abrirCerrarObservaciones() {
    if (document.getElementById('divObservaciones').style.display == "block") {
        document.getElementById('divObservaciones').style.display = "none";
        document.getElementById('textoContraerObservaciones').innerHTML = "<img src='imagenes/comments.png' title = 'Anotar Observaciones'>";
    } else {
        document.getElementById('divObservaciones').style.display = "block";
        document.getElementById('textoContraerObservaciones').innerHTML = "<img src='imagenes/arrow_up.png' title='Cerrar Observaciones'>";
    }
}
/*********************************************************************************************************************************************************
 ************************************************** FUNCION PARA ABRIR Y CERRAR LAS LISTAD DE PARTIDAS ****************************************************
 **********************************************************************************************************************************************************/
function sumarValores(campoExento, campoSubtotal, campoImpuesto, campoTotalMostrado, campoTotal, campo_retenido, campo_total_a_pagar_oculto, campo_total_a_pagar) {
    exento = document.getElementById(campoExento).value;
    subtotal = document.getElementById(campoSubtotal).value;
    impuesto = document.getElementById(campoImpuesto).value;
    total = parseFloat(exento) + parseFloat(subtotal) + parseFloat(impuesto);
    total_retenido = document.getElementById(campo_retenido).value;
    //total_a_pagar = document.getElementById(campo_total_a_pagar_oculto).value;
    total_a_pagar = parseFloat(total) - parseFloat(total_retenido);
    document.getElementById(campoTotalMostrado).value = total;
    formatoNumero(campoTotalMostrado, campoTotal);
    document.getElementById(campo_total_a_pagar).value = total_a_pagar;
    formatoNumero(campo_total_a_pagar, campo_total_a_pagar_oculto);
}

function actualizarMontosFinales(exento, subtotal, impuesto, retenido) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var id_orden_pago = document.getElementById('id_orden_pago').value;
    var exento = document.getElementById(exento).value;
    var subtotal = document.getElementById(subtotal).value;
    var impuesto = document.getElementById(impuesto).value;
    var retenido = document.getElementById(retenido).value;
    if (retenido == "") {
        retenido = 0;
    }
    var total = parseFloat(exento) + parseFloat(subtotal) + parseFloat(impuesto);
    var total_a_pagar = parseFloat(total) - parseFloat(retenido);
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {}
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&exento=" + exento + "&subtotal=" + subtotal + "&impuesto=" + impuesto + "&retenido=" + retenido + "&total=" + total + "&total_a_pagar=" + total_a_pagar + "&ejecutar=actualizarMontosFinales");
}

function formatoNumero(idcampo, campoOculto) {
    //alert('mostrado '+idcampo);
    //alert('oculto '+campoOculto);
    var res = document.getElementById(idcampo).value;
    document.getElementById(campoOculto).value = res;
    if (document.getElementById(idcampo).value >= 0 && document.getElementById(idcampo).value <= 99999999999) {
        resultado = parseFloat(res).toFixed(2).toString();
        resultado = resultado.split(".");
        var cadena = "";
        cont = 1
        for (m = resultado[0].length - 1; m >= 0; m--) {
            cadena = resultado[0].charAt(m) + cadena
            cont % 3 == 0 && m > 0 ? cadena = "." + cadena : cadena = cadena
            cont == 3 ? cont = 1 : cont++
        }
        document.getElementById(idcampo).value = cadena + "," + resultado[1];
    } else {
        document.getElementById(idcampo).value = "0";
        document.getElementById(idcampo).focus();
    }
}

function mostrarListaPresupuesto() {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (document.getElementById('multi_categoria').value == 'si') {
        multi_categoria = 'si';
    } else {
        multi_categoria = 'no';
    }
    var materiales = window.open("modulos/administracion/lib/listar_presupuesto.php?categoria_programatica=" + document.getElementById('id_categoria_programatica').value + "&anio=" + document.getElementById('anio').value + "&idfuente_financiamiento=" + document.getElementById('idfuente_financiamiento').value + "&idtipo_presupuesto=" + document.getElementById('idtipo_presupuesto').value + "&idordinal=" + document.getElementById('id_ordinal').value + "&origen=pagos&multi_categoria=" + multi_categoria, "materiales", "resizable = no, scrollbars=yes, width = 1200, height= 500");
    //materiales.document.getElementById('anio').value = 2010;
}

function abrirCerrarPartidas() {
    if (document.getElementById('divPartidas').style.display == "block") {
        document.getElementById('divPartidas').style.display = "none";
        //document.getElementById('formularioPartidas').style.display="none";
        document.getElementById('textoContraerPartidas').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
    } else {
        document.getElementById('divPartidas').style.display = "block";
        //document.getElementById('formularioPartidas').style.display="block";
        document.getElementById('textoContraerPartidas').innerHTML = "<img src='imagenes/cerrar.gif' title='Cerrar'>";
    }
}
/********************************************************************************************************************************************************
 *************************************************** FUNCION PARA ABRIR Y CERRAR LAS LISTAS DE MATERIALES **********************************************
 **********************************************************************************************************************************************************/
function abrirCerrarMateriales() {
    if (document.getElementById('divMateriales').style.display == "block") {
        document.getElementById('divMateriales').style.display = "none";
        document.getElementById('formularioMateriales').style.display = "none";
        document.getElementById('textoContraerMateriales').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
    } else {
        document.getElementById('formularioMateriales').style.display = "block";
        document.getElementById('divMateriales').style.display = "block";
        document.getElementById('textoContraerMateriales').innerHTML = "<img src='imagenes/cerrar.gif' title = 'Cerrar'>";
    }
}
/********************************************************************************************************************************************************
 ********************************************* FUCION PARA ABRIR Y CERRAR LOS PROVEEDORES ****************************************************************
 **********************************************************************************************************************************************************/
function abrirCerrarProveedores() {
    if (document.getElementById('formularioProveedores').style.display == "block") {
        document.getElementById('tablaProveedor').width = '800';
        document.getElementById('formularioProveedores').style.display = "none";
        document.getElementById('textoContraerProveedores').innerHTML = "<img src='imagenes/abrir.gif' title = 'Abrir'>";
    } else {
        document.getElementById('formularioProveedores').style.display = "block";
        document.getElementById('textoContraerProveedores').innerHTML = "<img src='imagenes/cerrar.gif' title = 'Cerrar'>";
    }
}
/********************************************************************************************************************************************************
 *********************************************** FUNCION PARA CONSULTAR LOS PEDIDOS DE LOS PROVEEDORES ***************************************************
 **********************************************************************************************************************************************************/
function consultarPedidosProveedores(id_beneficiarios, tipo, id_orden_pago, id_categoria_programatica, forma_pago) {
    //alert(id_categoria_programatica);
    //if (id_categoria_programatica != 0){
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var anio = document.getElementById('anio').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            //document.getElementById("listaSolicitudesProveedor").style.display = "block";
            if (ajax.responseText.indexOf("noTieneGanadas") != -1) {
                document.getElementById("listaSolicitudesProveedor").innerHTML = "Este Proveedor no tiene nigun Compromiso Por Causar";
                document.getElementById("solicitudes").value = "noTiene";
                //document.getElementById('tablaProveedor').style.display = 'none';
            } else {
                document.getElementById("listaSolicitudesProveedor").innerHTML = ajax.responseText;
                document.getElementById("solicitudes").value = "siTiene";
                //document.getElementById('tablaProveedor').style.display = 'block';
            }
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_beneficiarios=" + id_beneficiarios + "&id_orden_pago=" + id_orden_pago + "&tipo=" + tipo + "&id_categoria_programatica=" + id_categoria_programatica + "&anio=" + document.getElementById('anio').value + "&idfuente_financiamiento=" + document.getElementById('idfuente_financiamiento').value + "&idtipo_presupuesto=" + document.getElementById('idtipo_presupuesto').value + "&idordinal=" + document.getElementById('id_ordinal').value + "&forma_pago=" + forma_pago + "&anio=" + anio + "&ejecutar=consultarSolicitudesProveedor");
    //}
}
/********************************************************************************************************************************************************
 ***************************************************** REFRESCAR LA LISTA DE SOLICITUDES DE COTIZACION POR PROVEEDOR ************************************
 **********************************************************************************************************************************************************/
function refrescarListaSolicitudes(id_beneficiarios, tipo, id_orden_pago, id_categoria_programatica) {
    if (document.getElementById('listaSolicitudesProveedor').style.display == 'block') {
        consultarPedidosProveedores(id_beneficiarios, tipo, id_orden_pago, id_categoria_programatica, document.getElementById('forma_pago').value);
    }
}
/*******************************************************************************************************************************************************
 ****************************************** FUNCION PARA SELECCIONAR Y DESELECCIONAR LAS SOLICITUDES DE LA LISTA *****************************************
 **********************************************************************************************************************************************************/
function seleccionDeseleccionListaSolicitud(cantRegistros) {
    //alert(cantRegistros);
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var seleccionados = 0;
    formulario = document.getElementById("formSolicitudesFinalizadas");
    for (var i = 0; i < formulario.elements.length; i++) {
        var elemento = formulario.elements[i];
        if (elemento.type == "checkbox" || elemento.type == "radio") {
            if (elemento.checked) {
                seleccionados++;
            }
        }
    }
    if (seleccionados > 0) {
        document.getElementById('tipo_orden').disabled = true;
        document.getElementById("forma_pago").disabled = true;
        document.getElementById("nombre_categoria").disabled = true;
        document.getElementById("nombre_proveedor").disabled = true;
        //document.getElementById('buscarProveedor').style.display='none';
        //document.getElementById('buscarCategoriaProgramatica').style.visibility='hidden';
        document.getElementById('seleccionado').value = 'si';
    } else {
        document.getElementById('tipo_orden').disabled = false;
        document.getElementById("forma_pago").disabled = false;
        document.getElementById("nombre_categoria").disabled = false;
        document.getElementById("nombre_proveedor").disabled = false;
        //document.getElementById('buscarProveedor').style.display='block';
        //document.getElementById('buscarCategoriaProgramatica').style.visibility='visible';
        document.getElementById('seleccionado').value = 'no';
    }
    //actualizarDatosBasicos();
}
/********************************************************************************************************************************************************
 ************************************************ DESELECCION LISTA DE SOLICITUDES DE COTIZACION ********************************************************
 **********************************************************************************************************************************************************/
function deseleccionarListaSolicitud(id_solicitud) {
    formulario = document.getElementById("formSolicitudesFinalizadas");
    for (var i = 0; i < formulario.elements.length; i++) {
        var elemento = formulario.elements[i];
        if (elemento.type == "checkbox" || elemento.type == "radio") {
            if (elemento.value == id_solicitud) {
                elemento.checked = 0;
            }
        }
    }
    var seleccionados = 0;
    formulario = document.getElementById("formSolicitudesFinalizadas");
    for (var i = 0; i < formulario.elements.length; i++) {
        var elemento = formulario.elements[i];
        if (elemento.type == "checkbox" || elemento.type == "radio") {
            if (elemento.checked) {
                seleccionados++;
            }
        }
    }
    if (seleccionados > 0) {
        document.getElementById('tipo_orden').disabled = true;
        document.getElementById("forma_pago").disabled = true;
        document.getElementById("nombre_categoria").disabled = true;
        document.getElementById("nombre_proveedor").disabled = true;
        document.getElementById('seleccionado').value = 'si';
    } else {
        document.getElementById('tipo_orden').disabled = false;
        document.getElementById("forma_pago").disabled = false;
        document.getElementById("nombre_categoria").disabled = false;
        document.getElementById("nombre_proveedor").disabled = false;
        document.getElementById('seleccionado').value = 'no';
    }
}
/********************************************************************************************************************************************************
 ********************************************************* AGREGAR MATERIALES ***************************************************************************
 **********************************************************************************************************************************************************/
function agregarPartidas(id_orden_compra, id_orden_pago, idretencion) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var anio = document.getElementById('anio').value;
    if (document.getElementById('check_anticipo').checked == false) {
        var anticipo = "no";
    } else {
        var anticipo = "si";
    }
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            //alert (ajax.responseText);
            if (ajax.responseText != "filasagotadas") {
                consultarOrdenPago(id_orden_pago);
                if (document.getElementById('seleccionado').value == 'si') {
                    setTimeout("montoRetenidoTotal(" + id_orden_pago + ")", 2000);
                }
                document.getElementById('sinAfectacionOculto').value = 0;
                document.getElementById("divCargando").style.display = "none";
            } else {
                alert("Ya llego al limite de Compromisos a Cancelar por Orden de Pago");
                consultarOrdenPago(id_orden_pago);
                document.getElementById("divCargando").style.display = "none";
            }
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&anticipo=" + anticipo + "&id_orden_compra=" + id_orden_compra + "&id_orden_pago=" + id_orden_pago + "&anio=" + anio + "&ejecutar=ingresarOrdenCreada");
}

function montoRetenidoTotal(id_orden_compra) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            resultado = ajax.responseText;
            partesString = resultado.split("-"); // 0 Tipo (Parcial / TOtal) -  1 Monto Retenido - 2 Monto Total
            if (ajax.responseText.indexOf("total") != -1) {
                document.getElementById('textoMontoRetenidoPrincipal').value = partesString[1];
                document.getElementById('textoTotalAPagarPrincipal').value = partesString[2];
                document.getElementById('textoMontoRetenidoPrincipalOculto').value = partesString[3];
                document.getElementById('textoTotalAPagarPrincipalOculto').value = partesString[4];
            }
            setTimeout("mostrarListaRetenciones(" + id_orden_compra + ")", 500);
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + document.getElementById('id_orden_pago').value + "&id_orden_compra=" + id_orden_compra + "&ejecutar=montoRetenidoTotal");
}

function mostrarListaRetenciones(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            document.getElementById('listaRetenciones').innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=mostrarListaRetenciones");
}
/*********************************************************************************************************************************************************
 ********************************************************* INGRESAR DATOS BASICOS *************************************************************************
 **********************************************************************************************************************************************************/
function ingresarDatosBasicos(tipo_orden, categoria_programatica, anio, idfuente_financiamiento, idtipo_presupuesto, idordinal, justificacion, observaciones, ordenado_por, cedula_ordenado, numero_documento, fecha_documento, numero_proyecto, numero_contrato, id_beneficiarios, textoTotalAPagarPrincipalOculto, monto_sinafectacion, exento, subtotal, impuesto, forma_pago, anio) {
    //alert(textoTotalAPagarPrincipalOculto);
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (document.getElementById('sinAfectacionOculto').value == "0" && document.getElementById('id_categoria_programatica').value == '' && document.getElementById('multi_categoria').value == "no") {
        mostrarMensajes("error", "Disculpe debe seleccionar una categoria programatica")
    } else if (document.getElementById('compromete').value == "no" && document.getElementById('causa').value == "no" && document.getElementById('paga').value == "no" && monto_sinafectacion == '0') {
        mostrarMensajes("error", "Disculpe debe ingresar el monto");
    } else if (id_beneficiarios == "") {
        mostrarMensajes("error", "Debe llenar todos los datos para poder pasar al siguiente paso de la orden");
    } else {
        if (document.getElementById('check_anticipo').checked == true) {
            var anticipo = "si";
        } else {
            var anticipo = "no";
        }
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                if (ajax.responseText != "fallo") {
                    document.getElementById('botonSiguiente').style.display = "none";
                    document.getElementById('celdaImprimir').style.display = 'block';
                    document.getElementById("id_orden_pago").value = ajax.responseText;
                    consultarOrdenPago(ajax.responseText);
                } else {
                    mostrarMensajes("error", "Los datos Basicos de la Orden no puedieron ingresarse con exito");
                }
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&anticipo=" + anticipo + "&tipo_orden=" + tipo_orden + "&categoria_programatica=" + categoria_programatica + "&justificacion=" + justificacion + "&anio=" + anio + "&idfuente_financiamiento=" + idfuente_financiamiento + "&idtipo_presupuesto=" + idtipo_presupuesto + "&idordinal=" + idordinal + "&observaciones=" + observaciones + "&ordenado_por=" + ordenado_por + "&cedula_ordenado=" + cedula_ordenado + "&numero_documento=" + numero_documento + "&fecha_documento=" + fecha_documento + "&numero_proyecto=" + numero_proyecto + "&numero_contrato=" + numero_contrato + "&id_beneficiarios=" + id_beneficiarios + "&monto_sinafectacion=" + monto_sinafectacion + "&exento=" + exento + "&subtotal=" + subtotal + "&impuesto=" + impuesto + "&forma_pago=" + forma_pago + "&anio=" + anio + "&textoTotalAPagarPrincipalOculto=" + textoTotalAPagarPrincipalOculto + "&ejecutar=agregarDatosBasicos");
    }
}
/*********************************************************************************************************************************************************
 ************************************************** LISTA DE SOLICITUDES DE COTIZACION SELECCIONADAS ******************************************************
 **********************************************************************************************************************************************************/
function listarSolicitudesSeleccionadas(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var id_orden_pago = document.getElementById('id_orden_pago').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        //alert(ajax.responseText);
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            document.getElementById("solicitudesSeleccionada").innerHTML = ajax.responseText;
            if (ajax.responseText != '<center>No hay Compromisos Seleccionados</center>') {
                //alert('si hay seleccionados '+ajax.responseText);
                document.getElementById("seleccionado").value == 'si';
            }
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=listarSolicitudesSeleccionadas");
}
/*********************************************************************************************************************************************************
 **************************************************** ACTUALIZAR EL PRECIO Y LA CANTIDAD DE LAS PARTIDAS **************************************************
 **********************************************************************************************************************************************************/
function actualizarMonto(id_orden_pago, monto, id_partida) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (monto != "") {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block"
            }
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                if (ajax.responseText.indexOf("exito") != -1) {
                    consultarOrdenPago(id_orden_pago);
                } else {
                    mostrarMensajes("error", ajax.responseText);
                }
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_partida=" + id_partida + "&id_orden_pago=" + id_orden_pago + "&monto=" + monto + "&ejecutar=actualizarMonto");
    } else {
        mostrarMensajes("error", "Para Actualizar el Monto de la Partida, Primero debe ingresarlo");
    }
}
/*********************************************************************************************************************************************************
 ***************************************************** CONSULTA LAS PARTIDAS DE UNA ORDEN DE PAGO  ********************************************************
 **********************************************************************************************************************************************************/
function consultarPartidas(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var anio = document.getElementById('anio').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            document.getElementById("divPartidas").innerHTML = ajax.responseText;
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&anio=" + anio + "&ejecutar=partidas&accion=consultar");
}
/*********************************************************************************************************************************************************
 ******************************************************* ACTUALIZA LOS DATOS BASICOS DE UNA ORDEN DE PAGO *************************************************
 **********************************************************************************************************************************************************/
function actualizarDatosBasicos(accion, id_orden_pago) {
    //alert(id_orden_pago);
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    if (id_orden_pago) {
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                partes = ajax.responseText.split("|.|");
                //alert(partes[63]);
                document.getElementById("id_orden_pago").value = partes[62];
                if (partes[0] != '') {
                    document.getElementById("celdaNroOrden").innerHTML = '<STRONG>' + partes[0] + '</STRONG>';
                } else {
                    document.getElementById("celdaNroOrden").innerHTML = '<STRONG>Aun no generado</STRONG>';
                }
                if (partes[1] != '0000-00-00') {
                    document.getElementById("fecha_orden").value = partes[1];
                } else {
                    document.getElementById("fecha_orden").value = '';
                }
                document.getElementById("celdaFechaElaboracion").innerHTML = '<STRONG>' + partes[2] + '</STRONG>';
                document.getElementById("estado").value = partes[3];;
                if (partes[3] == 'anulado') {
                    document.getElementById("celdaEstado").innerHTML = partes[4] + partes[5];
                } else {
                    document.getElementById("celdaEstado").innerHTML = partes[4];
                }
                document.getElementById("tipo_orden").value = partes[6];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("tipo_orden").disabled = true;
                } else {
                    document.getElementById("tipo_orden").disabled = false;
                }
                document.getElementById("forma_pago").value = partes[45];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("forma_pago").disabled = true;
                } else {
                    document.getElementById("forma_pago").disabled = false;
                }
                if (partes[47] == 'si') {
                    document.getElementById('check_anticipo').checked = 1;
                } else {
                    document.getElementById('check_anticipo').checked = 0;
                }
                document.getElementById("id_categoria_programatica").value = partes[7];
                document.getElementById("nombre_categoria").value = partes[8]; //OJO AQUI VA partes[8]
                if (partes[3] != 'elaboracion') {
                    document.getElementById("nombre_categoria").disabled = true;
                } else {
                    document.getElementById("nombre_categoria").disabled = false;
                }
                if (partes[7] == 0) {
                    //document.getElementById("nombre_categoria").disabled 	= true;
                    document.getElementById("forma_pago").disabled = true;
                }
                document.getElementById("idfuente_financiamiento").value = partes[9];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("idfuente_financiamiento").disabled = true;
                } else {
                    document.getElementById("idfuente_financiamiento").disabled = false;
                }
                document.getElementById("cofinanciamiento").value = partes[10];
                document.getElementById("idtipo_presupuesto").value = partes[11];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("idtipo_presupuesto").disabled = true;
                } else {
                    document.getElementById("idtipo_presupuesto").disabled = false;
                }
                document.getElementById("anio").value = partes[12];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("anio").disabled = true;
                } else {
                    document.getElementById("anio").disabled = false;
                }
                document.getElementById("justificacion").value = partes[13];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("justificacion").disabled = true;
                } else {
                    document.getElementById("justificacion").disabled = false;
                }
                document.getElementById("observaciones").value = partes[14];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("observaciones").disabled = true;
                } else {
                    document.getElementById("observaciones").disabled = false;
                }
                document.getElementById("ordenado_por").value = partes[15];
                document.getElementById("cedula_ordenado").value = partes[16];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("ordenado_por").disabled = true;
                    document.getElementById("cedula_ordenado").disabled = true;
                } else {
                    document.getElementById("ordenado_por").disabled = false;
                    document.getElementById("cedula_ordenado").disabled = false;
                }
                document.getElementById("id_beneficiarios").value = partes[17];
                document.getElementById("nombre_proveedor").value = partes[18];
                document.getElementById("contribuyente_ordinario").value = partes[19];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("nombre_proveedor").disabled = true;
                } else {
                    document.getElementById("nombre_proveedor").disabled = false;
                }
                document.getElementById("compromete").value = partes[20];
                document.getElementById("causa").value = partes[21];
                document.getElementById("paga").value = partes[22];
                document.getElementById("multi_categoria").value = partes[63];
                if (partes[35] == 'si') {
                    document.getElementById('tablaTotalesNomina').style.display = 'block';
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'none';
                    document.getElementById('tablaPendientePorPagar').style.display = 'none';
                    document.getElementById('tablaPagoActual').style.display = 'none';
                    document.getElementById("asignaciones_mostrado").value = partes[26];
                    document.getElementById("deducciones_mostrado").value = partes[24];
                    document.getElementById("monto_sinafectacion_mostradoN").value = partes[30];
                    document.getElementById("subtotal").value = partes[25];
                    document.getElementById("exento").value = partes[23];
                    document.getElementById("monto_sinafectacionN").value = partes[29];
                } else {
                    //alert('forma '+partes[45]);
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'block';
                    document.getElementById('tablaTotalesNomina').style.display = 'none';
                    document.getElementById('tablaPendientePorPagar').style.display = 'none';
                    document.getElementById('tablaPagoActual').style.display = 'none';
                    document.getElementById('filaRetencion').style.display = 'block';
                    document.getElementById('filaAPagar').style.display = 'block';
                    document.getElementById("exento_mostrado").value = partes[24];
                    document.getElementById("subtotal_mostrado").value = partes[26];
                    document.getElementById("impuesto_mostrado").value = partes[28];
                    document.getElementById("monto_sinafectacion_mostrado").value = partes[30];
                    if (partes[45] != 'parcial' && partes[45] != 'valuacion') {
                        document.getElementById("textoMontoRetenidoPrincipal").value = partes[32];
                        document.getElementById("textoTotalAPagarPrincipal").value = partes[34];
                    } else {
                        document.getElementById('filaRetencion').style.display = 'none';
                        document.getElementById('filaAPagar').style.display = 'none';
                    }
                    document.getElementById("exento").value = partes[23];
                    document.getElementById("subtotal").value = partes[25];
                    document.getElementById("impuesto").value = partes[27];
                    document.getElementById("monto_sinafectacion").value = partes[29];
                    if (partes[45] != 'parcial' && partes[45] != 'valuacion') {
                        document.getElementById("textoMontoRetenidoPrincipalOculto").value = partes[31];
                        document.getElementById("textoTotalAPagarPrincipalOculto").value = partes[33];
                    }
                }
                if (partes[45] == 'parcial' || partes[45] == 'valuacion') {
                    document.getElementById('tablaPendientePorPagar').style.display = 'block';
                    document.getElementById("exento_pendiente_mostrado").value = partes[38];
                    document.getElementById("subtotal_pendiente_mostrado").value = partes[40];
                    document.getElementById("impuesto_pendiente_mostrado").value = partes[42];
                    document.getElementById("monto_pendiente_mostrado").value = partes[44];
                    document.getElementById("exento_pendiente").value = partes[37];
                    document.getElementById("subtotal_pendiente").value = partes[39];
                    document.getElementById("impuesto_pendiente").value = partes[27];
                    document.getElementById("monto_pendiente").value = partes[43];
                }
                //alert('es parcial '+partes[46]);
                if (partes[45] == 'parcial' || partes[45] == 'valuacion') {
                    document.getElementById('tablaPagoActual').style.display = 'block';
                    document.getElementById("exento_actual_mostrado").value = partes[51];
                    document.getElementById("subtotal_actual_mostrado").value = partes[53];
                    document.getElementById("impuesto_actual_mostrado").value = partes[55];
                    document.getElementById("monto_actual_mostrado").value = partes[57];
                    document.getElementById("textoMontoRetenidoFinal").value = partes[59];
                    document.getElementById("textoTotalAPagarFinal").value = partes[61];
                    document.getElementById("exento_actual").value = partes[50];
                    document.getElementById("subtotal_actual").value = partes[52];
                    document.getElementById("impuesto_actual").value = partes[54];
                    document.getElementById("monto_actual").value = partes[56];
                    document.getElementById("textoMontoRetenidoFinalOculto").value = partes[58];
                    document.getElementById("textoTotalAPagarFinalOculto").value = partes[60];
                    //}
                }
                document.getElementById("numero_proyecto").value = partes[48];
                document.getElementById("numero_contrato").value = partes[49];
                if (partes[33] > 0) {
                    document.getElementById("tipo_orden").disabled = true;
                    document.getElementById("forma_pago").disabled = true;
                    //document.getElementById("nombre_categoria").disabled 	= true;
                }
                if (document.getElementById("seleccionado").value == 'si') {
                    document.getElementById("nombre_proveedor").disabled = true;
                }
                esconderListaSolicitudes();
                //document.getElementById('textoTotalAPagarPrincipal').value = parseFloat(document.getElementById('monto_sinafectacion').value) - parseFloat(document.getElementById('textoMontoRetenidoPrincipalOculto').value);
                //formatoNumero('textoTotalAPagarPrincipal', 'textoTotalAPagarPrincipalOculto');
                consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, document.getElementById('id_orden_pago').value, document.getElementById('id_categoria_programatica').value, document.getElementById('forma_pago').value);
                //setTimeout("listarSolicitudesSeleccionadas("+id_orden_pago+")", 600);
                listarSolicitudesSeleccionadas(id_orden_pago);
                montoRetenidoTotal(id_orden_pago);
                document.getElementById("divCargando").style.display = "none";
                estado = document.getElementById('estado').value;
                actualizarBotones(id_orden_pago, estado);
                mostrarCuentasContables(id_orden_pago);
            }
        }
        ajax.send("id_orden_pago=" + id_orden_pago + "&anio=" + document.getElementById('anio').value + "&accion=" + accion + "&ejecutar=actualizarDatosBasicos");
    } else {
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                //alert('entro 2');
                partes = ajax.responseText.split("|.|");
                document.getElementById("id_orden_pago").value = partes[62]
                id_orden_pago = partes[62];
                if (partes[0] != '') {
                    document.getElementById("celdaNroOrden").innerHTML = '<STRONG>' + partes[0] + '</STRONG>';
                } else {
                    document.getElementById("celdaNroOrden").innerHTML = '<STRONG>Aun no generado</STRONG>';
                }
                if (partes[1] != '0000-00-00') {
                    document.getElementById("fecha_orden").value = partes[1];
                } else {
                    document.getElementById("fecha_orden").value = '';
                }
                document.getElementById("celdaFechaElaboracion").innerHTML = '<STRONG>' + partes[2] + '</STRONG>';
                document.getElementById("estado").value = partes[3];;
                if (partes[3] == 'anulado') {
                    document.getElementById("celdaEstado").innerHTML = partes[4] + partes[5];
                } else {
                    document.getElementById("celdaEstado").innerHTML = partes[4];
                }
                document.getElementById("tipo_orden").value = partes[6];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("tipo_orden").disabled = true;
                } else {
                    document.getElementById("tipo_orden").disabled = false;
                }
                document.getElementById("forma_pago").value = partes[45];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("forma_pago").disabled = true;
                } else {
                    document.getElementById("forma_pago").disabled = false;
                }
                if (partes[47] == 'si') {
                    document.getElementById('check_anticipo').checked = 1;
                } else {
                    document.getElementById('check_anticipo').checked = 0;
                }
                document.getElementById("id_categoria_programatica").value = partes[7];
                document.getElementById("nombre_categoria").value = partes[8]; //OJO AQUI VA partes[8]
                if (partes[3] != 'elaboracion') {
                    document.getElementById("nombre_categoria").disabled = true;
                } else {
                    document.getElementById("nombre_categoria").disabled = false;
                }
                if (partes[7] == 0) {
                    //document.getElementById("nombre_categoria").disabled 	= true;
                    document.getElementById("forma_pago").disabled = true;
                }
                document.getElementById("idfuente_financiamiento").value = partes[9];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("idfuente_financiamiento").disabled = true;
                } else {
                    document.getElementById("idfuente_financiamiento").disabled = false;
                }
                document.getElementById("cofinanciamiento").value = partes[10];
                document.getElementById("idtipo_presupuesto").value = partes[11];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("idtipo_presupuesto").disabled = true;
                } else {
                    document.getElementById("idtipo_presupuesto").disabled = false;
                }
                document.getElementById("anio").value = partes[12];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("anio").disabled = true;
                } else {
                    document.getElementById("anio").disabled = false;
                }
                document.getElementById("justificacion").value = partes[13];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("justificacion").disabled = true;
                } else {
                    document.getElementById("justificacion").disabled = false;
                }
                document.getElementById("observaciones").value = partes[14];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("observaciones").disabled = true;
                } else {
                    document.getElementById("observaciones").disabled = false;
                }
                document.getElementById("ordenado_por").value = partes[15];
                document.getElementById("cedula_ordenado").value = partes[16];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("ordenado_por").disabled = true;
                    document.getElementById("cedula_ordenado").disabled = true;
                } else {
                    document.getElementById("ordenado_por").disabled = false;
                    document.getElementById("cedula_ordenado").disabled = false;
                }
                document.getElementById("id_beneficiarios").value = partes[17];
                document.getElementById("nombre_proveedor").value = partes[18];
                document.getElementById("contribuyente_ordinario").value = partes[19];
                if (partes[3] != 'elaboracion') {
                    document.getElementById("nombre_proveedor").disabled = true;
                } else {
                    document.getElementById("nombre_proveedor").disabled = false;
                }
                document.getElementById("compromete").value = partes[20];
                document.getElementById("causa").value = partes[21];
                document.getElementById("paga").value = partes[22];
                document.getElementById("multi_categoria").value = partes[63];
                if (partes[35] == 'si') {
                    document.getElementById('tablaTotalesNomina').style.display = 'block';
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'none';
                    document.getElementById('tablaPendientePorPagar').style.display = 'none';
                    document.getElementById('tablaPagoActual').style.display = 'none';
                    document.getElementById("asignaciones_mostrado").value = partes[26];
                    document.getElementById("deducciones_mostrado").value = partes[24];
                    document.getElementById("monto_sinafectacion_mostradoN").value = partes[30];
                    document.getElementById("subtotal").value = partes[25];
                    document.getElementById("exento").value = partes[23];
                    document.getElementById("monto_sinafectacionN").value = partes[29];
                } else {
                    //alert('forma '+partes[45]);
                    document.getElementById('tablaMontoSinAfectacion').style.display = 'block';
                    document.getElementById('tablaTotalesNomina').style.display = 'none';
                    document.getElementById('tablaPendientePorPagar').style.display = 'none';
                    document.getElementById('tablaPagoActual').style.display = 'none';
                    document.getElementById('filaRetencion').style.display = 'block';
                    document.getElementById('filaAPagar').style.display = 'block';
                    document.getElementById("exento_mostrado").value = partes[24];
                    document.getElementById("subtotal_mostrado").value = partes[26];
                    document.getElementById("impuesto_mostrado").value = partes[28];
                    document.getElementById("monto_sinafectacion_mostrado").value = partes[30];
                    if (partes[45] != 'parcial' && partes[45] != 'valuacion') {
                        document.getElementById("textoMontoRetenidoPrincipal").value = partes[32];
                        document.getElementById("textoTotalAPagarPrincipal").value = partes[34];
                    } else {
                        document.getElementById('filaRetencion').style.display = 'none';
                        document.getElementById('filaAPagar').style.display = 'none';
                    }
                    document.getElementById("exento").value = partes[23];
                    document.getElementById("subtotal").value = partes[25];
                    document.getElementById("impuesto").value = partes[27];
                    document.getElementById("monto_sinafectacion").value = partes[29];
                    if (partes[45] != 'parcial' && partes[45] != 'valuacion') {
                        document.getElementById("textoMontoRetenidoPrincipalOculto").value = partes[31];
                        document.getElementById("textoTotalAPagarPrincipalOculto").value = partes[33];
                    }
                }
                if (partes[45] == 'parcial' || partes[45] == 'valuacion') {
                    document.getElementById('tablaPendientePorPagar').style.display = 'block';
                    document.getElementById("exento_pendiente_mostrado").value = partes[38];
                    document.getElementById("subtotal_pendiente_mostrado").value = partes[40];
                    document.getElementById("impuesto_pendiente_mostrado").value = partes[42];
                    document.getElementById("monto_pendiente_mostrado").value = partes[44];
                    document.getElementById("exento_pendiente").value = partes[37];
                    document.getElementById("subtotal_pendiente").value = partes[39];
                    document.getElementById("impuesto_pendiente").value = partes[27];
                    document.getElementById("monto_pendiente").value = partes[43];
                }
                //alert('es parcial '+partes[46]);
                if (partes[45] == 'parcial' || partes[45] == 'valuacion') {
                    document.getElementById('tablaPagoActual').style.display = 'block';
                    document.getElementById("exento_actual_mostrado").value = partes[51];
                    document.getElementById("subtotal_actual_mostrado").value = partes[53];
                    document.getElementById("impuesto_actual_mostrado").value = partes[55];
                    document.getElementById("monto_actual_mostrado").value = partes[57];
                    document.getElementById("textoMontoRetenidoFinal").value = partes[59];
                    document.getElementById("textoTotalAPagarFinal").value = partes[61];
                    document.getElementById("exento_actual").value = partes[50];
                    document.getElementById("subtotal_actual").value = partes[52];
                    document.getElementById("impuesto_actual").value = partes[54];
                    document.getElementById("monto_actual").value = partes[56];
                    document.getElementById("textoMontoRetenidoFinalOculto").value = partes[58];
                    document.getElementById("textoTotalAPagarFinalOculto").value = partes[60];
                    //}
                }
                document.getElementById("numero_proyecto").value = partes[48];
                document.getElementById("numero_contrato").value = partes[49];
                if (partes[33] > 0) {
                    document.getElementById("tipo_orden").disabled = true;
                    document.getElementById("forma_pago").disabled = true;
                    //document.getElementById("nombre_categoria").disabled 	= true;
                }
                if (document.getElementById("seleccionado").value == 'si') {
                    document.getElementById("nombre_proveedor").disabled = true;
                }
                esconderListaSolicitudes();
                //document.getElementById('textoTotalAPagarPrincipal').value = parseFloat(document.getElementById('monto_sinafectacion').value) - parseFloat(document.getElementById('textoMontoRetenidoPrincipalOculto').value);
                //formatoNumero('textoTotalAPagarPrincipal', 'textoTotalAPagarPrincipalOculto');
                consultarPedidosProveedores(document.getElementById('id_beneficiarios').value, document.getElementById('tipo_orden').value, document.getElementById('id_orden_pago').value, document.getElementById('id_categoria_programatica').value, document.getElementById('forma_pago').value);
                //setTimeout("listarSolicitudesSeleccionadas("+id_orden_pago+")", 600);
                listarSolicitudesSeleccionadas(id_orden_pago);
                montoRetenidoTotal(id_orden_pago);
                estado = document.getElementById('estado').value;
                actualizarBotones(id_orden_pago, estado);
                mostrarCuentasContables(id_orden_pago);
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&tipo_orden=" + document.getElementById('tipo_orden').value + "&id_orden_pago=" + document.getElementById('id_orden_pago').value + "&id_categoria_programatica=" + document.getElementById('id_categoria_programatica').value + "&justificacion=" + document.getElementById('justificacion').value + "&anio=" + document.getElementById('anio').value + "&idfuente_financiamiento=" + document.getElementById('idfuente_financiamiento').value + "&idtipo_presupuesto=" + document.getElementById('idtipo_presupuesto').value + "&idordinal=" + document.getElementById('id_ordinal').value + "&observaciones=" + document.getElementById('observaciones').value + "&ordenado_por=" + document.getElementById('ordenado_por').value + "&cedula_ordenado=" + document.getElementById('cedula_ordenado').value + "&fecha_documento=" + document.getElementById('fecha_documento').value + "&numero_documento=" + document.getElementById('numero_documento').value + "&numero_proyecto=" + document.getElementById('numero_proyecto').value + "&numero_contrato=" + document.getElementById('numero_contrato').value + "&id_beneficiarios=" + document.getElementById('id_beneficiarios').value + "&accion=" + accion + "&monto_sinafectacion=" + document.getElementById('monto_sinafectacion').value + "&forma_pago=" + document.getElementById('forma_pago').value + "&exento=" + document.getElementById('exento').value + "&sub_total=" + document.getElementById('subtotal').value + "&impuesto=" + document.getElementById('impuesto').value + "&total=" + document.getElementById('monto_sinafectacion').value + "&exento_actual=" + document.getElementById('exento_actual').value + "&sub_total_actual=" + document.getElementById('subtotal_actual').value + "&impuesto_actual=" + document.getElementById('impuesto_actual').value + "&total_actual=" + document.getElementById('monto_actual').value + "&ejecutar=actualizarDatosBasicos");
    }
}
/*********************************************************************************************************************************************************
 ************************************** FUNCION PARA MOSTRAR LAS CUENTAS CONTABLES APLICADAS AL COMPROMISO  ***********************************************
 **********************************************************************************************************************************************************/
function mostrarCuentasContables(id_orden_pago) {
    var ajax = nuevoAjax();
    //alert(id_orden_pago);
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            document.getElementById('tablaContabilidad').style.display = 'block';
            document.getElementById('divContable').innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("id_orden_pago=" + id_orden_pago + "&ejecutar=mostrarCuentasContables");
}
/*********************************************************************************************************************************************************
 ******************************************************** ELIMINAR PARTIDAS DE UNA ORDEN DE PAGO **********************************************************
 **********************************************************************************************************************************************************/
function eliminarPartidas(id_orden_pago, id_partida) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var anio = document.getElementById('anio').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            document.getElementById("divPartidas").innerHTML = ajax.responseText;
            consultarOrdenPago(id_orden_pago);
            if (document.getElementById("eliminoSolicitud").value != "") {
                deseleccionarListaSolicitud(document.getElementById("eliminoSolicitud").value);
            }
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&id_partida=" + id_partida + "&anio=" + anio + "&ejecutar=partidas&accion=eliminar");
}
/*********************************************************************************************************************************************************
 **************************************************** ACTUALIZAR LAS LISTAS DE TOTALES DE LOS MATERIALES **************************************************
 **********************************************************************************************************************************************************/
function actualizarListaDeTotales(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            document.getElementById('totales').innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=actualizarListaDeTotales");
}
/*********************************************************************************************************************************************************
 ******************************************** FUNCION PARA DARLE FORMATO CORRECTO AL CAMPO DE PRECIO UNITARIO *********************************************
 **********************************************************************************************************************************************************/
function asignarValor(precioOculto, precioMostrado) {
    document.getElementById(precioOculto).value = "";
    var valorMostrado = document.getElementById(precioMostrado).value;
    var tamanioMostrado = valorMostrado.length;
    for (var i = 0; i < tamanioMostrado; i++) {
        var ultimaLetra = valorMostrado.charAt(i);
        if (ultimaLetra == ",") {
            document.getElementById(precioOculto).value = document.getElementById(precioOculto).value + ".";
        } else {
            if (!isNaN(ultimaLetra)) {
                document.getElementById(precioOculto).value = document.getElementById(precioOculto).value + ultimaLetra;
            }
        }
    }
}
/*********************************************************************************************************************************************************
 ************************************** FUNCION PARA MOSTRAR LAS PARTIDAS ELACIONADAS A LOS MATERIALES SELECCIONADOS **************************************
 **********************************************************************************************************************************************************/
function mostrarPartidas(id_orden_pago, id_categoria_programatica) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block"
        }
        if (ajax.readyState == 4) {
            document.getElementById('divPartidas').innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&id_categoria_programatica=" + id_categoria_programatica + "&ejecutar=mostrarPartidas");
}
/*********************************************************************************************************************************************************
 *************************************** ACTUALIZA LOS BOTONES DE PROCESAR; DUPLICAR; ANULAR; EN ELABORACION **********************************************
 **********************************************************************************************************************************************************/
function actualizarBotones(id_orden_pago, estado) {
    if (estado == 'elaboracion') {
        document.getElementById('botonSiguiente').style.display = "none";
        document.getElementById('botonEnElaboracion').style.display = "block";
        document.getElementById('botonProcesar').style.display = "block";
        document.getElementById('botonDuplicar').style.display = "none";
        document.getElementById('botonAnular').style.display = "none";
        document.getElementById('celdaImprimir').style.display = 'block';
    }
    if (estado == 'procesado' || estado == 'devuelto' || estado == 'conformado') {
        document.getElementById('botonSiguiente').style.display = "none";
        document.getElementById('botonEnElaboracion').style.display = "none";
        document.getElementById('botonProcesar').style.display = "none";
        document.getElementById('botonDuplicar').style.display = "block";
        document.getElementById('botonAnular').style.display = "block";
        document.getElementById('celdaImprimir').style.display = 'block';
    }
    if (estado == 'anulado') {
        document.getElementById('botonSiguiente').style.display = "none";
        document.getElementById('botonEnElaboracion').style.display = "none";
        document.getElementById('botonProcesar').style.display = "none";
        document.getElementById('botonDuplicar').style.display = "block";
        document.getElementById('botonAnular').style.display = "none";
        document.getElementById('celdaImprimir').style.display = 'block';
    }
    if (estado == 'procesado' || estado == 'devuelto' || estado == 'conformado' || estado == 'pagada' || estado == 'anulado' || estado == 'parcial' || estado == 'ordenado') {
        document.getElementById('botonSiguiente').style.display = "none";
        document.getElementById('botonEnElaboracion').style.display = "none";
        document.getElementById('botonProcesar').style.display = "none";
        document.getElementById('botonDuplicar').style.display = "block";
        document.getElementById('celdaImprimir').style.display = 'block';
    }
}
/*********************************************************************************************************************************************************
 **************************************************************** PROCESA LA ORDEN DE COMPRA **************************************************************
 **********************************************************************************************************************************************************/
function procesarOrden(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (confirm("Seguro desea Procesar esta Orden de Pago")) {
        var ajax = nuevoAjax();
        if (document.getElementById("sinAfectacionOculto").value == "1" || document.getElementById("check_anticipo").checked == true) {
            ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 1) {
                    document.getElementById("divCargando").style.display = "block";
                }
                if (ajax.readyState == 4) {
                    var tamanio = ajax.responseText.lenght;
                    //alert(ajax.responseText);
                    if (ajax.responseText.indexOf("exito") != -1) {
                        consultarOrdenPago(id_orden_pago);
                        mostrarMensajes("exito", "Orden de Pago Procesada con Exito");
                    } else if (ajax.responseText.indexOf("sinPartidas") != -1) {
                        mostrarMensajes("error", "Disculpe debe seleccionar al menos una partida para procesar la orden");
                    } else if (ajax.responseText.indexOf("partidasSinDisponibilidad") != -1) {
                        mostrarMensajes("error", "Disculpe existen partidas sin disponibilidad");
                    } else if (ajax.responseText.indexOf("montosDiferentes") != -1) {
                        mostrarMensajes("error", "Disculpe el monto a pagar de la partida es distinto al indicado en los datos generales de la orden");
                    } else {
                        mostrarMensajes("error", "Disculpe la orden no se pudo procesar por el siguiente error: " + ajax.responseText + ", por favor comuniquese con el administrador del sistema");
                    }
                    document.getElementById("divCargando").style.display = "none";
                }
            }
            ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=procesarOrdenSinAfectacion");
        } else {
            if (document.getElementById('forma_pago').value == "parcial") {
                total_a_pagar = document.getElementById('textoTotalAPagarFinalOculto').value;
                total_retencion = document.getElementById('textoMontoRetenidoFinalOculto').value;
                ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
                ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 1) {
                        document.getElementById("divCargando").style.display = "block";
                    }
                    if (ajax.readyState == 4) {
                        //alert(ajax.responseText);
                        var tamanio = ajax.responseText.lenght;
                        if (ajax.responseText.indexOf("exito") != -1) {
                            consultarOrdenPago(id_orden_pago);
                            mostrarMensajes("exito", "Orden de Pago Procesada con Exito");
                        } else if (ajax.responseText.indexOf("sinPartidas") != -1) {
                            mostrarMensajes("error", "Disculpe debe seleccionar al menos una partida para procesar la orden");
                        } else if (ajax.responseText.indexOf("partidasSinDisponibilidad") != -1) {
                            mostrarMensajes("error", "Disculpe existen partidas sin disponibilidad");
                        } else if (ajax.responseText.indexOf("montosDiferentes") != -1) {
                            mostrarMensajes("error", "Disculpe el monto a pagar de la partida es distinto al indicado en los datos generales de la orden");
                        } else {
                            mostrarMensajes("error", "Disculpe la orden no se pudo procesar por el siguiente error: " + ajax.responseText + ", por favor comuniquese con el administrador del sistema");
                        }
                        document.getElementById("divCargando").style.display = "none";
                    }
                }
                ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&exento_a_pagar=" + document.getElementById('exento_actual').value + "&sub_total_a_pagar=" + document.getElementById('subtotal_actual').value + "&impuesto_a_pagar=" + document.getElementById('impuesto_actual').value + "&total_a_pagar=" + total_a_pagar + "&total_retencion=" + total_retencion + "&ejecutar=procesarOrden");
            } else {
                total = document.getElementById('monto_sinafectacion').value;
                total_a_pagar = document.getElementById('textoTotalAPagarPrincipalOculto').value;
                total_retencion = document.getElementById('textoMontoRetenidoPrincipalOculto').value
                ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
                ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 1) {
                        document.getElementById("divCargando").style.display = "block";
                    }
                    if (ajax.readyState == 4) {
                        var tamanio = ajax.responseText.lenght;
                        //alert(ajax.responseText);
                        if (ajax.responseText.indexOf("exito") != -1) {
                            consultarOrdenPago(id_orden_pago);
                            mostrarMensajes("exito", "Orden de Pago Procesada con Exito");
                        } else if (ajax.responseText.indexOf("sinPartidas") != -1) {
                            mostrarMensajes("error", "Disculpe debe seleccionar al menos una partida para procesar la orden");
                        } else if (ajax.responseText.indexOf("partidasSinDisponibilidad") != -1) {
                            mostrarMensajes("error", "Disculpe existen partidas sin disponibilidad");
                        } else if (ajax.responseText.indexOf("montosDiferentes") != -1) {
                            mostrarMensajes("error", "Disculpe el monto a pagar de la partida es distinto al indicado en los datos generales de la orden");
                        } else {
                            mostrarMensajes("error", "Disculpe la orden no se pudo procesar por el siguiente error: " + ajax.responseText + ", por favor comuniquese con el administrador del sistema");
                        }
                        document.getElementById("divCargando").style.display = "none";
                    }
                }
                ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&exento_a_pagar=" + document.getElementById('exento').value + "&sub_total_a_pagar=" + document.getElementById('subtotal').value + "&impuesto_a_pagar=" + document.getElementById('impuesto').value + "&total=" + total + "&total_a_pagar=" + total_a_pagar + "&total_retencion=" + total_retencion + "&ejecutar=procesarOrden");
            }
        }
    }
}
/*********************************************************************************************************************************************************
 **************************************************************** ANULAR LA ORDEN DE COMPRA **************************************************************
 **********************************************************************************************************************************************************/
function anularOrden(id_orden_pago, clave, fecha_anulacion_opago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (confirm("Realmente desea Anular la Orden de Pago?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                if (ajax.responseText.indexOf("exito") != -1) {
                    document.getElementById('divPreguntarUsuario').style.display = 'none';
                    consultarOrdenPago(id_orden_pago);
                } else if (ajax.responseText.indexOf("claveIncorrecta") != -1) {
                    mostrarMensajes("error", "Disculpe Clave incorrecta");
                    setTimeout("window.location.href='principal.php?accion=277&modulo=4'", 5000);
                } else {
                    mostrarMensajes("error", "No se pudo anular la orden");
                }
                document.getElementById("verificarClave").value = "";
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&clave=" + clave + "&fecha_anulacion_op=" + fecha_anulacion_opago + "&ejecutar=anularOrden");
    }
}
/*********************************************************************************************************************************************************
 **************************************************************** DUPLICAR LA ORDEN DE COMPRA **************************************************************
 **********************************************************************************************************************************************************/
function duplicarOrden(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (confirm("Realmente desea Duplicar la Orden?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                consultarOrdenPago(ajax.responseText);
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=duplicarOrden");
    }
}
/*********************************************************************************************************************************************************
 **************************************************************** CONSULTAR LA ORDEN DE PAGO **************************************************************
 **********************************************************************************************************************************************************/
function consultarOrdenPago(id_orden_pago) {
    //setTimeout("document.getElementById('tablaProveedor').style.display='block'", 200);
    //setTimeout("document.getElementById('tablaMaterialesPartidas').style.display='block'", 400);
    // ACTUALIZA EL ENCABEZADO
    //setTimeout("actualizarDatosBasicos('consultar', "+id_orden_pago+")", 600);
    //actualizarDatosBasicos('consultar', id_orden_pago);
    setTimeout("actualizarDatosBasicos('consultar', " + id_orden_pago + ")", 600);
    // SI EL TIPO DE DOCUMENTO DEPENDE DE UN DOCUMENTO QUE COMPROMETA MUESTRA LOS DOCUMENTOS
    /*if(document.getElementById('compromete').value == 'no' && document.getElementById('causa').value == 'si' && document.getElementById('paga').value == 'no'){	
    	alert("entro");
    	setTimeout("consultarPedidosProveedores("+document.getElementById('id_beneficiarios').value+","+document.getElementById('tipo_orden').value+","+id_orden_pago+","+ document.getElementById('id_categoria_programatica').value+", '"+document.getElementById('forma_pago').value+"')", 2600);
    	setTimeout("montoRetenidoTotal("+id_orden_pago+")",2000);
    	setTimeout("listarSolicitudesSeleccionadas("+id_orden_pago+")", 2400);
    }*/
    //listarSolicitudesSeleccionadas(id_orden_pago);
    // ACTUALIZA LOS BOTONES DE LA ORDEN DE PAGO SEGUN SU ESTADO
    //alert("AQUI");
    // MUESTRA U OCULTA LOS DIV DE LOS COMPROMISOS O LAS PARTIDAS
    setTimeout("ocultarMostrarSolicitudesPartidas()", 800);
    //ocultarMostrarSolicitudesPartidas();
    // ACTUALIZA LA CABEZERA DE LAS PARTIDAS Y EL TOTAL
    setTimeout("actualizarTotalesPartidas(" + id_orden_pago + ")", 800);
    // MUESTRA LAS PARTIDAS QUE TIENE LA ORDEN
    setTimeout("consultarPartidas(" + id_orden_pago + ")", 800);
    //setTimeout("validarSinAfectacion()", 2200);
    //setTimeout("esconderListaSolicitudes()", 2400);
    //setTimeout("actualizarDatosBasicos('consultar', "+id_orden_pago+")", 2300);
    //setTimeout("actualizarBotones("+id_orden_pago+")", 2300);
}

function ocultarMostrarSolicitudesPartidas() {
    //alert(document.getElementById('compromete').value);
    if (document.getElementById('compromete').value == 'no' && document.getElementById('causa').value == 'no' && document.getElementById('paga').value == 'no') {
        document.getElementById("tablaProveedor").style.display = "none";
        document.getElementById("listaSolicitudesProveedor").style.display = "none";
        document.getElementById("tablaMaterialesPartidas").style.display = "none";
    }
    if (document.getElementById('compromete').value == 'si' && document.getElementById('causa').value == 'si' && document.getElementById('paga').value == 'no') {
        document.getElementById("tablaProveedor").style.display = "none";
        document.getElementById("listaSolicitudesProveedor").style.display = "none";
        document.getElementById("tablaMaterialesPartidas").style.display = "block";
        if (document.getElementById('estado').value == 'elaboracion') {
            document.getElementById("formularioMateriales").style.display = "block";
        } else {
            document.getElementById("formularioMateriales").style.display = "none";
        }
    }
    if (document.getElementById('compromete').value == 'no' && document.getElementById('causa').value == 'si' && document.getElementById('paga').value == 'no') {
        document.getElementById("tablaProveedor").style.display = "block";
        document.getElementById("listaSolicitudesProveedor").style.display = "block";
        document.getElementById("tablaMaterialesPartidas").style.display = "block";
        document.getElementById("formularioMateriales").style.display = "none";
    }
}

function validarSinAfectacion() {
    if (document.getElementById('tipo_orden').value == 44) {
        document.getElementById('sinAfectacionOculto').value = 1;
    } else {
        document.getElementById('sinAfectacionOculto').value = 0;
    }
}

function esconderListaSolicitudes() {
    if (document.getElementById("solicitudes").value == "noTiene") {
        //document.getElementById("tablaProveedor").style.display = "none";
        //document.getElementById("listaSolicitudesProveedor").style.display = "none";
        if (document.getElementById('sinAfectacionOculto').value == '1') {
            if (document.getElementById('compromete').value == 'si' || document.getElementById('causa').value == 'si' || document.getElementById('paga').value == 'no') {
                document.getElementById('tablaMaterialesPartidas').style.display = 'block';
            } else {
                document.getElementById('tablaMaterialesPartidas').style.display = 'none';
            }
        } else {
            document.getElementById('tablaMaterialesPartidas').style.display = 'block';
        }
    } else {
        if (document.getElementById('compromete').value == 'no' || document.getElementById('causa').value == 'no' || document.getElementById('paga').value == 'no') {
            document.getElementById("tablaProveedor").style.display = "none";
            document.getElementById("listaSolicitudesProveedor").style.display = "none";
        }
    }
}

function actualizarTotalesPartidas(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            document.getElementById('totalPartidas').innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=actualizarTotalesPartidas");
}

function ingresarPartidaIndividual(id_orden_pago, id_partida, id_categoria_programatica, monto) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (id_partida == "") {
        mostrarMensajes("error", "No hay Partidas Seleccionados para ser ingresadas");
    } else if (monto == "") {
        mostrarMensajes("error", "Disculpe debe Ingresar un Monto");
        document.getElementById('monto').focus();
    } else {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                if (ajax.responseText.indexOf("exito") != -1) {
                    actualizarTotalesPartidas(id_orden_pago);
                    if (document.getElementById('forma_pago').value == 'total') {
                        document.getElementById('monto_sinafectacion_mostrado').value = parseFloat(document.getElementById('monto_sinafectacion').value) + parseFloat(monto);
                        formatoNumero('monto_sinafectacion_mostrado', 'monto_sinafectacion');
                        document.getElementById('textoTotalAPagarPrincipal').value = parseFloat(document.getElementById('textoTotalAPagarPrincipalOculto').value) + parseFloat(monto);
                        formatoNumero('textoTotalAPagarPrincipal', 'textoTotalAPagarPrincipalOculto');
                    } else {
                        document.getElementById('monto_sinafectacion_mostrado').value = parseFloat(document.getElementById('monto_sinafectacion').value) + parseFloat(monto);
                        formatoNumero('monto_sinafectacion_mostrado', 'monto_sinafectacion');
                        document.getElementById('monto_actual_mostrado').value = parseFloat(document.getElementById('monto_actual').value) + parseFloat(monto);
                        formatoNumero('monto_actual_mostrado', 'monto_actual');
                        document.getElementById('textoTotalAPagarFinal').value = parseFloat(document.getElementById('textoTotalAPagarFinalOculto').value) + parseFloat(monto);
                        formatoNumero('textoTotalAPagarFinal', 'textoTotalAPagarFinalOculto');
                    }
                    consultarPartidas(id_orden_pago);
                    consultarOrdenPago(id_orden_pago);
                } else if (ajax.responseText.indexOf("fallo") != -1) {
                    mostrarMensajes("error", "Disculpe no se pudo ingresar la Partida");
                } else if (ajax.responseText.indexOf("existe") != -1) {
                    mostrarMensajes("error", "Disculpe la Partida que intenta ingresar ya esta ingresada en la lista");
                }
                document.getElementById('partida').value = "";
                document.getElementById('id_partida').value = "";
                document.getElementById('denominacion_partida').value = "";
                document.getElementById('monto').value = "";
                document.getElementById('ordinal').value = "";
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&id_partida=" + id_partida + "&monto=" + monto + "&id_categoria_programatica=" + id_categoria_programatica + "&ejecutar=ingresarPartidaIndividual");
    }
    return false;
}

function actualizarValores(montoOculto, montoEscrito) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var montoOculto = document.getElementById(montoOculto).value;
    var montoEscrito = document.getElementById(montoEscrito).value;
    if (document.getElementById('forma_pago').value == 'total') {
        var montoParcial = document.getElementById('monto_sinafectacion').value - parseFloat(montoOculto);
        var montoFinal = parseFloat(montoParcial) + parseFloat(montoEscrito);
        document.getElementById('monto_sinafectacion_mostrado').value = montoFinal;
        formatoNumero('monto_sinafectacion_mostrado', 'monto_sinafectacion');
        document.getElementById('textoTotalAPagarPrincipal').value = montoFinal;
        formatoNumero('textoTotalAPagarPrincipal', 'textoTotalAPagarPrincipalOculto');
    } else {
        /*var montoParcial = document.getElementById('monto_actual').value-parseFloat(montoOculto);
        var montoFinal =  parseFloat(montoParcial)+parseFloat(montoEscrito);
		
        document.getElementById('monto_sinafectacion_mostrado').value = montoFinal;
        formatoNumero('monto_sinafectacion_mostrado', 'monto_sinafectacion');
		
        document.getElementById('monto_actual_mostrado').value = montoFinal;
        formatoNumero('monto_actual_mostrado', 'monto_actual');
		
        //textoMontoRetenidoFinalOculto
        //textoMontoRetenidoFinal
		
        //textoTotalAPagarFinalOculto
        //textoTotalAPagarFinal
		
        document.getElementById('textoTotalAPagarFinal').value = montoFinal;
        formatoNumero('textoTotalAPagarFinal', 'textoTotalAPagarFinalOculto');*/
    }
}

function actualizarFacturacionListaCompromisos(id_orden_compra, id_orden_pago, nro_factura, fecha_factura, nro_control) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            listarSolicitudesSeleccionadas(id_orden_pago);
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_compra=" + id_orden_compra + "&id_orden_pago=" + id_orden_pago + "&nro_factura=" + nro_factura + "&fecha_factura=" + fecha_factura + "&nro_control=" + nro_control + "&ejecutar=actualizarFacturacionListaCompromisos");
}

function agregarRetencionParcial(id_retencion, id_orden_pago, exento, subtotal, impuesto, total, total_retenido, total_a_pagar) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            listarSolicitudesSeleccionadas(id_orden_pago);
            consultarOrdenPago(id_orden_pago);
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_retencion=" + id_retencion + "&id_orden_pago=" + id_orden_pago + "&exento=" + exento + "&subtotal=" + subtotal + "&impuesto=" + impuesto + "&total=" + total + "&monto_retenido=" + total_retenido + "&monto_a_pagar=" + total_a_pagar + "&ejecutar=agregarRetencionParcial");
}

function limpiarDatos(id_orden_pago) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            consultarOrdenPago(id_orden_pago);
        }
        document.getElementById("divCargando").style.display = "none";
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&id_orden_pago=" + id_orden_pago + "&ejecutar=limpiarDatos");
}

function actualizarJustificacion(idorden_compra, idorden_pago, anio) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    if (document.getElementById('justificacion').value == "") {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                consultarOrdenPago(idorden_pago);
            }
            document.getElementById("divCargando").style.display = "none";
        }
        ajax.send("cofinanciamiento=" + cofinanciamiento + "&idorden_compra=" + idorden_compra + "&idorden_pago=" + idorden_pago + "&anio=" + anio + "&ejecutar=actualizarJustificacion");
    }
}

function actualizarDatosFactura(idretenciones, numero_factura, numero_control, fecha_factura) {
    var cofinanciamiento = document.getElementById('cofinanciamiento').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/administracion/lib/orden_pago_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            mostrarMensajes("exito", "Los datos de la factura se actualizaron con exito");
        }
        document.getElementById("divCargando").style.display = "none";
    }
    ajax.send("cofinanciamiento=" + cofinanciamiento + "&idretenciones=" + idretenciones + "&numero_control=" + numero_control + "&fecha_factura=" + fecha_factura + "&numero_factura=" + numero_factura + "&ejecutar=actualizarDatosFactura");
}