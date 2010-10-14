{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Cambio password docenti</h2>
</div>
<p>Utente: {$newPasswordDocente_username|escape:"htmlall"}<p>
<p>Email: <a href="mailto:{$newPasswordDocente_email|escape:"htmlall"}">{$newPasswordDocente_email|escape:"htmlall"}</a></p>
<p>Livello: {$newPasswordDocente_livelli|escape:"htmlall"}</p>

<h2>Password inviata con successo<h2>

{include file=footer_index.tpl}