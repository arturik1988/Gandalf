<?php
require 'app/init.php';
$auth = new Auth;

if(!empty($_POST)) {

	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);

	$signin = $auth->signin(array(
		"username" => $username,
		"password" => $password
	));

	if($signin) {
		header("Location: index.php");
	}
}

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gandalf | Sign in</title>
	</head>
	<body>
		<?php if($auth->hasErrors()): ?>
			<?php print_r($auth->errors()); ?>
		<?php endif; ?>

		<form action="signin.php" method="post">
			<fieldset>
				<legend>Sign in</legend>
				<label>
					Username
					<input type="text" name="username">
				</label>
				<label>
					Password
					<input type="password" name="password">
				</label>
			</fieldset>
			<input type="submit" value="Sign in">
		</form>
	</body>
</html>