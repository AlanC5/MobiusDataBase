<?php
//Search through current user's archive to locate desired archive, this page is called everytime user clicks on navbar
//Note that ZERO security measures has been taken
	require('../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
	session_start();
	if (isset($_GET['archive'])) {
		$archiveIdentifier = $_GET['archive'];
		// Session for archive so we don't have to grab name on client side,
		// Remember to update this name and variable whenever title is changed.

		//register mustache library
		require '../Mustache/Autoloader.php';
		Mustache_Autoloader::register();

		//start the mustache engine
		$m = new Mustache_Engine(array(
		    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../Templates'),
		));
		$userid = $_SESSION['userid'];

		// Grabs info for nav
		require('../nav.php');

		// Grabs archiveid with userid and archive name, archiveid is unique to every single archive even if they have the same name
		$selectedArchive = $DB->prepare("SELECT * FROM archive WHERE userid = :userid AND archiveid = :archiveid");
		$selectedArchive->execute(array(':userid' => $userid, ':archiveid' => $archiveIdentifier));
		$archiveNumRows = $selectedArchive->rowCount();

		if ($archiveNumRows > 0) {
			$selectedArchive->setFetchMode(PDO::FETCH_ASSOC);
			$row = $selectedArchive->fetch();
			$archiveId = $row["archiveid"];
			$archiveIcon = $row["icon"];
			$archiveName = $row["archivename"];
			$archiveDescription = $row["description"];
			$archivePrivate = $row["private"];

			$_SESSION['archiveid'] = $archiveId;

			// Grabs articles located in the specific archiveid
			$resultArticle = $DB->prepare("SELECT * FROM article JOIN user ON article.userid = user.userid WHERE archiveid = :archiveid");
			$resultArticle->execute(array(':archiveid' => $archiveId));
			$articleNumRows = $resultArticle->rowCount();

			$article = array();

			if ($articleNumRows > 0) {
				$resultArticle->setFetchMode(PDO::FETCH_ASSOC);

				while ($row = $resultArticle->fetch()) {
					// request with userid to the user table to find name of person

					$article[] = array("articleLink" => $row["filelocation"], "articleId" => $row["articleid"], "articleName" => $row["articlename"], "author" => $row["name"], "authorImg" => $row["imagefile"], "private" => $row["private"], "description" => $row["description"], "articleImg" => $row["imageURL"]);
				}
			}


			//Skipping collaborators for now, implement later on
			$values = array(
				'name' => $name,
				'userImage' => $imagefile,
				'archives' => $archive,
				'icon' => $archiveIcon,
				'title' => $archiveName,
				'description' => $archiveDescription,
				'private' => $archivePrivate,
				'article' => $article
				);

			//render the template with the set values
			echo $m->render('archive', $values);
		}
	};
?>
