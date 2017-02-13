<script language="javascript" src="modulos/nomina/js/tabla_constante.js"></script>
<p align="center"><h4 align="center">Tabla Constantes</h4>
<div align="center">
	<button name="listado" type="button" style="background-color:white;border-style:none;cursor:pointer;" onclick="window.open('lib/listas/listar_tabla_constantes.php','','resizabled = no, width = 900, height = 600')"><img src='imagenes/search0.png' title="Buscar Codigo" /></button>
    
	<img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=882&modeulo=13'"> &nbsp;
    
    <img src="imagenes/imprimir.png" style="cursor:pointer; visibility:hidden;" id="btImprimir" title="Imprimir" onclick="document.getElementById('divImprimir').style.display='block'; pdf.location.href='lib/reportes/nomina/reportes.php?nombre=nomina_tabla_constantes&idtabla_constantes='+document.getElementById('idtabla_constantes').value; document.getElementById('pdf').style.display='block';">
    
    <div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid; width:50%; left:25%">
        <div align="right">
            <a href="#" onClick="document.getElementById('divImprimir').style.display='none'; document.getElementById('pdf').style.display='none';">X</a>
        </div>
        <iframe name="pdf" id="pdf" style="display:none; width:99%; height:550px;"></iframe>   
    </div>
</div>
</p>


<input type="hidden" name="idtabla_constantes" id="idtabla_constantes">

<table width="500" border="0" align="center" cellspacing="4">
  <tr>
    <td width="75" align="right" class="viewPropTitle">Codigo:</td>
    <td colspan="6"><input type="text" size="18" id="codigo" name="codigo">
    </td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Descripción:</td>
    <td colspan="6"><input type="text" size="70" id="descripcion" name="descripcion"></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Desde:</td>
    <td width="156">
 <input type="text" name="desde" id="desde" size="13" readonly="readonly" value="<?php echo $desde; ?>"/>
    	<img src="imagenes/jscalendar0.gif" name="desde_cal" width="16" height="16" id="desde_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
        <script type="text/javascript">
							Calendar.setup({
							inputField    : "desde",
							button        : "desde_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script>
    </td>
    <td width="20" align="right">&nbsp;</td>
    <td width="8">&nbsp;</td>
    <td width="8">&nbsp;</td>
    <td width="46" align="right" class="viewPropTitle">Hasta:</td>
    <td width="162"><input type="text" name="hasta" id="hasta" size="13" readonly="readonly" value="<?php echo $hasta; ?>"/>
      <img src="imagenes/jscalendar0.gif" name="hasta_cal" width="16" height="16" id="hasta_cal" style="cursor: pointer;" title="Selector de Fecha" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />
      <script type="text/javascript">
							Calendar.setup({
							inputField    : "hasta",
							button        : "hasta_cal",
							align         : "Tr",
							ifFormat      : "%Y-%m-%d"
							});
						</script></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Unidad:</td>
    <td>
        <select id="unidad" name="unidad">
        	<option selected value="dias">D&iacute;as</option>
            <option value="meses">Meses</option>
            <option value="anos">A&ntilde;os</option>
            <option value="bolivares">Bolivares</option>
        </select>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="7" align="center">
        
        <table align="center">
        <tr>
            <td>
            <input type="button" class="button" name="guardar" id="guardar" value="Ingresar" onClick="ingresarConstante(document.getElementById('codigo').value, document.getElementById('descripcion').value, document.getElementById('desde').value, document.getElementById('hasta').value, document.getElementById('unidad').value)">
            </td>
            <td>
              <input type="button" class="button" name="modificar" id="modificar" value="Modificar" onClick="modificarTablaConstantes(document.getElementById('codigo').value, document.getElementById('descripcion').value, document.getElementById('desde').value, document.getElementById('hasta').value, document.getElementById('unidad').value)" style="display:none">
             </td>
             <td>
            <input type="button" class="button" name="eliminar" id="eliminar" value="Eliminar" onClick="eliminarTablaConstantes()" style="display:none">
            </td>
        </tr>
        </table>
	</td>
  </tr>
</table>
<p>&nbsp;</p>
<div id="rango"></div>