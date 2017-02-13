<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>SINTEGESP - Sistema Integrado de Gestión Pública</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" >
    <link href="css/datatables.min.css" rel="stylesheet" type="text/css" >
    <link href="css/ajustes.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrapValidator.css" rel="stylesheet" type="text/css">
  </head>

<body>



<?php

$originalDate = "21-03-2016";
$newDate = date("Y-m-d", strtotime($originalDate));


echo $newDate;

?>
</br>
<div class="list-group">
    <a href="#" class="list-group-item active">
        <span class="glyphicon glyphicon-picture"></span> Imagenes <span class="badge">15</span>
    </a>
    <a href="#" class="list-group-item">
        <span class="glyphicon glyphicon-folder-open"></span> Archivos <span class="badge">80</span>
    </a>
    <a href="#" class="list-group-item">
        <span class="glyphicon glyphicon-music"></span> Audios <span class="badge">10</span>
    </a>
    <a href="#" class="list-group-item">
        <span class="glyphicon glyphicon-film"></span> Videos <span class="badge">8</span>
    </a>
    <a href="#" class="list-group-item">
        <span class="glyphicon glyphicon-comment"></span> Mensajes <span class="badge">23</span>
    </a>
</div>




</body>

<script type="text/javascript" src="js/function.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/es.js"></script>
<script type="text/javascript" src="js/transition.js"></script>
<script type="text/javascript" src="js/collapse.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="js/datatables.min.js"></script>
<script type="text/javascript" src="js/bootstrapValidator.min.js"></script>




<script>
    $(document).ready(function() {
        $('#notEmptyForm').bootstrapValidator({
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                fullName: {
                    validators: {
                        notEmpty: {
                            message: 'The full name is required'
                        }
                    }
                }
            }
        });
    });
</script>
