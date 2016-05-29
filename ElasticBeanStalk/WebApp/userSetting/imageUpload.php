<?php
// http://stackoverflow.com/questions/8103860/move-uploaded-file-gives-failed-to-open-stream-permission-denied-error-after
// Change the directory owner for move_uploaded_file to work
// THE DIRECTORY IS CURRENTLY PUBLIC TO ALL, BUG NEEDS TO BE FIXED


// THIS TABLE HAS NOT BEEN CREATED MUST CREATE TABLE


//Note that ZERO security measures has been taken
require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
	session_start();
	// Store file using a more direct method so it stores better in DateTimeImmutable

	if (isset($_GET['files'])) {
		$uploaddir = '../userImages/';
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

				$uploaddir = 'http://mobius-website-1.appspot.com/userImages/';
        $filePath = $uploaddir . basename($filename);
				$userId = $_SESSION['userId'];
        $insert = $DB->prepare("INSERT INTO profileImage (imageId, userId, filePath, dateUploaded) VALUES (:imageId, :userId, :filePath, NOW())");
        $insert->execute(array(':imageId' => NULL, ':userId' => $userId, ':filePath' => $filePath));

        $update = $DB->prepare("UPDATE user SET imagefile = :filePath WHERE userId = :userId");
        $update->execute(array(':filePath' => $filePath, ':userId' => $userId));
				echo "Updated";
			}
		}

		else {
			echo "Invalid file size or type";
		}
	}

?>
