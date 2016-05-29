<?php
    session_start();
    require('../config.php');
    $userId = $_SESSION['userId'];
    $userStatement = $DB->prepare("SELECT * FROM user WHERE userId = :userId");
    $userStatement->execute(array(':userId' => $userId));
    $userStatement->setFetchMode(PDO::FETCH_ASSOC);

    $userStatementRow = $userStatement->fetch();
    $name =  $userStatementRow["name"];

    echo json_encode($name);
?>
