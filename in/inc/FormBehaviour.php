<?php
class FormBehaviour {
	// Variável que armazena a conexão
	private $conn 					= null;
	// Variável que armazena as questões sorteadas
	private $prod					= null;
	private $maxQuestionsPerPage 	= 10;
	//variveis necessarias para mostrar resultado
	private $correctQ				= null;
	private $wrongQ					= null;
	// Variaáveis de armazenamento pós-correção
	private $correctAnswers			= null; // Array
	private $incorrectAnswers 		= null; // Array
	private $incorrectAnswersHTML 	= null;
	private $correctionString 		= null;

	// Esta função é iniciada junto com a classe
	public function __construct() {
		if(!$this->databaseConnection()) {
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
               	$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               	return true;
            } catch (PDOException $e) {
                die (ERROR_DB_CONN);
                return false;
            }
        }
	}

	// Corrige os exercícios recebidos
	private function correction($questao, $resposta) {
		$q = explode(",", $questao);
		$r = explode(",", $resposta);
		if($this->databaseConnection()) {
			$stmt = $this->conn->prepare('SELECT q_correct_alt FROM questions WHERE q_id IN ('.implode(", ", $q).');');
			$stmt->execute();
			$rows = $stmt->fetchAll();
			$z = count($r)-1;
			$row = $this->fixSequence($rows, $q);
			for($i = 0; $i <= $z; $i++) {
				if(empty($r[$i])) {
					$fqXA[$i] = $q[$i];
					$fqX[$i] = $q[$i]."X";
				} else {
					if ($row[$i] == $r[$i]) {
						$fqCA[$i] = $q[$i];
						$fqC[$i] = $q[$i]."C";
					} elseif ($row[$i] != $r[$i]) {
						$fqEA[$i] = $q[$i];
						$fqE[$i] = $q[$i]."E";
					} else {
						echo "erro na correcao<br/>";
					}
				}
			}
			if (!empty($fqX)) {
				$XcorecaoA = implode(";", $fqXA);
				$Xcorecao = implode(";", $fqX);
			} else {
				$XcorecaoA = "";
				$Xcorecao = "";
			}
			if (!empty($fqC)) {
				$CcorecaoA = implode(";", $fqCA);
				$Ccorecao = implode(";", $fqC);
			} else {
				$CcorecaoA = "";
				$Ccorecao = "";
			}
			if (!empty($fqE)) {
				$EcorecaoA = implode(";", $fqEA);
				$Ecorecao = implode(";", $fqE);
			} else {
				$EcorecaoA = "";
				$Ecorecao = "";
			}
			$Result = $Ecorecao."_".$Ccorecao;
			if ($Xcorecao != null) {
				$Result .= "_".$Xcorecao;
			}
			$this->correctQ = $CcorecaoA;
			$this->wrongQ = $EcorecaoA;
			if ($XcorecaoA != null) {
				$this->wrongQ .= ";".$XcorecaoA;
			}
			$this->correctAnswers = explode(";", $this->correctQ);
			$this->incorrectAnswers = explode(";", $this->wrongQ);
			// Checar se os arrays não são vazios
			if ($this->correctAnswers[0] == "") {
				$this->correctAnswers = 0;
			}
			if ($this->incorrectAnswers[0] == "") {
				$this->incorrectAnswers = 0;
			}
			// Finalização
			$this->correctionString = $Result;
			return true;
		}
	}

	// Mantem a sequencia dos exercícios sorteados
	private function fixSequence($ro, $ques) {
		$sq = $ques;
		sort($sq);
		$z=count($ques);
		$p=count($ro);
		for($i=0;$i<$z;$i++) {
			for($i2=0;$i2<$z;$i2++) {
				if($ques[$i2] == $sq[$i]){ $qs[$i2]=$ro[$i]['q_correct_alt'];}
			}
		}
		return $qs;
	}

	//Realiza o processo de mostragem e correção simultânea
	public function setData() {
		if(isset($_POST['id_res0']) && isset($_SESSION['prod'])) {
			$questions = "";
			$answers = "";
			$a = 0;
			foreach ($_SESSION['prod'] as $z) {
				if($a == 0) {
					$questions .= $z[0];
				} else {
					$questions .= ",".$z[0];
				} $a++;
			} $a = 0;
			foreach ($_POST as $i) {
				if($a == 0){
					$answers.="".$i;
				} else {
					$answers.=",".$i;
				} $a++;
			}
			if ($this->correction($questions, $answers)) {
				$iProd = $this->getIncorrectQuestionsFromDB();
				$this->setIncorrectAnswersHTML($iProd);
				$this->setDBData();
				$this->printResult();
			}
			unset($_SESSION['prod']);
		}
	}

	// Obtem do banco as questoes incorretas
	private function getIncorrectQuestionsFromDB() {
		if($this->incorrectAnswers) {
			$query = 'SELECT * FROM questions WHERE q_id IN ('.implode(", ", $this->incorrectAnswers).');';
		    $stmt = $this->conn->prepare($query);
		   	$stmt->execute();
		   	$rows = $stmt->fetchAll();
			if ($nQ > count($rows)) {
		    	echo WARNING_QUESTIONS_EXCEPTION;
		    }
			return $rows;
		} else {
			return null;
		}
	}

	// Salva os dados no banco
	private function setDBData() {
		// Log
		$x = count($_SESSION['prod']);
        $query_log = $this->conn->prepare('INSERT INTO form_log (user_id, data, num_q) VALUES (:user_id, :data, :num)');
        $query_log->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $query_log->bindValue(':data', $this->correctionString, PDO::PARAM_STR);
        $query_log->bindValue(':num', $x, PDO::PARAM_INT);
        $query_log->execute();
        if (!$query_log) {
            return false;
        }
		// Perf
		if ($this->incorrectAnswers) {
			$stmt = $this->conn->prepare('SELECT q_sub_2_id FROM questions WHERE Q_id IN ('.implode(", ", $this->incorrectAnswers).');');
	   		$stmt->execute();
	   		$rows = $stmt->fetchAll();
   			foreach ($rows as $sub) {
				$query_update = $this->conn->prepare('INSERT INTO perf (perf_user_id, perf_sub) VALUES (:user_id, :sub)');
        	    $query_update->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        	    $query_update->bindValue(':sub', $sub['q_sub_2_id'], PDO::PARAM_INT);
        	    $query_update->execute();
   			}
   		}
		return true;
	}

	// Adiciona as questões ao HTML
	public function printQuestions($prod) {
		$x = count($prod);
		$output = '	<div class="simple-container">
						<div class="content">
							<h1 id="curr_page" style="color: #003A91;">Página 0 de 0.</h1>
							<form action="" method="post" name="FormQuestions">';
		for ($i=0; $i < $x; $i++) {
			$a = $prod[$i];
			$l = "div".$i;
			$output .= '<div class="q-box" id="div'.$i.'">
							<div class="q-top-box">
								<div class="top">
									<p> '.$a[1].' - '.$a[2].' ('.$a[3].')</p><hr>
									<p>'.$a[4].'</p>
								</div>
							</div>
							<div class="q-alt-box">
								<div class="alt">
									<input value="1" type="radio" id="'.$a[0].'1" name="id_res'.$i.'" onclick="feito(\''.$l.'\');"></input><label for="'.$a[0].'1">'.$a[5].'</label>
									<input value="2" type="radio" id="'.$a[0].'2" name="id_res'.$i.'" onclick="feito(\''.$l.'\');"></input><label for="'.$a[0].'2">'.$a[6].'</label>
									<input value="3" type="radio" id="'.$a[0].'3" name="id_res'.$i.'" onclick="feito(\''.$l.'\');"></input><label for="'.$a[0].'3">'.$a[7].'</label>
									<input value="4" type="radio" id="'.$a[0].'4" name="id_res'.$i.'" onclick="feito(\''.$l.'\');"></input><label for="'.$a[0].'4">'.$a[8].'</label>
									<input value="5" type="radio" id="'.$a[0].'5" name="id_res'.$i.'" onclick="feito(\''.$l.'\');"></input><label for="'.$a[0].'5">'.$a[9].'</label>
								</div>
							</div>';
			if(!empty($a[12])) {
				$output .= '<div class="q-top-box">
								<div class="top">
									<p>'.$a[12].'</p>
								</div>
							</div>
						</div>';
			} else {
				$output .= '</div>';
			}
		}
		$output .= '<input type="button" name="questions_form_submit" onclick="btFaz();" class="btn btn-lg btn-default" value="Próxima">
				</form>
			</div>
		</div>';
		echo $output;
	}

	// Mostra o resultado do formulário
	public function printResult() {
		if ($this->correctAnswers) {
			$cA = count($this->correctAnswers);
		} else { $cA = 0; }
		if ($this->incorrectAnswers) {
			$iA = count($this->incorrectAnswers);
		} else { $iA = 0; }
		?>
		<div class="simple-container">
			<div class="content">
				<h1 style="color: #003A91;">Resultado</h1>
				<div class="q-box">
					<div class="q-top-box">
						<div id="v" class="top">
							<div class="progress" id="progress"></div>
						</div>
					</div>
					<div class="q-alt-box">
						<div class="alt">
							<div class="collun-wrapper">
								<p>Acertos</p>
								<h2><?php echo $cA ?></h2>
							</div>
							<div class="collun-wrapper">
								<p>Erros</p>
								<h2><?php echo $iA; ?></h2>
							</div>
						</div>
					</div>
					<div class="line" id="line"></div>
					<div class="q-top-box">
						<div class="top">
							<a href="javascript:{}" id="toggler">Clique aqui para visualizar as questões incorretas!</a>
						</div>
					</div>
				</div>
				<?php echo $this->incorrectAnswersHTML; ?>
			</div>
		</div>
		<script type="text/javascript">
			var constant = <?php echo $cA/count($_SESSION['prod']); ?>;
			var circle = new ProgressBar.Circle('#progress', {
			    color: '#003A91',
			    strokeWidth: 5,
			    trailWidth: 1,
			    duration: 1500,
			    text: { value: '0' },
			    step: function(state, bar) {
			        bar.setText((bar.value() * 100).toFixed(0)+"%");
			    }
			});
			circle.animate(constant, function() {});

			var line = new ProgressBar.Line('#line', {
			    color: '#003A91',
			    trailColor: 'red'
			});
			line.animate(constant);

			$( "#incorrectAnswersWrapper" ).slideUp();
			$( "#toggler" ).click(function() {
			  if ( $( "#incorrectAnswersWrapper" ).is( ":hidden" ) ) {
			    $( "#incorrectAnswersWrapper" ).show( "slow" );
			  } else {
			    $( "#incorrectAnswersWrapper" ).slideUp();
			  }
			});
		</script>
	<?php }

	// Prepara variável que armazena o HTML das questões incorretas para a página do resultado
	private function setIncorrectAnswersHTML($prod) {
		$x = count($prod);
		if ($prod) {
			$output = '<div class="incorrectAnswersWrapper" id="incorrectAnswersWrapper">';
			$output .= '<h1 id="curr_page" style="color: #003A91;">Questões incorretas.</h1>';
			for ($i=0; $i < $x; $i++) {
				$a = $prod[$i];
				$l = "div".$i;
				$output .= '<div class="q-box" id="div'.$i.'">
								<div class="q-top-box">
									<div class="top">
										<p> '.$a[1].' - '.$a[2].' ('.$a[3].')</p><hr>
										<p>'.$a[4].'</p>
									</div>
								</div>
								<div class="q-alt-box">
									<div class="alt">
										<p>'.$a[5].'</p>
										<p>'.$a[6].'</p>
										<p>'.$a[7].'</p>
										<p>'.$a[8].'</p>
										<p>'.$a[9].'</p>
									</div>
								</div>';
				if(!empty($a[12])) {
					$output .= '<div class="q-top-box">
									<div class="top">
										<p>'.$a[12].'</p>
									</div>
								</div>
							</div>';
				} else {
					$output .= '</div>';
				}	
			}
			$output .= "</div>";
			$this->incorrectAnswersHTML = $output;
		} else {
			$this->incorrectAnswersHTML = "";
		}
	}
}
?>