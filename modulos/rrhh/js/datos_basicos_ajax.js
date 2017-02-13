// JavaScript Document
//******************************************************************************************************************************
//******************************************************************************************************************************
//****************************************************** DATOS BASICOS ****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
function consultarFicha(nomenclatura_ficha) {
    if (nomenclatura_ficha == 0) {
        document.getElementById('nro_ficha').innerHTML = "Seleccione";
    } else {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                document.getElementById('nro_ficha').innerHTML = ajax.responseText;
            }
        }
        ajax.send("nomenclatura_ficha=" + nomenclatura_ficha + "&ejecutar=consultarFicha");
    }
}

function ingresarInformacionGeneral() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var idgrupo_sanguineo = document.getElementById("gruposanguineo_datosBasicos").value;
    if (document.getElementById("donante_datosBasicos").checked == true) {
        var flag_donante = 1;
    } else {
        var flag_donante = 0;
    }
    var peso = document.getElementById("peso_datosBasicos").value;
    var talla = document.getElementById("talla_datosBasicos").value;
    if (document.getElementById("vehiculo_datosBasicos").checked == true) {
        var flag_vehiculo = 1;
    } else {
        var flag_vehiculo = 0;
    }
    if (document.getElementById("licencia_datosBasicos").checked == true) {
        var flag_licencia = 1;
    } else {
        var flag_licencia = 0;
    }
    var nombre_emergencia = document.getElementById("nombre_emergencia_datosBasicos").value;
    var telefono_emergencia = document.getElementById("telefono_emergencia_datosBasicos").value;
    var direccion_emergencia = document.getElementById("direccion_emergencia_datosBasicos").value;
    var talla_camisa = document.getElementById("talla_camisa_datosBasicos").value;
    var talla_pantalon = document.getElementById("talla_pantalon_datosBasicos").value;
    var talla_zapatos = document.getElementById("talla_zapatos_datosBasicos").value;
    var otras_actividades = document.getElementById("otras_actividades_datosBasicos").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "Los Datos fueron registradoscon Exito");
            } else {
                mostrarMensajes("error", "Disculpe no se pude registrar los datos con exito, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("talla_pantalon=" + talla_pantalon + "&idtrabajador=" + idtrabajador + "&idgrupo_sanguineo=" + idgrupo_sanguineo + "&flag_donante=" + flag_donante + "&peso=" + peso + "&talla=" + talla + "&flag_vehiculo=" + flag_vehiculo + "&flag_licencia=" + flag_licencia + "&nombre_emergencia=" + nombre_emergencia + "&telefono_emergencia=" + telefono_emergencia + "&direccion_emergencia=" + direccion_emergencia + "&talla_camisa=" + talla_camisa + "&talla_zapatos=" + talla_zapatos + "&otras_actividades=" + otras_actividades + "&ejecutar=ingresarInformacionGeneral");
}

function ingresarInformacionivss() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var numero_registro_ivss = document.getElementById("numero_registro_ivss").value;
    var fecha_registro_ivss = document.getElementById("fecha_registro_ivss").value;
    var ocupacion_oficio_ivss = document.getElementById("ocupacion_oficio_ivss").value;
    var otro_ivss = document.getElementById("otro_ivss").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            alert(ajax.responseText);
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "Los Datos fueron registradoscon Exito");
            } else {
                mostrarMensajes("error", "Disculpe no se pude registrar los datos con exito, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("numero_registro_ivss=" + numero_registro_ivss + "&idtrabajador=" + idtrabajador + "&fecha_registro_ivss=" + fecha_registro_ivss + "&ocupacion_oficio_ivss=" + ocupacion_oficio_ivss + "&otro_ivss=" + otro_ivss + "&ejecutar=ingresarInformacionivss");
}

function ingresarTrabajador() {
    var cedula = document.getElementById("cedula_datosBasicos").value;
    var apellidos = document.getElementById("apellidos_datosBasicos").value;
    var nombres = document.getElementById("nombres_datosBasicos").value;
    var idnacionalidad = document.getElementById("nacionalidad_datosBasicos").value;
    var rif = document.getElementById("rif_datosBasicos").value;
    var nro_pasaporte = document.getElementById("pasaporte_datosBasicos").value;
    var sexo = document.getElementById("sexo_datosBasicos").value;
    var fecha_nacimiento = document.getElementById("fecha_nacimiento_datosBasicos").value;
    var lugar_nacimiento = document.getElementById("lugar_nacimiento_datosBasicos").value;
    var idedo_civil = document.getElementById("edo_civil_datosBasicos").value;
    var direccion = document.getElementById("direccion_datosBasicos").value;
    var telefono_habitacion = document.getElementById("telefono_habitacion_datosBasicos").value;
    var telefono_movil = document.getElementById("telefono_movil_datosBasicos").value;
    var correo_electronico = document.getElementById("correo_electronico_datosBasicos").value;
    var fecha_ingreso = document.getElementById("fecha_ingreso_datosEmpleo0").value;
    var fecha_continuidad = document.getElementById("fecha_inicio_continuidad0").value;
    var idcargo = document.getElementById("idcargo_datosEmpleo0").value;
    var idubicacion_funcional = document.getElementById("ubicacion_funcional_datosEmpleo0").value;
    var idcentro_costo = document.getElementById("centro_costo_datosEmpleo0").value;
    var nomenclatura_ficha = document.getElementById("nomenclatura_ficha_datosBasicos").value;
    if (cedula == "") {
        mostrarMensajes("error", "Disculpe debe ingresar la cedula del trabajador");
        document.getElementById("cedula").focus();
    } else if (nombres == "") {
        mostrarMensajes("error", "Disculpe debe ingresar el nombre del trabajador");
        document.getElementById("nombres").focus();
    } else if (apellidos == "") {
        mostrarMensajes("error", "Disculpe debe ingresar el apellido del trabajador");
        document.getElementById("apellidos").focus();
    } else if (nomenclatura_ficha == "0") {
        mostrarMensajes("error", "Disculpe debe seleccionar la nomenclatura de la Ficha del Trabajador");
        document.getElementById("nomenclatura_ficha").focus();
    } else if (fecha_ingreso == "") {
        mostrarMensajes("error", "Disculpe debe Indicar la FECHA DE INGRESO del Trabajador");
        document.getElementById("fecha_ingreso_datosEmpleo0").focus();
    } else {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                var partes = ajax.responseText.split("|.|");
                //alert(ajax.responseText);
                if (partes[1] == "exito") {
                    mostrarMensajes("exito", "Los Datos fueron registradoscon Exito");
                    document.getElementById('nro_ficha').innerHTML = "";
                    //document.trabajador.reset();
                    //document.getElementById("tipo_nomina").disabled=false;
                    consultarTrabajador(partes[0]);
                    document.getElementById('idtrabajador').value = partes[0];
                    document.getElementById("fechaingreso_datos_basicos").style.display = "none";
                } else if (ajax.responseText == "ficha_repetida") {
                    mostrarMensajes("error", "Disculpe el numero de ficha que ingreso ya esta siendo usada por otro trabajador, por favor verifique");
                } else if (ajax.responseText == "cedula_repetida") {
                    mostrarMensajes("error", "Disculpe la cedula de identidad que intenta ingresar ya esta siendo usada por otro trabajador, verifique si el trabajador que intenta ingresar ya existe en el sistema, de lo contrario revise los datos sumistrados para este trabajador");
                }
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("nombre_imagen=" + nombre_imagen + "&cedula=" + cedula + "&apellidos=" + apellidos + "&nombres=" + nombres + "&idnacionalidad=" + idnacionalidad + "&rif=" + rif + "&nro_pasaporte=" + nro_pasaporte + "&sexo=" + sexo + "&fecha_nacimiento=" + fecha_nacimiento + "&lugar_nacimiento=" + lugar_nacimiento + "&idedo_civil=" + idedo_civil + "&telefono_habitacion=" + telefono_habitacion + "&direccion=" + direccion + "&telefono_movil=" + telefono_movil + "&correo_electronico=" + correo_electronico + "&nomenclatura_ficha=" + nomenclatura_ficha + "&fecha_ingreso=" + fecha_ingreso + "&fecha_continuidad=" + fecha_continuidad + "&idcargo=" + idcargo + "&idubicacion_funcional=" + idubicacion_funcional + "&idcentro_costo=" + idcentro_costo + "&ejecutar=ingresarTrabajador");
    }
}

function modificarTrabajador() {
    var idtrabajador = document.getElementById("idtrabajador").value;
    var cedula = document.getElementById("cedula_datosBasicos").value;
    var apellidos = document.getElementById("apellidos_datosBasicos").value;
    var nombres = document.getElementById("nombres_datosBasicos").value;
    var idnacionalidad = document.getElementById("nacionalidad_datosBasicos").value;
    var rif = document.getElementById("rif_datosBasicos").value;
    var nro_pasaporte = document.getElementById("pasaporte_datosBasicos").value;
    var sexo = document.getElementById("sexo_datosBasicos").value;
    var fecha_nacimiento = document.getElementById("fecha_nacimiento_datosBasicos").value;
    var lugar_nacimiento = document.getElementById("lugar_nacimiento_datosBasicos").value;
    var idedo_civil = document.getElementById("edo_civil_datosBasicos").value;
    var direccion = document.getElementById("direccion_datosBasicos").value;
    var telefono_habitacion = document.getElementById("telefono_habitacion_datosBasicos").value;
    var telefono_movil = document.getElementById("telefono_movil_datosBasicos").value;
    var correo_electronico = document.getElementById("correo_electronico_datosBasicos").value;
    var nro_ficha = document.getElementById("nro_ficha").value;
    //alert("cedula "+cedula);
    if (cedula == "") {
        mostrarMensajes("error", "Disculpe debe ingresar la cedula del trabajador");
    } else if (nombres == "") {
        mostrarMensajes("error", "Disculpe debe ingresar el nombre del trabajador");
    } else if (apellidos == "") {
        mostrarMensajes("error", "Disculpe debe ingresar el apellido del trabajador");
    } else if (nro_ficha == "") {
        mostrarMensajes("error", "Disculpe debe ingresar el numero de ficha del trabajador");
    } else {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);
                if (ajax.responseText == "exito") {
                    mostrarMensajes("exito", "Los Datos fueron modificados Exito");
                    consultarTrabajador(idtrabajador);
                    //document.getElementById("tipo_nomina").disabled=false;
                } else {
                    mostrarMensajes("error", "Disculpe se produjo un error actualizando los Datos Basicos del trabajador, por favor verifique");
                }
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("idtrabajador=" + idtrabajador + "&cedula=" + cedula + "&apellidos=" + apellidos + "&nombres=" + nombres + "&idnacionalidad=" + idnacionalidad + "&rif=" + rif + "&nro_pasaporte=" + nro_pasaporte + "&sexo=" + sexo + "&fecha_nacimiento=" + fecha_nacimiento + "&lugar_nacimiento=" + lugar_nacimiento + "&idedo_civil=" + idedo_civil + "&telefono_habitacion=" + telefono_habitacion + "&direccion=" + direccion + "&telefono_movil=" + telefono_movil + "&correo_electronico=" + correo_electronico + "&nro_ficha=" + nro_ficha + "&ejecutar=modificarTrabajador");
    }
}

function eliminarTrabajador() {
    if (confirm("Realmente desea Eliminar este trabajador?")) {
        idtrabajador = document.getElementById("idtrabajador").value;
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                mostrarMensajes("exito", "El Trabajador se ha eliminado con exito");
                document.trabajador.reset();
                document.getElementById("divCargando").style.display = "none";
                document.getElementById("idcargo").disabled = false;
                document.getElementById("ubicacion_funcional").disabled = false;
                document.getElementById("centro_costo").disabled = false;
                document.getElementById("fecha_ingreso").disabled = false;
                //document.getElementById("tipo_nomina").disabled=false;
            }
        }
        ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=eliminarTrabajador");
    }
}

function consultarId() {
    var cedula = document.getElementById("cedula_buscar").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "no_existe") {
                mostrarMensajes("error", "Disculpe este numero de cedula no esta asignado a ningun trabajador");
            } else {
                consultarTrabajador(ajax.responseText);
            }
        }
    }
    ajax.send("cedula=" + cedula + "&ejecutar=consultarId");
}

