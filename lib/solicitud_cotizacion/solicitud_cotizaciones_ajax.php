<?
session_start();
include("../../conf/conex.php");
include("../../funciones/funciones.php");
Conectarse();

extract($_POST);

if($ejecutar == "consultaItems"){
	$sql_items = mysql_query("select nro_items from solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
	$bus_items = mysql_fetch_array($sql_items);
	echo $bus_items["nro_items"];
}



if($ejecutar == "procesar"){//Se cambia a Procesar
$sql = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
$num = mysql_num_rows($sql);
if($num > 0){
	$sql =  mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
	$num = mysql_num_rows($sql);
		if($num > 0){

			$sql = mysql_query("select nro_solicitud_cotizacion from configuracion_compras");
			$bus = mysql_fetch_array($sql);
				$numero_actual = $bus["nro_solicitud_cotizacion"];
					if($_SESSION["modulo"] == 4){
						$siglas = "SCAD";
					}else if($_SESSION["modulo"] == 3){
						$siglas = "SCCS";
					}
					$numero = $siglas."-".$_SESSION["anio_fiscal"]."-".$numero_actual;
				
				
			$sql_solicitud = mysql_query("select * from solicitud_cotizacion where numero = '".$numero."'");
			$num_solicitud = mysql_num_rows($sql_solicitud);
				
				while($num_solicitud > 0){
					$sql_actualizar = mysql_query("update configuracion_compras set nro_solicitud_cotizacion = nro_solicitud_cotizacion+1");
					$sql = mysql_query("select nro_solicitud_cotizacion from configuracion_compras");
					$bus = mysql_fetch_array($sql);
					$numero_actual = $bus["nro_solicitud_cotizacion"];
					if($_SESSION["modulo"] == 4){
						$siglas = "SCAD";
					}else if($_SESSION["modulo"] == 3){
						$siglas = "SCCS";
					}
					$numero = $siglas."-".$_SESSION["anio_fiscal"]."-".$numero_actual;
					$sql_solicitud = mysql_query("select * from solicitud_cotizacion where numero = '".$numero."'");
					$num_solicitud = mysql_num_rows($sql_solicitud);
				}

			$sql_actualizar = mysql_query("update configuracion_compras set nro_solicitud_cotizacion = nro_solicitud_cotizacion+1");
			
				$sql = mysql_query("update solicitud_cotizacion set numero = '".$numero."',
																	fecha_solicitud = '".$fecha_validada."',
																	 tipo = '".$tipo."', 
																	 justificacion = '".$justificacion."', 
																	 observaciones = '".$observaciones."',
																	 estado = 'procesado',
																	 status = 'a',
																	 usuario = '".$_SESSION["login"]."',
																	 fechayhora = '".date("Y-m-d H:i:s")."',
																	 ordenado_por = '".$ordenado_por."',
																	 cedula_ordenado = '".$cedula_ordenado."' where idsolicitud_cotizacion = ".$id_solicitud."");
				if($sql){
				registra_transaccion("Procesar Solicitud de Cotizacion de Administracion (".$numero.")",$login,$fh,$pc,'solicitud_cotizacion');
					echo "exito";
				}else{
				registra_transaccion("Procesar Solicitud de Cotizacion ERROR (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
					echo "error";
				}
				
		}else{
		registra_transaccion("Procesar Solicitud de Cotizacion ERROR (No hay Materiales ".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
			echo "noMateriales";
		}
		
	}else{
	registra_transaccion("Procesar Solicitud de Cotizacion ERROR (No hay Proveedores ".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
			echo "noProveedores";
	}
}




if($ejecutar == "solicitud"){//SE SI REGISTRA LA CABEZERA DE LA SOLICITUD
	$sql = mysql_query("insert into solicitud_cotizacion(fecha_solicitud,
														 tipo, 
														 justificacion, 
														 observaciones,
														 estado,
														 status,
														 usuario,
														 fechayhora,
														 ordenado_por,
														 cedula_ordenado,
														 modo_comunicacion,
														 tipo_actividad)values(
															 '".$fecha_validada."',
															 '".$tipo."',
															 '".$justificacion."',
															 '".$observaciones."',
															 'espera',
															 'a',
															 '".$_SESSION["login"]."',
															 '".date("Y-m-d H:i:s")."',
															 '".$ordenado_por."',
															 '".$cedula_ordenado."',
															 '".$modo_comunicacion."',
															 '".$tipo_actividad."'
														 )")or die(mysql_error());
	$id = mysql_insert_id();
	echo $id;
	registra_transaccion("Insertar Datos Basicos de Solicitud de Cotizacion de Administracion (".$id.")",$login,$fh,$pc,'solicitud_cotizacion');
}


if($ejecutar == "actualizarSolicitud"){//SE SI REGISTRA LA CABEZERA DE LA SOLICITUD
	$sql = mysql_query("update solicitud_cotizacion set fecha_solicitud = '".$fecha_validada."',
														 tipo = '".$tipo."', 
														 justificacion = '".$justificacion."', 
														 observaciones = '".$observaciones."',
														 estado = 'espera',
														 status = 'a',
														 usuario = '".$_SESSION["login"]."',
														 fechayhora = '".date("Y-m-d H:i:s")."',
														 ordenado_por = '".$ordenado_por."',
														 modo_comunicacion = '".$modo_comunicacion."',
														 tipo_actividad = '".$tipo_actividad."',
														 cedula_ordenado = '".$cedula_ordenado."' where idsolicitud_cotizacion = ".$id_solicitud."");
	if($sql){
		registra_transaccion("Actualizar Solicitud de Cotizacion de Administracion (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
		echo "exito";
	}else{
	registra_transaccion("Actualizar Solicitud de Cotizacion de Administracion ERROR (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
		echo "error";
	}
}


if($ejecutar == "proveedores"){// SI SE REGISTRAR PROVEEDORES
	if($accion != "consultar"){	
		if($accion == "ingresar"){
				$sql = mysql_query("select * from proveedores_solicitud_cotizacion where 
																					idsolicitud_cotizacion = '".$id_solicitud."' 
																					and idbeneficiarios = '".$idbeneficiario."'");
					$num = mysql_num_rows($sql);
					if($num != 0){
						echo "repetido";
						exit();
					}
				$sql = mysql_query("insert into proveedores_solicitud_cotizacion (idsolicitud_cotizacion,
																					idbeneficiarios,
																					status,
																					usuario,
																					fechayhora)values(
																					'".$id_solicitud."',
																					'".$idbeneficiario."',
																					'a',
																					'".$_SESSION["login"]."',
																					'".date("Y-m-d H:i:s")."'
																					)");
					registra_transaccion("Ingresar Proveedores a la Solicitud de Cotizacion (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');														
		}else if ($accion == "eliminar"){
				$sql = mysql_query("delete from proveedores_solicitud_cotizacion where 
																					idsolicitud_cotizacion = '".$id_solicitud."' and 
																					idbeneficiarios = '".$idbeneficiario."'");
			registra_transaccion("Eliminar Proveedores de Solicitud de Cotizacion de Administracion (".$idbeneficiario.")",$login,$fh,$pc,'solicitud_cotizacion');
		}
	}
		$sql = mysql_query("select * from proveedores_solicitud_cotizacion, 
											beneficiarios 
											where 
											proveedores_solicitud_cotizacion.idsolicitud_cotizacion = ".$id_solicitud." and
											beneficiarios.idbeneficiarios = proveedores_solicitud_cotizacion.idbeneficiarios")or die("aquie essss".mysql_error());
		$num = mysql_num_rows($sql);
		if($num != 0){
			$sql_general = mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
			$bus_general = mysql_fetch_array($sql_general);
	
			?>
		    
            <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                    <td class="Browse" id="tituloGanador" <? if($bus_general["estado"] == "otorgado" || $bus_general["estado"] == "finalizado"){echo " style='display:block'";}else{echo " style='display:none'";}?> align="center"><div align="center" width="10%">Ganador</div></td>
                    
                    <td class="Browse" width="120%"><div align="center" >Nombre</div></td>
                    <td class="Browse" width="30%"><div align="center" >RIF</div></td>
                     <?
                    if($bus_general["estado"] == "espera"){
                    ?>
                    <td class="Browse" width="10%"><div align="center" >Acciones</div></td>
                    <?
                    }
                    ?>
                  </tr>
                  </thead>
                  <? 
                  $i = 0;
                  while($bus = mysql_fetch_array($sql)){
                  ?>
                          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                            <td class="Browse" <? if($bus_general["estado"] == "otorgado" || $bus_general["estado"] == "finalizado"){echo " style='display:block'";}else{echo " style='display:none'";}?> id="selectGanador<?=$i?>" align="center">&nbsp;
                            
                            <? 
                            if($bus["ganador"] == "y"){
                                ?>
                                <img src="imagenes/validar.png" title="Proveedor Ganador" onClick="cargarDatosGanador('<?=$bus["tipo_procedimiento"]?>', '<?=$bus["justificacion"]?>', '<?=$bus["nro_cotizacion"]?>', '<?=$bus["fecha_cotizacion"]?>')">
                                <?
                            }
                            if($bus_general["estado"] == "procesado"){
                                ?>
                                <input type="radio" name="radioGanador" id="radioGanador" value="<?=$bus["idbeneficiarios"]?>" onClick="document.getElementById('divDatosGanador').style.display='block', document.getElementById('id_radio').value=this.value">
                                <?
                            }
                            ?>
                           
                            </td>
                            <td class='Browse' align='left' width="120%"><?=$bus["nombre"]?></td>
                            <td class='Browse' align='center' width="30%"><?=$bus["rif"]?></td>
                            <?
                            if($bus_general["estado"] == "espera"){
                            ?>
                            <td class='Browse' align="center" width="10%">
        
                            <img src="imagenes/delete.png" onClick="eliminarProveedor(document.form2.id_solicitud.value, <?=$bus["idbeneficiarios"]?>)" style="cursor:pointer">
                            </td>
                            <?
                            }
                            ?>
                            
                          </tr>
        
                   <?
                   $i++;
                  }
                  ?>
                </table>
            
		<br>
        
    <? 
    //style="display:block; position:absolute; left:50%; width:96%; margin-left:-610px; height:100px; height:auto !important; min-height:100px; margin-top:240px; overflow:auto"    
    ?>
    <div id="divDatosGanador" style="display:none; background-color:#CCCCCC; left:50%; width:65%; margin-left:0 auto; border:#000000 1px solid">
       <form name="formulario_ganador" method="post">
       <?
	   $sql_ganador = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud." and ganador = 'y'");
       $num_ganador = mysql_num_rows($sql_ganador);
	   $bus_ganador = mysql_fetch_array($sql_ganador);
	   if($num_ganador > 0){
	   	?>
		
		        <table align="center">
        <tr>
            <td>Tipo Procedimiento:</td>
            <td id="divTipoProcedimiento">
            <strong><?=$bus_ganador["tipo_procedimiento"]?>&nbsp;</strong>
            </td>
            <td>Justificacion:</td>
            <td id="divJustificacionGanador"><strong><?=$bus_ganador["justificacion"]?>&nbsp;</strong></td>
         </tr>
         <tr>
            <td>Numero Cotizacion:</td>
            <td id="divNumeroCotizacion"><strong><?=$bus_ganador["nro_cotizacion"]?>&nbsp;</strong></td>
            <td>Fecha Cotizacion:</td>
            <td id="divFechaCotizacion"><strong><?=$bus_ganador["fecha_cotizacion"]?>&nbsp;</strong></td>
            
         </tr>
        </table>
		<?
	   }else{
	   ?> 
        
        <table align="center">
        <tr>
            <td>Modalidad de Contratacion:</td>
            <td id="divTipoProcedimiento">
            <?
            $sql_modalidad_contratacion = mysql_query("select * from modalidad_contratacion");	
			?>
            
            <select id="tipo_procedimiento" name="tipo_procedimiento">
               <?
              while($bus_modalidad_contratacion = mysql_fetch_array($sql_modalidad_contratacion)){
			  ?>
			  
			  <option value="<?=$bus_modalidad_contratacion["idmodalidad_contratacion"]?>"><?=$bus_modalidad_contratacion["descripcion"]?></option>
			  <?
			  
			  }
			   ?>
             </select>            </td>
            <td>Formato:</td>
            <td id="divJustificacionGanador">
            
            <select name="formato" id="formato">
              <option>.:: Seleccione ::.</option>
              <option value="Analisis de Cotizaciones" onclick="
              <?
          $sql_consulta_participantes = mysql_query("select b.idbeneficiarios 
		  													from 
															proveedores_solicitud_cotizacion ps,
															beneficiarios b
															where 
															ps.idsolicitud_cotizacion = '".$id_solicitud."'
															and b.idbeneficiarios = ps.idbeneficiarios");
		  
		  while($bus_consulta_participantes = mysql_fetch_array($sql_consulta_participantes)){
		  ?>
              document.getElementById('divformato_<?=$bus_consulta_participantes["idbeneficiarios"]?>').style.display='block', document.getElementById('divformato_acto_<?=$bus_consulta_participantes["idbeneficiarios"]?>').style.display='none',
              document.getElementById('div_<?=$bus_consulta_participantes["idbeneficiarios"]?>').style.display='block'
              <?
              }
			  ?>
              ">Analisis de Cotizaciones</option>
              <option value="Acto Motivado" onclick="
              <?
          $sql_consulta_participantes = mysql_query("select b.idbeneficiarios 
		  													from 
															proveedores_solicitud_cotizacion ps,
															beneficiarios b
															where 
															ps.idsolicitud_cotizacion = '".$id_solicitud."'
															and b.idbeneficiarios = ps.idbeneficiarios");
		  
		  while($bus_consulta_participantes = mysql_fetch_array($sql_consulta_participantes)){
		  ?>
              document.getElementById('divformato_<?=$bus_consulta_participantes["idbeneficiarios"]?>').style.display='none', document.getElementById('divformato_acto_<?=$bus_consulta_participantes["idbeneficiarios"]?>').style.display='block',
              document.getElementById('div_<?=$bus_consulta_participantes["idbeneficiarios"]?>').style.display='block'
              <?
              }
			  ?>
              ">Acto Motivado</option>
            </select></td>
         </tr>
<?
          $sql_consulta_participantes = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."'");
		  
		  while($bus_consulta_participantes = mysql_fetch_array($sql_consulta_participantes)){
		  ?>
		  <tr>
          <td colspan="4" style="">
			  <?
              $sql_beneficiario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_consulta_participantes["idbeneficiarios"]."'");
              $bus_beneficiario = mysql_fetch_array($sql_beneficiario);
              echo "<div id='div_".$bus_beneficiario["idbeneficiarios"]."' style='display:none; background-color:#003399; color:#FFFFFF; font-weight:bold'>".$bus_beneficiario["nombre"]."</div>";
              ?>
          </td>
          </tr>
          <tr>
          <td colspan="4">
           <table id="divformato_<?=$bus_beneficiario["idbeneficiarios"]?>" style="display:none; border:0px" cellpadding="0" cellspacing="0">
                   <tr>
                   	<td>Descuento</td>
                    <td>
                    <input type="hidden" name="idparticipante"  value="<?=$bus_beneficiario["idbeneficiarios"]?>">
                        <select name="descuento" >
                            <option value="si">si</option>
                            <option value="no">no</option>
                        </select>                    </td>
                    <td>&nbsp;Garantia y Servicio en la Zona</td>
                    <td>
                        <select name="garantia_servicio" >
                            <option value="si">si</option>
                            <option value="no">no</option>
                        </select>                    </td>
                    <td>&nbsp;Calidad</td>
                    <td>
                        <select name="calidad" >
                            <option value="si">si</option>
                            <option value="no">no</option>
                        </select>                    </td>
                    <tr>
                    <td>Gtos FLT</td>
                    <td>
                        <select name="gtos_flt">
                            <option value="si">si</option>
                            <option value="no">no</option>
                        </select>                    </td>
                    
                    <td>&nbsp;Emp. Local</td>
                    <td>
                        <select name="emp_local">
                            <option value="si">si</option>
                            <option value="no">no</option>
                        </select>                    </td>
                    <td>&nbsp;Experiencia</td>
                    <td>
                        <select name="experiencia">
                            <option value="si">si</option>
                            <option value="no">no</option>
                        </select>                    
                    </td>
                    </tr>
                    <tr>
                    <td>
                        Monto Total&nbsp; 
                    </td>
                    <td>
                        <input type="text" name="monto_total" style="text-align:right" size="10">                    
                    </td>
                    <td>
                        &nbsp;Nro. Cotizacion 
                    </td>
                    <td>
                        <input type="text" name="nro_cotizacion" id="nro_cotizacion" style="text-align:right" size="10">                    
                    </td>
                    </tr>
                    
                   
               </table>
               
               
               <table id="divformato_acto_<?=$bus_beneficiario["idbeneficiarios"]?>" style="display:none; border:0px" cellpadding="0" cellspacing="0">
                   <tr>
                   	<td>Articulo</td>
                    <td><input type="text" id="articulo"></td>
                    <td>&nbsp;Numeral</td>
                    <td><input type="text" id="numeral"></td>
                   </tr>
               </table>           
              
		  <?
		  }
		  ?>         
               
               
               
              </td>
          </tr>
          <tr>
            <td>Justificacion:</td>
            <td colspan="3" id="divNumeroCotizacion3"><textarea id="justificacionGanador" name="justificacionGanador" cols="70" rows="5"></textarea></td>
          </tr>
         <tr>
         	<td colspan="4">
            <input name="id_radio" type="hidden" id="id_radio">
          	<input type="button" class="button" value="Registrar Ganador" name="botonRegistrarGanador" id="botonRegistrarGanador" onClick="registrarGanador()"></td>
         </tr>
        </table>
        
        
     </div>
          <?
		  }
	  }else{
	  echo "No hay Proveedores Asociados";
	  }
	  ?>
	  </form>
      </div>
      
          <?
}




if($ejecutar == "materiales"){// SI SE REGISTRAR MATERIALES
	if($accion != "consultar"){
		if($accion == "ingresar"){
		
			$sql = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."' and idarticulos_servicios = '".$id_material."'");
			$num = mysql_num_rows($sql);
			if($num != 0){
				echo "repetido";
				exit();
			}
			$sql = mysql_query("insert into articulos_solicitud_cotizacion (idsolicitud_cotizacion,
																				idarticulos_servicios,
																				cantidad,
																				status,
																				usuario,
																				fechayhora)values(
																				'".$id_solicitud."',
																				'".$id_material."',
																				'".$cantidad."',
																				'a',
																				'".$_SESSION["login"]."',
																				'".date("Y-m-d H:i:s")."'
																				)");
			registra_transaccion("Ingresar Materiales a la Solicitud de Cotizacion de Administracion (ID Solicitud: ".$id_solicitud." ID Material: ".$id_material.")",$login,$fh,$pc,'articulos_solicitud_cotizacion');
			$sql_actualizar = mysql_query("update solicitud_cotizacion set nro_items = (nro_items)+1 where idsolicitud_cotizacion = ".$id_solicitud."")or die("insertar ".mysql_error());
			
																				
		}else if($accion == "eliminar"){
			$sql = mysql_query("delete from articulos_solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."' and idarticulos_servicios = '".$id_material."'");
			$sql_actualizar = mysql_query("update solicitud_cotizacion set nro_items = (nro_items)-1 where idsolicitud_cotizacion = ".$id_solicitud."")or die("eliminar ".mysql_error());
			registra_transaccion("Eliminar Articulos de Solicitud de Cotizacion de Administracion (ID Solicitud: ".$id_solicitud." ID Material: ".$id_material.")",$login,$fh,$pc,'articulos_solicitud_cotizacion');
		}
	}

	
	$query = "select * from articulos_solicitud_cotizacion, articulos_servicios, unidad_medida
									 where 
									 articulos_solicitud_cotizacion.idsolicitud_cotizacion = ".$id_solicitud." and
									  articulos_servicios.idarticulos_servicios = articulos_solicitud_cotizacion.idarticulos_servicios and 
									  articulos_servicios.idunidad_medida = unidad_medida.idunidad_medida";
	$sql = mysql_query($query)or die("seleccionando: ".mysql_error());
	$num = mysql_num_rows($sql);
	if($num != 0){
			$sql_general = mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
			$bus_general = mysql_fetch_array($sql_general);

$sql_proveedores = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."' and ganador = 'y'");
$bus_proveedores = mysql_fetch_array($sql_proveedores);

?>

  <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="100%">
				  <thead>
				  <tr>
					<td class="Browse" width="15%"><div align="center">Codigo</div></td>
					<td class="Browse" width="70%"><div align="center">Descripcion</div></td>
					<td class="Browse" width="5%"><div align="center">Und</div></td>
					<td class="Browse" width="10%"><div align="center">Cantidad</div></td>
					<?
					if($bus_general["estado"] == "otorgado" || $bus_general["estado"] == "finalizado"){
						if($bus_general["estado"] == "otorgado"){
					?>
						<td class="Browse" width="10%"><div align="center">Precio</div></td>
						 <?
						 }
						 ?>
						<td class="Browse" width="10%"><div align="center">Precio Unitario</div></td>
						<td class="Browse" width="10%"><div align="center">Total</div></td>
					<?
					}
					?>
					
					<?
					if($bus_general["estado"] == "espera" or $bus_general["estado"] == "otorgado"){
					?>
					<td class="Browse" width="10%"><div align="center">Acciones</div></td>
					<?
					}
					?>
				  </tr>
				  </thead>
				  <? 
				  $sql= mysql_query($query);
				  while($bus = mysql_fetch_array($sql)){
				  //var_dump($bus);
				  ?>
				  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
					<td class='Browse' align='left'><?=$bus["codigo"]?></td>
					<td class='Browse' align='left'><?=$bus["15"]?></td>
					<td class='Browse' align='center'><?=$bus["abreviado"]?></td>
					<td class='Browse' style="text-align:right"><?=$bus["cantidad"]?></td>
					<?
					if($bus_general["estado"] == "otorgado" || $bus_general["estado"] == "finalizado"){
						if($bus_general["estado"] == "otorgado"){
					?>
						<td class="Browse"><div align="center">
						<input align="right" style="text-align:right" name="precio<?=$bus["idarticulos_solicitud_cotizacion"]?><?=$h?>" 
																	type="hidden" 
																	id="precio<?=$bus["idarticulos_solicitud_cotizacion"]?><?=$h?>" 
																	size="10"
																	value="<?=$bus["precio_unitario"]?>">
						<input align="right" style="text-align:right" name="mostrarPrecio<?=$bus["idarticulos_solicitud_cotizacion"]?><?=$h?>" 
																	type="text" 
																	id="mostrarPrecio<?=$bus["idarticulos_solicitud_cotizacion"]?><?=$h?>" 
																	size="10"
																	onclick="this.select()"
																	value="<?=number_format($bus["precio_unitario"],2,',','.')?>">
                                                                    
                                                                    <!-- onblur="asignarValor('precio<?=$bus["idarticulos_solicitud_cotizacion"]?>','mostrarPrecio<?=$bus["idarticulos_solicitud_cotizacion"]?>')"-->
					   <?
					   }
					   ?>
						</div></td>
						<td class="Browse" align="right"><? if($bus["precio_unitario"] == ""){echo "0,00";}else{echo number_format($bus["precio_unitario"],2,',','.');}?></td>
						<td class="Browse" align="right"><? if($bus["total"] == 0){echo number_format($bus["8"],2,',','.');}else{echo number_format($bus["total"],2,',','.');}?></td>
					<?
					}
					if($bus_general["estado"] == "espera"){
					?>
					<td class='Browse' align="center">
				   <a href="javascript:;" onClick="eliminarMateriales(document.form2.id_solicitud.value, <?=$bus["idarticulos_servicios"]?>)"><img src="imagenes/delete.png"></a>					</td>
					<?
					}
					if($bus_general["estado"] == "otorgado"){
					?>
					<td class='Browse' align="center">
					<a href="javascript:;" onclick=""><img src="imagenes/refrescar.png" onClick="actualizarPrecio('<?=$bus["idsolicitud_cotizacion"]?>', document.getElementById('mostrarPrecio<?=$bus["idarticulos_solicitud_cotizacion"]?>').value, '<?=$bus["idarticulos_servicios"]?>', <?=$bus["idarticulos_solicitud_cotizacion"]?>)" title="Actualizar Precio"></a>					</td>
					<?
					}
					?>
				  </tr>
				   <?
				  }
				  ?>
  			</table>

				<?
			}else{
		echo "No hay Materiales Asociados";
		}
}





if($ejecutar == "datosBasicos"){// CONSULTA DE DATOS BASICOS
	if($accion == "consultar"){
		$sql = mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
		$bus_consulta = mysql_fetch_array($sql);
	}
	
	switch($bus_consulta["estado"]){
	  case "espera":
		  $mostrar_estado = "En Elaboraci&oacute;n";
		  break;
	  case "procesado":
	  	  $mostrar_estado = "Procesado";
		  break;
	  case "otorgado":
	  	  $mostrar_estado = "Otorgado";
		  break;
	  case "finalizado":
	  	  $mostrar_estado = "Finalizado";
		  break;
	  case "anulado":
	  	  $mostrar_estado = "Anulado";
		  break;
	  case "ordenado":
	   	$sql_relacion_pago = mysql_query("select * from relacion_compra_solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."'")or die(mysql_error());
		$bus_relacion_pago = mysql_fetch_array($sql_relacion_pago);
	  	$mostrar_estado = "Ordenado : ".$bus_relacion_pago["numero_orden"]." : ".$bus_relacion_pago["fecha_orden"];
		break;
	  }
	  
	  
		echo $bus_consulta["idsolicitud_cotizacion"]		."|.|".
		$bus_consulta["numero"]								."|.|".
		$bus_consulta["fecha_solicitud"]					."|.|".
		$bus_consulta["estado"]								."|.|".
		$mostrar_estado										."|.|".
		$bus_consulta["tipo"]								."|.|".
		$bus_consulta["nro_items"]							."|.|".
		$bus_consulta["justificacion"]						."|.|".
		$bus_consulta["observaciones"]						."|.|".
		$bus_consulta["modo_comunicacion"]					."|.|".
		$bus_consulta["tipo_actividad"]						."|.|".
		$bus_consulta["ordenado_por"]						."|.|".
		$bus_consulta["cedula_ordenado"]					."|.|";
		
}



if($ejecutar == "duplicar"){
	$sql = mysql_query("select * from solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
	$bus = mysql_fetch_array($sql);
	$sql = mysql_query("insert into solicitud_cotizacion(fecha_solicitud,
															tipo,
															justificacion,
															observaciones,
															ordenado_por,
															cedula_ordenado,
															nro_items,
															exento,
															sub_total,
															impuesto,
															total,
															estado,
															status,
															usuario,
															fechayhora)value('".$bus["fecha_solicitud"]."',
																			'".$bus["tipo"]."',
																			'".$bus["justificacion"]."',
																			'".$bus["observaciones"]."',
																			'".$bus["ordenado_por"]."',
																			'".$bus["cedula_ordenado"]."',
																			'".$bus["nro_items"]."',
																			'".$bus["exento"]."',
																			'".$bus["sub_total"]."',
																			'".$bus["impuesto"]."',
																			'".$bus["total"]."',
																			'espera',
																			'a',
																			'".$login."',
																			'".date("Y-m-d H:i:s")."')");
	$id = mysql_insert_id();

	$sql_proveedores = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
	while($bus_proveedores = mysql_fetch_array($sql_proveedores)){
		$sql_insert = mysql_query("insert into proveedores_solicitud_cotizacion(idsolicitud_cotizacion,
																				idbeneficiarios,
																				status,
																				usuario,
																				fechayhora)values(
																				'".$id."',
																				'".$bus_proveedores["idbeneficiarios"]."',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."'
																				)")or die("ingresando proveedor: ".mysql_error());
	}
	
	
	$sql_materiales= mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
	while($bus_materiales= mysql_fetch_array($sql_materiales)){
		$sql_insert = mysql_query("insert into articulos_solicitud_cotizacion(idsolicitud_cotizacion,
																				idarticulos_servicios,
																				cantidad,
																				status,
																				usuario,
																				fechayhora)values(
																				'".$id."',
																				'".$bus_materiales["idarticulos_servicios"]."',
																				'".$bus_materiales["cantidad"]."',
																				'a',
																				'".$login."',
																				'".date("Y-m-d H:i:s")."'
																				)");
	}
	
	
	if($sql){
		echo $id;
		registra_transaccion("Duplicar Solicitud de Cotizacion de Administracion (ID ANTIGUO: ".$id_solicitud." ID NUEVO: ".$id.")",$login,$fh,$pc,'solicitud_cotizacion');
	}else{
	registra_transaccion("Duplicar Solicitud de Cotizacion de Administracion ERROR (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
		echo "fallo";
	}
}


if($ejecutar == "anular"){
	$sql = mysql_query("update solicitud_cotizacion set estado = 'anulado' where idsolicitud_cotizacion = ".$id_solicitud."");
	if($sql){
		echo "exito";
		registra_transaccion("Anular Solicitud de Cotizacion de Administracion (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
	}
}

if($ejecutar == "verificarUsuario"){
	$claveValidacion = md5($claveValidacion);
	$sql = mysql_query("select * from usuarios where login = '".$login."' and clave = '".$claveValidacion."'");
	$num = mysql_num_rows($sql);
		if($num > 0){
			echo "exito";
		}else{
			echo "fallo";
		}
}

if($ejecutar == "cantidadProveedores"){
$sql = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
$num = mysql_num_rows($sql);
echo $num;
}


if($ejecutar == "registrarGanador"){

$arreglo = explode(";",$datos);



$sql = mysql_query("update solicitud_cotizacion set estado = 'otorgado' where idsolicitud_cotizacion = '".$id_solicitud."'");





foreach($arreglo as $arr){
$d = explode(",",$arr);
if($d[0] == $idbeneficiario){
	$ganador = "y";
}else{
	$ganador = "n";
}
if($d[0] != ''){

	$sql = mysql_query("update proveedores_solicitud_cotizacion set ganador = '".$ganador."', 
															tipo_procedimiento = '".$tipo."',
															justificacion = '".$justificacion."',
															formato = '".$formato."',
															articulo = '".$articulo."',
															numeral = '".$numeral."',
															descuento = '".$d[1]."',
															garantia_servicio = '".$d[2]."',
															calidad = '".$d[3]."',
															gtos_flt = '".$d[4]."',
															emp_local = '".$d[5]."',
															experiencia = '".$d[6]."',
															monto_total = '".$d[7]."',
															nro_cotizacion = '".$d[8]."'
															 where idsolicitud_cotizacion = ".$id_solicitud." 
															 and idbeneficiarios = ".$d[0]."")or die("proveedores solicitud: ".mysql_error());
	}
}


	
																	
	if($sql){
		echo "exito";
		registra_transaccion("Registrar Ganador en Solicitud de Cotizacion de Administracion (ID Solicitud: ".$id_solicitud." ID Beneficiario: ".$idbeneficiario.")",$login,$fh,$pc,'solicitud_cotizacion');
	}else{
	registra_transaccion("Registrar Ganador en Solicitud de Cotizacion de Administracion ERROR (ID Solicitud: ".$id_solicitud." ID Beneficiario: ".$idbeneficiario.")",$login,$fh,$pc,'solicitud_cotizacion');
		echo "fallo";
	}
}

//****************************************************************
if($ejecutar == "guardarPrecio"){

	//echo "ARTICULO: ".$id_articulo;
	
	
	$sql_proveedor = mysql_query("select * from proveedores_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."
																						and ganador = 'y'")or die('AQUIIII: '.mysql_error());
	$num_proveedor = mysql_num_rows($sql_proveedor);
	//echo $num_proveedor;
	$bus_proveedor = mysql_fetch_array($sql_proveedor);
	$sql_beneficiario_ordinario = mysql_query("select * from beneficiarios where idbeneficiarios = '".$bus_proveedor["idbeneficiarios"]."'")or die("beneficiarios: ".mysql_error());
	$bus_beneficiario_ordinario = mysql_fetch_array($sql_beneficiario_ordinario);
	$contribuyente_ordinario = $bus_beneficiario_ordinario["contribuyente_ordinario"];	
	
	$sql = mysql_query("select * from impuestos, articulos_servicios where articulos_servicios.idarticulos_servicios = ".$id_articulo." 
															and articulos_servicios.idimpuestos = impuestos.idimpuestos")or die("ffff".mysql_error());

	$bus = mysql_fetch_array($sql);
	//var_dump($bus);
	//echo "exento: ".$bus["exento"];
	$sql_cantidad = mysql_query("select * from articulos_solicitud_cotizacion where idarticulos_solicitud_cotizacion = ".$id_articulo_cotizacion."");
	$bus_cantidad = mysql_fetch_array($sql_cantidad);
	$total = $bus_cantidad["cantidad"] * $precio;
	if ($bus["exento"] == 0){
		if ($contribuyente_ordinario  == "si"){
			$porcentaje_impuesto = $bus["3"]/100;
			$impuesto_por_producto = $total*$porcentaje_impuesto;
			$exento = 0;
		}else{
			$exento = $total;
			$total = 0;
			$porcentaje_impuesto = 0;
			$impuesto_por_producto = 0;
		}
	}else{
		$exento = $total;
		$total = 0;
		$porcentaje_impuesto = 0;
		$impuesto_por_producto = 0;
	}
	 
	$sql2 = mysql_query("update articulos_solicitud_cotizacion set porcentaje_impuesto = '".$porcentaje_impuesto."', 
																	impuesto = '".$impuesto_por_producto."', 
																	total = '".$total."', 
																	exento = '".$exento."', 
																	precio_unitario = '".$precio."' 
																where idarticulos_solicitud_cotizacion = ".$id_articulo_cotizacion."");
	
	$sql = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
	
	while($bus = mysql_fetch_array($sql)){
		$sub_total += $bus["total"];
		$total_exento += $bus["exento"];
		$total_impuesto += $bus["impuesto"];
		$total_general += ($bus["total"] + $bus["impuesto"]) + $bus["exento"];	
	}
	
	$sql = mysql_query("update solicitud_cotizacion set sub_total = '".$sub_total."', exento = '".$total_exento."', impuesto = '".$total_impuesto."', total = '".$total_general."' where idsolicitud_cotizacion = ".$id_solicitud."");
		
		
		if($sql2){
			echo "<b>Exento:</b> ".number_format($total_exento,2,',','.')." | <b>SubTotal:</b> ".number_format($sub_total,2,',','.')." | <b>Impuestos:</b> ".number_format($total_impuesto,2,',','.')." | <b>Total Bs:</b> ".number_format($total_general,2,',','.');
		}else{
			echo "gggg".mysql_error();
		}
		registra_transaccion("Guardar Precio Solicitud de Cotizacion de Administracion (ID Solicitud: ".$id_solicitud." ID Material: ".$id_articulo.")",$login,$fh,$pc,'solicitud_cotizacion');
}























//***************************************************************
if($ejecutar == "consultarTotales"){
	$sql = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = '".$id_solicitud."'")or die("AQUII".mysql_error());
	
	while($bus = mysql_fetch_array($sql)){
		$sub_total += $bus["total"];
		$total_exento += $bus["exento"];
		$total_impuesto += $bus["impuesto"];
		$total_general += ($bus["total"] + $bus["impuesto"] + $bus["exento"]);	
	}
	
		if($sql){
			echo "<b>Exento:</b> ".number_format($total_exento,2,',','.')." | <b>SubTotal:</b> ".number_format($sub_total,2,',','.')." | <b>Impuestos:</b> ".number_format($total_impuesto,2,',','.')." | <b>Total Bs:</b> ".number_format($total_general,2,',','.');
		}else{
			echo "alla".mysql_error();
		}
}

//*******************************************************************
if($ejecutar == "finalizarSolicitud"){
	$sql = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud." and precio_unitario = 0");
	$num = mysql_num_rows($sql);
	
	if($num == 0){
		$sql = mysql_query("update solicitud_cotizacion set estado = 'finalizado' where idsolicitud_cotizacion = ".$id_solicitud."");
		if($sql){
			$sql_cantidad_items = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
			$num_cantidad_items = mysql_num_Rows($sql_cantidad_items);
			$sql_actualizar_items = mysql_query("update solicitud_cotizacion set nro_items = ".$num_cantidad_items." where idsolicitud_cotizacion = ".$id_solicitud."");
			echo "exito";
			registra_transaccion("Finalizar Solicitud de Cotizacion de Administracion (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
		}
	}else{
		$sql2 = mysql_query("select * from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud."");
		$num2 = mysql_num_rows($sql2);
		if($num2 == 1){
			echo "soloUnRegistro";	
			registra_transaccion("Finalizar Solicitud de Cotizacion de Administracion ERROR Tiene un solo articulo y esta en cero(".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
		}else if($num == $num2){
		registra_transaccion("Finalizar Solicitud de Cotizacion de Administracion ERROR Todos los Articulos estan en Cero (".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
			echo "todosEnCero";
		}else{
		registra_transaccion("Finalizar Solicitud de Cotizacion de Administracion ERROR Tiene articulos con precios Vacios(".$id_solicitud.")",$login,$fh,$pc,'solicitud_cotizacion');
			echo "preciosVacios"; 
		}
	}
	
}
//***************************************************************************
if($ejecutar == "materialesEnCero"){
	$sql2 = mysql_query("delete from articulos_solicitud_cotizacion where idsolicitud_cotizacion = ".$id_solicitud." and precio_unitario = 0");
	if($sql2){
		echo "exito";
	}else{
		"mmmm".mysql_error();
	}
}

?>