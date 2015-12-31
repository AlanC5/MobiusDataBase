<?php
//Note that ZERO security measures has been taken
//Figure out how to send email
require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment

	if($_POST['action'] == "forgot") {
		$email = $_POST['email'];

		//First check if email exists or not
		//Looks in database for matching emails and password
		$result = $DB->prepare("SELECT * FROM user WHERE email = :email");
		//Counts the number of rows that emails are equal
		$result->execute(array(':email' => $email));
		$count = $result->rowCount();

		//Log In
		if ($count == 1) {
			echo "Send Email";
			exit();
		}
		else {
			echo "Incorrect Email. Please Try Again";
		}
	}

?>
