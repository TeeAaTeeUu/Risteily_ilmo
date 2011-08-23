<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tester
 *
 * @author TeeAaTeeUu
 */
class tester {

    private $db;
    public $person_kentat = array(
        'name' => 40,
        'nick' => 40,
        'email' => 60,
        'etukortti' => 15,
        'info' => 512,
        'bussi' => 9,
        'aamiainen' => 1,
        'illallinen' => 1,
        'lounas' => 1,
        'granny' => 1,
        'id_cabin' => 15,
        'id_person' => 15
    );
    public $cabin_kentat = array(
        'luokka' => 5,
        'cabin' => 40,
        'description' => 512,
        'id_organization' => 15,
        'id_cabin' => 15
    );
    public $ruokailu_valinta = array(
        'aamiainen',
        'illallinen',
        'lounas',
        'granny'
    );
    public $luokka_valinta = array(
        'A',
        'B',
        'C'
    );
    public $bussi_valinta = array(
        'menopaluu',
        'meno',
        'paluu',
        'ei bussia'
    );
    public $form_selite = array(
        'person' => "Nimi",
        'submit' => "Lähetteesi",
        'email' => "Email",
        'valinta' => "Ainejärjestö",
        'luokka' => "Hytti-luokka",
        'food' => "Ruokailu",
        'aamiainen' => "Aamiainen",
        'illallinen' => "Illallinen",
        'lounas' => "Lounas",
        'granny' => "Grannys food",
        'travel' => "Linja-auto valinta",
        'bussi' => "Linja-auto valinta",
        'nick' => "Lempinimesi",
        'description' => "Hytin lisätiedot",
        'nro' => "Hytin tunniste",
        'ykl' => "YKL-jäsenyys",
        'club' => "Club One kortti",
        'lal' => "LAL-jäsenyys",
        'password' => "Salasana",
        'info' => "Lisätiedot",
        'cabin' => "Hytin nimi",
        'organization' => "Ainejärjestö",
        'etukortti' => "Club one -kortti",
        'hinta' => "Risteilyn hinta",
        'paikka' => "Lähtöpaikkasi",
        'ruokailu' => "Ruokailusi",
        'maksettu' => "Maksettu"
    );

    public function __construct($database) {
        $this->db = $database;
    }

    public function test_organization_id($post) {
        return $this->db->exists_organization_id($post['id_organization']);
    }

    public function test_password($post) {
        if (strlen($post['tunnus']) == 15) {
            $temp['id_person'] = $post['tunnus'];
            $temp['email'] = $post['email'];
            if ($this->db->exists_person_with_email_id($temp)) {
                return true;
            }
        }
        echo "Email- ja tunniste-yhdistelmä ei ollut oikea.";
        return false;
    }

    public function test_exists_persons_in_cabins_of_id_organization($id_organization) {
        return $this->db->exists_persons_in_cabins_of_id_organization($id_organization);
    }

    public function test_new_person($data_array) {

        if (!isset($data_array["email"])) {
            echo "Pitää olla email.";
            return false;
        }

        if (!$this->spamcheck($data_array["email"])) {
            echo "Email on vääränlainen";
            return false;
        }

        if ($data_array["name"] == "") {
            echo "Et kirjoittanut nimeäsi.";
            return false;
        }

        $ruoka = false;
        foreach ($this->ruokailu_valinta as $ateria) {
            if (isset($data_array[$ateria]))
                $ruoka = true;
        }
        if ($ruoka == false) {
            echo "Sinun pitää valita vähintään yksi ruoka.";
            return false;
        }

        if ($this->db->exists_person_name($data_array["name"])) {
            echo "Samanniminen henkilö on jo ilmoittautunut risteilylle.";
            return false;
        }

        if ($this->db->exists_person_nick($data_array["nick"])) {
            echo "Valitsemasi lempinimi on jo käytössä.";
            return false;
        }

        foreach ($this->person_kentat as $key => $value) {
            if (isset($data_array[$key])) {
                if (strlen($data_array[$key]) > $value) {
                    echo "$form_selite[$key] on liian pitkä" . '<br />' . "\n";
                    return false;
                }
            }
        }

        return true;
    }

    public function test_existing_person($data_array) {
        
        if (!$this->db->exists_person_id($data_array['id_person'])) {
            echo "Jotain meni pieleen.";
            return false;
        }

        if (!isset($data_array["email"])) {
            echo "Pitää olla email.";
            return false;
        }

        if (!$this->spamcheck($data_array["email"])) {
            echo "Email on vääränlainen";
            return false;
        }

        if ($data_array["name"] == "") {
            echo "Et kirjoittanut nimeäsi.";
            return false;
        }

        $ruoka = false;
        foreach ($this->ruokailu_valinta as $ateria) {
            if (isset($data_array[$ateria]))
                $ruoka = true;
        }
        if ($ruoka == false) {
            echo "Sinun pitää valita vähintään yksi ruoka.";
            return false;
        }

        if ($this->db->exists_person_name($data_array["name"])) {
            if (!$this->db->exists_person_name_with_id($data_array["name"], $data_array['id_person'])) {
                echo "Samanniminen henkilö on jo ilmoittautunut risteilylle.";
                return false;
            }
        }
        
        if ($this->db->exists_person_nick($data_array["nick"])) {
            if (!$this->db->exists_person_nick_with_id($data_array["nick"], $data_array['id_person'])) {
                echo "Valitsemasi lempinimi on jo käytössä.";
                return false;
            }
        }

        foreach ($this->person_kentat as $key => $value) {
            if (isset($data_array[$key])) {
                if (strlen($data_array[$key]) > $value) {
                    echo "$form_selite[$key] on liian pitkä" . '<br />' . "\n";
                    return false;
                }
            }
        }

        return true;
    }

    public function test_new_cabin($data_array, $id_person) {

        if (!$this->db->exists_person_by_id($id_person)) {
            echo "Olet jo ilmoittautunut?";
            return false;
        }

        if ($data_array["cabin"] == "") {
            echo "Ryhmällä pitää olla nimi.";
            return false;
        }

        if ($data_array["luokka"] == "") {
            echo "Ryhmällä pitää olla luokka.";
            return false;
        }

        if ($data_array["id_organization"] == "") {
            echo "Ryhmän pitää olla jonkin järjestön alla.";
            return false;
        }

        $luokka = false;
        if ($data_array['luokka'] !== "") {
            foreach ($this->luokka_valinta as $luokka) {
                if ($data_array['luokka'] == $luokka)
                    $luokka = true;
            }
        }
        if ($luokka == false) {
            echo "Miten onnistuit ilman hytti-luokkaa?";
            return false;
        }

        if ($this->db->exists_cabin_name($data_array["cabin"])) {
            echo "Samanniminen ryhmä on jo olemassa.";
            return false;
        }

        foreach ($this->cabin_kentat as $key => $value) {
            if (isset($data_array[$key])) {
                if (strlen($data_array[$key]) > $value) {
                    echo "$form_selite[$key] on liian pitkä" . '<br />' . "\n";
                    return false;
                }
            }
        }
        return true;
    }

    public function test_existing_cabin($id_cabin, $id_person) {

        if (!$this->db->exists_person_by_id($id_person)) {
            echo "Miten onnistuit?";
            return false;
        }

        if (!$this->db->exists_cabin_by_id($id_cabin)) {
            echo "Miten onnistuit?";
            return false;
        }
        return true;
    }

    private function spamcheck($field) {
        $field = filter_var($field, FILTER_SANITIZE_EMAIL);
        if (filter_var($field, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }

}

?>
