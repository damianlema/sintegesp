<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarGrupo"){
	$sql_consultar = mysql_query("select * from grupo_cuentas_contables where denominacion = '".$denominacion."'");
	$num_consultar = mysql_fetch_array($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into grupo_cuentas_contables(codigo, denominacion)values('".$codigo."', '".$denominacion."')");
	}else{
		echo "existe";
	}
}

if($ejecutar == "editarGrupo"){
echo "update grupo_cuentas_contables set codigo = '".$codigo."', denominacion = '".$denominacion."' where idgrupos_cuentas_contables = '".$idgrupo."'";
	$sql_ingresar = mysql_query("update grupo_cuentas_contables set codigo = '".$codigo."', denominacion = '".$denominacion."' where idgrupos_cuentas_contables = '".$idgrupo."'")or die(mysql_error());
}

if($ejecutar == "eliminarGrupo"){
	$sql_validar = mysql_query("select * from subgrupo_cuentas_contables where idgrupo = '".$idgrupo."'");
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		echo "usado";
	}else{
		$sql_ingresar = mysql_query("delete from grupo_cuentas_contables where idgrupos_cuentas_contables = '".$idgrupo."'");
	}
}



if($ejecutar == "listarGrupos"){
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="30%">
          <thead>
          <tr>
            <td width="7%" align="center" class="Browse" style="font-size:9px">Codigo</td>
            <td width="67%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query("select * from grupo_cuentas_contables where denominacion like '%".$campoBuscar."%' order by codigo");
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["codigo"]?></td>
                    <td class='Browse' align='left' width="67%" style="font-size:10px"><?=$bus_consultar["denominacion"]?></td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo"]?>', '<?=$bus_consultar["denominacion"]?>', '<?=$bus_consultar["idgrupos_cuentas_contables"]?>')">
                    </td>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo"]?>', '<?=$bus_consultar["denominacion"]?>', '<?=$bus_consultar["idgrupos_cuentas_contables"]?>')">                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
<?
}

?>