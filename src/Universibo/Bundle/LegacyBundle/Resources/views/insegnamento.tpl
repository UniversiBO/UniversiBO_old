{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>{$ins_title|escape:"htmlall"}</h2>
{include file="bookmark_channel.tpl"}
{if $ins_ContattoDocenteUri != ""}<div class="comandi"><p><a href="{$ins_ContattoDocenteUri|escape:"htmlall"}">{$ins_ContattoDocente|escape:"htmlall"}</a></p></div>{/if}
{if $ins_homepageAlternativaLink != ""}
<p>Le informazioni del corso posso essere consultate anche alla pagina<br /><a href="{$ins_homepageAlternativaLink|escape:"htmlall"}">{$ins_homepageAlternativaLink|escape:"htmlall"}</a></p>
{/if}</div>

{if count($ins_tabella) > 0}
{include file="tabellina_due_colonne.tpl" arrayToShow=$ins_tabella}
{/if}
{if $ins_infoDidEdit != ""}<div class="comandi"><p><a href="{$ins_infoDidEdit|escape:"htmlall"|nl2br}">Modifica le informazioni dell'esame</a></p></div>{/if}

<hr />
{include file="News/latest_news.tpl"}

<hr/>
{include file="Files/show_file_titoli.tpl"}


{include file="footer_index.tpl"}