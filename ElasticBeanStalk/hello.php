
<?php
require('configOOP.php');
require('user.php');

$connection = new connection();
$user = new user($connection);


//
//$statement = $DB->prepare("INSERT INTO user (userId, email, name, password, imagefile) VALUES (:userId, :email, :name, :password, :imagefile)");
//$statement->execute(array(':userId' => NULL, ':email' => 'light@gmail.com', ':name' => 'Light Yagami', ":password" => 'locoletogcoco', ":imagefile" => 'No'));

echo "PreChecking";


$user->insertUser("L", "L@gmail.com", "Deadman");

echo "Checking";




?>