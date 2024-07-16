<script>
  async function strHash(a, b) {
  b = b || 'SHA-256';
  var c = new TextEncoder().encode(a),
      d = await window.crypto.subtle.digest(b, c),
      e = Array.from(new Uint8Array(d)),
      f = e.map(function(c) {
        return c.toString(16).padStart(2, '0');
      }).join('');
  return f;
}
</script>
<script>
function cleartarget(){
  history.replaceState && history.replaceState(
      null, '', location.pathname + location.search.replace(/[\?&]target=[^&]+/, '').replace(/^&/, '?')
  );
  history.replaceState && history.replaceState(
      null, '', location.pathname + location.search.replace(/[\?&]del=[^&]+/, '').replace(/^&/, '?')
  );
}
</script>
<?php

function get_first_line_db($db, $sql) {

	echo ("<script>console.log(`" . $sql . "`)</script>");
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
		$counter1++;
	}
	return $a;
}

function generateRandomString($length = 10) {
	return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
?>