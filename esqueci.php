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
			<form name="entrar" form method="POST" action="esqueci">
				<div class="login-container">
					<?php
						require_once('accounts/config/config.php');
						require_once("accounts/libraries/class.phpmailer.php");
						require_once("accounts/libraries/class.smtp.php");
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

						if ($login->passwordResetWasSuccessful() == true && $login->passwordResetLinkIsValid() != true) {
						    Header('Location: entrar');
						}

						if ($login->passwordResetLinkIsValid() == true) { 
					?>
					<h1 style="color:#003A91; text-align: left;">Redefinição de senha</h1>
				    <input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
				    <input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>' />
					<div class="form-group">
						<input type="password" class="form-control" name="user_password_new" id="user_password_new" pattern=".{6,}" required autocomplete="off" placeholder="Nova senha">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="user_password_repeat" id="user_password_repeat" pattern=".{6,}" required autocomplete="off" placeholder="Redigite nova senha">
					</div>
					<input type="submit" class="btn btn-default" name="submit_new_password" value="Redefinir" />
					</input>
					<?php } else { ?>
					<h1 style="color:#003A91; text-align: left;">Redefinição de senha</h1>
					<div class="form-group">
						<input type="text" name="user_name" class="form-control" id="user_name" required placeholder="Nome de usuário">
					</div>
					<input type="submit" class="btn btn-default" name="request_password_reset" value="Próximo" />
					</input>
					<?php } ?>
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