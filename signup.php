<?php
require "app/init.php";
$auth = new Auth;

if(!empty($_POST)) {

	$email = trim($_POST["email"]);
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);

	$create = $auth->create(array(
		"email" => $email,
		"username" => $username,
		"password" => $password
	));

	if($create) {
		header("Location: index.php");
	}
}

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gandalf | Sign up</title>
	</head>
	<body>
		<?php if($auth->hasErrors()): ?>
			<?php print_r($auth->errors()); ?>
		<?php endif; ?>

		<form action="signup.php" method="post">
			<fieldset>
				<legend>Sign up</legend>
				<label>
					Your email address
					<input type="text" name="email">
				</label>
				<label>
					Choose username
					<input type="text" name="username">
				</label>
				<label>
					Choose password
					<input type="password" name="password">
				</label>
			</fieldset>
			<input type="submit" value="Sign up">
		</form>
	</body>
</html>