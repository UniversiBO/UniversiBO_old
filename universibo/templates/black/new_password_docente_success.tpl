{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table align="center" cellspacing="0" cellpadding="0" width="95%" summary="" align="center">
<tr><td>
<p class="Titolo">Cambio password docenti</p>
</td></tr>
{include file=avviso_notice.tpl}
&nbsp;<br />
<tr><td><p class="Normal">Utente: {$newPasswordDocente_username|escape:"htmlall"}<p></td></tr>
<tr><td><p class="Normal">Email: <a href="mailto:{$newPasswordDocente_email|escape:"htmlall"}">{$newPasswordDocente_email|escape:"htmlall"}</a></p></td></tr>
<tr><td><p class="Normal">Livello: {$newPasswordDocente_livelli|escape:"htmlall"}</p></td></tr>
&nbsp;<br />
<tr><td><p class="Titolo"></p>Password inviata con successo</td></tr>
</table>
{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}