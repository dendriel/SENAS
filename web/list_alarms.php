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

<h3>Lista de Alarmes</h3>

<div align="center">

<?php

$con = mysql_connect("localhost","root","root");

if (!$con) {

	die('Could not connect: ' . mysql_error());
}

mysql_select_db("senas", $con);
# ~Magic numbers~ but the code is to small to make a def header  :}
$active = mysql_query("SELECT * FROM sms WHERE stat=0");
$cancel = mysql_query("SELECT * FROM sms WHERE stat=1");
$failed = mysql_query("SELECT * FROM sms WHERE stat=2");
$sent = mysql_query("SELECT * FROM sms WHERE stat=3");

$count = 0;

echo "
<table class=\"alarm_table\"> 
 <tr>

  <td> <!-- 0 - Active -->
	<table class=\"alarm_active\">";
		echo "<div class=\"title\">Ativos</div>";
		echo "<tr class=\"header\"><td>Origem</td><td>Destino</td><td>Operadora</td><td>Data</td></tr>";
	  while($row = mysql_fetch_array($active)) {
  		echo "<tr><td>", $row['orig'],"</td><td>", $row['dest'], "</td><td>", $row['oper'], "</td><td>", $row['blow'], "</td></tr>";
		$count++;
	 }
	echo "Total: $count";
	$count = 0;
echo "	</table>
  </td>

  <td> <!-- 1 - Canceled -->
	<table class=\"alarm_canceled\">";
		echo "<div class=\"title\">Cancelados</div>";
		echo "<tr class=\"header\"><td>Origem</td><td>Destino</td><td>Operadora</td><td>Data</td></tr>";
	  while($row = mysql_fetch_array($cancel)) {
  		echo "<tr><td>", $row['orig'],"</td><td>", $row['dest'], "</td><td>", $row['oper'], "</td><td>", $row['blow'], "</td></tr>";
		$count++;
	 }
	echo "Total: $count";
	$count = 0;
echo "	</table>
  </td>

  <td> <!-- 2 - Failed -->
	<table class=\"alarm_failed\">";
		echo "<div class=\"title\">Falhos</div>";
		echo "<tr class=\"header\"><td>Origem</td><td>Destino</td><td>Operadora</td><td>Data</td></tr>";
	  while($row = mysql_fetch_array($failed)) {
  		echo "<tr><td>", $row['orig'],"</td><td>", $row['dest'], "</td><td>", $row['oper'], "</td><td>", $row['blow'], "</td></tr>";
		$count++;
	 }
	echo "Total: $count";
	$count = 0;
echo "	</table>
  </td>

  <td> <!-- 3 - Sent -->
	<table class=\"alarm_sent\">";
		echo "<div class=\"title\">Enviados</div>";
		echo "<tr class=\"header\"><td>Origem</td><td>Destino</td><td>Operadora</td><td>Data</td></tr>";
	  while($row = mysql_fetch_array($sent)) {
  		echo "<tr><td>", $row['orig'],"</td><td>", $row['dest'], "</td><td>", $row['oper'], "</td><td>", $row['blow'], "</td></tr>";
		$count++;
	 }
	echo "Total: $count";
	$count = 0;
echo "	</table>
  </td>

</tr>
</table>

";

mysql_close($con);
?>

</div>

<?php

# Retrieving fields content #
$user = $_POST['user'];
$pass = $_POST['pass'];

# Checking if was filled #
$len_user = strlen($user);
$len_pass = strlen($pass);
?>
</body>
</html>
