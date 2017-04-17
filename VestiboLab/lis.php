<?php
   include_once("bd.php");
   $sql="SELECT * FROM questions";
   $ds=mysql_query($sql) or die(mysql_error());
?>
<html>
   <head>
      <meta charset="utf-8">
      <link rel="stylesheet" type="text/css" href="css/estilo.css" />
      <title>Listar questões - VESTIBO</title>
   </head>
   <body>
      <div id="box">
         <center>
            <img src="logo.png" id="logo">
            <br>
            <input type="button" class="btn" name="btn_cad" value="Cadastrar questões" onclick="javascript: window.location = 'index.php';">
            <h1>Lista de questões (TOTAL:<?php echo(mysql_num_rows($ds)); ?> )</h1>
         </center>
         <?php
            echo ("<hr>");
            while($x=mysql_fetch_assoc($ds)){
               echo ("Vestibular: ".$x['q_vestibular']." - ".$x['q_year'].": ".$x['q_num']."<br>");
               echo ("<hr>");
               echo ("Pergunta: ".$x['q_content']."<br>");
               echo ("Alternativa 1: ".$x['q_alt_1']."<br>");
               echo ("Alternativa 2: ".$x['q_alt_2']."<br>");
               echo ("Alternativa 3: ".$x['q_alt_3']."<br>");
               echo ("Alternativa 4: ".$x['q_alt_4']."<br>");
               echo ("Alternativa 5: ".$x['q_alt_5']."<br>");
               echo ("Alternativa Correta: ".$x['q_correct_alt']."<br>");
               echo ("<br>");
               echo ("Tema: ".$x['q_sub_2_id']."<br>");
               echo ("Comentário: ".$x['q_comment']."<br>");
               echo ("Data de Criação: ".$x['q_timestamp']."<br>");
               echo ("ID da questão: ".$x['q_id']."<br>");
               echo ("<br><hr>");
            }
         ?>
      </div>
   </body>
</html>