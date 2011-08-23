<?php

include_once 'database.php';
include_once 'form_maker.php';
include_once 'tester.php';

class organization {

    private $db;
    public $id_organization;
    private $organization_array;
    private $form_maker;
    public $organization = array(
        'organization',
        'name',
        'info',
        'id_organization'
    );

    public function __construct($database_reference, $id_organization) {
        $this->db = $database_reference;

        $this->organization_array = $this->db->get_organization_by_id($id_organization);

        $this->id_organization = $this->organization_array['id_organization'];

        $this->form_maker = new form_maker($this->db);
    }

    public function print_incomplete_cabins_by_organization() {
        $cabins = $this->db->get_cabins_by_id_organization($this->id_organization);

        $tester = new tester($this->db);

        if (!$tester->test_exists_persons_in_cabins_of_id_organization($this->id_organization)) {
            echo('<p style="empty">' . "\n");
            echo('Kukaan ei ole vielä ilmoittautunut tälle listalle.' . "\n");
            echo('</p>');
        } else {
            echo("<p>alla vapaat hytit valitussa ainejärjestössä.</p>");

            echo '<table>';

            foreach ($cabins as $cabin) {
                $persons = array();

                $persons = $this->db->get_persons_nicks_by_id_cabin($cabin['id_cabin']);

//                var_dump($persons);

                if (count($persons) < 4 and count($persons) > 0) {
                    echo('<tr class="cabin"><td class="valinta">');
                    echo '<p class="hytti">ilmoittaudu tähän hyttiin.</p>' . "\n";

                    echo '<p bgcolor="green">';
                    $this->form_maker->print_form_select_this_cabin($cabin['id_cabin']);
                    echo '</p>';

                    echo '</td><td><table><tr><td class="nick">' . $cabin['cabin'] . '</td></tr>' . "\n" . '<tr><td>';

                    $this->print_persons($persons);

                    echo '</td></tr></table></td><td class="info"><table class="right"><tr><td><font class="luokka">' . $cabin['luokka'] . '-luokka</font></td></tr>' . "\n";
                    echo '<tr><td class="desc">' . $cabin['description'] . '</td></tr></table></td></tr>';
                    echo '<tr><td class="filler"></td><td></td><td></td></tr>';
                }
            }
            echo '</table>';
        }
    }
    
    public function print_all_cabins_by_organization() {
        $cabins = $this->db->get_cabins_by_id_organization($this->id_organization);

        $tester = new tester($this->db);

        if (!$tester->test_exists_persons_in_cabins_of_id_organization($this->id_organization)) {
            echo('<p style="empty">' . "\n");
            echo('Kukaan ei ole vielä ilmoittautunut tälle listalle.' . "\n");
            echo('</p>');
        } else {
            echo("<p>Hytit valitussa ainejärjestössä.</p>");

            echo '<table>';

            foreach ($cabins as $cabin) {
                $persons = array();

                $persons = $this->db->get_persons_nicks_by_id_cabin($cabin['id_cabin']);

//                var_dump($persons);

                if (count($persons) <= 4 and count($persons) > 0) {
                    echo '<tr class="cabin">';
                    echo '<td><table><tr><td class="nick">' . $cabin['cabin'] . '</td></tr>' . "\n" . '<tr><td>';

                    $this->print_persons($persons);

                    echo '</td></tr></table></td><td class="info"><table class="right"><tr><td><font class="luokka">' . $cabin['luokka'] . '-luokka</font></td></tr>' . "\n";
                    echo '<tr><td class="desc">' . $cabin['description'] . '</td></tr></table></td></tr>';
                    echo '<tr><td class="filler"></td><td></td><td></td></tr>';
                }
            }
            echo '</table>';
        }
    }

    private function print_persons($persons_array) {
        echo '<table>';

        foreach ($persons_array as $person)
            echo('<tr><td class="name">- ' . $person . '</td></tr>');

        echo '</table>';
    }

}

?>
