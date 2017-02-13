<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);

if($ejecutar == "ingresarGrupo"){
	$sql_consultar = mysql_query("select * from rubro_cuentas_contables where idsubgrupo = '".$idsubgrupo."' and codigo = '".$codigo."'");
	$num_consultar = mysql_fetch_array($sql_consultar);
	if($num_consultar == 0){
		$sql_ingresar = mysql_query("insert into rubro_cuentas_contables(idsubgrupo, codigo, denominacion)values('".$idsubgrupo."', '".$codigo."', '".$denominacion."')");
	}else{
		echo "existe";
	}
}

if($ejecutar == "editarGrupo"){
	$sql_ingresar = mysql_query("update rubro_cuentas_contables set idsubgrupo = '".$idsubgrupo."', codigo = '".$codigo."', denominacion = '".$denominacion."' where idrubro_cuentas_contables = '".$idrubro."'")or die(mysql_error());
}

if($ejecutar == "eliminarGrupo"){
	$sql_validar= mysql_query("select * from cuenta_cuentas_contables where idrubro= '".$idrubro."'");
	$num_validar = mysql_num_rows($sql_validar);
	if($num_validar > 0){
		echo "usado";
	}else{
		$sql_ingresar = mysql_query("delete from rubro_cuentas_contables where idrubro_cuentas_contables = '".$idrubro."'");
	}
	
}



if($ejecutar == "listarGrupos"){
?>
<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="68%">
          <thead>
          <tr>
           <td width="30%" align="center" class="Browse" style="font-size:9px">Sub Grupo</td>
            <td width="7%" align="center" class="Browse" style="font-size:9px">C&oacute;digo</td>
            <td width="58%" align="center" class="Browse" style="font-size:9px">Denominaci&oacute;n</td>
            <td align="center" class="Browse" style="font-size:9px" colspan="2">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $query = "SELECT grupo_cuentas_contables.codigo as codigo_grupo,
		  										subgrupo_cuentas_contables.codigo as codigo_subgrupo,
												grupo_cuentas_contables.denominacion as denominacion_grupo,
												subgrupo_cuentas_contables.denominacion as denominacion_subgrupo,
												subgrupo_cuentas_contables.idsubgrupo_cuentas_contables as idsubgrupo,
												rubro_cuentas_contables.codigo as codigo_rubro,
												rubro_cuentas_contables.denominacion as denominacion_rubro,
												rubro_cuentas_contables.idrubro_cuentas_contables as idrubro
													FROM 
												grupo_cuentas_contables,
												subgrupo_cuentas_contables,
												rubro_cuentas_contables
													WHERE
												subgrupo_cuentas_contables.idsubgrupo_cuentas_contables = rubro_cuentas_contables.idsubgrupo
												and grupo_cuentas_contables.idgrupos_cuentas_contables = subgrupo_cuentas_contables.idgrupo";
			if($subgrupoBuscar != "0"){
				$query .= " and rubro_cuentas_contables.idsubgrupo = '".$subgrupoBuscar."'";
			}
			
			$query .= " and rubro_cuentas_contables.denominacion like '%".$campoBuscar."%' order by codigo_grupo, codigo_subgrupo, codigo_rubro";
		  //echo $query;
		  $sql_consultar = mysql_query($query);
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="30%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["denominacion_grupo"]." ".$bus_consultar["codigo_subgrupo"]." ".$bus_consultar["denominacion_subgrupo"]?></td>
                    <td class='Browse' align='left' width="7%" style="font-size:10px"><?=$bus_consultar["codigo_grupo"]." ".$bus_consultar["codigo_subgrupo"]?>&nbsp;<?=$bus_consultar["codigo_rubro"]?></td>
                    <td class='Browse' align='left' width="58%" style="font-size:10px"><?=$bus_consultar["denominacion_rubro"]?></td>
                    <td width="3%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/modificar.png"
                            style="cursor:pointer"
                            onClick="seleccinarModificar('<?=$bus_consultar["codigo_rubro"]?>', '<?=$bus_consultar["denominacion_rubro"]?>', '<?=$bus_consultar["idsubgrupo"]?>', '<?=$bus_consultar["idrubro"]?>')">                    
                    </td>
                    <td width="5%" align="center" valign="middle" class='Browse'>
                      <img src="imagenes/delete.png"
                            style="cursor:pointer"
                            onClick="seleccinarEliminar('<?=$bus_consultar["codigo_rubro"]?>', '<?=$bus_consultar["denominacion_rubro"]?>', '<?=$bus_consultar["idsubgrupo"]?>', '<?=$bus_consultar["idrubro"]?>')">                    
                    </td>
                            
   		        </tr>
          <?
          }
          ?>
  </table>
<?
}

?>