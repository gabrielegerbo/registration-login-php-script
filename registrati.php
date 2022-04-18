<?php

$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$email = $_POST['email'];
$passwordget = $_POST['password'];
$controlla = $_POST['controlla'];
$password = sha1(md5(sha1($passwordget)));

date_default_timezone_set("Europe/Rome");
$time = microtime(true);
$dFormat = "l jS F, Y - H:i:s";
$mSecs = $time - floor($time);
$mSecs = substr($mSecs,1);
$unique = sprintf('%s%s', date($dFormat), $mSecs );
function generateRandomString($length = 30) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
} return $randomString;
} $returnString = generateRandomString();
$univoco = $returnString.sha1($unique);

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
$query="SELECT * FROM $tabellaUtenti WHERE email = '$email'";
$result = $mysqli->query($query) or die( "Unable to query");
$num = mysqli_num_rows($result);

if ($num > 0) {
$erroreMailUsata = "1";
} else {
$query="INSERT INTO $tabellaUtenti (nome, cognome, email, password, univoco) VALUES ('$nome','$cognome','$email','$password','$univoco')";
$mysqli->query($query) or die( "Unable to query");
$mysqli->close();
header("Location: login.php?reg=ok");
echo "registrazione effettuata";
}

$mysqli->close();

}

?>

<html>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<head>
<title>Registrati</title>
</head>

<body style="text-align:center;">

<h3 style="text-align:center;">Registrati</h3>

<form action="" method="post">

<?php
if($erroreCaratteri == "1") {echo "<p>Hai inserito caratteri non ammessi</p>"; }
if($erroreVuoto == "1") {echo "<p>Hai lasciato dei campi vuoti</p>"; }
if($erroreMail == "1") {echo "<p>Hai inserito una mail non valita</p>"; }
if($erroreMailUsata == "1") {echo "<p>Esiste già un utente registrato con questa mail</p>"; }
?>

<p>Nome:<br/><input id="nome" name="nome" type="text"/></p>
<p>Cognome:<br/><input id="cognome" name="cognome" type="text"/></p>
<p>Email:<br/><input id="email" name="email" type="text"/></p>
<p>Password:<br/><input id="password" name="password" type="password"/></p>
<input id="controlla" name="controlla" type="hidden" value="si"/>
<input name="Submit" type="submit" value="Registrati"/>
</form>

<p><a href="login.php">Accedi</a></p>

</body>
</html>