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
Lista de Movimientos Individuales</h4>
<h2 class="sqlmVersion"></h2>
<br>
     <form action="" method="post"> 
       <table width="552" border="0" align="center">
  <tr>
            <td width="32%" align="right" class='viewPropTitle'> Codigo del Bien:</td>
      <td width="24%"><input type="text" name="codigo" id="codigo"></td>
          <td width="15%" align="center"><input type="submit" name="buscar" id="buscar" value="Buscar" class="button"></td>
         </tr>
      </table>
    </form>
        
  <?
  	if($_POST){
  
	  $query = "select * from movimientos_bienes_individuales where codigo_bien like '%".$_POST["codigo"]."%'";
		//echo $query;		
  ?>      <br />
  
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="98%">
          <thead>
          <tr>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Codigo del Bien</td>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Tipo</td>
            <td width="37%" align="center" class="Browse" style="font-size:9px">Fecha Movimiento</td>
            
            <td width="6%" align="center" class="Browse" style="font-size:9px">Seleccionar</td>
          </tr>
          </thead>
          <?
		  $sql_consultar = mysql_query($query)or die(mysql_error()); 
          while($bus_consultar = mysql_fetch_array($sql_consultar)){
		  ?>
                  <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')">
                  	
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["codigo_bien"]?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px">
					<?
                    if($bus_consultar["tipo"] == "mueble"){
						echo "Mueble";
					}else{
						echo "Inmueble";
					}
					?></td>
                    <td class='Browse' align='left' width="37%" style="font-size:10px"><?=$bus_consultar["fecha_movimiento"]?></td>
                    
                    <td align="center" valign="middle" class='Browse'>
                        <img src="../../../imagenes/validar.png"
                            style="cursor:pointer"
                            onClick="opener.consultarMovimiento('<?=$bus_consultar["idmovimientos_bienes_individuales"]?>'), window.close()">                    </td>
   		        </tr>
          <?
          }
          ?>
        </table>
 <?
 }
 registra_transaccion("Listar Muebles",$login,$fh,$pc,'muebles');
 ?>