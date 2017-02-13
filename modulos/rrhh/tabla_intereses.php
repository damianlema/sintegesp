<script src="modulos/rrhh/js/tabla_intereses_ajax.js"></script>
<br>
<h4 align=center>Tabla de Intereses</h4>
<h2 class="sqlmVersion"></h2>

<br>
<form name="formulario_intereses" method="post" action="" id="formulario_intereses">
<input type="hidden" id="idinteres" name="idinteres">
<table align="center">
	<tr>
		<td align="right" class='viewPropTitle'>Año</td>
        <td>
        	<select id="anio" name="anio">
            <option>.:: Seleccione ::.</option>
            <?
            for($i=1997; $i<=date("Y");$i++){
				?>
				<option value="<?=$i?>"><?=$i?></option>
				<?	
			}
			?>
            </select>
        </td>
	</tr>
    <tr>
		<td align="right" class='viewPropTitle'>Mes</td>
        <td>
        	<select id="mes" name="mes">
            	<option>.:: Seleccione ::.</option>
             	<option value="01">(01) Enero</option>
                <option value="02">(02) Febrero</option>
                <option value="03">(03) Marzo</option>
                <option value="04">(04) Abril</option>
                <option value="05">(05) Mayo</option>
                <option value="06">(06) Junio</option>
                <option value="07">(07) Julio</option>
                <option value="08">(08) Agosto</option>
                <option value="09">(09) Septiembre</option>
                <option value="10">(10) Octubre</option>
                <option value="11">(11) Noviembre</option>
                <option value="12">(12) Diciembre</option>
            </select>
        </td>
	</tr>
    <tr>
		<td align="right" class='viewPropTitle'>Interes</td>
        <td><input type="text" id="interes" name="interes" size="12" onClick="this.select()"> %</td>
	</tr>
    <tr>
		<td colspan="2" align="center">
        	<table>
                <tr>
                    <td><input type="button" name="boton_ingresar_intereses" id="boton_ingresar_intereses" class="button" value="Ingresar" onClick="ingresarInteres()"></td>
                    <td><input type="button" name="boton_modificar_intereses" id="boton_modificar_intereses" class="button" value="Modificar" style="display:none" onClick="modificarInteres()"></td>
                </tr>
         	</table>
        </td>
	</tr>
</table>
</form>
<br>
<br>
<div id="listaPorcentajes">
<?
$sql_consultar = mysql_query("select * from tabla_intereses order by anio desc, mes desc");
		$meses['01'] = "Enero";
		$meses['02'] = "Febrero";
		$meses['03'] = "Marzo";
		$meses['04'] = "Abril";
		$meses['05'] = "Mayo";
		$meses['06'] = "Junio";
		$meses['07'] = "Julio";
		$meses['08'] = "Agosto";
		$meses['09'] = "Septiembre";
		$meses[10] = "Octubre";
		$meses[11] = "Noviembre";
		$meses[12] = "Diciembre";
		?>
		<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
        	<thead>
            <tr>
            	<td width="28%" class="Browse" align="center">Año</td>
                <td width="33%" class="Browse" align="center">Mes</td>
                <td width="25%" class="Browse" align="center">% Interes</td>
                <td class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["anio"]?></td>
                <td align="left" class="Browse"><?="(".$bus_consultar["mes"].")&nbsp;".$meses[$bus_consultar["mes"]]?></td>
                <td align="right" class="Browse"><?=number_format($bus_consultar["interes"],2,",",".")?>&nbsp;%</td>
                <td width="6%" align="center" class="Browse">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionarModificar('<?=$bus_consultar["idtabla_intereses"]?>', '<?=$bus_consultar["anio"]?>','<?=$bus_consultar["mes"]?>','<?=$bus_consultar["interes"]?>')"></td>
                 <td width="8%" align="center" class="Browse">
                 	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="eliminarIntereses('<?=$bus_consultar["idtabla_intereses"]?>')">
                 </td>
          </tr>
            <? } ?>
        </table>

</div>

