<?php
	echo('<meta charset="utf-8">');
	require_once('../accounts/config/config.php');
	require_once('../accounts/classes/Login.php');
	$login = new Login();
	if ($login->isUserLoggedIn() == false) {
	    Header('Location: ../entrar.php');
	}
	if ($_SESSION['user_name'] != 'will15') {
		if($_SESSION['user_name'] != 'baronis') {
			die("<br><b><h1>Você não é o William!</h1></b>");
		}
	}
	
	include_once("bd.php");
	$vestibular = $_POST ['vestibular'];
	$ano = $_POST ['ano'];
	$num = $_POST ['num'];
	$enunciado = $_POST ['enunciado'];
	$alt1 = $_POST ['alt1'];
	$alt2 = $_POST ['alt2'];
	$alt3 = $_POST ['alt3'];
	$alt4 = $_POST ['alt4'];
	$alt5 = $_POST ['alt5'];
	$altCorreta = $_POST ['altCorreta'];
	$tema = $_POST ['sub'];
	$comentario = $_POST ['comentario'];
	if(	$vestibular == null || $vestibular == '' 
		|| $ano == null || $ano == '' 
		|| $num == null || $num == '' 
		|| $enunciado == null || $enunciado == '' 
		|| $alt1 == null || $alt1 == '' 
		|| $alt2 == null || $alt2 == '' 
		|| $alt3 == null || $alt3 == '' 
		|| $alt4 == null || $alt4 == '' 
		|| $alt5 == null || $alt5 == '' 
		|| $altCorreta == null || $altCorreta == '' 
		|| $tema == null || $tema == ''){die("<hr><br><b><h1>Complete todos os campos obrigatórios, estagiário!</h1></b>");}
		$a = "(q_vestibular, q_year, q_num, q_content, q_alt_1, q_alt_2, q_alt_3, q_alt_4, q_alt_5, q_correct_alt, q_sub_2_id, q_comment)";
		$b = "('$vestibular', '$ano', '$num', '$enunciado', '$alt1', '$alt2', '$alt3', '$alt4', '$alt5', '$altCorreta', '$tema', '$comentario')";
		$sql="INSERT INTO questions ".$a." VALUES ".$b;
		$ds=mysql_query($sql) or die(mysql_error());
		echo "<h1>Sucesso!</h1>";
		echo ("<script language='JavaScript'>
            var si = alert('Questão adicionada com sucesso ao servidor!')
            window.location = './index.php'
        	</script>");
?>