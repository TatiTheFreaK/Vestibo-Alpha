<?php
// PAGE 3: Simulados
if (!isset($_POST['vest']) && !isset($_POST['year']) && !isset($_POST['id_res0'])): ?>
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
		<h1 style="color: #003A91;">Escolha um vestibular para começar</h1>
		<div class="q-box">
			<div class="q-top-box">
				<div class="top">
					<h1 style="color: #003A91;">UNESP</h1>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<form id="unesp2015" method="post" action="index?page=3">
					<input type="hidden" name="vest" value="unesp">
					<input type="hidden" name="year" value="2015">
					<p>2015<a style="float: right;" href="javascript:{}" onclick="document.getElementById('unesp2015').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
elseif(!isset($_POST['vest']) && !isset($_POST['year']) && isset($_POST['id_res0'])):
	require_once('inc/FormBehaviour.php');
	$form = new FormBehaviour();
	$form->setData();
elseif (isset($_POST['vest']) && isset($_POST['year'])):
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
		var actionDir = "?page=3";
	</script>
	<script src="js/form_behaviour_script.js"></script> <?php
else: ?>
<div class="simple-container">
	<div class="content">
		<h1 style="color: #003A91;">Por favor, informe o erro #P3L57 ao desenvolvedor!</h1>
	</div>
</div>
<?php endif;
?>