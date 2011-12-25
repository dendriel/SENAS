<?php
 $host = "127.0.0.1";
 $port = 3435;
 $timeout = 3;
?>

<html>
<head>
<title>SENAS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="layout.css">
</head>

<body>

<h1>SENAS</h1>

<h2><u>Sistema de Envio Autom&aacute;tico de SMS</u></h2>

<h3>Interface do Gerente</h3>

<div align="left">
<table>

 <tr> <!-- start of line 1 -->
  <td> <!-- start of col 1 -->
   <table class="subtable">
    <form name="halt" action="manager.php" method="post">
	<tr>
	 <td><div class="th">Desligar o Sistema</div></td>
	</tr> 
	<tr>
	 <td><div class="checkbox"><input type="checkbox" name="halt_check_box" value="yes" /> 
        	<?php
	          if ($_POST['halt_check_box'] == "yes") {
        	          $query = "\ID:105/ID\CMD:halt/CMD";
                	  $stream = fsockopen($host, $port, $errno, $errstr, $timeout);
	                  if ($stream == Null) {
        	                echo "Sistema indispon&iacute;vel!";
                	  } else {
	                        fputs($stream, $query);
        	                fclose($stream);
				echo "Comando enviado!";
                	  }
	          }
       	 	?> </div>
	 </td>
	</tr>
	<tr>
	 <td><input type="submit" value="Desligar" /></td>
	</tr>
    </form>
   </table>
  </td> <!-- end of col 1 -->

  <td> <!-- start of col 2 -->
   <table class="subtable">
    <form name="clean_log" action="manager.php" method="post">
        <tr>
         <td><div class="th">Apagar logs</div></td>
        </tr>
        <tr>
         <td><div class="checkbox"><input type="checkbox" name="log_check_box" value="yes" />
                <?php
                  if ($_POST['log_check_box'] == "yes") {
                          $query = "\ID:105/ID\CMD:clean_log/CMD";
                          $stream = fsockopen($host, $port, $errno, $errstr, $timeout);
                          if ($stream == Null) {
                                echo "Sistema indispon&iacute;vel!";
                          } else {
                                fputs($stream, $query);
                                fclose($stream);
                                echo "Comando enviado!";
                          }
                  }
                ?> </div>
         </td>
        </tr>
        <tr>
         <td><input type="submit" value="Apagar" /></td>
        </tr>
    </form>
   </table>
  </td> <!-- end of col 2 -->

  <td> <!-- start of col 2 -->
   <table class="subtable">
    <form name="send_sms" action="send_sms.php" method="post">
        <tr>
	 <td>Agendar SMS</td>
	</tr><tr>
         <td align="center"><input type="submit" value="Agendar" style="float:center"/></td>
        </tr>
    </form>
   </table>
  </td> <!-- end of col 2 -->

  <td> <!-- start of col 3 -->
   <table class="subtable">
    <form name="list_alarm" action="list_alarms.php" method="post">
        <tr>
	 <td>Listar SMS agendados</td>
	</tr><tr>
         <td align="center"><input type="submit" value="Listar" style="float:center"/></td>
        </tr>
    </form>
   </table>
  </td> <!-- end of col 3 -->


 </tr> <!-- end of line 1 -->

 <tr> <!-- level 2 -->
 <table class="subtable">
 <form name="whatever" action="manager.php" method="post">
	<tr>
	  <td align="right"><div class="txt">Usu&aacute;rio:</div></td><td><input type:"text" name="user" value="<?php echo $_POST['user']; ?>"  /></td> <br />
	</tr>
	<tr>
	  <td align="right"><div class="txt">Senha:</div></td><td> <input type="password" name="pass" value="" /></td> <br />
	</tr>
	<tr>
	 <td colspan=2 align="right"><input type="submit" value="Enviar" /></td>
	</tr>
 </form>
 </table>
 </tr> <!-- level 2 -->

</table> 
</div>

<?php

# Retrieving fields content #
$user = $_POST['user'];
$pass = $_POST['pass'];

# Checking if was filled #
$len_user = strlen($user);
$len_pass = strlen($pass);

if( $len_user > 0 && $len_pass > 0) {

	$stream = fsockopen($host, $port, $errno, $errstr, $timeout);
	if ($stream == Null) {
		?><div class="error"><?php echo "O sistema est&aacute; indispon&iacute;vel no momento."; ?></div><?php
	}
	else {
		fputs($stream, "\ID:100/ID\CMD:login/CMD\FROM:$user/FROM\CONTENT:$pass/CONTENT");
	}
}
else if( $len_user > 0 && $len_pass == 0) {
?>	<div class="error"><?php echo "Preencha o campo de senha.";?></div><?php
}
else if( $len_pass > 0 && $len_user == 0) {
?>	<div class="error"> <?php echo "Preencha o campo de usu&aacute;rio.";?></div><?php
}
?>


</body>
</html>
