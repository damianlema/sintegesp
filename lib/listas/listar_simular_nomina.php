<?
include("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Nominas Simuladas</h4>
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
        <td><input type="submit" id="buscar" name="buscar" value="Buscar" class="button"></td>
    </tr>
</table>     
</form>

<?

if($_POST){
$sql_consulta = mysql_query("select gn.descripcion,
									tn.titulo_nomina,
									rpn.desde,
									rpn.hasta,
									gn.estado,
									gn.idsimular_nomina,
									gn.fecha_elaboracion,
									gn.fecha_procesado,
									gn.idtipo_nomina,
									gn.idperiodo,
									gn.idcertificacion_simular_nomina,
									oc.numero_orden
										FROM
									simular_nomina gn,
									tipo_nomina tn,
									rango_periodo_nomina rpn,
									beneficiarios be,
									certificacion_simular_nomina oc
										WHERE
									gn.idtipo_nomina = '".$_POST["tipo_nomina"]."'
									and tn.idtipo_nomina = gn.idtipo_nomina
									and rpn.idrango_periodo_nomina = gn.idperiodo
									and gn.idcertificacion_simular_nomina = oc.idcertificacion_simular_nomina
									group by gn.idsimular_nomina");

}else{
$sql_consulta = mysql_query("select gn.descripcion,
									tn.titulo_nomina,
									rpn.desde,
									rpn.hasta,
									gn.estado,
									gn.idsimular_nomina,
									gn.fecha_elaboracion,
									gn.fecha_procesado,
									gn.idtipo_nomina,
									gn.idperiodo,
									gn.idcertificacion_simular_nomina,
									oc.numero_orden
										FROM
									simular_nomina gn,
									tipo_nomina tn,
									rango_periodo_nomina rpn,
									certificacion_simular_nomina oc
										WHERE
									tn.idtipo_nomina = gn.idtipo_nomina
									and rpn.idrango_periodo_nomina = gn.idperiodo
									and gn.idcertificacion_simular_nomina = oc.idcertificacion_simular_nomina
									group by gn.idsimular_nomina") or die (mysql_error());

}

?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
<tr>
					<td align="center">
                    
                    
						<table width="80%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
			  				<thead>
								<tr>
									<td width="18%" align="center" class="Browse">Tipo de Nomina</td>
                                    <td width="24%" align="center" class="Browse">Periodo</td>
                                  <td align="center" class="Browse">Acci&oacute;n</td>
								</tr>
							</thead>
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
							?>
							<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.consultarGenerarNomina('<?=$bus_consulta["idsimular_nomina"]?>', '<?=$bus_consulta["idtipo_nomina"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["idperiodo"]?>', '<?=$bus_consulta["fecha_elaboracion"]?>', '<?=$bus_consulta["fecha_procesado"]?>','<?=$bus_consulta["estado"]?>','<?=$bus_consulta["nombre"]?>','<?=$bus_consulta["idbeneficiarios"]?>', '<?=$bus_consulta["idcertificacion_simular_nomina"]?>', '<?=$bus_consulta["numero_orden"]?>'), window.close()">
                            <td class='Browse'><?=$bus_consulta["titulo_nomina"]?></td>
                            <td align="center" class='Browse'>
							<?
                            $desde= explode(" ", $bus_consulta["desde"]);
							$hasta= explode(" ", $bus_consulta["hasta"]);
							
							echo $desde[0]." - ".$hasta[0];
							?>                            </td>
                            <td width="5%" align="center" class='Browse'>
                                <img src="../../imagenes/validar.png" 
                                style="cursor:pointer"
                                onclick="opener.consultarGenerarNomina('<?=$bus_consulta["idsimular_nomina"]?>', '<?=$bus_consulta["idtipo_nomina"]?>', '<?=$bus_consulta["descripcion"]?>', '<?=$bus_consulta["idperiodo"]?>', '<?=$bus_consulta["fecha_elaboracion"]?>', '<?=$bus_consulta["fecha_procesado"]?>','<?=$bus_consulta["estado"]?>','<?=$bus_consulta["nombre"]?>','<?=$bus_consulta["idbeneficiarios"]?>', '<?=$bus_consulta["idcertificacion_simular_nomina"]?>', '<?=$bus_consulta["numero_orden"]?>'), window.close()">                            </td>
                          </tr>
							<?
							}
							?>
						</table>
                        
					
      </td>
  </tr>
  </table>