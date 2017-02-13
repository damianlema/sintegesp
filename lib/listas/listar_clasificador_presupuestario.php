<?
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style>   
     <h2 align="center">Listado Clasificador Presupuestario</h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br>
     
       <form action="" method="post"> 
       <table width="50%" border="0" align="center">
<tr>
            <td width="41%"><div align="right">Partida o Denominacion:</div></td>
<td width="45%"><label>
              <input name="codigo_denominacion" type="text" id="nombre_cedula" size="60" />
            </label></td>
<td width="14%"><label>
              <input type="submit" name="buscar" id="buscar" value="Buscar" class="button"/>
              <input type="hidden" name="destino" id="destino" value="<?=$_REQUEST["destino"]?>">
            </label></td>
         </tr>
        </table>
     </form>
        
        <br />
        <table width="96%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
          <thead>
          <tr>
            <td width="4%" class="Browse"><div align="center">Partida</div></td>
            <td width="4%" class="Browse"><div align="center">Generica</div></td>
            <td width="5%" class="Browse"><div align="center">Especifica</div></td>
            <td width="5%" class="Browse"><div align="center">Sub Especifica</div></td>
            <td width="71%" class="Browse"><div align="center">Denominacion</div></td>
            <td width="5%" class="Browse"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  if($_GET["idcategoria"]){
			  if($_POST["buscar"]){
				$sql_beneficiarios = mysql_query("select * from clasificador_presupuestario, maestro_presupuesto 
									where (clasificador_presupuestario.codigo_cuenta like '%".$_POST["codigo_denominacion"]."%' 
									|| clasificador_presupuestario.denominacion like '%".$_POST["codigo_denominacion"]."%')
									and maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
									and maestro_presupuesto.idcategoria_programatica = '".$_GET["idcategoria"]."'
									order by clasificador_presupuestario.codigo_cuenta");
			  }else{
				$sql_beneficiarios = mysql_query("select * from clasificador_presupuestario, maestro_presupuesto where 
										maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
										and maestro_presupuesto.idcategoria_programatica = '".$_GET["idcategoria"]."'
										order by clasificador_presupuestario.codigo_cuenta")or die(mysql_error());
			  }
		  }else{
			  if($_POST["buscar"]){
				$sql_beneficiarios = mysql_query("select * from clasificador_presupuestario
									where codigo_cuenta like '%".$_POST["codigo_denominacion"]."%' 
									|| denominacion like '%".$_POST["codigo_denominacion"]."%'
									order by clasificador_presupuestario.codigo_cuenta");
			  }else{
				$sql_beneficiarios = mysql_query("select * from clasificador_presupuestario order by clasificador_presupuestario.codigo_cuenta")or die(mysql_error());
			  }
		  }
          
          while($bus_beneficiarios = mysql_fetch_array($sql_beneficiarios)){
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
          onclick="
          <?
        if($_REQUEST["destino"] == "materiales"){
			?>
            opener.document.getElementById('clasificador').value='(<?=$bus_beneficiarios["codigo_cuenta"]?>) <?=$bus_beneficiarios["denominacion"]?>',opener.document.getElementById('id_clasificador').value='<?=$bus_beneficiarios["idclasificador_presupuestario"]?>', window.close()
            <?
            }
			if($_REQUEST["destino"] == "impuestos"){
			?>
            opener.document.getElementById('nombre_clasificador').value='(<?=$bus_beneficiarios["codigo_cuenta"]?>) <?=$bus_beneficiarios["denominacion"]?>',opener.document.getElementById('id_clasificador_presupuestario').value='<?=$bus_beneficiarios["idclasificador_presupuestario"]?>', window.close()
			<?
            }
			if($_REQUEST["destino"] == "maestro"){
			?>
            opener.document.getElementById('partida').value='<?=$bus_beneficiarios["partida"]?>',opener.document.getElementById('generica').value='<?=$bus_beneficiarios["generica"]?>',opener.document.getElementById('especifica').value='<?=$bus_beneficiarios["especifica"]?>',opener.document.getElementById('sub_especifica').value='<?=$bus_beneficiarios["sub_especifica"]?>',opener.document.getElementById('denopartida').value='<?=$bus_beneficiarios["denominacion"]?>',opener.document.getElementById('idclasificador_presupuestario').value='<?=$bus_beneficiarios["idclasificador_presupuestario"]?>', window.close()
			<?
            }
			?>
          ">
            <td class='Browse' align='left'><?=$bus_beneficiarios["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["denominacion"]?></td>
            
            <?
        if($_REQUEST["destino"] == "materiales"){
			?>
			<td class='Browse' align="center"><a href="#" onclick="opener.document.getElementById('clasificador').value='(<?=$bus_beneficiarios["codigo_cuenta"]?>) <?=$bus_beneficiarios["denominacion"]?>',opener.document.getElementById('id_clasificador').value='<?=$bus_beneficiarios["idclasificador_presupuestario"]?>', window.close()"><img src="../../imagenes/validar.png"></a></td>
			<?
			}else if($_REQUEST["destino"] == "impuestos"){
			?>
			<td width="6%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('nombre_clasificador').value='(<?=$bus_beneficiarios["codigo_cuenta"]?>) <?=$bus_beneficiarios["denominacion"]?>',opener.document.getElementById('id_clasificador_presupuestario').value='<?=$bus_beneficiarios["idclasificador_presupuestario"]?>', window.close()"><img src="../../imagenes/validar.png"></a></td>
			<?
			}
			if($_REQUEST["destino"] == "maestro"){
			?>
            <td width="6%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('partida').value='<?=$bus_beneficiarios["partida"]?>',opener.document.getElementById('generica').value='<?=$bus_beneficiarios["generica"]?>',opener.document.getElementById('especifica').value='<?=$bus_beneficiarios["especifica"]?>',opener.document.getElementById('sub_especifica').value='<?=$bus_beneficiarios["sub_especifica"]?>',opener.document.getElementById('denopartida').value='<?=$bus_beneficiarios["denominacion"]?>',opener.document.getElementById('idclasificador_presupuestario').value='<?=$bus_beneficiarios["idclasificador_presupuestario"]?>', window.close()"><img src="../../imagenes/validar.png"></a></td>
			<?
            }
			?>
            
          </tr>
          <?
          }
          ?>
          
        </table>