<?
session_start();
include("../../../conf/conex.php");
$conection_db = conectarse();
include("../../../funciones/funciones.php");
$existen_registros=0;
/*if (isset($_POST["buscar"])){
	$texto_buscar=$_POST["textoabuscar"];
	$sql_principal = mysql_query("select * from asiento_contable 
										where detalle like '%$texto_buscar%'");

}else{
	$sql_principal = mysql_query("select * from asiento_contable");
}
if (mysql_num_rows($sql_principal)<=0)
		{
			$existen_registros=1;
		}
		*/
?>
<script type="text/javascript" src="../../../js/funciones.js"></script>
<script type="text/javascript" src="../../../js/function.js"></script>
<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../../css/theme/calendar-win2k-cold-1.css"); </style>   
     <h2 align="center">Asientos Contables</h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br>
     
       <form action="" method="post"> 
       <table width="350" border="0" align="center">
         <tr>
            <td width="68%">Dato a Buscar:</td>
            <td width="17%">
              <label>
                <input type="text" name="textoabuscar" id="textoabuscar" />
              </label>
            </td>
            <td width="15%">
              <label>
                <input type="submit" name="buscar" id="buscar" value="Buscar" class="button"/>
              </label>
            </td>
         </tr>
        </table>
        </form>
   <?
   if ($_POST["buscar"]){
	$texto_buscar=$_POST["textoabuscar"];
	$sql_principal = mysql_query("select * from asiento_contable 
										where detalle like '%$texto_buscar%'");     
	?>
        <br />
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
            <td width="5%" class="Browse"><div align="center">Nro. Asiento</div></td>
            <td width="10%" class="Browse"><div align="center">Fecha</div></td>
            <td width="56%" class="Browse"><div align="center">Detalle</div></td>
            <td width="7%" class="Browse"><div align="center">Estado</div></td>
            <td width="7%" class="Browse"><div align="center">Accion</div></td>
          </tr>
          </thead>
          <? 
		 	
			  while($bus_principal = mysql_fetch_array($sql_principal)){ 
			   	if ($bus_principal["estado"]=='anulado'){?>
			   		<tr bgcolor='#FF0000' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" 
                onMouseOut="setRowColor(this, 0, 'out', '#FF0000', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
                onClick="opener.consultarCuentas('<?=$bus_principal["idasiento_contable"]?>'), window.close()">
			   	<? }else{ ?>
			  		<tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" 
                onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" style="cursor:pointer" 
                onClick="opener.consultarCuentas('<?=$bus_principal["idasiento_contable"]?>'), window.close()">
			  	<? } ?>
		            <td class='Browse' align='left'>&nbsp;<?=$bus_principal["numero_asiento"]?></td>
		            <td class='Browse' align='left'><?=$bus_principal["fecha_contable"]?></td>
		            <td class='Browse' align="left"><?=$bus_principal["detalle"]?></td>
		            <td class='Browse' align='left'><?=$bus_principal["estado"]?></td>

		            <td class='Browse' align="center"><a href="#" 
                    onClick="opener.consultarCuentas('<?=$bus_principal["idasiento_contable"]?>'), 
                             window.close()"><img src="../../../imagenes/validar.png"></a></td> 
          		</tr>
          <?
          		}
          	
		  ?>
          
        </table>
         <?
          		
          	}
		  
        registra_transaccion("Listar Cuentas Contables",$login,$fh,$pc,'cuentas_t');
		?>