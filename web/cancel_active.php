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

$sent = mysql_query("UPDATE sms SET stat=1 WHERE stat=0");

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
