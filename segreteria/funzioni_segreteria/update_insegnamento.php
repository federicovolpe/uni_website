<?php
//i messaggi di errore sono tutti salvati nelle variabili di session poicè si verrà redirezionati alla pagina segreteria.php
session_start();
    //salvataggio delle variabili salvate in post per il la query successiva se è la prima volta che viene aperta la pagina
    if($_POST['operazione'] == 'aggiungi'){ 
        $_SESSION['id_insegnamento'] = $_POST['id_insegnamento'];
        $_SESSION['nome_insegnamento'] = $_POST['nome'];
        $_SESSION['cfu'] = $_POST['cfu'];
        $_SESSION['anno'] = $_POST['anno'];
        $_SESSION['corso'] = $_POST['corso'];
        $_SESSION['operazione'] = $_POST['operazione'];
        $_SESSION['descrizione'] = $_POST['descrizione'];
        $_SESSION['docente_responsabile'] = $_POST['docente_responsabile'];
    }

    $db = pg_connect("host = localhost port = 5432 dbname = unimio");

    //control
    if($db){
        //verifica che non esistano già insegnamenti con lo stesso id
            $check_sql = "SELECT 1
                FROM insegnamento
                WHERE id = $1";
            $result = pg_prepare($db, "check", $check_sql);
            $result = pg_execute($db, "check", array($_POST['id_insegnamento']));
            $result_check = pg_fetch_row($result);

        //swithc eseguito sul parametro in sessione perchè quello in post con l'operazione di modifica si cancellerebbe
        switch ($_SESSION['operazione']){
            case 'aggiungi':
                if(isset($_GET['inserisci'])){ //se è stato inviato il comando di inserimento allora avvio la query
                    
                    if($result_check[0] == 1){//ritorno un messaggio di errore perchè esiste gia un insegnamento con questo id
                        $_SESSION['approved'] = 1;
                        $_SESSION['msg'] = "Esiste già un insegnamento con questo id";
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");

                    }else{
                        $insertion_sql = "INSERT INTO insegnamento (id, nome, descrizione, anno, corso, cfu) 
                                            VALUES ($1, $2, $3, $4, $5, $6)";
                        $preparato = pg_prepare($db, "inserzione", $insertion_sql);

                        if($preparato){
                            $inserito = pg_execute($db, "inserzione", array($_SESSION['id_insegnamento'], $_SESSION['nome_insegnamento'], $_SESSION['descrizione'], $_SESSION['anno'], $_SESSION['corso'], $_SESSION['cfu']));
                            
                            //inserzione del docente responsabile
                            $docente_sql = "INSERT INTO responsabile_insegnamento (docente, insegnamento) 
                                            VALUES ($1, $2)";
                            pg_query_params($db, $docente_sql, array($_SESSION['docente_responsabile'], $_SESSION['id_insegnamento']));

                            if($inserito){//inserimento degli insegnamenti propedeutici
                                $_SESSION['msg'] = "L'insegnamento ".$_SESSION['id_insegnamento']." è stato inserito con successo<br>propedeuticità:<br>";
                                foreach ($_POST as $key => $value) {
                                    print'inserimento di key: '.$key.' e value: '.$value;
                                    $propedeutico_sql = "INSERT INTO propedeuticità (insegnamento, propedeutico) 
                                                            VALUES ($1, $2)";
                                    $result = pg_query_params($db, $propedeutico_sql, array($_SESSION['id_insegnamento'], $value));
                                    if($result != false){
                                        $_SESSION['msg'] = $_SESSION['msg'] . 'inserita propedeuticità: '.$_SESSION['id_insegnamento'].' -> '.$value.'<br>';
                                    }else{
                                        $_SESSION['msg'] = $_SESSION['msg'] . 'non successo: '.$_SESSION['id_insegnamento'].' -> '.$value.'<br>';
                                    }
                                }
                                $_SESSION['approved'] = 0;
                                
                                //redirezione alla pagina segreteria.php
                                header("Location: ../segreteria.php");
                            }else{
                                $_SESSION['approved'] = 1;
                                $_SESSION['msg'] = pg_last_error();
                            }
                        }else{
                            $_SESSION['approved'] = 1;
                            $_SESSION['msg'] = pg_last_error();
                        }
                    }
                }
                break;
            case 'modifica':
                
                if($result_check[0] == 1){//     se esiste un corso con questo id allora procedo
                    //composizione della query in base ai parametri inseriti
                    $contaparametri = 2;
                    $modifica_sql = "UPDATE insegnamento 
                                    SET";
                    $array = [];
                    $array[] = $_SESSION['id_insegnamento'];
                                        //------------  inizio della composizione  --------
                    if(isset($_SESSION['nome_insegnamento']) && !empty($_SESSION['nome_insegnamento'])){
                        $modifica_sql .= "nome = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_SESSION['nome_insegnamento'];
                    }if(isset($_SESSION['descrizione']) && !empty($_SESSION['descrizione'])){
                        $modifica_sql .= "descrizione = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_SESSION['descrizione'];
                    }if(isset($_SESSION['corso']) && !empty($_SESSION['corso'])){
                        $modifica_sql .= "corso = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_SESSION['corso'];
                    }if(isset($_SESSION['cfu']) && !empty($_SESSION['cfu'])){
                        $modifica_sql .= "cfu = $$contaparametri";
                        $contaparametri++;
                        $array[] = $_SESSION['cfu'];
                    }

                    //togliere l'ultima virgola dalla query
                    if(substr($modifica_sql, -1) === ','){
                        $modifica_sql = substr($modifica_sql, 0, -1);
                    }
                    $modifica_sql = $modifica_sql ." WHERE id = $1";
                    
                    $preparato = pg_prepare($db, "modifica", $modifica_sql);
                    $esito_modifica = pg_execute($db, "modifica", $array);

                    if($esito_modifica){
                        $_SESSION['approved'] = 0;
                        $_SESSION['msg'] = "L'insegnamento è stato modificato con successo";
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");
                    }else{
                        $_SESSION['approved'] = 1;
                        $_SESSION['msg'] = pg_last_error();
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");
                    }
                }else{//             nel caso non esistesse un corso con questo id
                    $_SESSION['approved'] = 1;
                    $_SESSION['msg'] = "Non esiste un insegnamento con questo id";
                    //redirezione alla pagina segreteria.php
                    header("Location: ../segreteria.php");
                }
                break;

            case 'cancella': //cancellazione dell'insegnamento con l'id inserito
                    if($result_check[0] == 1){ //        esiste un segnamento con questo id, posso procedere
                        $cancellazione_sql = "DELETE FROM insegnamento WHERE id = $1";
                        
                        $cancellazione = pg_prepare($db, "cancellazione", $cancellazione_sql);
                        $esito_cancellazione = pg_execute($db, "cancellazione", array($_POST['id_insegnamento']));
                        if($esito_cancellazione){
                            $_SESSION['approved'] = 0;
                            $_SESSION['msg'] = "L'insegnamento è stato eliminato con successo";
                            //redirezione alla pagina segreteria.php
                            header("Location: ../segreteria.php");
                        }else{
                            $_SESSION['approved'] = 1;
                            $_SESSION['msg'] = pg_last_error();
                        }
                    }else{//ritorno un messaggio di errore
                        $_SESSION['approved'] = 1;
                        $_SESSION['msg'] = "Non esiste un insegnamento con questo id";
                        //redirezione alla pagina segreteria.php
                        header("Location: ../segreteria.php");
                    }
                break;
            default:
                $_SESSION['approved'] = 1;
                $_SESSION['msg'] = "Errore nella scelta dell'operazione";
                //redirezione alla pagina segreteria.php
                header("Location: ../segreteria.php");

                break;
        }
    }else{
        $_SESSION['msg'] = "Errore di connessione al database";
        $_SESSION['approved'] = 1;
        //redirezione alla pagina segreteria.php
        header("Location: ../segreteria.php");
    }
