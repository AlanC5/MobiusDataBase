<?php
session_start();

if (isset($_POST['updateArticle']) && !empty($_POST['updateArticle'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $articleId = $_POST['articleId'];

  $archiveId = $_POST['archiveId'];
  $articleName = $_POST['articleName'];
  $description =$_POST['description'];
  $privacy =$_POST['privacy'];

  try {

    // UPDATE article
    $statement = $DB->prepare("UPDATE article SET archiveId = :archiveId, articleName = :articleName, description = :description, private = :private WHERE userId = :userId AND articleId = :articleId");
    $statement->execute(array(':archiveId' => $archiveId, ':articleName' => $articleName, ':description' => $description, ':private' => $privacy, ':userId' => $userId, ':articleId' => $articleId));
    $count = $statement->rowCount();

    if ($count > 0) {
      echo "Successfully Updated Article";
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
