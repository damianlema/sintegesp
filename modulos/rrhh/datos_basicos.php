<script src="modulos/rrhh/js/datos_basicos_ajax.js"></script>
<style>
  .div{
    background-color:#EAEAEA;
    border:#333333 solid 1px;
    overflow:auto;
    font-family:Arial, Helvetica, sans-serif;
    font-size:14px;
    padding:10px;
  }

  .div:hover{
    background-color:#FFFFEE;

  }


</style>

<?php
if ($_POST["ingresar"]) {
  $_GET["accion"] = 173;
}

function listar_foros($padre, $titulo)
{
  global $foros;
  foreach ($foros[$padre] as $foro => $datos) {
    if (isset($foros[$foro])) {
      $nuevo_titulo = ($titulo == '') ? $datos['denominacion'] : "$titulo - {$datos['denominacion']} -";
      listar_foros($foro, $nuevo_titulo);
    } else {
      ?>
      <option value="<?=$datos['idniveles_organizacionales']?>">
        <?
        $sql_categoria_programatica = mysql_query("select * from categoria_programatica where idcategoria_programatica = '" . $datos["idcategoria_programatica"] . "'");
        $bus_categoria_programatica = mysql_fetch_array($sql_categoria_programatica);
        ?>
        (<?=$bus_categoria_programatica["codigo"]?>) <?=$titulo . " - " . $datos['denominacion']?>
      </option>
      <?
    }
  }
}

?>



<style type="text/css">


  <!--
  body {
    margin:0;
    padding:0;
    font: bold 10px/1.5em Verdana;
  }

  h2 {
    font: bold 12px Verdana, Arial, Helvetica, sans-serif;
    color: #000;
    margin: 0px;
    padding: 0px 0px 0px 15px;
  }

  /*- Menu Tabs F--------------------------- */

  #tabsF {
    float:left;
    width:100%;
    background:;
    font-size:93%;
    line-height:normal;
    border-bottom:1px solid #666;
  }
  #tabsF ul {
    margin:0;
    padding:2px 2px 0 10px;
    list-style:none;
  }
  #tabsF li {
    display:inline;
    margin:0;
    padding:0;
  }
  #tabsF a {
    float:left;
    background:url("imagenes/tableftF.gif") no-repeat left top;
    margin:0;
    padding:0 0 0 4px;
    text-decoration:none;
  }
  #tabsF a span {
    float:left;
    display:block;
    background:url("imagenes/tabrightF.gif") no-repeat right top;
    padding:5px 15px 4px 6px;
    color:#666;
  }
  /* Commented Backslash Hack hides rule from IE5-Mac \*/
  #tabsF a span {float:none;}
  /* End IE5-Mac hack */
  #tabsF a:hover span {
    color:#FFF;
  }
  #tabsF a:hover {
    background-position:0% -42px;
  }
  #tabsF a:hover span {
    background-position:100% -42px;
  }

  #tabsF #current a {
    background-position:0% -42px;
  }
  #tabsF #current a span {
    background-position:100% -42px;
  }
-->
</style>


<SCRIPT language=JavaScript>





  document.oncontextmenu=new Function("return false")
  <!--- oculta el script para navegadores antiguos
  var miPopUp
  var w=0

  function abreVentana(){
    miPopup=window.open("lib/listas/lista_trabajador.php?frm=datos_basicos","trabajadores","width=1000,height=500,scrollbars=yes")
    miPopup.focus()
  }

  function nuevoEstadoCivil(){
    miPopup=window.open("modulos/rrhh/edocivil.php?emergente=1","edocivil","width=600,height=400,scrollbars=yes")
    miPopup.focus()
  }

  function actualizarselect(){
  //alert("entro")
  cargaContenido("edo_civil")
}

function solonumeros(e){
  var key;
  if(window.event)
    {key=e.keyCode;}
  else if(e.which)
    {key=e.which;}
  if (key < 48 || key > 57)
    {return false;}
  return true;
}



function exportarPrestaciones() {
  var archivo = document.getElementById("archivo").value; archivo = archivo.trim();
  var idtrabajador = document.getElementById("idtrabajador").value;
  var nombre = 'exportarPrestaciones';
  if (archivo=="") alert("¡Debe ingresar el nombre del archivo!");
  else {
    var ajax=nuevoAjax();
    ajax.open("POST", "lib/reportes/recursos_humanos/excel.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
    ajax.onreadystatechange=function() {
      if(ajax.readyState == 1){
        document.getElementById("divCargando").style.display = "block";
      }
      if (ajax.readyState==4) {
        location.href = "lib/reportes/recursos_humanos/excel.php?nombre="+nombre+"&nombre_archivo="+archivo+"&idtrabajador="+idtrabajador;
        document.getElementById("divCargando").style.display = "none";
      }
    }
    ajax.send(null);
  }
}


// end hiding from old browsers -->
</SCRIPT>


<body onLoad="consultarFicha(document.getElementById('nomenclatura_ficha_datosBasicos').value)">
  <table align="center">
    <tr><td>
      <h4 align=center>Trabajadores</h4></td>

      <td>
        <button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick="abreVentana()"><img src='imagenes/search0.png'></button>

        &nbsp;<a href="principal.php?modulo=1&accion=16"><img src="imagenes/nuevo.png" border="0" title="Nuevos Datos Basicos"></a>
        <a href="#" onClick="document.getElementById('divTipoOrden').style.display='block';"><img src="imagenes/imprimir.png" border="0" title="Imprimir"></a>
        <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
          <div align="right"><a href="#" onClick="document.getElementById('divTipoOrden').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='block';">X</a></div>
          <table id="tableImprimir">
            <tr>
              <td>Ordenar Por: </td>
              <td>
                <select name="ordenarPor" id="ordenarPor">
                  <option value="cedula">C&eacute;dula</option>
                  <option value="nombres">Nombres</option>
                  <option value="apellidos">Apellidos</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/reportes/recursos_humanos/reportes.php?nombre=listatrab&orden='+document.getElementById('ordenarPor').value; document.getElementById('pdf').style.display='block'; document.getElementById('tableImprimir').style.display='none';">
              </td>
            </tr>
          </table>
          <iframe name="pdf" id="pdf" style="display:none" height="500" width="500"></iframe>
        </div>


      </td></tr></table>



      <h2 class="sqlmVersion"></h2>

      <input type="hidden" name="idtrabajador" id="idtrabajador">


      <table align="center" width="70%" >
        <tr>
          <td align='center' bgcolor="#3399FF" ><strong>Nro. C&eacute;dula</strong></td>
          <td width="125" align='center' bgcolor="#3399FF"><strong>Nro. Ficha </strong></td>
          <td align='center' bgcolor="#3399FF" ><strong>Nombres</strong> </td>
          <td align='center' bgcolor="#3399FF" ><strong>Apellidos </strong></td>
        </tr>
        <tr>
          <td width="160" align='center' bgcolor="#ccc" id="cedula_buscar" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px"></td>
          <td bgcolor="#ccc" align='center' id="nro_ficha_fijo" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px"><strong>&nbsp;</strong></td>
          <td width="230" align='center' bgcolor="#ccc" id="nombre_fijo" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px"><strong>&nbsp;</strong></td>
          <td width="230" align='center' bgcolor="#ccc" id="apellido_fijo" style="font-weight:bold; font-size: 16px; border:#666666 solid 1px"><strong>&nbsp;</strong></td>

          <tr>
          </table>


          <script>
            function mostrarPestana(div){
              document.getElementById('div_datosBasicos').style.display = 'none';
              document.getElementById('div_registroFotografico').style.display = 'none';
              document.getElementById('div_cargaFamiliar').style.display = 'none';
              document.getElementById('div_estudiosRealizados').style.display = 'none';
              document.getElementById('div_experienciaLaboral').style.display = 'none';
              document.getElementById('div_movimientos').style.display = 'none';
              document.getElementById('div_permisos').style.display = 'none';
              document.getElementById('div_vacaciones').style.display = 'none';
              document.getElementById('div_utilidades').style.display = 'none';
              document.getElementById('div_datosEmpleo').style.display = 'none';
              document.getElementById('div_prestacionesSociales').style.display = 'none';
              document.getElementById('div_cursosRealizados').style.display = 'none';
              document.getElementById('div_declaracionJurada').style.display = 'none';
              document.getElementById('div_sanciones').style.display = 'none';
              document.getElementById('div_reconocimientos').style.display = 'none';
              document.getElementById('div_cuentas_bancarias').style.display = 'none';
              document.getElementById('div_informacionGeneral').style.display = 'none';
              document.getElementById('div_ivss').style.display = 'none';
              document.getElementById(div).style.display = 'block';
            }
          </script>

          <?
//var_dump($privilegios);
          ?>
          <div id="tabsF">
            <ul>
              <li>
                <a href="javascript:;" onClick="mostrarPestana('div_datosBasicos'), document.getElementById('tabla_botones').style.display = 'block'">
                  <span>Datos Basicos</span>
                </a>
              </li>
            </ul>
          </div>

          <br>
          <br>
          <br>

          <!-- ************************************************* CUENTAS BANCARIAS ***************************************-->


          <div id="div_cuentas_bancarias" style="display:none">

            <br>
            <h4 align=center>Cuentas Bancarias</h4>
            <h2 class="sqlmVersion"></h2>
            <br>
            <input name="idcuenta_bancaria" type="hidden" id="idcuenta_bancaria"/>
            <table width="62%" border="0" align="center">
              <tr>
                <td align="right" class="viewPropTitle">Banco</td>
                <td>
                  <?
                  $sql_bancos = mysql_query("select * from banco");
                  ?>
                  <select name="banco_cuentas_bancarias" id="banco_cuentas_bancarias">
                    <option value="0">.:: Seleccione ::.</option>
                    <?
                    while ($bus_bancos = mysql_fetch_array($sql_bancos)) {
                      ?>
                      <option value="<?=$bus_bancos["idbanco"]?>"><?=$bus_bancos["denominacion"]?></option>
                      <?
                    }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td align="right" class="viewPropTitle">Nro de Cuenta</td>
                <td><label>
                  <input name="numero_cuenta_cuentas_bancarias" type="text" id="numero_cuenta_cuentas_bancarias" size="28" maxlength="20">
                </label></td>
              </tr>
              <tr>
                <td align="right" class="viewPropTitle"><p>Tipo de Cuenta</p></td>
                <td><label>
                  <select name="tipo_cuenta_cuentas_bancarias" id="tipo_cuenta_cuentas_bancarias">
                    <option value="Corriente">Corriente</option>
                    <option value="Ahorro">Ahorro</option>
                  </select>
                </label></td>
              </tr>
              <tr>
                <td align="right" class="viewPropTitle">Cuentas Bancarias para Pagar</td>
                <td><label>
                  <select name="motivo_cuenta_cuentas_bancarias" id="motivo_cuenta_cuentas_bancarias">
                    <?
                    $sql_motivos_cuentas = mysql_query("select * from motivos_cuentas");
                    while ($bus_motivos_cuentas = mysql_fetch_array($sql_motivos_cuentas)) {
                      ?><option value="<?=$bus_motivos_cuentas["idmotivos_cuentas"]?>"><?=$bus_motivos_cuentas["denominacion"]?></option><?
                    }
                    ?>
                  </select>
                </label></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" align="center"><table border="0">
                  <tr>
                    <td><label>
                      <input type="submit" name="boton_ingresar_cuentas_bancarias" id="boton_ingresar_cuentas_bancarias" value="Ingresar" class="button" onClick="ingresarCunetaBancaria()">
                    </label></td>
                    <td><label>
                      <input type="submit" name="boton_modificar_cuentas_bancarias" id="boton_modificar_cuentas_bancarias" value="Modificar" class="button" style="display:none" onClick="modificarCunetaBancaria()">
                    </label></td>
                    <td><label>
                      <input type="submit" name="boton_eliminar_cuentas_bancarias" id="boton_eliminar_cuentas_bancarias" value="Eliminar" class="button" style="display:none" onClick="eliminarCuentaBancaria()">
                    </label></td>
                  </tr>
                </table></td>
              </tr>
            </table>
            <br />
            <br />

            <div id="lista_cuentas_bancarias"></div>


          </div>



          <!-- ************************************************* CUENTAS BANCARIAS ***************************************-->




















          <!-- ************************************************* DATOS DE EMPLEO ***************************************-->

          <div id="div_datosEmpleo" style="display:none">
           <br>
           <h4 align=center>Datos del Empleo</h4>
           <h2 class="sqlmVersion"></h2>
           <br>
           <br>
           <table width="80%" align="center">
             <tr>
              <td width="20%" align='right' class='viewPropTitle'>Fecha de Ingreso:</td>
              <td colspan="3" class=''>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="41%"><input name="fecha_ingreso_datosEmpleo" type="text" id="fecha_ingreso_datosEmpleo" size="12" value="<?=$regtrabajador['fecha_ingreso']?>"></td>
                    <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_f" id="f_trigger_f" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                      <script type="text/javascript">
                        Calendar.setup({
                          inputField    : "fecha_ingreso_datosEmpleo",
                          button        : "f_trigger_f",
                          align         : "Tr",
                          ifFormat      : "%Y-%m-%d"
                        });
                      </script>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td align='right' class='viewPropTitle'>Cargo</td>
              <td class='' colspan="3">

               <?
               $sql_cargos = mysql_query("select * from cargos order by denominacion");
               ?>
               <select id="idcargo_datosEmpleo" name="idcargo_datosEmpleo">
                <option value="0">.:: Seleccione ::.</option>
                <?
                while ($bus_cargos = mysql_fetch_array($sql_cargos)) {
                  ?>
                  <option value="<?=$bus_cargos["idcargo"]?>"><?=$bus_cargos["denominacion"]?></option>
                  <?
                }
                ?>
              </select>              </td>
            </tr>
            <tr>
              <td align='right' class='viewPropTitle'>Ubicacion Funcional: </td>
              <td class='' colspan="3"><?
/*      $foros = array();
$result = mysql_query("SELECT idniveles_organizacionales,
denominacion,
idcategoria_programatica,
sub_nivel
FROM
niveles_organizacionales
WHERE
modulo = 1") or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
$foro = $row['idniveles_organizacionales'];
$padre = $row['sub_nivel'];
if(!isset($foros[$padre]))
$foros[$padre] = array();
$foros[$padre][$foro] = $row;
}
 */

$sql_ubicacion_funcional = mysql_query("select * from niveles_organizacionales where modulo = '1' order by codigo");

?>

<select id="ubicacion_funcional_datosEmpleo" name="ubicacion_funcional_datosEmpleo">
  <option value="0">.:: Seleccione ::.</option>
  <?
  while ($bus_ubicacion_funcional = mysql_fetch_array($sql_ubicacion_funcional)) {
    ?>
    <option value="<?=$bus_ubicacion_funcional["idniveles_organizacionales"]?>">(<?=$bus_ubicacion_funcional["codigo"]?>)&nbsp;<?=$bus_ubicacion_funcional["denominacion"]?>
    </option>
    <?
  }
  ?>
</select></td>
</tr>
<tr>
  <td align='right' class='viewPropTitle'>Centro de Costo</td>
  <td class='' colspan="3"><?
/*       $foros = array();
$result = mysql_query("SELECT idniveles_organizacionales,
denominacion,
sub_nivel,
idcategoria_programatica
FROM
niveles_organizacionales
WHERE
modulo = 1
and idcategoria_programatica != 0") or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
$foro = $row['idniveles_organizacionales'];
$padre = $row['sub_nivel'];
if(!isset($foros[$padre]))
$foros[$padre] = array();
$foros[$padre][$foro] = $row;
}

 */

$sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
  un.denominacion,
  ct.codigo
  from
  niveles_organizacionales no,
  unidad_ejecutora un,
  categoria_programatica ct
  where
  no.modulo='1'
  and no.idcategoria_programatica != '0'
  and ct.idcategoria_programatica = no.idcategoria_programatica
  and ct.idunidad_ejecutora = un.idunidad_ejecutora
  order by ct.codigo");

  ?>
  <select id="centro_costo_datosEmpleo" name="centro_costo_datosEmpleo">
    <option value="0">.:: Seleccione ::.</option>
    <?
    while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
      ?>
      <option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(
        <?=$bus_centro_costo["codigo"]?>
        )&nbsp;
        <?=$bus_centro_costo["denominacion"]?>
      </option>
      <?
    }
    ?>
  </select></td>
