<?php

include_once 'classes/database.php';
include_once 'layout.php';

top();

$db = new database();

echo "<p>kiitos " . $db->get_name_by_id($_GET['id_person']) . '!</p>' . "\n";

echo '<p>Tunnuksesi on ' . $_GET['id_person'] . ' , jolla emailisi kera pystyt muokkaamaan tietojasi myöhemmin.</p>';

bottom();

?>