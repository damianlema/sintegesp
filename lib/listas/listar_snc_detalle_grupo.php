<?
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style>   
     <h2 align="center">Catalogo Sistema Nacional de Contratistas</h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br>
     
       <form action="" method="post"> 
       <table width="50%" border="0" align="center">
      <tr>
            <td width="41%"><div align="right">C&oacute;digo o Descripci&oacute;n:</div></td>
<td width="45%"><label>
              <input name="nombre_cedula" type="text" id="nombre_cedula" size="60" />
            </label></td>
<td width="14%"><label>
              <input type="submit" name="buscar" id="buscar" value="Buscar" class="button"/>
            </label></td>
         </tr>
        </table>
     </form>
        
        <br />
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
            <td width="9%" class="Browse"><div align="center">Codigo</div></td>
            <td width="81%" class="Browse"><div align="center">Descripcion</div></td>
            <td width="10%" class="Browse"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  if($_POST["nombre_cedula"] != ""){
			$sql_beneficiarios = mysql_query("select * from snc_detalle_grupo where codigo like '%".$_POST["nombre_cedula"]."%' || descripcion like '%".$_POST["nombre_cedula"]."%'");



			while($bus_beneficiarios = mysql_fetch_array($sql_beneficiarios)){
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
          onclick="
          <?
          if($_GET["destino"] == "materiales"){
		  ?>
		  opener.document.form1.snc_detalle_grupo.value='(<?=$bus_beneficiarios["codigo"]?>) <?=$bus_beneficiarios["descripcion"]?>',opener.document.form1.id_snc_detalle_grupo.value='<?=$bus_beneficiarios["idsnc_detalle_grupo"]?>', window.close()
		  <?
		  }
		  ?>
          ">
            <td class='Browse' align='left'><?=$bus_beneficiarios["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["descripcion"]?></td>
            
            <?
        if($_GET["destino"] == "materiales"){
			?>
			<td class='Browse' align="center"><a href="#" onClick="opener.document.form1.snc_detalle_grupo.value='(<?=$bus_beneficiarios["codigo"]?>) <?=$bus_beneficiarios["descripcion"]?>',opener.document.form1.id_snc_detalle_grupo.value='<?=$bus_beneficiarios["idsnc_detalle_grupo"]?>', window.close()"><img src="../../imagenes/validar.png"></a></td>
			<?
			}
			?>
            
          </tr>
          <?
          }
		  }

/*

		  }else{
		  	$sql_beneficiarios = mysql_query("select * from snc_detalle_grupo");
			
			
			while($bus_beneficiarios = mysql_fetch_array($sql_beneficiarios)){
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
          onclick="
          <?
          if($_GET["destino"] == "materiales"){
		  ?>
		  opener.document.form1.snc_detalle_grupo.value='(<?=$bus_beneficiarios["codigo"]?>) <?=$bus_beneficiarios["descripcion"]?>',opener.document.form1.id_snc_detalle_grupo.value='<?=$bus_beneficiarios["idsnc_detalle_grupo"]?>', window.close()
		  <?
		  }
		  ?>
          ">
            <td class='Browse' align='left'><?=$bus_beneficiarios["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["descripcion"]?></td>
            
            <?
        if($_GET["destino"] == "materiales"){
			?>
			<td class='Browse' align="center"><a href="#" onClick="opener.document.form1.snc_detalle_grupo.value='(<?=$bus_beneficiarios["codigo"]?>) <?=$bus_beneficiarios["descripcion"]?>',opener.document.form1.id_snc_detalle_grupo.value='<?=$bus_beneficiarios["idsnc_detalle_grupo"]?>', window.close()"><img src="../../imagenes/validar.png"></a></td>
			<?
			}
			?>
            
          </tr>
          <?
          }
			
			
			
		  }
          
    */      
          ?>
          
        </table>