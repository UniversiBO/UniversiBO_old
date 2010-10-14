{include file=header_index.tpl}

<div class="titoloPagina">
<h2>Aggiungi il tuo commento</h2>
<p>{$FileStudentiComment_ris|escape:"htmlall"}</p>
</div>
{if $esiste_CommentoItem=="true"}
<p><a href="{$FilesStudentiComment_modifica|escape:"htmlall"}">Modifica il tuo commento</a></p>
{/if}

{include file=avviso_notice.tpl}

<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;alle informazioni sul file</a></p>

{include file=footer_index.tpl}