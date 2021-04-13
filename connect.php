<?php
	$servername = "server";
	$dbUsername = "user";
	$dbPassword = "";
	$dbName = "gdlevels";
	
	$connect = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
	
	if(!$connect){
		die("Connection Failed: " . mysql_error());
	}
?>
