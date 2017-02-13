<script src="modulos/utilidades/js/centralizarOrden.js"></script>
    <br>
<h4 align=center>Ajustar Requisiciones</h4>
	<h2 class="sqlmVersion"></h2>
 <br>
 
<table align="center">
    <tr>
        <td>Numero Orden</td>
        <td><input type='text' id="numero_orden" name="numero_orden"></td>
        <td><input type="button" name="boton_buscar" id="boton_buscar" value="Buscar" onClick="buscarOrdenes(document.getElementById('numero_orden').value)"></td>
    </tr>
</table>

<br>
<br>

<table>
<tr>
<td id="cuadroOrdenes"></td>
</tr>
</table>

