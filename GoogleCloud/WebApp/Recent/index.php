<?php
// TO DO apply ? to read URL and detect if there is any notification to send out and display
// Templates the most recent articles organizes by the time
// Must contain the archivename (include in CSS and JS)
  require('../../config.php');
  //calls on config.php - the file you should have looked at first and created the a database for
  session_start();

  $userId = $_SESSION['userId'];
  //register mustache library
  require '../Mustache/Autoloader.php';
  Mustache_Autoloader::register();

  //start the mustache engine
  $m = new Mustache_Engine(array(
      'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
  ));

  // Grabs info for nav
  require('../nav.php');

  // Grabs most recent articles based on time (savedate)
  // Specify *
  $resultArticle = $DB->prepare("SELECT
                                  user.name as 'name',
                                  user.imagefile as 'imagefile',
                                  article.articleURL as 'articleURL',
                                  article.articleName as 'articlename',
                                  article.private as 'private',
                                  article.description as 'description',
                                  article.imageURL as 'imageURL',
                                  archive.icon as 'icon',
                                  archive.archiveName as 'archivename'
                                  FROM article
                                  JOIN user ON article.userId = user.userId
                                  JOIN archive ON article.archiveId = archive.archiveId
                                  WHERE article.userId = :userId");
  $resultArticle->execute(array(':userId' => $userId));
  $articleNumRows = $resultArticle->rowCount();

  $article = array();

  if ($articleNumRows > 0) {
    $resultArticle->setFetchMode(PDO::FETCH_ASSOC);

    while ($row = $resultArticle->fetch()) {
      // request with userid to the user table to find name of person
      $author = $row["name"];
      $authorImg = $row["imagefile"];
      $article[] = array("articleLink" => $row["articleURL"],
      "articleName" => $row["articlename"],
      "author" => $author,
      "authorImg" => $authorImg,
      "private" => $row["private"],
      "description" => $row["description"],
      "articleImg" => $row["imageURL"],
      "locationArchiveIcon" => $row["icon"],
      "locationArchive" => $row["archivename"]);
    }
  }

  $updateMessage = "";

  if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $deletedArchive = $_GET['archive'];
    $updateMessage = "Deleted Archive: $deletedArchive";
  }

  $values = array(
    'name' => $name,
    'userImage' => $imagefile,
    'updateMessage' => $updateMessage,
    'archives' => $archive,
    'article' => $article
  );
  //render the template with the set values
  echo $m->render('recent', $values);

 ?>
