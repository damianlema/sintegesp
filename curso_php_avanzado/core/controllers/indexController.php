<?php

$template = new Smarty();
$type = isset($_GET['type']) ? $_GET['type'] : null;

$db = new Conexion();

switch($type){
	case 'tops':
		echo 'tops';
	break;
	case '1':
		echo '1';
	break;
	case '2':
		echo '2';
	break;
	case '3':
		echo '3';
	break;
	default:
		$sql = $db->query("SELECT * FROM post ORDER BY idpost DESC");
		$usersql = "SELECT user FROM users WHERE idusers=?";
		$prepare_sql = $db->prepare($usersql);
		$prepare_sql->bind_param('i',$id);

		while($registros = $db->recorrer($sql)){

			$id = $registros['duenio'];
			$prepare_sql->execute();
			$prepare_sql->bind_result($autor);
			$prepare_sql->fetch();

			$posts[] = array(
				'id'        => $registros['idpost'],
				'titulo'    => $registros['titulo'],
				'contenido' => $registros['contenido'],
				'duenio'    => $autor,
				'id_duenio' => $registros['duenio'],
				'puntos'    => $registros['puntos']
			);
		}
		$template->assign('posts', $posts);
	break;
}

$template->display('home/index.tpl');
?>