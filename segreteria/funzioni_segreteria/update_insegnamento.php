<?php
//i messaggi di errore sono tutti salvati nelle variabili di session poicè si verrà redirezionati alla pagina segreteria.php
session_start();
    //salvataggio delle variabili salvate in post per il la query successiva se è la prima volta che viene aperta la pagina
    if(isset($_POST['id_insegnamento'])){
        print'salvo le variabili in sessione';
        $_SESSION['id_insegnamento'] = $_POST['id_insegnamento'];
        $_SESSION['nome_insegnamento'] = $_POST['nome'];
        $_SESSION['cfu'] = $_POST['cfu'];
        $_SESSION['anno'] = $_POST['anno'];
        $_SESSION['corso'] = $_POST['corso'];
        $_SESSION['operazione'] = $_POST['operazione'];
        $_SESSION['descrizione'] = $_POST['descrizione'];
        $_SESSION['docente_responsabile'] = $_POST['docente_responsabile'];
    }
    include_once "../../lib/variabili_sessione.php";
    print'variabile id insegnamento: '.$_SESSION['id_insegnamento'];

    $db = pg_connect("host = localhost port = 5432 dbname = unimio");

    //control
    if($db){
        //swithc eseguito sul parametro in sessione perchè quello in post con l'operazione di modifica si cancellerebbe
        switch ($_SESSION['operazione']){
            case 'aggiungi':
                if(isset($_GET['inserisci'])){ //se è stato inviato il comando di inserimento allora avvio la query
                    //verifica che non esistano già insegnamenti con lo stesso id
                    $check_sql = "SELECT 1
                        FROM insegnamento
                        WHERE id = $1";
                    $result = pg_prepare($db, "check", $check_sql);
                    $result = pg_execute($db, "check", array($_POST['id_insegnamento']));
                    if($result == 1){//ritorno un messaggio di errore
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
                                
                                foreach ($_POST as $key => $value) {
                                    print'inserimento di key: '.$key.' e value: '.$value;
                                    $propedeutico_sql = "INSERT INTO propedeuticità (id_insegnamento, id_insegnamento_propedeutico) 
                                                            VALUES ($1, $2)";
                                    $result = pg_query_params($db, $propedeutico_sql, array($_SESSION['id_insegnamento'], $value));
                                }
                                $_SESSION['approved'] = 0;
                                $_SESSION['msg'] = "L'insegnamento è stato inserito con successo";
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
                //verifica che non esistano già insegnamenti con lo stesso idg
                
                $check_sql = "SELECT 1
                FROM insegnamento
                WHERE id = $1";
                $result = pg_prepare($db, "check", $check_sql);
                $result = pg_execute($db, "check", array($_POST['id_insegnamento']));
                
                if($result = 1){
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
                }else{//ritorno un messaggio di errore
                    $_SESSION['approved'] = 1;
                    $_SESSION['msg'] = "Non esiste un insegnamento con questo id";
                    //redirezione alla pagina segreteria.php
                    header("Location: ../segreteria.php");
                }
                break;

            case 'cancella': //cancellazione dell'insegnamento con l'id inserito
                $check_sql = "SELECT 1
                        FROM insegnamento
                        WHERE id = $1";
                    $result = pg_prepare($db, "check", $check_sql);
                    $result = pg_execute($db, "check", array($_POST['id_insegnamento']));
                    if($result == 1){
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
    include_once("../../lib/variabili_sessione.php");
    include_once('../../lib/navbar.php');

?>
</head>

<body>
<?php 
    if(isset($_SESSION['approved']) && $_SESSION['approved'] == 0 && isset($_SESSION['msg'])){
            echo '<div class="alert alert-success" role="alert">
            ' . $_SESSION['msg'] . '</div>';
            
        //altrimenti se la variabile approved è settata a 1, e c'è un messaggio di errore da mostrare
    }else if(isset($_SESSION['approved']) && $_SESSION['approved'] == 1 && isset($_SESSION['msg'])){
        echo '<div class="alert alert-danger" role="alert">
        ' . $_SESSION['msg'] . '</div>';
    } 
?>
<p>seleziona i corsi che saranno propedeutici per l'insegnamento<br></p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?inserisci" method="POST">
    <?php
        $db = pg_connect("host=localhost port=5432 dbname=unimio ");
        $sql = "SELECT nome, id FROM insegnamento WHERE corso = $1";
        $result = pg_query_params($db, $sql, array($_POST['corso']));
        while ($row = pg_fetch_assoc($result)) {
            echo "<label>
                    <input type=\"checkbox\" name=ins".$row['nome']." value='" . $row['id_insegnamento'] . "'>" . $row['nome'] .
                "</label><br>";
        }
    ?>
    <input type="submit" value="invia">
</form>

</body>
    <?php script_boostrap()?>
</html>