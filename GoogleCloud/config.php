<?php
$host = "mysql:unix_socket=/cloudsql/mobius-1:extension;dbname=mobiusTestData";
$username = "root";
$password = "";

$DB = null;
if (isset($_SERVER['SERVER_SOFTWARE']) &&
strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) {
  // Connect from App Engine.
  try{
     $DB = new pdo($host, $username, $password);
  }catch(PDOException $ex){
      die(json_encode(
          array('outcome' => false, 'message' => 'Unable to connect via app engine.')
          )
      );
  }
}
else {
  // Connect from a development environment.
  try{
     $DB = new pdo('mysql:host=127.0.0.1:3306;dbname=mobiusTestData', 'root', '<password>');
  }catch(PDOException $ex){
      die(json_encode(
          array('outcome' => false, 'message' => 'Unable to connect')
          )
      );
  }
}

?>
