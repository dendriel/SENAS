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

<div align="center">
<table>
<form name="login_form" action="login.php" method="post">
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
	stream_set_timeout($stream, $timeout);
	if ($stream == Null) {
		?><div class="error"><?php echo "O sistema est&aacute; indispon&iacute;vel no momento."; ?></div><?php
	}
	else {
		fputs($stream, "\ID:100/ID\CMD:login/CMD\FROM:$user/FROM\CONTENT:$pass/CONTENT");
		$answer = fgets($stream);
		if ($answer == "OK") {
			header('Location: manager.php'); 	
		}
		else if ($answer == "ERROR") {
			?><div class="error"><?php echo "Erro na requisi&ccedil;&atilde;o de autentica&ccedil;&atilde;o."; ?></div><?php

		}
		else if ($answer == "INVALID") {
			?><div class="error"><?php echo "Senha inv&aacute;lida."; ?></div><?php
		}
		else if ($answer == "NOTFOUND") {
			?><div class="error"><?php echo "Usu&aacute;rio inv&aacute;lido."; ?></div><?php
		}
		else {
			?><div class="error"><?php echo "Resposta desconhecida do servidor."; ?></div><?php
		}
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
