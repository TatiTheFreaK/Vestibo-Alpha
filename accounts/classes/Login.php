<?php
/**
 * Handles the user registration
 * @author Panique @ Edição --- Lampi
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login-advanced/
 * @license http://opensource.org/licenses/MIT MIT License
 */
class Login {
    private $db_connection = null;
    private $user_id = null;
    private $user_name = "";
    private $user_email = "";
    private $user_is_logged_in = false;
    private $password_reset_link_is_valid  = false;
    private $password_reset_was_successful = false;
    public $errors = array();
    public $messages = array();
    
    /* Sobre variáveis abaixo
    * Nas variáveis da sessao usa-se user_variavel e nos dados do bd se usa cad_variavel, 
    * exceto para o user_name que no bd se chama cad_nick...
    */
    public function __construct() {
        session_start();
        // checa as possíveis ações do login
        // 1. Logout (toda vez que ela dá logout)
        // 2. login via dados da sessão (login via a sessão)
        // 3. login via cookie @@@
        // 4. login via post data ou login comum

        // se ele tentou dar logout
        if (isset($_GET["logout"])) {
            $this->doLogout();
        } 
        // if se o usuário está logado na sessão
        elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {
            $this->loginWithSessionData();
            // usuario tentou mudar user_name
            if (isset($_POST["user_edit_submit_name"])) {
                //editando o user_name...
                $this->editUserName($_POST['user_name']);
            } 
            // user tenta mudar o email
            elseif (isset($_POST["user_edit_submit_email"])) {
                //editando o user_email...
                $this->editUserEmail($_POST['user_email']);
            } 
            // usuario tenta mudar a senha
            elseif (isset($_POST["user_edit_submit_password"])) {
                // editando a senha
                $this->editUserPassword($_POST['user_password_old'], $_POST['user_password_new'], $_POST['user_password_repeat']);
            }
            // usuario tenta trocar a imagem de perfil
            elseif (isset($_POST['user_edit_submit_image'])) {
                // trocando a imagem
                $this->editUserImage($_FILES['new_image']);
            }

        // login via cookie
        } elseif (isset($_COOKIE['rememberme'])) {
            $this->loginWithCookieData();

        // se ele já mandou o form-login
        } elseif (isset($_POST["login"])) {
            if (!isset($_POST['user_rememberme'])) {
                $_POST['user_rememberme'] = null;
            }
            $this->loginWithPostData($_POST['user_name'], $_POST['user_password'], $_POST['user_rememberme']);
        }

        // checando se ele já pediu para redefinir a senha
        if (isset($_POST["request_password_reset"]) && isset($_POST['user_name'])) {
            $this->setPasswordResetDatabaseTokenAndSendMail($_POST['user_name']);
        } elseif (isset($_GET["user_name"]) && isset($_GET["verification_code"])) {
            $this->checkIfEmailVerificationCodeIsValid($_GET["user_name"], $_GET["verification_code"]);
        } elseif (isset($_POST["submit_new_password"])) {
            $this->editNewPassword($_POST['user_name'], $_POST['user_password_reset_hash'], $_POST['user_password_new'], $_POST['user_password_repeat']);
        }
    }
    //checa se a conexão com o bd foi aberta...
    private function databaseConnection() {
        // if se a conexão já existe
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                // conexão com o bd
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } 
            catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        // retornar default
        return false;
    }
    //procura no banco o user_name com as seguintes especificações...
    private function getUserData($user_name) {
        // se a conexão foi aberta...
        if ($this->databaseConnection()) {
            // db query,pegando toda a info do user...
            $query_user = $this->db_connection->prepare('SELECT * FROM cad_users WHERE cad_nick = :user_name');
            $query_user->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $query_user->execute();
            // pegando o resultado agr...
            return $query_user->fetchObject();
        } else {
            return false;
        }
    }
    //LOGS na SESSION_data
    private function loginWithSessionData() {
        $this->user_name = $_SESSION['user_name'];
        $this->user_email = $_SESSION['user_email'];
        //Deixa os status de logado para true...
        $this->user_is_logged_in = true;
    }
    //LOGS NA COOKIE_data
    private function loginWithCookieData() {
        if (isset($_COOKIE['rememberme'])) {
            // extrair dados via cookie
            list ($user_id, $token, $hash) = explode(':', $_COOKIE['rememberme']);
            // checa tipo a validade do cookie
            if ($hash == hash('sha256', $user_id . ':' . $token . COOKIE_SECRET_KEY) && !empty($token)) {
                if ($this->databaseConnection()) {
                    // pegas os dados certos do cookie...
                    $sth = $this->db_connection->prepare("SELECT cad_first_name, cad_last_name, cad_id, cad_nick, cad_email FROM cad_users WHERE cad_id = :user_id
                    AND cad_rememberme_token = :user_rememberme_token AND cad_rememberme_token IS NOT NULL");
                    $sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    $sth->bindValue(':user_rememberme_token', $token, PDO::PARAM_STR);
                    $sth->execute();
                    // get row do resultado
                    $result_row = $sth->fetchObject();

                    if (isset($result_row->user_id)) {
                        // escreve no php sessão
                        $_SESSION['user_id'] = $result_row->cad_id;
                        $_SESSION['user_name'] = $result_row->cad_nick;
                        $_SESSION['user_email'] = $result_row->cad_email;
                        $_SESSION['user_image'] = $result_row->cad_image;
                        $_SESSION['user_full_name'] = $result_row->cad_first_name." ".$result_row->cad_last_name;
                        if (!$_SESSION['user_image']) {
                            $_SESSION['user_image'] = HTTP_DEFAULT_IMAGE_PATH;
                        }
                        $_SESSION['user_logged_in'] = 1;

                        // declara user_id
                        $this->user_id = $result_row->cad_id;
                        $this->user_name = $result_row->cad_nick;
                        $this->user_email = $result_row->cad_email;
                        $this->user_is_logged_in = true;
                        $this->newRememberMeCookie();
                        return true;
                    }
                }
            }
            // o cookie foi usado mas não é válido...
            $this->deleteRememberMeCookie();
            $this->errors[] = MESSAGE_COOKIE_INVALID;
        }
        return false;
    }
    //LOGIN com POST
    private function loginWithPostData($user_name, $user_password, $user_rememberme) {
        if (empty($user_name)) {
            $this->errors[] = MESSAGE_USERNAME_EMPTY;
        } 
        else if (empty($user_password)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } 
        else {
            // usuario loga com user_name or email e senha
            // se ele não digitou seu email correto, tenta pelo user_name
            if (!filter_var($user_name, FILTER_VALIDATE_EMAIL)) {
                // query, pega todas as infos do user
                $result_row = $this->getUserData(trim($user_name));

            } 
            else if ($this->databaseConnection()) {
                //query....
                $query_user = $this->db_connection->prepare('SELECT * FROM cad_users WHERE cad_email = :user_email');
                $query_user->bindValue(':user_email', trim($user_name), PDO::PARAM_STR);
                $query_user->execute();
                $result_row = $query_user->fetchObject();
            }
            // if se o usu nao existe
            if (! isset($result_row->cad_id)) {
                $this->errors[] = MESSAGE_LOGIN_FAILED;
            } else if (($result_row->cad_failed_logins >= 3) && ($result_row->cad_last_failed_login > (time() - 30))) {
                $this->errors[] = MESSAGE_PASSWORD_WRONG_3_TIMES;
            } 
            else if (! password_verify($user_password, $result_row->cad_password_hash)) {
                $sth = $this->db_connection->prepare('UPDATE cad_users '
                        . 'SET cad_failed_logins = cad_failed_logins+1, cad_last_failed_login = :user_last_failed_login '
                        . 'WHERE cad_nick = :user_name OR cad_email = :user_name');
                $sth->execute(array(':user_name' => $user_name, ':user_last_failed_login' => time()));
                $this->errors[] = MESSAGE_PASSWORD_WRONG;
            } 
            else if ($result_row->cad_active != 1) {
                $this->errors[] = MESSAGE_ACCOUNT_NOT_ACTIVATED;
            } 
            else {
                $_SESSION['user_id'] = $result_row->cad_id;
                $_SESSION['user_name'] = $result_row->cad_nick;
                $_SESSION['user_email'] = $result_row->cad_email;
                $_SESSION['user_image'] = $result_row->cad_image;
                $_SESSION['user_full_name'] = $result_row->cad_first_name." ".$result_row->cad_last_name;
                if (!$_SESSION['user_image']) {
                    $_SESSION['user_image'] = HTTP_DEFAULT_IMAGE_PATH;
                }
                $_SESSION['user_logged_in'] = 1;
                $this->user_id = $result_row->cad_id;
                $this->user_name = $result_row->cad_nick;
                $this->user_email = $result_row->cad_email;
                $this->user_is_logged_in = true;
                $sth = $this->db_connection->prepare('UPDATE cad_users '
                        . 'SET cad_failed_logins = 0, cad_last_failed_login = NULL '
                        . 'WHERE cad_id = :user_id AND cad_failed_logins != 0');
                $sth->execute(array(':user_id' => $result_row->cad_id));
                //parte do checkbox remenber-me
                if (isset($user_rememberme)) {
                    $this->newRememberMeCookie();
                } else {
                    $this->deleteRememberMeCookie();
                }
                //HASHHHH
                if (defined('HASH_COST_FACTOR')) {
                    if (password_needs_rehash($result_row->cad_password_hash, PASSWORD_DEFAULT, array('cost' => HASH_COST_FACTOR))) {
                        $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT, array('cost' => HASH_COST_FACTOR));
                        $query_update = $this->db_connection->prepare('UPDATE cad_users SET cad_password_hash = :user_password_hash WHERE cad_id = :user_id');
                        $query_update->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
                        $query_update->bindValue(':user_id', $result_row->cad_id, PDO::PARAM_INT);
                        $query_update->execute();

                        if ($query_update->rowCount() == 0) {
                        } else {}
                    }
                }
            }
        }
    }
    //COOKIE NO REMENBER_ME
    private function newRememberMeCookie() {
        if ($this->databaseConnection()) {
            $random_token_string = hash('sha256', mt_rand());
            $sth = $this->db_connection->prepare("UPDATE cad_users SET cad_rememberme_token = :user_rememberme_token WHERE cad_id = :user_id");
            $sth->execute(array(':user_rememberme_token' => $random_token_string, ':user_id' => $_SESSION['user_id']));
            $cookie_string_first_part = $_SESSION['user_id'] . ':' . $random_token_string;
            $cookie_string_hash = hash('sha256', $cookie_string_first_part . COOKIE_SECRET_KEY);
            $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;
            setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME, "/", COOKIE_DOMAIN);
        }
    }
    //Deleta o remnber caso o usuário peça...
    private function deleteRememberMeCookie() {
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare("UPDATE cad_users SET cad_rememberme_token = NULL WHERE cad_id = :user_id");
            $sth->execute(array(':user_id' => $_SESSION['user_id']));
        }
        setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
    }
    //LOGOUT RESTAURANDO A SESSAO
    public function doLogout() {
        $this->deleteRememberMeCookie();

        $_SESSION = array();
        session_destroy();

        $this->user_is_logged_in = false;
        $this->messages[] = MESSAGE_LOGGED_OUT;
    }
    public function isUserLoggedIn() {
        return $this->user_is_logged_in;
    }
    public function editUserName($user_name) {
        //editar usuario...
        $user_name = substr(trim($user_name), 0, 64);
        if (!empty($user_name) && $user_name == $_SESSION['user_name']) {
            $this->errors[] = MESSAGE_USERNAME_SAME_LIKE_OLD_ONE;
            //usuario não pode estar em branco
        } elseif (empty($user_name) || !preg_match("/^(?=.{2,64}$)[a-zA-Z][a-zA-Z0-9]*(?: [a-zA-Z0-9]+)*$/", $user_name)) {
            $this->errors[] = MESSAGE_USERNAME_INVALID;
        } else {
            $result_row = $this->getUserData($user_name);
            if (isset($result_row->cad_id)) {
                $this->errors[] = MESSAGE_USERNAME_EXISTS;
            } else {
                $query_edit_user_name = $this->db_connection->prepare('UPDATE cad_users SET cad_nick = :user_name WHERE cad_id = :user_id');
                $query_edit_user_name->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                $query_edit_user_name->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $query_edit_user_name->execute();

                if ($query_edit_user_name->rowCount()) {
                    $_SESSION['user_name'] = $user_name;
                    $this->messages[] = MESSAGE_USERNAME_CHANGED_SUCCESSFULLY . $user_name;
                } else {
                    $this->errors[] = MESSAGE_USERNAME_CHANGE_FAILED;
                }
            }
        }
    }
    //edita o email do user
    public function editUserEmail($user_email) {
        $user_email = substr(trim($user_email), 0, 64);
        if (!empty($user_email) && $user_email == $_SESSION["user_email"]) {
            $this->errors[] = MESSAGE_EMAIL_SAME_LIKE_OLD_ONE;
        } elseif (empty($user_email) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = MESSAGE_EMAIL_INVALID;
        } else if ($this->databaseConnection()) {
            // checa se o email já existe
            $query_user = $this->db_connection->prepare('SELECT * FROM cad_users WHERE cad_email = :user_email');
            $query_user->bindValue(':user_email', $user_email, PDO::PARAM_STR);
            $query_user->execute();
            $result_row = $query_user->fetchObject();
            // se o email existe
            if (isset($result_row->cad_id)) {
                $this->errors[] = MESSAGE_EMAIL_ALREADY_EXISTS;
            } else {
                $query_edit_user_email = $this->db_connection->prepare('UPDATE cad_users SET cad_email = :user_email WHERE cad_id = :user_id');
                $query_edit_user_email->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                $query_edit_user_email->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $query_edit_user_email->execute();

                if ($query_edit_user_email->rowCount()) {
                    $_SESSION['user_email'] = $user_email;
                    $this->messages[] = MESSAGE_EMAIL_CHANGED_SUCCESSFULLY . $user_email;
                } else {
                    $this->errors[] = MESSAGE_EMAIL_CHANGE_FAILED;
                }
            }
        }
    }
    //editar a senha do usuario
    public function editUserPassword($user_password_old, $user_password_new, $user_password_repeat) {
        if (empty($user_password_new) || empty($user_password_repeat) || empty($user_password_old)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } elseif ($user_password_new !== $user_password_repeat) {
            $this->errors[] = MESSAGE_PASSWORD_BAD_CONFIRM;
        // se tem no mínimo 6 chsar
        } elseif (strlen($user_password_new) < 6) {
            $this->errors[] = MESSAGE_PASSWORD_TOO_SHORT;
        } else {
            $result_row = $this->getUserData($_SESSION['user_name']);
            if (isset($result_row->cad_password_hash)) {
                if (password_verify($user_password_old, $result_row->cad_password_hash)) {
                    $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
                    $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
                    $query_update = $this->db_connection->prepare('UPDATE cad_users SET cad_password_hash = :user_password_hash WHERE cad_id = :user_id');
                    $query_update->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
                    $query_update->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $query_update->execute();
                    if ($query_update->rowCount()) {
                        $this->messages[] = MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY;
                    } else {
                        $this->errors[] = MESSAGE_PASSWORD_CHANGE_FAILED;
                    }
                } else {
                    $this->errors[] = MESSAGE_OLD_PASSWORD_WRONG;
                }
            } else {
                $this->errors[] = MESSAGE_USER_DOES_NOT_EXIST;
            }
        }
    }
    public function setPasswordResetDatabaseTokenAndSendMail($user_name) {
        $user_name = trim($user_name);
        if (empty($user_name)) {
            $this->errors[] = MESSAGE_USERNAME_EMPTY;
        } 
        else {
            $temporary_timestamp = time();
            $user_password_reset_hash = sha1(uniqid(mt_rand(), true));
            $result_row = $this->getUserData($user_name);
            if (isset($result_row->cad_id)) {
                $query_update = $this->db_connection->prepare('UPDATE cad_users SET cad_password_reset_hash = :user_password_reset_hash,
                                                               cad_password_reset_timestamp = :user_password_reset_timestamp
                                                               WHERE cad_nick = :user_nick');
                $query_update->bindValue(':user_password_reset_hash', $user_password_reset_hash, PDO::PARAM_STR);
                $query_update->bindValue(':user_password_reset_timestamp', $temporary_timestamp, PDO::PARAM_INT);
                $query_update->bindValue(':user_nick', $user_name, PDO::PARAM_STR);
                $query_update->execute();
                if ($query_update->rowCount() == 1) {
                    $this->sendPasswordResetMail($user_name, $result_row->cad_email, $user_password_reset_hash);
                    return true;
                } else {
                    $this->errors[] = MESSAGE_DATABASE_ERROR;
                }
            } else {
                $this->errors[] = MESSAGE_USER_DOES_NOT_EXIST;
            }
        }
        return false;
    }

    //manda o email de redefinição de senha
    public function sendPasswordResetMail($user_name, $user_email, $user_password_reset_hash) {
        $mail = new PHPMailer;
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Port = EMAIL_SMTP_PORT;
        }
        else {
            $mail->IsMail();
        }
        $mail->From = EMAIL_PASSWORDRESET_FROM;
        $mail->FromName = EMAIL_PASSWORDRESET_FROM_NAME;
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_PASSWORDRESET_SUBJECT;
        $link = EMAIL_PASSWORDRESET_URL.'?user_name='.urlencode($user_name).'&verification_code='.urlencode($user_password_reset_hash);
        $mail->Body = EMAIL_PASSWORDRESET_CONTENT . ' ' . $link;

        if(!$mail->Send()) {
            $this->errors[] = MESSAGE_PASSWORD_RESET_MAIL_FAILED . $mail->ErrorInfo;
            return false;
        } else {
            $this->messages[] = MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT;
            return true;
        }
    }
    public function checkIfEmailVerificationCodeIsValid($user_name, $verification_code) {
        $user_name = trim($user_name);
        if (empty($user_name) || empty($verification_code)) {
            $this->errors[] = MESSAGE_LINK_PARAMETER_EMPTY;
        } else {
            $result_row = $this->getUserData($user_name);
            if (isset($result_row->cad_id) && $result_row->cad_password_reset_hash == $verification_code) {
                $timestamp_one_hour_ago = time() - 3600;
                if ($result_row->cad_password_reset_timestamp > $timestamp_one_hour_ago) {
                    $this->password_reset_link_is_valid = true;
                } else {
                    $this->errors[] = MESSAGE_RESET_LINK_HAS_EXPIRED;
                }
            } else {
                $this->errors[] = MESSAGE_USER_DOES_NOT_EXIST;
            }
        }
    }
    //editando a nova senha pelo e-mail...
    public function editNewPassword($user_name, $user_password_reset_hash, $user_password_new, $user_password_repeat) {
        $user_name = trim($user_name);
        if (empty($user_name) || empty($user_password_reset_hash) || empty($user_password_new) || empty($user_password_repeat)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } 
        else if ($user_password_new !== $user_password_repeat) {
            $this->errors[] = MESSAGE_PASSWORD_BAD_CONFIRM;
        } 
        else if (strlen($user_password_new) < 6) {
            $this->errors[] = MESSAGE_PASSWORD_TOO_SHORT;
        } 
        else if ($this->databaseConnection()) {
            $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
            $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

            $query_update = $this->db_connection->prepare('UPDATE cad_users SET cad_password_hash = :user_password_hash,
                                                           cad_password_reset_hash = NULL, cad_password_reset_timestamp = NULL
                                                           WHERE cad_nick = :user_name AND cad_password_reset_hash = :user_password_reset_hash');
            $query_update->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
            $query_update->bindValue(':user_password_reset_hash', $user_password_reset_hash, PDO::PARAM_STR);
            $query_update->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $query_update->execute();
            if ($query_update->rowCount() == 1) {
                $this->password_reset_was_successful = true;
                $this->messages[] = MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY;
            } else {
                $this->errors[] = MESSAGE_PASSWORD_CHANGE_FAILED;
            }
        }
    }
    public function passwordResetLinkIsValid() {
        return $this->password_reset_link_is_valid;
    }
    public function passwordResetWasSuccessful() {
        return $this->password_reset_was_successful;
    }
    public function getUsername() {
        return $this->user_name;
    }
    //opção para a imagem pegando ela pelo 'gravatar'
    public function editUserImage($image) {
        if ($this->databaseConnection()) {
            if (empty($image)) {
                $this->errors[] = MESSAGE_IMAGE_INVALID;
            } else {
                // processa a imagem
                $max_file_size = 1024*500;
                $valid_exts = array('jpeg', 'jpg', 'png');
                $s = 140;
                if($image['size'] < $max_file_size) {
                    // get file extension
                    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, $valid_exts)) {
                        // resize image
                        $fileName = md5(uniqid(time())).'.'.$ext;
                        $path = LOCAL_IMAGE_PATH.$fileName;
                        $dbPath = HTTP_IMAGE_PATH.$fileName;
                        if ($this->deleteUsersCurrentImage()) {
                            $user_image_url = $this->setIimage($path, $ext, $image, $s, $s);
                            if ($user_image_url) {
                                $query_edit = $this->db_connection->prepare('UPDATE cad_users SET cad_image = :user_image_url WHERE cad_nick = :user_name LIMIT 1;');
                                $query_edit->bindValue(':user_image_url', $dbPath, PDO::PARAM_STR);
                                $query_edit->bindValue(':user_name', $_SESSION['user_name'], PDO::PARAM_STR);
                                $query_edit->execute();
                                if ($query_edit->rowCount() == 1) {
                                    $_SESSION['user_image'] = $user_image_url;
                                    $this->messages[] = MESSAGE_IMAGE_CHANGED_SUCCESSFULLY;
                                } else {
                                    $this->errors[] = MESSAGE_IMAGE_CHANGE_FAILED;
                                }
                            } else {
                                $this->errors[] = MESSAGE_PROCESSING_IMAGE_FAILURE; 
                            }
                        } else {
                            $this->errors[] = MESSAGE_OLDIMAGE_DELETE_FAILED;
                        }
                    } else {
                        $this->errors[] = MESSAGE_UNSUPPORTED_IMAGE;
                    }
                } else{
                    $this->errors[] = MESSAGE_IMAGE_TOO_BIG;
                }
            }
        }
    }

    public function setIimage($path, $endPath, $image, $width, $height){
        // Get original image x y
        list($w, $h) = getimagesize($image['tmp_name']);
        // calculate new image size with ratio
        $ratio = max($width/$w, $height/$h);
        $h = ceil($height / $ratio);
        $x = ($w - $width / $ratio) / 2;
        $w = ceil($width / $ratio);
        // read binary data from image file
        $imgString = file_get_contents($image['tmp_name']);
        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
        // Save image
        if ($endPath == 'jpeg' || $endPath == 'jpg' || $endPath == 'jpe') {
            imagejpeg($tmp, $path, 100);
        } elseif ($endPath == 'png') {
            imagepng($tmp, $path, 0);
        } else {
            return false;
        }
        imagedestroy($image);
        imagedestroy($tmp);
        return $path;
    }

    public function deleteUsersCurrentImage() {
        $result_row = $this->getUserData($_SESSION['user_name']);
        if ($result_row->cad_image) {
            if ($result_row->cad_image != "http://vestibo.com.br/img/user-image.png") {
                $fileName = explode(DIRECTORY_SEPARATOR, $result_row->cad_image);
                $path = LOCAL_IMAGE_PATH.DIRECTORY_SEPARATOR.end($fileName);
                if(unlink($path)) {
                    return true;
                }
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}