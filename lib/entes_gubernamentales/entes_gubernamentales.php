<script src="js/entes_gubernamentales_ajax.js"></script>
	<br>
	<h4 align=center>Entes Gubernamentales</h4>
	<h2 class="sqlmVersion"></h2>
<div align="center"><br>
	    <br />
	    <input type="hidden" id="id_ente_gubernamental" name="id_ente_gubernamental">
	    <br>
    <img src="imagenes/nuevo.png" width="16" height="16" style="cursor:pointer" onClick="window.location.href='principal.php?accion=741&modulo=6'"><br>
    <br>
    </div>
<table align="center" id="tablaPrincipal" width="600">
	<tr>
    	<td align="right" class='viewPropTitle'>Nombre:</td>
        <td><input type="text" id="nombre" name="nombre" size="80"></td>
    </tr>
    <tr>
    	<td align="right" class='viewPropTitle'>Presidente / Director(a):</td>
        <td><input type="text" id="director" name="director" size="80"></td>
    </tr>
    <tr>
    	<td align="right" class='viewPropTitle'>Cargo:</td>
        <td><input type="text" id="cargod" name="cargod" size="80"></td>
    </tr>
    <tr>
    	<td align="right" class='viewPropTitle'>Administrador(a):</td>
        <td><input type="text" id="administrador" name="administrador" size="80"></td>
    </tr>
    <tr>
    	<td align="right" class='viewPropTitle'>Cargo:</td>
        <td><input type="text" id="cargoa" name="cargoa" size="80"></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
        	<input type="button" id="boton_guardar" name="boton_guardar"  value="Ingresar" class="button" onClick="ingresarEnte()">
            <input type="button" id="boton_modificar" name="boton_modificar" value="Modificar" style="display:none" class="button" onClick="modificarEnte()">
            <input type="button" id="boton_eliminar" name="boton_eliminar" value="Eliminar" style="display:none" class="button" onClick="eliminarEnte()">
        </td>
    </tr>
</table>
<br>
<br>
<div id="listaEntesGubernamentales" style="display:block">
  <table class="Main" cellpadding="0" cellspacing="0" width="50%" align="center">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
		  <table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td width="81%" align="center" class="Browse">Nombre</td>
								  <td align="center" class="Browse" colspan="2">Acciones</td>
								</tr>
							</thead>
							
							<? 
								$sql_consultar = mysql_query("select * from entes_gubernamentales order by nombre ASC");
								while($bus_consultar = mysql_fetch_array($sql_consultar)){
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
									<td align='left' class='Browse'><?=$bus_consultar["nombre"]?></td>
                                    <td width="11%" align='center' class='Browse'>
                                    	<img src="imagenes/modificar.png" style="cursor:pointer" onClick="mostrarModificar('<?=$bus_consultar["nombre"]?>', '<?=$bus_consultar["director"]?>','<?=$bus_consultar["cargod"]?>','<?=$bus_consultar["administrador"]?>','<?=$bus_consultar["cargoa"]?>', '<?=$bus_consultar["identes_gubernamentales"]?>')">
                                    </td>
                                  	<td width="8%" align='center' class='Browse'>
                                  		<img src="imagenes/delete.png" style="cursor:pointer" onClick="mostrarEliminar('<?=$bus_consultar["nombre"]?>', '<?=$bus_consultar["director"]?>','<?=$bus_consultar["cargod"]?>','<?=$bus_consultar["administrador"]?>','<?=$bus_consultar["cargoa"]?>', '<?=$bus_consultar["identes_gubernamentales"]?>')">
                                  </td>
                          </tr>
								<?
								}
							?>
						</table>
						</form>
					</td>
				</tr>
  </table>
            
</div>
        