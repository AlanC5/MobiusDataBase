<?php
//Search through current user's archive to locate desired archive, this page is called everytime user clicks on navbar
//Note that ZERO security measures has been taken
	require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
	session_start();

	//register mustache library
	require '../Mustache/Autoloader.php';
	Mustache_Autoloader::register();

	//start the mustache engine
	$m = new Mustache_Engine(array(
	    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
	));

	$userId = $_SESSION['userId'];

	// Grabs info for nav
	require('../nav.php');

	$resultSetting = $DB->prepare("SELECT email, imagefile FROM user WHERE userId = :userId");
	$resultSetting->execute(array(':userId' => $userId));
	$resultSetting->setFetchMode(PDO::FETCH_ASSOC);
	$row = $resultSetting->fetch();
	$email = $row["email"];
	$userImage = $row["imagefile"];

	$values = array(
		'name' => $name,
		'archives' => $archive,
		'email' => $email,
		'userImage' => $userImage
		);

	echo $m->render('userSetting', $values);
?>
