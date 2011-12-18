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
<h3>Enviar SMS</h3>

<div align="center">
   <table>

    <form name="send_sms" action="send_sms.php" method="post">

<tr><!--start of line 1-->

   <td> <!--col 1-->
      <tr>  <!--start1-->
         <td><div class="txt_inp">Origem:<input type="text" name="orig" maxlength=10 value="<?php echo $_POST['orig'];?>"></div></td>
      </tr> <!--end1-->

      <tr>  <!--start2-->
         <td><div class="txt_inp">Destino:<input type="text" name="dest" maxlength=12 value="<?php echo $_POST['dest'];?>"></div></td>
      </tr> <!--end2-->

      <tr> <!--start-->
         <td><div class="txt_inp">Data/Hora:<input type="text" name="datetime" maxlength=12 value="<?php echo $_POST['datetime']; ?>"/></div></td>
      </tr><!--end-->

      <tr> <!--start-->
	 <td>
	   <div class="txt">Operadora:

	    <tr>
	      <td class="txt_inp"><input type="radio" name="oper" checked="yes" value="0" />VIVO</td>
	    </tr>

	    <tr>
	      <td class="txt_inp"><input type="radio" name="oper" value="1" />OI</td>
	    </tr>

	    <tr>
	      <td class="txt_inp"><input type="radio" name="oper" value="2" />CLARO</td>
	    </tr>

	    <tr>
	      <td class="txt_inp"><input type="radio" name="oper" value="3" />TIM</td>
	    </tr>

	   </div>
	  </td>

        </tr> <!--end-->

        <tr><!--start5-->
	  <td><div class="txt">Mensagem:<div style:"float:right"><textarea rows="8" cols="19" name="msg" maxlength=150><?php echo $_POST['msg'];?></textarea> <br />M&aacute;x.150 caracteres</div></div></td>
        </tr><!--end5-->

        <tr><!--start6-->
          <td><input type="submit" value="Enviar" style="float:right"/></td>
        </tr><!--end6-->

     </td> <!--end of col 1-->
</tr>
    </form>
   </table>
</div>

<?php

# Retrieving fields content #
$orig = $_POST['orig'];
$dest = $_POST['dest'];
$datetime = $_POST['datetime'];
$oper = $_POST['oper'];
$msg = $_POST['msg'];

# Checking if was filled #
$len_orig = strlen($orig);
$len_dest = strlen($dest);
$len_datetime = strlen($datetime);
$len_msg = strlen($msg);

if( $len_orig > 0 && $len_dest > 0 && $len_datetime > 0) {
	
	if ($len_datetime < 12) {
		?><div class="error"><?php echo "Campo Data/Hora de envio incompleto."; ?></div><?php
		return;
	}
	else if ($len_msg == 0) {
		?><div class="error"><?php echo "Voc&ecirc; n&atilde;o pode enviar uma mensagem vazia."; ?></div><?php
		return;
	}

	$stream = fsockopen($host, $port, $errno, $errstr, $timeout);
	stream_set_timeout($stream, $timeout);
	if ($stream == Null) {
		?><div class="error"><?php echo "O sistema est&aacute; indispon&iacute;vel no momento."; ?></div><?php
	}
	else {
		fputs($stream, "\ID:100/ID
				\CMD:send_sms/CMD
				\INFO:$oper/INFO
				\FROM:$orig/FROM
				\CONTENT:$msg/CONTENT
				\HOWMANY:1/HOWMANY
				\BLOW:$datetime/BLOW
				\DATA:
				   \PART0:$dest/PART0
				/DATA");
		$answer = fgets($stream);
		if ($answer == "OK") {
			?><div class="txt"><?php echo "SMS agendado com sucesso!";?></div><?php
		}
		else if ($answer == "ERROR") {
			?><div class="error"><?php echo "Erro do sistema."; ?></div><?php

		}
		else if ($answer == "NOTFOUND") {
			?><div class="error"><?php echo "Faltam dados na requisi&ccedil;&atilde;o."; ?></div><?php
		}
		else if ($answer == "INVALID") {
			?><div class="error"><?php echo "Data/Tempo inv&aacute;lido."; ?></div><?php
		}
		else {
			?><div class="error"><?php echo "Resposta desconhecida do servidor."; ?></div><?php
		}
	}
}
else if (($len_orig > 0 && $len_dest == 0) || ($len_orig > 0 && $len_datetime == 0) || ($len_dest > 0 && $len_orig == 0) || ($len_dest > 0 && $len_datetime == 0) || ($len_datetime > 0 && $len_orig == 0) || ($len_datetime > 0 && $len_dest == 0) || ( $len_msg > 0 && ($len_dest == 0 || $len_orig == 0 || $len_datetime ==0))) {
?>	<div class="error"> <?php echo "Preencha todos os campos.";?></div><?php
}
// No, we DON'T need javascript =/ //
?>

</body>
</html>