</tr>

<tr>
  <td align='right' class='viewPropTitle'>Fecha Inicio Continuidad Administrativa</td>
  <td width="10%">
    <input name="fecha_inicio_continuidad" type="text" id="fecha_inicio_continuidad" size="12" value="<?=$regtrabajador['fecha_continuidad_administrativa']?>">
  </td>
  <td width="70%" colspan="2" align="left"><img src="imagenes/jscalendar0.gif" name="f_trigger_f_i" id="f_trigger_f_i" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
    <script type="text/javascript">
      Calendar.setup({
        inputField    : "fecha_inicio_continuidad",
        button        : "f_trigger_f_i",
        align         : "Tr",
        ifFormat      : "%Y-%m-%d"
      });
    </script>
  </td>
</tr>


<tr>
  <td align='right' class='viewPropTitle'>Trabajador de Vacaciones?</td>
  <td colspan="3" class='' style="font-weight:bold; color:#FF0000"><label>
    <input type="checkbox" name="vacaciones_datosEmpleo" id="vacaciones_datosEmpleo">
  </label></td>
</tr>
<tr>
  <td align='right' class='viewPropTitle'>Activo para las Nominas?</td>
  <td colspan="3" class='' style="font-weight:bold; color:#FF0000"><input type="checkbox" id="activo_nomina_datosEmpleo" name="activo_nomina_datosEmpleo" checked></td>
</tr>
<tr>
  <td align='right' class='viewPropTitle'>Estado del Trabajador</td>
  <td colspan="3" class='' id="estado_trabajador_datosEmpleo" style="font-weight:bold; color:#FF0000">Activo</td>
</tr>
<tr>
  <td align='right' class='viewPropTitle'>Motivo del Estado</td>
  <td colspan="3" class='' id="justificacion_estado_datosEmpleo" style="font-weight:bold; color:#FF0000">Ninguna</td>
</tr>
<tr>
 <td colspan="4" align="center"><input type="button" name="boton_modificar_datosEmpleo" id="boton_modificar_datosEmpleo" value="Modificar Datos de Empleo" class="button" onClick="modificarDatosEmpleo()"></td>
</tr>



<tr>
  <td colspan="4">Periodos Cesantes:</td>
</tr>
<tr>
  <td colspan="4">
    <input type="hidden" name="idperiodos_cedentes_trabajadores" id="idperiodos_cedentes_trabajadores">
    <table align="center">
      <tr>
        <td>Desde</td>
        <td><input type="text" id="desde_continuidad" name="desde_continuidad" onChange="calcularTiempoPeriodosCedentes()" readonly>
          <img src="imagenes/jscalendar0.gif" name="f_trigger_f_dc" id="f_trigger_f_dc" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "desde_continuidad",
              button        : "f_trigger_f_dc",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script>
        </td>
        <td>Hasta</td>
        <td><input type="text" id="hasta_continuidad" name="hasta_continuidad" onChange="calcularTiempoPeriodosCedentes()" readonly>
          <img src="imagenes/jscalendar0.gif" name="f_trigger_f_hc" id="f_trigger_f_hc" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "hasta_continuidad",
              button        : "f_trigger_f_hc",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script>
        </td>
        <td>Tiempo</td>
        <td><input type="text" id="tiempo_continuidad" name="tiempo_continuidad" size="40" readonly></td>
        <td><img src="imagenes/validar.png" onClick="cargarPeriodosCesantes()" id="img_continuidad" style="cursor:pointer"></td>
      </tr>
    </table>

  </td>
</tr>

<tr>
 <td colspan="4" id="lista_periodos_cesantes"></td>
</tr>

</table>

</div>
<!-- ************************************************* DATOS DE EMPLEO **************************************-->







<!-- ****************************************** REGISTRO FOTOGRAFICO ************************************************ -->

<div id="div_registroFotografico" style="display:none">
  <br>
  <h4 align=center>Registro Fotografico</h4>
  <h2 class="sqlmVersion"></h2>
  <br>
  <br>
  <div id="mostrarImagen" align="center"></div>
  <table align="center">
    <tr>
      <td align='right' class='viewPropTitle'>Foto:</td>
      <td>
        <form method="post" id="formImagen" name="formImagen" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframeUpload">
          <input type="file" name="foto_registroFotografico" id="foto_registroFotografico" size="20" align="left" onChange="document.getElementById('formImagen').submit()">
          <input type="hidden" id="ejecutar" name="ejecutar" value="cargarImagen">
        </form>
        <iframe id="iframeUpload" name="iframeUpload" style="display:none"></iframe>
        <input type="hidden" id="nombre_imagen" name="nombre_imagen">
      </td>
    </tr>
    <tr>
      <td>Descripcion: </td>
      <td><textarea name="descripcion_foto" id="descripcion_foto" rows="3" cols="30"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="button" id="boton_registroFotografico" name="boton_registroFotografico" value="Subir Imagen" class="button" onClick="subirRegistroFotografico()"></td>
    </tr>
  </table>
  <br>
  <br>

  <div id="lista_registroFotografico"></div>


</div>

<!-- ****************************************** REGISTRO FOTOGRAFICO ************************************************ -->



<!-- ****************************************** CARGA FAMILIAR ****************************************************** -->
<div id="div_cargaFamiliar" style="display:none">



  <?
  $buscar_registros          = $_GET["busca"];
$idtrabajador_seleccionado = $_GET["id"]; //variable pasada para los datos del trabajador hasta que se escriba otra cedula
$idtrabajador_seleccion2   = $_GET["t"]; //variable pasada por la seleccion en la grilla para modificar o eliminar un familiar
$entro_buscar              = false;
$existen_registros         = 1; // switch para validar si hay datos a cargar en la grilla 0 existen 1 no existen

if ($idtrabajador_seleccionado != "" || $idtrabajador_seleccion2 != "") {
  if ($idtrabajador_seleccionado != "") {$idtrabajador_buscar = $idtrabajador_seleccionado;} else { $idtrabajador_buscar = $idtrabajador_seleccion2;}
  $sql_validar_id = mysql_query("select * from trabajador
    where idtrabajador=" . $idtrabajador_buscar
    , $conexion_db);
  $regtrabajador          = mysql_fetch_assoc($sql_validar_id);
  $c                      = $regtrabajador["cedula"];
  $id_trabajador          = $regtrabajador["idtrabajador"];
  $registros_grilla_carga = mysql_query("select * from carga_familiar where status='a' and idtrabajador=" . $id_trabajador, $conexion_db);
  if (mysql_num_rows($registros_grilla_carga) > 0) {
    $existen_registros = 0;
  }

}

$nacionalidad = mysql_query("select * from nacionalidad
  where status='a'"
    , $conexion_db); // registros para llenar el combo nacionalidad

$parentezco = mysql_query("select * from parentezco
  where status='a'"
    , $conexion_db); // registros para llenar el combo nacionalidad

//if (isset($_POST["validar_cedula"])){
if ($_REQUEST["cedula_buscar"] != "") {
  $cedula_validar     = $_REQUEST["cedula_buscar"];
  $sql_validar_cedula = mysql_query("select * from trabajador
    where cedula=" . $cedula_validar . " and status='a'"
    , $conexion_db);
  if (mysql_num_rows($sql_validar_cedula) > 0) {
        //header("location:error_rrhh.php?err=9&modo=0&busca=0");
    $regtrabajador          = mysql_fetch_assoc($sql_validar_cedula);
    $c                      = $regtrabajador["cedula"];
    $id_trabajador          = $regtrabajador["idtrabajador"];
    $registros_grilla_carga = mysql_query("select * from carga_familiar where status='a' and idtrabajador=" . $id_trabajador, $conexion_db);
    if (mysql_num_rows($registros_grilla_carga) > 0) {
      $existen_registros = 0;
    }
  }
}

$result           = mysql_query("select * from carga_familiar where idcarga_familiar = '" . $_GET['c'] . "'", $conexion_db);
$row              = mysql_fetch_assoc($result);
$idcarga_familiar = $_GET['c'];

?>









