<script src="modulos/presupuesto/js/consultar_ordenes_ajax.js"></script>
	<br>
	<h4 align=center>Consultar Ordenes</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" onsubmit="return buscarOrdenes()">
<table width="60%" border="0" align="center">
  <tr>
    <td class="viewPropTitle" align="right">Numero de Orden</td>
    <td><label>
      <input type="text" name="numero_orden" id="numero_orden">
    </label></td>
    <td class="viewPropTitle" align="right">Beneficiario</td>
    <td><label>
      <input type="text" name="beneficiario" id="beneficiario">
    </label></td>
    <td><label>
      <input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="button">
    </label></td>
  </tr>
</table>
<br />
<br />

<div id="mostrarDetalles"></div>

<br />

<div id="listaOrdenes">
	
</div>


