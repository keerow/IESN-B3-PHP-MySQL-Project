<?php
//Connection info
$DB_HOST = '192.168.1.16';
$DB_USER = 'user';
$DB_PASS = 'root';
$DB_NAME = 'MyDB';
$message = "";

//Connects to DB using credentials
try {
	$connect = new PDO("mysql:host = $DB_HOST; dbname = $DB_NAME", $DB_USER, $DB_PASS);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $error){
    $message = $error->getMessage();
}
?> 