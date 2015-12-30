<?php
	//Note that ZERO security measures has been taken
	require('../../config.php');
	session_start();
	if (isset($_POST['create']) && !empty($_POST['create'])) {

		if($_POST['create'] == "newArchive") {
			$userid = $_SESSION['userid'];

			$title = $_POST['title'];

			//First check if archive exists or not
			//Looks in database for matching archives
			$result = $DB->prepare("SELECT * FROM archive WHERE archivename = :title AND userid = :userid");
			$result->execute(array(':title' => $title, ':userid' => $userid));
			//Counts the number of rows that archives are equal, should be 1
			$count = $result->rowCount();

			//Archive name exists
			if ($count > 0) {
				echo "Exists";
				exit();
			}

			//Insert New Archive
			else {
				$icon = $_POST['icon'];
				$description = $_POST['description'];
				$private = 0;
				//private = 1 and public = 0
				if ($_POST['privacy'] == 'Private') {
					$private = 1;
				}
				$insertArchive = $DB->prepare("INSERT INTO archive (archiveid, userid, archivename, icon, description, private) VALUES (:archiveid, :userid, :title, :icon, :description, :private)");
				$insertArchive->execute(array(':archiveid' => NULL, ':userid' => $userid, ':title' => $title, ":icon" => $icon, ":description" => $description, ":private" => $private));

				$id = $DB->lastInsertId();

				// $result = $insertArchive->fetch(PDO::FETCH_ASSOC);
        // $id = $result["archiveid"];
				echo $id;
			}
		}
	}
?>
