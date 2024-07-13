<?php

$db = new PDO(
	'mysql:host=' . $dbip . ';dbname=' . $dbname . ';charset=utf8',
	'' . $dbuser . '',
	'' . $dbpsswd . ''
);

?>