function consultarTrabajador(idtrabajador) {
    //alert(idtrabajador);
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            document.getElementById("idtrabajador").value = idtrabajador;
            partes = ajax.responseText.split("|.|");
            //document.getElementById("idtrabajador").value = partes[0];
            //alert(partes[2]);
            document.getElementById("cedula_datosBasicos").value = partes[1];
            document.getElementById("apellidos_datosBasicos").value = partes[2];
            document.getElementById("nombres_datosBasicos").value = partes[3];
            document.getElementById("nacionalidad_datosBasicos").value = partes[4];
            document.getElementById("rif_datosBasicos").value = partes[5];
            document.getElementById("pasaporte_datosBasicos").value = partes[6];
            document.getElementById("sexo_datosBasicos").value = partes[7];
            document.getElementById("fecha_nacimiento_datosBasicos").value = partes[8];
            document.getElementById("lugar_nacimiento_datosBasicos").value = partes[9];
            document.getElementById("edo_civil_datosBasicos").value = partes[10];
            document.getElementById("gruposanguineo_datosBasicos").value = partes[11];
            document.getElementById("cedula_buscar").innerHTML = partes[1];
            document.getElementById("nombre_fijo").innerHTML = partes[3];
            document.getElementById("apellido_fijo").innerHTML = partes[2];
            if (partes[12] == 1) {
                document.getElementById("donante_datosBasicos").checked = true;
            } else {
                document.getElementById("donante_datosBasicos").checked = false;
            }
            document.getElementById("peso_datosBasicos").value = partes[13];
            document.getElementById("talla_datosBasicos").value = partes[14];
            document.getElementById("direccion_datosBasicos").value = partes[15];
            document.getElementById("telefono_habitacion_datosBasicos").value = partes[16];
            document.getElementById("telefono_movil_datosBasicos").value = partes[17];
            document.getElementById("correo_electronico_datosBasicos").value = partes[18];
            if (partes[19] == 1) {
                document.getElementById("vehiculo_datosBasicos").checked = true;
            } else {
                document.getElementById("vehiculo_datosBasicos").checked = false;
            }
            if (partes[20]) {
                document.getElementById("licencia_datosBasicos").checked = true
            } else {
                document.getElementById("licencia_datosBasicos").checked = false;
            }
            document.getElementById("nombre_emergencia_datosBasicos").value = partes[21];
            document.getElementById("telefono_emergencia_datosBasicos").value = partes[22];
            document.getElementById("direccion_emergencia_datosBasicos").value = partes[23];
            document.getElementById("talla_camisa_datosBasicos").value = partes[24];
            document.getElementById("talla_pantalon_datosBasicos").value = partes[25];
            document.getElementById("talla_zapatos_datosBasicos").value = partes[26];
            document.getElementById("otras_actividades_datosBasicos").value = partes[27];
            document.getElementById("idcargo_datosEmpleo").value = partes[28];
            document.getElementById("idcargo_datosEmpleo").disabled = true;
            document.getElementById("ubicacion_funcional_datosEmpleo").value = partes[29];
            document.getElementById("ubicacion_funcional_datosEmpleo").disabled = true;
            document.getElementById("centro_costo_datosEmpleo").value = partes[30];
            document.getElementById("centro_costo_datosEmpleo").disabled = true;
            document.getElementById("fecha_ingreso_datosEmpleo").value = partes[31];
            if (partes[44] == 'si') {
                document.getElementById("fecha_ingreso_datosEmpleo").disabled = false;
                document.getElementById("f_trigger_f").style.display = 'block';
            } else {
                document.getElementById("fecha_ingreso_datosEmpleo").disabled = true;
                document.getElementById("f_trigger_f").style.display = 'none';
            }
            document.getElementById('fecha_ingreso_prestaciones').value = partes[31];
            var fecha_ingreso = partes[31].split("-");
            document.getElementById('div_fecha_ingreso_prestaciones').innerHTML = fecha_ingreso[2] + "/" + fecha_ingreso[1] + "/" + fecha_ingreso[0];
            consultarSelectAniosPrestaciones(fecha_ingreso[0], fecha_ingreso[1]);
            //consultarSelectAniosVacaciones(fecha_ingreso[0]);
            //consultarSelectAniosAguinaldos(fecha_ingreso[0]);
            //consultarSelectAniosDeducciones(fecha_ingreso[0]);
            //document.getElementById("tipo_nomina").value= partes[32];
            document.getElementById("nro_ficha").innerHTML = partes[33];
            document.getElementById('nro_ficha_fijo').innerHTML = partes[33];
            document.getElementById("nomenclatura_ficha_datosBasicos").style.display = 'none';
            if (partes[34] == "a") {
                document.getElementById("estado_trabajador_datosEmpleo").innerHTML = 'Activo';
            } else {
                document.getElementById("estado_trabajador_datosEmpleo").innerHTML = 'Egresado';
                document.getElementById("justificacion_estado_datosEmpleo").innerHTML = partes[35];
            }
            if (partes[36] == "si") {
                document.getElementById('activo_nomina_datosEmpleo').checked = true;
            } else {
                document.getElementById('activo_nomina_datosEmpleo').checked = false;
            }
            if (partes[37] == "si") {
                document.getElementById('vacaciones_datosEmpleo').checked = true;
            } else {
                document.getElementById('vacaciones_datosEmpleo').checked = false;
            }
            document.getElementById("fecha_inicio_continuidad").value = partes[38];
            if (partes[39] != '') {
                document.getElementById('cuadroFoto').innerHTML = "<img src='modulos/rrhh/imagenes/" + partes[39] + "' width = '100' height='120'>";
            } else {
                document.getElementById('cuadroFoto').innerHTML = "Sin Imagen";
            }
            // IVSS
            document.getElementById("numero_registro_ivss").value = partes[40];
            document.getElementById("fecha_registro_ivss").value = partes[41];
            document.getElementById("ocupacion_oficio_ivss").value = partes[42];
            document.getElementById("otro_ivss").value = partes[43];
            document.getElementById("modificar_datosBasicos").style.display = 'block';
            //document.getElementById("eliminar").style.display='block';
            document.getElementById('cedula_datosBasicos').disabled = true;
            document.getElementById("ingresar_datosBasicos").style.display = 'none';
            //document.getElementById("idcargo").disabled=true;
            //document.getElementById("ubicacion_funcional").disabled=true;
            //document.getElementById("centro_costo").disabled=true;
            //document.getElementById("fecha_ingreso").disabled=true;
            //document.getElementById("tipo_nomina").disabled=true;
            document.getElementById("divCargando").style.display = "none";
            document.getElementById("fechaingreso_datos_basicos").style.display = "none";
            consultarCargaFamiliar(idtrabajador);
            consultarInstruccionAcademica(idtrabajador);
            consultar_registroFotografico(idtrabajador);
            consultarExperienciaLaboral(idtrabajador);
            buscarMovimientosTrabajador(idtrabajador);
            consultarHistoricoPermisos(idtrabajador);
            consultarReconocimientos(idtrabajador);
            consultarsanciones(idtrabajador);
            consultardeclaracion_jurada(idtrabajador);
            consultarCursos(idtrabajador);
            llenargrilla_vacaciones(idtrabajador);
            consultarAsociados_cuentas_bancarias(idtrabajador);
            consultarVacacionesPendientes(idtrabajador);
            listarPeriodosCesantes();
            mostrarPestanas();
            consultarPrestaciones();
            consultarVacaciones();
            consultarAguinaldos();
            consultarDeducciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarTrabajador");
}

function mostrarPestanas() {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            document.getElementById('tabsF').innerHTML = ajax.responseText;
        }
    }
    ajax.send("ejecutar=mostrarPestanas");
    /*	if(typeof document.getElementById("li_registroFotografico")!=="undefined"){
    		document.getElementById('li_registroFotografico').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_cargaFamiliar")!=="undefined"){
    		document.getElementById('li_cargaFamiliar').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_estudiosRealizados")!=="undefined"){
    		document.getElementById('li_estudiosRealizados').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_experienciaLaboral")!=="undefined"){
    		document.getElementById('li_experienciaLaboral').style.display 	= 'block';
    	}
    	
    	if(typeof document.getElementById("li_movimientos")!=="undefined"){
    		document.getElementById('li_movimientos').style.display 	= 'block';
    	}
    	
    	if(typeof document.getElementById("li_permisos")!=="undefined"){
    		document.getElementById('li_permisos').style.display 	= 'block';
    	}
    	
    	if(typeof document.getElementById("li_permisos")!=="undefined"){
    		document.getElementById('li_permisos').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_vacaciones")!=="undefined"){
    		document.getElementById('li_vacaciones').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_utilidades")!=="undefined"){
    		document.getElementById('li_utilidades').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_datosEmpleo")!=="undefined"){
    		document.getElementById('li_datosEmpleo').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_prestacionesSociales")!=="undefined"){
    		document.getElementById('li_prestacionesSociales').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_cursosRealizados")!=="undefined"){
    		document.getElementById('li_cursosRealizados').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_declaracionJurada")!=="undefined"){
    		document.getElementById('li_declaracionJurada').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_sanciones")!=="undefined"){
    		document.getElementById('li_sanciones').style.display 	= 'block';
    	}
    	if(typeof document.getElementById("li_reconocimientos")!=="undefined"){
    		document.getElementById('li_reconocimientos').style.display 	= 'block';
    	}
    	
    	if(typeof document.getElementById("li_cuentas_bancarias")!=="undefined"){
    		document.getElementById('li_cuentas_bancarias').style.display 	= 'block';
    	}
    	
    	if(typeof document.getElementById("li_informacionGeneral")!=="undefined"){
    		document.getElementById('li_informacionGeneral').style.display 	= 'block';
    	}*/
}
//******************************************************************************************************************************
//******************************************************************************************************************************
// ****************************************************** REGISTRO FOTOGRAFICO ********************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
function subirRegistroFotografico() {
    var nombre_imagen = document.getElementById("nombre_imagen").value;
    var idtrabajador = document.getElementById("idtrabajador").value;
    var descripcion = document.getElementById('descripcion_foto').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('mostrarImagen').innerHTML = '';
            document.getElementById('foto_registroFotografico').value = '';
            document.getElementById('descripcion_foto').value = '';
            consultar_registroFotografico(idtrabajador);
        }
    }
    ajax.send("descripcion=" + descripcion + "&idtrabajador=" + idtrabajador + "&nombre_imagen=" + nombre_imagen + "&ejecutar=subirRegistroFotografico");
}

function consultar_registroFotografico(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_registroFotografico').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultar_registroFotografico");
}

function eliminar_registroFotografico(idregistro_fotografico) {
    if (confirm("Seguro desea Eliminar esta imagen?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultar_registroFotografico(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idregistro_fotografico=" + idregistro_fotografico + "&ejecutar=eliminar_registroFotografico");
    }
}

function principal_registroFotografico(idregistro_fotografico_trabajador) {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarTrabajador(document.getElementById('idtrabajador').value);
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&idregistro_fotografico_trabajador=" + idregistro_fotografico_trabajador + "&ejecutar=principal_registroFotografico");
}
//******************************************************************************************************************************
//******************************************************************************************************************************
// ************************************************** DATOS DE EMPLEO ***************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
function modificarDatosEmpleo() {
    var idcargo = document.getElementById("idcargo_datosEmpleo").value;
    var ubicacion_funcional = document.getElementById("ubicacion_funcional_datosEmpleo").value;
    var centro_costo = document.getElementById("centro_costo_datosEmpleo").value;
    var fecha_ingreso = document.getElementById("fecha_ingreso_datosEmpleo").value;
    var vacaciones = document.getElementById('vacaciones_datosEmpleo').value;
    var activo_nomina = document.getElementById('activo_nomina_datosEmpleo').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var fecha_inicio_continuidad = document.getElementById('fecha_inicio_continuidad').value;
    if (document.getElementById('vacaciones_datosEmpleo').checked == true) {
        vacaciones = 'si';
    } else {
        vacaciones = 'no';
    }
    if (document.getElementById('activo_nomina_datosEmpleo').checked == true) {
        activo_nomina = 'si';
    } else {
        activo_nomina = 'no';
    }
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            consultarTrabajador(idtrabajador);
        }
    }
    ajax.send("fecha_inicio_continuidad=" + fecha_inicio_continuidad + "&idcargo=" + idcargo + "&ubicacion_funcional=" + ubicacion_funcional + "&centro_costo=" + centro_costo + "&fecha_ingreso=" + fecha_ingreso + "&vacaciones=" + vacaciones + "&activo_nomina=" + activo_nomina + "&idtrabajador=" + idtrabajador + "&ejecutar=modificarDatosEmpleo");
}
//******************************************************************************************************************************
//******************************************************************************************************************************
// ************************************************** CARGA FAMILIAR *****************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
function consultarCargaFamiliar(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_cargaFamiliar').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarCargaFamiliar");
}

function ingresarCargaFamiliar() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var apellido = document.getElementById('apellidos_cargaFamiliar').value;
    var nombre = document.getElementById('nombres_cargaFamiliar').value;
    var idnacionalidad = document.getElementById('idnacionalidad_cargaFamiliar').value;
    var cedula = document.getElementById('cedula_cargaFamiliar').value;
    var fecha_nacimiento = document.getElementById('fecha_nacimiento_cargaFamiliar').value;
    var sexo = document.getElementById('sexo_cargaFamiliar').value;
    var idparentezco = document.getElementById('idparentezco_cargaFamiliar').value;
    if (document.getElementById('constancia_cargaFamiliar').checked == true) {
        var constancia = "si";
    } else {
        var constancia = "no";
    }
    var direccion = document.getElementById('direccion_cargaFamiliar').value;
    var telefono = document.getElementById('telefono_cargaFamiliar').value;
    var ocupacion = document.getElementById('ocupacion_cargaFamiliar').value;
    var nombre_foto = document.getElementById('nombre_foto_cargaFamiliar').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarCargaFamiliar(idtrabajador);
            document.formulario_cargaFamiliar.reset();
            document.getElementById('foto_cargaFamiliar').value = '';
            document.getElementById('nombre_foto_cargaFamiliar').value = '';
            mostrarMensajes("exito", "Se Ingresaron los datos con exito");
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&apellido=" + apellido + "&nombre=" + nombre + "&idnacionalidad=" + idnacionalidad + "&cedula=" + cedula + "&fecha_nacimiento=" + fecha_nacimiento + "&sexo=" + sexo + "&idparentezco=" + idparentezco + "&constancia=" + constancia + "&direccion=" + direccion + "&telefono=" + telefono + "&ocupacion=" + ocupacion + "&nombre_foto=" + nombre_foto + "&ejecutar=ingresarCargaFamiliar");
}

function modificarCargaFamiliar() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var idcarga_familiar = document.getElementById('idcarga_familiar').value;
    var apellido = document.getElementById('apellidos_cargaFamiliar').value;
    var nombre = document.getElementById('nombres_cargaFamiliar').value;
    var idnacionalidad = document.getElementById('idnacionalidad_cargaFamiliar').value;
    var cedula = document.getElementById('cedula_cargaFamiliar').value;
    var fecha_nacimiento = document.getElementById('fecha_nacimiento_cargaFamiliar').value;
    var sexo = document.getElementById('sexo_cargaFamiliar').value;
    var idparentezco = document.getElementById('idparentezco_cargaFamiliar').value;
    if (document.getElementById('constancia_cargaFamiliar').checked == true) {
        var constancia = "si";
    } else {
        var constancia = "no";
    }
    var direccion = document.getElementById('direccion_cargaFamiliar').value;
    var telefono = document.getElementById('telefono_cargaFamiliar').value;
    var ocupacion = document.getElementById('ocupacion_cargaFamiliar').value;
    var nombre_foto = document.getElementById('nombre_foto_cargaFamiliar').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            consultarCargaFamiliar(idtrabajador);
            document.formulario_cargaFamiliar.reset();
            document.getElementById('foto_cargaFamiliar').value = '';
            document.getElementById('nombre_foto_cargaFamiliar').value = '';
            document.getElementById('boton_ingresar_cargaFamiliar').style.display = 'block';
            document.getElementById('boton_modificar_cargaFamiliar').style.display = 'none';
            //document.getElementById('lista_cargaFamiliar').innerHTML = ajax.responseText;
            mostrarMensajes("exito", "Se modifico los datos con exito");
        }
    }
    ajax.send("idcarga_familiar=" + idcarga_familiar + "&apellido=" + apellido + "&nombre=" + nombre + "&idnacionalidad=" + idnacionalidad + "&cedula=" + cedula + "&fecha_nacimiento=" + fecha_nacimiento + "&sexo=" + sexo + "&idparentezco=" + idparentezco + "&constancia=" + constancia + "&direccion=" + direccion + "&telefono=" + telefono + "&ocupacion=" + ocupacion + "&nombre_foto=" + nombre_foto + "&ejecutar=modificarCargaFamiliar");
}

