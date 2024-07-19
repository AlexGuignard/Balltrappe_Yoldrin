<?php
include './includes/helpers/config.php';
include './includes/helpers/db.php';

function get_first_line_db($db, $sql) {
	$a = $db->query($sql)->fetchAll();
	if (empty($a)) {return array();}
	$counter1 = 0;
	$counter2 = 0;
	foreach ($a as $line) {
		foreach ($line as $key) {
			unset($a[$counter1][$counter2]);
			$counter2++;
		}
		$counter1++;
	}
	return $a[0];
}
function get_all_lines_db($db, $sql) {
	$a = $db->query($sql)->fetchAll();
	if (empty($a)) {return array();}
	$counter1 = 0;
	$counter2 = 0;
	foreach ($a as $line) {
		foreach ($line as $key) {
			unset($a[$counter1][$counter2]);
			$counter2++;
		}
		$counter2 = 0;
		$counter1++;
	}
	return $a;
}

$from = $_REQUEST["from"];
$to = $_REQUEST["to"];
$message = $_REQUEST["message"];
echo ("SELECT id from friend_requests where from_id = '" . $to . "' or to_id = (SELECT msg_id from users where id = '" . $to . "')");

$conv_id = get_first_line_db($db, "SELECT id from friend_requests where to_id = '" . $to . "' or from_id = (SELECT msg_id from users where id = '" . $to . "')")["id"];
echo ("INSERT INTO messages(friendship_id,content,sender_id) VALUES ('" . $conv_id . "','" . $message . "','" . $from . "')");
$send = get_first_line_db($db, "INSERT INTO messages(friendship_id,content,sender_id) VALUES ('" . $conv_id . "','" . $message . "','" . $from . "')");

?>