<!-- form che riporta alla stessa pagina attuale ma con l'aggiunta della variabile get change password-->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?change_password" style="padding: 5%; justify-content: center; align-items: center; display: flex;" method="POST">
        <h4>vuoi cambiare password? </br></h4>
        <label for="password">password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" style="padding:2%;" class="btn btn-primary">Cambia</button>
</form>