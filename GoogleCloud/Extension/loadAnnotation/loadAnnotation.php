<?php

session_start();

if (isset($_POST['loadAnnotation']) && !empty($_POST['loadAnnotation'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $url = $_POST['articleURL'];

  try {
    // In the future SELECT query needs to be adjusted to include collabors using accessArchive Table
    // Grab the articleId
    $statement = $DB->prepare("SELECT * FROM article WHERE articleURL = :url AND userId = :userId");
    $statement->execute(array(':url' => $url, ':userId' => $userId));
    $count = $statement->rowCount();

    if ($count > 0) {
      $statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
      $articleId = $row["articleId"];

      $statement = $DB->prepare("SELECT * FROM parentAnnotation WHERE articleId = :articleId");
      $statement->execute(array(':articleId' => $articleId));
      $annotationCount = $statement->rowCount();

      $listofParentComment = array();
      $listofParentComment[] = array("articleId" => $articleId);

      if ($annotationCount > 0) {
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        // Need to include name and image URL of creator from user Table
  			while ($annotationRow = $statement->fetch()) {

          switch ($annotationRow["annotationType"]) {
            case "text":
              $typeStatement = $DB->prepare("SELECT * FROM textAnnotation WHERE pAnnotationId = :pAnnotationId");
              $typeStatement->execute(array(':pAnnotationId' => $annotationRow["pAnnotationId"]));
              $typeStatementCount = $typeStatement->rowCount();
              if ($typeStatementCount > 0) {
                $typeStatement->setFetchMode(PDO::FETCH_ASSOC);
                $typeStatementRow = $typeStatement->fetch();

                $annotatedText = $typeStatementRow["annotatedText"];
                $textPosition = $typeStatementRow["textPosition"];

                $listofParentComment[] = array("pAnnotationId" => $annotationRow["pAnnotationId"], "comment" => $annotationRow["comment"], "likes" => $annotationRow["likes"], "annotationType" => $annotationRow["annotationType"], "saveDate" => $annotationRow["saveDate"], "color" => $annotationRow["color"], "annotatedText" => $annotatedText, "textPosition" => $textPosition);
              }

              break;

            case "youtubeVideo":
                //$insert= $DB->prepare("");
                break;

            case "image":
                //insert SQL statements
                break;
          }
        }

        echo json_encode($listofParentComment);

      }

		}



  } catch (PDOException $ex) {
    echo "An error occurred while retrieving annotations.";
  }
  $DB = null;
}

?>