function seleccionarCargaFamiliar(idcarga_familiar, apellido, nombre, idnacionalidad, cedula, fecha_nacimiento, sexo, idparentezco, constancia, direccion, telefono, ocupacion) {
    document.getElementById('idcarga_familiar').value = idcarga_familiar;
    document.getElementById('apellidos_cargaFamiliar').value = apellido;
    document.getElementById('nombres_cargaFamiliar').value = nombre;
    document.getElementById('idnacionalidad_cargaFamiliar').value = idnacionalidad;
    document.getElementById('cedula_cargaFamiliar').value = cedula;
    document.getElementById('fecha_nacimiento_cargaFamiliar').value = fecha_nacimiento;
    document.getElementById('sexo_cargaFamiliar').value = sexo;
    document.getElementById('idparentezco_cargaFamiliar').value = idparentezco;
    if (constancia == "s") {
        document.getElementById('constancia_cargaFamiliar').checked = true;
    } else {
        document.getElementById('constancia_cargaFamiliar').checked = false;
    }
    document.getElementById('direccion_cargaFamiliar').value = direccion;
    document.getElementById('telefono_cargaFamiliar').value = telefono;
    document.getElementById('ocupacion_cargaFamiliar').value = ocupacion;
    document.getElementById('boton_ingresar_cargaFamiliar').style.display = 'none';
    document.getElementById('boton_modificar_cargaFamiliar').style.display = 'block';
}

function eliminarCargaFamiliar(idcarga_familiar) {
    if (confirm("¿Seguro desea eliminar la carga familiar seleccionada?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarCargaFamiliar(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idcarga_familiar=" + idcarga_familiar + "&ejecutar=eliminarCargaFamiliar");
    }
}
//******************************************************************************************************************************
//******************************************************************************************************************************
// ********************************************************** INSTRUCCION ACADEMICA ********************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
function ingresarInstruccionAcademica() {
    var idnivel_estudios = document.getElementById('idnivel_estudio').value;
    var idprofesion = document.getElementById('idprofesion').value;
    var idmension = document.getElementById('idmension').value;
    var institucion = document.getElementById('institucion').value;
    var anio_egreso = document.getElementById('anio_egreso').value;
    var nombre_foto = document.getElementById('nombre_foto_instruccionAcademica').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var observaciones = document.getElementById('observaciones').value;
    if (document.getElementById('constancia').checked == true) {
        var constancia = "s";
    } else {
        var constancia = "n";
    }
    if (document.getElementById('profesion_actual').checked == true) {
        var profesion_actual = "s";
    } else {
        var profesion_actual = "n";
    }
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "Sus datos han sido registrados con exito");
            } else {
                mostrarMensajes("error", "Disculpe los datos no se han podido registrar (" + ajax.responseText + ")");
            }
            consultarInstruccionAcademica(idtrabajador);
            limpiar_campos_instruccionAcademica();
        }
    }
    ajax.send("idnivel_estudios=" + idnivel_estudios + "&idprofesion=" + idprofesion + "&idmension=" + idmension + "&institucion=" + institucion + "&anio_egreso=" + anio_egreso + "&constancia=" + constancia + "&profesion_actual=" + profesion_actual + "&idtrabajador=" + idtrabajador + "&nombre_foto=" + nombre_foto + "&observaciones=" + observaciones + "&ejecutar=ingresarInstruccionAcademica");
}

function modificarInstruccionAcademica() {
    var idinstruccion_academica = document.getElementById('idinstruccion_academica').value;
    var idnivel_estudios = document.getElementById('idnivel_estudio').value;
    var idprofesion = document.getElementById('idprofesion').value;
    var idmension = document.getElementById('idmension').value;
    var institucion = document.getElementById('institucion').value;
    var anio_egreso = document.getElementById('anio_egreso').value;
    var nombre_foto = document.getElementById('nombre_foto_instruccionAcademica').value;
    var observaciones = document.getElementById('observaciones').value;
    if (document.getElementById('constancia').checked == true) {
        var constancia = "si";
    } else {
        var constancia = "no";
    }
    if (document.getElementById('profesion_actual').checked == true) {
        var profesion_actual = "si";
    } else {
        var profesion_actual = "no";
    }
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "Sus datos han sido Modificados con exito");
            } else {
                mostrarMensajes("error", "Disculpe los datos no se han podido Modificar");
            }
            consultarInstruccionAcademica(document.getElementById('idtrabajador').value);
            limpiar_campos_instruccionAcademica();
        }
    }
    ajax.send("observaciones=" + observaciones + "&idnivel_estudios=" + idnivel_estudios + "&idprofesion=" + idprofesion + "&idmension=" + idmension + "&institucion=" + institucion + "&anio_egreso=" + anio_egreso + "&constancia=" + constancia + "&profesion_actual=" + profesion_actual + "&idinstruccion_academica=" + idinstruccion_academica + "&nombre_foto=" + nombre_foto + "&ejecutar=modificarInstruccionAcademica");
}

function eliminarInstruccionAcademica(idinstruccion_academica) {
    if (confirm("Seguro desea eliminar la Instruccion Academica seleccionada?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                if (ajax.responseText == "exito") {
                    mostrarMensajes("exito", "Se elimino el registro con exito");
                } else {
                    mostrarMensajes("error", "Disculpe el Registro no se pudo eliminar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
                }
                consultarInstruccionAcademica(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idinstruccion_academica=" + idinstruccion_academica + "&ejecutar=eliminarInstruccionAcademica");
    }
}

function consultarInstruccionAcademica(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_instruccionAcademica').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarInstruccionAcademica");
}

function limpiar_campos_instruccionAcademica() {
    var idnivel_estudios = document.getElementById('idnivel_estudio').value = 0;
    var idprofesion = document.getElementById('idprofesion').value = 0;
    var idmension = document.getElementById('idmension').value = 0;
    var institucion = document.getElementById('institucion').value = "";
    var anio_egreso = document.getElementById('anio_egreso').value = "";
    var nombre_foto = document.getElementById('nombre_foto_instruccionAcademica').value = "";
    var observaciones = document.getElementById('observaciones').value = "";
    document.getElementById('constancia').checked = false;
    document.getElementById('profesion_actual').checked = false;
    document.getElementById('foto_instruccionAcademica').value = '';
    document.getElementById('boton_ingresar_instruccionAcademica').style.display = "block";
    document.getElementById('boton_modificar_instruccionAcademica').style.display = "none";
}

function seleccionar_instruccionAcademica(idnivel_estudios, idprofesion, idmension, institucion, anio_egreso, observaciones, constancia, profesion_actual, idinstruccion_academica) {
    document.getElementById('idnivel_estudio').value = idnivel_estudios;
    document.getElementById('idprofesion').value = idprofesion;
    document.getElementById('idmension').value = idmension;
    document.getElementById('institucion').value = institucion;
    document.getElementById('anio_egreso').value = anio_egreso;
    document.getElementById('observaciones').value = observaciones;
    if (constancia == "s") {
        document.getElementById('constancia').checked = true;
    } else {
        document.getElementById('constancia').checked = false;
    }
    if (profesion_actual == "s") {
        document.getElementById('profesion_actual').checked = true;
    } else {
        document.getElementById('profesion_actual').checked = false;
    }
    document.getElementById('boton_ingresar_instruccionAcademica').style.display = "none";
    document.getElementById('boton_modificar_instruccionAcademica').style.display = "block";
    document.getElementById('idinstruccion_academica').value = idinstruccion_academica;
}
//******************************************************************************************************************************
//******************************************************************************************************************************
//*************************************************** EXPERIENCIA LABORAL ******************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
function ingresarExperienciaLaboral() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var empresa = document.getElementById('empresa').value;
    var desde_exp = document.getElementById('desde_exp').value;
    var hasta_exp = document.getElementById('hasta_exp').value;
    var tiempo_srv = document.getElementById('tiempo_srv').value;
    var motivo = document.getElementById('motivo').value;
    var ultimo_cargo = document.getElementById('ultimo_cargo').value;
    var direccion_empresa = document.getElementById('direccion_empresa').value;
    var telefono = document.getElementById('telefono').value;
    var observaciones = document.getElementById('observaciones').value;
    var nombre_foto = document.getElementById('nombre_foto_experienciaLaboral').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El registro se ah ingresado con exito");
            } else {
                mostrarMensajes("error", "Disculpe el Registro no pudo ser ingresado, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            consultarExperienciaLaboral(idtrabajador);
            limpiarExperienciaLaboral();
        }
    }
    ajax.send("nombre_foto=" + nombre_foto + "&idtrabajador=" + idtrabajador + "&empresa=" + empresa + "&desde_exp=" + desde_exp + "&hasta_exp=" + hasta_exp + "&tiempo_srv=" + tiempo_srv + "&motivo=" + motivo + "&ultimo_cargo=" + ultimo_cargo + "&direccion_empresa=" + direccion_empresa + "&telefono=" + telefono + "&observaciones=" + observaciones + "&ejecutar=ingresarExperienciaLaboral");
}

function modificarExperienciaLaboral() {
    var idexperiencia_laboral = document.getElementById('idexperiencia_laboral').value;
    var empresa = document.getElementById('empresa').value;
    var desde_exp = document.getElementById('desde_exp').value;
    var hasta_exp = document.getElementById('hasta_exp').value;
    var tiempo_srv = document.getElementById('tiempo_srv').value;
    var motivo = document.getElementById('motivo').value;
    var ultimo_cargo = document.getElementById('ultimo_cargo').value;
    var direccion_empresa = document.getElementById('direccion_empresa').value;
    var telefono = document.getElementById('telefono').value;
    var observaciones = document.getElementById('observaciones').value;
    var nombre_foto = document.getElementById('nombre_foto_experienciaLaboral').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarExperienciaLaboral(document.getElementById("idtrabajador").value);
            limpiarExperienciaLaboral();
        }
    }
    ajax.send("nombre_foto=" + nombre_foto + "&idexperiencia_laboral=" + idexperiencia_laboral + "&empresa=" + empresa + "&desde_exp=" + desde_exp + "&hasta_exp=" + hasta_exp + "&tiempo_srv=" + tiempo_srv + "&motivo=" + motivo + "&ultimo_cargo=" + ultimo_cargo + "&direccion_empresa=" + direccion_empresa + "&telefono=" + telefono + "&observaciones=" + observaciones + "&ejecutar=modificarExperienciaLaboral");
}

function eliminarExperienciaLaboral(idexperiencia_laboral) {
    if (confirm("Seguro desea eliminar la experiencia laboral seleccionada?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarExperienciaLaboral(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idexperiencia_laboral=" + idexperiencia_laboral + "&ejecutar=eliminarExperienciaLaboral");
    }
}

function consultarExperienciaLaboral(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_experienciaLaboral').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarExperienciaLaboral");
}

function seleccionar_experienciaLaboral(idexperiencia_laboral, empresa, desde, hasta, tiempo_servicio, motivo_salida, ultimo_cargo, direccion_empresa, telefono_empresa, observaciones) {
    document.getElementById('idexperiencia_laboral').value = idexperiencia_laboral;
    document.getElementById('empresa').value = empresa;
    document.getElementById('desde_exp').value = desde;
    document.getElementById('hasta_exp').value = hasta;
    document.getElementById('tiempo_srv').value = tiempo_servicio;
    document.getElementById('motivo').value = motivo_salida;
    document.getElementById('ultimo_cargo').value = ultimo_cargo;
    document.getElementById('direccion_empresa').value = direccion_empresa;
    document.getElementById('telefono').value = telefono_empresa;
    document.getElementById('observaciones').value = observaciones;
    document.getElementById('boton_ingresar_experienciaLaboral').style.display = 'none';
    document.getElementById('boton_modificar_experienciaLaboral').style.display = 'block';
}

function limpiarExperienciaLaboral() {
    document.getElementById('idexperiencia_laboral').value = "";
    document.getElementById('empresa').value = "";
    document.getElementById('desde_exp').value = "";
    document.getElementById('hasta_exp').value = "";
    document.getElementById('tiempo_srv').value = "";
    document.getElementById('motivo').value = "";
    document.getElementById('ultimo_cargo').value = "";
    document.getElementById('direccion_empresa').value = "";
    document.getElementById('telefono').value = "";
    document.getElementById('observaciones').value = "";
    document.getElementById('boton_ingresar_experienciaLaboral').style.display = 'block';
    document.getElementById('boton_modificar_experienciaLaboral').style.display = 'none';
}
//***************************************************************************************************************************
//***************************************************************************************************************************
//***************************************************** MOVIMIENTOS ********************************************************
//***************************************************************************************************************************
//***************************************************************************************************************************
function buscarMovimientosTrabajador(idtrabajador) {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            document.getElementById("listaMovimientos").innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=buscarMovimientosTrabajador");
}

function consultarFichaMovimientos(nomenclatura_ficha) {
    var idtrabajador = document.getElementById('idtrabajador').value;
    if (nomenclatura_ficha == 0) {
        document.getElementById('nro_ficha_movimientos').innerHTML = "Seleccione";
    } else {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                document.getElementById('nro_ficha_movimientos').innerHTML = ajax.responseText;
                document.getElementById('campo_nro_ficha_movimientos').value = ajax.responseText;
            }
        }
        ajax.send("nomenclatura_ficha=" + nomenclatura_ficha + "&ejecutar=consultarFichaMovimientos");
    }
}

function ingresarMovimiento() {
    if (confirm("Seguro desea realizar este movimiento? recuerde que si es un movimiento de EGRESO el que intenta registrar, automaticamente esta persona saldra de todas las nominas donde este asociado")) {
        var idtrabajador = document.getElementById('idtrabajador').value;
        var fecha_movimiento = document.getElementById('fecha_movimiento_movimientos').value;
        var tipo_movimiento = document.getElementById('tipo_movimiento_movimientos').value;
        var justificacion = document.getElementById('justificacion_movimientos').value;
        var fecha_egreso = document.getElementById('fecha_egreso_movimientos').value;
        var causal = document.getElementById('causal_movimientos').value;
        var idnuevo_cargo = document.getElementById('nuevo_cargo_movimientos').value;
        var idubicacion_nueva = document.getElementById("nueva_ubicacion_funcional_movimientos").value;
        var fecha_reingreso = document.getElementById('fecha_reingreso_movimientos').value;
        var fecha_ingreso = document.getElementById('fecha_ingreso_datosEmpleo0').value;
        var desde = document.getElementById('desde_movimientos').value;
        var hasta = document.getElementById('hasta_movimientos').value;
        var centro_costo = document.getElementById('centro_costo_movimientos').value;
        if (document.getElementById('ficha_movimientos').value != "-") {
            var ficha = document.getElementById('ficha_movimientos').value + document.getElementById('campo_nro_ficha_movimientos').value;
        } else {
            var ficha = "";
        }
        var ajax = nuevoAjax();
        if (justificacion == "") {
            mostrarMensajes("error", "Disculpe debe ingresar la justificacion del movimiento");
        } else {
            ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 1) {
                    document.getElementById("divCargando").style.display = "block";
                }
                if (ajax.readyState == 4) {
                    //alert(ajax.responseText);
                    buscarMovimientosTrabajador(idtrabajador);
                    limpiarDatos();
                    consultarTrabajador(idtrabajador);
                    document.getElementById("divCargando").style.display = "none";
                }
            }
            ajax.send("ficha=" + ficha + "&fecha_ingreso=" + fecha_ingreso + "&centro_costo=" + centro_costo + "&idtrabajador=" + idtrabajador + "&fecha_movimiento=" + fecha_movimiento + "&tipo_movimiento=" + tipo_movimiento + "&justificacion=" + justificacion + "&fecha_egreso=" + fecha_egreso + "&causal=" + causal + "&idnuevo_cargo=" + idnuevo_cargo + "&idubicacion_nueva=" + idubicacion_nueva + "&fecha_reingreso=" + fecha_reingreso + "&desde=" + desde + "&hasta=" + hasta + "&ejecutar=ingresarMovimiento");
        }
    }
}

