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

class returnmessage {
}
session_start();

$msg_id = get_first_line_db($db, "SELECT msg_id FROM users where id = '" . $_SESSION["user_id"] . "'")["msg_id"];
$conv_id = $_GET["id"];
if (!empty(get_first_line_db($db, "SELECT * FROM friend_requests where ( from_id = '" . $_SESSION["user_id"] . "' OR to_id = '" . $msg_id . "') and accepted = 1"))) {
	$result = get_all_lines_db($db, "SELECT messages.sender_id , messages.id ,content,attachements,msg_read,friendship_id,f.from_id,f.to_id , messages.time , f.from_id,f.to_id FROM messages inner join friend_requests as f on f.id = messages.friendship_id	WHERE friendship_id = '" . $conv_id . "' order by messages.time ASC");
	$e = [];
	foreach ($result as $line) {
		$te = new returnmessage;
		$te->friendship_id = $line["friendship_id"];
		$te->read = $line["msg_read"];
		$te->content = $line["content"];
		$te->attachements = $line["attachements"];
		$te->sender_name = get_first_line_db($db, "SELECT login FROM users where id = '" . $line["sender_id"] . "'")["login"];
		$a = $line["from_id"] == $_SESSION["user_id"];
		$b = "";
		$l = "";
		if ($a) {
			$b = get_first_line_db($db, "SELECT login FROM users where msg_id = '" . $line["to_id"] . "'")["login"];
			$l = $line["to_id"];
		} else {
			$b = get_first_line_db($db, "SELECT login FROM users where id = " . $line["from_id"] . "")["login"];
			$l = $line["from_id"];
		}
		$te->other_person_name = $b;

		$te->other_person_id = $l;
		array_push($e, $te);
		//get_first_line_db($db, "UPDATE messages set msg_read = 1 where id = " . $line["id"] . "");
	}
	echo json_encode($e);
} else {
	echo ("Vous ne faites pas partie des personnes dans cette discution ou elle n'existes pas.");
}

?>