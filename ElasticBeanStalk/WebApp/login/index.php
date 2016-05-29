<?php

// require('../../config.php');

// session_start();
// if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
// 	header("Location: https://mobiusdev-dev.us-east-1.elasticbeanstalk.com/WebApp/login/login.php");
// 	exit();
// }

// else {
	echo
	'<html>
		<head>
			<title>Log In</title>
			<meta name="google-signin-client_id" content="102629415692-92fn510gpjosketphv6i2gbs1ds6o64l.apps.googleusercontent.com">
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
				<div class="g-signin2" data-onsuccess="onSignIn"></div>
				<a href="#" onclick="signOut();">Sign out</a>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="https://apis.google.com/js/platform.js" async defer></script>
			<script>
				function onSignIn(googleUser) {
					var profile = googleUser.getBasicProfile();
					console.log("ID: " + profile.getId()); // Do not send to your backend! Use an ID token instead.
					console.log("Name: " + profile.getName());
					console.log("Image URL: " + profile.getImageUrl());
					console.log("Email: " + profile.getEmail());

					window.location.replace("test.html");
				}
				function signOut() {
					var auth2 = gapi.auth2.getAuthInstance();
					auth2.signOut().then(function () {
						console.log("User signed out.");
					});
				}
			</script>
		</body>
	</html>';
// }

?>
