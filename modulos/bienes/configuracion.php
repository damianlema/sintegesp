<?


if($_POST){
extract($_POST);

		
			$sql_actualizar = mysql_query("update configuracion_bienes set iddependencia = '".$dependencias."',
																			primero_bienes = '".$primero_bienes."',
																			ci_primero_bienes = '".$ci_primero_bienes."',
																			cargo_primero_bienes = '".$cargo_primero_bienes."',
																			segundo_bienes = '".$segundo_bienes."',
																			ci_segundo_bienes = '".$ci_segundo_bienes."',
																			cargo_segundo_bienes = '".$cargo_segundo_bienes."',
																			cabecera_linea1 = '".$cabecera_linea1."',
																			cabecera_linea2 = '".$cabecera_linea2."',
																			cabecera_linea3 = '".$cabecera_linea3."',
																			cabecera_linea4 = '".$cabecera_linea4."'
																			")or die(mysql_query());
			$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");																
			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?
}
$sql= mysql_query("select * from configuracion_bienes");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
    </head>
    <body>
	<br>
	<h4 align=center>Configuraci&oacute;n Bienes e Inventarios</h4>
	<h2 class="sqlmVersion"></h2>
	<br>

<form name="form1" method="post" action="">
  <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
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
      </select>      </td>
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
   <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Primer nivel:</p>      </td>
      <td><label>
        <input name="primero_bienes" type="text" id="primero_bienes" value="<?=$bus["primero_bienes"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_primero_bienes" type="text" id="ci_primero_bienes" value="<?=$bus["ci_primero_bienes"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_primero_bienes" type="text" id="cargo_primero_bienes" value="<?=$bus["cargo_primero_bienes"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:</p>      </td>
      <td><label>
        <input name="segundo_bienes" type="text" id="segundo_bienes" value="<?=$bus["segundo_bienes"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_segundo_bienes" type="text" id="ci_segundo_bienes" value="<?=$bus["ci_segundo_bienes"]?>" size="16">
      </label></td>
      <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_segundo_bienes" type="text" id="cargo_segundo_bienes" value="<?=$bus["cargo_segundo_bienes"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
       <td class="viewPropTitle"><p align="right">Encabezado linea 1:</p>      </td>
       <td colspan="5"><input name="cabecera_linea1" type="text" id="cabecera_linea1" value="<?=$bus["cabecera_linea1"]?>" size="70"></td>
    </tr>
     <tr>
       <td class="viewPropTitle"><p align="right">Encabezado linea 2:</p>      </td>
       <td colspan="5"><input name="cabecera_linea2" type="text" id="cabecera_linea2" value="<?=$bus["cabecera_linea2"]?>" size="70"></td>
    </tr>
     <tr>
       <td class="viewPropTitle"><p align="right">Encabezado linea 3:</p>      </td>
       <td colspan="5"><input name="cabecera_linea3" type="text" id="cabecera_linea3" value="<?=$bus["cabecera_linea3"]?>" size="70"></td>
    </tr>
    <tr>
       <td class="viewPropTitle"><p align="right">Encabezado linea 4:</p>      </td>
       <td colspan="5"><input name="cabecera_linea4" type="text" id="cabecera_linea4" value="<?=$bus["cabecera_linea4"]?>" size="70"></td>
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