function limpiarDatos() {
    document.getElementById('fecha_movimiento_movimientos').value = '';
    document.getElementById('tipo_movimiento_movimientos').value = 0;
    document.getElementById('justificacion_movimientos').value = '';
    document.getElementById('fecha_egreso_movimientos').value = '';
    document.getElementById('causal_movimientos').value = '';
    document.getElementById('nuevo_cargo_movimientos').value = 0;
    document.getElementById("nueva_ubicacion_funcional_movimientos").value = 0;
    document.getElementById('fecha_reingreso_movimientos').value = '';
    document.getElementById('desde_movimientos').value = '';
    document.getElementById('hasta_movimientos').value = '';
    document.getElementById('centro_costo_movimientos').value = 0;
    document.getElementById("celda_ficha_movimientos").style.display = 'none';
    document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
    document.getElementById("campo_nro_ficha_movimientos").value = '';
    document.getElementById("nro_ficha_movimientos").innerHTML = '';
}

function seleccionarModificar(idmovimiento, fecha_movimiento, idtipo_movimiento, justificacion, fecha_egreso, causal, idubicacion_nueva, fecha_reingreso, desde, hasta, idnuevo_cargo, relacion_laboral, goce_sueldo, afecta_cargo, afecta_ubicacion, afecta_tiempo, centro_costo, afecta_centro_costo, afecta_ficha) {
    var idtrabajador = document.getElementById('idtrabajador').value;
    document.getElementById('idmovimiento_movimientos').value = idmovimiento;
    document.getElementById('fecha_movimiento_movimientos').value = fecha_movimiento;
    document.getElementById('tipo_movimiento_movimientos').value = idtipo_movimiento;
    document.getElementById('justificacion_movimientos').value = justificacion;
    document.getElementById('fecha_egreso_movimientos').value = fecha_egreso;
    document.getElementById('causal_movimientos').value = causal;
    document.getElementById('nuevo_cargo_movimientos').value = idnuevo_cargo;
    document.getElementById("nueva_ubicacion_funcional_movimientos").value = idubicacion_nueva;
    document.getElementById('fecha_reingreso_movimientos').value = fecha_reingreso;
    if (desde == "0000-00-00") {
        desde = "";
    }
    if (hasta == "0000-00-00") {
        hasta = "";
    }
    document.getElementById('desde_movimientos').value = desde;
    document.getElementById('hasta_movimientos').value = hasta;
    document.getElementById('centro_costo_movimientos').value = centro_costo;
    if (relacion_laboral == 'si') {
        document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'block';
        document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'block';
    } else {
        document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'none';
        document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'none';
    }
    if (afecta_cargo == 'si') {
        document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'block';
        document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'block';
    } else {
        document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'none';
        document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'none';
    }
    if (afecta_ubicacion == "si") {
        document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'block';
        document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'none';
        document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'none';
    }
    if (afecta_tiempo == "si") {
        document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'block';
        document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'none';
        document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'none';
    }
    if (afecta_centro_costo == "si") {
        document.getElementById("celda_centro_costo_movimientos").style.display = 'block';
        document.getElementById("campo_centro_costo_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_centro_costo_movimientos").style.display = 'none';
        document.getElementById("campo_centro_costo_movimientos").style.display = 'none';
    }
    if (afecta_ficha == "si") {
        document.getElementById("celda_ficha_movimientos").style.display = 'block';
        document.getElementById("celda_campo_ficha_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_ficha_movimientos").style.display = 'none';
        document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
    }
    document.getElementById('boton_ingresar_movimientos').style.display = 'none';
    document.getElementById('boton_editar_movimientos').style.display = 'block';
    document.getElementById('boton_eliminar_movimientos').style.display = 'none';
}

function seleccionarIngresar(relacion_laboral, goce_sueldo, afecta_cargo, afecta_ubicacion, afecta_tiempo, afecta_centro_costo, afecta_ficha) {
    if (relacion_laboral == 'si') {
        document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'block';
        document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'block';
    } else {
        document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'none';
        document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'none';
    }
    if (afecta_cargo == 'si') {
        document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'block';
        document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'block';
    } else {
        document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'none';
        document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'none';
    }
    if (afecta_ubicacion == "si") {
        document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'block';
        document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'none';
        document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'none';
    }
    if (afecta_tiempo == "si") {
        document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'block';
        document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'none';
        document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'none';
    }
    if (afecta_centro_costo == "si") {
        document.getElementById("celda_centro_costo_movimientos").style.display = 'block';
        document.getElementById("campo_centro_costo_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_centro_costo_movimientos").style.display = 'none';
        document.getElementById("campo_centro_costo_movimientos").style.display = 'none';
    }
    if (afecta_ficha == "si") {
        document.getElementById("celda_ficha_movimientos").style.display = 'block';
        document.getElementById("celda_campo_ficha_movimientos").style.display = 'block';
    } else {
        document.getElementById("celda_ficha_movimientos").style.display = 'none';
        document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
    }
    document.getElementById('boton_ingresar_movimientos').style.display = 'block';
    document.getElementById('boton_editar_movimientos').style.display = 'none';
    document.getElementById('boton_eliminar_movimientos').style.display = 'none';
}

function desaparecerCampos() {
    if (document.getElementById('idmovimiento_movimientos').value == '') {
        document.getElementById('celda_nombre_fecha_egreso_movimientos').style.display = 'none';
        document.getElementById('celda_campo_fecha_egreso_movimientos').style.display = 'none';
        document.getElementById('celda_nombre_nuevo_cargo_movimientos').style.display = 'none';
        document.getElementById('celda_campo_nuevo_cargo_movimientos').style.display = 'none';
        document.getElementById("celda_nombre_nueva_ubicacion_funcional_movimientos").style.display = 'none';
        document.getElementById("celda_campo_nueva_ubicacion_funcional_movimientos").style.display = 'none';
        document.getElementById("celda_nombre_fecha_reingreso_movimientos").style.display = 'none';
        document.getElementById("celda_campo_fecha_reingreso_movimientos").style.display = 'none';
        document.getElementById("celda_centro_costo_movimientos").style.display = 'none';
        document.getElementById("campo_centro_costo_movimientos").style.display = 'none';
        document.getElementById("celda_ficha_movimientos").style.display = 'none';
        document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
        document.getElementById("celda_ficha_movimientos").style.display = 'none';
        document.getElementById("celda_campo_ficha_movimientos").style.display = 'none';
        document.getElementById("campo_nro_ficha_movimientos").value = '';
        document.getElementById("nro_ficha_movimientos").innerHTML = '';
        document.getElementById('boton_ingresar_movimientos').style.display = 'block';
        document.getElementById('boton_editar_movimientos').style.display = 'none';
        document.getElementById('boton_eliminar_movimientos').style.display = 'none';
    }
}

function modificarMovimiento(idmovimiento) {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var idmovimiento = document.getElementById('idmovimiento_movimientos').value;
    var fecha_movimiento = document.getElementById('fecha_movimiento_movimientos').value;
    var tipo_movimiento = document.getElementById('tipo_movimiento_movimientos').value;
    var justificacion = document.getElementById('justificacion_movimientos').value;
    var fecha_egreso = document.getElementById('fecha_egreso_movimientos').value;
    var causal = document.getElementById('causal_movimientos').value;
    var idnuevo_cargo = document.getElementById('nuevo_cargo_movimientos').value;
    var idubicacion_nueva = document.getElementById("nueva_ubicacion_funcional_movimientos").value;
    var fecha_reingreso = document.getElementById('fecha_reingreso_movimientos').value;
    var fecha_ingreso = document.getElementById('fecha_ingreso_movimientos').value;
    var desde = document.getElementById('desde_movimientos').value;
    var hasta = document.getElementById('hasta_movimientos').value;
    var centro_costo = document.getElementById('centro_costo_movimientos').value;
    if (document.getElementById('ficha_movimientos').value != "-") {
        var ficha = document.getElementById('ficha_movimientos').value + document.getElementById('campo_nro_ficha_movimientos').value;
    } else {
        var ficha = "";
    }
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            buscarMovimientosTrabajador(document.getElementById("idtrabajador").value);
            limpiarDatos();
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("ficha=" + ficha + "&idtrabajador=" + idtrabajador + "&fecha_ingreso=" + fecha_ingreso + "&centro_costo=" + centro_costo + "&idmovimiento=" + idmovimiento + "&fecha_movimiento=" + fecha_movimiento + "&tipo_movimiento=" + tipo_movimiento + "&justificacion=" + justificacion + "&fecha_egreso=" + fecha_egreso + "&causal=" + causal + "&idnuevo_cargo=" + idnuevo_cargo + "&idubicacion_nueva=" + idubicacion_nueva + "&fecha_reingreso=" + fecha_reingreso + "&desde=" + desde + "&hasta=" + hasta + "&ejecutar=modificarMovimient");
}

function eliminarMovimiento(idmovimiento) {
    if (confirm("Seguro desea eliminar el movimiento seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/movimientos_personal_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                buscarMovimientosTrabajador(document.getElementById("idtrabajador").value);
                limpiarDatos();
                document.getElementById("divCargando").style.display = "none";
            }
        }
        ajax.send("idmovimiento=" + idmovimiento + "&ejecutar=eliminarMovimiento");
    }
}
// ************************************************************************************************************************
// ************************************************************************************************************************
// ****************************************************** HISTORICO DE PERMISOS *******************************************
// ************************************************************************************************************************
// ************************************************************************************************************************
function validarHoras(str_hora) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == 0) {
                document.getElementById('hr_inicio_historico').style.background = "#fec092";
                document.getElementById('hr_culminacion_historico').style.background = "#fec092";
            } else {
                document.getElementById('hr_inicio_historico').style.background = "#ffffff";
                document.getElementById('hr_culminacion_historico').style.background = "#ffffff";
            }
        }
    }
    ajax.send("hr_inicio=" + str_hora + "&ejecutar=validarHoras");
}

function checkbox(valor) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //document.getElementById('grilla').innerHTML = ajax.responseText;
        }
    }
    ajax.send("valor=" + valor + "&ejecutar=checkbox");
}

function buscarPermisos(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('grilla_historico').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=llenargrilla");
}

function validarFechas(desde, hasta) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('tiempo_historico').value = ajax.responseText;
            if (ajax.responseText == "La fecha de inicio ha de ser anterior a la fecha Actual") {
                document.getElementById('boton_ingresar_permiso').disabled = true;
                document.getElementById('boton_ingresar_permiso').value = "Seleccione Fechas Validas";
                document.getElementById('boton_ingresar_permiso').disabled = true;
                document.getElementById('boton_ingresar_permiso').value = "Seleccione Fechas Validas";
            } else {
                document.getElementById('boton_ingresar_permiso').disabled = false;
                document.getElementById('boton_ingresar_permiso').value = "Ingresar";
                document.getElementById('boton_ingresar_permiso').disabled = false;
                document.getElementById('boton_ingresar_permiso').value = "Ingresar";
            }
            totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value);
        }
    }
    ajax.send("hasta=" + hasta + "&desde=" + desde + "&ejecutar=validarFechas");
}

function totalHoras(total_dias, hr_inicio, hr_culminacion) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('tiempo_horas_historico').value = ajax.responseText;
            if (ajax.responseText == "Selecione horas validas") {
                document.getElementById('boton_ingresar_permiso').disabled = true;
                document.getElementById('boton_ingresar_permiso').value = "Selecione horas validas";
                document.getElementById('boton_ingresar_permiso').disabled = true;
                document.getElementById('boton_ingresar_permiso').value = "Selecione horas validas";
            } else {
                document.getElementById('boton_ingresar_permiso').disabled = false;
                document.getElementById('boton_ingresar_permiso').value = "Ingresar";
                document.getElementById('boton_ingresar_permiso').disabled = false;
                document.getElementById('boton_ingresar_permiso').value = "Ingresar";
            }
        }
    }
    ajax.send("hr_inicio=" + hr_inicio + "&hr_culminacion=" + hr_culminacion + "&total_dias=" + total_dias + "&ejecutar=total_Horas");
}

function ingresarHistorico() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var fecha_solicitud_historico = document.getElementById('fecha_solicitud_historico').value;
    var fecha_inicio = document.getElementById('fecha_inicio_historico').value;
    var hora_inicio = document.getElementById('hr_inicio_historico').value;
    var fecha_culminacion = document.getElementById('fecha_culminacion_historico').value;
    var hora_culminacion = document.getElementById('hr_culminacion_historico').value;
    var fecha_solicitud = document.getElementById('fecha_solicitud_historico').value;
    var tiempo_total = document.getElementById('tiempo_historico').value;
    if (document.getElementById('desc_bono_historico').checked == true) {
        var descuenta_bono_alimentacion = "si";
    } else {
        var descuenta_bono_alimentacion = "no";
    }
    if (document.getElementById('motivo_historico').checked == true) {
        var motivo = "si";
    } else {
        var motivo = "no";
    }
    if (document.getElementById('justificado1_historico').checked == true) {
        var justificado = "si";
    } else {
        var justificado = "no";
    }
    if (document.getElementById('remunerado1_historico').checked == true) {
        var remunerado = "si";
    } else {
        var remunerado = "no";
    }
    var aprobado_por = document.getElementById('aprobado_por_historico').value;
    var ci_aprobado = document.getElementById('ci_aprobado_historico').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El permiso ah sido registrado con exito");
            } else {
                mostrarMensajes("error", "Disculpe el permiso no se pudo registrar, por favor intente de nuevo mas tarde... (" + ajax.responseText + ")");
            }
            consultarHistoricoPermisos(idtrabajador);
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&fecha_solicitud_historico=" + fecha_solicitud_historico + "&fecha_inicio=" + fecha_inicio + "&hora_inicio=" + hora_inicio + "&fecha_culminacion=" + fecha_culminacion + "&hora_culminacion=" + hora_culminacion + "&fecha_solicitud=" + fecha_solicitud + "&tiempo_total=" + tiempo_total + "&descuenta_bono_alimentacion=" + descuenta_bono_alimentacion + "&motivo=" + motivo + "&justificado=" + justificado + "&remunerado=" + remunerado + "&aprobado_por=" + aprobado_por + "&ci_aprobado=" + ci_aprobado + "&ejecutar=ingresarHistorico");
}