?>

<!--codice html per la scelta dei corsi propedeutici-->
<!DOCTYPE html>
<html lang="en">
<head>
<?php
    include_once("../../lib/head.php"); 
    include_once("../../lib/functions.php")
?>
</head>

<body>
<?php include_once('../../lib/navbar.php');

        if(isset($_SESSION['approved']) && $_SESSION['approved'] == 0 && isset($_SESSION['msg'])){
                echo '<div class="alert alert-success" role="alert">
                ' . $_SESSION['msg'] . '</div>';
                
            //altrimenti se la variabile approved è settata a 1, e c'è un messaggio di errore da mostrare
        }else if(isset($_SESSION['approved']) && $_SESSION['approved'] == 1 && isset($_SESSION['msg'])){
            echo '<div class="alert alert-danger" role="alert">
            ' . $_SESSION['msg'] . '</div>';
        } 
?>


<form class="login-form" style="align-items: center; justify-content: center" action="<?php echo $_SERVER['PHP_SELF']; ?>?inserisci" method="POST">
<h3>seleziona i corsi che saranno propedeutici per l'insegnamento: <?php $_SESSION['id_insegnamento'];?><br></h3>
    <?php
        $db = pg_connect("host=localhost port=5432 dbname=unimio ");
        $sql = "SELECT nome, id as id_insegnamento FROM insegnamento WHERE corso = $1";
        $result = pg_query_params($db, $sql, array($_POST['corso']));
        while ($row = pg_fetch_assoc($result)) {
            echo'<div class="form-check">
                        <input class="form-check-input" name="' . $row['id_insegnamento'] . '" type="checkbox" value="' . $row['id_insegnamento'] . '" id="flexCheckDefault">
                        <label class="form-check-label" for="' . $row['id_insegnamento'] . '">' . $row['nome'] . '</label>
                    </div>';

        }
    ?>
    <button type="submit" style="padding:2%; margin-top: 10px" class="btn btn-primary">Conferma</button>
</form>

</body>
    <?php script_boostrap()?>
</html>
