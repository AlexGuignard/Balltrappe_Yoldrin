<?php
include "./includes/helpers/config.php";
include "./includes/helpers/hash.php";
include "./includes/helpers/db.php";
session_start();
$issue = "";

function issue($a, $x) {
	if ($a == "") {
		$a .= "<dialog class='issue_popup' style='position: relative;width: 7%;height: fit-content; left:-85%;top:-80%;color: white;overflow: hidden;text-align: center;background: red;pointer-events: none;'>";
		$a .= $x;
	} else {
		$a .= "<br>" . $x;
	}
	return $a;
}

if (isset($_GET["target"])) {
	if ($_GET["target"] != "disconnect") {
		$_SESSION['target'] = $_GET["target"];
	} elseif ($_GET['target'] == "disconnect") {
		session_destroy();
		unset($_GET);
		unset($_POST);
		echo ("<script>cleartarget()</script>");
		echo ("<script>window.location.reload()</script>");
	} else {
		session_start();
	}
	unset($_GET);
}

if (isset($_POST['email'])) {
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$issue = issue($issue, "Email invalide");
	} else {
		//check if account exists with this email
		$sql_query = "SELECT * from users where email = '" . $_POST['email'] . "'";
		$result = $db->query($sql_query)->fetchAll();
		if (count($result) != 0) {
			$issue = issue($issue, "Compte déja existant avec cet Email");
		} else {
			//check if account can be created using this email
			var_dump($result);
			$sql_query = "SELECT * from wl_emails where email = '" . $_POST['email'] . "'";
			$result = $db->query($sql_query)->fetchAll();
			if (count($result) == 0) {
				$issue = issue($issue, "Email non authorisée");
			} else {
				//Create accout and login
				$sql_query = "INSERT INTO users(login,psswd,admin,email) VALUES('" . $_POST['login'] . "','" . $_POST['psswd'] . "',0,'" . $_POST['email'] . "')";
				$result = $db->query($sql_query)->fetchAll();
			}
		}
	}
} else {
	if (isset($_POST['login'])) {
		//check account and login if gud and direct to home :)
		$sql_query = "SELECT * from users where ( login = '" . $_POST['login'] . "' or  email = '" . $_POST['login'] . "' ) and psswd = '" . $_POST['psswd'] . "'";
		$result = $db->query($sql_query)->fetchAll();
		if (count($result) > 0) {
			$_SESSION['target'] = "home";
			$_SESSION['user_id'] = $result[0]["id"];
			$_SESSION['admin'] = $result[0]["admin"];
		} else {
			if ($issue == "") {
				$issue = issue($issue, "Informations non valides");
			}
		}
	}
}

//replace useless warnings
if (strlen($issue) > 1) {
	if (str_contains($issue, "Login invalide")) {
		$issue = str_replace("Informations non valides<br>", "", $issue);
	}
	if (str_contains($issue, "Email invalide")) {
		$issue = str_replace("Informations non valides<br>", "", $issue);
		$issue = str_replace("Email non authorisée<br>", "", $issue);
	}
	if (str_contains($issue, "Email invalide")) {
		$issue = str_replace("Informations non valides<br>", "", $issue);
	}
	echo ($issue . "</dialog>");
	$_POST = array();
	session_destroy();

}

if (isset($_SESSION['target'])) {
	if ($_SESSION['target'] != "login") {
		include "./includes/header.php";
	}
	include './includes/styles/' . $_SESSION['target'] . '.html';
	switch ($_SESSION['target']) {
	case "home":
		include "home.php";
		break;
	case "account":
		include "account.php";
		break;
	case "messages":
		include "messages.php";
		break;
	case "records":
		include "records.php";
		break;
	case "upload":
		include "upload.php";
		break;
	case "library":
		include "library.php";
		break;
	case "login":
		include "login.php";
		break;
	default:
		include "home.php";
		break;
	}
} else {
	include './includes/styles/login.html';
	include './login.php';
}

include "./includes/footer.php";
?>