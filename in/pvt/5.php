<?php
// PAGE 5: Ajustes
?>
<div class="simple-container">
	<div class="content">
		<div class="q-box">
			<div class="q-top-box">
				<div class="top">
					<div class="profile-wrapper">
						<img class="user-image" <?php echo 'src="'.$_SESSION['user_image'].'"'; ?> alt="1"/>
					</div>
					<div class="profile-wrapper">
						<h1> <?php echo $_SESSION['user_full_name']; ?> </h1>
						<h2> <?php echo $_SESSION['user_name']; ?> </h2>
					</div>
				</div>
			</div>
		</div>
		<div class="q-box">
			<div class="q-top-box">
				<div class="top">
					<p>Foto de perfil<a style="float: right;" href="index?page=5&e=image">Alterar</a></p>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<p>Nome de usuário<a style="float: right;" href="index?page=5&e=username">Alterar</a></p>
				</div>
			</div>
			<div class="q-top-box">
				<div class="top">
					<p>Email<a style="float: right;" href="index?page=5&e=email">Alterar</a></p>
				</div>
			</div>
			<div class="q-alt-box">
				<div class="alt">
					<p>Senha<a style="float: right;" href="index?page=5&e=password">Alterar</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	if(isset($_GET['e'])) {
		if (isset($login)) {
	        if ($login->errors) {
	            foreach ($login->errors as $error) {
	                $alert .= '<div class="popup-alert">'.$error.'</div>';
	            }
	        }
	        if ($login->messages) {
	            foreach ($login->messages as $message) {
	                $alert .= '<div class="popup-alert">'.$message.'</div>';
	            }
	        }
	    }
		echo '<link href="../css/button-style.css" rel="stylesheet"><link href="../css/input-style.css" rel="stylesheet">';
		if ($_GET['e'] == 'username') {
			echo '
			<div id="popup">
				<div class="popup-box">
					<div class="popup-box-content">
						<h1>Alterar nome de usuário</h1>
						'.$alert.'
						<form method="post" class="popup-form" action="index?page=5&e=username" name="user_edit_form_name">
							<div class="form-group">
								<input type="text" name="user_name" pattern="[a-zA-Z0-9]{2,64}" required class="form-control" id="user_name" 
			          			placeholder="Novo nome de usuário">
			        		</div>
							<input type="submit" class="btn btn-default" name="user_edit_submit_name" value="Alterar" />
	        				</input>
						</form>
						<a href="javascript:void(0)" onclick="close_popup();">Cancelar</a>
					</div>
				</div>
			</div>';
		} elseif ($_GET['e'] == 'password') {
			echo '
			<div id="popup">
				<div class="popup-box">
					<div class="popup-box-content">
						<h1>Alterar nome de usuário</h1>
						'.$alert.'
						<form method="post" class="popup-form" action="index?page=5&e=password" name="user_edit_form_password">
							<div class="form-group">
								<input type="password" name="user_password_old" required class="form-control" id="user_password_old" autocomplete="off" placeholder="Senha antiga">
			        		</div>
							<div class="form-group half">
		          				<input type="password" class="form-control piece" id="user_password_new" placeholder="Nova senha" name="user_password_new" required autocomplete="off" />
		          				<input type="password" class="form-control piece" id="user_password_repeat" placeholder="Redigite nova senha" name="user_password_repeat" required autocomplete="off" />
		          			</div>
							<input type="submit" class="btn btn-default" name="user_edit_submit_password" value="Alterar" />
	        				</input>
						</form>
						<a href="javascript:void(0)" onclick="close_popup();">Cancelar</a>
					</div>
				</div>
			</div>';
		} elseif ($_GET['e'] == 'email') {
			echo '
			<div id="popup">
				<div class="popup-box">
					<div class="popup-box-content">
						<h1>Alterar nome de usuário</h1>
						'.$alert.'
						<form method="post" class="popup-form" action="index?page=5&e=email" name="user_edit_form_email">
							<div class="form-group">
								<input type="email" name="user_email" required class="form-control" id="user_email" 
			          			placeholder="Novo endereço de email">
			        		</div>
							<input type="submit" class="btn btn-default" name="user_edit_submit_email" value="Alterar" />
	        				</input>
						</form>
						<a href="javascript:void(0)" onclick="close_popup();">Cancelar</a>
					</div>
				</div>
			</div>';
		} elseif ($_GET['e'] == 'image') {
			echo '
			<div id="popup">
				<div class="popup-box">
					<div class="popup-box-content">
						<h1>Alterar foto de perfil</h1>
						'.$alert.'
						<form method="post" enctype="multipart/form-data" class="popup-form" action="index?page=5&e=image" name="user_change_image">
							<div class="form-group">
								<input type="file" name="new_image" required class="form-control" id="new_image">
			        		</div>
							<input type="submit" class="btn btn-default" name="user_edit_submit_image" value="Alterar" />
	        				</input>
						</form>
						<a href="javascript:void(0)" onclick="close_popup();">Cancelar</a>
					</div>
				</div>
			</div>';
		} else {}
	}
?>