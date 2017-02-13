<script src="modulos/presupuesto/js/cofinanciamiento_ajax.js"></script>
    <br>
<h4 align=center>Co-Financiamiento</h4>
	<h2 class="sqlmVersion"></h2>
 <br>


<table align="center" cellspacing="6">
<tr>
<td><img src="imagenes/search0.png" onclick="window.open('lib/listas/listar_cofinanciamientos.php','lista de cofinanciamiento','width=900, height=600')" style="cursor:pointer" title="Buscar Cofinanciamiento" alt="Buscar Cofinanciamiento"></td>
<td><img src="imagenes/nuevo.png" onclick="window.location.href = 'principal.php?modulo=<?=$_GET["modulo"]?>&accion=<?=$_GET["accion"]?>'" style="cursor:pointer" title="Nuevo Cofinanciamiento" alt="Nuevo Cofinanciamiento"></td>
</tr>
</table> 
 <br />

<input type="hidden" name="idcofinanciamiento" id="idcofinanciamiento">
<table width="70%" align="center">
<tr>
<td width="23%" align="right" class='viewPropTitle'>Denominacion</td>
<td colspan="3"><input name="denominacion" type="text" id="denominacion" size="70"></td>
</tr>
<tr>
	<td align="center" colspan="4">
    	<input type="button" name="boton_ingresar_cofinanciamiento" id="boton_ingresar_cofinanciamiento" value="Ingresar" class="button" onclick="ingresarCofinanciamiento()">
    </td>
</tr>
</table>

<br>

<table width="60%" align="center" id="tabla_fuentes" style="display:none">
<tr>
    <td width="33%" align="right" class='viewPropTitle'>Fuente de Finanaciamiento</td>
    <td width="48%">
    <select id="fuente_financiamiento" name="fuente_financiamiento">
    <?
    $sql_fuente = mysql_query("select * from fuente_financiamiento");
    while($bus_fuente = mysql_fetch_array($sql_fuente)){
        ?>
        <option value="<?=$bus_fuente["idfuente_financiamiento"]?>"><?=$bus_fuente["denominacion"]?></option>
        <?
    }
    ?>
    </select>
    </td>
    <td width="11%"><strong>%</strong>&nbsp;
    <input name="porcentaje_fuente_financiamiento" type="text" id="porcentaje_fuente_financiamiento" size="5" maxlength="100"></td>
	<td width="8%"><input type="button" id="boton_incluir" name="boton_incluir" value="Incluir" class="button" onclick="incluirFinancimiento()"></td>
</tr>
<tr>
	<td id="listaFuentesSeleccionadas" colspan="4"></td>
</tr>
</table>
