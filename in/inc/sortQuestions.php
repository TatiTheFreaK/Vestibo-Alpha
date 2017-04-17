<?php
// Sorteador de Questões (r6)
class sortQuestions {
	// Variável que armazena a conexão
	private $conn 	= null;
	private $prod 	= null;
	// Esta função é iniciada junto com a classe
	public function __construct() {
		if($this->databaseConnection()) {
			if(!isset($_POST['nQ'])) {
				if (!isset($_POST['vest']) && !isset($_POST['year'])) {
					die(ERROR_GET_NUMBER);
				} else {
					$this->prod = $this->sortByVestibular($_POST['vest'], $_POST['year']);
				}
			} else {
				$nQ = $_POST['nQ'];
				if(isset($_POST['sub'])){
					$this->prod = $this->sortBySubject($nQ);
				} else {
					if(isset($_POST['byPerformace'])) {
						$stmt = $this->conn->prepare('SELECT sub_id FROM perf WHERE user_id = ?;');
						$stmt->bindValue(1, $_SESSION['user_id']);
						$stmt->execute();
						$rows = $stmt->fetchAll();
					    if(count($rows) == 0){
					    	$this->prod = $this->sortByRandom($nQ);
						} else {
							$this->prod = $this->sortByPerformace($nQ, $subArray);
						}
					} else {
						(int) $x = $nQ;
						$this->prod = $this->sortByRandom($x);
					}
				}
			}
			
		} else {
			echo ERROR_DB;
		}
	}

	// Realiza a conexão com o Banco de Dados
	private function databaseConnection() {
		if ($this->conn != null) {
            return true;
        } else {
            try {
                $this->conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                die (ERROR_DB_CONN);
                return false;
            }
        }
	}

	public function getProd() {
		$_SESSION['prod'] = $this->prod;
		return $this->prod;
	}

	// Sorteia questôes caso o usuário não possua histórico de seu desempenho
	private function sortByRandom($nQ) {
		$stmt = $this->conn->prepare('SELECT * FROM questions;');
		$stmt->execute();
		$rows = $stmt->fetchAll();
	    if ($nQ > count($rows)) {
	    	echo WARNING_QUESTIONS_EXCEPTION;
	    }
	    shuffle($rows);
	    for ($i=0; $i < $nQ ; $i++) {
	    	$prod[] = $rows[$i];
	    }
	    return $prod;
	}

	// Sorteia questôes caso o usuário não possua histórico de seu desempenho
	private function sortByVestibular($v, $y) {
		$stmt = $this->conn->prepare('SELECT * FROM questions WHERE q_vestibular = ? AND q_year = ? ORDER BY q_num;');
		$stmt->bindValue(1, $v);
		$stmt->bindValue(2, $y);
		$stmt->execute();
		$rows = $stmt->fetchAll();
	    return $rows;
	}

	// Sorteia questões baseando-se no histórico de desempenho do usuário
	private function sortByPerformace($nQ, $subArray) {
	    $stmt = $this->conn->prepare('SELECT q_content FROM questions WHERE q_sub_2_id IN ('.implode(", ", $subArray).');');
	   	$stmt->execute();
	   	$rows = $stmt->fetchAll();
		if ($nQ > count($rows)) {
	    	echo WARNING_QUESTIONS_EXCEPTION;
	    }
	    shuffle($rows);
	    for ($i=0; $i < $nQ; $i++) { 
	    	$fRes[] = $rows[$i];
	    }
		return $fRes;
	}

	// Sorteia questões a partir de uma matéria específica
	private function sortBySubject($nQ) {
		$sub = (int) $_POST['sub'];
		$nQ = (int) $nQ;
	    $stmt = $this->conn->prepare('SELECT * FROM questions WHERE q_sub_2_id = ?;');
	    $stmt->bindValue(1, $sub);
	   	$stmt->execute();
		$result = $stmt->fetchAll();
		if ($nQ > count($result)) {
	    	echo WARNING_QUESTIONS_EXCEPTION;
	    }
	    for ($i=0; $i < $nQ; $i++) {
	    	$fRes[] = $result[$i];
	    }
		shuffle($fRes);
		return $fRes;
	}
}
?>