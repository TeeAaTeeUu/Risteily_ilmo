<?php

include_once 'classes/form_maker.php';
include_once 'classes/organization.php';
include_once 'classes/database.php';
include_once 'classes/cruise.php';
include_once 'classes/tester.php';
include_once 'layout.php';

top();

$db = new database();
$form_maker = new form_maker($db);

if (isset($_POST['select_organization_for_booking'])) {
    $tester = new tester($db);
    if ($tester->test_organization_id($_POST)) {
        $form_maker->print_form_select_organization_for_booking($_POST);

        $organization = new organization($db, $_POST['id_organization']);
        $organization->print_all_cabins_by_organization();
    }
}
else {
    $form_maker->print_form_select_organization_for_booking();
    
    $cruise = new cruise($db);
    $cruise->print_stats();
}
bottom();
?>