function abreVentana() {
    miPopup = window.open("lib/listas/lista_trabajador.php?frm=historico_permisos", "historico_permisos", "width=600,height=400,scrollbars=yes")
    miPopup.focus()
}

function habilitaDeshabilita() {
    if (document.getElementById('desc_bono_historico').checked == true) {
        document.getElementById('motivo_historico').disabled = false;
    }
    if (document.getElementById('desc_bono_historico').checked == false) {
        document.getElementById('motivo_historico').disabled = true;
    }
}

function consultarHistoricoPermisos(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('grilla_historico').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarHistoricoPermisos");
}

function seleccionarModificar(idhistorico_permisos, fecha_inicio, hora_inicio, fecha_culminacion, hora_culminacion, fecha_solicitud, tiempo_total, descuenta_bono_alimentacion, motivo, justificado, remunerado, aprobado_por, ci_aprobado) {
    document.getElementById('idhistorico_permisos').value = idhistorico_permisos;
    document.getElementById('fecha_solicitud_historico').value = fecha_solicitud;
    document.getElementById('fecha_inicio_historico').value = fecha_inicio;
    document.getElementById('hr_inicio_historico').value = hora_inicio;
    document.getElementById('fecha_culminacion_historico').value = fecha_culminacion;
    document.getElementById('hr_culminacion_historico').value = hora_culminacion;
    document.getElementById('tiempo_historico').value = tiempo_total;
    if (descuenta_bono_alimentacion = "si") {
        document.getElementById('desc_bono_historico').checked = true;
    } else {
        document.getElementById('desc_bono_historico').checked = false;
    }
    if (motivo = "si") {
        document.getElementById('motivo_historico').checked = true;
    } else {
        document.getElementById('motivo_historico').checked = false;
    }
    if (justificado = "si") {
        document.getElementById('justificado1_historico').checked = true;
    } else {
        document.getElementById('justificado1_historico').checked = false;
    }
    if (remunerado = "si") {
        document.getElementById('remunerado1_historico').checked = true;
    } else {
        document.getElementById('remunerado1_historico').checked = false;
    }
    document.getElementById('aprobado_por_historico').value = aprobado_por;
    document.getElementById('ci_aprobado_historico').value = ci_aprobado;
    document.getElementById('boton_ingresar_permiso').style.display = 'none';
    document.getElementById('boton_modificar_permiso').style.display = 'block';
}
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** RECONOCIMIENTOS ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
function ingresarReconocimientos() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var tipo = document.getElementById('tipo_reconocimientos').value;
    var motivo = document.getElementById('motivo_reconocimientos').value;
    var fecha = document.getElementById('fecha_reconocimientos').value;
    var numero_documentos = document.getElementById('numero_documentos_reconocimientos').value;
    var fecha_entrega = document.getElementById('fecha_entrega_reconocimientos').value;
    if (document.getElementById('constancia_anexa_reconocimientos').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_reconocimientos').value;
    var observaciones = document.getElementById('observaciones_reconocimientos').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El reconocimiento fue ingresado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe el reconocimiento no se pudo ingresar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiarReconocimientos();
            consultarReconocimientos(idtrabajador);
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&tipo=" + tipo + "&motivo=" + motivo + "&fecha=" + fecha + "&numero_documentos=" + numero_documentos + "&fecha_entrega=" + fecha_entrega + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&observaciones=" + observaciones + "&ejecutar=ingresarReconocimientos");
}

function modificarReconocimientos() {
    var idreconocimientos = document.getElementById('idreconocimientos').value;
    var tipo = document.getElementById('tipo_reconocimientos').value;
    var motivo = document.getElementById('motivo_reconocimientos').value;
    var fecha = document.getElementById('fecha_reconocimientos').value;
    var numero_documentos = document.getElementById('numero_documentos_reconocimientos').value;
    var fecha_entrega = document.getElementById('fecha_entrega_reconocimientos').value;
    if (document.getElementById('constancia_anexa_reconocimientos').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_reconocimientos').value;
    var observaciones = document.getElementById('observaciones_reconocimientos').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El reconocimiento fue Modificado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe el reconocimiento no se pudo Modificar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiarReconocimientos();
            consultarReconocimientos(document.getElementById('idtrabajador').value);
        }
    }
    ajax.send("idreconocimientos=" + idreconocimientos + "&tipo=" + tipo + "&motivo=" + motivo + "&fecha=" + fecha + "&numero_documentos=" + numero_documentos + "&fecha_entrega=" + fecha_entrega + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&observaciones=" + observaciones + "&ejecutar=modificarReconocimientos");
}

function seleccionarReconocimientos(idreconocimientos, tipo, motivo, fecha, numero_documentos, fecha_entrega, constancia_anexa, nombre_imagen, observaciones) {
    document.getElementById('idreconocimientos').value = idreconocimientos;
    document.getElementById('tipo_reconocimientos').value = tipo;
    document.getElementById('motivo_reconocimientos').value = motivo;
    document.getElementById('fecha_reconocimientos').value = fecha;
    document.getElementById('numero_documentos_reconocimientos').value = numero_documentos;
    document.getElementById('fecha_entrega_reconocimientos').value = fecha_entrega;
    if (constancia_anexa == "si") {
        document.getElementById('constancia_anexa_reconocimientos').checked = true;
    } else {
        document.getElementById('constancia_anexa_reconocimientos').checked = false;
    }
    //document.getElementById('nombre_imagen_reconocimientos').value 			= nombre_imagen;
    document.getElementById('observaciones_reconocimientos').value = observaciones;
    document.getElementById('boton_ingresar_reconocimientos').style.display = "none";
    document.getElementById('boton_modificar_reconocimientos').style.display = "block";
}

function eliminarReconocimiento(idreconocimientos) {
    if (confirm("Seguro desea eliminar el reconocimiento seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                alert(ajax.responseText);
                consultarReconocimientos(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idreconocimientos=" + idreconocimientos + "&ejecutar=eliminarReconocimiento");
    }
}

function consultarReconocimientos(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('listaReconocimientos').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarReconocimientos");
}

function limpiarReconocimientos() {
    document.getElementById('idreconocimientos').value = "";
    document.getElementById('tipo_reconocimientos').value = "";
    document.getElementById('motivo_reconocimientos').value = "";
    document.getElementById('fecha_reconocimientos').value = "";
    document.getElementById('numero_documentos_reconocimientos').value = "";
    document.getElementById('fecha_entrega_reconocimientos').value = "";
    document.getElementById('constancia_anexa_reconocimientos').checked = false;
    document.getElementById('nombre_imagen_reconocimientos').value = "";
    document.getElementById('observaciones_reconocimientos').value = "";
    document.getElementById('boton_ingresar_reconocimientos').style.display = "block";
    document.getElementById('boton_modificar_reconocimientos').style.display = "none";
}
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** SANCIONES ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
function ingresarsanciones() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var tipo = document.getElementById('tipo_sanciones').value;
    var motivo = document.getElementById('motivo_sanciones').value;
    var fecha = document.getElementById('fecha_sanciones').value;
    var numero_documentos = document.getElementById('numero_documentos_sanciones').value;
    var fecha_entrega = document.getElementById('fecha_entrega_sanciones').value;
    if (document.getElementById('constancia_anexa_sanciones').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_sanciones').value;
    var observaciones = document.getElementById('observaciones_sanciones').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "La Sancion fue ingresado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe la Sancion no se pudo ingresar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiarsanciones();
            consultarsanciones(idtrabajador);
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&tipo=" + tipo + "&motivo=" + motivo + "&fecha=" + fecha + "&numero_documentos=" + numero_documentos + "&fecha_entrega=" + fecha_entrega + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&observaciones=" + observaciones + "&ejecutar=ingresarsanciones");
}

function modificarsanciones() {
    var idsanciones = document.getElementById('idsanciones').value;
    var tipo = document.getElementById('tipo_sanciones').value;
    var motivo = document.getElementById('motivo_sanciones').value;
    var fecha = document.getElementById('fecha_sanciones').value;
    var numero_documentos = document.getElementById('numero_documentos_sanciones').value;
    var fecha_entrega = document.getElementById('fecha_entrega_sanciones').value;
    if (document.getElementById('constancia_anexa_sanciones').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_sanciones').value;
    var observaciones = document.getElementById('observaciones_sanciones').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El sancion fue Modificado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe el sancion no se pudo Modificar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiarsanciones();
            consultarsanciones(document.getElementById('idtrabajador').value);
        }
    }
    ajax.send("idsanciones=" + idsanciones + "&tipo=" + tipo + "&motivo=" + motivo + "&fecha=" + fecha + "&numero_documentos=" + numero_documentos + "&fecha_entrega=" + fecha_entrega + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&observaciones=" + observaciones + "&ejecutar=modificarsanciones");
}

function seleccionarsanciones(idsanciones, tipo, motivo, fecha, numero_documentos, fecha_entrega, constancia_anexa, nombre_imagen, observaciones) {
    document.getElementById('idsanciones').value = idsanciones;
    document.getElementById('tipo_sanciones').value = tipo;
    document.getElementById('motivo_sanciones').value = motivo;
    document.getElementById('fecha_sanciones').value = fecha;
    document.getElementById('numero_documentos_sanciones').value = numero_documentos;
    document.getElementById('fecha_entrega_sanciones').value = fecha_entrega;
    if (constancia_anexa == "si") {
        document.getElementById('constancia_anexa_sanciones').checked = true;
    } else {
        document.getElementById('constancia_anexa_sanciones').checked = false;
    }
    //document.getElementById('nombre_imagen_sanciones').value 				= nombre_imagen;
    document.getElementById('observaciones_sanciones').value = observaciones;
    document.getElementById('boton_ingresar_sanciones').style.display = "none";
    document.getElementById('boton_modificar_sanciones').style.display = "block";
}

function eliminarsancion(idsanciones) {
    if (confirm("Seguro desea eliminar el sancion seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                alert(ajax.responseText);
                consultarsanciones(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idsanciones=" + idsanciones + "&ejecutar=eliminarsancion");
    }
}

function consultarsanciones(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('listasanciones').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarsanciones");
}

function limpiarsanciones() {
    document.getElementById('idsanciones').value = "";
    document.getElementById('tipo_sanciones').value = "";
    document.getElementById('motivo_sanciones').value = "";
    document.getElementById('fecha_sanciones').value = "";
    document.getElementById('numero_documentos_sanciones').value = "";
    document.getElementById('fecha_entrega_sanciones').value = "";
    document.getElementById('constancia_anexa_sanciones').checked = false;
    document.getElementById('nombre_imagen_sanciones').value = "";
    document.getElementById('observaciones_sanciones').value = "";
    document.getElementById('boton_ingresar_sanciones').style.display = "block";
    document.getElementById('boton_modificar_sanciones').style.display = "none";
}
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** DECLARACION JURADA ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
function ingresardeclaracion_jurada() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var tipo = document.getElementById('tipo_declaracion_jurada').value;
    var fecha_declaracion = document.getElementById('fecha_declaracion_jurada').value;
    var fecha_entrega = document.getElementById('fecha_entrega_declaracion_jurada').value;
    if (document.getElementById('constancia_anexa_declaracion_jurada').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_declaracion_jurada').value;
    var observaciones = document.getElementById('observaciones_declaracion_jurada').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "La Sancion fue ingresado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe la Sancion no se pudo ingresar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiardeclaracion_jurada();
            consultardeclaracion_jurada(idtrabajador);
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&tipo=" + tipo + "&fecha_declaracion=" + fecha_declaracion + "&fecha_entrega=" + fecha_entrega + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&observaciones=" + observaciones + "&ejecutar=ingresardeclaracion_jurada");
}

function modificardeclaracion_jurada() {
    var iddeclaracion_jurada = document.getElementById('iddeclaracion_jurada').value;
    var tipo = document.getElementById('tipo_declaracion_jurada').value;
    var fecha_declaracion = document.getElementById('fecha_declaracion_jurada').value;
    var fecha_entrega = document.getElementById('fecha_entrega_declaracion_jurada').value;
    if (document.getElementById('constancia_anexa_declaracion_jurada').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_declaracion_jurada').value;
    var observaciones = document.getElementById('observaciones_declaracion_jurada').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El sancion fue Modificado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe el sancion no se pudo Modificar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiardeclaracion_jurada();
            consultardeclaracion_jurada(document.getElementById('idtrabajador').value);
        }
    }
    ajax.send("iddeclaracion_jurada=" + iddeclaracion_jurada + "&tipo=" + tipo + "&fecha_declaracion=" + fecha_declaracion + "&fecha_entrega=" + fecha_entrega + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&observaciones=" + observaciones + "&ejecutar=modificardeclaracion_jurada");
}

function seleccionardeclaracion_jurada(iddeclaracion_jurada, tipo, fecha_declaracion, fecha_entrega, constancia_anexa, nombre_imagen, observaciones) {
    document.getElementById('iddeclaracion_jurada').value = iddeclaracion_jurada;
    document.getElementById('tipo_declaracion_jurada').value = tipo;
    document.getElementById('fecha_declaracion_jurada').value = fecha_declaracion;
    document.getElementById('fecha_entrega_declaracion_jurada').value = fecha_entrega;
    if (constancia_anexa == "si") {
        document.getElementById('constancia_anexa_declaracion_jurada').checked = true;
    } else {
        document.getElementById('constancia_anexa_declaracion_jurada').checked = false;
    }
    //document.getElementById('nombre_imagen_declaracion_jurada').value 				= nombre_imagen;
    document.getElementById('observaciones_declaracion_jurada').value = observaciones;
    document.getElementById('boton_ingresar_declaracion_jurada').style.display = "none";
    document.getElementById('boton_modificar_declaracion_jurada').style.display = "block";
}

function eliminarDeclaracion_jurada(iddeclaracion_jurada) {
    if (confirm("Seguro desea eliminar el sancion seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);	
                consultardeclaracion_jurada(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("iddeclaracion_jurada=" + iddeclaracion_jurada + "&ejecutar=eliminarDeclaracion_jurada");
    }
}

function consultardeclaracion_jurada(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('listadeclaracion_jurada').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultardeclaracion_jurada");
}

function limpiardeclaracion_jurada() {
    document.getElementById('iddeclaracion_jurada').value = "";
    document.getElementById('tipo_declaracion_jurada').value = "";
    document.getElementById('fecha_declaracion_jurada').value = "";
    document.getElementById('fecha_entrega_declaracion_jurada').value = "";
    document.getElementById('constancia_anexa_declaracion_jurada').checked = false;
    document.getElementById('nombre_imagen_declaracion_jurada').value = "";
    document.getElementById('observaciones_declaracion_jurada').value = "";
    document.getElementById('boton_ingresar_declaracion_jurada').style.display = "block";
    document.getElementById('boton_modificar_declaracion_jurada').style.display = "none";
}
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
// *************************************************** CURSOS REALIZADOS ****************************************************** 
// ************************************************************************************************************************** 
// ************************************************************************************************************************** 
function ingresarCursos() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var denominacion = document.getElementById('denominacion_cursos').value;
    var detalle_contenido = document.getElementById('detalle_contenido_cursos').value;
    var desde = document.getElementById('fecha_desde_cursos').value;
    var hasta = document.getElementById('fecha_hasta_cursos').value;
    var duracion = document.getElementById('duracion_cursos').value;
    var institucion = document.getElementById('institucion_cursos').value;
    var telefonos = document.getElementById('telefonos_cursos').value;
    var realizado_por = document.getElementById('realizado_por').value;
    var tipo_duracion = document.getElementById('tipo_duracion').value;
    if (document.getElementById('constancia_anexa_cursos').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_cursos').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El Curso fue ingresado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe el curso no se pudo ingresar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiarCursos();
            consultarCursos(idtrabajador);
        }
    }
    ajax.send("tipo_duracion=" + tipo_duracion + "&idtrabajador=" + idtrabajador + "&denominacion=" + denominacion + "&detalle_contenido=" + detalle_contenido + "&desde=" + desde + "&hasta=" + hasta + "&duracion=" + duracion + "&institucion=" + institucion + "&telefonos=" + telefonos + "&realizado_por=" + realizado_por + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&ejecutar=ingresarCursos");
}

function modificarCursos() {
    var idcursos = document.getElementById('idcurso').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var denominacion = document.getElementById('denominacion_cursos').value;
    var detalle_contenido = document.getElementById('detalle_contenido_cursos').value;
    var desde = document.getElementById('fecha_desde_cursos').value;
    var hasta = document.getElementById('fecha_hasta_cursos').value;
    var duracion = document.getElementById('duracion_cursos').value;
    var institucion = document.getElementById('institucion_cursos').value;
    var telefonos = document.getElementById('telefonos_cursos').value;
    var realizado_por = document.getElementById('realizado_por').value;
    var tipo_duracion = document.getElementById('tipo_duracion').value;
    if (document.getElementById('constancia_anexa_cursos').checked == true) {
        var constancia_anexa = "si";
    } else {
        var constancia_anexa = "no";
    }
    var nombre_imagen = document.getElementById('nombre_imagen_cursos').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "exito") {
                mostrarMensajes("exito", "El Curso fue Modificado con Exito");
            } else {
                mostrarMensajes("error", "Disculpe el curso no se pudo Modificar, por favor intente de nuevo mas tarde (" + ajax.responseText + ")");
            }
            limpiarCursos();
            consultarCursos(document.getElementById('idtrabajador').value);
        }
    }
    ajax.send("tipo_duracion=" + tipo_duracion + "&idcursos=" + idcursos + "&denominacion=" + denominacion + "&detalle_contenido=" + detalle_contenido + "&desde=" + desde + "&hasta=" + hasta + "&duracion=" + duracion + "&institucion=" + institucion + "&telefonos=" + telefonos + "&realizado_por=" + realizado_por + "&constancia_anexa=" + constancia_anexa + "&nombre_imagen=" + nombre_imagen + "&ejecutar=modificarCursos");
}

