<?php

if(!isset($_SESSION['iduser'],$_SESSION['usuario'],$_SESSION['email'])){
	if($_POST) {
		include('core/models/class.Acceso.php');
		$acceso = new Acceso();
		$acceso->Login();
		exit;
	} else {
		$template = new Smarty();
		$template->display('public/login.tpl');
	}
}else{
	header('location: ?view=index');
}


?>