<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
    
        <a class="navbar-brand" href="#">
            <img src="../immagini/logo_unimi.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Università degli Studi di Milano
        </a>
        <?php
            if (isset($_SESSION['matricola']) || isset($_SESSION['id'])) {
                //mostra la matricola o id prima del bottone di logout
                if (isset($_SESSION['matricola'])){
                echo('<p class="navbar-text">Matricola: ' . $_SESSION['matricola'] . '</p>');
                }else if (isset($_SESSION['id'])){
                    echo('<p class="navbar-text">Id: ' . $_SESSION['id'] . '</p>');
                }
                echo('<button type="button" onclick="window.location.href=\'../login.php\'" class="btn btn-outline-primary">logout</button>');
            }
        ?>

        </div>
</nav>