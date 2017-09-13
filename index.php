<?php
$login = $_REQUEST['login'];
$password = $_REQUEST['password'];

$db = 'technofractal';
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '1234';

$connection = new PDO("mysql:dbname=$db;host=$host", $dbUsername, $dbPassword);


if (isset($_REQUEST['id'])) {
	// TODO: add
	$id = $_REQUEST['id'];
	$name = $_REQUEST['name'];
	
	$sth = $connection->prepare(""
			. "INSERT INTO users ("
			. "id, "
			. "name, "
			. "login, "
			. "password) VALUES ("
			. "$id, "
			. "'$name', "
			. "'$login', "
			. "'$password')");
	$sth->execute();
	
	if ($sth->errorCode() != '00000') {
		die(print_r($sth->errorInfo(), 1));
	}	
} else if (isset($_REQUEST['login'])) {
	
	$sth = $connection->prepare("SELECT * FROM users where login = :login");
	$sth->bindValue(':login', $login, PDO::PARAM_STR);
	$sth->execute();

	if ($sth->errorCode() != '00000') {
		die(print_r($sth->errorInfo(), 1));
	}

	$result = $sth->fetch(PDO::FETCH_ASSOC);

	if ($result['password'] == $password) {
		echo 'loged in';
?>
	<html>
		<body>
			<P>Enter new user<p/>
			<form method="POST" action="/">
				ID: <input type="text" name="id"/><br/>
				Name: <input type="text" name="name"/><br/>
				Login: <input type="text" name="login"/><br/>
				Password: <input type="text" name="password"/><br/>
				<input type="submit" value="enter"/>
			</form>
		</body>
	</html>	
<?php	
	} else {
		echo 'password incorrect';
?>
		<a href="/">back</a>
<?php
	}
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
}