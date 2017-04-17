<?php
class Front {
	private $conn 				= null;
	private $totalQuestions 	= null;
	private $totalRight 		= null;
	private $totalWrong 		= null;
	private $kd 				= null;
	private $totalForms 		= null;

	public function __construct() {
		if($this->databaseConnection()) {
			$this->totalQuestions = $this->getTotalQuestions();
			$this->setRightWrongTotal();
			if ($this->totalRight && $this->totalWrong) {
				$this->kd = $this->totalRight/$this->totalWrong;
			} else {
				$this->kd = 1;
			}
			
			$this->printPageContent();
		}
	}

	// Realiza a conexão com o Banco de Dados
	private function databaseConnection() {
		if ($this->conn != null) {
            return true;
        } else {
            try {
                $this->conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
               	$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               	return true;
            } catch (PDOException $e) {
                die ('ERROR_DB_CONN');
                return false;
            }
        }
	}

	// Obtém o numero total de questões feitas pelo usuário
	private function getTotalQuestions() {
		$stmt = $this->conn->prepare('SELECT num_q FROM form_log WHERE user_id = ?;');
		$stmt->bindValue(1, $_SESSION['user_id']);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if ($rows) {
			foreach ($rows as $v) {
				$s[] = $v['num_q'];
			}
			$this->totalForms = count($rows);
			$n = array_sum($s);
		    return $n;
		} else {
			$this->totalForms = 0;
			return 0;
		}
	}

	// Obtém o numero total de questões corretas e incorretas feitas pelo usuário
	private function setRightWrongTotal() {
		$stmt = $this->conn->prepare('SELECT data FROM form_log WHERE user_id = ?;');
		$stmt->bindValue(1, $_SESSION['user_id']);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$r = 0; $w = 0;
		if($rows) {
			foreach ($rows as $v) {
				$s1 = explode("_", $v['data']);
				if($s1[0] != ""){
					$sE = explode(";", $s1[0]);
					$w += count($sE);
				}
				if($s1[1] != "") {
					$sC = explode(";", $s1[1]);
					$r += count($sC);
				}
			}
		}
		$this->totalRight = $r;
		$this->totalWrong = $w;
	}

	// Marcações HTML
	private function printPageContent() { ?>
	<div class="simple-container">
		<div class="content">
			<h1 style="color: #003A91;">Seu progresso</h1>
			<div class="q-box">
				<div class="q-top-box">
					<div class="top">
						<div class="collun-wrapper">
							<p>Número de questões realizadas</p>
							<h2><?php echo $this->totalQuestions; ?></h2>
						</div>
						<div class="collun-wrapper">
							<p>Número de provas realizadas</p>
							<h2><?php echo $this->totalForms; ?></h2>
						</div>
					</div>
				</div>
				<div class="q-alt-box">
					<div class="alt">
						<div class="collun-wrapper">
							<p>Total de questões corretas</p>
							<h2><?php echo $this->totalRight; ?></h2>
						</div>
						<div class="collun-wrapper">
							<p>Total de questões incorretas</p>
							<h2><?php echo $this->totalWrong; ?></h2>
						</div>	
					</div>
				</div>
				<div class="q-top-box">
					<div class="top">
						<center>
							<p class="cwp">C.I.</p>
							<h2 class="cwh2"><?php echo $this->kd; ?></h2>
							<p>C.I. é o valor obtido da divisão das questões corretas pelas incorretas.</p>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php }
} ?>