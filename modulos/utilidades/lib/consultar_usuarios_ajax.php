<?
include("../../../conf/conex.php");
conectarse();
extract($_POST);

	if($_POST["valor"] != ""){
		if($_POST["valor"] == "*"){
			$sql = mysql_query("select * from usuarios where status != 'e' and login <> 'administrador' order by nombres");
			$sql_eliminados = mysql_query("select * from usuarios where status = 'e' and login <> 'administrador' order by nombres");
		}else{
			$sql = mysql_query("select * from usuarios where (nombres like '%".$_POST["valor"]."%' or apellidos like '%".$_POST["valor"]."%') and status != 'e' and login <> 'administrador' order by nombres");
			$sql_eliminados = mysql_query("select * from usuarios where (nombres like '%".$_POST["valor"]."%' or apellidos like '%".$_POST["valor"]."%') and status = 'e' and login <> 'administrador' order by nombres");
		}
		?>
        <strong><label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#3366CC'>Usuarios Activos</label></strong><br />
        <?
			while($bus=mysql_fetch_array($sql)){
				?>
                <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;'> <a href='principal.php?modulo=9&accion=62&id_usuario=<?=$bus["cedula"]?>'><b>-</b> <?=$bus["nombres"]." ".$bus["apellidos"]?></a></label><br />
				<?
			}
		$num = mysql_num_rows($sql);
			if($num == 0){
			?>
            <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000'>No hay usuarios con este nombre</label>
			<?
			}
		
			?>
            <br /><strong><label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000'>Usuarios Inactivos</label></strong><br />
            <?
		$num_eliminados = mysql_num_rows($sql_eliminados);
			if($num_eliminados == 0){
				?>
                <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#990000'>No hay usuarios con este nombre</label>
                <?		
			}else{
				while($bus_eliminados = mysql_fetch_array($sql_eliminados)){
					?>
                    <label style='font-family:Verdana, Arial, Helvetica, sans-serif; font-size:9px;'> <a href='principal.php?modulo=9&accion=62&id_usuario=<?=$bus_eliminados["cedula"]?>&inactivo=1'><b>-</b> <?=$bus_eliminados["nombres"]." ".$bus_eliminados["apellidos"]?></a></label><br />
					<?
				}
			}
	}
?>
