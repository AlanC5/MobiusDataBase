<?php
// Updates the archive (privacy, symbol, title, description) or delete
// Use archiveId and userId to prevent user from updating or deleting things they don't own (BETA)
// Detect if PDO executed successfully

  if (isset($_POST['update']) && !empty($_POST['update'])) {
    require('../../config.php');

    session_start();

    $userId = $_SESSION['userId'];
    $archiveId = $_SESSION['archiveId'];

    if($_POST['update'] == "privacy") {
      $newPrivacy = $_POST['newPrivacy'];
      $update = $DB->prepare("UPDATE archive SET private = :privacy WHERE archiveId = :archiveId");
      $update->execute(array(':privacy' => $newPrivacy, ':archiveId' => $archiveId));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "symbol") {
      $newSymbol = $_POST['newSymbol'];
      $update = $DB->prepare("UPDATE archive SET icon = :icon WHERE archiveId = :archiveId");
      $update->execute(array(':icon' => $newSymbol, ':archiveId' => $archiveId));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "title") {
      $newTitle = $_POST['newTitle'];
      $update = $DB->prepare("UPDATE archive SET archiveName = :archiveName WHERE archiveId = :archiveId");
      $update->execute(array(':archiveName' => $newTitle, ':archiveId' => $archiveId));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "description") {
      $newDescription = $_POST['newDescription'];
      $update = $DB->prepare("UPDATE archive SET description = :description WHERE archiveId = :archiveId");
      $update->execute(array(':description' => $newDescription, ':archiveId' => $archiveId));
      $count = $update->rowCount();
      if ($count > 0) {
        echo "Success";
      }
    }

    if($_POST['update'] == "delete") {
      $delete = $DB->prepare("DELETE FROM archive WHERE archiveId = :archiveId");
      $delete->execute(array(':archiveId' => $archiveId));
      $count = $delete->rowCount();
      if ($count > 0) {
        echo "Deleted";
      }
    }

    if($_POST['update'] == "deleteArticle") {
      $articleId = $_POST['article'];
      $delete = $DB->prepare("DELETE FROM article WHERE articleId = :articleId");
      $delete->execute(array(':articleId' => $articleId));
      $count = $delete->rowCount();
      if ($count > 0) {
        echo "Deleted";
      }
    }
  }

 ?>
