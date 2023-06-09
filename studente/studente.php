<?php
    session_start();
    //include delle funzioni
    include_once("../lib/functions.php");
    unset($_SESSION['id']);

    //se si è tentato di eseguire un cambio password
    if(isset($_GET['change_password'])){
        include_once('../lib/change_password.php');
    }

    //se lo studente intende fare la rinuncia agli studi
    if(isset($_GET['rinuncia_agli_studi'])){
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            
            //query di cancellamento dello studente
            $rinuncia_sql = "DELETE 
                            FROM studente 
                            WHERE matricola = $1";

            $preparato = pg_prepare($db, 'rinuncia', $rinuncia_sql);
            if($preparato){
                $result = pg_execute($db, 'rinuncia', array($_SESSION['matricola']));

                if($result){
                    $_POST['msg'] = "Rinuncia avvenuta con successo";
                    $_POST['approved'] = 0;
                }else{
                    $_POST['msg'] = pg_last_error();
                    $_POST['approved'] = 1;
                }
            }else{
                $_POST['msg'] = "errore nella preparazione della query: matricola: ".$_SESSION['matricola'];
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body style="background-color:white">
    <?php
        include_once('../lib/navbar.php');
    ?>

    <!-- --------------    stampa dei corsi dell'università       -------------     -->
    
    <style>
        .accordion-left {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 90%;
        }
        .accordion-header{
            width: 25%;
        }
        .accordion-body {
            height: 300px; 
            overflow-y: auto;
            width: 100%;
        }
        .accordion-item{
            width: 90%;
            min-width: 150px;
        }
        .accordion-button.collapsed{
            width: 100%;
        }

        .testo {
            flex-grow: 1;
            text-align: center;
        }
        
        .testo table {
            margin: 0 auto;
        }
    </style>
<div class="row">
    <div class="col-md-4" >
        <div class="accordion accordion-left" id="accordionExample">
            <?php
                //creazione di tutti gli accordion prelevando le info dei corsi dal database
                $db = pg_connect("host = localhost port = 5432 dbname = unimio");
                if($db){

                    $info_corsi_sql = "SELECT * FROM corso";
                    $insegnamenti_corso = "SELECT * 
                                            FROM insegnamento
                                            WHERE corso = $1";

                    //opto per una pg_query poichè la query non è parametrica
                    
                    $result = pg_query($db, $info_corsi_sql);
                    if($result){
                        $generatore = 0;
                        
                        while($row = pg_fetch_assoc($result)){
                        $generatore_id = "g". $generatore;    
                            print '
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" style="width: 100%" type="button" data-bs-toggle="collapse" data-bs-target="#'.$generatore_id.'" aria-expanded="false" aria-controls="'.$generatore_id.'">
                                            '.$row['nome_corso'].'
                                        </button>
                                    </h2>
                                    <div id="'.$generatore_id.'" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>corso: '.$row['nome_corso'].'.</strong><br><p>'.$row['descrizione'].'</p>
                                            <ul>';
                            
                            $insegnamenti = pg_query_params($db, $insegnamenti_corso, array($row['id']));
                            while($insegnamento = pg_fetch_assoc($insegnamenti)){
                                print'<li>' . $insegnamento['nome'].'</li>';
                            }
                            print'           </ul>
                                        </div>
                                    </div>
                                </div>';
                            $generatore++;
                        }
                    }
                }
            ?>
        </div>
    </div>

    <div class="col-md-8" style="border-left:10%">
        <div class="testo" style="margin:5%;text-align: left;">
            questa è la homepage dello studente<br>

            ti vuoi prenotare per un esame?<br>
            <a href="prenota_esame.php">prenota un esame</a><br>

            <br>vuoi visualizzare i tuoi voti?<br>
            <a href="esiti_esami.php">esiti esami</a><br>            
        </div>
    </div>
</div>

<div class="row" style="margin-top: 3%;margin: 7%;">
    <?php include_once('funzioni_studente/carriera_valida.php');?>
</div>

<!--------------------------------        form per la rinuncia agli studi volutamente sempre visibile  --------------------------->
        
        <form class="c-password" style="position: fixed; bottom: 10px; right: 10px; width:20%" action="<?php echo $_SERVER['PHP_SELF']; ?>?rinuncia_agli_studi" method="POST">
        <h4>Vuoi Rinunciare agli studi?</h4>
            <button type="submit" class="btn btn-primary">Rinuncia</button>
        </form>

    <!-----------------              form di cambio password           ------------------->
        <?php include_once('../lib/cambio_password.php')?>
        <?php script_boostrap()?>
</body>
</html>

