<body>
<div id="envelope">
<?php
	if(isset($_GET['error'])){
		if($_GET['error'] == "badid"){
			echo "<p class=\"error\">Invalid ID entered.</p>";
		}else if($_GET['error'] == "notdemon"){
			echo "<p class=\"error\">A level that wasn't rated demon was entered.</p>";
		}else if($_GET['error'] == "false"){
			echo "<p class=\"error\">This level is currently not in the list. Add it down below?</p>";
		}else if($_GET['error'] == "placeerror"){
			echo "<p class=\"error\">An invalid placement was entered.</p>";
		}
	}
?>
<form action="" class="sort" method="get">
	<b>Sort By:</b>
	<button type="submit" name="sort" value="top">PLACEMENT</button>
	<button type="submit" name="sort" value="name">NAME</button>
	<button type="submit" name="sort" value="id">ID</button>
</form>
<form action="" class="sort" method="get" id="searchform">
	<input type="text" name="search" placeholder="Search Level Name" maxlength="20"><img type="submit" onclick="document.getElementById('searchform').submit();" src="images/search.png">
</form>

<form action="" method="get">
	Show <input type="number" name="show"> levels per page
	<button type="submit">Submit</button>
</form>

<?php
	$sort = 'place';
	if(isset($_GET['sort'])){
		switch($_GET['sort']){
			case 'top':
				$sort = 'place';
				break;
			case 'name':
				$sort = 'name';
				break;
			case 'id':
				$sort = 'id';
				break;
			default:
				break;
		}
	}
	
	if(isset($_GET['show'])){
		$show = $_GET['show'];
	}else{
		$show = 10;
	}
	
	if(isset($_GET['search'])){
		$res = mysqli_query($connect, "SELECT id, name, difficulty, creator, place FROM levels WHERE name LIKE '".$_GET['search']."%' ORDER BY " . $sort . ";");
	}else{
		$res = mysqli_query($connect, "SELECT id, name, difficulty, creator, place FROM levels ORDER BY " . $sort . ";");
	}
	
	$rowCount = mysqli_num_rows($res);
	$rows = [];
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 0;
	}
	
	$rowCount -= $page * $show;
	
	// The amount shown cannot be more than the amount allowed
	if($rowCount > $show){
		$rowCount = $show;
	}
	
	while($row = mysqli_fetch_array($res, MYSQLI_NUM))
	{
		$rows[] = $row;
	}
	
	$rowsJS = json_encode(array_slice($rows, $show * $page, $show));
?>

<div id="wrapper">
</div>

<form action="update.php" method="post" id="placeForm">
	<p><b>Update Existing Placement</b></p>
	<p><input type="number" placeholder="ID" name="levelChange" id="nivel"></p>
	<p><input type="number" placeholder="New Placement" name="place"></p>
	<p><button type="submit" value="Save Changes">Submit</button></p>
</form>

<script>	
	<?php
		echo '
	var arraySQL = ' . $rowsJS . ';
	var rowCount = ' . $rowCount . ';';
	?>

	function create(id, name, diff, create, place){
		var span = document.createElement("div");
		var spanMini = document.createElement("div");
		var placement = document.createElement("div");
		span.onclick = function(){
			for(var i = 0; i < document.getElementsByClassName("levelWrap").length; i++){
				document.getElementsByClassName("levelWrap")[i].style.border = "0";
			}
			span.style.border = "2px solid #9000b0";
			document.getElementById("nivel").value = id;
		}
		span.className = "levelWrap";
		spanMini.className = "info";
		placement.className = "number";
		
		var p1 = document.createElement("span");
		var p2 = document.createElement("span");
		var placeNum = document.createElement("span");
		p1.className = "bigText";
		p2.className = "smallText";
		p1.innerHTML = name + " by " + create + "<br>";
		p2.innerHTML = diff + "<br>" + id;
		placeNum.innerHTML = place;
		
		spanMini.appendChild(p1);
		spanMini.appendChild(p2);
		placement.appendChild(placeNum);
		
		span.appendChild(placement);
		span.appendChild(spanMini);
		//document.getElementById("wrapper").insertBefore(span, document.getElementById("placeForm"));
		document.getElementById("wrapper").appendChild(span);
	}
	
	function showup(){
		document.getElementById("search").style.display = "initial";
	}
	
	for(var i = 0; i < rowCount; i++){
		create(arraySQL[i][0], arraySQL[i][1], arraySQL[i][2], arraySQL[i][3], arraySQL[i][4]);
	}

</script>