<br>
<h4 align=center>Carga Familiar</h4>
<h2 class="sqlmVersion"></h2>
<br>
<form method='POST' enctype="multipart/form-data" name='formulario_cargaFamiliar'>
  <input type="hidden" name="idcarga_familiar" maxlength="25" size="25" id="idcarga_familiar">
  <table align=center cellpadding=2 cellspacing=0 width="80%">
    <tr>
      <td align='right' class='viewPropTitle'>Apellidos:</td>
      <td class=''><input type="text" name="apellidos_cargaFamiliar" maxlength="45" size="45" id="apellidos_cargaFamiliar"></td>
      <td align='right' class='viewPropTitle'>Nombres:</td>
      <td class=''><input type="text" name="nombres_cargaFamiliar" maxlength="45" size="45" id="nombres_cargaFamiliar"></td>
    </tr>
    <tr>
      <td align='right' class='viewPropTitle'>Nacionalidad:</td>
      <td class='viewProp'>
        <label><select name="idnacionalidad_cargaFamiliar" id="idnacionalidad_cargaFamiliar" style="width:55%">
          <option>&nbsp;</option>
          <?php
          while ($regnacionalidad = mysql_fetch_array($nacionalidad)) {?>
          <option value="<?=$regnacionalidad["idnacionalidad"]?>"><?=$regnacionalidad["indicador"] . " " . $regnacionalidad["denominacion"];?>
          </option>
          <?}?>
        </select> </label>
        <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=9&accion=60&pop=si','agregar nacionalidad','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">       </td>
        <td align='right' class='viewPropTitle'>C&eacute;dula:</td>
        <td class=''><input type="text" id="cedula_cargaFamiliar" name="cedula_cargaFamilair" maxlength="12" size="12" onKeyPress="javascript:return solonumeros(event)"></td>
      </tr>
      <tr>
        <td align='right' class='viewPropTitle'>Fecha de Nacimiento:</td>
        <td>
          <input name="fecha_nacimiento_cargaFamiliar" type="text" id="fecha_nacimiento_cargaFamiliar" size="13" maxlength="10">
          <img src="imagenes/jscalendar0.gif" name="f_trigger_cf" width="16" height="16" id="f_trigger_cf" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "fecha_nacimiento_cargaFamiliar",
              button        : "f_trigger_cf",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script>       </td>
          <td align='right' class='viewPropTitle'>Sexo:</td>
          <td class=''>
            <select name="sexo_cargaFamiliar" id="sexo_cargaFamiliar" style="width:15%">
              <option VALUE=""></option>
              <option VALUE="F">F</option>
              <option VALUE="M">M</option>
            </select> </td>
          </tr>
          <tr>
            <td align='right' class='viewPropTitle'>Parentesco:</td>
            <td class='viewProp'>
              <label><select name="idparentezco_cargaFamiliar" id="idparentezco_cargaFamiliar" style="width:55%">
                <option>&nbsp;</option>
                <?php
                while ($regparentezco = mysql_fetch_array($parentezco)) {?>
                <option  value="<?=$regparentezco["idparentezco"]?>"><?=$regparentezco["denominacion"];?> </option>
                <?}?>
              </select> </label>
              <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=26&pop=si','agregar parentezco','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">       </td>
              <td align='right' class='viewPropTitle'>Constancia:</td>
              <td><input type ="checkbox" name ="constancia_cargaFamiliar" id="constancia_cargaFamiliar"></td>
            </tr>
            <tr>
              <td align='right' class='viewPropTitle'>Direcci&oacute;n:</td>
              <td class='' colspan="3"><textarea name="direccion_cargaFamiliar" cols="125" id="direccion_cargaFamiliar"></textarea>       </td>
            </tr>
            <tr>
              <td align='right' class='viewPropTitle'>Telef&oacute;no: </td>
              <td class=''><input type="text" name="telefono_cargaFamiliar" maxlength="25" size="30" id="telefono_cargaFamiliar"></td>
              <td align='right' class='viewPropTitle'>Ocupaci&oacute;n: </td>
              <td class=''><input type="text" name="ocupacion_cargaFamiliar" maxlength="55" size="55" id="ocupacion_cargaFamiliar"></td>
            </tr>
          </form>
          <tr>
            <td align='right' class='viewPropTitle'>Foto:</td>
            <td class=''>
              <form method="post" name="formulario_foto_cargaFamiliar" id="formulario_foto_cargaFamiliar" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframe_fotocaragFamiliar">
                <input type="file" name="foto_cargaFamiliar" id="foto_cargaFamiliar" onChange="document.formulario_foto_cargaFamiliar.submit()">
                <input type="hidden" id="ejecutar" name="ejecutar" value="cargarFotoCargaFamiliar">
              </form>
              <input type="hidden" name="nombre_foto_cargaFamiliar" id="nombre_foto_cargaFamiliar">
              <iframe id="iframe_fotocaragFamiliar" name="iframe_fotocaragFamiliar" style="display:none"></iframe>
            </td>
            <td align='right' class='viewPropTitle'>&nbsp;</td>
            <td class=''>&nbsp;</td>
          </tr>

        </table>
        <br><br>
        <table align=center cellpadding=2 cellspacing=0>
          <tr>
            <td><input align=center class='button' name='boton_ingresar_cargaFamiliar' id="boton_ingresar_cargaFamiliar" type='button' value='Ingresar' onClick="ingresarCargaFamiliar()"></td>
            <td><input align=center class='button' name='boton_modificar_cargaFamiliar' id="boton_modificar_cargaFamiliar" type='button' value='Modificar' onClick="modificarCargaFamiliar()" style="display:none"></td>
            <td><input align=center class='button' name='eliminar' type='button' value='Eliminar' onClick="eliminarCargaFamiliar()" style="display:none"></td>
            <td></td>
          </tr>
        </table>


        <br><br>
        <h2 class="sqlmVersion"></h2>
        <div align="center" id="lista_cargaFamiliar">
          <table class="Main" cellpadding="0" cellspacing="0" width="80%">
            <tr>
              <td>
                <form name="grilla" action="" method="POST">
                  <table class="Browse" cellpadding="0" cellspacing="0" width="80%" align=center>
                    <thead>
                      <tr>
                        <!--<td class="Browse">&nbsp;</td>-->
                        <td align="center" class="Browse">Imagen</td>
                        <td align="center" class="Browse">C&eacute;dula</td>
                        <td align="center" class="Browse">Apellidos</td>
                        <td align="center" class="Browse">Nombres</td>
                        <td align="center" class="Browse" colspan="2">Acci&oacute;n</td>
                      </tr>
                    </thead>

                    <?php
                    if ($existen_registros == 0) {
                      while ($llenar_grilla = mysql_fetch_array($registros_grilla_carga)) {
                        ?>
                        <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                          <td align='center' class='Browse' width='20%'>
                            <a href="javascript:;" onClick="document.getElementById('imagen_<?=$llenar_grilla["idcarga_familiar"]?>').style.display= 'block'">Ver Imagen</a>
                            <div style=" position:absolute; background-color:#CCCCCC; display:none; width:600px; height:400px; border:#666666 solid 1px" id="imagen_<?=$llenar_grilla["idcarga_familiar"]?>">
                              <div align="right" style="cursor:pointer"><strong onClick="document.getElementById('imagen_<?=$llenar_grilla["idcarga_familiar"]?>').style.display= 'none'">Cerrar</strong></div>
                              <img src="modulos/rrhh/imagenes/carga_familiar/<?=$llenar_grilla["foto"]?>">
                            </div>
                          </td>
                          <td align='right' class='Browse' width='20%'>

                            <?
                            if ($llenar_grilla["cedula"] != "") {
                              echo $llenar_grilla["cedula"];
                            } else {
                              echo "<center><strong>Sin Nro. de Cedula</strong></center>";
                            }
                            ?></td>
                            <?
                            echo "<td align='left' class='Browse'>" . $llenar_grilla["apellidos"] . "</td>";
                            echo "<td align='left' class='Browse'>" . $llenar_grilla["nombres"] . "</td>";
                            $c = $llenar_grilla["idcarga_familiar"];
                            $t = $llenar_grilla["idtrabajador"];
                            if (in_array(168, $privilegios) == true) {
                              echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=168&c=$c&t=$t&busca=0' class='Browse'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>";
                            }
                            if (in_array(169, $privilegios) == true) {
                              echo "<td align='center' class='Browse' width='7%'><a href='principal.php?modulo=1&accion=169&&c=$c&t=$t&busca=0' class='Browse'><img src='imagenes/delete.png' border='0' alt='Borrar' title='Borrar'></a></td>";
                            }
                            echo "</tr>";
                          }
                        }
                        ?>
                      </table>
                    </form>
                  </td>
                </tr>
              </table>
            </div>
            <br>


          </div>
          <!-- ****************************************** CARGA FAMILIAR ****************************************************** -->




          <!-- ******************************************** ESTUDIOS REALIZADOS **********************************************-->
          <div id="div_estudiosRealizados" style="display:none">



            <br>
            <h4 align=center>Instrucci&oacute;n Academica</h4>
            <h2 class="sqlmVersion"></h2>
            <br>


            <?php

            $profesion = mysql_query("select * from profesion
              where status='a'"
    , $conexion_db); // registros para llenar el combo profesion

            $mension = mysql_query("select * from mension
              where status='a'"
    , $conexion_db); // registros para llenar el combo mension

            $nivel_estudio = mysql_query("select * from nivel_estudio
              where status='a'"
              , $conexion_db);

              ?>
              <form action='' method='POST' enctype="multipart/form-data" name='instruccion_academica' id="instruccion_academica">
                <input type="hidden" name="idinstruccion_academica" id="idinstruccion_academica" value="">

                <table align=center cellpadding=2 cellspacing=0 width="80%">
                  <tr>
                    <td width="15%" align='right' class='viewPropTitle'>Nivel de Estudio:</td>
                    <td width="34%" class='viewProp'><table width="100%" border="0" cellspacing="0" cellpadding="4">
                      <tr>
                        <td width="15%">

                          <select name="idnivel_estudio" id="idnivel_estudio">
                            <option value=0>&nbsp;</option>
                            <?php
                            while ($regnivel_estudio = mysql_fetch_array($nivel_estudio)) {
                              ?>
                              <option value="<?=$regnivel_estudio["idnivel_estudio"]?>"> <?php echo $regnivel_estudio["denominacion"]; ?> </option>
                              <?
                            }
                            ?>
                          </select></td>
                          <td width="85%" align="left"><img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=13&pop=si','agregar estudios','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer"></td>
                        </tr>
                      </table></td>
                      <td width="11%" align='right' class='viewPropTitle'>Profesi&oacute;n:</td>
                      <td width="40%" class='viewProp'><table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                          <td width="13%"><select name="idprofesion" id="idprofesion">
                            <option value=0>&nbsp;</option>
                            <?php
                            while ($regprofesion = mysql_fetch_array($profesion)) {
                              ?>
                              <option value="<?=$regprofesion["idprofesion"]?>"> <?php echo $regprofesion["abreviatura"] . " " . $regprofesion["denominacion"]; ?> </option>
                              <?
                            }
                            ?>
                          </select></td>
                          <td width="87%"><img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=14&pop=si','agregar estudios','resisable = no, scrollbars = yes, width=900, height = 500')"></td>
                        </tr>
                      </table></td>
                    </tr>

                    <td align='right' class='viewPropTitle'>Mensi&oacute;n:</td>
                    <td class='viewProp' colspan="3"><button name="agregar_mension" type="button" style="background-color:white;border-style:none;cursor:pointer;" onClick=""></button>
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                          <td width="6%"><select name="idmension" id="idmension" >
                            <option value=0>&nbsp;</option>
                            <?php
                            while ($regmension = mysql_fetch_array($mension)) {
                              ?>
                              <option value="<?=$regmension["idmension"]?>"> <?php echo $regmension["denominacion"]; ?> </option>
                              <?
                            }
                            ?>
                          </select></td>
                          <td width="94%"><img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=15&pop=si','agregar estudios','resisable = no, scrollbars = yes, width=900, height = 500')"></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align='right' class='viewPropTitle'>Instituci&oacute;n:</td>
                      <td class='' colspan="3">
                        <input type="text" name="institucion" maxlength="150" size="100" id="institucion"></td>
                      </tr>
                    </table>

                    <table align=center cellpadding=2 cellspacing=0 width="80%">
                      <tr>
                        <td width="15%" align='right' class='viewPropTitle'>A&ntilde;o de egreso:</td>
                        <td width="8%" class=''><input type="text" name="anio_egreso" maxlength="4" size="5" id="anio_egreso"></td>
                        <td width="10%" align='right' class='viewPropTitle'>Constancia:</td>
                        <td width="4%">
                          <input type ="checkbox" name ="constancia" id="constancia" value="si" >
                        </td>
                        <td width="15%" align='right' class='viewPropTitle'>Profesi&oacute;n Actual?:</td>
                        <td width="48%">
                          <input type ="checkbox" name ="profesion_actual" value="si" id="profesion_actual">
                        </td>
                      </tr>
                    </table>
                    <table align=center cellpadding=2 cellspacing=0 width="80%">
                      <tr>
                        <td align='right' class='viewPropTitle'>Observaciones:</td>
                        <td class=''><input type="text" id="observaciones" name="observaciones" maxlength="150" size="150"></td>
                      </tr>
                      <input type="hidden" name="nombre_foto_instruccionAcademica" id="nombre_foto_instruccionAcademica">
                    </form>
                    <tr>
                      <td width="134" align='right' class='viewPropTitle'>Foto:</td>
                      <td width="779" class=''>
                       <form method="post" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" name="formulario_foto_instruccionAcademica" id="formulario_foto_instruccionAcademica" target="iframe_instruccionAcademica">
                        <input type="file" name="foto_instruccionAcademica" id="foto_instruccionAcademica" onChange="document.getElementById('formulario_foto_instruccionAcademica').submit()">
                        <input type="hidden" name="ejecutar" id="ejecutar" value="cargarFotoInstruccionAcademica">
                      </form>

                      <iframe id="iframe_instruccionAcademica" name="iframe_instruccionAcademica" style="display:none"></iframe>
                    </td>
                  </tr>
                </table>
                <br><br>
                <table align=center cellpadding=2 cellspacing=0>
                  <tr>
                    <td align="center">
                      <table align="center">
                        <tr>
                          <td>
                            <input align=center class='button' name='boton_ingresar_instruccionAcademica' id="boton_ingresar_instruccionAcademica" type='button' value='Ingresar' onClick="ingresarInstruccionAcademica()">
                          </td>
                          <td>
                            <input align=center class='button' name='boton_modificar_instruccionAcademica' id="boton_modificar_instruccionAcademica" type='button' value='Modificar' onClick="modificarInstruccionAcademica()" style="display:none">
                          </td>
                          <td>
                            <input align=center class='button' name='eliminar' type='button' value='Eliminar' onClick="eliminarInstruccionAcademica()" style="display:none">
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </form>
              <br><br>
              <h2 class="sqlmVersion"></h2>
              <div align="center" id="lista_instruccionAcademica"></div>
              <br>




            </div>
            <!-- ********************************************* ESTUDIOS REALIZADOS *********************************************-->


            <!-- ********************************************* EXPERIENCIA LABORAL *********************************************-->

            <div id="div_experienciaLaboral" style="display:none">
              <br>
              <h4 align="center">EXPERIENCIA LABORAL</h4><br>
              <h2 class="sqlmVersion"></h2>
              <br>
              <form method="post" name="frm_p" id="frm_p" action="">

                <input name="idexperiencia_laboral" type="hidden" readonly id="idexperiencia_laboral" value=""/>


                <table width="70%" border="0" align="center">
                  <tr>
                    <td width="147" align='right' class='viewPropTitle'>Empresa:</td>
                    <td colspan="3" ><input type="text" size="70" name="empresa" id="empresa" value=""/></td>
                  </tr>
                  <tr>
                    <td align='right' class='viewPropTitle'>Desde:</td>
                    <td width="120">

                      <input type="text" name="desde_exp" id="desde_exp" size="13" readonly value=""/>
                      <img src="imagenes/jscalendar0.gif" name="desde_cal" width="16" height="16" id="desde_cal" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                      <script type="text/javascript">
                        Calendar.setup({
                          inputField    : "desde_exp",
                          button        : "desde_cal",
                          align         : "Tr",
                          ifFormat      : "%Y-%m-%d"
                        });
                      </script>
                      <td width="45" align='right' class='viewPropTitle'>Hasta:</td>
                      <td width="248"><input type="text" name="hasta_exp" id="hasta_exp" size="13" readonly value="" onChange=""/>
                        <img src="imagenes/jscalendar0.gif" name="hasta_cal" width="16" height="16" id="hasta_cal" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                        <script type="text/javascript">
                          Calendar.setup({
                            inputField    : "hasta_exp",
                            button        : "hasta_cal",
                            align         : "Tr",
                            ifFormat      : "%Y-%m-%d"
                          });
                        </script>    </td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Tiempo de servicio:</td>
                        <td colspan="3"><input type="text" size="70" value="" id="tiempo_srv" name="tiempo_srv"/></td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Motivo de salida:</td>
                        <td colspan="3"><input type="text" size="70" name="motivo" id="motivo" value=""/></td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>&Uacute;ltimo Cargo:</td>
                        <td colspan="3"> <input type="text" size="70" name="ultimo_cargo" id="ultimo_cargo" value="" /> </td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Direcci&oacute;n de la empresa:</td>
                        <td colspan="3"><textarea cols="67" rows="5" name="direccion_empresa" id="direccion_empresa" ></textarea></td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Telefono de la empresa:</td>
                        <td><input type="text" size="15" name="telefono" id="telefono" value="" /></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Observaciones:</td>
                        <td colspan="3"><textarea cols="67" rows="5" name="observaciones" id="observaciones" ></textarea></td>
                      </tr>
                    </form>
                    <tr>
                      <td align='right' class='viewPropTitle'>Constancia de Trabajo:</td>
                      <td colspan="3">
                        <form method="post" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframe_experienciaLaboral" id="formulario_foto_experienciaLaboral" name="formulario_foto_experienciaLaboral">
                          <input type="file" name="foto_experienciaLaboral" id="foto_experienciaLaboral" onChange="document.getElementById('formulario_foto_experienciaLaboral').submit()">
                          <input type="hidden" name="nombre_foto_experienciaLaboral" id="nombre_foto_experienciaLaboral">
                          <input type="hidden" name="ejecutar" id="ejecutar" value="cargarFotoExperienciaLaboral">
                        </form>
                        <iframe name="iframe_experienciaLaboral" id="iframe_experienciaLaboral" style="display:none"></iframe>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4" align="center">
                        <table>
                          <tr>
                            <td>
                              <input type="button" value="Ingresar" class="button" name="boton_ingresar_experienciaLaboral" id="boton_ingresar_experienciaLaboral" onClick="ingresarExperienciaLaboral()"/></td>
                              <td><input type="button" value="Modificar" class="button" name="boton_modificar_experienciaLaboral" id="boton_modificar_experienciaLaboral" onClick="modificarExperienciaLaboral()" style="display:none"/></td>
                              <td><input type="button" value="Eliminar" class="button" name="boton_eliminar" id="boton_eliminar" style="display:none"/></td>


                            </tr>
                          </table>


                        </td>
                      </tr>
                    </table>

                    <div id="lista_experienciaLaboral"></div>
                  </div>
                  <br>


                  <!-- ********************************************* EXPERIENCIA LABORAL *********************************************-->



                  <!-- ********************************************* MOVIMIENTOS *********************************************-->
                  <div id="div_movimientos" style="display:none">
                    <input type="hidden" name="fecha_ingreso_movimientos" id="fecha_ingreso_movimientos">
                    <br>
                    <h4 align=center>Movimientos de Personal</h4>
                    <h2 class="sqlmVersion"></h2>
                    <p>&nbsp;</p>


                    <table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
                      <tr>
                        <td align='right' class='viewPropTitle'>Fecha del Movimiento</td>
                        <td>

                          <table width="42%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="41%"><input name="fecha_movimiento_movimientos" type="text" id="fecha_movimiento_movimientos" size="12" value="<?=date("Y-m-d")?>"></td>
                              <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_c" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                <script type="text/javascript">
                                  Calendar.setup({
                                    inputField    : "fecha_movimiento_movimientos",
                                    button        : "f_trigger_c",
                                    align         : "Tr",
                                    ifFormat      : "%Y-%m-%d"
                                  });
                                </script>          </td>
                              </tr>
                            </table></td>
                            <td align='right' class='viewPropTitle'>Tipo de Movimiento</td>
                            <td><label>
                              <?
                              $sql_tipos_movimientos = mysql_query("select * from tipo_movimiento_personal");
                              ?>
                              <select name="tipo_movimiento_movimientos" id="tipo_movimiento_movimientos">
                                <option value="0" onClick="desaparecerCampos()">.:: Seleccione ::.</option>
                                <?
                                while ($bus_tipos_movimientos = mysql_fetch_array($sql_tipos_movimientos)) {
                                  ?>
                                  <option value="<?=$bus_tipos_movimientos["idtipo_movimiento"]?>" onClick="seleccionarIngresar('<?=$bus_tipos_movimientos["relacion_laboral"]?>', '<?=$bus_tipos_movimientos["goce_sueldo"]?>', '<?=$bus_tipos_movimientos["afecta_cargo"]?>', '<?=$bus_tipos_movimientos["afecta_ubicacion"]?>', '<?=$bus_tipos_movimientos["afecta_tiempo"]?>', '<?=$bus_tipos_movimientos["afecta_centro_costo"]?>', '<?=$bus_tipos_movimientos["afecta_ficha"]?>')"><?=$bus_tipos_movimientos["denominacion"]?></option>
                                  <?
                                }
                                ?>
                              </select>
                            </label></td>
                          </tr>
                          <tr>
                            <td align='right' class='viewPropTitle'>Justificacion</td>
                            <td colspan="3"><label>
                              <textarea name="justificacion_movimientos" cols="80" rows="5" id="justificacion_movimientos"></textarea>
                            </label></td>
                          </tr>
                          <tr>
                            <td align='right' class='viewPropTitle'>
                              <div id="celda_centro_costo_movimientos" style="display:none">Centro de Costo</div>
                            </td>
                            <td colspan="3">
                              <?
                              $sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
                                un.denominacion,
                                ct.codigo
                                from
                                niveles_organizacionales no,
                                unidad_ejecutora un,
                                categoria_programatica ct
                                where
                                no.modulo='1'
                                and no.idcategoria_programatica != '0'
                                and ct.idcategoria_programatica = no.idcategoria_programatica
                                and ct.idunidad_ejecutora = un.idunidad_ejecutora
                                order by ct.codigo");

                                ?>
                                <div id="campo_centro_costo_movimientos" style="display:none">
                                  <select id="centro_costo_movimientos" name="centro_costo_movimientos">
                                    <option value="0">.:: Seleccione ::.</option>
                                    <?
                                    while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
                                      ?>
                                      <option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(<?=$bus_centro_costo["codigo"]?>)&nbsp;<?=$bus_centro_costo["denominacion"]?></option>
                                      <?
                                    }
                                    ?>
                                  </select>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align='right' class='viewPropTitle'>Causal</td>
                              <td>
                                <input type="text" name="causal_movimientos" id="causal_movimientos" />    </td>
                                <td align='right' class='viewPropTitle'>
                                  <div id="celda_nombre_fecha_egreso_movimientos" style="display:none">
                                    Fecha de Egreso:        </div>    </td>
                                    <td>
                                      <div id="celda_campo_fecha_egreso_movimientos" style="display:none">
                                        <table width="42%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="41%"><input name="fecha_egreso_movimientos" type="text" id="fecha_egreso_movimientos" size="12" /></td>
                                            <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_e" id="f_trigger_e" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                              <script type="text/javascript">
                                                Calendar.setup({
                                                  inputField    : "fecha_egreso_movimientos",
                                                  button        : "f_trigger_e",
                                                  align         : "Tr",
                                                  ifFormat      : "%Y-%m-%d"
                                                });
                                              </script>        </td>
                                            </tr>
                                          </table>
                                        </div>    </td>
                                      </tr>
                                      <tr>
                                        <td align='right' class='viewPropTitle'>
                                          <div id="celda_nombre_nuevo_cargo_movimientos" style="display:none">
                                            Cargo    </div>    </td>
                                            <td colspan="3">
                                              <div id="celda_campo_nuevo_cargo_movimientos" style="display:none">
                                                <?
                                                $sql_cargos = mysql_query("select * from cargos order by denominacion");
                                                ?>
                                                <select id="nuevo_cargo_movimientos" name="nuevo_cargo_movimientos">
                                                  <option value="0">.:: Seleccione ::.</option>
                                                  <?
                                                  while ($bus_cargos = mysql_fetch_array($sql_cargos)) {
                                                    ?>
                                                    <option value="<?=$bus_cargos["idcargo"]?>"><?=$bus_cargos["denominacion"]?></option>
                                                    <?
                                                  }
                                                  ?>
                                                </select>
                                              </div>    <label></label></td>
                                            </tr>
                                            <tr>
                                              <td align='right' class='viewPropTitle'>
                                                <div id="celda_nombre_nueva_ubicacion_funcional_movimientos" style="display:none">
                                                  Ubicacion Funcional:    </div>    </td>
                                                  <td colspan="3">
                                                    <div id="celda_campo_nueva_ubicacion_funcional_movimientos" style="display:none">
                                                      <?
                                                      $sql_ubicacion_funcional = mysql_query("select * from niveles_organizacionales where modulo = '1' order by codigo");
                                                      ?>
                                                      <select id="nueva_ubicacion_funcional_movimientos" name="nueva_ubicacion_funcional_movimientos">
                                                        <option value="0">.:: Seleccione ::.</option>
                                                        <?
                                                        while ($bus_ubicacion_funcional = mysql_fetch_array($sql_ubicacion_funcional)) {
                                                          ?>
                                                          <option value="<?=$bus_ubicacion_funcional["idniveles_organizacionales"]?>">(<?=$bus_ubicacion_funcional["codigo"]?>)&nbsp;<?=$bus_ubicacion_funcional["denominacion"]?>
                                                          </option>
                                                          <?
                                                        }
                                                        ?>
                                                      </select>
                                                    </div>      </td>
                                                  </tr>




                                                  <tr>
                                                    <td align='right' class='viewPropTitle'>
                                                      <div id="celda_ficha_movimientos" style="display:none">
                                                        Ficha:    </div>    </td>
                                                        <td colspan="3">
                                                          <div id="celda_campo_ficha_movimientos" style="display:none; float:left">
                                                            La Ficha Actual es: <span id="ficha_actual_movimientos" style="font-weight:bold"></span> Y la deseo cambiar por ->
                                                            <?
                                                            $sql_ficha = mysql_query("select * from nomenclatura_fichas");
                                                            ?>
                                                            <select id="ficha_movimientos" name="ficha_movimientos">
                                                              <option value="0">-</option>
                                                              <?
                                                              while ($bus_ficha = mysql_fetch_array($sql_ficha)) {
                                                                ?>
                                                                <option onClick="consultarFichaMovimientos(this.value)" value="<?=$bus_ficha["descripcion"]?>"><?=$bus_ficha["descripcion"]?>
                                                                </option>
                                                                <?
                                                              }
                                                              ?>
                                                            </select>
                                                            &nbsp;
                                                          </div>
                                                          <div id="nro_ficha_movimientos" style="font-weight:bold"></div>
                                                          <input type="hidden" name="campo_nro_ficha_movimientos" id="campo_nro_ficha_movimientos">
                                                        </td>
                                                      </tr>





                                                      <tr>
                                                        <td align='right' class='viewPropTitle'>
                                                          <div id="celda_nombre_fecha_reingreso_movimientos" style="display:none">
                                                            Fecha de Reingreso:    </div>    </td>
                                                            <td>
                                                              <div id="celda_campo_fecha_reingreso_movimientos" style="display:none">

                                                                <table width="42%" border="0" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                    <td width="41%"><input name="fecha_reingreso_movimientos" type="text" id="fecha_reingreso_movimientos" size="12"></td>
                                                                    <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_r" id="f_trigger_r" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                      <script type="text/javascript">
                                                                        Calendar.setup({
                                                                          inputField    : "fecha_reingreso",
                                                                          button        : "f_trigger_r",
                                                                          align         : "Tr",
                                                                          ifFormat      : "%Y-%m-%d"
                                                                        });
                                                                      </script>          </td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td align='right' class='viewPropTitle'>Desde</td>
                                                                <td><label>
                                                                </label>
                                                                <table width="42%" border="0" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                    <td width="41%"><input name="desde_movimientos" type="text" id="desde_movimientos" size="12" /></td>
                                                                    <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_d" id="f_trigger_d" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                      <script type="text/javascript">
                                                                        Calendar.setup({
                                                                          inputField    : "desde",
                                                                          button        : "f_trigger_d",
                                                                          align         : "Tr",
                                                                          ifFormat      : "%Y-%m-%d"
                                                                        });
                                                                      </script>          </td>
                                                                    </tr>
                                                                  </table>
                                                                  <label></label></td>
                                                                  <td align='right' class='viewPropTitle'>Hasta</td>
                                                                  <td><label>
                                                                  </label>
                                                                  <table width="42%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="41%"><input name="hasta_movimientos" type="text" id="hasta_movimientos" size="12" /></td>
                                                                      <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_h" id="f_trigger_h" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                        <script type="text/javascript">
                                                                          Calendar.setup({
                                                                            inputField    : "hasta_movimientos",
                                                                            button        : "f_trigger_h",
                                                                            align         : "Tr",
                                                                            ifFormat      : "%Y-%m-%d"
                                                                          });
                                                                        </script>          </td>
                                                                      </tr>
                                                                    </table>
                                                                    <label></label></td>
                                                                  </tr>
                                                                  <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                  </tr>
                                                                  <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td colspan="2"><table width="2%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                                      <tr>
                                                                        <td><label>
                                                                          <input type="submit" name="boton_ingresar_movimientos" id="boton_ingresar_movimientos" value="Ingresar" class="button" onClick="ingresarMovimiento()">
                                                                        </label></td>
                                                                        <td><label>
                                                                          <input type="submit" name="boton_editar_movimientos" id="boton_editar_movimientos" value="Modificar" class="button" style="display:none" onClick="modificarMovimiento(document.getElementById('idmovimiento').value)">
                                                                        </label></td>
                                                                        <td><label>
                                                                          <input type="submit" name="boton_eliminar_movimientos" id="boton_eliminar_movimientos" value="Eliminar" class="button" style="display:none">
                                                                        </label></td>
                                                                      </tr>
                                                                    </table></td>
                                                                    <td>&nbsp;</td>
                                                                  </tr>
                                                                </table>


                                                                <br />
                                                                <br />


                                                                <div id="listaMovimientos"></div>


                                                              </div>
                                                              <!-- ********************************************* MOVIMIENTOS *********************************************-->



                                                              <!-- ********************************************* PERMISOS *********************************************-->
                                                              <div id="div_permisos" style="display:none">



                                                                <h4 align=center>Hist&oacute;rico Permisos</h4>


                                                                <h2 class="sqlmVersion"></h2>
                                                                <br>
                                                                <input type="hidden" id="idhistorico_permisos" name="idhistorico_permisos">

                                                                <form name="form2_historico" id="form2_historico" method="post">
                                                                  <table width="70%" border="0" align="center">
                                                                    <tr>
                                                                      <td width="242" align="right" class='viewPropTitle'>Fecha De Solicitud:</td>
                                                                      <td colspan="2"><?
                                                                        list($fecha_solicitud, $hora) = split('[ ]', $fecha_solicitud);
                                                                        ?>
                                                                        <input type="text" name="fecha_solicitud_historico" id="fecha_solicitud_historico" size="13" readonly value="<?=$fecha_solicitud?>"/>
                                                                        <img src="imagenes/jscalendar0.gif" name="fecha_solicitud_cal" width="16" height="16" id="fecha_solicitud_cal" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                        <script type="text/javascript">
                                                                          Calendar.setup({
                                                                            inputField    : "fecha_solicitud_historico",
                                                                            button        : "fecha_solicitud_cal",
                                                                            align         : "Tr",
                                                                            ifFormat      : "%Y-%m-%d"
                                                                          });
                                                                        </script></td>
                                                                        <td width="173">&nbsp;</td>
                                                                        <td width="180">&nbsp;</td>
                                                                        <td width="3">&nbsp;</td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td class='viewPropTitle'><div align="right">Fecha De Inicio:</div></td>
                                                                        <td width="133"><?
                                                                          list($fecha_inicio, $hora) = split('[ ]', $fecha_inicio);
                                                                          ?>
                                                                          <input type="text" value="<?=$fecha_inicio?>" name="fecha_inicio_historico" id="fecha_inicio_historico" size="13" readonly onChange="validarFechas(document.getElementById('fecha_inicio_historico').value, document.getElementById('fecha_culminacion_historico').value) , totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value)"/>
                                                                          <img src="imagenes/jscalendar0.gif" name="fecha_inicio_cal" width="16" height="16" id="fecha_inicio_cal" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                          <script type="text/javascript">
                                                                            Calendar.setup({
                                                                              inputField    : "fecha_inicio_historico",
                                                                              button        : "fecha_inicio_cal",
                                                                              align         : "Tr",
                                                                              ifFormat      : "%Y-%m-%d"
                                                                            });
                                                                          </script></td>
                                                                          <td width="66" class='viewPropTitle'><div align="right">Hora :</div></td>
                                                                          <td colspan="2">
                                                                            <input type="text" size="13" name="hr_inicio_historico" id="hr_inicio_historico" title="Ejemplo: 07:45 am" maxlength="8" value="<?=$hr_inicio?>" onKeyUp="validarHoras(document.getElementById('hr_inicio_historico').value)" onChange="totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value)"/>
                                                                            <font size="1"><b>Ejemplo: 07:45 am</b></font></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td class='viewPropTitle'><div align="right">Fecha De Culminaci&oacute;n:</div></td>
                                                                            <td><?
                                                                              list($fecha_culminacion, $hora) = split('[ ]', $fecha_culminacion);
                                                                              ?>
                                                                              <input type="text" value="<?=$fecha_culminacion?>" name="fecha_culminacion_historico" id="fecha_culminacion_historico" size="13" readonly onChange="validarFechas(document.getElementById('fecha_inicio_historico').value, document.getElementById('fecha_culminacion_historico').value) , totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value)"/>
                                                                              <img src="imagenes/jscalendar0.gif" name="fecha_inicio_cal" width="16" height="16" id="fecha_culminacion_cal" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                              <script type="text/javascript">
                                                                                Calendar.setup({
                                                                                  inputField    : "fecha_culminacion_historico",
                                                                                  button        : "fecha_culminacion_cal",
                                                                                  align         : "Tr",
                                                                                  ifFormat      : "%Y-%m-%d"
                                                                                });
                                                                              </script></td>
                                                                              <td class='viewPropTitle'><div align="right">Hora :</div></td>
                                                                              <td colspan="2">
                                                                                <input type="text" size="13" name="hr_culminacion_historico" id="hr_culminacion_historico" value="<?=$hr_culminacion?>" maxlength="8" title="Ejemplo: 07:45 am" onKeyUp="validarHoras(document.getElementById('hr_culminacion_historico').value)" onChange="totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value)"/>
                                                                                <font size="1"><b>Ejemplo: 03:20 pm</b></font></td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td class='viewPropTitle'><div align="right">Tiempo Total:</div></td>
                                                                                <td colspan="3"><input value="<?=$tiempo_horas?>" type="text" size="50" name="tiempo_horas_historico" id="tiempo_horas_historico" readonly/>
                                                                                  <input type="hidden" id="tiempo_historico" size="50" onChange="totalHoras(document.getElementById('tiempo_historico').value, document.getElementById('hr_inicio_historico').value, document.getElementById('hr_culminacion_historico').value)" /></td>
                                                                                  <td>&nbsp;</td>
                                                                                </tr>
                                                                                <tr><?php //<input type="checkbox" value="1" name="desc_bono" id="desc_bono" onchange="validarDescuento(document.getElementById('desc_bono').value)"/>?>
                                                                                  <td class='viewPropTitle'><div align="right">Descuenta Bono Alimentario:</div></td>
                                                                                  <td colspan="2">
                                                                                    <?
                                                                                    if ($desc_bono == 1) {?>
                                                                                    <input type="checkbox" name="desc_bono_historico" id="desc_bono_historico" checked="checked" value="1" onClick="habilitaDeshabilita()">
                                                                                    <?} else {?>
                                                                                    <input type="checkbox" name="desc_bono_historico" id="desc_bono_historico" value="1" onClick="habilitaDeshabilita()" />
                                                                                    <?}
                                                                                    ?>      </td>
                                                                                    <td>&nbsp;</td>
                                                                                    <td>&nbsp;</td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td class='viewPropTitle'><div align="right">Motivo:</div></td>
                                                                                    <td colspan="4">
                                                                                      <textarea rows="5" cols="85" name="motivo_historico" id="motivo_historico"><?=$motivo?></textarea>
                                                                                    </td>
                                                                                    <td width="180">&nbsp;</td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td class='viewPropTitle'><div align="right">Justificado:</div></td>
                                                                                    <td colspan="2">
                                                                                      <?
                                                                                      if ($justificado1 == 1) {?>
                                                                                      <input type="checkbox" name="justificado1_historico" id="justificado1_historico" checked="checked"  value="1"/>
                                                                                      <?} else {?>
                                                                                      <input type="checkbox" value="1" name="justificado1_historico" id="justificado1_historico" />
                                                                                      <?}?>      </td>

                                                                                      <td class='viewPropTitle'><div align="right">Remunerado:</div></td>
                                                                                      <td>
                                                                                        <?
                                                                                        if ($remunerado1 == 1) {?>
                                                                                        <input type="checkbox" checked="checked" value="1" name="remunerado1_historico" id="remunerado1_historico" />
                                                                                        <?} else {?>
                                                                                        <input type="checkbox" value="1" name="remunerado1_historico" id="remunerado1_historico" />
                                                                                        <?}?>      </td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td class='viewPropTitle'><div align="right">Aprobado Por:</div></td>
                                                                                        <td colspan="2"><input type="text" size="30"  maxlength="30" name="aprobado_por_historico" id="aprobado_por_historico" value="<?=$aprobado_por?>"/></td>
                                                                                        <td class='viewPropTitle'><div align="right">C.I.:</div></td>
                                                                                        <td><input type="text" size="30"  maxlength="30" name="ci_aprobado_historico" id="ci_aprobado_historico" value="<?=$ci_aprobado?>"/></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td colspan="5">

                                                                                          <table align="center">
                                                                                            <tr>
                                                                                              <td>
                                                                                                <input type="button" id="boton_ingresar_permiso" name="boton_ingresar_permiso" value="Registrar Historico de Permiso" onClick="ingresarHistorico()" class="button">            </td>
                                                                                                <td><input type="button" id="boton_modificar_permiso" name="boton_modificar_permiso" value="Modificar Historico de Permiso" style="display:none" onClick="modificarHistorico()" class="button"></td>
                                                                                              </tr>
                                                                                            </table></td>
                                                                                          </tr>
                                                                                        </table>
                                                                                      </form>
                                                                                      <div id="grilla_historico" align="center"></div>
                                                                                      <?
                                                                                      if ($bandera == 1) {
    //echo "aqui";
    //echo $idtrabajador;
                                                                                        ?>
                                                                                        <script>
                                                                                         nuevaUrl='principal.php?accion=21&modulo=1&idtrabajador=<?=$idtrabajador?>&bandera_url=1'
                                                                                         nuevaWin='_self'
   nuevoTime=400 //Tiempo en milisegundos
   setTimeout("open(nuevaUrl,nuevaWin)",nuevoTime);
 </script>
 <!--header('refresh:2; url=http://www.tutores.org');-->
 <?
}
?>




