<?php
if (is_null(get_first_line_db($db, "select msg_id from users where id =" . $_SESSION["user_id"])["msg_id"])) {
	$id = "";
	$c = 0;
	do {
		$c++;
		$id = generateRandomString(8);
		var_dump("select id from users where msg_id = '" . $id . "'");
		$a = get_first_line_db($db, "select id from users where msg_id = '" . $id . "'");
	} while ($id == "" || !empty($a) && $c < 10);
	$b = get_first_line_db($db, "update users set msg_id = '" . $id . "' where id =" . $_SESSION["user_id"]);
}
;
$main_images_dir = "./images/user_uploads/";
$result = get_first_line_db($db, "SELECT login,email,user_icon FROM users where id = '" . $_SESSION["user_id"] . "'");
if (isset($_POST["edit_email"])) {
	if ($_POST["edit_email"] != $result["email"] && filter_var($_POST["edit_email"], FILTER_VALIDATE_EMAIL)) {
		//si l'email as été changé et est valide next
		if (!empty(get_first_line_db($db, "select * from wl_emails where email ='" . $_POST["edit_email"] . "'"))) {
			// si l'email est whitelist next
			get_first_line_db($db, "update users set email = '" . $_POST["edit_email"] . "' where id =" . $_SESSION["user_id"]); // update db email for active user
		} else {
			echo ('<div class="issue_update"><a>Email non whitelist</a></div>');
		}
	} elseif (!filter_var($_POST["edit_email"], FILTER_VALIDATE_EMAIL)) {
		echo ('<div class="issue_update"><a>Email non valide</a></div>');
	}
	if ($_POST["edit_login"] != $result["login"] && ctype_alnum($_POST["edit_login"])) {
		//si le login as été changé et next
		get_first_line_db($db, "update users set login = '" . $_POST["edit_login"] . "' where id =" . $_SESSION["user_id"]); // update db email for active user

	} elseif (!ctype_alnum($_POST["edit_login"])) {
		echo ('<div class="issue_update"><a>Login non valide<br>Lettres et chiffres <br>uniquement</a></div>');
	}
	if (!empty($_FILES)) {
		$tempfile = $_FILES["image"]['tmp_name'];
		$iconfile = $main_images_dir . "default_icon.png";
		$fileext = explode("/", $_FILES["image"]['type'])[1];
		$filename = "";
		while (file_exists($iconfile)) {
			$filename = generateRandomString(8) . "." . $fileext;
			$iconfile = $main_images_dir . $filename;
		}
		move_uploaded_file($tempfile, $iconfile);
		get_first_line_db($db, "update users set user_icon = '" . $filename . "' where id =" . $_SESSION["user_id"]); // update icon for active user

	}
}

$used_files = array();
array_push($used_files, "default_icon.png");
$sql = "select user_icon from users";
$a = $db->query($sql)->fetchAll()[0];

foreach ($a as $e) {
	array_push($used_files, $e);
}

$sql = "select file_name from user_images";
$b = $db->query($sql)->fetchAll();

foreach ($b as $e) {
	array_push($used_files, $e);
}

$allfiles = scandir($main_images_dir);
array_shift($allfiles);
array_shift($allfiles);
foreach ($allfiles as $e) {
	if (!in_array($e, $used_files)) {
		unlink($main_images_dir . $e);
	}
}

$result = get_first_line_db($db, "SELECT login,email,user_icon,msg_id FROM users where id = '" . $_SESSION["user_id"] . "'");
?>
<div>
	<form target="./?target=account" method="POST" enctype="multipart/form-data">
		<h1> Paramètres du compte</h1>
		<div class="icon_image" onclick="change_image_file()" style="background-image:url('./images/user_uploads/<?=$result["user_icon"]?>')">
		</div>
		<div>
			<input type="file" hidden name="image" id="select_image" accept="image/*">
			<label><input type="text" value ="<?=$result["login"]?>" name="edit_login"></label>
			<label><input type="email"  value ="<?=$result["email"]?>" name="edit_email"></label>
			<label><input type="submit"  value ="Modifier"></label>
		</div>
		<div id="msg_id_a" onclick="copyId()">
			<a type="text" readonly >Id de messages : <?=$result["msg_id"]?></a>
			<div>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
					<!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
					<path d="M280 64l40 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 128C0 92.7 28.7 64 64 64l40 0 9.6 0C121 27.5 153.3 0 192 0s71 27.5 78.4 64l9.6 0zM64 112c-8.8 0-16 7.2-16 16l0 320c0 8.8 7.2 16 16 16l256 0c8.8 0 16-7.2 16-16l0-320c0-8.8-7.2-16-16-16l-16 0 0 24c0 13.3-10.7 24-24 24l-88 0-88 0c-13.3 0-24-10.7-24-24l0-24-16 0zm128-8a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"/>
				</svg>
			</div>
			<div id="id_copied" hidden>
				<a style="color:red;">Id copié</a>
			</div>
		</div>
	</form>
</div>
<script>
	function copyId(){
		navigator.clipboard.writeText('<?=$result["msg_id"]?>');
		document.getElementById	("id_copied").hidden = false
		setTimeout	(()=>{
				document.getElementById	("id_copied").hidden = true
		},5000)
	}
	function change_image_file(){
		document.getElementById("select_image").click( )
	}
</script>