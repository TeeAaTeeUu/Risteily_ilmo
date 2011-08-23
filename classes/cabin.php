<?php

include_once 'database.php';
include_once 'person.php';

class cabin {

    private $db;
    public $id_cabin;
    public $id_organization;
    private $cabin_array;
    public $cabin = array(
        'luokka',
        'cabin',
        'description',
        'id_organization'
    );

    public function __construct($database_reference, $argument, $new_cabin) {
        $this->db = $database_reference;

        if ($new_cabin) {
            $this->filter_post($argument);

            $this->id_cabin = $this->db->get_unique_id(15);
            $this->cabin_array['id_cabin'] = $this->id_cabin;
        } else {
            $this->cabin_array = $this->db->get_cabin_by_id($argument);
            $this->id_cabin = $this->cabin_array['id_cabin'];
        }
        $this->id_organization = $this->cabin_array['id_organization'];

        if ($new_cabin)
            $this->db->put_query_from_array('cabins', $this->cabin_array);
    }

    private function filter_post($post) {
//        foreach ($this->person as $value) {
//            if (isset($post[$value]))
//                $this->person_array[$value] = $post[$value];
//        }
        foreach ($this->cabin as $value) {
            if (isset($post[$value]))
                $this->cabin_array[$value] = $post[$value];
        }
    }

}

?>
