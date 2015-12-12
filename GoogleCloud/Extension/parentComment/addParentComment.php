<?php

session_start();


if (isset($_POST['addParentComment']) && !empty($_POST['addParentComment'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $articleId = $_POST['articleId'];

  try {
    // Check if article Exists

    $statement = $DB->prepare("SELECT * FROM article WHERE articleId = :articleId");
    $statement->execute(array(':articleId' => $articleId));
    $count = $statement->rowCount();

    // If it does exist then save the parentAnnotation
    if ($count > 0) {
      //A parentAnnotation has no likes and no childrenAnnotation when created
				$comment = $_POST['comment'];
        $likes = 0;
        $annotationType = $_POST['annotationType'];
        $numChildrenAnnotation = 0;
        $color = $_POST['color'];

        $insert = $DB->prepare("INSERT INTO parentAnnotation (pAnnotationId, userId, articleId, comment, likes, annotationType, numChildrenAnnotation, color, saveDate) VALUES (:pAnnotationId, :userId, :articleId, :comment, :likes, :annotationType, :numChildrenAnnotation, :color, NOW())");

        if ($insert->execute(array(':pAnnotationId' => NULL, ':userId' => $userId, ':articleId' => $articleId, ':comment' => $comment, ':likes' => $likes, ':annotationType' => $annotationType, ':numChildrenAnnotation' => $numChildrenAnnotation, ':color' => $color))) {

          // Returns the last intserted Primary Key
          $pAnnotationId = $DB->lastInsertId();

          // Insert into respective annotationType table
          switch ($annotationType) {
            case "text":
                $annotatedText = $_POST['annotatedText'];
                $textPosition = $_POST['textPosition'];

                $insert = $DB->prepare("INSERT INTO textAnnotation (annotationId, pAnnotationId, annotatedText, textPosition) VALUES (:annotationId, :pAnnotationId, :annotatedText, :textPosition)");
                if($insert->execute(array(':annotationId' => NULL, ':pAnnotationId' => $pAnnotationId, ':annotatedText' => $annotatedText, ':textPosition' => $textPosition))) {
                  echo "Successfully Saved Text Annotation";
                }
                break;

            case "youtubeVideo":
                $annotatedTime = $_POST['annotatedTime'];

                $insert = $DB->prepare("INSERT INTO youtubeVideoAnnotation (annotationId, pAnnotationId, annotatedTime) VALUES (:annotationId, :pAnnotationId, :annotatedTime)");
                if($insert->execute(array(':annotationId' => NULL, ':pAnnotationId' => $pAnnotationId, ':annotatedTime' => $annotatedTime))) {
                  echo "Successfully Saved Youtube Video Annotation";
                }
                break;

            case "image":
                //insert SQL statements
                break;
          }

      }
      else {
        echo "Failed to Saved";
      }

    }
  } catch (PDOException $ex) {
    echo "An error occurred while saving parent comment.";
  }
  $DB = null;
}

?>
