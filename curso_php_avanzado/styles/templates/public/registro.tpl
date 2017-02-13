{include 'overall/header.tpl'}

<body>

	{include 'overall/nav.tpl'}

	<div class="container" style="margin-top: 30px;">
		<center>
			<div id='_AJAX_'>
			</div>
			<div class="form-signin" style="width: 500px;">
		        <h2 class="form-signin-heading">Registro</h2>
		        <label for="inputEmail" class="sr-only">Usuario</label>
		        <input type="text" id='usuario' class="form-control" placeholder="Introduce tu usuario" required autofocus>
		        <label for="inputPassword" class="sr-only">Clave</label>
		        <input type="password" id="clave" class="form-control" placeholder="Ingresa tu contraseña" required>
		        <label for="inputPassword" class="sr-only">Email</label>
		        <input type="email" id="email" class="form-control" placeholder="Ingresa tu correo electronico" required>
		        <br />
		        <button class="btn btn-primary btn-block" id="send_request" type="button">Registrar usuario</button>
		    </div>
	    </center>
	</div>

{include 'overall/footer.tpl'}

	<script>
		window.onload = function() {
			document.getElementById('send_request').onclick = function(){
				var connect, usuario, clave, email, form, result;
				usuario = document.getElementById('usuario').value;
				clave = document.getElementById('clave').value;
				email = document.getElementById('email').value;

				if(usuario != '' && clave != '' && email != ''){
					form = 'usuario='+ usuario + '&clave=' + clave + '&email=' + email;
					connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
					connect.onreadystatechange = function(){
						if(connect.readyState == 4 && connect.status == 200) {
							if(connect.responseText == 1) {
								result = '<div class="alert alert-dismissible alert-success" style="width: 500px;">';
							  	result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
							  	result += '<strong>Registro completado </strong> Bienvenido';
								result += '</div>';
								location.href = '?view=index';
								document.getElementById('_AJAX_').innerHTML = result;
							}else if(connect.responseText == 2) {
								console.log(connect.responseText);
								result = '<div class="alert alert-dismissible alert-danger" style="width: 500px;">';
							  	result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
							  	result += '<strong>ERROR: </strong> Usuario ya registrado';
								result += '</div>';
								document.getElementById('_AJAX_').innerHTML = result;
							}else if(connect.responseText == 3) {
								console.log(connect.responseText);
								result = '<div class="alert alert-dismissible alert-danger" style="width: 500px;">';
							  	result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
							  	result += '<strong>ERROR: </strong> El email ya existe';
								result += '</div>';
								document.getElementById('_AJAX_').innerHTML = result;
							}
						} else if(connect.readyState != 4) {
							result = '<div class="alert alert-dismissible alert-warning" style="width: 500px;">';
						  	result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
						  	result += 'Procesando....';
							result += '</div>';
							document.getElementById('_AJAX_').innerHTML = result;
						}
					}
					connect.open('POST','?view=registro',true);
					connect.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
					connect.send(form);
				}else{
					result = '<div class="alert alert-dismissible alert-danger" style="width: 500px;">';
				  	result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				  	result += '<strong>ERROR: </strong> Los datos no pueden estar vacios';
					result += '</div>';
					document.getElementById('_AJAX_').innerHTML = result;
				}
			}
		}
	</script>
  </body>
</html>