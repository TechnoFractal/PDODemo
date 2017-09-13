<?php

if (isset($_REQUEST['login']) && isset($_REQUEST['password']))
{
	$login = $_REQUEST['login'];
	$password = $_REQUEST['password'];
} else {
?>
<html>
	<body>
		<form method="POST" action="/">
			Login: <input type="text" name="login"/><br/>
			Password: <input type="text" name="password"/><br/>
			<input type="submit" value="submit"/>
		</form>
	</body>
</html>
<?php
exit;
}

$db = 'technofractal';
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '1234';

$connection = new PDO("mysql:dbname=$db;host=$host", $dbUsername, $dbPassword);

$sth = $connection->prepare("SELECT * FROM users where login = :login");
$sth->bindValue(':login', $login, PDO::PARAM_STR);
$sth->execute();

if ($sth->errorCode() != '00000')
{
	die(print_r($sth->errorInfo(), 1));
}

$result = $sth->fetch(PDO::FETCH_ASSOC);

if ($result['password'] == $password)
{
	echo 'loged in';
} else {
	echo 'password incorrect';
}

?>

<a href="/">back</a>