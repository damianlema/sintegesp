<?
include("../../../conf/conex.php");
Conectarse();
extract($_POST);

if($ejecutar == "cargarTiposReportes"){
 $sql_tipo_reporte = mysql_query("select * from tipos_reportes")or die(mysql_error());
?>
<select name="tipos_reportes" id="tipos_reportes">
	<option value="0">.:: Seleccion ::.</option>
	<?
	while($bus_tipo_reporte = mysql_fetch_array($sql_tipo_reporte)){
		$sql_nombres_firmas = mysql_query("select * from nombres_firmas where idmodulo = '".$idmodulo."' and idtipo_reporte = '".$bus_tipo_reporte["idtipos_reportes"]."'");
		$num_nombres_firmas = mysql_num_rows($sql_nombres_firmas);
		?>
		<option onclick="mostrarFormato('<?=$bus_tipo_reporte["idtipos_reportes"]?>', '<?=$bus_tipo_reporte["cant_firmas"]?>')" <? if($num_nombres_firmas > 0){ $seleccionado = true; echo "selected"; $cant = $bus_tipo_reporte["cant_firmas"];}?> value="<?=$bus_tipo_reporte["idtipos_reportes"]?>"><?=$bus_tipo_reporte["nombre_tipo"]?></option>
		<?
	}
	?>
</select>
<?
if($seleccionado == true){
?>
<input type="hidden" id="cant" name="cant" value='<?=$cant?>'>
<?
}
}





