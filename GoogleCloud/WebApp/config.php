<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "mobius";

//connecting to DB
try {
	 $DB = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	 $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch (PDOException $e) {
    echo $e->getMessage();
}

/*
mysql_connect("$host", "$username", "$password") or die(mysql_error());
//connect to db
mysql_select_db("$dbname") or die(mysql_error());
//selecting the database to use
*/
?>