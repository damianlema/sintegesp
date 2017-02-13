<?
session_start();
include("../../../conf/conex.php");
Conectarse();
?>
<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
<script src="../../../js/function.js" type="text/javascript" language="javascript"></script>
<!-- ************************************************************************************************************************************* 
    ****************************************************** LISTA DE CUENTAS BANCARIAS ***************************************************
    *******************************************************************************************************************************************-->
	<br>
	<h4 align=center>Lista de Cuentas Bancarias</h4>
	<h2 class="sqlmVersion"></h2>    
    
  
  <form method="post" action="">
  <table align="center">
  	<tr>
    	<td>Numero de Cuenta</td>
        <td><input type="text" name="numero_cuenta" id="numero_cuenta" size="25" maxlength="20"></td>
        <td>Banco</td>
        <td>
         <?
		  $sql_bancos = mysql_query("select * from banco");
		  ?>
		  <select name="banco" id="banco">
		  <option value="0">.:: Seleccione ::.</option>
		  <?
		  while($bus_bancos = mysql_fetch_array($sql_bancos)){
			?>
			<option value="<?=$bus_bancos["idbanco"]?>"><?=$bus_bancos["denominacion"]?></option>
			<?
		  }
		  ?>
      </select>
        </td>
        <td><input type="submit" name="buscar" id="buscar" value="Buscar" class="button"></td>
    </tr>
  </table>
  </option>
  
  
  
    
<div id="listaCuentasBancarias">
		    <?
			if($_POST){
				$query = "select * from cuentas_bancarias where status = 'a' ";
				if($_POST["numero_cuenta"] != ""){
					$query .= "and numero_cuenta like '%".$_POST["numero_cuenta"]."%' ";
				}
				if($_POST["banco"] != 0){
					$query .= "and idbanco = '".$_POST["banco"]."'";
				}
			}else{
				$query = "select * from cuentas_bancarias";
			}
			$sql_cuentas_bancarias = mysql_query($query);
			$num_cuentas_bancarias = mysql_num_rows($sql_cuentas_bancarias);
			if($num_cuentas_bancarias > 0){
			?>
			<table class="Browse" cellpadding="0" cellspacing="0" width="70%" align="center">
<thead>
					<tr>
						<td align="center" class="Browse">Nro. Cuenta</td>
						<td align="center" class="Browse">Banco</td>
                        <td align="center" class="Browse">Tipo de Cuenta</td>
                        <td align="center" class="Browse">Estado</td>
						<td align="center" class="Browse">Acci&oacute;n</td>
					</tr>
				</thead>
				<?
				while($bus_cuentas_bancarias = mysql_fetch_array($sql_cuentas_bancarias)){
				?>
				<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
						<td align='left' class='Browse'><?=$bus_cuentas_bancarias["numero_cuenta"]?></td>
                        <?
                        $sql_banco = mysql_query("select * from banco where idbanco = '".$bus_cuentas_bancarias["idbanco"]."'");
						$bus_banco = mysql_fetch_array($sql_banco);
						?>
                        <td align='left' class='Browse'><?=$bus_banco["denominacion"]?></td>
                        <?
                        $sql_tipo_cuenta = mysql_query("select * from tipo_cuenta_bancaria where idtipo_cuenta = '".$bus_cuentas_bancarias["idtipo_cuenta"]."'");
						$bus_tipo_cuenta = mysql_fetch_array($sql_tipo_cuenta);
						?>                        
                        <td align='left' class='Browse'><?=$bus_tipo_cuenta["denominacion"]?></td>
                        <td align='center' class='Browse'><?=$bus_cuentas_bancarias["estado"]?></td>

<td align='center' class='Browse' width='7%'>
								<img src='../../../imagenes/validar.png' border='0' alt='Seleccionar' title='Seleccionar' onclick=" opener.document.getElementById('id_cuenta_bancaria').value='<?=$bus_cuentas_bancarias["idcuentas_bancarias"]?>', window.onUpload = window.opener.consultarCuentaBancaria('<?=$bus_cuentas_bancarias["idcuentas_bancarias"]?>'), window.close()" style="cursor:pointer">
						</td>						

			  </tr>
				 <?
				 }
				 ?>
			</table>
<?
			}
			?>
    </div>