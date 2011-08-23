<?php
include_once '/classes/database.php';
include_once '/classes/form_maker.php';
include_once '/classes/tester.php';
include_once '/classes/person.php';
include_once '/layout.php';

top();

$db = new database();

$again = false;

if (isset($_POST['new_person'])) {
    $tester = new tester($db);
//        var_dump($_POST);
    if ($tester->test_new_person($_POST)) {
        $person = new person($db, $_POST, true);

        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'hytti.php?id_person=' . $person->id_person;
        echo "http://$host$uri/$extra";
        header("Location: http://$host$uri/$extra");
    }
    else
        $again = true;
}

$form_maker = new form_maker($db);

if ($again)
    $form_maker->print_form_new_person($_POST);
else
    $form_maker->print_form_new_person();

bottom();
?>