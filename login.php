<?php

if($_GET['logout'] == "si" || $_GET['reg'] == "ok") {
setcookie("univoco", "");
} else if($_COOKIE['univoco'] != "") {
header("Location: index.php");
}

$email = $_POST['email'];
$passwordget = $_POST['password'];
$controlla = $_POST['controlla'];
$password = sha1(md5(sha1($passwordget)));

if($controlla == "si") {
foreach($_POST as $key=>$value)
{
if (preg_match("([<>&(),%'?+])", $value) || preg_match('/"/', $value)){ $erroreCaratteri = "1"; }
if ($value == "") { $erroreVuoto = "1"; }
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $erroreMail = "1"; }
}

include 'CONFIG.php';

if ($erroreCaratteri != "1" && $erroreVuoto != "1" && $erroreMail != "1" && $controlla == "si") {

$mysqli = new mysqli($host, $username_db, $password_db, $db_name) or die( "Unable to connect");
$mysqli->select_db($db_name) or die( "Unable to select database");
mysqli_set_charset($mysqli,"utf8");
$query="SELECT univoco FROM $tabellaUtenti WHERE email = '$email' AND password = '$password'";
$result = $mysqli->query($query) or die( "Unable to query");
$num = mysqli_num_rows($result);
$logdb = mysqli_fetch_row($result);
$mysqli->close();
$univoco = $logdb['0'];

if ($num < 1) {
$erroreNonEsiste = "1";
} else {
setcookie("univoco", $univoco);
header("Location: index.php");
}

}

?>

<html>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<head>
<title>Accedi</title>
</head>

<body style="text-align:center;">
<h3 style="text-align:center;">Accedi</h3>

<form action="?" method="post">

<?php
if($erroreCaratteri == "1") {echo "<p>Hai inserito caratteri non ammessi</p>"; }
if($erroreVuoto == "1") {echo "<p>Hai lasciato dei campi vuoti</p>"; }
if($erroreMail == "1") {echo "<p>Hai inserito una mail non valita</p>"; }
if($erroreNonEsiste == "1") {echo "<p>Non esiste nessun utente con questi dati</p>"; }
if($_GET['reg'] == "ok") {echo "<p>Registrazione effettuata, puoi accedere</p>";}
if($_GET['logout'] == "si") {echo "<p>Accedi per continuare</p>";}
?>

<p>Email:<br/><input id="email" name="email" type="text"/></p>
<p>Password:<br/><input id="password" name="password" type="password"/></p>
<input id="controlla" name="controlla" type="hidden" value="si"/>
<input name="Submit" type="submit" value="Accedi"/>
</form>

<p><a href="registrati.php">Registrati</a></p>

</body>
</html>