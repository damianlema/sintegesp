<script src="modulos/nomina/js/asociar_tipo_nomina_trabajador_ajax.js"></script>


	<br>
	<h4 align=center>Asociar Tipos de Nomina al Trabajador</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<br />

<center><img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=955&modulo=13'"></center>
<br />


<table width="60%" border="0" align="center">
 
 
 
 <tr>
    <td class="viewPropTitle"><p align="right">Tipo de Nomina:</td>
    <td>
    <?
    $sql_tipo_nomina = mysql_query("select * from tipo_nomina");
	?>
    <select name="idtipo_nomina" id="idtipo_nomina">
    <option value="0">.:: Seleccione ::.</option>
    <?
    while($bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina)){
		?>
			<option value="<?=$bus_tipo_nomina["idtipo_nomina"]?>"><?=$bus_tipo_nomina["titulo_nomina"]?></option>
		<?	
	}
	?>
    </select>
    </td>
  </tr> 
 
   <tr>
<td align="right" class="viewPropTitle">Tipo de Asociacion</td>
    <td><label>
      <select name="tipo_asociacion" id="tipo_asociacion">
        <option value="individual" onclick="document.getElementById('imagen_buscar_trabajador').style.display='block', document.getElementById('nombre_trabajador').disabled=false, document.getElementById('listaTrabajadores').style.display = 'none'">Individual</option>
        <option value="global" onclick="document.getElementById('imagen_buscar_trabajador').style.display='none', document.getElementById('nombre_trabajador').disabled=true,document.getElementById('nombre_trabajador').value='', document.getElementById('lista_conceptos_constantes').style.display='none', consultarListaTrabajadores('','')">Global</option>
      </select>
    </label></td>
  </tr>
 
  <tr>
    <td width="22%" class="viewPropTitle"><p align="right">Trabajador</td>
    <td width="78%">
    <input name="id_trabajador" type="hidden" id="id_trabajador"/>
    
    
    <table>
    <tr>
    <td>
      <input name="nombre_trabajador" type="text" id="nombre_trabajador" size="60" readonly="readonly"/>
     </td>
     <td>
    <img src="imagenes/search0.png" name="imagen_buscar_trabajador" width="16" height="16" id="imagen_buscar_trabajador" style="cursor:pointer" onclick="window.open('lib/listas/lista_trabajador.php?frm=conceptos_trabajador','','resizable=no, scrollbars=yes, width =900, height = 600')"/>
    </td>
    </tr>
    </table>
    
    
    </td>
  </tr>

  
  <tr>
    <td colspan="2">
    <div id="listaTrabajadores" style="width:100%; overflow:auto; height:300px; display:none"></div>    
    </td>
  </tr>
  <tr> 
    <td id="texto_valor_fijo" align="right"></td>
    <td>
      <input name="valor_fijo" type="text" id="valor_fijo" size="15" style="display:none" value="0"/> 
    </td> 
  </tr> 
  <tr> 
    <td colspan="2" align="center">
      <input type="submit" name="boton_asociar" id="boton_asociar" value="Asociar" class="button" onclick="asociarTipoNomina()"/>
      <input type="submit" name="boton_proba" id="boton_proba" value="Probar" class="button" onclick="probarFormula()" style="display:none"/> 
    </td> 
  </tr> 
</table>





<div id="lista_conceptos_constantes">


</div>
