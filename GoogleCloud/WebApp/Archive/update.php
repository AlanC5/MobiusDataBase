<?php
// Updates the archive (privacy, symbol, title, description) or delete
// Use archiveid and userid to prevent user from updating or deleting things they don't own (BETA)
// Detect if PDO executed successfully

  if (isset($_POST['update']) && !empty($_POST['update'])) {
    require('../config.php');

    session_start();

    $userid = $_SESSION['userid'];
    $archiveid = $_SESSION['archiveid'];

    if($_POST['update'] == "privacy") {
      $newPrivacy = $_POST['newPrivacy'];
      $update = $DB->prepare("UPDATE archive SET private = :privacy WHERE archiveid = :archiveid");
      $update->execute(array(':privacy' => $newPrivacy, ':archiveid' => $archiveid));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "symbol") {
      $newSymbol = $_POST['newSymbol'];
      $update = $DB->prepare("UPDATE archive SET icon = :icon WHERE archiveid = :archiveid");
      $update->execute(array(':icon' => $newSymbol, ':archiveid' => $archiveid));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "title") {
      $newTitle = $_POST['newTitle'];
      $update = $DB->prepare("UPDATE archive SET archivename = :archivename WHERE archiveid = :archiveid");
      $update->execute(array(':archivename' => $newTitle, ':archiveid' => $archiveid));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "description") {
      $newDescription = $_POST['newDescription'];
      $update = $DB->prepare("UPDATE archive SET description = :description WHERE archiveid = :archiveid");
      $update->execute(array(':description' => $newDescription, ':archiveid' => $archiveid));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "delete") {
      $delete = $DB->prepare("DELETE FROM archive WHERE archiveid = :archiveid");
      $delete->execute(array(':archiveid' => $archiveid));
      $count = $delete->rowCount();
      if ($count > 0) {
        echo "Deleted";
      }
    }

    if($_POST['update'] == "deleteArticle") {
      $articleid = $_POST['article'];
      $delete = $DB->prepare("DELETE FROM article WHERE articleid = :articleid");
      $delete->execute(array(':articleid' => $articleid));
      $count = $delete->rowCount();
      if ($count > 0) {
        echo "Deleted";
      }
    }
  }

 ?>
