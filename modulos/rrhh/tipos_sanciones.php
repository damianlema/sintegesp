<script src="modulos/rrhh/js/tipos_sanciones_ajax.js"></script>
<body onLoad="consultarSanciones()">
	<br>
	<h4 align=center>Tipo de Sanciones</h4>
	<h2 class="sqlmVersion"></h2>
<br><br>
		<form method="post" name="tipos_sanciones" id="tipos_sanciones">

	<table align=center cellpadding=2 cellspacing=0>
			<input type="hidden" name="idtipo_sanciones" id="idtipo_sanciones" value="">
			<tr>
			  <td align='right' class='viewPropTitle'>Descripción:</td>
			  <td class='viewProp'><input type="text" name="descripcion" id="descripcion" value="">
&nbsp;<a href="principal.php?modulo=1&accion=1022"><img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Movimiento"></a></td>
	  </tr>
	</table>
	</table>
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
        <td><input type="button" value="Ingresar" class="button" id="boton_ingresar_tipos_sanciones" name="ingresar_tipos_sanciones" onClick="ingresarTiposSanciones()"></td>
        <td><input type="button" value="Modificar" class="button" id="boton_modificar_tipos_sanciones" name="modificar_tipos_sanciones" style="display:none" onClick="modificarTiposSanciones()"></td>
        <td><input type="reset" value="Reiniciar" class="button"></td>
        </tr>
	</table>
	
	</form>
	<br>


	
	<div align="center" id="listaTiposSanciones"></div>
</body>
</html>

