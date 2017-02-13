<?


if($_POST){
extract($_POST);
if ($_POST["controlar_cheques"])
		$controlar_cheques=1;
	else
		$controlar_cheques=0;
			$sql_actualizar = mysql_query("update configuracion_tesoreria set primero_tesoreria = '".$primero_tesoreria."',
																			ci_primero_tesoreria = '".$ci_primero_tesoreria."',
																			cargo_primero_tesoreria = '".$cargo_primero_tesoreria."',
																			segundo_tesoreria = '".$segundo_tesoreria."',
																			ci_segundo_tesoreria = '".$ci_segundo_tesoreria."',
																			cargo_segundo_tesoreria = '".$cargo_segundo_tesoreria."',
																			tercero_tesoreria = '".$tercero_tesoreria."',
																			ci_tercero_tesoreria = '".$ci_tercero_tesoreria."',
																			cargo_tercero_tesoreria = '".$cargo_tercero_tesoreria."',
																			nro_remision = '".$nro_remision."',
																			iddependencia = '".$dependencias."', 
																			status = 'a',
																			usuario= '".$login."',
																			fechayhora= '".$fh."',
																			controlar_cheques= '".$controlar_cheques."',
																			relacion_comprobantes = '".$nro_relacion."'
																			")or die(mysql_query());
			$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");																
			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?
}
$sql= mysql_query("select * from configuracion_tesoreria");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
    </head>
    <body>
	<br>
	<h4 align=center>Configuraci&oacute;n Tesoreria</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="form1" method="post" action="">
  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
   	 <td class="viewPropTitle"><div align="right">Dependencia:</div></td>
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
      </select>  
      </td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Primer nivel:</p>      </td>
      <td><label>
        <input name="primero_tesoreria" type="text" id="primero_tesoreria" value="<?=$bus["primero_tesoreria"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_primero_tesoreria" type="text" id="ci_primero_tesoreria" value="<?=$bus["ci_primero_tesoreria"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_primero_tesoreria" type="text" id="cargo_primero_tesoreria" value="<?=$bus["cargo_primero_tesoreria"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:</p>      </td>
      <td><label>
        <input name="segundo_tesoreria" type="text" id="segundo_tesoreria" value="<?=$bus["segundo_tesoreria"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_segundo_tesoreria" type="text" id="ci_segundo_tesoreria" value="<?=$bus["ci_segundo_tesoreria"]?>" size="16">
      </label></td>
      <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_segundo_tesoreria" type="text" id="cargo_segundo_tesoreria" value="<?=$bus["cargo_segundo_tesoreria"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Tercer nivel:</p>      </td>
      <td><label>
        <input name="tercero_tesoreria" type="text" id="tercero_tesoreria" value="<?=$bus["tercero_tesoreria"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_tercero_tesoreria" type="text" id="ci_tercero_tesoreria" value="<?=$bus["ci_tercero_tesoreria"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_tercero_tesoreria" type="text" id="cargo_tercero_tesoreria" value="<?=$bus["cargo_tercero_tesoreria"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
     <tr>
       <td class="viewPropTitle">N&uacute;mero de Documento de Remisi&oacute;n:</td>
       <td>
       	<input style="text-align:right" name="nro_remision" type="text" id="nro_remision" value="<?=$bus["nro_remision"]?>" size="10">
       </td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
    <tr>
       <td class="viewPropTitle">Controlar chequeras y numeros de cheque</td>
       <td colspan="5"><label>
         <input type="checkbox" name="controlar_cheques" id="controlar_cheques" value="1" <? if($bus["controlar_cheques"] == '1'){ echo "checked";}?>>
       </label></td>
    </tr>
   	<tr>
       <td class="viewPropTitle">N&uacute;mero de Relaci&oacute;n Tributos:</td>
       <td>
       	<input style="text-align:right" name="nro_relacion" type="text" id="nro_relacion" value="<?=$bus["relacion_comprobantes"]?>" size="10">
       </td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
    <tr>
      <td colspan="6"><div align="center">
        <input type="submit" name="button" id="button" value="Actualizar" class="button" />
        <input type="reset" name="button2" id="button2" value="Cancelar" class="button" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>