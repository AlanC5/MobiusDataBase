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

      $archiveStatement = $DB->prepare("SELECT archiveName FROM archive WHERE archiveId = :archiveId");
      $archiveStatement->execute(array(':archiveId' => $row["archiveId"]));
      $archiveStatement->setFetchMode(PDO::FETCH_ASSOC);
      $archiveRow = $archiveStatement->fetch();

      $listofParentComment = array();
      $listofParentComment[] = array("articleId" => $articleId, "articleName" => $row["articleName"], "description" => $row["description"], "private" => $row["private"], "archiveId" => $row["archiveId"], "archiveName" => $archiveRow["archiveName"]);


      $statement = $DB->prepare("SELECT * FROM parentAnnotation WHERE articleId = :articleId");
      $statement->execute(array(':articleId' => $articleId));
      $annotationCount = $statement->rowCount();


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

                $childStatement = $DB->prepare("SELECT * FROM childrenAnnotation WHERE pAnnotationId = :pAnnotationId");
                $childStatement->execute(array(':pAnnotationId' => $annotationRow["pAnnotationId"]));
                $childStatementCount = $childStatement->rowCount();

                $childList = array();

                if ($childStatementCount > 0) {
                  $childStatement->setFetchMode(PDO::FETCH_ASSOC);


                  while ($childStatementRow = $childStatement->fetch()) {
                      array_push($childList, $childStatementRow["comment"]);
                  }
                }

                $listofParentComment[] = array("pAnnotationId" => $annotationRow["pAnnotationId"], "comment" => $annotationRow["comment"], "likes" => $annotationRow["likes"], "annotationType" => $annotationRow["annotationType"], "saveDate" => $annotationRow["saveDate"], "color" => $annotationRow["color"], "annotatedText" => $annotatedText, "textPosition" => $textPosition, "children" => $childList);
              }

              break;

            case "youtubeVideo":
              $typeStatement = $DB->prepare("SELECT * FROM youtubeVideoAnnotation WHERE pAnnotationId = :pAnnotationId");
              $typeStatement->execute(array(':pAnnotationId' => $annotationRow["pAnnotationId"]));
              $typeStatementCount = $typeStatement->rowCount();
              if ($typeStatementCount > 0) {
                $typeStatement->setFetchMode(PDO::FETCH_ASSOC);
                $typeStatementRow = $typeStatement->fetch();

                $annotatedTime = $typeStatementRow["annotatedTime"];

                $listofParentComment[] = array("pAnnotationId" => $annotationRow["pAnnotationId"], "comment" => $annotationRow["comment"], "likes" => $annotationRow["likes"], "annotationType" => $annotationRow["annotationType"], "saveDate" => $annotationRow["saveDate"], "color" => $annotationRow["color"], "annotatedTime" => $annotatedTime);
              }
              break;


            case "image":
              //insert SQL statements
              break;
          }
        }
      }

      echo json_encode($listofParentComment);

		}



  } catch (PDOException $ex) {
    echo "An error occurred while retrieving annotations.";
  }
  $DB = null;
}

?>
