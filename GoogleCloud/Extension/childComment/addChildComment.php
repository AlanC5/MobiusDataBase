<?php

session_start();


if (isset($_POST['addChildComment']) && !empty($_POST['addChildComment'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $pAnnotationId = $_POST['pAnnotationId'];

  try {
    // Check if parentComment Exists

    $statement = $DB->prepare("SELECT * FROM parentAnnotation WHERE pAnnotationId = :pAnnotationId");
    $statement->execute(array(':pAnnotationId' => $pAnnotationId));
    $count = $statement->rowCount();

    // If it does exist then save the childAnnotation
    if ($count > 0) {
      //A commentAnnotation has no likes when created
		$comment = $_POST['comment'];
        $likes = 0;

        $insert = $DB->prepare("INSERT INTO childrenAnnotation (cAnnotationId, pAnnotationId, userId, comment, likes) VALUES (:cAnnotationId, :pAnnotationId, :userId, :comment, :likes)");

        // Insert data
        if ($insert->execute(array(':cAnnotationId' => NULL, ':pAnnotationId' => $pAnnotationId, ':userId' => $userId, ':comment' => $comment, ':likes' => $likes))) {

          // Returns the last inserted Primary Key
          $cAnnotationId = $DB->lastInsertId();

          // Update numChildrenAnnotation count of parentAnnotation table
          $statement = $DB->prepare("UPDATE parentAnnotation SET numChildrenAnnotation = :numChildrenAnnotation WHERE pAnnotationId = :pAnnotationId");
          $statement->execute(array(':numChildrenAnnotation' => $numChildrenAnnotation+1, ':pAnnotationId' => $pAnnotationId));

      }
      else {
        echo "Failed to save";
      }

    }
  } catch (PDOException $ex) {
    echo "An error occurred while saving child comment.";
  }
  $DB = null;
}

?>
