<?
include("../../../conf/conex.php");
Conectarse();
extract($_POST);
extract($_GET);


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
                <td width="7%" align="center" class="Browse">Centralizar</td>
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
              <td class='Browse' align="center"><a href="#" onClick="centralizarOrden('<?=$bus_consulta_oc["idorden_compra_servicio"]?>')"><img src="imagenes/refrescar.png"  style="cursor:pointer" border="0"></a></td>
                
</tr>
			<?
		}
		?>
		</table>
		<?
	
	break;
	
	
	
	case "centralizarOrden":
		$sql_eliminar = mysql_query("delete from relacion_documentos_remision where tabla = 'orden_compra_servicio' and id_documento = '".$idorden_compra."'");
		$sql_eliminar = mysql_query("delete from relacion_documentos_remision where tabla = 'orden_compra_servicio' and id_documento = '".$idorden_compra."'");
		
		$sql_actualizar = mysql_query("update orden_compra_servicio set ubicacion = '0' where idorden_compra_servicio = '".$idorden_compra."'");
		
		if($sql_actualizar){
			echo "exito";
		}
	break;
	
}
?>