function seleccionarCursos(idcurso, denominacion, detalle_contenido, fecha_desde, fecha_hasta, duracion, institucion, telefonos, realizado_por, constancia_anexa, tipo_duracion) {
    document.getElementById('idcurso').value = idcurso;
    document.getElementById('denominacion_cursos').value = denominacion;
    document.getElementById('detalle_contenido_cursos').value = detalle_contenido;
    document.getElementById('fecha_desde_cursos').value = fecha_desde;
    document.getElementById('fecha_hasta_cursos').value = fecha_hasta;
    document.getElementById('duracion_cursos').value = duracion;
    document.getElementById('institucion_cursos').value = institucion;
    document.getElementById('telefonos_cursos').value = telefonos;
    document.getElementById('realizado_por').value = realizado_por;
    document.getElementById('tipo_duracion').value = tipo_duracion;
    if (constancia_anexa == "si") {
        document.getElementById('constancia_anexa_cursos').checked = true;
    } else {
        document.getElementById('constancia_anexa_cursos').checked = false;
    }
    document.getElementById('boton_ingresar_cursos').style.display = "none";
    document.getElementById('boton_modificar_cursos').style.display = "block";
}

function eliminarCursos(idcursos) {
    if (confirm("Seguro desea eliminar el Curso seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                //alert(ajax.responseText);	
                consultarCursos(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idcursos=" + idcursos + "&ejecutar=eliminarCursos");
    }
}

function consultarCursos(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_cursos').innerHTML = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarCursos");
}

function limpiarCursos() {
    document.getElementById('denominacion_cursos').value = "";
    document.getElementById('detalle_contenido_cursos').value = "";
    document.getElementById('fecha_desde_cursos').value = "";
    document.getElementById('fecha_hasta_cursos').value = "";
    document.getElementById('duracion_cursos').value = "";
    document.getElementById('institucion_cursos').value = "";
    document.getElementById('telefonos_cursos').value = "";
    document.getElementById('realizado_por').value = '';
    document.getElementById('tipo_duracion').value = '';
    document.getElementById('constancia_anexa_cursos').checked = false;
    document.getElementById('boton_ingresar_cursos').style.display = "block";
    document.getElementById('boton_modificar_cursos').style.display = "none";
}
// **************************************************************************************************************************
// **************************************************************************************************************************
// ***************************************************** HISTORICO DE VACACIONES ********************************************
// **************************************************************************************************************************
// **************************************************************************************************************************
function ajustarFechas() {
    var fecha_inicio_vacacion = document.getElementById('fecha_inicio_vacacion_vacaciones').value;
    var fecha_culminacion_vacacion = document.getElementById('fecha_culminacion_vacacion_vacaciones').value;
    var fecha_inicio_disfrute = document.getElementById("fecha_inicio_disfrute_vacaciones").value;
    var fecha_culminacion_disfrute = document.getElementById("fecha_reincorporacion_vacaciones").value;
    var cantidad_dias_feriados = document.getElementById("cantidad_feriados_vacaciones").value;
    var fecha_culminacion_ajustada = document.getElementById("fecha_reincorporacion_ajustada_vacaciones").value;
    var tiempo_disfrute_vacaciones = "";
    var tiempo_disfrute = "";
    var total_fecha_ajustada = "";
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            tiempo_disfrute_vacaciones = ajax.responseText;
            var ajax2 = nuevoAjax();
            ajax2.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
            ajax2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax2.onreadystatechange = function() {
                if (ajax2.readyState == 1) {}
                if (ajax2.readyState == 4) {
                    tiempo_disfrute = ajax2.responseText;
                    var total_restante_disfrute = (parseInt(tiempo_disfrute_vacaciones) - parseInt(tiempo_disfrute));
                    var ajax3 = nuevoAjax();
                    ajax3.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
                    ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    ajax3.onreadystatechange = function() {
                        if (ajax3.readyState == 1) {}
                        if (ajax3.readyState == 4) {
                            total_fecha_ajustada = ajax3.responseText;
                            var total_ajustado = parseInt(tiempo_disfrute_vacaciones) - parseInt(total_fecha_ajustada);
                            var total = parseInt(total_restante_disfrute) + parseInt(total_ajustado) + parseInt(cantidad_dias_feriados);
                            setTimeout("document.getElementById('tiempo_disfrute_vacaciones').value = " + parseInt(tiempo_disfrute_vacaciones), 100);
                            if (document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value != '') {
                                setTimeout("document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = " + total, 100);
                                var total_pendiente = parseInt(document.getElementById('tiempo_pendiente_acumulado_oculto').value) + parseInt(total);
                                setTimeout("document.getElementById('tiempo_pendiente_acumulado').value = " + total_pendiente, 300);
                            }
                        }
                    }
                    ajax3.send("fecha_inicio=" + fecha_inicio_vacacion + "&fecha_culminacion=" + fecha_culminacion_ajustada + "&ejecutar=validarFechas_vacaciones");
                }
            }
            ajax2.send("fecha_inicio=" + fecha_inicio_disfrute + "&fecha_culminacion=" + fecha_culminacion_disfrute + "&ejecutar=validarFechas_vacaciones");
        }
    }
    ajax.send("fecha_inicio=" + fecha_inicio_vacacion + "&fecha_culminacion=" + fecha_culminacion_vacacion + "&ejecutar=validarFechas_vacaciones");
}

function consultarVacacionesPendientes(idtrabajador) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('tiempo_pendiente_acumulado_oculto').value = ajax.responseText;
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarVacacionesPendientes");
}
/*
function verificarFecha_historicoVacaciones(fecha_inicio, fecha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_disfrute_vacaciones').value 	= ajax.responseText;
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+fecha_culminacion+"&ejecutar=validarFechas_vacaciones");
}



function verificarFechaDisfrute_historicoVacaciones(fecha_inicio, fecha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 	= parseInt(document.getElementById('tiempo_disfrute_vacaciones').value) - parseInt(ajax.responseText)+parseInt(document.getElementById("cantidad_feriados_vacaciones").value);
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+fecha_culminacion+"&ejecutar=validarFechas_vacaciones");
}



function verificarFechaAjustada_historicoVacaciones(fecha_inicio, fecha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 	= (parseInt(document.getElementById('tiempo_disfrute_vacaciones').value) - parseInt(ajax.responseText))+parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value)+parseInt(document.getElementById("cantidad_feriados_vacaciones").value);
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+fecha_culminacion+"&ejecutar=validarFechas_vacaciones");
}



function aumentarFeriados(valor){
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value) + parseInt(valor);
}





//**********************************









function validarPeriodo_vacaciones(periodo){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			if(sms == 0){
				document.getElementById('periodo_vacaciones').style = "#fec092";
				}else{
					if(sms == 1){
						document.getElementById('periodo_vacaciones').style.background = "#ffffff";
						}else{
							document.getElementById('periodo_vacaciones').style.background = "#fec092";							
							}
					}
		} 
	}
	ajax.send("periodo="+periodo+"&ejecutar=validarPeriodo_vacaciones");		
}

function validarFechasDisfrute_vacaciones(fecha_inicio,feha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('oculto_disfrutados_vacaciones').value 			= ajax.responseText;
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value 	= ajax.responseText;
			
			document.getElementById('oculto_dias_vacaciones').value 		= ajax.responseText;
			var tiempo_disfrute 											= document.getElementById('tiempo_disfrute_vacaciones').value;
			var dias_disfrutados 											= document.getElementById('oculto_disfrutados_vacaciones').value;
			
			if(document.getElementById('cantidad_feriados_vacaciones').value != ''){
				restar_feriados_vacaciones(document.getElementById('cantidad_feriados_vacaciones').value);	
			}
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+feha_culminacion+"&ejecutar=validarFechas_vacaciones");		
}






function restar_feriados_vacaciones(cant_feriados){
	
	
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = (parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value)+parseInt(cant_feriados));
	
	
	document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = (parseInt(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value) - parseInt(document.getElementById('tiempo_disfrute_vacaciones').value));
	
	
	
	//alert(document.getElementById('tiempo_pendiente_disfrute_vacaciones').value);
	
	
	
	/*var ajax=nuevoAjax();
	var dias_disfrute = document.getElementById('oculto_dias_vacaciones').value;
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){	
			
			document.getElementById('oculto_disfrutados_vacaciones').value = ajax.responseText;
			
			var tiempo_disfrute 	= document.getElementById('tiempo_disfrute_vacaciones').value;
			var dias_disfrutados 	= document.getElementById('oculto_disfrutados_vacaciones').value;
			//obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("catidad_feriados="+cant_feriados+"&dias_disfrute="+dias_disfrute+"&ejecutar=cant_feriados");		
}




function validarFechas_vacaciones(fecha_inicio,feha_culminacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){
			}
		if (ajax.readyState==4){
			document.getElementById('tiempo_disfrute_vacaciones').value 	= ajax.responseText;
			var tiempo_disfrute 											= document.getElementById('tiempo_disfrute_vacaciones').value;
			var dias_disfrutados 											= document.getElementById('oculto_disfrutados_vacaciones').value;
			obtener_disfrutes_completos_vacaciones(tiempo_disfrute,dias_disfrutados);
		} 
	}
	ajax.send("fecha_inicio="+fecha_inicio+"&fecha_culminacion="+feha_culminacion+"&ejecutar=validarFechas_vacaciones");		
}

function reinicioAjustado_vacaciones(reinicio_ajustado,fecha_reincorporacion){
	var ajax=nuevoAjax();
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){
			var sms = ajax.responseText;
			alert(sms);
					if(sms != 0){
						alert("AQUI");
					var desde = document.getElementById('fecha_inicio_disfrute_vacaciones').value;
					var hasta = document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value;
					validarFechasDisfrute_vacaciones(desde,hasta);
					}
					
		} 
	}
	ajax.send("reinicio_ajustado="+reinicio_ajustado+"&fecha_reincorporacion="+fecha_reincorporacion+"&ejecutar=reinicioAjustado_vacaciones");		
}



function obtener_disfrutes_completos_vacaciones(tiempo_disfrute,tiempo_disfrutado){
	var ajax=nuevoAjax();
	var dias_disfrute = document.getElementById('oculto_dias_vacaciones').value;
	ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() { 
		if(ajax.readyState == 1){}
		if (ajax.readyState==4){	
			document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = ajax.responseText;
		} 
	}
	ajax.send("tiempo_disfrute="+tiempo_disfrute+"&tiempo_disfrutado="+tiempo_disfrutado+"&ejecutar=obten_disfrute_completo");		
}
*/
function llenargrilla_vacaciones(idtrabajador) {
    //alert(idtrabajador);
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            document.getElementById('grilla_vacaciones').innerHTML = ajax.responseText;
            //document.getElementById('accion_vacaciones').value 	= "Guardar";
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=llenargrilla_vacaciones");
}

function llenaroculto_vacaciones(valor) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //document.getElementById('grilla').innerHTML = ajax.responseText;			
        }
    }
    ajax.send("valor=" + valor + "&ejecutar=llenaroculto_vacaciones");
}

