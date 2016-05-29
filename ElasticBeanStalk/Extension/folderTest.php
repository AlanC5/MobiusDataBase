<?php
echo "Folder";
// Create a connection.
require('../config.php');

  try {
    // Show existing users.
    echo "Connected to DB";

    foreach($DB->query('SELECT * from user') as $row) {
            echo "<div><strong>" . $row['email'] . "</div>";
     }
  } catch (PDOException $ex) {
    echo "An error occurred in reading or writing to user.";
  }
  $DB = null;

?>
