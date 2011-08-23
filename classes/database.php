<?php

class database {

    private $local_db;
    public $dbname = "risteily";
    private $dbuser = "root";
    private $dbpw = "";
    private $dbhost = "localhost";
    public $etuliite = "risteily_";

    public function __construct() {
        $this->local_db = mysql_connect($this->dbhost, $this->dbuser, $this->dbpw)
                or die(mysql_error());
        mysql_select_db($this->dbname)
                or die(mysql_error());
    }

    public function get_query_select($what, $from, $where = null, $is = null, $order_by = null, $where_array_is_and = true) {
        $what = $this->filterParameters($what);
        $where = $this->filterParameters($where);
        $order_by = $this->filterParameters($order_by);
        $from = $this->filterParameters($from);
        $is = $this->filterParameters($is);


        $query = "SELECT " . $what . " FROM " . $this->etuliite . $from;

        if (!empty($where)) {
            if (is_array($where) and is_array($is)) {
                $query .= " WHERE " . $this->get_where_query_part_from_array($where, $is, $where_array_is_and);
            } else
                $query .= " WHERE " . $where . "='$is'";
        }

        if (!empty($order_by))
            $query .= " ORDER BY " . $order_by;

//        echo $query;

        return $this->get_query_bulk($query);
    }

    public function get_query_bulk($query) {
        $result = mysql_query($query)
                or die(mysql_error());

        $n = 0;
        $template_array = array();
        while ($row = mysql_fetch_assoc($result)) {
            $template_array[$n] = $row;
            ++$n;
        };

//        var_dump($template_array);

        return $template_array;
    }

    public function get_where_query_part_from_array($where_array, $is_array, $where_array_is_and) {
        $temp_query = "";
        $first = true;
        for ($i = 0; $i <= count($where_array) - 1; $i++) {
            if (!$first) {
                if ($where_array_is_and)
                    $temp_query .= " AND ";
                else
                    $temp_query .= " OR ";
            }

            $temp_query .= "$where_array[$i]='$is_array[$i]'";

            $first = false;
        }
        return $temp_query;
    }

    public function exists_in_db($from, $where, $is) {
        $data_array = $this->get_query_select('*', $from, $where, $is);

        if (is_array($where) and isset($data_array[0]))
            return true;
        elseif (isset($data_array[0]) and $data_array[0][$where] !== "")
            return true;
        else
            return false;
    }

    public function exists_person_name($name) {
        return $this->exists_in_db('persons', 'name', $name);
    }

    public function exists_person_by_id($id_person) {
        return $this->exists_in_db('persons', 'id_person', $id_person);
    }

    public function exists_cabin_by_id($id_cabin) {
        return $this->exists_in_db('cabins', 'id_cabin', $id_cabin);
    }

    public function exists_person_nick($nick) {
        return $this->exists_in_db('persons', 'nick', $nick);
    }

    public function exists_organization_name($organization) {
        return $this->exists_in_db('organizations', 'organization', $organization);
    }

    public function exists_person_id($id) {
        return $this->exists_in_db('persons', 'id_person', $id);
    }

    public function exists_person_with_email_id($post) {
        return $this->exists_in_db('persons', array('email', 'id_person'), array($post['email'], $post['id_person']));
    }

    public function exists_person_name_with_id($name, $id_person) {
        return $this->exists_in_db('persons', array('name', 'id_person'), array($name, $id_person));
    }

    public function exists_person_nick_with_id($nick, $id_person) {
        return $this->exists_in_db('persons', array('nick', 'id_person'), array($nick, $id_person));
    }

    public function exists_cabin_id($id) {
        return $this->exists_in_db('cabins', 'id_cabin', $id);
    }

    public function exists_cabin_name($name) {
        return $this->exists_in_db('cabins', 'cabin', $name);
    }

    public function exists_organization_id($id) {
        return $this->exists_in_db('organizations', 'id_organization', $id);
    }

    public function exists_id($id) {
        if ($this->exists_person_id($id))
            return true;
        elseif ($this->exists_cabin_id($id))
            return true;
        elseif ($this->exists_organization_id($id))
            return true;
        else
            return false;
    }

    public function count_persons_in_cabin_by_id($id_cabin) {
        $persons_array = $this->get_query_select('name', "persons", 'id_cabin', $id_cabin);
        return count($persons_array);
    }
    
    public function count_cabins_in_organization_by_id($id_organization) {
        $cabins_array = $this->get_query_select('cabin', "cabins", 'id_organization', $id_organization);
        return count($cabins_array);
    }

    public function get_organizations() {
        return $this->get_query_select('*', 'organizations', null, null, 'name');
    }

    public function get_cabins_by_id_organization($id_organization) {
        return $this->get_query_select('*', 'cabins', 'id_organization', $id_organization, 'cabin');
    }

    public function get_cabin_by_id($id_cabin) {
        $temp = $this->get_query_select('*', 'cabins', 'id_cabin', $id_cabin);
        return $temp[0];
    }

    public function get_id_cabin_by_id_person($id_person) {
        $temp = $this->get_query_select('id_cabin', 'persons', 'id_person', $id_person);
        return $temp[0]['id_cabin'];
    }

