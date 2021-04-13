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
	
	if(isset($_POST['levelChange'])){
		$id = $_POST['levelChange'];
		$place = $_POST['place'];
		
		require "connect.php";
		
		$sql = "SELECT id FROM levels WHERE id = " . $id . ";";
		
		$answer = mysqli_num_rows(mysqli_query($connect, $sql));
		if($answer <= 0){
			header("Location: index.php?" . $link . "error=false");
			exit();
		}
		
		$sql = "SELECT id FROM levels;";
		$answer = mysqli_query($connect, $sql);
		$rowCount = mysqli_num_rows($answer);
		
		if($place > 0 && $place <= $rowCount){
	
			$sql = "SELECT place FROM levels WHERE id = " . $id . ";";
			$answer = mysqli_query($connect, $sql);
			$result = mysqli_fetch_array($answer, MYSQLI_NUM);
			$add = 0;
			
			if($place < $result[0]){		// If moving level up
				$add = 1;					// Other levels go down 1
				$top = $result[0] - 1;
				$bottom = $place;
			}else if($place > $result[0]){	// If moving level down
				$add = -1;					// Other levels go up 1
				$top = $place;
				$bottom = $result[0] + 1;
			}else{							// Same place
				$add = 0;
				$top = $place;
				$bottom = $place;
			}
			$sql = "UPDATE levels SET place = place + " . $add . " WHERE place BETWEEN " . $bottom . " AND " . $top . ";
					UPDATE levels SET place = " . $place . " WHERE id = " . $id . ";";
			mysqli_multi_query($connect, $sql);
			$page = floor(($place - 1) / $replace);
			header("Location: index.php?" . $show . "page=".$page . "#no".$place);
			exit();
			/*if(){
					
					mysqli_stmt_bind_param($stmt, "is", $place, $id);	//inserted int, string, string
					mysqli_stmt_execute($stmt);
			}else{
				echo "error";
			}*/
		}else{
			header("Location: index.php?" . $link . "error=placeerror");
			exit();
		}
	}
?>