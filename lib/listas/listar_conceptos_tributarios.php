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
    	<td>Nombre / RIF</td>
        <td><input type="text" id="campo_buscar" name="campo_buscar"></td>
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){
$sql_consulta = mysql_query("select razon_social,
									rif
										from 
									contribuyente
										where
									razon_social like '%".$_POST["campo_buscar"]."%'
									and rif like '%".$_POST["campo_buscar"]."%'");

}else{
$sql_consulta = mysql_query("select * from concepto_tributario");

}

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="70%" align="center" class="Browse">Codigo</td>
									<td width="30%" align="center" class="Browse">Denominacion</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.document.getElementById('idconcepto_tributario_agregar').value = '<?=$bus_consulta["idconcepto_tributario"]?>', opener.document.getElementById('codigo_concepto').value = '<?=$bus_consulta["codigo"]?>', opener.document.getElementById('denominacion_concepto').value = '<?=$bus_consulta["denominacion"]?>', window.close()">
                            <td class='Browse'><?=$bus_consulta["codigo"]?></td>
                            <td class='Browse'><?=$bus_consulta["denominacion"]?></td>
                            <td width="6%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.document.getElementById('idconcepto_tributario_agregar').value = '<?=$bus_consulta["idconcepto_tributario"]?>', opener.document.getElementById('codigo_concepto').value = '<?=$bus_consulta["codigo"]?>', opener.document.getElementById('denominacion_concepto').value = '<?=$bus_consulta["denominacion"]?>', window.close()">                                
                            </td>
                          </tr>
							<?
							}
							?>
						</table>
                        
					
      </td>
    </tr>
  </table>