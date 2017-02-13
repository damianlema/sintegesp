<?

$sql_configuracion=mysql_query("select * from configuracion_contabilidad where status='a'"
                      ,$conexion_db);
$registro_configuracion=mysql_fetch_assoc($sql_configuracion);

if($_POST){
extract($_POST);
			$sql_actualizar = mysql_query("update configuracion_contabilidad set primero_contabilidad = '".$primero_contabilidad."',
																			ci_primero_contabilidad = '".$ci_primero_contabilidad."',
																			cargo_primero_contabilidad = '".$cargo_primero_contabilidad."',
																			segundo_contabilidad = '".$segundo_contabilidad."',
																			ci_segundo_contabilidad = '".$ci_segundo_contabilidad."',
																			cargo_segundo_contabilidad = '".$cargo_segundo_contabilidad."',
																			tercero_contabilidad = '".$tercero_contabilidad."',
																			ci_tercero_contabilidad = '".$ci_tercero_contabilidad."',
																			cargo_tercero_contabilidad = '".$cargo_tercero_contabilidad."',
																			nro_remision = '".$nro_remision."',
																			iddependencia = '".$dependencias."', 
																			status = 'a',
																			usuario= '".$login."',
																			fechayhora= '".$fh."',
                                      elaborado_por= '".$elabora_contabilidad."',
                                      ci_elaborado_por= '".$ci_elaborado_por."',
                                      cargo_elaborado_por= '".$cargo_elaborado_por."',
                                      conformado_por= '".$conforma_contabilidad."',
                                      ci_conformado_por= '".$ci_conformado_por."',
                                      cargo_conformado_por= '".$cargo_conformado_por."',
                                      aprobado_por= '".$aprueba_contabilidad."',
                                      ci_aprobado_por= '".$ci_aprobado_por."',
                                      cargo_aprobado_por= '".$cargo_aprobado_por."'
																			")or die(mysql_query());
			
			
			$sql_actualizar_dependencia = mysql_query("update dependencias set idmodulo = '".$_SESSION["modulo"]."' where iddependencia = '".$dependencias."'");
			
			?>
			<script>
			mostrarMensajes("exito", "La configuracion ha sido Guardada con Exito");
			</script>
			<?															
}
$sql= mysql_query("select * from configuracion_contabilidad");
$bus = mysql_fetch_array($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<title>Gesti&oacute;n P&uacute;blica</title>
    </head>
    <body>
	<br>
	<h4 align=center>Configuraci&oacute;n Contabilidad</h4>
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
        <input name="primero_contabilidad" type="text" id="primero_contabilidad" value="<?=$bus["primero_contabilidad"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_primero_contabilidad" type="text" id="ci_primero_contabilidad" value="<?=$bus["ci_primero_contabilidad"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_primero_contabilidad" type="text" id="cargo_primero_contabilidad" value="<?=$bus["cargo_primero_contabilidad"]?>" size="60">
      </label></td>
    </tr>
    <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Segundo nivel:</p>      </td>
      <td><label>
        <input name="segundo_contabilidad" type="text" id="segundo_contabilidad" value="<?=$bus["segundo_contabilidad"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_segundo_contabilidad" type="text" id="ci_segundo_contabilidad" value="<?=$bus["ci_segundo_contabilidad"]?>" size="16">
      </label></td>
      <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_segundo_contabilidad" type="text" id="cargo_segundo_contabilidad" value="<?=$bus["cargo_segundo_contabilidad"]?>" size="60">
      </label></td>
    </tr>
     <tr>
      <td class="viewPropTitle"><p align="right">Nombre y Apellidos Tercer nivel:</p>      </td>
      <td><label>
        <input name="tercero_contabilidad" type="text" id="tercero_contabilidad" value="<?=$bus["tercero_contabilidad"]?>" size="45">
      </label></td>
      <td width="42" class="viewPropTitle"><div align="right">C.I.: </div></td>
      <td width="109"><label>
        <input name="ci_tercero_contabilidad" type="text" id="ci_tercero_contabilidad" value="<?=$bus["ci_tercero_contabilidad"]?>" size="16">
      </label></td>
       <td width="57" class="viewPropTitle"><div align="right">Cargo: </div></td>
      <td width="388"><label>
        <input name="cargo_tercero_contabilidad" type="text" id="cargo_tercero_contabilidad" value="<?=$bus["cargo_tercero_contabilidad"]?>" size="60">
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
    </tr>
     <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"><strong>FIRMAS COMPROBANTES CONTABLES</strong></td>
    </tr>
    <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
        <td align='right' class='viewPropTitle'>Elaborado por: </td>
        <td colspan="3" class=''>
              <input type="hidden" name="ci_elaborado_por" id="ci_elaborado_por" value="<?=$registro_configuracion["ci_elaborado_por"]?>">
              <input type="hidden" name="cargo_elaborado_por" id="cargo_elaborado_por" value="<?=$registro_configuracion["cargo_elaborado_por"]?>">
              
              <select name="elabora_contabilidad" id="elabora_contabilidad">
                <option>..:: Seleccione ::..</option>
                <?
                $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion")or die(mysql_error());
                $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
             
                if($bus_configuracion_administracion["primero_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_administracion"]){ echo "selected"; }?> 
                        id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>',
                                document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_administracion"]?>'">
                        <?=$bus_configuracion_administracion["primero_administracion"]?>
                    </option>
               <? } 

               if($bus_configuracion_administracion["segundo_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_administracion"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>',
                              document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_administracion"]?>'">
                        <?=$bus_configuracion_administracion["segundo_administracion"]?>
                    </option>
               <? } 

               if($bus_configuracion_administracion["tercero_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_administracion"]){ echo "selected"; }?>
                       id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
                       onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>',
                        document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_administracion"]?>'">
                        <?=$bus_configuracion_administracion["tercero_administracion"]?>
                    </option>
                <? } 

              $sql_configuracion_administracion = mysql_query("select * from configuracion_compras")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              
              if($bus_configuracion_administracion["primero_compras"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_compras"]?>'">
                        <?=$bus_configuracion_administracion["primero_compras"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["segundo_compras"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_compras"]?>'">
                          <?=$bus_configuracion_administracion["segundo_compras"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["tercero_compras"] != ''){
                  ?>       
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_compras"]?>'">
                            <?=$bus_configuracion_administracion["tercero_compras"]?>
                    </option>
              <? } 
              $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              
              if($bus_configuracion_administracion["primero_rrhh"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["primero_rrhh"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["segundo_rrhh"] != ''){
              ?>

                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["tercero_rrhh"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                    </option>
              <? }

              $sql_configuracion_nomina = mysql_query("select * from configuracion_nomina")or die(mysql_error());
              $bus_configuracion_nomina = mysql_fetch_array($sql_configuracion_nomina);
              
              if($bus_configuracion_nomina["primero_nomina"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_nomina["primero_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["primero_nomina"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_nomina["ci_primero_nomina"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_nomina["cargo_primero_nomina"]?>'">
                            <?=$bus_configuracion_nomina["primero_nomina"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_nomina["segundo_nomina"] != ''){
              ?>

                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_nomina["segundo_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["segundo_nomina"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_nomina["ci_segundo_nomina"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_nomina["cargo_segundo_nomina"]?>'">
                            <?=$bus_configuracion_nomina["segundo_nomina"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_nomina["tercero_nomina"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_nomina["tercero_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["tercero_nomina"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_nomina["ci_tercero_nomina"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_nomina["cargo_tercero_nomina"]?>'">
                            <?=$bus_configuracion_nomina["tercero_nomina"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_contabilidad"]?>'">
                          <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                    </option>
              <? }
             
              if($bus_configuracion_administracion["segundo_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_contabilidad"]?>'">
                            <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                    </option>
              <? }
             
              if($bus_configuracion_administracion["tercero_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_contabilidad"]?>'">
                            <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_tributos"]?>'">
                            <?=$bus_configuracion_administracion["primero_tributos"]?>
                    </option>
              <? }
              
              if($bus_configuracion_administracion["segundo_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_tributos"]?>'">
                            <?=$bus_configuracion_administracion["segundo_tributos"]?>
                    </option>
              <? }
              
              if($bus_configuracion_administracion["tercero_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_tributos"]?>'">
                            <?=$bus_configuracion_administracion["tercero_tributos"]?>
                    </option>  
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_despacho"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_despacho"]?>'">
                            <?=$bus_configuracion_administracion["primero_despacho"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_despacho"]?>'">
                            <?=$bus_configuracion_administracion["segundo_despacho"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_despacho"]?>'">
                            <?=$bus_configuracion_administracion["tercero_despacho"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_obras")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["primero_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_obras"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_primero_obras"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_primero_obras"]?>'">
                            <?=$bus_configuracion_administracion["primero_obras"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["segundo_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_obras"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_segundo_obras"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_obras"]?>'">
                            <?=$bus_configuracion_administracion["segundo_obras"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["elaborado_por"] == $bus_configuracion_administracion["tercero_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_obras"]?>"
                        onClick="document.getElementById('ci_elaborado_por').value='<?=$bus_configuracion_administracion["ci_tercero_obras"]?>',
                          document.getElementById('cargo_elaborado_por').value='<?=$bus_configuracion_administracion["cargo_tecero_obras"]?>'">
                            <?=$bus_configuracion_administracion["tercero_obras"]?>
                    </option>
              <? } ?>
              </select>              
          </td>
      </tr>

      <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>
    <tr>
        <td align='right' class='viewPropTitle'>Conformado por: </td>
        <td colspan="3" class=''>
              <input type="hidden" name="ci_conformado_por" id="ci_conformado_por" value="<?=$registro_configuracion["ci_conformado_por"]?>">
              <input type="hidden" name="cargo_conformado_por" id="cargo_conformado_por" value="<?=$registro_configuracion["cargo_conformado_por"]?>">
              
              <select name="conforma_contabilidad" id="conforma_contabilidad">
                <option>..:: Seleccione ::..</option>
                <?
                $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion")or die(mysql_error());
                $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
             
                if($bus_configuracion_administracion["primero_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_administracion"]){ echo "selected"; }?> 
                        id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>',
                                document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_administracion"]?>'">
                        <?=$bus_configuracion_administracion["primero_administracion"]?>
                    </option>
               <? } 

               if($bus_configuracion_administracion["segundo_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_administracion"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>',
                              document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_administracion"]?>'">
                        <?=$bus_configuracion_administracion["segundo_administracion"]?>
                    </option>
               <? } 

               if($bus_configuracion_administracion["tercero_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_administracion"]){ echo "selected"; }?>
                       id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
                       onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>',
                        document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_administracion"]?>'">
                        <?=$bus_configuracion_administracion["tercero_administracion"]?>
                    </option>
                <? } 

              $sql_configuracion_administracion = mysql_query("select * from configuracion_compras")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              
              if($bus_configuracion_administracion["primero_compras"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_compras"]?>'">
                        <?=$bus_configuracion_administracion["primero_compras"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["segundo_compras"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_compras"]?>'">
                          <?=$bus_configuracion_administracion["segundo_compras"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["tercero_compras"] != ''){
                  ?>       
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_compras"]?>'">
                            <?=$bus_configuracion_administracion["tercero_compras"]?>
                    </option>
              <? } 
              $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              
              if($bus_configuracion_administracion["primero_rrhh"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["primero_rrhh"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["segundo_rrhh"] != ''){
              ?>

                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["tercero_rrhh"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                    </option>
              <? }

              $sql_configuracion_nomina = mysql_query("select * from configuracion_nomina")or die(mysql_error());
              $bus_configuracion_nomina = mysql_fetch_array($sql_configuracion_nomina);
              
              if($bus_configuracion_nomina["primero_nomina"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_nomina["primero_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["primero_nomina"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_nomina["ci_primero_nomina"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_nomina["cargo_primero_nomina"]?>'">
                            <?=$bus_configuracion_nomina["primero_nomina"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_nomina["segundo_nomina"] != ''){
              ?>

                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_nomina["segundo_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["segundo_nomina"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_nomina["ci_segundo_nomina"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_nomina["cargo_segundo_nomina"]?>'">
                            <?=$bus_configuracion_nomina["segundo_nomina"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_nomina["tercero_nomina"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_nomina["tercero_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["tercero_nomina"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_nomina["ci_tercero_nomina"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_nomina["cargo_tercero_nomina"]?>'">
                            <?=$bus_configuracion_nomina["tercero_nomina"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_contabilidad"]?>'">
                          <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                    </option>
              <? }
             
              if($bus_configuracion_administracion["segundo_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_contabilidad"]?>'">
                            <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                    </option>
              <? }
             
              if($bus_configuracion_administracion["tercero_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_contabilidad"]?>'">
                            <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_tributos"]?>'">
                            <?=$bus_configuracion_administracion["primero_tributos"]?>
                    </option>
              <? }
              
              if($bus_configuracion_administracion["segundo_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_tributos"]?>'">
                            <?=$bus_configuracion_administracion["segundo_tributos"]?>
                    </option>
              <? }
              
              if($bus_configuracion_administracion["tercero_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_tributos"]?>'">
                            <?=$bus_configuracion_administracion["tercero_tributos"]?>
                    </option>  
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_despacho"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_despacho"]?>'">
                            <?=$bus_configuracion_administracion["primero_despacho"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_despacho"]?>'">
                            <?=$bus_configuracion_administracion["segundo_despacho"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_despacho"]?>'">
                            <?=$bus_configuracion_administracion["tercero_despacho"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_obras")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["primero_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_obras"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_primero_obras"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_primero_obras"]?>'">
                            <?=$bus_configuracion_administracion["primero_obras"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["segundo_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_obras"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_segundo_obras"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_obras"]?>'">
                            <?=$bus_configuracion_administracion["segundo_obras"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["conformado_por"] == $bus_configuracion_administracion["tercero_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_obras"]?>"
                        onClick="document.getElementById('ci_conformado_por').value='<?=$bus_configuracion_administracion["ci_tercero_obras"]?>',
                          document.getElementById('cargo_conformado_por').value='<?=$bus_configuracion_administracion["cargo_tecero_obras"]?>'">
                            <?=$bus_configuracion_administracion["tercero_obras"]?>
                    </option>
              <? } ?>
              </select>              
          </td>
      </tr>
    <tr>
      <td width="316">&nbsp;</td>
      <td width="270">&nbsp;</td>
    </tr>

    <tr>
        <td align='right' class='viewPropTitle'>Aprobado por: </td>
        <td colspan="3" class=''>
              <input type="hidden" name="ci_aprobado_por" id="ci_aprobado_por" value="<?=$registro_configuracion["ci_aprobado_por"]?>">
              <input type="hidden" name="cargo_aprobado_por" id="cargo_aprobado_por" value="<?=$registro_configuracion["cargo_aprobado_por"]?>">
              
              <select name="aprueba_contabilidad" id="aprueba_contabilidad">
                <option>..:: Seleccione ::..</option>
                <?
                $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion")or die(mysql_error());
                $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
             
                if($bus_configuracion_administracion["primero_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_administracion"]){ echo "selected"; }?> 
                        id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_administracion"]?>',
                                document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_administracion"]?>'">
                        <?=$bus_configuracion_administracion["primero_administracion"]?>
                    </option>
               <? } 

               if($bus_configuracion_administracion["segundo_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_administracion"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_administracion"]?>',
                              document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_administracion"]?>'">
                        <?=$bus_configuracion_administracion["segundo_administracion"]?>
                    </option>
               <? } 

               if($bus_configuracion_administracion["tercero_administracion"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_administracion"]){ echo "selected"; }?>
                       id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
                       onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_administracion"]?>',
                        document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_administracion"]?>'">
                        <?=$bus_configuracion_administracion["tercero_administracion"]?>
                    </option>
                <? } 

              $sql_configuracion_administracion = mysql_query("select * from configuracion_compras")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              
              if($bus_configuracion_administracion["primero_compras"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_compras"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_compras"]?>'">
                        <?=$bus_configuracion_administracion["primero_compras"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["segundo_compras"] != ''){
                  ?> 
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_compras"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_compras"]?>'">
                          <?=$bus_configuracion_administracion["segundo_compras"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["tercero_compras"] != ''){
                  ?>       
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_compras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_compras"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_compras"]?>'">
                            <?=$bus_configuracion_administracion["tercero_compras"]?>
                    </option>
              <? } 
              $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              
              if($bus_configuracion_administracion["primero_rrhh"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_rrhh"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["primero_rrhh"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["segundo_rrhh"] != ''){
              ?>

                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_rrhh"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_administracion["tercero_rrhh"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_rrhh"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_rrhh"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_rrhh"]?>'">
                            <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                    </option>
              <? }

              $sql_configuracion_nomina = mysql_query("select * from configuracion_nomina")or die(mysql_error());
              $bus_configuracion_nomina = mysql_fetch_array($sql_configuracion_nomina);
              
              if($bus_configuracion_nomina["primero_nomina"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_nomina["primero_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["primero_nomina"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_nomina["ci_primero_nomina"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_nomina["cargo_primero_nomina"]?>'">
                            <?=$bus_configuracion_nomina["primero_nomina"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_nomina["segundo_nomina"] != ''){
              ?>

                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_nomina["segundo_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["segundo_nomina"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_nomina["ci_segundo_nomina"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_nomina["cargo_segundo_nomina"]?>'">
                            <?=$bus_configuracion_nomina["segundo_nomina"]?>
                    </option>
              <? } 
              
              if($bus_configuracion_nomina["tercero_nomina"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_nomina["tercero_nomina"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_nomina["tercero_nomina"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_nomina["ci_tercero_nomina"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_nomina["cargo_tercero_nomina"]?>'">
                            <?=$bus_configuracion_nomina["tercero_nomina"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_contabilidad"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_contabilidad"]?>'">
                          <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                    </option>
              <? }
             
              if($bus_configuracion_administracion["segundo_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_contabilidad"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_contabilidad"]?>'">
                            <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                    </option>
              <? }
             
              if($bus_configuracion_administracion["tercero_contabilidad"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_contabilidad"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_contabilidad"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_contabilidad"]?>'">
                            <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_presupuesto"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_presupuesto"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_presupuesto"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_presupuesto"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_presupuesto"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_presupuesto"]?>'">
                            <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_tesoreria"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_tesoreria"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_tesoreria"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_tesoreria"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_tesoreria"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_tesoreria"]?>'">
                            <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_tributos"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_tributos"]?>'">
                            <?=$bus_configuracion_administracion["primero_tributos"]?>
                    </option>
              <? }
              
              if($bus_configuracion_administracion["segundo_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_tributos"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_tributos"]?>'">
                            <?=$bus_configuracion_administracion["segundo_tributos"]?>
                    </option>
              <? }
              
              if($bus_configuracion_administracion["tercero_tributos"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_tributos"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_tributos"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_tributos"]?>'">
                            <?=$bus_configuracion_administracion["tercero_tributos"]?>
                    </option>  
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_despacho")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_despacho"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_despacho"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_despacho"]?>'">
                            <?=$bus_configuracion_administracion["primero_despacho"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_despacho"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_despacho"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_despacho"]?>'">
                            <?=$bus_configuracion_administracion["segundo_despacho"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_despacho"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_despacho"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_despacho"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_despacho"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tercero_despacho"]?>'">
                            <?=$bus_configuracion_administracion["tercero_despacho"]?>
                    </option>
              <? }

              $sql_configuracion_administracion = mysql_query("select * from configuracion_obras")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);

              if($bus_configuracion_administracion["primero_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["primero_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["primero_obras"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_primero_obras"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_primero_obras"]?>'">
                            <?=$bus_configuracion_administracion["primero_obras"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["segundo_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["segundo_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["segundo_obras"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_segundo_obras"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_segundo_obras"]?>'">
                            <?=$bus_configuracion_administracion["segundo_obras"]?>
                    </option>
              <? }

              if($bus_configuracion_administracion["tercero_obras"] != ''){
              ?>
                    <option <? if($registro_configuracion["aprobado_por"] == $bus_configuracion_administracion["tercero_obras"]){ echo "selected"; }?>
                        id="<?=$bus_configuracion_administracion["tercero_obras"]?>"
                        onClick="document.getElementById('ci_aprobado_por').value='<?=$bus_configuracion_administracion["ci_tercero_obras"]?>',
                          document.getElementById('cargo_aprobado_por').value='<?=$bus_configuracion_administracion["cargo_tecero_obras"]?>'">
                            <?=$bus_configuracion_administracion["tercero_obras"]?>
                    </option>
              <? } ?>
              </select>              
          </td>
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