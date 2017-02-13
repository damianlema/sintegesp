<?
include("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Listado de Desincorporación</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
<form method="post" action="">
<table align="center">
  <tr>
    	<td>Numero de Planilla / Justificacion</td>
        <td><input type="text" id="campo_buscar" name="campo_buscar"></td>
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){

	$sql_consulta = mysql_query("select *
										from
										desincorporacion_bienes
										where
									numero_planilla like '%".$_POST["campo_buscar"]."%'
									or justificacion like '%".$_POST["campo_buscar"]."%'");



?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="98%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
								  <td width="15%" align="center" class="Browse">Numero de Planilla</td>
                                  <td width="13%" align="center" class="Browse">Fecha de Proceso</td>
                                    <td width="61%" align="center" class="Browse">Justificacion</td>
                                  <td width="6%" align="center" class="Browse">Estado</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
						  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarDesincorporacion(<?=$bus_consulta["iddesincorporacion_bienes"]?>), window.close()">
                           <td width="15%" align="center" class="Browse">&nbsp;
						   <?
                           if($bus_consulta["numero_planilla"] == "0" or $bus_consulta["numero_planilla"] == ""){
						   	echo "<strong>No Generado</strong>";
						   }else{
						   	echo $bus_consulta["numero_planilla"];
						   }
						   ?></td>
                            <td width="13%" align="center" class="Browse">&nbsp;<?=$bus_consulta["fecha_proceso"]?></td>
                            <td width="61%" align="right" class="Browse">&nbsp;<?=$bus_consulta["justificacion"]?></td>
                                    <td width="6%" align="center" class="Browse">&nbsp;
									<?
                                    if($bus_consulta["estado"]== "anulado"){
										echo "<strong>".$bus_consulta["estado"]."</strong>";
									}else{
										echo $bus_consulta["estado"];
									}
									?></td>
                            <td width="5%" align="center" class='Browse'>
                              <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.consultarDesincorporacion(<?=$bus_consulta["iddesincorporacion_bienes"]?>), window.close()">                            </td>
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