    public function get_persons_in_cabins_by_cabins_array($cabins_array) {
        $temp_where_array;
        $temp_is_array;

        if (!empty($cabins_array)) {
            foreach ($cabins_array as $cabin) {
                $temp_where_array[] = 'id_cabin';
                $temp_is_array[] = $cabin['id_cabin'];
            }
        } else
            return array();

        return $this->get_query_select('*', 'persons', $temp_where_array, $temp_is_array, 'name', false);
    }

    public function exists_persons_in_cabins_of_id_organization($id_organization) {
        if (count($this->get_persons_in_cabins_by_cabins_array($this->get_cabins_by_id_organization($id_organization))) !== 0)
            return true;
        else
            false;
    }

    public function get_person_by_name($name) {
        $temp = $this->get_query_select('*', 'persons', 'name', $name);
        return $temp[0];
    }

    public function get_name_by_id($id_person) {
        $temp = $this->get_query_select('name', 'persons', 'id_person', $id_person);
        return $temp[0]['name'];
    }

    public function get_person_by_id($id_person) {
        $temp = $this->get_query_select('*', 'persons', 'id_person', $id_person);
        return $temp[0];
    }

    public function get_persons_nicks_by_id_cabin($id_cabin) {
        $temp = $this->get_query_select('name, nick', 'persons', 'id_cabin', $id_cabin);

//        var_dump($temp);

        $return = array();
        foreach ($temp as $person) {
            if ($person['nick'] !== "")
                $return[] = $person['nick'];
            else
                $return[] = $person['name'];
        }
        return $return;
    }

    public function get_organization_by_id($id_organization) {
        $temp = $this->get_query_select('*', 'organizations', 'id_organization', $id_organization);
        return $temp[0];
    }

    public function get_organization_id_by_organization($organization) {
        $temp = $this->get_query_select('id_organization', 'organizations', 'organization', $organization);
        return $temp[0]['id_organization'];
    }

    public function put_query_from_array($where, $array) {
        $array = $this->filterParameters($array);
        $where = $this->filterParameters($where);
        $query = "INSERT INTO " . $this->etuliite . $where . " (" . implode(", ", array_keys($array)) . ") VALUES ('" . implode("', '", array_values($array)) . "')";
        mysql_query($query)
                or die(mysql_error());
    }

    public function insert_into_db_organization($organization_array) {
        $this->put_query_from_array('organizations', $organization_array);
    }

    public function insert_into_db_person($person_array) {
        $this->put_query_from_array('persons', $person_array);
    }

    public function insert_into_db_cabin($cabin_array) {
        $this->put_query_from_array('persons', $cabin_array);
    }

    public function update_db($array, $table, $where = null, $is = null) {
        $array = $this->filterParameters($array);
        $table = $this->filterParameters($table);
        $where = $this->filterParameters($where);
        $is = $this->filterParameters($is);

        $query = "UPDATE " . $this->etuliite . $table . " SET ";

        $first = true;
        foreach ($array as $key => $value) {
            if (!$first)
                $query .= ", ";

            $query .= "$key='$value'";
            $first = false;
        }
        if (isset($where))
            $query .= "WHERE $where='$is'";

        echo $query;

        mysql_query($query)
                or die(mysql_error());
    }

    public function update_db_person_with_cabin_id($id_person, $id_cabin) {
        $this->update_db(array('id_cabin' => $id_cabin), 'persons', 'id_person', $id_person);
    }

    public function update_db_cabin_by_organization_id($id_cabin, $id_organization) {
        $this->update_db(array('id_organization' => $id_organization), 'cabins', 'id_cabin', $id_cabin);
    }

    private function filterParameters($array) {
        /*
         * Created by: Stefan van Beusekom
         * Created on: 31-01-2011
         * Description: A method that ensures safe data entry, and accepts either strings or arrays. If the array is multidimensional, 
         *                     it will recursively loop through the array and make all points of data safe for entry.
         * parameters: string or array;
         * return: string or array;
         */

        // Check if the parameter is an array
        if (is_array($array)) {
            // Loop through the initial dimension
            foreach ($array as $key => $value) {
                // Check if any nodes are arrays themselves
                if (is_array($array[$key]))
                // If they are, let the function call itself over that particular node
                    $array[$key] = $this->filterParameters($array[$key]);

                // Check if the nodes are strings
                if (is_string($array[$key]))
                // If they are, perform the real escape function over the selected node
                    $array[$key] = mysql_real_escape_string(htmlspecialchars($array[$key], ENT_QUOTES, "UTF-8"));
            }
        }
        // Check if the parameter is a string
        if (is_string($array))
        // If it is, perform a  mysql_real_escape_string on the parameter
            $array = mysql_real_escape_string(mysql_real_escape_string(htmlspecialchars($array, ENT_QUOTES, "UTF-8")));

        // Return the filtered result
        return $array;
    }

    private function get_random_string($length) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        // Length of character list
        $chars_length = (strlen($chars) - 1);
        // Start our string
        $string = $chars{mt_rand(0, $chars_length)};
        // Generate random string
        for ($i = 1; $i < $length; $i = strlen($string)) {
            // Grab a random character from our list
            $r = $chars{mt_rand(0, $chars_length)};
            // Make sure the same two characters don't appear next to each other
            if ($r != $string{$i - 1})
                $string .= $r;
        };
        return $string;
    }

    public function get_unique_id($length) {
        do {
            $string = $this->get_random_string(15);
        } while ($this->exists_id($string));
//        } while (false);
        return $string;
    }

}

?>