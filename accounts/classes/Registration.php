<?php
/**
 * Handles the user registration
 * @author Panique @ Edição disto --- Lampi
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login-advanced/
 * @license http://opensource.org/licenses/MIT MIT License
 */
class Registration
{
    private $db_connection            = null;
    public  $registration_successful  = false;
    public  $verification_successful  = false;
    public  $errors                   = array();
    public  $messages                 = array();
    /** Sobre variáveis daqui para baixo
    * Nas @var(variáveis) da sessao se usa user_variavel e nos dados do bd se usa cad_variavel, 
    * exceto para o user_name que no bd se chama cad_nick...
    */
    public function __construct() {
        session_start();
        if (isset($_POST["register"])) {
            $this->registerNewUser($_POST['user_name'], $_POST['user_email'], $_POST['user_password_new'], $_POST['user_password_repeat'], $_POST["captcha"],$_POST["user_nome"],$_POST["user_sobnome"],$_POST["user_nasc"]);
        } else if (isset($_GET["id"]) && isset($_GET["verification_code"])) {
            $this->verifyNewUser($_GET["id"], $_GET["verification_code"]);
        }
    }
    //Configuração no banco de dados
    private function databaseConnection() {
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }
    private function registerNewUser($user_name, $user_email, $user_password, $user_password_repeat, $captcha, $user_nome, $user_sobnome, $user_nasc) {
        //Começo do tratamento de erros dessa função
        $user_name  = trim($user_name);
        $user_email = trim($user_email);
        if (strtolower($captcha) != strtolower($_SESSION['captcha'])) {
            $this->errors[] = MESSAGE_CAPTCHA_WRONG;
        } 
        elseif (empty($user_name)) {
            $this->errors[] = MESSAGE_USERNAME_EMPTY;
        } 
        elseif (empty($user_password) || empty($user_password_repeat)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } 
        elseif ($user_password !== $user_password_repeat) {
            $this->errors[] = MESSAGE_PASSWORD_BAD_CONFIRM;
        } 
        elseif (strlen($user_password) < 6) {
            $this->errors[] = MESSAGE_PASSWORD_TOO_SHORT;
        } 
        elseif (strlen($user_name) > 64 || strlen($user_name) < 2) {
            $this->errors[] = MESSAGE_USERNAME_BAD_LENGTH;
        } 
        elseif (!preg_match('/^[a-z\d]{2,64}$/i', $user_name)) {
            $this->errors[] = MESSAGE_USERNAME_INVALID;
        } 
        elseif (empty($user_email)) {
            $this->errors[] = MESSAGE_EMAIL_EMPTY;
        } 
        elseif (empty($user_nome)) {
            $this->errors[] = MESSAGE_NOME_EMPTY;
        }
        elseif (empty($user_sobnome)) {
            $this->errors[] = MESSAGE_SOBRENOME_EMPTY;
        }
        elseif (empty($user_nasc)) {
            $this->errors[] = MESSAGE_NASCIMENTO_EMPTY;
        }
        elseif (strlen($user_email) > 64) {
            $this->errors[] = MESSAGE_EMAIL_TOO_LONG;
        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = MESSAGE_EMAIL_INVALID;
        } 
        //Término do tratamento de erros dessa função
        else if ($this->databaseConnection()) {
            $query_check_user_name = $this->db_connection->prepare('SELECT cad_nick, cad_email FROM cad_users WHERE cad_nick=:user_name OR cad_email=:user_email');
            $query_check_user_name->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $query_check_user_name->bindValue(':user_email', $user_email, PDO::PARAM_STR);
            $query_check_user_name->execute();
            $result = $query_check_user_name->fetchAll();
            if (count($result) > 0) {
                for ($i = 0; $i < count($result); $i++) {
                    $this->errors[] = ($result[$i]['cad_nick'] == $user_name) ? MESSAGE_USERNAME_EXISTS : MESSAGE_EMAIL_ALREADY_EXISTS;
                }
            } 
            else {
                $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
                // gera o hash da senha
                $user_activation_hash = sha1(uniqid(mt_rand(), true));

                // grava os usuarios no bd
                $query_new_user_insert = $this->db_connection->prepare('INSERT INTO cad_users (cad_nick, cad_password_hash, cad_email, cad_first_name, cad_last_name, cad_birth, cad_activation_hash, cad_registration_ip, cad_registration_datetime) 
                VALUES(:user_name, :user_password_hash, :user_email, :user_nome, :user_sobnome, :user_nasc, :user_activation_hash, :user_registration_ip, now())');
                $query_new_user_insert->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_nome', $user_nome, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_sobnome', $user_sobnome, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_nasc', $user_nasc, PDO::PARAM_INT);
                $query_new_user_insert->bindValue(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_registration_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $query_new_user_insert->execute();
                $user_id = $this->db_connection->lastInsertId();

                if ($query_new_user_insert) {
                    if ($this->sendVerificationEmail($user_id, $user_email, $user_activation_hash)) {
                        $this->messages[] = MESSAGE_VERIFICATION_MAIL_SENT;
                        $this->registration_successful = true;
                    } else {
                        $query_delete_user = $this->db_connection->prepare('DELETE FROM cad_users WHERE cad_id=:user_id');
                        $query_delete_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                        $query_delete_user->execute();

                        $this->errors[] = MESSAGE_VERIFICATION_MAIL_ERROR;
                    }
                } 
                else {
                    $this->errors[] = MESSAGE_REGISTRATION_FAILED;
                }
            }
        }
    }
    //Toda a configuração no config...
    public function sendVerificationEmail($user_id, $user_email, $user_activation_hash) {
        $mail = new PHPMailer;

        if (EMAIL_USE_SMTP) {
            $mail->IsSMTP();
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            $mail->Port = EMAIL_SMTP_PORT;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Priority = 1;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = '8bit';
            $mail->WordWrap = 900;
        } 
        else {
            $mail->IsMail();
        }
        $mail->From = EMAIL_VERIFICATION_FROM;
        $mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_VERIFICATION_SUBJECT;
        $link = EMAIL_VERIFICATION_URL.'?id='.urlencode($user_id).'&verification_code='.urlencode($user_activation_hash);
        // verificação de email
        $mail->Body = EMAIL_VERIFICATION_CONTENT.' '.$link;

        if(!$mail->Send()) {
            $this->errors[] = MESSAGE_VERIFICATION_MAIL_NOT_SENT . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }


        ///////////

require_once ( 'class.phpmailer.php' ); // Add the path as appropriate
  $Mail = new PHPMailer();
  $Mail->IsSMTP(); // Use SMTP
  $Mail->Host        = "smtp.gmail.com"; // Sets SMTP server
  $Mail->SMTPDebug   = 2; // 2 to enable SMTP debug information
  $Mail->SMTPAuth    = TRUE; // enable SMTP authentication
  $Mail->SMTPSecure  = "tls"; //Secure conection
  $Mail->Port        = 587; // set the SMTP port
  $Mail->Username    = 'MyGmail@gmail.com'; // SMTP account username
  $Mail->Password    = 'MyGmailPassword'; // SMTP account password
  $Mail->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
  $Mail->CharSet     = 'UTF-8';
  $Mail->Encoding    = '8bit';
  $Mail->Subject     = 'Test Email Using Gmail';
  $Mail->ContentType = 'text/html; charset=utf-8\r\n';
  $Mail->From        = 'MyGmail@gmail.com';
  $Mail->FromName    = 'GMail Test';
  $Mail->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line

  $Mail->AddAddress( $ToEmail ); // To:
  $Mail->isHTML( TRUE );
  $Mail->Body    = $MessageHTML;
  $Mail->AltBody = $MessageTEXT;
  $Mail->Send();
  $Mail->SmtpClose();

  if ( $Mail->IsError() ) { 
    echo "ERROR<br /><br />";
  }
  else {
    echo "OK<br /><br />";
  }

        ///////////
    }
    public function verifyNewUser($user_id, $user_activation_hash) {
        // se a conexão esta aberta
        if ($this->databaseConnection()) {
            // update os dados do user
            $query_update_user = $this->db_connection->prepare('UPDATE cad_users SET cad_active = 1, cad_activation_hash = NULL WHERE cad_id = :user_id AND cad_activation_hash = :user_activation_hash');
            $query_update_user->bindValue(':user_id', intval(trim($user_id)), PDO::PARAM_INT);
            $query_update_user->bindValue(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);
            $query_update_user->execute();

            if ($query_update_user->rowCount() > 0) {
                $this->verification_successful = true;
                $this->messages[] = MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL;
            } else {
                $this->errors[] = MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL;
            }
        }
    }
}