</div>
<!-- ********************************************* PERMISOS *********************************************-->



<!-- ********************************************* VACACIONES *********************************************-->
<div id="div_vacaciones" style="display:none">



  <div id="error"></div>
  <h4 align=center>Hist&oacute;rico Vacaciones</h4>
  <h2 class="sqlmVersion"></h2>
  <p>&nbsp;</p>
  <input id="idhistorico_vacaciones" name="idhistorico_vacaciones" type="hidden">
  <table width="73%" border="0" align="center" cellspacing="4">
    <tr>
      <td width="168" align="right" class="viewPropTitle">Per&iacute;odo:</td>
      <td width="150"><input type="text" size="25" maxlength="9" name="periodo_vacaciones" id="periodo_vacaciones" onKeyUp="validarPeriodo_vacaciones(document.getElementById('periodo_vacaciones').value)"/></td>
      <td colspan="2"><p align="left"><font size="1"><b>Ejemplo: 2009-2010</b></font></p></td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">N&uacute;mero Memorandum:</td>
      <td><input type="text" size="25" id="numero_memorandum_vacaciones" name="numero_memorandum_vacaciones"/></td>
      <td width="163" align="right" class="viewPropTitle">Fecha Memorandum:</td>
      <td width="173"><input type="text" name="fecha_memorandum_vacaciones" id="fecha_memorandum_vacaciones" size="15" readonly/>
        <img src="imagenes/jscalendar0.gif" alt="" name="fecha_memorandum_cal_vacaciones" width="16" height="16" id="fecha_memorandum_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
          Calendar.setup({
            inputField    : "fecha_memorandum_vacaciones",
            button        : "fecha_memorandum_cal_vacaciones",
            align         : "Tr",
            ifFormat      : "%Y-%m-%d"
          });
        </script></td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Fecha Inicio Vacaci&oacute;n:</td>
        <td><input type="text" name="fecha_inicio_vacacion_vacaciones" id="fecha_inicio_vacacion_vacaciones" onChange="ajustarFechas()" size="15" readonly/>
          <img src="imagenes/jscalendar0.gif" name="fecha_inicio_vacacion_cal_vacaciones" width="16" height="16" id="fecha_inicio_vacacion_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "fecha_inicio_vacacion_vacaciones",
              button        : "fecha_inicio_vacacion_cal_vacaciones",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script></td>
          <td align="right" class="viewPropTitle">Fecha Culminaci&oacute;n:</td>
          <td><input type="text" name="fecha_culminacion_vacacion_vacaciones" id="fecha_culminacion_vacacion_vacaciones" onChange="ajustarFechas()" size="15" readonly/>
            <img src="imagenes/jscalendar0.gif" name="fecha_culminacion_vacacion_cal_vacaciones" width="16" height="16" id="fecha_culminacion_vacacion_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
            <script type="text/javascript">
              Calendar.setup({
                inputField    : "fecha_culminacion_vacacion_vacaciones",
                button        : "fecha_culminacion_vacacion_cal_vacaciones",
                align         : "Tr",
                ifFormat      : "%Y-%m-%d"
              });
            </script></td>
          </tr>
          <tr>
            <td align="right" class="viewPropTitle">Tiempo Disfrute:</td>
            <td><input type="text" name="tiempo_disfrute_vacaciones" id="tiempo_disfrute_vacaciones" size="15" readonly/></td>
            <td align="right" class="viewPropTitle">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="viewPropTitle">Fecha Inicio Disfrute:</td>
            <td><input type="text" name="fecha_inicio_disfrute_vacaciones" id="fecha_inicio_disfrute_vacaciones" onChange="ajustarFechas()" size="15" readonly/>
              <img src="imagenes/jscalendar0.gif" alt="" name="fecha_inicio_disfrute_cal_vacaciones" width="16" height="16" id="fecha_inicio_disfrute_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
              <script type="text/javascript">
                Calendar.setup({
                  inputField    : "fecha_inicio_disfrute_vacaciones",
                  button        : "fecha_inicio_disfrute_cal_vacaciones",
                  align         : "Tr",
                  ifFormat      : "%Y-%m-%d"
                });
              </script></td>
              <td align="right" class="viewPropTitle">Cantidad de Dias Feriados:</td>
              <td><input type="text" size="25" id="cantidad_feriados_vacaciones" name="cantidad_feriados_vacaciones" onKeyUp="ajustarFechas()" onClick="select()" value="0"/></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Fecha Reincorporaci&oacute;n:</td>
              <td><input type="text" name="fecha_reincorporacion_vacaciones" id="fecha_reincorporacion_vacaciones" onChange="ajustarFechas()" size="15" readonly/>
                <img src="imagenes/jscalendar0.gif" alt="" name="fecha_inicio_reincorporacion_cal_vacaciones" width="16" height="16" id="fecha_inicio_reincorporacion_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                  Calendar.setup({
                    inputField    : "fecha_reincorporacion_vacaciones",
                    button        : "fecha_inicio_reincorporacion_cal_vacaciones",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                  });
                </script></td>
                <td align="right" class="viewPropTitle">Fecha Reinc. Ajustada:</td>
                <td><input type="text" name="fecha_reincorporacion_ajustada_vacaciones" id="fecha_reincorporacion_ajustada_vacaciones" onChange="ajustarFechas()" size="15" readonly/>
                  <img src="imagenes/jscalendar0.gif" alt="" name="fecha_reincorporacion_ajustada_cal_vacaciones" width="16" height="16" id="fecha_reincorporacion_ajustada_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                  <script type="text/javascript">
                    Calendar.setup({
                      inputField    : "fecha_reincorporacion_ajustada_vacaciones",
                      button        : "fecha_reincorporacion_ajustada_cal_vacaciones",
                      align         : "Tr",
                      ifFormat      : "%Y-%m-%d"
                    });
                  </script></td>
                </tr>
                <tr>
                  <td align="right" class="viewPropTitle">Tiempo Pendiente Disfrute:</td>
                  <td><input type="text" size="25" name="tiempo_pendiente_disfrute_vacaciones" id="tiempo_pendiente_disfrute_vacaciones" readonly/></td>
                  <td align="right" class="viewPropTitle"><input type="hidden" name="oculto_dias_vacaciones" id="oculto_dias_vacaciones" /></td>
                  <td><input type="hidden" name="oculto_disfrutados_vacaciones" id="oculto_disfrutados_vacaciones" /></td>
                </tr>
                <tr>
                  <td align="right" class="viewPropTitle">Tiempo Pendiente Acumulado</td>
                  <td><label>
                    <input type="text" name="tiempo_pendiente_acumulado" id="tiempo_pendiente_acumulado" readonly>

                  </label>
                  <input type="hidden" name="tiempo_pendiente_acumulado_oculto" id="tiempo_pendiente_acumulado_oculto" value="0">
                </td>
                <td align="right" class="viewPropTitle">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="viewPropTitle">Dias Bonificaci&oacute;n:</td>
                <td><input type="text" size="25" id="dias_bonificacion_vacaciones" name="dias_bonificacion_vacaciones"/></td>
                <td align="right" class="viewPropTitle">Monto Bono Vacacional:</td>
                <td><input type="text" size="25" name="monto_bono_vacacional_vacaciones" id="monto_bono_vacacional_vacaciones"/></td>
              </tr>
              <tr>
                <td align="right" class="viewPropTitle">N&uacute;mero Orden de Pago:</td>
                <td><input type="text" size="25" name="numero_orden_pago_vacaciones" id="numero_orden_pago_vacaciones"/></td>
                <td align="right" class="viewPropTitle">Fecha Orden de Pago:</td>
                <td><input type="text" name="fecha_orden_pago_vacaciones" id="fecha_orden_pago_vacaciones" size="15" readonly/>
                  <img src="imagenes/jscalendar0.gif" alt="" name="fecha_orden_pago_cal_vacaciones" width="16" height="16" id="fecha_orden_pago_cal_vacaciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                  <script type="text/javascript">
                    Calendar.setup({
                      inputField    : "fecha_orden_pago_vacaciones",
                      button        : "fecha_orden_pago_cal_vacaciones",
                      align         : "Tr",
                      ifFormat      : "%Y-%m-%d"
                    });
                  </script></td>
                </tr>
                <tr>
                  <td align="right" class="viewPropTitle">Elaborado Por:</td>
                  <td><input type="text" name="elaborado_por_vacaciones" id="elaborado_por_vacaciones" size="25"/></td>
                  <td align="right" class="viewPropTitle">C.I.:</td>
                  <td><input type="text" id="ci_elaborado_vacaciones" name="ci_elaborado_vacaciones" size="25" /></td>
                </tr>
                <tr>
                  <td align="right" class="viewPropTitle">Aprobado Por:</td>
                  <td><input type="text" name="aprobado_por_vacaciones" id="aprobado_por_vacaciones" size="25"/></td>
                  <td align="right" class="viewPropTitle">C.I.:</td>
                  <td><input type="text" id="ci_aprobado_vacaciones" name="ci_aprobado_vacaciones" size="25" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4" align="center">

                    <table align="center">
                      <tr>
                        <td><input type="button" name="boton_ingresar_vacaciones" id="boton_ingresar_vacaciones" value="Ingresar Vacaciones" class="button" onClick="accion_vacaciones('ingresar_vacaciones')"></td>
                        <td><input type="button" name="boton_modificar_vacaciones" id="boton_modificar_vacaciones" value="Modificar Vacaciones" class="button" style="display:none" onClick="accion_vacaciones('modificar_vacaciones')"></td>
                      </tr>
                    </table>    </td>
                  </tr>
                </table>

                <div id="grilla_vacaciones">&nbsp;</div>
                <div id="datos_vacaciones">&nbsp;</div>



              </div>
              <!-- ********************************************* VACACIONES *********************************************-->



              <!-- ********************************************* UTILIDADES *********************************************-->
              <div id="div_utilidades" style="display:none">utilidades</div>
              <!-- ********************************************* UTILIDADES *********************************************-->

              <script>
               function abrirCerrarAntiguedadIntereses(){
                if(document.getElementById('id_interes_mas_antiguedad').style.display == 'none'){
                  document.getElementById('id_interes_mas_antiguedad').style.display = 'block'
                  document.getElementById('td_signo_mas_antiguedad').innerHTML = "-";
                }else{
                  document.getElementById('id_interes_mas_antiguedad').style.display = 'none';
                  document.getElementById('td_signo_mas_antiguedad').innerHTML = "+";
                }
              }

              function abrirCerrarBonosVacaciones(){
                if(document.getElementById('id_interes_mas_bono_vacacional').style.display == 'none'){
                  document.getElementById('id_interes_mas_bono_vacacional').style.display = 'block'
                  document.getElementById('td_signo_mas_bono_vacacional').innerHTML = "-";
                }else{
                  document.getElementById('id_interes_mas_bono_vacacional').style.display = 'none';
                  document.getElementById('td_signo_mas_bono_vacacional').innerHTML = "+";
                }
              }

              function abrirCerrarDeducciones(){
                if(document.getElementById('id_interes_mas_deducciones').style.display == 'none'){
                  document.getElementById('id_interes_mas_deducciones').style.display = 'block'
                  document.getElementById('td_signo_mas_deducciones').innerHTML = "-";
                }else{
                  document.getElementById('id_interes_mas_deducciones').style.display = 'none';
                  document.getElementById('td_signo_mas_deducciones').innerHTML = "+";
                }
              }

              function abrirCerrarAguinaldos(){
                if(document.getElementById('id_interes_mas_aguinaldos').style.display == 'none'){
                  document.getElementById('id_interes_mas_aguinaldos').style.display = 'block'
                  document.getElementById('td_signo_mas_aguinaldos').innerHTML = "-";
                }else{
                  document.getElementById('id_interes_mas_aguinaldos').style.display = 'none';
                  document.getElementById('td_signo_mas_aguinaldos').innerHTML = "+";
                }
              }
            </script>


            <!-- ********************************************* PRESTACIONES SOCIALES *********************************************-->
            <div id="div_prestacionesSociales" style="display:none">
              <table>
                <tr>
                  <td width="25%">
                    <input name="fecha_ingreso_prestaciones" type="hidden" id="fecha_ingreso_prestaciones">
                    <div style="height: 105px;" class="div"
                    title="Mediante esta opción podra actualizar los datos de la tabla de prestaciones desde un archivo de hoja de calculo.&#013&#013Este proceso tomara los datos de la hoja de calculo y los actualizara en la base de datos.&#013&#013Descargue el modelo de archivo de carga masiva y el instructivo para realizar el procedimiento.&#013">
                      <div id='error_tipo' style="display: block;"><strong>Importar desde archivo</strong></div>
                      <table align="center" width="100%">
                        <tr>
                          <td>
                            <form method="post" id="formImportar" name="formImportar" enctype="multipart/form-data"
                            action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframeUploadImportar">
                            <input style="font-size: 10" type="file" name="archivo_importar_prestaciones" id="archivo_importar_prestaciones"
                            size="15" align="left"
                            onChange="document.getElementById('formImportar').submit()">
                            <input type="hidden" id="ejecutar" name="ejecutar" value="cargarImportar">
                          </form>
                          <iframe id="iframeUploadImportar" name="iframeUploadImportar" style="display:none"></iframe>
                          <input type="hidden" id="nombre_importar" name="nombre_importar">
                          <input type="hidden" id="tipo_importar" name="tipo_importar">
                          <input type="button" class="button" name="botonOrdenar" value="Importar" onClick="importarPrestaciones();" />
                        </td>
                      </tr>
                    </table>

                    <a href="modulos/rrhh/documentos/carga_prestaciones.xls" style="font-size: 10"><strong>Descargar modelo de archivo carga masiva</strong></a>
                    <br>
                    <a href="modulos/rrhh/documentos/instructivo_prestaciones.pdf" download="instructivo_prestaciones.pdf" style="font-size: 10"><strong>Descargar instrucciones para carga masiva</strong></a>
                  </div>
                </td>
                <td width="25%">
                  <div style="height: 105px;" class="div"
                  title="Mediante esta opción podra actualizar los datos de la tabla de prestaciones desde las tablas de prestaciones de años anteriores.&#013&#013Este proceso tomara los datos de la base de datos del año seleccionado y actualizara la del periodo que se tiene de trabajo actual.&#013&#013De esta manera puede actualizar datos que se encuentren correctos en años anteriores y que no se actualizarón de forma correcta en el nuevo año fiscal.&#013">
                    <div id='error_tipo' style="display: block;"><strong>Importar desde años anteriores</strong></div>
                    <table align="center" width="100%">
                      <tr>
                        <td>
                          <select id='anios_importar'>
                            <option>..: Seleccione :..</option>
                            <?php
                            $sql_anios = mysql_query("select * from gestion_configuracion_general.anios ORDER BY anio ASC")or die(mysql_error());
                            while($reg_anios = mysql_fetch_array($sql_anios)){
                              if($reg_anios["anio"] != $_SESSION['anio_fiscal']){
                                ?>
                                <option value="<?=$reg_anios["anio"]?>"><?=$reg_anios["anio"]?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="button" class="button" name="botonOrdenar" value="Importar" onClick="importarPrestacionesAnios();" />
                        </td>
                      </tr>
                      <div id='error_tipo_anio' style="display: none;"><strong></strong><i></i></div>
                    </table>
                  </div>
                </td>
                <td width="25%">
                  <div style="height: 105px;" class="div"
                  title="Mediante esta opción podra actualizar los datos de la tabla de prestaciones desde las nominas calculadas en el periodo que se encuentra activo.&#013&#013Solo se mostraran las nominas que se han procesado en las cuales se encuentra el trabajador que tiene seleccionado &#013&#013Es un proceso donde se van acumulando los montos calculados en nomina a los ya existentes en la tabla de prestaciones, si desea resetear los campos de la tabla de prestaciones, coloquese sobre la celda y agregue el valor 0 a cada una (Sueldo, Otros Complementos, Bono Vacacional y Bono Fin de Año) &#013">
                    <div id='error_tipo' style="display: block;"><strong>Actualizar desde Nominas</strong></div>
                    <table align="center" width="100%">
                      <tr>
                        <td>
                          <select id='select_tipo_nomina' style="font-size: 9">

                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <select id='select_periodo_nomina'>
                            <option>..: Seleccione Periodo :..</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="button" class="button" name="botonOrdenar" value="Actualizar" onClick="importarPrestacionesNomina();" />
                        </td>
                      </tr>
                      <div id='error_tipo_nomina' style="display: none;"><strong></strong><i></i></div>
                    </table>
                  </div>
                </td>
                <td>
                  <div style="height: 105px;" class="div">
                    <table align="right" width="100%">
                      <tr>
                        <td align="right">Fecha de Ingreso:</td>
                        <td id="div_fecha_ingreso_prestaciones" colspan="2"></td>
                      </tr>
                      <tr>
                        <td align="right" style="font-size: 10;">Prestaciones Acumuladas</td>
                        <td id="total_prestaciones_acumuladas" style="text-align:right; color:#000; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold"></td>
                        <td align="left"><strong>Bs.</strong></td>
                      </tr>
                      <tr>
                        <td align="right" style="font-size: 10;">Interes Acumulado</td>
                        <td id="total_interes_acumulado" style="text-align:right; color:#000; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold"></td>
                        <td align="left"><strong>Bs.</strong></td>
                      </tr>
                      <tr>
                        <td align="right" style="font-size: 10;">Prestaciones + Interes Acumulado</td>
                        <td id="total_prestaciones_interes_acumulado" style="text-align:right; color:#000; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold"></td>
                        <td align="left"><strong>Bs.</strong></td>
                      </tr>
                      <tr>
                        <td align="right" style="font-size: 10;">Total a pagar</td>
                        <td id="total_a_pagar" style="text-align:right; color:#900; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold"></td>
                        <td align="left"><strong>Bs.</strong></td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>

            <table align="center" width="100%" style="color:#FFF; background-color:#09F;font-weight:bold">
              <tr>
                <td style="color:#FFF">Antiguedad m&aacute;s Intereses</td>
                <td align="right" onClick="abrirCerrarAntiguedadIntereses()" style="cursor:pointer; color:#FFF" id="td_signo_mas_antiguedad">-</td>
              </tr>
            </table>


            <div id="id_interes_mas_antiguedad" style="display:block">
              <table align="center" width="90%">
                <tr>
                  <td colspan="2" align="right">Exportar a Excel: <input type="text" name="archivo" id="archivo"/>
                    <input type="button" class="button" name="botonOrdenar" value="Exportar" onClick="exportarPrestaciones();" />
                  </td>
                </tr>
              </table>
              <div id="lista_prestaciones"></div>
            </div>

            <br>

            <?php
/*
    <table align="center" width="100%" style="background-color:#09F; color:#FFF; font-weight:bold">
        <tr>
            <td style="color:#FFF">Vacacionales Vencidas</td>
            <td align="right" onClick="abrirCerrarBonosVacaciones()" style="cursor:pointer; color:#FFF" id="td_signo_mas_bono_vacacional">+</td>
        </tr>
    </table>



    <div id="id_interes_mas_bono_vacacional" style="display:none">
    <table align="center" width="50%">
        <tr>
          <td align="right" class='viewPropTitle'>Tipo</td>
            <td>
            <select name="tipo_vacaciones_vencidas" id="tipo_vacaciones_vencidas">
                 <option value="0">.:: Seleccione ::.</option>
                <option value="disfrute">Disfrute</option>
                <option value="bono">Bono</option>
            </select>
            </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Periodo</td>
          <td id="div_anios_vacaciones_vencidas">
            <select name="periodo_vacaciones_vencidas" id="periodo_vacaciones_vencidas">
               <option value="0">.:: Seleccione ::.</option>
        <?
  for ($i = 1997; $i < date("Y"); $i++) {
    ?>
            <option><?=$i . "-" . ($i + 1)?></option>
          <?
  }
  ?>

            </select>

            </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Dias</td>
            <td><input type="text" name="dias_vacaciones_vencidas" id="dias_vacaciones_vencidas"></td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Sueldo Base de Calculo</td>
            <td><input type="text" name="sueldo_vacaciones_vencidas" id="sueldo_vacaciones_vencidas"></td>
        </tr>
        <tr>
                <td colspan="2" align="center">
                    <table>
                        <tr>
                            <td><input type="button" name="boton_ingresar_bono_vacacional" id="boton_ingresar_bono_vacacional" class="button" value="Ingresar" onClick="ingresarVacacionesVencidas()"></td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>


    <div id="lista_vacaciones_vencidas"></div>

    </div>



    <br>


    <table align="center" width="100%" style="background-color:#09F; color:#FFF; font-weight:bold">
        <tr>
            <td style="color:#FFF">Aguinaldo y otras bonificaciones</td>
            <td align="right" onClick="abrirCerrarAguinaldos()" style="cursor:pointer; color:#FFF" id="td_signo_mas_aguinaldos">+</td>
        </tr>
    </table>



    <div id="id_interes_mas_aguinaldos" style="display:none">
    <table align="center" width="50%">
        <tr>
          <td align="right" class='viewPropTitle'>Tipo</td>
            <td>
            <input type="text" name="tipo_aguinaldos" id="tipo_aguinaldos">
            </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Periodo</td>
          <td id="div_anios_aguinaldos">
            <select name="periodos_aguinaldos" id="periodos_aguinaldos">
               <option value="0">.:: Seleccione ::.</option>
        <?
for ($i = 1997; $i < date("Y"); $i++) {
    ?>
            <option><?=$i . "-" . ($i + 1)?></option>
          <?
}
?>

            </select>

            </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Dias</td>
            <td><input type="text" name="dias_aguinaldos" id="dias_aguinaldos"></td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Sueldo Base de Calculo</td>
            <td><input type="text" name="sueldo_aguinaldos" id="sueldo_aguinaldos"></td>
        </tr>
        <tr>
                <td colspan="2" align="center">
                    <table>
                        <tr>
                            <td><input type="button" name="boton_ingresar_aguinaldos" id="boton_ingresar_aguinaldos" class="button" value="Ingresar" onClick="ingresarAguinaldos()"></td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>


    <div id="lista_aguinaldos"></div>
    </div>

    <br>


        <table align="center" width="100%" style="background-color:#09F; color:#FFF; font-weight:bold">
        <tr>
            <td style="color:#FFF">Deducciones</td>
            <td align="right" onClick="abrirCerrarDeducciones()" style="cursor:pointer; color:#FFF" id="td_signo_mas_deducciones">+</td>
        </tr>
    </table>



    <div id="id_interes_mas_deducciones" style="display:none">
    <table align="center" width="50%">
        <tr>
          <td align="right" class='viewPropTitle'>Tipo</td>
            <td>
            <input type="text" name="tipo_deducciones" id="tipo_deducciones">
            </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Periodo</td>
          <td id="div_anios_deducciones">
            <select name="periodos_deducciones" id="periodos_deducciones">
               <option value="0">.:: Seleccione ::.</option>
        <?
for ($i = 1997; $i < date("Y"); $i++) {
    ?>
            <option><?=$i . "-" . ($i + 1)?></option>
          <?
}
?>

            </select>

            </td>
        </tr>
        <tr>
          <td align="right" class='viewPropTitle'>Monto</td>
            <td><input type="text" name="monto_deducciones" id="monto_deducciones"></td>
        </tr>
    <tr>
                <td colspan="2" align="center">
                    <table>
                        <tr>
                            <td><input type="button" name="boton_ingresar_deducciones" id="boton_ingresar_deducciones" class="button" value="Ingresar" onClick="ingresarDeducciones()"></td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>


    <div id="lista_deducciones"></div>

    </div>
*/
    ?>


  </div>
  <!-- ********************************************* PRESTACIONES SOCIALES *********************************************-->




  <!-- ********************************************* DECLARACION JURADA *********************************************-->
  <div id="div_declaracionJurada" style="display:none">




    <div id="error"></div>
    <h4 align=center>Declaracion Jurada</h4>
    <h2 class="sqlmVersion"></h2>
    <br>
    <input type="hidden" id="iddeclaracion_jurada" name="iddeclaracion_jurada">
    <table width="80%" align="center">
      <tr>
        <td align="right" class="viewPropTitle">Tipo</td>
        <td>
          <select name="tipo_declaracion_jurada" id="tipo_declaracion_jurada">
            <option value="Ingreso">Ingreso</option>
            <option value="Actualizacion">Actualizacion</option>
            <option value="Egreso">Egreso</option>
          </select>
        </td>

      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Fecha Declaracion</td>
        <td colspan="3">
          <input type="text" name="fecha_declaracion_jurada" id="fecha_declaracion_jurada" size="12" readonly>
          <img src="imagenes/jscalendar0.gif" alt="" name="fecha_boton_declaracion_jurada" width="16" height="16" id="fecha_boton_declaracion_jurada" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "fecha_declaracion_jurada",
              button        : "fecha_boton_declaracion_jurada",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script>
        </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Fecha de Entrega</td>
        <td>
          <input type="text" name="fecha_entrega_declaracion_jurada" id="fecha_entrega_declaracion_jurada" size="12" readonly>
          <img src="imagenes/jscalendar0.gif" alt="" name="fecha_entrega_declaracion_jurada_imagen" width="16" height="16" id="fecha_entrega_declaracion_jurada_imagen" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "fecha_entrega_declaracion_jurada",
              button        : "fecha_entrega_declaracion_jurada_imagen",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script>
        </td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Constancia Anexa</td>
        <td><input type="checkbox" name="constancia_anexa_declaracion_jurada" id="constancia_anexa_declaracion_jurada"></td>
        <td align="right" class="viewPropTitle">Imagen</td>
        <td>

          <form method="post" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframe_declaracion_jurada" id="formulario_foto_declaracion_jurada" name="formulario_foto_declaracion_jurada">
            <input type="file" name="foto_declaracion_jurada" id="foto_declaracion_jurada" onChange="document.getElementById('formulario_foto_declaracion_jurada').submit()">
            <input type="hidden" name="nombre_imagen_declaracion_jurada" id="nombre_imagen_declaracion_jurada">
            <input type="hidden" name="ejecutar" id="ejecutar" value="cargarFotodeclaracion_jurada">
          </form>
          <iframe name="iframe_declaracion_jurada" id="iframe_declaracion_jurada" style="display:none"></iframe>
        </td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Observaciones</td>
        <td colspan="3"><textarea name="observaciones_declaracion_jurada" id="observaciones_declaracion_jurada" cols="60" rows="3"></textarea></td>
      </tr>

      <tr>
        <td colspan="4" align="center">


          <table align="center">
            <tr>
              <td><input type="button" name="boton_ingresar_declaracion_jurada" id="boton_ingresar_declaracion_jurada" value="Ingresar declaraci&oacute;n" onClick="ingresardeclaracion_jurada()" class="button"></td>
              <td><input type="button" name="boton_modificar_declaracion_jurada" id="boton_modificar_declaracion_jurada" value="Modificar declaraci&oacute;n" onClick="modificardeclaracion_jurada()" style="display:none" class="button"></td>
            </tr>
          </table>



        </td>
      </tr>
    </table>
    <br>
    <br>
    <div id="listadeclaracion_jurada"></div>






  </div>
  <!-- ********************************************* DECLARACION JURADA *********************************************-->




  <!-- ********************************************* CURSOS REALIZADOS *********************************************-->
  <div id="div_cursosRealizados" style="display:none">



    <div id="error"></div>
    <h4 align=center>Cursos Realizados</h4>
    <h2 class="sqlmVersion"></h2>
    <br>
    <input type="hidden" id="idcurso" name="idcurso">
    <table width="80%" align="center">
      <tr>
        <td align="right" class="viewPropTitle">Denominacion</td>
        <td colspan="3"><input name="denominacion_cursos" type="text" id="denominacion_cursos" size="80"></td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Detalle Contenido</td>
        <td colspan="3"><label>
          <textarea name="detalle_contenido_cursos" id="detalle_contenido_cursos" cols="60" rows="3"></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right" class="viewPropTitle">Duracion</td>
        <td colspan="3"><label>
          <input type="text" name="duracion_cursos" id="duracion_cursos">
        </label>
        <select name="tipo_duracion" id="tipo_duracion">
          <option value="horas_dias">Horas / dias</option>
          <option value="meses_anios">Meses / A&ntilde;os</option>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" class="viewPropTitle">Desde</td>
      <td>
        <input type="text" name="fecha_desde_cursos" id="fecha_desde_cursos" size="12" readonly>
        <img src="imagenes/jscalendar0.gif" alt="" name="fecha_desde_cursos_imagen" width="16" height="16" id="fecha_desde_cursos_imagen" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
          Calendar.setup({
            inputField    : "fecha_desde_cursos",
            button        : "fecha_desde_cursos_imagen",
            align         : "Tr",
            ifFormat      : "%Y-%m-%d"
          });
        </script>            </td>
        <td align="right" class="viewPropTitle"><span >Hasta</span></td>
        <td><input type="text" name="fecha_hasta_cursos" id="fecha_hasta_cursos" size="12" readonly>
          <img src="imagenes/jscalendar0.gif" alt="" name="fecha_hasta_boton_cursos" width="16" height="16" id="fecha_hasta_boton_cursos" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
          <script type="text/javascript">
            Calendar.setup({
              inputField    : "fecha_hasta_cursos",
              button        : "fecha_hasta_boton_cursos",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
            });
          </script></td>
        </tr>
        <tr>
          <td align="right" class="viewPropTitle">Instituci&oacute;n Educativa</td>
          <td colspan="3"><label>
            <input name="institucion_cursos" type="text" id="institucion_cursos" size="90">
          </label>            <label></label></td>
        </tr>
        <tr>
          <td align="right" class="viewPropTitle">Telefonos</td>
          <td colspan="3"><input name="telefonos_cursos" type="text" id="telefonos_cursos" size="50"></td>
        </tr>
        <tr>
          <td align="right" class="viewPropTitle">Realizado Por:</td>
          <td>
            <select name="realizado_por" id="realizado_por">
              <option value="Empleador">Empleador</option>
              <option value="Particular">Particular</option>
            </select>
          </td>
          <td align="right"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" class="viewPropTitle">Constancia Anexa</td>
          <td><input type="checkbox" name="constancia_anexa_cursos" id="constancia_anexa_cursos"></td>
          <td align="right" class="viewPropTitle">Imagen</td>
          <td>

            <form method="post" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframe_cursos" id="formulario_foto_cursos" name="formulario_foto_cursos">
              <input type="file" name="foto_cursos" id="foto_cursos" onChange="document.getElementById('formulario_foto_cursos').submit()">
              <input type="hidden" name="nombre_imagen_cursos" id="nombre_imagen_cursos">
              <input type="hidden" name="ejecutar" id="ejecutar" value="cargarFotoCursos">
            </form>
            <iframe name="iframe_cursos" id="iframe_cursos" style="display:none"></iframe>            </td>
          </tr>

          <tr>
            <td colspan="4" align="center">


              <table align="center">
                <tr>
                  <td><input type="button" name="boton_ingresar_cursos" id="boton_ingresar_cursos" value="Ingresar Cursos" onClick="ingresarCursos()" class="button"></td>
                  <td><input type="button" name="boton_modificar_cursos" id="boton_modificar_cursos" value="Modificar Cursos" onClick="modificarCursos()" style="display:none" class="button"></td>
                </tr>
              </table>        </td>
            </tr>
          </table>
          <br>
          <br>
          <div id="lista_cursos"></div>



        </div>
        <!-- ********************************************* CURSOS REALIZADOS *********************************************-->



        <!-- ********************************************* SANCIONES *********************************************-->
        <div id="div_sanciones" style="display:none">




          <div id="error"></div>
          <h4 align=center>Sanciones</h4>
          <h2 class="sqlmVersion"></h2>
          <br>
          <input type="hidden" id="idsanciones" name="idsanciones">
          <table width="80%" align="center">
            <tr>
              <td align="right" class="viewPropTitle">Tipo</td>
              <td>
                <select name="tipo_sanciones" id="tipo_sanciones">
                  <?
                  $sql_sanciones = mysql_query("select * from tipo_sanciones");
                  while ($bus_sanciones = mysql_fetch_array($sql_sanciones)) {
                    ?>
                    <option value="<?=$bus_sanciones["idtipo_sanciones"]?>"><?=$bus_sanciones["denominacion"]?></option>
                    <?
                  }
                  ?>
                </select>
              </td>

            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Motivo</td>
              <td colspan="3"><textarea name="motivo_sanciones" id="motivo_sanciones" cols="60" rows="3"></textarea></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Fecha</td>
              <td>
                <input type="text" name="fecha_sanciones" id="fecha_sanciones" size="12" readonly>
                <img src="imagenes/jscalendar0.gif" alt="" name="fecha_boton_sanciones" width="16" height="16" id="fecha_boton_sanciones" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                  Calendar.setup({
                    inputField    : "fecha_sanciones",
                    button        : "fecha_boton_sanciones",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                  });
                </script>
              </td>
              <td align="right" class="viewPropTitle">Numero de Documento</td>
              <td><input type="text" name="numero_documentos_sanciones" id="numero_documentos_sanciones"></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Fecha de Entrega</td>
              <td>
                <input type="text" name="fecha_entrega_sanciones" id="fecha_entrega_sanciones" size="12" readonly>
                <img src="imagenes/jscalendar0.gif" alt="" name="fecha_entrega_sanciones_imagen" width="16" height="16" id="fecha_entrega_sanciones_imagen" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                  Calendar.setup({
                    inputField    : "fecha_entrega_sanciones",
                    button        : "fecha_entrega_sanciones_imagen",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                  });
                </script>
              </td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Constancia Anexa</td>
              <td><input type="checkbox" name="constancia_anexa_sanciones" id="constancia_anexa_sanciones"></td>
              <td align="right" class="viewPropTitle">Imagen</td>
              <td>

                <form method="post" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframe_sanciones" id="formulario_foto_sanciones" name="formulario_foto_sanciones">
                  <input type="file" name="foto_sanciones" id="foto_sanciones" onChange="document.getElementById('formulario_foto_sanciones').submit()">
                  <input type="hidden" name="nombre_imagen_sanciones" id="nombre_imagen_sanciones">
                  <input type="hidden" name="ejecutar" id="ejecutar" value="cargarFotosanciones">
                </form>
                <iframe name="iframe_sanciones" id="iframe_sanciones" style="display:none"></iframe>
              </td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Observaciones</td>
              <td colspan="3"><textarea name="observaciones_sanciones" id="observaciones_sanciones" cols="60" rows="3"></textarea></td>
            </tr>

            <tr>
              <td colspan="4" align="center">


                <table align="center">
                  <tr>
                    <td><input type="button" name="boton_ingresar_sanciones" id="boton_ingresar_sanciones" value="Ingresar sanci&oacute;n" onClick="ingresarsanciones()" class="button"></td>
                    <td><input type="button" name="boton_modificar_sanciones" id="boton_modificar_sanciones" value="Modificar sanci&oacute;n" onClick="modificarsanciones()" style="display:none" class="button"></td>
                  </tr>
                </table>



              </td>
            </tr>
          </table>
          <br>
          <br>
          <div id="listasanciones"></div>




        </div>
        <!-- ********************************************* SANCIONES *********************************************-->



        <!-- ********************************************* RECONOCIMIENTOS *********************************************-->
        <div id="div_reconocimientos" style="display:none">
          <div id="error"></div>
          <h4 align=center>Reconocimientos</h4>
          <h2 class="sqlmVersion"></h2>
          <br>
          <input type="hidden" id="idreconocimientos" name="idreconocimientos">
          <table width="80%" align="center">
            <tr>
              <td align="right" class="viewPropTitle">Tipo</td>
              <td>
                <select name="tipo_reconocimientos" id="tipo_reconocimientos">
                  <?
                  $sql_reconocimientos = mysql_query("select * from tipos_reconocimientos");
                  while ($bus_reconocimientos = mysql_fetch_array($sql_reconocimientos)) {
                    ?>
                    <option value="<?=$bus_reconocimientos["idtipo_reconocimientos"]?>"><?=$bus_reconocimientos["denominacion"]?></option>
                    <?
                  }
                  ?>
                </select>
              </td>

            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Motivo</td>
              <td colspan="3"><textarea name="motivo_reconocimientos" id="motivo_reconocimientos" cols="60" rows="3"></textarea></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Fecha</td>
              <td>
                <input type="text" name="fecha_reconocimientos" id="fecha_reconocimientos" size="12" readonly>
                <img src="imagenes/jscalendar0.gif" alt="" name="fecha_boton_reconocimientos" width="16" height="16" id="fecha_boton_reconocimientos" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                  Calendar.setup({
                    inputField    : "fecha_reconocimientos",
                    button        : "fecha_boton_reconocimientos",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                  });
                </script>
              </td>
              <td align="right" class="viewPropTitle">Numero de Documento</td>
              <td><input type="text" name="numero_documentos_reconocimientos" id="numero_documentos_reconocimientos"></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Fecha de Entrega</td>
              <td>
                <input type="text" name="fecha_entrega_reconocimientos" id="fecha_entrega_reconocimientos" size="12" readonly>
                <img src="imagenes/jscalendar0.gif" alt="" name="fecha_entrega" width="16" height="16" id="fecha_entrega" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                <script type="text/javascript">
                  Calendar.setup({
                    inputField    : "fecha_entrega_reconocimientos",
                    button        : "fecha_entrega",
                    align         : "Tr",
                    ifFormat      : "%Y-%m-%d"
                  });
                </script>
              </td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Constancia Anexa</td>
              <td><input type="checkbox" name="constancia_anexa_reconocimientos" id="constancia_anexa_reconocimientos"></td>
              <td align="right" class="viewPropTitle">Imagen</td>
              <td>

                <form method="post" enctype="multipart/form-data" action="modulos/rrhh/lib/datos_basicos_ajax.php" target="iframe_reconocimientos" id="formulario_foto_reconocimientos" name="formulario_foto_reconocimientos">
                  <input type="file" name="foto_reconocimientos" id="foto_reconocimientos" onChange="document.getElementById('formulario_foto_reconocimientos').submit()">
                  <input type="hidden" name="nombre_imagen_reconocimientos" id="nombre_imagen_reconocimientos">
                  <input type="hidden" name="ejecutar" id="ejecutar" value="cargarFotoReconocimientos">
                </form>
                <iframe name="iframe_reconocimientos" id="iframe_reconocimientos" style="display:none"></iframe>
              </td>
            </tr>
            <tr>
              <td align="right" class="viewPropTitle">Observaciones</td>
              <td colspan="3"><textarea name="observaciones_reconocimientos" id="observaciones_reconocimientos" cols="60" rows="3"></textarea></td>
            </tr>

            <tr>
              <td colspan="4" align="center">


                <table align="center">
                  <tr>
                    <td><input type="button" name="boton_ingresar_reconocimientos" id="boton_ingresar_reconocimientos" value="Ingresar Reconocimiento" onClick="ingresarReconocimientos()" class="button"></td>
                    <td><input type="button" name="boton_modificar_reconocimientos" id="boton_modificar_reconocimientos" value="Modificar Reconocimiento" onClick="modificarReconocimientos()" style="display:none" class="button"></td>
                  </tr>
                </table>



              </td>
            </tr>
          </table>
          <br>
          <br>
          <div id="listaReconocimientos"></div>
        </div>
        <!-- ********************************************* reconocimientos *********************************************-->









        <!-- ********************************************* DATOS BASICOS *********************************************-->

        <!-- <form name='trabajador' action='' method='POST'> -->
        <div id="div_datosBasicos">
          <div id="resultado">
            <form name='trabajador' action='' method='POST'>
              <table align=center cellpadding=2 cellspacing=0 width="75%">
                <tr>
                  <td width="20%" align='right' bgcolor="#3399FF" class='viewPropTitle'>C&eacute;dula:</td>
                  <td width="28%" class=''>
                    <input type="text" id="cedula_datosBasicos" name="cedula_datosBasicos" maxlength="12" size="12" onKeyPress="javascript:return solonumeros(event)">
                    <input type="hidden" name="id_obrero" id="id_obrero">             </td>
                    <td width="16%" class=''>&nbsp;</td>
                    <td width="33%" rowspan="7" class=''><div id="cuadroFoto" style="width:100px; height:120px; border:#666666 solid 2px"> <br>
                      <br>
                      <br>
                      &nbsp;&nbsp;Sin Imagen </div></td>

                    </tr>

                    <tr>
                     <td width="20%" align='right' class='viewPropTitle'>N&ordm; de Ficha</td>
                     <td width="28%" class=''>
                       <table>
                        <tr>
                          <td><?
                            $sql_nomenclaturas = mysql_query("select * from nomenclatura_fichas");
                            ?>
                            <select name="nomenclatura_ficha_datosBasicos" id="nomenclatura_ficha_datosBasicos">
                              <option value="0" onClick="consultarFicha(this.value)">-</option>
                              <?
                              while ($bus_nomenclaturas = mysql_fetch_array($sql_nomenclaturas)) {
                                ?>
                                <option value="<?=$bus_nomenclaturas["idnomenclatura_fichas"]?>" onClick="consultarFicha(this.value)">
                                  <?=$bus_nomenclaturas["descripcion"]?>
                                </option>
                                <?
                              }
                              ?>
                            </select>                  </td>
                            <td id="nro_ficha" style="font-weight:bold"><!--<input name="nro_ficha" type="text" id="nro_ficha" size="10" maxlength="6" disabled>--></td>
                          </tr>
                        </table></td>
                        <td align='right' >&nbsp;</td>
                        <td width="1%" class=''>&nbsp;</td>
                      </tr>

                      <tr>
                        <td align='right' class='viewPropTitle'>Apellidos:</td>
                        <td class='' colspan="3"><input type="text" name="apellidos_datosBasicos" maxlength="45" size="45" id="apellidos_datosBasicos">                </td>
                        <td width="1%" align='right' >&nbsp;</td>
                        <td width="1%" class=''>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Nombres:</td>
                        <td class='' colspan="3"><input type="text" name="nombres_datosBasicos" maxlength="45" size="45" id="nombres_datosBasicos"></td>
                        <td align='right' >&nbsp;</td>
                        <td class=''>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>R.I.F.:</td>
                        <td class=''><input type="text" name="rif_datosBasicos" maxlength="12" size="15" id="rif_datosBasicos"></td>
                        <td align='right' >&nbsp;</td>
                        <td class='viewProp'>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>N&ordm; Pasaporte:</td>
                        <td class=''><input type="text" name="pasaporte_datosBasicos" maxlength="20" size="15" id="pasaporte_datosBasicos"></td>
                        <td align='right'>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align='right' class='viewPropTitle'>Nacionalidad:</td>
                        <td class='viewProp'>
                          <label><select name="nacionalidad_datosBasicos" id="nacionalidad_datosBasicos">
                            <option value="0">&nbsp;</option>
                            <?php
                            $nacionalidad = mysql_query("select * from nacionalidad");
                            while ($regnacionalidad = mysql_fetch_array($nacionalidad)) {
                              ?>
                              <option value="<?=$regnacionalidad["idnacionalidad"]?>">
                               <?php
                               echo $regnacionalidad["indicador"] . " " . $regnacionalidad["denominacion"];
                               echo "</option>";
                             } ?>
                           </select> </label>
                           <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=9&accion=60&pop=si','agregar nacionalidad','resisable = no, scrollbars = yes, width=900, height = 500')" style="cursor:pointer">       </td>
                           <td align='right'>&nbsp;</td>
                           <td>&nbsp;</td>
                         </tr>
                         <tr>
                          <td align='right' class='viewPropTitle'>Fecha de Nacimiento:</td>
                          <td><input name="fecha_nacimiento_datosBasicos" type="text" id="fecha_nacimiento_datosBasicos" size="13" maxlength="10">
                            <img src="imagenes/jscalendar0.gif" name="f_trigger_c2" id="f_trigger_c2" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                            <script type="text/javascript">
                              Calendar.setup({
                                inputField    : "fecha_nacimiento_datosBasicos",
                                button        : "f_trigger_c2",
                                align         : "Tr",
                                ifFormat      : "%Y-%m-%d"
                              });
                            </script></td>
                            <td align='right' >&nbsp;</td>
                            <td class='' colspan="4">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align='right' class='viewPropTitle'>Lugar de Nacimiento:</td>
                            <td class='' colspan="3"><input type="text" name="lugar_nacimiento_datosBasicos" maxlength="150" size="100" id="lugar_nacimiento_datosBasicos">       </td>

                          </tr>
                          <tr>
                            <td align='right' class='viewPropTitle'>Sexo:</td>
                            <td class=''>
                              <select name="sexo_datosBasicos" style="width:15%" id="sexo_datosBasicos">
                                <option VALUE="0"></option>
                                <option VALUE="F">F</option>
                                <option VALUE="M">M</option>
                              </select> </td>
                              <td align='right' class='viewPropTitle'>Estado Civil:</td>
                              <td class='viewProp'><label>
                                <select name="edo_civil_datosBasicos" id="edo_civil_datosBasicos">
                                  <option value="0">&nbsp;</option>
                                  <?php
                                  $edo_civil = mysql_query("select * from edo_civil");
                                  while ($regedo_civil = mysql_fetch_array($edo_civil)) {
                                    ?>
                                    <option value="<?=$regedo_civil["idedo_civil"]?>">
                                      <?
                                      echo $regedo_civil["denominacion"];
                                      echo "</option>";
                                      ?>
                                      <?php }?>
                                    </select>
                                  </label>
                                  <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=25&pop=si','agregar estado civil','resizable=no, scrollbars=yes, width=900, height = 500')" style="cursor:pointer"> </td>

                                </tr>
                                <tr>
                                  <td align='right' class='viewPropTitle'>Direcci&oacute;n:</td>
                                  <td class='' colspan="3"><textarea name="direccion_datosBasicos" cols="100" id="direccion_datosBasicos"></textarea>       </td>
                                </tr>
                                <tr>
                                  <td align='right' class='viewPropTitle'>Tlf. Habitaci&oacute;n:</td>
                                  <td class=''><input type="text" name="telefono_habitacion_datosBasicos" maxlength="25" size="25" id="telefono_habitacion_datosBasicos"></td>
                                  <td align='right' class='viewPropTitle'>Tlf. Movil:</td>
                                  <td class=''><input type="text" name="telefono_movil_datosBasicos" maxlength="25" size="25" id="telefono_movil_datosBasicos"></td>
                                </tr>
                                <tr>
                                  <td align='right' class='viewPropTitle'>e-Mail:</td>
                                  <td class=''><input type="text" name="correo_electronico_datosBasicos" maxlength="45" size="45" id="correo_electronico_datosBasicos"></td>
                                  <td align='right'>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>

                                <tr>
                                  <td colspan="6" align="center">

                                    <div id="fechaingreso_datos_basicos" style="display:block">
                                      <table width="92%" align="center">
                                       <tr>
                                         <td width="30%" align='right' class='viewPropTitle'>Fecha de Ingreso:</td>
                                         <td class=''><table border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="41%"><input name="fecha_ingreso_datosEmpleo0" type="text" id="fecha_ingreso_datosEmpleo0" size="12" value="<?=$regtrabajador['fecha_ingreso']?>"></td>
                                            <td width="59%"><img src="imagenes/jscalendar0.gif" name="f_trigger_f1" id="f_trigger_f1" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                              <script type="text/javascript">
                                                Calendar.setup({
                                                  inputField    : "fecha_ingreso_datosEmpleo0",
                                                  button        : "f_trigger_f1",
                                                  align         : "Tr",
                                                  ifFormat      : "%Y-%m-%d"
                                                });
                                              </script>                  </td>
                                            </tr>
                                          </table>
                                        </td>
                                        <td align='right' class='viewPropTitle'>Fecha Inicio Continuidad Administrativa</td>
                                        <td width="10%">
                                          <input name="fecha_inicio_continuidad0" type="text" id="fecha_inicio_continuidad0" size="12" value="<?=$regtrabajador['fecha_continuidad_administrativa']?>">
                                        </td>
                                        <td width="70%" align="left"><img src="imagenes/jscalendar0.gif" name="f_trigger_f_i1" id="f_trigger_f_i1" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                          <script type="text/javascript">
                                            Calendar.setup({
                                              inputField    : "fecha_inicio_continuidad0",
                                              button        : "f_trigger_f_i1",
                                              align         : "Tr",
                                              ifFormat      : "%Y-%m-%d"
                                            });
                                          </script>
                                        </td>

                                      </tr>
                                      <tr>
                                        <td align='right' class='viewPropTitle'>Cargo</td>
                                        <td class='' colspan="3">

                                         <?
                                         $sql_cargos = mysql_query("select * from cargos order by denominacion");
                                         ?>
                                         <select id="idcargo_datosEmpleo0" name="idcargo_datosEmpleo0">
                                          <option value="0">.:: Seleccione ::.</option>
                                          <?
                                          while ($bus_cargos = mysql_fetch_array($sql_cargos)) {
                                            ?>
                                            <option value="<?=$bus_cargos["idcargo"]?>"><?=$bus_cargos["denominacion"]?></option>
                                            <?
                                          }
                                          ?>
                                        </select>              </td>
                                      </tr>
                                      <tr>
                                        <td align='right' class='viewPropTitle'>Ubicaci&oacute;n Funcional: </td>
                                        <td class='' colspan="3"><?
/*      $foros = array();
$result = mysql_query("SELECT idniveles_organizacionales,
denominacion,
idcategoria_programatica,
sub_nivel
FROM
niveles_organizacionales
WHERE
modulo = 1") or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
$foro = $row['idniveles_organizacionales'];
$padre = $row['sub_nivel'];
if(!isset($foros[$padre]))
$foros[$padre] = array();
$foros[$padre][$foro] = $row;
}
 */

$sql_ubicacion_funcional = mysql_query("select * from niveles_organizacionales where modulo = '1' order by codigo");

?>

<select id="ubicacion_funcional_datosEmpleo0" name="ubicacion_funcional_datosEmpleo0">
  <option value="0">.:: Seleccione ::.</option>
  <?
  while ($bus_ubicacion_funcional = mysql_fetch_array($sql_ubicacion_funcional)) {
    ?>
    <option value="<?=$bus_ubicacion_funcional["idniveles_organizacionales"]?>">(<?=$bus_ubicacion_funcional["codigo"]?>)&nbsp;<?=$bus_ubicacion_funcional["denominacion"]?>
    </option>
    <?
  }
  ?>
</select></td>
</tr>
<tr>
  <td align='right' class='viewPropTitle'>Centro de Costo</td>
  <td class='' colspan="3"><?
/*       $foros = array();
$result = mysql_query("SELECT idniveles_organizacionales,
denominacion,
sub_nivel,
idcategoria_programatica
FROM
niveles_organizacionales
WHERE
modulo = 1
and idcategoria_programatica != 0") or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
$foro = $row['idniveles_organizacionales'];
$padre = $row['sub_nivel'];
if(!isset($foros[$padre]))
$foros[$padre] = array();
$foros[$padre][$foro] = $row;
}

 */

$sql_centro_costo = mysql_query("select no.idniveles_organizacionales,
  un.denominacion,
  ct.codigo
  from
  niveles_organizacionales no,
  unidad_ejecutora un,
  categoria_programatica ct
  where
  no.modulo='1'
  and no.idcategoria_programatica != '0'
  and ct.idcategoria_programatica = no.idcategoria_programatica
  and ct.idunidad_ejecutora = un.idunidad_ejecutora
  order by ct.codigo");

  ?>
  <select id="centro_costo_datosEmpleo0" name="centro_costo_datosEmpleo0">
    <option value="0">.:: Seleccione ::.</option>
    <?
    while ($bus_centro_costo = mysql_fetch_array($sql_centro_costo)) {
      ?>
      <option value="<?=$bus_centro_costo["idniveles_organizacionales"]?>">(
        <?=$bus_centro_costo["codigo"]?>
        )&nbsp;
        <?=$bus_centro_costo["denominacion"]?>
      </option>
      <?
    }
    ?>
  </select></td>
</tr>

<tr>

</tr>
</table>
</div>
<table align="center" cellpadding=2 cellspacing=0 id="tabla_botones">
  <tr>
    <td align="center"><input align=center class='button' name='ingresar_datosBasicos' id="ingresar_datosBasicos" type='button' value='Ingresar Datos Basicos' onClick="ingresarTrabajador()"></td>
    <td align="center"><input align=center class='button' name='modificar_datosBasicos' id="modificar_datosBasicos" type='button' value='Modificar' onClick="modificarTrabajador()" style="display:none"></td>
    <td align="center"><input align=center class='button' name='eliminar_datosBasicos' id="eliminar_datosBasicos" type='button' value='Eliminar' onClick="eliminarTrabajador()" style="display:none"></td>
    <td align="center"><input type="reset" value="Reiniciar" class="button" style="display:none"></td>
  </tr>
</table>



</td>
</tr>
</table>

</form>
</div>
</div>

<!-- ********************************************* DATOS BASICOS *********************************************-->




<!-- ********************************************* INFORMACION GENERAL *********************************************-->


<div id="div_informacionGeneral" style="display:none">
 <br>
 <h4 align=center>Informacion General</h4>
 <h2 class="sqlmVersion"></h2>
 <br>
 <table align=center cellpadding=2 cellspacing=0 width="60%">
  <tr>
    <td width="15%" align='right' class='viewPropTitle'>Peso:</td>
    <td width="15%" class=''><input type="text" name="peso_datosBasicos" maxlength="6" size="6" id="peso_datosBasicos"></td>
    <td width="15%" align='right' class='viewPropTitle'>Estatura:</td>
    <td width="15%" class=''><input type="text" name="talla_datosBasicos" maxlength="6" size="6" id="talla_datosBasicos"></td>
  </tr>
  <tr>
    <td width="15%" align='right' class='viewPropTitle'>Grupo Sanguineo:</td>
    <td width="15%" class='viewProp'>
      <label><select name="gruposanguineo_datosBasicos" id="gruposanguineo_datosBasicos" style="width:55%">
        <option value="0">&nbsp;</option>
        <?
        $grupo_sanguineo = mysql_query("select * from grupo_sanguineo");
        while ($reggrupo_sanguineo = mysql_fetch_array($grupo_sanguineo)) {
          ?>
          <option value="<?=$reggrupo_sanguineo["idgrupo_sanguineo"]?>"><?=$reggrupo_sanguineo["denominacion"]?></option>
          <?
        }
        ?>
      </select> </label>
      <img src='imagenes/add.png' onClick="window.open('principal.php?modulo=1&accion=27&pop=si','agregar grupo sanguineo','resizable = no, scrollbars = yes, width=900, height = 500')">
      <td width="15%" align='right' class='viewPropTitle'>Donante de Sangre:</td>
      <td width="15%" ><input type ="checkbox" name ="donante_datosBasicos" id="donante_datosBasicos"></td>
    </tr>
    <tr>
      <td width="15%" align='right' class='viewPropTitle'>Talla Camisa:</td>
      <td width="15%" class=''><input type="text" name="talla_camisa_datosBasicos" maxlength="10" size="10" id="talla_camisa_datosBasicos"></td>
      <td width="15%" align='right' class='viewPropTitle'>Talla Pantal&oacute;n:</td>
      <td width="15%" class=''><input type="text" name="talla_pantalon_datosBasicos" maxlength="10" size="10" id="talla_pantalon_datosBasicos"></td>
    </tr>
    <tr>
      <td width="15%" align='right' class='viewPropTitle'>Talla Zapatos:</td>
      <td width="15%" class=''><input type="text" name="talla_zapatos_datosBasicos" maxlength="10" size="10" id="talla_zapatos_datosBasicos"></td>
      <td width="15%" >&nbsp;</td>
      <td width="15%" >&nbsp;</td>
    </tr>
    <tr>
      <td width="15%" align='right' class='viewPropTitle'>Posee Vehiculo:</td>
      <td width="15%" ><input type ="checkbox" name ="vehiculo_datosBasicos" id="vehiculo_datosBasicos"></td>
      <td width="15%" align='right' class='viewPropTitle'>Licencia de Conducir:</td>
      <td width="15%" ><input type ="checkbox" name ="licencia_datosBasicos" id="licencia_datosBasicos"></td>
    </tr>
    <tr>
      <td width="15%" align='right' class='viewPropTitle'>Otras Actividades:</td>
      <td class='' colspan="3"><input type="text" name="otras_actividades_datosBasicos" maxlength="200" size="100" id="otras_actividades_datosBasicos"></td>
    </tr>



    <tr>
      <td align='right' class='viewPropTitle'>Llamar en caso de Emergencia:</td>
      <td class=''><input type="text" name="nombre_emergencia_datosBasicos" maxlength="45" size="45" id="nombre_emergencia_datosBasicos"></td>
      <td align='right' class='viewPropTitle'>Tlf Emergencia:</td>
      <td class=''><input type="text" name="telefono_emergencia_datosBasicos" maxlength="25" size="25" id="telefono_emergencia_datosBasicos"></td>
    </tr>
    <tr>
      <td align='right' class='viewPropTitle'>Direcci&oacute;n de Emergencia:</td>
      <td class='' colspan="3"><input type="text" name="direccion_emergencia_datosBasicos" maxlength="200" size="100" id="direccion_emergencia_datosBasicos"></td>
    </tr>



    <tr>
      <td colspan="4" align="center"><input type="button" name="boton_modificar_informacion_general" id="boton_modificar_informacion_general" value="Modificar Informacion General" class="button" onClick="ingresarInformacionGeneral()"></td>
    </tr>

  </table>
</div>


<!-- ********************************************* INFORMACION GENERAL *********************************************-->




<!-- ********************************************* IVSS *********************************************-->


<div id="div_ivss" style="display:none">
 <br>
 <h4 align=center>I.V.S.S.</h4>
 <h2 class="sqlmVersion"></h2>
 <br>
 <table align=center cellpadding=2 cellspacing=0 width="60%">
  <tr>
    <td width="15%" align='right' class='viewPropTitle'>Nro. de Registro I.V.S.S.:</td>
    <td width="15%" class=''><input type="text" name="numero_registro_ivss" maxlength="30" size="16" id="numero_registro_ivss"></td>
    <td width="15%" align='right' class='viewPropTitle'>Fecha de Registro I.V.S.S.:</td>
    <td width="15%" class=''><input type="text" name="fecha_registro_ivss" size="14" id="fecha_registro_ivss">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_g" id="f_trigger_g" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
      <script type="text/javascript">
        Calendar.setup({
          inputField    : "fecha_registro_ivss",
          button        : "f_trigger_g",
          align         : "Tr",
          ifFormat      : "%Y-%m-%d"
        });
      </script>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Ocupaci&oacute;n u Oficio:</td>
    <td class='' colspan="3"><input type="text" name="ocupacion_oficio_ivss" maxlength="200" size="100" id="ocupacion_oficio_ivss"></td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Otro:</td>
    <td class='' colspan="3"><input type="text" name="otro_ivss" maxlength="200" size="100" id="otro_ivss"></td>
  </tr>


  <tr>
    <td colspan="4" align="center"><input type="button" name="boton_modificar_ivss" id="boton_modificar_ivss" value="Actualizar Informacion I.V.S.S." class="button" onClick="ingresarInformacionivss()"></td>
  </tr>

</table>
</div>


<!-- ********************************************* IVSS *********************************************-->







<br><br>






<script> document.getElementById('cedula_datosBasicos').focus() </script>

<br>
</body>
</html>

