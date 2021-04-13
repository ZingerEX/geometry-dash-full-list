<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Exhaustive GD Demonlist</title>
		<meta name="description" content="The Geometry Dash Exhaustive List roughly ranks every demon in Geometry Dash">
		<meta name="keywords" content="GD, Geometry Dash, Demonlist, Demon, Extreme, Insane, Hard, Medium, Easy">
		<meta name="author" content="me">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="style.css?ver=<?php echo rand(111,999)?>" type="text/css">
		<link rel="shortcut icon" href="images/listface.png" type="image/x-icon">
	</head>

<?php
	session_start();
	if(isset($_GET['sort'])){
		$_SESSION['sort'] = $_GET['sort'];
	}
	if(isset($_GET['show'])){
		$_SESSION['show'] = $_GET['show'];
	}
	if(isset($_GET['search'])){
		$_SESSION['search'] = $_GET['search'];
	}else{
		if(isset($_SESSION['search'])){
			session_unset($_SESSION['search']);
		}
	}
	if(isset($_GET['page'])){
		$_SESSION['page'] = $_GET['page'];
	}

	require "connect.php";
	
	require "header.php";
	
	require "foot.html";
?>
</html>