<?php
session_start();

if (isset($_POST['requestArchive']) && !empty($_POST['requestArchive'])) {
  // Create a connection.
  require('../../config.php');
  $userId = $_SESSION['userId'];

  try {

    // Check if article exists prior to saving
    $statement = $DB->prepare("SELECT * FROM archive WHERE userId = :userId");
    $statement->execute(array(':userId' => $userId));
    $count = $statement->rowCount();

    $listofArchive = array();
    // If there is more than one result, send them over
    if ($count > 0) {
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      while ($row = $statement->fetch()) {

        // $privateBool is required because of Handlebars templating
        // May be more efficient to change the type of table instead
        $privateBool = false;
        if ($row["private"] == 1) {
          $privateBool = true;
        }

        $listofArchive[] = array("archiveId" => $row["archiveId"], "archiveName" => $row["archiveName"], "archiveIcon" => $row["icon"], "private" => $privateBool);
      }

      echo json_encode($listofArchive);
		}


  } catch (PDOException $ex) {
    echo "An error occurred in reading or writing to archive.";
  }
  $DB = null;
}
?>
