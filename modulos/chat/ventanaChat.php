<?
session_start();
include("../../conf/conex.php");
include("../../funciones/funciones.php");
$conexion = Conectarse();
extract($_POST);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
if($_GET["ua"] == $_SESSION["login"]){
	$login_mostrar = $_GET["ur"];
}else{
	$login_mostrar = $_GET["ua"];
}

$sql_nombre = mysql_query("select * from usuarios where login = '".$login_mostrar."'")or die(mysql_error());
$bus_nombre = mysql_fetch_array($sql_nombre);


?>
<title>Conversando con: <?=$bus_nombre["apellidos"]." ".$bus_nombre["nombres"]?></title>
</head>
<script src="js/ventanaChat_ajax.js"></script>
<script>
function validarCampo(valor){
	if(valor == ""){
		document.getElementById('boton_enviar_comentario').disabled = true;
	}else{
		document.getElementById('boton_enviar_comentario').disabled = false;
	}
}


function recargarVentana(){
	setInterval("consultarMensajes('<?=$_GET["ua"]?>', '<?=$_GET["ur"]?>')",2000);
}

</script>


<body onLoad="document.getElementById('cuadro_texto').focus(), consultarMensajes('<?=$_GET["ua"]?>', '<?=$_GET["ur"]?>'), recargarVentana()" style="height:100%">

<table width="100%" style="height:100%; margin-top:60px" cellpadding="0" cellspacing="0">
    <tr>
        <td style="height:85%; overflow:auto" valign="top">
        &nbsp;<br>
        <div style="border:#999999 solid 1px; width:100%; height:250px; overflow:auto; font-family:Arial, Helvetica, sans-serif; font-size:10px" id="ventana_mensajes">

        </div>
        </td>
    </tr>
    <tr>
        <td style="height:15%">
        
        <form  method="post" name="formulario_mensaje">
        <input type="hidden" id="ua" name="ua" value="<?=$_GET["ua"]?>">
        <input type="hidden" id="ur" name="ur" value="<?=$_GET["ur"]?>">
        <table width="100%">
        <tr>
        <td width="93%"><textarea name="cuadro_texto" id="cuadro_texto" cols="30" rows="3" onKeyUp="validarCampo(this.value)" style="width:100%"></textarea></td>
        <td width="7%"><input type="button" name="boton_enviar_comentario" id="boton_enviar_comentario" value="ENVIAR" disabled style="width:60px; height:50px" onClick="registrarComentario()"></td>
        </tr>
        </table>
        
        </form>
        
        
        </td>
    </tr>
</table>
<div id="estado"></div>
</body>