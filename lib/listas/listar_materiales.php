<?
session_start();
include("../../conf/conex.php");
$conection_db = conectarse();
include("../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../js/funciones.js"></script>
<script type="text/javascript" src="../../js/function.js"></script>
<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../css/theme/calendar-win2k-cold-1.css"); </style>
    <?
	if($_SESSION["modulo"] == 4){
	?> 
	     <h2 align="center">Catalogo de Bienes, Servicios, Obras, Inmuebles, Otros</h2>
    <?
	}
	if($_SESSION["modulo"] == 1 or $_SESSION["modulo"] == 13){
	?> 
	     <h2 align="center">Catalogo de Conceptos</h2>
    <?
	}
	if($_SESSION["modulo"] == 3 and $_REQUEST["destino"] != "requisicion"){
	?>
	    <h2 align="center">Catalogo de Compras y Servicios</h2>
    <? }
	if($_SESSION["modulo"] == 2){
	?>
	    <h2 align="center">Catalogo de Materiales</h2>
    <? }
	if($_SESSION["modulo"] == 3 and $_REQUEST["destino"] == "requisicion"){
	?>
	    <h2 align="center">Catalogo de Requisicion</h2>
    <? }
	?>
  <br />
     	<h2 class="sqlmVersion"></h2>
<br>
     
       <form action="" method="post"> 
       <table width="631" border="0" align="center">
  <tr>

			
			
			<td width="23%"> Tipo de Material:</td>
  <td width="15%">
					<?
					if ($_SESSION["modulo"] == '1' or $_SESSION["modulo"] == '13'){
						$sql_tipos_documentos = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' and paga = 'no' and modulo like '%-1-%' or modulo like '%-13-%'");
					}else if($_SESSION["modulo"] == '16'){
                    	$sql_tipos_documentos = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' and paga = 'no' and modulo like '%-3-%'");
					}else{
						$sql_tipos_documentos = mysql_query("select * from tipos_documentos where compromete = 'si' and causa = 'no' and paga = 'no' and modulo like '%-".$_SESSION["modulo"]."-%'");
					}
					?>
                    <select name="tipo_articulo" id="tipo_articulo">
                    <option value="0">.:: Seleccione ::.</option>
            		<?
                    while($bus_tipos_documentos = mysql_fetch_array($sql_tipos_documentos)){
						?>
                        <option value="<?=$bus_tipos_documentos["idtipos_documentos"]?>"><?=$bus_tipos_documentos["descripcion"]?></option>
                        <?
					}
					?>
                    
                </select>
            </td>

	
            
            
          <td width="26%" align="right">Codigo o Descripcion:</td>
<td width="23%"><label>
              <input type="text" name="codigo_descripcion" id="codigo_descripcion" />
            </label></td>
<td width="13%"><label>
              <input type="submit" name="buscar" id="buscar" value="Buscar" class="button"/>
            </label></td>
         </tr>
        </table>
     </form>
        
        <br />
        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
          <thead>
          <tr>
            <td class="Browse" width="15%"><div align="center">Codigo</div></td>
            <td class="Browse" width="65%"><div align="center">Descripcion</div></td>
            <td class="Browse" width="10%"><div align="center">Und</div></td>
            <td class="Browse" width="10%"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  
		  if($_GET["destino"] == "solicitud"){
		  	$tipo == 'Compras';
		  }
		  
	if ($_SESSION["modulo"] == '1' or $_SESSION["modulo"] == '13'){
		  if($_POST["codigo_descripcion"]){
				$query = "select * from articulos_servicios, unidad_medida, tipos_documentos where 
											(articulos_servicios.codigo like '%".$_POST["codigo_descripcion"]."%' 
											|| articulos_servicios.descripcion like '%".$_POST["codigo_descripcion"]."%') 
											and  unidad_medida.idunidad_medida = articulos_servicios.idunidad_medida
											and articulos_servicios.tipo = tipos_documentos.idtipos_documentos
											and (tipos_documentos.modulo like '%-1-%' or tipos_documentos.modulo like '%-13-%')";
																
				if ($_POST["tipo_articulo"] <> "0"){
					$query .= " and articulos_servicios.tipo = '".$_POST["tipo_articulo"]."'";
				}
				$query .= " order by articulos_servicios.descripcion";
				$sql_materiales = mysql_query($query);
		  }else{
				$query = "select * from articulos_servicios, unidad_medida, tipos_documentos where 
																	articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida 
																	and articulos_servicios.tipo = tipos_documentos.idtipos_documentos 
																	and (tipos_documentos.modulo like '%-1-%' or tipos_documentos.modulo like '%-13-%')";
				
				if ($_POST["tipo_articulo"] <> "0"){
					$query .= " and articulos_servicios.tipo = '".$_POST["tipo_articulo"]."'";
				}
				$query .= " order by articulos_servicios.descripcion";
				$sql_materiales = mysql_query($query);
			
		  }
	
	}else if($_SESSION["modulo"] == '16'){
		  if($_POST["codigo_descripcion"]){
				$query = "select * from articulos_servicios, unidad_medida, tipos_documentos where 
																(articulos_servicios.codigo like '%".$_POST["codigo_descripcion"]."%' 
																|| articulos_servicios.descripcion like '%".$_POST["codigo_descripcion"]."%') 
																and  unidad_medida.idunidad_medida = articulos_servicios.idunidad_medida
																and articulos_servicios.tipo = tipos_documentos.idtipos_documentos
																and tipos_documentos.modulo like '%-3-%'";
																
				if ($_POST["tipo_articulo"] <> "0"){
					$query .= " and articulos_servicios.tipo = '".$_POST["tipo_articulo"]."'";
				}
				$query .= " order by articulos_servicios.descripcion";
				$sql_materiales = mysql_query($query);
		  }else{
				$query = "select * from articulos_servicios, unidad_medida, tipos_documentos where 
																	articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida 
																	and articulos_servicios.tipo = tipos_documentos.idtipos_documentos 
																	and tipos_documentos.modulo like '%-3-%'";
				
				if ($_POST["tipo_articulo"] <> "0"){
					$query .= " and articulos_servicios.tipo = '".$_POST["tipo_articulo"]."'";
				}
				$query .= " order by articulos_servicios.descripcion";
				$sql_materiales = mysql_query($query);
			
		  }
	}else{
		  if($_POST["codigo_descripcion"]){
				$query = "select * from articulos_servicios, unidad_medida, tipos_documentos where 
																(articulos_servicios.codigo like '%".$_POST["codigo_descripcion"]."%' 
																|| articulos_servicios.descripcion like '%".$_POST["codigo_descripcion"]."%') 
																and  unidad_medida.idunidad_medida = articulos_servicios.idunidad_medida
																and articulos_servicios.tipo = tipos_documentos.idtipos_documentos
																and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%'";
																
				if ($_POST["tipo_articulo"] <> "0"){
					$query .= " and articulos_servicios.tipo = '".$_POST["tipo_articulo"]."'";
				}
				$query .= " order by articulos_servicios.descripcion";
				$sql_materiales = mysql_query($query);
		  }else{
				$query = "select * from articulos_servicios, unidad_medida, tipos_documentos where 
																	articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida 
																	and articulos_servicios.tipo = tipos_documentos.idtipos_documentos 
																	and tipos_documentos.modulo like '%-".$_SESSION["modulo"]."-%'";
				
				if ($_POST["tipo_articulo"] <> "0"){
					$query .= " and articulos_servicios.tipo = '".$_POST["tipo_articulo"]."'";
				}
				$query .= " order by articulos_servicios.descripcion";
				$sql_materiales = mysql_query($query);
			
		  }
	}      
          while($bus_materiales = mysql_fetch_array($sql_materiales)){
		  
		  
            $materiales_codigo = addslashes($bus_materiales["codigo"]);
			$materiales_descripcion = addslashes($bus_materiales["3"]);
			$materiales_abreviado = addslashes($bus_materiales["abreviado"]);
			//echo "SIN: ".$bus_materiales["codigo"]." CON: ".$materiales_codigo;

			
			
		 // var_dump($bus_materiales);
		  ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
          onclick="
          <?php
          if($_GET["destino"] == "materiales"){
		  ?>
          opener.document.form1.id_material.value=<?=$bus_materiales["idarticulos_servicios"]?>, opener.document.form1.submit(), window.close()
          <?
          }
		  if($_GET["destino"] == "solicitud"){
		  ?>
		  opener.document.form1.id_material.value='<?=$bus_materiales["idarticulos_servicios"]?>',
                 opener.document.form1.codigo_material.value='<?=$materiales_codigo?>',
                  opener.document.form1.descripcion_material.value='<?=$materiales_descripcion?>',
                   opener.document.form1.unidad_medida.value='<?=$materiales_abreviado?>',
                    opener.document.form1.cantidad.focus(),
                     window.close()
		  <?
		  }
		  if($_GET["destino"] == "solicituda"){
		  ?>
		  opener.document.getElementById('id_material').value='<?=$bus_materiales["idarticulos_servicios"]?>',
                 opener.document.getElementById('codigo_material').value='<?=$materiales_codigo?>',
                  opener.document.getElementById('descripcion_material').value='<?=$materiales_descripcion?>',
                   opener.document.getElementById('unidad_medida').value='<?=$materiales_abreviado?>',
                    opener.document.getElementById('cantidad').focus(),
                     window.close()
		  <?
		  }
		  if($_GET["destino"] == "orden_compra"){ 
		  ?>
		  opener.document.getElementById('id_material').value='<?=$bus_materiales["idarticulos_servicios"]?>',
                		 opener.document.getElementById('codigo_material').value='<?=$materiales_codigo?>',
                          opener.document.getElementById('descripcion_material').value='<?=$materiales_descripcion?>', 
                          opener.document.getElementById('unidad_medida').value='<?=$materiales_abreviado?>', 
                          opener.document.getElementById('cantidad').focus(),
                           window.close()
		  <?
		  }
		  if($_GET["destino"] == "requisicion"){
		  ?>
		  opener.document.getElementById('id_material').value='<?=$bus_materiales["idarticulos_servicios"]?>',
             opener.document.getElementById('codigo_material').value='<?=$materiales_codigo?>',
              opener.document.getElementById('descripcion_material').value='<?=$materiales_descripcion?>',
               opener.document.getElementById('unidad_medida').value='<?=$materiales_abreviado?>',
                opener.document.getElementById('cantidad').focus(),
                 window.close()
		  <?
		  }
		  if($_GET["destino"] == "certificacion"){
		  ?>
		  opener.document.getElementById('id_material').value='<?=$bus_materiales["idarticulos_servicios"]?>',
             opener.document.getElementById('codigo_material').value='<?=$materiales_codigo?>',
              opener.document.getElementById('descripcion_material').value='<?=$materiales_descripcion?>',
               opener.document.getElementById('unidad_medida').value='<?=$materiales_abreviado?>',
                opener.document.getElementById('cantidad').focus(),
                 window.close()
		  <?
		  }
		  ?>
          ">
            <td class='Browse' align='left'>&nbsp;<?=$bus_materiales["codigo"]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_materiales[3]?></td>
            <td class='Browse' align='left'>&nbsp;<?=$bus_materiales["abreviado"]?></td>
            
            <?
			if($_GET["destino"] == "materiales"){
			?>
			<td class='Browse' align="center"><a href="#" onClick="opener.document.form1.id_material.value=<?=$bus_materiales["idarticulos_servicios"]?>, opener.document.form1.submit(), window.close()"><img src="../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "solicitud"){
			?>
			<td class='Browse' align="center">
            <a href="#" 
            	onClick='opener.document.form1.id_material.value="<?=$bus_materiales["idarticulos_servicios"]?>",
                 opener.document.form1.codigo_material.value="<?=$materiales_codigo?>",
                  opener.document.form1.descripcion_material.value="<?=$materiales_descripcion?>",
                   opener.document.form1.unidad_medida.value="<?=$materiales_abreviado?>",
                    opener.document.form1.cantidad.focus(),
                     window.close()'>
                     <img src="../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "solicituda"){
			?>
			<td class='Browse' align="center">
            	<a href="#" 
                onClick='opener.document.form1.id_material.value="<?=$bus_materiales["idarticulos_servicios"]?>",
                 opener.document.form1.codigo_material.value="<?=$materiales_codigo?>",
                  opener.document.form1.descripcion_material.value="<?=$materiales_descripcion?>",
                   opener.document.form1.unidad_medida.value="<?=$materiales_abreviado?>",
                    opener.document.form1.cantidad.focus(),
                     window.close()'><img src="../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "orden_compra"){
			?>
            
			<td class='Browse' align="center">
            <a href="#" onClick='opener.document.getElementById("id_material").value="<?=$bus_materiales["idarticulos_servicios"]?>",
                		 opener.document.getElementById("codigo_material").value="<?=$materiales_codigo?>",
                          opener.document.getElementById("descripcion_material").value="<?=$materiales_descripcion?>", 
                          opener.document.getElementById("unidad_medida").value="<?=$materiales_abreviado?>", 
                          opener.document.getElementById("cantidad").focus(),
                           window.close()'>
              <img src="../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "requisicion"){
			?>
			<td class='Browse' align="center">
            <a href="#" 
            onClick='opener.document.getElementById("id_material").value="<?=$bus_materiales["idarticulos_servicios"]?>",
             opener.document.getElementById("codigo_material").value="<?=$materiales_codigo?>",
              opener.document.getElementById("descripcion_material").value="<?=$materiales_descripcion?>",
               opener.document.getElementById("unidad_medida").value="<?=$materiales_abreviado?>",
                opener.document.getElementById("cantidad").focus(),
                 window.close()'>
                 <img src="../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "certificacion"){
			?>
			<td class='Browse' align="center">
            <a href="#" onClick='opener.document.getElementById("id_material").value="<?=$bus_materiales["idarticulos_servicios"]?>",
             opener.document.getElementById("codigo_material").value="<?=$materiales_codigo?>",
              opener.document.getElementById("descripcion_material").value="<?=$materiales_descripcion?>",
               opener.document.getElementById("unidad_medida").value="<?=$materiales_abreviado?>",
                opener.document.getElementById("cantidad").focus(),
                 window.close()'>
            <img src="../../imagenes/validar.png"></a></td>
			<?
			}
			?>
            
          </tr>
          <?
          }
		  
          ?>
          
        </table>