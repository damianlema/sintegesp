<?
include("../../../conf/conex.php");
Conectarse();

if($_POST){
$sql_consulta = mysql_query("select * from detalle_catalogo_bienes where denominacion like '%".$_POST["denominacion"]."%'");
}else{
$sql_consulta = mysql_query("select * from detalle_catalogo_bienes");
}


?>
	<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Listado de Detalles</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
    
        <form method="post" action="">
        <input type="hidden" id="destino" name="destino" value="<?=$_REQUEST["destino"]?>">
        <input type="hidden" id="tipo_consulta" name="tipo_consulta" value="<?=$_REQUEST["tipo_consulta"]?>">
        <table align="center">
          <tr>
                <td>Denominación</td>
                <td><input type="text" id="denominacion" name="denominacion"></td>
                <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
            </tr>
        </table>     
        </form>
<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="12%" align="center" class="Browse">C&oacute;digo</td>
									<td width="76%" align="center" class="Browse">Denominaci&oacute;n</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?php
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							if(substr($bus_consulta["codigo"],0,1) == 2){
							?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="
                           <?php
                            if($_REQUEST["destino"] == "movimientos"){
							?>
							opener.document.getElementById('codigo_catalogo_bienes_nuevos').value = '(<?=$bus_consulta["codigo"]?>) <?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '<?=$bus_consulta["iddetalle_catalogo_bienes"]?>', opener.consultarTipoDetalle('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>'), window.close()
							<?php	
							}else{
							?>
							opener.document.getElementById('catalogo_bienes').value = '(<?=$bus_consulta["codigo"]?>) <?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idcatalogo_bienes').value = '<?=$bus_consulta["iddetalle_catalogo_bienes"]?>', 
                            
                            	<?php
                                if($_REQUEST["tipo_consulta"]=="movimiento_bienes"){
                                ?>
                                window.close()
                                <?php
                                }else{
                                ?>
                                 opener.cargarTipo('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>'), window.close()
                                <?php	
                                }	
                            
							}
							?>
                            ">
                            <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                            <td class='Browse' align="left"><?=$bus_consulta["denominacion"]?></td>
                            <td width="6%" align="center" class='Browse'>
                            <img src="../../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="
							<?php
                            if($_REQUEST["destino"] == "movimientos"){
							?>
							opener.document.getElementById('codigo_catalogo_bienes_nuevos').value = '(<?=$bus_consulta["codigo"]?>) <?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idcodigo_catalogo_bienes_nuevos').value = '<?=$bus_consulta["iddetalle_catalogo_bienes"]?>', opener.consultarTipoDetalle('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>'), window.close()
                                <?php	
							}else{
							?>
							onclick="opener.document.getElementById('catalogo_bienes').value = '(<?=$bus_consulta["codigo"]?>) <?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idcatalogo_bienes').value = '<?=$bus_consulta["iddetalle_catalogo_bienes"]?>', 
                            	<?php
                                if($_REQUEST["tipo_consulta"]=="movimiento_bienes"){
                                ?>
                                window.close()
                                <?php
                                }else{
                                ?>
                                 opener.cargarTipo('<?=$bus_consulta["iddetalle_catalogo_bienes"]?>'), window.close()
                                <?php	
                                }	
							}
							?>
                            ">
                                                                
                            </td>
                          </tr>
							<?php
							}
							}
							?>
						</table>
                        
					
      </td>
    </tr>
  </table>