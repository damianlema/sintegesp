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
                        $sql_consultar_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where afecta = 'd'");
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
  <td width="11%">
    <input type="submit" name="buscar" id="buscar" value="Buscar" class="button">
      		</td>
         </tr>
      </table>
     </form>
        
  <?
  $query = "select * from ingresos_egresos_financieros where tipo = 'egreso'";
		if($_POST){
			if($_POST["tipo_movimiento"] != 0){
				$query .= " and idtipo_movimiento = '".$_POST["tipo_movimiento"]."'";
			}
			if($_POST["cuenta"] != 0){
				$query .= " and idcuentas_bancarias = '".$_POST["cuenta"]."'";
			}
		}		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
          	<td width="12%" align="center" class="Browse" style="font-size:9px">Tipo de Movimiento</td>
            <td width="19%" align="center" class="Browse" style="font-size:9px">Numero Documento</td>
            <td width="11%" align="center" class="Browse" style="font-size:9px">Fecha</td>
            <td align="center" class="Browse" style="font-size:9px">Monto</td>
            <td align="center" class="Browse" style="font-size:9px">Cuenta</td>
            <td align="center" class="Browse" style="font-size:9px">Emitido Por</td>
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_ingresos = mysql_query($query); 
          while($bus_ingresos = mysql_fetch_array($sql_ingresos)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  <?
                  $sql_tipo_movimiento = mysql_query("select * from tipo_movimiento_bancario where idtipo_movimiento_bancario = '".$bus_ingresos["idtipo_movimiento"]."'");
				  $bus_tipo_movimiento = mysql_fetch_array($sql_tipo_movimiento);
				  ?>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_tipo_movimiento["denominacion"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_ingresos["numero_documento"]?></td>
                    <td class='Browse' align='left' style="font-size:10px">&nbsp;<?=$bus_ingresos["fecha"]?></td>
                    <td class='Browse' align='right' width="12%" style="font-size:10px">&nbsp;<?=$bus_ingresos["monto"]?></td>
                    <?
                    $sql_cuentas_bancarias = mysql_query("select * from cuentas_bancarias where idcuentas_bancarias = '".$bus_ingresos["idcuentas_bancarias"]."'");
					$bus_cuentas_bancarias = mysql_fetch_array($sql_cuentas_bancarias);
					?>
                    <td class='Browse' align='center' width="24%" style="font-size:10px">&nbsp;<?=$bus_cuentas_bancarias["numero_cuenta"]?></td>
                    <td class='Browse' align='left' width="16%" style="font-size:10px">&nbsp;<?=$bus_ingresos["emitido_por"]?></td>
                  <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.mostrarContenido('<?=$bus_ingresos["idingresos_financieros"]?>',
                            								'<?=$bus_ingresos["idtipo_movimiento"]?>',
                            								'<?=$bus_ingresos["numero_documento"]?>',
                                                            '<?=$bus_ingresos["fecha"]?>',
                                                            '<?=$bus_ingresos["idbanco"]?>',
                                                            '<?=$bus_ingresos["idcuentas_bancarias"]?>',
                                                            '<?=$bus_ingresos["monto"]?>',
                                                            '<?=$bus_ingresos["emitido_por"]?>',
                                                            '<?=$bus_ingresos["ci_emitido"]?>',
                                                            '<?=$bus_ingresos["concepto"]?>'), window.close()">
                    </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
 registra_transaccion("Listar Ingresos",$login,$fh,$pc,'documentos_recibidos');
 ?>