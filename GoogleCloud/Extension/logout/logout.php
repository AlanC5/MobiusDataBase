<?php
session_start();
unset($_SESSION['userId']);
echo "Logged out";
?>
