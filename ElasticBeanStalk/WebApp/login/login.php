<?php
	//Note that ZERO security measures has been taken
	require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
	session_start();

	if($_POST['action'] == "login") {
		$email = $_POST['email'];
		$password = $_POST['password'];

		//Looks in database for matching emails and password
		//NEED TO MAKE IT CASE SENSATIVE FOR PASSWORD
		// BINARY keyword makes it case sensative
		$result = $DB->prepare("SELECT * FROM user WHERE email = :email AND BINARY password = :password");
		$result->execute(array(':email' => $email, ':password' => $password));
		//Counts the number of rows that emails are equal
		$count = $result->rowCount();

		//Log In, and saves session using userid(primary key), allows us to access other data more easily later on
		if ($count == 1) {
			echo "Logged In. Welcome to Mobius.";

			// Setting fetch mode
			$result->setFetchMode(PDO::FETCH_ASSOC);
			$row = $result->fetch();
			$_SESSION['userId'] = $row['userId'];

			exit();
		}
		else {
			echo "Incorrect Email or Password. Please Try Again.";
		}
	}

?>
