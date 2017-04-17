<?php
define("DB_HOST", "mysql.hostinger.com.br");
define("DB_NAME", "u395938104_1");
define("DB_USER", "u395938104_adm");
define("DB_PASS", "admpass");
/**
 * Configuração para os cookies
 * COOKIE_RUNTIME: quanto tempo o cookie é válido? 1209600 segundos = 2 semanas
 * COOKIE_DOMAIN: o domínio onde o cookie irá "trabalhar"
 * COOKIE_SECRET_KEY: uma chave secreta do cookie para deixar o app mais seguro, se for mudado todo o cookie é apagado...
 */
define("COOKIE_RUNTIME", 1209600);
define("COOKIE_DOMAIN", ".127.0.0.1");
define("COOKIE_SECRET_KEY", "1gp@TMPS{+$78sfpMJFe-92s");
/**
 * Configuração para o email:
 * define("EMAIL_USE_SMTP", true); se ele usa smtp ou não (hostinger não suporta...)
 * define("EMAIL_SMTP_HOST", "ssl://smtp.gmail.com"); o smtp host...
 * define("EMAIL_SMTP_AUTH", true);
 * define("EMAIL_SMTP_USERNAME", "xxxxxxxxxx@gmail.com"); email
 * define("EMAIL_SMTP_PASSWORD", "xxxxxxxxxxxxxxxxxxxx"); a senha
 * define("EMAIL_SMTP_PORT", 465); a porta usada
 * define("EMAIL_SMTP_ENCRYPTION", "ssl"); e a encriptação do email ssl é dafault em todos...
 */
define("EMAIL_USE_SMTP", true);
define("EMAIL_SMTP_HOST", "smtp.zoho.com");
define("EMAIL_SMTP_AUTH", true);
define("EMAIL_SMTP_USERNAME", "naoresponda@vestibo.com.br");
define("EMAIL_SMTP_PASSWORD", "teste123");
define("EMAIL_SMTP_PORT", 587);
define("EMAIL_SMTP_ENCRYPTION", "tls");
/**
 * Configuração para o email de redefinição...
 */
define("EMAIL_PASSWORDRESET_URL", "http://vestibo.com.br/esqueci");
define("EMAIL_PASSWORDRESET_FROM", "naoresponda@vestibo.com.br");
define("EMAIL_PASSWORDRESET_FROM_NAME", "Vestibo");
define("EMAIL_PASSWORDRESET_SUBJECT", "Redefinir sua senha");
define("EMAIL_PASSWORDRESET_CONTENT", "Clique aqui para redefinir sua senha:");
/**
 * Configuração para o email de ativação
 */
define("EMAIL_VERIFICATION_URL", "http://vestibo.com.br/cadastrar");
define("EMAIL_VERIFICATION_FROM", "naoresponda@vestibo.com.br");
define("EMAIL_VERIFICATION_FROM_NAME", "Vestibo");
define("EMAIL_VERIFICATION_SUBJECT", "Ativação para Vestibo");
define("EMAIL_VERIFICATION_CONTENT", "Clique aqui para ativar sua conta:");
define("HASH_COST_FACTOR", "10");
/**
 * Caminho para a gravação das imagens de perfil dos usuários
 */
define('HTTP_IMAGE_PATH', 'http://vestibo.com.br/img/users/');
define('LOCAL_IMAGE_PATH', '../img/users/');
define('HTTP_DEFAULT_IMAGE_PATH', 'http://vestibo.com.br/img/user-image.png');
/**
 * Definições para as classes Login e Registration
 */
