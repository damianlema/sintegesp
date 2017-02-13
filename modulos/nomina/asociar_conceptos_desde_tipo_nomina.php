<script src="modulos/nomina/js/asociar_conceptos_desde_tipo_nomina_ajax.js"></script>

<br>
	<h4 align=center>Asociar Conceptos desde Tipos de Nomina</h4>
	<h2 class="sqlmVersion"></h2>
	<br>


<table width="65%" border="0" align="center">
  <tr>
    <td width="52%" align="right" class="viewPropTitle">Tipo de Nomina donde Existe el Concepto</td>
    <td width="48%"><label>
      <select name="tipo_nomina_existe_concepto" id="tipo_nomina_existe_concepto">
      <option value="0">.:: Seleccione ::.</option>
	  <?
      $sql_tipo_nomina = mysql_query("select * from tipo_nomina");
	  while($bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina)){
	  	?>
        <option value="<?=$bus_tipo_nomina["idtipo_nomina"]?>" onclick="consultarConceptos('<?=$bus_tipo_nomina["idtipo_nomina"]?>')"><?=$bus_tipo_nomina["titulo_nomina"]?></option>
        <?
	 	}
	  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Concepto o Constante</td>
    <td id="celda_concepto"><label>
      <select name="constante_concepto" id="constante_concepto">
      <option value="0">.:: Seleccione el tipo de nomina ::.</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">Tipo de Nomina a Donde Asociar</td>
    <td>
    <select name="tipo_nomina_asociar" id="tipo_nomina_asociar">
    <option value="0">.:: Seleccione ::.</option>
	<?
      $sql_tipo_nomina = mysql_query("select * from tipo_nomina");
	  while($bus_tipo_nomina = mysql_fetch_array($sql_tipo_nomina)){
	  	?>
        <option value="<?=$bus_tipo_nomina["idtipo_nomina"]?>" onclick="consultarConceptos('<?=$bus_tipo_nomina["idtipo_nomina"]?>')"><?=$bus_tipo_nomina["titulo_nomina"]?></option>
        <?
	 	}
	  ?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right" class="viewPropTitle">&nbsp;</td>
    <td><label></label></td>
  </tr>
</table>
