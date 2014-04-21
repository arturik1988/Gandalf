<?php
require 'app/init.php';
$auth = new Auth;

// Build table structure if it doesn't exist
$auth->build();

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gandalf | Index</title>
	</head>
	<body>
		<?php if($auth->check()): ?>
			<p>You are signed in. <a href="signout.php">Sign out</a></p>
		<?php else: ?>
			<p>You are not signed in! <a href="signup.php">Sign up</a> or <a href="signin.php">Sign in</a></p>
		<?php endif; ?>
	</body>
</html>