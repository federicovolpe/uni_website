<!--  script php che si occupa dell'inserimento di un esame nel database e del controllo delle variabili  -->
<?php
    //recupero delle variabili settate in post e in sessione
        $id_docente = $_SESSION['id'];
        $insegnamento = $_POST['insegnamento']; //l'insegnamento è espresso con il nome
        $date = $_POST['data'];
        
        //connessione al database
        $db = pg_connect("dbname=unimio host=localhost port=5432");
        if($db){

            //fetch id insegnamento perchè il nome dell'insegnamento non è utile ma l'id si
            $id_sql = "SELECT id FROM insegnamento WHERE nome = $1";
            $id_insegnamento = pg_prepare($db, 'prova',$id_sql);

            if($id_insegnamento){
                $id_insegnamento = pg_execute($db, 'prova', array($insegnamento));
                if($id_insegnamento){
                    $id_insegnamento = pg_fetch_assoc($id_insegnamento)['id'];
                }
            }

            
            if($id_insegnamento == ''){ // se l'insegnamento non è stato trovato allora mando un messaggio di errore
                $_POST['msg'] = "insegnamento non trovato query: SELECT id FROM insegnamento WHERE nome = ".$insegnamento;
                $_POST['approved'] = 1;
                return;
            }

            //query di inserimento per l'esame
            $sql = "INSERT INTO esami (insegnamento, docente, data) VALUES ($1, $2, $3)";
            $preparazione = pg_prepare($db , "update", $sql);

            if($preparazione){
                pg_execute($db, "update", array($id_insegnamento, $id_docente, $date));
                $_POST['msg'] = "esame inserito con successo docente: $id_docente</br>insegnamento: $id_insegnamento</br>data: $date</br>";
                $_POST['approved'] = 0;

            }else{
                $_POST['msg'] = pg_last_error();
                $_POST['approved'] = 1;
            }
        }else{
            $_POST['msg'] = "connessione al database fallita";
            $_POST['approved'] = 1;
        }
?>