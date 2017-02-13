<?


if($_POST){
extract($_POST);
			$sql_actualizar = mysql_query("update configuracion_recaudacion set primero_recaudacion = '".$primero_recaudacion."',
																ci_primero_recaudacion = '".$ci_primero_recaudacion."',
																cargo_primero_recaudacion = '".$cargo_primero_recaudacion."',
																segundo_recaudacion = '".$segundo_recaudacion."',
																ci_segundo_recaudacion = '".$ci_segundo_recaudacion."',
																cargo_segundo_recaudacion = '".$cargo_segundo_recaudacion."',
																tercero_recaudacion = '".$tercero_recaudacion."',
																ci_tercero_recaudacion = '".$ci_tercero_recaudacion."',
																cargo_tercero_recaudacion = '".$cargo_tercero_recaudacion."',
																iddependencia = '".$dependencias."',
																idestado = '".$estado."',
																idmunicipio = '".$municipios."',
																costo_unidad_tributaria = '".$unidad_tributaria."'")or die(mysql_query());
			
			$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");																
			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?
}
$sql= mysql_query("select * from configuracion_recaudacion");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
</head>
    <body onLoad="cargarSelect('municipios', 'celda_municipio', '<?=$bus["idestado"]?>', 'idestado')">
	<br>
	<h4 align=center>Configuraci&oacute;n Recaudacion</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<script src="modulos/recaudacion/js/configuracion_ajax.js"></script>
<form name="form1" method="post" action="">
  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
   	 <td width="305" class="viewPropTitle"><div align="right">Dependencia:</div></td>
     <td colspan="5">
     <select name="dependencias" id="dependencias">
	  <option value="0">.:: Seleccione ::. </option>
	  <?
      $sql_dependencias = mysql_query("select * from dependencias order by denominacion");
	  while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
	  	?>
	  	<option value="<?=$bus_dependencias["iddependencia"]?>"<? if ($bus_dependencias["iddependencia"] == $bus["iddependencia"]) echo " selected"?>><?=$bus_dependencias["denominacion"]?></option>
	  	<?
	  }
	  ?>
      </select>      </td>
    </tr>
    <tr>
      <td colspan="6" >&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Primer nivel:</p>      </td>
      <td width="284"><label>
        <input name="primero_recaudacion" type="text" id="primero_recaudacion" value="<?=$bus["primero_recaudacion"]?>" size="45">
      </label></td>
      <td width="48" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="118"><label>
        <input name="ci_primero_recaudacion" type="text" id="ci_primero_recaudacion" value="<?=$bus["ci_primero_recaudacion"]?>" size="16">
      </label></td>
      <td width="60" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="367"><label>
        <input name="cargo_primero_recaudacion" type="text" id="cargo_primero_recaudacion" value="<?=$bus["cargo_primero_recaudacion"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:</p>      </td>
      <td><label>
        <input name="segundo_recaudacion" type="text" id="segundo_recaudacion" value="<?=$bus["segundo_recaudacion"]?>" size="45">
      </label></td>
      <td width="48" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="118"><label>
        <input name="ci_segundo_recaudacion" type="text" id="ci_segundo_recaudacion" value="<?=$bus["ci_segundo_recaudacion"]?>" size="16">
      </label></td>
       <td width="60" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="367"><label>
        <input name="cargo_segundo_recaudacion" type="text" id="cargo_segundo_recaudacion" value="<?=$bus["cargo_segundo_recaudacion"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Tercer nivel:</p>      </td>
      <td><label>
        <input name="tercero_recaudacion" type="text" id="tercero_recaudacion" value="<?=$bus["tercero_recaudacion"]?>" size="45">
      </label></td>
      <td width="48" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="118"><label>
        <input name="ci_tercero_recaudacion" type="text" id="ci_tercero_recaudacion" value="<?=$bus["ci_tercero_recaudacion"]?>" size="16">
      </label></td>
      <td width="60" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="367"><label>
        <input name="cargo_tercero_recaudacion" type="text" id="cargo_tercero_recaudacion" value="<?=$bus["cargo_tercero_recaudacion"]?>" size="60">
      </label></td>
    </tr>
     
    
    <tr>
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Estado</div></td>
      <td colspan="5">
      <?
      $sql_estado = mysql_query("select * from estado");
	  ?>
      <select name="estado" id="estado" style="width:250px">
      	<option value="0">Ninguno</option>
		<?
        while($bus_estado = mysql_fetch_array($sql_estado)){
			?>
			<option value="<?=$bus_estado["idestado"]?>" onClick="cargarSelect('municipios', 'celda_municipio', '<?=$bus_estado["idestado"]?>', 'idestado')" <? if($bus_estado["idestado"] == $bus["idestado"]){echo "selected";}?>><?=$bus_estado["denominacion"]?></option>
			<?
		}
		?>    
      </select>      </td>
    </tr>
    <tr>
      <td class="viewPropTitle"><div align="right">Municipio</div></td>
      <td colspan="5" id="celda_municipio3"><select name="municipios" id="municipios">
        <option value="0">Seleccione el Estado</option>
      </select></td>
    <tr>
      <td class="viewPropTitle" align="right">Costo Uni. Trib.</td>
      <td colspan="5" id="celda_municipio2"><label>
        <input name="unidad_tributaria" type="text" id="unidad_tributaria" size="10" value="<?=$bus["costo_unidad_tributaria"]?>">
      </label></td>
    <tr>
      <td class="viewPropTitle">&nbsp;</td>
      <td colspan="5" id="celda_municipio">&nbsp;</td>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" align="center">
        <input type="submit" name="button" id="button" value="Actualizar" class="button">
   		<input type="reset" name="button2" id="button2" value="Cancelar" class="button"></td>
    </tr>
  </table>
</form>
