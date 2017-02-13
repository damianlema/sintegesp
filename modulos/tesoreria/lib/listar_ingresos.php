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
      <table width="610" border="0" align="center">
        <tr>
          <td width="22%">Tipo Movimiento:</td>
          <td width="26%">
            <select name="tipo_movimiento" id="tipo_movimiento">
              <option value="0">.:: Seleccione ::.</option>
              <?
                      $sql_consultar_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario");
                          while($bus_consultar_tipo_movimiento = mysql_fetch_array($sql_consultar_tipo_movimiento)){
                              ?>
              <option value="<?=$bus_consultar_tipo_movimiento["idtipo_movimiento_bancario"]?>">
                <?=$bus_consultar_tipo_movimiento["denominacion"]?>
                </option>
              <?
      				}
      				?>
				    </select>
          </td>
          <td width="18%">Numero Cuenta</td>
          <td width="23%">
    			<?
    				$sql_consultar_cuentas = mysql_query("select * from cuentas_bancarias");
    				?>
            <select name="cuenta" id="cuenta">
    					<option value="0">.:: Seleccione ::.</option>
    					<?
    					while($bus_consultar_cuentas = mysql_fetch_array($sql_consultar_cuentas)){
    					?>
    						<option	value="<?=$bus_consultar_cuentas["idcuentas_bancarias"]?>"><?=$bus_consultar_cuentas["numero_cuenta"]?></option>
    						<?
    					}
    					?>
    				  </select>  
            </td>
            <td width="23%">
              <input name="texto_buscar" type="text" id="texto_buscar" size="15" maxlength="20">
            </td>
            <td width="11%">
              <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
          	</td>
        </tr>
      </table>
    </form>
              
  <br />

    <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
      <thead>
      <tr>
      	<td width="8%" align="center" class="Browse" style="font-size:9px">Tipo de Movimiento</td>
        <td width="8%" align="center" class="Browse" style="font-size:9px">Numero Documento</td>
        <td width="8%" align="center" class="Browse" style="font-size:9px">Fecha</td>
        <td width="10%" align="center" class="Browse" style="font-size:9px">Monto</td>
        <td width="18%" align="center" class="Browse" style="font-size:9px">Cuenta</td>
        <td width="20%" align="center" class="Browse" style="font-size:9px">Emitido Por</td>
        <td width="26%" align="center" class="Browse" style="font-size:9px">Concepto</td>
        <td width="2%" align="center" class="Browse" style="font-size:9px"></td>
      </tr>
      </thead>
      <?
		   
  
		if($_POST){
			$query = "select * from ingresos_egresos_financieros";
			if($_POST["tipo_movimiento"] != 0){
				$query .= " where idtipo_movimiento = '".$_POST["tipo_movimiento"]."'";
        if($_POST["cuenta"] != 0){
          $query .= " and idcuentas_bancarias = '".$_POST["cuenta"]."'";
          if($_POST["texto_buscar"] != ''){
            $query .= " and (numero_documento like '%".$_POST["texto_buscar"]."%'
                            or emitido_por like '%".$_POST["texto_buscar"]."%'
                            or concepto like '%".$_POST["texto_buscar"]."%')";
          }
        }else{
          if($_POST["texto_buscar"] != ''){
            $query .= " and (numero_documento like '%".$_POST["texto_buscar"]."%'
                            or emitido_por like '%".$_POST["texto_buscar"]."%'
                            or concepto like '%".$_POST["texto_buscar"]."%')";
          }
        }
			}else{

  			if($_POST["cuenta"] != 0){
  				$query .= " where idcuentas_bancarias = '".$_POST["cuenta"]."'";
          if($_POST["texto_buscar"] != ''){
            $query .= " and (numero_documento like '%".$_POST["texto_buscar"]."%'
                            or emitido_por like '%".$_POST["texto_buscar"]."%'
                            or concepto like '%".$_POST["texto_buscar"]."%')";
          }
  			}else{
          if($_POST["texto_buscar"] != ''){
            $query .= " where (numero_documento like '%".$_POST["texto_buscar"]."%'
                            or emitido_por like '%".$_POST["texto_buscar"]."%'
                            or concepto like '%".$_POST["texto_buscar"]."%')";
          }
        }
      }
		  $sql_ingresos = mysql_query($query); 
          while($bus_ingresos = mysql_fetch_array($sql_ingresos)){
		  
		  		if ($bus_ingresos["estado"] == 'procesado'){
		  ?>
          		
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  <?
				}else{ ?>
				  <tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#FF0000', '#FFCC00', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')">
				<?
				}
                  $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where idtipo_movimiento_bancario = '".$bus_ingresos["idtipo_movimiento"]."'");
				  $bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento);
				  ?>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_tipo_movimiento["denominacion"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_ingresos["numero_documento"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_ingresos["fecha"]?></td>
                    <td class='Browse' align='right' style="font-size:10px">&nbsp;<?=$bus_ingresos["monto"]?></td>
                    <?
                    $sql_cuentas_bancarias = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$bus_ingresos["idcuentas_bancarias"]."'");
					$bus_cuentas_bancarias = mysql_fetch_array($sql_cuentas_bancarias);
					?>
                    <td class='Browse' align='center' style="font-size:10px">&nbsp;<?=$bus_cuentas_bancarias["numero_cuenta"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=utf8_decode($bus_ingresos["emitido_por"])?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=utf8_decode($bus_ingresos["concepto"])?></td>
                  <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.mostrarContenido('<?=$bus_ingresos["idingresos_financieros"]?>',
                            								'<?=$bus_ingresos["idfuente_financiamiento"]?>',
                            								'<?=$bus_ingresos["idtipo_movimiento"]?>',
                            								'<?=$bus_ingresos["numero_documento"]?>',
                                                            '<?=$bus_ingresos["fecha"]?>',
                                                            '<?=$bus_ingresos["idbanco"]?>',
                                                            '<?=$bus_ingresos["idcuentas_bancarias"]?>',
                                                            '<?=$bus_ingresos["monto"]?>',
                                                            '<?=utf8_decode($bus_ingresos["emitido_por"])?>',
                                                            '<?=$bus_ingresos["ci_emitido"]?>',
                                                            '<?=utf8_decode($bus_ingresos["concepto"])?>',
                                                            '<?=$bus_ingresos["idasiento_contable"]?>',
                                                            '<?=$bus_ingresos["estado"]?>',
                                                            '<?=$bus_ingresos["anio_documento"]?>',
                                                            '<?=$bus_ingresos["excluir_contabilidad"]?>'), window.close()">
                    </td>
   		        </tr>
          <?
          }
		  }	
          ?>
        </table>
 <?
 registra_transaccion("Listar Ingresos",$login,$fh,$pc,'documentos_recibidos');
 ?>