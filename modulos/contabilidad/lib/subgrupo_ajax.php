<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarGrupo"){
	$sql_consultar = mysql_query("select * from subgrupo_cuentas_contables where idgrupo = '".$idgrupo."' and denominacion = '".$denominacion."'");
	$num_consultar = mysql_fetch_array($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into subgrupo_cuentas_contables(idgrupo, codigo, denominacion)values('".$idgrupo."', '".$codigo."', '".$denominacion."')");
	}else{
		echo "existe";
	}
}

if($ejecutar == "editarGrupo"){
	$sql_ingresar = mysql_query("update subgrupo_cuentas_contables set idgrupo = '".$idgrupo."', codigo = '".$codigo."', denominacion = '".$denominacion."' where idsubgrupo_cuentas_contables = '".$idsubgrupo."'")or die(mysql_error());
}

if($ejecutar == "eliminarGrupo"){
	$sql_validar = mysql_query("select * from rubro_cuentas_contables where idsubgrupo = '".$idsubgrupo."'");
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar  > 0){
		echo "usado";
	}else{
	$sql_ingresar = mysql_query("delete from subgrupo_cuentas_contables where idsubgrupo_cuentas_contables = '".$idsubgrupo."'");
	}
	
}



if($ejecutar == "listarGrupos"){
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="68%">
          <thead>
          <tr>
            <td width="23%" align="center" class="Browse" style="font-size:9px">Grupo</td>
            <td width="5%" align="center" class="Browse" style="font-size:9px">Codigo</td>
            <td width="58%" align="center" class="Browse" style="font-size:9px">Denominacion</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $query = "SELECT grupo_cuentas_contables.codigo as codigo_grupo,
		  										grupo_cuentas_contables.denominacion as denominacion_grupo,
												grupo_cuentas_contables.idgrupos_cuentas_contables as idgrupo,
												subgrupo_cuentas_contables.codigo as codigo_subgrupo,
												subgrupo_cuentas_contables.denominacion as denominacion_subgrupo,
												subgrupo_cuentas_contables.idsubgrupo_cuentas_contables as idsubgrupo
													FROM
												grupo_cuentas_contables, 
												subgrupo_cuentas_contables
													WHERE
												grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo";
			if($grupoBuscar != "0"){
				$query .= " and subgrupo_cuentas_contables.idgrupo = '".$grupoBuscar."'";
			}
			$query .= " and subgrupo_cuentas_contables.denominacion like '%".$campoBuscar."%' order by denominacion_grupo, denominacion_subgrupo ";
		  //echo $query;
		  $sql_consultar = mysql_query($query)or die(mysql_error());
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="23%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["denominacion_grupo"]?></td>
                    <td class='Browse' align='left' width="5%" style="font-size:10px">&nbsp;<?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["codigo_subgrupo"]?></td>
                    <td class='Browse' align='left' width="58%" style="font-size:10px"><?=$bus_consultar["denominacion_subgrupo"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_subgrupo"]?>', '<?=$bus_consultar["denominacion_subgrupo"]?>', '<?=$bus_consultar["idgrupo"]?>', '<?=$bus_consultar["idsubgrupo"]?>')">                    </td>
                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_subgrupo"]?>', '<?=$bus_consultar["denominacion_subgrupo"]?>', '<?=$bus_consultar["idgrupo"]?>', '<?=$bus_consultar["idsubgrupo"]?>')">                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
<?
}

?>