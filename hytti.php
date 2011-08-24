<?php

include_once 'classes/database.php';
include_once 'classes/form_maker.php';
include_once 'classes/tester.php';
include_once 'classes/person.php';
include_once 'classes/organization.php';
include_once 'layout.php';

ob_start();

top();

$db = new database();

//$again = false;

if ((isset($_POST['new_cabin']) or isset($_POST['existing_cabin'])) and isset($_GET['id_person'])) {
    $tester = new tester($db);
//        var_dump($_POST);
    if (isset($_POST['new_cabin'])) {
        if ($tester->test_new_cabin($_POST, $_GET['id_person'])) {
            $cabin = new cabin($db, $_POST, true);
            $person = new person($db, $_GET['id_person'], false);
            $person->set_id_cabin($cabin->id_cabin);

            after_ok();
            exit;
        }
        else
           echo '<hr />';
    } elseif (isset($_POST['existing_cabin'])) {
        if ($tester->test_existing_cabin($_POST['id_cabin'], $_GET['id_person'])) {
            $person = new person($db, $_GET['id_person'], false);
            $person->set_id_cabin($_POST['id_cabin']);

            after_ok();
            exit;
        } else
            echo 'error';
    }
}

ob_end_flush();

function after_ok() {
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'kiitos.php?id_person=' . $_GET['id_person'];
//    echo "http://$host$uri/$extra";
    header("Location: http://$host$uri/$extra");
    exit;
}

$form_maker = new form_maker($db);

if (isset($_GET['id_person'])) {
    echo '<table><tr><td class="valinta">' . "\n";

    $form_maker->print_form_select_new_cabin(true);

    echo '</td><td>' . "\n";

    if (isset($_POST['new_cabin']) or isset($_POST['select_organization_for_booking']) or isset($_POST['existing_cabin']))
        $form_maker->print_form_select_organization_for_booking($_POST, true);
    else
        $form_maker->print_form_select_organization_for_booking($_POST, true);

    echo '</td></tr></table>' . "\n";

    if (isset($_POST['select_organization_for_booking'])) {
        $tester = new tester($db);
        if ($tester->test_organization_id($_POST)) {
            $organization = new organization($db, $_POST['id_organization']);
            $organization->print_incomplete_cabins_by_organization();
        }
    } elseif (isset($_POST['select_new_cabin']) or isset($_POST['new_cabin'])) {
//        if ($again)
            $form_maker->print_form_new_cabin($_POST);
//        else
//            $form_maker->print_form_new_cabin();
    }
}

bottom();
?>