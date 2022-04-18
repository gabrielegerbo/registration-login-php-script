<?php

$univoco = $_COOKIE['univoco'];

if($univoco == "" || preg_match("([<>&(),%'?+])", $univoco) || preg_match('/"/', $univoco)){
header("Location: login.php?logout=si");
}

include 'CONFIG.php';

$mysqli = new mysqli($host, $username_db, $password_db, $db_name) or die( "Unable to connect");
$mysqli->select_db($db_name) or die( "Unable to select database");
mysqli_set_charset($mysqli,"utf8");
$query="SELECT id,nome,cognome,email FROM $tabellaUtenti WHERE univoco = '$univoco'";
$result = $mysqli->query($query) or die( "Unable to query");
$num = mysqli_num_rows($result);
$logdb = mysqli_fetch_row($result);
$mysqli->close();
$id = $logdb['0'];
$nome = $logdb['1'];
$cognome = $logdb['2'];
$email = $logdb['3'];

if ($num < 1) {
header("Location: login.php?logout=si");
}

?>

<html>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<head>
<title>Index</title>
</head>

<body style="text-align:center;">

<h3 style="text-align:center;">Pagina index</h3>

<p></p>Benvenuto <? echo $nome." ".$cognome; ?></p>
<p></p>La tua mail è <? echo $email; ?></p>
<p></p>Il tuo ID utente è <?php echo $id; ?></p>
<p><a href="login.php?logout=si">Logout</a></p>

</body>

</html>