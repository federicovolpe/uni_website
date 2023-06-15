<?php
    session_start();
    //include delle funzioni
    include_once("../lib/functions.php");
    unset($_SESSION['id']);

    //se la pagina è stata con una operazione di cambio password
    if(isset($_GET['change_password'])){
        //utilizzo la funzione change password
        include_once('../change_password.php');
    }

    //se lo studente intende fare la rinuncia agli studi
    if(isset($_GET['rinuncia_agli_studi'])){
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            
            //query di cancellamento dello studente
            $rinuncia_sql = "DELETE FROM studente WHERE matricola = $1";
            $preparato = pg_prepare($db, 'rinuncia', $rinuncia_sql);
            $result = pg_execute($db, 'rinuncia', array($_SESSION['matricola']));

            if($result){
                $_POST['msg'] = "Rinuncia avvenuta con successo";
                $_POST['approved'] = 0;
            }else{
                $_POST['msg'] = "Rinuncia fallita";
                $_POST['approved'] = 1;
            }
        }else{
            $_POST['msg'] = "connessione al database fallita";
            $_POST['approved'] = 1;
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <?php include_once("../lib/head.php"); ?>
    <script>
        // script che fa in modo che quando si clicca il pulsante indietro si venga riportati a login.php
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            //se la pagina da cui è stata referenziata è diversa da esiti_esami.php o prenota_esame.php
            if (document.referrer !== 'esiti_esami.php' && document.referrer !== 'prenota_esame.php') {
                window.location.href = '../login.php';
            }
        }
    </script>
</head>

<body>
    <?php
        include_once('../lib/navbar.php');
    ?>
        <?php messaggi_errore_post2()?>
        <?php  print("<h1>Benvenuto ".  $_SESSION['nome']." ". $_SESSION['cognome'] ."</h1>");?>

    <!-- stampa dei corsi dell'università-->
    <style>
    .accordion-left {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
</style>

<div class="accordion accordion-left" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Accordion Item #1
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <strong>This is the first item's accordion body.</strong> It is shown by default.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Accordion Item #2
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <strong>This is the second item's accordion body.</strong> It is h1.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Accordion Item #3
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <strong>This is the third item's accordion body.</strong> It is h.
            </div>
        </div>
    </div>
</div>


    <div style="text-align: center;">
        questa è la homepage dello studente<br>
        dati dell'utente:<br>
        <table>
            stampa dei dati dell'utente
        </table>

        ti vuoi prenotare per un esame?<br>
        <a href="prenota_esame.php">prenota un esame</a><br>

        <br>vuoi visualizzare i tuoi voti?<br>
        <a href="esiti_esami.php">esiti esami</a><br>

        <br>stampa la tua carriera valida<br>
        <?php include_once('funzioni_studente/carriera_valida.php');?>
    </div>

    <!-----------------              form di cambio password           ------------------->
    <div class="rinuncia_studi" style="position: fixed; bottom: 10px; right: 10px;">
        <h4>Vuoi Rinunciare agli studi?</h4>
        <form class="c-password" action="<?php echo $_SERVER['PHP_SELF']; ?>?rinuncia_agli_studi" method="POST">
            <button type="submit" class="btn btn-primary">Rinuncia</button>
        </form>
    </div>

        <?php include_once('../lib/cambio_password.php')?>
        <?php script_boostrap()?>

</body>
</html>

