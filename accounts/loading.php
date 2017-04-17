<?php
	require_once('config/config.php');
	require_once('classes/Login.php');

	$login = new Login();

	if ($login->isUserLoggedIn() == false) {
	    Header('Location: ../entrar');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Redirecionando</title>
	<META http-equiv="refresh" content="1;URL=http://vestibo.com.br/in">
</head>
<body>
<style type="text/css">
html{
	height: 100%;
	background-color: #F5F5F5;
}
.box{
	width: 50px;
	height: 50px;
	position: fixed;
	top: 50%;
	left: 50%;
	margin-left: -25px;
	margin-top: -25px;
	border: 7px solid #003A91;
	border-radius: 100%;
	border-top-color: transparent;
	animation: giro 500ms infinite linear;
	-webkit-animation: giro 500ms infinite linear;
	-moz-animation: giro 500ms infinite linear;
	-o-animation: giro 500ms infinite linear;
}
@keyframes giro {
	100% {
		transform:rotate(360deg);
		-webkit-transform:rotate(360deg);
		-moz-transform:rotate(360deg);
		-o-transform:rotate(360deg);
	}
}
@-webkit-keyframes giro {
	100% {
		transform:rotate(360deg);
		-webkit-transform:rotate(360deg);
	}
}
@-moz-keyframes giro {
	100% {
		transform:rotate(360deg);
		-moz-transform:rotate(360deg);
	}
}
@-o-keyframes giro {
	100% {
		transform:rotate(360deg);
		-o-transform:rotate(360deg);
	}
}
</style>
	<div class="box"></div>
</body>
</html>