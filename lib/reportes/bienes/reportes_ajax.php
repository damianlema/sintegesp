<?
session_start();
include("../../../conf/conex.php");
include("../../../funciones/funciones.php");

if($ejecutar == "seleccionarNivel"){
	  $foros = array();
      $result = mysql_query("SELECT idniveles_organizacionales, 
	  								denominacion, 
									sub_nivel 
										FROM 
									niveles_organizacionales 
										where 
									organizacion = '".$idorganizacion."'
									and modulo = '".$_SESSION["modulo"]."'
									") or die(mysql_error());
      while($row = mysql_fetch_assoc($result)) {
          $foro = $row['idniveles_organizacionales'];
          $padre = $row['sub_nivel'];
          if(!isset($foros[$padre]))
              $foros[$padre] = array();
          $foros[$padre][$foro] = $row;
      }
	echo "aqui";
		?>
        <select id="idnivel_organizacion" name="idnivel_organizacion">
        <?
			listar_foros(0, '');
		?>
        </select>
		<?
		return;
}

?>