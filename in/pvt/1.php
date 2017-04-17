<?php
// PAGE 1: Exercícios 
if(!isset($_POST['nQ']) && isset($_POST['id_res0'])):
	require_once('inc/FormBehaviour.php');
	$form = new FormBehaviour();
	$form->setData();
elseif (!isset($_POST['nQ']) && !isset($_POST['id_res0'])): ?>
<div id="popup">
	<div class="popup-box">
		<div class="popup-box-content">
			<h1>Não se esqueça!</h1>
			<img style="padding: 5px; width: 30%; height: 30%;" src="../img/paper-pen.png">
			<p class="lead">Tenha em mãos papel e caneta para a resolução dos exercícios.</p>
			<a href="javascript:void(0)" onclick="close_popup();" class="btn btn-lg btn-default">Prosseguir</a>
		</div>
	</div>
</div>
<div class="simple-container">
	<div class="content">
		<h1 style="color: #003A91;">Escolha a quantidade de questões desejadas</h1>
		<div class="q-box">
			<div class="q-top-box">
				<div class="top">
					<form id="por" method="post" action="index?page=1">
						<div class="form-group">
							<input class="form-control" type="text" name="nQ" maxlength="2" placeholder="Quantidade de questões" value="">
						</div><div class="form-group">
							<input class="btn btn-default" type="submit" name="register" value="Começar"/>
		        		</div>
		        	</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
elseif (isset($_POST['nQ'])):
	$v = $_SESSION['user_name'].time();
	$_SESSION['curTask'] = sha1($v);
	require_once('inc/SortQuestions.php');
	require_once('inc/FormBehaviour.php');
	$sort = new SortQuestions();
	$form = new FormBehaviour();
	$x = $sort->getProd();
	$_SESSION['prod'] = $x;
	$r = count($x);
	$form->setData();
	$form->printQuestions($x); ?>
	<script type="text/javascript">
		var qtdquestions = <?php echo $r; ?>;
		var campoquestao = <?php echo $r; ?>;
		var actionDir = "?page=1";
	</script>
	<script src="js/form_behaviour_script.js"></script> <?php
else: ?>
<div class="simple-container">
	<div class="content">
		<h1 style="color: #003A91;">Por favor, informe o erro #P1L59 ao desenvolvedor!</h1>
	</div>
</div>
<?php
endif;
?>