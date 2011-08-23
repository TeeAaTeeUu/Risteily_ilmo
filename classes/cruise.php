<?php

include_once 'database.php';

class cruise {

    private $db;
    private $organizations;

    public function __construct($database_reference) {
        $this->db = $database_reference;
        $this->organizations = $this->db->get_organizations();
    }

    public function print_stats() {
        echo '<p>Risteilyn tilastot tähän asti</p>' ."\n";
        $cabin_num = 0;
        $person_num = 0;
        echo '<table>' . "\n";
        foreach ($this->organizations as $organization) {
            $cabins = $this->db->get_cabins_by_id_organization($organization['id_organization']);
            $persons = $this->db->get_persons_in_cabins_by_cabins_array($cabins);
            $cabin_num += count($cabins);
            $person_num += count($persons);
            
            echo '<tr><td>' . $organization['name'] . '</td><td> : </td><td>' . count($cabins) . ' hyttiä</td>';
            echo '<td> ja ' . count($persons) . ' henkilöä</td></tr>';
        }
        echo '<tr><td>Yhteensä</td><td> : </td><td>' . $cabin_num . ' hyttiä</td><td> ja ' . $person_num . ' henkilöä</td></tr></table>';
    }

}

?>
