<?php

require('config.php');

session_start();
if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
	header("Location: http://mobiusdev-dev.us-east-1.elasticbeanstalk.com/WebApp/recent/index.php");
	exit();
}

else {
	echo
	'<html>
		<head>
			<title>Log In</title>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
			<link rel="stylesheet" type="text/css" href="/styles/login.css">
		</head>

		<body>
			<div id="mobius-login-body">
			  <p id="mobius-login-heading">
			    MOBIUS
			  </p>
			  <span class="mobius-incorrect-description">Incorrect Email or Password</span>
			  <input type="email" class="email-transparent mobius-email-input" placeholder="Email" value="">
			  <div class="mobius-password-container" style="margin-bottom: 20px;">
			    <input type="password" class="mobius-password-input" placeholder="Password" value="">
			    <div class="my-toggle hideShowPassword-toggle-hide" style="position: absolute;">SHOW</div>
			  </div>
			  <div id="mobius-submit-button">
			    <span>Log In</span>
			  </div>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="/scripts/login.js"></script>
		</body>
	</html>';
}

?>
