<?php
if (isset($_POST["new_record"])) {
	if ($_POST['category'] == "big") {
		$sql = "INSERT INTO hunting_records(owner_id,category,name,weight,tag_number,description) VALUES('" . $_SESSION["user_id"] . "','" . $_POST['category'] . "','" . $_POST['name'] . "','" . $_POST['weight'] . "','" . $_POST['tag'] . "','" . $_POST['desc'] . "')";
		$records = get_first_line_db($db, $sql);
	} else {
		$sql = "INSERT INTO hunting_records(owner_id,category,name,count) VALUES('" . $_SESSION["user_id"] . "','" . $_POST['category'] . "','" . $_POST['name'] . "','" . $_POST['nb'] . "')";
		$records = get_first_line_db($db, $sql);
	}
}
if (isset($_REQUEST["delete_id"])) {
	$sql = "delete from hunting_records where id ='" . $_REQUEST["delete_id"] . "'";
	echo ($sql);
	$records = get_first_line_db($db, $sql);
	echo ("
		<script>
			window.location.refresh()
		</script>");
}

?>
<div>
	<form method="post" target="/target=records" id="new_record_form">
		<h1>Ajouter un kill</h1>
		<input hidden name="new_record">
		<input hidden name="category" id="category">
		<div><label>Espèce , Catégorie</label>
		<select name="category" id="select_category" onchange="update_category()">
		  <option value="">Choisissez une gatégorie</option>
		  <option value="big">Grand gibier</option>
		  <option value="small">Petit gibier</option>
		  <option value="bad">Prédateur,Nuisibles</option>
		</select>
		</div>
		<div><label>Nom</label><input type="text" name="name"></div>
		<div class="only_when_big"><label>Description</label><textarea name="desc"></textarea></div>
		<div class="only_when_big"><label>Poids</label><input type="number" name="weight"></div>
		<div class="only_when_not_big" ><label>Nombre</label><input type="number" name="nb"></div>
		<div class="only_when_big"><label>Numéro de bracelet</label><input type="number" name="tag"></div>
		<input type="submit" onsubmit ="check_category()">
		<a id="notif"></a>
	</form>
</div>
<div class="table_div">
<?php
$sql = "SELECT name,description,weight,tag_number,date,id FROM hunting_records where category = 'big' and  owner_id = " . $_SESSION["user_id"] . " order by date";
$records = get_all_lines_db($db, $sql);
if (!empty($records)) {
	echo ("
	<h1>Gros gibier</h1>
	<table>
	");
	echo ("
		<thead>
		<tr>
			<td>Nom</td>
			<td>Description</td>
			<td>Poids</td>
			<td>n° colier</td>
			<td>Date</td>
			<td>Supprimer</td>

		</tr>
		</thead>
		");
	$counter = 0;
	foreach ($records as $a) {
		echo ("
		<tr>
				<td>" . $records[$counter]["name"] . "</td>
				<td>" . $records[$counter]["description"] . "</td>
				<td>" . $records[$counter]["weight"] . "</td>
				<td>" . $records[$counter]["tag_number"] . "</td>
				<td>" . $records[$counter]["date"] . "</td>
				<td><div><a href='/?target=records&delete_id=" . $records[$counter]["id"] . "'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg></a></div></td>
			</tr>
			");
		$counter++;
	}
	echo ("</table>");
}
$sql = "SELECT name,count,date,id  FROM hunting_records where category = 'small' and  owner_id = " . $_SESSION["user_id"] . " order by date";
$records = get_all_lines_db($db, $sql);
if (!empty($records[0])) {
	echo ("
	<h1>Petit gibier</h1>
	<table>
	");
	echo ("
		<thead>
		<tr>
			<td>Nom</td>
			<td>Nombre</td>
			<td>Date</td>
			<td>Supprimer</td>

		</tr>
		</thead>
		");
	$counter = 0;
	foreach ($records as $a) {
		echo ("
			<script>console.log('" . implode('|', $records[$counter]) . "')</script>
			<tr>
				<td a>" . $records[$counter]["name"] . "</td>
				<td b>" . $records[$counter]["count"] . "</td>
				<td c>" . $records[$counter]["date"] . "</td>
				<td><div><a href='/?target=records&delete_id=" . $records[$counter]["id"] . "'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg></a></div></td>
			</tr>
			");
		$counter++;
	}
	echo ("</table>");
}
?>
<?php
$sql = "SELECT name,count,date,id FROM hunting_records where category = 'bad' and owner_id = " . $_SESSION["user_id"] . " order by date";
$records = get_all_lines_db($db, $sql);
if (!empty($records[0])) {
	echo ("
	<h1>Nuisibles Prédateurs et pestes</h1>
	<table>
	");
	echo ("
		<thead>
		<tr>
			<td>Nom</td>
			<td>Nombre</td>
			<td>Date</td>
			<td>Supprimer</td>

		</tr>
		</thead>
		");
	$counter = 0;
	foreach ($records as $a) {
		echo ("
			<tr>
				<td>" . $records[$counter]["name"] . "</td>
				<td>" . $records[$counter]["count"] . "</td>
				<td>" . $records[$counter]["date"] . "</td>
				<td><div><a href='/?target=records&delete_id=" . $records[$counter]["id"] . "'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg></a></div></td>
			</tr>
			");
		$counter++;
	}
	echo ("</table>");
}
?>
</div>

<script>
	for(let b of document.getElementsByClassName("only_when_big")){
        for(let c of b.children){
            c.hidden = true
        }
        console.log(b)
		b.hidden = true
	}
	for(let b of document.getElementsByClassName("only_when_not_big")){
		for(let c of b.children){
    		c.hidden = false
		}
		b.hidden = false
	}
	function update_category(){
		document.getElementById("category").value = document.getElementById("select_category").value
		console.log(document.getElementById("category").value )
		if(document.getElementById("category").value == "big"){
			for(let b of document.getElementsByClassName("only_when_big")){
	    		for(let c of b.children){
	        		c.hidden = false
	    		}
				b.hidden = false
			}
			for(let b of document.getElementsByClassName("only_when_not_big")){
	    		for(let c of b.children){
	        		c.hidden = true
	    		}
				b.hidden = true
			}
		}else{
			for(let b of document.getElementsByClassName("only_when_big")){
        		for(let c of b.children){
            		c.hidden = true
       			}
        		console.log(b)
				b.hidden = true
			}
			for(let b of document.getElementsByClassName("only_when_not_big")){
        		for(let c of b.children){
            		c.hidden = false
        		}
				b.hidden = false
			}
		}
	}
	document.getElementById('new_record_form').addEventListener('submit', function(event) {
		if(document.getElementById("category").value == ""){
        	event.preventDefault();
			document.getElementById("notif").text = "Veuillez sélectionner une catégorie"
			setTimeout(()=>{
					document.getElementById("notif").text = ""
			},5000)
		}
	});


</script>