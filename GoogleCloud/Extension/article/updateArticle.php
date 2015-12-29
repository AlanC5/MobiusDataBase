<?php
session_start();

if (isset($_POST['deleteArticle']) && !empty($_POST['deleteArticle'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $articleId = $_POST['articleId'];

  try {

    // Delete article
    $statement = $DB->prepare("UPDATE article SET archiveId = :archiveId, articleName = :articleName, description = :description, private = :private WHERE userId = :userId AND articleId = :articleId");
    $statement->execute(array(':userId' => $userId, ':articleId' => $articleId));
    $count = $statement->rowCount();

    if ($count > 0) {
      echo "Successfully Deleted Article";
    }

    else  {
      echo "Error while deleting Article";
    }



  } catch (PDOException $ex) {
    echo "An error occurred in reading or writing to archive.";
  }
  $DB = null;
}
?>
