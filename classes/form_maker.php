<?php

include_once 'database.php';

class form_maker {

    private $db;
    private $ruokailu_valinta = array(
        'aamiainen' => 9,
        'illallinen' => 32,
        'lounas' => 28,
        'granny' => 12.5
    );
    private $ruokailu_selite = array(
        'aamiainen' => 'Aamiainen',
        'illallinen' => "Illallinen",
        'lounas' => "Lounas",
        'granny' => "Granny's food"
    );
    private $luokka_valinta = array(
        'A' => '20',
        'B' => '15',
        'C' => '10'
    );
    private $bussi_valinta = array(
        'menopaluu' => 12,
        'meno' => 9,
        'paluu' => 9,
        'ei bussia' => 0
    );

    public function __construct($database) {

        $this->db = $database;
    }

    public function print_form_new_person($post = array()) {
        echo '<form method="post">' . "\n";

        $this->make_name($post);
        $this->make_email($post);
        $this->make_nick($post);
        $this->make_food_checkbox($post);
        $this->make_etukortti($post);
        $this->make_info($post);

        echo '<input name="new_person" type="submit"  value="lähetä" />';
        echo '</form>';
    }

    public function print_form_existing_person($id_person) {
        echo '<form method="post">' . "\n";

        if (is_array($id_person)) {
            if (isset($id_person['existing_person'])) {
                $person = $id_person;
            }
        } else
            $person = $this->db->get_person_by_id($id_person);

        $this->make_name($person);
        $this->make_email($person);
        $this->make_nick($person);
        $this->make_food_checkbox($person);
        $this->make_etukortti($person);
        $this->make_info($person);
        $this->make_hidden('id_person', $person['id_person']);

        echo '<input name="existing_person" type="submit"  value="päivitä" />';
        echo '<input name="existing_person_reset" type="submit"  value="reset" />';
        echo '</form>';
    }

    public function print_form_password($post = array()) {
        echo '<form method="post">' . "\n";

        $this->make_email($post);
        $this->make_password($post);

        echo '<input name="password" type="submit"  value="kirjaudu" />';
        echo '</form>';
    }

    public function print_form_select_this_cabin($id_cabin) {
        echo '<form method="post">' . "\n";

        $this->make_hidden('id_cabin', $id_cabin);

        echo '<input name="existing_cabin" type="submit"  value="valitse" />';
        echo '</form>';
    }

    public function print_form_new_cabin($post = array()) {
        echo '<form method="post">' . "\n";

        $this->make_cabin($post);
        $this->make_cabin_class_select($post);
        $this->make_organization_select($post);
        $this->make_description($post);

        echo '<input name="new_cabin" type="submit"  value="lähetä" />';
        echo '</form>';
    }

    public function print_form_select_organization_for_booking($post = array(), $same_form = false) {
        echo '<div class="center">';

        echo '<p>Valitse tästä ainejärjestö, jonka vapaaseen hyttiin haluat ilmoittautua.</p>';

        if ($same_form == false)
            echo '<form method="post">' . "\n";

        $this->make_organization_select($post, null);

        echo '<input name="select_organization_for_booking" type="submit"  value="listaa" />';
        echo '</form>';

        echo '</div>';
    }

    public function print_form_select_new_cabin($same_form = false) {
        echo '<p>Ilmoittaudu tätä kautta uuteen hyttiin.</p>';

        echo '<form method="post">' . "\n";
        
        echo '<input name="select_new_cabin" type="submit"  value="luo uusi hytti" />';
        if ($same_form == false)
            echo '</form>';
    }

    public function make_name($post = array()) {
        $value = '';
        if (isset($post['name']))
            $value = 'value="' . $post['name'] . '" ';
        echo '<span class="input">*Nimi </span><span>(julkinen, jos ei lempinimeä)</span><br />' . "\n" . '<input name="name" type="text" ' . $value . 'maxlength="255" /><br />' . "\n";
    }

    public function make_password($post = array()) {
        $value = '';
        if (isset($post['tunnus']))
            $value = 'value="' . $post['tunnus'] . '" ';
        echo '<span class="input">Tunniste</span><br />' . "\n" . '<input name="tunnus" type="password" ' . $value . 'maxlength="255" /><br />' . "\n";
    }

    public function make_hidden($name, $value) {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n";
    }

