<?php
session_start();
?>


<script src="modulos/utilidades/js/auditoria_ajax.js"></script>
	<br>
	<h4 align=center>Auditoria del Sistema</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    <table align="center" width="51%">
        <tr>
            <td>        	
              <div id="divTipoOrden" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
              <div align="right"><a href="javascript:;" onClick="document.getElementById('divTipoOrden').style.display='none';">X</a></div>
              <iframe name="pdf" id="pdf" style="display:block" height="600" width="700"></iframe>          
              </div>
            </td>
        </tr>
    </table>
    <table align="center">
      <tr>
        <td> 
          <a href="javascript:;" onClick="listarPDF(document.getElementById('fecha_desde').value, document.getElementById('fecha_hasta').value, document.getElementById('cedulaUsuario').value);">
          	<img src="imagenes/imprimir.png" border="0" title="Imprimir">
          </a>
        </td>
      </tr>
    </table>

<table align="center" width="51%">
<tr>
    	<td class='viewPropTitle' colspan="4"><strong>Criterios de Busqueda</strong></td>
    </tr>
    	<tr>
    	  <td class='viewPropTitle'>Desde</td>
    	  <td><input type="text" name="fecha_desde" id="fecha_desde" size="13" readonly="readonly"/>
            <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
            <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_desde",
							button        : "f_trigger_c",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
    	  <td class='viewPropTitle'>Hasta</td>
    	  <td><input type="text" name="fecha_hasta" id="fecha_hasta" size="13" readonly="readonly"/>
            <img src="imagenes/jscalendar0.gif" name="f_trigger_d" width="16" height="16" id="f_trigger_d" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
            <script type="text/javascript">
							Calendar.setup({
							inputField    : "fecha_hasta",
							button        : "f_trigger_d",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
  	  </tr>
    	<tr>
    	<td width="12%" class='viewPropTitle'>Documento:</td>
        <td width="33%"><label>
          <input type="text" name="documento" id="documento" />
        </label></td>
        <td width="13%" class='viewPropTitle'>&nbsp;</td>
        <td width="42%">&nbsp;</td>
    </tr>
    <tr>
        <td class='viewPropTitle' colspan="2">Usuario:</td>
        <td colspan="2">
            <input type="hidden" id="cedulaUsuario" name="cedulaUsuario">
            <input type="text" name="usuarios" id="usuarios" onKeyUp="consultarUsuarios(this.value)" autocomplete="OFF" size="45">
            <div id="divListaUsuarios" style="border:#000000 1px solid; position:absolute; display:none; width:225px; background-color:#FFFFFF; padding:5px 0px 10px 10px"></div>        </td>
    </tr>
    <tr>
        <td colspan="4">
        	<input type="button" id="botonConsultar" name="botonConsultar" value="Consultar" class="button" onClick="listarTransacciones(document.getElementById('fecha_desde').value, document.getElementById('fecha_hasta').value, document.getElementById('cedulaUsuario').value, document.getElementById('documento').value)">      </td>
    </tr>
</table>
<br>
	<h2 class="sqlmVersion">&nbsp;</h2>
	<br>
    <div id="listaConsulta" style="width:100%" ></div>
    
    <input type="hidden" id="rutaReportes" name="rutaReportes" value="<?=$_SESSION["rutaReportes"]?>">