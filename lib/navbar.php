<!--  script per la generazione di una navbar personalizzata per ogni utente  -->

<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="/uni_website/media/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> 
            Università degli Studi di Milano
        </a>
            <?php //creazione del riquadro contenente le informazioni dell'utente
            echo('<div class="nome-cognome">
                <style>
                    .nome-cognome {
                        border: 2px solid #1e95e8;
                        padding: 7px;
                        border-radius: 5px;
                        text-align:center;
                    }
                
                    .navbar-text {
                        font-weight: normal;
                        margin:0px;
                        padding:0px;
                    }
                </style>');
                if(isset($_SESSION['matricola']) || isset($_SESSION['id'])) {
                    //mostrare la matricola, nome cognome prima del pulsante di logout
                    if (isset($_SESSION['matricola'])){
                        echo('
                            <p class="navbar-text">Matricola: ' . $_SESSION['matricola'] . '</p>');
                    }else if (isset($_SESSION['id'])){
                        echo('
                            <p class="navbar-text">Id: ' . $_SESSION['id'] . '</p>');
                    }
                    echo( '<p class="navbar-text">'.$_SESSION['nome'] . ' ' . $_SESSION['cognome'] . '</p></div>');   
                    echo('<button type="button" onclick="window.location.href=\'../login.php\'" class="btn btn-outline-primary">logout</button>');
                }
                
            ?>
    </div>
</nav>    

    <?php messaggi_errore_post2()?>
    
    <?php //mostra il messaggio di benvenuto solo se ci si trova nelle pagine home utente
        $currentPage = basename($_SERVER['PHP_SELF']); //recupero del nome finale della pagina

        if($currentPage == 'studente.php' || $currentPage == 'docente.php' || $currentPage == 'segreteria.php'){  
            print("<div style= \"text-align: center; margin: 10px\">
                        <h1>Benvenuto ".  $_SESSION['nome']." ". $_SESSION['cognome'] ."</h1>
                    </div>");
        }
    ?>    