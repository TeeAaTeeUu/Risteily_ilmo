<?php

include '../classes/database.php';

$persons = array(// Mitkä arvot luodaan käyttäjä tasolle
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

$cabins = array(// Mitkä arvot luodaan hytti tasolle
    'luokka' => 5,
    'cabin' => 40,
    'description' => 512,
    'id_organization' => 15,
    'id_cabin' => 15
);

$organizations = array(// Mitkä arvot luodaan järjestö tasolle
    'organization' => 15,
    'name' => 20,
    'info' => 512,
    'id_organization' => 15
);

$organization_list = array(
    'reson' => 'Resonanssi ry',
    'hyk' => 'HYK ry',
    'helix' => 'Helix ry',
    'matrix' => 'Matrix ry',
    'myy' => 'Myy ry',
    'symbio' => 'Symbioosi ry',
    'eky' => 'EKY ry',
    'vasara' => 'Vasara ry',
    'mao' => 'MaO ry',
    'tekis' => 'TKO-aly ry',
    'muut' => 'Muut'
);

$tables = array(
    "person",
    "cabin",
    "organization"
);

$db = new database();

//$dbname = $db->dbname;

$query;

foreach ($tables as $value) {
    $table = $db->etuliite . $value;
    $query = "";


    $query = "CREATE TABLE IF NOT EXISTS " . $table . "( id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id)";

    foreach (${$value} as $key => $value) {
        $query .= ", ";
        $query .= "$key VARCHAR($value)";
    }

    $query .= " )";

    echo $query;

    mysql_query($query)
            or die(mysql_error());
}

foreach ($organization_list as $organization => $name) {
    if (!$db->exists_organization($organization)) {
        $organization_array = array(
            "id_organization" => $db->get_unique_id(15),
            "organization" => $organization,
            "name" => $name,
            "info" => ''
        );
        echo $name;
        $db->insert_into_db_organization($organization_array);
    }
}
?>