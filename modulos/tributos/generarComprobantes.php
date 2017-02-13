<div id="divImprimir" style="display:none; position:absolute; background-color:#CCCCCC; border:1px solid;">
<table align="center">
	<tr><td align="right"><a href="#" onClick="document.getElementById('divImprimir').style.display='none';">X</a></td></tr>
   	<tr><td><iframe name="pdf" id="pdf" style="display:none" height="600" width="750"></iframe></td></tr>
</table>
</div>
<script src="modulos/tributos/js/generar_comprobantes_ajax.js"></script>
	<br>
	<h4 align=center>Generar Comprobantes de Retenciones</h4>
	<br />
    <h2 class="sqlmVersion"></h2>
    <br>
    <br />


<form onsubmit="return listarOrdenPago(document.getElementById('busqueda').value)">
    <table align="center">
    	<tr>
        <td align='right' class='viewPropTitle'>Numero de Orden de Pago</td>
        <td><input type="text" id="busqueda" name="busqueda"></td>
        <td><input type="submit" id="boton_buscar" name="boton_buscar" style="cursor:pointer" class="button" value="Buscar"></td>
        </tr>
    </table>
</form>
	<div id="listaOrdenPago"></div>



