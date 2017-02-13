<?
include("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Listado de Detalles</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" action="">
<table align="center">
  <tr>
    	<td>Tipo de Contribuyente<td>
        <td>
        <select id="tipo_contribuyente" name="tipo_contribuyente">
            <option value="0" <? if($_POST["tipo_contribuyente"] == '0'){ echo "selected";}?>>Todos</option>
            <option value="pj" <? if($_POST["tipo_contribuyente"] == 'pj'){ echo "selected";}?>>Persona Juridica</option>
            <option value="pn" <? if($_POST["tipo_contribuyente"] == 'pn'){ echo "selected";}?>>Persona Natural</option>
            <option value="ve" <? if($_POST["tipo_contribuyente"] == 've'){ echo "selected";}?>>Vehiculo</option>
        </select>
        </td>
        <td>Nombre / RIF</td>
        <td><input type="text" id="campo_buscar" name="campo_buscar"></td>
        
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){

	if($_POST["tipo_contribuyente"] == "0"){
		$sql_consulta = mysql_query("select razon_social,
									rif,
									nro_placa,
									propietario,
									tipo_contribuyente,
									idcontribuyente
										from 
									contribuyente
										where
									(razon_social like '%".$_POST["campo_buscar"]."%'
									and rif like '%".$_POST["campo_buscar"]."%') or
									(propietario like '%".$_POST["campo_buscar"]."%'
									and nro_placa like '%".$_POST["campo_buscar"]."%')");
	}else{
		
		$sql_consulta = mysql_query("select razon_social,
									rif,
									nro_placa,
									propietario,
									tipo_contribuyente,
									idcontribuyente
										from 
									contribuyente
										where
									((razon_social like '%".$_POST["campo_buscar"]."%'
									and rif like '%".$_POST["campo_buscar"]."%') or
									(propietario like '%".$_POST["campo_buscar"]."%'
									and nro_placa like '%".$_POST["campo_buscar"]."%'))
									and tipo_contribuyente = '".$_POST["tipo_contribuyente"]."'");
	}



?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="70%" align="center" class="Browse">Nombre / Razon Social</td>
									<td width="30%" align="center" class="Browse">RIF</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="
                            <?
                            if($_GET["url"] == "solicitud_calculo"){
								?>
								opener.consultarContribuyente('<?=$bus_consulta["idcontribuyente"]?>'), window.close()
								<?
							}else{
							?>
								opener.document.getElementById('tipo_contribuyente').value = '<?=$bus_consulta["tipo_contribuyente"]?>', opener.document.getElementById('idcontribuyente').value = '<?=$bus_consulta["idcontribuyente"]?>', opener.document.getElementById('idcontribuyente_imagen').value = '<?=$bus_consulta["idcontribuyente"]?>', opener.verificarId(), opener.consultarContribuyente(), opener.consultarRegistroMercantil(), opener.document.getElementById('nombre_buscar').value = '<? if($bus_consulta["tipo_contribuyente"] == 've'){ echo $bus_consulta["propietario"]; }else{ echo $bus_consulta["razon_social"]; }?>' ,opener.document.getElementById('rif_buscar').value = '<? if($bus_consulta["tipo_contribuyente"] == 've'){ echo $bus_consulta["nro_placa"]; }else{ echo $bus_consulta["rif"]; }?>', window.close()
							<?
							}
							?>
                            ">
                            <td class='Browse'>
							<?
                            if($bus_consulta["tipo_contribuyente"] == 've'){
								echo $bus_consulta["propietario"];	
							}else{
								echo $bus_consulta["razon_social"];	
							}
							?></td>
                            <td class='Browse'>
                            <?
                            if($bus_consulta["tipo_contribuyente"] == 've'){
								echo $bus_consulta["nro_placa"];	
							}else{
								echo $bus_consulta["rif"];	
							}
							?>
                            </td>
                            <td width="6%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="
                                
                                <?
                            if($_GET["url"] == "solicitud_calculo"){
								?>
								opener.consultarContribuyente('<?=$bus_consulta["idcontribuyente"]?>'), window.close()
								<?
							}else{
							?>
								opener.document.getElementById('tipo_contribuyente').value = '<?=$bus_consulta["tipo_contribuyente"]?>', opener.document.getElementById('idcontribuyente').value = '<?=$bus_consulta["idcontribuyente"]?>', opener.document.getElementById('idcontribuyente_imagen').value = '<?=$bus_consulta["idcontribuyente"]?>', opener.verificarId(), opener.consultarContribuyente(), opener.consultarRegistroMercantil(), opener.document.getElementById('nombre_buscar').value = '<? if($bus_consulta["tipo_contribuyente"] == 've'){ echo $bus_consulta["propietario"]; }else{ echo $bus_consulta["razon_social"]; }?>',opener.document.getElementById('rif_buscar').value = '<? if($bus_consulta["tipo_contribuyente"] == 've'){ echo $bus_consulta["nro_placa"]; }else{ echo $bus_consulta["rif"]; }?>', window.close()
							<?
							}
							?>
                                
                                ">                                
                            </td>
                          </tr>
							<?
							}
							?>
						</table>
                        
					
      </td>
    </tr>
  </table>
  
  <?
  }
  ?>