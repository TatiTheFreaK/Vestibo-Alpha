<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400' rel='stylesheet' type='text/css'>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="Plataforma de estudos para o Vestibular">
	    <link rel="icon" href="img/favicon.ico">
		<link href="css/alt-style.css" rel="stylesheet">
		<link href="css/button-style.css" rel="stylesheet">
		<link href="css/input-style.css" rel="stylesheet">
		<title>Vestibo: Entrar</title>
	</head>
	<body>
	<div class="page">
		<div class="header">
			<a href="http://vestibo.com.br/"><img src="img/nav-logo-blue.png" class="logo" alt="Vestibo"></a>
		</div>
		<div class="body">
			<form name="entrar" form method="POST" action="entrar">
				<div class="login-container">
					<?php
						require_once('accounts/config/config.php');
						require_once('accounts/libraries/PHPMailer.php');
						require_once('accounts/classes/Login.php');
						$login = new Login();
						if ($login->isUserLoggedIn() == true) {
						    Header('Location: accounts/loading');
						} else {
						    if (isset($login)) {
						        if ($login->errors) {
						            foreach ($login->errors as $error) {
						                echo '<div class="msg-box">'.$error.'</div>';
						            }
						        }
						        if ($login->messages) {
						            foreach ($login->messages as $message) {
						                echo '<div class="msg-box">'.$message.'</div>';
						            }
						        }
						    }
						}
					?>
					<h1 style="color:#003A91; text-align: left;">Entrar</h1>
					<div class="form-group">
						<!--username--><input type="text" name="user_name" class="form-control" id="login_input_username" 
	          			placeholder="Nome de usuário">
	        		</div>
	        		<div class="form-group">
	        			<!--Senha--><input type="password" class="form-control" name="user_password" id="login_input_password" placeholder="Senha">
	        		</div>
	        		<input type="hidden" name="asking" value="true">
	        		<div class="form-group">
	        			<input type="checkbox" id="user_rememberme" name="user_rememberme" value="1" />
						<label for="user_rememberme">Lembrar de mim.</label>
					</div>
	        		<input type="submit" class="btn btn-default" name="login" value="Entrar" />
	        		</input>
	        		<a class="btn btn-default" href="esqueci" 
	        		style="float:right;padding-right:12px;padding-top:8px;">Esqueci minha senha</a>
	        		<div class="box-footer">
	        			<div class="left">
							<a href="http://vestibo.com.br/termos">Termos e condições</a>
						</div>
						<div class="right">
							<a href="http://vestibo.com.br/quem-somos">Quem somos</a>
						</div>
						<div class="centered">
							<p>Vestibo &copy; 2015.</p>
						</div>
	        		</div>
				</div>
			</form>
		</div>
	</div>
	</body>
</html>