function accion_vacaciones(accion) {
    var ajax = nuevoAjax();
    var idtrabajador = document.getElementById('idtrabajador').value;
    var periodo = document.getElementById('periodo_vacaciones').value;
    var numero_memorandum = document.getElementById('numero_memorandum_vacaciones').value;
    var fecha_memorandum = document.getElementById('fecha_memorandum_vacaciones').value;
    var fecha_inicio_vacacion = document.getElementById('fecha_inicio_vacacion_vacaciones').value;
    var fecha_culminacion_vacacion = document.getElementById('fecha_culminacion_vacacion_vacaciones').value;
    var tiempo_disfrute = document.getElementById('tiempo_disfrute_vacaciones').value;
    var fecha_inicio_disfrute = document.getElementById('fecha_inicio_disfrute_vacaciones').value;
    var fecha_reincorporacion = document.getElementById('fecha_reincorporacion_vacaciones').value;
    var fecha_reincorporacion_ajustada = document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value;
    var tiempo_pendiente_disfrute = document.getElementById('tiempo_pendiente_disfrute_vacaciones').value;
    var dias_bonificacion = document.getElementById('dias_bonificacion_vacaciones').value;
    var oculto_dias = document.getElementById('oculto_dias_vacaciones').value;
    var oculto_disfrutados = document.getElementById('oculto_disfrutados_vacaciones').value;
    var cantidad_feriados = document.getElementById('cantidad_feriados_vacaciones').value;
    var monto_bono_vacacional = document.getElementById('monto_bono_vacacional_vacaciones').value;
    var numero_orden_pago = document.getElementById('numero_orden_pago_vacaciones').value;
    var fecha_orden_pago = document.getElementById('fecha_orden_pago_vacaciones').value;
    var elaborado_por = document.getElementById('elaborado_por_vacaciones').value;
    var ci_elaborado = document.getElementById('ci_elaborado_vacaciones').value;
    var aprobado_por = document.getElementById('aprobado_por_vacaciones').value;
    var ci_aprobado = document.getElementById('ci_aprobado_vacaciones').value;
    var idhistorico_vacaciones = document.getElementById('idhistorico_vacaciones').value;
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            var sms = ajax.responseText;
            if (sms == 0) {
                mostrarMensajes("error", "Seleccione Trabajador");
            } else {
                if (sms == 1) {
                    mostrarMensajes("error", "Campos vacios");
                } else {
                    if (sms == 2) {
                        mostrarMensajes("exito", "Registro Exitoso");
                        llenargrilla_vacaciones(idtrabajador);
                        limpiarCampos_vacaciones();
                    } else {
                        if (sms == 5) {
                            mostrarMensajes("error", "Disculpe el perioro de vacaciones que intenta ingresar ya esta registrado")
                        } else {
                            mostrarMensajes("error", "Error Agregar Historico Vacacional: " + ajax.responseText);
                        }
                    }
                }
            }
            //boton(ajax.responseText);
            //document.getElementById('grilla').innerHTML = ajax.responseText;
            consultarVacacionesPendientes(idtrabajador);
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&oculto_disfrutados=" + oculto_disfrutados + "&oculto_dias=" + oculto_dias + "&cantidad_feriados=" + cantidad_feriados + "&idhistorico_vacaciones=" + idhistorico_vacaciones + "&ci_aprobado=" + ci_aprobado + "&aprobado_por=" + aprobado_por + "&ci_elaborado=" + ci_elaborado + "&elaborado_por=" + elaborado_por + "&fecha_orden_pago=" + fecha_orden_pago + "&numero_orden_pago=" + numero_orden_pago + "&monto_bono_vacacional=" + monto_bono_vacacional + "&dias_bonificacion=" + dias_bonificacion + "&tiempo_pendiente_disfrute=" + tiempo_pendiente_disfrute + "&fecha_reincorporacion_ajustada=" + fecha_reincorporacion_ajustada + "&fecha_reincorporacion=" + fecha_reincorporacion + "&numero_memorandum=" + numero_memorandum + "&fecha_memorandum=" + fecha_memorandum + "&fecha_inicio_vacacion=" + fecha_inicio_vacacion + "&fecha_culminacion_vacacion=" + fecha_culminacion_vacacion + "&fecha_inicio_disfrute=" + fecha_inicio_disfrute + "&tiempo_disfrute=" + tiempo_disfrute + "&periodo=" + periodo + "&accion=" + accion + "&ejecutar=accion_vacaciones");
}

function limpiarCampos_vacaciones() {
    document.getElementById('periodo_vacaciones').style.background = "#ffffff";
    document.getElementById('periodo_vacaciones').value = "";
    document.getElementById('numero_memorandum_vacaciones').value = "";
    document.getElementById('fecha_memorandum_vacaciones').value = "";
    document.getElementById('fecha_inicio_vacacion_vacaciones').value = "";
    document.getElementById('fecha_culminacion_vacacion_vacaciones').value = "";
    document.getElementById('tiempo_disfrute_vacaciones').value = "";
    document.getElementById('fecha_inicio_disfrute_vacaciones').value = "";
    document.getElementById('fecha_reincorporacion_vacaciones').value = "";
    document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value = "";
    document.getElementById('fecha_culminacion_vacacion_vacaciones').value = "";
    document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = "";
    document.getElementById('dias_bonificacion_vacaciones').value = "";
    document.getElementById('monto_bono_vacacional_vacaciones').value = "";
    document.getElementById('numero_orden_pago_vacaciones').value = "";
    document.getElementById('fecha_orden_pago_vacaciones').value = "";
    document.getElementById('elaborado_por_vacaciones').value = "";
    document.getElementById('ci_elaborado_vacaciones').value = "";
    document.getElementById('aprobado_por_vacaciones').value = "";
    document.getElementById('ci_aprobado_vacaciones').value = "";
    document.getElementById('cantidad_feriados_vacaciones').value = 0;
    document.getElementById('oculto_dias_vacaciones').value = "";
    document.getElementById('oculto_disfrutados_vacaciones').value = "";
}

function llenarFormulario_vacaciones(id_historico_vacacion) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('datos_vacaciones').innerHTML = ajax.responseText;
            // creacion de variables por id
            var idhistorico_vacaciones = document.getElementById('idhistorico_vacaciones_encontrado_vacaciones').value;
            //var idtrabajador = document.getElementById('idtrabajador_encontrado').value;
            var periodo = document.getElementById('periodo_encontrado_vacaciones').value;
            var numero_memorandum = document.getElementById('numero_memorandum_encontrado_vacaciones').value;
            var fecha_memorandum = document.getElementById('fecha_memorandum_encontrado_vacaciones').value;
            var fecha_inicio_vacacion = document.getElementById('fecha_inicio_vacacion_encontrado_vacaciones').value;
            var fecha_culminacion_vacacion = document.getElementById('fecha_culminacion_vacacion_encontrado_vacaciones').value;
            var tiempo_disfrute = document.getElementById('tiempo_disfrute_encontrado_vacaciones').value;
            var fecha_inicio_disfrute = document.getElementById('fecha_inicio_disfrute_encontrado_vacaciones').value;
            var fecha_reincorporacion = document.getElementById('fecha_reincorporacion_encontrado_vacaciones').value;
            var fecha_reincorporacion_ajustada = document.getElementById('fecha_reincorporacion_ajustada_encontrado_vacaciones').value;
            var dias_pendiente_disfrute = document.getElementById('dias_pendiente_disfrute_encontrado_vacaciones').value;
            var dias_bono = document.getElementById('dias_bono_encontrado_vacaciones').value;
            var monto_bonos = document.getElementById('monto_bonos_encontrado_vacaciones').value;
            var numero_orden_pago = document.getElementById('numero_orden_pago_encontrado_vacaciones').value;
            var fecha_cancelacion = document.getElementById('fecha_cancelacion_encontrado_vacaciones').value;
            var elaborado_por = document.getElementById('elaborado_por_encontrado_vacaciones').value;
            var ci_elaborado_por = document.getElementById('ci_elaborado_por_encontrado_vacaciones').value;
            var aprobada_por = document.getElementById('aprobada_por_encontrado_vacaciones').value;
            var ci_aprobado = document.getElementById('ci_aprobado_encontrado_vacaciones').value;
            var cantidad_feriados = document.getElementById('cantidad_feriadostabla_vacaciones').value;
            var oculto_dias = document.getElementById('oculto_dias_encontrado_vacaciones').value;
            var oculto_disfrutados = document.getElementById('oculto_disfrutados_encontrado_vacaciones').value;
            //asiganaciuon de valores por campos
            document.getElementById('idhistorico_vacaciones').value = idhistorico_vacaciones;
            document.getElementById('cantidad_feriados_vacaciones').value = cantidad_feriados;
            //document.getElementById('idtrabajador').value = idhistorico_vacaciones;
            document.getElementById('periodo_vacaciones').value = periodo;
            document.getElementById('numero_memorandum_vacaciones').value = numero_memorandum;
            document.getElementById('fecha_memorandum_vacaciones').value = fecha_memorandum;
            document.getElementById('fecha_inicio_vacacion_vacaciones').value = fecha_inicio_vacacion;
            document.getElementById('fecha_culminacion_vacacion_vacaciones').value = fecha_culminacion_vacacion;
            document.getElementById('tiempo_disfrute_vacaciones').value = tiempo_disfrute;
            document.getElementById('fecha_inicio_disfrute_vacaciones').value = fecha_inicio_disfrute;
            document.getElementById('fecha_reincorporacion_vacaciones').value = fecha_reincorporacion;
            document.getElementById('fecha_reincorporacion_ajustada_vacaciones').value = fecha_reincorporacion_ajustada;
            document.getElementById('tiempo_pendiente_disfrute_vacaciones').value = dias_pendiente_disfrute;
            document.getElementById('dias_bonificacion_vacaciones').value = dias_bono;
            document.getElementById('monto_bono_vacacional_vacaciones').value = monto_bonos;
            document.getElementById('numero_orden_pago_vacaciones').value = numero_orden_pago;
            document.getElementById('fecha_orden_pago_vacaciones').value = fecha_cancelacion;
            document.getElementById('elaborado_por_vacaciones').value = elaborado_por;
            document.getElementById('ci_elaborado_vacaciones').value = ci_elaborado_por;
            document.getElementById('aprobado_por_vacaciones').value = aprobada_por;
            document.getElementById('ci_aprobado_vacaciones').value = ci_aprobado;
            document.getElementById('oculto_dias_vacaciones').value = oculto_dias;
            document.getElementById('oculto_disfrutados_vacaciones').value = oculto_disfrutados;
            document.getElementById('boton_ingresar_vacaciones').style.display = 'none';
            document.getElementById('boton_modificar_vacaciones').style.display = 'block';
        }
    }
    ajax.send("id_historico=" + id_historico_vacacion + "&ejecutar=llenarFormulario_vacaciones");
}

function eliminar_vacaciones(idhistorico_vacaciones) {
    if (confirm("Realmente desea eliminar el historico de vacaciones seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                llenargrilla_vacaciones(document.getElementById('idtrabajador').value);
            }
        }
        ajax.send("idhistorico_vacaciones=" + idhistorico_vacaciones + "&ejecutar=eliminar_vacaciones");
    }
}
// **************************************************************************************************************************
// **************************************************************************************************************************
// ***************************************************** CUENTAS BANCARIAS ********************************************
// **************************************************************************************************************************
// **************************************************************************************************************************
function consultarAsociados_cuentas_bancarias() {
    var idtrabajador = document.getElementById("idtrabajador").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            document.getElementById("lista_cuentas_bancarias").innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarAsociados_cuentas_bancarias");
}

function ingresarCunetaBancaria() {
    var idtrabajador = document.getElementById("idtrabajador").value;
    var numero_cuenta = document.getElementById("numero_cuenta_cuentas_bancarias").value;
    var banco = document.getElementById("banco_cuentas_bancarias").value;
    var tipo_cuenta = document.getElementById("tipo_cuenta_cuentas_bancarias").value;
    var motivo_cuenta = document.getElementById("motivo_cuenta_cuentas_bancarias").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            if (ajax.responseText == 'exito') {
                mostrarMensajes("exito", "La cuenta bancaria fue ingresada con exito");
                limpiarDatos_cuentas_bancarias();
                consultarAsociados_cuentas_bancarias();
            } else {
                mostrarMensajes("error", "La cuenta bancaria YA EXISTE PARA OTRO TRABAJADOR");
                document.getElementById("divCargando").style.display = "none";
            }
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&numero_cuenta=" + numero_cuenta + "&banco=" + banco + "&tipo_cuenta=" + tipo_cuenta + "&motivo_cuenta=" + motivo_cuenta + "&ejecutar=ingresarCunetaBancaria");
}

function modificarCunetaBancaria() {
    var idcuenta_bancaria = document.getElementById("idcuenta_bancaria").value;
    var numero_cuenta = document.getElementById("numero_cuenta_cuentas_bancarias").value;
    var banco = document.getElementById("banco_cuentas_bancarias").value;
    var tipo_cuenta = document.getElementById("tipo_cuenta_cuentas_bancarias").value;
    var motivo_cuenta = document.getElementById("motivo_cuenta_cuentas_bancarias").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            limpiarDatos_cuentas_bancarias();
            consultarAsociados_cuentas_bancarias();
        }
    }
    ajax.send("idcuenta_bancaria=" + idcuenta_bancaria + "&numero_cuenta=" + numero_cuenta + "&banco=" + banco + "&tipo_cuenta=" + tipo_cuenta + "&motivo_cuenta=" + motivo_cuenta + "&ejecutar=modificarCunetaBancaria");
}

function seleccionar_cuentas_bancarias(nombres, nro_cuenta, tipo, motivo, banco, idcuenta_bancaria) {
    document.getElementById("idcuenta_bancaria").value = idcuenta_bancaria;
    document.getElementById("numero_cuenta_cuentas_bancarias").value = nro_cuenta;
    document.getElementById("banco_cuentas_bancarias").value = banco;
    document.getElementById("tipo_cuenta_cuentas_bancarias").value = tipo;
    document.getElementById("motivo_cuenta_cuentas_bancarias").value = motivo;
    document.getElementById("boton_ingresar_cuentas_bancarias").style.display = "none";
    document.getElementById("boton_modificar_cuentas_bancarias").style.display = "block";
    document.getElementById("boton_eliminar_cuentas_bancarias").style.display = "block";
}

function eliminarCuentaBancaria() {
    if (confirm("Realmente desea Eliminar esta Cuenta Bancaria?")) {
        var idcuenta_bancaria = document.getElementById("idcuenta_bancaria").value;
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/cuentas_bancarias_trabajador_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                limpiarDatos_cuentas_bancarias();
                consultarAsociados_cuentas_bancarias();
            }
        }
        ajax.send("idcuenta_bancaria=" + idcuenta_bancaria + "&ejecutar=eliminarCuentaBancaria");
    }
}

function limpiarDatos_cuentas_bancarias() {
    document.getElementById("idcuenta_bancaria").value = "";
    document.getElementById("numero_cuenta_cuentas_bancarias").value = "";
    document.getElementById("banco_cuentas_bancarias").value = 0;
    document.getElementById("tipo_cuenta_cuentas_bancarias").value = "";
    document.getElementById("motivo_cuenta_cuentas_bancarias").value = "";
    document.getElementById("boton_ingresar_cuentas_bancarias").style.display = "block";
    document.getElementById("boton_modificar_cuentas_bancarias").style.display = "none";
    document.getElementById("boton_eliminar_cuentas_bancarias").style.display = "none";
}

