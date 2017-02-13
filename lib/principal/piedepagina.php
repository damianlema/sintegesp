<?php
 /**
 * @file inc_piedepagina.php
 * Archivo de Inclusion de Pie de Pagina.
 *
 * Probado con un servidor Apache 2.0,
 * Postgresql version 7.4.7 y PHP version 4.3.10-10 bajo Ubuntu 3.4.
 *
 * @author Fundacite M&#233;rida - Leonardo Caballero.
 * @date 2005-09-08
 * @version 0.1
 *
 */
 
/***
*Condici&oacute;n que eval&uacute;a si la sesi&oacute;n ha expirado, en caso afirmativo muestra un mensaje al usuario y redirecciona al formulario de acceso al sistema

if (!isset($_SESSION['id_sesion']) && !isset($_SESSION['enviar_datos']) && !isset($_SESSION['expiro'])) {
  print "<script type='text/javascript'>";
  print "alert('La sesi&oacute;n a expirado, debe ingresar nuevamente al sistema...');";
  print "window.location='".$appsURL.$appsAliasURL."index.php'";
  print "</script>";
}*/
?>
  <table align='center'>
  <tr >
   <td colspan="3">
    <div align="center">
      <img src="imagenes/botones/apache.png" border="0">
	  <img src="imagenes/botones/php.png" border="0">
	  <img src="imagenes/botones/mysql.png" border="0">
	  <img src="imagenes/botones/valid-html.png" border="0">
	 </div>
   </td>
   </tr>
   <tr>
   <td colspan="3"></td>
   </tr>
   <tr>
   <td colspan="3">
    <div align="center">
	  <img src="imagenes/botones/valid-css.png" border="0">
	  <img src="imagenes/botones/ubuntu.png" border="0">
	  <img src="imagenes/botones/debian.png" border="0">
	  <img src="imagenes/botones/firefox.png" border="0">
	</div>
   </td>
  </tr>
  </table>