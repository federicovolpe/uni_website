<div style="color: grey;">
    <h5>tutte le variabili di sessione<br></h5>
    <?php
        foreach ($_SESSION as $key => $value) {
            echo $key . " => " . $value . "<br>";
        }
    ?>
</div>