function cargarPeriodosCesantes() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var desde = document.getElementById('desde_continuidad').value;
    var hasta = document.getElementById('hasta_continuidad').value;
    var tiempo = document.getElementById('tiempo_continuidad').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            desde = document.getElementById('desde_continuidad').value = "";
            hasta = document.getElementById('hasta_continuidad').value = "";
            tiempo = document.getElementById('tiempo_continuidad').value = "";
            listarPeriodosCesantes();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&desde=" + desde + "&hasta=" + hasta + "&tiempo=" + tiempo + "&ejecutar=cargarPeriodosCesantes");
}

function listarPeriodosCesantes() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            document.getElementById('lista_periodos_cesantes').innerHTML = ajax.responseText;
            document.getElementById("divCargando").style.display = "none";
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=listarPeriodosCesantes");
}

function seleccionarPeriodosCedentes(desde, hasta, tiempo, idperiodos_cedentes_trabajadores) {
    document.getElementById('desde_continuidad').value = desde;
    document.getElementById('hasta_continuidad').value = hasta;
    document.getElementById('tiempo_continuidad').value = tiempo;
    document.getElementById('idperiodos_cedentes_trabajadores').value = idperiodos_cedentes_trabajadores;
    document.getElementById('img_continuidad').src = 'imagenes/modificar.png';
    document.getElementById('img_continuidad').onclick = modificarPeriodosCedentes;
}

function eliminarPeriodosCedentes(idperiodos_cedentes_trabajadores) {
    if (confirm("Seguro dese eliminar este periodo Cedente?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                document.getElementById("divCargando").style.display = "block";
            }
            if (ajax.readyState == 4) {
                listarPeriodosCesantes();
            }
        }
        ajax.send("idperiodos_cedentes_trabajadores=" + idperiodos_cedentes_trabajadores + "&ejecutar=eliminarPeriodosCedentes");
    }
}

function modificarPeriodosCedentes() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var desde = document.getElementById('desde_continuidad').value;
    var hasta = document.getElementById('hasta_continuidad').value;
    var tiempo = document.getElementById('tiempo_continuidad').value;
    var idperiodos_cedentes_trabajadores = document.getElementById("idperiodos_cedentes_trabajadores").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            document.getElementById("divCargando").style.display = "block";
        }
        if (ajax.readyState == 4) {
            document.getElementById('img_continuidad').src = 'imagenes/validar.png';
            document.getElementById('img_continuidad').onclick = cargarPeriodosCesantes;
            listarPeriodosCesantes();
            desde = document.getElementById('desde_continuidad').value = "";
            hasta = document.getElementById('hasta_continuidad').value = "";
            tiempo = document.getElementById('tiempo_continuidad').value = "";
        }
    }
    ajax.send("idperiodos_cedentes_trabajadores=" + idperiodos_cedentes_trabajadores + "&idtrabajador=" + idtrabajador + "&desde=" + desde + "&hasta=" + hasta + "&tiempo=" + tiempo + "&ejecutar=modificarPeriodosCedentes");
}

function calcularTiempoPeriodosCedentes() {
    var desde = document.getElementById('desde_continuidad').value;
    var hasta = document.getElementById('hasta_continuidad').value;
    if (desde != "" && hasta != "") {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                document.getElementById('tiempo_continuidad').value = ajax.responseText;
            }
        }
        ajax.send("desde=" + desde + "&hasta=" + hasta + "&ejecutar=calcularTiempoPeriodosCedentes");
    }
}
// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** PRESTACIONES ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
function ingresarPrestaciones() {
    var anio = document.getElementById('anio_prestaciones').value;
    var mes = document.getElementById('mes_prestaciones').value;
    var sueldo = document.getElementById('sueldo_prestaciones').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "existe") {
                mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
            }
            document.getElementById('mes_prestaciones').value = 0;
            document.getElementById('sueldo_prestaciones').value = '';
            consultarPrestaciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&anio=" + anio + "&mes=" + mes + "&sueldo=" + sueldo + "&ejecutar=ingresarPrestaciones");
}

function consultarPrestaciones() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var fecha_ingreso = document.getElementById('fecha_ingreso_prestaciones').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_prestaciones').innerHTML = ajax.responseText;
            consultarTotalesGeneralesPrestaciones();
        }
    }
    ajax.send("fecha_ingreso=" + fecha_ingreso + "&idtrabajador=" + idtrabajador + "&ejecutar=consultarPrestaciones");
}

function consultarSelectAniosPrestaciones(anio, mes) {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            //document.getElementById('div_anios_prestaciones').innerHTML = ajax.responseText;
            consultarPrestaciones();
        }
    }
    ajax.send("anio=" + anio + "&mes=" + mes + "&idtrabajador=" + idtrabajador + "&ejecutar=consultarSelectAniosPrestaciones");
}

function eliminarPrestaciones(idtabla_prestaciones) {
    if (confirm("seguro desea eliminar el registro seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarPrestaciones();
            }
        }
        ajax.send("idtabla_prestaciones=" + idtabla_prestaciones + "&ejecutar=eliminarPrestaciones");
    }
}

function actualizarSueldoPrestaciones(sueldo_prestaciones_modificar_idtabla_prestaciones, complementos_prestaciones_modificar_idtabla_prestaciones, bono_vacacional_prestaciones_modificar_idtabla_prestaciones, bono_fin_anio_prestaciones_modificar_idtabla_prestaciones, idtabla_prestaciones) {
    var sueldo = document.getElementById("" + sueldo_prestaciones_modificar_idtabla_prestaciones + "").value;
    var otros = document.getElementById("" + complementos_prestaciones_modificar_idtabla_prestaciones + "").value;
    var bono_vacacional = document.getElementById("" + bono_vacacional_prestaciones_modificar_idtabla_prestaciones + "").value;
    var bono_fin_anio = document.getElementById("" + bono_fin_anio_prestaciones_modificar_idtabla_prestaciones + "").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarPrestaciones();
        }
    }
    ajax.send("sueldo=" + sueldo + "&otros=" + otros + "&bono_vacacional=" + bono_vacacional + "&bono_fin_anio=" + bono_fin_anio + "&idtabla_prestaciones=" + idtabla_prestaciones + "&ejecutar=actualizarSueldoPrestaciones");
}

function guardarValorSueldo(sueldo_prestaciones_modificar_idtabla_prestaciones, idtabla_prestaciones, valor) {
    var sueldo = document.getElementById("" + sueldo_prestaciones_modificar_idtabla_prestaciones + "").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarPrestaciones();
        }
    }
    ajax.send("sueldo=" + sueldo + "&idtabla_prestaciones=" + idtabla_prestaciones + "&ejecutar=guardarValorSueldo");
}

function guardarValorOtros(complementos_prestaciones_modificar_idtabla_prestaciones, idtabla_prestaciones, valor) {
    var otros = document.getElementById("" + complementos_prestaciones_modificar_idtabla_prestaciones + "").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarPrestaciones();
        }
    }
    ajax.send("otros=" + otros + "&idtabla_prestaciones=" + idtabla_prestaciones + "&ejecutar=guardarValorOtros");
}

function guardarValorBonoVacacional(bono_vacacional_prestaciones_modificar_idtabla_prestaciones, idtabla_prestaciones, valor) {
    var bono_vacacional = document.getElementById("" + bono_vacacional_prestaciones_modificar_idtabla_prestaciones + "").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarPrestaciones();
        }
    }
    ajax.send("bono_vacacional=" + bono_vacacional + "&idtabla_prestaciones=" + idtabla_prestaciones + "&ejecutar=guardarValorBonoVacacional");
}

function guardarValorBonoFinAnio(bono_fin_anio_prestaciones_modificar_idtabla_prestaciones, idtabla_prestaciones, valor) {
    var bono_fin_anio = document.getElementById("" + bono_fin_anio_prestaciones_modificar_idtabla_prestaciones + "").value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarPrestaciones();
        }
    }
    ajax.send("bono_fin_anio=" + bono_fin_anio + "&idtabla_prestaciones=" + idtabla_prestaciones + "&ejecutar=guardarValorBonoFinAnio");
}

function ingresarAdelanto(idtabla_prestaciones, adelanto_prestaciones, adelanto_interes) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            consultarPrestaciones();
        }
    }
    ajax.send("idtabla_prestaciones=" + idtabla_prestaciones + "&adelanto_prestaciones=" + adelanto_prestaciones + "&adelanto_interes=" + adelanto_interes + "&ejecutar=ingresarAdelanto");
}

function eliminarAdelanto(idtabla_adelanto) {
    if (confirm("Seguro desea eliminar el ADELANTO DE PRESTACIONES seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarPrestaciones();
                //alert(ajax.responseText);
            }
        }
        ajax.send("idtabla_adelanto=" + idtabla_adelanto + "&ejecutar=eliminarAdelanto");
    }
}
// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** VACACIONES ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
function ingresarVacacionesVencidas() {
    var tipo = document.getElementById('tipo_vacaciones_vencidas').value;
    var periodo = document.getElementById('periodo_vacaciones_vencidas').value;
    var dias = document.getElementById('dias_vacaciones_vencidas').value;
    var sueldo = document.getElementById('sueldo_vacaciones_vencidas').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "existe") {
                mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
            }
            document.getElementById('tipo_vacaciones_vencidas').value = 0;
            document.getElementById('periodo_vacaciones_vencidas').value = 0;
            document.getElementById('dias_vacaciones_vencidas').value = '';
            document.getElementById('sueldo_vacaciones_vencidas').value = '';
            consultarVacaciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&tipo=" + tipo + "&periodo=" + periodo + "&dias=" + dias + "&sueldo=" + sueldo + "&ejecutar=ingresarVacacionesVencidas");
}

function consultarVacaciones() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_vacaciones_vencidas').innerHTML = ajax.responseText;
            consultarTotalesGeneralesPrestaciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarVacaciones");
}

function consultarSelectAniosVacaciones(anio) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('periodo_vacaciones_vencidas').innerHTML = ajax.responseText;
        }
    }
    ajax.send("anio=" + anio + "&ejecutar=consultarSelectAniosVacaciones");
}

function eliminarVacaciones(idvacaciones) {
    if (confirm("seguro desea eliminar el registro seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarVacaciones();
            }
        }
        ajax.send("idvacaciones=" + idvacaciones + "&ejecutar=eliminarVacaciones");
    }
}
// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** AGUINALDO ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
function ingresarAguinaldos() {
    var tipo = document.getElementById('tipo_aguinaldos').value;
    var periodo = document.getElementById('periodo_aguinaldos').value;
    var dias = document.getElementById('dias_aguinaldos').value;
    var sueldo = document.getElementById('sueldo_aguinaldos').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "existe") {
                mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
            }
            document.getElementById('tipo_aguinaldos').value = 0;
            document.getElementById('periodo_aguinaldos').value = 0;
            document.getElementById('dias_aguinaldos').value = '';
            document.getElementById('sueldo_aguinaldos').value = '';
            consultarAguinaldos();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&tipo=" + tipo + "&periodo=" + periodo + "&dias=" + dias + "&sueldo=" + sueldo + "&ejecutar=ingresarAguinaldos");
}

function consultarAguinaldos() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_aguinaldos').innerHTML = ajax.responseText;
            consultarTotalesGeneralesPrestaciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarAguinaldos");
}

function consultarSelectAniosAguinaldos(anio) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('div_anios_aguinaldos').innerHTML = ajax.responseText;
        }
    }
    ajax.send("anio=" + anio + "&ejecutar=consultarSelectAniosAguinaldos");
}

function eliminarAguinaldos(idaguinaldos) {
    if (confirm("seguro desea eliminar el registro seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarAguinaldos();
            }
        }
        ajax.send("idaguinaldos=" + idaguinaldos + "&ejecutar=eliminarAguinaldos");
    }
}
// ********************************************************************************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
// ************************************************** Deducciones ****************************************************
// ********************************************************************************************************************
// ********************************************************************************************************************
function ingresarDeducciones() {
    var tipo = document.getElementById('tipo_deducciones').value;
    var periodo = document.getElementById('periodo_deducciones').value;
    var monto = document.getElementById('monto_deducciones').value;
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            if (ajax.responseText == "existe") {
                mostrarMensajes("error", "Disculpe el periodo que intenta ingresar para este trabajador ya fue registrado, por favor verifique");
            }
            document.getElementById('tipo_deducciones').value = "";
            document.getElementById('periodo_deducciones').value = 0;
            document.getElementById('monto_deducciones').value = '';
            consultarDeducciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&tipo=" + tipo + "&periodo=" + periodo + "&monto=" + monto + "&ejecutar=ingresarDeducciones");
}

function consultarDeducciones() {
    var idtrabajador = document.getElementById('idtrabajador').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('lista_deducciones').innerHTML = ajax.responseText;
            consultarTotalesGeneralesPrestaciones();
        }
    }
    ajax.send("idtrabajador=" + idtrabajador + "&ejecutar=consultarDeducciones");
}

function consultarSelectAniosDeducciones(anio) {
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            document.getElementById('div_anios_deducciones').innerHTML = ajax.responseText;
        }
    }
    ajax.send("anio=" + anio + "&ejecutar=consultarSelectAniosDeducciones");
}

function eliminarDeducciones(iddeducciones) {
    if (confirm("seguro desea eliminar el registro seleccionado?")) {
        var ajax = nuevoAjax();
        ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {}
            if (ajax.readyState == 4) {
                consultarDeducciones();
            }
        }
        ajax.send("iddeducciones=" + iddeducciones + "&ejecutar=eliminarDeducciones");
    }
}
// CONSULTAR TOTALES
function consultarTotalesGeneralesPrestaciones() {
    var idtrabajador = document.getElementById("idtrabajador").value;
    var fecha_ingreso = document.getElementById('fecha_ingreso_prestaciones').value;
    var ajax = nuevoAjax();
    ajax.open("POST", "modulos/rrhh/lib/datos_basicos_ajax.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {}
        if (ajax.readyState == 4) {
            var partes = ajax.responseText.split("|.|");
            document.getElementById('total_prestaciones_acumuladas').innerHTML = partes[0];
            document.getElementById('total_interes_acumulado').innerHTML = partes[1];
            document.getElementById('total_prestaciones_interes_acumulado').innerHTML = partes[2];
            document.getElementById('total_vacaciones_acumuladas').innerHTML = partes[3];
            document.getElementById('total_aguinaldos_acumulados').innerHTML = partes[4];
            document.getElementById('total_deducciones').innerHTML = partes[5];
            document.getElementById('total_a_pagar').innerHTML = partes[6];
        }
    }
    ajax.send("fecha_ingreso=" + fecha_ingreso + "&idtrabajador=" + idtrabajador + "&ejecutar=consultarTotalesGeneralesPrestaciones");
}