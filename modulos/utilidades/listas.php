<?php 
	header("Content-type: text/xml");
	// se realiza la conecxion a la base de datos
	include("../../conf/conex.php");
	conectarse();
	// se hace la seleccion de la base de datos con la que se va a trabajar
    $sql = mysql_query("select * from modulo where mostrar = 'si' order by id_modulo");
	//$html="<tree id='0'><item text='Modulos' id='999999' open='1' call='1' select='1'>";
	$html="<tree id='0'><item text='SINTEGESP' id='999999' open='1' call='1' select='1'>";
	while($bus = mysql_fetch_array($sql)){
		
		$html.="<item text='".$bus["nombre_modulo"]."' id='m_".$bus["id_modulo"]."'>";
			
			$sql2 = mysql_query("select * from accion where id_modulo = ".$bus["id_modulo"]." order by nombre_accion");
			while($bus2= mysql_fetch_array($sql2)){
				
				$html.="<item text='".$bus2["nombre_accion"]."' id='".$bus2["id_accion"]."'>";
				
				$sql3 = mysql_query("select * from accion where accion_padre = ".$bus2["id_accion"]."");
					
					while($bus3= mysql_fetch_array($sql3)){
						
						$html.="<item text='".$bus3["nombre_accion"]."' id='".$bus3["id_accion"]."'>";
							$sql4 = mysql_query("select * from accion where accion_padre = ".$bus3["id_accion"]."");
								
								while($bus4= mysql_fetch_array($sql4)){
									
									$html.="<item text='".$bus4["nombre_accion"]."' id='".$bus4["id_accion"]."'>";
										
										$sql5 = mysql_query("select * from accion where accion_padre = ".$bus4["id_accion"]."");
											
											while($bus5= mysql_fetch_array($sql5)){
												
												$html.="<item text='".$bus5["nombre_accion"]."' id='".$bus5["id_accion"]."'>";
												
													$sql6 = mysql_query("select * from accion where accion_padre = ".$bus5["id_accion"]."");
											
														while($bus5= mysql_fetch_array($sql6)){
															$html.="<item text='".$bus5["nombre_accion"]."' id='".$bus5["id_accion"]."'></item>";
														}
													$html.='</item>';
											}
										$html.='</item>';
								}
								
							$html.='</item>';
					}
					
					$html.='</item>';
			}
			
		$html.='</item>';
		
	}
	$html.="</item></tree>";		
	echo $html;
?>
