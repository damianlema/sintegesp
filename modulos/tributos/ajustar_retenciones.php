<script src="modulos/tributos/js/ajustar_retenciones_ajax.js"></script>
<form method="post" action="" onsubmit="return listarCompromisos()">
<table align="center">
    <tr>
        <td align="right" class='viewPropTitle'>Nro. Orden</td>
        <td><input type="text" name="numero_orden" id="numero_orden"></td>
        <td align="right" class='viewPropTitle'>Beneficiario</td>
        <td><input type="text" name="beneficiario" id="beneficiario"></td>
        <td><input type="submit" name="boton_buscar" id="boton_buscar" class="button" value="Buscar"></td>
    </tr>
</table>
</form>
<br />
<div id="listaRetenciones"></div>
<br />
<div id="listaCompromisos"></div>
