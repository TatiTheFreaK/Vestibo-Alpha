<?php
	$conn=mysql_connect("mysql.hostinger.com.br","u395938104_adm","admpass");
	if (!$conn) 
	{
		die ("<script language='JavaScript'>
            var si = alert('Não cosegui conexao!')
            window.location = 'index.php'
        	</script>");
	}
	$dbsel=mysql_select_db("u395938104_1");
	if (!$dbsel)
	{
		die ("<script language='JavaScript'>
            var si = alert('Erro BD#1')
            window.location = 'index.php'
        	</script>");
	}
?>