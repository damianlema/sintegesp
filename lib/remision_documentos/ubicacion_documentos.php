<?
session_start();
?>
<script src="js/ubicar_documentos_ajax.js" type="text/javascript" language="javascript"></script>	
    <br>
<h4 align=center>Ubicar Documentos</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
 
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="2" >
 <form action="" method="post"> 
       
       <input type="hidden" name="destino" id="destino" <? if($_GET["destino"]){ echo "value='".$_GET["destino"]."'";}else{echo "value='".$_POST["destino"]."'";}?>>
       <table width="50%" border="0" align="center">
		<tr>
			 <? if ($modulo == 4) {?> 
      		<td width="20%"><div align="center">Tipo<br>
                <select name="tipo_documento">
                  <option value="compromisos">Compromisos</option>
                  <option value="pagos">Pagos</option>        
                </select>
      			</div>
      		</td>
      		<? } ?>
            <td width="30%"><div align="center">Estado del Documento:<br>
                <select name="estado_documento">
                  <option value="todas">.:: Todos ::.</option>
                  <option value="elaboracion">En Elaboracion</option>
                  <option value="procesado">Procesado</option>
                  <option value="conformado">Conformado</option>
                  <option value="devuelto">Devuelto</option>
                  <option value="ordenado">Ordenado</option>
                  <option value="pagado">Pagado</option>
                  <option value="anulado">Anulado</option>

                </select>
      		</div>
      		</td>
           
          	<td width="50%"><div align="center">Tipo de Busqueda:<br>
              <input type="text" name="texto_busqueda" id="texto_busqueda">
              <select name="tipo_busqueda" id="tipo_busqueda">
                    <option value="todas">Todos</option>
                    <option value="numero">No. Orden</option>
                    <option value="proveedor">Proveedor</option>
                    <option value="justificacion">Justificacion</option>            
                </select>
          	</div>
            </td>
	        <td width="10%"><label>
    	        <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
        	</label></td>
             </tr>
        </table>
     </form>
        
  <?
  if($_POST["buscar"]){
				if($_POST["estado_documento"] != "todas"){
				
				$sql_ordenes = mysql_query("select * from orden_compra_servicio, beneficiarios, tipos_documentos  
												where (orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%'
													or orden_compra_servicio.justificacion like '%".$_POST["texto_busqueda"]."%'
													or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%')
													and orden_compra_servicio.estado = '".$_POST["estado_documento"]."'
													and orden_compra_servicio.numero_orden != ''
												and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
												and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios")or die("error 1 ".mysql_error());
				}else{
					$sql_ordenes = mysql_query("select * from orden_compra_servicio, beneficiarios, tipos_documentos  
												where (orden_compra_servicio.numero_orden like '%".$_POST["texto_busqueda"]."%'
													or orden_compra_servicio.justificacion like '%".$_POST["texto_busqueda"]."%'
													or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%')
													and orden_compra_servicio.numero_orden != ''
												and orden_compra_servicio.tipo = tipos_documentos.idtipos_documentos
												and beneficiarios.idbeneficiarios = orden_compra_servicio.idbeneficiarios")or die("error 1 ".mysql_error());
				}
		
		$existen_datos = mysql_num_rows($sql_ordenes);
		/*if ($existen_datos == 0) {?>
        	<br>
			<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
			<thead>
				  <tr>
                  	<td align="center">
						<? echo "No existen documentos para esa busqueda"; ?>
                    </td>
                  </tr>
            </thead>
        </table>
		<?
		}else{*/
			
  ?>      <br />
  <div id="divSeleccionarDocumentos" style=" width:100%; height:100px; overflow:auto;">
        
        
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="85%">
<thead>
          <tr>
          	<td class="Browse" width="11%"><div align="center">N&uacute;mero</div></td>
            <td class="Browse" width="21%"><div align="center">Proveedor</div></td>
            <td class="Browse" width="9%"><div align="center">Fecha</div></td>
            <td class="Browse" width="8%"><div align="center">Monto</div></td>
            <td class="Browse" width="36%"><div align="center">Justificacion</div></td>
            <td class="Browse" width="9%"><div align="center">Estado</div></td>
            <td class="Browse" width="6%"><div align="center">Acci&oacute;n</div></td>
    </tr>
          </thead>
          <? 
		  
          
          while($bus_ordenes = mysql_fetch_array($sql_ordenes)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes["numero_orden"]?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["nombre"]?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["fecha_orden"]?></td>
                    <td class='Browse' style="text-align:right"><?=number_format($bus_ordenes["total"],2,",",".")?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["justificacion"]?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes["estado"]?></td>
                  	
					<? 
					$tabla = "orden_compra_servicio";
					if ($tabla == "orden_compra_servicio"){
							$id = $bus_ordenes["idorden_compra_servicio"];
					   }else if ($tabla == "orden_pago"){
					   		$id = $bus_ordenes["idorden_pago"];
					   }?>
                    <td class='Browse' align="center">
                    <img src="imagenes/validar.png" style=" cursor:pointer" onClick="ubicarDocumento(<?=$id?>, '<?=$tabla?>')">
                    </td>
                       
                  </tr>
          <?
          }
		  //echo "PRUEBA: ".$_POST["estado_documento"];
		if($_POST["estado_documento"] != "todas"){  
		  $sql_ordenes2 = mysql_query("select * from orden_pago, beneficiarios, tipos_documentos  
											where (orden_pago.numero_orden like '%".$_POST["texto_busqueda"]."%'
												or orden_pago.justificacion like '%".$_POST["texto_busqueda"]."%'
												or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%')
												and orden_pago.estado = '".$_POST["estado_documento"]."'
												and orden_pago.numero_orden != ''
											and orden_pago.tipo = tipos_documentos.idtipos_documentos
											and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios")or die("error 5 ".mysql_error());
		  
		  }else{
		  	$sql_ordenes2 = mysql_query("select * from orden_pago, beneficiarios, tipos_documentos  
											where (orden_pago.numero_orden like '%".$_POST["texto_busqueda"]."%'
												or orden_pago.justificacion like '%".$_POST["texto_busqueda"]."%'
												or beneficiarios.nombre like '%".$_POST["texto_busqueda"]."%')
											and orden_pago.numero_orden != ''
											and orden_pago.tipo = tipos_documentos.idtipos_documentos
											and beneficiarios.idbeneficiarios = orden_pago.idbeneficiarios")or die("error 5 ".mysql_error());
		  }
          
          
          while($bus_ordenes2 = mysql_fetch_array($sql_ordenes2)){

		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                    <td class='Browse' align='left'>&nbsp;<?=$bus_ordenes2["numero_orden"]?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes2["nombre"]?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes2["fecha_orden"]?></td>
                    <td class='Browse' style="text-align:right"><?=number_format($bus_ordenes2["total"],2,",",".")?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes2["justificacion"]?></td>
                    <td class='Browse' align='left'><?=$bus_ordenes2["estado"]?></td>
                  	<? 
					$tabla = "orden_pago";
					if ($tabla == "orden_compra_servicio"){
							$id = $bus_ordenes2["idorden_compra_servicio"];
					   }else if ($tabla == "orden_pago"){
					   		$id = $bus_ordenes2["idorden_pago"];
					   }?>
                    <td class='Browse' align="center">
                    <img src="imagenes/validar.png" style=" cursor:pointer" onClick="ubicarDocumento(<?=$id?>, '<?=$tabla?>')">
                    </td>
                       
                  </tr>
          <?
          }
		  ?>
          
        </table>
        
        
        </div>
 <? //}
 	} ?>
</table>




<br>
<br>
    <br>
	<h2 align="center">Recorrido del Documento</h2>
	<br>


<div id="divListaDocumentos" style=" width:98%; height:280px; overflow:auto;">
</div>