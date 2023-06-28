<?php
if (isset($_POST))  {
    // Retrieve the name and surname from the form submission
    $id = $_POST['id'];
    if (isset($_POST['email'])){
        $email = $_POST['email'];
    }
    if (isset($_POST['password'])){
        $password = $_POST['password'];
    }
    if (isset($_POST['nome'])){
        $nome = $_POST['nome'];
    }
    if (isset($_POST['cognome'])){
        $cognome = $_POST['cognome'];
    }
    $operazione = $_POST['operazione'];
    
    // connessione al database
    $db = pg_connect("host=localhost port=5432 dbname=unimio");

    if ($db) {
        //controllo che non ci sia già uno docente con lo stessa id
        $check = "SELECT 1
            FROM docente
            WHERE id = $1";

        $result_check = pg_prepare($db, "check", $check);
        $result_check = pg_execute($db, "check", array($id));
        $rows = pg_num_rows($result_check_rows);

        switch($operazione){
            case 'aggiungi':

                if($rows === 0){// se il risultato è vuoto allora significa che non esiste nessuno docente già registrato con queste credenziali
                        $inserzione_sql = "INSERT INTO docente (id, nome, cognome, email, passwrd) 
                                VALUES ($1, $2, $3, $4, $5)";
                        $preparato = pg_prepare($db, "inserzione", $inserzione_sql);
                        
                        if ($preparato) { //se la preparazione della query va a buon fine allora la eseguo
                            $inserito = pg_execute($db, "inserzione", array($id, $nome, $cognome, $email, $password));

                            if ($inserito) { //segnalazione con un messaggio di successo
                                $_POST['approved'] = 0;
                                $_POST['msg'] = "il docente è stato inserito con successo";
                            } else {     //segnalazione con un messaggio di fallito inserimento
                                $_POST['approved'] = 1;
                                $_POST['msg'] = pg_last_error();
                            }
                        } else { // messaggio di log nella pagina se la preparazione della query non va a buon termine
                            $_POST['approved'] = 1;
                            $_POST['msg'] = "qualcosa è andato storto nella preparazione della query.";
                        }
                }else{ //esiste già quelcuno con queste credenziali
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "Risulta già uno docente con lo stesso id o email";
                }
                break;

            case 'modifica':

                if($rows === 1){ //se il numero di righe è 1 allora lo docente risulta presente

                    $contaparametri = 2;
                    $sql = "UPDATE docente
                            SET ";
                    $array = [];  // Initialize an empty array
                    $array[] = $id;
                    if (isset($_POST['nome']) && !empty($_POST['nome'])) {
                        $sql .= "nome = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['nome'];
                    }
                    
                    if (isset($_POST['cognome']) && !empty($_POST['cognome'])) {
                        $sql .= "cognome = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['cognome'];
                    }
                    
                    if (isset($_POST['email']) && !empty($_POST['email'])) {
                        $sql .= "email = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['email'];
                    }
                    
                    if (isset($_POST['password']) && !empty($_POST['password'])) {
                        $sql .= "passwrd = $$contaparametri,";
                        $contaparametri++;
                        $array[] = $_POST['password'];
                    }
                    
                    //togliere l'ultima virgola dalla query
                    if (substr($sql, -1) === ',') {
                        $sql = substr($sql, 0, -1);
                    }
                    $sql = $sql . " WHERE id = $1";
                    print("query creata : ".$sql."<br> con l'array: ");
                    print_r($array);

                    $result = pg_prepare($db, "op_docente", $sql);
                    $esito_modifica = pg_execute($db, "op_docente", $array);
                    
                    
                    if ($esito_modifica) {//ritorno al sito con un messaggio di successo
                        $_POST['approved'] = 0;
                        $_POST['msg'] = "il docente è stato modificato con successo";
                    } else {
                        $_POST['approved'] = 1;
                        $_POST['msg'] = pg_last_error();
                    }
                }else{
                    $_POST['approved'] = 1;
                     $_POST['msg'] = "non risulta un professore con questa email e password!:res ".$result_check[0];
                }
            break;


            case 'cancella':

                if($rows === 1){

                    $cancella_sql = "DELETE FROM docente WHERE id = $1";
                    $result = pg_prepare($db, "op_docente", $cancella_sql);
                
                    if ($result) { //se la preparazione della query va a buon fine allora la eseguo
                        $cancellato = pg_execute($db, "op_docente", array($id));

                        if ($cancellato) {
                            $_POST['approved'] = 0;
                            $_POST['msg'] = "il docente ".$id." è stato cancellato con successo";
                        } else {
                            $_POST['approved'] = 1;
                            $_POST['msg'] = pg_last_error();
                        }
                    } else {
                        $_POST['approved'] = 1;
                        $_POST['msg'] = "fallita la prepaqrazione della query";
                    }

                }else{
                    $_POST['approved'] = 1;
                    $_POST['msg'] = "non risulta un professore con questa email e password";
                }
            break;

            default:
            $_POST['approved'] = 1;
            $_POST['msg'] = "l'operazione ".$operazione."non è stata riconosciuta";
        }

        pg_close($db);
    } else {
        $_POST['approved'] = 1;
        $_POST['msg'] = "connessione al db fallita";
    }
}else{
    echo("variabili post non  settate!");
}
?>
