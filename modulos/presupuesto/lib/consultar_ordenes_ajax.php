<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);


switch($ejecutar){
	case "buscarOrdenes":
		$sql_consulta_oc = mysql_query("SELECT oc.idorden_compra_servicio,
												oc.numero_orden as numero_orden,
												oc.fecha_orden as fecha_orden,
												td.descripcion as tipo_documento,
												be.nombre as nombre_beneficiario,
												ca.codigo as codigo_categoria,
												ue.denominacion as denominacion_categoria,
												tp.denominacion as denominacion_tipo_presupuesto,
												ff.denominacion as denominacion_fuente_financiamiento,
												oc.total as total_orden,
												oc.estado as estado_orden,
												oc.anio as anio_orden
													FROM 
												orden_compra_servicio oc,
												tipos_documentos td,
												beneficiarios be,
												categoria_programatica ca,
												unidad_ejecutora ue,
												tipo_presupuesto tp,
												fuente_financiamiento ff
													WHERE 
												oc.numero_orden like '%".$numero_orden."%'
												AND be.idbeneficiarios = oc.idbeneficiarios
												AND be.nombre like '%".$beneficiario."%'
												AND ca.idcategoria_programatica = oc.idcategoria_programatica
												AND ue.idunidad_ejecutora = ca.idunidad_ejecutora
												AND tp.idtipo_presupuesto = oc.idtipo_presupuesto
												AND ff.idfuente_financiamiento = oc.idfuente_financiamiento
												AND oc.estado != 'elaboracion' 
												AND td.idtipos_documentos = oc.tipo
												group by oc.idorden_compra_servicio
												order by oc.numero_orden");
		?>
		<br>
        <br>
        <div style="background-color:#CCCCCC; font-size:18px"><center><strong>COMPROMISOS</strong></center></div>
        <br>

<table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
			  <thead>
			  <tr>
				<td width="8%" align="center" class="Browse">Nro. Orden</td>
                <td width="7%" align="center" class="Browse">Fecha Orden</td>
				<td width="8%" align="center" class="Browse">Tipo de Documento</td>
				<td width="27%" align="center" class="Browse">Beneficiario</td>
				<td width="34%" align="center" class="Browse">Cat. Programatica</td>
				<td width="9%" align="center" class="Browse">Total</td>
                <td width="7%" align="center" class="Browse">Estado</td>
                <td width="2%" align="center" class="Browse">Sel.</td>
			  </tr>
			  </thead>
		<?
		while($bus_consulta_oc = mysql_fetch_array($sql_consulta_oc)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            	<td class='Browse' align="left">&nbsp;<?=$bus_consulta_oc["numero_orden"]?></td>
                <td class='Browse' align="left"><?=$bus_consulta_oc["fecha_orden"]?></td>
                <td class='Browse' align="left"><?=$bus_consulta_oc["tipo_documento"]?></td>
                <td class='Browse' align="left"><?=$bus_consulta_oc["nombre_beneficiario"]?></td>
                <td class='Browse' align="left"><?="(".$bus_consulta_oc["codigo_categoria"].") ".$bus_consulta_oc["denominacion_categoria"]?></td>
                <td class='Browse' align="right"><?=number_format($bus_consulta_oc["total_orden"],2,",",".")?></td>
              <td class='Browse' align="center">
			  <?
              if($bus_consulta_oc["estado_orden"] == "anulado"){
			 	echo "<strong>".ucwords($bus_consulta_oc["estado_orden"])."</strong>"; 
			  }else{
			 	echo ucwords($bus_consulta_oc["estado_orden"]); 
			  }
			  
			  
			  ?></td>
              <td class='Browse' align="center"><a href="#" onClick="mostrarDetalles('<?=$bus_consulta_oc["idorden_compra_servicio"]?>', 'oc')"><img src="imagenes/validar.png"  style="cursor:pointer" border="0"></a></td>
                
</tr>
			<?
		}
		?>
		</table>
		<?
		
		
		
		$sql_consulta_op = mysql_query("SELECT op.idorden_pago,
												op.numero_orden as numero_orden,
												op.fecha_orden as fecha_orden,
												td.descripcion as tipo_documento,
												be.nombre as nombre_beneficiario,
												ca.codigo as codigo_categoria,
												ue.denominacion as denominacion_categoria,
												tp.denominacion as denominacion_tipo_presupuesto,
												ff.denominacion as denominacion_fuente_financiamiento,
												op.total as total_orden,
												op.estado as estado_orden,
												op.anio as anio_orden
													FROM 
												orden_pago op,
												tipos_documentos td,
												beneficiarios be,
												categoria_programatica ca,
												unidad_ejecutora ue,
												tipo_presupuesto tp,
												fuente_financiamiento ff
													WHERE 
												op.numero_orden like '%".$numero_orden."%'
												AND be.idbeneficiarios = op.idbeneficiarios
												AND be.nombre like '%".$beneficiario."%'
												AND ca.idcategoria_programatica = op.idcategoria_programatica
												AND ue.idunidad_ejecutora = ca.idunidad_ejecutora
												AND tp.idtipo_presupuesto = op.idtipo_presupuesto
												AND ff.idfuente_financiamiento = op.idfuente_financiamiento
												AND op.estado != 'elaboracion' 
												AND td.idtipos_documentos = op.tipo
												group by op.idorden_pago
												order by op.numero_orden");
		?>
		<br>
        <br>
        <div style="background-color:#CCCCCC; font-size:18px"><center><strong>CAUSADOS</strong></center></div>
        <br>
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
			  <thead>
			  <tr>
				<td width="8%" align="center" class="Browse">Nro. Orden</td>
                <td width="7%" align="center" class="Browse">Fecha Orden</td>
				<td width="8%" align="center" class="Browse">Tipo de Documento</td>
				<td width="27%" align="center" class="Browse">Beneficiario</td>
				<td width="34%" align="center" class="Browse">Cat. Programatica</td>
				<td width="9%" align="center" class="Browse">Total</td>
                <td width="7%" align="center" class="Browse">Estado</td>
                <td width="2%" align="center" class="Browse">Sel.</td>
			  </tr>
			  </thead>
		<?
		while($bus_consulta_op = mysql_fetch_array($sql_consulta_op)){
			?>
			<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
            	<td class='Browse' align="left">&nbsp;<?=$bus_consulta_op["numero_orden"]?></td>
                <td class='Browse' align="left"><?=$bus_consulta_op["fecha_orden"]?></td>
                <td class='Browse' align="left"><?=$bus_consulta_op["tipo_documento"]?></td>
                <td class='Browse' align="left"><?=$bus_consulta_op["nombre_beneficiario"]?></td>
                <td class='Browse' align="left"><?="(".$bus_consulta_op["codigo_categoria"].") ".$bus_consulta_op["denominacion_categoria"]?></td>
                <td class='Browse' align="right"><?=number_format($bus_consulta_op["total_orden"],2,",",".")?></td>
              <td class='Browse' align="center">
              <?
              if($bus_consulta_op["estado_orden"] == "anulado"){
			 	echo "<strong>".ucwords($bus_consulta_op["estado_orden"])."</strong>"; 
			  }else{
			 	echo ucwords($bus_consulta_op["estado_orden"]); 
			  }
			  ?>
              </td>
              <td class='Browse' align="center"><a href="#" onClick="mostrarDetalles('<?=$bus_consulta_op["idorden_pago"]?>', 'op')"><img src="imagenes/validar.png" border='0' style="cursor:pointer"></a></td>
                
            </tr>
			<?
		}
		?>
		</table>
		<?
	break;
	
	
	case "mostrarDetalles":
	if($tipo == "oc"){
		$sql_consulta_oc = mysql_query("SELECT oc.idorden_compra_servicio,
												oc.numero_orden as numero_orden,
												oc.fecha_orden as fecha_orden,
												td.descripcion as tipo_documento,
												be.nombre as nombre_beneficiario,
												ca.codigo as codigo_categoria,
												ue.denominacion as denominacion_categoria,
												tp.denominacion as denominacion_tipo_presupuesto,
												ff.denominacion as denominacion_fuente_financiamiento,
												oc.total as total_orden,
												oc.estado as estado_orden,
												oc.anio as anio_orden
													FROM 
												orden_compra_servicio oc,
												tipos_documentos td,
												beneficiarios be,
												categoria_programatica ca,
												unidad_ejecutora ue,
												tipo_presupuesto tp,
												fuente_financiamiento ff
													WHERE 
												oc.idorden_compra_servicio = '".$id."'
												AND be.idbeneficiarios = oc.idbeneficiarios
												AND ca.idcategoria_programatica = oc.idcategoria_programatica
												AND ue.idunidad_ejecutora = ca.idunidad_ejecutora
												AND tp.idtipo_presupuesto = oc.idtipo_presupuesto
												AND td.idtipos_documentos = oc.tipo
												AND ff.idfuente_financiamiento = oc.idfuente_financiamiento
												AND oc.estado != 'elaboracion' group by oc.idorden_compra_servicio");
												
			
			$bus_consulta_oc = mysql_fetch_array($sql_consulta_oc);
			
			if ($bus_consulta_oc["estado_orden"]=="pagado"){
				$sql_relacion_pago = mysql_query("select * from relacion_pago_compromisos where idorden_compra_servicio = '".$bus_consulta_oc["idorden_compra_servicio"]."'")or die(mysql_error());
				$bus_relacion_pago = mysql_fetch_array($sql_relacion_pago);
				$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_relacion_pago["idorden_pago"]."'");
				$bus_orden_pago = mysql_fetch_array($sql_orden_pago);
				$sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_orden_pago["idorden_pago"]."'");
				$bus_cheque = mysql_fetch_array($sql_cheque);
	  			$mostrar_estado = "Pagado : ".$bus_orden_pago["numero_orden"]." : ".$bus_orden_pago["fecha_orden"]." : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"];
			}else{
				$mostrar_estado = $bus_consulta_oc["estado_orden"];
			}
			?>
			<table width="90%" align="center">
  <tr>
                	<td class="viewPropTitle" align="right">Nro. Orden</td>
                    <td><strong><?=$bus_consulta_oc["numero_orden"]?></strong></td>
                    <td class="viewPropTitle" align="right">Fecha Orden</td>
                    <td><strong><?=$bus_consulta_oc["fecha_orden"]?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
               	</tr>
                <tr>
                	<td class="viewPropTitle" align="right">Tipo de Documento</td>
                    <td><strong><?=$bus_consulta_oc["tipo_documento"]?></strong></td>
                     <td>&nbsp;</td>
                    <td>&nbsp;</td>
                     <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>   
                    <td class="viewPropTitle" align="right">Estado</td>
                    <td><strong><?=$mostrar_estado?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                     <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </tr>
                	<td class="viewPropTitle" align="right">Fuente Financiamiento</td>
                    <td><strong><?=$bus_consulta_oc["denominacion_fuente_financiamiento"]?></strong></td>
                    <td class="viewPropTitle" align="right">Tipo de Presupuesto</td>
                    <td><strong><?=$bus_consulta_oc["denominacion_tipo_presupuesto"]?></strong></td>
                    <td class="viewPropTitle" align="right">A&ntilde;o</td>
                    <td><strong><?=$bus_consulta_oc["anio_orden"]?></strong></td>
                </tr>
                </tr>
                	<td class="viewPropTitle" align="right">Categor&iacute;a Program&aacute;tica</td>
                    <td colspan="5"><strong>(<?=$bus_consulta_oc["codigo_categoria"]?>)</strong>&nbsp;<strong><?=$bus_consulta_oc["denominacion_categoria"]?></strong></td>
                </tr>
                </tr>
                	<td class="viewPropTitle" align="right">Beneficiario</td>
                    <td colspan="3"><strong><?=$bus_consulta_oc["nombre_beneficiario"]?></strong></td>
                    <td class="viewPropTitle" align="right">Total</td>
                    <td><strong><?=number_format($bus_consulta_oc["total_orden"],2,",",".")?></strong></td>
                </tr>
                </table>
                <br>
			<?
            $sql_partidas = mysql_query("select * from partidas_orden_compra_servicio where idorden_compra_servicio = ".$id."");
																		
$num_partidas = mysql_num_rows($sql_partidas);
if($num_partidas != 0){
	?>
    <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse" colspan="4"><div align="center">Partida</div></td>
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
      </thead>
          <? 
          while($bus_partidas = mysql_fetch_array($sql_partidas)){
		  
		  $sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = ".$bus_partidas["idmaestro_presupuesto"]."");
		  $bus_maestro = mysql_fetch_array($sql_maestro);
		  //echo $bus_partidas["idmaestro_presupuesto"];
		  
          	if($bus_partidas["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus_partidas["estado"] == "disponible"){
			?>
			
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}
			
			
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'")or die(mysql_error());
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>
				<?
                $disponible = consultarDisponibilidad($bus_maestro["idRegistro"]);
				?>

             <td class='Browse' align='right'><?=number_format($bus_partidas["monto"],2,',','.')?></td>
          </tr>
          <?
          }
          ?>
</table>																	
		<?
            }else{
            echo "No hay Partidas Asociadas";
            }																	

			
	}else{
		$sql_consulta_op = mysql_query("SELECT op.idorden_pago,
												op.numero_orden as numero_orden,
												op.fecha_orden as fecha_orden,
												td.descripcion as tipo_documento,
												be.nombre as nombre_beneficiario,
												ca.codigo as codigo_categoria,
												ue.denominacion as denominacion_categoria,
												tp.denominacion as denominacion_tipo_presupuesto,
												ff.denominacion as denominacion_fuente_financiamiento,
												op.total as total_orden,
												op.estado as estado_orden,
												op.anio as anio_orden
													FROM 
												orden_pago op,
												tipos_documentos td,
												beneficiarios be,
												categoria_programatica ca,
												unidad_ejecutora ue,
												tipo_presupuesto tp,
												fuente_financiamiento ff
													WHERE 
												op.idorden_pago = '".$id."'
												AND be.idbeneficiarios = op.idbeneficiarios
												AND ca.idcategoria_programatica = op.idcategoria_programatica
												AND ue.idunidad_ejecutora = ca.idunidad_ejecutora
												AND tp.idtipo_presupuesto = op.idtipo_presupuesto
												AND td.idtipos_documentos = op.tipo
												AND ff.idfuente_financiamiento = op.idfuente_financiamiento
												AND op.estado != 'elaboracion' group by op.idorden_pago");
	
	$bus_consulta_op = mysql_fetch_array($sql_consulta_op);
	
	if ($bus_consulta_op["estado_orden"]=="pagada"){
		  $sql_cheque = mysql_query("select * from pagos_financieros where idorden_pago = '".$bus_consulta_op["idorden_pago"]."'");
		  $bus_cheque = mysql_fetch_array($sql_cheque);
		  $mostrar_estado = "Pagado : ".$bus_cheque["numero_cheque"]." : ".$bus_cheque["fecha_cheque"];
	}else{
		$mostrar_estado = $bus_consulta_op["estado_orden"];
	}
	?>
			<table width="90%" align="center">
            	<tr>
                	<td class="viewPropTitle" align="right">Nro. Orden</td>
                    <td><strong><?=$bus_consulta_op["numero_orden"]?></strong></td>
                    <td class="viewPropTitle" align="right">Fecha Orden</td>
                    <td><strong><?=$bus_consulta_op["fecha_orden"]?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
               	</tr>
                <tr>
                	<td class="viewPropTitle" align="right">Tipo de Documento</td>
                    <td><strong><?=$bus_consulta_op["tipo_documento"]?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="viewPropTitle" align="right">Estado</td>
                    <td><strong><?=$mostrar_estado?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </tr>
                	<td class="viewPropTitle" align="right">Fuente Financiamiento</td>
                    <td><strong><?=$bus_consulta_op["denominacion_fuente_financiamiento"]?></strong></td>
                    <td class="viewPropTitle" align="right">Tipo de Presupuesto</td>
                    <td><strong><?=$bus_consulta_op["denominacion_tipo_presupuesto"]?></strong></td>
                    <td class="viewPropTitle" align="right">A&ntilde;o</td>
                    <td><strong><?=$bus_consulta_op["anio_orden"]?></strong></td>
                </tr>
                </tr>
                	<td class="viewPropTitle" align="right">Categor&iacute;a Program&aacute;tica</td>
                    <td colspan="5"><strong>(<?=$bus_consulta_op["codigo_categoria"]?>)</strong>&nbsp;<strong><?=$bus_consulta_op["denominacion_categoria"]?></strong></td>
                </tr>
                </tr>
                	<td class="viewPropTitle" align="right">Beneficiario</td>
                    <td colspan="3"><strong><?=$bus_consulta_op["nombre_beneficiario"]?></strong></td>
                    <td class="viewPropTitle" align="right">Total</td>
                    <td><strong><?=number_format($bus_consulta_op["total_orden"],2,",",".")?></strong></td>
                </tr>
</table>
                <br>
                
                <?
				
$sql = mysql_query("select * from partidas_orden_pago where idorden_pago = ".$id."");

																	
$num = mysql_num_rows($sql);
if($num != 0){
	?>
    <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0" class="Browse">
<thead>
          <tr>
            <td class="Browse" colspan="5"><div align="center">Partida</div></td>
            
            <td class="Browse"><div align="center">Descripci&oacute;n</div></td>
            <td class="Browse"><div align="center">Monto a Comprometer</div></td>
          </tr>
      </thead>
          <? 
          while($bus = mysql_fetch_array($sql)){
          	if($bus["estado"] == "sobregiro"){
		  ?>
			<tr bgcolor="#FFFF00" onMouseOver="setRowColor(this, 0, 'over', '#FFFF00', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FFFF00', '#EAFFEA', '#FFFFAA')">
			<?
			}else if($bus["estado"] == "disponible"){
			?>
			
            <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
			<?
			}
			
			$sql_maestro = mysql_query("select * from maestro_presupuesto where idRegistro = '".$bus["idmaestro_presupuesto"]."'");
			$bus_maestro = mysql_fetch_array($sql_maestro);
          $sql_clasificador = mysql_query("select * from clasificador_presupuestario where idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'")or die(mysql_error());
		  $bus_clasificador = mysql_fetch_array($sql_clasificador);
		  ?>
            <td class='Browse' align='left'><?=$bus_categoria["codigo"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["partida"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["generica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["sub_especifica"]?></td>
            <td class='Browse' align='left'><?=$bus_clasificador["denominacion"]?></td>
	        <td class='Browse' align='right'><?=number_format($bus["monto"],2,',','.')?></td>
          </tr>
          <?
          }
          ?>
</table>																	
		<?
    }else{
	echo "No hay Partidas Asociadas";
    }																	

	
	}
	
	break;
}
?>