if($ejecutar == "mostrarFormato"){
	?>
	<table align="center" width="80%">
    	<tr>
        <?
        $m=0;
		for($i=1;$i<($cant_firmas+1);$i++){
		$sql_nombres_firmas = mysql_query("select * from nombres_firmas where idmodulo = '".$idmodulo."' and idtipo_reporte = '".$idtipo_reporte."' order by idnombres_firmas ASC LIMIT ".($i-1).",1")or die(mysql_error());
		$bus_nombres_firmas = mysql_fetch_array($sql_nombres_firmas);
		
			?>
			<td>
                <table style="border:#000000 1px solid;">
                    <tr>
                    	<td align='right' class='viewPropTitle'>Titulo:</td>
                        <td><input type="text" id="titulo<?=$i?>" name="titulo<?=$i?>" value = '<?=$bus_nombres_firmas["titulo"]?>'></td>
                    </tr>
                    <tr>
                        <td align='right' class='viewPropTitle'>Dependencia:</td>
                        <td>
                        	<select name="dependencia<?=$i?>" id="dependencia<?=$i?>" style="width:100%">
                            <option value="0">.:: Seleccione ::.</option>
                            <?
                            $sql_dependencias =  mysql_query("select * from dependencias");
							while($bus_dependencias = mysql_fetch_array($sql_dependencias)){
								?>
								<option <? if($bus_nombres_firmas["iddependencia"] == $bus_dependencias["iddependencia"]){echo "selected";}?> value="<?=$bus_dependencias["iddependencia"]?>"><?=$bus_dependencias["denominacion"]?></option>
								<?
							}
							?>
                            </select>                        </td>
                    </tr>
                    <tr>
                    	<td align='right' class='viewPropTitle'>Responsable:</td>
                    	<td>
                        	<input type="hidden" id="nombre_campo<?=$i?>" name="nombre_campo<?=$i?>" value="<?=$bus_nombres_firmas["nombre_campo"]?>">
                            <input type="hidden" id="tabla<?=$i?>" name="tabla<?=$i?>" value="<?=$bus_nombres_firmas["tabla"]?>">
                        	<select name="responsable" id="responsable">
                            <option value="0" onclick="document.getElementById('nombre_campo<?=$i?>').value='', document.getElementById('tabla<?=$i?>').value=''">.:: Seleccione ::.</option>
							<?
                                  $sql_configuracion_administracion = mysql_query("select * from configuracion_administracion")or die(mysql_error());
                                  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
                                  ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_administracion'){
                                            echo "selected";
                                            }?> 
                                            id="<?=$bus_configuracion_administracion["primero_administracion"]?>"
                                            onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_administracion', document.getElementById('tabla<?=$i?>').value='configuracion_administracion'">
                            <?=$bus_configuracion_administracion["primero_administracion"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_administracion'){
                                            echo "selected";
                                            }?>
                                            id="<?=$bus_configuracion_administracion["segundo_administracion"]?>"
                                            onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_administracion', document.getElementById('tabla<?=$i?>').value='configuracion_administracion'">
                            <?=$bus_configuracion_administracion["segundo_administracion"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_administracion'){
                                            echo "selected";
                                            }?>
                                   id="<?=$bus_configuracion_administracion["tercero_administracion"]?>"
                                    onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_administracion', document.getElementById('tabla<?=$i?>').value='configuracion_administracion'">
                            <?=$bus_configuracion_administracion["tercero_administracion"]?>
                            </option>
					<?
                  $sql_configuracion_administracion = mysql_query("select * from configuracion_compras")or die(mysql_error());
                  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
                  ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_compras'){
                                            echo "selected";
                                            }?>
                                        id="<?=$bus_configuracion_administracion["primero_compras"]?>"
                                        onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_compras', document.getElementById('tabla<?=$i?>').value='configuracion_compras'">
                            <?=$bus_configuracion_administracion["primero_compras"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_compras'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["segundo_compras"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_compras', document.getElementById('tabla<?=$i?>').value='configuracion_compras'">
                            <?=$bus_configuracion_administracion["segundo_compras"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_compras'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["tercero_compras"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_compras', document.getElementById('tabla<?=$i?>').value='configuracion_compras'">
                            <?=$bus_configuracion_administracion["tercero_compras"]?>
                            </option>
					<?
                  $sql_configuracion_administracion = mysql_query("select * from configuracion_rrhh")or die(mysql_error());
                  $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
                  ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_rrhh'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["primero_rrhh"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_rrhh', document.getElementById('tabla<?=$i?>').value='configuracion_rrhh'">
                            <?=$bus_configuracion_administracion["primero_rrhh"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_rrhh'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["segundo_rrhh"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_rrhh', document.getElementById('tabla<?=$i?>').value='configuracion_rrhh'">
                            <?=$bus_configuracion_administracion["segundo_rrhh"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_rrhh'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["tercero_rrhh"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_rrhh', document.getElementById('tabla<?=$i?>').value='configuracion_rrhh'">
                            <?=$bus_configuracion_administracion["tercero_rrhh"]?>
                            </option>
			<?
              $sql_configuracion_administracion = mysql_query("select * from configuracion_contabilidad")or die(mysql_error());
              $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
              ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_contabilidad'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["primero_contabilidad"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_contabilidad', document.getElementById('tabla<?=$i?>').value='configuracion_contabilidad'">
                            <?=$bus_configuracion_administracion["primero_contabilidad"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_contabilidad'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["segundo_contabilidad"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_contabilidad', document.getElementById('tabla<?=$i?>').value='configuracion_contabilidad'">
                            <?=$bus_configuracion_administracion["segundo_contabilidad"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_contabilidad'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["tercero_contabilidad"]?>"
                                 onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_contabilidad', document.getElementById('tabla<?=$i?>').value='configuracion_contabilidad'">
                            <?=$bus_configuracion_administracion["tercero_contabilidad"]?>
                            </option>
			<?
          $sql_configuracion_administracion = mysql_query("select * from configuracion_presupuesto")or die(mysql_error());
          $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
          ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_presupuesto'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["primero_presupuesto"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_presupuesto', document.getElementById('tabla<?=$i?>').value='configuracion_presupuesto'">
                            <?=$bus_configuracion_administracion["primero_presupuesto"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_presupuesto'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["segundo_presupuesto"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_presupuesto', document.getElementById('tabla<?=$i?>').value='configuracion_presupuesto'">
                            <?=$bus_configuracion_administracion["segundo_presupuesto"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_presupuesto'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["tercero_presupuesto"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_presupuesto', document.getElementById('tabla<?=$i?>').value='configuracion_presupuesto'">
                            <?=$bus_configuracion_administracion["tercero_presupuesto"]?>
                            </option>
			<?
          $sql_configuracion_administracion = mysql_query("select * from configuracion_tesoreria")or die(mysql_error());
          $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
          ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_tesoreria'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["primero_tesoreria"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_tesoreria', document.getElementById('tabla<?=$i?>').value='configuracion_tesoreria'">
                            <?=$bus_configuracion_administracion["primero_tesoreria"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_tesoreria'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["segundo_tesoreria"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_tesoreria', document.getElementById('tabla<?=$i?>').value='configuracion_tesoreria'">
                            <?=$bus_configuracion_administracion["segundo_tesoreria"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_tesoreria'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["tercero_tesoreria"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_tesoreria', document.getElementById('tabla<?=$i?>').value='configuracion_tesoreria'">
                            <?=$bus_configuracion_administracion["tercero_tesoreria"]?>
                            </option>
	<?
      $sql_configuracion_administracion = mysql_query("select * from configuracion_tributos")or die(mysql_error());
      $bus_configuracion_administracion = mysql_fetch_array($sql_configuracion_administracion);
      ?>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'primero_tributos'){
                                            echo "selected";
                    
                                            }?>
                                  id="<?=$bus_configuracion_administracion["primero_tributos"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='primero_tributos', document.getElementById('tabla<?=$i?>').value='configuracion_tributos'">
                            <?=$bus_configuracion_administracion["primero_tributos"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'segundo_tributos'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["segundo_tributos"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='segundo_tributos', document.getElementById('tabla<?=$i?>').value='configuracion_tributos'">
                            <?=$bus_configuracion_administracion["segundo_tributos"]?>
                            </option>
                            <option <? if($bus_nombres_firmas["nombre_campo"] == 'tercero_tributos'){
                                            echo "selected";
                                            }?>
                                  id="<?=$bus_configuracion_administracion["tercero_tributos"]?>"
                                  onclick="document.getElementById('nombre_campo<?=$i?>').value='tercero_tributos', document.getElementById('tabla<?=$i?>').value='configuracion_tributos'">
                            <?=$bus_configuracion_administracion["tercero_tributos"]?>
                            </option>
                          </select>                          </td>
                    </tr>
                    <tr>
                      <td align='right' class='viewPropTitle'>Posicion:</td>
                      <td><select id="posicion<?=$i?>" name="posicion<?=$i?>">
                        <option value="0">.:: Seleccione ::.</option>
                        <?
                            for($k=1;$k<($cant_firmas+1);$k++){
								?>
                        <option <? if($bus_nombres_firmas["posicion"] == $k){echo "selected";}?> value="<?=$k?>">
                          <?=$k?>
                        </option>
                        <?
							}
							?>
                      </select></td>
                    </tr>
                    <tr>
                    	<td align='right' class='viewPropTitle'>Tipo Cuadro:</td>
                    	<td><label>
                    	  <select name="campo_completo<?=$i?>" id="campo_completo<?=$i?>">
                    	    <option <? if($bus_nombres_firmas["campo_completo"] == "0"){echo "selected";}?> value="0">Completo</option>
                    	    <option <? if($bus_nombres_firmas["campo_completo"] == "1"){echo "selected";}?> value="1">Arriba</option>
                    	    <option <? if($bus_nombres_firmas["campo_completo"] == "2"){echo "selected";}?> value="2">Abajo</option>
                  	      </select>
                    	</label></td>
                    </tr>
                </table>
          </td>
			<?
			if($m == 2){
				?>
				<tr>
                </tr>
				<?
				$m=0;
			}else{
			$m++;
			}
		}
		?>
        </tr>
    </table>
	<?
}







if($ejecutar == "procesarConfiguracion"){
		$sql_configuracion_reporte = mysql_query("insert configuracion_reportes 
														set idtipo_reporte = '".$idtipo_reporte."',
															idtipo_documento = '".$idmodulo."'")or die("COMPRAS: ".mysql_error());


		$sql_eliminar = mysql_query("delete from nombres_firmas where idmodulo = '".$idmodulo."'");
		
		if($titulo1 != ""){
			$sql_nombres_fimas = mysql_query("insert into nombres_firmas (idtipo_reporte, 
																		idmodulo, 
																		iddependencia, 
																		titulo, 
																		nombre_campo, 
																		tabla, 
																		posicion,
																		campo_completo)VALUES('".$idtipo_reporte."',
																						'".$idmodulo."',
																						'".$dependencia1."',
																						'".$titulo1."',
																						'".$nombre_campo1."',
																						'".$tabla1."',
																						'".$posicion1."',
																						'".$campo_completo1."')")or die("TITULO 1: ".mysql_error());
		}
		
		if($titulo2 != ""){
			$sql_nombres_fimas = mysql_query("insert into nombres_firmas (idtipo_reporte, 
																		idmodulo, 
																		iddependencia, 
																		titulo, 
																		nombre_campo, 
																		tabla, 
																		posicion,
																		campo_completo)VALUES('".$idtipo_reporte."',
																						'".$idmodulo."',
																						'".$dependencia2."',
																						'".$titulo2."',
																						'".$nombre_campo2."',
																						'".$tabla2."',
																						'".$posicion2."',
																						'".$campo_completo2."')")or die("TITULO 2: ".mysql_error());
		}
		
		
		if($titulo3 != ""){
			$sql_nombres_fimas = mysql_query("insert into nombres_firmas (idtipo_reporte, 
																		idmodulo, 
																		iddependencia, 
																		titulo, 
																		nombre_campo, 
																		tabla, 
																		posicion,
																		campo_completo)VALUES('".$idtipo_reporte."',
																						'".$idmodulo."',
																						'".$dependencia3."',
																						'".$titulo3."',
																						'".$nombre_campo3."',
																						'".$tabla3."',
																						'".$posicion3."',
																						'".$campo_completo3."')")or die("TITULO 3: ".mysql_error());
		}
		
		
		if($titulo4 != ""){
			$sql_nombres_fimas = mysql_query("insert into nombres_firmas (idtipo_reporte, 
																		idmodulo, 
																		iddependencia, 
																		titulo, 
																		nombre_campo, 
																		tabla, 
																		posicion,
																		campo_completo)VALUES('".$idtipo_reporte."',
																						'".$idmodulo."',
																						'".$dependencia4."',
																						'".$titulo4."',
																						'".$nombre_campo4."',
																						'".$tabla4."',
																						'".$posicion4."',
																						'".$campo_completo4."')")or die("TITULO 4: ".mysql_error());
		}
		
		
		if($titulo5 != ""){
			$sql_nombres_fimas = mysql_query("insert into nombres_firmas (idtipo_reporte, 
																		idmodulo, 
																		iddependencia, 
																		titulo, 
																		nombre_campo, 
																		tabla, 
																		posicion,
																		campo_completo)VALUES('".$idtipo_reporte."',
																						'".$idmodulo."',
																						'".$dependencia5."',
																						'".$titulo5."',
																						'".$nombre_campo5."',
																						'".$tabla5."',
																						'".$posicion5."',
																						'".$campo_completo5."')")or die("TITULO 5: ".mysql_error());
		}
		
		
		if($titulo6 != ""){
			$sql_nombres_fimas = mysql_query("insert into nombres_firmas (idtipo_reporte, 
																		idmodulo, 
																		iddependencia, 
																		titulo, 
																		nombre_campo, 
																		tabla, 
																		posicion,
																		campo_completo)VALUES('".$idtipo_reporte."',
																						'".$idmodulo."',
																						'".$dependencia6."',
																						'".$titulo6."',
																						'".$nombre_campo6."',
																						'".$tabla6."',
																						'".$posicion6."',
																						'".$campo_completo6."')")or die("TITULO 6: ".mysql_error());
		}
																						
	
	
}
?>