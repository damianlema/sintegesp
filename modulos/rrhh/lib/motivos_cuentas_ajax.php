<?
include("../../../conf/conex.php");
Conectarse();



if($ejecutar == "consultarAsociados"){
	$sql_consultar = mysql_query("select 
								 *
								 	from 
								 motivos_cuentas");
	
	?>
	<table border="0" class="Browse" cellpadding="0" cellspacing="0" width="80%" align="center">
        	<thead>
            <tr>
            	<td width="26%" class="Browse" align="center">Denominacion</td>
                <td width="11%" class="Browse" align="center" colspan="2">Acci&oacute;n</td>
            </tr>
            </thead>
            <? while($bus_consultar = mysql_fetch_array($sql_consultar)){?>
            	<tr bordercolor="#000000" bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                <td align="left" class="Browse"><?=$bus_consultar["denominacion"]?></td>
                
                
                
                <td class="Browse" align="center">
                 <img src="imagenes/modificar.png" style="cursor:pointer" alt='Modificar' title='Modificar' onclick="seleccionar('<?=$bus_consultar["denominacion"]?>','<?=$bus_consultar["idmotivos_cuentas"]?>')">                </td>
                 <td class="Browse" align="center">
                 	<img src="imagenes/delete.png" style="cursor:pointer" alt='Eliminar' title='Eliminar' onclick="seleccionar('<?=$bus_consultar["denominacion"]?>','<?=$bus_consultar["idmotivos_cuentas"]?>')">
                 </td>
          </tr>
            <? } ?>
        </table>
	<?
	
}




if($ejecutar == "ingresarCunetaBancaria"){
	$sql_ingresar = mysql_query("insert into motivos_cuentas (denominacion)VALUES('".$denominacion."')");
}




if($ejecutar =="modificarCunetaBancaria"){
		$sql_ingresar = mysql_query("update motivos_cuentas set denominacion = '".$denominacion."'
																		   	where 
																		   idmotivos_cuentas='".$idmotivos_cuentas."'");
}



if($ejecutar=="eliminarCuentaBancaria"){
	$sql_eliminar = mysql_query("delete from motivos_cuentas where idmotivos_cuentas = '".$idmotivos_cuentas."'");
}
?>