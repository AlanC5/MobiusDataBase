<?php

session_start();

if (isset($_POST['addArticle']) && !empty($_POST['addArticle'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $url = $_POST['articleURL'];


  try {

    // Check if article exists prior to saving
    $statement = $DB->prepare("SELECT * FROM article WHERE articleURL = :url AND userId = :userId");
    $statement->execute(array(':url' => $url, ':userId' => $userId));
    $count = $statement->rowCount();

    if ($count > 0) {
				echo "Article Exists";
				exit();
		}
    // Insert new article
    else {
      $articleName = $_POST['articleName'];
      // Load the archiveId into the extension
      $archiveId = $_POST['archiveId'];
      $description = $_POST['description'];
      $imageURL = $_POST['imageURL'];
      $privacy = $_POST['privacy'];

      $insert = $DB->prepare("INSERT INTO article (articleId, archiveId, userId, articleName, description, private, articleURL, imageURL, saveDate) VALUES (:articleId, :archiveId, :userId, :articleName, :description, :private, :articleURL, :imageURL, NOW())");

      if ($insert->execute(array(':articleId' => NULL, ':archiveId' => $archiveId, ':userId' => $userId, ':articleName' => $articleName, ':description' => $description, ':private' => $privacy, ':articleURL' => $url, ':imageURL' => $imageURL))) {

        echo $DB->lastInsertId();
        // echo "Successfully Saved";
      }
      else {
        echo "Failed to Saved";
      }
    }

  } catch (PDOException $ex) {
    echo "An error occurred while retrieving archives.";
  }
  $DB = null;
}
?>
