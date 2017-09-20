<?php

session_start();

$db = 'technofractal';
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '1234';

$connection = new PDO("mysql:dbname=$db;host=$host", $dbUsername, $dbPassword);

$action = 'login';

//var_dump($_SESSION); die();

if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}

if ($action == 'verify')
{
	$login = $_REQUEST['login'];
	$password = $_REQUEST['password'];
	
	$sth = $connection->prepare("SELECT * FROM users where login = :login");
	$sth->bindValue(':login', $login, PDO::PARAM_STR);
	$sth->execute();

	if ($sth->errorCode() != '00000') {
		die(print_r($sth->errorInfo(), 1));
	}

	$result = $sth->fetch(PDO::FETCH_ASSOC);

	if ($result['password'] == $password) 
	{
		$_SESSION["logged_id"] = true;
		header('Location: /?action=add');
		exit();
	} else {
		echo 'password incorrect<br/>';
		echo '<a href="/">back</a>';
	}	
} else if (
	!(
		isset($_SESSION["logged_id"]) && 
		$_SESSION["logged_id"]
	) || $action == 'login'
) {
	?>
	<html>
		<body>
			<form method="POST" action="/?action=verify">
				Login: <input type="text" name="login"/><br/>
				Password: <input type="text" name="password"/><br/>
				<input type="submit" value="submit"/>
			</form>
		</body>
	</html>
	<?php
	
	exit();
}

if ($action == 'insert') {
	// TODO: add
	$name = $_REQUEST['name'];
	$login = $_REQUEST['login'];
	$password = $_REQUEST['password'];

	$sth = $connection->prepare(""
			. "INSERT INTO users ("
			. "name, "
			. "login, "
			. "password) VALUES ("
			. "'$name', "
			. "'$login', "
			. "'$password')");
	$sth->execute();
	
	if ($sth->errorCode() == '23000') {
		echo 'Пользователь уже существует<br/>';
		echo '<a href="/?action=add">back</a>';
		die();
	} else if ($sth->errorCode() != '00000') {
		die(print_r($sth->errorInfo(), 1));
	}

	header('Location: /?action=add');
	exit();
} else if ($action == 'add') {
?>
	<html>
		<body>
			<P>Enter new user<p/>
			<form method="POST" action="/?action=insert">
				Name: <input type="text" name="name"/><br/>
				Login: <input type="text" name="login"/><br/>
				Password: <input type="text" name="password"/><br/>
				<input type="submit" value="enter"/>
			</form>
		</body>
	</html>	
	<br/>
	<a href="/?action=logout">logout</a>
<?php
} else if ($action == 'logout') {
	$_SESSION["logged_id"] = false;
	header('Location: /');
}