<?php

unset($_SESSION['iduser'],$_SESSION['usuario'],$_SESSION['email']);
session_destroy();
header('location: ?view=index');

?>