    public function make_email($post = array()) {
        $value = '';
        if (isset($post['email']))
            $value = 'value="' . $post['email'] . '" ';
        echo '<span class="input">*Email</span><br />' . "\n" . '<input name="email" type="text" ' . $value . 'maxlength="255" /><br />' . "\n";
    }

    public function make_nick($post = array()) {
        $value = '';
        if (isset($post['nick']))
            $value = 'value="' . $post['nick'] . '" ';
        echo '<span class="input">Lempinimesi </span><span>(julkinen)</span><br />' . "\n" . '<input name="nick" type="text" ' . $value . 'maxlength="255" /><br />' . "\n";
    }

    public function make_etukortti($post = array()) {
        $value = '';
        if (isset($post['etukortti']))
            $value = 'value="' . $post['etukortti'] . '" ';
        echo '<span class="input">Club one -kortti</span><br />' . "\n" . '<input name="etukortti" type="text" ' . $value . 'maxlength="255" /><br />' . "\n";
    }

    public function make_cabin($post = array()) {
        $value = '';
        if (isset($post['cabin']))
            $value = 'value="' . $post['cabin'] . '" ';
        echo '<span class="input">*Hyttisi nimi </span><span>(julkinen)</span><br />' . "\n" . '<input name="cabin" type="text" ' . $value . 'maxlength="255" /><br />' . "\n";
    }

    public function make_info($post = array()) {
        $value = '';
        if (isset($post['info']))
            $value = $post['info'];
        echo '<span class="input">Lisätietoa</span><br />' . "\n" . '<textarea name="info" rows="8" cols="40">' . $value . '</textarea><br />' . "\n";
    }

    public function make_description($post = array()) {
        $value = '';
        if (isset($post['description']))
            $value = $post['description'];
        echo '<span class="input">Hyttisi lisätietoa </span><span>(julkinen)</span><br />' . "\n" . '<textarea name="description" rows="8" cols="40">' . $value . '</textarea><br />' . "\n";
    }

    public function make_food_checkbox($post = array()) {
        echo '<span class="input">*Ruokailusi </span><span>(yksi pakollinen)</span><br />' . "\n";

        $first = true;
        foreach ($this->ruokailu_valinta as $ruokailu => $hinta) {
            $value = '';
            if (isset($post[$ruokailu]))
                $value = ' checked="checked"';
            if (!$first)
                echo '<br />' . "\n";
            $first = false;
            echo '<input name="' . $ruokailu . '" type="checkbox" value="1"' . $value . ' /><label class="checkbox" for="' . $ruokailu . '">' . $this->ruokailu_selite[$ruokailu] . ' (' . $hinta . 'e)</label>';
        }
        echo '<br />';
    }

    public function make_cabin_class_select($post = array()) {
        echo '<span class="input">*Hyttiluokka</span><br />' . "\n";
        echo '<select name="luokka">' . "\n";

        foreach ($this->luokka_valinta as $luokka => $hinta) {
            $value = '';
            if (isset($post['luokka'])) {
                if ($post['luokka'] == $luokka)
                    $value = ' selected="selected"';
            }
            echo '<option value="' . $luokka . '"' . $value . '>' . $luokka . '-luokka (' . $hinta . 'e / hlö)</option>' . "\n";
        }

        echo '</select><br />' . "\n";
    }

    public function make_travel_type_select($post = array()) {
        echo '<span class="input">*Linja-auto valinta</span><br />' . "\n";
        echo '<select name="travel">' . "\n";

        foreach ($this->bussi_valinta as $tyyppi => $hinta) {
            $value = '';
            if (isset($post['travel'])) {
                if ($post['travel'] == $tyyppi)
                    $value = ' selected="selected"';
            }
            echo '<option value="' . $tyyppi . '"' . $value . '>' . $tyyppi . ' (' . $hinta . 'e / hlö)</option>' . "\n";
        }

        echo '</select><br />' . "\n";
    }

    public function make_organization_select($post = array(), $list_form = "*") {
        echo '<span class="input">' . $list_form . 'Ainejärjestö</span><br />' . "\n";
        echo '<select name="id_organization">' . "\n";

        $organizations = $this->db->get_organizations();

        foreach ($organizations as $organization) {
            $value = '';
            if (isset($post['id_organization'])) {
                if ($post['id_organization'] == $organization['id_organization'])
                    $value = ' selected="selected"';
            }
            echo '<option value="' . $organization['id_organization'] . '"' . $value . '>' . $organization['name'] . '</option>' . "\n";
        }

        echo '</select><br />' . "\n";
    }

}

?>
