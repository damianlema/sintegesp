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
    	<td>Tipo de Nomina</td>
        <td>
          <select name="tipo_nomina" id="tipo_nomina">
          	<?
            $sql_consultar = mysql_query("select * from tipo_nomina");
				while($bus_consultar= mysql_fetch_array($sql_consultar)){
					?>
					<option value="<?=$bus_consultar["idtipo_nomina"]?>"><?=$bus_consultar["titulo_nomina"]?></option>
					<?	
				}
			?>
            
          </select>
        </td>
        <td>Tipo de Hoja de Tiempo</td>
        <td>
          <select name="tipo_hoja_tiempo" id="tipo_hoja_tiempo">
          	<?
            $sql_consultar = mysql_query("select * from tipo_hoja_tiempo");
				while($bus_consultar= mysql_fetch_array($sql_consultar)){
					?>
					<option value="<?=$bus_consultar["idtipo_hoja_tiempo"]?>"><?=$bus_consultar["descripcion"]?></option>
					<?	
				}
			?>
            
          </select>
        </td>
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){
$sql_consulta = mysql_query("select tht.descripcion,
									tn.titulo_nomina,
									p.desde,
									p.hasta,
									ht.idtipo_hoja_tiempo,
									ht.idtipo_nomina,
									ht.periodo,
									ht.idhoja_tiempo
										FROM
									tipo_hoja_tiempo tht,
									tipo_nomina tn,
									rango_periodo_nomina p,
									hoja_tiempo ht
										WHERE
									ht.idtipo_nomina ='".$_POST["tipo_nomina"]."'
									and tht.idtipo_hoja_tiempo ='".$_POST["tipo_hoja_tiempo"]."'
									and tht.idtipo_hoja_tiempo = ht.idtipo_hoja_tiempo
									and tn.idtipo_nomina = ht.idtipo_nomina
									and p.idrango_periodo_nomina = ht.periodo
									group by ht.idhoja_tiempo");

}else{
$sql_consulta = mysql_query("select tht.descripcion,
									tn.titulo_nomina,
									p.desde,
									p.hasta,
									ht.idtipo_hoja_tiempo,
									ht.idtipo_nomina,
									ht.periodo,
									ht.idhoja_tiempo
										FROM
									tipo_hoja_tiempo tht,
									tipo_nomina tn,
									rango_periodo_nomina p,
									hoja_tiempo ht
										WHERE
									tht.idtipo_hoja_tiempo = ht.idtipo_hoja_tiempo
									and tn.idtipo_nomina = ht.idtipo_nomina
									and p.idrango_periodo_nomina = ht.periodo
									group by ht.idhoja_tiempo")or die(mysql_error());

}

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="35%" align="center" class="Browse">Tipo de Hoja de Tiempo</td>
                                    <td width="35%" align="center" class="Browse">Tipo de Nomina</td>
                                    <td width="12%" align="center" class="Browse">Periodo</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onclick="opener.seleccionarPeriodo('<?=$bus_consulta["idtipo_nomina"]?>'), opener.consultarDatosPrincipales('<?=$bus_consulta["idtipo_hoja_tiempo"]?>', '<?=$bus_consulta["idtipo_nomina"]?>', '<?=$bus_consulta["periodo"]?>' , '<?=$bus_consulta["idhoja_tiempo"]?>'), window.close()">
                            <td class='Browse'><?=$bus_consulta["descripcion"]?></td>
                            <td class='Browse'><?=$bus_consulta["titulo_nomina"]?></td>
                            <td class='Browse'>
							<?
                            $desde= explode(" ", $bus_consulta["desde"]);
							$hasta= explode(" ", $bus_consulta["hasta"]);
							
							echo $desde[0]." - ".$hasta[0];
							?></td>
                            <td width="6%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.seleccionarPeriodo('<?=$bus_consulta["idtipo_nomina"]?>'), opener.consultarDatosPrincipales('<?=$bus_consulta["idtipo_hoja_tiempo"]?>', '<?=$bus_consulta["idtipo_nomina"]?>', '<?=$bus_consulta["periodo"]?>', '<?=$bus_consulta["idhoja_tiempo"]?>'), window.close()">
                            </td>
                          </tr>
							<?
							}
							?>
						</table>
                        
					
      </td>
  </tr>
  </table>