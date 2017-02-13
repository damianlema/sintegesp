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
     <h2 align="center">
     Listar Compromisos
     </h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br> 

        
  <?



			$sql_consultar = mysql_query("SELECT orden_compra_servicio.numero_orden as nro_compra,
					orden_compra_servicio.fecha_orden as fecha,
					beneficiarios.nombre,
					orden_compra_servicio.nro_factura,
					orden_compra_servicio.fecha_factura,
					articulos_compra_servicio.precio_unitario as precio_unitario,
					articulos_compra_servicio.cantidad as cantidad,
					articulos_compra_servicio.idarticulos_compra_servicio as idarticulo,
					articulos_servicios.descripcion as descripcion
						FROM 
					articulos_servicios,
					orden_compra_servicio,
					articulos_compra_servicio,
					clasificador_presupuestario,
					beneficiarios
						WHERE
					articulos_compra_servicio.idarticulos_servicios = articulos_servicios.idarticulos_servicios
					and articulos_servicios.activo = 'a'
					and articulos_servicios.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
					and clasificador_presupuestario.partida = '404'
					and orden_compra_servicio.idorden_compra_servicio = articulos_compra_servicio.idorden_compra_servicio
					and articulos_compra_servicio.registrado != 'si'
					and (orden_compra_servicio.estado = 'pagado' or orden_compra_servicio.estado = 'procesado')
					and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios")or die(mysql_error());

		//echo $query;
		
		
		$num_ordenes = mysql_num_rows($sql_consultar);
		if($num_ordenes != 0){	
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
          	<td width="8%" align="center" class="Browse" style="font-size:9px">Nro. Compra</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td width="73%" align="center" class="Browse" style="font-size:9px">Descripcion del Bien</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px">Monto</td>
            <td width="8%" align="center" class="Browse" style="font-size:9px"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  
         
          while($bus_ordenes = mysql_fetch_array($sql_consultar)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer"
                  onclick="opener.document.getElementById('nro_documento_bienes_nuevos').value = '<?=$bus_ordenes["nro_compra"]?>', opener.document.getElementById('fecha_documento_bienes_nuevos').value = '<?=$bus_ordenes["fecha"]?>', opener.document.getElementById('proveedores_bienes_nuevos').value = '<?=$bus_ordenes["nombre"]?>', opener.document.getElementById('nro_factura_bienes_nuevos').value = '<?=$bus_ordenes["nro_factura"]?>', opener.document.getElementById('fecha_factura_bienes_nuevos').value = '<?=$bus_ordenes["fecha_factura"]?>', window.close()">
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_ordenes["nro_compra"]?></td>
                    <td class='Browse' align='left' width="8%" style="font-size:10px"><?=$bus_ordenes["fecha"]?></td>
                    <td class='Browse' align='left' width="73%" style="font-size:10px"><?=$bus_ordenes["descripcion"]?></td>
                    <td class='Browse' align="right" width="8%" style="font-size:10px"><?=number_format($bus_consultar["precio_unitario"]*$bus_consultar["cantidad"],2,",",".")?></td>
               
                        <td class='Browse' align="center">
                        <a href="#" onClick="opener.document.getElementById('nro_documento_bienes_nuevos').value = '<?=$bus_ordenes["nro_compra"]?>', opener.document.getElementById('fecha_documento_bienes_nuevos').value = '<?=$bus_ordenes["fecha"]?>', opener.document.getElementById('proveedores_bienes_nuevos').value = '<?=$bus_ordenes["nombre"]?>', opener.document.getElementById('nro_factura_bienes_nuevos').value = '<?=$bus_ordenes["nro_factura"]?>', opener.document.getElementById('fecha_factura_bienes_nuevos').value = '<?=$bus_ordenes["fecha_factura"]?>', window.close()">
                        	<img src="../../imagenes/validar.png">
                        </a>
                        </td>
                  </tr>
          <?
          }
		  
          ?>
          
        </table>
 <?
 
 }
 

 ?>