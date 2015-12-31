<?php
//Note that ZERO security measures has been taken
// Some security measures have been taken, PREPARED STATEMENTS
//Registers empty spring as well, prevent register.js from sending if email,name,password is empty
require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment

	if($_POST['action'] == "newUser") {
		$email = $_POST['email'];
		$name = $_POST['name'];
		$password = $_POST['password'];
		// named placeholder method
		//First check if email exists or not
		//Looks in database for matching emails
		$result = $DB->prepare("SELECT * FROM user WHERE email = :email");
		$result->execute(array(':email' => $email));
		$count = $result->rowCount();
		if ($count > 0) {
			echo "Existing User";
			exit();
		}
		//Insert New User
		else {
			// named placeholder method
			$data = array(':userid' => NULL, ':email' => $email, ':name' => $name, ':password' =>$password);
			$STH = $DB->prepare("INSERT INTO user ( userid, email, name, password ) values ( :userid, :email, :name, :password )");
			$STH->execute($data);

			echo "Registered";
		}
	}

?>
