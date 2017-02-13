<?
include("../../../conf/conex.php");
Conectarse();
extract($_POST);
extract($_GET);


switch($ejecutar){
	case "ingresarInteres":
		$sql_ingresar = mysql_query("insert into tabla_intereses (anio, mes, interes, usuario, fechayhora, pc)VALUES
													('".$anio."', '".$mes."', '".$interes."', '".$login."', '".$fh."', '".$pc."')");
	break;
	
	case "modificarInteres":
		$sql_modificar = mysql_query("update tabla_intereses set
									 					anio = '".$anio."',
														mes = '".$mes."',
														interes = '".$interes."'
														where idtabla_intereses = '".$idinteres."'");
	break;
	
	
	case "consultarIntereses":
		$meses['01'] = "Enero";
		$meses['02'] = "Febrero";
		$meses['03'] = "Marzo";
		$meses['04'] = "Abril";
		$meses['05'] = "Mayo";
		$meses['06'] = "Junio";
		$meses['07'] = "Julio";
		$meses['08'] = "Agosto";
		$meses['09'] = "Septiembre";
		$meses[10] = "Octubre";
		$meses[11] = "Noviembre";
		$meses[12] = "Diciembre";
		$sql_consultar = mysql_query("select * from tabla_intereses order by anio desc, mes desc");
		?>
		<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="50%" align="center">
        	<thead>
            <tr>
            	<td width="28%" class="Browse" align="center">Año</td>
                <td width="33%" class="Browse" align="center">Mes</td>
                <td width="25%" class="Browse" align="center">% Interes</td>
                <td class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["anio"]?></td>
                <td align="left" class="Browse"><?="(".$bus_consultar["mes"].")&nbsp;".$meses[$bus_consultar["mes"]]?></td>
                <td align="right" class="Browse"><?=number_format($bus_consultar["interes"],2,",",".")?>&nbsp;%</td>
                <td width="6%" align="center" class="Browse">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionarModificar('<?=$bus_consultar["idtabla_intereses"]?>', '<?=$bus_consultar["anio"]?>','<?=$bus_consultar["mes"]?>','<?=$bus_consultar["interes"]?>')"></td>
                 <td width="8%" align="center" class="Browse">
                 	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="eliminarIntereses('<?=$bus_consultar["idtabla_intereses"]?>')">
                 </td>
          </tr>
            <? } ?>
        </table>
		<?
		
		
	break;
	
	case "eliminarIntereses":
		$sql_eliminar = mysql_query("delete from tabla_intereses where idtabla_intereses = '".$idinteres."'");
	break;
}

?>