<?php
    //include functions.php
    include_once("lib/functions.php");
    session_start();

    //se è stato tentato  un login allora utilizzo il dispatcher per il controllo e fetchd dei dati e il reindirizzamento
    if(isset($_GET['log_try'])&& $_GET['log_try'] == 1 ){
        include_once("lib/dispatcher.php");
    }

    // se non è stato tentato un login allora controllo se ci sono variabili di sessione settate, nel caso le cancello
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    unset($_SESSION['matricola']);
    unset($_SESSION['corso_frequentato']);
    unset($_SESSION['password']);
    unset($_SESSION['cognome']);
    unset($_SESSION['nome']);
    
?>
<!doctype html>
<html lang="en">

<?php
    //include del file head.php
    include_once("lib/head.php");
?>

<body>
        <?php
            //include del file navbar.php
            include_once("lib/navbar.php");
        ?>
    <div class="login-form">
        <div style="display: flex; flex-direction: column; justify-content: center;width: 85%;align-items: center;">
            <h1 class="text-center" style="color: blue">Benvenuto nella pagina di login</h1>

            <!--form che reindirizza a questa stessa pagina ma con la variabile di log( settata a 1 se si è tentato un login )
                settata per attivare il dispatcher-->
            <form style="width: 50%; justify-content:center; align-items: center;"action="login.php?log_try=1" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="email">Email</span>
                    <input type="text" class="form-control"  name="email" placeholder="Inserisci email" aria-label="email" aria-describedby="basic-addon1">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="codice_accesso">Password</span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Inserisci password" aria-label="Password" aria-describedby="basic-addon1">
                      
                    <input class="btn btn-primary" type="submit" id="accedi" label="Accedi" style="float: right" value="Accedi">
                </div>
            </form>
        </div>
    </div>

    <!-- contenitore di fine pagina -->
    <?php include_once('lib/footer.php') ?>

</body>
<?php script_boostrap();?>
</html>