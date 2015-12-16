<?php
	//Note that ZERO security measures has been taken
	require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'status') {
	session_start();


	if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
		echo "Already Logged In";
		exit();
	}

	else {
		echo "Not Logged In";
	}

}




	if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'login') {

		session_start();


		if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
			echo "Already Logged In";
			exit();
		}

		else {
			$email = $_POST['email'];
			$password = $_POST['password'];
			//First check if email exists or not
			//Looks in database for matching emails and password
			//NEED TO MAKE IT CASE SENSATIVE FOR PASSWORD
			// BINARY keyword makes it case sensative
			$result = $DB->prepare("SELECT * FROM user WHERE email = :email AND BINARY password = :password");
			$result->execute(array(':email' => $email, ':password' => $password));
			//Counts the number of rows that emails are equal
			$count = $result->rowCount();
			//Log In, and saves session using userid(primary key), allows us to access other data more easily later on
			if ($count == 1) {
				// Setting fetch mode
				$result->setFetchMode(PDO::FETCH_ASSOC);
				$row = $result->fetch();
				$_SESSION['userId'] = $row['userId'];

				echo "Logged In";

				exit();
			}
			else {
				echo "Incorrect Email or Password";
			}
		}
	}
?>
