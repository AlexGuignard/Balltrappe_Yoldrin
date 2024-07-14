<?php
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

function generateRandomString($length = 10) {
	return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

$result = get_first_line_db($db, "SELECT login,email,user_icon FROM users where id = '" . $_SESSION["user_id"] . "'");
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
	</form>
</div>
<script>
	function change_image_file(){
		document.getElementById("select_image").click( )
	}
</script>