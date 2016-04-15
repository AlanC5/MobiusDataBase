
<?php
require('configOOP.php');
require('user.php');

$connection = new connection();
$user = new user($connection);

echo "PreChecking";


$user->insertUser("Lucifer", "Dark@net.com", "SinsBro");

echo "Checking";




?>