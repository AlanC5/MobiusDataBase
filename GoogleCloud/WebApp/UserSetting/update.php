<?php
// Updates Title and Password
if (isset($_POST['updateUser']) && !empty($_POST['updateUser'])) {
  require('../config.php');

  session_start();

  $userid = $_SESSION['userid'];

  if($_POST['updateUser'] == "name") {
    $newName = $_POST['newName'];
    $update = $DB->prepare("UPDATE user SET name = :name WHERE userid = :userid");
    $update->execute(array(':name' => $newName, ':userid' => $userid));
    echo "Updated";
  }

  if($_POST['updateUser'] == "password") {
    if (!empty($_POST['oldPassword']) && !empty($_POST['newPassword'])) {
      $oldPass = $_POST['oldPassword'];
      $newPass = $_POST['newPassword'];
      $select = $DB->prepare("SELECT BINARY password FROM user WHERE userid = :userid");
      $select->execute(array(':userid' =>$userid));
      $select->setFetchMode(PDO::FETCH_ASSOC);
      $row = $select->fetch();
      $currentPass = $row['BINARY password'];

      if ($oldPass == $currentPass) {
        $update = $DB->prepare("UPDATE user SET password = :password WHERE userid = :userid");
        $update->execute(array(':password' => $newPass, ':userid' => $userid));
        echo "Updated";
      }

      if ($oldPass != $currentPass) {
        echo "Incorrect";
      }
    }
  }

}

?>
