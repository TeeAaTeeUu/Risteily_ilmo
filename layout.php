<?php

function top() {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" type="text/css" href="css/css.css" />
        </head>
        <body>
            <div class="body">
                <div class="navbar">
                    <?php
                    $lista = array(
                        'index' => "Etusivu",
                        'matkaaja' => "Ilmoittaudu",
                        'lista' => "Hytti-listat",
                        'muokkaa' => "Muokkaa tietojasi"
                    );

                    foreach ($lista as $tiedosto => $nimi)
                        echo '<a href="' . $tiedosto . '.php">' . $nimi . '</a><br />';
                    ?>
                </div>
                <div class="inner">
                    <?php
                }

                function bottom() {
                    ?>
                </div>
            </div>
        </body>
    </html>
    <?php
}
?>