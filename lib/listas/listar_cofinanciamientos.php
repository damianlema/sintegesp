<?
include("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Lista de Cofinanciamientos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" action="">
<table align="center">
  <tr>
    	<td>Buscar</td>
        <td>
          <input type="text" name="campoBuscar" id="campoBuscar">
        </td>
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){
$sql_consulta = mysql_query("select * from cofinanciamiento
										where denominacion like '%".$_POST["campoBuscar"]."%'");

}else{
$sql_consulta = mysql_query("select * from cofinanciamiento");

}

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="18%" align="center" class="Browse">Denominacion</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.document.getElementById('denominacion').value = '<?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idcofinanciamiento').value = '<?=$bus_consulta["idcofinanciamiento"]?>', opener.listarFuentes('<?=$bus_consulta["idcofinanciamiento"]?>'), opener.document.getElementById('tabla_fuentes').style.display = 'block', window.close()">
                            <td class='Browse'><?=$bus_consulta["denominacion"]?></td>
                            <td width="5%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.document.getElementById('denominacion').value = '<?=$bus_consulta["denominacion"]?>', opener.document.getElementById('idcofinanciamiento').value = '<?=$bus_consulta["idcofinanciamiento"]?>', opener.listarFuentes('<?=$bus_consulta["idcofinanciamiento"]?>'), opener.document.getElementById('tabla_fuentes').style.display = 'block',  window.close()">                                
                            </td>
                          </tr>
							<?
							}
							?>
						</table>
                        
					
      </td>
  </tr>
  </table>