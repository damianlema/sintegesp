<?
session_start();

include("../../../conf/conex.php");
$conection_db = conectarse();
include("../../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../../js/funciones.js"></script>
<script type="text/javascript" src="../../../js/function.js"></script>
<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../../css/theme/calendar-win2k-cold-1.css"); </style>
    <br />
     	<h2 class="sqlmVersion"></h2>
	<br> 
     <form action="" method="post"> 
       <table width="573" border="0" align="center">
			<tr>
            <td width="15%"> Beneficiario:</td>
            <td width="22%"><input type="text" name="beneficiario" id="beneficiario"></td>
             <td width="29%"> Numero de Cheque:</td>
            <td width="22%"><input type="text" name="numero_cheque" id="numero_cheque"></td>
      		<td width="12%">
       		  <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      		</td>
         </tr>
      </table>
    </form>
        
  <?
 
	if($_POST["buscar"]){
		$query = "select * from pagos_financieros";
		$query .= " where";
		$query .= " beneficiario like '%".$_POST["beneficiario"]."%'";
		$query .= "and numero_cheque like '%".$_POST["numero_cheque"]."%'";	
				
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
          	<td width="10%" align="center" class="Browse" style="font-size:9px">Orden Pago</td>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Beneficiario</td>
            <td width="11%" align="center" class="Browse" style="font-size:9px">Monto Cheque</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Banco</td>
            <td align="center" class="Browse" style="font-size:9px">Cuenta</td>
            <td align="center" class="Browse" style="font-size:9px">Numero Cheque</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_emision_pagos = mysql_query($query);
          while($bus_emision_pagos = mysql_fetch_array($sql_emision_pagos)){

				$sql_cuentas_bancarias = mysql_query("select * from cuentas_bancarias
							where idcuentas_bancarias = '".$bus_emision_pagos["idcuenta_bancaria"]."'");
				$bus_cuentas_bancarias = mysql_fetch_array($sql_cuentas_bancarias);
				$sql_banco = mysql_query("select * from banco where idbanco = '".$bus_cuentas_bancarias["idbanco"]."'");
				$bus_banco = mysql_fetch_array($sql_banco);

				$sql_tipo_documento = mysql_query("select * from tipos_documentos
										where idtipos_documentos = '".$bus_emision_pagos["idtipo_documento"]."'");
			  	$bus_tipo_documento = mysql_fetch_array($sql_tipo_documento);

				$sql_orden_pago = mysql_query("select * from orden_pago where idorden_pago = '".$bus_emision_pagos["idorden_pago"]."'");
				$bus_orden_pago = mysql_fetch_array($sql_orden_pago);

				$sql_cheques_cuentas_bancarias = mysql_query("select * from cheques_cuentas_bancarias
									where idcheques_cuentas_bancarias = '".$bus_emision_pagos["idcheques_cuentas_bancarias"]."'");
				$bus_cheques_cuentas_bancarias = mysql_fetch_array($sql_cheques_cuentas_bancarias);
				if ($bus_emision_pagos["estado"] == 'anulado'){
					$estado = ' --- (ANULADO) --- '.$bus_emision_pagos["fecha_anulacion"];
				}else{
					$estado = '';
				}


		  		if ($bus_emision_pagos["estado"] == "anulado"){
					?>
                    <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="
                    										opener.mostrarContenido('<?=$bus_emision_pagos["idtipo_documento"]?>',
                            								'<?=$bus_emision_pagos["forma_pago"]?>',
                                                            '<?=$bus_emision_pagos["porcentaje_pago"]?>',
                                                            '<?=$bus_emision_pagos["numero_parte_pago"]?>',
                                                            '<?=$bus_emision_pagos["idtipo_movimiento_bancario"]?>',
                                                            '<?=$bus_orden_pago["numero_orden"]?>',
                                                            '<?=$bus_emision_pagos["idcuenta_bancaria"]?>',
                                                            '<?=$bus_emision_pagos["numero_cheque"]?>',
                                                            '<?=$bus_emision_pagos["fecha_cheque"]?>',
                                                            '<?=$bus_emision_pagos["monto_cheque"]?>',
                                                            '<?=$bus_emision_pagos["beneficiario"].$estado?>',
                                                            '<?=$bus_emision_pagos["ci_beneficiario"]?>',
                                                            '<?=$bus_emision_pagos["formato_imprimir"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["chequera_numero"]?>',
                                                            '<?=$bus_emision_pagos["estado"]?>',
                                                            '<?=$bus_emision_pagos["idpagos_financieros"]?>',
                                                            '<?=$bus_cuentas_bancarias["idbanco"]?>',
                                                            '<?=$bus_emision_pagos["modo_cancelacion"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["digitos_consecutivos"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["cantidad_digitos"]?>',
                                                            '<?=$bus_emision_pagos["idasiento_contable"]?>'), window.close()">
                <?php
                
                 }else{
                    ?>
                    <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="
                    										opener.mostrarContenido('<?=$bus_emision_pagos["idtipo_documento"]?>',
                            								'<?=$bus_emision_pagos["forma_pago"]?>',
                                                            '<?=$bus_emision_pagos["porcentaje_pago"]?>',
                                                            '<?=$bus_emision_pagos["numero_parte_pago"]?>',
                                                            '<?=$bus_emision_pagos["idtipo_movimiento_bancario"]?>',
                                                            '<?=$bus_orden_pago["numero_orden"]?>',
                                                            '<?=$bus_emision_pagos["idcuenta_bancaria"]?>',
                                                            '<?=$bus_emision_pagos["numero_cheque"]?>',
                                                            '<?=$bus_emision_pagos["fecha_cheque"]?>',
                                                            '<?=$bus_emision_pagos["monto_cheque"]?>',
                                                            '<?=$bus_emision_pagos["beneficiario"].$estado?>',
                                                            '<?=$bus_emision_pagos["ci_beneficiario"]?>',
                                                            '<?=$bus_emision_pagos["formato_imprimir"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["chequera_numero"]?>',
                                                            '<?=$bus_emision_pagos["estado"]?>',
                                                            '<?=$bus_emision_pagos["idpagos_financieros"]?>',
                                                            '<?=$bus_cuentas_bancarias["idbanco"]?>',
                                                            '<?=$bus_emision_pagos["modo_cancelacion"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["digitos_consecutivos"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["cantidad_digitos"]?>',
                                                            '<?=$bus_emision_pagos["idasiento_contable"]?>'), window.close()">
                <?php	
                        
               	}
	
			  	
					?>
                    <td class='Browse' align='left' width="10%" style="font-size:10px">&nbsp;<?=$bus_orden_pago["numero_orden"]?></td>
                    <?
                    if($bus_emision_pagos["estado"] == "anulado"){
						?>
						<td class='Browse' align='left' width="37%" style="font-size:10px; font-weight:bold"><strong>
						&nbsp;<?=$bus_emision_pagos["beneficiario"]?>( ANULADO )</strong>
                        </td>
                    <?
					}else{
						?>
						<td class='Browse' align='left' width="37%" style="font-size:10px; font-weight:bold"><strong>
						&nbsp;<?=$bus_emision_pagos["beneficiario"]?></strong>
                        </td>
					<?	
					}
					?>                    </td>
                    <td class='Browse' align='right' width="11%" style="font-size:10px">&nbsp;<?=number_format($bus_emision_pagos["monto_cheque"],2,",",".")?></td>
                    
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_banco["denominacion"]?></td>
                    <td class='Browse' align='left' width="17%" style="font-size:10px">&nbsp;<?=$bus_cuentas_bancarias["numero_cuenta"]?></td>
                    <td class='Browse' align='left' width="9%" style="font-size:10px">&nbsp;<?=$bus_emision_pagos["numero_cheque"]?></td>
                    <?
                    
					
					?>
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.mostrarContenido('<?=$bus_emision_pagos["idtipo_documento"]?>',
                            								'<?=$bus_emision_pagos["forma_pago"]?>',
                                                            '<?=$bus_emision_pagos["porcentaje_pago"]?>',
                                                            '<?=$bus_emision_pagos["numero_parte_pago"]?>',
                                                            '<?=$bus_emision_pagos["idtipo_movimiento_bancario"]?>',
                                                            '<?=$bus_orden_pago["numero_orden"]?>',
                                                            '<?=$bus_emision_pagos["idcuenta_bancaria"]?>',
                                                            '<?=$bus_emision_pagos["numero_cheque"]?>',
                                                            '<?=$bus_emision_pagos["fecha_cheque"]?>',
                                                            '<?=$bus_emision_pagos["monto_cheque"]?>',
                                                            '<?=$bus_emision_pagos["beneficiario"].$estado?>',
                                                            '<?=$bus_emision_pagos["ci_beneficiario"]?>',
                                                            '<?=$bus_emision_pagos["formato_imprimir"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["chequera_numero"]?>',
                                                            '<?=$bus_emision_pagos["estado"]?>',
                                                            '<?=$bus_emision_pagos["idpagos_financieros"]?>',
                                                            '<?=$bus_cuentas_bancarias["idbanco"]?>',
                                                            '<?=$bus_emision_pagos["modo_cancelacion"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["digitos_consecutivos"]?>',
                                                            '<?=$bus_cheques_cuentas_bancarias["cantidad_digitos"]?>',
                                                            '<?=$bus_emision_pagos["idasiento_contable"]?>'), window.close()">
                    </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
	}
 registra_transaccion("Listar Documentos Recibidos en ".$oficina." ",$login,$fh,$pc,'documentos_recibidos');
 ?>