<?php

include_once 'layout.php';
include_once 'classes/form_maker.php';
include_once 'classes/database.php';
include_once 'classes/tester.php';
include_once 'classes/person.php';

top();

$db = new database();
$form_maker = new form_maker($db);
$again = false;

if (isset($_POST['password'])) {
    $tester = new tester($db);
    if ($tester->test_password($_POST)) {
        $form_maker->print_form_existing_person($_POST['tunnus']);
    } else
        $again = true;
}
elseif (isset($_POST['existing_person'])) {
    $tester = new tester($db);
    if ($tester->test_existing_person($_POST)) {
        $person = new person($db, $_POST['id_person'], false);
        $person->update_person($_POST);
        
        echo 'Päivitys onnistui!' . "\n";
    }
    $form_maker->print_form_existing_person($_POST);
} elseif (isset($_POST['existing_person_reset'])) {
    $form_maker->print_form_existing_person($_POST['id_person']);
} else {
    if ($again)
        $form_maker->print_form_password($_POST);
    else
        $form_maker->print_form_password();
};

bottom();
?>
