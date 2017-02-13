<?php
include "../../../funciones/funciones.php";
?>
<script src="modulos/presupuesto/js/modificar_presupuesto_ajax.js"></script>
	<body>
	<br>
    <h4 align=center>Corregir Presupuesto Original</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

	<h4 align=center>Rango a Mostrar</h4>

	<table align=center cellpadding=2 cellspacing=0 width="90%">
		<tr>
			<td align='right' class='viewPropTitle'>A&ntilde;o:</td>
			<td class='viewProp' style="width:7%">
				<select id="anio" name="anio" style="width:90%" disabled="disabled">
                        <?
anio_fiscal();
?>
				</select>			</td>
			<td align='right' class='viewPropTitle' style="width:15%">Tipo de Presupuesto:</td>
			<td class='viewProp' style="width:15%">
            <?
$sqltp                = "select * from tipo_presupuesto";
$sql_tipo_presupuesto = mysql_query($sqltp, $conexion_db);
?>
				<select name="tipo_presupuesto" id="tipo_presupuesto" style="width:98%">
					<option value="">&nbsp;</option>
					<?php
while ($rowtipo_presupuesto = mysql_fetch_array($sql_tipo_presupuesto)) {
    ?>
									<option <?php echo 'value="' . $rowtipo_presupuesto["idtipo_presupuesto"] . '"';if ($rowtipo_presupuesto["idtipo_presupuesto"] == $idtipo_presupuesto_fijo) {echo ' selected';} ?>>
										<?php echo $rowtipo_presupuesto["denominacion"]; ?>									</option>
					<?php
}
?>
				</select>			</td>

			<td align='right' class='viewPropTitle' style="width:18%">Fuente de Financiamiento:</td>
			<td class='viewProp' style="width:20%">
            <?
$sql_fuente_financiamiento = mysql_query("select * from fuente_financiamiento");
?>
				<select name="fuente_financiamiento" id="fuente_financiamiento" style="width:98%">
					<option value="">&nbsp;</option>
					<?php
while ($rowfuente_financiamiento = mysql_fetch_array($sql_fuente_financiamiento)) {
    ?>
									<option <?php echo 'value="' . $rowfuente_financiamiento["idfuente_financiamiento"] . '"';if ($rowfuente_financiamiento["idfuente_financiamiento"] == $idfuente_financiamiento_fijo) {echo ' selected';} ?>>
										<?php echo $rowfuente_financiamiento["denominacion"]; ?>									</option>
					<?php
}
?>
				</select>			</td>

			<td align='right' class='viewPropTitle' style="width:11%">Categoria Pro.:</td>
			<td class='viewProp' style="width:10%">
            <?
$sql_categoria_programatica = mysql_query("select * from categoria_programatica order by codigo");
?>
				<select name="categoria_programatica" id="categoria_programatica" style="width:95%">
					<option value="">&nbsp;</option>
					<?php
while ($rowcategoria_programatica = mysql_fetch_array($sql_categoria_programatica)) {
    ?>
									<option <?php echo 'value="' . $rowcategoria_programatica["idcategoria_programatica"] . '"'; ?>>
										<?php echo $rowcategoria_programatica["codigo"]; ?>									</option>
					<?php
}
?>
				</select>			</td>
	  </tr>
	</table>
	<br>
	<h2 class="sqlmVersion"></h2>
	<br>

	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class='viewProp'><input type="text" name="textoabuscar" id="textoabuscar" maxlength="60" size="30"></td>
			<td>
				<input type="button" class="button" name="buscar" id="buscar" value="Buscar" onClick="buscar_presupuesto()">
			</td>
		</tr>
	</table>
	<br>
	<div id="resultado" align="center">
        <table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
            <thead>
                <tr>
                    <td width="2%" align="center" class="Browse">A&ntilde;o</td>
                  <td width="2%" align="center" class="Browse">Tipo</td>
                  <td width="7%" align="center" class="Browse">Fuente Financiamiento</td>
                  <td width="7%" align="center" class="Browse">Categoria Programatica</td>
                  <td width="61%" align="center" class="Browse">Partida</td>
                  <td width="9%" align="center" class="Browse">Monto Actual</td>
                  <td width="9%" align="center" class="Browse">Disponible</td>
                  <td width="9%" align="center" class="Browse">Ajustar</td>
                  <td width="3%" colspan="2" align="center" class="Browse">Acci&oacute;n</td>
              </tr>
            </thead>
        </table>
	</div>
</body>
</html>