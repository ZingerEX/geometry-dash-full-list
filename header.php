<body>
<main id="envelope">

<p id="desc">The Geometry Dash Exhaustive List aims to roughly rank every single demon in Geometry Dash according to their relative difficulty.<br>
Anybody is able to contribute by adding a level or modifying the placement of any level.<br>
I ask that you try to place levels accurately according to how you view their difficulty.</p>

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
		}else if($_GET['error'] == "duplicate"){
			echo "<p class=\"error\">This level is already in the list.</p>";
		}
	}
	
	if(isset($_GET['sort'])){
		$sort = $_GET['sort'];
	}else{
		$sort = "";
	}
	if(isset($_GET['show'])){
		$show = $_GET['show'];
	}else{
		$show = "''";
	}
	if(isset($_GET['search'])){
		$search = $_GET['search'];
	}else{
		$search = "";
	}
	if(isset($_GET['page'])){
		$page = intval($_GET['page']);
	}else{
		$page = "''";
	}
	
	
	if(intval($show) == 0){
		$replace = 10;
	}else{
		$replace = $show;
	}
?>

<div class="sort">
<form>
	<b>Sort By:</b>
	<button type="button" onclick="searchSwitch(value, '', '', '');" name="sort" value="top">PLACEMENT</button>
	<button type="button" onclick="searchSwitch(value, '', '', '');" name="sort" value="name">NAME</button>
	<button type="button" onclick="searchSwitch(value, '', '', '');" name="sort" value="id">ID</button>
</form>

<form onsubmit="children[1].click(); return false;">
	<label>	Show
		<input type="number" style="width:60px" name="show" value="<?php echo $replace ?>">
		levels per page </label>
	<button type="button" onclick="searchSwitch('', document.getElementsByName('show')[0].value, '', '');">Submit</button>
</form>
</div>

<div class="sort">
<form id="searchform" onsubmit="children[1].click(); return false;">
	<label>
		<b>SEARCH:</b><br>
		<input type="text" name="search" placeholder="Search Level Name" maxlength="20" value="<?php echo $search ?>">
	</label>
	<img onclick="searchSwitch('', '', document.getElementsByName('search')[0].value, '');" src="images/search.png" alt="SEARCH">
</form>

<button type="button" id="clear" onclick="searchSwitch('', '', 'nullificationprocessingdata', '');">CLEAR SEARCH</button>
</div>

<script>
	<?php
	echo '
		var sort = "' . $sort . '";
		var show = ' . $show . ';
		var search = "' . $search . '";
		var page = ' . $page . ';';
	?>

function searchSwitch(one, two, three, four){
	if(one.length > 0){
		sort = one;
		page = 0;
	}else if(two.length > 0){
		show = two;
		page = 0;
	}else if(three.length > 0){
		search = three;
		page = 0;
	}else if(four.toString().length > 0){
		page = Number(four) - 1;
		if(page > maxPage - 1){
			page = maxPage - 1;
		}
		if(page < 0){
			page = 0;
		}
	}
	
	var searchString = "";
	if(sort != ""){
		searchString += "sort=" + sort + "&";
	}
	if(show != ""){
		searchString += "show=" + show + "&";
	}
	if(search != "" && search != "nullificationprocessingdata"){
		searchString += "search=" + search + "&";
	}
	if(page != ""){
		searchString += "page=" + page + "&";
	}
	if(searchString[searchString.length - 1] == "&"){
		searchString = searchString.slice(0, searchString.length - 1);		//remove last character "&"
	}
	window.location.hash = "";
	window.location.search = searchString;
}

</script>

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
	
	$page = intval($page);
	
	if(isset($_GET['search'])){
		$filtered = mysqli_query($connect, "SELECT id, name, difficulty, creator, place FROM levels WHERE name LIKE '".$_GET['search']."%' ORDER BY " . $sort . " LIMIT " . $page*$show . ", " . $show . ";");	//	LIMIT A,B selects B rows starting from row A
		$res = mysqli_query($connect, "SELECT COUNT(id) FROM levels WHERE name LIKE '".$_GET['search']."%' ORDER BY " . $sort . ";");
	}else{
		$filtered = mysqli_query($connect, "SELECT id, name, difficulty, creator, place FROM levels ORDER BY " . $sort . " LIMIT " . $page*$show . ", " . $show . ";");
		$res = mysqli_query($connect, "SELECT COUNT(id) FROM levels ORDER BY " . $sort . ";");
	}
	
	$rowCount = mysqli_num_rows($filtered);
	$fullCount = mysqli_fetch_array($res)[0];
	
	$rows = [];
	
	// The amount shown cannot be more than the amount allowed
	if($rowCount > $show){
		$rowCount = $show;
	}
	
	while($row = mysqli_fetch_array($filtered, MYSQLI_NUM))
	{
		$rows[] = $row;
	}
	
	$rowsJS = json_encode($rows);
?>

<form onsubmit="children[1].click(); return false;" action="#" class="pages">
	<b class="arrow" onclick="searchSwitch('', '', '', <?php echo $page ?>);"><</b>
	<span class="pageContainer">
		<label> Page <input type="number" name="page" style="width:40px" value="<?php echo $page+1 ?>"> of <?php echo ceil($fullCount/$show) ?> pages </label>
		<button type="submit" style="width:40px" onclick="searchSwitch('', '', '', document.getElementsByName('page')[0].value);">GO</button>
	</span>
	<b class="arrow" onclick="searchSwitch('', '', '', <?php echo $page+2 ?>);">></b>
</form>

<div id="wrapper">
</div>

<form onsubmit="children[1].click(); return false;" action="#" class="pages">
	<b class="arrow" onclick="searchSwitch('', '', '', <?php echo $page ?>);"><</b>
	<span class="pageContainer">
		<label> Page <input type="number" name="page" style="width:40px" value="<?php echo $page+1 ?>"> of <?php echo ceil($fullCount/$show) ?> pages </label>
		<button type="submit" style="width:40px" onclick="searchSwitch('', '', '', document.getElementsByName('page')[1].value);">GO</button>
	</span>
	<b class="arrow" onclick="searchSwitch('', '', '', <?php echo $page+2 ?>);">></b>
</form>

<form action="update.php" method="post" id="placeform">
	<p><b>Update Existing Placement</b></p>
	
	<p><label>ID: <input type="number" placeholder="4284013" name="levelChange" id="nivel"></label></p>
	<p><label>Place: <input type="number" placeholder="20" name="place"></label></p>
	<p><button type="submit" value="Save Changes">Submit</button></p>
</form>

<script>
	<?php
		echo '
	const arraySQL = ' . $rowsJS . ';
	const rowCount = ' . $rowCount . ';
	const maxPage = ' . ceil($fullCount/$show) . ';';
	?>

	function create(id, name, diff, create, place){
		const span = document.createElement("div");
		const spanMini = document.createElement("div");
		const placement = document.createElement("div");
		span.onclick = function(){
			for(var i = 0; i < document.getElementsByClassName("levelWrap").length; i++){
				document.getElementsByClassName("levelWrap")[i].style.border = "0";
			}
			span.style.border = "2px solid rgb(255, 0, 155)";
			document.getElementById("nivel").value = id;
		}
		span.className = "levelWrap";
		span.id = "no" + place.toString();
		spanMini.className = "info";
		placement.className = "number";
		
		const p1 = document.createElement("span");
		const p2 = document.createElement("span");
		const placeNum = document.createElement("span");
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