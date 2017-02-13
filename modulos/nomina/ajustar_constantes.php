<script src="modulos/nomina/js/ajustar_constantes_ajax.js"></script>


	<br>
	<h4 align=center>Ajustar Constantes</h4>
	<h2 class="sqlmVersion"></h2>
	

<center><img src="imagenes/nuevo.png" style="cursor:pointer" onclick="window.location.href='principal.php?accion=1209&modulo=13'"></center>
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
			<option value="<?=$bus_tipo_nomina["idtipo_nomina"]?>"
              onclick="consultarConstantes('','')"
        ><?=$bus_tipo_nomina["titulo_nomina"]?></option>
		<?	
	}
	?>
    </select>
    </td>
</tr> 

<tr>
  <td class="viewPropTitle"><p align="right">Constante:</td>
  <td id="celda_constante">
    <select name="idconstante" id="idconstante">
      <option value="0">.:: Seleccione ::.</option>
    </select>
  </td>
</tr>
 
  
<tr> 
    <td colspan="2" align="center">
      <input type="submit" name="boton_consultar" id="boton_consultar" value="Buscar" class="button" onclick="consultarListaTrabajadores('','')"/>
    </td> 
  </tr> 
</table>
<div id="titulo_datos_ajuste" style="display:none; position:absolute; left:50%; height:5px; width:76%; margin-left:-480px;margin-top:17px">
  Datos del Ajuste
</div>
<div id="tabla_datos_ajuste" style="display:none; position:absolute; border:1px solid; left:50%; height:100px; width:76%; margin-left:-480px;margin-top:30px">
<table width="80%" border="0" align="center">
  <tr>
    <td class="viewPropTitle"><p align="right">Fecha del Ajuste:</td>
    <td>
      <input type="text" id="fecha_ajuste" name="fecha_ajuste" size="12" value="<?=date('Y-m-d')?>">
      <img src="imagenes/jscalendar0.gif" name="f_trigger_c" width="16" height="16" id="f_trigger_c" style="cursor: pointer;" title="Selector de Fecha" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''"/>
        <script>
        Calendar.setup({
          inputField    : 'fecha_ajuste',
          button        : 'f_trigger_c',
          align         : 'Tr',
          ifFormat      : '%Y-%m-%d'
        });
        </script>
    </td>
  </tr>
  <tr>
    <td class="viewPropTitle"><p align="right">Aplicar ajuste:</td>
    <td>
        <select id="forma_fijar_valor">
          <option value="porcentual">Porcentual</option>
          <option value="valor_fijo">Valor fijo</option>
          <option value="sumar_valor">Sumar al valor actual</option>
        </select>
    </td>
    <td class="viewPropTitle"><p align="right">Valor:</td>
    <td>
      <input type="text" name="valor_ajuste" 
                              id="valor_ajuste" 
                              style="text-align:right" 
                              size="8" 
                              value="0" 
                              onClick="this.select()">
    </td>
  </tr>
  <tr>
    <td class="viewPropTitle"><p align="right">Rango desde:</td>
    <td>
        <select id="rango_desde">
          <option value="desde_todos">Todos</option>
          <option value="desde_igual">Igual ( = )</option>
          <option value="desde_mayor">Mayor ( > )</option>
          <option value="desde_mayor_igual">Mayor o igual ( >= )</option>
        </select>
    </td>
    <td>
      <input type="text" name="valor_rango_desde" 
                              id="valor_rango_desde" 
                              style="text-align:right" 
                              size="8" 
                              value="0" 
                              onClick="this.select()">
    </td>
    <td class="viewPropTitle"><p align="right">Rango Hasta:</td>
    <td>
        <select id="rango_hasta">
          <option value="hasta_todos">Todos</option>
          <option value="hasta_igual">Igual ( = )</option>
          <option value="hasta_menor">Menor ( < )</option>
          <option value="hasta_menor_igual">Menor o igual ( <= )</option>
        </select>
    </td>
    <td>
      <input type="text" name="valor_rango_hasta" 
                              id="valor_rango_hasta" 
                              style="text-align:right" 
                              size="8" 
                              value="0" 
                              onClick="this.select()">
    </td>
  </tr>
  <tr> 
    <td colspan="6" align="center">
      <input type="submit" name="boton_aplicar" id="boton_aplicar" value="Aplicar Ajuste" class="button" onclick="aplicar_ajuste()"/>
    </td> 
  </tr> 
</table>
</div>
<br>
<table width="80%" border="0" align="center">
  <tr><td>
    <div id="listaTrabajadores" style="width:95%; overflow:auto; height:300px; display:none; margin-top:130px"></div>
  </td></tr>
</table>
