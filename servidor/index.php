<?
mysql_connect("localhost", "root", "1234");
?>
<html target="_blank">
	<head>
<link rel="shortcut icon" href="imagenes/icono_sistema.ico" type="image/x-icon" />
	<title>.:: SINTEGESP ::. Sistema Integrado de Gesti&oacute;n P&uacute;blica</title>
	<link href="gestion/css/theme/green/main.css" rel="stylesheet" type="text/css"> <!carga la hoja de estilo>
	<script language='JavaScript' type='text/JavaScript'>
		function abrir(anio){
			OpenWin = this.open('gestion_'+anio+'/marcos.html', 'Ventana Principal', "type=fullWindow,fullscreen,toolbar=no, menubar=no, location=no, scrollbars=yes, resizable=no, status=yes");
			//winacces=resizeTo(screen.width, screen.height);
			
			}
			document.oncontextmenu=new Function("return false")
		</SCRIPT>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-color: #EAEAEA;
}
.Estilo4 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style></head>
	<body>
		
	<!-- <br><br><br><br><br><br><br><br><br><br> -->
	<form> 
		<table align="center" width="100%" height="100%" >
			<tr><td height="59" style="background-image: url(gestion/imagenes/encabeza.jpg)"></td>
			</tr>
            <tr>
			<td valign="middle" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
		    	<table width="370" class="home" style="border:#CCCCCC 8px solid">
            		<tr>
            		  <td width="185"><img src="gestion/imagenes/prueba_logo2.png" width="181" height="94"></td>
                      <td width="159">
                        <?
                        $sql_consulta = mysql_query("select * from gestion_configuracion_general.anios")or die(mysql_error());
						?>
						<table>
                        <tr>
                        <td style="font-family:verdana; font-size:11px">
                        A&ntilde;o a Ejecutar:
                        </td>
                        <td>
                        <select name="anio" id="anio">
							<?
                            while($bus_consulta = mysql_fetch_array($sql_consulta)){
                            ?>
                            <option <? if($bus_consulta["anio"] == date("Y")){echo "selected";}?> value="<?=$bus_consulta["anio"]?>"><?=$bus_consulta["anio"]?></option>
                            <?
                            }
                            ?>
                        </select>
						</td>
                        </tr>
                        </table>

                        <input type=button value="Iniciar aplicaci&oacute;n" onClick="abrir(document.getElementById('anio').value)" class="button">                        </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
                        <br>
                        <br>
                      Haga click en <strong>Iniciar Aplicaci&oacute;n</strong> y le aparecera la pantalla de acceso al sistema                        </td>
                   	</tr>
                </table>
                <br>
<br>
Copyleft &copy; 2009 La red.com, c.a.<br>
RIF: J-31421362-8<br>
          Direcci&oacute;n: Calle Pedernales Casa N&ordm; 12 Local La red.com <br>
          Urb. Delfin Mendoza 
  Tucupita, Estado Delta Amacuro<br>
  Hecho en Venezuela<br>
          Tlf: (0287) 721-31-44<br>
      	  <a href="http://www.sintegesp.com" class="Estilo4">www.sintegesp.com</a><br>
			</td>
			</tr>
		</table>
	</form>
</body>
</html>