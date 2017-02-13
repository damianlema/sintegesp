<?php /* Smarty version 3.1.27, created on 2016-07-19 16:45:15
         compiled from "C:\xampp\htdocs\curso_php_avanzado\styles\templates\overall\nav.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:23792578e9863a2e2c6_58362143%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd042db4a24a69310b7c73aa9a729521af9261a3' => 
    array (
      0 => 'C:\\xampp\\htdocs\\curso_php_avanzado\\styles\\templates\\overall\\nav.tpl',
      1 => 1468962911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23792578e9863a2e2c6_58362143',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_578e98640c0328_31331311',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_578e98640c0328_31331311')) {
function content_578e98640c0328_31331311 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '23792578e9863a2e2c6_58362143';
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brandaaa</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <?php if ((isset($_GET['view']) && $_GET['view'] == 'index') || !isset($_GET['view'])) {?>
          <li class="active">
        <?php } else { ?>
          <li>
        <?php }?>
          <a href="?view=index">Inicio</a></li>
        <li><a href="#">Link</a></li>
        <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu 1 <b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li><a href="../gestion_desarrollo/acceso.php">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">One more separated link</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
      </ul>
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <?php if (isset($_SESSION['usuario'])) {?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $_SESSION['usuario'];?>
<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="?view=perfil&id_users=<?php echo $_SESSION['iduser'];?>
">Perfil</a></li>
              <li><a href="?view=cuenta">Cuenta</a></li>
              <li><a href="?view=logout">Cerrar sesión</a></li>
            </ul>
          </li>
        <?php } else { ?>
            <?php if (isset($_GET['view']) && $_GET['view'] == 'login') {?>
              <li class="active">
            <?php } else { ?>
              <li>
            <?php }?>
              <a href="?view=login">Login</a></li>
            <?php if (isset($_GET['view']) && $_GET['view'] == 'registro') {?>
              <li class="active">
            <?php } else { ?>
              <li>
            <?php }?>
              <a href="?view=registro">Registrarme</a></li>
        <?php }?>
      </ul>
    </div>
  </div>
</nav><?php }
}
?>