<?php
	//Note that ZERO security measures has been taken
	require('../../config.php');
	//calls on config.php - the file you should have looked at first and created the a database for
	//for this file start trying to understand from after the "start here" comment
	session_start();

	//register mustache library
	require '../../Mustache/Autoloader.php';
	Mustache_Autoloader::register();

	//start the mustache engine
	$m = new Mustache_Engine(array(
	    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../../Templates'),
	));

	$userid = $_SESSION['userid'];

	// Grabs info for nav
	require('../../nav.php');

	$values = array(
		'name' => $name,
		'userImage' => $imagefile,
		'archives' => $archive
		);

	//render the template with the set values
	echo $m->render('createArchive', $values);

?>
