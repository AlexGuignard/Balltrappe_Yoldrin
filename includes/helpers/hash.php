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
}
</script>
<?php

function get_first_line_db($db, $sql) {
	$a = $db->query($sql)->fetchAll();
	if (empty($a)) {return array();}
	return $a[0];
}

?>