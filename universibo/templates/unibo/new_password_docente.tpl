{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Cambio password docenti</h2>
</div>
<p>Utente: {$newPasswordDocente_username|escape:"htmlall"}<p>
<p>Email: <a href="mailto:{$newPasswordDocente_email|escape:"htmlall"}">{$newPasswordDocente_email|escape:"htmlall"}</a></p>
<p>Livello: {$newPasswordDocente_livelli|escape:"htmlall"}</p>

<form  method="post">
<p>Controlla che i dati siano corretti!</p>  
<p>Sei sicuro di inviare una nuova password a questo utente?</p>  
<input class="submit" type="submit" name="f21_submit" id="f21_submit" value="Invia">
</form>

{include file=footer_index.tpl}