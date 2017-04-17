<?php
	require_once('accounts/config/config.php');
	require_once('accounts/classes/Login.php');
	$login = new Login();
	if ($login->isUserLoggedIn() == true) {
	    Header('Location: accounts/loading');
	}
?>
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
		<title>Vestibo: Cadastrar</title>
	</head>
	<body>
		<div class="page">
			<div class="header">
				<a href="http://vestibo.com.br/"><img src="img/nav-logo-blue.png" class="logo" alt="Vestibo"></a>
			</div>
			<div class="body">
				<form method="post" action="cadastrar" name="registerform">
					<div class="cadastro-container">
						<?php
							require_once("accounts/libraries/class.phpmailer.php");
							require_once("accounts/libraries/class.smtp.php");
							require_once('accounts/classes/Registration.php');
							$registration = new Registration();
							if (isset($registration)) {
							    if ($registration->errors) {
							        foreach ($registration->errors as $error) {
							            echo '<div class="msg-box">'.$error.'</div>';
							        }
							    }
							    if ($registration->messages) {
							        foreach ($registration->messages as $message) {
							            echo '<div class="msg-box">'.$message.'</div>';
							        }
							    }
							}
						?>
						<h1 style="color:#003A91; text-align: left;">Cadastro</h1>
						<div class="form-group">
	          				<input type="text" class="form-control" id="login_input_username" pattern="[a-zA-Z0-9]{2,64}" placeholder="Nome de usuário" name="user_name" required />
	          			</div>
	          			<div class="form-group half">
	          				<input type="text" class="form-control piece" id="login_input_nome" pattern="[a-zA-Z0-9]{2,64}" placeholder="Nome" name="user_nome" required />
	          				<input type="text" class="form-control piece" id="login_input_sobnome" pattern="[a-zA-Z0-9]{2,64}" placeholder="Sobrenome" name="user_sobnome" required />
	          			</div>
	          			<div class="form-group">
	          				<input type="date" class="form-control" id="login_input_user_nasc" placeholder="Data de nascimento" name="user_nasc" required />
	          			</div>
	          			<div class="form-group">
	          				<input type="email" class="form-control" id="login_input_email" placeholder="Email" name="user_email" required />
	          			</div>
	          			<div class="form-group half">
	          				<input type="password" class="form-control piece" id="login_input_password_new" placeholder="Senha" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
	          				<input type="password" class="form-control piece" id="login_input_password_repeat" placeholder="Redigite sua senha" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
	          			</div>
    					<div class="form-group">
    						<img src="accounts/tools/showCaptcha.php" alt="!!!" />
    						<label><?php echo WORDING_REGISTRATION_CAPTCHA; ?></label>
    						<input class="form-control" type="text" name="captcha" placeholder="Digite os caracteres acima" required />
    					</div>
		        		<input class="btn btn-default" type="submit" name="register" value="Cadastrar"/>
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