define('MESSAGE_ACCOUNT_NOT_ACTIVATED', 'Sua conta ainda não foi ativada.<br>Favor clicar no link de confirmação enviado por email.<br><a href="../entrar.php">Ir para o Login.</a>');
define("MESSAGE_CAPTCHA_WRONG", "Captcha incorreto!");
define("MESSAGE_COOKIE_INVALID", "Cookie inválido");
define("MESSAGE_DATABASE_ERROR", "Erro de conexão com o bd.");
define("MESSAGE_EMAIL_ALREADY_EXISTS", "Este e-mail já está registrado. Tente usar a \"recuperação de senha\".");
define("MESSAGE_EMAIL_CHANGE_FAILED", "Desculpe, a alteração de e-mail falhou.");
define("MESSAGE_EMAIL_CHANGED_SUCCESSFULLY", "Seu e-mail foi alterado com sucesso. Novo e-mail é ");
define("MESSAGE_EMAIL_EMPTY", "Email não pode ficar em branco");
define("MESSAGE_EMAIL_INVALID", "Seu e-mail possui um formato inválido");
define("MESSAGE_EMAIL_SAME_LIKE_OLD_ONE", "Desculpe, este email é o mesmo do atual. Por favor informe outro email.");
define("MESSAGE_EMAIL_TOO_LONG", "Email não pode ter mais de 64 caracteres.");
define("MESSAGE_LINK_PARAMETER_EMPTY", "Link vazio.");
define("MESSAGE_LOGGED_OUT", "Você saiu..");
define("MESSAGE_LOGIN_FAILED", "Login falhou.");
define("MESSAGE_NASCIMENTO_EMPTY", "Digite a data de seu nascimento...");
define("MESSAGE_NOME_EMPTY", "Digite seu Nome...");
define("MESSAGE_OLDIMAGE_DELETE_FAILED", "Erro ao remover a imagem atual.");
define("MESSAGE_OLD_PASSWORD_WRONG", "Sua senha antiga está incorreta.");
define("MESSAGE_PASSWORD_BAD_CONFIRM", "As senhas informadas não coincidem");
define("MESSAGE_PASSWORD_CHANGE_FAILED", "Desculpe, a alteração de senha falhou.");
define("MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY", "Senha alterada com sucesso!");
define("MESSAGE_PASSWORD_EMPTY", "Senha está em branco");
define("MESSAGE_PASSWORD_RESET_MAIL_FAILED", "Email de recuperação de senha não foi enviado! Erro: ");
define("MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT", "Email de recuperação de senha enviado!");
define("MESSAGE_PASSWORD_TOO_SHORT", "Tamanho mínimo da senha é de 6 caracteres");
define("MESSAGE_PASSWORD_WRONG", "Senha incorreta. Tente novamente.");
define("MESSAGE_PASSWORD_WRONG_3_TIMES", "Você inseriu uma senha incorreta 3 vezes ou mais. Favor aguardar 30 segundos e tente novamente.");
define("MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL", "Desculpe, nenhum id encontrado...");
define("MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL", 'Ativação bem sucedida!<br>Você pode entrar agora!<a href="entrar.php">Ir para o Login.</a>');
define("MESSAGE_REGISTRATION_FAILED", "Desculpe, seu registro falhou. Volte e tente novamente.");
define("MESSAGE_RESET_LINK_HAS_EXPIRED", "Este link de recuperação expirou. Use o link sempre em menos de uma hora.");
define("MESSAGE_SOBRENOME_EMPTY", "Digite seu Sobrenome...");
define("MESSAGE_VERIFICATION_MAIL_ERROR", "Desculpe, não foi possível enviar um email de verificação. Sua conta não foi criada.");
define("MESSAGE_VERIFICATION_MAIL_NOT_SENT", "Email de verificação não foi enviado! Erro: ");
define("MESSAGE_VERIFICATION_MAIL_SENT", "Sua conta foi criada e enviamos um email.<br>Clique no link de verificação deste email.");
define("MESSAGE_USER_DOES_NOT_EXIST", "Este usuário não existe");
define("MESSAGE_USERNAME_BAD_LENGTH", "Usuário não pode conter menos que 2 caracteres ou mais que 64");
define("MESSAGE_USERNAME_CHANGE_FAILED", "Desculpe, a alteração do nome de usuário falhou");
define("MESSAGE_USERNAME_CHANGED_SUCCESSFULLY", "Seu nome de usuário foi alterado com sucesso. Novo nome de usuário é ");
define("MESSAGE_USERNAME_EMPTY", "Campo nome de usuário está vazio");
define("MESSAGE_USERNAME_EXISTS", "Desculpe, este nome de usuário já foi utilizado. Escolha outro.");
define("MESSAGE_USERNAME_INVALID", "Nome de usuário fora do padrão: somente a-Z e números são permitidos, 2 a 64 caracteres");
define("MESSAGE_USERNAME_SAME_LIKE_OLD_ONE", "Desculpe, o nome de usuário é o mesmo atual. Escolha outro.");
define("MESSAGE_IMAGE_TOO_BIG", "A imagem é muito grande.<br>Por favor, escolha uma imagem de até 500kb.");
define("MESSAGE_UNSUPPORTED_IMAGE", "Desculpe, mas este formato de imagem não é suportado");
define("MESSAGE_PROCESSING_IMAGE_FAILURE", "Erro ao processar a imagem.");
define("MESSAGE_IMAGE_CHANGE_FAILED", "Desculpe, mas não foi possível enviar a imagem para o banco de dados.");
define("MESSAGE_IMAGE_CHANGED_SUCCESSFULLY", "Imagem alterada com sucesso.");
define("WORDING_REGISTRATION_CAPTCHA", "Digite os caracteres");
/**
 * Definições para as classes SortQuestions e FormBehaviour
 */
define("WARNING_QUESTIONS_EXCEPTION", '<b>Aviso: Numero de questoes pedidas excede o numero de questoes disponiveis no servidor!</b><br>');
define("ERROR_DB", '<b>Problema ao acessar o Banco de Dados!</b><br>');
define("ERROR_DB_CONN", '<b>Problema de conexão com o Banco de Dados!</b><br>');
define("ERROR_GET_NUMBER", '<b>Quantas questoes?</b><br>');
?>