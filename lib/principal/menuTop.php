<?
session_start();
$cedula = $_SESSION['cedula_usuario'];
?><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<style type="text/css">
<!--
    body {
        margin:0;
        padding:0;
        font: bold 10px/1.5em Verdana;
}

h2 {
        font: bold 12px Verdana, Arial, Helvetica, sans-serif;
        color: #000;
        margin: 0px;
        padding: 0px 0px 0px 15px;
}

/*- Menu Tabs F--------------------------- */

    #tabsF {
      float:left;
      width:100%;
      background:#efefef;
      font-size:93%;
      line-height:normal;
          border-bottom:1px solid #666;
      }
    #tabsF ul {
        margin:0;
        padding:10px 10px 0 50px;
        list-style:none;
      }
    #tabsF li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabsF a {
      float:left;
      background:url("../../imagenes/tableftF.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabsF a span {
      float:left;
      display:block;
      background:url("../../imagenes/tabrightF.gif") no-repeat right top;
      padding:5px 15px 4px 6px;
      color:#666;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \*/
    #tabsF a span {float:none;}
    /* End IE5-Mac hack */
    #tabsF a:hover span {
      color:#FFF;
      }
    #tabsF a:hover {
      background-position:0% -42px;
      }
    #tabsF a:hover span {
      background-position:100% -42px;
      }

        #tabsF #current a {
                background-position:0% -42px;
        }
        #tabsF #current a span {
                background-position:100% -42px;
        }
-->
</style>

<div id="tabsF">
  <ul>
    <?php
include "../../conf/conex.php";
conectarse();
$modulo = $_GET["modulo"];
if ($_GET["id_padre"]) {
    $sql = mysql_query("select * from accion where accion_padre = " . $_GET["id_padre"] . " and mostrar = 1 order by posicion");
} else {
    $sql = mysql_query("select * from accion where id_modulo = " . $modulo . " and accion_padre = 0 and mostrar = 1 order by posicion");
}

while ($bus = mysql_fetch_array($sql)) {
    $sql_permisos = mysql_query("select * from privilegios_acciones where id_accion = " . $bus["id_accion"] . " and id_usuario = " . $cedula . "");
    $num_permisos = mysql_num_rows($sql_permisos);
    if ($num_permisos > 0) {
        ?>
        <li>
        <?php
        if ($bus["url"] == "") {
          ?>
          <a href='<?="?id_accion=" . $bus["id_accion"] . "&modulo=" . $modulo . "&id_padre=" . $bus["id_accion"] . ""?>'
            onmouseover = "window.status='aa'; return true">
          <?php
        } else {
          echo "<a href='../../principal.php?accion=" . $bus["id_accion"] . "&modulo=" . $modulo . "' target = 'main'>";
        }
        ?>

        <span><?php echo $bus["nombre_accion"]; ?></span></a></li>
        <?php
    }
}
if ($_GET["id_padre"] == 0) {
    ?>
      <script>
      window.parent.frames[3].location.href ="../principal/fondo_main.php";
      </script>
    <?
}

if ($_GET["id_padre"]) {
    $sql_padre = mysql_query("select * from accion where id_accion = " . $_GET["id_padre"] . "");
    $bus_padre = mysql_fetch_array($sql_padre);
    ?>
      <script>
      window.parent.frames[3].location.href ="../principal/fondo_main.php";
      </script>
      <?
    ?>
      <li>
        <a href='<?="?id_accion=" . $bus["id_accion"] . "&modulo=" . $modulo . "&id_padre=" . $bus_padre["accion_padre"] . ""?>'>
          <span id="atras">Atras</span>
          </a>
      </li>
      <?
}
?>
    </ul>
</div>
