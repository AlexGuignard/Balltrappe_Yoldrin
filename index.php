<?php
include "./includes/helpers/hash.php";
include "./includes/helpers/db.php";
session_start();
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
$issue = "";
if (isset($_POST['email']) && isset($_POST['login'])) {
	//check if account exists with this email
	$sql_query = "SELECT * from users where email = '" . $_POST['email'] . "'";
	$result = $db->query($sql_query)->fetchAll();
	if (count($result) != 0) {
		unset($_POST);
		if (strlen($issue) > 1) {
			$issue .= "<br>Compte déja existant avec cet Email";
		} else {
			$issue = "<dialog class='issue_popup' style='position: relative;width: 7%;height: fit-content; left:-85%;top:-85%;color: white;overflow: hidden;text-align: center;background: red;pointer-events: none;'>
			Compte déja existant avec cet Email";
		}
	} else {
		//check if account can be created using this email
		$sql_query = "SELECT * from wl_emails where email = '" . $_POST['email'] . "'";
		$result = $db->query($sql_query)->fetchAll();
		if (count($result) == 0) {
			unset($_POST);
			if (strlen($issue) > 1) {
				$issue .= "<br>Email non authorisée";
			} else {
				$issue = "<dialog class='issue_popup' style='position: relative;width: 7%;height: fit-content; left:-85%;top:-85%;color: white;overflow: hidden;text-align: center;background: red;pointer-events: none;'>
				Email non authorisée";
			}
		}
	}
} elseif (isset($_POST['login'])) {
	//check account and login if gud and direct to home :)
	$sql_query = "SELECT * from users where ( login = '" . $_POST['login'] . "' or  email = '" . $_POST['login'] . "' ) and psswd = '" . $_POST['psswd'] . "'";
	$result = $db->query($sql_query)->fetchAll();
	if (count($result) > 0) {
		$_SESSION['target'] = "home";
		$_SESSION['user_id'] = $result[0]["id"];
		$_SESSION['admin'] = $result[0]["admin"];
	} else {
		if (strlen($issue) > 1) {
			$issue .= "<br>Informations invalides";
		} else {
			$issue = "<dialog class='issue_popup' style='position: relative;width: 7%;height: fit-content; left:-85%;top:-85%;color: white;overflow: hidden;text-align: center;background: red;pointer-events: none;'>
				Informations invalides";
		}
	}
}
if (isset($_POST)) {
	if (isset($_POST['email'])) {
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$issue = "<dialog class='issue_popup' style='position: relative;width: 7%;height: fit-content; left:-85%;top:-85%;color: white;overflow: hidden;text-align: center;background: red;pointer-events: none;'>
				Email invalide
			";
			$_POST = array();
			session_destroy();
		}
	} elseif (isset($_POST['login'])) {
		if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
			$issue = "<dialog class='issue_popup' style='position: relative;width: 7%;height: fit-content; left:-85%;top:-85%;color: white;overflow: hidden;text-align: center;background: red;pointer-events: none;'>
				login invalide
			";
			$_POST = array();
			session_destroy();
		}
	}
}
if (strlen($issue) > 1) {
	echo ($issue . "</dialog>");
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

	}
} else {
	include './includes/styles/login.html';
	include './login.php';
}

include "./includes/footer.php";

echo ("<script>cleartarget()</script>");
?>