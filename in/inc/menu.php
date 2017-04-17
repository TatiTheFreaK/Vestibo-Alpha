<?php
	switch ($_GET['page']) {
		case '0':
			echo '<div id="m" class="esc">
				<div class="menu-container">
					<a href="?page=0" class="active">início</a>
					<a href="?page=1">exercícios</a>
					<a href="?page=2">matérias</a>
					<a href="?page=3">simulados</a>
					<a href="?page=5">ajustes</a>
				</div>
			</div>'; break;
		case '1':
			echo '<div id="m" class="esc">
				<div class="menu-container">
					<a href="?page=0">início</a>
					<a href="?page=1" class="active">exercícios</a>
					<a href="?page=2">matérias</a>
					<a href="?page=3">simulados</a>
					<a href="?page=5">ajustes</a>
				</div>
			</div>'; break;
		case '2':
			echo '<div id="m" class="esc">
				<div class="menu-container">
					<a href="?page=0">início</a>
					<a href="?page=1">exercícios</a>
					<a href="?page=2" class="active">matérias</a>
					<a href="?page=3">simulados</a>
					<a href="?page=5">ajustes</a>
				</div>
			</div>'; break;
		case '3':
			echo '<div id="m" class="esc">
				<div class="menu-container">
					<a href="?page=0">início</a>
					<a href="?page=1">exercícios</a>
					<a href="?page=2">matérias</a>
					<a href="?page=3" class="active">simulados</a>
					<a href="?page=5">ajustes</a>
				</div>
			</div>'; break;
		case '4': header("location:?page=0"); break;
		case '5':
			echo '<div id="m" class="esc">
				<div class="menu-container">
					<a href="?page=0">início</a>
					<a href="?page=1">exercícios</a>
					<a href="?page=2">matérias</a>
					<a href="?page=3">simulados</a>
					<a href="?page=5" class="active">ajustes</a>
				</div>
			</div>'; break;
		default: header("location:?page=0"); break;}
?>