<?php
    function display_esami_prenotabili($matricola) {
        
        //connessione al database
        $db = pg_connect("host = localhost port = 5432 dbname = unimio");
        if($db){
            //tabella dove ci sono gli insegnamenti dello studente con un esame programmato
            $sql = "SELECT ES.esame_id, ES.nome, ES.data
                    FROM studente AS S
                    JOIN
                        (SELECT I.corso AS corso, E.id AS esame_id, I.nome, E.data
                        FROM esami AS E
                        INNER JOIN insegnamento AS I ON I.id = E.insegnamento) AS ES ON ES.corso = S.corso_frequentato
                        WHERE S.matricola = $1";
            $preparato = pg_prepare($db, "esami_iscrivibili", $sql);

            if($preparato){
                $result = pg_execute($db, "esami_iscrivibili", array($matricola));
                if($result){
                    while($row = pg_fetch_assoc($result)){
                        //l'ultima colonna metterà in post l'id dell'esame a cui ci si vuole prenotare
                        echo '<tr>
                                <td>'. $row['nome'] .'</td>
                                <td>'. $row['data'] .'</td>
                                <td>
                                    <form action="'. $_SERVER['PHP_SELF'] .'" method="GET">
                                        <input type="hidden" name="esame" value="'. $row['esame_id'] .'">
                                        <button type="submit">form iscrizione</button>
                                    </form>
                                </td>
                            </tr>';
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


    function messaggi_errore() {
        echo '<script>
            const urlParams = new URLSearchParams(window.location.search);
            const approved = urlParams.get(\'approved\');
            const msg = urlParams.get(\'msg\');
            if (approved === \'0\') {
                var successMessage = document.createElement(\'div\');
                successMessage.className = \'p-3 mb-2 bg-success text-white\';
                successMessage.textContent = $msg;
                document.body.appendChild(successMessage);
            }
            if (approved === \'1\') {
                var successMessage = document.createElement(\'div\');
                successMessage.className = \'p-3 mb-2 bg-danger text-white\';
                successMessage.textContent = $msg;
                document.body.appendChild(successMessage);
            }
        </script>';
    }

    function messaggi_errore_post2(){
        //se la variabile approved è settata a 0, e c'è un messaggio da mostrare
        if(isset($_POST['approved']) && $_POST['approved'] == 0 && isset($_POST['msg'])){
            echo '<div class="alert alert-success" role="alert">
            ' . $_POST['msg'] . '</div>';

        //altrimenti se la variabile approved è settata a 1, e c'è un messaggio di errore da mostrare
        }else if(isset($_POST['approved']) && $_POST['approved'] == 1 && isset($_POST['msg'])){
            echo '<div class="alert alert-danger" role="alert">
            ' . $_POST['msg'] . '</div>';
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
        print('verifica email = '.$email.'<br>');
         print('verifica password = '.$password.'<br>');
        //se le variabili non sono vuote
        if(!empty($email) && !empty($password)){
            $conn = pg_connect("host = localhost port = 5432 dbname = unimio");
            if($conn){
                
                if(substr($email, -17) === 'studenti.unimi.it'){
                    print("query per i studenti<br>");
                    $query = "SELECT 1
                        FROM studente
                        WHERE email = $1 AND passwrd = $2 ;";
                    $prepara = pg_prepare($conn, "query_di_verifica", $query);
                    $esito_verifica = pg_execute($conn, "query_di_verifica", array($email, $password));
                    print('query di verifica : SELECT 1
                    FROM studente
                    WHERE email = '.$email.' AND passwrd = '. $password .' ;');
                    if(pg_num_rows($esito_verifica) >= 1){
                        print('VERIFICA RIUSCITA<br>');
                        $query2 = " SELECT *
                            FROM studente
                            WHERE email = $1 AND passwrd = $2 ;";
                        $prepara = pg_prepare($conn, "fetch_info", $query2);
                        $result = pg_execute($conn, "fetch_info", array($email, $password));

                        if($result){  
                            $row = pg_fetch_assoc($result);
                            $_SESSION['nome'] = $row['nome'];
                            $_SESSION['cognome'] = $row['cognome'];    
                            $_SESSION['matricola'] = $row['matricola'];
                            $_SESSION['corso_frequentato'] = $row['corso_frequentato'];
                        
                            // segnalare al dispatcher che l'autenticazione ha avuto successo
                            $_POST['approved'] = 0;
                        }
                    } else {// Accesso non valido, reindirizzamento a pagina di errore     
                        print("STUDENTE NON TROVATO<br>");                   
                        $_POST['msg'] = "le credenziali non sono state trovate nel database";
                        $_POST['approved'] = 1;
                    }
                }
                if(substr($email, -16) === 'docenti.unimi.it'){
                    print("query per i docenti<br>");
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
                if(substr($email, -19) === 'segreteria.unimi.it'){
                    //query ad hoc per gli utenti di segreteria
                    print("query per la segreteria<br>");
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
                    } else {// Accesso non valido, reindirizzamento a pagina di errore                        
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