<?php

////Connect to MySQL
//$dbtype = "mysql";
//$dbhost = "nahsifljaws01"; // Host name 
//$dbuser = "slotadmin"; // Mysql username 
//$dbpass = "slotadmin"; // Mysql password 
//$dbname = "slotting"; // Database name 
//$conn1 = new PDO("{$dbtype}:host={$dbhost};dbname={$dbname};charset=utf8", $dbuser, $dbpass, array());
//$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



//Connect to MySQL
$dbtype = "mysql";
$dbhost = "127.0.0.1"; // Host name 
$dbuser = "bentley"; // Mysql username 
$dbpass = "dave41"; // Mysql password 
$dbname = "slotting"; // Database name 
$conn1 = new PDO("{$dbtype}:host={$dbhost};dbname={$dbname};charset=utf8", $dbuser, $dbpass, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));