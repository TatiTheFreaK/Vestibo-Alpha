<?php
	require_once('accounts/config/config.php');
	require_once('accounts/classes/Login.php');
	$login = new Login();
	if ($login->isUserLoggedIn() == true) {
	    Header('Location: accounts/loading.php');
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
		<title>Vestibo</title>
		<link href="css/global-style.css" rel="stylesheet">
		<link href="css/button-style.css" rel="stylesheet">
		<link href="css/facebook-style.css" rel="stylesheet">
	</head>
	<body>
		<script>
		<!--
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '297272677138823',
		      xfbml      : true,
		      version    : 'v2.2'
		    });
		  };
		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/pt_BR/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
	    //-->
		</script>
		<div class="page">
		<div class="like"><div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div></div>
			<div class="header">
				<div class="header-content">
					<div class="header-left">
						<a href="http://vestibo.com.br/" class="header-item"><img src="img/nav-logo-blue.png" class="logo" alt="Vestibo"></a>
					</div>
					<div class="header-right c">
						<a href="cadastrar" class="btn btn-lg btn-default">Cadastrar-se</a>
						<a href="entrar" class="btn btn-lg btn-default">Entrar</a>
					</div>
				</div>
			</div>
			<div class="body">
				<div class="centered">
					<div class="simple-container">
						<div class="content">
							<h1>Vestibo, um novo jeito de se preparar para o vestibular</h1>
			            	<p class="lead">Realize simulados, visualize seu progresso e receba questões diariamente de acordo com as sua necessidade, tudo isso de forma simples e prática para que você tenha o melhor desempenho nos Vestibulares.</p>
		            	</div>
					</div>
					<div class="blue-container">
						<div class="content">
							<h1>Funcionalidades</h1>
			            	<p class="lead">O Vestibo é uma Plataforma que funciona de acordo com sua dificuldade, assim o material certo sempre será apresentado na hora certa a você...</p>
	        				<div class="co">
	    						<img class="img-circle" src="img/divulgacao/Pen.png">
	    						<h2>Treine</h2>
								<p>Com o Vestibo você pode estudar de uma forma prática e eficiente, com os melhores conteúdos didáticos da internet.</p>
	    					</div>
	        				<div class="co">
	    						<img class="img-circle" src="img/divulgacao/Share.png">
	    						<h2>Compartilhe</h2>
								<p>Com a plataforma você também pode se reunir com seus amigos via Facebook e compartilhar seu progresso com eles.</p>
	    					</div>
	        				<div class="co">
	    						<img class="img-circle" src="img/divulgacao/Book.png">
	    						<h2>Aproveite</h2>
	    						<p> Cadastre-se já e comece a estudar firme para os vestibulares e Enem, tudo isso acompanhado de seus amigos.</p>
	    					</div>
		            	</div>
					</div>
					<div class="simple-container">
						<div class="content">
							<h1>O Vestibo oferece tudo isso de forma gratuita e dinâmica a você</h1>
			            	<p class="lead">Cadastre-se já!</p>
			            	<div class="cad-group">
			            		<a href="cadastrar" class="btn btn-lg btn-default">Cadastrar-se</a>
			            		<a href="entrar?fb=true" class="btn btn-lg btn-default">Entrar com o Facebook</a>
							</div>
						</div>
					</div>
					<div class="footer">
						<div class="f-left">Vestibo &copy; 2015.</div>
						<div class="f-right">
							<a href="http://vestibo.com.br/termos">Termos e condições</a>
							<a href="http://vestibo.com.br/quem-somos">Quem somos</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<noscript>
			<div id="popup">
				<div class="popup-box">
					<div class="popup-box-content">
						<h1>Atenção!</h1>
						<img style="padding: 5px; width: 30%; height: 30%;" src="../img/alerta.png">
						<p class="lead">Seu navegador está com o Javascript desabilitado. Para utilizar este site, habilite-o.</p>
					</div>
				</div>
			</div>
		</noscript>
		<script src="lib/jquery-1.10.2.js"></script>
		<script src="in/js/popup.js"></script>
	</body>
</html>

