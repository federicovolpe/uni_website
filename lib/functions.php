<?php
    function display_esiti_esami($matricola) {
        //connessione al database
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        
        if($db){
            //tabella dove ci sono tutti gli esiti degli esami dello studente indicato
            $esiti_sql = "SELECT I.nome, ES.data, E.esito
                            FROM esiti AS E
                            INNER JOIN esami AS ES ON E.esame = ES.id
                            INNER JOIN insegnamento AS I ON ES.insegnamento = I.id
                            WHERE E.studente = $1
                            GROUP BY E.esame, I.nome, ES.data, E.esito";
           
            $preparato1 = pg_prepare($db, "esiti", $esiti_sql);

            if($preparato1){
                $esiti = pg_execute($db, "esiti", array($matricola));

                if(pg_num_rows($esiti) > 0){ //creazione delle righe della tabella
                    while($row = pg_fetch_assoc($esiti)){
                        echo '<tr>
                                <td>'. $row['nome'] .'</td>
                                <td>'. $row['data'] .'</td>';
                        if( $row['esito'] < 18){
                            echo '<td style="color: red;">'. $row['esito'] .'</td>';
                        }else{
                            echo '<td style="color: green;">'. $row['esito'] .'</td>';
                        }
                    }

                }else{ //nel caso non ci fossero esiti da mostrare           
                    $_POST['msg'] = 'non ci sono esiti registrati per lo studente '.$matricola;
                    $_POST['approved'] = 1;
                }
            }else{                  
                $_POST['msg'] = pg_last_error();
                $_POST['approved'] = 1;
            }
        }else{                  
            $_POST['msg'] = 'connessione al database non riuscita';
            $_POST['approved'] = 1;
        }
    }
    
    function display_esami_prenotabili($matricola) {
        
        //connessione al database
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){

            //tabella dove ci sono gli insegnamenti dello studente corrispondenti ad un esame a cui ci si può iscrivere o cancellare iscrizione
            $esami_sql = "SELECT ES.esame_id, ES.nome, ES.data
                    FROM studente AS S
                    JOIN
                        (SELECT I.corso AS corso, E.id AS esame_id, I.nome, E.data
                        FROM esami AS E
                        INNER JOIN insegnamento AS I ON I.id = E.insegnamento) AS ES ON ES.corso = S.corso_frequentato
                        WHERE S.matricola = $1";
           
            $preparato1 = pg_prepare($db, "esami_iscrivibili", $esami_sql);

            if($preparato1){
                $esami = pg_execute($db, "esami_iscrivibili", array($matricola));
                if($esami){

                    while($row = pg_fetch_assoc($esami)){
                        
                        //stampa delle prime due colonne contenenti il nome e la data dell'esame
                        echo '<tr>
                                <td>'. $row['nome'] .'</td>
                                <td>'. $row['data'] .'</td>
                                <td>';

                        //query per vedere se ci si è già iscritti a quell'esame
                        $iscrizioni_sql = "SELECT * 
                                            FROM iscrizioni 
                                            WHERE esame = $1 AND studente = $2";

                        $preparato2 = pg_prepare($db, "iscrizioni", $iscrizioni_sql);
                        $iscritto = pg_execute($db, "iscrizioni", array($row['esame_id'],$matricola));

                        //se l'esame risulta fra quelli a cui ci si è già iscritti allora mostro la cancellazione da un esame
                        if(pg_num_rows($iscritto) >= 1){ 
                            print'<form action="'. $_SERVER['PHP_SELF'] .'" method="GET">
                                    <input type="hidden" name="c_esame" value="'. $row['esame_id'] .'">
                                    <button class="btn btn-danger" type="submit">cancella iscrizione</button>
                                </form>
                                </td>
                                </tr>';
                        }else{
                            print'<form action="'. $_SERVER['PHP_SELF'] .'" method="GET">
                                    <input type="hidden" name="esame" value="'. $row['esame_id'] .'">
                                    <button class="btn btn-success" type="submit">iscriviti</button>
                                </form>
                                </td>
                                </tr>';
                        }
                    }
                }else{
                    print("l'esecuzione della query non è andata a buon fine</br>");
                }
            }else{
                print("la preparazione della query non è andata a buon fine</br>");
            }
        } else{
            print("connessione con il database fallita");
        }
    }

    function messaggi_errore_post2(){

        //se la variabile approved è settata a 0, e c'è un messaggio da mostrare
        if(isset($_POST['approved']) && $_POST['approved'] == 0 && isset($_POST['msg'])){
            echo '<div class="alert alert-success" role="alert">' . $_POST['msg'] . '</div>';

        //altrimenti se la variabile approved è settata a 1, e c'è un messaggio di errore da mostrare
        }else if(isset($_POST['approved']) && $_POST['approved'] == 1 && isset($_POST['msg'])){
            echo '<div class="alert alert-danger" role="alert">' . $_POST['msg'] . '</div>';
        }
    }

    function script_boostrap(){
        $import = '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>';
        return $import;
    }

    function verifica_recupera_info(){
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];

        //se le variabili non sono vuote
        if(!empty($email) && !empty($password)){

            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
            if($conn){
                
                //caso della verifica di un utente di tipo studente
                if(substr($email, -17) === 'studenti.unimi.it'){
                    $query = "SELECT 1
                                FROM studente
                                WHERE email = $1 AND passwrd = $2 ;";

                    $prepara = pg_prepare($conn, "query_di_verifica", $query);
                    $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

                    if(pg_num_rows($esito_verifica) >= 1){
                        //query per il recupero delle infromazioni riguardanti lo studente indicato
                        $query2 = " SELECT *
                                    FROM studente
                                    WHERE email = $1 AND passwrd = $2 ;";

                        $prepara = pg_prepare($conn, "fetch_info", $query2);
                        $result = pg_execute($conn, "fetch_info", array($email, $password));

                        if($result){  //settaggio delle informazioni ricavate in variabili di sessione 
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['matricola'] = $row['matricola'];
                            $_SESSION['corso_frequentato'] = $row['corso_frequentato'];
                        
                            // segnalare al dispatcher che l'autenticazione ha avuto successo
                            $_POST['approved'] = 0;
                        }
                    } else {// Accesso non valido, reindirizzamento a pagina di errore                      
                        $_POST['msg'] = "le credenziali non sono state trovate nel database";
                        $_POST['approved'] = 1;
                    }
                }

                //caso della verifica di un utente di tipo docente
                if(substr($email, -16) === 'docenti.unimi.it'){
                    //query ad hoc per i docenti
                    $query = "SELECT 1
                                FROM docente
                                WHERE email = $1 AND passwrd = $2 ;";

                    $prepara = pg_prepare($conn, "query_di_verifica", $query);
                    $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

                    if(pg_num_rows($esito_verifica) >= 1){
                        $query2 = " SELECT *
                                    FROM docente
                                    WHERE email = $1 AND passwrd = $2 ;";
                        $prepara = pg_prepare($conn, "fetch_info", $query2);
                        $result = pg_execute($conn, "fetch_info", array($email, $password));

                        if($result){  
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['id'] = $row['id'];
                            
                            // segnalare al dispatcher che l'autenticazione ha avuto successo
                            $_POST['approved'] = 0;
                        }
                    } else {// Accesso non valido, reindirizzamento a pagina di errore                        
                        $_POST['msg'] = "le credenziali non sono state trovate nel database";
                        $_POST['approved'] = 1;
                    }
                }

                //caso della verifica di un utente di tipo segreteria
                if(substr($email, -19) === 'segreteria.unimi.it'){
                    //query ad hoc per gli utenti di segreteria
                    $query = "SELECT 1
                        FROM segreteria
                        WHERE email = $1 AND passwrd = $2 ;";

                    $prepara = pg_prepare($conn, "query_di_verifica", $query);
                    $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));

                    if(pg_num_rows($esito_verifica) >= 1){
                        $query2 = " SELECT *
                            FROM segreteria
                            WHERE email = $1 AND passwrd = $2 ;";
                        $prepara = pg_prepare($conn, "fetch_info", $query2);
                        $result = pg_execute($conn, "fetch_info", array($email, $password));

                        if($result){  
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['id'] = $row['id'];

                            // segnalare al dispatcher che l'autenticazione ha avuto successo
                            $_POST['approved'] = 0;
                        }
                    } else {// Accesso non valido, reindirizzamento a pagina di errore (le credenziali non sono state trovate nel database)                       
                        $_POST['msg'] = "le credenziali non sono state trovate nel database";
                        $_POST['approved'] = 1;
                    }
                }
            }else{  //connessione al database fallita
                $_POST['msg'] = "la connessione al database NON è andata a buon fine";
                $_POST['approved'] = 1;
            }
        }
    }
?>