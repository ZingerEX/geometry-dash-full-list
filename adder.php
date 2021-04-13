<?php
	session_start();
	
	if(isset($_SESSION['sort'])){
		$sort = "sort=" . $_SESSION['sort'] . "&";
	}else{
		$sort = "";
	}
	if(isset($_SESSION['show'])){
		$show = "show=" . $_SESSION['show'] . "&";
		if(intval($_SESSION['show']) == 0){
			$replace = 10;
		}else{
			$replace = $_SESSION['show'];
		}
	}else{
		$show = "";
		$replace = 10;
	}
	if(isset($_SESSION['search'])){
		$search = "search=" . $_SESSION['search'] . "&";
	}else{
		$search = "";
	}
	if(isset($_SESSION['page'])){
		$pageB = "page=" . intval($_SESSION['page']) . "&";
	}else{
		$pageB = "";
	}
	
	$link = $sort . $show . $search . $pageB;
	session_unset();
	session_destroy();
	
	
	if(isset($_GET['level'])){
		$id = $_GET['level'];
	}
	$object = file_get_contents("https://gdbrowser.com/api/level/".$id, FILE_IGNORE_NEW_LINES);
	if($object == "-1" || $object == ""){
		header("Location: index.php?" . $link . "error=badid");
		exit();
	}
	$data = json_decode($object);
	
	if($data->stars < 10){
		header("Location: index.php?" . $link . "error=notdemon");
		exit();
	}
	
	$id = intval($data->id);
	$diff = $data->difficulty;
	$creator = $data->author;
	$name = $data->name;
	
	require "connect.php";
	
	$result = mysqli_query($connect, "SELECT id FROM levels WHERE id = ".$id);
	if(mysqli_num_rows($result) > 0){
		header("Location: index.php?" . $link . "error=duplicate");
		exit();
	}
	$sql = "INSERT INTO levels VALUES(?, ?, ?, ?, ?);";
	$stmt = mysqli_stmt_init($connect);
	$rows= 0;
	if(mysqli_stmt_prepare($stmt, $sql)){
			$result = mysqli_query($connect, "SELECT id FROM levels;");
			$rows = mysqli_num_rows($result) + 1;	// The last row in table + 1
			
			$page = floor(($rows - 1) / $replace);	//Page to start at
			
			mysqli_stmt_bind_param($stmt, "isssi", $id, $name, $diff, $creator, $rows);	//inserted int, string, string
			mysqli_stmt_execute($stmt);
	}else{
		echo "error";
		$page = 0;
	}
	header("Location: index.php?" . $show . "page=".$page . "#no" . $rows);
?>