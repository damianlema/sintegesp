<?
include("conf/conex.php");
Conectarse();
/*
$crear_tabla = mysql_query("CREATE TABLE `movimientos_personal2` (
  `idmovimientos_personal` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `fecha_movimiento` date NOT NULL default '0000-00-00',
  `idtipo_movimiento` int(10) unsigned NOT NULL default '0',
  `justificacion` text NOT NULL,
  `fecha_ingreso` date NOT NULL default '0000-00-00',
  `idcargo` varchar(45) NOT NULL default '0',
  `idubicacion_funcional` int(10) unsigned NOT NULL default '0',
  `fecha_egreso` date NOT NULL default '0000-00-00',
  `causal` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `centro_costo` int(10) unsigned NOT NULL default '0',
  `fecha_reingreso` date NOT NULL default '0000-00-00',
  `desde` date NOT NULL default '0000-00-00',
  `hasta` date NOT NULL default '0000-00-00',
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `idconstante` int(10) unsigned NOT NULL default '0',
  `valor` decimal(16,2) NOT NULL default '0.00',
  `nro_ficha` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idmovimientos_personal`),
  KEY `idtrabajador` (`idtrabajador`),
  KEY `idtipo_movimiento` (`idtipo_movimiento`),
  KEY `idubicacion_funcional` (`idubicacion_funcional`),
  KEY `idtipo_nomina` (`idtipo_nomina`),
  KEY `idconstante` (`idconstante`),
  KEY `centro_costo` (`centro_costo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");


$i=1;

$sql_constante = mysql_query("select * from constantes_nomina where idconstantes_nomina = 1")or die(mysql_error());
$bus_constante = mysql_fetch_array($sql_constante)or die(mysql_error());

$actualizar = mysql_query("update movimientos_personal set idconstante = 1
											WHERE justificacion like '%SALARIO MINIMO%'")or die(mysql_error());


$sql_trabajador = mysql_query("SELECT * FROM trabajador");

while($bus_trabajador = mysql_fetch_array($sql_trabajador)){
	echo "<br/>".$i." - id trabajador ".$bus_trabajador["idtrabajador"]."<br/>";
	$i=$i+1;
	$sql_consulta = mysql_query("SELECT *
									FROM movimientos_personal
									WHERE fecha_movimiento < '2015-05-01'
									AND idconstante = 1
									AND idtrabajador = '".$bus_trabajador["idtrabajador"]."'")or die(" err ".mysql_error());
	if(mysql_num_rows($sql_consulta)>0){
		$bus_consulta = mysql_fetch_array($sql_consulta)or die(" otro err ".mysql_error());
		echo " uno ".$bus_consulta["idmovimientos_personal"]." ".$bus_consulta["valor"]."<br/>";

		//echo mysql_num_rows($sql_consulta)."<br/>";
		
		echo " valor ".$bus_consulta["valor"]."<br/>";
		if ($bus_consulta["valor"] == 0.00){
			
			$sql_concepto = mysql_query("select conceptos_nomina.idconceptos_nomina
												from 	relacion_concepto_trabajador,
														conceptos_nomina,
														relacion_formula_conceptos_nomina
												where relacion_concepto_trabajador.idtrabajador='".$bus_trabajador["idtrabajador"]."'
												and relacion_concepto_trabajador.tabla = 'conceptos_nomina'
												and conceptos_nomina.idconceptos_nomina = relacion_concepto_trabajador.idconcepto
												and conceptos_nomina.tipo_concepto =2
												and relacion_formula_conceptos_nomina.idconcepto_nomina = conceptos_nomina.idconceptos_nomina
												and relacion_formula_conceptos_nomina.valor_oculto = 'CN_1'
												and relacion_formula_conceptos_nomina.valor = 'REMUNERACION BASICA'")or die(mysql_error());

			$bus_concepto = mysql_fetch_array($sql_concepto);
			echo " id concepto2 ".$bus_concepto["idconceptos_nomina"]."<br/>";

			$sql_generar_nomina = mysql_query("select MIN(idrelacion_generar_nomina) from relacion_generar_nomina 
												WHERE idtrabajador = '".$bus_trabajador["idtrabajador"]."'
												and idconcepto = '".$bus_concepto["idconceptos_nomina"]."'
												and tabla = 'conceptos_nomina'
												")or die(" aqui ".mysql_error());
			
			$bus_generar_nomina = mysql_fetch_array($sql_generar_nomina)or die(mysql_error());

			echo " idrelacion ".$bus_generar_nomina["MIN(idrelacion_generar_nomina)"]."<br/>";
			echo " existe ".mysql_num_rows($sql_generar_nomina)."<br/>";

			if(mysql_num_rows($sql_generar_nomina) > 0){
				
				$sql_valor_nomina = mysql_query("select * from relacion_generar_nomina 
								where idrelacion_generar_nomina = '".$bus_generar_nomina["MIN(idrelacion_generar_nomina)"]."'");
				$bus_valor_nomina = mysql_fetch_array($sql_valor_nomina);
				$valor = $bus_valor_nomina["total"]*2;
				if ($valor > 0){
					echo "id trabajador ".$bus_consulta["idtrabajador"]." sueldo ".$valor."<br/>";

					$actualizar = mysql_query("update movimientos_personal set fecha_movimiento = '2015-03-01',
																			valor = $valor 
												WHERE idmovimientos_personal = '".$bus_consulta["idmovimientos_personal"]."'")
											or die(mysql_error());
				}
			}
		}
		
	}else{
		echo " dos <br/>";
		$sql_consulta2 = mysql_query("SELECT *
									FROM movimientos_personal
									WHERE '".$bus_trabajador["idtrabajador"]."'")or die(mysql_error());
		$bus_movimiento = mysql_fetch_array($sql_consulta2);
		
		$sql_concepto = mysql_query("select conceptos_nomina.idconceptos_nomina
												from 	relacion_concepto_trabajador,
														conceptos_nomina,
														relacion_formula_conceptos_nomina
												where relacion_concepto_trabajador.idtrabajador='".$bus_trabajador["idtrabajador"]."'
												and relacion_concepto_trabajador.tabla = 'conceptos_nomina'
												and conceptos_nomina.idconceptos_nomina = relacion_concepto_trabajador.idconcepto
												and conceptos_nomina.tipo_concepto =2
												and relacion_formula_conceptos_nomina.idconcepto_nomina = conceptos_nomina.idconceptos_nomina
												and relacion_formula_conceptos_nomina.valor_oculto = 'CN_1'
												and relacion_formula_conceptos_nomina.valor = 'REMUNERACION BASICA'");

		$bus_concepto = mysql_fetch_array($sql_concepto);
		echo " id concepto2 ".$bus_concepto["idconceptos_nomina"]."<br/>";

		$sql_generar_nomina = mysql_query("select MIN(idrelacion_generar_nomina) from relacion_generar_nomina 
											WHERE idtrabajador = '".$bus_trabajador["idtrabajador"]."'
											and idconcepto = '".$bus_concepto["idconceptos_nomina"]."'
											and tabla = 'conceptos_nomina'
											")or die(" aqui ".mysql_error());
		
		$bus_generar_nomina = mysql_fetch_array($sql_generar_nomina)or die(mysql_error());

		echo " idrelacion ".$bus_generar_nomina["MIN(idrelacion_generar_nomina)"]."<br/>";
		echo " existe ".mysql_num_rows($sql_generar_nomina)."<br/>";

		if(mysql_num_rows($sql_generar_nomina) > 0){
			
			$sql_valor_nomina = mysql_query("select * from relacion_generar_nomina 
							where idrelacion_generar_nomina = '".$bus_generar_nomina["MIN(idrelacion_generar_nomina)"]."'");
			$bus_valor_nomina = mysql_fetch_array($sql_valor_nomina);
			$valor = $bus_valor_nomina["total"]*2;
			$descripcion = "SE ASOCIO LA CONSTANTE DE VALOR FIJO (".$bus_constante["descripcion"]." AL VALOR : ".$valor.")";
			//echo $descripcion."<br/>";
			if ($valor > 0){
				
				$insertar_movimiento = mysql_query("insert into movimientos_personal(	  			
														idtrabajador,
														  fecha_movimiento,
														  idtipo_movimiento,
														  justificacion,
														  status,
														  fecha_ingreso,
														  idcargo,
														  idubicacion_funcional,
														  centro_costo,
														  idconstante,
														  valor
													)VALUES(
															'".$bus_trabajador["idtrabajador"]."',
															'2015-03-01',
															'1000000',
															'".$descripcion."',
															'a',
															'".$bus_trabajador["fecha_ingreso"]."',
															'".$bus_trabajador["idcargo"]."',
															'".$bus_trabajador["idunidad_funcional"]."',
															'".$bus_trabajador["centro_costo"]."',
															'1',
															'".$valor."'
															)")or die(" error insertando ".mysql_error());
			}
		}
	}
}

$sql_cambiar = mysql_query("select * from movimientos_personal order by fecha_movimiento")or die(mysql_error());

while($bus_cambiar = mysql_fetch_array($sql_cambiar)){
	$insertar_movimiento = mysql_query("insert into movimientos_personal2(	  			
											idtrabajador,
											  fecha_movimiento,
											  idtipo_movimiento,
											  justificacion,
											  fecha_ingreso,
											  idcargo,
											  idubicacion_funcional,
											  fecha_egreso,
											  causal,
											  usuario,
											  status,
											  fechayhora,
											  centro_costo,
											  fecha_reingreso,
											  desde,
											  hasta,
											  idtipo_nomina,
											  idconstante,
											  valor,
											  nro_ficha
										)VALUES(
												'".$bus_cambiar["idtrabajador"]."',
												'".$bus_cambiar["fecha_movimiento"]."',
												'".$bus_cambiar["idtipo_movimiento"]."',
												'".$bus_cambiar["justificacion"]."',
												'".$bus_cambiar["fecha_ingreso"]."',
												'".$bus_cambiar["idcargo"]."',
												'".$bus_cambiar["idubicacion_funcional"]."',
												'".$bus_cambiar["fecha_egreso"]."',
												'".$bus_cambiar["causal"]."',
												'".$bus_cambiar["usuario"]."',
												'".$bus_cambiar["status"]."',
												'".$bus_cambiar["fechayhora"]."',
												'".$bus_cambiar["centro_costo"]."',
												'".$bus_cambiar["fecha_reingreso"]."',
												'".$bus_cambiar["desde"]."',
												'".$bus_cambiar["hasta"]."',
												'".$bus_cambiar["idtipo_nomina"]."',
												'".$bus_cambiar["idconstante"]."',
												'".$bus_cambiar["valor"]."',
												'".$bus_cambiar["nro_ficha"]."'
												)")or die(" error insertando tabla 2 ".mysql_error());
}

*/
$sql_actualizar = mysql_query("select * from movimientos_personal 	
												WHERE idconstante = 1
												and valor > 0
												and fecha_movimiento < '2015-05-01'");
while($bus_actualizar = mysql_fetch_array($sql_actualizar)){
	$justificacion = 'SE INGRESO LA CONSTANTE DE VALOR FIJO (REMUNERACION BASICA AL VALOR: '.$bus_actualizar["valor"].')';
	echo $bus_actualizar["idtrabajador"]." ".$justificacion."<br/>";
	if($bus_actualizar["valor"]<1000) echo " ESTE ".$bus_actualizar["idtrabajador"]."<BR/>";
	$actualizar = mysql_query("update movimientos_personal set 
									justificacion = '".$justificacion."'
																			
												WHERE idmovimientos_personal = '".$bus_actualizar["idmovimientos_personal"]."'")
											or die(mysql_error());
}


$sql_eliminar = mysql_query("delete from movimientos_personal 
									where idconstante = 1
									and valor = 0
									");
/*

while($bus_consulta = mysql_fetch_array($sql_consulta)){
		echo $i." - ".$bus_consulta["valor"]." id trabajador ".$bus_consulta["idtrabajador"]."<br/>";
		if ($bus_consulta["valor"] == 0.00){

			$sql_concepto = mysql_query("select conceptos_nomina.idconceptos_nomina
												from 	relacion_concepto_trabajador,
														conceptos_nomina,
														relacion_formula_conceptos_nomina
												where relacion_concepto_trabajador.idtrabajador='".$bus_consulta["idtrabajador"]."'
												and relacion_concepto_trabajador.tabla = 'conceptos_nomina'
												and conceptos_nomina.idconceptos_nomina = relacion_concepto_trabajador.idconcepto
												and relacion_formula_conceptos_nomina.idconcepto_nomina = conceptos_nomina.idconceptos_nomina
												and relacion_formula_conceptos_nomina.valor_oculto = 'CN_1'
												and relacion_formula_conceptos_nomina.valor = 'REMUNERACION BASICA'");

			$bus_concepto = mysql_fetch_array($sql_concepto);
			echo " id concepto ".$bus_concepto["idconceptos_nomina"]."<br/>";

			$sql_generar_nomina = mysql_query("select MIN(idrelacion_generar_nomina) from relacion_generar_nomina 
												WHERE idtrabajador = '".$bus_consulta["idtrabajador"]."'
												and idconcepto = '".$bus_concepto["idconceptos_nomina"]."'
												and tabla = 'conceptos_nomina'
												")or die(" aqui ".mysql_error());
			
			$bus_generar_nomina = mysql_fetch_array($sql_generar_nomina)or die(mysql_error());

			echo " idrelacion ".$bus_generar_nomina["MIN(idrelacion_generar_nomina)"]."<br/>";
			echo " existe ".mysql_num_rows($sql_generar_nomina)."<br/>";

			if(mysql_num_rows($sql_generar_nomina) > 0){
				
				$sql_valor_nomina = mysql_query("select * from relacion_generar_nomina 
								where idrelacion_generar_nomina = '".$bus_generar_nomina["MIN(idrelacion_generar_nomina)"]."'");
				$bus_valor_nomina = mysql_fetch_array($sql_valor_nomina);
				$valor = $bus_valor_nomina["total"]*2;
				if ($valor > 0){
					echo "id trabajador ".$bus_consulta["idtrabajador"]." sueldo ".$valor."<br/>";

					$actualizar = mysql_query("update movimientos_personal set fecha_movimiento = '2015-03-01',
																			valor = $valor 
												WHERE idmovimientos_personal = '".$bus_consulta["idmovimientos_personal"]."'")
											or die(mysql_error());
				}
			}
		}
		$i=$i+1;
	} 

*/