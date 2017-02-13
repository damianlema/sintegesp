<?
session_start();
include_once("../../conf/conex.php");
$conexion_db=conectarse();
?>	
	<body>
	
    	<link href="../../css/theme/green/main.css" rel="stylesheet" type="text/css">
	<script src="../../js/function.js" type="text/javascript" language="javascript"></script>
    <br>
	<h4 align=center>Lista de Conceptos y Constantes</h4>
	<h2 class="sqlmVersion"></h2>
	<br>
	

	
	<form name="buscar" action="listar_conceptos_constantes.php" method="POST">
	<table align=center cellpadding=2 cellspacing=0>
		<tr>
			<td align='right' class='viewPropTitle'>Buscar:</td>
			<td class=''><label>
			  <select name="buscar" id="buscar">
			    <option <? if($_POST["buscar"] == "conceptos_nomina"){ echo "selected";}?> value="conceptos_nomina">Conceptos</option>
			    <option <? if($_POST["buscar"] == "constantes_nomina"){ echo "selected";}?> value="constantes_nomina">Constantes</option>
		      </select>
	      </label></td>
          <td align='right' class='viewPropTitle'><input type="text" name="campoBuscar" id="campoBuscar" value="<?=$_POST["campoBuscar"]?>"></td>
			<td>
				<input align=center name="boton_buscar" type="submit" value="Buscar" class="button">
				</a>
			</td>
		</tr>
	</table>
	</form>
	
	<div align="center">
    <?
    if($_POST){
		
		if($_POST["buscar"] == "constantes_nomina"){
			$sql= mysql_query("select cn.codigo, 
							   cn.descripcion,
							   cn.tipo,
							   cn.valor,
							   cn.idconstantes_nomina
								from constantes_nomina cn, 
							  				tipo_conceptos_nomina tc
													  where 
													  cn.descripcion like '%".$_POST["campoBuscar"]."%'
													  and cn.afecta = tc.idconceptos_nomina");
		}else{
			$sql= mysql_query("select cn.codigo, 
							  			cn.descripcion,
										cn.idconceptos_nomina
										from conceptos_nomina cn, 
							  				tipo_conceptos_nomina tc
													  where 
													  cn.descripcion like '%".$_POST["campoBuscar"]."%'
													  and cn.tipo_concepto = tc.idconceptos_nomina");
		}
		
	?>
			<table class="Main" cellpadding="0" cellspacing="0" width="80%">
				<tr>
					<td>
						<form name="grilla" action="lista_cargos.php" method="POST">
						<table class="Browse" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<!--<td class="Browse">&nbsp;</td>-->
									<td align="center" class="Browse">C&oacute;digo</td>
									<td align="center" class="Browse">Descripci&oacute;n</td>
									<td align="center" class="Browse" colspan="2">Selecci&oacute;n</td>
								</tr>
							</thead>
							
							<?php
								while($bus= mysql_fetch_array($sql)){
								?>
								<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" onClick="opener.document.getElementById('id_concepto_constante').value = '<?=$bus["id".$_POST["buscar"].""]?>', opener.document.getElementById('tabla').value = '<?=$_POST["buscar"]?>', opener.document.getElementById('concepto_constante').value = '(<?=$bus["codigo"]?>) <?=$bus["descripcion"]?>', <? if($bus["tipo"] == 'valor_fijo' and $bus["valor"] == "0"){ echo "opener.document.getElementById('valor_fijo').style.display = 'block', opener.document.getElementById('texto_valor_fijo').innerHTML = 'Valor Fijo', opener.document.getElementById('valor_fijo').focus(), ";}?> window.close()">
                                    <td align='center' class='Browse' width='20%'><?=$bus["codigo"]?></td>
                                    <td align='left' class='Browse'><?=$bus["descripcion"]?></td>
                                  <td align='center' class='Browse' width='7%'> 
                                    <a href='#' onClick="opener.document.getElementById('id_concepto_constante').value = '<?=$bus["id".$_POST["buscar"].""]?>', opener.document.getElementById('tabla').value = '<?=$_POST["buscar"]?>', opener.document.getElementById('concepto_constante').value = '(<?=$bus["codigo"]?>) <?=$bus["descripcion"]?>', <? if($bus["tipo"] == 'valor_fijo' and $bus["valor"] == "0"){ echo "opener.document.getElementById('valor_fijo').style.display = 'block', opener.document.getElementById('texto_valor_fijo').innerHTML = 'Valor Fijo', opener.document.getElementById('valor_fijo').focus(), ";}?> window.close()">
                                    <img src='../../imagenes/validar.png'>
                                    </a>
                                  </td>
								</tr>
								<?
                                }
							?>
						</table>
						</form>
					</td>
				</tr>
			</table>
   <?
	}
   ?>
		</div>
	
</body>
</html>