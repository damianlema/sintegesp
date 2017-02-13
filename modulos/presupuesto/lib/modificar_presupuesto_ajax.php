<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");
Conectarse();
extract($_POST);


if($ejecutar == "buscar_presupuesto"){ ?>
	
        <table class="Browse" align=center cellpadding="0" cellspacing="0" width="95%">
            <thead>
                <tr>
                  <td width="1%" align="center" class="Browse">A&ntilde;o</td>
                  <td width="1%" align="center" class="Browse">Tipo</td>
                  <td width="7%" align="center" class="Browse">Fuente Financiamiento</td>
                  <td width="7%" align="center" class="Browse">Categoria Programatica</td>
                  <td width="12%" align="center" class="Browse">Partida</td>
                  <td width="45%" align="center" class="Browse">Denominaci&oacute;n</td>
                  <td width="9%" align="center" class="Browse">Monto Actual</td>
                  <td width="9%" align="center" class="Browse">Disponible</td>
                  <td width="9%" align="center" class="Browse">Ajustar</td>
                  <td width="3%" colspan="2" align="center" class="Browse">Acci&oacute;n</td>
              </tr>
            </thead>
            <?php  //  llena la grilla con los registros de la tabla de programas 
           
		   $sql="select tipo_presupuesto.denominacion as denotipo_presupuesto, 
						clasificador_presupuestario.codigo_cuenta as codigopartida,
						clasificador_presupuestario.denominacion as denopartida,
						ordinal.codigo as codigoordinal,
						ordinal.denominacion as denoordinal,
						categoria_programatica.codigo as codigocategoria,
						fuente_financiamiento.denominacion as denofuente_financiamiento,
						maestro_presupuesto.monto_actual as monto_actual,
						maestro_presupuesto.anio as anio_maestro,
						maestro_presupuesto.idRegistro as idRegistro_maestro,
						maestro_presupuesto.monto_original as monto_original,
						maestro_presupuesto.total_compromisos,
						maestro_presupuesto.total_disminucion,
maestro_presupuesto.monto_actual-maestro_presupuesto.total_compromisos-maestro_presupuesto.pre_compromiso-maestro_presupuesto.reservado_disminuir  disponible
							from 
								maestro_presupuesto, 
								tipo_presupuesto, 
								clasificador_presupuestario, 
								categoria_programatica, 
								fuente_financiamiento, 
								ordinal
							where 
								maestro_presupuesto.status='a'
								and maestro_presupuesto.idtipo_presupuesto = tipo_presupuesto.idtipo_presupuesto
								and maestro_presupuesto.idclasificador_presupuestario = clasificador_presupuestario.idclasificador_presupuestario
								and maestro_presupuesto.idordinal=ordinal.idordinal
								and maestro_presupuesto.idfuente_financiamiento=fuente_financiamiento.idfuente_financiamiento
								and maestro_presupuesto.idcategoria_programatica=categoria_programatica.idcategoria_programatica ";
											
	
			
			if ($anio<>""){ 
				$sql=$sql." and maestro_presupuesto.anio='".$anio."'";
			}
		
			if ($tipo_presupuesto<>""){
				$sql=$sql." and maestro_presupuesto.idtipo_presupuesto = '".$tipo_presupuesto."'";
			}
		
			if ($fuente_financiamiento<>""){
				$sql=$sql." and maestro_presupuesto.idfuente_financiamiento = '".$fuente_financiamiento."'";
			}
		
			if ($categoria_programatica<>""){
				$sql=$sql." and maestro_presupuesto.idcategoria_programatica = '".$categoria_programatica."'";
			}
			
			if ($textoabuscar<>""){
				$sql=$sql." and (clasificador_presupuestario.codigo_cuenta like '%".$textoabuscar."%' 
								 or clasificador_presupuestario.denominacion like '%".$textoabuscar."%')";
			}
		
			$sql .= " order by maestro_presupuesto.anio, tipo_presupuesto.denominacion, fuente_financiamiento.denominacion, 
						categoria_programatica.codigo, clasificador_presupuestario.codigo_cuenta, ordinal.codigo";
			
		    $registros_grilla = mysql_query($sql)or die(mysql_error());
		   
            while($llenar_grilla= mysql_fetch_array($registros_grilla)){ 
                
                $sql_es_ordinal= mysql_query("select * FROM 
                                                maestro_presupuesto mp,
                                                ordinal o
                                                    WHERE
                                                o.idordinal = mp.idordinal
                                                and o.codigo = '0000'
                                                and mp.idRegistro = '".$llenar_grilla["idRegistro_maestro"]."'")or die(mysql_error());
                $num_es_ordinal = mysql_num_rows($sql_es_ordinal);
                
                if($num_es_ordinal > 0){
                    $es_ordinal = 'no';
                }else{
                    $es_ordinal = 'si';
                }
                //echo "ID: ".$llenar_grilla["idRegistro_maestro"]." ORDINAL: ".$es_ordinal."<br>";
                // VALIDO SI ES SUB ESPECIFICA
                $sql_es_sub = mysql_query("SELECT * FROM
                                                maestro_presupuesto mp,
                                                clasificador_presupuestario cp
                                                    WHERE
                                                cp.idclasificador_presupuestario = mp.idclasificador_presupuestario
                                                and cp.sub_especifica = '00'
                                                and mp.idRegistro = '".$llenar_grilla["idRegistro_maestro"]."'")or die(mysql_error());
                $num_es_sub = mysql_num_rows($sql_es_sub);
                
                if($num_es_sub > 0){
                    $es_sub = 'no';
                }else{
                    $es_sub = 'si';
                }
                //echo "ID: ".$llenar_grilla["idRegistro_maestro"]." SUB: ".$es_sub."<br>";
                
                
                $sql_ordinal = mysql_query("select * from ordinal where codigo = '0000'");
                $bus_ordinal = mysql_fetch_array($sql_ordinal);
                // SI ES ESPECIFICA
                if($es_sub == 'no' and $es_ordinal == 'no'){
                    //echo "AQUI";
                    $sql_maestro = mysql_query("SELECT 
                                cp.partida,
                                cp.generica,
                                cp.especifica,
                                cp.sub_especifica,
                                mp.idRegistro,
                                (mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
                                mp.monto_actual,
                                mp.monto_original,
                                mp.idcategoria_programatica,
                                mp.idtipo_presupuesto,
                                mp.idfuente_financiamiento,
                                mp.idclasificador_presupuestario
                                    FROM 
                                maestro_presupuesto mp,
                                clasificador_presupuestario cp	
                                WHERE
                                    mp.idRegistro = '".$llenar_grilla["idRegistro_maestro"]."'
                                    and cp.idclasificador_presupuestario = mp.idclasificador_presupuestario")or die(mysql_error());
                    $bus_maestro = mysql_fetch_array($sql_maestro);
                    
                    $sql_clasificador_sub = mysql_query("SELECT * FROM
                                                            clasificador_presupuestario
                                                                WHERE
                                                            partida = '".$bus_maestro["partida"]."'
                                                            and generica = '".$bus_maestro["generica"]."'
                                                            and especifica = '".$bus_maestro["especifica"]."'
                                                            and sub_especifica != '00'");
                    $num_clasificador_sub = mysql_num_rows($sql_clasificador_sub);
                    
                    if($num_clasificador_sub > 0){
                        while($bus_clasificador_sub = mysql_fetch_array($sql_clasificador_sub)){
                            $sql_suma = mysql_query("SELECT 
                                        monto_actual,
                                        monto_original
                                            FROM 
                                        maestro_presupuesto mp
                                            WHERE
                                        idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'
                                        and idtipo_presupuesto = '".$bus_maestro["idtipo_presupuesto"]."'
                                        and idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'
                                        and idclasificador_presupuestario = '".$bus_clasificador_sub["idclasificador_presupuestario"]."'");
                            $bus_suma = mysql_fetch_array($sql_suma);
                            $disponible_especifica += $bus_suma["monto_original"];
                        }
                        //echo "AQUI ".$disponible_especifica;
                    }else{
                    //echo "aqui";
                        $sql_maestro = mysql_query("SELECT 
                                (mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
                                mp.idcategoria_programatica,
                                mp.idtipo_presupuesto,
                                mp.idfuente_financiamiento,
                                mp.idclasificador_presupuestario,
                                mp.monto_actual
                                    FROM maestro_presupuesto mp
                                WHERE
                                    idRegistro = '".$llenar_grilla["idRegistro_maestro"]."'");
                        $bus_maestro = mysql_fetch_array($sql_maestro);
                    
                        $sql_ordinales = mysql_query("SELECT * 
                                    FROM maestro_presupuesto
                                    WHERE
                                    idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'
                                    and idtipo_presupuesto = '".$bus_maestro["idtipo_presupuesto"]."'
                                    and idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'
                                    and idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'
                                    and idordinal != '".$bus_ordinal["idordinal"]."'");
                        while($bus_ordinales = mysql_fetch_array($sql_ordinales)){
                            $sql_suma = mysql_query("SELECT 
                                (monto_actual - pre_compromiso - total_compromisos - reservado_disminuir) as disponible,
                                monto_actual,
                                monto_original
                                    FROM maestro_presupuesto
                                WHERE
                                    idcategoria_programatica = '".$bus_ordinales["idcategoria_programatica"]."'
                                    and idtipo_presupuesto = '".$bus_ordinales["idtipo_presupuesto"]."'
                                    and idfuente_financiamiento = '".$bus_ordinales["idfuente_financiamiento"]."'
                                    and idclasificador_presupuestario = '".$bus_ordinales["idclasificador_presupuestario"]."'
                                    and idordinal = '".$bus_ordinales["idordinal"]."'");
                            $bus_suma = mysql_fetch_array($sql_suma);
                            
                            $disponible_especifica += $bus_suma["monto_original"];
                        }

                    }
                    
                    //if($llenar_grilla["idRegistro_maestro"]==1215){
                        //echo $disponible_especifica."<br>";
                    //}
                    //echo $disponible_especifica;
                    if(($bus_maestro["monto_actual"] - $disponible_especifica) == 0 and $disponible_especifica =! 0){
                        $mostrar_partida = 'no';
                    }else{
                        $mostrar_partida = 'si';
                        $disponible_mostrar = ($bus_maestro["disponible"] - $disponible_especifica);
                    }
                    
                    
                }
                $disponible_especifica = 0;
                
                
                
                // SI ES SUB ESPECIFICA
                if($es_sub == "si" and $es_ordinal == 'no'){
                    $sql_maestro = mysql_query("SELECT 
                                (mp.monto_actual - mp.pre_compromiso - mp.total_compromisos - mp.reservado_disminuir) as disponible,
                                mp.idcategoria_programatica,
                                mp.idtipo_presupuesto,
                                mp.idfuente_financiamiento,
                                mp.idclasificador_presupuestario,
                                mp.monto_actual,
                                mp.monto_original
                                    FROM maestro_presupuesto mp
                                WHERE
                                    idRegistro = '".$llenar_grilla["idRegistro_maestro"]."'");
                    $bus_maestro = mysql_fetch_array($sql_maestro);
                    
                    $sql_ordinales = mysql_query("SELECT * 
                                    FROM maestro_presupuesto
                                    WHERE
                                    idcategoria_programatica = '".$bus_maestro["idcategoria_programatica"]."'
                                    and idtipo_presupuesto = '".$bus_maestro["idtipo_presupuesto"]."'
                                    and idfuente_financiamiento = '".$bus_maestro["idfuente_financiamiento"]."'
                                    and idclasificador_presupuestario = '".$bus_maestro["idclasificador_presupuestario"]."'
                                    and idordinal != '".$bus_ordinal["idordinal"]."'");
                    while($bus_ordinales = mysql_fetch_array($sql_ordinales)){
                        $sql_suma = mysql_query("SELECT 
                            (monto_actual - pre_compromiso - total_compromisos - reservado_disminuir) as disponible,
                            monto_actual,
                            monto_original
                                FROM maestro_presupuesto
                            WHERE
                                idcategoria_programatica = '".$bus_ordinales["idcategoria_programatica"]."'
                                and idtipo_presupuesto = '".$bus_ordinales["idtipo_presupuesto"]."'
                                and idfuente_financiamiento = '".$bus_ordinales["idfuente_financiamiento"]."'
                                and idclasificador_presupuestario = '".$bus_ordinales["idclasificador_presupuestario"]."'
                                and idordinal = '".$bus_ordinales["idordinal"]."'");
                        $bus_suma = mysql_fetch_array($sql_suma);
                        $disponible_especifica += $bus_suma["monto_original"];
                    }
                    if(($bus_maestro["disponible"] - $disponible_especifica) == 0 and $disponible_especifica =! 0){
                        $mostrar_partida = 'no';
                    }else{
                        $mostrar_partida = 'si';
                        $disponible_mostrar = ($bus_maestro["disponible"] - $disponible_especifica);
                    }
                }
                $disponible_especifica=0;
                
                
                // SI ES ORDINAL
                
                if($es_ordinal == 'si'){
                    //echo "AQUI<br>";
                    $mostrar_partida = 'si';
                    $disponible_mostrar = $llenar_grilla["disponible"];
                }
                
                
                
                if($llenar_grilla["monto_original"] == 0){
                    $mostrar_partida = 'si';
                }
                
                
                if($llenar_grilla["total_compromisos"] != 0){
                    $mostrar_partida = 'si';
                }
                
                if($llenar_grilla["total_disminucion"] != 0){
                    $mostrar_partida = 'si';
                }
                
                
                //echo "MOSTRAR PARTIDA: ".$mostrar_partida."<br>";
                // VALIDAR DISPONIBILIDADES **************************************************************************************
                
                
                
                
                // VALIDAR DISPONIBILIDADES **************************************************************************************
                
                if($mostrar_partida == 'si'){
					$cp=$llenar_grilla["idRegistro_maestro"];
					//echo $cp;
					?>
					<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
					<?php
                    echo "<td align='center' class='Browse' width='1%'>".$llenar_grilla["anio_maestro"]."</td>";
                    echo "<td align='center' class='Browse' width='1%'>".$llenar_grilla["denotipo_presupuesto"]."</td>";
                    echo "<td align='left' class='Browse'width='7%'>".$llenar_grilla["denofuente_financiamiento"]."</td>";
                    echo "<td align='center' class='Browse' width='7%'>".$llenar_grilla["codigocategoria"]."</td>";?>
                    <td align='left' class='Browse' width='10%'><?=$llenar_grilla["codigopartida"]." ".$llenar_grilla["codigoordinal"]?> </td>
					
					
                    <td align='left' class='Browse' width='40%'><? if ($llenar_grilla["codigoordinal"]<>'0000'){ echo $llenar_grilla["denoordinal"];} else {echo $llenar_grilla["denopartida"];} ?>  
                    </td>
                    <? echo "<td align='right' class='Browse' width='9%'>".number_format($llenar_grilla["monto_actual"],2,",",".")."</td>";
                    echo "<td align='right' class='Browse' width='9%'>".number_format($disponible_mostrar,2,",",".")."</td>";?>
                    
                    <td align='right' class='Browse' width='12%'>
                    
                    <input align="right" style="text-align:right" name="monto_presupuesto_original<?=$llenar_grilla["idRegistro_maestro"]?>" 
            												type="text" id="monto_presupuesto_original<?=$llenar_grilla["idRegistro_maestro"]?>" 
                                                            size="20" onclick="this.select()"
                                                            value="<?=number_format($llenar_grilla["monto_original"],2,',','.')?>">
                    
                    </td>
                    
                    <td align='center' class='Browse' width='2%'> <button name='poner_ppto' type='button' style='background-color:#e7dfce;border-style:none;cursor:pointer;' onclick="actualizar_presupuesto('<?=$cp?>')"><img src='imagenes/refrescar.png'>
                    </td>
                    </tr>
                    <? }
                
                $mostrar_partida ='';
                $disponible_mostrar=0;
                }
            ?>
        </table>
<?
}

if($ejecutar == "actualizar_presupuesto"){
	
	$sql_actualiza_rectificacion = mysql_query("update maestro_presupuesto set monto_original = '".$monto."' 
																				where idRegistro = '".$idRegistro_maestro."'");

	echo number_format($monto,2,",",".");

}


?>