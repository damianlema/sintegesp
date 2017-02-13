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
    <br>
<h4 align=center>
Lista de Movimientos Lotes</h4>
<h2 class="sqlmVersion"></h2>
<br>
     <form action="" method="post"> 
       <table width="90%" border="0" align="center">
  		<tr>
      <td width="16%" align="right" class='viewPropTitle'> Nro. Movimiento:</td>
      <td width="17%"><input type="text" name="nro_movimiento" id="nro_movimiento"></td>
      <td width="11%" align="right" class='viewPropTitle'> Nro. Orden:</td>
      <td width="19%"><input type="text" name="nro_orden" id="nro_orden"></td>
      <td width="12%" align="right" class='viewPropTitle'> Justificacion:</td>
      <td width="16%"><input type="text" name="justificacion" id="justificacion"></td>
          <td width="9%" align="center"><input type="submit" name="buscar" id="buscar" value="Buscar" class="button"></td>
         </tr>
      </table>
    </form>
        
  <?
  	if($_POST){
  
	  $query = "select * from movimientos_lotes where 
	  											nro_movimiento like '%".$_POST["nro_movimiento"]."%'
												and nro_orden like '%".$_POST["nro_orden"]."%'
												and justificacion_movimiento like '%".$_POST["justificacion"]."%'";
		//echo $query;		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="9%" align="center" class="Browse" style="font-size:9px">Nro Movimiento</td>
            <td width="11%" align="center" class="Browse" style="font-size:9px">Nro Orden</td>
            <td width="14%" align="center" class="Browse" style="font-size:9px">Fecha Orden</td>
            <td width="10%" align="center" class="Browse" style="font-size:9px">Tipo</td>
            <td width="50%" align="center" class="Browse" style="font-size:9px">Justificación</td>
            <td width="50%" align="center" class="Browse" style="font-size:9px">Estado</td>
            
            
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query($query)or die(mysql_error()); 
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="9%" style="font-size:10px">&nbsp;<?=$bus_consultar["nro_movimiento"]?></td>
                    <td class='Browse' align='left' width="11%" style="font-size:10px">&nbsp;<?=$bus_consultar["nro_orden"]?></td>
                    <td class='Browse' align='left' width="14%" style="font-size:10px">&nbsp;<?=$bus_consultar["fecha_orden"]?></td>
                    <td class='Browse' align='left' width="10%" style="font-size:10px">
					<?
                    if($bus_consultar["tipo"] == "mueble"){
						echo "Mueble";
					}else{
						echo "Inmueble";
					}
					?></td>
                    <td class='Browse' align='left' width="50%" style="font-size:10px">&nbsp;<?=$bus_consultar["justificacion_movimiento"]?></td>
                    <td class='Browse' align='left' width="50%" style="font-size:10px">&nbsp;<?=$bus_consultar["estado"]?></td>
                    
                    <td align="center" valign="middle" class='Browse'>
                      <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.consultarMovimiento('<?=$bus_consultar["idmovimientos_lotes"]?>'), window.close()">                    </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
 }
 registra_transaccion("Listar Muebles",$login,$fh,$pc,'muebles');
 ?>