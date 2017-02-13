<?
include("../../conf/conex.php");
Conectarse();
?>
	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
	<br>
	<h4 align=center>Listado de N&oacute;minas</h4>
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
        <td><input name="buscar_texto" type="text" id="buscar_texto" size="30"</td>
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
									gn.idgenerar_nomina,
									gn.fecha_elaboracion,
									gn.fecha_procesado,
									gn.idtipo_nomina,
									gn.idperiodo,
									gn.idcategoria_programatica,								
									be.nombre,
									be.idbeneficiarios,
									gn.idorden_compra_servicio,
									gn.idorden_compra_servicio_aporte
										FROM
									generar_nomina gn,
									tipo_nomina tn,
									rango_periodo_nomina rpn,
									beneficiarios be
									
										WHERE
									gn.idtipo_nomina = '".$_POST["tipo_nomina"]."'
									and gn.descripcion like '%".$_POST["buscar_texto"]."%'
									and tn.idtipo_nomina = gn.idtipo_nomina
									and rpn.idrango_periodo_nomina = gn.idperiodo
									and be.idbeneficiarios = gn.idbeneficiarios
									
									group by gn.idgenerar_nomina");
									
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" class="Main">
	<tr>
		<td align="center">
			<table width="98%" border="0" align=center cellpadding="0" cellspacing="0" class="Browse">
  				<thead>
					<tr>
						<td width="18%" align="center" class="Browse">Tipo de Nomina</td>
                        <td width="42%" align="center" class="Browse">Descripcion</td>
                        <td width="24%" align="center" class="Browse">Periodo</td>
                        <td width="24%" align="center" class="Browse">Beneficiario</td>
                        <td width="11%" align="center" class="Browse">Estado</td>
                      <td align="center" class="Browse">Acci&oacute;n</td>
					</tr>
				</thead>
				<?
                while($bus_consulta = mysql_fetch_array($sql_consulta)){
					$desde= explode(" ", $bus_consulta["desde"]);
					$hasta= explode(" ", $bus_consulta["hasta"]);

                	if ($bus_consulta["idcategoria_programatica"] != 0 && $bus_consulta["idcategoria_programatica"] != ''){
                		$sql_categoria = mysql_query("select unidad_ejecutora.denominacion as denounidadejecutora
                						from categoria_programatica, unidad_ejecutora
									where categoria_programatica.idcategoria_programatica = '".$bus_consulta["idcategoria_programatica"]."'
										and unidad_ejecutora.idunidad_ejecutora = categoria_programatica.idunidad_ejecutora ");
                		$bus_categoria = mysql_fetch_array($sql_categoria);
                		$nombre_categoria = $bus_categoria["denounidadejecutora"];
                	}else{
                		$nombre_categoria = 'Seleccione el centro de costo SOLO si va a centralizar el pago de la nomina en una sola Categoria Programatica';
                	}
		            $sql_certificacion = mysql_query("select numero_orden from orden_compra_servicio 
		            									where idorden_compra_servicio='".$bus_consulta["idorden_compra_servicio"]."'"); 

		            if(mysql_num_rows($sql_certificacion)>0){
		            	$bus_numero_certificacion = mysql_fetch_array($sql_certificacion);
		            	$numero_certificacion = $bus_numero_certificacion["numero_orden"];
		            }else{
		            	$numero_certificacion = 'ORDEN NO GENERADA';
		            }

					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" 
						onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
						onclick="opener.consultarGenerarNomina('<?=$bus_consulta["idgenerar_nomina"]?>', 
		                    		'<?=$bus_consulta["idtipo_nomina"]?>', '<?=utf8_decode($bus_consulta["descripcion"])?>', 
		                    		'<?=$bus_consulta["idperiodo"]?>', '<?=$bus_consulta["fecha_elaboracion"]?>', 
		                    		'<?=$bus_consulta["fecha_procesado"]?>','<?=$bus_consulta["estado"]?>',
		                    		'<?=utf8_decode($bus_consulta["nombre"])?>','<?=$bus_consulta["idbeneficiarios"]?>', 
		                    		'<?=$bus_consulta["idorden_compra_servicio"]?>', '<?=$numero_certificacion?>', 
		                    		'<?=$desde[0]." - ".$hasta[0]?>', '<?=$bus_consulta["idorden_compra_servicio_aporte"]?>',
		                    		'<?=$bus_consulta["idcategoria_programatica"]?>','<?=$nombre_categoria?>'), window.close()">
		                <td class='Browse'><?=$bus_consulta["titulo_nomina"]?></td>
		                <td class='Browse'><?=$bus_consulta["descripcion"]?></td>
		                <td align="center" class='Browse'>
						<?
						echo $desde[0]." - ".$hasta[0];
						?>
		                </td>
		                <td class='Browse'><?=$bus_consulta["nombre"]?></td>
		                <td class='Browse'><?=$bus_consulta["estado"]?></td>
		               
		                <td width="5%" align="center" class='Browse'>
		                    <img src="../../imagenes/validar.png" 
		                    style="cursor:pointer"
		                    onclick="opener.consultarGenerarNomina('<?=$bus_consulta["idgenerar_nomina"]?>', 
		                    		'<?=$bus_consulta["idtipo_nomina"]?>', '<?=utf8_decode($bus_consulta["descripcion"])?>', 
		                    		'<?=$bus_consulta["idperiodo"]?>', '<?=$bus_consulta["fecha_elaboracion"]?>', 
		                    		'<?=$bus_consulta["fecha_procesado"]?>','<?=$bus_consulta["estado"]?>',
		                    		'<?=utf8_decode($bus_consulta["nombre"])?>','<?=$bus_consulta["idbeneficiarios"]?>', 
		                    		'<?=$bus_consulta["idorden_compra_servicio"]?>', '<?=$bus_consulta["numero_orden"]?>', 
		                    		'<?=$desde[0]." - ".$hasta[0]?>', '<?=$bus_consulta["idorden_compra_servicio_aporte"]?>',
		                    		'<?=$bus_consulta["idcategoria_programatica"]?>','<?=$nombre_categoria?>'), window.close()"> 


		                </td>
	              	</tr>
				<?
				}
				?>
			</table>
      	</td>
  	</tr>
</table>
  <? } ?>