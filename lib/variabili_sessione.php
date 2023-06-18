<!--  script utile per il debugging per la stampa di tutte le variabili di sessione e di post -->

<div style="color: grey;">
    <h5>tutte le variabili di sessione<br></h5>
    <?php
        print'parametri in sessione:<br>';
        foreach ($_SESSION as $key => $value) {
            echo $key . " => " . $value . "<br>";
        }
        print'parametri in post:<br>';
        foreach ($_POST as $key => $value) {
            echo $key . ': ' . $value . '<br>';
        }
    ?>
</div>