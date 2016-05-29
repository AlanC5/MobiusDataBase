
<?php
require('configOOP.php');
require('user.php');

$connection = new connection();
$user = new user($connection);

echo "PreChecking";


$user->insertUser("Midoriya", "Deku@net.com", "blame");

echo "Checking";




?>