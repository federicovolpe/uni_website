<!-- form che riporta alla stessa pagina attuale ma con l'aggiunta della variabile get change password-->
<div style="margin-top: 20vh;display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <h4>vuoi cambiare password?</h4>
    <form class="c-password" action="<?php echo $_SERVER['PHP_SELF']; ?>?change_password" method="POST">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" class="btn btn-primary">Cambia</button>
    </form>
</div>
