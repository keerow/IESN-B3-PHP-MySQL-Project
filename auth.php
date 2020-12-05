<?php
session_start();

//Connection info
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'Techniques_web';

//Connects to database
$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

//Tries connection
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
