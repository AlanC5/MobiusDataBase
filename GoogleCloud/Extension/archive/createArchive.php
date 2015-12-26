<?php
session_start();

if (isset($_POST['createArchive']) && !empty($_POST['createArchive'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];
  $archiveName = $_POST['archiveName'];

  try {

    // Check if archive exists prior to saving
    $statement = $DB->prepare("SELECT * FROM archive WHERE userId = :userId AND archiveName = :archiveName");
    $statement->execute(array(':userId' => $userId, ':archiveName' => $archiveName));
    $count = $statement->rowCount();

    $listofArchive = array();
    // If there is more than zero results, exit becuase archive exists;
    if ($count > 0) {
      echo "Archive Exists Already";
    }

    // else insert new Archive and return archive id
    else {
      $icon = $_POST['archiveIcon'];
      $description = $_Post['archiveDescription'];
      $privacy = $_POST['archivePrivacy'];

      $insert = $DB->prepare("INSERT INTO archive (archiveId, userId, archiveName, icon, description, private, saveDate) VALUES (:archiveId, :userId, :archiveName, :icon, :description, :private, NOW())");

      if ($insert->execute(array('archiveId' => NULL, ':userId' => $userId, ':archiveName' => $archiveName, ':icon' => $icon, ':description' => $description, ':private' => $privacy))) {

        echo $DB->lastInsertId();
        // echo "Successfully Saved";
      }
      else {
        echo "Failed to Saved";
      }
    }


  } catch (PDOException $ex) {
    echo "An error occurred in reading or writing to archive.";
  }
  $DB = null;
}
?>
