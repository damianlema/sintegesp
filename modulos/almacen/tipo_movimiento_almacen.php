<script src="modulos/almacen/js/tipo_movimiento_almacen_ajax.js"></script>
	<br>
	<h4 align=center>Conceptos de Movimientos de Almacen</h4>
	<h2 class="sqlmVersion"></h2>
    <br>

<center><a href="principal.php?accion=<?=$_GET["accion"]?>&amp;modulo=<?=$_GET["modulo"]?>">
	<img src="imagenes/nuevo.png" border="0" title="Nuevo Tipo de Movimiento">
</a></center>
<br />
<input type="hidden" id="id_tipo_movimiento_almacen" name="id_tipo_movimiento_almacen">
<table width="58%" border="0" align="center" cellpadding="4" cellspacing="0">
   <tr>
    <td align='right' class='viewPropTitle'>C&oacute;digo: </td>
    <td>
      <input name="codigo" type="text" id="codigo" size="6">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Denominaci&oacute;n: </td>
    <td>
      <input name="denominacion" type="text" id="denominacion" size="50">
    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Documento de Origen: </td>
    <td>
       <?
       $sql_consulta_select = mysql_query("select * from tipos_documentos where ((nro_contador != 0 and causa ='no' and compromete = 'si' and paga ='no') or (nro_contador != 0 and causa ='no' and compromete = 'no' and paga ='no')) and modulo like '%-".$_SESSION["modulo"]."-%'");
		?>
		<select name="documento_origen" id="documento_origen">
			<option value="0">.:: Seleccione ::.</option>
		<?
		 while($bus_consulta_select = mysql_fetch_array($sql_consulta_select)){
			?>
			<option value="<?=$bus_consulta_select["idtipos_documentos"]?>"><?=$bus_consulta_select["descripcion"]?></option>
			<?
		}
		?>
        </select>        
    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Afecta: </td>
    <td>
   	<input type="radio" name="afecta" id="afecta_entrada" value="1">Entrada
	<input type="radio" name="afecta" id="afecta_salida" value="2">Salida 
	<input type="radio" name="afecta" id="afecta_transferencia" value="3">
	Inventario Inicial
    <input type="radio" name="afecta" id="afecta_traslado" value="4">Traslado</td>
  </tr>
  
   <tr>
   	
    <td align='right' class='viewPropTitle'>Origen Material: </td>
    <td>
     <input type="radio" name="origen" id="origen_nuevo" value ="1">Nuevo
  	 <input type="radio" name="origen" id="origen_existente" value="2">Existente
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Activo: </td>
    <td>
      <input type="checkbox" name="activo" id="activo" value="0">
    </td>
  </tr>
   <tr>
    <td align='right' class='viewPropTitle'>Describir el movimiento: </td>
    <td>
      <input type="checkbox" name="describir_motivo" id="describir_motivo" value="0">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Memoria fotogr&aacute;fica: </td>
    <td>
      <input type="checkbox" name="memoria_fotografica" id="memoria_fotografica" value="0">
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Comprobante: </td>
    <td>
     <select name="comprobante" id="comprobante">
            <option value="">.: Seleccione :.</option>
            <option value="registro_inventario">Registro de Inventario</option>
            <option value="ajuste_inventario">Ajuste de Inventario</option>
            <option value="entrada_inventario">Entrada de Inventario</option>
            <option value="despacho_inventario">Despacho de Inventario</option>
            <option value="traslado_inventario">Traslado de Materias</option>
        </select>
    </td>
  </tr>
  <tr>
    <td align='right' class='viewPropTitle'>Nro. de Comprobante: </td>
    <td>
      <input name="numero_comprobante" type="text" id="numero_comprobante" size="5">
    </td>
  </tr>
 <br>
  <tr>
    <td colspan="2"><table width="50%" border="0" align="center" cellpadding="4" cellspacing="0">
      <tr>
        <td align="center">
          <input type="button" name="boton_ingresar" id="boton_ingresar" value="Ingresar" class="button" onClick="ingresarMovimiento()">
        </td>
        <td align="center">
          <input type="button" name="boton_modificar" id="boton_modificar" value="Modificar" class="button" style="display:none" onClick="modificarMovimiento()">
        </td>
        <td align="center">
          <input type="button" name="boton_eliminar" id="boton_eliminar" value="Eliminar" class="button" style="display:none" onClick="eliminarMovimiento()">
        </td>
        </tr>
    </table></td>
  </tr>
</table>
<br>
<br>
<br>
<div id="listaTipoMovimiento">
<?
		$sql_consulta = mysql_query("select * from tipo_movimiento_almacen order by afecta, codigo")or die(mysql_error());
	?>
	<table class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
   	  <thead>
        <tr>
          <td width="10%" align="center" class="Browse">C&oacute;digo</td>	
          <td width="80%" align="center" class="Browse">Denominaci&oacute;n</td>
          <td align="center" class="Browse" colspan="2">Acciones</td>&nbsp;
        </tr>
        </thead>
        <?
		
		$afecta = 0;
        while($bus_consulta = mysql_fetch_array($sql_consulta)){
		if ($afecta <> $bus_consulta["afecta"]){
			$afecta = $bus_consulta["afecta"];
			if ($afecta == 1) $denominacion_afecta = "Entrada";
			if ($afecta == 2) $denominacion_afecta = "Salida";
			if ($afecta == 3) $denominacion_afecta = "Inventario Inicial";
			if ($afecta == 4) $denominacion_afecta = "Traslado de Materias";
			echo "<tr>";
			echo "<td align='left' colspan='4' bgcolor='#0099CC'><b>&nbsp;".$denominacion_afecta."</b></td>";
			echo "</tr>"; ?>
       		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["descripcion"]?></td>
			
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
      		</tr>
        <?
		}else{ ?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
        	<td class='Browse'><?=$bus_consulta["codigo"]?></td>
          	<td class='Browse'><?=$bus_consulta["descripcion"]?></td>
			
            <td width="1%" align="center" class='Browse'><img src="imagenes/modificar.png" style="cursor:pointer" onClick="seleccionarModificar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
            <td width="1%" align="center" class='Browse'><img src="imagenes/delete.png" style="cursor:pointer" onClick="seleccionarEliminar('<?=$bus_consulta["id_tipo_movimiento_almacen"]?>', '<?=$bus_consulta["codigo"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["afecta"]?>', '<?=$bus_consulta["activo"]?>', '<?=$bus_consulta["origen_materia"]?>', '<?=$bus_consulta["describir_motivo"]?>', '<?=$bus_consulta["memoria_fotografica"]?>', '<?=$bus_consulta["comprobante"]?>', '<?=$bus_consulta["numero_comprobante"]?>', '<?=$bus_consulta["documento_origen"]?>')"></td>
     	 	</tr>
		<?
		}
        }
		?>
        </table>
</div>
