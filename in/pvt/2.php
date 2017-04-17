<?php
// PAGE 2: Matérias
if(!isset($_POST['sub']) && isset($_POST['id_res0'])):
	require_once('inc/FormBehaviour.php');
	$form = new FormBehaviour();
	$form->setData();
elseif (!isset($_POST['sub']) && !isset($_POST['id_res0'])): ?>
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
		<h1 style="color: #003A91;">Escolha uma matéria para começar</h1>
		<div class="q-box">
			<div class="q-top-box">
				<div class="top">
					<form id="por" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="01">
					<input type="hidden" name="nQ" value="20">
					<p>Português<a style="float: right;" href="javascript:{}" onclick="document.getElementById('por').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<form id="mat" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="2">
					<input type="hidden" name="nQ" value="20">
					<p>Matemática<a style="float: right;" href="javascript:{}" onclick="document.getElementById('mat').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-top-box">
				<div class="top">
					<form id="his" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="3">
					<input type="hidden" name="nQ" value="20">
					<p>História<a style="float: right;" href="javascript:{}" onclick="document.getElementById('his').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<form id="geo" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="4">
					<input type="hidden" name="nQ" value="20">
					<p>Geografia<a style="float: right;" href="javascript:{}" onclick="document.getElementById('geo').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-top-box">
				<div class="top">
					<form id="qui" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="5">
					<input type="hidden" name="nQ" value="20">
					<p>Química<a style="float: right;" href="javascript:{}" onclick="document.getElementById('qui').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<form id="fis" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="6">
					<input type="hidden" name="nQ" value="20">
					<p>Física<a style="float: right;" href="javascript:{}" onclick="document.getElementById('fis').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-top-box">
				<div class="top">
					<form id="bio" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="7">
					<input type="hidden" name="nQ" value="20">
					<p>Biologia<a style="float: right;" href="javascript:{}" onclick="document.getElementById('bio').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<form id="ing" method="post" action="index?page=2">
					<input type="hidden" name="sub" value="8">
					<input type="hidden" name="nQ" value="20">
					<p>Inglês<a style="float: right;" href="javascript:{}" onclick="document.getElementById('ing').submit(); return false;">Começar!</a></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
elseif (isset($_POST['sub'])):
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
		var actionDir = "?page=2";
	</script>
	<script src="js/form_behaviour_script.js"></script> <?php
else: ?>
<div class="simple-container">
	<div class="content">
		<h1 style="color: #003A91;">Por favor, informe o erro #P2L117 ao desenvolvedor!</h1>
	</div>
</div>
<?php
endif;
?>