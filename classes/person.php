<?php

include_once 'database.php';
include_once 'cabin.php';

class person {

    public $id_cabin;
    public $id_person;
    private $person_array = array();
//    private $cabin_array = array();
    private $db;
    public $person = array(
        'name',
        'nick',
        'email',
        'etukortti',
        'info',
        'travel',
        'aamiainen',
        'illallinen',
        'lounas',
        'granny',
        'id_person',
        'id_cabin'
    );

    public function __construct($database_reference, $argument, $new_person) {
        $this->db = $database_reference;

        if ($new_person) {
            $this->filter_post($argument);

            $this->id_person = $this->db->get_unique_id(15);
            $this->person_array['id_person'] = $this->id_person;
            
            $this->db->put_query_from_array('persons', $this->person_array);
        } else {
            $id_person = $argument;

            $this->person_array = $this->db->get_person_by_id($id_person);
            $this->id_person = $this->person_array['id_person'];
            $this->id_cabin = $this->person_array['id_cabin'];
        }
    }
    
    public function set_id_cabin($id_cabin) {
        $this->id_cabin = $id_cabin;
        $this->person_array['id_cabin'] = $this->id_cabin;
        
        $this->db->update_db_person_with_cabin_id($this->id_person, $this->id_cabin);
    }
    
    public function update_person($post) {
        $this->filter_post($post);
        
        $this->db->update_db($this->person_array, 'persons', 'id_person', $this->id_person);
    }

//    public function get_name() {
//        return $this->person_array['person'];
//    }
//
//    public function get_nick() {
//        if (isset($this->person_array['nick']))
//            return $this->person_array['nick'];
//        else
//            return $this->person_array['person'];
//    }

    private function filter_post($post) {
        foreach ($this->person as $value) {
            if (isset($post[$value]))
                $this->person_array[$value] = $post[$value];
        }
//        foreach ($this->cabin as $value) {
//            if (isset($post[$value]))
//                $this->cabin_array[$value] = $post[$value];
//        }
//        $this->cabin_array['id_organization'] = $this->db->get_organization_id_by_organization($post['organization']);
    }

}

?>
