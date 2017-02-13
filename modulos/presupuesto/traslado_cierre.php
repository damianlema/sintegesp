<?php
include "../../../funciones/funciones.php";
?>
<script src="modulos/presupuesto/js/traslado_cierre_ajax.js"></script>
<body>
     <br>
    <h4 align=center>Traslados de Cierre</h4>
  <h2 class="sqlmVersion"></h2>
  <table align=center cellpadding=2 cellspacing=0 width="10%">
      <tr>
        <td align='center' >&nbsp;<a href="principal.php?modulo=2&accion=870"><img src="imagenes/nuevo.png" border="0" title="Nuevo Traslado Presupuestario"></a></td>
    </tr>
          <tr>
              <td>
                  <div id="divTipo" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
                      <div align="right"><a href="#" onClick="document.getElementById('divTipo').style.display='none'; document.getElementById('pdf').style.display='none'; document.getElementById('tableImprimir').style.display='none';">X</a></div>
                      <table id="tableImprimir">
                        <tr><td><input type="radio" name="tipo" id="solicitud" value="solicitud" checked /> Solicitud</td></tr>
                        <tr><td><input type="radio" name="tipo" id="simulado" value="simulado" /> Simulado</td></tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" class="button" name="botonOrdenar" value="Generar Reporte" onClick="pdf.location.href='lib/<?=$_SESSION["rutaReportes"]?>/presupuesto.php?nombre=traslado_presupuestario&id_traslado='+document.getElementById('idtraslados_presupuestarios').value+'&solicitud='+document.getElementById('solicitud').checked+'&simulado='+document.getElementById('simulado').checked; document.getElementById('pdf').style.display='block'; document.getElementById('divImprimir').style.display='block'; document.getElementById('divTipo').style.display='none'; document.getElementById('tableImprimir').style.display='none';">
                            </td>
                        </tr>
                      </table>
                  </div>
                </td>
            </tr>
  </table>
  <br>









<table align="center" cellpadding="2" cellspacing="0" width="80%">
  <tr>
    <td align='right' >&nbsp;</td>
    <td colspan="2" class=''>&nbsp;</td>
    <td>&nbsp;</td>
    <td align='right' >&nbsp;</td>
    <td class=''>&nbsp;</td>
    <td align='right'>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle' width="10%">Nro. Solicitud:</td>
    <td class='' width="10%"><input type="text" id="nro_solicitud" name="nro_solicitud" maxlength="12" size="12"/>
    </td>
    <td align='right' class='viewPropTitle' width="12%">Fecha Solicitud:</td>
    <td width="15%"><input name="fecha_solicitud" type="text" id="fecha_solicitud" size="13" maxlength="10" />
        <img src="imagenes/jscalendar0.gif" name="f_trigger_c" id="f_trigger_c" style="cursor: pointer; " title="Selector de Fecha" onMouseOver="this.style.background='#E6E6E6';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_solicitud",
              button        : "f_trigger_c",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
              });
            </script>
    </td>
    <td align='right' class='viewPropTitle' width="10%">Nro. Resolucion:</td>
    <td class='' width="10%"><input type="text" id="nro_resolucion" name="nro_resolucion" maxlength="12" size="12" />
    </td>
    <td align='right' class='viewPropTitle' width="13%">Fecha Resoluci&oacute;n:</td>
    <td width="15%"><input name="fecha_resolucion" type="text" id="fecha_resolucion" size="13" maxlength="10" />
        <img src="imagenes/jscalendar0.gif" name="f_trigger_d" id="f_trigger_d" style="cursor: pointer; " title="Selector de Fecha" onMouseOver="this.style.background='#E6E6E6';" onMouseOut="this.style.background=''" />
        <script type="text/javascript">
              Calendar.setup({
              inputField    : "fecha_resolucion",
              button        : "f_trigger_d",
              align         : "Tr",
              ifFormat      : "%Y-%m-%d"
              });
            </script>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle' width="12%">Justificaci&oacute;n:</td>
    <td class='' colspan="7"><textarea id="justificacion" name="justificacion" cols="137" rows="3" ></textarea>
    </td>
  </tr>
</table>
<table align="center" cellpadding="2" cellspacing="0" width="80%">
  <tr>
    <td width="10%" align='right' class='viewPropTitle'>A&ntilde;o:</td>
    <td width="13%" class='viewProp'>
      <select name="anio" style="width:80%" id="anio" disabled="disabled">
                        <?
anio_fiscal();
?>
      </select>
    </td>
    <td align='right' class='viewPropTitle' colspan="2">Total Disminuidas:</td>
    <td class='' width="12%"><input type="label" style="text-align:right" id="total_cedentes" name="total_cedentes" maxlength="18" size="18"/>
    </td>
    <td width="9%">&nbsp;</td>
    <td align='right' class='viewPropTitle' colspan="2">Total Aumentadas:</td>
    <td class='' width="12%"><input type="label" style="text-align:right" id="total_receptoras" name="total_receptoras" maxlength="18" size="18"/>
    </td>
    <td width="10%">&nbsp;</td>
  </tr>
</table>
    <table align="center" cellpadding="2" cellspacing="0">
        <tr>
        <td>
            <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarTraslado()">
            <input type="reset" value="Reiniciar" class="button" id="boton_reiniciar"/>
        </td>
        </tr>
    </table>




<div id="listarTraslados"></div>
</body>
</html>
