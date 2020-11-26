<?php

error_reporting(E_WARNING);

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'admin';

$conn = new mysqli ($host,$user,$password,$db);
if($conn->connect_error){
	echo "not connected";
}

 ?>