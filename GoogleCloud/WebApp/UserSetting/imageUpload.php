<?php
// http://stackoverflow.com/questions/8103860/move-uploaded-file-gives-failed-to-open-stream-permission-denied-error-after
// Change the directory owner for move_uploaded_file to work
// THE DIRECTORY IS CURRENTLY PUBLIC TO ALL, BUG NEEDS TO BE FIXED

//Note that ZERO security measures has been taken
require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
	session_start();
	// Store file using a more direct method so it stores better in DateTimeImmutable

	if (isset($_GET['files'])) {
		$uploaddir = '../UserImages/';
		$filename = $_FILES["file"]["name"];

		// Check file type (only allows png,jpeg, and jpg)
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["file"]["name"]);
		$file_extension = end($temporary);						// Grabs end of array, which is the file extention

		//Approx. 400kb files can be uploaded. Error may be due to file size
		if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] < 400000) && in_array($file_extension, $validextensions)) {
			// Returns error if there is
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
			}

			else {
				// Naming convention based on time and make sure there is no file with the same name
				do {
          // Determines if file with same name exists or not
					$temp = explode(".", $filename);
					$filename = round(microtime(true)) . '.' . end($temp);
				}	while (file_exists($uploaddir . basename($filename)));

				// Uploads the file
				move_uploaded_file($_FILES["file"]["tmp_name"], $uploaddir . basename($filename));

				$uploaddir = 'http://localhost/website/UserImages/';
        $filepath = $uploaddir . basename($filename);
				$userid = $_SESSION['userid'];
        $insert = $DB->prepare("INSERT INTO profileimage (imageid, userid, filepath, dateuploaded) VALUES (:imageid, :userid, :filepath, NOW())");
        $insert->execute(array(':imageid' => NULL, ':userid' => $userid, ':filepath' => $filepath));

        $update = $DB->prepare("UPDATE user SET imagefile = :filepath WHERE userid = :userid");
        $update->execute(array(':filepath' => $filepath, ':userid' => $userid));
				echo "Updated";
			}
		}

		else {
			echo "Invalid file size or type";
		}
	}

?>
