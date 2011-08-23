<?php

include_once 'classes/database.php';
include_once 'layout.php';

top();

$db = new database();

echo "kiitos " . $db->get_name_by_id($_GET['id_person']) . "!\n";



bottom();

?>
